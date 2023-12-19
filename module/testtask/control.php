<?php
/**
 * The control file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testtask extends control
{
    /**
     * All products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * Project id.
     *
     * @var    int
     * @access public
     */
    public $projectID = 0;

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

        /* Get product data. */
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($this->app->tab == 'project')
            {
                $objectID = $this->session->project;
                $products  = $this->product->getProducts($objectID, 'all', '', false);
            }
            elseif($this->app->tab == 'execution')
            {
                $objectID = $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                $products = $this->product->getPairs('', 0, '', 'all');
            }
            if(empty($products) and !helper::isAjaxRequest()) helper::end($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testtask&objectID=$objectID")));
        }
        else
        {
            $products = $this->product->getPairs('', 0, '', 'all');
        }
        $this->view->products = $this->products = $products;
    }

    /**
     * Browse test tasks.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  string      $type
     * @param  string      $orderBy
     * @param  int         $recTotal
     * @param  int         $recPerPage
     * @param  int         $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $type = 'local,totalStatus', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $beginTime = 0, $endTime = 0)
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('reportList',   $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');

        $scopeAndStatus = explode(',', $type);
        $this->session->set('testTaskVersionScope', $scopeAndStatus[0]);
        $this->session->set('testTaskVersionStatus', $scopeAndStatus[1]);

        $beginTime = $beginTime ? date('Y-m-d', strtotime($beginTime)) : '';
        $endTime   = $endTime   ? date('Y-m-d', strtotime($endTime))   : '';

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $branch    = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
        $this->loadModel('qa')->setMenu($this->products, $productID, $branch, $type);
        $this->session->set('branch', $branch, 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Get tasks. */
        $product = $this->product->getById($productID);
        if($product->type == 'normal') $branch = 'all';
        $tasks = $this->testtask->getProductTasks($productID, $branch, $sort, $pager, $scopeAndStatus, $beginTime, $endTime);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;

        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->orderBy     = $orderBy;
        $this->view->tasks       = $tasks;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;
        $this->view->branch      = $branch;
        $this->view->beginTime   = $beginTime;
        $this->view->endTime     = $endTime;
        $this->view->product     = $product;

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
    public function browseUnits($productID = 0, $browseType = 'newest', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('buildList', $this->app->getURI(true) . '#app=' . $this->app->tab, 'execution');
        $this->loadModel('testcase');
        $this->app->loadLang('tree');

        /* Set menu. */
        $productID = $this->loadModel('product')->saveState($productID, $this->products);
        $product   = $this->product->getByID($productID);
        if($this->app->tab == 'project')
        {
            $this->lang->scrum->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
            $this->lang->scrum->menu->qa['subMenu']->testtask['subModule'] = '';

            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $this->lang->waterfall->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
                $this->lang->waterfall->menu->qa['subMenu']->testtask['subModule'] = '';
            }

            $this->loadModel('project')->setMenu($projectID);
            if(!$product->shadow) $this->lang->modulePageNav = $this->product->select($this->products, $productID, 'testtask', 'browseUnits', "projectID=$projectID", '', 0, '', false);
        }
        else
        {
            $this->lang->qa->menu->testcase['subModule'] .= ',testtask';
            $this->loadModel('qa')->setMenu($this->products, $productID);
        }

        /* Load pager. */
        if($browseType == 'newest') $recPerPage = '10';
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]  = html::a($this->createLink('testtask', 'browseUnits', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testtask->common;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->tasks       = $this->testtask->getProductUnitTasks($productID, $browseType, $sort, $pager);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager       = $pager;
        $this->view->product     = $product;
        $this->view->suiteList   = $this->loadModel('testsuite')->getSuites($productID);

        $this->display();
    }

    /**
     * Create a test task.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $build
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($productID, $executionID = 0, $build = 0, $projectID = 0)
    {
        if(!empty($_POST))
        {
            $taskID = $this->testtask->create($projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('testtask', $taskID, 'opened');

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $taskID));

            $task = $this->dao->findById($taskID)->from(TABLE_TESTTASK)->fetch();
            if($this->app->tab == 'project') $link = $this->createLink('project', 'testtask', "projectID=$task->project");
            if($this->app->tab == 'execution') $link = $this->createLink('execution', 'testtask', "executionID=$task->execution");
            if($this->app->tab == 'qa') $link = $this->createLink('testtask', 'browse', "productID=" . $this->post->product);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->loadModel('project');

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->project->setMenu($projectID);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($executionID);
        }
        elseif($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu($this->products, $productID);
        }

        /* Create testtask from testtask of test.*/
        $productID  = $productID ? $productID : key($this->products);
        $executions = empty($productID) ? array() : $this->loadModel('product')->getExecutionPairsByProduct($productID, '', 'id_desc', $projectID, 'stagefilter');
        $builds     = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs($productID, 'all', 'notrunk,withexecution', $projectID, 'project', '', false);

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(!empty($execution) and $execution->type == 'kanban') $this->lang->testtask->execution = str_replace($this->lang->execution->common, $this->lang->kanban->common, $this->lang->testtask->execution);

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);

        $project = $this->project->getByID($projectID);
        if($project && !$project->multiple) $this->view->noMultipleExecutionID = $this->loadModel('execution')->getNoMultipleID($project->id);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->create;

        $this->view->product     = $this->product->getByID($productID);
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->executions  = $executions;
        $this->view->builds      = $builds;
        $this->view->build       = $build;
        $this->view->testreports = array('') + $this->loadModel('testreport')->getPairs($productID);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|qdfirst|nodeleted');

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
        $taskID = (int)$taskID;
        $task   = $this->testtask->getById($taskID, true);
        if(!$task)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));
        }
        $this->checkAccess($task);

        /* When the session changes, you need to query the related products again. */
        if($this->session->project != $task->project) $this->view->products = $this->products = $this->product->getProductPairsByProject($task->project);
        $this->session->project = $task->project;

        $productID = $task->product;
        $buildID   = $task->build;

        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

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

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($task->project);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'project', $task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'execution', $task->execution);
        }
        elseif($this->app->tab == 'qa')
        {
            $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);
        }

        $this->executeHooks($taskID);

        if($task->execution) $this->view->execution = $this->loadModel('project')->getById($task->execution);

        $this->view->title           = "TASK #$task->id $task->name/" . $this->products[$productID];
        $this->view->productID       = $productID;
        $this->view->task            = $task;
        $this->view->users           = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions         = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->build           = $build;
        $this->view->testreportTitle = $this->dao->select('title')->from(TABLE_TESTREPORT)->where('id')->eq($task->testreport)->fetch('title');
        $this->view->stories         = $stories;
        $this->view->bugs            = $bugs;
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
        if($this->app->tab == 'project')
        {
            $this->lang->scrum->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
            $this->lang->scrum->menu->qa['subMenu']->testtask['subModule'] = '';
            $this->loadModel('project')->setMenu($this->session->project);
            $this->lang->modulePageNav = $this->product->select($this->products, $productID, 'testtask', 'browseUnits', '', '', 0, '', false);
        }
        else
        {
            $this->loadModel('qa')->setMenu($this->products, $productID);
            $this->app->rawModule = 'testcase';
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* Load lang. */
        $this->app->loadLang('testtask');
        $this->app->loadLang('execution');

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
     * Check access.
     *
     * @param  object $testtask
     * @access private
     * @return bool
     */
    private function checkAccess($testtask)
    {
        $canAccess = true;

        $view = $this->app->user->view;

        if(!$this->app->user->admin)
        {
            if($testtask->product   && strpos(",{$view->products},", ",$testtask->product,") === false)   $canAccess = false;
            if($testtask->project   && strpos(",{$view->projects},", ",$testtask->project,") === false)   $canAccess = false;
            if($testtask->execution && strpos(",{$view->sprints},",  ",$testtask->execution,") === false) $canAccess = false;
        }

        if($canAccess) return true;

        echo(js::alert($this->lang->testtask->accessDenied));
        echo js::locate(helper::createLink('testtask', 'browse'));

        return false;
    }

    /**
     * Browse cases of a test task.
     *
     * @param  int    $taskID
     * @param  string $browseType  bymodule|all|assignedtome
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function cases($taskID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load modules. */
        $this->loadModel('datatable');
        $this->loadModel('testcase');
        $this->loadModel('execution');

        /* Save the session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set the browseType and moduleID. */
        $browseType = strtolower($browseType);

        /* Get task and product info, set menu. */
        $task = $this->testtask->getById($taskID);
        if(!$task) return print(js::error($this->lang->testtask->checkLinked) . js::locate('back'));

        $this->checkAccess($task);

        $productID = $task->product;
        $product   = $this->product->getByID($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($task->project);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'project', $task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'execution', $task->execution);
        }
        else
        {
            $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);
        }
        setcookie('preTaskID', $taskID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Determines whether an object is editable. */
        $canBeChanged = common::canBeChanged('testtask', $task);

        if($this->cookie->preTaskID != $taskID)
        {
            $_COOKIE['taskCaseModule'] = 0;
            setcookie('taskCaseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }

        if($browseType == 'bymodule') setcookie('taskCaseModule', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        if($browseType != 'bymodule') $this->session->set('taskCaseBrowseType', $browseType);
        if($browseType == 'bysuite')  $suiteName = $this->loadModel('testsuite')->getById($param)->name;

        /* Set the browseType, moduleID and queryID. */
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->taskCaseModule ? $this->cookie->taskCaseModule : 0));
        $queryID    = ($browseType == 'bysearch' or $browseType == 'bysuite') ? (int)$param : 0;

        /* Get execution type and set assignedToList. */
        $execution = $this->execution->getById($task->execution);
        if($execution and $execution->acl == 'private')
        {
            $assignedToList = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        }
        else
        {
            $assignedToList = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted|qafirst');
        }

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy, 't2.id');

        /* Get test cases. */
        $runs = $this->testtask->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $task);
        $runs = $this->loadModel('story')->checkNeedConfirm($runs);

        $case2RunMap = array();
        foreach($runs as $run) $case2RunMap[$run->case] = $run->id;
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $scenesGroup = $this->testtask->getSceneCases($productID, $runs);
        $runs        = $scenesGroup['runs'];
        $scenes      = $scenesGroup['scenes'];

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['module']                      = 'testtask';
        $this->config->testcase->search['params']['product']['values'] = array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['params']['status']['values']  = array('' => '') + $this->lang->testcase->statusList;
        $this->config->testcase->search['params']['lib']['values']     = $this->loadModel('caselib')->getLibraries();
        $this->config->testcase->search['params']['scene']['values']   = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0,  0);

        $this->config->testcase->search['queryID']              = $queryID;
        $this->config->testcase->search['fields']['assignedTo'] = $this->lang->testtask->assignedTo;
        $this->config->testcase->search['params']['assignedTo'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
        $this->config->testcase->search['actionURL'] = inlink('cases', "taskID=$taskID&browseType=bySearch&queryID=myQueryID");
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        if($product->shadow) unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $showModule = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=datatable&section=testtaskCases&key=showModule");

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->cases;

        $this->view->productID      = $productID;
        $this->view->productName    = $this->products[$productID];
        $this->view->task           = $task;
        $this->view->runs           = array_merge($scenes, $runs);
        $this->view->case2RunMap    = $case2RunMap;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|qafirst|noletter');
        $this->view->assignedToList = $assignedToList;
        $this->view->moduleTree     = $this->loadModel('tree')->getTreeMenu($productID, 'case', 0, array('treeModel', 'createTestTaskLink'), $taskID, $task->branch);
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->taskID         = $taskID;
        $this->view->moduleID       = $moduleID;
        $this->view->moduleName     = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->treeClass      = $browseType == 'bymodule' ? '' : 'hidden';
        $this->view->pager          = $pager;
        $this->view->branches       = $this->loadModel('branch')->getPairs($productID);
        $this->view->setModule      = true;
        $this->view->showBranch     = false;
        $this->view->suites         = $this->loadModel('testsuite')->getSuitePairs($productID);
        $this->view->suiteName      = isset($suiteName) ? $suiteName : $this->lang->testtask->browseBySuite;
        $this->view->canBeChanged   = $canBeChanged;
        $this->view->automation     = $this->loadModel('zanode')->getAutomationByProduct($productID);
        $this->view->modulePairs    = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : array();

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

        $task = $this->testtask->getById($taskID);
        $this->checkAccess($task);

        if(!empty($_POST))
        {
            $this->app->loadLang('testcase');
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

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($task->project);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'project', $task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'execution', $task->execution);
        }
        else
        {
            $this->testtask->setMenu($this->products, $productID, $branchID, $taskID);
        }
        unset($this->lang->testtask->report->charts['bugStageGroups']);
        unset($this->lang->testtask->report->charts['bugHandleGroups']);

        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

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
        $this->app->loadLang('execution');
        $this->app->loadLang('task');
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        setcookie('taskCaseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Get task and product info, set menu. */
        $groupBy = empty($groupBy) ? 'story' : $groupBy;
        $task    = $this->testtask->getById($taskID);
        if(!$task) return print(js::error($this->lang->notFound) . js::locate('back'));

        $productID = $task->product;
        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'project', $task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'execution', $task->execution);
        }
        else
        {
            $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);
        }

        /* Determines whether an object is editable. */
        $canBeChanged = common::canBeChanged('testtask', $task);

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
            $buildStories     = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($buildStoryIdList)->andWhere('deleted')->eq(0)->andWhere('id')->notin(array_keys($groupCases))->fetchAll('id');
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

        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->productID    = $productID;
        $this->view->task         = $task;
        $this->view->taskID       = $taskID;
        $this->view->browseType   = 'group';
        $this->view->groupBy      = $groupBy;
        $this->view->groupByList  = $groupByList;
        $this->view->cases        = $groupCases;
        $this->view->account      = 'all';
        $this->view->canBeChanged = $canBeChanged;
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
        /* Get task info. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->loadModel('product')->saveState($task->product, $this->products);

        if(!empty($_POST))
        {
            $changes = $this->testtask->update($taskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            $link = isonlybody() ? 'parent' : $this->session->testtaskList;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->loadModel('project');

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->project->setMenu($task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
        }
        else
        {
            $this->loadModel('qa')->setMenu($this->products, $productID, $task->branch, $taskID);
        }

        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        /* Create testtask from testtask of test.*/
        $productID   = $productID ? $productID : key($this->products);
        $projectID   = $this->lang->navGroup->testtask == 'qa' ? 0 : $this->session->project;
        $executions  = empty($productID) ? array() : $this->product->getExecutionPairsByProduct($productID, 0, 'id_desc', $projectID);
        $executionID = $task->execution;
        if($executionID)
        {
            $execution = $this->loadModel('execution')->getById($executionID);
            if(!isset($executions[$executionID]))
            {
                $executions[$executionID] = $execution->name;
                if(empty($execution->multiple))
                {
                    $project = $this->loadModel('project')->getById($execution->project);
                    $executions[$executionID] = $project->name . "({$this->lang->project->disableExecution})";
                }
            }
            $builds = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noempty,notrunk,withexecution', $executionID, 'execution', $task->build, false);
        }
        else
        {
            $builds = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noempty,notrunk,withexecution', $task->project, 'project', $task->build, false);
        }

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->task         = $task;
        $this->view->project      = $this->project->getByID($projectID);
        $this->view->executions   = $executions;
        $this->view->builds       = empty($productID) ? array() : $builds;
        $this->view->testreports  = $this->loadModel('testreport')->getPairs($task->product, $task->testreport);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted|noclosed', $task->owner);
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($this->products, $productID, $testtask->branch, $taskID);

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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($this->products, $productID, $testtask->branch, $taskID);

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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent.parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->success, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($this->products, $productID, $testtask->branch, $taskID);

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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'Blocked', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID);
            if($message) $this->lang->saveSuccess = $message;

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testtask', 'view', "taskID=$taskID")));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($this->products, $productID, $testtask->branch, $taskID);

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
            return print(js::confirm($this->lang->testtask->confirmDelete, inlink('delete', "taskID=$taskID&confirm=yes")));
        }
        else
        {
            $task = $this->testtask->getByID($taskID);
            $this->testtask->delete(TABLE_TESTTASK, $taskID);

            $message = $this->executeHooks($taskID);
            if($message) $response['message'] = $message;

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
                return $this->send($response);
            }

            $browseList = $this->createLink('testtask', 'browse', "productID=$task->product");
            if($this->app->tab == 'execution') $browseList = $this->createLink('execution', 'testtask', "executionID=$task->execution");
            if($this->app->tab == 'project')   $browseList = $this->createLink('project', 'testtask', "projectID=$task->project");
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($browseList, 'parent'));
        }
    }

    /**
     * Link cases to a test task.
     *
     * @param  int    $taskID
     * @param  string $type
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
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
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* Get task and product id. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);
        $product   = $this->product->getByID($productID);

        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;
        $this->checkAccess($task);

        /* Save session. */
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($task->project);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'project', $task->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->loadModel('execution')->setMenu($task->execution);
            $this->lang->modulePageNav = $this->testtask->select($productID, $taskID, 'execution', $task->execution);
        }
        else
        {
            $this->testtask->setMenu($this->products, $productID, $task->branch, $taskID);
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['product']['values'] = array($productID => $this->products[$productID]);
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $task->branch);
        $this->config->testcase->search['actionURL']                   = inlink('linkcase', "taskID=$taskID&type=$type&param=$param");
        $this->config->testcase->search['params']['scene']['values']   = $this->testcase->getSceneMenu($productID, 0, $viewType = 'case', $startSceneID = 0,  0);
        $this->config->testcase->search['style']                       = 'simple';

        $build   = $this->loadModel('build')->getByID($task->build);
        $stories = array();
        if($build)
        {
            $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($build->stories)->fetchPairs();
            $this->config->testcase->search['params']['story']['values'] = $stories;
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        }

        if($product->shadow) unset($this->config->testcase->search['fields']['product']);
        if($type != 'bystory')
        {
            unset($this->config->testcase->search['fields']['story']);
            unset($this->config->testcase->search['params']['story']);
        }
        if($task->productType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->config->testcase->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$task->productType]);
            $branchName = $this->loadModel('branch')->getById($task->branch);
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main, $task->branch => $branchName);
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
            return print(js::confirm($this->lang->testtask->confirmUnlinkCase, $this->createLink('testtask', 'unlinkCase', "rowID=$rowID&confirm=yes")));
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $testRun = $this->dao->select('t1.task,t1.`case`,t2.story')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
                ->where('t1.id')->eq((int)$rowID)
                ->fetch();

            $linkedProject = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($testRun->story)->fetchPairs('project');
            $this->dao->delete()->from(TABLE_PROJECTCASE)->where('`case`')->eq($testRun->case)->andWhere('project')->notin($linkedProject)->exec();

            $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq((int)$rowID)->exec();
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
            }
            $this->loadModel('action')->create('case' ,$testRun->case, 'unlinkedfromtesttask', '', $testRun->task);
            return $this->send($response);
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
            $this->loadModel('action');
            foreach($_POST['caseIDList'] as $caseID) $this->action->create('case', $caseID, 'unlinkedfromtesttask', '', $taskID);
        }

        return print(js::locate($this->createLink('testtask', 'cases', "taskID=$taskID")));
    }

    /**
     * Run case.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function runCase($runID, $caseID = 0, $version = 0, $confirm = '')
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
        $automation = $this->loadModel('zanode')->getAutomationByProduct($run->case->product);
        $confirmURL = inlink('runCase', "runID=$runID&caseID=$caseID&version=$version&confirm=yes");
        $cancelURL  = inlink('runCase', "runID=$runID&caseID=$caseID&version=$version&confirm=no");

        if($automation and $confirm == '' and $run->case->auto == 'auto') return print(js::confirm($this->lang->zanode->runCaseConfirm, $confirmURL, $cancelURL));
        if($confirm == 'yes')
        {
            $resultID = $this->testtask->initResult($runID, $caseID, $run->case->version, $automation->node);
            if(!dao::isError()) $this->zanode->runZTFScript($automation->id, $caseID, $resultID);
            if(dao::isError()) return print(js::error(dao::getError()) . js::locate($this->createLink('zanode', 'browse'), 'parent'));
        }

        if(!empty($_POST))
        {
            $caseResult = $this->testtask->createResult($runID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $taskID = empty($run->task) ? 0 : $run->task;
            $this->loadModel('action')->create('case', $caseID, 'run', '', $taskID);
            if($caseResult == 'fail')
            {

                $response['result']  = 'success';
                $response['locate']  = $this->createLink('testtask', 'results',"runID=$runID&caseID=$caseID&version=$version");
                return $this->send($response);
            }
            else
            {
                /* set cookie for ajax load caselist when close colorbox. */
                setcookie('selfClose', 1, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

                if($preAndNext->next and $this->app->tab != 'my')
                {
                    $nextRunID   = $runID ? $preAndNext->next->id : 0;
                    $nextCaseID  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
                    $nextVersion = $preAndNext->next->version;

                    $response['result'] = 'success';
                    $response['next']   = 'success';
                    $response['locate'] = inlink('runCase', "runID=$nextRunID&caseID=$nextCaseID&version=$nextVersion");
                    return $this->send($response);
                }
                else
                {
                    $response['result'] = 'success';
                    $response['locate'] = 'reload';
                    $response['target'] = 'parent';
                    return $this->send($response);
                }
            }
        }

        $preCase  = array();
        $nextCase = array();
        if($preAndNext->pre and $this->app->tab != 'my')
        {
            $preCase['runID']   = $runID ? $preAndNext->pre->id : 0;
            $preCase['caseID']  = $runID ? $preAndNext->pre->case : $preAndNext->pre->id;
            $preCase['version'] = $preAndNext->pre->version;
        }
        if($preAndNext->next and $this->app->tab != 'my')
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
        $this->view->confirm  = $confirm;

        $this->display();
    }

    /**
     * Batch run case.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $from
     * @param  int    $taskID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function batchRun($productID, $orderBy = 'id_desc', $from = 'testcase', $taskID = 0, $confirm = '')
    {
        $this->loadModel('tree');
        $url = $this->session->caseList ? $this->session->caseList : $this->createLink('testcase', 'browse', "productID=$productID");
        $automation = $this->loadModel('zanode')->getAutomationByProduct($productID);

        if($this->post->results)
        {
            $this->testtask->batchRun($from, $taskID);
            $this->loadModel('action');
            foreach(array_keys($this->post->results) as $caseID) $this->action->create('case', $caseID, 'run', '', $taskID);
            return print(js::locate($url, 'parent'));
        }

        if(!$this->post->caseIDList) return print(js::locate($url, 'parent'));
        $caseIDList = array_filter($this->post->caseIDList);

        /* The case of tasks of qa. */
        if($productID or ($this->app->tab == 'project' and empty($productID)))
        {
            if($this->app->tab == 'project')
            {
                $this->loadModel('project')->setMenu($this->session->project);
            }
            elseif($this->app->tab == 'execution')
            {
                $this->loadModel('execution')->setMenu($this->session->execution);
            }
            else
            {
                $this->loadModel('qa')->setMenu($this->products, $productID, $taskID);
            }

            $cases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($caseIDList)->fetchAll('id');
        }
        /* The case of my. */
        else
        {
            if($this->app->tab == 'project')
            {
                $this->loadModel('project')->setMenu($this->session->project);
                $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                    ->where('t2.id')->in($caseIDList)
                    ->fetchAll('id');
            }
            else
            {
                $this->lang->testtask->menu = $this->lang->my->menu->work;
                $this->lang->my->menu->work['subModule'] = 'testtask';

                $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                    ->where('t1.id')->in($caseIDList)
                    ->fetchAll('id');
            }

            $this->view->title = $this->lang->testtask->batchRun;
        }

        /* Set modules. */
        $moduleOptionMenu = array(0 => '/');
        foreach($cases as $caseID => $case)
        {
            if($case->auto == 'auto' and $confirm == 'yes') unset($cases[$caseID]);
            $moduleOptionMenu += $this->tree->getModulesName($case->module);
        }
        if(empty($cases)) return print(js::locate($url));

        $this->view->moduleOptionMenu = $moduleOptionMenu;

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
            ->where('t2.id')->in($caseIDList)
            ->andWhere('t1.version=t2.version')
            ->andWhere('t2.status')->ne('wait')
            ->fetchGroup('case', 'id');

        $this->view->caseIDList = array_keys($cases);
        $this->view->productID  = $productID;
        $this->view->title      = $this->lang->testtask->batchRun;
        $this->view->position[] = $this->lang->testtask->common;
        $this->view->position[] = $this->lang->testtask->batchRun;
        $this->view->from       = $from;
        $this->view->confirm    = $confirm;
        $this->display();
    }

    /**
     * View test results of a test run.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $status  all|done
     * @access public
     * @return void
     */
    public function results($runID, $caseID = 0, $version = 0, $status = 'done')
    {
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        if($runID)
        {
            $case    = $this->testtask->getRunById($runID)->case;
            $results = $this->testtask->getResults($runID, 0, $status);

            $testtaskID = $this->dao->select('task')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch('task');
            $testtask   = $this->dao->select('id, build, execution, product')->from(TABLE_TESTTASK)->where('id')->eq($testtaskID)->fetch();

            $this->view->testtask = $testtask;
        }
        else
        {
            $case    = $this->loadModel('testcase')->getByID($caseID, $version);
            $results = $this->testtask->getResults(0, $caseID, $status);
        }

        $this->view->case    = $case;
        $this->view->runID   = $runID;
        $this->view->results = $results;
        $this->view->builds  = $this->loadModel('build')->getBuildPairs($case->product, $case->branch);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed, noletter');

        $this->display();
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
        $this->loadModel('action');
        foreach($this->post->caseIDList as $caseID) $this->action->create('case', $caseID, 'assigned', '', $this->post->assignedTo);
        return print(js::locate($this->session->caseList, 'parent'));
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
            $taskID = $this->testtask->importUnitResult($productID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('action')->create('testtask', $taskID, 'opened');
            return print(js::locate($this->createLink('testtask', 'unitCases', "taskID=$taskID"), 'parent'));
        }

        /* Set menu. */
        $productID = $this->loadModel('product')->saveState($productID, $this->products);
        if($this->app->tab == 'project')
        {
            $this->lang->scrum->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
            $this->lang->scrum->menu->qa['subMenu']->testtask['subModule'] = '';
            $this->loadModel('project')->setMenu($this->session->project);
            $this->lang->modulePageNav = $this->product->select($this->products, $productID, 'testtask', 'browseUnits', '', '', 0, '', false);

            /* Replace language. */
            $project = $this->project->getByID($this->session->project);
            if(!empty($project->model) and $project->model == 'waterfall')
            {
                $this->lang->testtask->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->testtask->execution);
            }
        }
        else
        {
            $this->loadModel('qa')->setMenu($this->products, $productID);
            $this->app->rawModule = 'testcase';
        }

        $this->app->loadLang('job');
        $this->app->rawModule = 'testcase';

        $projectID  = $this->app->tab == 'qa' ? 0 : $this->session->project;
        $executions = empty($productID) ? array() : $this->loadModel('product')->getExecutionPairsByProduct($productID, '', 'id_desc', $projectID);
        $builds     = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs($productID, 'all', 'notrunk', 0, 'execution', '', false);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->importUnitResult;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->importUnitResult;

        $this->view->executions = $executions;
        $this->view->builds     = $builds;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->productID  = $productID;
        $this->view->projectID  = $projectID;
        $this->display();
    }

    /**
     * AJAX: return test tasks of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserTestTasks($userID = '', $id = '', $status = 'all')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $testTasks = $this->testtask->getUserTestTaskPairs($account, 0, $status);

        if($id) return print(html::select("testtasks[$id]", $testTasks, '', 'class="form-control"'));
        return print(html::select('testtask', $testTasks, '', 'class="form-control"'));
    }

    /**
     * Ajax get test tasks.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetTestTasks($productID, $executionID = 0)
    {
        $pairs = $this->testtask->getPairs($productID, $executionID);
        return print(html::select('testtask', $pairs, '', "class='form-control chosen'"));
    }

    /**
     * Ajax: Get test report by productID.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetTestReports($productID)
    {
        /* Testreport list. */
        $pairs = $this->loadModel('testreport')->getPairs($productID);
        return print(html::select('testreport', array('') + $pairs, '', "class='form-control chosen'"));
    }

    /**
     * Drop menu page.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $taskID
     * @param  string $module
     * @param  string $method
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $branch, $taskID, $module, $method, $objectType = '', $objectID = 0)
    {
        $scope     = empty($objectType) ? 'local' : 'all';
        $testtasks = $this->testtask->getProductTasks($productID, $branch, 'id_desc', null, array($scope, 'totalStatus'));

        $namePairs = array();
        foreach($testtasks as $testtaskID => $testtask) $namePairs[$testtaskID] = $testtask->name;

        $this->view->currentTaskID   = $taskID;
        $this->view->testtasks       = $testtasks;
        $this->view->module          = $module;
        $this->view->method          = $method;
        $this->view->productID       = $productID;
        $this->view->branch          = $branch;
        $this->view->objectType      = $objectType;
        $this->view->objectID        = $objectID;
        $this->view->testtasksPinyin = common::convert2Pinyin($namePairs);

        $this->display();
    }

    /**
     * Ajax get test result info.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetResult($resultID)
    {
        $result = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('id')->eq((int)$resultID)->fetch();
        $this->send(array('result' => 'success', 'message' => '', 'data' => $result));
    }

    /**
     * AJAX: Get executionID by buildID.
     *
     * @param  int    $buildID
     * @access public
     * @return int
     */
    public function ajaxGetExecutionByBuild($buildID)
    {
        $execution = $this->loadModel('execution')->getByBuild($buildID);
        return print($execution ? $execution->id : 0);
    }
}
