<?php
declare(strict_types = 1);
class metricTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('metric');
        $this->objectTao   = $tester->loadTao('metric');
    }

    /**
     * Test getViewTableData method.
     *
     * @param  object $metric
     * @param  array  $result
     * @access public
     * @return mixed
     */
    public function getViewTableDataTest($metric = null, $result = null)
    {
        $result = $this->objectModel->getViewTableData($metric, $result);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTimeTable method.
     *
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $withCalcTime
     * @access public
     * @return mixed
     */
    public function getTimeTableTest($data = null, $dateType = 'day', $withCalcTime = true)
    {
        $result = $this->objectModel->getTimeTable($data, $dateType, $withCalcTime);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getObjectTable method.
     *
     * @param  array  $header
     * @param  array  $data
     * @param  string $dateType
     * @param  bool   $withCalcTime
     * @access public
     * @return mixed
     */
    public function getObjectTableTest($header = null, $data = null, $dateType = 'day', $withCalcTime = true)
    {
        $result = $this->objectModel->getObjectTable($header, $data, $dateType, $withCalcTime);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMetricsByCodeList method.
     *
     * @param  array $codeList
     * @access public
     * @return mixed
     */
    public function getMetricsByCodeListTest($codeList = null)
    {
        $result = $this->objectModel->getMetricsByCodeList($codeList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRecordCalcInfo method.
     *
     * @param  mixed $recordID
     * @access public
     * @return mixed
     */
    public function getRecordCalcInfoTest($recordID = null)
    {
        $result = $this->objectModel->getRecordCalcInfo($recordID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDAO method.
     *
     * @access public
     * @return mixed
     */
    public function getDAOTest()
    {
        $result = $this->objectModel->getDAO();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDataStatement method.
     *
     * @param  object $calculator
     * @param  string $returnType
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getDataStatementTest($calculator = null, $returnType = 'statement', $vision = 'rnd')
    {
        $result = $this->objectModel->getDataStatement($calculator, $returnType, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test calculateMetricByCode method.
     *
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function calculateMetricByCodeTest($code = null)
    {
        $result = $this->objectModel->calculateMetricByCode($code);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getResultByCodeWithArray method.
     *
     * @param  string $code
     * @param  array  $options
     * @param  string $type
     * @param  object $pager
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function getResultByCodeWithArrayTest($code = null, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        $result = $this->objectModel->getResultByCodeWithArray($code, $options, $type, $pager, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test mergeRecord method.
     *
     * @param  array $record
     * @param  array $result
     * @access public
     * @return mixed
     */
    public function mergeRecordTest($record = null, $result = array())
    {
        $result = $this->objectModel->mergeRecord($record, $result);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUniqueKeyByRecord method.
     *
     * @param  array $record
     * @access public
     * @return mixed
     */
    public function getUniqueKeyByRecordTest($record = null)
    {
        $result = $this->objectModel->getUniqueKeyByRecord($record);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCalculator method.
     *
     * @param  string $scope
     * @param  string $purpose
     * @param  string $code
     * @access public
     * @return mixed
     */
    public function getCalculatorTest($scope = null, $purpose = null, $code = null)
    {
        $result = $this->objectModel->getCalculator($scope, $purpose, $code);
        if(dao::isError()) return dao::getError();

        if(is_object($result) && isset($result->fieldList)) return get_class($result);
        return $result;
    }

    /**
     * Test calculateReuseMetric method.
     *
     * @param  object $calculator
     * @param  array  $options
     * @param  string $type
     * @param  object $pager
     * @param  string $vision
     * @access public
     * @return mixed
     */
    public function calculateReuseMetricTest($calculator = null, $options = array(), $type = 'realtime', $pager = null, $vision = 'rnd')
    {
        $result = $this->objectModel->calculateReuseMetric($calculator, $options, $type, $pager, $vision);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}