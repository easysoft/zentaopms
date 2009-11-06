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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class user extends control
{
    private $referer;

    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('admin');
        $this->loadModel('dept');
    }

    public function view($account)
    {
        $this->locate($this->createLink('user', 'todo', "account=$account"));
    }

    /* 用户的todo列表。*/
    public function todo($account, $date = 'today')
    {
        /* 加载todo model。*/
        $this->loadModel('todo');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        if($date == 'today') $date = $this->todo->today();

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->company->orgView . $this->lang->colon . $this->lang->user->todo;
        $position[]      = $this->lang->user->todo;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'todo');
        $this->assign('dates',    $this->todo->buildDateList()); 
        $this->assign('date',     $date);
        $this->assign('todos',    $this->todo->getList($date, $account));
        $this->assign('user',     $this->dao->findByAccount($account)->from(TABLE_USER)->fetch());

        $this->display();
    }

    /* 用户的task列表。*/
    public function task($account)
    {
        /* 加载task model。*/
        $this->loadModel('task');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
 
        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->task;
        $position[]      = $this->lang->user->task;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'task');
        $this->assign('tasks',    $this->task->getUserTasks($account));
        $this->assign('user',     $this->dao->findByAccount($account)->from(TABLE_USER)->fetch());

        $this->display();
    }

    /* 用户的bug列表。*/
    public function bug($account)
    {
        /* 加载bug model。*/
        $this->loadModel('bug');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->bug;
        $position[]      = $this->lang->user->bug;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'bug');
        $this->assign('bugs',     $this->user->getBugs($account));
        $this->assign('user',     $this->dao->findByAccount($account)->from(TABLE_USER)->fetch());

        $this->display();
    }

    /* 用户的project列表。*/
    public function project($account)
    {
        /* 加载project model。*/
        $this->loadModel('project');
        $this->lang->set('menugroup.user', 'company');
        $user = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->project;
        $position[]      = $this->lang->user->project;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('tabID',    'project');
        $this->assign('projects', $this->user->getProjects($account));
        $this->assign('user',     $this->dao->findByAccount($account)->from(TABLE_USER)->fetch());

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
    }

    /* 创建一个用户。*/
    public function create($companyID = 0, $from = 'admin')
    {
        if($companyID == 0) $companyID = $this->app->company->id;
        $this->lang->set('menugroup.user', $from);

        if(!empty($_POST))
        {
            $this->user->create($companyID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($from == 'admin')
            {
                die(js::locate($this->createLink('admin', 'browseuser', "companyid={$this->app->company->id}"), 'parent'));
            }
            else
            {
                die(js::locate($this->createLink('company', 'browse'), 'parent'));
            }
        }

        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->user->create;
        $position[]      = html::a($this->createLink('admin', 'browseuser', "companyid={$this->app->company->id}"), $this->lang->admin->user);
        $position[]      = $this->lang->user->create;
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('depts',    $this->dept->getOptionMenu());

        $this->display();
    }

    /* 编辑一个用户。*/
    public function edit($userID, $from = 'admin')
    {
        $this->lang->set('menugroup.user', $from);
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($from == 'admin')
            {
                die(js::locate($this->createLink('admin', 'browseuser', "companyid={$this->app->company->id}"), 'parent'));
            }
            else
            {
                die(js::locate($this->createLink('company', 'browse'), 'parent'));
            }
        }

        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->user->edit;
        $position[]      = $this->lang->user->edit;
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('user',     $this->user->getById($userID));
        $this->assign('depts',    $this->dept->getOptionMenu());

        $this->display();
    }

    /* 删除一个用户。*/
    public function delete($userID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->user->confirmDelete, $this->createLink('user', 'delete', "userID=$userID&confirm=yes"));
            exit;
        }
        else
        {
            $this->user->delete($userID);
            echo js::locate($this->createLink('admin', 'browseuser', "companyID={$this->app->company->id}"), 'parent');
            exit;
        }
    }

    /**
     * 登陆系统：完成用户身份验证，并取得授权。
     * 
     * @access public
     * @return void
     */
    public function login($referer = '')
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
        if(!empty($_POST))
        {
            $user = $this->user->identify($_POST['account'], $_POST['password'], $this->app->company->id);
            if($user)
            {
                $user->rights = $this->user->authorize($_POST['account']);
                $_SESSION['user'] = $user;
                $this->app->setSessionUser($user);

                /* POST变量中设置了referer信息，且非user/login.html, 非user/deny.html，并且包含当前系统的域名。*/
                if(isset($_POST['referer'])  and 
                   !empty($_POST['referer']) and 
                   strpos($_POST['referer'], $loginLink) === false and 
                   strpos($_POST['referer'], $denyLink)  === false and 
                   strpos($_POST['referer'], $this->app->company->pms) !== false
                )
                {
                    $this->locate($_POST['referer']);
                }
                else
                {
                    $this->locate($this->createLink($this->config->default->module));
                }
            }
            else
            {
                $this->locate($this->createLink('user', 'login'));
            }
        }
        else
        {
            $header['title'] = $this->lang->user->login;
            $this->assign('header',  $header);
            $this->assign('referer', $this->referer);
            $this->display();
        }
    }

    /* 访问受限页面。*/
    public function deny($module, $method, $refererBeforeDeny)
    {
        $this->setReferer();
        $header['title'] = $this->lang->user->deny;
        $this->assign('header',   $header);
        $this->assign('module',   $module);
        $this->assign('method',   $method);
        $this->assign('denyPage', $this->referer);                 // 访问受限的页面。
        $this->assign('refererBeforeDeny', $refererBeforeDeny);    // 受限页面之前的referer页面。
        $this->app->loadLang($module);
        $this->app->loadLang('index');
        $this->display();
    }

    /**
     * 退出系统。
     * 
     * @access public
     * @return void
     */
    public function logout($referer = 0)
    {
        session_destroy();
        $vars = !empty($referer) ? "referer=$referer" : '';
        $this->locate($this->createLink('user', 'login', $vars));
    }
}
