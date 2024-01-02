<?php
/**
 * The control file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class action extends control
{
    /**
     * Create a action or delete all patch actions, this method is used by the Ztools.
     *
     * @param  string $objectType
     * @param  string $actionType
     * @param  string $objectName
     * @access public
     * @return void
     */
    public function create($objectType, $actionType, $objectName)
    {
        $actionID = $this->action->create($objectType, 0, $actionType, '', $objectName);

        if($actionID)
        {
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }
        else
        {
            $this->send(array('result' => 'fail', 'message' => 'error'));
        }
    }


    /**
     * Trash.
     *
     * @param  string $browseType
     * @param  string $type all|hidden
     * @param  bool   $byQuery
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function trash($browseType = 'all', $type = 'all', $byQuery = false, $queryID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('backup');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',        $uri, 'product');
        $this->session->set('productPlanList',    $uri, 'product');
        $this->session->set('storyList',          $uri, 'product');
        $this->session->set('releaseList',        $uri, 'product');
        $this->session->set('programList',        $uri, 'program');
        $this->session->set('projectList',        $uri, 'project');
        $this->session->set('executionList',      $uri, 'execution');
        $this->session->set('taskList',           $uri, 'execution');
        $this->session->set('buildList',          $uri, 'execution');
        $this->session->set('bugList',            $uri, 'qa');
        $this->session->set('caseList',           $uri, 'qa');
        $this->session->set('testtaskList',       $uri, 'qa');
        $this->session->set('docList',            $uri, 'doc');
        $this->session->set('opportunityList',    $uri, 'project');
        $this->session->set('riskList',           $uri, 'project');
        $this->session->set('trainplanList',      $uri, 'project');
        $this->session->set('roomList',           $uri, 'admin');
        $this->session->set('researchplanList',   $uri, 'project');
        $this->session->set('researchreportList', $uri, 'project');
        $this->session->set('meetingList',        $uri, 'project');
        $this->session->set('designList',         $uri, 'project');
        $this->session->set('storyLibList',       $uri, 'assetlib');
        $this->session->set('issueLibList',       $uri, 'assetlib');
        $this->session->set('riskLibList',        $uri, 'assetlib');
        $this->session->set('opportunityLibList', $uri, 'assetlib');
        $this->session->set('practiceLibList',    $uri, 'assetlib');
        $this->session->set('componentLibList',   $uri, 'assetlib');

        /* Save the object name used to replace the search language item. */
        $this->session->set('objectName', zget($this->lang->action->objectTypes, $browseType, ''), 'admin');

        /* Build the search form. */
        $queryID   = (int)$queryID;
        $actionURL = $this->createLink('action', 'trash', "browseType=$browseType&type=$type&byQuery=true&queryID=myQueryID");
        $this->action->buildTrashSearchForm($queryID, $actionURL);

        /* Get deleted objects. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort           = common::appendOrder($orderBy);
        $trashes        = $byQuery ? $this->action->getTrashesBySearch($browseType, $type, $queryID, $sort, $pager) : $this->action->getTrashes($browseType, $type, $sort, $pager);
        $objectTypeList = $this->action->getTrashObjectTypes($type);
        $objectTypeList = array_keys($objectTypeList);

        $preferredType       = array();
        $moreType            = array();
        $preferredTypeConfig = $this->config->action->preferredType->ALM;
        if($this->config->systemMode == 'light') $preferredTypeConfig = $this->config->action->preferredType->light;
        foreach($objectTypeList as $objectType)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;
            in_array($objectType, $preferredTypeConfig) ? $preferredType[$objectType] = $objectType : $moreType[$objectType] = $objectType;
        }
        if(count($preferredType) < $this->config->action->preferredTypeNum)
        {
            $toPreferredType = array_splice($moreType, 0, $this->config->action->preferredTypeNum - count($preferredType));
            $preferredType   = $preferredType + $toPreferredType;
        }

        /* Get the projects name of executions. */
        if($browseType == 'execution')
        {
            $this->loadModel('project');
            $projectIdList = array();
            foreach($trashes as $trash) $projectIdList[] = $trash->project;
            $this->view->projectList = $this->project->getByIdList($projectIdList, 'all');
        }

        /* Get the products name of story. */
        if(strpos(',story,requirement,', ",$browseType,") !== false)
        {
            $this->loadModel('story');
            $storyIdList = array();
            foreach($trashes as $trash) $storyIdList[] = $trash->objectID;
            $this->view->productList = $this->story->getByList($storyIdList, 'story', 'all');
        }

        /* Get the executions name of task. */
        if($browseType == 'task')
        {
            $this->app->loadLang('task');
            $this->loadModel('execution');
            $executionIdList = array();
            foreach($trashes as $trash) $executionIdList[] = $trash->execution;
            $this->view->executionList = $this->execution->getByIdList($executionIdList, 'all');
        }

        /* Process pivot name. */
        foreach($trashes as $trash)
        {
            if($trash->objectType == 'pivot')
            {
                $pivotNames = json_decode($trash->objectName, true);
                $trash->objectName = zget($pivotNames, $this->app->getClientLang(), '');
                if(empty($trash->objectName))
                {
                    $pivotNames = array_filter($pivotNames);
                    $trash->objectName = reset($pivotNames);
                }
            }
        }

        /* Title and position. */
        $this->view->title      = $this->lang->action->trash;
        $this->view->position[] = $this->lang->action->trash;

        $this->view->trashes             = $trashes;
        $this->view->type                = $type;
        $this->view->currentObjectType   = $browseType;
        $this->view->orderBy             = $orderBy;
        $this->view->pager               = $pager;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter');
        $this->view->preferredType       = $preferredType;
        $this->view->moreType            = $moreType;
        $this->view->preferredTypeConfig = $preferredTypeConfig;
        $this->view->byQuery             = $byQuery;
        $this->view->queryID             = $queryID;

        $this->display();
    }

    /**
     * Undelete an object.
     *
     * @param  int    $actionID
     * @param  string $browseType
     * @param  string $confirmChange
     * @access public
     * @return void
     */
    public function undelete($actionID, $browseType = 'all', $confirmChange = 'no')
    {
        $oldAction = $this->action->getById($actionID);
        $extra     = $oldAction->extra == ACTIONMODEL::BE_HIDDEN ? 'hidden' : 'all';

        if(in_array($oldAction->objectType, array('program', 'project', 'execution', 'product')))
        {
            if($oldAction->objectType == 'product')
            {
                $product      = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($oldAction->objectID)->fetch();
                $programID    = isset($product->program) ? $product->program : 0;
                $repeatObject = $this->dao->select('*')->from(TABLE_PRODUCT)
                    ->where('id')->ne($oldAction->objectID)
                    ->andWhere("(name = '{$product->name}' and program = {$programID})", true)
                    ->beginIF($product->code)->orWhere("code = '{$product->code}'")->fi()
                    ->markRight(1)
                    ->andWhere('deleted')->eq('0')
                    ->fetch();
            }
            else
            {
                $project       = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($oldAction->objectID)->fetch();
                $sprintProject = isset($project->project) ? $project->project : '0';
                $repeatObject  = $this->dao->select('*')->from(TABLE_PROJECT)
                    ->where('id')->ne($oldAction->objectID)
                    ->beginIF($oldAction->objectType == 'program' or $oldAction->objectType == 'project')->andWhere("(name = '{$project->name}' and parent = {$project->parent})", true)->fi()
                    ->beginIF($oldAction->objectType == 'execution')->andWhere("(name = '{$project->name}' and project = {$sprintProject})", true)->fi()
                    ->beginIF($oldAction->objectType == 'project' and $project->code)->orWhere("(code = '{$project->code}' and model = '$project->model')")->fi()
                    ->beginIF($oldAction->objectType == 'execution' and $project->code)->orWhere("code = '{$project->code}'")->fi()
                    ->markRight(1)
                    ->beginIF($oldAction->objectType == 'program')->andWhere('type')->eq('program')->fi()
                    ->beginIF($oldAction->objectType == 'project')->andWhere('type')->eq('project')->fi()
                    ->beginIF($oldAction->objectType == 'execution')->andWhere('type')->in('sprint,stage,kanban')->fi()
                    ->andWhere('deleted')->eq('0')
                    ->fetch();
            }

            if($repeatObject)
            {
                $table  = $oldAction->objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
                $object = $oldAction->objectType == 'product' ? $product : $project;

                $existNames = $this->dao->select('name')->from($table)->where('name')->like($repeatObject->name . '_%')->fetchPairs();
                for($i = 1; $i < 10000; $i ++)
                {
                    $replaceName = $repeatObject->name . '_' . $i;
                    if(!in_array($replaceName, $existNames)) break;
                }
                $replaceCode = '';
                if($object->code)
                {
                    $existCodes = $this->dao->select('code')->from($table)->where('code')->like($repeatObject->code . '_%')->fetchPairs();
                    for($i = 1; $i < 10000; $i ++)
                    {
                        $replaceCode = $repeatObject->code . '_' . $i;
                        if(!in_array($replaceCode, $existCodes)) break;
                    }
                }

                if($repeatObject->name == $object->name and $repeatObject->code and $repeatObject->code == $object->code)
                {
                    if($confirmChange == 'no') return print(js::confirm(sprintf($this->lang->action->repeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName, $replaceCode), $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes")));
                    if($confirmChange == 'yes') $this->dao->update($table)->set('name')->eq($replaceName)->set('code')->eq($replaceCode)->where('id')->eq($oldAction->objectID)->exec();
                }
                elseif($repeatObject->name == $object->name)
                {
                    if($confirmChange == 'no') return print(js::confirm(sprintf($this->lang->action->nameRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName), $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes")));
                    if($confirmChange == 'yes') $this->dao->update($table)->set('name')->eq($replaceName)->where('id')->eq($oldAction->objectID)->exec();
                }
                elseif($repeatObject->code and $repeatObject->code == $object->code)
                {
                    if($confirmChange == 'no') return print(js::confirm(sprintf($this->lang->action->codeRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceCode), $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes")));
                    if($confirmChange == 'yes') $this->dao->update($table)->set('code')->eq($replaceCode)->where('id')->eq($oldAction->objectID)->exec();
                }
            }

            if($oldAction->objectType == 'execution')
            {
                $confirmLang = $this->restoreStages($oldAction, $browseType, $confirmChange);
                if($confirmLang !== true) return print(js::confirm($confirmLang, $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes")));
            }
        }

        $this->action->undelete($actionID);

        $sameTypeObjects = $this->action->getTrashes($oldAction->objectType, $extra, 'id_desc', null);
        $browseType      = ($sameTypeObjects and $browseType != 'all') ? $oldAction->objectType : 'all';

        return print(js::locate($this->createLink('action', 'trash', "browseType=$browseType&type=$extra"), 'parent'));
    }

    /**
     * Hide an deleted object.
     *
     * @param  int    $actionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function hideOne($actionID, $browseType = 'all')
    {
        $oldAction = $this->action->getById($actionID);

        $this->action->hideOne($actionID);

        $sameTypeObjects = $this->action->getTrashes($oldAction->objectType, 'all', 'id_desc', null);
        $browseType      = ($sameTypeObjects and $browseType != 'all') ? $oldAction->objectType : 'all';

        return print(js::locate($this->createLink('action', 'trash', "browseType=$browseType"), 'parent'));
    }

    /**
     * Hide all deleted objects.
     *
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function hideAll($confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->action->confirmHideAll, inlink('hideAll', "confirm=yes"));
        }
        else
        {
            $this->action->hideAll();
            echo js::reload('parent');
        }
    }

    /**
     * Comment.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function comment($objectType, $objectID)
    {
        if(!empty($_POST))
        {
            $isInZinPage = isInModal() || in_array($objectType, $this->config->action->newPageModule);

            if(strtolower($objectType) == 'task')
            {
                $task       = $this->loadModel('task')->getById($objectID);
                $executions = explode(',', $this->app->user->view->sprints);
                if(!in_array($task->execution, $executions))
                {
                    if($isInZinPage) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
                    return print(js::error($this->lang->error->accessDenied));
                }
            }
            elseif(strtolower($objectType) == 'story')
            {
                $story      = $this->loadModel('story')->getById($objectID);
                $executions = explode(',', $this->app->user->view->sprints);
                $products   = explode(',', $this->app->user->view->products);
                if(!array_intersect(array_keys($story->executions), $executions) and !in_array($story->product, $products) and empty($story->lib))
                {
                    if($isInZinPage) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
                    return print(js::error($this->lang->error->accessDenied));
                }
            }

            $comment = isset($this->post->actioncomment) ? $this->post->actioncomment : $this->post->comment;
            if($comment)
            {
                $actionID = $this->action->create($objectType, $objectID, 'Commented', $comment);
                if(empty($actionID))
                {
                    if($isInZinPage) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
                    return print(js::error($this->lang->error->accessDenied));
                }
                if(defined('RUN_MODE') && RUN_MODE == 'api')
                {
                    return $this->send(array('status' => 'success', 'data' => $actionID));
                }
            }

            if($isInZinPage)
            {
                return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $objectType, 'objectID' => (int)$objectID))));
            }
            echo js::reload('parent');
        }

        $this->view->title      = $this->lang->action->create;
        $this->view->objectType = $objectType;
        $this->view->objectID   = $objectID;
        $this->display();
    }

    /**
     * Edit comment of a action.
     *
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function editComment($actionID)
    {
        if(!empty($_POST))
        {
            if(strlen(trim(strip_tags($this->post->lastComment, '<img>'))) != 0)
            {
                $this->action->updateComment($actionID);
            }
            else
            {
                dao::$errors['submit'][] = $this->lang->action->historyEdit;
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $action = $this->action->getById($actionID);
            if(isInModal() || in_array($action->objectType, $this->config->action->newPageModule))
            {
                return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $action->objectType, 'objectID' => (int)$action->objectID))));
            }
            return $this->send(array('result' => 'success', 'locate' => 'reload', 'load' => true, 'closeModal' => true));
        }

        $action = $this->action->getById($actionID);

        $this->view->title      = $this->lang->action->editComment;
        $this->view->actionID   = $actionID;
        $this->view->comment    = $this->action->formatActionComment($action->comment);
        $this->display();
    }

    /**
     * Restore stages.
     *
     * @param  object $action
     * @param  string $browseType
     * @param  string $confirmChange
     * @access public
     * @return bool|string
     */
    public function restoreStages($action, $browseType, $confirmChange)
    {
        /* Check parent stage isCreateTask. */
        $execution      = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($action->objectID)->fetch();
        $hasCreatedTask = $this->loadModel('programplan')->isCreateTask($execution->parent);
        if(!$hasCreatedTask) die(js::alert($this->lang->action->hasCreatedTask));

        /* Check type of siblings. */
        $siblings = $this->dao->select('DISTINCT type')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('parent')->eq($execution->parent)->fetchPairs('type');
        if($execution->type == 'stage' and (isset($siblings['sprint']) or isset($siblings['kanban']))) die(js::alert($this->lang->action->hasOtherType[$execution->type]));
        if(($execution->type == 'sprint' or $execution->type == 'kanban') and isset($siblings['stage'])) die(js::alert($this->lang->action->hasOtherType[$execution->type]));

        /* If parent stage is not exists, you should recover its parent stages, refresh status. */
        $stagePathList    = explode(',', trim($execution->path, ','));
        $deletedStageList = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stagePathList)->andWhere('deleted')->eq(1)->andWhere('type')->eq('stage')->orderBy('id_asc')->fetchAll('id');
        $deletedParents   = $deletedStageList;
        array_pop($deletedParents);

        $deletedTitle = '';
        foreach($deletedParents as $deletedParent) $deletedTitle .= "'{$deletedParent->name}',";

        /* If parent stage's attribute has changed, sub-stage's attribute need change. */
        $deletedTopParent = current($deletedStageList);
        $checkTopStage    = $this->loadModel('programplan')->checkTopStage($deletedTopParent->id);
        $parentAttr       = $deletedTopParent->attribute;
        if(!$checkTopStage) $parentAttr = $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($deletedTopParent->parent)->fetch('attribute');

        $needChangeAttr    = false;
        $startChangedStage = $execution;
        foreach($deletedStageList as $deletedStage)
        {
            if($parentAttr != 'mix' and $parentAttr != $deletedStage->attribute)
            {
                $startChangedStage = $deletedStage;
                $needChangeAttr    = true;
                break;
            }
            $parentAttr = $deletedStage->attribute;
        }

        /* Confirm. */
        if(!empty($deletedTitle) or $needChangeAttr)
        {
            $this->app->loadLang('stage');

            $deletedTitle = trim($deletedTitle, ',');
            $confirmLang  = sprintf($this->lang->action->hasDeletedParent, $deletedTitle) . $this->lang->action->whetherToRestore;
            if($needChangeAttr) $confirmLang = sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;
            if(!empty($deletedTitle) and $needChangeAttr) $confirmLang = sprintf($this->lang->action->hasDeletedParent, $deletedTitle) . sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;

            if($confirmChange == 'no')
            {
                return $confirmLang;
            }
            else
            {
                if(!empty($deletedTitle)) $this->action->restoreStages($deletedParents);
                if($needChangeAttr)
                {
                    $needChangedStages = substr($execution->path, strpos($execution->path, ",{$startChangedStage->id},"));
                    $needChangedStages = explode(',', trim($needChangedStages, ','));
                    $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($parentAttr)->where('id')->in($needChangedStages)->exec();
                }
            }
        }

        $this->programplan->computeProgress($startChangedStage->parent);

        return true;
    }

    /**
     * Clear dynamic records older than one month.
     *
     * @access public
     * @return void
     */
    public function cleanActions()
    {
        $this->action->cleanActions();
    }

    /**
     * 通过 Ajax 获取操作记录列表。
     * Get action list by ajax.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetList(string $objectType, int $objectID)
    {
        $actions = $this->action->getList($objectType, $objectID);
        $actions = $this->action->buildActionList($actions);
        return $this->send($actions);
    }
}
