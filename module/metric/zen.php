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
     * @param  int    $metrics
     * @access protected
     * @return void
     */
    protected function prepareTree($scope, $modules)
    {
        $moduleTree = array();
        foreach($modules as $module)
        {
            $object  = $module->object;
            $purpose = $module->purpose;

            $moduleTree[$object] = (object)array
            (
                'id' => $object,
                'parent' => '0',
                'name' => $this->lang->metric->objectList[$object],
                'url' => $this->inlink('browse', "scope=$scope&param=$object&type=byTree")
            );

            $moduleTree["{$object}_{$purpose}"] = (object)array
            (
                'id' => "{$object}_{$purpose}",
                'parent' => $object,
                'name' => $this->lang->metric->purposeList[$purpose],
                'url' => $this->inlink('browse', "scope=$scope&param={$object}_{$purpose}&type=byTree")
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
        foreach($this->lang->metric->scopeList as $scope => $scopeText)
        {
            $scopeList[] = array('key' => $scope, 'text' => $scopeText);
        }

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

        $legendBasic = array();
        $legendBasic['scope']   = array('name' => $this->lang->metric->scope, 'text' => zget($this->lang->metric->scopeList, $metric->scope));
        $legendBasic['object']  = array('name' => $this->lang->metric->object, 'text' => zget($this->lang->metric->objectList, $metric->object));
        $legendBasic['purpose'] = array('name' => $this->lang->metric->purpose, 'text' => zget($this->lang->metric->purposeList, $metric->purpose));
        $legendBasic['code']    = array('name' => $this->lang->metric->code, 'text' => $metric->code);
        $legendBasic['unit']    = array('name' => $this->lang->metric->unit, 'text' => $metric->unit);
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

        $createEditInfo = array();
        $createEditInfo['createdBy']     = array('name' => $this->lang->metric->createdBy, 'text' => $this->lang->metric->system . $this->lang->at . $metric->createdDate);
        //$createEditInfo['createdBy']     = array('name' => $this->lang->metric->createdBy, 'text' => zget($users, $metric->createdBy) . ($metric->createdBy ? $this->lang->at . $metric->createdDate : ''));
        //$createEditInfo['implementedBy'] = array('name' => $this->lang->metric->implementedBy, 'text' => zget($users, $metric->implementedBy) . ($metric->implementedBy ? $this->lang->at . $metric->implementedDate : ''));
        //$createEditInfo['offlineBy']     = array('name' => $this->lang->metric->offlineBy, 'text' => zget($users, $metric->offlineBy) . ($metric->offlineBy ? $this->lang->at . $metric->offlineDate : ''));
        $createEditInfo['lastEdited']    = array('name' => $this->lang->metric->lastEdited, 'text' => zget($users, $metric->editedBy) . ($metric->editedBy ? $this->lang->at . $metric->editedDate : ''));

        return $createEditInfo;

    }

    /**
     * 获取度量数据表的表头。
     * Get header of result table.
     *
     * @param  array     $result
     * @access protected
     * @return array|false
     */
    protected function getResultHeader($result)
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
     * 获取度量数据表的数据。
     * Get data of result table.
     *
     * @param  object    $metric
     * @param  array     $result
     * @access protected
     * @return array|false
     */
    protected function getResultData($metric, $result)
    {
        $scope = $metric->scope;
        if(empty($result)) return false;

        if($metric->scope != 'global') $objectPairs = $this->metric->getPairsByScope($scope);

        $tableData = array();
        foreach($result as $record)
        {
            $fieldList = array_keys($record);
            $dateList  = array_intersect($fieldList, $this->config->metric->dateList);

            $row = new stdclass();
            if(!empty($dateList))  $row->date = $this->metric->buildDateCell($record);
            if($scope != 'global') $row->scope = $objectPairs[$record[$scope]];
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
}
