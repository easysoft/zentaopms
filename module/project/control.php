<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class project extends control
{
    private $projects;

    /**
     * Construct function, Set projects.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if($this->methodName != 'computeburn')
        {
            $this->projects = $this->project->getPairs();
            if(!$this->projects and $this->methodName != 'create') $this->locate($this->createLink('project', 'create'));
        }
    }

    /**
     * The index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        if(empty($this->projects)) $this->locate($this->createLink('project', 'create'));
        $this->locate($this->createLink('project', 'browse'));
    }

    /**
     * Browse a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function browse($projectID = 0)
    {
        $this->locate($this->createLink($this->moduleName, 'task', "projectID=$projectID"));
    }

    /**
     * Common actions.
     * 
     * @param  int    $projectID 
     * @access private
     * @return object current object
     */
    private function commonAction($projectID = 0)
    {
        $this->loadModel('product');

        /* Get projects and products info. */
        $projectID     = $this->project->saveState($projectID, array_keys($this->projects));
        $project       = $this->project->getById($projectID);
        $products      = $this->project->getProducts($project->id);
        $childProjects = $this->project->getChildProjects($project->id);
        $teamMembers   = $this->project->getTeamMembers($project->id);

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        /* Assign. */
        $this->view->projects      = $this->projects;
        $this->view->project       = $project;
        $this->view->childProjects = $childProjects;
        $this->view->products      = $products;
        $this->view->teamMembers   = $teamMembers;

        /* Check the privilege. */
        if(!$this->project->checkPriv($project))
        {
            echo(js::alert($this->lang->project->accessDenied));
            die(js::locate('back'));
        }

        return $project;
    }

    /**
     * Tasks of a project.
     * 
     * @param  int    $projectID 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function task($projectID = 0, $status = 'all', $orderBy = 'status_asc,id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);

        /* Header and position. */
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[]    = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->task;

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $tasks = $this->loadModel('task')->getProjectTasks($projectID, $status, $orderBy, $pager);

        /* Assign. */
        $this->view->tasks      = $tasks ? $tasks : array();
        $this->view->tabID      = 'task';
        $this->view->pager      = $pager->get();
        $this->view->recTotal   = $pager->recTotal;
        $this->view->recPerPage = $pager->recPerPage;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = strtolower($status);

        $this->display();
    }

    /**
     * Browse tasks in group.
     * 
     * @param  int    $projectID 
     * @param  string $groupBy    the field to group by
     * @access public
     * @return void
     */
    public function grouptask($projectID = 0, $groupBy = 'story')
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* Header and session. */
        $this->view->header['title'] = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]      = $this->lang->project->task;

        /* Get tasks and group them. */
        $tasks       = $this->loadModel('task')->getProjectTasks($projectID, $status = 'all', $groupBy);
        $groupBy     = strtolower(str_replace('`', '', $groupBy));
        $taskLang    = $this->lang->task;
        $groupByList = array();
        $groupTasks  = array();
        foreach($tasks as $task)
        {
            if($groupBy == 'story')
            { 
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'status')
            {
                $groupTasks[$taskLang->statusList[$task->status]][] = $task;
            }
            elseif($groupBy == 'assignedto')
            {
                $groupTasks[$task->assignedToRealName][] = $task;
            }
            elseif($groupBy == 'type')
            {
                $groupTasks[$taskLang->typeList[$task->type]][] = $task;
            }
            else
            {
                $groupTasks[$task->$groupBy][] = $task;
            }
        }

        /* Assign. */
        $this->view->tasks       = $groupTasks;
        $this->view->tabID       = 'task';
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->display();
    }

    /**
     * Import tasks undoned from other projects.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function importTask($projectID)
    {
        if(!empty($_POST))
        {
            $this->project->importTask($projectID);
            die(js::locate(inlink('task', "projectID=$projectID"), 'parent'));
        }

        $project = $this->commonAction($projectID);

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        $this->view->header->title  = $project->name . $this->lang->colon . $this->lang->project->importTask;
        $this->view->position[]     = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[]     = $this->lang->project->importTask;
        $this->view->tasks2Imported = $this->project->getTasks2Imported($projectID);
        $this->view->projects       = $this->project->getPairs('all');
        $this->display();
    }

    /**
     * Browse stories of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function story($projectID = 0)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('task');

        /* Save session. */
        $this->app->session->set('storyList', $this->app->getURI(true));

        $project = $this->commonAction($projectID);

        /* Header and position. */
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->story;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->story;

        /* The pager. */
        $stories    = $this->story->getProjectStories($projectID);
        $storyTasks = $this->task->getStoryTaskCounts(array_keys($stories), $projectID);
        $users      = $this->user->getPairs('noletter');

        /* Assign. */
        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->stories    = $stories;
        $this->view->storyTasks = $storyTasks;
        $this->view->tabID      = 'story';
        $this->view->users      = $users;

        $this->display();
    }

    /**
     * Browse bugs of a project. 
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bug($projectID = 0, $orderBy = 'status,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true));

        $project   = $this->commonAction($projectID);
        $products  = $this->project->getProducts($project->id);
        $productID = key($products);    // Get the first product for creating bug.

        /* Header and position. */
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->bug;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->bug;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $bugs  = $this->bug->getProjectBugs($projectID, $orderBy, $pager);
        $users = $this->user->getPairs('noletter');

        /* Assign. */
        $this->view->header    = $header;
        $this->view->position  = $position;
        $this->view->bugs      = $bugs;
        $this->view->tabID     = 'bug';
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $users;
        $this->view->productID = $productID;

        $this->display();
    }

    /**
     * Browse builds of a project. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function build($projectID = 0)
    {
        $this->session->set('buildList', $this->app->getURI(true));

        $project = $this->commonAction($projectID);

        /* Header and position. */
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->build;
        $this->view->position[]    = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->build;

        /* Get builds. */
        $this->view->builds = $this->loadModel('build')->getProjectBuilds((int)$projectID);

        $this->display();
    }

    /**
     * Browse burndown chart of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function burn($projectID = 0)
    {
        $this->loadModel('report');

        $project = $this->commonAction($projectID);

        /* Header and position. */
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->burn;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->burn;

        /* Create charts. */
        $dataXML = $this->report->createSingleXML($this->project->getBurnData($project->id), $this->lang->project->charts->burn->graph);
        $charts  = $this->report->createJSChart('line', $dataXML, 800);

        /* Assign. */
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'burn';
        $this->view->charts   = $charts;

        $this->display();
    }

    /**
     * Get data of burndown chart. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function burnData($projectID = 0)
    {
        $this->loadModel('report');
        $sets = $this->project->getBurnData($projectID);
        die($this->report->createSingleXML($sets, $this->lang->project->charts->burn->graph));
    }

    /**
     * Compute burndown datas.
     * 
     * @param  string $reload 
     * @access public
     * @return void
     */
    public function computeBurn($reload = 'no')
    {
        $this->view->burns = $this->project->computeBurn();
        if($reload == 'yes') die(js::reload('parent'));
        die($this->display());
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
        $project = $this->commonAction($projectID);

        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->team;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->team;

        $this->view->header   = $header;
        $this->view->position = $position;

        $this->display();
    }

    /**
     * Docs of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function doc($projectID)
    {
        $this->project->setMenu($this->projects, $projectID);
        $this->session->set('docList', $this->app->getURI(true));

        $project = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $this->view->header->title = $this->lang->project->doc;
        $this->view->position[]    = html::a($this->createLink($this->moduleName, 'browse'), $project->name);
        $this->view->position[]    = $this->lang->project->doc;
        $this->view->project       = $project;
        $this->view->docs          = $this->loadModel('doc')->getProjectDocs($projectID);
        $this->view->modules       = $this->doc->getProjectModulePairs();
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Create a project.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $projectID = $this->project->create();
            $this->project->updateProducts($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('project', $projectID, 'opened');
            die(js::locate($this->createLink('project', 'tips', "projectID=$projectID"), 'parent'));
        }

        $this->project->setMenu($this->projects, '');

        $this->view->header->title = $this->lang->project->create;
        $this->view->position[]    = $this->view->header->title;
        $this->view->projects      = array('' => '') + $this->projects;
        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->view->allProducts   = $this->loadModel('product')->getPairs();
        $this->display();
    }

    /**
     * Edit a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function edit($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $changes = $this->project->update($projectID);
            $this->project->updateProducts($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('project', 'view', "projectID=$projectID"), 'parent'));
        }

        /* Set menu. */
        $this->project->setMenu($this->projects, $projectID);

        $projects = array('' => '') + $this->projects;
        $project  = $this->project->getById($projectID);
        $managers = $this->project->getDefaultManagers($projectID);
        if(empty($project->PO)) $project->PO = $managers->PO;
        if(empty($project->PM)) $project->PM = $this->app->user->account;
        if(empty($project->QM)) $project->QM = $managers->QM;
        if(empty($project->RM)) $project->RM = $managers->RM;

        /* Remove current project from the projects. */
        unset($projects[$projectID]);

        $header['title'] = $this->lang->project->edit . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->edit;

        $linkedProducts = $this->project->getProducts($project->id);
        $linkedProducts = join(',', array_keys($linkedProducts));
        
        $this->view->header         = $header;
        $this->view->position       = $position;
        $this->view->projects       = $projects;
        $this->view->project        = $project;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed,nodeleted');
        $this->view->groups         = $this->loadModel('group')->getPairs();
        $this->view->allProducts    = $this->loadModel('product')->getPairs();
        $this->view->linkedProducts = $linkedProducts;

        $this->display();
    }

    /**
     * View a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function view($projectID)
    {
        $project = $this->project->getById($projectID);
        if(!$project) die(js::error($this->lang->notFound) . js::locate('back'));

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        $this->view->header->title = $this->lang->project->view;
        $this->view->position[]    = $this->view->header->title;

        $this->view->project  = $project;
        $this->view->products = $this->project->getProducts($project->id);
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->actions  = $this->loadModel('action')->getList('project', $projectID);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Delete a project.
     * 
     * @param  int    $projectID 
     * @param  string $confirm   yes|no
     * @access public
     * @return void
     */
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
            $this->session->set('project', '');
            die(js::locate(inlink('index'), 'parent'));
        }
    }

    /**
     * Manage products.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function manageProducts($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->updateProducts($projectID);
            if(dao::isError()) dis(js::error(dao::getError()));
            die(js::locate($browseProjectLink));
        }

        $this->loadModel('product');
        $project  = $this->project->getById($projectID);

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        /* Title and position. */
        $header['title'] = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageProducts;

        $allProducts    = $this->product->getPairs();
        $linkedProducts = $this->project->getProducts($project->id);
        $linkedProducts = join(',', array_keys($linkedProducts));

        /* Assign. */
        $this->view->header         = $header;
        $this->view->position       = $position;
        $this->view->allProducts    = $allProducts;
        $this->view->linkedProducts = $linkedProducts;

        $this->display();
    }

    /**
     * Manage childs projects.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function manageChilds($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->updateChilds($projectID);
            die(js::locate($browseProjectLink));
        }
        $project  = $this->project->getById($projectID);
        $projects = $this->projects;
        unset($projects[$projectID]);
        unset($projects[$project->parent]);
        if(empty($projects)) $this->locate($browseProjectLink);

        /* Header and position. */
        $header['title'] = $this->lang->project->manageChilds . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageChilds;

        $childProjects = $this->project->getChildProjects($project->id);
        $childProjects = join(",", array_keys($childProjects));

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        /* Assign. */
        $this->view->header        = $header;
        $this->view->position      = $position;
        $this->view->projects      = $projects;
        $this->view->childProjects = $childProjects;

        $this->display();
    }
    
    /**
     * Manage members of the project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function manageMembers($projectID = 0)
    {
        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $this->locate($this->createLink('project', 'team', "projectID=$projectID"));
            exit;
        }
        $this->loadModel('user');

        $project = $this->project->getById($projectID);
        $users   = $this->user->getPairs('noclosed, nodeleted');
        $users   = array('' => '') + $users;
        $members = $this->project->getTeamMembers($projectID);

        /* The deleted members. */
        foreach($members as $member)
        {
            if(!@$users[$member->account]) $member->account .= $this->lang->user->deleted;
        }

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        $header['title'] = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->manageMembers;
        $this->view->header   = $header;
        $this->view->position = $position;

        $this->view->project  = $project;
        $this->view->users    = $users;
        $this->view->members  = $members;
        $this->display();
    }

    /**
     * Unlink a memeber.
     * 
     * @param  int    $projectID 
     * @param  string $account 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkMember($projectID, $account, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->project->confirmUnlinkMember, $this->inlink('unlinkMember', "projectID=$projectID&account=$account&confirm=yes")));
        }
        else
        {
            $this->project->unlinkMember($projectID, $account);
            die(js::locate($this->inlink('team', "projectID=$projectID"), 'parent'));
        }
    }

    /**
     * Link stories to a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function linkStory($projectID = 0)
    {
        /* Get projects and products. */
        $project    = $this->project->getById($projectID);
        $products   = $this->project->getProducts($projectID);
        $browseLink = $this->createLink('project', 'story', "projectID=$projectID");

        $this->session->set('storyList', $this->app->getURI(true)); // Save session.
        $this->project->setMenu($this->projects, $project->id);     // Set menu.

        if(empty($products))
        {
            echo js::alert($this->lang->project->errorNoLinkedProducts);
            die(js::locate($this->createLink('project', 'manageproducts', "projectID=$projectID")));
        }

        if(!empty($_POST))
        {
            $this->project->linkStory($projectID);
            die(js::locate($browseLink, 'parent'));
            exit;
        }

        $this->loadModel('story');

        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->linkStory;
        $position[]      = html::a($browseLink, $project->name);
        $position[]      = $this->lang->project->linkStory;

        $allStories = $this->story->getProductStories(array_keys($products), $moduleID = '0', $status = 'active');
        $prjStories = $this->story->getProjectStoryPairs($projectID);

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->project    = $project;
        $this->view->products   = $products;
        $this->view->allStories = $allStories;
        $this->view->prjStories = $prjStories;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Unlink a story.
     * 
     * @param  int    $projectID 
     * @param  int    $storyID 
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function unlinkStory($projectID, $storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->project->confirmUnlinkStory, $this->createLink('project', 'unlinkstory', "projectID=$projectID&storyID=$storyID&confirm=yes"));
            exit;
        }
        else
        {
            $this->project->unlinkStory($projectID, $storyID);
            echo js::locate($this->app->session->storyList, 'parent');
            exit;
        }
    }

    /**
     * AJAX: get products of a project in html select.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function ajaxGetProducts($projectID)
    {
        $products = $this->project->getProducts($projectID);
        die(html::select('product', $products, '', 'class="select-3"'));
    }
    
    /**
     * When create a project, help the user. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function tips($projectID)
    {
        $url = $this->createLink('project', 'task', "projectID=$projectID");       
        $header['title']       = $this->lang->project->tips;
        $this->view->header    = $header;
        $this->view->projectID = $projectID;        
        $this->display();
        die("<html><head><meta http-equiv='refresh' content='3; url=$url' /></head><body></body></html>");
    }
}
