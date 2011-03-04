<?php
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
        $project = $this->project->getById($projectID); 
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");

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
                die(js::locate($browseProjectLink, 'parent'));
            }
            elseif($this->post->after == 'toStoryList')
            {
                die(js::locate($this->createLink('project', 'story', "projectID=$projectID"), 'parent'));
            }
        }

        $stories = $this->story->getProjectStoryPairs($projectID);
        $members = $this->project->getTeamMemberPairs($projectID, 'nodeleted');

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->task->create;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->project  = $project;
        $this->view->stories  = $stories;
        $this->view->storyID  = $storyID;
        $this->view->members  = $members;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter, noclosed, nodeleted');
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
        $this->view->task    = $this->task->getByID($taskID);
        $this->view->project = $this->project->getById($this->view->task->project);
        $this->view->members = $this->project->getTeamMemberPairs($this->view->project->id ,'nodeleted');
        $this->view->users   = $this->loadModel('user')->getPairs('noletter, noclosed, nodeleted'); 
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
    public function edit($taskID)
    {
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('task', $taskID);

            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('task', $taskID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
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
     * View a task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        $task = $this->task->getById($taskID);
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

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Finished', $this->post->comment);
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
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->task->confirmDelete, inlink('delete', "projectID=$projectID&taskID=$taskID&confirm=yes")));
        }
        else
        {
            $story = $this->dao->select('story')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('story');
            $this->task->delete(TABLE_TASK, $taskID);
            if($story) $this->loadModel('story')->setStage($story);
            die(js::locate($this->session->taskList, 'parent'));
        }
    }

    /**
     * Send email.
     * 
     * @param  int    $taskID 
     * @param  int    $actionID 
     * @access private
     * @return void
     */
    private function sendmail($taskID, $actionID)
    {
        /* Set toList and ccList. */
        $task   = $this->task->getByID($taskID);
        $toList = $task->assignedTo;
        $ccList = trim($task->mailto, ',');

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
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Create the email content. */
        $this->view->task   = $task;
        $this->view->action = $action;
        $this->clear();
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, 'TASK#' . $task->id . $this->lang->colon . $task->name, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
    
    /**
     * AJAX: return tasks of a user in html select. 
     * 
     * @param  string $account 
     * @param  string $status 
     * @access public
     * @return string
     */
    public function ajaxGetUserTasks($account = '', $status = 'wait,doing')
    {
        if($account == '') $account = $this->app->user->account;
        $tasks = $this->task->getUserTaskPairs($account, $status);
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
}
