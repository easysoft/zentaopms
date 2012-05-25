<?php
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
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
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('project');
        $this->loadModel('story');
    }

    /**
     * Create a task.
     * 
     * @param  int    $projectID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function create($projectID = 0, $storyID = 0)
    {
        $project   = $this->project->getById($projectID); 
        $taskLink  = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");
        $storyLink = $this->session->storyList ? $this->session->storyList : $this->createLink('project', 'story', "projectID=$projectID");
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $tasksID = $this->task->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            /* Create actions. */
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                $actionID = $this->action->create('task', $taskID, 'Opened', '');
                $this->sendmail($taskID, $actionID);
            }            

            /* Locate the browser. */
            if($this->post->after == 'continueAdding')
            {
                echo js::alert($this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding']);
                die(js::locate($this->createLink('task', 'create', "projectID=$projectID&storyID={$this->post->story}"), 'parent'));
            }
            elseif($this->post->after == 'toTastList')
            {
                die(js::locate($taskLink, 'parent'));
            }
            elseif($this->post->after == 'toStoryList')
            {
                die(js::locate($storyLink, 'parent'));
            }
        }

        $stories = $this->story->getProjectStoryPairs($projectID);
        $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[]      = html::a($taskLink, $project->name);
        $position[]      = $this->lang->task->create;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->project  = $project;
        $this->view->stories  = $stories;
        $this->view->storyID  = $storyID;
        $this->view->members  = $members;
        $this->display();
    }

    /**
     * Batch create task.
     * 
     * @param  int    $projectID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0, $storyID = 0)
    {
        $project   = $this->project->getById($projectID); 
        $taskLink  = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");
        $storyLink = $this->session->storyList ? $this->session->storyList : $this->createLink('project', 'story', "projectID=$projectID");
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $mails = $this->task->batchCreate($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            foreach($mails as $mail)
            {
                $this->sendmail($mail->taskID, $mail->actionID);
            }            

            /* Locate the browser. */
            die(js::locate($taskLink, 'parent'));
        }

        $stories = $this->story->getProjectStoryPairs($projectID);
        $stories['same'] = $this->lang->task->same;
        $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[]      = html::a($taskLink, $project->name);
        $position[]      = $this->lang->task->create;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->project  = $project;
        $this->view->stories  = $stories;
        $this->view->storyID  = $storyID;
        $this->view->members  = $members;
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
        $this->view->users   = $this->loadModel('user')->getPairs('noletter'); 
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
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('task', $taskID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }

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
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->lang->task->edit;
        $this->view->position[]    = $this->lang->task->edit;
        $this->view->stories       = $this->story->getProjectStoryPairs($this->view->project->id);
        $this->view->members       = $this->loadModel('user')->appendDeleted($this->view->members, $this->view->task->assignedTo);        
        
        $this->display();
    }

    /**
     * Batch edit task.
     * 
     * @param  int    $projectID 
     * @param  string $from example:projectTask, taskBatchEdit
     * @param  string $status 
     * @param  int    $param 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function batchEdit($projectID = 0, $from = '', $status = 'all', $param = 0, $orderBy = '')
    {
        /* Initialize vars. */
        $browseType      = strtolower($status);
        $queryID         = ($browseType == 'bysearch') ? (int)$param : 0;
        $showSuhosinInfo = false;
        $parentLink      = $this->session->taskList;
        $project         = $this->project->getById($projectID); 
        $allTasks        = array();
        $editedTasks     = array();
        $taskIDList      = array();
        $columns         = 13;
        if(!$orderBy) $orderBy = $this->cookie->projectTaskOrder ? $this->cookie->projectTaskOrder : 'status,id_desc';

        /* Set project menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        /* Get all tasks. */
        if($browseType != "bysearch")
        {
            $allTasks = $this->task->getProjectTasks($projectID, $status, $orderBy, null); 
        }
        else
        {   
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('taskQuery', $query->sql);
                    $this->session->set('taskForm', $query->form);
                }
                else
                {
                    $this->session->set('taskQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->taskQuery == false) $this->session->set('taskQuery', ' 1 = 1');
            }
            $taskQuery = str_replace("`project` = 'all'", '1', $this->session->taskQuery); // Search all project.
            $allTasks  = $this->project->getSearchTasks($taskQuery, null, $orderBy);
        }

        /* Get form data for project-task. */
        if($from == 'projectTask')
        {
            if($this->post->taskIDList) 
            {
                $taskIDList = $this->post->taskIDList;
            }
            elseif(!isset($this->post->taskIDList) and $this->session->taskIDList)
            {
                $taskIDList = $this->session->taskIDList;
            }

            /* Initialize the tasks whose need to edited. */
            foreach($allTasks as $task) if(in_array($task->id, $taskIDList)) $editedTasks[$task->id] = $task;

            $showSuhosinInfo = $this->loadModel('common')->judgeSuhosinSetting(count($editedTasks), $columns);

            /* Set the sessions. */
            $this->app->session->set('taskIDList', $taskIDList);
            $this->app->session->set('showSuhosinInfo', $showSuhosinInfo);
        }
        /* Get form data for task-batchEdit. */
        elseif($from == 'taskBatchEdit')
        {
            $allChanges = $this->task->batchUpdate();

            if(!empty($allChanges))
            {
                foreach($allChanges as $taskID => $changes)
                {
                    if(!empty($changes))
                    {
                        $actionID = $this->loadModel('action')->create('task', $taskID, 'Edited');
                        $this->action->logHistory($actionID, $changes);
                        $this->sendmail($taskID, $actionID);

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
            }
            die(js::locate($parentLink));
        }

        $this->view->header['title'] = $project->name . $this->lang->colon . $this->lang->task->batchEdit;
        $this->view->position[] = $this->lang->task->batchEdit;

        if($showSuhosinInfo) $this->view->suhosinInfo = $this->lang->suhosinInfo;
        $this->view->projectID   = $project->id;
        $this->view->editedTasks = $editedTasks;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->members     = $this->project->getTeamMemberPairs($projectID, 'nodeleted');

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
            $this->sendmail($taskID, $actionID);

            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon . $this->lang->task->assign;
        $this->view->position[]    = $this->lang->task->assign;

        $this->view->users = $this->project->getTeamMemberPairs($projectID);
        $this->display();
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

        /* Set menu. */
        $project = $this->project->getById($task->project);
        $this->project->setMenu($this->project->getPairs(), $project->id);

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->view;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$task->project"), $project->name);
        $position[]      = $this->lang->task->view;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->project  = $project;
        $this->view->task     = $task;
        $this->view->actions  = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
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
                $actionID = $this->action->create('task', $taskID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->start;
        $this->view->position[]    = $this->lang->task->start;
        $this->display();
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

            $task = $this->task->getById($taskID);
            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Finished', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }

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
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->position[]    = $this->lang->task->finish;
        
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
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->position[]    = $this->lang->task->finish;
        
        $this->display();

    }

    /**
     * Batch close tasks.
     * 
     * @access public
     * @return void
     */
    public function batchClose()
    {
        if($this->post->tasks)
        {
            $tasks = $this->post->tasks;
            unset($_POST['tasks']);
            $this->loadModel('action');

            foreach($tasks as $taskID)
            {
                $this->commonAction($taskID);
                $task = $this->task->getById($taskID);
                if($task->status == 'wait' or $task->status == 'doing') continue;

                $changes = $this->task->close($taskID);

                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Closed', '');
                    $this->action->logHistory($actionID, $changes);
                    $this->sendmail($taskID, $actionID);
                }
            }
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
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->cancel;
        $this->view->position[]    = $this->lang->task->cancel;
        
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
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->activate;
        $this->view->position[]    = $this->lang->task->activate;
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
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->task->confirmDelete, inlink('delete', "projectID=$projectID&taskID=$taskID&confirm=yes")));
        }
        else
        {
            $story = $this->dao->select('story')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('story');
            $this->task->delete(TABLE_TASK, $taskID);
            if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
            if($story) $this->loadModel('story')->setStage($story);
            die(js::locate($this->session->taskList, 'parent'));
        }
    }

    /**
     * Send email.
     * 
     * @param  int    $taskID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($taskID, $actionID)
    {
        /* Set toList and ccList. */
        $task        = $this->task->getById($taskID);
        $projectName = $this->project->getById($task->project)->name;
        $toList      = $task->assignedTo;
        $ccList      = trim($task->mailto, ',');

        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $task->finishedBy;
        }

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Create the email content. */
        $this->view->task   = $task;
        $this->view->action = $action;
        $this->clear();
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $projectName . ':' . 'TASK#' . $task->id . $this->lang->colon . $task->name, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
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

        if($id) die(html::select("tasks[$id]", $tasks, '', 'class="select-1 f-left"'));
        die(html::select('task', $tasks, '', 'class=select-1'));
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
        die(html::select('task', $tasks, $taskID));
    }

    /**
     * The report page.
     * 
     * @param  int    $projectID 
     * @param  string $browseType 
     * @access public
     * @return void
     */
    public function report($projectID, $browseType = 'all')
    {
        
        $this->loadModel('report');
        $this->view->charts   = array();
        $this->view->renderJS = '';

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->task->$chartFunc();
                $chartOption = $this->lang->task->report->$chart;
                $this->task->mergeChartOption($chart);

                $chartXML  = $this->report->createSingleXML($chartData, $chartOption->graph);
                $this->view->charts[$chart] = $this->report->createJSChart($chartOption->swf, $chartXML, $chartOption->width, $chartOption->height);
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
            $this->view->renderJS = $this->report->renderJsCharts(count($this->view->charts));
        }

        $this->project->setMenu($this->project->getPairs(), $projectID);
        $this->projects            = $this->project->getPairs();
        $this->view->header->title = $this->projects[$projectID] . $this->lang->colon . $this->lang->task->report->common;
        $this->view->projectID     = $projectID;
        $this->view->browseType    = $browseType;
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
    public function export($projectID, $orderBy)
    {
        if($_POST)
        {
            $taskLang   = $this->lang->task;
            $taskConfig = $this->config->task;

            /* Create field lists. */
            $fields = explode(',', $taskConfig->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($taskLang->$fieldName) ? $taskLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get tasks. */
            $tasks = $this->dao->select('*')->from(TABLE_TASK)->alias('t1')->where($this->session->taskReportCondition)->orderBy($orderBy)->fetchAll('id');

            /* Get users and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $projects = $this->loadModel('project')->getPairs('all');

            /* Get related objects id lists. */
            $relatedStoryIdList  = array();
            foreach($tasks as $task) $relatedStoryIdList[$task->story] = $task->story;

            /* Get related objects title or names. */
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('task')->andWhere('objectID')->in(@array_keys($tasks))->fetchGroup('objectID');

            foreach($tasks as $task)
            {
                if($this->post->fileType == 'csv')
                {
                    $task->desc = htmlspecialchars_decode($task->desc);
                    $task->desc = str_replace("<br />", "\n", $task->desc);
                    $task->desc = str_replace('"', '""', $task->desc);
                }

                /* fill some field with useful value. */
                $task->story = isset($relatedStories[$task->story]) ? $relatedStories[$task->story] : '';

                if(isset($projects[$task->project]))                  $task->project      = $projects[$task->project];
                if(isset($taskLang->typeList[$task->type]))           $task->type         = $taskLang->typeList[$task->type];
                if(isset($taskLang->priList[$task->pri]))             $task->pri          = $taskLang->priList[$task->pri];
                if(isset($taskLang->statusList[$task->status]))       $task->status       = $taskLang->statusList[$task->status];
                if(isset($taskLang->reasonList[$task->closedReason])) $task->closedReason = $taskLang->reasonList[$task->closedReason];

                if(isset($users[$task->openedBy]))     $task->openedBy     = $users[$task->openedBy];
                if(isset($users[$task->assignedTo]))   $task->assignedTo   = $users[$task->assignedTo];
                if(isset($users[$task->finishedBy]))   $task->finishedBy   = $users[$task->finishedBy];
                if(isset($users[$task->canceledBy]))   $task->canceledBy   = $users[$task->canceledBy];
                if(isset($users[$task->closedBy]))     $task->closedBy     = $users[$task->closedBy];
                if(isset($users[$task->lastEditedBy])) $task->lastEditedBy = $users[$task->lastEditedBy];

                $task->openedDate     = substr($task->openedDate,     0, 10);
                $task->assignedDate   = substr($task->assignedDate,   0, 10);
                $task->finishedDate   = substr($task->finishedDate,   0, 10);
                $task->canceledDate   = substr($task->canceledDate,   0, 10);
                $task->closedDate     = substr($task->closedDate,     0, 10);
                $task->lastEditedDate = substr($task->lastEditedDate, 0, 10);

                /* Set related files. */
                if(isset($relatedFiles[$task->id]))
                {
                    foreach($relatedFiles[$task->id] as $file)
                    {
                        $fileURL = 'http://' . $this->server->http_host . $this->config->webRoot . "data/upload/$task->company/" . $file->pathname;
                        $task->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                    }
                }
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $tasks);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }
}
