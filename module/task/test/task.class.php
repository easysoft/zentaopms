<?php
class taskTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('task');

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
            `mode`, `story`, `color`,`desc`,`assignedTo`,`realStarted`,`finishedBy`,`canceledBy`,`closedReason` FROM zt_task WHERE id = $objectID")->fetch();
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
     * @param  int    $parentID
     * @access public
     * @return bool
     */
    public function afterBatchCreateObject(array $taskIdList, int $parentID = 0): bool
    {
        return $this->objectModel->afterBatchCreate($taskIdList, $parentID);
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

        return $this->objectModel->afterBatchUpdate($taskData);
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
     * @return array
     */
    public function startTest(int $taskID, array $param = array()): array
    {
        $task        = new stdclass();
        $startFields = array('id' => $taskID, 'status' => 'doing', 'assignedTo' => '', 'realstarted' => helper::now(), 'left' => 0, 'consumed' => 0);
        foreach($startFields as $field => $defaultvalue) $task->{$field} = $defaultvalue;
        foreach($param as $key => $value) $task->{$key} = $value;

        $oldTask = $this->objectModel->getByID($taskID);
        $result  = $this->objectModel->start($oldTask, $task);
        return $result;
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
     * @param  int    $taskID
     * @param  string $comment
     * @param  object $teamData
     * @param  array  $drag
     * @access public
     * @return array
     */
    public function activateTest(int $taskID, string $comment = '', object $teamData = null, array $drag = array()): array
    {
        $task = new stdclass();
        $activateFields = array('id' => $taskID, 'status' => 'doing','assignedTo' => '', 'left' => '3');
        foreach($activateFields as $field => $defaultValue) $task->{$field} = $defaultValue;

        $changes = $this->objectModel->activate($task, $comment, $teamData, $drag);

        if(dao::isError()) return dao::getError();
        return $changes;
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
        $task = new stdclass();
        $task->id = $taskID;
        foreach($param as $key => $value)
        {
            if($key == 'comment')
            {
                $_POST['comment'] = $value;
            }
            else
            {
                $task->{$key} = $value;
            }
        }

        $this->objectModel->cancel($task);
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
        return $result;
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
    public function updateTeamByEffortTest(int $effortID, object $record, int $taskID): array
    {
        $task        = $this->objectModel->getByID($taskID);
        $currentTeam = $this->objectModel->getTeamByAccount($task->team);
        $this->objectModel->updateTeamByEffort($effortID, $record, $currentTeam, $task);
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
        $this->objectModel->addTaskEffort($data);

        $objectID = $this->objectModel->dao->lastInsertID();
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
     * @access public
     * @return array|false
     */
    public function getToAndCcListTest(int $taskID): array|false
    {
        $task = $this->objectModel->getByID($taskID);
        if(empty($task)) return false;

        return $this->objectModel->getToAndCcList($task);
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
     * @param  array        $param
     * @param  array        $testTasks
     * @param  string       $requiredField
     * @access public
     * @return object|array
     */
    public function createTaskOfTestObject(array $param = array(), array $testTasks = array(), string $requiredField = ''): object|array
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
        $task->id     = $taskID;
        $task->status = $taskStatus;

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

        $taskID = $this->objectModel->copyTaskData($parentTask);

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
    public function updateTaskByChildAndStatusTest(object $parentTask, object $childTask, string $status)
    {
        $now = time();

        $this->objectModel->updateTaskByChildAndStatus($parentTask, $childTask, $status);

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
    public function createUpdateParentTaskActionTest(object $oldParentTask)
    {
        $this->objectModel->createUpdateParentTaskAction($oldParentTask);

        return $this->objectModel->dao->select('action')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($oldParentTask->id)->orderBy('`id` desc')->fetch();;
    }

    /**
     * 通过父任务更新子任务。
     * Update children task by parent task.
     *
     * @param  int    $parentID
     * @param  string $action
     * @param  string $comment
     * @access public
     * @return object|false
     */
    public function updateChildrenByParentTest(int $parentID, string $action, string $comment): object|false
    {
        $task = $this->objectModel->getByID($parentID);

        $data = new stdclass();
        $data->name   = $task->name;
        $data->pri    = $task->pri;
        $data->status = $task->status;

        $this->objectModel->updateChildrenByParent($parentID, $data, $action, $comment);

        $childID = $this->objectModel->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($parentID)->fetch('id');
        return $this->objectModel->dao->select('action')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($childID)->orderBy('id_desc')->fetch();
    }

    /**
     * 取消父任务更新子任务。。
     * Update a child task when cancel its parent task.
     *
     * @param  int    $taskID
     * @access public
     * @return object|false
     */
    public function cancelParentTaskTest(int $taskID): object|false
    {
        $task = $this->objectModel->getByID($taskID);

        $data = new stdclass();
        $data->id        = $task->id;
        $data->name      = $task->name;
        $data->pri       = $task->pri;
        $data->status    = $task->status;
        $data->execution = $task->execution;
        $this->objectModel->cancelParentTask($data);

        $childID = $this->objectModel->dao->select('id')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetch('id');
        return $this->objectModel->dao->select('action')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($childID)->orderBy('id_desc')->fetch();
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
}
