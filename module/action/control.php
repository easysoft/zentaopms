<?php
declare(strict_types=1);
/**
 * The control file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        https://www.zentao.net
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
    public function trash(string $browseType = 'all', string $type = 'all', bool $byQuery = false, int $queryID = 0, string $orderBy = 'date_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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
        if(in_array($browseType, array('story', 'requirement')))
        {
            $storyIdList = array();
            foreach($trashes as $trash) $storyIdList[] = $trash->objectID;
            $this->view->productList = $this->loadModel('story')->getByList($storyIdList, 'all');
        }

        /* 获取任务的执行名称。 */
        /* Get the executions name of task. */
        if($browseType == 'task') $this->view->executionList = $executionList = $this->loadModel('execution')->getByIdList(array_column($trashes, 'execution'), 'all');

        /* 补充操作记录的信息。 */
        /* Supplement the information recorded by the operation. */
        foreach($trashes as $trash) $this->actionZen->processTrash($trash, $projectList, $productList, $executionList);

        $this->view->title             = $this->lang->action->trash;
        $this->view->trashes           = $trashes;
        $this->view->type              = $type;
        $this->view->currentObjectType = $browseType;
        $this->view->orderBy           = $orderBy;
        $this->view->pager             = $pager;
        $this->view->users             = $this->loadModel('user')->getPairs('noletter');
        $this->view->preferredType     = $preferredType;
        $this->view->byQuery           = $byQuery;
        $this->view->queryID           = $queryID;
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

        /* 当对象类型为program、project、execution、product时，需要检查是否有重复的对象。 */
        /* When the object type is program, project, execution, product, you need to check if there are duplicate objects. */
        if(in_array($oldAction->objectType, array('program', 'project', 'execution', 'product')))
        {
            $table = $oldAction->objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            list($repeatObject, $object) = $this->action->getRepeatObject($oldAction, $table);

            if($repeatObject)
            {
                list($replaceName, $replaceCode) = $this->actionZen->getReplaceNameAndCode($repeatObject, $object, $table);
                if($confirmChange == 'no')
                {
                    $message = $this->actionZen->getConfirmNoMessage($repeatObject, $object, $oldAction, $replaceName, $replaceCode);
                    if($message)
                    {
                        $url = $this->createLink('action', 'undelete', "action={$actionID}&browseType={$browseType}&confirmChange=yes");
                        return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$message}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$url}'});     });"));
                    }
                }
                elseif($confirmChange == 'yes')
                {
                    $this->actionZen->recoverObject($repeatObject, $object, $replaceName, $replaceCode, $table, $oldAction);
                }
            }

            if($oldAction->objectType == 'execution')
            {
                $confirmLang = $this->actionZen->restoreStages($oldAction, $confirmChange);
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
        if(!empty($_POST))
        {
            $isInZinPage = isInModal() || in_array($objectType, $this->config->action->newPageModule);
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
            $actionID    = $this->action->create($objectType, $objectID, 'Commented', isset($commentData->actioncomment) ? $commentData->actioncomment : $this->post->comment);
            if(empty($actionID))
            {
                if($isInZinPage) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->accessDenied));
                return print(js::error($this->lang->error->accessDenied));
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $actionID));

            if($isInZinPage) return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $objectType, 'objectID' => (int)$objectID))));

            /* 用于ZIN的新UI。*/
            /* For new UI with ZIN. */
            return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $objectType, 'objectID' => $objectID))));
        }

        $this->view->title      = $this->lang->action->create;
        $this->view->objectType = $objectType;
        $this->view->objectID   = $objectID;
        $this->display();
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
        $action = $this->action->getById($actionID);

        if(!empty($_POST))
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

            $action = $this->action->getById($actionID);
            if(isInModal() || in_array($action->objectType, $this->config->action->newPageModule))
            {
                return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $action->objectType, 'objectID' => (int)$action->objectID))));
            }
            return $this->send(array('status' => 'success', 'closeModal' => true, 'callback' => array('name' => 'zui.HistoryPanel.update', 'params' => array('objectType' => $action->objectType, 'objectID' => $action->objectID))));
        }

        $this->view->title      = $this->lang->action->editComment;
        $this->view->actionID   = $actionID;
        $this->view->comment    = $this->action->formatActionComment($action->comment);
        $this->display();
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
}
