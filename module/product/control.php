<?php
declare(strict_types=1);
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
        $this->products = $this->product->getPairs('all', 0, '', 'all');
        if($this->product->checkLocateCreate($this->products)) $this->locate($this->createLink('product', 'create'));

        $this->view->products = $this->products;
    }

    /**
     * 产品首页，浏览仪表盘。
     * Index page, browse dashboard.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function index(int $productID = 0)
    {
        /* Check product id and get product branch. */
        $productID = $this->product->checkAccess($productID, $this->products);
        $branch    = (int)$this->cookie->preBranch;

        /* Set Menu. */
        if($this->app->getViewType() != 'mhtml') unset($this->lang->product->menu->index);
        if($this->app->viewType == 'mhtml') $this->product->setMenu($productID, $branch);

        $this->view->title = $this->lang->product->index;
        $this->display();
    }

    /**
     * 产品下项目列表。
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
        $this->productZen->setProjectMenu($productID, $branch, (string)$this->cookie->preBranch);

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
        $this->view->PMList       = $this->loadModel('user')->getListByAccounts(helper::arrayColumn($projectStats, 'PM'), 'account');
        $this->view->product      = $product;
        $this->view->projects     = $projects;
        $this->view->status       = $status;
        $this->view->users        = $this->user->getPairs('noletter');
        $this->view->branchID     = $branch;
        $this->view->branchStatus = $this->loadModel('branch')->getByID($branch, 0, 'status');
        $this->view->pager        = $pager;
        $this->view->involved     = $involved;
        $this->view->orderBy      = $orderBy;
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
        $this->loadModel('tree');
        $isProjectStory = $this->app->rawModule == 'projectstory';
        $cookieOrderBy  = $this->cookie->productStoryOrder ? $this->cookie->productStoryOrder : 'id_desc';

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Generate data. */
        $productID = $this->app->tab != 'project' ? $this->product->checkAccess($productID, $this->products) : $productID;
        $product   = $this->productZen->getBrowseProduct($productID);
        $project   = $projectID ? $this->loadModel('project')->getByID($projectID) : null;
        $branchID  = $this->productZen->getBranchID($product, $branch);
        $orderBy   = $orderBy  ? $orderBy  : $cookieOrderBy;
        $branch    = $branchID ? $branchID : $branch;

        /* ATTENTION: be careful to change the order of follow sentences. */
        $this->productZen->setMenu4Browse($projectID, $productID, $branch, $storyType);
        $this->productZen->saveAndModifyCookie4Browse($productID, $branch, $param, $browseType, $orderBy);
        if($browseType == '')
        {
            $browseType = 'unclosed';
            if($this->config->vision == 'or') $browseType = 'assignedtome';
        }

        /* Generate data. */
        $moduleID = $this->productZen->getModuleId($param, $browseType);
        $stories  = $this->productZen->getStories($projectID, $productID, $branchID, $moduleID, $param, $storyType, $browseType, $orderBy, $pager);

        /* Process the sql, get the condition partition, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', (strpos('bysearch,reviewbyme,bymodule', $browseType) === false && !$isProjectStory));

        /* Save session. */
        $this->productZen->saveSession4Browse($product, $storyType, $browseType, $isProjectStory);

        /* Build search form. */
        $this->productZen->buildSearchFormForBrowse($project, $projectID, $productID, $branch, $param, $storyType, $browseType, $isProjectStory);

        /* Assign. */
        $this->view->moduleID   = $moduleID;
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->param      = $param;
        $this->view->moduleTree = $this->productZen->getModuleTree($projectID, $productID, $branch, $param, $storyType, $browseType);

        $this->productZen->assignBrowseData($stories, $browseType, $storyType, $isProjectStory, $product, $project, $branch, $branchID);
    }

    /**
     * 创建产品。可以是顶级产品，也可以是项目集下的产品。
     * Create a product.
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

            $productID = $this->product->create($productData, (string) $this->post->lineName);
            if(dao::isError()) return $this->sendError(dao::getError());

            $response = $this->productZen->responseAfterCreate($productID, $productData->program);
            return $this->send($response);
        }

        $this->productZen->setCreateMenu($programID);

        $this->view->title      = $this->lang->product->create;
        $this->view->gobackLink = $this->productZen->getBackLink4Create($extra);
        $this->view->programID  = $programID;
        $this->view->fields     = $this->productZen->getFormFields4Create($programID);
        $this->display();
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
            $productData = $this->productZen->buildProductForEdit();

            $this->product->update($productID, $productData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($action == 'undelete') $this->loadModel('action')->undelete((int)$extra);
            $response = $this->productZen->responseAfterEdit($productID, $programID);
            return $this->send($response);
        }

        $productID = $this->product->checkAccess($productID, $this->products);
        $this->productZen->setEditMenu($productID, $programID);

        $product = $this->product->getByID($productID);

        $this->view->title   = $this->lang->product->edit . $this->lang->colon . $product->name;
        $this->view->product = $product;
        $this->view->fields  = $this->productZen->getFormFields4Edit($product);

        $this->display();
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
            /* 从POST中获取数据。 */
            $products = form::batchData($this->config->product->form->batchEdit)->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $result = $this->product->batchUpdate($products);
            if(dao::isError()) return $this->send($result);

            $response = $this->productZen->responseAfterBatchEdit($programID);
            return $this->send($response);
        }

        /* 获取要修改的产品ID列表。*/
        $productIdList = $this->post->productIDList;
        if(empty($productIdList)) return $this->locate($this->session->productList);

        /* Set menu when page come from program. */
        if($this->app->tab == 'program') common::setMenuVars('program', 0);

        if($this->config->vision == 'or') unset($this->lang->product->statusList['normal']);

        /* 构造批量编辑页面表单配置数据。*/
        $this->productZen->buildBatchEditForm($programID, $productIdList);
    }

    /**
     * 激活产品。
     * Activate product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function activate(int $productID)
    {
        if(!empty($_POST))
        {
            $productData = $this->productZen->buildProductForActivate();

            $this->product->activate($productID, $productData, $this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($productID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->product->setMenu($productID);

        $product = $this->product->getByID($productID);

        $this->view->title   = $product->name . $this->lang->colon .$this->lang->close;
        $this->view->product = $product;
        $this->view->actions = $this->loadModel('action')->getList('product', $productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->fields  = $this->productZen->getFormFields4Activate();
        $this->display();
    }

    /**
     * 关闭产品。
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

            $this->product->close($productID, $productData, $this->post->comment);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($productID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->product->setMenu($productID);

        $product = $this->product->getByID($productID);

        $this->view->title   = $product->name . $this->lang->colon .$this->lang->close;
        $this->view->product = $product;
        $this->view->actions = $this->loadModel('action')->getList('product', $productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->fields  = $this->productZen->getFormFields4Close();
        $this->display();
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
        if($product->status == 'wait') $product = $this->product->getStatByID($product->id, 'requirement');
        if(!$product) return $this->productZen->responseNotFound4View();

        $product->desc = $this->loadModel('file')->setImgSize($product->desc);
        if($product->line)    $product->lineName    = $this->loadModel('tree')->getByID($product->line)->name;
        if($product->program) $product->programName = $this->loadModel('program')->getByID($product->program)->name;

        /* Set navigation menu. */
        $this->product->setMenu($productID);

        /* Execute hooks. */
        $this->executeHooks($productID);

        $this->view->title     = $product->name . $this->lang->colon . $this->lang->product->view;
        $this->view->product   = $product;
        $this->view->actions   = $this->loadModel('action')->getList('product', $productID);
        $this->view->dynamics  = $this->action->getDynamic('all', 'all', 'date_desc', 50, $productID);
        $this->view->users     = $this->user->getPairs('noletter');
        $this->view->groups    = $this->loadModel('group')->getPairs();
        $this->view->branches  = $this->loadModel('branch')->getPairs($productID);
        $this->view->reviewers = !empty($product->reviewer) ? explode(',', $product->reviewer) : array();
        $this->view->members   = $this->loadModel('user')->getListByAccounts(array($product->PO, $product->RD, $product->QD, $product->feedback, $product->ticket, $product->createdBy), 'account');

        $this->display();
    }

    /**
     * 删除产品。
     * Delete a product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function delete(int $productID)
    {
        /* Delete product. */
        $this->product->deleteByID($productID);

        /* Reset session. */
        $this->session->set('product', '');

        /* Response JSON message. */
        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        return $this->sendSuccess(array('load' => $this->createLink('product', 'all')));
    }

    /**
     * 产品路线图。
     * Road map of a product.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function roadmap(int $productID,  string $branch = 'all')
    {
        /* Set env variables. */
        $this->product->setMenu($productID, $branch);
        $this->productZen->saveSession4Roadmap();

        /* Generate data. */
        $product = $this->product->getByID($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'showErrorNone', 'fromModule=product'));

        $roadmaps = $this->product->getRoadmap($productID, $branch);
        $branches = $product->type == 'normal' ? array(0 => '') : $this->loadModel('branch')->getPairs($productID);

        /* Assign view data. */
        $this->view->title    = $product->name . $this->lang->colon . $this->lang->product->roadmap;
        $this->view->product  = $product;
        $this->view->roadmaps = $roadmaps;
        $this->view->branches = $branches;

        $this->display();
    }

    /**
     * 产品动态。
     * Product dynamic.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  int    $param     userID
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
        $this->productZen->saveBackUriSessionForDynamic();
        $this->product->setMenu($productID, 0, $type);

        /* Generate orderBy string. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* Get user account. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->user->getByID($param, 'id');
            if($user) $account = $user->account;
        }

        /* Get actions. */
        list($actions, $dateGroups) = $this->productZen->getActionsForDynamic($account, $orderBy, $productID, $type, $recTotal, $date, $direction);
        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($dateGroups, 1) - count($dateGroups) : $this->action->getDynamicCount();

        /* Assign. */
        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->product->dynamic;
        $this->view->userIdPairs  = $this->user->getPairs('noletter|nodeleted|noclosed|useid');
        $this->view->accountPairs = $this->user->getPairs('noletter|nodeleted|noclosed');
        $this->view->productID    = $productID;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->account      = $account;
        $this->view->user         = isset($user) ? $user : '';
        $this->view->param        = $param;
        $this->view->dateGroups   = $dateGroups;
        $this->view->direction    = $direction;
        $this->view->recTotal     = $recTotal;

        $this->display();
    }

    /**
     * 单个产品的仪表盘。
     * The dashboard for one product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function dashboard(int $productID = 0)
    {
        /* Check and get product ID. */
        $productID = $this->product->checkAccess($productID, $this->products);

        /* Set productID to menu. */
        $this->product->setMenu($productID);

        /* Get product. */
        $product = $this->product->getStatByID($productID);
        if(!$product) return $this->locate('product', 'all');

        $this->view->title = $product->name . $this->lang->colon . $this->lang->product->view;
        echo $this->fetch('block', 'dashboard', 'dashboard=singleproduct');
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

        /* Generate statistics of products. */
        if($this->config->systemMode == 'light' && $orderBy == 'program_asc') $orderBy = 'order_asc';

        if(strtolower($browseType) == 'bysearch')
        {
            $queryID  = ($browseType == 'bySearch' || !empty($param)) ? $param : 0;
            $products = $this->product->getListBySearch($queryID);
        }
        else
        {
            $products = $this->product->getList($programID, $browseType);
        }

        $this->product->refreshStats(); // Refresh stats fields of products.

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->product->refreshStats(); // Refresh stats fields of products.

        $productStatList = $this->product->getStats(array_keys($products), $orderBy, $pager, 'story', $programID);

        /* Generate root program list. */
        $rootProgramList = $this->loadModel('program')->getRootProgramList();

        /* Save search form. */
        $actionURL = $this->createLink('product', 'all', "browseType=bySearch&orderBy=order_asc&queryID=myQueryID");
        $this->product->buildProductSearchForm($param, $actionURL);

        /* Assign. */
        $this->view->title         = $this->lang->productCommon;
        $this->view->recTotal      = $pager->recTotal;
        $this->view->productStats  = $productStatList;
        $this->view->programList   = $rootProgramList;
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
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function kanban($browseType = 'my')
    {
        /* Assign. */
        $this->view->title      = $this->lang->product->kanban;
        $this->view->kanbanList = $this->productZen->getKanbanList($browseType);
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * 维护产品线。
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

        $this->view->title    = $this->lang->product->manageLine;
        $this->view->programs = $this->loadModel('program')->getTopPairs('withDeleted');
        $this->view->lines    = $this->product->getLines();
        $this->view->fields   = $this->config->product->form->manageLine;
        $this->display();
    }

    /**
     * 白名单列表。
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
     * 添加用户到白名单。
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
     * 从白名单移除用户。
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
     * 导出产品。
     * Export product.
     *
     * @param  int    $programID
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $param
     * @access public
     * @return void
     */
    public function export(int $programID, string $status, string $orderBy, int $param = 0)
    {
        if($_POST)
        {
            /* 获取导出字段和数据。 */
            $fields       = $this->productZen->getExportFields();
            $productStats = $this->productZen->getExportData($programID, $status, $orderBy, $param);
            $rowspan      = $this->productZen->getExportRowspan($productStats);

            /* 如果只导出选中产品，删除非选中产品。 */
            if($this->post->exportType == 'selected')
            {
                $checkedItem = $this->post->checkedItem;
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

        /* Get filename to export. */
        $fileName = '';
        if($programID)
        {
            $program = $this->loadModel('program')->getByID($programID);
            if($program) $fileName = $program->name;
        }
        if(!$programID || !$fileName) $fileName = $this->lang->product->common;
        $fileName .= isset($this->lang->product->featureBar['all'][$status]) ?  '-' . $this->lang->product->featureBar['all'][$status] : '';

        $this->view->fileName = $fileName;
        $this->display();
    }

    /**
     * 需求矩阵。
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
        if(is_bool($branch)) $branch = (string)(int)$branch;

        /* Set menu. The projectstory module does not execute. */
        $this->productZen->setTrackMenu($productID, $branch, $projectID);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager  = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title           = $this->lang->story->track;
        $this->view->tracks          = $this->loadModel('story')->getTracks($productID, $branch, $projectID, $pager);
        $this->view->pager           = $pager;
        $this->view->productID       = $productID;
        $this->view->projectProducts = $this->product->getProductPairsByProject($projectID);
        $this->view->branch          = $branch;
        $this->view->projectID       = $projectID;

        $this->display();
    }

    /**
     * 设置是否展示非当前项目集的项目信息。
     * Ajax set show setting.
     *
     * @access public
     * @return void
     */
    public function ajaxSetShowSetting()
    {
        $this->loadModel('setting')->updateItem("{$this->app->user->account}.product.showAllProjects", $this->post->showAllProjects);
    }

    /**
     * 获取关联某个执行的产品下拉数据。
     * Ajax get products.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetProducts(int $executionID)
    {
        $items = array();
        if(empty($executionID)) return print(json_encode($items));

        $this->app->loadLang('build');
        $status   = empty($this->config->CRProduct) ? 'noclosed' : 'all';
        $products = $this->product->getProductPairsByProject($executionID, $status);

        foreach($products as $productID => $productName) $items[] = array('text' => $productName, 'value' => $productID);
        return print(json_encode($items));
    }

    /**
     * 通过产品ID获取产品信息。
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
      * 获取关联产品的项目下拉数据。
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
        $projects = $this->product->getProjectPairsByProduct($productID, $branch);
        if($this->app->getViewType() == 'json') return print(json_encode($projects));

        $items = array();
        foreach($projects as $projectID => $projectName) $items[] = array('text' => $projectName, 'value' => $projectID, 'keys' => $projectName);
        return print(json_encode($items));
    }

     /**
      * 获取关联产品的项目下拉数据。
     * AJAX: get projects of a product in html select.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function ajaxGetProjectsByBranch(int $productID, string $branch = '')
    {
        $projects = $this->product->getProjectPairsByProduct($productID, $branch);

        $projectList = array();
        foreach($projects as $projectID => $projectName) $projectList[] = array('value' => $projectID, 'text' => $projectName);
        return $this->send($projectList);
    }

    /**
     * 获取关联产品的执行下拉数据。
     * AJAX: get executions of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $branch
     * @param  string $pageType
     * @param  int    $executionID
     * @param  string $from showImport
     * @param  string mode
     * @access public
     * @return void
     */
    public function ajaxGetExecutions(int $productID, int $projectID = 0, string $branch = '', string $pageType = '', int $executionID = 0, string $from = '', string $mode = '')
    {
        if($this->app->tab == 'execution' && $this->session->execution)
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            if($execution->type == 'kanban') $projectID = $execution->project;
        }

        if($projectID) $project = $this->loadModel('project')->getByID($projectID);

        $mode .= ($from == 'bugToTask' || empty($this->config->CRExecution)) ? 'noclosed' : '';
        $mode .= !$projectID ? ',multiple' : '';
        $executions = $this->product->getExecutionPairsByProduct($productID, $branch, $projectID, $from == 'showImport' ? '' : $mode);
        if($this->app->getViewType() == 'json') return print(json_encode($executions));

        $executionList = array();
        if($pageType == 'batch')
        {
            foreach($executions as $executionID => $executionName) $executionList[] = array('value' => $executionID, 'text' => $executionName);
            return $this->send($executionList);
        }
        else
        {
            foreach($executions as $executionID => $executionName) $executionList[] = array('text' => $executionName, 'value' => $executionID, 'keys' => $executionName);
            return print(json_encode($executionList));
        }
    }

    /**
     * 获取关联产品的执行下拉数据。
     * AJAX: get executions of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function ajaxGetExecutionsByProject(int $productID, int $projectID = 0, string $branch = '')
    {
        $noMultipleExecutionID = $projectID ? $this->loadModel('execution')->getNoMultipleID($projectID) : '';
        $executions            = $this->product->getExecutionPairsByProduct($productID, $branch, $projectID, 'multiple,stagefilter');

        $executionList = array();
        foreach($executions as $executionID => $executionName) $executionList[] = array('value' => $executionID, 'text' => $executionName);

        return $this->send(array('executions' => $executionList, 'noMultipleExecutionID' => $noMultipleExecutionID));
    }

    /**
     * 获取产品下的计划下拉数据。
     * Get plan drop-down data under product.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $planID
     * @param  string $fieldID
     * @param  int    $needCreate
     * @param  string $expired
     * @param  string $param
     * @access public
     * @return void
     */
    public function ajaxGetPlans(int $productID, string $branch = '', int $planID = 0, string $fieldID = '', int $needCreate = 0, string $expired = '', string $param = '')
    {
        $param = strtolower($param);
        $plans = $this->loadModel('productplan')->getPairs($productID, empty($branch) ? 'all' : $branch, $expired, strpos($param, 'skipparent') !== false);

        $items = array();
        foreach($plans as $id => $name)
        {
            if(!$id) continue;
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        return print(json_encode($items));
    }

    /**
     * 获取产品线的下拉组件数据。
     * Ajax get product lines.
     *
     * @param  int    $programID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetLine(int $programID, int $productID = 0)
    {
        $lines = array();
        if(empty($productID) or $programID) $lines = $this->product->getLinePairs($programID);

        $items = array();
        foreach($lines as $lineID => $lineName) $items[] = array('text' => $lineName, 'value' => $lineID);

        if($productID)  return print(json_encode(array('multiple' => false, 'defaultValue' => '', 'name' => "lines[$productID]", 'items' => $items)));
        if(!$productID) return print(json_encode(array('multiple' => false, 'defaultValue' => '', 'name' => "line", 'items' => $items)));
    }

    /**
     * 获取产品的评审人下拉列表。
     * Ajax: Get a drop-down list of reviewers for the product..
     *
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function ajaxGetReviewers(int $productID, int $storyID = 0)
    {
        /* Get product reviewers. */
        $product          = $this->product->getByID($productID);
        $productReviewers = $product->reviewer;
        if(!$productReviewers and $product->acl != 'open') $productReviewers = $this->user->getProductViewListUsers($product);

        $storyReviewers = '';
        if($storyID)
        {
            $story          = $this->loadModel('story')->getByID($storyID);
            $storyReviewers = $this->story->getReviewerPairs($story->id, $story->version);
            $storyReviewers = implode(',', array_keys($storyReviewers));
        }

        $reviewers = $this->user->getPairs('noclosed|nodeleted', $storyReviewers, 0, $productReviewers);

        $items = array();
        foreach($reviewers as $account => $realname) $items[] = array('text' => $realname, 'value' => $account, 'keys' => $realname);
        return print(json_encode(array('multiple' => true, 'defaultValue' => $storyReviewers, 'name' => "reviewer", 'items' => $items)));
    }

    /**
     * 获取产品下1.5级下拉数据。
     * Get 1.5 level drop-down data under product.
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
        if($this->app->tab == 'qa' || $from == 'qa') $shadow = 'all';

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
     * 保存产品ID到session中。
     * Save the product ID to the session.
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

    /**
     * 删除一个产品线。
     * Delete a product line.
     *
     * @param  int    $lineID
     * @access public
     * @return void
     */
    public function ajaxDeleteLine(int $lineID)
    {
        $this->product->deleteLine($lineID);

        $link = inlink('manageLine');
        return $this->send(array('result' => 'success', 'callback' => "loadModal(\"$link\", 'manageLineModal');"));
    }
}
