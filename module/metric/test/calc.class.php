<?php
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $this->tester = $tester;
        $this->initMetric();
    }

    /**
     * 运行sql重置zt_metric表。
     * Truncate zt_metric and insert data again.
     *
     * @access public
     * @return void
     */
    public function initMetric()
    {
        global $tester,$app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/metric.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
    }

   /**
     * 测试 getByID  方法。
     * Test getByID method.
     *
     * @param  int          $metricID
     * @param  array|string $fieldList
     * @access public
     * @return void
     */
    public function getByID($metricID, $fieldList = '*')
    {
        return $this->objectModel->getByID($metricID, $fieldList);
    }

    /**
     * 测试 getMetricsByIDList  方法。
     * Test getMetricsByIDList method.
     *
     * @param  array|string $idList
     * @access public
     * @return array
     */
    public function getMetricsByIDList($idList)
    {
        return $this->objectModel->getMetricsByIDList($idList);
    }

    /**
     * 测试 getOldMetricList 方法。
     * Test getOldMetricList method.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getOldMetricList($orderBy = 'id_desc')
    {
        return $this->objectModel->getOldMetricList($orderBy);
    }

    /**
     * 测试 getByCode 方法。
     * Test getByCode method.
     *
     * @param  string       $code
     * @param  array|string $fieldList
     * @access public
     * @return void
     */
    public function getByCode($code, $fieldList = '*')
    {
        return $this->objectModel->getByCode($code, $fieldList);
    }

    /**
     * 测试 getByList 方法。
     * Test getByList method.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  int    $param
     * @param  string $sort
     * @access public
     * @return array|false
     */
    public function getList($scope, $stage = 'all', $sort = 'id_desc')
    {
        return $this->objectModel->getList($scope, $stage, 0, '', 0, $sort);
    }

    /**
     * 测试 getByListByFilter 方法。
     * Test getByListByFilter method.
     *
     * @param  string $scope
     * @param  string $stage
     * @access public
     * @return array|false
     */
    public function getListByFilter($scope, $stage = 'all')
    {
        return $this->objectModel->getListByFilter($scope, $stage);
    }

    /**
     * 测试 getByListByCollect 方法。
     * Test getByListByCollect method.
     *
     * @param  string $scope
     * @param  string $stage
     * @access public
     * @return array|false
     */
    public function getListByCollect($stage = 'all')
    {
        return $this->objectModel->getListByCollect($stage);
    }

    /**
     * Test getPairsByScope method.
     *
     * @param  string $scope
     * @access public
     * @return array|false
     */
    public function getPairsByScope($scope)
    {
        return $this->objectModel->getPairsByScope($scope);
    }

    /**
     * Test getControlOptions method.
     *
     * @param  string $scope
     * @access public
     * @return array|false
     */
    public function getControlOptions($optionType)
    {
        return $this->objectModel->getControlOptions($optionType);
    }

    /**
     * 测试 getScopePairs 方法。
     * Test getScopePairs method.
     *
     * @param  bool   $all
     * @access public
     * @return array
     */
    public function getScopePairs($all = true)
    {
        return $this->objectModel->getScopePairs($all);
    }

    /**
     * 测试 groupMetricByObject 方法。
     * Test groupMetricByObject method.
     *
     * @param  array  $metrics
     * @param  bool   $isObject true|false  去对象|计数
     * @param  array  $params   array('group' => '', 'key' => '')
     * @access public
     * @return array
     */
    public function groupMetricByObject($metrics, $isObject = true, $params = array('group' => 'program', 'key' => 1))
    {
        $groupedMetrics = $this->objectModel->groupMetricByObject($metrics);

        if(!isset($params['group']) or !isset($groupedMetrics[$params['group']])) return false;

        $groupMetric = $groupedMetrics[$params['group']];
        if($isObject)
        {
            return (!isset($params['key']) or !isset($groupMetric[$params['key']]))  ? false : $groupMetric[$params['key']];
        }
        else
        {
            return array('count' => count($groupMetric));
        }
    }

    /**
     * 测试根据code获取度量项数据方法。
     * Test get metric by code.
     *
     * @param  string     $code
     * @param  array|null $options
     * @access public
     * @return void
     */
    public function getMetricByCode($code, $options = null)
    {
        return $this->objectModel->getResultByCode($code, $options);
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access private
     * @return string
     */
    private function getBaseCalcPath()
    {
        global $tester;
        return $tester->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * Get root of metric calculator.
     *
     * @access public
     * @return string
     */
    public function getCalcRoot()
    {
        return $this->objectModel->getCalcRoot();
    }

    /**
     * Get path of calculator data set.
     *
     * @access public
     * @return string
     */
    public function getDatasetPath()
    {
        return $this->objectModel->getDatasetPath();
    }

    /**
     * 计算度量项，并返回计算后的度量项对象。
     * Calculate metric.
     *
     * @param  string $file
     * @access public
     * @return object
     */
    public function calcMetric($file)
    {
        $code    = pathinfo($file, PATHINFO_FILENAME);
        $purpose = basename(dirname($file));
        $scope   = basename(dirname($file, 2));

        include_once $this->getBaseCalcPath();
        include_once $this->objectModel->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';

        $calc = new $code;
        $rows = $this->prepareDataset($calc)->fetchAll();

        foreach($rows as $row)
        {
            $calc->calculate((object)$row);
        }

        return $calc;
    }

    /**
     * 准备计算度量项所需要的数据源。
     * Prepare dataset object for calc.
     *
     * @param  object    $calc
     * @access public
     * @return void
     */
    public function prepareDataset($calc)
    {
        global $tester;
        $dataSource = $calc->dataset;

        if(!isset($calc->dataset))
        {
            $calc->setDAO($tester->dao);
            return $calc->getStatement();
        }

        $dataset   = $this->objectModel->getDataset($tester->dao);
        $fieldList = implode(',', array_unique($calc->fieldList));

        return $dataset->$dataSource($fieldList);
    }

    /**
     * Test getDataStatement.
     *
     * @param  string $code
     * @param  string $scope
     * @param  string $purpose
     * @access public
     * @return PDOStatement|string
     */
    public function getDataStatement($code, $scope, $purpose)
    {
        include_once $this->getBaseCalcPath();
        include_once $this->objectModel->getCalcRoot() . $scope . DS . $purpose . DS . $code . '.php';

        $calculator = new $code;
        $dataStatement = $this->objectModel->getDataStatement($calculator);

        if($dataStatement instanceof PDOStatement) return strtolower($dataStatement->queryString);
        return false;
    }

    /**
     * Test getResultByCode.
     *
     * @param  string      $code
     * @param  array       $options e.g. array('product' => '1,2,3', 'year' => '2023')
     * @param  string      $type    cron|realtime
     * @access public
     * @return array
     */
    public function getResultByCode($code, $options = array(), $type = 'realtime')
    {
        return $this->objectModel->getResultByCode($code, $options, $type);
    }

    /**
     * Test getResultByCodes.
     *
     * @param  string      $codes
     * @param  array       $options e.g. array('product' => '1,2,3', 'year' => '2023')
     * @param  string|null $print
     * @access public
     * @return array
     */
    public function getResultByCodes($codes, $options = array(), $print = null)
    {
        $results = $this->objectModel->getResultByCodes($codes, $options);
        if($print) return $results[$print];
        return $results;
    }

    /**
     * Test getModuleTreeList.
     *
     * @param  string $scope
     * @access public
     * @return array|false
     */
    public function getModuleTreeList($scope)
    {
        $treeList = $this->objectModel->getModuleTreeList($scope);
        usort($treeList, function($a, $b) {
            // 先按照 object 属性排序
            $objectComparison = strcmp($a->object, $b->object);

            // 如果 object 属性相同，则按照 purpose 属性排序
            if ($objectComparison === 0) {
                return strcmp($a->purpose, $b->purpose);
            }

            return $objectComparison;
        });

        return $treeList;
    }

    /**
     * Test getViewTableHeader.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function getViewTableHeader($code)
    {
        $metric = $this->objectModel->dao->select('*')->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
        return $this->objectModel->getViewTableHeader($metric);
    }

    /**
     * Test checkCalcExists.
     *
     * @param  object $metric
     * @access public
     * @return bool
     */
    public function checkCalcExists($metric)
    {
        $exists = $this->objectModel->checkCalcExists($metric);
        return $exists ? 'true' : 'false';
    }

    /**
     * Test getMetricRecordDateField.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function getMetricRecordDateField($code)
    {
        return $this->objectModel->getMetricRecordDateField($code);
    }

    /**
     * Test getDateTypeByCode.
     *
     * @param  string $code
     * @access public
     * @return string
     */
    public function getDateTypeByCode($code)
    {
        return $this->objectModel->getDateTypeByCode($code);
    }

    /**
     * Test isHeaderGroup.
     *
     * @param  array $header
     * @access public
     * @return bool
     */
    public function isHeaderGroup($header)
    {
        return $this->objectModel->isHeaderGroup($header);
    }

    /**
     * Test getExecutableMetric.
     *
     * @access public
     * @return array
     */
    public function getExecutableMetric()
    {
        return $this->objectModel->getExecutableMetric();
    }

    /**
     * Test getExecutableCalcList.
     *
     * @access public
     * @return array
     */
    public function getExecutableCalcList()
    {
        return $this->objectModel->getExecutableCalcList();
    }

    /**
     * Test getCalcInstanceList.
     *
     * @access public
     * @return array
     */
    public function getCalcInstanceList()
    {
        return $this->objectModel->getCalcInstanceList();
    }

    /**
     * Test getPieEchartsOptions.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  string $print
     * @access public
     * @return mixed
     */
    public function getPieEchartsOptions($header, $data, $print = 'all')
    {
        $options = $this->objectModel->getPieEchartsOptions($header, $data);
        if($print == 'all')    return $options;
        if($print == 'series') return $options['series'];
        if($print == 'data')   return $options['series'][0]['data'];
    }

    /**
     * Test getEchartXY.
     *
     * @param  array $header
     * @access public
     * @return bool
     */
    public function getEchartXY($header)
    {
        list($x, $y) = $this->objectModel->getEchartXY($header);
        return ($x && $y) ? $x . ',' . $y : '';
    }

    /**
     * Test isSystemMetric.
     *
     * @param  array $header
     * @access public
     * @return array
     */
    public function getChartTypeList($header)
    {
        return $this->objectModel->getChartTypeList($header);
    }

    /**
     * Test replaceCRLF.
     *
     * @param  string $str
     * @param  string $replace
     * @access public
     * @return bool
     */
    public function replaceCRLF($str, $replace = ';')
    {
        return $this->objectModel->replaceCRLF($str, $replace);
    }

    /**
     * Test isSystemMetric.
     *
     * @param  array $record
     * @access public
     * @return bool
     */
    public function isSystemMetric($record)
    {
        $result = array((object)$record);
        return $this->objectModel->isSystemMetric($result);
    }

    /**
     * Test getOldMetricByID.
     *
     * @param  int   $measurementID
     * @access public
     * @return object
     */
    public function getOldMetricByID($measurementID)
    {
        return $this->objectModel->getOldMetricByID($measurementID);
    }

    /**
     * Test buildDateCell.
     *
     * @param  array  $record
     * @param  string $dateType
     * @access public
     * @return object
     */
    public function buildDateCell($record, $dateType)
    {
        return $this->objectModel->buildDateCell($record, $dateType);
    }

    /**
     * Test getMetricCycle.
     *
     * @param  string $code
     * @access public
     * @return string|bool
     */
    public function getMetricCycle($record)
    {
        return $this->objectModel->getMetricCycle($record);
    }

    /**
     * Test getDateType.
     *
     * @param  array  $dateFields
     * @access public
     * @return string
     */
    public function getDateType($dateFields)
    {
        return $this->objectModel->getDateType($dateFields);
    }

    /**
     * Test generate data zoom config.
     *
     * @param  int    $dataLength
     * @param  int    $initZoom
     * @param  stirng $axis
     * @access public
     * @return array
     */
    public function genDataZoom($dataLength, $initZoom, $axis)
    {
        return $this->objectModel->genDataZoom($dataLength, $initZoom, $axis);
    }

    /**
     * Test getCalcRoot.
     *
     * @access public
     * @return string
     */
    public function getCalcRootTest()
    {
        $path = $this->objectModel->getCalcRoot();
        return substr($path, -19);
    }

    /**
     * Test getDatasetPath.
     *
     * @access public
     * @return string
     */
    public function getDatasetPathTest()
    {
        $path = $this->objectModel->getDatasetPath();
        return substr($path, -25);
    }

    /**
     * Test getBaseCalcPath.
     *
     * @access public
     * @return string
     */
    public function getBaseCalcPathTest()
    {
        $path = $this->objectModel->getBaseCalcPath();
        return substr($path, -28);
    }

    /**
     * Test parseSqlFunction.
     *
     * @param  string $sql
     * @access public
     * @return bool|string
     */
    public function parseSqlFunction($sql)
    {
        return $this->objectModel->parseSqlFunction($sql);
    }

    /**
     * Judge whether a metric has the concept of an object.
     *
     * @param  array  $header
     * @access public
     * @return bool
     */
    public function isObjectMetric($header)
    {
        $isObjectMetric = $this->objectModel->isObjectMetric($header);
        return $isObjectMetric == true ? 'true' : 'false';
    }

    /**
     * Judge whether a metric has the concept of an date.
     *
     * @param  array  $header
     * @access public
     * @return bool
     */
    public function isDateMetric($header)
    {
        $isDateMetric = $this->objectModel->isDateMetric($header);
        return $isDateMetric == true ? 'true' : 'false';
    }

    /**
     * Test setDefaultOptions.
     *
     * @param  array $options
     * @param  array $dataFields
     * @access public
     * @return array
     */
    public function setDefaultOptions($options, $dataFields)
    {
        return $this->objectModel->setDefaultOptions($options, $dataFields);
    }

    /**
     * Test isOldMetric.
     *
     * @param  object $metric
     * @access public
     * @return bool
     */
    public function isOldMetric($metric)
    {
        return $this->objectModel->isOldMetric($metric);
    }

    /**
     * Test getDateLabels.
     *
     * @param  string $dateType
     * @access public
     * @return array
     */
    public function getDateLabels($dateType)
    {
        $dateLabel = $this->objectModel->getDateLabels($dateType);
        if($dateLabel === array()) return 'empty array';
        return $dateLabel;
    }

    /**
     * Test getDefaultDate.
     *
     * @param  array  $dateLabels
     * @access public
     * @return string
     */
    public function getDefaultDate($dateLabels)
    {
        return $this->objectModel->getDefaultDate($dateLabels);
    }

    /**
     * Test getSqlFunctionName.
     *
     * @param  array  $dateLabels
     * @access public
     * @return string
     */
    public function getSqlFunctionName($measurementCode)
    {
        $measurement = new stdclass();
        $measurement->code = $measurementCode;

        return $this->objectModel->getSqlFunctionName($measurement);
    }

    /**
     * Test getCollectConf.
     *
     * @param  object $metric
     * @access public
     * @return string
     */
    public function getCollectConfText($metric)
    {
        return $this->objectModel->getCollectConfText($metric);
    }

    public function deduplication($code)
    {
        $this->objectModel->deduplication($code);

        $recordCount = $this->objectModel->dao->select('count(id) as num')->from(TABLE_METRICLIB)
            ->where('metricCode')->eq($code)
            ->fetch('num');

        return $recordCount;
    }
}
