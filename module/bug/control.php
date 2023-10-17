<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class bug extends control
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
     * Construct function, load some modules auto.
     *
     * @param  string $moduleName
     * @param  string $methodName
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
        $this->loadModel('qa');

        /* Get product data. */
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($this->app->tab == 'project' or $this->app->tab == 'execution')
            {
                $objectID = $this->app->tab == 'project' ? $this->session->project : $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                $mode     = ($this->app->methodName == 'create' and empty($this->config->CRProduct)) ? 'noclosed' : '';
                $products = $this->product->getPairs($mode, 0, '', 'all');
            }
            if(empty($products) and !helper::isAjaxRequest()) return print($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=bug&objectID=$objectID")));
        }
        else
        {
            $mode     = (empty($this->config->CRProduct)) ? 'noclosed' : '';
            $products = $this->product->getPairs($mode, 0, '', 'all');
        }
        $this->view->products = $this->products = $products;
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
    public function browse($productID = 0, $branch = '', $browseType = '', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');

        $productID = $this->product->saveState($productID, $this->products);
        $product   = $this->product->getById($productID);
        if($product->type != 'normal')
        {
            /* Set productID, moduleID, queryID and branch. */
            $branch = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
            setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
            setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }
        else
        {
            $branch = 'all';
        }

        $this->qa->setMenu($this->products, $productID, $branch);

        /* Set browse type. */
        $browseType = strtolower($browseType);
        if($this->cookie->preProductID != $productID or ($this->cookie->preBranch != $branch and $product->type != 'normal' and $branch != 'all') or $browseType == 'bybranch')
        {
            $_COOKIE['bugModule'] = 0;
            setcookie('bugModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }
        if($browseType == 'bymodule' or $browseType == '')
        {
            setcookie('bugModule', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $_COOKIE['bugBranch'] = 0;
            setcookie('bugBranch', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($browseType == '') setcookie('treeBranch', $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }
        if($browseType == 'bybranch') setcookie('bugBranch', $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        if($browseType != 'bymodule' and $browseType != 'bybranch') $this->session->set('bugBrowseType', $browseType);

        $moduleID = ($browseType == 'bymodule') ? (int)$param : (($browseType == 'bysearch' or $browseType == 'bybranch') ? 0 : ($this->cookie->bugModule ? $this->cookie->bugModule : 0));
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set session. */
        $this->session->set('bugList', $this->app->getURI(true) . "#app={$this->app->tab}", 'qa');

        /* Set moduleTree. */
        if($browseType == '')
        {
            setcookie('treeBranch', $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $browseType = 'unclosed';
        }
        else
        {
            $branch = $this->cookie->treeBranch;
        }

        if($this->projectID and !$productID)
        {
            $moduleTree = $this->tree->getBugTreeMenu($this->projectID, $productID, 0, array('treeModel', 'createBugLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'bug', 0, array('treeModel', 'createBugLink'), '', $branch);
        }

        if(($browseType != 'bymodule' && $browseType != 'bybranch')) $this->session->set('bugBrowseType', $browseType);
        if(($browseType == 'bymodule' || $browseType == 'bybranch') and $this->session->bugBrowseType == 'bysearch') $this->session->set('bugBrowseType', 'unclosed');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->qaBugOrder ? $this->cookie->qaBugOrder : 'id_desc';
        setcookie('qaBugOrder', $orderBy, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get executions. */
        $cacheKey = sprintf($this->config->cacheKeys->bug->browse, $this->projectID);
        if(helper::isCacheEnabled() && $this->cache->has($cacheKey))
        {
            $executions = $this->cache->get($cacheKey);
        }
        else
        {
            $executions = $this->loadModel('execution')->fetchPairs($this->projectID, 'all', 'empty|withdelete|hideMultiple');
            if($this->config->cache->enable) $this->cache->set($cacheKey, $executions);
        }

        /* Get product id list. */
        $productIDList = $productID ? $productID : array_keys($this->products);

        /* Get bugs. */
        $bugs = $this->bug->getBugs($productIDList, $executions, $branch, $browseType, $moduleID, $queryID, $sort, $pager, $this->projectID);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->bug->dao->get(), 'bug', $browseType == 'needconfirm' ? false : true);

        /* Process bug for check story changed. */
        $bugs = $this->loadModel('story')->checkNeedConfirm($bugs);

        /* Process the openedBuild and resolvedBuild fields. */
        $bugs = $this->bug->processBuildForBugs($bugs);

        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }
        $storyList = $storyIdList ? $this->loadModel('story')->getByList($storyIdList) : array();
        $taskList  = $taskIdList  ? $this->loadModel('task')->getByList($taskIdList)   : array();

        /* Build the search form. */
        $actionURL = $this->createLink('bug', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['onMenuBar'] = 'yes';
        $searchProducts = $this->product->getPairs('', 0, '', 'all');
        $this->bug->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $branch);

        $showModule  = !empty($this->config->datatable->bugBrowse->showModule) ? $this->config->datatable->bugBrowse->showModule : '';
        $productName = ($productID and isset($this->products[$productID])) ? $this->products[$productID] : $this->lang->product->allProduct;

        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product and $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, 0, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        /* Set view. */
        $this->view->title           = $productName . $this->lang->colon . $this->lang->bug->common;
        $this->view->position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName,'','title=' . $productName);
        $this->view->position[]      = $this->lang->bug->common;
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->productName     = $productName;
        $this->view->builds          = $this->loadModel('build')->getBuildPairs($productID, $branch);
        $this->view->releasedBuilds  = $this->loadModel('release')->getReleasedBuilds($productID, $branch);
        $this->view->modules         = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch);
        $this->view->moduleTree      = $moduleTree;
        $this->view->moduleName      = $moduleID ? $this->tree->getById($moduleID)->name : $this->lang->tree->all;
        $this->view->summary         = $this->bug->summary($bugs);
        $this->view->browseType      = $browseType;
        $this->view->bugs            = $bugs;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->pager           = $pager;
        $this->view->param           = $param;
        $this->view->orderBy         = $orderBy;
        $this->view->moduleID        = $moduleID;
        $this->view->memberPairs     = $this->user->getPairs('noletter|noclosed');
        $this->view->branch          = $branch;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->executions      = $executions;
        $this->view->plans           = $this->loadModel('productplan')->getPairs($productID);
        $this->view->stories         = $storyList;
        $this->view->tasks           = $taskList;
        $this->view->setModule       = true;
        $this->view->isProjectBug    = ($productID and !$this->projectID) ? false : true;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
        $this->view->showBranch      = $showBranch;
        $this->view->projectPairs    = $this->loadModel('project')->getPairsByProgram();

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
    public function report($productID, $browseType, $branchID, $moduleID, $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->bug->$chartFunc();
                $chartOption = $this->lang->bug->report->$chart;
                if(!empty($chartType) and $chartType != 'default') $chartOption->type = $chartType;
                $this->bug->mergeChartOption($chart);

                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $project = $this->loadModel('project')->getByShadowProduct($productID);
        if(!empty($project) and !$project->multiple) unset($this->lang->bug->report->charts['bugsPerExecution']);

        $this->qa->setMenu($this->products, $productID, $branchID);
        $this->view->title         = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common . $this->lang->colon . $this->lang->bug->reportChart;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->reportChart;
        $this->view->productID     = $productID;
        $this->view->browseType    = $browseType;
        $this->view->branchID      = $branchID;
        $this->view->moduleID      = $moduleID;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? join(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * Create a bug.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $extras       others params, forexample, executionID=10,moduleID=10
     * @access public
     * @return void
     */
    public function create($productID, $branch = '', $extras = '')
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type) unset($this->lang->bug->typeList[$type]);

        /* Whether there is a object to transfer bug, for example feedback. */
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        $from = isset($output['from']) ? $output['from'] : '';

        if($this->app->tab == 'execution')
        {
            if(isset($output['executionID'])) $this->loadModel('execution')->setMenu($output['executionID']);
            $execution = $this->dao->findById($this->session->execution)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($execution->id, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'bug');
                $laneID      = isset($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }
        else if($this->app->tab == 'project')
        {
            if(isset($output['projectID'])) $this->loadModel('project')->setMenu($output['projectID']);
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch);
        }

        $this->view->users = $this->user->getPairs('devfirst|noclosed|nodeleted');
        $this->app->loadLang('release');

        if(!empty($_POST))
        {
            $response['result'] = 'success';

            /* Set from param if there is a object to transfer bug. */
            setcookie('lastBugModule', (int)$this->post->module, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $bugResult = $this->bug->create('', $extras);
            if(!$bugResult or dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $bugID = $bugResult['id'];
            if($bugResult['status'] == 'exists')
            {
                $response['message'] = sprintf($this->lang->duplicate, $this->lang->bug->common);
                $response['locate']  = $this->createLink('bug', 'view', "bugID=$bugID");
                $response['id']      = $bugResult['id'];
                return $this->send($response);
            }

            /* Record related action, for example FromSonarqube. */
            $createAction = $from == 'sonarqube' ? 'fromSonarqube' : 'Opened';
            $actionID     = $this->action->create('bug', $bugID, $createAction);

            $extras = str_replace(array(',', ' '), array('&', ''), $extras);
            parse_str($extras, $output);
            if(isset($output['todoID']))
            {
                $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($output['todoID'])->exec();
                $this->action->create('todo', $output['todoID'], 'finished', '', "BUG:$bugID");

                if($this->config->edition != 'open')
                {
                    $todo = $this->dao->select('type, idvalue')->from(TABLE_TODO)->where('id')->eq($output['todoID'])->fetch();
                    if($todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, 'done');
                }
            }

            $message = $this->executeHooks($bugID);
            if($message) $this->lang->saveSuccess = $message;

            /* Return bug id when call the API. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $bugID));

            /* If link from no head then reload. */
            if(isonlybody())
            {
                $executionID = isset($output['executionID']) ? $output['executionID'] : $this->session->execution;
                $executionID = $this->post->execution ? $this->post->execution : $executionID;
                $execution   = $this->loadModel('execution')->getByID($executionID);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban($kanbanData, 0)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "parent.updateKanban(\"bug\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'locate' => 'parent'));
                }
            }

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $bugID));

            if($this->app->tab == 'execution')
            {
                if(!preg_match("/(m=|\/)execution(&f=|-)bug(&|-|\.)?/", $this->session->bugList))
                {
                    $location = $this->session->bugList;
                }
                else
                {
                    $executionID = $this->post->execution ? $this->post->execution : zget($output, 'executionID', $this->session->execution);
                    $location    = $this->createLink('execution', 'bug', "executionID=$executionID");
                }

            }
            elseif($this->app->tab == 'project')
            {
                $location = $this->createLink('project', 'bug', "projectID=" . zget($output, 'projectID', $this->session->project));
            }
            else
            {
                setcookie('bugModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
                $location = $this->createLink('bug', 'browse', "productID={$this->post->product}&branch=$branch&browseType=byModule&param={$this->post->module}&orderBy=id_desc");
            }
            if($this->app->getViewType() == 'xhtml') $location = $this->createLink('bug', 'view', "bugID=$bugID", 'html');
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $location;
            return $this->send($response);
        }

        /* Get product, then set menu. */
        $productID      = $this->product->saveState($productID, $this->products);
        $currentProduct = $this->product->getById($productID);

        if($branch === '') $branch = (int)$this->cookie->preBranch;

        /* Init vars. */
        $projectID   = 0;
        $moduleID    = 0;
        $executionID = 0;
        $taskID      = 0;
        $storyID     = 0;
        $buildID     = 0;
        $caseID      = 0;
        $runID       = 0;
        $testtask    = 0;
        $version     = 0;
        $title       = $from == 'sonarqube' ? $_COOKIE['sonarqubeIssue'] : '';
        $steps       = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $os          = '';
        $browser     = '';
        $assignedTo  = isset($currentProduct->QD) ? $currentProduct->QD : '';
        $deadline    = '';
        $mailto      = '';
        $keywords    = '';
        $severity    = 3;
        $type        = 'codeerror';
        $pri         = 3;
        $color       = '';
        $feedbackBy  = '';
        $notifyEmail = '';

        /* Parse the extras. extract fix php7.2. */
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        extract($output);

        if($runID and $resultID) extract($this->bug->getBugInfoFromResult($resultID, 0, 0, isset($stepIdList) ? $stepIdList : ''));// If set runID and resultID, get the result info by resultID as template.
        if(!$runID and $caseID)  extract($this->bug->getBugInfoFromResult($resultID, $caseID, $version, isset($stepIdList) ? $stepIdList : ''));// If not set runID but set caseID, get the result info by resultID and case info.

        /* If bugID setted, use this bug as template. */
        if(isset($bugID))
        {
            $bug = $this->bug->getById($bugID);

            extract((array)$bug);
            $executionID = $bug->execution;
            $moduleID    = $bug->module;
            $taskID      = $bug->task;
            $storyID     = $bug->story;
            $buildID     = $bug->openedBuild;
            $severity    = $bug->severity;
            $type        = $bug->type;
            $assignedTo  = $bug->assignedTo;
            $deadline    = helper::isZeroDate($bug->deadline) ? '' : $bug->deadline;
            $color       = $bug->color;
            $testtask    = $bug->testtask;
            $feedbackBy  = $bug->feedbackBy;
            $notifyEmail = $bug->notifyEmail;
            if($pri == 0) $pri = '3';
        }

        if($testtask)
        {
            $testtask = $this->loadModel('testtask')->getById($testtask);
            $buildID  = $testtask->build;
        }

        if(isset($todoID))
        {
            $todo  = $this->loadModel('todo')->getById($todoID);
            $title = $todo->name;
            $steps = $todo->desc;
            $pri   = $todo->pri;
        }

        /* Get branches. */
        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $projectID : $executionID;
            $productBranches = $currentProduct->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct($productID, $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $currentProduct->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        /* If executionID is setted, get builds and stories of this execution. */
        if($executionID)
        {
            $builds  = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noterminate,nodone,noreleased', $executionID, 'execution');
            $stories = $this->story->getExecutionStoryPairs($executionID);
            if(!$projectID) $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        }
        else
        {
            $moduleID = $moduleID ? $moduleID : 0;
            $builds   = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noterminate,nodone,withbranch,noreleased');
            $stories  = $this->story->getProductStoryPairs($productID, $branch, $moduleID, 'all','id_desc', 0, 'full', 'story', false);
        }

        $moduleOwner = $this->bug->getModuleOwner($moduleID, $productID);

        /* Get all project team members linked with this product. */
        $productMembers = $this->bug->getProductMemberPairs($productID, $branch);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->view->users;

        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, ($branch === 'all' or !isset($branches[$branch])) ? 0 : $branch);
        if(empty($moduleOptionMenu)) return print(js::locate(helper::createLink('tree', 'browse', "productID=$productID&view=story")));

        /* Get products and projects. */
        $products = $this->config->CRProduct ? $this->products : $this->product->getPairs('noclosed', 0, '', 'all');
        $projects = array(0 => '');
        if($executionID)
        {
            $products       = array();
            $linkedProducts = $this->loadModel('product')->getProducts($executionID);
            foreach($linkedProducts as $product) $products[$product->id] = $product->name;
        }
        elseif($projectID)
        {
            $products    = array();
            $productList = $this->config->CRProduct ? $this->product->getOrderedProducts('all', 40, $projectID) : $this->product->getOrderedProducts('normal', 40, $projectID);
            foreach($productList as $product) $products[$product->id] = $product->name;

            /* Set project menu. */
            if($this->app->tab == 'project') $this->project->setMenu($projectID);
        }
        else
        {
            $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        }

        $projectModel = '';
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if($project)
            {
                if(empty($bugID) or $this->app->tab != 'qa') $projects += array($projectID => $project->name);

                /* Replace language. */
                if(!empty($project->model) and $project->model == 'waterfall') $this->lang->bug->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->bug->execution);
                $projectModel = $project->model;

                if(!$project->multiple) $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);
            }
        }

        /* Link all projects to product when copying bug under qa.*/
        if(!empty($bugID) and $this->app->tab == 'qa') $projects += $this->product->getProjectPairsByProduct($productID, $branch);

        /* Get block id of assinge to me. */
        $blockID = 0;
        if(isonlybody())
        {
            $blockID = $this->dao->select('id')->from(TABLE_BLOCK)
                ->where('block')->eq('assingtome')
                ->andWhere('module')->eq('my')
                ->andWhere('account')->eq($this->app->user->account)
                ->orderBy('order_desc')
                ->fetch('id');
        }

        /* Get executions. */
        $executions = array(0 => '');
        if(isset($projects[$projectID])) $executions += $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : 0, 'id_desc', $projectID, !$projectID ? 'multiple|stagefilter' : 'stagefilter');
        $execution  = $executionID ? $this->loadModel('execution')->getByID($executionID) : '';
        $executions = isset($executions[$executionID]) ? $executions : $executions + array($executionID => $execution->name);

        /* Set custom. */
        if($execution and !$execution->multiple)
        {
            $this->config->bug->list->customCreateFields = str_replace('execution,', '', $this->config->bug->list->customCreateFields);
            $this->config->bug->custom->createFields = str_replace('execution,', '', $this->config->bug->custom->createFields);
        }
        foreach(explode(',', $this->config->bug->list->customCreateFields) as $field) $customFields[$field] = $this->lang->bug->$field;


        $this->view->title        = isset($this->products[$productID]) ? $this->products[$productID] . $this->lang->colon . $this->lang->bug->create : $this->lang->bug->create;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->bug->custom->createFields;

        $this->view->gobackLink            = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('bug', 'browse', "productID=$productID") : '';
        $this->view->products              = $products;
        $this->view->productID             = $productID;
        $this->view->productName           = isset($this->products[$productID]) ? $this->products[$productID] : '';
        $this->view->moduleOptionMenu      = $moduleOptionMenu;
        $this->view->stories               = $stories;
        $this->view->projects              = defined('TUTORIAL') ? $this->loadModel('tutorial')->getProjectPairs() : $projects;
        $this->view->projectExecutionPairs = $this->loadModel('project')->getProjectExecutionPairs();
        $this->view->executions            = defined('TUTORIAL') ? $this->loadModel('tutorial')->getExecutionPairs() : $executions;
        $this->view->builds                = $builds;
        $this->view->releasedBuilds        = $this->loadModel('release')->getReleasedBuilds($productID, $branch);
        $this->view->moduleID              = (int)$moduleID;
        $this->view->projectID             = $projectID;
        $this->view->projectModel          = $projectModel;
        $this->view->execution             = $execution;
        $this->view->taskID                = $taskID;
        $this->view->storyID               = $storyID;
        $this->view->buildID               = $buildID;
        $this->view->caseID                = $caseID;
        $this->view->resultFiles           = (!empty($resultID) and !empty($stepIdList)) ? $this->loadModel('file')->getByObject('stepResult', $resultID, str_replace('_', ',', $stepIdList)) : array();
        $this->view->runID                 = $runID;
        $this->view->version               = $version;
        $this->view->testtask              = $testtask;
        $this->view->bugTitle              = $title;
        $this->view->pri                   = $pri;
        $this->view->steps                 = htmlSpecialString($steps);
        $this->view->os                    = $os;
        $this->view->browser               = $browser;
        $this->view->productMembers        = $productMembers;
        $this->view->assignedTo            = $assignedTo;
        $this->view->deadline              = $deadline;
        $this->view->mailto                = $mailto;
        $this->view->keywords              = $keywords;
        $this->view->severity              = $severity;
        $this->view->type                  = $type;
        $this->view->product               = $currentProduct;
        $this->view->branch                = $branch;
        $this->view->branches              = $branches;
        $this->view->blockID               = $blockID;
        $this->view->color                 = $color;
        $this->view->stepsRequired         = strpos($this->config->bug->create->requiredFields, 'steps');
        $this->view->isStepsTemplate       = $steps == $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect ? true : false;
        $this->view->issueKey              = $from == 'sonarqube' ? $output['sonarqubeID'] . ':' . $output['issueKey'] : '';
        $this->view->feedbackBy            = $feedbackBy;
        $this->view->notifyEmail           = $notifyEmail;

        $this->display();
    }

    /**
     * Batch create.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $moduleID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = '', $executionID = 0, $moduleID = 0, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $actions = $this->bug->batchCreate($productID, $branch, $extra);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Return bug id list when call the API. */
            if($this->viewType == 'json')
            {
                $bugIDList = array_keys($actions);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $bugIDList));
            }

            setcookie('bugModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

            /* If link from no head then reload. */
            if(isonlybody() and $executionID)
            {
                $execution = $this->loadModel('execution')->getByID($executionID);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);

                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::reload('parent.parent'));
                }
            }

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('bug', 'browse', "productID={$productID}&branch=$branch&browseType=unclosed&param=0&orderBy=id_desc"), 'parent'));
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        if($branch === '') $branch = (int)$this->cookie->preBranch;
        $this->qa->setMenu($this->products, $productID, $branch);

        /* If executionID is setted, get builds and stories of this execution. */
        if($executionID)
        {
            $builds    = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased', $executionID, 'execution');
            $stories   = $this->story->getExecutionStoryPairs($executionID);
            $execution = $this->loadModel('execution')->getById($executionID);
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'bug');
                $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }
        else
        {
            $builds  = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased');
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
            $this->view->titles = $titles;
        }

        /* Set custom. */
        $product = $this->product->getById($productID);
        foreach(explode(',', $this->config->bug->list->customBatchCreateFields) as $field)
        {
            if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
            $customFields[$field] = $this->lang->bug->$field;
        }

        if($product->type != 'normal')
        {
            $this->config->bug->custom->batchCreateFields = sprintf($this->config->bug->custom->batchCreateFields, $product->type);
        }
        else
        {
            $this->config->bug->custom->batchCreateFields = trim(sprintf($this->config->bug->custom->batchCreateFields, ''), ',');
        }

        $showFields = $this->config->bug->custom->batchCreateFields;
        if($product->type == 'normal')
        {
            $showFields = str_replace(array(0 => ",branch,", 1 => ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        $projectID = $this->lang->navGroup->bug == 'project' ? $this->session->project : (isset($execution) ? $execution->project : 0);
        $project   = $this->loadModel('project')->getByID($projectID);
        if(isset($project->model) && $project->model == 'kanban') $customFields['execution'] = $this->lang->bug->kanban;

        /* Get branches. */
        if($executionID)
        {
            $productBranches = $product->type != 'normal' ? $this->execution->getBranchByProduct($productID, $executionID) : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array();
            $branch          = key($branches);
        }
        else
        {
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->batchCreate;

        $this->view->project          = $project;
        $this->view->product          = $product;
        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->builds           = $builds;
        $this->view->users            = $this->user->getPairs('devfirst|noclosed');
        $this->view->projects         = array('' => '') + $this->product->getProjectPairsByProduct($productID, $branch ? "0,$branch" : 0);
        $this->view->projectID        = $projectID;
        $this->view->executions       = array('' => '') + $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : 0, 'id_desc', $projectID, 'multiple,stagefilter');
        $this->view->executionID      = $executionID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $branch === 'all' ? 0 : $branch);
        $this->view->moduleID         = $moduleID;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;

        $this->display();
    }

    /**
     * View a bug.
     *
     * @param  int    $bugID
     * @param  string $form
     * @access public
     * @return void
     */
    public function view($bugID, $from = 'bug')
    {
        /* Judge bug exits or not. */
        $bugID = (int)$bugID;
        $bug   = $this->bug->getById($bugID, true);
        if(!$bug) return print(js::error($this->lang->notFound) . js::locate($this->createLink('qa', 'index')));

        $this->session->set('storyList', '', 'product');
        $this->session->set('projectList', $this->app->getURI(true) . "#app={$this->app->tab}", 'project');
        $this->bug->checkBugExecutionPriv($bug);

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        /* Set menu. */
        if(!isonlybody())
        {
            if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
            if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($bug->execution);
            if($this->app->tab == 'qa')        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

            if($this->app->tab == 'devops')
            {
                $repos = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
                $this->repo->setMenu($repos);
                $this->lang->navGroup->bug = 'devops';
            }

            if($this->app->tab == 'product')
            {
                $this->loadModel('product')->setMenu($bug->product);
                $this->lang->product->menu->plan['subModule'] .= ',bug';
            }
        }

        /* Get product info. */
        $productID = $bug->product;
        $product   = $this->loadModel('product')->getByID($productID);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);

        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $bug->branch);
        $this->session->set("project", key($projects), 'project');

        $this->executeHooks($bugID);

        /* Header and positon. */
        $this->view->title      = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $product->name);
        $this->view->position[] = $this->lang->bug->view;

        /* Assign. */
        $this->view->project     = $this->loadModel('project')->getByID($bug->project);
        $this->view->productID   = $productID;
        $this->view->branches    = $branches;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getById($bug->module);
        $this->view->bug         = $bug;
        $this->view->from        = $from;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noterminate, nodone, hasdeleted');
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('bug', $bugID);
        $this->view->product     = $product;
        $this->view->linkCommits = $this->loadModel('repo')->getCommitsByObject($bugID, 'bug');

        $this->view->projects = array('' => '') + $projects;

        $this->display();
    }

    /**
     * Edit a bug.
     *
     * @param  int    $bugID
     * @param  bool   $comment
     * @param  string $kanbanGroup
     * @access public
     * @return void
     */
    public function edit($bugID, $comment = false, $kanbanGroup = 'default')
    {
        if(!empty($_POST))
        {
            $changes = array();
            $files   = array();
            if($comment == false)
            {
                $changes = $this->bug->update($bugID);
                if(dao::isError())
                {
                    if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'error', 'message' => dao::getError()));
                    return print(js::error(dao::getError()));
                }
            }

            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('bug', $bugID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $bugID));
            $bug = $this->bug->getById($bugID);

            $this->executeHooks($bugID);

            if($bug->toTask != 0)
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                        $cancelURL  = $this->server->HTTP_REFERER;
                        return print(js::confirm(sprintf($this->lang->bug->remindTask, $bug->Task), $confirmURL, $cancelURL, 'parent', 'parent'));
                    }
                }
            }
            if(isonlybody())
            {
                $execution = $this->loadModel('execution')->getByID($bug->execution);
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

                    if(isset($execution->type) and $execution->type == 'kanban')
                    {
                        $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                        $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                        $kanbanData    = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                        $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                        $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                        $kanbanData      = $kanbanData[$kanbanType];
                        $kanbanData      = json_encode($kanbanData);
                        return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::closeModal('parent.parent'));
                }
            }
            return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* Get the info of bug, current product and modue. */
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $executionID     = $bug->execution;
        $projectID       = $bug->project;
        $currentModuleID = $bug->module;
        $product         = $this->loadModel('product')->getByID($productID);
        $execution       = $this->loadModel('execution')->getByID($executionID);
        $this->bug->checkBugExecutionPriv($bug);

        if(!isset($this->products[$bug->product]))
        {
            $this->products[$bug->product] = $product->name;
            $this->view->products = $this->products;
        }

        /* Set the menu. */
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->qa->setMenu($this->products, $productID, $bug->branch);
        if($this->app->tab == 'devops')
        {
            session_write_close();

            $repos = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
            $this->repo->setMenu($repos);

            $this->lang->navGroup->bug = 'devops';
        }

        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type)
        {
            if($bug->type != $type) unset($this->lang->bug->typeList[$type]);
        }

        if($this->app->tab == 'qa')
        {
            $this->view->products = $this->config->CRProduct ? $this->products : $this->product->getPairs('noclosed');
        }
        if($this->app->tab == 'project')
        {
            $products    = array();
            $productList = $this->config->CRProduct ? $this->product->getOrderedProducts('all', 40, $bug->project) : $this->product->getOrderedProducts('normal', 40, $bug->project);
            foreach($productList as $productInfo) $products[$productInfo->id] = $productInfo->name;

            $this->view->products = $products;
        }

        /* Set header and position. */
        $this->view->title      = $this->lang->bug->edit . "BUG #$bug->id $bug->title - " . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->edit;

        $projectID = $this->lang->navGroup->bug == 'project' ? $this->session->project : 0;

        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID = $this->app->tab == 'project' ? $bug->project : $bug->execution;
        }

        /* Display status of branch. */
        $branches = $this->loadModel('branch')->getList($productID, isset($objectID) ? $objectID : 0, 'all');
        $branchOption    = array();
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchOption[$branchInfo->id]    = $branchInfo->name;
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }
        if(!isset($branchTagOption[$bug->branch]))
        {
            $bugBranch = $this->branch->getById($bug->branch, $bug->product, '');
            $branchTagOption[$bug->branch] = $bug->branch == BRANCH_MAIN ? $bugBranch : ($bugBranch->name . ($bugBranch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
        }

        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0, $bug->branch);
        if(!isset($moduleOptionMenu[$bug->module])) $moduleOptionMenu += $this->tree->getModulesName($bug->module);

        $cases = array();
        if($bug->case)
        {
            $case  = $this->loadModel('testcase')->getByID($bug->case);
            $cases = array($case->id => $case->id . $case->title);
        }

        $this->config->moreLinks['case'] = inlink('ajaxGetProductCases', "bugID={$bugID}");

        /* Get assigned to member. */
        if($bug->execution)
        {
            $assignedToList = $this->user->getTeamMemberPairs($bug->execution, 'execution');
        }
        elseif($bug->project)
        {
            $assignedToList = $this->loadModel('project')->getTeamMemberPairs($bug->project);
        }
        else
        {
            $assignedToList = $this->bug->getProductMemberPairs($bug->product, $bug->branch);
            $assignedToList = array_filter($assignedToList);
            if(empty($assignedToList)) $assignedToList = $this->user->getPairs('devfirst|noclosed');
        }
        if($bug->assignedTo and !isset($assignedToList[$bug->assignedTo]) and $bug->assignedTo != 'closed')
        {
            /* Fix bug #28378. */
            $assignedTo = $this->user->getById($bug->assignedTo);
            $assignedToList[$bug->assignedTo] = $assignedTo->realname;
        }
        if($bug->status == 'closed') $assignedToList['closed'] = 'Closed';

        $branch = $product->type == 'branch' ? ($bug->branch > 0 ? $bug->branch . ',0' : '0') : '';

        $productBugs = array();
        if($bug->resolution and $bug->duplicateBug)
        {
            $duplicateBug = $this->bug->getByID($bug->duplicateBug);
            $productBugs  = $this->bug->getProductBugPairs($productID, $branch, $duplicateBug->title);
        }
        $this->config->moreLinks['duplicateBug'] = inlink('ajaxGetProductBugs', "productID={$productID}&bugID={$bugID}&type=json");

        unset($productBugs[$bugID]);

        $executions = array(0 => '') + $this->product->getExecutionPairsByProduct($bug->product, $bug->branch, 'id_desc', $bug->project);
        if(!empty($bug->execution) and empty($executions[$bug->execution])) $executions[$execution->id] = $execution->name . "({$this->lang->bug->deleted})";

        $projects = array(0 => '') + $this->product->getProjectPairsByProduct($productID, $bug->branch);
        if(!empty($bug->project) and empty($projects[$bug->project]))
        {
            $project = $this->loadModel('project')->getByID($bug->project);
            $projects[$project->id] = $project->name . "({$this->lang->bug->deleted})";
        }

        if($product->shadow) $this->view->project = $this->loadModel('project')->getByShadowProduct($bug->product);
        $this->app->loadLang('build');

        $this->view->bug                   = $bug;
        $this->view->product               = $product;
        $this->view->execution             = $execution;
        $this->view->productBugs           = $productBugs;
        $this->view->productName           = $this->products[$productID];
        $this->view->plans                 = $this->loadModel('productplan')->getPairs($productID, $bug->branch, '', true);
        $this->view->projects              = $projects;
        $this->view->projectExecutionPairs = $this->loadModel('project')->getProjectExecutionPairs();
        $this->view->moduleOptionMenu      = $moduleOptionMenu;
        $this->view->currentModuleID       = $currentModuleID;
        $this->view->executions            = $executions;
        $this->view->branchOption          = $branchOption;
        $this->view->branchTagOption       = $branchTagOption;
        $this->view->tasks                 = $this->task->getExecutionTaskPairs($bug->execution);
        $this->view->testtasks             = $this->loadModel('testtask')->getPairs($bug->product, $bug->execution, $bug->testtask);
        $this->view->users                 = $this->user->getPairs('noclosed', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->assignedToList        = $assignedToList;
        $this->view->cases                 = array('' => '') + $cases;
        $this->view->actions               = $this->action->getList('bug', $bugID);

        $this->display();
    }

    /**
     * Ajax get opened and resolved builds.
     *
     * @param  int    $bugID
     * @access public
     * @return int
     */
    public function ajaxGetAllBuilds($bugID)
    {
        $bug         = $this->bug->getById($bugID);
        $productID   = $bug->product;
        $executionID = $bug->execution;
        $projectID   = $bug->project;

        $allBuilds = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noempty');
        if($executionID)
        {
            $openedBuilds = $this->build->getBuildPairs($productID, $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased', $executionID, 'execution');
        }
        elseif($projectID)
        {
            $openedBuilds = $this->build->getBuildPairs($productID, $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased', $projectID, 'project');
        }
        else
        {
            $openedBuilds = $this->build->getBuildPairs($productID, $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased');
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

        $builds = new stdclass();
        $builds->openedBuilds = $this->bug->convertArrayToObjectArray($openedBuilds);

        $resolvedBuilds = $openedBuilds + $oldResolvedBuild;
        $builds->resolvedBuilds = $this->bug->convertArrayToObjectArray($resolvedBuilds);

        $builds->resolvedBuildName = zget($resolvedBuilds, $bug->resolvedBuild);

        return print(helper::jsonEncode($builds));
    }

    /**
     * Ajax get testTasks.
     *
     * @param  int    $bugID
     * @access public
     * @return int
     */
    public function ajaxGetTestTasks($bugID)
    {
        $bug       = $this->bug->getById($bugID);
        $testTasks = $this->loadModel('testtask')->getPairs($bug->product, $bug->execution, $bug->testtask, 'noempty');
        $testTasks = $this->bug->convertArrayToObjectArray($testTasks);

        return print(helper::jsonEncode($testTasks));
    }

    /**
     * Ajax get stories.
     *
     * @param  int    $bugID
     * @access public
     * @return int
     */
    public function ajaxGetStories($bugID)
    {
        $bug     = $this->bug->getById($bugID);
        $stories = $bug->execution ? $this->story->getExecutionStoryPairs($bug->execution) : $this->story->getProductStoryPairs($bug->product, $bug->branch, 0, 'all', 'id_desc', 0, 'full', 'story', false);
        if(isset($stories[0])) unset($stories[0]);
        $stories = $this->bug->convertArrayToObjectArray($stories);

        return print(helper::jsonEncode($stories));
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
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($productID);
        }

        if($this->post->titles)
        {
            $allChanges = $this->bug->batchUpdate();

            foreach($allChanges as $bugID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);

                $bug = $this->bug->getById($bugID);
                if($bug->toTask != 0)
                {
                    foreach($changes as $change)
                    {
                        if($change['field'] == 'status')
                        {
                            $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                            $cancelURL  = $this->server->HTTP_REFERER;
                            return print(js::confirm(sprintf($this->lang->bug->remindTask, $bug->task), $confirmURL, $cancelURL, 'parent', 'parent'));
                        }
                    }
                }
            }
            return print(js::locate($this->session->bugList, 'parent'));
        }

        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));
        /* Initialize vars.*/
        $bugIDList = array_unique($this->post->bugIDList);
        $bugs      = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        /* The bugs of a product. */
        if($productID)
        {
            $product = $this->product->getByID($productID);
            $branchProduct = $product->type == 'normal' ? false : true;

            /* Set plans. */
            foreach($bugs as $bug)
            {
                $projectID  = $bug->project;
                $plans      = $this->loadModel('productplan')->getPairs($productID, $bug->branch, '', true);
                $plans      = array('' => '', 'ditto' => $this->lang->bug->ditto) + $plans;
                $bug->plans = $plans;
            }

            /* Set branches and modules. */
            $branches        = 0;
            $branchTagOption = array();
            $modules         = array();
            if($product->type != 'normal')
            {
                $branches = $this->loadModel('branch')->getList($productID, 0 ,'all');
                foreach($branches as $branchInfo) $branchTagOption[$productID][$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
                $branches = array_keys($branches);
            }

            $modulePairs = $this->tree->getOptionMenu($productID, 'bug', 0, $branches);
            $modules[$productID] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);

            /* Set product menu. */
            $this->qa->setMenu($this->products, $productID, $branch);

            $project = $this->loadModel('project')->getByID($projectID);
            if($product->shadow and isset($project) and empty($project->multiple))
            {
                $this->config->bug->custom->batchEditFields = str_replace('productplan', '', $this->config->bug->custom->batchEditFields);
                $this->config->bug->list->customBatchEditFields = str_replace(',productplan,', ',', $this->config->bug->list->customBatchEditFields);
            }

            $this->view->title      = $product->name . $this->lang->colon . "BUG" . $this->lang->bug->batchEdit;
            $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID&branch=$branch"), $this->products[$productID]);
            $this->view->plans      = $plans;
        }
        /* The bugs of my. */
        else
        {
            $branchProduct   = false;
            $productIdList   = array();
            $branchTagOption = array();
            foreach($bugs as $bug) $productIdList[$bug->product] = $bug->product;
            $productList = $this->product->getByIdList($productIdList);
            foreach($productList as $product)
            {
                $branches = 0;
                if($product->type != 'normal')
                {
                    $branches = $this->loadModel('branch')->getList($product->id, 0 ,'all');
                    foreach($branches as $branchInfo) $branchTagOption[$product->id][$branchInfo->id] = '/' . $product->name . '/' . $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');

                    $branches      = array_keys($branches);
                    $branchProduct = true;
                }

                $modulePairs = $this->tree->getOptionMenu($product->id, 'bug', 0, $branches);
                $modules[$product->id] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);
            }

            $this->app->loadLang('my');
            $this->lang->task->menu = $this->lang->my->menu->work;
            $this->lang->my->menu->work['subModule'] = 'bug';

            $this->view->position[]  = html::a($this->createLink('my', 'bug'), $this->lang->my->bug);
            $this->view->title       = "BUG" . $this->lang->bug->batchEdit;
            $this->view->plans       = $this->loadModel('productplan')->getPairs($product->id, $branch);
            $this->view->productList = $productList;
        }

        /* Judge whether the editedBugs is too large and set session. */
        $countInputVars  = count($bugs) * (count(explode(',', $this->config->bug->custom->batchEditFields)) + 2);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);

        /* Set Custom. */
        foreach(explode(',', $this->config->bug->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->bug->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->bug->custom->batchEditFields;

        /* Set users. */
        $users = $this->loadModel('user')->getPairs();
        $limitUsers = $users;
        if(count($users) > $this->config->batchMaxCount) $limitUsers = array_slice($users, 0 , $this->config->batchMaxCount);

        $branchIdList    = array();
        $projectIdList   = array();
        $executionIdList = array();
        $productBugList  = array();
        foreach($bugs as $bug)
        {
            $projectIdList[$bug->project]     = $bug->project;
            $executionIdList[$bug->execution] = $bug->execution;

            $branchIdList[$bug->product][$bug->branch] = $bug->branch;

            if(!isset($modules[$bug->product][$bug->branch]) and isset($modules[$bug->product])) $modules[$bug->product][$bug->branch] = $modules[$bug->product][0] + $this->tree->getModulesName($bug->module);

            $bugProduct = isset($productList) ? $productList[$bug->product] : $product;
            $branch     = $bugProduct->type == 'branch' ? ($bug->branch > 0 ? $bug->branch . ',0' : '0') : '';
            if(!isset($productBugList[$bug->product][$bug->branch]))
            {
                if($bug->resolution and $bug->duplicateBug)
                {
                    $duplicateBug = $this->bug->getByID($bug->duplicateBug);
                    $productBugList[$bug->product][$bug->branch] = $this->bug->getProductBugPairs($bug->product, $branch, $duplicateBug->title);
                }
            }

            $bug->assignedToList = array();
            if($this->app->tab == 'project' or $this->app->tab == 'execution')
            {
                if($bug->execution)
                {
                    $bug->assignedToList = $executionMembers[$bug->execution];
                }
                elseif($bug->project)
                {
                    $bug->assignedToList = $projectMembers[$bug->project];
                }
                else
                {
                    $bug->assignedToList = $productMembers[$bug->product][$bug->branch];
                    if(empty($bug->assignedToList))
                    {
                        $bug->assignedToList = $limitUsers;
                        if(count($users) > $this->config->batchMaxCount) $this->config->moreLinks["assignedTos[$bug->id]"] = helper::createLink('user', 'ajaxGetMore');
                        unset($bug->assignedToList['closed']);
                    }
                }
            }
            else
            {
                $bug->assignedToList = $limitUsers;
                if(count($users) > $this->config->batchMaxCount) $this->config->moreLinks["assignedTos[$bug->id]"] = helper::createLink('user', 'ajaxGetMore');
                unset($bug->assignedToList['closed']);
            }
            $bug->assignedToList = array('' => '', 'ditto' => $this->lang->bug->ditto) + array($bug->assignedTo => zget($users, $bug->assignedTo)) + $bug->assignedToList;

            $this->config->moreLinks["duplicateBugs[{$bug->id}]"] = inlink('ajaxGetProductBugs', "productID={$bug->product}&bugID={$bug->id}&type=json");
            if(count($users) > $this->config->batchMaxCount) $this->config->moreLinks["resolvedBys[$bug->id]"]     = helper::createLink('user', 'ajaxGetMore');
        }

        /* Get assigned to member. */
        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $this->loadModel('project');
            $this->loadModel('execution');

            $productMembers   = array();
            $projectMembers   = array();
            $executionMembers = array();
            if($productID)
            {
                $branchList = zget($branchIdList, $productID, array());
                foreach($branchList as $branchID)
                {
                    $members = $this->bug->getProductMemberPairs($productID, $branchID);
                    $productMembers[$productID][$branchID] = array_filter($members);
                }
            }
            else
            {
                foreach($productIdList as $id)
                {
                    $branchList = zget($branchIdList, $id, array());
                    foreach($branchList as $branchID)
                    {
                        $members = $this->bug->getProductMemberPairs($id, $branchID);
                        $productMembers[$id][$branchID] = array_filter($members);
                    }
                }
            }

            $projectMemberGroup = $this->project->getTeamMemberGroup($projectIdList);
            $projectMembers     = array();
            foreach($projectIdList as $projectID)
            {
                $projectTeam = zget($projectMemberGroup, $projectID, array());
                if(empty($projectTeam)) $projectMembers[$projectID] = array();
                foreach($projectTeam as $user)
                {
                    $projectMembers[$projectID][$user->account] = $user->realname;
                }
            }

            $executionMemberGroup = $this->execution->getMembersByIdList($executionIdList);
            $executionMembers     = array();
            foreach($executionIdList as $executionID)
            {
                $executionTeam = zget($executionMemberGroup, $executionID, array());
                if(empty($executionTeam)) $executionMemberGroup[$executionID] = array();
                foreach($executionTeam as $user)
                {
                    $executionMembers[$executionID][$user->account] = $user->realname;
                }
            }

            $this->view->productMembers   = $productMembers;
            $this->view->projectMembers   = $projectMembers;
            $this->view->executionMembers = $executionMembers;
        }

        /* Assign. */
        $this->view->productID        = $productID;
        $this->view->branchProduct    = $branchProduct;
        $this->view->severityList     = array('ditto' => $this->lang->bug->ditto) + $this->lang->bug->severityList;
        $this->view->typeList         = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->typeList;
        $this->view->priList          = array('0' => '', 'ditto' => $this->lang->bug->ditto) + $this->lang->bug->priList;
        $this->view->resolutionList   = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->resolutionList;
        $this->view->statusList       = array('' => '',  'ditto' => $this->lang->bug->ditto) + $this->lang->bug->statusList;
        $this->view->bugs             = $bugs;
        $this->view->branch           = $branch;
        $this->view->users            = $users;
        $this->view->limitUsers       = $limitUsers;
        $this->view->modules          = $modules;
        $this->view->branchTagOption  = $branchTagOption;
        $this->view->productBugList   = $productBugList;

        $this->display();
    }

    /**
     * Update assign of bug.
     *
     * @param  int    $bugID
     * @param  string $kanbanGroup
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function assignTo($bugID, $kanbanGroup = 'default', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        $this->bug->checkBugExecutionPriv($bug);

        /* Set menu. */
        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->bug->assign($bugID);
            if(dao::isError()) return print(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($bugID);

            if(isonlybody())
            {
                $bug          = $this->bug->getById($bugID);
                $execution    = $this->loadModel('execution')->getByID($bug->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and isset($execution->type) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', 0, $kanbanGroup, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent'));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $bugID));
            }
            else
            {
                return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
            }
        }

        /* Get assigned to member. */
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            if($bug->execution)
            {
                $users = $this->user->getTeamMemberPairs($bug->execution, 'execution');
            }
            elseif($bug->project)
            {
                $users = $this->loadModel('project')->getTeamMemberPairs($bug->project);
            }
            else
            {
                $users = $this->bug->getProductMemberPairs($bug->product, $bug->branch);
                $users = array_filter($users);
                if(empty($users)) $users = $this->user->getPairs('devfirst|noclosed');
            }
        }
        else
        {
            $users = $this->user->getPairs('devfirst|noclosed');
        }

        $this->view->title      = $this->products[$bug->product] . $this->lang->colon . $this->lang->bug->assignedTo;
        $this->view->position[] = $this->lang->bug->assignedTo;

        $this->view->users   = $users;
        $this->view->bug     = $bug;
        $this->view->bugID   = $bugID;
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
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
        if($this->post->bugIDList)
        {
            $bugIDList     = $this->post->bugIDList;
            $bugIDList     = array_unique($bugIDList);
            $oldBugs       = $this->bug->getByList($bugIDList);
            $skipBugIDList = '';
            unset($_POST['bugIDList']);

            /* Remove condition mismatched bugs. */
            foreach($bugIDList as $key => $bugID)
            {
                $oldBug = $oldBugs[$bugID];
                if($branchID == $oldBug->branch)
                {
                    unset($bugIDList[$key]);
                    continue;
                }
                elseif($branchID != $oldBug->branch and !empty($oldBug->module))
                {
                    $skipBugIDList .= '[' . $bugID . ']';
                    unset($bugIDList[$key]);
                    continue;
                }
            }

            if(!empty($skipBugIDList))
            {
                echo js::alert(sprintf($this->lang->bug->noSwitchBranch, $skipBugIDList));
            }

            $allChanges = $this->bug->batchChangeBranch($bugIDList, $branchID, $oldBugs);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
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
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            $allChanges = $this->bug->batchChangeModule($bugIDList, $moduleID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch change the plan of bug.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function batchChangePlan($planID)
    {
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            $allChanges = $this->bug->batchChangePlan($bugIDList, $planID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $bugID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Batch update assign of bug.
     *
     * @param  int     $objectID  projectID|executionID
     * @param  string  $type      execution|project|product|my
     * @access public
     * @return void
     */
    public function batchAssignTo($objectID, $type = 'execution')
    {
        if(!empty($_POST) && isset($_POST['bugIDList']))
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);
            unset($_POST['bugIDList']);
            foreach($bugIDList as $bugID)
            {
                $this->loadModel('action');
                $changes = $this->bug->assign($bugID);
                if(dao::isError()) return print(js::error(dao::getError()));
                $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if($type == 'product' || $type == 'my') return print(js::reload('parent'));
        if($type == 'execution') return print(js::locate($this->createLink('execution', 'bug', "executionID=$objectID")));
        if($type == 'project')   return print(js::locate($this->createLink('project', 'bug', "projectID=$objectID")));
    }

    /**
     * confirm a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function confirmBug($bugID, $extra = '', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        if(!empty($_POST))
        {
            $changes = $this->bug->confirm($bugID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'bugConfirmed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($bugID);

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            if(isonlybody())
            {
                $execution    = $this->loadModel('execution')->getByID($bug->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and isset($execution->type) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent'));
                }
            }
            return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $productID = $bug->product;
        $this->bug->checkBugExecutionPriv($bug);
        $this->qa->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->confirmBug;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->confirmBug;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noclosed', $bug->assignedTo);
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
        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $this->bug->batchConfirm($bugIDList);
        if(dao::isError()) return print(js::error(dao::getError()));
        foreach($bugIDList as $bugID) $this->action->create('bug', $bugID, 'bugConfirmed');
        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Resolve a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function resolve($bugID, $extra = '', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        if($bug->execution) $execution = $this->loadModel('execution')->getByID($bug->execution);

        if(!empty($_POST))
        {
            $changes = $this->bug->resolve($bugID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);

            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $fileAction . $this->post->comment, $this->post->resolution . ($this->post->duplicateBug ? ':' . (int)$this->post->duplicateBug : ''));
            $this->action->logHistory($actionID, $changes);

            $bug = $this->bug->getById($bugID);

            $this->executeHooks($bugID);

            if($bug->toTask != 0)
            {
                /* If task is not finished, update it's status. */
                $task = $this->task->getById($bug->toTask);
                if($task->status != 'done')
                {
                    $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                    unset($_GET['onlybody']);
                    $cancelURL  = $this->createLink('bug', 'view', "bugID=$bugID");
                    return print(js::confirm(sprintf($this->lang->bug->remindTask, $bug->toTask), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                }
            }

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            if(isonlybody())
            {
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and isset($execution->type) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this', "typeof(parent.parent.setTitleWidth) == 'function' ? parent.parent.setTitleWidth() : parent.parent.location.reload()"));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $bugID));
            }
            else
            {
                return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
            }
        }

        $projectID  = $bug->project;
        $productID  = $bug->product;
        $users      = $this->user->getPairs('noclosed');
        $assignedTo = $bug->openedBy;
        if(!isset($users[$assignedTo])) $assignedTo = $this->bug->getModuleOwner($bug->module, $productID);
        unset($this->lang->bug->resolutionList['tostory']);

        $this->bug->checkBugExecutionPriv($bug);
        $this->qa->setMenu($this->products, $productID, $bug->branch);

        $this->config->moreLinks['duplicateBug'] = inlink('ajaxGetProductBugs', "productID={$productID}&bugID={$bugID}&type=json");

        $this->view->title          = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->bug            = $bug;
        $this->view->users          = $users;
        $this->view->assignedTo     = $assignedTo;
        $this->view->executions     = $this->loadModel('product')->getExecutionPairsByProduct($productID, $bug->branch ? "0,{$bug->branch}" : 0, 'id_desc', $projectID, 'stagefilter');
        $this->view->builds         = $this->loadModel('build')->getBuildPairs($productID, $bug->branch, 'withbranch,noreleased');
        $this->view->actions        = $this->action->getList('bug', $bugID);
        $this->view->execution      = isset($execution) ? $execution : '';
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
        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $changes   = $this->bug->batchResolve($bugIDList, $resolution, $resolvedBuild);
        if(dao::isError()) return print(js::error(dao::getError()));

        foreach($changes as $bugID => $bugChanges)
        {
            $actionID = $this->action->create('bug', $bugID, 'Resolved', '', $resolution);
            $this->action->logHistory($actionID, $bugChanges);
        }

        $this->loadModel('score')->create('ajax', 'batchOther');
        return print(js::locate($this->session->bugList, 'parent'));
    }

    /**
     * Activate a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function activate($bugID, $extra = '', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        if(!empty($_POST))
        {
            $changes = $this->bug->activate($bugID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));

            $files = $this->loadModel('file')->saveUpload('bug', $bugID);

            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID   = $this->action->create('bug', $bugID, 'Activated', $fileAction . $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($bugID);

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            if(isonlybody())
            {
                $execution    = $this->loadModel('execution')->getByID($bug->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and isset($execution->type) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent'));
                }
            }
            return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $productID = $bug->product;
        $this->bug->checkBugExecutionPriv($bug);
        $this->qa->setMenu($this->products, $productID, $bug->branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->activate;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noclosed', $bug->resolvedBy);
        $this->view->builds  = $this->loadModel('build')->getBuildPairs($productID, $bug->branch, 'noempty,noreleased', 0, 'execution', $bug->openedBuild);
        $this->view->actions = $this->action->getList('bug', $bugID);

        $this->display();
    }

    /**
     * Close a bug.
     *
     * @param  int    $bugID
     * @param  string $extra
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function close($bugID, $extra = '', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        if(!empty($_POST))
        {
            $changes = $this->bug->close($bugID, $extra);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->action->create('bug', $bugID, 'Closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);

            $this->dao->update(TABLE_BUG)->set('assignedTo')->eq('closed')->where('id')->eq((int)$bugID)->exec();

            $this->executeHooks($bugID);

            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            if(isonlybody())
            {
                $execution    = $this->loadModel('execution')->getByID($bug->execution);
                $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                if($this->app->tab == 'execution' and isset($execution->type) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($bug->execution, $execLaneType, 'id_desc', $regionID, $execGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                elseif($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                    $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);
                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"bug\", $kanbanData)"));
                }
                else
                {
                    return print(js::closeModal('parent.parent', 'this', "typeof(parent.parent.setTitleWidth) == 'function' ? parent.parent.setTitleWidth() : parent.parent.location.reload()"));
                }
            }
            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $bugID));
            }
            else
            {
                return print(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
            }
        }

        $productID = $bug->product;
        $this->bug->checkBugExecutionPriv($bug);
        $this->qa->setMenu($this->products, $productID, $bug->branch);

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
     * @param  string $excludeBugs
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBugs($bugID, $browseType = '', $excludeBugs = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get bug and queryID. */
        $bug     = $this->bug->getById($bugID);
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->bug->checkBugExecutionPriv($bug);

        /* Set the menu. */
        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        /* Hide plan and product in no product project. */
        if($bug->project and $this->app->tab != 'qa')
        {
            $project = $this->loadModel('project')->getByID($bug->project);
            if(!$project->hasProduct)
            {
                unset($this->config->bug->search['fields']['product']);
                if(!$project->multiple)
                {
                    unset($this->config->bug->search['fields']['execution']);
                    unset($this->config->bug->search['fields']['plan']);
                }
            }
        }

        /* Build the search form. */
        $actionURL = $this->createLink('bug', 'linkBugs', "bugID=$bugID&browseType=bySearch&excludeBugs=$excludeBugs&queryID=myQueryID", '', true);
        $this->bug->buildSearchForm($bug->product, $this->products, $queryID, $actionURL);

        /* Get bugs to link. */
        $bugs2Link = $this->bug->getBugs2Link($bugID, $browseType, $queryID, $pager, $excludeBugs);

        /* Assign. */
        $this->view->title      = $this->lang->bug->linkBugs . "BUG #$bug->id $bug->title - " . $this->products[$bug->product];
        $this->view->position[] = html::a($this->createLink('product', 'view', "productID=$bug->product"), $this->products[$bug->product]);
        $this->view->position[] = html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title);
        $this->view->position[] = $this->lang->bug->linkBugs;
        $this->view->bug        = $bug;
        $this->view->bugs2Link  = $bugs2Link;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->display();
    }

    /**
     * Batch close bugs.
     *
     * @param  int    $releaseID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function batchClose($releaseID = '', $viewType = '')
    {
        if($releaseID) $this->post->bugIDList = $this->post->unlinkBugs;
        if($this->post->bugIDList)
        {
            $bugIDList = $this->post->bugIDList;
            $bugIDList = array_unique($bugIDList);

            /* Reset $_POST. Do not unset that because the function of close need that in model. */
            $_POST = array();

            $closedBugs = array();
            $bugs = $this->bug->getByList($bugIDList);
            foreach($bugs as $bugID => $bug)
            {
                if($bug->status != 'resolved')
                {
                    if($bug->status != 'closed') $skipBugs[$bugID] = $bugID;
                    continue;
                }

                $changes = $this->bug->close($bugID);

                $actionID = $this->action->create('bug', $bugID, 'Closed');
                $this->action->logHistory($actionID, $changes);
                $closedBugs[] = $bugID;
            }

            $this->dao->update(TABLE_BUG)->set('assignedTo')->eq('closed')->where('id')->in($closedBugs)->exec();

            $this->loadModel('score')->create('ajax', 'batchOther');
            if(isset($skipBugs)) echo js::alert(sprintf($this->lang->bug->skipClose, join(',', $skipBugs)));
            if($viewType)
            {
                return print(js::locate($this->createLink($viewType, 'view', "releaseID=$releaseID&type=bug"), 'parent'));
            }
        }
        return print(js::reload('parent'));
    }

    /**
     * Batch activate bugs.
     *
     * @access public
     * @return void
     */
    public function batchActivate($productID, $branch = 0)
    {
        if($this->post->statusList)
        {
            $activateBugs = $this->bug->batchActivate();
            foreach($activateBugs as $bugID => $bug) $this->action->create('bug', $bugID, 'Activated', $bug['comment']);
            $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->bugList, 'parent'));
        }

        if(!$this->post->bugIDList) return print(js::locate($this->session->bugList, 'parent'));

        $bugIDList = array_unique($this->post->bugIDList);
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchActivate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->batchActivate;

        $this->view->bugs    = $bugs;
        $this->view->users   = $this->user->getPairs();
        $this->view->builds  = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty,noreleased');

        $this->display();
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
        $this->bug->checkBugExecutionPriv($bug);
        $this->dao->update(TABLE_BUG)->set('storyVersion')->eq($bug->latestStoryVersion)->where('id')->eq($bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'confirmed', '', $bug->latestStoryVersion);
        return print(js::reload('parent'));
    }

    /**
     * Delete a bug.
     *
     * @param  int    $bugID
     * @param  string $confirm  yes|no
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function delete($bugID, $confirm = 'no', $from = '')
    {
        $bug = $this->bug->getById($bugID);
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->bug->confirmDelete, inlink('delete', "bugID=$bugID&confirm=yes&from=$from")));
        }
        else
        {
            $this->bug->delete(TABLE_BUG, $bugID);
            if($bug->toTask != 0)
            {
                $task = $this->task->getById($bug->toTask);
                if(!$task->deleted)
                {
                    $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                    unset($_GET['onlybody']);
                    $cancelURL  = $this->createLink('bug', 'view', "bugID=$bugID");
                    return print(js::confirm(sprintf($this->lang->bug->remindTask, $bug->toTask), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                }
            }

            $this->executeHooks($bugID);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));

            if(isonlybody()) return print(js::reload('parent.parent'));

            if($from == 'taskkanban')
            {
                $execLaneType    = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($bug->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'bug' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban(\"bug\", $kanbanData)"));
            }

            $locateLink = $this->session->bugList ? $this->session->bugList : inlink('browse', "productID={$bug->product}");
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * AJAX: get bugs of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserBugs($userID = '', $id = '' , $appendID = 0)
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;
        $bugs    = $this->bug->getUserBugPairs($account, true, 0, '', '', $appendID);

        if($id) return print(html::select("bugs[$id]", $bugs, '', 'class="form-control"'));
        return print(html::select('bug', $bugs, '', 'class=form-control'));
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
        $account  = $this->bug->getModuleOwner($moduleID, $productID);
        $realName = '';
        if(!empty($account))
        {
            $user        = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($account)->fetch();
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $realName = $firstLetter . ($user->realname ? $user->realname : $account);
        }
        return print(json_encode(array($account, $realName)));
    }

    /**
     * AJAX: get team members of the executions as assignedTo list.
     *
     * @param  int    $executionID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo($executionID, $selectedUser = '')
    {
        $executionMembers = $this->user->getTeamMemberPairs($executionID, 'execution');

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(empty($selectedUser)) $selectedUser = $execution->QD;

        return print(html::select('assignedTo', $executionMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get team members of the latest executions of a product as assignedTo list.
     *
     * @param  int    $productID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxLoadExecutionTeamMembers($productID, $selectedUser = '')
    {
        $productMembers = $this->bug->getProductMemberPairs($productID);

        return print(html::select('assignedTo', $productMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * AJAX: get all users as assignedTo list.
     *
     * @param  string $selectedUser
     * @param  string $params   noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @access public
     * @return string
     */
    public function ajaxLoadAllUsers($selectedUser = '', $params = 'devfirst|noclosed')
    {
        $allUsers = $this->loadModel('user')->getPairs($params);

        return print(html::select('assignedTo', $allUsers, $selectedUser, 'class="form-control"'));
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
     * @param  string $browseType
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $browseType = '', $executionID = 0)
    {
        if($_POST)
        {
            $this->loadModel('transfer');
            $this->session->set('bugTransferParams', array('productID' => $productID, 'executionID' => $executionID, 'branch' => 'all'));
            if(!$productID or $browseType == 'bysearch')
            {
                $this->config->bug->datatable->fieldList['module']['dataSource']['method'] = 'getAllModulePairs';
                $this->config->bug->datatable->fieldList['module']['dataSource']['params'] = 'bug';

                if($executionID)
                {
                    $object    = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
                    $projectID = $object->type == 'project' ? $object->id : $object->parent;
                    $this->config->bug->datatable->fieldList['project']['dataSource']   = array('module' => 'project', 'method' => 'getPairsByIdList', 'params' => $projectID);
                    $this->config->bug->datatable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs', 'params' => $projectID);
                }
            }

            $this->transfer->export('bug');
            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }
        $product = $this->loadModel('product')->getByID($productID);
        if(isset($product->type) and $product->type == 'normal') $this->config->bug->exportFields = str_replace('branch,', '', $this->config->bug->exportFields);

        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            if(empty($execution->multiple)) $this->config->bug->exportFields = str_replace('execution,', '', $this->config->bug->exportFields);
            if(!empty($product->shadow)) $this->config->bug->exportFields = str_replace('product,', '', $this->config->bug->exportFields);
        }

        $fileName = $this->lang->bug->common;
        if($executionID)
        {
            $executionName = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('name');
            $fileName      = $executionName . $this->lang->dash . $fileName;
        }
        else
        {
            $productName = !empty($product->name) ? $product->name : '';
            $browseType  = isset($this->lang->bug->featureBar['browse'][$browseType]) ? $this->lang->bug->featureBar['browse'][$browseType] : zget($this->lang->bug->moreSelects, $browseType, '');

            $fileName = $productName . $this->lang->dash . $browseType . $fileName;
        }

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->bug->exportFields;
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
        return print(json_encode($bug));
    }

    /**
     * Ajax get bug field options for auto test.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetBugFieldOptions($productID, $executionID = 0)
    {
        $modules  = $this->loadModel('tree')->getOptionMenu($productID, 'bug');
        $builds   = $this->loadModel('build')->getBuildPairs($productID, 'all', 'noreleased', $executionID, 'execution');
        $type     = $this->lang->bug->typeList;
        $pri      = $this->lang->bug->priList;
        $severity = $this->lang->bug->severityList;

        return print(json_encode(array('modules' => $modules, 'categories' => $type, 'versions' => $builds, 'severities' => $severity, 'priorities' => $pri)));
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
    public function ajaxGetDropMenu($productID, $module, $method, $extra = '')
    {
        $products = array();
        if(!empty($extra)) $products = $this->product->getProducts($extra, 'all', 'program desc, line desc, ');

        if($this->config->systemMode == 'ALM')
        {
            $this->view->programs = $this->loadModel('program')->getPairs(true);
            $this->view->lines    = $this->product->getLinePairs();
        }

        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->products  = $products;
        $this->view->projectID = $this->session->project;
        $this->display();
    }

    /**
     * Ajax get product members.
     *
     * @param  int    $productID
     * @param  string $selectedUser
     * @param  int    $branchID
     * @access public
     * @return string
     */
    public function ajaxGetProductMembers($productID, $selectedUser = '', $branchID = '')
    {
        $productMembers = $this->bug->getProductMemberPairs($productID, $branchID);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->loadModel('user')->getPairs('devfirst|noclosed');

        return print(html::select('assignedTo', $productMembers, $selectedUser, 'class="form-control"'));
    }

    /**
     * Ajax get product bugs.
     *
     * @param  int     $productID
     * @param  int     $bugID
     * @param  string  $type
     * @access public
     * @return string
     */
    public function ajaxGetProductBugs($productID, $bugID, $type = 'html')
    {
        $search      = $this->get->search;
        $limit       = $this->get->limit ? $this->get->limit : $this->config->maxCount;
        $product     = $this->loadModel('product')->getById($productID);
        $bug         = $this->bug->getById($bugID);
        $branch      = $product->type == 'branch' ? ($bug->branch > 0 ? $bug->branch . ',0' : '0') : '';
        $productBugs = $this->bug->getProductBugPairs($productID, $branch, $search, $limit);

        unset($productBugs[$bugID]);

        if($type == 'json') return print(helper::jsonEncode($productBugs));
        return print(html::select('duplicateBug', $productBugs, '', "class='form-control' placeholder='{$this->lang->bug->duplicateTip}'"));
    }

    /**
     * Ajax get project team members.
     *
     * @param  int    $projectID
     * @param  string $selectedUser
     * @access public
     * @return string
     */
    public function ajaxGetProjectTeamMembers($projectID, $selectedUser = '')
    {
        $users       = $this->loadModel('user')->getPairs('noclosed|all');
        $teamMembers = empty($projectID) ? array() : $this->loadModel('project')->getTeamMemberPairs($projectID);
        foreach($teamMembers as $account => $member) $teamMembers[$account] = $users[$account];

        return print(html::select('assignedTo', $teamMembers, $selectedUser, 'class="form-control"'));
    }


    /**
     * Ajax get execution lang.
     *
     * @param  int  $projectID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionLang($projectID)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($project->model == 'kanban') return print($this->lang->bug->kanban);
        return print($this->lang->bug->execution);
    }

    /**
     * Ajax get released builds.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @access public
     * @return string
     */
    public function ajaxGetReleasedBuilds($productID, $branch = 'all')
    {
        $releasedBuilds = $this->loadModel('release')->getReleasedBuilds($productID, $branch);

        return print(helper::jsonEncode($releasedBuilds));
    }

    /**
     * Ajax get relation cases.
     *
     * @param  int        $bugID
     * @access public
     * @return string
     */
    public function ajaxGetProductCases($bugID)
    {
        $search = $this->get->search;
        $limit  = $this->get->limit;

        $bug = $this->bug->getByID($bugID);

        $cases = $this->loadmodel('testcase')->getPairsByProduct($bug->product, array(0, $bug->branch), $search, $limit);

        return print(helper::jsonEncode($cases));
    }
}
