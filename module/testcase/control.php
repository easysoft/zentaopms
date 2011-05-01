<?php
/**
 * The control file of case currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class testcase extends control
{
    private $products = array();

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
        $this->view->products = $this->products = $this->product->getPairs();
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
    public function browse($productID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $productID = $this->product->saveState($productID, $this->products);
        $moduleID  = ($browseType == 'bymodule') ? (int)$param : 0;
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu, save session. */
        $this->testcase->setMenu($this->products, $productID);
        $this->session->set('caseList', $this->app->getURI(true));
        $this->session->set('productID', $productID);
        $this->session->set('moduleID', $moduleID);
        $this->session->set('browseType', $browseType);
        $this->session->set('orderBy', $orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* By module or all cases. */
        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $childModuleIds    = $this->tree->getAllChildId($moduleID);
            $this->view->cases = $this->testcase->getModuleCases($productID, $childModuleIds, $orderBy, $pager);
        }
        /* Cases need confirmed. */
        elseif($browseType == 'needconfirm')
        {
            $this->view->cases = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->orderBy($orderBy)
                ->fetchAll();
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('testcaseQuery', $query->sql);
                    $this->session->set('testcaseForm', $query->form);
                }
                else
                {
                    $this->session->set('testcaseQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
            }

            $caseQuery = str_replace("`product` = 'all'", '1', $this->session->testcaseQuery); // If product is all, change it to 1=1.
            $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where($caseQuery)
                ->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        /* save session .*/
        $sql = $this->dao->get();
        $sql = explode('WHERE', $sql);
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('testcaseReport', $sql[0]);

        /* Build the search form. */
        $this->config->testcase->search['params']['product']['values']= array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['actionURL'] = $this->createLink('testcase', 'browse', "productID=$productID&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['queryID']   = $queryID;
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->testcase->search);

        /* Assign. */
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]    = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testcase->common;
        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->moduleTree    = $this->tree->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createCaseLink'));
        $this->view->moduleID      = $moduleID;
        $this->view->pager         = $pager;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->treeClass     = $browseType == 'bymodule' ? '' : 'hidden';

        $this->display();
    }

    /**
     * Create a test case.
     * 
     * @param  int   $productID 
     * @param  int   $moduleID 
     * @param  int   $testcaseID
     * @access public
     * @return void
     */
    public function create($productID, $moduleID = 0, $testcaseID = 0)
    {
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $caseID = $this->testcase->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            $this->action->create('case', $caseID, 'Opened');
            die(js::locate($this->createLink('testcase', 'browse', "productID=$_POST[product]&browseType=byModule&param=$_POST[module]"), 'parent'));
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Set productID and currentModuleID. */
        $productID       = $this->product->saveState($productID, $this->products);
        $currentModuleID = (int)$moduleID;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $productID);

        /* Init vars. */
        $type     = '';
        $stage    = '';
        $pri      = 0;
        $storyID  = 0;
        $title    = '';
        $keywords = '';
        $steps    = array();

        if($testcaseID > 0)
        {
            $testcase  = $this->testcase->getById($testcaseID);
            $productID = $testcase->product;
            $type      = $testcase->type ? $testcase->type : 'feature';
            $stage     = $testcase->stage;
            $pri       = $testcase->pri;
            $storyID   = $testcase->story;
            $title     = $testcase->title;
            $keywords  = $testcase->keywords;
            $steps     = $testcase->steps;
        }

        /* Padding the steps to the default steps count. */
        if(count($steps) < $this->config->testcase->defaultSteps)
        {
            $paddingCount = $this->config->testcase->defaultSteps - count($steps);
            $step->desc   = '';
            $step->expect = '';
            for($i = 1; $i <= $paddingCount; $i ++) $steps[] = $step;
        }

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->testcase->create;

        $users = $this->user->getPairs();
        $this->view->header           = $header;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->users            = $users;           
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->stories          = $this->story->getProductStoryPairs($productID);
        $this->view->type             = $type;
        $this->view->stage            = $stage;
        $this->view->pri              = $pri;
        $this->view->storyID          = $storyID;
        $this->view->title            = $title;
        $this->view->keywords         = $keywords;
        $this->view->steps            = $steps;

        $this->display();
    }

    /**
     * View a test case.
     * 
     * @param  int   $caseID 
     * @param  int   $version 
     * @access public
     * @return void
     */
    public function view($caseID, $version = 0)
    {
        $case = $this->testcase->getById($caseID, $version);
        if(!$case) die(js::error($this->lang->notFound) . js::locate('back'));

        $productID = $case->product;
        $this->testcase->setMenu($this->products, $productID);

        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->view;
        $this->view->position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testcase->view;

        $this->view->case        = $case;
        $this->view->productName = $this->products[$productID];
        $this->view->modulePath  = $this->tree->getParents($case->module);
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->loadModel('action')->getList('case', $caseID);

        $this->display();
    }

    /**
     * Edit a case.
     * 
     * @param  int   $caseID 
     * @access public
     * @return void
     */
    public function edit($caseID)
    {
        $this->loadModel('story');

        if(!empty($_POST))
        {
            $changes = $this->testcase->update($caseID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('testcase', $caseID);
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
            $step->desc   = '';
            $step->expect = '';
            $case->steps[] = $step;
        }
        $productID       = $case->product;
        $currentModuleID = $case->module;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->edit;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->testcase->edit;

        /* Set menu. */
        $this->testcase->setMenu($this->products, $productID);

        $users = $this->user->getPairs();
        $this->view->header           = $header;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->users            = $users;
        $this->view->stories          = $this->story->getProductStoryPairs($productID);

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->case     = $case;
        $this->view->actions  = $this->loadModel('action')->getList('case', $caseID);

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
            die(js::locate($this->session->caseList, 'parent'));
        }
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
     * @param  string $productID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($productID, $orderBy)
    {
        $fields   = array();
        $users    = $this->loadModel('user')->getPairs('noletter');
        $products = $this->loadModel('product')->getPairs();
        $relatedModules = $this->dao->select('id, name')->from(TABLE_MODULE)->fetchPairs();
        $relatedStories = $this->dao->select('id, title')->from(TABLE_STORY)->fetchPairs();
        $relatedCases   = $this->dao->select('id, title')->from(TABLE_CASE)->fetchPairs();

        /* get the fields of task module from lang. */
        $fields = array(
            'id'             => $this->lang->testcase->id, 
            'step'           => $this->lang->testcase->stepDesc . $this->lang->testcase->stepExpect,
            'product'        => $this->lang->testcase->product, 
            'module'         => $this->lang->testcase->module, 
            'story'          => $this->lang->testcase->story,
            'storyVersion'   => $this->lang->testcase->storyVersion,
            'title'          => $this->lang->testcase->title, 
            'keywords'       => $this->lang->testcase->keywords, 
            'pri'            => $this->lang->testcase->pri, 
            'type'           => $this->lang->testcase->type, 
            'stage'          => $this->lang->testcase->stage, 
            'howRun'         => $this->lang->testcase->howRun, 
            'scriptedBy'     => $this->lang->testcase->scriptedBy, 
            'scriptedDate'   => $this->lang->testcase->scriptedDate, 
            'scriptStatus'   => $this->lang->testcase->scriptedStatus, 
            'scriptLocation' => $this->lang->testcase->scriptedLocation, 
            'status'         => $this->lang->testcase->status, 
            'frequency'      => $this->lang->testcase->frequency,
            'order'          => $this->lang->testcase->order, 
            'openedBy'       => $this->lang->testcase->openedBy, 
            'openedDate'     => $this->lang->testcase->openedDate, 
            'lastEditedBy'   => $this->lang->testcase->lastEditedBy, 
            'lastEditedDate' => $this->lang->testcase->lastEditedDate, 
            'version'        => $this->lang->testcase->version, 
            'linkCase'       => $this->lang->testcase->linkCase
        );

        if($_POST)
        {
            $testcases = $this->testcase->getByQuery($productID, $this->session->testcaseReport, $orderBy);

            foreach($testcases as $testcase)
            {
                $step = '';
                $i    = 1;
                $testcaseSteps = $this->dao->select('`desc`, expect')->from(TABLE_CASESTEP)->where('`case`')->eq($testcase->id)->fetchAll();
                foreach($testcaseSteps as $testcaseStep)
                {
                    $step   .= $i . '、' . $this->lang->testcase->stepDesc . ':' . $testcaseStep->desc . $this->lang->testcase->stepExpect . ':' . $testcaseStep->expect . "<br />";
                    $i++;
                }
                $testcase->company = $step;

                if($_POST['fileType'] == 'html')
                {
                    $legendAttatchs = $this->dao->select('pathname, title')->from(TABLE_FILE)->where('objectType')->eq('task')->andWhere('objectID')->eq($testcase->id)->fetchAll();
                    if($legendAttatchs)
                    {
                        foreach($legendAttatchs as $legendAttatch) 
                        {
                            $legendAttatch->pathname = "http://" . $_SERVER['HTTP_HOST'] . $this->config->webRoot . "data/upload/$testcase->company/" . $legendAttatch->pathname;
                            $testcase->legendAttatchs  .= "<a href=$legendAttatch->pathname>" . $legendAttatch->title . "</a><br />";
                        }
                    }
                }
                else if($_POST['fileType'] == 'csv')
                {
                    $testcase->company = str_replace("&lt;br /&gt;", "\n", $testcase->company);
                    $testcase->company = str_replace("<br />", "\n", $testcase->company);
                    $testcase->company = str_replace("&nbsp;", " ", $testcase->company);
                    $testcase->company = str_replace('"', '""', $testcase->company);
                }

                /* drop some field that is not needed. */
                unset($testcase->path);

                /* fill some field with useful value. */
                $testcase->module   = isset($relatedModules[$testcase->module]) ? $relatedModules[$testcase->module] : '';
                $testcase->story    = isset($relatedStories[$testcase->story]) ? $relatedStories[$testcase->story] : '';
                $testcase->linkCase = isset($relatedCases[$testcase->linkCase]) ? $relatedCases[$testcase->linkCase] : '';

                $testcase->product        = $products[$testcase->product];
                $testcase->pri            = $this->lang->testcase->priList[$testcase->pri];
                $testcase->type           = $this->lang->testcase->typeList[$testcase->type];
                $testcase->stage          = $this->lang->testcase->stageList[$testcase->stage];
                $testcase->scriptedBy     = $users[$testcase->scriptedBy] ;
                $testcase->status         = $this->lang->testcase->statusList[$testcase->status];
                $testcase->openedBy       = $users[$testcase->openedBy];
                $testcase->openedDate     = substr($testcase->openedDate, 0, 10);
                $testcase->lastEditedBy   = $users[$testcase->lastEditedBy];
                $testcase->lastEditedDate = substr($testcase->lastEditedDate, 0, 10);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $testcases);
            if($this->post->fileType == 'csv')  $this->fetch('file', 'export2CSV', $_POST);
            if($this->post->fileType == 'xml')  $this->fetch('file', 'export2XML', $_POST);
            if($this->post->fileType == 'html') $this->fetch('file', 'export2HTML', $_POST);
        }

        $this->display();
    }
}
