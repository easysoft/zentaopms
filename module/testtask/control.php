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
     * 产品键值对。
     * Product key-value pairs.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * 项目 ID。
     * Project id.
     *
     * @var    int
     * @access public
     */
    public $projectID = 0;

    /**
     * 扩展构造方法，获取产品键值对。
     * Extend the constructor to get product key-value pairs.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(static::class == 'testtask')
        {
            $products = $this->testtaskZen->getProducts();

            /* 如果没有获取到产品键值对、并且即不是弹窗页面也不是 ajax 请求，那么跳转到错误提示页面。*/
            /* If the product key-value pair is not obtained and it is not a pop-up page or an ajax request, then jump to the error page. */
            if(empty($products) && !isonlybody() && !helper::isAjaxRequest())
            {
                $tab      = ($this->app->tab == 'project' || $this->app->tab == 'execution') ? $this->app->tab : 'qa';
                $objectID = ($tab == 'project' || $tab == 'execution') ? $this->session->$tab : 0;
                helper::end($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testtask&objectID=$objectID")));
            }

            $this->products       = $products;
            $this->view->products = $products;
        }
    }

    /**
     * 测试单的列表。
     * Browse test tasks.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $beginTime
     * @param  string $endTime
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $branch = '', string $type = 'local,totalStatus', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $beginTime = '', string $endTime = '')
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('reportList',   $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');

        /* Set menu. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);
        $this->loadModel('qa')->setMenu($productID, $branch);
        $this->session->set('branch', $branch, 'qa');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        /* Get tasks. */
        $beginTime = $beginTime ? date('Y-m-d', strtotime($beginTime)) : '';
        $endTime   = $endTime   ? date('Y-m-d', strtotime($endTime))   : '';
        $product   = $this->product->getById($productID);
        if($product->type == 'normal') $branch = 'all';
        $testtasks = $this->testtask->getProductTasks($productID, $branch, $type, $beginTime, $endTime, $sort, $pager);

        /* 获取不同状态测试单的数量，用于列表底部统计信息展示。 */
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($testtasks as $testtask)
        {
            if($testtask->status == 'wait')    $waitCount ++;
            if($testtask->status == 'doing')   $testingCount ++;
            if($testtask->status == 'blocked') $blockedCount ++;
            if($testtask->status == 'done')    $doneCount ++;
            if($testtask->build == 'trunk' || empty($testtask->buildName)) $testtask->buildName = $this->lang->trunk;
        }

        $this->view->title       = $product->name . $this->lang->colon . $this->lang->testtask->common;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->allSummary  = sprintf($this->lang->testtask->allSummary, count($testtasks), $waitCount, $testingCount, $blockedCount, $doneCount);
        $this->view->pageSummary = sprintf($this->lang->testtask->pageSummary, count($testtasks));
        $this->view->tasks       = $testtasks;
        $this->view->product     = $product;
        $this->view->branch      = $branch;
        $this->view->type        = $type;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->beginTime   = $beginTime;
        $this->view->endTime     = $endTime;
        $this->display();
    }

    /**
     *
     * 单元测试列表页面。
     * Browse unit tasks.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseUnits(int $productID = 0, string $browseType = 'newest', int $projectID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('caseList',     $this->app->getURI(true), $this->app->tab);
        $this->session->set('buildList',    $this->app->getURI(true) . '#app=' . $this->app->tab, 'execution');

        /* Set menu. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);
        $this->loadModel('qa')->setMenu($productID);
        $this->app->rawModule = 'testcase';

        /* Load pager. */
        if($browseType == 'newest') $recPerPage = '10';
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);

        $this->app->loadLang('testcase');
        $this->lang->testcase->featureBar['browseunits'] = $this->lang->testtask->unitTag;

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->productID  = $productID;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->tasks      = $this->testtask->getProductUnitTasks($productID, $browseType, $sort, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 创建测试单页面和创建表单的提交。
     * Create a test task.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $build
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create(int $productID, int $executionID = 0, int $build = 0, int $projectID = 0)
    {
        if(!empty($_POST))
        {
            /* 表单数据的收集和组装。 */
            $formData = form::data($this->config->testtask->form->create)
                ->setDefault('project', $projectID)
                ->setDefault('createdBy', $this->app->user->account)
                ->setDefault('createdDate', helper::now())
                ->get();

            $formData = $this->loadModel('file')->processImgURL($formData, $this->config->testtask->editor->create['id'], $this->post->uid);
            if($formData->execution)
            {
                $execution = $this->loadModel('execution')->getByID($formData->execution);
                $formData->projectID = $execution->project;
            }

            /* 进行测试单数据插入操作。 */
            $testtaskID = $this->testtask->create($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 插入数据后对上传的文件进行处理。 */
            $this->loadModel('file')->updateObjectID($this->post->uid, $testtaskID, 'testtask');
            $this->file->saveUpload('testtask', $testtaskID);

            /* 执行工作流的扩展动作并返回提示消息。 */
            $message = $this->executeHooks($testtaskID);
            if(!$message) $message = $this->lang->saveSuccess;

            /* 根据不同的应用生成不同的跳转链接。 */
            $testtask = $this->dao->findById($testtaskID)->from(TABLE_TESTTASK)->fetch();
            if($this->app->tab == 'project')   $link = $this->createLink('project', 'testtask', "projectID=$testtask->project");
            if($this->app->tab == 'execution') $link = $this->createLink('execution', 'testtask', "executionID=$testtask->execution");
            if($this->app->tab == 'qa')        $link = $this->createLink('testtask', 'browse', "productID=" . $this->post->product);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $link, 'id' => $testtaskID));
        }

        if($executionID)
        {
            /* 根据所选迭代的类型，调整表单字段的文本显示。 */
            $execution = $this->loadModel('execution')->getByID($executionID);
            if(!empty($execution) and $execution->type == 'kanban') $this->lang->testtask->execution = str_replace($this->lang->execution->common, $this->lang->kanban->common, $this->lang->testtask->execution);
        }

        if($projectID)
        {
            /* 如果是无迭代项目， 则获取影子迭代的迭代ID */
            $project = $this->loadModel('project')->getByID($projectID);
            if($project && !$project->multiple) $this->view->noMultipleExecutionID = $this->loadModel('execution')->getNoMultipleID($project->id);
        }

        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);
        $this->testtaskZen->setMenu($productID, 0, $projectID, $executionID);

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->product     = $this->product->getByID($productID);
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->executions  = $productID ? $this->loadModel('product')->getExecutionPairsByProduct($productID, '', 'id_desc', $projectID, 'stagefilter') : array();
        $this->view->builds      = $productID ? $this->loadModel('build')->getBuildPairs($productID, 'all', 'notrunk,withexecution', $projectID, 'project', '', false) : array();
        $this->view->build       = $build;
        $this->view->testreports = array('') + $this->loadModel('testreport')->getPairs($productID);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|qdfirst|nodeleted');
        $this->display();
    }

    /**
     * 查看当前测试单的概要信息。
     * View a test task.
     *
     * @param  int    $testtaskID
     * @access public
     * @return void
     */
    public function view(int $testtaskID)
    {
        /* Get test task. */
        $testtask = $this->testtask->getByID($testtaskID, true);
        if(!$testtask) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        /* When the session changes, you need to query the related products again. */
        $products = $this->products;
        if($this->session->project != $testtask->project) $products = $this->loadModel('product')->getProductPairsByProject($testtask->project);
        $this->session->project = $testtask->project;

        /* 如果该测试单的所属产品不在products里，则把所属产品塞入到products里。 */
        $productID = $testtask->product;
        if(!isset($products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $products[$productID] = $product->name;
        }

        $this->testtaskZen->setMenu($productID, $testtask->branch, $testtask->project, $testtask->execution);

        $this->executeHooks($testtaskID); // 执行工作流配置的扩展动作。

        if($testtask->execution) $this->view->execution = $this->loadModel('project')->getByID($testtask->execution);

        $this->view->title      = "TASK #$testtask->id $testtask->name/" . $products[$productID];
        $this->view->task       = $testtask;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('testtask', $testtaskID);
        $this->view->testreport = $this->loadModel('testreport')->getById($testtask->testreport);
        $this->display();
    }

    /**
     * 查看单元测试测试单的用例列表。
     * Browse unit cases.
     *
     * @param  int    $testtaskID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function unitCases(int $testtaskID, string $orderBy = 't1.id_asc')
    {
        /* Set browseType, productID, moduleID and queryID. */
        $testtask  = $this->testtask->getByID($testtaskID);
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);
        if($this->app->tab == 'project')
        {
            $this->lang->scrum->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
            $this->lang->scrum->menu->qa['subMenu']->testtask['subModule'] = '';
            $this->loadModel('project')->setMenu($this->session->project);
        }
        else
        {
            $this->loadModel('qa')->setMenu($productID);
            $this->app->rawModule = 'testcase';
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* Get test cases. */
        $runs = $this->testtask->groupRunsBySuite($testtaskID, "t4.suite_asc,$orderBy");

        /* 因为套件和测试单是多堆垛关系，所以要过滤掉相同ID的测试单执行数据。 */
        $filterRuns = array();
        foreach($runs as $run)
        {
            if(empty($filterRuns[$run->id])) $filterRuns[$run->id] = $run;
        }

        /* save session .*/
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        /* append run case result to runs. */
        $filterRuns = $this->loadModel('testcase')->appendData($filterRuns, 'testrun');

        /* 将测试单执行数据按照套件进行分组，方便按套件计算数量。 */
        $groupCases = array();
        foreach($filterRuns as $run) $groupCases[$run->suite][] = $run;

        /* 将每个套件下的总执行数量赋予每个套件的第一条执行记录。 */
        $suite = null;
        foreach($filterRuns as $run)
        {
            $run->rowspan = 0;
            if($suite !== $run->suite)
            {
                $suite = $run->suite;
                if(!empty($groupCases[$run->suite])) $run->rowspan = count($groupCases[$run->suite]);
            }
        }

        /* Assign. */
        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->productID   = $productID;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->runs        = $filterRuns;
        $this->view->taskID      = $testtaskID;
        $this->display();
    }

    /**
     * 查看非单元测试的测试单的用例列表。
     * Browse cases of a test task which isn't unit test.
     *
     * @param  int    $taskID
     * @param  string $browseType  all|assignedtome|bysuite|byModule
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function cases(int $taskID, string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 获取测试单信息。*/
        /* Get testtask info. */
        $testtask = $this->testtask->getByID($taskID);
        if(!$testtask) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->testtask->checkLinked, 'locate' => array('back' => true))));

        /* 预处理部分变量供后面使用。*/
        /* Prepare variables. */
        $browseType = strtolower($browseType);
        $param      = (int)$param;

        /* 保存部分内容到 session 中供后面使用。*/
        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        if($browseType != 'bymodule') $this->session->set('taskCaseBrowseType', $browseType);

        /* 保存部分内容到 cookie 中供后面使用。*/
        /* Save cookies. */
        helper::setcookie('preTaskID', $taskID);
        if($this->cookie->preTaskID != $taskID) helper::setcookie('taskCaseModule', 0, 0);
        if($browseType == 'bymodule') helper::setcookie('taskCaseModule', $param, 0);

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
        $products  = $this->products;
        $productID = $testtask->product;
        $product   = $this->loadModel('product')->getByID($productID);
        if(!isset($products[$productID])) $products[$productID] = $product->name;

        $this->testtaskZen->setMenu($productID, $testtask->branch, $testtask->project, $testtask->execution);

        /* 预处理部分变量供查询使用。*/
        /* Prepare variables for query. */
        $this->app->loadClass('pager', $static = true);
        $pager    = pager::init($recTotal, $recPerPage, $pageID);
        $queryID  = ($browseType == 'bysearch' or $browseType == 'bysuite') ? $param : 0;
        $moduleID = ($browseType == 'bymodule') ? $param : ($browseType == 'bysearch' ? 0 : ($this->cookie->taskCaseModule ?: 0));
        $sort     = common::appendOrder($orderBy, 't2.id');

        /* 从数据库中查询一个测试单下的测试用例。*/
        /* Get cases of a testtask from database. */
        $runs = $this->testtask->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $testtask);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $this->testtaskZen->setSearchParamsForCases($product, $moduleID, $taskID, $queryID);
        $this->testtaskZen->assignForCases($product, $testtask, $runs, $moduleID, $browseType, $param, $orderBy, $pager);
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

        $task = $this->testtask->getByID($taskID);

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

        $this->testtaskZen->setMenu($productID, $branchID, $task->project, $task->execution);

        unset($this->lang->testtask->report->charts['bugStageGroups']);
        unset($this->lang->testtask->report->charts['bugHandleGroups']);

        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common . $this->lang->colon . $this->lang->testtask->reportChart;
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
        helper::setcookie('taskCaseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Get task and product info, set menu. */
        $groupBy = empty($groupBy) ? 'story' : $groupBy;
        $task    = $this->testtask->getByID($taskID);
        if(!$task) return print(js::error($this->lang->notFound) . js::locate('back'));

        $productID = $task->product;
        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->testtaskZen->setMenu($productID, $task->branch, $task->project, $task->execution);

        /* Determines whether an object is editable. */
        $canBeChanged = common::canBeChanged('testtask', $task);

        $runs = $this->testtask->getRuns($taskID, 0, $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $runs = $this->testcase->appendData($runs, 'run');
        $groupCases  = array();
        foreach($runs as $run)
        {
            if($groupBy == 'story')
            {
                $groupCases[$run->story][] = $run;
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
            }
        }

        $story = null;
        foreach($runs as $run)
        {
            $run->rowspan = 0;
            if($story !== $run->story)
            {
                $story = $run->story;
                if(!empty($groupCases[$run->story])) $run->rowspan = count($groupCases[$run->story]);
            }
        }

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;

        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->productID    = $productID;
        $this->view->task         = $task;
        $this->view->taskID       = $taskID;
        $this->view->browseType   = 'group';
        $this->view->groupBy      = $groupBy;
        $this->view->runs         = $runs;
        $this->view->account      = 'all';
        $this->view->canBeChanged = $canBeChanged;
        $this->display();
    }

    /**
     * 编辑一个测试单。
     * Edit a test task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function edit(int $taskID)
    {
        if(!empty($_POST))
        {
            $oldTask = $this->testtask->getByID($taskID);
            $task    = $this->testtaskZen->buildTaskForEdit($taskID, $oldTask->product);
            $this->testtaskZen->checkTaskForEdit($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->testtask->update($task, $oldTask);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes || $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->session->testtaskList, 'closeModal' => true));
        }

        /* Get task info. */
        $task      = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess($task->product, $this->products);

        if(!isset($this->products[$productID]) && $productID)
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->testtaskZen->setMenu($productID, $task->branch, $task->project, $task->execution);
        $this->testtaskZen->assignForEdit($task, $productID);

        $this->display();
    }

    /**
     * 开始一个测试单。
     * Start a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function start(int $taskID)
    {
        if(!empty($_POST))
        {
            $task = $this->testtaskZen->buildTaskForStart($taskID);

            $this->testtask->start($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($productID, $testtask->branch);

        $this->view->title    = $testtask->name . $this->lang->colon . $this->lang->testtask->start;
        $this->view->actions  = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->testtask = $testtask;
        $this->display();
    }

    /**
     * 关闭一个测试单。
     * Close a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function close(int $taskID)
    {
        if(!empty($_POST))
        {
            $task = $this->testtaskZen->buildTaskForClose($taskID);

            $this->testtask->close($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess((int)$testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($productID, $testtask->branch);

        $this->view->title        = $testtask->name . $this->lang->colon . $this->lang->close;
        $this->view->actions      = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|nodeleted|qdfirst');
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');
        $this->view->testtask     = $testtask;
        $this->display();
    }

    /**
     * 阻塞一个测试单。
     * Block a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function block(int $taskID)
    {
        if(!empty($_POST))
        {
            $task = $this->testtaskZen->buildTaskForBlock($taskID);

            $this->testtask->block($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($productID, $testtask->branch);

        $this->view->title    = $testtask->name . $this->lang->colon . $this->lang->testtask->block;
        $this->view->actions  = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->testtask = $testtask;
        $this->display();
    }

    /**
     * 激活一个测试单。
     * Activate a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function activate(int $taskID)
    {
        if(!empty($_POST))
        {
            $task = $this->testtaskZen->buildTaskForActivate($taskID);

            $this->testtask->activate($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);

        /* Set menu. */
        $this->loadModel('qa')->setMenu($productID, $testtask->branch);

        $this->view->title    = $testtask->name . $this->lang->colon . $this->lang->testtask->activate;
        $this->view->actions  = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted', $testtask->owner);
        $this->view->testtask = $testtask;
        $this->display();
    }

    /**
     * 删除一个测试单。
     * Delete a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function delete(int $taskID)
    {
        $task = $this->testtask->getByID($taskID);
        if(!$task) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        $this->testtask->delete(TABLE_TESTTASK, $taskID);
        if(dao::isError()) return $this->send(array('result' => 'success', 'message' => dao::getError()));

        $message = $this->executeHooks($taskID) ?: $this->lang->saveSuccess;

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

        $browseList = inlink('browse', "productID=$task->product");
        if($this->app->tab == 'execution') $browseList = $this->createLink('execution', 'testtask', "executionID=$task->execution");
        if($this->app->tab == 'project')   $browseList = $this->createLink('project', 'testtask', "projectID=$task->project");

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->app->methodName == 'view' ? $browseList : true));
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
            if(dao::isError()) return $this->send(array('result' => 'success', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('cases', "taskID={$taskID}")));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        /* Get task and product id. */
        $task      = $this->testtask->getByID($taskID);
        $productID = $this->loadModel('product')->checkAccess($task->product, $this->products);
        $product   = $this->product->getByID($productID);

        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        $this->testtaskZen->setMenu($productID, $task->branch, $task->project, $task->execution);

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
     * 从测试单中移除一个用例。
     * Remove a case from testtask.
     *
     * @param  int    $runID
     * @access public
     * @return void
     */
    public function unlinkCase(int $runID)
    {
        $this->testtask->unlinkCase($runID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 批量从测试单中移除用例。
     * Batch remove cases from a testtask.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function batchUnlinkCases(int $taskID)
    {
        $this->testtask->batchUnlinkCases($taskID, $this->post->caseIdList);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $taskID = empty($run->task) ? 0 : $run->task;
            $this->loadModel('action')->create('case', $caseID, 'run', '', $taskID);
            if($caseResult == 'fail')
            {
                $link = $this->createLink('testtask', 'results',"runID=$runID&caseID=$caseID&version=$version");

                $response['result']   = 'success';
                $response['message']  = $this->lang->saveSuccess;
                $response['callback'] = "loadModal('$link', 'runCaseModal')";
                return $this->send($response);
            }
            else
            {
                /* set cookie for ajax load caselist when close colorbox. */
                helper::setcookie('selfClose', 1, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

                if($preAndNext->next and $this->app->tab != 'my')
                {
                    $nextRunID   = $runID ? $preAndNext->next->id : 0;
                    $nextCaseID  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
                    $nextVersion = $preAndNext->next->version;
                    $link        = inlink('runCase', "runID={$nextRunID}&caseID={$nextCaseID}&version={$nextVersion}");

                    $response['result']   = 'success';
                    $response['message']  = $this->lang->saveSuccess;
                    $response['callback'] = "loadModal('$link', 'runCaseModal')";
                    return $this->send($response);
                }
                else
                {
                    $response['result']     = 'success';
                    $response['load']       = true;
                    $response['closeModal'] = true;
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

        $this->view->title    = $this->lang->testtask->lblRunCase;
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

            return $this->send(array('result' => 'success', 'load' => $url));
        }

        if(!$this->post->caseIdList) return print(js::locate($url, 'parent'));

        $caseIdList = array_unique($this->post->caseIdList);

        /* The case of tasks of qa. */
        if($productID or ($this->app->tab == 'project' and empty($productID)))
        {
            $this->testtaskZen->setMenu($productID, 0, $this->session->project, $this->session->execution);

            $cases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($caseIdList)->fetchAll('id');
        }
        /* The case of my. */
        else
        {
            if($this->app->tab == 'project')
            {
                $this->loadModel('project')->setMenu($this->session->project);
                $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                    ->where('t2.id')->in($caseIdList)
                    ->fetchAll('id');
            }
            else
            {
                $this->lang->testtask->menu = $this->lang->my->menu->work;
                $this->lang->my->menu->work['subModule'] = 'testtask';

                $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                    ->where('t1.id')->in($caseIdList)
                    ->fetchAll('id');
            }

            $this->view->title = $this->lang->testtask->batchRun;
        }

        /* Set modules. */
        $moduleOptionMenu = array(0 => '/');
        foreach($cases as $caseID => $case)
        {
            if($case->auto == 'auto' and $confirm == 'yes') unset($cases[$caseID]);
            $moduleOptionMenu += $this->tree->getModulesName((array)$case->module);
        }
        if(empty($cases)) return print(js::locate($url));

        $this->view->moduleOptionMenu = $moduleOptionMenu;

        /* If case has changed and not confirmed, remove it. */
        if($from == 'testtask')
        {
            $runs = $this->dao->select('`case`, version')->from(TABLE_TESTRUN)
                ->where('`case`')->in($caseIdList)
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
            ->where('t2.id')->in($caseIdList)
            ->andWhere('t1.version=t2.version')
            ->andWhere('t2.status')->ne('wait')
            ->fetchGroup('case', 'id');

        $this->view->caseIdList = array_keys($cases);
        $this->view->productID  = $productID;
        $this->view->title      = $this->lang->testtask->batchRun;
        $this->view->from       = $from;
        $this->view->confirm    = $confirm;
        $this->display();
    }

    /**
     * 查看测试用例的执行结果。
     * View test results of a test run.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $status  all|done
     * @param  string $type    all|fail
     * @access public
     * @return void
     */
    public function results(int $runID, int $caseID = 0, int $version = 0, string $status = 'done', string $type = 'all')
    {
        /* Set project menu. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        /* Set case and results. */
        if($runID)
        {
            $this->app->loadLang('testcase');

            /* If runID is not empty, set testtask. */
            $run     = $this->testtask->getRunById($runID);
            $case    = $run->case;
            $results = $this->testtask->getResults($runID, 0, $status, $type);

            $this->view->testtask = $this->testtask->fetchByID($run->task);
        }
        else
        {
            $case    = $this->loadModel('testcase')->getByID($caseID, $version);
            $results = $this->testtask->getResults(0, $caseID, $status, $type);
        }


        /* Assign. */
        $this->view->case      = $case;
        $this->view->runID     = $runID;
        $this->view->results   = $results;
        $this->view->type      = $type;
        $this->view->builds    = $this->loadModel('build')->getBuildPairs($case->product, $case->branch);
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed, noletter');
        $this->view->testtasks = $this->testtask->getPairs($case->product);

        $this->display();
    }

    /**
     * 批量指派测试单中的用例。
     * Batch assign cases in a testtask.
     *
     * @param  int    $taskID
     * @param  string $account
     * @access public
     * @return void
     */
    public function batchAssign(int $taskID, string $account)
    {
        $this->testtask->batchAssign($taskID, $account, $this->post->caseIdList);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);
        if($this->app->tab == 'project')
        {
            $this->lang->scrum->menu->qa['subMenu']->testcase['subModule'] = 'testtask';
            $this->lang->scrum->menu->qa['subMenu']->testtask['subModule'] = '';
            $this->loadModel('project')->setMenu($this->session->project);

            /* Replace language. */
            $project = $this->project->getByID($this->session->project);
            if(!empty($project->model) and $project->model == 'waterfall')
            {
                $this->lang->testtask->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->testtask->execution);
            }
        }
        else
        {
            $this->loadModel('qa')->setMenu($productID);
            $this->app->rawModule = 'testcase';
        }

        $this->app->loadLang('job');
        $this->app->rawModule = 'testcase';

        $projectID  = $this->app->tab == 'qa' ? 0 : $this->session->project;
        $executions = empty($productID) ? array() : $this->loadModel('product')->getExecutionPairsByProduct($productID, '', 'id_desc', $projectID);
        $builds     = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs($productID, 'all', 'notrunk', 0, 'execution', '', false);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->importUnitResult;
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
    public function ajaxGetUserTestTasks(int $userID = 0, string $id = '', string $status = 'all')
    {
        $account = $this->app->user->account;
        if($userID)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;
        }

        $testTasks = $this->testtask->getUserTestTaskPairs($account, 0, $status);

        $items = array();
        foreach($testTasks as $taskID =>  $taskName) $items[] = array('text' => $taskName, 'value' => $taskID);

        $fieldName = $id ? "testtasks[$id]" : 'testtask';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * 获取一个产品下的测试单的键值对。
     * Get testtask key-value pairs of a product by ajax.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $appendTaskID
     * @access public
     * @return void
     */
    public function ajaxGetTestTasks(int $productID, int $executionID = 0, int $appendTaskID = 0)
    {
        $tasks = array();
        $pairs = $this->testtask->getPairs($productID, $executionID, $appendTaskID);
        foreach($pairs as $taskID => $taskName) $tasks[] = array('text' => $taskName, 'value' => $taskID, 'keys' => $taskName);

        return $this->send(array('result' => 'success', 'tasks' => $tasks));
    }

    /**
     * 获取一个产品下的测试报告键值对。
     * Get test report key-value pairs of a product by ajax.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetTestReports(int $productID)
    {
        $reports = array();
        $pairs   = $this->loadModel('testreport')->getPairs($productID);
        foreach($pairs as $id => $title) $reports[] = array('text' => $title, 'value' => $id);

        return $this->send(array('result' => 'success', 'reports' => $reports));
    }

    /**
     * 获取一个产品下的测试单的下拉列表。
     * Get drop menu of testtasks of a product by ajax.
     *
     * // TODO: 暂时不做大的调整，重构 1.5 级导航时可能需要此方法提供数据。
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $taskID
     * @param  string $module
     * @param  string $method
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $productID, string $branch, int $taskID, string $module, string $method, string $objectType = '', int $objectID = 0)
    {
        $scope     = empty($objectType) ? 'local' : 'all';
        $testtasks = $this->testtask->getProductTasks($productID, $branch, "$scope,totalStatus", '', '', 'id_desc', null);

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
     * 根据 id 获取用例的一次运行结果。
     * Get a run result of a case by ajax.
     *
     * @param  int    $resultID
     * @access public
     * @return void
     */
    public function ajaxGetResult(int $resultID)
    {
        $result = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('id')->eq($resultID)->fetch();
        return $this->send(array('result' => 'success', 'data' => $result));
    }
}
