<?php
class program extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('project');
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
        $this->projects = $this->project->getPairs('nocode');
        $projectID  = $this->project->saveState($projectID, $this->projects);
        $selectHtml = $this->project->select('', $projectID, 0, 'project', 'task', $extra);
        $this->lang->programSwapper = $selectHtml;
    }

    public function index($status = 'all', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->commonAction();

        if(common::hasPriv('program', 'create')) $this->lang->pageActions = html::a($this->createLink('program', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->program->create, '', "class='btn btn-primary'");

        $programType = $this->cookie->programType;

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->projectList = $this->program->getList($status, $orderBy, $pager);
        $this->view->status      = $status;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->title       = $this->lang->program->index;
        $this->view->position[]  = $this->lang->program->index;
        $this->view->programType = $programType;
        $this->display();
    }

    public function create($type = 'scrum', $copyProgramID = '')
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
        $this->view->programs      = array('' => '') + $this->program->getPairsByType($type);
        $this->view->type          = $type;
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
        $projectID = $project->id;

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

        $this->view->title      = $this->lang->project->finish;
        $this->view->position[] = $this->lang->project->finish;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
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

    public function processErrors($errors)
    {
        foreach($errors as $field => $error)
        {
            $errors[$field] = str_replace($this->lang->program->stage, $this->lang->program->common, $error);
        }

        return $errors;
    }
}
