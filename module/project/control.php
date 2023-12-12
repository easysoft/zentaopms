<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */
class project extends control
{
    /**
     * Construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->view->globalDisableProgram = $this->config->systemMode == 'light';
    }

    /**
     * Project create guide.
     *
     * @param  int    $programID
     * @param  string $from
     * @param  int    $productID
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function createGuide($programID = 0, $from = 'project', $productID = 0, $branchID = 0)
    {
        $this->view->from      = $from;
        $this->view->programID = $programID;
        $this->view->productID = $productID;
        $this->view->branchID  = $branchID;
        $this->display();
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
            if(!isset($this->config->setCode) or empty($this->config->setCode)) unset($fields['code']);

            if(isset($fields['hasProduct'])) $fields['hasProduct'] = $projectLang->type;

            $involved = $this->cookie->involved ? $this->cookie->involved : 0;
            $projects = $this->project->getInfoList($status, $orderBy, '', $involved);
            $users    = $this->loadModel('user')->getPairs('noletter');

            $this->loadModel('product');
            foreach($projects as $i => $project)
            {
                $hasProduct = $project->hasProduct;

                $project->PM         = zget($users, $project->PM);
                $project->status     = $this->processStatus('project', $project);
                $project->model      = zget($projectLang->modelList, $project->model);
                $project->budget     = $project->budget != 0 ? $project->budget . zget($projectLang->unitList, $project->budgetUnit) : $this->lang->project->future;
                $project->parent     = $project->parentName;
                $project->hasProduct = zget($projectLang->projectTypeList, $project->hasProduct);

                $linkedProducts = $this->product->getProducts($project->id, 'all', '', false);
                $project->linkedProducts = implode('，', $linkedProducts);

                if(!$hasProduct) $project->linkedProducts = '';
                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->post->checkedItem;
                    if(strpos(",$checkedItem,", ",{$project->id},") === false) unset($projects[$i]);
                }
            }
            if($this->config->edition != 'open') list($fields, $projects) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $projects);
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
    public function ajaxGetDropMenu($projectID, $module, $method)
    {
        /* Load module. */
        $this->loadModel('program');

        /* Set cookie for show all project. */
        $_COOKIE['showClosed'] = 1;

        /* Sort project. */
        $programs        = array();
        $orderedProjects = array();

        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc,id_desc')
            ->fetchAll('id');

        $programs = $this->program->getPairs(true);

        foreach($projects as $project)
        {
            $project->parent = $this->program->getTopByID($project->parent);
            $project->parent = isset($programs[$project->parent]) ? $project->parent : $project->id;
            $orderedProjects[$project->parent][] = $project;
            unset($projects[$project->id]);
        }

        $this->view->link      = $this->project->getProjectLink($module, $method, $projectID);
        $this->view->projectID = $projectID;
        $this->view->projects  = $orderedProjects;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->programs  = $programs;

