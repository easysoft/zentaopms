<?php
/**
 * The control file of user module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
class user extends control
{
    private $referer;

    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company')->setMenu();
        $this->loadModel('dept');
    }

    public function view($account)
    {
        $this->locate($this->createLink('user', 'todo', "account=$account"));
    }

    /* 用户的todo列表。*/
    public function todo($account, $type = 'today', $status = 'all')
    {
        /* 登记session。*/
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

        /* 加载todo model。*/
        $this->loadModel('todo');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设置菜单。*/
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);

        $todos = $this->todo->getList($type, $account, $status);
        $date  = (int)$type == 0 ? $this->todo->today() : $type;

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->company->orgView . $this->lang->colon . $this->lang->user->todo;
        $position[]      = $this->lang->user->todo;

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'todo';
        $this->view->dates    = $this->todo->buildDateList(); 
        $this->view->date     = $date;
        $this->view->todos    = $todos;
        $this->view->user     = $user;
        $this->view->account  = $account;
        $this->view->type     = $type;

        $this->display();
    }

    /* 用户的task列表。*/
    public function task($account)
    {
        $this->session->set('taskList', $this->app->getURI(true));

        /* 加载task model。*/
        $this->loadModel('task');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设置菜单。*/
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
 
        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->task;
        $position[]      = $this->lang->user->task;

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'task';
        $this->view->tasks    = $this->task->getUserTasks($account);
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        $this->display();
    }

    /* 用户的bug列表。*/
    public function bug($account)
    {
        $this->session->set('bugList', $this->app->getURI(true));

        /* 加载bug model。*/
        $this->loadModel('bug');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设置菜单。*/
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
 
        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->bug;
        $position[]      = $this->lang->user->bug;

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'bug';
        $this->view->bugs     = $this->user->getBugs($account);
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->users    = $this->user->getPairs('noletter');

        $this->display();
    }

    /* 用户的project列表。*/
    public function project($account)
    {
        /* 加载project model。*/
        $this->loadModel('project');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设置菜单。*/
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->project;
        $position[]      = $this->lang->user->project;

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'project';
        $this->view->projects = $this->user->getProjects($account);
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        $this->display();
    }

    /* 查看个人档案。*/
    public function profile($account)
    {
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->profile;
        $position[]      = $this->lang->user->profile;

        /* 设置菜单。*/
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->user     = $this->user->getById($account);

        $this->display();
    }

    /* 设置referer信息。*/
    private function setReferer($referer = 0)
    {
        if(!empty($referer))
        {
            $this->referer = helper::safe64Decode($referer);
        }
        else
        {
            $this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        }
        $this->referer = htmlspecialchars($this->referer);
    }

    /* 创建一个用户。*/
    public function create($deptID = 0, $from = 'admin')
    {
        $this->lang->set('menugroup.user', $from);
        $this->lang->user->menu = $this->lang->company->menu;

        if(!empty($_POST))
        {
            $this->user->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[]      = $this->lang->user->create;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->depts    = $this->dept->getOptionMenu();
        $this->view->deptID   = $deptID;

        $this->display();
    }

    /* 编辑一个用户。*/
    public function edit($userID, $from = 'admin')
    {
        $this->lang->set('menugroup.user', $from);
        $this->lang->user->menu = $this->lang->company->menu;
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($from == 'admin')
            {
                die(js::locate($this->createLink('admin', 'browseuser'), 'parent'));
            }
            else
            {
                die(js::locate($this->createLink('company', 'browse'), 'parent'));
            }
        }

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->edit;
        $position[]      = $this->lang->user->edit;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->user     = $this->user->getById($userID);
        $this->view->depts    = $this->dept->getOptionMenu();

        $this->display();
    }

    /* 删除一个用户。*/
    public function delete($userID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->user->confirmDelete, $this->createLink('user', 'delete', "userID=$userID&confirm=yes")));
        }
        else
        {
            $this->user->delete(TABLE_USER, $userID);
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
    }

    /* 激活一个用户。*/
    public function activate($userID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->user->confirmActivate, $this->createLink('user', 'activate', "userID=$userID&confirm=yes")));
        }
        else
        {
            $this->user->activate($userID);
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
    }

    /**
     * 登陆系统：完成用户身份验证，并取得授权。
     * 
     * @access public
     * @return void
     */
    public function login($referer = '', $from = '')
    {
        $this->setReferer($referer);

        $loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');

        /* 如果用户已经登录，返回原来的页面。*/
        if($this->user->isLogon())
        {
            if(strpos($this->referer, $loginLink) === false and 
               strpos($this->referer, $denyLink)  === false and 
               strpos($this->referer, $this->app->company->pms) !== false
            )
            {
                $this->locate($this->referer);
            }
            else
            {
                $this->locate($this->createLink($this->config->default->module));
            }
        }

        /* 用户提交了登陆信息，则检查用户的身份。*/
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;

            $user = $this->user->identify($account, $password);

            if($user)
            {
                /* 对用户进行授权，并登记session。*/
                $user->rights = $this->user->authorize($this->post->account);
                $this->session->set('user', $user);
                $this->app->user = $this->session->user;

                /* 记录登录记录。*/
                $this->loadModel('action')->create('user', $user->id, 'login');

                /* POST变量中设置了referer信息，且非user/login.html, 非user/deny.html，并且来自zentao系统。*/
                if($this->post->referer != false and 
                   strpos($this->post->referer, $loginLink) === false and 
                   strpos($this->post->referer, $denyLink)  === false and 
                   $from == 'zentao'
                )
                {
                    if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));
                    die(js::locate($_POST['referer'], 'parent'));
                }
                else
                {
                    if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));
                    die(js::locate($this->createLink($this->config->default->module), 'parent'));
                }
            }
            else
            {
                if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'failed')));
                die(js::error($this->lang->user->loginFailed));
            }
        }
        else
        {
            $header['title'] = $this->lang->user->login;
            $this->view->header  = $header;
            $this->view->referer = $this->referer;
            $this->view->s       = $this->loadModel('setting')->getItem('system', 'global', 'sn');
            $this->display();
        }
    }

    /* 访问受限页面。*/
    public function deny($module, $method, $refererBeforeDeny = '')
    {
        $this->setReferer();
        $header['title'] = $this->lang->user->deny;
        $this->view->header            = $header;
        $this->view->module            = $module;
        $this->view->method            = $method;
        $this->view->denyPage          = $this->referer;        // 访问受限的页面。
        $this->view->refererBeforeDeny = $refererBeforeDeny;    // 受限页面之前的referer页面。
        $this->app->loadLang($module);
        $this->app->loadLang('index');
        $this->display();
        exit;
    }

    /**
     * 退出系统。
     * 
     * @access public
     * @return void
     */
    public function logout($referer = 0)
    {
        $this->loadModel('action')->create('user', $this->app->user->id, 'logout');
        session_destroy();
        $vars = !empty($referer) ? "referer=$referer" : '';
        $this->locate($this->createLink('user', 'login', $vars));
    }
}
