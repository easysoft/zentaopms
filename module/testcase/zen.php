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
     * 设置列表页面的 cookie。
     * Set browse cookie.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $param
     * @access protected
     * @return void
     */
    protected function setBrowseCookie(string $productID, string $branch, string $browseType, string $param): void
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
            if(!empty($value) && empty($steps[$key]))
            {
                dao::$errors[] = sprintf($this->lang->testcase->stepsEmpty, $key);
                return false;
            }
        }

        if(!empty($_FILES['scriptFile'])) unset($_FILES['scriptFile']);

        $result = $this->testcase->getStatus('update', $oldCase);
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
            ->setIF($formData->data->story != false && $formData->data->story != $oldCase->story, 'storyVersion', $this->loadModel('story')->getVersion($formData->data->story))
            ->setIF(!$formData->data->linkCase, 'linkCase', '')
            ->setIF($formData->data->auto, 'auto', 'auto')
            ->setIF($formData->data->auto && $formData->data->script, 'script', htmlentities($formData->data->script))
            ->setIF(!$formData->data->auto, 'auto', 'no')
            ->setIF(!$formData->data->auto, 'script', '')
            ->join('stage', ',')
            ->join('linkCase', ',')
            ->cleanInt('story,product,branch,module')
            ->stripTags($this->config->testcase->editor->edit['id'], $this->config->allowedTags)
            ->remove('comment,steps,expects,files,labels,linkBug,stepType,scriptFile,scriptName')
            ->get();

        return $this->loadModel('file')->processImgURL($case, $this->config->testcase->editor->edit['id'], $this->post->uid);
    }

    /**
     * 设置列表页面的 session。
     * Set Browse session.
     *
     * @param  int       $productID
     * @param  int       $moduleID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function setBrowseSession(int $productID, int $moduleID, string $browseType, string $orderBy): void
    {
        if($browseType != 'bymodule') $this->session->set('caseBrowseType', $browseType);

        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('productID', $productID);
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
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function setBrowseMenu(int $productID, string $branch, string $browseType, int $projectID): void
    {
        /* 在不同的应用中，设置不同的导航。 */
        /* Set menu, save session. */
        if($this->app->tab == 'project')
        {
            $linkedProducts = $this->product->getProducts($projectID, 'all', '', false);
            $this->products = array('0' => $this->lang->product->all) + $linkedProducts;

            $hasProduct = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('hasProduct');
            if(!$hasProduct) unset($this->config->testcase->search['fields']['product']);

            $branch = intval($branch) > 0 ? $branch : 'all';
            $this->loadModel('project')->setMenu($projectID);

            $this->view->products   = $this->products;
            $this->view->hasProduct = $hasProduct;
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch, $browseType);
        }
    }

    /**
     * 设置菜单。
     * Set menu.
     *
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  int       $productID
     * @param  string    $branch
     * @access protected
     * @return void
     */
    protected function setMenu(int $projectID, int $executionID, int $productID, string $branch)
    {
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($projectID);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($executionID);
        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $branch);
    }

    /**
     * 构建搜索表单。
     * Build the search form.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $queryID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function buildBrowseSearchForm(int $productID, string $branch, int $queryID, int $projectID): void
    {
        $this->config->testcase->search['onMenuBar'] = 'yes';

        $currentModule  = $this->app->tab == 'project' ? 'project'  : 'testcase';
        $currentMethod  = $this->app->tab == 'project' ? 'testcase' : 'browse';
        $projectParam   = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
        $actionURL      = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $searchProducts = $this->product->getPairs('', 0, '', 'all');

        $this->testcase->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $projectID);
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

        $executionID = $from == 'execution' ? $param : 0;
        $testcaseID  = $from && strpos('testcase|work|contribute', $from) !== false ? $param : 0;

        /* 设置分支。 */
        /* Set branches. */
        if($this->app->tab == 'project' || $this->app->tab == 'execution')
        {
            $objectID        = $this->app->tab == 'project' ? $this->session->project : $executionID;
            $productBranches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($productID), $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = isset($product->type) && $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        /* 设置菜单。 */
        /* Set menu. */
        $this->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $branch);

        /* 初始化用例数据。 */
        /* Initialize the testcase. */
        $case = $this->initTestcase($storyID, $testcaseID, $from == 'bug' ? $param : 0);

        /* 设置模块和需求。 */
        /* Set modules. */
        $this->assignModulesAndStoiresForCreate($productID, $moduleID, $branch, $case->story, $branches);

        $this->view->product        = $product;
        $this->view->projectID      = isset($projectID) ? $projectID : 0;
        $this->view->currentSceneID = $testcaseID > 0 ? $case->scene : (int)$this->cookie->lastCaseScene;
        $this->view->case           = $case;
        $this->view->executionID    = $executionID;
        $this->view->branch         = $branch;
        $this->view->branches       = $branches;
    }

    /**
     * 展示创建 testcase 的需求和模块变量。
     * Show the modules and stoires associated with the creation testcase.
     *
     * @param  int       $productID
     * @param  int       $moduleID
     * @param  string    $branch
     * @param  int       $storyID
     * @param  array     $branches
     * @access protected
     * @return void
     */
    protected function assignModulesAndStoiresForCreate(int $productID, int $moduleID, string $branch, int $storyID, array $branches)
    {
        if($storyID)
        {
            $story = $this->loadModel('story')->getByID($storyID);
            if(empty($moduleID)) $moduleID = $story->module;
        }

        $currentModuleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastCaseModule;

        $modules = array();
        if($currentModuleID)
        {
            $productModules = $this->loadModel('tree')->getOptionMenu($productID, 'story');
            $storyModuleID  = array_key_exists($currentModuleID, $productModules) ? $currentModuleID : 0;
            $modules        = $this->tree->getStoryModule($storyModuleID);
            $modules        = $this->tree->getAllChildID($modules);
        }

        /* 获取未关闭的需求。 */
        /* Get the status of stories are not closed. */
        $stories = $this->loadModel('story')->getProductStoryPairs($productID, $branch, $modules, 'active', 'id_desc', 50, 'full', 'story', false);
        if($this->app->tab != 'qa' && $this->app->tab != 'product')
        {
            $projectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
            $stories   = $this->story->getExecutionStoryPairs($projectID, $productID, $branch, $modules);
        }
        if($storyID && !isset($stories[$storyID])) $stories = $this->story->formatStories(array($storyID => $storyID)) + $stories;

        $this->view->stories          = $stories;
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' || !isset($branches[$branch]) ? 0 : $branch);
        $this->view->sceneOptionMenu  = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0, $branch === 'all' || !isset($branches[$branch]) ? 0 : $branch);
    }

    /**
     * 指定用例和场景。
     * Assign browse cases and scenes.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  int       $moduleID
     * @param  string    $caseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function assignCasesAndScenesForBrowse(int $productID, string $branch, string $browseType, int $queryID, int $moduleID, string $caseType, string $orderBy, object $pager): void
    {
        $cases          = array();
        $caseIdList     = array();
        $queryCondition = '';

        /* 不是仅场景的时候获取用例列表。*/
        /* Get test cases when the browseType is not onlyscene. */
        if($browseType != 'onlyscene')
        {
            $sort           = common::appendOrder($orderBy);
            $cases          = $this->testcase->getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $caseType, $sort, null);
            $queryCondition = $this->dao->get();
            $caseIdList     = array_column($cases, 'id');
        }

        $productParam = $productID;
        if(intval($productID) <= 0)
        {
            $productParam = array_keys($this->products);
            if(count($productParam) > 1) unset($productParam[0]);
        }

        /* 获取顶级的场景和案例。*/
        /* Get top level cases and scenes.*/
        $topObjects = array();
        if(!$this->cookie->onlyAutoCase)
        {
            $topObjects = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, $pager, 'top', array(), $browseType);
            if(empty($topObjects) && $pageID > 1)
            {
                $pager      = pager::init(0, $recPerPage, 1);
                $topObjects = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, $pager, 'top', array(), $browseType);
            }
        }

        /* 获取用例和场景列表。*/
        /* Get children cases and scenes.*/
        /* Process case for check story changed. */
        $scenes = $this->testcase->getList($productParam, $branch, $moduleID, $caseIdList, null, 'child', array_keys($topObjects), $browseType, $queryCondition);
        $scenes = $this->loadModel('story')->checkNeedConfirm($scenes);
        $scenes = $this->testcase->appendData($scenes);

        /* 保存查询的 session。*/
        /* save session. */
        $this->loadModel('common')->saveQueryCondition($queryCondition, 'testcase', false);

        $this->view->cases   = $cases;
        $this->view->scenes  = $scenes;
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
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function assignProductAndBranchForBrowse(int $productID, string $branch, int $projectID): void
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
        $this->view->productName     = $this->products[$productID];
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

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
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
        $this->view->iscenes     = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0,  0);
        $this->view->suiteList   = $this->loadModel('testsuite')->getSuites($productID);
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : array();
        $this->view->libraries   = $this->loadModel('caselib')->getLibraries();
        $this->view->stories     = array('') + $this->loadModel('story')->getPairs($productID);
        $this->view->automation  = $this->loadModel('zanode')->getAutomationByProduct($productID);
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
        /* 处理单元测试用例列表的标签。*/
        /* Process the sub menu of unit test case. */
        if($case->auto == 'unit')
        {
            $this->lang->testcase->subMenu->testcase->feature['alias']  = '';
            $this->lang->testcase->subMenu->testcase->unit['alias']     = 'view';
            $this->lang->testcase->subMenu->testcase->unit['subModule'] = 'testcase';
        }

        /* 初始化用例步骤。*/
        /* Unit the steps of case. */
        if(empty($case->steps))
        {
            $step = new stdclass();
            $step->type   = 'step';
            $step->desc   = '';
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
            $this->caselib->setLibMenu($libraries, $case->lib);
        }
    }

    /**
     * 设置编辑用例的导航。
     * Set menu for editing case.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function setMenuForCaseEdit(object $case): void
    {
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($case->project);

        if($this->app->tab == 'execution')
        {
            if(!$executionID) $executionID = $case->execution;
            $this->loadModel('execution')->setMenu($executionID);
        }

        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $case->product, $case->branch);
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
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($case->lib, $viewType = 'caselib', $startModuleID = 0);
    }

    /**
     * 指定编辑用例的数据。
     * Assign for editing case.
     *
     * @param  object    $case
     * @access protected
     * @return void
     */
    protected function assignForEditCase(object $case): void
    {
        $product = $this->product->getByID($case->product);
        if(!isset($this->products[$case->product])) $this->products[$case->product] = $product->name;

        $this->view->title     = $this->products[$case->product] . $this->lang->colon . $this->lang->testcase->edit;
        $this->view->isLibCase = false;
        $this->view->product   = $product;
        $this->view->products  = $this->products;

        $this->assignBranchForEdit($case);
        $this->assignStoriesForEdit($case);
        $this->assignModuleOptionMenuForEdit($case);
    }

    /**
     * 指定编辑用例的分支。
     * Assign branch data for editing case.
     *
     * @param  object  $case
     * @access private
     * @return void
     */
    private function assignBranchForEdit(object $case): void
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
     * 指定编辑用例的需求。
     * Assign stories for editing case.
     *
     * @param  object  $case
     * @access private
     * @return void
     */
    private function assignStoriesForEdit(object $case): void
    {
        $moduleIdList = array();
        if($case->module) $moduleIdList = $this->tree->getAllChildID($case->module);

        if($this->app->tab == 'execution')
        {
            $stories = $this->loadModel('story')->getExecutionStoryPairs($case->execution, $case->product, $case->branch, $moduleIdList);
        }
        else
        {
            $stories = $this->loadModel('story')->getProductStoryPairs($case->product, $case->branch, $moduleIdList, 'all','id_desc', 0, 'full', 'story', false);
        }

        $this->view->stories = $stories;
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
        $moduleOptionMenu = $this->tree->getOptionMenu($case->product, $viewType = 'case', $startModuleID = 0, $case->branch);

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
        $sceneOptionMenu = $this->testcase->getSceneMenu($productID, $case->module, $viewType = 'case', $startSceneID = 0, 0);
        if(!isset($sceneOptionMenu[$case->scene])) $sceneOptionMenu += $this->testcase->getScenesName($case->scene);

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
    protected function buildCaseForCase(string $from, int $param): object
    {
        return form::data($this->config->testcase->form->create)
            ->setIF($from == 'bug', 'fromBug', $param)
            ->setIF($this->post->auto, 'auto', 'auto')
            ->setIF($this->post->auto && $this->post->script, 'script', $this->post->script ? htmlentities($this->post->script) : '')
            ->setIF($this->testcase->forceNotReview() || $this->post->forceNotReview, 'status', 'normal')
            ->setIF($this->app->tab == 'project',   'project',   $this->session->project)
            ->setIF($this->app->tab == 'execution', 'execution', $this->session->execution)
            ->setIF($this->post->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->get();
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
            if(!empty($value) and empty($steps[$key])) dao::$errors['message']["steps$key"] = sprintf($this->lang->testcase->stepsEmpty, $key);
        }
        if(dao::isError()) return false;

        $param = '';
        if(!empty($case->lib))     $param = "lib={$case->lib}";
        if(!empty($case->product)) $param = "product={$case->product}";

        $result = $this->loadModel('common')->removeDuplicate('case', $case, $param);
        if($result and $result['stop'])
        {
            return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->testcase->common), 'locate' => $this->createLink('testcase', 'view', "caseID={$result['duplicate']}")));
        }
        return true;
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
        $case->stage        = '';
        $case->pri          = 3;
        $case->scene        = 0;
        $case->story        = $storyID;
        $case->title        = '';
        $case->precondition = '';
        $case->keywords     = '';
        $case->steps        = array();
        $case->color        = '';
        $case->auto         = '';

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
        if(!empty($result['children'])) $scenes['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'sub');
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
            $scene['id']     = $result['id'];
            $scene['text']   = $result['title'];
            $scene['parent'] = $parent;
            $scene['type']   = $type;
            if(!empty($result['children'])) $scene['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'node');
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
        $mindMapSteps['id']      = $case->id;
        $mindMapSteps['text']    = $case->title;
        $mindMapSteps['type']    = 'root';
        $stepItem['subSide']     = 'right';

        $reverseSteps = array_reverse($case->steps);

        $stepList = array();
        $parentSteps = array();
        foreach($reverseSteps as $step)
        {
            if(empty($step->id)) continue;
            $stepItem = array();
            $stepItem['id']      = $step->id;
            $stepItem['text']    = $step->step;
            $stepItem['type']    = $step->grade == 1 ? 'sub' : 'node';
            $stepItem['parent']  = $step->parent > 0 ? $step->parent : $case->id;
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
        helper::setcookie('lastCaseScene',  (string)$case->scene);
        helper::setcookie('caseModule', '0');

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
     * @return void
     */
    protected function responseAfterCreate(int $caseID): array
    {
        $message = $this->executeHooks($caseID);
        if(!$message) $message = $this->lang->saveSuccess;

        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $caseID));
        /* If link from no head then reload. */
        if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true));

        /* 判断是否当前一级菜单不是 QA，并且 caseList session 存在，并且 caseList 不是动态页面。 */
        /* Use this session link, when the tab is not QA, a session of the case list exists, and the session is not from the Dynamic page. */
        $useSession = ($this->app->tab != 'qa' and $this->session->caseList and strpos($this->session->caseList, 'dynamic') === false);
        $locateLink = $this->app->tab == 'project' ? $this->createLink('project', 'testcase', "projectID={$this->session->project}") : $this->createLink('testcase', 'browse', "productID={$this->post->product}&branch={$this->post->branch}");
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $useSession ? $this->session->caseList : $locateLink));
    }
}

