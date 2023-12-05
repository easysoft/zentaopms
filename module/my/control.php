<?php
declare(strict_types=1);
/**
 * The control file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: control.php 5020 2013-07-05 02:03:26Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class my extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('user');
        $this->loadModel('dept');
    }

    /**
     * Index page, goto todo.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->title = $this->lang->my->common;
        echo $this->fetch('block', 'dashboard', 'dashboard=my');
    }

    /**
     * 积分列表。
     * Get score list
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function score(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $scores = $this->loadModel('score')->getListByAccount($this->app->user->account, $pager);

        $this->view->title      = $this->lang->score->common;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->pager      = $pager;
        $this->view->scores     = $scores;

        $this->display();
    }

    /**
     * 日程列表。
     * My calendar.
     *
     * @access public
     * @return void
     */
    public function calendar()
    {
        $this->locate($this->createLink('my', 'todo', 'type=before&userID=&status=undone'));
    }

    /**
     * 待处理列表。
     * My work view.
     *
     * @param  string $mode
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function work(string $mode = 'task', string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if($mode == 'testcase' && $type == 'assignedTo') $type = 'assigntome';

        $this->lang->my->featureBar[$this->app->rawMethod] = $this->lang->my->featureBar[$this->app->rawMethod][$mode];
        echo $this->fetch('my', $mode, "type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        //$this->showWorkCount($recTotal, $recPerPage, $pageID);
    }

    /**
     * Show to-do work count.
     *
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function showWorkCount($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('task');
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('testcase');
        $this->loadModel('testtask');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get the number of tasks assigned to me. */
        $tasks     = $this->task->getUserTasks($this->app->user->account, 'assignedTo', 0, $pager);
        $taskCount = $pager->recTotal;

        /* Get the number of stories assigned to me. */
        $assignedToStories    = $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'story', false, 'all');
        $assignedToStoryCount = $pager->recTotal;
        $reviewByStories      = $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'story', false, 'all');
        $reviewByStoryCount   = $pager->recTotal;
        $storyCount           = $assignedToStoryCount + $reviewByStoryCount;

        $requirementCount = 0;
        $isOpenedURAndSR  = $this->config->URAndSR ? 1 : 0;
        if($isOpenedURAndSR)
        {
            /* Get the number of requirements assigned to me. */
            $assignedRequirements     = $this->story->getUserStories($this->app->user->account, 'assignedTo', 'id_desc', $pager, 'requirement', false, 'all');
            $assignedRequirementCount = $pager->recTotal;
            $reviewByRequirements     = $this->story->getUserStories($this->app->user->account, 'reviewBy', 'id_desc', $pager, 'requirement', false, 'all');
            $reviewByRequirementCount = $pager->recTotal;
            $requirementCount         = $assignedRequirementCount + $reviewByRequirementCount;
        }

        /* Get the number of bugs assigned to me. */
        $bugs     = $this->bug->getUserBugs($this->app->user->account, 'assignedTo', 'id_desc', 0, $pager);
        $bugCount = $pager->recTotal;

        /* Get the number of testcases assigned to me. */
        $cases     = $this->testcase->getByAssignedTo($this->app->user->account, $auto = 'skip', 'id_desc', $pager);
        $caseCount = $pager->recTotal;

        /* Get the number of testtasks assigned to me. */
        $testTasks     = $this->testtask->getByUser($this->app->user->account, $pager, 'id_desc', 'wait');
        $testTaskCount = $pager->recTotal;

        $issueCount   = 0;
        $riskCount    = 0;
        $ncCount      = 0;
        $qaCount      = 0;
        $meetingCount = 0;
        $ticketCount  = 0;
        $isMax        = in_array($this->config->edition, array('max', 'ipd')) ? 1 : 0;

        $feedbackCount = 0;
        $isBiz         = $this->config->edition == 'biz' ? 1 : 0;

        if($this->config->edition != 'open')
        {
            $feedbacks     = $this->loadModel('feedback')->getList('assigntome', 'id_desc', $pager);
            $feedbackCount = $pager->recTotal;

            $ticketList  = $this->loadModel('ticket')->getList('assignedtome', 'id_desc', $pager);
            $ticketCount = $pager->recTotal;
        }

        if($isMax)
        {
            $this->loadModel('issue');
            $this->loadModel('risk');
            $this->loadModel('review');
            $this->loadModel('meeting');

            /* Get the number of issues assigned to me. */
            $issues     = $this->issue->getUserIssues('assignedTo', 0, $this->app->user->account, 'id_desc', $pager);
            $issueCount = $pager->recTotal;

            /* Get the number of risks assigned to me. */
            $risks     = $this->risk->getUserRisks('assignedTo', $this->app->user->account, 'id_desc', $pager);
            $riskCount = $pager->recTotal;

            /* Get the number of nc assigned to me. */
            $ncList  = $this->my->getNcList('assignedToMe', 'id_desc', $pager, 'active');
            $ncCount = $pager->recTotal;

            /* Get the number of nc assigned to me. */
            $auditplanList  = $this->loadModel('auditplan')->getList(0, 'mychecking', '', 'id_desc', $pager);
            $auditplanCount = $pager->recTotal;
            $qaCount        = $ncCount + $auditplanCount;

            /* Get the number of meetings assigned to me. */
            $meetings     = $this->meeting->getListByUser('futureMeeting', 'id_desc', 0, $pager);
            $meetingCount = $pager->recTotal;
        }

        if($this->app->viewType != 'json')
        {
echo <<<EOF
<script>
var taskCount     = $taskCount;
var storyCount    = $storyCount;
var bugCount      = $bugCount;
var caseCount     = $caseCount;
var testTaskCount = $testTaskCount;

var isOpenedURAndSR = $isOpenedURAndSR;
if(isOpenedURAndSR !== 0) var requirementCount = $requirementCount;

var isMax = $isMax;
var isBiz = $isBiz;

if(isBiz !== 0 || isMax !== 0)
{
    var feedbackCount = $feedbackCount;
    var ticketCount   = $ticketCount;
}

if(isMax !== 0)
{
    var issueCount   = $issueCount;
    var riskCount    = $riskCount;
    var qaCount      = $qaCount;
    var meetingCount = $meetingCount;
}
</script>
EOF;
        }
    }

    /**
     * 贡献列表。
     * My contribute view.
     *
     * @param  string $mode
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function contribute(string $mode = 'task', string $type = 'openedBy', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(($mode == 'issue' || $mode == 'risk') && $type == 'openedBy') $type = 'createdBy';
        if($mode == 'testtask' && $type == 'openedBy') $type = 'done';
        if(($mode == 'doc' || $mode == 'testcase') && $type == 'openedBy') $type = 'openedbyme';

        $this->lang->my->featureBar[$this->app->rawMethod] = $this->lang->my->featureBar[$this->app->rawMethod][$mode];
        echo $this->fetch('my', $mode, "type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 待办列表。
     * My todos.
     *
     * @param  string $type
     * @param  int    $userID
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function todo(string $type = 'before', string $userID = '', string $status = 'all', string $orderBy = "date_desc,status,begin", int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'my');
        $this->session->set('taskList',     $uri, 'my');
        $this->session->set('storyList',    $uri, 'my');
        $this->session->set('testtaskList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->user->getById($userID, 'id');
        $account = $user->account;

        /* Append id for second sort, get todos and tasks. */
        $sort = common::appendOrder($orderBy);
        if($type == 'before') $status = 'undone';
        $todos = $this->loadModel('todo')->getList($type, $account, $status, 0, $pager, $sort);
        $tasks = $this->loadModel('task')->getUserSuspendedTasks($account);

        $count = array('wait' => 0, 'doing' => 0);
        foreach($todos as $key => $todo)
        {
            if($todo->type == 'task' && isset($tasks[$todo->objectID])) unset($todos[$key]);
            if($todo->status == 'wait' || $todo->status == 'doing')  $count[$todo->status] ++;
            if($todo->date == '2030-01-01') $todo->date = $this->lang->todo->future;
        }

        /* Assign. */
        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->todos        = $todos;
        $this->view->date         = (int)$type == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($type));
        $this->view->type         = $type;
        $this->view->status       = $status;
        $this->view->user         = $user;
        $this->view->users        = $this->user->getPairs('noletter');
        $this->view->account      = $this->app->user->account;
        $this->view->times        = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time         = date::now();
        $this->view->waitCount    = $count['wait'];
        $this->view->doingCount   = $count['doing'];
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * 需求列表。
     * My stories.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'planTitle') !== false) $sort = str_replace('planTitle', 'plan', $sort);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $queryID = $type == 'bysearch' ? $param : 0;

        $this->loadModel('story');
        if($type == 'assignedBy')
        {
            $stories = $this->my->getAssignedByMe($this->app->user->account, $pager, $sort, 'story');
        }
        elseif($type == 'bysearch')
        {
            $stories = $this->my->getStoriesBySearch($queryID, $this->app->rawMethod, $sort, $pager);
        }
        else
        {
            $stories = $this->story->getUserStories($this->app->user->account, $type, $sort, $pager, 'story', false, 'all');
        }
        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        foreach($stories as $story) $story->estimate = $story->estimate . $this->config->hourUnit;

         /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=story&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildStorySearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title    = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->stories  = $stories;
        $this->view->users    = $this->user->getPairs('noletter');
        $this->view->type     = $type;
        $this->view->param    = $param;
        $this->view->mode     = 'story';
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->display();
    }

    /**
     * 用户需求列表。
     * My requirements.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function requirement(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'productTitle') !== false) $sort = str_replace('productTitle', 'product', $sort);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $queryID = ($type == 'bysearch') ? $param : 0;

        $this->loadModel('story');
        if($type == 'assignedBy')
        {
            $stories = $this->my->getAssignedByMe($this->app->user->account, $pager, $sort, 'requirement');
        }
        elseif($type == 'bysearch')
        {
            $stories = $this->my->getRequirementsBySearch($queryID, $this->app->rawMethod, $sort, $pager);
        }
        else
        {
            $stories = $this->story->getUserStories($this->app->user->account, $type, $sort, $pager, 'requirement', false, 'all');
        }
        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        foreach($stories as $story) $story->estimate = $story->estimate . $this->config->hourUnit;

         /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=requirement&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildRequirementSearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title    = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->stories  = $stories;
        $this->view->users    = $this->user->getPairs('noletter');
        $this->view->type     = $type;
        $this->view->param    = $param;
        $this->view->mode     = 'requirement';
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->display();
    }

    /**
     * 任务列表。
     * My tasks
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        if($type != 'bySearch')            $this->session->set('myTaskType', $type);
        if($this->app->viewType != 'json') $this->session->set('taskList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($orderBy, 'estimateLabel') !== false || strpos($orderBy, 'consumedLabel') !== false || strpos($orderBy, 'leftLabel') !== false) $sort = str_replace('Label', '', $sort);

        /* Get tasks. */
        $this->loadModel('task');
        $queryID = $type == 'bySearch' ? $param : 0;
        if($type == 'assignedBy')
        {
            $tasks = $this->my->getAssignedByMe($this->app->user->account, $pager, $sort, 'task');
        }
        elseif($type == 'bySearch')
        {
            $tasks = $this->my->getTasksBySearch($this->app->user->account, 0, $pager, $sort, $queryID);
        }
        else
        {
            $tasks = $this->task->getUserTasks($this->app->user->account, $type, 0, $pager, $sort, $queryID);
        }
        $summary = $this->loadModel('execution')->summary($tasks);
        $tasks   = $this->myZen->buildTaskData($tasks);

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=task&browseType=bySearch&queryID=myQueryID");
        $this->my->buildTaskSearchForm($queryID, $actionURL);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $this->view->tabID      = 'task';
        $this->view->tasks      = $this->app->viewType == 'json' ?  array_values($tasks) : $tasks;
        $this->view->summary    = $summary;
        $this->view->type       = $type;
        $this->view->kanbanList = $this->execution->getPairs(0, 'kanban');
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->view->mode       = 'task';
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * bug 列表。
     * My bugs.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bug(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. load Lang. */
        $this->loadModel('bug');
        $queryID  = $type == 'bySearch' ? $param : 0;
        if($type != 'bySearch')            $this->session->set('myBugType', $type);
        if($this->app->viewType != 'json') $this->session->set('bugList', $this->app->getURI(true), 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        if(strpos($sort, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);
        if($type == 'assignedBy')
        {
            $bugs = $this->my->getAssignedByMe($this->app->user->account, $pager, $sort, 'bug');
        }
        else
        {
            $bugs = $this->bug->getUserBugs($this->app->user->account, $type, $sort, 0, $pager, 0, $queryID);
        }
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);
        $bugs = $this->bug->batchAppendDelayedDays($bugs);

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=bug&browseType=bySearch&queryID=myQueryID");
        $this->my->buildBugSearchForm($queryID, $actionURL);

        /* assign. */
        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->bugs        = $bugs;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->memberPairs = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->tabID       = 'bug';
        $this->view->type        = $type;
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->mode        = 'bug';

        $this->display();
    }

    /**
     * 测试单列表。
     * My test task.
     *
     * @param  string $type       wait|done
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask(string $type = 'wait', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Save session. */
        if($this->app->viewType != 'json')
        {
            $uri = $this->app->getURI(true);
            $this->session->set('testtaskList', $uri, 'qa');
            $this->session->set('reportList',   $uri, 'qa');
            $this->session->set('buildList',    $uri, 'execution');
        }

        /* Append id for second sort. */
        $this->app->loadLang('project');
        $sort  = common::appendOrder($orderBy);
        $count = array('wait' => 0, 'doing' => 0, 'blocked' => 0);
        $tasks = $this->loadModel('testtask')->getByUser($this->app->user->account, $pager, $sort, $type);
        foreach($tasks as $task)
        {
            if($task->status == 'wait' || $task->status == 'doing' || $task->status == 'blocked') $count[$task->status] ++;
            if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
            if(empty($task->executionMultiple)) $task->executionName = $task->projectName . "({$this->lang->project->disableExecution})";
        }

        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestTask;
        $this->view->tasks        = $tasks;
        $this->view->type         = $type;
        $this->view->waitCount    = $count['wait'];
        $this->view->testingCount = $count['doing'];
        $this->view->blockedCount = $count['blocked'];
        $this->view->mode         = 'testtask';
        $this->view->pager        = $pager;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * 用例列表。
     * My test case.
     *
     * @param  string $type      assigntome|openedbyme
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase(string $type = 'assigntome', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'qa');
        $this->session->set('bugList',  $uri . "#app={$this->app->tab}", 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort    = common::appendOrder($orderBy);
        $queryID = $type == 'bysearch' ? $param : 0;

        $cases = array();
        if($type == 'assigntome') $cases = $this->loadModel('testcase')->getByAssignedTo($this->app->user->account, 'skip|run', $sort, $pager);
        if($type == 'openedbyme') $cases = $this->loadModel('testcase')->getByOpenedBy($this->app->user->account, 'skip', $sort, $pager);
        if($type == 'bysearch' && $this->app->rawMethod == 'contribute') $cases = $this->my->getTestcasesBySearch($queryID, 'contribute', $orderBy, $pager);
        if($type == 'bysearch' && $this->app->rawMethod == 'work')       $cases = $this->my->getTestcasesBySearch($queryID, 'work', $orderBy, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->myZen->buildCaseData($cases, $type);

        /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=testcase&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildTestCaseSearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestCase;
        $this->view->cases      = $cases;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->tabID      = 'test';
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'testcase';

        $this->display();
    }

    /**
     * 文档列表。
     * Doc page of my.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function doc(string $type = 'openedbyme', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session, load lang. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',   $uri, 'product');
        $this->session->set('executionList', $uri, 'execution');
        $this->session->set('projectList',   $uri, 'project');
        if($this->app->viewType != 'json') $this->session->set('docList', $uri, 'doc');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort    = common::appendOrder($orderBy);
        $queryID = $type == 'bySearch' ? $param : 0;
        $docs    = $this->loadModel('doc')->getDocsByBrowseType($type, $queryID, 0, $sort, $pager);

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=doc&browseType=bySearch&queryID=myQueryID");
        $this->loadModel('doc')->buildSearchForm(0, array(), $queryID, $actionURL, 'contribute');

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->doc;
        $this->view->docs       = $docs;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->param      = $param;

        $this->display();

    }

    /**
     * 我的项目列表。
     * My projects.
     *
     * @param  string  $status doing|wait|suspended|closed|openedbyme
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @param  string  $orderBy
     * @access public
     * @return void
     */
    public function project(string $status = 'doing', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->app->loadLang('project');

        $uri = $this->app->getURI(true);
        $this->app->session->set('programList', $uri, 'program');
        $this->app->session->set('projectList', $uri, 'my');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get PM id list. */
        $accounts = array();
        $projects = $this->user->getObjects($this->app->user->account, 'project', $status, $orderBy, $pager);
        foreach($projects as $project)
        {
            if(!empty($project->PM) && !in_array($project->PM, $accounts)) $accounts[] = $project->PM;
        }
        $PMList = $this->user->getListByAccounts($accounts, 'account');

        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->projects    = $projects;
        $this->view->PMList      = $PMList;
        $this->view->pager       = $pager;
        $this->view->status      = $status;
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->orderBy     = $orderBy;
        $this->view->usersAvatar = $this->user->getAvatarPairs('');
        $this->display();
    }

    /**
     * 我的执行列表。
     * My executions.
     *
     * @param  string  $type       undone|done
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function execution(string $type = 'undone', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->app->loadLang('execution');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $executions  = $this->user->getObjects($this->app->user->account, 'execution', $type, $orderBy, $pager);

        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->execution;
        $this->view->tabID       = 'project';
        $this->view->executions  = $executions;
        $this->view->parentGroup = $this->loadModel('execution')->getChildIdGroup(array_keys($executions));
        $this->view->type        = $type;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->mode        = 'execution';

        $this->display();
    }

    /**
     * 问题列表。
     * My issues.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function issue(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Set session. */
        $this->app->session->set('issueList', $this->app->getURI(true), 'project');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $browseType = strtolower($type);
        $queryID    = $browseType == 'bysearch' ? $param : 0;
        $actionURL  = $this->createLink('my', $this->app->rawMethod, "mode=issue&type=bySearch&param=myQueryID");
        $this->loadModel('issue')->buildSearchForm($actionURL, $queryID);

        $this->view->title       = $this->lang->my->issue;
        $this->view->mode        = 'issue';
        $this->view->users       = $this->user->getPairs('noclosed|noletter');
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->type        = $type;
        $this->view->param       = $param;
        $this->view->issues      = $type == 'assignedBy' ? $this->my->getAssignedByMe($this->app->user->account, $pager,  $orderBy, 'issue') : $this->loadModel('issue')->getUserIssues($type, $queryID, $this->app->user->account, $orderBy, $pager);
        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();
        $this->display();
    }

    /**
     * 风险列表。
     * My risks.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function risk(string $type = 'assignedTo', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Set session. */
        $this->app->session->set('riskList', $this->app->getURI(true), 'project');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $queryID       = $type == 'bysearch' ? $param : 0;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=risk&type=bysearch&param=myQueryID");
        $this->my->buildRiskSearchForm($queryID, $actionURL, $currentMethod);

        /* Get risks by type*/
        $this->loadModel('risk');
        if($type == 'assignedBy')
        {
            $risks = $this->my->getAssignedByMe($this->app->user->account, $pager, $orderBy, 'risk');
        }
        elseif($type == 'bysearch')
        {
            $risks = $this->my->getRisksBySearch($queryID, $currentMethod, $orderBy, $pager);
        }
        else
        {
            $risks = $this->risk->getUserRisks($type, $this->app->user->account, $orderBy, $pager);
        }

        $this->view->title       = $this->lang->my->risk;
        $this->view->risks       = $risks;
        $this->view->users       = $this->user->getPairs('noclosed|noletter');
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->type        = $type;
        $this->view->mode        = 'risk';
        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();
        $this->display();
    }

    /**
     * 评审列表。
     * My audits.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function audit(string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $typeList = array();
        if($this->app->rawMethod == 'contribute')
        {
            $reviewList = $this->my->getReviewedList($browseType, $orderBy, $pager);
        }
        else
        {
            $typeList = $this->my->getReviewingTypeList();
            if(!isset($typeList->$browseType)) $browseType = 'all';

            $this->lang->my->featureBar['audit'] = (array)$typeList;
            $reviewList = $this->my->getReviewingList($browseType, $orderBy, $pager);
        }

        $flows = array();
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $this->app->loadLang('approval');
            $flows = $this->my->getFlowPairs();
        }

        $this->view->title       = $this->lang->review->common;
        $this->view->users       = $this->user->getPairs('noclosed|noletter');
        $this->view->reviewList  = $reviewList;
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->mode        = 'audit';
        $this->view->flows       = $flows;
        $this->display();
    }

    /**
     * 质量保证计划列表。
     * My auditplans.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function auditplan(string $browseType = 'myChecking', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Set session. */
        $this->session->set('auditplanList', $this->app->getURI(true));

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager  = pager::init($recTotal, $recPerPage, $pageID);

        $auditplans = $this->loadModel('auditplan')->getList(0, $browseType, $param, $orderBy, $pager);

        $this->app->loadLang('process');
        $this->view->executions      = $this->loadModel('execution')->getPairs();
        $this->view->projects        = $this->loadModel('project')->getPairs();
        $this->view->processTypeList = $this->lang->process->classify;
        $this->view->processes       = $this->loadModel('pssp')->getProcesses();
        $this->view->activities      = $this->pssp->getActivityPairs();
        $this->view->outputs         = $this->pssp->getOutputPairs();

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->auditplan;
        $this->view->browseType = $browseType;
        $this->view->auditplans = $auditplans;
        $this->view->users      = $this->user->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->view->mode       = 'auditplan';
        $this->display();
    }

    /**
     * 不符合项列表。
     * My ncs.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function nc(string $browseType = 'assignedToMe', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Set session. */
        $this->session->set('ncList', $this->app->getURI(true));

        $this->app->loadLang('nc');

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $status = $this->app->rawMethod == 'contribute' ? '' : 'active';
        $ncList = $browseType == 'assignedBy' ? $this->my->getAssignedByMe($this->app->user->account, $pager, $orderBy, 'nc') : $this->my->getNcList($browseType, $orderBy, $pager, $status);

        foreach($ncList as $nc) $ncIdList[] = $nc->id;
        $this->session->set('ncIdList', isset($ncIdList) ? $ncIdList : '');

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->nc;
        $this->view->browseType = $browseType;
        $this->view->ncs        = $ncList;
        $this->view->users      = $this->user->getPairs('noclosed|noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->view->mode       = 'nc';
        $this->display();
    }

    /**
     * 会议列表。
     * My meeting list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function myMeeting(string $browseType = 'futureMeeting', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('meetingList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = $browseType == 'bysearch' ? $param : 0;
        $actionURL = $this->createLink('my', 'work', "mode=myMeeting&browseType=bysearch&param=myQueryID");
        $this->loadModel('meeting')->buildSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myMeeting;
        $this->view->browseType = $browseType;
        $this->view->meetings   = $this->meeting->getListByUser($browseType, $orderBy, $queryID, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->users      = $this->user->getPairs('all,noletter');
        $this->view->queryID    = $queryID;
        $this->view->mode       = 'myMeeting';
        $this->view->projects   = array(0 => '') + $this->loadModel('project')->getPairsByProgram(0, 'all', true);
        $this->view->executions = array(0 => '') + $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        $this->view->rooms      = $this->loadModel('meetingroom')->getPairs();
        $this->display();
    }

    /**
     * 反馈列表。
     * Feedback list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function feedback(string $browseType = 'assigntome', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('feedbackList', $this->app->getURI(true), 'feedback');

        $queryID = $browseType == 'bysearch' ? (int)$param : 0;
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $feedbacks = $browseType != 'bysearch' ?$this->loadModel('feedback')->getList($browseType, $orderBy, $pager) : $this->loadModel('feedback')->getBySearch($queryID, $orderBy, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'workFeedback');

        $this->myZen->assignRelatedData($feedbacks);
        $this->myZen->buildSearchFormForFeedback($queryID, $orderBy);

        $this->loadModel('datatable');
        $this->lang->datatable->moduleSetting  = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->moduleSetting);
        $this->lang->datatable->showModule     = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->showModule);
        $this->lang->datatable->showModuleList = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->showModuleList);

        $this->view->title       = $this->lang->my->feedback;
        $this->view->mode        = 'feedback';
        $this->view->browseType  = $browseType;
        $this->view->feedbacks   = $feedbacks;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->depts       = $this->dept->getOptionMenu();
        $this->view->users       = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->projects    = $this->loadModel('project')->getPairsByProgram(0, 'noclosed');
        $this->view->allProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
        $this->view->modulePairs = $this->tree->getModulePairs(0, 'feedback');
        $this->view->modules     = $this->tree->getOptionMenu(0, 'feedback', 0);
        $this->display();
    }

    /**
     * 工单列表。
     * My ticket.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function ticket(string $browseType = 'assignedtome', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('ticketList', $this->app->getURI(true), 'ticket');

        $this->app->loadClass('pager', $static = true);
        $pager   = pager::init($recTotal, $recPerPage, $pageID);
        $queryID = $browseType == 'bysearch' ? $param : 0;
        if($browseType != 'bysearch')
        {
            $tickets = $this->loadModel('ticket')->getList($browseType, $orderBy, $pager);
        }
        else
        {
            $tickets = $this->loadModel('ticket')->getBySearch($queryID, $orderBy, $pager);
        }

        $actionURL = $this->createLink('my', 'work', "mode=ticket&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildTicketSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->ticket->browse;
        $this->view->products   = $this->loadModel('feedback')->getGrantProducts();
        $this->view->users      = $this->user->getPairs('noclosed|nodeleted|noletter');
        $this->view->tickets    = $tickets;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;
        $this->view->mode       = 'ticket';
        $this->display();
    }

    /**
     * 团队列表。
     * My team.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function team(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $this->lang->navGroup->my = 'system';

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Get users by dept. */
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('company')->getUsers('inside', 'bydept', 0, $deptID, $sort, $pager);
        foreach($users as $user) unset($user->password); // Remove passwd.

        $this->view->title      = $this->lang->my->team;
        $this->view->users      = $users;
        $this->view->deptID     = $deptID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 编辑个人信息。
     * Edit profile
     *
     * @access public
     * @return void
     */
    public function editProfile()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        if(!empty($_POST))
        {
            $user = form::data($this->config->user->form->edit)
                ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
                ->add('id', $this->app->user->id)
                ->get();

            $this->user->update($user);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('my', 'profile')));
        }

        $userGroups = $this->loadModel('group')->getByAccount($this->app->user->account);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->fetchPairs('id', 'name');
        $this->display();
    }

    /**
     * 修改密码。
     * Change password.
     *
     * @access public
     * @return void
     */
    public function changePassword()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        $this->app->loadLang('admin');

        $isonlybody = isInModal();
        if(!$isonlybody) unset($this->lang->my->menu);

        if(!empty($_POST))
        {
            $this->user->updatePassword($this->app->user->id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($isonlybody) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('index', 'index')));
        }

        $this->view->isonlybody = $isonlybody;
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->display();
    }

    /**
     * 管理联系人。
     * Manage contacts.
     *
     * @param  int    $listID
     * @access public
     * @return void
     */
    public function manageContacts(int $listID = 0)
    {
        if($_POST)
        {
            if($listID)  $this->user->updateContactList($listID);
            if(!$listID) $this->user->createContactList();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "renderContactList"));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('manageContacts', "listID={$listID}")));
        }

        $list     = $listID ? $this->user->getContactListByID($listID) : null;
        $userList = !empty($list->userList) ? $list->userList : '';

        if($list)
        {
            $mode  = $list->account == $this->app->user->account ? 'edit' : 'view';
            $label = $list->account == $this->app->user->account ? $this->lang->my->manageContacts : $this->lang->my->viewContacts;
        }
        else
        {
            $mode  = 'create';
            $label = $this->lang->my->createContacts;
        }

        $userParams = empty($this->config->user->showDeleted) ? 'noletter|noempty|noclosed|noclosed|nodeleted' : 'noletter|noempty|noclosed|noclosed';
        $users      = $this->user->getPairs($userParams, $mode == 'new' ? '' : $userList, $this->config->maxCount);

        $this->view->title = $this->lang->my->common . $this->lang->colon . $label;
        $this->view->lists = $this->user->getContactLists($this->app->user->account, 'list');
        $this->view->users = $users;
        $this->view->mode  = $mode;
        $this->view->label = $label;
        $this->view->list  = $list;
        $this->display();
    }

    /**
     * 删除一个联系人列表。
     * Delete a contact list.
     *
     * @param  int    $listID
     * @access public
     * @return void
     */
    public function deleteContacts(int $listID)
    {
        $this->user->deleteContactList($listID);
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 构建联系人列表。
     * Build contact lists.
     *
     * @param  string $dropdownName
     * @param  string $attr
     * @access public
     * @return void
     */
    public function buildContactLists(string $dropdownName = 'mailto', string $attr = '')
    {
        $this->view->contactLists = $this->user->getContactLists();
        $this->view->dropdownName = $dropdownName;
        $this->view->attr         = $attr;
        $this->display();
    }

    /**
     * 查看我的个人档案。
     * View my profile.
     *
     * @access public
     * @return void
     */
    public function profile()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        $user = $this->user->getById($this->app->user->account);

        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->user         = $user;
        $this->view->groups       = $this->loadModel('group')->getByAccount($this->app->user->account);
        $this->view->deptPath     = $this->dept->getParents($user->dept);
        $this->view->personalData = $this->user->getPersonalData();
        $this->display();
    }

    /**
     * 查看个性化设置。
     * User preference setting.
     *
     * @access public
     * @return void
     */
    public function preference(string $showTip = 'true')
    {
        $this->loadModel('setting');

        if($_POST)
        {
            foreach($_POST as $key => $value) $this->setting->setItem("{$this->app->user->account}.common.$key", $value);

            $this->setting->setItem("{$this->app->user->account}.common.preferenceSetted", 1);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->preference;
        $this->view->showTip    = $showTip;

        $this->view->URSRList         = $this->loadModel('custom')->getURSRPairs();
        $this->view->URSR             = $this->setting->getURSR();
        $this->view->programLink      = isset($this->config->programLink)   ? $this->config->programLink   : 'program-browse';
        $this->view->productLink      = isset($this->config->productLink)   ? $this->config->productLink   : 'product-all';
        $this->view->projectLink      = isset($this->config->projectLink)   ? $this->config->projectLink   : 'project-browse';
        $this->view->executionLink    = isset($this->config->executionLink) ? $this->config->executionLink : 'execution-task';
        $this->view->preferenceSetted = isset($this->config->preferenceSetted) ? true : false;

        $this->display();
    }

    /**
     * 我的动态列表。
     * My dynamic.
     *
     * @param  string $type
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return void
     */
    public function dynamic(string $type = 'today', int $recTotal = 0, string $date = '', string $direction = 'next')
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',        $uri, 'product');
        $this->session->set('storyList',          $uri, 'product');
        $this->session->set('designList',         $uri, 'project');
        $this->session->set('productPlanList',    $uri, 'product');
        $this->session->set('releaseList',        $uri, 'product');
        $this->session->set('projectList',        $uri, 'project');
        $this->session->set('executionList',      $uri, 'execution');
        $this->session->set('taskList',           $uri, 'execution');
        $this->session->set('buildList',          $uri, 'execution');
        $this->session->set('bugList',            $uri, 'qa');
        $this->session->set('caseList',           $uri, 'qa');
        $this->session->set('caselibList',        $uri, 'qa');
        $this->session->set('testsuiteList',      $uri, 'qa');
        $this->session->set('testtaskList',       $uri, 'qa');
        $this->session->set('reportList',         $uri, 'qa');
        $this->session->set('docList',            $uri, 'doc');
        $this->session->set('todoList',           $uri, 'my');
        $this->session->set('riskList',           $uri, 'project');
        $this->session->set('issueList',          $uri, 'project');
        $this->session->set('stakeholderList',    $uri, 'project');
        $this->session->set('meetingroomList',    $uri, 'admin');
        $this->session->set('meetingList',        $uri, 'project');
        $this->session->set('meetingList',        $uri, 'assetlib');
        $this->session->set('storyLibList',       $uri, 'assetlib');
        $this->session->set('issueLibList',       $uri, 'assetlib');
        $this->session->set('riskLibList',        $uri, 'assetlib');
        $this->session->set('opportunityLibList', $uri, 'assetlib');
        $this->session->set('practiceLibList',    $uri, 'assetlib');
        $this->session->set('componentLibList',   $uri, 'assetlib');
        $this->session->set('opportunityList',    $uri, 'project');

        /* Append id for second sort. */
        $orderBy    = $direction == 'next' ? 'date_desc' : 'date_asc';
        $date       = empty($date) ? '' : date('Y-m-d', $date);
        $actions    = $this->loadModel('action')->getDynamic($this->app->user->account, $type, $orderBy, 50, 'all', 'all', 'all', $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction);
        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($dateGroups, 1) - count($dateGroups) : $this->action->getDynamicCount();

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->dynamic;
        $this->view->type       = $type;
        $this->view->orderBy    = $orderBy;
        $this->view->dateGroups = $dateGroups;
        $this->view->direction  = $direction;
        $this->view->recTotal   = $recTotal;
        $this->view->users      = $this->user->getPairs('noletter|nodeleted');
        $this->display();
    }

    /**
     * 上传头像。
     * Upload avatar.
     *
     * @access public
     * @return void
     */
    public function uploadAvatar()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $result = $this->user->uploadAvatar();
            return $this->send(array('result' => 'success', 'callback' => "loadModal('" . $this->createLink('user', 'cropavatar', "imageID={$result['fileID']}") . "', 'profile');"));
        }
    }

    /**
     * 取消与然之的绑定。
     * Unbind ranzhi.
     *
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unbind(string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->user->confirmUnbind, $this->createLink('my', 'unbind', "confirm=yes")));
        }
        else
        {
            $this->user->unbind($this->app->user->account);
            return print(js::locate($this->createLink('my', 'profile'), 'parent'));
        }
    }

    /**
     * Ajax: 切换界面.
     * Switch vision by ajax.
     *
     * @param  string $vision
     * @access public
     * @return void
     */
    public function ajaxSwitchVision(string $vision)
    {
        $_SESSION['vision'] = $vision;
        $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.vision", $vision);
        $this->config->vision = $vision;

        $_SESSION['user']->rights = $this->user->authorize($this->app->user->account);

        return $this->send(array('result' => 'success', 'load' => helper::createLink('index', 'index')));
    }
}
