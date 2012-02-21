<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
     * @param  string $locate  yes|no locate to the browse page or not.
     * @param  string $status  the projects status, if locate is no, then get projects by the $status.
     * @access public
     * @return void
     */
    public function index($locate = 'yes', $status = 'undone')
    {
        if(empty($this->projects)) $this->locate($this->createLink('project', 'create'));
        if($locate == 'yes') $this->locate($this->createLink('project', 'browse'));

        $this->app->loadLang('my');
        $this->view->projectStats  = $this->project->getProjectStats($status);

        $this->display();
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
     * @access public
     * @return object current object
     */
    public function commonAction($projectID = 0)
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
    public function task($projectID = 0, $status = 'all', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {   
        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($status);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $project    = $this->commonAction($projectID);
        $projectID  = $project->id;
     
        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->projectTaskOrder ? $this->cookie->projectTaskOrder : 'status,id_desc';
        setcookie('projectTaskOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        /* Header and position. */
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[]    = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->task;

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $tasks = array();
        if($browseType != "bysearch")
        {
            $tasks = $this->loadModel('task')->getProjectTasks($projectID, $status, $orderBy, $pager); 
        }
        else
        {   
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('taskQuery', $query->sql);
                    $this->session->set('taskForm', $query->form);
                }
                else
                {
                    $this->session->set('taskQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->taskQuery == false) $this->session->set('taskQuery', ' 1 = 1');
            }
            $taskQuery = str_replace("`project` = 'all'", '1', $this->session->taskQuery); // Search all project.
            $this->session->set('taskReportCondition', $taskQuery);
            $tasks = $this->project->getSearchTasks($taskQuery, $pager, $orderBy);
        }

       /* Build the search form. */
        $this->config->project->search['actionURL'] = $this->createLink('project', 'task', "projectID=$projectID&status=bySearch&param=myQueryID");
        $this->config->project->search['queryID']   = $queryID;
        $this->config->project->search['params']['project']['values'] = array(''=>'', $projectID => $this->projects[$projectID], 'all' => $this->lang->project->allProject);
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->project->search);

        /* Assign. */
        $this->view->tasks           = $tasks;
        $this->view->tabID           = 'task';
        $this->view->pager           = $pager;
        $this->view->recTotal        = $pager->recTotal;
        $this->view->recPerPage      = $pager->recPerPage;
        $this->view->orderBy         = $orderBy;
        $this->view->browseType      = $browseType;
        $this->view->status          = $status;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->param           = $param;
        $this->view->projectID       = $projectID;

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
        $tasks       = $this->loadModel('task')->getProjectTasks($projectID, $status = 'all', $groupBy ? $groupBy : 'story');
        $groupBy     = strtolower(str_replace('`', '', $groupBy));
       
        $taskLang    = $this->lang->task;
        $groupByList = array();
        $groupTasks  = array();

        /* Get users. */
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($tasks as $task)
        {
            if($groupBy == '')
            {
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'story')
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
            elseif($groupBy == 'openedby')
            {
                $groupTasks[$users[$task->openedBy]][] = $task;
            }
            elseif($groupBy == 'finishedby')
            {
                $groupTasks[$users[$task->finishedBy]][] = $task;
            }
            elseif($groupBy == 'closedby')
            {
                $groupTasks[$users[$task->closedBy]][] = $task;
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
        $this->view->members     = $this->project->getTeamMembers($projectID);
        $this->view->tasks       = $groupTasks;
        $this->view->tabID       = 'task';
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->projectID   = $projectID;
        $this->view->users       = $users;
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
     * Import from Bug. 
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function importBug($projectID = 0, $browseType = 'all', $param = 0, $recTotal = 0, $recPerPage = 30, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $mails = $this->project->importBug($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            foreach($mails as $mail) $this->sendmail($mail->taskID, $mail->actionID);

            /* Locate the browser. */
            die(js::locate($this->createLink('project', 'importBug', "projectID=$projectID"), 'parent'));
        }

        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('bugList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);

        $this->loadModel('bug');
        $projects = $this->project->getPairs();
        $this->project->setMenu($projects, $projectID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $header['title'] = $projects[$projectID] . $this->lang->colon . $this->lang->project->importBug;
        $position[]      = html::a($this->createLink('project', 'task', "projectID=$projectID"), $projects[$projectID]);
        $position[]      = $this->lang->project->importBug;
        
        /* Get users, products and projects.*/
        $users    = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $products = $this->dao->select('t1.product, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq($projectID)
            ->fetchPairs('product');
        if(!empty($products))
        {
            unset($projects);
            $projects = $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')
                ->on('t1.project = t2.id')
                ->where('t1.product')->in(array_keys($products))
                ->fetchPairs('project');
        }
        else
        {
            $projectName = $projects[$projectID];
            unset($projects);
            $projects[$projectID] = $projectName;
        }

        /* Get bugs.*/
        $bugs = array();
        if($browseType != "bysearch")
        {
            $bugs = $this->bug->getActiveBugs($pager, $projectID, array_keys($products));
        }
        else
        {   
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('bugQuery', $query->sql);
                    $this->session->set('bugForm', $query->form);
                }
                else
                {
                    $this->session->set('bugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->bugQuery == false) $this->session->set('bugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN(array_keys($products)), $this->session->bugQuery); // Search all project.
            $bugs = $this->project->getSearchBugs($products, $projectID, $bugQuery, $pager, 'id_desc');
        }

       /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('project', 'importBug', "projectID=$projectID&browseType=bySearch&param=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        if(!empty($products))
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'') + $products + array('all'=>$this->lang->project->aboveAllProduct);
        }
        else
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'');
        }
        $this->config->bug->search['params']['project']['values'] = array(''=>'') + $projects + array('all'=>$this->lang->project->aboveAllProject);
        unset($this->config->bug->search['fields']['resolvedBy']); 
        unset($this->config->bug->search['fields']['closedBy']);    
        unset($this->config->bug->search['fields']['status']);      
        unset($this->config->bug->search['fields']['toTask']);      
        unset($this->config->bug->search['fields']['toStory']);     
        unset($this->config->bug->search['fields']['severity']);    
        unset($this->config->bug->search['fields']['resolution']);  
        unset($this->config->bug->search['fields']['resolvedBuild']);
        unset($this->config->bug->search['fields']['resolvedDate']);
        unset($this->config->bug->search['fields']['closedDate']);   
        unset($this->config->bug->search['params']['resolvedBy']); 
        unset($this->config->bug->search['params']['closedBy']);    
        unset($this->config->bug->search['params']['status']);      
        unset($this->config->bug->search['params']['toTask']);      
        unset($this->config->bug->search['params']['toStory']);     
        unset($this->config->bug->search['params']['severity']);    
        unset($this->config->bug->search['params']['resolution']);  
        unset($this->config->bug->search['params']['resolvedBuild']);
        unset($this->config->bug->search['params']['resolvedDate']);
        unset($this->config->bug->search['params']['closedDate']);   
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->bug->search);

        /* Assign. */
        $this->view->header     = $header;
        $this->view->pager      = $pager;
        $this->view->bugs       = $bugs;
        $this->view->recTotal   = $pager->recTotal;
        $this->view->recPerPage = $pager->recPerPage;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->users      = $users;
        $this->view->projectID  = $projectID;
        $this->display();
    }

    /**
     * Browse stories of a project.
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function story($projectID = 0, $orderBy = '')
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('task');

        /* Save session. */
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->projectStoryOrder ? $this->cookie->projectStoryOrder : 'pri';
        setcookie('projectStoryOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        $project = $this->commonAction($projectID);

        /* Header and position. */
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->story;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->story;

        /* The pager. */
        $stories    = $this->story->getProjectStories($projectID, $orderBy);
        $storyTasks = $this->task->getStoryTaskCounts(array_keys($stories), $projectID);
        $users      = $this->user->getPairs('noletter');

        /* Get project's product. */
        $productID = 0;
        $products = $this->loadModel('product')->getProductsByProject($projectID);
        if($products) $productID = key($products);

        /* Assign. */
        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->productID  = $productID;
        $this->view->stories    = $stories;
        $this->view->orderBy    = $orderBy;
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
    public function bug($projectID = 0, $orderBy = 'status,id_desc', $build = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
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
        $bugs  = $this->bug->getProjectBugs($projectID, $orderBy, $pager, $build);
        $users = $this->user->getPairs('noletter');

        /* Assign. */
        $this->view->header    = $header;
        $this->view->position  = $position;
        $this->view->bugs      = $bugs;
        $this->view->tabID     = 'bug';
        $this->view->build     = $this->loadModel('build')->getById($build);
        $this->view->buildID   = $this->view->build ? $this->view->build->id : 0;
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
        $this->loadModel('testtask');
        $this->session->set('buildList', $this->app->getURI(true));

        $project = $this->commonAction($projectID);

        /* Header and position. */
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->build;
        $this->view->position[]    = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->build;

        /* Get builds. */
        $this->view->builds = $this->loadModel('build')->getProjectBuilds((int)$projectID);
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Browse test tasks of project. 
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
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        $project = $this->commonAction($projectID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->header->title = $this->projects[$projectID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]    = html::a($this->createLink('project', 'testtask', "projectID=$projectID"), $this->projects[$projectID]);
        $this->view->position[]    = $this->lang->testtask->common;
        $this->view->projectID     = $projectID;
        $this->view->projectName   = $this->projects[$projectID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->tasks         = $this->testtask->getProjectTasks($projectID);
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');

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

        /* Create charts by flash. */
        //$dataXML = $this->report->createSingleXML($this->project->getBurnData($project->id), $this->lang->project->charts->burn->graph, $this->lang->report->singleColor);
        //$charts  = $this->report->createJSChart('line', $dataXML, 700, 350);

        /* Create charts by flot. */
        $sets   = $this->project->getBurnDataFlot($project->id);
        $count  = $sets['count'];
        unset($sets['count']);
        $dataJSON = $this->report->createSingleJSON($sets);
        $charts   = $this->report->createJSChartFlot($project->name, $dataJSON, $count, 700, 350);

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
    public function create($projectID = '', $copyProjectID = '')
    {
        if($projectID)
        {
            $this->view->tips      = $this->fetch('project', 'tips', "projectID=$projectID");
            $this->view->projectID = $projectID;
            $this->display();
            exit;
        }

        $teamname  = '';
        $products  = '';
        $whitelist = '';
        $acl       = '';

        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $teamname    = $copyProject->team;
            $acl         = $copyProject->acl;
            $whitelist   = $copyProject->whitelist;
            $products    = join(',', array_keys($this->project->getProducts($copyProjectID))); 
        }

        if(!empty($_POST))
        {
            $projectID = $copyProjectID == '' ? $this->project->create() : $this->project->create($copyProjectID);
            $this->project->updateProducts($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('project', $projectID, 'opened');
            die(js::locate($this->createLink('project', 'create', "projectID=$projectID"), 'parent'));
        }

        $this->project->setMenu($this->projects, key($this->projects));

        $this->view->header->title = $this->lang->project->create;
        $this->view->position[]    = $this->view->header->title;
        $this->view->projects      = array('' => '') + $this->projects;
        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->view->allProducts   = $this->loadModel('product')->getPairs();
        $this->view->teamname      = $teamname ;
        $this->view->products      = $products ;
        $this->view->whitelist     = $whitelist;
        $this->view->acl           = $acl      ;
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
     * Order project 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function order($projectID)
    {
        if($_POST)
        {
            $this->project->saveOrder();
            die(js::reload('parent'));
        }
        $project   = $this->commonAction($projectID);
        $this->project->setMenu($this->projects, $project->id);
        $this->view->projects  = $this->project->getList();
        $this->view->projectID = $project->id;
        $this->display();
    }

    /**
     * Send email.
     * 
     * @param  int    $taskID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($taskID, $actionID)
    {
        /* Set toList and ccList. */
        $task        = $this->loadModel('task')->getById($taskID);
        $projectName = $this->project->getById($task->project)->name;
        $toList      = $task->assignedTo;
        $ccList      = trim($task->mailto, ',');

        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $task->finishedBy;
        }

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Create the email content. */
        $this->view->task   = $task;
        $this->view->action = $action;
        $this->clear();
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $projectName . ':' . 'TASK#' . $task->id . $this->lang->colon . $task->name, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
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

        $allProducts     = $this->product->getPairs();
        $linkedProducts  = $this->project->getProducts($project->id);
        $linkedProducts  = join(',', array_keys($linkedProducts));

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
        foreach($members as $account => $member)
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
     * Project dynamic.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function dynamic($projectID = 0, $type = 'today', $param = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri   = $this->app->getURI(true);
        $this->session->set('productList',     $uri);
        $this->session->set('productPlanList', $uri);
        $this->session->set('releaseList',     $uri);
        $this->session->set('storyList',       $uri);
        $this->session->set('projectList',     $uri);
        $this->session->set('taskList',        $uri);
        $this->session->set('buildList',       $uri);
        $this->session->set('bugList',         $uri);
        $this->session->set('caseList',        $uri);
        $this->session->set('testtaskList',    $uri);

        /* Set the menu. If the projectID = 0, use the indexMenu instead. */
        $this->project->setMenu($this->projects, $projectID);
        if($projectID == 0)
        {
            $this->projects = array('0' => $this->lang->project->selectProject) + $this->projects;
            unset($this->lang->project->menu);
            $this->lang->project->menu = $this->lang->project->indexMenu;
            $this->lang->project->menu->list = $this->project->select($this->projects, 0, 'project', 'dynamic');
        }

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;

        /* Set the user and type. */
        $account = $type == 'account' ? $param : 'all';
        $period  = $type == 'account' ? 'all'  : $type;

        /* The header and position. */
        $project = $this->project->getByID($projectID);
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->dynamic;
        $this->view->position[]    = $this->lang->project->dynamic;

        /* Assign. */
        $this->view->projectID = $projectID;
        $this->view->type      = $type;
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted|noletter');
        $this->view->account   = $account;
        $this->view->actions   = $this->loadModel('action')->getDynamic($account, $period, $orderBy, $pager, 'all', $projectID);
        $this->display();
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
        $this->view->projectID = $projectID;        
        $this->display('project', 'tips');
    }
}
