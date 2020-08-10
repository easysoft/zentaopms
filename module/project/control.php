<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class project extends control
{
    public $projects;

    /**
     * Construct function, Set projects.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        if($this->methodName != 'computeburn')
        {
            $this->projects = $this->project->getPairs('nocode');
            if(!$this->projects and $this->methodName != 'index' and $this->methodName != 'create' and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('project', 'create'));
            if(!$this->projects and $this->methodName != 'index' and $this->methodName != 'create' and $this->app->getViewType() == 'mhtml') $this->locate($this->createLink('project', 'index'));
        }
    }

    /**
     * The index page.
     *
     * @param  string $locate     yes|no locate to the browse page or not.
     * @param  string $status     the projects status, if locate is no, then get projects by the $status.
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($locate = 'auto', $projectID = 0)
    {
        if($locate == 'yes') $this->locate($this->createLink('project', 'task'));

        if($this->app->viewType != 'mhtml') unset($this->lang->project->menu->index);
        $this->commonAction($projectID);
        
        if(common::hasPriv('project', 'create')) $this->lang->modulePageActions = html::a($this->createLink('project', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->project->create, '', "class='btn btn-primary'");

        $this->view->title         = $this->lang->project->index;
        $this->view->position[]    = $this->lang->project->index;

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
    public function commonAction($projectID = 0, $extra = '')
    {
        $this->loadModel('product');

        /* Get projects and products info. */
        $projectID     = $this->project->saveState($projectID, $this->projects);
        $project       = $this->project->getById($projectID);
        $products      = $this->project->getProducts($projectID);
        $childProjects = $this->project->getChildProjects($projectID);
        $teamMembers   = $this->project->getTeamMembers($projectID);
        $actions       = $this->loadModel('action')->getList('project', $projectID);

        /* Set menu. */
        $this->project->setMenu($this->projects, $projectID, $buildID = 0, $extra);

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
    public function task($projectID = 0, $status = 'unclosed', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->loadModel('tree');
        $this->loadModel('search');
        $this->loadModel('task');
        $this->loadModel('datatable');

        $this->project->getLimitedProject();

        /* Set browse type. */
        $browseType = strtolower($status);
        if($this->config->global->flow == 'onlyTask' and $browseType == 'byproduct') $param = 0;

        /* Get products by project. */
        $project   = $this->commonAction($projectID, $status);
        $projectID = $project->id;
        $products  = $this->config->global->flow == 'onlyTask' ? array() : $this->loadModel('product')->getProductsByProject($projectID);
        setcookie('preProjectID', $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        if($this->cookie->preProjectID != $projectID)
        {
            $_COOKIE['moduleBrowseParam'] = $_COOKIE['productBrowseParam'] = 0;
            setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', false, false);
            setcookie('productBrowseParam', 0, 0, $this->config->webRoot, '', false, true);
        }
        if($browseType == 'bymodule')
        {
            setcookie('moduleBrowseParam',  (int)$param, 0, $this->config->webRoot, '', false, false);
            setcookie('productBrowseParam', 0, 0, $this->config->webRoot, '', false, true);
        }
        elseif($browseType == 'byproduct')
        {
            setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', false, false);
            setcookie('productBrowseParam', (int)$param, 0, $this->config->webRoot, '', false, true);
        }
        else
        {
            $this->session->set('taskBrowseType', $browseType);
        }

        if($browseType == 'bymodule' and $this->session->taskBrowseType == 'bysearch') $this->session->set('taskBrowseType', 'unclosed');

        /* Set queryID, moduleID and productID. */
        $queryID   = ($browseType == 'bysearch')  ? (int)$param : 0;
        $moduleID  = ($browseType == 'bymodule')  ? (int)$param : (($browseType == 'bysearch' or $browseType == 'byproduct') ? 0 : $this->cookie->moduleBrowseParam);
        $productID = ($browseType == 'byproduct') ? (int)$param : (($browseType == 'bysearch' or $browseType == 'bymodule')  ? 0 : $this->cookie->productBrowseParam);

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->projectTaskOrder ? $this->cookie->projectTaskOrder : 'status,id_desc';
        setcookie('projectTaskOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Header and position. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->task;

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get tasks. */
        $tasks = $this->project->getTasks($productID, $projectID, $this->projects, $browseType, $queryID, $moduleID, $sort, $pager);

       /* Build the search form. */
        $actionURL = $this->createLink('project', 'task', "projectID=$projectID&status=bySearch&param=myQueryID");
        $this->config->project->search['onMenuBar'] = 'yes';
        $this->project->buildTaskSearchForm($projectID, $this->projects, $queryID, $actionURL);

        /* team member pairs. */
        $memberPairs = array();
        foreach($this->view->teamMembers as $key => $member) $memberPairs[$key] = $member->realname;

        $showAllModule = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
        $extra         = (isset($this->config->project->task->allModule) && $this->config->project->task->allModule == 1) ? 'allModule' : '';
        $showModule    = !empty($this->config->datatable->projectTask->showModule) ? $this->config->datatable->projectTask->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($projectID, 'task', $showModule) : array();

        /* Assign. */
        $this->view->tasks         = $tasks;
        $this->view->summary       = $this->project->summary($tasks);
        $this->view->tabID         = 'task';
        $this->view->pager         = $pager;
        $this->view->recTotal      = $pager->recTotal;
        $this->view->recPerPage    = $pager->recPerPage;
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->status        = $status;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->param         = $param;
        $this->view->projectID     = $projectID;
        $this->view->project       = $project;
        $this->view->productID     = $productID;
        $this->view->modules       = $this->tree->getTaskOptionMenu($projectID, 0, 0, $showAllModule ? 'allModule' : '');
        $this->view->moduleID      = $moduleID;
        $this->view->moduleTree    = $this->tree->getTaskTreeMenu($projectID, $productID, $startModuleID = 0, array('treeModel', 'createTaskLink'), $extra);
        $this->view->memberPairs   = $memberPairs;
        $this->view->branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->view->setModule     = true;

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
    public function grouptask($projectID = 0, $groupBy = 'story', $filter = '')
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* Header and session. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->task;

        /* Get tasks and group them. */
        if(empty($groupBy))$groupBy = 'story';
        if(($groupBy == 'story') and ($project->type == 'ops'))$groupBy = 'status';
        $sort        = $this->loadModel('common')->appendOrder($groupBy);
        $tasks       = $this->loadModel('task')->getProjectTasks($projectID, $productID = 0, $status = 'all', $modules = 0, $sort);
        $groupBy     = str_replace('`', '', $groupBy);
        $taskLang    = $this->lang->task;
        $groupByList = array();
        $groupTasks  = array();

        $groupTasks = array();
        $allCount   = 0;
        foreach($tasks as $task)
        {
            $groupTasks[] = $task;
            $allCount++;
            if(isset($task->children))
            {
                foreach($task->children as $child)
                {
                    $groupTasks[] = $child;
                    $allCount++;
                }
                $task->children = true;
                unset($task->children);
            }
        }

        /* Get users. */
        $users = $this->loadModel('user')->getPairs('noletter');
        $tasks = $groupTasks;
        $groupTasks = array();
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
            elseif($groupBy == 'assignedTo')
            {
                if(isset($task->team))
                {
                    foreach($task->team as $team)
                    {
                        $cloneTask = clone $task;
                        $cloneTask->assignedTo = $team->account;
                        $cloneTask->estimate   = $team->estimate;
                        $cloneTask->consumed   = $team->consumed;
                        $cloneTask->left       = $team->left;
                        if($team->left == 0) $cloneTask->status = 'done';

                        $realname = zget($users, $team->account);
                        $cloneTask->assignedToRealName = $realname;
                        $groupTasks[$realname][] = $cloneTask;
                    }
                }
                else
                {
                    $groupTasks[$task->assignedToRealName][] = $task;
                }
            }
            elseif($groupBy == 'finishedBy')
            {
                if(isset($task->team))
                {
                    $task->consumed = $task->estimate = $task->left = 0;
                    foreach($task->team as $team)
                    {
                        if($team->left != 0)
                        {
                            $task->estimate += $team->estimate;
                            $task->consumed += $team->consumed;
                            $task->left     += $team->left;
                            continue;
                        }

                        $cloneTask = clone $task;
                        $cloneTask->finishedBy = $team->account;
                        $cloneTask->estimate   = $team->estimate;
                        $cloneTask->consumed   = $team->consumed;
                        $cloneTask->left       = $team->left;
                        $cloneTask->status     = 'done';
                        $realname = zget($users, $team->account);
                        $groupTasks[$realname][] = $cloneTask;
                    }
                    if(!empty($task->left)) $groupTasks[$users[$task->finishedBy]][] = $task;
                }
                else
                {
                    $groupTasks[$users[$task->finishedBy]][] = $task;
                }
            }
            elseif($groupBy == 'closedBy')
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
        /* Process closed data when group by assignedTo. */
        if($groupBy == 'assignedTo' and isset($groupTasks['Closed']))
        {
            $closedTasks = $groupTasks['Closed'];
            unset($groupTasks['Closed']);
            $groupTasks['closed'] = $closedTasks;
        }

        /* Remove task by filter and group. */
        $filter = (empty($filter) and isset($this->lang->project->groupFilter[$groupBy])) ? key($this->lang->project->groupFilter[$groupBy]) : $filter;
        if($filter != 'all')
        {
            if($groupBy == 'story' and $filter == 'linked' and isset($groupTasks[0]))
            {
                $allCount -= count($groupTasks[0]);
                unset($groupTasks[0]);
            }
            elseif($groupBy == 'pri' and $filter == 'noset')
            {
                foreach($groupTasks as $pri => $tasks)
                {
                    if($pri)
                    {
                        $allCount -= count($tasks);
                        unset($groupTasks[$pri]);
                    }
                }
            }
            elseif($groupBy == 'assignedTo' and $filter == 'undone')
            {
                foreach($groupTasks as $assignedTo => $tasks)
                {
                    foreach($tasks as $i => $task)
                    {
                        if($task->status != 'wait' and $task->status != 'doing')
                        {
                            $allCount -= 1;
                            unset($groupTasks[$assignedTo][$i]);
                        }
                    }
                }
            }
            elseif(($groupBy == 'finishedBy' or $groupBy == 'closedBy') and isset($tasks['']))
            {
                $allCount -= count($tasks['']);
                unset($tasks['']);
            }
        }

        /* Assign. */
        $this->app->loadLang('tree');
        $this->view->members     = $this->project->getTeamMembers($projectID);
        $this->view->tasks       = $groupTasks;
        $this->view->tabID       = 'task';
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->projectID   = $projectID;
        $this->view->users       = $users;
        $this->view->moduleID    = 0;
        $this->view->moduleName  = $this->lang->tree->all;
        $this->view->filter      = $filter;
        $this->view->allCount    = $allCount;
        $this->display();
    }

    /**
     * Import tasks undoned from other projects.
     *
     * @param  int    $projectID
     * @param  int    $fromProject
     * @access public
     * @return void
     */
    public function importTask($toProject, $fromProject = 0)
    {
        if(!empty($_POST))
        {
            $this->project->importTask($toProject,$fromProject);
            die(js::locate(inlink('importTask', "toProject=$toProject&fromProject=$fromProject"), 'parent'));
        }

        $project   = $this->commonAction($toProject);
        $toProject = $project->id;
        $branches  = $this->project->getProjectBranches($toProject);
        $tasks     = $this->project->getTasks2Imported($toProject, $branches);
        $projects  = $this->project->getProjectsToImport(array_keys($tasks));
        unset($projects[$toProject]);
        unset($tasks[$toProject]);

        if($fromProject == 0)
        {
            $tasks2Imported = array();
            foreach($projects as $id  => $projectName)
            {
                $tasks2Imported = array_merge($tasks2Imported, $tasks[$id]);
            }
        }
        else
        {
            $tasks2Imported = zget($tasks, $fromProject, array());
        }

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        $this->view->title          = $project->name . $this->lang->colon . $this->lang->project->importTask;
        $this->view->position[]     = html::a(inlink('browse', "projectID=$toProject"), $project->name);
        $this->view->position[]     = $this->lang->project->importTask;
        $this->view->tasks2Imported = $tasks2Imported;
        $this->view->projects       = $projects;
        $this->view->projectID      = $project->id;
        $this->view->fromProject    = $fromProject;
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
        $this->app->loadConfig('task');

        if(!empty($_POST))
        {
            $mails = $this->project->importBug($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

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
        $projects = $this->project->getPairs('nocode');
        $this->project->setMenu($projects, $projectID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $title      = $projects[$projectID] . $this->lang->colon . $this->lang->project->importBug;
        $position[] = html::a($this->createLink('project', 'task', "projectID=$projectID"), $projects[$projectID]);
        $position[] = $this->lang->project->importBug;

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
            $bugs = $this->bug->getActiveAndPostponedBugs(array_keys($products), $projectID, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importBugQuery', $query->sql);
                    $this->session->set('importBugForm', $query->form);
                }
                else
                {
                    $this->session->set('importBugQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->importBugQuery == false) $this->session->set('importBugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN(array_keys($products)), $this->session->importBugQuery); // Search all project.
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
        $this->config->bug->search['params']['plan']['values']    = $this->loadModel('productplan')->getPairs(array_keys($products));
        $this->config->bug->search['module'] = 'importBug';
        $this->config->bug->search['params']['confirmed']['values'] = array('' => '') + $this->lang->bug->confirmedList;
        $this->config->bug->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($projectID, $viewType = 'bug', $startModuleID = 0);
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
        unset($this->config->bug->search['fields']['branch']);
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
        unset($this->config->bug->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        /* Assign. */
        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->pager          = $pager;
        $this->view->bugs           = $bugs;
        $this->view->recTotal       = $pager->recTotal;
        $this->view->recPerPage     = $pager->recPerPage;
        $this->view->browseType     = $browseType;
        $this->view->param          = $param;
        $this->view->users          = $users;
        $this->view->project        = $this->project->getByID($projectID);
        $this->view->projectID      = $projectID;
        $this->view->requiredFields = explode(',', $this->config->task->create->requiredFields);
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
    public function story($projectID = 0, $orderBy = 'order_desc', $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->app->loadLang('testcase');

        $this->project->getLimitedProject();

        $type = strtolower($type);
        setcookie('storyPreProjectID', $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
        if($this->cookie->storyPreProjectID != $projectID)
        {
            $_COOKIE['storyModuleParam'] = $_COOKIE['storyProductParam'] = $_COOKIE['storyBranchParam'] = 0;
            setcookie('storyModuleParam',  0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', 0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  0, 0, $this->config->webRoot, '', false, true);
        }
        if($type == 'bymodule')
        {
            $_COOKIE['storyModuleParam']  = (int)$param;
            $_COOKIE['storyProductParam'] = 0;
            $_COOKIE['storyBranchParam']  = 0;
            setcookie('storyModuleParam', (int)$param, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', 0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  0, 0, $this->config->webRoot, '', false, true);
        }
        elseif($type == 'byproduct')
        {
            $_COOKIE['storyModuleParam']  = 0;
            $_COOKIE['storyProductParam'] = (int)$param;
            $_COOKIE['storyBranchParam']  = 0;
            setcookie('storyModuleParam',  0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', (int)$param, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  0, 0, $this->config->webRoot, '', false, true);
        }
        elseif($type == 'bybranch')
        {
            $_COOKIE['storyModuleParam']  = 0;
            $_COOKIE['storyProductParam'] = 0;
            $_COOKIE['storyBranchParam']  = $param;
            setcookie('storyModuleParam',  0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', 0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  $param, 0, $this->config->webRoot, '', false, true);
        }
        else
        {
            $this->session->set('projectStoryBrowseType', $type);
        }

        /* Save session. */
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->projectStoryOrder ? $this->cookie->projectStoryOrder : 'pri';
        setcookie('projectStoryOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $stories = $this->story->getProjectStories($projectID, $sort, $type, $param, 'story', '', $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);
        $users   = $this->user->getPairs('noletter');

        /* Build the search form. */
        $modules  = array();
        $projectModules = $this->loadModel('tree')->getTaskTreeModules($projectID, true);
        $products = $this->project->getProducts($projectID);
        foreach($products as $product)
        {
            $productModules = $this->tree->getOptionMenu($product->id);
            foreach($productModules as $moduleID => $moduleName)
            {
                if($moduleID and !isset($projectModules[$moduleID])) continue;
                $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
            }
        }
        $actionURL    = $this->createLink('project', 'story', "projectID=$projectID&orderBy=$orderBy&type=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->project->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'projectStory');

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->story;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->story;

        /* Count T B C */
        $storyIdList = array_keys($stories);;
        $storyTasks  = $this->loadModel('task')->getStoryTaskCounts($storyIdList, $projectID);
        $storyBugs   = $this->loadModel('bug')->getStoryBugCounts($storyIdList, $projectID);
        $storyCases  = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        $plans = $this->project->getPlans($products);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $productID => $plan) $allPlans += $plan;
        }

        if($this->cookie->storyModuleParam)  $this->view->module  = $this->loadModel('tree')->getById($this->cookie->storyModuleParam);
        if($this->cookie->storyProductParam) $this->view->product = $this->loadModel('product')->getById($this->cookie->storyProductParam);
        if($this->cookie->storyBranchParam)
        {
            $productID = 0;
            $branchID  = $this->cookie->storyBranchParam;
            if(strpos($branchID, ',') !== false) list($productID, $branchID) = explode(',', $branchID);
            $this->view->branch  = $this->loadModel('branch')->getById($branchID, $productID);
        }

        /* Get project's product. */
        $productID    = 0;
        $productPairs = $this->loadModel('product')->getProductsByProject($projectID);
        if($productPairs) $productID = key($productPairs);

        /* Assign. */
        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->productID    = $productID;
        $this->view->project      = $project;
        $this->view->stories      = $stories;
        $this->view->allPlans     = $allPlans;
        $this->view->summary      = $this->product->summary($stories);
        $this->view->orderBy      = $orderBy;
        $this->view->type         = $this->session->projectStoryBrowseType;
        $this->view->param        = $param;
        $this->view->moduleTree   = $this->loadModel('tree')->getProjectStoryTreeMenu($projectID, $startModuleID = 0, array('treeModel', 'createProjectStoryLink'));
        $this->view->tabID        = 'story';
        $this->view->storyTasks   = $storyTasks;
        $this->view->storyBugs    = $storyBugs;
        $this->view->storyCases   = $storyCases;
        $this->view->users        = $users;
        $this->view->pager        = $pager;
        $this->view->branchGroups = $branchGroups;

        $this->display();
    }

    /**
     * Browse bugs of a project.
     *
     * @param  int    $projectID
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
    public function bug($projectID = 0, $orderBy = 'status,id_desc', $build = 0, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true));

        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;
        $products  = $this->project->getProducts($project->id);
        $productID = key($products);    // Get the first product for creating bug.
        $branchID  = isset($products[$productID]) ? $products[$productID]->branch : 0;

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->bug;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->bug;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = $this->loadModel('common')->appendOrder($orderBy);
        $bugs  = $this->bug->getProjectBugs($projectID, $build, $type, $param, $sort, '', $pager);
        $users = $this->user->getPairs('noletter');

        /* team member pairs. */
        $memberPairs = array();
        $memberPairs[] = "";
        foreach($this->view->teamMembers as $key => $member)
        {
            $memberPairs[$key] = $member->realname;
        }

        /* Build the search form. */
        $actionURL = $this->createLink('project', 'bug', "projectID=$projectID&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->project->buildBugSearchForm($products, $queryID, $actionURL);

        /* Assign. */
        $this->view->title       = $title;
        $this->view->position    = $position;
        $this->view->bugs        = $bugs;
        $this->view->tabID       = 'bug';
        $this->view->build       = $this->loadModel('build')->getById($build);
        $this->view->buildID     = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->users       = $users;
        $this->view->productID   = $productID;
        $this->view->branchID    = empty($this->view->build->branch) ? $branchID : $this->view->build->branch;
        $this->view->memberPairs = $memberPairs;
        $this->view->type        = $type;
        $this->view->param       = $param;

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

        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Header and position. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->build;
        $this->view->position[] = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->build;

        $builds = $this->loadModel('build')->getProjectBuilds((int)$projectID);
        $projectBuilds = array();
        if(!empty($builds))
        {
            foreach($builds as $build) $projectBuilds[$build->product][] = $build;
        }

        /* Get builds. */
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->buildsTotal   = count($builds);
        $this->view->projectBuilds = $projectBuilds;

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

        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $productTasks = array();

        $tasks = $this->testtask->getProjectTasks($projectID, $orderBy, $pager);
        foreach($tasks as $key => $task) $productTasks[$task->product][] = $task;

        $this->view->title       = $this->projects[$projectID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]  = html::a($this->createLink('project', 'testtask', "projectID=$projectID"), $this->projects[$projectID]);
        $this->view->position[]  = $this->lang->testtask->common;
        $this->view->projectID   = $projectID;
        $this->view->projectName = $this->projects[$projectID];
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->tasks       = $productTasks;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->products    = $this->loadModel('product')->getPairs();

        $this->display();
    }

    /**
     * Browse burndown chart of a project.
     *
     * @param  int       $projectID
     * @param  string    $type
     * @param  int       $interval
     * @access public
     * @return void
     */
    public function burn($projectID = 0, $type = 'noweekend', $interval = 0)
    {
        $this->loadModel('report');
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->burn;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->burn;

        /* Get date list. */
        $projectInfo = $this->project->getByID($projectID);
        list($dateList, $interval) = $this->project->getDateList($projectInfo->begin, $projectInfo->end, $type, $interval, 'Y-m-d');
        $chartData = $this->project->buildBurnData($projectID, $dateList, $type);

        $dayList = array_fill(1, floor($project->days / $this->config->project->maxBurnDay) + 5, '');
        foreach($dayList as $key => $val) $dayList[$key] = $this->lang->project->interval . ($key + 1) . $this->lang->day;

        /* Assign. */
        $this->view->title       = $title;
        $this->view->position    = $position;
        $this->view->tabID       = 'burn';
        $this->view->projectID   = $projectID;
        $this->view->projectName = $project->name;
        $this->view->type        = $type;
        $this->view->interval    = $interval;
        $this->view->chartData   = $chartData;
        $this->view->dayList     = array('full' => $this->lang->project->interval . '1' . $this->lang->day) + $dayList;

        unset($this->lang->modulePageActions);
        $this->display();
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
        $this->display();
    }

    /**
     * Fix burn for first date.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function fixFirst($projectID)
    {
        if($_POST)
        {
            $this->project->fixFirst($projectID);
            die(js::reload('parent.parent'));
        }

        $project = $this->project->getById($projectID);

        $this->view->firstBurn = $this->dao->select('*')->from(TABLE_BURN)->where('project')->eq($projectID)->andWhere('date')->eq($project->begin)->fetch();
        $this->view->project   = $project;
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
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        $title      = $project->name . $this->lang->colon . $this->lang->project->team;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->team;

        $this->view->title    = $title;
        $this->view->position = $position;

        $this->display();
    }

    /**
     * Create a project.
     *
     * @param string $projectID
     * @param string $copyProjectID
     * @param int    $planID
     *
     * @access public
     * @return void
     */
    public function create($projectID = '', $copyProjectID = '', $planID = 0, $confirm = 'no')
    {
        if($projectID)
        {
            if(!empty($planID))
            {
                if($confirm == 'yes')
                {
                    $this->project->linkStories($projectID);
                }
                else
                {
                    die(js::confirm($this->lang->project->importPlanStory, inlink('create', "projectID=$projectID&copyProjectID=&planID=$planID&confirm=yes"), inlink('create', "projectID=$projectID"), 'parent', 'parent'));
                }
            }
            $this->view->title     = $this->lang->project->tips;
            $this->view->tips      = $this->fetch('project', 'tips', "projectID=$projectID");
            $this->view->projectID = $projectID;
            $this->display();
            exit;
        }

        $name         = '';
        $code         = '';
        $team         = '';
        $products     = array();
        $whitelist    = '';
        $acl          = 'open';
        $plan         = new stdClass();
        $productPlan  = array();
        $productPlans = array();
        if($copyProjectID)
        {
            $copyProject = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($copyProjectID)->fetch();
            $name        = $copyProject->name;
            $code        = $copyProject->code;
            $team        = $copyProject->team;
            $acl         = $copyProject->acl;
            $whitelist   = $copyProject->whitelist;
            $products    = $this->project->getProducts($copyProjectID);
            foreach($products as $product)
            {
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
            }
        }

        if(!empty($planID))
        {
            $plan        = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->eq($planID)->fetch();
            $products    = $this->dao->select('t1.id, t1.name, t1.type, t2.branch')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
                ->where('t1.id')->eq($plan->product)
                ->fetchAll('id');
            $productPlan = $this->loadModel('productplan')->getPairs($plan->product, 0, 'unexpired');
        }

        if(!empty($_POST))
        {
            $projectID = $copyProjectID == '' ? $this->project->create() : $this->project->create($copyProjectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->project->updateProducts($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('project', $projectID, 'opened', '', join(',', $_POST['products']));

            $this->executeHooks($projectID);

            $planID = '';
            if(isset($_POST['plans']))
            {
                foreach($_POST['plans'] as $planID)
                {
                    if(!empty($planID)) break;
                }
            }
            if(!empty($planID))
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID&copyProjectID=&planID=$planID&confirm=no")));
            }
            else
            {
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID")));
            }
        }

        $this->view->isSprint = false;
        if(strpos($this->config->custom->productProject, '_2')) 
        {
            $this->view->isSprint = true;
            unset($this->lang->project->typeList['waterfall']);
            unset($this->lang->project->typeList['ops']);

            unset($this->lang->project->endList[62]);
            unset($this->lang->project->endList[93]);
            unset($this->lang->project->endList[186]);
            unset($this->lang->project->endList[365]);
        }

        $projectID = key($this->projects);
        if($this->session->project) $projectID = $this->session->project;
        $this->project->setMenu($this->projects, $projectID);

        $this->view->title         = $this->lang->project->create;
        $this->view->position[]    = $this->view->title;
        $this->view->projects      = array('' => '') + $this->projects;
        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->view->allProducts   = array(0 => '') + $this->loadModel('product')->getPairs('noclosed|nocode');
        $this->view->acl           = $acl;
        $this->view->plan          = $plan;
        $this->view->name          = $name;
        $this->view->code          = $code;
        $this->view->team          = $team;
        $this->view->projectID     = $projectID;
        $this->view->products      = $products;
        $this->view->productPlan   = array(0 => '') + $productPlan;
        $this->view->productPlans  = array(0 => '') + $productPlans;
        $this->view->whitelist     = $whitelist;
        $this->view->copyProjectID = $copyProjectID;
        $this->view->branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->display();
    }

    /**
     * Edit a project.
     *
     * @param  int    $projectID
     * @param  string $action
     * @param  string $extra
     *
     * @access public
     * @return void
     */
    public function edit($projectID, $action = 'edit', $extra = '')
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $oldProducts = $this->project->getProducts($projectID);
            $changes     = $this->project->update($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->project->updateProducts($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($action == 'undelete')
            {
                $this->loadModel('action');
                $this->dao->update(TABLE_PROJECT)->set('deleted')->eq(0)->where('id')->eq($projectID)->exec();
                $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($extra)->exec();
                $this->action->create('project', $projectID, 'undeleted');
            }
            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->project->getProducts($projectID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            $products     = $diffProducts ? join(',', $newProducts) : '';

            if($changes or $diffProducts)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited', '', $products);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "projectID=$projectID")));
        }

        /* Set menu. */
        $this->project->setMenu($this->projects, $projectID);

        $projects = array('' => '') + $this->projects;
        $project  = $this->project->getById($projectID);
        $managers = $this->project->getDefaultManagers($projectID);

        /* Remove current project from the projects. */
        unset($projects[$projectID]);

        $title      = $this->lang->project->edit . $this->lang->colon . $project->name;
        $position[] = html::a($browseProjectLink, $project->name);
        $position[] = $this->lang->project->edit;

        $allProducts    = array(0 => '') + $this->loadModel('product')->getPairs('noclosed|nocode');
        $linkedProducts = $this->project->getProducts($project->id);
        $linkedBranches = array();
        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }
        $this->loadModel('productplan');
        $productPlans = array(0 => '');
        foreach($linkedProducts as $product)
        {
            $productPlans[$product->id] = $this->productplan->getPairs($product->id);
        }

        $this->view->isSprint = false;
        if(strpos($this->config->custom->productProject, '_2')) 
        {
            $this->view->isSprint = true;

            unset($this->lang->project->typeList['waterfall']);
            unset($this->lang->project->typeList['ops']);

            unset($this->lang->project->endList[62]);
            unset($this->lang->project->endList[93]);
            unset($this->lang->project->endList[186]);
            unset($this->lang->project->endList[365]);
        }

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst', $project->PO, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst',  $project->PM, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst',  $project->QD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', $project->RD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->projects       = $projects;
        $this->view->project        = $project;
        $this->view->poUsers        = $poUsers;
        $this->view->pmUsers        = $pmUsers;
        $this->view->qdUsers        = $qdUsers;
        $this->view->rdUsers        = $rdUsers;
        $this->view->groups         = $this->loadModel('group')->getPairs();
        $this->view->allProducts    = $allProducts;
        $this->view->linkedProducts = $linkedProducts;
        $this->view->productPlans   = $productPlans;
        $this->view->branchGroups   = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);
        $this->display();
    }

    /**
     * Batch edit.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchEdit($projectID = 0)
    {
        if($this->post->names)
        {
            $allChanges = $this->project->batchUpdate();
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

        $this->project->setMenu($this->projects, $projectID);

        $projectIDList = $this->post->projectIDList ? $this->post->projectIDList : die(js::locate($this->session->projectList, 'parent'));
        $projects      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->fetchAll('id');

        $appendPoUsers = $appendPmUsers = $appendQdUsers = $appendRdUsers = array();
        foreach($projects as $project)
        {
            $appendPoUsers[$project->PO] = $project->PO;
            $appendPmUsers[$project->PM] = $project->PM;
            $appendQdUsers[$project->QD] = $project->QD;
            $appendRdUsers[$project->RD] = $project->RD;
        }

        /* Set custom. */
        foreach(explode(',', $this->config->project->customBatchEditFields) as $field) $customFields[$field] = $this->lang->project->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->project->custom->batchEditFields;

        $this->loadModel('user');
        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst', $appendPmUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst',  $appendPoUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst',  $appendQdUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', $appendRdUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $this->view->title         = $this->lang->project->batchEdit;
        $this->view->position[]    = $this->lang->project->batchEdit;
        $this->view->projectIDList = $projectIDList;
        $this->view->projects      = $projects;
        $this->view->pmUsers       = $pmUsers;
        $this->view->poUsers       = $poUsers;
        $this->view->qdUsers       = $qdUsers;
        $this->view->rdUsers       = $rdUsers;
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
        $project   = $this->commonAction($projectID);
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

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->project->start;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $this->view->project->name);
        $this->view->position[] = $this->lang->project->start;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->display();
    }

    /**
     * Delay project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function putoff($projectID)
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->project->putoff($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Delayed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($projectID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->project->putoff;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $this->view->project->name);
        $this->view->position[] = $this->lang->project->putoff;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->display();
    }

    /**
     * Suspend project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function suspend($projectID)
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

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

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->project->suspend;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $this->view->project->name);
        $this->view->position[] = $this->lang->project->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->display();
    }

    /**
     * Activate project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function activate($projectID)
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

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

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->project->activate;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $this->view->project->name);
        $this->view->position[] = $this->lang->project->activate;
        $this->view->project    = $project;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * Close project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function close($projectID)
    {
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

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

        $this->view->title      = $this->view->project->name . $this->lang->colon .$this->lang->project->close;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $this->view->project->name);
        $this->view->position[] = $this->lang->project->close;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('project', $projectID);
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
        $project = $this->project->getById($projectID, true);
        if(!$project) die(js::error($this->lang->notFound) . js::locate('back'));

        $products = $this->project->getProducts($project->id);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);
        $this->app->loadLang('bug');

        list($dateList, $interval) = $this->project->getDateList($project->begin, $project->end, 'noweekend', 0, 'Y-m-d');
        $chartData = $this->project->buildBurnData($projectID, $dateList, 'noweekend');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->executeHooks($projectID);

        $this->view->title      = $this->lang->project->view;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->view->title;

        $this->view->project      = $project;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->planGroups   = $this->project->getPlans($products);
        $this->view->groups       = $this->loadModel('group')->getPairs();
        $this->view->actions      = $this->loadModel('action')->getList('project', $projectID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', $projectID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('project', $projectID);
        $this->view->statData     = $this->project->statRelatedData($projectID);
        $this->view->chartData    = $chartData;

        $this->display();
    }

    /**
     * Kanban.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function kanban($projectID, $type = 'story', $orderBy = 'order_asc')
    {
        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',  $uri);
        $this->app->session->set('storyList', $uri);
        $this->app->session->set('bugList',   $uri);

        /* Compatibility IE8*/
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) header("X-UA-Compatible: IE=EmulateIE7");

        $this->project->setMenu($this->projects, $projectID);
        $project = $this->loadModel('project')->getById($projectID);
        $tasks   = $this->project->getKanbanTasks($projectID, "id");
        $bugs    = $this->loadModel('bug')->getProjectBugs($projectID);
        $stories = $this->loadModel('story')->getProjectStories($projectID, $orderBy);

        $kanbanGroup   = $this->project->getKanbanGroupData($stories, $tasks, $bugs, $type);
        $kanbanSetting = $this->project->getKanbanSetting();

        $this->view->title         = $this->lang->project->kanban;
        $this->view->position[]    = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->kanban;
        $this->view->stories       = $stories;
        $this->view->realnames     = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyOrder    = $orderBy;
        $this->view->orderBy       = 'id_asc';
        $this->view->projectID     = $projectID;
        $this->view->browseType    = '';
        $this->view->project       = $project;
        $this->view->type          = $type;
        $this->view->kanbanGroup   = $kanbanGroup;
        $this->view->kanbanColumns = $this->project->getKanbanColumns($kanbanSetting);
        $this->view->statusMap     = $this->project->getKanbanStatusMap($kanbanSetting);
        $this->view->statusList    = $this->project->getKanbanStatusList($kanbanSetting);
        $this->view->colorList     = $this->project->getKanbanColorList($kanbanSetting);

        $this->display();
    }

    /**
     * Tree view.
     * Product
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function tree($projectID, $type = 'task')
    {
        $this->project->setMenu($this->projects, $projectID);
        $project = $this->loadModel('project')->getById($projectID);
        $tree    = $this->project->getProjectTree($projectID);

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);
        $this->app->session->set('caseList', $uri);
        $this->app->session->set('bugList', $uri);

        if($type === 'json') die(helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS));

        $this->view->title      = $this->lang->project->tree;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->tree;
        $this->view->project    = $project;
        $this->view->projectID  = $projectID;
        $this->view->level      = $type;
        $this->view->tree       = $this->project->printTree($tree);
        $this->display();
    }

    /**
     * Print kanban.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function printKanban($projectID, $orderBy = 'id_asc')
    {
        $this->view->title = $this->lang->project->printKanban;
        $contents = array('story', 'wait', 'doing', 'done', 'cancel');

        if($_POST)
        {
            $stories    = $this->loadModel('story')->getProjectStories($projectID, $orderBy);
            $storySpecs = $this->story->getStorySpecs(array_keys($stories));

            $order = 1;
            foreach($stories as $story) $story->order = $order++;

            $kanbanTasks = $this->project->getKanbanTasks($projectID, "id");
            $kanbanBugs  = $this->loadModel('bug')->getProjectBugs($projectID);

            $users       = array();
            $taskAndBugs = array();
            foreach($kanbanTasks as $task)
            {
                $storyID = $task->storyID;
                $status  = $task->status;
                $users[] = $task->assignedTo;

                $taskAndBugs[$status]["task{$task->id}"] = $task;
            }
            foreach($kanbanBugs as $bug)
            {
                $storyID = $bug->story;
                $status  = $bug->status;
                $status  = $status == 'active' ? 'wait' : ($status == 'resolved' ? ($bug->resolution == 'postponed' ? 'cancel' : 'done') : $status);
                $users[] = $bug->assignedTo;

                $taskAndBugs[$status]["bug{$bug->id}"] = $bug;
            }

            $datas = array();
            foreach($contents as $content)
            {
                if($content != 'story' and !isset($taskAndBugs[$content])) continue;
                $datas[$content] = $content == 'story' ? $stories : $taskAndBugs[$content];
            }

            unset($this->lang->story->stageList['']);
            unset($this->lang->story->stageList['wait']);
            unset($this->lang->story->stageList['planned']);
            unset($this->lang->story->stageList['projected']);
            unset($this->lang->story->stageList['released']);
            unset($this->lang->task->statusList['']);
            unset($this->lang->task->statusList['wait']);
            unset($this->lang->task->statusList['closed']);
            unset($this->lang->bug->statusList['']);
            unset($this->lang->bug->statusList['closed']);

            $originalDatas = $datas;
            if($this->post->content == 'increment')
            {
                $prevKanbans = $this->project->getPrevKanban($projectID);
                foreach($datas as $type => $data)
                {
                    if(isset($prevKanbans[$type]))
                    {
                        $prevData = $prevKanbans[$type];
                        foreach($prevData as $id)
                        {
                            if(isset($data[$id])) unset($datas[$type][$id]);
                        }
                    }
                }
            }

            $this->project->saveKanbanData($projectID, $originalDatas);

            $hasBurn = $this->post->content == 'all';
            if($hasBurn)
            {
                /* Get date list. */
                $projectInfo    = $this->project->getByID($projectID);
                list($dateList) = $this->project->getDateList($projectInfo->begin, $projectInfo->end, 'noweekend');
                $chartData      = $this->project->buildBurnData($projectID, $dateList, 'noweekend');
            }

            $this->view->hasBurn    = $hasBurn;
            $this->view->datas      = $datas;
            $this->view->chartData  = $chartData;
            $this->view->storySpecs = $storySpecs;
            $this->view->realnames  = $this->loadModel('user')->getRealNameAndEmails($users);
            $this->view->projectID  = $projectID;

            die($this->display());

        }

        $this->project->setMenu($this->projects, $projectID);
        $project = $this->project->getById($projectID);

        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->printKanban;
        $this->display();
    }

    /**
     * Story kanban.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function storyKanban($projectID)
    {
        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('storyList', $uri);

        /* Compatibility IE8*/
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) header("X-UA-Compatible: IE=EmulateIE7");

        $this->project->setMenu($this->projects, $projectID);
        $project = $this->loadModel('project')->getById($projectID);
        $stories = $this->loadModel('story')->getProjectStories($projectID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        /* Get project's product. */
        $productID = 0;
        $productPairs = $this->loadModel('product')->getProductsByProject($projectID);
        if($productPairs) $productID = key($productPairs);

        $this->view->title      = $this->lang->project->storyKanban;
        $this->view->position[] = html::a($this->createLink('project', 'story', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->storyKanban;
        $this->view->stories    = $this->story->getKanbanGroupData($stories);
        $this->view->realnames  = $this->loadModel('user')->getPairs('noletter');
        $this->view->projectID  = $projectID;
        $this->view->project    = $project;
        $this->view->productID  = $productID;

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
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('project')->eq($projectID)->exec();
            $this->project->updateUserView($projectID);
            $this->session->set('project', '');
            $this->executeHooks($projectID);
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
    public function manageProducts($projectID, $from = '')
    {
        /* use first project if projectID does not exist. */
        if(!isset($this->projects[$projectID])) $projectID = key($this->projects);

        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $oldProducts = $this->project->getProducts($projectID);

            if($from == 'buildCreate' && $this->session->buildCreate) $browseProjectLink = $this->session->buildCreate;

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
        $project  = $this->project->getById($projectID);

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);

        /* Title and position. */
        $title      = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $position[] = html::a($browseProjectLink, $project->name);
        $position[] = $this->lang->project->manageProducts;

        $allProducts     = $this->product->getPairs('noclosed|nocode');
        $linkedProducts  = $this->project->getProducts($project->id);
        $linkedBranches  = array();
        // Merge allProducts and linkedProducts for closed product.
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

    /**
     * Manage members of the project.
     *
     * @param  int    $projectID
     * @param  int    $team2Import    the team to import.
     * @access public
     * @return void
     */
    public function manageMembers($projectID = 0, $team2Import = 0, $dept = '')
    {
        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            die(js::locate($this->createLink('project', 'team', "projectID=$projectID"), 'parent'));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $project        = $this->project->getById($projectID);
        $users          = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback', '', $this->config->maxCount);
        $roles          = $this->user->getUserRoles(array_keys($users));
        $deptUsers      = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);
        $currentMembers = $this->project->getTeamMembers($projectID);
        $members2Import = $this->project->getMembers2Import($team2Import, array_keys($currentMembers));
        $teams2Import   = $this->project->getTeams2Import($this->app->user->account, $projectID);
        $teams2Import   = array('' => '') + $teams2Import;

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["accounts[]"] = $this->config->user->moreLink;

        $title      = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->manageMembers;

        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->project        = $project;
        $this->view->users          = $users;
        $this->view->deptUsers      = $deptUsers;
        $this->view->roles          = $roles;
        $this->view->dept           = $dept;
        $this->view->depts          = array('' => '') + $this->loadModel('dept')->getOptionMenu();
        $this->view->currentMembers = $currentMembers;
        $this->view->members2Import = $members2Import;
        $this->view->teams2Import   = $teams2Import;
        $this->view->team2Import    = $team2Import;
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
                $this->send($response);
            }
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
    public function linkStory($projectID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->loadModel('story');
        $this->loadModel('product');

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
            die(js::locate($browseLink));
        }

        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;

        /* Set modules and branches. */
        $modules     = array();
        $branches    = array();
        $productType = 'normal';
        $this->loadModel('tree');
        $this->loadModel('branch');
        foreach($products as $product)
        {
            $productModules = $this->tree->getOptionMenu($product->id);
            foreach($productModules as $moduleID => $moduleName) $modules[$moduleID] = ((count($products) >= 2 and $moduleID != 0) ? $product->name : '') . $moduleName;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                $branches[$product->branch] = $product->branch;
                if($product->branch == 0)
                {
                    foreach($this->branch->getPairs($product->id, 'noempty') as $branchID => $branchName) $branches[$branchID] = $branchID;
                }
            }
        }

        /* Build the search form. */
        $actionURL    = $this->createLink('project', 'linkStory', "projectID=$projectID&browseType=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->project->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'linkStory');

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch('', 0, $queryID, 'id', $projectID);
        }
        else
        {
            $allStories = $this->story->getProductStories(array_keys($products), $branches, $moduleID = '0', $status = 'active', 'story', 'id_desc', $hasParent = false, '', $pager = null);
        }
        foreach($allStories as $id => $story)
        {
            if(isset($prjStories[$story->id])) unset($allStories[$id]);
        }
        $prjStories = $this->story->getProjectStoryPairs($projectID);

        foreach($allStories as $id => $story)
        {
            if(isset($prjStories[$story->id])) unset($allStories[$id]);
        }

        /* Pager. */
        $recTotal   = count($allStories);
        $allStories = array_chunk($allStories, $recPerPage);
        $this->app->loadClass('pager', $static = true);

        /* Assign. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->linkStory;
        $position[] = html::a($browseLink, $project->name);
        $position[] = $this->lang->project->linkStory;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->project      = $project;
        $this->view->products     = $products;
        $this->view->allStories   = empty($allStories) ? $allStories : $allStories[$pageID - 1];;
        $this->view->pager        = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->browseType   = $browseType;
        $this->view->productType  = $productType;
        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->branchGroups = $branchGroups;
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
            die(js::confirm($this->lang->project->confirmUnlinkStory, $this->createLink('project', 'unlinkstory', "projectID=$projectID&storyID=$storyID&confirm=yes")));
        }
        else
        {
            $this->project->unlinkStory($projectID, $storyID);

            /* if kanban then reload and if ajax request then send result. */
            if(isonlybody())
            {
                die(js::reload('parent'));
            }
            elseif(helper::isAjaxRequest())
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
                $this->send($response);
            }
            die(js::locate($this->app->session->storyList, 'parent'));
        }
    }

    /**
     * batch unlink story.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($projectID)
    {
        if(isset($_POST['storyIdList']))
        {
            $storyIdList = $this->post->storyIdList;
            $_POST       = array();
            foreach($storyIdList as $storyID)
            {
                $this->project->unlinkStory($projectID, $storyID);
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        die(js::locate($this->createLink('project', 'story', "projectID=$projectID")));
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
    public function dynamic($projectID = 0, $type = 'today', $param = '', $recTotal = 0, $date = '', $direction = 'next')
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

        /* use first project if projectID does not exist. */
        if(!isset($this->projects[$projectID])) $projectID = key($this->projects);

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';
        $sort    = $this->loadModel('common')->appendOrder($orderBy);

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
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Set the user and type. */
        $account = $type == 'account' ? $param : 'all';
        $period  = $type == 'account' ? 'all'  : $type;
        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($account, $period, $sort, $pager, 'all', $projectID, $date, $direction);

        /* The header and position. */
        $project = $this->project->getByID($projectID);
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->dynamic;
        $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->dynamic;

        /* Assign. */
        $this->view->projectID  = $projectID;
        $this->view->type       = $type;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->account    = $account;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->dateGroups = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction  = $direction;
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
        $products = $this->project->getProducts($projectID, false);
        die(html::select('product', $products, '', 'class="form-control"'));
    }

    /**
     * AJAX: get team members of the project.
     *
     * @param  int    $projectID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function ajaxGetMembers($projectID, $assignedTo = '')
    {
        $users      = $this->project->getTeamMemberPairs($projectID);
        if($this->app->getViewType() === 'json')
        {
            die(json_encode($users));
        }
        else
        {
            $assignedTo = isset($users[$assignedTo]) ? $assignedTo : '';
            die(html::select('assignedTo', $users, $assignedTo, "class='form-control'"));
        }
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
        $this->view->project   = $this->project->getById($projectID);
        $this->view->projectID = $projectID;
        $this->display('project', 'tips');
    }

    /**
     * Drop menu page.
     *
     * @param  int    $projectID
     * @param  int    $module
     * @param  int    $method
     * @param  int    $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($projectID, $module, $method, $extra)
    {
        $this->view->link      = $this->project->getProjectLink($module, $method, $extra);
        $this->view->projectID = $projectID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;

        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in(array_keys($this->projects))->orderBy('order desc')->fetchAll();
        $projectPairs = array();
        foreach($projects as $project) $projectPairs[$project->id] = $project->name;
        $this->view->projects = $projects;
        $this->display();
    }

    /**
     * Update order.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        $idList   = explode(',', trim($this->post->projects, ','));
        $orderBy  = $this->post->orderBy;
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
     * Story sort.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function storySort($projectID)
    {
        $idList   = explode(',', trim($this->post->storys, ','));
        $orderBy  = $this->post->orderBy;

        $order = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('story')->in($idList)->andWhere('project')->eq($projectID)->orderBy('order_asc')->fetch('order');
        foreach($idList as $storyID)
        {
            $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('project')->eq($projectID)->exec();
            $order++;
        }
    }

    /**
     * All project.
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
    public function all($status = 'undone', $projectID = 0, $orderBy = 'order_desc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if($this->projects)
        {
            $project   = $this->commonAction($projectID);
            $projectID = $project->id;
        }
        $this->session->set('projectList', $this->app->getURI(true));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('my');
        $this->view->title         = $this->lang->project->allProject;
        $this->view->position[]    = $this->lang->project->allProject;
        $this->view->projectStats  = $this->project->getProjectStats($status == 'byproduct' ? 'all' : $status, $productID, 0, 30, $orderBy, $pager);
        $this->view->products      = array(0 => $this->lang->product->select) + $this->loadModel('product')->getPairs();
        $this->view->productID     = $productID;
        $this->view->projectID     = $projectID;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->status        = $status;

        $this->display();
    }

    /**
     * Export project.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($status, $productID, $orderBy)
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

            $projectStats = $this->project->getProjectStats($status == 'byproduct' ? 'all' : $status, $productID, 0, 30, $orderBy, null);
            $users        = $this->loadModel('user')->getPairs('noletter');
            foreach($projectStats as $i => $project)
            {
                $project->PM            = zget($users, $project->PM);
                $project->status        = isset($project->delay) ? $projectLang->delayed : $this->processStatus('project', $project);
                $project->totalEstimate = $project->hours->totalEstimate;
                $project->totalConsumed = $project->hours->totalConsumed;
                $project->totalLeft     = $project->hours->totalLeft;
                $project->progress      = $project->hours->progress . '%';

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$project->id},") === false) unset($projectStats[$i]);
                }
            }
            if(isset($this->config->bizVersion)) list($fields, $projectStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $projectStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $projectStats);
            $this->post->set('kind', 'project');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->display();
    }

    /**
     * Doc for compatible.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function doc($projectID)
    {
        $this->locate($this->createLink('doc', 'objectLibs', "type=project&objectID=$projectID&from=project"));
    }

    /**
     * Kanban setting.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxKanbanSetting($projectID)
    {
        if($_POST)
        {
            $this->loadModel('setting');
            $data = fixer::input('post')->get();
            if(common::hasPriv('project', 'kanbanHideCols'))
            {
                $allCols = $data->allCols;
                $this->setting->setItem("system.project.kanbanSetting.allCols", $allCols);
            }
            if(common::hasPriv('project', 'kanbanColsColor')) $this->setting->setItem("system.project.kanbanSetting.colorList", json_encode($data->colorList));

            die(js::reload('parent.parent'));
        }

        $this->app->loadLang('task');

        $this->view->setting   = $this->project->getKanbanSetting();
        $this->view->projectID = $projectID;
        $this->display();
    }

    /**
     * Ajax reset kanban setting
     *
     * @param  int    $projectID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxResetKanban($projectID, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->kanbanSetting->noticeReset, inlink('ajaxResetKanban', "projectID=$projectID&confirm=yes")));

        $this->loadModel('setting');

        if(common::hasPriv('project', 'kanbanHideCols') and isset($this->config->project->kanbanSetting->allCols))
        {
            $allCols = json_decode($this->config->project->kanbanSetting->allCols, true);
            unset($allCols[$projectID]);
            $this->setting->setItem("system.project.kanbanSetting.allCols", json_encode($allCols));
        }

        $account = $this->app->user->account;
        $this->setting->deleteItems("owner={$account}&module=project&section=kanbanSetting&key=showOption");

        if(common::hasPriv('project', 'kanbanColsColor')) $this->setting->deleteItems("owner=system&module=project&section=kanbanSetting&key=colorList");

        die(js::reload('parent.parent'));
    }

    /**
     * Import stories by plan.
     *
     * @param int $projectID
     * @param int $planID
     *
     * @access public
     * @return void
     */
    public function importPlanStories($projectID, $planID)
    {
        $planStories = $planProducts = array();
        $planStory   = $this->loadModel('story')->getPlanStories($planID);
        $count = 0;
        if(!empty($planStory))
        {
            foreach($planStory as $id => $story)
            {
                if($story->status == 'draft') 
                {
                    $count++; 
                    unset($planStory[$id]);
                    continue;
                }
                $planProducts[$story->id] = $story->product;
            }
            $planStories = array_keys($planStory);
            $this->project->linkStory($projectID, $planStories, $planProducts);
        }
        if($count != 0) echo js::alert(sprintf($this->lang->project->haveDraft, $count)) . js::locate($this->createLink('project', 'story', "projectID=$projectID"));
        die(js::locate(helper::createLink('project', 'story', 'projectID=' . $projectID), 'parent'));
    }

    /**
     * Story info for tree list.
     *
     * @param int $storyID
     * @param int $version
     *
     * @access public
     * @return void
     */
    public function treeStory($storyID, $version = 0)
    {
        $this->loadModel('story');
        $story = $this->story->getById($storyID, $version, true);

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, type')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $bugs         = $this->dao->select('id,title')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $fromBug      = $this->dao->select('id,title')->from(TABLE_BUG)->where('toStory')->eq($storyID)->fetch();
        $cases        = $this->dao->select('id,title')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $modulePath   = $this->loadModel('tree')->getParents($story->module);
        $users        = $this->loadModel('user')->getPairs('noletter');

        $this->view->product    = $product;
        $this->view->branches   = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->plan       = $plan;
        $this->view->bugs       = $bugs;
        $this->view->fromBug    = $fromBug;
        $this->view->cases      = $cases;
        $this->view->story      = $story;
        $this->view->users      = $users;
        $this->view->projects   = $this->loadModel('project')->getPairs('nocode');
        $this->view->actions    = $this->loadModel('action')->getList('story', $storyID);
        $this->view->modulePath = $modulePath;
        $this->view->version    = $version == 0 ? $story->version : $version;
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('story', $storyID);
        $this->display();
    }

    /**
     * Task info for tree list.
     *
     * @param int $taskID
     *
     * @access public
     * @return void
     */
    public function treeTask($taskID)
    {
        $this->loadModel('task');
        $task = $this->task->getById($taskID, true);
        if($task->fromBug != 0)
        {
            $bug = $this->loadModel('bug')->getById($task->fromBug);
            $task->bugSteps = '';
            if($bug)
            {
                $task->bugSteps = $this->loadModel('file')->setImgSize($bug->steps);
                foreach($bug->files as $file) $task->files[] = $file;
            }
            $this->view->fromBug = $bug;
        }
        else
        {
            $story = $this->loadModel('story')->getById($task->story);
            $task->storySpec     = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->spec);
            $task->storyVerify   = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->verify);
            $task->storyFiles    = $this->loadModel('file')->getByObject('story', $task->story);
        }

        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        $project = $this->project->getById($task->project);

        $this->view->task    = $task;
        $this->view->project = $project;
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }
}
