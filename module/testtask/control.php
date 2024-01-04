<?php
declare(strict_types=1);
/**
 * The control file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: control.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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

            /* 如果没有获取到产品键值对、并且即不是弹窗页面，并且是 zin 或者 fetch 请求，那么跳转到错误提示页面。*/
            /* If the product key-value pair is not obtained and it is not a pop-up page, and it is a zin request or a fetch request, then jump to the error page. */
            if(empty($products) && !isInModal() && (helper::isAjaxRequest('zin') || helper::isAjaxRequest('fetch')))
            {
                $tab      = ($this->app->tab == 'project' || $this->app->tab == 'execution') ? $this->app->tab : 'qa';
                $objectID = ($tab == 'project' || $tab == 'execution') ? $this->session->$tab : 0;
                $this->locate($this->createLink('product', 'showErrorNone', "moduleName={$tab}&activeMenu=testtask&objectID=$objectID"));
            }

            $this->products       = $products;
            $this->view->products = $products;
        }
    }

    /**
     * 测试单的列表。
     * Browse testtasks.
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
    public function browse(int $productID = 0, string $branch = '0', string $type = 'local,totalStatus', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $beginTime = '', string $endTime = '')
    {
        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);

        /* 保存部分内容到 session 中供后面使用。*/
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('reportList',   $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');

        /* 预处理部分变量供查询使用。*/
        /* Prepare variables for query. */
        $this->app->loadClass('pager', $static = true);
        $pager     = pager::init($recTotal, $recPerPage, $pageID);
        $beginTime = $beginTime ? date('Y-m-d', strtotime($beginTime)) : '';
        $endTime   = $endTime   ? date('Y-m-d', strtotime($endTime))   : '';
        $sort      = common::appendOrder($orderBy);
        $product   = $this->product->getById($productID);

        /* 处理分支。 */
        /* Process branch. */
        $branch = $this->testtaskZen->getBrowseBranch($branch, $product->type);

        /* 设置1.5级菜单。  */
        /* Set 1.5 level menu. */
        $this->loadModel('qa')->setMenu($productID, $branch);

        /* 从数据库中查询符合条件的测试单。*/
        /* Query the testtasks from the database. */
        $testtasks = $this->testtask->getProductTasks($productID, $branch, $type, $beginTime, $endTime, $sort, $pager);

        /* Process testtask members. */
        $users = $this->loadModel('user')->getPairs('noclosed|noletter');
        foreach($testtasks as $testtask)
        {
            if(empty($testtask->members)) continue;

            $members = array();
            foreach(explode(',', $testtask->members) as $member) $members[] = zget($users, $member);
            $testtask->members = implode(',', array_unique($members));
        }

        $this->testtaskZen->prepareSummaryForBrowse($testtasks);

        $this->view->title     = $product->name . $this->lang->colon . $this->lang->testtask->common;
        $this->view->users     = $users;
        $this->view->tasks     = $testtasks;
        $this->view->product   = $product;
        $this->view->branch    = $branch;
        $this->view->type      = $type;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->beginTime = $beginTime;
        $this->view->endTime   = $endTime;
        $this->display();
    }

    /**
     * 单元测试列表页面。
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
    public function browseUnits(int $productID = 0, string $browseType = 'newest', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);

        /* 保存部分内容到 session 中供后面使用。*/
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('caseList',     $this->app->getURI(true), $this->app->tab);
        $this->session->set('buildList',    $this->app->getURI(true) . '#app=' . $this->app->tab, 'execution');

        $this->loadModel('qa')->setMenu($productID);

        /* 确保二级导航高亮的是测试用例。*/
        /* Make sure the secondary navigation highlights test cases. */
        $this->lang->qa->menu->testcase['subModule'] .= ',testtask';

        /* 预处理部分变量供查询使用。*/
        /* Prepare variables for query. */
        if($browseType == 'newest') $recPerPage = '10';
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->tasks      = $this->testtask->getProductUnitTasks($productID, $browseType, $sort, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->product    = $this->product->getByID($productID);
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 创建测试单页面和创建表单的提交。
     * Create a testtask.
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
        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);

        if(!empty($_POST))
        {
            /* 生成表单数据。*/
            /* Generate form data. */
            $formData = form::data($this->config->testtask->form->create)->add('project', $projectID)->get();
            $formData = $this->loadModel('file')->processImgURL($formData, $this->config->testtask->editor->create['id'], $this->post->uid);
            if($formData->execution)
            {
                $execution = $this->loadModel('execution')->fetchByID($formData->execution);
                $formData->project = $execution->project;
            }

            if($formData->build && empty($formData->execution))
            {
                $build = $this->loadModel('build')->getById((int)$this->post->build);
                $formData->project = $build->project;
            }

            $formData->members = trim($formData->members, ',');

            /* 创建测试单。*/
            /* Create a testtask. */
            $testtaskID = $this->testtask->create($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 处理上传的文件。*/
            /* Process uploaded files. */
            $this->loadModel('file')->updateObjectID($this->post->uid, $testtaskID, 'testtask');
            $this->file->saveUpload('testtask', $testtaskID);

            /* 执行工作流的扩展动作并返回提示消息。*/
            /* Execute the extended action of the workflow and return a prompt message. */
            $message = $this->executeHooks($testtaskID) ?: $this->lang->saveSuccess;

            /* 根据不同的应用生成不同的跳转链接。*/
            /* Create different links according to different applications. */
            $testtask = $this->testtask->fetchByID($testtaskID);
            if($this->app->tab == 'project')   $link = $this->createLink('project', 'testtask', "projectID={$testtask->project}");
            if($this->app->tab == 'execution') $link = $this->createLink('execution', 'testtask', "executionID={$testtask->execution}");
            if($this->app->tab == 'qa')        $link = $this->createLink('testtask', 'browse', "productID={$this->post->product}");
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $link, 'id' => $testtaskID));
        }

        $this->testtaskZen->setMenu($productID, 0, $projectID, $executionID);
        $this->testtaskZen->assignForCreate($productID, $projectID, $executionID, $build);

        $this->display();
    }

    /**
     * 查看当前测试单的详细信息。
     * View detail of a testtask.
     *
     * @param  int    $testtaskID
     * @access public
     * @return void
     */
    public function view(int $testtaskID)
    {
        /* 查询测试单详细信息。 */
        /* Query detail of a testtask. */
        $testtask = $this->testtask->getByID($testtaskID, true);
        if(!$testtask) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        /* session 改变时重新查询关联的产品。*/
        /* When the session changes, query the related products again. */
        $products = $this->products;
        if($this->session->project != $testtask->project) $products = $this->loadModel('product')->getProductPairsByProject($testtask->project);
        $this->session->project = $testtask->project;

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
        $productID = $testtask->product;
        if(!isset($products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $products[$productID] = $product->name;
        }

        $this->testtaskZen->setMenu($testtask->product, $testtask->branch, $testtask->project, $testtask->execution);
        $this->testtaskZen->setDropMenu($productID, $testtask);

        /* 执行工作流配置的扩展动作。*/
        /* Execute extended actions configured in the workflow. */
        $this->executeHooks($testtaskID);

        if($testtask->execution) $this->view->execution = $this->loadModel('project')->getByID($testtask->execution);

        $this->view->title      = "TASK #$testtask->id $testtask->name/" . $products[$productID];
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('testtask', $testtaskID);
        $this->view->testreport = $this->loadModel('testreport')->getById($testtask->testreport);
        $this->view->buildName  = $testtask->build == 'trunk' ? $this->lang->trunk : $testtask->buildName;
        $this->view->task       = $testtask;
        $this->view->productID  = $productID;
        $this->display();
    }

    /**
     * 查看单元测试测试单的用例列表。
     * Browse cases of a testtask which is unit test.
     *
     * @param  int    $testtaskID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function unitCases(int $testtaskID, string $orderBy = 't1.id_asc')
    {
        $testtask = $this->testtask->getByID($testtaskID);

        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);

        $this->loadModel('qa')->setMenu($productID);
        $this->lang->qa->menu->testcase['subModule'] .= ',testtask';

        /* 根据测试套件获取测试用例执行结果。*/
        /* Get testrun of test cases by suite. */
        $suiteRuns = $this->testtask->getRunsForUnitCases($testtaskID, "t4.suite_asc,$orderBy");

        /* 保存部分内容到 session 中供后面使用。*/
        /* Save session .*/
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        /* 因为套件和测试单是多对多关系，所以要过滤掉相同ID的测试单执行数据。 */
        /* Filter out the testrun data with the same ID because the suite and testtask have a many-to-many relationship. */
        $runs = array();
        foreach($suiteRuns as $run) $runs[$run->id] = $run;

        $runs = $this->loadModel('testcase')->appendData($runs, 'testrun');
        $runs = $this->testtaskZen->processRowspanForUnitCases($runs);

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->runs      = $runs;
        $this->view->productID = $productID;
        $this->view->taskID    = $testtaskID;
        $this->display();
    }

    /**
     * 查看非单元测试的测试单的用例列表。
     * Browse cases of a testtask which isn't unit test.
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

        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($testtask->product, $this->products);

        $this->testtaskZen->setMenu($productID, (string)$testtask->branch, $testtask->project, $testtask->execution);

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
        $product = $this->loadModel('product')->getByID($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        /* 预处理部分变量供查询使用。*/
        /* Prepare variables for query. */
        $this->app->loadClass('pager', $static = true);
        $pager    = pager::init($recTotal, $recPerPage, $pageID);
        $queryID  = ($browseType == 'bysearch' or $browseType == 'bysuite') ? $param : 0;
        $moduleID = ($browseType == 'bymodule') ? $param : ($browseType == 'bysearch' ? 0 : ($this->cookie->taskCaseModule ?: 0));
        $sort     = common::appendOrder($orderBy, 't2.id');
        $sort     = str_replace('case_', 'id_', $sort);

        /* 从数据库中查询一个测试单下关联的测试用例。*/
        /* Query the cases associated with a testtask from the database. */
        $runs = $this->testtask->getTaskCases($productID, $browseType, $queryID, $moduleID, $sort, $pager, $testtask);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $this->testtaskZen->setSearchParamsForCases($product, $moduleID, $taskID, $queryID);
        $this->testtaskZen->assignForCases($product, $testtask, $runs, $moduleID, $browseType, $param, $orderBy, $pager);
        $this->display();
    }

    /**
     * 查看一个测试单的报表。
     * The report page of a testtask.
     *
     * @param  int    $productID
     * @param  int    $taskID
     * @param  string $browseType
     * @param  int    $branchID
     * @param  int    $moduleID
     * @param  string $chartType
     * @access public
     * @return void
     */
    public function report(int $productID, int $taskID, string $browseType, int $branchID, int $moduleID = 0, string $chartType = 'pie')
    {
        $this->loadModel('report');
        $charts = $datas = array();
        if(!empty($_POST))
        {
            $this->app->loadLang('testcase');
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->testtask->$chartFunc($taskID);
                $chartOption = $this->config->testtask->report->options;
                if(!empty($chartType))
                {
                    $chartOption->type           = $chartType;
                    $chartOption->graph->caption = $this->lang->testtask->report->charts[$chart];
                }

                $charts[$chart] = $chartOption;
                $datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $task = $this->testtask->getByID($taskID);
        $this->testtaskZen->setMenu($productID, $branchID, $task->project, $task->execution);
        $this->testtaskZen->setDropMenu($productID, $task);

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common . $this->lang->colon . $this->lang->testtask->reportChart;
        $this->view->productID  = $productID;
        $this->view->taskID     = $taskID;
        $this->view->browseType = $browseType;
        $this->view->moduleID   = $moduleID;
        $this->view->branchID   = $branchID;
        $this->view->chartType  = $chartType;
        $this->view->charts     = $charts;
        $this->view->datas      = $datas;
        $this->display();
    }

    /**
     * 分组浏览一个测试单关联的用例。
     * Browse the cases associated with a testtask in groups.
     *
     * @param  int    $taskID
     * @param  string $groupBy
     * @access public
     * @return void
     */
    public function groupCase(int $taskID, string $groupBy = 'story')
    {
        /* 检查测试单是否存在。*/
        /* Check if the testtask exists. */
        $task = $this->testtask->getByID($taskID);
        if(!$task) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('qa', 'index'))));

        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($task->product, $this->products);
        $this->testtaskZen->setMenu($productID, $task->branch, $task->project, $task->execution);
        $this->testtaskZen->setDropMenu($productID, $task);

        /* 保存部分内容到 session 和 cookie 中供后面使用。*/
        /* Save session and cookie. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        helper::setcookie('taskCaseModule', 0, 0);

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
        if(!isset($this->products[$productID]))
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        /* 从数据库中查询一个测试单下关联的测试用例。*/
        /* Query the cases associated with a testtask from the database. */
        $groupBy = $groupBy ?: 'story';
        $cases   = $this->testtask->getRuns($taskID, array(0), $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $cases = $this->loadModel('testcase')->appendData($cases, 'run');

        /* 处理测试用例的跨行合并属性供前端组件分组使用。*/
        /* Process the rowspan property of cases for use by front-end component groupings. */
        $cases = $this->testtaskZen->processRowspanForGroupCase($cases, $task->build);

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->canBeChanged = common::canBeChanged('testtask', $task);
        $this->view->productID    = $productID;
        $this->view->cases        = $cases;
        $this->view->task         = $task;
        $this->view->groupBy      = $groupBy;
        $this->display();
    }

    /**
     * 编辑一个测试单。
     * Edit a testtask.
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
        $task = $this->testtask->getByID($taskID);

        /* 检查是否有权限访问测试单所属产品。*/
        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($task->product, $this->products);

        /* 如果测试单所属产品在产品键值对中不存在，将其加入。*/
        /* Prepare the product key-value pairs. */
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

        $this->view->title    = $testtask->name . $this->lang->colon . $this->lang->close;
        $this->view->actions  = $this->loadModel('action')->getList('testtask', $taskID);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|nodeleted|qdfirst');
        $this->view->testtask = $testtask;
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
     * 关联测试用例到一个测试单。
     * Link cases to a testtask.
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
    public function linkCase(int $taskID, string $type = 'all', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(!empty($_POST))
        {
            $runs = form::batchData($this->config->testtask->form->linkCase)->get();
            $this->testtask->linkCase($taskID, $type, $runs);
            if(dao::isError()) return $this->send(array('result' => 'success', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('cases', "taskID={$taskID}")));
        }
        /* Get testtask info. */
        $task = $this->testtask->getByID($taskID);
        if(!$task) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->testtask->checkLinked, 'locate' => array('back' => true))));

        /* Check if user have permission to access the product to which the testtask belongs. */
        $productID = $this->loadModel('product')->checkAccess($task->product, $this->products);

        /* Prepare the product key-value pairs. */
        $product = $this->product->getByID($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true), 'qa');

        $this->testtaskZen->setMenu($productID, $task->branch, $task->project, $task->execution);
        $this->testtaskZen->setDropMenu($productID, $task);
        $this->testtaskZen->setSearchParamsForLinkCase($product, $task, $type, $param);

        /* 从数据库中查询一个测试单下可以关联的测试用例。*/
        /* Query the cases that can be associated with a testtask from the database. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $cases = $this->testtask->getLinkableCases($productID, $task, $type, $param, $pager);

        $this->view->title        = $task->name . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->suites       = $this->loadModel('testsuite')->getSuites($task->product);
        $this->view->relatedTasks = $this->testtask->getRelatedTestTasks($productID, $taskID);
        $this->view->cases        = $cases;
        $this->view->task         = $task;
        $this->view->type         = $type;
        $this->view->param        = $param;
        $this->view->pager        = $pager;
        $this->view->product      = $product;
        $this->view->branch       = $task->branch;
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
     * 执行一个用例。
     * Run a case.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function runCase(int $runID, int $caseID = 0, int $version = 0, string $confirm = '')
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

        $caseID = $caseID ?: $run->case->id;

        $this->testtaskZen->checkAndExecuteAutomatedTest($run, $runID, $caseID, $version, $confirm);

        $preAndNext = $this->loadModel('common')->getPreAndNextObject('testcase', $caseID);
        if(!empty($_POST))
        {
            $stepResults = form::batchData($this->config->testtask->form->runCase)->get();
            $caseResult  = $this->testtask->createResult($runID, (int)$this->post->case, (int)$this->post->version, $stepResults);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('case', $caseID, 'run', '', zget($run, 'task', 0));

            $this->testtaskZen->responseAfterRunCase($caseResult, $preAndNext, $runID, $caseID, $version);
        }

        $this->testtaskZen->assignForRunCase($run, $preAndNext, $runID, $caseID, $version, $confirm);
        $this->display();
    }

    /**
     * 批量执行用例。
     * Batch run cases.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $from
     * @param  int    $taskID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function batchRun(int $productID, string $orderBy = 'id_desc', string $from = 'testcase', int $taskID = 0, string $confirm = '')
    {
        $url = $this->session->caseList ?: inlink('browse', "productID=$productID");

        if($this->post->results)
        {
            $cases = form::batchData($this->config->testtask->form->batchRun)->get();
            $this->testtask->batchRun($cases, $from, $taskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $url));
        }

        if(!$this->post->caseIdList) $this->locate($url);

        /* 根据不同情况获取要批量执行的用例。*/
        /* Get cases to run according to different situations. */
        $caseIdList = array_unique($this->post->caseIdList);
        $cases      = $this->testtaskZen->prepareCasesForBatchRun($productID, $orderBy, $from, $taskID, $confirm, $caseIdList);
        if(empty($cases)) return $this->send(array('result' => 'fail', 'load' => $url));

        /* 获取用例所属模块的键值对。*/
        /* Get key-value pairs of case module. */
        $this->loadModel('tree');
        $modules = array('/');
        foreach($cases as $case) $modules += $this->tree->getModulesName((array)$case->module);

        $this->view->title     = $this->lang->testtask->batchRun;
        $this->view->steps     = $this->loadModel('testcase')->getStepGroupByIdList($caseIdList);
        $this->view->modules   = $modules;
        $this->view->cases     = $cases;
        $this->view->productID = $productID;
        $this->view->from      = $from;
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
        $this->view->builds    = $this->loadModel('build')->getBuildPairs(array($case->product), $case->branch);
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
     * 导入单元测试结果。
     * Import unit results.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function importUnitResult(int $productID)
    {
        /* 检查是否有权限访问要导入单元测试结果的产品。*/
        /* Check if user have permission to access the product into which unit test results are to be imported. */
        $productID = $this->loadModel('product')->checkAccess($productID, $this->products);

        if($_POST)
        {
            $task   = $this->testtaskZen->buildTaskForImportUnitResult($productID);
            $taskID = $this->testtask->importUnitResult($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('unitCases', "taskID=$taskID")));
        }

        $this->app->loadLang('job');
        $this->loadModel('qa')->setMenu($productID);

        /* 确保二级导航高亮的是测试用例。*/
        /* Make sure the secondary navigation highlights test cases. */
        $this->lang->qa->menu->testcase['subModule'] .= ',testtask';

        $executions = empty($productID) ? array() : $this->product->getExecutionPairsByProduct($productID);
        $builds     = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'notrunk', 0, 'execution', '', false);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->importUnitResult;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->executions = $executions;
        $this->view->builds     = $builds;
        $this->view->productID  = $productID;
        $this->display();
    }

    /**
     * 获取用户负责的测试单的键值对。在创建或编辑测试单类型的待办时使用。
     * Get the key-value pair of the testtask that the user is responsible for. Used when creating or editing a testtask type backlog.
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

        $params = $method == 'report' ? "productID={$productID}&taskID=%s&browseType=all&branch={$branch}" : "taskID=%s";

        $this->view->currentTaskID   = $taskID;
        $this->view->testtasks       = $testtasks;
        $this->view->module          = $module;
        $this->view->method          = $method;
        $this->view->productID       = $productID;
        $this->view->branch          = $branch;
        $this->view->objectType      = $objectType;
        $this->view->objectID        = $objectID;
        $this->view->testtasksPinyin = common::convert2Pinyin($namePairs);
        $this->view->link            = $this->createLink($module, $method, $params);

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

    /**
    * AJAX: Get executionID by buildID.
     *
     * @param  int    $buildID
     * @access public
     * @return int
     */
    public function ajaxGetExecutionByBuild(int $buildID)
    {
        $execution = $this->loadModel('execution')->getByBuild($buildID);
        return print($execution ? $execution->id : 0);
    }
}
