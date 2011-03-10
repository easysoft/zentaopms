<?php
/**
 * The control file of bug currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class bug extends control
{
    private $products = array();

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
        $this->products = $this->product->getPairs();
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
    public function browse($productID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $productID  = $this->product->saveState($productID, $this->products);
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu and save session. */
        $this->bug->setMenu($this->products, $productID);
        $this->session->set('bugList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $bugs = array();
        if($browseType == 'all')
        {
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == "bymodule")
        {
            $childModuleIds = $this->tree->getAllChildId($moduleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds, $orderBy, $pager);
        }
        elseif($browseType == 'assigntome')
        {
            $bugs = $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'openedbyme')
        {
            $bugs = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'resolvedbyme')
        {
            $bugs = $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'assigntonull')
        {
            $bugs = $this->dao->findByAssignedTo('')->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'unresolved')
        {
            $bugs = $this->dao->findByStatus('active')->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'longlifebugs')
        {
            $bugs = $this->dao->findByLastEditedDate("<", date(DT_DATE1, strtotime('-7 days')))->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->andWhere('openedDate')->lt(date(DT_DATE1,strtotime('-7 days')))
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'postponedbugs')
        {
            $bugs = $this->dao->findByResolution('postponed')->from(TABLE_BUG)->andWhere('product')->eq($productID)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'needconfirm')
        {
            $bugs = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->orderBy($orderBy)
                ->fetchAll();
        }
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('bugQuery', $query->sql);
                    $this->session->set('bugForm', $query->form);
                }
                else
                {
                    $this->session->set('bugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->bugQuery == false) $this->session->set('bugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", '1', $this->session->bugQuery); // Search all product.
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where($bugQuery)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        /* Process the sql, get the conditon partion, save it to session. Thus the report page can use the same condition. */
        if($browseType != 'needconfirm')
        {
            $sql = explode('WHERE', $this->dao->get());
            $sql = explode('ORDER', $sql[1]);
            $this->session->set('bugReportCondition', $sql[0]);
        }

        /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('bug', 'browse', "productID=$productID&browseType=bySearch&queryID=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['product']['values']       = array($productID => $this->products[$productID], 'all' => $this->lang->bug->allProduct);
        $this->config->bug->search['params']['module']['values']        = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairs($productID);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->build->getProductBuildPairs($productID);
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->bug->search);

        $users = $this->user->getPairs('noletter');

        /* Get custome fields. */
        $customFields = $this->cookie->bugFields != false ? $this->cookie->bugFields : $this->config->bug->list->defaultFields;
        $customed     = !($customFields == $this->config->bug->list->defaultFields);
        
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->common;

        $this->view->header      = $header;
        $this->view->position    = $position;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->moduleTree  = $this->tree->getTreeMenu($productID, $viewType = 'bug', $startModuleID = 0, array('treeModel', 'createBugLink'));
        $this->view->browseType  = $browseType;
        $this->view->bugs        = $bugs;
        $this->view->users       = $users;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->moduleID    = $moduleID;
        $this->view->customed    = $customed;
        $this->view->customFields= explode(',', str_replace(' ', '', trim($customFields)));

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
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
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
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        if(!empty($_POST))
        {
            $bugID = $this->bug->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Opened');
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'browse', "productID={$this->post->product}&type=byModule&param={$this->post->module}"), 'parent'));
        }

        /* Get product, then set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->bug->setMenu($this->products, $productID);

        /* Remove the unused types. */
        unset($this->lang->bug->typeList['designchange']);
        unset($this->lang->bug->typeList['newfeature']);
        unset($this->lang->bug->typeList['trackthings']);

        /* Init vars. */
        $moduleID   = 0;
        $projectID  = 0;
        $taskID     = 0;
        $storyID    = 0;
        $buildID    = 0;
        $caseID     = 0;
        $runID      = 0;
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

        /* If bugID setted, use this bug as template. */
        if(isset($bugID)) 
        {
            $bug = $this->bug->getById($bugID);
            extract((array)$bug);
            $projectID = $bug->project;
            $moduleID  = $bug->module;
            $taskID    = $bug->task;
            $storyID   = $bug->story;
            $buildID   = $bug->openedBuild;
            $severity  = $bug->severity;
            $type      = $bug->type;
        }

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

        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->create;

        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->stories          = $stories;
        $this->view->users            = $this->user->getPairs('noclosed,nodeleted');
        $this->view->projects         = $this->product->getProjectPairs($productID);
        $this->view->builds           = $builds;
        $this->view->tasks            = $this->loadModel('task')->getProjectTaskPairs($projectID);
        $this->view->moduleID         = $moduleID;
        $this->view->projectID        = $projectID;
        $this->view->taskID           = $taskID;
        $this->view->storyID          = $storyID;
        $this->view->buildID          = $buildID;
        $this->view->caseID           = $caseID;
        $this->view->title            = $title;
        $this->view->steps            = $steps;
        $this->view->os               = $os;
        $this->view->browser          = $browser;
        $this->view->assignedTo       = $assignedTo;
        $this->view->mailto           = $mailto;
        $this->view->keywords         = $keywords;
        $this->view->severity         = $severity;
        $this->view->type             = $type;    

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
        $bug = $this->bug->getById($bugID);
        if(!$bug) die(js::error($this->lang->notFound) . js::locate('back'));

        /* Get product info. */
        $productID   = $bug->product;
        $productName = $this->products[$productID];
        
        /* Set menu. */
        $this->bug->setMenu($this->products, $productID);

        /* Header and positon. */
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->view;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $this->view->position[]    = $this->lang->bug->view;

        /* Assign. */
        $this->view->productID   = $productID;
        $this->view->productName = $productName;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bug         = $bug;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getProductBuildPairs($productID);

        $this->display();
    }

    /**
     * Edit a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function edit($bugID)
    {
        if(!empty($_POST))
        {
            $changes  = $this->bug->update($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('bug', $bugID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($bugID, $actionID);
            }
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* Get the info of bug, current product and modue. */
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $currentModuleID = $bug->module;

        /**
         * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task. 
         * These thress types if upgrade from bugfree2.x.
         */
        if($bug->type != 'designchange') unset($this->lang->bug->typeList['designchange']);
        if($bug->type != 'newfeature')   unset($this->lang->bug->typeList['newfeature']);
        if($bug->type != 'trackthings')  unset($this->lang->bug->typeList['trackthings']);

        /* Set the menu. */
        $this->bug->setMenu($this->products, $productID);

        /* Set header and position. */
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->edit;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->edit;

        /* Assign. */
        $this->view->bug              = $bug;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->projects         = $this->product->getProjectPairs($bug->product);
        $this->view->stories          = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product);
        $this->view->tasks            = $this->task->getProjectTaskPairs($bug->project);
        $this->view->users            = $this->user->appendDeleted($this->user->getPairs('nodeleted'), "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy");
        $this->view->openedBuilds     = $this->loadModel('build')->getProductBuildPairs($productID, 'noempty');
        $this->view->resolvedBuilds   = array('' => '') + $this->view->openedBuilds;
        $this->view->actions          = $this->action->getList('bug', $bugID);
        $this->view->templates        = $this->bug->getUserBugTemplates($this->app->user->account);

        $this->display();
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
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $this->post->comment, $this->post->resolution);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $this->view->position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->bug->resolve;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted');
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->actions = $this->action->getList('bug', $bugID);
        $this->display();
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
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $this->view->position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->bug->activate;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs('nodeleted');
        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($productID);
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
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $this->bug->setMenu($this->products, $productID);

        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->close;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->close;

        $this->view->bug     = $bug;
        $this->view->users   = $this->user->getPairs();
        $this->view->actions = $this->action->getList('bug', $bugID);
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
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->bug->confirmDelete, inlink('delete', "bugID=$bugID&confirm=yes")));
        }
        else
        {
            $this->bug->delete(TABLE_BUG, $bugID);
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
     * Custom fields.
     * 
     * @access public
     * @return void
     */
    public function customFields()
    {
        if($_POST)
        {
            $customFields = $this->post->customFields;
            $customFields = join(',', $customFields);
            setcookie('bugFields', $customFields, $this->config->cookieLife, $this->config->webRoot);
            die(js::reload('parent'));
        }

        $customFields = $this->cookie->bugFields ? $this->cookie->bugFields : $this->config->bug->list->defaultFields;

        $this->view->allFields     = $this->bug->getFieldPairs($this->config->bug->list->allFields);
        $this->view->customFields  = $this->bug->getFieldPairs($customFields);
        $this->view->defaultFields = $this->bug->getFieldPairs($this->config->bug->list->defaultFields);
        die($this->display());
    }

    /**
     * AJAX: get bugs of a user in html select.
     * 
     * @param  string $account 
     * @access public
     * @return string
     */
    public function ajaxGetUserBugs($account = '')
    {
        if($account == '') $account = $this->app->user->account;
        $bugs = $this->bug->getUserBugPairs($account);
        die(html::select('bug', $bugs, '', 'class=select-1'));
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
        if($moduleID) die($this->dao->findByID($moduleID)->from(TABLE_MODULE)->fetch('owner'));
        die($this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('bugOwner'));
    }

    /**
     * Send email.
     * 
     * @param  int    $bugID 
     * @param  int    $actionID 
     * @access private
     * @return void
     */
    private function sendmail($bugID, $actionID)
    {
        /* Set toList and ccList. */
        $bug    = $this->bug->getByID($bugID);
        $toList = $bug->assignedTo;
        $ccList = trim($bug->mailto, ',');
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
        $this->view->bug    = $bug;
        $this->view->action = $action;
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send it. */
        $this->loadModel('mail')->send($toList, 'BUG #' . $bug->id . $this->lang->colon . $bug->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
}
