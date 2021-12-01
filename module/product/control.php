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
        $this->products = $this->product->getPairs('nocode|all');
        if(empty($this->products) and strpos(',create,index,showerrornone,ajaxgetdropmenu,kanban', $this->methodName) === false and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('product', 'create'));
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
        $this->lang->product->switcherMenu = $this->product->getSwitcher();

        if($locate == 'yes') $this->locate($this->createLink($this->moduleName, 'browse'));

        if($this->app->getViewType() != 'mhtml') unset($this->lang->product->menu->index);
        $productID = $this->product->saveState($productID, $this->products);
        $branch    = (int)$this->cookie->preBranch;

        if($this->app->viewType == 'mhtml') $this->product->setMenu($productID, $branch);

        if(common::hasPriv('product', 'create')) $this->lang->TRActions = html::a($this->createLink('product', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->product->create, '', "class='btn btn-primary'");

        $this->view->title      = $this->lang->product->index;
        $this->view->position[] = $this->lang->product->index;
        $this->display();
    }

    /**
     * The projects which linked the product.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $involved
     * @access public
     * @return void
     */
    public function project($status = 'all', $productID = 0, $branch = 0, $involved = 0)
    {
        $this->app->loadLang('execution');
        $this->app->loadLang('project');

        $this->product->setMenu($productID, $branch);

        /* Get PM id list. */
        $accounts     = array();
        $projectStats = $this->product->getProjectStatsByProduct($productID, $status, $branch, $involved);

        foreach($projectStats as $project)
        {
            if(!empty($project->PM) and !in_array($project->PM, $accounts)) $accounts[] = $project->PM;
        }
        $PMList = $this->user->getListByAccounts($accounts, 'account');

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->product->project;
        $this->view->position[]   = $this->products[$productID];
        $this->view->position[]   = $this->lang->product->project;
        $this->view->projectStats = $projectStats;
        $this->view->PMList       = $PMList;
        $this->view->productID    = $productID;
        $this->view->status       = $status;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Browse a product.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $storyType requirement|story
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = 0, $browseType = '', $param = 0, $storyType = 'story', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0)
    {
        $productID = $this->app->tab != 'project' ? $this->product->saveState($productID, $this->products) : $productID;

        if($this->app->tab == 'product') $this->product->setMenu($productID, $branch);
        if($this->app->tab == 'project')
        {
            $this->session->set('storyList', $this->app->getURI(true), 'project');
            $this->loadModel('project')->setMenu($projectID);
        }

        /* Lower browse type. */
        $browseType = strtolower($browseType);

        /* Load datatable and execution. */
        $this->loadModel('datatable');
        $this->loadModel('execution');

        /* Set product, module and query. */
        $branch = ($branch === '') ? (int)$this->cookie->preBranch : (int)$branch;
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('preBranch', (int)$branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch or $browseType == 'bybranch')
        {
            $_COOKIE['storyModule'] = 0;
            setcookie('storyModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }

        if($browseType == 'bymodule' or $browseType == '')
        {
            setcookie('storyModule', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($this->app->tab == 'project') setcookie('storyModuleParam', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $_COOKIE['storyBranch'] = 0;
            setcookie('storyBranch', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($browseType == '') setcookie('treeBranch', (int)$branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }
        if($browseType == 'bybranch') setcookie('storyBranch', (int)$branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

        $cookieModule = $this->app->tab == 'project' ? $this->cookie->storyModuleParam : $this->cookie->storyModule;
        $moduleID = ($browseType == 'bymodule') ? (int)$param : (($browseType == 'bysearch' or $browseType == 'bybranch') ? 0 : ($cookieModule ? $cookieModule : 0));
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu. The projectstory module does not execute. */
        if($this->app->tab == 'product')
        {
            /* Save session. */
            $this->session->set('storyList',   $this->app->getURI(true), 'product');
            $this->session->set('productList', $this->app->getURI(true), 'product');

            $this->lang->product->switcherMenu = $this->product->getSwitcher($productID, "storyType=$storyType", $branch);
        }

        /* Set moduleTree. */
        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        if($browseType == '')
        {
            setcookie('treeBranch', (int)$branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $browseType = 'unclosed';
        }
        else
        {
            $branch = (int)$this->cookie->treeBranch;
        }

        /* If in project story and not chose product, get project story mdoules. */
        if($this->app->rawModule == 'projectstory' and empty($productID))
        {
            $moduleTree = $this->tree->getProjectStoryTreeMenu($projectID, 0, array('treeModel', $createModuleLink));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'story', $startModuleID = 0, array('treeModel', $createModuleLink), array('projectID' => $projectID, 'productID' => $productID), $branch, "&param=$param&storyType=$storyType");
        }

        if($browseType != 'bymodule' and $browseType != 'bybranch') $this->session->set('storyBrowseType', $browseType);
        if(($browseType == 'bymodule' or $browseType == 'bybranch') and $this->session->storyBrowseType == 'bysearch') $this->session->set('storyBrowseType', 'unclosed');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->productStoryOrder ? $this->cookie->productStoryOrder : 'id_desc';
        setcookie('productStoryOrder', $orderBy, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $product = $this->product->getById($productID);

        /* Get stories. */
        if($this->app->rawModule == 'projectstory')
        {
            if(!empty($product)) $this->session->set('currentProductType', $product->type);
            $this->products  = $this->loadModel('project')->getProducts($projectID, false);
            $projectProducts = $this->product->getProducts($projectID);
            $productPlans    = $this->execution->getPlans($projectProducts);

            if($browseType == 'bybranch') $param = $branch;
            $stories = $this->story->getExecutionStories($projectID, $productID, $branch, $sort, $browseType, $param, 'story', '', $pager);
        }
        else
        {
            $stories = $this->product->getStories($productID, $branch, $browseType, $queryID, $moduleID, $storyType, $sort, $pager);
        }

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', $browseType != 'bysearch' && $this->app->rawModule != 'projectstory');

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        /* Get related tasks, bugs, cases count of each story. */
        $storyIdList = array();
        foreach($stories as $story)
        {
            $storyIdList[$story->id] = $story->id;
            if(!empty($story->children))
            {
                foreach($story->children as $child) $storyIdList[$child->id] = $child->id;
            }
        }
        $storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        /* Change for requirement story title. */
        if($storyType == 'requirement')
        {
            $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
            $this->config->product->search['fields']['title'] = $this->lang->story->title;
            unset($this->config->product->search['fields']['plan']);
            unset($this->config->product->search['fields']['stage']);
        }

        /* Build search form. */
        $rawModule = $this->app->rawModule;
        $rawMethod = $this->app->rawMethod;

        $params    = $rawModule == 'projectstory' ? "projectID=$projectID&" : '';
        $actionURL = $this->createLink($rawModule, $rawMethod, $params . "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID&storyType=$storyType");

        $this->config->product->search['onMenuBar'] = 'yes';
        $this->product->buildSearchForm($productID, $this->products, $queryID, $actionURL);

        $showModule = !empty($this->config->datatable->productBrowse->showModule) ? $this->config->datatable->productBrowse->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'story', $showModule) : array();

        $productName = ($this->app->rawModule == 'projectstory' and empty($productID)) ? $this->lang->product->all : $this->products[$productID];

        /* Assign. */
        $this->view->title           = $productName . $this->lang->colon . $this->lang->product->browse;
        $this->view->position[]      = $productName;
        $this->view->position[]      = $this->lang->product->browse;
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->productName     = $productName;
        $this->view->moduleID        = $moduleID;
        $this->view->stories         = $stories;
        $this->view->plans           = $this->loadModel('productplan')->getPairs($productID, $branch, '', true);
        $this->view->productPlans    = isset($productPlans) ? array(0 => '') + $productPlans : array();
        $this->view->summary         = $this->product->summary($stories, $storyType);
        $this->view->moduleTree      = $moduleTree;
        $this->view->parentModules   = $this->tree->getParents($moduleID);
        $this->view->pager           = $pager;
        $this->view->users           = $this->user->getPairs('noletter|pofirst|nodeleted');
        $this->view->orderBy         = $orderBy;
        $this->view->browseType      = $browseType;
        $this->view->modules         = $this->tree->getOptionMenu($productID, 'story', 0, $branch);
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->branch          = $branch;
        $this->view->branches        = $this->loadModel('branch')->getPairs($productID);
        $this->view->storyStages     = $this->product->batchGetStoryStage($stories);
        $this->view->setModule       = true;
        $this->view->storyTasks      = $storyTasks;
        $this->view->storyBugs       = $storyBugs;
        $this->view->storyCases      = $storyCases;
        $this->view->param           = $param;
        $this->view->projectID       = $projectID;
        $this->view->products        = $this->products;
        $this->view->projectProducts = isset($projectProducts) ? $projectProducts : array();
        $this->view->storyType       = $storyType;
        $this->view->from            = $this->app->tab;
        $this->display();
    }

    /**
     * Create a product.
     *
     * @param  int    $programID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($programID = 0, $extra = '')
    {
        if(!empty($_POST))
        {
            $productID = $this->product->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('product', $productID, 'opened');

            $this->executeHooks($productID);
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $productID));

            $tab = $this->app->tab;
            $moduleName = $tab == 'program'? 'program' : $this->moduleName;
            $methodName = $tab == 'program'? 'product' : 'browse';
            $param      = $tab == 'program' ? "programID=$programID" : "productID=$productID";
            $locate     = $this->createLink($moduleName, $methodName, $param);
            if($tab == 'doc') $locate = $this->createLink('doc', 'objectLibs', 'type=product');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        if($this->app->tab == 'program') $this->loadModel('program')->setMenu($programID);
        if($this->app->getViewType() == 'mhtml')
        {
            if($this->app->rawModule == 'projectstory' and $this->app->rawMethod == 'story')
            {
                $this->loadModel('project')->setMenu();
            }
            else
            {
                $this->product->setMenu('');
            }
        }

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst|noclosed',  '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('nodeleted|qdfirst|noclosed',  '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('nodeleted|devfirst|noclosed', '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $lines = array();
        if($programID) $lines = array('') + $this->product->getLinePairs($programID);
        if($this->config->systemMode == 'classic') $lines = array('') + $this->product->getLinePairs();

        if($this->app->tab == 'doc') unset($this->lang->doc->menu->product['subMenu']);

        $this->view->title      = $this->lang->product->create;
        $this->view->position[] = $this->view->title;
        $this->view->gobackLink = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('product', 'all') : '';
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->programID  = $programID;
        $this->view->poUsers    = $poUsers;
        $this->view->qdUsers    = $qdUsers;
        $this->view->rdUsers    = $rdUsers;
        $this->view->users      = $this->user->getPairs('nodeleted|noclosed');
        $this->view->programs   = array('') + $this->loadModel('program')->getTopPairs('', 'noclosed');
        $this->view->lines      = $lines;
        $this->view->URSRPairs  = $this->loadModel('custom')->getURSRPairs();

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * Edit a product.
     *
     * @param  int       $productID
     * @param  string    $action
     * @param  string    $extra
     * @param  int       $programID
     * @access public
     * @return void
     */
    public function edit($productID, $action = 'edit', $extra = '', $programID = 0)
    {
        $this->app->loadLang('custom');

        /* Init vars. */
        $product = $this->product->getById($productID);
        if($product->bind) $this->config->product->edit->requiredFields = 'name';

        $unmodifiableProjects = array();
        $canChangeProgram     = true;
        $singleLinkProjects   = array();
        $multipleLinkProjects = array();
        $linkStoriesProjects  = array();

        /* Link the projects stories under this product. */
        $unmodifiableProjects = $this->dao->select('t1.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchPairs('project', 'product');

        if(!empty($unmodifiableProjects)) $canChangeProgram = false;

        /* Get the projects linked with this product. */
        $projectPairs = $this->dao->select('t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchPairs();

        if(!empty($projectPairs))
        {
            foreach($projectPairs as $projectID => $projectName)
            {
                if($canChangeProgram)
                {
                    $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                    if(count($products) == 1)
                    {
                        $singleLinkProjects[$projectID] = $projectName;
                    }

                    if(count($products) > 1)
                    {
                        $multipleLinkProjects[$projectID] = $projectName;
                    }
                }
                else
                {
                    if(isset($unmodifiableProjects[$projectID])) $linkStoriesProjects[$projectID] = $projectName;
                }
            }
        }

        if(!empty($_POST))
        {
            $changes = $this->product->update($productID);

            if($this->config->systemMode == 'new')
            {
                /* Change the projects set of the program. */
                if(($_POST['program'] != $product->program) and ($singleLinkProjects or $multipleLinkProjects))
                {
                    $this->product->updateProjects($productID, $singleLinkProjects, $multipleLinkProjects);
                }
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
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

            $this->executeHooks($productID);

            $moduleName = $programID ? 'program'    : 'product';
            $methodName = $programID ? 'product' : 'view';
            $param      = $programID ? "programID=$programID" : "product=$productID";
            $locate     = $this->createLink($moduleName, $methodName, $param);

            if(!$programID) $this->session->set('productList', $this->createLink('product', 'browse', $param), 'product');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $productID = $this->product->saveState($productID, $this->products);
        $this->product->setMenu($productID);

        if($programID) $this->loadModel('program')->setMenu($programID);

        /* Get the relevant person in charge. */
        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst|noclosed',  $product->PO, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('nodeleted|qdfirst|noclosed',  $product->QD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('nodeleted|devfirst|noclosed', $product->RD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $lines = array();
        if($product->program) $lines = array('') + $this->product->getLinePairs($product->program);
        if($this->config->systemMode == 'classic') $lines = array('') + $this->product->getLinePairs();

        /* Get programs. */
        $programs = $this->loadModel('program')->getTopPairs();
        if(!isset($programs[$product->program]) and $product->program)
        {
            $program  = $this->program->getByID($product->program);
            $programs = array($product->program => $program->name);
        }

        $this->view->title      = $this->lang->product->edit . $this->lang->colon . $product->name;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->edit;

        $this->view->product              = $product;
        $this->view->groups               = $this->loadModel('group')->getPairs();
        $this->view->program              = $this->loadModel('program')->getParentPairs();
        $this->view->poUsers              = $poUsers;
        $this->view->poUsers              = $poUsers;
        $this->view->qdUsers              = $qdUsers;
        $this->view->rdUsers              = $rdUsers;
        $this->view->users                = $this->user->getPairs('nodeleted|noclosed');
        $this->view->programs             = array('') + $programs;
        $this->view->lines                = $lines;
        $this->view->URSRPairs            = $this->loadModel('custom')->getURSRPairs();
        $this->view->canChangeProgram     = $canChangeProgram;
        $this->view->singleLinkProjects   = $singleLinkProjects;
        $this->view->multipleLinkProjects = $multipleLinkProjects;
        $this->view->linkStoriesProjects  = $linkStoriesProjects;

        unset($this->lang->product->typeList['']);
        $this->display();
    }

    /**
     * Batch edit products.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function batchEdit($programID = 0)
    {
        $this->lang->product->switcherMenu = '';
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

            $locate = $this->app->tab == 'product' ? $this->createLink('product', 'all') : $this->createLink('program', 'product', "programID=$programID");
            die(js::locate($locate, 'parent'));
        }

        $productIDList = $this->post->productIDList;
        if(empty($productIDList)) die(js::locate($this->session->productList, 'parent'));

        /* Set menu when page come from program. */
        if($this->app->tab == 'program') $this->loadModel('program')->setMenu(0);

        /* Set custom. */
        foreach(explode(',', $this->config->product->customBatchEditFields) as $field) $customFields[$field] = $this->lang->product->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->product->custom->batchEditFields;

        $products      = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIDList)->fetchAll('id');
        $appendPoUsers = $appendQdUsers = $appendRdUsers = array();
        foreach($products as $product)
        {
            $appendPoUsers[$product->PO] = $product->PO;
            $appendQdUsers[$product->QD] = $product->QD;
            $appendRdUsers[$product->RD] = $product->RD;
        }

        /* Navigation remains under the program. */
        $this->lang->program->switcherMenu = $this->loadModel('program')->getSwitcher($programID, true);

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('nodeleted|pofirst', $appendPoUsers);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('nodeleted|qdfirst', $appendQdUsers);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('nodeleted|devfirst', $appendRdUsers);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $programs             = array();
        $unauthorizedPrograms = array();
        if($this->config->systemMode == 'new')
        {
            $programs = $this->program->getTopPairs();

            /* Get unauthorized programs. */
            $programIDList = array();
            foreach($products as $product)
            {
                if($product->program and !isset($programs[$product->program]) and !in_array($product->program, $programIDList)) $programIDList[] = $product->program;
            }
            $unauthorizedPrograms = $this->program->getPairsByList($programIDList);

            /* Get product lines by programs.*/
            $lines = array(0 => '');
            foreach($programs + $unauthorizedPrograms as $id => $program)
            {
                $lines[$id] = array('') + $this->product->getLinePairs($id);
            }
        }
        else
        {
            $lines = array('') + $this->product->getLinePairs();
        }

        $this->view->title                = $this->lang->product->batchEdit;
        $this->view->position[]           = $this->lang->product->batchEdit;
        $this->view->lines                = $lines;
        $this->view->productIDList        = $productIDList;
        $this->view->products             = $products;
        $this->view->poUsers              = $poUsers;
        $this->view->qdUsers              = $qdUsers;
        $this->view->rdUsers              = $rdUsers;
        $this->view->programID            = $programID;
        $this->view->programs             = array('' => '') + $programs;
        $this->view->unauthorizedPrograms = $unauthorizedPrograms;

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

            $this->executeHooks($productID);

            die(js::reload('parent.parent'));
        }

        $this->product->setMenu($productID);

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
        $productID = (int)$productID;
        $product   = $this->product->getStatByID($productID);

        if(!$product)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            die(js::error($this->lang->notFound) . js::locate($this->createLink('product', 'index')));
        }

        $product->desc = $this->loadModel('file')->setImgSize($product->desc);
        $this->product->setMenu($productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

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
            $this->session->set('product', '');
            $this->executeHooks($productID);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
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
        $this->lang->product->switcherMenu = $this->product->getSwitcher($productID, '', $branch);
        $this->product->setMenu($productID, $branch);

        $this->session->set('releaseList',     $this->app->getURI(true), 'product');
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');

        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        if(empty($product)) $this->locate($this->createLink('product', 'showErrorNone', 'fromModule=product'));

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->roadmap;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->roadmap;
        $this->view->product    = $product;
        $this->view->roadmaps   = $this->product->getRoadmap($productID, $branch);
        $this->view->branches   = $product->type == 'normal' ? array(0 => '') : $this->loadModel('branch')->getPairs($productID);

        $this->display();
    }

    /**
     * Product dynamic.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction    next|pre
     * @access public
     * @return void
     */
    public function dynamic($productID = 0, $type = 'today', $param = '', $recTotal = 0, $date = '', $direction = 'next')
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');

        $this->product->setMenu($productID, 0, 0, '', $type);

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';
        $sort    = $this->loadModel('common')->appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id');
            if($user) $account = $user->account;
        }
        $period  = $type == 'account' ? 'all'  : $type;
        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($account, $period, $sort, $pager, $productID, 'all', 'all', $date, $direction);

        /* The header and position. */
        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->product->dynamic;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $this->products[$productID]);
        $this->view->position[] = $this->lang->product->dynamic;

        $this->lang->product->switcherMenu = $this->product->getSwitcher($productID, $type);

        $this->view->userIdPairs  = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed|useid');
        $this->view->accountPairs = $this->user->getPairs('noletter|nodeleted|noclosed');

        /* Assign. */
        $this->view->productID  = $productID;
        $this->view->type       = $type;
        $this->view->orderBy    = $orderBy;
        $this->view->account    = $account;
        $this->view->user       = isset($user) ? $user : '';
        $this->view->param      = $param;
        $this->view->pager      = $pager;
        $this->view->dateGroups = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction  = $direction;
        $this->display();
    }

    /**
     * Product dashboard.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function dashboard($productID = 0)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');

        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getStatByID($productID);
        if(!$product) die(js::locate('product', 'all'));

        $product->desc = $this->loadModel('file')->setImgSize($product->desc);
        $this->product->setMenu($productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->view;
        $this->view->position[] = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $this->view->position[] = $this->lang->product->view;

        $this->view->product  = $product;
        $this->view->actions  = $this->loadModel('action')->getList('product', $productID);
        $this->view->users    = $this->user->getPairs('noletter');
        $this->view->lines    = array('') + $this->product->getLinePairs();
        $this->view->dynamics = $this->action->getDynamic('all', 'all', 'date_desc', $pager, $productID);
        $this->view->roadmaps = $this->product->getRoadmap($productID, 0, 6);

        $this->display();
    }

    /**
     * Ajax get products.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetProducts($executionID)
    {
        $this->loadModel('build');
        $products = $this->product->getProductPairsByProject($executionID);
        if(empty($products))
        {
            die(printf($this->lang->build->noProduct, $this->createLink('execution', 'manageproducts', "executionID=$executionID&from=buildCreate", '', 'true'), 'project'));
        }
        else
        {
            die(html::select('product', $products, empty($product) ? '' : $product->id, "onchange='loadBranches(this.value);' class='form-control chosen' required data-toggle='modal' data-type='iframe'"));
        }
    }

    /**
     * AJAX: get projects of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProjects($productID, $branch = 0, $projectID = 0)
    {
        $projects  = array('' => '');
        $projects += $this->product->getProjectPairsByProduct($productID, $branch ? "0,$branch" : $branch);
        if($this->app->getViewType() == 'json') die(json_encode($projects));

        die(html::select('project', $projects, $projectID, "class='form-control' onchange='loadProductExecutions({$productID}, this.value)'"));
    }

    /**
     * AJAX: get executions of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $branch
     * @param  string $number
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetExecutions($productID, $projectID = 0, $branch = 0, $number = '', $executionID = 0)
    {
        $executions = $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : $branch, 'id_desc', $projectID);
        if($this->app->getViewType() == 'json') die(json_encode($executions));

        if($number === '')
        {
            die(html::select('execution', array('' => '') + $executions, $executionID, "class='form-control' onchange='loadExecutionRelated(this.value)'"));
        }
        else
        {
            $executionsName = "executions[$number]";
            $executions     = empty($executions) ? array('' => '') : $executions;
            die(html::select($executionsName, $executions, '', "class='form-control' onchange='loadExecutionBuilds($executionID, this.value, $number)'"));
        }
    }

    /**
     * AJAX: get plans of a product in html select.
     *
     * @param  int    $productID
     * @param  int    $planID
     * @param  bool   $needCreate
     * @param  string $expired
     * @access public
     * @return void
     */
    public function ajaxGetPlans($productID, $branch = 0, $planID = 0, $fieldID = '', $needCreate = false, $expired = '')
    {
        $plans = $this->loadModel('productplan')->getPairs($productID, $branch, $expired);
        $field = $fieldID ? "plans[$fieldID]" : 'plan';
        $output = html::select($field, $plans, $planID, "class='form-control chosen'");
        if(count($plans) == 1 and $needCreate)
        {
            $output .= "<div class='input-group-btn'>";
            $output .= html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch", '', true), "<i class='icon icon-plus'></i>", '', "class='btn btn-icon' data-toggle='modal' data-type='iframe' data-width='95%' title='{$this->lang->productplan->create}'");
            $output .= '</div>';
            $output .= "<div class='input-group-btn'>";
            $output .= html::a("javascript:void(0)", "<i class='icon icon-refresh'></i>", '', "class='btn btn-icon refresh' data-toggle='tooltip' title='{$this->lang->refresh}' onclick='loadProductPlans($productID)'");
            $output .= '</div>';
        }
        die($output);
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

        if($productID)  die(html::select("lines[$productID]", array('' => '') + $lines, '', "class='form-control picker-select'"));
        if(!$productID) die(html::select('line', array('' => '') + $lines, '', "class='form-control chosen'"));
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
        if(!$productReviewers) $productReviewers = $this->loadModel('user')->getProductViewListUsers($product, '', '', '');

        $storyReviewers = '';
        if($storyID)
        {
            $story          = $this->loadModel('story')->getByID($storyID);
            $storyReviewers = $this->story->getReviewerPairs($story->id, $story->version);
            $storyReviewers = implode(',', array_keys($storyReviewers));
        }

        $reviewers = $this->loadModel('user')->getPairs('noclosed|nodeleted', $storyReviewers, 0, $productReviewers);

        die(html::select("reviewer[]", $reviewers, $storyReviewers, "class='form-control chosen' multiple"));
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
    public function ajaxGetDropMenu($productID, $module, $method, $extra = '', $from = '')
    {
        if($from == 'qa')
        {
            $this->app->loadConfig('qa');
            foreach($this->config->qa->menuList as $menu) $this->lang->navGroup->$menu = 'qa';
        }

        $programProducts = array();

        $products = $this->app->tab == 'project' ? $this->product->getProducts($this->session->project) : $this->product->getList();

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
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        /* Init vars. */
        $idList  = explode(',', trim($this->post->products, ','));
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        /* Remove programID. */
        foreach($idList as $i => $id)
        {
            if(!is_numeric($id)) unset($idList[$i]);
        }

        /* Update order. */
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
     * @param  string $moduleName
     * @param  string $activeMenu
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function showErrorNone($moduleName = 'qa', $activeMenu = 'index', $objectID = 0)
    {
        if($moduleName == 'project')
        {
            $this->loadModel('project')->setMenu($objectID);
            $project = $this->project->getByID($objectID);
            $this->lang->project->menu      = $this->lang->{$project->model}->menu;
            $this->lang->project->menuOrder = $this->lang->{$project->model}->menuOrder;
            $this->app->rawModule = $activeMenu;

            if($activeMenu == 'bug')            $this->lang->{$project->model}->menu->qa['subMenu']->bug['subModule']        = 'product';
            if($activeMenu == 'testcase')       $this->lang->{$project->model}->menu->qa['subMenu']->testcase['subModule']   = 'product';
            if($activeMenu == 'testtask')       $this->lang->{$project->model}->menu->qa['subMenu']->testtask['subModule']   = 'product';
            if($activeMenu == 'testreport')     $this->lang->{$project->model}->menu->qa['subMenu']->testreport['subModule'] = 'product';
            if($activeMenu == 'projectrelease') $this->lang->{$project->model}->menu->release['subModule']                   = 'projectrelease';
        }
        elseif($moduleName == 'qa')
        {
            $this->loadModel('qa')->setMenu(array(), 0);
            $this->app->rawModule = $activeMenu;

            if($activeMenu == 'testcase')   unset($this->lang->qa->menu->testcase['subMenu']);
            if($activeMenu == 'testsuite')  unset($this->lang->qa->menu->testcase['subMenu']);
            if($activeMenu == 'testtask')   unset($this->lang->qa->menu->testtask['subMenu']);
            if($activeMenu == 'testreport') unset($this->lang->qa->menu->testtask['subMenu']);
        }
        elseif($moduleName == 'execution')
        {
            $this->loadModel('execution')->setMenu($objectID);
            $this->app->rawModule = $activeMenu;
            if($activeMenu == 'bug')        $this->lang->execution->menu->qa['subMenu']->bug['subModule']        = 'product';
            if($activeMenu == 'testcase')   $this->lang->execution->menu->qa['subMenu']->testcase['subModule']   = 'product';
            if($activeMenu == 'testtask')   $this->lang->execution->menu->qa['subMenu']->testtask['subModule']   = 'product';
            if($activeMenu == 'testreport') $this->lang->execution->menu->qa['subMenu']->testreport['subModule'] = 'product';
        }
        if($this->app->getViewType() == 'mhtml') $this->product->setMenu('');

        $this->view->title    = $this->lang->$moduleName->common;
        $this->view->objectID = $objectID;
        $this->display();
    }

    /**
     * Products under project set.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function all($browseType = 'noclosed', $orderBy = 'order_asc')
    {
        /* Load module and set session. */
        $this->loadModel('program');
        $this->session->set('productList', $this->app->getURI(true), 'product');

        if($this->app->viewType == 'mhtml')
        {
            $productID = $this->product->saveState(0, $this->products);
            $this->product->setMenu($productID);
        }

        /* Process product structure. */
        $productStats     = $this->product->getStats($orderBy, '', $browseType, '', 'story');
        $productStructure = $this->product->statisticProgram($productStats);

        $this->view->title        = $this->lang->product->common;
        $this->view->position[]   = $this->lang->product->common;

        $this->view->recTotal         = count($productStats);
        $this->view->productStats     = $productStats;
        $this->view->productStructure = $productStructure;
        $this->view->orderBy          = $orderBy;
        $this->view->browseType       = $browseType;

        $this->display();
    }

    /**
     * Product kanban.
     *
     * @access public
     * @return void
     */
    public function kanban()
    {
        $this->session->set('projectList', $this->app->getURI(true), 'project');
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');
        $this->session->set('releaseList', $this->app->getURI(true), 'product');

        $kanbanGroup = $this->product->getStats4Kanban();
        extract($kanbanGroup);

        $programPairs  = $this->loadModel('program')->getPairs(true);
        $myProducts    = array();
        $otherProducts = array();
        foreach($productList as $productID => $product)
        {
            if($product->status != 'normal') continue;
            if($product->PO == $this->app->user->account) $myProducts[$product->program][] = $productID;
            else $otherProducts[$product->program][] = $productID;
        }

        $kanbanList = array();
        if(!empty($myProducts))    $kanbanList['my']    = $myProducts;
        if(!empty($otherProducts)) $kanbanList['other'] = $otherProducts;

        $emptyHour = new stdclass();
        $emptyHour->totalEstimate = 0;
        $emptyHour->totalConsumed = 0;
        $emptyHour->totalLeft     = 0;
        $emptyHour->progress      = 0;

        $this->view->title            = $this->lang->product->kanban;
        $this->view->kanbanList       = $kanbanList;
        $this->view->programList      = array(0 => $this->lang->product->emptyProgram) + $programPairs;
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
            $this->product->manageLine();
            die(js::reload('parent'));
        }

        $this->view->title      = $this->lang->product->line;
        $this->view->position[] = $this->lang->product->line;

        $this->view->programs = array('') + $this->loadModel('program')->getTopPairs();
        $this->view->lines    = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('`order`')->fetchAll();
        $this->display();
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
    public function whitelist($productID = 0, $module = 'product', $objectType = 'product', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->lang->product->switcherMenu = $this->product->getSwitcher($productID, '', 0);
        $this->product->setMenu($productID, 0);
        $this->lang->modulePageNav = '';

        echo $this->fetch('personnel', 'whitelist', "objectID=$productID&module=product&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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
    public function addWhitelist($productID = 0, $deptID = 0, $copyID = 0)
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
    public function unbindWhitelist($id = 0, $confirm = 'no')
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
    public function export($status, $orderBy)
    {
        if($_POST)
        {
            $productLang   = $this->lang->product;
            $productConfig = $this->config->product;

            /* Create field lists. */
            if(!$this->config->URAndSR) $productConfig->list->exportFields = str_replace('activeRequirements,changedRequirements,draftRequirements,closedRequirements,requireCompleteRate,', '', $productConfig->list->exportFields);
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $productConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($productLang, $fieldName);
                unset($fields[$key]);
            }

            $lines = $this->product->getLinePairs();
            $productStats = $this->product->getStats('program_desc,line_desc,' . $orderBy, null, $status);
            foreach($productStats as $i => $product)
            {
                $product->line                = zget($lines, $product->line, '');
                if($this->config->URAndSR)
                {
                    $product->activeRequirements  = (int) $product->requirements['active'];
                    $product->changedRequirements = (int) $product->requirements['changed'];
                    $product->draftRequirements   = (int) $product->requirements['draft'];
                    $product->closedRequirements  = (int) $product->requirements['closed'];
                    $product->totalRequirements   = $product->activeRequirements + $product->changedRequirements + $product->draftRequirements + $product->closedRequirements;
                    $product->requireCompleteRate = ($product->totalRequirements == 0 ? 0 : round($product->closedRequirements / $product->totalRequirements, 3) * 100) . '%';
                }
                $product->activeStories       = (int)$product->stories['active'];
                $product->changedStories      = (int)$product->stories['changed'];
                $product->draftStories        = (int)$product->stories['draft'];
                $product->closedStories       = (int)$product->stories['closed'];
                $product->totalStories        = $product->activeStories + $product->changedStories + $product->draftStories + $product->closedStories;
                $product->storyCompleteRate   = ($product->totalStories == 0 ? 0 : round($product->closedStories / $product->totalStories, 3) * 100) . '%';
                $product->unResolvedBugs      = (int)$product->unResolved;
                $product->assignToNullBugs    = (int)$product->assignToNull;
                $product->closedBugs          = (int)$product->closedBugs;
                $product->bugFixedRate        = (($product->unResolved + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unResolved + $product->fixedBugs), 3) * 100) . '%';
                $product->program             = $product->programName;

                /* get rowspan data */
                if($lastProgram == '' or $product->program != $lastProgram)
                {
                    $rowspan[$i]['rows']['program'] = 1;
                    $programI = $i;
                }
                else $rowspan[$programI]['rows']['program'] ++;
                if($lastLine == '' or $product->line != $lastLine)
                {
                    $rowspan[$i]['rows']['line'] = 1;
                    $lineI = $i;
                }
                else $rowspan[$lineI]['rows']['line'] ++;
                $lastProgram = $product->program;
                $lastLine    = $product->line;

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$product->id},") === false) unset($productStats[$i]);
                }
            }
            if(isset($this->config->bizVersion)) list($fields, $productStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $productStats);

            if(isset($rowspan)) $this->post->set('rowspan', $rowspan);
            $this->post->set('fields', $fields);
            $this->post->set('rows', $productStats);
            $this->post->set('kind', 'product');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }
        $this->display();
    }

    /**
     * Build of product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function build($productID = 0, $branch = 0)
    {
        $this->app->loadLang('build');
        $this->session->set('productList', $this->app->getURI(true), 'product');

        /* Get all product list. Locate to the create product page if there is no product. */
        $this->products = $this->product->getPairs('', $this->session->project);
        if(empty($this->products) and strpos('create|view', $this->methodName) === false) $this->locate($this->createLink('product', 'create'));

        /* Get current product. */
        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getById($productID);
        $this->product->setMenu($productID, $branch);

        /* Set menu.*/
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $this->view->title      = $product->name . $this->lang->colon . $this->lang->product->build;
        $this->view->position[] = $this->lang->product->build;
        $this->view->products   = $this->products;
        $this->view->product    = $product;
        $this->view->builds     = $this->dao->select('*')->from(TABLE_BUILD)->where('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->eq($branch)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Set product id to session in ajax
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxSetState($productID)
    {
        $this->session->set('product', (int)$productID);
        $this->send(array('result' => 'success', 'productID' => $this->session->product));
    }
}
