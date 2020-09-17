<?php
class program extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
        $this->loadModel('group');
        $this->programs = $this->program->getPairs();
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
        $this->display();
    }

    /**
     * Program index view.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function index($programID = 0)
    {
        $this->lang->navGroup->program = 'program';
        if(!$programID) $programID = $this->session->PRJ;
        $this->session->set('program', $programID);

        $this->view->title      = $this->lang->program->common . $this->lang->colon . $this->lang->program->index;
        $this->view->position[] = $this->lang->program->index;
        $this->view->program    = $this->project->getByID($programID);

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
    public function PGMBrowse($status = 'all', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->lang->navGroup->program = 'program';
        if(common::hasPriv('program', 'pgmcreate')) $this->lang->pageActions = html::a($this->createLink('program', 'pgmcreate'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->PGMCreate, '', "class='btn btn-primary'");

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
            $programs = $this->program->getList($status, $orderBy, $pager, true);
        }

        $this->view->programs    = $programs;
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->title       = $this->lang->program->PGMBrowse;
        $this->view->position[]  = $this->lang->program->PGMBrowse;
        $this->view->programType = $programType;
        $this->display();
    }

    /**
     * Program create guide.
     *
     * @access public
     * @return void
     */
    public function createGuide()
    {
        $this->display();
    }

    /**
     * Create a program.
     *
     * @param  string $template
     * @param  int    $parentProgramID
     * @param  int    $copyProgramID
     * @access public
     * @return void
     */
    public function PGMCreate($parentProgramID = 0)
    {
        $this->lang->navGroup->program = 'program';

        if($_POST)
        {
            $projectID = $this->program->PGMCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => $this->processErrors(dao::getError())));

            $this->loadModel('action')->create('program', $projectID, 'opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('pgmbrowse', array('status' => 'wait', 'orderBy' => 'order_desc'))));
        }

        $this->view->title         = $this->lang->program->PGMCreate;
        $this->view->position[]    = $this->lang->program->PGMCreate;

        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->view->pmUsers       = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->parentProgram = $parentProgramID ? $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($parentProgramID)->fetch() : '';
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
        $this->lang->navGroup->program = 'program';

        $program = $this->project->getByID($programID);

        if($_POST)
        {
            $changes = $this->program->PGMUpdate($programID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => $this->processErrors(dao::getError())));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('program', $programID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $url = $this->session->PRJList ? $this->session->PRJList : inlink('pgmbrowse');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $url));
        }

        $parents = $this->program->getParentPairs();
        unset($parents[$programID]);

        $this->view->title       = $this->lang->program->PGMEdit;
        $this->view->position[]  = $this->lang->program->PGMEdit;

        $this->view->pmUsers     = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->program     = $program;
        $this->view->parents     = $parents;
        $this->view->groups      = $this->loadModel('group')->getPairs();
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
        $program = $this->project->getByID($programID);

        if($_POST)
        {
            $changes = $this->program->update($programID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => $this->processErrors(dao::getError())));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('program', $programID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $url = $this->session->PRJList ? $this->session->PRJList : inlink('browse');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $url));
        }

        $parents = $this->program->getParentPairs();
        unset($parents[$programID]);

        $this->view->pmUsers     = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $program->PM);
        $this->view->title       = $this->lang->program->PGMEdit;
        $this->view->position[]  = $this->lang->program->PGMEdit;
        $this->view->program     = $program;
        $this->view->parents     = $parents;
        $this->view->groups      = $this->loadModel('group')->getPairs();
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
        if($confirm == 'no') die(js::confirm(sprintf($this->lang->program->confirmDelete, $program->name), $this->createLink('program', 'delete', "programID=$programID&confirm=yes")));

        $this->dao->update(TABLE_PROGRAM)->set('deleted')->eq(1)->where('id')->eq($programID)->exec();
        $this->loadModel('action')->create('program', $programID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);

        die(js::reload('parent'));
    }

    /**
     * Browse groups.
     *
     * @param  int    $companyID
     * @access public
     * @return void
     */
    public function group($programID = 0)
    {
        $this->session->set('program', $programID);
        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->browse;
        $position[] = $this->lang->group->browse;

        $groups = $this->group->getList($programID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->groups     = $groups;
        $this->view->programID  = $programID;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Create a group.
     *
     * @access public
     * @return void
     */
    public function createGroup($programID = 0)
    {
        if(!empty($_POST))
        {
            $_POST['program'] = $programID;
            $this->group->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;
        $this->display('group', 'create');
    }

    /**
     * Edit a group.
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

        $title      = $this->lang->company->orgView . $this->lang->colon . $this->lang->group->edit;
        $position[] = $this->lang->group->edit;
        $this->view->title    = $title;
        $this->view->position = $position;
        $this->view->group    = $this->group->getById($groupID);

        $this->display('group', 'edit');
    }

    /**
     * Copy a group.
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
            $_POST['program'] = $group->program;
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
     * manageView
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function manageView($groupID)
    {
        if($_POST)
        {
            $this->group->updateView($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $group = $this->group->getById($groupID);
        if($group->acl) $group->acl = json_decode($group->acl, true);

        $this->view->title      = $group->name . $this->lang->colon . $this->lang->group->manageView;
        $this->view->position[] = $group->name;
        $this->view->position[] = $this->lang->group->manageView;

        $this->view->group      = $group;
        $this->view->products   = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('program')->eq($group->program)->orderBy('order_desc')->fetchPairs('id', 'name');
        $this->view->projects   = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('program')->eq($group->program)->orderBy('order_desc')->fetchPairs('id', 'name');

        $this->display();
    }

    /**
     * Manage privleges of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0, $menu = '', $version = '')
    {
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

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('group', "programID=$group->program")));
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
            $program = $this->project->getByID($group->program);
            /* Unset not program privs. */
            foreach($this->lang->resource as $method => $label)
            {
                if(!in_array($method, $this->config->programPriv->{$program->template})) unset($this->lang->resource->$method);
            }
        }

        $this->display('group', 'managePriv');
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
     * Manage program members.
     *
     * @param  int    $projectID
     * @param  int    $dept
     * @access public
     * @return void
     */
    public function manageMembers($projectID, $dept = '')
    {
        $this->session->set('program', $projectID);
        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            die(js::locate($this->createLink('program', 'browse'), 'parent'));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $project        = $this->project->getById($projectID);
        $users          = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles          = $this->user->getUserRoles(array_keys($users));
        $deptUsers      = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);
        $currentMembers = $this->project->getTeamMembers($projectID);

        $title      = $this->lang->program->manageMembers . $this->lang->colon . $project->name;
        $position[] = $this->lang->program->manageMembers;

        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->project        = $project;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = array('' => '') + $this->loadModel('dept')->getOptionMenu();
        $this->view->currentMembers = $currentMembers;
        $this->display();
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
        $project   = $this->project->getByID($projectID);
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
     * Delete a program.
     *
     * @param  int     $projectID
     * @param  varchar $confirm
     * @access public
     * @return void
     */
    public function delete($programID, $confirm = 'no')
    {
        $childrenCount = $this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('parent')->eq($programID)->andWhere('template')->ne('')->andWhere('deleted')->eq(0)->fetch('count');
        if($childrenCount) die(js::alert($this->lang->program->hasChildren));

        $program = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($programID)->fetch();
        if($confirm == 'no') die(js::confirm(sprintf($this->lang->program->confirmDelete, $program->name), $this->createLink('program', 'delete', "programID=$programID&confirm=yes")));

        $this->dao->update(TABLE_PROJECT)->set('deleted')->eq(1)->where('id')->eq($programID)->exec();
        $this->loadModel('action')->create('program', $programID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);

        die(js::reload('parent'));
    }

    /**
     * Suspend a program.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function suspend($projectID)
    {
        $project = $this->project->getByID($projectID);

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
     * Activate a program.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function activate($projectID)
    {
        $project = $this->project->getByID($projectID);

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
     * Close a program.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function close($projectID)
    {
        $project = $this->project->getByID($projectID);

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
                $program->template = zget($programLang->templateList, $program->template);
                $program->category = zget($programLang->categoryList, $program->category);
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
     * Process program errors.
     *
     * @param  array $errors
     * @access public
     * @return void
     */
    public function processErrors($errors)
    {
        foreach($errors as $field => $error)
        {
            $errors[$field] = str_replace($this->lang->program->stage, $this->lang->program->common, $error);
        }

        return $errors;
    }

    /**
     * Ajax get program drop menu.
     *
     * @param  int     $programID
     * @param  varchar $module
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($programID, $module, $method, $extra)
    {
        $this->loadModel('project');
        $this->view->programID = $programID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;

        $programs = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in(array_keys($this->programs))->orderBy('order desc')->fetchAll();
        $programPairs = array();
        foreach($programs as $program) $programPairs[$program->id] = $program->name;
        $this->view->programs = $programs;
        $this->display();
    }

    /**
     * Ajax get program enter link.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetEnterLink($programID = 0)
    {
        $this->lang->navGroup->program = 'project';
        $program         = $this->project->getByID($programID);
        $programProjects = $this->project->getPairs('', $this->session->PRJ);
        $programProject  = key($programProjects);

        if($program->template == 'waterfall')
        {
            $link = $this->createLink('programplan', 'browse', 'programID=' . $programID);
        }
        if($program->template == 'scrum')
        {
            $link = $programProject ? $this->createLink('project', 'task', 'projectID=' . $programProject) : $this->createLink('project', 'create', '', '', '', $programID);
        }

        die($link);
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
    public function PRJBrowse($programID = 0, $browseType = 'all', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->navGroup->program = 'project';
        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->projectStats = array();
        $this->view->pager        = $pager;
        $this->view->programID    = $programID;
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;
        $this->view->stack        = '';
        $this->display();
    }
}
