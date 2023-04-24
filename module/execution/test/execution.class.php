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
        global $tester;
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
     * Get no multiple execution id.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getNoMultipleIDTest($projectID)
    {
        return $this->executionModel->getNoMultipleID($projectID);
    }

    /**
     * Check begin and end date.
     *
     * @param  int    $projectID
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return bool|array
     */
    public function checkBeginAndEndDateTest($projectID, $begin, $end)
    {
        $this->executionModel->checkBeginAndEndDate($projectID, $begin, $end);

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
        global $tester;
        $tester->loadModel('programplan');

        $_POST['percent'] = $percent;
        $oldExecution = empty($executionID) ? '' : $this->executionModel->getByID($executionID);
        $this->executionModel->checkWorkload($type, $percent, $oldExecution);
        unset($_POST);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * Set Kanban.
     *
     * @param  int    $executionID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function setKanbanTest($executionID, $param = array())
    {
        global $tester;
        $tester->loadModel('kanban');

        $object = $tester->dbh->query("SELECT `displayCards`,`fluidBoard`,`colWidth`,`minColWidth`,`maxColWidth` FROM zt_project WHERE id = $executionID ")->fetch();

        $_POST['heightType'] = 'auto';
        foreach($object as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->executionModel->setKanban($executionID);
        $execution = $this->executionModel->getByID($executionID);

        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $execution;
        }
    }

    /**
     * Set project into session.
     *
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function setProjectSessionTest($executionID)
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
     * Create the select code of executions.
     *
     * @param  int    $executionID
     * @param  string $currentModule
     * @param  string $currentMethod
     * @access public
     * @return void
     */
    public function selectTest($executionID, $currentModule, $currentMethod)
    {
        $executions = $this->executionModel->getPairs();
        return $this->executionModel->select($executions, $executionID, 0, $currentModule, $currentMethod);
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
    public function createTest($param = array(), $project = '', $dayNum = '', $days = '')
    {
        $products  = array('');
        $plans     = array('');
        $whitelist = array('');
        $beginData = date('Y-m-d');
        $endData   = date('Y-m-d',strtotime("+$dayNum day"));
        $delta     = intval($dayNum) + 1;

        $createFields = array('project' => $project, 'name' => '', 'code' => '', 'begin' => $beginData, 'end' => $endData,
            'lifetime' => 'short', 'status' => 'wait', 'products' => $products, 'delta' => $delta, 'days' => $days,
            'plans' => $plans, 'team' => '', 'teams' => '0', 'PO' => '', 'QD' => '', 'PM' => '', 'RD' => '', 'whitelist' => '',
            'desc' => '', 'acl' => 'private', 'percent' => '0');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->executionModel->create();

        unset($_POST);

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

        $change = $this->executionModel->update($objectID);

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
        $dayses          = array($executionID => '');

        $createFields = array('executionIDList' => $executionIDList, 'names' => $name, 'codes' => $codes, 'PMs' => $pms, 'lifetimes' => $lifetimes,
            'begins' => $begins, 'ends' => $ends, 'descs' => $descs, 'statuses' => $statuses, 'teams' => $teams, 'dayses' => $dayses,'POs' => $pos,
            'QDs' => $qds, 'RDs' => $rds);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->executionModel->batchUpdate();

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
     * function changeStatus2Inactived test by execution
     *
     * @param  int         $executionID
     * @param  string      $status       suspended|closed
     * @access public
     * @return bool|array
     */
    public function changeStatus2InactivedObject($executionID, $status)
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

        $result = $this->executionModel->changeStatus2Inactived($executionID, $status, $selfAndChildren, $siblingList);

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
     * function start test by execution
     *
     * @param  string $executionID
     * @param  array  $param
     * @param  bool   $testParent
     * @access public
     * @return array
     */
    public function startTest($executionID,$param = array(), $testParent = false)
    {
        $data = date('Y-m-d');

        $createFields = array( 'comment' => '开始描述测试', 'realBegan' => $data);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $obj = $this->executionModel->start($executionID);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            if ($error[0] = "此任务已被启动，不能重复启动！")
            {
                return $error[0];
            }
            else
            {
                return $error;
            }
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
     * function putoff test by execution
     *
     * @param  string $executionID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function putoffTest($executionID, $param = array())
    {
        $begin = date('Y-m-d');
        $end   = date('Y-m-d',strtotime("+5 day"));

        $createFields = array('status' => 'wait', 'days' => '5', 'comment' => '延期描述测试', 'begin' => $begin, 'end' => $end);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $obj = $this->executionModel->putoff($executionID);

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

        $obj = $this->executionModel->suspend($executionID);

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

        $obj = $this->executionModel->activate($executionID);

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
     * function close test by execution
     *
     * @param  string $executionID
     * @param  array  $param
     * @param  bool   $testParent
     * @access public
     * @return array
     */
    public function closeTest($executionID, $param = array(), $testParent = false)
    {
        $todate = date('Y-m-d');

        $createFields = array('status' => 'closed', 'comment' => '关闭描述测试', 'realEnd' => $todate);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $obj = $this->executionModel->close($executionID);

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
     * function getPairs test by execution
     *
     * @param  string $projectID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getPairsTest($projectID, $count)
    {
        $object = $this->executionModel->getPairs($projectID);

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
     * function getByID test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getByIDTest($executionID)
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
     * function getByIdList test by execution
     *
     * @param  array $executionIdList
     * @access public
     * @return array
     */
    public function getByIdListTest($executionIdList)
    {
        $object = $this->executionModel->getByIdList($executionIdList);

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
     * function getList test by execution
     *
     * @param  string $projectID
     * @param  string $type
     * @param  array  $status
     * @param  string $limit
     * @param  string $productID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getListTest($projectID, $type, $status, $limit, $productID, $count)
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
     * fnction getInvolvedExecutionList test by execution
     *
     * @param  string $projectID
     * @param  string $limit
     * @param  string $productID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getInvolvedExecutionListTest($projectID, $limit, $productID, $count)
    {
        $object = $this->executionModel->getInvolvedExecutionList($projectID,$status = 'involved',$limit, $productID);

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
     * function getByProject test by execution
     *
     * @param  string $projectID
     * @param  array  $status
     * @param  string $limit
     * @param  string $count
     * @access public
     * @return array
     */
    public function getByProjectTest($projectID, $status, $limit, $count)
    {
        $object = $this->executionModel->getByProject($projectID, $status, $limit);

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
     * function getIdList test execution
     *
     * @param  string $projectID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getIdListTest($projectID, $count)
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
        $app->moduleName = 'task';
        $object = $this->executionModel->getTree($executionID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object[0] ? count($object[0]->children) : 0;
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
     * function getChildExecutions test execution
     *
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getChildExecutionsTest($executionID, $count)
    {
        $object = $this->executionModel->getChildExecutions($executionID);

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
     * Check the privilege.
     *
     * @access public
     * @return string|bool
     */
    public function getLimitedExecutionTest()
    {
        $this->executionModel->getProductGroupList();
        return isset($_SESSION['limitedExecutions']) ? $_SESSION['limitedExecutions'] : true;
    }

    /**
     * function getProductGroupList test execution
     *
     * @param  string $count
     * @access public
     * @return array
     */
    public function getProductGroupListTest($count)
    {
        $object = $this->executionModel->getProductGroupList();

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
            return $object[""];
        }
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
        global $tester;

        $execution  = $tester->dbh->query("select * from zt_project where id = $executionID")->fetch();
        $executions = array($executionID => $execution->name);
        $page       = 'NULL';

        $object = $this->executionModel->getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $page);

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
     * function getDefaultManagers test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getDefaultManagersTest($executionID)
    {
        $object = $this->executionModel->getDefaultManagers($executionID);

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
     * function getBranchByProduct test by execution
     *
     * @param  string $productID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getBranchByProductTest($productID, $count)
    {
        $object = $this->executionModel->getBranchByProduct($productID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return isset($object[$productID]) ? count($object[$productID]) : 0;
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getOrderedExecutions test exection
     *
     * @param  string $projectID
     * @param  string $status
     * @param  string $count
     * @access public
     * @return array
     */
    public function getOrderedExecutionsTest($projectID, $status, $count)
    {
        $object = $this->executionModel->getOrderedExecutions($projectID,$status);

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
     * function getToImport test execution
     *
     * @param  string $executionIds
     * @param  string $type
     * @param  string $count
     * @access public
     * @return array
     */
    public function getToImportTest($executionIds, $type, $count)
    {
        $object = $this->executionModel->getToImport($executionIds, $type);

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
     * function updateProducts test by execution
     *
     * @param  string $executionID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateProductsTest($executionID,$param = array())
    {
        global $tester;

        $products = array();
        $branch   = array();

        $createFields = array('products' => $products, 'branch' => $branch, 'post' => 'post');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->executionModel->updateProducts($executionID);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {

            $productList  = $tester->dbh->query("select * from zt_projectproduct where project = $executionID")->fetchAll();
            return $productList;
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

        $object = $this->executionModel->getTasks2Imported($toExecution, $branches);

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
     * function importTask test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  array  $param
     * @access public
     * @return array
     */
    public function importTaskTest($executionID, $count, $param = array())
    {
        global $tester;

        $tasks        = array();
        $createFields = array('tasks' => $tasks);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->executionModel->importTask($executionID);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            $taskList = $tester->dbh->query("select * from zt_task where execution = $executionID")->fetchAll();
            return count($taskList);
        }
        else
        {
            $taskList = $tester->dbh->query("select * from zt_task where execution = $executionID")->fetchAll();
            return $taskList;
        }
    }

    /**
     * function statRelatedData test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function statRelatedDataTest($executionID)
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
     * function importBug test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  array  $param
     * @access public
     * @return array
     */
    public function importBugTest($executionID, $count, $param = array())
    {
        $import     = array();
        $id         = array();
        $pri        = array();
        $assignedTo = array();
        $estimate   = array();
        $deadline   = array();

        $createFields = array('import' => $import, 'id' => $id, 'pri' => $pri, 'assignedTo' => $assignedTo,
            'estimate' => $estimate, 'deadline' => $deadline);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->executionModel->importBug($executionID);

        unset($_POST);

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
     * function updateChilds test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateChildsTest($executionID, $count, $param = array())
    {
        global $tester;

        $childs = array();
        $createFields = array('childs' => $childs);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->executionModel->updateChilds($executionID);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            $childExecutions = $tester->dbh->query("select id,name,type from zt_project where parent = $executionID")->fetchAll();
            return count($childExecutions);
        }
        else
        {
            $childExecutions = $tester->dbh->query("select id,name,type from zt_project where parent = $executionID")->fetchAll();
            return $childExecutions;
        }
    }

    /**
     * function changeProject test by execution
     *
     * @param  string $newProject
     * @param  string $oldProject
     * @param  string $executionID
     * @param  array  $syncStories
     * @access public
     * @return array
     */
    public function changeProjectTest($newProject, $oldProject, $executionID, $syncStories = 'no')
    {
        global $tester;

        $this->executionModel->changeProject($newProject, $oldProject, $executionID, $syncStories);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            $newExecution = $tester->dbh->query("select parent,path from zt_project where id = $executionID")->fetchAll();
            return $newExecution;
        }
    }

    /**
     * function linkStory test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  array  $param
     * @access public
     * @return array
     */
    public function linkStoryTest($executionID, $count, $param = array())
    {
        global $tester;

        $stories  = array();
        $products = array();

        $createFields = array('stories' => $stories, 'products' => $products);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $tester->dbh->query("delete from zt_projectstory where project = $executionID");

        $this->executionModel->linkStory($executionID);

        unset($_POST);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            $object = $tester->dbh->query("select * from zt_projectstory where project = $executionID")->fetchAll();
            return count($object);
        }
        else
        {
            $object = $tester->dbh->query("select * from zt_projectstory where project = $executionID")->fetchAll();
            return $object;
        }
    }

    /**
     * Link all stories by execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $planID
     * @access public
     * @return int
     */
    public function linkStoriesTest($executionID, $productID = 0, $planID = 0)
    {
        global $tester;
        if($planID) $tester->dao->update(TABLE_PROJECTPRODUCT)->set('plan')->eq($planID)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->exec();

        $this->executionModel->linkStories($executionID);
        $objects = $tester->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->fetchAll();
        return count($objects);
    }

    /**
     * function linkCases test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  string $productID
     * @param  string $storyID
     * @access public
     * @return array
     */
    public function linkCasesTest($executionID, $count, $productID, $storyID)
    {
        global $tester;

        $tester->dbh->query("delete from zt_projectcase where project = $executionID");

        $this->executionModel->linkCases($executionID, $productID, $storyID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            $object = $tester->dbh->query("select * from zt_projectcase where project = $executionID")->fetchAll();
            return count($object);
        }
        else
        {
            $object = $tester->dbh->query("select * from zt_projectcase where project = $executionID")->fetchAll();
            return $object;
        }
    }

    /**
     * function unlinkStory test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  string $storyID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function unlinkStoryTest($executionID, $count, $storyID, $param = array())
    {
        global $tester;

        $stories  = array();
        $products = array();

        $createFields = array('stories' => $stories, 'products' => $products);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $tester->dbh->query("delete from zt_projectstory where project = $executionID");

        $this->executionModel->linkStory($executionID);

        unset($_POST);

        $this->executionModel->unlinkStory($executionID, $storyID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            $object = $tester->dbh->query("select * from zt_projectstory where project = $executionID")->fetchAll();
            return count($object);
        }
        else
        {
            $object = $tester->dbh->query("select * from zt_projectstory where project = $executionID")->fetchAll();
            if(isset($object))
            {
                return $object;
            }
            else
            {
                return "全部需求已解除";
            }
        }
    }

    /**
     * function unlinkCases test by execution
     *
     * @param  string $executionID
     * @param  string $productID
     * @param  string $storyID
     * @access public
     * @return array
     */
    public function unlinkCasesTest($executionID, $productID, $storyID)
    {
        global $tester;

        $tester->dbh->query("delete from zt_projectcase where project = $executionID");

        $this->executionModel->linkCases($executionID, $productID, $storyID);
        $this->executionModel->unlinkCases($executionID, $storyID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            $object = $tester->dbh->query("select * from zt_projectcase where project = $executionID")->fetchAll();
            return $object;
        }
    }

    /**
     * function getTeamMembers test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getTeamMembersTest($executionID, $count)
    {
        $object = $this->executionModel->getTeamMembers($executionID);

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
     * function getTeams2Import test by execution
     *
     * @param  string $account
     * @param  string $currentExecution
     * @param  string $count
     * @access public
     * @return array
     */
    public function getTeams2ImportTest($account, $currentExecution, $count)
    {
        $object = $this->executionModel->getTeams2Import($account, $currentExecution);

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
     * function getMembers2Import test by execution
     *
     * @param  string $execution
     * @param  array  $currentMembers
     * @param  string $count
     * @access public
     * @return array
     */
    public function getMembers2ImportTest($execution, $currentMembers, $count)
    {
        $object = $this->executionModel->getMembers2Import($execution, $currentMembers);

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
     * function getCanCopyObjects test by execution
     *
     * @param  int    $projectID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getCanCopyObjectsTest($projectID = 0, $count = 0)
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
     * function manageMembers test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  array $param
     * @access public
     * @return array
     */
    public function manageMembersTest($executionID, $count, $param = array())
    {
        global $tester;
        $tester->dbh->query("delete from zt_team where root = $executionID");

        $realnames = array();
        $roles     = array();
        $days      = array();
        $hours     = array();
        $accounts  = array();
        $limited   = array();

        $createFields = array('realnames' => $realnames, 'roles' => $roles, 'hours' => $hours, 'accounts' => $accounts,
            'limited' => $limited, 'days' => $days);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->executionModel->manageMembers($executionID);

        unset($_POST);

        $object = $tester->dbh->query("select * from zt_team where root = $executionID")->fetchAll();

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
     * function addProjectMembers test by execution
     *
     * @param  int    $projectID
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function addProjectMembersTest($projectID = 0, $executionID = 0, $count = 0)
    {
        global $tester;
        $tester->dbh->query("delete from zt_team where root = $projectID");
        $executionMembers = $tester->dao->select('`root`,`account`,`join`,`role`,`days`,`type`,`hours`')->from(TABLE_TEAM)->where('root')->eq($executionID)->fetchAll('account');

        $this->executionModel->addProjectMembers($projectID, $executionMembers);

        $object = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($projectID)->fetchAll();

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
     * function unlinkMember test by execution
     *
     * @param  string $sprintID
     * @param  string $account
     * @param  string $count
     * @access public
     * @return array
     */
    public function unlinkMemberTest($sprintID, $account, $count)
    {
        global $tester;
        $oldObject = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($sprintID)->fetchAll();

        $this->executionModel->unlinkMember($sprintID, $account);

        $object = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($sprintID)->fetchAll();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
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
    public function fixFirstTest($executionID, $param = array(), $date)
    {
        global $tester;

        $createFields = array('estimate' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->executionModel->computeBurn();
        $this->executionModel->fixFirst($executionID);

        unset($_POST);

        $object = $tester->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('date')->eq($date)->fetchAll();

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
     * Function getBurnDataFlot test by execution.
     *
     * @param  int   $executionID
     * @access public
     * @return int
     */
    public function getBurnDataFlotTest($executionID = 0)
    {
        $object = $this->executionModel->getBurnDataFlot($executionID, $burnBy = 'left');

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
     * Function getBurnData test by execution.
     *
     * @param  int   $executionID
     * @access public
     * @return int
     */
    public function getBurnDataTest($executionID = 0)
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
     * function processBurnData test by execution
     *
     * @param  string $executionID
     * @param  array  $itemCounts
     * @param  string $begin
     * @param  string $end
     * @param  string $count
     * @access public
     * @return array
     */
    public function processBurnDataTest($executionID, $itemCounts, $begin, $end, $count)
    {
        global $tester;
        $sets = $tester->dao->select('execution, `date` as name, `left` as value')->from(TABLE_BURN)->where('execution')->eq($executionID)->fetchAll('name');

        $object = $this->executionModel->processBurnData($sets, $itemCounts, $begin, $end);

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
     * function summary test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function summaryTest($executionID)
    {
        global $tester;
        $execution  = $tester->dbh->query("select * from zt_project where id = $executionID")->fetch();
        $executions = array($executionID => $execution->name);
        $page       = 'NULL';
        $tasks      = $this->executionModel->getTasks('0', $executionID, $executions,'all', '0', '0', 'status,id_desc', $page);

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
     * function isClickable test by execution
     *
     * @param  string $execution
     * @param  string $action
     * @access public
     * @return array
     */
    public function isClickableTest($execution, $action)
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
     * function getDateList test by execution
     *
     * @param  string $begin
     * @param  string $end
     * @param  string $type
     * @param  string $count
     * @param  int $interval
     * @access public
     * @return array
     */
    public function getDateListTest($begin, $end, $type, $count, $interval = 0)
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
     * function getTotalEstimate test by execution
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getTotalEstimateTest($executionID)
    {
        $object = $this->executionModel->getTotalEstimate($executionID);

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
     * function fixOrder test by execution
     *
     * @access public
     * @return array
     */
    public function fixOrderTest()
    {
        global $tester;

        $this->executionModel->fixOrder();

        $object = $tester->dao->select('id,`order`')->from(TABLE_EXECUTION)->fetchAll('id');

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
     * function getKanbanTasks test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getKanbanTasksTest($executionID, $count)
    {
        $object = $this->executionModel->getKanbanTasks($executionID);

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
        $object        = $this->executionModel->getKanbanColumns($kanbanSetting);

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
     * function buildBurnData test by execution
     *
     * @param  string $executionID
     * @param  string $count
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildBurnDataTest($executionID, $count, $type = 'noweek')
    {
        $begin = '2022-01-07';
        $end   = '2022-01-17';

        $dateList = $this->executionModel->getDateList($begin, $end, $type, 0, $format = 'Y-m-d');

        $object = $this->executionModel->buildBurnData($executionID, $dateList[0], $type);

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
     * function getPlans test by execution
     *
     * @param array  $products
     * @param string $executionID
     * @param string $count
     * @access public
     * @return array
     */
    public function getPlansTest($products, $executionID, $count)
    {
        $object = $this->executionModel->getPlans($products,'', $executionID);

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
     * function setTreePath test by executon
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
     * Test get begin and end for CFD.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getBeginEnd4CFDTest($executionID)
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
     * Test get taskes by search.
     *
     * @param  string $condition
     * @param  int    $recPerPage
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getSearchTasksTest($condition, $recPerPage, $orderBy)
    {
        global $tester;

        /* Load pager. */
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager(0, $recPerPage, 1);

        $objects = $this->executionModel->getSearchTasks($condition, $pager, $orderBy);

        $returns = '';
        foreach($objects as $object)
        {
            $returns .= "$object->id:name:$object->name";
            if(!empty($object->team))
            {
                $returns .= ',team:[';
                foreach($object->team as $team) $returns .= "$team->id,";
                $returns = trim($returns, ',');
                $returns .= ']';
            }
            $returns .= ';';
        }

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $returns;
        }
    }

    /**
     * Test Get bugs by search in execution.
     *
     * @param array  $products
     * @param int    $executionID
     * @param string $sql
     * @param object $pager
     * @param string $orderBy
     * @access public
     * @return void
     */
    public function getSearchBugsTest($products, $executionID, $sql, $pager = null, $orderBy = 'id_desc')
    {
        $object = $this->executionModel->getSearchBugs($products, $executionID, $sql, $pager, $orderBy);

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
     * Test Get Prev Kanban.
     *
     * @param int $executionID
     * @access public
     * @return void
     */
    public function getPrevKanbanTest($executionID)
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
     * Test save Kanban Data.
     *
     * @param int $executionID
     * @param array $kanbanDatas
     * @access public
     * @return void
     */
    public function saveKanbanDataTest($executionID, $kanbanDatas = '')
    {
        if($kanbanDatas === '')
        {
            global $tester;
            $contents    = array('story', 'wait', 'doing', 'done', 'cancel');
            $stories     = $tester->loadModel('story')->getExecutionStories($executionID, 0, 0, 'id_asc');
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
            $kanbanDatas = $datas;
        }

        $object = $this->executionModel->saveKanbanData($executionID, $kanbanDatas);

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
     * Test Update user view of execution and it's product.
     *
     * @param int    $executionID
     * @param string $objectType
     * @param array $users
     * @access public
     * @return void
     */
    public function updateUserViewTest($executionID, $objectType = 'sprint', $users = array())
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
     * Test set menu.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function setMenuTest($executionID = 0)
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return '0';

        $this->executionModel->setMenu($executionID);

        global $lang;
        return strip_tags($lang->switcherMenu);
    }

    /**
     * Get switcher.
     *
     * @param  int    $executionID
     * @param  string $method
     * @access public
     * @return string
     */
    public function getSwitcherTest($executionID = 0, $method = '')
    {
        return $this->executionModel->getSwitcher($executionID, 'execution', $method);
    }

    /**
     * Test sync no multiple sprint.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function syncNoMultipleSprintTest($projectID)
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
     * Test print cell.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function printCellTest($executionID)
    {
        $col = new stdClass();
        $col->order = 1;
        $col->id    = 'id';
        $col->show  = 1;
        $col->width = 70;
        $col->fixed = 'left';
        $col->title = 'ID';
        $col->sort  = 'yes';
        $col->name  = '';

        $execution = $this->executionModel->getByID($executionID);

        ob_start();
        $this->executionModel->printCell($col, $execution, array());
        $objects = ob_get_clean();

        if(dao::isError()) return dao::getError();

        return strip_tags($objects);
    }

    /**
     * Test build operate menu.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function buildOperateMenuTest($executionID = 0)
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return '0';

        return $this->executionModel->buildOperateMenu($execution);
    }

    /**
     * Test print nested list.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function printNestedListTest($projectID = 0)
    {
        $executions = $this->executionModel->getStatData($projectID);

        global $app;
        $app->moduleName = 'execution';
        ob_start();
        $this->executionModel->printNestedList($executions[0], false, array(), 1);
        $result = ob_get_clean();
        return mb_substr(strip_tags($result), 0, 2);
    }

    /**
     * Test print nested list.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function printTreeTest($executionID)
    {
        global $app;
        $app->moduleName = 'task';
        $tree = $this->executionModel->getTree($executionID);
        return str_replace(' ', '', strip_tags($this->executionModel->printTree($tree, false)));
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
        $tasks = $this->executionModel->getSearchTasks("execution = $executionID", null, 'id_desc');
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
     * Test build bug search form.
     *
     * @param  int    $productID
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function buildBugSearchFormTest($productID, $queryID)
    {
        $product = $this->productModel->getByID($productID);
        if(empty($product)) return '0';

        $this->executionModel->loadModel('bug');
        $this->executionModel->buildBugSearchForm(array($productID => $product), $queryID, 'searchBug');

        return $_SESSION['executionBugsearchParams']['queryID'];
    }

    /**
     * Test build story search form.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function buildStorySearchFormTest($executionID, $queryID)
    {
        $execution = $this->executionModel->getByID($executionID);
        if(empty($execution)) return '0';

        $this->executionModel->loadModel('story');
        $products     = $this->productModel->getProducts($executionID);
        $branchGroups = $this->executionModel->loadModel('branch')->getByProducts(array_keys($products));
        $this->executionModel->buildStorySearchForm($products, $branchGroups, array(), $queryID, 'searchStory', 'executionStory', $execution);

        return $_SESSION['executionStorysearchParams']['queryID'];
    }

    /**
     * Test get CFD data.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getCFDDataTest($executionID = 0)
    {
        $begin = strtotime('2022-01-12');
        $end   = strtotime('2022-02-12');

        $dateList = array();
        for($date = $begin; $date <= $end; $date += 24 * 3600) $dateList[] = date('Y-m-d', $date);

        return $this->executionModel->getCFDData($executionID, $dateList);
    }

    /**
     * Test build CFD data.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function buildCFDDataTest($executionID = 0)
    {
        $begin = strtotime('2022-01-12');
        $end   = strtotime('2022-02-12');

        $dateList = array();
        for($date = $begin; $date <= $end; $date += 24 * 3600) $dateList[] = date('Y-m-d', $date);

        return $this->executionModel->buildCFDData($executionID, $dateList, 'task');
    }

    /**
     * Test check CFD data.
     *
     * @param  int    $executionID
     * @param  string $date
     * @access public
     * @return array
     */
    public function checkCFDDataTest($executionID, $date)
    {
        $this->executionModel->checkCFDData($executionID, $date);
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
     * Reset execution sorts.
     *
     * @param  int    $projectID
     * @param  string $type noParent
     * @access public
     * @return string
     */
    public function resetExecutionSortsTest($projectID, $type = '')
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
}
