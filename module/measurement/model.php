<?php
declare(strict_types=1);
/**
 * The model file of measurement module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Guanxiying <guanxiying@easycorp.ltd>
 * @package   measurement
 * @link      https://www.zentao.net
 */
class measurementModel extends model
{
    /* SQL error message.*/
    public $errorInfo = '';

    public $programQueue = array();
    public $productQueue = array();

    /**
     * Get by measurement id.
     *
     * @param  int    $measurementID
     * @access public
     * @return object
     */
    public function getByID(int $measurementID): object
    {
        $measurement = $this->dao->select('*')->from(TABLE_BASICMEAS)->where('id')->eq($measurementID)->fetch();
        if(!$measurement) return false;

        if($measurement->collectType == 'action')
        {
            $collectConf = json_decode($measurement->collectConf);
            $measurement->collectConf         = new stdclass();
            $measurement->collectConf->action = $collectConf->action;
            $measurement->collectConf->type   = '';
            $measurement->collectConf->week   = '';
        }
        else
        {
            $measurement->collectConf = json_decode($measurement->collectConf);
            if(is_object($measurement->collectConf))
            {
                $measurement->collectConf->module = '';
                $measurement->collectConf->action = '';
            }
        }

        return $measurement;
    }

    /**
     * Get measurement list.
     *
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getList(string $browseType, int $queryID, string $orderBy, object $pager = null): object
    {
        $measurementQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('measurementQuery', $query->sql);
                $this->session->set('measurementForm', $query->form);
            }

            if($this->session->measurementQuery == false) $this->session->set('measurementQuery', ' 1 = 1');
            $measurementQuery = $this->session->measurementQuery;
        }

        return $this->dao->select('*')->from(TABLE_BASICMEAS)
            ->where('deleted')->eq(0)
            ->beginIF($browseType == 'bysearch')->andWhere($measurementQuery)->fi()
            ->orderby($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get the basic meas key value pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        $pairs = $this->dao->select('id, name')->from(TABLE_BASICMEAS)->where('deleted')->eq(0)->orderBy('purpose, `order` desc')->fetchPairs();
        return array('' => '') + $pairs;
    }

    /**
     * Get the basic meas templates.
     *
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getTemplates(object $pager = null): object
    {
        return $this->dao->select('*')->from(TABLE_MEASTEMPLATE)->page($pager)->fetchAll('id');
    }

    /**
     * Get template by ID.
     *
     * @param  int   $templateID
     * @access public
     * @return object
     */
    public function getTemplateByID(int $templateID): object
    {
        return $this->dao->select('*')->from(TABLE_MEASTEMPLATE)->where('id')->eq($templateID)->fetch();
    }

    /**
     * Get the list of reports for the measurement.
     *
     * @param  string $type
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReports(string $type, object $pager = null): array
    {
        if($type == 'pairs') $pager = null;
        $reports = $this->loadModel('report')->getReportList('cmmi', '', $pager);

        $pairs = array();
        foreach($reports as $report)
        {
            $name = json_decode($report->name, true);
            if(!is_array($name) or empty($name)) $name[$this->app->getClientLang()] = $report->name;
            $reportName = !isset($name[$this->app->getClientLang()]) ? $name['en'] : $name[$this->app->getClientLang()];
            $report->name = $reportName;

            if($type == 'pairs') $pairs[$report->id] = $reportName;
        }

        if($type == 'pairs') return array('' => '') + $pairs;

        return $reports;
    }

    /**
     * Get parameters in SQL statement.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getSqlVars(string $sql): array
    {
        $sql = trim(stripslashes(trim($sql)), ';') . ' ';
        preg_match_all('/\$(\w+)/i', $sql, $out);
        return array_unique($out[1]);
    }

    /**
     * Get the value of the basic measure by ID.
     *
     * @param  int    $measurementID
     * @param  string $params
     * @access public
     * @return string
     */
    public function getBasicMeasValue(int $measurementID, string $params = ''): string
    {
        $measurement = $this->getByID($measurementID);
        return $this->execSqlMeasurement($measurement, $params);
    }

