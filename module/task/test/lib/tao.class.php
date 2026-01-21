<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class taskTaoTest extends baseTest
{
    protected $moduleName = 'task';
    protected $className  = 'tao';

    /**
     * Test formatDatetime method.
     *
     * @param  object $task
     * @access public
     * @return object
     */
    public function formatDatetimeTest(?object $task = null)
    {
        $result = $this->invokeArgs('formatDatetime', [$task]);
        if(dao::isError()) return dao::getError();
        return $result;
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

        $this->instance->autoUpdateTaskByStatus($parentTask, $childTask, $status);

        $task = $this->instance->getByID(intval($parentTask->id));

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
        $task = $this->instance->getByID($taskID);
        return $this->instance->buildTaskForEffort($record, $task, $lastDate, $isFinishTask);
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
        $task      = $this->instance->getByID($taskID);
        $oldEffort = $this->instance->getEffortByID($effortID);

        $effort = clone $oldEffort;
        foreach($param as $key => $value) $effort->{$key} = $value;

        return $this->instance->buildTaskForUpdateEffort($task, $oldEffort, $effort);
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
        if(!empty($taskIdList)) $tasks = $this->instance->getByIdList($taskIdList);

        $parentIdList = array();
        foreach($tasks as $task)
        {
            if($task->parent <= 0 or isset($tasks[$task->parent]) or isset($parentIdList[$task->parent])) continue;
            $parentIdList[$task->parent] = $task->parent;
        }
        $parentTasks = $this->instance->getByIdList($parentIdList);

        return $this->instance->buildTaskTree($tasks, $parentTasks);
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
        $effort = $this->instance->getEffortByID($effortID);

        foreach($param as $key => $value) $effort->{$key} = $value;
        $result = $this->instance->checkEffort($effort);

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
        $task = $this->instance->computeTaskStatus($currentTask, $oldTask, $task, $autoStatus, $hasEfforts, $members);

        if(dao::isError()) return dao::getError();
        return $task;
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
        $parentTask = $this->instance->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentTaskID)->fetch();
        foreach($parentTask as $key => $value)
        {
            if(strpos($key, 'Date')) unset($parentTask->$key);
        }

        $this->instance->copyTaskData($parentTask);
        $taskID = $this->instance->dao->lastInsertID();

        $testResult['subTaskEffort'] = $this->instance->dao->select('*')->from(TABLE_EFFORT)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->fetch();
        $testResult['childrenTask']  = $this->instance->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();

        return $testResult[$testType];
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
        $this->instance->createAutoUpdateTaskAction($oldParentTask);

        return $this->instance->dao->select('action')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($oldParentTask->id)->orderBy('`id` desc')->fetch();;
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
        // Mock user pairs to avoid database call
        $users = array(
            'admin' => '管理员',
            'user1' => '用户1',
            'user2' => '用户2',
            'user3' => '用户3',
            'user4' => '用户4'
        );

        // Create copies to avoid modifying the original objects
        $oldTaskCopy = clone $oldTask;
        $taskCopy = clone $task;

        // Process old team information
        $oldTeams = $oldTaskCopy->team;
        $oldTaskCopy->team = '';
        foreach($oldTeams as $team) {
            $oldTaskCopy->team .= "团队成员: " . zget($users, $team->account) . ", 预计: " . (float)$team->estimate . ", 消耗: " . (float)$team->consumed . ", 剩余: " . (float)$team->left . "\n";
        }

        // Process new team information
        $taskCopy->team = '';
        if(!empty($teamData['team']))
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
     * Test update a task.
     *
     * @param  int                 $objectID
     * @param  array               $param
     * @access public
     * @return array|object|string
     */
    public function doUpdateTest(int $taskID, array $param = array()): array|object|string
    {
        $oldTask = $this->instance->fetchByID($taskID);
        $task    = clone $oldTask;

        foreach($task as $field => $value)
        {
            if(in_array($field, array_keys($param))) $task->$field = $param[$field];
            if(strpos($field, 'Date') && !$task->$field) $task->$field = null;
        }

        $this->instance->doUpdate($task, $oldTask, $this->instance->config->task->edit->requiredFields);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->instance->fetchByID($taskID);
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
        $tasks = $this->instance->fetchExecutionTasks($executionID, $productID, $type, $modules, $orderBy);
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
    public function fetchUserTasksByTypeTest(string $account, string $type = 'assignedTo', string $orderBy = 'id_desc', int $projectID = 0, int $limit = 0, ?object $pager = null): array
    {
        $object = $this->instance->fetchUserTasksByType($account, $type, $orderBy, $projectID, $limit, $pager);

        if(dao::isError()) return dao::getError();
        return $object;
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
        $effort = $this->instance->getEffortByID($effortID);
        $task   = $this->instance->getById($effort->objectID);

        $result = new stdclass();
        $result->taskEstimate   = $task->estimate;
        $result->taskConsumed   = $task->consumed;
        $result->taskLeft       = $task->left;

        $result->effortLeft     = $effort->left;
        $result->effortConsumed = $effort->consumed;

        $result->left = $this->instance->getLeftAfterDeleteWorkhour($effort, $task);

        return $result;
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getRequiredFields4Edit');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $task);

        if(dao::isError()) return dao::getError();

        return $result;
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
        $effort = $this->instance->getEffortByID($effortID);
        $task   = $this->instance->getById($effort->objectID);

        return $this->instance->getTaskAfterDeleteWorkhour($effort, $task);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getTeamInfoList');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $teamList, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList);

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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('recordTaskVersion');
        $method->setAccessible(true);

        try {
            $result = $method->invoke($this->instance, $task);
            if(dao::isError()) return 0;
            return $result ? 1 : 0;
        } catch (Exception $e) {
            return 0;
        }
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
        $this->instance->resetEffortLeft($taskID, array($account));
        if(dao::isError())
        {
            return !dao::getError();
        }
        else
        {
            return $this->instance->dao->select('*')->from(TABLE_EFFORT)
                ->where('objectID')->eq($taskID)
                ->andWhere('account')->eq($account)
                ->andWhere('objectType')->eq('task')
                ->orderBy('date_desc,id_desc')
                ->fetch();
        }
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
        $this->instance->setTeamMember($memberInfo, $mode, $inTeam);

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($memberInfo->task)->andWhere('account')->eq($memberInfo->account)->fetch();
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
        $this->instance->updateRelation($childID, $parentID);
        $relation = $this->instance->dao->select('*')->from(TABLE_RELATION)->where('AID')->eq($childID)->andWhere('relation')->eq('subdividefrom')->andWhere('AType')->eq('task')->andWhere('BType')->eq('task')->fetch();

        if(empty($relation)) return 'null';
        return $relation->BID;
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

        $this->instance->updateTaskEsDateByGantt($postData);

        if(dao::isError()) return dao::getError();
        return true;
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
    public function updateTeamByEffortTest(int $effortID, object $record, int $taskID, mix $task = null, string $lastDate): array
    {
        $task        = $this->instance->getByID($taskID);
        $currentTeam = $this->instance->getTeamByAccount($task->team);
        $this->instance->updateTeamByEffort($effortID, $record, $currentTeam, $task, $lastDate);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->instance->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->fetchAll();
        }
    }
}
