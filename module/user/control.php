<?php
declare(strict_types=1);
/**
 * The control file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: control.php 5005 2013-07-03 08:39:11Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class user extends control
{
    /**
     * 来源地址。
     * Origin url.
     *
     * @var    array
     * @access public
     */
    public $referer = '';

    /**
     * 查看用户详情。
     * View user details.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function view(int $userID)
    {
        $this->locate($this->createLink('user', 'todo', "userID=$userID&type=all"));
    }

    /**
     * 查看某个用户的待办。
     * View user's todo.
     *
     * @param  int    $userID
     * @param  string $type       the todo type, all|before|future|thisWeek|thisMonth|thisYear|assignedToOther|cycle
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function todo(int $userID, string $type = 'today', string $status = 'all', string $orderBy = 'date,status,begin', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $user = $this->user->getById($userID, 'id');
        if(empty($user)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('my', 'team'))));
        if($user->deleted) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->user->noticeHasDeleted, 'locate' => array('back' => true))));

        /* Set this url to session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Get users and todos. */
        $todos  = $this->loadModel('todo')->getList($type, $user->account, $status, 0, $pager, $sort);
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->user->error->noAccess, 'locate' => array('back' => true))));

        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->todo;
        $this->view->deptUsers = $users;
        $this->view->todos     = $todos;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->status    = $status;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的需求。
     * View user's stories.
     *
     * @param  int    $userID
     * @param  string $storyType
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story(int $userID, string $storyType = 'story', string $type = 'assignedTo', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        $this->session->set('storyList', $this->app->getURI(true), 'product');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'Title') !== false) $sort = str_replace('Title', '', $sort);

        /* Modify story title. */
        $this->loadModel('story');
        if($storyType == 'requirement') $this->lang->story->title = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);

        /* Assign. */
        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->story;
        $this->view->stories   = $this->story->getUserStories($user->account, $type, $sort, $pager, $storyType, false, 'all');
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->storyType = $storyType;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的任务。
     * View user's tasks.
     *
     * @param  int    $userID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task(int $userID, string $type = 'assignedTo', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('taskList', $this->app->getURI(true), 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'Label') !== false) $sort = str_replace('Label', '', $sort);

        /* Assign. */
        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->task;
        $this->view->tasks     = $this->loadModel('task')->getUserTasks($user->account, $type, 0, $pager, $sort);
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * 查看某个用户的 bug。
     * View user's bugs.
     *
     * @param  int    $userID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bug(int $userID, string $type = 'assignedTo', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save the session. */
        $this->session->set('bugList', $this->app->getURI(true), 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Load the lang of bug module. */
        $this->app->loadLang('bug');

        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->bug;
        $this->view->bugs      = $this->loadModel('bug')->getUserBugs($user->account, $type, $orderBy, 0, $pager);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的测试单。
     * View user's test tasks.
     *
     * @param  int    $userID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask(int $userID, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        $this->app->loadLang('testcase');

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->testTask;
        $this->view->tasks     = $this->loadModel('testtask')->getByUser($user->account, $pager, $sort);
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * 查看某个用户的测试用例。
     * View user's test cases.
     *
     * @param  int    $userID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase(int $userID, string $type = 'case2Him', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session, load lang. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        $this->app->loadLang('testcase');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'caseID') !== false) $sort = str_replace('caseID', 'id', $sort);

        $cases = array();
        if($type == 'case2Him')
        {
            $cases = $this->loadModel('testcase')->getByAssignedTo($user->account, '', $sort, $pager);
        }
        elseif($type == 'caseByHim')
        {
            $cases = $this->loadModel('testcase')->getByOpenedBy($user->account, '', $sort, $pager);
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', $type == 'case2Him' ? false : true);

        /* Process case for check story changed. */
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        /* Assign. */
        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->testCase;
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->cases     = $cases;
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的执行。
     * View user's executions.
     *
     * @param  int    $userID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function execution(int $userID, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->execution;
        $this->view->executions = $this->user->getExecutions($user->account, 'all', $orderBy, $pager);
        $this->view->deptUsers  = $users;
        $this->view->user       = $user;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的问题。
     * View user's issues.
     *
     * @param  int    $userID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function issue(int $userID, string $type = 'assignedTo', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('issueList', $this->app->getURI(true), 'project');

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->issue;
        $this->view->issues    = $this->loadModel('issue')->getUserIssues($type, 0, $user->account, $orderBy, $pager);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的风险。
     * View user's risks.
     *
     * @param  int    $userID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function risk(int $userID, string $type = 'assignedTo', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('riskList', $this->app->getURI(true), 'project');

        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->user->common . $this->lang->colon . $this->lang->user->risk;
        $this->view->risks     = $this->loadModel('risk')->getUserRisks($type, $user->account, $orderBy, $pager);
        $this->view->deptUsers = $users;
        $this->view->user      = $user;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 查看某个用户的档案。
     * View user's archives.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function profile(int $userID = 0)
    {
        $user   = $userID ? $this->user->getById($userID, 'id') : $this->app->user;
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        $this->view->title     = "USER #$user->id $user->account/" . $this->lang->user->profile;
        $this->view->groups    = $this->loadModel('group')->getByAccount($user->account);
        $this->view->deptPath  = $this->loadModel('dept')->getParents($user->dept);
        $this->view->deptUsers = $users;
        $this->view->user      = $user;

        $this->display();
    }

    /**
     * 添加一个用户。
     * Create a user.
     *
     * @param  int    $deptID
     * @param  string $type
     * @access public
     * @return void
     */
    public function create(int $deptID = 0, $type = 'inside')
    {
        if(!empty($_POST))
        {
            $user = form::data($this->config->user->form->create)
                ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
                ->get();

            $userID = $this->user->create($user);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $userID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('company', 'browse')));
        }

        $this->userZen->prepareRolesAndGroups();

        $this->view->title     = $this->lang->user->create;
        $this->view->companies = $this->loadModel('company')->getOutsideCompanies();
        $this->view->depts     = $this->loadModel('dept')->getOptionMenu();
        $this->view->rand      = updateSessionRandom();
        $this->view->visions   = getVisions();
        $this->view->deptID    = $deptID;
        $this->view->type      = $type;

        $this->display();
    }

    /**
     * 批量添加用户。
     * Batch create users.
     *
     * @param  int    $deptID
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchCreate(int $deptID = 0, string $type = 'inside')
    {
        if(!empty($_POST))
        {
            $users = form::batchData($this->config->user->form->batchCreate)->get();

            $userIdList = $this->user->batchCreate($users, $this->post->verifyPassword);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $userIdList));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('company', 'browse')));
        }

        $this->userZen->prepareRolesAndGroups();
        $this->userZen->prepareCustomFields('batchCreate', 'create');

        $this->view->title     = $this->lang->user->batchCreate;
        $this->view->companies = $this->loadModel('company')->getOutsideCompanies();
        $this->view->depts     = $this->loadModel('dept')->getOptionMenu();
        $this->view->rand      = updateSessionRandom();
        $this->view->visions   = getVisions();
        $this->view->deptID    = $deptID;
        $this->view->type      = $type;

        $this->display();
    }

    /**
     * 编辑一个用户。
     * Edit a user.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function edit(int $userID)
    {
        if(!empty($_POST))
        {
            $user = form::data($this->config->user->form->edit)
                ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
                ->add('id', $userID)
                ->get();

            $this->user->update($user);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $user       = $this->user->getById($userID, 'id');
        $userGroups = $this->loadModel('group')->getByAccount($user->account, true);

        $this->view->title      = $this->lang->user->edit;
        $this->view->companies  = $this->loadModel('company')->getOutsideCompanies();
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->groups     = $this->user->getGroupsByVisions($user->visions);
        $this->view->rand       = updateSessionRandom();
        $this->view->visions    = getVisions();
        $this->view->userGroups = array_keys($userGroups);
        $this->view->user       = $user;

        $this->display();
    }

    /**
     * 批量编辑用户。
     * Batch edit users.
     *
     * @param  int    $deptID
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchEdit(int $deptID = 0, string $type = 'inside')
    {
        if($this->post->account)
        {
            $users = form::batchData($this->config->user->form->batchEdit)->get();

            $this->user->batchUpdate($users, $this->post->verifyPassword);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID={$deptID}&browseType={$type}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
        }

        if(!$this->post->userIdList)
        {
            $locate = $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID={$deptID}&browseType={$type}");
            $this->locate($locate);
        }

        $this->userZen->prepareCustomFields('batchEdit', 'edit');

        $this->view->title     = $this->lang->user->batchEdit;
        $this->view->companies = $this->loadModel('company')->getOutsideCompanies();
        $this->view->depts     = $this->loadModel('dept')->getOptionMenu();
        $this->view->users     = $this->user->getByIdList($this->post->userIdList);
        $this->view->rand      = updateSessionRandom();
        $this->view->visions   = getVisions();
        $this->view->type      = $type;

        $this->display();
    }

    /**
     * 删除一个用户。
     * Delete a user.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function delete(int $userID)
    {
        $user = $this->user->getByID($userID, 'id');
        if($this->app->user->admin and $this->app->user->account == $user->account) return;

        if($_POST)
        {
            if($this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) return $this->send(array('result' => 'fail', 'message' => array('verifyPassword' => $this->lang->user->error->verifyPassword)));

            $this->user->delete(TABLE_USER, $userID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* if ajax request, send result. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->rand = updateSessionRandom();
        $this->view->user = $user;
        $this->display();
    }

    /**
     * 解锁一个用户。
     * Unlock a user.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unlock(int $userID)
    {
        $user = $this->user->getById($userID, 'id');
        $this->user->cleanLocked($user->account);
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 解除一个用户和 ZDOO 的绑定。
     * Unbind a user from ZDOO.
     *
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unbind(int $userID)
    {
        $user = $this->user->getById($userID, 'id');
        $this->user->unbind($user->account);
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 用户登录。
     * User login.
     *
     * @param  string $referer
     * @access public
     * @return void
     */
    public function login(string $referer = '')
    {
        $viewType = $this->app->getViewType();

        /* 重新加载语言项。*/
        /* Reload lang. */
        if($viewType == 'json' && $this->get->lang && $this->get->lang != $this->app->getClientLang()) $this->userZen->reloadLang($this->get->lang);

        /* 检查缓存目录和数据目录访问权限。如果不能访问，终止程序并输出提示信息。*/
        /* Check the access permissions of the cache directory and data directory. If you cannot access, terminate the program and output the prompt message. */
        $this->userZen->checkDirPermission();

        /* 设置来源网址。*/
        /* Set referer. */
        $this->referer = $this->userZen->setReferer($referer);

        /* 预处理变量。*/
        /* Prepare variables. */
        $loginLink     = inlink('login');
        $denyLink      = inlink('deny');
        $locateWebRoot = $this->config->webRoot . (helper::isWithTID() ? "?tid={$this->get->tid}" : '');
        $locateReferer = $this->referer;
        if(helper::isWithTID() && strpos($locateReferer, 'tid=') === false) $locateReferer .= (strpos($locateReferer, '?') === false ? '?' : '&') . "tid={$this->get->tid}";

        /* 如果用户已经登录则返回相关信息。*/
        /* If user has logon, return related info. */
        if($this->user->isLogon()) return $this->send($this->userZen->responseForLogon($this->referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot));

        /* 处理登录逻辑。*/
        /* Process login. */
        $result = $this->userZen->login($this->referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot);
        if($result) return $this->send($result);

        helper::setcookie('tab', '', time());
        $loginExpired = !(preg_match("/(m=|\/)(index)(&f=|-)(index)(&|-|\.)?/", strtolower($this->referer), $output) || $this->referer == $this->config->webRoot || empty($this->referer) || preg_match("/\/www\/$/", strtolower($this->referer), $output));

        $this->view->title        = $this->lang->user->login;
        $this->view->plugins      = $this->loadModel('extension')->getExpiringPlugins(true);
        $this->view->unsafeSites  = $this->loadModel('misc')->checkOneClickPackage();
        $this->view->rand         = updateSessionRandom();
        $this->view->keepLogin    = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';
        $this->view->sn           = zget($this->config->global, 'sn', '');
        $this->view->referer      = $this->referer;
        $this->view->loginExpired = $loginExpired;
        $this->display();
    }

    /**
     * 拒绝访问页面。
     * Deny page.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $referer the referer of the denied page.
     * @access public
     * @return void
     */
    public function deny(string $module, string $method, string $referer = '')
    {
        $this->userZen->setReferer();

        $module = strtolower($module);
        $method = strtolower($method);

        $this->app->loadLang('my');
        $this->app->loadLang($module == 'requirement' ? 'story' : $module);

        /* 判断禁止访问的类型。*/
        /* Judge the type of deny. */
        $denyType = 'nopriv';
        $rights   = $this->app->user->rights['rights'];
        $acls     = $this->app->user->rights['acls'];
        if(isset($rights[$module][$method]))
        {
            $menu = isset($this->lang->navGroup->$module) ? $this->lang->navGroup->$module : $module;
            $menu = strtolower($menu);

            if(!isset($acls['views'][$menu])) $denyType = 'noview';

            $this->view->menu = $menu;
        }

        $this->view->title    = $this->lang->user->deny;
        $this->view->module   = $module;
        $this->view->method   = $method;
        $this->view->denyPage = $this->referer; // The denied page.
        $this->view->referer  = $referer;       // The referer of the denied page.
        $this->view->denyType = $denyType;

        $this->display();
    }

    /**
     * 退出登录。
     * User logout.
     *
     * @param  string $referer
     * @access public
     * @return void
     */
    public function logout(string $referer = '')
    {
        if(!empty($this->app->user->id)) $this->loadModel('action')->create('user', $this->app->user->id, 'logout');

        helper::setcookie('za',  '', time() - 3600);
        helper::setcookie('zp',  '', time() - 3600);
        helper::setcookie('tab', '', time() - 3600);

        $_SESSION = array();    // Clear session in roadrunner.
        session_destroy();

        if($this->app->getViewType() == 'json') return $this->send(array('status' => 'success'));

        return $this->send(array('result' => 'success', 'load' => inlink('login', !empty($referer) ? "referer=$referer" : '')));
    }

    /**
     * 管理员重置密码。
     * Admin reset password.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        if(!isset($_SESSION['resetFileName']))
        {
            $resetFileName = $this->app->getBasePath() . 'tmp' . DIRECTORY_SEPARATOR . uniqid('reset_') . '.txt';
            $this->session->set('resetFileName', $resetFileName);
        }
        $resetFileName  = $this->session->resetFileName;
        $needCreateFile = !file_exists($resetFileName) || (time() - filemtime($resetFileName)) > 60 * 2;

        if($_POST)
        {
            if($needCreateFile) return $this->send(array('result' => 'success', 'load' => true));

            $user = form::data($this->config->user->form->reset)
                ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
                ->get();

            $result = $this->user->resetPassword($user);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!$result) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->resetFail));

            $referer = helper::safe64Encode($this->createLink('index', 'index'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->user->resetSuccess, 'locate' => inlink('logout', 'referer=' . $referer)));
        }

        /* 移除真实路径以确保安全。*/
        /* Remove the real path to ensure security. */
        $resetFileName = str_replace($this->app->getBasePath(), '', $resetFileName);

        $this->view->title          = $this->lang->user->resetPwdByAdmin;
        $this->view->rand           = updateSessionRandom();
        $this->view->needCreateFile = $needCreateFile;
        $this->view->resetFileName  = $resetFileName;

        $this->display();
    }

    /**
     * 忘记密码。
     * Forget password.
     *
     * @access public
     * @return void
     */
    public function forgetPassword()
    {
        if(!empty($_POST))
        {
            $data = form::data($this->config->user->form->forgetPassword)->get();

            $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->account)->fetch();
            if(empty($user)) return $this->send(array('result' => 'fail', 'message' => array('account' => $this->lang->user->error->noUser)));
            if(empty($user->email)) return $this->send(array('result' => 'fail', 'message' => array('email' => $this->lang->user->error->noEmail)));
            if($user->email != $data->email) return $this->send(array('result' => 'fail', 'message' => array('email' => $this->lang->user->error->errorEmail)));

            $this->loadModel('mail');
            if(!$this->config->mail->turnon) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->emailSetting));

            $code = uniqid();
            $this->dao->update(TABLE_USER)->set('resetToken')->eq(json_encode(array('code' => $code, 'endTime' => strtotime("+{$this->config->user->resetPasswordTimeout} minutes"))))->where('account')->eq($user->account)->exec();

            $result = $this->mail->send($user->account, $this->lang->user->resetPWD, sprintf($this->lang->mail->forgetPassword, commonModel::getSysURL() . inlink('resetPassword', 'code=' . $code)), '', true, array(), true);
            if(strstr($result, 'ERROR')) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->sendMailFail));

            return $this->send(array('result' => 'success', 'message' => $this->lang->user->sendEmailSuccess));
        }

        $this->view->title = $this->lang->user->resetPwdByMail;
        $this->display();
    }

    /**
     * 邮箱重置密码。
     * Reset password by email.
     *
     * @param  string $code
     * @access public
     * @return void
     */
    public function resetPassword(string $code)
    {
        $expired = true;
        $user    = $this->dao->select('account, resetToken')->from(TABLE_USER)->where('resetToken')->like('%"code":"' . $code . '"%')->fetch();
        if($user)
        {
            $resetToken = json_decode($user->resetToken);
            if($resetToken->endTime >= time()) $expired = false;
        }

        if(!empty($_POST))
        {
            if($expired) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->linkExpired));

            $user = form::data($this->config->user->form->resetPassword)
                ->add('account', $user->account)
                ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
                ->get();

            $this->user->resetPassword($user);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->dao->update(TABLE_USER)->set('resetToken')->eq('')->where('account')->eq($user->account)->exec();

            return $this->send(array('result' => 'fail', 'message' => $this->lang->saveSuccess, 'load' => inlink('login')));
        }

        $this->view->title   = $this->lang->user->resetPWD;
        $this->view->rand    = updateSessionRandom();
        $this->view->expired = $expired;

        $this->display();
    }

    /**
     * 查看某个用户的动态。
     * View dynamic of a user.
     *
     * @param  int    $userID
     * @param  string $period
     * @param  int    $recTotal
     * @param  int    $date
     * @param  string $direction    next|pre
     * @access public
     * @return void
     */
    public function dynamic(int $userID, string $period = 'today', int $recTotal = 0, int $date = 0, string $direction = 'next')
    {
        $user   = $this->user->getById($userID, 'id');
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        if(!isset($users[$userID])) $users[$userID] = $user->realname;

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');

        /* Append id for second sort. */
        $orderBy    = $direction == 'next' ? 'date_desc' : 'date_asc';
        $date       = $date ? date('Y-m-d', $date) : '';
        $actions    = $this->loadModel('action')->getDynamic($user->account, $period, $orderBy, 50, 'all', 'all', 'all', $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction, $period);
        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($dateGroups, 1) - count($dateGroups) : $this->action->getDynamicCount();


        /* Assign. */
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->dynamic;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->dateGroups = $dateGroups;
        $this->view->deptUsers  = $users;
        $this->view->user       = $user;
        $this->view->period     = $period;
        $this->view->recTotal   = $recTotal;
        $this->display();
    }

    /**
     * 裁剪头像。
     * Crop avatar.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function cropAvatar(int $imageID)
    {
        $image = $this->loadModel('file')->getByID($imageID);

        if(!empty($_POST))
        {
            $size = form::data($this->config->user->form->cropAvatar)->get();
            $this->file->cropImage($image->realPath, $image->realPath, $size->left, $size->top, $size->right - $size->left, $size->bottom - $size->top, $size->scaled ? $size->scaleWidth : 0, $size->scaled ? $size->scaleHeight : 0);

            $this->app->user->avatar = $image->webPath;
            $this->session->set('user', $this->app->user);
            $this->dao->update(TABLE_USER)->set('avatar')->eq($image->webPath)->where('account')->eq($this->app->user->account)->exec();
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadModal('" . $this->createLink('my', 'profile') . "', 'profile', {}, $.apps.updateUserToolbar);"));
        }

        $this->view->title = $this->lang->user->cropAvatar;
        $this->view->image = $image;
        $this->display();
    }

    /**
     * AJAX: 获取某个联系人列表中包含的用户。
     * AJAX: Get users in a contact list.
     *
     * @param  int    $contactListID
     * @access public
     * @return void
     */
    public function ajaxGetContactUsers(int $contactListID)
    {
        if(!$contactListID) return $this->send(array());

        $list = $this->user->getContactListByID($contactListID);
        if(!$list) return $this->send(array());

        $accountList = array_filter(array_unique(explode(',', $list->userList)));
        if(!$accountList) return $this->send(array());

        $users = $this->user->getListByAccounts($accountList);
        $items = array_map(function($user){return array('text' => $user->realname, 'value' => $user->account);}, $users);
        return $this->send(array_values($items));

    }

    /**
     * Ajax: 获取当前用户可以查看的联系人列表。
     * Ajax: Get contact lists that current user can view.
     *
     * @access public
     * @return void
     */
    public function ajaxGetContactList()
    {
        $lists = $this->user->getContactLists();
        $items = array_map(function($id, $name){return array('text' => $name, 'value' => $id);}, array_keys($lists), $lists);
        return $this->send($items);
    }

    /**
     * AJAX: 打印用户模板。
     * AJAX: Print user templates.
     *
     * @param  string $type
     * @param  string $link
     * @access public
     * @return void
     */
    public function ajaxPrintTemplates(string $type, string $link = '')
    {
        $this->view->link      = $link;
        $this->view->type      = $type;
        $this->view->templates = $this->user->getUserTemplates($type);
        $this->display();
    }

    /**
     * AJAX: 保存一个用户模板。
     * AJAX: Save a user template.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxSaveTemplate(string $type)
    {
        $this->user->saveUserTemplate($type);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => inlink('ajaxPrintTemplates', "type=$type")));
    }

    /**
     * AJAX：删除一个用户模板。
     * AJAX: Delete a user template.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxDeleteTemplate(int $templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)
            ->where('id')->eq($templateID)
            ->beginIF(!$this->app->user->admin)->andWhere('account')->eq($this->app->user->account)->fi()
            ->exec();
        return $this->send(array('result' => 'success'));
    }

    /**
     * Ajax get more user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetMore()
    {
        $params = base64_decode($this->get->params);
        parse_str($params, $parsedParams);
        $users = $this->user->getPairs(zget($parsedParams, 'params', ''), zget($parsedParams, 'usersToAppended', ''));

        $search   = $this->get->search;
        $limit    = $this->get->limit;
        $index    = 0;
        $newUsers = array();
        foreach($users as $account => $realname)
        {
            if($index >= $limit) break;
            if($search && stripos($account, $search) === false and stripos($realname, $search) === false) continue;
            $index ++;
            $newUsers[$account] = $realname;
        }

        echo json_encode($newUsers);
    }

    /**
     * AJAX: 根据界面类型获取权限组。
     * AJAX: Get groups by vision.
     *
     * @param  string  $visions rnd|lite|rnd,lite
     * @access public
     * @return string
     */
    public function ajaxGetGroups(string $visions)
    {
        if(!$visions) $visions = array($this->config->vision);
        $groups = $this->user->getGroupsByVisions($visions);
        $items  = array_map(function($groupID, $groupName){return array('text' => $groupName, 'value' => $groupID);}, array_keys($groups), $groups);
        return $this->send($items);
    }

    /**
     * 刷新用于登录的随机数。
     * Refresh random for login.
     *
     * @access public
     * @return void
     */
    public function refreshRandom()
    {
        $rand = updateSessionRandom();
        echo (string)$rand;
    }
}