    /**
     * Get unexecuted queues.
     *
     * @access public
     * @return object
     */
    public function getUnexecutedQueues(): object
    {
        return $this->dao->select('*')->from(TABLE_MEASQUEUE)->where('status')->eq('')->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get the key value pairs of all modules.
     *
     * @access public
     * @return array
     */
    public function getModulePairs(): array
    {
        $this->app->loadLang('group');

        $pairs = array();
        foreach($this->lang->resource as $moduleName => $module)
        {
            if(isset($this->lang->$moduleName) and isset($this->lang->$moduleName->common))
            {
                $pairs[$moduleName] = $this->lang->$moduleName->common;
            }
            else
            {
                $pairs[$moduleName] = $moduleName;
            }
        }

        return array('' => '') + $pairs;
    }

    /**
     * Get module actions.
     *
     * @param  string $moduleName
     * @access public
     * @return array
     */
    public function getModuleActions($moduleName = ''): array
    {
        $this->app->loadLang('group');
        $this->app->loadLang($moduleName);
        $pairs = array();
        foreach($this->lang->resource->$moduleName as $action => $actionName)
        {
            $pairs[$action] = $this->lang->$moduleName->$actionName;
        }
        return $pairs;
    }

    /**
     * Get trigger options.
     *
     * @access public
     * @return array
     */
    public function getTriggerOptions(): array
    {
        $this->app->loadLang('group');
        $options = array();
        foreach($this->config->measurement->triggers as $trigger)
        {
            list($module, $method) = explode('.', $trigger);
            $this->app->loadLang($module);
            $methodAlias = $method;
            $text = $this->lang->{$module}->common;
            if(isset($this->lang->resource->$module) and isset($this->lang->resource->$module->$method)) $methodAlias = $this->lang->resource->$module->$method;
            $text .= '/' . $this->lang->$module->$methodAlias;
            $options[$trigger] = $text;
        }
        $options = array_merge($this->lang->measurement->actions, $options);
        return $options;
    }

    /**
     * Create derived measures.
     *
     * @access public
     * @return int
     */
    public function createDerivation(): int
    {
        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('deleted', 0)
            ->remove('definitionMethods')
            ->get();

        $data = $this->processCollectConf($data);
        $data->definition = $this->post->definitionMethods;

        $this->dao->insert(TABLE_DERIVEMEAS)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->measurement->createderivation->requiredFields,'notempty')
            ->exec();

        if(!dao::isError())return $this->dao->lastInsertID();
    }

    /**
     * Edit derived measures.
     *
     * @param  int    $measurementID
     * @access public
     * @return int
     */
    public function editDerivation($measurementID): int
    {
        $data = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('measurementID,definitionMethods')
            ->get();

        $data = $this->processCollectConf($data);
        $data->definition = $this->post->definitionMethods;

        $this->dao->update(TABLE_DERIVEMEAS)
            ->data($data)
            ->autoCheck()
            ->batchCheck($this->config->measurement->editderivation->requiredFields, 'notempty')
            ->where('id')->eq((int)$measurementID)
            ->exec();

        if(!dao::isError()) return $measurementID;
    }

    /**
     * Create basic measures.
     *
     * @access public
     * @return int
     */
    public function createBasic(): int
    {
        $data = fixer::input('post')
            ->add('engine', 'php')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $data = $this->processCollectConf($data);

        $this->dao->insert(TABLE_BASICMEAS)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->measurement->createbasic->requiredFields, 'notempty')
            ->checkIF(!empty($data->code), 'code', 'unique')
            ->exec();

