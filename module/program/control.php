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

        $this->view->title      = $this->lang->program->PGMIndex;
        $this->view->position[] = $this->lang->program->PGMIndex;
        $this->display();
    }

    /**
     * Project list.
     *
     * @param  varchar $status
     * @param  varchar $orderBy
     * @access public
     * @return void
     */
    public function PGMBrowse($status = 'all', $orderBy = 'order_asc')
    {
        $this->lang->navGroup->program       = 'program';
        $this->lang->program->mainMenuAction = html::a('javascript:history.go(-1);', '<i class="icon icon-back"></i> ' . $this->lang->goback, '', "class='btn btn-link'");

        if(common::hasPriv('program', 'pgmcreate')) $this->lang->pageActions = html::a($this->createLink('program', 'pgmcreate'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->PGMCreate, '', "class='btn btn-secondary'");

        $this->app->session->set('programList', $this->app->getURI(true));

        $programType = $this->cookie->programType ? $this->cookie->programType : 'bylist';

        if($programType === 'bygrid')
        {
            $programs = $this->program->getProgramStats($status, 20, $orderBy);
        }
        else
        {
            $programs = $this->program->getPGMList($status, $orderBy, null, true);
        }

        /* Get PM id list. */
        $accounts = array();
        foreach($programs as $program)
        {
            if(!empty($program->PM) and !in_array($program->PM, $accounts)) $accounts[] = $program->PM;
        }
        $PMList = $this->loadModel('user')->getListByAccounts($accounts, 'account');

        $this->view->title       = $this->lang->program->PGMBrowse;
        $this->view->position[]  = $this->lang->program->PGMBrowse;

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
    public function PGMProduct($programID = 0, $browseType = 'noclosed', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $program = $this->program->getPGMByID($programID);
        if(empty($program) || $program->type != 'program') die(js::error($this->lang->notFound) . js::locate('back'));

        $this->lang->navGroup->program       = 'program';
        $this->lang->program->switcherMenu   = $this->program->getPGMSwitcher($programID, true);
        $this->lang->program->mainMenuAction = $this->program->getPGMMainAction();
        $this->program->setPGMViewMenu($programID);

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

        $this->view->title       = $this->lang->program->PGMProduct;
        $this->view->position[]  = $this->lang->program->PGMProduct;
        $this->view->program     = $program;
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
        $this->lang->navGroup->program = 'program';
        $parentProgram = $this->program->getPGMByID($parentProgramID);

        if($_POST)
        {
            $projectID = $this->program->PGMCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('program', $projectID, 'opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('pgmbrowse')));
        }

        $this->view->title      = $this->lang->program->PGMCreate;
        $this->view->position[] = $this->lang->program->PGMCreate;

        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->poUsers        = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->parentProgram  = $parentProgram;
        $this->view->parents        = $this->program->getParentPairs();
        $this->view->PGMList        = $this->program->getPGMList();
        $this->view->budgetUnitList = $this->program->getBudgetUnitList();
        $this->view->remainBudget   = $this->program->getParentRemainBudget($parentProgram);

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
        $this->lang->navGroup->program = 'program';

        $program = $this->program->getPGMByID($programID);
        $parentProgram = $program->parent ? $this->program->getPGMByID($program->parent) : '';

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

        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->poUsers        = $this->user->getPairs('noclosed|nodeleted|pofirst');
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->program        = $program;
        $this->view->parents        = $parents;
        $this->view->PGMList        = $this->program->getPGMList();
        $this->view->budgetUnitList = $this->program->getBudgetUnitList();
        $this->view->parentProgram  = $parentProgram;
        $this->view->remainBudget   = $this->program->getParentRemainBudget($parentProgram) + (float)$program->budget;

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
        $this->lang->navGroup->program = 'program';
        $this->loadModel('action');
        $program = $this->program->getPGMByID($programID);

        if(!empty($_POST))
        {
            /* Only when all subprograms and subprojects are closed can the program be closed. */
            $hasUnfinished = $this->program->hasUnfinished($program);
            if($hasUnfinished) die(js::error($this->lang->program->PGMCloseErrorMessage));

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

        $this->view->title      = $this->lang->program->PGMClose;
        $this->view->position[] = $this->lang->program->PGMClose;
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
    public function PGMStart($programID)
    {
        $this->lang->navGroup->program = 'program';
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $changes = $this->project->start($programID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('program', $programID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($programID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->project->start;
        $this->view->position[] = $this->lang->project->start;
        $this->view->program    = $this->program->getPGMByID($programID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->display();
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
        $this->lang->navGroup->program = 'program';
        $this->loadModel('action');
        $program = $this->program->getPGMByID($programID);

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

        $this->view->title      = $this->lang->program->PGMActivate;
        $this->view->position[] = $this->lang->program->PGMActivate;
        $this->view->program    = $program;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * Suspend a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function PGMSuspend($programID)
    {
        $this->lang->navGroup->program = 'program';
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

        $this->view->title      = $this->lang->project->suspend;
        $this->view->position[] = $this->lang->project->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->action->getList('program', $programID);
        $this->view->project    = $this->program->getPGMByID($programID);

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
        $programID = $this->program->savePGMState($programID, $this->program->getPGMPairs());
        if(!$programID) $this->locate($this->createLink('program', 'PGMbrowse')); 
        setCookie("lastPGM", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->app->session->set('PGMProject', $this->app->getURI(true));
        $this->app->session->set('projectList', $this->app->getURI(true));

        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu   = $this->program->getPGMSwitcher($programID, true);
        $this->lang->program->mainMenuAction = $this->program->getPGMMainAction();
        $this->program->setPGMViewMenu($programID);

        $this->loadModel('datatable');

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
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu   = $this->program->getPGMSwitcher($programID, true);
        $this->lang->program->mainMenuAction = $this->program->getPGMMainAction();
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
        $this->lang->program->switcherMenu   = $this->program->getPGMSwitcher($programID, true);
        $this->lang->program->mainMenuAction = $this->program->getPGMMainAction();
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
        $closedProjects = $this->program->getPRJList(0, 'closed', 0, 'id_desc');

        $closedProjectNames = array();
        foreach($closedProjects as $project) $closedProjectNames = common::convert2Pinyin($closedProjectNames);

        $closedProjectsHtml = '';
        foreach($closedProjects as $project) $closedProjectsHtml .= html::a($this->createLink('program', 'index', '', '', '', $project->id), '<i class="icon icon-menu-doc"></i>' . $project->name);

        $this->view->projectID = $projectID;
        $this->view->module    = $module;
        $this->view->method    = $method;

        $this->view->normalProjectsHtml = $this->program->getPRJTreeMenu(0, array('programmodel', 'createPRJManageLink'), 0, 'dropmenu');
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
     * Ajax get parent remain budget.
     *
     * @param  int    $parentProgramID
     * @access public
     * @return void
     */
    public function ajaxGetParentRemainBudget($parentProgramID)
    {
        $parentProgram = $this->program->getPGMByID($parentProgramID);
        $remainBudget  = $this->program->getParentRemainBudget($parentProgram);
        echo number_format($remainBudget, 2);
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
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $project = $this->program->getPRJByID($projectID);
        if(empty($project) || $project->type != 'project') die(js::error($this->lang->notFound) . js::locate('back'));

        $this->lang->navGroup->program = 'project';
        $projectID = $this->program->savePRJState($projectID, $this->program->getPRJPairs());

        if(!$projectID) $this->locate($this->createLink('program', 'PRJbrowse')); 
        setCookie("lastPRJ", $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        $this->view->title      = $this->lang->program->common . $this->lang->colon . $this->lang->program->PRJIndex;
        $this->view->position[] = $this->lang->program->PRJIndex;
        $this->view->project    = $project;

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
        $this->lang->program->menu = $this->lang->PRJ->menu;
        if($this->session->moreProjectLink) $this->lang->program->mainMenuAction = html::a($this->session->moreProjectLink, '<i class="icon icon-back"></i> ' . $this->lang->goback, '', "class='btn btn-link'");
        $this->app->session->set('PRJBrowse', $this->app->getURI(true));
        $this->loadModel('datatable');
        $this->session->set('projectList', $this->app->getURI(true));

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
        $this->view->PRJTree      = $this->program->getPRJTreeMenu(0, array('programmodel', 'createPRJManageLink'), 0, 'list');
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
        if($from == 'PRJ') $this->lang->program->menu = $this->lang->PRJ->menu;

        if($_POST)
        {
            $projectID = $this->program->PRJCreate();
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
                    $this->session->set('projectPlanList', $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=lists", '', '', $projectID));
                    $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('programplan', 'create', "projectID=$projectID", '', '', $projectID)));
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

        $products     = array();
        $productPlans = array();

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

        $parentProgram = $this->program->getPGMByID($programID);

        $this->view->title      = $this->lang->program->PRJCreate;
        $this->view->position[] = $this->lang->program->PRJCreate;

        $this->view->pmUsers        = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjects   = $this->program->getPRJPairsByModel();
        $this->view->products       = $products;
        $this->view->allProducts    = array('0' => '') + $this->program->getPGMProductPairs($programID);
        $this->view->productPlans   = array('0' => '') + $productPlans;
        $this->view->branchGroups   = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->programID      = $programID;
        $this->view->model          = $model;
        $this->view->name           = $name;
        $this->view->code           = $code;
        $this->view->team           = $team;
        $this->view->acl            = $acl;
        $this->view->auth           = $auth;
        $this->view->whitelist      = $whitelist;
        $this->view->copyProjectID  = $copyProjectID;
        $this->view->from           = $from;
        $this->view->programList    = $this->program->getParentPairs();
        $this->view->parentProgram  = $parentProgram;
        $this->view->URSRPairs      = $this->loadModel('custom')->getURSRPairs();
        $this->view->remainBudget   = $this->program->getParentRemainBudget($parentProgram);
        $this->view->budgetUnitList = $this->program->getBudgetUnitList();

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
    public function PRJEdit($projectID = 0, $from = 'PRJ')
    {
        $this->app->loadLang('custom');
        $this->app->loadLang('project');
        $this->loadModel('productplan');

        $project   = $this->program->getPRJByID($projectID);
        $programID = $project->parent;

        /* Navigation stay in program when enter from program list. */
        if($from == 'PRJ')
        {
            $this->lang->program->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('program', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
            $this->lang->navGroup->program = 'project';
        }
        if($from == 'pgmbrowse')
        {
            $this->lang->navGroup->program = 'program';
        }
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);
        }

        if($_POST)
        {
            $changes = $this->program->PRJUpdate($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $locateLink = $this->session->PRJBrowse ? $this->session->PRJBrowse : inLink('PRJView', "projectID=$projectID");
            if($from == 'pgmbrowse')  $locateLink = inLink('PGMBrowse');
            if($from == 'pgmproject') $locateLink = $this->session->PGMProject ? $this->session->PGMProject : inLink('PGMProject', "programID=$programID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }


        $linkedBranches = array();
        $productPlans   = array(0 => '');
        $allProducts    = $this->program->getPGMProductPairs($project->parent, 'assign', 'noclosed');
        $linkedProducts = $this->project->getProducts($projectID);
        $parentProgram  = $this->program->getPGMByID($programID);

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

        $this->view->title      = $this->lang->program->PRJEdit;
        $this->view->position[] = $this->lang->program->PRJEdit;

        $this->view->PMUsers           = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->users             = $this->user->getPairs('noclosed|nodeleted');
        $this->view->project           = $project;
        $this->view->programList       = $this->program->getParentPairs();
        $this->view->programID         = $programID;
        $this->view->allProducts       = array('0' => '') + $allProducts;
        $this->view->productPlans      = $productPlans;
        $this->view->linkedProducts    = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups      = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);
        $this->view->URSRPairs         = $this->loadModel('custom')->getURSRPairs();
        $this->view->from              = $from;
        $this->view->parentProgram     = $parentProgram;
        $this->view->remainBudget      = $this->program->getParentRemainBudget($parentProgram) + (float)$project->budget;
        $this->view->budgetUnitList    = $this->program->getBudgetUnitList();

        $this->display();
    }

    /**
     * Batch edit projects.
     *
     * @param  string $from
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PRJBatchEdit($from = 'prjbrowse', $programID = 0)
    {
        /* Navigation stay in program when enter from program list. */
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);
        }
        if($from == 'prjbrowse')
        {
            $this->lang->program->menu = $this->lang->PRJ->menu;
        }

        if($this->post->names)
        {
            $allChanges = $this->program->PRJBatchUpdate();

            if(!empty($allChanges))
            {
                foreach($allChanges as $projectID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('project', $projectID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            die(js::locate($this->session->projectList, 'parent'));
        }

        $projectIdList = $this->post->projectIdList ? $this->post->projectIdList : die(js::locate($this->session->projectList, 'parent'));
        $projects      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');

        foreach($projects as $project) $appendPMUsers[$project->PM] = $project->PM;

        $this->view->title      = $this->lang->program->batchEdit;
        $this->view->position[] = $this->lang->program->batchEdit;

        $this->view->projectIdList = $projectIdList;
        $this->view->projects      = $projects;
        $this->view->programList   = $this->program->getParentPairs();
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
    public function PRJView($projectID = 0)
    {
        $this->app->loadLang('bug');
        $this->lang->navGroup->program = 'project';
        $this->lang->program->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('program', $this->lang->noMenuModule);
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

        $this->view->title        = $this->lang->program->PRJView; 
        $this->view->position     = $this->lang->program->PRJView;
        $this->view->projectID    = $projectID;
        $this->view->project      = $this->program->getPRJByID($projectID);
        $this->view->products     = $products;
        $this->view->actions      = $this->loadModel('action')->getList('project', $projectID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
        $this->view->statData     = $this->program->getPRJStatData($projectID);
        $this->view->workhour     = $this->program->getPRJWorkhour($projectID);
        $this->view->planGroup    = $this->loadModel('project')->getPlans($products);;
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
    public function PRJGroup($projectID = 0, $programID = 0)
    {
        $this->lang->navGroup->program = 'project';
        $this->lang->program->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('program', $this->lang->noMenuModule);
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
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function PRJManageView($groupID, $projectID, $programID)
    {
        $this->lang->navGroup->program = 'project';
        $this->lang->program->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('program', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

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
        $this->lang->program->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('program', $this->lang->noMenuModule);
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
        $this->lang->program->menu = $this->lang->scrum->setMenu;
        $moduleIndex = array_search('program', $this->lang->noMenuModule);
        if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);

        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $link = $this->createLink('program', 'PRJManageMembers', "projectID=$projectID");
            $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $link));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $project   = $this->project->getById($projectID);
        $users     = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles     = $this->user->getUserRoles(array_keys($users));
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->program->PRJManageMembers . $this->lang->colon . $project->name;
        $this->view->position[] = $this->lang->program->PRJManageMembers;

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
        $this->loadModel('action');
        $project = $this->program->getPRJByID($projectID);

        if(!empty($_POST))
        {
            $changes = $this->project->start($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            /* Start all superior programs. */
            if($project->parent)
            {
                $path = explode(',', $project->path);
                $path = array_filter($path);
                foreach($path as $programID)
                {
                    if($programID == $projectID) continue;
                    $program = $this->program->getPGMByID($programID);
                    if($program->status == 'wait' || $program->status == 'suspended')
                    {
                        $changes = $this->project->start($programID);
                        if(dao::isError()) die(js::error(dao::getError()));

                        if($this->post->comment != '' or !empty($changes))
                        {
                            $actionID = $this->action->create('program', $programID, 'Started', $this->post->comment);
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
    public function PRJSuspend($projectID)
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
        $this->view->project    = $this->program->getPGMByID($projectID);

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
        $this->view->project    = $this->program->getPRJByID($projectID);
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
    public function PRJActivate($projectID)
    {
        $this->loadModel('action');
        $project = $this->program->getPRJByID($projectID);

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
     * @param  int    $programID
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
    public function PRJWhitelist($projectID = 0, $programID = 0, $module = 'program', $from = 'PRJ', $objectType = 'project', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($from == 'PRJ') 
        {
            $this->lang->navGroup->program = 'project';
            $this->lang->program->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('program', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->navGroup->program     = 'program';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);
        }

        echo $this->fetch('personnel', 'whitelist', "objectID=$projectID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&programID=$programID&from=$from");
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
    public function PRJAddWhitelist($projectID = 0, $deptID = 0, $programID = 0, $from = 'PRJ')
    {
        /* Navigation stay in program when enter from program list. */
        if($from == 'PRJ') 
        {
            $this->lang->navGroup->program = 'project';
            $this->lang->program->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('program', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);
        }

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$projectID&dept=$deptID&objectType=project&module=program&programID=$programID&from=$from");
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
     * @param  string $from  PRJ|pgmbrowse|pgmproject
     * @access public
     * @return void
     */
    public function PRJManageProducts($projectID, $programID = 0, $from = 'PRJ')
    {
        /* Navigation stay in program when enter from program list. */
        if($from == 'PRJ') 
        {
            $this->lang->navGroup->program = 'project';
            $this->lang->program->menu = $this->lang->scrum->setMenu;
            $moduleIndex = array_search('program', $this->lang->noMenuModule);
            if($moduleIndex !== false) unset($this->lang->noMenuModule[$moduleIndex]);
        }
        if($from == 'pgmbrowse')
        {
            $this->lang->navGroup->program = 'program';
        }
        if($from == 'pgmproject')
        {
            $this->app->rawMethod = 'pgmproject';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);
        }

        if(!empty($_POST))
        {
            if(!isset($_POST['products']))
            {
                dao::$errors['message'][] = $this->lang->program->errorNoProducts;
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
            if($from == 'pgmproject') $locateLink = $this->session->PGMProject ? $this->session->PGMProject : inLink('PGMProject', "programID=$programID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $this->loadModel('product');
        $project = $this->project->getById($projectID);

        $allProducts        = $this->program->getPGMProductPairs($project->parent, 'assign', 'noclosed');
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
        $this->view->title             = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $this->view->position[]        = $this->lang->project->manageProducts;
        $this->view->allProducts       = $allProducts;
        $this->view->linkedProducts    = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups      = $this->loadModel('branch')->getByProducts(array_keys($allProducts), '', $linkedBranches);

        $this->display();
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
        $program = $this->program->getPGMByID($programID);
        if(!$program) die(js::error($this->lang->notFound) . js::locate('back'));

        echo $this->fetch('program', 'PGMProduct', "programID=$programID");
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
        $project   = $this->program->getPRJByID($projectID);
        $oldTopPGM = $this->program->getTopPGMByID($project->parent);
        $newTopPGM = $this->program->getTopPGMByID($programID);

        if($oldTopPGM == $newTopPGM) die();

        $response  = array();
        $response['result']  = true;
        $response['message'] = $this->lang->program->changeProgramTip;

        $multiLinkedProducts = $this->program->getMultiLinkedProducts($projectID);
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
