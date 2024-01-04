<?php
/**
 * The control file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $this->display();
    }

    /**
     * Get score list
     *
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     *
     * @access public
     * @return mixed
     */
    public function score($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $scores = $this->loadModel('score')->getListByAccount($this->app->user->account, $pager);

        $this->view->title      = $this->lang->score->common;
        $this->view->user       = $this->loadModel('user')->getById($this->app->user->account);
        $this->view->pager      = $pager;
        $this->view->scores     = $scores;
        $this->view->position[] = $this->lang->score->record;

        $this->display();
    }

    /**
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
     * My work view.
     *
     * @param  string     $mode
     * @param  string     $type
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function work($mode = 'task', $type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('my', $mode, "type=$type&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");

        $this->showWorkCount($recTotal, $recPerPage, $pageID);
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
        $cases     = $this->testcase->getByAssignedTo($this->app->user->account, 'id_desc', $pager, 'skip');
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
        $isMax        = ($this->config->edition == 'max' or $this->config->edition == 'ipd') ? 1 : 0;

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
     * My contribute view.
     *
     * @param  string     $mode
     * @param  string     $type
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function contribute($mode = 'task', $type = 'openedBy', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(($mode == 'issue' or $mode == 'risk') and $type == 'openedBy') $type = 'createdBy';

        echo $this->fetch('my', $mode, "type=$type&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
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
    public function todo($type = 'before', $userID = '', $status = 'all', $orderBy = "date_desc,status,begin", $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'my');
        $this->session->set('taskList',     $uri, 'my');
        $this->session->set('storyList',    $uri, 'my');
        $this->session->set('testtaskList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        /* The title and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->position[] = $this->lang->my->todo;

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $todos = $this->loadModel('todo')->getList($type, $account, $status, 0, $pager, $sort);
        $tasks = $this->loadModel('task')->getUserSuspendedTasks($account);
        foreach($todos as $key => $todo)
        {
            if($todo->type == 'task' and isset($tasks[$todo->idvalue])) unset($todos[$key]);
        }

        /* Assign. */
        $this->view->todos        = $todos;
        $this->view->date         = (int)$type == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($type));
        $this->view->type         = $type;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->status       = $status;
        $this->view->user         = $user;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->account      = $this->app->user->account;
        $this->view->orderBy      = $orderBy == 'date_desc,status,begin,id_desc' ? '' : $orderBy;
        $this->view->pager        = $pager;
        $this->view->times        = date::buildTimeList($this->config->todo->times->begin, $this->config->todo->times->end, $this->config->todo->times->delta);
        $this->view->time         = date::now();
        $this->view->importFuture = ($type != 'today');

        $this->display();
    }

    /**
     * My stories.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('story');
        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $queryID = ($type == 'bysearch') ? (int)$param : 0;

        if($type == 'assignedBy')
        {
            $stories = $this->my->getAssignedByMe($this->app->user->account, '', $pager, $sort, 'story');
        }
        elseif($type == 'bysearch')
        {
            $stories = $this->my->getStoriesBySearch($queryID, $this->app->rawMethod, $sort, $pager);
        }
        else
        {
            $stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $sort, $pager, 'story', false, 'all');
        }

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

         /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=story&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildStorySearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[] = $this->lang->my->story;
        $this->view->stories    = $stories;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'story';

        $this->display();
    }

    /**
     * My requirements.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function requirement($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->loadModel('story');
        if($this->app->viewType != 'json') $this->session->set('storyList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $queryID = ($type == 'bysearch') ? (int)$param : 0;

        if($type == 'assignedBy')
        {
            $stories = $this->my->getAssignedByMe($this->app->user->account, '', $pager, $sort, 'requirement');
        }
        elseif($type == 'bysearch')
        {
            $stories = $this->my->getRequirementsBySearch($queryID, $this->app->rawMethod, $sort, $pager);
        }
        else
        {
            $stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $sort, $pager, 'requirement', false, 'all');
        }

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

         /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=requirement&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildRequirementSearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[] = $this->lang->my->story;
        $this->view->stories    = $stories;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'requirement';

        $this->display();
    }

    /**
     * My tasks
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $queryID  = ($type == 'bySearch') ? (int)$param : 0;

        /* Save session. */
        if($type != 'bySearch')            $this->session->set('myTaskType', $type);
        if($this->app->viewType != 'json') $this->session->set('taskList', $this->app->getURI(true), 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Get tasks. */
        if($type == 'assignedBy')
        {
            $tasks = $this->my->getAssignedByMe($this->app->user->account, 0, $pager, $sort, 'task');
        }
        elseif($type == 'bySearch')
        {
            $tasks = $this->my->getTasksBySearch($this->app->user->account, 0, $pager, $sort, $queryID);
        }
        else
        {
            $tasks = $this->task->getUserTasks($this->app->user->account, $type, 0, $pager, $sort, $queryID);
        }

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }

        /* Get the story language configuration. */
        $this->app->loadLang('story');

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=task&browseType=bySearch&queryID=myQueryID");
        $this->my->buildTaskSearchForm($queryID, $actionURL);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $this->view->position[] = $this->lang->my->task;
        $this->view->tabID      = 'task';
        $this->view->tasks      = $tasks;
        $this->view->summary    = $this->loadModel('execution')->summary($tasks);
        $this->view->type       = $type;
        $this->view->kanbanList = $this->execution->getPairs(0, 'kanban');
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->view->mode       = 'task';

        if($this->app->viewType == 'json') $this->view->tasks = array_values($this->view->tasks);
        $this->display();
    }

    /**
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
    public function bug($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. load Lang. */
        $this->loadModel('bug');
        $this->app->loadLang('bug');
        $queryID  = ($type == 'bySearch') ? (int)$param : 0;
        if($type != 'bySearch')            $this->session->set('myBugType', $type);
        if($this->app->viewType != 'json') $this->session->set('bugList', $this->app->getURI(true), 'qa');


        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        if(strpos($sort, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);
        if($type == 'assignedBy')
        {
            $bugs = $this->my->getAssignedByMe($this->app->user->account, '', $pager, $sort, 'bug');
        }
        else
        {
            $bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $type, $sort, 0, $pager, '', $queryID);
        }

        $bugs = $this->bug->checkDelayedBugs($bugs);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=bug&browseType=bySearch&queryID=myQueryID");
        $this->my->buildBugSearchForm($queryID, $actionURL);

        /* assign. */
        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->position[]  = $this->lang->my->bug;
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
     * My test task.
     *
     * @param  string $type wait|done
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask($type = 'wait', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
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

        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestTask;
        $this->view->position[] = $this->lang->my->myTestTask;
        $this->view->tasks      = $this->loadModel('testtask')->getByUser($this->app->user->account, $pager, $sort, $type);

        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->type       = $type;
        $this->view->pager      = $pager;
        $this->view->mode       = 'testtask';

        $this->display();
    }

    /**
     * My test case.
     *
     * @param  string     $type      assigntome|openedbyme
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function testcase($type = 'assigntome', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testcase');
        $this->loadModel('testtask');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'qa');
        $this->session->set('bugList',  $uri . "#app={$this->app->tab}", 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        $queryID = ($type == 'bysearch') ? (int)$param : 0;

        $cases = array();
        if($type == 'assigntome') $cases = $this->testcase->getByAssignedTo($this->app->user->account, $sort, $pager, 'skip|run');
        if($type == 'openedbyme') $cases = $this->testcase->getByOpenedBy($this->app->user->account, $sort, $pager, 'skip');
        if($type == 'bysearch' and $this->app->rawMethod == 'contribute') $cases = $this->my->getTestcasesBySearch($queryID, 'contribute', $orderBy, $pager);
        if($type == 'bysearch' and $this->app->rawMethod == 'work')       $cases = $this->my->getTestcasesBySearch($queryID, 'work', $orderBy, $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->testcase->appendData($cases, $type == 'assigntome' ? 'run' : 'case');

        /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=testcase&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildTestcaseSearchForm($queryID, $actionURL, $currentMethod);

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myTestCase;
        $this->view->position[] = $this->lang->my->myTestCase;
        $this->view->cases      = $cases;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->tabID      = 'test';
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->summary    = $this->testcase->summary($cases);
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->mode       = 'testcase';

        $this->display();
    }

    /**
     * doc page of my.
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
    public function doc($type = 'openedbyme', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session, load lang. */
        $uri = $this->app->getURI(true);
        $this->loadModel('doc');
        if($this->app->viewType != 'json') $this->session->set('docList', $uri, 'doc');

        $queryID = ($type == 'bySearch') ? (int)$param : 0;

        $this->session->set('productList',   $uri, 'product');
        $this->session->set('executionList', $uri, 'execution');
        $this->session->set('projectList',   $uri, 'project');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $docs = $this->doc->getDocsByBrowseType($type, $queryID, 0, $sort, $pager);

        $actionURL = $this->createLink('my', $this->app->rawMethod, "mode=doc&browseType=bySearch&queryID=myQueryID");
        $this->loadModel('doc')->buildSearchForm(0, array(), $queryID, $actionURL, 'contribute');

        /* Assign. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->doc;
        $this->view->position[] = $this->lang->my->doc;
        $this->view->docs       = $docs;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();

    }

    /**
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
    public function project($status = 'doing', $recTotal = 0, $recPerPage = 15, $pageID = 1, $orderBy = 'id_desc')
    {
        $this->loadModel('program');
        $this->app->loadLang('project');

        $uri = $this->app->getURI(true);
        $this->app->session->set('programList', $uri, 'program');
        $this->app->session->set('projectList', $uri, 'my');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get PM id list. */
        $accounts = array();
        $projects = $this->user->getObjects($this->app->user->account, 'project', $status, $orderBy, $pager);
        foreach($projects as $project)
        {
            if(!empty($project->PM) and !in_array($project->PM, $accounts)) $accounts[] = $project->PM;
        }
        $PMList = $this->user->getListByAccounts($accounts, 'account');

        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->position[]  = $this->lang->my->project;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
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
     * My executions.
     * @param  string  $type undone|done
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     *
     * @access public
     * @return void
     */
    public function execution($type = 'undone', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->app->loadLang('project');
        $this->app->loadLang('execution');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $executions  = $this->user->getObjects($this->app->user->account, 'execution', $type, $orderBy, $pager);
        $parentGroup = $this->dao->select('parent, id')->from(TABLE_PROJECT)
            ->where('parent')->in(array_keys($executions))
            ->andWhere('type')->in('stage,kanban,sprint')
            ->fetchGroup('parent', 'id');

        $this->view->title       = $this->lang->my->common . $this->lang->colon . $this->lang->my->execution;
        $this->view->position[]  = $this->lang->my->execution;
        $this->view->tabID       = 'project';
        $this->view->executions  = $executions;
        $this->view->parentGroup = $parentGroup;
        $this->view->type        = $type;
        $this->view->pager       = $pager;
        $this->view->mode        = 'execution';

        $this->display();
    }

    /**
     * My issues.
     *
     * @access public
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function issue($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $browseType = strtolower($type);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL  = $this->createLink('my', $this->app->rawMethod, "mode=issue&type=bySearch&param=myQueryID");
        $this->loadModel('issue')->buildSearchForm($actionURL, $queryID);

        $this->app->session->set('issueList', $this->app->getURI(true), 'project');

        $this->view->title      = $this->lang->my->issue;
        $this->view->position[] = $this->lang->my->issue;
        $this->view->mode       = 'issue';
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->type       = $type;
        $this->view->param      = $param;
        $this->view->issues     = $type == 'assignedBy' ? $this->loadModel('my')->getAssignedByMe($this->app->user->account, '', $pager,  $orderBy, 'issue') : $this->loadModel('issue')->getUserIssues($type, $queryID, $this->app->user->account, $orderBy, $pager);

        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();

        $this->display();
    }

    /**
     * My risks.
     *
     * @access public
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @return void
     */
    public function risk($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set the pager. */
        $this->loadModel('risk');
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $queryID       = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=risk&type=bysearch&param=myQueryID");
        $this->my->buildRiskSearchForm($queryID, $actionURL, $currentMethod);

        /* Get risks by type*/
        if($type == 'assignedBy')
        {
            $risks = $this->my->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, 'risk');
        }
        else
        {
            if($type != 'bysearch') $risks = $this->risk->getUserRisks($type, $this->app->user->account, $orderBy, $pager);
        }

        if($type == 'bysearch' and $currentMethod == 'contribute') $risks = $this->my->getRisksBySearch($queryID, $currentMethod, $orderBy, $pager);
        if($type == 'bysearch' and $currentMethod == 'work') $risks = $this->my->getRisksBySearch($queryID, $currentMethod, $orderBy, $pager);

        $this->app->session->set('riskList', $this->app->getURI(true), 'project');

        $this->view->title      = $this->lang->my->risk;
        $this->view->position[] = $this->lang->my->risk;
        $this->view->risks      = $risks;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->type       = $type;
        $this->view->mode       = 'risk';

        $this->view->projectList = $this->loadModel('project')->getPairsByProgram();

        $this->display();
    }

    /**
     * My audits.
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
    public function audit($browseType = 'all', $param = 0, $orderBy = 'time_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
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

        $this->view->flows = array();
        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            $this->app->loadLang('approval');
            $this->view->flows = $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->fetchPairs('module', 'name');
        }

        $this->view->title       = $this->lang->review->common;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->reviewList  = $reviewList;
        $this->view->recTotal    = $recTotal;
        $this->view->recPerPage  = $recPerPage;
        $this->view->pageID      = $pageID;
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->mode        = 'audit';
        $this->display();
    }

    /**
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
    public function auditplan($browseType = 'myChecking', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('auditplan');
        $this->loadModel('process');
        $this->loadModel('pssp');
        $this->session->set('auditplanList', $this->app->getURI(true));

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager  = pager::init($recTotal, $recPerPage, $pageID);

        $auditplans = $this->auditplan->getList(0, $browseType, $param, $orderBy, $pager);

        $this->view->executions      = $this->loadModel('execution')->getPairs();
        $this->view->projects        = $this->loadModel('project')->getPairs();
        $this->view->processTypeList = $this->lang->process->classify;
        $this->view->processes       = $this->pssp->getProcesses();
        $this->view->activities      = $this->pssp->getActivityPairs();
        $this->view->outputs         = $this->pssp->getOutputPairs();

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->auditplan;
        $this->view->browseType = $browseType;
        $this->view->auditplans = $auditplans;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->view->mode       = 'auditplan';
        $this->display();
    }

    /**
     * My ncs.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function nc($browseType = 'assignedToMe', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('nc');
        $this->session->set('ncList', $this->app->getURI(true));

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager  = pager::init($recTotal, $recPerPage, $pageID);
        $status = $this->app->rawMethod == 'contribute' ? '' : 'active';
        $ncList = $browseType == 'assignedBy' ? $this->my->getAssignedByMe($this->app->user->account, '', $pager, $orderBy, 'nc') : $this->my->getNcList($browseType, $orderBy, $pager, $status);

        foreach($ncList as $nc) $ncIdList[] = $nc->id;
        $this->session->set('ncIdList', isset($ncIdList) ? $ncIdList : '');

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->nc;
        $this->view->position[] = $this->lang->my->nc;
        $this->view->browseType = $browseType;
        $this->view->ncs        = $ncList;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->view->mode       = 'nc';
        $this->display();
    }

    /**
     * My meeting list.
     *
     * @param  string     $browseType
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function myMeeting($browseType = 'futureMeeting', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('meeting');

        $uri = $this->app->getURI(true);
        $this->session->set('meetingList', $uri, 'my');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('my', 'work', "mode=myMeeting&browseType=bysearch&param=myQueryID");
        $this->meeting->buildSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->myMeeting;
        $this->view->browseType = $browseType;
        $this->view->meetings   = $this->meeting->getListByUser($browseType, $orderBy, $queryID, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->users      = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->queryID    = $queryID;
        $this->view->mode       = 'myMeeting';
        $this->view->projects   = array(0 => '') + $this->loadModel('project')->getPairsByProgram('', 'all', true);
        $this->view->executions = array(0 => '') + $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        $this->view->rooms      = array('' => '') + $this->loadModel('meetingroom')->getPairs();

        $this->display();
    }

    /**
     * Feedback .
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
    public function feedback($browseType = 'assigntome', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('feedback');

        $this->loadModel('datatable');
        $this->lang->datatable->moduleSetting  = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->moduleSetting);
        $this->lang->datatable->showModule     = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->showModule);
        $this->lang->datatable->showModuleList = str_replace($this->lang->module, $this->lang->feedback->moduleAB, $this->lang->datatable->showModuleList);

        $this->session->set('feedbackList', $this->app->getURI(true), 'feedback');

        $queryID = $browseType == 'bysearch' ? (int)$param : 0;
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if($browseType != 'bysearch')
        {
            $feedbacks = $this->feedback->getList($browseType, $orderBy, $pager);
        }
        else
        {
            $feedbacks = $this->feedback->getBySearch($queryID, $orderBy, $pager);
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'workFeedback');

        $storyIdList = $bugIdList = $todoIdList = $taskIdList = $ticketIdList = array();
        foreach($feedbacks as $feedback)
        {
            if($feedback->solution == 'tobug')   $bugIdList[]    = $feedback->result;
            if($feedback->solution == 'tostory') $storyIdList[]  = $feedback->result;
            if($feedback->solution == 'totodo')  $todoIdList[]   = $feedback->result;
            if($feedback->solution == 'totask')  $taskIdList[]   = $feedback->result;
            if($feedback->solution == 'ticket')  $ticketIdList[] = $feedback->result;
        }
        $bugs    = $bugIdList    ? $this->loadModel('bug')->getByList($bugIdList) : array();
        $stories = $storyIdList  ? $this->loadModel('story')->getByList($storyIdList) : array();
        $todos   = $todoIdList   ? $this->loadModel('todo')->getByList($todoIdList) : array();
        $tasks   = $taskIdList   ? $this->loadModel('task')->getByList($taskIdList) : array();
        $tickets = $ticketIdList ? $this->loadModel('ticket')->getByList($ticketIdList) : array();

        $products = $this->loadModel('product')->getPairs();

        $this->config->feedback->search['module']    = 'workFeedback';
        $this->config->feedback->search['actionURL'] = inlink('work', "mode=feedback&browseType=bysearch&param=myQueryID&orderBy=$orderBy");
        $this->config->feedback->search['queryID']   = $queryID;
        $this->config->feedback->search['onMenuBar'] = 'no';
        $this->config->feedback->search['params']['product']['values']     = array('' => '') + $products;
        $this->config->feedback->search['params']['module']['values']      = array('' => '') + $this->loadModel('tree')->getOptionMenu(0, $viewType = 'feedback', $startModuleID = 0);
        $this->config->feedback->search['params']['processedBy']['values'] = array('' => '') + $this->feedback->getFeedbackPairs('admin');

        unset($this->config->feedback->search['fields']['assignedTo']);
        unset($this->config->feedback->search['fields']['closedBy']);
        unset($this->config->feedback->search['fields']['closedDate']);
        unset($this->config->feedback->search['fields']['closedReason']);
        unset($this->config->feedback->search['fields']['processedBy']);
        unset($this->config->feedback->search['fields']['processedDate']);
        unset($this->config->feedback->search['fields']['solution']);

        $this->loadModel('search')->setSearchParams($this->config->feedback->search);

        $this->view->title       = $this->lang->my->feedback;
        $this->view->position[]  = $this->lang->my->feedback;
        $this->view->mode        = 'feedback';
        $this->view->browseType  = $browseType;
        $this->view->feedbacks   = $feedbacks;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->bugs        = $bugs;
        $this->view->todos       = $todos;
        $this->view->stories     = $stories;
        $this->view->tasks       = $tasks;
        $this->view->tickets     = $tickets;
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->projects    = $this->loadModel('project')->getPairsByProgram('', 'noclosed');
        $this->view->allProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
        $this->view->modulePairs = $this->tree->getModulePairs(0, 'feedback');
        $this->view->modules     = $this->tree->getOptionMenu(0, $viewType = 'feedback', 0);
        $this->display();
    }

    /**
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
    public function ticket($browseType = 'assignedtome', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('ticket');
        $queryID = $browseType == 'bysearch' ? (int)$param : 0;

        $this->session->set('ticketList', $this->app->getURI(true), 'feedback');

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if($browseType != 'bysearch')
        {
            $tickets = $this->ticket->getList($browseType, $orderBy, $pager);
        }
        else
        {
            $tickets = $this->ticket->getBySearch($queryID, $orderBy, $pager);
        }

        $actionURL = $this->createLink('my', 'work', "mode=ticket&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildTicketSearchForm($queryID, $actionURL);

        $this->view->title      = $this->lang->ticket->browse;
        $this->view->products   = $this->loadModel('feedback')->getGrantProducts();
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');
        $this->view->tickets    = $tickets;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;
        $this->view->mode       = 'ticket';
        $this->display();
    }

    /**
     * My team.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function team($orderBy = 'id', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->my = 'system';

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Get users by dept. */
        $deptID = $this->app->user->admin ? 0 : $this->app->user->dept;
        $users  = $this->loadModel('company')->getUsers('inside', 'bydept', 0, $deptID, $sort, $pager);
        foreach($users as $user) unset($user->password); // Remove passwd.

        $this->view->title      = $this->lang->my->team;
        $this->view->position[] = $this->lang->my->team;
        $this->view->users      = $users;
        $this->view->deptID     = $deptID;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Edit profile
     *
     * @access public
     * @return void
     */
    public function editProfile()
    {
        if($this->app->user->account == 'guest')
        {
            echo js::alert('guest'), js::locate('back');
            return;
        }
        if(!empty($_POST))
        {
            $_POST['account'] = $this->app->user->account;
            $_POST['groups']  = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->fetchPairs('group', 'group');
            $this->user->update($this->app->user->id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('my', 'profile'), 'closeModal' => true));
        }

        $this->app->loadConfig('user');
        $this->app->loadLang('user');

        $userGroups = $this->loadModel('group')->getByAccount($this->app->user->account);

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->position[] = $this->lang->my->editProfile;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->groups     = $this->dao->select('id, name')->from(TABLE_GROUP)->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * Change password
     *
     * @access public
     * @return void
     */
    public function changePassword()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));
        if(!empty($_POST))
        {
            $this->user->updatePassword($this->app->user->id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('my', 'index')));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
        $this->view->position[] = $this->lang->my->changePassword;
        $this->view->user       = $this->user->getById($this->app->user->account);
        $this->view->rand       = $this->user->updateSessionRandom();

        $this->display();
    }

    /**
     * Manage contacts.
     *
     * @param  int    $listID
     * @param  string $mode
     * @access public
     * @return void
     */
    public function manageContacts($listID = 0, $mode = 'new')
    {
        if($_POST)
        {
            $data = fixer::input('post')->setDefault('users', array())->get();
            if($data->mode == 'new')
            {
                if(empty($data->newList))
                {
                    dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->user->contacts->listName);

                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                    return $this->send($response);
                }
                $listID = $this->user->createContactList($data->newList, $data->users);
                if(dao::isError())
                {
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
                $this->user->setGlobalContacts($listID, isset($data->share));
                if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.parent.ajaxGetContacts('#mailto')"));
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('manageContacts', "listID=$listID&mode=edit")));
            }
            elseif($data->mode == 'edit')
            {
                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;

                $this->user->updateContactList($data->listID, $data->listName, $data->users);
                $this->user->setGlobalContacts($data->listID, isset($data->share));

                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                    return $this->send($response);
                }

                $response['locate'] = inlink('manageContacts', "listID=$listID&mode=edit");
                return $this->send($response);
            }
        }

        $mode  = empty($mode) ? 'edit' : $mode;
        $lists = $this->user->getContactLists($this->app->user->account);

        $globalContacts = isset($this->config->my->global->globalContacts) ? $this->config->my->global->globalContacts : '';
        $globalContacts = !empty($globalContacts) ? explode(',', $globalContacts) : array();

        $myContacts = $this->user->getListByAccount($this->app->user->account);
        $disabled   = $globalContacts;

        if(!empty($myContacts) && !empty($globalContacts))
        {
            foreach($globalContacts as $id)
            {
                if(in_array($id, array_keys($myContacts))) unset($disabled[array_search($id, $disabled)]);
            }
        }

        $listID = $listID ? $listID : key($lists);

        /* Create or manage list according to mode. */
        if($mode == 'new')
        {
            $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->user->contacts->createList;
            $this->view->position[] = $this->lang->user->contacts->createList;
        }
        else
        {
            $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->user->contacts->manage;
            $this->view->position[] = $this->lang->user->contacts->manage;
            $this->view->list       = $this->user->getContactListByID($listID);
        }

        $userParams = empty($this->config->user->showDeleted) ? 'noletter|noempty|noclosed|noclosed|nodeleted' : 'noletter|noempty|noclosed|noclosed';
        $users      = $this->user->getPairs($userParams, $mode == 'new' ? '' : $this->view->list->userList, $this->config->maxCount);
        if(isset($this->config->user->moreLink)) $this->config->moreLinks['users[]'] = $this->config->user->moreLink;

        $this->view->mode           = $mode;
        $this->view->lists          = $lists;
        $this->view->listID         = $listID;
        $this->view->users          = $users;
        $this->view->disabled       = $disabled;
        $this->view->globalContacts = $globalContacts;
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
            return print(js::confirm($this->lang->user->contacts->confirmDelete, inlink('deleteContacts', "listID=$listID&confirm=yes")));
        }
        else
        {
            $this->user->deleteContactList($listID);
            return print(js::locate(inlink('manageContacts'), 'parent'));
        }
    }

    /**
     * Build contact lists.
     *
     * @param  string $dropdownName
     * @param  string $attr
     * @access public
     * @return void
     */
    public function buildContactLists($dropdownName = 'mailto', $attr = '')
    {
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');
        $this->view->dropdownName = $dropdownName;
        $this->view->attr         = $attr;
        $this->display();
    }

    /**
     * View my profile.
     *
     * @access public
     * @return void
     */
    public function profile()
    {
        if($this->app->user->account == 'guest') return print(js::alert('guest') . js::locate('back'));

        $this->app->loadConfig('user');
        $this->app->loadLang('user');
        $user = $this->user->getById($this->app->user->account);

        $this->view->title        = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]   = $this->lang->my->profile;
        $this->view->user         = $user;
        $this->view->groups       = $this->loadModel('group')->getByAccount($this->app->user->account);
        $this->view->deptPath     = $this->dept->getParents($user->dept);
        $this->view->personalData = $this->user->getPersonalData();
        $this->display();
    }

    /**
     * User preference setting.
     *
     * @access public
     * @return void
     */
    public function preference($showTip = true)
    {
        $this->loadModel('setting');

        if($_POST)
        {
            foreach($_POST as $key => $value) $this->setting->setItem("{$this->app->user->account}.common.$key", $value);

            $this->setting->setItem("{$this->app->user->account}.common.preferenceSetted", 1);
            if(isOnlybody()) return print(js::closeModal('parent.parent'));

            return print(js::locate($this->createLink('my', 'index'), 'parent'));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->preference;
        $this->view->position[] = $this->lang->my->preference;
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
     * My dynamic.
     *
     * @param  string $type
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction    next|pre
     * @access public
     * @return void
     */
    public function dynamic($type = 'today', $recTotal = 0, $date = '', $direction = 'next')
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

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* The header and position. */
        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->dynamic;
        $this->view->position[] = $this->lang->my->dynamic;

        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($this->app->user->account, $type, $orderBy, 50, 'all', 'all', 'all', $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction, $type);

        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($actions) : $this->action->getDynamicCount();

        /* Assign. */
        $this->view->type       = $type;
        $this->view->orderBy    = $orderBy;
        $this->view->dateGroups = $dateGroups;
        $this->view->direction  = $direction;
        $this->view->recTotal   = $recTotal;
        $this->display();
    }

    /**
     * Upload avatar.
     *
     * @access public
     * @return void
     */
    public function uploadAvatar()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $result = $this->loadModel('user')->uploadAvatar();
            $this->send($result);
        }
    }

    /**
     * Unbind ranzhi
     *
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unbind($confirm = 'no')
    {
        $this->loadModel('user');
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
     * Switch vision by ajax.
     *
     * @param  string $vision
     * @access public
     * @return void
     */
    public function ajaxSwitchVision($vision)
    {
        $_SESSION['vision'] = $vision;
        $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.vision", $vision);
        $this->config->vision = $vision;

        $_SESSION['user']->rights = $this->loadModel('user')->authorize($this->app->user->account);

        setcookie('vision', $vision, $this->config->cookieLife, $this->config->webRoot, '', false, false);

        echo js::locate($this->createLink('index', 'index'), 'parent');
    }
}
