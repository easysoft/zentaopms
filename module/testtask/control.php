<?php
/**
 * The control file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class testtask extends control
{
    private $products = array();

    /**
     * Construct function, load product module, assign products to view auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getPairs();
    }

    /**
     * Index page, header to browse.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('testtask', 'browse'));
    }

    /**
     * Browse test tasks. 
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($productID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->testtask->setMenu($this->products, $productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]    = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testtask->common;
        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->tasks         = $this->testtask->getProductTasks($productID);
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');

        $this->display();
    }

    /**
     * Create a test task.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $taskID = $this->testtask->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('testtask', $taskID, 'opened');
            die(js::locate($this->createLink('testtask', 'browse', "productID=$productID"), 'parent'));
        }

        /* Set menu. */
        $productID  = $this->product->saveState($productID, $this->products);
        $this->testtask->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->create;

        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * View a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        /* Get test task, and set menu. */
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->view;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->view;

        $this->view->productID = $productID;
        $this->view->task      = $task;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions   = $this->loadModel('action')->getList('testtask', $taskID);

        $this->display();
    }

    /**
     * Browse cases of a test task.
     * 
     * @param  int    $taskID 
     * @param  string $browseType  bymodule|all|assignedtome
     * @param  int    $param 
     * @access public
     * @return void
     */
    public function cases($taskID, $browseType = 'byModule', $param = 0)
    {
        /* Save the session. */
        $this->app->loadLang('testcase');
        $this->session->set('caseList', $this->app->getURI(true));

        /* Set the browseType and moduleID. */
        $browseType = strtolower($browseType);
        $moduleID  = ($browseType == 'bymodule') ? (int)$param : 0;

        /* Get task and product info, set menu. */
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $modules = '';
            if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
            $this->view->runs      = $this->testtask->getRuns($taskID, $modules);
        }
        elseif($browseType == 'assignedtome')
        {
            $this->view->runs = $this->testtask->getUserRuns($taskID, $this->session->user->account);
        }

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->cases;

        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->task        = $task;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->moduleTree  = $this->loadModel('tree')->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createTestTaskLink'), $extra = $taskID);
        $this->view->browseType  = $browseType;
        $this->view->taskID      = $taskID;
        $this->view->moduleID    = $moduleID;

        $this->display();
    }

    /**
     * Edit a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function edit($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "taskID=$taskID"), 'parent'));
        }

        /* Get task info. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->edit;

        $this->view->task      = $task;
        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->users     = $this->loadModel('user')->getPairs();

        $this->display();
    }

    /**
     * Delete a test task.
     * 
     * @param  int    $taskID 
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($taskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testtask->confirmDelete, inlink('delete', "taskID=$taskID&confirm=yes")));
        }
        else
        {
            $task = $this->testtask->getByID($taskID);
            $this->testtask->delete(TABLE_TESTTASK, $taskID);
            die(js::locate(inlink('browse', "product=$task->product"), 'parent'));
        }
    }

    /**
     * Link cases to a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function linkCase($taskID)
    {
        if(!empty($_POST))
        {
            $this->testtask->linkCase($taskID);
            $this->locate(inlink('cases', "taskID=$taskID"));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get task and product id. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['product']['values']= array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['actionURL'] = inlink('linkcase', "taskID=$taskID");
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->testcase->search);

        /* Save session. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->linkCase;

        /* Get cases. */
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = str_replace("`product` = 'all'", '1', $this->session->testcaseQuery); // If search all product, replace product = all to 1=1
        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs('case');
        $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
            ->andWhere('product')->eq($productID)
            ->andWhere('id')->notIN($linkedCases)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Remove a case from test task.
     * 
     * @param  int    $rowID 
     * @access public
     * @return void
     */
    public function unlinkCase($rowID)
    {
        $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq((int)$rowID)->exec();
        die(js::reload('parent'));
    }

    /**
     * Run case.
     * 
     * @param  int    $runID 
     * @access public
     * @return void
     */
    public function runCase($runID)
    {
        if(!empty($_POST))
        {
            $this->testtask->createResult($runID);
            if(dao::isError()) die(js::error(dao::getError()));
            echo js::reload('parent');
            die(js::closeWindow());
        }

        $this->view->run = $this->testtask->getRunById($runID);
        die($this->display());
    }

    /**
     * View test results of a test run.
     * 
     * @param  int    $runID 
     * @access public
     * @return void
     */
    public function results($runID)
    {
        $this->view->run     = $this->testtask->getRunById($runID);
        $this->view->results = $this->testtask->getRunResults($runID);
        die($this->display());
    }

    /**
     * Batch assign cases.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function batchAssign($taskID)
    {
        $this->dao->update(TABLE_TESTRUN)
            ->set('assignedTo')->eq($this->post->assignedTo)
            ->where('task')->eq((int)$taskID)
            ->andWhere('`case`')->in($this->post->cases)
            ->exec();
        die(js::reload('parent'));
    }
}
