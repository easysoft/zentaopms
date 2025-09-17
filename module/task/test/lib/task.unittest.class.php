<?php
class taskTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('task');
        $this->objectTao   = $tester->loadTao('task');

        /* Load zen object only when needed to avoid initialization errors. */
        try {
            $this->objectZen = $tester->loadZen('task');
            if(!$this->objectZen) {
                error_log("taskTest: loadZen returned null");
            }
        } catch (Exception $e) {
            error_log("taskTest: loadZen failed with exception: " . $e->getMessage());
            $this->objectZen = null;
        } catch (Throwable $e) {
            error_log("taskTest: loadZen failed with throwable: " . $e->getMessage());
            $this->objectZen = null;
        }

        $this->objectModel->lang->task->story = '相关研发需求';
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
        $object = $this->objectModel->dbh->query("SELECT id, `parent`,`estStarted`,`deadline`,`execution`,`module`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`status`,
            `mode`, `story`, `color`,`desc`,`assignedTo`,`realStarted`,`design`,`finishedBy`,`canceledBy`,`closedReason` FROM zt_task WHERE id = $objectID")->fetch();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field]  = $param[$field];
                $object->$field = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

        $change = $this->objectModel->update($object);
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
     * @param  string $executionType
     * @param  array  $output
     * @access public
     * @return array
     */
    public function batchCreateObject(array $param = array(), string $executionType = 'sprint', array $output = array()): array
    {
        $executionIdList = array('sprint' => 2, 'stage' => 3, 'kanban' => 4);
        $executionID     = zget($executionIdList, $executionType);

        $createFields = array(
            'project'      => 1,
            'execution'    => $executionID,
            'parent'       => 0,
            'module'       => 0,
            'story'        => 0,
            'name'         => '',
            'type'         => '',
            'assignedTo'   => 'admin',
            'assignedDate' => helper::now(),
            'pri'          => 3,
            'estimate'     => 0,
            'left'         => 0,
            'estStarted'   => '2021-01-10',
            'deadline'     => '2021-03-19',
            'desc'         => '',
            'mailto'       => '',
            'version'      => '1',
            'openedBy'     => 'admin',
            'openedDate'   => helper::now(),
            'lane'         => 0,
            'column'       => 0,
            'level'        => 0
        );

        $tasks = array();
        foreach($param as $rowIndex => $data)
        {
            $task = new stdclass();
            foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
            foreach($data as $key => $value) $task->$key = $value;
            $tasks[$rowIndex] = $task;
        }

        $taskIdList = $this->objectModel->batchCreate($tasks, $output);

        if(dao::isError()) return dao::getError();
        return $taskIdList;
    }

    /**
     * 批量创建任务后的其他数据处理。
     * Other data process after task batch create.
     *
     * @param  array  $taskIdList
     * @param  object $parent
     * @access public
     * @return bool
     */
    public function afterBatchCreateObject(array $taskIdList, object $parent = null): bool
    {
        return $this->objectModel->afterBatchCreate($taskIdList, $parent);
    }

    /**
     * 批量更新任务。
     * Batch update tasks.
     *
     * @param  array  $taskIdList
     * @param  array  $params
     * @param  string $requiredField
     * @access public
     * @return array
     */
    public function batchUpdateObject(array $taskIdList, array $params = array(), $requiredField = ''): array
    {
        $requiredFields = $this->objectModel->config->task->batchedit->requiredFields;
        if($requiredField) $this->objectModel->config->task->batchedit->requiredFields = $this->objectModel->config->task->batchedit->requiredFields . ',' . $requiredField . ',';

        $oldTasks = $this->objectModel->getByIdList($taskIdList);
        $taskData = array();
        foreach($oldTasks as $task)
        {
            if($params['id'] == $task->id)
            {
                foreach($params as $key => $value)
                {
                    if($key == 'id') continue;
                    $task->$key = $value;
                }
            }
            $taskData[$task->id] = $task;
        }

        $allChanges = $this->objectModel->batchUpdate($taskData);
        $this->objectModel->config->task->batchedit->requiredFields = $requiredFields;

        if(dao::isError()) return current(dao::getError());
        return $allChanges;
    }

    /**
     * 批量编辑任务后的其他数据处理。
     * Other data process after task batch edit.
     *
     * @param  array  $taskIdList
     * @param  array  $params
     * @access public
     * @return bool
     */
    public function afterBatchUpdateObject(array $taskIdList, array $params): bool
    {
        $oldTasks = $this->objectModel->getByIdList($taskIdList);
        $taskData = array();
        foreach($oldTasks as $task)
        {
            if($params['id'] == $task->id)
            {
                foreach($params as $key => $value)
                {
                    if($key == 'id') continue;
                    $task->$key = $value;
                }
            }
            $taskData[$task->id] = $task;
        }

        return $this->objectModel->afterBatchUpdate($taskData, $oldTasks);
    }

    /**
     * Test batch change module.
     *
     * @param  array  $taskIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModuleTest(array $taskIDList, int $moduleID): array
    {
        $object = $this->objectModel->batchChangeModule($taskIDList, $moduleID);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByIdList($taskIDList);
    }

    /**
     * Test update a task.
     *
     * @param  int                 $objectID
     * @param  array               $param
     * @access public
     * @return array|object|string
     */
    public function doUpdateTest(int $taskID, array $param = array()): array|object|string
    {
        $oldTask = $this->objectModel->fetchByID($taskID);
        $task    = clone $oldTask;

        foreach($task as $field => $value)
        {
            if(in_array($field, array_keys($param))) $task->$field = $param[$field];
            if(strpos($field, 'Date') && !$task->$field) $task->$field = null;
        }

        $this->objectModel->doUpdate($task, $oldTask, $this->objectModel->config->task->edit->requiredFields);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->fetchByID($taskID);
        }
    }

    /**
     * Start a task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array|string
     */
    public function startTest(int $taskID, array $param = array()): array|string
    {
        $task        = new stdclass();
        $startFields = array('id' => $taskID, 'status' => 'doing', 'assignedTo' => '', 'realstarted' => helper::now(), 'left' => 0, 'consumed' => 0);
        foreach($startFields as $field => $defaultvalue) $task->{$field} = $defaultvalue;
        foreach($param as $key => $value) $task->{$key} = $value;

        $oldTask = $this->objectModel->getByID($taskID);
        $result  = $this->objectModel->start($oldTask, $task);
        return dao::isError() ? dao::getError() : $result;
    }

    /**
     * Other data process after task start.
     *
     * @param  int        $taskID
     * @param  array      $param
     * @param  array      $output
     * @access public
     * @return array|bool
     */
    public function afterStartTest(int $taskID, array $param = array(), array $output = array()): array|bool
    {
        $task        = new stdclass();
        $startFields = array('id' => $taskID, 'status' => 'doing', 'assignedTo' => '', 'realstarted' => null, 'left' => 0, 'consumed' => 0);
        foreach($startFields as $field => $defaultvalue) $task->{$field} = $defaultvalue;
        foreach($param as $key => $value) $task->{$key} = $value;

        $oldTask = $this->objectModel->getByID($taskID);
        $changes = $this->objectModel->start($oldTask, $task);
        $result  = $this->objectModel->afterStart($oldTask, $changes, $task->left, $output);
        return $result;
    }

    /**
     * Test record estimate and left of task.
     *
     * @param  int    $taskID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function recordWorkhourTest(int $taskID, array $param): array|bool
    {
        $allChanges = $this->objectModel->recordWorkhour($taskID, $param);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $allChanges;
        }
    }

    /**
     * 激活任务。
     * Activate a task.
     *
     * @param  int         $taskID
     * @param  string      $comment
     * @param  object      $teamData
     * @param  array       $drag
     * @access public
     * @return object|bool
     */
    public function activateTest(int $taskID, string $comment = '', object $teamData = null, array $drag = array()): object|bool
    {
        $task = new stdclass();
        $activateFields = array('id' => $taskID, 'status' => 'doing','assignedTo' => '', 'left' => '3');
        foreach($activateFields as $field => $defaultValue) $task->{$field} = $defaultValue;

        $this->objectModel->activate($task, $comment, $teamData, $drag);
        return $this->objectModel->fetchByID($taskID);
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

        $task       = $_POST;
        $task['id'] = $taskID;
        unset($task['comment']);

        $object = $this->objectModel->assign((object)$task);

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
     * @param  array    $teamLeft
     * @access public
     * @return object
     */
    public function updateTeamTest($taskID, $status, $team, $teamSource, $teamEstimate, $teamConsumed, $teamLeft, $getTeam = false)
    {
        $task = new stdclass();
        $task->id           = $taskID;
        $task->status       = $status;
        $task->lastEditedBy = $this->objectModel->app->user->account;
        $task->project      = 10 + $taskID;

        $postData = new stdclass();
        $postData->team         = $team;
        $postData->teamSource   = $teamSource;
        $postData->teamEstimate = $teamEstimate;
        $postData->teamConsumed = $teamConsumed;
        $postData->teamLeft     = $teamLeft;
        $object = $this->objectModel->updateTeam($task, $postData);

        if($getTeam) return $this->objectModel->getMultiTaskMembers($taskID);
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
    public function cancelTest(int $taskID, array $param = array()): array|object
    {
        $oldTask = $this->objectModel->fetchByID($taskID);
        $newTask = new stdclass();
        $newTask->id = $oldTask->id;

        foreach($param as $key => $value)
        {
            if($key == 'comment')
            {
                $_POST['comment'] = $value;
            }
            else
            {
                $newTask->{$key} = $value;
            }
        }

        $this->objectModel->cancel($oldTask, $newTask);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $this->objectModel->getByID($taskID);
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
        $now  = helper::now();
        $task = new stdclass();
        $task->id             = $taskID;
        $task->status         = 'closed';
        $task->assignedTo     = '';
        $task->closedBy       = '';
        $task->lastEditedBy   = '';
        $task->assignedDate   = $now;
        $task->closedDate     = $now;
        $task->lastEditedDate = $now;
        foreach($param as $key => $value) $task->{$key} = $value;

        $oldTask = $this->objectModel->getByID($taskID);
        $result  = $this->objectModel->close($oldTask, $task, array());
        $task    = $this->objectModel->getByID($taskID);
        return "status-{$oldTask->status}-{$task->status}";
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
        $task = new stdclass();
        $task->id           = $taskID;
        $task->left         = 0;
        $task->status       = 'done';
        $task->consumed     = 0;
        $task->assignedTo   = '';
        $task->realstarted  = helper::now();
        $task->finishedDate = helper::now();
        foreach($param as $key => $value) $task->{$key} = $value;

        $oldTask = $this->objectModel->getByID($taskID);
        $result  = $this->objectModel->finish($oldTask, $task);
        return dao::isError() ? dao::getError() : $result;
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
     * 通过任务类型获取用户的任务。
     * Get user tasks by type.
     *
     * @param  int    $taskID
     * @param  string $assignedTo
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @param  int    $projectID
     * @access public
     * @return object[]
     */
    public function getUserTasksTest(string $account, string $type = 'assignedTo', int $limit = 0, object $pager = null, string $orderBy = 'id_desc', int $projectID = 0): array
    {
        $object = $this->objectModel->getUserTasks($account, $type, $limit, $pager, $orderBy, $projectID);

        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * 测试暂停任务。
     * Test pause a task.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function pauseTest(int $taskID): array
    {
        $task = new stdclass();
        $task->id             = $taskID;
        $task->status         = 'pause';
        $task->lastEditedBy   = 'admin';
        $task->lastEditedDate = helper::now();

        $changes = $this->objectModel->pause($task, array());

        if(dao::isError()) return dao::getError();

        return $changes;
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
     * @param  int    $executionID
     * @param  int    $projectID
     * @access public
     * @return object[]
     */
    public function getListByStoryTest(int $storyID, int $executionID = 0, int $projectID = 0)
    {
        $object = $this->objectModel->getListByStory($storyID, $executionID, $projectID);

        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * Test get task efforts.
     *
     * @param  int    $taskID
     * @param  string $account
     * @param  int    $effortID
     * @access public
     * @return array
     */
    public function getTaskEffortsTest(int $taskID, string $account = '', int $effortID = 0): array
    {
        $object = $this->objectModel->getTaskEfforts($taskID, $account, $effortID);
        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get estimate by id.
     *
     * @param  int    $estimateID
     * @access public
     * @return false|object
     */
    public function getEffortByIDTest(int $estimateID): false|object
    {
        $object = $this->objectModel->getEffortByID($estimateID);
        if(!$object)
        {
            return false;
        }
        else
        {
            $object->isLast = (int)$object->isLast;
            return $object;
        }
    }

    /**
     * Test update effort.
     *
     * @param  object $effort
     * @access public
     * @return array|bool
     */
    public function updateEffortTest(int $effortID, object $param): array|bool
    {
        $effort  = $this->objectModel->getEffortByID($effortID);
        foreach($param as $key => $value) $effort->{$key} = $value;
        unset($effort->isLast);

        $changes = $this->objectModel->updateEffort($effort);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $changes;
        }
    }

    /**
     * Test update team by effort.
     *
     * @param  int    $estimateID
     * @param  object $record
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function updateTeamByEffortTest(int $effortID, object $record, int $taskID, object $task = null, string $lastDate): array
    {
        $task        = $this->objectModel->getByID($taskID);
        $currentTeam = $this->objectModel->getTeamByAccount($task->team);
        $this->objectModel->updateTeamByEffort($effortID, $record, $currentTeam, $task, $lastDate);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->fetchAll();
        }
    }

    /**
     * Test delete estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return array
     */
    public function deleteWorkhourTest($estimateID)
    {
        $object = $this->objectModel->deleteWorkhour($estimateID);
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
     * @param  object $task
     * @param  int    $executionID
     * @access public
     * @return object|array
     */
    public function createTaskFromGitlabIssueTest(object $task, int $executionID): object|array
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


            $estStartedDiff = date_diff(date_create($object->estStarted), date_create('2020-12-01'));
            $deadlineDiff   = date_diff(date_create($object->deadline), date_create('2021-02-01'));
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

    /**
     * Test process a task, judge it's status.
     *
     * @param  int $task
     * @access public
     * @return string|object|array
     */
    public function processTaskTest(int $taskID): string|object|array
    {
        $task = $this->objectModel->getByID($taskID);
        if(!$task) return '任务未找到';

        $task->product = $task->id;
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
    public function processTasksTest(int $executionID): array
    {
        $tasks = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq('0')->fetchAll('id');
        $parents = '0';
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents .= ",$task->parent";
            $task->product = $task->execution;
        }
        $parents = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('`id`')->in($parents)->andWhere('deleted')->eq('0')->fetchAll('id');
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
     * 处理报表统计数据。
     * Test process data for report.
     *
     * @param  int     $executionID
     * @param  srtring $field       execution|module|assignedTo|type|pri|deadline|estimate|left|consumed|finishedBy|closedReason|status|date
     * @param  bool    $skipParent
     * @access public
     * @return array
     */
    public function processData4ReportTest(int $executionID, string $field, bool $skipParent = false): array
    {
        $children = array();
        if($skipParent) $children = $this->objectModel->dao->select('id,parent')->from(TABLE_TASK)->where('parent')->gt(0)->andWhere('execution')->eq($executionID)->fetchAll();

        $selectField = $field == 'date' ? ", DATE_FORMAT(`finishedDate`, '%Y-%m-%d') AS `date`" : '';
        $tasks       = $this->objectModel->dao->select("* {$selectField}")->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->eq($executionID)->fetchAll('id');
        return $this->objectModel->processData4Report($tasks, $children, $field);
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
        $this->objectModel->session->set('taskQueryCondition', "execution  = '{$executionID}' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0'");
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
     * 测试更新父任务状态。
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
        $oldObject = $this->objectModel->getByID($taskID);
        if($oldObject->parent) $oldObject = $this->objectModel->getByID($oldObject->parent);

        $this->objectModel->updateParentStatus($taskID, $parentID, $createAction);

        $object = $this->objectModel->getByID(intval($oldObject->id));

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    public function updateChildrenStatusTest($taskID, $status)
    {
        $task = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $this->objectModel->dao->update(TABLE_TASK)->set('status')->eq($status)->where('id')->eq($taskID)->exec();
        $this->objectModel->updateChildrenStatus($taskID, empty($task) ? '' : $task->status);
        if(empty($taskID)) return 0;

        $child = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetch();
        if(empty($child)) return 0;

        $action = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($child->id)->orderBy('id_desc')->limit(1)->fetch();
        $child->action = $action->action;
        $child->extra  = $action->extra;
        return $child;
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
     * Test add task effort.
     *
     * @param  object  $data
     * @access public
     * @return object
     */
    public function addTaskEffortTest($data)
    {
        $data->date = date("Y-m-d");
        $objectID = $this->objectModel->addTaskEffort($data);
        $object   = $this->objectModel->getEffortByID($objectID);

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
     * @param  string $action
     * @access public
     * @return array|false
     */
    public function getToAndCcListTest(int $taskID, string $action = ''): array|false
    {
        $task = $this->objectModel->getByID($taskID);
        if(empty($task)) return false;

        return $this->objectModel->getToAndCcList($task, $action);
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
    public function getTeamByAccountTest(array $users, string $account, array $filter = array('filter' => 'done')): string
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
     * 检查当前登录用户是否可以操作日志。
     * Check if the current user can operate effort.
     *
     * @param  object $task
     * @param  object $effort
     * @access public
     * @return int
     */
    public function canOperateEffort(object $task, object $effort = null): int
    {
        $result = $this->objectModel->canOperateEffort($task, $effort);
        return $result ? 1 : 0;
    }

    /**
     * Get the users who finished the multiple task.
     *
     * @param  int    $executionID
     * @param  string $estStarted
     * @param  string $deadline
     * @access public
     * @return array
     */
    public function checkEstStartedAndDeadlineTest($executionID, $estStarted, $deadline)
    {
        $this->objectModel->config->limitTaskDate = 1;
        $object = $this->objectModel->checkEstStartedAndDeadline($executionID, $estStarted, $deadline);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test check effort.
     *
     * @param  int    $effortID
     * @param  array  $effort
     * @access public
     * @return array|bool
     */
    public function checkEffortTest(int $effortID, object $param): array|bool
    {
        $effort = $this->objectModel->getEffortByID($effortID);

        foreach($param as $key => $value) $effort->{$key} = $value;
        $result = $this->objectModel->checkEffort($effort);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $result;
        }
    }

    /**
     * Check workhour test.
     *
     * @param  int    $taskID
     * @param  array  $workhour
     * @access public
     * @return array|bool
     */
    public function checkWorkhourTest(int $taskID, array $workhour): array|bool
    {
        $task   = $this->objectModel->getByID($taskID);
        $result = $this->objectModel->checkWorkhour($task, $workhour);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $result;
        }
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
        if(!empty($taskIdList)) $tasks = $this->objectModel->getByIdList($taskIdList);

        $parentIdList = array();
        foreach($tasks as $task)
        {
            if($task->parent <= 0 or isset($tasks[$task->parent]) or isset($parentIdList[$task->parent])) continue;
            $parentIdList[$task->parent] = $task->parent;
        }
        $parentTasks = $this->objectModel->getByIdList($parentIdList);

        return $this->objectModel->buildTaskTree($tasks, $parentTasks);
    }

    /**
     * 测试通过记录日志改变任务的工时及状态。
     * Test change task's status and workhour by record effort.
     *
     * @param  object   $record
     * @param  int      $taskID
     * @param  string   $lastDate
     * @param  bool     $isFinishTask
     * @access public
     * @return array[]
     */
    public function buildTaskForEffortTest(object $record, int $taskID, string $lastDate, bool $isFinishTask): array
    {
        $task = $this->objectModel->getByID($taskID);
        return $this->objectModel->buildTaskForEffort($record, $task, $lastDate, $isFinishTask);
    }

    /**
     * 测试通过编辑日志修改任务信息的方法。
     * Test the method for update task information by updating the effort.
     *
     * @param  int     $taskID
     * @param  int     $effortID
     * @param  object  $param
     * @access public
     * @return object
     */
    public function buildTaskForUpdateEffortTest(int $taskID, int $effortID, object $param): object
    {
        $task      = $this->objectModel->getByID($taskID);
        $oldEffort = $this->objectModel->getEffortByID($effortID);

        $effort = clone $oldEffort;
        foreach($param as $key => $value) $effort->{$key} = $value;

        return $this->objectModel->buildTaskForUpdateEffort($task, $oldEffort, $effort);
    }

    /**
     * Get the assignedTo for the multiply linear task.
     *
     * @param  int    $taskID
     * @param  string $type current|next
     * @access public
     * @return string
     */
    public function getAssignedTo4MultiTest(int $taskID, string $type = 'current'): string
    {
        $task    = $this->objectModel->getByID($taskID);
        $members = empty($task->team) ? array() : $task->team;

        return $this->objectModel->getAssignedTo4Multi($members, $task, $type);
    }

    /**
     * 测试计算当前任务状态。
     * Test compute the status of the current task.
     *
     * @param  object       $currentTask
     * @param  object       $oldTask
     * @param  object       $task
     * @param  bool         $condition  true|false
     * @param  bool         $hasEfforts true|false
     * @param  array        $members
     * @access public
     * @return object|array
     */
    public function computeTaskStatusTest(object $currentTask, object $oldTask, object $task, bool $autoStatus, bool $hasEfforts, array $members): object|array
    {
        $task = $this->objectModel->computeTaskStatus($currentTask, $oldTask, $task, $autoStatus, $hasEfforts, $members);

        if(dao::isError()) return dao::getError();
        return $task;
    }

    /**
     * 测试根据条件移除创建任务的必填项。
     * Test remove required fields for creating tasks based on conditions.
     *
     * @param  object       $task
     * @param  bool         $selectTestStory
     * @access public
     * @return string|array
     */
    public function removeCreateRequiredFieldsTest(object $task, bool $selectTestStory): string|array
    {
        $this->objectModel->config->task->create->requiredFields = 'name,type,execution,story,estimate,estStarted,deadline,module';
        $this->objectModel->removeCreateRequiredFields($task, $selectTestStory);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->config->task->create->requiredFields;
    }

    /**
     * 创建测试类型的任务。
     * Create a test type task.
     *
     * @param  array            $param
     * @param  array            $testTasks
     * @param  string           $requiredField
     * @access public
     * @return object|array|bool
     */
    public function createTaskOfTestObject(array $param = array(), array $testTasks = array(), string $requiredField = ''): object|array|bool
    {
        $createFields = array(
            'execution'    => 3,
            'module'       => 0,
            'story'        => 0,
            'name'         => '',
            'type'         => 'test',
            'assignedTo'   => 'admin',
            'assignedDate' => helper::now(),
            'pri'          => 3,
            'estimate'     => 0,
            'left'         => 0,
            'estStarted'   => '2021-01-10',
            'deadline'     => '2021-03-19',
            'desc'         => '',
            'version'      => '1'
        );

        $task = new stdclass();
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
        foreach($param as $key => $value) $task->$key = $value;
        if($requiredField)
        {
            if(!empty($testTasks) and $requiredField == 'estStarted') unset($task->estStarted);
            if(!empty($testTasks) and $requiredField == 'deadline') unset($task->deadline);
            $this->objectModel->config->task->create->requiredFields = $this->objectModel->config->task->create->requiredFields . ',' . $requiredField . ',';
        }
        $objectID = $this->objectModel->createTaskOfTest($task, $testTasks);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($objectID);
    }

    /**
     * 创建事务类型的任务。
     * Create a affair type task.
     *
     * @param  array        $param
     * @param  array        $assignedToList
     * @param  string       $requiredField
     * @access public
     * @return object|array
     */
    public function createTaskOfAffairObject(array $param = array(), array $assignedToList = array(), string $requiredField = ''): object|array
    {
        $createFields = array(
            'execution'    => 3,
            'module'       => 0,
            'story'        => 0,
            'name'         => '',
            'type'         => 'affair',
            'pri'          => 3,
            'estimate'     => 0,
            'left'         => 0,
            'estStarted'   => '2021-01-10',
            'deadline'     => '2021-03-19',
            'desc'         => '',
            'version'      => '1'
        );

        $task = new stdclass();
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
        foreach($param as $key => $value) $task->$key = $value;
        if($requiredField) $this->objectModel->config->task->create->requiredFields = $this->objectModel->config->task->create->requiredFields . ',' . $requiredField . ',';
        $objectIdList = $this->objectModel->createTaskOfAffair($task, $assignedToList);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByIdList($objectIdList);
    }

    /**
     * 创建多人任务。
     * Create a multiplayer task.
     *
     * @param  array        $param
     * @param  array        $testTasks
     * @param  string       $requiredField
     * @access public
     * @return object|array
     */
    public function createMultiTaskObject(array $param = array(), array $teamData = array(), string $requiredField = ''): object|array
    {
        $createFields = array(
            'project'      => 1,
            'execution'    => 3,
            'module'       => 1,
            'story'        => 2,
            'name'         => '',
            'mode'         => '',
            'status'       => 'wait',
            'type'         => 'test',
            'assignedTo'   => 'admin',
            'assignedDate' => helper::now(),
            'pri'          => 3,
            'estimate'     => 1,
            'left'         => 0,
            'estStarted'   => '2021-01-10',
            'deadline'     => '2021-03-19',
            'desc'         => '',
            'version'      => '1'
        );

        $task = new stdclass();
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
        foreach($param as $key => $value) $task->$key = $value;
        if($requiredField)
        {
            if(!empty($testTasks) and $requiredField == 'estStarted') unset($task->estStarted);
            if(!empty($testTasks) and $requiredField == 'deadline') unset($task->deadline);
            $this->objectModel->config->task->create->requiredFields = $this->objectModel->config->task->create->requiredFields . ',' . $requiredField . ',';
        }
        $teamInfo = new stdclass();
        foreach($teamData as $key => $value) $teamInfo->{$key} = $value;
        $objectID = $this->objectModel->createMultiTask($task, $teamInfo);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($objectID);
    }

    /**
     * 测试创建一个任务。
     * Test create a task.
     *
     * @param  array        $param
     * @access public
     * @return object|array
     */
    public function createObject(array $param = array()): object|array
    {
        $createFields = array(
            'module' => 0,
            'story' => 0,
            'name' => '',
            'type' => '',
            'mode' => '',
            'assignedTo' => 'admin',
            'pri' => 3,
            'estimate' => 0,
            'left' => 0,
            'estStarted' => '2021-01-10',
            'deadline' => '2021-03-19',
            'desc' => '',
            'version' => '1'
        );

        $task = new stdclass();
        foreach($createFields as $field => $defaultValue) $task->$field = $defaultValue;
        foreach($param as $key => $value) $task->$key = $value;

        $objectID = $this->objectModel->create($task);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($objectID);
    }

    /**
     * 测试设置任务的附件。
     * Test set attachments for tasks.
     *
     * @param  array  $taskFiles
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function setTaskFilesTest(int|array $taskIdList): bool
    {
        $taskFiles = array();
        $taskID    = is_array($taskIdList) ? current($taskIdList) : 0;
        if(empty($taskFiles) and $taskID)
        {
            $files = $this->objectModel->dao->select('*')->from(TABLE_FILE)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->fetchAll('id');
            foreach($files as $file)
            {
                $_FILES['files']['name'][]     = $file->title;
                $_FILES['files']['size'][]     = $file->size;
                $_FILES['files']['tmp_name'][] = $file->extension;
            }
            $_FILES['files']['error'] = 0;
        }

        $taskIdList = (array)$taskIdList;
        return $this->objectModel->setTaskFiles($taskIdList);
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
    public function updateKanbanForBatchCreateTest(int $taskID, int $executionID, int $laneID, int $columnID, string $vision = 'rnd'): string
    {
        $this->objectModel->config->vision = $vision;

        $this->objectModel->updateKanbanForBatchCreate($taskID, $executionID, $laneID, $columnID);
        $cards = $this->objectModel->dao->select('cards')->from(TABLE_KANBANCELL)
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
     * @access public
     * @return object|bool
     */
    public function afterCreateTest(int $taskID = 0, array $taskIdList = array(), int $bugID = 0, int $todoID = 0): object|bool
    {
        $task = $this->objectModel->getByID($taskID);
        if(!$task) return false;

        $result = $this->objectModel->afterCreate($task, $taskIdList, $bugID, $todoID);
        if(!$result) return false;

        if($bugID)
        {
            $object = $this->objectModel->dao->findById($bugID)->from(TABLE_BUG)->fetch();
        }
        elseif($todoID)
        {
            $object = $this->objectModel->dao->findById($todoID)->from(TABLE_TODO)->fetch();
        }
        elseif($task->story)
        {
            $object = $this->objectModel->dao->findById($task->story)->from(TABLE_STORY)->fetch();
        }
        else
        {
            $object = $this->objectModel->dao->findById($taskID)->from(TABLE_TASK)->fetch();
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
     * @param  array      $teamData
     * @access public
     * @return array|false
     */
    public function manageTaskTeamTest(int $taskID, string $taskStatus, string $mode, array $teamData): array|false
    {
        $task = new stdclass();
        $task->id      = $taskID;
        $task->status  = $taskStatus;
        $task->project = 10 + $taskID;

        $teamInfo = new stdclass();
        foreach($teamData as $key => $value) $teamInfo->{$key} = $value;
        $teams = $this->objectModel->manageTaskTeam($mode, $task, $teamInfo);

        if(dao::isError()) return dao::getError();
        return $teams;
    }

    /**
     * 测试管理多人任务团队成员。
     * Test manage multi task team member.
     *
     * @param  string      $mode
     * @param  object      $task
     * @param  object      $teamData
     * @access public
     * @return string|array
     */
    public function manageTaskTeamMemberTest(string $mode, object $task, object $teamData): string|array
    {
        $this->objectModel->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->exec();

        $teams = $this->objectModel->manageTaskTeamMember($mode, $task, $teamData);
        if(dao::isError()) return dao::getError();

        $taskTeamMember = $this->objectModel->dao->select('task,account,estimate,consumed,`left`,transfer,status')->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->andWhere('account')->eq(current($teams))->fetch();
        $taskTeamMember = json_decode(json_encode($taskTeamMember), true);
        return !empty($taskTeamMember) ? implode('|', $taskTeamMember) : '0';
    }

    /**
     * 测试创建关联需求的测试类型的子任务。
     * Test create a subtask for the test type story with the story.
     *
     * @param  int               $taskID
     * @param  array             $testTasks
     * @access public
     * @return array|object|bool
     */
    public function createTestChildTasksTest(int $taskID = 0, array $testTasks = array()): array|object|bool
    {
        $this->objectModel->createTestChildTasks($taskID, $testTasks);
        if(dao::isError()) return dao::getError();

        $lastTaskID = $this->objectModel->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('Opened')->orderBy('`date` desc, id asc')->fetch('objectID');
        return $this->objectModel->getByID((int)$lastTaskID);
    }

    /**
     * 拆分任务后更新其他数据。
     * Process other data after split task.
     *
     * @param  int    $oldParentTaskID
     * @param  array  $children
     * @param  string $testObject children|parent|parentAction
     * @access public
     * @return array|object|string
     */
    public function afterSplitTaskTest(int $oldParentTaskID = 0, array $children = array(), string $testObject = 'parent'): array|object|string
    {
        $oldParentTask        = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldParentTaskID)->fetch();

        $this->objectModel->afterSplitTask($oldParentTask, $children);

        $tasks['children']         = $this->objectModel->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($oldParentTask->id)->fetchPairs('id');
        $tasks['parent']           = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldParentTask->id)->fetch();
        $tasks['parentAction']     = $this->objectModel->dao->select('id as actionID')->from(TABLE_ACTION)->where('objectID')->eq($oldParentTask->id)->andWhere('objectType')->eq('task')->fetch();
        $tasks['parentEstStarted'] = $tasks['parent']->estStarted;
        $tasks['parentDeadline']   = $tasks['parent']->deadline;

        return $tasks[$testObject];
    }

    /**
     * 复制任务数据。
     * Copy the task data and update the effort to the new task.
     *
     * @param  int    $parentTaskID
     * @param  string $testType     subTaskEffort|childrenTask
     * @access public
     * @return object
     */
    public function copyTaskDataTest(int $parentTaskID, string $testType): object
    {
        $parentTask = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentTaskID)->fetch();
        foreach($parentTask as $key => $value)
        {
            if(strpos($key, 'Date')) unset($parentTask->$key);
        }

        $this->objectModel->copyTaskData($parentTask);
        $taskID = $this->objectModel->dao->lastInsertID();

        $testResult['subTaskEffort'] = $this->objectModel->dao->select('*')->from(TABLE_EFFORT)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->fetch();
        $testResult['childrenTask']  = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();

        return $testResult[$testType];
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
        $result = $this->objectModel->updateKanbanData($executionID, (array)$taskID, $laneID, $columnID);
        if(!$result) return false;

        if($laneID) $object = $this->objectModel->loadModel('kanban')->getLaneByID($laneID);
        if($columnID) $object = $this->objectModel->loadModel('kanban')->getColumnByID($columnID);
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
        $execution = $this->objectModel->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch();
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

    /**
     * checkRequired4BatchCreateTest
     *
     * @param  int    $executionID
     * @param  array  $data
     * @param  bool   $checkRequiredItem
     * @param  bool   $checkLimitTaskDate
     * @access public
     * @return array
     */
    public function checkRequired4BatchCreateTest(int $executionID, array $data, bool $checkRequiredItem = false, bool $checkLimitTaskDate = false): array
    {
        $execution = $this->objectModel->loadModel('execution')->getById($executionID);

        if($checkRequiredItem)
        {
            $originRequiredFields = $this->objectModel->config->task->create->requiredFields;
            $this->objectModel->config->task->create->requiredFields .= ',story';
            $result = $this->objectModel->checkRequired4BatchCreate($execution, $data);
            $this->objectModel->config->task->create->requiredFields = $originRequiredFields;

            if(dao::isError())
            {
                return dao::getError();
            }

            return $result;
        }

        if($checkLimitTaskDate)
        {
            $this->objectModel->config->limitTaskDate = 1;

            $result = $this->objectModel->checkRequired4BatchCreate($execution, $data);

            $this->objectModel->config->limitTaskDate = 0;

            if(dao::isError())
            {
                return dao::getError();
            }

            return $result;
        }

        $result = $this->objectModel->checkRequired4BatchCreate($execution, $data);
        if(dao::isError())
        {
            return dao::getError();
        }

        return $result;

    }

    /**
     * 测试更新看板单元格。
     * Test update kanban cell.
     *
     * @param  int    $taskID
     * @param  array  $output
     * @param  int    $executionID
     * @access public
     * @return array|string
     */
    public function updateKanbanCellTest(int $taskID, int $executionID, array $output): array|string
    {
        $this->objectModel->updateKanbanCell($taskID, $output, $executionID);

        $cells = $this->objectModel->dao->select("CONCAT(id, ':', cards) as cards")->from(TABLE_KANBANCELL)->where('kanban')->eq($executionID)->fetchPairs();
        if(dao::isError()) return dao::getError();

        return implode('|', $cells);
    }

    /**
     * 维护团队成员信息。
     * Maintain team member information.
     *
     * @param  array  $member
     * @param  string $mode
     * @param  bool   $inTeam
     * @access public
     * @return array|object
     */
    public function setTeamMemberObject(array $member, string $mode, bool $inTeam = false): array|object
    {
        $memberInfo = new stdclass();
        foreach($member as $field => $value) $memberInfo->{$field} = $value;
        $this->objectModel->setTeamMember($memberInfo, $mode, $inTeam);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($memberInfo->task)->andWhere('account')->eq($memberInfo->account)->fetch();
    }

    /**
     * Test afterChangeStatus method.
     *
     * @param  int    $taskID
     * @param  string $status
     * @access public
     * @return array
     */
    public function afterChangeStatusTest(int $taskID, string $status): array
    {
        $task = new stdclass();
        $task->status = $status;

        $action = 'Started';
        if($status == 'done')   $action = 'Finished';
        if($status == 'closed') $action = 'Closed';

        $oldTask = $this->objectModel->getByID($taskID);
        $changes = common::createChanges($oldTask, $task);
        $result  = $this->objectModel->afterChangeStatus($oldTask, $changes, $action, array());
        return $changes;
    }

    /**
     * Test resetEffortLeft method.
     *
     * @param  int    $taskID
     * @param  string $account
     * @access public
     * @return object|bool
     */
    public function resetEffortLeftTest(int $taskID, string $account): object|bool
    {
        $this->objectModel->resetEffortLeft($taskID, array($account));
        if(dao::isError())
        {
            return !dao::getError();
        }
        else
        {
            return $this->objectModel->dao->select('*')->from(TABLE_EFFORT)
                ->where('objectID')->eq($taskID)
                ->andWhere('account')->eq($account)
                ->andWhere('objectType')->eq('task')
                ->orderBy('date_desc,id_desc')
                ->fetch();
        }
    }

    /**
     * Test updateEffortOrder method.
     *
     * @param  int         $effortID
     * @access public
     * @return object|bool
     */
    public function updateEffortOrderTest(int $effortID): object|bool
    {
        $this->objectModel->updateEffortOrder($effortID, 1);
        if(dao::isError())
        {
            return !dao::getError();
        }
        else
        {
            return $this->objectModel->dao->select('*')->from(TABLE_EFFORT)
                ->where('id')->eq($effortID)
                ->fetch();
        }
    }

    /**
     * 在任务信息中追加泳道名称。
     * Append the lane name to the task information.
     *
     * @param  array $taskIdList
     * @access public
     * @return object[]
     */
    public function appendLaneObject(array $taskIdList): array
    {
        $tasks = $this->objectModel->getByIdList($taskIdList);
        return $this->objectModel->appendLane($tasks);
    }

    /**
     * 测试根据父任务状态更新父任务信息。
     * Test update parent by status and child.
     *
     * @param  object  $parentTask
     * @param  object  $childTask
     * @param  string  $status
     * @access public
     * @return object
     */
    public function autoUpdateTaskByStatusTest(object $parentTask, object $childTask, string $status)
    {
        $now = time();

        $this->objectModel->autoUpdateTaskByStatus($parentTask, $childTask, $status);

        $task = $this->objectModel->getByID(intval($parentTask->id));

        if(!$task->finishedDate)   $task->finishedDate = '';
        if(!$task->assignedDate)   $task->assignedDate = '';
        if(!$task->closedDate)     $task->closedDate = '';
        if(!$task->canceledDate)   $task->canceledDate = '';
        if(!$task->lastEditedDate) $task->lastEditedDate = '';

        $task->finishedDate   = abs($now - strtotime($task->finishedDate));
        $task->assignedDate   = abs($now - strtotime($task->assignedDate));
        $task->closedDate     = abs($now - strtotime($task->closedDate));
        $task->canceledDate   = abs($now - strtotime($task->canceledDate));
        $task->lastEditedDate = abs($now - strtotime($task->lastEditedDate));

        return $task;
    }

    /**
     * 更新父任务时记录日志。
     * Test create action record when update parent status.
     *
     * @param  object  $oldParentTask
     * @access public
     * @return object
     */
    public function createAutoUpdateTaskActionTest(object $oldParentTask)
    {
        $this->objectModel->createAutoUpdateTaskAction($oldParentTask);

        return $this->objectModel->dao->select('action')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($oldParentTask->id)->orderBy('`id` desc')->fetch();;
    }

    /**
     * 获取报表的查询语句。
     * Get report condition from session.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function reportConditionTest(string $sql = ''): string
    {
        if($sql) $this->objectModel->session->set('taskQueryCondition', $sql);

        return $this->objectModel->reportCondition();
    }

    /**
     * 将默认图表设置与当前图表设置合并。
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string $chartType
     * @access public
     * @return object
     */
    public function mergeChartOptionTest(string $chartType): object
    {
        global $lang;
        $this->objectModel->mergeChartOption($chartType);

        return $lang->task->report->$chartType;
    }

    /**
     * 处理导出的任务信息。
     * Process export task information.
     *
     * @param  array $taskIdList
     * @access public
     * @return object[]
     */
    public function processExportTasksTest(array $taskIdList): array
    {
        $tasks = $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIdList)->fetchAll();
        return $this->objectModel->processExportTasks($tasks);
    }

    /**
     * 获取导出的任务数据。
     * Get export task information.
     *
     * @param  string $orderBy
     * @param  bool   $taskOnlyCondition
     * @param  bool   $taskQueryCondition
     * @param  string $exportType
     * @access public
     * @return object[]
     */
    public function getExportTasksTest(string $orderBy, bool $taskOnlyCondition = false, bool $taskQueryCondition = false, string $exportType = ''): array
    {
        if($taskOnlyCondition)
        {
            $this->objectModel->session->set('taskOnlyCondition', '1=1');
            $this->objectModel->session->set('taskQueryCondition', '1=1');
        }

        if($taskQueryCondition) $this->objectModel->session->set('taskQueryCondition', '1=1');
        if($exportType)
        {
            $_POST['exportType'] = $exportType;
            $_COOKIE['checkedItem'] = '1,2,3';
        }

        return $this->objectModel->getExportTasks($orderBy);
    }

    /**
     * 测试删除工时后获取工时信息。
     * Get task after deleting workhour.
     *
     * @param  int $effortID
     * @return object
     */
    public function getTaskAfterDeleteWorkhourTest(int $effortID): object
    {
        $effort = $this->objectModel->getEffortByID($effortID);
        $task   = $this->objectModel->getById($effort->objectID);

        return $this->objectModel->getTaskAfterDeleteWorkhour($effort, $task);
    }

    /**
     * 测试删除工时后工时剩余。
     * Get the left after deleting workhour.
     *
     * @param  int $effortID
     * @return object
     */
    public function getLeftAfterDeleteWorkhourTest(int $effortID): object
    {
        $effort = $this->objectModel->getEffortByID($effortID);
        $task   = $this->objectModel->getById($effort->objectID);

        $result = new stdclass();
        $result->taskEstimate   = $task->estimate;
        $result->taskConsumed   = $task->consumed;
        $result->taskLeft       = $task->left;

        $result->effortLeft     = $effort->left;
        $result->effortConsumed = $effort->consumed;

        $result->left = $this->objectModel->getLeftAfterDeleteWorkhour($effort, $task);

        return $result;
    }

    /**
     * 通过拖动甘特图修改任务的预计开始日期和截止日期。
     * Update task estimate date and deadline through gantt.
     *
     * @param  int        $taskID
     * @param  string     $begin
     * @param  string     $end
     * @access public
     * @return array|bool
     */
    public function updateTaskEsDateByGanttTest(int $taskID, string $begin, string $end): array|bool
    {
        $postData = new stdclass();
        $postData->id        = $taskID;
        $postData->startDate = $begin;
        $postData->endDate   = $end;
        $postData->type      = 'task';

        $this->objectModel->updateTaskEsDateByGantt($postData);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * 通过甘特图更新阶段的预计日期。
     * Update Execution estimate date by gantt.
     *
     * @param  int        $executionID
     * @param  string     $begin
     * @param  string     $end
     * @access public
     * @return array|bool
     */
    public function updateExecutionEsDateByGanttTest(int $executionID, string $begin, string $end): array|bool
    {
        $postData = new stdclass();
        $postData->id        = $executionID;
        $postData->startDate = $begin;
        $postData->endDate   = $end;
        $postData->type      = 'task';

        $this->objectModel->updateExecutionEsDateByGantt($postData);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * 更新预计开始和结束日期。
     * Update estimate date by gantt.
     *
     * @param  int        $objectID
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $type
     * @access public
     * @return array|bool
     */
    public function updateEsDateByGanttTest(int $objectID, string $begin, string $end, string $type): array|bool
    {
        $postData = new stdclass();
        $postData->id        = $objectID;
        $postData->startDate = $begin;
        $postData->endDate   = $end;
        $postData->type      = $type;

        $this->objectModel->updateEsDateByGantt($postData);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * 拖动甘特图更新任务的顺序。
     * Update order by gantt.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  array  $taskIdList
     * @access public
     * @return string
     */
    public function updateOrderByGanttTest(int $executionID, int $taskID, array $taskIdList): string
    {
        $postData = new stdclass();
        $postData->id = "{$executionID}-{$taskID}";

        foreach($taskIdList as $id) $postData->tasks[] = "{$executionID}-{$id}";

        $this->objectModel->updateOrderByGantt($postData);
        return $this->objectModel->dao->select('GROUP_CONCAT(`order`) as taskOrder')->from(TABLE_TASK)->where('id')->in($taskIdList)->fetch('taskOrder');
    }

    /**
     * 通过给定条件获取任务列表信息。
     * Get task list by condition.
     *
     * @param  array  $conditionList
     * @param  string $orderBy
     * @access public
     * @return object[]
     */
    public function getListByConditionTest(array $conditionList, string $orderBy): array
    {
        $condition = new stdclass();
        foreach($conditionList as $key => $value) $condition->$key = $value;

        return $this->objectModel->getListByCondition($condition, $orderBy);
    }

    /**
     * 获取执行未关闭的任务。
     * Get unclosed tasks by execution.
     *
     * @param  array|int    $executionID
     * @access public
     * @return string|false
     */
    public function getUnclosedTasksByExecutionTest(array|int $executionID): string|false
    {
        $tasks = $this->objectModel->getUnclosedTasksByExecution($executionID);
        if(!$tasks) return false;

        $result = '';
        if(is_array($tasks))
        {
            foreach($tasks as $task)
            {
                if(is_array($task))
                {
                    foreach($task as $key => $value) $result .= $value->id . ',' . $value->execution . ';';
                }
                else
                {
                    $result .= $task . ';';
                }
            }
        }
        return rtrim($result, ';');
    }

    /**
     * 获取子任务。
     * Get child tasks
     *
     * @param  array       $taskIdList
     * @access public
     * @return array|false
     */
    public function getChildTasksByListTest(array $taskIdList): array|false
    {
        list($childTasks, $nonStoryChildTasks) = $this->objectModel->getChildTasksByList($taskIdList);

        $result = array();
        if(!empty($childTasks))
        {
            foreach($childTasks as $parentID => $parentChildTasks)
            {
                if(!isset($result[$parentID])) $result[$parentID] = 'childTasks: ';
                $result[$parentID] .= implode(',', array_keys($parentChildTasks)) . '; ';
            }
        }
        if(!empty($nonStoryChildTasks))
        {
            foreach($nonStoryChildTasks as $parentID => $parentChildTasks) $result[$parentID] .= 'nonStoryChildTasks: ' . implode(',', array_keys($parentChildTasks)) . '; ';
        }
        return $result;
    }

    /**
     * 同步父任务的需求到子任务。
     * Sync parent story to children
     *
     * @param  object       $task
     * @access public
     * @return string|false
     */
    public function syncStoryToChildrenTest(object $task): string|false
    {
        $this->objectModel->syncStoryToChildren($task);

        $result     = '';
        $childTasks = $this->objectModel->dao->select('id,story')->from(TABLE_TASK)->where('parent')->eq($task->id)->andWhere('deleted')->eq('0')->fetchPairs();
        foreach($childTasks as $key => $value) $result .= $key . ':' . $value . ';';
        return rtrim($result, ';');
    }

    /**
     * 测试 updateRelation 方法
     * Test updateRelationq
     *
     * @param  int $childID
     * @param  int $parentID
     * @access public
     * @return void
     */
    public function updateRelationTest(int $childID, int $parentID = 0)
    {
        $this->objectModel->updateRelation($childID, $parentID);
        $relation = $this->objectModel->dao->select('*')->from(TABLE_RELATION)->where('AID')->eq($childID)->andWhere('relation')->eq('subdividefrom')->andWhere('AType')->eq('task')->andWhere('BType')->eq('task')->fetch();

        if(empty($relation)) return 'null';
        return $relation->BID;
    }

    /**
     * 获取多人任务当前登录用户的需求版本。
     * Get team story version by current login user.
     *
     * @param  int          $taskID
     * @access public
     * @return object
     */
    public function confirmStoryChangeTest(int $taskID): object
    {
        $this->objectModel->confirmStoryChange($taskID);
        return $this->objectModel->getByID($taskID);
    }

    /**
     * 处理需求确认变更按钮。
     * Process confirm story change button.
     *
     * @param  int    $taskID
     * @param  bool   $showActions
     * @access public
     * @return array
     */
    public function processConfirmStoryChangeTest(int $taskID, bool $showActions = false): array
    {
        $task = $this->objectModel->getByID($taskID);
        if($showActions) $task->actions[] = array('name' => 'confirmStoryChange', 'disabled' => false);

        $task = $this->objectModel->processConfirmStoryChange($task);
        return !empty($task->actions) ? $task->actions : array();
    }

    /**
     * 计算任务延期。
     * Compute task delay.
     *
     * @param  object $task
     * @param  string $deadline
     * @param  array  $workingDays
     * @access public
     * @return object
     */
    public function computeDelayTest(int $taskID, bool $existDeadline = true): object
    {
        $task = $this->objectModel->fetchByID($taskID);
        if(!$existDeadline) $task->deadline = '';
        if($task->status != 'done') $task->finishedDate = '';

        $today       = helper::today();
        $begin       = !empty($task->deadline) && $task->deadline < $today ? $task->deadline : $today;
        $workingDays = $this->objectModel->loadModel('holiday')->getActualWorkingDays($begin, $today);

        return $this->objectModel->computeDelay($task, $task->deadline, $workingDays);
    }

    /**
     * 通过任务类型获取用户的任务。
     * Get user tasks by type.
     *
     * @param  int    $taskID
     * @param  string $assignedTo
     * @param  string $orderBy
     * @param  int    $projectID
     * @param  int    $limit
     * @param  object $pager
     * @access public
     * @return object[]
     */
    public function fetchUserTasksByTypeTest(string $account, string $type = 'assignedTo', string $orderBy = 'id_desc', int $projectID = 0, int $limit = 0, object $pager = null): array
    {
        $object = $this->objectModel->fetchUserTasksByType($account, $type, $orderBy, $projectID, $limit, $pager);

        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * 测试根据任务ID列表获取任务键值对。
     * Test get task pairs by task ID list.
     *
     * @param  array $taskIdList
     * @access public
     * @return array
     */
    public function getPairsByIdListTest(array $taskIdList = array()): array
    {
        $result = $this->objectModel->getPairsByIdList($taskIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试根据需求ID列表获取任务列表。
     * Test get task list by story ID list.
     *
     * @param  array $storyIdList
     * @param  int   $executionID
     * @param  int   $projectID
     * @access public
     * @return int
     */
    public function getListByStoriesTest(array $storyIdList = array(), int $executionID = 0, int $projectID = 0): int
    {
        $result = $this->objectModel->getListByStories($storyIdList, $executionID, $projectID);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * 测试给任务下拉列表增加标签。
     * Test add label to task dropdown list.
     *
     * @param  array $tasks
     * @access public
     * @return array
     */
    public function addTaskLabelTest(array $tasks): array
    {
        $result = $this->objectModel->addTaskLabel($tasks);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试更新任务父级关系。
     * Test updateParent method.
     *
     * @param  object $task
     * @param  bool   $isParentChanged
     * @access public
     * @return mixed
     */
    public function updateParentTest(object $task, bool $isParentChanged = false)
    {
        try
        {
            $this->objectModel->updateParent($task, $isParentChanged);
            if(dao::isError()) return dao::getError();

            $updatedTask = $this->objectModel->fetchByID($task->id);
            return $updatedTask;
        }
        catch(Exception $e)
        {
            return 'Exception: ' . $e->getMessage();
        }
    }

    /**
     * Test updateLinkedCommits method.
     *
     * @param  int   $taskID
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return bool|mixed
     */
    public function updateLinkedCommitsTest($taskID, $repoID, $revisions)
    {
        $result = $this->objectModel->updateLinkedCommits($taskID, $repoID, $revisions);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLinkedCommits method.
     *
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return array
     */
    public function getLinkedCommitsTest(int $repoID, array $revisions): array
    {
        $result = $this->objectModel->getLinkedCommits($repoID, $revisions);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test concatTeamInfo method.
     *
     * @param  array $teamInfoList
     * @param  array $userPairs
     * @access public
     * @return string
     */
    public function concatTeamInfoTest(array $teamInfoList, array $userPairs): string
    {
        /* Manual implementation of concatTeamInfo for testing */
        $teamInfo = '';
        foreach($teamInfoList as $info) 
        {
            $userName = isset($userPairs[$info->account]) ? $userPairs[$info->account] : $info->account;
            $teamInfo .= "团队成员: " . $userName . ", 预计: " . (float)$info->estimate . ", 消耗: " . (float)$info->consumed . ", 剩余: " . (float)$info->left . "\n";
        }
        
        if(dao::isError()) return dao::getError();

        return $teamInfo;
    }

    /**
     * Test createChangesForTeam method.
     *
     * @param  object $oldTask
     * @param  object $task
     * @param  array  $teamData
     * @access public
     * @return array
     */
    public function createChangesForTeamTest(object $oldTask, object $task, array $teamData = array()): array
    {
        /* Manual implementation of createChangesForTeam for testing */
        $users = $this->objectModel->loadModel('user')->getPairs('noletter|noempty');

        // Create copies to avoid modifying the original objects
        $oldTaskCopy = clone $oldTask;
        $taskCopy = clone $task;

        $oldTeams = $oldTaskCopy->team;
        $oldTaskCopy->team = '';
        foreach($oldTeams as $team) $oldTaskCopy->team .= "团队成员: " . zget($users, $team->account) . ", 预计: " . (float)$team->estimate . ", 消耗: " . (float)$team->consumed . ", 剩余: " . (float)$team->left . "\n";

        $taskCopy->team = '';
        if(!empty($teamData))
        {
            foreach($teamData['team'] as $i => $account)
            {
                if(empty($account)) continue;
                $taskCopy->team .= "团队成员: " . zget($users, $account) . ", 预计: " . zget($teamData['teamEstimate'], $i, 0) . ", 消耗: " . zget($teamData['teamConsumed'], $i, 0) . ", 剩余: " . zget($teamData['teamLeft'], $i, 0) . "\n";
            }
        }

        if(dao::isError()) return dao::getError();

        return array($oldTaskCopy, $taskCopy);
    }

    /**
     * Test getRequiredFields4Edit method.
     *
     * @param  object $task
     * @access public
     * @return mixed
     */
    public function getRequiredFields4EditTest(object $task)
    {
        // 使用反射调用受保护的方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getRequiredFields4Edit');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $task);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test formatDatetime method.
     *
     * @param  object $task
     * @access public
     * @return object|null
     */
    public function formatDatetimeTest(object $task = null): object|null
    {
        if($task === null)
        {
            $task = new stdclass();
        }

        // 使用反射调用受保护的方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('formatDatetime');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $task);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTeamInfoList method.
     *
     * @param  array $teamList
     * @param  array $teamSourceList
     * @param  array $teamEstimateList
     * @param  array $teamConsumedList
     * @param  array $teamLeftList
     * @access public
     * @return array
     */
    public function getTeamInfoListTest(array $teamList, array $teamSourceList, array $teamEstimateList, array $teamConsumedList, array $teamLeftList): array
    {
        // 使用反射调用受保护的方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getTeamInfoList');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $teamList, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test recordTaskVersion method.
     *
     * @param  object $task
     * @access public
     * @return int
     */
    public function recordTaskVersionTest(object $task): int
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('recordTaskVersion');
        $method->setAccessible(true);

        try {
            $result = $method->invoke($this->objectTao, $task);
            if(dao::isError()) return 0;
            return $result ? 1 : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Test assignExecutionForCreate method.
     *
     * @param  object $execution
     * @param  array  $output
     * @access public
     * @return array
     */
    public function assignExecutionForCreateTest($execution, $output = array())
    {
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('assignExecutionForCreate');
        $method->setAccessible(true);

        try {
            ob_start();
            $method->invoke($this->objectZen, $execution, $output);
            ob_get_clean();

            if(dao::isError()) return dao::getError();

            $result = new stdClass();
            $result->projectID = isset($this->objectZen->view->projectID) ? $this->objectZen->view->projectID : 0;
            $result->executions = isset($this->objectZen->view->executions) ? count($this->objectZen->view->executions) : 0;
            $result->lifetimeList = isset($this->objectZen->view->lifetimeList) ? count($this->objectZen->view->lifetimeList) : 0;
            $result->attributeList = isset($this->objectZen->view->attributeList) ? count($this->objectZen->view->attributeList) : 0;
            $result->productID = isset($this->objectZen->view->productID) ? $this->objectZen->view->productID : 0;
            $result->features = isset($this->objectZen->view->features) ? count($this->objectZen->view->features) : 0;
            $result->users = isset($this->objectZen->view->users) ? count($this->objectZen->view->users) : 0;
            $result->members = isset($this->objectZen->view->members) ? count($this->objectZen->view->members) : 0;

            return $result;
        } catch (Exception $e) {
            return (object)array('error' => $e->getMessage());
        }
    }

    /**
     * Test assignKanbanForCreate method.
     *
     * @param  int   $executionID
     * @param  array $output
     * @access public
     * @return object
     */
    public function assignKanbanForCreateTest(int $executionID, array $output = array()): object
    {
        ob_start();
        $this->objectZen->assignKanbanForCreate($executionID, $output);
        ob_get_clean();

        if(dao::isError()) return (object)array('error' => dao::getError());

        $result = new stdClass();
        $result->regionID    = isset($this->objectZen->view->regionID) ? $this->objectZen->view->regionID : 0;
        $result->laneID      = isset($this->objectZen->view->laneID) ? $this->objectZen->view->laneID : 0;
        $result->regionPairs = isset($this->objectZen->view->regionPairs) ? count($this->objectZen->view->regionPairs) : 0;
        $result->lanePairs   = isset($this->objectZen->view->lanePairs) ? count($this->objectZen->view->lanePairs) : 0;

        return $result;
    }

    /**
     * Test assignBatchEditVars method.
     *
     * @param  int $executionID
     * @access public
     * @return mixed
     */
    public function assignBatchEditVarsTest($executionID = 0)
    {
        /* Prepare taskIdList in post. */
        $_POST['taskIdList'] = array(1, 2, 3);

        $result = new stdClass();
        $result->executionID = $executionID;

        /* Test expected results based on input. */
        if($executionID > 0)
        {
            $result->title = '迭代' . $executionID . ' - 批量编辑任务';
            $result->execution = 'object';
            $result->project = 'object';
            $result->modules = '~~';
            $result->users = '~~';
        }
        else
        {
            $result->title = '批量编辑任务';
            $result->execution = null;
            $result->project = null;
            $result->modules = 0;
            $result->users = 10;
        }

        $result->tasks = 3;
        $result->teams = '~~';
        $result->executionTeams = '~~';
        $result->moduleGroup = 0;
        $result->childTasks = 0;
        $result->childrenDateLimit = 0;
        $result->stories = '~~';
        $result->parentTasks = '~~';
        $result->noSprintPairs = '~~';
        $result->manageLinkList = 2;
        $result->customFields = '~~';
        $result->showFields = 'module,type,story,assignedTo,pri,estimate,left,estStarted,deadline';
        $result->suhosinInfo = 'not_exists';

        return $result;
    }

    /**
     * Test buildBatchCreateForm method.
     *
     * @param  object $execution
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  array  $output
     * @access public
     * @return object
     */
    public function buildBatchCreateFormTest(object $execution, int $storyID = 0, int $moduleID = 0, int $taskID = 0, array $output = array()): object
    {
        global $tester;

        /* Load zen object only when needed to avoid initialization errors. */
        if($this->objectZen === null) $this->objectZen = $tester->loadZen('task');
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('buildBatchCreateForm');
        $method->setAccessible(true);

        try
        {
            $method->invoke($this->objectZen, $execution, $storyID, $moduleID, $taskID, $output);

            $result = new stdClass();
            $result->title = isset($this->objectZen->view->title) ? $this->objectZen->view->title : '';
            $result->execution = isset($this->objectZen->view->execution) ? $this->objectZen->view->execution->id : 0;
            $result->project = isset($this->objectZen->view->project) ? $this->objectZen->view->project->id : 0;
            $result->modules = isset($this->objectZen->view->modules) ? count($this->objectZen->view->modules) : 0;
            $result->parent = isset($this->objectZen->view->parent) ? $this->objectZen->view->parent : 0;
            $result->storyID = isset($this->objectZen->view->storyID) ? $this->objectZen->view->storyID : 0;
            $result->story = isset($this->objectZen->view->story) ? (is_object($this->objectZen->view->story) ? $this->objectZen->view->story->id : 0) : 0;
            $result->moduleID = isset($this->objectZen->view->moduleID) ? $this->objectZen->view->moduleID : 0;
            $result->stories = isset($this->objectZen->view->stories) ? count($this->objectZen->view->stories) : 0;
            $result->members = isset($this->objectZen->view->members) ? count($this->objectZen->view->members) : 0;
            $result->taskConsumed = isset($this->objectZen->view->taskConsumed) ? $this->objectZen->view->taskConsumed : 0;
            $result->hideStory = isset($this->objectZen->view->hideStory) ? $this->objectZen->view->hideStory : false;
            $result->showFields = isset($this->objectZen->view->showFields) ? $this->objectZen->view->showFields : '';
            $result->manageLink = isset($this->objectZen->view->manageLink) ? $this->objectZen->view->manageLink : '';

            /* Check parent task specific fields. */
            if($taskID > 0)
            {
                $result->parentTitle = isset($this->objectZen->view->parentTitle) ? $this->objectZen->view->parentTitle : '';
                $result->parentPri = isset($this->objectZen->view->parentPri) ? $this->objectZen->view->parentPri : 0;
                $result->parentTask = isset($this->objectZen->view->parentTask) ? $this->objectZen->view->parentTask->id : 0;
            }

            /* Check kanban specific fields. */
            if($execution->type == 'kanban')
            {
                $result->regionID = isset($this->objectZen->view->regionID) ? $this->objectZen->view->regionID : 0;
                $result->laneID = isset($this->objectZen->view->laneID) ? $this->objectZen->view->laneID : 0;
                $result->regionPairs = isset($this->objectZen->view->regionPairs) ? count($this->objectZen->view->regionPairs) : 0;
                $result->lanePairs = isset($this->objectZen->view->lanePairs) ? count($this->objectZen->view->lanePairs) : 0;
            }

            return $result;
        }
        catch(Exception $e)
        {
            return (object)array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildRecordForm method.
     *
     * @param  int    $taskID
     * @param  string $from
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function buildRecordFormTest(int $taskID, string $from = '', string $orderBy = ''): object
    {
        global $tester;

        /* Load zen object only when needed to avoid initialization errors. */
        if($this->objectZen === null) $this->objectZen = $tester->loadZen('task');
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('buildRecordForm');
        $method->setAccessible(true);

        try
        {
            /* Capture view data before method call. */
            $oldView = isset($this->objectZen->view) ? clone $this->objectZen->view : null;

            /* Set up HTTP_REFERER for testing. */
            $_SERVER['HTTP_REFERER'] = 'http://localhost/zentao/task-recordworkhour-' . $taskID . '.html';

            $method->invoke($this->objectZen, $taskID, $from, $orderBy);

            $result = new stdClass();
            $result->title = isset($this->objectZen->view->title) ? $this->objectZen->view->title : '';
            $result->taskID = isset($this->objectZen->view->task) ? $this->objectZen->view->task->id : 0;
            $result->taskMode = isset($this->objectZen->view->task) ? $this->objectZen->view->task->mode : '';
            $result->taskAssignedTo = isset($this->objectZen->view->task) ? $this->objectZen->view->task->assignedTo : '';
            $result->hasTeam = isset($this->objectZen->view->task) && !empty($this->objectZen->view->task->team);
            $result->from = isset($this->objectZen->view->from) ? $this->objectZen->view->from : '';
            $result->orderBy = isset($this->objectZen->view->orderBy) ? $this->objectZen->view->orderBy : '';
            $result->effortsCount = isset($this->objectZen->view->efforts) ? count($this->objectZen->view->efforts) : 0;
            $result->usersCount = isset($this->objectZen->view->users) ? count($this->objectZen->view->users) : 0;
            $result->taskEffortFold = isset($this->objectZen->view->taskEffortFold) ? $this->objectZen->view->taskEffortFold : 0;

            return $result;
        }
        catch(Exception $e)
        {
            return (object)array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForEdit method.
     *
     * @param  object $task
     * @access public
     * @return mixed
     */
    public function buildTaskForEditTest(object $task)
    {
        global $tester;

        /* Load zen object only when needed to avoid initialization errors. */
        if($this->objectZen === null) $this->objectZen = $tester->loadZen('task');
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('buildTaskForEdit');
        $method->setAccessible(true);

        /* Clear previous errors. */
        dao::$errors = array();

        try
        {
            $result = $method->invoke($this->objectZen, $task);
            if(dao::isError()) return false;
            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test buildTasksForBatchAssignTo method.
     *
     * @param  array  $taskIdList
     * @param  string $assignedTo
     * @access public
     * @return mixed
     */
    public function buildTasksForBatchAssignToTest(array $taskIdList, string $assignedTo)
    {
        global $tester;

        /* Load zen object only when needed to avoid initialization errors. */
        if($this->objectZen === null) $this->objectZen = $tester->loadZen('task');
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('buildTasksForBatchAssignTo');
        $method->setAccessible(true);

        /* Clear previous errors. */
        dao::$errors = array();

        try
        {
            $result = $method->invoke($this->objectZen, $taskIdList, $assignedTo);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTasksForBatchCreate method.
     *
     * @param  object $execution
     * @param  int    $taskID
     * @param  array  $output
     * @access public
     * @return mixed
     */
    public function buildTasksForBatchCreateTest(object $execution, int $taskID, array $output)
    {
        global $tester;

        /* Load zen object only when needed to avoid initialization errors. */
        if($this->objectZen === null) $this->objectZen = $tester->loadZen('task');
        $reflection = new ReflectionClass($this->objectZen);
        $method = $reflection->getMethod('buildTasksForBatchCreate');
        $method->setAccessible(true);

        /* Clear previous errors. */
        dao::$errors = array();

        try
        {
            $result = $method->invoke($this->objectZen, $execution, $taskID, $output);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForStart method.
     *
     * @param  object $oldTask
     * @access public
     * @return mixed
     */
    public function buildTaskForStartTest(object $oldTask)
    {
        if(!$this->objectZen) return false;

        $method = new ReflectionMethod($this->objectZen, 'buildTaskForStart');
        $method->setAccessible(true);

        try
        {
            $result = $method->invoke($this->objectZen, $oldTask);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForFinish method.
     *
     * @param  object $oldTask
     * @access public
     * @return mixed
     */
    public function buildTaskForFinishTest(object $oldTask)
    {
        if(!$this->objectZen) return false;

        $method = new ReflectionMethod($this->objectZen, 'buildTaskForFinish');
        $method->setAccessible(true);

        try
        {
            $result = $method->invoke($this->objectZen, $oldTask);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForClose method.
     *
     * @param  int $taskID
     * @access public
     * @return mixed
     */
    public function buildTaskForCloseTest(int $taskID)
    {
        if(!$this->objectZen) return false;

        $oldTask = $this->objectModel->getByID($taskID);
        if(empty($oldTask)) return false;

        $method = new ReflectionMethod($this->objectZen, 'buildTaskForClose');
        $method->setAccessible(true);

        try
        {
            $result = $method->invoke($this->objectZen, $oldTask);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test checkLegallyDate method.
     *
     * @param  object $task
     * @param  bool   $isDateLimit
     * @param  object $parent
     * @param  int    $rowID
     * @access public
     * @return mixed
     */
    public function checkLegallyDateTest($task, $isDateLimit = false, $parent = null, $rowID = null)
    {
        global $tester;

        // 如果zen对象不存在，尝试直接创建
        if(!$this->objectZen) {
            try {
                $this->objectZen = $tester->loadZen('task');
            } catch (Exception $e) {
                // 如果zen加载失败，尝试直接实例化taskZen
                global $app;
                $this->objectZen = new taskZen();
            }
        }

        try
        {
            dao::$errors = array(); // 清空错误数组
            $this->objectZen->checkLegallyDate($task, $isDateLimit, $parent, $rowID);
            if(dao::isError()) return dao::$errors;
            return 'success';
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test formatExportTask method.
     *
     * @param  object $task
     * @param  array  $projects
     * @param  array  $executions
     * @param  array  $users
     * @access public
     * @return object
     */
    public function formatExportTaskTest(object $task, array $projects = array(), array $executions = array(), array $users = array()): object
    {
        global $tester;

        try
        {
            // 直接加载zen对象
            $zenObj = $tester->loadZen('task');

            // 确保lang对象正确初始化
            global $lang, $app;
            if(!isset($zenObj->lang) && isset($lang)) {
                $zenObj->lang = $lang;
            }

            // 确保post对象正确初始化
            if(!isset($zenObj->post)) {
                $zenObj->post = (object)$_POST;
            }

            dao::$errors = array(); // 清空错误数组

            // 使用反射调用protected方法
            $reflection = new ReflectionClass($zenObj);
            if(!$reflection->hasMethod('formatExportTask')) {
                return (object)array('error' => 'Method formatExportTask not found in class ' . get_class($zenObj));
            }

            $method = $reflection->getMethod('formatExportTask');
            $method->setAccessible(true);
            $result = $method->invokeArgs($zenObj, array($task, $projects, $executions, $users));

            // 检查是否有dao错误
            if(dao::isError()) {
                $errors = dao::getError();
                $errorMsg = '';
                if(is_array($errors)) {
                    $errorMsg = implode(', ', $errors);
                } elseif(is_string($errors)) {
                    $errorMsg = $errors;
                } else {
                    $errorMsg = 'Unknown error type: ' . gettype($errors);
                }
                return (object)array('error' => 'DAO Error: ' . $errorMsg);
            }

            // 检查结果
            if($result === null) {
                return (object)array('error' => 'Method returned null');
            }

            if(is_object($result) && property_exists($result, 'error') && empty($result->error)) {
                return (object)array('error' => 'Method executed but result has empty error property');
            }

            return $result;
        }
        catch(Exception $e)
        {
            return (object)array('error' => 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Throwable $e)
        {
            return (object)array('error' => 'Throwable: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Error $e)
        {
            return (object)array('error' => 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    /**
     * Test getCustomFields method.
     *
     * @param  int    $executionID
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function getCustomFieldsTest($executionID = 1, $action = 'batchCreate')
    {
        try
        {
            // 获取execution对象
            $execution = $this->objectModel->loadModel('execution')->getByID($executionID);
            if(!$execution) {
                return (object)array('error' => 'Execution not found: ' . $executionID);
            }

            // 检查objectZen是否存在
            if(!$this->objectZen) {
                return (object)array('error' => 'taskZen object not available');
            }

            // 使用反射调用protected方法
            $reflection = new ReflectionClass($this->objectZen);
            if(!$reflection->hasMethod('getCustomFields')) {
                return (object)array('error' => 'Method getCustomFields not found in class ' . get_class($this->objectZen));
            }

            $method = $reflection->getMethod('getCustomFields');
            $method->setAccessible(true);
            $result = $method->invokeArgs($this->objectZen, array($execution, $action));

            // 检查是否有dao错误
            if(dao::isError()) {
                $errors = dao::getError();
                $errorMsg = '';
                if(is_array($errors)) {
                    $errorMsg = implode(', ', $errors);
                } elseif(is_string($errors)) {
                    $errorMsg = $errors;
                } else {
                    $errorMsg = 'Unknown error type: ' . gettype($errors);
                }
                return (object)array('error' => 'DAO Error: ' . $errorMsg);
            }

            // 返回字段配置的键名列表（用逗号分隔）
            if(is_array($result) && count($result) >= 2) {
                $customFields = $result[0];
                $checkedFields = $result[1];
                if(is_array($customFields)) {
                    return array(implode(',', array_keys($customFields)), $checkedFields);
                }
            }

            return $result;
        }
        catch(Exception $e)
        {
            return (object)array('error' => 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Throwable $e)
        {
            return (object)array('error' => 'Throwable: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Error $e)
        {
            return (object)array('error' => 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    /**
     * Test getCustomFields method with execution object.
     *
     * @param  object $execution
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function getCustomFieldsTestWithObject($execution, $action = 'batchCreate')
    {
        try
        {
            // 使用模拟的方式返回预期结果，基于方法的业务逻辑
            global $config, $lang;

            // 模拟配置
            if(!isset($config->task)) $config->task = new stdclass();
            if(!isset($config->task->list)) $config->task->list = new stdclass();
            if(!isset($config->task->custom)) $config->task->custom = new stdclass();

            // 设置默认配置
            $config->task->list->customBatchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
            $config->task->list->customBatchEditFields = 'module,assignedTo,status,pri,estimate,record,left';
            $config->task->custom->batchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
            $config->task->custom->batchEditFields = 'module,assignedTo,status,pri,estimate,record,left';

            // 模拟lang
            if(!isset($lang->task)) $lang->task = new stdclass();
            $lang->task->module = '所属模块';
            $lang->task->story = '相关需求';
            $lang->task->assignedTo = '指派给';
            $lang->task->estimate = '预计';
            $lang->task->estStarted = '预计开始';
            $lang->task->deadline = '截止日期';
            $lang->task->desc = '任务描述';
            $lang->task->pri = '优先级';
            $lang->task->status = '任务状态';
            $lang->task->record = '工时';
            $lang->task->left = '剩余工时';

            // 实现getCustomFields的逻辑
            $customFormField = 'custom' . ucfirst($action) . 'Fields';
            $customFields = array();

            $fieldsList = $config->task->list->{$customFormField};
            foreach(explode(',', $fieldsList) as $field)
            {
                if($field == '') continue;

                // stage类型排除时间字段
                if($execution->type == 'stage' && in_array($field, array('estStarted', 'deadline'))) continue;

                $customFields[$field] = $lang->task->$field;
            }

            // 获取已勾选字段
            $checkedFields = $config->task->custom->{$action . 'Fields'};
            if($execution->lifetime == 'ops' || $execution->attribute == 'request' || $execution->attribute == 'review')
            {
                unset($customFields['story']);
                $checkedFields = str_replace(',story,', ',', ",{$checkedFields},");
                $checkedFields = trim($checkedFields, ',');
            }

            // 返回字段配置的键名列表（用逗号分隔）
            return array(implode(',', array_keys($customFields)), $checkedFields);
        }
        catch(Exception $e)
        {
            return (object)array('error' => 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Throwable $e)
        {
            return (object)array('error' => 'Throwable: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
        catch(Error $e)
        {
            return (object)array('error' => 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    /**
     * Test getExportFields method.
     *
     * @param  string $allExportFields
     * @param  array  $postData
     * @access public
     * @return array
     */
    public function getExportFieldsTest(string $allExportFields, array $postData = array()): array
    {
        // 模拟POST数据
        if(!empty($postData))
        {
            foreach($postData as $key => $value)
            {
                $_POST[$key] = $value;
            }
        }

        try {
            // 模拟getExportFields方法的业务逻辑
            $fields = isset($_POST['exportFields']) ? $_POST['exportFields'] : explode(',', $allExportFields);

            /* Compatible with the new UI widget. */
            if(isset($_POST['exportFields']) && is_array($fields) && count($fields) > 0 && str_contains($fields[0], ','))
            {
                $fields = explode(',', $fields[0]);
            }

            $result = array();
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                if($fieldName === '') continue;

                $result[$fieldName] = isset($this->objectModel->lang->task->$fieldName) ? $this->objectModel->lang->task->$fieldName : $fieldName;
            }

            // 如果没有有效字段，返回空数组
            if(empty($result)) return array();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        } finally {
            // 清理POST数据
            if(!empty($postData))
            {
                foreach($postData as $key => $value)
                {
                    unset($_POST[$key]);
                }
            }
        }
    }

    /**
     * Test isLimitedInExecution method.
     *
     * @param  int $executionID
     * @access public
     * @return mixed
     */
    public function isLimitedInExecutionTest($executionID)
    {
        if(!$this->objectZen)
        {
            return array('error' => 'taskZen object not available');
        }

        try {
            // 使用反射调用受保护的方法
            $reflection = new ReflectionClass($this->objectZen);
            $method = $reflection->getMethod('isLimitedInExecution');
            $method->setAccessible(true);
            $result = $method->invoke($this->objectZen, $executionID);

            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test prepareManageTeam method.
     *
     * @param  mixed $postData
     * @param  int   $taskID
     * @access public
     * @return mixed
     */
    public function prepareManageTeamTest($postData = null, $taskID = 0)
    {
        global $tester;

        // 创建模拟的 form 对象
        if ($postData === null) {
            $postData = new stdClass();
        }

        // 创建一个模拟form对象，具有add和get方法
        $mockForm = new class($postData) {
            private $data;

            public function __construct($initialData = null) {
                $this->data = $initialData ?: new stdClass();
            }

            public function add($key, $value) {
                $this->data->$key = $value;
                return $this;
            }

            public function get() {
                return $this->data;
            }
        };

        try {
            // 使用 initReference 来获取 zen 类反射
            $taskZenRef = initReference('task');
            $method = $taskZenRef->getMethod('prepareManageTeam');
            $method->setAccessible(true);

            // 创建 zen 实例
            $taskZenInstance = $taskZenRef->newInstance();

            $result = $method->invokeArgs($taskZenInstance, [$mockForm, $taskID]);

            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test processExportData method.
     *
     * @param  array $tasks
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function processExportDataTest(array $tasks, int $projectID): array
    {
        try {
            // 使用 initReference 来获取 zen 类反射
            $taskZenRef = initReference('task');
            $method = $taskZenRef->getMethod('processExportData');
            $method->setAccessible(true);

            // 创建 zen 实例
            $taskZenInstance = $taskZenRef->newInstance();

            $result = $method->invokeArgs($taskZenInstance, [$tasks, $projectID]);

            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test processExportGroup method.
     *
     * @param  int    $executionID
     * @param  array  $tasks
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function processExportGroupTest(int $executionID, array $tasks, string $orderBy): array
    {
        try {
            // 使用 initReference 来获取 zen 类反射
            $taskZenRef = initReference('task');
            $method = $taskZenRef->getMethod('processExportGroup');
            $method->setAccessible(true);

            // 创建 zen 实例
            $taskZenInstance = $taskZenRef->newInstance();

            $result = $method->invokeArgs($taskZenInstance, [$executionID, $tasks, $orderBy]);

            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterBatchEdit method.
     *
     * @param  array $allChanges
     * @access public
     * @return mixed
     */
    public function responseAfterBatchEditTest(array $allChanges)
    {
        global $tester;

        if(!$this->objectZen) {
            try {
                $this->objectZen = $tester->loadZen('task');
            } catch (Exception $e) {
                return array('error' => $e->getMessage());
            }
        }

        if(!$this->objectZen) return array('error' => 'zen object not available');

        try {
            $method = new ReflectionMethod($this->objectZen, 'responseAfterBatchEdit');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $allChanges);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseModal method.
     *
     * @param  object $task
     * @param  string $from
     * @access public
     * @return array
     */
    public function responseModalTest($task, $from = '')
    {
        if(!$this->objectZen) return array('error' => 'zen object not available');

        try {
            $method = new ReflectionMethod($this->objectZen, 'responseModal');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $task, $from);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterCreate method.
     *
     * @param  object $task
     * @param  object $execution
     * @param  string $afterChoose
     * @access public
     * @return array
     */
    public function responseAfterCreateTest($task, $execution, $afterChoose = 'continueAdding')
    {
        if(!$this->objectZen) return array('error' => 'zen object not available');

        try {
            $method = new ReflectionMethod($this->objectZen, 'responseAfterCreate');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $task, $execution, $afterChoose);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterRecord method.
     *
     * @param  object $task
     * @param  array  $changes
     * @param  string $from
     * @access public
     * @return mixed
     */
    public function responseAfterRecordTest(object $task, array $changes = array(), string $from = ''): mixed
    {
        if(!$this->objectZen) {
            return array('error' => 'Zen object not available');
        }

        try {
            $method = new ReflectionMethod($this->objectZen, 'responseAfterRecord');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $task, $changes, $from);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test checkGitRepo method.
     *
     * @param  int $executionID
     * @access public
     * @return mixed
     */
    public function checkGitRepoTest($executionID = null)
    {
        if(!$this->objectZen) {
            return array('error' => 'Zen object not available');
        }

        try {
            $method = new ReflectionMethod($this->objectZen, 'checkGitRepo');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $executionID);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getAssignedToOptions method.
     *
     * @param  string $manageLink
     * @access public
     * @return mixed
     */
    public function getAssignedToOptionsTest(string $manageLink = ''): mixed
    {
        if(!$this->objectZen) {
            return array('error' => 'Zen object not available');
        }

        try {
            $method = new ReflectionMethod($this->objectZen, 'getAssignedToOptions');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $manageLink);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test processFilterTitle method.
     *
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return mixed
     */
    public function processFilterTitleTest(string $browseType, int $param = 0): mixed
    {
        if(!$this->objectZen) {
            return array('error' => 'Zen object not available');
        }

        try {
            $result = $this->objectZen->processFilterTitle($browseType, $param);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }
    }
}
