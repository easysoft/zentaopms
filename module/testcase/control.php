<?php
/**
 * The control file of case currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: control.php 5112 2013-07-12 02:51:33Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testcase extends control
{
    public $products = array();

    /**
     * Construct function, load product, tree, user auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->view->products = $this->products = $this->product->getPairs('nocode');
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', "fromModule=testcase")));
    }

    /**
     * Index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('testcase', 'browse'));
    }

    /**
     * Browse cases.
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
    public function browse($productID = 0, $branch = '', $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');

        /* Set browse type. */
        $browseType = strtolower($browseType);

        /* Set browseType, productID, moduleID and queryID. */
        $productID  = $this->product->saveState($productID, $this->products);
        $branch     = ($branch === '') ? (int)$this->cookie->preBranch : (int)$branch;
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot);
        setcookie('preBranch', (int)$branch, $this->config->cookieLife, $this->config->webRoot);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch)
        {
            $_COOKIE['caseModule'] = 0;
            setcookie('caseModule', 0, $this->config->cookieLife, $this->config->webRoot);
        }
        if($browseType == 'bymodule') setcookie('caseModule', (int)$param, $this->config->cookieLife, $this->config->webRoot);
        if($browseType != 'bymodule') $this->session->set('caseBrowseType', $browseType);

        $moduleID   = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->caseModule ? $this->cookie->caseModule : 0));
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu, save session. */
        $this->testcase->setMenu($this->products, $productID, $branch);
        $this->session->set('caseList', $this->app->getURI(true));
        $this->session->set('productID', $productID);
        $this->session->set('moduleID', $moduleID);
        $this->session->set('browseType', $browseType);
        $this->session->set('orderBy', $orderBy);

        /* Load lang. */
        $this->app->loadLang('testtask');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Get test cases. */
        $cases = $this->testcase->getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager);

        /* save session .*/
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', $browseType != 'bysearch' ? false : true);

        /* Process case for check story changed. */
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['onMenuBar'] = 'yes';
        $this->testcase->buildSearchForm($productID, $this->products, $queryID, $actionURL);

        $showModule = !empty($this->config->datatable->testcaseBrowse->showModule) ? $this->config->datatable->testcaseBrowse->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'case', $showModule) : array();

        /* Assign. */
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]    = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testcase->common;
        $this->view->productID     = $productID;
        $this->view->product       = $this->product->getById($productID);
        $this->view->productName   = $this->products[$productID];
        $this->view->modules       = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch);
        $this->view->moduleTree    = $this->tree->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createCaseLink'), '', $branch);
        $this->view->moduleName    = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->moduleID      = $moduleID;
        $this->view->pager         = $pager;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->cases         = $cases;
        $this->view->branch        = $branch;
        $this->view->branches      = $this->loadModel('branch')->getPairs($productID);
        $this->view->setShowModule = true;


        $this->display();
    }

    /**
     * Group case.
     * 
     * @param  int    $productID 
     * @param  string $groupBy 
     * @access public
     * @return void
     */
    public function groupCase($productID = 0, $branch = '', $groupBy = 'stroy')
    {
        $groupBy   = empty($groupBy) ? 'stroy' : $groupBy;
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;

        $this->app->loadLang('testtask');

        $this->testcase->setMenu($this->products, $productID, $branch);
        $this->session->set('caseList', $this->app->getURI(true));

        $cases = $this->testcase->getModuleCases($productID, $branch, 0, $groupBy);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $groupCases  = array();
        $groupByList = array();
        foreach($cases as $case)
        {
            if($groupBy == 'story')
            {
                $groupCases[$case->story][] = $case;
                $groupByList[$case->story]  = $case->storyTitle;
            }
        }

        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]    = html::a($this->createLink('testcase', 'groupTask', "productID=$productID&groupBy=$groupBy"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testcase->common;
        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->browseType    = 'group';
        $this->view->groupBy       = $groupBy;
        $this->view->groupByList   = $groupByList;
        $this->view->cases         = $groupCases;
        $this->display();
    }

    /**
     * Create a test case.
     * 
     * @param  int    $productID 
     * @param  int    $moduleID 
     * @param  string $from
     * @param  int    $param
     * @access public
     * @return void
     */
    public function create($productID, $branch = '', $moduleID = 0, $from = '', $param = 0, $storyID = 0)
    {
        $testcaseID = $from == 'testcase' ? $param : 0;
        $bugID      = $from == 'bug' ? $param : 0;
        
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $caseResult = $this->testcase->create($bugID);
            if(!$caseResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $caseID = $caseResult['id'];
            if($caseResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->testcase->common);
                $response['locate']  = $this->createLink('testcase', 'view', "caseID=$caseID");
                $this->send($response);
            }

            $this->loadModel('action');
            $this->action->create('case', $caseID, 'Opened');
            $response['locate'] = $this->createLink('testcase', 'browse', "productID={$_POST['product']}&branch=" . (isset($_POST['branch']) ? $_POST['branch'] : '') . "&browseType=byModule&args={$_POST['module']}");
            $this->send($response);
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Set productID and currentModuleID. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        if($storyID and empty($moduleID))
        {
            $story    = $this->loadModel('story')->getByID($storyID);
            $moduleID = $story->module;
        }
        $currentModuleID = (int)$moduleID;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $productID, $branch);

        /* Init vars. */
        $type         = 'feature';
        $stage        = '';
        $pri          = 0;
        $caseTitle    = '';
        $precondition = '';
        $keywords     = '';
        $steps        = array();

        /* If testcaseID large than 0, use this testcase as template. */
        if($testcaseID > 0)
        {
            $testcase     = $this->testcase->getById($testcaseID);
            $productID    = $testcase->product;
            $type         = $testcase->type ? $testcase->type : 'feature';
            $stage        = $testcase->stage;
            $pri          = $testcase->pri;
            $storyID      = $testcase->story;
            $caseTitle    = $testcase->title;
            $precondition = $testcase->precondition;
            $keywords     = $testcase->keywords;
            $steps        = $testcase->steps;
        }
               
        /* If bugID large than 0, use this bug as template. */
        if($bugID > 0)
        {
            $bug      = $this->loadModel('bug')->getById($bugID);
            $type     = $bug->type;
            $pri      = $bug->pri ? $bug->pri : $bug->severity;
            $storyID  = $bug->story;
            $caseTitle= $bug->title;
            $keywords = $bug->keywords;
            $steps    = $this->testcase->createStepsFromBug($bug->steps);
        }
       
        /* Padding the steps to the default steps count. */
        if(count($steps) < $this->config->testcase->defaultSteps)
        {
            $paddingCount = $this->config->testcase->defaultSteps - count($steps);
            $step = new stdclass();
            $step->desc   = '';
            $step->expect = '';
            for($i = 1; $i <= $paddingCount; $i ++) $steps[] = $step;
        }

        $title      = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $position[] = $this->lang->testcase->common;
        $position[] = $this->lang->testcase->create;

        /* Get the status of stories are not closed. */
        $storyStatus = $this->lang->story->statusList;
        unset($storyStatus['closed']);
        $modules = array();
        if($currentModuleID)
        {
            $modules = $this->loadModel('tree')->getStoryModule($currentModuleID);
            $modules = $this->tree->getAllChildID($modules);
        }
        $stories = $this->story->getProductStoryPairs($productID, $branch, $modules, array_keys($storyStatus), 'id_desc', 50);

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->createFields;

        $this->view->title            = $title;
        $this->view->caseTitle        = $caseTitle;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch);
        $this->view->currentModuleID  = $currentModuleID ? $currentModuleID : (isset($story->module) ? $story->module : 0);
        $this->view->stories          = $stories;
        $this->view->type             = $type;
        $this->view->stage            = $stage;
        $this->view->pri              = $pri;
        $this->view->storyID          = $storyID;
        $this->view->title            = $title;
        $this->view->precondition     = $precondition;
        $this->view->keywords         = $keywords;
        $this->view->steps            = $steps;
        $this->view->branch           = $branch;
        $this->view->branches         = $this->session->currentProductType != 'normal' ? $this->loadModel('branch')->getPairs($productID) : array();

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
            $caseID = $this->testcase->batchCreate($productID, $branch, $storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=byModule&param=$moduleID"), 'parent'));
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Set productID and currentModuleID. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        if($storyID and empty($moduleID))
        {
            $story    = $this->loadModel('story')->getByID($storyID);
            $moduleID = $story->module;
        }
        $currentModuleID = (int)$moduleID;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $productID, $branch);

        /* Set story list. */
        $story     = $storyID ? $this->story->getByID($storyID) : '';
        $storyList = $storyID ? array($storyID => $story->id . ':' . $story->title . '(' . $this->lang->story->pri . ':' . $story->pri . ',' . $this->lang->story->estimate . ':' . $story->estimate . ')') : array('');

        /* Set module option menu. */
        $moduleOptionMenu          = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch);
        $moduleOptionMenu['ditto'] = $this->lang->testcase->ditto;

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->batchCreateFields;

        $this->view->title            = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->batchCreate;
        $this->view->position[]       = html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[]       = $this->lang->testcase->common;
        $this->view->position[]       = $this->lang->testcase->batchCreate;
        $this->view->productID        = $productID;
        $this->view->story            = $story;
        $this->view->storyList        = $storyList;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->branch           = $branch;
        $this->view->branches         = $this->loadModel('branch')->getPairs($productID);

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
        parse_str(str_replace(array(',', ' '), array('&', ''), $extras));

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

        if(!$case) die(js::error($this->lang->notFound) . js::locate('back', 'parent'));
        if(empty($case->steps)) die(js::locate($this->createLink('bug', 'create', "product=$productID&branch=$branch&extras=$extras"), 'parent'));

        $this->view->title     = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->createBug;
        $this->view->case      = $case;
        $this->view->result    = reset($results);
        $this->view->extras    = $extras;
        $this->view->productID = $productID;
        $this->view->branch    = $branch;
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
        $case = $this->testcase->getById($caseID, $version);
        if(!$case) die(js::error($this->lang->notFound) . js::locate('back'));
        if($from == 'testtask') $run = $this->loadModel('testtask')->getRunByCase($taskID, $caseID);

        $productID = $case->product;
        $this->testcase->setMenu($this->products, $productID, $case->branch);

        $this->view->title      = "CASE #$case->id $case->title - " . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testcase->common;
        $this->view->position[] = $this->lang->testcase->view;

        $this->view->case           = $case;
        $this->view->from           = $from;
        $this->view->taskID         = $taskID;
        $this->view->version        = $version ? $version : $case->version;
        $this->view->productName    = $this->products[$productID];
        $this->view->branchName     = $this->session->currentProductType == 'normal' ? '' : $this->loadModel('branch')->getById($case->branch);
        $this->view->modulePath     = $this->tree->getParents($case->module);
        $this->view->users          = $this->user->getPairs('noletter');
        $this->view->actions        = $this->loadModel('action')->getList('case', $caseID);
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('testcase', $caseID);
        $this->view->runID          = $from == 'testcase' ? 0 : $run->id;

        $this->display();
    }

    /**
     * Edit a case.
     * 
     * @param  int   $caseID 
     * @access public
     * @return void
     */
    public function edit($caseID, $comment = false)
    {
        $this->loadModel('story');

        if(!empty($_POST))
        {
            $changes = array();
            $files   = array();
            if($comment == false)
            {
                $changes = $this->testcase->update($caseID);
                if(dao::isError()) die(js::error(dao::getError()));
                $files = $this->loadModel('file')->saveUpload('testcase', $caseID);
            }
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $this->loadModel('action');
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
                $actionID = $this->action->create('case', $caseID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('testcase', 'view', "caseID=$caseID"), 'parent'));
        }

        $case = $this->testcase->getById($caseID);
        if(empty($case->steps))
        {
            $step = new stdclass();
            $step->desc   = '';
            $step->expect = '';
            $case->steps[] = $step;
        }
        $productID       = $case->product;
        $currentModuleID = $case->module;
        $title           = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->edit;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->testcase->common;
        $position[]      = $this->lang->testcase->edit;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $productID, $case->branch);

        $this->view->title            = $title;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->branches         = $this->session->currentProductType == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $case->branch);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->users            = $this->user->getPairs('noletter');
        $this->view->stories          = $this->story->getProductStoryPairs($productID, $case->branch);
        $this->view->case             = $case;
        $this->view->actions          = $this->loadModel('action')->getList('case', $caseID);

        $this->display();
    }

    /**
     * Batch edit case.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $branch = 0)
    {
        if($this->post->titles)
        {
            $allChanges = $this->testcase->batchUpdate();
            if($allChanges)
            {
                foreach($allChanges as $caseID => $changes )
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }

            die(js::locate($this->session->caseList, 'parent'));
        }

        $caseIDList = $this->post->caseIDList ? $this->post->caseIDList : die(js::locate($this->session->caseList));

        /* Get the edited cases. */
        $cases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($caseIDList)->fetchAll('id');

        /* The cases of a product. */
        if($productID)
        {
            $product = $this->product->getByID($productID);
            $this->testcase->setMenu($this->products, $productID, $branch);

            /* Set modules. */
            $modules = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0, $branch);
            $modules = array('ditto' => $this->lang->testcase->ditto) + $modules;

            $this->view->modules    = $modules;
            $this->view->position[] = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
            $this->view->title      = $product->name . $this->lang->colon . $this->lang->testcase->batchEdit;
        }
        /* The cases of my. */
        else
        {
            $this->lang->testcase->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.testcase', 'my');
            $this->lang->testcase->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->position[] = html::a($this->server->http_referer, $this->lang->my->testCase);
            $this->view->title      = $this->lang->testcase->batchEdit;
        }
        
        /* Judge whether the editedTasks is too large and set session. */
        $showSuhosinInfo = false;
        $showSuhosinInfo = $this->loadModel('common')->judgeSuhosinSetting(count($cases), count(explode(',', $this->config->testcase->custom->batchEditFields)) + 3);
        $this->app->session->set('showSuhosinInfo', $showSuhosinInfo);
        if($showSuhosinInfo) $this->view->suhosinInfo = $this->lang->suhosinInfo;

        /* Set custom. */
        foreach(explode(',', $this->config->testcase->customBatchEditFields) as $field) $customFields[$field] = $this->lang->testcase->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->testcase->custom->batchEditFields;

        /* Assign. */
        $this->view->position[] = $this->lang->testcase->common;
        $this->view->position[] = $this->lang->testcase->batchEdit;
        $this->view->caseIDList = $caseIDList;
        $this->view->productID  = $productID;
        $this->view->priList    = array('ditto' => $this->lang->testcase->ditto) + $this->lang->testcase->priList;
        $this->view->typeList   = array('' => '', 'ditto' => $this->lang->testcase->ditto) + $this->lang->testcase->typeList;
        $this->view->cases      = $cases;

        $this->display();
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
            die(js::confirm($this->lang->testcase->confirmDelete, inlink('delete', "caseID=$caseID&confirm=yes")));
        }
        else
        {
            $this->testcase->delete(TABLE_CASE, $caseID);

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
                $this->send($response);
            }
            die(js::locate($this->session->caseList, 'parent'));
        }
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
        if($this->post->caseIDList)
        {
            $caseIDList = $this->post->caseIDList;
            unset($_POST['caseIDList']);
            $allChanges = $this->testcase->batchChangeModule($caseIDList, $moduleID);
            if(dao::isError()) die(js::error(dao::getError()));
            foreach($allChanges as $caseID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('case', $caseID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        die(js::locate($this->session->caseList, 'parent'));
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
        $caseIDList = $this->post->caseIDList ? $this->post->caseIDList : die(js::locate($this->session->caseList));

        foreach($caseIDList as $caseID) $this->testcase->delete(TABLE_CASE, $caseID);
        die(js::locate($this->session->caseList));
    }

    /**
     * Link related cases.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function linkCases($caseID, $browseType = '', $param = 0)
    {
        /* Link cases. */
        if(!empty($_POST))
        {
            $this->testcase->linkCases($caseID);
            if(isonlybody()) die(js::closeModal('parent.parent', '', "function(){parent.parent.loadLinkCases('$caseID')}"));
            die(js::locate($this->createLink('testcase', 'edit', "caseID=$caseID"), 'parent'));
        }

        /* Get case and queryID. */
        $case    = $this->testcase->getById($caseID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $case->product, $case->branch);

        /* Build the search form. */
        $actionURL = $this->createLink('testcase', 'linkCases', "caseID=$caseID&browseType=bySearch&queryID=myQueryID", '', true);
        $this->testcase->buildSearchForm($case->product, $this->products, $queryID, $actionURL);

        /* Get cases to link. */
        $cases2Link = $this->testcase->getCases2Link($caseID, $browseType, $queryID);

        /* Assign. */
        $this->view->title      = $case->title . $this->lang->colon . $this->lang->testcase->linkCases;
        $this->view->position[] = html::a($this->createLink('product', 'view', "productID=$case->product"), $this->products[$case->product]);
        $this->view->position[] = html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case->title);
        $this->view->position[] = $this->lang->testcase->linkCases;
        $this->view->case       = $case;
        $this->view->cases2Link = $cases2Link;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * AJAX: get linkCases.
     *
     * @param  int    $caseID
     * @access public
     * @return string
     */
    public function ajaxGetLinkCases($caseID)
    {
        /* Get linkCases. */
        $cases = $this->testcase->getLinkCases($caseID);

        /* Build linkCase list. */
        $output = '';
        foreach($cases as $caseId => $caseTitle)
        {
            $output .= '<li>';
            $output .= html::a(inlink('view', "caseID=$caseId"), "#$caseId " . $caseTitle, '_blank');
            $output .= html::a("javascript:unlinkCase($caseID, $caseId)", '<i class="icon-remove"></i>', '', "title='{$this->lang->unlink}' style='float:right'");
            $output .= '</li>';
        }

        die($output);
    }

    /**
     * Unlink related case.
     *
     * @param  int    $caseID
     * @param  int    $case2Unlink
     * @access public
     * @return string
     */
    public function unlinkCase($caseID, $case2Unlink = 0)
    {
        /* Unlink related case. */
        $this->testcase->unlinkCase($caseID, $case2Unlink);

        die('success');
    }

    /**
     * Confirm testcase changed. 
     * 
     * @param  int    $caseID 
     * @access public
     * @return void
     */
    public function confirmChange($caseID)
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_TESTRUN)->set('version')->eq($case->version)->where('`case`')->eq($caseID)->exec();
        die(js::locate(inLink('view', "caseID=$caseID"), 'parent'));
    }

    /**
     * Confirm story changes.
     * 
     * @param  int   $caseID 
     * @access public
     * @return void
     */
    public function confirmStoryChange($caseID)
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_CASE)->set('storyVersion')->eq($case->latestStoryVersion)->where('id')->eq($caseID)->exec();
        $this->loadModel('action')->create('case', $caseID, 'confirmed', '', $case->latestStoryVersion);
        die(js::reload('parent'));
    }

    /**
     * export 
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $taskID = 0)
    {
        if($_POST)
        {
            $caseLang   = $this->lang->testcase;
            $caseConfig = $this->config->testcase;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $caseConfig->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($caseLang->$fieldName) ? $caseLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get cases. */
            if($this->session->testcaseOnlyCondition)
            {
                if($taskID)
                {
                    $caseIDList = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs();
                    $cases = $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQueryCondition)->andWhere('id')->in($caseIDList)
                        ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                        ->orderBy($orderBy)->fetchAll('id');
                }
                else
                {
                    $cases = $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQueryCondition)
                        ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                        ->orderBy($orderBy)->fetchAll('id');
                }
            }
            else
            {
                $cases   = array();
                $orderBy = " ORDER BY " . str_replace(array('|', '^A', '_'), ' ', $orderBy);
                $stmt    = $this->dbh->query($this->session->testcaseQueryCondition . $orderBy);
                while($row = $stmt->fetch())
                {
                    $caseID = isset($row->case) ? $row->case : $row->id;
                    if($this->post->exportType == 'selected' and strpos(",{$this->cookie->checkedItem},", ",$caseID,") === false) continue;
                    $cases[$caseID] = $row;
                    $row->id        = $caseID;
                }
            }

            /* Get users, products and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $products = $this->loadModel('product')->getPairs('nocode');

            /* Get related objects id lists. */
            $relatedModuleIdList = array();
            $relatedStoryIdList  = array();
            $relatedCaseIdList   = array();

            foreach($cases as $case)
            {
                $case->title = htmlspecialchars_decode($case->title);

                $relatedModuleIdList[$case->module] = $case->module;
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
            $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedCases   = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedSteps   = $this->dao->select('`case`, version, `desc`, expect')->from(TABLE_CASESTEP)->where('`case`')->in(@array_keys($cases))->orderBy('version desc,id')->fetchGroup('case');
            $relatedModules = array('0' => '/') + $relatedModules;

            foreach($cases as $case)
            {
                $case->stepDesc   = '';
                $case->stepExpect = '';
                if(isset($relatedSteps[$case->id]))
                {
                    $i = 1;
                    foreach($relatedSteps[$case->id] as $step)
                    {
                        if($step->version != $case->version) continue;
                        $sign = (in_array($this->post->fileType, array('html', 'xml'))) ? '<br />' : "\n";
                        $case->stepDesc   .= $i . ". " . htmlspecialchars_decode($step->desc) . $sign;
                        $case->stepExpect .= $i . ". " . htmlspecialchars_decode($step->expect) . $sign;
                        $i ++;
                    }
                }

                if($this->post->fileType == 'csv')
                {
                    $case->stepDesc   = str_replace('"', '""', $case->stepDesc);
                    $case->stepExpect = str_replace('"', '""', $case->stepExpect);
                }

                /* fill some field with useful value. */
                if(isset($products[$case->product]))      $case->product = $products[$case->product] . "(#$case->product)";
                if(isset($relatedModules[$case->module])) $case->module  = $relatedModules[$case->module] . "(#$case->module)";
                if(isset($relatedStories[$case->story]))  $case->story   = $relatedStories[$case->story] . "(#$case->story)";

                if(isset($caseLang->priList[$case->pri]))              $case->pri           = $caseLang->priList[$case->pri];
                if(isset($caseLang->typeList[$case->type]))            $case->type          = $caseLang->typeList[$case->type];
                if(isset($caseLang->statusList[$case->status]))        $case->status        = $caseLang->statusList[$case->status];
                if(isset($users[$case->openedBy]))                     $case->openedBy      = $users[$case->openedBy];
                if(isset($users[$case->lastEditedBy]))                 $case->lastEditedBy  = $users[$case->lastEditedBy];
                if(isset($caseLang->resultList[$case->lastRunResult])) $case->lastRunResult = $caseLang->resultList[$case->lastRunResult];

                $case->stage = explode(',', $case->stage);
                foreach($case->stage as $key => $stage) $case->stage[$key] = isset($caseLang->stageList[$stage]) ? $caseLang->stageList[$stage] : $stage;
                $case->stage = join("\n", $case->stage);

                $case->openedDate     = substr($case->openedDate, 0, 10);
                $case->lastEditedDate = substr($case->lastEditedDate, 0, 10);

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
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $cases);
            $this->post->set('kind', 'testcase');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->view->allExportFields = $this->config->testcase->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * Export templet 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function exportTemplet($productID)
    {
        if($_POST)
        {
            $fields['module']       = $this->lang->testcase->module;
            $fields['title']        = $this->lang->testcase->title;
            $fields['stepDesc']     = $this->lang->testcase->stepDesc;
            $fields['stepExpect']   = $this->lang->testcase->stepExpect;
            $fields['keywords']     = $this->lang->testcase->keywords;
            $fields['type']         = $this->lang->testcase->type;
            $fields['pri']          = $this->lang->testcase->pri;
            $fields['status']       = $this->lang->testcase->status;
            $fields['stage']        = $this->lang->testcase->stage;
            $fields['precondition'] = $this->lang->testcase->precondition;

            $fields[''] = '';
            $fields['typeValue']   = $this->lang->testcase->lblTypeValue;
            $fields['stageValue']  = $this->lang->testcase->lblStageValue;
            $fields['statusValue'] = $this->lang->testcase->lblStatusValue;

            $modules = $this->loadModel('tree')->getOptionMenu($productID, 'case');
            $rows    = array();
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
                    $row->statusValue = join("\n", $this->lang->testcase->statusList);
                }
                $rows[] = $row;
            }

            $this->post->set('fields', $fields);
            $this->post->set('kind', 'testcase');
            $this->post->set('rows', $rows);
            $this->post->set('extraNum',   $this->post->num);
            $this->post->set('fileName', 'templet');
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

            move_uploaded_file($file['tmpname'], $this->file->savePath . $file['pathname']);

            $fileName = $this->file->savePath . $file['pathname'];
            $rows     = $this->file->parseCSV($fileName);
            $fields   = $this->testcase->getImportFields();
            $fields   = array_flip($fields);
            $header   = array();
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

            if(count($columnKey) != count($header) or $this->post->encode != 'utf-8')
            {
                $fc     = file_get_contents($fileName);
                $encode = $this->post->encode != "utf-8" ? $this->post->encode : 'gbk';
                if(function_exists('mb_convert_encoding'))
                {
                    $fc = @mb_convert_encoding($fc, 'utf-8', $encode);
                }
                elseif(function_exists('iconv'))
                {
                    $fc = @iconv($encode, 'utf-8', $fc);
                }
                else
                {
                    die(js::alert($this->lang->testcase->noFunction));
                }
                file_put_contents($fileName, $fc);

                $rows      = $this->file->parseCSV($fileName);
                $columnKey = array();
                $header   = array();
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
                if(count($columnKey) != count($header)) die(js::alert($this->lang->testcase->errorEncode));
            }

            $this->session->set('importFile', $fileName);

            die(js::locate(inlink('showImport', "productID=$productID&branch=$branch"), 'parent.parent'));
        }
        $this->display();
    }

    /**
     * Show import data
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = 0)
    {
        if($_POST)
        {
            $this->testcase->createFromImport($productID, (int)$branch);
            die(js::locate(inlink('browse', "productID=$productID"), 'parent'));
        }

        $this->testcase->setMenu($this->products, $productID, $branch);

        $file       = $this->session->importFile;
        $caseLang   = $this->lang->testcase;
        $caseConfig = $this->config->testcase;
        $modules    = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, $branch);
        $stories    = $this->loadModel('story')->getProductStoryPairs($productID, $branch);
        $fields     = $this->testcase->getImportFields();
        $fields     = array_flip($fields);

        $rows   = $this->loadModel('file')->parseCSV($file);
        $header   = array();
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

        $endField = end($fields);
        $caseData = array();
        $stepData = array();
        foreach($rows as $row => $data)
        {
            $case = new stdclass();
            foreach($columnKey as $key => $field)
            {
                if(!isset($data[$key])) continue;
                $cellValue = $data[$key];
                if($field == 'story')
                {
                    $case->$field = 0;
                    if(strrpos($cellValue, '(#') !== false)
                    {
                        $id = trim(substr($cellValue, strrpos($cellValue,'(#') + 2), ')');
                        $case->$field = $id;
                    }   
                }
                elseif($field == 'module')
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
                    $steps    = explode("\n", $cellValue);
                    $stepKey  = str_replace('step', '', strtolower($field));
                    $caseStep = array();

                    foreach($steps as $step)
                    {
                        $step = trim($step);
                        if(empty($step)) continue;
                        if(preg_match('/^([0-9]+)([.、]{1})/U', $step, $out))
                        {
                            $num     = $out[1];
                            $sign    = $out[2];
                            $signbit = $sign == '.' ? 1 : 3;
                            $step    = trim(substr($step, strpos($step, $sign) + $signbit));
                            if(!empty($step)) $caseStep[$num] = $step;
                        }
                        elseif(isset($num))
                        {
                            $caseStep[$num] .= "\n" . $step;
                        }
                        else
                        {
                            if($field == 'stepDesc')
                            {
                                $num = 1;
                                $caseStep[$num] = $step;
                            }
                            if($field == 'stepExpect' and isset($stepData[$row]['desc']))
                            {
                                end($stepData[$row]['desc']);
                                $num = key($stepData[$row]['desc']);
                                $caseStep[$num] = $step;
                            }
                        }
                    }
                    unset($num);
                    unset($sign);
                    $stepData[$row][$stepKey] = $caseStep;
                }
            }

            $caseData[$row] = $case;
            unset($case);
        }

        if(empty($caseData))
        {
            echo js::alert($this->lang->error->noData);
            die(js::locate($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch")));
        }

        $this->view->title      = $this->lang->testcase->common . $this->lang->colon . $this->lang->testcase->showImport;
        $this->view->position[] = $this->lang->testcase->showImport;

        $this->view->stories   = $stories;
        $this->view->modules   = $modules;
        $this->view->cases     = $this->dao->select('id, module, story, stage, status, pri, type')->from(TABLE_CASE)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetchAll('id');
        $this->view->caseData  = $caseData;
        $this->view->stepData  = $stepData;
        $this->view->productID = $productID;
        $this->view->branch    = $branch;
        $this->view->product   = $this->products[$productID];
        $this->display();
    }
}
