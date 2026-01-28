<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class testtaskModelTest extends baseTest
{
    protected $moduleName = 'testtask';
    protected $className  = 'model';

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
        $taskID = $this->instance->create($projectID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->instance->getById($taskID);
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
        $changes = $this->instance->update($task, $oldTask);

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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->start((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task = $this->instance->fetchByID($task['id']);
        return array('task' => $task);
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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->close((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task = $this->instance->fetchByID($task['id']);
        return array('task' => $task);
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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->block((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task = $this->instance->fetchByID($task['id']);
        return array('task' => $task);
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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->activate((object)$task);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $task = $this->instance->fetchByID($task['id']);
        return array('task' => $task);
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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->unlinkCase($runID);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $run    = $this->instance->dao->select('COUNT(1) AS count')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch('count');
        $cases  = $this->instance->dao->select('project, `case`')->from(TABLE_PROJECTCASE)->fetchAll();
        $action = $this->instance->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();

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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->batchUnlinkCases($taskID, $caseIdList);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $cases   = $this->instance->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();
        $actions = $this->instance->dao->select('objectType,objectID,action,extra')->from(TABLE_ACTION)->orderBy('id_desc')->limit(count($caseIdList))->fetchAll();

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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->batchAssign($taskID, $account, $caseIdList);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $cases   = $this->instance->dao->select('`case`, assignedTo')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();
        $actions = $this->instance->dao->select('objectType,objectID,action,extra')->from(TABLE_ACTION)->orderBy('id_desc')->limit(count($caseIdList))->fetchAll();

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
        zendata('history')->gen(0);
        zendata('action')->gen(0);
        $result = $this->instance->linkCase($taskID, $type, $runs);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $limit   = count($runs);
        $runs    = $this->instance->dao->select('task, `case`, version, assignedTo, status')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->orderBy('id_desc')->limit($limit)->fetchAll();
        $actions = $this->instance->dao->select('objectType, objectID, action, extra')->from(TABLE_ACTION)->orderBy('id_desc')->limit($limit)->fetchAll();
        $task    = $this->instance->dao->select('*')->from(TABLE_TESTTASK)->where('id')->eq($taskID)->fetch();

        $cases = array();
        $tab   = $this->instance->app->tab;
        if(!empty($task->project) || !empty($task->execution))
        {
            $project = !empty($task->execution) ? $task->execution : $task->project;
            $cases   = $this->instance->dao->select('project, product, `case`, version, `order`')->from(TABLE_PROJECTCASE)->where('project')->eq($project)->limit($limit)->fetchAll();
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
        $pairs = $this->instance->getPairs($productID, $executionID, $appendTaskID);

        return count($pairs);
    }

    public function getRunsTest($taskID, $moduleID, $orderBy, $pager = null)
    {
        $objects = $this->instance->getRuns($taskID, $moduleID, $orderBy, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserRunsTest($taskID, $user, $modules = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->instance->getUserRuns($taskID, $user, $modules = '', $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskCasesTest($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task)
    {
        $objects = $this->instance->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserTestTaskPairsTest($account, $limit = 0, $status = 'all', $skipProductIDList = array(), $skipExecutionIDList = array())
    {
        $objects = $this->instance->getUserTestTaskPairs($account, $limit = 0, $status = 'all', $skipProductIDList = array(), $skipExecutionIDList = array());

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
        $object = $this->instance->getRunById($runID);

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
        $caseResult = $this->instance->createResult($runID, $caseID, $version, $stepResults);
        if(dao::isError()) return dao::getError();
        if(!$caseResult) return $caseResult;

        $case   = $this->instance->dao->select('lastRunner, lastRunDate, lastRunResult')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
        $result = $this->instance->dao->select('lastRunner, run, `case`, version, caseResult, stepResults')->from(TABLE_TESTRESULT)->where('`case`')->eq($caseID)->orderBy('id_desc')->limit(1)->fetch();
        $run    = $this->instance->dao->select('lastRunner, lastRunDate, lastRunResult, status')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch();

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
        zendata('action')->gen(0);
        $result = $this->instance->batchRun($cases, $runCaseType, $taskID);
        if(dao::isError()) return dao::getError();
        if(!$result) return $result;

        $caseIdList = array_keys($cases);

        $cases   = $this->instance->dao->select('id, lastRunner, lastRunDate, lastRunResult')->from(TABLE_CASE)->where('id')->in($caseIdList)->fetchAll('id');
        $results = $this->instance->dao->select('lastRunner, date, run, `case`, version, caseResult, stepResults')->from(TABLE_TESTRESULT)->fetchAll('case');
        $runs    = $this->instance->dao->select('id, lastRunner, lastRunDate, lastRunResult, status')->from(TABLE_TESTRUN)->fetchAll('id');
        $actions = $this->instance->dao->select('objectType,objectID,action,extra')->from(TABLE_ACTION)->fetchAll('objectID');

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
        $objects = $this->instance->getResults($runID, $caseID, $status, $type);
        foreach($objects as $object)
        {
            if($object->stepResults) $object->stepResults = implode(',', array_keys($object->stepResults));
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printCellTest($col, $run, $users, $task, $branches, $mode = 'datatable')
    {
        $objects = $this->instance->printCell($col, $run, $users, $task, $branches, $mode = 'datatable');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getToAndCcListTest($testtask)
    {
        $objects = $this->instance->getToAndCcList($testtask);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test importUnitResult method.
     *
     * @param  object $task
     * @param  string $scenario
     * @access public
     * @return mixed
     */
    public function importUnitResultTest(object $task, string $scenario = 'default')
    {
        global $tester;

        // 清理之前的session数据
        unset($_SESSION['resultFile']);

        if($scenario == 'nofile')
        {
            // 模拟无文件上传的情况
            $_SESSION['resultFile'] = array();
            $result = $this->instance->importUnitResult($task);
            return dao::isError() ? 'false' : ($result === false ? 'false' : $result);
        }
        elseif($scenario == 'valid')
        {
            // 模拟有效的XML文件上传
            $validXml = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="ExampleTest" tests="2" failures="0" errors="0" time="0.042">
  <testcase classname="ExampleTest" name="testExample1" time="0.021"/>
  <testcase classname="ExampleTest" name="testExample2" time="0.021"/>
</testsuite>';

            // 创建临时文件
            $tempFile = sys_get_temp_dir() . '/valid_junit_' . uniqid() . '.xml';
            file_put_contents($tempFile, $validXml);

            // 模拟上传文件结构
            $_SESSION['resultFile'] = array(
                array(
                    'pathname'  => 'valid_junit.xml',
                    'title'     => 'valid_junit.xml',
                    'size'      => strlen($validXml),
                    'extension' => 'xml',
                    'tmpname'   => $tempFile
                )
            );

            try {
                $result = $this->instance->importUnitResult($task);
                if(file_exists($tempFile)) @unlink($tempFile);
                if(dao::isError()) return 'false';
                return is_numeric($result) && $result > 0 ? (string)$result : 'false';
            } catch(Throwable $e) {
                if(file_exists($tempFile)) @unlink($tempFile);
                return 'false';
            }
        }
        elseif($scenario == 'invalid')
        {
            // 模拟无效XML文件
            $invalidXml = '<invalid>xml</content>';
            $tempFile = sys_get_temp_dir() . '/invalid_' . uniqid() . '.xml';
            file_put_contents($tempFile, $invalidXml);

            $_SESSION['resultFile'] = array(
                array(
                    'pathname'  => 'invalid.xml',
                    'title'     => 'invalid.xml',
                    'size'      => strlen($invalidXml),
                    'extension' => 'xml',
                    'tmpname'   => $tempFile
                )
            );

            try {
                $result = $this->instance->importUnitResult($task);
                if(file_exists($tempFile)) @unlink($tempFile);
                return dao::isError() ? 'false' : ($result === false ? 'false' : $result);
            } catch(Throwable $e) {
                if(file_exists($tempFile)) @unlink($tempFile);
                return 'false';
            }
        }
        elseif($scenario == 'empty')
        {
            // 模拟空XML文件（无测试用例）
            $emptyXml = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="EmptyTest" tests="0" failures="0" errors="0" time="0">
</testsuite>';
            $tempFile = sys_get_temp_dir() . '/empty_' . uniqid() . '.xml';
            file_put_contents($tempFile, $emptyXml);

            $_SESSION['resultFile'] = array(
                array(
                    'pathname'  => 'empty.xml',
                    'title'     => 'empty.xml',
                    'size'      => strlen($emptyXml),
                    'extension' => 'xml',
                    'tmpname'   => $tempFile
                )
            );

            try {
                $result = $this->instance->importUnitResult($task);
                if(file_exists($tempFile)) @unlink($tempFile);
                return dao::isError() ? 'false' : ($result === false ? 'false' : $result);
            } catch(Throwable $e) {
                if(file_exists($tempFile)) @unlink($tempFile);
                return 'false';
            }
        }

        // 默认情况（无文件）
        try {
            $result = $this->instance->importUnitResult($task);
            return dao::isError() ? 'false' : ($result === false ? 'false' : $result);
        } catch(Throwable $e) {
            return 'false';
        }
    }

    public function parseCppXMLResultTest($fileName, $productID, $frame)
    {
        $objects = $this->instance->parseCppXMLResult($fileName, $productID, $frame);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseXMLResultTest($fileName, $productID, $frame)
    {
        $objects = $this->instance->parseXMLResult($fileName, $productID, $frame);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseZTFUnitResultTest($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $objects = $this->instance->parseZTFUnitResult($caseResults, $frame, $productID, $jobID, $compileID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseZTFFuncResultTest($caseResults, $frame, $productID, $jobID, $compileID)
    {
        $objects = $this->instance->parseZTFFuncResult($caseResults, $frame, $productID, $jobID, $compileID);

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

        $relatedSteps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('case')->eq($result->case)->orderBy('id')->fetchAll('id', false);
        $result->stepResults = unserialize($result->stepResults);

        $objects = $this->instance->processResultSteps($result, $relatedSteps);

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
        list($resultFiles, $stepFiles) = $this->instance->getResultsFiles($resultIdList);

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
        $tasks = $this->instance->getPairsByList(explode(',', $taskIdList));

        if(dao::isError()) return dao::getError();
        return implode(',', $tasks);
    }

    /**
     * 测试更新测试单状态。
     * Test updateStatus a testtask.
     *
     * @param  int               $taskID
     * @access public
     * @return object|array|bool
     */
    public function updateStatusTest(int $taskID): object|array|bool
    {
        $this->instance->updateStatus($taskID);
        if(dao::isError()) return dao::getError();

        return $this->instance->fetchByID($taskID);
    }

    /**
     * 测试更新测试单状态。
     * Test assign a case in testtask.
     *
     * @param  int               $runID
     * @access public
     * @return object|array|bool
     */
    public function assignCaseTest(int $runID, string $account): object|array|bool
    {
        $oldRun = $this->instance->dao->select('*')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch();
        if(!$oldRun) $oldRun = new stdclass();

        $run = new stdclass();
        $run->id         = $runID;
        $run->assignedTo = $account;
        $run->uid        = '';
        $run->comment    = '';

        $this->instance->assignCase($run, $oldRun);
        if(dao::isError()) return dao::getError();

        $newRun = $this->instance->dao->select('*')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch();
        return $newRun ? $newRun : false;
    }

    /**
     * Test processExecutionName method.
     *
     * @param  array $tasks
     * @access public
     * @return array
     */
    public function processExecutionNameTest(array $tasks): array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('processExecutionName');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($tasks));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRunByCase method.
     *
     * @param  int $taskID
     * @param  int $caseID
     * @access public
     * @return mixed
     */
    public function getRunByCaseTest(int $taskID, int $caseID)
    {
        $result = $this->instance->getRunByCase($taskID, $caseID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test addPrefixToOrderBy method.
     *
     * @param  string $orderBy
     * @access public
     * @return string
     */
    public function addPrefixToOrderByTest(string $orderBy): string
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('addPrefixToOrderBy');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($orderBy));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSceneCases method.
     *
     * @param  int   $productID
     * @param  array $runs
     * @access public
     * @return array
     */
    public function getSceneCasesTest(int $productID, array $runs): array
    {
        $result = $this->instance->getSceneCases($productID, $runs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupByCases method.
     *
     * @param  int|array $caseIDList
     * @access public
     * @return array
     */
    public function getGroupByCasesTest($caseIDList)
    {
        $result = $this->instance->getGroupByCases($caseIDList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initResultForAutomatedTest method.
     *
     * @param  int $runID
     * @param  int $caseID
     * @param  int $version
     * @param  int $nodeID
     * @access public
     * @return int|false
     */
    public function initResultForAutomatedTestTest(int $runID = 0, int $caseID = 0, int $version = 0, int $nodeID = 0)
    {
        $result = $this->instance->initResultForAutomatedTest($runID, $caseID, $version, $nodeID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processStepResults method.
     *
     * @param  array  $caseIdList
     * @param  int    $caseID
     * @param  string $caseResult
     * @param  array  $postSteps
     * @param  array  $postReals
     * @access public
     * @return array
     */
    public function processStepResultsTest(array $caseIdList, int $caseID, string $caseResult, array $postSteps, array $postReals): array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('processStepResults');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$caseIdList, $caseID, $caseResult, $postSteps, $postReals]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test formatZtfLog method.
     *
     * @param  string $result
     * @param  array  $stepResults
     * @access public
     * @return string
     */
    public function formatZtfLogTest(string $result, array $stepResults): string
    {
        $result = $this->instance->formatZtfLog($result, $stepResults);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test importDataOfUnitResult method.
     *
     * @param  int    $taskID
     * @param  int    $productID
     * @param  array  $suites
     * @param  array  $cases
     * @param  array  $results
     * @param  array  $suiteNames
     * @param  array  $caseTitles
     * @param  string $auto
     * @access public
     * @return bool
     */
    public function importDataOfUnitResultTest(int $taskID, int $productID, array $suites, array $cases, array $results, array $suiteNames, array $caseTitles, string $auto = 'unit'): bool
    {
        $result = $this->instance->importDataOfUnitResult($taskID, $productID, $suites, $cases, $results, $suiteNames, $caseTitles, $auto);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test importSuiteOfUnitResult method.
     *
     * @param  object $suite
     * @access public
     * @return int
     */
    public function importSuiteOfUnitResultTest(object $suite): int
    {
        $this->instance->loadModel('action');

        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('importSuiteOfUnitResult');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$suite]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test importRunOfUnitResult method.
     *
     * @param  object $case
     * @param  int    $caseID
     * @param  object $testRun
     * @access public
     * @return mixed
     */
    public function importRunOfUnitResultTest(object $case, int $caseID, object $testRun)
    {
        // 确保case对象包含必需的属性
        if(!isset($case->version)) $case->version = 1;
        if(!isset($case->lastRunner)) $case->lastRunner = '';
        if(!isset($case->lastRunDate)) $case->lastRunDate = '';
        if(!isset($case->lastRunResult)) $case->lastRunResult = '';

        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('importRunOfUnitResult');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->instance, [$case, $caseID, $testRun]);
            if(dao::isError()) {
                $errors = dao::getError();
                return 'dao_error: ' . (is_array($errors) ? implode(', ', $errors) : $errors);
            }

            // 返回插入成功的标志，而不是具体的ID
            return $result > 0 ? 'success' : 'failed';
        } catch(Exception $e) {
            return 'exception: ' . $e->getMessage();
        } catch(Error $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test linkImportedCaseToSuite method.
     *
     * @param  object $case
     * @param  int    $caseID
     * @param  object $suiteCase
     * @access public
     * @return mixed
     */
    public function linkImportedCaseToSuiteTest(object $case, int $caseID, object $suiteCase)
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('linkImportedCaseToSuite');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->instance, [$case, $caseID, $suiteCase]);
            if(dao::isError()) {
                $errors = dao::getError();
                return 'dao_error: ' . (is_array($errors) ? implode(', ', $errors) : $errors);
            }
            return $result;
        } catch(Exception $e) {
            return 'exception: ' . $e->getMessage();
        } catch(Error $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test getExistSuitesOfUnitResult method.
     *
     * @param  array  $names
     * @param  int    $productID
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getExistSuitesOfUnitResultTest(array $names, int $productID, string $auto): array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getExistSuitesOfUnitResult');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$names, $productID, $auto]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getExistCasesOfUnitResult method.
     *
     * @param  array  $titles
     * @param  int    $suiteID
     * @param  int    $productID
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getExistCasesOfUnitResultTest(array $titles, int $suiteID, int $productID, string $auto): array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getExistCasesOfUnitResult');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$titles, $suiteID, $productID, $auto]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initSuite method.
     *
     * @param  int    $product
     * @param  string $name
     * @param  string $now
     * @access public
     * @return object
     */
    public function initSuiteTest(int $product, string $name, string $now): object
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('initSuite');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$product, $name, $now]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initCase method.
     *
     * @param  int    $product
     * @param  string $title
     * @param  string $now
     * @param  string $auto
     * @param  string $frame
     * @param  string $type
     * @param  string $stage
     * @access public
     * @return object
     */
    public function initCaseTest(int $product, string $title, string $now, string $auto, string $frame, string $type = 'unit', string $stage = 'unittest'): object
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('initCase');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$product, $title, $now, $auto, $frame, $type, $stage]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initResult method.
     *
     * @param  string $now
     * @access public
     * @return object
     */
    public function initResultTest(string $now): object
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('initResult');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, [$now]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processResult method.
     *
     * @param  object $result
     * @param  object $matchNode
     * @param  string $failure
     * @param  string $skipped
     * @access public
     * @return object|array
     */
    public function processResultTest(object $result, object $matchNode, string $failure, string $skipped): object|array
    {
        // Use reflection to access private method
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('processResult');
        $method->setAccessible(true);

        $processedResult = $method->invokeArgs($this->instance, [$result, $matchNode, $failure, $skipped]);
        if(dao::isError()) return dao::getError();

        return $processedResult;
    }
}
