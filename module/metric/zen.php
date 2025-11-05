<?php
declare(strict_types=1);
/**
 * The zen file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
class metricZen extends metric
{
    private $validObjects;

    /**
     * 构建创建度量的数据。
     * Build metric data for create.
     *
     * @access protected
     * @return object
     */
    protected function buildMetricForCreate()
    {
        return form::data($this->config->metric->form->create)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->get();
    }

    /**
     * 构建编辑度量的数据。
     * Build metric data for edit.
     *
     * @access protected
     * @return object
     */
    protected function buildMetricForEdit()
    {
        return form::data($this->config->metric->form->create)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', helper::now())
            ->get();
    }

    /**
     * 成功插入度量数据后，其他的额外操作。
     * Process after create metric.
     *
     * @param  int       $metricID
     * @param  string    $afterCreate
     * @param  string    $from   metric|metriclib
     * @param  string    $location
     * @access protected
     * @return array
     */
    protected function responseAfterCreate($metricID, $afterCreate, $from, $location)
    {
        if($afterCreate == 'back' && $location) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);

        $location = $this->createLink('metric', 'implement', "metricID=$metricID&from=$from");
        $callback = array('name' => 'loadImplement', 'params' => $location);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => $callback);
    }

    /**
     * 成功编辑度量数据后，其他的额外操作。
     * Process after edit metric.
     *
     * @access protected
     * @return array
     */
    protected function responseAfterEdit($metricID, $afterEdit, $location)
    {
        if($afterEdit == 'back' && $location) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);

        $location = $this->createLink('metric', 'implement', "metricID=$metricID");
        $callback = array('name' => 'loadImplement', 'params' => $location);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => $callback);
    }

    /**
     * 根据分类后的度量项，准备数据源句柄。
     * Prepare the data source handle based on the classified measures.
     *
     * @param  object    $calcGroup
     * @access protected
     * @return array
     */
    protected function prepareDataset($calcGroup)
    {
        $dao = $this->metric->getDAO();

        $dataSource = $calcGroup->dataset;
        $calcList   = $calcGroup->calcList;

        foreach($calcList as $calc)
        {
            $calc->setDAO($dao);
            if($calc->useSCM && ($this->config->inQuickon || $this->config->inCompose))
            {
                $scm = $this->app->loadClass('scm');
                $calc->setSCM($scm);
                $calc->setGitFoxRepos($this->loadModel('repo')->getGitFoxRepos());
            }
        }

        if(empty($dataSource))
        {
            $calc = current($calcList);
            $calc->setDAO($dao);

            return $calc->getStatement();
        }

        foreach($calcList as $calc)
        {
            $calc->setHolidays($this->loadModel('holiday')->getList());
            $calc->setWeekend(isset($this->config->project->weekend) ? $this->config->project->weekend : 2);
        }

        $dataset   = $this->metric->getDataset($dao);
        $fieldList = $this->metric->uniteFieldList($calcList);

        return $dataset->$dataSource($fieldList);
    }

    /**
     * 构建模块树数据。
     * Prepare module tree data.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  array  $modules
     * @access protected
     * @return void
     */
    protected function prepareTree($scope, $stage, $modules)
    {
        $sortedModules = array();
        $groupModules  = array();
        foreach($modules as $module)
        {
            $groupModules[$module->object][] = $module;
        }
        foreach(array_keys($this->lang->metric->objectList) as $object)
        {
            if(isset($groupModules[$object]) and !empty($groupModules[$object]))
            {
                $sortedModules = array_merge($sortedModules, $groupModules[$object]);
            }
        }

        $moduleTree = array();
        foreach($sortedModules as $module)
        {
            $object  = $module->object;
            $purpose = $module->purpose;

            $moduleTree[$object] = (object)array
            (
                'id' => $object,
                'parent' => '0',
                'name' => $this->lang->metric->objectList[$object],
                'url' => $this->inlink('browse', "scope=$scope&stage=$stage&param=$object&type=byTree")
            );

            $moduleTree["{$object}_{$purpose}"] = (object)array
            (
                'id' => "{$object}_{$purpose}",
                'parent' => $object,
                'name' => $this->lang->metric->purposeList[$purpose],
                'url' => $this->inlink('browse', "scope=$scope&stage=$stage&param={$object}_{$purpose}&type=byTree")
            );
        }

        return $moduleTree;
    }

    /**
     * 构建范围下拉数据。
     * Prepare scope picker data.
     *
     * @access protected
     * @return void
     */
    protected function prepareScopeList()
    {
        $scopeList = array();
        foreach($this->lang->metric->scopeList as $scope => $scopeText) $scopeList[] = array('key' => $scope, 'text' => $scopeText);
        return $scopeList;
    }

    /**
     * 开始计时。
     *
     * @access private
     * @return float
     */
    private function startTime()
    {
        return microtime(true);
    }

    /**
     * 结束计时。
     *
     * @param  float   $beginTime
     * @access private
     * @return string
     */
    private function endTime($beginTime)
    {
        $time = microtime(true) - $beginTime;
        return number_format((float)$time, 5, '.', '0');
    }

    /**
     * 获取有效的对象。
     * Get valid objects.
     *
     * @access private
     * @return array
     */
    private function getValidObjects()
    {
        if($this->validObjects !== null) return $this->validObjects;

        /* 保证逻辑集中，这里直接使用sql查询获取数据，保证查询性能。*/
        /* To ensure logical concentration, here we directly use sql to get data to ensure query performance. */
        $productList = $this->dao->select('id,closedDate')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->andWhere('shadow')->eq(0)
            ->fetchPairs('id', 'closedDate');

        $projectList = $this->dao->select('id,closedDate')->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notIN('closed,done')
            ->andWhere('type')->eq('project')
            ->fetchPairs('id', 'closedDate');

        $executionList = $this->dao->select('id,closedDate')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notIN('closed,done')
            ->andWhere('type')->in('sprint,stage,kanban')
            ->fetchPairs('id', 'closedDate');

        $this->validObjects = array('product' => $productList, 'project' => $projectList, 'execution' => $executionList);

        return $this->validObjects;
    }

    /**
     * 计算度量数据。
     * Calculate metric data.
     *
     * @param  array     $classifiedCalcGroup
     * @access protected
     * @return void
     */
    protected function calculateMetric($classifiedCalcGroup)
    {
        set_time_limit(0);
        $calcBeginTime = $this->startTime();

        foreach($classifiedCalcGroup as $calcGroup)
        {
            try
            {
                /* 开始计算度量数据。*/
                /* Start calculating metric data. */
                $beginTime = $this->startTime();

                $statement = $this->prepareDataset($calcGroup);
                $this->calcMetric($statement, $calcGroup->calcList);

                $recordWithCode = $this->prepareMetricRecord($calcGroup->calcList);

                $calcTime = $this->endTime($beginTime);

                /* 开始插入度量数据。*/
                /* Start inserting metric data. */
                $beginTime = $this->startTime();

                $this->metric->insertMetricLib($recordWithCode);

                $executeTime = $this->endTime($beginTime);

                /* 记录度量数据计算和插入的时间以及sql语句。*/
                /* Record the time of metric data calculation and insertion and sql statement. */
                $total = 0;
                $codes = '';
                $sql   = $statement ? $statement->get() : '';
                foreach($recordWithCode as $code => $records)
                {
                    $count = count($records);
                    $codes .= "$code($count), ";
                    $total += $count;
                }
                $this->metric->saveLogs("Calculate consumed time(seconds): calc: $calcTime, insert: $executeTime, total: $total, sql: $sql, $codes");
            }
            catch(Exception $e)
            {
                a($this->formatException($e));
            }
            catch(Error $e)
            {
                a($this->formatException($e));
            }
        }

        /* 记录度量数据计算的总时间。*/
        /* Record the total time of metric data calculation. */
        $executeTime = $this->endTime($calcBeginTime);
        $this->metric->saveLogs("Calculate all consumed time: {$executeTime} seconds");

        /* 开始去重。*/
        /* Start deduplication. */
        $beginTime = $this->startTime();
        $metrics = $this->metric->getExecutableMetric();
        foreach($metrics as $code)
        {
            $deduplicationBeginTime = $this->startTime();
            $this->metric->deduplication($code);
            $executeTime = $this->endTime($deduplicationBeginTime);
            $this->metric->saveLogs("Deduplication consumed time: {$executeTime} seconds, the code: $code");
        }

        /* 重建主键。*/
        /* Rebuild the primary key. */
        $this->metric->rebuildPrimaryKey();

        /* 记录去重的时间。*/
        /* Record the time of deduplication. */
        $executeTime = $this->endTime($beginTime);
        $this->metric->saveLogs("Deduplicate all consumed time: {$executeTime} seconds");
    }

    /**
     * 根据度量项计算的结果，构建可插入表的度量数据。
     * Build measurements that can be inserted into tables based on the results of the measurements computed.
     *
     * @param  array     $calcList
     * @access protected
     * @return array
     */
    protected function prepareMetricRecord($calcList)
    {
        /* 获取今天和昨天的日期。*/
        /* Get the date of today and yesterday. */
        $yesterday = date('j', strtotime('-1 day', strtotime('today')));
        $today     = date('j');
        $options = array('year' => date('Y'), 'month' => date('n'), 'week' => substr(date('oW'), -2), 'day' => "$today,$yesterday");

        /* 获取未关闭的对象id。*/
        /* Get the id of the unclosed objects. */
        $this->getValidObjects();
        $validObjects = $this->validObjects;
        $records = array();
        $yesterday = strtotime('-1 day');
        foreach($calcList as $code => $calc)
        {
            $metric = $this->metric->getByCode($code);
            /* 如果度量项是复用的，则计算复用的度量数据。*/
            /* If the metric is reused, calculate the reused metric data. */
            if($calc->reuse) $this->prepareReuseMetricResult($calc, $options);

            /* 判断是否统计关闭时的对象的度量数据。*/
            $endWithClosing = preg_match('/_when_closing$/', $code);

            $results = $calc->getResult($options);
            $records[$code] = array();
            if(is_array($results))
            {
                $scope = $metric->scope;
                foreach($results as $record)
                {
                    $record = (object)$record;
                    if(!is_numeric($record->value) || empty($record->value)) continue;

                    /* 如果度量项是产品、项目、执行且不统计关闭时的对象的度量数据，则过滤掉已关闭的数据。*/
                    /* If the metric is product, project, execution, filter out the closed data. */
                    $closedDate = isset($validObjects[$scope]) && !helper::isZeroDate(zget($validObjects[$scope], $record->$scope, '')) ? $validObjects[$scope][$record->$scope] : null;
                    $isClosedEarlierThanYesterday = $closedDate ? strtotime($closedDate) < $yesterday : false;
                    if(!$endWithClosing && $closedDate !== null && $isClosedEarlierThanYesterday) continue;

                    $record->metricID   = $calc->id;
                    $record->metricCode = $code;
                    $record->date       = helper::now();
                    $record->system     = $metric->scope == 'system' ? 1 : 0;

                    $records[$code][] = $record;
                }
            }
        }

        return $records;
    }

    /**
     * 获取复用的度量数据。
     * Prepare metric result for reuse metric.
     *
     * @param  object    $calc
     * @param  array     $options
     * @access protected
     * @return array
     */
    protected function prepareReuseMetricResult($calc, $options)
    {
        $reuseMetrics = array();
        foreach($calc->reuseMetrics as $key => $reuseMetric) $reuseMetrics[$key] = $this->metric->getResultByCode($reuseMetric, $options);
        $calc->calculate($reuseMetrics);
    }

    /**
     * 获取某度量项某日期的度量数据。
     * Get metric record by code and date.
     *
     * @param  string    $code
     * @param  object    $calc
     * @param  string    $date
     * @param  string    $type
     * @access protected
     * @return array
     */
    protected function getRecordByCodeAndDate($code, $calc, $date, $type = 'single')
    {
        $now = helper::now();

        $metric   = $this->metric->getByCode($code);
        $dateType = $this->metric->getDateTypeByCode($code);

        if($dateType == 'nodate') return array();
        if($type == 'all' && $this->metric->checkHasInferenceOfDate($code, $dateType, $date)) return array();

        $records    = array();
        $dateConfig = $this->metric->parseDateStr($date, $dateType);
        $results    = $calc->getResult($dateConfig);

        if(is_array($results))
        {
            foreach($results as $record)
            {
                $record = (object)$record;
                if(empty($record->value)) continue;

                $record->metricID   = $metric->id;
                $record->metricCode = $code;
                $record->date       = $now;
                $record->system     = $metric->scope == 'system' ? 1 : 0;

                $records[] = $record;
            }
        }

        return $records;
    }

    /**
     * 根据度量项编码，初始化度量数据。
     * Initialize metric data based on metric code.
     *
     * @param  string    $code
     * @param  string    $dateType
     * @param  string    $date
     * @access protected
     * @return array
     */
    protected function initMetricRecords($recordCommon, $scope, $date = 'now')
    {
        $records = array();
        if($scope == 'system')
        {
            $record = clone $recordCommon;
            //$record->system = 1;
            $uniqueKey = $this->getUniqueKeyByRecord($record);

            $records[$uniqueKey] = $record;
        }
        else
        {
            if($date == 'now') $date = helper::now();

            $scopeList = $this->metric->getPairsByScopeAndDate($scope, $date);

            foreach($scopeList as $key => $value)
            {
                $record = clone $recordCommon;
                $record->$scope = $key;
                $uniqueKey = $this->getUniqueKeyByRecord($record);

                $records[$uniqueKey] = $record;
            }
        }

        return $records;
    }

    /**
     * 根据度量项范围，构建度量数据的通用字段。
     * Build common fields of metric data based on metric scope.
     *
     * @param  int       $metricID
     * @param  string    $code
     * @param  string    $date
     * @param  array     $dateValues
     * @access protected
     * @return array
     */
    protected function buildRecordCommonFields($metricID, $code, $date, $dateValues)
    {
        $record = new stdclass();
        $record->value        = 0;
        $record->date         = $date;
        $record->calcType     = 'cron';
        $record->calculatedBy = 'system';

        $record = (object)array_merge($dateValues, (array)$record);

        return $record;
    }

    /**
     * 补全缺失的度量数据。
     * Complete missing metric data.
     *
     * @param  array     $records
     * @param  array     $metric
     * @access protected
     * @return array
     */
    protected function completeMissingRecords($records, $metric)
    {
        $now          = helper::now();
        $dateValues   = $this->metric->parseDateStr($now);
        $dateType     = $metric->dateType;
        $recordCommon = $this->buildRecordCommonFields($metric->id, $metric->code, $now, $dateValues->$dateType);
        $initRecords  = $this->initMetricRecords($recordCommon, $metric->scope);

        foreach($records as $record)
        {
            $uniqueKey = $this->getUniqueKeyByRecord($record, $metric->scope);
            if(!isset($initRecords[$uniqueKey]))
            {
                $initRecords[$uniqueKey] = $record;
                continue;
            }

            $initRecords[$uniqueKey]->value = $record->value;
        }

        return array_values($initRecords);
    }

    /**
     * 根据度量数据，获取度量数据的唯一键。
     * Get the unique key of metric data based on metric data.
     *
     * @param  object    $record
     * @param  string    $scope
     * @access protected
     * @return string
     */
    protected function getUniqueKeyByRecord($record, $scope = '')
    {
        $record = (array)$record;
        $uniqueKeys = array();
        $ignoreFields = array('value', 'metricID', 'metricCode', 'calcType', 'calculatedBy', 'date');
        if($scope == 'system') $ignoreFields[] = 'id';
        foreach($record as $field => $value)
        {
            if(in_array($field, $ignoreFields) || empty($value)) continue;
            $uniqueKeys[] = $field . $value;
        }

        return implode('_', $uniqueKeys);
    }

    /**
     * 遍历数据，对每个度量项计算每一行数据。
     * Calculate metric for every row.
     *
     * @param  array    $rows
     * @param  array    $calcList
     * @access protected
     * @return void
     */
    protected function calcMetric($statement, $calcList)
    {
        if(empty($statement)) return;

        $dbType = $this->config->metricDB->type;
        if($dbType == 'duckdb')
        {
            $this->loadModel('bi');
            $sql = $statement->get();
            $dbh = $this->app->loadDriver('duckdb');
            $rows = $dbh->query($sql)->fetchAll();
            foreach($rows as $row)
            {
                foreach($calcList as $calc)
                {
                    if(!$calc->reuse)
                    {
                        $record = $this->getCalcFields($calc, $row);
                        $calc->calculate($record);
                    }
                }
            }
            return;
        }

        $statement = $statement->query();
        while($row = $statement->fetch())
        {
            foreach($calcList as $code => $calc)
            {
                if(!$calc->reuse)
                {
                    $record = $this->getCalcFields($calc, $row);
                    $calc->calculate($record);
                }
            }
        }
    }

    protected function getCalcFields($calc, $row)
    {
        if(!isset($calc->dataset) || empty($calc->dataset)) return (object)$row;

        $pureRow = new stdclass();
        foreach($calc->fieldList as $field)
        {
            if(strpos(strtoupper($field), ' AS ') !== false)
            {
                $pos = strpos(strtoupper($field), ' AS ');
                $tag = substr($field, $pos, 4);
                $extractField = explode($tag, $field);
                $pureField    = end($extractField);
                $aliasField   = $pureField;
            }
            else
            {
                $extractField = explode('.', $field);
                $pureField    = end($extractField);
                $aliasField   = str_replace('.', '_', $field);
            }

            $pureRow->$pureField = $row->$aliasField;
        }

        if(isset($row->defaultHours)) $pureRow->defaultHours = $row->defaultHours;

        return $pureRow;
    }

    /**
     * 获取度量项的基本信息。
     * Get the basic information of the metric.
     *
     * @param  object    $view
     * @param  string    $fields
     * @access protected
     * @return array
     */
    protected function getBasicInfo(object $view, string $fields = 'scope,object,purpose,dateType,name,alias,code,unit,stage')
    {
        extract((array)$view);

        $isOldMetric = $this->metric->isOldMetric($metric);
        $unit = $isOldMetric ? $metric->oldUnit : zget($this->lang->metric->unitList, $metric->unit);

        $legendBasic = array();
        if(strpos($fields, 'scope') !== false)      $legendBasic['scope']       = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->scopeList, $metric->scope));
        if(strpos($fields, 'object') !== false)     $legendBasic['object']      = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->objectList, $metric->object));
        if(strpos($fields, 'purpose') !== false)    $legendBasic['purpose']     = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->purposeList, $metric->purpose));
        if(strpos($fields, 'dateType') !== false)   $legendBasic['dateType']    = array('name' => $this->lang->metric->dateType, 'text' => zget($this->lang->metric->dateTypeList, $metric->dateType));
        if(strpos($fields, 'name') !== false)       $legendBasic['name']        = array('name' => $this->lang->metric->name, 'text' => $metric->name);
        if(strpos($fields, 'alias') !== false)      $legendBasic['alias']       = array('name' => $this->lang->metric->alias, 'text' => $metric->alias);
        if(strpos($fields, 'code') !== false)       $legendBasic['code']        = array('name' => $this->lang->metric->code, 'text' => $metric->code);
        if(strpos($fields, 'unit') !== false)       $legendBasic['unit']        = array('name' => $this->lang->metric->unit, 'text' => $unit);
        if($isOldMetric)
        {
            $legendBasic['collectType'] = array('name' => $this->lang->metric->collectType, 'text' => zget($this->lang->metric->old->collectTypeList, $metric->collectType));
            $legendBasic['collectConf'] = array('name' => $this->lang->metric->collectConf, 'text' => $this->metric->getCollectConfText($metric));
        }
        if(strpos($fields, 'desc') !== false)       $legendBasic['desc']        = array('name' => $this->lang->metric->desc, 'text' => $metric->desc);
        if(strpos($fields, 'definition') !== false) $legendBasic['definition']  = array('name' => $this->lang->metric->definition, 'text' => $metric->definition);
        if(strpos($fields, 'stage') !== false)      $legendBasic['stage']       = array('name' => $this->lang->metric->stage, 'text' => zget($this->lang->metric->stageList, $metric->stage));

        return $legendBasic;
    }

    /**
     * 获取度量项的创建和编辑信息。
     * Get the create and edit information of the metric.
     *
     * @param  object    $view
     * @param  string    $fields
     * @access protected
     * @return array
     */
    protected function getCreateEditInfo(object $view, string $fields = 'createdBy,implementedBy,offlineBy,lastEdited')
    {
        extract((array)$view);

        $users = $this->loadModel('user')->getPairs('noletter');
        $users['system'] = 'system';

        $createEditInfo = array();
        if(strpos($fields, 'createdBy') !== false)     $createEditInfo['createdBy']     = array('name' => $this->lang->metric->createdBy, 'text' => zget($users, $metric->createdBy) . ($metric->createdBy ? $this->lang->at . $metric->createdDate : ''));
        if(strpos($fields, 'implementedBy') !== false) $createEditInfo['implementedBy'] = array('name' => $this->lang->metric->implementedBy, 'text' => zget($users, $metric->implementedBy) . ($metric->implementedBy ? $this->lang->at . $metric->implementedDate : ''));
        if(strpos($fields, 'offlineBy') !== false)     $createEditInfo['offlineBy']     = array('name' => $this->lang->metric->offlineBy, 'text' => zget($users, $metric->delistedBy) . ($metric->delistedBy ? $this->lang->at . $metric->delistedDate : ''));
        if(strpos($fields, 'lastEdited') !== false)    $createEditInfo['lastEdited']    = array('name' => $this->lang->metric->lastEdited, 'text' => zget($users, $metric->editedBy) . ($metric->editedBy ? $this->lang->at . $metric->editedDate : ''));

        return $createEditInfo;

    }

    /**
     * 获取旧版详情区块。
     * Get old metric info panel.
     *
     * @param  int $oldMetricID
     * @access protected
     * @return array
     */
    protected function getOldMetricInfo($oldMetricID)
    {
        $oldMetric = $this->metric->getOldMetricByID($oldMetricID);

        $oldMetricInfo = array();
        $oldMetricInfo['scope']       = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->old->scopeList, $oldMetric->scope));
        $oldMetricInfo['object']      = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->old->objectList, $oldMetric->object));
        $oldMetricInfo['purpose']     = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->old->purposeList, $oldMetric->purpose));
        $oldMetricInfo['code']        = array('name' => $this->lang->metric->code, 'text' => $oldMetric->code);
        $oldMetricInfo['unit']        = array('name' => $this->lang->metric->unit, 'text' => $oldMetric->unit);
        $oldMetricInfo['collectType'] = array('name' => $this->lang->metric->collectType, 'text' => zget($this->lang->metric->old->collectTypeList, $oldMetric->collectType));
        $oldMetricInfo['collectConf'] = array('name' => $this->lang->metric->collectConf, 'text' => $this->metric->getCollectConfText($oldMetric));
        $oldMetricInfo['definition']  = array('name' => $this->lang->metric->declaration, 'text' => $oldMetric->definition);
        $oldMetricInfo['sql']         = array('name' => $this->lang->metric->sqlStatement, 'text' => $oldMetric->configure);

        return $oldMetricInfo;
    }

    /**
     * 处理周定时配置。
     * Process week configuration.
     *
     * @param  string $dateConf
     * @access protected
     * @return string
     */
    protected function processWeekConf(string $dateConf): string
    {
        $days = explode(',', $dateConf);
        $dateConfNames = array();
        foreach($days as $day)
        {
            $dateConfNames[] = $this->lang->metric->oldMetric->dayNames[$day];
        }
        $dateConf = implode('、', $dateConfNames);

        return $dateConf;
    }

    /**
     * 获取度量数据表的宽度。
     * Get width of result table in view.
     *
     * @param  array     $headers
     * @access protected
     * @return int
     */
    protected function getViewTableWidth($headers)
    {
        $width = 0;
        foreach($headers as $header) $width += isset($header['width']) ? $header['width'] : 160;

        /* Add a little redundancy. */
        $width ++;

        return $width;
    }

    /**
     * Get pager extra.
     *
     * @param  string    $tableWidth
     * @access protected
     * @return string
     */
    public function getPagerExtra($tableWidth)
    {
        return ($tableWidth > 300) ? '' : 'shortPageSize';
    }

    /**
     * 格式化异常为字符串。
     * Format exception to string.
     *
     * @param  Exception $e
     * @access protected
     * @return string
     */
    public function formatException($e)
    {
        $message = $e->getMessage();
        $line    = $e->getLine();
        $file    = $e->getFile();

        return "Error: $message in $file on line $line";
    }

    /**
     * 根据后台配置的估算单位对列表赋值。
     * Assign unitList['measure'] by custom hourPoint.
     *
     * @access protected
     * @return void
     */
    protected function processUnitList()
    {
        $this->app->loadLang('custom');
        $key = zget($this->config->custom, 'hourPoint', '0');

        $this->lang->metric->unitList['measure'] = $this->lang->custom->conceptOptions->hourPoint[$key];
    }

    /**
     * 构建操作权限。
     * Prepare action priv.
     *
     * @param  array     $metrics
     * @access protected
     * @return array
     */
    protected function prepareActionPriv(array $metrics): array
    {
        $this->loadModel('screen');
        foreach($metrics as $metric)
        {
            $metric->canEdit        = $metric->stage == 'wait';
            $metric->canImplement   = ($metric->stage == 'wait' && !$this->metric->isOldMetric($metric) && $metric->builtin === '0');
            $metric->canDelist      = $metric->stage == 'released' && $metric->builtin === '0';
            $metric->canRecalculate = $metric->stage == 'released' && !empty($metric->dateType) && $metric->dateType != 'nodate';
            $metric->isUsed         = $this->screen->checkIFChartInUse($metric->id, 'metric');
        }
        return $metrics;
    }
}
