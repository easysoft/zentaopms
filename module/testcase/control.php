<?php
declare(strict_types=1);
/**
 * The control file of case currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: control.php 5112 2013-07-12 02:51:33Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
        if(!isInModal() && $this->app->methodName != 'delete')
        {
            if($this->app->tab == 'project')
            {
                $objectID = $this->session->project;
                $products = $this->product->getProducts((int)$objectID, 'all', '', false);
            }
            elseif($this->app->tab == 'execution')
            {
                $objectID = $this->session->execution;
                $products = $this->product->getProducts((int)$objectID, 'all', '', false);
            }
            else
            {
                $mode     = ($this->app->methodName == 'create' and empty($this->config->CRProduct)) ? 'noclosed' : '';
                $products = $this->product->getPairs($mode, 0, '', 'all');
            }
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
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $caseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  int    $projectID
     * @param  string $from
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $branch = '', string $browseType = 'all', int $param = 0, string $caseType = '', string $orderBy = 'sort_asc,id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0, string $from = 'testcase', int $blockID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if($from == 'doc' || $from == 'ai')
        {
            $this->app->loadLang('doc');
            $realProducts = $this->product->getPairs('nodeleted', 0, '', 'all');
            if(empty($realProducts)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noProduct));
        }

        /* 把访问的产品ID等状态信息保存到session和cookie中。*/
        /* Save the product id user last visited to session and cookie. */
        $productID  = ($this->app->tab != 'project' || $from == 'doc' || $from == 'ai') ? $this->product->checkAccess($productID, $this->products) : $productID;
        $branch     = $this->testcaseZen->getBrowseBranch($branch);
        $browseType = strtolower($browseType);
        $moduleID   = $browseType == 'bymodule' ? $param : 0;
        $suiteID    = $browseType == 'bysuite'  ? $param : ($browseType == 'bymodule' ? ($this->cookie->caseSuite ? (int)$this->cookie->caseSuite : 0) : 0);
        $queryID    = $browseType == 'bysearch' ? $param : 0;
        if($browseType == 'bymodule' && $this->session->caseBrowseType) $browseType = $this->session->caseBrowseType;

        $this->testcaseZen->setBrowseCookie($productID, $branch, $browseType, (string)$param);
        $this->testcaseZen->setBrowseSession($productID, $branch, $moduleID, $browseType, $orderBy);
        list($productID, $branch) = $this->testcaseZen->setBrowseMenu($productID, $branch, $projectID);

        $currentModule  = ($this->app->tab == 'project' && $from != 'doc' && $from != 'ai') ? 'project'  : 'testcase';
        $currentMethod  = ($this->app->tab == 'project' && $from != 'doc' && $from != 'ai') ? 'testcase' : 'browse';
        $projectParam   = ($this->app->tab == 'project' && $from != 'doc' && $from != 'ai') ? "projectID={$this->session->project}&" : '';
        $suffixParam    = "&caseType=$caseType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
        if($from == 'doc' || $from == 'ai') $suffixParam .= "&projectID=$projectID&from=$from&blockID=$blockID";
        $actionURL      = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID" . $suffixParam);
        $this->testcaseZen->buildBrowseSearchForm($productID, $queryID, $projectID, $actionURL);

        $this->testcaseZen->assignCasesForBrowse($productID, $branch, $browseType, ($browseType == 'bysearch' ? $queryID : $suiteID), $moduleID, $caseType, $orderBy, $recTotal, $recPerPage, $pageID, $from);
        $this->testcaseZen->assignModuleTreeForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignProductAndBranchForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignForBrowse($productID, $branch, $browseType, $projectID, $param, $moduleID, $suiteID, $caseType);

        $this->view->from        = $from;
        $this->view->blockID     = $blockID;
        $this->view->docBlock    = false;
        $this->view->idList      = '';
        $this->view->suffixParam = $suffixParam;
        if($from === 'doc')
        {
            $docBlock = $this->loadModel('doc')->getDocBlock($blockID);
            $this->view->docBlock = $docBlock;
            if($docBlock)
            {
                $content = json_decode($docBlock->content, true);
                if(isset($content['idList'])) $this->view->idList = $content['idList'];
            }
        }

        $this->display();
    }

    /**
     * 浏览场景列表。
     * Browse scenes.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseScene(int $productID = 0, string $branch = '', int $moduleID = 0, string $orderBy = 'sort_asc,id_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $productID  = $this->product->checkAccess($productID, $this->products);
        $branch     = $this->testcaseZen->getBrowseBranch($branch);
        $tree       = $moduleID ? $this->tree->getByID($moduleID) : '';
        $showModule = !empty($this->config->testcase->browse->showModule) ? $this->config->testcase->browse->showModule : '';

        $this->testcaseZen->setBrowseCookie($productID, $branch);
        $this->testcaseZen->setBrowseSession($productID, $branch, $moduleID);
        $this->testcaseZen->assignProductAndBranchForBrowse($productID, $branch);

        /* 这些变量用于设置公共的头部菜单。These variables are used to set the common header menu. */
        $this->view->browseType = 'onlyscene';
        $this->view->caseType   = '';
        $this->view->projectID  = 0;
        $this->view->suiteList  = $this->loadModel('testsuite')->getSuites($productID);

        $this->view->title       = zget($this->products, $productID, '') . $this->lang->hyphen . $this->lang->testcase->common;
        $this->view->scenes      = $this->testcase->getSceneGroups($productID, $branch, $moduleID, $orderBy, $pager);
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->modules     = $this->tree->getOptionMenu($productID, 'case', 0, $branch == 'all' ? '0' : $branch);
        $this->view->moduleTree  = $this->tree->getTreeMenu($productID, 'case', 0, ['treeModel', 'createSceneLink'], ['orderBy' => $orderBy], $branch);
        $this->view->moduleName  = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : [];
        $this->view->moduleID    = $moduleID;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * 分组查看用例。
     * Group case.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $groupBy
     * @param  int    $objectID
     * @param  string $caseType
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function groupCase(int $productID = 0, string $branch = '', string $groupBy = 'story', int $objectID = 0, string $caseType = '', string $browseType = 'all')
    {
        if($browseType) $this->session->set('caseBrowseType', $browseType);
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        /* 设置SESSION和COOKIE，获取产品信息。 */
        /* Set session and cookie, and get product. */
        $productID = $this->product->checkAccess($productID, $this->products);
        $product   = $this->product->getByID($productID);
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);

        if($branch === '') $branch = $this->cookie->preBranch;
        if(empty($groupBy)) $groupBy = 'story';

        $projectID = $executionID = 0;
        if($this->app->tab == 'project')
        {
            $projectID = $this->session->project;
            $this->view->projectID = $projectID;
        }
        else
        {
            $executionID = $this->session->execution;
            $this->view->executionID = $executionID;
        }

        /* 设置菜单。 */
        /* Set menu. */
        $this->testcaseZen->setMenu((int)$projectID, (int)$executionID, $productID, $branch);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->title       = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->common;
        $this->view->productID   = $productID;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->cases       = $this->testcaseZen->getGroupCases($productID, $branch, $groupBy, $caseType, $browseType);
        $this->view->suiteList   = $this->loadModel('testsuite')->getSuites($productID);
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
     * @param  int    $objectID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function zeroCase(int $productID = 0, int $branchID = 0, string $orderBy = 'id_desc', int $objectID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        /* 设置 session, cookie 和菜单。*/
        /* Set session, cookie and set menu. */
        $this->session->set('storyList', $this->app->getURI(true) . '#app=' . $this->app->tab, 'product');
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        $this->testcaseZen->setMenu((int)$this->session->project, 0, $productID, $branchID);
        $projectID = $executionID = 0;
        if($this->app->tab == 'project')
        {
            $projectID = $objectID;
            $products  = $this->product->getProducts($this->session->project, 'all', '', false);
            $productID = $this->product->checkAccess($productID, $products);
            $this->config->hasSwitcherMethods[] = 'testcase-zerocase';

            $productPairs = $this->loadModel('project')->getMultiLinkedProducts($this->session->project);
            $this->view->projectID      = $projectID;
            $this->view->switcherParams = "projectID={$this->session->project}&productID={$productID}&currentMethod=zerocase";
            $this->view->switcherText   = zget($productPairs, $productID, $this->lang->product->all);
        }
        if($this->app->tab == 'execution')
        {
            $executionID = $objectID;
            $products    = $this->product->getProducts($executionID, 'all', '', false);
            $productID   = $this->product->checkAccess($productID, $products);
            $this->config->hasSwitcherMethods[] = 'testcase-zerocase';

            $productPairs = $this->loadModel('project')->getMultiLinkedProducts($executionID);
            $this->view->executionID    = $executionID;
            $this->view->switcherParams = "executioID={$executionID}&productID={$productID}&currentMethod=zerocase";
            $this->view->switcherText   = zget($productPairs, $productID, $this->lang->product->all);
        }
        else
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->checkAccess($productID, $products);
            $this->loadModel('qa');
            $this->app->rawModule = 'testcase';
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
        }

        /* 设置 分页。*/
        /* Set pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* 追加二次排序条件。*/
        /* Append id for second sort. */
        $sort = common::appendOrder(empty($orderBy) ? 'id_desc' : $orderBy);
        if(strpos($sort, 'planTitle') !== false) $sort = str_replace('planTitle', 'plan', $sort);

        $this->lang->testcase->featureBar['zerocase'] = $this->lang->testcase->featureBar['browse'];

        /* 展示变量. */
        /* Show the variables. */
        $this->loadModel('story');
        $this->view->title      = $this->lang->story->zeroCase;
        $this->view->stories    = $this->story->getZeroCase($productID, $projectID, $executionID, $branchID, $sort, $pager);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->objectID   = $objectID;
        $this->view->productID  = $productID;
        $this->view->branch     = $branchID;
        $this->view->orderBy    = $orderBy;
        $this->view->suiteList  = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->browseType = '';
        $this->view->product    = $this->product->getByID($productID);
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 创建一个测试用例。
     * Create a test case.
     * @param int    $productID
     * @param string $branch
     * @param int    $moduleID
     * @param string $from
     * @param int    $param
     * @param int    $storyID
     * @param string $extras
     * @access public
     * @return void
     */
    public function create(int $productID, string $branch = '', int $moduleID = 0, string $from = '', int $param = 0, int $storyID = 0, string $extras = '')
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if(!empty($_POST))
        {
            /* 构建用例。 */
            /* Build Case. */
            $case = $this->testcaseZen->buildCaseForCreate($from, $param);

            /* 创建测试用例前检验表单数据是否正确。 */
            /* Check from data for create case. */
            $this->testcaseZen->checkCreateFormData($case);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $caseID = $this->testcase->create($case);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcaseZen->afterCreate($case, $caseID);
            return $this->testcaseZen->responseAfterCreate($caseID, $case->module);
        }

        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* 设置产品id和分支。 */
        /* Set productID and branch. */
        $productID = $this->product->checkAccess($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch ? : '';

        $this->testcaseZen->assignCreateVars($productID, $branch, $moduleID, $from, $param, $storyID);

        /* 设置自定义字段。 */
        /* Set custom fields. */
        foreach(explode(',', $this->config->testcase->list->customCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->createFields;

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);

        $this->view->title        = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->create;
        $this->view->productID    = $productID;
        $this->view->users        = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->gobackLink   = isset($output['from']) && $output['from'] == 'global' ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
        $this->view->needReview   = $this->testcase->forceNotReview() == true ? 0 : 1;
        $this->view->onlyAutoCase = $this->cookie->onlyAutoCase;
        $this->display();
    }

    /**
     * 批量创建用例。
     * Create batch testcase.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function batchCreate(int $productID, string $branch = '', int $moduleID = 0, int $storyID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if(!empty($_POST))
        {
            $testcases = $this->testcaseZen->buildCasesForBathcCreate($productID);
            $testcases = $this->testcaseZen->checkTestcasesForBatchCreate($testcases, $productID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($testcases as $testcase)
            {
                $testcaseID = $this->testcase->create($testcase);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $this->executeHooks($testcaseID);
                $this->testcase->syncCase2Project($testcase, $testcaseID);
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');
            return $this->testcaseZen->responseAfterBatchCreate($productID, $branch);
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* 设置 session 和 cookie, 并且设置产品 id、分支。 */
        /* Set session and cookie, and set product id, branch. */
        $productID = $this->product->checkAccess($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch;

        /* 设置菜单。 */
        /* Set menu. */
        $this->testcaseZen->setMenu((int)$this->session->project, 0, $productID, $branch);

        /* 指派产品、分支、需求、自定义字段等变量. */
        /* Assign the variables about product, branches, story and custom fields. */
        $this->testcaseZen->assignForBatchCreate($productID, $branch, $moduleID, $storyID);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->title            = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->batchCreate;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' ? '0' : $branch);
        $this->view->currentSceneID   = 0;
        $this->view->branch           = $branch;
        $this->view->needReview       = $this->testcase->forceNotReview() == true ? 0 : 1;
        $this->display();
    }

    /**
     * 创建 bug。
     * Create bug.
     *
     * @param  int    $productID
     * @param  int    $caseID
     * @param  int    $version
     * @param  int    $runID
     * @param  string $extras
     * @access public
     * @return void
     */
    public function createBug(int $productID, int $caseID, int $version, int $runID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        /* 获取用例和用例执行结果。 */
        /* Get case and results. */
        $case = '';
        if($runID)
        {
            $case = $this->loadModel('testtask')->getRunById($runID)->case;
        }
        elseif($caseID)
        {
            $case = $this->testcase->fetchById($caseID);
        }

        /* 如果用例不存在，关闭模态框。 */
        /* If case isn't exist, close modal box. */
        if(!$case) return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound, 'closeModal' => true));

        /* 如果产品未获取，获取产品。 */
        /* If product isn't available, get the product. */
        if(!isset($this->products[$productID]))
        {
            $product = $this->product->getByID($productID);
            $this->products[$productID] = $product->name;
        }

        /* 展示变量. */
        /* Show the variables. */
        $this->view->title   = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->createBug;
        $this->view->caseID  = $caseID;
        $this->view->version = $version;
        $this->view->runID   = $runID;
        $this->display();
    }

    /**
     * View a test case.
     *
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $from
     * @param  int    $taskID
     * @param  string $stepsType
     * @access public
     * @return void
     */
    public function view(int $caseID, int $version = 0, string $from = 'testcase', int $taskID = 0, string $stepsType = '')
    {
        $this->session->set('bugList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('testtaskID', $taskID, $this->app->tab);

        if(empty($stepsType)) $stepsType = $this->cookie->stepsType;
        if(empty($stepsType)) $stepsType = 'table';
        setCookie('stepsType', $stepsType, $this->config->cookieLife, $this->config->webRoot);

        $case = $this->testcase->getById($caseID, $version);

        /* 如果用例不存在，返回到测试仪表盘页面。 */
        /* If testcase isn't exist, locate to qa-ndex.*/
        if(!$case)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            if(!$this->server->http_referer) return print(js::alert($this->lang->notFound) . js::locate($this->createLink('testcase', 'browse', '')));
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('testcase', 'browse'))));
        }

        $case->caseID = $case->id;
        $this->executeHooks($caseID);

        if(defined('RUN_MODE') && RUN_MODE == 'api' && !empty($this->app->version)) return $this->send(array('status' => 'success', 'case' => $case));

        $this->testcaseZen->assignCaseForView($case, $from, $taskID);

        $isLibCase = $case->lib && empty($case->product);
        /* 如果用例是在用例库内，指定相关变量。 */
        /* If testcase is in case lib, assign associated variables. */
        if($isLibCase)
        {
            $libraries = $this->loadModel('caselib')->getLibraries();
            $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->caselib->setLibMenu($libraries, $case->lib);
            if($this->app->tab == 'project') $this->view->projectID = (int)$this->session->project;

            $this->view->libID   = $case->lib;
            $this->view->title   = "CASE #$case->id $case->title - " . $libraries[$case->lib];
            $this->view->libName = $libraries[$case->lib];
        }
        /* 如果用例不在用例库内，指定相关变量。 */
        /* If testcase isn't in case lib, assign associated variables. */
        else
        {
            $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

            $productID = $case->product;
            $product   = $this->product->getByID($productID);
            $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);

            $this->testcaseZen->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $case->branch);

            $this->view->title       = "CASE #$case->id $case->title - " . $product->name;
            $this->view->product     = $product;
            $this->view->branches    = $branches;
            $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $case->branch, '');
        }

        $this->view->testcase  = $case;
        $this->view->stepsType = $stepsType;
        $this->view->version   = $version ? $version : $case->version;
        $this->view->isLibCase = $isLibCase;
        $this->display();
    }

    /**
     * 编辑用例。
     * Edit a case.
     *
     * @param  int    $caseID
     * @param  string $comment
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function edit(int $caseID, string $comment = 'false', int $executionID = 0, string $from = 'testcase')
    {
        $oldCase = $this->testcase->getByID($caseID);
        if(!$oldCase) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->notFound}')", 'load' => array('back' => true)));

        $testtasks = $this->loadModel('testtask')->getGroupByCases($caseID);
        $testtasks = empty($testtasks[$caseID]) ? array() : $testtasks[$caseID];

        if(!empty($_POST))
        {
            $formData = form::data($this->config->testcase->form->edit, $caseID);
            $case     = $this->testcaseZen->prepareEditExtras($formData, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = array();
            if($comment != 'true')
            {
                $changes = $this->testcase->update($case, $oldCase, $testtasks);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($case->comment || !empty($changes)) $this->testcaseZen->addEditAction($caseID, $oldCase->status, $case->status, $changes, $case->comment);

            $message = $this->executeHooks($caseID);
            if(!$message) $message = $this->lang->saveSuccess;

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $caseID));
            if(isInModal() && $this->app->tab == 'my') return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true));
            $testtaskID = $from == 'testtask' && $this->session->testtaskID ? $this->session->testtaskID : 0;
            $locate     = $oldCase->lib ? $this->createLink('caselib', 'viewCase', "caseID={$caseID}") : $this->createLink('testcase', 'view', "caseID={$caseID}&version=0&from={$from}&testtaskID={$testtaskID}");
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $locate));
        }

        $case = $this->testcaseZen->preProcessForEdit($oldCase);

        if($case->lib && empty($case->product))
        {
            $productID = isset($this->session->product) ? $this->session->product : 0;
            $libraries = $this->loadModel('caselib')->getLibraries();

            $this->testcaseZen->setMenuForLibCaseEdit($case, $libraries);
            $this->testcaseZen->assignForEditLibCase($case, $libraries);
        }
        else
        {
            $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。
            $productID = $case->product;

            $this->testcaseZen->setMenuForCaseEdit($case, $executionID);
            $this->testcaseZen->assignForEditCase($case, $executionID);
        }

        $this->testcaseZen->assignForEdit($productID, $case, $testtasks);
        $this->display();
    }

    /**
     * Batch edit case.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $type
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, string $branch = '0', string $type = 'case', string $from = '')
    {
        if($type == 'case') $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。
        if(!$this->post->caseIdList && !$this->post->id) $this->locate($this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$productID}"));

        $caseIdList = $this->post->caseIdList ? array_unique($this->post->caseIdList) : array_unique($this->post->id);
        $cases      = $this->testcase->getByList($caseIdList);
        $caseIdList = array_keys($cases);
        $testtasks  = $this->loadModel('testtask')->getGroupByCases($caseIdList);
        if($this->post->id)
        {
            $oldSteps    = $this->testcase->fetchStepsByList($caseIdList);
            $editedCases = $this->testcaseZen->buildCasesForBathcEdit($cases, $oldSteps);
            $editedCases = $this->testcaseZen->checkCasesForBatchEdit($editedCases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 更新用例。 */
            /* Update cases. */
            $caseFiles = $this->testcase->getRelatedFiles($caseIdList);
            foreach($editedCases as $caseID => $case)
            {
                $oldCase = $cases[$caseID];
                if(!isset($case->files))    $case->files    = zget($caseFiles, $caseID, array());
                if(!isset($oldCase->files)) $oldCase->files = zget($caseFiles, $caseID, array());
                if(!isset($oldCase->steps)) $oldCase->steps = zget($oldSteps, $caseID, array());
                $changes = $this->testcase->update($case, $oldCase, zget($testtasks, $caseID, array()));
                $this->executeHooks($caseID);

                if(empty($changes)) continue;
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchEdit');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
        }

        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $this->view->projectID = $this->session->project;
        }
        elseif($this->app->tab == 'my')
        {
            $this->loadModel('my');
            if($from == 'work' || $from == 'contribute')
            {
                $this->lang->my->menu->{$from}['subModule'] = 'testcase';
                $this->lang->my->menu->{$from}['subMenu']->testcase['subModule'] = 'testcase';
            }
        }

        $this->testcaseZen->assignForBatchEdit($productID, $branch, $type, $cases);
        if($type == 'lib') $this->testcaseZen->assignLibForBatchEdit($productID);

        /* 判断要编辑的用例是否太大，设置 session。 */
        /* Judge whether the editedCases is too large and set session. */
        $countInputVars  = count($cases) * (count(explode(',', $this->config->testcase->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $stories = $this->loadModel('story')->getProductStoryPairs($productID, $branch, array(), 'all', 'id_desc', 0, '', 'story', false);
        $cases   = $this->testcaseZen->processStepsAndExpectsForBatchEdit($cases);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->stories         = $this->story->addGradeLabel($stories);
        $this->view->caseIdList      = $caseIdList;
        $this->view->productID       = $productID;
        $this->view->cases           = $cases;
        $this->view->forceNotReview  = $this->testcase->forceNotReview();
        $this->view->testtasks       = $testtasks;
        $this->view->isLibCase       = $type == 'lib' ? true : false;
        $this->display();
    }

    /**
     * 评审用例。
     * Review case.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function review(int $caseID)
    {
        $oldCase = $this->testcase->getByID($caseID);
        if($_POST)
        {
            $case = $this->testcaseZen->prepareReviewData($caseID, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcase->review($case, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($caseID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        if((isset($oldCase->caseStatus) && $oldCase->caseStatus != 'wait') || $oldCase->status != 'wait')
        {
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$this->lang->hasReviewed}'}).then((res) => {loadCurrentPage()});"));
        }


        $this->view->title      = $this->lang->testcase->review;
        $this->view->users      = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->testcaseID = $caseID;
        $this->view->actions    = $this->loadModel('action')->getList('case', $caseID);
        $this->display();
    }

    /**
     * Batch review case.
     *
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchReview(string $result)
    {
        if($this->post->caseIdList)
        {
            $this->testcase->batchReview($this->post->caseIdList, $result);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->caseList));
    }

    /**
     * 删除用例。
     * Delete a test case.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function delete(int $caseID)
    {
        $this->testcase->delete(TABLE_CASE, $caseID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $message = $this->executeHooks($caseID);
        if(!$message) $message = $this->lang->saveSuccess;

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

        $case = $this->testcase->getByID($caseID);
        $locateLink = $this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$case->product}");
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $locateLink));
    }

    /**
     * Batch delete cases and scenes.
     *
     * @access public
     * @return void
     */
    public function batchDelete()
    {
        $caseIdList  = zget($_POST, 'caseIdList',  array());
        $sceneIdList = zget($_POST, 'sceneIdList', array());
        if($caseIdList || $sceneIdList)
        {
            $this->testcase->batchDelete($caseIdList, $sceneIdList);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
    }

    /**
     * 批量修改用例的分支。
     * Batch change branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch(int $branchID)
    {
        $caseIdList  = zget($_POST, 'caseIdList',  array());
        $sceneIdList = zget($_POST, 'sceneIdList', array());
        if($caseIdList || $sceneIdList)
        {
            $this->testcase->batchChangeBranch($caseIdList, $sceneIdList, $branchID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
    }

    /**
     * 批量修改用例的模块。
     * Batch change the module of case.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID)
    {
        $caseIdList  = zget($_POST, 'caseIdList',  array());
        $sceneIdList = zget($_POST, 'sceneIdList', array());
        if($caseIdList || $sceneIdList)
        {
            $this->testcase->batchChangeModule($caseIdList, $sceneIdList, $moduleID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Batch review case.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchChangeType(string $type)
    {
        $caseIdList = zget($_POST, 'caseIdList',  array());
        if($caseIdList)
        {
            $this->testcase->batchChangeType($caseIdList, $type);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
    }

    /**
     * 关联相关用例。
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
    public function linkCases(int $caseID, string $browseType = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $case = $this->testcase->getByID($caseID);

        /* 设置导航。*/
        /* Set menu. */
        $this->testcase->setMenu($case->product, $case->branch);

        /* 构建搜索表单。*/
        /* Build the search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->testcaseZen->buildLinkCasesSearchForm($case, $queryID);

        /* 获取可关联的用例。*/
        /* Get cases to link. */
        $cases2Link = $this->testcase->getCases2Link($caseID, $browseType, $queryID);

        /* 用例分页。*/
        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal   = count($cases2Link);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $cases2Link = array_chunk($cases2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->title      = $case->title . $this->lang->hyphen . $this->lang->testcase->linkCases;
        $this->view->case       = $case;
        $this->view->cases2Link = empty($cases2Link) ? $cases2Link : $cases2Link[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * 关联相关 bug。
     * Link related bugs.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs(int $caseID, string $browseType = '', int $param = 0, string $orderBy = 'id_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('bug');

        $case = $this->testcase->getByID($caseID);

        /* 构建搜索表单。*/
        /* Build the search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->testcaseZen->buildLinkBugsSearchForm($case, $queryID);

        /* 获取关联的bug。*/
        /* Get bugs to link. */
        $bugs2Link = $this->testcase->getBugs2Link($caseID, $browseType, $queryID, $orderBy);

        /* bug 分页。*/
        /* Pager. */
        $this->app->loadClass('pager', true);
        if(empty($recTotal)) $recTotal = count($bugs2Link);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $bugs2Link = array_chunk($bugs2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->title      = $this->lang->testcase->linkBugs;
        $this->view->case       = $case;
        $this->view->bugs2Link  = empty($bugs2Link) ? $bugs2Link : $bugs2Link[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;

        $this->display();
    }

    /**
     * 确认用例变更。
     * Confirm testcase changed.
     *
     * @param  int    $caseID
     * @param  int    $taskID
     * @param  string $from   view|list
     * @access public
     * @return void
     */
    public function confirmChange(int $caseID, int $taskID = 0, string $from = 'view')
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_TESTRUN)->set('version')->eq($case->version)->where('`case`')->eq($caseID)->exec();
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $from == 'view' ? inlink('view', "caseID={$caseID}&version={$case->version}&from=testtask&taskID={$taskID}") : true));
    }

    /**
     * 确认用例库用例的更新。
     * Confirm case changed in lib.
     *
     * @param  int    $caseID
     * @param  int    $libCaseID
     * @access public
     * @return void
     */
    public function confirmLibcaseChange(int $caseID, int $libCaseID)
    {
        /** 获取用例和用例库的用例，设置用例版本。 /
        /* Get case and lib case, set case version.*/
        $case    = $this->testcase->getById($caseID);
        $libCase = $this->testcase->getById($libCaseID);
        $version = $case->version + 1;

        /* 更新用例基础信息。 */
        /* Update case base information. */
        $this->dao->update(TABLE_CASE)
            ->set('version')->eq($version)
            ->set('fromCaseVersion')->eq($version)
            ->set('precondition')->eq($libCase->precondition)
            ->set('title')->eq($libCase->title)
            ->set('keywords')->eq($libCase->keywords)
            ->where('id')->eq($caseID)
            ->exec();

        /* 更新用例步骤。 */
        /* Update case steps. */
        $parentSteps = array();
        foreach($libCase->steps as $step)
        {
            $data = new stdclass();
            $data->parent  = zget($parentSteps, $step->parent, 0);
            $data->case    = $caseID;
            $data->version = $version;
            $data->type    = $step->type;
            $data->desc    = $step->desc;
            $data->expect  = $step->expect;
            $this->dao->insert(TABLE_CASESTEP)->data($data)->exec();
            $parentSteps[$step->id] = $this->dao->lastInsertID();
        }

        /* 更新用例文件。 */
        /* Update case files. */
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
            $file->title = $file->name;

            unset($file->id, $file->name, $file->realPath, $file->webPath, $file->url);
            $file->objectID = $caseID;
            $file->pathname = $newFileName;
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
    }

    /**
     * 忽略用例库用例的更新。
     * Ignore case changed in lib.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function ignoreLibcaseChange(int $caseID)
    {
        $case = $this->testcase->fetchBaseInfo($caseID);
        $this->dao->update(TABLE_CASE)->set('fromCaseVersion')->eq($case->version)->where('id')->eq($caseID)->exec();
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
    }

    /**
     * Confirm story changes.
     *
     * @param  int    $caseID
     * @param  bool   $reload
     * @access public
     * @return void
     */
    public function confirmStoryChange(int $caseID, bool $reload = true)
    {
        $case = $this->testcase->fetchBaseInfo($caseID);
        if($case->story)
        {
            $story = $this->loadModel('story')->fetchBaseInfo($case->story);
            if($story->version != $case->storyVersion)
            {
                $this->dao->update(TABLE_CASE)->set('storyVersion')->eq($story->version)->where('id')->eq($caseID)->exec();
                $this->loadModel('action')->create('case', $caseID, 'confirmed', '', $story->version);
            }
        }
        if($reload) return $this->send(array('load' => true));
    }

    /**
     * Batch confirm story change of cases.
     *
     * @access public
     * @return void
     */
    public function batchConfirmStoryChange()
    {
        $caseIdList = zget($_POST, 'caseIdList',  array());
        if($caseIdList)
        {
            $this->testcase->batchConfirmStoryChange($caseIdList);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
    }

    /**
     * 导出用例。
     * export cases.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $taskID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function export(int $productID, string $orderBy, int $taskID = 0, string $browseType = '')
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        $product = $this->loadModel('product')->getByID($productID);

        if($product->shadow)           $this->config->testcase->exportFields = str_replace('product,', '', $this->config->testcase->exportFields);
        if($product->type == 'normal') $this->config->testcase->exportFields = str_replace('branch,', '', $this->config->testcase->exportFields);
        if($product->type != 'normal') $this->lang->testcase->branch = $this->lang->product->branchName[$product->type];

        if($_POST)
        {
            $fields = $this->testcaseZen->getExportFields($product->type);
            $cases  = $this->testcase->getCasesToExport($this->post->exportType, $taskID, $orderBy, (int)$this->post->limit);
            $cases  = $this->testcaseZen->processCasesForExport($cases, $productID, $taskID);

            if($this->config->edition != 'open') list($fields, $cases) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $cases);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $cases);
            $this->post->set('kind', 'testcase');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName   = $this->lang->testcase->common;
        $browseType = isset($this->lang->testcase->featureBar['browse'][$browseType]) ? $this->lang->testcase->featureBar['browse'][$browseType] : '';

        if($taskID)
        {
            $task     = $this->loadModel('testtask')->fetchByID($taskID);
            $taskName = $task->name;
            $this->config->testcase->exportFields = str_replace('pri,', 'pri,assignedTo,', $this->config->testcase->exportFields);
        }

        $this->view->fileName        = $product->name . $this->lang->dash . ($taskID ? $taskName . $this->lang->dash : '') . $browseType . $fileName;
        $this->view->allExportFields = $this->config->testcase->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * 导出模板。
     * Export template.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function exportTemplate(int $productID)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if($_POST)
        {
            $num     = (int)$this->post->num;
            $product = $this->loadModel('product')->getByID($productID);

            $fields = $this->testcaseZen->getFieldsForExportTemplate($product->type);
            $rows   = $this->testcaseZen->getRowsForExportTemplate($product, $num);

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
     * 从 csv 导入用例。
     * Import testcases from csv.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function import(int $productID, string $branch = '0')
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if($_POST)
        {
            /* 获取上传的文件。 */
            /* Get file. */
            $file = $this->loadModel('file')->getUpload('file');
            if(empty($_FILES))  return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));
            if(empty($file[0])) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileNotEmpty));

            $file = $file[0];
            if(!$file || (isset($file['extension']) && $file['extension'] != 'csv')) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));

            /* 移动上传的文件。 */
            /* Move file. */
            $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
            move_uploaded_file($file['tmpname'], $fileName);

            /* 获取上传文件中的用例字段。 */
            /* Get imported fields of the testcase. */
            $fields = $this->testcase->getImportFields($productID);
            $fields = array_flip($fields);

            /* 获取字段列值。 */
            /* Get column key. */
            $columnKey = $this->testcaseZen->processImportColumnKey($fileName, $fields);

            /* 如果字段列少于3个，或者编码非utf-8，再次计算字段列值。 */
            /* If there are less than 3 column keys, or if the encoding is not utf-8, process the column key again. */
            if(count($columnKey) <= 3 || $this->post->encode != 'utf-8')
            {
                /* 转换编码. */
                /* Convert Encoding. */
                $encode = $this->post->encode != "utf-8" ? $this->post->encode : 'gbk';
                $fc     = file_get_contents($fileName);
                $fc     = helper::convertEncoding($fc, $encode, 'utf-8');
                file_put_contents($fileName, $fc);

                /* 获取字段列值。 */
                /* Get column key. */
                $columnKey = $this->testcaseZen->processImportColumnKey($fileName, $fields);

                if(count($columnKey) == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorEncode));
            }

            $this->session->set('fileImport', $fileName);

            return $this->send(array('result' => 'success', 'load' => inlink('showImport', "productID={$productID}&branch={$branch}"), 'closeModal' => true));
        }

        $this->display();
    }

    /**
     * Import case from lib.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  int        $libID
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function importFromLib(int $productID, string $branch = '0', int $libID = 0, string $orderBy = 'id_desc', string $browseType = '', int $queryID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        /* 获取用例库，设置用例库 id 和分支。 */
        /* Get libraries, set lib id and branch. */
        $libraries = $this->loadModel('caselib')->getLibraries();
        if(empty($libraries)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->testcase->noLibrary)));
        if(empty($libID) || !isset($libraries[$libID])) $libID = key($libraries);
        if($branch == '') $branch = 0;

        if($_POST)
        {
            list($cases, $steps, $files, $hasImported) = $this->testcaseZen->buildDataForImportFromLib($productID, $branch, $libID);

            $this->loadModel('action');
            $errors        = '';
            $importedCases = array();
            foreach($cases as $oldCaseID => $case)
            {
                $this->config->testcase->create->requiredFields = strpos(",{$this->config->testcase->create->requiredFields},", ',module,') !== false ? ',module,' : '';
                $this->testcase->doCreate($case);
                if(dao::isError())
                {
                    $errors .= "{$oldCaseID}:";
                    foreach(dao::getError() as $fieldErrors) $errors .= implode(',', $fieldErrors);
                    continue;
                }
                $caseID = $this->dao->lastInsertID();
                $this->executeHooks($caseID);
                $this->testcase->syncCase2Project($case, $caseID);
                $this->testcase->importSteps($caseID, zget($steps, $oldCaseID, array()));
                $this->testcase->importFiles($caseID, zget($files, $oldCaseID, array()));

                $importedCases[] = $oldCaseID;

                $this->action->create('case', $caseID, 'fromlib', '', $libID);
            }

            if(!empty($errors)) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$errors}');"));
            if(!empty($importedCases)) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->testcase->importedFromLib, count($importedCases), implode(',', $importedCases)), 'load' => true));
            if(!empty($hasImported) && is_string($hasImported)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->testcase->importedCases, trim($hasImported, ',')), 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true));
        }

        /* 设置菜单。 */
        /* Set menu. */
        $this->testcaseZen->setMenu((int)$this->session->project, 0, $productID, $branch);

        $browseType = strtolower($browseType);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $recPerPage, $pageID);

        $cases = $this->testcase->getCanImportCases($productID, $libID, $orderBy, $pager, $browseType, (int)$queryID);

        /* 展示变量. */
        /* Show the variables. */
        $this->testcaseZen->assignForImportFromLib($productID, $branch, $libID, $orderBy, $queryID, $libraries, $projectID, $cases);

        $this->view->title      = $this->lang->testcase->common . $this->lang->hyphen . $this->lang->testcase->importFromLib;
        $this->view->libraries  = $libraries;
        $this->view->cases      = $cases;
        $this->view->libModules = $this->tree->getOptionMenu($libID, 'caselib');
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;
        $this->display();
    }

    /**
     * 展示导入的数据。
     * Show import data.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert     0 is covered old, 1 is insert new.
     * @access public
     * @return void
     */
    public function showImport(int $productID, string $branch = '0', int $pagerID = 1, int $maxImport = 0, string $insert = '')
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        /* Get file information. */
        $file    = $this->session->fileImport;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if($_POST)
        {
            $cases = $this->testcaseZen->buildCasesForShowImport($productID);
            $cases = $this->testcaseZen->checkCasesForShowImport($cases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $cases = $this->testcaseZen->importCases($cases);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->testcaseZen->responseAfterShowImport($productID, $branch, $maxImport, $tmpFile);
        }

        $this->testcaseZen->setMenu((int)$this->session->project, 0, $productID, $branch);

        /* Get imported data. */
        if(!empty($maxImport) && file_exists($tmpFile))
        {
            $data = unserialize(file_get_contents($tmpFile));
        }
        else
        {
            $pagerID = 1;
            list($data, $stepVars) = $this->testcaseZen->getImportedData($productID, $file);
            file_put_contents($tmpFile, serialize($data));
        }

        $this->testcaseZen->assignShowImportVars($productID, $branch, $data['caseData'], isset($stepVars) ? $stepVars : 0, $pagerID, $maxImport);

        $this->view->title      = $this->lang->testcase->common . $this->lang->hyphen . $this->lang->testcase->showImport;
        $this->view->cases      = $this->testcase->getByProduct($productID);
        $this->view->productID  = $productID;
        $this->view->branch     = $branch;
        $this->view->product    = $this->product->getByID($productID);
        $this->view->maxImport  = $maxImport;
        $this->view->dataInsert = $insert;
        $this->display();
    }

    /**
     * 导入用例到用例库。
     * Import cases to library.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function importToLib(int $caseID = 0)
    {
        if(!empty($_POST))
        {
            $libID = (int)$this->post->lib;
            if(empty($libID))
            {
                dao::$errors['lib'][] = sprintf($this->lang->error->notempty, $this->lang->testcase->caselib);
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            list($cases, $steps, $files) = $this->testcaseZen->buildDataForImportToLib($caseID, $libID);

            $this->testcase->importToLib($cases, $steps, $files);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!empty($caseID)) return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true, 'closeModal' => true));
        }
        $this->view->title     = $this->lang->testcase->importToLib;
        $this->view->libraries = $this->loadModel('caselib')->getLibraries();
        $this->display();
    }

    /**
     * 用例产生的 bugs。
     * Case bugs.
     *
     * @param  int    $runID
     * @param  int    $caseID
     * @param  int    $version
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function bugs(int $runID, int $caseID = 0, int $version = 0, string $orderBy = 'id_asc')
    {
        $this->view->title   = $this->lang->testcase->bugs;
        $this->view->bugs    = $this->loadModel('bug')->getCaseBugs($runID, $caseID, $version, $orderBy);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy = $orderBy;
        $this->display();
    }

    /**
     * 查看自动化脚本。
     * Show script.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function showScript(int $caseID)
    {
        $case = $this->testcase->getByID($caseID);
        if($case) $case->script = html_entity_decode($case->script);

        $this->view->case = $case;
        $this->display();
    }

    /**
     * 自动化设置。
     * Automation test setting.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function automation(int $productID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if($_POST)
        {
            /* 设置语言项以便 form 类检查必填项时输出正确的字段名。*/
            /* Set language item for form class to check required fields. */
            $this->loadModel('zanode');
            $this->lang->testcase->scriptPath = $this->lang->zanode->scriptPath;
            $this->lang->testcase->node       = $this->lang->zanode->common;

            $automation = form::data($this->config->testcase->form->automation)
                ->add('createdBy', $this->app->user->account)
                ->add('createddate', helper::now())
                ->get();

            $this->zanode->setAutomationSetting($automation);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->sendSuccess(array('load' => inlink('browse', "productID={$automation->product}")));
        }

        $this->view->title      = $this->lang->testcase->automation;
        $this->view->automation = $this->loadModel('zanode')->getAutomationByProduct($productID);
        $this->view->nodeList   = $this->zanode->getPairs();
        $this->view->products   = $productID ? array() : $this->product->getPairs('', 0, '', 'all');
        $this->view->productID  = $productID;

        $this->display();
    }

    /**
     * 获取待审核的用例数。
     * Ajax: Get amount of casees pending review.
     *
     * @access public
     * @return int
     */
    public function ajaxGetReviewAmount()
    {
        echo $this->testcaseTao->getReviewAmount();
    }

    /**
     * Create scene.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function createScene(int $productID, string $branch = '', int $moduleID = 0)
    {
        $this->testcaseZen->checkProducts(); // 如果不存在产品，则跳转到产品创建页面。

        if($_POST)
        {
            $this->lang->testcase->title = $this->lang->testcase->sceneTitle;
            $scene = form::data($this->config->testcase->form->createScene)->get();

            $this->testcase->createScene($scene);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 记录父场景 ID，便于下次创建场景时默认选中父场景。*/
            /* Record the ID of the parent scene, so that the parent scene will be selected by default when creating a scene next time. */
            helper::setcookie('lastCaseScene', $scene->parent);

            $locate = $this->session->caseList ?: inlink('browse', "productID={$scene->product}&branch={$scene->branch}&browseType=all&param={$scene->module}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->assignCreateSceneVars($productID, $branch, $moduleID);

        $this->display();
    }

    /**
     * Ajax get module scenes.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  int    $sceneID
     * @access public
     * @return void
     */
    public function ajaxGetScenes(int $productID, string $branch = 'all', int $moduleID = 0, int $sceneID = 0)
    {
        if(empty($branch)) $branch = 'all';
        $optionMenu = $this->testcase->getSceneMenu($productID, $moduleID, $branch, 0, $sceneID);

        $items = array();
        foreach($optionMenu as $optionID => $optionName) $items[] = array('text' => $optionName, 'value' => $optionID);

        return $this->send($items);
    }

    /**
     * 通过 ajax 方式获取模块选项。
     * Ajax get option menu.
     *
     * @param  int    $rootID
     * @param  int    $branch
     * @param  int    $rootModuleID
     * @param  string $returnType
     * @param  string $fieldID
     * @access public
     * @return void
     */
    public function ajaxGetOptionMenu(int $rootID, int|string $branch = 0, int $rootModuleID = 0, string $returnType = 'html', string $fieldID = '')
    {
        $optionMenu = $this->loadModel('tree')->getOptionMenu($rootID, 'case', $rootModuleID, $branch);

        if($returnType == 'mhtml')
        {
            $field  = $fieldID ? "modules[$fieldID]" : 'module';
            $output = html::select("$field", $optionMenu, '', "class='input'");
            die($output);
        }

        if($returnType == 'json') die(json_encode($optionMenu));

        $items = array();
        foreach($optionMenu as $optionID => $optionName) $items[] = array('text' => $optionName, 'value' => $optionID);

        return print(json_encode($items));
    }

    /**
     * Batch change scene.
     *
     * @param  int    $sceneID
     * @access public
     * @return void
     */
    public function batchChangeScene(int $sceneID)
    {
        $caseIdList = zget($_POST, 'caseIdList',  array());
        if($caseIdList)
        {
            $this->testcase->batchChangeScene($caseIdList, $sceneID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
    }

    /**
     * Change scene.
     *
     * @access public
     * @return void
     */
    public function changeScene()
    {
        $sourceID = $this->post->sourceID;
        $sceneID  = $this->post->targetID;
        if($sourceID == $sceneID) return $this->send(array('result' => 'success', 'load' => true));

        if(strpos($sourceID, 'case_') !== false)
        {
            $caseID  = str_replace('case_', '', $sourceID);
            $oldCase = $this->dao->select('scene')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
            if($oldCase->scene == $sceneID) return $this->send(array('result' => 'success', 'load' => true));

            $this->dao->update(TABLE_CASE)->set('scene')->eq($sceneID)->where('id')->eq($caseID)->exec();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newCase = new stdclass();
            $newCase->scene = $sceneID;

            $changes  = common::createChanges($oldCase, $newCase);
            $actionID = $this->loadModel('action')->create('case', (int)$caseID, 'edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $oldScene      = $this->testcase->getSceneByID((int)$sourceID);
        $newScene      = $this->testcase->getSceneByID((int)$sceneID);
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

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Delete a scene and its children.
     *
     * @param  int    $sceneID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteScene(int $sceneID, string $confirm = 'no')
    {
        $scene = $this->testcase->getSceneByID($sceneID);
        $count = $this->dao->select('COUNT(1) AS count')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('parent')->eq($sceneID)->fetch('count');
        if($count)
        {
            if($confirm != 'yes')
            {
                $confirmURL = inlink('deleteScene', "sceneID={$sceneID}&confirm=yes");
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->testcase->hasChildren}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$confirmURL}'});});"));
            }

            $this->loadModel('action');
            $scenes = $this->dao->select('id')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('path')->like($scene->path . '%')->fetchPairs();
            $this->dao->update(TABLE_SCENE)->set('deleted')->eq('1')->where('deleted')->eq(0)->andWhere('path')->like($scene->path . '%')->exec();
            foreach($scenes as $sceneID) $this->action->create('scene', $sceneID, 'deleted', '', actionModel::CAN_UNDELETED);
        }
        else
        {
            if($confirm != 'yes')
            {
                $confirmURL = inlink('deleteScene', "sceneID={$sceneID}&confirm=yes");
                $sceneDeleteConfirmMessage = sprintf($this->lang->testcase->confirmDeleteScene, $scene->title);
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$sceneDeleteConfirmMessage}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$confirmURL}'});});"));
            }

            $this->testcase->delete(TABLE_SCENE, $sceneID);
        }

        $locateLink = $this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$scene->product}");
        return $this->send(array('result' => 'success', 'load' => $locateLink));
    }

    /**
     * 编辑一个场景。
     * Edit a scene.
     *
     * @param  int    $sceneID
     * @access public
     * @return void
     */
    public function editScene(int $sceneID)
    {
        $oldScene = $this->testcase->getSceneByID($sceneID);
        if(!$oldScene)
        {
            $browseLink = $this->session->caseList ? $this->session->caseList : inlink('browse');
            return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound, 'load' => $browseLink));
        }

        if($_POST)
        {
            $this->lang->testcase->title = $this->lang->testcase->sceneTitle;
            $scene = form::data($this->config->testcase->form->editScene)->get();

            $this->testcase->updateScene($scene, $oldScene);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $sceneID));

            $locate = $this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$scene->product}&branch={$scene->branch}&browseType=all&param={$scene->module}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
        }

        $this->assignEditSceneVars($oldScene);
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
        $sourceID    = (int)$this->post->sourceID;
        $sourceOrder = (int)$this->post->sourceOrder;
        $targetID    = (int)$this->post->targetID;
        $targetOrder = (int)$this->post->targetOrder;
        $type        = $this->post->type;
        $module      = $this->post->module;

        $table    = $module == 'case' ? TABLE_CASE : TABLE_SCENE;
        $target   = $this->testcase->fetchByID($targetID, $module);
        $sortList = $this->dao->select('id,sort')->from($table)
            ->where($module == 'case' ? 'scene' : 'parent')->eq($module == 'case' ? $target->scene : $target->parent)
            ->andWhere('product')->eq($target->product)
            ->orderBy('sort_asc,id_desc')
            ->fetchPairs();

        $sortList    = array_keys($sortList);
        $sourceIndex = array_search($sourceID, $sortList);                                              // 获取拖动排序对象的索引。
        $sourceData  = array_splice($sortList, $sourceIndex, 1);                                        // 将拖动排序的对象从数组移除。
        $targetIndex = array_search($targetID, $sortList);                                              // 获取拖动后排序对象的索引。
        array_splice($sortList, $type == 'before' ? $targetIndex : ($targetIndex + 1), 0, $sourceData); // 将排序的对象放置在拖动后排序对象的前面或者后面。

        foreach($sortList as $sort => $objectID) $this->dao->update($table)->set('sort')->eq($sort)->where('id')->eq($objectID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 导出 xmind 格式的用例。
     * Export xmind.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function exportXmind(int $productID, int $moduleID, string $branch)
    {
        if($_POST)
        {
            $configList = $this->testcaseZen->buildMindConfig('xmind');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcase->saveMindConfig('xmind', $configList);

            $imoduleID = $this->post->imodule ? $this->post->imodule : 0;
            $context   = $this->testcaseZen->getMindExport('xmind', $productID, (int)$imoduleID, $branch);

            $productName = '';
            if(count($context['caseList']))
            {
                $productName = $context['caseList'][0]->productName;
            }
            else
            {
                $product     = $this->product->getByID($productID);
                $productName = $product->name;
            }

            $fileData = $this->testcaseZen->getXmindExportData($productID, $productName, $context);

            $this->fetch('file', 'sendDownHeader', array('fileName' => $productName, 'xmind', $fileData));
        }

        $product = $this->product->fetchByID($productID);

        $this->view->settings         = $this->testcase->getMindConfig('freemind');
        $this->view->productName      = $product->name;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, ($branch === 'all' || !isset($branches[$branch])) ? '0' : $branch);

        $this->display();
    }

    /**
     * 导出 xmind 格式的用例。
     * Export xmind.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function exportFreeMind(int $productID, int $moduleID, string $branch)
    {
        if($_POST)
        {
            $configList = $this->testcaseZen->buildMindConfig('freemind');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcase->saveMindConfig('freemind', $configList);

            $imoduleID = $this->post->imodule ? $this->post->imodule : 0;
            $context   = $this->testcaseZen->getMindExport('freemind', $productID, (int)$imoduleID, $branch);

            $productName = '';
            if(count($context['caseList']))
            {
                $productName = $context['caseList'][0]->productName;
            }
            else
            {
                $product     = $this->product->getByID($productID);
                $productName = $product->name;
            }

            $xmlDoc = $this->testcaseZen->createFreeMindXmlDoc($productID, $productName, $context);

            $xmlStr = $xmlDoc->saveXML();
            $this->fetch('file', 'sendDownHeader', array('fileName' => $productName, 'mm', $xmlStr));
        }

        $product = $this->product->fetchByID($productID);

        $this->view->settings         = $this->testcase->getMindConfig('freemind');
        $this->view->productName      = $product->name;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, ($branch === 'all' || !isset($branches[$branch])) ? '0' : $branch);

        $this->display();
    }

    /**
     * 获取 xmind 的配置项。
     * Get xmind config.
     *
     * @access public
     * @return void
     */
    public function getXmindConfig()
    {
        $result = $this->testcase->getMindConfig('xmind');
        $this->send($result);
    }

    /**
     * 获取 xmind 文件内容。
     * Get xmind content.
     *
     * @access public
     * @return void
     */
    public function getXmindImport()
    {
        if(!commonModel::hasPriv('testcase', 'importXmind')) $this->loadModel('common')->deny('testcase', 'importXmind');

        if($this->session->xmindImportType == 'xml')
        {
            $xmlPath = $this->session->xmindImport . '/content.xml';
            $results = str_replace(array('<', '>'), array('&lt;', '&gt;'), $this->testcase->getXmindImport($xmlPath));
        }
        else
        {
            $jsonPath = $this->session->xmindImport . '/content.json';
            $results  = file_get_contents($jsonPath);
        }

        echo $results;
    }

    /**
     * 导入 xmind.
     * Import xmind.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return void
     */
    public function importXmind(int $productID, string $branch)
    {
        if($_POST)
        {
            $file = $this->loadModel('file')->getUpload('file');
            if(empty($_FILES))  return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));
            if(empty($file[0])) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileNotEmpty));

            $file = $file[0];
            if(!$file || (isset($file['extension']) && $file['extension'] != 'xmind')) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat));

            /* 保存xmind配置。*/
            /* Sav xmind config. */
            $configList = $this->testcaseZen->buildMindConfig('xmind');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->testcase->saveMindConfig('xmind', $configList);

            /* 检查扩展名。*/
            /* Check extension name of file. */
            $extName = trim(strtolower(pathinfo($_FILES['file']['name'][0], PATHINFO_EXTENSION)));
            if($extName != 'xmind') return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileFormat));

            $result = $this->testcaseZen->parseUploadFile($productID, $branch);
            if(is_array($result)) return $this->send($result);

            return $this->send(array('result' => 'success', 'load' => $this->createLink('testcase', 'showXmindImport', "productID=$result&branch=$branch"), 'closeModal' => true));
        }

        $this->view->settings = $this->testcase->getMindConfig('xmind');
        $this->display();
    }

    /**
     * 保存导入的 xmind 文件。
     * Save imported xmind.
     *
     * @access public
     * @return void
     */
    public function saveXmindImport()
    {
        if(!commonModel::hasPriv('testcase', 'importXmind')) $this->loadModel('common')->deny('testcase', 'importXmind');

        if(!empty($_POST))
        {
            $productID    = (int)$this->post->productID;
            $branch       = $this->post->branch;
            $isInsert     = (bool)$this->post->insert;
            $sceneList    = json_decode($this->post->sceneList,    true);
            $testcaseList = json_decode($this->post->testcaseList, true);

            $cases  = $this->testcaseZen->buildCasesByXmind($productID, $branch, $testcaseList, $isInsert);
            $result = $this->testcase->saveXmindImport($sceneList, $cases);
            if($result['result'] == 'success') $result['load'] = $this->createLink('testcase', 'browse');
            return $this->send($result);
        }

        return $this->send(array('result' => 'fail', 'message' => $this->lang->errorSaveXmind));
    }

    /**
     * 展示导入的 xmind 内容。
     * Show imported xmind.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function showXmindImport(int $productID, string $branch)
    {
        if(!commonModel::hasPriv('testcase', 'importXmind')) $this->loadModel('common')->deny('testcase', 'importXmind');

        /* Set menu. */
        $this->testcaseZen->setMenu(0, 0, $productID, $branch);

        $product  = $this->product->getByID($productID);
        $branches = (isset($product->type) && $product->type != 'normal') ? $this->loadModel('branch')->getPairs($productID, 'active') : array();

        if($this->session->xmindImportType == 'xml')
        {
            $xmlPath = $this->session->xmindImport . '/content.xml';
            $results = str_replace(array('<', '>'), array('&lt;', '&gt;'), $this->testcase->getXmindImport($xmlPath));
        }
        else
        {
            $jsonPath = $this->session->xmindImport . '/content.json';
            $results  = file_get_contents($jsonPath);
        }
        $results = json_decode($results, true);

        $scenes = array();
        if(!empty($results[0]['rootTopic'])) $scenes = $this->testcaseZen->processScene($results[0]['rootTopic']);
        if(!empty($results['xmap-content']['sheet']['topic'])) $scenes = $this->testcaseZen->processScene($results['xmap-content']['sheet']['topic']);

        $this->view->title            = $this->lang->testcase->xmindImport;
        $this->view->settings         = $this->testcase->getMindConfig('xmind');
        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->scenes           = $scenes;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, ($branch === 'all' or !isset($branches[$branch])) ? '0' : $branch);

        $this->display();
    }

    /**
     * AJAX: 获取用例对应分支的模块。
     * AJAX: Get module items for case.
     *
     * @param  int    $productID
     * @param  int    $libID
     * @param  int    $branch
     * @param  int    $caseID
     * @access public
     * @return json
     */
    public function ajaxGetCanImportModuleItems(int $productID, int $libID, int $branch, int $caseID)
    {
        $moduleItems     = $this->testcase->getCanImportedModules($productID, $libID, $branch, 'items', array($caseID => $caseID));
        $caseModuleItmes = isset($moduleItems[$caseID]) ? $moduleItems[$caseID] : array();
        return print(json_encode($caseModuleItmes));
    }
}
