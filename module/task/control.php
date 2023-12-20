<?php
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2013-07-12 01:28:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class task extends control
{
    /**
     * Construct function, load model of project and story modules.
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('story');
        $this->loadModel('tree');
    }

    /**
     * Create a task.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  int    $todoID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($executionID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $todoID = 0, $extra = '', $bugID = 0)
    {
        if(empty($this->app->user->view->sprints) and !$executionID) $this->locate($this->createLink('execution', 'create'));
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($executionID)) $execution = $this->execution->getById($executionID);
        $executions  = $this->execution->getPairs(0, 'all', isset($execution) ? (!common::canModify('execution', $execution) ? 'noclosed' : '') : 'noclosed');
        $executionID = $this->execution->saveState($executionID, $executions);
        $execution   = $this->execution->getById($executionID);

        $this->execution->setMenu($executionID);
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        $this->execution->getLimitedExecution();
        $limitedExecutions = !empty($_SESSION['limitedExecutions']) ? $_SESSION['limitedExecutions'] : '';
        if(strpos(",{$limitedExecutions},", ",$executionID,") !== false)
        {
            echo js::alert($this->lang->task->createDenied);
            return print(js::locate($this->createLink('execution', 'task', "executionID=$executionID")));
        }

        $task = new stdClass();
        $task->module     = $moduleID;
        $task->mode       = '';
        $task->assignedTo = '';
        $task->name       = '';
        $task->story      = $storyID;
        $task->type       = '';
        $task->pri        = '3';
        $task->estimate   = '';
        $task->desc       = '';
        $task->estStarted = '';
        $task->deadline   = '';
        $task->mailto     = '';
        $task->color      = '';
        if($taskID > 0)
        {
            $task        = $this->task->getByID($taskID);
            $executionID = $task->execution;

            /* Emptying consumed hours when copy task. */
            if($task->mode == 'multi')
            {
                foreach($task->team as $teamMember) $teamMember->consumed = 0;
            }
        }

        if($todoID > 0)
        {
            $todo = $this->loadModel('todo')->getById($todoID);
            $task->name = $todo->name;
            $task->pri  = $todo->pri;
            $task->desc = $todo->desc;
        }

        if($bugID > 0)
        {
            $bug = $this->loadModel('bug')->getById($bugID);
            $task->name       = $bug->title;
            $task->pri        = $bug->pri;
            $task->pri        = !empty($bug->pri) ? $bug->pri : '3';
            $task->assignedTo = array($bug->assignedTo);
        }

        $taskLink  = $this->createLink('execution', 'browse', "executionID=$executionID&tab=task");

        $this->loadModel('kanban');
        if($execution->type == 'kanban')
        {
            $regionPairs = $this->kanban->getRegionPairs($execution->id, 0, 'execution');
            $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
            $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'task');
            $laneID      = isset($output['laneID']) ? $output['laneID'] : key($lanePairs);

            $this->view->regionID    = $regionID;
            $this->view->laneID      = $laneID;
            $this->view->regionPairs = $regionPairs;
            $this->view->lanePairs   = $lanePairs;
        }

        if(!empty($_POST))
        {
            $response['result'] = 'success';

            setcookie('lastTaskModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($this->post->execution) $executionID = (int)$this->post->execution;

            /* Create task here. */
            $tasksID = $this->task->create($executionID, $bugID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            /* if the count of tasksID is 1 then check exists. */
            if(count($tasksID) == 1)
            {
                $taskID = current($tasksID);
                if($taskID['status'] == 'exists')
                {
                    $response['locate']  = $this->createLink('task', 'view', "taskID={$taskID['id']}");
                    $response['message'] = sprintf($this->lang->duplicate, $this->lang->task->common);
                    return $this->send($response);
                }
            }

            /* Create actions. */
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                /* if status is exists then this task has exists not new create. */
                if($taskID['status'] == 'exists') continue;

                $taskID = $taskID['id'];
                $this->action->create('task', $taskID, 'Opened', '');
            }

            /* Create task in kanban. */
            $kanbanID = $execution->type == 'kanban' ? $executionID : $_POST['execution'];

            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(!empty($_POST['lane'])) $laneID = $_POST['lane'];

            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'wait');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($kanbanID, $laneID, $columnID, 'task', $taskID);
            if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($kanbanID, 'task');

            /* To do status. */
            if($todoID > 0)
            {
                $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
                $this->action->create('todo', $todoID, 'finished', '', "TASK:$taskID");

                if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;
            $response['message'] = $this->lang->saveSuccess;

            /* Return task id when call the API. */
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $taskID));

            /* If link from no head then reload. */
            if(isonlybody())
            {
                if($this->app->tab == 'execution' or $this->config->vision == 'lite')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);

                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, 0)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->kanban->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);

                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban(\"task\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
                }
            }

            /* Locate the browser. */
            if($this->app->getViewType() == 'xhtml')
            {
                $taskLink  = $this->createLink('task', 'view', "taskID=$taskID", 'html');
                $response['locate'] = $taskLink;
                return $this->send($response);
            }

            if($this->post->after == 'continueAdding')
            {
                $storyParam = $this->post->story ? $this->post->story : '';

                $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
                $response['locate']  = $this->createLink('task', 'create', "executionID=$executionID&storyID={$storyParam}&moduleID=$moduleID");
                if($this->app->tab == 'project') $response['locate']  = 'reload';
                return $this->send($response);
            }
            elseif($this->post->after == 'toTaskList')
            {
                setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $taskLink  = $this->createLink('execution', 'task', "executionID=$executionID&status=unclosed&param=0&orderBy=id_desc");
                $response['locate'] = $taskLink;
                return $this->send($response);
            }
            elseif($this->post->after == 'toStoryList')
            {
                $response['locate'] = $this->createLink('execution', 'story', "executionID=$executionID");
                if($this->config->vision == 'lite')
                {
                    $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
                    $response['locate'] = $this->createLink('projectstory', 'story', "projectID=$projectID");
                }
                return $this->send($response);
            }
            else
            {
                $response['locate'] = $taskLink;
                return $this->send($response);
            }
        }

        $users            = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $members          = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $showAllModule    = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');

        /* Fix bug #3381. When the story module is the root module. */
        if($storyID)
        {
            $task->module = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch('module');
        }
        else
        {
            $task->module = $task->module ? $task->module : (int)$this->cookie->lastTaskModule;
            if(!isset($moduleOptionMenu[$task->module])) $task->module = 0;
        }

        $stories = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', '', 'active');

        /* Get block id of assinge to me. */
        $blockID = 0;
        if(isonlybody())
        {
            $blockID = $this->dao->select('id')->from(TABLE_BLOCK)
                ->where('block')->eq('assingtome')
                ->andWhere('module')->eq('my')
                ->andWhere('account')->eq($this->app->user->account)
                ->orderBy('order_desc')
                ->fetch('id');
        }

        $title      = $execution->name . $this->lang->colon . $this->lang->task->create;
        $position[] = html::a($taskLink, $execution->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->create;

        $projectID = $execution ? $execution->project : 0;

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        if(!empty($projectID))
        {
            $executions = $this->execution->getByProject($projectID, 'all', 0, true);

            $executionKey = 0;
            $executionModifyList = $this->execution->getByIdList(array_keys($executions));
            foreach($executionModifyList as $modifykey)
            {
                if(!common::canModify('execution', $modifykey)) $executionKey = $modifykey->id;
                if($executionKey) unset($executions[$executionKey]);
            }
        }

        $lifetimeList  = array();
        $attributeList = array();
        $executionList = $this->execution->getByIdList(array_keys($executions));
        foreach($executionList as $id => $object)
        {
            $lifetimeList[$id]  = $object->lifetime;
            $attributeList[$id] = $object->attribute;
        }

        $testStoryIdList = $this->loadModel('story')->getTestStories(array_keys($stories), $execution->id);
        /* Stories that can be used to create test tasks. */
        $testStories     = array();
        foreach($stories as $storyID => $storyTitle)
        {
            if(empty($storyID) or isset($testStoryIdList[$storyID])) continue;
            $testStories[$storyID] = $storyTitle;
        }

        $this->view->customFields  = $customFields;
        $this->view->showFields    = $this->config->task->custom->createFields;
        $this->view->showAllModule = $showAllModule;

        $this->view->title            = $title;
        $this->view->testStories      = $testStories;
        $this->view->position         = $position;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID=$executionID") : '';
        $this->view->execution        = $execution;
        $this->view->executions       = $executions;
        $this->view->lifetimeList     = $lifetimeList;
        $this->view->attributeList    = $attributeList;
        $this->view->task             = $task;
        $this->view->users            = $users;
        $this->view->storyID          = $storyID;
        $this->view->stories          = $stories;
        $this->view->testStoryIdList  = $testStoryIdList;
        $this->view->members          = $members;
        $this->view->blockID          = $blockID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->projectID        = $projectID;
        $this->view->productID        = $this->loadModel('product')->getProductIDByProject($projectID);;
        $this->view->features         = $this->execution->getExecutionFeatures($execution);
        $this->display();
    }

    /**
     * Batch create task.
     *
     * @param int    $executionID
     * @param int    $storyID
     * @param int    $iframe
     * @param int    $taskID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function batchCreate($executionID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $iframe = 0, $extra = '')
    {
        $this->execution->getLimitedExecution();
        $limitedExecutions = !empty($_SESSION['limitedExecutions']) ? $_SESSION['limitedExecutions'] : '';
        if(strpos(",{$limitedExecutions},", ",$executionID,") !== false)
        {
            echo js::alert($this->lang->task->createDenied);
            return print(js::locate($this->createLink('execution', 'task', "executionID=$executionID")));
        }

        $execution = $this->execution->getById($executionID);

        if($this->app->tab == 'my')
        {
            $taskLink = $this->createLink('my', 'work', 'mode=task');
        }
        elseif($this->app->tab == 'project' and $execution->multiple)
        {
            $taskLink = $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        }
        else
        {
            $taskLink  = $this->createLink('execution', 'browse', "executionID=$executionID");
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        /* When common task are child tasks, query whether common task are consumed. */
        $taskConsumed = 0;
        if($taskID) $taskConsumed = $this->dao->select('consumed')->from(TABLE_TASK)->where('id')->eq($taskID)->andWhere('parent')->eq(0)->fetch('consumed');

        if(!empty($_POST))
        {
            $mails = $this->task->batchCreate($executionID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));

            $taskIDList = array();
            foreach($mails as $mail) $taskIDList[] = $mail->taskID;

            /* Return task id list when call the API. */
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $taskIDList));

            /* If link from no head then reload. */
            if(isonlybody())
            {
                if($this->app->tab == 'execution' or $this->config->vision == 'lite')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);

                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, 0)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);

                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::reload('parent.parent'));
                }
            }
            return print(js::locate($taskLink, 'parent'));
        }

        $story = $this->story->getByID($storyID);
        if($story)
        {
            $moduleID = $story->module;
            $stories  = $this->story->getExecutionStoryPairs($executionID, 0, 'all', $moduleID, 'short', 'active');
        }
        else
        {
            $stories = $this->story->getExecutionStoryPairs($executionID, 0, 'all', 0, 'short', 'active');
        }

        $members       = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customBatchCreateFields) as $field)
        {
            if($execution->type == 'stage' and strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }

        $showFields = $this->config->task->custom->batchCreateFields;
        if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review')
        {
            unset($customFields['story']);
            $showFields = str_replace(',story,', ',', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        if($execution->type == 'kanban')
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            $this->loadModel('kanban');
            $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
            $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
            $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'task');
            $laneID      = isset($output['laneID']) ? $output['laneID'] : key($lanePairs);

            $this->view->regionID    = $regionID;
            $this->view->laneID      = $laneID;
            $this->view->regionPairs = $regionPairs;
            $this->view->lanePairs   = $lanePairs;
        }

        $title      = $execution->name . $this->lang->colon . $this->lang->task->batchCreate;
        $position[] = html::a($taskLink, $execution->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->batchCreate;

        if($taskID) $this->view->parentTitle = $this->dao->select('name')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('name');
        if($taskID) $this->view->parentPri   = $this->dao->select('pri')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('pri');

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->execution    = $execution;
        $this->view->stories      = $stories;
        $this->view->modules      = $modules;
        $this->view->parent       = $taskID;
        $this->view->storyID      = $storyID;
        $this->view->story        = $story;
        $this->view->storyTasks   = $this->task->getStoryTaskCounts(array_keys($stories), $executionID);
        $this->view->members      = $members;
        $this->view->moduleID     = $moduleID;
        $this->view->taskConsumed = $taskConsumed;
        $this->display();
    }

    /**
     * Common actions of task module.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function commonAction($taskID)
    {
        $this->view->task      = $this->loadModel('task')->getByID($taskID);
        $this->view->execution = $this->execution->getById($this->view->task->execution);
        $this->view->members   = $this->loadModel('user')->getTeamMemberPairs($this->view->execution->id, 'execution','nodeleted');
        $this->view->actions   = $this->loadModel('action')->getList('task', $taskID);

        /* Set menu. */
        $this->execution->setMenu($this->view->execution->id);
        if(!$this->view->execution->multiple) $this->loadModel('project')->setMenu($this->view->task->project);

        $this->view->position[] = html::a($this->createLink('execution', 'browse', "execution={$this->view->task->execution}"), $this->view->execution->name);
    }

    /**
     * Edit a task.
     *
     * @param  int    $taskID
     * @param  string $comment
     * @param  string $kanbanGroup
     * @param  string $from
     * @access public
     * @return void
     */
    public function edit($taskID, $comment = 'false', $kanbanGroup = 'default', $from = '')
    {
        $this->commonAction($taskID);
        $task = $this->task->getById($taskID);
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($task->project);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = array();
            if(!$comment or $comment == 'false')
            {
                $changes = $this->task->update($taskID);
                if(dao::isError()) return print(js::error(dao::getError()));
            }

            if($this->post->comment != '' or !empty($changes))
            {
                $action     = !empty($changes) ? 'Edited' : 'Commented';
                $actionID   = $this->action->create('task', $taskID, $action, $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if($_POST['status'] == 'doing') $this->loadModel('common')->syncPPEStatus($taskID);

            if($task->fromBug != 0)
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        $cancelURL  = $this->server->HTTP_REFERER;
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                    }
                }
            }

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                if($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::reload('parent.parent'));
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, $this->view->task->parent);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        if(!isset($this->view->members[$this->view->task->assignedTo])) $this->view->members[$this->view->task->assignedTo] = $this->view->task->assignedTo;
        if(isset($this->view->members['closed']) or $this->view->task->status == 'closed') $this->view->members['closed']  = 'Closed';

        $executions = array();
        if(!empty($task->project)) $executions = $this->execution->getByProject($task->project, 'all', 0, true);

        $this->view->title         = $this->lang->task->edit . 'TASK' . $this->lang->colon . $this->view->task->name;
        $this->view->position[]    = $this->lang->task->common;
        $this->view->position[]    = $this->lang->task->edit;
        $this->view->stories       = $this->story->getExecutionStoryPairs($this->view->execution->id, 0, 'all', '', 'full', 'active');
        $this->view->tasks         = $tasks;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed', "{$this->view->task->openedBy},{$this->view->task->canceledBy},{$this->view->task->closedBy}");
        $this->view->showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->view->modules       = $this->tree->getTaskOptionMenu($this->view->task->execution, 0, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions    = $executions;
        $this->display();
    }

    /**
     * Batch edit task.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit($executionID = 0)
    {
        if($this->post->names)
        {
            $allChanges = $this->task->batchUpdate();
            if(dao::isError()) return print(js::error(dao::getError()));

            if(!empty($allChanges))
            {
                /* updateStatus is a description of whether to update the responsibility performance*/
                $waitTaskID = false;
                foreach($allChanges as $taskID => $changes)
                {
                    if(empty($changes)) continue;

                    /* Determine whether the status of a task has been changed, if the status of a task has been changed, set $updateStatus to taskID*/
                    if($waitTaskID == false)
                    {
                        foreach($changes as $changeField)
                        {
                            if($changeField['field'] == 'status' && $changeField['new'] == 'doing')
                            {
                                $waitTaskID = $taskID;
                                break;
                            }
                        }
                    }

                    $actionID = $this->loadModel('action')->create('task', $taskID, 'Edited');
                    $this->action->logHistory($actionID, $changes);

                    $task = $this->task->getById($taskID);
                    if($task->fromBug != 0)
                    {
                        foreach($changes as $change)
                        {
                            if($change['field'] == 'status')
                            {
                                $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                                $cancelURL  = $this->server->HTTP_REFERER;
                                return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                            }
                        }
                    }
                    if($waitTaskID !== false) $this->loadModel('common')->syncPPEStatus($waitTaskID);
                }
            }
            $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->taskList, 'parent'));
        }

        if(!$this->post->taskIDList) return print(js::locate($this->session->taskList, 'parent'));

        $taskIDList = array_unique($this->post->taskIDList);

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
            $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$execution->id"), $execution->name);
            $this->view->execution  = $execution;
            $this->view->modules    = $modules;
        }
        /* The tasks of my. */
        else
        {
            /* Set my menu. */
            $this->loadModel('my');
            $this->lang->my->menu->work['subModule'] = 'task';

            $this->view->position[] = html::a($this->createLink('my', 'task'), $this->lang->my->task);
            $this->view->title      = $this->lang->task->batchEdit;
            $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        }

        /* Get edited tasks. */
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIDList)->fetchAll('id');
        $teams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIDList)->fetchGroup('task', 'id');

        /* Get execution teams. */
        $executionIDList = array();
        foreach($tasks as $task) if(!in_array($task->execution, $executionIDList)) $executionIDList[] = $task->execution;
        $executionTeams = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($executionIDList)->andWhere('type')->eq('execution')->fetchGroup('root', 'account');

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($tasks) * (count(explode(',', $this->config->task->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom*/
        if(isset($execution))
        {
            foreach(explode(',', $this->config->task->customBatchEditFields) as $field)
            {
                if($execution->type == 'stage' and strpos('estStarted,deadline', $field) !== false) continue;
                $customFields[$field] = $this->lang->task->$field;
            }
        }
        else
        {
            foreach(explode(',', $this->config->task->customBatchEditFields) as $field) $customFields[$field] = $this->lang->task->$field;
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchEditFields;

        /* Assign. */
        $this->view->position[]     = $this->lang->task->common;
        $this->view->position[]     = $this->lang->task->batchEdit;
        $this->view->executionID    = $executionID;
        $this->view->priList        = array('0' => '', 'ditto' => $this->lang->task->ditto) + $this->lang->task->priList;
        $this->view->statusList     = array('' => '',  'ditto' => $this->lang->task->ditto) + $this->lang->task->statusList;
        $this->view->typeList       = array('' => '',  'ditto' => $this->lang->task->ditto) + $this->lang->task->typeList;
        $this->view->taskIDList     = $taskIDList;
        $this->view->tasks          = $tasks;
        $this->view->teams          = $teams;
        $this->view->executionTeams = $executionTeams;
        $this->view->executionName  = isset($execution) ? $execution->name : '';
        $this->view->executionType  = isset($execution) ? $execution->type : '';
        $this->view->users          = $this->loadModel('user')->getPairs('nodeleted');

        $this->display();
    }

    /**
     * Update assign of task
     *
     * @param  int    $requestID
     * @param  int    $taskID
     * @param  string $kanbanGroup
     * @param  string $from
     * @access public
     * @return void
     */
    public function assignTo($executionID, $taskID, $kanbanGroup = 'default', $from = '')
    {
        $this->commonAction($taskID);
        $task = $this->task->getByID($taskID);

        if(!empty($task->team) and $task->mode == 'multi' and strpos('done,cencel,closed', $task->status) === false)
        {
            return $this->editTeam($executionID, $taskID, $kanbanGroup, $from);
        }

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->assign($taskID);

            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $actionID = $this->action->create('task', $taskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));
            if(isonlybody())
            {
                $task         = $this->task->getById($taskID);
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                if($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this'));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        // Ipd or vision research-stage module does not have team, so get research team members.
        if($this->config->vision == 'or')
        {
            $members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'project', 'nodeleted');
        }
        else
        {
            $members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        }

        /* Compute next assignedTo. */
        if(!empty($task->team) and strpos('done,cencel,closed', $task->status) === false)
        {
            $task->nextUser = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) or $task->status == 'closed') $members['closed'] = 'Closed';

        $this->view->title      = $this->view->execution->name . $this->lang->colon . $this->lang->task->assign;
        $this->view->position[] = $this->lang->task->assign;
        $this->view->task       = $task;
        $this->view->members    = $members;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Batch change the module of task.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        if($this->post->taskIDList)
        {
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            $allChanges = $this->task->batchChangeModule($taskIDList, $moduleID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $taskID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('task', $taskID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }
        return print(js::reload('parent'));
    }

    /**
     * Batch update assign of task.
     *
     * @param  int    $execution
     * @access public
     * @return void
     */
    public function batchAssignTo($execution)
    {
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            if(!is_array($taskIDList)) return print(js::locate($this->createLink('execution', 'task', "executionID=$execution"), 'parent'));
            $taskIDList = array_unique($taskIDList);

            $muletipleTasks = $this->dao->select('task, account')->from(TABLE_TASKTEAM)->where('task')->in($taskIDList)->fetchGroup('task', 'account');
            $tasks          = $this->task->getByList($taskIDList);
            $this->loadModel('action');
            foreach($tasks as $taskID => $task)
            {
                if(isset($muletipleTasks[$taskID]) and $task->assignedTo != $this->app->user->account and $task->mode == 'linear') continue;
                if(isset($muletipleTasks[$taskID]) and !isset($muletipleTasks[$taskID][$this->post->assignedTo])) continue;
                if($task->status == 'closed') continue;

                $changes = $this->task->assign($taskID);
                if(dao::isError()) return print(js::error(dao::getError()));
                $actionID = $this->action->create('task', $taskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::reload('parent'));
        }
    }

    /**
     * View a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        $taskID = (int)$taskID;
        $task   = $this->task->getById($taskID, true);
        if(!$task)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'all')));
        }
        if(!$this->loadModel('common')->checkPrivByObject('execution', $task->execution)) return print(js::error($this->lang->execution->accessDenied) . js::locate($this->createLink('execution', 'all')));

        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $this->commonAction($taskID);
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        $execution = $this->execution->getById($task->execution);
        if(!isonlybody() and $execution->type == 'kanban')
        {
            setcookie('taskToOpen', $taskID, 0, $this->config->webRoot, '', false, true);
            return print(js::locate($this->createLink('execution', 'kanban', "executionID=$execution->id")));
        }

        $this->session->project = $task->project;

        if($task->fromBug != 0)
        {
            $bug = $this->loadModel('bug')->getById($task->fromBug);
            $task->bugSteps = '';
            if($bug)
            {
                $task->bugSteps = $this->loadModel('file')->setImgSize($bug->steps);
                foreach($bug->files as $file) $task->files[] = $file;
            }
            $this->view->fromBug = $bug;
        }
        else
        {
            $story = $this->story->getById($task->story, $task->storyVersion);
            $task->storySpec   = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->spec);
            $task->storyVerify = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->verify);
            $task->storyFiles  = zget($story, 'files', array());
        }

        $task->linkedBranch = $this->task->getLinkedBranch($taskID);
        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        $this->executeHooks($taskID);

        $title      = "TASK#$task->id $task->name / $execution->name";
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$task->execution"), $execution->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->view;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->execution    = $execution;
        $this->view->task         = $task;
        $this->view->actions      = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext   = $this->loadModel('common')->getPreAndNextObject('task', $taskID);
        $this->view->product      = $this->tree->getProduct($task->module);
        $this->view->modulePath   = $this->tree->getParents($task->module);
        $this->view->linkMRTitles = $this->loadModel('mr')->getLinkedMRPairs($taskID, 'task');
        $this->view->linkCommits  = $this->loadModel('repo')->getCommitsByObject($taskID, 'task');
        $this->display();
    }

    /**
     * Confirm story change
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function confirmStoryChange($taskID)
    {
        $task = $this->task->getById($taskID);
        $this->dao->update(TABLE_TASK)->set('storyVersion')->eq($task->latestStoryVersion)->where('id')->eq($taskID)->exec();
        $this->loadModel('action')->create('task', $taskID, 'confirmed', '', $task->latestStoryVersion);

        $this->executeHooks($taskID);

        echo js::reload('parent');
    }

    /**
     * Start a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function start($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $task = $this->task->getById($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->start($taskID, $extra);

            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $act = $this->post->left == 0 ? 'Finished' : 'Started';
            $actionID = $this->action->create('task', $taskID, $act, $this->post->comment);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);
            $this->loadModel('common')->syncPPEStatus($taskID);

            /* Remind whether to update status of the bug, if task which from that bug has been finished. */
            if($changes and $this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status' and $change['new'] == 'done')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if(isset($output['from']) and $output['from'] == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $assignedTo = empty($task->assignedTo) ? $this->app->user->account : $task->assignedTo;
        if(!empty($task->team)) $assignedTo = $this->task->getAssignedTo4Multi($task->team, $task);

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->start;
        $this->view->position[] = $this->lang->task->start;

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->members    = $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $this->view->assignedTo = $assignedTo;
        $this->display();
    }

    /**
     * Record consumed and estimate.
     *
     * @param  int    $taskID
     * @param  string $from
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function recordEstimate($taskID, $from = '', $orderBy = '')
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $changes = $this->task->recordEstimate($taskID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('common')->syncPPEStatus($taskID);

            /* Remind whether to update status of the bug, if task which from that bug has been finished. */
            $task = $this->task->getById($taskID);
            if($changes and $this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status' and $change['new'] == 'done')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody())
            {
                $execution     = $this->execution->getByID($task->execution);
                $execLaneType  = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy   = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::reload('parent') . js::execute("parent.parent.updateKanban($kanbanData)"));
                }
                if($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::reload('parent') . js::execute("parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::reload('parent') . js::execute("if(typeof(parent.parent.ajaxRefresh) == 'function') parent.parent.ajaxRefresh()"));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $uri = $this->app->getURI(true);
        $this->session->set('estimateList', $uri, 'execution');
        if(isonlybody()) $this->session->set('estimateList', $uri . (strpos($uri, '?') === false ? '?' : '&')  . 'onlybody=yes', 'execution');

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
        if(strpos($referer, 'recordestimate') and $this->cookie->taskEffortFold !== false)
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
                $teamMember = helper::arrayColumn($task->team, 'account');
                if(in_array($currentAccount, $teamMember)) $taskEffortFold = 1;
            }
        }

        $this->view->title          = $this->lang->task->record;
        $this->view->task           = $task;
        $this->view->from           = $from;
        $this->view->orderBy        = $orderBy;
        $this->view->efforts        = $this->task->getTaskEstimate($taskID, '', '', $orderBy);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->taskEffortFold = $taskEffortFold;

        $this->display();
    }

    /**
     * Edit consumed and estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return void
     */
    public function editEstimate($estimateID)
    {
        $estimate = $this->task->getEstimateById($estimateID);
        if(!empty($_POST))
        {
            $changes = $this->task->updateEstimate($estimateID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $estimate->task, 'EditEstimate', $this->post->work);
            $this->action->logHistory($actionID, $changes);

            $url = $this->session->estimateList ? $this->session->estimateList : inlink('recordEstimate', "taskID={$estimate->task}");
            return print(js::locate($url, 'parent'));
        }

        $this->view->title      = $this->lang->task->editEstimate;
        $this->view->position[] = $this->lang->task->editEstimate;
        $this->view->estimate   = $estimate;
        $this->view->task       = $this->task->getById($estimate->objectID);
        $this->display();
    }

    /**
     * Delete estimate.
     *
     * @param  int    $estimateID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteEstimate($estimateID, $confirm = 'no')
    {
        $estimate = $this->task->getEstimateById($estimateID);
        $taskID   = $estimate->objectID;
        $task     = $this->task->getById($taskID);
        if($confirm == 'no' and $task->consumed - $estimate->consumed != 0)
        {
            return print(js::confirm($this->lang->task->confirmDeleteEstimate, $this->createLink('task', 'deleteEstimate', "estimateID=$estimateID&confirm=yes")));
        }
        elseif($confirm == 'no' and $task->consumed - $estimate->consumed == 0)
        {
            return print(js::confirm($this->lang->task->confirmDeleteLastEstimate, $this->createLink('task', 'deleteEstimate', "estimateID=$estimateID&confirm=yes")));
        }
        else
        {
            $changes = $this->task->deleteEstimate($estimateID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $taskID, 'DeleteEstimate');
            $this->action->logHistory($actionID, $changes);

            if($task->consumed - $estimate->consumed == 0)
            {
                $this->action->create('task', $taskID, 'Adjusttasktowait');
                return print(js::reload('parent.parent'));
            }

            return print(js::reload('parent'));
        }
    }

    /**
     * Finish a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function finish($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->finish($taskID, $extra);
            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }
            $files = $this->loadModel('file')->saveUpload('task', $taskID);

            $task = $this->task->getById($taskID);
            if($this->post->comment != '' or !empty($changes))
            {
                $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
                $actionID = $this->action->create('task', $taskID, 'Finished', $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);
            $this->loadModel('common')->syncPPEStatus($taskID);

            if($this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug", '', true);
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == "taskkanban")
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('result' => 'success', 'data' => $taskID));
            }
            else
            {
                return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $task         = $this->view->task;
        $members      = $task->team ? $this->task->getMemberPairs($task) : $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $task->nextBy = $task->openedBy;

        if(!empty($task->team))
        {
            $task->nextBy     = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $task->myConsumed = 0;
            $currentTeam      = $this->task->getTeamByAccount($task->team);
            if($currentTeam) $task->myConsumed = $currentTeam->consumed;
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->position[] = $this->lang->task->finish;
        $this->view->members    = $members;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Pause task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function pause($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->pause($taskID, $extra);
            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Paused', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task         = $this->task->getById($taskID);
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this'));
            }
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));

            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->pause;
        $this->view->position[] = $this->lang->task->pause;

        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Restart task
     *
     * @param  int    $taskID
     * @param  string $from
     * @access public
     * @return void
     */
    public function restart($taskID, $from = '')
    {
        $this->commonAction($taskID);

        $task = $this->task->getById($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->start($taskID);
            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $act = $this->post->left == 0 ? 'Finished' : 'Restarted';
            $actionID = $this->action->create('task', $taskID, $act, $this->post->comment);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task         = $this->task->getById($taskID);
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                if($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this'));
            }

            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->restart;
        $this->view->position[] = $this->lang->task->restart;

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->members    = $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $this->view->assignedTo = $task->assignedTo == '' ? $this->app->user->account : $task->assignedTo;
        $this->display();
    }

    /**
     * Close a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function close($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->close($taskID, $extra);

            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task      = $this->task->getById($taskID);
                $execution = $this->execution->getByID($task->execution);

                if(isset($task->fromIssue) and $task->fromIssue > 0)
                {
                    $fromIssue = $this->loadModel('issue')->getByID($task->fromIssue);
                    if($fromIssue->status != 'closed')
                    {
                        $confirmURL = $this->createLink('issue', 'close', "id=$task->fromIssue");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindIssue, $task->fromIssue), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }

                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->position[] = $this->lang->task->finish;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Batch cancel tasks.
     *
     * @param  string $skipTaskIdList
     * @access public
     * @return void
     */
    public function batchCancel()
    {
        if($this->post->taskIDList)
        {
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            unset($_POST['assignedTo']);
            $this->loadModel('action');

            $tasks = $this->task->getByList($taskIDList);
            foreach($tasks as $taskID => $task)
            {
                if($task->status == 'done' or $task->status == 'closed' or $task->status == 'cancel') continue;

                $changes = $this->task->cancel($taskID);
                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Canceled', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }

        return print(js::reload('parent'));
    }

    /**
     * Batch close tasks.
     *
     * @access public
     * @return void
     */
    public function batchClose($confirm = 0)
    {
        if($this->post->taskIDList or $confirm)
        {
            $taskIDList = $this->post->taskIDList;
            if(!isset($_POST['taskIDList']) && !empty($_SESSION['batchCloseTaskIDList']))
            {
                $taskIDList = explode(',', $this->session->batchCloseTaskIDList);
                unset($_SESSION['batchCloseTaskIDList']);
            }
            if($taskIDList) $taskIDList = array_unique($taskIDList);

            unset($_POST['taskIDList']);
            unset($_POST['assignedTo']);
            $this->loadModel('action');

            $tasks = $this->task->getByList($taskIDList);
            foreach($tasks as $taskID => $task)
            {
                if(($task->status != 'done' and $task->status != 'cancel') and empty($confirm))
                {
                    $skipTasks[$taskID] = $taskID;
                    continue;
                }

                /* Skip parent task when batch close task. */
                if($task->parent == '-1')
                {
                    $parentTasks[$taskID] = $taskID;
                    continue;
                }

                /* Skip closed task when batch close task. */
                if($task->status == 'closed') continue;

                $changes = $this->task->close($taskID);
                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Closed', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            if(isset($skipTasks) and empty($confirm))
            {
                $skipTasks  = join(',', $skipTasks);
                $confirmURL = $this->createLink('task', 'batchClose', "confirm=1");
                $cancelURL  = $this->server->HTTP_REFERER;
                $this->session->set('batchCloseTaskIDList', $skipTasks, 'task');
                return print(js::confirm(sprintf($this->lang->task->error->skipClose, $skipTasks), $confirmURL, $cancelURL, 'self', 'parent'));
            }

            if(isset($parentTasks))
            {
                $parentTasks = join(',', $parentTasks);
                return print(js::alert(sprintf($this->lang->task->error->closeParent, $parentTasks)) . js::reload('parent'));
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }

        return print(js::reload('parent'));
    }

    /**
     * Cancel a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function cancel($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->cancel($taskID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Canceled', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task         = $this->task->getById($taskID);
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this'));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->task->cancel;
        $this->view->position[] = $this->lang->task->cancel;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Activate a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function activate($taskID, $extra = '')
    {
        $this->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->activate($taskID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task         = $this->task->getById($taskID);
                $execution    = $this->execution->getByID($task->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == "taskkanban")
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this'));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        if(!isset($this->view->members[$this->view->task->finishedBy])) $this->view->members[$this->view->task->finishedBy] = $this->view->task->finishedBy;

        if(!empty($this->view->task->team))
        {
            $teamAccounts = helper::arrayColumn($this->view->task->team, 'account');
            $teamMembers  = array();
            foreach($this->view->members as $account => $name)
            {
                if(!$account or in_array($account, $teamAccounts)) $teamMembers[$account] = $name;
            }
            $this->view->teamMembers = $teamMembers;
        }
        $this->view->title      = $this->view->execution->name . $this->lang->colon . $this->lang->task->activate;
        $this->view->position[] = $this->lang->task->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Delete a task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $confirm yes|no
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function delete($executionID, $taskID, $confirm = 'no', $from = '')
    {
        $task = $this->task->getById($taskID);
        if($task->parent < 0) return print(js::alert($this->lang->task->cannotDeleteParent));

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->task->confirmDelete, inlink('delete', "executionID=$executionID&taskID=$taskID&confirm=yes&from=$from")));
        }
        else
        {
            $this->task->delete(TABLE_TASK, $taskID);
            if($task->parent > 0)
            {
                $this->task->updateParentStatus($task->id);
                $this->loadModel('action')->create('task', $task->parent, 'deleteChildrenTask', '', $taskID);
            }
            if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
            if($task->story) $this->loadModel('story')->setStage($task->story);

            $this->executeHooks($taskID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            if($from == 'taskkanban')
            {
                $execLaneType    = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban(\"task\", $kanbanData)"));
            }

            if($from == 'market') return print(js::locate($this->createLink('marketresearch', 'stage', "researchID=$task->project"), 'parent'));

            $locateLink = $this->session->taskList ? $this->session->taskList : $this->createLink('execution', 'task', "executionID={$task->execution}");
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * AJAX: return tasks of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserTasks($userID = '', $id = '', $status = 'wait,doing', $appendID = 0)
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $tasks          = $this->task->getUserTaskPairs($account, $status, '', $appendID);
        $suspendedTasks = $this->task->getUserSuspendedTasks($account);
        foreach($tasks as $taskid => $task)
        {
            if(isset($suspendedTasks[$taskid])) unset($tasks[$taskid]);
        }

        if($id) return print(html::select("tasks[$id]", $tasks, '', 'class="form-control"'));
        echo html::select('task', $tasks, '', 'class=form-control');
    }

    /**
     * AJAX: return execution tasks in html select.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionTasks($executionID, $taskID = 0)
    {
        $tasks = $this->task->getExecutionTaskPairs((int)$executionID);
        echo html::select('task', empty($tasks) ? array('' => '') : $tasks, $taskID, "class='form-control'");
    }

    /**
     * Ajax get tasks for execution list.
     *
     * @param  int    $executionID
     * @param  int    $maxTaskID
     * @access public
     * @return void
     */
    public function ajaxGetTasks($executionID, $maxTaskID = 0)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        $tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq('0')
            ->andWhere('status')->notin('closed,cancel')
            ->andWhere('parent')->le('0')
            ->andWhere('execution')->eq($executionID)
            ->andWhere('id')->gt($maxTaskID)
            ->orderBy('id_asc')
            ->limit(50)
            ->fetchAll('id');

        if(empty($tasks)) die('');

        $parentGroup = $this->dao->select('*')->from(TABLE_TASK)
            ->where('parent')->in(array_keys($tasks))
            ->andWhere('parent')->gt('0')
            ->andWhere('deleted')->eq('0')
            ->fetchGroup('parent', 'id');

        $users = $this->loadModel('user')->getPairs('noletter|nodeleted');
        foreach($tasks as $taskID => $task)
        {
            if(isset($parentGroup[$taskID]))
            {
                $tasks[$taskID]->children = $parentGroup[$taskID];
            }
            else
            {
                $tasks[$taskID]->children = array();
            }
        }

        $list  = '';
        $count = count($tasks);
        foreach($tasks as $task)
        {
            $showmore = ($count == 50) && ($task == end($tasks));
            $list    .= $this->task->buildNestedList($execution, $task, false, $showmore, $users);
        }

        die($list);
    }

    /**
     * AJAX: get the actions of a task. for web app.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetDetail($taskID)
    {
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);
        $this->display();
    }

    /**
     * The report page.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function report($executionID, $browseType = 'all', $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts   = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->task->$chartFunc();
                $chartOption = $this->lang->task->report->$chart;
                if(!empty($chartType) and $chartType != 'default') $chartOption->type = $chartType;
                $this->task->mergeChartOption($chart);
                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(!$execution->multiple) unset($this->lang->task->report->charts['tasksPerExecution']);

        $executions = $this->execution->getPairs();

        $this->execution->setMenu($executionID);
        $this->executions          = $executions;
        $this->view->title         = $this->executions[$executionID] . $this->lang->colon . $this->lang->task->report->common;
        $this->view->position[]    = $this->executions[$executionID];
        $this->view->position[]    = $this->lang->task->report->common;
        $this->view->executionID   = $executionID;
        $this->view->browseType    = $browseType;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';

        $this->display();
    }

    /**
     * get data to export
     *
     * @param  int $executionID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($executionID, $orderBy, $type)
    {
        $execution       = $this->execution->getById($executionID);
        $allExportFields = $this->config->task->exportFields;
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) $allExportFields = str_replace(' story,', '', $allExportFields);

        if($_POST)
        {
            $this->loadModel('file');
            $taskLang = $this->lang->task;
            if($type == 'bysearch') $this->config->task->datatable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getAllModulePairs');

            /* Create field lists. */
            $sort   = common::appendOrder($orderBy);
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $allExportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($taskLang->$fieldName) ? $taskLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get tasks. */
            $tasks = array();
            if($this->session->taskOnlyCondition)
            {
                $tasks = $this->dao->select('*')->from(TABLE_TASK)->alias('t1')->where($this->session->taskQueryCondition)
                    ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->post->checkedItem)->fi()
                    ->orderBy($sort)->fetchAll('id');

                foreach($tasks as $key => $task)
                {
                    /* Compute task progress. */
                    if($task->consumed == 0 and $task->left == 0)
                    {
                        $task->progress = 0;
                    }
                    elseif($task->consumed != 0 and $task->left == 0)
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
                $stmt = $this->dbh->query($this->session->taskQueryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->post->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $tasks[$row->id] = $row;
            }

            /* Get users and executions. */
            $users      = $this->loadModel('user')->getPairs('noletter');
            $projects   = $this->loadModel('project')->getPairs();
            $executions = $this->execution->getPairs(0, 'all', 'all|nocode');

            /* Get related objects id lists. */
            $relatedStoryIdList  = array();
            foreach($tasks as $task)
            {
                $relatedStoryIdList[$task->story] = $task->story;
                $relatedBugIdList[$task->fromBug] = $task->fromBug;
            }

            /* Get team for multiple task. */
            $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');

            /* Process multiple task info. */
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

            /* Get related objects title or names. */
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('task')->andWhere('objectID')->in(@array_keys($tasks))->andWhere('extra')->ne('editor')->fetchGroup('objectID');
            $relatedModules = $this->loadModel('tree')->getAllModulePairs('task');

            if($tasks)
            {
                $children = array();
                foreach($tasks as $task)
                {
                    if($task->parent > 0 and isset($tasks[$task->parent]))
                    {
                        $children[$task->parent][$task->id] = $task;
                        unset($tasks[$task->id]);
                    }
                }
                if(!empty($children))
                {
                    $position = 0;
                    foreach($tasks as $task)
                    {
                        $position ++;
                        if(isset($children[$task->id]))
                        {
                            array_splice($tasks, $position, 0, $children[$task->id]);
                            $position += count($children[$task->id]);
                        }
                    }
                }
            }

            if($type == 'group')
            {
                $stories    = $this->loadModel('story')->getExecutionStories($executionID);
                $groupTasks = array();
                foreach($tasks as $task)
                {
                    $task->storyTitle = isset($stories[$task->story]) ? $stories[$task->story]->title : '';
                    if(isset($task->team))
                    {
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

                            if($orderBy == 'assignedTo')
                            {
                                $cloneTask->assignedToRealName = zget($users, $team->account);
                                $cloneTask->assignedTo = $team->account;
                            }
                            if($orderBy == 'finishedBy')$cloneTask->finishedBy = $team->account;
                            $groupTasks[$team->account][] = $cloneTask;
                        }
                        if(!empty($task->left) and $orderBy == 'finishedBy') $groupTasks[$task->finishedBy][] = $task;
                    }
                    else
                    {
                        $groupTasks[$task->$orderBy][] = $task;
                    }
                }

                $tasks = array();
                foreach($groupTasks as $groupTask)
                {
                    foreach($groupTask as $task)$tasks[] = $task;
                }
            }

            $bugs = $this->loadModel('bug')->getByList($relatedBugIdList);
            foreach($tasks as $task)
            {
                if($this->post->fileType == 'csv')
                {
                    $task->desc = htmlspecialchars_decode($task->desc);
                    $task->desc = str_replace("<br />", "\n", $task->desc);
                    $task->desc = str_replace('"', '""', $task->desc);
                    $task->desc = str_replace('&nbsp;', ' ', $task->desc);
                }

                /* fill some field with useful value. */
                $task->story = isset($relatedStories[$task->story]) ? $relatedStories[$task->story] . "(#$task->story)" : '';

                $task->fromBug = empty($task->fromBug) ? '' : "#$task->fromBug " . $bugs[$task->fromBug]->title;

                if(isset($projects[$task->project]))                  $task->project      = $projects[$task->project] . "(#$task->project)";
                if(isset($executions[$task->execution]))              $task->execution    = $executions[$task->execution] . "(#$task->execution)";
                if(isset($taskLang->typeList[$task->type]))           $task->type         = $taskLang->typeList[$task->type];
                if(isset($taskLang->priList[$task->pri]))             $task->pri          = $taskLang->priList[$task->pri];
                if(isset($taskLang->statusList[$task->status]))       $task->status       = $this->processStatus('task', $task);
                if(isset($taskLang->reasonList[$task->closedReason])) $task->closedReason = $taskLang->reasonList[$task->closedReason];
                if(isset($relatedModules[$task->module]))             $task->module       = $relatedModules[$task->module] . "(#$task->module)";
                if(isset($taskLang->modeList[$task->mode]))           $task->mode         = $taskLang->modeList[$task->mode];

                if(isset($users[$task->openedBy]))     $task->openedBy     = $users[$task->openedBy];
                if(isset($users[$task->assignedTo]))   $task->assignedTo   = $users[$task->assignedTo];
                if(isset($users[$task->finishedBy]))   $task->finishedBy   = $users[$task->finishedBy];
                if(isset($users[$task->canceledBy]))   $task->canceledBy   = $users[$task->canceledBy];
                if(isset($users[$task->closedBy]))     $task->closedBy     = $users[$task->closedBy];
                if(isset($users[$task->lastEditedBy])) $task->lastEditedBy = $users[$task->lastEditedBy];

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
                if(!empty($task->team))   $task->name = '[' . $taskLang->multipleAB . '] ' . $task->name;

                $task->openedDate     = substr($task->openedDate,     0, 10);
                $task->assignedDate   = substr($task->assignedDate,   0, 10);
                $task->finishedDate   = substr($task->finishedDate,   0, 10);
                $task->canceledDate   = substr($task->canceledDate,   0, 10);
                $task->closedDate     = substr($task->closedDate,     0, 10);
                $task->lastEditedDate = substr($task->lastEditedDate, 0, 10);
                $task->estimate       = $task->estimate . $this->lang->execution->workHourUnit;
                $task->consumed       = $task->consumed . $this->lang->execution->workHourUnit;
                $task->left           = $task->left     . $this->lang->execution->workHourUnit;

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
            if($this->config->edition != 'open') list($fields, $tasks) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $tasks);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $tasks);
            $this->post->set('kind', 'task');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->app->loadLang('execution');
        $fileName      = $this->lang->task->common;
        $executionName = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch('name');
        if(isset($this->lang->execution->featureBar['task'][$type]))
        {
            $browseType = $this->lang->execution->featureBar['task'][$type];
        }
        else
        {
            $browseType = isset($this->lang->execution->statusSelects[$type]) ? $this->lang->execution->statusSelects[$type] : '';
        }

        $this->view->fileName        = $executionName . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $allExportFields;
        $this->view->customExport    = true;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->executionID     = $executionID;
        $this->display();
    }

    /**
     * Ajax get task by ID.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetByID($taskID)
    {
        $task     = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $realname = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($task->assignedTo)->fetch('realname');
        $task->assignedTo = $realname ? $realname : ($task->assignedTo == 'closed' ? 'Closed' : $task->assignedTo);
        if($task->story)
        {
            $this->app->loadLang('story');
            $stage = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($task->story)->andWhere('version')->eq($task->storyVersion)->fetch('stage');
            $task->storyStage = zget($this->lang->story->stageList, $stage);
        }
        echo json_encode($task);
    }

    /**
     * Update assign of multi task.
     *
     * @param  int    $requestID
     * @param  object $taskID
     * @param  string $kanbanGroup
     * @param  string $from
     * @access public
     * @return void
     */
    public function editTeam($executionID, $taskID, $kanbanGroup = 'default', $from = '')
    {
        $task = $this->task->getById($taskID);
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->updateTeam($taskID);

            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }

            $actionID = $this->action->create('task', $taskID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));
            if(isonlybody())
            {
                $execution = $this->execution->getByID($task->execution);

                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }

                if($from == 'taskkanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $this->session->execLaneType ? $this->session->execLaneType : 'all', 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }

                return print(js::closeModal('parent.parent', 'this'));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->task    = $task;
        $this->view->members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display('', 'editTeam');
    }
}
