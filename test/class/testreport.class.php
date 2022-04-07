<?php
class testreportTest
{
    public function __construct()
    {
        global $tester;
        global $app;
        $this->objectModel = $tester->loadModel('testreport');
        $this->testtask    = $tester->loadModel('testtask');
        $this->build       = $tester->loadModel('build');
        $app->loadLang('bug');
    }

    /**
     * Create report.
     *
     * @access public
     * @return int
     */
    public function createTest($param)
    {
        $begin_date = date('Y-m-d');
        $end_date   = date('Y-m-d',strtotime("+7 day"));
        $builds     = array('11');
        $labels     = array();

        $createFields = array('begin' => $begin_date, 'end' => $end_date, 'product' => '1', 'execution' => '101', 'tasks' => '1', 'objectID' => '1', 'objectType' => 'testtask', 'owner' => '', 'title' => '', 'report' => '', 'labels' => $labels, 'builds' => $builds, 'cases' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $reportID = $this->objectModel->create();
        unset($_POST);
        if(dao::isError()) return dao::getError();
        $objects = $this->objectModel->getByID($reportID);
        return $objects;
    }

    public function updateTest($reportID, $param)
    {
        $report = $this->objectModel->getByID($reportID);

        $begin_date = $report->begin;
        $end_date   = $report->end;
        $builds     = $report->builds;
        $labels     = array();

        $createFields = array('begin' => $begin_date, 'end' => $end_date, 'product' => $report->product, 'execution' => $report->execution, 'tasks' => $report->tasks, 'objectID' => $report->objectID, 'objectType' => $report->objectType, 'owner' => '', 'title' => '', 'report' => '', 'labels' => $labels, 'builds' => $builds, 'cases' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->update($reportID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($reportID);
        return $objects;
    }

    /**
     * Get report by id.
     *
     * @param  int    $reportID
     * @access public
     * @return object
     */

    public function getByIdTest($reportID)
    {
        $objects = $this->objectModel->getById($reportID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Get report list.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getListTest($objectID, $objectType, $extra = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($objectID, $objectType, $extra, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get bug info and summary.
     *
     * @param  array  $tasksIDs
     * @param  array  $productIdList
     * @param  int    $reportID
     * @param  array  $buildIDs
     * @access public
     * @return array
     */
    public function getBug4ReportTest($taskIDs, $productIdList, $reportID, $buildIDs)
    {
        $tasks   = $taskIDs ? $this->testtask->getByList($taskIDs) : array();
        $builds  = $buildIDs ? $this->build->getByList($buildIDs) : array();
        $report  = $this->objectModel->getByID($reportID);

        $objects = $this->objectModel->getBug4Report($tasks, $productIdList, $report->begin, $report->end, $builds);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get task cases.
     *
     * @param  array  $taskID
     * @param  int    $reportID
     * @access public
     * @return array
     */

    public function getTaskCasesTest($taskID, $reportID, $idList = '', $pager = null)
    {
        $task   = $taskID ? $this->testtask->getByList($taskID) : array();
        $report  = $this->objectModel->getByID($reportID);

        $objects = $this->objectModel->getTaskCases($task, $report->begin, $report->end, $idList, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
     /**
     * Get caseID list.
     *
     * @param  int    $reportID
     * @access public
     * @return array
     */

    public function getCaseIdListTest($reportID)
    {
        $objects = $this->objectModel->getCaseIdList($reportID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
   /**
     * Get result summary.
     *
     * @param  int    $taskID
     * @param  int    $reportID
     * @access public
     * @return string
     */

    public function getResultSummaryTest($taskID, $reportID)
    {
        $tasks   = $taskID ? $this->testtask->getByList($taskID) : array();
        $report = $this->objectModel->getByID($reportID);
        $cases  = $this->objectModel->getTaskCases($tasks, $report->begin, $report->end);

        $objects = $this->objectModel->getResultSummary($tasks, $cases, $report->begin, $report->end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get per run result for testreport.
     *
     * @param  int     $taskID
     * @param  int     $reportID
     * @access public
     * @return string
     */

    public function getPerCaseResult4ReportTest($taskID, $reportID)
    {
        $tasks   = $taskID ? $this->testtask->getByList($taskID) : array();
        $report = $this->objectModel->getByID($reportID);

        $objects = $this->objectModel->getPerCaseResult4Report($tasks, $report->cases, $report->begin, $report->end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Get per case runner for testreport.
     *
     * @param  int     $taskID
     * @param  int     $reportID
     * @access public
     * @return string
     */

    public function getPerCaseRunner4ReportTest($taskID, $reportID)
    {
        $tasks   = $taskID ? $this->testtask->getByList($taskID) : array();
        $report = $this->objectModel->getByID($reportID);
        $objects = $this->objectModel->getPerCaseRunner4Report($tasks, $report->cases, $report->begin, $report->end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Get bugs for test
     *
     * @param  array  $builds
     * @param  array  $product
     * @param  string $begin
     * @param  string $end
     * @param  string $type
     * @access public
     * @return void
     */

    public function getBugs4TestTest($buildIdList, $product, $taskID, $type = 'build')
    {
        $task    = $this->testtask->getByID($taskID);
        $builds  = $this->build->getByList($buildIdList);
        $begin   = !empty($begin) ? date("Y-m-d", strtotime($begin)) : $task->begin;
        $end     = !empty($end) ? date("Y-m-d", strtotime($end)) : $task->end;
        $objects = $this->objectModel->getBugs4Test($builds, $product, $begin, $end, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Get stories for test
     *
     * @param  array  $builds
     * @return void
     */
    public function getStories4TestTest($buildIdList)
    {
        $builds  = $this->build->getByList($buildIdList);
        $objects = $this->objectModel->getStories4Test($builds);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
    /**
     * Get pairs.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getPairsTest($productID = 0)
    {
        $objects = $this->objectModel->getPairs($productID);
        if(dao::isError()) return dao::getError();
        if($objects) $objects = array_keys($objects);
        return $objects;
    }
}
