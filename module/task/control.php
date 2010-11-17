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
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('project');
        $this->loadModel('story');
    }

    /* 添加任务。*/
    public function create($projectID = 0, $storyID = 0)
    {
        $project = $this->project->getById($projectID); 
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");

        /* 设置菜单。*/
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $tasksID = $this->task->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                $actionID = $this->action->create('task', $taskID, 'Opened', '');
                $this->sendmail($taskID, $actionID);
            }            
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

    /* 公共的操作。*/
    public function commonAction($taskID)
    {
        $this->view->task    = $this->task->getByID($taskID);
        $this->view->project = $this->project->getById($this->view->task->project);
        $this->view->members = $this->project->getTeamMemberPairs($this->view->project->id ,'nodeleted');
        $this->view->users   = $this->loadModel('user')->getPairs('noletter, noclosed, nodeleted'); 
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);

        /* 设置菜单。*/
        $this->project->setMenu($this->project->getPairs(), $this->view->project->id);
        $this->view->position[] = html::a($this->createLink('project', 'browse', "project={$this->view->task->project}"), $this->view->project->name);

    }

    /* 编辑任务。*/
    public function edit($taskID)
    {
        /* 执行公共的操作。*/
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

        /* 赋值。*/
        $this->view->header->title = $this->lang->task->edit;
        $this->view->position[]    = $this->lang->task->edit;
        $this->view->stories       = $this->story->getProjectStoryPairs($this->view->project->id);
        $this->view->members       = $this->loadModel('user')->appendDeleted($this->view->members, $this->view->task->owner);        
        
        $this->display();
    }

    /* 记录工时。*/
    public function logEfforts($taskID)
    {
        /* 执行公共的操作。*/
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

        /* 导航信息。*/
        $this->view->header->title = $this->lang->task->logEfforts;
        $this->view->position[]    = $this->lang->task->logEfforts;

        $this->display();
    }

    /* 查看任务。*/
    public function view($taskID)
    {
        $this->loadModel('action');
        $task = $this->task->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));

        $project = $this->project->getById($task->project);

        /* 设置菜单。*/
        $this->project->setMenu($this->project->getPairs(), $project->id);

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->view;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$task->project"), $project->name);
        $position[]      = $this->lang->task->view;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->project  = $project;
        $this->view->task     = $task;
        $this->view->actions  = $this->action->getList('task', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /* 确认需求变动。*/
    public function confirmStoryChange($taskID)
    {
        $task = $this->task->getById($taskID);
        $this->dao->update(TABLE_TASK)->set('storyVersion')->eq($task->latestStoryVersion)->where('id')->eq($taskID)->exec();
        $this->loadModel('action')->create('task', $taskID, 'confirmed', '', $task->latestStoryVersion);
        die(js::reload('parent'));
    }

    /* 开始一个任务。*/
    public function start($taskID)
    {
        /* 执行公共的操作。*/
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->changeStatus($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Started' : 'Commented';
                $actionID = $this->action->create('task', $taskID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        /* 赋值。*/
        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->start;
        $this->view->position[]    = $this->lang->task->start;
        $this->display();
    }
    
    /* 完成一个任务。*/
    public function complete($taskID)
    {
        /* 执行公共的操作。*/
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->changeStatus($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Finished' : 'Commented';
                $actionID = $this->action->create('task', $taskID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        /* 赋值。*/
        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->complete;
        $this->view->position[]    = $this->lang->task->complete;
        
        $this->display();
    }

    /* 取消一个任务。*/
    public function cancel($taskID)
    {
        /* 执行公共的操作。*/
        $this->commonAction($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->changeStatus($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Canceled' : 'Commented';
                $actionID = $this->action->create('task', $taskID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        /* 赋值。*/
        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->cancel;
        $this->view->position[]    = $this->lang->task->cancel;
        
        $this->display();
    }

    /* 删除一个任务。*/
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

    /* 发送邮件。*/
    private function sendmail($taskID, $actionID)
    {
        /* 设定toList和ccList。*/
        $task   = $this->task->getByID($taskID);
        $toList = $task->owner;
        
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

         /* 获得action信息。*/
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* 赋值，获得邮件内容。*/
        $this->view->task   = $task;
        $this->view->action = $action;
        $this->clear();
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* 发信。*/
        $this->loadModel('mail')->send($toList, 'TASK#' . $task->id . $this->lang->colon . $task->name, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
    
    /* Ajax请求： 返回用户任务的下拉列表框。*/
    public function ajaxGetUserTasks($account = '', $status = 'wait,doing')
    {
        if($account == '') $account = $this->app->user->account;
        $tasks = $this->task->getUserTaskPairs($account, $status);
        die(html::select('task', $tasks, '', 'class=select-1'));
    }

    /* Ajax请求： 返回项目任务的下拉列表框。*/
    public function ajaxGetProjectTasks($projectID, $taskID = 0)
    {
        $tasks = $this->task->getProjectTaskPairs((int)$projectID);
        die(html::select('task', $tasks, $taskID));
    }
}
