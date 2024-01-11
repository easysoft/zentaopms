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
     * @param  string    $scope
     * @access protected
     * @return array
     */
    protected function responseAfterCreate($metricID, $scope, $afterCreate)
    {
        if($afterCreate == 'back')
        {
            $location = $this->createLink('metric', 'browse', "scope=$scope");
            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);
        }

        $closeModal = false;
        $location = $this->createLink('metric', 'implement', "metricID=$metricID");
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
    protected function responseAfterEdit($scope)
    {
        $location = $this->createLink('metric', 'browse', "scope=$scope");
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);
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
        $dataSource = $calcGroup->dataset;
        $calcList   = $calcGroup->calcList;

        if(empty($dataSource))
        {
            $calc = current($calcList);
            $calc->setDAO($this->dao);

            return $calc->getStatement();
        }

        $dataset   = $this->metric->getDataset($this->dao);
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
     * 根据度量项计算的结果，构建可插入表的度量数据。
     * Build measurements that can be inserted into tables based on the results of the measurements computed.
     *
     * @param  array     $calcList
     * @access protected
     * @return array
     */
    protected function prepareMetricRecord($calcList)
    {
        $options = array('year' => date('Y'), 'month' => date('n'), 'week' => substr(date('oW'), -2), 'day' => date('j'));

        $now        = helper::now();
        $dateValues = $this->metric->generateDateValues($now);

        $records = array();
        foreach($calcList as $code => $calc)
        {
            $metric       = $this->metric->getByCode($code);
            $dateType     = $this->metric->getDateTypeByCode($code);
            $recordCommon = $this->buildMetricRecordCommonFields($metric->id, $code, $now, $dateValues->$dateType);
            $initRecords  = $this->initMetricRecords($recordCommon, $metric->scope);

            $results = $calc->getResult($options);
            foreach($results as $record)
            {
                $record = (object)$record;
                if(empty($record->value)) continue;

                $record->metricID   = $calc->id;
                $record->metricCode = $code;
                $record->date       = $now;
                $record->system     = $metric->scope == 'system' ? 1 : 0;

                $uniqueKey = $this->getUniqueKeyByRecord($record);
                if(!isset($initRecords[$uniqueKey]))
                {
                    $initRecords[$uniqueKey] = $record;
                    continue;
                }

                $initRecords[$uniqueKey]->value = $record->value;
            }

            $records = array_merge($records, array_values($initRecords));
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
    protected function initMetricRecords($recordCommon, $scope)
    {
        $records = array();
        if($scope == 'system')
        {
            $record = clone $recordCommon;
            $record->system = 1;
            $uniqueKey = $this->getUniqueKeyByRecord($record);

            $records[$uniqueKey] = $record;
        }
        else
        {
            $scopeList = $this->metric->getPairsByScope($scope);
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
     * @param  int     $metricID
     * @param  string  $code
     * @param  string  $scope
     * @param  string  $scopeValue
     * @param  string  $date
     * @access protected
     * @return array
     */
    protected function buildMetricRecordCommonFields($metricID, $code, $date, $dateValues)
    {
        $record = new stdclass();
        $record->value      = 0;
        $record->metricID   = $metricID;
        $record->metricCode = $code;
        $record->date       = $date;

        $record = (object)array_merge((array)$record, $dateValues);

        return $record;
    }

    /**
     * 根据度量数据，获取度量数据的唯一键。
     * Get the unique key of metric data based on metric data.
     *
     * @param  object    $record
     * @access protected
     * @return string
     */
    protected function getUniqueKeyByRecord($record)
    {
        $record = (array)$record;
        $uniqueKeys = array();
        $ignoreFields = array('value', 'metricID', 'metricCode', 'date');
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
    protected function calcMetric($rows, $calcList)
    {
        foreach($rows as $row)
        {
            foreach($calcList as $calc)
            {
                $record = $this->getCalcFields($calc, $row);
                $calc->calculate($record);
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
    protected function getBasicInfo(object $view, string $fields = 'scope,object,purpose,code,unit,stage')
    {
        extract((array)$view);

        $isOldMetric = $this->metric->isOldMetric($metric);
        $unit = $isOldMetric ? $metric->oldUnit : zget($this->lang->metric->unitList, $metric->unit);

        $legendBasic = array();
        if(strpos($fields, 'scope') !== false)      $legendBasic['scope']       = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->scopeList, $metric->scope));
        if(strpos($fields, 'object') !== false)     $legendBasic['object']      = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->objectList, $metric->object));
        if(strpos($fields, 'purpose') !== false)    $legendBasic['purpose']     = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->purposeList, $metric->purpose));
        if(strpos($fields, 'name') !== false)       $legendBasic['name']        = array('name' => $this->lang->metric->name, 'text' => $metric->name);
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
     * 根据table宽度返回pager extra。
     * Return pager extra based on the table width.
     *
     * @param  string    $tableWidth
     * @access protected
     * @return int
     */
    public function getPagerExtra($tableWidth)
    {
        return ($tableWidth > 300) ? '' : 'shortPageSize';
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
        foreach($metrics as $metric)
        {
            $metric->canEdit      = $metric->stage == 'wait';
            $metric->canImplement = ($metric->stage == 'wait' && !$this->metric->isOldMetric($metric) && $metric->builtin === '0');
            $metric->canDelist    = $metric->stage == 'released';
        }
        return $metrics;
    }
}
