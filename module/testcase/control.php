<?php
/**
 * The control file of case currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: control.php 5112 2013-07-12 02:51:33Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testcase extends control
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
     * Construct function, load product, tree, user auto.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('qa');

        /* Get product data. */
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($this->app->tab == 'project')
            {
                $objectID = $this->session->project;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            elseif($this->app->tab == 'execution')
            {
                $objectID = $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                $mode     = ($this->app->methodName == 'create' and empty($this->config->CRProduct)) ? 'noclosed' : '';
                $products = $this->product->getPairs($mode, 0, '', 'all');
            }
            if(empty($products) and !helper::isAjaxRequest()) return print($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testcase&objectID=$objectID")));
        }
        else
        {
            $products = $this->product->getPairs('', 0, '', 'all');
        }
        $this->view->products = $this->products = $products;
    }

    /**
     * Browse cases.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $browseType
     * @param  int        $param
     * @param  string     $caseType
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @param  int        $projectID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $browseType = 'all', $param = 0, $caseType = '', $orderBy = 'sort_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0)
    {
        $this->loadModel('datatable');
        $this->app->loadLang('zanode');

        /* Set browse type. */
        $browseType = strtolower($browseType);

        /* Set browseType, productID, moduleID and queryID. */
        $productID = $this->app->tab != 'project' ? $this->product->saveState($productID, $this->products) : $productID;
        $branch    = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch)
        {
            $_COOKIE['caseModule'] = 0;
            setcookie('caseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }
        if($browseType == 'bymodule') setcookie('caseModule', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        if($browseType == 'bysuite')  setcookie('caseSuite', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
        if($browseType != 'bymodule') $this->session->set('caseBrowseType', $browseType);

        $moduleID = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->caseModule ? $this->cookie->caseModule : 0));
        $suiteID  = ($browseType == 'bysuite') ? (int)$param : ($browseType == 'bymodule' ? ($this->cookie->caseSuite ? $this->cookie->caseSuite : 0) : 0);
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu, save session. */
        if($this->app->tab == 'project')
        {
            $linkedProducts = $this->product->getProducts($projectID, 'all', '', false);
            $this->products = count($linkedProducts) > 1 ? array('0' => $this->lang->product->all) + $linkedProducts : $linkedProducts;
            $productID      = count($linkedProducts) > 1 ? $productID : key($linkedProducts);
            $hasProduct     = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('hasProduct');
            if(!$hasProduct) unset($this->config->testcase->search['fields']['product']);

            $branch = intval($branch) > 0 ? $branch : 'all';
            $this->loadModel('project')->setMenu($projectID);
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch, $browseType);
        }

        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, $this->app->tab);
        $this->session->set('productID', $productID);
        $this->session->set('moduleID', $moduleID);
        $this->session->set('browseType', $browseType);
        $this->session->set('orderBy', $orderBy);
        $this->session->set('testcaseOrderBy', '`sort` asc', $this->app->tab);
        $this->session->set('testcaseOrderBy', '`sort` asc');

        /* Load lang. */
        $this->app->loadLang('testtask');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);

        $cases  = array();
        $pager->pageID = $pageID;   // 场景和用例混排，$pageID 可能大于场景分页后的总页数。在 pager 构造函数中会被设为 1，这里要重新赋值。

        $scenes = $this->testcase->getSceneGroups($productID, $branch, $moduleID, $caseType, $sort, $pager);   // 获取包含子场景和用例的顶级场景树。

        if(!$this->cookie->onlyScene)
        {
            $recPerPage = $pager->recPerPage;
            $sceneTotal = $pager->recTotal;
            $sceneCount = count($scenes);

            /* 场景条数小于每页记录数，继续获取用例。 */
            if($sceneCount < $recPerPage)
            {
                /* 重置 $pager 属性，只获取需要的用例条数。*/
                $pager->recTotal   = 0;
                $pager->pageID     = 1; // 查询用例时的分页起始偏移量单独计算，每次查询的页码都设为 1 即可，后面会重新设置页码。
                $pager->recPerPage = $recPerPage - $sceneCount; // 可能存在场景没排满一页，需要用例补全的情况。这里只查询需要补全的记录数。

                if($sceneCount == 0) $pager->offset = $recPerPage * ($pageID - 1) - $sceneTotal;   // 场景数为 0 表示本页查询只显示用例，需要计算用例分页的起始偏移量。

                $cases = $this->testcase->getTestCases($productID, $branch, $browseType, $browseType == 'bysearch' ? $queryID : $suiteID, $moduleID, $caseType, $sort, $pager);
                $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
            }

            /* 合并场景和用例的总记录数，并重新计算总页数和当前页码。*/
            $pager->recTotal  += $sceneTotal;
            $pager->recPerPage = $recPerPage;
            $pager->pageTotal  = ceil($pager->recTotal / $recPerPage);
            $pager->pageID     = $pageID;
        }

        $sceneCount = count($scenes);
        $caseCount  = 0;
        /* Process case for check story changed. */
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->testcase->appendData($cases);
        foreach($cases as $case)
        {
            $case->id      = 'case_' . $case->id;   // Add a prefix to avoid duplication with the scene ID.
            $case->parent  = 0;
            $case->grade   = 1;
            $case->path    = ',' . $case->id . ',';
            $case->isScene = false;
            if(!$case->scene) $caseCount++;
        }

        if($this->cookie->onlyScene)
        {
            $summary = sprintf($this->lang->testcase->summaryScene, $sceneCount);
        }
        else
        {
            $summary = sprintf($this->lang->testcase->summary, $sceneCount, $caseCount);
        }

        /* Build the search form. */
        $currentModule = $this->app->tab == 'project' ? 'project'  : 'testcase';
        $currentMethod = $this->app->tab == 'project' ? 'testcase' : 'browse';
        $projectParam  = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
        $actionURL = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['onMenuBar'] = 'yes';

        $searchProducts = $this->product->getPairs('', 0, '', 'all');
        $this->testcase->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $projectID, 0, $branch);

        $showModule = !empty($this->config->datatable->testcaseBrowse->showModule) ? $this->config->datatable->testcaseBrowse->showModule : '';

        /* Get module tree.*/
        if($projectID and empty($productID))
        {
            $moduleTree = $this->tree->getCaseTreeMenu($projectID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $projectID, 'productID' => $productID), $branch);
        }

        $product = $this->product->getById($productID);

        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product and $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, $projectID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        $sceneCases = array_merge($scenes, $cases);
        if($browseType == 'bysearch')
        {
            $sceneCases = $this->testcase->processSearchedData($scenes, $cases);
        }

        /* Assign. */
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';
        $this->view->title           = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testcase->common;
        $this->view->projectID       = $projectID;
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->productName     = $this->products[$productID];
        $this->view->modules         = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch == 'all' ? '0' : $branch);
        $this->view->scenes          = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0,  0);
        $this->view->moduleTree      = $moduleTree;
        $this->view->moduleName      = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->moduleID        = $moduleID;
        $this->view->projectType     = !empty($projectID) ? $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('model') : '';
        $this->view->summary         = $summary;
        $this->view->pager           = $pager;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->orderBy         = $orderBy;
        $this->view->browseType      = $browseType;
        $this->view->param           = $param;
        $this->view->caseType        = $caseType;
        $this->view->cases           = $sceneCases;
        $this->view->branch          = (!empty($product) and $product->type != 'normal') ? $branch : 0;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->suiteList       = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->suiteID         = $suiteID;
        $this->view->setModule       = true;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : array();
        $this->view->showBranch      = $showBranch;
        $this->view->libraries       = $this->loadModel('caselib')->getLibraries();
        $this->view->automation      = $this->loadModel('zanode')->getAutomationByProduct($productID);

        $this->display();
    }

    /**
     * Group case.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $groupBy
     * @param  int    $projectID
     * @param  string $caseType
     * @access public
     * @return void
     */
    public function groupCase($productID = 0, $branch = '', $groupBy = 'story', $projectID = 0, $caseType = '')
    {
        $groupBy   = empty($groupBy) ? 'story' : $groupBy;
        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getByID($productID);
        if($branch === '') $branch = $this->cookie->preBranch;

        $this->app->loadLang('testtask');

        $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);
        if($this->app->tab == 'project')
        {
            $products = array('0' => $this->lang->product->all) + $this->product->getProducts($this->session->project, 'all', '', false);
            if(!$product->shadow) $this->lang->modulePageNav = $this->product->select($products, $productID, 'testcase', 'groupCase', $projectID, $branch);
        }

        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);

        $cases = $this->testcase->getModuleCases($productID, $branch, 0, '', 'no', $caseType, $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->testcase->appendData($cases);

        $groupCases  = array();
        $groupByList = array();
        foreach($cases as $case)
        {
            if($groupBy == 'story')
            {
                if($case->storyDeleted) continue;
                $groupCases[$case->story][] = $case;
                $groupByList[$case->story]  = $case->storyTitle;
            }
        }

        $this->app->loadLang('execution');
        $this->app->loadLang('task');

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]  = html::a($this->createLink('testcase', 'groupTask', "productID=$productID&groupBy=$groupBy"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testcase->common;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->groupByList = $groupByList;
        $this->view->cases       = $groupCases;
        $this->view->suiteList   = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->suiteID     = 0;
        $this->view->moduleID    = 0;
        $this->view->branch      = $branch;
        $this->view->caseType    = $caseType;
        $this->view->product     = $product;
        $this->display();
    }

    /**
     * Show zero case story.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $orderBy
     * @param  int    $projectID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function zeroCase($productID = 0, $branchID = 0, $orderBy = 'id_desc', $projectID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $orderBy = empty($orderBy) ? 'id_desc' : $orderBy;
        $this->session->set('storyList', $this->app->getURI(true) . '#app=' . $this->app->tab, 'product');
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);

        $this->loadModel('story');
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $products  = $this->product->getProducts($this->session->project, 'all', '', false);
            $productID = $this->product->saveState($productID, $products);
            $product   = $this->product->getByID($productID);
            if(!$product->shadow) $this->lang->modulePageNav = $this->product->select($products, $productID, 'testcase', 'zeroCase', $projectID, $branchID);
        }
        else
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->saveState($productID, $products);
            $product   = $this->product->getByID($productID);
            $this->loadModel('qa');
            $this->app->rawModule = 'testcase';
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->qa->setMenu($products, $productID, $branchID);
        }

        /* Append id for secend sort. */
        $sort    = common::appendOrder($orderBy);
        $stories = $this->story->getZeroCase($productID, $branchID, $sort);

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal = count($stories);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $stories  = array_chunk($stories, $pager->recPerPage);

        $this->view->title      = $this->lang->story->zeroCase;

        $this->view->stories    = empty($stories) ? $stories : $stories[$pageID - 1];
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projectID  = $projectID;
        $this->view->productID  = $productID;
        $this->view->branchID   = $branchID;
        $this->view->orderBy    = $orderBy;
        $this->view->suiteList  = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->browseType = '';
        $this->view->product    = $product;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create a test case.
     * @param        $productID
     * @param string $branch
     * @param int    $moduleID
     * @param string $from
     * @param int    $param
     * @param int    $storyID
     * @param string $extras
     * @access public
     * @return void
     */
    public function create($productID, $branch = '', $moduleID = 0, $from = '', $param = 0, $storyID = 0, $extras = '')
    {
        $testcaseID  = ($from and strpos('testcase|work|contribute', $from) !== false) ? $param : 0;
        $bugID       = $from == 'bug' ? $param : 0;
        $executionID = $from == 'execution' ? $param : 0;

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);

        $this->loadModel('story');
        if(!empty($_POST))
        {
            if(!empty($_FILES['scriptFile'])) unset($_FILES['scriptFile']);
            $response['result'] = 'success';

            setcookie('lastCaseModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            setcookie('lastCaseScene', (int)$this->post->scene, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $caseResult = $this->testcase->create($bugID);
            if(!$caseResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $caseID = $caseResult['id'];
            if($caseResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->testcase->common);
                $response['locate']  = $this->createLink('testcase', 'view', "caseID=$caseID");
                return $this->send($response);
            }

            $this->loadModel('action');
            $this->action->create('case', $caseID, 'Opened');
            if($this->testcase->getStatus('create') == 'wait') $this->action->create('case', $caseID, 'submitReview');

            /* If the story is linked project, make the case link the project. */
            $this->testcase->syncCase2Project($caseResult['caseInfo'], $caseID);

            $message = $this->executeHooks($caseID);
            if($message) $this->lang->saveSuccess = $message;
            $response['message'] = $this->lang->saveSuccess;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $caseID));
            /* If link from no head then reload. */
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));

            setcookie('caseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

            /* Use this session link, when the tab is not QA, a session of the case list exists, and the session is not from the Dynamic page. */
            $useSession         = ($this->app->tab != 'qa' and $this->session->caseList and strpos($this->session->caseList, 'dynamic') === false);
            $locateLink         = $this->app->tab == 'project' ? $this->createLink('project', 'testcase', "projectID={$this->session->project}") : $this->createLink('testcase', 'browse', "productID={$this->post->product}&branch={$this->post->branch}");
            $response['locate'] = $useSession ? $this->session->caseList : $locateLink;
            return $this->send($response);
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Init vars. */
        $type         = 'feature';
        $stage        = '';
        $pri          = 3;
        $scene        = 0;
        $caseTitle    = '';
        $precondition = '';
        $keywords     = '';
        $steps        = array();
        $color        = '';
        $auto         = '';

        /* If testcaseID large than 0, use this testcase as template. */
        if($testcaseID > 0)
        {
            $testcase     = $this->testcase->getById($testcaseID);
            $productID    = $testcase->product;
            $type         = $testcase->type ? $testcase->type : 'feature';
            $stage        = $testcase->stage;
            $pri          = $testcase->pri;
            $scene        = $testcase->scene;
            $storyID      = $testcase->story;
            $caseTitle    = $testcase->title;
            $precondition = $testcase->precondition;
            $keywords     = $testcase->keywords;
            $steps        = $testcase->steps;
            $color        = $testcase->color;
            $auto         = $testcase->auto;
        }

        /* If bugID large than 0, use this bug as template. */
        if($bugID > 0)
        {
            $bug       = $this->loadModel('bug')->getById($bugID);
            $type      = $bug->type;
            $pri       = $bug->pri ? $bug->pri : $bug->severity;
            $storyID   = $bug->story;
            $caseTitle = $bug->title;
            $keywords  = $bug->keywords;
            $steps     = $this->testcase->createStepsFromBug($bug->steps);
        }

        /* Set productID and branch. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch;

        /* Set menu. */
        if($this->app->tab == 'project' or $this->app->tab == 'execution') $this->loadModel('execution');
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        elseif($this->app->tab == 'execution')
        {
            $this->execution->setMenu($this->session->execution);
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch);
        }

        /* Set branch. */
        $product = $this->product->getById($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;
        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $this->session->project : $executionID;
            $productBranches = (isset($product->type) and $product->type != 'normal') ? $this->execution->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = (isset($product->type) and $product->type != 'normal') ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        /* Padding the steps to the default steps count. */
        if(count($steps) < $this->config->testcase->defaultSteps)
        {
            $paddingCount = $this->config->testcase->defaultSteps - count($steps);
            $step = new stdclass();
            $step->type   = 'item';
            $step->desc   = '';
            $step->expect = '';
            for($i = 1; $i <= $paddingCount; $i ++) $steps[] = $step;
        }

        $title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $position[] = $this->lang->testcase->common;
        $position[] = $this->lang->testcase->create;

        /* Set story and currentModuleID. */
        if($storyID)
        {
            $story = $this->loadModel('story')->getByID($storyID);
            if(empty($moduleID)) $moduleID = $story->module;
        }

        $currentModuleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastCaseModule;
        $currentSceneID  = (int)$this->cookie->lastCaseScene;
        if($testcaseID > 0) $currentSceneID = $scene;
        /* Get the status of stories are not closed. */
        $modules = array();
        if($currentModuleID)
        {
            $productModules = $this->tree->getOptionMenu($productID, 'story');
            $storyModuleID  = array_key_exists($currentModuleID, $productModules) ? $currentModuleID : 0;
            $modules        = $this->loadModel('tree')->getStoryModule($storyModuleID);
            $modules        = $this->tree->getAllChildID($modules);
        }

        $storyStatus = $this->story->getStatusList('active');
        $stories     = $this->story->getProductStoryPairs($productID, $branch, $modules, $storyStatus, 'id_desc', 0, 'full', 'story', false);
        if($this->app->tab != 'qa' and $this->app->tab != 'product')
        {
            $projectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $stories   = $this->story->getExecutionStoryPairs($projectID, $productID, $branch, $modules);
        }
        /* Logic of task 44139. */
        if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories))
        {
            $stories = $this->story->getProductStoryPairs($productID, $branch, 0, $storyStatus, 'id_desc', 0, 'full', 'story', false);
        }

        if($storyID and !isset($stories[$storyID])) $stories = $this->story->formatStories(array($storyID => $story)) + $stories;//Fix bug #2406.
        $productInfo = $this->loadModel('product')->getById($productID);

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->createFields;

        $this->view->title            = $title;
        $this->view->position         = $position;
        $this->view->projectID        = isset($projectID) ? $projectID : 0;
        $this->view->productID        = $productID;
        $this->view->executionID      = $executionID;
        $this->view->productInfo      = $productInfo;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->currentSceneID   = $currentSceneID;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
        $this->view->stories          = $stories;
        $this->view->caseTitle        = $caseTitle;
        $this->view->color            = $color;
        $this->view->type             = $type;
        $this->view->stage            = $stage;
        $this->view->pri              = $pri;
        $this->view->storyID          = $storyID;
        $this->view->precondition     = $precondition;
        $this->view->keywords         = $keywords;
        $this->view->steps            = $steps;
        $this->view->hiddenProduct    = !empty($product->shadow);
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->branch           = $branch;
        $this->view->product          = $product;
        $this->view->branches         = $branches;
        $this->view->auto             = $auto;
        $this->view->sceneOptionMenu  = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

        $this->display();
    }


    /**
     * Create a batch test case.
     *
     * @param  int   $productID
     * @param  int   $moduleID
     * @param  int   $storyID
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = '', $moduleID = 0, $storyID = 0)
    {
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $caseIDList = $this->testcase->batchCreate($productID, $branch, $storyID);
            if(dao::isError()) return print(js::error(dao::getError()));
            if(isonlybody())
            {
                $execution = $this->loadModel('execution')->getByID($this->session->execution);
                if($this->app->tab == 'execution' and $execution->type == 'kanban')
                {
                    return print(js::closeModal('parent.parent', ''));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this'));
                }
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $caseIDList));

            setcookie('caseModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $currentModule = $this->app->tab == 'project' ? 'project'  : 'testcase';
            $currentMethod = $this->app->tab == 'project' ? 'testcase' : 'browse';
            $projectParam  = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
            return print(js::locate($this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=all&param=0&caseType=&orderBy=id_desc"), 'parent'));
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Set productID and currentModuleID. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch;
        if($storyID and empty($moduleID))
        {
            $story    = $this->loadModel('story')->getByID($storyID);
            $moduleID = $story->module;
        }
        $currentModuleID = (int)$moduleID;

        /* Set menu. */
        $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);

        /* Set story list. */
        $story       = $storyID ? $this->story->getByID($storyID) : '';
        $storyPairs  = $this->loadModel('story')->getProductStoryPairs($productID, $branch === 'all' ? 0 : $branch);
        $storyPairs += $storyID ? array($storyID => $story->id . ':' . $story->title) : array('');

        /* Set custom. */
        $product = $this->product->getById($productID);
        foreach(explode(',', $this->config->testcase->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->testcase->$field;
        }

        if($product->type != 'normal')
        {
            $this->config->testcase->custom->batchCreateFields = sprintf($this->config->testcase->custom->batchCreateFields, $product->type);
        }
        else
        {
            $this->config->testcase->custom->batchCreateFields = trim(sprintf($this->config->testcase->custom->batchCreateFields, ''), ',');
        }

        $showFields = $this->config->testcase->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        if($this->app->tab == 'project' and $product->type != 'normal')
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

            $productBranches = $this->loadModel('execution')->getBranchByProduct($productID, $this->session->project, 'noclosed|withMain');
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairs($productID, 'active');
        }

        /* Set module option menu. */
        $moduleOptionMenu          = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' ? 0 : $branch);
        $moduleOptionMenu['ditto'] = $this->lang->testcase->ditto;

        $sceneOptionMenu = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        $sceneOptionMenu['ditto'] = $this->lang->testcase->ditto;

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->batchCreate;
        $this->view->position[]       = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[]       = $this->lang->testcase->common;
        $this->view->position[]       = $this->lang->testcase->batchCreate;
        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->story            = $story;
        $this->view->storyPairs       = $storyPairs;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->sceneOptionMenu  = $sceneOptionMenu;
        $this->view->currentSceneID   = 0;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;
        $this->view->needReview       = $this->testcase->forceNotReview() == true ? 0 : 1;

        $this->display();
    }

    /**
     * Create bug.
     *
     * @param  int    $productID
     * @param  string $extras
     * @access public
     * @return void
     */
    public function createBug($productID, $branch = 0, $extras = '')
    {
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $params);
        extract($params);

        $this->loadModel('testtask');
        $case = '';
        if($runID)
        {
            $case    = $this->testtask->getRunById($runID)->case;
            $results = $this->testtask->getResults($runID);
        }
        elseif($caseID)
        {
            $case    = $this->testcase->getById($caseID);
            $results = $this->testtask->getResults(0, $caseID);
        }

        if(!$case) return print(js::error($this->lang->notFound) . js::locate('back', 'parent'));

        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        $this->view->title   = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->createBug;
        $this->view->runID   = $runID;
        $this->view->case    = $case;
        $this->view->caseID  = $caseID;
        $this->view->version = $version;
        $this->display();
    }

    /**
     * View a test case.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $from
     * @access public
     * @return void
     */
    public function view($caseID, $version = 0, $from = 'testcase', $taskID = 0)
    {
        $this->session->set('bugList', $this->app->getURI(true), $this->app->tab);

        $caseID = (int)$caseID;
        $case   = $this->testcase->getById($caseID, $version);

        if(!$case)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));
        }

        $case = $this->loadModel('story')->checkNeedConfirm($case);

        if($from == 'testtask')
        {
            $run = $this->loadModel('testtask')->getRunByCase($taskID, $caseID);
            $case->assignedTo    = $run->assignedTo;
            $case->lastRunner    = $run->lastRunner;
            $case->lastRunDate   = $run->lastRunDate;
            $case->lastRunResult = $run->lastRunResult;
            $case->caseStatus    = $case->status;
            $case->status        = $run->status;

            $results = $this->testtask->getResults($run->id);
            $result  = array_shift($results);
            if($result)
            {
                $case->xml      = $result->xml;
                $case->duration = $result->duration;
            }
        }

        $isLibCase = ($case->lib and empty($case->product));
        if($isLibCase)
        {
            $libraries = $this->loadModel('caselib')->getLibraries();
            $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->caselib->setLibMenu($libraries, $case->lib);

            $this->view->title      = "CASE #$case->id $case->title - " . $libraries[$case->lib];
            $this->view->position[] = html::a($this->createLink('caselib', 'browse', "libID=$case->lib"), $libraries[$case->lib]);

            $this->view->libName = $libraries[$case->lib];
        }
        else
        {
            $productID = $case->product;
            $product   = $this->product->getByID($productID);
            $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);

            if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($this->session->project);
            if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($this->session->execution);
            if($this->app->tab == 'qa')        $this->testcase->setMenu($this->products, $productID, $case->branch);

            $this->view->title      = "CASE #$case->id $case->title - " . $product->name;

            $this->view->product     = $product;
            $this->view->branches    = $branches;
            $this->view->productName = $product->name;
            $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $case->branch, '');
        }

        $caseFails = $this->dao->select('COUNT(*) AS count')->from(TABLE_TESTRESULT)
            ->where('caseResult')->eq('fail')
            ->andwhere('`case`')->eq($caseID)
            ->beginIF($from == 'testtask')->andwhere('`run`')->eq($taskID)->fi()
            ->fetch('count');
        $case->caseFails = $caseFails;

        $this->executeHooks($caseID);

        $this->view->position[] = $this->lang->testcase->common;
        $this->view->position[] = $this->lang->testcase->view;

        $this->view->case       = $case;
        $this->view->from       = $from;
        $this->view->taskID     = $taskID;
        $this->view->version    = $version ? $version : $case->version;
        $this->view->modulePath = $this->tree->getParents($case->module);
        $this->view->caseModule = empty($case->module) ? '' : $this->tree->getById($case->module);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('case', $caseID);
        $this->view->preAndNext = !isOnlybody() ? $this->loadModel('common')->getPreAndNextObject('testcase', $caseID) : '';
        $this->view->runID      = $from == 'testcase' ? 0 : $run->id;
        $this->view->isLibCase  = $isLibCase;
        $this->view->caseFails  = $caseFails;

        if(defined('RUN_MODE') and RUN_MODE == 'api' and !empty($this->app->version)) return $this->send(array('status' => 'success', 'case' => $case));
        $this->display();
    }

    /**
     * Edit a case.
     *
     * @param  int   $caseID
     * @param  bool  $comment
     * @param  int   $executionID
     * @access public
     * @return void
     */
    public function edit($caseID, $comment = false, $executionID = 0)
    {
        $this->loadModel('story');

        $case = $this->testcase->getById($caseID);
        if(!$case) return print(js::error($this->lang->notFound) . js::locate('back'));

        $testtasks = $this->loadModel('testtask')->getGroupByCases($caseID);
        $testtasks = empty($testtasks[$caseID]) ? array() : $testtasks[$caseID];

        if(!empty($_POST))
        {
            if(!empty($_FILES['scriptFile'])) unset($_FILES['scriptFile']);

            $changes = array();
            if($comment == false or $comment == 'false')
            {
                $changes = $this->testcase->update($caseID, $testtasks);
                if(dao::isError()) return print(js::error(dao::getError()));
            }
            if($this->post->comment != '' or !empty($changes))
            {
                $this->loadModel('action');
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('case', $caseID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                if($case->status != 'wait' and $this->post->status == 'wait') $this->action->create('case', $caseID, 'submitReview');
            }

            $this->executeHooks($caseID);

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $caseID));
            }
            else
            {
                return print(js::locate($this->createLink('testcase', 'view', "caseID=$caseID"), 'parent'));
            }
        }

        if(empty($case->steps))
        {
            $step = new stdclass();
            $step->type   = 'step';
            $step->desc   = '';
            $step->expect = '';
            $case->steps[] = $step;
        }

        $isLibCase = ($case->lib and empty($case->product));
        if($isLibCase)
        {
            $productID = isset($this->session->product) ? $this->session->product : 0;
            $libraries = $this->loadModel('caselib')->getLibraries();
            $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->caselib->setLibMenu($libraries, $case->lib);

            $title      = "CASE #$case->id $case->title - " . $libraries[$case->lib];
            $position[] = html::a($this->createLink('caselib', 'browse', "libID=$case->lib"), $libraries[$case->lib]);

            $this->view->libID     = $case->lib;
            $this->view->libName   = $libraries[$case->lib];
            $this->view->libraries = $libraries;
            $this->view->moduleOptionMenu = $this->tree->getOptionMenu($case->lib, $viewType = 'caselib', $startModuleID = 0);
        }
        else
        {
            $productID  = $case->product;
            $product    = $this->product->getById($productID);
            if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

            $title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->edit;
            $position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);

            /* Set menu. */
            if($this->app->tab == 'project' or $this->app->tab == 'execution')
            {
                $this->loadModel('execution');
                if($this->app->tab == 'project') $this->loadModel('project')->setMenu($case->project);
                if($this->app->tab == 'execution')
                {
                    if(!$executionID) $executionID = $case->execution;
                    $this->execution->setMenu($executionID);
                }
            }
            if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $case->branch);

            $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $case->branch);
            if($case->lib and $case->fromCaseID)
            {
                $libName    = $this->loadModel('caselib')->getById($case->lib)->name;
                $libModules = $this->tree->getOptionMenu($case->lib, 'caselib');
                foreach($libModules as $moduleID => $moduleName)
                {
                    if($moduleID == 0) continue;
                    $moduleOptionMenu[$moduleID] = $libName . $moduleName;
                }
            }

            if(!isset($moduleOptionMenu[$case->module])) $moduleOptionMenu += $this->tree->getModulesName($case->module);

            /* Get product and branches. */
            if($this->app->tab == 'execution' or $this->app->tab == 'project')
            {
                $objectID = $this->app->tab == 'project' ? $case->project : $executionID;
            }

            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, isset($objectID) ? $objectID : 0, 'all');
            $branchTagOption = array();
            foreach($branches as $branchInfo)
            {
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
            if(!isset($branchTagOption[$case->branch]))
            {
                $caseBranch = $this->branch->getById($case->branch, $case->product, '');
                $branchTagOption[$case->branch] = $case->branch == BRANCH_MAIN ? $caseBranch : ($caseBranch->name . ($caseBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
            }

            $moduleIdList = $case->module;
            if($case->module) $moduleIdList = $this->tree->getAllChildID($case->module);

            $storyStatus = $this->story->getStatusList('noclosed');
            if($this->app->tab == 'execution')
            {
                $stories = $this->story->getExecutionStoryPairs($case->execution, $productID, $case->branch, $moduleIdList);
            }
            else
            {
                $stories = $this->story->getProductStoryPairs($productID, $case->branch, $moduleIdList, $storyStatus,'id_desc', 0, 'full', 'story', false);
            }
            /* Logic of task 44139. */
            if(!in_array($this->app->tab, array('execution', 'project')) and empty($stories))
            {
                $stories = $this->story->getProductStoryPairs($case->product, $case->branch, 0, $storyStatus, 'id_desc', 0, 'full', 'story', false);
            }

            $this->view->productID        = $productID;
            $this->view->product          = $product;
            $this->view->products         = $this->products;
            $this->view->branchTagOption  = $branchTagOption;
            $this->view->productName      = $this->products[$productID];
            $this->view->moduleOptionMenu = $moduleOptionMenu;
            $this->view->stories          = $stories;
        }

        $sceneOptionMenu = $this->testcase->getSceneMenu($productID, $case->module, $viewType = 'case', $startSceneID = 0,  0 );
        if(!isset($sceneOptionMenu[$case->scene])) $sceneOptionMenu += $this->testcase->getScenesName($case->scene);

        $forceNotReview = $this->testcase->forceNotReview();
        if($forceNotReview) unset($this->lang->testcase->statusList['wait']);

        $this->view->title           = $title;
        $this->view->currentModuleID = $case->module;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->case            = $case;
        $this->view->actions         = $this->loadModel('action')->getList('case', $caseID);
        $this->view->isLibCase       = $isLibCase;
        $this->view->forceNotReview  = $forceNotReview;
        $this->view->testtasks       = $testtasks;
        $this->view->sceneOptionMenu = $sceneOptionMenu;
        $this->view->currentSceneID  = $case->scene;

        $this->display();
    }

    /**
     * Batch edit case.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  string $tab
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $branch = 0, $type = 'case', $tab = '')
    {
        if(!$this->post->caseIDList) return print(js::locate($this->session->caseList));

        $caseIDList = array_filter($this->post->caseIDList);
        $testtasks  = $this->loadModel('testtask')->getGroupByCases($caseIDList);
        if($this->post->title)
        {
            $allChanges = $this->testcase->batchUpdate($testtasks);
            if($allChanges)
            {
                foreach($allChanges as $caseID => $changes )
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }

            return print(js::locate($this->session->caseList, 'parent'));
        }

        $branchProduct = false;

        if($this->app->tab == 'project')               $this->loadModel('project')->setMenu($this->session->project);
        if($this->app->tab == 'qa' and $type != 'lib') $this->testcase->setMenu($this->products, $productID, $branch);
        if($this->app->tab == 'execution')             $this->loadModel('execution')->setMenu($this->session->execution);

        /* The cases of a product. */
        if($productID)
        {
            /* Get the edited cases. */
            $cases = $this->testcase->getByList($caseIDList);

            if($type == 'lib')
            {
                $libID     = $productID;
                $libraries = $this->loadModel('caselib')->getLibraries();

                /* Remove story custom fields from caselib */
                $this->config->testcase->customBatchEditFields   = str_replace(',story', '', $this->config->testcase->customBatchEditFields);
                $this->config->testcase->custom->batchEditFields = str_replace(',story', '', $this->config->testcase->custom->batchEditFields);

                /* Set caselib menu. */
                $this->caselib->setLibMenu($libraries, $libID);

                /* Set modules. */
                $modules[$productID][$branch] = $this->tree->getOptionMenu($libID, 'caselib', 0, $branch);

                $this->view->title      = $libraries[$libID] . $this->lang->colon . $this->lang->testcase->batchEdit;
                $this->view->position[] = html::a($this->createLink('caselib', 'browse', "libID=$libID"), $libraries[$libID]);
            }
            else
            {
                $product = $this->product->getByID($productID);

                if($product->type != 'normal') $branchProduct = true;

                /* Set branches and modules. */
                $branches        = array();
                $branchTagOption = array();
                $modules         = array();
                if($product->type != 'normal')
                {
                    $branches = $this->loadModel('branch')->getList($productID, 0, 'all');
                    foreach($branches as $branchInfo)
                    {
                        $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                    }
                    if($this->app->tab == 'project')
                    {
                        $branchTagOption = $this->loadModel('branch')->getPairsByProjectProduct($this->session->project, $productID);
                    }
                    foreach($branchTagOption as $branchID => $branchName) $modules[$productID][$branchID] = $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
                }
                else
                {
                    $modules[$productID][BRANCH_MAIN] = $this->tree->getOptionMenu($productID, 'case');
                }

                $this->view->branchTagOption = array($productID => $branchTagOption);
                $this->view->title           = $product->name . $this->lang->colon . $this->lang->testcase->batchEdit;
                $this->view->product         = $product;
            }
        }
        else
        {
            /* Get the edited cases. */
            $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.id')->in($caseIDList)
                ->fetchAll('id');
            $caseIDList = array_keys($cases);

            /* The cases of my. */
            $this->app->loadLang('my');
            $this->lang->testcase->menu = $this->lang->my->menu->work;
            $this->lang->my->menu->work['subModule'] = 'testcase';

            $this->view->position[] = html::a($this->server->http_referer, $this->lang->my->myTestCase);
            $this->view->title      = $this->lang->testcase->batchEdit;

            $productIdList = array();
            foreach($cases as $case) $productIdList[$case->product] = $case->product;

            $branchTagOption = array();
            $products        = $this->product->getByIdList($productIdList);
            foreach($products as $product)
            {
                $branches = 0;
                if($product->type != 'normal')
                {
                    $branches = $this->loadModel('branch')->getList($product->id, 0, 'all');
                    foreach($branches as $branchInfo) $branchTagOption[$product->id][$branchInfo->id] = '/' . $product->name . '/' . $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                    $branches      = array_keys($branches);
                    $branchProduct = true;
                }

                $modulePairs = $this->tree->getOptionMenu($product->id, 'case', 0, $branches);
                $modules[$product->id] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);
            }

            $this->view->products        = $products;
            $this->view->branchTagOption = $branchTagOption;
        }

        /* Judge whether the editedCases is too large and set session. */
        $countInputVars  = count($cases) * (count(explode(',', $this->config->testcase->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $stories = $this->loadModel('story')->getProductStoryPairs($productID, $branch);
        $this->view->stories = array('' => '', 'ditto' => $this->lang->testcase->ditto) + $stories;

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customBatchEditFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->batchEditFields;

        /* Append module when change product type. */
        $modulePairs = array(0 => '/');
        foreach($cases as $case)
        {
            $caseProduct = $type == 'lib' ? $productID : $case->product;
            if(isset($modules[$caseProduct][$case->branch]))
            {
                $modulePairs[$case->id] = $modules[$caseProduct][$case->branch];
            }
            else
            {
                $modulePairs[$case->id] = $modules[$caseProduct][0] + $this->tree->getModulesName($case->module);
            }
        }

        $scenePairs = array(0 => '/');
        foreach($cases as $case)
        {
            $scenePairs[$case->id] = $this->testcase->getSceneMenu($productID, $case->module, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);;
        }

        /* Assign. */
        $this->view->scenePairs     = $scenePairs;
        $this->view->position[]     = $this->lang->testcase->common;
        $this->view->position[]     = $this->lang->testcase->batchEdit;
        $this->view->caseIDList     = $caseIDList;
        $this->view->productID      = $productID;
        $this->view->branchProduct  = $branchProduct;
        $this->view->priList        = array('ditto' => $this->lang->testcase->ditto) + $this->lang->testcase->priList;
        $this->view->typeList       = array('' => '', 'ditto' => $this->lang->testcase->ditto) + $this->lang->testcase->typeList;
        $this->view->cases          = $cases;
        $this->view->forceNotReview = $this->testcase->forceNotReview();
        $this->view->modulePairs    = $modulePairs;
        $this->view->testtasks      = $testtasks;
        $this->view->isLibCase      = $type == 'lib' ? true : false;

        $this->display();
    }

    /**
     * Review case.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function review($caseID)
    {
        if($_POST)
        {
            $changes = $this->testcase->review($caseID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if(is_array($changes))
            {
                $result = $this->post->result;
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Reviewed', $this->post->comment, ucfirst($result));
                $this->action->logHistory($actionID, $changes);

                $this->executeHooks($caseID);

                return print(js::reload('parent.parent'));
            }
        }

        $this->view->users   = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->case    = $this->testcase->getById($caseID);
        $this->view->actions = $this->loadModel('action')->getList('case', $caseID);
        $this->display();
    }

    /**
     * Batch review case.
     *
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchReview($result)
    {
        if($this->post->caseIDList)
        {
            $this->testcase->batchReview($this->post->caseIDList, $result);
            if(dao::isError()) return print(js::error(dao::getError()));
        }
        echo js::locate($this->session->caseList, 'parent');
    }

    /**
     * Delete a test case
     *
     * @param  int    $caseID
     * @param  string $confirm yes|noe
     * @access public
     * @return void
     */
    public function delete($caseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->testcase->confirmDelete, inlink('delete', "caseID=$caseID&confirm=yes")));
        }
        else
        {
            $case = $this->testcase->getById($caseID);
            $this->testcase->delete(TABLE_CASE, $caseID);

            $message = $this->executeHooks($caseID);
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

            $locateLink = $this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$case->product}");
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * Batch delete cases.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchDelete($productID = 0)
    {
        $this->post->caseIDList  = $this->post->caseIDList ? $this->post->caseIDList : array();
        $this->post->sceneIDList = $this->post->sceneIDList ? $this->post->sceneIDList : array();
        if($this->post->caseIDList || $this->post->sceneIDList)
        {
            $this->testcase->batchDelete($this->post->caseIDList, $this->post->sceneIDList);
            if(dao::isError()) return print(js::error(dao::getError()));
        }
        echo js::locate($this->session->caseList);
    }

    /**
     * Batch change branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch($branchID)
    {
        if($this->post->caseIDList || $this->post->sceneIDList)
        {
            $this->testcase->batchChangeBranch($this->post->caseIDList, $this->post->sceneIDList, $branchID);
            if(dao::isError()) return print(js::error(dao::getError()));
        }

        echo js::locate($this->session->caseList, 'parent');
    }

    /**
     * Batch change the module of case.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        if($this->post->caseIDList || $this->post->sceneIDList)
        {
            $this->testcase->batchChangeModule($this->post->caseIDList, $this->post->sceneIDList, $moduleID);
            if(dao::isError()) return print(js::error(dao::getError()));
        }

        echo js::locate($this->session->caseList, 'parent');
    }

    /**
     * Batch review case.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchCaseTypeChange($type)
    {
        if($this->post->caseIDList)
        {
            $this->testcase->batchCaseTypeChange($this->post->caseIDList, $type);
            if(dao::isError()) return print(js::error(dao::getError()));
        }
        echo js::locate($this->session->caseList, 'parent');
    }

    /**
     * Link related cases.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkCases($caseID, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Get case and queryID. */
        $case    = $this->testcase->getById($caseID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $case->product, $case->branch);

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'linkCases', "caseID=$caseID&browseType=bySearch&queryID=myQueryID", '', true);
        $objectID  = 0;
        if($this->app->tab == 'project') $objectID = $case->project;
        if($this->app->tab == 'execution') $objectID = $case->execution;

        unset($this->config->testcase->search['fields']['product']);
        $this->testcase->buildSearchForm($case->product, $this->products, $queryID, $actionURL, $objectID);

        /* Get cases to link. */
        $cases2Link = $this->testcase->getCases2Link($caseID, $browseType, $queryID);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal   = count($cases2Link);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $cases2Link = array_chunk($cases2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->title      = $case->title . $this->lang->colon . $this->lang->testcase->linkCases;
        $this->view->position[] = html::a($this->createLink('product', 'view', "productID=$case->product"), zget($this->products, $case->product, ''));
        $this->view->position[] = html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case->title);
        $this->view->position[] = $this->lang->testcase->linkCases;
        $this->view->case       = $case;
        $this->view->cases2Link = empty($cases2Link) ? $cases2Link : $cases2Link[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Link related bugs.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs($caseID, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('bug');

        /* Get case and queryID. */
        $case    = $this->testcase->getById($caseID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'linkBugs', "caseID=$caseID&browseType=bySearch&queryID=myQueryID", '', true);

        /* Unset search field 'plan' in single project. */
        unset($this->config->bug->search['fields']['product']);
        if($case->project and ($this->app->tab == 'project' or $this->app->tab == 'execution'))
        {
            $project = $this->loadModel('project')->getById($case->project);
            if(!$project->hasProduct and $project->model == 'waterfall') unset($this->config->bug->search['fields']['plan']);
        }

        $this->bug->buildSearchForm($case->product, $this->products, $queryID, $actionURL);

        /* Get cases to link. */
        $bugs2Link = $this->testcase->getBugs2Link($caseID, $browseType, $queryID);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal  = count($bugs2Link);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $bugs2Link = array_chunk($bugs2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->position[] = $this->lang->testcase->linkBugs;
        $this->view->case       = $case;
        $this->view->bugs2Link  = empty($bugs2Link) ? $bugs2Link : $bugs2Link[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Confirm testcase changed.
     *
     * @param  int    $caseID
     * @param  int    $taskID
     * @param  string $from
     * @access public
     * @return void
     */
    public function confirmChange($caseID, $taskID = 0, $from = 'view')
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_TESTRUN)->set('version')->eq($case->version)->where('`case`')->eq($caseID)->exec();
        if($from == 'view') return print(js::locate(inlink('view', "caseID=$caseID&version=$case->version&from=testtask&taskID=$taskID"), 'parent'));
        echo js::reload('parent');
    }

    /**
     * Confirm libcase changed.
     *
     * @param  int    $caseID
     * @param  int    $libcaseID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function confirmLibcaseChange($caseID, $libcaseID)
    {
        $case    = $this->testcase->getById($caseID);
        $libCase = $this->testcase->getById($libcaseID);
        $version = $case->version + 1;

        $this->dao->delete()->from(TABLE_FILE)->where('objectType')->eq('testcase')->andWhere('objectID')->eq($caseID)->exec();
        foreach($libCase->files as $fileID => $file)
        {
            $fileName = pathinfo($file->pathname, PATHINFO_FILENAME);
            $datePath = substr($file->pathname, 0, 6);
            $realPath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $fileName;

            $rand        = rand();
            $newFileName = $fileName . 'copy' . $rand;
            $newFilePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" .  $newFileName;
            copy($realPath, $newFilePath);

            $newFileName = $file->pathname;
            $newFileName = str_replace('.', "copy$rand.", $newFileName);

            unset($file->id, $file->realPath, $file->webPath);
            $file->objectID = $caseID;
            $file->pathname = $newFileName;
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
        }

        $this->dao->update(TABLE_CASE)
            ->set('version')->eq($version)
            ->set('fromCaseVersion')->eq($version)
            ->set('precondition')->eq($libCase->precondition)
            ->set('title')->eq($libCase->title)
            ->where('id')->eq($caseID)
            ->exec();

        foreach($libCase->steps as $step)
        {
            unset($step->id);
            $step->case    = $caseID;
            $step->version = $version;
            $this->dao->insert(TABLE_CASESTEP)->data($step)->exec();
        }

        echo js::locate($this->createLink('testcase', 'view', "caseID=$caseID&version=$version"), 'parent');
    }

    /**
     * Ignore libcase changed.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function ignoreLibcaseChange($caseID)
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_CASE)->set('fromCaseVersion')->eq($case->version)->where('id')->eq($caseID)->exec();
        echo js::reload('parent');
    }

    /**
     * Confirm story changes.
     *
     * @param  int    $caseID
     * @param  bool   $reload
     * @access public
     * @return void
     */
    public function confirmStoryChange($caseID, $reload = true)
    {
        $case = $this->testcase->getById($caseID);
        if($case->story)
        {
            $this->dao->update(TABLE_CASE)->set('storyVersion')->eq($case->latestStoryVersion)->where('id')->eq($caseID)->exec();
            $this->loadModel('action')->create('case', $caseID, 'confirmed', '', $case->latestStoryVersion);
        }
        if($reload) return print(js::reload('parent'));
    }

    /**
     * Batch ctory change cases.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchConfirmStoryChange($productID = 0)
    {
        if($this->post->caseIDList)
        {
            $caseIDList = array_filter($this->post->caseIDList);
            foreach($caseIDList as $caseID) $this->confirmStoryChange($caseID,false);
        }
        echo js::locate($this->session->caseList);
    }

    /**
     * export
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $taskID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $taskID = 0, $browseType = '')
    {
        if(strpos($orderBy, 'case') !== false)
        {
            list($field, $sort) = explode('_', $orderBy);
            $orderBy = '`' . $field . '`_' . $sort;
        }

        $product  = $this->loadModel('product')->getById($productID);
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if($product->type != 'normal')
        {
            $this->lang->testcase->branch = $this->lang->product->branchName[$product->type];
        }
        else
        {
            $this->config->testcase->exportFields = str_replace('branch,', '', $this->config->testcase->exportFields);
        }

        if($product->shadow) $this->config->testcase->exportFields = str_replace('product,', '', $this->config->testcase->exportFields);

        if($_POST)
        {
            $this->loadModel('file');
            $this->app->loadLang('testtask');
            $caseLang   = $this->lang->testcase;
            $caseConfig = $this->config->testcase;

            /* Create field lists. */
            $fields  = $this->post->exportFields ? $this->post->exportFields : explode(',', $caseConfig->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                if(!($product->type == 'normal' and $fieldName == 'branch'))
                {
                    $fields[$fieldName] = isset($caseLang->$fieldName) ? $caseLang->$fieldName : $fieldName;
                }
                unset($fields[$key]);
            }

            /* Get cases. */
            $queryCondition = preg_replace("/AND\s+t[0-9]\.scene\s+=\s+'0'/i", '', $this->session->testcaseQueryCondition);
            if($this->session->testcaseOnlyCondition)
            {
                $caseIDList = array();
                if($taskID) $caseIDList = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();

                $cases = $this->dao->select('*')->from(TABLE_CASE)->where($queryCondition)
                    ->beginIF($taskID)->andWhere('id')->in($caseIDList)->fi()
                    ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->post->checkedItem)->fi()
                    ->orderBy($orderBy)
                    ->beginIF($this->post->limit)->limit($this->post->limit)->fi()
                    ->fetchAll('id');
            }
            else
            {
                $cases   = array();
                $orderBy = " ORDER BY " . str_replace(array('|', '^A', '_'), ' ', $orderBy);
                $stmt    = $this->dao->query($queryCondition . $orderBy . ($this->post->limit ? ' LIMIT ' . $this->post->limit : ''));
                while($row = $stmt->fetch())
                {
                    $caseID = isset($row->case) ? $row->case : $row->id;
                    if($this->post->exportType == 'selected' and strpos(",{$this->post->checkedItem},", ",$caseID,") === false) continue;
                    $cases[$caseID] = $row;
                    $row->id        = $caseID;
                }
            }
            if($taskID) $caseLang->statusList = $this->lang->testcase->statusList;

            $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.run=t2.id')
                ->where('t1.`case`')->in(array_keys($cases))
                ->beginIF($taskID)->andWhere('t2.task')->eq($taskID)->fi()
                ->orderBy('id_desc')
                ->query();
            $results = array();
            while($result = $stmt->fetch())
            {
                if(!isset($results[$result->case])) $results[$result->case] = unserialize($result->stepResults);
            }

            /* Get users, products and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $branches = $this->loadModel('branch')->getPairs($productID);

            /* Get related objects id lists. */
            $relatedStoryIdList  = array();
            $relatedCaseIdList   = array();

            foreach($cases as $case)
            {
                $relatedStoryIdList[$case->story]   = $case->story;
                $relatedCaseIdList[$case->linkCase] = $case->linkCase;

                /* Process link cases. */
                $linkCases = explode(',', $case->linkCase);
                foreach($linkCases as $linkCaseID)
                {
                    if($linkCaseID) $relatedCaseIdList[$linkCaseID] = trim($linkCaseID);
                }
            }

            /* Get related objects title or names. */
            $relatedModules = $this->loadModel('tree')->getAllModulePairs('case');
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedCases   = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedSteps   = $this->dao->select('id,parent,`case`,version,type,`desc`,expect')->from(TABLE_CASESTEP)->where('`case`')->in(@array_keys($cases))->orderBy('version desc,id')->fetchGroup('case', 'id');
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('testcase')->andWhere('objectID')->in(@array_keys($cases))->andWhere('extra')->ne('editor')->fetchGroup('objectID');
            $relatedScenes  = $this->testcase->getSceneMenu($productID, 0);

            $cases = $this->testcase->appendData($cases);
            foreach($cases as $case)
            {
                $case->stepDesc   = '';
                $case->stepExpect = '';
                $case->real       = '';
                $result = isset($results[$case->id]) ? $results[$case->id] : array();

                $case->openedDate     = !helper::isZeroDate($case->openedDate)     ? $case->openedDate     : '';
                $case->lastEditedDate = !helper::isZeroDate($case->lastEditedDate) ? $case->lastEditedDate : '';
                $case->lastRunDate    = !helper::isZeroDate($case->lastRunDate)    ? $case->lastRunDate    : '';

                $case->real = '';
                if(!empty($result) and !isset($relatedSteps[$case->id]))
                {
                    $firstStep  = reset($result);
                    $case->real = $firstStep['real'];
                }

                if(isset($relatedSteps[$case->id]))
                {
                    $i = $childId = 0;
                    foreach($relatedSteps[$case->id] as $step)
                    {
                        $stepId = 0;
                        if($step->type == 'group' or $step->type == 'step')
                        {
                            $i++;
                            $childId = 0;
                            $stepId  = $i;
                        }
                        else
                        {
                            $stepId = $i . '.' . $childId;
                        }
                        if($step->version != $case->version) continue;
                        $sign = (in_array($this->post->fileType, array('html', 'xml'))) ? '<br />' : "\n";
                        $case->stepDesc   .= $stepId . ". " . htmlspecialchars_decode($step->desc) . $sign;
                        $case->stepExpect .= $stepId . ". " . htmlspecialchars_decode($step->expect) . $sign;
                        $case->real .= $stepId . ". " . (isset($result[$step->id]) ? $result[$step->id]['real'] : '') . $sign;
                        $childId ++;
                    }
                }
                $case->stepDesc     = trim($case->stepDesc);
                $case->stepExpect   = trim($case->stepExpect);
                $case->real         = trim($case->real);

                if($this->post->fileType == 'csv')
                {
                    $case->stepDesc   = str_replace('"', '""', $case->stepDesc);
                    $case->stepExpect = str_replace('"', '""', $case->stepExpect);
                }

                /* fill some field with useful value. */
                $case->product = !isset($products[$case->product])     ? '' : $products[$case->product] . "(#$case->product)";
                $case->branch  = !isset($branches[$case->branch])      ? '' : $branches[$case->branch] . "(#$case->branch)";
                $case->module  = !isset($relatedModules[$case->module])? '' : $relatedModules[$case->module] . "(#$case->module)";
                $case->story   = !isset($relatedStories[$case->story]) ? '' : $relatedStories[$case->story] . "(#$case->story)";
                $case->scene   = !isset($relatedScenes[$case->scene]) ? '' : $relatedScenes[$case->scene] . "(#$case->scene)";

                if(isset($caseLang->priList[$case->pri]))              $case->pri           = $caseLang->priList[$case->pri];
                if(isset($caseLang->typeList[$case->type]))            $case->type          = $caseLang->typeList[$case->type];
                if(isset($caseLang->statusList[$case->status]))        $case->status        = $this->processStatus('testcase', $case);
                if(isset($users[$case->openedBy]))                     $case->openedBy      = $users[$case->openedBy];
                if(isset($users[$case->lastEditedBy]))                 $case->lastEditedBy  = $users[$case->lastEditedBy];
                if(isset($users[$case->lastRunner]))                   $case->lastRunner    = $users[$case->lastRunner];
                if(isset($caseLang->resultList[$case->lastRunResult])) $case->lastRunResult = $caseLang->resultList[$case->lastRunResult];

                $case->bugsAB       = $case->bugs;       unset($case->bugs);
                $case->resultsAB    = $case->results;    unset($case->results);
                $case->stepNumberAB = $case->stepNumber; unset($case->stepNumber);
                unset($case->caseFails);

                $case->stage = explode(',', $case->stage);
                foreach($case->stage as $key => $stage) $case->stage[$key] = isset($caseLang->stageList[$stage]) ? $caseLang->stageList[$stage] : $stage;
                $case->stage = join("\n", $case->stage);

                $case->openedDate     = substr($case->openedDate, 0, 10);
                $case->lastEditedDate = substr($case->lastEditedDate, 0, 10);
                $case->lastRunDate    = helper::isZeroDate($case->lastRunDate) ? '' : $case->lastRunDate;

                if($case->linkCase)
                {
                    $tmpLinkCases = array();
                    $linkCaseIdList = explode(',', $case->linkCase);
                    foreach($linkCaseIdList as $linkCaseID)
                    {
                        $linkCaseID = trim($linkCaseID);
                        $tmpLinkCases[] = isset($relatedCases[$linkCaseID]) ? $relatedCases[$linkCaseID] . "(#$linkCaseID)" : $linkCaseID;
                    }
                    $case->linkCase = join("; \n", $tmpLinkCases);
                }

                /* Set related files. */
                $case->files = '';
                if(isset($relatedFiles[$case->id]))
                {
                    foreach($relatedFiles[$case->id] as $file)
                    {
                        $fileURL = common::getSysURL() . $this->createLink('file', 'download', "fileID={$file->id}");
                        $case->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                    }
                }
            }
            if($this->config->edition != 'open') list($fields, $cases) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $cases);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $cases);
            $this->post->set('kind', 'testcase');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName    = $this->lang->testcase->common;
        $browseType  = isset($this->lang->testcase->featureBar['browse'][$browseType]) ? $this->lang->testcase->featureBar['browse'][$browseType] : '';

        if($taskID) $taskName = $this->dao->findById($taskID)->from(TABLE_TESTTASK)->fetch('name');

        $this->view->fileName        = zget($products, $productID, '') . $this->lang->dash . ($taskID ? $taskName . $this->lang->dash : '') . $browseType . $fileName;
        $this->view->allExportFields = $this->config->testcase->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * Export template.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function exportTemplate($productID)
    {
        if($_POST)
        {
            $product = $this->loadModel('product')->getById($productID);

            if($product->type != 'normal') $fields['branch'] = $this->lang->product->branchName[$product->type];
            $fields['module']       = $this->lang->testcase->module;
            $fields['title']        = $this->lang->testcase->title;
            $fields['precondition'] = $this->lang->testcase->precondition;
            $fields['stepDesc']     = $this->lang->testcase->stepDesc;
            $fields['stepExpect']   = $this->lang->testcase->stepExpect;
            $fields['keywords']     = $this->lang->testcase->keywords;
            $fields['pri']          = $this->lang->testcase->pri;
            $fields['type']         = $this->lang->testcase->type;
            $fields['stage']        = $this->lang->testcase->stage;

            $fields[''] = '';
            $fields['typeValue']  = $this->lang->testcase->lblTypeValue;
            $fields['stageValue'] = $this->lang->testcase->lblStageValue;
            if($product->type != 'normal') $fields['branchValue'] = $this->lang->product->branchName[$product->type];

            $projectID = $this->app->tab == 'project' ? $this->session->project : 0;
            $branches  = $this->loadModel('branch')->getPairs($productID, '' , $projectID);
            $this->loadModel('tree');
            $modules = $product->type == 'normal' ? $this->tree->getOptionMenu($productID, 'case', 0, 0) : array();

            foreach($branches as $branchID => $branchName)
            {
                $branches[$branchID] = $branchName . "(#$branchID)";
                $modules += $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
            }

            $rows    = array();
            $num     = (int)$this->post->num;
            for($i = 0; $i < $num; $i++)
            {
                foreach($modules as $moduleID => $module)
                {
                    $row = new stdclass();
                    $row->module     = $module . "(#$moduleID)";
                    $row->stepDesc   = "1. \n2. \n3.";
                    $row->stepExpect = "1. \n2. \n3.";

                    if(empty($rows))
                    {
                        $row->typeValue   = join("\n", $this->lang->testcase->typeList);
                        $row->stageValue  = join("\n", $this->lang->testcase->stageList);
                        if($product->type != 'normal') $row->branchValue = join("\n", $branches);
                    }
                    $rows[] = $row;
                }
            }

            $this->post->set('fields', $fields);
            $this->post->set('kind', 'testcase');
            $this->post->set('rows', $rows);
            $this->post->set('extraNum', $num);
            $this->post->set('fileName', 'Template');
            $this->fetch('file', 'export2csv', $_POST);
        }

        $this->display();
    }

    /**
     * Import csv
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function import($productID, $branch = 0)
    {
        if($_FILES)
        {
            $file = $this->loadModel('file')->getUpload('file');
            $file = $file[0];

            $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
            move_uploaded_file($file['tmpname'], $fileName);

            $rows   = $this->file->parseCSV($fileName);
            $fields = $this->testcase->getImportFields($productID);
            $fields = array_flip($fields);
            $header = array();
            foreach($rows[0] as $i => $rowValue)
            {
                if(empty($rowValue)) break;
                $header[$i] = $rowValue;
            }
            unset($rows[0]);

            $columnKey = array();
            foreach($header as $title)
            {
                if(!isset($fields[$title])) continue;
                $columnKey[] = $fields[$title];
            }

            if(count($columnKey) <= 3 or $this->post->encode != 'utf-8')
            {
                $encode = $this->post->encode != "utf-8" ? $this->post->encode : 'gbk';
                $fc     = file_get_contents($fileName);
                $fc     = helper::convertEncoding($fc, $encode, 'utf-8');
                file_put_contents($fileName, $fc);

                $rows      = $this->file->parseCSV($fileName);
                $columnKey = array();
                $header    = array();
                foreach($rows[0] as $i => $rowValue)
                {
                    if(empty($rowValue)) break;
                    $header[$i] = $rowValue;
                }
                unset($rows[0]);
                foreach($header as $title)
                {
                    if(!isset($fields[$title])) continue;
                    $columnKey[] = $fields[$title];
                }
                if(count($columnKey) == 0) return print(js::alert($this->lang->testcase->errorEncode));
            }

            $this->session->set('fileImport', $fileName);

            return print(js::locate(inlink('showImport', "productID=$productID&branch=$branch"), 'parent.parent'));
        }

        $this->display();
    }

    /**
     * Import case from lib to cases or to caseLib.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $libID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  bool   $toLib
     * @access public
     * @return void
     */
    public function importFromLib($productID, $branch = 0, $libID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0, $toLib = false)
    {
        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;
        $product    = $productID ? $this->loadModel('product')->getById($productID) : 0;
        $branches   = array();
        if($branch == '') $branch = 0;

        $this->loadModel('branch');
        if(!$toLib && $product->type != 'normal') $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($productID, 'active', $projectID);

        $libList   = $this->loadModel('caselib')->getLibraries();
        $libraries = $libList;
        if($toLib)
        {
            $currentLib = $this->session->caseLib;
            $this->loadModel('caselib')->setLibMenu($libList, $currentLib);
            $libraries = array_diff($libList, array($currentLib => $libList[$currentLib]));
        }
        if(empty($libraries))
        {
            if($toLib)
            {
                $tips = $this->lang->testcase->noOtherLibrary;
            }
            else
            {
                $tips = $this->lang->testcase->noLibrary;
            }
            echo js::alert($tips);
            return print(js::locate($this->session->caseList));
        }
        if(empty($libID) or !isset($libraries[$libID])) $libID = key($libraries);

        if($_POST)
        {
            $this->testcase->importFromLib($productID, $libID, $branch, $toLib ? $currentLib : 0);
            return print(js::reload('parent'));
        }

        if(!$toLib) $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);

        /* Build the search form. */
        $actionURL = $this->createLink($toLib ? 'caselib' : 'testcase', 'importFromLib', "productID=$productID&branch=$branch&libID=$libID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['onMenuBar'] = 'no';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;
        $this->config->testcase->search['fields']['lib'] = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libList[$libID], 'all' => $this->lang->caselib->all));
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib');
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->loadModel('testsuite');
        foreach($branches as $branchID => $branchName) $canImportModules[$branchID] = $this->testsuite->getCanImportModules($productID, $libID, $branchID);
        if(empty($branches)) $canImportModules[0] = $this->testsuite->getCanImportModules($productID, $libID, 0, $toLib ? $currentLib : 0);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $recPerPage, $pageID);

        $this->view->title      = $this->lang->testcase->common . $this->lang->colon . $this->lang->testcase->importFromLib;
        $this->view->position[] = $this->lang->testcase->importFromLib;

        $this->view->libraries        = $libraries;
        $this->view->libID            = $libID;
        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->cases            = $this->testsuite->getCanImportCases($productID, $libID, $branch, $orderBy, $pager, $browseType, $queryID);
        $this->view->libModules       = $this->tree->getOptionMenu($libID, 'caselib');
        $this->view->pager            = $pager;
        $this->view->orderBy          = $orderBy;
        $this->view->branches         = $branches;
        $this->view->browseType       = $browseType;
        $this->view->queryID          = $queryID;
        $this->view->canImportModules = $canImportModules;
        $this->view->toLib            = $toLib;

        $this->display();
    }

    /**
     * Show import data
     *
     * @param  int        $productID
     * @param  int        $branch
     * @param  int        $pagerID
     * @param  int        $maxImport
     * @param  string|int $insert     0 is covered old, 1 is insert new.
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = 0, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        $file    = $this->session->fileImport;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if($_POST)
        {
            $this->testcase->createFromImport($productID, (int)$branch);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->isEndPage)
            {
                unlink($tmpFile);

                if($this->app->tab == 'project')
                {
                    return print(js::locate($this->createLink('project', 'testcase', "projectID={$this->session->project}&productID=$productID"), 'parent'));
                }
                else
                {
                    return print(js::locate(inlink('browse', "productID=$productID"), 'parent'));
                }
            }
            else
            {
                return print(js::locate(inlink('showImport', "productID=$productID&branch=$branch&pagerID=" . ($this->post->pagerID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', '')), 'parent'));
            }
        }

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        else
        {
            $this->testcase->setMenu($this->products, $productID, $branch);
        }

        $caseLang   = $this->lang->testcase;
        $caseConfig = $this->config->testcase;
        $branches   = $this->loadModel('branch')->getPairs($productID, 'active');
        $stories    = $this->loadModel('story')->getProductStoryPairs($productID, $branch);
        $fields     = $this->testcase->getImportFields($productID);
        $fields     = array_flip($fields);

        $branchModules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, empty($branches) ? array(0) : array_keys($branches));
        foreach($branchModules as $branchID => $moduleList)
        {
            foreach($moduleList as $moduleID => $moduleName) $modules[$branchID][$moduleID] = $moduleName;
        }

        if(!empty($maxImport) and file_exists($tmpFile))
        {
            $data = unserialize(file_get_contents($tmpFile));
            $caseData = $data['caseData'];
            $stepData = $data['stepData'];
        }
        else
        {
            $pagerID = 1;
            $rows    = $this->loadModel('file')->parseCSV($file);
            $header  = array();
            foreach($rows[0] as $i => $rowValue)
            {
                if(empty($rowValue)) break;
                $header[$i] = $rowValue;
            }
            unset($rows[0]);

            $endField = end($fields);
            $caseData = array();
            $stepData = array();
            $stepVars = 0;
            foreach($rows as $row => $data)
            {
                $case = new stdclass();
                foreach($header as $key => $title)
                {
                    if(!isset($fields[$title])) continue;
                    $field = $fields[$title];

                    if(!isset($data[$key])) continue;
                    $cellValue = $data[$key];
                    if($field == 'story' or $field == 'module' or $field == 'branch')
                    {
                        $case->$field = 0;
                        if(strrpos($cellValue, '(#') !== false)
                        {
                            $id = trim(substr($cellValue, strrpos($cellValue,'(#') + 2), ')');
                            $case->$field = $id;
                        }
                    }
                    elseif(in_array($field, $caseConfig->export->listFields))
                    {
                        if($field == 'stage')
                        {
                            $stages = explode("\n", $cellValue);
                            foreach($stages as $stage) $case->stage[] = array_search($stage, $caseLang->{$field . 'List'});
                            $case->stage = join(',', $case->stage);
                        }
                        else
                        {
                            $case->$field = array_search($cellValue, $caseLang->{$field . 'List'});
                        }
                    }
                    elseif($field != 'stepDesc' and $field != 'stepExpect')
                    {
                        $case->$field = $cellValue;
                    }
                    else
                    {
                        $steps = (array)$cellValue;
                        if(strpos($cellValue, "\n"))
                        {
                            $steps = explode("\n", $cellValue);
                        }
                        elseif(strpos($cellValue, "\r"))
                        {
                            $steps = explode("\r", $cellValue);
                        }

                        $stepKey  = str_replace('step', '', strtolower($field));
                        $caseStep = array();

                        foreach($steps as $step)
                        {
                            $trimedStep = trim($step);
                            if(empty($trimedStep)) continue;
                            if(preg_match('/^(([0-9]+)\.[0-9]+)([.、]{1})/U', $step, $out))
                            {
                                $num     = $out[1];
                                $parent  = $out[2];
                                $sign    = $out[3];
                                $signbit = $sign == '.' ? 1 : 3;
                                $step    = trim(substr($step, strlen($num) + $signbit));
                                if(!empty($step)) $caseStep[$num]['content'] = $step;
                                $caseStep[$num]['type']    = 'item';
                                $caseStep[$parent]['type'] = 'group';
                            }
                            elseif(preg_match('/^([0-9]+)([.、]{1})/U', $step, $out))
                            {
                                $num     = $out[1];
                                $sign    = $out[2];
                                $signbit = $sign == '.' ? 1 : 3;
                                $step    = trim(substr($step, strpos($step, $sign) + $signbit));
                                if(!empty($step)) $caseStep[$num]['content'] = $step;
                                $caseStep[$num]['type'] = 'step';
                            }
                            elseif(isset($num))
                            {
                                if(!isset($caseStep[$num]['content'])) $caseStep[$num]['content'] = '';
                                $caseStep[$num]['content'] .= "\n" . $step;
                            }
                            else
                            {
                                if($field == 'stepDesc')
                                {
                                    $num = 1;
                                    $caseStep[$num]['content'] = $step;
                                    $caseStep[$num]['type']    = 'step';
                                }
                                if($field == 'stepExpect' and isset($stepData[$row]['desc']))
                                {
                                    end($stepData[$row]['desc']);
                                    $num = key($stepData[$row]['desc']);
                                    $caseStep[$num]['content'] = $step;
                                }
                            }
                        }
                        unset($num);
                        unset($sign);
                        $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);
                        $stepData[$row][$stepKey] = $caseStep;
                    }
                }

                if(empty($case->title)) continue;
                $caseData[$row] = $case;
                unset($case);
            }

            $data['caseData'] = $caseData;
            $data['stepData'] = $stepData;
            file_put_contents($tmpFile, serialize($data));
        }

        if(empty($caseData))
        {
            echo js::alert($this->lang->error->noData);
            return print(js::locate($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch")));
        }

        $allCount = count($caseData);
        $allPager = 1;
        if($allCount > $this->config->file->maxImport)
        {
            if(empty($maxImport))
            {
                $this->view->allCount  = $allCount;
                $this->view->maxImport = $maxImport;
                $this->view->productID = $productID;
                $this->view->branch    = $branch;
                return print($this->display());
            }

            $allPager = ceil($allCount / $maxImport);
            $caseData = array_slice($caseData, ($pagerID - 1) * $maxImport, $maxImport, true);
        }
        if(empty($caseData)) return print(js::locate(inlink('browse', "productID=$productID&branch=$branch")));

        /* Judge whether the editedCases is too large and set session. */
        $countInputVars  = count($caseData) * 12 + (isset($stepVars) ? $stepVars : 0);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $this->view->title      = $this->lang->testcase->common . $this->lang->colon . $this->lang->testcase->showImport;
        $this->view->position[] = $this->lang->testcase->showImport;

        $this->view->stories    = $stories;
        $this->view->modules    = $modules;
        $this->view->cases      = $this->testcase->getByProduct($productID);
        $this->view->caseData   = $caseData;
        $this->view->stepData   = $stepData;
        $this->view->productID  = $productID;
        $this->view->branches   = $branches;
        $this->view->isEndPage  = $pagerID >= $allPager;
        $this->view->allCount   = $allCount;
        $this->view->allPager   = $allPager;
        $this->view->pagerID    = $pagerID;
        $this->view->branch     = $branch;
        $this->view->product    = $this->product->getByID($productID);
        $this->view->maxImport  = $maxImport;
        $this->view->dataInsert = $insert;

        $this->display();
    }

    /**
     * Import cases to library.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function importToLib($caseID = 0)
    {
        if($this->server->request_method == 'POST')
        {
            $this->testcase->importToLib($caseID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!empty($caseID)) return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => 'reload'));
        }
        $this->view->libraries = $this->loadModel('caselib')->getLibraries();
        $this->display();
    }

    /**
     * Case bugs.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function bugs($runID, $caseID = 0, $version = 0)
    {
        $this->view->title = $this->lang->testcase->bugs;
        $this->view->bugs  = $this->loadModel('bug')->getCaseBugs($runID, $caseID, $version);
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Show script.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function showScript($caseID)
    {
        $case = $this->testcase->getByID($caseID);
        if($case) $case->script = html_entity_decode($case->script);
        $this->view->case = $case;
        $this->display();
    }

    /**
     * Automation test setting.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function automation($productID = 0)
    {
        $this->loadModel('zanode');
        $nodeList   = $this->zanode->getPairs();
        $automation = $this->dao->select('*')->from(TABLE_AUTOMATION)->where('product')->eq($productID)->fetch();

        if($_POST)
        {
            $this->zanode->setAutomationSetting();

            if(dao::isError()) return print(js::error(dao::getError()));

            // if(!empty($_POST['syncToZentao']))
            //     $this->zanode->syncCasesToZentao($_POST['scriptPath']);

            // $nodeID = $_POST['node'];
            // $node   = $this->zanode->getNodeByID($_POST['node']);

            $locatelink = $this->createLink('testcase', 'browse', "productID={$_POST['product']}");
            $locatelink = str_replace(array('?onlybody=yes', '&onlybody=yes'), '', $locatelink);

            return print(js::locate($locatelink, 'parent.parent'));
        }

        $this->view->title      = $this->lang->zanode->automation;
        $this->view->automation = $automation;
        $this->view->nodeList   = $nodeList;
        $this->view->productID  = $productID;
        $this->view->products   = $this->product->getPairs('', 0, '', 'all');

        $this->display();
    }

    /**
     * Export case getModuleByStory
     *
     * @params int $storyID
     * @return void
     */
    public function ajaxGetStoryModule($storyID)
    {
        $story = $this->dao->select('module')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $moduleID = !empty($story) ? $story->module : 0;
        echo json_encode(array('moduleID'=> $moduleID));
    }

    /**
     * Get status by ajax.
     *
     * @param  string $methodName
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function ajaxGetStatus($methodName, $caseID = 0)
    {
        $case   = $this->testcase->getByID($caseID);
        $status = $this->testcase->getStatus($methodName, $case);
        if($methodName == 'update') $status = zget($status, 1, '');
        echo $status;
    }

    /**
     * Ajax: Get count of need review casese.
     *
     * @access public
     * @return int
     */
    public function ajaxGetReviewCount()
    {
        echo $this->dao->select('count(id) as count')->from(TABLE_CASE)->where('status')->eq('wait')->fetch('count');
    }

    /**
     * Create a scene.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function createScene($productID, $branch = '', $moduleID = 0)
    {
        if($_POST)
        {
            setcookie('lastCaseScene', (int)$this->post->parent, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);

            $sceneID = $this->testcase->createScene();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $useSession = $this->app->tab != 'qa' && $this->session->caseList && strpos($this->session->caseList, 'dynamic') === false;
            $locate     = $useSession ? $this->session->caseList : inlink('browse', "productID={$this->post->product}&branch={$this->post->branch}&browseType=all&param={$this->post->module}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        /* Set menu. */
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        else
        {
            if(empty($this->products)) $this->locate($this->createLink('product', 'create'));
            $this->qa->setMenu($this->products, $productID, $branch);
        }

        /* Set branch. */
        $product = $this->product->getById($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;
        if($this->app->tab == 'execution' || $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $this->session->project : $executionID;
            $productBranches = (isset($product->type) && $product->type != 'normal') ? $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = (isset($product->type) && $product->type != 'normal') ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $this->view->title    = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->newScene;
        $this->view->modules  = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' || !isset($branches[$branch])) ? 0 : $branch);
        $this->view->scenes   = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, ($branch === 'all' || !isset($branches[$branch])) ? 0 : $branch);
        $this->view->moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastCaseModule;
        $this->view->parent   = (int)$this->cookie->lastCaseScene;
        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $branches;
        $this->display();
    }

    /**
     * Ajax: get scenes module.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $stype
     * @param  int    $storyID
     * @param  string $onlyOption
     * @param  string $status
     * @param  int    $limit
     * @param  string $type
     * @param  int    $hasParent
     * @param  string $number
     * @param  int    $currentScene
     * @access public
     * @return void
     */
    public function ajaxGetModuleScenes($productID, $branch = 0, $moduleID = 0, $stype = 1, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0, $type = 'full', $hasParent = 1, $number = '', $currentScene = 0)
    {
        $optionMenu = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0, $branch, $currentScene);
        $output     = ($stype == 1) ? html::select("parent", $optionMenu, "", "class='form-control'") : $output = html::select("scene".$number, $optionMenu, array('' => ''), "class='form-control'");

        die($output);
    }

    /**
     * Ajax get module scenes.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  string $element
     * @param  int    $sceneID
     * @param  int    $number
     * @param  int    $ditto
     * @access public
     * @return void
     */
    public function ajaxGetScenes($productID, $branch = 0, $moduleID = 0, $element = 'scene', $sceneID = 0, $number = 0, $ditto = 0)
    {
        $scenes = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0, $branch, $sceneID);

        if($ditto) $scenes['ditto'] = $this->lang->testcase->ditto;

        die(html::select($number ? "{$element}[{$number}]" : $element, $scenes, '', "class='form-control'"));
    }

    /**
     * Batch change scene.
     *
     * @param  int $sceneID
     * @access public
     * @return void
     */
    public function batchChangeScene($sceneID)
    {
        if($this->post->caseIDList)
        {
            $this->testcase->batchChangeScene($this->post->caseIDList, $sceneID);
            if(dao::isError()) return print(js::error(dao::getError()));
        }

        echo js::locate($this->session->caseList, 'parent');
    }

    /**
     * Change scene of a case or change parent of a scene.
     *
     * @access public
     * @return bool
     */
    public function changeScene()
    {
        $sourceID = $this->post->sourceID;
        $sceneID  = $this->post->targetID;
        if($sourceID == $sceneID) return false;

        if(strpos($sourceID, 'case_') !== false)
        {
            $caseID  = str_replace('case_', '', $sourceID);
            $oldCase = $this->dao->select('scene')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
            if($oldCase->scene == $sceneID) return false;

            $this->dao->update(TABLE_CASE)->set('scene')->eq($sceneID)->where('id')->eq($caseID)->exec();
            if(dao::isError()) return false;

            $newCase = new stdclass();
            $newCase->scene = $sceneID;

            $changes  = common::createChanges($oldCase, $newCase);
            $actionID = $this->loadModel('action')->create('case', $caseID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return !dao::isError();
        }

        $oldScene      = $this->testcase->getSceneByID($sourceID);
        $newScene      = $this->testcase->getSceneByID($sceneID);
        $oldParentPath = substr($oldScene->path, 0, strpos($oldScene->path, ",{$oldScene->id},") + strlen(",{$oldScene->id},"));

        $this->dao->update(TABLE_SCENE)->set('parent')->eq($sceneID)->where('id')->eq($oldScene->id)->exec();
        $this->dao->update(TABLE_SCENE)
            ->set('product')->eq($newScene->product)
            ->set('branch')->eq($newScene->branch)
            ->set('module')->eq($newScene->module)
            ->set("grade=grade + {$newScene->grade} + 1 - {$oldScene->grade}")
            ->set("path=REPLACE(path, '{$oldParentPath}', '{$newScene->path}{$oldScene->id},')")
            ->where('path')->like("{$oldScene->path}%")
            ->exec();

        return !dao::isError();
    }

    /**
     * Delete a scene.
     *
     * @param  int    $sceneID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteScene($sceneID, $confirm = 'no')
    {
        $scene = $this->testcase->getSceneByID($sceneID);

        if($confirm == 'no') return print(js::confirm(sprintf($this->lang->testcase->confirmDeleteScene,addslashes($scene->title)), $this->createLink('testcase', 'deleteScene', "sceneID=$sceneID&confirm=yes")));

        $childrenCount = $this->dao->select('COUNT(*) AS count')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('parent')->eq($sceneID)->fetch('count');
        if($childrenCount)
        {
            if($confirm != "wait") return print(js::confirm(sprintf($this->lang->testcase->hasChildren), $this->createLink('testcase', 'deleteScene', "sceneID=$sceneID&confirm=wait")));

            $scenes = $this->dao->select('id')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('path')->like($scene->path . '%')->fetchPairs();
            $this->dao->update(TABLE_SCENE)->set('deleted')->eq('1')->where('deleted')->eq(0)->andWhere('path')->like($scene->path . '%')->exec();

            $this->loadModel('action');
            foreach($scenes as $sceneID) $this->action->create('scene', $sceneID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
        }
        else
        {
            $this->testcase->delete(TABLE_SCENE, $sceneID);
        }

        echo js::reload('parent');
    }

    /**
     * Edit scene.
     *
     * @param  int    $sceneID
     * @access public
     * @return void
     */
    public function editScene($sceneID)
    {
        $scene = $this->testcase->getSceneByID($sceneID);
        if(!$scene) return print(js::error($this->lang->notFound) . js::locate('back'));

        if($_POST)
        {
            $this->testcase->updateScene($sceneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $sceneID));

            $useSession = $this->app->tab != 'qa' && $this->session->caseList && strpos($this->session->caseList, 'dynamic') === false;
            $locate     = $useSession ? $this->session->caseList : inlink('browse', "productID={$this->post->product}&branch={$this->post->branch}&browseType=all&param={$this->post->module}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $productID = $scene->product;
        $product   = $this->product->getByID($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        /* Set menu. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu(null);
        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $scene->branch);

        /* Display status of branch. */
        $branches   = array();
        $branchList = $this->loadModel('branch')->getList($productID, 0, 'all');
        foreach($branchList as $branch) $branches[$branch->id] = $branch->name . ($branch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        if(!isset($branches[$scene->branch]))
        {
            $caseBranch = $this->branch->getByID($scene->branch, $scene->product, '');
            if($caseBranch) $branches[$scene->branch] = $caseBranch->name . ($caseBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        $modules = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $scene->branch);
        if(!isset($modules[$scene->module])) $modules += $this->tree->getModulesName($scene->module);

        $scenes = $this->testcase->getSceneMenu($productID, $scene->module, $viewType = 'case', $startSceneID = 0,  $scene->branch, $sceneID);
        if(!isset($scenes[$scene->parent])) $scenes += $this->testcase->getScenesName($scene->parent);

        $this->view->title    = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->editScene;
        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = $branches;
        $this->view->modules  = $modules;
        $this->view->scenes   = $scenes;
        $this->view->scene    = $scene;
        $this->display();
    }

    /**
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $type     = $this->post->type;
        $sourceID = $this->post->sourceID;
        $targetID = $this->post->targetID;
        $dataList = $this->post->dataList;
        if(!$type || !$sourceID || !$targetID || !$dataList) return false;
        if($sourceID == $targetID) return false;

        $idList      = array_map(function($data){return $data['id'];},    $dataList);
        $orderList   = array_map(function($data){return $data['order'];}, $dataList);
        $sourceIndex = array_search($sourceID, $idList);
        $targetIndex = array_search($targetID, $idList);

        if($sourceIndex === false || $targetIndex === false) return false;

        if($sourceIndex > $targetIndex)
        {
            $idList    = array_slice($idList,    $targetIndex, $sourceIndex - $targetIndex + 1);
            $orderList = array_slice($orderList, $targetIndex, $sourceIndex - $targetIndex + 1);
            array_unshift($idList, array_pop($idList));
        }
        else
        {
            $idList    = array_slice($idList,    $sourceIndex, $targetIndex - $sourceIndex + 1);
            $orderList = array_slice($orderList, $sourceIndex, $targetIndex - $sourceIndex + 1);
            if(count($idList) == 2)
            {
                $idList = array_reverse($idList);
            }
            else
            {
                array_splice($idList, -1, 0, array_shift($idList));
            }
        }

        $table = $type == 'case' ? TABLE_CASE : TABLE_SCENE;

        foreach($idList as $key => $id)
        {
            if(!isset($orderList[$key])) continue;
            $this->dao->update($table)->set('sort')->eq($orderList[$key])->where('id')->eq($id)->exec();
        }
    }

    /**
     * Export xmind.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $branch
     * @access public
     * @return void
     */
    public function exportXMind($productID, $moduleID, $branch)
    {
        if($_POST)
        {
            $this->classXmind = $this->app->loadClass('xmind');
            if (isset($_POST['imodule'])) $imoduleID = $_POST['imodule'];

            $configResult = $this->testcase->saveXmindConfig();
            if($configResult['result'] == 'fail') return print(js::alert($configResult['message']));

            $context = $this->testcase->getXmindExport($productID, $imoduleID, $branch);

            $xmlDoc = new DOMDocument('1.0', 'UTF-8');
            $xmlDoc->formatOutput = true;

            $versionAttr       =  $xmlDoc->createAttribute('version');
            $versionAttrValue  =  $xmlDoc->createTextNode('1.0.1');
            $versionAttr->appendChild($versionAttrValue);

            $mapNode = $xmlDoc->createElement('map');
            $mapNode->appendChild($versionAttr);
            $xmlDoc->appendChild($mapNode);

            $productName = '';
            if(count($context['caseList']))
            {
                $productName = $context['caseList'][0]->productName;
            }
            else
            {
                $product     = $this->product->getById($productID);
                $productName = $product->name;
            }

            $productNode   = $xmlDoc->createElement('node');
            $textAttr      = $xmlDoc->createAttribute('TEXT');
            $textAttrValue = $xmlDoc->createTextNode($this->classXmind->toText("$productName", $productID));

            $textAttr->appendChild($textAttrValue);
            $productNode->appendChild($textAttr);
            $mapNode->appendChild($productNode);

            $sceneNodes  = array();
            $moduleNodes = array();

            $this->classXmind->createModuleNode($xmlDoc, $context, $productNode, $moduleNodes);
            $this->classXmind->createSceneNode($xmlDoc, $context, $productNode, $moduleNodes, $sceneNodes);
            $this->classXmind->createTestcaseNode($xmlDoc, $context, $productNode, $moduleNodes, $sceneNodes);

            $xmlStr = $xmlDoc->saveXML();
            $this->fetch('file', 'sendDownHeader', array('fileName' => $productName, 'mm', $xmlStr));
        }

        $tree    = $moduleID ? $this->tree->getByID($moduleID) : '';
        $product = $this->product->getById($productID);
        $config  = $this->testcase->getXmindConfig();

        $this->view->settings         = $config;
        $this->view->moduleName       = $tree != '' ? $tree->name : '/';
        $this->view->productName      = $product->name;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

        $this->display();
    }

    /**
     * Get xmind config.
     *
     * @access public
     * @return void
     */
    public function getXmindConfig()
    {
        $result = $this->testcase->getXmindConfig();
        $this->send($result);
    }

    /**
     * Get xmind content.
     *
     * @access public
     * @return void
     */
    public function getXmindImport()
    {
        if(!commonModel::hasPriv("testcase", "importXmind")) $this->loadModel('common')->deny('testcase', 'importXmind');
        $folder = $this->session->xmindImport;
        $type   = $this->session->xmindImportType;

        if($type == 'xml')
        {
            $xmlPath = "$folder/content.xml";
            $results = $this->testcase->getXmindImport($xmlPath);

            echo $results;
        }
        else
        {
            $jsonPath = "$folder/content.json";
            $jsonStr = file_get_contents($jsonPath);

            echo $jsonStr;
        }
    }

    /**
     * Import xmind.
     *
     * @param  int $productID
     * @param  int $branch
     * @access public
     * @return void
     */
    public function importXmind($productID, $branch)
    {
        if($_FILES)
        {
            $this->classXmind = $this->app->loadClass('xmind');
            if($_FILES['file']['size'] == 0)  return print(js::alert($this->lang->testcase->errorFileNotEmpty));

            $configResult = $this->testcase->saveXmindConfig();
            if($configResult['result'] == 'fail') return print(js::alert($configResult['message']));

            $tmpName  = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $extName  = trim(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)));
            if($extName != 'xmind') return print(js::alert($this->lang->testcase->errorFileFormat));

            $newPureName  = $this->app->user->id."-xmind";
            $importFolder = $this->app->getTmpRoot() . "import";
            if(!is_dir($importFolder)) mkdir($importFolder, 0755, true);

            $dest = $this->app->getTmpRoot() . "import/".$newPureName.$extName;
            if(!move_uploaded_file($tmpName, $dest)) return print(js::alert($this->lang->testcase->errorXmindUpload));

            $extractFolder   =  $this->app->getTmpRoot() . "import/".$newPureName;
            $this->classFile = $this->app->loadClass('zfile');
            if(is_dir($extractFolder)) $this->classFile->removeDir($extractFolder);

            $this->app->loadClass('pclzip', true);
            $zip = new pclzip($dest);

            $files      = $zip->listContent();
            $removePath = $files[0]['filename'];
            if($zip->extract(PCLZIP_OPT_PATH, $extractFolder, PCLZIP_OPT_REMOVE_PATH, $removePath) == 0)
            {
                return print(js::alert($this->lang->testcase->errorXmindUpload));
            }

            $this->classFile->removeFile($dest);

            $jsonPath = $extractFolder."/content.json";
            if(file_exists($jsonPath) == true)
            {

                $fetchResult = $this->fetchByJSON($extractFolder, $productID, $branch);
            }
            else
            {
                $fetchResult = $this->fetchByXML($extractFolder, $productID, $branch);
            }

            if($fetchResult['result'] == 'fail')
            {
                return print(js::alert($fetchResult['message']));
            }

            $this->session->set('xmindImport', $extractFolder);
            $this->session->set('xmindImportType', $fetchResult['type']);

            $pId = $fetchResult['pId'];

            return print(js::locate($this->createLink('testcase', 'showXmindImport', "productID=$pId&branch=$branch"), 'parent.parent'));
        }

        $config = $this->testcase->getXmindConfig();

        $this->view->settings    = $config;

        $this->display();
    }

    /**
     * Fetch by xml.
     *
     * @param  string $extractFolder
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    function fetchByXML($extractFolder, $productID, $branch)
    {
        $filePath = $extractFolder."/content.xml";
        $xmlNode = simplexml_load_file($filePath);
        $title = $xmlNode->sheet->topic->title;
        if(strlen($title) == 0)
        {
            return array('result'=>'fail','message'=>$this->lang->testcase->errorXmindUpload);
        }

        $pId = $productID;
        if($this->classXmind->endsWith($title,"]") == true)
        {
            $tmpId = $this->classXmind->getBetween($title,"[","]");
            if(empty($tmpId) == false)
            {
                $projectCount = $this->dao->select('count(*) as count')
                    ->from(TABLE_PRODUCT)
                    ->where('id')
                    ->eq((int)$tmpId)
                    ->andWhere('deleted')->eq('0')
                    ->fetch('count');

                if((int)$projectCount == 0) return array('result'=>'fail','message'=>$this->lang->testcase->errorImportBadProduct);

                $pId = $tmpId;
            }
        }

        return array('result'=>'success','pId'=>$pId, 'type'=>'xml');
    }

    /**
     * Fetch by json.
     *
     * @param  string $extractFolder
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    function fetchByJSON($extractFolder, $productID, $branch)
    {
        $filePath = $extractFolder."/content.json";
        $jsonStr = file_get_contents($filePath);
        $jsonDatas = json_decode($jsonStr, true);
        $title = $jsonDatas[0]['rootTopic']['title'];
        if(strlen($title) == 0)
        {
            return array('result'=>'fail','message'=>$this->lang->testcase->errorXmindUpload);
        }

        $pId = $productID;
        if($this->classXmind->endsWith($title,"]") == true)
        {
            $tmpId = $this->classXmind->getBetween($title,"[","]");
            if(empty($tmpId) == false)
            {
                $projectCount = $this->dao->select('count(*) as count')
                    ->from(TABLE_PRODUCT)
                    ->where('id')
                    ->eq((int)$tmpId)
                    ->andWhere('deleted')->eq('0')
                    ->fetch('count');

                if((int)$projectCount == 0) return array('result'=>'fail','message'=>$this->lang->testcase->errorImportBadProduct);

                $pId = $tmpId;
            }
        }

        return array('result'=>'success','pId'=>$pId,'type'=>'json');
    }

    /**
     * Save xmind config.
     *
     * @access public
     * @return void
     */
    public function saveXmindConfig()
    {
        $result = $this->testcase->saveXmindConfig();
        $this->send($result);
    }

    /**
     * Save imported xmind.
     *
     * @access public
     * @return void
     */
    public function saveXmindImport()
    {
        if(!commonModel::hasPriv("testcase", "importXmind")) $this->loadModel('common')->deny('testcase', 'importXmind');
        if(!empty($_POST))
        {
            $result = $this->testcase->saveXmindImport();
            return $this->send($result);
        }

        $this->send(array('result' => 'fail', 'message' => $this->lang->errorSaveXmind));
    }

    /**
     * Show imported xmind.
     *
     * @param  int $productID
     * @param  int $branch
     * @access public
     * @return void
     */
    public function showXmindImport($productID,$branch)
    {
        if(!commonModel::hasPriv("testcase", "importXmind")) $this->loadModel('common')->deny('testcase', 'importXmind');
        $product  = $this->product->getById($productID);
        $branches = (isset($product->type) and $product->type != 'normal') ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        $config   = $this->testcase->getXmindConfig();

        /* Set menu. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu(null);
        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $branch);

        $jsLng = array();
        $jsLng['caseNotExist'] = $this->lang->testcase->caseNotExist;
        $jsLng['saveFail']     = $this->lang->testcase->saveFail;
        $jsLng['set2Scene']    = $this->lang->testcase->set2Scene;
        $jsLng['set2Testcase'] = $this->lang->testcase->set2Testcase;
        $jsLng['clearSetting'] = $this->lang->testcase->clearSetting;
        $jsLng['setModule']    = $this->lang->testcase->setModule;
        $jsLng['pickModule']   = $this->lang->testcase->pickModule;
        $jsLng['clearBefore']  = $this->lang->testcase->clearBefore;
        $jsLng['clearAfter']   = $this->lang->testcase->clearAfter;
        $jsLng['clearCurrent'] = $this->lang->testcase->clearCurrent;
        $jsLng['removeGroup']  = $this->lang->testcase->removeGroup;
        $jsLng['set2Group']    = $this->lang->testcase->set2Group;

        $this->view->title            = $this->lang->testcase->xmindImport;
        $this->view->settings         = $config;
        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->product          = $product;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        $this->view->gobackLink       = $this->createLink('testcase', 'browse', "productID=$productID");
        $this->view->jsLng            = $jsLng;

        $this->display();
    }
}
