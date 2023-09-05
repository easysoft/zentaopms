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
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $branch = '', string $browseType = 'all', int $param = 0, string $caseType = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0)
    {
        /* 把访问的产品ID等状态信息保存到session和cookie中。*/
        /* Save the product id user last visited to session and cookie. */
        $productID  = $this->app->tab != 'project' ? $this->product->saveState($productID, $this->products) : $productID;
        $branch     = ($this->cookie->preBranch !== '' && $branch === '') ? $this->cookie->preBranch : $branch;
        $browseType = strtolower($browseType);
        $moduleID   = ($browseType == 'bymodule') ? $param : ($browseType == 'bysearch' ? 0 : ($this->cookie->caseModule ? $this->cookie->caseModule : 0));
        $suiteID    = ($browseType == 'bysuite')  ? $param : ($browseType == 'bymodule' ? ($this->cookie->caseSuite ? $this->cookie->caseSuite : 0) : 0);
        $queryID    = ($browseType == 'bysearch') ? $param : 0;

        $this->testcaseZen->setBrowseCookie((string)$productID, $branch, $browseType, (string)$param);
        $this->testcaseZen->setBrowseSession($productID, $moduleID, $browseType, $orderBy);
        $this->testcaseZen->setBrowseMenu($productID, $branch, $browseType, $projectID);
        $this->testcaseZen->buildBrowseSearchForm($productID, $branch, $queryID, $projectID);
        $this->testcaseZen->assignCasesAndScenesForBrowse($productID, $branch, $browseType, ($browseType == 'bysearch' ? $queryID : $suiteID), $moduleID, $caseType, $orderBy, $recTotal, $recPerPage, $pageID);
        $this->testcaseZen->assignModuleTreeForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignProductAndBranchForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignForBrowse($productID, $branch, $browseType, $projectID, $param, $moduleID, $suiteID, $caseType);

        $this->display();
    }

    /**
     * 分组查看用例。
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
    public function groupCase(int $productID = 0, string $branch = '', string $groupBy = 'story', int $projectID = 0, string $caseType = '')
    {
        /* 设置SESSION和COOKIE，获取产品信息。 */
        /* Set session and cookie, and get product. */
        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getByID($productID);
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);

        if($branch === '') $branch = $this->cookie->preBranch;
        if(empty($groupBy)) $groupBy = 'story';

        /* 设置菜单。 */
        /* Set menu. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu((int)$this->session->project);
        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $branch);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->cases       = $this->testcaseZen->getGroupCases($productID, $branch, $groupBy, $caseType);
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
     * @param  int    $projectID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function zeroCase(int $productID = 0, int $branchID = 0, string $orderBy = 'id_desc', int $projectID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 设置 session, cookie 和菜单。*/
        /* Set session, cookie and set menu. */
        $this->session->set('storyList', $this->app->getURI(true) . '#app=' . $this->app->tab, 'product');
        $this->session->set('caseList', $this->app->getURI(true), $this->app->tab);
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $products  = $this->product->getProducts($this->session->project, 'all', '', false);
            $productID = $this->product->saveState($productID, $products);
        }
        else
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->saveState($productID, $products);
            $this->loadModel('qa');
            $this->app->rawModule = 'testcase';
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->qa->setMenu($products, $productID, $branchID);
        }

        /* 设置 分页。*/
        /* Set pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* 追加二次排序条件。*/
        /* Append id for second sort. */
        $sort = common::appendOrder(empty($orderBy) ? 'id_desc' : $orderBy);

        $this->lang->testcase->featureBar['zerocase'] = $this->lang->testcase->featureBar['browse'];

        /* 展示变量. */
        /* Show the variables. */
        $this->loadModel('story');
        $this->view->title      = $this->lang->story->zeroCase;
        $this->view->stories    = $this->story->getZeroCase($productID, $branchID, $sort, $pager);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->projectID  = $projectID;
        $this->view->productID  = $productID;
        $this->view->branchID   = $branchID;
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
            return $this->testcaseZen->responseAfterCreate($caseID);
        }

        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* 设置产品id和分支。 */
        /* Set productID and branch. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch;

        $moduleID = $this->testcaseZen->assignCreateVars($productID, $branch, $moduleID, $from, $param, $storyID);

        /* 设置自定义字段。 */
        /* Set custom fields. */
        foreach(explode(',', $this->config->testcase->customCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->createFields;

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $this->view->productID  = $productID;
        $this->view->users      = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->gobackLink = isset($output['from']) && $output['from'] == 'global' ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
        $this->view->needReview = $this->testcase->forceNotReview() == true ? 0 : 1;
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
        if(!empty($_POST))
        {
            $testcases = $this->testcaseZen->buildCasesForBathcCreate($productID);
            $testcases = $this->testcaseZen->checkTestcasesForBatchCreate($testcases, $productID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($testcases as $testcase)
            {
                $testcaseID = $this->testcase->create($testcase);
                $this->executeHooks($testcaseID);
                $this->testcase->syncCase2Project($testcase, $testcaseID);
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');
            return $this->testcaseZen->responseAfterBatchCreate($productID, $branch);
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* 设置 session 和 cookie, 并且设置产品 id、分支。 */
        /* Set session and cookie, and set product id, branch. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = $this->cookie->preBranch;

        /* 设置菜单。 */
        /* Set menu. */
        $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);

        /* 指派产品、分支、需求、自定义字段等变量. */
        /* Assign the variables about product, branches, story and custom fields. */
        $this->testcaseZen->assignForBatchCreate($productID, $branch, $moduleID, $storyID);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->batchCreate;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' ? 0 : $branch);
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
     * @param  string $extras
     * @access public
     * @return void
     */
    public function createBug(int $productID, string $extras = '')
    {
        /* 从 extras 中提取变量。 */
        /* Extract variables from extras. */
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $params);
        extract($params);

        /* 获取用例和用例执行结果。 */
        /* Get case and results. */
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
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function view(int $caseID, int $version = 0, string $from = 'testcase', int $taskID = 0, $stepsType = 'table')
    {
        $this->session->set('bugList', $this->app->getURI(true), $this->app->tab);

        $case = $this->testcase->getById($caseID, $version);

        /* 如果用例不存在，返回到测试仪表盘页面。 */
        /* If testcase isn't exist, locate to qa-ndex.*/
        if(!$case)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));
        }
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

            $this->view->title   = "CASE #$case->id $case->title - " . $libraries[$case->lib];
            $this->view->libName = $libraries[$case->lib];
        }
        /* 如果用例不在用例库内，指定相关变量。 */
        /* If testcase isn't in case lib, assign associated variables. */
        else
        {
            $productID = $case->product;
            $product   = $this->product->getByID($productID);
            $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);

            $this->testcaseZen->setMenu((int)$this->session->project, (int)$this->session->execution, $productID, $case->branch);

            $this->view->title       = "CASE #$case->id $case->title - " . $product->name;
            $this->view->product     = $product;
            $this->view->branches    = $branches;
            $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $case->branch, '');
        }

        $this->view->stepsType  = $stepsType;
        $this->view->version    = $version ? $version : $case->version;
        $this->view->isLibCase  = $isLibCase;
        $this->display();
    }

    /**
     * 编辑用例。
     * Edit a case.
     *
     * @param  int    $caseID
     * @param  bool   $comment
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function edit(int $caseID, bool $comment = false, int $executionID = 0)
    {
        $oldCase = $this->testcase->getByID($caseID);
        if(!$oldCase) return print(js::error($this->lang->notFound) . js::locate('back'));

        $testtasks = $this->loadModel('testtask')->getGroupByCases($caseID);
        $testtasks = empty($testtasks[$caseID]) ? array() : $testtasks[$caseID];

        if(!empty($_POST))
        {
            $formData = form::data($this->config->testcase->form->edit);
            $case     = $this->testcaseZen->prepareEditExtras($formData, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = array();
            if(!$comment)
            {
                $changes = $this->testcase->update($case, $oldCase, $testtasks);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($case->comment || !empty($changes)) $this->testcaseZen->addEditAction($caseID, $oldCase->status, $case->status, $changes, $case->comment);

            $message = $this->executeHooks($caseID);
            if(!$message) $message = $this->lang->saveSuccess;

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $caseID));
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $this->createLink('testcase', 'view', "caseID={$caseID}")));
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
            $productID = $case->product;

            $this->testcaseZen->setMenuForCaseEdit($case);
            $this->testcaseZen->assignForEditCase($case);
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
     * @param  string $tab
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, string $branch = '0', string $type = 'case', string $tab = '')
    {
        if(!$this->post->caseIdList && !$this->post->id) $this->locate($this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$productID}"));

        $caseIdList = $this->post->caseIdList ? array_unique($this->post->caseIdList) : array_unique($this->post->id);
        $cases      = $this->testcase->getByList($caseIdList);
        $caseIdList = array_keys($cases);
        $testtasks  = $this->loadModel('testtask')->getGroupByCases($caseIdList);
        if($this->post->id)
        {
            $editedCases = $this->testcaseZen->buildCasesForBathcEdit($cases);
            $editedCases = $this->testcaseZen->checkCasesForBatchEdit($editedCases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 更新用例。 */
            /* Update cases. */
            foreach($editedCases as $caseID => $case)
            {
                $changes = $this->testcase->update($case, $cases[$caseID], zget($testtasks, $caseID, array()));
                $this->executeHooks($caseID);

                if(empty($changes)) continue;
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('score')->create('ajax', 'batchEdit');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
        }

        $this->testcaseZen->assignForBatchEdit($productID, $branch, $type, $cases);

        /* 判断要编辑的用例是否太大，设置 session。 */
        /* Judge whether the editedCases is too large and set session. */
        $countInputVars  = count($cases) * (count(explode(',', $this->config->testcase->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* 展示变量. */
        /* Show the variables. */
        $this->view->stories         = $this->loadModel('story')->getProductStoryPairs($productID, $branch);
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
        if($_POST)
        {
            $oldCase = $this->testcase->getByID($caseID);
            $case    = $this->testcaseZen->prepareReviewData($caseID, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->testcase->review($case, $oldCase);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($caseID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $this->view->title = $this->lang->testcase->review;
        $this->view->users = $this->user->getPairs('noletter|noclosed|nodeleted');
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
     * Batch change branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch($branchID)
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
     * Batch change the module of case.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        $caseIdList  = zget($_POST, 'caseIdList',  array());
        $sceneIdList = zget($_POST, 'sceneIdList', array());
        if($caseIdList || $sceneIdList)
        {
            $this->testcase->batchChangeModule($caseIdList, $sceneIdList, $moduleID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
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
        $this->testcase->setMenu($this->products, $case->product, $case->branch);

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
        $this->view->title      = $case->title . $this->lang->colon . $this->lang->testcase->linkCases;
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
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs(int $caseID, string $browseType = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('bug');

        $case = $this->testcase->getByID($caseID);

        /* 构建搜索表单。*/
        /* Build the search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->testcaseZen->buildLinkBugsSearchForm($case, $queryID);

        /* 获取关联的bug。*/
        /* Get bugs to link. */
        $bugs2Link = $this->testcase->getBugs2Link($caseID, $browseType, $queryID);

        /* bug 分页。*/
        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal  = count($bugs2Link);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $bugs2Link = array_chunk($bugs2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->title     = $this->lang->testcase->linkBugs;
        $this->view->case      = $case;
        $this->view->bugs2Link = empty($bugs2Link) ? $bugs2Link : $bugs2Link[$pageID - 1];
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager     = $pager;

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

            unset($file->id, $file->realPath, $file->webPath);
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
            if($story->version)
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
        $product = $this->loadModel('product')->getByID($productID);

        if($product->shadow)           $this->config->testcase->exportFields = str_replace('product,', '', $this->config->testcase->exportFields);
        if($product->type == 'normal') $this->config->testcase->exportFields = str_replace('branch,', '', $this->config->testcase->exportFields);
        if($product->type != 'normal') $this->lang->testcase->branch = $this->lang->product->branchName[$product->type];

        if($_POST)
        {
            $fields = $this->testcaseZen->getExportFields($product->type);
            $cases  = $this->testcase->getCasesToExport($this->post->exportType, $taskID, $orderBy, $this->post->limit);
            $cases  = $this->testcaseZen->processCasesForExport($cases, $productID, $taskID);

            if($this->config->edition != 'open') list($fields, $cases) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $cases);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $cases);
            $this->post->set('kind', 'testcase');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName   = $this->lang->testcase->common;
        $browseType = isset($this->lang->testcase->featureBar['browse'][$browseType]) ? $this->lang->testcase->featureBar['browse'][$browseType] : '';

        if($taskID) $taskName = $this->dao->findById($taskID)->from(TABLE_TESTTASK)->fetch('name');

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
        if($_FILES)
        {
            /* 获取上传的文件。 */
            /* Get file. */
            $file = $this->loadModel('file')->getUpload('file');
            $file = $file[0];

            /* 移动上传的文件。 */
            /* Move file. */
            $fileName = $this->file->savePath . $this->file->getSaveName($file['pathname']);
            move_uploaded_file($file['tmpname'], $fileName);

            /* 获取上传文件中的用例字段。 */
            /* Get imported fields of the testcase. */
            $fields    = $this->testcase->getImportFields($productID);
            $fields    = array_flip($fields);

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

            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => inlink('showImport', "productID={$productID}&branch={$branch}"), 'closeModal' => true));
        }

        $this->display();
    }

    /**
     * Import case from lib.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $libID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importFromLib($productID, $branch = 0, $libID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0)
    {
        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;
        $product    = $this->loadModel('product')->getById($productID);
        $branches   = array();
        if($branch == '') $branch = 0;

        $this->loadModel('branch');
        if($product->type != 'normal') $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($productID, 'active', $projectID);

        $libraries = $this->loadModel('caselib')->getLibraries();
        if(empty($libraries)) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->noLibrary, 'load' => $this->session->caseList));

        if(empty($libID) || !isset($libraries[$libID])) $libID = key($libraries);

        if($_POST)
        {
            $result = $this->testcase->importFromLib($productID, $libID, $branch);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($result) && is_string($result))
            {
                $imported = trim($result, ',');
                return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->testcase->importedCases, $imported)));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true));
        }

        $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch&libID=$libID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['module']    = 'testsuite';
        $this->config->testcase->search['onMenuBar'] = 'no';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;
        $this->config->testcase->search['fields']['lib'] = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->caselib->all));
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib');
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->loadModel('testsuite');
        foreach($branches as $branchID => $branchName) $canImportModules[$branchID] = $this->testsuite->getCanImportModules($productID, $libID, $branchID);
        if(empty($branches)) $canImportModules[0] = $this->testsuite->getCanImportModules($productID, $libID, 0);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $recPerPage, $pageID);

        $this->view->title      = $this->lang->testcase->common . $this->lang->colon . $this->lang->testcase->importFromLib;

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
        /* Get file information. */
        $file    = $this->session->fileImport;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if($_POST)
        {
            $cases = $this->testcaseZen->buildCasesForShowImport();
            $cases = $this->testcaseZen->checkCasesForShowImport($cases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $cases = $this->testcaseZen->importCases($cases);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->testcaseZen->responseAfterShowImport($productID, $branch, $maxImport, $tmpFile);
        }

        $this->app->tab == 'project' ? $this->loadModel('project')->setMenu($this->session->project) : $this->testcase->setMenu($this->products, $productID, $branch);

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

        $this->assignShowImportVars($productID, $branch, $data['caseData'], isset($stepVars) ? $stepVars : 0, $pagerID, $maxImport);

        $this->view->title      = $this->lang->testcase->common . $this->lang->colon . $this->lang->testcase->showImport;
        $this->view->stories    = $this->loadModel('story')->getProductStoryPairs($productID, $branch);
        $this->view->cases      = $this->testcase->getByProduct($productID);
        $this->view->stepData   = $data['stepData'];
        $this->view->productID  = $productID;
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
        if(!empty($_POST))
        {
            $this->testcase->importToLib($caseID);
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
     * @access public
     * @return void
     */
    public function bugs(int $runID, int $caseID = 0, int $version = 0)
    {
        $this->view->title = $this->lang->testcase->bugs;
        $this->view->bugs  = $this->loadModel('bug')->getCaseBugs($runID, $caseID, $version);
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
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
    public function automation($productID = 0)
    {
        $this->loadModel('zanode');

        if($_POST)
        {
            $this->zanode->setAutomationSetting();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            // if($this->post->syncToZentao) $this->zanode->syncCasesToZentao($this->post->scriptPath);
            // if($this->post->node) $node = $this->zanode->getNodeByID($this->post->node);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('testcase', 'browse', "productID={$this->post->product}")));
        }

        $this->view->title      = $this->lang->zanode->automation;
        $this->view->automation = $this->zanode->getPairs();
        $this->view->nodeList   = $this->zanode->getAutomationByProduct($productID);
        $this->view->productID  = $productID;
        $this->view->products   = $this->product->getPairs('', 0, '', 'all');

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
        if($_POST)
        {
            $scene = form::data($this->config->testcase->form->createScene)->get();

            $this->testcase->createScene($scene);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 记录父场景 ID，便于下次创建场景时默认选中父场景。*/
            /* Record the ID of the parent scene, so that the parent scene will be selected by default when creating a scene next time. */
            helper::setcookie('lastCaseScene', $scene->parent);

            $useSession = $this->app->tab != 'qa' && $this->session->caseList && strpos($this->session->caseList, 'dynamic') === false;
            $locate     = $useSession ? $this->session->caseList : inlink('browse', "productID={$scene->product}&branch={$scene->branch}&browseType=all&param={$scene->module}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->assignCreateSceneVars($productID, $branch, $moduleID);

        $this->display();
    }

    /**
     * Ajax get module scenes.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $sceneID
     * @access public
     * @return void
     */
    public function ajaxGetScenes(int $productID, int $branch = 0, int $moduleID = 0, int $sceneID = 0)
    {
        $optionMenu = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0, $branch, $sceneID);

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
    public function ajaxGetOptionMenu(int $rootID, int|string $branch = 0, int $rootModuleID = 0, string $returnType = 'html', string $fieldID = ''): bool
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
        $count = $this->dao->select('COUNT(*) AS count')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('parent')->eq($sceneID)->fetch('count');
        if($count)
        {
            if($confirm != 'yes')
            {
                $confirmURL = inlink('deleteScene', "sceneID={$sceneID}&confirm=yes");
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->testcase->hasChildren}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
            }

            $this->loadModel('action');
            $scenes = $this->dao->select('id')->from(TABLE_SCENE)->where('deleted')->eq('0')->andWhere('path')->like($scene->path . '%')->fetchPairs();
            $this->dao->update(TABLE_SCENE)->set('deleted')->eq('1')->where('deleted')->eq(0)->andWhere('path')->like($scene->path . '%')->exec();
            foreach($scenes as $sceneID) $this->action->create('scene', $sceneID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
        }
        else
        {
            $this->testcase->delete(TABLE_SCENE, $sceneID);
        }

        $locateLink = $this->session->caseList ? $this->session->caseList : inlink('browse', "productID={$scene->product}");
        return $this->send(array('result' => 'success', 'load' => $locateLink));
    }

    /**
     * Edit scene.
     *
     * @param  int $sceneId
     * @param  int $executionID
     * @access public
     * @return void
     */
    public function editScene($sceneID, $executionID = 0)
    {
        $this->loadModel('story');

        /* Update scene. */
        if(!empty($_POST))
        {
            $changes = array();
            $files   = array();

            $sceneResult = $this->testcase->updateScene($sceneID);
            if(!$sceneResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action');
            $sceneID = $sceneResult['id'];

            $this->action->create('scene', $sceneID - CHANGEVALUE, 'Edited');
            $this->executeHooks($sceneID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $sceneID));

            $scene   = $this->dao->findById((int)$sceneID - CHANGEVALUE)->from(TABLE_SCENE)->fetch();
            $product = $scene->product;
            $this->executeHooks($product);

            helper::setcookie('caseModule', $scene->module);

            $loadLink = $this->createLink('testcase', 'browse', "root={$product}" . ($scene->module ? "&branch=0&type=byModule&param={$scene->module}" : ''));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $loadLink, 'closeModal' => true));
        }

        /* Get scene. */
        $scene = $this->dao->select('*')->from(VIEW_SCENECASE)->where('id')->eq($sceneID)->andWhere('isCase')->eq(2)->fetch();

        if(!$scene) return print(js::error($this->lang->notFound) . js::locate('back'));

        $productID = $scene->product;
        $product   = $this->product->getById($productID);
        if(!isset($this->products[$productID])) $this->products[$productID] = $product->name;

        $title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->editScene;
        $position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);

        /* Set menu. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu(null);
        if($this->app->tab == 'qa') $this->testcase->setMenu($this->products, $productID, $scene->branch);

        /* Display status of branch. */
        $branches = $this->loadModel('branch')->getList($productID, isset($objectID) ? $objectID : 0, 'all');
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        if(!isset($branchTagOption[$scene->branch]))
        {
            $caseBranch = $this->branch->getById($scene->branch, $scene->product, '');
            $branchTagOption[$scene->branch] = $scene->branch == BRANCH_MAIN ? $caseBranch : ($caseBranch->name . ($caseBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
        }

        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $scene->branch);

        if(!isset($moduleOptionMenu[$scene->module])) $moduleOptionMenu += $this->tree->getModulesName($scene->module);

        if($scene->module)
        {
            $sceneOptionMenu = $this->testcase->getSceneMenu($productID, $scene->module, $viewType = 'case', $startSceneID = 0,  $scene->branch, $sceneID);
        }
        else
        {
            $sceneOptionMenu = $this->testcase->getSceneMenu($productID, 0, $viewType = 'case', $startSceneID = 0,  $scene->branch, $sceneID);
        }

        if(!isset($sceneOptionMenu[$scene->parent])) $sceneOptionMenu += $this->testcase->getScenesName($scene->parent);

        /* Get product and branches. */
        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID = $this->app->tab == 'project' ? $scene->project : $executionID;
        }

        $this->view->productID        = $productID;
        $this->view->product          = $product;
        $this->view->products         = $this->products;
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->sceneOptionMenu  = $sceneOptionMenu;
        $this->view->stories          = $this->story->getProductStoryPairs($productID, $scene->branch, 0, 'all','id_desc', 0, 'full', 'story', false);

        $forceNotReview = $this->testcase->forceNotReview();
        if($forceNotReview) unset($this->lang->testcase->statusList['wait']);

        $this->view->title           = $title;
        $this->view->branch          = 0;
        $this->view->currentModuleID = $scene->module;
        $this->view->currentParentID = $scene->parent;
        $this->view->users           = $this->user->getPairs('noletter');

        $this->view->actions         = $this->loadModel('action')->getList('case', $sceneID);
        $this->view->gobackLink      = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
        $this->view->forceNotReview  = $forceNotReview;
        $this->view->scene           = $scene;
        $this->view->executionID     = $executionID;

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
     * 导出 xmind 格式的用例。
     * Export xmind.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function exportXMind(int $productID, int $moduleID, string $branch)
    {
        if($_POST)
        {
            $configResult = $this->testcase->saveXmindConfig();
            if($configResult['result'] == 'fail') return $this->send($configResult);

            $imoduleID = $this->post->imodule ? $this->post->imoduleID : 0;
            $context   = $this->testcase->getXmindExport($productID, $imoduleID, $branch);

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

            $xmlDoc = $this->testcaseZen->createXmlDoc($productID, $productName, $context);

            $xmlStr = $xmlDoc->saveXML();
            $this->fetch('file', 'sendDownHeader', array('fileName' => $productName, 'mm', $xmlStr));
        }

        $product = $this->product->getByID($productID);

        $this->view->settings         = $this->testcase->getXmindConfig();
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
            if($_FILES['file']['size'] == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileNotEmpty));

            $configResult = $this->testcase->saveXmindConfig();
            if($configResult['result'] == 'fail') return $this->send($configResult);

            $tmpName  = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $extName  = trim(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)));
            if($extName != 'xmind') return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorFileFormat));

            $newPureName  = $this->app->user->id."-xmind";
            $importFolder = $this->app->getTmpRoot() . "import";
            if(!is_dir($importFolder)) mkdir($importFolder, 0755, true);

            $dest = $this->app->getTmpRoot() . "import/".$newPureName.$extName;
            if(!move_uploaded_file($tmpName, $dest)) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload));

            $extractFolder   =  $this->app->getTmpRoot() . "import/".$newPureName;
            $this->classFile = $this->app->loadClass('zfile');
            if(is_dir($extractFolder)) $this->classFile->removeDir($extractFolder);

            $this->app->loadClass('pclzip', true);
            $zip = new pclzip($dest);

            $files      = $zip->listContent();
            $removePath = $files[0]['filename'];
            if($zip->extract(PCLZIP_OPT_PATH, $extractFolder, PCLZIP_OPT_REMOVE_PATH, $removePath) == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->errorXmindUpload));

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

            if($fetchResult['result'] == 'fail') return $this->send($fetchResult);

            $this->session->set('xmindImport', $extractFolder);
            $this->session->set('xmindImportType', $fetchResult['type']);

            $pId = $fetchResult['pId'];

            return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => $this->createLink('testcase', 'showXmindImport', "productID=$pId&branch=$branch"), 'closeModal' => true));
        }

        $this->view->settings = $this->testcase->getXmindConfig();

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

        $folder = $this->session->xmindImport;
        $type   = $this->session->xmindImportType;
        $data   = array();
        if($type == 'xml')
        {
            $xmlPath = "$folder/content.xml";
            $results = $this->testcase->getXmindImport($xmlPath);
            $results = json_decode($results, true);
        }
        else
        {
            $jsonPath = "$folder/content.json";
            $jsonStr  = file_get_contents($jsonPath);
            $results  = json_decode($jsonStr, true);
        }

        $scenes = array();
        if(!empty($results[0]['rootTopic'])) $scenes = $this->testcaseZen->processScene($results[0]['rootTopic']);

        $this->view->title            = $this->lang->testcase->xmindImport;
        $this->view->settings         = $config;
        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->product          = $product;
        $this->view->scenes           = $scenes;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        $this->view->gobackLink       = $this->createLink('testcase', 'browse', "productID=$productID");
        $this->view->jsLng            = $jsLng;

        $this->display();
    }
}
