<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class bug extends control
{
    public $products = array();

    /**
     * Construct function, load some modules auto.
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
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('task');
        $this->view->products = $this->products = $this->product->getPairs('nocode');

        if($this->config->global->flow == 'onlyTest')
        {
            $this->config->bug->list->customCreateFields      = str_replace(array('project,', 'story,', 'task,'), '', $this->config->bug->list->customCreateFields);
            $this->config->bug->list->customBatchCreateFields = str_replace('project,', '', $this->config->bug->list->customBatchCreateFields);

            $this->config->bug->custom->batchCreateFields = str_replace('project,', '', $this->config->bug->custom->batchCreateFields);
        }

        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', "fromModule=bug")));
    }

    /**
     * The index page, locate to browse.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('bug', 'browse'));
    }

    /**
     * Browse bugs.
     * 
     * @param  int    $productID 
     * @param  string $branch
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
        $this->loadModel('datatable');

        /* Set browse type. */
        $browseType = strtolower($browseType);

        /* Set productID, moduleID, queryID and branch. */
        $productID = $this->product->saveState($productID, $this->products);
        $branch    = ($branch == '') ? (int)$this->cookie->preBranch  : (int)$branch;
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot);
        setcookie('preBranch', (int)$branch, $this->config->cookieLife, $this->config->webRoot);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch)
        {
            $_COOKIE['bugModule'] = 0;
            setcookie('bugModule', 0, $this->config->cookieLife, $this->config->webRoot);
        }
        if($browseType == 'bymodule') setcookie('bugModule', (int)$param, $this->config->cookieLife, $this->config->webRoot);
        if($browseType != 'bymodule') $this->session->set('bugBrowseType', $browseType);

        $moduleID  = ($browseType == 'bymodule') ? (int)$param : ($browseType == 'bysearch' ? 0 : ($this->cookie->bugModule ? $this->cookie->bugModule : 0));
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu and save session. */
        $this->bug->setMenu($this->products, $productID, $branch, $moduleID);
        $this->session->set('bugList', $this->app->getURI(true));

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->qaBugOrder ? $this->cookie->qaBugOrder : 'id_desc';
        setcookie('qaBugOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get projects. */
        $projects = $this->loadModel('project')->getPairs('empty|withdelete');

        /* Get bugs. */
        $bugs = $this->bug->getBugs($productID, $projects, $branch, $browseType, $moduleID, $queryID, $sort, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', $browseType == 'needconfirm' ? false : true);

        /* Process bug for check story changed. */
        $bugs = $this->loadModel('story')->checkNeedConfirm($bugs);

        /* Process the openedBuild and resolvedBuild fields. */
        $bugs = $this->bug->processBuildForBugs($bugs);

        /* Build the search form. */
        $actionURL = $this->createLink('bug', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['onMenuBar'] = 'yes';
        $this->bug->buildSearchForm($productID, $this->products, $queryID, $actionURL);

        $showModule = !empty($this->config->datatable->bugBrowse->showModule) ? $this->config->datatable->bugBrowse->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();

        /* Set view. */
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->common;
        $this->view->productID     = $productID;
        $this->view->product       = $this->product->getById($productID);
        $this->view->productName   = $this->products[$productID];
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->modules       = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch);
        $this->view->moduleTree    = $this->tree->getTreeMenu($productID, $viewType = 'bug', $startModuleID = 0, array('treeModel', 'createBugLink'), '', $branch);
        $this->view->moduleName    = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->browseType    = $browseType;
        $this->view->bugs          = $bugs;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->pager         = $pager;
        $this->view->param         = $param;
        $this->view->orderBy       = $orderBy;
        $this->view->moduleID      = $moduleID;
        $this->view->memberPairs   = $this->user->getPairs('noletter|nodeleted');
        $this->view->branch        = $branch;
        $this->view->branches      = $this->loadModel('branch')->getPairs($productID);
        $this->view->setShowModule = true;

        $this->display();
    }

    /**
     * The report page.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  int    $branchID
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function report($productID, $browseType, $branchID, $moduleID)
    {
        $this->loadModel('report');
        $this->view->charts   = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->bug->$chartFunc();
                $chartOption = $this->lang->bug->report->$chart;
                $this->bug->mergeChartOption($chart);

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $this->bug->setMenu($this->products, $productID, $branchID);
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common . $this->lang->colon . $this->lang->bug->reportChart;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->reportChart;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->moduleID      = $moduleID;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * Create a bug.
     * 
     * @param  int    $productID 
     * @param  string $extras       others params, forexample, projectID=10,moduleID=10
     * @access public
     * @return void
     */
    public function create($productID, $branch = '', $extras = '')
    {
        $this->view->users = $this->user->getPairs('nodeleted|devfirst|noclosed');
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));
        $this->app->loadLang('release');

        if(!empty($_POST) and !isset($_POST['stepIDList']))
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $bugResult = $this->bug->create();
            if(!$bugResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $bugID = $bugResult['id'];
            if($bugResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->bug->common);
                $response['locate']  = $this->createLink('bug', 'view', "bugID=$bugID");
                $this->send($response);
            }

            $actionID = $this->action->create('bug', $bugID, 'Opened');
            $this->bug->sendmail($bugID, $actionID);

            $location = $this->createLink('bug', 'browse', "productID={$this->post->product}&branch=$branch&type=byModule&param={$this->post->module}");
            $response['locate'] = isset($_SESSION['bugList']) ? $this->session->bugList : $location;
            $this->send($response);
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $branches  = $this->session->currentProductType == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);
        $this->bug->setMenu($this->products, $productID, $branch);

        /* Init vars. */
        $moduleID   = 0;
        $projectID  = 0;
        $taskID     = 0;
        $storyID    = 0;
        $buildID    = 0;
        $caseID     = 0;
        $runID      = 0;
        $testtask   = 0;
        $version    = 0;
        $title      = '';
        $steps      = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $os         = '';
        $browser    = '';
        $assignedTo = '';
        $deadline   = '';
        $mailto     = '';
        $keywords   = '';
        $severity   = 3;
        $type       = 'codeerror';

        /* Parse the extras. */
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras);

        if($runID and $resultID) extract($this->bug->getBugInfoFromResult($resultID));// If set runID and resultID, get the result info by resultID as template.
        if(!$runID and $caseID)  extract($this->bug->getBugInfoFromResult($resultID, $caseID, $version));// If not set runID but set caseID, get the result info by resultID and case info.

        /* If bugID setted, use this bug as template. */
        if(isset($bugID)) 
        {
            $bug = $this->bug->getById($bugID);
            extract((array)$bug);
            $projectID  = $bug->project;
            $moduleID   = $bug->module;
            $taskID     = $bug->task;
            $storyID    = $bug->story;
            $buildID    = $bug->openedBuild;
            $severity   = $bug->severity;
            $type       = $bug->type;
            $assignedTo = $bug->assignedTo;
            $deadline   = $bug->deadline;
        }

        /* If projectID is setted, get builds and stories of this project. */
        if($projectID)
        {
            $builds  = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, $branch, 'noempty,noterminate,nodone');
            $stories = $this->story->getProjectStoryPairs($projectID);
        }
        else
        {
            $builds  = $this->loadModel('build')->getProductBuildPairs($productID, $branch, 'noempty,noterminate,nodone');
            $stories = $this->story->getProductStoryPairs($productID, $branch);
        }

        /* Set team members of the latest project as assignedTo list. */
        $latestProject = $this->product->getLatestProject($productID);
        if(!empty($latestProject)) 
        {
            $projectMembers = $this->loadModel('project')->getTeamMemberPairs($latestProject->id, 'nodeleted');
        }
        else
        {
            $projectMembers = $this->view->users; 
        }

        /* Set custom. */
        foreach(explode(',', $this->config->bug->list->customCreateFields) as $field) $customFields[$field] = $this->lang->bug->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->bug->custom->createFields;

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->create;

        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch);
        $this->view->stories          = $stories;
        $this->view->projects         = $this->product->getProjectPairs($productID, $branch ? "0,$branch" : 0, $params = 'nodeleted');
        $this->view->builds           = $builds;
        $this->view->moduleID         = $moduleID;
        $this->view->projectID        = $projectID;
        $this->view->taskID           = $taskID;
        $this->view->storyID          = $storyID;
        $this->view->buildID          = $buildID;
        $this->view->caseID           = $caseID;
        $this->view->runID            = $runID;
        $this->view->version          = $version;
        $this->view->testtask         = $testtask;
        $this->view->bugTitle         = $title;
        $this->view->steps            = htmlspecialchars($steps);
        $this->view->os               = $os;
        $this->view->browser          = $browser;
        $this->view->projectMembers   = $projectMembers;
        $this->view->assignedTo       = $assignedTo;
        $this->view->deadline         = $deadline;
        $this->view->mailto           = $mailto;
        $this->view->keywords         = $keywords;
        $this->view->severity         = $severity;
        $this->view->type             = $type;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;

        $this->display();
    }

    /**
     * Batch create. 
     * 
     * @param  int    $productID 
     * @param  int    $projectID 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = '', $projectID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $actions = $this->bug->batchCreate($productID, $branch);
            foreach($actions as $bugID => $actionID) $this->bug->sendmail($bugID, $actionID);
            die(js::locate($this->session->bugList, 'parent'));
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->bug->setMenu($this->products, $productID, $branch);

        /* If projectID is setted, get builds and stories of this project. */
        if($projectID)
        {
            $builds  = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, $branch, 'noempty');
            $stories = $this->story->getProjectStoryPairs($projectID);
        }
        else
        {
            $builds  = $this->loadModel('build')->getProductBuildPairs($productID, $branch, 'noempty');
            $stories = $this->story->getProductStoryPairs($productID, $branch);
        }

        if($this->session->bugImagesFile)
        {
            $files = $this->session->bugImagesFile;
            foreach($files as $fileName => $file)
            {
                $title = $file['title'];
                $titles[$title] = $fileName;
            }
            krsort($titles);
            $this->view->titles = $titles;
        }

        /* Set custom. */
        $product  = $this->product->getById($productID);
        foreach(explode(',', $this->config->bug->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->bug->$field;
        }
        $showFields = $this->config->bug->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->batchCreate;

        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->builds           = $builds;
        $this->view->users            = $this->user->getPairs('nodeleted,devfirst');
        $this->view->projects         = $this->product->getProjectPairs($productID, $branch ? "0,$branch" : 0, $params = 'nodeleted');
        $this->view->projectID        = $projectID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch);
        $this->view->moduleID         = $moduleID;
        $this->view->branch           = $branch;
        $this->view->branches         = $this->loadModel('branch')->getPairs($productID);
        $this->display();
    }

    /**
     * View a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function view($bugID)
    {
        /* Judge bug exits or not. */
        $bug = $this->bug->getById($bugID, true);
        if(!$bug) die(js::error($this->lang->notFound) . js::locate('back'));

        if($bug->project and !$this->loadModel('project')->checkPriv($this->project->getByID($bug->project)))
        {
            echo(js::alert($this->lang->project->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
            die(js::locate('back'));
        }

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        /* Set menu. */
        $this->bug->setMenu($this->products, $bug->product, $bug->branch);

        /* Get product info. */
        $productID   = $bug->product;
        $productName = $this->products[$productID];
      
        /* Header and positon. */
        $this->view->title      = "BUG #$bug->id $bug->title - " . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $this->view->position[] = $this->lang->bug->view;

        /* Assign. */
        $this->view->productID   = $productID;
        $this->view->productName = $productName;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bug         = $bug;
        $this->view->branchName  = $this->session->currentProductType == 'normal' ? '' : $this->loadModel('branch')->getById($bug->branch, $bug->product);
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getProductBuildPairs($productID, $branch = 0, $params = '');
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('bug', $bugID);

        $this->display();
    }

    /**
     * Edit a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function edit($bugID, $comment = false)
    {
        if(!empty($_POST))
        {
            $changes = array();
            $files   = array();
            if($comment == false)
            {
                $changes  = $this->bug->update($bugID);
                if(dao::isError()) die(js::error(dao::getError()));
                $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            }
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('bug', $bugID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->bug->sendmail($bugID, $actionID);
            }

            $bug = $this->bug->getById($bugID);
            if($bug->toTask != 0) 
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status') 
                    {
                        $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                        $cancelURL  = $this->server->HTTP_REFERER;
                        die(js::confirm(sprintf($this->lang->bug->remindTask, $bug->Task), $confirmURL, $cancelURL, 'parent', 'parent'));
                    }
                }
            } 
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* Get the info of bug, current product and modue. */
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $projectID       = $bug->project;
        $currentModuleID = $bug->module;

        /* Set the menu. */
        $this->bug->setMenu($this->products, $productID, $bug->branch);

        /* Set header and position. */
        $this->view->title      = $this->lang->bug->edit . "BUG #$bug->id $bug->title - " . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->edit;

        /* Assign. */
        $allBuilds = $this->loadModel('build')->getProductBuildPairs($productID, $branch = 0, 'noempty');
        if($projectID)
        {
            $openedBuilds = $this->build->getProjectBuildPairs($projectID, $productID, $bug->branch, 'noempty,noterminate,nodone');
        }
        else
        {
            $openedBuilds = $this->build->getProductBuildPairs($productID, $bug->branch, 'noempty,noterminate,nodone');
        }

        /* Set the openedBuilds list. */
        $oldOpenedBuilds = array();
        $bugOpenedBuilds = explode(',', $bug->openedBuild);
        foreach($bugOpenedBuilds as $buildID)
        {
            if(isset($allBuilds[$buildID])) $oldOpenedBuilds[$buildID] = $allBuilds[$buildID];
        }
        $openedBuilds = $openedBuilds + $oldOpenedBuilds;

        /* Set the resolvedBuilds list. */
        $oldResolvedBuild = array();
        if(($bug->resolvedBuild) and isset($allBuilds[$bug->resolvedBuild])) $oldResolvedBuild[$bug->resolvedBuild] = $allBuilds[$bug->resolvedBuild];

        $this->view->bug              = $bug;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID, $bug->branch);
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $bug->branch);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->projects         = $this->product->getProjectPairs($bug->product, $bug->branch ? "0,{$bug->branch}" : 0, 'nodeleted');
        $this->view->stories          = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product, $bug->branch);
        $this->view->branches         = $this->session->currentProductType == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);
        $this->view->tasks            = $this->task->getProjectTaskPairs($bug->project);
        $this->view->users            = $this->user->getPairs('nodeleted', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->openedBuilds     = $openedBuilds;
        $this->view->resolvedBuilds   = array('' => '') + $openedBuilds + $oldResolvedBuild;
        $this->view->actions          = $this->action->getList('bug', $bugID);
        $this->view->templates        = $this->bug->getUserBugTemplates($this->app->user->account);

        $this->display();
    }

    /**
     * Batch edit bug.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $branch = 0)
    {
        if($this->post->titles)
        {
            $allChanges = $this->bug->batchUpdate();

            foreach($allChanges as $bugID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
                $this->bug->sendmail($bugID, $actionID);

                $bug = $this->bug->getById($bugID);
                if($bug->toTask != 0) 
                {
                    foreach($changes as $change)
                    {
                        if($change['field'] == 'status') 
                        {
                            $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                            $cancelURL  = $this->server->HTTP_REFERER;
                            die(js::confirm(sprintf($this->lang->bug->remindTask, $bug->task), $confirmURL, $cancelURL, 'parent', 'parent'));
                        }
                    }
                } 
            }
            die(js::locate($this->session->bugList, 'parent'));
        }

        $bugIDList = $this->post->bugIDList ? $this->post->bugIDList : die(js::locate($this->session->bugList, 'parent'));
        /* Initialize vars.*/
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        /* The bugs of a product. */
        if($productID)
        {
            $product = $this->product->getByID($productID);
            $branchProduct = $product->type == 'normal' ? false : true;

            /* Set plans. */
            $plans          = $this->loadModel('productplan')->getPairs($productID, $branch);
            $plans = array('' => '', 'ditto' => $this->lang->bug->ditto) + $plans;

            /* Set product menu. */
            $this->bug->setMenu($this->products, $productID, $branch);
            $this->view->title      = $product->name . $this->lang->colon . "BUG" . $this->lang->bug->batchEdit;
            $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
            $this->view->plans      = $plans;
            $this->view->branches   = $product->type == 'normal' ? array() : array('' => '', 'ditto' => $this->lang->bug->ditto) + $this->loadModel('branch')->getPairs($product->id);
        }
        /* The bugs of my. */
        else
        {
            $branchProduct = false;
            $productIdList = array();
            foreach($bugs as $bug) $productIdList[$bug->product] = $bug->product;
            $products = $this->product->getByIdList($productIdList);
            foreach($products as $product)
            {
                if($product->type != 'normal')
                {
                    $branchProduct = true;
                    break;
                }
            }

            $this->lang->bug->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.bug', 'my');
            $this->lang->bug->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->position[] = html::a($this->createLink('my', 'bug'), $this->lang->my->bug);
            $this->view->title      = "BUG" . $this->lang->bug->batchEdit;
        }

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($bugs) * (count(explode(',', $this->config->bug->custom->batchEditFields)) + 2);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom*/
        foreach(explode(',', $this->config->bug->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->bug->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->bug->custom->batchEditFields;

        /* Set users. */
        $users = $this->user->getPairs('nodeleted,devfirst');
        $users = array('' => '', 'ditto' => $this->lang->bug->ditto) + $users;

        /* Assign. */
        $this->view->position[]     = $this->lang->bug->common;
        $this->view->position[]     = $this->lang->bug->batchEdit;
        $this->view->bugIDList      = $bugIDList;
        $this->view->productID      = $productID;
        $this->view->branchProduct  = $branchProduct;
        $this->view->severityList   = array('ditto' => $this->lang->bug->ditto) + $this->lang->bug->severityList;
        $this->view->typeList       = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->typeList;
        $this->view->priList        = array('0' => '', 'ditto' => $this->lang->bug->ditto) + $this->lang->bug->priList;
        $this->view->resolutionList = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->resolutionList;
        $this->view->statusList     = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->statusList;
        $this->view->osList         = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->osList;
        $this->view->browserList    = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->browserList;
        $this->view->bugs           = $bugs;
        $this->view->branch         = $branch;
        $this->view->users          = $users;

        $this->display();
    }

    /**
     * Update assign of bug. 
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function assignTo($bugID)
    {
        $bug = $this->bug->getById($bugID);

        /* Set menu. */
        $this->bug->setMenu($this->products, $bug->product, $bug->branch);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->bug->assign($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);
            $this->bug->sendmail($bugID, $actionID);

            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $this->view->title      = $this->products[$bug->product] . $this->lang->colon . $this->lang->bug->assignedTo;
        $this->view->position[] = $this->lang->bug->assignedTo;

        $this->view->users   = $this->user->getPairs('nodeleted', $bug->assignedTo);
        $this->view->bug     = $bug;
        $this->view->bugID   = $bugID;
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Batch change the module of bug.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            unset($_POST['bugIDList']);
            $allChanges = $this->bug->batchChangeModule($bugIDList, $moduleID);
            if(dao::isError()) die(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
                $this->bug->sendmail($bugID, $actionID);
            }
        }
        die(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch update assign of bug. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function batchAssignTo($projectID, $type = 'project')
    {
        if(!empty($_POST) && isset($_POST['bugIDList']))
        {
            $bugIDList = $this->post->bugIDList;
            unset($_POST['bugIDList']);
            foreach($bugIDList as $bugID)
            {
                $this->loadModel('action');
                $changes = $this->bug->assign($bugID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
                $this->bug->sendmail($bugID, $actionID);
            }
        }
        if($type == 'product') die(js::locate($this->createLink('bug', 'browse', "productID=$projectID")));
        if($type == 'my')      die(js::locate($this->createLink('my', 'bug')));
        die(js::locate($this->createLink('project', 'bug', "projectID=$projectID")));
    }

    /**
     * confirm a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function confirmBug($bugID)
    {
        if(!empty($_POST))
        {
            $changes = $this->bug->confirm($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'bugConfirmed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
            $this->bug->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $this->bug->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->confirmBug;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->confirmBug;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted', $bug->assignedTo);
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Batch confirm bugs. 
     * 
     * @access public
     * @return void
     */
    public function batchConfirm()
    {
        $bugIDList  = $this->post->bugIDList ? $this->post->bugIDList : die(js::locate($this->session->bugList, 'parent'));
        $this->bug->batchConfirm($bugIDList);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($bugIDList as $bugID)
        {
            $actionID = $this->action->create('bug', $bugID, 'bugConfirmed');
            $this->bug->sendmail($bugID, $actionID);
        }
        die(js::locate($this->session->bugList, 'parent'));
    }
    
    /**
     * Resolve a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function resolve($bugID)
    {
        if(!empty($_POST))
        {
            $this->bug->resolve($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);

            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $fileAction . $this->post->comment, $this->post->resolution . ($this->post->duplicateBug ? ':' . (int)$this->post->duplicateBug : ''));
            $this->bug->sendmail($bugID, $actionID);

            $bug = $this->bug->getById($bugID);
            if($bug->toTask != 0) 
            {
                /* If task is not finished, update it's status. */
                $task = $this->task->getById($bug->toTask);
                if($task->status != 'done')
                {
                    $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                    unset($_GET['onlybody']);
                    $cancelURL  = $this->createLink('bug', 'view', "bugID=$bugID");
                    die(js::confirm(sprintf($this->lang->bug->remindTask, $bug->toTask), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                }
            } 
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $users      = $this->user->getPairs('nodeleted');
        $assignedTo = $bug->openedBy;
        if(!isset($users[$assignedTo])) $assignedTo = $this->bug->getModuleOwner($bug->module, $productID);
        unset($this->lang->bug->resolutionList['tostory']);

        $this->bug->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->resolve;

        $this->view->bug        = $bug;
        $this->view->users      = $users;
        $this->view->assignedTo = $assignedTo;
        $this->view->projects   = $this->loadModel('product')->getProjectPairs($productID, $bug->branch ? "0,{$bug->branch}" : 0, $params = 'nodeleted');
        $this->view->builds     = $this->loadModel('build')->getProductBuildPairs($productID, $branch = 0, 'all');
        $this->view->actions    = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Batch resolve bugs.
     * 
     * @param  string    $resolution 
     * @param  string    $resolvedBuild 
     * @access public
     * @return void
     */
    public function batchResolve($resolution, $resolvedBuild = '')
    {
        $bugIDList = $this->post->bugIDList ? $this->post->bugIDList : die(js::locate($this->session->bugList, 'parent'));
        $bugIDList = $this->bug->batchResolve($bugIDList, $resolution, $resolvedBuild);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($bugIDList as $bugID)
        {
            $actionID = $this->action->create('bug', $bugID, 'Resolved', '', $resolution);
            $this->bug->sendmail($bugID, $actionID);
        }
        die(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Activate a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function activate($bugID)
    {
        if(!empty($_POST))
        {
            $this->bug->activate($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            $actionID = $this->action->create('bug', $bugID, 'Activated', $this->post->comment);
            $this->bug->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->activate;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted', $bug->resolvedBy);
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID, $bug->branch, 'noempty');
        $this->view->actions = $this->action->getList('bug', $bugID);

        $this->display();
    }

    /**
     * Close a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function close($bugID)
    {
        if(!empty($_POST))
        {
            $this->bug->close($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Closed', $this->post->comment);
            $this->bug->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->close;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->close;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Link related bugs.
     *
     * @param  int    $bugID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return void
     */
    public function linkBugs($bugID, $browseType = '', $param = 0)
    {
        /* Link bugs. */
        if(!empty($_POST))
        {
            $this->bug->linkBugs($bugID);
            if(isonlybody()) die(js::closeModal('parent.parent', '', "function(){parent.parent.loadLinkBugs('$bugID')}"));
            die(js::locate($this->createLink('bug', 'edit', "bugID=$bugID"), 'parent'));
        }

        /* Get bug and queryID. */
        $bug     = $this->bug->getById($bugID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Set the menu. */
        $this->bug->setMenu($this->products, $bug->product, $bug->branch);

        /* Build the search form. */
        $actionURL = $this->createLink('bug', 'linkBugs', "bugID=$bugID&browseType=bySearch&queryID=myQueryID", '', true);
        $this->bug->buildSearchForm($bug->product, $this->products, $queryID, $actionURL);

        /* Get bugs to link. */
        $bugs2Link = $this->bug->getBugs2Link($bugID, $browseType, $queryID);

        /* Assign. */
        $this->view->title      = $this->lang->bug->linkBugs . "BUG #$bug->id $bug->title - " . $this->products[$bug->product];
        $this->view->position[] = html::a($this->createLink('product', 'view', "productID=$bug->product"), $this->products[$bug->product]);
        $this->view->position[] = html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title);
        $this->view->position[] = $this->lang->bug->linkBugs;
        $this->view->bug        = $bug;
        $this->view->bugs2Link  = $bugs2Link;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * AJAX: get linkBugs.
     *
     * @param  int    $bugID
     * @access public
     * @return string
     */
    public function ajaxGetLinkBugs($bugID)
    {
        /* Get linkbugs. */
        $bugs = $this->bug->getLinkBugs($bugID);

        /* Build linkBug list.*/
        $output = '';
        foreach($bugs as $bugId => $bugTitle)
        {
            $output .= '<li>';
            $output .= html::a(inlink('view', "bugID=$bugId"), "#$bugId " . $bugTitle, '_blank');
            $output .= html::a("javascript:unlinkBug($bugID, $bugId)", '<i class="icon-remove"></i>', '', "title='{$this->lang->unlink}' style='float:right'");
            $output .= '</li>';
        }

        die($output);
    }

    /**
     * Unlink related bug.
     *
     * @param  int    $bugID
     * @param  int    $bug2Unlink
     * @access public
     * @return string
     */
    public function unlinkBug($bugID, $bug2Unlink = 0)
    {
        /* Unlink related bug. */
        $this->bug->unlinkBug($bugID, $bug2Unlink);

        die('success');
    }

    /**
     * Batch close bugs. 
     * 
     * @access public
     * @return void
     */
    public function batchClose()
    {
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;

            /* Reset $_POST. Do not unset that because the function of close need that in model. */
            $_POST = array();

            $bugs = $this->bug->getByList($bugIDList);
            foreach($bugs as $bugID => $bug)
            {
                if($bug->status != 'resolved')
                {
                    if($bug->status != 'closed') $skipBugs[$bugID] = $bugID;
                    continue;
                }

                $this->bug->close($bugID);

                $actionID = $this->action->create('bug', $bugID, 'Closed');
                $this->bug->sendmail($bugID, $actionID);
            }

            if(isset($skipBugs)) echo js::alert(sprintf($this->lang->bug->skipClose, join(',', $skipBugs)));
        }
        die(js::reload('parent'));
    }

    /**
     * Confirm story change.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function confirmStoryChange($bugID)
    {
        $bug = $this->bug->getById($bugID);
        $this->dao->update(TABLE_BUG)->set('storyVersion')->eq($bug->latestStoryVersion)->where('id')->eq($bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'confirmed', '', $bug->latestStoryVersion);
        die(js::reload('parent'));
    }

    /**
     * Delete a bug.
     * 
     * @param  int    $bugID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($bugID, $confirm = 'no')
    {
        $bug = $this->bug->getById($bugID);
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->bug->confirmDelete, inlink('delete', "bugID=$bugID&confirm=yes")));
        }
        else
        {
            $this->bug->delete(TABLE_BUG, $bugID);
            if($bug->toTask != 0) echo js::alert($this->lang->bug->remindTask . $bug->toTask);
            die(js::locate($this->session->bugList, 'parent'));
        }
    }

    /**
     * Save current template.
     * 
     * @access public
     * @return string
     */
    public function saveTemplate()
    {
        $this->bug->saveUserBugTemplate();
        if(dao::isError()) echo js::error(dao::getError(), $full = false);
        die($this->fetch('bug', 'buildTemplates'));
    }

    /**
     * Build the user templates selection code.
     * 
     * @access public
     * @return void
     */
    public function buildTemplates()
    {
        $this->view->templates = $this->bug->getUserBugTemplates($this->app->user->account);
        $this->display('bug', 'buildTemplates');
    }

    /**
     * Delete a user template.
     * 
     * @param  int    $templateID 
     * @access public
     * @return void
     */
    public function deleteTemplate($templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)->where('id')->eq($templateID)->andWhere('account')->eq($this->app->user->account)->exec();
        die();
    }

    /**
     * AJAX: get bugs of a user in html select.
     * 
     * @param  string $account 
     * @param  string $id       the id of the select control.
     * @access public
     * @return string
     */
    public function ajaxGetUserBugs($account = '', $id = '')
    {
        if($account == '') $account = $this->app->user->account;
        $bugs = $this->bug->getUserBugPairs($account);

        if($id) die(html::select("bugs[$id]", $bugs, '', 'class="form-control"'));
        die(html::select('bug', $bugs, '', 'class=form-control'));
    }

    /**
     * AJAX: Get bug owner of a module.
     * 
     * @param  int    $moduleID 
     * @param  int    $productID 
     * @access public
     * @return string
     */
    public function ajaxGetModuleOwner($moduleID, $productID = 0)
    {
        $owner = $this->bug->getModuleOwner($moduleID, $productID);
        die($owner);
    }

    /**
     * AJAX: get team members of the project as assignedTo list.
     * 
     * @param  int    $projectID 
     * @param  string $selectedUser 
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo($projectID, $selectedUser = '')
    {
        $projectMembers = $this->loadModel('project')->getTeamMemberPairs($projectID);
        
        die(html::select('assignedTo', $projectMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get team members of the latest project of a product as assignedTo list.
     * 
     * @param  int    $productID 
     * @param  string $selectedUser 
     * @access public
     * @return string
     */
    public function ajaxLoadProjectTeamMembers($productID, $selectedUser = '')
    {
        $latestProject = $this->product->getLatestProject($productID);
        if(!empty($latestProject)) 
        {
            $projectMembers = $this->loadModel('project')->getTeamMemberPairs($latestProject->id, 'nodeleted');
        }
        else
        {
            $projectMembers = $this->loadModel('user')->getPairs('nodeleted|devfirst|noclosed');
        }

        die(html::select('assignedTo', $projectMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get all users as assignedTo list.
     *
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadAllUsers($selectedUser = '')
    {
        $allUsers = $this->loadModel('user')->getPairs('nodeleted|devfirst|noclosed');

        die(html::select('assignedTo', $allUsers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get actions of a bug. for web app. 
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function ajaxGetDetail($bugID)
    {
        $this->view->actions = $this->loadModel('action')->getList('bug', $bugID);
        $this->display();
    }

    /**
     * Get data to export 
     * 
     * @param  string $productID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($productID, $orderBy)
    {
        if($_POST)
        {
            $this->loadModel('file');
            $this->loadModel('branch');
            $bugLang   = $this->lang->bug;
            $bugConfig = $this->config->bug;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $bugConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($bugLang->$fieldName) ? $bugLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get bugs. */
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where($this->session->bugQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($orderBy)->fetchAll('id');

            /* Get users, products and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $products = $this->loadModel('product')->getPairs('nocode');
            $projects = $this->loadModel('project')->getPairs('all|nocode');

            /* Get related objects id lists. */
            $relatedProductIdList = array();
            $relatedModuleIdList  = array();
            $relatedStoryIdList   = array();
            $relatedTaskIdList    = array();
            $relatedBugIdList     = array();
            $relatedCaseIdList    = array();
            $relatedBuildIdList   = array();
            $relatedBranchIdList  = array();

            foreach($bugs as $bug)
            {
                $relatedProductIdList[$bug->product]  = $bug->product;
                $relatedModuleIdList[$bug->module]    = $bug->module;
                $relatedStoryIdList[$bug->story]      = $bug->story;
                $relatedTaskIdList[$bug->task]        = $bug->task;
                $relatedCaseIdList[$bug->case]        = $bug->case;
                $relatedBugIdList[$bug->duplicateBug] = $bug->duplicateBug;
                $relatedBranchIdList[$bug->branch]    = $bug->branch;

                /* Process link bugs. */
                $linkBugs = explode(',', $bug->linkBug);
                foreach($linkBugs as $linkBugID)
                {
                    if($linkBugID) $relatedBugIdList[$linkBugID] = trim($linkBugID);
                }

                /* Process builds. */
                $builds = $bug->openedBuild . ',' . $bug->resolvedBuild;
                $builds = explode(',', $builds);
                foreach($builds as $buildID)
                {
                    if($buildID) $relatedBuildIdList[$buildID] = trim($buildID);
                }
            }

            /* Get related objects title or names. */
            $productsType   = $this->dao->select('id, type')->from(TABLE_PRODUCT)->where('id')->in($relatedProductIdList)->fetchPairs();
            $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedTasks   = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($relatedTaskIdList)->fetchPairs();
            $relatedBugs    = $this->dao->select('id, title')->from(TABLE_BUG)->where('id')->in($relatedBugIdList)->fetchPairs();
            $relatedCases   = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedBranch  = array('0' => $this->lang->branch->all) + $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($relatedBranchIdList)->fetchPairs();
            $relatedBuilds  = array('trunk' => $this->lang->trunk) + $this->dao->select('id, name')->from(TABLE_BUILD)->where('id')->in($relatedBuildIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('bug')->andWhere('objectID')->in(@array_keys($bugs))->andWhere('extra')->ne('editor')->fetchGroup('objectID');

            foreach($bugs as $bug)
            {
                if($this->post->fileType == 'csv')
                {
                    $bug->steps = htmlspecialchars_decode($bug->steps);
                    $bug->steps = str_replace("<br />", "\n", $bug->steps);
                    $bug->steps = str_replace('"', '""', $bug->steps);
                    $bug->steps = str_replace('&nbsp;', ' ', $bug->steps);
                    $bug->steps = strip_tags($bug->steps);
                }

                /* fill some field with useful value. */
                if(isset($products[$bug->product]))            $bug->product       = $products[$bug->product] . "(#$bug->product)";
                if(isset($projects[$bug->project]))            $bug->project       = $projects[$bug->project] . "(#$bug->project)";
                if(isset($relatedModules[$bug->module]))       $bug->module        = $relatedModules[$bug->module] . "(#$bug->module)";
                if(isset($relatedStories[$bug->story]))        $bug->story         = $relatedStories[$bug->story] . "(#$bug->story)";
                if(isset($relatedTasks[$bug->task]))           $bug->task          = $relatedTasks[$bug->task] . "($bug->task)";
                if(isset($relatedBugs[$bug->duplicateBug]))    $bug->duplicateBug  = $relatedBugs[$bug->duplicateBug] . "($bug->duplicateBug)";
                if(isset($relatedCases[$bug->case]))           $bug->case          = $relatedCases[$bug->case] . "($bug->case)";
                if(isset($relatedBuilds[$bug->resolvedBuild])) $bug->resolvedBuild = $relatedBuilds[$bug->resolvedBuild] . "(#$bug->resolvedBuild)";
                if(isset($relatedBranch[$bug->branch]))        $bug->branch        = $relatedBranch[$bug->branch] . "(#$bug->branch)";

                if(isset($bugLang->priList[$bug->pri]))               $bug->pri        = $bugLang->priList[$bug->pri];
                if(isset($bugLang->typeList[$bug->type]))             $bug->type       = $bugLang->typeList[$bug->type];
                if(isset($bugLang->severityList[$bug->severity]))     $bug->severity   = $bugLang->severityList[$bug->severity];
                if(isset($bugLang->osList[$bug->os]))                 $bug->os         = $bugLang->osList[$bug->os];
                if(isset($bugLang->browserList[$bug->browser]))       $bug->browser    = $bugLang->browserList[$bug->browser];
                if(isset($bugLang->statusList[$bug->status]))         $bug->status     = $bugLang->statusList[$bug->status];
                if(isset($bugLang->confirmedList[$bug->confirmed]))   $bug->confirmed  = $bugLang->confirmedList[$bug->confirmed];
                if(isset($bugLang->resolutionList[$bug->resolution])) $bug->resolution = $bugLang->resolutionList[$bug->resolution];
                
                if(isset($users[$bug->openedBy]))     $bug->openedBy     = $users[$bug->openedBy];
                if(isset($users[$bug->assignedTo]))   $bug->assignedTo   = $users[$bug->assignedTo];
                if(isset($users[$bug->resolvedBy]))   $bug->resolvedBy   = $users[$bug->resolvedBy];
                if(isset($users[$bug->lastEditedBy])) $bug->lastEditedBy = $users[$bug->lastEditedBy];
                if(isset($users[$bug->closedBy]))     $bug->closedBy     = $users[$bug->closedBy];

                $bug->openedDate     = substr($bug->openedDate,     0, 10);
                $bug->assignedDate   = substr($bug->assignedDate,   0, 10);
                $bug->closedDate     = substr($bug->closedDate,     0, 10);
                $bug->resolvedDate   = substr($bug->resolvedDate,   0, 10);
                $bug->lastEditedDate = substr($bug->lastEditedDate, 0, 10);

                if($bug->linkBug)
                {
                    $tmpLinkBugs = array();
                    $linkBugIdList = explode(',', $bug->linkBug);
                    foreach($linkBugIdList as $linkBugID)
                    {
                        $linkBugID = trim($linkBugID);
                        $tmpLinkBugs[] = isset($relatedBugs[$linkBugID]) ? $relatedBugs[$linkBugID] : $linkBugID;
                    }
                    $bug->linkBug = join("; \n", $tmpLinkBugs);
                }

                if($bug->openedBuild)
                {
                    $tmpOpenedBuilds   = array();
                    $tmpResolvedBuilds = array();
                    $buildIdList = explode(',', $bug->openedBuild);
                    foreach($buildIdList as $buildID)
                    {
                        $buildID = trim($buildID);
                        $tmpOpenedBuilds[] = isset($relatedBuilds[$buildID]) ? $relatedBuilds[$buildID] . "(#$buildID)" : $buildID;
                    }
                    $bug->openedBuild = join("\n", $tmpOpenedBuilds);
                    if($this->post->fileType == 'html') $bug->openedBuild = nl2br($bug->openedBuild);
                }

                /* Set related files. */
                if(isset($relatedFiles[$bug->id]))
                {
                    foreach($relatedFiles[$bug->id] as $file)
                    {
                        $fileURL = common::getSysURL() . $this->file->webPath . $this->file->getRealPathName($file->pathname);
                        $bug->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                    }
                }

                $bug->mailto = trim(trim($bug->mailto), ',');
                $mailtos     = explode(',', $bug->mailto);
                $bug->mailto = '';
                foreach($mailtos as $mailto)
                {
                    $mailto = trim($mailto);
                    if(isset($users[$mailto])) $bug->mailto .= $users[$mailto] . ',';
                }

                unset($bug->caseVersion);
                unset($bug->result);
                unset($bug->deleted);
            }

            if(!(in_array('platform', $productsType) or in_array('branch', $productsType))) unset($fields['branch']);// If products's type are normal, unset branch field.

            $this->post->set('fields', $fields);
            $this->post->set('rows', $bugs);
            $this->post->set('kind', 'bug');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->view->allExportFields = $this->config->bug->list->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * Ajax get bug by ID.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function ajaxGetByID($bugID)
    {
        $bug = $this->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        $realname = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($bug->assignedTo)->fetch('realname');
        $bug->assignedTo = $realname ? $realname : ($bug->assignedTo == 'closed' ? 'Closed' : $bug->assignedTo);
        die(json_encode($bug));
    }
}
