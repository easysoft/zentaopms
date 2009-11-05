<?php
/**
 * The control file of dashboard module of ZenTaoMS.
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
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class my extends control
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
    }

    /* 首页，暂时跳转到待办事宜。*/
    public function index()
    {
        $this->locate($this->createLink('my', 'todo'));
    }

    /* 用户的todo列表。*/
    public function todo($date = 'today', $account = '', $status = 'all')
    {
        /* 加载todo model。*/
        $this->loadModel('todo');

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $position[]      = $this->lang->my->todo;

        $todos = $this->todo->getList($date, $account, $status);
        if((int)$date == 0) $date = $this->todo->today();

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'todo');
        $this->assign('dates',    $this->todo->buildDateList()); 
        $this->assign('date',     $date);
        $this->assign('todos',    $todos);

        $this->display();
    }

    /* 用户的task列表。*/
    public function task()
    {
        /* 加载task model。*/
        $this->loadModel('task');

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $position[]      = $this->lang->my->task;

        /* 记录用户当前选择的列表。*/
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'task');
        $this->assign('tasks',     $this->task->getUserTasks($this->app->user->account));

        $this->display();
    }

    /* 用户的bug列表。*/
    public function bug()
    {
        /* 加载bug model。*/
        $this->loadModel('bug');

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $position[]      = $this->lang->my->bug;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'bug');
        $this->assign('bugs',     $this->user->getBugs($this->app->user->account));

        $this->display();
    }

    /* 用户的project列表。*/
    public function project()
    {
        /* 加载project model。*/
        $this->loadModel('project');

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $position[]      = $this->lang->my->project;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'project');
        $this->assign('projects', $this->user->getProjects($this->app->user->account));

        $this->display();
    }

    /* 编辑个人档案。*/
    public function editProfile()
    {
       if(!empty($_POST))
        {
            $this->user->update($this->app->user->id);
            die(js::locate($this->createLink('my', 'index'), 'parent'));
        }

        $header['title'] = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $position[]      = $this->lang->my->editProfile;
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('user',     $this->app->user);

        $this->display();
    }
}
