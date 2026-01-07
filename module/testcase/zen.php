<?php
declare(strict_types=1);
/**
 * The zen file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
class testcaseZen extends testcase
{
    /**
     * 检查是否有产品，如果没有则跳转到创建产品的页面。
     * Check products.
     *
     * @access protected
     * @return void
     */
    protected function checkProducts(): void
    {
        $objectID = 0;
        $tab      = $this->app->tab == 'project' || $this->app->tab == 'execution' ? $this->app->tab : 'qa';
        if($this->app->tab == 'project')
        {
            $objectID = $this->session->project;
        }
        elseif($this->app->tab == 'execution')
        {
            $objectID = $this->session->execution;
        }
        if(empty($this->products) && (helper::isAjaxRequest('zin') || helper::isAjaxRequest('fetch'))) $this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testcase&objectID=$objectID"));
    }

    /**
     * 设置列表页面的 cookie。
     * Set browse cookie.
     *
     * @param  int         $productID
     * @param  string|bool $branch
     * @param  string      $browseType
     * @param  int         $param
     * @access protected
     * @return void
     */
    protected function setBrowseCookie(int $productID, string|bool $branch, string $browseType = '', string $param = ''): void
    {
        helper::setcookie('preProductID', $productID);
        helper::setcookie('preBranch', $branch);

        if($this->cookie->preProductID != $productID || $this->cookie->preBranch != $branch)
        {
            $_COOKIE['caseModule'] = 0;
            helper::setcookie('caseModule', '0');
        }

        if($browseType == 'bymodule') helper::setcookie('caseModule', $param);
        if($browseType == 'bysuite')  helper::setcookie('caseSuite', $param);
    }

    /**
     * 获取列表页面的 branch 参数。
     * Get browse branch param.
     *
     * @param  string    $branch
     * @access protected
     * @return string
     */
    protected function getBrowseBranch(string $branch): string
    {
        if($branch === '') $branch = $this->cookie->preBranch;
        if($branch === '' || $branch === false) $branch = '0';

        return $branch;
    }

    /**
     * 设置列表页面的 session。
     * Set Browse session.
     *
     * @param  int         $productID
     * @param  string|bool $branch
     * @param  int         $moduleID
     * @param  string      $browseType
     * @param  string      $orderBy
     * @access protected
     * @return void
     */
    protected function setBrowseSession(int $productID, string|bool $branch, int $moduleID, string $browseType = '', string $orderBy = ''): void
    {
        if($browseType != 'bymodule') $this->session->set('caseBrowseType', $browseType);

        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('productID', $productID);
        $this->session->set('branch', $branch, 'qa');
        $this->session->set('moduleID', $moduleID);
        $this->session->set('browseType', $browseType);
        $this->session->set('orderBy', $orderBy);
        $this->session->set('testcaseOrderBy', '`sort` asc', $this->app->tab);
        $this->session->set('testcaseOrderBy', '`sort` asc');
    }

    /**
     * 设置列表页面的导航。
     * Set menu in browse.
     *
     * @param  int         $productID
     * @param  string|bool $branch
     * @param  int         $projectID
     * @access protected
     * @return array
     */
    protected function setBrowseMenu(int $productID, string|bool $branch, int $projectID = 0): array
    {
        /* 在不同的应用中，设置不同的导航。 */
        /* Set menu, save session. */
        if($this->app->tab == 'project')
        {
            if(empty($projectID)) $projectID = (int)$this->session->project;
            if(empty($projectID)) return array($productID, $branch);

            $linkedProducts = $this->product->getProducts($projectID, 'all', '', false);
            $this->products = count($linkedProducts) > 1 ? array('0' => $this->lang->product->all) + $linkedProducts : $linkedProducts;
            $productID      = count($linkedProducts) > 1 ? $productID : key($linkedProducts);

            $hasProduct = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('hasProduct');
            if(!$hasProduct) unset($this->config->testcase->search['fields']['product']);

            $branch = intval($branch) > 0 ? $branch : 'all';
            $this->loadModel('project')->setMenu($projectID);

            $this->view->products   = $this->products;
            $this->view->projectID  = $projectID;
            $this->view->hasProduct = $hasProduct;
        }
        else
        {
            $this->loadModel('qa')->setMenu($productID, $branch);
        }

        return array($productID, $branch);
    }

    /**
     * 设置菜单。
     * Set menu.
     *
     * @param  int        $projectID
     * @param  int        $executionID
     * @param  int        $productID
     * @param  string|int $branch
     * @access protected
     * @return void
     */
    protected function setMenu(int $projectID = 0, int $executionID = 0, int $productID = 0, string|int $branch = '')
    {
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($projectID);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($executionID);
        if($this->app->tab == 'qa') $this->testcase->setMenu($productID, $branch);

        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
    }

    /**
     * 构建搜索表单。
     * Build the search form.
     *
     * @param  int       $productID
     * @param  int       $queryID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function buildBrowseSearchForm(int $productID, int $queryID, int $projectID, string $actionURL): void
    {
        if($this->app->rawModule == 'testcase') $this->config->testcase->search['onMenuBar'] = 'yes';

        $searchProducts = $this->product->getPairs('', 0, '', 'all');
        $this->testcase->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $projectID);
    }

    /**
     * 展示从用例库导入的用例。
     * Assign the cases imported from the library.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $libID
     * @param  string    $orderBy
     * @param  int       $queryID
     * @param  array     $libraries
     * @param  array     $cases
     * @access protected
     * @return void
     */
    protected function assignForImportFromLib(int $productID, string $branch, int $libID, string $orderBy, int $queryID, array $libraries, int $projectID, array $cases): void
    {
        $product    = $this->loadModel('product')->getById($productID);
        $branches   = array();
        if($product->type != 'normal')
        {
            $this->loadModel('branch');
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($productID, 'active', $projectID);
        }

        foreach($branches as $branchID => $branchName) $canImportModules[$branchID] = $this->testcase->getCanImportedModules($productID, $libID, $branchID, 'items', $cases);
        if(empty($branches)) $canImportModules[0] = $this->testcase->getCanImportedModules($productID, $libID, 0, 'items', $cases);

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'importFromLib', "productID={$productID}&branch={$branch}&libID={$libID}&orderBy={$orderBy}&browseType=bySearch&queryID=myQueryID");

        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['onMenuBar'] = 'no';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->config->testcase->search['fields']['lib'] = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->caselib->all));
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($libID, 'caselib');

        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->libID            = $libID;
        $this->view->orderBy          = $orderBy;
        $this->view->queryID          = $queryID;
        $this->view->product          = $product;
        $this->view->branches         = $branches;
        $this->view->canImportModules = $canImportModules;
    }

    /**
     * 展示创建 testcase 的相关变量。
     * Show the variables associated with the creation testcase.
     *
     * @param int        $productID
     * @param string     $branch
     * @param int        $moduleID
     * @param string     $from
     * @param int        $param
     * @param int        $storyID
     * @access protected
     * @return void
     */
    protected function assignCreateVars(int $productID, string $branch = '', int $moduleID = 0, string $from = '', int $param = 0, int $storyID = 0)
    {
        $product = $this->product->getById($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        $projectID   = $from == 'project'   ? $param : $this->session->project;
        $executionID = $from == 'execution' ? $param : 0;
        $testcaseID  = $from && strpos('testcase|work|contribute', $from) !== false ? $param : 0;

        /* 设置分支。 */
        /* Set branches. */
        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            $objectID        = $this->app->tab == 'project' ? $this->session->project : $executionID;
            $productBranches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($productID), $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array('');
            $branch          = empty($branch) ? (string)key($branches) : $branch;
        }
        else
        {
            $branches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        /* 设置菜单。 */
        /* Set menu. */
        $executionID = $executionID ? $executionID : (int)$this->session->execution;
        $this->setMenu((int)$this->session->project, $executionID, $productID, $branch);

        /* 初始化用例数据。 */
        /* Initialize the testcase. */
        $case = $this->initTestcase($storyID, $testcaseID, $from == 'bug' ? $param : 0);
        if($case->story && $this->loadModel('story')->fetchByID($case->story)->product != $productID) $case->story = 0;

        /* 设置模块和需求。 */
        /* Set modules. */
        $this->assignModulesForCreate($productID, $moduleID, $branch, $case->story, $branches);

        $this->view->product        = $product;
        $this->view->projectID      = isset($projectID) ? $projectID : 0;
        $this->view->currentSceneID = $testcaseID > 0 ? $case->scene : (int)$this->cookie->lastCaseScene;
        $this->view->case           = $case;
        $this->view->executionID    = $executionID;
        $this->view->branch         = $branch;
        $this->view->branches       = $branches;
        $this->view->from           = $from;
        $this->view->param          = $param;
    }


    /**
     * 展示创建场景的相关变量。
     * Show the variables associated with the creation scene.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $moduleID
     * @access protected
     * @return void
     */
    protected function assignCreateSceneVars(int $productID, string $branch = '', int $moduleID = 0): void
    {
        $product = $this->product->getById($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        /* 获取分支键值对及当前分支。*/
        /* Get branch pairs and set current branch. */
        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            /* 在项目或执行中显示时只显示项目或执行关联的分支键值对。*/
            /* When showing in a project or execution, only the branch key-value pairs associated with the project or execution are shown. */
            $objectID        = $this->app->tab == 'project' ? $this->session->project : 0;
            $productBranches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($productID), $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();

            if(!empty($branches)) $branch = (string)key($branches);
        }
        else
        {
            $branches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        /* 设置菜单。 */
        /* Set menu. */
        $this->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $branch);

        $this->view->title    = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->newScene;
        $this->view->modules  = $this->tree->getOptionMenu($productID, 'case', 0, ($branch === 'all' || !isset($branches[$branch])) ? 'all' : (string)$branch);
        $this->view->scenes   = $this->testcase->getSceneMenu($productID, $moduleID, ($branch === 'all' || !isset($branches[$branch])) ? 'all' : (string)$branch);
        $this->view->moduleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastCaseModule;
        $this->view->parent   = (int)$this->cookie->lastCaseScene;
        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $branches;
    }

    /**
     * 处理编辑场景相关的变量。
     * Assign variables of edit a scene.
     *
     * @param  object    $oldScene
     * @access protected
     * @return void
     */
    protected function assignEditSceneVars(object $oldScene): void
    {
        $productID = $oldScene->product;
        $branchID  = (string)$oldScene->branch;
        $moduleID  = $oldScene->module;
        $parentID  = $oldScene->parent;

        /* 设置菜单。 */
        /* Set menu. */
        $this->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $branchID);

        $product = $this->product->getByID($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        /* Display status of branch. */
        $branches   = array();
        $branchList = $this->loadModel('branch')->getList($productID, 0, 'all');
        foreach($branchList as $branch) $branches[$branch->id] = $branch->name . ($branch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        if(!isset($branches[$branchID]))
        {
            $sceneBranch = $this->branch->getByID($branchID, $productID, '');
            if($sceneBranch) $branches[$branchID] = $sceneBranch->name . ($sceneBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        $modules = $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
        if(!isset($modules[$moduleID])) $modules += $this->tree->getModulesName($moduleID);

        $scenes = $this->testcase->getSceneMenu($productID, $moduleID, $branchID, 0, $oldScene->id);
        if(!isset($scenes[$parentID])) $scenes += $this->testcase->getScenesName((array)$parentID);

        $this->view->title    = $this->products[$productID] . $this->lang->hyphen . $this->lang->testcase->editScene;
        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = $branches;
        $this->view->modules  = $modules;
        $this->view->scenes   = $scenes;
        $this->view->scene    = $oldScene;
    }

    /**
     * 展示创建 testcase 的模块变量。
     * Show the modules associated with the creation testcase.
     *
     * @param  int       $productID
     * @param  int       $moduleID
     * @param  string    $branch
     * @param  int       $storyID
     * @param  array     $branches
     * @access protected
     * @return void
     */
    protected function assignModulesForCreate(int $productID, int $moduleID, string $branch, int $storyID, array $branches)
    {
        if($storyID)
        {
            $story = $this->loadModel('story')->getByID($storyID);
            if(empty($moduleID)) $moduleID = $story->module;
        }

        $currentModuleID = !$moduleID && $productID == (int)$this->cookie->lastCaseProduct ? (int)$this->cookie->lastCaseModule : $moduleID;

        $this->view->currentModuleID  = $currentModuleID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' || !isset($branches[$branch]) ? '0' : $branch);
        $this->view->sceneOptionMenu  = $this->testcase->getSceneMenu($productID, $moduleID, $branch === 'all' || !isset($branches[$branch]) ? '0' : $branch);
    }

    /**
     * 处理浏览页面的用例列表。
     * Process cases for browse page.
     *
     * @param  array  $cases
     * @access public
     * @return array
     */
    public function processCasesForBrowse(array $cases): array
    {
        if(!$cases) return [];

        foreach($cases as $case)
        {
            $case->caseID  = $case->id;
            $case->id      = 'case_' . $case->id;   // 给用例 ID 加前缀以防止和场景 ID 重复。Add prefix to case ID to prevent it from conflicting with scene ID.
            $case->parent  = 0;
            $case->isScene = false;
            $case->title   = htmlspecialchars_decode($case->title);
        }

        $caseScenes = array_unique(array_filter(array_column($cases, 'scene'))); // 获取用例的场景 ID。Get unique scene IDs from cases.
        if(!$caseScenes) return $cases;

        $pathList = $this->dao->select('path')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('id')->in($caseScenes)->fetchPairs(); // 获取场景的路径列表。Get path list of scenes.
        $idList   = array_unique(array_filter(explode(',', implode(',', $pathList))));
        $scenes   = $this->dao->select('*')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('id')->in($idList)->orderBy('grade_desc, sort_asc')->fetchAll('id'); // 获取场景列表。Get scene list.

        foreach($cases as $case)
        {
            if(!isset($scenes[$case->scene])) continue;

            $scene = $scenes[$case->scene];
            $scene->hasCase = true;

            $case->parent = $scene->id;
            $case->grade  = $scene->grade + 1;
            $case->path   = $scene->path . $case->id . ',';
        }

        $this->dao->setTable(TABLE_CASE);
        $fieldTypes = $this->dao->getFieldsType();

        foreach($scenes as $scene)
        {
            $scene = $this->testcase->buildSceneBaseOnCase($scene, $fieldTypes);
            $scene->scene = $scene->parent;
            if(isset($scene->hasCase))
            {
                if (isset($scenes[$scene->parent])) $scenes[$scene->parent]->hasCase = true; // 如果子场景有用例，那么父场景也有用例。If the child scene has a use case, the parent scene also has a use case.
            }
            else
            {
                $scene->hasCase = false; // hasCase 为 false 时在页面上显示“暂无用例”。If hasCase is false, show "No use case" on the page.
            }
        }

        return array_merge($scenes, $cases);
    }

    /**
     * 指定浏览用例页面的用例列表。
     * Assign cases for browse page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  int       $moduleID
     * @param  string    $caseType
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return void
     */
    protected function assignCasesForBrowse(int $productID, string $branch, string $browseType, int $queryID, int $moduleID, string $caseType, string $orderBy, int $recTotal, int $recPerPage, int $pageID, string $from = 'testcase'): void
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'caseID') !== false) $sort = str_replace('caseID', 'id', $sort);

        $cases = $this->testcase->getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $caseType, 'no', $sort, $pager, $from);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->testcase->appendData($cases);

        if($this->config->edition != 'open')
        {
            $caseRelatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($cases), 'testcase', 'byRelation', true);
            foreach($cases as $caseID => $case) $case->relatedObject = zget($caseRelatedObjectList, $caseID, 0);
        }

        $this->view->cases   = $this->processCasesForBrowse($cases);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
    }

    /**
     *
     * 指定模块树。
     * Assign module tree in browse page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function assignModuleTreeForBrowse(int $productID, string $branch, int $projectID): void
    {
        if($projectID && empty($productID))
        {
            $this->view->moduleTree = $this->tree->getCaseTreeMenu($projectID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $this->view->moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $projectID, 'productID' => $productID), $branch);
        }
    }

    /**
     *
     * 指定产品和分支。
     * Assign product and branch.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function assignProductAndBranchForBrowse(int $productID, string $branch, int $projectID = 0): void
    {
        /* 根据产品类型判断是否展示分支，获取分支选项信息和带标签的分支选项信息。*/
        /* Judge whether to show branch according to the type of product, get branch option and branch tag option. */
        $product = $this->product->getByID($productID);

        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product && $product->type != 'normal')
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

        $this->view->productID       = $productID;
        $this->view->productName     = zget($this->products, $productID, '');
        $this->view->product         = $product;
        $this->view->branch          = (!empty($product) and $product->type != 'normal') ? $branch : 0;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
    }

    /**
     * 指定变量。
     * Assign variables.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $projectID
     * @param  int       $param
     * @param  int       $moduleID
     * @param  int       $suiteID
     * @param  string    $caseType
     * @access protected
     * @return void
     */
    protected function assignForBrowse(int $productID, string $branch, string $browseType, int $projectID, int $param, int $moduleID, int $suiteID, string $caseType): void
    {
        $showModule = !empty($this->config->testcase->browse->showModule) ? $this->config->testcase->browse->showModule : '';
        $tree       = $moduleID ? $this->tree->getByID($moduleID) : '';

        $this->view->title       = zget($this->products, $productID, '') . $this->lang->hyphen . $this->lang->testcase->common;
        $this->view->projectID   = $projectID;
        $this->view->projectType = !empty($projectID) ? $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('model') : '';
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->moduleID    = $moduleID;
        $this->view->moduleName  = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->suiteID     = $suiteID;
        $this->view->caseType    = $caseType;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->modules     = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch == 'all' ? '0' : $branch);
        $this->view->iscenes     = $this->testcase->getSceneMenu($productID, $moduleID);
        $this->view->suiteList   = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : array();
        $this->view->libraries   = $this->loadModel('caselib')->getLibraries();
        $this->view->stories     = array('') + $this->loadModel('story')->getPairs($productID);
        $this->view->automation  = $this->loadModel('zanode')->getAutomationByProduct($productID);

        if($projectID)
        {
            $productPairs = $this->product->getProductPairsByProject($projectID);

            $this->view->switcherParams   = "projectID={$projectID}&productID={$productID}&currentMethod=testcase";
            $this->view->switcherText     = zget($productPairs, $productID, $this->lang->product->all);
            $this->view->project          = $this->loadModel('project')->getByID($projectID);
            $this->view->switcherObjectID = $productID;
        }
    }

    /**
     * 处理更新请求数据。
     * Processing request data.
     *
     * @param  form         $formData
     * @param  object       $oldBug
     * @access protected
     * @return object|false
     */
    protected function prepareEditExtras(form $formData, object $oldCase): object|false
    {
        foreach($formData->data->expects as $key => $value)
        {
            if(!empty($value) && empty($formData->data->steps[$key]))
            {
                dao::$errors['message'][] = sprintf($this->lang->testcase->stepsEmpty, $key);
                return false;
            }
        }

        if(!empty($_FILES['scriptFile'])) unset($_FILES['scriptFile']);

        $result = $this->getStatusForUpdate($oldCase);
        if(!$result || !is_array($result)) return $result;

        list($stepChanged, $status) = $result;

        $case = $formData->add('id', $oldCase->id)
            ->add('version', $stepChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->add('stepChanged', $stepChanged)
            ->setForce('status', $status)
            ->setDefault('story,branch', 0)
            ->setDefault('stage', '')
            ->setDefault('deleteFiles', array())
            ->setDefault('storyVersion', $oldCase->storyVersion)
            ->setDefault('script', $oldCase->script)
            ->setIF($formData->data->story != false && $formData->data->story != $oldCase->story, 'storyVersion', $this->loadModel('story')->getVersion((int)$formData->data->story))
            ->setIF(!$formData->data->linkCase, 'linkCase', '')
            ->setIF($formData->data->auto == 'auto' && $formData->data->script, 'script', htmlentities($formData->data->script))
            ->setIF($formData->data->auto == 'no', 'script', '')
            ->join('stage', ',')
            ->join('linkCase', ',')
            ->cleanInt('story,product,branch,module')
            ->skipSpecial('script')
            ->remove('files,labels,scriptFile,scriptName')
            ->removeIF($formData->data->auto == 'auto' && !$formData->data->script, 'script')
            ->get();
        if(!empty($oldCase->lib) && empty($oldCase->product) && !empty($_POST['lib'])) $case->lib = $this->post->lib;

        return $case;
    }

    /**
     * 提前处理用例数据。
     * Preprocess case.
     *
     * @param  object    $case
     * @access protected
     * @return object
     */
    protected function preProcessForEdit(object $case): object
    {
        /* 初始化用例步骤。*/
        /* Unit the steps of case. */
        if(empty($case->steps))
        {
            $step = new stdclass();
            $step->type   = 'step';
            $step->desc   = '';
            $step->name   = '';
            $step->expect = '';
            $case->steps[] = $step;
        }

        return $case;
    }

    /**
     * 设置编辑用例库用例的导航。
     * Set menu for editing lib case.
     *
     * @param  object    $case
     * @param  array     $libraries
     * @access protected
     * @return void
     */
    protected function setMenuForLibCaseEdit(object $case, array $libraries): void
    {
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        else
        {
            $this->loadModel('caselib')->setLibMenu($libraries, $case->lib);
        }
    }

    /**
     * 设置编辑用例的导航。
     * Set menu for editing case.
     *
     * @param  object    $case
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function setMenuForCaseEdit(object $case, int $executionID): void
    {
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($case->project);
            $this->view->projectID = $case->project;
        }

        if($this->app->tab == 'execution')
        {
            if(!$executionID) $executionID = $case->execution;
            $this->loadModel('execution')->setMenu($executionID);
            $this->view->executionID = $executionID;
        }

        if($this->app->tab == 'qa') $this->testcase->setMenu($case->product, $case->branch);
    }

    /**
     * 指定编辑用例库用例的数据。
     * Assign data for editint lib case.
     *
     * @param  object    $case
     * @param  array     $libraries
     * @access protected
     * @return void
     */
    protected function assignForEditLibCase(object $case, array $libraries): void
    {
        $this->view->title            = "CASE #$case->id $case->title - " . $libraries[$case->lib];
        $this->view->isLibCase        = true;
        $this->view->libraries        = $libraries;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($case->lib, 'caselib', 0);
        $this->view->libName          = $libraries[$case->lib];
        $this->view->libID            = $case->lib;
    }

    /**
     * 指定编辑用例的数据。
     * Assign for editing case.
     *
     * @param  object    $case
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignForEditCase(object $case, int $executionID): void
    {
        $product = $this->product->getByID($case->product);
        if(!isset($this->products[$case->product])) $this->products[$case->product] = $product->name;

        $this->view->title     = $this->products[$case->product] . $this->lang->hyphen . $this->lang->testcase->edit;
        $this->view->isLibCase = false;
        $this->view->product   = $product;
        $this->view->products  = $this->products;
        $this->view->branch    = $this->cookie->preBranch;

        $this->assignBranchForEdit($case, $executionID);
        $this->assignModuleOptionMenuForEdit($case);
    }

    /**
     * 指定编辑用例的分支。
     * Assign branch data for editing case.
     *
     * @param  object  $case
     * @param  int     $executionID
     * @access private
     * @return void
     */
    private function assignBranchForEdit(object $case, int $executionID): void
    {
        $objectID = 0;
        if($this->app->tab == 'execution') $objectID = $executionID;
        if($this->app->tab == 'project')   $objectID = $case->project;

        $branches = $this->loadModel('branch')->getList($case->product, $objectID, 'all');
        $branchTagOption = array();
        foreach($branches as $branchInfo) $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');

        if(!isset($branchTagOption[$case->branch]))
        {
            $caseBranch = $this->branch->getByID($case->branch, $case->product, '');
            $branchTagOption[$case->branch] = $case->branch == BRANCH_MAIN ? $caseBranch : ($caseBranch->name . ($caseBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
        }

        $this->view->branchTagOption = $branchTagOption;
    }

    /**
     * 指定编辑用的模块选项。
     * Assign module option menu for editing case.
     *
     * @param  object  $case
     * @access private
     * @return void
     */
    private function assignModuleOptionMenuForEdit(object $case): void
    {
        $moduleOptionMenu = $this->tree->getOptionMenu($case->product, 'case', 0, (string)$case->branch);

        if($case->lib && $case->fromCaseID)
        {
            $lib        = $this->loadModel('caselib')->getByID($case->lib);
            $libModules = $this->tree->getOptionMenu($case->lib, 'caselib');
            foreach($libModules as $moduleID => $moduleName)
            {
                if($moduleID == 0) continue;
                $moduleOptionMenu[$moduleID] = $lib->name . $moduleName;
            }
        }

        if(!isset($moduleOptionMenu[$case->module])) $moduleOptionMenu += $this->tree->getModulesName((array)$case->module);

        $this->view->moduleOptionMenu = $moduleOptionMenu;
    }

    /**
     * 指定编辑用例的其他数据。
     * Assign other data for editing case.
     *
     * @param  int       $productID
     * @param  object    $case
     * @param  array     $testtasks
     * @access protected
     * @return void
     */
    protected function assignForEdit(int $productID, object $case, array $testtasks): void
    {
        $sceneOptionMenu = $this->testcase->getSceneMenu($productID, $case->module);
        if(!isset($sceneOptionMenu[$case->scene])) $sceneOptionMenu += $this->testcase->getScenesName((array)$case->scene);

        $forceNotReview = $this->testcase->forceNotReview();
        if($forceNotReview) unset($this->lang->testcase->statusList['wait']);

        $this->view->case            = $case;
        $this->view->testtasks       = $testtasks;
        $this->view->forceNotReview  = $forceNotReview;
        $this->view->sceneOptionMenu = $sceneOptionMenu;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->actions         = $this->loadModel('action')->getList('case', $case->id);
    }

    /**
     * 指定批量编辑用例的数据。
     * Assign for editing case.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $type
     * @param  array     $cases
     * @access protected
     * @return void
     */
    protected function assignForBatchEdit(int $productID, string $branch, string $type, array $cases): void
    {
        list($productIdList, $libIdList) = $this->assignTitleForBatchEdit($productID, $branch, $type, $cases);

        /* 设置模块。 */
        /* Set modules. */
        $modules         = array();
        $branchProduct   = false;
        $branchTagOption = array();
        $products        = $this->product->getByIdList($productIdList);
        $branches        = array(0 => 0);
        foreach($products as $product)
        {
            if($product->type != 'normal')
            {
                $branches = $this->loadModel('branch')->getList($product->id, 0, 'all');
                foreach($branches as $branchInfo) $branchTagOption[$product->id][$branchInfo->id] = "/{$product->name}/{$branchInfo->name}" . ($branchInfo->status == 'closed' ? " ({$this->lang->branch->statusList['closed']})" : '');
                $branchProduct = true;
            }

            $modulePairs = $this->tree->getOptionMenu($product->id, 'case', 0, array_keys($branches));
            foreach($modulePairs as $branchID => $branchModules)
            {
                $modules['case'][$product->id][$branchID] = array();
                foreach($branchModules as $moduleID => $module) $modules['case'][$product->id][$branchID][] = array('text' => $module, 'value' => $moduleID);
            }
        }
        foreach($libIdList as $libID)
        {
            $libModules                = $this->tree->getOptionMenu($libID, 'caselib');
            $modules['lib'][$libID][0] = array();
            foreach($libModules as $moduleID => $module) $modules['lib'][$libID][0][] = array('text' => $module, 'value' => $moduleID);
        }

        if($this->app->tab == 'project') $branchTagOption = $this->loadModel('branch')->getPairsByProjectProduct($this->session->project, $productID);

        /* 指派模块和场景。 */
        /* Assign modules and scenes. */
        $this->assignModuleAndSceneForBatchEdit($productID, $branch, $branches, $cases, $modules);

        /* 设置自定义字段。 */
        /* Set custom fields. */
        foreach(explode(',', $this->config->testcase->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->batchEditFields;

        $this->view->branchTagOption = $branchTagOption;
        $this->view->products        = $products;
        $this->view->branchProduct   = $branchProduct;
    }

    /**
     * 指派用例库下批量编辑用例的数据。
     * Assign for batch editing case in caselib.
     *
     * @param  int       $libID
     * @access protected
     * @return void
     */
    protected function assignLibForBatchEdit(int $libID): void
    {
        $libraries = $this->loadModel('caselib')->getLibraries();
        $this->loadModel('caselib')->setLibMenu($libraries, $libID);
        $this->view->libID = $libID;
    }

    /**
     * 指定批量编辑用例的页面标题。
     * Assign title for editing case.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $type
     * @param  array     $cases
     * @access protected
     * @return array
     */
    protected function assignTitleForBatchEdit(int $productID, string $branch, string $type, array $cases): array
    {
        $productIdList = array();
        $libIdList     = array();
        /* 指派用例库用例。 */
        /* Assign lib cases. */
        if($type == 'lib')
        {
            $libID     = $productID;
            $libIdList = array($libID);
            $libraries = $this->loadModel('caselib')->getLibraries();

            /* Remove story custom fields from caselib */
            $this->config->testcase->list->customBatchEditFields   = str_replace(',story', '', $this->config->testcase->list->customBatchEditFields);
            $this->config->testcase->custom->batchEditFields = str_replace(',story', '', $this->config->testcase->custom->batchEditFields);

            /* Set caselib menu. */
            $this->caselib->setLibMenu($libraries, $libID);

            $this->view->title = $libraries[$libID] . $this->lang->hyphen . $this->lang->testcase->batchEdit;
        }
        /* 指派测试用例。 */
        /* Assign test cases. */
        elseif($productID)
        {
            $product       = $this->product->getByID($productID);
            $productIdList = array($productID);

            $this->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $branch);

            $this->view->title = $product->name . $this->lang->hyphen . $this->lang->testcase->batchEdit;
        }
        /* 指派地盘标签下的用例。 */
        /* Assign cases of my tab. */
        else
        {
            foreach($cases as $case)
            {
                if($case->lib == 0) $productIdList[$case->product] = $case->product;
                if($case->lib > 0) $libIdList[$case->lib] = $case->lib;
            }

            $this->view->title = $this->lang->testcase->batchEdit;
        }
        return array($productIdList, $libIdList);
    }

    /**
     * 指定批量编辑用例的模块和场景标题。
     * Assign modules and scenes for editing case.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $branches
     * @param  array     $cases
     * @param  array     $modules
     * @access protected
     * @return void
     */
    protected function assignModuleAndSceneForBatchEdit(int $productID, string $branch, array $branches, array $cases, array $modules): void
    {
        $moduleScenes      = array();
        $moduleScenesPairs = array();
        $modulePairs       = array();
        $scenePairs        = array();
        foreach($cases as $case)
        {
            $case->story = $case->story ? $case->story : '';

            /* 设置用例模块。 */
            /* Set case module. */
            $objectID   = $case->lib > 0 ? $case->lib : $case->product;
            $objectType = $case->lib > 0 ? 'lib' : 'case';
            if(isset($modules[$objectType][$objectID][$case->branch]))
            {
                $modulePairs[$case->id] = $modules[$objectType][$objectID][$case->branch];
            }
            elseif(isset($modules[$objectType][$objectID]))
            {
                $modulePairs[$case->id] = $modules[$objectType][$objectID][0];
            }
            else
            {
                $module = $this->tree->getByID($case->module);
                $modulePairs[$case->id][] = array('text' => zget($module, 'name', ''), 'value' => $case->module);
            }

            /* 设置用例场景。 */
            /* Set case 场景. */
            if(!isset($moduleScenesPairs[$case->module]))
            {
                $moduleScenesPairs[$case->module] = array();
                $moduleScenes = $this->testcase->getSceneMenu($productID, $case->module, $branch !== 'all' && !isset($branches[$branch]) ? '0' : $branch);
                foreach($moduleScenes as $sceneID => $scene) $moduleScenesPairs[$case->module][] = array('text' => $scene, 'value' => $sceneID);
            }
            $scenePairs[$case->id][] = $moduleScenesPairs[$case->module];
            if(!isset($scenes[$case->scene])) $scenePairs[$case->id][] = array('text' => '/' . $this->testcase->fetchSceneName($case->scene), 'value' => $case->scene);
        }
        $this->view->scenePairs      = $scenePairs;
        $this->view->modulePairs     = $modulePairs;
    }

    /**
     * 指派导入的数据。
     * Assign show imported data.
     *
     * @param int        $productID
     * @param string     $branch
     * @param array      $caseData
     * @param int        $stepVars
     * @param int        $pagerID
     * @param int        $maxImport
     * @access protected
     * @return void
     */
    protected function assignShowImportVars(int $productID, string $branch, array $caseData, int $stepVars, int $pagerID, int $maxImport)
    {
        if(empty($caseData)) return $this->sendError($this->lang->error->noData, $this->createLink('testcase', 'browse', "productID={$productID}&branch={$branch}"));

        /* 设置模块。 */
        /* Set modules. */
        $modules       = array();
        $branches      = $this->loadModel('branch')->getPairs($productID, 'active');
        $branchModules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, empty($branches) ? array(0) : array_keys($branches));

        foreach($branchModules as $branchID => $moduleList)
        {
            $modules[$branchID] = array();
            foreach($moduleList as $moduleID => $moduleName)
            {
                $modules[$branchID][$moduleID] = $moduleName;
            }
        }

        /* 如果导入的用例数大于最大导入数，则在最大导入时截取。 */
        /* If the number of imported cases is greater than max import, intercept at max import. */
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

                $this->display();
                exit;
            }

            $allPager = ceil($allCount / $maxImport);
            $caseData = array_slice($caseData, ($pagerID - 1) * $maxImport, $maxImport, true);
        }
        if(empty($caseData)) return $this->sendError($this->lang->error->noData, $this->createLink('testcase', 'browse', "productID={$productID}&branch={$branch}"));

        /* 判断输入的用例是否超出限制，并设定session。 */
        /* Judge whether the imported cases is too large and set session. */
        $countInputVars  = count($caseData) * 12 + $stepVars;
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $this->view->modules    = $modules;
        $this->view->caseData   = $caseData;
        $this->view->branches   = $branches;
        $this->view->allCount   = $allCount;
        $this->view->allPager   = $allPager;
        $this->view->isEndPage  = $pagerID >= $allPager;
        $this->view->pagerID    = $pagerID;
    }

    /**
     * Add edit action.
     *
     * @param  int       $caseID
     * @param  string    $oldStatus
     * @param  string    $status
     * @param  array     $changes
     * @param  string    $comment
     * @access protected
     * @return void
     */
    protected function addEditAction(int $caseID, string $oldStatus, string $status, array $changes = array(), string $comment = ''): void
    {
        $this->loadModel('action');
        $action   = !empty($changes) ? 'Edited' : 'Commented';
        $actionID = $this->action->create('case', $caseID, $action, $comment);

        $this->action->logHistory($actionID, $changes);

        if($oldStatus != 'wait' && $status == 'wait') $this->action->create('case', $caseID, 'submitReview');
    }

    /**
     * 指定 testcase 查看的关联的变量。
     * Assign testcase related variables.
     *
     * @param  object    $case
     * @param  string    $from
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function assignCaseForView(object $case, string $from, int $taskID)
    {
        $case = $this->loadModel('story')->checkNeedConfirm($case);
        if($from == 'testtask')
        {
            $run = $this->loadModel('testtask')->getRunByCase($taskID, $case->id);
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
        $case = $this->testcase->appendCaseFails($case, $from, $taskID);
        $case = $this->processStepsForMindMap($case);

        $sceneOptionMenu = $this->testcase->getSceneMenu($case->product, $case->module);
        if(!isset($sceneOptionMenu[$case->scene])) $sceneOptionMenu += $this->testcase->getScenesName((array)$case->scene);

        $this->view->from       = $from;
        $this->view->taskID     = $taskID;
        $this->view->runID      = $from == 'testcase' ? 0 : $run->id;
        $this->view->case       = $case;
        $this->view->caseFails  = $case->caseFails;
        $this->view->modulePath = $this->tree->getParents($case->module);
        $this->view->caseModule = empty($case->module) ? '' : $this->tree->getById($case->module);
        $this->view->preAndNext = !isOnlybody() ? $this->loadModel('common')->getPreAndNextObject('testcase', $case->id) : '';
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('case', $case->id);
        $this->view->scenes     = $sceneOptionMenu;
    }

    /**
     * 指定批量创建用例的相关变量。
     * Assign for batch creating case.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $moduleID
     * @param  int       $storyID
     * @access protected
     * @return void
     */
    protected function assignForBatchCreate(int $productID, string $branch = '', int $moduleID = 0, int $storyID = 0)
    {
        $product = $this->product->getById($productID);

        /* 设置分支。 */
        /* Set branches. */
        if($this->app->tab == 'project' and $product->type != 'normal')
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

            $productBranches = $this->loadModel('execution')->getBranchByProduct(array($productID), $this->session->project, 'noclosed|withMain');
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairs($productID, 'active');
        }

        /* 设置自定义字段和展示字段。 */
        /* Set custom fields and show fields. */
        foreach(explode(',', $this->config->testcase->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->testcase->$field;
        }
        $showFields = sprintf($this->config->testcase->custom->batchCreateFields, $product->type);
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(",branch,", ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        /* 设置需求键对。 */
        /* Set story pairs. */
        $storyPairs  = $this->loadModel('story')->getProductStoryPairs($productID, $branch === 'all' ? 0 : $branch, array(), 'active,reviewing', 'id_desc', 50, '', 'story', false);
        $story       = $storyID ? $this->story->fetchByID($storyID) : '';
        $storyPairs += $storyID ? array($storyID => $story->id . ':' . $story->title) : array();
        if($storyID && empty($moduleID)) $moduleID = $story->module;

        $this->view->product         = $product;
        $this->view->branches        = $branches;
        $this->view->customFields    = $customFields;
        $this->view->showFields      = $showFields;
        $this->view->story           = $story;
        $this->view->storyPairs      = $this->story->addGradeLabel($storyPairs);
        $this->view->sceneOptionMenu = $this->testcase->getSceneMenu($productID, $moduleID, $branch === 'all' || !isset($branches[$branch]) ? '0' : $branch);
        $this->view->currentModuleID = $moduleID;
    }

    /**
     * 构建创建 testcase 页面数据。
     * Build form fields for creating testcase.
     *
     * @param  string    $from
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function buildCaseForCreate(string $from, int $param): object
    {
        $status = $this->getStatusForCreate();

        $case = form::data($this->config->testcase->form->create)
            ->add('status', $status)
            ->setIF($from == 'bug', 'fromBug', $param)
            ->setIF($from == 'project' && $param, 'project', $param)
            ->setIF($from == 'execution' && $param, 'execution', $param)
            ->setIF($from != 'project' && $this->app->tab == 'project',   'project',   $this->session->project)
            ->setIF($from != 'execution' && $this->app->tab == 'execution', 'execution', $this->session->execution)
            ->setIF($this->post->project, 'project', $this->post->project)       // API will post project param
            ->setIF($this->post->execution, 'execution', $this->post->execution) // API will post execution param
            ->setIF($this->post->auto, 'auto', 'auto')
            ->setIF($this->post->auto && $this->post->script, 'script', $this->post->script ? htmlentities($this->post->script) : '')
            ->setIF($this->post->story, 'storyVersion', $this->loadModel('story')->getVersion((int)$this->post->story))
            ->get();

        /* 如果用例产品是影子产品，同步用例到项目中。 */
        $product = $this->loadModel('product')->getById($case->product);
        if($product->shadow && empty($case->project))
        {
            $project = $this->loadModel('project')->getByShadowProduct($case->product);
            $case->project = $project->id;
        }

        /* 如果用例的项目不为空，且不启用迭代，同步用例到影子迭代中。 */
        if(!empty($case->project))
        {
            if(!isset($project)) $project = $this->loadModel('project')->fetchByID($case->project);
            if(!$project->multiple) $case->execution = $this->loadModel('execution')->getNoMultipleID($case->project);
        }
        return $case;
    }

    /**
     * 构建批量创建用例的数据。
     * Build cases for batch creating.
     *
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function buildCasesForBathcCreate(int $productID): array
    {
        $now            = helper::now();
        $account        = $this->app->user->account;
        $forceNotReview = $this->testcase->forceNotReview();
        $storyVersions  = array();
        $testcases      = form::batchData($this->config->testcase->form->batchCreate)->get();

        $projectID   = $this->app->tab == 'project' ? $this->session->project : 0;
        $executionID = 0;
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if(!$project->multiple) $executionID = $this->dao->select('id')->from(TABLE_PROJECT)->where('parent')->eq($projectID)->fetch('id');
        }

        foreach($testcases as $testcase)
        {
            $testcase->product      = $productID;
            $testcase->project      = $projectID;
            $testcase->execution    = $executionID;
            $testcase->openedBy     = $account;
            $testcase->openedDate   = $now;
            $testcase->status       = $forceNotReview || $testcase->review == 0 ? 'normal' : 'wait';
            $testcase->version      = 1;
            $testcase->storyVersion = isset($storyVersions[$testcase->story]) ? $storyVersions[$testcase->story] : 0;
            $testcase->stepType     = array();
            if($testcase->story && !isset($storyVersions[$testcase->story]))
            {
                if(count($storyVersions) == 0) $this->loadModel('story');
                $testcase->storyVersion = $this->story->getVersion((int)$testcase->story);
                $storyVersions[$testcase->story] = $testcase->storyVersion;
            }
            unset($testcase->review);

            list($testcase->steps, $testcase->stepType) = $this->testcase->processStepsOrExpects($testcase->steps);
            list($testcase->expects)                    = $this->testcase->processStepsOrExpects($testcase->expects);
        }
        return $testcases;
    }

    /**
     * 构建批量编辑用例的数据。
     * Build cases for batch editing.
     *
     * @param  array     $oldCases
     * @access protected
     * @return array
     */
    protected function buildCasesForBathcEdit(array $oldCases, array $oldSteps): array
    {
        $caseIdList = array_keys($oldCases);
        if(empty($caseIdList)) return array();

        $now            = helper::now();
        $account        = $this->app->user->account;
        $cases          = form::batchData($this->config->testcase->form->batchEdit)->get();
        $forceNotReview = $this->testcase->forceNotReview();
        foreach($cases as $caseID => $case)
        {
            $oldCase = $oldCases[$caseID];
            $case->id             = $caseID;
            $case->product        = $oldCase->product;
            $case->lastEditedBy   = $account;
            $case->lastEditedDate = $now;
            if(!isset($case->precondition)) $case->precondition = $oldCase->precondition;

            $versionChanged = false;
            if($case->title && $case->title != $oldCase->title) $versionChanged = true;
            if($case->precondition && $case->precondition != $oldCase->precondition) $versionChanged = true;

            list($case->steps, $case->stepType) = $this->testcase->processStepsOrExpects($case->steps);
            list($case->expects)                = $this->testcase->processStepsOrExpects($case->expects);

            $oldStep     = zget($oldSteps, $caseID, array());
            $stepChanged = $this->testcase->processStepsChanged($case, $oldStep);
            if($stepChanged && !$forceNotReview) $case->status = 'wait';

            if($stepChanged) $versionChanged = true;
            $case->version     = $versionChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version;
            $case->stepChanged = $stepChanged;
        }
        return $cases;
    }

    /**
     * 构建导入用例的数据。
     * Build cases for showing imported.
     *
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function buildCasesForShowImport(int $productID): array
    {
        /* 初始化变量。 */
        /* Initialize variables. */
        $now               = helper::now();
        $account           = $this->app->user->account;
        $cases             = form::batchData($this->config->testcase->form->showImport)->get();
        $forceNotReview    = $this->testcase->forceNotReview();
        $insert            = $this->post->insert;
        $caseIdList        = !$insert ? array_keys($this->post->title) : array();
        $oldCases          = $this->testcase->getByList($caseIdList);
        $oldSteps          = $this->testcase->fetchStepsByList($caseIdList);
        $storyVersionPairs = $this->loadModel('story')->getVersions($this->post->story);

        foreach($cases as $case)
        {
            list($case->steps, $case->stepType) = $this->testcase->processStepsOrExpects($case->steps);
            list($case->expects)                = $this->testcase->processStepsOrExpects($case->expects);

            /* 构建更新的用例. */
            /* Build updated case. */
            if(!empty($case->rawID) && !$insert)
            {
                $oldCase     = zget($oldCases, $case->rawID, new stdclass());
                $oldStep     = zget($oldSteps, $case->rawID, array());
                $stepChanged = $this->testcase->processStepsChanged($case, $oldStep);

                $case->id             = $case->rawID;
                $case->product        = $productID;
                $case->lastEditedBy   = $account;
                $case->lastEditedDate = $now;
                $case->version        = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
                if($case->story != $oldCase->story) $case->storyVersion = zget($storyVersionPairs, $case->story, 1);

                $changes = common::createChanges($oldCase, $case);
                if(!$changes && !$stepChanged) unset($cases[$case->rawID]);
            }
            /* 构建插入的用例. */
            /* Build inserted case. */
            else
            {
                $case->product    = $productID;
                $case->version    = 1;
                $case->openedBy   = $this->app->user->account;
                $case->openedDate = $now;
                $case->status     = !$forceNotReview ? 'wait' : 'normal';
                if($this->app->tab == 'project') $case->project = $this->session->project;
                if($case->story) $case->storyVersion = zget($storyVersionPairs, $case->story, 1);
            }
            $case->frequency = 1;
            unset($case->rawID);
        }

        return $cases;
    }

    /**
     * 根据 xmind 构建导入用例的数据。
     * Build imported cases by xmind data.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $caseList
     * @param  bool      $isInsert
     * @access protected
     * @return array
     */
    protected function buildCasesByXmind(int $productID, string $branch, array $caseList, bool $isInsert): array
    {
        $caseIdList     = array_filter(array_map(function($case){return zget($case, 'id', 0);}, $caseList));
        $now            = helper::now();
        $forceNotReview = $this->testcase->forceNotReview();
        $account        = $this->app->user->account;
        $oldCases       = $this->testcase->getByList($caseIdList);
        $oldSteps       = $this->testcase->fetchStepsByList($caseIdList);
        $branch         = (int)$branch;
        $modules        = $this->loadModel('tree')->getOptionMenu($productID, 'case');

        $cases = array();
        foreach($caseList as $caseData)
        {
            $moduleID = (int)$caseData['module'];
            $case     = new stdclass();
            $case->module       = isset($modules[$moduleID]) ? $moduleID : 0;
            $case->product      = $productID;
            $case->branch       = $branch;
            $case->title        = $caseData['name'];
            $case->pri          = $caseData['pri'];
            $case->precondition = $caseData['precondition'];
            $case->tmpPId       = $caseData['tmpPId'];
            $case->version      = 1;

            $case = $this->testcase->processCaseSteps($case, (object)$caseData);

            $caseID  = (int)zget($caseData, 'id', 0);
            $oldCase = zget($oldCases, $caseID, null);

            if(empty($oldCase) || $isInsert)
            {
                $case->type       = 'feature';
                $case->status     = !$forceNotReview ? 'wait' : 'normal';
                $case->openedBy   = $account;
                $case->openedDate = $now;
            }
            else
            {
                $oldStep     = zget($oldSteps, $caseID, array());
                $stepChanged = $this->testcase->processStepsChanged($case, $oldStep);

                $case->version     = $stepChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version;
                $case->stepChanged = $stepChanged;
                if($stepChanged && !$forceNotReview) $case->status = 'wait';

                $case->id             = $caseID;
                $case->lastEditedBy   = $account;
                $case->lastEditedDate = $now;
            }
            $cases[] = $case;
        }

        return $cases;
    }

    /**
     * 构建从用例库导入的数据。
     * Build data for importing from lib.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $libID
     * @access protected
     * @return array
     */
    protected function buildDataForImportFromLib(int $productID, string $branch, int $libID): array
    {
        /* 处理模块。 */
        /* Process modules. */
        $preModule = 0;
        $modules   = $this->post->module ? $this->post->module : array();
        foreach($modules as $caseID => $module)
        {
            if($module != 'ditto') $preModule = $module;
            if($module == 'ditto') $modules[$caseID] = $preModule;
        }

        /* 处理分支。 */
        /* Process branches. */
        $preBranch   = 0;
        $caseModules = array();
        $branches    = $this->post->branch;
        $caseIdList  = $this->post->caseIdList;
        if(!empty($branches))
        {
            foreach($branches as $caseID => $branch)
            {
                if($branch != 'ditto') $prevBranch = $branch;
                if($branch == 'ditto') $branches[$caseID] = $prevBranch;
                if(!isset($caseModules[$branch]) && in_array($caseID, $caseIdList) !== false) $caseModules[$branch] = $this->testcase->getCanImportedModules($productID, $libID, $branch, 'pairs', $caseIdList);
            }
        }
        else
        {
            $caseModules[$branch] = $this->testcase->getCanImportedModules($productID, $libID, $branch, 'pairs', $caseIdList);
        }

        /* 构建用例。 */
        /* Build cases. */
        $hasImported = '';
        $cases       = array();
        $steps       = array();
        $files       = array();
        $fromCases   = $this->testcase->getByList($caseIdList);
        $fromSteps   = $this->testcase->getRelatedSteps($caseIdList);
        $fromFiles   = $this->testcase->getRelatedFiles($caseIdList);
        foreach($caseIdList as $caseID)
        {
            if(!isset($fromCases[$caseID])) continue;

            $case = clone $fromCases[$caseID];
            $case->lib             = 0;
            $case->steps           = array();
            $case->expects         = array();
            $case->stepType        = array();
            $case->product         = $productID;
            $case->fromCaseID      = $case->id;
            $case->fromCaseVersion = $case->version;
            if(isset($modules[$case->id])) $case->module = $modules[$case->id];
            if(isset($branches[$case->id])) $case->branch = $branches[$case->id];
            unset($case->id);
            if(!$case->scriptedDate)   unset($case->scriptedDate);
            if(!$case->reviewedDate)   unset($case->reviewedDate);
            if(!$case->lastEditedDate) unset($case->lastEditedDate);
            if(!$case->lastRunDate)    unset($case->lastRunDate);

            $caseBranch = zget($case, 'branch', $branch);
            if(empty($caseModules[$caseBranch][$case->fromCaseID][$case->module]))
            {
                $hasImported .= "$case->fromCaseID,";
            }
            else
            {
                $cases[$caseID] = $case;
                $steps[$caseID] = zget($fromSteps, $caseID, array());
                $files[$caseID] = zget($fromFiles, $caseID, array());
            }
        }

        return array($cases, $steps, $files, $hasImported);
    }

    /**
     * 构建导入到用例库的数据。
     * Build data for import to lib.
     *
     * @param  int       $caseID
     * @param  int       $libID
     * @access protected
     * @return array
     */
    protected function buildDataForImportToLib(int $caseID, int $libID): array
    {
        $cases          = array();
        $steps          = array();
        $files          = array();
        $caseIdList     = !empty($caseID) ? $caseID : $this->post->caseIdList;
        $caseIdList     = str_replace('case_' , '', (string)$caseIdList);
        $caseIdList     = explode(',' , $caseIdList);
        $fromCases      = $this->testcase->getByList($caseIdList);
        $fromSteps      = $this->testcase->fetchStepsByList($caseIdList);
        $fromFiles      = $this->testcase->getRelatedFiles($caseIdList);
        $libCases       = $this->dao->select('*')->from(TABLE_CASE)->where('lib')->eq($libID)->andWhere('product')->eq(0)->andWhere('deleted')->eq('0')->fetchGroup('fromCaseID', 'id');
        $maxOrder       = $this->dao->select('max(`order`) as maxOrder')->from(TABLE_CASE)->where('deleted')->eq(0)->fetch('maxOrder');
        $maxModuleOrder = $this->dao->select('max(`order`) as maxOrder')->from(TABLE_MODULE)->where('deleted')->eq(0)->fetch('maxOrder');
        foreach($fromCases as $caseID => $case)
        {
            /* 初始化用例。 */
            /* Init case. */
            $libCase = $this->initLibCase($case, $libID, ++ $maxOrder, $maxModuleOrder, $libCases);

            /* 设置用例步骤。 */
            /* Set case steps. */
            $steps[$caseID] = array();
            if(!isset($fromSteps[$caseID])) $fromSteps[$caseID] = array();
            foreach($fromSteps[$caseID] as $step)
            {
                $stepID        = $step->id;
                $step->version = zget($libCase, 'version', 1);
                unset($step->id);
                $steps[$caseID][$stepID] = $step;
            }

            /* 设置用例文件。 */
            /* Set case files. */
            $files[$caseID] = array();
            if(!isset($fromFiles[$caseID])) $fromFiles[$caseID] = array();
            foreach($fromFiles[$caseID] as $file)
            {
                $file->oldpathname = $file->pathname;

                $files[$caseID][$file->id] = $file;
            }

            $cases[$caseID] = $libCase;
        }

        return array($cases, $steps, $files);
    }

    /**
     * 为展示导入用例构建更新的用例。
     * Build update case for showing import.
     *
     * @param  object    $case
     * @param  object    $oldCase
     * @param  array     $oldStep
     * @param  bool      $forceNotReview
     * @access protected
     * @return bool
     */
    protected function buildUpdateCaseForShowImport(object $case, object $oldCase, array $oldStep, bool $forceNotReview): bool
    {
        $stepChanged = (count($oldStep) != count($case->desc));
        if(!$stepChanged)
        {
            $desc     = array_values($case->desc);
            $expect   = array_values($case->expect);
            $stepType = array_values($case->stepType);
            foreach($oldStep as $index => $step)
            {
                if(!isset($desc[$index]) || !isset($expect[$index]) || $step->desc != $desc[$index] || $step->expect != $expect[$index] || $step->type != $stepType[$index])
                {
                    $stepChanged = true;
                    break;
                }
            }
        }
        $case->version        = $stepChanged ? (int)$oldCase->version + 1 : (int)$oldCase->version;
        $case->stepChanged    = $stepChanged;
        if($stepChanged && !$forceNotReview) $case->status = 'wait';

        return $stepChanged;
    }

    /**
     * 创建测试用例前检验表单数据是否正确。
     * check from data for create case.
     *
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function checkCreateFormData(object $case): bool
    {
        $steps   = $case->steps;
        $expects = $case->expects;
        foreach($expects as $key => $value)
        {
            if(!empty($value) && empty($steps[$key])) dao::$errors[] = sprintf($this->lang->testcase->stepsEmpty, $key);
        }
        foreach(explode(',', $this->config->testcase->create->requiredFields) as $field)
        {
            $field = trim($field);
            if($field && empty($case->{$field}))
            {
                $fieldName = $this->config->testcase->form->create[$field]['type'] != 'array' ? "{$field}" : "{$field}[]";
                dao::$errors[$fieldName] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
            }
        }
        if(dao::isError()) return false;

        return true;
    }

    /**
     * 批量创建测试用例前检验数据是否正确。
     * Check testcases for batch creating.
     *
     * @param  array     $testcases
     * @access protected
     * @return array
     */
    protected function checkTestcasesForBatchCreate(array $testcases): array
    {
        $this->loadModel('common');
        $requiredErrors = array();
        foreach($testcases as $i => $testcase)
        {
            /* 检验必填项。 */
            /* Check reuqired. */
            foreach(explode(',', $this->config->testcase->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field && empty($testcases[$i]->{$field}))
                {
                    $fieldName = $this->config->testcase->form->batchCreate[$field]['type'] != 'array' ? "{$field}[{$i}]" : "{$field}[{$i}][]";
                    $requiredErrors[$fieldName] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
                }
            }

        }
        if(!empty($requiredErrors)) dao::$errors = $requiredErrors;
        return $testcases;
    }

    /**
     * 批量创建测试用例前检验数据是否正确。
     * Check testcases for batch editing.
     *
     * @param  array     $cases
     * @access protected
     * @return array
     */
    protected function checkCasesForBatchEdit(array $cases): array
    {
        $this->loadModel('common');
        $requiredErrors = array();
        foreach($cases as $i => $testcase)
        {
            /* 检验必填项。 */
            /* Check reuqired. */
            foreach(explode(',', $this->config->testcase->edit->requiredFields) as $field)
            {
                $field = trim($field);
                if($field && empty($cases[$i]->{$field}))
                {
                    $fieldName = $this->config->testcase->form->batchEdit[$field]['type'] != 'array' ? "{$field}[{$i}]" : "{$field}[{$i}][]";
                    $requiredErrors[$fieldName] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
                }
            }
        }

        if(!empty($requiredErrors)) dao::$errors = $requiredErrors;
        return $cases;
    }

    /**
     * 导入测试用例前检验数据是否正确。
     * Check testcases for importing.
     *
     * @param  array     $cases
     * @access protected
     * @return array
     */
    protected function checkCasesForShowImport(array $cases): array
    {
        if($this->config->edition != 'open')
        {
            $extendFields = $this->testcase->getFlowExtendFields();
            $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

            foreach($extendFields as $extendField)
            {
                if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
                {
                    $this->config->testcase->create->requiredFields .= ',' . $extendField->field;
                }
            }
        }

        foreach($cases as $i => $case)
        {
            /* 检查期望对应的步骤是否填写。 */
            /* Check the step which has expect. */
            foreach($case->expects as $exportID => $value)
            {
                if(!empty($value) && empty($case->steps[$exportID]))
                {
                    $caseErrors["steps[{$i}]"][] = sprintf($this->lang->testcase->stepsEmpty, $exportID);
                }
            }

            /* 检验必填项。 */
            /* Check reuqired. */
            foreach(explode(',', $this->config->testcase->edit->requiredFields) as $field)
            {
                $field = trim($field);
                if($field && empty($case->{$field}))
                {
                    $fieldName = $this->config->testcase->form->showImport[$field]['type'] != 'array' ? "{$field}[{$i}]" : "{$field}[{$i}][]";
                    $caseErrors[$fieldName][] = sprintf($this->lang->error->notempty, $this->lang->testcase->{$field});
                }
            }
        }

        if(!empty($caseErrors)) dao::$errors = $caseErrors;
        return $cases;
    }

    /**
     * 检查 mind 配置。
     * Build mind config.
     *
     * @access protected
     * @return array|bool
     */
    protected function buildMindConfig(string $type): array|bool
    {
        $configList = array();

        $module = $this->post->module;
        if(!empty($module)) $configList[] = array('key' => 'module', 'value' => $module);

        $scene = $this->post->scene;
        if(!empty($scene)) $configList[] = array('key' => 'scene', 'value' => $scene);

        $case = $this->post->case;
        if(!empty($case)) $configList[] = array('key' => 'case', 'value' => $case);

        $precondition = $this->post->precondition;
        if(!empty($precondition)) $configList[] = array('key' => 'precondition', 'value' => $precondition);

        $pri = $this->post->pri;
        if(!empty($pri)) $configList[] = array('key' => 'pri', 'value' => $pri);

        $group = $this->post->group;
        if(!empty($group)) $configList[] = array('key' => 'group', 'value' => $group);

        $configErrors = array();
        foreach($configList as $config)
        {
            $key   = $config['key'];
            $value = $config['value'];
            if(!preg_match("/^[a-zA-Z]{1,10}$/", $value)) $configErrors[$key][] = sprintf($this->lang->testcase->errorMindConfig, $this->lang->testcase->{$key});
        }

        if(!empty($configErrors)) dao::$errors = $configErrors;

        $map = array();
        $map[strtolower($module)]       = true;
        $map[strtolower($scene)]        = true;
        $map[strtolower($case)]         = true;
        $map[strtolower($precondition)] = true;
        $map[strtolower($pri)]          = true;
        $map[strtolower($group)]        = true;

        if(count($map) < 5 && count($map) > 0) dao::$errors['message'][] = '特征字符串不能重复';
        return !dao::isError() ? $configList : false;
    }

    /**
     * 初始化用例数据。
     * Initialize the testcase.
     *
     * @param  int       $storyID
     * @param  int       $testcaseID
     * @param  int       $bugID
     * @access protected
     * @return object
     */
    protected function initTestcase(int $storyID, int $testcaseID, int $bugID): object
    {
        /* 初始化用例。 */
        /* Initialize the testcase. */
        $case = new stdclass();
        $case->type         = 'feature';
        $case->pri          = 3;
        $case->scene        = 0;
        $case->story        = $storyID;
        $case->stage        = $case->title = $case->precondition = $case->keywords = $case->color = $case->auto = $case->script = '';
        $case->steps        = array();

        /* 如果用例 id 大于 0，使用这个用例数据作为模板。 */
        /* If testcaseID large than 0, use this testcase as template. */
        if($testcaseID > 0)
        {
            $testcase = $this->testcase->getById($testcaseID);
            $case->product      = $testcase->product;
            $case->type         = $testcase->type ? $testcase->type : 'feature';
            $case->stage        = $testcase->stage;
            $case->pri          = $testcase->pri;
            $case->scene        = $testcase->scene;
            $case->story        = $testcase->story;
            $case->title        = $testcase->title;
            $case->precondition = $testcase->precondition;
            $case->keywords     = $testcase->keywords;
            $case->steps        = $testcase->steps;
            $case->color        = $testcase->color;
            $case->auto         = $testcase->auto;
            $case->script       = $testcase->script;
            $case->files        = $testcase->files;
            foreach($case->files as $file)
            {
                $file->name = $file->title;
                $file->url  = $this->createLink('file', 'download', "fileID={$file->id}");
            }

            if($this->config->edition != 'open')
            {
                $extendFields = $this->loadModel('flow')->getExtendFields('testcase', 'create');
                foreach($extendFields as $field) $case->{$field->field} = $testcase->{$field->field};
            }
        }

        /* 如果 bug id 大于 0，使用这个 bug 数据作为模板。 */
        /* If bugID large than 0, use this bug as template. */
        if($bugID > 0)
        {
            $bug = $this->loadModel('bug')->getById($bugID);
            $case->type      = $bug->type;
            $case->pri       = $bug->pri ? $bug->pri : $bug->severity;
            $case->story     = $bug->story;
            $case->title     = $bug->title;
            $case->keywords  = $bug->keywords;
            $case->steps     = $this->testcase->createStepsFromBug($bug->steps);
        }

        /* 追加步骤到默认的步骤数。 */
        /* Append the steps to the default steps count. */
        $case->steps = $this->testcase->appendSteps(!empty($case->steps) ? $case->steps : array());

        return $case;
    }

    /**
     * 初始化要导入到用例库的用例
     * Init case to import to lib.
     *
     * @param  object    $case
     * @param  int       $libID
     * @param  int       $maxOrder
     * @param  int       $maxModuleOrder
     * @param  array     $libCases
     * @access protected
     * @return object
     */
    protected function initLibCase(object $case, int $libID, int $maxOrder, int $maxModuleOrder, array $libCases): object
    {
        $libCase = new stdclass();
        $libCase->lib             = $libID;
        $libCase->title           = $case->title;
        $libCase->precondition    = $case->precondition;
        $libCase->keywords        = $case->keywords;
        $libCase->pri             = $case->pri;
        $libCase->type            = $case->type;
        $libCase->stage           = $case->stage;
        $libCase->status          = $case->status;
        $libCase->fromCaseID      = $case->id;
        $libCase->fromCaseVersion = $case->version;
        $libCase->color           = $case->color;
        $libCase->order           = $maxOrder;
        $libCase->module          = empty($case->module) ? 0 : $this->testcase->importCaseRelatedModules($libID, $case->module, $maxModuleOrder);

        if(!isset($libCases[$case->id]))
        {
            $libCase->openedBy   = $this->app->user->account;
            $libCase->openedDate = helper::now();
        }
        else
        {
            $libCaseList = array_keys($libCases[$case->id]);
            $libCaseID   = $libCaseList[0];

            $libCase->id             = $libCaseID;
            $libCase->lastEditedBy   = $this->app->user->account;
            $libCase->lastEditedDate = helper::now();
            $libCase->version        = (int)$libCases[$case->id][$libCaseID]->version + 1;
        }

        return $libCase;
    }

    /**
     * 导入用例。
     * Import cases.
     *
     * @param  array     $cases
     * @access protected
     * @return array
     */
    protected function importCases(array $cases): array
    {
        $this->loadModel('action');
        $daoErrors = array();
        foreach($cases as $case)
        {
            if(isset($case->id))
            {
                $oldCase = $this->testcase->getByID($case->id);

                if($oldCase->product != $case->product) continue;

                $changes = $this->testcase->update($case, $oldCase);

                $actionID = $this->action->create('case', $case->id, 'Edited');
                $this->action->logHistory($actionID, $changes);

                $this->testcase->updateCase2Project($oldCase, $case);
            }
            else
            {
                $caseID   = $this->testcase->create($case);
                $case->id = $caseID;
                dao::isError() ? $daoErrors = array_merge($daoErrors, dao::getError()) : $this->testcase->syncCase2Project($case, $caseID);
            }
        }
        if(!empty($daoErrors)) dao::$errors = $daoErrors;

        return $cases;
    }

    /**
     * 处理xmind数据
     * Process scene data.
     *
     * @param  array     $result
     * @access protected
     * @return array
     */
    protected function processScene(array $result): array
    {
        $scenes['id']   = $result['id'];
        $scenes['text'] = $result['title'];
        $scenes['type'] = 'root';
        if(!empty($result['children'] && !empty($result['children']['attached']))) $scenes['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'sub');
        if(!empty($result['children'] && !empty($result['children']['topics']['topic']))) $scenes['children'] = $this->processChildScene($result['children']['topics']['topic'], $result['id'], 'sub');
        return $scenes;
    }

    /**
     * 处理xmind的节点数据
     * process scene child data.
     *
     * @param  array     $results
     * @param  string    $parent
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function processChildScene(array $results, string $parent, string $type)
    {
        $scenes = array();
        foreach($results as $result)
        {
            if(!isset($result['id'])) continue;
            $scene['id']     = $result['id'];
            $scene['text']   = $result['title'];
            $scene['parent'] = $parent;
            $scene['type']   = $type;
            if(!empty($result['children']) && !empty($result['children']['attached']))
            {
                $scene['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'node');
            }
            elseif(!empty($result['children']) && !empty($result['children']['topics']))
            {
                if(!isset($result['children']['topics']['type']) || empty($result['children']['topics']['topic'])) continue;
                $topic = $result['children']['topics']['topic'];
                if(isset($topic['id']))
                {
                    $sceneChild = array();
                    $sceneChild['id']       = $topic['id'];
                    $sceneChild['text']     = $topic['title'];
                    $sceneChild['parent']   = $result['id'];
                    $sceneChild['type']     = 'node';

                    $grandChild = array();
                    if(!empty($topic['children']) && !empty($topic['children']['topics']['topic'])) $grandChild = $topic['children']['topics']['topic'];
                    if(isset($topic['nodeType'])) $grandChild = $topic;
                    if(isset($grandChild['id'])) $grandChild = array($grandChild);

                    $sceneChild['children'] = $this->processChildScene($grandChild, $topic['id'], 'node');

                    $scene['children'][] = $sceneChild;
                }
                else
                {
                    $scene['children'] = $this->processChildScene($topic, $result['id'], 'node');
                }
            }
            $scenes[] = $scene;
        }
        return $scenes;
    }

    /**
     * 为展示脑图计算步骤数据。
     * Process steps for mindmap.
     *
     * @param  object    $case
     * @access protected
     * @return object
     */
    protected function processStepsForMindMap(object $case): object
    {
        $mindMapSteps = array();
        $mindMapSteps['id']      = 'case_' . $case->id;
        $mindMapSteps['text']    = $case->title;
        $mindMapSteps['type']    = 'root';
        $stepItem['subSide']     = 'right';

        $reverseSteps = array_reverse($case->steps);

        $stepList = array();
        $parentSteps = array();
        foreach($reverseSteps as $step)
        {
            if(empty($step->id)) continue;

            if($step->type != 'group')
            {
                $descItem = array();
                $descItem['id']      = 'desc_' . $step->id;
                $descItem['text']    = $step->expect;
                $descItem['type']    = 'node';
                $descItem['parent']  = $step->id;
                $descItem['subSide'] = 'right';

                $parentSteps[$step->id][] = $descItem;
            }

            $stepItem = array();
            $stepItem['id']      = $step->id;
            $stepItem['text']    = $step->step;
            $stepItem['type']    = $step->grade == 1 ? 'sub' : 'node';
            $stepItem['parent']  = $step->parent > 0 ? $step->parent : 'case_' . $case->id;
            $stepItem['subSide'] = 'right';
            if(isset($parentSteps[$step->id])) $stepItem['children'] = array_reverse($parentSteps[$step->id]);

            if($step->parent > 0)
            {
                $parentSteps[$step->parent][] = $stepItem;
            }
            else
            {
                $stepList[] = $stepItem;
            }
        }
        $mindMapSteps['children'] = array_reverse($stepList);
        $case->mindMapSteps = $mindMapSteps;
        return $case;
    }

    /**
     * 计算导入列的键值。
     * Process import column key.
     *
     * @param  string    $fileName
     * @param  array     $fields
     * @access protected
     * @return void
     */
    protected function processImportColumnKey(string $fileName, array $fields): array
    {
        /* 获取文件内的行，设置头。 */
        /* Get rows and set header. */
        $rows   = $this->loadModel('file')->parseCSV($fileName);
        $header = array();
        foreach($rows[0] as $i => $rowValue)
        {
            if(empty($rowValue)) break;
            $header[$i] = $rowValue;
        }
        unset($rows[0]);

        /* 设置列的键值。 */
        /* Set column key. */
        $columnKey = array();
        foreach($header as $title)
        {
            if(isset($fields[$title])) $columnKey[] = $fields[$title];
        }

        return $columnKey;
    }

    /**
     * 获取分组用例。
     * Get group cases.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $groupBy
     * @param  string    $caseType
     * @param  string    $browseType
     * @access protected
     * @return array
     */
    protected function getGroupCases(int $productID, string $branch, string $groupBy, string $caseType, string $browseType = ''): array
    {
        /* 获取用例。 */
        /* Get cases. */
        $cases = $this->testcase->getModuleCases($productID, $branch, 0, $browseType, 'no', $caseType, $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->testcase->appendData($cases);
        foreach($cases as $case) $case->caseID  = $case->id;

        /* 获取用例的需求分组。 */
        /* Get story groups of cases. */
        $groupCases = array();
        if($groupBy == 'story')
        {
            foreach($cases as $case) $groupCases[$case->story][] = $case;
        }

        /* 设置用例的需求分组的行占比。 */
        /* Set row span of story group in cases. */
        $story = null;
        foreach($cases as $index => $case)
        {
            if($case->storyDeleted)
            {
                unset($cases[$index]);
                continue;
            }
            $case->rowspan = 0;
            if($story !== $case->story)
            {
                $story = $case->story;
                if(!empty($groupCases[$case->story])) $case->rowspan = count($groupCases[$case->story]);
            }
        }
        return $cases;
    }

    /**
     * 创建完 testcase 后的相关处理。
     * Relevant processing after create testcase.
     *
     * @param  object    $case
     * @param  int       $caseID
     * @access protected
     * @return void
     */
    protected function afterCreate(object $case, int $caseID)
    {
        /* 设置 cookie。 */
        /* Set cookie. */
        helper::setcookie('lastCaseModule', (string)$case->module);
        helper::setcookie('lastCaseProduct', (string)$case->product);
        helper::setcookie('lastCaseScene',  (string)$case->scene);
        helper::setcookie('caseModule', '0');

        if(!empty($_POST['fileList']))
        {
            $fileList = $this->post->fileList;
            if($fileList) $fileList = json_decode($fileList, true);
            $this->loadModel('file')->saveDefaultFiles($fileList, 'testcase', $caseID);
        }

        /* 如果需求关联到项目，把用例也关联到项目。 */
        /* If the story is linked project, make the case link the project. */
        $this->testcase->syncCase2Project($case, $caseID);
    }

    /**
     * 返回创建 testcase 的结果。
     * Respond after creating testcase.
     *
     * @param  int       $caseID
     * @access protected
     * @return array|int
     */
    protected function responseAfterCreate(int $caseID, int $moduleID = 0): array|int
    {
        $message = $this->executeHooks($caseID);
        if(!$message) $message = $this->lang->saveSuccess;

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $caseID));
        /* If link from no head then reload. */
        if(isInModal() || helper::isAjaxRequest('modal')) return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));

        /* 判断是否当前一级菜单不是 QA，并且 caseList session 存在，并且 caseList 不是动态页面。 */
        /* Use this session link, when the tab is not QA, a session of the case list exists, and the session is not from the Dynamic page. */
        $useSession = ($this->app->tab != 'qa' and $this->session->caseList and strpos($this->session->caseList, 'dynamic') === false);

        if($this->app->tab == 'project')
        {
            $locateLink = $this->createLink('project', 'testcase', "projectID={$this->session->project}");
        }
        else
        {
            $params = "productID={$this->post->product}&branch={$this->post->branch}";
            if($moduleID) $params .= "&browseType=byModule&param=$moduleID";
            $locateLink = $this->createLink('testcase', 'browse', $params);
        }
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $useSession ? $this->session->caseList : $locateLink));
    }

    /**
     * 返回批量创建 testcase 的结果。
     * Respond after batch creating testcase.
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @access protected
     * @return void
     */
    protected function responseAfterBatchCreate(int $productID, string|int $branch): array
    {
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(helper::isAjaxRequest('modal')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $caseIdList));

        helper::setcookie('caseModule', '0');

        $currentModule = $this->app->tab == 'qa' ? 'testcase' : $this->app->tab;
        $currentMethod = $this->app->tab == 'qa' ? 'browse'   : 'testcase';
        $projectParam  = $this->app->tab == 'qa' ? ''         : "{$this->app->tab}ID=" . zget($_SESSION, $this->app->tab, 0) . '&';
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($currentModule, $currentMethod, "{$projectParam}productID={$productID}&branch={$branch}")));
    }

    /**
     * 返回展示导入 testcase 的结果。
     * Respond after showing imported testcase.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $maxImport
     * @param  string    $tmpFile
     * @param  string    $message
     * @access protected
     * @return void
     */
    protected function responseAfterShowImport(int $productID, string $branch = '0', int $maxImport = 0, string $tmpFile = '', string $message = ''): array
    {
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if($this->post->isEndPage)
        {
            if(!empty($_SESSION['fileImport']) && file_exists($_SESSION['fileImport'])) unlink($tmpFile);
            if(!empty($_SESSION['fileImport'])) unset($_SESSION['fileImport']);
            $locateLink = $this->app->tab == 'project' ? $this->createLink('project', 'testcase', "projectID={$this->session->project}&productID={$productID}") : inlink('browse', "productID={$productID}");
        }
        else
        {
            $locateLink = inlink('showImport', "productID={$productID}&branch={$branch}&pagerID=" . ((int)$this->post->pagerID + 1) . "&maxImport={$maxImport}&insert=" . zget($_POST, 'insert', ''));
        }
        return $this->send(array('result' => 'success', 'message' => $message ? $message : $this->lang->saveSuccess, 'load' => $locateLink));
    }

    /**
     * 处理评审数据。
     * Prepare review data.
     *
     * @param  int        $caseID
     * @param  object     $oldCase
     * @access protected
     * @return bool|object
     */
    protected function prepareReviewData(int $caseID, object $oldCase): bool|object
    {
        $now    = helper::now();
        $status = $this->getStatusForReview($oldCase);

        $case = form::data($this->config->testcase->form->review, $caseID)->add('id', $caseID)
            ->setForce('status', $status)
            ->setDefault('reviewedDate', substr($now, 0, 10))
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->join('reviewedBy', ',')
            ->stripTags($this->config->testcase->editor->review['id'], $this->config->allowedTags)
            ->get();

        if(!$case->result)
        {
            dao::$errors['result'] = $this->lang->testcase->mustChooseResult;
            return false;
        }

        return $this->loadModel('file')->processImgURL($case, $this->config->testcase->editor->review['id'], $this->post->uid);
    }

    /**
     * 构建关联用例页面的搜索表单。
     * Build search form for link cases.
     *
     * @param  object    $case
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkCasesSearchForm(object $case, int $queryID): void
    {
        $actionURL = $this->createLink('testcase', 'linkCases', "caseID={$case->id}&browseType=bySearch&queryID=myQueryID", '', true);
        $objectID  = 0;
        if($this->app->tab == 'project')   $objectID = $case->project;
        if($this->app->tab == 'execution') $objectID = $case->execution;

        unset($this->config->testcase->search['fields']['product']);
        $this->testcase->buildSearchForm($case->product, $this->products, $queryID, $actionURL, $objectID);
    }

    /**
     * 构建关联 bug 页面的搜索表单。
     * Build search form for link bugs.
     *
     * @param  object    $case
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkBugsSearchForm(object $case, int $queryID): void
    {
        $actionURL = $this->createLink('testcase', 'linkBugs', "caseID={$case->id}&browseType=bySearch&queryID=myQueryID", '', true);
        $objectID  = 0;
        if($this->app->tab == 'project')   $objectID = $case->project;
        if($this->app->tab == 'execution') $objectID = $case->execution;

        /* 删除单一项目的计划字段。*/
        /* Unset search field 'plan' in single project. */
        unset($this->config->bug->search['fields']['product']);
        if($case->project && ($this->app->tab == 'project' || $this->app->tab == 'execution'))
        {
            $project = $this->loadModel('project')->getByID($case->project);
            if(!$project->hasProduct && $project->model == 'waterfall') unset($this->config->bug->search['fields']['plan']);
        }

        $this->loadModel('bug')->buildSearchForm($case->product, $this->products, $queryID, $actionURL, (string)$objectID);
    }

    /**
     * 获取导出的字段列表。
     * Get the export fields.
     *
     * @param  string    $productType
     * @access protected
     * @return array
     */
    protected function getExportFields(string $productType): array
    {
        $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $this->config->testcase->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);

            if($productType != 'normal' || $fieldName != 'branch') $fields[$fieldName] = zget($this->lang->testcase, $fieldName);

            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * 获取 mind 导出的数据。
     * Get export data.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access protected
     * @return array
     */
    protected function getMindExport(string $type, int $productID, int $moduleID, string $branch): array
    {
        $caseList   = $this->testcase->getCaseListForXmindExport($productID, $moduleID, $branch);
        $stepList   = $this->testcase->getStepByProductAndModule($productID, $moduleID);
        $moduleList = $this->getModuleListForXmindExport($productID, $moduleID, $branch);
        $sceneInfo  = $this->testcase->getSceneByProductAndModule($productID, $moduleID);
        $config     = $this->testcase->getMindConfig($type);

        return array('caseList' => $caseList, 'stepList' => $stepList, 'sceneMaps' => $sceneInfo['sceneMaps'], 'topScenes' => $sceneInfo['topScenes'], 'moduleList' => $moduleList, 'config' => $config);
    }

    /**
     * 导出xmind格式用例时获取模块列表。
     * Get module list for xmind export.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return array
     */
    private function getModuleListForXmindExport(int $productID, int $moduleID, string $branch): array
    {
        if($moduleID)
        {
            $module = $this->loadModel('tree')->getByID($moduleID);
            if(!$module) return array();

            return array($module->id => $module->name);
        }

        $moduleList = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' || !isset($branches[$branch])) ? 'all' : $branch);
        unset($moduleList['0']);

        return $moduleList;
    }


    /**
     * 获取导入的数据。
     * Get imported data.
     *
     * @param  int       $productID
     * @param  string    $file
     * @access protected
     * @return void
     */
    protected function getImportedData(int $productID, string $file): array
    {
        $rows    = $this->loadModel('file')->parseCSV($file);
        $header  = array();
        foreach($rows[0] as $i => $rowValue)
        {
            if(empty($rowValue)) break;
            $header[$i] = $rowValue;
        }
        unset($rows[0]);

        $fields   = $this->testcase->getImportFields($productID);
        $fields   = array_flip($fields);
        $caseData = array();
        $stepVars = 0;
        foreach($rows as $row => $data)
        {
            $case = new stdclass();
            foreach($header as $key => $title)
            {
                if(!isset($fields[$title]) || !isset($data[$key])) continue;

                $field     = $fields[$title];
                $cellValue = $data[$key];
                $case = $this->getImportField($field, $cellValue, $case);
            }

            if(empty($case->title)) continue;
            $caseData[$row] = $case;
            unset($case);
        }

        return array(array('caseData' => $caseData), $stepVars);
    }

    /**
     * 获取导入的用例字段。
     * Get imported field.
     *
     * @param  string    $field
     * @param  string    $cellValue
     * @param  object    $case
     * @access protected
     * @return object
     */
    protected function getImportField(string $field, string $cellValue, object $case): object
    {
        if($field == 'story' || $field == 'module' || $field == 'branch')
        {
            $case->{$field} = 0;
            if(strrpos($cellValue, '(#') !== false)
            {
                $id = trim(substr($cellValue, strrpos($cellValue,'(#') + 2), ')');
                $case->{$field} = $id;
            }
        }
        elseif($field == 'stepDesc')
        {
            $case->steps = $cellValue;
        }
        elseif($field == 'stepExpect')
        {
            $case->expects = $cellValue;
        }
        elseif(in_array($field, $this->config->testcase->export->listFields))
        {
            if($field == 'stage')
            {
                $stages = explode("\n", $cellValue);
                foreach($stages as $stage) $case->stage[] = array_search($stage, $this->lang->testcase->{$field . 'List'});
                $case->stage = join(',', $case->stage);
            }
            else
            {
                $case->{$field} = array_search($cellValue, $this->lang->testcase->{$field . 'List'});
            }
        }
        else
        {
            $case->{$field} = $cellValue;
        }
        return $case;
    }
    /**
     * 获取导入的用例步骤。
     * Get imported steps.
     *
     * @param  string    $field
     * @param  array     $steps
     * @param  array     $stepData
     * @param  int       $row
     * @access protected
     * @return array
     */
    protected function getImportSteps(string $field, array $steps, array $stepData, int $row): array
    {
        $caseSteps = array();
        foreach($steps as $step)
        {
            $step = trim($step);
            if(empty($step)) continue;

            preg_match('/^((([0-9]+)[.]([0-9]+))[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^(([0-9]+)[.]([0-9]+))[.、](.*)$/Uu', $step, $out);
            if(!$out) preg_match('/^([0-9]+)[.、](.*)$/Uu', $step, $out);
            if($out)
            {
                $count  = count($out);
                $num    = $out[1];
                $parent = $count > 4 ? $out[2] : '0';
                $grand  = $count > 6 ? $out[3] : '0';
                $step   = trim($out[2]);
                if($count > 4) $step = $count > 6 ? trim($out[6]) : trim($out[4]);

                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['number']  = $num;

                $caseSteps[$num]['type'] = $count > 4 ? 'item' : 'step';
                if(!empty($parent)) $caseSteps[$parent]['type'] = 'group';
                if(!empty($grand)) $caseSteps[$grand]['type']   = 'group';
            }
            elseif(isset($num))
            {
                $caseSteps[$num]['content'] = isset($caseSteps[$num]['content']) ? "{$caseSteps[$num]['content']}\n{$step}" : "\n{$step}";
            }
            elseif($field == 'stepDesc')
            {
                $num = 1;
                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['type']    = 'step';
                $caseSteps[$num]['number']  = $num;
            }
            elseif($field == 'stepExpect' && isset($stepData[$row]['desc']))
            {
                end($stepData[$row]['desc']);
                $num = key($stepData[$row]['desc']);
                $caseSteps[$num]['content'] = $step;
                $caseSteps[$num]['number']  = $num;
            }
        }
        return $caseSteps;
    }

    /**
     * 处理导出的用例数据。
     * Process export cases.
     *
     * @param  array     $cases
     * @param  int       $productID
     * @param  int       $taskID
     * @access protected
     * @return array
     */
    protected function processCasesForExport(array $cases, int $productID, int $taskID): array
    {
        $products = $this->product->getPairs('', 0, '', 'all');
        $branches = $this->loadModel('branch')->getPairs($productID);
        $users    = $this->loadModel('user')->getPairs('noletter');
        $results  = $this->testcase->getCaseResultsForExport(array_keys($cases), $taskID);

        $relatedModules = $this->loadModel('tree')->getAllModulePairs('case');
        $relatedStories = $this->testcase->getRelatedStories($cases);
        $relatedCases   = $this->testcase->getRelatedCases($cases);
        $relatedSteps   = $this->testcase->getRelatedSteps(array_keys($cases));
        $relatedFiles   = $this->testcase->getRelatedFiles(array_keys($cases));
        $relatedScenes  = $this->testcase->getSceneMenu($productID, 0);

        if($taskID) $this->app->loadLang('testtask');

        $cases = $this->testcase->appendData($cases);
        foreach($cases as $case) $this->processCaseForExport($case, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes);

        return $cases;
    }

    /**
     * 处理导出的某个用例。
     * Process export case.
     *
     * @param  object    $case
     * @param  array     $products
     * @param  array     $branches
     * @param  array     $users
     * @param  array     $results
     * @param  array     $relatedModules
     * @param  array     $relatedStories
     * @param  array     $relatedCases
     * @param  array     $relatedSteps
     * @param  array     $relatedFiles
     * @param  array     $relatedScenes
     * @access protected
     * @return void
     */
    protected function processCaseForExport(object $case, array $products, array $branches, array $users, array $results, array $relatedModules, array $relatedStories,  array $relatedCases, array $relatedSteps, array $relatedFiles, array $relatedScenes): void
    {
        $case->stepDesc       = '';
        $case->stepExpect     = '';
        $case->real           = '';
        $case->openedDate     = !helper::isZeroDate($case->openedDate)     ? substr($case->openedDate, 0, 10)     : '';
        $case->lastEditedDate = !helper::isZeroDate($case->lastEditedDate) ? substr($case->lastEditedDate, 0, 10) : '';
        $case->lastRunDate    = !helper::isZeroDate($case->lastRunDate)    ? $case->lastRunDate                   : '';

        $case->product = isset($products[$case->product])     ? $products[$case->product] . "(#$case->product)"     : '';
        $case->branch  = isset($branches[$case->branch])      ? $branches[$case->branch] . "(#$case->branch)"       : '';
        $case->module  = isset($relatedModules[$case->module])? $relatedModules[$case->module] . "(#$case->module)" : '';
        $case->story   = isset($relatedStories[$case->story]) ? $relatedStories[$case->story] . "(#$case->story)"   : '';
        $case->scene   = isset($relatedScenes[$case->scene])  ? $relatedScenes[$case->scene] . "(#$case->scene)"    : '';

        $case->pri           = zget($this->lang->testcase->priList, $case->pri);
        $case->type          = zget($this->lang->testcase->typeList, $case->type);
        $case->status        = $this->processStatus('testcase', $case);
        $case->openedBy      = zget($users, $case->openedBy);
        $case->lastEditedBy  = zget($users, $case->lastEditedBy);
        $case->lastRunner    = zget($users, $case->lastRunner);
        $case->lastRunResult = zget($this->lang->testcase->resultList, $case->lastRunResult);

        $case->bugsAB       = $case->bugs;
        $case->resultsAB    = $case->results;
        $case->stepNumberAB = $case->stepNumber;

        unset($case->bugs);
        unset($case->results);
        unset($case->stepNumber);
        unset($case->caseFails);

        $this->processStepForExport($case, zget($results, $case->id, array()), $relatedSteps);
        $this->processStageForExport($case);
        $this->processFileForExport($case, $relatedFiles);
        if($case->linkCase) $this->processLinkCaseForExport($case);
    }

    /**
     * 处理导出的用例的步骤。
     * Process step of case for export.
     *
     * @param  object    $case
     * @param  array     $result
     * @param  array     $relatedSteps
     * @access protected
     * @return void
     */
    protected function processStepForExport(object $case, array $result, array $relatedSteps): void
    {
        $case->real = '';
        if(!empty($result) && !isset($relatedSteps[$case->id]))
        {
            $firstStep  = reset($result);
            $case->real = $firstStep['real'];
        }

        if(!isset($case->stepDesc))   $case->stepDesc  = '';
        if(!isset($case->stepExpect)) $case->stepExpect = '';
        if(isset($case->id) && isset($relatedSteps[$case->id]))
        {
            $preGrade      = 1;
            $parentSteps   = array();
            $key           = array(0, 0, 0);
            foreach($relatedSteps[$case->id] as $step)
            {
                $grade = 1;
                $parentSteps[$step->id] = $step->parent;
                if(isset($parentSteps[$step->parent])) $grade = isset($parentSteps[$parentSteps[$step->parent]]) ? 3 : 2;

                if($grade > $preGrade)
                {
                    $key[$grade - 1] = 1;
                }
                else
                {
                    if($grade < $preGrade)
                    {
                        if($grade < 2) $key[1] = 0;
                        if($grade < 3) $key[2] = 0;
                    }
                    $key[$grade - 1] ++;
                }

                $stepID = implode('.', $key);
                $stepID = str_replace('.0', '', $stepID);
                $stepID = str_replace('.0', '', $stepID);

                $sign = (in_array($this->post->fileType, array('html', 'xml'))) ? '<br />' : "\n";
                $case->stepDesc   .= $stepID . ". " . htmlspecialchars_decode($step->desc) . $sign;
                $case->stepExpect .= $stepID . ". " . htmlspecialchars_decode($step->expect) . $sign;
                $case->real       .= $stepID . ". " . (isset($result[$step->id]) ? $result[$step->id]['real'] : '') . $sign;

                $preGrade = $grade;
            }
        }
        $case->stepDesc   = !empty($case->stepDesc)   ? trim($case->stepDesc)   : '';
        $case->stepExpect = !empty($case->stepExpect) ? trim($case->stepExpect) : '';
        $case->real       = !empty($case->real)       ? trim($case->real)       : '';

        if($this->post->fileType == 'csv')
        {
            $case->stepDesc   = str_replace('"', '""', $case->stepDesc);
            $case->stepExpect = str_replace('"', '""', $case->stepExpect);
        }
    }

    /**
     * 处理导出的用例的适用阶段。
     * Process stage of case for export.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function processStageForExport(object $case): void
    {
        $case->stage = explode(',', $case->stage);
        foreach($case->stage as $key => $stage) $case->stage[$key] = isset($this->lang->testcase->stageList[$stage]) ? $this->lang->testcase->stageList[$stage] : $stage;
        $case->stage = join("\n", $case->stage);
    }

    /**
     * 处理导出用例的相关用例。
     * Process link case of the case for export.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function processLinkCaseForExport(object $case): void
    {
        $tmpLinkCases   = array();
        $linkCaseIdList = explode(',', $case->linkCase);
        foreach($linkCaseIdList as $linkCaseID)
        {
            $linkCaseID = trim($linkCaseID);
            $tmpLinkCases[] = isset($relatedCases[$linkCaseID]) ? $relatedCases[$linkCaseID] . "(#$linkCaseID)" : $linkCaseID;
        }
        $case->linkCase = join("; \n", $tmpLinkCases);
    }

    /**
     * 处理导出用例的附件。
     * Process file of case for export.
     *
     * @param  object    $case
     * @param  array     $relatedFiles
     * @access protected
     * @return void
     */
    protected function processFileForExport(object $case, array $relatedFiles): void
    {
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

    /**
     * 处理批量编辑用例的步骤和预期。
     * Process steps and expects for batch edit.
     *
     * @param  array     $cases
     * @access protected
     * @return array
     */
    protected function processStepsAndExpectsForBatchEdit(array $cases): array
    {
        $relatedSteps = $this->testcase->getRelatedSteps(array_keys($cases));
        foreach($cases as $case)
        {
            $this->processStepForExport($case, array(), $relatedSteps);
            $case->steps   = $case->stepDesc;
            $case->expects = $case->stepExpect;
        }
        return $cases;
    }

    /**
     * 获取导出模板的字段。
     * Get fields for export template.
     *
     * @param  string    $productType
     * @access protected
     * @return array
     */
    protected function getFieldsForExportTemplate(string $productType): array
    {
        $fields = array();
        $fields['branch']       = $this->lang->product->branchName[$productType];
        $fields['module']       = $this->lang->testcase->module;
        $fields['title']        = $this->lang->testcase->title;
        $fields['precondition'] = $this->lang->testcase->precondition;
        $fields['stepDesc']     = $this->lang->testcase->stepDesc;
        $fields['stepExpect']   = $this->lang->testcase->stepExpect;
        $fields['keywords']     = $this->lang->testcase->keywords;
        $fields['pri']          = $this->lang->testcase->pri;
        $fields['type']         = $this->lang->testcase->type;
        $fields['stage']        = $this->lang->testcase->stage;
        $fields['']             = '';
        $fields['typeValue']    = $this->lang->testcase->lblTypeValue;
        $fields['stageValue']   = $this->lang->testcase->lblStageValue;
        $fields['branchValue']  = $this->lang->product->branchName[$productType];

        if($productType == 'normal')
        {
            unset($fields['branch']);
            unset($fields['branchValue']);
        }

        return $fields;
    }

    /**
     * 获取导出模板的行。
     * Get rows for export template.
     *
     * @param  object    $product
     * @param  int       $num
     * @access protected
     * @return array
     */
    protected function getRowsForExportTemplate(object $product, int $num): array
    {
        $this->loadModel('tree');

        $projectID = $this->app->tab == 'project' ? $this->session->project : 0;
        $branches  = $this->loadModel('branch')->getPairs($product->id, '' , $projectID);
        $modules   = $product->type == 'normal' ? $this->tree->getOptionMenu($product->id, 'case', 0, 'all') : array();

        foreach($branches as $branchID => $branchName)
        {
            $branches[$branchID] = $branchName . "(#$branchID)";
            $modules += $this->tree->getOptionMenu($product->id, 'case', 0, (string)$branchID);
        }

        $rows = array();
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
                    $row->typeValue  = join("\n", $this->lang->testcase->typeList);
                    $row->stageValue = join("\n", $this->lang->testcase->stageList);
                    if($product->type != 'normal') $row->branchValue = join("\n", $branches);
                }

                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * 获取创建用例时的状态值。
     * Get status for create.
     *
     * @access public
     * @return string
     */
    public function getStatusForCreate(): string
    {
        if($this->testcase->forceNotReview() || !$this->post->needReview) return 'normal';
        return 'wait';
    }

    /**
     * 获取评审用例时的状态值。
     * Get status for review.
     *
     * @param  object $case
     * @access private
     * @return string
     */
    private function getStatusForReview(object $case): string
    {
        if($this->post->result == 'pass') return 'normal';
        return zget($case, 'status', '');
    }

    /**
     * 获取更新的状态。
     * Get status for update.
     *
     * @param  object     $case
     * @access public
     * @return bool|array
     */
    public function getStatusForUpdate(object $case): bool|array
    {
        if($this->post->lastEditedDate && $case->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors['message'][] = $this->lang->error->editedByOther;
            return false;
        }

        /* 判断步骤是否变更。*/
        /* Judge steps changed or not. */
        $stepChanged = false;
        if($this->post->steps)
        {
            $steps = array();
            foreach($this->post->steps as $key => $desc)
            {
                if(!$desc) continue;
                $steps[] = array('desc' => trim($desc), 'type' => trim(zget($this->post->stepType, $key, 'step')), 'expect' => trim(zget($this->post->expects, $key, '')));
            }

            /* 如果步骤数量发生变化，步骤变更。*/
            /* If step count changed, case changed. */
            if(count($case->steps) != count($steps))
            {
                $stepChanged = true;
            }
            else
            {
                /* 对比步骤的每一步。*/
                /* Compare every step. */
                $i = 0;
                foreach($case->steps as $key => $oldStep)
                {
                    if(trim($oldStep->desc) != trim($steps[$i]['desc']) || trim($oldStep->expect) != $steps[$i]['expect'] || trim($oldStep->type) != $steps[$i]['type'])
                    {
                        $stepChanged = true;
                        break;
                    }
                    $i++;
                }
            }
        }

        $status = $this->post->status ? $this->post->status : $case->status;
        if(!$this->testcase->forceNotReview() && $stepChanged) $status = 'wait';

        if($this->post->title && $case->title != $this->post->title) $stepChanged = true;
        if(isset($_POST['precondition']) && $case->precondition != $this->post->precondition) $stepChanged = true;
        if(!empty($_FILES['files']['name'][0])) $stepChanged = true;
        if(!empty($_POST['deleteFiles'])) $stepChanged = true;

        return array($stepChanged, $status);
    }

    /**
     * 创建 freemind 的 xml 文档。
     * Create freemind's xml doc.
     *
     * @param  int       $productID
     * @param  string    $productName
     * @param  array     $context
     * @access protected
     * @return object
     */
    protected function createFreeMindXmlDoc(int $productID, string $productName, array $context): object
    {
        $this->classFreeMind = $this->app->loadClass('freemind');

        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;

        $versionAttr = $xmlDoc->createAttribute('version');
        $versionAttr->value = '1.0.1';

        $textAttr = $xmlDoc->createAttribute('TEXT');
        $textAttr->value = $this->classFreeMind->toText("$productName", $productID);

        $mapNode = $xmlDoc->createElement('map');
        $mapNode->appendChild($versionAttr);

        $productNode = $xmlDoc->createElement('node');
        $productNode->appendChild($textAttr);

        $mapNode->appendChild($productNode);

        $xmlDoc->appendChild($mapNode);

        $sceneNodes  = array();
        $moduleNodes = array();
        $this->classFreeMind->createModuleNode($xmlDoc, $context, $productNode, $moduleNodes);
        $this->classFreeMind->createSceneNode($xmlDoc, $context, $productNode, $moduleNodes, $sceneNodes);
        $this->classFreeMind->createTestcaseNode($xmlDoc, $context, $productNode, $moduleNodes, $sceneNodes);

        return $xmlDoc;
    }

    /**
     * 获取 xmind 导出的数据。
     * Get export data of xmind.
     *
     * @param  int       $productID
     * @param  string    $productName
     * @param  array     $context
     * @access protected
     * @return string
     */
    protected function getXmindExportData(int $productID, string $productName, array $context): string
    {
        $this->classXmind = $this->app->loadClass('xmind');

        $xmlDoc = new DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;

        $titleAttr       = $xmlDoc->createElement('title', $this->classXmind->toText("$productName", $productID));
        $idAttr          = $xmlDoc->createElement('id', $this->classXmind->toText(uniqid(), ''));
        $productChildren = $xmlDoc->createElement('children');
        $productTopics   = $this->classXmind->createTopics($xmlDoc);
        $productChildren->appendChild($productTopics);

        $class      = $xmlDoc->createAttribute('structure-class');
        $classValue = $xmlDoc->createTextNode('org.xmind.ui.map.clockwise');
        $class->appendChild($classValue);

        $productTopic = $xmlDoc->createElement('topic');
        $productTopic->appendChild($titleAttr);
        $productTopic->appendChild($idAttr);
        $productTopic->appendChild($class);
        $productTopic->appendChild($productChildren);

        $titleAttr = $xmlDoc->createElement('title', 'sheet');

        $sheet      = $xmlDoc->createElement('sheet');
        $theme      = $xmlDoc->createAttribute('theme');
        $themeValue = $xmlDoc->createTextNode('65q18ujpt3vgdbk1ifknidq03m');
        $theme->appendChild($themeValue);

        $sheet->appendChild($titleAttr);
        $sheet->appendChild($theme);
        $sheet->appendChild($productTopic);

        $xmapContent = $this->classXmind->initXmapContent($xmlDoc);
        $xmapContent->appendChild($sheet);

        $xmlDoc->appendChild($xmapContent);

        $sceneTopics  = array();
        $moduleTopics = array();
        $this->classXmind->createModuleTopic($xmlDoc, $context, $productTopics, $moduleTopics);
        $this->classXmind->createSceneTopic($xmlDoc, $context, $productTopics, $moduleTopics, $sceneTopics);
        $this->classXmind->createTestcaseTopic($xmlDoc, $context, $productTopics, $moduleTopics, $sceneTopics);

        $this->app->loadClass('pclzip', true);
        $zfile = $this->app->loadClass('zfile');

        /* Init xmind file. */
        $exportPath = $this->app->getCacheRoot() . $this->app->user->account . uniqid() . '/';
        if(is_dir($exportPath)) $zfile->removeDir($exportPath);
        $zfile->mkdir($exportPath);

        file_put_contents($exportPath . 'content.xml', $xmlDoc->saveXML());

        /* create style.xml. */
        $styleXmlContent = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><xmap-styles xmlns="urn:xmind:xmap:xmlns:style:2.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:svg="http://www.w3.org/2000/svg" version="2.0"><master-styles><style id="65q18ujpt3vgdbk1ifknidq03m" type="theme"/></master-styles></xmap-styles><?xml version="1.0" encoding="UTF-8" standalone="no"?><xmap-styles xmlns="urn:xmind:xmap:xmlns:style:2.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:svg="http://www.w3.org/2000/svg" version="2.0"><master-styles><style id="65q18ujpt3vgdbk1ifknidq03m" type="theme"/></master-styles></xmap-styles>';
        file_put_contents($exportPath . 'style.xml', $styleXmlContent);

        /* create mate.xml. */
        $metaXmlContent = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><meta xmlns="urn:xmind:xmap:xmlns:meta:2.0" version="2.0"></meta>';
        file_put_contents($exportPath . 'meta.xml', $metaXmlContent);

        /* create META_INF/manifest.xml. */
        $zfile->mkdir($exportPath . 'META-INF');
        $manifestXmlContent = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><manifest xmlns="urn:xmind:xmap:xmlns:manifest:2.0" version="2.0"></manifest>';
        file_put_contents($exportPath . 'META-INF' . DS . 'manifest.xml', $manifestXmlContent);

        /* Zip to xmind. */
        $fileName = uniqid() . '.xmind';
        helper::cd($exportPath);
        $files = array('style.xml', 'meta.xml', 'content.xml', 'META-INF/manifest.xml');
        $zip   = new pclzip($fileName);
        $zip->create($files);
        $fileData = file_get_contents($exportPath . $fileName);
        $zfile->removeDir($exportPath);

        return $fileData;
    }

    /**
     * 解析上传的 xmind 文件。
     * Parse the upload xmind file.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access protected
     * @return void
     */
    protected function parseUploadFile(int $productID, int|string $branch): array|int
    {
        /* 创建导入目录。*/
        /* Create import Directory. */
        $importDir = $this->app->getTmpRoot() . 'import';
        if(!is_dir($importDir)) mkdir($importDir, 0755, true);

        /* 将上传的临时文件移动到指定的临时位置。*/
        /* Move the uploaded temporary file to the specified temporary location. */
        $fileName = $this->app->user->id . '-xmind';
        $filePath = $importDir . '/' . $fileName;
        $tmpFile  = $filePath . 'tmp';
        if(!move_uploaded_file($_FILES['file']['tmp_name'][0], $tmpFile)) return array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload);

        /* 删掉已经存在的当前用户的导入目录。*/
        /* Remove the file path. */
        $this->classFile = $this->app->loadClass('zfile');
        if(is_dir($filePath)) $this->classFile->removeDir($filePath);

        /* 压缩临时文件。*/
        /* Zip the temporary file. */
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($tmpFile);

        /* 获取 ZIP 文件中的内容列表。*/
        /* Get the content list from the zip file. */
        $files      = $zip->listContent();
        $removePath = $files[0]['filename'];

        /* 限制解压的文件内容以阻止 ZIP 解压缩的目录穿越漏洞。*/
        /* Limit the file content to prevent the directory traversal vulnerability of ZIP decompression. */
        $extractFiles = array('content.json', 'content.xml');
        if(in_array($removePath, $extractFiles)) $removePath = '';
        if($zip->extract(PCLZIP_OPT_PATH, $filePath, PCLZIP_OPT_BY_NAME, $extractFiles, PCLZIP_OPT_REMOVE_PATH, $removePath) == 0) return array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload);

        $this->classFile->removeFile($tmpFile);

        $this->classXmind = $this->app->loadClass('xmind');
        if(file_exists($filePath . '/content.json'))
        {
            $fetchResult = $this->fetchByJSON($filePath, $productID, $branch);
        }
        else
        {
            $fetchResult = $this->fetchByXML($filePath, $productID);
        }
        if($fetchResult['result'] == 'fail') return $fetchResult;

        $this->session->set('xmindImport', $filePath);
        $this->session->set('xmindImportType', $fetchResult['type']);

        return (int)$fetchResult['pID'];
    }

    /**
     * 获取 content.xml 的内容。
     * Fetch content.xml.
     *
     * @param  string $filePath
     * @param  int    $productID
     * @access public
     * @return array
     */
    private function fetchByXML(string $filePath, int $productID): array
    {
        $file    = $filePath . '/content.xml';
        $xmlNode = simplexml_load_file($file);
        $title   = (string)$xmlNode->sheet->topic->title;
        if(!is_string($title) || strlen($title) == 0) return array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload);

        $pID = $productID;
        if($this->classXmind->endsWith($title, ']'))
        {
            $tmpID = $this->classXmind->getBetween($title, '[', ']');
            if(!empty($tmpID))
            {
                $product = $this->loadModel('product')->getByID((int)$tmpID);
                if(!$product || $product->deleted) return array('result' => 'fail', 'message' => $this->lang->testcase->errorImportBadProduct);

                $pID = $tmpID;
            }
        }

        return array('result' => 'success', 'pID' => $pID, 'type' => 'xml');
    }

    /**
     * 获取 content.json 的内容。
     * Fetch by json.
     *
     * @param  string     $filePath
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return array
     */
    function fetchByJSON(string $filePath, int $productID, int|string $branch): array
    {
        $file      = $filePath . '/content.json';
        $jsonStr   = file_get_contents($file);
        $jsonDatas = json_decode($jsonStr, true);
        $title     = $jsonDatas[0]['rootTopic']['title'];
        if(strlen($title) == 0) return array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload);

        $pID = $productID;
        if($this->classXmind->endsWith($title, ']'))
        {
            $tmpID = $this->classXmind->getBetween($title, '[', ']');
            if(!empty($tmpID))
            {
                $product = $this->loadModel('product')->getByID((int)$tmpID);
                if(!$product || $product->deleted) return array('result' => 'fail', 'message' => $this->lang->testcase->errorImportBadProduct);

                $pID = $tmpID;
            }
        }

        return array('result' => 'success', 'pID' => $pID, 'type' => 'json');
    }
}
