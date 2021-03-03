<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */
class project extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('execution');
        $this->loadModel('group');
    }

    /**
     * Project create guide.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function createGuide($projectID = 0, $from = 'PRJ')
    {
        $this->view->from      = $from;
        $this->view->projectID = $projectID;
        $this->display();
    }

    /**
     * Update children user view.
     *
     * @param  int    $projectID
     * @param  array  $account
     * @access public
     * @return void
     */
    public function updateChildUserView($projectID = 0, $account = array())
    {
        $childPGMList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childPRJList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('project')->eq($projectID)->fetchPairs();

        if(!empty($childPGMList))  $this->user->updateUserView($childPGMList, 'project',  array($account));
        if(!empty($childPRJList))  $this->user->updateUserView($childPRJList, 'project',  array($account));
        if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', array($account));
    }

    /**
     * Export project.
     *
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($status, $orderBy)
    {
        if($_POST)
        {
            $projectLang   = $this->lang->project;
            $projectConfig = $this->config->project;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $projectConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($projectLang, $fieldName);
                unset($fields[$key]);
            }

            $projects = $this->project->getList($status, $orderBy, null);
            $users    = $this->loadModel('user')->getPairs('noletter');
            foreach($projects as $i => $project)
            {
                $project->PM       = zget($users, $project->PM);
                $project->status   = $this->processStatus('project', $project);
                $project->model    = zget($projectLang->modelList, $project->model);
                $project->product  = zget($projectLang->productList, $project->product);
                $project->budget   = $project->budget . zget($projectLang->unitList, $project->budgetUnit);

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$project->id},") === false) unset($projects[$i]);
                }
            }

            if(isset($this->config->bizVersion)) list($fields, $projectStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $projectStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $projects);
            $this->post->set('kind', 'project');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }

    /**
     * Ajax get project drop menu.
     *
     * @param  int     $projectID
     * @param  string  $module
     * @param  string  $method
     * @access public
     * @return void
     */
    public function ajaxGetPRJDropMenu($projectID = 0, $module, $method)
    {
        $closedProjects = $this->project->getPRJList(0, 'closed', 0, 'id_desc');

        $closedProjectNames = array();
        foreach($closedProjects as $project) $closedProjectNames = common::convert2Pinyin($closedProjectNames);

        $closedProjectsHtml = '';
        foreach($closedProjects as $project) $closedProjectsHtml .= html::a($this->createLink('project', 'index', '', '', '', $project->id), '<i class="icon icon-menu-doc"></i>' . $project->name);

        $this->view->projectID = $projectID;
        $this->view->module    = $module;
        $this->view->method    = $method;

        $this->view->normalProjectsHtml = $this->project->getPRJTreeMenu(0, array('projectmodel', 'createPRJManageLink'), 0, 'dropmenu');
        $this->view->closedProjectsHtml = $closedProjectsHtml;

        $this->display();
    }

    /**
     * Ajax get projects.
     *
     * @access public
     * @return void
     */
    public function ajaxGetCopyProjects()
    {
        $data = fixer::input('post')->get();
        $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF(trim($data->name))->andWhere('name')->like("%$data->name%")->fi()
            ->fetchPairs();

        $html = empty($projects) ? "<div class='text-center'>{$this->lang->noData}</div>" : '';
        foreach($projects as $id => $name)
        {
            $active = $data->cpoyProjectID == $id ? 'active' : '';
            $html .= "<div class='col-md-4 col-sm-6'><a href='javascript:;' data-id=$id class='nobr $active'>" . html::icon($this->lang->icons['project'], 'text-muted') . $name . "</a></div>"; 
        }
        echo $html;
    }

    /**
     * Ajax get available budget.
     *
     * @param  int    $parentProjectID
     * @access public
     * @return void
     */
    public function ajaxGetAvailableBudget($parentProjectID)
    {
        $parentProject   = $this->project->getPGMByID($parentProjectID);
        $availableBudget = $this->project->getAvailableBudget($parentProject);
        echo number_format($availableBudget, 2);
    }

    /**
     * Update project order.
     *
     * @access public
     * @return string
     */
    public function updateOrder()
    {
        $projects = $this->post->projects;
        foreach($projects as $id => $order)
        {    
            $this->dao->update(TABLE_PROJECT)
                ->set('`order`')->eq($order)
                ->where('id')->eq($id)
                ->exec();
        }

        $this->send(array('result' => 'success'));
    }

    /**
     * Project index view.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $this->lang->navGroup->project = 'project';
        $projectID = $this->project->savePRJState($projectID, $this->project->getPRJPairs());

        $project = $this->project->getPRJByID($projectID);
        if(empty($project) || $project->type != 'project') die(js::error($this->lang->notFound) . js::locate('back'));

        if(!$projectID) $this->locate($this->createLink('project', 'PRJbrowse')); 
        setCookie("lastPRJ", $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->view->title      = $this->lang->project->common . $this->lang->colon . $this->lang->project->PRJIndex;
        $this->view->position[] = $this->lang->project->PRJIndex;
        $this->view->project    = $project;

        $this->display();
    }

    /**
     * Projects list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $browseType = 'doing', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->project->menu = $this->lang->PRJ->menu;
        if($this->session->moreProjectLink) $this->lang->project->mainMenuAction = html::a($this->session->moreProjectLink, '<i class="icon icon-back"></i> ' . $this->lang->goback, '', "class='btn btn-link'");
        $this->app->session->set('PRJBrowse', $this->app->getURI(true));
        $this->loadModel('datatable');
        $this->session->set('projectList', $this->app->getURI(true));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $projectTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=project&key=PRJProjectTitle');
        $order        = explode('_', $orderBy);
        $sortField    = zget($this->config->project->sortFields, $order[0], 'id') . '_' . $order[1];
        $projectStats = $this->project->getPRJStats($projectID, $browseType, $queryID, $sortField, $pager, $projectTitle);

        $this->view->title      = $this->lang->project->PRJBrowse;
        $this->view->position[] = $this->lang->project->PRJBrowse;

        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->projectID    = $projectID;
        $this->view->project      = $this->project->getPRJByID($projectID);
        $this->view->PRJTree      = $this->project->getPRJTreeMenu(0, array('projectmodel', 'createPRJManageLink'), 0, 'list');
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;

        $this->display();
    }

    /**
     * Set module display mode.
     *
     * @access public
     * @return void
     */
    public function projectTitle()
    {
        $this->loadModel('setting');
        if($_POST)
        {
            $PRJProjectTitle = $this->post->PRJProjectTitle;
            $this->setting->setItem($this->app->user->account . '.project.PRJProjectTitle', $PRJProjectTitle);
            die(js::reload('parent.parent'));
        }

        $status = $this->setting->getItem('owner=' . $this->app->user->account . '&module=project&key=PRJProjectTitle');
        $this->view->status = empty($status) ? '0' : $status;
        $this->display();
    }

    /**
     * Create a project.
     *
     * @param  string $model
     * @param  int    $projectID
     * @param  string $from PRJ|PGM
     * @param  int    $copyProjectID
     * @access public
     * @return void
     */
    public function create($model = 'waterfall', $projectID = 0, $from = 'PRJ', $copyProjectID = '')
    {
        if($from == 'PRJ') $this->lang->project->menu = $this->lang->PRJ->menu;

        if($_POST)
        {
            $projectID = $this->project->PRJCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('project', $projectID, 'opened');

            if($from == 'PGM')
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PGMBrowse')));
            }
            else
            {
                if($model == 'waterfall')
                {
                    $productID = $this->loadModel('product')->getProductIDByProject($projectID, true);
                    $this->session->set('projectPlanList', $this->createLink('projectplan', 'browse', "projectID=$projectID&productID=$productID&type=lists", '', '', $projectID));
                    $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('projectplan', 'create', "projectID=$projectID", '', '', $projectID)));
                }

                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('project', 'create', '', '', '', $projectID)));
            }
        }

        $name      = '';
        $code      = '';
        $team      = '';
        $whitelist = '';
        $acl       = 'private';
        $auth      = 'extend';

        $products      = array();
        $productPlans  = array();
        $parentProject = $this->project->getPGMByID($projectID);

        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProject->name;
            $code        = $copyProject->code;
            $team        = $copyProject->team;
            $acl         = $copyProject->acl;
            $auth        = $copyProject->auth;
            $whitelist   = $copyProject->whitelist;
            $projectID   = $copyProject->parent;
            $model       = $copyProject->model;

            $products = $this->project->getProducts($copyProjectID);
            foreach($products as $product)
            {
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
            }
        }

        $this->view->title      = $this->lang->project->PRJCreate;
        $this->view->position[] = $this->lang->project->PRJCreate;

        $this->view->pmUsers         = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users           = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjects    = $this->project->getPRJPairsByModel();
        $this->view->products        = $products;
        $this->view->allProducts     = array('0' => '') + $this->project->getPGMProductPairs($projectID);
        $this->view->productPlans    = array('0' => '') + $productPlans;
        $this->view->branchGroups    = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->projectID       = $projectID;
        $this->view->model           = $model;
        $this->view->name            = $name;
        $this->view->code            = $code;
        $this->view->team            = $team;
        $this->view->acl             = $acl;
        $this->view->auth            = $auth;
        $this->view->whitelist       = $whitelist;
        $this->view->copyProjectID   = $copyProjectID;
        $this->view->from            = $from;
        $this->view->projectList     = $this->project->getParentPairs();
        $this->view->parentProject   = $parentProject;
        $this->view->URSRPairs       = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget = $this->project->getAvailableBudget($parentProject);
        $this->view->budgetUnitList  = $this->project->getBudgetUnitList();

        $this->display();
    }

    /**
     * Edit a project.
     *
     * @param  int    $projectID
     * @param  string $from  PRJ|pgmbrowse|pgmproject
     * @access public
     * @return void
     */
    public function edit($projectID = 0, $from = 'PRJ')
    {
        $this->app->loadLang('custom');
        $this->app->loadLang('project');
        $this->loadModel('productplan');
        $this->loadModel('action');

        $project   = $this->project->getPRJByID($projectID);
        $projectID = $project->parent;

        /* Navigation stay in project when enter from project list. */
        $this->adjustNavigation($from, $projectID);

        if($_POST)
        {
            $changes = $this->project->PRJUpdate($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $locateLink = $this->session->PRJBrowse ? $this->session->PRJBrowse : inLink('PRJView', "projectID=$projectID");
            if($from == 'pgmbrowse')  $locateLink = inLink('PGMBrowse');
            if($from == 'pgmproject') $locateLink = $this->session->PGMProject ? $this->session->PGMProject : inLink('PGMProject', "projectID=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }


        $linkedBranches = array();
        $productPlans   = array(0 => '');
        $allProducts    = $this->project->getPGMProductPairs($project->parent, 'assign', 'noclosed');
        $linkedProducts = $this->project->getProducts($projectID);
        $parentProject  = $this->project->getPGMByID($projectID);

        /* If the story of the product which linked the project, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $projectStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->andWhere('product')->eq($productID)->fetchAll('story');
            if(!empty($projectStories)) array_push($unmodifiableProducts, $productID);
        }

        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        foreach($linkedProducts as $product)
        {
            $productPlans[$product->id] = $this->productplan->getPairs($product->id);
        }

        $this->view->title      = $this->lang->project->PRJEdit;
        $this->view->position[] = $this->lang->project->PRJEdit;

        $this->view->PMUsers              = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users                = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project              = $project;
        $this->view->projectList          = $this->project->getParentPairs();
        $this->view->projectID            = $projectID;
        $this->view->allProducts          = array('0' => '') + $allProducts;
        $this->view->productPlans         = $productPlans;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups         = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);
        $this->view->URSRPairs            = $this->loadModel('custom')->getURSRPairs();
        $this->view->from                 = $from;
        $this->view->parentProject        = $parentProject;
        $this->view->availableBudget      = $this->project->getAvailableBudget($parentProject) + (float)$project->budget;
        $this->view->budgetUnitList       = $this->project->getBudgetUnitList();

        $this->display();
    }

    /**
     * Batch edit projects.
     *
     * @param  string $from
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchEdit($from = 'prjbrowse', $projectID = 0)
    {
        $this->loadModel('action');

        /* Navigation stay in project when enter from project list. */
        $this->adjustNavigation($from, $projectID);

        if($this->post->names)
        {
            $allChanges = $this->project->PRJBatchUpdate();

            if(!empty($allChanges))
            {
                foreach($allChanges as $projectID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->action->create('project', $projectID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            die(js::locate($this->session->projectList, 'parent'));
        }

        $projectIdList = $this->post->projectIdList ? $this->post->projectIdList : die(js::locate($this->session->projectList, 'parent'));
        $projects      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');

        foreach($projects as $project) $appendPMUsers[$project->PM] = $project->PM;

        $this->view->title      = $this->lang->project->batchEdit;
        $this->view->position[] = $this->lang->project->batchEdit;

        $this->view->projects      = $projects;
        $this->view->projectList   = $this->project->getParentPairs();
        $this->view->PMUsers       = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $appendPMUsers);

        $this->display();
    }

    /**
     * View a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function view($projectID = 0)
    {
        $this->app->loadLang('bug');
        $this->lang->navGroup->project = 'project';
        $this->lang->project->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('project', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        $this->app->session->set('PRJBrowse', $this->app->getURI(true));

        $products = $this->loadModel('product')->getProductsByProject($projectID);;
        $linkedBranches = array();
        foreach($products as $product)
        {    
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->title        = $this->lang->project->PRJView; 
        $this->view->position     = $this->lang->project->PRJView;
        $this->view->projectID    = $projectID;
        $this->view->project      = $this->project->getPRJByID($projectID);
        $this->view->products     = $products;
        $this->view->actions      = $this->loadModel('action')->getList('project', $projectID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
        $this->view->statData     = $this->project->getPRJStatData($projectID);
        $this->view->workhour     = $this->project->getPRJWorkhour($projectID);
        $this->view->planGroup    = $this->loadModel('project')->getPlans($products);;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', $projectID);

        $this->display();
    }

    /**
     * Project browse groups.
     *
     * @param  int    $projectID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function group($projectID = 0, $projectID = 0)
    {
        $this->lang->navGroup->project = 'project';
        $this->lang->project->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('project', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups     = $this->group->getList($projectID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->groups     = $groups;
        $this->view->projectID  = $projectID;
        $this->view->projectID  = $projectID;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Project create a group.
     *
     * @param  int    $projectID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function createGroup($projectID = 0, $projectID = 0)
    {
        if(!empty($_POST))
        {
            $_POST['PRJ'] = $projectID;
            $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::closeModal('parent.parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;

        $this->display('group', 'create');
    }

    /**
     * Project manage view.
     *
     * @param  int    $groupID
     * @param  int    $projectID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function manageView($groupID, $projectID, $projectID)
    {
        $this->lang->navGroup->project = 'project';
        $this->lang->project->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('project', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        if($_POST)
        {
            $this->group->updateView($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PRJGroup', "projectID=$projectID&projectID=$projectID")));
        }

        $group = $this->group->getById($groupID);

        $this->view->title      = $group->name . $this->lang->colon . $this->lang->group->manageView;
        $this->view->position[] = $group->name;
        $this->view->position[] = $this->lang->group->manageView;

        $this->view->group    = $group;
        $this->view->products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('project')->eq($group->PRJ)->orderBy('order_desc')->fetchPairs('id', 'name');
        $this->view->projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('id')->eq($group->PRJ)->orderBy('order_desc')->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * Manage privleges of a group.
     *
     * @param  string    $type
     * @param  int       $param
     * @param  string    $menu
     * @param  string    $version
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
        $this->lang->navGroup->project = 'project';
        $this->lang->project->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('project', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        if($type == 'byGroup')
        {
            $groupID = $param;
            $group   = $this->group->getById($groupID);
        }

        $this->view->type = $type;
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->group->checkMenuModule($menu, $moduleName) or $type != 'byGroup') $this->app->loadLang($moduleName);
        }

        if(!empty($_POST))
        {
            if($type == 'byGroup')  $result = $this->group->updatePrivByGroup($groupID, $menu, $version);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PRJGroup', "projectID=$group->PRJ")));
        }

        if($type == 'byGroup')
        {
            $this->group->sortResource();
            $groupPrivs = $this->group->getPrivs($groupID);

            $this->view->title      = $group->name . $this->lang->colon . $this->lang->group->managePriv;
            $this->view->position[] = $group->name;
            $this->view->position[] = $this->lang->group->managePriv;

            /* Join changelog when be equal or greater than this version.*/
            $realVersion = str_replace('_', '.', $version);
            $changelog = array();
            foreach($this->lang->changelog as $currentVersion => $currentChangeLog)
            {
                if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join($currentChangeLog, ',');
            }

            $this->view->group      = $group;
            $this->view->changelogs = ',' . join($changelog, ',') . ',';
            $this->view->groupPrivs = $groupPrivs;
            $this->view->groupID    = $groupID;
            $this->view->menu       = $menu;
            $this->view->version    = $version;

            /* Unset not project privs. */
            $project = $this->project->getByID($group->PRJ);
            foreach($this->lang->resource as $method => $label)
            {
                if(!in_array($method, $this->config->projectPriv->{$project->model})) unset($this->lang->resource->$method);
            }
        }

        $this->display();
    }

    /**
     * Manage project members.
     *
     * @param  int    $projectID
     * @param  int    $dept
     * @access public
     * @return void
     */
    public function manageMembers($projectID, $dept = '')
    {
        $this->lang->navGroup->project = 'project';
        $this->lang->project->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('project', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $link = $this->createLink('project', 'PRJManageMembers', "projectID=$projectID");
            $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $link));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $project   = $this->project->getById($projectID);
        $users     = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles     = $this->user->getUserRoles(array_keys($users));
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->project->PRJManageMembers . $this->lang->colon . $project->name;
        $this->view->position[] = $this->lang->project->PRJManageMembers;

        $this->view->project        = $project;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = array('' => '') + $this->dept->getOptionMenu();
        $this->view->currentMembers = $this->project->getTeamMembers($projectID);;
        $this->display();
    }

    /**
     * Manage members of a group.
     *
     * @param  int    $groupID
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function manageGroupMember($groupID, $deptID = 0)
    {
        if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $group      = $this->group->getById($groupID);
        $groupUsers = $this->group->getUserPairs($groupID);
        $allUsers   = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        $title      = $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[] = $group->name;
        $position[] = $this->lang->group->manageMember;

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->group      = $group;
        $this->view->deptTree   = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createGroupManageMemberLink'), $groupID);
        $this->view->groupUsers = $groupUsers;
        $this->view->otherUsers = $otherUsers;

        $this->display('group', 'manageMember');
    }

    /**
     * Project copy a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function copyGroup($groupID)
    {
        if(!empty($_POST))
         {
             $group = $this->group->getByID($groupID);
             $_POST['PRJ'] = $group->PRJ;
             $this->group->copy($groupID);
             if(dao::isError()) die(js::error(dao::getError()));
             die(js::closeModal('parent.parent', 'this'));
         }

         $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->copy;
         $this->view->position[] = $this->lang->group->copy;
         $this->view->group      = $this->group->getById($groupID);

         $this->display('group', 'copy');
    }

    /**
     * Project edit a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function editGroup($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->update($groupID);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $this->view->position[] = $this->lang->group->edit;
        $this->view->group      = $this->group->getById($groupID);

        $this->display('group', 'edit');
    }

    /**
     * Start project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function start($projectID)
    {
        $this->loadModel('action');
        $project = $this->project->getPRJByID($projectID);

        if(!empty($_POST))
        {
            $changes = $this->project->start($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            /* Start all superior projects. */
            if($project->parent)
            {
                $path = explode(',', $project->path);
                $path = array_filter($path);
                foreach($path as $projectID)
                {
                    if($projectID == $projectID) continue;
                    $project = $this->project->getPGMByID($projectID);
                    if($project->status == 'wait' || $project->status == 'suspended')
                    {
                        $changes = $this->project->start($projectID);
                        if(dao::isError()) die(js::error(dao::getError()));

                        if($this->post->comment != '' or !empty($changes))
                        {
                            $actionID = $this->action->create('project', $projectID, 'Started', $this->post->comment);
                            $this->action->logHistory($actionID, $changes);
                        }
                    }
                }
            }

            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->start;
        $this->view->position[] = $this->lang->project->start;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('project', $projectID);
        $this->display();
    }

    /**
     * Suspend a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function suspend($projectID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->project->suspend($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->suspend;
        $this->view->position[] = $this->lang->project->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('project', $projectID);
        $this->view->project    = $this->project->getPGMByID($projectID);

        $this->display('project', 'suspend');
    }

    /**
     * Close a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function close($projectID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->project->close($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->close;
        $this->view->position[] = $this->lang->project->close;
        $this->view->project    = $this->project->getPRJByID($projectID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('project', $projectID);

        $this->display('project', 'close');
    }

    /**
     * Activate a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function activate($projectID)
    {
        $this->loadModel('action');
        $project = $this->project->getPRJByID($projectID);

        if(!empty($_POST))
        {
            $changes = $this->project->activate($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $project->begin);
        $newEnd   = date('Y-m-d', strtotime($project->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->lang->project->activate;
        $this->view->position[] = $this->lang->project->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('project', $projectID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->view->project    = $project;

        $this->display('project', 'activate');
    }

    /**
     * Delete a project.
     *
     * @param  int     $projectID
     * @param  string  $from
     * @access public
     * @return void
     */
    public function delete($projectID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $project = $this->project->getPRJByID($projectID);
            echo js::confirm(sprintf($this->lang->project->PRJConfirmDelete, $project->name), $this->createLink('project', 'PRJDelete', "projectID=$projectID&confirm=yes"));
            die();
        }
        else
        {
            $projectIdList = $this->project->getExecutionsByProject($projectID);
            $this->project->delete(TABLE_PROJECT, $projectID);
            $this->dao->update(TABLE_PROJECT)->set('deleted')->eq(1)->where('id')->in(array_keys($projectIdList))->exec();
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('project')->eq($projectID)->exec();
            $this->project->updateUserView($projectID);
            $this->session->set('PRJ', '');

            die(js::reload('parent'));
        }
    }

    /**
     * Update projects order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $idList  = explode(',', trim($this->post->projects, ','));
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        $projects = $this->dao->select('id,`order`')->from(TABLE_PROJECT)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($projects as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PROJECT)
                ->set('`order`')->eq($order)
                ->set('lastEditedBy')->eq($this->app->user->account)
                ->set('lastEditedDate')->eq(helper::now())
                ->where('id')->eq($newID)
                ->exec();
        }
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $projectID
     * @param  int    $projectID
     * @param  string $module
     * @param  string $from  PRJ|pgmbrowse|pgmproject
     * @param  string $objectType
     * @param  string $orderby
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist($projectID = 0, $projectID = 0, $module = 'project', $from = 'PRJ', $objectType = 'project', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($from == 'PRJ') 
        {
            $this->lang->navGroup->project = 'project';
            $this->lang->project->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('project', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->navGroup->project     = 'project';
            $this->lang->project->switcherMenu = $this->project->getPGMSwitcher($projectID, true);
            $this->project->setPGMViewMenu($projectID);
        }

        echo $this->fetch('personnel', 'whitelist', "objectID=$projectID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID&from=$from");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $projectID
     * @param  int     $deptID
     * @param  int     $projectID
     * @param  int     $from
     * @access public
     * @return void
     */
    public function addWhitelist($projectID = 0, $deptID = 0, $projectID = 0, $from = 'PRJ')
    {
        /* Navigation stay in project when enter from project list. */
        $this->adjustNavigation($from, $projectID);

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$projectID&dept=$deptID&objectType=project&module=project&projectID=$projectID&from=$from");
    }

    /*
     * Removing users from the white list.
     *
     * @param  int     $id
     * @param  string  $confirm
     * @access public
     * @return void
     */
    public function unbindWhielist($id = 0, $confirm = 'no')
    {
        echo $this->fetch('personnel', 'unbindWhielist', "id=$id&confirm=$confirm");
    }

    /**
     * Manage products.
     *
     * @param  int     $projectID
     * @param  int     $projectID
     * @param  string $from  PRJ|pgmbrowse|pgmproject
     * @access public
     * @return void
     */
    public function manageProducts($projectID, $projectID = 0, $from = 'PRJ')
    {
        /* Navigation stay in project when enter from project list. */
        $this->adjustNavigation($from, $projectID);

        if(!empty($_POST))
        {
            if(!isset($_POST['products']))
            {
                dao::$errors['message'][] = $this->lang->project->errorNoProducts;
                $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $oldProducts = $this->project->getProducts($projectID);
            $this->project->updateProducts($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->project->getProducts($projectID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create('project', $projectID, 'Managed', '', !empty($_POST['products']) ? join(',', $_POST['products']) : '');

            $locateLink = $this->session->PRJBrowse ? $this->session->PRJBrowse : inLink('PRJManageProducts', "projectID=$projectID");
            if($from == 'pgmbrowse')  $locateLink = inLink('PGMBrowse');
            if($from == 'pgmproject') $locateLink = $this->session->PGMProject ? $this->session->PGMProject : inLink('PGMProject', "projectID=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $this->loadModel('product');
        $project = $this->project->getById($projectID);

        $allProducts        = $this->project->getPGMProductPairs($project->parent, 'assign', 'noclosed');
        $linkedProducts     = $this->project->getProducts($project->id);
        $linkedBranches     = array();

        /* If the story of the product which linked the project, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $projectStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->andWhere('product')->eq($productID)->fetchAll('story');
            if(!empty($projectStories)) array_push($unmodifiableProducts, $productID);
        }

        /* Merge allProducts and linkedProducts for closed product. */
        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if(!empty($product->branch)) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Assign. */
        $this->view->title                = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $this->view->position[]           = $this->lang->project->manageProducts;
        $this->view->allProducts          = $allProducts;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups         = $this->loadModel('branch')->getByProducts(array_keys($allProducts), '', $linkedBranches);

        $this->display();
    }

    /**
     * View a project.
     *
     * @param int $projectID
     * @access public
     * @return void
     */
    public function view($projectID)
    {
        $project = $this->project->getPGMByID($projectID);
        if(!$project) die(js::error($this->lang->notFound) . js::locate('back'));

        echo $this->fetch('project', 'PGMProduct', "projectID=$projectID");
    }

    /**
     * AJAX: Check products.
     *
     * @param  int    $projectID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxCheckProduct($projectID, $projectID)
    {
        /* Set vars. */
        $project   = $this->project->getPRJByID($projectID);
        $oldTopPGM = $this->project->getTopPGMByID($project->parent);
        $newTopPGM = $this->project->getTopPGMByID($projectID);

        if($oldTopPGM == $newTopPGM) die();

        $response  = array();
        $response['result']  = true;
        $response['message'] = $this->lang->project->changeProjectTip;

        $multiLinkedProducts = $this->project->getMultiLinkedProducts($projectID);
        if($multiLinkedProducts)
        {
            $multiLinkedProjects = array();
            foreach($multiLinkedProducts as $productID => $product)
            {
                $multiLinkedProjects[$productID] = $this->loadModel('product')->getProjectPairsByProduct($productID);
            }
            $response['result']              = false;
            $response['message']             = $multiLinkedProducts;
            $response['multiLinkedProjects'] = $multiLinkedProjects;
        }
        die(json_encode($response));
    }

    /**
     * Adjust the navigation.
     *
     * @param  string $from
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function adjustNavigation($from = '', $projectID = 0)
    {
        if($from == 'prjbrowse') $this->lang->project->menu = $this->lang->PRJ->menu;
        if($from == 'pgmbrowse') $this->lang->navGroup->project = 'project';

        if($from == 'PRJ')
        {
            $this->lang->navGroup->project = 'project';
            $this->lang->project->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('project', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }

        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->project->switcherMenu = $this->project->getPGMSwitcher($projectID, true);
            $this->project->setPGMViewMenu($projectID);
        }
    }
}
