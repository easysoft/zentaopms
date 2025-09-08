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
}