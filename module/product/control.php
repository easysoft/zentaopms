<?php
/**
 * The control file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: control.php 5144 2013-07-15 06:37:03Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class product extends control
{
    public $products = array();

    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* Load need modules. */
        $this->loadModel('story');
        $this->loadModel('release');
        $this->loadModel('tree');
        $this->loadModel('user');

        /* Get all products, if no, goto the create page. */
        $this->products = $this->product->getPairs('nocode');
        if(empty($this->products) and strpos(',create,index,showerrornone,', $this->methodName) === false and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('product', 'create'));
        $this->view->products = $this->products;
    }

    /**
     * Index page, to browse.
     *
     * @param  string $locate     locate to browse page or not. If not, display all products.
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function index($locate = 'auto', $productID = 0, $status = 'noclosed', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if($this->config->global->flow == 'onlyTest') $this->locate($this->createLink($this->moduleName, 'build'));

        if($this->app->user->account == 'guest' or commonModel::isTutorialMode()) $this->config->product->homepage = 'index';
        if(!isset($this->config->product->homepage))
        {
            if($this->products and $this->app->getViewType() != 'mhtml') die($this->fetch('custom', 'ajaxSetHomepage', "module=product"));

            $this->config->product->homepage = 'index';
            $this->fetch('custom', 'ajaxSetHomepage', "module=product&page=index");
        }

        $homepage = $this->config->product->homepage;
        if($homepage == 'browse' and $locate == 'auto') $locate = 'yes';

        if($locate == 'yes') $this->locate($this->createLink($this->moduleName, 'browse'));

        if($this->app->getViewType() != 'mhtml') unset($this->lang->product->menu->index);
        $productID = $this->product->saveState($productID, $this->products);
        $branch    = (int)$this->cookie->preBranch;
        $this->product->setMenu($this->products, $productID, $branch);

        $this->view->title         = $this->lang->product->index;
        $this->view->position[]    = $this->lang->product->index;
        $this->display();
    }

    /**
     * project 
     * 
     * @param  string $status 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function project($status = 'all', $productID = 0, $branch = 0)
    {
        $this->product->setMenu($this->products, $productID, $branch);

        $this->app->loadLang('my');
        $this->view->projectStats  = $this->loadModel('project')->getProjectStats($status, $productID, $branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->product->project;
        $this->view->position[] = $this->products[$productID];
        $this->view->position[] = $this->lang->product->project;
        $this->view->productID  = $productID;
        $this->display();
    }

    /**
     * Browse a product.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  int    $param 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $browseType = 'unclosed', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Lower browse type. */
        $browseType = strtolower($browseType);

        /* Load datatable. */
        $this->loadModel('datatable');

        /* Save session. */
        $this->session->set('storyList',   $this->app->getURI(true));
        $this->session->set('productList', $this->app->getURI(true));

        /* Set product, module and query. */
        $productID = $this->product->saveState($productID, $this->products);
        $branch    = ($branch === '') ? (int)$this->cookie->preBranch : (int)$branch;
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot);
        setcookie('preBranch', (int)$branch, $this->config->cookieLife, $this->config->webRoot);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch)
        {
            $_COOKIE['storyModule'] = 0;
            setcookie('storyModule', 0, $this->config->cookieLife, $this->config->webRoot);
        }
        if($browseType == 'bymodule') setcookie('storyModule', (int)$param, $this->config->cookieLife, $this->config->webRoot);
        if($browseType != 'bymodule') $this->session->set('storyBrowseType', $browseType);

        $moduleID  = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->storyModule ? $this->cookie->storyModule : 0));
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu. */
        $this->product->setMenu($this->products, $productID, $branch);

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->productStoryOrder ? $this->cookie->productStoryOrder : 'id_desc';
        setcookie('productStoryOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get stories. */
        $stories = $this->product->getStories($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');

        /* Get related tasks, bugs, cases count of each story. */
        $storyIdList = array();
        foreach($stories as $story) $storyIdList[$story->id] = $story->id;
        $storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        /* Build search form. */
        $actionURL = $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $this->config->product->search['onMenuBar'] = 'yes';
        $this->product->buildSearchForm($productID, $this->products, $queryID, $actionURL);

        $showModule = !empty($this->config->datatable->productBrowse->showModule) ? $this->config->datatable->productBrowse->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'story', $showModule) : array();

        /* Assign. */
        $this->view->title         = $this->products[$productID]. $this->lang->colon . $this->lang->product->browse;
        $this->view->position[]    = $this->products[$productID];
        $this->view->position[]    = $this->lang->product->browse;
        $this->view->productID     = $productID;
        $this->view->product       = $this->product->getById($productID);
        $this->view->productName   = $this->products[$productID];
        $this->view->moduleID      = $moduleID;
        $this->view->stories       = $stories;
        $this->view->plans         = $this->loadModel('productplan')->getPairs($productID, $branch);
        $this->view->summary       = $this->product->summary($stories);
        $this->view->moduleTree    = $this->tree->getTreeMenu($productID, $viewType = 'story', $startModuleID = 0, array('treeModel', 'createStoryLink'), '', $branch);
        $this->view->parentModules = $this->tree->getParents($moduleID);
        $this->view->pager         = $pager;
        $this->view->users         = $this->user->getPairs('nodeleted|noletter|pofirst');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->modules       = $this->tree->getOptionMenu($productID, $viewType = 'story', 0, $branch);
        $this->view->moduleID      = $moduleID;
        $this->view->moduleName    = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->branch        = $branch;
        $this->view->branches      = $this->loadModel('branch')->getPairs($productID);
        $this->view->storyStages   = $this->product->batchGetStoryStage($stories);
        $this->view->setShowModule = true;
        $this->view->storyTasks    = $storyTasks;
        $this->view->storyBugs     = $storyBugs;
        $this->view->storyCases    = $storyCases;
        $this->view->param         = $param;
        $this->view->products      = $this->products;
        $this->display();
    }

    /**
     * Create a product. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $productID = $this->product->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('product', $productID, 'opened');
            die(js::locate($this->createLink($this->moduleName, 'browse', "productID=$productID"), 'parent'));
        }

        $this->product->setMenu($this->products, key($this->products));

        $this->view->title      = $this->lang->product->create;
        $this->view->position[] = $this->view->title;
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->poUsers    = $this->loadModel('user')->getPairs('nodeleted|pofirst|noclosed');
        $this->view->qdUsers    = $this->loadModel('user')->getPairs('nodeleted|qdfirst|noclosed');
        $this->view->rdUsers    = $this->loadModel('user')->getPairs('nodeleted|devfirst|noclosed');

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * Edit a product.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function edit($productID, $action = 'edit', $extra = '')
    {
        if(!empty($_POST))
        {
            $changes = $this->product->update($productID); 
            if(dao::isError()) die(js::error(dao::getError()));
            if($action == 'undelete')
            {
                $this->loadModel('action');
                $this->dao->update(TABLE_PRODUCT)->set('deleted')->eq(0)->where('id')->eq($productID)->exec();
                $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($extra)->exec();
                $this->action->create('product', $productID, 'undeleted');
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('product', $productID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "product=$productID"), 'parent'));
        }

        $this->product->setMenu($this->products, $productID);

        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        $this->view->title      = $this->lang->product->edit . $this->lang->colon . $product->name;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->edit;
        $this->view->product    = $product;
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->poUsers    = $this->loadModel('user')->getPairs('nodeleted|pofirst',  $product->PO);
        $this->view->qdUsers    = $this->loadModel('user')->getPairs('nodeleted|qdfirst',  $product->QD);
        $this->view->rdUsers    = $this->loadModel('user')->getPairs('nodeleted|devfirst', $product->RD);

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * Batch edit products.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0)
    {
        if($this->post->names)
        {
            $allChanges = $this->product->batchUpdate();
            if(!empty($allChanges))
            {
                foreach($allChanges as $productID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('product', $productID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            die(js::locate($this->session->productList, 'parent'));
        }

        $this->product->setMenu($this->products, $productID);

        $productIDList = $this->post->productIDList ? $this->post->productIDList : die(js::locate($this->session->productList, 'parent'));

        /* Set custom. */
        foreach(explode(',', $this->config->product->customBatchEditFields) as $field) $customFields[$field] = $this->lang->product->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->product->custom->batchEditFields;

        $this->view->title         = $this->lang->product->batchEdit;
        $this->view->position[]    = $this->lang->product->batchEdit;
        $this->view->productIDList = $productIDList;
        $this->view->products      = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIDList)->fetchAll('id');
        $this->view->poUsers       = $this->loadModel('user')->getPairs('nodeleted|pofirst');
        $this->view->qdUsers       = $this->loadModel('user')->getPairs('nodeleted|qdfirst');
        $this->view->rdUsers       = $this->loadModel('user')->getPairs('nodeleted|devfirst');

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * Close product.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function close($productID)
    {
        $product = $this->product->getById($productID);
        $actions = $this->loadModel('action')->getList('product', $productID);

        if(!empty($_POST))
        {
            $changes = $this->product->close($productID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('product', $productID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent.parent'));
        }

        $this->product->setMenu($this->products, $productID);

        $this->view->product    = $product;
        $this->view->title      = $this->view->product->name . $this->lang->colon .$this->lang->close;
        $this->view->position[] = $this->lang->close;
        $this->view->actions    = $actions;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * View a product.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function view($productID)
    {
        $this->product->setMenu($this->products, $productID);

        $product  = $this->product->getStatByID($productID);
        $product->desc = $this->loadModel('file')->setImgSize($product->desc);
        if(!$product) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->view;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->view;
        $this->view->product    = $product;
        $this->view->actions    = $this->loadModel('action')->getList('product', $productID);
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->groups     = $this->loadModel('group')->getPairs();

        $this->display();
    }

    /**
     * Delete a product.
     * 
     * @param  int    $productID 
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function delete($productID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->product->confirmDelete, $this->createLink('product', 'delete', "productID=$productID&confirm=yes")));
        }
        else
        {
            $this->product->delete(TABLE_PRODUCT, $productID);
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('product')->eq($productID)->exec();
            $this->session->set('product', '');     // 清除session。
            die(js::locate($this->createLink('product', 'browse'), 'parent'));
        }
    }

    /**
     * Road map of a product. 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function roadmap($productID, $branch = 0)
    {
        $this->product->setMenu($this->products, $productID, $branch);

        $this->session->set('releaseList',     $this->app->getURI(true));
        $this->session->set('productPlanList', $this->app->getURI(true));

        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->roadmap;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->roadmap;
        $this->view->product    = $product;
        $this->view->roadmaps   = $this->product->getRoadmap($productID, $branch);
        $this->view->branches   = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);

        $this->display();
    }

    /**
     * Product dynamic.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function dynamic($productID = 0, $type = 'today', $param = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri   = $this->app->getURI(true);
        $this->session->set('productList',     $uri);
        $this->session->set('productPlanList', $uri);
        $this->session->set('releaseList',     $uri);
        $this->session->set('storyList',       $uri);
        $this->session->set('projectList',     $uri);
        $this->session->set('taskList',        $uri);
        $this->session->set('buildList',       $uri);
        $this->session->set('bugList',         $uri);
        $this->session->set('caseList',        $uri);
        $this->session->set('testtaskList',    $uri);

        $this->product->setMenu($this->products, $productID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Set the user and type. */
        $account = $type == 'account' ? $param : 'all';
        $period  = $type == 'account' ? 'all'  : $type;

        /* The header and position. */
        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->product->dynamic;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $this->products[$productID]);
        $this->view->position[] = $this->lang->product->dynamic;

        /* Assign. */
        $this->view->productID = $productID;
        $this->view->type      = $type;
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted|noletter');
        $this->view->account   = $account;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->param     = $param;
        $this->view->actions   = $this->loadModel('action')->getDynamic($account, $period, $sort, $pager, $productID);
        $this->display();
    }

    /**
     * AJAX: get projects of a product in html select.
     * 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @param  string $number
     * @access public
     * @return void
     */
    public function ajaxGetProjects($productID, $projectID = 0, $branch = 0, $number = '')
    {
        if($productID == 0)
        {
            $projects = array(0 => '') + $this->loadModel('project')->getPairs();
        }
        else
        {
            $projects = $this->product->getProjectPairs($productID, $branch ? "0,$branch" : $branch, $params = 'nodeleted');
        }
        if($this->app->getViewType() == 'json') die(json_encode($projects));
        
        if($number === '')
        {
            die(html::select('project', $projects, $projectID, 'class=form-control onchange=loadProjectRelated(this.value)'));
        }
        else
        {
            $projectName = "projects[$number]";
            $projects    = empty($projects) ? array('' => '') : $projects;
            die(html::select($projectName, $projects, '', "class='form-control' onchange='loadProjectBuilds($productID, this.value, $number)'"));
        }
    }

    /**
     * AJAX: get plans of a product in html select. 
     * 
     * @param  int    $productID 
     * @param  int    $planID 
     * @param  bool   $needCreate
     * @access public
     * @return void
     */
    public function ajaxGetPlans($productID, $branch = 0, $planID = 0, $fieldID = '', $needCreate = false)
    {
        $plans = $this->loadModel('productplan')->getPairs($productID, $branch);
        $field = $fieldID ? "plans[$fieldID]" : 'plan';
        $output = html::select($field, $plans, $planID, "class='form-control chosen'");
        if(count($plans) == 1 and $needCreate) 
        {
            $output .= "<span class='input-group-addon'>";
            $output .= html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch"), $this->lang->productplan->create, '_blank');
            $output .= '&nbsp; ';
            $output .= html::a("javascript:loadProductPlans($productID)", $this->lang->refresh);
            $output .= '</span>';
        }
        die($output);
    }

    /**
     * Drop menu page.
     * 
     * @param  int    $productID 
     * @param  string $module 
     * @param  string $method 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $module, $method, $extra)
    {
        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;

        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in(array_keys($this->products))->orderBy('`order` desc')->fetchAll('id');
        $productPairs = array();
        foreach($products as $product) $productPairs[$product->id] = $product->name;
        $productsPinyin = common::convert2Pinyin($productPairs);

        foreach($products as $key => $product) $product->key = $productsPinyin[$product->name];
        $this->view->products  = $products;
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
        $idList   = explode(',', trim($this->post->products, ','));
        $orderBy  = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        $products = $this->dao->select('id,`order`')->from(TABLE_PRODUCT)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($products as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Show error no product when visit qa.
     * 
     * @param  string $fromModule 
     * @access public
     * @return void
     */
    public function showErrorNone($fromModule = 'bug')
    {
        $this->loadModel($fromModule)->setMenu($this->products, key($this->products));
        $this->lang->set('menugroup.product', 'qa');
        $this->lang->product->menu      = $this->lang->$fromModule->menu;
        $this->lang->product->menuOrder = $this->lang->$fromModule->menuOrder;

        $this->view->title = $this->lang->$fromModule->common;
        $this->display();
    }

    /**
     * All product.
     * 
     * @param  int    $productID 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function all($productID = 0, $status = 'noclosed', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->session->set('productList', $this->app->getURI(true));
        $productID = $this->product->saveState($productID, $this->products);
        $this->product->setMenu($this->products, $productID);

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('my');
        $this->view->title        = $this->lang->product->allProduct;
        $this->view->position[]   = $this->lang->product->allProduct;
        $this->view->productStats = $this->product->getStats($orderBy, $pager, $status);
        $this->view->productID    = $productID;
        $this->view->pager        = $pager;
        $this->view->recTotal     = $pager->recTotal;
        $this->view->recPerPage   = $pager->recPerPage;
        $this->view->orderBy      = $orderBy;
        $this->view->status       = $status;
        $this->display();
    }

    /**
     * Doc for compatible.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function doc($productID)
    {
        $this->locate($this->createLink('doc', 'objectLibs', "type=product&objectID=$productID&from=product"));
    }

    /**
     * Build of product.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function build($productID = 0)
    {
        $this->app->loadLang('build');
        $this->session->set('productList', $this->app->getURI(true));

        /* Get all product list. Locate to the create product page if there is no product. */
        $this->products = $this->product->getPairs();
        if(empty($this->products) and strpos('create|view', $this->methodName) === false) $this->locate($this->createLink('product', 'create'));

        /* Get current product. */
        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getById($productID);
        $this->product->setMenu($this->products, $productID);

        /* Set menu.*/
        $this->session->set('buildList', $this->app->getURI(true));

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->build;
        $this->view->position[] = $this->lang->product->build;
        $this->view->products   = $this->products;
        $this->view->product    = $product;
        $this->view->builds     = $this->dao->select('*')->from(TABLE_BUILD)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }
}
