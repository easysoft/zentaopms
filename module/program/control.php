<?php
class program extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
        $this->programs = $this->program->getPairs();
    }

    public function transfer($programID = 0)
    {   
        $this->session->set('program', $programID);
        $program         = $this->project->getByID($programID); 
        $programProjects = $this->project->getPairsByProgram($programID);
        $programProject  = key($programProjects);
        $this->session->set('programTemplate', $program->template);

        if($program->template == 'cmmi')
        {   
            $link = $this->createLink('programplan', 'browse', 'programID=' . $programID);
        }   
        if($program->template == 'scrum')
        {   
            $link = $programProject ? $this->createLink('project', 'task', 'projectID=' . $programProject) : $this->createLink('project', 'create', '', '', '', $programID); 
        }   

        die(js::locate($link, 'parent'));
    }

    /**
     * Common actions.
     *
     * @param  int    $projectID
     * @access public
     * @return object current object
     */
    public function commonAction($projectID = 0, $extra = '')
    {
        /* Set menu. */
        //$this->projects = $this->project->getPairs('nocode');
        //$projectID  = $this->project->saveState($projectID, $this->projects);
        //$selectHtml = $this->project->select('', $projectID, 0, 'project', 'task', $extra);
        //$this->lang->programSwapper = $selectHtml;
    }

    public function index($status = 'doing', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->commonAction();

        if(common::hasPriv('program', 'create')) $this->lang->pageActions = html::a($this->createLink('program', 'createguide'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->create, '', "class='btn btn-primary' data-toggle='modal' data-type='ajax'");

        $programType = $this->cookie->programType ? $this->cookie->programType : 'bylist';

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        if($programType === 'bygrid')
        {
            $projectList = $this->project->getProjectStats($status == 'byproduct' ? 'all' : $status, 0, 0, 30, $orderBy, $pager, 'program');
            foreach($projectList as $projectID => $project)
            {
                $project->teamCount  = count($this->project->getTeamMembers($project->id));
            }
        }
        else
        {
            $projectList = $this->program->getList($status, $orderBy, $pager);
        }

        $this->view->projectList = $projectList;
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->title       = $this->lang->program->index;
        $this->view->position[]  = $this->lang->program->index;
        $this->view->programType = $programType;
        $this->display();
    }

    public function createGuide()
    {
        $this->display();
    }

    public function create($template = 'scrum', $copyProgramID = '')
    {
        $this->commonAction();

        if($_POST)
        {
            $projectID = $this->program->create();
            if(dao::isError())
            {
                $this->send(array('result' => 'fail', 'message' => $this->processErrors(dao::getError())));
            }
            $this->loadModel('action')->create('project', $projectID, 'opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('index')));
        }

        $name         = '';
        $code         = '';
        $team         = '';
        $whitelist    = '';
        $acl          = 'open';
        if($copyProgramID)
        {
            $copyProgram = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProgramID)->fetch();
            $name        = $copyProgram->name;
            $code        = $copyProgram->code;
            $team        = $copyProgram->team;
            $acl         = $copyProgram->acl;
            $whitelist   = $copyProgram->whitelist;
        }

        $this->view->title         = $this->lang->program->create;
        $this->view->position[]    = $this->lang->program->create;
        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->view->pmUsers       = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst');
        $this->view->programs      = array('' => '') + $this->program->getPairsByTemplate($template);
        $this->view->template      = $template;
        $this->view->name          = $name;
        $this->view->code          = $code;
        $this->view->team          = $team;
        $this->view->acl           = $acl;
        $this->view->whitelist     = $whitelist;
        $this->view->copyProgramID = $copyProgramID;
        $this->display();
    }

    public function edit($projectID = 0)
    {
        $this->commonAction();

        $project = $this->project->getByID($projectID);

        if($_POST)
        {
            $changes = $this->project->update($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => $this->processErrors(dao::getError())));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('index')));
        }

        $this->view->pmUsers     = $this->loadModel('user')->getPairs('noclosed|nodeleted|pmfirst',  $project->PM);
        $this->view->title       = $this->lang->project->edit;
        $this->view->position[]  = $this->lang->project->edit;
        $this->view->project     = $project;
        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->display();
    }

    public function manageMembers($projectID, $dept = '')
    {
        $this->session->set('program', $projectID);
        if(!empty($_POST))
        {    
            $this->project->manageMembers($projectID);
            die(js::locate($this->createLink('program', 'index'), 'parent'));
        }    

        /* Load model. */        
        $this->loadModel('user');
        $this->loadModel('dept');

        $project        = $this->project->getById($projectID);
        $users          = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback');
        $roles          = $this->user->getUserRoles(array_keys($users));
        $deptUsers      = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);
        $currentMembers = $this->project->getTeamMembers($projectID);
        //$members2Import = $this->project->getMembers2Import($team2Import, array_keys($currentMembers));
        //$teams2Import   = $this->project->getTeams2Import($this->app->user->account, $projectID);
        //$teams2Import   = array('' => '') + $teams2Import;

        /* Set menu. */
        //$this->project->setMenu($this->projects, $project->id);

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
        //$this->view->members2Import = $members2Import;
        //$this->view->teams2Import   = $teams2Import;
        //$this->view->team2Import    = $team2Import;
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
                $actionID = $this->action->create('project', $projectID, 'Started', $this->post->comment);
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
     * Finish project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function finish($projectID)
    {
        $project   = $this->project->getByID($projectID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->finish($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Finished', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->program->finish;
        $this->view->position[] = $this->lang->program->finish;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $project->id);
        $this->display();
    }

    public function delete($projectID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm(sprintf($this->lang->project->confirmDelete, $this->projects[$projectID]), $this->createLink('project', 'delete', "projectID=$projectID&confirm=yes"));
            exit;
        }
        else
        {
            $this->project->delete(TABLE_PROJECT, $projectID);
            die(js::locate(inlink('index'), 'parent'));
        }
    }

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
                $actionID = $this->action->create('project', $projectID, 'Suspended', $this->post->comment);
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
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->view->project    = $project;
        $this->display('project', 'activate');
    }

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
                $actionID = $this->action->create('project', $projectID, 'Closed', $this->post->comment);
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
                $program->type     = zget($programLang->typeList, $program->type);
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

    public function processErrors($errors)
    {
        foreach($errors as $field => $error)
        {
            $errors[$field] = str_replace($this->lang->program->stage, $this->lang->program->common, $error);
        }

        return $errors;
    }

    public function ajaxGetDropMenu($programID, $module, $method, $extra)
    {    
        $this->loadModel('project');
        $this->view->link      = $this->program->getProgramLink($module, $method, $extra);
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
}
