<?php
class taskTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('task');
    }

    public function createObject($param = array(), $executionID = '')
    {
        $assignedTo = array('');
        $createFields = array('module' => '', 'story' => '', 'name' => '', 'type' => '', 'assignedTo' => $assignedTo,
            'pri' => 3, 'estimate' => '', 'estStarted' => '2021-01-10', 'deadline' => '2021-03-19', 'desc' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->create($executionID);
        if (in_array('user92', $_POST['assignedTo'], true))
        {
            $objectID = $object['user92']['id'];
        }
        else
        {
            $objectID = $object['']['id'];
        }
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
        $object = $tester->dbh->query("SELECT `parent`,`estStarted`,`deadline`,`execution`,`module`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`status`,
            `color`,`desc`,`assignedTo`,`realStarted`,`finishedBy`,`canceledBy`,`closedReason` FROM zt_task WHERE id = $objectID")->fetch();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field] = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

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

    public function batchCreateObject($param = array(), $executionID = '')
    {
        $modul = array('','','');
        $parent = array('0','0','0');
        $name = array('','','');
        $type = array('','','');
        $assignedTo = array('','','');
        $story =array('','','');
        $pri = array('3','3','3');
        $color = array('','','');
        $desc = array('','','');
        $estimate = array('','','');
        $createFields = array('parent' => $parent, 'module' => $modul, 'name' => $name, 'type' => $type, 'assignedTo' => $assignedTo,
            'pri' => $pri, 'story' => $story, 'color' => $color, 'desc' => $desc ,'estimate' => $estimate);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->batchCreate($executionID);
        if (in_array('批量任务三', $_POST['name'], true))
        {
            $objectID = $object[2]->taskID;
        }
        else
        {
            $objectID = $object[0];
        }
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

    public function batchUpdateObject($param = array(), $taskID = '')
    {
        $taskIDList = array($taskID => $taskID);
        $colors = array($taskID =>'#ff4e3e');
        $name = array($taskID =>'');
        $modules = array($taskID => '0');
        $assignedTos = array($taskID =>'');
        $types =array($taskID => '');
        $statuses = array($taskID =>'wait');
        $estStarteds = array($taskID => '');
        $deadlines = array($taskID => '');
        $pris = array($taskID => '3');
        $finishedBys = array($taskID => '');
        $canceledBys = array($taskID => '');
        $closedBys = array($taskID => '');
        $closedReasons = array($taskID => '');
        $consumeds = array($taskID => '');
        $lefts = array($taskID => '');
        $createFields = array('taskIDList' => $taskIDList, 'modules' => $modules, 'names' => $name, 'types' => $types, 'assignedTos' => $assignedTos,
            'pris' => $pris, 'estStarteds' => $estStarteds, 'colors' => $colors, 'deadlines' => $deadlines, 'statuses' => $statuses, 'finishedBys'=>$finishedBys,
            'canceledBys' => $canceledBys, 'closedBys' => $closedBys, 'closedReasons' => $closedReasons, 'consumeds' => $consumeds, 'lefts'=> $lefts);
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
            $object = $object[$taskID];
            return $object;
        }
    }

    public function batchChangeModuleTest($taskIDList, $moduleID)
    {
        $object = $this->objectModel->batchChangeModule($taskIDList, $moduleID);
        return $object[1];
    }

    public function startTest($taskID,$param = array())
    {
        $createFields = array( 'status' => 'doing', 'consumed' => '9', 'assignedTo' => '', 'comment' => '9', 'realStarted' => '', 'left' => '3');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $obj = $this->objectModel->start($taskID);
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

    public function recordEstimateTest($taskID, $param = array())
    {
        $todate   = date("Y-m-d");
        $id       = array('1','2','3');
        $dates    = array($todate, $todate, $todate);
        $consumed = array('','','');
        $left     = array('','','');
        $work     = array('','','');
        $createFields = array('id' => $id, 'dates' => $dates, 'consumed' => $consumed, 'left' => $left, 'work' => $work);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->recordEstimate($taskID);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function activateTest($taskID, $param = array())
    {
        $createFields = array('status' => 'doing', 'comment' => '单元测试','assignedTo' => '', 'left' => '3');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->activate($taskID, $extra = '');
        unset($_POST);
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

    public function assignTest($taskID, $param = array())
    {
        $createFields = array('assignedTo' => '', 'status' => '', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->assign($taskID);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function cancelTest($taskID, $param = array())
    {
        $createFields = array('status' => 'cancel', 'comment' => '单元测试');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->cancel($taskID);
        unset($_POST);
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

    public function closeTest($taskID, $param = array())
    {
        $createFields = array('status' => 'closed', 'comment' => '单元测试');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->close($taskID);
        unset($_POST);
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

    public function finishTest($taskID, $param = array())
    {
        $todate = date("Y-m-d h:i:s");
        $labels = array('');
        $createFields = array('status' => 'done', 'currentConsumed' => '', 'realStarted' => '2020-01-17 17:07:07', 'consumed' => '',
            'assignedTo' => '', 'finishedDate' => $todate, 'labels' => $labels, 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->finish($taskID);
        unset($_POST);
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

    public function getByIdTest($taskID)
    {
        $object = $this->objectModel->getById($taskID);
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

    public function getByListTest($taskID)
    {
        $object = $this->objectModel->getByList($taskID);
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

    public function getExecutionTaskPairsTest($executionID)
    {
        $object = $this->objectModel->getExecutionTaskPairs($executionID);
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

    public function getExecutionTasksTest($executionID,$count)
    {
        $object = $this->objectModel->getExecutionTasks($executionID);
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

    public function getTasksByModuleTest($executionID, $moduleIdList, $count)
    {
        $object = $this->objectModel->getTasksByModule($executionID, $moduleIdList);
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

    public function getUserTasksTest($taskID, $assignedTo)
    {
        $createFields = array('assignedTo' => $assignedTo, 'status' => 'doing', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->assign($taskID);
        $object = $this->objectModel->getUserTasks($assignedTo);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function pauseTest($taskID, $param = array())
    {
        $createFields = array('status' => 'pause', 'comment' => '单元测试');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->pause($taskID);
        unset($_POST);
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

    public function getUserTaskPairsTest($taskID, $assignedTo)
    {
        $createFields = array('assignedTo' => $assignedTo, 'status' => 'doing', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->assign($taskID);
        $object = $this->objectModel->getUserTaskPairs($assignedTo);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function getUserSuspendedTasksTest($taskID, $assignedTo)
    {
        $createFields = array('assignedTo' => $assignedTo, 'status' => 'doing', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->assign($taskID);
        $object = $this->objectModel->getUserSuspendedTasks($assignedTo);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function getStoryTasksTest($storyID, $count)
    {
        $object = $this->objectModel->getStoryTasks($storyID);
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

    public function getStoryTaskCountsTest($storyIDList)
    {
        $object = $this->objectModel->getStoryTaskCounts($storyIDList);
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

    public function getTaskEstimateTest($taskID)
    {
        $object = $this->objectModel->getTaskEstimate($taskID);
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

    public function getEstimateByIdTest($estimateID)
    {
        $object = $this->objectModel->getEstimateById($estimateID);
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

    public function updateEstimateTest($estimateID, $param = array())
    {
        $createFields = array('date' => '0000-00-00', 'consumed' => '1', 'left' => '1', 'work' => '这里是工作内容1');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $object = $this->objectModel->updateEstimate($estimateID);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function deleteEstimateTest($estimateID)
    {
        $object = $this->objectModel->deleteEstimate($estimateID);
        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function createTaskFromGitlabIssueTest($task, $executionID)
    {
        $objectID = $this->objectModel->createTaskFromGitlabIssue($task, $executionID);

        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getById($objectID);
            return $object;
        }
    }

    public function getProjectIDTest($executionID)
    {
        $object = $this->objectModel->getProjectID($executionID);

        unset($_POST);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }
}
