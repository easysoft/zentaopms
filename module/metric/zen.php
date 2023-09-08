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
     * @access protected
     * @return array
     */
    protected function responseAfterCreate()
    {
        $location = $this->createLink('metric', 'browse');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);
    }

    /**
     * 成功编辑度量数据后，其他的额外操作。
     * Process after edit metric.
     *
     * @access protected
     * @return array
     */
    protected function responseAfterEdit()
    {
        $location = $this->createLink('metric', 'browse');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $location);
    }

    /**
     * 根据分类后的度量项，准备数据源句柄。
     * Prepare the data source handle based on the classified measures.
     *
     * @param  object    $classifiedCalc
     * @param  object    $dataset
     * @access protected
     * @return object
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
     * @param  array    $calcList
     * @access protected
     * @return array
     */
    protected function prepareMetricRecord($calcList)
    {
        $records = array();
        foreach($calcList as $code => $calc)
        {
            $results = $calc->getResult();

            if(!is_array($results) || count($results) == 0) continue;

            $record = (object)current($results);

            $global = 1;
            foreach($this->config->metric->excludeGlobal as $exclude)
            {
                if(isset($record->$exclude))
                {
                    $global = 0;
                    break;
                }
            }

            foreach($results as $record)
            {
                $record = (object)$record;

                if(empty($record->value)) $record->value = 0;

                $record->metricID   = $calc->id;
                $record->metricCode = $code;
                $record->date       = helper::now();
                $record->global     = $global;

                $records[] = $record;
            }
        }

        return $records;
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
                $calc->calculate((object)$row);
            }
        }
    }

    /**
     * 获取度量项的基本信息。
     * Get the basic information of the metric.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getBasicInfo(object $view)
    {
        extract((array)$view);

        $unit = $this->metric->isOldMetric($metric) ? $metric->oldUnit : zget($this->lang->metric->unitList, $metric->unit);

        $legendBasic = array();
        $legendBasic['scope']   = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->scopeList, $metric->scope));
        $legendBasic['object']  = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->objectList, $metric->object));
        $legendBasic['purpose'] = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->purposeList, $metric->purpose));
        $legendBasic['code']    = array('name' => $this->lang->metric->code, 'text' => $metric->code);
        $legendBasic['unit']    = array('name' => $this->lang->metric->unit, 'text' => $unit);
        $legendBasic['stage']   = array('name' => $this->lang->metric->stage, 'text' => zget($this->lang->metric->stageList, $metric->stage));

        return $legendBasic;
    }

    /**
     * 获取度量项的创建和编辑信息。
     * Get the create and edit information of the metric.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getCreateEditInfo(object $view)
    {
        extract((array)$view);

        $users = $this->loadModel('user')->getPairs('noletter');
        $users['system'] = 'system';

        $createEditInfo = array();
        $createEditInfo['createdBy']     = array('name' => $this->lang->metric->createdBy, 'text' => zget($users, $metric->createdBy) . ($metric->createdBy ? $this->lang->at . $metric->createdDate : ''));
        $createEditInfo['implementedBy'] = array('name' => $this->lang->metric->implementedBy, 'text' => zget($users, $metric->implementedBy) . ($metric->implementedBy ? $this->lang->at . $metric->implementedDate : ''));
        $createEditInfo['offlineBy']     = array('name' => $this->lang->metric->offlineBy, 'text' => zget($users, $metric->delistedBy) . ($metric->delistedBy ? $this->lang->at . $metric->delistedDate : ''));
        $createEditInfo['lastEdited']    = array('name' => $this->lang->metric->lastEdited, 'text' => zget($users, $metric->editedBy) . ($metric->editedBy ? $this->lang->at . $metric->editedDate : ''));

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

        $collectConf = json_decode($oldMetric->collectConf);
        $dateType    = $this->lang->metric->dateList[$collectConf->type];
        $dateConf    = $collectConf->type == 'week' ? $collectConf->week : $collectConf->month;
        $collectConfText = sprintf($this->lang->metric->collectConfText, $dateType, $dateConf, $oldMetric->execTime);
        
        $oldMetricInfo = array();
        $oldMetricInfo['scope']       = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->old->scopeList, $oldMetric->scope));
        $oldMetricInfo['object']      = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->old->objectList, $oldMetric->object));
        $oldMetricInfo['purpose']     = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->old->purposeList, $oldMetric->purpose));
        $oldMetricInfo['code']        = array('name' => $this->lang->metric->code, 'text' => $oldMetric->code);
        $oldMetricInfo['unit']        = array('name' => $this->lang->metric->unit, 'text' => $oldMetric->unit);
        $oldMetricInfo['collectType'] = array('name' => $this->lang->metric->collectType, 'text' => zget($this->lang->metric->old->collectTypeList, $oldMetric->collectType));
        $oldMetricInfo['collectType'] = array('name' => $this->lang->metric->collectConf, 'text' => $collectConfText);
        $oldMetricInfo['definition']  = array('name' => $this->lang->metric->declaration, 'text' => $oldMetric->definition);
        $oldMetricInfo['sql']         = array('name' => $this->lang->metric->sqlStatement, 'text' => $oldMetric->configure);

        return $oldMetricInfo;
    }

    /**
     * 获取度量数据表的表头。
     * Get header of result table in view.
     *
     * @param  array $result
     * @access protected
     * @return array|false
     */
    protected function getViewTableHeader($result)
    {
        if(empty($result)) return false;

        $fieldList = array_keys(current($result));
        $scopeList = array_intersect($fieldList, $this->config->metric->scopeList);
        $dateList  = array_intersect($fieldList, $this->config->metric->dateList);
        $scope     = current($scopeList);

        $header = array();
        if(!empty($scopeList)) $header[] = array('name' => 'scope', 'title' => $this->lang->metric->scopeList[$scope] . $this->lang->metric->name);
        if(!empty($dateList))  $header[] = array('name' => 'date',  'title' => $this->lang->metric->date);
        $header[] = array('name' => 'value', 'title' => $this->lang->metric->value);

        return $header;
    }

    /**
     * 获取度量数据表的表头。
     * Get header of result table.
     *
     * @param  array $result
     * @access protected
     * @return array|false
     */
    protected function getResultTableHeader($result)
    {
        if(empty($result)) return false;

        $header = array();
        $fieldList = array_keys(current($result));
        $scopeList = array_keys($this->lang->metric->scopeList);
        $dateList  = array_keys($this->lang->metric->dateList);

        foreach($fieldList as $field)
        {
            if(in_array($field, $scopeList)) $header[] = array('name' => $field, 'title' => $this->lang->metric->scopeList[$field]);
            if(in_array($field, $dateList))  $header[] = array('name' => $field, 'title' => $this->lang->metric->dateList[$field]);
            if($field == 'value')            $header[] = array('name' => 'value', 'title' => $this->lang->metric->value);
        }

        return $header;
    }

    /**
     * 获取度量数据表的数据。
     * Get data of result table.
     *
     * @param  object    $metric
     * @param  array     $result
     * @access protected
     * @return array|false
     */
    protected function getViewTableData($metric, $result)
    {
        $scope = $metric->scope;
        if(empty($result)) return false;

        if($metric->scope != 'system') $objectPairs = $this->metric->getPairsByScope($scope);

        $tableData = array();
        foreach($result as $record)
        {
            $fieldList = array_keys($record);
            $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

            $row = new stdclass();
            if(!empty($dateList))  $row->date = $this->metric->buildDateCell($record);
            if($scope != 'system') $row->scope = $objectPairs[$record[$scope]];
            $row->value = $record['value'];

            $tableData[] = $row;
        }

        return $tableData;
    }

    /**
     * 获取度量数据表的数据。
     * Get data of result table.
     *
     * @param  object    $metric
     * @param  array     $result
     * @access protected
     * @return array|false
     */
    protected function getResultTableData($metric, $result)
    {
        $scope = $metric->scope;
        if(empty($result)) return false;

        if($metric->scope != 'system') $objectPairs = $this->metric->getPairsByScope($scope);

        foreach($result as $record)
        {
            $fieldList = array_keys($record);

            $row = new stdclass();
            if($scope != 'system') $row->$scope = $objectPairs[$record[$scope]];
            $row->value = $record['value'];

            $tableData[] = $row;
        }

        return $tableData;
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
     * 校验度量项。
     * Verify calculator of metric.
     *
     * @param  object $metric
     * @access protected
     * @return string
     */
    protected function verifyCalc($metric)
    {
        $verifyResult = array();
        foreach($this->config->metric->verifyList as $method => $tip)
        {
            $verifyItem = new stdclass();
            $verifyItem->tip    = $tip;
            $verifyItem->result = $this->metric->$method($metric);

            $verifyResult[] = $verifyItem;
        }

        $dryRunOutput = $this->metric->dryRunCalc($metric);

        $dryRunResult = new stdclass();
        $dryRunResult->tip    = $dryRunOutput;
        $dryRunResult->result = empty($dryRunOutput) ? true : false;

        $verifyResult[] = $dryRunResult;

        return json_encode($verifyResult);
    }

    /**
     * 构建操作权限。
     *  Prepare action priv.
     *
     * @param  array    $metrics
     * @access protected
     * @return array
     */
    protected function prepareActionPriv(array $metrics): array
    {
        foreach($metrics as $metric)
        {
            $metric->canEdit = $metric->stage == 'wait';
            $metric->canImplement = ($metric->stage == 'wait' && !$this->metric->isOldMetric($metric));
            $metric->canDelist = $metric->stage == 'released';
        }
        return $metrics;
    }
}
