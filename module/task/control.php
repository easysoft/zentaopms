<?php
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        $this->loadModel('story');
        $this->loadModel('tree');

        if($this->config->global->flow == 'onlyTask')
        {
            $this->config->task->customCreateFields        = str_replace(array('story,'), '', $this->config->task->customCreateFields);
            $this->config->task->customBatchCreateFields   = str_replace(array('story,'), '', $this->config->task->customBatchCreateFields);
            $this->config->task->custom->batchCreateFields = str_replace(array('story,'), '', $this->config->task->custom->batchCreateFields);
        }
    }

    /**
     * Create a task.
     *
     * @param  int    $projectID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function create($projectID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $todoID = 0)
    {
        $this->project->getLimitedProject();
        $limitedProjects = !empty($_SESSION['limitedProjects']) ? $_SESSION['limitedProjects'] : '';
        if(strpos(",{$limitedProjects},", ",$projectID,") !== false)
        {
            echo js::alert($this->lang->task->createDenied);
            die(js::locate($this->createLink('project', 'task', "projectID=$projectID")));
        }

        $task = new stdClass();
        $task->module     = $moduleID;
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
            $task      = $this->task->getByID($taskID);
            $projectID = $task->project;
        }

        if($todoID > 0)
        {
            $todo = $this->loadModel('todo')->getById($todoID);
            $task->name = $todo->name;
            $task->pri  = $todo->pri;
            $task->desc = $todo->desc;
        }

        $project   = $this->project->getById($projectID);
        $taskLink  = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");
        $storyLink = $this->session->storyList ? $this->session->storyList : $this->createLink('project', 'story', "projectID=$projectID");

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            setcookie('lastTaskModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', false, false);
            if($this->post->project) $projectID = (int)$this->post->project;
            $tasksID = $this->task->create($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            /* if the count of tasksID is 1 then check exists. */
            if(count($tasksID) == 1)
            {
                $taskID = current($tasksID);
                if($taskID['status'] == 'exists')
                {
                    $response['locate']  = $this->createLink('task', 'view', "taskID={$taskID['id']}");
                    $response['message'] = sprintf($this->lang->duplicate, $this->lang->task->common);
                    $this->send($response);
                }
            }

            /* Create actions. */
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                /* if status is exists then this task has exists not new create. */
                if($taskID['status'] == 'exists') continue;

                $taskID   = $taskID['id'];
                $this->action->create('task', $taskID, 'Opened', '');
            }

            /* If link from no head then reload*/
            if(isonlybody())
            {
                $this->executeHooks($taskID);
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            }

            if($todoID > 0)
            {
                $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
                $this->action->create('todo', $todoID, 'finished', '', "TASK:$taskID");
            }

            $this->executeHooks($taskID);

            /* Locate the browser. */
            if($this->app->getViewType() == 'xhtml')
            {
                $taskLink  = $this->createLink('task', 'view', "taskID=$taskID");
                $response['locate'] = $taskLink;
                $this->send($response);
            }

            if($this->post->after == 'continueAdding')
            {
                $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
                $response['locate']  = $this->createLink('task', 'create', "projectID=$projectID&storyID={$this->post->story}&moduleID=$moduleID");
                $this->send($response);
            }
            elseif($this->post->after == 'toTaskList')
            {
                setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', false, false);
                $taskLink  = $this->createLink('project', 'task', "projectID=$projectID&status=unclosed&param=0&orderBy=id_desc");
                $response['locate'] = $taskLink;
                $this->send($response);
            }
            elseif($this->post->after == 'toStoryList')
            {
                $response['locate'] = $storyLink;
                $this->send($response);
            }
            else
            {
                $response['locate'] = $taskLink;
                $this->send($response);
            }
        }

        $users            = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $members          = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $showAllModule    = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($projectID, 0, 0, $showAllModule ? 'allModule' : '');
        $task->module     = $task->module ? $task->module : (int)$this->cookie->lastTaskModule;

        /* Fix bug #2737. When moduleID is not story module. */
        $moduleIdList = array();
        if($task->module)
        {
            $moduleID     = $this->tree->getStoryModule($task->module);
            $moduleIdList = $this->tree->getAllChildID($moduleID);
        }
        $stories = $this->story->getProjectStoryPairs($projectID, 0, 0, $moduleIdList);

        $title      = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[] = html::a($taskLink, $project->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->create;

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;
        if($project->type == 'ops') unset($customFields['story']);

        $this->view->customFields  = $customFields;
        $this->view->showFields    = $this->config->task->custom->createFields;
        $this->view->showAllModule = $showAllModule;

        $this->view->title            = $title;
        $this->view->position         = $position;
        $this->view->project          = $project;
        $this->view->projects         = $this->loadModel('project')->getPairs();
        $this->view->task             = $task;
        $this->view->users            = $users;
        $this->view->stories          = $stories;
        $this->view->testStoryIdList  = $this->loadModel('story')->getTestStories(array_keys($stories), $project->id);
        $this->view->members          = $members;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->display();
    }

    /**
     * Batch create task.
     *
     * @param int $projectID
     * @param int $storyID
     * @param int $iframe
     * @param int $taskID
     *
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $iframe = 0)
    {
        $this->project->getLimitedProject();
        $limitedProjects = !empty($_SESSION['limitedProjects']) ? $_SESSION['limitedProjects'] : '';
        if(strpos(",{$limitedProjects},", ",$projectID,") !== false)
        {
            echo js::alert($this->lang->task->createDenied);
            die(js::locate($this->createLink('project', 'task', "projectID=$projectID")));
        }

        $project   = $this->project->getById($projectID);
        $taskLink  = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");
        $storyLink = $this->session->storyList ? $this->session->storyList : $this->createLink('project', 'story', "projectID=$projectID");

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $mails = $this->task->batchCreate($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Locate the browser. */
            if(!empty($iframe)) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $storyLink));
        }

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchCreateFields;


        $stories = $this->story->getProjectStoryPairs($projectID, 0, 0, 0, 'short');
        $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');

        $showAllModule = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($projectID, 0, 0, $showAllModule ? 'allModule' : '');

        $title      = $project->name . $this->lang->colon . $this->lang->task->batchCreate;
        $position[] = html::a($taskLink, $project->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->batchCreate;

        if($taskID) $this->view->parentTitle = $this->dao->select('name')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('name');

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->project    = $project;
        $this->view->stories    = $stories;
        $this->view->modules    = $modules;
        $this->view->parent     = $taskID;
        $this->view->storyID    = $storyID;
        $this->view->story      = $this->story->getByID($storyID);
        $this->view->storyTasks = $this->task->getStoryTaskCounts(array_keys($stories), $projectID);
        $this->view->members    = $members;
        $this->view->moduleID   = $moduleID;
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
        $this->view->task    = $this->loadModel('task')->getByID($taskID);
        $this->view->project = $this->project->getById($this->view->task->project);
        $this->view->members = $this->project->getTeamMemberPairs($this->view->project->id ,'nodeleted');
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $this->view->project->id);
        $this->view->position[] = html::a($this->createLink('project', 'browse', "project={$this->view->task->project}"), $this->view->project->name);
    }

    /**
     * Edit a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function edit($taskID, $comment = false)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = array();
            $files   = array();
            if($comment == false)
            {
                $changes = $this->task->update($taskID);
                if(dao::isError()) die(js::error(dao::getError()));
                $files = $this->loadModel('file')->saveUpload('task', $taskID);
            }

            $task = $this->task->getById($taskID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = (!empty($changes) or !empty($files)) ? 'Edited' : 'Commented';
                $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
                $actionID = $this->action->create('task', $taskID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if($task->fromBug != 0)
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        $cancelURL  = $this->server->HTTP_REFERER;
                        die(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                    }
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                die(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $noclosedProjects = $this->project->getPairs('noclosed,nocode');
        unset($noclosedProjects[$this->view->project->id]);
        $this->view->projects = array($this->view->project->id => $this->view->project->name) + $noclosedProjects;
        $tasks = $this->task->getParentTaskPairs($this->view->project->id, $this->view->task->parent);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        if(!isset($this->view->members[$this->view->task->assignedTo])) $this->view->members[$this->view->task->assignedTo] = $this->view->task->assignedTo;
        if(isset($this->view->members['closed']) or $this->view->task->status == 'closed') $this->view->members['closed']  = 'Closed';

        $this->view->title         = $this->lang->task->edit . 'TASK' . $this->lang->colon . $this->view->task->name;
        $this->view->position[]    = $this->lang->task->common;
        $this->view->position[]    = $this->lang->task->edit;
        $this->view->stories       = $this->story->getProjectStoryPairs($this->view->project->id);
        $this->view->tasks         = $tasks;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted', "{$this->view->task->openedBy},{$this->view->task->canceledBy},{$this->view->task->closedBy}");
        $this->view->showAllModule = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
        $this->view->modules       = $this->tree->getTaskOptionMenu($this->view->task->project, 0, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->display();
    }

    /**
     * Batch edit task.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchEdit($projectID = 0)
    {
        if($this->post->names)
        {
            $allChanges = $this->task->batchUpdate();
            if(dao::isError()) die(js::error(dao::getError()));

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
                                $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                                $cancelURL  = $this->server->HTTP_REFERER;
                                die(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                            }
                        }
                    }
                }
            }
            $this->loadModel('score')->create('ajax', 'batchOther');
            die(js::locate($this->session->taskList, 'parent'));
        }

        $taskIDList = $this->post->taskIDList ? $this->post->taskIDList : die(js::locate($this->session->taskList, 'parent'));
        $taskIDList = array_unique($taskIDList);

        /* The tasks of project. */
        if($projectID)
        {
            $project = $this->project->getById($projectID);
            $this->project->setMenu($this->project->getPairs(), $project->id);

            /* Set modules and members. */
            $showAllModule = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
            $modules       = $this->tree->getTaskOptionMenu($projectID, 0, 0, $showAllModule ? 'allModule' : '');
            $modules       = array('ditto' => $this->lang->task->ditto) + $modules;

            $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
            $members = array('' => '', 'ditto' => $this->lang->task->ditto) + $members;
            $members['closed'] = 'Closed';

            $this->view->title      = $project->name . $this->lang->colon . $this->lang->task->batchEdit;
            $this->view->position[] = html::a($this->createLink('project', 'browse', "project=$project->id"), $project->name);
            $this->view->project    = $project;
            $this->view->modules    = $modules;
            $this->view->members    = $members;
        }
        /* The tasks of my. */
        else
        {
            $this->lang->task->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.task', 'my');
            $this->lang->task->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->position[] = html::a($this->createLink('my', 'task'), $this->lang->my->task);
            $this->view->title      = $this->lang->task->batchEdit;
            $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        }

        /* Get edited tasks. */
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIDList)->fetchAll('id');
        $teams = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($taskIDList)->andWhere('type')->eq('task')->fetchGroup('root', 'account');

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($tasks) * (count(explode(',', $this->config->task->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customBatchEditFields) as $field) $customFields[$field] = $this->lang->task->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->batchEditFields;

        /* Assign. */
        $this->view->position[]  = $this->lang->task->common;
        $this->view->position[]  = $this->lang->task->batchEdit;
        $this->view->projectID   = $projectID;
        $this->view->priList     = array('0' => '', 'ditto' => $this->lang->task->ditto) + $this->lang->task->priList;
        $this->view->statusList  = array('' => '',  'ditto' => $this->lang->task->ditto) + $this->lang->task->statusList;
        $this->view->typeList    = array('' => '',  'ditto' => $this->lang->task->ditto) + $this->lang->task->typeList;
        $this->view->taskIDList  = $taskIDList;
        $this->view->tasks       = $tasks;
        $this->view->teams       = $teams;
        $this->view->projectName = isset($project) ? $project->name : '';

        $this->display();
    }

    /**
     * Update assign of task
     *
     * @param  int    $requestID
     * @access public
     * @return void
     */
    public function assignTo($projectID, $taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->assign($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('task', $taskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $task = $this->task->getByID($taskID);

        $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');

        /* Compute next assignedTo. */
        if(!empty($task->team))
        {
            $task->nextUser = $this->task->getNextUser(array_keys($task->team), $task->assignedTo);
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) or $task->status == 'closed') $members['closed'] = 'Closed';

        $this->view->title      = $this->view->project->name . $this->lang->colon . $this->lang->task->assign;
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
            if(dao::isError()) die(js::error(dao::getError()));
            foreach($allChanges as $taskID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('task', $taskID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }
        die(js::reload('parent'));
    }

    /**
     * Batch update assign of task.
     *
     * @param  int    $project
     * @access public
     * @return void
     */
    public function batchAssignTo($project)
    {
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            if(!is_array($taskIDList)) die(js::locate($this->createLink('project', 'task', "projectID=$project"), 'parent'));
            $taskIDList = array_unique($taskIDList);

            $muletipleTasks = $this->dao->select('root , account')->from(TABLE_TEAM)->where('type')->eq('task')->andWhere('root')->in($taskIDList)->fetchGroup('root', 'account');
            $tasks          = $this->task->getByList($taskIDList);
            $this->loadModel('action');
            foreach($tasks as $taskID => $task)
            {
                if(isset($muletipleTasks[$taskID]) and $task->assignedTo != $this->app->user->account) continue; 
                if(isset($muletipleTasks[$taskID]) and !isset($muletipleTasks[$taskID][$this->post->assignedTo])) continue;

                $changes = $this->task->assign($taskID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('task', $taskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
            die(js::reload('parent'));
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
        $task = $this->task->getById($taskID, true);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));

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
            $story = $this->story->getById($task->story);
            $task->storySpec     = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->spec);
            $task->storyVerify   = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->verify);
            $task->storyFiles    = $this->loadModel('file')->getByObject('story', $task->story);
        }

        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        /* Set menu. */
        $project = $this->project->getById($task->project);
        $this->project->setMenu($this->project->getPairs(), $project->id);

        $this->executeHooks($taskID);

        $title      = "TASK#$task->id $task->name / $project->name";
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$task->project"), $project->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->view;

        $this->view->title       = $title;
        $this->view->position    = $position;
        $this->view->project     = $project;
        $this->view->task        = $task;
        $this->view->actions     = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('task', $taskID);
        $this->view->product     = $this->tree->getProduct($task->module);
        $this->view->modulePath  = $this->tree->getParents($task->module);
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

        die(js::reload('parent'));
    }

    /**
     * Start a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->start($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $act = $this->post->left == 0 ? 'Finished' : 'Started';
                $actionID = $this->action->create('task', $taskID, $act, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            /* Remind whether to update status of the bug, if task which from that bug has been finished. */
            $task = $this->task->getById($taskID);

            $this->executeHooks($taskID);

            if($changes and $this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status' and $change['new'] == 'done')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        die(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->start;
        $this->view->position[] = $this->lang->task->start;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Record consumed and estimate.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function recordEstimate($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $changes = $this->task->recordEstimate($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

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
                        die(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->session->set('estimateList', $this->app->getURI(true));
        if(isonlybody() && $this->config->requestType != 'GET') $this->session->set('estimateList', $this->app->getURI(true) . '?onlybody=yes');

        $this->view->task      = $this->task->getById($taskID);
        $this->view->estimates = $this->task->getTaskEstimate($taskID);
        $this->view->title     = $this->lang->task->record;
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
            if(dao::isError()) die(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $estimate->task, 'EditEstimate', $this->post->work);
            $this->action->logHistory($actionID, $changes);

            $url = $this->session->estimateList ? $this->session->estimateList : inlink('record', "taskID={$estimate->task}");
            die(js::locate($url, 'parent'));
        }

        $estimate = $this->task->getEstimateById($estimateID);

        $this->view->title      = $this->lang->task->editEstimate;
        $this->view->position[] = $this->lang->task->editEstimate;
        $this->view->estimate   = $estimate;
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
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->task->confirmDeleteEstimate, $this->createLink('task', 'deleteEstimate', "estimateID=$estimateID&confirm=yes")));
        }
        else
        {
            $estimate = $this->task->getEstimateById($estimateID);
            $changes  = $this->task->deleteEstimate($estimateID);
            if(dao::isError()) die(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $estimate->task, 'DeleteEstimate');
            $this->action->logHistory($actionID, $changes);
            die(js::reload('parent'));
        }
    }

    /**
     * Finish a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function finish($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->finish($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('task', $taskID);

            $task = $this->task->getById($taskID);
            if($this->post->comment != '' or !empty($changes))
            {
                $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
                $actionID = $this->action->create('task', $taskID, 'Finished', $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if($this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        die(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                die(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $task         = $this->view->task;
        $members      = $this->loadModel('project')->getTeamMemberPairs($task->project, 'nodeleted');
        $task->nextBy = $task->openedBy;

        $this->view->users = $members;
        if(!empty($task->team))
        {
            $teams = array_keys($task->team);

            $task->nextBy     = $this->task->getNextUser($teams, $task->assignedTo);
            $task->myConsumed = $task->team[$task->assignedTo]->consumed;

            $lastAccount = end($teams);
            if($lastAccount != $task->assignedTo)
            {
                $members = $this->task->getMemberPairs($task);
            }
            else
            {
                $task->nextBy = $task->openedBy;
            }
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->position[] = $this->lang->task->finish;
        $this->view->members    = $members;

        $this->display();
    }

    /**
     * Pause task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function pause($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->pause($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Paused', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->pause;
        $this->view->position[] = $this->lang->task->pause;

        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Restart task
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function restart($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->start($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $act = $this->post->left == 0 ? 'Finished' : 'Restarted';
                $actionID = $this->action->create('task', $taskID, $act, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->restart;
        $this->view->position[] = $this->lang->task->restart;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Close a task.
     *
     * @param  int      $taskID
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->close($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                die(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->finish;
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

        die(js::reload('parent'));
    }

    /**
     * Batch close tasks.
     *
     * @access public
     * @return void
     */
    public function batchClose($skipTaskIdList = '')
    {
        if($this->post->taskIDList or $skipTaskIdList)
        {
            $taskIDList = $this->post->taskIDList;
            if($taskIDList)     $taskIDList = array_unique($taskIDList);
            if($skipTaskIdList) $taskIDList = $skipTaskIdList;

            unset($_POST['taskIDList']);
            unset($_POST['assignedTo']);
            $this->loadModel('action');

            $tasks = $this->task->getByList($taskIDList);
            foreach($tasks as $taskID => $task)
            {
                if(empty($skipTaskIdList) and ($task->status == 'wait' or $task->status == 'doing'))
                {
                    $skipTasks[$taskID] = $taskID;
                    continue;
                }

                /* Skip parent task when batch close task. */
                if($task->parent == '-1') continue;

                /* Skip closed task when batch close task. */
                if($task->status == 'closed') continue;

                $changes = $this->task->close($taskID);
                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Closed', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            if(isset($skipTasks) and empty($skipTaskIdList))
            {
                $skipTasks = join(',', $skipTasks);
                $confirmURL = $this->createLink('task', 'batchClose', "skipTaskIdList=$skipTasks");
                $cancelURL  = $this->server->HTTP_REFERER;
                die(js::confirm(sprintf($this->lang->task->error->skipClose, $skipTasks), $confirmURL, $cancelURL, 'self', 'parent'));
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }
        die(js::reload('parent'));
    }

    /**
     * Cancel a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function cancel($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->cancel($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Canceled', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->cancel;
        $this->view->position[] = $this->lang->task->cancel;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Activate a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->activate($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        if(!isset($this->view->members[$this->view->task->finishedBy])) $this->view->members[$this->view->task->finishedBy] = $this->view->task->finishedBy;
        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->task->activate;
        $this->view->position[] = $this->lang->task->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Delete a task.
     *
     * @param  int    $projectID
     * @param  int    $taskID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($projectID, $taskID, $confirm = 'no')
    {
        $task = $this->task->getById($taskID);
        if($task->parent < 0) die(js::alert($this->lang->task->cannotDeleteParent));

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->task->confirmDelete, inlink('delete', "projectID=$projectID&taskID=$taskID&confirm=yes")));
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

            die(js::locate($this->session->taskList, 'parent'));
        }
    }

    /**
     * AJAX: return tasks of a user in html select.
     *
     * @param  string $account
     * @param  string $id
     * @param  string $status
     * @access public
     * @return string
     */
    public function ajaxGetUserTasks($account = '', $id = '', $status = 'wait,doing')
    {
        if($account == '') $account = $this->app->user->account;
        $tasks = $this->task->getUserTaskPairs($account, $status);

        if($id) die(html::select("tasks[$id]", $tasks, '', 'class="form-control"'));
        die(html::select('task', $tasks, '', 'class=form-control'));
    }

    /**
     * AJAX: return project tasks in html select.
     *
     * @param  int    $projectID
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function ajaxGetProjectTasks($projectID, $taskID = 0)
    {
        $tasks = $this->task->getProjectTaskPairs((int)$projectID);
        die(html::select('task', empty($tasks) ? array('' => '') : $tasks, $taskID, "class='form-control'"));
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
     * @param  int    $projectID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function report($projectID, $browseType = 'all', $chartType = 'default')
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

        $this->project->setMenu($this->project->getPairs(), $projectID);
        $this->projects            = $this->project->getPairs();
        $this->view->title         = $this->projects[$projectID] . $this->lang->colon . $this->lang->task->report->common;
        $this->view->position[]    = $this->projects[$projectID];
        $this->view->position[]    = $this->lang->task->report->common;
        $this->view->projectID     = $projectID;
        $this->view->browseType    = $browseType;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';

        $this->display();
    }

    /**
     * get data to export
     *
     * @param  int $projectID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($projectID, $orderBy, $type)
    {
        $project = $this->project->getById($projectID);
        $allExportFields = $this->config->task->exportFields;
        if($project->type == 'ops') $allExportFields = str_replace(' story,', '', $allExportFields);

        if($_POST)
        {
            $this->loadModel('file');
            $taskLang = $this->lang->task;

            /* Create field lists. */
            $sort   = $this->loadModel('common')->appendOrder($orderBy);
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
                    ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
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
                $stmt = $this->dbh->query($this->session->taskQueryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $tasks[$row->id] = $row;
            }

            /* Get users and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $projects = $this->loadModel('project')->getPairs('all|nocode');

            /* Get related objects id lists. */
            $relatedStoryIdList  = array();
            foreach($tasks as $task) $relatedStoryIdList[$task->story] = $task->story;

            /* Get team for multiple task. */
            $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)
                ->where('root')->in(array_keys($tasks))
                ->andWhere('type')->eq('task')
                ->fetchGroup('root');

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
                $stories    = $this->loadModel('story')->getProjectStories($projectID);
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

            foreach($tasks as $task)
            {
                if($this->post->fileType == 'csv')
                {
                    $task->desc = htmlspecialchars_decode($task->desc);
                    $task->desc = str_replace("<br />", "\n", $task->desc);
                    $task->desc = str_replace('"', '""', $task->desc);
                }

                /* fill some field with useful value. */
                $task->story = isset($relatedStories[$task->story]) ? $relatedStories[$task->story] . "(#$task->story)" : '';

                if(isset($projects[$task->project]))                  $task->project      = $projects[$task->project] . "(#$task->project)";
                if(isset($taskLang->typeList[$task->type]))           $task->type         = $taskLang->typeList[$task->type];
                if(isset($taskLang->priList[$task->pri]))             $task->pri          = $taskLang->priList[$task->pri];
                if(isset($taskLang->statusList[$task->status]))       $task->status       = $this->processStatus('task', $task);
                if(isset($taskLang->reasonList[$task->closedReason])) $task->closedReason = $taskLang->reasonList[$task->closedReason];
                if(isset($relatedModules[$task->module]))             $task->module       = $relatedModules[$task->module] . "(#$task->module)";

                if(isset($users[$task->openedBy]))     $task->openedBy     = $users[$task->openedBy];
                if(isset($users[$task->assignedTo]))   $task->assignedTo   = $users[$task->assignedTo];
                if(isset($users[$task->finishedBy]))   $task->finishedBy   = $users[$task->finishedBy];
                if(isset($users[$task->canceledBy]))   $task->canceledBy   = $users[$task->canceledBy];
                if(isset($users[$task->closedBy]))     $task->closedBy     = $users[$task->closedBy];
                if(isset($users[$task->lastEditedBy])) $task->lastEditedBy = $users[$task->lastEditedBy];

                if($task->parent > 0 && strpos($task->name, htmlentities('>')) !== 0) $task->name = '>' . $task->name;
                if(!empty($task->team))   $task->name = '[' . $taskLang->multipleAB . '] ' . $task->name;

                $task->openedDate     = substr($task->openedDate,     0, 10);
                $task->assignedDate   = substr($task->assignedDate,   0, 10);
                $task->finishedDate   = substr($task->finishedDate,   0, 10);
                $task->canceledDate   = substr($task->canceledDate,   0, 10);
                $task->closedDate     = substr($task->closedDate,     0, 10);
                $task->lastEditedDate = substr($task->lastEditedDate, 0, 10);

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
            if(isset($this->config->bizVersion)) list($fields, $tasks) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $tasks);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $tasks);
            $this->post->set('kind', 'task');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->app->loadLang('project');
        $fileName = $this->lang->task->common;
        $projectName = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('name');
        if(isset($this->lang->project->featureBar['task'][$type]))
        {
            $browseType = $this->lang->project->featureBar['task'][$type];
        }
        else
        {
            $browseType = isset($this->lang->project->statusSelects[$type]) ? $this->lang->project->statusSelects[$type] : '';
        }

        $this->view->fileName        = $projectName . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $allExportFields;
        $this->view->customExport    = true;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->projectID       = $projectID;
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
        die(json_encode($task));
    }
}
