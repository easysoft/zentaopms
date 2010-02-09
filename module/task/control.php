<?php
/**
 * The control file of task module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
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
            $taskID = $this->task->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            $this->action->create('task', $taskID, 'Opened', '');
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
        $members = $this->project->getTeamMemberPairs($projectID);

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->task->create;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('project',  $project);
        $this->assign('stories',  $stories);
        $this->assign('storyID',  $storyID);
        $this->assign('members',  $members);
        $this->display();
    }

    /* 导入任务。*/
    //public function import($projectID)
    //{
    //    /* 设置菜单。*/
    //    $this->project->setMenu($this->project->getPairs(), $projectID);

    //    $relatedProjects = $this->project->getRelatedProjects($projectID);
    //    $tasks = array();
    //    foreach($relatedProjects as $projectID => $projectName) $tasks = array_merge($tasks, $this->task->getProjectTasks($projectID));
    //    $this->view->header->title = $this->lang->task->import;
    //    $this->view->position      = $this->lang->task->import;
    //    $this->view->tasks = $tasks;
    //    $this->display();
    //}

    /* 编辑任务。*/
    public function edit($taskID)
    {
        $task = $this->task->getById($taskID);
        $project = $this->project->getById($task->project);

        /* 设置菜单。*/
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('task', $taskID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $stories = $this->story->getProjectStoryPairs($project->id);
        $members = $this->project->getTeamMemberPairs($project->id);

        $header['title'] = $this->lang->task->edit;
        $position[]      = html::a($this->createLink('project', 'browse', "project=$task->project"), $project->name);
        $position[]      = $this->lang->task->edit;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('project',  $project);
        $this->assign('stories',  $stories);
        $this->assign('members',  $members);
        $this->assign('task',     $task);
        $this->display();
    }

    /* 查看任务。*/
    public function view($taskID)
    {
        $this->loadModel('action');
        $task = $this->task->getById($taskID);
        $project = $this->project->getById($task->project);

        /* 设置菜单。*/
        $this->project->setMenu($this->project->getPairs(), $project->id);

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->view;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$task->project"), $project->name);
        $position[]      = $this->lang->task->view;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('project',  $project);
        $this->assign('task',     $task);
        $this->assign('actions',  $this->action->getList('task', $taskID));
        $this->assign('users',    $this->loadModel('user')->getPairs('noletter'));
        $this->display();
    }

    /* 删除一个任务。*/
    public function delete($projectID, $taskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->task->confirmDelete, $this->createLink('task', 'delete', "projectID=$projectID&taskID=$taskID&confirm=yes"));
            exit;
        }
        else
        {
            $this->task->delete($taskID);
            echo js::locate($this->createLink('project', 'browse', "projectID=$projectID"), 'parent');
            exit;
        }
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
