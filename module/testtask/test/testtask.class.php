<?php
class testtaskTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testtask');
    }

    /**
     * 初始化结果。
     * Init test results.
     *
     * @access public
     * @return void
     */
    public function initResult(): void
    {
        global $tester;
        $testResults = $tester->dao->select('*')->from(TABLE_TESTRESULT)->fetchAll();
        foreach($testResults as $testResult)
        {
            if($testResult->caseResult == 'fail')
            {
                $tester->dao->update(TABLE_TESTRESULT)->set('`stepResults`')->eq('a:1:{i:'.$testResult->run.';a:2:{s:6:"result";s:4:"fail";s:4:"real";s:0:"";}}')->where('id')->eq($testResult->id)->exec();
            }
            else
            {
                $tester->dao->update(TABLE_TESTRESULT)->set('`stepResults`')->eq('a:1:{i:'.$testResult->run.';a:2:{s:6:"result";s:4:"pass";s:4:"real";s:0:"";}}')->where('id')->eq($testResult->id)->exec();
            }
        }
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

    /**
     * 测试批量用例到一个测试单。
     * Test batch link cases to a testtask.
     *
     * @param  int    $taskID
     * @param  string $type     all|bystory|bysuite|bybuild|bybug
     * @param  array  $runs
     * @access public
     * @return bool|array
     */
    public function linkCaseTest(int $taskID, string $type, array $runs): bool|array
    {
        $result = $this->objectModel->linkCase($taskID, $type, $runs);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $limit   = count($runs);
        $runs    = $this->objectModel->dao->select('task, `case`, version, assignedTo, status')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->orderBy('id_desc')->limit($limit)->fetchAll();
        $actions = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit($limit)->fetchAll();

        $cases = array();
        $tab   = $this->objectModel->app->tab;
        if($tab == 'project' || $tab == 'execution')
        {
            $project = $tab == 'project' ? $this->objectModel->session->project : $this->objectModel->session->execution;
            $cases   = $this->objectModel->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($project)->limit($limit)->fetchAll();
        }

        return array('runs' => $runs, 'cases' => $cases, 'actions' => $actions);
    }

    /**
     * 测试获取一个产品下的测试单键值对。
     * Test get key-value pairs of testtasks of a product.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $appendTaskID
     * @access public
     * @return int
     */
    public function getPairsTest(int $productID, int $executionID = 0, int $appendTaskID = 0): int
    {
        $pairs = $this->objectModel->getPairs($productID, $executionID, $appendTaskID);

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
     * @access public
     * @return array|object
     */
    public function getRunByIdTest($runID): array|object
    {
        $object = $this->objectModel->getRunById($runID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试执行一条测试用例后记录结果。
     * Test record result of a test case.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  array  $stepResults
     * @access public
     * @return bool|array
     */
    public function createResultTest(int $runID, int $caseID, int $version, array $stepResults = array()): bool|array
    {
        $caseResult = $this->objectModel->createResult($runID, $caseID, $version, $stepResults);
        if(dao::isError()) return dao::getError();
        if(!$caseResult) return $caseResult;

        $case   = $this->objectModel->dao->select('lastRunner, lastRunDate, lastRunResult')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
        $result = $this->objectModel->dao->select('lastRunner, run, `case`, version, caseResult, stepResults')->from(TABLE_TESTRESULT)->where('`case`')->eq($caseID)->orderBy('id_desc')->limit(1)->fetch();
        $run    = $this->objectModel->dao->select('lastRunner, lastRunDate, lastRunResult, status')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch();

        return array('caseResult' => $caseResult, 'case' => $case, 'result' => $result, 'run' => $run);
    }

    /**
     * 测试批量执行测试用例后记录结果。
     * Test batch record result of test cases.
     *
     * @param  array  $cases
     * @param  string $runCaseType
     * @param  int    $taskID
     * @access public
     * @return array|object
     */
    public function batchRunTest(array $cases, string $runCaseType = 'testcase', int $taskID = 0): bool|array
    {
        $result = $this->objectModel->batchRun($cases, $runCaseType, $taskID);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $caseIdList = array_keys($cases);

        $cases   = $this->objectModel->dao->select('id, lastRunner, lastRunDate, lastRunResult')->from(TABLE_CASE)->where('id')->in($caseIdList)->fetchAll('id');
        $results = $this->objectModel->dao->select('lastRunner, date, run, `case`, version, caseResult, stepResults')->from(TABLE_TESTRESULT)->fetchAll('case');
        $runs    = $this->objectModel->dao->select('id, lastRunner, lastRunDate, lastRunResult, status')->from(TABLE_TESTRUN)->fetchAll('id');
        $actions = $this->objectModel->dao->select('objectType,objectID,action,extra')->from(TABLE_ACTION)->fetchAll('objectID');

        return array('cases' => $cases, 'results' => $results, 'runs' => $runs, 'actions' => $actions);
    }

    /**
     * 测试获取用例执行结果。
     * Test get results.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  string $status
     * @param  string $type
     * @access public
     * @return array
     */
    public function getResultsTest(int $runID, int $caseID = 0, string $status = 'all', string $type = 'all'): array
    {
        $objects = $this->objectModel->getResults($runID, $caseID, $status, $type);
        foreach($objects as $object)
        {
            if($object->stepResults) $object->stepResults = implode(',', array_keys($object->stepResults));
        }

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

    /**
     * 测试计算用例执行结果的步骤
     * Test process result steps.
     *
     * @param  int    $resultID
     * @access public
     * @return string|array
     */
    public function processResultStepsTest(int $resultID): string|array
    {
        global $tester;

        $result = $tester->dao->select('*')->from(TABLE_TESTRESULT)->where('id')->eq($resultID)->fetch();

        $relatedSteps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('case')->eq($result->case)->orderBy('id')->fetchAll('id');
        $result->stepResults = unserialize($result->stepResults);

        $objects = $this->objectModel->processResultSteps($result, $relatedSteps);

        $return = '';
        foreach($objects as $object)
        {
            $return .= "{$object['id']}, {$object['name']}, {$object['grade']}";
            if(isset($object['result'])) $return .= ", {$object['result']}, {$object['real']}";
            $return = trim($return) . '; ';
        }

        if(dao::isError()) return dao::getError();

        return trim($return);
    }

    /**
     * 测试结果的文件。
     * Test get files of the results
     *
     * @param  array  $resultIdList
     * @access public
     * @return array
     */
    public function getResultsFilesTest(array $resultIdList): array
    {
        list($resultFiles, $stepFiles) = $this->objectModel->getResultsFiles($resultIdList);

        if(dao::isError()) return dao::getError();

        $return = array('result' => '', 'step' => '');

        foreach($resultFiles as $resultID => $files)
        {
            $return['result'] .= "{$resultID}: ";
            $return['result'] .= implode(',', array_keys($files));
            $return['result'] .= " ";
        }
        $return['result'] = trim($return['result']);

        foreach($stepFiles as $stepID => $extraFiles)
        {
            $return['step'] .= "{$stepID}: ";
            foreach($extraFiles as $files) $return['step'] .= implode(',', array_keys($files));
            $return['step'] .= " ";
        }
        $return['step'] = trim($return['step']);

        return $return;
    }

    /**
     * 测试通过 ID 列表获取测试单键对。
     * Test get testtask pairs by id list.
     *
     * @param  string       $taskIdList
     * @access public
     * @return string|array
     */
    public function getPairsByListTest(string $taskIdList): string|array
    {
        $tasks = $this->objectModel->getPairsByList(explode(',', $taskIdList));

        if(dao::isError()) return dao::getError();
        return implode(',', $tasks);
    }
}
