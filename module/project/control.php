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
    /**
     * Project create guide.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function createGuide($projectID = 0, $from = 'project')
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
        $childPrograms = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('project')->eq($projectID)->fetchPairs();

        if(!empty($childPrograms)) $this->user->updateUserView($childPrograms, 'project',  array($account));
        if(!empty($childProjects)) $this->user->updateUserView($childProjects, 'project',  array($account));
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
    public function ajaxGetDropMenu($projectID = 0, $module, $method)
    {
        /* Load module. */
        $this->loadModel('program');

        /* Sort project. */
        $programs        = array();
        $orderedProjects = array();
        $objects         = $this->program->getList('all', 'order_asc', null, true);
        foreach($objects as $objectID => $object)
        {
            if($object->type == 'program')
            {
                $programs[$objectID] = $object->name;
            }
            else
            {
                $object->parent = $this->program->getTopByID($object->parent);
                $orderedProjects[] = $object;
                unset($objects[$object->id]);
            }
        }

        $this->view->projectID = $projectID;
        $this->view->projects  = $orderedProjects;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->programs  = $programs;

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
     * Project index view.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $projectID = $this->project->saveState($projectID, $this->project->getPairsByProgram());

        if($projectID == 0 and common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'create'));
        if($projectID == 0 and !common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'browse'));

        $this->project->setMenu($projectID);

        $project = $this->project->getByID($projectID);
        if(empty($project) || $project->type != 'project') die(js::error($this->lang->notFound) . js::locate('back'));

        if(!$projectID) $this->locate($this->createLink('project', 'browse'));
        setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->view->title      = $this->lang->project->common . $this->lang->colon . $this->lang->project->index;
        $this->view->position[] = $this->lang->project->index;
        $this->view->project    = $project;

        $this->display();
    }

    /**
     * Project list.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($programID = 0, $browseType = 'doing', $param = 0, $orderBy = 'order_asc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->loadModel('datatable');
        $this->loadModel('execution');
        $this->session->set('projectList', $this->app->getURI(true), 'project');

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=project&key=programTitle');
        $projectStats = $this->loadModel('program')->getProjectStats($programID, $browseType, $queryID, $orderBy, $pager, $programTitle);

        $this->view->title      = $this->lang->project->browse;
        $this->view->position[] = $this->lang->project->browse;

        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getByID($programID);
        $this->view->programTree  = $this->project->getTreeMenu(0, array('projectmodel', 'createManageLink'), 0, 'list');
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
    public function programTitle()
    {
        $this->loadModel('setting');
        if($_POST)
        {
            $programTitle = $this->post->programTitle;
            $this->setting->setItem($this->app->user->account . '.project.programTitle', $programTitle);
            die(js::reload('parent.parent'));
        }

        $status = $this->setting->getItem('owner=' . $this->app->user->account . '&module=project&key=programTitle');
        $this->view->status = empty($status) ? '0' : $status;
        $this->display();
    }

    /**
     * Create a project.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @access public
     * @return void
     */
    public function create($model = 'scrum', $programID = 0, $copyProjectID = 0)
    {
        $this->loadModel('execution');

        if($_POST)
        {
            $projectID = $this->project->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('project', $projectID, 'opened');

            /* Link the plan stories. */
            if(!empty($_POST['plans']))
            {
                foreach($_POST['plans'] as $planID)
                {
                    $planStories = $planProducts = array();
                    $planStory   = $this->loadModel('story')->getPlanStories($planID);
                    if(!empty($planStory))
                    {
                        foreach($planStory as $id => $story)
                        {
                            if($story->status == 'draft')
                            {
                                unset($planStory[$id]);
                                continue;
                            }
                            $planProducts[$story->id] = $story->product;
                        }
                        $planStories = array_keys($planStory);
                        $this->execution->linkStory($projectID, $planStories, $planProducts);
                    }
                }
            }

            if($this->viewType == 'json') $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $projectID));

            if($this->app->openApp == 'program')
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('program', 'browse')));
            }
            elseif($this->app->openApp == 'doc')
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'objectLibs', "type=project&objectID=$projectID")));
            }
            else
            {
                if($model == 'waterfall')
                {
                    $productID = $this->loadModel('product')->getProductIDByProject($projectID, true);
                    $this->session->set('projectPlanList', $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=lists", '', '', $projectID), 'project');
                    $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('programplan', 'create', "projectID=$projectID", '', '', $projectID)));
                }

                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('project', 'browse', "programID=0&browseType=all", '', '', $projectID)));
            }
        }

        if($this->app->openApp == 'program') $this->loadModel('program')->setMenu($programID);

        $name      = '';
        $code      = '';
        $team      = '';
        $whitelist = '';
        $acl       = 'private';
        $auth      = 'extend';

        $products      = array();
        $productPlans  = array();
        $parentProgram = $this->loadModel('program')->getByID($programID);

        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProject->name;
            $code        = $copyProject->code;
            $team        = $copyProject->team;
            $acl         = $copyProject->acl;
            $auth        = $copyProject->auth;
            $whitelist   = $copyProject->whitelist;
            $programID   = $copyProject->parent;
            $model       = $copyProject->model;

            $products = $this->project->getProducts($copyProjectID);
            foreach($products as $product)
            {
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
            }
        }

        $this->view->title      = $this->lang->project->create;
        $this->view->position[] = $this->lang->project->create;

        $this->view->pmUsers         = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users           = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjects    = $this->project->getPairsByModel();
        $this->view->products        = $products;
        $this->view->allProducts     = array('0' => '') + $this->program->getProductPairs($programID);
        $this->view->productPlans    = array('0' => '') + $productPlans;
        $this->view->branchGroups    = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->programID       = $programID;
        $this->view->model           = $model;
        $this->view->name            = $name;
        $this->view->code            = $code;
        $this->view->team            = $team;
        $this->view->acl             = $acl;
        $this->view->auth            = $auth;
        $this->view->whitelist       = $whitelist;
        $this->view->copyProjectID   = $copyProjectID;
        $this->view->programList     = $this->program->getParentPairs();
        $this->view->parentProgram   = $parentProgram;
        $this->view->URSRPairs       = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget = $this->program->getBudgetLeft($parentProgram);
        $this->view->budgetUnitList  = $this->program->getBudgetUnitList();

        $this->display();
    }

    /**
     * Edit a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function edit($projectID = 0)
    {
        $this->loadModel('action');
        $this->loadModel('custom');
        $this->loadModel('productplan');
        $this->loadModel('user');
        $this->loadModel('program');
        $this->loadModel('execution');

        $project   = $this->project->getByID($projectID);
        $programID = $project->parent;
        $this->project->setMenu($projectID);

        if($_POST)
        {
            $oldPlans = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('plan');
            $oldPlanStories = $this->dao->select('t1.story')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.project=t2.project')
                ->where('t1.project')->eq($projectID)
                ->andWhere('t2.plan')->in(array_keys($oldPlans))
                ->fetchAll('story');
            $diffResult = array_diff($oldPlans, $_POST['plans']);

            $changes = $this->project->update($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            /* Link the plan stories. */
            if(!empty($_POST['plans']) and !empty($diffResult))
            {
                $this->loadModel('productplan')->linkProject($projectID, $_POST['plans'], $oldPlanStories);
            }

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

            $locateLink = $this->session->projectList ? $this->session->projectList : inLink('view', "projectID=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $linkedBranches = array();
        $productPlans   = array(0 => '');
        $allProducts    = $this->program->getProductPairs($project->parent, 'assign', 'noclosed');
        $linkedProducts = $this->project->getProducts($projectID);
        $parentProject  = $this->program->getByID($project->parent);

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

        $this->view->title      = $this->lang->project->edit;
        $this->view->position[] = $this->lang->project->edit;

        $this->view->PMUsers              = $this->user->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users                = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project              = $project;
        $this->view->programList          = $this->program->getParentPairs();
        $this->view->projectID            = $projectID;
        $this->view->allProducts          = array('0' => '') + $allProducts;
        $this->view->productPlans         = $productPlans;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups         = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);
        $this->view->URSRPairs            = $this->custom->getURSRPairs();
        $this->view->parentProject        = $parentProject;
        $this->view->parentProgram        = $this->program->getByID($project->parent);
        $this->view->availableBudget      = $this->program->getBudgetLeft($parentProject) + (float)$project->budget;
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
    public function batchEdit($from = 'browse', $projectID = 0)
    {
        $this->loadModel('action');
        $this->loadModel('execution');

        if($this->post->names)
        {
            $allChanges = $this->project->batchUpdate();

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
        $this->view->programList   = $this->loadModel('program')->getParentPairs();
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
        $projectID = (int)$projectID;
        $project   = $this->project->getById($projectID);
        if(empty($project) || strpos('scrum,waterfall', $project->model) === false) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->project->setMenu($projectID);

        $products = $this->loadModel('product')->getProducts($projectID);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->title        = $this->lang->project->view;
        $this->view->position     = $this->lang->project->view;
        $this->view->projectID    = $projectID;
        $this->view->project      = $project;
        $this->view->products     = $products;
        $this->view->actions      = $this->loadModel('action')->getList('project', $projectID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
        $this->view->statData     = $this->project->getStatData($projectID);
        $this->view->workhour     = $this->project->getWorkhour($projectID);
        $this->view->planGroup    = $this->loadModel('execution')->getPlans($products);;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', $projectID);

        $this->display();
    }

    /**
     * Project browse groups.
     *
     * @param  int    $projectID
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function group($projectID = 0, $programID = 0)
    {
        $this->loadModel('group');
        $this->project->setMenu($projectID);

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups     = $this->group->getList($projectID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->groups     = $groups;
        $this->view->project    = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $this->view->projectID  = $projectID;
        $this->view->programID  = $programID;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Project create a group.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function createGroup($projectID = 0)
    {
        $this->loadModel('group');

        if(!empty($_POST))
        {
            $_POST['project'] = $projectID;
            $groupID = $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            if($this->viewType == 'json') $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $groupID));
            die(js::closeModal('parent.parent'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;

        $this->display('group', 'create');
    }

    /**
     * Project dynamic.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction  next|pre
     * @access public
     * @return void
     */
    public function dynamic($projectID = 0, $type = 'today', $param = '', $recTotal = 0, $date = '', $direction = 'next')
    {
        $this->project->setMenu($projectID);

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');

        if(isset($this->config->maxVersion))
        {
            $this->session->set('riskList', $uri, 'project');
            $this->session->set('issueList', $uri, 'project');
        }

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';
        $sort    = $this->loadModel('common')->appendOrder($orderBy);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById($param, 'account');
            if($user) $account = $user->account;
        }
        $period  = $type == 'account' ? 'all'  : $type;
        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($account, $period, $sort, $pager, 'all', $projectID, 'all', $date, $direction);

        /* The header and position. */
        $project = $this->project->getByID($projectID);
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->dynamic;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->dynamic;

        $this->view->userIdPairs  = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $this->view->accountPairs = $this->user->getPairs('noletter|nodeleted');

        /* Assign. */
        $this->view->projectID  = $projectID;
        $this->view->type       = $type;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->account    = $account;
        $this->view->param      = $param;
        $this->view->dateGroups = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction  = $direction;
        $this->display();
    }

    /**
     * Execution list.
     *
     * @param  string $status
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $productID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function execution($status = 'all', $projectID = 0, $orderBy = 'id_desc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->app->session->set('executionList', $uri, 'project');

        echo $this->fetch('execution', 'all', "status=$status&projectID=$projectID&orderBy=$orderBy&productID=$productID&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Project qa dashboard.
     *
     * @param  int $projectID
     * @access public
     * @return void
     */
    public function qa($projectID = 0)
    {
        $this->project->setMenu($projectID);
        $this->view->title = $this->lang->project->qa;
        $this->display();
    }

    /**
     * Project bug list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $build
     * @param  string $type
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bug($projectID = 0, $productID = 0, $orderBy = 'status,id_desc', $build = 0, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');
        $this->loadModel('product');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true), 'project');
        $this->project->setMenu($projectID);

        $project  = $this->project->getByID($projectID);
        $type     = strtolower($type);
        $queryID  = ($type == 'bysearch') ? (int)$param : 0;
        $products = $this->project->getProducts($projectID);
        $branchID = isset($products[$productID]) ? $products[$productID]->branch : 0;

        $productPairs = array('0' => $this->lang->product->all);
        foreach($products as $product) $productPairs[$product->id] = $product->name;
        $this->lang->modulePageNav = $this->product->select($productPairs, $productID, 'project', 'bug', '', $branchID, 0, '', false);

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->bug->common;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->bug->common;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = $this->loadModel('common')->appendOrder($orderBy);
        $bugs  = $this->bug->getProjectBugs($projectID, $productID, $build, $type, $param, $sort, '', $pager);
        $users = $this->user->getPairs('noletter');

        /* team member pairs. */
        $memberPairs   = array();
        $memberPairs[] = "";
        $teamMembers   = $this->project->getTeamMembers($projectID);
        foreach($teamMembers as $key => $member) $memberPairs[$key] = $member->realname;

        /* Build the search form. */
        $actionURL = $this->createLink('project', 'bug', "projectID=$projectID&productID=$productID&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->loadModel('execution')->buildBugSearchForm($products, $queryID, $actionURL, 'project');

        /* Assign. */
        $this->view->title       = $title;
        $this->view->position    = $position;
        $this->view->bugs        = $bugs;
        $this->view->tabID       = 'bug';
        $this->view->build       = $this->loadModel('build')->getById($build);
        $this->view->buildID     = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->users       = $users;
        $this->view->productID   = $productID;
        $this->view->project     = $this->project->getById($projectID);
        $this->view->branchID    = empty($this->view->build->branch) ? $branchID : $this->view->build->branch;
        $this->view->memberPairs = $memberPairs;
        $this->view->type        = $type;
        $this->view->param       = $param;

        $this->display();
    }

    /**
     * Project case list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase($projectID = 0, $productID = 0, $branch = 0, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('product');
        $products = array('0' => $this->lang->product->all) + $this->project->getProducts($projectID, false);
        $this->lang->modulePageNav = $this->product->select($products, $productID, 'project', 'testcase', '', $branch, 0, '', false);

        echo $this->fetch('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=$orderBy&recTotal=$orderBy&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID");
    }

    /**
     * List of test reports for the project.
     *
     * @param  int    $projectID
     * @param  string $objectType   project|execution|product
     * @param  string $extra
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testreport($projectID = 0, $objectType = 'project', $extra = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('testreport', 'browse', "objectID=$projectID&objectType=$objectType&extra=$extra&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Project test task list.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask($projectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testtask');
        $this->app->loadLang('testreport');

        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $this->project->setMenu($projectID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $productTasks = array();

        $project = $this->project->getByID($projectID);
        $tasks = $this->testtask->getProjectTasks($projectID, $orderBy, $pager);
        foreach($tasks as $key => $task) $productTasks[$task->product][] = $task;

        $this->view->title        = $project->name . $this->lang->colon . $this->lang->project->common;
        $this->view->position[]   = html::a($this->createLink('project', 'testtask', "projectID=$projectID"), $project->name);
        $this->view->position[]   = $this->lang->testtask->common;
        $this->view->project      = $project;
        $this->view->projectID    = $projectID;
        $this->view->projectName  = $project->name;
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->tasks        = $productTasks;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->products     = $this->loadModel('product')->getPairs('', 0);
        $this->view->canBeChanged = common::canModify('project', $project); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Browse builds of a project.
     *
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @access public
     * @return void
     */
    public function build($projectID = 0, $type = 'all', $param = 0)
    {
        /* Load module and get project. */
        $this->loadModel('build');
        $project = $this->project->getByID($projectID);
        $this->project->setMenu($projectID);

        $this->session->set('buildList', $this->app->getURI(true), 'project');

        /* Get products' list. */
        $products = $this->project->getProducts($projectID, false);
        $products = array('' => '') + $products;

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('project', 'build', "projectID=$projectID&type=bysearch&queryID=myQueryID");

        $executions = $this->loadModel('execution')->getByProject($projectID, 'all', '', true);
        $this->config->build->search['fields']['execution'] = $this->project->lang->executionCommon;
        $this->config->build->search['params']['execution'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $executions);

        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'project');

        if($type == 'bysearch')
        {
            $builds = $this->build->getProjectBuildsBySearch((int)$projectID, (int)$param);
        }
        else
        {
            $builds = $this->build->getProjectBuilds((int)$projectID, $type, $param);
        }

        /* Set project builds. */
        $projectBuilds = array();
        if(!empty($builds))
        {
            foreach($builds as $build) $projectBuilds[$build->product][] = $build;
        }

        /* Header and position. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->execution->build;
        $this->view->position[] = $this->lang->execution->build;

        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->buildsTotal   = count($builds);
        $this->view->projectBuilds = $projectBuilds;
        $this->view->product       = $type == 'product' ? $param : 'all';
        $this->view->projectID     = $projectID;
        $this->view->project       = $project;
        $this->view->products      = $products;
        $this->view->executions    = $executions;
        $this->view->type          = $type;

        $this->display();
    }

    /**
     * Project manage view.
     *
     * @param  int    $groupID
     * @param  int    $projectID
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function manageView($groupID, $projectID, $programID)
    {
        $this->loadModel('group');
        if($_POST)
        {
            $this->group->updateView($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('group', "projectID=$projectID&programID=$programID")));
        }

        $this->project->setMenu($projectID);

        $group = $this->group->getById($groupID);

        $this->view->title      = $group->name . $this->lang->colon . $this->lang->group->manageView;
        $this->view->position[] = $group->name;
        $this->view->position[] = $this->lang->group->manageView;

        $this->view->group    = $group;
        $this->view->products = $this->loadModel('product')->getProductPairsByProject($projectID);
        $this->view->projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('id')->eq($group->project)->orderBy('order_desc')->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * Manage privleges of a group.
     *
     * @param  int       $projectID
     * @param  string    $type
     * @param  int       $param
     * @param  string    $menu
     * @param  string    $version
     * @access public
     * @return void
     */
    public function managePriv($projectID, $type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
        $this->loadModel('group');
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

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('group', "projectID=$group->project")));
        }

        $this->project->setMenu($projectID);

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
                if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join(',', $currentChangeLog);
            }

            $this->view->group      = $group;
            $this->view->changelogs = ',' . join(',', $changelog) . ',';
            $this->view->groupPrivs = $groupPrivs;
            $this->view->groupID    = $groupID;
            $this->view->projectID  = $projectID;
            $this->view->menu       = $menu;
            $this->view->version    = $version;

            /* Unset not project privs. */
            $project = $this->project->getByID($group->project);
            foreach($this->lang->resource as $method => $label)
            {
                if(!in_array($method, $this->config->programPriv->{$project->model})) unset($this->lang->resource->$method);
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
        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');
        $this->loadModel('execution');
        $this->project->setMenu($projectID);

        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $this->loadModel('action')->create('team', $projectID, 'ManagedTeam');

            $link = $this->createLink('project', 'manageMembers', "projectID=$projectID");
            $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $link));
        }

        $project   = $this->project->getById($projectID);
        $users     = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles     = $this->user->getUserRoles(array_keys($users));
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $this->view->position[] = $this->lang->project->manageMembers;

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
        $this->loadModel('group');
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

        if($this->config->systemMode == 'new')
        {
            $outsideUsers = $this->loadModel('user')->getPairs('outside|noclosed|noletter|noempty');
            $this->view->outsideUsers = array_diff_assoc($outsideUsers, $groupUsers);
        }

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
        $this->loadModel('group');
        if(!empty($_POST))
         {
             $group = $this->group->getByID($groupID);
             $_POST['project'] = $group->project;
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
        $this->loadModel('group');
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
        $project = $this->project->getByID($projectID);

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
        $this->view->project    = $this->project->getByID($projectID);

        $this->display();
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
        $this->view->project    = $this->project->getByID($projectID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('project', $projectID);

        $this->display();
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
        $this->app->loadLang('execution');
        $project = $this->project->getByID($projectID);

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

        $this->display();
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
            $project = $this->project->getByID($projectID);
            echo js::confirm(sprintf($this->lang->project->confirmDelete, $project->name), $this->createLink('project', 'delete', "projectID=$projectID&confirm=yes"));
            die();
        }
        else
        {
            $this->loadModel('user');
            $this->loadModel('action');

            $this->project->delete(TABLE_PROJECT, $projectID);
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('execution')->eq($projectID)->exec();
            $this->user->updateUserView($projectID, 'project');

            /* Delete the execution under the project. */
            $executionIdList = $this->loadModel('execution')->getByProject($projectID);
            if(empty($executionIdList)) die(js::reload('parent'));

            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->in(array_keys($executionIdList))->exec();
            foreach($executionIdList as $executionID => $execution) $this->action->create('execution', $executionID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
            $this->user->updateUserView($executionIdList, 'sprint');

            $this->session->set('project', '');
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
     * @param  string $module
     * @param  string $from  project|program|programProject
     * @param  string $objectType
     * @param  string $orderby
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist($projectID = 0, $module = 'project', $from = 'project', $objectType = 'project', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('personnel', 'whitelist', "objectID=$projectID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID&from=$from");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $projectID
     * @param  int     $deptID
     * @param  int     $programID
     * @param  int     $from
     * @access public
     * @return void
     */
    public function addWhitelist($projectID = 0, $deptID = 0, $programID = 0, $from = 'project')
    {
        echo $this->fetch('personnel', 'addWhitelist', "objectID=$projectID&dept=$deptID&objectType=project&module=project&programID=$programID&from=$from");
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
     * Manage products.
     *
     * @param  int    $projectID
     * @param  string $from  project|program|programproject
     * @access public
     * @return void
     */
    public function manageProducts($projectID, $from = 'project')
    {
        $this->loadModel('product');
        $this->loadModel('program');

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

            $locateLink = inLink('manageProducts', "projectID=$projectID");
            if($from == 'program')  $locateLink = $this->createLink('program', 'browse');
            if($from == 'programproject') $locateLink = $this->session->programProject ? $this->session->programProject : inLink('programProject', "projectID=$projectID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $project = $this->project->getById($projectID);
        if($this->app->openApp == 'program')
        {
            $this->program->setMenu($project->parent);
        }
        else if($this->app->openApp == 'project')
        {
            $this->project->setMenu($projectID);
        }

        $allProducts    = $this->program->getProductPairs($project->parent, 'assign', 'noclosed');
        $linkedProducts = $this->product->getProducts($project->id);
        $linkedBranches = array();

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
     * AJAX: Check products.
     *
     * @param  int    $programID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxCheckProduct($programID, $projectID)
    {
        /* Set vars. */
        $project   = $this->project->getByID($projectID);
        $oldTopPGM = $this->loadModel('program')->getTopByID($project->parent);
        $newTopPGM = $this->program->getTopByID($programID);

        if($oldTopPGM == $newTopPGM) die();

        $response  = array();
        $response['result']  = true;
        $response['message'] = $this->lang->project->changeProgramTip;

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
}
