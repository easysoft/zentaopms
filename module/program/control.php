<?php
/**
 * The control file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id
 * @link        http://www.zentao.net
 */
class program extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
        $this->loadModel('group');
    }

    /**
     * Program create guide.
     *
     * @param  int    $programID
     * @param  string $from
     * @access public
     * @return void
     */
    public function createGuide($programID = 0, $from = 'PRJ')
    {
        $this->view->from      = $from;
        $this->view->programID = $programID;
        $this->display();
    }

    /**
     * Program home page.
     *
     * @access public
     * @return void
     */
    public function PGMIndex()
    {
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction();

        $this->view->title      = $this->lang->program->PGMIndex;
        $this->view->position[] = $this->lang->program->PGMIndex;
        $this->display();
    }

    /**
     * Project list.
     *
     * @param  varchar $status
     * @param  varchar $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function PGMBrowse($status = 'all', $orderBy = 'order_asc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction();

        if(common::hasPriv('program', 'pgmcreate')) $this->lang->pageActions = html::a($this->createLink('program', 'pgmcreate'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->PGMCreate, '', "class='btn btn-secondary'");

        $this->app->session->set('programList', $this->app->getURI(true));

        $programType = $this->cookie->programType ? $this->cookie->programType : 'bylist';

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($programType === 'bygrid')
        {
            $programs = $this->program->getProgramStats($status, 20, $orderBy, $pager);
        }
        else
        {
            $programs = $this->program->getPGMList($status, $orderBy, $pager, true);
        }

        $this->view->title       = $this->lang->program->PGMBrowse;
        $this->view->position[]  = $this->lang->program->PGMBrowse;

        $this->view->programs    = $programs;
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->programType = $programType;

        $this->display();
    }

    /**
     * Program products list.
     *
     * @param  int     $programID
     * @param  string  $browseType
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function PGMProduct($programID = 0, $browseType = 'noclosed', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->program->PGMProduct;
        $this->view->position[]  = $this->lang->program->PGMProduct;

        $this->view->program     = $this->program->getPGMByID($programID);
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->products    = $this->loadModel('product')->getStats($orderBy, $pager, $browseType, '', 'story', $programID);

        $this->display();
    }

    /**
     * Create a project.
     *
     * @param  int  $parentProgramID
     * @access public
     * @return void
     */
    public function PGMCreate($parentProgramID = 0)
    {
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction();

        if($_POST)
        {
            $projectID = $this->program->PGMCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('program', $projectID, 'opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('pgmbrowse')));
        }

        $this->view->title      = $this->lang->program->PGMCreate;
        $this->view->position[] = $this->lang->program->PGMCreate;

        $this->view->pmUsers       = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->poUsers       = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users         = $this->user->getPairs('noclosed|nodeleted');
        $this->view->parentProgram = $parentProgramID ? $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($parentProgramID)->fetch() : 0;
        $this->view->parents       = $this->program->getParentPairs();

        $this->display();
    }

    /**
     * Edit a program.
     *
     * @param  int $programID
     * @access public
     * @return void
     */
    public function PGMEdit($programID = 0)
    {
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction();

        $program = $this->program->getPGMByID($programID);

        if($_POST)
        {
            $changes = $this->program->PGMUpdate($programID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('program', $programID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('PGMBrowse')));
        }

        $parents = $this->program->getParentPairs();
        unset($parents[$programID]);

        $this->view->title       = $this->lang->program->PGMEdit;
        $this->view->position[]  = $this->lang->program->PGMEdit;

        $this->view->pmUsers     = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->poUsers     = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users       = $this->user->getPairs('noclosed|nodeleted');
        $this->view->program     = $program;
        $this->view->parents     = $parents;

        $this->display();
    }

    /**
     * View a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PGMView($programID = 0)
    {
        $this->lang->navGroup->program     = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $program = $this->program->getPGMByID($programID);

        $this->view->title       = $this->lang->program->PGMView;
        $this->view->position[]  = $this->lang->program->PGMView;

        $this->view->pmUsers     = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->program     = $program;
        $this->display();
    }

    /**
     * Close a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PGMClose($programID)
    {
        $program = $this->program->getPGMByID($programID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->close($programID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->program->PGMClose;
        $this->view->position[] = $this->lang->program->PGMClose;

        $this->view->project    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('program', $programID);

        $this->display('project', 'close');
    }

    /**
     * Activate a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function PGMActivate($programID = 0)
    {
        $program = $this->program->getPGMByID($programID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->activate($programID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent.parent'));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $program->begin);
        $newEnd   = date('Y-m-d', strtotime($program->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->lang->program->PGMActivate;
        $this->view->position[] = $this->lang->program->PGMActivate;

        $this->view->program    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('program', $programID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * Delete a program.
     *
     * @param  int     $projectID
     * @param  varchar $confirm
     * @access public
     * @return void
     */
    public function PGMDelete($programID, $confirm = 'no')
    {
        $childrenCount = $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->andWhere('deleted')->eq(0)->fetch('count');
        if($childrenCount) die(js::alert($this->lang->program->hasChildren));

        $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
        if($confirm == 'no') die(js::confirm($this->lang->program->confirmDelete, $this->createLink('program', 'PGMDelete', "programID=$programID&confirm=yes")));

        $this->dao->update(TABLE_PROGRAM)->set('deleted')->eq(1)->where('id')->eq($programID)->exec();
        $this->loadModel('action')->create('program', $programID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);

        die(js::reload('parent'));
    }

    /**
     * Program project list.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function PGMProject($programID = 0, $browseType = 'doing', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $this->loadModel('datatable');
        $this->app->session->set('PRJBrowse', $this->app->getURI(true));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=program&key=PRJProgramTitle');
        $order        = explode('_', $orderBy);
        $sortField    = zget($this->config->program->sortFields, $order[0], 'id') . '_' . $order[1];
        $projectStats = $this->program->getPRJStats($programID, $browseType, 0, $sortField, $pager, $programTitle);

        $this->view->title      = $this->lang->program->PGMProject;
        $this->view->position[] = $this->lang->program->PGMProject;

        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getPRJByID($programID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;

        $this->display();
    }

    /**
     * Program stakeholder list.
     *
     * @param  int    $programID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function PGMStakeholder($programID = 0, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->loadModel('user');
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $recPerPage, $pageID);

        $this->view->title      = $this->lang->program->PGMStakeholder;
        $this->view->position[] = $this->lang->program->PGMStakeholder;

        $this->view->stakeholders = $this->program->getStakeholders($programID, $orderBy, $pager);
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getPRJByID($programID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->orderBy      = $orderBy;

        $this->display();
    }

    /**
     * Create program stakeholder.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function createStakeholder($programID = 0, $dept = '', $parentIdList = '')
    {
        if($_POST)
        {
            $this->program->createStakeholder($programID);
            die(js::locate($this->createLink('program', 'PGMStakeholder', "programID=$programID"), 'parent'));
        }

        $this->loadModel('user');
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $this->loadModel('dept');
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->program->createStakeholder;
        $this->view->position[] = $this->lang->program->createStakeholder;

        $this->view->programID          = $programID;
        $this->view->program            = $this->program->getPGMByID($programID);
        $this->view->users              = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->deptUsers          = $deptUsers;
        $this->view->dept               = $dept;
        $this->view->depts              = array('' => '') + $this->dept->getOptionMenu();
        $this->view->stakeholders       = $this->program->getStakeholders($programID, 't1.id_desc');
        $this->view->parentStakeholders = $this->program->getStakeholdersByPGMList($parentIdList);

        $this->display();
    }

    /**
     * Unlink program stakeholder.
     *
     * @param  int    $programID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlinkStakeholder($stakeholderID, $programID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->program->confirmDelete, $this->inlink('unlinkStakeholder', "stakeholderID=$stakeholderID&programID=$programID&confirm=yes")));
        }
        else
        {
            $account = $this->dao->select('user')->from(TABLE_STAKEHOLDER)->where('id')->eq($stakeholderID)->fetch('user');
            $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('id')->eq($stakeholderID)->exec();

            $this->loadModel('user')->updateUserView($programID, 'program', array($account));

            /* Update children user view. */
            $childPGMList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
            $childPRJList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
            $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

            if(!empty($childPGMList))  $this->user->updateUserView($childPGMList, 'program',  array($account));
            if(!empty($childPRJList))  $this->user->updateUserView($childPRJList, 'project',  array($account));
            if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', array($account));

            die(js::reload('parent'));
         }
    }

    /**
     * Export program.
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
            $programLang   = $this->lang->program;
            $programConfig = $this->config->program;

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $programConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($programLang, $fieldName);
                unset($fields[$key]);
            }

            $programs = $this->program->getList($status, $orderBy, null);
            $users    = $this->loadModel('user')->getPairs('noletter');
            foreach($programs as $i => $program)
            {
                $program->PM       = zget($users, $program->PM);
                $program->status   = $this->processStatus('project', $program);
                $program->model    = zget($programLang->modelList, $program->model);
                $program->product  = zget($programLang->productList, $program->product);
                $program->budget   = $program->budget . zget($programLang->unitList, $program->budgetUnit);

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$program->id},") === false) unset($programs[$i]);
                }
            }

            if(isset($this->config->bizVersion)) list($fields, $projectStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $projectStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $programs);
            $this->post->set('kind', 'program');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }

    /**
     * Ajax get program drop menu.
     *
     * @param  int     $programID
     * @param  string  $module
     * @param  string  $method
     * @access public
     * @return void
     */
    public function ajaxGetPGMDropMenu($programID = 0, $module, $method)
    {
        $this->view->programID = $programID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->programs  = $this->program->getPGMList('all');
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
        $closedProjects = $this->program->getPRJList(0, 'closed', 0, 'id_desc', null, 0, 0);

        $closedProjectNames = array();
        foreach($closedProjects as $project) $closedProjectNames = common::convert2Pinyin($closedProjectNames);

        $closedProjectsHtml = '';
        foreach($closedProjects as $project) $closedProjectsHtml .= html::a($this->createLink('program', 'index', '', '', '', $project->id), '<i class="icon icon-menu-doc"></i>' . $project->name);

        $this->view->projectID = $projectID;
        $this->view->module    = $module;
        $this->view->method    = $method;

        $this->view->normalProjectsHtml = $this->program->getPRJTreeMenu(0, array('programmodel', 'createPRJManageLink'));
        $this->view->closedProjectsHtml = $closedProjectsHtml;

        $this->display();
    }

    /**
     * Gets the most recently created project.
     *
     * @access public
     * @return string
     */
    public function ajaxGetRecentProjects()
    {
        $recentProjects = $this->program->getPRJRecent();
        if(!empty($recentProjects))
        {
            foreach($recentProjects as $project)
            {
                echo html::a(helper::createLink('project', 'task', 'projectID=' . $project->id, '', false, $project->project), '<i class="icon icon-menu-doc"></i>' . $project->name, '', "class='text-ellipsis' title='$project->name'");
            }
        }
    }

    /**
     * Update program order.
     *
     * @access public
     * @return string
     */
    public function updateOrder()
    {
        $programs = $this->post->programs;
        foreach($programs as $id => $order)
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
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $this->lang->navGroup->program = 'project';
        if(!$projectID) $projectID = $this->session->PRJ;
        $this->session->set('PRJ', $projectID);

        $this->view->title      = $this->lang->program->common . $this->lang->colon . $this->lang->program->PRJIndex;
        $this->view->position[] = $this->lang->program->PRJIndex;
        $this->view->project    = $this->program->getPRJByID($projectID);

        $this->display();
    }

    /**
     * Projects list.
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
    public function PRJBrowse($programID = 0, $browseType = 'doing', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->program = 'project';
        $this->app->session->set('PRJBrowse', $this->app->getURI(true));
        $this->loadModel('datatable');

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=program&key=PRJProgramTitle');
        $order        = explode('_', $orderBy);
        $sortField    = zget($this->config->program->sortFields, $order[0], 'id') . '_' . $order[1];
        $projectStats = $this->program->getPRJStats($programID, $browseType, $queryID, $sortField, $pager, $programTitle);

        $this->view->title      = $this->lang->program->PRJBrowse;
        $this->view->position[] = $this->lang->program->PRJBrowse;

        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getPRJByID($programID);
        $this->view->PRJTree      = $this->program->getPRJTreeMenu(0, array('programmodel', 'createPRJManageLink'));
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
    public function PRJProgramTitle()
    {
        $this->loadModel('setting');
        if($_POST)
        {
            $PRJProgramTitle = $this->post->PRJProgramTitle;
            $this->setting->setItem($this->app->user->account . '.program.PRJProgramTitle', $PRJProgramTitle);
            die(js::reload('parent.parent'));
        }

        $status = $this->setting->getItem('owner=' . $this->app->user->account . '&module=program&key=PRJProgramTitle');
        $this->view->status = empty($status) ? '0' : $status;
        $this->display();
    }

    /**
     * Create a project.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  string $from PRJ|PGM
     * @param  int    $copyProjectID
     * @access public
     * @return void
     */
    public function PRJCreate($model = 'waterfall', $programID = 0, $from = 'PRJ', $copyProjectID = '')
    {
        if($from == 'PRJ')
        {
            $this->lang->navGroup->program = 'project';
        }
        else
        {
            $this->lang->navGroup->program     = 'program';
            $this->lang->program->switcherMenu = $this->program->getPGMCommonAction();
        }

        if($_POST)
        {
            $projectID = $this->program->PRJCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('project', $projectID, 'opened');

            if($from == 'PGM')
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PGMBrowse')));
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PRJBrowse', array('programID' => $programID, 'browseType' => 'all'))));
        }

        $name      = '';
        $code      = '';
        $team      = '';
        $whitelist = '';
        $acl       = 'open';
        $auth      = 'extend';

        $products     = array();
        $productPlans = array();

        if($copyProjectID)
        {
            $copyProgram = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProgram->name;
            $code        = $copyProgram->code;
            $team        = $copyProgram->team;
            $acl         = $copyProgram->acl;
            $auth        = $copyProgram->auth;
            $whitelist   = $copyProgram->whitelist;

            $products = $this->project->getProducts($copyProjectID);
            foreach($products as $product)
            {
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
            }
        }

        $allProducts = $this->program->getPGMProduct($programID);

        $this->view->title      = $this->lang->program->PRJCreate;
        $this->view->position[] = $this->lang->program->PRJCreate;

        $this->view->pmUsers       = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users         = $this->user->getPairs('noclosed|nodeleted');
        $this->view->programs      = array('' => '') + $this->program->getPRJPairsByModel($model, $programID);
        $this->view->products      = $products;
        $this->view->allProducts   = array('0' => '') + $allProducts;
        $this->view->productPlans  = array('0' => '') + $productPlans;
        $this->view->branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->programID     = $programID;
        $this->view->model         = $model;
        $this->view->name          = $name;
        $this->view->code          = $code;
        $this->view->team          = $team;
        $this->view->acl           = $acl;
        $this->view->auth          = $auth;
        $this->view->whitelist     = $whitelist;
        $this->view->copyProjectID = $copyProjectID;
        $this->view->from          = $from;
        $this->view->programList   = $this->program->getParentPairs();
        $this->view->parentProgram = $this->program->getPGMByID($programID);

        $this->display();
    }

    /**
     * Edit a project.
     *
     * @param  int    $projectID
     * @param  int    $parentID
     * @access public
     * @return void
     */
    public function PRJEdit($projectID = 0, $parentID = 0)
    {
        $this->lang->navGroup->program = 'project';
        $this->app->loadLang('project');
        $this->loadModel('productplan');

        if($_POST)
        {
            $changes = $this->program->PRJUpdate($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $url = $this->session->PRJBrowse ? $this->session->PRJBrowse : inLink('PRJBrowse');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $url));
        }

        $project = $this->program->getPRJByID($projectID);
        $parents = $this->program->getParentPairs();

        $linkedBranches = array();
        $productPlans   = array(0 => '');
        $allProducts    = $parentID ? $this->program->getPGMProduct($parentID) : $this->program->getPGMProduct($projectID);
        $linkedProducts = $parentID ? array() : $this->project->getProducts($projectID);

        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        foreach($linkedProducts as $product)
        {
            $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
        }

        $this->view->title      = $this->lang->program->PRJEdit;
        $this->view->position[] = $this->lang->program->PRJEdit;

        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project        = $project;
        $this->view->parents        = $parents;
        $this->view->parentID       = $parentID;
        $this->view->allProducts    = array('0' => '') + $allProducts;
        $this->view->productPlans   = $productPlans;
        $this->view->linkedProducts = $linkedProducts;
        $this->view->branchGroups   = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);

        $this->display();
    }

    /**
     * Project browse groups.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function PRJGroup($projectID = 0, $programID = 0)
    {
        $this->lang->navGroup->program = 'project';
        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups     = $this->group->getList($projectID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->groups     = $groups;
        $this->view->projectID  = $projectID;
        $this->view->programID  = $programID;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Project create a group.
     *
     * @param  int    $projectID
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PRJCreateGroup($projectID = 0, $programID = 0)
    {
        $this->lang->navGroup->program = 'project';

        if(!empty($_POST))
        {
            $_POST['PRJ'] = $projectID;
            $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inLink('PRJGroup', "projectID=$projectID&programID=$programID"), 'parent.parent'));
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
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PRJManageView($groupID, $projectID, $programID)
    {
        $this->lang->navGroup->program = 'project';

        if($_POST)
        {
            $this->group->updateView($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('PRJGroup', "projectID=$projectID&programID=$programID")));
        }

        $group = $this->group->getById($groupID);

        $this->view->title      = $group->name . $this->lang->colon . $this->lang->group->manageView;
        $this->view->position[] = $group->name;
        $this->view->position[] = $this->lang->group->manageView;

        $this->view->group    = $group;
        $this->view->products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('program')->eq($group->PRJ)->orderBy('order_desc')->fetchPairs('id', 'name');
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
    public function PRJManagePriv($type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
        $this->lang->navGroup->program = 'project';

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

            /* Unset not program privs. */
            $program = $this->project->getByID($group->PRJ);
            foreach($this->lang->resource as $method => $label)
            {
                if(!in_array($method, $this->config->programPriv->{$program->model})) unset($this->lang->resource->$method);
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
    public function PRJManageMembers($projectID, $dept = '')
    {
        $this->lang->navGroup->program = 'project';

        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            die(js::reload('parent.parent'));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $project        = $this->project->getById($projectID);
        $users          = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles          = $this->user->getUserRoles(array_keys($users));
        $deptUsers      = empty($dept) ? array() : $this->dept->getDeptUserPairs($dept);

        $title      = $this->lang->program->PRJManageMembers . $this->lang->colon . $project->name;
        $position[] = $this->lang->program->PRJManageMembers;

        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->project        = $project;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = array('' => '') + $this->loadModel('dept')->getOptionMenu();
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
    public function PRJManageGroupMember($groupID, $deptID = 0)
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
    public function PRJCopyGroup($groupID)
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
    public function PRJEditGroup($groupID)
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
    public function PRJStart($projectID)
    {
        $this->lang->navGroup->program = 'project';
        $project   = $this->program->getPGMByID($projectID);
        $projectID = $project->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->start($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $projectID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->start;
        $this->view->position[] = $this->lang->project->start;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->display();
    }

    /**
     * Suspend a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function PRJSuspend($projectID)
    {
        $this->lang->navGroup->program = 'project';
        $project = $this->program->getPGMByID($projectID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->suspend($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $projectID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->suspend;
        $this->view->position[] = $this->lang->project->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->view->project    = $project;

        $this->display('project', 'suspend');
    }

    /**
     * Close a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function PRJClose($projectID)
    {
        $this->lang->navGroup->program = 'project';
        $project = $this->program->getPGMByID($projectID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->close($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $projectID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->close;
        $this->view->position[] = $this->lang->project->close;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);

        $this->display('project', 'close');
    }

    /**
     * Activate a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function PRJActivate($projectID)
    {
        $this->lang->navGroup->program = 'project';
        $project = $this->program->getPRJByID($projectID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->activate($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $projectID, 'Activated', $this->post->comment);
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

        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
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
    public function PRJDelete($projectID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $project = $this->program->getPRJByID($projectID);
            echo js::confirm(sprintf($this->lang->program->PRJConfirmDelete, $project->name), $this->createLink('program', 'PRJDelete', "projectID=$projectID&confirm=yes"));
            die();
        }
        else
        {
            $this->project->delete(TABLE_PROJECT, $projectID);
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
    public function PRJUpdateOrder()
    {
        $idList  = explode(',', trim($this->post->projects, ','));
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        $projects = $this->dao->select('id,`order`')->from(TABLE_PROJECT)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($projects as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $projectID
     * @param  int    $programID
     * @param  string $module
     * @param  string $objectType
     * @param  string $orderby
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function PRJWhitelist($projectID = 0, $programID = 0, $module='program', $objectType = 'project', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->lang->navGroup->program = 'project';
        echo $this->fetch('personnel', 'whitelist', "objectID=$projectID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $projectID
     * @param  int     $deptID
     * @access public
     * @return void
     */
    public function PRJAddWhitelist($projectID = 0, $deptID = 0)
    {
        $this->lang->navGroup->program = 'project';
        echo $this->fetch('personnel', 'addWhitelist', "objectID=$projectID&dept=$deptID&objectType=project&module=program");
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
     * @param  int     $programID
     * @param  string  $from
     * @access public
     * @return void
     */
    public function PRJManageProducts($projectID, $programID, $from = '')
    {
        $this->lang->navGroup->program = 'project';
        $browseProjectLink = $this->createLink('program', 'PRJBrowse', "programID=$programID");

        if(!empty($_POST))
        {
            $oldProducts = $this->project->getProducts($projectID);
            $this->project->updateProducts($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->project->getProducts($projectID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create('project', $projectID, 'Managed', '', !empty($_POST['products']) ? join(',', $_POST['products']) : '');

            die(js::locate($browseProjectLink));
        }

        $this->loadModel('product');
        $project   = $this->project->getById($projectID);
        $programID = $this->program->getTopProgramID($projectID);

        /* Title and position. */
        $title      = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $position[] = html::a($browseProjectLink, $project->name);
        $position[] = $this->lang->project->manageProducts;

        $allProducts    = $this->product->getPairs('noclosed|nocode', $programID);
        $linkedProducts = $this->project->getProducts($project->id);
        $linkedBranches = array();

        /* Merge allProducts and linkedProducts for closed product. */
        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if(!empty($product->branch)) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Assign. */
        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->allProducts    = $allProducts;
        $this->view->linkedProducts = $linkedProducts;
        $this->view->branchGroups   = $this->loadModel('branch')->getByProducts(array_keys($allProducts), '', $linkedBranches);

        $this->display();
    }
}
