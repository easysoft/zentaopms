<?php
declare(strict_types=1);
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: model.php 5154 2013-07-16 05:51:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class taskModel extends model
{
    /**
     * 激活任务。
     * Activate task.
     *
     * @param  object      $task
     * @param  string      $comment
     * @param  object      $teamData
     * @param  string      $drag
     * @access public
     * @return array|false
     */
    public function activate(object $task, string $comment, object $teamData, array $drag = array()): array|false
    {
        $taskID = $task->id;

        if(strpos($this->config->task->activate->requiredFields, 'comment') !== false && !$comment)
        {
            dao::$errors['comment'] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $oldTask = $this->getById($taskID);
        if($oldTask->isParent) $this->config->task->activate->requiredFields = '';

        $this->dao->update(TABLE_TASKTEAM)->set('status')->eq('wait')->where('task')->eq($task->id)->andWhere('consumed')->eq(0)->andWhere('left')->gt('0')->exec();
        $this->dao->update(TABLE_TASKTEAM)->set('status')->eq('doing')->where('task')->eq($task->id)->andWhere('consumed')->gt(0)->andWhere('left')->gt(0)->exec();
        $this->dao->update(TABLE_TASKTEAM)->set('status')->eq('done')->where('task')->eq($task->id)->andWhere('consumed')->gt(0)->andWhere('left')->eq('0')->exec();

        if(!empty($oldTask->team))
        {
            /* When activate and assigned to a team member, then update his left data in teamData. */
            $teamIndex = zget(array_flip($teamData->team), $task->assignedTo, '');
            if($teamIndex !== '') $teamData->teamLeft[$teamIndex] = $task->left;

            $this->manageTaskTeam($oldTask->mode, $task, $teamData);
            $task = $this->computeMultipleHours($oldTask, $task);
            if(!empty($task->assignedTo) && $task->assignedTo == 'closed') $task->assignedTo = '';
        }

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheck($this->config->task->activate->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();
        if(dao::isError()) return false;

        if($task->left != $oldTask->left) $this->loadModel('program')->refreshProjectStats($oldTask->project);

        if($oldTask->parent > 0) $this->updateParentStatus($taskID);
        if($oldTask->story)  $this->loadModel('story')->setStage($oldTask->story);
        if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status, $taskID);

        $this->updateKanbanCell($taskID, $drag, $oldTask->execution);

        return common::createChanges($oldTask, $task);
    }

    /**
     * 添加一条工时记录。
     * Add task effort.
     *
     * @param  object $data
     * @access public
     * @return int
     */
    public function addTaskEffort(object $data): int
    {
        $oldTask  = $this->getById($data->task);
        $relation = $this->loadModel('action')->getRelatedFields('task', $data->task);

        $effort = new stdclass();
        $effort->objectType = 'task';
        $effort->objectID   = $data->task;
        $effort->execution  = $oldTask->execution;
        $effort->product    = $relation['product'];
        $effort->project    = (int)$relation['project'];
        $effort->account    = $data->account;
        $effort->date       = $data->date;
        $effort->consumed   = $data->consumed;
        $effort->left       = $data->left;
        $effort->work       = isset($data->work) ? $data->work : '';
        $effort->vision     = $this->config->vision;
        $effort->order      = isset($data->order) ? $data->order : 0;
        $this->dao->insert(TABLE_EFFORT)->data($effort)->autoCheck()->exec();
        $effortID = $this->dao->lastInsertID();

        $this->loadModel('program')->refreshProjectStats($effort->project);

        return $effortID;
    }

    /**
     * 批量创建任务后的其他数据处理。
     * other data process after task batch create.
     *
     * @param  array  $taskIdList
     * @param  object $parent
     * @access public
     * @return bool
     */
    public function afterBatchCreate(array $taskIdList, object $parent = null): bool
    {
        /* Process other data after split task. */
        if($parent && !empty($taskIdList))
        {
            $this->afterSplitTask($parent, $taskIdList);
        }

        return !dao::isError();
    }

    /**
     * 批量编辑任务后的其他数据处理。
     * other data process after task batch edit.
     *
     * @param  object[] $tasks
     * @param  object[] $oldTasks
     * @access public
     * @return bool
     */
    public function afterBatchUpdate(array $tasks, array $oldTasks = array()): bool
    {
        $this->loadModel('story');
        $this->loadModel('kanban');
        $this->loadModel('feedback');

        $today          = helper::today();
        $currentAccount = $this->app->user->account;
        $syncStatus     = false;
        foreach($tasks as $taskID => $task)
        {
            $oldTask = zget($oldTasks, $taskID);

            /* Record effort. */
            if(!empty($task->consumed) && $task->consumed != $oldTask->consumed)
            {
                $record = new stdclass();
                $record->account  = $currentAccount;
                $record->task     = $taskID;
                $record->date     = $today;
                $record->left     = $task->left;
                $record->consumed = $task->consumed;
                $this->addTaskEffort($record);
            }

            /* Update Kanban and story stage. */
            if($oldTask->story) $this->story->setStage($oldTask->story);
            if($task->status != $oldTask->status) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);

            /* Update parent task's status, date and hour. */
            if($oldTask->parent > 0)
            {
                $this->updateParentStatus($taskID);
                $this->computeBeginAndEnd($oldTask->parent);
            }

            if($this->config->edition != 'open' && $oldTask->feedback && !isset($feedbacks[$oldTask->feedback]))
            {
                $feedbacks[$oldTask->feedback] = $oldTask->feedback;
                $this->feedback->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status, $taskID);
            }

            if(!empty($task->story) && !empty($task->isParent) && $this->post->syncChildren) $this->syncStoryToChildren($task);

            if(!$syncStatus && $oldTask->status == 'wait' && $task->status == 'doing')
            {
                $syncStatus = true;
                $this->loadModel('common')->syncPPEStatus($oldTask->id);
            }

        }

        return !dao::isError();
    }

    /**
     * 处理状态变化之后的操作。
     * Process other data after task status changed.
     *
     * @param  object $task
     * @param  array  $changes
     * @param  string $action  Finished|Closed|Started
     * @param  array  $output
     * @access public
     * @return bool
     */
    public function afterChangeStatus(object $task, array $changes, string $action, array $output): bool
    {
        /* Process other data. */
        if($task->parent > 0) $this->updateParentStatus($task->id);
        if($task->isParent)   $this->updateChildrenStatus($task->id, $task->status);
        if($task->story) $this->loadModel('story')->setStage($task->story);

        $this->updateKanbanCell($task->id, $output, $task->execution);

        $files = $this->loadModel('file')->saveUpload('task', $task->id);
        if($changes || $this->post->comment)
        {
            $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
            $actionID   = $this->loadModel('action')->create('task', $task->id, $action, $fileAction . $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }
        return !dao::isError();
    }

    /**
     * 创建任务后的其他数据处理。
     * Other data process after task create.
     *
     * @param  object $task
     * @param  array  $taskIdList
     * @param  int    $bugID
     * @param  int    $todoID
     * @access public
     * @return bool
     */
    public function afterCreate(object $task, array $taskIdList, int $bugID, int $todoID): bool
    {
        $this->loadModel('file');

        $this->setTaskFiles($taskIdList); // Set attachments for tasks.
        foreach($taskIdList as $taskID)
        {
            /* If the task comes from a bug, update the task and bug information. */
            if($bugID > 0)
            {
                $this->dao->update(TABLE_TASK)->set('fromBug')->eq($bugID)->where('id')->eq($taskID)->exec();
                $this->dao->update(TABLE_BUG)->set('toTask')->eq($taskID)->where('id')->eq($bugID)->exec();
                $this->loadModel('action')->create('bug', $bugID, 'converttotask', '', $taskID);
            }

            /* If the task comes from a todo, update the todo information. */
            if($todoID > 0)
            {
                $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
                $this->loadModel('action')->create('todo', $todoID, 'finished', '', "TASK:$taskID");

                /* If the todo comes from a feedback, update the feedback information. */
                $todo = $this->dao->findByID($todoID)->from(TABLE_TODO)->fetch();
                if($this->config->edition != 'open' && $todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, 'done', '', $todoID);
            }

            /* If the task comes from a design, update the task information. */
            if(!empty($task->design))
            {
                $design = $this->loadModel('design')->getByID($task->design);
                $this->dao->update(TABLE_TASK)->set('designVersion')->eq($design->version)->where('id')->eq($taskID)->exec();
            }

            /* If the task comes from a story, update the stage of the story. */
            if($task->story) $this->loadModel('story')->setStage($task->story);
        }
        return !dao::isError();
    }

    /**
     * 拆分任务后更新其他数据。
     * Process other data after split task.
     *
     * @param  object $oldParentTask
     * @param  array  $childrenIdList
     * @access public
     * @return bool
     */
    public function afterSplitTask(object $oldParentTask, array $childrenIdList = array()): bool
    {
        $parentID = (int)$oldParentTask->id;

        /* When a normal task is consumed, create the subtask and update the parent task status. */
        if($oldParentTask->isParent == 0 && $oldParentTask->consumed > 0)
        {
            $this->taskTao->copyTaskData($oldParentTask);
            if(dao::isError()) return false;
        }

        $parentTask = new stdclass();
        $parentTask->isParent       = 1;
        $parentTask->lastEditedBy   = $this->app->user->account;
        $parentTask->lastEditedDate = helper::now();
        $this->dao->update(TABLE_TASK)->data($parentTask)->where('id')->eq($parentID)->exec();

        $this->updateParentStatus(current($childrenIdList));
        $this->computeBeginAndEnd($parentID);
        $this->loadModel('program')->refreshProjectStats($oldParentTask->project);

        /* Create a action. */
        $extra    = implode(',', $childrenIdList);
        $actionID = $this->loadModel('action')->create('task', $parentID, 'createChildren', '', trim($extra, ','));

        /* Create a log history. */
        $newParentTask = $this->getByID($parentID);
        $changes       = common::createChanges($oldParentTask, $newParentTask);
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }

    /**
     * 开始任务后的其他数据处理。
     * Other data process after task start.
     *
     * @param  object     $oldTask
     * @param  array      $changes
     * @param  float      $left
     * @param  array      $output
     * @access public
     * @return array|bool
     */
    public function afterStart(object $oldTask, array $changes, float $left, array $output = array()): array|bool
    {
        /* Update the data of the parent task. */
        if($oldTask->parent > 0) $this->computeBeginAndEnd($oldTask->parent);

        /* Create related dynamic and record. */
        $action = 'Started';
        if($oldTask->status == 'pause') $action = 'Restarted';
        if($left == 0) $action = 'Finished';
        $this->afterChangeStatus($oldTask, $changes, $action, $output);

        /* Send Webhook notifications and synchronize status to execution, project and program. */
        $this->executeHooks($oldTask->id);
        $this->loadModel('common')->syncPPEStatus($oldTask->id);

        /* Remind whether to update status of the bug, if task which from that bug has been finished. */
        if($changes && $this->needUpdateBugStatus($oldTask))
        {
            $response = $this->taskTao->getRemindBugLink($oldTask, $changes);
            if($response) return $response;
        }

        return true;
    }

    /**
     * 编辑任务后的其他数据处理:记录分数、更改需求阶段、处理父任务变更、更改反馈状态等。
     * Additional data processing after updating tasks: record scores, change story stage, handle parent task changes, change feedback status.
     *
     * @param  object $oldTask
     * @param  object $task
     * @access public
     * @return void
     */
    public function afterUpdate(object $oldTask, object $task): void
    {
        /* Update children task. */
        if(isset($task->execution) && $task->execution != $oldTask->execution)
        {
            $newExecution  = $this->loadModel('execution')->getByID((int)$task->execution);
            $task->project = $newExecution->project;
            $this->dao->update(TABLE_TASK)->set('execution')->eq($task->execution)->set('module')->eq($task->module)->set('project')->eq($task->project)->where('parent')->eq($task->id)->exec();
        }

        /* Multi-task change to normal task. */
        if($task->mode == 'single') $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->exec();

        if(isset($task->version) && $task->version > $oldTask->version) $this->recordTaskVersion($task);

        /* Compute task's story stage. */
        $this->loadModel('story')->setStage($task->story);
        if($task->story != $oldTask->story) $this->story->setStage($oldTask->story);

        /* Create score. */
        if($task->status == 'done')   $this->loadModel('score')->create('task', 'finish', $task->id);
        if($task->status == 'closed') $this->loadModel('score')->create('task', 'close', $task->id);

        if($task->status != $oldTask->status) $this->loadModel('kanban')->updateLane($task->execution, 'task', $task->id);

        $isParentChanged = $task->parent != $oldTask->parent;

        /* If there is a parent task before updating the task, update the parent. */
        $this->updateParent($task, $isParentChanged);
        if($isParentChanged && $oldTask->parent > 0)
        {
            $oldParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldTask->parent)->fetch();
            $this->updateParentStatus($task->id, $oldTask->parent, !$isParentChanged);
            $this->computeBeginAndEnd($oldTask->parent);

            $oldChildCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_TASK)->where('parent')->eq($oldTask->parent)->fetch('count');
            if(!$oldChildCount) $this->dao->update(TABLE_TASK)->set('isParent')->eq(0)->where('id')->eq($oldTask->parent)->exec();

            $this->dao->update(TABLE_TASK)->set('lastEditedBy')->eq($this->app->user->account)->set('lastEditedDate')->eq(helper::now())->where('id')->eq($oldTask->parent)->exec();
            $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldTask->parent)->fetch();
            $changes       = common::createChanges($oldParentTask, $newParentTask);

            $this->loadModel('action')->create('task', $task->id, 'unlinkParentTask', '', $oldTask->parent, '', false);
            $actionID = $this->action->create('task', $oldTask->parent, 'unLinkChildrenTask', '', $task->id, '', false);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        }

        if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status, $oldTask->id);
        if(!empty($oldTask->mode) && empty($task->mode)) $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->exec();

        if(!empty($task->story) && $this->post->syncChildren) $this->syncStoryToChildren($task);
        if(!empty($task->mode) && $task->story != $oldTask->story && $task->storyVersion > $oldTask->storyVersion) $this->dao->update(TABLE_TASKTEAM)->set('storyVersion')->eq($task->storyVersion)->where('task')->eq($task->id)->exec();
    }

    /**
     * 在任务信息中追加泳道名称。
     * Append the lane name to the task information.
     *
     * @param  array  $tasks
     * @access public
     * @return object[]
     */
    public function appendLane(array $tasks): array
    {
        $executionIdList = array();
        foreach($tasks as $task)
        {
            $task->lane = '';
            if(!isset($executionIdList[$task->execution])) $executionIdList[$task->execution] = $task->execution;
        }

        $lanes = $this->dao->select('t1.kanban,t1.lane,t2.name,t1.cards')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane = t2.id')
            ->where('t1.kanban')->in($executionIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('task')
            ->andWhere("t1.cards")->ne('')
            ->fetchAll();

        if(empty($lanes)) return $tasks;

        foreach($tasks as $task)
        {
            foreach($lanes as $lane)
            {
                if($lane->kanban != $task->execution) continue;
                if(strpos(",{$lane->cards},", ",{$task->id},") === false) continue;

                $task->lane = $lane->name;
                break;
            }
        }

        return $tasks;
    }

    /**
     * 任务指派用户。
     * Assign a task to a user again.
     *
     * @param  object $task
     * @param  int    $taskID
     * @access public
     * @return array|false
     */
    public function assign(object $task): array|false
    {
        $oldTask = $this->getById($task->id);

        /* Check task left. */
        if($oldTask->isParent == '0' && !in_array($oldTask->status, array('done', 'closed')) && isset($task->left) && $task->left == 0)
        {
            dao::$errors['left'] = sprintf($this->lang->error->notempty, $this->lang->task->left);
            return false;
        }

        /* Update parent task status. */
        if($oldTask->parent > 0) $this->updateParentStatus($task->id);

        $this->dao->update(TABLE_TASK)
            ->data($task)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($task->id)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldTask, $task);

        /* Record log. */
        $actionID = $this->loadModel('action')->create('task', $task->id, 'Assigned', $this->post->comment, $task->assignedTo);
        $this->action->logHistory($actionID, $changes);

        return $changes;
    }

    /**
     * 批量创建任务。
     * Batch create tasks.
     *
     * @param  array       $tasks
     * @param  array       $output
     * @access public
     * @return array|false
     */
    public function batchCreate(array $tasks, array $output): array|false
    {
        $this->loadModel('story');

        $executionID = !empty($tasks) ? current($tasks)->execution : 0;
        $taskIdList  = array();
        $parents     = array();
        foreach($tasks as $task)
        {
            /* Get the lane and column of the current task. */
            $laneID   = $task->lane;
            $columnID = $task->column;
            $level    = $task->level;
            unset($task->lane, $task->column, $task->level);

            /* Create a task. */
            $taskID = $this->create($task);
            if(!$taskID) return false;

            $parents[$level] = $taskID;
            if($level > 0)
            {
                $task->id     = $taskID;
                $task->parent = $parents[$level - 1];
                $this->dao->update(TABLE_TASK)->set('parent')->eq($task->parent)->where('id')->eq($taskID)->exec();
                $this->updateParent($task, false);
            }

            /* Update Kanban and story stage. */
            if(!empty($task->story))
            {
                $this->story->setStage($task->story);

                if($this->config->edition != 'open')
                {
                    $relation = new stdClass();
                    $relation->relation = 'generated';
                    $relation->AID      = $task->story;
                    $relation->AType    = 'story';
                    $relation->BID      = $taskID;
                    $relation->BType    = 'task';
                    $relation->product  = 0;
                    $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                }
            }
            $this->updateKanbanForBatchCreate($taskID, $executionID, $laneID, (int)$columnID);

            $taskIdList[$taskID] = $taskID;
        }
        return $taskIdList;
    }

    /**
     * 批量更改任务所属模块。
     * Batch change the module of task.
     *
     * @param  array  $taskIdList
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function batchChangeModule(array $taskIdList, int $moduleID): bool
    {
        $now      = helper::now();
        $oldTasks = $this->getByIdList($taskIdList);

        $this->loadModel('action');
        foreach($taskIdList as $taskID)
        {
            $oldTask = zget($oldTasks, $taskID, null);
            if(!$oldTask || $moduleID == $oldTask->module) continue;

            $task = new stdclass();
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $task->module         = $moduleID;

            $this->dao->update(TABLE_TASK)->data($task)
                ->autoCheck()
                ->check('module', 'ge', 0)
                ->where('id')->eq((int)$taskID)
                ->exec();

            if(dao::isError()) return false;

            $changes  = common::createChanges($oldTask, $task);
            $actionID = $this->action->create('task', (int)$taskID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
        return true;
    }

    /**
     * 批量更新任务。
     * Batch update tasks.
     *
     * @param  array $taskData
     * @access public
     * @return array[]|false
     */
    public function batchUpdate(array $taskData): array|false
    {
        $this->loadModel('action');
        $this->loadModel('score');

        $allChanges = array();
        $oldTasks   = $taskData ? $this->getByIdList(array_keys($taskData)) : array();
        foreach($taskData as $taskID => $task)
        {
            foreach($this->config->task->dateFields as $field)
            {
                if(in_array($field, explode(',', $this->config->task->batchedit->requiredFields))) continue;
                if(empty($task->$field)) unset($task->$field);
            }

            /* Update a task.*/
            $this->dao->update(TABLE_TASK)->data($task)
                ->autoCheck()
                ->batchCheck($this->config->task->batchedit->requiredFields, 'notempty')
                ->checkFlow()
                ->where('id')->eq($taskID)
                ->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$taskID}]"] = $error;
                return false;
            }

            /* Create the task description of the current version in the database. */
            $oldTask = zget($oldTasks, $taskID);
            if($task->version > $oldTask->version)
            {
                $taskSpec = new stdclass();
                $taskSpec->task       = $taskID;
                $taskSpec->version    = $task->version;
                $taskSpec->name       = $task->name;
                $taskSpec->estStarted = isset($task->estStarted) ? $task->estStarted : null;
                $taskSpec->deadline   = isset($task->deadline) ? $task->deadline : null;

                $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            }

            if(!empty($oldTask->mode) && $task->story != $oldTask->story)
            {
                $storyVersion = $this->dao->select('version')->from(TABLE_STORY)->where('id')->eq($task->story)->limit(1)->fetch('version');
                $this->dao->update(TABLE_TASKTEAM)->set('storyVersion')->eq($storyVersion)->where('task')->eq($task->id)->exec();
            }

            if($task->status == 'done')   $this->score->create('task', 'finish', $taskID);
            if($task->status == 'closed') $this->score->create('task', 'close', $taskID);
            $changes  = common::createChanges($oldTask, $task);
            if(!empty($changes))
            {
                $extra = '';
                array_map(function($field) use (&$extra) { if($field['field'] == 'status') $extra = 'statuschanged'; }, $changes);

                $actionID = $this->action->create('task', $taskID, 'Edited', '', $extra);
                $this->action->logHistory($actionID, $changes);
                $allChanges[$taskID] = $changes;
            }
        }
        $this->score->create('ajax', 'batchEdit');
        return $allChanges;
    }

    /**
     * 取消一个任务。
     * Cancel a task.
     *
     * @param  object  $oldTask
     * @param  object  $task
     * @param  array   $output
     * @access public
     * @return bool
     */
    public function cancel(object $oldTask, object $task, array $output = array()): bool
    {
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq($oldTask->id)->exec();
        if(dao::isError()) return false;

        if(!empty($oldTask->mode))    $this->dao->update(TABLE_TASKTEAM)->set('status')->eq($task->status)->where('task')->eq($oldTask->id)->exec();
        if(!empty($oldTask->fromBug)) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($oldTask->fromBug)->exec();

        /* Cancel a parent task. */
        $changes = common::createChanges($oldTask, $task);
        $this->afterChangeStatus($oldTask, $changes, 'Canceled', $output);
        return true;
    }

    /**
     * 检查当前登录用户是否可以操作日志。
     * Check if the current user can operate effort.
     *
     * @param  object $task
     * @param  object $effort
     * @access public
     * @return bool
     */
    public function canOperateEffort(object $task, object $effort = null): bool
    {
        if(empty($task->team))
        {
            if($effort === null) $effort = new stdclass();
            return $this->loadModel('common')->canOperateEffort($effort);
        }

        /* Check for add effort. */
        if(empty($effort))
        {
            $members = array_column($task->team, 'account');
            if(!in_array($this->app->user->account, $members)) return false;
            if($task->mode == 'linear' && $this->app->user->account != $task->assignedTo) return false;
            return true;
        }

        /* Check for edit and delete effort. */
        if($task->mode == 'linear')
        {
            if(in_array($task->status, array('pause', 'cancel', 'closed'))) return false;
            if($task->status == 'doing') return $effort->account == $this->app->user->account;
        }
        if($this->app->user->account == $effort->account) return true;
        return false;
    }

    /**
     * 检查开始日期和截止日期。
     * Check estStarted and deadline date.
     *
     * @param  int        $executionID
     * @param  string     $estStarted
     * @param  string     $deadline
     * @param  string     $prefix
     * @access public
     * @return false|void
     */
    public function checkEstStartedAndDeadline(int $executionID, string $estStarted, string $deadline, string $prefix = '')
    {
        $execution = $this->loadModel('execution')->getByID($executionID);
        if(empty($execution) || empty($this->config->limitTaskDate)) return false;
        if(empty($execution->multiple)) $this->lang->execution->common = $this->lang->project->common;

        if(!empty($estStarted) && !helper::isZeroDate($estStarted))
        {
            if($estStarted < $execution->begin) dao::$errors['estStarted'] = $prefix . sprintf($this->lang->task->error->beginLtExecution, $this->lang->execution->common, $execution->begin);
            if($estStarted > $execution->end)   dao::$errors['estStarted'] = $prefix . sprintf($this->lang->task->error->beginGtExecution, $this->lang->execution->common, $execution->end);
        }

        if(!empty($deadline) && !helper::isZeroDate($deadline))
        {
            if($deadline > $execution->end)   dao::$errors['deadline'] = $prefix . sprintf($this->lang->task->error->endGtExecution, $this->lang->execution->common, $execution->end);
            if($deadline < $execution->begin) dao::$errors['deadline'] = $prefix . sprintf($this->lang->task->error->endLtExecution, $this->lang->execution->common, $execution->begin);
        }
    }

    /**
     * 关闭任务。
     * Close a task.
     *
     * @param  object     $oldTask
     * @param  object     $task
     * @param  string     $output
     * @access public
     * @return bool|array
     */
    public function close(object $oldTask, object $task, array $output = array()): bool|array
    {
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq((int)$oldTask->id)->exec();
        if(dao::isError()) return false;

        if(!empty($oldTask->mode)) $this->dao->update(TABLE_TASKTEAM)->set('`status`')->eq($task->status)->where('task')->eq($task->id)->exec();

        $changes = common::createChanges($oldTask, $task);
        $this->afterChangeStatus($oldTask, $changes, 'Closed', $output);
        $this->loadModel('score')->create('task', 'close', $task->id);

        /* Confirm need update issue status. */
        if(isset($oldTask->fromIssue) && $oldTask->fromIssue > 0)
        {
            $fromIssue = $this->loadModel('issue')->getByID($oldTask->fromIssue);
            if($fromIssue->status != 'closed')
            {
                $confirmURL = $this->createLink('issue', 'close', "id=$oldTask->fromIssue");
                $cancelURL  = $this->createLink('task', 'view', "taskID=$oldTask->id");
                return array('result' => 'success', 'load' => array('confirm' => sprintf($this->lang->task->remindIssue, $oldTask->fromIssue), 'confirmed' => $confirmURL, 'canceled' => $cancelURL));
            }
        }
        return true;
    }

    /**
     * 根据父任务ID计算父任务的预计开始 实际开始 截止日期。
     * Compute begin and end for parent task.
     *
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function computeBeginAndEnd(int $taskID): bool
    {
        /* Get estStarted realStarted and deadline of the sub-tasks. */
        $tasks = $this->dao->select('estStarted, realStarted, deadline')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('status')->ne('cancel')->andWhere('deleted')->eq(0)->fetchAll();
        if(empty($tasks)) return !dao::isError();

        /* Compute the earliest estStarted, the earliest realStarted and the latest deadline. */
        $earliestEstStarted  = '';
        $earliestRealStarted = '';
        $latestDeadline      = '';
        foreach($tasks as $task)
        {
            if(!helper::isZeroDate($task->estStarted)  && (empty($earliestEstStarted)  || $earliestEstStarted  > $task->estStarted))  $earliestEstStarted  = $task->estStarted;
            if(!helper::isZeroDate($task->realStarted) && (empty($earliestRealStarted) || $earliestRealStarted > $task->realStarted)) $earliestRealStarted = $task->realStarted;
            if(!helper::isZeroDate($task->deadline)    && (empty($latestDeadline)      || $latestDeadline      < $task->deadline))    $latestDeadline      = $task->deadline;
        }

        /* Initialize task data and update it. */
        $parent = $this->fetchById($taskID);

        $newTask = array();
        if(!empty($earliestEstStarted)  && !helper::isZeroDate($parent->estStarted)  && $parent->estStarted  > $earliestEstStarted)  $newTask['estStarted']  = $earliestEstStarted;
        if(!empty($earliestRealStarted) && !helper::isZeroDate($parent->realStarted) && $parent->realStarted > $earliestRealStarted) $newTask['realStarted'] = $earliestRealStarted;
        if(!empty($latestDeadline)      && !helper::isZeroDate($parent->deadline)    && $parent->deadline    < $latestDeadline)      $newTask['deadline']    = $latestDeadline;
        if(!empty($newTask)) $this->dao->update(TABLE_TASK)->data($newTask)->autoCheck()->where('id')->eq($taskID)->exec();

        if($parent->parent) $this->computeBeginAndEnd($parent->parent);

        return !dao::isError();
    }

    /**
     * 计算多人任务工时。
     * Compute hours for multiple task.
     *
     * @param  object      $oldTask
     * @param  object      $task
     * @param  array       $team
     * @param  bool        $autoStatus
     * @access public
     * @return object|bool
     */
    public function computeMultipleHours(object $oldTask, object $task = null, array $team = array(), bool $autoStatus = true): object|bool
    {
        if(!$oldTask) return false;

        if(empty($team)) $team = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($oldTask->id)->orderBy('order')->fetchAll('id', false); // If the team is empty, get the team from the task team table.

        /* If the team is not empty, compute the team hours. */
        if(!empty($team))
        {
            /* Get members, old team and current task. */
            $members     = array_column($team, 'account');
            $oldTeam     = zget($oldTask, 'team', array());
            $currentTask = !empty($task) ? clone $task : new stdclass();
            if(!isset($currentTask->status)) $currentTask->status = $oldTask->status;
            $oldTask->team = $team;

            /* If the assignedTo is not empty, the current task assignedTo is assignedTo. */
            if(!empty($_POST['assignedTo']) && is_string($_POST['assignedTo']))
            {
                $currentTask->assignedTo = $this->post->assignedTo;
            }
            /* If assignedTo is empty, get the assignedTo for the multiply linear task. */
            else
            {
                $currentTask->assignedTo = $this->getAssignedTo4Multi($members, $oldTask);
                if($oldTask->assignedTo != $currentTask->assignedTo) $currentTask->assignedDate = helper::now();
                $oldTask->team = $oldTeam;
            }

            /* Compute estimate and left. */
            $currentTask->estimate = 0;
            $currentTask->left     = 0;
            foreach($team as $member)
            {
                $currentTask->estimate += (float)$member->estimate;
                $currentTask->left     += (float)$member->left;
            }

            /* Get task efforts, and compute consumed. */
            $efforts = $this->getTaskEfforts($oldTask->id);
            $currentTask->consumed = 0;
            foreach($efforts as $effort) $currentTask->consumed += (float)$effort->consumed;

            /* If task is not empty, the task status is computed and the task is returned. */
            if(!empty($task)) return $this->taskTao->computeTaskStatus($currentTask, $oldTask, $task, $autoStatus, !empty($efforts), $members);

            /* If task is empty, update the current task. */
            $this->dao->update(TABLE_TASK)->data($currentTask)->autoCheck()->where('id')->eq($oldTask->id)->exec();
        }
        return !dao::isError();
    }

    /**
     * 根据父任务ID计算父任务的预计、消耗和剩余工时。
     * Compute parent task working hours.
     *
     * @param  int|bool $taskID
     * @access public
     * @return bool
     */
    public function computeWorkingHours(int|bool $taskID): bool
    {
        if(!$taskID) return true;

        /* Get sub-tasks. */
        $tasks = $this->dao->select('`id`,`estimate`,`consumed`,`left`, status')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('status')->ne('cancel')->andWhere('deleted')->eq(0)->fetchAll('id');
        /* If task doesn't have sub-tasks, clear out the consumed hours. */
        if(empty($tasks))
        {
            $this->dao->update(TABLE_TASK)->set('consumed')->eq(0)->where('id')->eq($taskID)->exec();
            return !dao::isError();
        }

        /* Compute task estimate, consumed and left through sub-tasks. */
        $estimate = 0;
        $consumed = 0;
        $left     = 0;
        foreach($tasks as $task)
        {
            $estimate += $task->estimate;
            $consumed += $task->consumed;
            if($task->status != 'closed') $left += $task->left;
        }

        /* Initialize task data to update. */
        $newTask = new stdclass();
        $newTask->estimate       = $estimate;
        $newTask->consumed       = $consumed;
        $newTask->left           = $left;
        $newTask->lastEditedBy   = $this->app->user->account;
        $newTask->lastEditedDate = helper::now();

        /* Update task data. */
        $this->dao->update(TABLE_TASK)->data($newTask)->autoCheck()->where('id')->eq($taskID)->exec();
        return !dao::isError();
    }

    /**
     * 创建一个任务。
     * Create a task.
     *
     * @param  object    $task
     * @param  bool      $createAction
     * @access public
     * @return false|int
     */
    public function create(object $task, bool $createAction = true): false|int
    {
        /* If the lifetime if the execution is ops and the attribute of execution is request or review, remove story from required fields. */
        $execution      = $this->dao->findByID($task->execution)->from(TABLE_PROJECT)->fetch();
        $requiredFields = ',' . $this->config->task->create->requiredFields . ',';
        if($execution && $this->isNoStoryExecution($execution)) $requiredFields = str_replace(',story,', ',', $requiredFields);

        /* Insert task data. */
        if(empty($task->assignedTo)) unset($task->assignedDate);
        $this->dao->insert(TABLE_TASK)->data($task)
            ->checkIF($task->estimate != '', 'estimate', 'float')
            ->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        /* Get task id. */
        $taskID = $this->dao->lastInsertID();

        if($task->parent)
        {
            $task->id = $taskID;
            $this->updateParent($task, false);
            unset($task->id);
        }
        else
        {
            $this->dao->update(TABLE_TASK)->set('path')->eq(",$taskID,")->where('id')->eq($taskID)->exec();
        }

        /* Insert task desc data. */
        $taskSpec = new stdclass();
        $taskSpec->task       = $taskID;
        $taskSpec->version    = $task->version;
        $taskSpec->name       = $task->name;
        if(!empty($task->estStarted)) $taskSpec->estStarted = $task->estStarted;
        if(!empty($task->deadline)) $taskSpec->deadline = $task->deadline;
        $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();

        if(dao::isError()) return false;

        if($createAction)
        {
            $this->loadModel('action')->create('task', $taskID, 'Opened', '');
            if(!empty($task->assignedTo)) $this->action->create('task', $taskID, 'Assigned', '', $task->assignedTo);
        }
        $this->loadModel('file')->updateObjectID($this->post->uid, $taskID, 'task');
        $this->loadModel('score')->create('task', 'create', $taskID);
        if(dao::isError()) return false;

        return $taskID;
    }

    /**
     * 从GitLab议题创建任务。
     * Create task from gitlab issue.
     *
     * @param  object    $task
     * @param  int       $executionID
     * @access public
     * @return int
     */
    public function createTaskFromGitlabIssue(object $task, int $executionID): int|false
    {
        $task->version      = 1;
        $task->openedBy     = $this->app->user->account;
        $task->lastEditedBy = $this->app->user->account;
        $task->assignedDate = isset($task->assignedTo) ? helper::now() : null;
        $task->story        = 0;
        $task->module       = 0;
        $task->estimate     = 0;
        $task->deadline     = $task->deadline ?? null;
        $task->estStarted   = null;
        $task->left         = 0;
        $task->pri          = 3;
        $task->type         = 'devel';
        $task->execution    = $task->execution ?? $executionID;
        $task->project      = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('project');

        /* Set project of the task to 0 if the project of execution is not exist. */
        if(empty($task->project)) $task->project = 0;

        $this->dao->insert(TABLE_TASK)->data($task, 'id,product')
             ->autoCheck()
             ->batchCheck($this->config->task->create->requiredFields, 'notempty')
             ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
             ->exec();

        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 创建事务类型的任务。
     * Create a task of affair type.
     *
     * @param  object      $task
     * @param  array       $assignedToList
     * @access public
     * @return false|array
     */
    public function createTaskOfAffair(object $task, array $assignedToList): false|array
    {
        $taskIdList = array();
        foreach($assignedToList as $assignedTo)
        {
            /* If the type of task is affair and assignedTo is empty, skip it. */
            if(count($assignedToList) > 1 && empty($assignedTo)) continue;

            $task->assignedTo = $assignedTo;
            $taskID = $this->create($task);
            if(!$taskID) return false;

            $taskIdList[] = $taskID;
        }

        if(dao::isError()) return false;

        return $taskIdList;
    }

    /**
     * 创建测试类型的任务。
     * Create a test type task.
     *
     * @param  object    $task
     * @param  array     $testTasks
     * @access public
     * @return false|int
     */
    public function createTaskOfTest(object $task, array $testTasks): false|int
    {
        $this->config->task->create->requiredFields = str_replace(array(',estimate,', ',story,', ',estStarted,', ',deadline,', ',module,'), ',', ",{$this->config->task->create->requiredFields},");

        $taskID = $this->create($task);
        if(!$taskID) return false;

        /* If the current task has test subtasks, create test subtasks and update the task information. */
        if(!empty($testTasks))
        {
            $this->createTestChildTasks($taskID, $testTasks);
            $this->computeWorkingHours($taskID);
            $this->computeBeginAndEnd($taskID);
            $this->dao->update(TABLE_TASK)->set('isParent')->eq(1)->where('id')->eq($taskID)->exec();
        }

        if(dao::isError()) return false;
        return $taskID;
    }

    /**
     * 创建关联需求的测试类型的子任务。
     * Create a subtask for the test type story with the story.
     *
     * @param  int      $taskID
     * @param  object[] $testTasks
     * @access public
     * @return void
     */
    public function createTestChildTasks(int $taskID, array $testTasks)
    {
        $this->loadModel('action');

        /* Get the stories of the test tasks. */
        $testStoryIdList = array();
        foreach($testTasks as $task)
        {
            if(!isset($task->story)) continue;
            $testStoryIdList[$task->story] = $task->story;
        }

        $testStories = $this->dao->select('id,title,version,module')->from(TABLE_STORY)->where('id')->in($testStoryIdList)->fetchAll('id');
        $parentTask  = $this->fetchById($taskID);
        foreach($testTasks as $task)
        {
            /* If the story id is not exist, skip it. */
            $storyID = isset($task->story) ? $task->story : 0;
            if(!isset($testStories[$storyID])) continue;

            /* Construct a task and create it. */
            $task->parent       = $taskID;
            $task->storyVersion = $testStories[$storyID]->version;
            $task->name         = $this->lang->task->lblTestStory . " #{$storyID} " . $testStories[$storyID]->title;
            $task->module       = $testStories[$storyID]->module;
            $this->dao->insert(TABLE_TASK)->data($task)->exec();

            /* Get task id and create a action. */
            $childTaskID = $this->dao->lastInsertID();
            $this->action->create('task', $childTaskID, 'Opened');

            $this->dao->update(TABLE_TASK)->set('path')->eq("{$parentTask->path}{$childTaskID},")->where('id')->eq($childTaskID)->exec();
            if($this->config->edition != 'open')
            {
                $relation = new stdClass();
                $relation->relation = 'generated';
                $relation->AID      = $task->story;
                $relation->AType    = 'story';
                $relation->BID      = $childTaskID;
                $relation->BType    = 'task';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            $this->taskTao->updateRelation($childTaskID, (int)$taskID);
        }
    }

    /**
     * 创建多人任务。
     * Create a multiplayer task.
     *
     * @param  object    $task
     * @param  object    $teamData
     * @access public
     * @return false|int
     */
    public function createMultiTask(object $task, object $teamData): false|int
    {
        $task->assignedTo = '';
        $taskID = $this->create($task, false);
        if(!$taskID) return false;

        if(count(array_filter($teamData->team)) < 2) return $taskID;

        /* Manage the team of task and calculate the team hours. */
        $task->id = $taskID;
        $teams    = $this->manageTaskTeam($task->mode, $task, $teamData);
        if($teams)
        {
            $this->computeMultipleHours($task);
            $this->loadModel('program')->refreshProjectStats($task->project);
        }

        if(!empty($task->story))
        {
            $storyVersion = $this->dao->select('version')->from(TABLE_STORY)->where('id')->eq($task->story)->limit(1)->fetch('version');
            $this->dao->update(TABLE_TASKTEAM)->set('storyVersion')->eq($storyVersion)->where('task')->eq($task->id)->exec();
        }

        /* Send mail after created team. */
        $this->loadModel('action')->create('task', $taskID, 'Opened', '');

        return $taskID;
    }

    /**
     * 删除工时并更新任务。 Delete the work hour from the task.
     *
     * @param  int    $effortID
     * @access public
     * @return array|false
     */
    public function deleteWorkhour(int $effortID)
    {
        $effort = $this->getEffortByID($effortID);
        if(empty($effort))
        {
            dao::$errors[] = $this->lang->notFound;
            return false;
        }

        $task = $this->getById($effort->objectID);
        $data = $this->taskTao->getTaskAfterDeleteWorkhour($effort, $task);

        /* 删除工时；如果任务是多人任务，则重新计算工时。*/
        $this->dao->update(TABLE_EFFORT)->set('deleted')->eq('1')->where('id')->eq($effort->id)->exec();
        if(!empty($task->team)) $data = $this->computeMultipleHours($task, $data) ?? $data;

        /* 更新任务、父任务状态、需求阶段。*/
        $this->dao->update(TABLE_TASK)->data($data) ->where('id')->eq($effort->objectID)->exec();

        if($task->consumed != $data->consumed || $task->left != $data->left) $this->loadModel('program')->refreshProjectStats($task->project);
        if($task->parent > 0) $this->updateParentStatus($task->id);
        if($task->story)  $this->loadModel('story')->setStage($task->story);

        /* 计算此工时所对应任务的变更。*/
        $oldTask = new stdclass();
        $oldTask->consumed = $task->consumed;
        $oldTask->left     = $task->left;
        $oldTask->status   = $task->status;

        $newTask = new stdclass();
        $newTask->consumed = $data->consumed;
        $newTask->left     = $data->left;
        $newTask->status   = $data->status;

        if(!dao::isError()) return common::createChanges($oldTask, $newTask);
    }

    /**
     * 完成任务。
     * Finish a task.
     *
     * @param  object     $oldTask
     * @param  object     $task
     * @access public
     * @return bool|array
     */
    public function finish(object $oldTask, object $task): bool|array
    {
        $currentTeam = !empty($oldTask->team) ? $this->getTeamByAccount($oldTask->team) : array();
        if($currentTeam)
        {
            $consumed = $currentTeam->consumed + (float)$this->post->currentConsumed;
            $this->dao->update(TABLE_TASKTEAM)->set('left')->eq(0)->set('consumed')->eq($consumed)->set('status')->eq('done')->where('id')->eq($currentTeam->id)->exec();
            $task = $this->computeMultipleHours($oldTask, $task);
        }

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq((int)$oldTask->id)->exec();

        if(dao::isError()) return false;

        if($task->consumed != $oldTask->consumed || $task->left != $oldTask->left) $this->loadModel('program')->refreshProjectStats($oldTask->project);
        return common::createChanges($oldTask, $task);
    }

    /**
     * 获取多人串行任务的指派人。
     * Get the assignedTo for the multiply linear task.
     *
     * @param  string|array $members
     * @param  object       $task
     * @param  string       $type    current|next
     * @access public
     * @return string
     */
    public function getAssignedTo4Multi(string|array|bool $members, object $task, string $type = 'current'): string
    {
        if(!$members || empty($task->team) || $task->mode != 'linear') return $task->assignedTo;

        /* Format task team members. */
        if(!is_array($members)) $members = explode(',', trim($members, ','));
        $members = array_values($members);
        if(is_object($members[0])) $members = array_column($members, 'account');

        /* Get the member of the first unfinished task. */
        $teamHours = array_values($task->team);
        foreach($members as $i => $account)
        {
            if(isset($teamHours[$i]) && $teamHours[$i]->status == 'done') continue;
            if($type == 'current') return $account;
            break;
        }

        /* Get the member of the second unfinished task. */
        if($type == 'next' && isset($members[$i + 1])) return $members[$i + 1];

        return $task->openedBy;
    }

    /**
     * 通过任务ID获取任务的信息。
     * Get task info by ID.
     *
     * @param  int          $taskID
     * @param  bool         $setImgSize
     * @access public
     * @return false|object
     */
    public function getByID(int $taskID, bool $setImgSize = false, $vision = ''): false|object
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getTask();

        if($vision == '') $vision = $this->config->vision; // TODO: $vision is for compatibling with viewing drill data.
        $task = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.id')->eq($taskID)
            ->beginIf($vision != 'all')->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->fetch();
        if(!$task) return false;

        /* Format data. */
        $task->openedDate     = !empty($task->openedDate)     ? substr($task->openedDate, 0, 19)     : null;
        $task->finishedDate   = !empty($task->finishedDate)   ? substr($task->finishedDate, 0, 19)   : null;
        $task->canceledDate   = !empty($task->canceledDate)   ? substr($task->canceledDate, 0, 19)   : null;
        $task->closedDate     = !empty($task->closedDate)     ? substr($task->closedDate, 0, 19)     : null;
        $task->lastEditedDate = !empty($task->lastEditedDate) ? substr($task->lastEditedDate, 0, 19) : null;
        $task->realStarted    = !empty($task->realStarted)    ? substr($task->realStarted, 0, 19)    : null;

        /* Get the child tasks of the parent task. */
        $childIdList = $this->getAllChildId($taskID, false);
        $children    = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.id')->in($childIdList)
            ->beginIf($vision != 'all')->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->fetchAll('id', false);

        foreach($children as $child)
        {
            $child->team    = array();
            $child->members = array();
        }

        $task->children = $this->processTasks($children);

        if($task->parent > 0) $task->parentName = $this->dao->findById($task->parent)->from(TABLE_TASK)->fetch('name');

        /* Get task team and team members. */
        if(!empty($task->mode))
        {
            $task->members = array();
            $task->team    = $this->getTeamByTask($taskID);
            foreach($task->team as $member)
            {
                $task->members[$member->account] = $member->account;
                if($member->account == $this->app->user->account) $task->storyVersion = $member->storyVersion;
            }
        }
        else
        {
            $task->members = array();
            $task->team    = false;
        }

        $task = $this->loadModel('file')->replaceImgURL($task, 'desc');
        $task->files = $this->file->getByObject('task', $taskID);
        if($setImgSize && $task->desc) $task->desc = $this->file->setImgSize($task->desc);
        /* Get related test cases. */
        if($task->story) $task->cases = $this->dao->select('id, title')->from(TABLE_CASE)->where('story')->eq($task->story)->andWhere('storyVersion')->eq($task->storyVersion)->andWhere('deleted')->eq('0')->fetchPairs();

        /* Process a task, compute its progress and get its related information. */
        return $this->processTask($task, false);
    }

    /**
     * 通过任务ID列表批量获取任务信息。
     * Get the task information from the task ID list.
     *
     * @param  array    $taskIdList
     * @access public
     * @return object[]
     */
    public function getByIdList(array $taskIdList = array()): array
    {
        if(empty($taskIdList)) return array();
        return $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($taskIdList)
            ->fetchAll('id', false);
    }

    /**
     * 获取指定的任务 id name 键值对。
     * Get task pairs by task ID list.
     *
     * @param  array    $taskIdList
     * @access public
     * @return array
     */
    public function getPairsByIdList(array $taskIdList = array()) : array
    {
        $taskPairs = $this->dao->select('id, name')->from(TABLE_TASK)->where('deleted')->eq('0')->andWhere('id')->in($taskIdList)->fetchPairs();
        return array(0 => '') + $taskPairs;
    }

    /**
     * 获取按每天完成统计的报表数据。
     * Get report data of finished tasks per day.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfFinishedTasksPerDay(): array
    {
        $tasks = $this->dao->select("id, DATE_FORMAT(`finishedDate`, '%Y-%m-%d') AS `date`")->from(TABLE_TASK)
            ->where($this->reportCondition())
            ->andWhere('finishedDate')->notZeroDatetime()
            ->orderBy('finishedDate asc')
            ->fetchAll('id');
        if(!$tasks) return array();

        return $this->processData4Report($tasks, array(), 'date');
    }

    /**
     * 获取按指派给统计的报表数据。
     * Get report data of tasks per assignedTo.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerAssignedTo(): array
    {
        $tasks = $this->dao->select('id,assignedTo')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'assignedTo');

        /* Get user's realname. */
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($dataList as $account => $data)
        {
            if(isset($this->users[$account])) $data->name = $this->users[$account];
        }
        return $dataList;
    }

    /**
     * 获取按关闭原因统计的报表数据。
     * Get report data of tasks per closed reason.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerClosedReason(): array
    {
        $tasks = $this->dao->select('id,closedReason')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->andWhere('closedReason')->ne('')
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'closedReason');
        foreach($dataList as $closedReason => $data)
        {
            if(isset($this->lang->task->reasonList[$closedReason])) $data->name = $this->lang->task->reasonList[$closedReason];
        }
        return $dataList;
    }

    /**
     * 获取按消耗时间统计的报表数据。
     * Get report data of tasks per consumed.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerConsumed(): array
    {
        $tasks = $this->dao->select('id,consumed')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,consumed')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'consumed');
    }

    /**
     * 获取按照截止日期统计的报表数据。
     * Get report data of tasks per deadline.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerDeadline(): array
    {
        $tasks = $this->dao->select('id,deadline')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->orderBy('deadline asc')
            ->fetchAll('id');
        if(!$tasks) return array();

        return $this->processData4Report($tasks, array(), 'deadline');
    }

    /**
     * 获取按预计时间统计的报表数据。
     * Get report data of tasks per estimate.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerEstimate(): array
    {
        $tasks = $this->dao->select('id,estimate')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,estimate')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'estimate');
    }

    /**
     * 获取按执行任务数统计的报表数据。
     * Get report data of tasks per execution.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerExecution(): array
    {
        $tasks = $this->taskTao->getListByReportCondition('execution', $this->reportCondition());
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'execution');

        /* Get execution names for these tasks. */
        $executions = $this->loadModel('execution')->fetchPairs(0, 'all', true, true);
        foreach($dataList as $executionID => $data) $data->name  = isset($executions[$executionID]) ? $executions[$executionID] : $this->lang->report->undefined;
        return $dataList;
    }

    /**
     * 获取按由谁完成统计的报表数据。
     * Get report data of tasks per finishedBy.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerFinishedBy(): array
    {
        $tasks = $this->dao->select('id,finishedBy')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->andWhere('finishedBy')->ne('')
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'finishedBy');

        /* Get user's realname. */
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($dataList as $account => $data)
        {
            if(isset($this->users[$account])) $data->name = $this->users[$account];
        }
        return $dataList;
    }

    /**
     * 获取按剩余时间统计的报表数据。
     * Get report data of tasks per left.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerLeft(): array
    {
        $tasks = $this->dao->select('id,`left`')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,`left`')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'left');
    }

    /**
     * 获取按模块任务数统计的报表数据。
     * Get report data of tasks per module.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerModule(): array
    {
        $tasks = $this->dao->select('id,module')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'module');

        /* Get modules name. */
        $modules = $this->loadModel('tree')->getModulesName(array_keys($dataList), true, true);
        foreach($dataList as $moduleID => $data)
        {
            $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        }
        return $dataList;
    }

    /**
     * 获取按照优先级统计的报表数据。
     * Get report data of tasks per priority.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerPri(): array
    {
        $tasks = $this->dao->select('id,pri')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'pri');

        foreach($dataList as $pri) $pri->name = $this->lang->task->priList[$pri->name];
        return $dataList;
    }

    /**
     * 获取按任务类型统计的报表数据。
     * Get report data of tasks per type.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerType(): array
    {
        $tasks = $this->dao->select('id,type')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'type');

        foreach($dataList as $type => $data)
        {
            if(isset($this->lang->task->typeList[$type])) $data->name = $this->lang->task->typeList[$type];
        }
        return $dataList;
    }

    /**
     * 按照任务状态统计的报表数据。
     * Get report data of status.
     *
     * @access public
     * @return object[]
     */
    public function getDataOfTasksPerStatus(): array
    {
        $tasks = $this->dao->select('id,status')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $dataList = $this->processData4Report($tasks, array(), 'status');
        foreach($dataList as $status => $data) $data->name = $this->lang->task->statusList[$status];

        return $dataList;
    }

    /**
     * 根据日志ID获取日志信息和是否最后一次日志。
     * Get effort data and check last by id.
     *
     * @param  int    $effortID
     * @access public
     * @return false|object
     */
    public function getEffortByID(int $effortID): false|object
    {
        $effort = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('id')->eq($effortID)
            ->fetch();
        if(!$effort ) return false;

        /* If the estimate is the last of its task, status of task will be checked. */
        $lastID = $this->dao->select('id')->from(TABLE_EFFORT)
            ->where('objectID')->eq($effort->objectID)
            ->andWhere('objectType')->eq('task')
            ->andWhere('deleted')->eq('0')
            ->orderBy('date_desc,id_desc')->limit(1)->fetch('id');

        $effort->isLast = $lastID == $effort->id;
        return $effort;
    }

    /**
     * 获取执行下的任务列表信息。
     * Get the task list under a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return array
     */
    public function getExecutionTasks(int $executionID, int $productID = 0, string|array $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', object $pager = null): array
    {
        $tasks = $this->taskTao->fetchExecutionTasks($executionID, $productID, $type, $modules, $orderBy, $pager);
        if(empty($tasks)) return array();

        $taskTeam = $this->taskTao->getTeamMembersByIdList(array_keys($tasks));
        foreach($tasks as $task)
        {
            if(isset($taskTeam[$task->id])) $tasks[$task->id]->team = $taskTeam[$task->id];
        }

        if($this->config->vision == 'lite') $tasks = $this->appendLane($tasks);

        $userList     = $this->loadModel('user')->getPairs('noletter|noclosed');
        $parentIdList = array();
        foreach($tasks as &$task)
        {
            $task->assignedToRealName = zget($userList, $task->assignedTo);

            if($task->parent <= 0 || isset($tasks[$task->parent]) || isset($parentIdList[$task->parent])) continue;
            $parentIdList[$task->parent] = $task->parent;
        }

        return $this->processTasks($tasks);
    }

    /**
     * 获取任务 id:name 的数组。
     * Get an array of task id:name.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getExecutionTaskPairs(int $executionID, string $status = 'all', string $orderBy = 'finishedBy, id_desc'): array
    {
        $tasks = $this->dao->select('t1.id,t1.name,t1.parent,t2.realname AS finishedByRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.finishedBy = t2.account')
            ->where('t1.execution')->eq((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        $taskPairs = array();
        foreach($tasks as $taskID => $task)
        {
            $prefix = $task->parent > 0 ? "[{$this->lang->task->childrenAB}] " : '';
            $taskPairs[$taskID] = $prefix . "$task->id:" . (empty($task->finishedByRealName) ? '' : "$task->finishedByRealName:") . "$task->name";;
        }
        return $taskPairs;
    }

    /**
     * 获取导出的任务数据。
     * Get export task information.
     *
     * @param  string $orderBy
     * @access public
     * @return object[]
     */
    public function getExportTasks(string $orderBy): array
    {
        $sort  = common::appendOrder($orderBy);
        $tasks = array();
        if($this->session->taskOnlyCondition)
        {
            $tasks = $this->dao->select('*')->from(TABLE_TASK)->alias('t1')->where($this->session->taskQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($sort)
                ->fetchAll('id', false);

            foreach($tasks as $key => $task)
            {
                /* Compute task progress. */
                if($task->consumed == 0 && $task->left == 0)
                {
                    $task->progress = 0;
                }
                elseif($task->consumed != 0 && $task->left == 0)
                {
                    $task->progress = 100;
                }
                else
                {
                    $task->progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
                }
                $task->progress .= '%';
            }
        }
        elseif($this->session->taskQueryCondition)
        {
            $stmt = $this->dbh->query($this->session->taskQueryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
            while($row = $stmt->fetch()) $tasks[$row->id] = $row;
        }

        return $this->processExportTasks($tasks);
    }

    /**
     * 通过给定条件获取任务列表信息。
     * Get task list by condition.
     *
     * @param  object      $condition
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return object[]
     */
    public function getListByCondition(object $condition, string $orderBy = 'id_desc', object|null $pager = null): array
    {
        $defaultValueList = array('priList' => array(), 'assignedToList' => array(), 'statusList' => array(), 'idList' => array(), 'taskName' => '');
        foreach($defaultValueList as $key => $defaultValue)
        {
            if(!isset($condition->$key))
            {
                $condition->$key = $defaultValue;
                continue;
            }
            if(strripos($key, 'list') === strlen($key) - 4 && !is_array($condition->$key)) $condition->$key = array_filter(explode(',', $condition->$key));
        }

        return $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($condition->priList))->andWhere('pri')->in($condition->priList)->fi()
            ->beginIF(!empty($condition->assignedToList))->andWhere('assignedTo')->in($condition->assignedToList)->fi()
            ->beginIF(!empty($condition->statusList))->andWhere('status')->in($condition->statusList)->fi()
            ->beginIF(!empty($condition->idList))->andWhere('id')->in($condition->idList)->fi()
            ->beginIF(!empty($condition->taskName))->andWhere('name')->like("%{$condition->taskName}%")->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 通过需求列表获取对应的任务列表，任务只取部分属性。
     * Get the list of task information by the list of stories.
     *
     * @param  int[]    $storyIdList
     * @param  int      $executionID
     * @param  int      $projectID
     * @access public
     * @return object[]
     */
    public function getListByStories(array $storyIdList, int $executionID = 0, int $projectID = 0): array
    {
        return $this->dao->select('id, story, parent, name, assignedTo, pri, status, estimate, consumed, closedReason, `left`')
            ->from(TABLE_TASK)
            ->where('story')->in($storyIdList)
            ->andWhere('deleted')->eq('0')
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchAll('id');
    }

    /**
     * 通过需求获取对应的任务列表，任务只取部分属性。
     * Get the task list by a story.
     *
     * @param  int   $storyID
     * @param  int   $executionID
     * @param  int   $projectID
     * @access public
     * @return object[]
     */
    public function getListByStory(int $storyID, int $executionID = 0, int $projectID = 0): array
    {
        $tasks = $this->dao->select('id, parent, name, assignedTo, pri, status, isParent, estimate, consumed, closedReason, `left`')
            ->from(TABLE_TASK)
            ->where('story')->eq($storyID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchAll('id');

        $parentIdList = array();
        foreach($tasks as $task)
        {
            /* 如果任务不是父任务，或者父任务已经在任务列表中，或者父任务已经在当前列表中，则跳过处理。*/
            if($task->parent <= 0 || isset($parentIdList[$task->parent])) continue;
            $parentIdList[$task->parent] = $task->parent;
        }

        $parentTasks = $this->getByIdList($parentIdList);
        $tasks       = $this->taskTao->buildTaskTree($tasks, $parentTasks); /* 将子任务放到父任务里，或者将父任务的名字放到子任务里。*/
        return $this->taskTao->batchComputeProgress($tasks); /* 通过任务的消耗和剩余工时计算任务及其子任务的进度，结果以百分比的数字部分显示。*/
    }

    /**
     * 获取任务的团队成员。
     * Get task's team member pairs.
     *
     * @param  object $task
     * @access public
     * @return array
     */
    public function getMemberPairs(object $task): array
    {
        if(!is_array($task->team)) return array();

        $users   = $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $members = array();
        foreach($task->team as $member)
        {
            if(isset($users[$member->account])) $members[$member->account] = $users[$member->account];
        }
        return $members;
    }

    /**
     * 获取并行任务的团队成员。
     * Get parallel task's members.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getMultiTaskMembers(int $taskID): array
    {
        $taskType = $this->dao->select('mode')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('mode');
        if($taskType != 'multi') return array();

        $teamMembers = $this->dao->select('account')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->fetchPairs();
        return empty($teamMembers) ? $teamMembers : array_keys($teamMembers);
    }

    /**
     * 获取父任务 id:name 的数组。
     * Get an array of parent task id:name.
     *
     * @param  int    $executionID
     * @param  string $appendIdList
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getParentTaskPairs(int $executionID, string $appendIdList = '', int $taskID = 0): array
    {
        /*
         过滤多人任务。
         过滤已经记录工时的任务。
         过滤已取消、已关闭的任务。
         过滤自己和后代任务。
        */
        $children = $this->getAllChildId($taskID);
        $taskList = $this->dao->select('id, name, isParent, consumed')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notin('cancel,closed')
            ->andWhere('parent')->eq('0')
            ->andWhere('execution')->eq($executionID)
            ->andWhere('mode')->eq('')
            ->beginIF($children)->andWhere('id')->notin($children)->fi()
            ->beginIF($appendIdList)->orWhere('id')->in($appendIdList)->fi()
            ->fetchAll();

        $taskPairs = array();
        foreach($taskList as $task)
        {
            if(!$task->isParent && $task->consumed > 0) continue;
            $taskPairs[$task->id] = $task->name;
        }

        return $taskPairs;
    }

    /**
     * 给任务的下拉列表增加标签。
     * Add label to task dropdown list.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function addTaskLabel(array $tasks): array
    {
        $taskList = $this->getByIdList(array_keys($tasks));

        $options = array();
        foreach($taskList as $task)
        {
            if($task->isParent)
            {
                $options[] = array('text' => array('html' => "<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>{$this->lang->task->parentAB}</span> #{$task->id} {$task->name}"), 'hint' => $task->name, 'value' => $task->id, 'keys' => $task->name);
            }
            elseif($task->parent > 0)
            {
                $options[] = array('text' => array('html' => "<span class='label rounded-xl ring-0 inverse bg-opacity-10 text-inherit mr-1 size-sm'>{$this->lang->task->childrenAB}</span> #{$task->id} {$task->name}"), 'hint' => $task->name, 'value' => $task->id, 'keys' => $task->name);
            }
            else
            {
                $options[] = array('text' => "#{$task->id} {$task->name}", 'hint' => $task->name, 'value' => $task->id, 'keys' => $task->name);
            }
        }

        return $options;
    }

    /**
     * 获取任务所有子任务id。
     * Get all child task id.
     *
     * @param  int    $taskID
     * @param  bool   $includeSelf
     * @access public
     * @return array
     */
    public function getAllChildId(int $taskID, bool $includeSelf = true): array
    {
        return $this->dao->select('id')->from(TABLE_TASK)
            ->where('path')->like("%,$taskID,%")
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$includeSelf)->andWhere('id')->ne($taskID)->fi()
            ->fetchPairs('id');
    }

    /**
     * 获取关联需求的任务数。
     * Get the number of tasks linked with the story.
     *
     * @param  array  $stories
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryTaskCounts(array $stories, int $executionID = 0): array
    {
        if(empty($stories)) return array();

        $taskCounts = $this->dao->select('story, COUNT(id) AS tasks')
            ->from(TABLE_TASK)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->groupBy('story')
            ->fetchPairs();

        foreach($stories as $storyID)
        {
            if(!isset($taskCounts[$storyID])) $taskCounts[$storyID] = 0;
        }
        return $taskCounts;
    }

    /**
     * 根据任务、人员和日志ID查询并排序任务的日志列表。
     * Get task efforts by taskID, account or effortID.
     *
     * @param  int|array $taskIdList
     * @param  string    $account
     * @param  int       $effortID
     * @param  string    $orderBy
     * @access public
     * @return array
     */
    public function getTaskEfforts(int|array $taskIdList, string $account = '', int $effortID = 0, string $orderBy = 'date,id'): array
    {
        return $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('objectType')->eq('task')
            ->andWhere('objectID')->in($taskIdList)
            ->andWhere('deleted')->eq('0')
            ->beginIF($account)->andWhere('account')->eq($account)->fi()
            ->beginIF($effortID)->orWhere('id')->eq($effortID)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);
    }

    /**
     * 获取用户所在多人任务的团队工序。
     * Get the team process of the multi-task by account.
     *
     * @param  array  $teams
     * @param  string $account
     * @param  array  $extra
     * @access public
     * @return object|bool
     */
    public function getTeamByAccount(array $teams, string $account = '', array $extra = array('filter' => 'done')): object|bool
    {
        if(empty($account)) $account = $this->app->user->account;

        $filterStatus = zget($extra, 'filter', '');
        $effortID     = zget($extra, 'effortID', '');
        $repeatUsers  = array();
        $taskID       = 0;
        foreach($teams as $team)
        {
            if(isset($extra['order']) && $team->order == $extra['order'] && $team->account == $account) return $team;
            if(empty($taskID) && $effortID) $taskID = $team->task;
            if(isset($repeatUsers[$team->account]))  $repeatUsers[$team->account] = 1;
            if(!isset($repeatUsers[$team->account])) $repeatUsers[$team->account] = 0;
        }

        /*
         * 1. No repeat account or account is not repeat account;
         * 2. Not by effort;
         * Then direct get team by account.
         */
        if(empty($repeatUsers[$account]) || empty($effortID))
        {
            foreach($teams as $team)
            {
                if(empty($effortID) && $filterStatus && $team->status == $filterStatus) continue;
                if($team->account == $account) return $team;
            }
        }
        elseif($effortID)
        {
            $efforts  = $this->getTaskEfforts($taskID, '', $effortID);
            $prevTeam = current($teams);
            foreach($efforts as $effort)
            {
                $currentTeam = reset($teams);
                if($effort->id == $effortID)
                {
                    if($effort->account == $currentTeam->account) return $currentTeam;
                    if($effort->account == $prevTeam->account)    return $prevTeam;
                    return false;
                }

                if($effort->left == 0 && $currentTeam->account == $effort->account) $prevTeam = array_shift($teams);
            }
        }

        return false;
    }

    /**
     * 获取指派用户和抄送给用户列表。
     * Get toList and ccList.
     *
     * @param  object      $task
     * @param  string      $action
     * @access public
     * @return array|false
     */
    public function getToAndCcList(object $task, string $action): array|false
    {
        /* Set assignedTo and mailto. */
        $assignedTo = $task->assignedTo;
        $mailto     = empty($task->mailto) ? array() : explode(',', $task->mailto);
        if($task->mode == 'multi')
        {
            $teamList   = $this->getMultiTaskMembers($task->id);
            $teamList   = implode(',', $teamList);
            $assignedTo = $teamList;
        }

        /* If the assignor is closed, treat the completion person as the assignor. */
        if(strtolower($assignedTo) == 'closed') $assignedTo = empty($task->finishedBy) ? $task->openedBy : $task->finishedBy;

        /* If the child task is paused, closed or canceled, append the mailto from parent task. */
        if($task->parent > 0)
        {
            $appendParent = false;
            if(in_array($action, array('paused', 'closed', 'canceled')))
            {
                $actionExtra = $this->dao->select('id,extra')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($task->id)->andWhere('action')->eq($action)->orderBy('id_desc')->limit(1)->fetch('extra');
                if($actionExtra != 'autobyparent') $appendParent = true;
            }
            elseif($action == 'edited')
            {
                $actionExtra = $this->dao->select('id,extra')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->eq($task->id)->andWhere('action')->eq($action)->orderBy('id_desc')->limit(1)->fetch('extra');
                if($actionExtra == 'statuschanged' && in_array($task->status, array('pause', 'close', 'cancel'))) $appendParent = true;
            }

            if($appendParent)
            {
                $parentTasks = $this->dao->select('id,assignedTo,finishedBy,mailto')->from(TABLE_TASK)->where('id')->in($task->path)->andWhere('id')->ne($task->id)->fetchAll('id');
                foreach($parentTasks as $parentTask)
                {
                    $parentAssignedTo = $parentTask->assignedTo;
                    if(strtolower($parentAssignedTo) == 'closed') $parentAssignedTo = empty($parentTask->finishedBy) ? $parentTask->openedBy : $parentTask->finishedBy;

                    $mailto[] = $parentAssignedTo;
                    $mailto   = array_merge($mailto, empty($parentTask->mailto) ? array() : explode(',', $parentTask->mailto));
                }
            }
        }
        $mailto = array_unique(array_filter(array_map(function($account) use($assignedTo){ return $account == $assignedTo ? '' : $account;}, $mailto)));

        if(empty($assignedTo) && empty($mailto)) return false;

        /* If the assignor is empty, consider the first one with the cc as the assignor. */
        if(empty($assignedTo)) $assignedTo = array_shift($mailto);

        if(in_array($action, array('paused', 'closed', 'canceled')) && $task->parent > 0)
        {
            $parentTasks = $this->dao->select('id,assignedTo,finishedBy,mailto')->from(TABLE_TASK)->where('id')->in($task->path)->fetchAll('id');
            foreach($parentTasks as $parentTask)
            {
                $mailto[] = (strtolower($parentTask->assignedTo) == 'closed') ? $parentTask->finishedBy : $parentTask->assignedTo;
                $mailto  += is_null($parentTask->mailto) ? array() : explode(',', trim($parentTask->mailto, ','));
            }
        }
        $mailto = array_unique(array_filter(array_map(function($account) use($assignedTo){ return $account == $assignedTo ? '' : $account;}, $mailto)));

        return array($assignedTo, implode(',', $mailto));
    }

    /**
     * 获取未完成的任务。
     * Get the unfinish tasks.
     *
     * @param  int      $executionID
     * @access public
     * @return object[]
     */
    public function getUnfinishTasks(int $executionID): array
    {
        return $this->dao->select('*')->from(TABLE_TASK)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->in('wait,doing,pause')
            ->fetchAll('id', false);
    }

    /**
     * 获取暂停的项目和执行下，指派给指定用户的任务信息。
     * Get tasks assigned to the user for the suspended project and execution.
     *
     * @param  string   $account
     * @access public
     * @return object[]
     */
    public function getUserSuspendedTasks(string $account): array
    {
        return $this->dao->select('t1.*')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on("t1.project = t3.id")
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('(t2.status')->eq('suspended')
            ->orWhere('t3.status')->eq('suspended')
            ->markRight(1)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetchAll('id', false);
    }

    /**
     * 通过任务类型获取用户的任务。
     * Get user tasks by type.
     *
     * @param  string   $account
     * @param  string   $type
     * @param  int      $limit
     * @param  object   $pager
     * @param  string   $orderBy
     * @param  int      $projectID
     * @access public
     * @return object[]
     */
    public function getUserTasks(string $account, string $type = 'assignedTo', int $limit = 0, object $pager = null, string $orderBy = 'id_desc', int $projectID = 0): array
    {
        if($type != 'myInvolved' && !$this->loadModel('common')->checkField(TABLE_TASK, $type)) return array();

        $tasks = $this->taskTao->fetchUserTasksByType($account, $type, $orderBy, $projectID, $limit, $pager);

        if(empty($tasks)) return $tasks;

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', false);

        $taskTeam = $this->taskTao->getTeamMembersByIdList(array_keys($tasks));
        foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;

        return $this->processTasks($tasks);
    }

    /**
     * 获取指派给用户的任务 id:name 数组。
     * Get the task id:name array assigned to the user.
     *
     * @param  string  $account
     * @param  string  $status
     * @param  array   $skipExecutionIDList
     * @param  array   $appendTaskID
     * @access public
     * @return array
     */
    public function getUserTaskPairs(string $account, string $status = 'all', array $skipExecutionIDList = array(), array $appendTaskID = array()): array
    {
        $tasks = $this->dao->select('t1.id, t1.name, t2.name as executionName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipExecutionIDList))->andWhere('t1.execution')->notin($skipExecutionIDList)->fi()
            ->beginIF(!empty($appendTaskID))->orWhere('t1.id')->in($appendTaskID)->fi()
            ->fetchAll('id');

        $taskPairs = array();
        foreach($tasks as $task) $taskPairs[$task->id] = $task->executionName . ' / ' . $task->name;

        return $taskPairs;
    }

    /**
     * 判断任务操作是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $task
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $task, string $action): bool
    {
        $action = strtolower($action);

        /* 任务不可修改的话，则无法进行操作。 */
        if(!common::canModify('task', $task)) return false;

        /* IPD任务当关联的需求池需求撤销/移除移除时，任务需要点击确认。*/
        /* IPD Task when the associated demand is retraec / unlink, the task needs to confirm . */
        if($action == 'confirmdemandretract') return !empty($task->confirmeActionType) && $task->confirmeActionType == 'confirmedretract';
        if($action == 'confirmdemandunlink')  return !empty($task->confirmeActionType) && $task->confirmeActionType == 'confirmedunlink';

        /* 如果是转任务，直接返回 true。 */
        if($action == 'totask') return true;

        /* 父任务只能编辑、创建子任务和指派。 Parent task only can edit task, create children and assign to somebody. */
        if((!empty($task->isParent)) && !in_array($action, array('edit', 'batchcreate', 'cancel', 'assignto', 'pause', 'close', 'restart', 'confirmstorychange'))) return false;

        /* 子任务、多人任务、已取消已关闭的任务不能创建子任务。Multi task and child task and canceled/closed task cannot create children. */
        if($action == 'batchcreate' && (!empty($task->team) || !empty($task->mode) || !empty($task->rawParent) || in_array($task->status, array('closed', 'cancel')))) return false;

        if(!empty($task->team))
        {
            global $app;
            $myself = new self();
            if($task->mode == 'linear')
            {
                if($action == 'assignto' && !in_array($task->status, array('done', 'cancel', 'closed'))) return false;
                if($action == 'start' && in_array($task->status, array('wait', 'doing')))
                {
                    if($task->assignedTo != $app->user->account) return false;

                    $currentTeam = $myself->getTeamByAccount($task->team, $app->user->account);
                    if($currentTeam && $currentTeam->status == 'wait') return true;
                }
                if($action == 'finish' && $task->assignedTo != $app->user->account) return false;
            }
            elseif($task->mode == 'multi')
            {
                $currentTeam = $myself->getTeamByAccount($task->team, $app->user->account);
                if($action == 'start' && in_array($task->status, array('wait', 'doing')) && $currentTeam && $currentTeam->status == 'wait') return true;
                if($action == 'finish' && (empty($currentTeam) || $currentTeam->status == 'done')) return false;
            }
        }

        $executionInfo = zget($task, 'executionInfo', array());

        /* 如果是IPD串行项目下的任务，则只有当前阶段开始以后才能开始/关闭任务。*/
        /* If it is a task under an IPD serial project, the task can be started / closed only after the current phase begins. */
        if(!empty($executionInfo->canStartExecution['disabled']) && in_array($action, array('start', 'finish', 'recordworkhour'))) return false;

        /* 根据状态判断是否可以点击。 Check clickable by status. */
        if($action == 'batchcreate')        return (empty($task->team) || empty($task->children)) && zget($executionInfo, 'type') != 'kanban';
        if($action == 'start')              return $task->status == 'wait';
        if($action == 'restart')            return $task->status == 'pause';
        if($action == 'pause')              return $task->status == 'doing';
        if($action == 'assignto')           return !in_array($task->status, array('closed', 'cancel'));
        if($action == 'close')              return $task->status == 'done' || $task->status == 'cancel';
        if($action == 'activate')           return $task->status == 'done' || $task->status == 'closed' || $task->status == 'cancel';
        if($action == 'finish')             return $task->status != 'done' && $task->status != 'closed' && $task->status != 'cancel';
        if($action == 'cancel')             return $task->status != 'done' && $task->status != 'closed' && $task->status != 'cancel';
        if($action == 'confirmstorychange') return !in_array($task->status, array('cancel', 'closed')) && !empty($task->needConfirm);

        return true;
    }

    /**
     * 检查执行是否有需求列表。
     * Check whether execution has story list.
     *
     * @param  object    $execution
     * @access public
     * @return bool
     */
    public function isNoStoryExecution(object $execution): bool
    {
        if(empty($execution)) return false;
        return $execution->lifetime == 'ops';
    }

    /**
     * 维护多人任务的团队信息。
     * Manage multi task team.
     *
     * @param  string $mode
     * @param  object $task
     * @param  object $teamData
     * @access public
     * @return array|false
     */
    public function manageTaskTeam(string $mode, object $task, object $teamData): array|false
    {
        /* Get old team member, and delete old task team. */
        $oldTeamData = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->fetchAll('account', false);
        $oldTeams    = array_values($oldTeamData);
        $oldMembers  = array_column($oldTeams, 'account');
        $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->exec();

        /* Set effort left = 0 when linear task members be changed. */
        $changeUsers = array();
        $teamData->team = array_filter($teamData->team);
        foreach($teamData->team as $index => $account)
        {
            if($mode == 'linear' && isset($oldTeams[$index]) && $oldTeams[$index]->account != $account) $changeUsers[] = $oldTeams[$index]->account;
        }

        /* Manage task team member. */
        $teams = $this->manageTaskTeamMember($mode, $task, $teamData, $oldTeamData);
        if(dao::isError()) return false;

        /* Set effort left = 0 when multi task members be removed. */
        if($mode == 'multi' && $oldMembers)
        {
            $removedMembers = array_diff($oldMembers, $teams);
            $changeUsers    = array_merge($changeUsers, $removedMembers);
        }
        if($changeUsers) $this->taskTao->resetEffortLeft($task->id, $changeUsers);

        return $teams;
    }

    /**
     * 维护多人任务团队成员信息。
     * Manage multi-task team member information.
     *
     * @param  string      $mode
     * @param  object      $task
     * @param  object      $teamData
     * @param  array       $oldTeamData
     * @access public
     * @return false|array
     */
    public function manageTaskTeamMember(string $mode, object $task, object $teamData, array $oldTeamData = array()): false|array
    {
        /* If status of the task is doing, get the person who did not complete the task. */
        $undoneUsers = array();
        if($task->status == 'doing')
        {
            $efforts = $this->getTaskEfforts($task->id);
            foreach($efforts as $effort)
            {
                if($effort->left != 0) $undoneUsers[$effort->account] = $effort->account;
                else unset($undoneUsers[$effort->account]);
            }
        }

        $minStatus = 'done';
        $teamList  = array_filter($teamData->team);
        $teams     = array();
        $order     = 1;
        foreach($teamList as $index => $account)
        {
            /* Set member information. */
            $member = new stdclass();
            $member->task     = $task->id;
            $member->order    = $order;
            $member->account  = $account;
            $member->estimate = isset($teamData->teamEstimate) ? (float)zget($teamData->teamEstimate, $index, 0.00) : 0.00;
            $member->consumed = isset($teamData->teamConsumed) ? (float)zget($teamData->teamConsumed, $index, 0.00) : 0.00;
            $member->left     = isset($teamData->teamLeft) ? (float)zget($teamData->teamLeft, $index, 0.00) : 0.00;
            $member->status   = isset($oldTeamData[$account]->status) ? $oldTeamData[$account]->status : 'wait';
            if($task->status == 'wait' && $member->estimate > 0 && $member->left == 0) $member->left = $member->estimate;
            if($task->status == 'done') $member->left = 0;
            if($member->status == 'done' && !empty($member->left)) $member->status = 'doing';

            /* Compute task status of member. */
            if($member->left == 0 && $member->consumed > 0)
            {
                $member->status = 'done';
            }
            elseif($task->status == 'doing')
            {
                $teamSource = zget($teamData->teamSource, $index);
                if(!empty($teamSource) && $teamSource != $account && isset($undoneUsers[$teamSource])) $member->transfer = $teamSource;
                if(isset($undoneUsers[$account]) && ($mode == 'multi' || ($mode == 'linear' && $minStatus != 'wait'))) $member->status = 'doing';
            }

            /* Compute multi-task status, and in a linear task, there is only one doing status. */
            if(($mode == 'linear' && $member->status == 'doing') || $member->status == 'wait') $minStatus = 'wait';
            if($minStatus != 'wait' && $member->status == 'doing') $minStatus = 'doing';

            /* Insert or update team member. */
            $this->taskTao->setTeamMember($member, $mode, isset($teams[$account]));
            if(dao::isError()) return false;
            $teams[$account] = $account;

            $order ++;
        }
        return $teams;
    }

    /**
     * 将默认图表设置与当前图表设置合并。
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string $chartType
     * @access public
     * @return void
     */
    public function mergeChartOption(string $chartType)
    {
        $chartOption  = $this->lang->task->report->$chartType;
        $commonOption = $this->lang->task->report->options;

        $chartOption->graph->caption = $this->lang->task->report->charts[$chartType];
        if(!isset($chartOption->type))   $chartOption->type   = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* merge configuration */
        foreach($commonOption->graph as $key => $value)
        {
            if(isset($chartOption->graph->$key)) continue;
            $chartOption->graph->$key = $value;
        }
    }

    /**
     * 通过缺陷(Bug)导入的任务在完成时检查是否需要更新对应缺陷的状态。
     * When task is finished, check whether need update status of bug.
     *
     * @param  object  $task
     * @access public
     * @return bool
     */
    public function needUpdateBugStatus(object $task): bool
    {
        /* If the task is not imported from a bug, return false. */
        if($task->fromBug == 0) return false;

        /* If the bug has been resolved, return false. */
        $bug = $this->loadModel('bug')->getByID($task->fromBug);
        if($bug->status == 'resolved') return false;

        return true;
    }

    /**
     * 暂停一个任务。
     * Pause a task.
     *
     * @param  object $task
     * @param  array  $output
     * @access public
     * @return array|bool
     */
    public function pause(object $task, array $output = array()): bool|array
    {
        /* Get old task. */
        $oldTask = $this->getById($task->id);

        /* Update kanban status. */
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq($task->id)->exec();

        /* If task has parent task, update status of the parent task by the child task. */
        if($oldTask->isParent) $this->updateChildrenStatus($task->id);

        /* If output is not empty, update kanban cell. */
        $this->updateKanbanCell($task->id, $output, $oldTask->execution);

        if(dao::isError()) return false;
        return common::createChanges($oldTask, $task);
    }

    /**
     * 处理报表统计数据。
     * Process data for report.
     *
     * @param  array  $tasks
     * @param  array  $children
     * @param  string $field    execution|module|assignedTo|type|pri|deadline|estimate|left|consumed|finishedBy|closedReason|status|date
     * @access public
     * @return array
     */
    public function processData4Report(array $tasks, array $children, string $field): array
    {
        if(!empty($children))
        {
            /* Remove the parent task from the tasks. */
            foreach($children as $childTask) unset($tasks[$childTask->parent]);
        }

        $fields   = array();
        $dataList = array();
        foreach($tasks as $task)
        {
            $key = strpos(',estimate,consumed,left,', ",{$field},") !== false ? helper::formatHours($task->$field) : (string)$task->$field;
            if(!isset($fields[$key])) $fields[$key] = 0;
            $fields[$key] ++;
        }

        /* Process table statistics data. */
        if($field != 'date' && $field != 'deadline') asort($fields);
        foreach($fields as $field => $count)
        {
            $data = new stdclass();
            $data->name  = $field;
            $data->value = $count;

            $dataList[$field] = $data;
        }

        return $dataList;
    }

    /**
     * 处理导出的任务信息。
     * Process export task information.
     *
     * @param  array $tasks
     * @access protected
     * @return object[]
     */
    protected function processExportTasks(array $tasks): array
    {
        /* Process multiple task info. */
        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        $users    = $this->loadModel('user')->getPairs('noletter');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team)
            {
                $tasks[$taskID]->team     = $team;
                $tasks[$taskID]->estimate = '';
                $tasks[$taskID]->left     = '';
                $tasks[$taskID]->consumed = '';

                foreach($team as $userInfo)
                {
                    $tasks[$taskID]->estimate .= zget($users, $userInfo->account) . ':' . $userInfo->estimate . "\n";
                    $tasks[$taskID]->left     .= zget($users, $userInfo->account) . ':' . $userInfo->left . "\n";
                    $tasks[$taskID]->consumed .= zget($users, $userInfo->account) . ':' . $userInfo->consumed . "\n";
                }
            }
        }

        /* Process parent-child task info. */
        $parentIdList = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parentIdList[] = $task->parent;
        }

        $parents = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($parentIdList)->fetchPairs();
        foreach($tasks as $task)
        {
            $task->parentId = $task->parent;
            $task->parent   = zget($parents, $task->parent, '');
        }

        return $tasks;
    }

    /**
     * 处理任务，计算进度、获取相关信息。
     * Process a task, compute its progress and get its relates.
     *
     * @param  object $task
     * @param  bool   $convertParent
     * @access public
     * @return object
     */
    public function processTask(object $task, bool $convertParent = true): object
    {
        $today = helper::today();

        /* Delayed or not?. */
        if(!empty($task->deadline) && !helper::isZeroDate($task->deadline))
        {
            $finishedDate = ($task->status == 'done' || $task->status == 'closed') && !helper::isZeroDate($task->finishedDate) ? substr($task->finishedDate, 0, 10) : $today;
            $actualDays   = $this->loadModel('holiday')->getActualWorkingDays($task->deadline, $finishedDate);
            $delay        = !is_array($actualDays) ? 0 : count($actualDays) - 1;
            if($delay > 0) $task->delay = $delay;
        }

        /* Story changed or not. */
        $task->needConfirm = false;
        if(!empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion)
        {
            $task->needConfirm = true;
            $task->rawStatus   = $task->status;
            $task->status      = 'changed';
        }

        /* Set product type for task. */
        if(!empty($task->product))
        {
            $product = $this->loadModel('product')->fetchByID($task->product);
            if($product) $task->productType = $product->type;
        }

        /* Get related test cases. */
        if($task->story) $task->cases = $this->dao->select('id, title')->from(TABLE_CASE)->where('story')->eq($task->story)->andWhere('storyVersion')->eq($task->storyVersion)->andWhere('deleted')->eq('0')->fetchPairs();

        /* Set realname to task.*/
        if($task->assignedTo != 'closed') $task->assignedToRealName = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($task->assignedTo)->fetch('realname');

        /* Set closed realname. */
        if($task->assignedTo == 'closed') $task->assignedToRealName = 'Closed';

        $task->progress = $this->taskTao->computeTaskProgress($task);

        if($task->mode == 'multi')
        {
            $teamMembers = $this->dao->select('t1.realname')->from(TABLE_USER)->alias('t1')
                ->leftJoin(TABLE_TASKTEAM)->alias('t2')
                ->on('t1.account = t2.account')
                ->where('t2.task')->eq($task->id)
                ->fetchPairs();
            $task->teamMembers = implode(',', array_keys($teamMembers));
        }

        foreach($task as $field => $value)
        {
            if(in_array($field, $this->config->task->dateFields) && helper::isZeroDate($value)) $task->$field = '';
        }

        $task->rawParent = $task->parent;
        if($convertParent)
        {
            $task->parent = array();
            foreach(explode(',', trim((string)$task->path, ',')) as $parentID)
            {
                if(!$parentID) continue;
                if($parentID == $task->id) continue;
                $task->parent[] = (int)$parentID;
            }
        }

        return $task;
    }

    /**
     * 批量处理任务，计算进度、获取相关信息。
     * Batch process tasks, compute their progress and get their relates.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function processTasks(array $tasks): array
    {
        $storyVersionPairs = $this->getTeamStoryVersion(array_keys($tasks));
        foreach($tasks as &$task)
        {
            $task->storyVersion = zget($storyVersionPairs, $task->id, $task->storyVersion);

            $task = $this->processTask($task, false);
            if(!empty($task->children))
            {
                foreach($task->children as &$child) $child = $this->processTask($child, false);
            }
        }

        return $tasks;
    }

    /**
     * 记录工时。
     * Record workhour and left of task.
     *
     * @param  int    $taskID
     * @param  array  $workhour the form data from POST
     * @access public
     * @return array
     */
    public function recordWorkhour(int $taskID, array $workhour): array
    {
        $task = $this->fetchByID($taskID);
        $task = $this->taskTao->formatDatetime($task);
        $task->team = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->orderBy('order')->fetchAll('id', false);

        /* Check if field is valid. */
        $workhour = $this->checkWorkhour($task, $workhour);
        if(!$workhour || dao::isError()) return array();

        /* Add field to workhour. */
        $workhour = $this->taskTao->buildWorkhour($taskID, $workhour);
        if(empty($workhour)) return array();

        $allChanges  = array();
        $oldStatus   = $task->status;
        $lastDate    = $this->dao->select('date')->from(TABLE_EFFORT)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->andWhere('deleted')->eq('0')->orderBy('date_desc,id_desc')->limit(1)->fetch('date');
        $currentTeam = !empty($task->team) ? $this->getTeamByAccount($task->team, $this->app->user->account, array()) : array();

        foreach($workhour as $record)
        {
            $this->addTaskEffort($record);
            $effortID = $this->dao->lastInsertID();

            $isFinishTask = (empty($currentTeam) && in_array($task->status, $this->config->task->unfinishedStatus)) || (!empty($currentTeam) && $currentTeam->status != 'done');
            /* Change the workhour and status of tasks through effort. */
            list($newTask, $actionID) = $this->taskTao->buildTaskForEffort($record, $task, (string)$lastDate, $isFinishTask);
            if($lastDate <= $record->date) $lastDate = $record->date;

            /* Process multi-person task. Update consumed on team table. */
            if(!empty($currentTeam))
            {
                $currentTeam->status = $record->left == 0 ? 'done' : 'doing';
                $this->taskTao->updateTeamByEffort($effortID, $record, $currentTeam, $task);
                $newTask = $this->computeMultipleHours($task, $newTask);
            }

            $changes = common::createChanges($task, $newTask, 'task');
            if($changes && $actionID) $this->loadModel('action')->logHistory($actionID, $changes);
            if($changes) $allChanges = array_merge($allChanges, $changes);
            $task = $newTask;
        }

        /* Update task and do other operations. */
        if($allChanges)
        {
            foreach($this->config->task->dateFields as $field) if(empty($task->$field)) unset($task->$field);
            $this->dao->update(TABLE_TASK)->data($task, 'team')->where('id')->eq($taskID)->exec();

            if($task->parent > 0) $this->updateParentStatus($task->id);
            if($task->story)  $this->loadModel('story')->setStage($task->story);
            if($task->status != $oldStatus) $this->loadModel('kanban')->updateLane($task->execution, 'task', $taskID);
            if($task->status == 'done' && !dao::isError()) $this->loadModel('score')->create('task', 'finish', $taskID);
        }
        $this->loadModel('program')->refreshProjectStats($task->project);

        return $allChanges;
    }

    /**
     * 检查录入日志的字段必填性及日志记录人要在多人任务的团队中。
     * Check that the required fields of the effort must be filled in and the effort recorder must be in the multi-task team.
     *
     * @param  object      $task
     * @param  array       $workhour
     * @access public
     * @return false|array
     */
    public function checkWorkhour(object $task, array $workhour): false|array
    {
        foreach($workhour as $id => $record)
        {
            if(!$record->work && !$record->consumed && !$record->left)
            {
                unset($workhour[$id]);
                continue;
            }

            $date     = $record->date;
            $consumed = $record->consumed;
            $left     = $record->left;

            /* Check the date of workhour. */
            if(helper::isZeroDate($date)) dao::$errors["date[$id]"] = $this->lang->task->error->dateEmpty;
            if($date > helper::today())   dao::$errors["date[$id]"] = 'ID #' . $id . ' ' . $this->lang->task->error->date;

            /* Check consumed hours. */
            if(!$consumed)
            {
                dao::$errors["consumed[$id]"] = $this->lang->task->error->consumedThisTime;
            }
            elseif(!is_numeric($consumed) && !empty($consumed))
            {
                dao::$errors["consumed[$id]"] = 'ID #' . $id . ' ' . $this->lang->task->error->totalNumber;
            }
            elseif(is_numeric($consumed) && $consumed <= 0)
            {
                dao::$errors["consumed[$id]"] = sprintf($this->lang->error->gt, 'ID #' . $id . ' ' . $this->lang->task->record, '0');
            }
            elseif(!$record->work && $this->config->edition != 'open')
            {
                dao::$errors["work[$id]"] = sprintf($this->lang->error->notempty, $this->lang->task->work);
            }

            /* Check left hours. */
            if($left === '') dao::$errors["left[$id]"] = $this->lang->task->error->left;
            if(!is_numeric($left)) dao::$errors["left[$id]"] = 'ID #' . $id . ' ' . $this->lang->task->error->leftNumber;
            if(is_numeric($left) && $left < 0) dao::$errors["left[$id]"] = sprintf($this->lang->error->gt, 'ID #' . $id . ' ' . $this->lang->task->left, '0');
        }

        if(dao::isError()) return false;

        $inTeam = $this->dao->select('id')->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->andWhere('account')->eq($this->app->user->account)->fetch('id');
        if($task->team && !$inTeam) return false;

        return $workhour;
    }

    /**
     * 设置任务的附件。
     * Set attachments for tasks.
     *
     * @param  int|array $taskIdList
     * @access public
     * @return bool
     */
    public function setTaskFiles(array $taskIdList): bool
    {
        if(empty($taskIdList)) return true;

        $taskID        = array_shift($taskIdList);
        $taskFilePairs = $this->loadModel('file')->saveUpload('task', $taskID);
        if(!empty($taskIdList))
        {
            $taskFiles = $taskFilePairs ? $this->dao->select('*')->from(TABLE_FILE)->where('id')->in(array_keys($taskFilePairs))->fetchAll('id', false) : array();
            foreach($taskIdList as $objectID)
            {
                foreach($taskFiles as $taskFile)
                {
                    $taskFile->objectID = $objectID;
                    unset($taskFile->id);
                    $this->dao->insert(TABLE_FILE)->data($taskFile)->exec();
                }
            }
        }
        return !dao::isError();
    }

    /**
     * 获取报表的查询语句。
     * Get report condition from session.
     *
     * @access public
     * @return string
     */
    public function reportCondition(): string
    {
        if(!empty($_SESSION['taskQueryCondition']))
        {
            if(!$this->session->taskOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', (string)$this->session->taskQueryCondition) . ')';
            return $this->session->taskQueryCondition;
        }
        return '1=1';
    }

    /**
     * 开始一个任务。
     * Start a task.
     *
     * @param  object      $oldTask
     * @param  object      $task
     * @access public
     * @return false|array
     */
    public function start(object $oldTask, object $task): false|array
    {
        /* Process data for multiple tasks. */
        $currentTeam = !empty($oldTask->team) ? $this->getTeamByAccount($oldTask->team) : array();
        if($currentTeam)
        {
            /* Update task team. */
            $team = new stdclass();
            $team->consumed = $task->consumed;
            $team->left     = $task->left;
            $team->status   = empty($team->left) ? 'done' : 'doing';
            $this->dao->update(TABLE_TASKTEAM)->data($team)->where('id')->eq($currentTeam->id)->exec();

            /* Compute hours for multiple task. */
            $task = $this->computeMultipleHours($oldTask, $task);

            /* Set the assigner for the task. */
            $now = helper::now();
            if($team->status == 'done')
            {
                $task->assignedTo   = $this->getAssignedTo4Multi($oldTask->team, $oldTask, 'current');
                $task->assignedDate = $now;
            }

            /* Set the task finisher. */
            $finishedUsers = $this->taskTao->getFinishedUsers($oldTask->id, array_keys($oldTask->members));
            if(count($finishedUsers) == count($oldTask->team))
            {
                $task->status       = 'done';
                $task->finishedBy   = $this->app->user->account;
                $task->finishedDate = $now;
            }
        }

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq($oldTask->id)->exec();

        if(dao::isError()) return false;

        if($task->consumed != $oldTask->consumed || $task->left != $oldTask->left) $this->loadModel('program')->refreshProjectStats($oldTask->project);

        return common::createChanges($oldTask, $task);
    }

    /**
     * 更新一个任务。
     * Update a task.
     *
     * @param  object             $task
     * @param  object             $teamData
     * @access public
     * @return array|string|false
     */
    public function update(object $task, object $teamData = null): array|string|false
    {
        $taskID  = $task->id;
        $oldTask = $this->getByID($taskID);
        $task->project = $oldTask->project;

        if($task->consumed < $oldTask->consumed)
        {
            dao::$errors[] = $this->lang->task->error->consumedSmall;
            return false;
        }

        /* Compute hours and manage team for multi-task. */
        if($teamData && !empty($teamData->team) && count(array_filter($teamData->team)) > 1)
        {
            $teams = $this->manageTaskTeam($task->mode, $task, $teamData);
            if(!empty($teams)) $task = $this->computeMultipleHours($oldTask, $task, array(), false);
        }

        $requiredFields = $this->taskTao->getRequiredFields4Edit($task);
        if(dao::isError()) return false;

        $this->taskTao->doUpdate($task, $oldTask, $requiredFields);
        if(dao::isError()) return false;

        if($task->estimate != $oldTask->estimate || $task->consumed != $oldTask->consumed || $task->left != $oldTask->left) $this->loadModel('program')->refreshProjectStats($oldTask->project);

        $this->afterUpdate($oldTask, $task);

        unset($oldTask->parent, $task->parent);

        /* Logging history when multi-task team members have changed. */
        if(!empty($oldTask->team) && !empty($teamData->team)) list($oldTask, $task) = $this->taskTao->createChangesForTeam($oldTask, $task);

        $this->loadModel('file')->processFileDiffsForObject('task', $oldTask, $task);
        $task    = $this->file->replaceImgURL($task, 'desc');
        $changes = common::createChanges($oldTask, $task);

        /* Record log. */
        if($this->post->comment != '' || !empty($changes))
        {
            $extra = '';
            array_map(function($field) use (&$extra) { if($field['field'] == 'status') $extra = 'statuschanged'; }, $changes);

            $action   = !empty($changes) ? 'Edited' : 'Commented';
            $actionID = $this->loadModel('action')->create('task', $taskID, $action, $this->post->comment, $extra);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 编辑任务的日志。
     * Update effort of task.
     *
     * @param  object  $effort
     * @access public
     * @return array|false
     */
    public function updateEffort(object $effort): array|false
    {
        $oldEffort = $this->getEffortByID($effort->id);

        if(!$this->taskTao->checkEffort($effort)) return false;

        $this->dao->update(TABLE_EFFORT)->data($effort)
            ->autoCheck()
            ->where('id')->eq((int)$effort->id)
            ->exec();

        $this->loadModel('program')->refreshProjectStats($oldEffort->project);

        $task = $this->getById($oldEffort->objectID);
        $data = $this->buildTaskForUpdateEffort($task, $oldEffort, $effort);

        /* Process multi-task by effort. */
        if(!empty($task->team))
        {
            $currentTeam = $this->getTeamByAccount($task->team, $oldEffort->account, array('order' => $oldEffort->order));
            if($currentTeam)
            {
                $newTeamInfo = new stdclass();
                $newTeamInfo->consumed = $currentTeam->consumed + $effort->consumed - $oldEffort->consumed;
                if($currentTeam->status != 'done') $newTeamInfo->left = $data->left;
                if($currentTeam->status != 'done' && $newTeamInfo->consumed > 0 && $data->left == 0) $newTeamInfo->status = 'done';
                $this->dao->update(TABLE_TASKTEAM)->data($newTeamInfo)->where('id')->eq($currentTeam->id)->exec();

                $data = $this->computeMultipleHours($task, $data);
            }
        }

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();

        if(dao::isError()) return false;

        if($task->consumed != $data->consumed || $task->left != $data->left) $this->loadModel('program')->refreshProjectStats($task->project);

        if($task->parent > 0) $this->updateParentStatus($task->id);
        if($task->story)      $this->loadModel('story')->setStage($task->story);

        $oldTask = new stdclass();
        $oldTask->consumed = $task->consumed;
        $oldTask->left     = $task->left;
        $oldTask->status   = $task->status;

        $newTask = new stdclass();
        $newTask->consumed = $data->consumed;
        $newTask->left     = $data->left;
        $newTask->status   = $data->status;

        return common::createChanges($oldTask, $newTask);
    }

    /**
     * 更新串行任务工时日志的排序。
     * Update effort order for linear task team.
     *
     * @param  int    $effortID
     * @param  int    $order
     * @access public
     * @return bool
     */
    public function updateEffortOrder(int $effortID, int $order): bool
    {
        $this->dao->update(TABLE_EFFORT)->set('`order`')->eq($order)->where('id')->eq($effortID)->exec();
        return !dao::isError();
    }

    /**
     * 更新预计开始和结束日期。
     * Update estimate date by gantt.
     *
     * @param  object $postData
     * @access public
     * @return bool
     */
    public function updateEsDateByGantt(object $postData)
    {
        $this->app->loadLang('project');

        $postData->endDate = date('Y-m-d', strtotime('-1 day', strtotime($postData->endDate)));
        $changeTable = $postData->type == 'task' ? TABLE_TASK : TABLE_PROJECT;
        $actionType  = $postData->type == 'task' ? 'task' : 'execution';
        $oldObject   = $this->dao->select('*')->from($changeTable)->where('id')->eq($postData->id)->fetch();

        if($postData->type == 'task')
        {
            $this->taskTao->updateTaskEsDateByGantt($postData);
        }
        elseif($postData->type == 'plan')
        {
            $this->updateExecutionEsDateByGantt($postData);
        }

        if(dao::isError()) return false;

        $newObject = $this->dao->select('*')->from($changeTable)->where('id')->eq($postData->id)->fetch();
        $changes   = common::createChanges($oldObject, $newObject);
        $actionID  = $this->loadModel('action')->create($actionType, $postData->id, 'edited');
        if(!empty($changes)) $this->loadModel('action')->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 通过甘特图更新阶段的预计日期。
     * Update Execution estimate date by gantt.
     *
     * @param  object $postData
     * @access public
     * @return bool
     */
    public function updateExecutionEsDateByGantt(object $postData): bool
    {
        /* Get parent information. */
        $stage      = $this->dao->select('project,parent')->from(TABLE_EXECUTION)->where('id')->eq($postData->id)->fetch();
        $parentID   = $stage->project != $stage->parent ? $stage->parent : 0;
        $parentData = $this->dao->select('begin,end')->from(TABLE_PROJECT)->where('id')->eq($parentID ? $parentID : $stage->project)->fetch();

        $begin = helper::isZeroDate($parentData->begin) ? '' : $parentData->begin;
        $end   = helper::isZeroDate($parentData->end)   ? '' : $parentData->end;

        $this->app->loadLang('programplan');
        $typeLang = $parentID ? $this->lang->programplan->parent : $this->lang->project->common;
        if(helper::diffDate($begin, $postData->startDate) > 0) dao::$errors[] = sprintf($this->lang->task->overEsStartDate, $typeLang, $typeLang);
        if(helper::diffDate($end, $postData->endDate) < 0) dao::$errors[] = sprintf($this->lang->task->overEsEndDate, $typeLang, $typeLang);
        if(dao::isError()) return false;

        $this->dao->update(TABLE_PROJECT)
            ->set('begin')->eq($postData->startDate)
            ->set('end')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($postData->id)
            ->exec();
        return !dao::isError();
    }

    /**
     * 更新看板单元格。
     * Update kanban cell.
     *
     * @param  int    $taskID
     * @param  array  $output
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function updateKanbanCell(int $taskID, array $output, int $executionID): void
    {
        if(!isset($output['toColID'])) $this->loadModel('kanban')->updateLane($executionID, 'task', $taskID);
        if(isset($output['toColID'])) $this->loadModel('kanban')->moveCard($taskID, (int)$output['fromColID'], (int)$output['toColID'], (int)$output['fromLaneID'], (int)$output['toLaneID']);
    }

    /**
     * 更新看板中的任务泳道数据。
     * Update the task lane data in Kanban.
     *
     * @param  int    $executionID
     * @param  array  $taskIdList
     * @param  int    $laneID
     * @param  int    $oldColumnID
     * @access public
     * @return bool
     */
    public function updateKanbanData(int $executionID, array $taskIdList, int $laneID, int $oldColumnID): bool
    {
        if(!$executionID) return false;

        $this->loadModel('kanban');

        /* Get kanban id, lane id and column id. */
        $laneID   = !empty($laneID) ? $laneID : 0;
        $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'wait');
        if(empty($columnID)) $columnID = $oldColumnID;

        /* If both of lane id and column id are not empty, add task to the kanban cell. */
        if($laneID && $columnID)
        {
            foreach($taskIdList as $taskID) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'task', (string)$taskID);
        }

        /* If lane id or column id is empty, update the task type lane of the kanban. */
        if(!$laneID || !$columnID) $this->kanban->updateLane($executionID, 'task');

        return true;
    }

    /**
     * 批量创建任务后的看板数据处理。
     * Kanban data processing after batch create tasks.
     *
     * @param  int    $taskID
     * @param  int    $executionID
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return bool
     */
    public function updateKanbanForBatchCreate(int $taskID, int $executionID, int $laneID, int $columnID): bool
    {
        $this->loadModel('kanban');

        if($this->config->vision == 'lite')
        {
            $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'task', (string)$taskID);
        }
        else
        {
            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'wait');
            if(!empty($laneID) && !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'task', (string)$taskID);
        }
        return !dao::isError();
    }

    /**
     * 拖动甘特图更新任务的顺序。
     * Update order by gantt.
     *
     * @param  object $postData
     * @access public
     * @return bool
     */
    public function updateOrderByGantt(object $postData): bool
    {
        $order = 1;
        foreach($postData->tasks as $task)
        {
            $idList = explode('-', $task);
            $taskID = $idList[1];
            $this->dao->update(TABLE_TASK)->set('`order`')->eq($order)->where('id')->eq($taskID)->exec();
            $order ++;
        }
        return !dao::isError();
    }

    /**
     * 编辑任务时更新父任务的信息。
     * Update parent of a task.
     *
     * @param  object $task
     * @param  bool   $isParentChanged
     * @access public
     * @return void
     */
    public function updateParent(object $task, bool $isParentChanged = false): void
    {
        $oldTask    = $this->fetchByID($task->id);
        $parentTask = empty($task->parent) ? null : $this->fetchByID((int)$task->parent);
        $parentPath = $parentTask ? $parentTask->path : ',';
        $path       = $parentPath . $task->id . ',';

        $this->dao->update(TABLE_TASK)->set('path')->eq($path)->where('id')->eq($task->id)->exec();
        if($parentTask && !$parentTask->isParent) $this->dao->update(TABLE_TASK)->set('isParent')->eq(1)->where('id')->eq((int)$task->parent)->exec();

        /* 更新所有子任务的path. */
        $childIdList = $this->getAllChildId($task->id, false);
        if($childIdList)
        {
            $children = $this->getByIdList($childIdList);
            foreach($children as $child)
            {
                $newChildPath = str_replace($oldTask->path, $path, $child->path);
                $this->dao->update(TABLE_TASK)->set('path')->eq($newChildPath)->where('id')->eq($child->id)->exec();
            }
        }

        $this->updateParentStatus($task->id, $task->parent, !$isParentChanged);
        if($task->parent) $this->computeBeginAndEnd($task->parent);

        $this->taskTao->updateRelation((int)$task->id, (int)$task->parent);
        if($isParentChanged && $task->parent)
        {
            $this->loadModel('action')->create('task', $task->id, 'linkParentTask', '', $task->parent, '', false);
            $actionID = $this->action->create('task', $task->parent, 'linkChildTask', '', $task->id, '', false);

            $newParentTask = $this->fetchByID($task->parent);
            $changes = common::createChanges($parentTask, $newParentTask);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * 更新父任务的状态.
     * Update parent status by taskID of the child task.
     *
     * @param  int    $taskID
     * @param  int    $parentID
     * @param  bool   $createAction
     * @access public
     * @return void
     */
    public function updateParentStatus(int $taskID, int $parentID = 0, bool $createAction = true) :void
    {
        /* Get child task info. */
        $childTask = $this->dao->select('id,assignedTo,parent,path')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(empty($childTask)) return;

        $taskIdList = $childTask->path;
        if($parentID && $childTask->parent != $parentID) $taskIdList = $this->dao->select('id,assignedTo,parent,path')->from(TABLE_TASK)->where('id')->eq($parentID)->fetch('path');

        $taskIdList  = array_unique(array_filter(explode(',', $taskIdList)));
        $parentTasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIdList)->andWhere('id')->ne($taskID)->orderBy('path_desc')->fetchAll('id', false);
        if(empty($parentTasks)) return;

        $this->loadModel('story');
        foreach($parentTasks as $parentID => $parentTask)
        {
            /* Compute parent task hours and status. */
            $this->computeWorkingHours($parentID);
            $status = $this->taskTao->getParentStatusById($parentID);
            if(empty($status))
            {
                $this->dao->update(TABLE_TASK)->set('isParent')->eq(0)->where('id')->eq($parentID)->exec();
                continue;
            }
            /* 只有子任务是进行中和已完成的时候才更新父任务的状态。*/
            /* Update parent task status only child status is 'doing' or 'done'. */
            if(!in_array($status, array('doing', 'done'))) continue;
            if($parentTask->status == $status) continue;

            /* Update task status. */
            $this->taskTao->autoUpdateTaskByStatus($parentTask, $childTask, $status);
            if(dao::isError() || !$createAction) return;

            if($parentTask->story) $this->story->setStage($parentTask->story);

            /* Create action record. */
            $this->taskTao->createAutoUpdateTaskAction($parentTask, 'child');
            if($this->config->edition != 'open' && $parentTask->feedback) $this->loadModel('feedback')->updateStatus('task', $parentTask->feedback, $status, $parentTask->status, $parentID);
        }
    }

    /**
     * 更新子任务的状态.
     * Update children status by taskID.
     *
     * @param  int    $taskID
     * @param  string $oldParentStatus
     * @access public
     * @return void
     */
    public function updateChildrenStatus(int $taskID, string $oldParentStatus = '') :void
    {
        /* Get child task info. */
        $parentTask = $this->dao->select('id,status,isParent,parent,path')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(empty($parentTask)) return;

        $parentStatus = $parentTask->status;
        if(!in_array($parentStatus, array('doing', 'pause', 'cancel', 'closed'))) return;

        $childrenTasks = $this->dao->select('*')->from(TABLE_TASK)->where('path')->like("{$parentTask->path}%")->andWhere('id')->ne($taskID)->fetchAll('id', false);
        if(empty($childrenTasks)) return;

        $autoActions = array();
        if($parentTask->status == 'doing' && $oldParentStatus == 'pause') $autoActions = $this->dao->select('objectID,extra')->from(TABLE_ACTION)->where('objectType')->eq("task")->andWhere('objectID')->in(array_keys($childrenTasks))->andWhere('action')->eq('paused')->orderBy('date')->fetchAll('objectID');

        $this->loadModel('story');
        foreach($childrenTasks as $childID => $childTask)
        {
            if($childTask->status == $parentStatus) continue;
            if($parentStatus == 'pause'  && $childTask->status != 'doing') continue;
            if($parentStatus == 'cancel' && in_array($childTask->status, array('done', 'closed'))) continue;
            if($parentStatus == 'doing'  && $childTask->status != 'pause') continue;
            if(isset($autoActions[$childID]) && $autoActions[$childID]->extra != 'autobyparent') continue;

            $this->taskTao->autoUpdateTaskByStatus($childTask, null, $parentStatus);
            if(dao::isError()) return;

            if($childTask->story) $this->story->setStage($childTask->story);

            /* Create action record. */
            $this->taskTao->createAutoUpdateTaskAction($childTask, 'parent');
            if($this->config->edition != 'open' && $childTask->feedback) $this->loadModel('feedback')->updateStatus('task', $childTask->feedback, $status, $childTask->status, $childID);
        }
    }

    /**
     * 更新团队信息。
     * Update team.
     *
     * @param  object $task
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function updateTeam(object $task, object $postData): array|false
    {
        $taskID  = $task->id;
        $oldTask = $this->getById($taskID);

        /* Check team data. */
        $team = array_filter($postData->team);
        foreach($team as $i => $account)
        {
            if($postData->teamConsumed[$i] == 0 && $postData->teamLeft[$i] == 0)
            {
                dao::$errors[] = $this->lang->task->noticeTaskStart;
                return false;
            }
        }
        if(count($team) <= 1)
        {
            dao::$errors[] = $this->lang->task->error->teamMember;
            return false;
        }

        /* Manage the team and calculate task work information. */
        $teams = $this->manageTaskTeam($oldTask->mode, $task, $postData);
        !empty($teams) ? $task = $this->computeMultipleHours($oldTask, $task) : $task->mode = '';

        /* Update parent task status. */
        if($oldTask->parent > 0) $this->updateParentStatus($taskID);

        $this->dao->update(TABLE_TASK)
            ->data($task)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();

        if(dao::isError()) return false;

        if($task->estimate != $oldTask->estimate || $task->consumed != $oldTask->consumed || $task->left != $oldTask->left) $this->loadModel('program')->refreshProjectStats($oldTask->project);

        return common::createChanges($oldTask, $task);
    }

    /**
     * 通过任务ID获取任务团队信息。
     * Get the task team information through the task ID.
     *
     * @param  int       $taskID
     * @param  string    $orderBy
     * @access protected
     * @return object[]
     */
    public function getTeamByTask(int $taskID, string $orderBy = 'order_asc'): array
    {
        return $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->orderBy($orderBy)->fetchAll('id', false);
    }

    /**
     * 更新任务关联的代码提交记录。
     * Update the commit logs linked with the tasks.
     *
     * @param  int       $taskID
     * @param  int       $repoID
     * @param  array     $revisions
     * @access public
     * @return bool
     */
    public function updateLinkedCommits(int $taskID, int $repoID, array $revisions): bool
    {
        if(!$taskID || !$repoID || empty($revisions)) return true;
        $task = $this->dao->select('project')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(!$task) return true;
        foreach($revisions as $revision)
        {
            $data = new stdclass();
            $data->product  = 0;
            $data->project  = $task->project;
            $data->AType    = 'task';
            $data->AID      = $taskID;
            $data->BType    = 'commit';
            $data->BID      = $revision;
            $data->relation = 'completedin';
            $data->extra    = $repoID;
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'commit';
            $data->AID      = $revision;
            $data->BType    = 'task';
            $data->BID      = $taskID;
            $data->relation = 'completedfrom';
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }
        return !dao::isError();
    }

    /**
     * 获取Task关联的提交数据。
     * Get the commit data for the associated bugs
     * @param  int       $repoID
     * @param  array     $revisions
     * @access public
     * @return bool
     */
    public function getLinkedCommits(int $repoID, array $revisions): array
    {
        return $this->dao->select('t1.revision,t3.id AS id,t3.name AS name')
            ->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on("t2.relation='completedin' AND t2.BType='commit' AND t2.BID=t1.id")
            ->leftJoin(TABLE_TASK)->alias('t3')->on("t2.AType='task' AND t2.AID=t3.id")
            ->where('t1.revision')->in($revisions)
            ->andWhere('t1.repo')->eq($repoID)
            ->andWhere('t3.id')->ne('')
            ->fetchGroup('revision', 'id');
    }

    /**
     * 获取执行未关闭的任务
     * Get unclosed tasks by execution
     *
     * @param  string|int   $execution
     * @access public
     * @return array|false
     */
    public function getUnclosedTasksByExecution(array|int $execution): array|false
    {
        if(is_array($execution)) return $this->dao->select('id,execution')->from(TABLE_TASK)->where('execution')->in($execution)->andWhere('status')->ne('closed')->andWhere('deleted')->eq('0')->fetchGroup('execution');
        return $this->dao->select('id,name')->from(TABLE_TASK)->where('execution')->eq($execution)->andWhere('status')->ne('closed')->andWhere('deleted')->eq('0')->fetchPairs();
    }

    /**
     * 同步父任务的需求到子任务
     * Sync parent story to children
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function syncStoryToChildren(object $task): bool
    {
        $nonStoryTasks = $this->dao->select('id,story')->from(TABLE_TASK)
            ->where('path')->like("%,$task->id,%")
            ->andWhere('deleted')->eq('0')
            ->andWhere('story')->eq(0)
            ->andWhere('id')->ne($task->id)
            ->fetchPairs();

        $taskStory = $this->loadModel('story')->fetchByID($task->story);
        $this->loadModel('action');
        foreach($nonStoryTasks as $id => $story)
        {
            $this->dao->update(TABLE_TASK)->set('story')->eq($task->story)->set('storyVersion')->eq($taskStory->version)->where('id')->eq($id)->exec();

            $changes  = array(array('field' => 'story', 'old' => $story, 'new' => $task->story, 'diff' => ''));
            $actionID = $this->action->create('task', $id, 'syncStoryByParentTask');
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 获取子任务
     * Get child tasks
     *
     * @param  array $taskIdList
     * @access public
     * @return array|false
     */
    public function getChildTasksByList(array $taskIdList): array|false
    {
        $childTasks         = $this->dao->select('id,parent')->from(TABLE_TASK)->where('parent')->in($taskIdList)->andWhere('deleted')->eq('0')->fetchGroup('parent', 'id');
        $nonStoryChildTasks = $this->dao->select('id,parent')->from(TABLE_TASK)->where('parent')->in($taskIdList)->andWhere('story')->eq('0')->andWhere('deleted')->eq('0')->fetchGroup('parent', 'id');
        return array($childTasks, $nonStoryChildTasks);
    }

    /**
     * 确认需求变更。
     * Confirm story change.
     *
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function confirmStoryChange(int $taskID): bool
    {
        $task = $this->getByID($taskID);
        $this->dao->update(TABLE_TASK)->set('storyVersion')->eq($task->latestStoryVersion)->where('id')->eq($taskID)->exec();
        $this->dao->update(TABLE_TASKTEAM)->set('storyVersion')->eq($task->latestStoryVersion)->where('task')->eq($taskID)->andWhere('account')->eq($this->app->user->account)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('task', $taskID, 'confirmed', '', $task->latestStoryVersion);
        return !dao::isError();
    }

    /**
     * 获取多人任务当前登录用户的需求版本。
     * Get team story version by current login user.
     *
     * @param  int|array|string $taskIdList
     * @access public
     * @return array
     */
    public function getTeamStoryVersion(int|array|string $taskIdList): array
    {
        return $this->dao->select('task,storyVersion')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->andWhere('account')->eq($this->app->user->account)->fetchPairs();
    }

    /**
     * 处理需求确认变更按钮。
     * Process confirm story change button.
     *
     * @param  object $task
     * @access public
     * @return object
     */
    public function processConfirmStoryChange(object $task): object
    {
        if(empty($task->actions[0]['name']) || $task->actions[0]['name'] != 'confirmStoryChange') return $task;
        if(empty($task->team) && (empty($task->assignedTo) || $task->assignedTo == $this->app->user->account)) return $task;

        if(!empty($task->team))
        {
            $taskMembers = !empty($task->team) ? array_column($task->team, 'account') : array();
            $disabled    = !in_array($this->app->user->account, $taskMembers);
            $task->actions[0]['disabled'] = $disabled;
            if($disabled) $task->actions[0]['hint'] = $this->lang->task->disabledHint->memberConfirmStoryChange;
        }
        else
        {
            $task->actions[0]['disabled'] = true;
            $task->actions[0]['hint']     = $this->lang->task->disabledHint->assignedConfirmStoryChange;
        }

        return $task;
    }
}
