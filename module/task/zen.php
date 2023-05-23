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
        $executionID      = $execution->id;
        $showAllModule    = zget($this->config->execution->task, 'allModule', '');
        $modulePairs      = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');

        /* Display relevant variables. */
        $this->assignExecutionForCreate($execution);
        $this->assignStoryForCreate($executionID);
        if($execution->type == 'kanban') $this->assignKanbanForCreate($executionID, $output);

        /* Set Custom fields. */
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $this->view->title         = $execution->name . $this->lang->colon . $this->lang->task->create;
        $this->view->customFields  = $customFields;
        $this->view->showAllModule = $showAllModule;
        $this->view->modulePairs   = $modulePairs;
        $this->view->showFields    = $this->config->task->custom->createFields;
        $this->view->gobackLink    = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID={$executionID}") : '';
        $this->view->execution     = $execution;
        $this->view->storyID       = $storyID;
        $this->view->blockID       = isonlybody() ? $this->loadModel('block')->getSpecifiedBlockID('my', 'assingtome', 'assingtome') : 0;
        $this->view->hideStory     = $this->task->isNoStoryExecution($execution);

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
     * @access protected
     * @return void
     */
    protected function assignStoryForCreate(int $executionID): void
    {
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', '', 'active');
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
        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, $task->parent);
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
        $this->view->modules       = $this->tree->getTaskOptionMenu($task->execution, 0, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions    = $executions;
        $this->view->contactLists  = $this->loadModel('user')->getContactLists($this->app->user->account, 'withnote');
        $this->display();
    }

    /**
     * 创建批量编辑表单。
     * Build batch edit form.
     *
     * @param  array     $taskIdList
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function buildBatchEditForm(array $taskIdList, int $executionID): void
    {
        /* Get edited tasks. */
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIdList)->fetchAll('id');
        $teams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'id');

        /* Get execution teams. */
        $executionIdList = array();
        foreach($tasks as $task) if(!in_array($task->execution, $executionIdList)) $executionIdList[] = $task->execution;
        $executionTeams = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($executionIdList)->andWhere('type')->eq('execution')->fetchGroup('root', 'account');

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($tasks) * (count(explode(',', $this->config->task->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom. */
        foreach(explode(',', $this->config->task->customBatchEditFields) as $field)
        {
            if(!empty($this->view->executionn) && $this->view->execution->type == 'stage' && strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchEditFields;

        /* Assign. */
        $this->view->executionID    = $executionID;
        $this->view->priList        = array('0' => '', 'ditto' => $this->lang->task->ditto) + $this->lang->task->priList;
        $this->view->statusList     = array('ditto' => $this->lang->task->ditto) + $this->lang->task->statusList;
        $this->view->typeList       = array('ditto' => $this->lang->task->ditto) + $this->lang->task->typeList;
        $this->view->taskIDList     = $taskIdList;
        $this->view->tasks          = $tasks;
        $this->view->teams          = $teams;
        $this->view->executionTeams = $executionTeams;
        $this->view->executionName  = isset($execution) ? $execution->name : '';
        $this->view->executionType  = isset($execution) ? $execution->type : '';
        $this->view->users          = $this->loadModel('user')->getPairs('nodeleted');

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
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, 0, $showAllModule);
        $story         = $this->story->getByID($storyID);
        $stories       = $this->story->getExecutionStoryPairs($execution->id, 0, 'all', $story ? $story->module : 0, 'short', 'active');

        list($customFields, $checkedFields) = $this->getCustomFields($execution, 'batchCreate');

        $this->view->title         = $execution->name . $this->lang->colon . $this->lang->task->batchCreate;
        $this->view->execution     = $execution;
        $this->view->modules       = $modules;
        $this->view->parent        = $taskID;
        $this->view->storyID       = $storyID;
        $this->view->story         = $story;
        $this->view->moduleID      = $story ? $story->module : $moduleID;
        $this->view->stories       = $stories;
        $this->view->storyTasks    = $this->task->getStoryTaskCounts(array_keys($stories), $execution->id);
        $this->view->members       = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        $this->view->taskConsumed  = isset($task) ? $task->consumed : 0;
        $this->view->customFields  = $customFields;
        $this->view->checkedFields = $checkedFields;
        $this->view->hideStory     = $this->task->isNoStoryExecution($execution);

        $this->display();
    }

    /**
     * 构造待更新的任务数据。
     * Build the task data to be update.
     *
     * @param  int          $taskID
     * @param  object       $task
     * @access protected
     * @return object|false
     */
    protected function buildTaskForEdit(int $taskID, object $task): object|false
    {
        $oldTask = $this->task->getByID($taskID);

        /* Check if the fields is valid. */
        if($task->estimate < 0 or $task->left < 0 or $task->consumed < 0) dao::$errors[] = $this->lang->task->error->recordMinus;
        if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($oldTask->execution, $task->estStarted, $task->deadline);
        if(!empty($task->lastEditedDate) && $oldTask->lastEditedDate != $task->lastEditedDate) dao::$errors[] = $this->lang->error->editedByOther;
        if(dao::isError()) return false;

        $now  = helper::now();
        $task = form::data($this->config->task->form->edit)->add('id', $taskID)
            ->setIF(!$task->assignedTo && !empty($oldTask->team) && !empty($this->post->team), 'assignedTo', $this->task->getAssignedTo4Multi($this->post->team, $oldTask))
            ->setIF($task->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)
            ->setIF($task->mode == 'single', 'mode', '')
            ->setIF(!$oldTask->mode && !$task->assignedTo && !empty($this->post->team), 'assignedTo', $this->post->team[0])
            ->setIF($task->story !== false && $task->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($task->story))
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
        $muletipleTasks = $this->dao->select('task, account')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'account');
        $tasks          = $this->task->getByList($taskIdList);
        /* Filter tasks. */
        foreach($tasks as $taskID => $task)
        {
            if(isset($muletipleTasks[$taskID]) && $task->assignedTo != $this->app->user->account && $task->mode == 'linear') unset($tasks[$taskID]);
            if(isset($muletipleTasks[$taskID]) && !isset($muletipleTasks[$taskID][$this->post->assignedTo])) unset($tasks[$taskID]);
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
     * @param  int       $executionID
     * @param  int       $taskID
     * @access protected
     * @return object[]
     */
    protected function buildTasksForBatchCreate(int $executionID, int $taskID): array
    {
        $tasks = form::batchData($this->config->task->form->batchCreate)->get();
        foreach($tasks as $task)
        {
            $task->execution = $executionID;
            $task->left      = $task->estimate;
        }

        /* 去除重复数据。 Deduplicated data. */
        return $this->removeDuplicate4BatchCreate($executionID, $tasks);
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
        $formData  = form::data($this->config->task->form->create);
        $postData  = $formData->get();
        $execution = $this->dao->findById($postData->execution)->from(TABLE_EXECUTION)->fetch();
        $team      = !empty($postData->team) ? array_filter($postData->team) : array();
        $task      = $formData->setDefault('execution', $executionID)
            ->setDefault('project', $execution->project)
            ->setIF($postData->estimate !== false, 'left', $postData->estimate)
            ->setIF(isset($postData->story), 'storyVersion', isset($postData->story) ? $this->loadModel('story')->getVersion($postData->story) : 0)
            ->setIF(empty($postData->multiple) || count($team) < 1, 'mode', '')
            ->setIF($this->task->isNoStoryExecution($execution), 'story', 0)
            ->setIF(!empty($postData->assignedTo), 'assignedDate', helper::now())
            ->get();

        if(empty($postData->estStarted)) unset($task->estStarted);
        if(empty($postData->deadline)) unset($task->deadline);

        /* Processing image link. */
        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $postData->uid);

        /* Check if the input post data meets the requirements. */
        $this->checkCreateTask($task);
        return $task;
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
        $task = form::data($this->config->task->form->activate)->add('id', $taskID)->get();

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
        if(!isset($postData->selectTestStory)) return array();

        $testTasks = array();
        foreach($postData->testStory as $key => $storyID)
        {
            if(empty($storyID)) continue;

            /* Process the ditto option as a concrete value. */
            $estStarted = !isset($postData->testEstStarted[$key]) || isset($postData->estStartedDitto[$key]) ? $estStarted : $postData->testEstStarted[$key];
            $deadline   = !isset($postData->testDeadline[$key]) || isset($postData->deadlineDitto[$key]) ? $deadline : $postData->testDeadline[$key];
            $assignedTo = !isset($postData->testAssignedTo[$key]) || $postData->testAssignedTo[$key] == 'ditto' ? $assignedTo : $postData->testAssignedTo[$key];

            /* Set task data. */
            $task = new stdclass();
            $task->execution  = $executionID;
            $task->story      = $storyID;
            $task->pri        = $postData->testPri[$key];
            $task->estStarted = $estStarted;
            $task->deadline   = $deadline;
            $task->assignedTo = $assignedTo;
            $task->estimate   = (float)$postData->testEstimate[$key];
            $task->left       = (float)$postData->testEstimate[$key];

            $testTasks[$storyID] = $task;
        }

        $this->checkCreateTestTasks($testTasks);
        return $testTasks;
    }

    /**
     * 批量创建任务之前构造数据。
     * Build data before batch create tasks.
     *
     * @param  object    $execution
     * @param  object    $tasks
     * @param  int       $index
     * @param  array     $dittoFields
     * @param  array     $extendFields
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildData4BatchCreate(object $execution, object $tasks, int $index, array $dittoFields, array $extendFields, int $taskID): object
    {
        extract($dittoFields);
        $now = helper::now();

        $task             = new stdclass();
        $task->story      = (int)$story;
        $task->type       = $type;
        $task->module     = (int)$module;
        $task->assignedTo = $assignedTo;
        $task->color      = isset($tasks->color[$index]) ? $tasks->color[$index] : '';
        $task->name       = $tasks->name[$index];
        $task->desc       = nl2br($tasks->desc[$index]);
        $task->pri        = (int)$tasks->pri[$index];
        $task->estimate   = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $task->left       = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $task->project    = $execution->project;
        $task->execution  = $execution->id;
        $task->estStarted = $estStarted;
        $task->deadline   = $deadline;
        $task->status     = 'wait';
        $task->openedBy   = $this->app->user->account;
        $task->openedDate = $now;
        $task->parent     = $taskID;
        $task->vision     = isset($tasks->vision[$index]) ? $tasks->vision[$index] : 'rnd';
        $task->version    = 1;
        if($story) $task->storyVersion = (int)$this->dao->findById($task->story)->from(TABLE_STORY)->fetch('version');
        if($assignedTo) $task->assignedDate = $now;
        if(strpos($this->config->task->create->requiredFields, 'estStarted') !== false && empty($estStarted)) $task->estStarted = '';
        if(strpos($this->config->task->create->requiredFields, 'deadline') !== false && empty($deadline))     $task->deadline   = '';
        if(isset($tasks->lanes[$index])) $task->laneID = $tasks->lanes[$index];

        /* 处理工作流字段。 Process workflow fields. */
        foreach($extendFields as $extendField)
        {
            $task->{$extendField->field} = $tasks->{$extendField->field}[$index];
            if(is_array($task->{$extendField->field})) $task->{$extendField->field} = implode(',', $task->{$extendField->field});

            $task->{$extendField->field} = htmlSpecialString($task->{$extendField->field});
        }

        return $task;
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
     * 根据页面是执行还是我的地盘设置参数。
     * Set parameters based on whether the page is execution or my.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function batchEdit4Pages(int $executionID): void
    {
        /* The tasks of execution. */
        if($executionID)
        {
            $execution = $this->execution->getById($executionID);
            $this->execution->setMenu($execution->id);

            /* Set modules and members. */
            $showAllModule = isset($this->config->task->allModule) ? $this->config->task->allModule : '';
            $modules       = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
            $modules       = array('ditto' => $this->lang->task->ditto) + $modules;

            $this->view->title      = $execution->name . $this->lang->colon . $this->lang->task->batchEdit;
            $this->view->execution  = $execution;
            $this->view->modules    = $modules;
        }
        /* The tasks of my. */
        else
        {
            /* Set my menu. */
            $this->loadModel('my');
            $this->lang->my->menu->work['subModule'] = 'task';

            $this->view->title      = $this->lang->task->batchEdit;
            $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        }
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
        $this->view->task      = $this->loadModel('task')->getByID($taskID);
        $this->view->execution = $this->execution->getById($this->view->task->execution);
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
                $this->task->checkEstStartedAndDeadline($task->execuiton, $task->estStarted, $task->deadline);
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
     * 检查是否能记录任务的日志。
     * Check if the task effort can be recorded.
     *
     * @param  object    $task
     * @access protected
     * @return bool
     */
    protected function checkRecordEffort(object $task): bool
    {
        if(empty($task->team)) return true;
        if($task->assignedTo != $this->app->user->account && $task->mode == 'linear') return false;
        if(!isset($task->members[$this->app->user->account])) return false;
        return true;
    }

    /**
     * 检查当前用户在该执行中是否是受限用户。
     * Checks if the current user is a limited user in this execution.
     *
     * @param  string    $executionID
     * @access protected
     * @return bool
     */
    protected function isLimitedInExecution(string $executionID): bool
    {
        $this->execution->getLimitedExecution();
        $limitedExecutions = !empty($this->session->limitedExecutions) ? $this->session->limitedExecutions : '';

        if(strpos(",{$limitedExecutions},", ",$executionID,") !== false) return true;
        return false;
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
     * 获取跳转链接。
     * Get jump link.
     *
     * @param  object    $execution
     * @access protected
     * @return string
     */
    protected function getJumpLink(object $execution): string
    {
        if($this->app->tab == 'my')
        {
            return $this->createLink('my', 'work', 'mode=task');
        }
        elseif($this->app->tab == 'project' && $execution->multiple)
        {
            return $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        }
        else
        {
            return $this->createLink('execution', 'browse', "executionID={$execution->id}");
        }
    }

    /**
     * 任务的数据更新之后，获取对应看板的数据。
     * Get R&D kanban's or task kanban's data after task's data is updated.
     *
     * @param  object    $execution
     * @param  int       $regionID
     * @access protected
     * @return string
     */
    protected function getKanbanData(object $execution, int $regionID = 0): string
    {
        $this->loadModel('kanban');

        $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
        $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
        $rdSearchValue     = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
        $taskSearchValue   = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';

        /* 处理专业研发看板。 Handling professional R&D kanban. */
        if($execution->type == 'kanban')
        {
            $kanbanData = $this->kanban->getRDKanban($execution->id, $executionLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
            return json_encode($kanbanData);
        }

        /* 处理任务看板。 Handling task kanban. */
        $kanbanData = $this->kanban->getExecutionKanban($execution->id, $executionLaneType, $executionGroupBy, $taskSearchValue);
        $kanbanType = $executionLaneType == 'all' ? 'task' : key($kanbanData);
        return json_encode($kanbanData[$kanbanType]);
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
            $projectID = $this->execution->getProjectID($executionID);
            $response['load'] = $this->createLink('projectstory', 'story', "projectID={$projectID}");
        }
        return $response;
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

        if(isonlybody()) return $this->responseModal($task, $from);

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
        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;

        if(!empty($allChanges))
        {
            foreach($allChanges as $taskID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->loadModel('action')->create('task', $taskID, 'Edited');
                $this->action->logHistory($actionID, $changes);

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
            }
        }

        $this->loadModel('score')->create('ajax', 'batchOther');
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
        if(isonlybody()) return $this->responseModal($task, $from);

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('task', 'view', "taskID=$taskID"));
    }

    /**
     * 如果页面是弹窗，则调用此方法得到正确的返回链接。
     * Get response infomation of a modal page.
     *
     * @param  object    $task
     * @param  string    $from     ''|taskkanban
     * @param  int       $regionID
     * @access protected
     * @return array
     */
    protected function responseModal(object $task, string $from, int $regionID = 0): array
    {
        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;

        $execution  = $this->execution->getByID($task->execution);
        $kanbanData = $this->getKanbanData($execution, $regionID);

        $inLiteKanban = $this->config->vision == 'lite' && $this->app->tab == 'project' && $this->session->kanbanview == 'kanban';
        if(($this->app->tab == 'execution' || $inLiteKanban) && $execution->type == 'kanban')
        {
            $response['callback'] = "parent.parent.updateKanban({$kanbanData}, {$regionID})";
            return $response;
        }

        if($from == 'taskkanban')
        {
            $response['callback'] = "parent.parent.updateKanban(\"task\", $kanbanData)";
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

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        $message = $this->executeHooks($task->id);
        if($message) $response['message'] = $message;

        /* Return task id when call the API. */
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $task->id);

        /* Processing the return information of pop-up windows. */
        if(isonlybody())
        {
            /* If it is Kanban execution, refresh the Kanban statically through callback. */
            if($this->app->tab == 'execution' || $this->config->vision == 'lite')
            {
                $kanbanData = $this->getKanbanData($execution);
                $response['closeModal'] = true;
                $response['callback']   = $execution->type == 'kanban' ? "parent.updateKanban({$kanbanData}, 0)" : "parent.updateKanban(\"task\", {$kanbanData})";
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
        if(isonlybody()) return $this->responseModal($task, $from);
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('task', 'view', "taskID={$task->id}"));
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
    protected function removeDuplicate4BatchCreate(int $executionID, array $tasks): array
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
            ->setIF(!$this->post->realStarted and helper::isZeroDate($oldTask->realStarted), 'realStarted', $now)
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->get();

        if(empty($task->currentConsumed)) dao::$errors['currentConsumed'][] = $this->lang->task->error->consumedEmpty;
        if($task->realStarted > $task->finishedDate) dao::$errors['realStarted'][] = $this->lang->task->error->finishedDateSmall;
        return $task;
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
    protected function buildEffortForFinish(object $oldTask, object $task): object
    {
        /* Record consumed and left. */
        if(empty($oldTask->team))
        {
            $task->consumed = $task->consumed - $oldTask->consumed;
        }
        else
        {
            $currentTeam = $this->task->getTeamByAccount($oldTask->team);
            $task->consumed = $currentTeam ? $task->consumed - $currentTeam->consumed : $task->consumed;
        }
        if($task->consumed < 0) dao::$errors[] = $this->lang->task->error->consumedSmall;

        $estimate = new stdclass();
        $estimate->date     = helper::isZeroDate($task->finishedDate) ? helper::today() : substr($task->finishedDate, 0, 10);
        $estimate->task     = $oldTask->id;
        $estimate->left     = 0;
        $estimate->work     = zget($task, 'work', '');
        $estimate->account  = $this->app->user->account;
        $estimate->consumed = $task->consumed;

        return $estimate;
    }
}
