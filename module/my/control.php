<?php
/**
 * The control file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class my extends control
{
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
        $this->loadModel('dept');
        $this->my->setMenu();
    }

    /**
     * Index page, goto todo.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('my', 'todo'));
    }

    /**
     * My todos.
     * 
     * @param  string $type 
     * @param  string $account 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function todo($type = 'today', $account = '', $status = 'all')
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

        /* The header and position. */
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->todo;
        $this->view->position[]    = $this->lang->my->todo;

        /* Assign. */
        $this->view->dates = $this->loadModel('todo')->buildDateList();
        $this->view->todos = $this->todo->getList($type, $account, $status);
        $this->view->date  = (int)$type == 0 ? $this->todo->today() : $type;
        $this->view->type  = $type;
        $this->view->importFeature = ($type == 'before' or $type == TODOMODEL::DAY_IN_FEATURE);

        $this->display();
    }

    /**
     * My stories.
     * 
     * @access public
     * @return void
     */
    public function story($type = 'assignedto')
    {
        /* Save session. */
        $this->session->set('storyList', $this->app->getURI(true));

        /* Assign. */
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->story;
        $this->view->position[]    = $this->lang->my->story;
        $this->view->stories       = $this->loadModel('story')->getUserStories($this->app->user->account, $type);
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->type          = $type;

        $this->display();
    }

    /**
     * My tasks.
     * 
     * @param  string $type the browse type
     * @access public
     * @return void
     */
    public function task($type = 'assignedto')
    {
        /* Save session. */
        $this->session->set('taskList',  $this->app->getURI(true));
        $this->session->set('storyList', $this->app->getURI(true));

        /* Assign. */
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->task;
        $this->view->position[]    = $this->lang->my->task;
        $this->view->tabID         = 'task';
        $this->view->tasks         = $this->loadModel('task')->getUserTasks($this->app->user->account, $type);
        $this->view->type          = $type;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * My bugs.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bug($type = 'assigntome', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. load Lang. */
        $this->session->set('bugList', $this->app->getURI(true));
        $this->app->loadLang('bug');
 
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $bugs = array();
        if($type == 'assigntome')
        {
            $bugs = $this->dao->select('*')
                ->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')
                ->on('t1.product = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t1.assignedTo')->eq($this->app->user->account)
                ->orderBy('t1.id_desc')->page($pager)->fetchAll();
        }
        elseif($type == 'openedbyme')
        {
            $bugs = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'resolvedbyme')
        {
            $bugs = $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'closedbyme')
        {
            $bugs = $this->dao->findByClosedBy($this->app->user->account)->from(TABLE_BUG)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
     
        /* assign. */
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->bug;
        $this->view->position[]    = $this->lang->my->bug;
        $this->view->bugs          = $bugs;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->tabID         = 'bug';
        $this->view->type          = $type;
        $this->view->pager         = $pager;

        $this->display();
    }

    /**
     * My test task.
     * 
     * @access public
     * @return void
     */
    public function testtask()
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        $this->app->loadLang('testcase');

        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->testTask;
        $this->view->position[]    = $this->lang->my->testTask;
        $this->view->tasks         = $this->loadModel('testtask')->getByUser($this->app->user->account);
        
        $this->display();

    }

    /**
     * My test case.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function testcase($type = 'assigntome', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session, load lang. */
        $this->session->set('caseList', $this->app->getURI(true));
        $this->app->loadLang('testcase');
        
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        
        $cases = array();
        if($type == 'assigntome')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'donebyme')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->eq('done')
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }
        
        /* Assign. */
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->testCase;
        $this->view->position[]    = $this->lang->my->testCase;
        $this->view->cases         = $cases;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->tabID         = 'test';
        $this->view->type          = $type;
        $this->view->pager         = $pager;
        
        $this->display();
    }

    /**
     * My projects.
     * 
     * @access public
     * @return void
     */
    public function project()
    {
        $this->app->loadLang('project');

        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->project;
        $this->view->position[]    = $this->lang->my->project;
        $this->view->tabID         = 'project';
        $this->view->projects      = @array_reverse($this->user->getProjects($this->app->user->account));

        $this->display();
    }

    /**
     * Edit profile 
     * 
     * @access public
     * @return void
     */
    public function editProfile()
    {
        if($this->app->user->account == 'guest') die(js::alert('guest') . js::locate('back'));
        if(!empty($_POST))
        {
            $this->user->update($this->app->user->id);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'profile'), 'parent'));
        }

        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->editProfile;
        $this->view->position[]    = $this->lang->my->editProfile;
        $this->view->user          = $this->user->getById($this->app->user->id);

        $this->display();
    }

    /**
     * View my profile.
     * 
     * @access public
     * @return void
     */
    public function profile()
    {
        if($this->app->user->account == 'guest') die(js::alert('guest') . js::locate('back'));
        $user                 = $this->user->getById($this->app->user->account);
        $deptPath             = $this->dept->getParents($user->dept); 
        $this->view->deptPath = $deptPath;
        $this->view->header->title = $this->lang->my->common . $this->lang->colon . $this->lang->my->profile;
        $this->view->position[]    = $this->lang->my->profile;
        $this->view->user          = $this->user->getById($this->app->user->id);
        $this->display();
    }
}
