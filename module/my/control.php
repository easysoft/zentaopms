<?php
/**
 * The control file of dashboard module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
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

        /* 导航条。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->position[]    = $this->lang->my->todo;

        /* 赋值。*/
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
        /* 登记session。*/
        $this->session->set('storyList', $this->app->getURI(true));

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
        /* 登记session。*/
        $this->session->set('taskList',  $this->app->getURI(true));
        $this->session->set('storyList', $this->app->getURI(true));

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
        /* 登记session，加载语言。*/
        $this->session->set('bugList', $this->app->getURI(true));
        $this->app->loadLang('bug');

        /* 赋值。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->position[]    = $this->lang->my->bug;
        $this->view->bugs          = $this->user->getBugs($this->app->user->account);
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->tabID         = 'bug';

        $this->display();
    }

    /* 用户的project列表。*/
    public function project()
    {
        /* 加载语言。*/
        $this->app->loadLang('project');

        /* 赋值。*/
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->position[]    = $this->lang->my->project;
        $this->view->tabID         = 'project';
        $this->view->projects      = @array_reverse($this->user->getProjects($this->app->user->account));

        $this->display();
    }

    /* 编辑个人档案。*/
    public function editProfile()
    {
        if($this->app->user->account == 'guest') die(js::alert('guest') . js::locate('back'));
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
        if($this->app->user->account == 'guest') die(js::alert('guest') . js::locate('back'));
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]    = $this->lang->my->profile;
        $this->view->user          = $this->user->getById($this->app->user->id);
        $this->display();
    }
}