        if(!dao::isError())
        {
            $insertID = $this->dao->lastInsertID();
            $order    = $insertID * 5;
            $this->dao->update(TABLE_BASICMEAS)->set('order')->eq($order)->where('id')->eq($insertID)->exec();
            return $insertID;
        }
    }

    /**
     * Edit basic measures.
     *
     * @param  int    $measurementID
     * @access public
     * @return int
     */
    public function editBasic($measurementID): int
    {
        $data = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('measurementID')
            ->get();

        $data = $this->processCollectConf($data);
        $this->dao->update(TABLE_BASICMEAS)
            ->data($data)
            ->autoCheck()
            ->batchCheck($this->config->measurement->editbasic->requiredFields, 'notempty')
            ->where('id')->eq((int)$measurementID)
            ->exec();

        if(!dao::isError()) return $measurementID;
    }

    /**
     * Batch edit basic measurement.
     *
     * @access public
     * @return bool
     */
    public function batchEdit(): bool
    {
        $data           = fixer::input('post')->get();
        $oldMeasurement = $this->dao->select('id,code,name')->from(TABLE_BASICMEAS)->where('id')->in(array_keys($data->code))->fetchAll('id');
        $newMeasurement = array();
        $account        = $this->app->user->account;
        $editDate       = helper::now();

        $codeList = array();
        foreach($data->code as $id => $code)
        {
            $newMeasurement[$id]['code']       = trim($code);
            $newMeasurement[$id]['purpose']    = $data->purpose[$id];
            $newMeasurement[$id]['scope']      = $data->scope[$id];
            $newMeasurement[$id]['object']     = $data->object[$id];
            $newMeasurement[$id]['name']       = trim($data->name[$id]);
            $newMeasurement[$id]['unit']       = trim($data->unit[$id]);
            $newMeasurement[$id]['definition'] = trim($data->definition[$id]);
            $newMeasurement[$id]['editedBy']   = $account;
            $newMeasurement[$id]['editedDate'] = $editDate;

            if(in_array($code, $codeList))    helper::end(js::error(sprintf($this->lang->measurement->codeExistence, $code)));
            if(empty($code))                  helper::end(js::error(sprintf($this->lang->measurement->codeEmpty, $id)));
            if(empty($data->name[$id]))       helper::end(js::error(sprintf($this->lang->measurement->nameEmpty, $id)));
            if(empty($data->unit[$id]))       helper::end(js::error(sprintf($this->lang->measurement->unitEmpty, $id)));
            if(empty($data->definition[$id])) helper::end(js::error(sprintf($this->lang->measurement->definitionEmpty, $id)));
            $codeList[$id] = $code;
        }

        foreach($newMeasurement as $id => $measurement)
        {
            $this->dao->update(TABLE_BASICMEAS)->data($measurement)->check('code', 'unique', "id != $id")->batchCheck($this->config->measurement->editbasic->requiredFields, 'notempty')->where('id')->eq($id)->exec();
            if(dao::isError()) return false;
        }
        return true;
    }

    /**
     * Process timing configuration.
     *
     * @param  object $data
     * @access public
     * @return object
     */
    public function processCollectConf($data): object
    {
        if($data->collectType == 'crontab')
        {
            if($data->config['type'] == 'week')
            {
                unset($data->config['month']);
                if(isset($data->config['week'])) $data->config['week'] = join(',', $data->config['week']);
            }
            if($data->config['type'] == 'month')
            {
                unset($data->config['week']);
                if(isset($data->config['month'])) $data->config['month'] = join(',', $data->config['month']);
            }

            $data->collectConf = json_encode($data->config);
        }
        else
        {
            $data->collectConf = json_encode(array('action' => $this->post->action));
        }

        unset($data->config);
        unset($data->module);
        unset($data->action);

        return $data;
    }

    /**
     * Init crontab queue from measurement table.
     *
     * @access public
     * @return void
     */
    public function initCrontabQueue(): void
    {
        $measList = $this->dao->select('*')->from(TABLE_BASICMEAS)
            ->where('deleted')->eq(0)
            ->andWhere('collectType')->eq('crontab')
            ->fetchAll('id');

        foreach($measList as $meas) $this->createMeasQueue($meas);
    }

    /**
     * Create meas queue.
     *
     * @param object $meas
     * @param int    $program
     * @param int    $product
     * @param int    $project
     * @access public
     * @return void
     */
    public function createMeasQueue(object $meas, int $program = 0, int $product = 0, int $project = 0): void
    {
        if(empty($this->programQueue)) $this->programQueue = $this->getProgramQueue();
        if(empty($this->productQueue)) $this->productQueue = $this->getProductQueue();

        $day         = date('d');
        $week        = date('w');
        $collectConf = json_decode($meas->collectConf);

        $params = json_decode($meas->params);

        if(isset($collectConf->week) or isset($collectConf->month))
        {
            $queue = new stdclass();
            $queue->type        = $collectConf->type;
            $queue->mid         = $meas->id;
            $queue->execTime    = $meas->execTime;
            $queue->createdDate = helper::now();

            foreach($this->productQueue as $programID => $productList)
            {
                if($meas->scope != 'product') continue;

                $params = array();
                $params['project'] = $programID;

                foreach($productList as $productID)
                {
                    $params['product'] = $productID;
                    $queue->params = json_encode($params);
                    $this->dao->insert(TABLE_MEASQUEUE)->data($queue)->exec();
                }
            }

            foreach($this->programQueue as $projectID => $projectIdList)
            {
                if($meas->scope != 'project') continue;

                $params = array();
                $params['project'] = $projectID;

                $queue->params  = json_encode($params);
                $this->dao->insert(TABLE_MEASQUEUE)->data($queue)->exec();
            }
        }
    }

    /**
     * Exec queue.
     *
     * @param  object $queue
     * @access public
     * @return void
     */
    public function execQueue(object $queue): void
    {
        /* Create basic meas recrods. */
        $params = json_decode($queue->params);
        $meas   = $this->getByID($queue->mid);
        $this->createBasicMeasRecord($meas, $params, $queue);
        $this->dao->update(TABLE_MEASQUEUE)->set('status')->eq('executed')->where('id')->eq($queue->id)->exec();
    }

    /**
     * Create meas record.
     *
     * @param  object $meas
     * @param  object $params
     * @param  object $queue
     * @access public
     * @return void
     */
    public function createBasicMeasRecord(object $meas, object $params, object $queue): void
    {
        $record = new stdclass();
        $record->type     = $queue->type;
        $record->mid      = $queue->mid;
        $record->date     = helper::today();
        $record->year     = date('Y');
        $record->month    = date('Ym');
        $record->week     = date('W');
        $record->day      = date('Ymd');
        $record->value    = $this->getBasicMeasValue($meas->id, $params);
        $record->measCode = $meas->code;
        $record->params   = json_encode($params);

        if(is_object($params))
        {
            $record->project = isset($params->project) ? $params->project : 0;
        }
        elseif(is_array($params))
        {
            $record->project = isset($params['project']) ? $params['project'] : 0;
        }

        $this->dao->insert(TABLE_MEASRECORDS)->data($record)->exec();
    }

    /**
     * Create basic meas record by action.
     *
     * @param  object $meas
     * @param  object $record
     * @param  string $pp
     * @access public
     * @return void
     */
    public function createBasicMeasRecordByAction(object $meas, object $record, string $pp = ''): void
    {
        $params = json_decode($meas->params);
        if(empty($params))
        {
            $record->value = $this->getBasicMeasValue($meas->id);
            $record = $this->dao->select('*')->from(TABLE_MEASRECORDS)->where('type')->eq('basic')->andWhere('mid')->eq($meas->id)->fetch();
            if(!empty($record)) return;

            $this->dao->insert(TABLE_MEASRECORDS)->data($record)->exec();
        }
        else
        {
            foreach($params as $param)
            {
                $hasProgramParam = ($param->varName == 'project') ? true : false;
                $hasProductParam = ($param->varName == 'product') ? true : false;
                $hasProjectParam = ($param->varName == 'sprint')  ? true : false;
            }

            foreach($pp as $queryParams)
            {
                if($hasProgramParam) $record->project = $queryParams['project'];
                if($hasProductParam) $record->product = $queryParams['product'];
                if($hasProjectParam) $record->sprint  = $queryParams['sprint'];

                $record = $this->dao->select('*')->from(TABLE_MEASRECORDS)
                    ->where('type')->eq('basic')
                    ->andWhere('mid')->eq($meas->id)
                    ->beginIF($hasProgramParam)->andWhere('project')->eq($record->project)->fi()
                    ->beginIF($hasProductParam)->andWhere('product')->eq($record->product)->fi()
                    ->beginIF($hasProjectParam)->andWhere('sprint')->eq($record->sprint)->fi()
                    ->fetch();
                if(!empty($record)) continue;

                $record->value = $this->getBasicMeasValue($meas->id, $queryParams);
                $this->dao->insert(TABLE_MEASRECORDS)->data($record)->exec();
            }
        }
    }

    /**
     * Get program list for measurement queue.
     *
     * @access public
     * @return object
     */
    public function getProgramQueue(): object
    {
        return $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('project')->andWhere('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * Get product list for measurement queue.
     *
     * @access public
     * @return array
     */
    public function getProductQueue(): array
    {
        return $this->dao->select('program,id')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchGroup('program', 'id');
    }

    /**
     * Save template.
     *
     * @access public
     * @return int
     */
    public function saveTemplate(): int
    {
        $attributes   = array();
        $attributes[] = 'span|data-holder|Text';
        $attributes[] = 'span|contenteditable|Text';
        $attributes[] = 'span|data-holder-edit|Text';
        $attributes[] = 'div|data-holder|Text';
        $attributes[] = 'div|contenteditable|Text';
        $attributes[] = 'div|data-holder-edit|Text';

        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->stripTags($this->config->measurement->editor->createtemplate['id'], $this->config->allowedTags, $attributes)
            ->remove('uid')
            ->get();

        $this->dao->insert(TABLE_MEASTEMPLATE)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->measurement->createtemplate->requiredFields,'notempty')
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
    }

    /**
     * Edit template.
     *
     * @param  int    $templateID
     * @access public
     * @return array
     */
    public function editTemplate(int $templateID): array
    {
        $attributes   = array();
        $attributes[] = 'span|data-holder|Text';
        $attributes[] = 'span|contenteditable|Text';
        $attributes[] = 'span|data-holder-edit|Text';
        $attributes[] = 'div|data-holder|Text';
        $attributes[] = 'div|contenteditable|Text';
        $attributes[] = 'div|data-holder-edit|Text';

        $oldTemplate = $this->getTemplateByID($templateID);
        $template = fixer::input('post')
            ->stripTags($this->config->measurement->editor->edittemplate['id'], $this->config->allowedTags, $attributes)
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_MEASTEMPLATE)->data($template)
            ->where('id')->eq($templateID)
            ->autoCheck()
            ->batchCheck($this->config->measurement->edittemplate->requiredFields,'notempty')
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTemplate, $template);
    }

    /**
     * Build param control of one param of measurement or report.
     *
     * @param  string    $name
     * @param  string    $controlType
     * @param  string    $value
     * @param  string    $optionType
     * @param  string    $controlID
     * @param  string    $mode
     * @access public
     * @return string
     */
    public function buildParamControl(string $name, string $controlType, string $value = '', string $optionType = '', string $controlID = '', string $mode = 'design'): string
    {
        if($mode == 'design') $name = $name . '[]';
        if($mode == 'report') $name = "params[$controlID][$name]";
        if($controlType == 'date')
        {
            $control = html::input($name, $value, "class='form-control form-date' data-name='$name' data-control='$controlID'");
        }
        elseif($controlType == 'select')
        {
            $options = $this->getControlOptions($optionType);
            $control = html::select($name, $options, $value, "class='form-control chosen' data-name=$name data-control=$controlID");
        }
        else
        {
            $control = html::input($name, $value, "class='form-control' data-name=$name data-control=$controlID");
        }

        return $control;
    }

    /**
     * Build param control of one param of measurement or report.
     *
     * @param  string $param
     * @param  object $setting
     * @access public
     * @return string
     */
    public function showParam(string $param, object $setting): string
    {
        if($setting->varType == 'select')
        {
            $options = $this->getControlOptions($setting->options);
            return zget($options, $param, '');
        }

        return $param;
    }

    /**
     * Get opertions of one control.
     *
     * @param  string $optionType
     * @access public
     * @return array
     */
    public function getControlOptions($optionType): array
    {
        static $optionList = array();

        $options = array();
        if(!isset($optionList[$optionType]))
        {
            if($optionType == 'project')
            {
                $options = array('' => '') + $this->loadModel('project')->getPairsByProgram();
            }
            elseif($optionType == 'product')
            {
                $options = array('' => '') + $this->loadModel('product')->getPairs('nocode');
            }
            elseif($optionType == 'sprint')
            {
                $options = array('' => '') + $this->loadModel('execution')->getPairs();
            }
            elseif($optionType == 'user')
            {
                $options = $this->loadModel('user')->getPairs('noletter');
            }
            elseif(strpos($optionType, '.') !== false)
            {
                list($moduleName, $varListName) = explode('.', $optionType);
                $this->app->loadLang($moduleName);
                $varListName .= 'List';
                $options = $this->lang->$moduleName->$varListName;
                unset($options[0]);
                unset($options['']);
                $options = array('' => '') + $options;
            }
            else
            {
                $options = array('' => '');
            }

            $optionList[$optionType] = $options;
        }
        else
        {
            $options = $optionList[$optionType];
        }

        return $options;
    }

    /**
     * Get params of an object parsed from holder.
     *
     * @param  object $sysData
     * @access public
     * @return array
     */
    public function getParams($sysData): array
    {
        if($sysData->type == 'basic')
        {
            $measurement = $this->getByID($sysData->measurementID);
            return $measurement ? json_decode($measurement->params, true) : array();
        }

        if($sysData->type == 'report') return $this->getReportParams($sysData->reportID);
    }

    /**
     * Get report's params.
     *
     * @param  int    $reportID
     * @access public
     * @return array
     */
    public function getReportParams($reportID): array
    {
        $params = array();
        $report = $this->loadModel('report')->getReportByID($reportID);
        $vars = json_decode($report->vars, true);

        if($vars and isset($vars['varName']))
        {
            foreach($vars['varName'] as $key => $varName)
            {
                $param             = array();
                $param['varName']  = $varName;
                $param['showName'] = isset($vars['showName'][$key]) ? $vars['showName'][$key] : '';
                $param['varType']  = isset($vars['requestType'][$key]) ? $vars['requestType'][$key] : '';
                $param['options']  = isset($vars['selectList'][$key]) ? $vars['selectList'][$key] : '';
                $param['default']  = isset($vars['default'][$key]) ? $vars['default'][$key] : '';

                $params[] = $param;
            }
        }
        return $params;
    }

    /**
     * Parse measurement or report from holdervalue.
     *
     * @param  string    $holderValue
     * @access public
     * @return object
     */
    public function parseHolderValue($holderValue): object
    {
        $holderValue = ltrim($holderValue, '{');
        $holderValue = rtrim($holderValue, '}');

        $sysData = new stdclass();

        list($type, $value) = explode(':', $holderValue);
        list($sysData->type, $sysData->controlID) = explode('_', $type);
        if($sysData->type == 'report')
        {
            list($sysData->reportType, $sysData->reportID) = explode('.', $value);
        }
        else
        {
            list($sysData->type, $sysData->measurementID) = explode('.', $value);
        }

        return $sysData;
    }

    /**
     * Parsing template content.
     *
     * @param  string $content
     * @access public
     * @return content
     */
    public function parseTemplateContent($content): content
    {
        $content = $this->parseMeasurementContent($content);
        $content = $this->parseReportContent($content);

        return $content;
    }

    /**
     * Parse measurement content with post params.
     *
     * @param  string    $content
     * @access public
     * @return content
     */
    public function parseMeasurementContent($content): content
    {
        $pattern = '/\{measurement_.*\:\w+\.\d+\}/U';
        preg_match_all($pattern, $content, $matches);

        $matches = isset($matches[0]) ? $matches[0] : array();
        if(!empty($matches))
        {
            foreach($matches as $match)
            {
                $measurement = $this->parseHolderValue($match);
                if($measurement->type == 'basic')
                {
                    $params    = zget($this->post->params, $measurement->controlID);
                    $replace[] = $this->getBasicMeasValue($measurement->measurementID, $params);
                }
                else
                {
                    $replace[] = $this->getDeriveMeasValue($measurement->measurementID, $measurement->controlID);
                }
            }

            $content = str_replace($matches, $replace, $content);
        }

        return $content;
    }

    /**
     * Parse report content with post params.
     *
     * @param  string  $content
     * @access public
     * @return string
     */
    public function parseReportContent($content): string
    {
        $pattern = '/\{report_.*\:\w+\.\d+\}/U';
        preg_match_all($pattern, $content, $matches);

        $matches = isset($matches[0]) ? $matches[0] : array();
        if(!empty($matches))
        {
            foreach($matches as $match)
            {
                $param = $this->parseHolderValue($match);

                $replace[] = $this->getReportContent($param->reportID, $param->controlID);
            }

            $content = str_replace($matches, $replace, $content);
        }
        return $content;
    }

    /**
     * Get report content.
     *
     * @param  int    $reportID
     * @param  int    $controlID
     * @access public
     * @return void
     */
    public function getReportContent($reportID, $controlID): void
    {
        global $lang;
        $report = $this->loadModel('report')->getReportByID($reportID);
        $sql    = $report->sql;
        $params = $this->getReportParams($reportID);

        $this->session->set('sqlLangs', $report->langs);

        foreach($params as $param)
        {
            $varName     = $param['varName'];
            $paramValues = zget($this->post->params, $controlID, '');
            $paramValue  = zget($paramValues, $param['varName'], '');
            $sql         = str_replace('$' . $param['varName'], $this->dbh->quote($paramValue), $sql);
        }

        $sql           = $this->report->replaceTableNames($sql);
        $tableAndField = $this->report->getTables($sql);
        $tables        = $tableAndField['tables'];
        $fields        = $tableAndField['fields'];
        $dataList      = $this->dao->query($sql)->fetchAll();

        if(empty($dataList)) return '';

        $moduleNames = array();
        foreach($tables as $table)
        {
            if(strpos($table, $this->config->db->prefix) === false) continue;
            $module = str_replace($this->config->db->prefix, '', $table);
            if($module == 'case')   $module = 'testcase';
            if($module == 'module') $module = 'tree';

            /* Code for workflow.*/
            if(strpos($module, 'flow_') !== false)
            {
                $moduleName = substr($module, 5);
                $flowFields = $this->loadModel('workflowfield')->getFieldPairs($moduleName);
                $this->lang->$moduleName = new stdclass();

                foreach($flowFields as $flowField => $fieldName)
                {
                    if(!$flowField) continue;
                    $this->lang->$moduleName->$flowField = $fieldName;
                }
                $moduleNames[$table] = $module;
            }
            else
            {
                if($this->app->loadLang($module)) $moduleNames[$table] = $module;
            }
        }

        $step          = $report->step;
        $data          = (array)current($dataList);
        $moduleNames   = array_reverse($moduleNames, true);
        $reverseFields = array_reverse($fields, true);
        $mergeFields   = $this->report->mergeFields(array_keys($data), $reverseFields, $moduleNames);

        if($step == 2)
        {
            $condition = json_decode($report->params, true);
            if(!empty($condition['isUser'])) $this->view->users = $this->loadModel('user')->getPairs('noletter');

            $groupLang['group1'] = $this->report->getGroupLang($condition['group1'], $reverseFields, $moduleNames);
            $groupLang['group2'] = $this->report->getGroupLang($condition['group2'], $reverseFields, $moduleNames);
            list($headers, $reportData) = $this->report->processData($dataList, $condition);

            $headerNames = $this->report->getHeaderNames($fields, $moduleNames, $condition);
        }

        $fields = empty($mergeFields) ? array() : $mergeFields;

        ob_start();
        include '../report/ext/view/reportdata.html.php';
        $reportContent = ob_get_contents();
        ob_clean();
        return $reportContent;
    }

    /**
     * Get components from template.
     *
     * @param  string    $content
     * @access public
     * @return array
     */
    public function getTemplateComponents($content): array
    {
        $pattern = '/\{measurement_.*\:\w+\.\d+\}/U';
        preg_match_all($pattern, $content, $matches);
        $components = array();
        foreach($matches[0] as $holder) $components[] = $this->parseHolderValue($holder);
        $pattern = '/\{report_.*\:\w+\.\d+\}/U';
        preg_match_all($pattern, $content, $matches);
        foreach($matches[0] as $holder) $components[] = $this->parseHolderValue($holder);
        return $components;
    }

    /**
     * Get templatePairs.
     *
     * @access public
     * @return array
     */
    public function getTemplatePairs($model = ''): array
    {
        return $this->dao->select('id, name')->from(TABLE_MEASTEMPLATE)
            ->where('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->fetchPairs();
    }

    /**
     * Get measure file.
     *
     * @param  int    $measurementID
     * @access public
     * @return string
     */
    public function getMeasureFile($measurementID): string
    {
        if(!$measurementID) return false;
        return $this->app->getTmpRoot() . 'model' . DS . "ext_{$measurementID}.php";
    }

    /**
     * Get measure class.
     *
     * @param  int    $measurementID
     * @access public
     * @return string
     */
    public function getMeasureClass($measurementID): string
    {
        if(!$measurementID) return false;
        return "ext_{$measurementID}";
    }

    /**
     * Process post params.
     *
     * @access public
     * @return array
     */
    public function processPostParams(): array
    {
        return array_combine($this->post->varName, $this->post->queryValue);
    }

    /**
     * Create sql function.
     *
     * @param  string $sql
     * @param  object $measurement
     * @access public
     * @return array
     */
    public function createSqlFunction($sql, $measurement): array
    {
        $measFunction = $this->getSqlFunctionName($measurement);
        $postFunction = $this->parseSqlFunction($sql);
        if(!$measFunction or !$postFunction) return array('result' => 'fail', 'errors' => $this->lang->measurement->tips->nameError);

        $sql = str_replace($postFunction, $measFunction, $sql);

        try
        {
            $this->dbh->exec("DROP FUNCTION IF EXISTS `$measFunction`");
            $result = $this->dbh->exec($sql);
        }
        catch(PDOException $exception)
        {
            $message = sprintf($this->lang->measurement->tips->createError, $exception->getMessage());
            return array('result' => 'fail', 'errors' => $message);
        }

        return array('result' => 'success');
    }

    /**
     * Parsing SQL function.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function parseSqlFunction($sql): string
    {
        $pattern = "/create\s+function\s+`{0,1}([\$,a-z,A-z,_,0-9,\(,|)]+`{0,1})\(+/Ui";
        preg_match_all($pattern, $sql, $matches);

        if(empty($matches[1][0])) return null;
        return trim($matches[1][0], '`');
    }

    /**
     * Parsing SQL params.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function parseSqlParams($sql): array
    {
        $pattern = "/create\s+function.*\(([\$,a-z,A-z,_\,\(,\(),\s,0-9]+)\)+/Ui";
        preg_match_all($pattern, $sql, $matches);
        if(empty($matches[1][0])) return array();

        $paramItem = array();
        $paramItem['showName']     = '';
        $paramItem['varType']      = '';
        $paramItem['options']      = '';
        $paramItem['defaultValue'] = '';
        $paramItem['queryValue']   = '';

        $paramList = explode(',', $matches[1][0]);
        foreach($paramList as $param)
        {
            $param = trim($param);
            $paramItem['varName'] = substr($param, 0, strpos($param, ' '));
            $params[] = $paramItem;
        }
        return $params;
    }

    /**
     * Get sql function name of a measurement.
     *
     * @param  object    $measurement
     * @access public
     * @return string
     */
    public function getSqlFunctionName($measurement): string
    {
        if(!$measurement) return '';
        return strtolower("qc_{$measurement->code}");
    }

    /**
     * Exec a sql measurement.
     *
     * @param  object    $measurement
     * @param  array     $vars
     * @access public
     * @return int|string
     */
    public function execSqlMeasurement($measurement, $vars, $action = 'save'): int|string
    {
        $function = $this->getSqlFunctionName($measurement);
        if(!$function)
        {
            $this->errorInfo = $this->lang->measurement->tips->nameError;
            return false;
        }

        $vars     = (array) $vars;
        foreach($vars as $key => $param)
        {
            if(is_object($param))
            {
                unset($vars[$key]);
                continue;
            }

            $vars[$key] = $this->dbh->quote($param);
        }

        $params = join(',', $vars);

        try
        {
            $result = $this->dbh->query("select $function($params)")->fetch(PDO::FETCH_NUM);
        }
        catch(PDOException $exception)
        {
            $this->errorInfo = $exception->getMessage();
            return false;
        }

        $queryResult = isset($result[0]) . $measurement->unit ? $result[0] : null;
        if($action == 'save') $this->loadModel('measrecord')->save($measurement, $vars, $queryResult);
        return $queryResult;
    }

    /**
     * Build template form.
     *
     * @param  string $components
     * @access public
     * @return string
     */
    public function buildTemplateForm($components): string
    {
        $form = "";
        foreach($components as $component)
        {
            $params = $this->getParams($component);
            if($component->type  == 'basic')
            {
                $measurement = $this->getByID($component->measurementID);

                if(!empty($params))
                {
                    $form .="<fieldset>";
                    $form .="<h4>{$measurement->name}</h4>";
                    foreach($params as $param)
                    {
                        $form .="<div class='form-group'>";
                        $form .="<label class='col-md-3'>";
                        $form .=$param['showName'];
                        $form .="</label>";
                        $form .="<div class='col-md-9'>";
                        $form .=$this->buildParamControl($param['varName'], $param['varType'], $param['defaultValue'], $param['options'], $component->controlID, 'report');
                        $form .="</div>";
                        $form .="</div>";
                    }
                    $form .="<fieldset>";
                }
            }
            if($component->type  == 'report')
            {
                $report = $this->loadModel('report')->getReportByID($component->reportID);
                if(!empty($params))
                {
                    //if(count($params) == 1 and $params[0]['varName'] == 'program') continue;

                    $form .="<fieldset>";
                    $form .="<legend>{$report->name[$this->app->clientLang]}</legend>";
                    foreach($params as $param)
                    {
                        $form .="<div class='form-group'>";
                        $form .="<label class='col-md-1 col-sm-2'>";
                        $form .=$param['showName'];
                        $form .="</label>";
                        $form .="<div class='col-md-6 col-sm-10'>";
                        $form .=$this->buildParamControl($param['varName'], $param['varType'], zget($param, 'defaultValue', ''), $param['options'], $component->controlID, 'report');
                        $form .="</div>";
                        $form .="</div>";
                    }
                    $form .="<fieldset>";
                }
            }
        }
        return $form;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID): void
    {
        $this->config->measurement->search['actionURL'] = $actionURL;
        $this->config->measurement->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->measurement->search);
    }
}
