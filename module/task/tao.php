<?php
declare(strict_types=1);
/**
 * The tao file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

class taskTao extends taskModel
{
    /**
     * 计算任务列表中每个任务的进度，包括子任务。
     * Compute progress of task list, include its' children.
     *
     * @param  object[]  $tasks
     * @access protected
     * @return object[]
     */
    protected function batchComputeProgress(array $tasks): array
    {
        foreach($tasks as $task)
        {
            $task->progress = $this->computeTaskProgress($task);
            if(empty($task->children)) continue;

            $task->children = $this->batchComputeProgress($task->children);
        }

        return $tasks;
    }

    /**
     * 根据填写的日志，记录历史记录、改变任务的状态、消耗工时等信息并返回。
     * According to the effort, record the history, change the status of the task, consumed hours and other information and return.
     *
     * @param  object    $record
     * @param  object    $task
     * @param  string    $lastDate
     * @param  bool      $isFinishTask
     * @access protected
     * @return array
     */
    protected function buildTaskForEffort(object $record, object $task, string $lastDate, bool $isFinishTask): array
    {
        $this->loadModel('action');
        $now = helper::now();

        $actionID = 0;
        $newTask  = clone $task;
        $newTask->consumed      += $record->consumed;
        $newTask->lastEditedBy   = $this->app->user->account;
        $newTask->lastEditedDate = $now;
        if(!$task->realStarted) $newTask->realStarted = $now;

        if($lastDate <= $record->date) $newTask->left = $record->left;

        /* Finish task by effort. */
        if(!$newTask->left && $isFinishTask)
        {
            $newTask->status         = 'done';
            $newTask->assignedTo     = $task->openedBy;
            $newTask->assignedDate   = $now;
            $newTask->finishedBy     = $this->app->user->account;
            $newTask->finishedDate   = $now;
            $actionID = $this->action->create('task', $task->id, 'Finished', $record->work);
        }
        /* Start task by effort. */
        elseif($newTask->status == 'wait')
        {
            $newTask->status       = 'doing';
            $newTask->assignedTo   = $this->app->user->account;
            $newTask->assignedDate = $now;
            $actionID = $this->action->create('task', $task->id, 'Started', $record->work);
        }
        /* Activate task by effort. */
        elseif($newTask->left != 0 && strpos('done,pause,cancel,closed,pause', $task->status) !== false)
        {
            $newTask->status         = 'doing';
            $newTask->assignedTo     = $this->app->user->account;
            $newTask->finishedBy     = '';
            $newTask->canceledBy     = '';
            $newTask->closedBy       = '';
            $newTask->closedReason   = '';
            $newTask->finishedDate   = null;
            $newTask->canceledDate   = null;
            $newTask->closedDate     = null;
            $actionID = $this->action->create('task', $task->id, 'Activated', $record->work);
        }
        else
        {
            $actionID = $this->action->create('task', $task->id, 'RecordWorkhour', $record->work, (float)$record->consumed);
        }

        return array($newTask, $actionID);
    }

    /**
     * 编辑日志后，构造待更新的任务数据。
     * After editing the effort, build the task data to be updated.
     *
     * @param  object      $task
     * @access protected
     * @return array|false
     */
    protected function buildTaskForUpdateEffort(object $task, object $oldEffort, object $effort): object
    {
        $lastEffort = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('objectID')->eq($task->id)
            ->andWhere('objectType')->eq('task')
            ->orderBy('date_desc,id_desc')->limit(1)->fetch();

        $consumed = $task->consumed + $effort->consumed - $oldEffort->consumed;
        $left     = ($lastEffort && $effort->id == $lastEffort->id) ? $effort->left : $lastEffort->left;

        $now  = helper::now();
        $data = new stdclass();
        $data->consumed       = $consumed;
        $data->left           = $left;
        $data->status         = $task->status;
        $data->lastEditedBy   = $this->app->user->account;
        $data->lastEditedDate = $now;
        if(empty($left) && strpos('wait,doing,pause', $task->status) !== false)
        {
            $data->status       = 'done';
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;
            $data->assignedTo   = $task->openedBy;
        }

        return $data;
    }

    /**
     * 将任务的层级改为父子结构。
     * Change the hierarchy of tasks to a parent-child structure.
     *
     * @param  object[]  $tasks
     * @param  object[]  $parentTasks
     * @access protected
     * @return object[]
     */
    protected function buildTaskTree(array $tasks, array $parentTasks): array
    {
        foreach($tasks as $task)
        {
            /* 如果不是父任务则跳过。*/
            if($task->parent <= 0) continue;

            if(isset($tasks[$task->parent]))
            {
                /* 如果任务列表里有这个任务的父任务，则将子任务放到父任务里，并删除子任务。*/
                if(!isset($tasks[$task->parent]->children)) $tasks[$task->parent]->children = array();
                $tasks[$task->parent]->children[$task->id] = $task;
                unset($tasks[$task->id]);
            }
            else
            {
                /* 如果任务列表里没有这个任务的父任务，则从父任务列表获取到父任务名称并附加到子任务上。*/
                $parent = $parentTasks[$task->parent];
                $task->parentName = $parent->name;
            }
        }
        return $tasks;
    }

    /**
     * 构造日志数据，加上任务ID、记录人字段。
     * Add fields to workhour.
     *
     * @param  int       $taskID
     * @param  array     $workhour
     * @access protected
     * @return array
     */
    protected function buildWorkhour(int $taskID, array $workhour): array
    {
        foreach($workhour as $record)
        {
            $record->task    = $taskID;
            $record->account = $this->app->user->account;
        }

        return $workhour;
    }

    /**
     * 编辑日志时，检查输入是否合法。
     * When editing a effort, check that the input is legal.
     *
     * @param  object  $effort
     * @access protected
     * @return bool
     */
    protected function checkEffort(object $effort): bool
    {
        $today = helper::today();
        if(helper::isZeroDate($effort->date)) dao::$errors['date']     = $this->lang->task->error->dateEmpty;
        if($effort->date > $today)            dao::$errors['date']     = $this->lang->task->error->date;
        if($effort->consumed <= 0)            dao::$errors['comsumed'] = sprintf($this->lang->error->gt, $this->lang->task->record, '0');
        if($effort->left < 0)                 dao::$errors['left']     = sprintf($this->lang->error->ge, $this->lang->task->left, '0');

        return !dao::isError();
    }

    /**
     * 检查一个任务是否有子任务。
     * Check if a task has children.
     *
     * @param  int       $taskID
     * @access protected
     * @return bool
     */
    protected function checkHasChildren(int $taskID): bool
    {
        $childrenCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetch('count');
        if(!$childrenCount) return false;
        return true;
    }

    /**
     * 获取任务的进度，通过任务的消耗和剩余工时计算，结果以百分比的数字部分显示。
     * Compute progress of a task.
     *
     * @param  object    $task
     * @access protected
     * @return float
     */
    protected function computeTaskProgress(object $task): float
    {
        if($task->left > 0) return round($task->consumed / ((float)$task->consumed + (float)$task->left), 2) * 100;
        if($task->consumed == 0) return 0;
        return 100;
    }

    /**
     *  计算当前任务的状态。
     *  Compute the status of the current task.
     *
     * @param  object    $currentTask
     * @param  object    $oldTask
     * @param  object    $task
     * @param  bool      $autoStatus  true|false
     * @param  bool      $hasEfforts  true|false
     * @param  array     $members
     * @access protected
     * @return object
     */
    protected function computeTaskStatus(object $currentTask, object $oldTask, object $task, bool $autoStatus, bool $hasEfforts, array $members): object
    {
        /* If the status is not automatic, return the current task. */
        if(!$autoStatus) return $currentTask;

        /* If consumed of the current task is empty and current task has no efforts, the current task status should be wait. */
        if($currentTask->consumed == 0 && !$hasEfforts)
        {
            if(!isset($task->status)) $currentTask->status = 'wait';
            $currentTask->finishedBy   = '';
            $currentTask->finishedDate = null;
        }

        /* If neither consumed nor left of the current task is empty, the current task status should be doing. */
        if($currentTask->consumed > 0 && $currentTask->left > 0)
        {
            $currentTask->status       = 'doing';
            $currentTask->finishedBy   = '';
            $currentTask->finishedDate = null;
        }

        /* If consumed of the current task is not empty and left of the current task is empty, the current task status should be done or doing. */
        if($currentTask->consumed > 0 && $currentTask->left == 0)
        {
            $finishedUsers = $this->getFinishedUsers($oldTask->id, $members);
            /* If the number of finisher is less than the number of team members , the current task status should be doing. */
            if(count($finishedUsers) != count($members))
            {
                if(strpos('cancel,pause', $oldTask->status) === false || ($oldTask->status == 'closed' && $oldTask->reason == 'done'))
                {
                    $currentTask->status       = 'doing';
                    $currentTask->finishedBy   = '';
                    $currentTask->finishedDate = null;
                }
            }
            /* If status of old task is wait or doing or pause, the current task status should be done. */
            elseif(strpos('wait,doing,pause', $oldTask->status) !== false)
            {
                $currentTask->status       = 'done';
                $currentTask->assignedTo   = $oldTask->openedBy;
                $currentTask->assignedDate = helper::now();
                $currentTask->finishedBy   = $this->app->user->account;
                $currentTask->finishedDate = zget($task, 'finishedDate', null);
            }
        }

        return $currentTask;
    }

    /**
     * 拼接团队成员信息，包括账号、预计、消耗、剩余，用来创建历史记录。例如：团队成员: admin, 预计: 2, 消耗: 0, 剩余: 3。
     * Concat team info for create history.
     *
     * @param  array     $teamInfoList
     * @param  array     $userPairs
     * @access protected
     * @return string
     */
    protected function concatTeamInfo(array $teamInfoList, array $userPairs): string
    {
        $teamInfo = '';
        foreach($teamInfoList as $info) $teamInfo .= "{$this->lang->task->teamMember}: " . zget($userPairs, $info->account) . ", {$this->lang->task->estimateAB}: " . (float)$info->estimate . ", {$this->lang->task->consumedAB}: " . (float)$info->consumed . ", {$this->lang->task->leftAB}: " . (float)$info->left . "\n";
        return $teamInfo;
    }

    /**
     * 复制任务数据。
     * Copy the task data and update the effort to the new task.
     *
     * @param  object $parentTask
     * @access public
     * @return bool
     */
    public function copyTaskData(object $task): bool
    {
        /* 复制当前任务信息。 */
        /* Copy the current task to child task, and change the parent field value. */
        $copyTask = clone $task;
        $copyTask->parent   = $task->id;
        $copyTask->feedback = 0;
        unset($copyTask->id);

        if($this->config->edition != 'open')
        {
            $fieldList = $this->loadModel('workflowfield')->getList('task');
            foreach($fieldList as $field)
            {
                if($field->type == 'date' || $field->type == 'datetime') $this->config->task->dateFields[] = $field->field;
            }
        }
        foreach($this->config->task->dateFields as $dateField)
        {
            if(empty($copyTask->$dateField)) unset($copyTask->$dateField);
        }

        $this->dao->insert(TABLE_TASK)->data($copyTask)->autoCheck()->exec();
        $copyTaskID = $this->dao->lastInsertID();

        if(dao::isError()) return false;

        $path = $copyTask->path . "{$copyTaskID},";
        $this->dao->update(TABLE_TASK)->set('path')->eq($path)->where('id')->eq($copyTaskID)->exec();
        if(dao::isError()) return false;

        /* 将父任务的日志记录更新到子任务下。 */
        /* Update the logs of the parent task under the subtask. */
        $this->dao->update(TABLE_EFFORT)->set('objectID')->eq($copyTaskID)
            ->where('objectID')->eq($task->id)
            ->andWhere('objectType')->eq('task')
            ->exec();

        return !dao::isError();
    }

    /**
     * 多人任务的团队变更后，记录对比历史记录。
     * Update a task.
     *
     * @param  object    $oldTask
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function createChangesForTeam(object $oldTask, object $task): array
    {
        $users = $this->loadModel('user')->getPairs('noletter|noempty');

        $oldTeams    = $oldTask->team;
        $oldTeamInfo = array();
        foreach($oldTeams as $team) $oldTeamInfo[$team->account][] = "{$this->lang->task->teamMember}: " . zget($users, $team->account) . ", {$this->lang->task->estimateAB}: " . (float)$team->estimate . ", {$this->lang->task->consumedAB}: " . (float)$team->consumed . ", {$this->lang->task->leftAB}: " . (float)$team->left;

        $newTeamInfo = array();
        foreach($this->post->team as $i => $account)
        {
            if(empty($account)) continue;
            $newTeamInfo[$account][] = "{$this->lang->task->teamMember}: " . zget($users, $account) . ", {$this->lang->task->estimateAB}: " . zget($this->post->teamEstimate, $i, 0) . ", {$this->lang->task->consumedAB}: " . zget($this->post->teamConsumed, $i, 0) . ", {$this->lang->task->leftAB}: " . zget($this->post->teamLeft, $i, 0);
        }

        /* 只保留有变化的部分, 方便对比。*/
        $oldTask->team = '';
        $task->team    = '';
        foreach($oldTeamInfo as $account => $infos)
        {
            foreach($infos as $index => $info)
            {
                if(isset($newTeamInfo[$account][$index]) && $newTeamInfo[$account][$index] == $info)
                {
                    unset($newTeamInfo[$account][$index]);
                }
                else
                {
                    $oldTask->team .= $info . "\n";
                }
            }
        }

        foreach($newTeamInfo as $account => $infos)
        {
            foreach($infos as $info)
            {
                $task->team .= $info . "\n";
            }
        }

        return array($oldTask, $task);
    }

    /**
     * 更新自动更新任务状态时记录修改日志。
     * Create action record when auto update task.
     *
     * @param  object    $oldTask
     * @param  string    $source   parent|child
     * @access protected
     * @return void
     */
    protected function createAutoUpdateTaskAction(object $oldTask, string $source = 'parent') :void
    {
        $newTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldTask->id)->fetch();

        unset($oldTask->subStatus);
        unset($newTask->subStatus);

        $changes = common::createChanges($oldTask, $newTask);
        $action  = '';

        if($newTask->status == 'done'   && $oldTask->status != 'done')   $action = 'Finished';
        if($newTask->status == 'closed' && $oldTask->status != 'closed') $action = 'Closed';
        if($newTask->status == 'pause'  && $oldTask->status != 'pause')  $action = 'Paused';
        if($newTask->status == 'cancel' && $oldTask->status != 'cancel') $action = 'Canceled';
        if($newTask->status == 'doing'  && $oldTask->status == 'wait')   $action = 'Started';
        if($newTask->status == 'doing'  && $oldTask->status == 'pause')  $action = 'Restarted';
        if($newTask->status == 'wait'   && $oldTask->status != 'wait')   $action = 'Adjusttasktowait';
        if($newTask->status == 'doing'  && $oldTask->status != 'wait' && $oldTask->status != 'pause') $action = 'Activated';

        if(!$action) return;

        $actionID = $this->loadModel('action')->create('task', $oldTask->id, $action, '', 'autoby' . $source, '', false);
        $this->action->logHistory($actionID, $changes);
    }

    /**
     * 更新一个任务。
     * Update a task.
     *
     * @param  object    $task
     * @param  object    $oldTask
     * @param  string    $requiredFields
     * @access protected
     * @return bool
     */
    protected function doUpdate(object $task, object $oldTask, string $requiredFields): bool
    {
        /* Task link design. */
        if(!empty($task->design))
        {
            $design = $this->dao->select('version')->from(TABLE_DESIGN)->where('id')->eq($task->design)->fetch();
            $task->designVersion = $design->version;
        }

        $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();
        if($this->isNoStoryExecution($execution)) $task->story = 0;

        /* Set the datetime and operator when the task is modified. */
        if(empty($task->lastEditedDate) || empty($task->lastEditedBy))
        {
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = helper::now();
        }

        if(isset($task->estimate)) $task->estimate = round((float)$task->estimate, 2);
        if(isset($task->consumed)) $task->consumed = round((float)$task->consumed, 2);
        if(isset($task->left))     $task->left     = round((float)$task->left, 2);

        $this->dao->update(TABLE_TASK)->data($task, 'deleteFiles,renameFiles,files,docVersions,oldDocs,docs')
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $requiredFields, 'notempty')
            ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
            ->batchCheckIF($task->status == 'wait' || $task->status == 'doing', 'finishedBy,finishedDate,canceledBy,canceledDate,closedBy,closedDate,closedReason', 'empty')
            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->batchCheckIF($task->status == 'done', 'canceledBy,canceledDate', 'empty')
            ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy,finishedDate,closedBy,closedDate,closedReason', 'empty')
            ->checkFlow()
            ->where('id')->eq($task->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * 获取执行下的任务。
     * Fetch tasks under execution by executionID(Todo).
     *
     * @param  int|array    $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  object       $pager
     * @access protected
     * @return object[]
     */
    protected function fetchExecutionTasks(int|array $executionID, int $productID = 0, string|array $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', ?object $pager = null): array
    {
        if(is_string($type)) $type = strtolower($type);
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $orderBy = str_replace('status', 'statusOrder', $orderBy);
        $fields  = "DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, INSTR('wait,doing,done,pause,cancel,closed,', t1.status) as statusOrder";
        ($this->config->edition == 'max' or $this->config->edition == 'ipd') && $fields .= ', t5.name as designName, t5.version as latestDesignVersion';

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($this->app->user->account)->fetchPairs('objectID', 'objectID');

        if(is_numeric($executionID) && $executionID) $execution = $this->fetchByID($executionID, 'project');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t3')->on('t3.task = t1.id')
            ->beginIF($productID)->leftJoin(TABLE_MODULE)->alias('t4')->on('t1.module = t4.id')->fi()
            ->beginIF($this->config->edition == 'max' or $this->config->edition == 'ipd')->leftJoin(TABLE_DESIGN)->alias('t5')->on('t1.design= t5.id')->fi()
            ->where('t1.execution')->in($executionID)
            ->beginIF(is_numeric($executionID) && !empty($execution->isTpl))->andWhere('t1.isTpl')->eq('1')->fi()
            ->beginIF($type == 'myinvolved')
            ->andWhere("((t3.`account` = '{$this->app->user->account}') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
            ->fi()
            ->beginIF($productID)->andWhere("((t4.root=" . (int)$productID . " and t4.type='story') OR t2.product=" . (int)$productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t3.`account` = '{$this->app->user->account}' and t1.status != 'closed' and t3.status != 'done') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($this->app->user->account)
            ->orWhere('t3.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id', false);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', !($productID || in_array($type, array('myinvolved', 'needconfirm', 'assignedtome', 'finishedbyme'))));

        return $tasks;
    }

    /**
     * 通过任务类型查找用户的任务。
     * Fetch user tasks by type.
     *
     * @param  string      $account
     * @param  string      $type      assignedTo|finishedBy|closedBy
     * @param  string      $orderBy
     * @param  int         $projectID
     * @param  int         $limit
     * @param  object|null $pager
     * @access protected
     * @return object[]
     */
    protected function fetchUserTasksByType(string $account, string $type, string $orderBy, int $projectID, int $limit, object|null $pager): array
    {
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $orderBy = str_replace('project_', 't1.project_', $orderBy);

        return $this->dao->select("t1.*, t4.id AS project, t2.id AS executionID, t2.name AS executionName, t4.name AS projectName, t2.multiple AS executionMultiple, t2.type AS executionType, t3.id AS storyID, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t2.project = t4.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t5.task = t1.id and t5.account = '{$account}'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF($type != 'closedBy' && $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'finishedBy')
            ->andWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t5.status')->eq('done')
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'assignedTo' && ($this->app->rawModule == 'my' || $this->app->rawModule == 'block'))->andWhere('t2.status', true)->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)->fi()
            ->beginIF(!in_array($type, array('all', 'finishedBy', 'assignedTo', 'myInvolved')))->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'assignedTo')->andWhere("((t1.assignedTo = '{$account}') or (t1.mode = 'multi' and t5.`account` = '{$account}' and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->beginIF($type == 'assignedTo' && $this->app->rawModule == 'my' && $this->app->rawMethod == 'work')->andWhere('t1.status')->notin('closed,cancel')->fi()
            ->beginIF($type == 'myInvolved')
            ->andWhere("((t5.`account` = '{$this->app->user->account}') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
            ->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id', false);
    }

    /**
     * Format the task with valid datetime that set the zero date or datetime to null.
     *
     * @param  object $task
     * @return object
     */
    protected function formatDatetime(object $task): object
    {
        if(empty($task)) return $task;
        if($this->config->edition != 'open')
        {
            $fieldList = $this->loadModel('workflowfield')->getList('task');
            foreach($fieldList as $field)
            {
                if($field->type == 'date' || $field->type == 'datetime') $this->config->task->dateFields[] = $field->field;
            }
        }
        foreach($task as $key => $value)
        {
            if(!in_array($key, $this->config->task->dateFields)) continue;
            if(!empty($value) && is_string($value) && helper::isZeroDate($value)) $task->$key = null;
            if(isset($task->$key) && empty($value)) $task->$key = null;
        }
        return $task;
    }

    /**
     * 获取多人任务的完成者。
     * Get the users who finished the multiple task.
     *
     * @param  int    $taskID
     * @param  array  $team
     * @access protected
     * @return array
     */
    protected function getFinishedUsers(int $taskID = 0, array $team = array()): array
    {
        return $this->dao->select('id,account')->from(TABLE_TASKTEAM)
            ->where('task')->eq($taskID)
            ->andWhere('status')->in('done,closed')
            ->beginIF($team)->andWhere('account')->in($team)->fi()
            ->fetchPairs('id', 'account');
    }

    /**
     * Get left workhour of task after deleting a workhour of task.
     *
     * @param  object $effort
     * @param  object $task
     * @return float
     */
    protected function getLeftAfterDeleteWorkhour(object $effort, object $task): float
    {
        $left = $task->left;
        if($effort->isLast)
        {
            $lastTwoEfforts = $this->dao->select('`left`')->from(TABLE_EFFORT)
                ->where('objectID')->eq($effort->objectID)
                ->andWhere('objectType')->eq('task')
                ->andWhere('deleted')->eq('0')
                ->orderBy('date desc,id desc')->limit(2)->fetchAll();
            $lastTwoEfforts  = isset($lastTwoEfforts[1]) ? $lastTwoEfforts[1] : '';
            if($lastTwoEfforts) $left = $lastTwoEfforts->left;
            if(empty($lastTwoEfforts) && $left == 0) $left = $task->estimate;
        }

        /* 如果该任务是多人团队任务则做一些额外的处理。*/
        if(empty($task->team)) return (float)$left;

        /* 获取要删除的工时的团队，如果要删除的工时的用户不是团队成员则不做任何处理。*/
        $currentTeam = $this->getTeamByAccount($task->team, $effort->account, array('effortID' => $effort->id, 'order' => $effort->order));
        if(!$currentTeam) return $left;

        $left = $currentTeam->left;
        if($task->mode == 'multi') /* 如果任务是多人并行任务。注：多人串行任务的mode是linear。*/
        {
            /* 获取要删除的工时对应的用户的工时信息列表。*/
            $accountEfforts = $this->getTaskEfforts($currentTeam->task, $effort->account, $effort->id);
            $lastEffort     = array_pop($accountEfforts);
            if($lastEffort->id == $effort->id)
            {
                $lastTwoEfforts = array_pop($accountEfforts);
                if($lastTwoEfforts) $left = $lastTwoEfforts->left;
            }
        }

        /* 更新要删除的工时对应的用户的任务信息。*/
        $newTeamInfo = new stdclass();
        $newTeamInfo->consumed = $currentTeam->consumed - $effort->consumed;
        if($currentTeam->status != 'done') $newTeamInfo->left = $left;
        if($currentTeam->status == 'done' && $left > 0 && $task->mode == 'multi')
        {
            $newTeamInfo->status = 'doing';
            $newTeamInfo->left = $left;
        }

        if($currentTeam->status != 'done' && $newTeamInfo->consumed > 0 && $left == 0) $newTeamInfo->status = 'done';
        if($task->mode == 'multi' && $currentTeam->status == 'done' && ($newTeamInfo->consumed == 0 && $left == 0))
        {
            $newTeamInfo->status = 'doing';
            $newTeamInfo->left   = $currentTeam->estimate;
        }
        $this->dao->update(TABLE_TASKTEAM)->data($newTeamInfo)->where('id')->eq($currentTeam->id)->exec();
        return (float)$left;
    }

    /**
     * 根据报表条件查询任务.
     * Get task list by report.
     *
     * @param  string    $field
     * @param  string    $condition
     * @access protected
     * @return object[]
     */
    protected function getListByReportCondition(string $field, string $condition): array
    {
        return $this->dao->select("id,{$field}")->from(TABLE_TASK)->where($condition)->fetchAll('id');
    }

    /**
     * 查询当前任务作为父任务时的状态。
     * Get the status of the taskID as a parent task.
     *
     * @param  int       $taskID
     * @access protected
     * @return string
     */
    protected function getParentStatusById(int $taskID) :string
    {
        $childrenStatus = $this->dao->select('id,status,parent')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('deleted')->eq('0')->fetchPairs('status', 'status');
        if(empty($childrenStatus)) return '';

        unset($childrenStatus['closed']);
        if(count($childrenStatus) == 0) return 'closed';

        unset($childrenStatus['cancel']);
        if(count($childrenStatus) == 0) return 'cancel';

        if(count($childrenStatus) == 1) return current($childrenStatus);

        if(isset($childrenStatus['doing']) || isset($childrenStatus['pause']) || isset($childrenStatus['wait'])) return 'doing';
        if(isset($childrenStatus['done'])) return 'done';

        return '';
    }

    /**
     * 如果任务是从Bug转来的，并且已经完成了，则获取提醒bug的链接。
     * Get a link to locate the bug if the task was transferred from the bug and it has already been finished.
     *
     * @param  object    $task
     * @param  array     $changes
     * @access protected
     * @return array
     */
    protected function getRemindBugLink(object $task, array $changes): array
    {
        foreach($changes as $change)
        {
            if($change['field'] == 'status' && $change['new'] == 'done')
            {
                $confirmURL = helper::createLink('bug', 'view', "id={$task->fromBug}");
                return array('result' => 'success', 'load' => true, 'callback' => "zui.Modal.confirm('" . sprintf($this->lang->task->remindBug, $task->fromBug) . "').then((res) => {if(res) loadModal('{$confirmURL}')});", 'closeModal' => true);
            }
        }

        return array();
    }

    /**
     * 获取edit方法的必填项。
     * Get required fields for edit method.
     *
     * @param  object    $task
     * @access protected
     * @return string
     */
    protected function getRequiredFields4Edit(object $task): string|bool
    {
        $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();

        $requiredFields = ',' . $this->config->task->edit->requiredFields . ',';
        if($this->isNoStoryExecution($execution)) $requiredFields = str_replace(',story,', ',', $requiredFields);

        if(strpos(',doing,pause,', $task->status) && empty($task->left))
        {
            dao::$errors['left'] = sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]);
            return false;
        }

        return trim($requiredFields, ',');
    }

    /**
     * 获取团队成员以及他的预计、消耗、剩余工时。
     * Get team account,estimate,consumed and left info.
     *
     * @param  array     $teamList
     * @param  array     $teamSourceList
     * @param  array     $teamEstimateList
     * @param  array     $teamConsumedList
     * @param  array     $teamLeftList
     * @access protected
     * @return object[]
     */
    protected function getTeamInfoList(array $teamList, array $teamSourceList, array $teamEstimateList, array $teamConsumedList, array $teamLeftList): array
    {
        $teamInfoList = array();
        foreach($teamList as $index => $account)
        {
            if(empty($account)) continue;

            $teamInfo = new stdclass();
            $teamInfo->account  = $account;
            $teamInfo->source   = $teamSourceList[$index];
            $teamInfo->estimate = $teamEstimateList[$index];
            $teamInfo->consumed = $teamConsumedList[$index];
            $teamInfo->left     = $teamLeftList[$index];

            $teamInfoList[$index] = $teamInfo;
        }

        return $teamInfoList;
    }

    /**
     * 维护团队成员信息。
     * Maintain team member information.
     *
     * @param  object    $member
     * @param  string    $mode   multi|linear
     * @param  bool      $inTeam
     * @access protected
     * @return bool
     */
    protected function setTeamMember(object $member, string $mode, bool $inTeam): bool
    {
        if($mode == 'multi' && $inTeam)
        {
            $this->dao->update(TABLE_TASKTEAM)
                ->beginIF($member->estimate)->set("estimate= estimate + {$member->estimate}")->fi()
                ->beginIF($member->left)->set("`left` = `left` + {$member->left}")->fi()
                ->beginIF($member->consumed)->set("`consumed` = `consumed` + {$member->consumed}")->fi()
                ->where('task')->eq($member->task)
                ->andWhere('account')->eq($member->account)
                ->exec();
        }
        else
        {
            $this->dao->insert(TABLE_TASKTEAM)->data($member)->autoCheck()->exec();
        }
        return !dao::isError();
    }

    /**
     * 通过任务ID列表查询任务团队信息。
     * Get task team by id list.
     *
     * @param  array     $taskIdList
     * @access protected
     * @return array[]
     */
    protected function getTeamMembersByIdList(array $taskIdList): array
    {
        return $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task');
    }

    /**
     * Get a new task after deleting a workhour of task.
     *
     * @param  object $effort
     * @param  object $task
     * @return object
     */
    protected function getTaskAfterDeleteWorkhour(object $effort, object $task)
    {
        /* Compute the left and consumed workhour of the task. */
        $consumed = $task->consumed - $effort->consumed;
        $left     = $this->getLeftAfterDeleteWorkhour($effort, $task);

        /* Define and prepare one new task object to update the task. */
        $data = new stdclass();
        $data->consumed = $consumed;
        $data->left     = $left;
        $data->status   = ($left == 0 && $consumed != 0) ? 'done' : $task->status;
        if($effort->isLast && $consumed == 0 && $task->status != 'wait')
        {
            $data->status       = 'wait';
            $data->left         = $task->estimate;
            $data->finishedBy   = '';
            $data->canceledBy   = '';
            $data->closedBy     = '';
            $data->closedReason = '';
            $data->finishedDate = null;
            $data->canceledDate = null;
            $data->closedDate   = null;
            if($task->assignedTo == 'closed') $data->assignedTo = $this->app->user->account;
        }
        elseif($effort->isLast && $left != 0 && strpos('done,pause,cancel,closed', $task->status) !== false)
        {
            $data->status         = 'doing';
            $data->finishedBy     = '';
            $data->canceledBy     = '';
            $data->closedBy       = '';
            $data->closedReason   = '';
            $data->finishedDate   = null;
            $data->canceledDate   = null;
            $data->closedDate     = null;
        }
        elseif($consumed != 0 && $left == 0 && strpos('done,pause,cancel,closed', $task->status) === false)
        {
            $now = helper::now();
            $data->status         = 'done';
            $data->assignedTo     = $task->openedBy;
            $data->assignedDate   = $now;
            $data->finishedBy     = $this->app->user->account;
            $data->finishedDate   = $now;
        }
        else
        {
            $data->status = $task->status;
        }

        return $data;
    }

    /**
     * 记录任务的版本。
     * Record task version.
     *
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function recordTaskVersion(object $task): bool
    {
        $taskSpec = new stdclass();
        $taskSpec->task       = $task->id;
        $taskSpec->version    = $task->version;
        $taskSpec->name       = $task->name;
        $taskSpec->estStarted = $task->estStarted;
        $taskSpec->deadline   = $task->deadline;
        $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * 将日志表中剩余设置为0。
     * Set effort left to 0.
     *
     * @param  int       $taskID
     * @param  array     $members
     * @access protected
     * @return bool
     */
    protected function resetEffortLeft(int $taskID, array $members): bool
    {
        foreach($members as $account)
        {
            $this->dao->update(TABLE_EFFORT)->set('`left`')->eq(0)->where('account')->eq($account)
                ->andWhere('objectID')->eq($taskID)
                ->andWhere('objectType')->eq('task')
                ->orderBy('date_desc,id_desc')
                ->limit('1')
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * 根据子任务以及父任务的状态更新父任务。
     * Update parent task status by child and parent status.
     *
     * @param  object    $task
     * @param  object    $childTask
     * @param  string    $status
     * @access protected
     * @return void
     */
    protected function autoUpdateTaskByStatus(object $task, object|null $childTask, string $status) :void
    {
        $now     = helper::now();
        $account = $this->app->user->account;
        $data    = new stdclass();
        $data->status = $status;

        if($status == 'done')
        {
            $data->assignedTo   = $task->openedBy;
            $data->assignedDate = $now;
            $data->finishedBy   = $account;
            $data->finishedDate = $now;
            $data->canceledBy   = '';
            $data->canceledDate = null;
        }

        if($status == 'cancel')
        {
            $data->assignedTo   = $task->openedBy;
            $data->assignedDate = $now;
            $data->finishedBy   = '';
            $data->finishedDate = null;
            $data->canceledBy   = $account;
            $data->canceledDate = $now;
        }

        if($status == 'closed')
        {
            $data->assignedTo   = 'closed';
            $data->assignedDate = $now;
            $data->closedBy     = $account;
            $data->closedDate   = $now;
            $data->closedReason = $task->status == 'cancel' ? 'cancel' : 'done';
        }

        if($status == 'doing' || $status == 'wait')
        {
            if($task->assignedTo == 'closed')
            {
                $data->assignedTo   = !empty($childTask) ? $childTask->assignedTo : $task->openedBy;
                $data->assignedDate = $now;
            }
            if($status == 'doing' && !empty($childTask->realStarted)) $data->realStarted = $childTask->realStarted;

            $data->finishedBy   = '';
            $data->finishedDate = null;
            $data->canceledBy   = '';
            $data->canceledDate = null;
            $data->closedBy     = '';
            $data->closedDate   = null;
            $data->closedReason = '';
        }

        $data->lastEditedBy   = $account;
        $data->lastEditedDate = $now;

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();
    }

    /**
     * 通过拖动甘特图修改任务的预计开始日期和截止日期。
     * Update task estimate date and deadline through gantt.
     *
     * @param  object    $postData
     * @access protected
     * @return bool
     */
    protected function updateTaskEsDateByGantt(object $postData): bool
    {
        $task        = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($postData->id)->fetch();
        $isChildTask = $task->parent > 0;

        if($isChildTask) $parentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();
        $stage = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->andWhere('project')->eq($task->project)->fetch();

        $start    = $isChildTask ? $parentTask->estStarted   : $stage->begin;
        $end      = $isChildTask ? $parentTask->deadline     : $stage->end;
        $typeLang = $isChildTask ? $this->lang->task->parent : $this->lang->project->stage;

        if(helper::diffDate($start, $postData->startDate) > 0) dao::$errors[] = sprintf($this->lang->task->overEsStartDate, $typeLang, $typeLang);
        if(helper::diffDate($end, $postData->endDate) < 0)     dao::$errors[] = sprintf($this->lang->task->overEsEndDate, $typeLang, $typeLang);
        if(dao::isError()) return false;

        /* Update estimate started and deadline of a task. */
        $this->dao->update(TABLE_TASK)
            ->set('estStarted')->eq($postData->startDate)
            ->set('deadline')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($postData->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * 通过填写的日志更新多人任务的团队表，计算多人任务的工时。
     * Update team of multi-task by effort.
     *
     * @param  int       $effortID
     * @param  object    $record
     * @param  object    $currentTeam
     * @param  object    $task
     * @access protected
     * @return void
     */
    protected function updateTeamByEffort(int $effortID, object $record, object $currentTeam, object $task, string $lastDate)
    {
        $this->dao->update(TABLE_TASKTEAM)
            ->set("consumed = consumed + {$record->consumed}")
            ->set('status')->eq($currentTeam->status)
            ->beginIF($record->date >= $lastDate)->set('left')->eq($record->left)->fi()
            ->where('id')->eq($currentTeam->id)
            ->exec();

        if($task->mode == 'linear' && empty($record->order)) $this->updateEffortOrder($effortID, $currentTeam->order);
    }

    /**
     * 更新父子任务关系。
     * Update relation of parent and child.
     *
     * @param  int       $childID
     * @param  int       $parentID
     * @access protected
     * @return void
     */
    protected function updateRelation(int $childID, int $parentID = 0): void
    {
        if(empty($childID)) return;

        $relation = $this->dao->select('*')->from(TABLE_RELATION)->where('AID')->eq($childID)->andWhere('relation')->eq('subdividefrom')->andWhere('AType')->eq('task')->andWhere('BType')->eq('task')->fetch();
        if($relation)
        {
            if($parentID && $relation->BID == $parentID) return;

            $this->dao->delete()->from(TABLE_RELATION)->where('BID')->eq($childID)->andWhere('relation')->eq('subdivideinto')->andWhere('AType')->eq('task')->andWhere('BType')->eq('task')->exec();
            $this->dao->delete()->from(TABLE_RELATION)->where('AID')->eq($childID)->andWhere('relation')->eq('subdividefrom')->andWhere('AType')->eq('task')->andWhere('BType')->eq('task')->exec();
        }

        if(empty($parentID)) return;

        $data = new stdclass();
        $data->AType     = 'task';
        $data->BType     = 'task';
        $data->AID       = $childID;
        $data->BID       = $parentID;
        $data->relation  = 'subdividefrom';
        $this->dao->insert(TABLE_RELATION)->data($data)->exec();

        $data->AID      = $parentID;
        $data->BID      = $childID;
        $data->relation = 'subdivideinto';
        $this->dao->insert(TABLE_RELATION)->data($data)->exec();
        return;
    }

    /**
     * 归并子任务到父任务下。
     * Merge child into parent
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function mergeChildIntoParent(array $tasks): array
    {
        $mergeTasks = function($parents, $parentID = 0) use(&$mergeTasks)
        {
            $tasks = array();
            if(!isset($parents[$parentID])) return $tasks;

            foreach($parents[$parentID] as $childTask)
            {
                $tasks[$childTask->id] = $childTask;
                if(isset($parents[$childTask->id])) $tasks += $mergeTasks($parents, $childTask->id);
            }
            return $tasks;
        };

        $parents = array();
        foreach($tasks as $task) $parents[$task->parent][$task->id] = $task;

        $mergedTasks = array();
        foreach($tasks as $task)
        {
            if(isset($mergedTasks[$task->id])) continue;
            if($task->parent && isset($tasks[$task->parent])) continue;

            $mergedTasks[$task->id] = $task;
            $mergedTasks           += $mergeTasks($parents, $task->id);
        }
        return $mergedTasks;
    }
}
