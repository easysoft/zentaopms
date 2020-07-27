<?php
/**
 * The control file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testtask extends control
{
    public $products = array();

    /**
     * Construct function, load product module, assign products to view auto.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getPairs('nocode');
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', "fromModule=testtask")));
    }

    /**
     * Index page, header to browse.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('testtask', 'browse'));
    }

    /**
     * Browse test tasks.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $type = 'local,totalStatus', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $beginTime = 0, $endTime = 0)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));
        $this->session->set('buildList', $this->app->getURI(true));

        $scopeAndStatus = explode(',',$type);
        $this->session->set('testTaskVersionScope', $scopeAndStatus[0]);
        $this->session->set('testTaskVersionStatus', $scopeAndStatus[1]);

        $beginTime = $beginTime ? date('Y-m-d', strtotime($beginTime)) : '';
        $endTime   = $endTime   ? date('Y-m-d', strtotime($endTime))   : '';

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->testtask->setMenu($this->products, $productID, $branch);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]  = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testtask->common;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->orderBy     = $orderBy;
        $this->view->tasks       = $this->testtask->getProductTasks($productID, $branch, $sort, $pager, $scopeAndStatus, $beginTime, $endTime);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;
        $this->view->branch      = $branch;
        $this->view->beginTime   = $beginTime;
        $this->view->endTime     = $endTime;

        $this->display();
    }

    /**
     * Browse unit tasks.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browseUnits($productID = 0, $browseType = 'newest', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->lang->testtask->menu      = $this->lang->testcase->menu;
        $this->lang->testtask->menuOrder = $this->lang->testcase->menuOrder;
        $this->lang->testtask->subMenu->testcase->unit['subModule'] = 'testtask';
        $this->loadModel('testtask')->setUnitMenu($this->products, $productID);

        /* Load pager. */
        if($browseType == 'newest') $recPerPage = '10';
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]  = html::a($this->createLink('testtask', 'browseUnits', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testtask->common;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->tasks       = $this->testtask->getProductUnitTasks($productID, $browseType, $sort, $pager);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;

        $this->display();
    }

    /**
     * Create a test task.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create($productID, $projectID = 0, $build = 0)
    {
        if(!empty($_POST))
        {
            $taskID = $this->testtask->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('testtask', $taskID, 'opened');

            $this->executeHooks($taskID);

            die(js::locate($this->createLink('testtask', 'browse', "productID=$productID"), 'parent'));
        }

        /* Create testtask from build of project.*/
        if($projectID != 0 and $build != 0)
        {
            $products = $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->where('t1.project')->eq($projectID)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('id');

            $productID = $productID ? $productID : key($products);
            $projects  = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->andWhere('type')->ne('ops')->andWhere('deleted')->eq(0)->fetchPairs('id');
            $builds    = $this->dao->select('id, name')->from(TABLE_BUILD)->where('id')->eq($build)->andWhere('deleted')->eq(0)->fetchPairs('id');
        }

        /* Create testtask from testtask of project.*/
        if($projectID != 0 and $build == 0)
        {
            $products = $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->where('t1.project')->eq($projectID)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('id');

            $productID = $productID ? $productID : key($products);
            $projects  = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->andWhere('type')->ne('ops')->andWhere('deleted')->eq(0)->fetchPairs('id');
            $builds    = $this->dao->select('id, name')->from(TABLE_BUILD)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->fetchPairs('id');
        }

        /* Create testtask from testtask of test.*/
        if($projectID == 0)
        {
            $params   = 'nodeleted';
            $projects = array();
            $datas = $this->dao->select('t2.id, t2.name, t2.deleted')->from(TABLE_PROJECTPRODUCT)
                ->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.product')->eq((int)$productID)
                ->beginIF('0')->andWhere('t1.branch')->in('0')->fi()
                ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
                ->andWhere('t2.type')->ne('ops')
                ->orderBy('t1.project desc')
                ->fetchAll();
    
            foreach($datas as $data)
            {
                if($params == 'nodeleted' and $data->deleted) continue;
                $projects[$data->id] = $data->name;
            }
            $projects = array('' => '') +  $projects;
            $builds   = $this->loadModel('build')->getProductBuildPairs($productID, 0, 'notrunk');
        }

        /* Set menu. */
        $productID  = $this->product->saveState($productID, $this->products);
        $this->testtask->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->create;

        if($projectID != 0)
        {
            $this->app->loadLang('build');
            $this->view->products  = $products;
            $this->view->projectID = $projectID;
        }
        $this->view->projects     = $projects;
        $this->view->productID    = $productID;
        $this->view->builds       = $builds;
        $this->view->build        = $build;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|qdfirst|nodeleted');

        $this->display();
    }

    /**
     * View a test task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        /* Get test task, and set menu. */
        $task = $this->testtask->getById($taskID, true);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $buildID   = $task->build;

        $build   = $this->loadModel('build')->getByID($buildID);
        $stories = array();
        $bugs    = array();

        if($build)
        {
            $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($build->stories)->fetchAll();
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');

            $bugs    = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($build->bugs)->fetchAll();
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');
        }

        $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);

        $this->executeHooks($taskID);

        $this->view->title      = "TASK #$task->id $task->name/" . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->view;

        $this->view->productID = $productID;
        $this->view->task      = $task;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions   = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->build     = $build;
        $this->view->stories   = $stories;
        $this->view->bugs      = $bugs;
        $this->display();
    }

    /**
     * Browse unit cases.
     * 
     * @param  int    $taskID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function unitCases($taskID, $orderBy = 'id')
    {
        $task = $this->testtask->getById($taskID);

        /* Set browseType, productID, moduleID and queryID. */
        $productID = $this->product->saveState($task->product, $this->products);

        /* Set menu, save session. */
        $this->lang->testtask->menu      = $this->lang->testcase->menu;
        $this->lang->testtask->menuOrder = $this->lang->testcase->menuOrder;
        $this->lang->testtask->subMenu->testcase->unit['subModule'] = 'testtask';
        $this->loadModel('testtask')->setUnitMenu($this->products, $productID, 0, $taskID);
        $this->session->set('caseList', $this->app->getURI(true));

        /* Load lang. */
        $this->app->loadLang('testtask');
        $this->app->loadLang('project');

        /* Get test cases. */
        $runs = $this->testtask->getRuns($taskID, 0, $orderBy);

        /* save session .*/
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = array();
        $runs = $this->loadModel('testcase')->appendData($runs, 'testrun');
        foreach($runs as $run) $cases[$run->case] = $run;

        $results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('`case`')->in(array_keys($cases))->andWhere('run')->in(array_keys($runs))->fetchAll('run');
        foreach($results as $result)
        {
            $runs[$result->run]->caseResult = $result->caseResult;
            $runs[$result->run]->xml        = $result->xml;
            $runs[$result->run]->duration   = $result->duration;
        }

        $groupCases = $this->dao->select('*')->from(TABLE_SUITECASE)->where('`case`')->in(array_keys($cases))->orderBy('case')->fetchGroup('suite', 'case');
        $summary    = array();
        if(empty($groupCases)) $groupCases[] = $cases;
        foreach($groupCases as $suiteID => $groupCase)
        {
            $caseCount = 0;
            $failCount = 0;
            $duration  = 0;
            foreach($groupCase as $caseID => $suitecase)
            {
                $case = $cases[$caseID];
                $groupCases[$suiteID][$caseID] = $case;
                $duration += $case->duration;
                $caseCount ++;
                if($case->caseResult == 'fail') $failCount ++;
            }
            $summary[$suiteID] = sprintf($this->lang->testtask->summary, $caseCount, $failCount, $duration);
        }

        $suites = $this->loadModel('testsuite')->getUnitSuites($productID);

        /* Assign. */
        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[] = html::a($this->createLink('testcase', 'browseUnits', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testcase->common;

        $this->view->productID   = $productID;
        $this->view->task        = $task;
        $this->view->product     = $this->product->getById($productID);
        $this->view->productName = $this->products[$productID];
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->groupCases  = $groupCases;
        $this->view->suites      = $suites;
        $this->view->summary     = $summary;
        $this->view->taskID      = $taskID;

        $this->display();
    }

    /**
     * Browse cases of a test task.
     *
     * @param  string $taskID
     * @param  string $browseType  bymodule|all|assignedtome
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function cases($taskID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->loadModel('testcase');

        /* Save the session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        /* Set the browseType and moduleID. */
        $browseType = strtolower($browseType);

        /* Get task and product info, set menu. */
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->testtask->checkLinked) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);
        setcookie('preTaskID', $taskID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        if($this->cookie->preTaskID != $taskID)
        {
            $_COOKIE['taskCaseModule'] = 0;
            setcookie('taskCaseModule', 0, 0, $this->config->webRoot, '', false, true);
        }
        if($browseType == 'bymodule') setcookie('taskCaseModule', (int)$param, 0, $this->config->webRoot, '', false, true);
        if($browseType != 'bymodule') $this->session->set('taskCaseBrowseType', $browseType);

        $moduleID = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->taskCaseModule ? $this->cookie->taskCaseModule : 0));
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy, 't2.id');

        /* Get test cases. */
        $runs = $this->testtask->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['module']                      = 'testtask';
        $this->config->testcase->search['params']['product']['values'] = array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['params']['status']['values']  = array('' => '') + $this->lang->testtask->statusList;

        $this->config->testcase->search['queryID']              = $queryID;
        $this->config->testcase->search['fields']['assignedTo'] = $this->lang->testtask->assignedTo;
        $this->config->testcase->search['params']['assignedTo'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
        $this->config->testcase->search['actionURL'] = inlink('cases', "taskID=$taskID&browseType=bySearch&queryID=myQueryID");
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        /* Append bugs and results. */
        $runs = $this->testcase->appendData($runs, 'run');

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->cases;

        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->task          = $task;
        $this->view->runs          = $runs;
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|qafirst');
        $this->view->assignedTos   = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted|qafirst');
        $this->view->moduleTree    = $this->loadModel('tree')->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createTestTaskLink'), $extra = $taskID);
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->taskID        = $taskID;
        $this->view->moduleID      = $moduleID;
        $this->view->moduleName    = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->treeClass     = $browseType == 'bymodule' ? '' : 'hidden';
        $this->view->pager         = $pager;
        $this->view->branches      = $this->loadModel('branch')->getPairs($productID);
        $this->view->setModule     = false;

        $this->display();
    }

    /**
     * The report page.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $branchID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function report($productID, $taskID, $browseType, $branchID, $moduleID = 0, $chartType = 'pie')
    {
        $this->loadModel('report');
        $this->view->charts = array();

        if(!empty($_POST))
        {
            $this->app->loadLang('testcase');
            $task    = $this->testtask->getById($taskID);
            $bugInfo = $this->testtask->getBugInfo($taskID, $productID);
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = isset($bugInfo[$chart]) ? $bugInfo[$chart] : $this->testtask->$chartFunc($taskID);
                $chartOption = $this->testtask->mergeChartOption($chart);
                if(!empty($chartType)) $chartOption->type = $chartType;

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $this->testtask->setMenu($this->products, $productID, $branchID, $taskID);
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common . $this->lang->colon . $this->lang->testtask->reportChart;
        $this->view->position[]    = html::a($this->createLink('testtask', 'cases', "taskID=$taskID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testtask->reportChart;
        $this->view->productID     = $productID;
        $this->view->taskID        = $taskID;
        $this->view->browseType    = $browseType;
        $this->view->moduleID      = $moduleID;
        $this->view->branchID      = $branchID;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';

        $this->display();
    }

    /**
     * Group case.
     *
     * @param  int    $taskID
     * @param  string $groupBy
     * @access public
     * @return void
     */
    public function groupCase($taskID, $groupBy = 'story')
    {
        /* Save the session. */
        $this->loadModel('testcase');
        $this->app->loadLang('project');
        $this->app->loadLang('task');
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get task and product info, set menu. */
        $groupBy = empty($groupBy) ? 'story' : $groupBy;
        $task    = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);

        $runs = $this->testtask->getRuns($taskID, 0, $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $runs = $this->testcase->appendData($runs, 'run');
        $groupCases  = array();
        $groupByList = array();
        foreach($runs as $run)
        {
            if($groupBy == 'story')
            {
                $groupCases[$run->story][] = $run;
                $groupByList[$run->story]  = $run->storyTitle;
            }
            elseif($groupBy == 'assignedTo')
            {
                $groupCases[$run->assignedTo][] = $run;
            }
        }

        if($groupBy == 'story' && $task->build)
        {
            $buildStoryIdList = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('stories');
            $buildStories     = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($buildStoryIdList)->andWhere('id')->notin(array_keys($groupCases))->fetchAll('id');
            foreach($buildStories as $buildStory)
            {
                $groupCases[$buildStory->id][] = $buildStory;
                $groupByList[$buildStory->id]  = $buildStory->title;
            }
        }

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->cases;

        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->productID   = $productID;
        $this->view->task        = $task;
        $this->view->taskID      = $taskID;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->groupByList = $groupByList;
        $this->view->cases       = $groupCases;
        $this->view->account     = 'all';
        $this->display();
    }

    /**
     * Edit a test task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function edit($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            die(js::locate(inlink('view', "taskID=$taskID"), 'parent'));
        }

        /* Get task info. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->edit;

        $this->view->task         = $task;
        $this->view->projects     = $this->product->getProjectPairs($productID);
        $this->view->builds       = $this->loadModel('build')->getProductBuildPairs($productID, $branch = 0, $params = 'notrunk');
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted', $task->owner);
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');

        $this->display();
    }

    /**
     * Start testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->start($taskID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID, $testtask->branch, $taskID);

        $this->view->testtask   = $testtask;
        $this->view->title      = $testtask->name . $this->lang->colon . $this->lang->testtask->start;
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->start;
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->actions    = $this->loadModel('action')->getList('testtask', $taskID);
        $this->display();
    }

    /**
     * activate testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->activate($taskID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent')); 
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID, $testtask->branch, $taskID);

        $this->view->testtask   = $testtask;
        $this->view->title      = $testtask->name . $this->lang->colon . $this->lang->testtask->start;
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->actions    = $this->loadModel('action')->getList('testtask', $taskID);
        $this->display();
    }

    /**
     * Close testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->close($taskID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            $this->send(array('result' => 'success', 'message' => $this->lang->success, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID, $testtask->branch, $taskID);

        $this->view->testtask     = $this->testtask->getById($taskID);
        $this->view->title        = $testtask->name . $this->lang->colon . $this->lang->close;
        $this->view->position[]   = $this->lang->testtask->common;
        $this->view->position[]   = $this->lang->close;
        $this->view->actions      = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|nodeleted|qdfirst');
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');
        $this->display();
    }

    /**
     * block testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function block($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->block($taskID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Blocked', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID, $testtask->branch, $taskID);

        $this->view->testtask   = $testtask;
        $this->view->title      = $testtask->name . $this->lang->colon . $this->lang->testtask->start;
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->block;
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->actions    = $this->loadModel('action')->getList('testtask', $taskID);
        $this->display();
    }

    /**
     * Delete a test task.
     *
     * @param  int    $taskID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($taskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testtask->confirmDelete, inlink('delete', "taskID=$taskID&confirm=yes")));
        }
        else
        {
            $task = $this->testtask->getByID($taskID);
            $this->testtask->delete(TABLE_TESTTASK, $taskID);

            $this->executeHooks($taskID);

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
            die(js::locate(inlink('browse', "product=$task->product"), 'parent'));
        }
    }

    /**
     * Link cases to a test task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function linkCase($taskID, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $this->testtask->linkCase($taskID, $type);
            $this->locate(inlink('cases', "taskID=$taskID"));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get task and product id. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Save session. */
        $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['product']['values']= array($productID => $this->products[$productID]);
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['actionURL'] = inlink('linkcase', "taskID=$taskID&type=$type&param=$param");
        $this->config->testcase->search['style']     = 'simple';
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->config->testcase->search['fields']['branch'] = $this->lang->product->branch;
            $branches = array('' => '') + $this->loadModel('branch')->getPairs($task->product, 'noempty');
            if($task->branch) $branches = array('' => '', $task->branch => $branches[$task->branch]);
            $this->config->testcase->search['params']['branch']['values'] = $branches;
        }
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->title      = $task->name . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->linkCase;

        $testTask = $this->testtask->getRelatedTestTasks($productID, $taskID);

        /* Get cases. */
        $cases = $this->testtask->getLinkableCases($productID, $task, $taskID, $type, $param, $pager);

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases     = $cases;
        $this->view->taskID    = $taskID;
        $this->view->testTask  = $testTask;
        $this->view->pager     = $pager;
        $this->view->task      = $task;
        $this->view->type      = $type;
        $this->view->param     = $param;
        $this->view->suiteList = $this->loadModel('testsuite')->getSuites($task->product);

        $this->display();
    }

    /**
     * Remove a case from test task.
     *
     * @param  int    $rowID
     * @access public
     * @return void
     */
    public function unlinkCase($rowID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testtask->confirmUnlinkCase, $this->createLink('testtask', 'unlinkCase', "rowID=$rowID&confirm=yes")));
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq((int)$rowID)->exec();
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            $this->send($response);
        }
    }

    /**
     * Batch unlink cases.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function batchUnlinkCases($taskID)
    {
        if(isset($_POST['caseIDList']))
        {
            $this->dao->delete()->from(TABLE_TESTRUN)
                ->where('task')->eq((int)$taskID)
                ->andWhere('`case`')->in($this->post->caseIDList)
                ->exec();
        }

        die(js::locate($this->createLink('testtask', 'cases', "taskID=$taskID")));
    }

    /**
     * Run case.
     *
     * @param  int    $runID
     * @param  String $extras   others params, forexample, caseID=10, version=3
     * @access public
     * @return void
     */
    public function runCase($runID, $caseID = 0, $version = 0)
    {
        if($runID)
        {
            $run = $this->testtask->getRunById($runID);
        }
        else
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $version);
        }

        $caseID     = $caseID ? $caseID : $run->case->id;
        $preAndNext = $this->loadModel('common')->getPreAndNextObject('testcase', $caseID);
        if(!empty($_POST))
        {
            $caseResult = $this->testtask->createResult($runID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($caseResult == 'fail')
            {

                $response['result']  = 'success';
                $response['locate']  = $this->createLink('testtask', 'results',"runID=$runID&caseID=$caseID&version=$version");
                die($this->send($response));
            }
            else
            {
                /* set cookie for ajax load caselist when close colorbox. */
                setcookie('selfClose', 1, 0, $this->config->webRoot, '', false, false);

                if($preAndNext->next)
                {
                    $nextRunID   = $runID ? $preAndNext->next->id : 0;
                    $nextCaseID  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
                    $nextVersion = $preAndNext->next->version;

                    $response['result'] = 'success';
                    $response['next']   = 'success';
                    $response['locate'] = inlink('runCase', "runID=$nextRunID&caseID=$nextCaseID&version=$nextVersion");
                    die($this->send($response));
                }
                else
                {
                    $response['result'] = 'success';
                    $response['locate'] = 'reload';
                    $response['target'] = 'parent';
                    die($this->send($response));
                }
            }
        }

        $preCase  = array();
        $nextCase = array();
        if($preAndNext->pre)
        {
            $preCase['runID']   = $runID ? $preAndNext->pre->id : 0;
            $preCase['caseID']  = $runID ? $preAndNext->pre->case : $preAndNext->pre->id;
            $preCase['version'] = $preAndNext->pre->version;
        }
        if($preAndNext->next)
        {
            $nextCase['runID']   = $runID ? $preAndNext->next->id : 0;
            $nextCase['caseID']  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
            $nextCase['version'] = $preAndNext->next->version;
        }

        $this->view->run      = $run;
        $this->view->preCase  = $preCase;
        $this->view->nextCase = $nextCase;
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed, noletter');
        $this->view->caseID   = $caseID;
        $this->view->version  = $version;
        $this->view->runID    = $runID;

        $this->display();
    }

    /**
     * Batch run case.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchRun($productID, $orderBy = 'id_desc', $from = 'testcase', $taskID = 0)
    {
        $url = $this->session->caseList ? $this->session->caseList : $this->createLink('testcase', 'browse', "productID=$productID");
        if($this->post->results)
        {
            $this->testtask->batchRun($from, $taskID);
            die(js::locate($url, 'parent'));
        }

        $caseIDList = $this->post->caseIDList ? $this->post->caseIDList : die(js::locate($url, 'parent'));
        $caseIDList = array_unique($caseIDList);
        /* The case of tasks of qa. */
        if($productID)
        {
            $this->testtask->setMenu($this->products, $productID, $taskID);
            $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        }
        /* The case of my. */
        else
        {
            $this->lang->testtask->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.testtask', 'my');
            $this->lang->testtask->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->title = $this->lang->testtask->batchRun;
        }

        $cases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($caseIDList)->fetchAll('id');

        /* If case has changed and not confirmed, remove it. */
        if($from == 'testtask')
        {    
            $runs = $this->dao->select('`case`, version')->from(TABLE_TESTRUN)
                ->where('`case`')->in($caseIDList)
                ->andWhere('task')->eq($taskID)
                ->fetchPairs();
            foreach($cases as $caseID => $case)
            {
                if(isset($runs[$caseID]) && $runs[$caseID] < $case->version) unset($cases[$caseID]);
            }
        }

        $this->view->cases = $cases;
        $this->view->steps = $this->dao->select('t1.*')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t2.id')->in(array_keys($cases))
            ->andWhere('t1.version=t2.version')
            ->andWhere('t2.status')->ne('wait')
            ->fetchGroup('case', 'id');

        $this->view->caseIDList = array_keys($cases);
        $this->view->productID  = $productID;
        $this->view->title      = $this->lang->testtask->batchRun;
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->batchRun;
        $this->display();
    }

    /**
     * View test results of a test run.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function results($runID, $caseID = 0, $version = 0)
    {
        if($runID)
        {
            $case    = $this->testtask->getRunById($runID)->case;
            $results = $this->testtask->getResults($runID);

            $testtaskID = $this->dao->select('task')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch('task');
            $testtask   = $this->dao->select('id, build, project, product')->from(TABLE_TESTTASK)->where('id')->eq($testtaskID)->fetch();

            $this->view->testtask = $testtask;
        }
        else
        {
            $case    = $this->loadModel('testcase')->getByID($caseID, $version);
            $results = $this->testtask->getResults(0, $caseID);
        }

        $this->view->case    = $case;
        $this->view->runID   = $runID;
        $this->view->results = $results;
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($case->product, $branch = 0, $params = '');
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed, noletter');

        die($this->display());
    }

    /**
     * Batch assign cases.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function batchAssign($taskID)
    {
        $this->dao->update(TABLE_TESTRUN)
            ->set('assignedTo')->eq($this->post->assignedTo)
            ->where('task')->eq((int)$taskID)
            ->andWhere('`case`')->in($this->post->caseIDList)
            ->exec();
        die(js::locate($this->session->caseList, 'parent'));
    }

    /**
     * Import unit results.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function importUnitResult($productID)
    {
        if($_POST)
        {
            $file = $this->loadModel('file')->getUpload('resultFile');
            $file = $file[0];

            $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
            move_uploaded_file($file['tmpname'], $fileName);
            $this->session->set('resultFile', $fileName);

            $taskID = $this->testtask->importUnitResult($productID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action')->create('testtask', $taskID, 'opened');
            die(js::locate($this->createLink('testtask', 'unitCases', "taskID=$taskID"), 'parent'));
        }

        $this->testtask->setMenu($this->products, $productID);

        $this->app->loadLang('job');
        $projects = $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)
            ->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF('0')->andWhere('t1.branch')->in('0')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t2.type')->ne('ops')
            ->orderBy('t1.project desc')
            ->fetchPairs('id', 'name');

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->importUnitResult;
        $this->view->position[]  = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testtask->importUnitResult;

        $this->view->projects  = array('' => '') + $projects;
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID, 0, 'notrunk');
        $this->view->users     = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->productID = $productID;
        $this->display();
    }
}
