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

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->testcaseZen->assignCasesAndScenesForBrowse($productID, $branch, $browseType, ($browseType == 'bysearch' ? $queryID : $suiteID), $moduleID, $caseType, $orderBy, $pager);
        $this->testcaseZen->assignModuleTreeForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignProductAndBranchForBrowse($productID, $branch, $projectID);
        $this->testcaseZen->assignForBrowse($productID, $branch, $browseType, $projectID, $param, $moduleID, $suiteID, $caseType);

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
    public function groupCase(int $productID = 0, string $branch = '', string $groupBy = 'story', int $projectID = 0, string $caseType = '')
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
        foreach($cases as $case)
        {
            if($groupBy == 'story') $groupCases[$case->story][] = $case;
        }

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

        $this->app->loadLang('execution');
        $this->app->loadLang('task');

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->projectID   = $projectID;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->cases       = $cases;
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
            $productID = (int)$productID;
            $product   = $this->product->getByID($productID);
            if(!$product->shadow) $this->lang->modulePageNav = $this->product->select($products, $productID, 'testcase', 'zeroCase', $projectID, $branchID);
        }
        else
        {
            $products  = $this->product->getPairs();
            $productID = $this->product->saveState($productID, $products);
            $productID = (int)$productID;
            $product   = $this->product->getByID($productID);
            $this->loadModel('qa');
            $this->app->rawModule = 'testcase';
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->qa->setMenu($products, $productID, $branchID);
        }

        /* Append id for second sort. */
        $sort    = common::appendOrder($orderBy);
        $stories = $this->story->getZeroCase($productID, $branchID, $sort);

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal = count($stories);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $stories  = array_chunk($stories, $pager->recPerPage);

        $this->lang->testcase->featureBar['zerocase'] = $this->lang->testcase->featureBar['browse'];

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
            $case = $this->testcaseZen->buildCaseForCase($from, $param);

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

        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $this->view->productID        = $productID;
        $this->view->users            = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->gobackLink       = isset($output['from']) && $output['from'] == 'global' ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
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
            $caseIdList = $this->testcase->batchCreate($productID, $branch, $storyID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(helper::isAjaxRequest('modal')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $caseIdList));

            helper::setcookie('caseModule', 0);
            $currentModule = $this->app->tab == 'project' ? 'project'  : 'testcase';
            $currentMethod = $this->app->tab == 'project' ? 'testcase' : 'browse';
            $projectParam  = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($currentModule, $currentMethod, "{$projectParam}productID={$productID}&branch={$branch}&browseType=all&param=0&caseType=&orderBy=id_desc")));
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
        $storyPairs += $storyID ? array($storyID => $story->id . ':' . $story->title) : array();

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
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, 'case', 0, $branch === 'all' ? 0 : $branch);
        $sceneOptionMenu  = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->batchCreate;
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

        if(!$case) return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound, 'closeModal' => true));

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
        $case = $this->testcase->getByID($caseID);
        if(!$case) return print(js::error($this->lang->notFound) . js::locate('back'));

        $testtasks = $this->loadModel('testtask')->getGroupByCases($caseID);
        $testtasks = empty($testtasks[$caseID]) ? array() : $testtasks[$caseID];

        if(!empty($_POST))
        {
            if(!empty($_FILES['scriptFile'])) unset($_FILES['scriptFile']);

            $changes = array();
            if(!$comment)
            {
                $changes = $this->testcase->update($caseID, $testtasks);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($this->post->comment != '' || !empty($changes))
            {
                $this->loadModel('action');
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('case', $caseID, $action, $this->post->comment);

                $this->action->logHistory($actionID, $changes);

                if($case->status != 'wait' && $this->post->status == 'wait') $this->action->create('case', $caseID, 'submitReview');
            }

            $message = $this->executeHooks($caseID);
            if(!$message) $message = $this->lang->saveSuccess;

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $caseID));

            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $this->createLink('testcase', 'view', "caseID={$caseID}")));
        }

        $case = $this->testcaseZen->preProcessForEdit($case);

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
     * @param  int    $branch
     * @param  string $type
     * @param  string $tab
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $branch = 0, $type = 'case', $tab = '')
    {
        if(!$this->post->caseIdList) return print(js::locate($this->session->caseList));

        $caseIdList = array_unique($this->post->caseIdList);
        $testtasks  = $this->loadModel('testtask')->getGroupByCases($caseIdList);
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

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->caseList));
        }

        $branchProduct = false;

        if($this->app->tab == 'project')               $this->loadModel('project')->setMenu($this->session->project);
        if($this->app->tab == 'qa' and $type != 'lib') $this->testcase->setMenu($this->products, $productID, $branch);
        if($this->app->tab == 'execution')             $this->loadModel('execution')->setMenu($this->session->execution);

        /* The cases of a product. */
        if($productID)
        {
            /* Get the edited cases. */
            $cases = $this->testcase->getByList($caseIdList);

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
                $modules    = array();
                $libModules = $this->tree->getOptionMenu($libID, 'caselib', 0, $branch);
                foreach($libModules as $moduleID => $module) $modules[] = array('text' => $module, 'value' => $moduleID);

                $this->view->title = $libraries[$libID] . $this->lang->colon . $this->lang->testcase->batchEdit;
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

                    foreach($branchTagOption as $branchID => $branchName)
                    {
                        $branchModules = $this->tree->getOptionMenu($productID, 'case', 0, $branchID);
                        foreach($branchModules as $moduleID => $module) $modules[$productID][$branchID][] = array('text' => $module, 'value' => $moduleID);
                    }
                }
                else
                {
                    $branchModules = $this->tree->getOptionMenu($productID, 'case');
                    foreach($branchModules as $moduleID => $module) $modules[$productID][BRANCH_MAIN][] = array('text' => $module, 'value' => $moduleID);
                }

                $this->view->branchTagOption = array($productID => $branchTagOption);
                $this->view->title           = $product->name . $this->lang->colon . $this->lang->testcase->batchEdit;
            }
        }
        else
        {
            /* Get the edited cases. */
            $cases = $this->dao->select('t1.*,t2.id as runID')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.id')->in($caseIdList)
                ->fetchAll('id');
            $caseIdList = array_keys($cases);

            /* The cases of my. */
            $this->app->loadLang('my');
            $this->lang->testcase->menu = $this->lang->my->menu->work;
            $this->lang->my->menu->work['subModule'] = 'testcase';

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

                $modules     = array();
                $modulePairs = $this->tree->getOptionMenu($product->id, 'case', 0, $branches);
                foreach($modulePairs as $moduleID => $module) $modules[] = array('text' => $module, 'value' => $moduleID);
                if($product->type == 'normal') $modules = array(0 => $modules);
            }

            $this->view->products        = $products;
            $this->view->branchTagOption = $branchTagOption;
        }

        /* Judge whether the editedCases is too large and set session. */
        $countInputVars  = count($cases) * (count(explode(',', $this->config->testcase->custom->batchEditFields)) + 3);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        $stories = $this->loadModel('story')->getProductStoryPairs($productID, $branch);

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customBatchEditFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->batchEditFields;

        /* Append module when change product type. */
        $modulePairs = array();
        $scenePairs  = array();
        foreach($cases as $case)
        {
            $caseProduct = $type == 'lib' ? $productID : $case->product;
            if(isset($modules[$caseProduct][$case->branch]))
            {
                $modulePairs[$case->id] = $modules[$caseProduct][$case->branch];
            }
            else
            {
                $modulePairs[$case->id] = isset($modules[$caseProduct]) ? $modules[$caseProduct][0] : array('text' => $this->tree->getModulesName($case->module), 'value' => $case->module);
            }

            $scenes = $this->testcase->getSceneMenu($productID, $case->module, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
            foreach($scenes as $sceneID => $scene) $scenePairs[$case->id][] = array('text' => $scene, 'value' => $sceneID);
            if(!isset($scenes[$case->scene])) $scenePairs[$case->id][] = array('text' => '/' .$this->testcase->fetchSceneName($case->scene), 'value' => $case->scene);
        }

        /* Assign. */
        $this->view->scenePairs     = $scenePairs;
        $this->view->stories        = $stories;
        $this->view->caseIdList     = $caseIdList;
        $this->view->productID      = $productID;
        $this->view->branchProduct  = $branchProduct;
        $this->view->priList        = $this->lang->testcase->priList;
        $this->view->typeList       = $this->lang->testcase->typeList;
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
            if($this->post->result == false) return $this->send(array('result' => 'fail', 'message' => $this->lang->testcase->mustChooseResult));

            $changes = $this->testcase->review($caseID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(is_array($changes))
            {
                $result = $this->post->result;
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Reviewed', $this->post->comment, ucfirst($result));
                $this->action->logHistory($actionID, $changes);

                $this->executeHooks($caseID);

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            }
        }

        $this->view->testcase = $this->testcase->getByID($caseID);
        $this->view->users    = $this->user->getPairs('noletter|noclosed|nodeleted');
        $this->view->case     = $this->testcase->getById($caseID);
        $this->view->actions  = $this->loadModel('action')->getList('case', $caseID);
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
        if($this->post->caseIdList)
        {
            $caseIdList = $this->post->caseIdList;
            $caseIdList = array_unique($caseIdList);
            unset($_POST['caseIdList']);
            $allChanges = $this->testcase->batchChangeBranch($caseIdList, $branchID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $caseID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('case', $caseID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->caseList));
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
        if($this->post->caseIdList)
        {
            $caseIdList = $this->post->caseIdList;
            $caseIdList = array_unique($caseIdList);
            unset($_POST['caseIdList']);
            $allChanges = $this->testcase->batchChangeModule($caseIdList, $moduleID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if (!empty($allChanges[1]))
            {
                foreach($allChanges[1] as $caseID => $changes)
                {
                    $this->loadModel('action');
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }

            if (!empty($allChanges[0]))
            {
                foreach($allChanges[0] as $sceneID => $changes)
                {
                    $this->loadModel('action');
                    $actionID = $this->action->create('scene', $sceneID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->caseList));
    }

    /**
     * Batch review case.
     *
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchCaseTypeChange($result)
    {
        if(!$this->post->caseIdList) return print(js::locate($this->session->caseList, 'parent'));
        $caseIdList = array_unique($this->post->caseIdList);
        $this->testcase->batchCaseTypeChange($caseIdList, $result);

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->caseList));
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
        $objectID  = 0;
        if($this->app->tab == 'project')   $objectID = $case->project;
        if($this->app->tab == 'execution') $objectID = $case->execution;

        /* Unset search field 'plan' in single project. */
        unset($this->config->bug->search['fields']['product']);
        if($case->project and ($this->app->tab == 'project' or $this->app->tab == 'execution'))
        {
            $project = $this->loadModel('project')->getById($case->project);
            if(!$project->hasProduct and $project->model == 'waterfall') unset($this->config->bug->search['fields']['plan']);
        }

        $this->bug->buildSearchForm($case->product, $this->products, $queryID, $actionURL, $objectID);

        /* Get cases to link. */
        $bugs2Link = $this->testcase->getBugs2Link($caseID, $browseType, $queryID);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal  = count($bugs2Link);
        $pager     = new pager($recTotal, $recPerPage, $pageID);
        $bugs2Link = array_chunk($bugs2Link, $pager->recPerPage);

        /* Assign. */
        $this->view->title      = $this->lang->testcase->linkBugs;
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
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $from == 'view' ? inlink('view', "caseID=$caseID&version=$case->version&from=testtask&taskID=$taskID") : true));
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
        return $this->send(array('load' => true));
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
        if($reload) return $this->send(array('load' => true));
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
        if($this->post->caseIdList)
        {
            $caseIdList = array_unique($this->post->caseIdList);
            foreach($caseIdList as $caseID) $this->confirmStoryChange($caseID,false);
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->session->caseList));
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
            if($this->session->testcaseOnlyCondition)
            {
                $caseIdList = array();
                if($taskID) $caseIdList = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();

                $cases = $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQueryCondition)
                    ->beginIF($taskID)->andWhere('id')->in($caseIdList)->fi()
                    ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                    ->orderBy($orderBy)
                    ->beginIF($this->post->limit)->limit($this->post->limit)->fi()
                    ->fetchAll('id');
            }
            else
            {
                $cases   = array();
                $orderBy = " ORDER BY " . str_replace(array('|', '^A', '_'), ' ', $orderBy);
                $stmt    = $this->dao->query($this->session->testcaseQueryCondition . $orderBy . ($this->post->limit ? ' LIMIT ' . $this->post->limit : ''));
                while($row = $stmt->fetch())
                {
                    $caseID = isset($row->case) ? $row->case : $row->id;
                    if($this->post->exportType == 'selected' and strpos(",{$this->cookie->checkedItem},", ",$caseID,") === false) continue;
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

            return $this->send(array('load' => inlink('showImport', "productID=$productID&branch=$branch"), 'closeModal' => true));
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

        if(empty($libID) or !isset($libraries[$libID])) $libID = key($libraries);

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
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('project', 'testcase', "projectID={$this->session->project}&productID=$productID")));
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', "productID=$productID")));
                }
            }
            else
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('showImport', "productID=$productID&branch=$branch&pagerID=" . ((int)$this->post->pagerID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''))));
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
                                if(!empty($step))
                                {
                                    $caseStep[$num]['content'] = $step;
                                    $caseStep[$num]['number']  = $num;
                                }
                                $caseStep[$num]['type']    = 'item';
                                $caseStep[$parent]['type'] = 'group';
                            }
                            elseif(preg_match('/^([0-9]+)([.、]{1})/U', $step, $out))
                            {
                                $num     = $out[1];
                                $sign    = $out[2];
                                $signbit = $sign == '.' ? 1 : 3;
                                $step    = trim(substr($step, strpos($step, $sign) + $signbit));
                                if(!empty($step))
                                {
                                    $caseStep[$num]['content'] = $step;
                                    $caseStep[$num]['number']  = $num;
                                }
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
                                    $caseStep[$num]['number']  = $num;
                                }
                                if($field == 'stepExpect' and isset($stepData[$row]['desc']))
                                {
                                    end($stepData[$row]['desc']);
                                    $num = key($stepData[$row]['desc']);
                                    $caseStep[$num]['content'] = $step;
                                    $caseStep[$num]['number']  = $num;
                                }
                            }
                        }
                        unset($num);
                        unset($sign);
                        $stepVars += count($caseStep, COUNT_RECURSIVE) - count($caseStep);
                        $stepData[$row][$stepKey] = array_values($caseStep);
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

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            // if(!empty($_POST['syncToZentao']))
            //     $this->zanode->syncCasesToZentao($_POST['scriptPath']);

            // $nodeID = $_POST['node'];
            // $node   = $this->zanode->getNodeByID($_POST['node']);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('testcase', 'browse', "productID={$this->post->product}")));
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
     * Create scene.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  string $from
     * @param  string $param
     * @param  int    $storyID
     * @param  string $extras
     * @access public
     * @return void
     */
    public function createScene($productID, $branch = '', $moduleID = 0, $from = '', $param = 0, $storyID = 0, $extras = '')
    {
        if(!empty($_POST))
        {
            $response['result'] = 'success';
            helper::setcookie('lastCaseScene', (int)$this->post->parent);

            $sceneResult = $this->testcase->createScene();
            if(!$sceneResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $sceneID = $sceneResult['id'];
            $this->loadModel('action');
            $this->action->create('scene', $sceneID, 'Opened');

            $useSession          = $this->app->tab != 'qa' and $this->session->caseList and strpos($this->session->caseList, 'dynamic') === false;
            $response['locate']  = $useSession ? $this->session->caseList : $this->createLink('testcase', 'browse', "root={$this->post->product}&branch=$branch&type=byModule&param=" . $this->post->module);
            $response['message'] = $this->lang->saveSuccess;

            return $this->send($response);
        }

        /* Set menu. */
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));
        $this->qa->setMenu($this->products, $productID, $branch);

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

        $currentModuleID = $moduleID ? (int)$moduleID : (int)$this->cookie->lastCaseModule;
        $currentParentID = (int)$this->cookie->lastCaseScene;

        $modules = array();
        if($currentModuleID)
        {
            $productModules = $this->tree->getOptionMenu($productID, 'story');
            $storyModuleID  = array_key_exists($currentModuleID, $productModules) ? $currentModuleID : 0;
            $modules        = $this->loadModel('tree')->getStoryModule($storyModuleID);
            $modules        = $this->tree->getAllChildID($modules);
        }

        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->newScene;
        $this->view->productID        = $productID;
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->currentParentID  = $currentParentID;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('testcase', 'browse', "productID=$productID") : '';
        $this->view->showFields       = $this->config->testcase->custom->createFields;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        $this->view->branch           = $branch;
        $this->view->product          = $product;
        $this->view->branches         = $branches;
        $this->view->sceneTitle       = '';
        $this->view->sceneOptionMenu  = $this->testcase->getSceneMenu($productID, $moduleID, $viewType = 'case', $startSceneID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);

        $this->display();
    }

    /**
     * Ajax get module scenes.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $stype
     * @param  int    $storyID
     * @param  bool   $onlyOption
     * @param  string $status
     * @param  string $limit
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

        $items = array();
        foreach($optionMenu as $optionID => $optionName)
        {
            $items[] = array('text' => $optionName, 'value' => $optionID);
        }

        return print(json_encode($items));
    }

    /**
     * Ajax get option menu.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  int    $branch
     * @param  int    $rootModuleID
     * @param  string $returnType
     * @param  string $fieldID
     * @param  bool   $needManage
     * @param  string $extra
     * @param  int    $currentModuleID
     * @access public
     * @return void
     */
    public function ajaxGetOptionMenu($rootID, $viewType = 'story', $branch = 0, $rootModuleID = 0, $returnType = 'html', $fieldID = '', $needManage = false, $extra = '', $currentModuleID = 0)
    {
        $this->loadModel('tree');

        if($viewType == 'task')
        {
            $optionMenu = $this->tree->getTaskOptionMenu($rootID, 0, 0, $extra);
        }
        else
        {
            $optionMenu = $this->tree->getOptionMenu((int)$rootID, $viewType, $rootModuleID, $branch);
        }

        if($returnType == 'html')
        {
            if($viewType == 'line')
            {
                $lineID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('id_desc')->limit(1)->fetch('id');
                $output = html::select("line", $optionMenu, $lineID, "class='form-control'");
                $output .= "<span class='input-group-addon' style='border-radius: 0px 2px 2px 0px; border-right-width: 1px;'>";
                $output .= html::a($this->createLink('tree', 'browse', "rootID=$rootID&view=$viewType&currentModuleID=0&branch=$branch", '', true), $viewType == 'line' ? $this->lang->tree->manageLine : $this->lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                $output .= '</span>';

                die($output);
            }
            else
            {
                $items = array();
                foreach($optionMenu as $optionID => $optionName)
                {
                    $items[] = array('text' => $optionName, 'value' => $optionID);
                }

                return print(json_encode($items));
            }
        }

        if($returnType == 'mhtml')
        {
            $changeFunc = '';
            if($viewType == 'task') $changeFunc = "";
            $field  = $fieldID ? "modules[$fieldID]" : 'module';
            $output = html::select("$field", $optionMenu, '', "class='input' $changeFunc");
            die($output);
        }

        if($returnType == 'json') die(json_encode($optionMenu));
    }

    /**
     * Ajax get scenes.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $stype
     * @param  int    $storyID
     * @param  bool   $onlyOption
     * @param  string $status
     * @param  string $limit
     * @param  string $type
     * @param  int    $hasParent
     * @param  string $number
     * @param  int    $currentScene
     * @access public
     * @return void
     */
    public function ajaxGetScenesForBC($productID, $branch = 0, $moduleID = 0, $stype = 1, $storyID = 0, $onlyOption = 'false', $status = '', $limit = 0, $type = 'full', $hasParent = 1, $number = '', $currentScene = 0)
    {
        $optionMenu = $this->testcase->getSceneMenu($productID, $moduleID, 'case', 0, $branch, $currentScene);

        if($stype == 1)
        {
            $optionMenu['ditto'] = $this->lang->testcase->ditto;
            $output = html::select("parent", $optionMenu, "", "class='form-control'");
            return print($output);
        }
        else
        {
            $scenes = array();
            foreach($optionMenu as $sceneID => $sceneName) $scenes[] = array('value' => $sceneID, 'text' => $sceneName);
            return $this->send($scenes);
        }

    }

    /**
     * Batch change scene.
     *
     * @param  int $sceneId
     * @access public
     * @return void
     */
    public function batchChangeScene($sceneId)
    {
        if($this->post->caseIdList)
        {
            $caseIdList = $this->post->caseIdList;
            $caseIdList = array_unique($caseIdList);
            unset($_POST['caseIdList']);
            $allChanges = $this->testcase->batchChangeScene($caseIdList, $sceneId);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($allChanges[1]))
            {
                foreach($allChanges[1] as $caseID => $changes)
                {
                    $this->loadModel('action');
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                }
            }

            if(!empty($allChanges[0]))
            {
                foreach($allChanges[0] as $sceneID => $changes)
                {
                    $this->loadModel('action');
                    $actionID = $this->action->create('scene', $sceneID - CHANGEVALUE, 'Edited');
                }
            }
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
        $now       = helper::now();
        $currentID = $this->post->sourceId;
        $targetId  = $this->post->targetId;

        $targetScene  = $this->dao->findById((int)$targetId)->from(VIEW_SCENECASE)->fetch();
        $currentScene = $this->dao->findById((int)$currentID)->from(VIEW_SCENECASE)->fetch();

        $product = $targetScene->product;
        $module  = $targetScene->module;
        if($targetScene->isCase == 2)
        {
            if($currentScene->isCase == 2)
            {
                $childPath = $targetScene->path . "$currentID,";
                $grade     = $targetScene->grade + 1;

                $this->dao->update(TABLE_SCENE)->set('parent')->eq($targetId)
                    ->set('path')->eq($childPath)
                    ->set('grade')->eq($grade)
                    ->set('product')->eq($product)
                    ->set('module')->eq($module)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->set('lastEditedDate')->eq($now)
                    ->where('id')->eq($currentID-CHANGEVALUE)
                    ->exec();

                $children = $this->dao->select('*')->from(VIEW_SCENECASE)
                    ->where('deleted')->eq(0)
                    ->andWhere('path')->like("%,$currentID,%")
                    ->orderBy('grade')
                    ->fetchAll('id');

                foreach($children as $id => $childScene)
                {
                    if($id && $id != $currentID)
                    {
                        if($childScene->parent)
                        {
                            $parentScene = $this->dao->findById((int)$childScene->parent-CHANGEVALUE)->from(TABLE_SCENE)->fetch();
                            if($childScene->isCase == 2)
                            {
                                if($childScene->grade > $currentScene->grade)
                                {
                                    $childPath = $parentScene->path . "$id,";
                                    $grade     = $parentScene->grade + 1;

                                    $this->dao->update(TABLE_SCENE)->set('path')->eq($childPath)
                                        ->set('grade')->eq($grade)
                                        ->set('product')->eq($parentScene->product)
                                        ->set('module')->eq($parentScene->module)
                                        ->set('lastEditedBy')->eq($this->app->user->account)
                                        ->set('lastEditedDate')->eq($now)
                                        ->where('id')->eq($id - CHANGEVALUE)
                                        ->exec();
                                }
                            }
                            else
                            {
                                $this->dao->update(TABLE_CASE)
                                    ->set('product')->eq($parentScene->product)
                                    ->set('module')->eq($parentScene->module)
                                    ->set('lastEditedBy')->eq($this->app->user->account)
                                    ->set('lastEditedDate')->eq($now)
                                    ->where('id')->eq($id)
                                    ->exec();
                            }
                        }
                    }
                }
            }
            else
            {
                $this->dao->update(TABLE_CASE)->set('scene')->eq($targetId)
                    ->set('product')->eq($product)
                    ->set('module')->eq($module)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->set('lastEditedDate')->eq($now)
                    ->where('id')->eq($currentID)
                    ->exec();
            }
        }
    }

    /**
     * Delete scene.
     *
     * @param  int    $sceneId
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteScene($sceneID, $confirm = 'no')
    {
        $scene = $this->dao->select('*')->from(VIEW_SCENECASE)->where('id')->eq($sceneID)->andWhere('isCase')->eq(2)->fetch();

        if($confirm == 'no') return print(js::confirm(sprintf($this->lang->testcase->confirmDeleteScene,addslashes($scene->title)), $this->createLink('testcase', 'deleteScene', "sceneID=$sceneID&confirm=yes")));

        $childrenCount = $this->dao->select('count(*) as count')->from(VIEW_SCENECASE)->where('parent')->eq($sceneID)->andWhere('deleted')->eq(0)->fetch('count');
        if($childrenCount)
        {
            if($confirm != "wait") return print(js::confirm(sprintf($this->lang->testcase->hasChildren), $this->createLink('testcase', 'deleteScene', "sceneID=$sceneID&confirm=wait")));
            $all = $this->dao->select('id,isCase')->from(VIEW_SCENECASE)->where('path')->like($scene->path . '%')->andWhere('deleted')->eq(0)->fetchAll();

            foreach($all as $v)
            {
                if($v->isCase == 2)
                {
                    $this->testcase->delete(TABLE_SCENE, $v->id - CHANGEVALUE);
                }
                else
                {
                    $this->testcase->delete(TABLE_CASE, $v->id);
                }
            }
        }
        else
        {

            $this->testcase->delete(TABLE_SCENE, $sceneID-CHANGEVALUE);
        }

        echo js::reload('parent');
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
        $idList  = explode(',', trim($this->post->scenes, ','));
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'sort') === false) return false;
        /* Get list of original scenes and cases, and sort with orderBy param. */
        $scenesMap = $this->dao->select('id,sort,isCase')->from(VIEW_SCENECASE)->where('id')->in($idList)->orderBy($orderBy)->fetchAll('id');

        foreach($scenesMap as $scene)
        {
            /* Compare with sorted list from front-end. */
            $newID = array_shift($idList);
            if($scene->id == $newID) continue;

            /* Change sort value of scene. */
            if ($scenesMap[$newID]->isCase == 2)
            {
                $this->dao->update(TABLE_SCENE)
                    ->set('sort')->eq($scene->sort)
                    ->set('lastEditedBy')->eq($this->app->user->account)
                    ->set('lastEditedDate')->eq(helper::now())
                    ->where('id')->eq($newID - CHANGEVALUE)
                    ->exec();
                continue;
            }

            /* Change sort value of case. */
            $this->dao->update(TABLE_CASE)
                ->set('sort')->eq($scene->sort)
                ->set('lastEditedBy')->eq($this->app->user->account)
                ->set('lastEditedDate')->eq(helper::now())
                ->where('id')->eq($newID)
                ->exec();
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
            if($configResult['result'] == 'fail') return $this->send($configResult);

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
