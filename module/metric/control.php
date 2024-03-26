<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     metric
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z zhouxin@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * __construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 度量项预览列表。
     * Preview metric list.
     *
     * @param  string $scope
     * @param  string $viewType
     * @param  int    $metricID
     * @param  string $filtersBase64
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function preview($scope = 'project', $viewType = 'single', $metricID = 0, $filtersBase64 = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->metric->processScopeList('released');

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $filters = array();
        if($scope == 'filter')
        {
            $filters = json_decode(base64_decode($filtersBase64), true);
            if(!is_array($filters)) $filters = array();
            $metrics = $this->metric->getListByFilter($filters, 'released');
        }
        elseif($scope == 'collect')
        {
            $metrics = $this->metric->getListByCollect('released');
        }
        else
        {
            $metrics = $this->metric->getList($scope, 'released');
        }
        $groupMetrics = $this->metric->groupMetricByObject($metrics);

        $current = $this->metric->getByID($metricID);
        if(empty($current) and $groupMetrics) $current = current(current($groupMetrics));

        $resultHeader  = array();
        $resultData    = array();
        $allResultData = array();
        if(!empty($current))
        {
            $current = $this->metric->getByID($current->id);

            $result    = $this->metric->getResultByCode($current->code, array(), 'cron', $pager);
            $allResult = $this->metric->getResultByCode($current->code, array(), 'cron');

            $resultHeader  = $this->metric->getViewTableHeader($current);
            $resultData    = $this->metric->getViewTableData($current, $result);
            $allResultData = $this->metric->getViewTableData($current, $allResult);
        }

        $currentDateType = $current ? $current->dateType : 'nodate';
        $currentCode     = $current ? $current->code : '';
        $currentScope    = $current ? $current->scope : '';
        list($groupHeader, $groupData) = $this->metric->getGroupTable($resultHeader, $resultData, $currentDateType);
        $this->view->groupHeader   = $groupHeader;
        $this->view->groupData     = $groupData;
        $this->view->dateType      = $currentDateType;
        $this->view->dateLabels    = $this->metric->getDateLabels($currentDateType);
        $this->view->defaultDate   = $this->metric->getDefaultDate($this->view->dateLabels);
        $this->view->tableWidth    = $this->metricZen->getViewTableWidth($groupHeader);
        $this->view->pagerExtra    = $this->metricZen->getPagerExtra($this->view->tableWidth);
        $this->view->headerGroup   = $this->metric->isHeaderGroup($groupHeader);

        $this->view->metrics       = $metrics;
        $this->view->groupMetrics  = $groupMetrics;
        $this->view->current       = $current;
        $this->view->metricList    = $this->lang->metric->metricList;
        $this->view->scope         = $scope;
        $this->view->title         = $this->lang->metric->common;
        $this->view->viewType      = $viewType;
        $this->view->recTotal      = count($metrics);
        $this->view->filters       = $filters;
        $this->view->filtersBase64 = $filtersBase64;
        $this->view->dtablePager   = $pager;
        $this->view->chartTypeList = $this->metric->getChartTypeList($resultHeader);
        $this->view->echartOptions = $this->metric->getEchartsOptions($resultHeader, $allResultData);
        $this->view->metricRecordType = $this->metric->getMetricRecordType($currentCode, $currentScope);
        $this->view->noDataTip     = $this->metric->getNoDataTip($currentCode);

        $this->display();
    }

    /**
     * 获取度量项列表。
     * Get metric list by ajax.
     *
     * @param  string $scope
     * @param  string $filters
     * @access public
     * @return void
     */
    public function ajaxGetMetrics($scope, $filters)
    {
        if($scope == 'filter')
        {
            $filters = json_decode(base64_decode($filters), true);
            if(!is_array($filters)) $filters = array();
            $metrics = $this->metric->getListByFilter($filters, 'released');
        }
        elseif($scope == 'collect')
        {
            $metrics = $this->metric->getListByCollect('released');
        }
        else
        {
            $metrics = $this->metric->getList($scope, 'released');
        }

        echo(json_encode($metrics));
    }

    /**
     * 查看度量项的详情。
     * View metric details.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function details($metricID)
    {
        $metric = $this->metric->getByID($metricID);

        $this->view->metric         = $metric;
        $this->view->legendBasic    = $this->metricZen->getBasicInfo($this->view,'scope,object,purpose,dateType,name,alias,code,unit,desc,definition');
        $this->view->createEditInfo = $this->metricZen->getCreateEditInfo($this->view, 'createdBy,implementedBy,lastEdited');

        $this->display();
    }

    /**
     * 计算历史数据。
     * Update metric lib of history.
     *
     * @param  string $date
     * @access public
     * @return void
     */
    public function updateHistoryMetricLib($date)
    {
        $date = str_replace('_', '-', $date);

        $classifiedCalcGroup = json_decode(file_get_contents($this->app->getTmpRoot() . 'calc'));
        $calcList            = $this->metric->getCalcInstanceList();

        $records = array();
        foreach($classifiedCalcGroup as $calcGroup)
        {
            foreach($calcGroup->calcList as $code => $calc) 
            {
                $calcObj = $calcList[$code];
                $calcObj->result = json_decode(json_encode($calc->result), true);
                $inferenceRecord = $this->metricZen->getRecordByCodeAndDate($code, $calcObj, $date);
                if(!empty($inferenceRecord)) $records[$code] = $inferenceRecord;
            }
        }
        $this->metric->insertMetricLib($records, 'inference');
    }

    /**
     * 保存计算后的度量项对象。
     * Save calculated metric to file.
     *
     * @access public
     * @return void
     */
    public function saveClassifiedCalcGroup()
    {
        $calcList            = $this->metric->getCalcInstanceList();
        $classifiedCalcGroup = $this->metric->classifyCalc($calcList);

        foreach($classifiedCalcGroup as $calcGroup)
        {
            if($this->config->edition == 'open' and in_array($calcGroup->dataset, array('getFeedbacks', 'getIssues', 'getRisks'))) continue;
            if($this->config->edition == 'biz' and in_array($calcGroup->dataset, array('getIssues', 'getRisks'))) continue;

            try
            {
                $statement = $this->metricZen->prepareDataset($calcGroup);
                if(empty($statement)) continue;

                $rows = $statement->fetchAll();
                $this->metricZen->calcMetric($rows, $calcGroup->calcList);

                foreach($calcGroup->calcList as $calc) $calc->setDAO(null);
            }
            catch(Exception $e)
            {
                a($this->metricZen->formatException($e));
            }
            catch(Error $e)
            {
                a($this->metricZen->formatException($e));
            }
        }

        file_put_contents($this->app->getTmpRoot(). 'calc', json_encode($classifiedCalcGroup));
    }

    /**
     * 删除重复度量库数据。
     * Delete duplication record in metric data.
     *
     * @access public
     * @return void
     */
    public function deduplicateRecord()
    {
        $metrics = $this->metric->getExecutableMetric();
        foreach($metrics as $code) $this->metric->deduplication($code);
    }

    /**
     * 计算度量项。
     * Execute metric.
     *
     * @access public
     * @return void
     */
    public function updateMetricLib()
    {
        // 保存当前的错误报告级别和显示错误的设置
        $originalDebug = $this->config->debug;

        // 开启调试模式
        $this->config->debug = 2;
        if($this->config->metricDB->type == 'sqlite') $this->metric->syncSqlite();

        $calcList = $this->metric->getCalcInstanceList();
        $classifiedCalcGroup = $this->metric->classifyCalc($calcList);

        foreach($classifiedCalcGroup as $calcGroup)
        {
            if($this->config->edition == 'open' and in_array($calcGroup->dataset, array('getFeedbacks', 'getIssues', 'getRisks'))) continue;
            if($this->config->edition == 'biz' and in_array($calcGroup->dataset, array('getIssues', 'getRisks'))) continue;

            try
            {
                $statement = $this->metricZen->prepareDataset($calcGroup);
                if(empty($statement)) continue;

                $rows = $statement->fetchAll();
                $this->metricZen->calcMetric($rows, $calcGroup->calcList);

                $recordWithCode = $this->metricZen->prepareMetricRecord($calcGroup->calcList);
                $this->metric->insertMetricLib($recordWithCode);
            }
            catch(Exception $e)
            {
                a($this->metricZen->formatException($e));
            }
            catch(Error $e)
            {
                a($this->metricZen->formatException($e));
            }
        }

        $metrics = $this->metric->getExecutableMetric();
        foreach($metrics as $code) $this->metric->deduplication($code);

        // 恢复之前的调试状态
        $this->config->debug = $originalDebug;

        if(dao::isError())
        {
            echo dao::getError();
            return false;
        }
        echo 'success';
    }

    /**
     * 获取数据表格的数据。
     * Get data of datatable.
     *
     * @param  int $metricID
     * @access public
     * @return string
     */
    public function ajaxCollectMetric($metricID)
    {
        $metric = $this->metric->getByID($metricID);

        $isCollect = strpos($metric->collector, ',' . $this->app->user->account . ',') !== false;
        $collector = explode(',', $metric->collector);

        if($isCollect)
        {
            $key = array_search($this->app->user->account, $collector);
            if($key) unset($collector[$key]);
        }
        else
        {
            $collector[] = $this->app->user->account;
        }
        $collector = array_filter($collector);

        $metric = new stdclass();
        $metric->collector = ',' . implode(',', $collector) . ',';
        $this->metric->updateMetricFields($metricID, $metric);

        $response = new stdclass();
        $response->result  = 'success';
        $response->collect = !$isCollect;

        echo json_encode($response);
    }

    /**
     * 获取度量项默认值和测试值的的下拉选项。
     * Get options of default value and query value.
     *
     * @param  string $optionType
     * @access public
     * @return string
     */
    public function ajaxGetControlOptions($optionType)
    {
        $options = $this->metric->getControlOptions($optionType);

        $optionList = array();
        foreach($options as $value => $option) $optionList[] = array('value' => $value, 'text' => $option, 'keys' => $option);

        return $this->send($optionList);
    }

    /**
     * 获取度量项的侧边栏。
     * Get side tree widget by ajax.
     *
     * @param  string $scope
     * @param  string $checkedList
     * @access public
     * @return string
     */
    public function ajaxGetMetricSideTree($scope, $checkedList)
    {
        $checkedList = explode(',', $checkedList);
        $metrics = $scope == 'collect' ? $this->metric->getListByCollect('released') : $this->metric->getList($scope, 'released');

        $this->view->groupMetrics = $this->metric->groupMetricByObject($metrics);
        $this->view->checkedList  = $checkedList;
        $this->view->scope        = $scope;
        $this->display();
    }

    public function ajaxGetMultipleMetricBox($metricID, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $metric    = $this->metric->getByID($metricID);
        $result    = $this->metric->getResultByCode($metric->code, array(), 'cron', $pager);
        $allResult = $this->metric->getResultByCode($metric->code, array(), 'cron');

        $resultHeader  = $this->metric->getViewTableHeader($metric);
        $resultData    = $this->metric->getViewTableData($metric, $result);
        $allResultData = $this->metric->getViewTableData($metric, $allResult);

        $dateType = $metric->dateType;
        list($groupHeader, $groupData) = $this->metric->getGroupTable($resultHeader, $resultData, $dateType);
        $this->view->groupHeader   = $groupHeader;
        $this->view->groupData     = $groupData;
        $this->view->dateType      = $dateType;
        $this->view->dateLabels    = $this->metric->getDateLabels($dateType);
        $this->view->defaultDate   = $this->metric->getDefaultDate($this->view->dateLabels);
        $this->view->tableWidth    = $this->metricZen->getViewTableWidth($groupHeader);
        $this->view->pagerExtra    = $this->metricZen->getPagerExtra($this->view->tableWidth);
        $this->view->headerGroup   = $this->metric->isHeaderGroup($groupHeader);
        $this->view->dtablePager   = $pager;
        $this->view->metricRecordType = $this->metric->getMetricRecordType($metric->code, $metric->scope);

        $this->view->metric        = $metric;
        $this->view->chartTypeList = $this->metric->getChartTypeList($resultHeader);
        $this->view->echartOptions = $this->metric->getEchartsOptions($resultHeader, $allResultData);

        $this->display();
    }

    public function ajaxGetTableAndCharts($metricID, $viewType = 'single', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $usePager = (!isset($_POST['scope']) or empty($_POST['scope']));
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $metric    = $this->metric->getByID($metricID);
        $result    = $this->metric->getResultByCode($metric->code, $_POST, 'cron', $usePager ? $pager : null);
        $allResult = $this->metric->getResultByCode($metric->code, $_POST, 'cron');

        $resultHeader  = $this->metric->getViewTableHeader($metric);
        $resultData    = $this->metric->getViewTableData($metric, $result);
        $allResultData = $this->metric->getViewTableData($metric, $allResult);
        if($usePager) $this->view->dtablePager = $pager;

        list($groupHeader, $groupData) = $this->metric->getGroupTable($resultHeader, $resultData, $metric->dateType);
        $this->view->groupHeader   = $groupHeader;
        $this->view->groupData     = $groupData;
        $this->view->tableWidth    = $this->metricZen->getViewTableWidth($groupHeader);
        $this->view->pagerExtra    = $this->metricZen->getPagerExtra($this->view->tableWidth);
        $this->view->headerGroup   = $this->metric->isHeaderGroup($groupHeader);
        $this->view->metricRecordType = $this->metric->getMetricRecordType($metric->code, $metric->scope);

        $this->view->viewType      = $viewType;
        $this->view->metric        = $metric;
        $this->view->chartTypeList = $this->metric->getChartTypeList($resultHeader);
        $this->view->echartOptions = $this->metric->getEchartsOptions($resultHeader, $allResultData);
        $this->view->noDataTip     = $this->metric->getNoDataTip($metric->code);

        $this->display();
    }

    /**
     * 重算度量项历史数据。
     * Recalculate metric history data.
     *
     * @access public
     * @return string
     */
    public function recalculate()
    {
        $this->display();
    }

    /**
     * 重算度量项进度。
     * Show recalculate progress.
     *
     * @param  string $calcType all|inference
     * @access public
     * @return string
     */
    public function recalculateProgress($calcType)
    {
        $startDate = $this->metric->getInstallDate();
        if($calcType == 'all') $endDate = helper::now();

        $this->view->startDate = substr($startDate, 0, 10);
        $this->view->endDate   = substr($endDate, 0, 10);
        $this->display();
    }

    /**
     * 获取数据表格的数据。
     * Get data of datatable.
     *
     * @param  int    $metricID
     * @param  string $chartType
     * @access public
     * @return string
     */
    public function ajaxGetEchartsOptions($metricID, $chartType = 'line')
    {
        $metric = $this->metric->getByID($metricID);
        $result = $this->metric->getResultByCode($metric->code, $_POST, 'cron');

        $resultHeader = $this->metric->getViewTableHeader($metric);
        $resultData   = $this->metric->getViewTableData($metric, $result);

        $echartOptions = $this->metric->getEchartsOptions($resultHeader, $resultData, $chartType);

        echo json_encode($echartOptions);
    }
}