        $this->display();
    }

    /**
     * 设置1.5级项目下拉菜单。
     * Ajax get project drop menu for zui3.
     *
     * @param  int    $projectID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenuData(int $projectID, string $module, string $method)
    {
        $this->ajaxGetDropMenu($projectID, $module, $method);
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

        $model = $data->model;
        if($data->model == 'agileplus')     $model = array('scrum', 'agileplus');
        if($data->model == 'waterfallplus') $model = array('waterfall', 'waterfallplus');

        $projectPairs = $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('model')->in($model)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF(trim($data->name))->andWhere('name')->like("%$data->name%")->fi()
            ->fetchPairs();
        $projects = $this->project->getPairsByModel('', 0, '', array_keys($projectPairs));
        $html = empty($projects) ? "<div class='text-center'>{$this->lang->noData}</div>" : '';
        foreach($projects as $id => $name)
        {
            $active = $data->cpoyProjectID == $id ? 'active' : '';
            $html .= "<div class='col-md-4 col-sm-6'><a href='javascript:;' data-id=$id class='nobr $active'>" . html::icon($this->lang->icons['project'], 'text-muted') . $name . "</a></div>";
        }
        echo $html;
    }

    /**
     * Ajax get unlink tips when unlink team member.
     *
     * @param  int    $projectID
     * @param  int    $account
     * @access public
     * @return void
     */
    public function ajaxGetUnlinkTips($projectID, $account)
    {
        $project = $this->project->getByID($projectID);
        if(!$project->multiple) return;

        $executions       = $this->loadModel('execution')->getByProject($projectID, 'undone', 0, true);
        $executionMembers = $this->dao->select('t1.root,t2.name')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.root')->in(array_keys($executions))
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t1.account')->eq($account)
            ->fetchPairs();
        if(empty($executionMembers)) return;

        $executionNames = '';
        $count          = 0;
        foreach($executionMembers as $executionName)
        {
            if($count == 0) $executionNames  = $executionName;
            if($count == 1) $executionNames .= ',' . $executionName;
            if($count > 1) break;
            $count++;
        }
        if(count($executionMembers) <= 2) $this->lang->project->etc = ' ';
        if(strpos($this->app->getClientLang(), 'zh') !== false)
        {
            $this->lang->project->unlinkExecutionMember = sprintf($this->lang->project->unlinkExecutionMember, $executionNames, $this->lang->project->etc, count($executionMembers));
        }
        else
        {
            $this->lang->project->unlinkExecutionMember = sprintf($this->lang->project->unlinkExecutionMember, count($executionMembers), $executionNames, $this->lang->project->etc);
        }
        echo $this->lang->project->unlinkExecutionMember;
    }

    /**
     * AJAX: Get linked products with branch.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetLinkedProducts($projectID)
    {
        $productsWithBranch = array();
        $linkedProducts     = $this->project->getBranchesByProject($projectID);
        foreach($linkedProducts as $productID => $branches)
        {
            foreach($branches as $branchID => $branchInfo) $productsWithBranch[$productID][$branchID] = $branchID;
        }

        echo json_encode($productsWithBranch);
    }

    /**
     * Ajax: Get selected object's information.
     *
     * @param  str    $objectType
     * @param  int    $objectID
     * @param  int    $selectedProgramID
     * @access public
     * @return void
     */
    public function ajaxGetObjectInfo($objectType, $objectID, $selectedProgramID)
    {
        if($selectedProgramID)
        {
            $selectedProgram = $this->loadModel('program')->getByID($selectedProgramID);
            if($selectedProgram->budget) $availableBudget = $this->program->getBudgetLeft($selectedProgram);
        }

        if(!empty($objectID))
        {
            $object = $objectType == 'project' ? $this->project->getByID($objectID) : $this->loadModel('program')->getByID($objectID);

            if(isset($availableBudget)) $availableBudget = $object->parent == $selectedProgramID ? $availableBudget + (int)$object->budget : $availableBudget;

            if($objectType == 'program')
            {
                $minChildBegin = $this->dao->select('`begin` as minBegin')->from(TABLE_PROGRAM)->where('id')->ne($objectID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$objectID},%")->orderBy('begin_asc')->fetch('tminBegin');
                $maxChildEnd   = $this->dao->select('`end` as maxEnd')->from(TABLE_PROGRAM)->where('id')->ne($objectID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$objectID},%")->andWhere('end')->ne('0000-00-00')->orderBy('end_desc')->fetch('maxEnd');
            }
        }

        $data = array();
        if(isset($selectedProgram))
        {
            $data['selectedProgramBegin'] = $selectedProgram->begin;
            $data['selectedProgramEnd']   = $selectedProgram->end;
            $data['budgetUnit']           = $selectedProgram->budgetUnit;
            $data['selectedProgramPath']  = explode(',', $selectedProgram->path);
        }

        if($objectType == 'program')
        {
            $withProgram = $this->config->systemMode == 'ALM' ? true : false;
            $allProducts = array(0 => '') + $this->program->getProductPairs($selectedProgramID, 'all', 'noclosed', '', 0, $withProgram);
            $data['allProducts'] = html::select("products[]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");
            $data['plans']       = html::select('plans[][][]', '', '', 'class=\'form-control chosen\' multiple');
        }

        /* Finish task #64882.Get the path of the last selected program. */
        if(!empty($objectID))       $data['objectPath']      = explode(',', $object->path);
        if(isset($availableBudget)) $data['availableBudget'] = $availableBudget;
        if(isset($minChildBegin))   $data['minChildBegin']   = $minChildBegin;
        if(isset($maxChildEnd))     $data['maxChildEnd']     = $maxChildEnd;

        echo json_encode($data);
    }

    /**
     * Project index view.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function index($projectID = 0, $browseType = 'all', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $projectID = $this->project->saveState($projectID, $this->project->getPairsByProgram());

        if($projectID == 0 and common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'create'));
        if($projectID == 0 and !common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'browse'));

        $this->project->setMenu($projectID);

        $project = $this->project->getByID($projectID);

        /* Locate to task when set no execution. */
        if(!$project->multiple)
        {
            $executions = $this->loadModel('execution')->getList($project->id);
            foreach($executions as $execution)
            {
                if(!$execution->multiple) $this->locate($this->createLink('execution', 'task', "executionID={$execution->id}"));
            }
        }

        if(empty($project) || $project->type != 'project') return print(js::error($this->lang->notFound) . js::locate('back'));

        if(!$projectID) $this->locate($this->createLink('project', 'browse'));
        setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        if($project->model == 'kanban' and $this->config->vision != 'lite')
        {
            /* Load pager and get kanban list. */
            $this->app->loadClass('pager', $static = true);
            $pager = new pager($recTotal, $recPerPage, $pageID);

            $kanbanList = $this->loadModel('execution')->getList($projectID, 'all', $browseType, 0, 0, 0, $pager);

            $executionActions = array();
            foreach($kanbanList as $kanbanID => $kanban)
            {
                foreach($this->config->execution->statusActions as $action)
                {
                    if($this->execution->isClickable($kanban, $action)) $executionActions[$kanbanID][] = $action;
                }
                if($this->execution->isClickable($kanban, 'delete')) $executionActions[$kanbanID][] = 'delete';
            }

            $this->view->kanbanList       = $kanbanList;
            $this->view->browseType       = $browseType;
            $this->view->memberGroup      = $this->execution->getMembersByIdList(array_keys($kanbanList));
            $this->view->usersAvatar      = $this->loadModel('user')->getAvatarPairs('all');
            $this->view->executionActions = $executionActions;
            $this->view->pager            = $pager;
        }

        $this->view->title       = $this->lang->project->common . $this->lang->colon . $this->lang->project->index;
        $this->view->position[]  = $this->lang->project->index;
        $this->view->project     = $project;
        $this->view->userIdPairs = $this->loadModel('user')->getPairs('nodeleted|showid|all');

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

        $projectType = $this->cookie->projectType ? $this->cookie->projectType : 'bylist';
        $browseType  = strtolower($browseType);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('project', 'browse', "&programID=$programID&browseType=bySearch&queryID=myQueryID");
        $this->project->buildSearchForm($queryID, $actionURL);

        $this->loadModel('program')->refreshStats(); // Refresh stats fields of projects.

        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=project&key=programTitle');

        $this->view->title          = $this->lang->project->browse;
        $this->view->projectStats   = $this->program->getProjectStats($programID, $browseType, $queryID, $orderBy, $pager, $programTitle);
        $this->view->pager          = $pager;
        $this->view->programID      = $programID;
        $this->view->program        = $this->program->getByID($programID);
        $this->view->programTree    = $this->project->getTreeMenu(0, array('projectmodel', 'createManageLink'), 0, 'list');
        $this->view->programs       = array('0' => '') + $this->program->getParentPairs();
        $this->view->users          = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->userIdPairs    = $this->loadModel('user')->getPairs('nodeleted|showid');
        $this->view->usersAvatar    = $this->user->getAvatarPairs();
        $this->view->browseType     = $browseType;
        $this->view->projectType    = $projectType;
        $this->view->param          = $param;
        $this->view->orderBy        = $orderBy;
        $this->view->recTotal       = $recTotal;
        $this->view->recPerPage     = $recPerPage;
        $this->view->pageID         = $pageID;
        $this->view->showBatchEdit  = $this->cookie->showProjectBatchEdit;

        $this->display();
    }

    /**
     * Project kanban.
     *
     * @access public
     * @return void
     */
    public function kanban()
    {
        extract($this->project->getStats4Kanban());

        $this->view->title            = $this->lang->project->kanban;
        $this->view->kanbanGroup      = array_filter($kanbanGroup);
        $this->view->latestExecutions = $latestExecutions;
        $this->view->programPairs     = array(0 => $this->lang->project->noProgram) + $this->loadModel('program')->getPairs(true, 'order_asc');

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
            return print(js::reload('parent.parent'));
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
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '')
    {
        $this->loadModel('execution');
        $this->loadModel('product');

        if($model == 'kanban') unset($this->lang->project->authList['reset']);

        if($_POST)
        {
            $projectID = $this->project->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('project', $projectID, 'opened');

            /* Link the plan stories. */
            if(!empty($_POST['hasProduct']) && !empty($_POST['plans']))
            {
                $planIdList = array();
                foreach($_POST['plans'] as $plans)
                {
                    foreach($plans as $planID)
                    {
                        $planIdList[$planID] = $planID;
                    }
                }

                $planStoryGroup = $this->loadModel('story')->getStoriesByPlanIdList($planIdList);
                foreach($planIdList as $planID)
                {
                    $planStories = $planProducts = array();
                    $planStory   = isset($planStoryGroup[$planID]) ? $planStoryGroup[$planID] : array();
                    if(!empty($planStory))
                    {
                        foreach($planStory as $id => $story)
                        {
                            if($story->status != 'active')
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

            $message = $this->executeHooks($projectID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $projectID));

            if($this->app->tab != 'project' and $this->session->createProjectLocate)
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->createProjectLocate));
            }
            else
            {
                if($model == 'waterfall' or $model == 'waterfallplus')
                {
                    $productID = $this->product->getProductIDByProject($projectID, true);
                    $this->session->set('projectPlanList', $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=lists", '', '', $projectID), 'project');
                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('programplan', 'create', "projectID=$projectID", '', '', $projectID)));
                }

                $parent = isset($_POST['parent']) ? $_POST['parent'] : 0;
                $systemMode = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=mode');
                if(!empty($systemMode) and $systemMode == 'light') $parent = 0;
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('project', 'browse', "programID=$parent&browseType=all", '', '', $projectID)));
            }
        }

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if($this->app->tab == 'program' and $programID)                   $this->loadModel('program')->setMenu($programID);
        if($this->app->tab == 'product' and !empty($output['productID'])) $this->loadModel('product')->setMenu($output['productID']);
        if($this->app->tab == 'doc') unset($this->lang->doc->menu->project['subMenu']);
        $this->session->set('projectModel', $model);

        $name          = '';
        $code          = '';
        $team          = '';
        $whitelist     = '';
        $acl           = 'private';
        $auth          = 'extend';
        $multiple      = 1;
        $hasProduct    = 1;
        $shadow        = 0;
        $products      = array();
        $productPlans  = array();
        $parentProgram = $this->loadModel('program')->getByID($programID);

        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProject->name;
            $code        = $copyProject->code;
            $team        = $copyProject->team;
            $whitelist   = $copyProject->whitelist;
            $acl         = $copyProject->acl;
            $auth        = $copyProject->auth;
            $multiple    = $copyProject->multiple;
            $hasProduct  = $copyProject->hasProduct;
            $programID   = $copyProject->parent;
            $products    = $this->product->getProducts($copyProjectID);

            if(!$copyProject->hasProduct) $shadow = 1;
            foreach($products as $product)
            {
                $branches = implode(',', $product->branches);
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id, $branches, 'noclosed', true);
            }
        }

        if($this->view->globalDisableProgram) $programID = $this->config->global->defaultProgram;
        $topProgramID = $this->program->getTopByID($programID);

        if($model == 'kanban')
        {
            $this->lang->project->aclList    = $this->lang->project->kanbanAclList;
            $this->lang->project->subAclList = $this->lang->project->kanbanSubAclList;
        }

        $sprintConcept = empty($this->config->custom->sprintConcept) ?
        $this->config->executionCommonList[$this->app->getClientLang()][0] :
        $this->config->executionCommonList[$this->app->getClientLang()][1];

        $withProgram = $this->config->systemMode == 'ALM' ? true : false;
        $allProducts = array('0' => '') + $this->program->getProductPairs($programID, 'all', 'noclosed', '', $shadow, $withProgram);

        $this->view->title               = $this->lang->project->create;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('project', 'browse') : '';
        $this->view->pmUsers             = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users               = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjects        = $this->project->getPairsByModel($model);
        $this->view->products            = $products;
        $this->view->allProducts         = $allProducts;
        $this->view->productPlans        = array('0' => '') + $productPlans;
        $this->view->branchGroups        = $this->loadModel('branch')->getByProducts(array_keys($products), 'noclosed');
        $this->view->programID           = $programID;
        $this->view->productID           = isset($output['productID']) ? $output['productID'] : 0;
        $this->view->branchID            = isset($output['branchID']) ? $output['branchID'] : 0;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs($topProgramID);
        $this->view->model               = $model;
        $this->view->name                = $name;
        $this->view->code                = $code;
        $this->view->team                = $team;
        $this->view->acl                 = $acl;
        $this->view->auth                = $auth;
        $this->view->whitelist           = $whitelist;
        $this->view->multiple            = $multiple;
        $this->view->hasProduct          = $hasProduct;
        $this->view->copyProjectID       = $copyProjectID;
        $this->view->programList         = $this->program->getParentPairs();
        $this->view->parentProgram       = $parentProgram;
        $this->view->URSRPairs           = $this->loadModel('custom')->getURSRPairs();
        $this->view->availableBudget     = $this->program->getBudgetLeft($parentProgram);
        $this->view->budgetUnitList      = $this->project->getBudgetUnitList();

        $this->display('project', 'create');
    }

    /**
     * Edit a project.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function edit($projectID = 0, $from = '')
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
        if($project->model == 'kanban')
        {
            unset($this->lang->project->authList['reset']);
            $this->lang->project->aclList    = $this->lang->project->kanbanAclList;
            $this->lang->project->subAclList = $this->lang->project->kanbanSubAclList;
        }

        if($_POST)
        {
            $oldPlanList = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->andWhere('plan')->ne(0)->fetchPairs('plan');
            $oldPlans    = array();
            foreach($oldPlanList as $oldPlanIDList)
            {
                if(is_numeric($oldPlanIDList)) $oldPlans[$oldPlanIDList] = $oldPlanIDList;
                if(!is_numeric($oldPlanIDList))
                {
                    $oldPlanIDList = explode(',', $oldPlanIDList);
                    foreach($oldPlanIDList as $oldPlanID) $oldPlans[$oldPlanID] = $oldPlanID;
                }
            }

            $changes = $this->project->update($projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            /* Link the plan stories. */
            $newPlans = array();
            if(isset($_POST['plans']))
            {
                foreach($_POST['plans'] as $plans)
                {
                    foreach($plans as $planIDList)
                    {
                        foreach($planIDList as $planID) $newPlans[$planID] = $planID;
                    }
                }
            }

            $diffResult = array_diff($oldPlans, $newPlans);
            if(!empty($newPlans) and !empty($diffResult))
            {
                $this->loadModel('productplan')->linkProject($projectID, $newPlans);
            }

            $message = $this->executeHooks($projectID);
            if($message) $this->lang->saveSuccess = $message;

            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

            $locateLink = ($this->session->projectList and $from != 'view') ? $this->session->projectList : inLink('view', "projectID=$projectID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $withProgram = $this->config->systemMode == 'ALM' ? true : false;

        $linkedBranchList    = array();
        $productPlans        = array();
        $branches            = $this->project->getBranchesByProject($projectID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);
        $allProducts         = $this->program->getProductPairs($project->parent, 'all', 'noclosed', '', 0, $withProgram);
        $linkedProducts      = $this->loadModel('product')->getProducts($projectID, 'all', '', true, $linkedProductIdList);
        $parentProject       = $this->program->getByID($project->parent);
        $plans               = $this->productplan->getGroupByProduct(array_keys($linkedProducts), 'skipParent|unexpired');
        $projectStories      = $this->project->getStoriesByProject($projectID);
        $projectBranches     = $this->project->getBranchGroupByProject($projectID, array_keys($linkedProducts));

        /* If the story of the product which linked the project, you don't allow to remove the product. */
        $unmodifiableProducts     = array();
        $unmodifiableBranches     = array();
        $unmodifiableMainBranches = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->name;
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranchList[$branchID] = $branchID;

                if(!isset($productPlans[$productID])) $productPlans[$productID] = isset($plans[$productID][BRANCH_MAIN]) ? $plans[$productID][BRANCH_MAIN] : array();
                $productPlans[$productID] += isset($plans[$productID][$branchID]) ? $plans[$productID][$branchID] : array();

                if(!empty($projectStories[$productID][$branchID]) or !empty($projectBranches[$productID][$branchID]))
                {
                    if($branchID == BRANCH_MAIN) $unmodifiableMainBranches[$productID] = $branchID;
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                }
            }
        }

        $productPlansOrder = array();
        foreach($productPlans as $productID => $plan)
        {
            $orderPlans    = $this->loadModel('productPlan')->getListByIds(array_keys($plan), true);
            $orderPlansMap = array_keys($orderPlans);
            foreach($orderPlansMap as $planMapID)
            {
                $productPlansOrder[$productID][$planMapID] = $productPlans[$productID][$planMapID];
            }
        }

        $canChangeModel = $this->project->checkCanChangeModel($projectID, $project->model);

        unset($this->lang->project->modelList['']);

        $this->view->title      = $this->lang->project->edit;
        $this->view->position[] = $this->lang->project->edit;

        $this->view->PMUsers                  = $this->user->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users                    = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project                  = $project;
        $this->view->programList              = $this->program->getParentPairs();
        $this->view->program                  = $this->program->getByID($project->parent);
        $this->view->projectID                = $projectID;
        $this->view->allProducts              = array('0' => '') + $allProducts;
        $this->view->multiBranchProducts      = $this->loadModel('product')->getMultiBranchPairs();
        $this->view->productPlans             = array_filter($productPlansOrder);
        $this->view->linkedProducts           = $linkedProducts;
        $this->view->branches                 = $branches;
        $this->view->executions               = $this->execution->getPairs($projectID);
        $this->view->unmodifiableProducts     = $unmodifiableProducts;
        $this->view->unmodifiableBranches     = $unmodifiableBranches;
        $this->view->unmodifiableMainBranches = $unmodifiableMainBranches;
        $this->view->branchGroups             = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), 'noclosed', $linkedBranchList);
        $this->view->URSRPairs                = $this->custom->getURSRPairs();
        $this->view->parentProject            = $parentProject;
        $this->view->parentProgram            = $this->program->getByID($project->parent);
        $this->view->availableBudget          = $this->program->getBudgetLeft($parentProject) + (float)$project->budget;
        $this->view->budgetUnitList           = $this->project->getBudgetUnitList();
        $this->view->model                    = $project->model;
        $this->view->disableModel             = (isset($canChangeModel) and $canChangeModel) ? '' : 'disabled';
        $this->view->teamMembers              = $this->user->getTeamMemberPairs($projectID, 'project');

        $this->display();
    }

    /**
     * Batch edit projects.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $this->loadModel('action');
        $this->loadModel('execution');

        if($this->post->names)
        {
            $allChanges = $this->project->batchUpdate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($allChanges))
            {
                foreach($allChanges as $projectID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->action->create('project', $projectID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            $locateLink = $this->session->projectList;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        if(!$this->post->projectIdList) return print(js::locate($this->session->projectList, 'parent'));
        $projectIdList = $this->post->projectIdList;
        $projects      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');

        /* Get program list. */
        $programs           = $this->loadModel('program')->getParentPairs();
        $unauthorizedIDList = array();
        foreach($projects as $project)
        {
            if(!isset($programs[$project->parent]) and !in_array($project->parent, $unauthorizedIDList)) $unauthorizedIDList[] = $project->parent;
            $appendPMUsers[$project->PM] = $project->PM;
        }
        $unauthorizedPrograms = $this->program->getPairsByList($unauthorizedIDList);

        $this->view->title      = $this->lang->project->batchEdit;
        $this->view->position[] = $this->lang->project->batchEdit;

        $this->view->projects             = $projects;
        $this->view->programs             = $programs;
        $this->view->unauthorizedPrograms = $unauthorizedPrograms;
        $this->view->PMUsers              = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $appendPMUsers);

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
        if(!defined('RUN_MODE') || RUN_MODE != 'api') $projectID = $this->project->saveState((int)$projectID, $this->project->getPairsByProgram());

        $this->session->set('teamList', $this->app->getURI(true), 'project');

        $projectID = $this->project->setMenu($projectID);
        $project   = $this->project->getById($projectID);

        if(in_array($this->config->systemMode, array('ALM', 'PLM')))
        {
            $programList = array_filter(explode(',', $project->path));
            array_pop($programList);
            $this->view->programList = $this->loadModel('program')->getPairsByList($programList);
        }

        if(empty($project) || strpos('scrum,waterfall,kanban,agileplus,waterfallplus,ipd', $project->model) === false)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('project', 'browse')));
        }

        $products = $this->loadModel('product')->getProducts($projectID);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if(isset($product->branches))
            {
                foreach($product->branches as $branchID) $linkedBranches[$branchID] = $branchID;
            }
        }

        /* Check exist extend fields. */
        $isExtended = false;
        if($this->config->edition != 'open')
        {
            $extend = $this->loadModel('workflowaction')->getByModuleAndAction('project', 'view');
            if(!empty($extend) and $extend->extensionType == 'extend') $isExtended = true;
        }

        $this->executeHooks($projectID);

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
        $this->view->planGroup    = $this->loadModel('execution')->getPlans($products);
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', 30, 'all', $projectID);
        $this->view->isExtended   = $isExtended;

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
            if(dao::isError()) return print(js::error(dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $groupID));
            return print(js::closeModal('parent.parent'));
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
        $this->loadModel('execution');
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
        $this->session->set('reportList',      $uri, 'qa');

        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            $this->session->set('riskList', $uri, 'project');
            $this->session->set('issueList', $uri, 'project');
        }

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById($param, 'account');
            if($user) $account = $user->account;
        }
        $period     = $type == 'account' ? 'all' : $type;
        $date       = empty($date) ? '' : date('Y-m-d', $date);
        $actions    = $this->loadModel('action')->getDynamic($account, $period, $orderBy, 50, 'all', $projectID, 'all', $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction, $type);

        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($actions) : $this->action->getDynamicCount();

        $project = $this->project->getByID($projectID);

        /* Assign. */
        $this->view->title        = $project->name . $this->lang->colon . $this->lang->project->dynamic;
        $this->view->userIdPairs  = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $this->view->accountPairs = $this->user->getPairs('noletter|nodeleted');
        $this->view->projectID    = $projectID;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->account      = $account;
        $this->view->param        = $param;
        $this->view->dateGroups   = $dateGroups;
        $this->view->direction    = $direction;
        $this->view->recTotal     = $recTotal;
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
    public function execution($status = 'all', $projectID = 0, $orderBy = 'order_asc', $productID = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->loadModel('execution');
        $this->loadModel('task');
        $this->loadModel('programplan');
        $this->session->set('executionList', $this->app->getURI(true), 'project');

        if($this->cookie->showTask) $this->session->set('taskList', $this->app->getURI(true), 'project');

        $projects  = $this->project->getPairsByProgram();
        $projectID = $this->project->saveState($projectID, $projects);
        $project   = $this->project->getByID($projectID);
        $this->project->setMenu($projectID);

        if(!$projectID) return print(js::locate($this->createLink('project', 'browse')));
        if(!$project->multiple)
        {
            $executionID = $this->execution->getNoMultipleID($projectID);
            return print(js::locate($this->createLink('execution', 'task', "executionID=$executionID")));
        }
        if(!empty($project->model) and $project->model == 'kanban' and !(defined('RUN_MODE') and RUN_MODE == 'api')) return print(js::locate($this->createLink('project', 'index', "projectID=$projectID")));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('program')->refreshStats(); // Refresh stats fields of projects.

        $allExecution = $this->execution->getStatData($projectID, 'all');
        $this->view->allExecutionNum = empty($allExecution);

        $this->view->title      = $this->lang->execution->allExecutions;
        $this->view->position[] = $this->lang->execution->allExecutions;

        $executionStats  = $this->execution->getStatData($projectID, $status, $productID, 0, $this->cookie->showTask, '', $orderBy, $pager);
        $showToggleIcon  = false;

        foreach($executionStats as $execution)
        {
            if(!empty($execution->tasks) or !empty($execution->children)) $showToggleIcon = true;
        }

        $changeStatusHtml  = "<div class='btn-group dropup'>";
        $changeStatusHtml .= "<button data-toggle='dropdown' type='button' class='btn'>{$this->lang->statusAB} <span class='caret'></span></button>";
        $changeStatusHtml .= "<div class='dropdown-menu search-list'><div class='list-group'>";
        foreach($this->lang->execution->statusList as $statusAB => $statusText)
        {
            $actionLink        = $this->createLink('execution', 'batchChangeStatus', "status=$statusAB&projectID=$projectID");
            $changeStatusHtml .= html::a('#', $statusText, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#executionForm')\" onmouseover=\"setBadgeStyle(this, true);\" onmouseout=\"setBadgeStyle(this, false)\"");
        }
        $changeStatusHtml .= "</div></div></div>";

        if($project->model == 'ipd' and $this->config->edition == 'ipd')
        {
            $this->view->reviewPoints = $this->loadModel('review')->getReviewPointByProject($projectID);
        }

        $this->view->executionStats   = $executionStats;
        $this->view->showToggleIcon   = $showToggleIcon;
        $this->view->productList      = $this->loadModel('product')->getProductPairsByProject($projectID, 'all', '', false);
        $this->view->productID        = $productID;
        $this->view->product          = $this->product->getByID($productID);
        $this->view->projectID        = $projectID;
        $this->view->project          = $project;
        $this->view->projects         = $projects;
        $this->view->pager            = $pager;
        $this->view->orderBy          = $orderBy;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->status           = $status;
        $this->view->isStage          = (isset($project->model) and in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) ? true : false;
        $this->view->changeStatusHtml = common::hasPriv('execution', 'batchChangeStatus') ? $changeStatusHtml : '';

        $this->display();
    }

    /**
     * Project bug list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branchID
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
    public function bug($projectID = 0, $productID = 0, $branchID = 'all', $orderBy = 'status,id_desc', $build = 0, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');
        $this->loadModel('product');
        $this->loadModel('datatable');
        $this->loadModel('tree');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true), 'project');
        $this->project->setMenu($projectID);

        $product  = $this->product->getById($productID);
        $project  = $this->project->getByID($projectID);
        $type     = strtolower($type);
        $queryID  = ($type == 'bysearch') ? (int)$param : 0;
        $products = $this->product->getProducts($projectID);

        if(!$project->multiple) unset($this->config->bug->datatable->fieldList['execution']);
        if(!$project->hasProduct)
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model != 'scrum') unset($this->config->bug->search['fields']['plan']);
        }
        if(!$project->multiple and !$project->hasProduct) unset($this->config->bug->search['fields']['plan']);

        $productPairs = array('0' => $this->lang->product->all);
        foreach($products as $productData) $productPairs[$productData->id] = $productData->name;

        if($project->hasProduct) $this->lang->modulePageNav = $this->product->select($productPairs, $productID, 'project', 'bug', $projectID, $branchID);

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->bug->common;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->bug->common;

        $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);

        /* team member pairs. */
        $memberPairs   = array();
        $memberPairs[] = "";
        $teamMembers   = $this->project->getTeamMembers($projectID);
        foreach($teamMembers as $key => $member) $memberPairs[$key] = $member->realname;

        /* Build the search form. */
        $actionURL = $this->createLink('project', 'bug', "projectID=$projectID&productID=$productID&branchID=$branchID&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->loadModel('execution')->buildBugSearchForm($products, $queryID, $actionURL, 'project');

        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product and $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->loadModel('branch')->getList($productID, $projectID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        $moduleID = $type != 'bysearch' ? $param : 0;
        $modules  = $this->tree->getAllModulePairs('bug');

        /* Get module tree.*/
        $extra = array('projectID' => $projectID, 'orderBy' => $orderBy, 'type' => $type, 'build' => $build, 'branchID' => $branchID);
        if($projectID and empty($productID) and count($products) > 1)
        {
            $moduleTree = $this->tree->getBugTreeMenu($projectID, $productID, 0, array('treeModel', 'createBugLink'), $extra);
        }
        elseif(!empty($products))
        {
            $productID  = empty($productID) ? reset($products)->id : $productID;
            $moduleTree = $this->tree->getTreeMenu($productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('productID' => $productID, 'branchID' => $branchID), $branchID);
        }
        else
        {
            $moduleTree = '';
        }
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        /* Process the openedBuild and resolvedBuild fields. */
        $bugs = $this->bug->getProjectBugs($projectID, $productID, $branchID, $build, $type, $param, $sort, '', $pager);
        $bugs = $this->bug->processBuildForBugs($bugs);
        $bugs = $this->bug->checkDelayedBugs($bugs);

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

        $showModule  = !empty($this->config->datatable->projectBug->showModule) ? $this->config->datatable->projectBug->showModule : '';

        /* Assign. */
        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->bugs            = $bugs;
        $this->view->tabID           = 'bug';
        $this->view->build           = $this->loadModel('build')->getById($build);
        $this->view->buildID         = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->productID       = $productID;
        $this->view->project         = $project;
        $this->view->branchID        = empty($this->view->build->branch) ? $branchID : $this->view->build->branch;
        $this->view->memberPairs     = $memberPairs;
        $this->view->type            = $type;
        $this->view->param           = $param;
        $this->view->builds          = $this->loadModel('build')->getBuildPairs($productID);
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->executions      = $executions;
        $this->view->plans           = $this->loadModel('productplan')->getPairs($productID ? $productID : array_keys($products));
        $this->view->stories         = $storyList;
        $this->view->tasks           = $taskList;
        $this->view->projectPairs    = $this->project->getPairsByProgram();
        $this->view->moduleTree      = $moduleTree;
        $this->view->modules         = $modules;
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
        $this->view->setModule       = true;
        $this->view->showBranch      = false;

        $this->display();
    }

    /**
     * Project case list.
     *
     * @param  int        $projectID
     * @param  int        $productID
     * @param  string|int $branch
     * @param  string     $browseType
     * @param  int        $param
     * @param  string     $caseType
     * @param  string     $orderBy
     * @param  int        $recTotal
     * @param  int        $recPerPage
     * @param  int        $pageID
     * @access public
     * @return void
     */
    public function testcase($projectID = 0, $productID = 0, $branch = 'all', $browseType = 'all', $param = 0, $caseType = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('product');
        $this->session->set('bugList', $this->app->getURI(true), 'project');
        $this->session->set('reviewList', $this->app->getURI(true), 'project');

        $products = array('0' => $this->lang->product->all) + $this->product->getProducts($projectID, 'all', '', false);

        $extra = "$projectID,$browseType";

        $hasProduct = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch('hasProduct');
        if($hasProduct) $this->lang->modulePageNav = $this->product->select($products, $productID, 'project', 'testcase', $extra, $branch);

        echo $this->fetch('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID");
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
     * @param  int    $projectID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function build($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc')
    {
        /* Load module and get project. */
        $this->loadModel('build');
        $this->loadModel('product');
        $project = $this->project->getByID($projectID);
        $this->project->setMenu($projectID);

        $this->session->set('buildList', $this->app->getURI(true), 'project');

        /* Get products' list. */
        $products = $this->product->getProducts($projectID, 'all', '', false);
        $products = array('' => '') + $products;


        $fromModule = $this->app->tab == 'project' ? 'projectbuild' : 'project';
        $fromMethod = $this->app->tab == 'project' ? 'browse' : 'build';
        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink($fromModule, $fromMethod, "projectID=$projectID&type=bysearch&queryID=myQueryID");

        $devel         = $project->model == 'waterfall' ? true : false;
        $executions    = $this->loadModel('execution')->getByProject($projectID, 'all', '', true, $devel);
        $this->config->build->search['fields']['execution'] = $this->project->lang->executionCommon;
        $this->config->build->search['params']['execution'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $executions);
        if(!$project->hasProduct) unset($this->config->build->search['fields']['product']);

        $product = $param ? $this->loadModel('product')->getById($param) : '';
        if($product and $product->type != 'normal')
        {
            $this->loadModel('build');
            $this->loadModel('branch');
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($product->id, '', $projectID);
            $this->config->build->search['fields']['branch'] = sprintf($this->lang->build->branchName, $this->lang->product->branchName[$product->type]);
            $this->config->build->search['params']['branch'] = array('operator' => '=', 'control' => 'select', 'values' => $branches);
        }
        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'project');

        if($type == 'bysearch')
        {
            $builds = $this->build->getProjectBuildsBySearch((int)$projectID, (int)$param, $orderBy);
        }
        else
        {
            $builds = $this->build->getProjectBuilds((int)$projectID, $type, $param, $orderBy);
        }

        /* Set project builds. */
        $projectBuilds = array();
        $productList   = $this->product->getProducts($projectID);
        $showBranch    = false;
        if(!empty($builds))
        {
            foreach($builds as $build)
            {
                $build->builds = $this->build->getByList($build->builds);
                $projectBuilds[$build->product][] = $build;
            }

            /* Get branch name. */
            $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($projectBuilds));
            foreach($builds as $build)
            {
                $build->branchName = '';
                if(isset($branchGroups[$build->product]))
                {
                    $showBranch  = true;
                    $branchPairs = $branchGroups[$build->product];
                    foreach(explode(',', trim($build->branch, ',')) as $branchID)
                    {
                        if(isset($branchPairs[$branchID])) $build->branchName .= "{$branchPairs[$branchID]},";
                    }
                    $build->branchName = trim($build->branchName, ',');

                    if(empty($build->branchName) and empty($build->builds)) $build->branchName = $this->lang->branch->main;
                }
            }
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
        $this->view->executions    = $this->execution->getPairs();
        $this->view->buildPairs    = $this->loadModel('build')->getBuildPairs(0);
        $this->view->type          = $type;
        $this->view->showBranch    = $showBranch;
        $this->view->fromModule    = $fromModule;
        $this->view->fromMethod    = $fromMethod;

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
            if($this->group->checkNavModule($menu, $moduleName) or $type != 'byGroup') $this->app->loadLang($moduleName);
        }

        if(!empty($_POST))
        {
            if($type == 'byGroup')  $result = $this->group->updatePrivByGroup($groupID, $menu, $version);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('group', "projectID=$group->project")));
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
            if($project->hasProduct)
            {
                if($this->config->URAndSR) unset($this->lang->resource->requirement);
                unset($this->lang->resource->productplan);
                unset($this->lang->resource->tree);
            }
            else
            {
                $this->lang->productplan->common  = $this->lang->productplan->plan;
                $this->lang->projectstory->common = $this->lang->projectstory->storyCommon;
                $this->lang->projectstory->story  = $this->lang->projectstory->storyList;
                $this->lang->projectstory->view   = $this->lang->projectstory->storyView;
                unset($this->lang->resource->project->manageProducts);
                unset($this->lang->resource->projectstory->linkStory);
                unset($this->lang->resource->projectstory->importplanstories);
                unset($this->lang->resource->projectstory->unlinkStory);
                unset($this->lang->resource->projectstory->batchUnlinkStory);
                unset($this->lang->resource->story->view);
                if($this->config->URAndSR) unset($this->lang->resource->requirement->view);
                if($this->config->URAndSR) unset($this->lang->resource->requirement->batchChangeBranch);
                unset($this->lang->resource->tree->browseTask);
                unset($this->lang->resource->tree->browsehost);
                unset($this->lang->resource->tree->editHost);
                unset($this->lang->resource->tree->fix);
            }

            if($project->model == 'waterfall' or $project->model == 'waterfallplus')
            {
                unset($this->lang->resource->productplan);
                unset($this->lang->resource->projectplan);
            }
            if($project->model == 'scrum') unset($this->lang->resource->projectstory->track);

            if(!$project->multiple and !$project->hasProduct)
            {
                unset($this->lang->resource->story->batchChangePlan);
                unset($this->lang->resource->execution->importplanstories);
            }

            $this->view->project  = $project;
            $this->lang->resource = $this->project->processProjectPrivs($project->multiple ? $project->model : 'noSprint');
        }

        $this->display();
    }

    /**
     * Browse team of a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function team($projectID = 0)
    {
        $this->session->set('teamList', $this->app->getURI(true), 'project');

        $this->app->loadLang('execution');
        $this->project->setMenu($projectID);

        $project = $this->project->getById($projectID);
        $deptID  = $this->app->user->admin ? 0 : $this->app->user->dept;

        $this->view->title        = $project->name . $this->lang->colon . $this->lang->project->team;
        $this->view->projectID    = $projectID;
        $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
        $this->view->deptUsers    = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        $this->view->canBeChanged = common::canModify('project', $project);

        $this->display();
    }

    /**
     * Unlink a memeber.
     *
     * @param  int    $projectID
     * @param  int    $userID
     * @param  string $confirm  yes|no
     * @param  string $removeExecution  yes|no
     * @access public
     * @return void
     */
    public function unlinkMember($projectID, $userID, $confirm = 'no', $removeExecution = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->project->confirmUnlinkMember, $this->inlink('unlinkMember', "projectID=$projectID&userID=$userID&confirm=yes")));

        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $this->project->unlinkMember($projectID, $account, $removeExecution);
        if(!dao::isError()) $this->loadModel('action')->create('team', $projectID, 'managedTeam');

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
            return $this->send($response);
        }
        echo js::locate($this->inlink('team', "projectID=$projectID"), 'parent');
    }

    /**
     * Manage project members.
     *
     * @param  int    $projectID
     * @param  int    $dept
     * @param  int    $copyProjectID
     * @access public
     * @return void
     */
    public function manageMembers($projectID, $dept = '', $copyProjectID = 0)
    {
        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');
        $this->loadModel('execution');
        $this->project->setMenu($projectID);
        $project = $this->project->getById($projectID);

        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(empty($project->multiple))
            {
                $executionID = $this->execution->getNoMultipleID($projectID);
                if($executionID) $this->execution->manageMembers($executionID);
            }

            $this->loadModel('action')->create('team', $projectID, 'ManagedTeam');

            $link = $this->session->teamList ? $this->session->teamList : $this->createLink('project', 'team', "projectID=$projectID");
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $link));
        }

        $users        = $this->user->getPairs('noclosed|nodeleted|devfirst');
        $roles        = $this->user->getUserRoles(array_keys($users));
        $deptUsers    = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);
        $userInfoList = $this->user->getUserDisplayInfos(array_keys($users), $dept);

        $currentMembers = $this->project->getTeamMembers($projectID);
        $members2Import = $this->project->getMembers2Import($copyProjectID, array_keys($currentMembers));

        $this->view->title      = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $this->view->position[] = $this->lang->project->manageMembers;

        $this->view->project        = $project;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->userInfoList   = $userInfoList;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = array('' => '') + $this->dept->getOptionMenu();
        $this->view->currentMembers = $currentMembers;
        $this->view->members2Import = $members2Import;
        $this->view->teams2Import   = array('' => '') + $this->loadModel('personnel')->getCopiedObjects($projectID, 'project', true);
        $this->view->copyProjectID  = $copyProjectID;
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
            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $group      = $this->group->getById($groupID);
        $project    = $this->project->getByID($group->project);
        $groupUsers = $this->group->getUserPairs($groupID);
        $allUsers   = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        if($project->acl != 'open')
        {
            $canViewMembers = $this->dao->select('account')->from(TABLE_USERVIEW)->where("CONCAT(',', projects, ',')")->like("%,$group->project,%")->fetchPairs();
            foreach($otherUsers as $account => $otherUser)
            {
                if(!isset($canViewMembers[$account])) unset($otherUsers[$account]);
            }
        }

        $outsideUsers = $this->loadModel('user')->getPairs('outside|noclosed|noletter|noempty');
        if($project->acl != 'open')
        {
            foreach($outsideUsers as $account => $outsideUser)
            {
                if(!isset($canViewMembers[$account])) unset($outsideUsers[$account]);
            }
        }

        $this->view->outsideUsers = array_diff_assoc($outsideUsers, $groupUsers);

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
             if(dao::isError()) return print(js::error(dao::getError()));
             return print(js::closeModal('parent.parent', 'this'));
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
            return print(js::closeModal('parent.parent', 'this'));
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
            if(dao::isError()) return print(js::error(dao::getError()));

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
                        if(dao::isError()) return print(js::error(dao::getError()));

                        if($this->post->comment != '' or !empty($changes))
                        {
                            $actionID = $this->action->create('project', $projectID, 'Started', $this->post->comment);
                            $this->action->logHistory($actionID, $changes);
                        }
                    }
                }
            }
            $this->loadModel('common')->syncPPEStatus($projectID);

            $this->executeHooks($projectID);
            return print(js::reload('parent.parent'));
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
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            return print(js::reload('parent.parent'));
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
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            return print(js::reload('parent.parent'));
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
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            return print(js::reload('parent.parent'));
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
     * @param  string  $confirm
     * @param  string  $from browse|view
     * @access public
     * @return void
     */
    public function delete($projectID, $confirm = 'no', $from = 'browse')
    {
        if($confirm == 'no')
        {
            $project = $this->project->getByID($projectID);
            return print(js::confirm(sprintf($this->lang->project->confirmDelete, $project->name), $this->createLink('project', 'delete', "projectID=$projectID&confirm=yes&from=$from")));
        }
        else
        {
            $this->loadModel('user');
            $this->loadModel('action');

            $this->project->delete(TABLE_PROJECT, $projectID);
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('execution')->eq($projectID)->exec();
            $this->user->updateUserView($projectID, 'project');

            /* Delete the execution under the project. */
            $executionIdList = $this->loadModel('execution')->getPairs($projectID);

            /* Delete shadow product.*/
            $project = $this->project->getByID($projectID);
            if(!$project->hasProduct)
            {
                $productID = $this->loadModel('product')->getProductIDByProject($projectID);
                $product   = $this->product->getById($productID);
                if($product->shadow) $this->dao->update(TABLE_PRODUCT)->set('deleted')->eq(1)->where('id')->eq($productID)->exec();
            }

            $message = $this->executeHooks($projectID);
            if($message) $this->lang->saveSuccess = $message;

            if(empty($executionIdList))
            {
                if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
                if($from == 'view') return print(js::locate($this->createLink('project', 'browse'), 'parent'));
                return print(js::reload('parent'));
            }

            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->in(array_keys($executionIdList))->exec();
            foreach($executionIdList as $executionID => $execution) $this->action->create('execution', $executionID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
            $this->user->updateUserView($executionIdList, 'sprint');

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));

            $this->session->set('project', '');
            if($from == 'view') return print(js::locate($this->createLink('project', 'browse'), 'parent'));
            return print(js::reload('parent'));
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
        $projectID = $this->project->setMenu($projectID);
        $project   = $this->project->getById($projectID);
        if(isset($project->acl) and $project->acl == 'open') $this->locate($this->createLink('project', 'index', "projectID=$projectID"));

        echo $this->fetch('personnel', 'whitelist', "objectID=$projectID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID&from=$from");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $projectID
     * @param  int     $deptID
     * @param  int     $copyID
     * @param  int     $programID
     * @param  int     $from
     * @access public
     * @return void
     */
    public function addWhitelist($projectID = 0, $deptID = 0, $copyID = 0, $programID = 0, $from = 'project')
    {
        $projectID = $this->project->setMenu($projectID);
        $project   = $this->project->getById($projectID);
        if(isset($project->acl) and $project->acl == 'open') $this->locate($this->createLink('project', 'index', "projectID=$projectID"));

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$projectID&dept=$deptID&copyID=$copyID&objectType=project&module=project&programID=$programID&from=$from");
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
        $this->loadModel('execution');

        $project = $this->project->getById($projectID);
        if(!$project->hasProduct) return print(js::error($this->lang->project->cannotManageProducts) . js::locate('back'));

        $executions = $this->execution->getPairs($projectID);

        if(!empty($_POST))
        {
            if(!isset($_POST['products']) and !isset($_POST['otherProducts']))
            {
                dao::$errors['message'][] = $this->lang->project->errorNoProducts;
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $oldProducts = $this->product->getProducts($projectID);
            if($project->multiple and $project->model != 'waterfall' and $project->model != 'waterfallplus') $oldExecutionProducts = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($executions))->fetchGroup('project', 'product');

            $this->project->updateProducts($projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(empty($project->multiple))
            {
                $executionID = $this->execution->getNoMultipleID($projectID);
                if($executionID) $this->execution->updateProducts($executionID);
            }

            $newProducts   = $this->product->getProducts($projectID);
            $oldProductIDs = array_keys($oldProducts);
            $newProductIDs = array_keys($newProducts);
            $diffProducts  = array_merge(array_diff($oldProductIDs, $newProductIDs), array_diff($newProductIDs, $oldProductIDs));
            if($diffProducts) $this->loadModel('action')->create('project', $projectID, 'Managed', '', !empty($_POST['products']) ? join(',', $_POST['products']) : '');

            if(empty($project->division))
            {
                foreach(array_keys($executions) as $executionID)
                {
                    $this->execution->updateProducts($executionID);
                    if($diffProducts) $this->loadModel('action')->create('execution', $executionID, 'Managed', '', implode(',', array_keys($newProducts)));
                }
            }

            if($project->multiple and $project->model != 'waterfall' and $project->model != 'waterfallplus')
            {
                $unlinkedProducts = array_diff($oldProductIDs, $newProductIDs);
                if(!empty($unlinkedProducts))
                {
                    $unlinkedProductPairs = array();
                    foreach($unlinkedProducts as $unlinkedProduct) $unlinkedProductPairs[$unlinkedProduct] = $oldProducts[$unlinkedProduct]->name;

                    $unlinkExecutions = array();
                    foreach($oldExecutionProducts as $executionID => $executionProducts)
                    {
                        $unlinkExecutionProducts = array_intersect_key($unlinkedProductPairs, $executionProducts);
                        if($unlinkExecutionProducts) $unlinkExecutions[$executionID] = $unlinkExecutionProducts;
                    }

                    foreach($unlinkExecutions as $executionID => $unlinkExecutionProducts) $this->action->create('execution', $executionID, 'unlinkproduct', '', implode(',', $unlinkExecutionProducts));
                }
            }

            $locateLink = inLink('manageProducts', "projectID=$projectID");
            if($from == 'program')  $locateLink = $this->session->projectList;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        if($this->app->tab == 'program')
        {
            $this->program->setMenu($project->parent);
        }
        else if($this->app->tab == 'project')
        {
            $this->project->setMenu($projectID);
        }

        $linkedBranches      = array();
        $linkedBranchIdList  = array();
        $branches            = $this->project->getBranchesByProject($projectID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);
        $allProducts         = $this->program->getProductPairs($project->parent, 'all', 'noclosed', $linkedProductIdList);
        $linkedProducts      = $this->product->getProducts($projectID, 'all', '', true, $linkedProductIdList);
        $projectStories      = $this->project->getStoriesByProject($projectID);
        $projectBranches     = $this->project->getBranchGroupByProject($projectID, array_keys($linkedProducts));

        /* If the story of the product which linked the project, you don't allow to remove the product. */
        $unmodifiableProducts     = array();
        $unmodifiableBranches     = array();
        $unmodifiableMainBranches = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $linkedBranches[$productID] = array();
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranches[$productID][$branchID] = $branchID;
                $linkedBranchIdList[$branchID] = $branchID;

                if(!empty($projectStories[$productID][$branchID]) or !empty($projectBranches[$productID][$branchID]))
                {
                    if($branchID == BRANCH_MAIN) $unmodifiableMainBranches[$productID] = $branchID;
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                }
            }
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($allProducts), 'ignoreNormal|noclosed', $linkedBranchIdList);
        if($this->config->systemMode == 'ALM')
        {
            $topProgramID           = $project->parent ? $this->program->getTopByPath($project->path) : 0;
            $productsGroupByProgram = $this->product->getProductsGroupByProgram();

            $currentProducts = array();
            $otherProducts   = array();
            foreach($productsGroupByProgram as $programID => $programProducts)
            {
                if($programID != $topProgramID)
                {
                    foreach($programProducts as $productID => $productName)
                    {
                        if(!empty($branchGroups[$productID]))
                        {
                            foreach($branchGroups[$productID] as $branchID => $branchName)
                            {
                                if(isset($linkedProducts[$productID]) and isset($linkedBranches[$productID][$branchID])) continue;

                                $otherProducts["{$productID}_{$branchID}"] = $productName . '_' . $branchName;
                            }
                        }
                        else
                        {
                            if(isset($linkedProducts[$productID])) continue;
                            $otherProducts[$productID] = $productName;
                        }
                    }
                }
                else
                {
                    $currentProducts += $programProducts;
                }
            }

            $this->view->currentProducts = $currentProducts;
            $this->view->otherProducts   = $otherProducts;
        }

        /* Assign. */
        $this->view->title                    = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $this->view->project                  = $project;
        $this->view->linkedProducts           = $linkedProducts;
        $this->view->linkedBranches           = $linkedBranches;
        $this->view->branches                 = $branches;
        $this->view->project                  = $project;
        $this->view->unmodifiableProducts     = $unmodifiableProducts;
        $this->view->unmodifiableBranches     = $unmodifiableBranches;
        $this->view->unmodifiableMainBranches = $unmodifiableMainBranches;
        $this->view->branchGroups             = $branchGroups;
        $this->view->executions               = $executions;
        $this->view->allBranches              = $this->branch->getByProducts(array_keys($allProducts), 'ignoreNormal');
        $this->view->allProducts              = $allProducts;

        $this->display();
    }

   /**
     * AJAX: get executions of a project in html select.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $mode
     * @param  string $type       all|sprint|stage|kanban
     * @access public
     * @return void
     */
    public function ajaxGetExecutions($projectID, $executionID = 0, $mode = '', $type = 'all')
    {
        $disabled   = '';
        $executions = array('' => '');

        if($projectID)
        {
            $project    = $this->project->getById($projectID);
            $executions = $this->loadModel('execution')->getPairs($projectID, $type, $mode);
            $executions = array('' => '') + $this->execution->resetExecutionSorts($executions, array(), array(), $projectID);
            $disabled   = !empty($project->multiple) ? '' : 'disabled';
        }

        if($this->app->getViewType() == 'json') return print(json_encode($executionList));
        return print(html::select('execution', $executions, $executionID, "class='form-control $disabled' $disabled"));
    }

    /**
     * AJAX: get a project by execution id.
     *
     * @param  string $executionID
     * @access public
     * @return string
     */
    public function ajaxGetPairsByExecution($executionID)
    {
        $execution    = $this->loadModel('execution')->getByID($executionID);
        $projectPairs = $this->loadModel('project')->getPairsByIdList($execution->project);

        if($this->app->getViewType() == 'json')
        {
            $project = array('id' => key($projectPairs), 'name' => reset($projectPairs));
            $pinyin  = common::convert2Pinyin(array(reset($projectPairs)));
            $project['namePinyin'] = zget($pinyin, $project['name']);

            return print(json_encode($project));
        }
        else
        {
            return print(html::select('project', $projectPairs, $execution->project, "class='form-control' onchange='loadProductExecutions({$execution->project}, this.value)'"));
        }
    }
}
