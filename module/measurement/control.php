<?php
declare(strict_types=1);
/**
 * The control file of measurement module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Guanxiying <guanxiying@easycorp.ltd>
 * @package   measurement
 * @link      https://www.zentao.net
 */
class measurement extends control
{
    /**
     * Construct.
     *▫
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /* Set measurement menu group. */
        $this->projectID = isset($_GET['PRJ']) ? $_GET['PRJ'] : 0;
        if(!$this->projectID)
        {
            $this->lang->navGroup->measurement = 'system';
            $this->lang->navGroup->report      = 'system';
        }
    }

    /**
     * Browse measurements.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $browseType = 'all', int $param = 0, string $orderBy = 'purpose,order_desc', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        $this->loadModel('datatable');

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('measurement', 'browse', "browseType=bysearch&queryID=myQueryID");
        $this->measurement->buildSearchForm($actionURL, $queryID);

        $this->view->title           = $this->lang->measurement->browse;
        $this->view->position[]      = $this->lang->measurement->browse;

        $this->view->pager           = $pager;
        $this->view->recTotal        = $recTotal;
        $this->view->recPerPage      = $recPerPage;
        $this->view->pageID          = $pageID;
        $this->view->orderBy         = $orderBy;
        $this->view->browseType      = $browseType;
        $this->view->param           = $param;
        $this->view->measurementList = $this->measurement->getList($browseType, $queryID, $orderBy, $pager);
        $this->display();
    }

    /**
     * Create basic measurement.
     *
     * @access public
     * @return void
     */
    public function createBasic()
    {
        if($_POST)
        {
            $measurementID = $this->measurement->createBasic();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('measurement', $measurementID, 'createBasic');

            $response = array();
            $response['result']  = 'success';
            $response['message'] = $this->lang->measurement->saveSuccess;
            $response['locate']  = $this->session->measList ? $this->session->measList : $this->createLink('measurement', 'browse', "basic");

            return $this->send($response);
        }

        $this->view->title      = $this->lang->measurement->create;
        $this->view->position[] = $this->lang->measurement->create;

        $this->view->moduleList     = $this->measurement->getModulePairs();
        $this->view->triggerOptions = $this->measurement->getTriggerOptions();
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * Delete measurement.
     *
     * @param  int    $measurementID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete(int $measurementID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->measurement->confirmDelete, $this->createLink('measurement', 'delete', "measurementID=$measurementID&confirm=yes"), ''));
        }
        else
        {
            $this->measurement->delete(TABLE_BASICMEAS, $measurementID);

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }

            return print(js::locate(inlink('browse'), 'parent'));
        }
    }

    /**
     * Edit basic measurement.
     *
     * @param  int    $measurementID
     * @access public
     * @return void
     */
    public function editBasic(int $measurementID)
    {
        $measurement = $this->measurement->getByID($measurementID);

        if($_POST)
        {
            $measurementID = $this->measurement->editBasic($measurementID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('measurement',$measurementID,'editBasic');

            $response = array();
            $response['result']  = 'success';
            $response['message'] = $this->lang->measurement->saveSuccess;
            $response['locate']  = $this->session->measList ? $this->session->measList : $this->createLink('measurement', 'browse', "basic");
            return $this->send($response);
        }

        $this->view->title          = $this->lang->measurement->editBasic;
        $this->view->position[]     = $this->lang->measurement->editBasic;
        $this->view->measurement    = $measurement;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->triggerOptions = $this->measurement->getTriggerOptions();
        $this->display();
    }

    /**
     * Search measurements.
     *
     * @param  string $types
     * @param  int    $measurementID
     * @access public
     * @return void
     */
    public function searchMeas(string $types, int $measurementID)
    {
        if($types == 'derivemeas')
        {
            $basicMeasurement = $this->measurement->getPairs();
            $derivation       = $this->measurement->getByID('derivation', $measurementID);
            $definition       = json_decode($derivation->definition,true);

            $definitionItem = array();
            $definitionForm = array();
            foreach($definition as $key => $value)
            {
                if(preg_match('/\{|\}/', $value))
                {
                    $basicmeasID      =  preg_replace('/\{|\}/', '', $value);
                    $basicmeasName    = $basicMeasurement[$basicmeasID];
                    $definitionItem[] = $basicmeasName;
                    $definitionForm[] = '通过接口获';
                }
                else
                {
                    $methodName = $value;
                    if($methodName == '*') $methodName = 'x';
                    if($methodName == '/') $methodName = '÷';

                    $definitionItem[] = $methodName;
                    $definitionForm[] = $value;
                }
            }

            $returnData = array();
            $returnData['definitionItem'] = $definitionItem;
            $returnData['definitionForm'] = $definitionForm;
        }
    }

    /**
     * Browse template.
     *
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function template(string $type = 'complex', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if($type == 'complex') $templates = $this->measurement->getTemplates($pager);
        if($type == 'single')
        {
            $this->app->loadLang('report');
            $templates = $this->measurement->getReports('list', $pager);
        }

        $this->view->title      = $this->lang->measurement->template;
        $this->view->position[] = $this->lang->measurement->template;
        $this->view->type       = $type;
        $this->view->templates  = $templates;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create report template.
     *
     * @access public
     * @return void
     */
    public function createTemplate()
    {
        if($_POST)
        {
            $this->measurement->saveTemplate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->createLink('measurement', 'template');
            return $this->send(array('result' => 'success', 'message' => $this->lang->measurement->saveSuccess, 'locate' => $locate));
        }

        $this->view->title       = $this->lang->measurement->createTemplate;
        $this->view->position[]  = $this->lang->measurement->createTemplate;
        $this->view->basicMeases = $this->measurement->getPairs();
        $this->view->reports     = $this->measurement->getReports('pairs');
        $this->display();
    }

    /**
     * Edit report template.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function editTemplate(int $templateID = 0)
    {
        if($_POST)
        {
            $changes = $this->measurement->editTemplate($templateID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->createLink('measurement', 'template');
            return $this->send(array('result' => 'success', 'message' => $this->lang->measurement->saveSuccess, 'locate' => $locate));
        }

        $this->view->title        = $this->lang->measurement->editTemplate;
        $this->view->position[]   = $this->lang->measurement->editTemplate;
        $this->view->template     = $this->measurement->getTemplateByID($templateID);
        $this->view->basicMeases  = $this->measurement->getPairs('basic');
        $this->view->deriveMeases = $this->measurement->getPairs('derivation');
        $this->view->reports      = $this->measurement->getReports('pairs');
        $this->display();
    }

    /**
     * View report template.
     *
     * @param  int    $templateID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function viewTemplate(int $templateID = 0, int $projectID = 0)
    {
        $template = $this->measurement->getTemplateByID($templateID);
        $this->view->template = $template;

        if($_POST)
        {
            $_GET['onlybody'] = 'yes';
            $this->view->content = $this->measurement->parseTemplateContent($template->content);
            return $this->display('measurement', 'report');
        }

        $this->view->title        = $this->lang->measurement->viewTemplate;
        $this->view->position[]   = $this->lang->measurement->viewTemplate;
        $this->view->projectID    = $projectID;
        $this->view->basicMeases  = $this->measurement->getPairs('basic');
        $this->view->deriveMeases = $this->measurement->getPairs('derivation');
        $this->view->projectPairs = $this->loadModel('project')->getPairsByModel($template->model);
        $this->view->components   = $this->measurement->getTemplateComponents($template->content);
        $this->display();
    }

    /**
     * Save report template.
     *
     * @access public
     * @return void
     */
    public function saveReport()
    {
        $report = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->remove('parseContent, saveReport')
            ->get();

        $this->dao->insert(TABLE_PROGRAMREPORT)->data($report)->check('name', 'notempty')->autocheck()->exec();

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Design measurement function.
     *
     * @param  int    $measurementID
     * @access public
     * @return void
     */
    public function design(int $measurementID)
    {
        $measurement = $this->measurement->getByID($measurementID);
        if($_POST)
        {
            $result = $this->measurement->createPhpFunction($this->post->code, $measurement);
            if($result['result'] != 'success') return $this->send($result);

            foreach($this->post->varName as $i => $varName)
            {
                if(empty($varName)) return $this->send(array('result' => 'fail', 'errors' => $this->lang->measurement->tips->noticeVarName));
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');
                $errors = array();
                if($params[$varName]['showName'] == '') $errors[] = sprintf($this->lang->measurement->tips->showNameMissed, $varName);
                if(empty($this->post->queryValue[$i]))  $errors[] = sprintf($this->lang->measurement->tips->noticeQueryValue, $varName);
                if(!empty($errors)) return $this->send(array('result' => 'fail', 'errors' => join("<br>", $errors)));

                $params[$varName]['varName']  = $varName;
                $params[$varName]['varType']  = zget($this->post->varType, $i, 'input');
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');
                $params[$varName]['options']  = $this->post->options[$i];
                $params[$varName]['defaultValue'] = zget($this->post->defaultValue, $i, '');
            }

            $this->dao->update(TABLE_BASICMEAS)
                ->set('configure')->eq($this->post->code)
                ->set('params')->eq(json_encode($params))
                ->where('id')->eq($measurementID)
                ->exec();

            $params       = $this->measurement->processPostParams();
            $measFunction = $this->measurement->getPhpFunctionName($measurement);
            $saveRecord   = $this->post->action == 'save';
            $queryResult  = $this->measurement->execPhpMeasurement($measurement, $params, $saveRecord);
            if($queryResult === false) return $this->send(array('result' => 'fail', 'message' => $this->measurement->errorInfo));

            if($this->post->action == 'save') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
            return $this->send(array('result' => 'success', 'queryResult' => sprintf($this->lang->measurement->saveSqlMeasSuccess, $queryResult)));
        }

        $defaultParamString = zget($this->config->measurement->scopeParams, $measurement->scope);
        $defaultCode        = sprintf($this->lang->measurement->phpTemplate, $this->measurement->getPhpFunctionName($measurement), $defaultParamString);
        $params             = json_decode((string)$measurement->params, true);

        $this->view->title         = $this->lang->measurement->design . $this->lang->colon . $measurement->name;
        $this->view->position[]    = $this->lang->measurement->design;
        $this->view->code          = !empty($measurement->configure) ? $measurement->configure : $defaultCode;
        $this->view->params        = empty($params) ? array() : json_decode($measurement->params, true);
        $this->view->measurement   = $measurement;
        $this->view->measurementID = $measurementID;
        $this->view->programPairs  = $this->loadModel('project')->getPairsByProgram();
        $this->display();
    }

    /**
     * Set SQL function.
     *
     * @param  int    $measurementID
     * @access public
     * @return void
     */
    public function setSQL(int $measurementID)
    {
        $measurement = $this->measurement->getByID($measurementID);
        if($_POST)
        {
            $result = $this->measurement->createSqlFunction($this->post->sql, $measurement);
            if($result['result'] != 'success') return $this->send($result);

            foreach($this->post->varName as $i => $varName)
            {
                if(empty($varName)) return $this->send(array('result' => 'fail', 'errors' => $this->lang->measurement->tips->noticeVarName));
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');
                $errors = array();
                if($params[$varName]['showName'] == '') $errors[] = sprintf($this->lang->measurement->tips->showNameMissed, $varName);
                if(empty($this->post->queryValue[$i]))  $errors[] = sprintf($this->lang->measurement->tips->noticeQueryValue, $varName);
                if(!empty($errors)) return $this->send(array('result' => 'fail', 'errors' => join("<br>", $errors)));

                $params[$varName]['varName']  = $varName;
                $params[$varName]['varType']  = zget($this->post->varType, $i, 'input');
                $params[$varName]['showName'] = zget($this->post->showName, $i, '');
                $params[$varName]['options']  = $this->post->options[$i];
                $params[$varName]['defaultValue'] = zget($this->post->defaultValue, $i, '');
            }

            $this->dao->update(TABLE_BASICMEAS)
                ->set('configure')->eq($this->post->sql)
                ->set('params')->eq(json_encode($params))
                ->where('id')->eq($measurementID)
                ->exec();

            $params       = $this->measurement->processPostParams();
            $measFunction = $this->measurement->getSqlFunctionName($measurement);
            $queryResult  = $this->measurement->execSqlMeasurement($measurement, $params, $this->post->action);
            if($queryResult === false) return $this->send(array('result' => 'fail', 'message' => $this->measurement->errorInfo));

            if($this->post->action == 'save') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
            return $this->send(array('result' => 'success', 'queryResult' => sprintf($this->lang->measurement->saveSqlMeasSuccess, $queryResult)));
        }

        $defaultParamString = zget($this->config->measurement->scopeParams, $measurement->scope);
        $defaultSQL         = sprintf($this->lang->measurement->sqlTemplate, $this->measurement->getSqlFunctionName($measurement), $defaultParamString);
        $params             = json_decode((string)$measurement->params, true);

        $this->view->title         = $this->lang->measurement->design . $this->lang->colon . $measurement->name;
        $this->view->position[]    = $this->lang->measurement->design;
        $this->view->sql           = !empty($measurement->configure) ? $measurement->configure : $defaultSQL;
        $this->view->params        = empty($params) ? array() : json_decode($measurement->params, true);
        $this->view->measurement   = $measurement;
        $this->view->measurementID = $measurementID;
        $this->view->programPairs  = $this->loadModel('project')->getPairsByProgram();
        $this->display();
    }

    /**
     * Get php params from post php code.
     *
     * @access public
     * @return void
     */
    public function getPhpParams()
    {
        $measurementID = (int)$this->post->measurementID;
        $measurement   = $this->measurement->getByID($measurementID);
        if(!$this->post->code) return print('');
        if($this->post->code == $measurement->configure) $this->locate($this->server->http_referer);

        $this->view->params        = $this->measurement->getPhpMeasParams($this->post->code, $measurement->code);
        $this->view->measurement   = $measurement;
        $this->view->measurementID = $measurementID;
        $this->display();
    }

    /**
     * Get sql params from sql.
     *
     * @access public
     * @return void
     */
    public function getSqlParams()
    {
        $measurementID = $this->post->measurementID;
        $measurement   = $this->measurement->getByID((int)$measurementID);
        if(!$this->post->sql) return print('');
        if($this->post->sql == $measurement->configure) $this->locate($this->server->http_referer);

        $measFunction = $this->measurement->getSqlFunctionName($measurement);
        $postFunction = $this->measurement->parseSqlFunction($this->post->sql);
        if(!$postFunction) return print("sql error."); //@todo fix error message.
        $this->post->sql = str_replace($postFunction, $measFunction, $this->post->sql);

        $this->view->params        = $this->measurement->parseSqlParams($this->post->sql);
        $this->view->measurement   = $measurement;
        $this->view->measurementID = $measurementID;
        $this->display();
    }

    /**
     * Init crontab queue.
     *
     * @access public
     * @return void
     */
    public function initCrontabQueue()
    {
        $this->measurement->initCrontabQueue();
        return print('success');
    }

    /**
     * Exec crontab queue.
     *
     * @access public
     * @return void
     */
    public function execCrontabQueue()
    {
        $queues = $this->measurement->getUnexecutedQueues();
        foreach($queues as $queue)
        {
            if($queue->execTime and date('H:i') < $queue->execTime) continue;
            $this->measurement->execQueue($queue);
        }
        return print('success');
    }

    /**
     * Set tips.
     *
     * @param  string $type
     * @param  string $object
     * @access public
     * @return void
     */
    public function setTips(string $type = 'progress', string $object = 'SPI')
    {
        $this->loadModel('custom');

        if(strtolower($this->server->request_method) == "post")
        {
            $data = (array)fixer::input('post')->get();
            extract($data);

            $tipsConfig = array();
            foreach($mins as $key => $min)
            {
                $max    = zget($maxs, $key, '');
                $tip    = zget($tips, $key, '');
                $range  = zget($ranges, $key, '');
                if(empty($min) and empty($max) and empty($tip)) continue;
                if($object == 'SPI' || $object == 'CPI')
                {
                    if($min < 0 || $max < 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->numberError));
                }
                if(!preg_match("/^(\-|\+?)\d+(\.\d+)?$/", $min))  return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->regionMustNumber));
                if(!preg_match("/^(\-|\+?)\d+(\.\d+)?$/", $max))  return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->regionMustNumber));
                if(empty($tip)) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->tipNotEmpty));

                $tipCofig = new stdclass();
                $tipCofig->min   = $min;
                $tipCofig->max   = $max;
                $tipCofig->tip   = $tip;
                $tipCofig->type  = $object;
                $tipCofig->range = $range;

                $tipsConfig[$key] = $tipCofig;
            }

            if($type == 'progress') $this->loadModel('setting')->setItem("system.custom.$object.progressTip", json_encode($tipsConfig));
            if($type == 'cost')     $this->loadModel('setting')->setItem("system.custom.$object.costTip", json_encode($tipsConfig));

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('measurement', 'setTips', "type=$type&object=$object")));
        }

        $tipType    = $type . 'Tip';
        $tipsConfig = isset($this->config->custom->$object->$tipType) ? $this->config->custom->$object->$tipType : '';

        $this->view->title      = $this->lang->custom->common . $this->lang->colon . $this->lang->custom->setTips;
        $this->view->position[] = $this->lang->custom->common;
        $this->view->position[] = $this->lang->custom->setTips;
        $this->view->tipsConfig = json_decode($tipsConfig);
        $this->view->type       = $type;
        $this->view->object     = $object;
        $this->display();
    }

    /**
     * Ajax set params.
     *
     * @param  int    $measurementID
     * @access public
     * @return void
     */
    public function ajaxSetParams(int $measurementID = 0)
    {
        $measurement = $this->measurement->getByID($measurementID);

        $data   = fixer::input('post')->get();
        $params = array();
        foreach($data->varName as $i => $varName)
        {
            if(empty($varName))            return $this->send(array('result' => 'fail', 'message' => $this->lang->measurement->tips->noticeVarName));
            if(empty($data->showName[$i])) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->measurement->tips->noticeShowName, $data->showName[$i])));
            if(empty($data->varType[$i]))  return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->measurement->tips->noticeVarType, $data->varName[$i])));

            $params[$varName]['varName']  = $varName;
            $params[$varName]['varType']  = $data->varType[$i];
            $params[$varName]['showName'] = $data->showName[$i];
            $params[$varName]['options']  = $data->options[$i];
            if(isset($data->defaultValue)) $params[$varName]['defaultValue'] = zget($data->defaultValue, $i, '');
            if(isset($data->queryValue))   $params[$varName]['queryValue']   = zget($data->queryValue, $i, '');
        }

        $this->session->set('params', json_encode($params));
        if($measurement->method == 'php') $this->session->set('queryParams', $this->post->queryValue);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "hideParamForm()"));
    }

    /**
     * Ajax build SQL.
     *
     * @access public
     * @return void
     */
    public function ajaxBuildSQL()
    {
        return print($this->fetch('sqlbuilder', 'create'));
    }

    /**
     * Ajax get param control.
     *
     * @param  string $controlType
     * @param  string $optionType
     * @param  string $value
     * @access public
     * @return void
     */
    public function ajaxGetParamControl(string $controlType, string $optionType = '', string $value = '')
    {
        $value = base64_decode($value);
        $html  = '';
        $html .= "<td>" . $this->measurement->buildParamControl('defaultValue', $controlType, $value, $optionType) . "</td>";
        $html .= "<td>" . $this->measurement->buildParamControl('queryValue', $controlType, '', $optionType) . "</td>";

        return print($html);
    }

    /**
     * Ajax check element whether need params.
     *
     * @param  string $holderValue
     * @access public
     * @return void
     */
    public function ajaxCheckElementNeedParams(string $holderValue)
    {
        $holderValue = base64_Decode($holderValue);
        $sysData     = $this->measurement->parseHolderValue($holderValue);
        $params      = $this->measurement->getParams($sysData);
        $needParams  = empty($params) ? 'no' : 'yes';
        return print($needParams);
    }

    /**
     * Ajax build param form.
     *
     * @param  string $holderValue
     * @access public
     * @return void
     */
    public function ajaxBuildParamForm(string $holderValue)
    {
        $holderValue = base64_Decode($holderValue);
        $sysData     = $this->measurement->parseHolderValue($holderValue);

        $this->view->controlID = $sysData->controlID;
        $this->view->params    = $this->measurement->getParams($sysData);
        $this->view->sysData   = $sysData;
        $this->display();
    }

    /**
     * Ajax get module actions.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function ajaxGetModuleActions(string $module)
    {
        $actions = $this->measurement->getModuleActions($module);
        return print(html::select('action', $actions, '', "class='form-control chosen'"));
    }

    /**
     * Update sort.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $idList  = explode(',', trim($this->post->meas, ','));
        $orderBy = $this->post->orderBy;
        foreach($idList as $id => $value)
        {
            if($value == 'undefined') unset($idList[$id]);
        }

        if(strpos($orderBy, 'order') === false) return false;
        $data = $this->dao->select('id, `order`')->from(TABLE_BASICMEAS)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');

        foreach($data as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_BASICMEAS)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Batch edit measurement.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $measurementID = $this->post->measurement;
        if(!empty($measurementID))
        {
            $this->view->measurements = $this->dao->select('*')->from(TABLE_BASICMEAS)->where('id')->in($this->post->measurement)->andWhere('deleted')->eq('0')->orderBy('order_desc')->fetchAll();
        }
        elseif($_POST)
        {
            $this->measurement->batchEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return print(js::locate($this->createLink('measurement', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->measurement->common . $this->lang->colon . $this->lang->measurement->batchEdit;
        $this->view->position[] = $this->lang->measurement->batchEdit;
        $this->view->orderBy    = 'order_desc';
        $this->display();
    }
}
