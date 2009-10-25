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
 * @version     $Id: control.php 1425 2009-10-17 09:21:36Z wwccss $
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

        if(!empty($_POST))
        {
            $this->task->create($projectID);
            die(js::locate($browseProjectLink, 'parent'));
        }

        $stories = $this->story->getProjectStoryPair($projectID);
        $members = $this->project->getTeamMemberPair($projectID);
        $stories = array(0  => '') + $stories;
        $members = array('' => '') + $members;

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

    /* 编辑任务。*/
    public function edit($taskID)
    {
        $task = $this->task->getById($taskID);
        $project = $this->project->getById($task->project);
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$project->id&tab=task");

        if(!empty($_POST))
        {
            $this->task->update($taskID);
            die(js::locate($browseProjectLink, 'parent'));
        }

        $stories = $this->story->getProjectStoryPair($project->id);
        $members = $this->project->getTeamMemberPair($project->id);
        $stories = array(0  => '') + $stories;
        $members = array('' => '') + $members;

        $header['title'] = $project->name . $this->lang->colon . $this->lang->task->edit;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->task->edit;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('project',  $project);
        $this->assign('stories',  $stories);
        $this->assign('members',  $members);
        $this->assign('task',     $task);
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
}
