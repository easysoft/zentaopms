<?php
class executionTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('execution');
    }

    public function getHour()
    {
        return date('Y-m-d');
    }

    public function getReduceHour($dayNum)
    {
        return date('Y-m-d',strtotime("-$dayNum day"));
    }

    public function createObject($param = array(), $project, $dayNum, $days)
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
            'desc' => '', 'acl' => 'private');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create();
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getByID($objectID);
            return $object;
        }
    }

    public function updateObject($objectID, $param = array())
    {
        global $tester;
        $products = array('1','81','91');
        $object = $tester->dbh->query("SELECT `project`,`name`,`code`,`begin`,`end`,`days`,`lifetime`,`team`,`status`,`PO`,`QD`,`PM`,
            `RD`,`desc`,`acl` FROM zt_project WHERE id = $objectID ")->fetch();
        $object->products = $products;
        foreach($object as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $change = $this->objectModel->update($objectID);
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
            'begins' => $begins, 'ends' => $ends, 'descs' => $descs, 'statuses' => $statuses, 'teams'=>$teams, 'dayses' => $dayses,'POs' => $pos,
            'QDs' => $qds, 'RDs' => $rds);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->batchUpdate();
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

    public function startTest($executionID,$param = array())
    {
        $data = date('Y-m-d');
        $createFields = array( 'comment' => '开始描述测试', 'realBegan' => $data);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->start($executionID);
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
            return $obj;
        }
    }

    public function putoffTest($executionID, $param = array())
    {
        $begin = date('Y-m-d');
        $end   = date('Y-m-d',strtotime("+5 day"));
        $createFields = array('status' => 'wait', 'days' => '5', 'comment' => '延期描述测试', 'begin' => $begin, 'end' => $end);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->putoff($executionID);
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

    public function suspendTest($executionID, $param = array())
    {
        $createFields = array('status' => 'suspended', 'comment' => '挂起描述测试');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->suspend($executionID);
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

    public function activateTest($executionID, $param = array())
    {
        self::suspendTest($executionID);
        $begin = date('Y-m-d');
        $end   = date('Y-m-d',strtotime("+5 day"));
        $createFields = array('status' => 'doing', 'comment' => '激活描述测试', 'begin' => $begin, 'end' => $end);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->activate($executionID);
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

    public function closeTest($executionID, $param = array())
    {
        $todate = date('Y-m-d');
        $createFields = array('status' => 'closed', 'comment' => '关闭描述测试', 'realEnd' => $todate);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->close($executionID);
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

    public function getPairsTest($projectID,$count)
    {
        $object = $this->objectModel->getPairs($projectID);
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

    public function getByIDTest($executionID)
    {
        $object = $this->objectModel->getByID($executionID);
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

    public function getByIdListTest($executionIdList)
    {
        $object = $this->objectModel->getByIdList($executionIdList);
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

    public function getListTest($projectID, $type, $status, $limit, $productID, $count)
    {
        $object = $this->objectModel->getList($projectID, $type, $status, $limit, $productID);
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

    public function getInvolvedExecutionListTest($projectID, $limit, $productID, $count)
    {
        $object = $this->objectModel->getInvolvedExecutionList($projectID,$status = 'involved',$limit, $productID);
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

    public function getByProjectTest($projectID, $status, $limit, $count)
    {
        $object = $this->objectModel->getByProject($projectID, $status, $limit);
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

    public function getIdListTest($projectID, $count)
    {
        $object = $this->objectModel->getIdList($projectID);
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

    public function getBranchesTest($executionID, $count)
    {
        $object = $this->objectModel->getBranches($executionID);
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

    public function getTreeTest_bak($executionID, $count)
    {
        $object = $this->objectModel->getTree($executionID);
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

    public function getRelatedExecutionsTest($executionID, $count)
    {
        $object = $this->objectModel->getRelatedExecutions($executionID);
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

    public function getChildExecutionsTest($executionID, $count)
    {
        $object = $this->objectModel->getChildExecutions($executionID);
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

    public function getProductGroupListTest($count)
    {
        $object = $this->objectModel->getProductGroupList();
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

    public function getTasksTest($productID, $executionID, $browseType, $queryID, $moduleID, $sort, $count)
    {
        global $tester;
        $execution  = $tester->dbh->query("select * from zt_project where id = $executionID")->fetch();
        $executions = array($executionID => $execution->name);
        $page       = 'NULL';
        $object = $this->objectModel->getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $page);
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

    public function getDefaultManagersTest($executionID)
    {
        $object = $this->objectModel->getDefaultManagers($executionID);
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

    public function getBranchByProductTest($productID, $count)
    {
        $object = $this->objectModel->getBranchByProduct($productID);
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

    public function getOrderedExecutionsTest($projectID, $status, $count)
    {
        $object = $this->objectModel->getOrderedExecutions($projectID,$status);
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

    public function getToImportTest($executionIds, $type, $count)
    {
        $object = $this->objectModel->getToImport($executionIds, $type);
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

    public function updateProductsTest($executionID,$param = array())
    {
        global $tester;
        $products = array();
        $branch   = array();
        $createFields = array('products' => $products, 'branch' => $branch, 'post' => 'post');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $this->objectModel->updateProducts($executionID);
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

    public function getTasks2ImportedTest($toExecution, $count)
    {
        $branches = $this->objectModel->getBranches($toExecution);

        $object = $this->objectModel->getTasks2Imported($toExecution, $branches);
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

    public function importTaskTest($executionID, $count, $param = array())
    {
        global $tester;
        $tasks        = array();
        $createFields = array('tasks' => $tasks);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->importTask($executionID);
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

    public function statRelatedDataTest($executionID)
    {
        $object = $this->objectModel->statRelatedData($executionID);
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

        $object = $this->objectModel->importBug($executionID);
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

    public function updateChildsTest($executionID, $count, $param = array())
    {
        global $tester;
        $childs = array();
        $createFields = array('childs' => $childs);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->updateChilds($executionID);
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

    public function changeProjectTest($newProject, $oldProject, $executionID, $syncStories = 'no')
    {
        global $tester;
        $this->objectModel->changeProject($newProject, $oldProject, $executionID, $syncStories);
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

    public function linkStoryTest($executionID, $count, $param = array())
    {
        global $tester;
        $stories  = array();
        $products = array();
        $createFields = array('stories' => $stories, 'products' => $products);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $tester->dbh->query("delete from zt_projectstory where project = $executionID");

        $this->objectModel->linkStory($executionID);
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

    public function linkCasesTest($executionID, $count, $productID, $storyID)
    {
        global $tester;
        $tester->dbh->query("delete from zt_projectcase where project = $executionID");

        $this->objectModel->linkCases($executionID, $productID, $storyID);
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

    public function unlinkStoryTest($executionID, $count, $storyID, $param = array())
    {
        global $tester;
        $stories  = array();
        $products = array();
        $createFields = array('stories' => $stories, 'products' => $products);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $tester->dbh->query("delete from zt_projectstory where project = $executionID");

        $this->objectModel->linkStory($executionID);
        unset($_POST);
        $this->objectModel->unlinkStory($executionID, $storyID);
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

    public function unlinkCasesTest($executionID, $productID, $storyID)
    {
        global $tester;
        $tester->dbh->query("delete from zt_projectcase where project = $executionID");

        $this->objectModel->linkCases($executionID, $productID, $storyID);
        $this->objectModel->unlinkCases($executionID, $storyID);
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

    public function getTeamMembersTest($executionID, $count)
    {
        $object = $this->objectModel->getTeamMembers($executionID);
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

    public function getMembersByIdListTest($executionIdList, $count)
    {
        $object = $this->objectModel->getMembersByIdList($executionIdList);
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

    public function getTeamSkipTest($taskID, $begin, $end, $count)
    {
        global $tester;
        $teams = $tester->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->orderBy('order')->fetchAll('account');
        $object = $this->objectModel->getTeamSkip($teams, $begin, $end);
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
}
