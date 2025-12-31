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
     * @param  string    $cardPosition
     * @access protected
     * @return void
     */
    protected function assignCreateVars(object $execution, int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID, array $output, string $cardPosition)
    {
        /* Get information about the task. */
        $task = $this->setTaskByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID, $output);
        $this->view->showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';

        /* Get module information. */
        $executionID = $execution->id;
        $modulePairs = $this->tree->getTaskOptionMenu($executionID, 0 ,$this->view->showAllModule ? 'allModule' : '');
        if(!isset($modulePairs[$task->module])) $task->module = 0;
        $this->view->task = $task;

        /* Display relevant variables. */
        $this->assignExecutionForCreate($execution, $output);
        $this->assignStoryForCreate($executionID, $moduleID);
        if($execution->type == 'kanban') $this->assignKanbanForCreate($executionID, $output);

        /* Set Custom fields. */
        foreach(explode(',', $this->config->task->list->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $executionTypeLang = zget($this->lang->execution->typeList, $execution->type, '');
        $this->lang->task->noticeLinkStory = sprintf($this->lang->task->noticeLinkStory, $executionTypeLang);

        $parents = $this->task->getParentTaskPairs($executionID);
        $parents = $this->task->addTaskLabel($parents);
        if($execution->multiple)  $manageLink = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "execution={$execution->id}") : '';
        if(!$execution->multiple) $manageLink = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={$execution->project}") : '';

        $this->view->title             = $execution->name . $this->lang->hyphen . $this->lang->task->create;
        $this->view->customFields      = $customFields;
        $this->view->modulePairs       = $modulePairs;
        $this->view->showFields        = $this->config->task->custom->createFields;
        $this->view->gobackLink        = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID={$executionID}") : '';
        $this->view->execution         = $execution;
        $this->view->project           = $this->loadModel('project')->fetchById($execution->project);
        $this->view->storyID           = $storyID;
        $this->view->blockID           = helper::isAjaxRequest('modal') ? $this->loadModel('block')->getSpecifiedBlockID('my', 'assigntome', 'assigntome') : 0;
        $this->view->hideStory         = $this->task->isNoStoryExecution($execution);
        $this->view->from              = $storyID || $todoID || $bugID  ? 'other' : 'task';
        $this->view->taskID            = $taskID;
        $this->view->parents           = $parents;
        $this->view->loadUrl           = $this->createLink('task', 'create', "executionID={execution}&storyID={$storyID}&moduleID={$moduleID}&task={$taskID}&todoID={$todoID}&cardPosition={$cardPosition}&bugID={$bugID}");
        $this->view->assignedToOptions = $this->getAssignedToOptions($manageLink);
        $this->view->manageLink        = $manageLink;

        $this->display();
    }

    /**
     * 设置创建页面展示的执行相关数据。
     * Set the execution-related data for the create page display.
     *
     * @param  object    $execution
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function assignExecutionForCreate(object $execution, array $output): void
    {
        $projectID     = $execution ? $execution->project : 0;
        $lifetimeList  = array();
        $attributeList = array();
        $executions    = $this->executionPairs;

        /* 全局创建，过滤模板执行。*/
        if(!empty($output['from']) && $output['from'] == 'global')
        {
            dao::$filterTpl = 'always';
            $executions     = $this->execution->getByProject(0, 'all', 0, true);
        }
        elseif($projectID)
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
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', $moduleID, 'full', 'active', 'story', false);
        $testStoryIdList = $this->loadModel('story')->getTestStories(array_keys($stories), $executionID);
        $testStories     = array();
        foreach($stories as $testStoryID => $storyTitle)
        {
            if(empty($testStoryID) || isset($testStoryIdList[$testStoryID])) continue;
            $testStories[$testStoryID] = $storyTitle;
        }
        $this->view->testStories     = $testStories;
        $this->view->testStoryIdList = $testStoryIdList;
        $this->view->stories         = $this->story->addGradeLabel($stories);
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
            $execution = $this->execution->getById($executionID);

            $this->view->title     = $execution->name . $this->lang->hyphen . $this->lang->task->batchEdit;
            $this->view->execution = $execution;
            $this->view->project   = $this->loadModel('project')->fetchById($execution->project);
            $this->view->modules   = $this->tree->getTaskOptionMenu($executionID, 0, !empty($this->config->task->allModule) ? 'allModule' : '');
        }
        else
        {
            $this->view->title   = $this->lang->task->batchEdit;
            $this->view->users   = $this->loadModel('user')->getPairs('noletter');
            $this->view->modules = array();
        }

        /* Check if the request data size exceeds the PHP limit. */
        $tasks = $this->task->getByIdList($this->post->taskIdList);
        $parentTaskIdList = array();
        foreach($tasks as $taskID => $task)
        {
            $tasks[$taskID]->consumed = 0;
            $parentTaskIdList[$task->parent] = $task->parent;
        }
        $countInputVars  = count($tasks) * (count(explode(',', $this->config->task->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $this->config->task->batchedit->requiredFields = $this->config->task->edit->requiredFields;

        foreach(explode(',', $this->config->task->list->customBatchEditFields) as $field)
        {
            if(!empty($execution) && $execution->type == 'stage' && strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchEditFields;

        $executionTeams    = array();
        $executionIdList   = array_unique(array_column($tasks, 'execution'));
        $executionTeamList = $this->execution->getMembersByIdList($executionIdList);
        foreach($executionIdList as $id) $executionTeams[$id] = array_column((array)zget($executionTeamList, $id, array()), 'account');

        $moduleGroup = array();
        if(!$executionID)
        {
            foreach($tasks as $task)
            {
                if(isset($moduleGroup[$task->execution])) continue;
                $executionInfo    = $this->execution->fetchByID($task->execution);
                $executionModules = $this->tree->getTaskOptionMenu($task->execution, 0, 'allModule');
                foreach($executionModules as $moduleID => $moduleName) $moduleGroup[$task->execution][] = array('text' => $executionInfo->name . $moduleName, 'value' => $moduleID);
            }
        }

        list($childTasks, $nonStoryChildTasks) = $this->task->getChildTasksByList(array_keys($tasks));
        $childrenDateLimit = array();
        foreach($childTasks as $parent => $children)
        {
            $childDateLimit = array('estStarted' => '', 'deadline' => '');
            foreach($children as $child)
            {
                if(!helper::isZeroDate($child->estStarted) && (empty($childDateLimit['estStarted']) || $childDateLimit['estStarted'] > $child->estStarted)) $childDateLimit['estStarted'] = $child->estStarted;
                if(!helper::isZeroDate($child->deadline)   && (empty($childDateLimit['deadline'])   || $childDateLimit['deadline']   < $child->deadline))   $childDateLimit['deadline']   = $child->deadline;
            }
            $childrenDateLimit[$parent] = $childDateLimit;
        }

        $storyPairs = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', 'full', 'active', 'story', false);;
        $storyList  = $this->story->getByList(array_keys($storyPairs));
        $stories    = array();
        foreach($storyList as $story)
        {
            $stories[0][] = array('value' => $story->id, 'text' => $storyPairs[$story->id]);
            if($story->module) $stories[$story->module][] = array('value' => $story->id, 'text' => $storyPairs[$story->id]);
        }

        $manageLinkList['project']   = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={projectID}") : '';
        $manageLinkList['execution'] = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "executionID={executionID}") : '';

        /* Assign. */
        $this->view->executionID        = $executionID;
        $this->view->tasks              = $tasks;
        $this->view->teams              = $this->task->getTeamMembersByIdList($this->post->taskIdList);
        $this->view->executionTeams     = $executionTeams;
        $this->view->users              = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->moduleGroup        = $moduleGroup;
        $this->view->childTasks         = $childTasks;
        $this->view->nonStoryChildTasks = $nonStoryChildTasks;
        $this->view->childrenDateLimit  = $childrenDateLimit;
        $this->view->stories            = $stories;
        $this->view->parentTasks        = $this->task->getByIdList($parentTaskIdList);
        $this->view->noSprintPairs      = $this->loadModel('project')->getProjectExecutionPairs();
        $this->view->manageLinkList     = $manageLinkList;

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
        $task = $this->view->task;

        /* Get the task parent id,name pairs. */
        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, strVal($task->parent), $taskID);
        $tasks = $this->task->addTaskLabel($tasks);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        /* Prepare to assign to relevant parameters. */
        if(!isset($this->view->members[$task->assignedTo])) $this->view->members[$task->assignedTo] = $task->assignedTo;
        if(isset($this->view->members['closed']) || $task->status == 'closed') $this->view->members['closed'] = 'Closed';

        /* Get the executions of the task. */
        $executions = !empty($task->project) ? $this->execution->getByProject($task->project, 'all', 0, true) : array();

        /* Get task members. */
        $taskMembers = $this->view->members;
        if(!empty($task->team))
        {
            foreach($task->members as $teamAccount)
            {
                if(!isset($this->view->members[$teamAccount])) continue;
                $taskMembers[$teamAccount] = $this->view->members[$teamAccount];
            }
        }

        /* Get execution stories. */
        $moduleID = $task->module;
        if($moduleID)
        {
            $moduleID = $this->loadModel('tree')->getStoryModule($moduleID);
            $moduleID = $this->tree->getAllChildID($moduleID);
        }

        $storyPairs = $this->story->getExecutionStoryPairs($this->view->execution->id, 0, 'all', $moduleID, 'full', 'all', 'story', false);
        $storyList  = $this->story->getByList(array_keys($storyPairs));
        $stories    = array();

        foreach($storyList as $story)
        {
            if($story->status != 'active' && $task->story != $story->id) continue;
            $stories[$story->id] = $story->title;
        }

        $syncChildren   = array();
        $childDateLimit = array('estStarted' => '', 'deadline' => '');
        if(!empty($task->children))
        {
            foreach($task->children as $child)
            {
                if(empty($child->story)) $syncChildren[] = $child->id;
                if(!helper::isZeroDate($child->estStarted) && (empty($childDateLimit['estStarted']) || $childDateLimit['estStarted'] > $child->estStarted)) $childDateLimit['estStarted'] = $child->estStarted;
                if(!helper::isZeroDate($child->deadline)   && (empty($childDateLimit['deadline'])   || $childDateLimit['deadline']   < $child->deadline))   $childDateLimit['deadline']   = $child->deadline;
            }
        }

        if($this->view->execution->multiple)  $manageLink = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "execution={$this->view->execution->id}") : '';
        if(!$this->view->execution->multiple) $manageLink = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={$this->view->execution->project}") : '';

        $this->view->title          = $this->lang->task->edit . 'TASK' . $this->lang->hyphen . $this->view->task->name;
        $this->view->stories        = $this->story->addGradeLabel($stories);
        $this->view->tasks          = $tasks;
        $this->view->taskMembers    = $taskMembers;
        $this->view->users          = $this->loadModel('user')->getPairs('nodeleted|noclosed', "{$task->openedBy},{$task->canceledBy},{$task->closedBy}");
        $this->view->showAllModule  = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->view->modules        = $this->tree->getTaskOptionMenu($task->execution, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions     = $executions;
        $this->view->syncChildren   = $syncChildren;
        $this->view->childDateLimit = $childDateLimit;
        $this->view->parentTask     = !empty($task->parent) ? $this->task->getById($task->parent) : null;
        $this->view->manageLink     = $manageLink;
        $this->view->project        = $task->project ? $this->loadModel('project')->fetchById($task->project) : null;
        $this->display();
    }

    /**
     * 构建指派给表单。
     * Build form for assignTo page.
     *
     * @param  int       $executionID
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function buildUsersAndMembersToForm(int $executionID, int $taskID): void
    {
        $task         = $this->task->getByID($taskID);
        $projectModel = $this->dao->findById($task->project)->from(TABLE_PROJECT)->fetch('model');
        $memberType   = $projectModel == 'research' ? 'project'      : 'execution';
        $objectID     = $projectModel == 'research' ? $task->project : $executionID;
        $members      = $this->loadModel('user')->getTeamMemberPairs($objectID, $memberType, 'nodeleted');

        /* Compute next assignedTo. */
        if(!empty($task->team))
        {
            if(in_array($task->status, $this->config->task->unfinishedStatus)) $task->nextUser = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) || $task->status == 'closed') $members['closed'] = 'Closed';

        if($this->view->execution->multiple)  $manageLink = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "execution={$this->view->execution->id}") : '';
        if(!$this->view->execution->multiple) $manageLink = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={$this->view->execution->project}") : '';

        $this->view->members    = $members;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->manageLink = $manageLink;
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
            $this->view->parentTask   = $task;
        }

        /* 获取模块和需求下拉数据。 Get module and story dropdown data. */
        $showAllModule = !empty($this->config->execution->task->allModule) ? 'allModule' : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, $showAllModule);
        $story         = $this->story->getByID($storyID);
        $stories       = $this->story->getExecutionStoryPairs($execution->id, 0, 'all', $story ? $story->module : 0, 'short', 'active', 'story', false);

        list($customFields, $checkedFields) = $this->getCustomFields($execution, 'batchCreate');
        if(isset($customFields['story'])) $customFields['preview'] = $customFields['copyStory'] = '';

        $showFields = $this->config->task->custom->batchCreateFields;
        if(strpos(",$showFields,", ',story,') !== false)
        {
            $showFields .= ',preview,copyStory';
        }
        else
        {
            $showFields = trim(str_replace(array(',copyStory,', ',preview,'), ',', ",{$showFields},"), ',');
        }

        $this->config->task->batchcreate->requiredFields = $this->config->task->create->requiredFields;

        if($execution->multiple)  $manageLink = common::hasPriv('execution', 'manageMembers') ? $this->createLink('execution', 'manageMembers', "execution={$execution->id}") : '';
        if(!$execution->multiple) $manageLink = common::hasPriv('project', 'manageMembers') ? $this->createLink('project', 'manageMembers', "projectID={$execution->project}") : '';

        $this->view->title         = $this->lang->task->batchCreate;
        $this->view->execution     = $execution;
        $this->view->project       = $this->loadModel('project')->fetchById($execution->project);
        $this->view->modules       = $modules;
        $this->view->parent        = $taskID;
        $this->view->storyID       = $storyID;
        $this->view->story         = $story;
        $this->view->moduleID      = $story ? $story->module : $moduleID;
        $this->view->stories       = array_filter($stories);
        $this->view->storyTasks    = $this->task->getStoryTaskCounts(array_keys($stories), $execution->id);
        $this->view->members       = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        $this->view->taskConsumed  = isset($task) && $task->isParent == 0 ? $task->consumed : 0;
        $this->view->customFields  = $customFields;
        $this->view->checkedFields = $checkedFields;
        $this->view->hideStory     = $this->task->isNoStoryExecution($execution);
        $this->view->showFields    = $showFields;
        $this->view->manageLink    = $manageLink;

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
        $referer = strtolower($_SERVER['HTTP_REFERER']);
        if(strpos($referer, 'recordworkhour') and $this->cookie->taskEffortFold !== false)
        {
            $taskEffortFold = $this->cookie->taskEffortFold;
        }
        else
        {
            $taskEffortFold = 0;
            $currentAccount = $this->app->user->account;
            if($task->assignedTo == $currentAccount) $taskEffortFold = 1;
            if(!empty($task->team))
            {
                $teamMember = array_column($task->team, 'account');
                if(in_array($currentAccount, $teamMember)) $taskEffortFold = 1;
            }
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
        if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($oldTask->execution, (string)$task->estStarted, (string)$task->deadline);
        if(!empty($_POST['lastEditedDate']) && $oldTask->lastEditedDate != $this->post->lastEditedDate) dao::$errors[] = $this->lang->error->editedByOther;
        if(dao::isError()) return false;

        $now  = helper::now();
        $task = form::data($this->config->task->form->edit, $task->id)
            ->add('id', $task->id)
            ->add('lastEditedDate', $now)
            ->setDefault('design', $oldTask->design)
            ->setIF(!$task->assignedTo && !empty($oldTask->team) && !empty($this->post->team), 'assignedTo', $this->task->getAssignedTo4Multi($this->post->team, $oldTask))
            ->setIF($task->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)
            ->setIF($task->mode == 'single', 'mode', '')
            ->setIF(!$oldTask->mode && !$task->assignedTo && !empty($this->post->team), 'assignedTo', zget($this->post->team, 0, ''))
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
            ->get();

        $project = $this->loadModel('project')->fetchById($oldTask->project);
        $parents = $this->getParentEstStartedAndDeadline(array($task->parent));
        $this->checkLegallyDate($task, $project->taskDateLimit == 'limit', isset($parents[$task->parent]) ? $parents[$task->parent] : null);

        $team = $this->post->team ? array_filter($this->post->team) : array();
        if($task->mode && empty($team)) dao::$errors['assignedTo'] = $this->lang->task->teamNotEmpty;
        if(dao::isError()) return false;

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->edit['id'], (string)$this->post->uid);
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
            /* 不是完成，取消状态的多人任务不能指派。*/
            /* Multiple tasks that are not finished or canceled cannot be assigned. */
            if(isset($multipleTasks[$taskID]) && strpos('wait,doing,pause', $task->status) !== false) unset($tasks[$taskID]);

            /* 多人任务不能指派给任务团队外的人。*/
            /* Multiple tasks cannot be assigned to users that are not in the task team. */
            if(isset($multipleTasks[$taskID]) && !isset($multipleTasks[$taskID][$assignedTo])) unset($tasks[$taskID]);

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

        /* Check parent name is not empty when has child task. */
        $levelNames = array();
        foreach($this->post->level as $i => $level)
        {
            $level = (int)$level;
            $levelNames[$level]['name']  = trim($this->post->name[$i]);
            $levelNames[$level]['index'] = $i;

            $preLevel = $level - 1;
            if($level > 0 && !empty($levelNames[$level]['name']) && empty($levelNames[$preLevel]['name'])) dao::$errors["name[" . $levelNames[$preLevel]['index'] . "]"] = $this->lang->task->error->emptyParentName;
        }
        if(dao::isError()) return false;

        if($execution && $this->task->isNoStoryExecution($execution)) unset($this->config->task->form->batchcreate['story']);
        $tasks = form::batchData()->get();
        foreach($tasks as $task)
        {
            $task->project      = $execution->project;
            $task->execution    = $execution->id;
            $task->left         = $task->estimate;
            $task->parent       = $taskID;
            $task->lane         = !empty($task->lane)   ? $task->lane   : zget($output, 'laneID',   0);
            $task->column       = !empty($task->column) ? $task->column : zget($output, 'columnID', 0);
            $task->storyVersion = !empty($task->story)  ? $this->story->getVersion($task->story) : 1;

            if($task->assignedTo) $task->assignedDate = helper::now();
        }

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
        $formConfig = $this->config->task->form->create;
        if($this->post->type == 'affair') $formConfig['assignedTo']['type'] = 'array';
        if($this->post->type == 'test')
        {
            $formConfig['story']['skipRequired'] = true;
            $formConfig['module']['skipRequired'] = true;
            if($this->post->selectTestStory == 'on') $formConfig['estStarted']['skipRequired'] = $formConfig['deadline']['skipRequired'] = $formConfig['estimate']['skipRequired'] = true;
        }

        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        if($execution && $this->task->isNoStoryExecution($execution)) unset($formConfig['story']);

        $team = $this->post->team ? array_filter($this->post->team) : array();
        $task = form::data($formConfig)->setDefault('execution', $executionID)
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
            ->setIF($this->post->type == 'test' && $this->post->selectTestStory == 'on' && !empty($this->post->testStory), 'story', 0)
            ->get();

        /* Processing image link. */
        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], (string)$this->post->uid);

        /* Check if the input post data meets the requirements. */
        $this->checkCreateTask($task, $team);
        return $task;
    }

    /**
     * 构造批量编辑的任务数据。
     * Build the tasks data to batch edit.
     *
     * @param  array     $taskData
     * @param  array     $oldTasks
     * @access protected
     * @return array
     */
    protected function buildTasksForBatchEdit(array $taskData, array $oldTasks): false|array
    {
        $now = helper::now();
        foreach($taskData as $taskID => $task)
        {
            $oldTask = $oldTasks[$taskID];

            $task->parent       = $oldTask->parent;
            $task->assignedTo   = $task->status == 'closed' ? 'closed' : $task->assignedTo;
            $task->assignedDate = !empty($task->assignedTo) && $oldTask->assignedTo != $task->assignedTo ? $now : $oldTask->assignedDate;
            $task->version      = $oldTask->name != $task->name || $oldTask->estStarted != $task->estStarted || $oldTask->deadline != $task->deadline ?  $oldTask->version + 1 : $oldTask->version;
            $task->consumed     = $task->consumed < 0 ? $task->consumed  : $task->consumed + $oldTask->consumed;
            $task->storyVersion = ($task->story && $oldTask->story != $task->story) ? $this->loadModel('story')->getVersion((int)$task->story) : $oldTask->storyVersion;

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
     * @param  int         $taskID
     * @access protected
     * @return object|bool
     */
    protected function buildTaskForActivate(int $taskID): object|bool
    {
        $task = form::data($this->config->task->form->activate, $taskID)->add('id', $taskID)->get();
        if($task->left && $task->left < 0)
        {
            dao::$errors['left'] = sprintf($this->lang->task->error->recordMinus, $this->lang->task->left);
            return false;
        }
        unset($task->comment);

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->activate['id'], (string)$this->post->uid);
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
        $task = form::data($this->config->task->form->start, $oldTask->id)->add('id', $oldTask->id)
            ->setIF($oldTask->assignedTo != $this->app->user->account, 'assignedDate', $now)
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->start['id'], (string)$this->post->uid);
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
        $task = form::data($this->config->task->form->cancel, $oldTask->id)
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

        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->cancel['id'], (string)$this->post->uid);
    }

    /**
     * 构造待创建的测试类型的子任务数据。
     * Build subtask data for the test type to create.
     *
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function buildTestTasksForCreate(int $executionID): array|bool
    {
        /* Set data for the type of test task that has linked stories. */
        $postData = form::data($this->config->task->form->testTask->create)->get();
        if($postData->selectTestStory == 'off') return array();

        $testTasks = array();
        $execution = $this->loadModel('execution')->getByID($executionID);
        $now       = helper::now();
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
            $task->estimate   = !empty($postData->testEstimate[$key])   ? (float)$postData->testEstimate[$key] : 0;
            $task->left       = !empty($postData->testEstimate[$key])   ? (float)$postData->testEstimate[$key] : 0;
            $task->type       = 'test'; /* Setting the task type to test to prevent duplicate tasks from being created. */
            $task->vision     = $this->config->vision;
            $task->project    = $execution->project;
            $task->openedBy   = $this->app->user->account;
            $task->openedDate = $now;

            $testTasks[] = $task;
        }

        $this->checkCreateTestTasks($testTasks);
        if(dao::isError()) return false;

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
        $task = form::data($this->config->task->form->finish, $oldTask->id)
            ->setIF(!$this->post->realStarted && helper::isZeroDate($oldTask->realStarted), 'realStarted', $now)
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->get();

        $task->realStarted = date('Y-m-d H:i', strtotime($task->realStarted));

        if(strpos(",{$this->config->task->finish->requiredFields},", ',comment,') !== false && empty($_POST['comment'])) dao::$errors['comment'] = sprintf($this->lang->error->notempty, $this->lang->comment);
        if(!$this->post->currentConsumed && $oldTask->consumed == '0') dao::$errors['currentConsumed'][] = $this->lang->task->error->consumedEmpty;
        if(!$this->post->finishedDate) dao::$errors['finishedDate'][] = sprintf($this->lang->error->notempty, $this->lang->task->finishedDate);
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
        $consumed = (float)$this->post->currentConsumed;
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
        $task = form::data($this->config->task->form->close, $oldTask->id)->add('id', $oldTask->id)
            ->setIF($oldTask->status == 'done',   'closedReason', 'done')
            ->setIF($oldTask->status == 'cancel', 'closedReason', 'cancel')
            ->get();

        return  $this->loadModel('file')->processImgURL($task, $this->config->task->editor->start['id'], (string)$this->post->uid);
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
    protected function commonAction(int $taskID, string $vision = ''): void
    {
        $this->view->task      = $this->task->getByID($taskID, true, $vision);
        $this->view->execution = $this->execution->getByID($this->view->task->execution);
        $this->view->members   = $this->loadModel('user')->getTeamMemberPairs($this->view->execution->id, 'execution','nodeleted');
        $this->view->actions   = $this->loadModel('action')->getList('task', $taskID);

        /* Set menu. */
        $this->setMenu($this->view->execution->id);
    }

    /**
     * 检查任务的开始时间和截止时间是否合法。
     * Check if the start and end time of the task is legal.
     *
     * @param  object    $task
     * @param  bool      $isDateLimit
     * @param  object    $parent
     * @param  int|null  $rowID
     * @access public
     * @return void
     */
    public function checkLegallyDate(object $task, bool $isDateLimit, ?object $parent, ?int $rowID = null): void
    {
        $beginIndex = $rowID === null ? 'estStarted' : "estStarted[$rowID]";
        $endIndex   = $rowID === null ? 'deadline'   : "deadline[$rowID]";

        $beginIsZeroDate = helper::isZeroDate($task->estStarted);
        $endIsZeroDate   = helper::isZeroDate($task->deadline);
        if(!$beginIsZeroDate and !$endIsZeroDate and $task->deadline < $task->estStarted) dao::$errors[$endIndex] = $this->lang->task->error->deadlineSmall;

        if(!$isDateLimit || empty($parent)) return;
        if(!$beginIsZeroDate && !helper::isZeroDate($parent->estStarted) && $task->estStarted < $parent->estStarted) dao::$errors[$beginIndex] = sprintf($this->lang->task->overParentEsStarted, $parent->estStarted);
        if(!$endIsZeroDate   && !helper::isZeroDate($parent->deadline)   && $task->deadline > $parent->deadline)     dao::$errors[$endIndex]   = sprintf($this->lang->task->overParentDeadline, $parent->deadline);
    }

    /**
     * 检查传入的创建数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  object    $task
     * @param  array     $team
     * @access protected
     * @return bool
     */
    protected function checkCreateTask(object $task, array $team): bool
    {
        /* Check if the estimate is positive. */
        if($task->estimate < 0) dao::$errors['estimate'] = $this->lang->task->error->recordMinus;
        if($this->post->multiple && empty($team)) dao::$errors['assignedTo'] = $this->lang->task->teamNotEmpty;
        if(dao::isError()) return false;

        /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
        if(!empty($this->config->limitTaskDate))
        {
            $this->task->checkEstStartedAndDeadline($task->execution, (string)$task->estStarted, (string)$task->deadline);
            if(dao::isError()) return false;
        }

        $project = $this->dao->findById($task->project)->from(TABLE_PROJECT)->fetch();
        $parents = $this->getParentEstStartedAndDeadline(array($task->parent));
        $this->checkLegallyDate($task, $project->taskDateLimit == 'limit', isset($parents[$task->parent]) ? $parents[$task->parent] : null);

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
        $execution      = $this->loadModel('execution')->fetchById($executionID);
        if($this->task->isNoStoryExecution($execution)) $requiredFields = str_replace(',story,', ',', ',' . $requiredFields . ',');
        $requiredFields = array_filter(explode(',', $requiredFields));

        $levels       = array();
        $project      = $this->loadModel('project')->fetchById($execution->project);
        $parentIdList = array_filter(array_column($tasks, 'parent', 'parent'));
        $parents      = $this->getParentEstStartedAndDeadline($parentIdList);
        foreach($tasks as $rowIndex => $task)
        {
            $levels[$task->level] = $rowIndex;

            if(mb_strlen($task->name) > 255) dao::$errors["name[$rowIndex]"] = sprintf($this->lang->task->error->length, 255);
            if(!empty($this->post->estimate[$rowIndex]) and !preg_match("/^[0-9]+(.[0-9]+)?$/", (string)$this->post->estimate[$rowIndex]))
            {
                dao::$errors["estimate[$rowIndex]"] = $this->lang->task->error->estimateNumber;
            }

            /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
            if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($executionID, (string)$task->estStarted, (string)$task->deadline);

            /* Check start and end date. */
            if(!helper::isZeroDate($task->deadline) && $task->deadline < $task->estStarted)
            {
                dao::$errors["deadline[$rowIndex]"] = $this->lang->task->error->deadlineSmall;
            }

            $parentTask = isset($parents[$task->parent]) ? $parents[$task->parent] : null;
            if($task->level > 0 && isset($levels[$task->level - 1])) $parentTask = zget($tasks, $levels[$task->level - 1], null);
            $this->checkLegallyDate($task, $project->taskDateLimit == 'limit', $parentTask, $rowIndex);

            /* Check if the estimate is positive. */
            if($task->estimate < 0) dao::$errors["estimate[$rowIndex]"] = $this->lang->task->error->recordMinus;

            /* Check if the required fields are empty. */
            foreach($requiredFields as $field)
            {
                if($field == 'estimate' && $this->post->isParent[$rowIndex] == '1') continue;

                if(empty($task->$field)) dao::$errors[$field . "[$rowIndex]"] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
            }
        }

        return !dao::isError();
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
        $oldTask      = reset($oldTasks);
        $project      = $this->loadModel('project')->fetchById($oldTask->project);
        $parentIdList = array_filter(array_column($tasks, 'parent', 'parent'));
        $parents      = $this->getParentEstStartedAndDeadline($parentIdList);
        foreach($tasks as $taskID => $task)
        {
            $oldTask = $oldTasks[$taskID];

            /* Check work hours. */
            if(in_array($task->status, array('doing', 'pause')) && empty($oldTask->mode) && empty($task->left) && !$oldTask->isParent)
            {
                dao::$errors["left[{$taskID}]"] = (array)sprintf($this->lang->task->error->leftEmptyAB, zget($this->lang->task->statusList, $task->status));
            }
            if($task->estimate < 0)  dao::$errors["estimate[$taskID]"]   = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->estimateAB);
            if($task->consumed < 0 ) dao::$errors["consumed[{$taskID}]"] = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->consumedThisTime);
            if($task->left < 0)      dao::$errors["left[$taskID]"]       = (array)sprintf($this->lang->task->error->recordMinus, $this->lang->task->leftAB);

            if(!empty($this->config->limitTaskDate)) $this->task->checkEstStartedAndDeadline($oldTask->execution, (string)$task->estStarted, (string)$task->deadline, $taskID);

            if($task->status == 'cancel') continue;
            if($task->status == 'done' && !$task->consumed) dao::$errors["consumed[{$taskID}]"] = (array)sprintf($this->lang->error->notempty, $this->lang->task->consumedThisTime);

            $parentTask = isset($parents[$task->parent]) ? $parents[$task->parent] : null;
            if(isset($tasks[$task->parent])) $parentTask = zget($tasks, $task->parent, null);
            $this->checkLegallyDate($task, $project->taskDateLimit == 'limit', $parentTask, $taskID);
        }
        return !dao::isError();
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
        if(empty($tasks))
        {
            dao::$errors[] = $this->lang->task->error->noTestTask;
            return false;
        }

        foreach($tasks as $rowID => $task)
        {
            $index = $rowID + 1;
            /* Check if the estimate is positive. */
            if($task->estimate < 0)
            {
                dao::$errors["testEstimate[{$index}]"] = sprintf($this->lang->task->error->recordMinus, $this->lang->task->estimate);
                return false;

            }

            /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($task->execution, (string)$task->estStarted, (string)$task->deadline);
                if(dao::isError())
                {
                    $error = current(dao::getError());
                    dao::$errors["testDeadline[{$index}]"] = $error;
                    return false;
                }
            }

            /* Check start and end date. */
            if($task->estStarted > $task->deadline)
            {
                dao::$errors["testDeadline[{$index}]"] = $this->lang->task->error->deadlineSmall;
                return false;
            }

            /* Checking the required fields of task data. */
            $this->dao->insert(TABLE_TASK)->data($task)->batchCheck($this->config->task->create->requiredFields, 'notempty');
            if(dao::isError())
            {
                $errors = dao::getError();
                foreach($errors as $field => $error)
                {
                    $fieldName = 'test' . ucfirst($field);
                    dao::$errors["{$fieldName}[{$index}]"] = $error;
                }
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
        if(!$task->left && !$task->consumed) dao::$errors['message'] = $this->lang->task->noticeTaskStart;
        if($task->left && $task->left < 0) dao::$errors['left'] = sprintf($this->lang->task->error->recordMinus, $this->lang->task->left);
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
        $task->rawEstimate    = $task->estimate;
        $task->rawConsumed    = $task->consumed;
        $task->rawLeft        = $task->left;
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
        foreach(explode(',', $this->config->task->list->{$customFormField}) as $field)
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
     * @access public
     * @return array
     */
    public function generalCreateResponse(object $task, int $executionID, string $afterChoose): array
    {
        /* Set the universal return value. */
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

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
        $back = $this->config->vision == 'lite' ? 'projectstory-story' : 'execution-story';
        $response['callback'] = "openUrl(" . json_encode(array('back' => $back)) . ")";
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
        if($this->app->user->admin) return false;
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
        $users         = $this->loadModel('user')->getPairs('noletter');
        $projects      = $this->loadModel('project')->getPairs();
        $executions    = $this->loadModel('execution')->fetchPairs(0, 'all', true, true);
        $allExecutions = $this->loadModel('execution')->fetchPairs(0, 'all', false, true);

        /* Get related objects id lists. */
        $relatedStoryIdList = array();
        $relatedBugIdList   = array();
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

            if(isset($relatedModules[$task->module])) $task->module = $relatedModules[$task->module] . "(#$task->module)";
            if(isset($allExecutions[$task->execution]) && !isset($executions[$task->execution])) $task->execution = '';

            /* Convert username to real name. */
            if(!empty($task->mailto))
            {
                $mailtoList = explode(',', $task->mailto);

                $task->mailto = '';
                foreach($mailtoList as $mailto)
                {
                    if(!empty($mailto)) $task->mailto .= ',' . zget($users, $mailto);
                }

                $task->mailto = trim($task->mailto, ',');
            }

            /* Compute task progress. */
            if(!isset($task->progress))
            {
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

            if(!empty($task->team))
            {
                $task->name = '[' . $this->lang->task->multipleAB . '] ' . $task->name;
                unset($task->team);
            }

            if($task->isParent && strpos($task->name, "[{$this->lang->task->parentAB}]") === false)
            {
                $task->name = '[' . $this->lang->task->parentAB . '] ' . $task->name;
            }
            elseif($task->parent > 0 && strpos($task->name, "[{$this->lang->task->childrenAB}]") === false)
            {
                $task->name = '[' . $this->lang->task->childrenAB . '] ' . $task->name;
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
            if(isset($task->closedReason) && $task->closedReason == 'cancel' && isset($task->finishedDate) && helper::isZeroDate($task->finishedDate)) $task->finishedDate = null;
            break;
        case 'wait':
            if($task->consumed > 0 and $task->left > 0) $task->status = 'doing';
            if($task->consumed > 0 and $task->left == 0) // 消耗了工时，且预计剩余为0，则更新为已完成
            {
                $task->status       = 'done';
                $task->finishedBy   = $currentAccount;
                $task->finishedDate = helper::now();
            }
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
                    $response['callback'] = "confirmBug('" . sprintf($this->lang->task->remindBug, $task->fromBug) . "', {$task->id}, {$task->fromBug})";
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
                        $response['callback'] = "confirmBug('" . sprintf($this->lang->task->remindBug, $task->fromBug) . "', {$task->id}, {$task->fromBug})";
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
        $response['closeModal'] = $this->app->rawMethod != 'recordworkhour';

        if($this->app->rawMethod == 'recordworkhour')
        {
            $response['callback'] = "loadModal('" . inLink('recordworkhour', "taskID={$task->id}") . "', '#modal-record-hours-task-{$task->id}')";
            return $response;
        }

        $execution    = $this->loadModel('execution')->getByID((int)$task->execution);
        $inLiteKanban = $this->config->vision == 'lite' && $this->app->tab == 'project' && $this->session->kanbanview == 'kanban';
        if((($this->app->tab == 'execution' || $inLiteKanban) && $execution->type == 'kanban') || $from == 'taskkanban')
        {
            $response['callback'] = 'refreshKanban()';
            return $response;
        }

        $response['load'] = $from != 'edittask';
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
        if(helper::isAjaxRequest('modal') || isonlybody())
        {
            /* If it is Kanban execution, refresh the Kanban statically through callback. */
            if($this->app->tab == 'execution' && $execution->type == 'kanban' || $this->config->vision == 'lite')
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

        if($this->config->vision == 'lite') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('execution', 'task', "executionID={$execution->id}"));

        /* If it is Kanban execution, locate the kanban page. */
        if($afterChoose != 'continueAdding' && $execution->type == 'kanban') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('execution', 'kanban', "executionID={$execution->id}"));

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
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $taskIdList);

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        if(isInModal())
        {
            $response['closeModal'] = true;
            $response['callback']   = 'loadCurrentPage()';
            if(($execution->multiple && $this->app->tab == 'execution') || (!$execution->multiple && $this->app->tab == 'project') || $this->config->vision == 'lite') $response['callback'] = "refreshKanban()";
            return $response;
        }

        $link = $this->createLink('execution', 'task', "executionID={$execution->id}");
        if($this->app->tab == 'my') $link = $this->createLink('my', 'work', 'mode=task');

        if($this->app->tab == 'project' && $execution->multiple && $this->config->vision != 'lite') $link = $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        if($this->app->tab == 'project' && $execution->multiple && $this->config->vision == 'lite') $link = $this->createLink('execution', 'task', "kanbanID={$execution->id}");

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
            $response = $this->task->getRemindBugLink($task, $changes);
            if($response) return $this->send($response);
        }

        if(helper::isAjaxRequest('modal')) return $this->responseModal($task, $from);

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;
        $response['load']    = $this->createLink('execution', 'browse', "executionID={$task->execution}&tab=task");
        return $response;
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
     * @param  array     $output feedback扩展使用
     * @access public
     * @return object
     */
    public function setTaskByObjectID(int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID, array $output = array()): object
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

            foreach($task->files as $file)
            {
                $file->name = $file->title;
                $file->url  = $this->createLink('file', 'download', "fileID={$file->id}");
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
            $task->story      = $bug->story;
            $task->pri        = !empty($bug->pri) ? $bug->pri : $this->config->task->default->pri;
            $task->assignedTo = array($bug->assignedTo);
        }

        /* If exist story, copy story module by story id. */
        if($storyID)
        {
            $task->story  = $storyID;
            $task->module = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch('module');
        }
        else
        {
            $task->module = $task->module ? $task->module : (int)$this->cookie->lastTaskModule;
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
        if(!$execution || (!empty($execution) && $execution->multiple))
        {
            /* If the admin denied modification of closed executions, only query not closed executions. */
            $queryMode = ($this->app->methodName == 'view' || ($execution && common::canModify('execution', $execution))) ? '' : 'noclosed';

            /* Get executions the current user can access. */
            $this->executionPairs = $this->execution->getPairs(0, 'all', $queryMode);

            /* Call checkAccess method to judge the user can access the execution or not, if not return the first one he can access. */
            $executionID = $this->execution->checkAccess($executionID, $this->executionPairs);
        }

        /* Set Menu. */
        if($this->app->tab == 'project' || (!empty($execution) && !$execution->multiple))
        {
            $this->project->setMenu(isset($execution) ? (int)$execution->project : $this->session->project);
            $this->view->projectID = $execution->project;
        }
        else
        {
            $this->execution->setMenu($executionID);
        }

        return $executionID;
    }

    /**
     * 检查是否有gitlab, gitea, gogs, gitfox 代码库。
     * Check gitlab, gitea, gogs, gitfox repo for execution.
     *
     * @param  int       $executionID
     * @access protected
     * @return bool
     */
    protected function checkGitRepo(int $executionID): bool
    {
        $this->loadModel('repo');
        $repoList   = $this->repo->getListBySCM(implode(',', $this->config->repo->gitServiceTypeList), 'haspriv');
        $productIds = $this->loadModel('product')->getProductIDByProject($executionID, false);
        foreach($repoList as $repo)
        {
            $linkedProducts = explode(',', $repo->product);
            foreach($productIds as $productID)
            {
                if(in_array($productID, $linkedProducts)) return true;
            }
        }

        return false;
    }

    /**
     * 获取指派给配置。
     * Get assigned to options.
     *
     * @param  string    $manageLink
     * @access protected
     * @return array
     */
    protected function getAssignedToOptions(string $manageLink): array
    {
        $options = array();
        $options['single']['multiple'] = false;
        $options['single']['checkbox'] = false;
        $options['single']['toolbar']  = false;

        $options['multiple']['multiple']  = true;
        $options['multiple']['checkbox']  = true;
        $options['multiple']['toolbar'][] = array('key' => 'selectAll', 'text' => $this->lang->selectAll);
        $options['multiple']['toolbar'][] = array('key' => 'cancelSelect', 'text' => $this->lang->cancelSelect);

        if($manageLink)
        {
            $options['single']['toolbar'] = array();
            $options['single']['toolbar'][] = $options['multiple']['toolbar'][] = array('className' => 'text-primary manageTeamBtn', 'key' => 'manageTeam', 'text' => $this->lang->execution->manageTeamMember, 'icon' => 'plus-solid-circle', 'url' => $manageLink, 'data-toggle' => 'modal', 'data-size' => 'lg', 'data-dismiss' => 'pick');
        }

        return $options;
    }

    /**
     * 获取父任务的开始时间和截止时间。
     * Get the start and end time of the parent task.
     *
     * @param  array     $parentIdList
     * @access protected
     * @return array
     */
    protected function getParentEstStartedAndDeadline(array $parentIdList): array
    {
        $pathPairs = $this->dao->select('id,path')->from(TABLE_TASK)->where('id')->in($parentIdList)->fetchPairs();
        if(empty($pathPairs)) return array();

        $allParentIdList = array_filter(array_unique(explode(',', implode(',', $pathPairs))));
        $allParents      = $this->dao->select('id,estStarted,deadline')->from(TABLE_TASK)->where('id')->in($allParentIdList)->fetchAll('id');
        $parents         = array();
        foreach($pathPairs as $parentID => $path)
        {
            $parent = new stdClass();
            $parent->estStarted = null;
            $parent->deadline   = null;
            foreach(array_reverse(array_filter(explode(',', $path))) as $taskID)
            {
                if(!isset($allParents[$taskID])) continue;

                $task = $allParents[$taskID];
                if(empty($parent->estStarted)  && !helper::isZeroDate($task->estStarted)) $parent->estStarted = $task->estStarted;
                if(empty($parent->deadline)    && !helper::isZeroDate($task->deadline))   $parent->deadline   = $task->deadline;
                if(!empty($parent->estStarted) && !empty($parent->deadline)) break;
            }
            $parents[$parentID] = $parent;
        }
        return $parents;
    }

    /**
     * 处理过滤条件显示内容。
     * Process filter title.
     *
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return string
     */
    public function processFilterTitle(string $browseType, int $param): string
    {
        if($browseType != 'bysearch' && $browseType != 'bymodule' && $browseType != 'byproduct')
        {
            $statusName = zget($this->lang->execution->featureBar['task'], $browseType, '');
            if(empty($statusName)) $statusName = zget($this->lang->execution->statusSelects, $browseType);
            return sprintf($this->lang->task->report->tpl->feature, $statusName);
        }

        if($browseType == 'byproduct' && $param)
        {
            $productName = $this->loadModel('product')->getById($param)->name;
            return sprintf($this->lang->task->report->tpl->feature, $productName);
        }

        $searchConfig = $this->loadModel('search')->processSearchParams('task');
        $fieldParams  = $searchConfig['params'];
        if($browseType == 'bymodule') return sprintf($this->lang->task->report->tpl->search, $this->config->execution->search['fields']['module'], '=', zget($fieldParams['module']['values'], $param));

        $leftConditions  = array();
        $rightConditions = array();
        $searchFields    = $this->session->taskForm;
        $fieldNames      = $searchConfig['fields'];
        if(!$searchFields) return sprintf($this->lang->task->report->tpl->feature, $this->lang->all);

        $this->app->loadLang('search');
        $groupAndOr = 'and';
        $users      = $this->loadModel('user')->getPairs('noletter');
        foreach($searchFields as $index => $field)
        {
            if($index == 6) $groupAndOr = $field['groupAndOr'];
            if(!isset($field['field'])) continue;

            if(isset($field['value']) && $field['value'] === '') continue;
            if(!empty($fieldParams[$field['field']]['values']))
            {
                if($fieldParams[$field['field']]['values'] == 'users') $fieldParams[$field['field']]['values'] = $users;
                $field['value'] = zget($fieldParams[$field['field']]['values'], $field['value']);
            }

            $fieldName = zget($fieldNames, $field['field']);
            $operator  = zget($this->lang->search->operators, $field['operator']);

            if($index == 0 || $index == 3) $field['andOr'] = '';
            if($index < 3)     $leftConditions[]  = zget($this->lang->search->andor, $field['andOr']) . sprintf($this->lang->task->report->tpl->search, $fieldName, $operator, $field['value']);
            elseif($index < 6) $rightConditions[] = zget($this->lang->search->andor, $field['andOr']) . sprintf($this->lang->task->report->tpl->search, $fieldName, $operator, $field['value']);
        }

        if(empty($leftConditions) && empty($rightConditions)) return sprintf($this->lang->task->report->tpl->feature, $this->lang->all);

        if(empty($leftConditions))  return implode('', $rightConditions);
        if(empty($rightConditions)) return implode('', $leftConditions);

        return sprintf($this->lang->task->report->tpl->multi, implode('', $leftConditions), zget($this->lang->search->andor, $groupAndOr), implode('', $rightConditions));
    }

    /**
     * 获取报表任务列表。
     * Get report task list.
     *
     * @param  object $execution
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return array
     */
    public function getReportTaskList(object $execution, string $browseType = '', int $param = 0): array
    {
        if(!$execution->multiple) unset($this->lang->task->report->charts['tasksPerExecution']);

        $this->loadModel('execution')->setMenu($execution->id);
        if($this->app->tab == 'project') $this->view->projectID = $execution->project;

        $mode       = $this->app->tab == 'execution' ? 'multiple' : '';
        $executions = $this->execution->getPairs(0, 'all', "nocode,noprefix,{$mode}");

        /* Set queryID, moduleID and productID. */
        $queryID = $moduleID = $productID = 0;
        if($browseType == 'bysearch')  $queryID   = (int)$param;
        if($browseType == 'bymodule')  $moduleID  = (int)$param;
        if($browseType == 'byproduct') $productID = (int)$param;

        return $this->execution->getTasks((int)$productID, $execution->id, $executions, $browseType, $queryID, (int)$moduleID, 'id_desc');
    }
}
