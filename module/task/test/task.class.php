<?php
class taskTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('task');
    }

    /**
     * Create a task.
     *
     * @param  array      $param
     * @param  array      $assignedToList
     * @param  int        $multiple
     * @param  array      $team
     * @param  bool       $selectTestStory
     * @param  array      $teamSourceList
     * @param  array      $teamEstimateList
     * @param  array|bool $teamConsumedList
     * @param  array|bool $teamLeftList
     * @param  string     $requiredFields
     * @access public
     * @return array|object
     */
    public function createTest($param, $assignedToList = array(), $multiple = 0, $team = array(), $selectTestStory = false, $teamSourceList = array(), $teamEstimateList = array(), $teamConsumedList = false, $teamLeftList = false, $requiredFields = '')
    {
        global $tester;
        $_SERVER['HTTP_HOST'] = $tester->config->db->host;

        if($requiredFields) $tester->config->task->create->requiredFields = $tester->config->task->create->requiredFields . ',' . $requiredFields;

        $task         = new stdclass();
        $createFields = array('mailto' => '', 'mode' => '', 'status' => 'wait');
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
        foreach($param as $key => $value) $task->$key = $value;
        $taskIdList = $this->objectModel->create($task, $assignedToList, $multiple, $team, $selectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList);

        unset($_POST);
        if(dao::isError()) return dao::getError();

        if(!$taskIdList) return false;
        return $this->objectModel->getByID(current($taskIdList));
    }

    /**
     * Test update a task.
     *
     * @param  int   $objectID
     * @param  array $param
     * @access public
     * @return array|string
     */
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

    /**
     * Test batch create tasks.
     *
     * @param  array  $data
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  int    $storyID
     * @param  bool   $verifyScore
     * @param  array  $output
     * @access public
     * @return object|int
     */
    public function batchCreateObject($data = array(), $executionID = 0, $taskID = 0, $storyID = 0, $verifyScore = false, $output = array())
    {
        global $tester;

        $_SERVER['HTTP_HOST'] = $tester->config->db->host;

        $execution = $tester->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        $lastScore = $tester->dao->select('after')->from(TABLE_SCORE)->orderBy('id_desc')->limit(1)->fetch('after');

        $objectIdList = $this->objectModel->batchCreate($execution, $data, $output);

        $childTask = array();
        if(!dao::isError()) $childTask = $this->objectModel->getById(current($objectIdList));

        if(dao::isError())
        {
            return dao::getError();
        }
        elseif(!empty($taskID))
        {
            $parentTask = $this->objectModel->getById($childTask->parent);
            return $parentTask;
        }
        elseif(!empty($storyID))
        {
            $releatedStory = $tester->loadModel('story')->getById($childTask->story);
            return $releatedStory;
        }
        elseif($verifyScore)
        {
            $score = $tester->dao->select('after')->from(TABLE_SCORE)->orderBy('id_desc')->limit(1)->fetch('after');
            return $score == $lastScore + 1;
        }
        elseif(!empty($output))
        {
            $laneID   = isset($output['laneID'])   ? $output['laneID']   : 0;
            $columnID = isset($output['columnID']) ? $output['columnID'] : 0;
            $cards    = $tester->dao->select('cards')->from(TABLE_KANBANCELL)
                ->where('kanban')->eq($executionID)
                ->andWhere('lane')->eq($laneID)
                ->andWhere('column')->eq($columnID)
                ->andWhere('type')->eq('task')
                ->fetch('cards');
            $taskIdList   = trim($cards, ',');
            $taskIdList   = explode(',', $taskIdList);
            $latestTaskID = current($taskIdList);
            $task         = $this->objectModel->getById($latestTaskID);

            return $task ? $task : false;
        }
        else
        {
            return count($objectIdList);
        }
    }

    /**
     * Test batch update tasks.
     *
     * @param  array  $param
     * @param  int    $taskID
     * @access public
     * @return array
     */
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

    /**
     * Test batch change module.
     *
     * @param  array  $taskIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModuleTest($taskIDList, $moduleID)
    {
        $object = $this->objectModel->batchChangeModule($taskIDList, $moduleID);
        return $object[1];
    }

    public function startTest($taskID, $param = array())
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

    /**
     * Test record estimate and left of task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test activate a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test assign a task to a user again.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function assignTest($taskID, $param = array())
    {
        $createFields = array('assignedTo' => '', 'status' => '', 'comment' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $task = $_POST;
        $task['id'] = $taskID;
        unset($task['comment']);
        $object = $this->objectModel->assign((object)$task, $taskID);
        unset($_POST);
        if(dao::isError())
        {
            $errors = dao::getError();
            return array_shift($errors);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test assign a task to a team again.
     *
     * @param  int      $taskID
     * @param  string   $status
     * @param  array    $team
     * @param  array    $teamSource
     * @param  array    $teamConsumed
     * @param  araay    $teamLeft
     * @access public
     * @return object
     */
    public function updateTeamTest($taskID, $status, $team, $teamSource, $teamEstimate, $teamConsumed, $teamLeft, $getTeam = false)
    {
        global $tester;

        $task = new stdclass();
        $task->id           = $taskID;
        $task->status       = $status;
        $task->lastEditedBy = $tester->app->user->account;
        $object = $this->objectModel->updateTeam($task, $team, $teamSource, $teamEstimate, $teamConsumed, $teamLeft);

        if($getTeam) return $this->objectModel->getTeamMembers($taskID);
        if(dao::isError())
        {
            $errors = dao::getError();
            return array_shift($errors);
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test cancel a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test close a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test finish a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test get task info by Id.
     *
     * @param  int   $taskID
     * @access public
     * @return object
     */
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

    /**
     * Test get task info by Id List.
     *
     * @param  int|array|string $taskID
     * @access public
     * @return array
     */
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

    /**
     * Test get execution tasks pairs..
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
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

    /**
     * Test get tasks of a execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $type
     * @param  string $modules
     * @param  string $orderBy
     * @param  string $count
     * @access public
     * @return array
     */
    public function getExecutionTasksTest($executionID, $productID = 0, $type = 'all', $modules = array(), $orderBy = 'status_asc, id_desc', $count = '0')
    {
        $tasks = $this->objectModel->getExecutionTasks($executionID, $productID, $type, $modules, $orderBy);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == "1")
        {
            return count($tasks);
        }
        else
        {
            return $tasks;
        }
    }

    /**
     * Test get tasks list of a execution.
     *
     * @param  int    $executionID
     * @param  array  $moduleIdList
     * @param  int    $count
     * @access public
     * @return array
     */
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

    /**
     * Test get tasks of a user.
     *
     * @param  int    $taskID
     * @param  string $assignedTo
     * @access public
     * @return array
     */
    public function getUserTasksTest($account, $type = 'assignedTo', $limit = 0, $pager = null, $orderBy = 'id_desc', $projectID = 0)
    {
        $object = $this->objectModel->getUserTasks($account, $type, $limit, $pager, $orderBy, $projectID);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test pause a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test get tasks pairs of a user.
     *
     * @param  int    $taskID
     * @param  string $assignedTo
     * @access public
     * @return array
     */
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

    /**
     * Test get suspended tasks of a user.
     *
     * @param  int    $taskID
     * @param  string $assignedTo
     * @access public
     * @return array
     */
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

    /**
     * Test get task pairs of a story.
     *
     * @param  int    $storyID
     * @param  int    $count
     * @access public
     * @return array
     */
    public function getListByStoryTest($storyID, $count)
    {
        $object = $this->objectModel->getListByStory($storyID);
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
     * Test get counts of some stories' tasks.
     *
     * @param  array  $storyIDList
     * @access public
     * @return int
     */
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

    /**
     * Test get task efforts.
     *
     * @param  int    $taskID
     * @param  string $account
     * @param  string $append
     * @access public
     * @return object
     */
    public function getTaskEffortsTest($taskID, $account = '', $append = '')
    {
        $object = $this->objectModel->getTaskEfforts($taskID, $account, $append);
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
     * Test get estimate by id.
     *
     * @param  int    $estimateID
     * @access public
     * @return object
     */
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

    /**
     * Test update estimate.
     *
     * @param  int    $estimateID
     * @param  array  $param
     * @access public
     * @return array
     */
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

    /**
     * Test delete estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return array
     */
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

    /**
     * Test create task from gitlab issue.
     *
     * @param  array  $task
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function createTaskFromGitlabIssueTest($task, $executionID)
    {
        $objectID = $this->objectModel->createTaskFromGitlabIssue($task, $executionID);

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

    /**
     * Test get project id by execution id.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getProjectIDTest($executionID)
    {
        $object = $this->objectModel->getProjectID($executionID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get story comments.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCommentsTest($storyID)
    {
        $object = $this->objectModel->getStoryComments($storyID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test compute parent task working hours.
     *
     * @param  int    $taskID
     * @access public
     * @return object
     */
    public function computeWorkingHoursTest($taskID)
    {
        $result = $this->objectModel->computeWorkingHours($taskID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getById($taskID);
            if(!empty($object) and $object->parent > 0) $parentObject = $this->objectModel->getById($object->parent);
            return isset($parentObject) ? $parentObject : $object;
        }
    }

    /**
     * Test compute begin and end for parent task.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function computeBeginAndEndTest($taskID)
    {
        $result = $this->objectModel->computeBeginAndEnd($taskID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getById($taskID);

            if(empty($object)) return 0;

            if($object->parent > 0) $object = $this->objectModel->getById($object->parent);


            $estStartedDiff = date_diff(date_create($object->estStarted), date_create(helper::now()));
            $deadlineDiff   = date_diff(date_create($object->deadline), date_create(helper::now()));
            return array('estStartedDiff' => $estStartedDiff->d, 'deadlineDiff' => $deadlineDiff->d);
        }
    }

    /**
     * 测试计算多人任务的工时。
     * Test compute hours for multiple task.
     *
     * @param  object       $oldTask
     * @param  object       $task
     * @param  array        $team
     * @param  bool         $autoStatus
     * @access public
     * @return array|object
     */
    public function computeMultipleHoursTest($oldTask, $task = null, $team = array(), $autoStatus = true): array|object
    {
        $result = $this->objectModel->computeMultipleHours($oldTask, $task, $team, $autoStatus);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getById($oldTask->id);
            return !empty($team) ? $result : $object;
        }
    }

    public function getParentTaskPairsTest($executionID, $append = '')
    {
        $objectList = $this->objectModel->getParentTaskPairs($executionID, $append);
        $objectList = count($objectList) == 1 ? array('name' => 0): $objectList;

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objectList;
        }
    }

    /**
     * Test process a task, judge it's status.
     *
     * @param  object $task
     * @access public
     * @return object
     */
    public function processTaskTest($task)
    {
        $task->deadline = $task->deadline == '-1day' ? date('Y-m-d',strtotime('-1 day')) : date('Y-m-d',strtotime('+1 day'));
        $object = $this->objectModel->processTask($task);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test batch process tasks.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function processTasksTest($executionID)
    {
        global $tester;
        $tasks = $tester->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq('0')->fetchAll('id');
        $parents = '0';
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents .= ",$task->parent";
        }
        $parents = $tester->dao->select('*')->from(TABLE_TASK)->where('`id`')->in($parents)->andWhere('deleted')->eq('0')->fetchAll('id');
        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }

        $object = $this->objectModel->processTasks($tasks);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test process data for report.
     *
     * @param  bool  $children
     * @param  array $field
     * @access public
     * @return array
     */
    public function processData4ReportTest($children, $field)
    {
        global $tester;
        $tasks = $tester->dao->select('*')->from(TABLE_TASK)->where('`execution`')->eq('101')->andWhere('deleted')->eq(0)->fetchAll('id');
        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
            $task->date = '0000-00-00';
        }

        $children = $children ? $tasks[601]->children + $tasks[602]->children + $tasks[603]->children : array();

        $object = $this->objectModel->processData4Report($tasks, $children, $field);

        $object['void'] = isset($object['']) ? $object[''] : 'void';

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            if($field == 'deadline')
            {
                $dateList = array(date('Y-m-d',strtotime('-8 day')), date('Y-m-d',strtotime('-15 day')));
                return array($object[$dateList[0]], $object[$dateList[1]]);
            }
            return count($object) == 0 ? array('void' => 'void') : $object;
        }
    }

    /**
     * Test get report data of tasks per execution.
     *
     * @param  int $executionID
     * @access public
     * @return array
     */
    public function getDataOfTasksPerExecutionTest($executionID)
    {
        global $tester;
        $tester->session->set('taskQueryCondition', "execution  = '{$executionID}' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0'");
        $object = $this->objectModel->getDataOfTasksPerExecution();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per module.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerModuleTest()
    {
        $object = $this->objectModel->getDataOfTasksPerModule();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per assignedto.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerAssignedToTest()
    {
        $object = $this->objectModel->getDataOfTasksPerAssignedTo();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per type.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerTypeTest()
    {
        $object = $this->objectModel->getDataOfTasksPerType();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per priority.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerPriTest()
    {
        $object = $this->objectModel->getDataOfTasksPerPri();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per deadline.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerDeadlineTest($dateID)
    {
        $dateList = array(date('Y-m-d',strtotime('+1 day')), date('Y-m-d',strtotime('+2 day')), date('Y-m-d',strtotime('+3 day')), date('Y-m-d',strtotime('+4 day')), date('Y-m-d',strtotime('-1 day')), date('Y-m-d',strtotime('-2 day')), date('Y-m-d',strtotime('-3 day')), date('Y-m-d',strtotime('-4 day')));
        $object = $this->objectModel->getDataOfTasksPerDeadline();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return array($dateID => $object[$dateList[$dateID]]);
        }
    }

    /**
     * Test get report data of tasks per estimate.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerEstimateTest()
    {
        $object = $this->objectModel->getDataOfTasksPerEstimate();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per left.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerLeftTest()
    {
        $object = $this->objectModel->getDataOfTasksPerLeft();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per consumed.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerConsumedTest()
    {
        $object = $this->objectModel->getDataOfTasksPerConsumed();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per finishedBy.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerFinishedByTest()
    {
        $object = $this->objectModel->getDataOfTasksPerFinishedBy();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per closed reason.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerClosedReasonTest()
    {
        $object = $this->objectModel->getDataOfTasksPerClosedReason();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of finished tasks per day.
     *
     * @access public
     * @return array
     */
    public function getDataOffinishedTasksPerDayTest()
    {
        $object = $this->objectModel->getDataOffinishedTasksPerDay();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get report data of tasks per status.
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerStatusTest()
    {
        $object = $this->objectModel->getDataOfTasksPerStatus();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test update parent status by taskID.
     *
     * @param  int   $taskID
     * @param  int   $parentID
     * @param  bool  $createAction
     * @access public
     * @return object
     */
    public function updateParentStatusTest($taskID, $parentID = 0, $createAction = true)
    {
        $object = $this->objectModel->updateParentStatus($taskID, $parentID, $createAction);
        if(!$object) $object = $this->objectModel->getByID($taskID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test judge an action is clickable or not.
     *
     * @param  object $task
     * @param  string $action
     * @access public
     * @return int
     */
    public function isClickableTest($task, $action)
    {
        $object = $this->objectModel->isClickable($task, $action);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object ? 1 : 2;
        }
    }

    /**
     * Test add task estimate.
     *
     * @param  object  $data
     * @access public
     * @return object
     */
    public function addTaskEstimateTest($data)
    {
        $data->date = date("Y-m-d");
        $this->objectModel->addTaskEstimate($data);

        global $tester;
        $objectID = $tester->dao->lastInsertID();
        $object   = $this->objectModel->getEstimateById($objectID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get toList and ccList.
     *
     * @param  int    $taskID
     * @param  bool   $skipMailto
     * @access public
     * @return array
     */
    public function getToAndCcListTest($taskID, $skipMailto = false)
    {
        $task = $this->objectModel->getByID($taskID);
        if(empty($task)) return 0;
        if($skipMailto) $task->mailto = '';

        $object = $this->objectModel->getToAndCcList($task);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            if(isset($object[0])) $object[2] = $object[0];
            if(isset($object[1]) and $object[1] == '') $object[1] = 0;
            return $object;
        }
    }

    /**
     * Test for get team by account.
     *
     * @param  array  $users
     * @param  string $account
     * @param  string $filter
     * @access public
     * @return string
     */
    public function getTeamByAccount($users, $account, $filter = array('filter' => 'done'))
    {
        $object = $this->objectModel->getTeamByAccount($users, $account, $filter);
        if(empty($object)) return '_';
        return $object->account . '_' . $object->status;
    }

    /**
     * Test get assignedTo  for multi task.
     *
     * @param  array  $users
     * @param  string $current
     * @access public
     * @return string
     */
    public function getAssignedTo4Multi($users, $task, $type = 'current')
    {
        $assignedTo = $this->objectModel->getAssignedTo4Multi($users, $task, $type);
        return empty($assignedTo) ? 'null' : $assignedTo;
    }

    /**
     * Test for can operate effort;
     *
     * @param  object  $task
     * @param  object  $effort
     * @access public
     * @return bool
     */
    public function canOperateEffort($task, $effort = null)
    {
        $result = $this->objectModel->canOperateEffort($task, $effort);
        return $result ? 1 : 0;
    }

    /**
     * Test get task's team member pairs.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getMemberPairsTest($taskID)
    {
        $task = $this->objectModel->getByID($taskID);
        if(empty($task)) return 0;

        $object = $this->objectModel->getMemberPairs($task);
        $object['count'] = count($object);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test check whether need update status of bug.
     *
     * @param  object $task
     * @access public
     * @return int
     */
    public function needUpdateBugStatusTest($task)
    {
        $object = $this->objectModel->needUpdateBugStatus($task);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object ? 1 : 2;
        }
    }

    /**
     * Get the users who finished the multiple task.
     *
     * @param  int          $taskID
     * @param  string|array $team
     * @access public
     * @return array
     */
    public function getFinishedUsersTest($taskID = 0, $team = array())
    {
        $object = $this->objectModel->getFinishedUsers($taskID, $team);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get the users who finished the multiple task.
     * @param  int    $executionID
     * @param  string $estStarted
     * @param  string $deadline
     *
     * @access public
     * @return array
     */
    public function checkEstStartedAndDeadlineTest($executionID, $estStarted, $deadline)
    {
        $object = $this->objectModel->checkEstStartedAndDeadline($executionID, $estStarted, $deadline);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试根据类型查询任务。
     * Test fetch tasks of a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  int          $count
     * @access public
     * @return object[]|int|bool
     */
    public function fetchExecutionTasksTest(int $executionID, int $productID = 0, array|string $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', int $count = 0): array|int|bool
    {
        $tasks = $this->objectModel->fetchExecutionTasks($executionID, $productID, $type, $modules, $orderBy);
        if(dao::isError())
        {
            $error = dao::getError();
            return dao::getError();
        }
        elseif($count == "1")
        {
            return count($tasks);
        }
        else
        {
            return $tasks;
        }
    }

    /**
     * 测试将任务的层级改为父子结构。
     * Test change the hierarchy of tasks to a parent-child structure.
     *
     * @param  array  $taskIdList
     * @access public
     * @return object[]
     */
    public function buildTaskTreeTest(array $taskIdList): array
    {
        $tasks = array();
        if(!empty($taskIdList)) $tasks = $this->objectModel->getByList($taskIdList);

        $parentIdList = array();
        foreach($tasks as $task)
        {
            if($task->parent <= 0 or isset($tasks[$task->parent]) or isset($parentIdList[$task->parent])) continue;
            $parentIdList[$task->parent] = $task->parent;
        }
        $parentTasks = $this->objectModel->getByList($parentIdList);

        return $this->objectModel->buildTaskTree($tasks, $parentTasks);
    }

    /**
     * Get the assignedTo for the multiply linear task.
     *
     * @param  int    $taskID
     * @param  string $type current|next
     * @access public
     * @return string
     */
    public function getAssignedTo4MultiTest($taskID, $type = 'current'): string
    {
        $task    = $this->objectModel->getByID($taskID);
        $members = empty($task->team) ? array() : $task->team;

        return $this->objectModel->getAssignedTo4Multi($members, $task, $type);
    }

    /**
     * 测试计算当前任务状态。
     * Test compute the status of the current task.
     *
     * @param  object $currentTask
     * @param  object $oldTask
     * @param  object $task
     * @param  bool   $condition  true|false
     * @param  bool   $hasEfforts true|false
     * @param  int    $teamCount
     * @access public
     * @return object|array
     */
    public function computeTaskStatusTest($currentTask, $oldTask, $task, $autoStatus, $hasEfforts, $members): object|array
    {
        $task = $this->objectModel->computeTaskStatus($currentTask, $oldTask, $task, $autoStatus, $hasEfforts, $members);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $task;
        }
    }

    /**
     * 测试根据条件移除创建任务的必填项。
     * Test remove required fields for creating tasks based on conditions.
     *
     * @param  object $task
     * @param  bool   $selectTestStory
     * @access public
     * @return string|array
     */
    public function removeCreateRequiredFieldsTest(object $task, bool $selectTestStory): string|array
    {
        global $tester;
        $tester->config->task->create->requiredFields = 'name,type,execution,story,estimate,estStarted,deadline,module';
        $this->objectModel->removeCreateRequiredFields($task, $selectTestStory);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $tester->config->task->create->requiredFields;
        }
    }

    /**
     * 测试创建一个任务。
     * Test create a task.
     *
     * @param  array  $param
     * @access public
     * @return object|array
     */
    public function doCreateObject($param = array()): object|array
    {
        $createFields = array(
            'module' => 0,
            'story' => 0,
            'name' => '',
            'type' => '',
            'assignedTo' => 'admin',
            'pri' => 3,
            'estimate' => '',
            'estStarted' => '2021-01-10',
            'deadline' => '2021-03-19',
            'desc' => '',
            'version' => '1'
        );

        $task = new stdclass();
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;

        foreach($param as $key => $value) $task->$key = $value;

        $objectID = $this->objectModel->doCreate($task);

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

    /**
     * 测试设置任务的附件。
     * Test set attachments for tasks.
     *
     * @param  array  $taskFiles
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function setTaskFilesTest(array $taskIdList, int $taskID): array
    {
        global $tester;

        $taskFiles = array();
        if($taskIdList)
        {
            $taskFiles = $tester->dao->select('*')->from(TABLE_FILE)->where('objectID')->in($taskIdList)->andWhere('objectType')->eq('task')->fetchAll('id');
            foreach($taskFiles as $fileID => $taskFile) unset($taskFiles[$fileID]->id);
        }
        if(empty($taskFiles) and $taskID)
        {
            $files = $tester->dao->select('*')->from(TABLE_FILE)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->fetchAll('id');
            foreach($files as $file)
            {
                $_FILES['files']['name'][]     = $file->title;
                $_FILES['files']['size'][]     = $file->size;
                $_FILES['files']['tmp_name'][] = $file->extension;
            }
            $_FILES['files']['error'] = 0;
        }

        return $this->objectModel->setTaskFiles($taskFiles, $taskID);
    }

    /**
     * Kanban data processing after batch create tasks.
     *
     * @param  int    $taskID
     * @param  int    $executionID
     * @param  int    $laneID
     * @param  int    $columnID
     * @param  string $vision
     * @access public
     * @return string
     */
    public function updateKanban4BatchCreateTest($taskID, $executionID, $laneID, $columnID, $vision = 'rnd')
    {
        global $tester;

        $tester->config->vision = $vision;

        $this->objectModel->updateKanban4BatchCreate($taskID, $executionID, $laneID, $columnID);
        $cards = $tester->dao->select('cards')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($executionID)
            ->andWhere('lane')->eq($laneID)
            ->andWhere('column')->eq($columnID)
            ->fetch('cards');

        return $cards;
    }

    /**
     * 测试创建任务后的其他数据处理。
     * Test other data processing after task creation.
     *
     * @param  int         $taskID
     * @param  array       $taskIdList
     * @param  int         $bugID
     * @param  int         $todoID
     * @param  array       $testTasks
     * @access public
     * @return object|bool
     */
    public function afterCreateTest($taskID = 0, $taskIdList = array(), $bugID = 0, $todoID = 0, $testTasks = array()): object|bool
    {
        global $tester;
        $_SERVER['HTTP_HOST'] = $tester->config->db->host;

        $task = $this->objectModel->getByID($taskID);
        if(!$task) return false;

        $result = $this->objectModel->afterCreate($task, $taskIdList, $bugID, $todoID, $testTasks);
        if(!$result) return false;

        if($bugID)
        {
            $object = $tester->dao->findById($bugID)->from(TABLE_BUG)->fetch();
        }
        elseif($todoID)
        {
            $object = $tester->dao->findById($todoID)->from(TABLE_TODO)->fetch();
        }
        elseif($task->story)
        {
            $object = $tester->dao->findById($task->story)->from(TABLE_STORY)->fetch();
        }
        else
        {
            $object = $tester->dao->findById($taskID)->from(TABLE_TASK)->fetch();
        }

        return $object;
    }

    /**
     * 测试管理多人任务团队。
     * Test manage multi task team members.
     *
     * @param  int        $taskID
     * @param  string     $taskStatus
     * @param  string     $mode
     * @param  array      $teamList
     * @param  array      $teamSourceList
     * @param  array      $teamEstimateList
     * @param  array|bool $teamConsumedList
     * @param  array|bool $teamLeftList

     * @access public
     * @return array
     */
    public function manageTaskTeamTest(int $taskID, string $taskStatus, string $mode, array $teamList, array $teamSourceList, array $teamEstimateList, array|bool $teamConsumedList, array|bool $teamLeftList): array
    {
        $task = new stdclass();
        $task->id     = $taskID;
        $task->status = $taskStatus;
        $teams = $this->objectModel->manageTaskTeam($mode, $task, $teamList, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $teams;
        }
    }

    /**
     * 测试管理多人任务团队成员。
     * Test manage multi task team member.
     *
     * @param  int        $taskID
     * @param  string     $taskStatus
     * @param  string     $mode
     * @param  int        $row
     * @param  string     $account
     * @param  string     $minStatus
     * @param  array      $undoneUsers
     * @param  array      $teamSourceList
     * @param  array      $teamEstimateList
     * @param  array|bool $teamConsumedList
     * @param  array|bool $teamLeftList
     * @param  bool       $inTeams
     * @access public
     * @return array
     */
    public function manageTaskTeamMemberTest(int $taskID, string $taskStatus, string $mode, int $row, string $account, string $minStatus, array $undoneUsers, array $teamSourceList, array $teamEstimateList, array|bool $teamConsumedList, array|bool $teamLeftList, bool $inTeams): array
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->exec();

        $task = new stdclass();
        $task->id     = $taskID;
        $task->status = $taskStatus;
        $minStatus = $this->objectModel->manageTaskTeamMember($mode, $task, $row, $account, $minStatus, $undoneUsers, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList, $inTeams);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $taskTeamMember = $tester->dao->select('task,account,estimate,consumed,`left`,transfer,status')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->andWhere('account')->eq($account)->fetch();
            $taskTeamMember = json_decode(json_encode($taskTeamMember), true);
            return array('minStatus' => $minStatus, 'taskTeamMember' => !empty($taskTeamMember) ? implode('|', $taskTeamMember) : '0');
        }
    }

    /**
     * 测试创建关联需求的测试类型的子任务。
     * Test create a subtask for the test type story with the story.
     *
     * @param  int          $taskID
     * @param  array        $testTasks
     * @access public
     * @return array|object
     */
    public function createTestChildTasksTest($taskID = 0, $testTasks = array()): array|object
    {
        global $tester;
        $_SERVER['HTTP_HOST'] = $tester->config->db->host;

        $this->objectModel->createTestChildTasks($taskID, $testTasks);
        if(dao::isError()) return dao::getError();

        $lastTaskID = $tester->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('Opened')->orderBy('`date` desc')->fetch('objectID');
        return $this->objectModel->getByID($lastTaskID);
    }

    /**
     * 拆分任务后更新其他数据。
     * Process other data after split task.
     *
     * @param  int    $oldParentTaskID
     * @param  string $childTasks
     * @param  string $testObject children|parent|parentAction
     * @access public
     * @return object|string
     */
    public function afterSplitTaskTest(int $oldParentTaskID = 0, string $childTasks = '', string $testObject = 'parent'): object|string
    {
        global $tester;
        $_SERVER['HTTP_HOST'] = $tester->config->db->host;
        $oldParentTask        = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldParentTaskID)->fetch();

        $this->objectModel->afterSplitTask($oldParentTask, $childTasks);

        $tasks['children']       = $tester->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($oldParentTask->id)->fetchPairs('id');
        $tasks['parent']         = $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldParentTask->id)->fetch();
        $tasks['parentAction']   = $tester->dao->select('id as actionID')->from(TABLE_ACTION)->where('objectID')->eq($oldParentTask->id)->andWhere('objectType')->eq('task')->fetch();
        $tasks['parentEstStarted']     = $tasks['parent']->estStarted;
        $tasks['parentDeadline']       = $tasks['parent']->deadline;

        return $tasks[$testObject];
    }

    /**
     * 更新看板中的任务泳道数据。
     * Update the task lane data in Kanban.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return object|bool
     */
    public function updateKanbanDataTest(int $executionID, int $taskID, int $laneID, int $columnID): object|bool
    {
        global $tester;
        $execution = $task = new stdclass();
        if($executionID) $execution = $tester->loadModel('execution')->getByID($executionID);
        if($taskID) $task = $this->objectModel->getByID($taskID);
        $result = $this->objectModel->updateKanbanData($execution, $task, $laneID, $columnID);
        if(!$result) return false;

        if($laneID) $object = $tester->loadModel('kanban')->getLaneByID($laneID);
        if($columnID) $object = $tester->loadModel('kanban')->getColumnByID($columnID);
        return isset($object) ? $object : false;
    }

    /**
     * 测试检查执行是否有需求列表。
     * Test check whether execution has story list.
     *
     * @param  int    $executionID
     * @access public
     * @return int|array
     */
    public function isNoStoryExecutionTest(int $executionID): int|array
    {
        global $tester;
        $execution = $tester->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch();
        $result    = $this->objectModel->isNoStoryExecution($execution);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return !empty($result) ? 1 : 0;
        }
    }
}
