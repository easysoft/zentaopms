<?php
/**
 * The control file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
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
     * @param  string $type         the tod type, today|lastweek|thisweek|all|undone, or a date.
     * @param  string $status 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function todo($account, $type = 'today', $status = 'all', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set thie url to session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* set menus. */
        $this->lang->set('menugroup.user', 'company');
        $this->user->setMenu($this->user->getPairs('noempty|noclosed|nodeleted'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

        /* Get user, totos. */
        $user  = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $todos = $this->todo->getList($type, $account, $status, 0, $pager);
        $date  = (int)$type == 0 ? $this->todo->today() : $type;

        $header['title'] = $this->lang->company->orgView . $this->lang->colon . $this->lang->user->todo;
        $position[]      = $this->lang->user->todo;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'todo';
        $this->view->date     = $date;
        $this->view->todos    = $todos;
        $this->view->user     = $user;
        $this->view->account  = $account;
        $this->view->type     = $type;
        $this->view->pager    = $pager;

        $this->display();
    }

    /**
     * Tasks of a user. 
     * 
     * @param  string $account 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function task($account, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('taskList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set the menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

        /* Assign. */
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->task;
        $position[]      = $this->lang->user->task;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'task';
        $this->view->tasks    = $this->loadModel('task')->getUserTasks($account, 'assignedto', 0, $pager);
        $this->view->account  = $account;
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->pager    = $pager;

        $this->display();
    }

    /**
     * User bugs. 
     * 
     * @param  string $account 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bug($account, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('bugList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set menu. */
        $this->lang->set('menugroup.user', 'company');
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

        /* Load the lang of bug module. */
        $this->app->loadLang('bug');
 
        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->bug;
        $position[]      = $this->lang->user->bug;

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'bug';
        $this->view->bugs     = $this->user->getBugs($account, $pager);
        $this->view->account  = $account;
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();
        $this->view->users    = $this->user->getPairs('noletter');
        $this->view->pager    = $pager;

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
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

        $header['title'] = $this->lang->user->common . $this->lang->colon . $this->lang->user->project;
        $position[]      = $this->lang->user->project;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'project';
        $this->view->projects = $this->user->getProjects($account);
        $this->view->account  = $account;
        $this->view->user     = $this->dao->findByAccount($account)->from(TABLE_USER)->fetch();

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
        $position[]      = $this->lang->user->profile;

        /* Set menu. */
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

        $user = $this->user->getById($account);
        $deptPath = $this->dept->getParents($user->dept);
       
        $header['title'] = "USER #$user->id $user->account/" . $this->lang->user->profile;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->account  = $account;
        $this->view->user     = $user;

        $this->view->deptPath = $deptPath;

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

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[]      = $this->lang->user->create;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->depts    = $this->dept->getOptionMenu();
        $this->view->deptID   = $deptID;

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
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $this->user->batchCreate();
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchCreate;
        $position[]      = $this->lang->user->batchCreate;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->depts    = $this->dept->getOptionMenu();
        $this->view->deptID   = $deptID;

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

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->edit;
        $position[]      = $this->lang->user->edit;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->user     = $this->user->getById($userID);
        $this->view->depts    = $this->dept->getOptionMenu();

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
            $this->user->batchEdit();
            die(js::locate($this->createLink('company', 'browse', "deptID=$deptID"), 'parent'));
        }
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchEdit;
        $position[]      = $this->lang->user->edit;
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->depts    = $this->dept->getOptionMenu();
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

        /* If user is logon, back to the rerferer. */
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

        /* Passed account and password by post or get. */
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;

            if($this->user->checkLocked($account)) die(js::error(sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes)));
            
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
                    if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));

                    /* Get the module and method of the referer. */
                    if($this->config->requestType == 'PATH_INFO')
                    {
                        $path = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
                        $path = rtrim($path, '.html');
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
                    if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));
                    die(js::locate($this->createLink($this->config->default->module), 'parent'));
                }
            }
            else
            {
                if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'failed')));
                $fails       = $this->user->failPlus($account);
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
            $demoUsers = $this->user->getPairs('nodeleted, noletter');
            array_shift($demoUsers);
            array_shift($demoUsers);
            array_pop($demoUsers);
            $this->view->showDemoUsers = $this->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq('showDemoUsers')->fetch();
            $this->view->demoUsers = $demoUsers;

            $header['title'] = $this->lang->user->login;
            $this->view->header    = $header;
            $this->view->referer   = $this->referer;
            $this->view->s         = $this->loadModel('setting')->getItem('system', 'common', 'global', 'sn', 0);
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
        $header['title'] = $this->lang->user->deny;
        $this->view->header            = $header;
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
        $this->user->setMenu($this->user->getPairs('noempty|noclosed'), $account);
        $this->view->userList = $this->user->setUserList($this->user->getPairs('noempty|noclosed'), $account);

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

        $this->view->header->title = $this->lang->company->common . $this->lang->colon . $this->lang->company->dynamic;
        $this->view->position[]    = $this->lang->company->dynamic;

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

        $this->view->lists = $this->user->getContactLists($this->app->user->account);
        $this->view->users = $users;
        $this->view->mode  = $mode;
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
}
