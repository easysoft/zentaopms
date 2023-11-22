<?php
declare(strict_types=1);
/**
 * The control file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: control.php 5005 2013-07-03 08:39:11Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
        $cases = $this->testcase->appendData($cases);

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
        $this->view->executions = $this->user->getObjects($user->account, 'execution', 'all', $orderBy, $pager);
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

        $this->view->title        = "USER #$user->id $user->account/" . $this->lang->user->profile;
        $this->view->groups       = $this->loadModel('group')->getByAccount($user->account);
        $this->view->deptPath     = $this->loadModel('dept')->getParents($user->dept);
        $this->view->personalData = $this->user->getPersonalData($user->account);
        $this->view->deptUsers    = $users;
        $this->view->user         = $user;

        $this->display();
    }

    /**
     * Set the referer.
     *
     * @param  string   $referer
     * @access public
     * @return void
     */
    public function setReferer($referer = '')
    {
        $this->referer = $this->server->http_referer ? $this->server->http_referer: '';
        if(!empty($referer)) $this->referer = helper::safe64Decode($referer);
        if($this->post->referer) $this->referer = $this->post->referer;

        /* Build zentao link regular. */
        $webRoot = $this->config->webRoot;
        $linkReg = $webRoot . 'index.php?' . $this->config->moduleVar . '=\w+&' . $this->config->methodVar . '=\w+';
        if($this->config->requestType == 'PATH_INFO') $linkReg = $webRoot . '\w+' . $this->config->requestFix . '\w+';
        $linkReg = str_replace(array('/', '.', '?', '-'), array('\/', '\.', '\?', '\-'), $linkReg);

        /* Check zentao link by regular. */
        $this->referer = preg_match('/^' . $linkReg . '/', $this->referer) ? $this->referer : $webRoot;
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
        if(!empty($_POST))
        {
            if(strtolower($_POST['account']) == 'guest')
            {
                return $this->send(array('result' => 'fail', 'message' => str_replace('ID ', '', sprintf($this->lang->user->error->reserved, $_POST['account']))));
            }

            $userID = $this->user->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $userID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('company', 'browse')));
        }

        $groups = $this->dao->select('id, name, role, vision')
            ->from(TABLE_GROUP)
            ->fetchAll();
        $groupList = array();
        $roleGroup = array();
        foreach($groups as $group)
        {
            if($group->vision == 'rnd') $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $this->view->title     = $this->lang->user->create;
        $this->view->depts     = $this->loadModel('dept')->getOptionMenu();
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;
        $this->view->deptID    = $deptID;
        $this->view->rand      = $this->user->updateSessionRandom();
        $this->view->companies = $this->loadModel('company')->getOutsideCompanies();

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
        if(!empty($_POST))
        {
            $userIdList = $this->user->batchCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $userIdList));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('company', 'browse')));
        }

        $groups = $this->dao->select('id, name, role')
            ->from(TABLE_GROUP)
            ->where('vision')->eq($this->config->vision)
            ->fetchAll();
        $groupList = array();
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        /* Set custom. */
        foreach(explode(',', $this->config->user->availableBatchCreateFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $customFields[$field] = $this->lang->user->$field;
        }

        $batchCreateFields = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=user&section=custom&key=batchCreateFields");
        if(!$batchCreateFields) $batchCreateFields = $this->config->user->custom->batchCreateFields;
        foreach(explode(',', $batchCreateFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $showFields[$field] = $field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = join(',', $showFields);

        $this->view->title      = $this->lang->user->batchCreate;
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->deptID     = $deptID;
        $this->view->groupList  = $groupList;
        $this->view->roleGroup  = $roleGroup;
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $this->user->getVisionList();
        $this->view->companies  = $this->loadModel('company')->getOutsideCompanies();

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
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $userVisionList = $this->user->getVisionList();

        $user       = $this->user->getById($userID, 'id');
        $userGroups = $this->loadModel('group')->getByAccount($user->account, count($userVisionList) > 1 ? true : false);

        $this->view->title      = $this->lang->user->edit;
        $this->view->user       = $user;
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->companies  = $this->loadModel('company')->getOutsideCompanies();
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->where('project')->eq(0)->fetchPairs('id', 'name');
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $userVisionList;

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
        if(empty($_POST)) return $this->send(array('result' => 'success', 'load' => $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID=$deptID")));
        if(!empty($_POST['account']))
        {
            $this->user->batchEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->userList ? $this->session->userList : $this->createLink('company', 'browse', "deptID=$deptID")));
        }
        if(isset($_POST['users'])) $this->view->users = $this->dao->select('*')->from(TABLE_USER)->where('id')->in($this->post->users)->orderBy('id')->fetchAll('id');

        /* Set custom. */
        foreach(explode(',', $this->config->user->availableBatchEditFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $customFields[$field] = $this->lang->user->$field;
        }

        $batchEditFields = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=user&section=custom&key=batchEditFields");
        if(!$batchEditFields) $batchEditFields = $this->config->user->custom->batchEditFields;
        foreach(explode(',', $batchEditFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $showFields[$field] = $field;
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = join(',', $showFields);

        $this->view->title      = $this->lang->user->batchEdit;
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->visionList = $this->user->getVisionList();

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
    public function delete($userID)
    {
        $user = $this->user->getByID($userID, 'id');
        if($this->app->user->admin and $this->app->user->account == $user->account) return;
        if($_POST)
        {
            if($this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) return $this->send(array('result' => 'fail', 'message' => array('verifyPassword' => $this->lang->user->error->verifyPassword)));

            $this->user->delete(TABLE_USER, $userID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('delete', $user->email);

            /* if ajax request, send result. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->rand = $this->user->updateSessionRandom();
        $this->view->user = $user;
        $this->display();
    }

    /**
     * Unlock a user.
     *
     * @param  int    $userID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlock($userID, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->user->confirmUnlock, $this->createLink('user', 'unlock', "userID=$userID&confirm=yes")));

        $user = $this->user->getById($userID, 'id');
        $this->user->cleanLocked($user->account);
        return print(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse'), 'parent'));
    }

    /**
     * Unbind Ranzhi
     *
     * @param  string $userID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unbind($userID, $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->user->confirmUnbind, $this->createLink('user', 'unbind', "userID=$userID&confirm=yes")));

        $user = $this->user->getById($userID, 'id');
        $this->user->unbind($user->account);
        return print(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse'), 'parent'));
    }


    /**
     * User login, identify him and authorize him.
     *
     * @param string $referer
     * @param string $from
     *
     * @access public
     * @return void
     */
    public function login($referer = '', $from = '')
    {
        /* Check if you can operating on the folder. */
        $canModifyDIR = true;
        if($this->user->checkTmp() === false)
        {
            $canModifyDIR = false;
            $folderPath   = $this->app->tmpRoot;
        }
        elseif(!is_dir($this->app->dataRoot) or substr(decoct(fileperms($this->app->dataRoot)), -4) != '0777')
        {
            $canModifyDIR = false;
            $folderPath   = $this->app->dataRoot;
        }

        if(!$canModifyDIR)
        {
            if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                return print(sprintf($this->lang->user->mkdirWin, $folderPath, $folderPath));
            }
            else
            {
                return print(sprintf($this->lang->user->mkdirLinux, $folderPath, $folderPath, $folderPath, $folderPath));
            }
        }

        $this->setReferer($referer);

        $loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');

        /* Reload lang by lang of get when viewType is json. */
        if($this->app->getViewType() == 'json' and $this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('user');
        }

        /* If user is logon, back to the referer. */
        if($this->user->isLogon())
        {
            if($this->app->getViewType() == 'json')
            {
                $data = $this->user->getDataInJSON($this->app->user);
                return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
            }

            $response['result'] = 'success';
            if(strpos($this->referer, $loginLink) === false and
               strpos($this->referer, $denyLink)  === false and
               strpos($this->referer, 'ajax') === false and
               strpos($this->referer, 'block')  === false and $this->referer
            )
            {
                $response['locate'] = $this->referer;
                if(helper::isWithTID() and strpos($response['locate'], 'tid=') === false) $response['locate'] .= (strpos($response['locate'], '?') === false ? '?' : '&') . "tid={$this->get->tid}";
                return $this->send($response);
            }
            else
            {
                $response['locate'] = $this->config->webRoot . (helper::isWithTID() ? "?tid={$this->get->tid}" : '');
                return $this->send($response);
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

            $account = trim($account);
            if($this->user->checkLocked($account))
            {
                $response['result']  = 'fail';
                $response['message'] = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
                if($this->app->getViewType() == 'json') return print(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $response['message']))));
                return $this->send($response);
            }

            if((!empty($this->config->safe->loginCaptcha) and strtolower($this->post->captcha) != strtolower($this->session->captcha) and $this->app->getViewType() != 'json'))
            {
                $response['result']  = 'fail';
                $response['message'] = $this->lang->user->errorCaptcha;
                return $this->send($response);
            }

            $user = $this->user->identify($account, $password);

            if($user)
            {
                /* Set user group, rights, view and award login score. */
                $user = $this->user->login($user);

                /* Go to the referer. */
                if($this->post->referer and strpos($this->post->referer, $loginLink) === false and strpos($this->post->referer, $denyLink) === false and strpos($this->post->referer, 'block') === false)
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }

                    /* Get the module and method of the referer. */
                    $module = $this->config->default->module;
                    $method = $this->config->default->method;
                    if($this->config->requestType == 'PATH_INFO')
                    {
                        $requestFix = $this->config->requestFix;

                        $path = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
                        $path = rtrim($path, '.html');
                        if($path and strpos($path, $requestFix) !== false) list($module, $method) = explode($requestFix, $path);
                    }
                    else
                    {
                        $url   = html_entity_decode($this->post->referer);
                        $param = substr($url, strrpos($url, '?') + 1);

                        if(strpos($param, '&') !== false) list($module, $method) = explode('&', $param);
                        $module = str_replace('m=', '', $module);
                        $method = str_replace('f=', '', $method);
                    }

                    /* Check parsed name of module and method from referer. */
                    if(empty($module) or !$this->app->checkModuleName($module, $exit = false) or
                       empty($method) or !$this->app->checkMethodName($module, $exit = false))
                    {
                        $module = $this->config->default->module;
                        $method = $this->config->default->method;
                    }

                    $response['result']  = 'success';
                    if(common::hasPriv($module, $method))
                    {
                        $response['locate'] = $this->post->referer;
                        if(helper::isWithTID() and strpos($response['locate'], 'tid=') === false) $response['locate'] .= (strpos($response['locate'], '?') === false ? '?' : '&') . "tid={$this->get->tid}";
                        return $this->send($response);
                    }
                    else
                    {
                        $response['locate'] = $this->config->webRoot . (helper::isWithTID() ? "?tid={$this->get->tid}" : '');
                        return $this->send($response);
                    }
                }
                else
                {
                    if($this->app->getViewType() == 'json')
                    {
                        $data = $this->user->getDataInJSON($user);
                        return print(helper::removeUTF8Bom(json_encode(array('status' => 'success') + $data)));
                    }

                    $response['locate']  = $this->config->webRoot . (helper::isWithTID() ? "?tid={$this->get->tid}" : '');
                    $response['result']  = 'success';
                    return $this->send($response);
                }
            }
            else
            {
                $response['result']  = 'fail';
                $fails = $this->user->failPlus($account);
                if($this->app->getViewType() == 'json') return print(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $this->lang->user->loginFailed))));
                $remainTimes = $this->config->user->failTimes - $fails;
                if($remainTimes <= 0)
                {
                    $response['message'] = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
                    return $this->send($response);
                }
                elseif($remainTimes <= 3)
                {
                    $response['message'] = sprintf($this->lang->user->lockWarning, $remainTimes);
                    return $this->send($response);
                }

                $response['message'] = $this->lang->user->loginFailed;
                if(dao::isError()) $response['message'] = dao::getError();
                return $this->send($response);
            }
        }
        else
        {
            helper::setcookie('tab', '', time(), $this->config->webRoot);
            $loginExpired = !(preg_match("/(m=|\/)(index)(&f=|-)(index)(&|-|\.)?/", strtolower($this->referer), $output) or $this->referer == $this->config->webRoot or empty($this->referer) or preg_match("/\/www\/$/", strtolower($this->referer), $output));

            $this->loadModel('misc');
            $this->loadModel('extension');
            $this->view->title        = $this->lang->user->login;
            $this->view->referer      = $this->referer;
            $this->view->s            = zget($this->config->global, 'sn', '');
            $this->view->keepLogin    = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';
            $this->view->rand         = $this->user->updateSessionRandom();
            $this->view->unsafeSites  = $this->misc->checkOneClickPackage();
            $this->view->plugins      = $this->extension->getExpiringPlugins(true);
            $this->view->loginExpired = $loginExpired;
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
        if($module == 'requirement') $this->app->loadLang('story');
        if($module != 'requirement') $this->app->loadLang($module);

        $this->app->loadLang('my');

        /* Check deny type. */
        $rights  = $this->app->user->rights['rights'];
        $acls    = $this->app->user->rights['acls'];

        $module  = strtolower($module);
        $method  = strtolower($method);

        $denyType = 'nopriv';
        if(isset($rights[$module][$method]))
        {
            $menu = isset($this->lang->navGroup->$module) ? $this->lang->navGroup->$module : $module;
            $menu = strtolower($menu);

            if(!isset($acls['views'][$menu])) $denyType = 'noview';
            $this->view->menu = $menu;
        }

        $this->view->denyType = $denyType;

        $this->display();
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
        helper::setcookie('za', '', time() - 3600, $this->config->webRoot);
        helper::setcookie('zp', '', time() - 3600, $this->config->webRoot);
        helper::setcookie('tab', '', time() - 3600, $this->config->webRoot);

        $_SESSION = array();
        session_destroy();

        if($this->app->getViewType() == 'json') return $this->send(array('status' => 'success'));
        $vars = !empty($referer) ? "referer=$referer" : '';
        return $this->send(array('result' => 'success', 'load' => $this->createLink('user', 'login', $vars)));
    }

    /**
     * Reset password.
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

        $resetFileName = $this->session->resetFileName;

        $needCreateFile = false;
        if(!file_exists($resetFileName) or (time() - filemtime($resetFileName)) > 60 * 2) $needCreateFile = true;

        if($_POST)
        {
            if($needCreateFile) return $this->send(array('result' => 'success', 'load' => true));

            $result = $this->user->resetPassword();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!$result) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->resetFail));

            $referer = helper::safe64Encode($this->createLink('index', 'index'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->user->resetSuccess, 'locate' => $this->createLink('user', 'logout', 'referer=' . $referer)));
        }

        /* Remove the real path for security reason. */
        $resetFileName = str_replace($this->app->getBasePath(), '', $resetFileName);

        $this->view->title          = $this->lang->user->resetPassword;
        $this->view->status         = 'reset';
        $this->view->needCreateFile = $needCreateFile;
        $this->view->resetFileName  = $resetFileName;

        $this->display();
    }

    /**
     * Forget password.
     *
     * @access public
     * @return void
     */
    public function forgetPassword()
    {
        $this->app->loadLang('admin');
        $this->loadModel('mail');

        if(!empty($_POST))
        {
            /* Check account and email. */
            $account = $_POST['account'];
            $email   = $_POST['email'];
            if(empty($account)) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->accountEmpty));
            if(empty($email)) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->emailEmpty));

            $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($account)->fetch();
            if(empty($user)) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->noUser));
            if(empty($user->email)) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->noEmail));

            if($user->email != $email) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->errorEmail));
            if(!$this->config->mail->turnon) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->emailSetting));

            $code = uniqid();
            $this->dao->update(TABLE_USER)->set('resetToken')->eq(json_encode(array('code' => $code, 'endTime' => strtotime("+{$this->config->user->resetPasswordTimeout} minutes"))))->where('account')->eq($account)->exec();

            $result = $this->mail->send($account, $this->lang->user->resetPWD, sprintf($this->lang->mail->forgetPassword, commonModel::getSysURL() . inlink('resetPassword', 'code=' . $code)), '', true, array(), true);
            if(strstr($result, 'ERROR')) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->sendMailFail), true);

            return $this->send(array('result' => 'success', 'message' => $this->lang->user->sendEmailSuccess));
        }

        $this->view->title = $this->lang->user->resetPassword;
        $this->display();
    }

    /**
     * Reset password.
     *
     * @param  string  $code
     * @access public
     * @return void
     */
    public function resetPassword($code)
    {
        $expired = true;
        $user    = $this->dao->select('account, resetToken')->from(TABLE_USER)->where('resetToken')->like('%"' . $code . '"%')->fetch();
        if($user)
        {
            $resetToken = json_decode($user->resetToken);
            if($resetToken->endTime >= time()) $expired = false;
        }

        if(!empty($_POST))
        {
            if($expired) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->linkExpired));
            $_POST['account'] = $user->account;

            $this->user->resetPassword();
            if(dao::isError())
            {
                if(empty($_POST['password2'])) dao::$errors['password2'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);

                $response['result']  = 'fail';
                $response['message'] = dao::getError();

                return $this->send($response);
            }

            $this->dao->update(TABLE_USER)->set('resetToken')->eq('')->where('account')->eq($this->post->account)->exec();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['load']    = inlink('login');

            return $this->send($response);
        }

        $this->view->title   = $this->lang->user->resetPWD;
        $this->view->expired = $expired;
        $this->view->user    = empty($user) ? '' : $user;
        $this->view->rand    = $this->user->updateSessionRandom();

        $this->display();
    }

    /**
     * User dynamic.
     *
     * @param  int    $userID
     * @param  string $period
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction     next|pre
     * @access public
     * @return void
     */
    public function dynamic($userID = '', $period = 'today', $recTotal = 0, $date = '', $direction = 'next')
    {
        $user    = $this->user->getById($userID, 'id');
        $account = $user->account;
        $deptID  = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users   = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');

        /* set menus. */
        $this->view->userList = $this->user->setUserList($users, $userID);

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
        $date       = empty($date) ? '' : date('Y-m-d', $date);
        $actions    = $this->loadModel('action')->getDynamic($account, $period, $orderBy, 50, 'all', 'all', 'all', $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction);
        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($dateGroups, 1) - count($dateGroups) : $this->action->getDynamicCount();

        /* Assign. */
        $this->view->title      = $this->lang->user->common . $this->lang->colon . $this->lang->user->dynamic;
        $this->view->type       = $period;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->recTotal   = $recTotal;
        $this->view->user       = $user;
        $this->view->dateGroups = $dateGroups;
        $this->view->direction  = $direction;
        $this->display();
    }

	/**
     * crop avatar
     *
     * @param  int    $image
     * @access public
     * @return void
     */
    public function cropAvatar($image)
    {
        $image = $this->loadModel('file')->getByID($image);

        if(!empty($_POST))
        {
            $size = fixer::input('post')->get();
            $this->file->cropImage($image->realPath, $image->realPath, $size->left, $size->top, $size->right - $size->left, $size->bottom - $size->top, $size->scaled ? $size->scaleWidth : 0, $size->scaled ? $size->scaleHeight : 0);

            $this->app->user->avatar = $image->webPath;
            $this->session->set('user', $this->app->user);
            $this->dao->update(TABLE_USER)->set('avatar')->eq($image->webPath)->where('account')->eq($this->app->user->account)->exec();
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadModal('" . helper::createLink('my', 'profile') . "', 'profile', {}, window.updateUserAvatar);"));
        }

        $this->view->user  = $this->user->getById($this->app->user->account);
        $this->view->title = $this->lang->user->cropAvatar;
        $this->view->image = $image;
        $this->display();
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
        $html .= html::submitButton('', '', 'btn btn-primary');
        $html .= '</form>';
        echo $html;
    }

    /**
     * AJAX: 获取联系人列表人员。
     * AJAX: get users from a contact list.
     *
     * @param  int    $contactListID
     * @access public
     * @return string
     */
    public function ajaxGetContactUsers(int $contactListID)
    {
        $list  = $contactListID ? $this->user->getContactListByID($contactListID) : '';
        $users = $this->user->getContactUserPairs($list ? $list->userList : '');

        $items = array();
        foreach($users as $userID => $userName) $items[] = array('text' => $userName, 'value' => $userID);
        return print(json_encode($items));
    }

    /**
     * Ajax: 获取联系人列表。
     * Ajax get contact list.
     *
     * @access public
     * @return string
     */
    public function ajaxGetContactList()
    {
        $contactList = $this->user->getContactLists($this->app->user->account, 'withnote');

        $items = array();
        foreach($contactList as $contactID => $contactName) $items[] = array('text' => $contactName, 'value' => $contactID);
        return print(json_encode($items));
    }

    /**
     * Ajax print templates.
     *
     * @param  int    $type
     * @param  string $link
     * @access public
     * @return void
     */
    public function ajaxPrintTemplates($type, $link = '')
    {
        $this->view->link      = $link;
        $this->view->type      = $type;
        $this->view->templates = $this->user->getUserTemplates($type);
        $this->display();
    }

    /**
     * Save current template.
     *
     * @access public
     * @return string
     */
    public function ajaxSaveTemplate($type)
    {
        $this->user->saveUserTemplate($type);
        if(dao::isError()) echo js::error(dao::getError(), $full = false);
        return print($this->fetch('user', 'ajaxPrintTemplates', "type=$type"));
    }

    /**
     * Delete a user template.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxDeleteTemplate($templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)->where('id')->eq($templateID)
            ->beginIF(!$this->app->user->admin)->andWhere('account')->eq($this->app->user->account)->fi()
            ->exec();
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
        $users = $this->user->getPairs($parsedParams['params'], $parsedParams['usersToAppended']);

        $search   = $this->get->search;
        $limit    = $this->get->limit;
        $index    = 0;
        $newUsers = array();
        if(empty($search)) return array();
        foreach($users as $account => $realname)
        {
            if($index >= $limit) break;
            if(stripos($account, $search) === false and stripos($realname, $search) === false) continue;
            $index ++;
            $newUsers[$account] = $realname;
        }

        echo json_encode($newUsers);
    }

    /**
     * Ajax get group by vision.
     *
     * @param  string  $visions rnd|lite
     * @param  int     $i
     * @param  string  $selected
     * @access public
     * @return string
     */
    public function ajaxGetGroup($visions, $i = 0, $selected = '')
    {
        $visions   = explode(',', $visions);
        $groupList = $this->user->getGroupsByVisions($visions);
        if($i)
        {
            if($i > 1) $groupList = $groupList + array('ditto' => $this->lang->user->ditto);
            return print(html::select("group[$i][]", $groupList, $selected, 'size=3 multiple=multiple class="form-control chosen"'));
        }
        $items = array();
        foreach($groupList as $groupID => $groupName) $items[] = array('text' => $groupName, 'value' => $groupID, 'keys' => $groupName);
        return print(json_encode($items));
    }

    /**
     * Refresh random for login
     *
     * @access public
     * @return void
     */
    public function refreshRandom()
    {
        $rand = (string)$this->user->updateSessionRandom();
        echo $rand;
    }
}
