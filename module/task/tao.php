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
     * Compute progress of a task.
     * 获取任务的进度。
     *
     * @param  object   $task
     * @access protected
     * @return float
     */
    protected function computeTaskProgress(object $task): float
    {
        if($task->left != 0) return round($task->consumed / ($task->consumed + $task->left), 2) * 100;
        if($task->consumed == 0) return 0;
        return 100;
    }

    /**
     * Compute progress of task list, include its' children.
     * 计算任务列表中每个任务的进度，包括子任务。
     *
     * @param  object[] $tasks
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
     * Fetch tasks under execution by executionID(Todo).
     * 获取执行下的任务。
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  object       $pager
     * @access protected
     * @return object[]
     */
    protected function fetchExecutionTasks(int $executionID, int $productID = 0, string|array $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', object $pager = null): array
    {
        if(is_string($type)) $type = strtolower($type);
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $fields  = "DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";
        if($this->config->edition == 'max') $fields .= ', t6.name as designName, t6.version as latestDesignVersion';

        $currentAccount = $this->app->user->account;

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($currentAccount)->fetchPairs('objectID', 'objectID');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on('t4.task = t1.id')
            ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
            ->beginIF($this->config->edition == 'max')->leftJoin(TABLE_DESIGN)->alias('t6')->on('t1.design= t6.id')->fi()
            ->where('t1.execution')->eq($executionID)
            ->beginIF($type == 'myinvolved')->andWhere("((t4.`account` = '{$currentAccount}') OR t1.`assignedTo` = '{$currentAccount}' OR t1.`finishedby` = '{$currentAccount}')")->fi()
            ->beginIF($productID)->andWhere("((t5.root=" . $productID . " and t5.type='story') OR t2.product=" . $productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$currentAccount}' or (t1.mode = 'multi' and t4.`account` = '{$currentAccount}' and t1.status != 'closed' and t4.status != 'done') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($currentAccount)
            ->orWhere('t4.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'review')
            ->andWhere("FIND_IN_SET('{$currentAccount}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('myinvolved', 'needconfirm', 'assignedtome'))) ? false : true);

        return $tasks;
    }

    /**
     * Fetch user tasks by type.
     *
     * @param  string $account
     * @param  string $type      assignedTo|finishedBy|closedBy
     * @param  string $orderBy
     * @param  int    $projectID
     * @access protected
     * @return object[]
     */
    protected function fetchUserTasksByType(string $account, string $type, string $orderBy, int $projectID, int $limit, object|null $pager): array
    {
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $orderBy = str_replace('project_', 't1.project_', $orderBy);

        return $this->dao->select("t1.*, t4.id as project, t2.id as executionID, t2.name as executionName, t4.name as projectName, t2.multiple as executionMultiple, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on("t2.project = t4.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t5.task = t1.id and t5.account = '{$account}'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'finishedBy')
            ->andWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t5.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'assignedTo' and ($this->app->rawModule == 'my' or $this->app->rawModule == 'block'))->andWhere('t2.status', true)->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)->fi()
            ->beginIF($type != 'all' and $type != 'finishedBy' and $type != 'assignedTo')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'assignedTo')->andWhere("(t1.assignedTo = '{$account}' or (t1.mode = 'multi' and t5.`account` = '{$account}' and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->beginIF($type == 'assignedTo' and $this->app->rawModule == 'my' and $this->app->rawMethod == 'work')->andWhere('t1.status')->notin('closed,cancel')->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id');
    }

    /**
     * Get task team by id list.
     * 通过任务ID列表查询任务团队信息。
     *
     * @param  array      $taskIdList
     * @access protected
     * @return object[]
     */
    protected function getTeamMembersByIdList(array $taskIdList): array
    {
        return $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task');
    }

    /**
     * Get task list by report.
     * 根据报表条件查询任务.
     *
     * @param  string $field
     * @param  string $condition
     * @access public
     * @return object[]
     */
    protected function getListByReportCondition(string $field, string $condition): array
    {
        return $this->dao->select("id,{$field}")->from(TABLE_TASK)
                ->where($condition)
                ->fetchAll('id');
    }

    /**
     * Get the assignedTo for the multiply linear task.
     * 获取多人串行任务的指派人。
     *
     * @param  string|array $members
     * @param  object       $task
     * @param  string       $type current|next
     * @access protected
     * @return string
     */
    protected function getAssignedTo4Multi(string|array $members, object $task, string $type = 'current'): string
    {
        if(empty($task->team) or $task->mode != 'linear') return $task->assignedTo;

        /* Format task team members. */
        if(!is_array($members)) $members = explode(',', trim($members, ','));
        $members = array_values($members);
        if(is_object($members[0])) $members = array_map(function($member){return $member->account;}, $members);

        /* Get the member of the first unfinished task. */
        $teamHours = array_values($task->team);
        foreach($members as $i => $account)
        {
            if(isset($teamHours[$i]) and $teamHours[$i]->status == 'done') continue;
            if($type == 'current') return $account;
            break;
        }

        /* Get the member of the second unfinished task. */
        if($type == 'next' and isset($members[$i + 1])) return $members[$i + 1];

        return $task->openedBy;
    }

    /**
     * Change the hierarchy of tasks to a parent-child structure.
     * 将任务的层级改为父子结构。
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
            if($task->parent <= 0) continue;
            if(isset($tasks[$task->parent]))
            {
                if(!isset($tasks[$task->parent]->children)) $tasks[$task->parent]->children = array();
                $tasks[$task->parent]->children[$task->id] = $task;
                unset($tasks[$task->id]);
            }
            else
            {
                $parent = $parentTasks[$task->parent];
                $task->parentName = $parent->name;
            }
        }
        return $tasks;
    }

    /**
     *  Compute the status of the current task.
     *  计算当前任务的状态。
     *
     * @param  object $currentTask
     * @param  object $oldTask
     * @param  object $task
     * @param  bool   $condition  true|false
     * @param  bool   $hasEfforts true|false
     * @param  int    $teamCount
     * @access protected
     * @return object
     */
    protected function computeCurrentTaskStatus(object $currentTask, object $oldTask, object $task, bool $autoStatus, bool $hasEfforts, array $members): object
    {
        if(!$autoStatus) return $currentTask;

        if($currentTask->consumed == 0 and $hasEfforts)
        {
            if(!isset($task->status)) $currentTask->status = 'wait';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        if($currentTask->consumed > 0 && $currentTask->left > 0)
        {
            $currentTask->status       = 'doing';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        if($currentTask->consumed > 0 and $currentTask->left == 0)
        {
            $finisedUsers = $this->getFinishedUsers($oldTask->id, $members);
            if(count($finisedUsers) != count($members))
            {
                if(strpos('cancel,pause', $oldTask->status) === false or ($oldTask->status == 'closed' and $oldTask->reason == 'done'))
                {
                    $currentTask->status       = 'doing';
                    $currentTask->finishedBy   = null;
                    $currentTask->finishedDate = null;
                }
            }
            elseif(strpos('wait,doing,pause', $oldTask->status) !== false)
            {
                $currentTask->status       = 'done';
                $currentTask->assignedTo   = $oldTask->openedBy;
                $currentTask->assignedDate = helper::now();
                $currentTask->finishedBy   = $this->app->user->account;
                $currentTask->finishedDate = $task->finishedDate;
            }
        }

        return $currentTask;
    }

    /**
     * 通过拖动甘特图修改任务的预计开始日期和截止日期。
     * Update task estimate date and deadline through gantt.
     *
     * @param  int     $taskID
     * @param  object  $postData
     * @access protected
     * @return void
     */
    protected function updateTaskEsDateByGantt(int $taskID, object $postData)
    {
        $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $isChildTask = $task->parent > 0 ? true : false;

        if($isChildTask) $parentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();

        $stage  = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->andWhere('project')->eq($task->project)->fetch();

        $start = $isChildTask ? $parentTask->estStarted   : $stage->begin;
        $end   = $isChildTask ? $parentTask->deadline     : $stage->end;
        $arg   = $isChildTask ? $this->lang->task->parent : $this->lang->project->stage;

        if(helper::diffDate($start, $postData->startDate) > 0) dao::$errors = sprintf($this->lang->task->overEsStartDate, $arg, $arg);
        if(helper::diffDate($end, $postData->endDate) < 0)     dao::$errors = sprintf($this->lang->task->overEsEndDate, $arg, $arg);

        $this->dao->update(TABLE_TASK)
            ->set('estStarted')->eq($postData->startDate)
            ->set('deadline')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($taskID)
            ->exec();
    }
}
