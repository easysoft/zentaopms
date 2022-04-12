<?php
class testtaskTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testtask');
    }

    /**
     * Test create testtask.
     * 
     * @param  int   $projectID 
     * @param  array $params 
     * @access public
     * @return void
     */
    public function create($projectID, $params)
    {
        $_POST  = $params;
        $taskID = $this->objectModel->create($projectID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getById($taskID);
    }

    public function update($taskID)
    {
        $objects = $this->objectModel->update($taskID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function startTest($taskID)
    {
        $objects = $this->objectModel->start($taskID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function closeTest($taskID)
    {
        $objects = $this->objectModel->close($taskID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function blockTest($taskID)
    {
        $objects = $this->objectModel->block($taskID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function activateTest($taskID)
    {
        $objects = $this->objectModel->activate($taskID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function linkCaseTest($taskID, $type)
    {
        $objects = $this->objectModel->linkCase($taskID, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRunsTest($taskID, $moduleID, $orderBy, $pager = null)
    {
        $objects = $this->objectModel->getRuns($taskID, $moduleID, $orderBy, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserRunsTest($taskID, $user, $modules = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getUserRuns($taskID, $user, $modules = '', $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskCasesTest($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task)
    {
        $objects = $this->objectModel->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserTestTaskPairsTest($account, $limit = 0, $status = 'all', $skipProductIDList = array(), $skipExecutionIDList = array())
    {
        $objects = $this->objectModel->getUserTestTaskPairs($account, $limit = 0, $status = 'all', $skipProductIDList = array(), $skipExecutionIDList = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRunByIdTest($runID)
    {
        $objects = $this->objectModel->getRunById($runID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createResultTest($runID = 0)
    {
        $objects = $this->objectModel->createResult($runID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchRunTest($runCaseType = 'testcase', $taskID = 0)
    {
        $objects = $this->objectModel->batchRun($runCaseType = 'testcase', $taskID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getResultsTest($runID, $caseID = 0)
    {
        $objects = $this->objectModel->getResults($runID, $caseID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printCellTest($col, $run, $users, $task, $branches, $mode = 'datatable')
    {
        $objects = $this->objectModel->printCell($col, $run, $users, $task, $branches, $mode = 'datatable');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getToAndCcListTest($testtask)
    {
        $objects = $this->objectModel->getToAndCcList($testtask);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function importUnitResultTest($productID)
    {
        $objects = $this->objectModel->importUnitResult($productID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processAutoResultTest($testtaskID, $productID, $suites, $cases, $results, $suiteNames = array(), $caseTitles = array(), $auto = 'unit')
    {
        $objects = $this->objectModel->processAutoResult($testtaskID, $productID, $suites, $cases, $results, $suiteNames = array(), $caseTitles = array(), $auto = 'unit');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseCppXMLResultTest($fileName, $productID, $frame)
    {
        $objects = $this->objectModel->parseCppXMLResult($fileName, $productID, $frame);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseXMLResultTest($fileName, $productID, $frame)
    {
        $objects = $this->objectModel->parseXMLResult($fileName, $productID, $frame);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseZTFUnitResultTest($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $objects = $this->objectModel->parseZTFUnitResult($caseResults, $frame, $productID, $jobID, $compileID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseZTFFuncResultTest($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $objects = $this->objectModel->parseZTFFuncResult($caseResults, $frame, $productID, $jobID, $compileID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
