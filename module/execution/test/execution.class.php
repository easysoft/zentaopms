<?php
class executionTest
{

    /**
     * __construct loadModel execution
     *
     * @access public
     */
    public function __construct()
    {
        global $tester, $app;

        $app->rawModule  = 'execution';
        $app->moduleName = 'execution';

        $this->executionModel = $tester->loadModel('execution');
        $this->treeModel      = $tester->loadModel('tree');
        $this->productModel   = $tester->loadModel('product');
    }

    /**
     * Compute cfd of a execution.
     *
     * @param  int|string|array $executionID
     * @access public
     * @return array
     */
    public function computeCFDTest($executionID = 0)
    {
        $this->executionModel->computeCFD($executionID);

        $today = helper::today();
        return $this->executionModel->dao->select('*')->from(TABLE_CFD)
            ->where('date')->eq($today)
            ->beginIF(!empty($executionID))->andWhere('execution')->in($executionID)->fi()
            ->fetchAll('id');
    }

    /**
     * 检查执行开始、结束日期是否正确。
     * Check begin and end date.
     *
     * @param  int    $projectID
     * @param  string $begin
     * @param  string $end
     * @param  int    $parentID
     * @access public
     * @return bool|array
     */
    public function checkBeginAndEndDateTest(int $projectID, string $begin, string $end, int $parentID): bool|array
    {
        $this->executionModel->checkBeginAndEndDate($projectID, $begin, $end, $parentID);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * Check the workload format and total.
     *
     * @param  string $executionID
     * @param  string $type
     * @param  int    $percent
     * @access public
     * @return object
     */
    public function checkWorkloadTest($executionID, $type = '', $percent = 0)
    {
        global $tester, $config;
        $tester->loadModel('programplan');

        $_POST['products'][0] = 1;
        $_POST['branch'][0]   = array();
        $oldExecution = empty($executionID) ? '' : $this->executionModel->getByID($executionID);
        $this->executionModel->checkWorkload($type, $percent, $oldExecution);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * Set Kanban.
     *
     * @param  int    $executionID
     * @param  array  $param
     * @access public
     * @return array|object
     */
    public function setKanbanTest($executionID, $param = array()): array|object
    {
        $object = $this->executionModel->dao->select('displayCards,fluidBoard,colWidth,minColWidth,maxColWidth')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
        foreach($param as $key => $value) $object->$key = $value;

        $this->executionModel->setKanban($executionID, $object);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->executionModel->getByID($executionID);
        }
    }

    /**
     * 给执行所属的项目ID设置session。
     * Set project into session.
     *
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function setProjectSessionTest(int $executionID): int
    {
        unset($_SESSION['project']);
        $this->executionModel->setProjectSession($executionID);
        return empty($_SESSION['project']) ? 0 : $_SESSION['project'];
    }

    /**
     * Test save state.
     *
     * @param  int    $executionID
     * @param  array  $executions
     * @access public
     * @return array
     */
    public function saveStateTest($executionID = 0, $executions = array())
    {
        return $this->executionModel->saveState($executionID, $executions);
    }

    /**
     * Check the privilege.
     *
     * @param mixed $executionID
     * @access public
     * @return bool
     */
    public function checkPrivTest($executionID)
    {
        return $this->executionModel->checkPriv($executionID);
    }

    /**
     * get todate
     *
     * @access public
     * @return string
     */
    public function getHour()
    {
        return date('Y-m-d');
    }

    /**
     * get Reduce date
     *
     * @param  string $dayNum
     * @access public
     * @return string
     */
    public function getReduceHour($dayNum)
    {
        return date('Y-m-d',strtotime("-$dayNum day"));
    }

    /**
     * function create test by execution
     *
     * @param  array  $param
     * @param  string $project
     * @param  string $dayNum
     * @param  string $days
     * @access public
     * @return array
     */
    public function createTest($param = array(), $project = '', $teamMembers = array(), $days = '')
    {
        $products  = array('');
        $plans     = array('');
        $whitelist = array('');
        $beginData = date('Y-m-d');
        $endData   = date('Y-m-d',strtotime("+$days day"));
        $delta     = intval($days) + 1;

        $createFields = array('project' => (int)$project, 'name' => '', 'code' => '', 'begin' => $beginData, 'end' => $endData,
            'lifetime' => 'short', 'status' => 'wait', 'products' => $products, 'days' => $days, 'type' => 'sprint',
            'plans' => $plans, 'team' => '', 'PO' => '', 'QD' => '', 'PM' => '', 'RD' => '', 'whitelist' => '',
            'desc' => '', 'acl' => 'private', 'percent' => '0', 'openedBy' => 'admin', 'openedDate' => date('Y-m-d H:i:s'));

        $execution = new stdclass();
        foreach($createFields as $field => $defaultValue) $execution->$field = $defaultValue;
        foreach($param as $key => $value) $execution->$key = $value;

        $this->executionModel->config->execution->create->requiredFields = 'project,name,code,begin,end';
        $objectID = $this->executionModel->create($execution, $teamMembers);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->executionModel->getByID($objectID);
            return $object;
        }
    }

