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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
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
        $this->my->setMenu();
    }

    /* 首页，暂时跳转到待办事宜。*/
    public function index()
    {
        $this->locate($this->createLink('my', 'todo'));
    }

    /* 用户的todo列表。*/
    public function todo($type = 'today', $account = '', $status = 'all')
    {
        /* 登记session。*/
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->position[]    = $this->lang->my->todo;

        $this->view->dates = $this->loadModel('todo')->buildDateList();
        $this->view->todos = $this->todo->getList($type, $account, $status);
        $this->view->date  = (int)$type == 0 ? $this->todo->today() : $type;
        $this->view->type  = $type;
        $this->view->importFeature = ($type == 'before');

        $this->display();
    }

    /* 用户的story列表。*/
    public function story()
    {
        /* 记录用户当前选择的列表。*/
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* 赋值。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[]    = $this->lang->my->story;
        $this->view->stories       = $this->loadModel('story')->getUserStories($this->app->user->account, 'active,draft,changed');
        $this->view->users         = $this->user->getPairs('noletter');

        $this->display();
    }

    /* 用户的task列表。*/
    public function task()
    {
        /* 记录用户当前选择的列表。*/
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* 赋值。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $this->view->position[]    = $this->lang->my->task;
        $this->view->tabID         = 'task';
        $this->view->tasks         = $this->loadModel('task')->getUserTasks($this->app->user->account, 'wait,doing');
        $this->display();
    }

    /* 用户的bug列表。*/
    public function bug()
    {
        $this->session->set('bugList', $this->app->getURI(true));
        $this->app->loadLang('bug');

        /* 赋值。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->position[]    = $this->lang->my->bug;
        $this->view->tabID         = 'bug';
        $this->view->bugs          = $this->user->getBugs($this->app->user->account);

        $this->display();
    }

    /* 用户的project列表。*/
    public function project()
    {
        $this->app->loadLang('project');
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->position[]    = $this->lang->my->project;
        $this->view->tabID         = 'project';
        $this->view->projects      = @array_reverse($this->user->getProjects($this->app->user->account));
        $this->display();
    }

    /* 编辑个人档案。*/
    public function editProfile()
    {
        if(!empty($_POST))
        {
            $this->user->update($this->app->user->id);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'profile'), 'parent'));
        }

        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->position[]    = $this->lang->my->editProfile;
        $this->view->user          = $this->user->getById($this->app->user->id);

        $this->display();
    }

    /* 查看个人档案。*/
    public function profile()
    {
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]    = $this->lang->my->profile;
        $this->view->user          = $this->user->getById($this->app->user->id);
        $this->display();
    }
}
