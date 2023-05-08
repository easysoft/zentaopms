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
     * 创建一个任务。
     * Create a task.
     *
     * @param  object    $task
     * @access protected
     * @return int|bool
     */
    protected function doCreate(object $task): int|bool
    {
        /* Insert task data. */
        $this->dao->insert(TABLE_TASK)->data($task)
            ->checkIF($task->estimate != '', 'estimate', 'float')
            ->autoCheck()
            ->batchCheck($this->config->task->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        /* Get task id. */
        $taskID = (int)$this->dao->lastInsertID();

        /* Insert task desc data. */
        $taskSpec = new stdClass();
        $taskSpec->task       = $taskID;
        $taskSpec->version    = $task->version;
        $taskSpec->name       = $task->name;
        $taskSpec->estStarted = $task->estStarted ? $task->estStarted : null;
        $taskSpec->deadline   = $task->deadline ? $task->deadline : null;
        $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();

        if(dao::isError()) return false;

        return $taskID;
    }

    /**
     * 获取任务的进度。
     * Compute progress of a task.
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
     * 计算任务列表中每个任务的进度，包括子任务。
     * Compute progress of task list, include its' children.
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
     * 获取执行下的任务。
     * Fetch tasks under execution by executionID(Todo).
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
     * 通过任务类型查找用户的任务。
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
     * 通过任务ID列表查询任务团队信息。
     * Get task team by id list.
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
     * 根据报表条件查询任务.
     * Get task list by report.
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
     * 获取多人串行任务的指派人。
     * Get the assignedTo for the multiply linear task.
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
     *  计算当前任务的状态。
     *  Compute the status of the current task.
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
        /* If the status is not automatic, return the current task. */
        if(!$autoStatus) return $currentTask;

        /* If consumed of the current task is empty and current task has no efforts, the current task status should be wait. */
        if($currentTask->consumed == 0 and !$hasEfforts)
        {
            if(!isset($task->status)) $currentTask->status = 'wait';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        /* If neither consumed nor left of the current task is empty, the current task status should be doing. */
        if($currentTask->consumed > 0 && $currentTask->left > 0)
        {
            $currentTask->status       = 'doing';
            $currentTask->finishedBy   = null;
            $currentTask->finishedDate = null;
        }

        /* If consumed of the current task is not empty and left of the current task is empty, the current task status should be done or doing. */
        if($currentTask->consumed > 0 and $currentTask->left == 0)
        {
            $finisedUsers = $this->getFinishedUsers($oldTask->id, $members);
            /* If the number of finisher is less than the number of team members , the current task status should be doing. */
            if(count($finisedUsers) != count($members))
            {
                if(strpos('cancel,pause', $oldTask->status) === false or ($oldTask->status == 'closed' and $oldTask->reason == 'done'))
                {
                    $currentTask->status       = 'doing';
                    $currentTask->finishedBy   = null;
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

    /**
     * 根据条件移除创建任务的必填项。
     * Remove required fields for creating tasks based on conditions.
     *
     * @param  object    $task
     * @param  bool      $selectTestStory
     * @access protected
     * @return void
     */
    protected function removeCreateRequiredFields(object $task, bool $selectTestStory): void
    {
        /* Get create required fields and the execution of the task. */
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";
        $execution      = $this->dao->findByID($task->execution)->from(TABLE_PROJECT)->fetch();

        /* If the lifetime if the execution is ops and the attribute of execution is request or review, remove story from required fields. */
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review')))
        {
            $requiredFields = str_replace(",story,", ',', "$requiredFields");
        }

        /* If the type of the task is test and select story is true, remove some required fields. */
        if($task->type == 'test' and $selectTestStory)
        {
            $requiredFields = str_replace(array(",estimate,", ",story,", ",estStarted,", ",deadline,", ",module,"), ',', "$requiredFields");
        }

        $this->config->task->create->requiredFields = trim($requiredFields, ',');
    }

    /**
     * 在批量创建之前移除post数据中重复的数据。
     * Remove the duplicate data before batch create tasks.
     *
     * @param  int         $executionID
     * @param  object      $tasks
     * @access protected
     * @return object|false
     */
    protected function removeDuplicate4BatchCreate(int $executionID, object $tasks): object|false
    {
        $storyIDs  = array();
        $taskNames = array();

        foreach($tasks->story as $key => $storyID)
        {
            /* 过滤事务型和任务名称为空的数据。 */
            if(empty($tasks->name[$key])) continue;
            if($tasks->type[$key] == 'affair') continue;
            if($tasks->type[$key] == 'ditto' and isset($tasks->type[$key - 1]) and $tasks->type[$key - 1] == 'affair') continue;

            if($storyID == 'ditto') $storyID = $preStory;
            $preStory = $storyID;

            if(!isset($tasks->story[$key - 1]) and $key > 1 and !empty($tasks->name[$key - 1]))
            {
                $storyIDs[]  = 0;
                $taskNames[] = $tasks->name[$key - 1];
            }

            /* 判断Post传过来的任务有没有重复数据。 */
            $hasExistsName = in_array($tasks->name[$key], $taskNames);
            if($hasExistsName and in_array($storyID, $storyIDs))
            {
                dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $tasks->name[$key];
                return false;
            }

            $storyIDs[]  = $storyID;
            $taskNames[] = $tasks->name[$key];
        }

        /* 去重并赋值。 */
        $result = $this->loadModel('common')->removeDuplicate('task', $tasks, "execution=$executionID and story " . helper::dbIN($storyIDs));
        return $result['data'];
    }

    /**
     * 批量创建任务之前构造数据。
     * Construct data before batch create tasks.
     *
     * @param  object     $execution
     * @param  object     $tasks
     * @param  int        $index
     * @param  array      $dittoFields
     * @param  array      $extendFields
     * @access protected
     * @return array
     */
    protected function constructData4BatchCreate(object $execution, object $tasks, int $index, array $dittoFields, array $extendFields): array
    {
        extract($dittoFields);
        $now = helper::now();

        $data[$index]             = new stdclass();
        $data[$index]->story      = (int)$story;
        $data[$index]->type       = $type;
        $data[$index]->module     = (int)$module;
        $data[$index]->assignedTo = $assignedTo;
        $data[$index]->color      = $tasks->color[$index];
        $data[$index]->name       = $tasks->name[$index];
        $data[$index]->desc       = nl2br($tasks->desc[$index]);
        $data[$index]->pri        = $tasks->pri[$index];
        $data[$index]->estimate   = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $data[$index]->left       = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $data[$index]->project    = $execution->project;
        $data[$index]->execution  = $execution->id;
        $data[$index]->estStarted = $estStarted;
        $data[$index]->deadline   = $deadline;
        $data[$index]->status     = 'wait';
        $data[$index]->openedBy   = $this->app->user->account;
        $data[$index]->openedDate = $now;
        $data[$index]->parent     = $tasks->parent[$index];
        $data[$index]->vision     = isset($tasks->vision[$index]) ? $tasks->vision[$index] : 'rnd';
        if($story) $data[$index]->storyVersion = (int)$this->dao->findById($data[$index]->story)->from(TABLE_STORY)->fetch('version');
        if($assignedTo) $data[$index]->assignedDate = $now;
        if(strpos($this->config->task->create->requiredFields, 'estStarted') !== false and empty($estStarted)) $data[$index]->estStarted = '';
        if(strpos($this->config->task->create->requiredFields, 'deadline') !== false and empty($deadline))     $data[$index]->deadline   = '';
        if(isset($tasks->lanes[$index])) $data[$index]->laneID = $tasks->lanes[$index];

        /* 附加工作流字段。 */
        foreach($extendFields as $extendField)
        {
            $data[$index]->{$extendField->field} = $tasks->{$extendField->field}[$index];
            if(is_array($data[$index]->{$extendField->field})) $data[$index]->{$extendField->field} = join(',', $data[$index]->{$extendField->field});

            $data[$index]->{$extendField->field} = htmlSpecialString($data[$index]->{$extendField->field});
        }

        return $data;
    }

    /**
     * 批量创建前检查必填项
     * Check required fields before batch create tasks.
     *
     * @param  object    $execution
     * @param  object[]  $data
     * @access protected
     * @return object[]|false
     */
    protected function checkRequired4BatchCreate(object $execution, array $data): array|false
    {
        /* 设置必填项。 */
        $requiredFields = ',' . $this->config->task->create->requiredFields . ',';
        if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review') $requiredFields = str_replace(',story,', ',', $requiredFields);
        $requiredFields = trim($requiredFields, ',');

        /* check data. */
        foreach($data as $i => $task)
        {
            /* 检查任务是否开启了起止日期必填的配置(limitTaskDate)。 */
            if(!empty($this->config->limitTaskDate))
            {
                $this->checkEstStartedAndDeadline($execution->id, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

            /* 检查任务截止日期是否为空以及是否小于预计开始日期。 */
            if(!helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted)
            {
                dao::$errors['message'][] = $this->lang->task->error->deadlineSmall;
                return false;
            }

            /* 检查任务预计是否为数字类型。 */
            if($task->estimate and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $task->estimate))
            {
                dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
                return false;
            }

            /* 验证必填字段。 */
            $requiredFields = array_filter(explode(',', $requiredFields));
            foreach($requiredFields as $field)
            {
                if(empty($task->$field))
                {
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                    return false;
                }
            }
            if($task->estimate) $task->estimate = (float)$task->estimate;
        }

        return $data;
    }

    /**
     * 拆分已消耗的任务。
     * Split the consumed task.
     *
     * @param  object    $parentTask
     * @access protected
     * @return bool
     */
    protected function splitConsumedTask(object $parentTask): bool
    {
        $clonedTask = clone $parentTask;
        $clonedTask->parent = $parentTask->id;
        unset($clonedTask->id);
        $this->dao->insert(TABLE_TASK)->data($clonedTask)->autoCheck()->exec();
        if(dao::isError()) return false;

        $clonedTaskID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_EFFORT)->set('objectID')->eq($clonedTaskID)
            ->where('objectID')->eq($parentTask->id)
            ->andWhere('objectType')->eq('task')
            ->exec();
        if(dao::isError()) return false;

        return true;
    }

    /**
     * 拆分之后更新父任务的parent、lastEditedBy、lastEditedDate字段。
     * Update the parent's parent, lastEditedBy, and lastEditedDate fields after split.
     *
     * @param  int        $parentID
     * @access protected
     * @return void
     */
    protected function updateParentAfterSplit(int $parentID): void
    {
        $task = new stdclass();
        $task->parent         = '-1';
        $task->lastEditedBy   = $this->app->user->account;
        $task->lastEditedDate = helper::now();
        $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($parentID)->exec();
    }

    /**
     * Manage multi task team member.
     *
     * @param  string     $mode
     * @param  object     $task
     * @param  int        $row
     * @param  string     $account
     * @param  string     $minStatus
     * @param  array      $undoneUsers
     * @param  array      $teamSourceList
     * @param  array      $teamEstimateList
     * @param  array|bool $teamConsumedList
     * @param  array|bool $teamLeftList
     * @param  bool       $inTeams
     * @access protected
     * @return string
     */
    protected function manageTaskTeamMember(string $mode, object $task, int $row, string $account, string $minStatus, array $undoneUsers, array $teamSourceList, array $teamEstimateList, array|bool $teamConsumedList, array|bool $teamLeftList, bool $inTeams): string
    {
        /* Set member information. */
        $member = new stdClass();
        $member->task     = $task->id;
        $member->order    = $row;
        $member->account  = $account;
        $member->estimate = zget($teamEstimateList, $row, 0);
        $member->consumed = $teamConsumedList ? zget($teamConsumedList, $row, 0) : 0;
        $member->left     = $teamLeftList ? zget($teamLeftList, $row, 0) : 0;
        $member->status   = 'wait';
        if($task->status == 'wait' and $member->estimate > 0 and $member->left == 0) $member->left = $member->estimate;
        if($task->status == 'done') $member->left = 0;

        /* Compute task status of member. */
        if($member->left == 0 and $member->consumed > 0)
        {
            $member->status = 'done';
        }
        elseif($task->status == 'doing')
        {
            $teamSource = $teamSourceList[$row];

            if(!empty($teamSource) and $teamSource != $account and isset($undoneUsers[$teamSource])) $member->transfer = $teamSource;
            if(isset($undoneUsers[$account]) and ($mode == 'multi' or ($mode == 'linear' and $minStatus != 'wait'))) $member->status = 'doing';
        }

        /* Compute multi-task status, and in a linear task, there is only one doing status. */
        if(($mode == 'linear' and $member->status == 'doing') or $member->status == 'wait') $minStatus = 'wait';
        if($minStatus != 'wait' and $member->status == 'doing') $minStatus = 'doing';

        /* Insert or update team. */
        if($mode == 'multi' and $inTeams)
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

        return $minStatus;
    }
}