    /**
     * function update test by execution
     *
     * @param  string $objectID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateObject($objectID, $param = array())
    {
        global $tester;

        $products = array('1','81','91');
        $object   = $tester->dbh->query("SELECT `project`,`name`,`code`,`begin`,`end`,`days`,`lifetime`,`team`,`status`,`PO`,`QD`,`PM`,
            `RD`,`desc`,`acl` FROM zt_project WHERE id = $objectID ")->fetch();
        $object->products = $products;

        foreach($object as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $change = $this->executionModel->update($objectID, (object)$_POST);

        if($change == array()) $change = '没有数据更新';

        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
    }

    /**
     * function batchUpdate test by execution
     *
     * @param  array  $param
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function batchUpdateObject($param = array(), $executionID = '')
    {
        $executionIDList = array($executionID => $executionID);
        $codes           = array($executionID => '');
        $name            = array($executionID => '');
        $pms             = array($executionID => '');
        $pos             = array($executionID => '');
        $qds             = array($executionID => '');
        $rds             = array($executionID => '');
        $lifetimes       = array($executionID => '');
        $statuses        = array($executionID => '');
        $begins          = array($executionID => date('Y-m-d'));
        $ends            = array($executionID => date('Y-m-d',strtotime("+5 day")));
        $descs           = array($executionID => '');
        $teams           = array($executionID => '');
        $days            = array($executionID => '');

        $createFields = array('id' => $executionIDList, 'name' => $name, 'code' => $codes, 'PM' => $pms, 'lifetime' => $lifetimes,
            'begin' => $begins, 'end' => $ends, 'desc' => $descs, 'status' => $statuses, 'team' => $teams, 'days' => $days,'PO' => $pos,
            'QD' => $qds, 'RD' => $rds);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->executionModel->batchUpdate((object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $object[$executionID];
            return $object;
        }
    }

    /**
     * function batchChangeStatus test by execution
     *
     * @param  array   $executionIdList
     * @param  string  $status
     * @access public
     * @return array
     */
    public function batchChangeStatusObject($executionIdList = '', $status = '')
    {
        $result = $this->executionModel->batchChangeStatus($executionIdList, $status);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return empty($result) ? 'empty' : $result;
        }
    }

    /**
     * function changeStatus2Wait test by execution
     *
     * @param  int    $executionID
     * @access public
     * @return string|array
     */
    public function changeStatus2WaitObject($executionID)
    {
        global $tester;

        $tester->loadModel('programplan');
        $selfAndChildrenList = $tester->programplan->getSelfAndChildrenList($executionID);
        $siblingStages       = $tester->programplan->getSiblings($executionID);

        $selfAndChildren = $selfAndChildrenList[$executionID];
        $execution       = $selfAndChildren[$executionID];
        $executionType   = $execution->type;

        $siblingList = array();
        if($executionType == 'stage') $siblingList = $siblingStages[$executionID];

        $result = $this->executionModel->changeStatus2Wait($executionID, $selfAndChildren, $siblingList);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return (empty($result) or $result == "'',") ? 'empty' : $result;
        }
    }

    /**
     * function changeStatus2Doing test by execution
     *
     * @param  int    $executionID
     * @access public
     * @return bool|array
     */
    public function changeStatus2DoingObject($executionID)
    {
        global $tester;

        $tester->loadModel('programplan');
        $selfAndChildrenList = $tester->programplan->getSelfAndChildrenList($executionID);

        $selfAndChildren = $selfAndChildrenList[$executionID];
        $execution       = $selfAndChildren[$executionID];
        $executionType   = $execution->type;

        $this->executionModel->changeStatus2Doing($executionID, $selfAndChildren);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return true;
        }
    }

    /**
     * function changeStatus2Inactive test by execution
     *
     * @param  int         $executionID
     * @param  string      $status       suspended|closed
     * @access public
     * @return bool|array
     */
    public function changeStatus2InactiveObject($executionID, $status)
    {
        global $tester;

        $tester->loadModel('programplan');
        $selfAndChildrenList = $tester->programplan->getSelfAndChildrenList($executionID);
        $siblingStages       = $tester->programplan->getSiblings($executionID);

        $selfAndChildren = $selfAndChildrenList[$executionID];
        $execution       = $selfAndChildren[$executionID];
        $executionType   = $execution->type;

        $siblingList = array();
        if($executionType == 'stage') $siblingList = $siblingStages[$executionID];

        $result = $this->executionModel->changeStatus2Inactive($executionID, $status, $selfAndChildren, $siblingList);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return (empty($result) or $result == "'',") ? 'empty' : $result;
        }
    }

    /**
     * 测试开始一个执行。
     * Test start the execution.
     *
     * @param  int    $executionID
     * @param  array  $param
     * @param  bool   $testParent
     * @access public
     * @return array|object|string
     */
    public function startTest(int $executionID, array $param = array(), bool $testParent = false): array|object|string
    {
        $data = date('Y-m-d');

        $createFields = array('status' => 'doing', 'comment' => '开始描述测试', 'realBegan' => $data);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $obj = $this->executionModel->start($executionID, (object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            $errors = dao::getError();
            if(!empty($errors['realBegan'])) return $errors['realBegan'][0];
            return $errors;
        }

        if($testParent)
        {
            $execution       = $this->executionModel->getByID($executionID);
            $executionParent = $this->executionModel->getByID($execution->parent);
            return $executionParent;
        }

        foreach($obj as $change) $changes[$change['field']] = $change;
        return $changes;

    }

    /**
     * function putoff test by execution.
     *
     * @param  int    $executionID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function putoffTest(int $executionID, array $param = array()): object|string|array
    {
        $begin = date('Y-m-d');
        $end   = date('Y-m-d', strtotime("+5 day"));

        $createFields = array('status' => 'wait', 'days' => '5', 'comment' => '延期描述测试', 'begin' => $begin, 'end' => $end);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $obj = $this->executionModel->putoff($executionID, (object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $obj;
        }
    }

    /**
     * function suspend test by execution
     *
     * @param  string $executionID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function suspendTest($executionID, $param = array())
    {
        $createFields = array('status' => 'suspended', 'comment' => '挂起描述测试');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $obj = $this->executionModel->suspend($executionID, (object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $obj;
        }
    }

    /**
     * function activate test by execution
     *
     * @param  string $executionID
     * @param  array  $param
     * @param  bool   $testParent
     * @access public
     * @return array
     */
    public function activateTest($executionID, $param = array(), $testParent = false)
    {
        self::suspendTest($executionID);

        $begin = date('Y-m-d');
        $end   = date('Y-m-d',strtotime("+5 day"));

        $createFields = array('status' => 'doing', 'comment' => '激活描述测试', 'begin' => $begin, 'end' => $end);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $obj = $this->executionModel->activate($executionID, (object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            if($testParent)
            {
                $execution       = $this->executionModel->getByID($executionID);
                $executionParent = $this->executionModel->getByID($execution->parent);
                return $executionParent;
            }
            return $obj;
        }
    }

    /**
     * 测试关闭执行。
     * Test close execution.
     *
     * @param  string $executionID
     * @param  array  $param
     * @param  bool   $testParent   是否观察父级对象的测试结果。
     * @access public
     * @return array
     */
    public function closeTest($executionID, $param = array(), $testParent = false)
    {
        $today = date('Y-m-d');

        $createFields = array('status' => 'closed', 'comment' => '关闭描述测试', 'realEnd' => $today);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $_POST['uid'] = 'test';

        $obj = $this->executionModel->close($executionID, (object)$_POST);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            if($testParent)
            {
                global $tester;
                $tester->loadModel('programplan')->computeProgress($executionID, 'close');
                $execution       = $this->executionModel->getByID($executionID);
                $executionParent = $this->executionModel->getByID($execution->parent);
                return $executionParent;
            }
            return $obj;
        }
    }

    /**
     * 根据给定条件构建执行键值对。
     * Build execution id:name pairs through the conditions.
     *
     * @param  int    $projectID
     * @param  int    $count
     * @param  string $mode      all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|noprefix|withobject
     * @access public
     * @return array|int
     */
    public function getPairsTest(int $projectID, int $count, string $mode = ''): array|int
    {
        $object = $this->executionModel->getPairs($projectID, 'all', $mode);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getByID test by execution
     *
     * @param  string $executionID
     * @access public
     * @return string|object|false
     */
    public function getByIDTest(int $executionID): string|object|false
    {
        $object = $this->executionModel->getByID($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取执行列表信息。
     * Get execution list information.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $status
     * @param  int    $limit
     * @param  int    $productID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getListTest(int $projectID, string $type, string $status, int $limit, int $productID, int $count): array|int
    {
        $object = $this->executionModel->getList($projectID, $type, $status, $limit, $productID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取我参与的执行列表信息。
     * Get involved execution list information.
     *
     * @param  int    $projectID
     * @param  int    $limit
     * @param  int    $productID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getInvolvedExecutionListTest(int $projectID, int $limit, int $productID, int $count): array|int
    {
        $object = $this->executionModel->getInvolvedExecutionList($projectID, $status = 'involved', $limit, $productID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 根据项目ID获取项目下的执行信息。
     * Get executions data by project.
     *
     * @param  int       $projectID
     * @param  string    $status
     * @param  int       $limit
     * @param  bool      $pairs
     * @param  bool      $devel
     * @param  int       $appendedID
     * @param  int       $count
     * @access public
     * @return array|int
     */
    public function getByProjectTest(int $projectID, string $status, int $limit, bool $pairs = false, bool $devel = false, int $appendedID = 0, int $count = 0): array|int
    {
        $object = $this->executionModel->getByProject($projectID, $status, $limit, $pairs, $devel, $appendedID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取给定项目下所有执行的Id列表。
     * Get execution id list by project.
     *
     * @param  int    $projectID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getIdListTest(int $projectID, int $count): array|int
    {
        $object = $this->executionModel->getIdList($projectID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Get execution stat data.
     *
     * @param int    $projectID
     * @param string $browseType
     * @param int    $productID
     * @param int    $branch
     * @param bool   $withTasks
     * @param string $param
     * @param string $orderBy
     * @param object $pager
     * @access public
     * @return int
     */
    public function getStatDataTest($projectID = 0, $browseType = 'undone', $productID = 0, $branch = 0, $withTasks = false, $param = '', $orderBy = 'id_asc', $pager = null)
    {
        $objects = $this->executionModel->getStatData($projectID);
        return count($objects);
    }

    /**
     * function getBranches test execution
     *
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getBranchesTest($executionID, $count)
    {
        $object = $this->executionModel->getBranches($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getTree test execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getTreeTest($executionID)
    {
        global $app;
        $app->moduleName = 'execution';
        $app->methodName = 'tree';
        $object = $this->executionModel->getTree($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return !empty($object[0]) ? count($object[0]->children) : 0;
        }
    }

    /**
     * function getRelatedExecutions test execution
     *
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getRelatedExecutionsTest($executionID, $count)
    {
        $object = $this->executionModel->getRelatedExecutions($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取子阶段列表。
     * Get child executions.
     *
     * @param  int    $executionID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getChildExecutionsTest($executionID, $count): array|int
    {
        $object = $this->executionModel->getChildExecutions($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Check the privilege.
     *
     * @access public
     * @return string|bool
     */
    public function getLimitedExecutionTest()
    {
        return $this->executionModel->getLimitedExecution();
    }

    /**
     * function getTasks test by execution
     *
     * @param  string $productID
     * @param  string $executionID
     * @param  string $browseType
     * @param  string $queryID
     * @param  string $moduleID
     * @param  string $sort
     * @param  string $count
     * @access public
     * @return array
     */
    public function getTasksTest($productID, $executionID, $browseType, $queryID, $moduleID, $sort, $count)
    {
        global $tester, $app;
        $app->moduleName = 'execution';
        $app->methodName = 'task';

        $execution  = $tester->dbh->query("select * from zt_project where id = $executionID")->fetch();
        $executions = array($executionID => $execution->name);

        $app->loadClass('pager');
        $pager = new pager(0, 100, 1);
        $object = $this->executionModel->getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $pager);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Get the task data group by execution id list.
     *
     * @param  array  $executionIdList
     * @access public
     * @return int
     */
    public function getTaskGroupByExecutionTest($executionIdList = array())
    {
        $objects = $this->executionModel->getTaskGroupByExecution($executionIdList);
        return count($objects);
    }

    /**
     * 根据产品ID列表查询分支信息。
     * Get branch information by the product ID list.
     *
     * @param  int    $productID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getBranchByProductTest(int $productID, int $count): array|int
    {
        $object = $this->executionModel->getBranchByProduct(array($productID));

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return isset($object[$productID]) ? count($object[$productID]) : 0;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取排序后的执行列表信息。
     * Get ordered executions.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getOrderedExecutionsTest(int $executionID, string $status, int $count): array|int
    {
        $object = $this->executionModel->getOrderedExecutions($executionID, $status);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }

        return $count == 1 ? count($object) : $object;
    }

    /**
     * 获取要导入的执行列表。
     * Get executions to import.
     *
     * @param  array  $executionIdList
     * @param  string $type
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function getToImportTest(array $executionIdList, string $type, int $count): array|int
    {
        $object = $this->executionModel->getToImport($executionIdList, $type);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 更新执行关联的产品信息。
     * Update products of a execution.
     *
     * @param  int   $executionID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateProductsTest(int $executionID, array $param = array()): array
    {
        $postData = new stdclass();
        $postData->products = array();
        $postData->branch   = array();

        foreach($param as $key => $value) $postData->$key = $value;

        $this->executionModel->updateProducts($executionID, $postData);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchAll();
        }
    }

    /**
     * function getTasks2Imported test by execution
     *
     * @param  string $toExecution
     * @param  string $count
     * @access public
     * @return array
     */
    public function getTasks2ImportedTest($toExecution, $count)
    {
        $branches = $this->executionModel->getBranches($toExecution);
        $object   = $this->executionModel->getTasks2Imported($toExecution, $branches);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object[$toExecution]);
        }
        else
        {
            return $object[$toExecution];
        }
    }

    /**
     * 转入任务到指定的执行。
     * Import tasks.
     *
     * @param  int       $executionID
     * @param  int       $count
     * @param  array     $taskIdList
     * @access public
     * @return array|int
     */
    public function importTaskTest(int $executionID, int $count, array $taskIdList = array()): array|int
    {
        $this->executionModel->importTask($executionID, $taskIdList);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            $taskList = $this->executionModel->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($executionID)->fetchAll();
            return count($taskList);
        }
        else
        {
            $taskList = $this->executionModel->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($executionID)->fetchAll();
            return $taskList;
        }
    }


    /**
     * 转入任务到指定的执行。
     * Import tasks.
     *
     * @param  int       $executionID
     * @param  int       $count
     * @param  array     $taskIdList
     * @access public
     * @return array|int
     */
    public function afterImportTaskTest(int $executionID, int $count, array $taskIdList = array()): array|int
    {
        $dateExceed   = array();
        $taskStories  = array();
        $parents      = array();
        $execution    = $this->executionModel->fetchByID($executionID);
        $tasks        = $this->executionModel->loadModel('task')->getByIdList($taskIdList);
        $assignedToes = array();
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to execution. */
            $assignedToes[$task->assignedTo] = $task->execution;
            $taskStories[$task->story]       = $task->story;
            if($task->parent < 0) $parents[$task->id] = $task->id;
        }

        $this->executionModel->afterImportTask($execution, $parents, $assignedToes, $taskStories);

        $teams = $this->executionModel->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('execution')->andWhere('root')->eq($executionID)->fetchAll();
        return $count ? count($teams) : $teams;
    }

    /**
     * 统计执行的需求数、任务数、Bug数。
     * Statistics the number of stories, tasks, and bugs for the execution.
     *
     * @param  int          $executionID
     * @access public
     * @return object|array
     */
    public function statRelatedDataTest(int $executionID): object|array
    {
        $object = $this->executionModel->statRelatedData($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 导入Bug。
     * Import task from Bug.
     *
     * @param  int        $executionID
     * @param  array      $postData
     * @access public
     * @return array|bool
     */
    public function importBugTest(int $executionID, array $postData): array|bool
    {
        $this->executionModel->loadModel('task');

        $tasks          = array();
        $execution      = $this->executionModel->getByID($executionID);
        $bugs           = $this->executionModel->loadModel('bug')->getByIdList(array_keys($postData));
        $showAllModule  = isset($this->executionModel->config->execution->task->allModule) ? $this->executionModel->config->execution->task->allModule : '';
        $modules        = $this->executionModel->loadModel('tree')->getTaskOptionMenu($execution->id, 0, $showAllModule ? 'allModule' : '');
        $now            = helper::now();
        foreach($postData as $bugID => $task)
        {
            $bug = zget($bugs, $bugID, '');
            if(empty($bug)) continue;

            unset($task->id);
            $task->bug          = $bug;
            $task->project      = $execution->project;
            $task->execution    = $execution->id;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->storyVersion;
            $task->module       = isset($modules[$bug->module]) ? $bug->module : 0;
            $task->fromBug      = $bugID;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->consumed     = 0;
            $task->status       = 'wait';
            $task->openedDate   = $now;
            $task->openedBy     = $this->executionModel->app->user->account;

            $tasks[$bugID] = $task;
        }

        return $this->executionModel->importBug($tasks);
    }

    /**
     * 修改执行的所属项目。
     * Change execution project.
     *
     * @param  int    $newProjectID
     * @param  int    $oldProjectID
     * @param  int    $executionID
     * @param  string $syncStories yes|no
     * @access public
     * @return array|object
     */
    public function changeProjectTest(int $newProjectID, int $oldProjectID, int $executionID, string $syncStories = 'no'): array|object
    {
        $this->executionModel->changeProject($newProjectID, $oldProjectID, $executionID, $syncStories);

        if(dao::isError()) return dao::getError();
        return $this->executionModel->dao->select('parent,path')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
    }

    /**
     * function linkStory test by execution
     *
     * @param  int    $executionID
     * @param  int    $count
     * @param  array  $stories
     * @access public
     * @return array|int
     */
    public function linkStoryTest(int $executionID, int $count, array $stories = array()): array|int
    {

        $this->executionModel->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->exec();
        $this->executionModel->linkStory($executionID, $stories);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            $object = $this->executionModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
            return count($object);
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
        }
    }

    /**
     * 批量关联需求。
     * Link all stories by execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $planID
     * @access public
     * @return int
     */
    public function linkStoriesTest(int $executionID, int $productID = 0, int $planID = 0): int
    {
        if($planID) $this->executionModel->dao->update(TABLE_PROJECTPRODUCT)->set('plan')->eq($planID)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->exec();

        $this->executionModel->linkStories($executionID);
        $objects = $this->executionModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->fetchAll();
        return count($objects);
    }

    /**
     * 执行批量关联用例。
     * Batch link cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function linkCasesTest(int $executionID, int $productID, int $storyID, int $count): array|int
    {
        $this->executionModel->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->exec();
        $this->executionModel->linkCases($executionID, $productID, $storyID);

        if(dao::isError()) return dao::getError();

        $objects = $this->executionModel->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->fetchAll();
        return $count ? count($objects) : $objects;
    }

    /**
     * 移除需求。
     * Unlink a story.
     *
     * @param  int       $executionID
     * @param  int       $storyID
     * @param  array     $stories
     * @param  int       $count       1: get count of objects, other: get object.
     * @access public
     * @return array|int
     */
    public function unlinkStoryTest(int $executionID, int $storyID, array $stories, int $count): array|int
    {
        $this->executionModel->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->exec();

        $this->executionModel->linkStory($executionID, $stories);
        $this->executionModel->unlinkStory($executionID, $storyID);

        if(dao::isError()) return dao::getError();

        if($count == 1)
        {
            $object = $this->executionModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
            return count($object);
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
        }
    }

    /**
     * 解除用例跟执行的关联关系。
     * Unlink cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function unlinkCasesTest(int $executionID, int $productID, int $storyID): array
    {
        $this->executionModel->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->exec();

        $this->executionModel->linkCases($executionID, $productID, $storyID);
        $this->executionModel->unlinkCases($executionID, $storyID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->fetchAll();
        }
    }

    /**
     * 获取执行团队成员列表。
     * Get team members.
     *
     * @param  int       $executionID
     * @param  int       $count
     * @access public
     * @return int|array
     */
    public function getTeamMembersTest(int $executionID, int $count): int|array
    {
        $object = $this->executionModel->getTeamMembers($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getMembersByIdList test by execution
     *
     * @param  array  $executionIdList
     * @param  string $count
     * @access public
     * @return array
     */
    public function getMembersByIdListTest($executionIdList, $count)
    {
        $object = $this->executionModel->getMembersByIdList($executionIdList);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getMembers2Import test by execution
     *
     * @param  int    $executionID
     * @param  array  $currentMembers
     * @param  int    $count
     * @access public
     * @return int|string|array
     */
    public function getMembers2ImportTest(int $executionID, array $currentMembers, int $count): int|string|array
    {
        $object = $this->executionModel->getMembers2Import($executionID, $currentMembers);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            if(empty($object))
            {
                return '无数据';
            }
            else
            {
                return $object;
            }
        }
    }

    /**
     * 获取可以复制团队的项目、执行列表。
     * Get projects and executions that copy the team.
     *
     * @param  int               $projectID
     * @param  int               $count
     * @access public
     * @return array|int|string
     */
    public function getCanCopyObjectsTest(int $projectID = 0, int $count = 0): array|int|string
    {
        $object = $this->executionModel->getCanCopyObjects($projectID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            if(empty($object))
            {
                return '无数据';
            }
            else
            {
                return $object;
            }
        }
    }

    /**
     * 维护执行的团队成员。
     * Manage members of the execution.
     *
     * @param  int    $executionID
     * @param  int    $count
     * @param  array  $params
     * @access public
     * @return array|string|int
     */
    public function manageMembersTest(int $executionID, int $count, array $params = array()): array|string|int
    {
        $this->executionModel->dao->delete()->from(TABLE_TEAM)->where('root')->eq($executionID)->exec();

        $members = array();
        foreach($params as $key => $valueList)
        {
            $members[$key] = new stdclass();
            foreach($valueList as $field => $value)
            {
                $members[$key]->$field = $value;
            }
        }

        $execution = $this->executionModel->getByID($executionID);
        $this->executionModel->manageMembers($execution, $members);

        $objects = $this->executionModel->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($executionID)->orderBy('id_asc')->fetchAll();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($objects);
        }
        else
        {
            if(empty($objects))
            {
                return '无数据';
            }
            else
            {
                return $objects;
            }
        }
    }

    /**
     * function addProjectMembers test by execution
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function addProjectMembersTest(int $projectID = 0, int $executionID = 0, int $count = 0): array|int
    {
        $this->executionModel->dao->delete()->from(TABLE_TEAM)->where('root')->eq($projectID)->exec();
        $executionMembers = $this->executionModel->dao->select('`root`,`account`,`join`,`role`,`days`,`type`,`hours`')->from(TABLE_TEAM)->where('root')->eq($executionID)->fetchAll('account');

        $this->executionModel->addProjectMembers($projectID, $executionMembers);

        $object = $this->executionModel->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($projectID)->fetchAll();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 移除执行团队成员。
     * Remove the user from the execution team members.
     *
     * @param  int             $executionID
     * @param  string          $account
     * @param  int             $count
     * @access public
     * @return array|object|int
     */
    public function unlinkMemberTest(int $executionID, string $account, int $count): array|object|int
    {
        global $tester;
        $oldObject = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($executionID)->fetchAll();

        $this->executionModel->unlinkMember($executionID, $account);

        $object = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($executionID)->fetchAll();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($oldObject);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function computeBurn test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function computeBurnTest($count)
    {
        $object = $this->executionModel->computeBurn();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Function fixFirst test by execution.
     *
     * @param  string $executionID
     * @param  array  $param
     * @param  string $date
     * @access public
     * @return array
     */
    public function fixFirstTest($executionID, $param = array(), $date = '')
    {
        $burnData = new stdclass;

        $createFields = array('estimate' => '');

        foreach($createFields as $field => $defaultValue) $burnData->$field = $defaultValue;
        foreach($param as $key => $value)
        {
            if($key == 'withLeft' && $value)
            {
                $burnData->left = $burnData->estimate;
                continue;
            }
            $burnData->$key = $value;
        }

        $this->executionModel->computeBurn();
        $this->executionModel->fixFirst($burnData);

        unset($_POST);

        $object = $this->executionModel->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('date')->eq($date)->fetchAll();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            if(empty($object))
            {
                return '无数据';
            }
            else
            {
                return $object;
            }
        }
    }

    /**
     * 获取燃尽图时间点数据。
     * Get burn chart flot data.
     *
     * @param  int    $executionID
     * @param  string $burnBy
     * @param  bool   $showDelay
     * @access public
     * @return array|null
     */
    public function getBurnDataFlotTest(int $executionID, string $burnBy, bool $showDelay = false, string $begin = '', string $end = ''):array|null
    {
        $dateList = array();
        if($begin && $end) list($dateList) = $this->executionModel->getDateList($begin, $end, 'noweekend', 0, 'Y-m-d');

        $object = $this->executionModel->getBurnDataFlot($executionID, $burnBy, $showDelay, $dateList);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取执行的燃尽图数据。
     *  Get execution burn data.
     *
     * @param  int          $executionID
     * @access public
     * @return string|array
     */
    public function getBurnDataTest(int $executionID = 0): string|array
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return '0';

        $object = $this->executionModel->getBurnData(array($executionID => $execution));

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 根据传入的条件筛选日期列表。
     * Process burndown datas when the sets is smaller than the itemCounts.
     *
     * @param  int       $executionID
     * @param  int       $itemCounts
     * @param  string    $begin
     * @param  string    $end
     * @param  int       $count
     * @access public
     * @return array|int
     */
    public function processBurnDataTest(int $executionID, int $itemCounts, string $begin, string $end, int $count): array|int
    {
        $dateList = $this->executionModel->dao->select('execution, `date` as name, `left` as value')->from(TABLE_BURN)->where('execution')->eq($executionID)->fetchAll('name');
        $object   = $this->executionModel->processBurnData($dateList, $itemCounts, $begin, $end);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取任务列表的统计信息。
     * Get the summary of execution.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function summaryTest(int $executionID): string
    {
        $executionName = $this->executionModel->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('name');
        $executions    = array($executionID => $executionName);
        $tasks         = $this->executionModel->getTasks('0', $executionID, $executions,'all', '0', '0', 'status,id_desc');

        $object = $this->executionModel->summary($tasks);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $object;
        }
    }

    /**
     * 判断操作按钮是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $execution
     * @param  string $action
     * @access public
     * @return string
     */
    public function isClickableTest(object $execution, string $action): string
    {
        $object = $this->executionModel->isClickable($execution, $action);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            if($object == true)
            {
                return "检查通过";
            }
            else
            {
                return "检查不通过";
            }
        }
    }

    /**
     * 获取日期列表数据。
     * Get date list data.
     *
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $type
     * @param  int        $count
     * @param  int|string $interval
     * @access public
     * @return array|string|int
     */
    public function getDateListTest(string $begin, string $end, string $type, int $count, int|string $interval = 0): array|string|int
    {
        $object = $this->executionModel->getDateList($begin, $end, $type, $interval);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        elseif($count == "1")
        {
            return count($object[0]);
        }
        else
        {
            if(empty($object[0]))
            {
                return '无数据';
            }
            else
            {
                return $object;
            }
        }
    }

    /**
     * 修复执行的排序顺序。
     * Fix the sort order of execution.
     *
     * @access public
     * @return array
     */
    public function fixOrderTest(): array
    {
        global $tester;

        $this->executionModel->fixOrder();

        $object = $tester->dao->select('id,`order`')->from(TABLE_EXECUTION)->fetchAll('id');

        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * 获取看板的任务卡片数据。
     * Get the Kanban task card data.
     *
     * @param  int       $executionID
     * @param  int       $count
     * @param  array     $excludeTasks
     * @access public
     * @return array|int
     */
    public function getKanbanTasksTest(int $executionID, int $count, array $excludeTasks = array()): array|int
    {
        $object = $this->executionModel->getKanbanTasks($executionID, 'id_desc', $excludeTasks);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getKanbanSetting test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanSettingTest($count)
    {
        $object = $this->executionModel->getKanbanSetting();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object->colorList);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getKanbanColumns test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanColumnsTest($count)
    {
        $kanbanSetting = $this->executionModel->getKanbanSetting();
        $kanbanSetting->allCols = false;

        $object = $this->executionModel->getKanbanColumns($kanbanSetting);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getKanbanStatusMap test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanStatusMapTest($count)
    {
        $kanbanSetting = $this->executionModel->getKanbanSetting();
        $object        = $this->executionModel->getKanbanStatusMap($kanbanSetting);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getKanbanStatusList test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanStatusListTest($count)
    {
        $kanbanSetting = $this->executionModel->getKanbanSetting();
        $object        = $this->executionModel->getKanbanStatusList($kanbanSetting);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getKanbanColorList test by execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanColorListTest($count)
    {
        $kanbanSetting = $this->executionModel->getKanbanSetting();
        $object        = $this->executionModel->getKanbanColorList($kanbanSetting);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 构建燃尽图数据。
     * Build burn data.
     *
     * @param  int    $executionID
     * @param  int    $count
     * @param  string $type         noweekend|withweekend
     * @param  string $burnBy       left|estimate|storyPoint
     * @access public
     * @return array|int
     */
    public function buildBurnDataTest(int $executionID, int $count, string $type = 'noweekend', string $burnBy = 'left'): array|int
    {
        $begin = '2022-01-07';
        $end   = '2022-01-17';

        $dateList = $this->executionModel->getDateList($begin, $end, $type, 0, 'Y-m-d');
        $object   = $this->executionModel->buildBurnData($executionID, $dateList[0], $burnBy);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * 通过产品的ID列表获取计划数据。
     * Get plan data from the ID list of the product.
     *
     * @param  array     $productIdList
     * @param  string    $param         withMainPlan|skipParent
     * @param  int       $executionID
     * @param  int       $count
     * @access public
     * @return array|int
     */
    public function getPlansTest(array $productIdList, string $param, int $executionID, int $count): array|int
    {
        $object = $this->executionModel->getPlans($productIdList, $param, $executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getStageLinkProductPairs test by execution
     *
     * @param  array $projects
     * @param  string $count
     * @access public
     * @return array
     */
    public function getStageLinkProductPairsTest($projects, $count)
    {
        $object = $this->executionModel->getStageLinkProductPairs($projects);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function setTreePath test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function setTreePathTest($executionID)
    {
        global $tester;

        $this->executionModel->setTreePath($executionID);

        $object = $tester->dao->select('id,project,parent,path')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetchAll('id');

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 获取累计流图的开始、结束日期。
     * Test get begin and end for CFD.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getBeginEnd4CFDTest(int $executionID): array
    {
        global $tester;

        $execution = $this->executionModel->getByID($executionID);
        $object    = $this->executionModel->getBeginEnd4CFD($execution);

        $object[0] = $object[0] == date('Y-m-d', strtotime('-13 days', time())) ? 1 : 0;
        $object[1] = $object[1] == helper::today() ? 1 : 0;

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * 通过搜索条件获取任务列表信息。
     * Get taskes by search.
     *
     * @param  string $condition
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @access public
     * @return array
     */
    public function getSearchTasksTest(string $condition, string $orderBy, int $recPerPage): array
    {
        /* Load pager. */
        global $tester, $app;
        $app->moduleName = 'execution';
        $app->methodName = 'searchtasks';
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager(0, $recPerPage, 1);

        $objects = $this->executionModel->getSearchTasks($condition, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test Get kanban group data.
     *
     * @param array $stories
     * @param array $tasks
     * @param array $bugs
     * @param string $type
     * @access public
     * @return void
     */
    public function getKanbanGroupDataTest($stories, $tasks, $bugs, $type = 'story')
    {
        $object = $this->executionModel->getKanbanGroupData($stories, $tasks, $bugs, $type);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            if(count((array)$object['closed']) == 0 and count((array)$object['nokey']) == 0) return 'empty';
            return count($object['nokey']->tasks);
        }
    }

    /**
     * 获取上一个看板的数据。
     * Get the data from the previous Kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return array|string|null
     */
    public function getPrevKanbanTest(int $executionID): array|string|null
    {
        $result = $this->executionModel->getPrevKanban($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return !$result ? 'empty' : $result;
        }
    }

    /**
     * 保存看板数据。
     * Save Kanban Data.
     *
     * @param  int    $executionID
     * @param  bool   $isEmptyData
     * @access public
     * @return array|string|null
     */
    public function saveKanbanDataTest(int $executionID, $isEmptyData = false): array|string|null
    {
        $kanbanDataList = array();
        if(!$isEmptyData)
        {
            global $tester;
            $contents    = array('story', 'wait', 'doing', 'done', 'cancel');
            $stories     = $tester->loadModel('story')->getExecutionStories($executionID, 0, 'id_asc');
            $kanbanTasks = $this->executionModel->getKanbanTasks($executionID, "id");
            $kanbanBugs  = $tester->loadModel('bug')->getExecutionBugs($executionID);
            $users       = array();
            $taskAndBugs = array();
            foreach($kanbanTasks as $task)
            {
                $storyID = $task->storyID;
                $status  = $task->status;
                $users[] = $task->assignedTo;

                $taskAndBugs[$status]["task{$task->id}"] = $task;
            }
            foreach($kanbanBugs as $bug)
            {
                $storyID = $bug->story;
                $status  = $bug->status;
                $status  = $status == 'active' ? 'wait' : ($status == 'resolved' ? ($bug->resolution == 'postponed' ? 'cancel' : 'done') : $status);
                $users[] = $bug->assignedTo;

                $taskAndBugs[$status]["bug{$bug->id}"] = $bug;
            }

            $datas = array();
            foreach($contents as $content)
            {
                if($content != 'story' and !isset($taskAndBugs[$content])) continue;
                $datas[$content] = $content == 'story' ? $stories : $taskAndBugs[$content];
            }
            $kanbanDataList = $datas;
        }

        $object = $this->executionModel->saveKanbanData($executionID, $kanbanDataList);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            $result = $this->executionModel->getPrevKanban($executionID);
            return !$result ? 'empty' : $result;
        }
    }

    /**
     * 更新用户可查看的执行和产品。
     * Update the execution and product that users can view.
     *
     * @param  int    $executionID
     * @param  string $objectType
     * @param  array  $users
     * @access public
     * @return string|array
     */
    public function updateUserViewTest(int $executionID, string $objectType = 'sprint', array $users = array()): string|array
    {
        $this->executionModel->updateUserView($executionID, $objectType, $users);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            if(count($users) > 0) su($users[0]);

            global $tester;
            return ",{$tester->app->user->view->sprints},";
        }
    }

    /**
     * Create default sprint.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function createDefaultSprintTest($projectID)
    {
        $result = $this->executionModel->createDefaultSprint($projectID);

        if(dao::isError()) return dao::getError();

        return $result > 0;
    }

    /**
     * 设置看板执行的菜单。
     * Set kanban menu.
     *
     * @param  int           $executionID
     * @access public
     * @return object|string
     */
    public function setMenuTest($executionID = 0): object|string
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return '0';

        $this->executionModel->setMenu($executionID);

        global $lang;
        return $lang->execution->menu;
    }

    /**
     * Test sync no multiple sprint.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function syncNoMultipleSprintTest(int $projectID): int
    {
        return $this->executionModel->syncNoMultipleSprint($projectID);
    }

    /**
     * Test build search form.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildSearchFormTest($queryID)
    {
        $this->executionModel->buildSearchForm($queryID, 'searchUrl');

        return $_SESSION['executionsearchParams']['queryID'];
    }

    /**
     * Test print nested list.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function buildTreeTest($executionID)
    {
        global $app;
        $app->moduleName = 'task';
        $tree = $this->executionModel->getTree($executionID);
        return $this->executionModel->buildTree($tree);
    }

    /**
     * Test format tasks for tree.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function formatTasksForTreeTest($executionID)
    {
        global $app;
        $app->moduleName = 'task';
        $tasks = $this->executionModel->getSearchTasks("execution = $executionID", 'id_desc');
        return $this->executionModel->formatTasksForTree($tasks);
    }

    /**
     * Test fill tasks in tree.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function fillTasksInTreeTest($executionID)
    {
        $fullTrees = $this->treeModel->getTaskStructure($executionID, 0);
        if(empty($fullTrees)) return '0';

        return $this->executionModel->fillTasksInTree((object)$fullTrees[0], $executionID);
    }

    /**
     * Test build task search form.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function buildTaskSearchFormTest($executionID, $queryID)
    {
        $this->executionModel->buildTaskSearchForm($executionID, array($executionID => 'yes'), $queryID, 'searchTask');

        return $_SESSION['tasksearchParams']['queryID'];
    }

    /**
     * 构造Bug的搜索表单。
     * Build bug search form.
     *
     * @param  int    $productID
     * @param  int    $queryID
     * @param  string $type
     * @access public
     * @return int
     */
    public function buildBugSearchFormTest(int $productID, int $queryID, string $type = 'execution'): int
    {
        $product = $this->productModel->getByID($productID);
        if(empty($product)) return 0;

        $this->executionModel->loadModel('bug');
        $this->executionModel->buildBugSearchForm(array($productID => $product), $queryID, 'searchBug', $type);

        return $_SESSION['executionBugsearchParams']['queryID'];
    }

    /**
     * 构造需求的搜索表单。
     * Build story search form.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function buildStorySearchFormTest(int $executionID, int $queryID): int
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return 0;

        $this->executionModel->loadModel('story');
        $products     = $this->executionModel->loadModel('product')->getProducts($executionID);
        $branchGroups = $this->executionModel->loadModel('branch')->getByProducts(array_keys($products));
        $this->executionModel->buildStorySearchForm($products, $branchGroups, array(), $queryID, 'searchStory', 'executionStory', $execution);

        return $_SESSION['executionStorysearchParams']['queryID'];
    }

    /**
     * 获取累计流图的数据。
     * Get CFD data to display.
     *
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getCFDDataTest(int $executionID = 0, string $type = 'story'): array
    {
        $begin = strtotime('2022-01-12');
        $end   = strtotime('2022-02-12');

        $dateList = array();
        for($date = $begin; $date <= $end; $date += 24 * 3600) $dateList[] = date('Y-m-d', $date);

        return $this->executionModel->getCFDData($executionID, $dateList, $type);
    }

    /**
     * 构造累计流图数据。
     * Test build CFD data.
     *
     * @param  int    $executionID
     * @param  string $type        task|story|bug
     * @access public
     * @return array
     */
    public function buildCFDDataTest($executionID = 0, string $type = 'task'): array
    {
        $begin = strtotime('2022-01-12');
        $end   = strtotime('2022-02-12');

        $dateList = array();
        for($date = $begin; $date <= $end; $date += 24 * 3600) $dateList[] = date('Y-m-d', $date);

        return $this->executionModel->buildCFDData($executionID, $dateList, $type);
    }

    /**
     * 查看指定的执行日期是否有数据，且没有更新最新日期的数据。
     * Check whether there is data on the specified date of execution, and there is no data with the latest date added.
     *
     * @param  int    $executionID
     * @param  string $date
     * @access public
     * @return array
     */
    public function updateCFDDataTest(int $executionID, string $date): array
    {
        $this->executionModel->updateCFDData($executionID, $date);

        return $this->executionModel->dao->select("date, `count` AS value, `name`")->from(TABLE_CFD)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('date')->eq($date)
            ->orderBy('date DESC, id asc')->fetchGroup('name', 'date');
    }

    /**
     * Test build execution object by status.
     *
     * @param  string $status
     * @access public
     * @return object
     */
    public function buildExecutionByStatusTest($status)
    {
        return $this->executionModel->buildExecutionByStatus($status);
    }

    /**
     * 给执行列表重新排序。
     * Reset execution sorts.
     *
     * @param  int    $projectID
     * @param  string $type noParent
     * @access public
     * @return string
     */
    public function resetExecutionSortsTest(int $projectID, string $type = ''): string
    {
        $executions           = array();
        $executionIDList      = '';
        $firstGradeExecutions = array();
        if($projectID)
        {
            $executions = $this->executionModel->dao->select('*')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('project')->eq($projectID)
                ->andWhere('type')->in('sprint,stage,kanban')
                ->orderBy('order_asc')
                ->fetchAll('id');

            if($type == 'hasParent')
            {
                foreach($executions as $execution)
                {
                    if($execution->grade == 1) $firstGradeExecutions[$execution->id] = $execution->id;
                }
            }
        }

        $executions = $this->executionModel->resetExecutionSorts($executions, $firstGradeExecutions);
        if(!empty($executions))
        {
            $executionIDList = array_keys($executions);
            $executionIDList = implode(',', $executionIDList);
        }
        return $executionIDList;
    }

    /**
     * Get user custom workflow field.
     *
     * @param  string  $moduleName
     * @access public
     * @return array
     */
    public function getExtendFieldsTest($moduleName = 'project')
    {
        $extendFields = $this->executionModel->getExtendFields($moduleName);
        return $extendFields;
    }

    /**
     * 获取搜索执行的查询语句。
     * Get execution query SQL.
     *
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function getExecutionQueryTest(int $queryID): string
    {
        if(!$queryID) $this->executionModel->session->set('executionQuery', "(( `project` = 'all' ) AND ( 1  AND `status` = 'doing'))");

        return $this->executionModel->getExecutionQuery($queryID);
    }

    /**
     * 设置看板执行的菜单。
     * Set kanban menu.
     *
     * @access public
     * @return object
     */
    public function setKanbanMenuTest(): object
    {
        $this->executionModel->setKanbanMenu();
        return $this->executionModel->lang->execution->menu;
    }

    /**
     * 根据条件设置执行二级导航。
     * Set secondary navigation based on the conditions.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function removeMenuTest(int $executionID): object
    {
        $execution = $this->executionModel->fetchByID($executionID);
        $this->executionModel->removeMenu($execution);
        return $this->executionModel->lang->execution->menu;
    }

    /**
     * 获取系统关闭的功能。
     * Get the system close function.
     *
     * @param  object $execution
     * @access public
     * @return array
     */
    public function getExecutionFeaturesTest(object $execution): array
    {
        $features =  $this->executionModel->getExecutionFeatures($execution);
        foreach($features as $key => $value)
        {
            if(!$value) $features[$key] = 0;
        }

        return $features;
    }

    /**
     * 将执行ID保存到session中。
     * Save the execution ID to the session.
     *
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function saveSessionTest(int $executionID): int
    {
        $this->executionModel->saveSession($executionID);
        return $this->executionModel->session->execution;
    }

    /**
     * 更新今日的累计流图数据。
     * Update today's cumulative flow graph data.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $colName
     * @param  string $cardIdList
     * @access public
     * @return object
     */
    public function updateTodayCFDDataTest(int $executionID, string $type, string $colName, string $cardIdList): object
    {
        $columnCard = new stdclass();
        $columnCard->cards = $cardIdList;

        $laneGroup = array(array($columnCard));
        $count     = $cardIdList ? count(explode(',', $cardIdList)) : 0;

        $this->executionModel->updateTodayCFDData($executionID, $type, $colName, $laneGroup);

        return $this->executionModel->dao->select('*')->from(TABLE_CFD)
            ->where('execution')->eq($executionID)
            ->andWhere('date')->eq(helper::today())
            ->andWhere('name')->eq($colName)
            ->andWhere('type')->eq($type)
            ->andWhere('count')->eq($count)
            ->fetch();
    }

    /**
     * 根据给定条件构建执行键值对。
     * Build execution id:name pairs through the conditions.
     *
     * @param  int    $projectID
     * @param  string $mode     all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|noprefix|withobject|hideMultiple
     * @access public
     * @return array
     */
    public function buildExecutionPairsTest(int $projectID, string $mode = ''): array
    {
         $executions = $this->executionModel->dao->select("*, IF(INSTR('done,closed', status) < 2, 0, 1) AS isDone, INSTR('doing,wait,suspended,closed', status) AS sortStatus")->from(TABLE_EXECUTION)
            ->where('vision')->eq($this->executionModel->config->vision)
            ->andWhere('type')->in('stage,sprint,kanban')
            ->andWhere('project')->eq($projectID)
            ->beginIF(strpos($mode, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(strpos($mode, 'withdelete') === false)->andWhere('deleted')->eq(0)->fi()
            ->fetchAll('id');

         $allExecutions = $this->executionModel->dao->select('id,name,parent,grade')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->eq($projectID)
            ->fetchAll('id');

         $parents = array();
         foreach($allExecutions as $exec) $parents[$exec->parent] = true;

         $projectPairs = strpos($mode, 'withobject') !== false ? $this->executionModel->dao->select('id,name')->from(TABLE_PROJECT)->fetchPairs('id') : array();

         return $this->executionModel->buildExecutionPairs($mode, $allExecutions, $executions, $parents, $projectPairs);
    }

    /**
     * 处理产品计划的数据。
     * Process product planning data.
     *
     * @param  array  $productIdList
     * @param  string $param
     * @access public
     * @return array
     */
    public function processProductPlansTest(array $productIdList, string $param = ''): array
    {
        $plans = $this->executionModel->dao->select('t1.id,t1.title,t1.product,t1.parent,t1.begin,t1.end,t1.branch,t2.type as productType')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.begin desc, t1.id desc')
            ->fetchAll('id');

        return $this->executionModel->processProductPlans($plans, $param);
    }

    /**
     * 批量导入Bug后的其他数据处理。
     * other data process after import bugs.
     *
     * @param  int $taskID
     * @param  int $bugID
     * @access public
     * @return bool
     */
    public function afterImportBugTest(int $taskID, int $bugID): bool
    {
        $task = $this->executionModel->loadModel('task')->getByID($taskID);
        $bug  = $this->executionModel->loadModel('bug')->getByID($bugID);

        return $this->executionModel->afterImportBug($task, $bug);
    }

    /**
     * 测试处理树状图需求类型数据。
     * Test processStoryNode method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function processStoryNodeTest(int $executionID)
    {
        $fullTrees = $this->treeModel->getTaskStructure($executionID, 0);
        if(empty($fullTrees)) return '0';

        global $tester;
        $stories     = $tester->loadModel('story')->getListByProject($executionID);
        $storyGroups = array();
        foreach($stories as $story) $storyGroups[$story->product][$story->module][$story->id] = $story;

        $taskGroups = $this->executionModel->getTaskGroups($executionID);

        $node = (object)$fullTrees[0];
        if(!isset($node->id)) $node->id = 0;
        return $this->executionModel->processStoryNode($node, $storyGroups, $taskGroups, array(), 0);
    }

    /**
     * 测试处理树状图任务类型数据。
     * Test processTaskNode method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function processTaskNodeTest(int $executionID)
    {
        $fullTrees = $this->treeModel->getTaskStructure($executionID, 0);
        if(empty($fullTrees)) return '0';

        $node = (object)$fullTrees[0];
        if(!isset($node->id)) $node->id = 0;

        $taskGroups = $this->executionModel->getTaskGroups($executionID);
        return $this->executionModel->processTaskNode($node, $taskGroups);
    }

    /**
     * 批量处理执行的名称。
     * The name of the batch process execution.
     *
     * @param  int    $projectID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function batchProcessNameTest(int $projectID, int $count): array|int
    {
        $project    = $this->executionModel->loadModel('project')->getByID($projectID);
        $executions = $this->executionModel->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->andWhere('project')->eq($projectID)
            ->fetchAll();

        return $count ? count($executions) : $executions;
    }

    /**
     * 取消关联需求后的其他数据处理。
     * Other data process after unlink story.
     *
     * @param  int       $executionID
     * @param  int       $storyID
     * @param  int       $count
     * @access public
     * @return array|int
     */
    public function afterUnlinkStoryTest(int $executionID, int $storyID, int $count): array|int
    {
        $execution = $this->executionModel->getByID($executionID);
        $this->executionModel->afterUnlinkStory($execution, $storyID);
        if(dao::isError()) return dao::getError();

        if($count == 1)
        {
            $object = $this->executionModel->dao->select('*')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('execution')->eq($executionID)->fetchAll();
            return count($object);
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('execution')->eq($executionID)->fetchAll();
        }
    }

    /**
     * Test generateRow method.
     *
     * @access public
     * @return array
     */
    public function generateRowTest()
    {
        $executions = $this->executionModel->getStatData();
        return $this->executionModel->generateRow($executions, array(), array());
    }

    /**
     * Test appendTasks method.
     *
     * @access public
     * @return array
     */
    public function appendTasksTest()
    {
        $executions = $this->executionModel->getStatData(0, 'all', 0, 0, true);
        if(empty($executions[0]->tasks)) return false;

        return $this->executionModel->appendTasks($executions[0]->tasks, array());
    }

    /**
     * 批量处理任务，团队、父子层级、泳道等信息。
     * Batch process tasks, teams, parent-child, lanes, etc.
     *
     * @param  int    $executionID
     * @param  int    $count
     * @access public
     * @return array|int
     */
    public function processTasksTest(int $executionID, int $count): array|int
    {
        $tasks = $this->executionModel->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('execution')->eq($executionID)
            ->fetchAll('id');

        $tasks = $this->executionModel->processTasks($tasks);
        return $count ? count($tasks) : $tasks;
    }

    /**
     * 测试创建执行主库
     * Test for create main lib.
     *
     * @param  int executionID
     * @access public
     * @return object|array
     */
    public function createMainLibTest(int $executionID, string $type = 'sprint'): object|array
    {
        $libID = $this->executionModel->createMainLib(1, $executionID, $type);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            if(!$libID) return array();
            $object = $this->executionModel->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($libID)->fetch();
            return $object;
        }
    }

    /**
     * 测试添加执行团队成员
     * Test for add execution members.
     *
     * @param  int   executionID
     * @param  array members
     * @access public
     * @return object|array
     */
    public function addExecutionMembersTest(int $executionID, array $members): object|array
    {
        $this->executionModel->addExecutionMembers($executionID, $members);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->executionModel->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($executionID)->andWhere('type')->eq('execution')->fetchAll();
        }
    }

    /**
     * 测试通过 ID 列表获取执行键对。
     * Test get execution pairs by id list.
     *
     * @param  string       $executionIdList
     * @access public
     * @return string|array
     */
    public function getPairsByListTest(string $executionIdList): string|array
    {
        $executions = $this->executionModel->getPairsByList(explode(',', $executionIdList));

        if(dao::isError()) return dao::getError();
        return implode(',', $executions);
    }

    /**
     * 测试通过执行ID列表获取执行的子级ID列表组。
     * Test get the children id list of the execution group by the parent id list.
     *
     * @param  string       $parentIdList
     * @access public
     * @return string|array
     */
    public function getChildIdGroupTest(string $parentIdList): string|array
    {
        $executions = $this->executionModel->getChildIdGroup(explode(',', $parentIdList));

        if(dao::isError()) return dao::getError();
        $return = '';
        foreach($executions as $parentID => $children)
        {
            $return .= "{$parentID}:";
            foreach($children as $child) $return .= "{$child->id},";
            $return = trim($return, ',') . ';';
        }
        return $return;
    }
}
