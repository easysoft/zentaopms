<?php
/**
 * The control file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @link        https://www.zentao.net
 */
class product extends control
{
    public $products = array();

    /**
     * Construct function.
     *
     * @param  string moduleName
     * @param  string methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(!isset($this->app->user)) return;

        /* Load need modules. */
        $this->loadModel('user');

        /* Get all products, if no, goto the create page. */
        $this->products = $this->product->getPairs('nocode', 0, '', 'all');
        if($this->product->checkLocateCreate($this->products)) $this->locate($this->createLink('product', 'create'));

        $this->view->products = $this->products;
    }

    /**
     * Index page, to browse.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function index(int $productID = 0)
    {
        /* Check product id and get product branch. */
        $productID = $this->product->saveVisitState($productID, $this->products);
        $branch    = (int)$this->cookie->preBranch;

        /* Set Menu. */
        if($this->app->getViewType() != 'mhtml') unset($this->lang->product->menu->index);
        if($this->app->viewType == 'mhtml') $this->product->setMenu($productID, $branch);

        /* Add create product button for view. */
        if(common::hasPriv('product', 'create')) $this->lang->TRActions = html::a($this->createLink('product', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->product->create, '', "class='btn btn-primary'");

        $this->view->title = $this->lang->product->index;
        $this->display();
    }

    /**
     * The projects which linked the product.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  string $branch
     * @param  string $involved
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function project(string $status = 'all', int $productID = 0, string $branch = '', string $involved = '0', string $orderBy = 'order_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(!$involved) $involved = $this->cookie->involved;
        $this->productZen->setProjectMenu($productID, $branch, $this->cookie->preBranch);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get projects linked product with statistic. */
        $projectStats = $this->product->getProjectStatsByProduct($productID, $status, $branch, (bool)$involved, $orderBy, $pager);

        /* Get project pairs of same program. */
        $product  = $this->product->getByID($productID);
        $projects = $this->loadModel('project')->getPairsByProgram($product->program, 'all', false, 'order_asc', '', '', 'product');
        foreach($projectStats as $project) unset($projects[$project->id]);

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->product->project;
        $this->view->projectStats = $projectStats;
        $this->view->PMList       = $this->getPMList($projectStats);
        $this->view->product      = $product;
        $this->view->projects     = $projects;
        $this->view->status       = $status;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->branchID     = $branch;
        $this->view->branchStatus = $this->loadModel('branch')->getByID($branch, 0, 'status');
        $this->view->pager        = $pager;
        $this->display();
    }

    /**
     * 浏览产品研发/用户需求列表。
     * Browse requirements list of product.
     *
     * @param  int     $productID
     * @param  string  $branch      all|''|0
     * @param  string  $browseType
     * @param  int     $param       Story Module ID
     * @param  string  $storyType   requirement|story
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $branch = '', string $browseType = '', int $param = 0, string $storyType = 'story', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0)
    {
        $browseType = strtolower($browseType);

        /* Pre process. */
        $this->loadModel('datatable');
        $this->loadModel('execution');
        $this->loadModel('tree');
        $isProjectStory = $this->app->rawModule == 'projectstory';
        $cookieOrderBy  = zget($this->cookie, 'productStoryOrder', 'id_desc');
        $showModule     = !empty($this->config->datatable->productBrowse->showModule) ? $this->config->datatable->productBrowse->showModule : ''; //config->datatable->productBrowse may be undefined.

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Generate data. */
        $productID = $this->app->tab != 'project' ? $this->product->saveVisitState($productID, $this->products) : $productID;
        $product   = $this->productZen->getBrowseProduct($productID);
        $project   = $this->loadModel('project')->getByID($projectID);
        $branchID  = $this->productZen->getBranchID($product, $branch);
        $orderBy   = $orderBy ? $orderBy : $cookieOrderBy;
        $branch    = $branchID;

        /* ATTENTION: be careful to change the order of follow sentences. */
        $this->productZen->setMenu4Browse($projectID, $productID, $branch, $storyType);
        $this->productZen->saveAndModifyCookie4Browse($productID, $branch, $param, $browseType, $orderBy);

        /* Generate data. */
        $moduleID        = $this->productZen->getModuleId($param, $browseType);
        $moduleTree      = $this->productZen->getModuleTree($projectID, $productID, $branch, $param, $storyType, $browseType);
        $showBranch      = $this->productZen->canShowBranch($projectID, $productID, $storyType, $isProjectStory);
        $projectProducts = $this->productZen->getProjectProductList($projectID, $storyType, $isProjectStory);
        $productPlans    = $this->productZen->getProductPlans($projectProducts, $projectID, $storyType, $isProjectStory);
        $stories         = $this->productZen->getStories($projectID, $productID, $branchID, $moduleID, $param, $storyType, $browseType, $orderBy, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $queryCondition = $this->dao->get();
        $this->loadModel('common')->saveQueryCondition($queryCondition, 'story', (strpos('bysearch,reviewbyme,bymodule', $browseType) === false and !$isProjectStory));

        /* Collect story ID list. */
        $storyIdList = $this->productZen->getStoryIdList($stories);

        /* Generate data. */
        list($branchOpt, $branchTagOpt) = $this->productZen->getBranchAndTagOption($projectID, $product, $isProjectStory);
        $branchOptions                  = (empty($product) && $isProjectStory) ? $this->productZen->getBranchOptions($projectProducts, $projectID) : array();
        $storyTasks                     = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs                      = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases                     = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);
        $productName                    = ($isProjectStory and empty($productID)) ? $this->lang->product->all : $this->products[$productID];
        $modulePairs                    = $showModule ? $this->tree->getModulePairs($productID, 'story', $showModule) : array();

        /* Save session. */
        $this->productZen->saveSession4Browse($product, $storyType, $browseType, $isProjectStory);

        /* Build search form. */
        $this->productZen->buildSearchForm4Browse($project, $projectID, $productID, $branch, $param, $storyType, $browseType, $isProjectStory);

        /* Assign. */
        $this->view->title           = $productName . $this->lang->colon . ($storyType === 'story' ? $this->lang->product->browse : $this->lang->product->requirement);
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->productName     = $productName;
        $this->view->moduleID        = $moduleID;
        $this->view->stories         = $stories;
        $this->view->plans           = $this->loadModel('productplan')->getPairs($productID, ($branch === 'all' or empty($branch)) ? '' : $branch, 'unexpired,noclosed', true);
        $this->view->productPlans    = !empty($productPlans) ? array(0 => '') + $productPlans : array();
        $this->view->summary         = $this->product->summary($stories, $storyType);
        $this->view->moduleTree      = $moduleTree;
        $this->view->parentModules   = $this->tree->getParents($moduleID);
        $this->view->pager           = $pager;
        $this->view->users           = $this->user->getPairs('noletter|pofirst|nodeleted');
        $this->view->orderBy         = $orderBy;
        $this->view->browseType      = $browseType;
        $this->view->modules         = $this->tree->getOptionMenu($productID, 'story', 0, $branchID);
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = ($moduleID and $moduleID !== 'all') ? $this->tree->getByID($moduleID)->name : $this->lang->tree->all;
        $this->view->branch          = $branch;
        $this->view->branchID        = $branchID;
        $this->view->branchOptions   = $branchOptions;
        $this->view->branchOption    = $branchOpt;
        $this->view->branchTagOption = $branchTagOpt;
        $this->view->showBranch      = $showBranch;
        $this->view->storyStages     = $this->product->batchGetStoryStage($stories);
        $this->view->setModule       = true;
        $this->view->storyTasks      = $storyTasks;
        $this->view->storyBugs       = $storyBugs;
        $this->view->storyCases      = $storyCases;
        $this->view->param           = $param;
        $this->view->projectID       = $projectID;
        $this->view->products        = $this->products;
        $this->view->projectProducts = !empty($projectProducts) ? $projectProducts : array();
        $this->view->storyType       = $storyType;
        $this->view->from            = $this->app->tab;
        $this->view->modulePairs     = $modulePairs;
        $this->view->project         = $project;
        $this->view->recTotal        = $pager->recTotal;

        $this->display();
    }

    /**
     * Create a product.
     * 创建产品。可以是顶级产品，也可以是项目集下的产品。
     *
     * @param  int    $programID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create(int $programID = 0, string $extra = '')
    {
        if(!empty($_POST))
        {
            $productData = $this->productZen->buildProductForCreate();

            $productID = $this->product->create($productData, $this->post->uid, zget($_POST, 'lineName', ''));
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response = $this->productZen->responseAfterCreate($productID, $product->program);
            return $this->send($response);
        }

        $this->productZen->setCreateMenu($programID);
        $this->productZen->buildCreateForm($programID, $extra);
    }

    /**
     * 编辑产品。
     * Edit a product.
     *
     * @param  int    $productID
     * @param  string $action
     * @param  string $extra
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function edit(int $productID, string $action = 'edit', string $extra = '', int $programID = 0)
    {
        if(!empty($_POST))
        {
            $productData = $this->productZen->buildProductForEdit($this->post->acl);

            $changes = $this->product->update($productID, $productData, $this->post->uid);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($action == 'undelete') $this->loadModel('action')->undelete((int)$extra);
            $response = $this->productZen->responseAfterEdit($productID, $programID, $changes);
            return $this->send($response);
        }

        $this->productZen->setEditMenu($productID, $programID);

        $product   = $this->product->getByID($productID);
        $productID = $this->product->saveVisitState($productID, $this->products);

        $this->productZen->buildEditForm($product);
    }

    /**
     * 根据POST过来的ID列表，批量编辑相应产品。
     * Batch edit products.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function batchEdit(int $programID = 0)
    {
        if($this->post->name)
        {
            /* 从POST中获取数据，并预处理数据。 */
            $formConfig = $this->productZen->appendFlowFields($this->config->product->form->batchEdit, 'batch');
            $data       = form::data($formConfig);
            $products   = $this->productZen->prepareBatchEditExtras($data);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $result = $this->product->batchUpdate($products);
            if(dao::isError()) return $this->send($result);

            $response = $this->productZen->responseAfterBatchEdit($result, $programID);
            return $this->send($response);
        }

        /* 获取要修改的产品ID列表。*/
        $productIdList = $this->post->productIDList;
        if(empty($productIdList)) return print(js::locate($this->session->productList, 'parent'));

        /* Set menu when page come from program. */
        if($this->app->tab == 'program') $this->loadModel('program')->setMenu(0);

        /* 构造批量编辑页面表单配置数据。*/
        $this->productZen->buildBatchEditForm($programID, explode(',', $productIdList));
    }

    /**
     * Close product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function close(int $productID)
    {
        if(!empty($_POST))
        {
            $productData = $this->productZen->buildProductForClose();

            $changes = $this->product->close($productID, $productData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response = $this->productZen->responseAfterClose($productID, $changes, $this->post->comment);
            return $this->send($response);
        }

        $this->product->setMenu($productID);
        $this->productZen->buildCloseForm($productID);
    }

    /**
     * 查看产品。
     * View a product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function view(int $productID)
    {
        /* Get product. */
        $product = $this->product->getStatByID($productID);
        if(!$product) return $this->productZen->responseNotFound4View();
        $product->desc = $this->loadModel('file')->setImgSize($product->desc);

        /* Set navigation menu. */
        $this->product->setMenu($productID);

        /* Execute hooks. */
        $this->executeHooks($productID);

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->view;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->view;
        $this->view->product    = $product;
        $this->view->actions    = $this->loadModel('action')->getList('product', $productID);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->branches   = $this->loadModel('branch')->getPairs($productID);
        $this->view->reviewers  = explode(',', $product->reviewer);

        $this->display();
    }

    /**
     * 删除产品。
     * Delete a product.
     *
     * @param  int    $productID
     * @param  string $confirm   yes|no
     * @access public
     * @return void
     */
    public function delete(int $productID, string $confirm = 'no')
    {
        /* Not confirm. */
        if($confirm == 'no') return print(js::confirm($this->lang->product->confirmDelete, $this->createLink('product', 'delete', "productID=$productID&confirm=yes")));

        /* Delete product. */
        $this->product->deleteById($productID);

        /* Reset session. */
        $this->session->set('product', '');

        /* Response JSON message. */
        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;
        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));

        print(js::locate($this->createLink('product', 'all'), 'parent'));
    }

    /**
     * 产品路线图。
     * Road map of a product.
     *
     * @param  int    $productID
     * @param  stirng $branch
     * @access public
     * @return void
     */
    public function roadmap(int $productID,  string $branch = 'all')
    {
        /* Set env viriables. */
        $this->product->setMenu($productID, $branch);
        $this->productZen->saveSession4Roadmap();

        /* Generate data. */
        $product = $this->product->getByID($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'showErrorNone', 'fromModule=product'));

        $roadmaps = $this->product->getRoadmap($productID, $branch);
        $branches = $product->type == 'normal' ? array(0 => '') : $this->loadModel('branch')->getPairs($productID);

        /* Assign view data. */
        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->roadmap;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->roadmap;
        $this->view->product    = $product;
        $this->view->roadmaps   = $roadmaps;
        $this->view->branches   = $branches;

        $this->display();
    }

    /**
     * 产品动态。
     * Product dynamic.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  int    $param
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction next|pre
     * @access public
     * @return void
     */
    public function dynamic(int $productID = 0, string $type = 'today', int $param = 0, int $recTotal = 0, string $date = '', string $direction = 'next')
    {
        $this->loadModel('action');

        /* Save env data. */
        $this->productZen->saveBackUriSession4Dynamic();
        $this->product->setMenu($productID, 0, $type);

        /* Generate order by string. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* Get user account. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->user->getByID($param, 'id');
            if($user) $account = $user->account;
        }

        /* Get actions. */
        list($actions, $pager) = $this->getActions4Dynamic($account, $orderBy, $productID, $type, $recTotal, $date, $direction);
        if(empty($recTotal)) $recTotal = count($actions);

        /* Assign. */
        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->product->dynamic;
        $this->view->position[]   = html::a($this->createLink($this->moduleName, 'browse'), $this->products[$productID]);
        $this->view->position[]   = $this->lang->product->dynamic;
        $this->view->userIdPairs  = $this->user->getPairs('noletter|nodeleted|noclosed|useid');
        $this->view->accountPairs = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->productID    = $productID;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->account      = $account;
        $this->view->user         = isset($user) ? $user : '';
        $this->view->param        = $param;
        $this->view->pager        = $pager;
        $this->view->dateGroups   = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction    = $direction;
        $this->view->recTotal     = $recTotal;

        $this->display();
    }

    /**
     * Product dashboard.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function dashboard(int $productID = 0)
    {
        /* Check and get product ID. */
        $productID = $this->product->saveVisitState($productID, $this->products);

        /* Get product. */
        $product   = $this->product->getStatByID($productID);
        if(!$product) return print(js::locate('product', 'all'));
        $product->desc = $this->loadModel('file')->setImgSize($product->desc);

        /* Save env data. */
        $this->productZen->saveBackUriSession4Dashboard();
        $this->product->setMenu($productID);

        /* Assign. */
        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->view;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->view;
        $this->view->product    = $product;
        $this->view->actions    = $this->loadModel('action')->getList('product', $productID);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->lines      = array('') + $this->product->getLinePairs();
        $this->view->dynamics   = $this->productZen->getActions4Dashboard($productID);
        $this->view->roadmaps   = $this->product->getRoadmap($productID, 0, 6);

        $this->display();
    }

    /**
     * 产品排序。
     * Sort product.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        /* Can only be order by program sorting. */
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'program') === false) return false;

        /* Get sorted id list. */
        $sortedIdList = explode(',', trim($this->post->products, ','));

        /* Sort by sorted id list. */
        $this->product->updateOrder($sortedIdList);
    }

    /**
     * 访问与测试相关模块或方法时，又没有相关产品，或者没有产品，会跳转到该方法。例如：qa。
     * Show error no product when visit qa.
     *
     * @param  string $moduleName   project|qa|execution
     * @param  string $activeMenu
     * @param  int    $objectID     The format of this parameter should be integer.
     * @access public
     * @return void
     */
    public function showErrorNone(string $moduleName = 'qa', string $activeMenu = 'index', int $objectID = 0)
    {
        $this->productZen->setShowErrorNoneMenu($moduleName, $activeMenu, $objectID);

        $this->view->title    = $this->lang->$moduleName->common;
        $this->view->objectID = $objectID;
        $this->display();
    }

    /**
     * 产品列表。
     * Product list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function all(string $browseType = 'noclosed', string $orderBy = 'program_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $programID = 0)
    {
        /* Set env data. */
        $this->productZen->setMenu4All();

        /* Generate statistics of products and program. */
        $this->app->loadClass('pager', true);
        $pager   = new pager($recTotal, $recPerPage, $pageID);
        $queryID = ($browseType == 'bySearch' or !empty($param)) ? $param : 0;
        if($this->config->systemMode == 'light' and $orderBy == 'program_asc') $orderBy = 'order_asc';

        $productStats = $this->product->getStats($orderBy, $pager, $browseType, 0, 'story', 0, $queryID);

        /* Save search form. */
        $actionURL = $this->createLink('product', 'all', "browseType=bySearch&orderBy=order_asc&queryID=myQueryID");
        $this->product->buildProductSearchForm($param, $actionURL);

        $this->view->title         = $this->lang->productCommon;
        $this->view->recTotal      = $pager->recTotal;
        $this->view->productStats  = $productStats;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->userIdPairs   = $this->user->getPairs('noletter|showid');
        $this->view->avatarList    = $this->user->getAvatarPairs('');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->pager         = $pager;
        $this->view->showBatchEdit = $this->cookie->showProductBatchEdit;
        $this->view->param         = $param;
        $this->view->recPerPage    = $recPerPage;
        $this->view->pageID        = $pageID;
        $this->view->programID     = $programID;

        $this->display();
    }

    /**
     * 产品看板。
     * Product kanban.
     *
     * @access public
     * @return void
     */
    public function kanban()
    {
        /* Save back URI to session. */
        $this->productZen->saveBackUriSession4Kanban();

        /* Generate data. */
        list( , $productList, $planList, $projectList, $executionList, $projectProduct, $projectLatestExecutions, $hourList, $releaseList) = $this->product->getStats4Kanban();

        $programPairs = $this->loadModel('program')->getPairs(true);
        $kanbanList   = $this->productZen->getProductList4Kanban($productList);
        $emptyHour    = $this->productZen->getEmptyHour();

        /* Assign. */
        $this->view->title            = $this->lang->product->kanban;
        $this->view->kanbanList       = $kanbanList;
        $this->view->programList      = array($this->lang->product->emptyProgram) + $programPairs;
        $this->view->productList      = $productList;
        $this->view->planList         = $planList;
        $this->view->projectList      = $projectList;
        $this->view->projectProduct   = $projectProduct;
        $this->view->latestExecutions = $projectLatestExecutions;
        $this->view->executionList    = $executionList;
        $this->view->hourList         = $hourList;
        $this->view->emptyHour        = $emptyHour;
        $this->view->releaseList      = $releaseList;

        $this->display();
    }

    /**
     * Manage product line.
     *
     * @access public
     * @return void
     */
    public function manageLine()
    {
        $this->app->loadLang('tree');
        if($_POST)
        {
            /* 从POST中获取数据，并预处理数据。 */
            /* Get data form post and prepare data. */
            $data  = form::data($this->config->product->form->manageLine);
            $lines = $this->productZen->prepareManageLineExtras($data);
            if($lines === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 添加或更新产品线。 */
            /* Add or update product line. */
            $response = $this->product->manageLine($lines);
            return $this->send($response);
        }

        $this->productZen->buildManageLineForm();
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $objectType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist(int $productID = 0, string $module = 'product', string $objectType = 'product', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->product->setMenu($productID, 0);
        $this->lang->modulePageNav = '';

        echo $this->fetch('personnel', 'whitelist', "objectID=$productID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $productID
     * @param  int     $deptID
     * @param  int     $copyID
     * @param  string  $branch
     * @access public
     * @return void
     */
    public function addWhitelist(int $productID = 0, int $deptID = 0, int $copyID = 0)
    {
        $this->product->setMenu($productID);
        $this->lang->modulePageNav = '';

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$productID&dept=$deptID&copyID=$copyID&objectType=product&module=product");
    }

    /*
     * Removing users from the white list.
     *
     * @param  int     $id
     * @param  string  $confirm
     * @access public
     * @return void
     */
    public function unbindWhitelist(int $id = 0, string $confirm = 'no')
    {
        echo $this->fetch('personnel', 'unbindWhitelist', "id=$id&confirm=$confirm");
    }

    /**
     * Export product.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @access public
     * @return void
     */
    public function export(string $status, string $orderBy)
    {
        if($_POST)
        {
            /* 获取导出字段和数据。 */
            $fields       = $this->productZen->getExportFields();
            $productStats = $this->productZen->getExportData($status, $orderBy);
            $rowspan      = $this->productZen->getExportRowspan($productStats);

            /* 如果只导出选中产品，删除非选中产品。 */
            if($this->post->exportType == 'selected')
            {
                $checkedItem = $this->cookie->checkedItem;
                foreach($productStats as $i => $product)
                {
                    if(strpos(",$checkedItem,", ",{$product->id},") === false) unset($productStats[$i]);
                }
            }
            if($this->config->edition != 'open') list($fields, $productStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $productStats);

            $this->post->set('rowspan', $rowspan);
            $this->post->set('fields', $fields);
            $this->post->set('rows', $productStats);
            $this->post->set('kind', 'product');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }

    /**
     * Story track.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $projectID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function track(int $productID, string $branch = '', int $projectID = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $branch = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;

        /* Set menu. The projectstory module does not execute. */
        $this->productZen->setTrackMenu($productID, $branch, $projectID);

        /* Load pager and get tracks. */
        $this->app->loadClass('pager', true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);
        $tracks = $this->loadModel('story')->getTracks($productID, $branch, $projectID, $pager);

        /* Get project products. */
        $projectProducts = array();
        if($projectID) $projectProducts = $this->product->getProducts($projectID);

        $this->view->title           = $this->lang->story->track;
        $this->view->tracks          = $tracks;
        $this->view->pager           = $pager;
        $this->view->productID       = $productID;
        $this->view->projectProducts = $projectProducts;
        $this->display();
    }

    /**
     * Ajax set show setting.
     *
     * @access public
     * @return void
     */
    public function ajaxSetShowSetting()
    {
        $data = fixer::input('post')->get();
        $this->loadModel('setting')->updateItem("{$this->app->user->account}.product.showAllProjects", $data->showAllProjects);
    }

    /**
     * Ajax get products.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetProducts(int $executionID)
    {
        if(empty($executionID)) return print(html::select('product', array(), '', "class='form-control chosen' required"));

        $this->app->loadLang('build');
        $status   = empty($this->config->CRProduct) ? 'noclosed' : 'all';
        $products = $this->product->getProductPairsByProject($executionID, $status);

        if(empty($products)) return printf($this->lang->build->noProduct, $this->createLink('execution', 'manageproducts', "executionID=$executionID&from=buildCreate", '', 'true'), 'project');
        return print(html::select('product', $products, '', "onchange='loadBranches(this.value);' class='form-control chosen' required data-toggle='modal' data-type='iframe'"));
    }

    /**
     * Ajax get product by id.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetProductByID(int $productID)
    {
        $product = $this->product->getByID($productID);

        $product->branchSourceName = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
        $product->branchName       = $this->lang->product->branchName[$product->type];
        echo json_encode($product);
    }

    /**
     * AJAX: get projects of a product in html select.
     *
     * @param  int    $productID
     * @param  string $branch    ''|'all'|int
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProjects(int $productID, string $branch = '', int $projectID = 0)
    {
        $projects  = array('' => '');
        $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        if($this->app->getViewType() == 'json') return print(json_encode($projects));

        return print(html::select('project', $projects, $projectID, "class='form-control' onchange='loadProductExecutions({$productID}, this.value)'"));
    }

     /**
     * AJAX: get projects of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $number
     * @access public
     * @return void
     */
    public function ajaxGetProjectsByBranch($productID, $branch = 0, $number = 0)
    {
        $projects  = array('' => '');
        $projects += $this->product->getProjectPairsByProduct($productID, $branch);

        return print(html::select('projects' . "[$number]", array('' => '') + $projects, 0, "class='form-control' onchange='loadProductExecutionsByProject($productID, this.value, $number)'"));
    }

    /**
     * AJAX: get executions of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $branch
     * @param  string $number
     * @param  int    $executionID
     * @param  string $from showImport
     * @param  string mode
     * @access public
     * @return void
     */
    public function ajaxGetExecutions($productID, $projectID = 0, $branch = 0, $number = '', $executionID = 0, $from = '', $mode = '')
    {
        if($this->app->tab == 'execution' and $this->session->execution)
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            if($execution->type == 'kanban') $projectID = $execution->project;
        }

        if($projectID) $project = $this->loadModel('project')->getByID($projectID);
        $mode .= ($from == 'bugToTask' or empty($this->config->CRExecution)) ? 'noclosed' : '';
        $mode .= !$projectID ? ',multiple' : '';
        $executions = $from == 'showImport' ? $this->product->getAllExecutionPairsByProduct($productID, $branch, $projectID) : $this->product->getExecutionPairsByProduct($productID, $branch, $projectID, $mode);
        if($this->app->getViewType() == 'json') return print(json_encode($executions));

        if($number === '')
        {
            $event = $from == 'bugToTask' ? '' : " onchange='loadExecutionRelated(this.value)'";
            $datamultiple = !empty($project) ? "data-multiple={$project->multiple}" : '';
            return print(html::select('execution', array('' => '') + $executions, $executionID, "class='form-control' $datamultiple $event"));
        }
        else
        {
            $executions     = empty($executions) ? array('' => '') : $executions;
            $executionsName = $from == 'showImport' ? "execution[$number]" : "executions[$number]";
            $misc           = $from == 'showImport' ? "class='form-control' onchange='loadImportExecutionRelated(this.value, $number)'" : "class='form-control' onchange='loadExecutionBuilds($productID, this.value, $number)'";
            return print(html::select($executionsName, $executions, '', $misc));
        }
    }

    /**
     * AJAX: get executions of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $branch
     * @param  int    $number
     * @access public
     * @return void
     */
    public function ajaxGetExecutionsByProject($productID, $projectID = 0, $branch = 0, $number = 0)
    {
        $noMultipleExecutionID = $projectID ? $this->loadModel('execution')->getNoMultipleID($projectID) : '';
        $executions            = $this->product->getExecutionPairsByProduct($productID, $branch, $projectID, 'multiple,stagefilter');

        $disabled = $noMultipleExecutionID ? "disabled='disabled'" : '';
        $html = html::select("executions[{$number}]", array('' => '') + $executions, 0, "class='form-control' onchange='loadExecutionBuilds($productID, this.value, $number)' $disabled");

        if($noMultipleExecutionID) $html .= html::hidden("executions[{$number}]", $noMultipleExecutionID, "id=executions{$number}");

        return print($html);
    }

    /**
     * AJAX: get plans of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $planID
     * @param  bool   $needCreate
     * @param  string $expired
     * @param  string $param
     * @access public
     * @return void
     */
    public function ajaxGetPlans($productID, $branch = 0, $planID = 0, $fieldID = '', $needCreate = false, $expired = '', $param = '')
    {
        $param    = strtolower($param);
        if(strpos($param, 'forstory') === false)
        {
            $plans = $this->loadModel('productplan')->getPairs($productID, empty($branch) ? 'all' : $branch, $expired, strpos($param, 'skipparent') !== false);
        }
        else
        {
            $plans = $this->loadModel('productplan')->getPairsForStory($productID, $branch == '0' ? 'all' : $branch, $param);
        }
        $field    = $fieldID !== '' ? "plans[$fieldID]" : 'plan';
        $multiple = strpos($param, 'multiple') === false ? '' : 'multiple';
        $output   = html::select($field, $plans, $planID, "class='form-control chosen' $multiple");

        if($branch == 0 and strpos($param, 'edit') and (strpos($param, 'forstory') === false)) $output = html::select($field, $plans, $planID, "class='form-control chosen' multiple");

        if(count($plans) == 1 and $needCreate and $needCreate !== 'false')
        {
            $output .= "<div class='input-group-btn'>";
            $output .= html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch", '', true), "<i class='icon icon-plus'></i>", '', "class='btn btn-icon' data-toggle='modal' data-type='iframe' data-width='95%' title='{$this->lang->productplan->create}'");
            $output .= '</div>';
            $output .= "<div class='input-group-btn'>";
            $output .= html::a("javascript:void(0)", "<i class='icon icon-refresh'></i>", '', "class='btn btn-icon refresh' data-toggle='tooltip' title='{$this->lang->refresh}' onclick='loadProductPlans($productID)'");
            $output .= '</div>';
        }
        echo $output;
    }

    /**
     * Ajax get product lines.
     *
     * @param  int    $programID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetLine($programID, $productID = 0)
    {
        $lines = array();
        if(empty($productID) or $programID) $lines = $this->product->getLinePairs($programID);

        if($productID)  return print(html::select("lines[$productID]", array('' => '') + $lines, '', "class='form-control picker-select'"));
        if(!$productID) return print(html::select('line', array('' => '') + $lines, '', "class='form-control picker-select'"));
    }

    /**
     * Ajax get reviewers.
     *
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetReviewers($productID, $storyID = 0)
    {
        /* Get product reviewers. */
        $product          = $this->product->getByID($productID);
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->user->getProductViewListUsers($product, '', '', '', '');

        $storyReviewers = '';
        if($storyID)
        {
            $story          = $this->loadModel('story')->getByID($storyID);
            $storyReviewers = $this->story->getReviewerPairs($story->id, $story->version);
            $storyReviewers = implode(',', array_keys($storyReviewers));
        }

        $reviewers = $this->user->getPairs('noclosed|nodeleted', $storyReviewers, 0, $productReviewers);

        echo html::select("reviewer[]", $reviewers, $storyReviewers, "class='form-control chosen' multiple");
    }

    /**
     * Drop menu page.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @param  string $from
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $productID, string $module, string $method, string $extra = '', string $from = '')
    {
        $shadow = '0';
        if($from == 'qa') $shadow = 'all';

        $products        = $this->productZen->getProducts4DropMenu($shadow);
        $programProducts = array();
        foreach($products as $product) $programProducts[$product->program][] = $product;

        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->products  = $programProducts;
        $this->view->projectID = $this->app->tab == 'project' ? $this->session->project : 0;
        $this->view->programs  = $this->loadModel('program')->getPairs(true);
        $this->view->lines     = $this->product->getLinePairs();
        $this->display();
    }

    /**
     * Set product id to session in ajax
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxSetState(int $productID)
    {
        $this->session->set('product', $productID, $this->app->tab);
        return $this->send(array('result' => 'success', 'productID' => $this->session->product));
    }
}
