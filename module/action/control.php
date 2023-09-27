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
     * 创建一个动作或者删除所有的补丁动作，此方法由Ztools使用。
     * Create a action or delete all patch actions, this method is used by the Ztools.
     *
     * @param  string $objectType
     * @param  string $actionType
     * @param  string $objectName
     * @access public
     * @return void
     */
    public function create(string $objectType, string $actionType, string $objectName)
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
     * 回收站。
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
    public function trash(string $browseType = 'all', string $type = 'all', bool $byQuery = false, int $queryID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Url存入session。 */
        /* Save url into session. */
        $this->actionZen->saveUrlIntoSession();

        /* 保存用于替换搜索语言项的对象名称。 */
        /* Save the object name used to replace the search language item. */
        $this->session->set('objectName', zget($this->lang->action->objectTypes, $browseType, ''), 'admin');

        /* 生成表单搜索数据。 */
        /* Build the search form. */
        $actionURL = $this->createLink('action', 'trash', "browseType=$browseType&type=$type&byQuery=true&queryID=myQueryID");
        $this->action->buildTrashSearchForm($queryID, $actionURL);

        /* 分页初始化。 */
        /* Load paper. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 生成排序规则。 */
        /* Generate the sort rules.  */
        $sort           = common::appendOrder($orderBy);
        $trashes        = $byQuery ? $this->action->getTrashesBySearch($browseType, $type, $queryID, $sort, $pager) : $this->action->getTrashes($browseType, $type, $sort, $pager);
        $objectTypeList = array_keys($this->action->getTrashObjectTypes($type));

        /* 获取头部模块标题导航。 */
        /* Build the header navigation title. */
        $preferredType = $this->actionZen->getTrashesHeaderNavigation($objectTypeList);

        /* 初始化项目、产品、执行列表。 */
        /* Initialize the project, product, execution list. */
        $projectList   = array();
        $productList   = array();
        $executionList = array();

        /* 获取执行所属的项目名称。 */
        /* Get the projects name of executions. */
        if($browseType == 'execution') $this->view->projectList = $projectList = $this->loadModel('project')->getByIdList(array_column($trashes, 'project'), 'all');

        /* 获取用户故事所属的产品名称。 */
        /* Get the products name of story. */
        if(in_array($browseType, array('story', 'requirement'))) $this->view->productList = $productList = $this->loadModel('product')->getByIdList(array_column($trashes, 'objectID'), 'all');

        /* 获取任务的执行名称。 */
        /* Get the executions name of task. */
        if($browseType == 'task') $this->view->executionList = $executionList = $this->loadModel('execution')->getByIdList(array_column($trashes, 'execution'), 'all');

        /* 补充操作记录的信息。 */
        /* Supplement the information recorded by the operation. */
        foreach($trashes as $trash) $this->actionZen->processTrash($trash, $projectList, $productList, $executionList);

        $this->view->title               = $this->lang->action->trash;
        $this->view->trashes             = $trashes;
        $this->view->type                = $type;
        $this->view->currentObjectType   = $browseType;
        $this->view->orderBy             = $orderBy;
        $this->view->pager               = $pager;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter');
        $this->view->preferredType       = $preferredType;
        $this->view->byQuery             = $byQuery;
        $this->view->queryID             = $queryID;
        $this->display();
    }

    /**
     * 恢复一个回收站对象。
     * Undelete an object.
     *
     * @param  int    $actionID
     * @param  string $browseType
     * @param  string $confirmChange
     * @access public
     * @return void
     */
    public function undelete(int $actionID, string $browseType = 'all', string $confirmChange = 'no')
    {
        $oldAction = $this->actionZen->checkActionExist($actionID);
        $extra     = $oldAction->extra == actionModel::BE_HIDDEN ? 'hidden' : 'all';

        if(in_array($oldAction->objectType, array('program', 'project', 'execution', 'product')))
        {
            $table = $oldAction->objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            list($repeatObject, $object) = $this->action->getRepeatObject($oldAction, $table);

            if($repeatObject)
            {
                list($replaceName, $replaceCode) = $this->actionZen->getReplaceNameAndCode($repeatObject, $object, $table);
                if($confirmChange == 'no')
                {
                    $message = '';
                    if($repeatObject->name == $object->name && $repeatObject->code && $repeatObject->code == $object->code)
                    {
                        $message = sprintf($this->lang->action->repeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName, $replaceCode);
                    }
                    elseif($repeatObject->name == $object->name)
                    {
                        $message = sprintf($this->lang->action->nameRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName);
                    }
                    elseif($repeatObject->code && $repeatObject->code == $object->code)
                    {
                        $message = sprintf($this->lang->action->codeRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceCode);
                    }

                    if($message)
                    {
                        $url = $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes");
                        return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$message}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$url}'});     });"));
                    }
                }
                elseif($confirmChange == 'yes')
                {
                    $recoverData = array();
                    if($repeatObject->name == $object->name && $repeatObject->code && $repeatObject->code == $object->code)
                    {
                        $recoverData = array('code' => $replaceCode, 'name' => $replaceName);
                    }
                    elseif($repeatObject->name == $object->name)
                    {
                        $recoverData = array('name' => $replaceName);
                    }
                    elseif($repeatObject->code && $repeatObject->code == $object->code)
                    {
                        $recoverData = array('code' => $replaceCode);
                    }

                    if(!empty($recoverData)) $this->action->updateObjectByID($table, $oldAction->objectID, $recoverData);
                }
            }

            if($oldAction->objectType == 'execution')
            {
                $confirmLang = $this->restoreStages($oldAction, $confirmChange);
                $url         = $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes");
                if($confirmLang !== true) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$confirmLang}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$url}'});});"));
            }
        }

        $result = $this->action->undelete($actionID);
        if(true !== $result) return $this->send(array('result' => 'fail', 'load' => array('confirm' => $result)));

        $sameTypeObjects = $this->action->getTrashes($oldAction->objectType, $extra, 'id_desc', null);
        $browseType      = ($sameTypeObjects && $browseType != 'all') ? $oldAction->objectType : 'all';

        return $this->send(array('result' => 'success', 'load' => $this->createLink('action', 'trash', "browseType=$browseType&type=$extra")));
    }

    /**
     * 隐藏一个已经被删除的对象。
     * Hide an deleted object.
     *
     * @param  int    $actionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function hideOne(int $actionID, string $browseType = 'all')
    {
        $oldAction = $this->actionZen->checkActionExist($actionID);

        $this->action->hideOne($actionID);

        $sameTypeObjects = $this->action->getTrashes($oldAction->objectType, 'all', 'id_desc', null);
        $browseType      = ($sameTypeObjects && $browseType != 'all') ? $oldAction->objectType : 'all';

        return $this->send(array('result' => 'success', 'load' => $this->createLink('action', 'trash', "browseType={$browseType}")));
    }

    /**
     * 隐藏所有被删除的对象。
     * Hide all deleted objects.
     *
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function hideAll(string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $url     = inlink('hideAll', "confirm=yes");
            $message = $this->lang->action->confirmHideAll;
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$message}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$url}'});});"));
        }

        $this->action->hideAll();
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 评论。
     * Comment.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function comment(string $objectType, int $objectID)
    {
        /* 当评论的是任务，需判断当前用户是否拥有任务的权限。 */
        /* When commenting on a task, you need to determine whether the current user has the permission of the task. */
        if(strtolower($objectType) == 'task')
        {
            $task       = $this->loadModel('task')->getById($objectID);
            $executions = explode(',', $this->app->user->view->sprints);
            if(!in_array($task->execution, $executions)) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
        }
        /* 当评论的是用户故事，需判断当前用户是否有此用户故事的权限。 */
        /* When commenting on a story, you need to determine whether the current user has the permission of the story. */
        elseif(strtolower($objectType) == 'story')
        {
            $story      = $this->loadModel('story')->getById($objectID);
            $executions = explode(',', $this->app->user->view->sprints);
            $products   = explode(',', $this->app->user->view->products);
            if(!array_intersect(array_keys($story->executions), $executions) && !in_array($story->product, $products) && empty($story->lib)) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
        }

        /* 获取评论内容并生成一条action数据。 */
        $commentData = form::data($this->config->action->form->comment)->get();
        $actionID    = $this->action->create($objectType, $objectID, 'Commented', $commentData->comment);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $actionID));

        /* 用于ZIN的新UI。*/
        /* For new UI with ZIN. */
        return $this->send(array('status' => 'success', 'closeModal' => true, 'load' => true));
    }

    /**
     * 编辑评论。
     * Edit comment.
     *
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function editComment(int $actionID)
    {
        /* 获取表单内的数据。 */
        /* Get form data. */
        $commentData = form::data($this->config->action->form->editComment)->get();

        $error = false;

        /* 判断是否符合更新的条件。 */
        /* Determine whether the update conditions are met. */
        if(strlen(trim(strip_tags($commentData->lastComment, '<img>'))) != 0)
        {
            $error = $this->action->updateComment($actionID, $commentData->lastComment, $commentData->uid);
        }

        if(!$error)
        {
            /* 不符合更新条件，返回错误。 */
            /* The update conditions are not met and an error is returned. */
            dao::$errors['submit'][] = $this->lang->action->historyEdit;
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Restore stages.
     *
     * @param  object $action
     * @param  string $confirmChange
     * @access public
     * @return bool|string
     */
    public function restoreStages(object $action, string $confirmChange = 'no'): bool|string
    {
        /* 检查父阶段是否创建过任务。 */
        /* Check parent stage isCreateTask. */
        $execution      = $this->loadModel('execution')->getByID($action->objectID);
        $hasCreatedTask = $this->loadModel('programplan')->isCreateTask($execution->parent);
        if(!$hasCreatedTask) return $this->lang->action->hasCreatedTask;

        /* 检查同级执行的类型。 */
        /* Check type of siblings. */
        $siblings = $this->execution->getSiblingsTypeByParentID($execution->parent);

        if($execution->type == 'stage' && (isset($siblings['sprint']) || isset($siblings['kanban']))) return $this->lang->action->hasOtherType[$execution->type];
        if(($execution->type == 'sprint' || $execution->type == 'kanban') && isset($siblings['stage'])) return $this->lang->action->hasOtherType[$execution->type];

        /* 如果父阶段不存在，你应该恢复父级阶段，并且刷新状态。 */
        /* If parent stage is not exists, you should recover its parent stages, refresh status. */
        $stagePathList    = explode(',', trim($execution->path, ','));
        $deletedStageList = $this->action->getDeletedStagedByList($stagePathList);
        $deletedParents   = $deletedStageList;
        array_pop($deletedParents);

        $needChangeAttr    = false; //是否需要修改attribute值
        $deletedTitle      = ''; //被删除的标题
        $startChangedStage = $execution;

        if(!empty($deletedParents))
        {
            foreach($deletedParents as $deletedParent) $deletedTitle .= "'{$deletedParent->name}',";

            /* 如果父阶段的状态已经被改变，子阶段的的状态也要改变。 */
            /* If parent stage's attribute has changed, sub-stage's attribute need change. */
            $deletedTopParent = current($deletedStageList);
            $isTopStage       = $this->loadModel('programplan')->isTopStage($deletedTopParent->id);
            $parentAttr       = $deletedTopParent->attribute;
            if(!$isTopStage) $parentAttr = $this->action->getAttributeByExecutionID();

            /* 从父阶段开始逐级下查，如果发现有跟父阶段不一样的attribute，对后面所有的attrubite值进行修改。 */
            /* Check down step by step from the parent stage, if you find that there is an attribute different from the parent stage, modify all the later attrubite values*. */
            $startChangedStage = $execution;
            foreach($deletedStageList as $deletedStage)
            {
                if($parentAttr != 'mix' && $parentAttr != $deletedStage->attribute)
                {
                    $startChangedStage = $deletedStage;
                    $needChangeAttr    = true;
                    break;
                }
                $parentAttr = $deletedStage->attribute;
            }
        }

        /* 确认是否要恢复父阶段并且修改不一样的attribute值。 */
        /* Confirm whether you want to restore the parent stage and modify the different attribute value. */
        if(!empty($deletedTitle) || $needChangeAttr)
        {
            $this->app->loadLang('stage');

            $confirmLang  = sprintf($this->lang->action->hasDeletedParent, trim($deletedTitle, ',')) . $this->lang->action->whetherToRestore;
            if($needChangeAttr) $confirmLang = sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;
            if(!empty($deletedTitle) && $needChangeAttr) $confirmLang = sprintf($this->lang->action->hasDeletedParent, $deletedTitle) . sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;

            if($confirmChange == 'no') return $confirmLang;

            /* 如果被删除的标题集合不为空，则更新所有的阶段。 */
            /* If the collection of titles being deleted is not empty, all stages are updated. */
            if(!empty($deletedTitle)) $this->action->restoreStages($deletedParents);

            /* 如果需要更新attribute的值，则恢复路径上所有的父节点以及更新attrubute值。 */
            /* If the attribute value needs to be updated, restore all parent nodes on the path and update the attribute value. */
            if($needChangeAttr)
            {
                $needChangedStages = substr($execution->path, strpos($execution->path, ",{$startChangedStage->id},"));
                $needChangedStages = explode(',', trim($needChangedStages, ','));
                $this->action->updateStageAttribute($parentAttr, $needChangedStages);
            }
        }

        $this->programplan->computeProgress($startChangedStage->parent);

        return true;
    }
}
