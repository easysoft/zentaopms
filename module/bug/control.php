<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('task');
        $this->products = $this->product->getPairs('nocode');
        if(empty($this->products))
        {
            echo js::alert($this->lang->product->errorNoProduct);
            die(js::locate($this->createLink('product', 'create')));
        }
        $this->view->products = $this->products;
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
     * @param  string $browseType 
     * @param  int    $param 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($productID = 0, $browseType = 'unclosed', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $productID  = $this->product->saveState($productID, $this->products);
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu and save session. */
        $this->bug->setMenu($this->products, $productID);
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

        $projects = $this->loadModel('project')->getPairs();
        $projects[0] = '';

        /* Get bugs. */
        $bugs = array();
        if($browseType == 'all') $bugs = $this->bug->getAllBugs($productID, $projects, $sort, $pager);
        elseif($browseType == "bymodule")
        {
            $childModuleIds = $this->tree->getAllChildId($moduleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds, $projects, $sort, $pager);
        }
        elseif($browseType == 'assigntome')    $bugs = $this->bug->getByAssigntome($productID, $projects, $sort, $pager);
        elseif($browseType == 'openedbyme')    $bugs = $this->bug->getByOpenedbyme($productID, $projects, $sort, $pager);
        elseif($browseType == 'resolvedbyme')  $bugs = $this->bug->getByResolvedbyme($productID, $projects, $sort, $pager);
        elseif($browseType == 'assigntonull')  $bugs = $this->bug->getByAssigntonull($productID, $projects, $sort, $pager);
        elseif($browseType == 'unconfirmed')   $bugs = $this->bug->getUnconfirmed($productID, $projects, $sort, $pager);
        elseif($browseType == 'unresolved')    $bugs = $this->bug->getByStatus($productID, $projects, 'unresolved', $sort, $pager);
        elseif($browseType == 'unclosed')      $bugs = $this->bug->getByStatus($productID, $projects, 'unclosed', $sort, $pager);
        elseif($browseType == 'longlifebugs')  $bugs = $this->bug->getByLonglifebugs($productID, $projects, $sort, $pager);
        elseif($browseType == 'postponedbugs') $bugs = $this->bug->getByPostponedbugs($productID, $projects, $sort, $pager);
        elseif($browseType == 'needconfirm')   $bugs = $this->bug->getByNeedconfirm($productID, $projects, $sort, $pager);
        elseif($browseType == 'bysearch')      $bugs = $this->bug->getBySearch($productID, $projects, $queryID, $sort, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', $browseType == 'needconfirm' ? false : true);

        /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('bug', 'browse', "productID=$productID&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['product']['values']       = array($productID => $this->products[$productID], 'all' => $this->lang->bug->allProduct);
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($productID);
        $this->config->bug->search['params']['module']['values']        = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairs($productID);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->build->getProductBuildPairs($productID);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $users = $this->user->getPairs('noletter');

        /* Process the openedBuild and resolvedBuild fields. */
        $productIdList = array();
        foreach($bugs as $bug) $productIdList[$bug->id] = $bug->product;
        $builds = $this->loadModel('build')->getProductBuildPairs(array_unique($productIdList));
        foreach($bugs as $key => $bug) 
        {
            $openBuildIdList = explode(',', $bug->openedBuild);
            $openedBuild = '';
            foreach($openBuildIdList as $buildID)
            {
                $openedBuild .= isset($builds[$buildID]) ? $builds[$buildID] : $buildID;
                $openedBuild .= ',';
            }
            $bug->openedBuild   = rtrim($openedBuild, ',');
            $bug->resolvedBuild = isset($builds[$bug->resolvedBuild]) ? $builds[$bug->resolvedBuild] : $bug->resolvedBuild;
        }

        $memberPairs = $this->user->getPairs('noletter|nodeleted');
       
        $title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[] = $this->lang->bug->common;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->productID    = $productID;
        $this->view->productName  = $this->products[$productID];
        $this->view->builds       = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->moduleTree   = $this->tree->getTreeMenu($productID, $viewType = 'bug', $startModuleID = 0, array('treeModel', 'createBugLink'));
        $this->view->browseType   = $browseType;
        $this->view->bugs         = $bugs;
        $this->view->users        = $users;
        $this->view->pager        = $pager;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->moduleID     = $moduleID;
        $this->view->memberPairs  = $memberPairs;

        $this->display();
    }

    /**
     * The report page.
     * 
     * @param  int    $productID 
     * @param  string $browseType 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function report($productID, $browseType, $moduleID)
    {
        $this->loadModel('report');
        $this->view->charts   = array();
        $this->view->renderJS = '';

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->bug->$chartFunc();
                $chartOption = $this->lang->bug->report->$chart;
                $this->bug->mergeChartOption($chart);

                $chartXML  = $this->report->createSingleXML($chartData, $chartOption->graph);
                $this->view->charts[$chart] = $this->report->createJSChart($chartOption->swf, $chartXML, $chartOption->width, $chartOption->height);
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
            $this->view->renderJS = $this->report->renderJsCharts(count($this->view->charts));
        }

        $this->bug->setMenu($this->products, $productID);
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
    public function create($productID, $extras = '')
    {
        $this->view->users = $this->user->getPairs('nodeleted,devfirst');
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
            $this->sendmail($bugID, $actionID);

            $location = $this->createLink('bug', 'browse', "productID={$this->post->product}&type=byModule&param={$this->post->module}");
            $response['locate'] = isset($_SESSION['bugList']) ? $this->session->bugList : $location;
            $this->send($response);
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->bug->setMenu($this->products, $productID);

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
        $mailto     = '';
        $keywords   = '';
        $severity   = 3;
        $type       = 'codeerror';

        /* Parse the extras. */
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras);

        /* If set runID, get the last result info as the template. */
        if($runID > 0) $resultID = $this->dao->select('id')->from(TABLE_TESTRESULT)->where('run')->eq($runID)->orderBy('id desc')->limit(1)->fetch('id');
        if(isset($resultID) and $resultID > 0) extract($this->bug->getBugInfoFromResult($resultID));

        /* If set caseID and runID='', get the last result info as the template. */
        if($caseID > 0 && $runID == '') 
        { 
            $resultID = $this->dao->select('id')->from(TABLE_TESTRESULT)->where('`case`')->eq($caseID)->orderBy('date desc')->limit(1)->fetch('id'); 
            if(isset($resultID) and $resultID > 0) extract($this->bug->getBugInfoFromResult($resultID, $caseID, $version));
        }

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
        }

        /* If projectID is setted, get builds and stories of this project. */
        if($projectID)
        {
            $builds  = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, 'noempty');
            $stories = $this->story->getProjectStoryPairs($projectID);
        }
        else
        {
            $builds  = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty,release');
            $stories = $this->story->getProductStoryPairs($productID);
        }

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->create;

        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->stories          = $stories;
        $this->view->projects         = $this->product->getProjectPairs($productID, $params = 'nodeleted');
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
        $this->view->assignedTo       = $assignedTo;
        $this->view->mailto           = $mailto;
        $this->view->contactLists     = $this->user->getContactLists($this->app->user->account, 'withnote');
        $this->view->keywords         = $keywords;
        $this->view->severity         = $severity;
        $this->view->type             = $type;    

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
    public function batchCreate($productID, $projectID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $actions = $this->bug->batchCreate($productID);
            foreach($actions as $bugID => $actionID) $this->sendmail($bugID, $actionID);
            die(js::locate($this->session->bugList, 'parent'));
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->bug->setMenu($this->products, $productID);

        /* If projectID is setted, get builds and stories of this project. */
        if($projectID)
        {
            $builds  = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, 'noempty');
            $stories = $this->story->getProjectStoryPairs($projectID);
        }
        else
        {
            $builds  = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
            $stories = $this->story->getProductStoryPairs($productID);
        }

        if($this->session->bugImagesFile)
        {
            $extractPath = $this->session->bugImagesFile;
            if(is_dir($extractPath))
            {
                $titles = array();
                $files = glob($extractPath . '/*');
                sort($files);
                foreach($files as $fileName)
                {
                    $fileName = basename($fileName);
                    $titles[$fileName] = preg_replace('/^\d+_/', '', pathinfo($fileName, PATHINFO_FILENAME));
                }
                $this->view->titles = $titles;
            }
        }

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->batchCreate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->batchCreate;

        $this->view->productID        = $productID;
        $this->view->stories          = $stories;
        $this->view->builds           = $builds;
        $this->view->users            = $this->user->getPairs('nodeleted,devfirst');
        $this->view->projects         = $this->product->getProjectPairs($productID, $params = 'nodeleted');
        $this->view->projectID        = $projectID;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->moduleID         = $moduleID;
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
            die(js::locate('back'));
        }

        /* Update action. */
        if($bug->assignedTo == $this->app->user->account) $this->loadModel('action')->read('bug', $bugID);

        /* Set menu. */
        $this->bug->setMenu($this->products, $bug->product);

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
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getProductBuildPairs($productID);
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
                $this->sendmail($bugID, $actionID);
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
        $this->bug->setMenu($this->products, $productID);

        /* Set header and position. */
        $this->view->title      = $this->lang->bug->edit . "BUG #$bug->id $bug->title - " . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->edit;

        /* Assign. */
        if($projectID)
        {
            $this->view->openedBuilds     = $this->loadModel('build')->getProjectBuildPairs($projectID, $productID, 'noempty');
        }
        else
        {
            $this->view->openedBuilds     = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
        }
        $this->view->bug              = $bug;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->projects         = $this->product->getProjectPairs($bug->product);
        $this->view->stories          = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product);
        $this->view->tasks            = $this->task->getProjectTaskPairs($bug->project);
        $this->view->users            = $this->user->getPairs('nodeleted', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->resolvedBuilds   = array('' => '') + $this->view->openedBuilds;
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
    public function batchEdit($productID = 0)
    {
        if($this->post->titles)
        {
            $allChanges = $this->bug->batchUpdate();

            foreach($allChanges as $bugID => $changes)
            {
                if(empty($changes)) continue;

                $actionID = $this->action->create('bug', $bugID, 'Edited');
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($bugID, $actionID);

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

        /* The bugs of a product. */
        if($productID)
        {
            $product = $this->product->getByID($productID);

            /* Set product menu. */
            $this->bug->setMenu($this->products, $productID);
            $this->view->title      = $product->name . $this->lang->colon . "BUG" . $this->lang->bug->batchEdit;
            $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        }
        /* The bugs of my. */
        else
        {
            $this->lang->bug->menu = $this->lang->my->menu;
            $this->lang->set('menugroup.bug', 'my');
            $this->lang->bug->menuOrder = $this->lang->my->menuOrder;
            $this->loadModel('my')->setMenu();
            $this->view->position[] = html::a($this->createLink('my', 'bug'), $this->lang->my->bug);
            $this->view->title      = "BUG" . $this->lang->bug->batchEdit;
        }
        /* Initialize vars.*/
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIDList)->fetchAll('id');

        /* Judge whether the editedTasks is too large and set session. */
        $showSuhosinInfo = false;
        $showSuhosinInfo = $this->loadModel('common')->judgeSuhosinSetting(count($bugs), $this->config->bug->batchEdit->columns);
        $this->app->session->set('showSuhosinInfo', $showSuhosinInfo);
        if($showSuhosinInfo) $this->view->suhosinInfo = $this->lang->suhosinInfo;

        /* Assign. */
        $this->view->position[] = $this->lang->bug->common;
        $this->view->position[] = $this->lang->bug->batchEdit;
        $this->view->bugIDList  = $bugIDList;
        $this->view->productID  = $productID;
        $this->view->bugs       = $bugs;
        $this->view->users      = $this->user->getPairs('nodeleted,devfirst');

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
        $this->bug->setMenu($this->products, $bug->product);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->bug->assign($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);
            $this->sendmail($bugID, $actionID);

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
                $this->sendmail($bugID, $actionID);
            }
        }
        if($type == 'product') die(js::locate($this->createLink('bug', 'browse', "productID=$projectID")));
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
            $this->bug->confirm($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'bugConfirmed', $this->post->comment);
            $this->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $this->bug->setMenu($this->products, $productID);

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
            $this->sendmail($bugID, $actionID);
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
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $fileAction . $this->post->comment, $this->post->resolution);
            $this->sendmail($bugID, $actionID);

            $bug = $this->bug->getById($bugID);
            if($bug->toTask != 0) 
            {
                $confirmURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                unset($_GET['onlybody']);
                $cancelURL  = $this->createLink('bug', 'view', "bugID=$bugID");
                die(js::confirm(sprintf($this->lang->bug->remindTask, $bug->toTask), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
            } 
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->resolve;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted', $bug->openedBy);
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->actions = $this->action->getList('bug', $bugID);
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
        $bugIDList  = $this->post->bugIDList ? $this->post->bugIDList : die(js::locate($this->session->bugList, 'parent'));
        $this->bug->batchResolve($bugIDList, $resolution, $resolvedBuild);
        if(dao::isError()) die(js::error(dao::getError()));
        foreach($bugIDList as $bugID)
        {
            $actionID = $this->action->create('bug', $bugID, 'Resolved', '', $resolution);
            $this->sendmail($bugID, $actionID);
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
            $this->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->activate;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted', $bug->resolvedBy);
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
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
            $this->sendmail($bugID, $actionID);
            if(isonlybody()) die(js::closeModal('parent.parent'));
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->bug->close;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->bug->close;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('noletter');
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
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
                $this->sendmail($bugID, $actionID);
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
        if(dao::isError()) die(js::error(dao::getError()));
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
        $owner = '';
        if($moduleID) $owner = $this->dao->findByID($moduleID)->from(TABLE_MODULE)->fetch('owner');
        if(!$owner)   $owner = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('QD');
        die($owner);
    }

    /**
     * AJAX: get assignedTo list, make sure the members of the project at the first.
     * 
     * @param  int    $projectID 
     * @param  string $selectedUser 
     * @access public
     * @return string
     */
    public function ajaxLoadAssignedTo($projectID, $selectedUser = '')
    {
        $allUsers       = $this->loadModel('user')->getPairs('nodeleted, devfirst');
        $projectMembers = $this->loadModel('project')->getTeamMemberPairs($projectID);
        $assignedToList = array_merge($projectMembers, $allUsers);
        
        die(html::select('assignedTo', $assignedToList, $selectedUser, 'class="form-control"'));
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
     * Send email.
     * 
     * @param  int    $bugID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($bugID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Set toList and ccList. */
        $bug         = $this->bug->getByID($bugID);
        $productName = $this->products[$bug->product];
        $toList      = $bug->assignedTo;
        $ccList      = trim($bug->mailto, ',');
        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $bug->resolvedBy;
        }

        /* Get action info. */
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();
        if(strtolower($action->action) == 'opened') $action->comment = $bug->steps;

        /* Create the mail content. */
        if($action->action == 'opened') $action->comment = '';
        $this->view->bug    = $bug;
        $this->view->action = $action;
        $this->view->users  = $this->user->getPairs('noletter');

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send it. */
        $this->loadModel('mail')->send($toList, $productName . ':' . 'BUG #'. $bug->id . $this->lang->colon . $bug->title, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
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
            $bugLang   = $this->lang->bug;
            $bugConfig = $this->config->bug;

            /* Create field lists. */
            $fields = explode(',', $bugConfig->list->exportFields);
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
            $relatedModuleIdList = array();
            $relatedStoryIdList  = array();
            $relatedTaskIdList   = array();
            $relatedBugIdList    = array();
            $relatedCaseIdList   = array();
            $relatedBuildIdList  = array();

            foreach($bugs as $bug)
            {
                $relatedModuleIdList[$bug->module]    = $bug->module;
                $relatedStoryIdList[$bug->story]      = $bug->story;
                $relatedTaskIdList[$bug->task]        = $bug->task;
                $relatedCaseIdList[$bug->case]        = $bug->case;
                $relatedBugIdList[$bug->duplicateBug] = $bug->duplicateBug;

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
            $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedTasks   = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($relatedTaskIdList)->fetchPairs();
            $relatedBugs    = $this->dao->select('id, title')->from(TABLE_BUG)->where('id')->in($relatedBugIdList)->fetchPairs();
            $relatedCases   = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedBuilds  = array('trunk' => 'Trunk') + $this->dao->select('id, name')->from(TABLE_BUILD)->where('id')->in($relatedBuildIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('bug')->andWhere('objectID')->in(@array_keys($bugs))->fetchGroup('objectID');

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

                if(isset($bugLang->priList[$bug->pri]))               $bug->pri        = $bugLang->priList[$bug->pri];
                if(isset($bugLang->typeList[$bug->type]))             $bug->type       = $bugLang->typeList[$bug->type];
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
                        $fileURL = 'http://' . $this->server->http_host . $this->config->webRoot . "data/upload/{$this->app->company->id}/" . $file->pathname;
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

            $this->post->set('fields', $fields);
            $this->post->set('rows', $bugs);
            $this->post->set('kind', 'bug');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }
}
