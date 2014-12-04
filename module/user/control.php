<?php
/**
 * The control file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: control.php 5005 2013-07-03 08:39:11Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class user extends control
{
    public $referer;

    /**
     * Construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('company')->setMenu();
        $this->loadModel('dept');
        $this->loadModel('todo');
    }

    /**
     * View a user.
     * 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function view($account)
    {
        $this->locate($this->createLink('user', 'todo', "account=$account"));
    }

    /**
     * Todos of a user. 
     * 
     * @param  string $account 
     * @param  string $type         the todo type, today|lastweek|thisweek|all|undone, or a date.
     * @param  string $status 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function todo($account, $type = 'today', $status = 'all', $orderBy='date,status,begin', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set thie url to session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Get user, totos. */
        $user    = $this->user->getById($account);
        $account = $user->account;
        $todos   = $this->todo->getList($type, $account, $status, 0, $pager, $sort);
        $date    = (int)$type == 0 ? helper::today() : $type;

        /* set menus. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->todo;
        $this->view->position[] = $this->lang->user->todo;
        $this->view->tabID      = 'todo';
        $this->view->date       = $date;
        $this->view->todos      = $todos;
        $this->view->user       = $user;
        $this->view->account    = $account;
        $this->view->type       = $type;
        $this->view->status     = $status;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Story of a user.
     * 
     * @param  string $account 
     * @param  string $type 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function story($account, $type = 'assignedTo', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->session->set('storyList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        /* Assign. */
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->story;
        $this->view->position[] = $this->lang->user->story;
        $this->view->stories    = $this->loadModel('story')->getUserStories($account, $type, 'id_desc', $pager);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->type       = $type;
        $this->view->account    = $account;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Tasks of a user. 
     * 
     * @param  string $account 
     * @param  string $type
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function task($account, $type = 'assignedTo', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('taskList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set the menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        /* Assign. */
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->task;
        $this->view->position[] = $this->lang->user->task;
        $this->view->tabID      = 'task';
        $this->view->tasks      = $this->loadModel('task')->getUserTasks($account, $type, 0, $pager);
        $this->view->type       = $type;
        $this->view->account    = $account;
        $this->view->user       = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * User bugs. 
     * 
     * @param  string $account 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bug($account, $type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('bugList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        /* Load the lang of bug module. */
        $this->app->loadLang('bug');
 
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->bug;
        $this->view->position[] = $this->lang->user->bug;
        $this->view->tabID      = 'bug';
        $this->view->bugs       = $this->loadModel('bug')->getUserBugs($account, $type, $orderBy, 0, $pager);
        $this->view->account    = $account;
        $this->view->type       = $type;
        $this->view->user       = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * User's testtask 
     * 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function testtask($account, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        $this->app->loadLang('testcase');

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->testTask;
        $this->view->position[] = $this->lang->user->testTask;
        $this->view->tasks      = $this->loadModel('testtask')->getByUser($account, $pager, $sort);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->account    = $account;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();

    }

    /**
     * User's test case.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function testcase($account, $type = 'case2Him', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session, load lang. */
        $this->session->set('caseList', $this->app->getURI(true));
        $this->app->loadLang('testcase');
        
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

         /* Set menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);
       
        $cases = array();
        if($type == 'case2Him')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->orderBy($sort)->page($pager)->fetchAll();
        }
        elseif($type == 'caseByHim')
        {
            $cases = $this->dao->findByOpenedBy($account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($sort)->page($pager)->fetchAll();
        }
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', $type == 'assigntome' ? false : true);
        
        /* Assign. */
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->testCase;
        $this->view->position[] = $this->lang->user->testCase;
        $this->view->account    = $account;
        $this->view->cases      = $cases;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->tabID      = 'test';
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        
        $this->display();
    }

    /**
     * User projects. 
     * 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function project($account)
    {
        /* Set the menus. */
        $this->loadModel('project');
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclose|nodeleted'), $account);

        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->project;
        $this->view->position[] = $this->lang->user->project;
        $this->view->tabID      = 'project';
        $this->view->projects   = $this->user->getProjects($account);
        $this->view->account    = $account;
        $this->view->user       = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

        $this->display();
    }

    /**
     * The profile of a user.
     * 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function profile($account)
    {
        /* Set menu. */
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclose|nodeleted'), $account);

        $user = $this->user->getById($account);
       
        $this->view->title      = "USER #$user->id $user->account/" . $this->lang->user->profile;
        $this->view->position[] = $this->lang->user->common;
        $this->view->position[] = $this->lang->user->profile;
        $this->view->account    = $account;
        $this->view->user       = $user;
        $this->view->groups     = $this->loadModel('group')->getByAccount($account);
        $this->view->deptPath   = $this->dept->getParents($user->dept);

        $this->display();
    }

    /**
     * Set the rerferer.
     * 
     * @param  string   $referer 
     * @access public
     * @return void
     */
    public function setReferer($referer = '')
    {
        if(!empty($referer))
        {
            $this->referer = helper::safe64Decode($referer);
        }
        else
        {
            $this->referer = $this->server->http_referer ? $this->server->http_referer: '';
        }
    }

    /**
     * Create a suer.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function create($deptID = 0)
    {
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $this->user->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
        $groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[] = $this->lang->user->create;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->depts     = $this->dept->getOptionMenu();
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;
        $this->view->deptID    = $deptID;

        $this->display();
    }


    /**
     * Batch create users.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function batchCreate($deptID = 0)
    {
        $groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $this->user->batchCreate();
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchCreate;
        $position[] = $this->lang->user->batchCreate;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->depts     = $this->dept->getOptionMenu();
        $this->view->deptID    = $deptID;
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;

        $this->display();
    }

    /**
     * Edit a user.
     * 
     * @param  string|int $userID   the int user id or account
     * @access public
     * @return void
     */
    public function edit($userID)
    {
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        $user       = $this->user->getById($userID);
        $userGroups = $this->loadModel('group')->getByAccount($user->account);

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->edit;
        $position[] = $this->lang->user->edit;
        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->user       = $user;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->groups     = $this->loadModel('group')->getPairs();
 
        $this->display();
    }

    /**
     * Batch edit user.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function batchEdit($deptID = 0)
    {
        if(isset($_POST['users']))
        {
            $this->view->users = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($this->post->users)->orderBy('id')->fetchAll('id');
        }
        elseif($_POST)
        {
            if($this->post->account) $this->user->batchEdit();
            die(js::locate($this->createLink('company', 'browse', "deptID=$deptID"), 'parent'));
        }
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchEdit;
        $this->view->position[] = $this->lang->user->batchEdit;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->display();
    }

    /**
     * Delete a user.
     * 
     * @param  int    $userID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($userID, $confirm = 'no')
    {
        $user = $this->user->getByID($userID);
        if(strpos($this->app->company->admins, ",{$this->app->user->account},") !== false and $this->app->user->account == $user->account) return;
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->user->confirmDelete, $this->createLink('user', 'delete', "userID=$userID&confirm=yes")));
        }
        else
        {
            $this->user->delete(TABLE_USER, $userID);

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                $this->send($response);
            }
            die(js::locate($this->session->userList, 'parent'));
        }
    }

    /**
     * Unlock a user.
     * 
     * @param  int    $account 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function unlock($account, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->user->confirmUnlock, $this->createLink('user', 'unlock', "account=$account&confirm=yes")));
        }
        else
        {
            $this->user->cleanLocked($account);
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
    }

    /**
     * User login, identify him and authorize him.
     * 
     * @access public
     * @return void
     */
    public function login($referer = '', $from = '')
    {
        $this->setReferer($referer);

        $loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');

        /* Reload lang by lang of get when viewType is json. */
        if($this->app->getViewType() == 'json' and $this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('user');
        }

        /* If user is logon, back to the rerferer. */
        if($this->user->isLogon())
        {
            if($this->app->getViewType() == 'json')
            {
                $data = $this->user->getDataInJSON($this->app->user);
                die(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
            }

            if(strpos($this->referer, $loginLink) === false and 
               strpos($this->referer, $denyLink)  === false 
            )
            {
                $this->locate($this->referer);
            }
            else
            {
                $this->locate($this->createLink($this->config->default->module));
            }
        }

        /* Passed account and password by post or get. */
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;

            if($this->user->checkLocked($account))
            {
                $failReason = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
                if($this->app->getViewType() == 'json') die(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $failReason))));
                die(js::error($failReason));
            }
            
            $user = $this->user->identify($account, $password);

            if($user)
            {
                $this->user->cleanLocked($account);
                /* Authorize him and save to session. */
                $user->rights = $this->user->authorize($account);
                $user->groups = $this->user->getGroups($account);
                $this->session->set('user', $user);
                $this->app->user = $this->session->user;
                $this->loadModel('action')->create('user', $user->id, 'login');

                /* Keep login. */
                if($this->post->keepLogin) $this->user->keepLogin($user);

                /* Go to the referer. */
                if($this->post->referer and 
                   strpos($this->post->referer, $loginLink) === false and 
                   strpos($this->post->referer, $denyLink)  === false 
                )
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        die(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }

                    /* Get the module and method of the referer. */
                    if($this->config->requestType == 'PATH_INFO')
                    {
                        $path = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
                        $path = rtrim($path, '.html');
                        if(empty($path)) $path = $this->config->requestFix;
                        list($module, $method) = explode($this->config->requestFix, $path);
                    }
                    else
                    {
                        $url   = html_entity_decode($this->post->referer);
                        $param = substr($url, strrpos($url, '?') + 1);
                        list($module, $method) = explode('&', $param);
                        $module = str_replace('m=', '', $module);
                        $method = str_replace('f=', '', $method);
                    }

                    if(common::hasPriv($module, $method))
                    {
                        die(js::locate($this->post->referer, 'parent'));
                    }
                    else
                    {
                        die(js::locate($this->createLink($this->config->default->module), 'parent'));
                    }
                }
                else
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        die(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }
                    die(js::locate($this->createLink($this->config->default->module), 'parent'));
                }
            }
            else
            {
                $fails = $this->user->failPlus($account);
                if($this->app->getViewType() == 'json') die(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $this->lang->user->loginFailed))));
                $remainTimes = $this->config->user->failTimes - $fails;
                if($remainTimes <= 0)
                {
                    die(js::error(sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes)));
                }
                else if($remainTimes <= 3)
                {
                    die(js::error(sprintf($this->lang->user->lockWarning, $remainTimes)));
                }
                die(js::error($this->lang->user->loginFailed));
            }
        }
        else
        { 
            if(!empty($this->config->global->showDemoUsers))
            {
                $demoUsers = $this->user->getPairs('nodeleted, noletter, noempty, noclosed');
                $this->view->demoUsers = $demoUsers;
            }

            $this->app->loadLang('misc');
            $this->view->noGDLib   = sprintf($this->lang->misc->noGDLib, common::getSysURL() . $this->config->webRoot);
            $this->view->title     = $this->lang->user->login;
            $this->view->referer   = $this->referer;
            $this->view->s         = zget($this->config->global, 'sn');
            $this->view->keepLogin = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';
            $this->display();
        }
    }

    /**
     * Deny page.
     * 
     * @param  string $module
     * @param  string $method 
     * @param  string $refererBeforeDeny    the referer of the denied page.
     * @access public
     * @return void
     */
    public function deny($module, $method, $refererBeforeDeny = '')
    {
        $this->setReferer();
        $this->view->title             = $this->lang->user->deny;
        $this->view->module            = $module;
        $this->view->method            = $method;
        $this->view->denyPage          = $this->referer;        // The denied page.
        $this->view->refererBeforeDeny = $refererBeforeDeny;    // The referer of the denied page.
        $this->app->loadLang($module);
        $this->app->loadLang('my');
        $this->display();
        exit;
    }

    /**
     * Logout.
     * 
     * @access public
     * @return void
     */
    public function logout($referer = 0)
    {
        if(isset($this->app->user->id)) $this->loadModel('action')->create('user', $this->app->user->id, 'logout');
        session_destroy();
        setcookie('za', false);
        setcookie('zp', false);

        if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));
        $vars = !empty($referer) ? "referer=$referer" : '';
        $this->locate($this->createLink('user', 'login', $vars));
    }
    
    /**
     * User dynamic.
     * 
     * @param  string $period 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function dynamic($period = 'today', $account = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* set menus. */
        $this->lang->set('menugroup.user', 'company');
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed|nodeleted'), $account);

        /* Save session. */
        $uri   = $this->app->getURI(true);
        $this->session->set('productList',     $uri);
        $this->session->set('productPlanList', $uri);
        $this->session->set('releaseList',     $uri);
        $this->session->set('storyList',       $uri);
        $this->session->set('projectList',     $uri);
        $this->session->set('taskList',        $uri);
        $this->session->set('buildList',       $uri);
        $this->session->set('bugList',         $uri);
        $this->session->set('caseList',        $uri);
        $this->session->set('testtaskList',    $uri);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;

        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->dynamic;
        $this->view->position[] = $this->lang->user->dynamic;

        /* Assign. */
        $this->view->period  = $period;
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter');
        $this->view->account = $account;
        $this->view->user    = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->actions = $this->loadModel('action')->getDynamic($account, $period, $orderBy, $pager);
        $this->display();
    }

    /**
     * Manage contacts.
     * 
     * @param  int    $listID 
     * @access public
     * @return void
     */
    public function manageContacts($listID = 0)
    {
        $lists = $this->user->getContactLists($this->app->user->account);

        /* If set $mode, need to update database. */
        if($this->post->mode)
        {
            /* The mode is new: append or new a list. */
            if($this->post->mode == 'new')
            {
                if($this->post->list2Append)
                {
                    $this->user->append2ContactList($this->post->list2Append, $this->post->users);
                    die(js::locate(inlink('manageContacts', "listID={$this->post->list2Append}"), 'parent'));
                }
                elseif($this->post->newList)
                {
                    $listID = $this->user->createContactList($this->post->newList, $this->post->users);
                    die(js::locate(inlink('manageContacts', "listID=$listID"), 'parent'));
                }
            }
            elseif($this->post->mode == 'edit')
            {
                $this->user->updateContactList($this->post->listID, $this->post->listName, $this->post->users);
                die(js::locate(inlink('manageContacts', "listID={$this->post->listID}"), 'parent'));
            }
        }
        if($this->post->users) 
        {
            $mode  = 'new';
            $users = $this->user->getContactUserPairs($this->post->users);
        }
        else
        {
            $mode  = 'edit';
            $listID= $listID ? $listID : key($lists);
            if(!$listID) die(js::alert($this->lang->user->contacts->noListYet) . js::locate($this->createLink('company', 'browse'), 'parent'));

            $list  = $this->user->getContactListByID($listID);
            $users = explode(',', $list->userList);
            $users = $this->user->getContactUserPairs($users);
            $this->view->list = $list;
        }

        $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->manageContacts;
        $this->view->position[] = $this->lang->company->common;
        $this->view->position[] = $this->lang->user->manageContacts;
        $this->view->lists      = $this->user->getContactLists($this->app->user->account);
        $this->view->users      = $users;
        $this->view->listID     = $listID;
        $this->view->mode       = $mode;
        $this->display();
    }

    /**
     * Delete a contact list.
     * 
     * @param  int    $listID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function deleteContacts($listID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->user->contacts->confirmDelete, inlink('deleteContacts', "listID=$listID&confirm=yes"));
            exit;
        }
        else
        {
            $this->user->deleteContactList($listID);
            echo js::locate(inlink('manageContacts'), 'parent');
            exit;
        }
    }

    /**
     * Get user for ajax
     *
     * @param  string $requestID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function ajaxGetUser($taskID = '', $assignedTo = '')
    {
        $users = $this->user->getPairs('noletter, noclosed');
        $html = "<form method='post' target='hiddenwin' action='" . $this->createLink('task', 'assignedTo', "taskID=$taskID&assignedTo=$assignedTo") . "'>";
        $html .= html::select('assignedTo', $users, $assignedTo);
        $html .= html::submitButton();
        $html .= '</form>';
        echo $html;
    }

    /**
     * AJAX: get users from a contact list.
     * 
     * @param  int    $contactListID 
     * @access public
     * @return string
     */
    public function ajaxGetContactUsers($contactListID)
    {
        $users = $this->user->getPairs('nodeleted,devfirst');
        if(!$contactListID) return print(html::select('mailto[]', $users, '', "class='form-control' multiple data-placeholder='{$this->lang->chooseUsersToMail}'"));
        $list = $this->user->getContactListByID($contactListID);
        return print(html::select('mailto[]', $users, $list->userList, "class='form-control' multiple data-placeholder='{$this->lang->chooseUsersToMail}'"));
    }
}
