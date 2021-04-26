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
        $this->loadModel('execution');
    }

    /**
     * Program list.
     *
     * @param  varchar $status
     * @param  varchar $orderBy
     * @access public
     * @return void
     */
    public function browse($status = 'all', $orderBy = 'order_asc')
    {
        if(common::hasPriv('program', 'create')) $this->lang->pageActions = html::a($this->createLink('program', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->create, '', "class='btn btn-secondary'");

        $this->session->set('programList', $this->app->getURI(true), 'program');
        $this->session->set('projectList', $this->app->getURI(true), 'project');

        $programType = $this->cookie->programType ? $this->cookie->programType : 'bylist';

        if($programType === 'bygrid')
        {
            $programs = $this->program->getProgramStats($status, 20, $orderBy);
        }
        else
        {
            $programs = $this->program->getList($status, $orderBy, null, true);
        }

        /* Get PM id list. */
        $accounts = array();
        foreach($programs as $program)
        {
            if(!empty($program->PM) and !in_array($program->PM, $accounts)) $accounts[] = $program->PM;
        }
        $PMList = $this->loadModel('user')->getListByAccounts($accounts, 'account');

        $this->view->title       = $this->lang->program->browse;
        $this->view->position[]  = $this->lang->program->browse;

        $this->view->programs    = $programs;
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->programType = $programType;
        $this->view->PMList      = $PMList;

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
    public function product($programID = 0, $browseType = 'noclosed', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $programID = $this->program->saveState($programID, $this->program->getPairs());
        setCookie("lastProgram", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->program->setMenu($programID);

        $program = $this->program->getByID($programID);
        if(empty($program) || $program->type != 'program') die(js::error($this->lang->notFound) . js::locate('back'));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get the top programID. */
        if($programID)
        {
            $path      = explode(',', $program->path);
            $path      = array_filter($path);
            $programID = current($path);
        }

        $this->view->title       = $this->lang->program->product;
        $this->view->position[]  = $this->lang->program->product;
        $this->view->program     = $program;
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->products    = $this->loadModel('product')->getStats($orderBy, $pager, $browseType, '', 'story', $programID);

        $this->display();
    }

    /**
     * Create a program.
     *
     * @param  int  $parentProgramID
     * @access public
     * @return void
     */
    public function create($parentProgramID = 0)
    {
        $parentProgram = $this->program->getByID($parentProgramID);

        if($_POST)
        {
            $projectID = $this->program->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('program', $projectID, 'opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->program->create;
        $this->view->position[] = $this->lang->program->create;

        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->poUsers        = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->parentProgram  = $parentProgram;
        $this->view->parents        = $this->program->getParentPairs();
        $this->view->programList    = $this->program->getList();
        $this->view->budgetUnitList = $this->project->getBudgetUnitList();
        $this->view->budgetLeft     = $this->program->getBudgetLeft($parentProgram);

        $this->display();
    }

    /**
     * Edit a program.
     *
     * @param  int $programID
     * @access public
     * @return void
     */
    public function edit($programID = 0)
    {
        if($_POST)
        {
            $changes = $this->program->update($programID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('program', $programID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse')));
        }

        $program       = $this->program->getByID($programID);
        $parentProgram = $program->parent ? $this->program->getByID($program->parent) : '';
        $parents       = $this->program->getParentPairs();
        unset($parents[$programID]);

        $this->view->title      = $this->lang->program->edit;
        $this->view->position[] = $this->lang->program->edit;

        $this->view->pmUsers         = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->poUsers         = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users           = $this->user->getPairs('noclosed|nodeleted');
        $this->view->program         = $program;
        $this->view->parents         = $parents;
        $this->view->programList     = $this->program->getList();
        $this->view->budgetUnitList  = $this->program->getBudgetUnitList();
        $this->view->parentProgram   = $parentProgram;
        $this->view->availableBudget = $this->program->getBudgetLeft($parentProgram) + (float)$program->budget;

        $this->display();
    }

    /**
     * Close a program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function close($programID)
    {
        $this->loadModel('action');
        $program = $this->project->getByID($programID, 'program');

        if(!empty($_POST))
        {
            /* Only when all subprograms and subprojects are closed can the program be closed. */
            $hasUnfinished = $this->program->hasUnfinished($program);
            if($hasUnfinished) die(js::error($this->lang->program->closeErrorMessage));

            $changes = $this->project->close($programID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($programID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->program->close;
        $this->view->position[] = $this->lang->program->close;
        $this->view->project    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);

        $this->display('project', 'close');
    }

    /**
     * Start program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function start($programID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->project->start($programID, 'program');
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($programID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->program->start;
        $this->view->position[] = $this->lang->program->start;
        $this->view->project    = $this->project->getByID($programID, 'program');
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->display('project', 'start');
    }

    /**
     * Activate a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function activate($programID = 0)
    {
        $this->loadModel('action');
        $program = $this->project->getByID($programID, 'program');

        if(!empty($_POST))
        {
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

        $this->view->title      = $this->lang->program->activate;
        $this->view->position[] = $this->lang->program->activate;
        $this->view->project    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display('project', 'activate');
    }

    /**
     * Suspend a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function suspend($programID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->project->suspend($programID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($programID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->program->suspend;
        $this->view->position[] = $this->lang->program->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->view->project    = $this->project->getByID($programID, 'program');

        $this->display('project', 'suspend');
    }

    /**
     * Delete a program.
     *
     * @param  int    $programID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($programID, $confirm = 'no')
    {
        $childrenCount = $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->andWhere('deleted')->eq(0)->fetch('count');
        if($childrenCount) die(js::alert($this->lang->program->hasChildren));

        $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
        if($confirm == 'no') die(js::confirm($this->lang->program->confirmDelete, $this->createLink('program', 'delete', "programID=$programID&confirm=yes")));

        $this->dao->update(TABLE_PROGRAM)->set('deleted')->eq(1)->where('id')->eq($programID)->exec();
        $this->loadModel('action')->create('program', $programID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);

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
    public function project($programID = 0, $browseType = 'all', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $programID = $this->program->saveState($programID, $this->program->getPairs());
        setCookie("lastProgram", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
        if(!$programID) $this->locate($this->createLink('program', 'browse'));

        $this->program->setMenu($programID);

        $this->app->session->set('programProject', $this->app->getURI(true), 'program');
        $this->app->session->set('projectList', $this->app->getURI(true), 'project');

        $this->loadModel('datatable');

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $programTitle = $this->loadModel('setting')->getItem('owner=' . $this->app->user->account . '&module=program&key=programTitle');
        $order        = explode('_', $orderBy);
        $sortField    = zget($this->config->program->sortFields, $order[0], 'id') . '_' . $order[1];
        $projectStats = $this->program->getProjectStats($programID, $browseType, 0, $sortField, $pager, $programTitle);

        $this->view->title      = $this->lang->program->project;
        $this->view->position[] = $this->lang->program->project;

        $this->view->projectStats = $projectStats;
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getByID($programID);
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
    public function stakeholder($programID = 0, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->program->setMenu($programID);

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $recPerPage, $pageID);

        $this->view->title      = $this->lang->program->stakeholder;
        $this->view->position[] = $this->lang->program->stakeholder;

        $this->view->stakeholders = $this->program->getStakeholders($programID, $orderBy, $pager);
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->program      = $this->program->getByID($programID);
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
            die(js::locate($this->createLink('program', 'stakeholder', "programID=$programID"), 'parent'));
        }

        $this->loadModel('user');
        $this->lang->program->switcherMenu = $this->program->getSwitcher($programID, true);

        $this->loadModel('dept');
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->program->createStakeholder;
        $this->view->position[] = $this->lang->program->createStakeholder;

        $this->view->programID          = $programID;
        $this->view->program            = $this->program->getByID($programID);
        $this->view->users              = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->deptUsers          = $deptUsers;
        $this->view->dept               = $dept;
        $this->view->depts              = array('' => '') + $this->dept->getOptionMenu();
        $this->view->stakeholders       = $this->program->getStakeholders($programID, 't1.id_desc');
        $this->view->parentStakeholders = $this->program->getStakeholdersByList($parentIdList);

        $this->display();
    }

    /**
     * Unlink program stakeholder.
     *
     * @param  int    $stakeholderID
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
            $this->updateChildUserView($programID, $account);

            die(js::reload('parent'));
         }
    }

    /**
     * Batch unlink program stakeholders.
     *
     * @param  int    $programID
     * @param  string $stakeholderIDList
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function batchUnlinkStakeholders($programID = 0, $stakeholderIDList = '', $confirm = 'no')
    {
        $stakeholderIDList = $stakeholderIDList ? $stakeholderIDList : implode(',', $this->post->stakeholderIDList);

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->program->confirmBatchUnlink, $this->inlink('batchUnlinkStakeholders', "programID=$programID&stakeholderIDList=$stakeholderIDList&confirm=yes")));
        }
        else
        {
            $account = $this->dao->select('user')->from(TABLE_STAKEHOLDER)->where('id')->in($stakeholderIDList)->fetchPairs('user');
            $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('id')->in($stakeholderIDList)->exec();

            $this->loadModel('user')->updateUserView($programID, 'program', $account);
            $this->updateChildUserView($programID, $account);

            die(js::reload('parent'));
        }
    }

    /**
     * Update children user view.
     *
     * @param  int    $programID
     * @param  array  $account
     * @access public
     * @return void
     */
    public function updateChildUserView($programID = 0, $account = array())
    {
        $childPGMList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
        $childPRJList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

        if(!empty($childPGMList))  $this->user->updateUserView($childPGMList, 'program',  array($account));
        if(!empty($childPRJList))  $this->user->updateUserView($childPRJList, 'project',  array($account));
        if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', array($account));
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
    public function ajaxGetDropMenu($programID = 0, $module, $method)
    {
        $this->view->programID = $programID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->programs  = $this->program->getList('all');
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
     * Ajax get budget left.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetBudgetLeft($programID)
    {
        $program    = $this->program->getByID($programID);
        $budgetLeft = $this->program->getBudgetLeft($program);
        echo number_format($budgetLeft, 2);
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
     * View a program.
     *
     * @param int $programID
     * @access public
     * @return void
     */
    public function view($programID)
    {
        $programID = (int)$programID;
        $program   = $this->program->getByID($programID);
        if(!$program) die(js::error($this->lang->notFound) . js::locate('back'));

        echo $this->fetch('program', 'product', "programID=$programID");
    }
}
