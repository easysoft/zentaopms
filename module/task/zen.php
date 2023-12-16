<?php
declare(strict_types=1);
/**
 * The zen file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
class taskZen extends task
{

    /**
     * 执行数组。
     * Execution's [id] => 'name' pairs.
     *
     * @var    array
     * @access private
     */
    private $executionPairs = array();

    /**
     * 展示创建任务的相关变量。
     * Show the variables associated with the creation task.
     *
     * @param  object    $execution
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  int       $todoID
     * @param  int       $bugID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function assignCreateVars(object $execution, int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID, array $output): void
    {
        /* Get information about the task. */
        $this->view->task = $this->setTaskByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID);

        /* Get module information. */
        $executionID = $execution->id;
        $modulePairs = $this->tree->getTaskOptionMenu($executionID);

        /* Display relevant variables. */
        $this->assignExecutionForCreate($execution);
        $this->assignStoryForCreate($executionID, $moduleID);
        if($execution->type == 'kanban') $this->assignKanbanForCreate($executionID, $output);

        /* Set Custom fields. */
        foreach(explode(',', $this->config->task->list->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $this->view->title         = $execution->name . $this->lang->colon . $this->lang->task->create;
        $this->view->customFields  = $customFields;
        $this->view->modulePairs   = $modulePairs;
        $this->view->showFields    = $this->config->task->custom->createFields;
        $this->view->gobackLink    = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID={$executionID}") : '';
        $this->view->execution     = $execution;
        $this->view->storyID       = $storyID;
        $this->view->blockID       = helper::isAjaxRequest('modal') ? $this->loadModel('block')->getSpecifiedBlockID('my', 'assigntome', 'assigntome') : 0;
        $this->view->hideStory     = $this->task->isNoStoryExecution($execution);
        $this->view->from          = $storyID || $todoID || $bugID  ? 'other' : 'task';
        $this->view->taskID        = $taskID;

        $this->display();
    }

    /**
     * 设置创建页面展示的执行相关数据。
     * Set the execution-related data for the create page display.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignExecutionForCreate(object $execution): void
    {
        $projectID     = $execution ? $execution->project : 0;
        $lifetimeList  = array();
        $attributeList = array();
        $executions    = $this->executionPairs;
        if($projectID)
        {
            $executions    = $this->execution->getByProject($projectID, 'all', 0, true);
            $executionList = $this->execution->getByIdList(array_keys($executions));
            foreach($executionList as $executionItem)
            {
                if(!common::canModify('execution', $executionItem)) unset($executions[$executionItem->id]);
            }
        }

        $executionList = $this->execution->getByIdList(array_keys($executions));
        foreach($executionList as $executionItem)
        {
            $executionKey = $executionItem->id;
            $lifetimeList[$executionKey]  = $executionItem->lifetime;
            $attributeList[$executionKey] = $executionItem->attribute;
        }

        $this->view->projectID     = $projectID;
        $this->view->executions    = $executions;
        $this->view->lifetimeList  = $lifetimeList;
        $this->view->attributeList = $attributeList;
        $this->view->productID     = $this->loadModel('product')->getProductIDByProject($projectID);
        $this->view->features      = $this->execution->getExecutionFeatures($execution);
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->members       = $this->user->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
    }

    /**
     * 设置创建页面展示的需求相关数据。
     * Set the stories related data for the create page display.
     *
     * @param  int       $executionID
     * @param  int       $moduleID
     * @access protected
     * @return void
     */
    protected function assignStoryForCreate(int $executionID, int $moduleID): void
    {
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', $moduleID, 'full', 'active');
        $testStoryIdList = $this->loadModel('story')->getTestStories(array_keys($stories), $executionID);
        $testStories     = array();
        foreach($stories as $testStoryID => $storyTitle)
        {
            if(empty($testStoryID) || isset($testStoryIdList[$testStoryID])) continue;
            $testStories[$testStoryID] = $storyTitle;
        }
        $this->view->testStories     = $testStories;
        $this->view->testStoryIdList = $testStoryIdList;
        $this->view->stories         = $stories;
    }

    /**
     * 设置创建页面展示的看板相关数据。
     * Set Kanban related data for create page display.
     *
     * @param  int       $executionID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function assignKanbanForCreate(int $executionID, array $output): void
    {
        $this->loadModel('kanban');

        $regionID    = isset($output['regionID']) ? (int)$output['regionID'] : 0;
        $laneID      = isset($output['laneID']) ? (int)$output['laneID'] : 0;
        $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
        $regionID    = $regionID ? $regionID : key($regionPairs);
        $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'task');
        $laneID      = $laneID ? $laneID : key($lanePairs);

        $this->view->regionID    = $regionID;
        $this->view->laneID      = $laneID;
        $this->view->regionPairs = $regionPairs;
        $this->view->lanePairs   = $lanePairs;
    }

    /**
     * 展示批量编辑任务的相关变量。
     * Show the variables associated with the batch edit task.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignBatchEditVars(int $executionID): void
    {
        $this->loadModel('tree');

        /* Set menu and related variables. */
        if($executionID)
        {
            $this->execution->setMenu($executionID);
            $execution = $this->execution->getById($executionID);

            $this->view->title     = $execution->name . $this->lang->colon . $this->lang->task->batchEdit;
            $this->view->execution = $execution;
            $this->view->modules   = $this->tree->getTaskOptionMenu($executionID, 0, !empty($this->config->task->allModule) ? 'allModule' : '');
        }
        else
        {
            $this->loadModel('my');
            $this->lang->my->menu->work['subModule'] = 'task';

            $this->view->title   = $this->lang->task->batchEdit;
            $this->view->users   = $this->loadModel('user')->getPairs('noletter');
            $this->view->modules = array();
        }

        /* Check if the request data size exceeds the PHP limit. */
        $tasks           = $this->task->getByIdList($this->post->taskIdList);
        $countInputVars  = count($tasks) * (count(explode(',', $this->config->task->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        foreach(explode(',', $this->config->task->customBatchEditFields) as $field)
        {
            if(!empty($execution) && $execution->type == 'stage' && strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchEditFields;

        $executionTeams    = array();
        $executionIdList   = array_unique(array_column($tasks, 'execution'));
        $executionTeamList = $this->execution->getMembersByIdList($executionIdList);
        foreach($executionIdList as $id) $executionTeams[$id] = array_column($executionTeamList[$id], 'account');

        $moduleGroup = array();
        if(!$executionID)
        {
            foreach($tasks as $task)
            {
                if(isset($moduleGroup[$task->execution])) continue;
                $executionInfo    = $this->execution->getByID($task->execution);
                $executionModules = $this->tree->getTaskOptionMenu($task->execution, 0, 'allModule');
                foreach($executionModules as $moduleID => $moduleName) $moduleGroup[$task->execution][] = array('text' => $executionInfo->name. $moduleName, 'value' => $moduleID);
            }
        }

        /* Assign. */
        $this->view->executionID    = $executionID;
        $this->view->tasks          = $tasks;
        $this->view->teams          = $this->task->getTeamMembersByIdList($this->post->taskIdList);
        $this->view->executionTeams = $executionTeams;
        $this->view->users          = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->moduleGroup    = $moduleGroup;

        $this->display();
    }

    /**
     * 构建任务编辑表单。
     * Build task edit form.
     *
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function buildEditForm(int $taskID): void
    {
        $task  = $this->view->task;

        /* Get the task parent id,name pairs. */
        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, strVal($task->parent));
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        /* Prepare to assign to relevant parameters. */
        if(!isset($this->view->members[$task->assignedTo])) $this->view->members[$task->assignedTo] = $task->assignedTo;
        if(isset($this->view->members['closed']) || $task->status == 'closed') $this->view->members['closed'] = 'Closed';

        /* Get the executions of the task. */
        $executions = !empty($task->project) ? $this->execution->getByProject($task->project, 'all', 0, true) : array();

        /* Get task members. */
        $taskMembers = array();
        if(!empty($task->team))
        {
            foreach($task->members as $teamAccount)
            {
                if(!isset($this->view->members[$teamAccount])) continue;
                $taskMembers[$teamAccount] = $this->view->members[$teamAccount];
            }
        }
        else
        {
            $taskMembers = $this->view->members;
        }

        $this->view->title         = $this->lang->task->edit . 'TASK' . $this->lang->colon . $this->view->task->name;
        $this->view->stories       = $this->story->getExecutionStoryPairs($this->view->execution->id, 0, 'all', '', 'full', 'active');
        $this->view->tasks         = $tasks;
        $this->view->taskMembers   = $taskMembers;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed', "{$task->openedBy},{$task->canceledBy},{$task->closedBy}");
        $this->view->showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->view->modules       = $this->tree->getTaskOptionMenu($task->execution, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions    = $executions;
        $this->view->contactLists  = $this->loadModel('user')->getContactLists();
        $this->display();
    }

    /**
     * 构建指派给表单。
     * Build from for assignTo page.
     *
     * @param  int       $executionID
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function buildAssignToForm(int $executionID, int $taskID): void
    {
        $task    = $this->task->getByID($taskID);
        $members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');

        /* Compute next assignedTo. */
        if(!empty($task->team) && in_array($task->status, $this->config->task->unfinishedStatus))
        {
            $task->nextUser = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) || $task->status == 'closed') $members['closed'] = 'Closed';

        $this->view->title   = $this->view->execution->name . $this->lang->colon . $this->lang->task->assign;
        $this->view->task    = $task;
        $this->view->members = $members;
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * 构建批量创建任务的表单数据。
     * Build batch create form.
     *
     * @param  object    $execution
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function buildBatchCreateForm(object $execution, int $storyID, int $moduleID, int $taskID, array $output): void
    {
        /* 获取区域和泳道下拉数据，并设置区域和泳道的默认值。 */
        /* Get region and lane dropdown data and set default values for regions and lanes. */
        if($execution->type == 'kanban') $this->assignKanbanForCreate($execution->id, $output);

        if($taskID)
        {
            $task = $this->dao->findById($taskID)->from(TABLE_TASK)->fetch();
            $this->view->parentTitle  = $task->name;
            $this->view->parentPri    = $task->pri;
        }

        /* 获取模块和需求下拉数据。 Get module and story dropdown data. */
        $showAllModule = !empty($this->config->execution->task->allModule) ? 'allModule' : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, $showAllModule);
        $story         = $this->story->getByID($storyID);
        $stories       = $this->story->getExecutionStoryPairs($execution->id, 0, 'all', $story ? $story->module : 0, 'short', 'active');

        list($customFields, $checkedFields) = $this->getCustomFields($execution, 'batchCreate');

        $this->view->title         = $this->lang->task->batchCreate;
        $this->view->execution     = $execution;
        $this->view->modules       = $modules;
        $this->view->parent        = $taskID;
        $this->view->storyID       = $storyID;
        $this->view->story         = $story;
        $this->view->moduleID      = $story ? $story->module : $moduleID;
        $this->view->stories       = array_filter($stories);
        $this->view->storyTasks    = $this->task->getStoryTaskCounts(array_keys($stories), $execution->id);
        $this->view->members       = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        $this->view->taskConsumed  = isset($task) ? $task->consumed : 0;
        $this->view->customFields  = $customFields;
        $this->view->checkedFields = $checkedFields;
        $this->view->hideStory     = $this->task->isNoStoryExecution($execution);

        $this->display();
    }

    /**
     * 构造任务记录日志的表单数据。
     * Build record workhour form.
     *
     * @param  int       $taskID
     * @param  string    $from
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function buildRecordForm(int $taskID, string $from, string $orderBy): void
    {
        $task = $this->task->getById($taskID);
        if(!empty($task->team) and $task->mode == 'linear')
        {
            if(empty($orderBy))
            {
                $orderBy = 'id_desc';
            }
            else
            {
                /* The id sort with order or date style. */
                $orderBy .= preg_replace('/(order_|date_)/', ',id_', $orderBy);
            }
        }

        if(!$orderBy) $orderBy = 'id_desc';

        /* Set the fold state of the current task. */
        $taskEffortFold = 0;
        $currentAccount = $this->app->user->account;
        if($task->assignedTo == $currentAccount) $taskEffortFold = 1;
        if(!empty($task->team))
        {
            $teamMember = array_column($task->team, 'account');
            if(in_array($currentAccount, $teamMember)) $taskEffortFold = 1;
        }

        $this->view->title          = $this->lang->task->record;
        $this->view->task           = $task;
        $this->view->from           = $from;
        $this->view->orderBy        = $orderBy;
        $this->view->efforts        = $this->task->getTaskEfforts($task->id, '', 0, $orderBy);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->taskEffortFold = $taskEffortFold;

        $this->display();
    }

    /**
     * 构造待更新的任务数据。
     * Build the task data to be update.
     *
     * @param  object       $task
     * @access protected
     * @return object|false
     */
    protected function buildTaskForEdit(object $task): object|false
    {
        $oldTask = $this->task->getByID($task->id);

        /* Check if the fields is valid. */
        if($task->estimate < 0 or $task->left < 0 or $task->consumed < 0) dao::$errors[] = $this->lang->task->error->recordMinus;
        if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($oldTask->execution, $task->estStarted, $task->deadline);
        if(!empty($task->lastEditedDate) && $oldTask->lastEditedDate != $task->lastEditedDate) dao::$errors[] = $this->lang->error->editedByOther;
        if(dao::isError()) return false;

        $now  = helper::now();
        $task = form::data($this->config->task->form->edit)->add('id', $task->id)
            ->setDefault('deleteFiles', array())
            ->add('lastEditedDate', $now)
            ->setIF(!$task->assignedTo && !empty($oldTask->team) && !empty($this->post->team), 'assignedTo', $this->task->getAssignedTo4Multi($this->post->team, $oldTask))
            ->setIF($task->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)
            ->setIF($task->mode == 'single', 'mode', '')
            ->setIF(!$oldTask->mode && !$task->assignedTo && !empty($this->post->team), 'assignedTo', $this->post->team[0])
            ->setIF($task->story !== false && $task->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion((int)$task->story))
            ->setIF($task->status == 'wait' && $task->left == $oldTask->left && $task->consumed == 0 && $task->estimate, 'left', $task->estimate)
            ->setIF($task->status == 'done', 'left', 0)
            ->setIF($task->status == 'done'   && empty($task->finishedBy),   'finishedBy',   $this->app->user->account)
            ->setIF($task->status == 'done'   && empty($task->finishedDate), 'finishedDate', $now)
            ->setIF($task->status == 'cancel' && empty($task->canceledBy),   'canceledBy',   $this->app->user->account)
            ->setIF($task->status == 'cancel' && empty($task->canceledDate), 'canceledDate', $now)
            ->setIF($task->status == 'cancel', 'assignedTo',   $oldTask->openedBy)
            ->setIF($task->status == 'cancel', 'assignedDate', $now)
            ->setIF($task->status == 'closed' && empty($task->closedBy),     'closedBy',     $this->app->user->account)
            ->setIF($task->status == 'closed' && empty($task->closedDate),   'closedDate',   $now)
            ->setIF($task->consumed > 0 && $task->left > 0 && $task->status == 'wait', 'status', 'doing')
            ->setIF($oldTask->parent >= 0 && empty($task->parent), 'parent', 0)
            ->setIF($oldTask->parent < 0, 'estimate', $oldTask->estimate)
            ->setIF($oldTask->parent < 0, 'left', $oldTask->left)
            ->setIF($oldTask->name != $task->name || $oldTask->estStarted != $task->estStarted || $oldTask->deadline != $task->deadline, 'version', $oldTask->version + 1)
            ->stripTags($this->config->task->editor->edit['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->get();

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->edit['id'], $this->post->uid);
    }

    /**
     * 构造待批量指派的任务数据。
     * Build the task data to batch assign to.
     *
     * @param  string[]  $taskIdList
     * @param  string    $assignedTo
     * @access protected
     * @return object[]
     */
    protected function buildTasksForBatchAssignTo(array $taskIdList, string $assignedTo): array
    {
        $taskIdList     = array_unique($taskIdList);
        $multipleTasks = $this->dao->select('task, account')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'account');
        $tasks          = $this->task->getByIdList($taskIdList);

        /* Filter tasks. */
        foreach($tasks as $taskID => $task)
        {
            if(isset($multipleTasks[$taskID]) && $task->assignedTo != $this->app->user->account && $task->mode == 'linear') unset($tasks[$taskID]);
            if(isset($multipleTasks[$taskID]) && !isset($multipleTasks[$taskID][$this->post->assignedTo])) unset($tasks[$taskID]);
            if($task->status == 'closed') unset($tasks[$taskID]);
        }

        /* Prepare data. */
        $now          = helper::now();
        $prepareTasks = array();
        foreach($tasks as $task)
        {
            $prepareTask = new stdclass();
            $prepareTask->id             = $task->id;
            $prepareTask->lastEditedBy   = $this->app->user->account;
            $prepareTask->lastEditedDate = $now;
            $prepareTask->assignedDate   = $now;
            $prepareTask->assignedTo     = $assignedTo;

            $prepareTasks[] = clone $prepareTask;
        }

        return $prepareTasks;
    }

    /**
     * 处理批量创建任务的请求数据。
     * Process the request data for the batch create tasks.
     *
     * @param  object         $execution
     * @param  int            $taskID
     * @param  array          $output
     * @access protected
     * @return false|object[]
     */
    protected function buildTasksForBatchCreate(object $execution, int $taskID, array $output): false|array
    {
        $this->loadModel('story');

        $tasks = form::batchData()->get();
        foreach($tasks as $task)
        {
            $task->project      = $execution->project;
            $task->execution    = $execution->id;
            $task->left         = $task->estimate;
            $task->parent       = $taskID;
            $task->lane         = empty($task->lane) && !empty($output['laneID']) ? (int)$output['laneID'] : $task->lane;
            $task->storyVersion = $task->story ? $this->story->getVersion($task->story) : 1;
        }

        /* Remove data with the same task name. */
        $tasks = $this->removeDuplicateForBatchCreate($execution->id, $tasks);
        if(dao::isError()) return false;

        /* Check if the input post data meets the requirements. */
        $this->checkBatchCreateTask($execution->id, $tasks);
        if(dao::isError()) return false;

        return $tasks;
    }

    /**
     * 构造待创建的任务数据。
     * Build the task data to create.
     *
     * @param  int       $executionID
     * @access protected
     * @return object
     */
    protected function buildTaskForCreate(int $executionID): object
    {
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        $team      = $this->post->team ? array_filter($this->post->team) : array();
        $task      = form::data()->setDefault('execution', $executionID)
            ->setDefault('project', $execution->project)
            ->setDefault('left', 0)
            ->setIF($this->post->estimate, 'left', $this->post->estimate)
            ->setIF($this->post->mode, 'mode', $this->post->mode)
            ->setIF($this->post->story, 'storyVersion', $this->post->story ? $this->loadModel('story')->getVersion((int)$this->post->story) : 1)
            ->setIF(!$this->post->multiple || count($team) < 1, 'mode', '')
            ->setIF($this->task->isNoStoryExecution($execution), 'story', 0)
            ->setIF($this->post->assignedTo, 'assignedDate', helper::now())
            ->setIF(!$this->post->estStarted, 'estStarted', null)
            ->setIF(!$this->post->deadline, 'deadline', null)
            ->get();

        /* Processing image link. */
        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $this->post->uid);

        /* Check if the input post data meets the requirements. */
        $this->checkCreateTask($task);
        return $task;
    }

    /**
     * 构造批量编辑的任务数据。
     * Build the tasks data to batch edit.
     *
     * @access protected
     * @return array
     */
    protected function buildTasksForBatchEdit(): false|array
    {
        $taskData = form::batchData()->get();
        $oldTasks = $taskData ? $this->task->getByIdList(array_keys($taskData)) : array();
        $now      = helper::now();
        foreach($taskData as $taskID => $task)
        {
            $oldTask = $oldTasks[$taskID];

            $task->parent       = $oldTask->parent;
            $task->assignedTo   = $task->status == 'closed' ? 'closed' : $task->assignedTo;
            $task->assignedDate = !empty($task->assignedTo) && $oldTask->assignedTo != $task->assignedTo ? $now : $oldTask->assignedDate;
            $task->version      = $oldTask->name != $task->name || $oldTask->estStarted != $task->estStarted || $oldTask->deadline != $task->deadline ?  $oldTask->version + 1 : $oldTask->version;
            $task->consumed     = $task->consumed < 0 ? $task->consumed  : $task->consumed + $oldTask->consumed;

            if(empty($task->closedReason) && $task->status == 'closed')
            {
                if($oldTask->status == 'done')   $task->closedReason = 'done';
                if($oldTask->status == 'cancel') $task->closedReason = 'cancel';
            }
            $task = $this->processTaskByStatus($task, $oldTask);
            if($task->assignedTo) $task->assignedDate = $now;
        }

        $this->checkBatchEditTask($taskData, $oldTasks);
        if(dao::isError()) return false;

        return $taskData;
    }

    /**
     * 构造激活的任务数据。
     * Build the task data to activate.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForActivate(int $taskID): object
    {
        $task = form::data()->add('id', $taskID)->get();
        unset($task->comment);

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->activate['id'], $this->post->uid);
    }

    /**
     * 处理开始任务的请求数据。
     * Process the request data for the start task.
     *
     * @param  object    $oldTask
     * @access protected
     * @return false|object
     */
    protected function buildTaskForStart(object $oldTask): false|object
    {
        $now  = helper::now();
        $task = form::data($this->config->task->form->start)->add('id', $oldTask->id)
            ->setIF($oldTask->assignedTo != $this->app->user->account, 'assignedDate', $now)
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->start['id'], $this->post->uid);
        if($task->left == 0 && empty($oldTask->team))
        {
            $task->status       = 'done';
            $task->finishedBy   = $this->app->user->account;
            $task->finishedDate = $now;
            $task->assignedTo   = $oldTask->openedBy;
        }

        $currentTeam = !empty($oldTask->team) ? $this->task->getTeamByAccount($oldTask->team) : array();

        /* Check if the input post data meets the requirements. */
        $result = $this->checkStart($oldTask, $task, $currentTeam);
        if(!$result) return false;

        return $task;
    }

    /**
     * 构造取消的任务数据。
     * Build the task data to cancel.
     *
     * @param  object    $oldTask
     * @access protected
     * @return object
     */
    protected function buildTaskForCancel(object $oldTask): object
    {
        $now  = helper::now();
        $task = form::data($this->config->task->form->cancel)
            ->add('id', $oldTask->id)
            ->setDefault('status', 'cancel')
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('finishedBy', '')
            ->setDefault('canceledBy, lastEditedBy', $this->app->user->account)
            ->setDefault('canceledDate, lastEditedDate', $now)
            ->setIF(empty($oldTask->finishedDate), 'finishedDate', null)
            ->remove('comment')
            ->get();

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->cancel['id'], $this->post->uid);
    }

    /**
     * 构造待创建的测试类型的子任务数据。
     * Build subtask data for the test type to create.
     *
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function buildTestTasksForCreate(int $executionID): array
    {
        /* Set data for the type of test task that has linked stories. */
        $postData = form::data($this->config->task->form->testTask->create)->get();
        if(empty($postData->selectTestStory)) return array();

        $testTasks = array();
        foreach($postData->testStory as $key => $storyID)
        {
            if(empty($storyID)) continue;

            /* Set task data. */
            $task = new stdclass();
            $task->execution  = $executionID;
            $task->story      = $storyID;
            $task->pri        = !empty($postData->testPri[$key]) ? $postData->testPri[$key] : ($_POST['pri'] ?? 3);
            $task->estStarted = !empty($postData->testEstStarted[$key]) ? $postData->testEstStarted[$key] : null;
            $task->deadline   = !empty($postData->testDeadline[$key])   ? $postData->testDeadline[$key]   : null;
            $task->assignedTo = !empty($postData->testAssignedTo[$key]) ? $postData->testAssignedTo[$key] : '';
            $task->estimate   = (float)$postData->testEstimate[$key];
            $task->left       = (float)$postData->testEstimate[$key];
            $task->type       = 'test'; /* Setting the task type to test to prevent duplicate tasks from being created. */
            $task->vision     = $this->config->vision;

            $testTasks[$storyID] = $task;
        }

        $this->checkCreateTestTasks($testTasks);
        return $testTasks;
    }

    /**
     * 处理开始任务的日志数据。
     * Process the effort data for the start task.
     *
     * @param  object    $oldTask
     * @param  object    $task
     * @access protected
     * @return object
     */
    protected function buildEffortForStart(object $oldTask, object $task): object
    {
        $currentTeam = !empty($oldTask->team) ? $this->task->getTeamByAccount($oldTask->team) : array();

        $effort = new stdclass();
        $effort->date     = helper::today();
        $effort->task     = $task->id;
        $effort->consumed = zget($task, 'consumed', 0);
        $effort->left     = zget($task, 'left', 0);
        $effort->work     = zget($task, 'work', '');
        $effort->account  = $this->app->user->account;
        $effort->consumed = !empty($oldTask->team) && $currentTeam ? $effort->consumed - $currentTeam->consumed : $effort->consumed - $oldTask->consumed;

        return $effort;
    }

    /**
     * 构建并检查完成任务所需的数据。
     * Build and check the request data for the finish task.
     *
     * @param  object    $oldTask
     * @access protected
     * @return object
     */
    protected function buildTaskForFinish(object $oldTask): object
    {
        $now = helper::now();
        $task = form::data($this->config->task->form->finish)
            ->setIF(!$this->post->realStarted && helper::isZeroDate($oldTask->realStarted), 'realStarted', $now)
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->get();

        if(strpos(",{$this->config->task->finish->requiredFields},", ',comment,') !== false && empty($_POST['comment'])) dao::$errors['comment'] = sprintf($this->lang->error->notempty, $this->lang->comment);
        if(!$this->post->currentConsumed && $oldTask->consumed == '0') dao::$errors['currentConsumed'][] = $this->lang->task->error->consumedEmpty;
        if($task->realStarted > $task->finishedDate) dao::$errors['finishedDate'][] = $this->lang->task->error->finishedDateSmall;

        $task->consumed = $oldTask->consumed + (float)$this->post->currentConsumed;
        return $task;
    }

    /**
     * 处理完成任务的日志数据。
     * Process the effort data for the finish task.
     *
     * @param  object    $oldTask
     * @param  object    $task
     * @access protected
     * @return object
     */
    protected function buildEffortForFinish(object $oldTask, object $task): object
    {
        /* Record consumed and left. */
        if(empty($oldTask->team))
        {
            $consumed = $task->consumed - $oldTask->consumed;
        }
        else
        {
            $currentTeam = $this->task->getTeamByAccount($oldTask->team);
            $consumed = $currentTeam ? $task->consumed - $currentTeam->consumed : $task->consumed;
        }

        if($consumed < 0) dao::$errors[] = $this->lang->task->error->consumedSmall;

        $effort = new stdclass();
        $effort->date     = helper::isZeroDate($task->finishedDate) ? helper::today() : substr($task->finishedDate, 0, 10);
        $effort->task     = $oldTask->id;
        $effort->left     = 0;
        $effort->work     = zget($task, 'work', '');
        $effort->account  = $this->app->user->account;
        $effort->consumed = $consumed;

        if($this->post->comment) $effort->work = $this->post->comment;

        return $effort;
    }

    /**
     * 处理关闭任务的请求数据。
     * Process the request data for the close task.
     *
     * @param  object    $oldTask
     * @access protected
     * @return object
     */
    protected function buildTaskForClose(object $oldTask): object
    {
        $task = form::data($this->config->task->form->close)->add('id', $oldTask->id)
            ->setIF($oldTask->status == 'done',   'closedReason', 'done')
            ->setIF($oldTask->status == 'cancel', 'closedReason', 'cancel')
            ->get();

        return  $this->loadModel('file')->processImgURL($task, $this->config->task->editor->start['id'], $this->post->uid);
    }

    /**
     * 构造图表数据。
     * Build chart data.
     *
     * @param  string    $chartType
     * @access protected
     * @return void
     */
    protected function buildChartData(string $chartType): array
    {
        $this->loadModel('report');

        $chartList = array();
        $dataList  = array();
        foreach($this->post->charts as $chart)
        {
            $chartFunc   = 'getDataOf' . $chart;
            $chartData   = $this->task->$chartFunc();
            $chartOption = $this->lang->task->report->$chart;
            if(!empty($chartType) and $chartType != 'default') $chartOption->type = $chartType;

            $this->task->mergeChartOption($chart);

            $chartList[$chart] = $chartOption;
            $dataList[$chart]  = $this->report->computePercent($chartData);
        }

        $this->view->datas = $dataList;

        return $chartList;
    }

    /**
     * 任务模块的一些常用操作。
     * Common actions of task module.
     *
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function commonAction(int $taskID): void
    {
        $this->view->task      = $this->task->getByID($taskID);
        $this->view->execution = $this->execution->getByID($this->view->task->execution);
        $this->view->members   = $this->loadModel('user')->getTeamMemberPairs($this->view->execution->id, 'execution','nodeleted');
        $this->view->actions   = $this->loadModel('action')->getList('task', $taskID);

        /* Set menu. */
        $this->execution->setMenu($this->view->execution->id);
    }

    /**
     * 检查传入的创建数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function checkCreateTask(object $task): bool
    {
        /* Check if the estimate is positive. */
        if($task->estimate < 0)
        {
            dao::$errors['estimate'] = $this->lang->task->error->recordMinus;
            return false;
        }

        /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
        if(!empty($this->config->limitTaskDate))
        {
            $this->task->checkEstStartedAndDeadline($task->execution, $task->estStarted, $task->deadline);
            if(dao::isError()) return false;
        }

        /* Check start and end date. */
        if(!helper::isZeroDate($task->deadline) && $task->estStarted > $task->deadline)
        {
            dao::$errors['deadline'] = $this->lang->task->error->deadlineSmall;
            return false;
        }

        return !dao::isError();
    }

    /**
     * 批量创建前检查必填项。
     * Check required fields before batch create tasks.
     *
     * @param  int            $executionID
     * @param  object[]       $tasks
     * @access protected
     * @return bool
     */
    protected function checkBatchCreateTask(int $executionID, array $tasks): bool
    {
        /* Set required fields. */
        $requiredFields = $this->config->task->create->requiredFields;
        $execution      = $this->loadModel('execution')->getById($executionID);
        if($this->task->isNoStoryExecution($execution)) $requiredFields = str_replace(',story,', ',', ',' . $requiredFields . ',');
        $requiredFields = array_filter(explode(',', $requiredFields));

        foreach($tasks as $rowIndex => $task)
        {
            /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
            }

            /* Check start and end date. */
            if(!helper::isZeroDate($task->deadline) && $task->deadline < $task->estStarted)
            {
                dao::$errors["deadline[$rowIndex]"] = $this->lang->task->error->deadlineSmall;
            }

            /* Check if the estimate is positive. */
            if($task->estimate < 0)
            {
                dao::$errors["estimate[$rowIndex]"] = $this->lang->task->error->recordMinus;
            }

            /* Check if the required fields are empty. */
            foreach($requiredFields as $field)
            {
                if(empty($task->$field))
                {
                    dao::$errors[$field . "[$rowIndex]"] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                }
            }
        }

        if(dao::isError()) return false;
        return true;
    }

    /**
     * 检查传入的批量编辑数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  array     $tasks
     * @param  array     $oldTasks
     * @access protected
     * @return bool
     */
    protected function checkBatchEditTask(array $tasks, array $oldTasks): bool
    {
        foreach($tasks as $taskID => $task)
        {
            $oldTask = $oldTasks[$taskID];

            /* Check work hours. */
            if(in_array($task->status, array('doing', 'pause')) && empty($oldTask->mode) && empty($task->left) && $task->parent >= 0)
            {
                dao::$errors["left[{$taskID}]"] = (array)sprintf($this->lang->task->error->leftEmptyAB, zget($this->lang->task->statusList, $task->status));
            }
            if($task->estimate < 0)  dao::$errors["estimate[$taskID]"]   = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->estimateAB);
            if($task->consumed < 0 ) dao::$errors["consumed[{$taskID}]"] = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->consumedThisTime);
            if($task->left < 0)      dao::$errors["left[$taskID]"]       = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->leftAB);

            if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($oldTask->execution, $task->estStarted, $task->deadline, "task:{$taskID} ");

            if($task->status == 'cancel') continue;
            if($task->status == 'done' && !$task->consumed) dao::$errors["consumed[{$taskID}]"] = (array)sprintf($this->lang->error->notempty, $this->lang->task->consumedThisTime);
            if(!empty($task->deadline) && $task->estStarted > $task->deadline) dao::$errors["deadline[{$taskID}]"] = (array)$this->lang->task->error->deadlineSmall;
        }
        return !dao::isError();
    }

    /**
     * 检查规定时间内是否创建了同名任务。
     * Check whether a task with the same name is created within the specified time.
     *
     * @param  object    $task
     * @access protected
     * @return int
     */
    protected function checkDuplicateName($task): int
    {
        /* Check duplicate task. */
        if($task->type == 'affair' || !$task->name) return 0;
        $sql    = "execution={$task->execution} AND story=" . (int)$task->story . (isset($task->feedback) ? " AND feedback=" . (int)$task->feedback : '');
        $result = $this->loadModel('common')->removeDuplicate('task', $task, $sql);
        if($result['stop']) return zget($result, 'duplicate', 0);
        return 0;
    }

    /**
     * 检查关联需求的测试类型任务数据格式是否符合要求。
     * Check if the test type task data format of the linked stories meets the requirements.
     *
     * @param  object[]  $tasks
     * @access protected
     * @return bool
     */
    protected function checkCreateTestTasks(array $tasks): bool
    {
        foreach($tasks as $task)
        {
            /* Check if the estimate is positive. */
            if($task->estimate < 0)
            {
                dao::$errors[] = "ID: {$task->story} {$this->lang->task->error->recordMinus}";
                return false;
            }

            /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($task->execution, $task->estStarted, $task->deadline);
                if(dao::isError())
                {
                    $error = current(dao::getError());
                    dao::$errors[] = "ID: {$task->story} {$error}";
                    return false;
                }
            }

            /* Check start and end date. */
            if($task->estStarted > $task->deadline)
            {
                dao::$errors[] = "ID: {$task->story} {$this->lang->task->error->deadlineSmall}";
                return false;
            }

            /* Checking the required fields of task data. */
            $this->dao->insert(TABLE_TASK)->data($task)->batchCheck($this->config->task->create->requiredFields, 'notempty');
            if(dao::isError())
            {
                $error = current(dao::getError());
                dao::$errors[] = "ID: {$task->story} {$error}";
                return false;
            }
        }
        return !dao::isError();
    }

    /**
     * 检查传入的开始数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  object    $oldTask
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function checkStart(object $oldTask, object $task): bool
    {
        $currentTeam = !empty($oldTask->team) ? $this->task->getTeamByAccount($oldTask->team) : array();
        if(!empty($oldTask->team))
        {
            if($currentTeam && $task->consumed < $currentTeam->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
            if($currentTeam && $currentTeam->status == 'doing' && $oldTask->status == 'doing') dao::$errors[] = $this->lang->task->error->alreadyStarted;
        }
        else
        {
            if($task->consumed < $oldTask->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
            if($oldTask->status == 'doing') dao::$errors[] = $this->lang->task->error->alreadyStarted;
        }
        if(!$task->left && !$task->consumed) dao::$errors['consumed'] = sprintf($this->lang->error->notempty, $this->lang->task->consumed);
        return !dao::isError();
    }

    /**
     * 格式化导出任务的字段。
     * Format export task.
     *
     * @param  object    $task
     * @param  array     $projects
     * @param  array     $executions
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function formatExportTask(object $task, array $projects, array $executions, array $users): object
    {
        $taskLang = $this->lang->task;
        if($this->post->fileType == 'csv')
        {
            $task->desc = htmlspecialchars_decode((string)$task->desc);
            $task->desc = str_replace("<br />", "\n", $task->desc);
            $task->desc = str_replace('"', '""', $task->desc);
            $task->desc = str_replace('&nbsp;', ' ', $task->desc);
        }

        if(isset($projects[$task->project]))                  $task->project      = $projects[$task->project] . "(#$task->project)";
        if(isset($executions[$task->execution]))              $task->execution    = $executions[$task->execution] . "(#$task->execution)";
        if(isset($taskLang->typeList[$task->type]))           $task->type         = $taskLang->typeList[$task->type];
        if(isset($taskLang->priList[$task->pri]))             $task->pri          = $taskLang->priList[$task->pri];
        if(isset($taskLang->statusList[$task->status]))       $task->status       = $this->processStatus('task', $task);
        if(isset($taskLang->reasonList[$task->closedReason])) $task->closedReason = $taskLang->reasonList[$task->closedReason];
        if(isset($taskLang->modeList[$task->mode]))           $task->mode         = $taskLang->modeList[$task->mode];

        if(isset($users[$task->openedBy]))     $task->openedBy     = $users[$task->openedBy];
        if(isset($users[$task->assignedTo]))   $task->assignedTo   = $users[$task->assignedTo];
        if(isset($users[$task->finishedBy]))   $task->finishedBy   = $users[$task->finishedBy];
        if(isset($users[$task->canceledBy]))   $task->canceledBy   = $users[$task->canceledBy];
        if(isset($users[$task->closedBy]))     $task->closedBy     = $users[$task->closedBy];
        if(isset($users[$task->lastEditedBy])) $task->lastEditedBy = $users[$task->lastEditedBy];

        $task->openedDate     = helper::isZeroDate($task->openedDate) ? '' : substr($task->openedDate,     0, 10);
        $task->assignedDate   = helper::isZeroDate($task->assignedDate) ? '' : substr($task->assignedDate,   0, 10);
        $task->finishedDate   = helper::isZeroDate($task->finishedDate) ? '' : substr($task->finishedDate,   0, 10);
        $task->canceledDate   = helper::isZeroDate($task->canceledDate) ? '' : substr($task->canceledDate,   0, 10);
        $task->closedDate     = helper::isZeroDate($task->closedDate) ? '' : substr($task->closedDate,     0, 10);
        $task->lastEditedDate = helper::isZeroDate($task->lastEditedDate) ? '' : substr($task->lastEditedDate, 0, 10);
        $task->estimate       = $task->estimate . $this->lang->execution->workHourUnit;
        $task->consumed       = $task->consumed . $this->lang->execution->workHourUnit;
        $task->left           = $task->left     . $this->lang->execution->workHourUnit;

        return $task;
    }

    /**
     * 为表单获取自定义字段。
     * Get task's custom fields for form.
     *
     * @param  object    $execution
     * @param  string    $action
     * @access protected
     * @return array
     */
    protected function getCustomFields(object $execution, string $action): array
    {
        /* 设置自定义字段列表。 Set custom field list. */
        $customFormField = 'custom' . ucfirst($action). 'Fields';
        foreach(explode(',', $this->config->task->{$customFormField}) as $field)
        {
            if($execution->type == 'stage' && strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }

        /* 设置已勾选的自定义字段。 Set checked custom fields. */
        $checkedFields = $this->config->task->custom->{$action . 'Fields'};
        if($execution->lifetime == 'ops' || $execution->attribute == 'request' || $execution->attribute == 'review')
        {
            unset($customFields['story']);
            $checkedFields = str_replace(',story,', ',', ",{$checkedFields},");
            $checkedFields = trim($checkedFields, ',');
        }

        return array($customFields, $checkedFields);
    }

    /**
     * 获取导出的字段。
     * Get export fields.
     *
     * @param  string    $allExportFields
     * @access protected
     * @return void
     */
    protected function getExportFields(string $allExportFields): array
    {
        $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $allExportFields);

        /* Compatible with the new UI widget. */
        if($this->post->exportFields && str_contains($fields[0], ',')) $fields = explode(',', $fields[0]);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($this->lang->task->$fieldName) ? $this->lang->task->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * 处理创建后选择跳转的返回信息。
     * Process the return information for selecting a jump after creation.
     *
     * @param  object    $task
     * @param  int       $executionID
     * @param  string    $afterChoose continueAdding|toTaskList|toStoryList
     * @access protected
     * @return array
     */
    protected function generalCreateResponse(object $task, int $executionID, string $afterChoose): array
    {
        /* Set the universal return value. */
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;
        $response['load']    = $this->createLink('execution', 'browse', "executionID={$executionID}&tab=task");

        /* Set the response to continue adding task to story. */
        $executionID = $task->execution;
        if($afterChoose == 'continueAdding')
        {
            $storyID  = $task->story ? $task->story : 0;
            $moduleID = $task->module ? $task->module : 0;
            $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
            $response['load']    = $this->createLink('task', 'create', "executionID={$executionID}&storyID={$storyID}&moduleID={$moduleID}");
            return $response;
        }

        /* Set the response to return task list. */
        if($afterChoose == 'toTaskList')
        {
            helper::setcookie('moduleBrowseParam', '0');
            $response['load'] = $this->createLink('execution', 'task', "executionID={$executionID}&status=unclosed&param=0&orderBy=id_desc");
            return $response;
        }

        /* Set the response to return story list. */
        $response['load'] = $this->createLink('execution', 'story', "executionID={$executionID}");
        if($this->config->vision == 'lite')
        {
            $execution = $this->execution->getByID($executionID);
            $response['load'] = $this->createLink('projectstory', 'story', "projectID={$execution->project}");
        }
        return $response;
    }

    /**
     * 检查当前用户在该执行中是否是受限用户。
     * Checks if the current user is a limited user in this execution.
     *
     * @param  int       $executionID
     * @access protected
     * @return bool
     */
    protected function isLimitedInExecution(int $executionID): bool
    {
        $limitedExecutions = $this->execution->getLimitedExecution();

        if(strpos(",{$limitedExecutions},", ",$executionID,") !== false) return true;
        return false;
    }

    /**
     * 准备管理团队的数据。
     * Prepare manage team data.
     *
     * @param  form      $postData
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function prepareManageTeam(form $postData, int $taskID): object
    {
        return $postData->add('id', $taskID)
            ->add('lastEditedBy', $this->app->user->account)
            ->get();
    }

    /**
     * 处理导出的任务数据。
     * Process export data.
     *
     * @param  array     $tasks
     * @param  int       $projectID
     * @access protected
     * @return object[]
     */
    protected function processExportData(array $tasks, int $projectID): array
    {
        /* Get users and executions. */
        $users      = $this->loadModel('user')->getPairs('noletter');
        $projects   = $this->loadModel('project')->getPairs();
        $executions = $this->loadModel('execution')->fetchPairs(0, 'all', true, true);

        /* Get related objects id lists. */
        $relatedStoryIdList  = array();
        foreach($tasks as $task)
        {
            $relatedStoryIdList[$task->story] = $task->story;
            $relatedBugIdList[$task->fromBug] = $task->fromBug;
        }

        /* Get related objects title or names. */
        $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($relatedStoryIdList)->fetchPairs();
        $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('task')->andWhere('objectID')->in(array_keys($tasks))->andWhere('extra')->ne('editor')->fetchGroup('objectID');
        $relatedModules = $this->loadModel('tree')->getAllModulePairs('task');

        $bugs = $this->loadModel('bug')->getByIdList($relatedBugIdList);
        foreach($tasks as $task)
        {
            $task->story   = isset($relatedStories[$task->story]) ? $relatedStories[$task->story] . "(#$task->story)" : '';
            $task->fromBug = empty($task->fromBug) ? '' : "#$task->fromBug " . $bugs[$task->fromBug]->title;

            if(isset($relatedModules[$task->module]))             $task->module       = $relatedModules[$task->module] . "(#$task->module)";

            /* Convert username to real name. */
            if(!empty($task->mailto))
            {
                $mailtoList = explode(',', $task->mailto);

                $task->mailto = '';
                foreach($mailtoList as $mailto)
                {
                    if(!empty($mailto)) $task->mailto .= ',' . zget($users, $mailto);
                }
            }

            if($task->parent > 0 && strpos($task->name, htmlentities('>')) !== 0) $task->name = '>' . $task->name;
            if(!empty($task->team))
            {
                $task->name = '[' . $this->lang->task->multipleAB . '] ' . $task->name;
                unset($task->team);
            }

            $task = $this->formatExportTask($task, $projects, $executions, $users);

            /* Set related files. */
            $task->files = '';
            if(isset($relatedFiles[$task->id]))
            {
                foreach($relatedFiles[$task->id] as $file)
                {
                    $fileURL = common::getSysURL() . $this->createLink('file', 'download', "fileID={$file->id}");
                    $task->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                }
            }
        }

        return $tasks;
    }

    /**
     * 处理导出任务分组信息。
     * Process export task group information.
     *
     * @param  int       $executionID
     * @param  array     $tasks
     * @param  string    $orderBy
     * @access protected
     * @return object[]
     */
    protected function processExportGroup(int $executionID, array $tasks, string $orderBy): array
    {
        $stories    = $this->loadModel('story')->getExecutionStories($executionID);
        $groupTasks = array();
        foreach($tasks as $task)
        {
            $task->storyTitle = isset($stories[$task->story]) ? $stories[$task->story]->title : '';

            if(!isset($task->team))
            {
                $groupTasks[$task->$orderBy][] = $task;
                continue;
            }

            if($orderBy == 'finishedBy') $task->consumed = $task->estimate = $task->left = 0;
            foreach($task->team as $team)
            {
                if($orderBy == 'finishedBy' and $team->left != 0)
                {
                    $task->estimate += $team->estimate;
                    $task->consumed += $team->consumed;
                    $task->left     += $team->left;
                    continue;
                }

                $cloneTask = clone $task;
                $cloneTask->estimate = $team->estimate;
                $cloneTask->consumed = $team->consumed;
                $cloneTask->left     = $team->left;
                if($team->left == 0) $cloneTask->status = 'done';

                if($orderBy == 'assignedTo') $cloneTask->assignedTo = $team->account;
                if($orderBy == 'finishedBy') $cloneTask->finishedBy = $team->account;
                $groupTasks[$team->account][] = $cloneTask;
            }
            if(!empty($task->left) and $orderBy == 'finishedBy') $groupTasks[$task->finishedBy][] = $task;
        }

        $tasks = array();
        foreach($groupTasks as $groupTask)
        {
            foreach($groupTask as $task) $tasks[] = $task;
        }

        return $tasks;
    }

    /**
     * 通过任务状态处理任务的人员、日期字段。
     * Process the person and date fields of a task by status.
     *
     * @param  object    $task
     * @param  object    $oldTask
     * @access protected
     * @return object
     */
    protected function processTaskByStatus(object $task, object $oldTask): object
    {
        $now            = helper::now();
        $currentAccount = $this->app->user->account;
        switch($task->status)
        {
        case 'done':
            $task->left = 0;
            $task->finishedBy   = $oldTask->status == 'done' ? $oldTask->finishedBy : $currentAccount;
            $task->finishedDate = $oldTask->status == 'done' ? $oldTask->finishedDate : $now;
            $task->canceledBy   = '';
            $task->canceledDate = null;
            break;
        case 'cancel':
            $task->canceledBy   = $oldTask->status == 'cancel' ? $oldTask->canceledBy : $currentAccount;
            $task->canceledDate = $oldTask->status == 'cancel' ? $oldTask->canceledDate : $now;
            $task->assignedTo   = $oldTask->openedBy;
            $task->assignedDate = $now;
            $task->finishedBy   = '';
            $task->finishedDate = null;
            break;
        case 'closed':
            $task->closedBy   = $oldTask->status == 'closed' ? $oldTask->closedBy : $currentAccount;
            $task->closedDate = $oldTask->status == 'closed' ? $oldTask->closedDate : $now;
            if($task->closedReason == 'cancel' and helper::isZeroDate($task->finishedDate)) $task->finishedDate = null;
            break;
        case 'wait':
            if($task->consumed > 0 and $task->left > 0) $task->status = 'doing';
            if($task->left == $oldTask->left and $task->consumed == 0) $task->left = $task->estimate;
            break;
        case 'pause':
            $task->finishedDate = null;
        default:
            break;
        }
        if(in_array($task->status, array('wait', 'doing')))
        {
            $task->canceledBy   = '';
            $task->finishedBy   = '';
            $task->closedBy     = '';
            $task->canceledDate = null;
            $task->finishedDate = null;
            $task->closedDate   = null;
            $task->closedReason = '';
        }
        return $task;
    }

    /**
     * 编辑任务后返回响应.
     * Response after edit.
     *
     * @param  int       $taskID
     * @param  string    $from    ''|taskkanban
     * @param  array[]   $changes
     * @access protected
     * @return array
     */
    protected function responseAfterEdit(int $taskID, string $from, array $changes): array
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $taskID);

        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;

        $task = $this->task->getById($taskID);
        if($task->fromBug != 0)
        {
            foreach($changes as $change)
            {
                if($change['field'] == 'status')
                {
                    $response['callback'] = "parent.confirmBug('" . sprintf($this->lang->task->remindBug, $task->fromBug) . "', {$task->fromBug})";
                    return $response;
                }
            }
        }

        if(helper::isAjaxRequest('modal')) return $this->responseModal($task, $from);

        $response['load'] = $this->createLink('task', 'view', "taskID=$taskID");
        return $response;
    }

    /**
     * 批量编辑任务后返回响应。
     * Response after batch edit.
     *
     * @param  array[]   $allChanges
     * @access protected
     * @return array
     */
    protected function responseAfterBatchEdit(array $allChanges): array
    {
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        if(!empty($allChanges))
        {
            foreach($allChanges as $taskID => $changes)
            {
                $task = $this->task->getById($taskID);
                if(!$task->fromBug) continue;
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $response['callback'] = "parent.confirmBug('" . sprintf($this->lang->task->remindBug, $task->fromBug) . "', {$task->fromBug})";
                        return $response;
                    }
                }
            }
        }

        $response['load'] = $this->session->taskList;
        return $response;
    }

    /**
     * 指派后返回响应.
     * Response after assignto.
     *
     * @param  int       $taskID
     * @param  string    $from   ''|taskkanban
     * @access protected
     * @return array
     */
    protected function responseAfterAssignTo(int $taskID, string $from): array
    {
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success');

        $task = $this->task->getById($taskID);
        if(helper::isAjaxRequest('modal')) return $this->responseModal($task, $from);

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('task', 'view', "taskID=$taskID"));
    }

    /**
     * 如果页面是弹窗，则调用此方法得到正确的返回链接。
     * Get response information of a modal page.
     *
     * @param  object    $task
     * @param  string    $from     ''|taskkanban
     * @access protected
     * @return array
     */
    protected function responseModal(object $task, string $from): array
    {
        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;

        $execution = $this->loadModel('execution')->getByID((int)$task->execution);

        $inLiteKanban = $this->config->vision == 'lite' && $this->app->tab == 'project' && $this->session->kanbanview == 'kanban';
        if((($this->app->tab == 'execution' || $inLiteKanban) && $execution->type == 'kanban') || $from == 'taskkanban')
        {
            $response['callback'] = 'refreshKanban()';
            return $response;
        }

        $response['load'] = true;
        return $response;
    }

    /**
     * 处理创建任务后的返回信息。
     * The information return after process the create task.
     *
     * @param  object    $taskID
     * @param  object    $execution
     * @param  string    $afterChoose continueAdding|toTaskList|toStoryList
     * @access protected
     * @return array
     */
    protected function responseAfterCreate(object $task, object $execution, string $afterChoose): array
    {
        /* If there is a database error, return the error message. */
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;

        $message = $this->executeHooks($task->id);
        if($message) $response['message'] = $message;

        /* Return task id when call the API. */
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $task->id);

        /* Processing the return information of pop-up windows. */
        if(helper::isAjaxRequest('modal'))
        {
            /* If it is Kanban execution, refresh the Kanban statically through callback. */
            if($this->app->tab == 'execution' || $this->config->vision == 'lite')
            {
                $response['closeModal'] = true;
                $response['callback']   = 'refreshKanban()';
                return $response;
            }
            $response['load'] = true;
            return $response;
        }

        /* Locate the browser. */
        if($this->app->getViewType() == 'xhtml')
        {
            $response['load'] = $this->createLink('task', 'view', "taskID={$task->id}", 'html');
            return $response;
        }

        /* Process the return information for selecting a jump after creation. */
        return $this->generalCreateResponse($task, $execution->id, $afterChoose);
    }

    /**
     * 处理批量创建任务后的返回信息。
     * The information return after process the batch create task.
     *
     * @param  array     $taskIdList
     * @param  object    $execution
     * @access protected
     * @return array
     */
    protected function responseAfterbatchCreate(array $taskIdList, object $execution): array
    {
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        /* Return task id when call the API. */
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $taskIdList));

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        if(helper::isAjaxRequest('modal') && ($this->app->tab == 'execution' || $this->config->vision == 'lite'))
        {
            $response['closeModal'] = true;
            $response['callback']   = "refreshKanban()";
            return $response;
        }

        $link = $this->createLink('execution', 'browse', "executionID={$execution->id}");
        if($this->app->tab == 'my') $link = $this->createLink('my', 'work', 'mode=task');
        if($this->app->tab == 'project' && $execution->multiple) $link = $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        $response['load'] = $link;

        return $response;
    }

    /**
     * 处理开始任务后的返回信息。
     * The information return after process the start task.
     *
     * @param  object    $task
     * @param  string    $from ''|taskkanban
     * @access protected
     * @return array
     */
    protected function responseAfterChangeStatus(object $task, string $from): array
    {
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success');
        if(isInModal()) return $this->responseModal($task, $from);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true);
    }

    /**
     * 处理批量关闭操作之后的返回信息。
     * The information return after process the batch close task.
     *
     * @param  array     $skipTasks
     * @param  array     $parentTasks
     * @param  string    $confirm       yes|no
     * @access protected
     * @return array
     */
    protected function responseAfterBatchClose(array $skipTasks, array $parentTasks, string $confirm): array
    {
        if(!empty($skipTasks) && $confirm == 'no')
        {
            $skipTasks  = implode(',', $skipTasks);
            $confirmURL = $this->createLink('task', 'batchClose', "confirm=yes");
            $cancelURL  = $this->server->HTTP_REFERER;
            $this->session->set('batchCloseTaskIDList', $skipTasks, 'task');
            return array('result' => 'success', 'load' => array('confirm' => sprintf($this->lang->task->error->skipClose, $skipTasks), 'confirmed' => $confirmURL, 'canceled' => $cancelURL));
        }

        if(!empty($parentTasks))
        {
            $parentTasks = implode(',', $parentTasks);
            return array('result' => 'success', 'load' => true, 'message' => sprintf($this->lang->task->error->closeParent, $parentTasks));
        }

        return array('result' => 'success', 'load' => true);
    }

    /**
     * 在记录工时后获取跳转链接。
     * Get response information after record effort.
     *
     * @param  object    $task
     * @param  array     $changes
     * @param  string    $from
     * @access protected
     * @return int|array
     */
    protected function responseAfterRecord(object $task, array $changes, string $from): int|array
    {
        /* Remind whether to update status of the bug, if task which from that bug has been finished. */
        if($changes and $this->task->needUpdateBugStatus($task))
        {
            $response = $this->taskTao->getRemindBugLink($task, $changes);
            if($response) return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $response['confirmed'], $response['canceled'], 'parent', 'parent.parent'));
        }

        if(helper::isAjaxRequest('modal')) return $this->responseModal($task, $from);

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;
        $response['load']    = $this->createLink('execution', 'browse', "executionID={$task->execution}&tab=task");
        return $response;
    }

    /**
     * 在批量创建之前移除post数据中重复的数据。
     * Remove the duplicate data before batch create tasks.
     *
     * @param  int       $executionID
     * @param  array     $tasks
     * @access protected
     * @return array
     */
    protected function removeDuplicateForBatchCreate(int $executionID, array $tasks): array
    {
        /* 1. 检查表单是否有重复。 Check duplicate in form data. */
        $duplicateTasks = array();
        $storyIdList    = array();
        foreach($tasks as $rowIndex => $task)
        {
            if(empty($task->story)) continue;

            /* 事务型任务可能有多个指派人，不需要检查是否重名。 Tasks of Affair type no need to check duplicate name. */
            if($task->type == 'affair') continue;

            /* 表单的任务名称+不能有重复。 The name of post tasks must be unique. */

            /* 检查Post传过来的任务有没有重复数据，不能有相同需求的同名任务。 Check whether the post tasks have duplicate data. */
            $duplicateKey = (string)$task->story . '-' . $task->name;
            if(isset($duplicateTasks[$duplicateKey]))
            {
                dao::$errors["name[$rowIndex]"] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $task->name;
                return array();
            }
            $duplicateTasks[$duplicateKey] = array('rowIndex' => $rowIndex, 'name' => $task->name);
            $storyIdList[$task->story]     = $task->story;
        }

        /* 2. 检查数据库是否有重复数据。 Check duplicate in db. */
        $existTasks = $this->task->getListByStories($storyIdList, $executionID);
        foreach($existTasks as $task)
        {
            $duplicateKey = (string)$task->story . '-' . $task->name;
            if(isset($duplicateTasks[$duplicateKey]))
            {
                $rowIndex = $duplicateTasks[$duplicateKey]['rowIndex'];
                unset($tasks[$rowIndex]);
            }
        }

        return $tasks;
    }

    /**
     * 通过传入的对象ID设置任务信息。
     * Set task through the input object ID.
     *
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  int       $todoID
     * @param  int       $bugID
     * @access protected
     * @return object
     */
    protected function setTaskByObjectID(int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID): object
    {
        $task = $this->config->task->create->template;
        $task->module = $moduleID;

        /* If exist task, copy task information by task id. */
        if($taskID)
        {
            /* Emptying consumed hours when copy task. */
            $task = $this->task->getByID($taskID);
            if($task->mode == 'multi')
            {
                foreach($task->team as $teamMember) $teamMember->consumed = 0;
            }
        }

        /* If exist todo, copy todo information by todo id. */
        if($todoID)
        {
            $todo = $this->loadModel('todo')->getById($todoID);
            $task->name = $todo->name;
            $task->pri  = $todo->pri;
            $task->desc = $todo->desc;
        }

        /* If exist bug, copy bug information by bug id. */
        if($bugID)
        {
            $bug = $this->loadModel('bug')->getById($bugID);
            $task->name       = $bug->title;
            $task->pri        = !empty($bug->pri) ? $bug->pri : $this->config->task->default->pri;
            $task->assignedTo = array($bug->assignedTo);
        }

        /* If exist story, copy story module by story id. */
        if($storyID)
        {
            $task->story  = $storyID;
            $task->module = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch('module');
        }
        elseif(!$moduleID)
        {
            $task->module = (int)$this->cookie->lastTaskModule;
        }

        return $task;
    }

    /**
     * 设置任务二级导航。
     * Set the task secondary navigation.
     *
     * @param  int       $executionID
     * @access protected
     * @return int
     */
    protected function setMenu(int $executionID): int
    {
        $execution = $this->execution->getById($executionID);

        /* If the admin denied modification of closed executions, only query not closed executions. */
        $queryMode = $execution && common::canModify('execution', $execution) ? 'all' : 'noclosed';

        /* Get executions the current user can access. */
        $this->executionPairs = $this->execution->getPairs(0, 'all', $queryMode);

        /* Call checkAccess method to judge the user can access the execution or not, if not return the first one he can access. */
        $executionID = $this->execution->checkAccess($executionID, $this->executionPairs);

        /* Set Menu. */
        $this->execution->setMenu($executionID);
        if($this->app->tab == 'project') $this->project->setMenu($execution->project);

        return $executionID;
    }
}
