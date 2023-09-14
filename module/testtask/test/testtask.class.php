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

    /**
     * 测试更新测试单。
     * Test update a test task.
     *
     * @param  object $task
     * @access public
     * @return array|object
     */
    public function updateTest(object $task): array|object
    {
        global $tester;
        $oldTask = $tester->dao->findByID($task->id)->from(TABLE_TESTTASK)->fetch();
        foreach(explode(',', $tester->config->testtask->create->requiredFields) as $field)
        {
            if(!isset($task->{$field})) $task->{$field} = $oldTask->{$field};
        }
        $changes = $this->objectModel->update($task, $oldTask);

        if(dao::isError()) return dao::getError();

        $task = $tester->dao->findByID($task->id)->from(TABLE_TESTTASK)->fetch();
        return $task;
    }

    /**
     * 测试开始一个测试单。
     * Test start a testtask.
     *
     * param  array  $task
     * access public
     * return bool|array
     */
    public function startTest(array $task): bool|array
    {
        $result = $this->objectModel->start((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task    = $this->objectModel->fetchByID($task['id']);
        $action  = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
        $history = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();

        return array('task' => $task, 'action' => $action, 'history' => $history);
    }

    /**
     * 测试关闭一个测试单。
     * Test close a testtask.
     *
     * param  array  $task
     * access public
     * return bool|array
     */
    public function closeTest(array $task): bool|array
    {
        $result = $this->objectModel->close((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task    = $this->objectModel->fetchByID($task['id']);
        $action  = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
        $history = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();

        return array('task' => $task, 'action' => $action, 'history' => $history);
    }

    /**
     * 测试阻塞一个测试单。
     * Test block a testtask.
     *
     * param  array  $task
     * access public
     * return bool|array
     */
    public function blockTest(array $task): bool|array
    {
        $result = $this->objectModel->block((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task    = $this->objectModel->fetchByID($task['id']);
        $action  = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
        $history = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();

        return array('task' => $task, 'action' => $action, 'history' => $history);
    }

    /**
     * 测试激活一个测试单。
     * Test activate a testtask.
     *
     * param  array  $task
     * access public
     * return bool|array
     */
    public function activateTest(array $task): bool|array
    {
        $result = $this->objectModel->activate((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task    = $this->objectModel->fetchByID($task['id']);
        $action  = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
        $history = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();

        return array('task' => $task, 'action' => $action, 'history' => $history);
    }

    /**
     * 测试从一个测试单移除一个用例。
     * Test remove a case from a testtask.
     *
     * @param  int    $runID
     * @access public
     * @return bool|array
     */
    public function unlinkCaseTest(int $runID): bool|array
    {
        $result = $this->objectModel->unlinkCase($runID);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $run    = $this->objectModel->dao->select('COUNT(*) AS count')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch('count');
        $cases  = $this->objectModel->dao->select('project, `case`')->from(TABLE_PROJECTCASE)->fetchAll();
        $action = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();

        return array('run' => $run, 'cases' => implode(',', array_column($cases, 'case')), 'action' => $action);
    }

    /**
     * 测试批量从一个测试单移除用例。
     * Test batch remove cases from a testtask.
     *
     * @param  int    $taskID
     * @param  array  $caseIdList
     * @access public
     * @return bool|array
     */
    public function batchUnlinkCasesTest(int $taskID, array $caseIdList): bool|array
    {
        $result = $this->objectModel->batchUnlinkCases($taskID, $caseIdList);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $cases   = $this->objectModel->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();
        $actions = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(count($caseIdList))->fetchAll();

        return array('cases' => implode(',', $cases), 'actions' => $actions);
    }

    /**
     * 测试批量指派一个测试单中的用例。
     * Test batch assign cases in a testtask.
     *
     * @param  int    $taskID
     * @param  string $account
     * @param  array  $caseIdList
     * @access public
     * @return bool|array
     */
    public function batchAssignTest(int $taskID, string $account, array $caseIdList): bool|array
    {
        $result = $this->objectModel->batchAssign($taskID, $account, $caseIdList);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $cases   = $this->objectModel->dao->select('`case`, assignedTo')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();
        $actions = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(count($caseIdList))->fetchAll();

        return array('cases' => implode(',', $cases), 'actions' => $actions);
    }

    public function linkCaseTest($taskID, $type)
    {
        $objects = $this->objectModel->linkCase($taskID, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取一个产品下的测试单键值对。
     * Test get key-value pairs of testtasks of a product.
     *
     * @param  int          $productID
     * @param  int          $executionID
     * @param  string|array $appendIdList
     * @access public
     * @return int
     */
    public function getPairsTest(int $productID, int $executionID = 0, string|array $appendIdList = ''): int
    {
        $pairs = $this->objectModel->getPairs($productID, $executionID, $appendIdList);

        return count($pairs);
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

    /**
     * 测试获取执行结果。
     * Test get info of a test run.
     *
     * @param  int          $runID
     * @param  int          $caseID
     * @param  int          $version
     * @param  string       $status  all|done
     * @param  string       $type    all|fail
     * @access public
     * @return array|object
     */
    public function getRunByIdTest($runID): array|object
    {
        $object = $this->objectModel->getRunById($runID);

        if(dao::isError()) return dao::getError();

        return $object;
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
