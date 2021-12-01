<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class execution extends control
{
    /**
     * The global $executions.
     *
     * @var array
     * @access public
     */
    public $executions;

    /**
     * The global $objectType.
     *
     * @var string
     * @access public
     */
    public $objectType;

    /**
     * Construct function, Set executions.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->loadModel('project');

        if(defined('IN_UPGRADE') and IN_UPGRADE) return false;
        $this->executions = $this->execution->getPairs(0, 'all', 'nocode');
        $skipCreateStep   = array('computeburn', 'ajaxgetdropmenu', 'executionkanban', 'ajaxgetteammembers');
        if(!in_array($this->methodName, $skipCreateStep) and $this->app->tab == 'execution')
        {
            if(!$this->executions and $this->methodName != 'index' and $this->methodName != 'create' and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
        }
        $this->objectType = $this->config->systemMode == 'classic' ? 'project' : 'execution';
    }

    /**
     * The index page.
     *
     * @param  string $locate     yes|no locate to the browse page or not.
     * @param  string $status     the executions status, if locate is no, then get executions by the $status.
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function index($locate = 'auto', $executionID = 0)
    {
        if($locate == 'yes') $this->locate($this->createLink('execution', 'task'));

        $this->commonAction($executionID);

        if(common::hasPriv('execution', 'create')) $this->lang->TRActions = html::a($this->createLink('execution', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->execution->create, '', "class='btn btn-primary'");

        $this->view->title      = $this->lang->execution->index;
        $this->view->position[] = $this->lang->execution->index;

        $this->display();
    }

    /**
     * Browse a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function browse($executionID = 0)
    {
        $this->locate($this->createLink($this->moduleName, 'task', "executionID=$executionID"));
    }

    /**
     * Common actions.
     *
     * @param  int    $executionID
     * @param  string $extra
     * @access public
     * @return object current object
     */
    public function commonAction($executionID = 0, $extra = '')
    {
        $this->loadModel('product');

        /* Get executions and products info. */
        $executionID     = $this->execution->saveState($executionID, $this->executions);
        $execution       = $this->execution->getById($executionID);
        $products        = $this->execution->getProducts($executionID);
        $childExecutions = $this->execution->getChildExecutions($executionID);
        $teamMembers     = $this->execution->getTeamMembers($executionID);
        $actions         = $this->loadModel('action')->getList($this->objectType, $executionID);

        /* Set menu. */
        $this->execution->setMenu($executionID, $buildID = 0, $extra);

        /* Assign. */
        $this->view->executions      = $this->executions;
        $this->view->execution       = $execution;
        $this->view->childExecutions = $childExecutions;
        $this->view->products        = $products;
        $this->view->teamMembers     = $teamMembers;

        return $execution;
    }

    /**
     * Tasks of a execution.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function task($executionID = 0, $status = 'unclosed', $param = 0, $orderBy = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->loadModel('tree');
        $this->loadModel('search');
        $this->loadModel('task');
        $this->loadModel('datatable');
        $this->loadModel('setting');

        if(common::hasPriv('execution', 'create')) $this->lang->TRActions = html::a($this->createLink('execution', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->execution->create, '', "class='btn btn-primary'");

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        /* Set browse type. */
        $browseType = strtolower($status);

        /* Get products by execution. */
        $execution   = $this->commonAction($executionID, $status);
        $executionID = $execution->id;
        $products    = $this->loadModel('product')->getProductPairsByProject($executionID);
        setcookie('preExecutionID', $executionID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        /* Save the recently five executions visited in the cookie. */
        $recentExecutions = isset($this->config->execution->recentExecutions) ? explode(',', $this->config->execution->recentExecutions) : array();
        array_unshift($recentExecutions, $executionID);
        $recentExecutions = array_unique($recentExecutions);
        $recentExecutions = array_slice($recentExecutions, 0, 5);
        $this->setting->setItem($this->app->user->account . 'common.execution.recentExecutions', implode(',', $recentExecutions));
        $this->setting->setItem($this->app->user->account . 'common.execution.lastExecution', $executionID);

        if($this->cookie->preExecutionID != $executionID)
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
        $this->app->session->set('taskList', $uri, 'execution');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionTaskOrder ? $this->cookie->executionTaskOrder : 'status,id_desc';
        setcookie('executionTaskOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Header and position. */
        $this->view->title      = $execution->name . $this->lang->colon . $this->lang->execution->task;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[] = $this->lang->execution->task;

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get tasks. */
        $tasks = $this->execution->getTasks($productID, $executionID, $this->executions, $browseType, $queryID, $moduleID, $sort, $pager);
        if(empty($tasks) and $pageID > 1)
        {
            $pager = pager::init(0, $recPerPage, 1);
            $tasks = $this->execution->getTasks($productID, $executionID, $this->executions, $browseType, $queryID, $moduleID, $sort, $pager);
        }

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'task', "executionID=$executionID&status=bySearch&param=myQueryID");
        $this->config->execution->search['onMenuBar'] = 'yes';
        $this->execution->buildTaskSearchForm($executionID, $this->executions, $queryID, $actionURL);

        /* team member pairs. */
        $memberPairs = array();
        foreach($this->view->teamMembers as $key => $member) $memberPairs[$key] = $member->realname;

        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $extra         = (isset($this->config->execution->task->allModule) && $this->config->execution->task->allModule == 1) ? 'allModule' : '';
        $showModule    = !empty($this->config->datatable->executionTask->showModule) ? $this->config->datatable->executionTask->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($executionID, 'task', $showModule) : array();

        /* Assign. */
        $this->view->tasks        = $tasks;
        $this->view->summary      = $this->execution->summary($tasks);
        $this->view->tabID        = 'task';
        $this->view->pager        = $pager;
        $this->view->recTotal     = $pager->recTotal;
        $this->view->recPerPage   = $pager->recPerPage;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;
        $this->view->status       = $status;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->param        = $param;
        $this->view->executionID  = $executionID;
        $this->view->execution    = $execution;
        $this->view->productID    = $productID;
        $this->view->modules      = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
        $this->view->moduleID     = $moduleID;
        $this->view->moduleTree   = $this->tree->getTaskTreeMenu($executionID, $productID, $startModuleID = 0, array('treeModel', 'createTaskLink'), $extra);
        $this->view->memberPairs  = $memberPairs;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->view->setModule    = true;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Browse tasks in group.
     *
     * @param  int    $executionID
     * @param  string $groupBy    the field to group by
     * @param  string $filter
     * @access public
     * @return void
     */
    public function grouptask($executionID = 0, $groupBy = 'story', $filter = '')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true), 'execution');

        /* Header and session. */
        $this->view->title      = $execution->name . $this->lang->colon . $this->lang->execution->task;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[] = $this->lang->execution->task;

        /* Get tasks and group them. */
        if(empty($groupBy))$groupBy = 'story';
        if(($groupBy == 'story') and ($execution->type == 'ops'))$groupBy = 'status';
        $sort        = $this->loadModel('common')->appendOrder($groupBy);
        $tasks       = $this->loadModel('task')->getExecutionTasks($executionID, $productID = 0, $status = 'all', $modules = 0, $sort);
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
        $filter = (empty($filter) and isset($this->lang->execution->groupFilter[$groupBy])) ? key($this->lang->execution->groupFilter[$groupBy]) : $filter;
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
        $this->view->members     = $this->execution->getTeamMembers($executionID);
        $this->view->tasks       = $groupTasks;
        $this->view->tabID       = 'task';
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->executionID = $executionID;
        $this->view->users       = $users;
        $this->view->moduleID    = 0;
        $this->view->moduleName  = $this->lang->tree->all;
        $this->view->filter      = $filter;
        $this->view->allCount    = $allCount;
        $this->display();
    }

    /**
     * Import tasks undoned from other executions.
     *
     * @param  int    $executionID
     * @param  int    $fromExecution
     * @access public
     * @return void
     */
    public function importTask($toExecution, $fromExecution = 0)
    {
        if(!empty($_POST))
        {
            $this->execution->importTask($toExecution);
            die(js::locate(inlink('importTask', "toExecution=$toExecution&fromExecution=$fromExecution"), 'parent'));
        }

        $execution   = $this->commonAction($toExecution);
        $toExecution = $execution->id;
        $branches    = $this->execution->getBranches($toExecution);
        $tasks       = $this->execution->getTasks2Imported($toExecution, $branches);
        $executions  = $this->execution->getToImport(array_keys($tasks), $execution->type);
        unset($executions[$toExecution]);
        unset($tasks[$toExecution]);

        if($fromExecution == 0)
        {
            $tasks2Imported = array();
            foreach($executions as $id  => $executionName)
            {
                $tasks2Imported = array_merge($tasks2Imported, $tasks[$id]);
            }
        }
        else
        {
            $tasks2Imported = zget($tasks, $fromExecution, array());
        }

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true), 'execution');

        $this->view->title            = $execution->name . $this->lang->colon . $this->lang->execution->importTask;
        $this->view->position[]       = html::a(inlink('browse', "executionID=$toExecution"), $execution->name);
        $this->view->position[]       = $this->lang->execution->importTask;
        $this->view->tasks2Imported   = $tasks2Imported;
        $this->view->executions       = $executions;
        $this->view->executionID      = $execution->id;
        $this->view->fromExecution    = $fromExecution;
        $this->display();
    }

    /**
     * Import from Bug.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importBug($executionID = 0, $browseType = 'all', $param = 0, $recTotal = 0, $recPerPage = 30, $pageID = 1)
    {
        $this->app->loadConfig('task');

        if(!empty($_POST))
        {
            $mails = $this->execution->importBug($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            die(js::locate($this->createLink('execution', 'importBug', "executionID=$executionID"), 'parent'));
        }

        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        $this->loadModel('bug');
        $executions = $this->execution->getPairs(0, 'all', 'nocode');
        $this->execution->setMenu($executionID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $title      = $executions[$executionID] . $this->lang->colon . $this->lang->execution->importBug;
        $position[] = html::a($this->createLink('execution', 'task', "executionID=$executionID"), $executions[$executionID]);
        $position[] = $this->lang->execution->importBug;

        /* Get users, products and executions.*/
        $users    = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $products = $this->dao->select('t1.product, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq($executionID)
            ->fetchPairs('product');
        if(!empty($products))
        {
            unset($executions);
            $executions = $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')
                ->on('t1.project = t2.id')
                ->where('t1.product')->in(array_keys($products))
                ->fetchPairs('project');
        }
        else
        {
            $executionName = $executions[$executionID];
            unset($executions);
            $executions[$executionID] = $executionName;
        }

        /* Get bugs.*/
        $bugs = array();
        if($browseType != "bysearch")
        {
            $bugs = $this->bug->getActiveAndPostponedBugs(array_keys($products), $executionID, $pager);
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
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN(array_keys($products)), $this->session->importBugQuery); // Search all execution.
            $bugs = $this->execution->getSearchBugs($products, $executionID, $bugQuery, $pager, 'id_desc');
        }

       /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('execution', 'importBug', "executionID=$executionID&browseType=bySearch&param=myQueryID");
        $this->config->bug->search['queryID']   = $queryID;
        if(!empty($products))
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'') + $products + array('all'=>$this->lang->execution->aboveAllProduct);
        }
        else
        {
            $this->config->bug->search['params']['product']['values'] = array(''=>'');
        }
        $this->config->bug->search['params']['execution']['values'] = array(''=>'') + $executions + array('all'=>$this->lang->execution->aboveAllExecution);
        $this->config->bug->search['params']['plan']['values']    = $this->loadModel('productplan')->getPairs(array_keys($products));
        $this->config->bug->search['module'] = 'importBug';
        $this->config->bug->search['params']['confirmed']['values'] = array('' => '') + $this->lang->bug->confirmedList;
        $this->config->bug->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($executionID, $viewType = 'bug', $startModuleID = 0);
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
        $this->view->execution        = $this->execution->getByID($executionID);
        $this->view->executionID      = $executionID;
        $this->view->requiredFields = explode(',', $this->config->task->create->requiredFields);
        $this->display();
    }

    /**
     * Browse stories of a execution.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story($executionID = 0, $orderBy = 'order_desc', $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('datatable');
        $this->app->loadLang('testcase');

        $type      = strtolower($type);
        $param     = $param;
        $productID = 0;
        setcookie('storyPreExecutionID', $executionID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
        if($this->cookie->storyPreExecutionID != $executionID)
        {
            $_COOKIE['storyModuleParam'] = $_COOKIE['storyProductParam'] = $_COOKIE['storyBranchParam'] = 0;
            setcookie('storyModuleParam',  0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', 0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  0, 0, $this->config->webRoot, '', false, true);
        }
        if($type == 'bymodule')
        {
            $_COOKIE['storyModuleParam']  = $param;
            $_COOKIE['storyProductParam'] = 0;
            $_COOKIE['storyBranchParam']  = 0;
            setcookie('storyModuleParam', $param, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', 0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyBranchParam',  0, 0, $this->config->webRoot, '', false, true);
        }
        elseif($type == 'byproduct')
        {
            $productID = $param;
            $_COOKIE['storyModuleParam']  = 0;
            $_COOKIE['storyProductParam'] = $param;
            $_COOKIE['storyBranchParam']  = 0;
            setcookie('storyModuleParam',  0, 0, $this->config->webRoot, '', false, true);
            setcookie('storyProductParam', $param, 0, $this->config->webRoot, '', false, true);
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
            $this->session->set('executionStoryBrowseType', $type);
        }

        /* Save session. */
        $this->app->session->set('storyList', $this->app->getURI(true), 'execution');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionStoryOrder ? $this->cookie->executionStoryOrder : 'pri';
        setcookie('executionStoryOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $queryID     = ($type == 'bysearch') ? $param : 0;
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $stories = $this->story->getExecutionStories($executionID, 0, 0, $sort, $type, $param, 'story', '', $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        $users = $this->user->getPairs('noletter');

        /* Build the search form. */
        $modules = array();
        $executionModules = $this->loadModel('tree')->getTaskTreeModules($executionID, true);
        $products = $this->execution->getProducts($executionID);
        foreach($products as $product)
        {
            $productModules = $this->tree->getOptionMenu($product->id);
            foreach($productModules as $moduleID => $moduleName)
            {
                if($moduleID and !isset($executionModules[$moduleID])) continue;
                $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
            }
        }
        $actionURL    = $this->createLink('execution', 'story', "executionID=$executionID&orderBy=$orderBy&type=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'executionStory');

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->story;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->story;

        /* Count T B C */
        $storyIdList = array_keys($stories);;
        $storyTasks  = $this->loadModel('task')->getStoryTaskCounts($storyIdList, $executionID);
        $storyBugs   = $this->loadModel('bug')->getStoryBugCounts($storyIdList, $executionID);
        $storyCases  = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        $plans    = $this->execution->getPlans($products);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        if($this->cookie->storyModuleParam)  $this->view->module  = $this->loadModel('tree')->getById($this->cookie->storyModuleParam);
        if($this->cookie->storyProductParam) $this->view->product = $this->loadModel('product')->getById($this->cookie->storyProductParam);
        if($this->cookie->storyBranchParam)
        {
            $branchID = $this->cookie->storyBranchParam;
            if(strpos($branchID, ',') !== false) list($productID, $branchID) = explode(',', $branchID);
            $this->view->branch  = $this->loadModel('branch')->getById($branchID, $productID);
        }

        /* Get execution's product. */
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);

        if(empty($productID)) $productID = key($productPairs);
        $showModule  = !empty($this->config->datatable->executionStory->showModule) ? $this->config->datatable->executionStory->showModule : '';
        $modulePairs = $showModule ? $this->tree->getModulePairs($type == 'byproduct' ? $param : 0, 'story', $showModule) : array();

        /* Assign. */
        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->productID    = $productID;
        $this->view->execution    = $execution;
        $this->view->stories      = $stories;
        $this->view->allPlans     = $allPlans;
        $this->view->summary      = $this->product->summary($stories);
        $this->view->orderBy      = $orderBy;
        $this->view->type         = $this->session->executionStoryBrowseType;
        $this->view->param        = $param;
        $this->view->moduleTree   = $this->loadModel('tree')->getProjectStoryTreeMenu($executionID, $startModuleID = 0, array('treeModel', 'createStoryLink'));
        $this->view->modulePairs  = $modulePairs;
        $this->view->tabID        = 'story';
        $this->view->storyTasks   = $storyTasks;
        $this->view->storyBugs    = $storyBugs;
        $this->view->storyCases   = $storyCases;
        $this->view->users        = $users;
        $this->view->pager        = $pager;
        $this->view->setModule    = true;
        $this->view->branchGroups = $branchGroups;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Execution qa dashboard.
     *
     * @param  int $executionID
     * @access public
     * @return void
     */
    public function qa($executionID = 0)
    {
        $this->commonAction($executionID);
        $this->view->title = $this->lang->execution->qa;
        $this->display();
    }

    /**
     * Browse bugs of a execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
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
    public function bug($executionID = 0, $productID = 0, $orderBy = 'status,id_desc', $build = 0, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');
        $this->loadModel('product');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true), 'execution');

        $type        = strtolower($type);
        $queryID     = ($type == 'bysearch') ? (int)$param : 0;
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        $products    = $this->execution->getProducts($execution->id);
        $branchID    = isset($products[$productID]) ? $products[$productID]->branch : 0;

        $productPairs = array('0' => $this->lang->product->all);
        foreach($products as $product) $productPairs[$product->id] = $product->name;
        $this->lang->modulePageNav = $this->product->select($productPairs, $productID, 'execution', 'bug', '', $branchID, 0, '', false);

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->bug;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->bug;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = $this->loadModel('common')->appendOrder($orderBy);
        $bugs  = $this->bug->getExecutionBugs($executionID, $productID, $build, $type, $param, $sort, '', $pager);
        $bugs  = $this->bug->checkDelayedBugs($bugs);
        $users = $this->user->getPairs('noletter');

        /* team member pairs. */
        $memberPairs = array();
        $memberPairs[] = "";
        foreach($this->view->teamMembers as $key => $member)
        {
            $memberPairs[$key] = $member->realname;
        }

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'bug', "executionID=$executionID&productID=$productID&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->execution->buildBugSearchForm($products, $queryID, $actionURL);

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
     * Execution case list.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase($executionID = 0, $type = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testcase');
        $this->loadModel('testtask');
        $this->commonAction($executionID);
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'execution');
        $this->session->set('bugList',  $uri, 'execution');

        $products  = $this->execution->getProducts($executionID);
        $productID = key($products);    // Get the first product for creating testcase.

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $cases = $this->loadModel('testcase')->getExecutionCases($executionID, $orderBy, $pager, $type);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->testcase->appendData($cases, 'case');

        $this->view->title       = $this->lang->execution->testcase;
        $this->view->executionID = $executionID;
        $this->view->productID   = $productID;
        $this->view->cases       = $cases;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->type        = $type;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->execution   = $this->execution->getByID($executionID);

        $this->display();
    }

    /**
     * List of test reports for the execution.
     *
     * @param  int    $executionID
     * @param  string $objectType   project|execution|product
     * @param  string $extra
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testreport($executionID = 0, $objectType = 'execution', $extra = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);
        echo $this->fetch('testreport', 'browse', "objectID=$executionID&objectType=$objectType&extra=$extra&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Browse builds of a execution.
     *
     * @param  int    $executionID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @access public
     * @return void
     */
    public function build($executionID = 0, $type = 'all', $param = 0)
    {
        /* Load module and set session. */
        $this->loadModel('testtask');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Get products' list. */
        $products = $this->execution->getProducts($executionID, false);
        $products = array('' => '') + $products;

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('execution', 'build', "executionID=$executionID&type=bysearch&queryID=myQueryID");
        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'execution');

        /* Get builds. */
        if($type == 'bysearch')
        {
            $builds = $this->loadModel('build')->getExecutionBuildsBySearch((int)$executionID, $queryID);
        }
        else
        {
            $builds = $this->loadModel('build')->getExecutionBuilds((int)$executionID, $type, $param);
        }

        /* Set execution builds. */
        $executionBuilds = array();
        $productList     = $this->execution->getProducts($executionID);
        if(!empty($builds))
        {
            foreach($builds as $build)
            {
                /* If product is normal, unset branch name. */
                if(isset($productList[$build->product]) and $productList[$build->product]->type == 'normal') $build->branchName = '';
                $executionBuilds[$build->product][] = $build;
            }
        }

        /* Header and position. */
        $this->view->title      = $execution->name . $this->lang->colon . $this->lang->execution->build;
        $this->view->position[] = html::a(inlink('browse', "executionID=$executionID"), $execution->name);
        $this->view->position[] = $this->lang->execution->build;

        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->buildsTotal   = count($builds);
        $this->view->executionBuilds = $executionBuilds;
        $this->view->executionID     = $executionID;
        $this->view->product       = $type == 'product' ? $param : 'all';
        $this->view->products      = $products;
        $this->view->type          = $type;

        $this->display();
    }

    /**
     * Browse test tasks of execution.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask($executionID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testtask');
        $this->app->loadLang('testreport');

        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'execution');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $productTasks = array();

        $tasks = $this->testtask->getExecutionTasks($executionID, $orderBy, $pager);
        foreach($tasks as $key => $task) $productTasks[$task->product][] = $task;

        $this->view->title         = $this->executions[$executionID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]    = html::a($this->createLink('execution', 'testtask', "executionID=$executionID"), $this->executions[$executionID]);
        $this->view->position[]    = $this->lang->testtask->common;
        $this->view->execution     = $execution;
        $this->view->executionID   = $executionID;
        $this->view->executionName = $this->executions[$executionID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->tasks         = $productTasks;
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->products      = $this->loadModel('product')->getPairs('', 0);
        $this->view->canBeChanged  = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Browse burndown chart of a execution.
     *
     * @param  int       $executionID
     * @param  string    $type
     * @param  int       $interval
     * @access public
     * @return void
     */
    public function burn($executionID = 0, $type = 'noweekend', $interval = 0, $burnBy = 'left')
    {
        $this->loadModel('report');
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        $burnBy    = $this->cookie->burnBy ? $this->cookie->burnBy : $burnBy;

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->burn;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->burn;

        /* Get date list. */
        $executionInfo = $this->execution->getByID($executionID);
        list($dateList, $interval) = $this->execution->getDateList($executionInfo->begin, $executionInfo->end, $type, $interval, 'Y-m-d');
        $chartData = $this->execution->buildBurnData($executionID, $dateList, $type, $burnBy);

        $dayList = array_fill(1, floor((int)$execution->days / $this->config->execution->maxBurnDay) + 5, '');
        foreach($dayList as $key => $val) $dayList[$key] = $this->lang->execution->interval . ($key + 1) . $this->lang->day;

        /* Assign. */
        $this->view->title         = $title;
        $this->view->position      = $position;
        $this->view->tabID         = 'burn';
        $this->view->burnBy        = $burnBy;
        $this->view->executionID   = $executionID;
        $this->view->executionName = $execution->name;
        $this->view->type          = $type;
        $this->view->interval      = $interval;
        $this->view->chartData     = $chartData;
        $this->view->dayList       = array('full' => $this->lang->execution->interval . '1' . $this->lang->day) + $dayList;

        unset($this->lang->TRActions);
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
        $this->view->burns = $this->execution->computeBurn();
        if($reload == 'yes') die(js::reload('parent'));
        $this->display();
    }

    /**
     * Fix burn for first date.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function fixFirst($executionID)
    {
        if($_POST)
        {
            $this->execution->fixFirst($executionID);
            die(js::reload('parent.parent'));
        }

        $execution = $this->execution->getById($executionID);

        $this->view->firstBurn = $this->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('date')->eq($execution->begin)->fetch();
        $this->view->execution   = $execution;
        $this->display();
    }

    /**
     * Browse team of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function team($executionID = 0)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        $title      = $execution->name . $this->lang->colon . $this->lang->execution->team;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->team;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->deptUsers    = $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id');
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Create a execution.
     *
     * @param string $projectID
     * @param string $executionID
     * @param string $copyExecutionID
     * @param int    $planID
     * @param string $confirm
     * @param string $productID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function create($projectID = '', $executionID = '', $copyExecutionID = '', $planID = 0, $confirm = 'no', $productID = 0, $extra = '')
    {
        /* Set menu. */
        if($this->app->tab == 'project')
        {
            if(!empty($copyExecutionID)) $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($copyExecutionID)->fetch('project');

            $projectID = $this->project->saveState($projectID, $this->project->getPairsByProgram());
            $this->project->setMenu($projectID);
        }
        elseif($this->app->tab == 'execution')
        {
            $selectedExecutionID = $executionID;
            if($this->config->systemMode == 'new') $selectedExecutionID = key($this->executions);
            $this->execution->setMenu($selectedExecutionID);
        }
        elseif($this->app->tab == 'doc')
        {
            unset($this->lang->doc->menu->execution['subMenu']);
        }

        $project = $this->project->getByID($projectID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->app->loadLang('program');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        if($executionID)
        {
            $execution = $this->execution->getById($executionID);
            if(!empty($planID) and $execution->lifetime != 'ops')
            {
                if($confirm == 'yes')
                {
                    $this->execution->linkStories($executionID);
                }
                else
                {
                    die(js::confirm($this->lang->execution->importPlanStory, inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=$planID&confirm=yes"), inlink('create', "projectID=$projectID&executionID=$executionID")));
                }
            }

            $this->view->title       = $this->lang->execution->tips;
            $this->view->tips        = $this->fetch('execution', 'tips', "executionID=$executionID");
            $this->view->executionID = $executionID;
            $this->view->projectID   = $projectID;
            $this->display();
            exit;
        }

        $name         = '';
        $code         = '';
        $team         = '';
        $products     = array();
        $whitelist    = '';
        $acl          = 'private';
        $plan         = new stdClass();
        $productPlan  = array();
        $productPlans = array();
        if($copyExecutionID)
        {
            $copyExecution = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($copyExecutionID)->fetch();
            $name        = $copyExecution->name;
            $code        = $copyExecution->code;
            $team        = $copyExecution->team;
            $acl         = $copyExecution->acl;
            $whitelist   = $copyExecution->whitelist;
            $projectID   = $copyExecution->project;
            $products    = $this->execution->getProducts($copyExecutionID);
            foreach($products as $product)
            {
                $productPlans[$product->id] = $this->loadModel('productplan')->getPairs($product->id);
            }
        }

        if(!empty($planID))
        {
            $plan     = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->eq($planID)->fetch();
            $products = $this->dao->select('t1.id, t1.name, t1.type, t2.branch')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
                ->where('t1.id')->eq($plan->product)
                ->fetchAll('id');

            $productPlan = $this->loadModel('productplan')->getPairs($plan->product, 0, 'unexpired');
        }

        if(!empty($_POST))
        {
            $executionID = $this->execution->create($copyExecutionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create($this->objectType, $executionID, 'opened', '', join(',', $_POST['products']));

            $this->executeHooks($executionID);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $executionID));

            if($this->app->tab == 'doc')
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'objectLibs', "type=execution")));
            }

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
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=$planID&confirm=no")));
            }
            else
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID&executionID=$executionID")));
            }
        }

        if(!empty($project->model) and $project->model == 'waterfall')
        {
            $this->lang->execution->type = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->execution->type);
        }

        $this->view->title           = (($this->app->tab == 'execution') and ($this->config->systemMode == 'new')) ? $this->lang->execution->createExec : $this->lang->execution->create;
        $this->view->position[]      = $this->view->title;
        $this->view->gobackLink      = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'all') : '';
        $this->view->executions      = array('' => '') + $this->execution->getList($projectID);
        $this->view->groups          = $this->loadModel('group')->getPairs();
        $this->view->allProducts     = array(0 => '') + $this->loadModel('product')->getProductPairsByProject($projectID, 'noclosed');
        $this->view->acl             = $acl;
        $this->view->plan            = $plan;
        $this->view->name            = $name;
        $this->view->code            = $code;
        $this->view->team            = $team;
        $this->view->teams           = array(0 => '') + $this->execution->getTeamPairsByProject((int)$projectID);
        $this->view->allProjects     = array(0 => '') + $this->project->getPairsByModel();
        $this->view->executionID     = $executionID;
        $this->view->productID       = $productID;
        $this->view->projectID       = $projectID;
        $this->view->isStage         = (isset($project->model) and $project->model == 'waterfall') ? true : false;
        $this->view->products        = $products;
        $this->view->productPlan     = array(0 => '') + $productPlan;
        $this->view->productPlans    = array(0 => '') + $productPlans;
        $this->view->whitelist       = $whitelist;
        $this->view->copyExecutionID = $copyExecutionID;
        $this->view->branchGroups    = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->users           = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->from            = $this->app->tab;
        $this->display();
    }

    /**
     * Edit a execution.
     *
     * @param  int    $executionID
     * @param  string $action
     * @param  string $extra
     *
     * @access public
     * @return void
     */
    public function edit($executionID, $action = 'edit', $extra = '')
    {
        /* Load language files and get browseExecutionLink. */
        $this->app->loadLang('program');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $browseExecutionLink = $this->createLink('execution', 'browse', "executionID=$executionID");

        if(!empty($_POST))
        {
            $oldPlans    = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->andWhere('plan')->ne(0)->fetchPairs('plan');
            $oldProducts = $this->execution->getProducts($executionID);
            $changes     = $this->execution->update($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($action == 'undelete')
            {
                $this->loadModel('action');
                $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(0)->where('id')->eq($executionID)->exec();
                $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($extra)->exec();
                $this->action->create($this->objectType, $executionID, 'undeleted');
            }

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->execution->getProducts($executionID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            $products     = $diffProducts ? join(',', $newProducts) : '';

            if($changes or $diffProducts)
            {
                $actionID = $this->loadModel('action')->create($this->objectType, $executionID, 'edited', '', $products);
                $this->action->logHistory($actionID, $changes);
            }

            /* Link the plan stories. */
            $newPlans   = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->andWhere('plan')->ne(0)->fetchPairs('plan');
            $diffResult = array_diff($oldPlans, $newPlans);
            $diffResult = array_merge($diffResult, array_diff($newPlans, $oldPlans));
            if(!empty($newPlans) and !empty($diffResult))
            {
                $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
                $this->loadModel('productplan')->linkProject($executionID, $_POST['plans']);
                $this->productplan->linkProject($projectID, $_POST['plans']);
            }

            $this->executeHooks($executionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "executionID=$executionID")));
        }

        /* Set menu. */
        $this->execution->setMenu($executionID);

        $executions = array('' => '') + $this->executions;
        $execution  = $this->execution->getById($executionID);
        $managers   = $this->execution->getDefaultManagers($executionID);


        /* Remove current execution from the executions. */
        unset($executions[$executionID]);

        $title      = $this->lang->execution->edit . $this->lang->colon . $execution->name;
        $position[] = html::a($browseExecutionLink, $execution->name);
        $position[] = $this->lang->execution->edit;

        $allProducts       = array(0 => '');
        $executionProsucts = $this->execution->getProducts($execution->project, true, 'noclosed');
        foreach($executionProsucts as $product) $allProducts[$product->id] = $product->name;

        $linkedProducts = $this->execution->getProducts($execution->id);
        $linkedBranches = array();

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $executionStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->fetchAll('story');
            if(!empty($executionStories)) array_push($unmodifiableProducts, $productID);
        }

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

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst', $execution->PO, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst',  $execution->PM, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst',  $execution->QD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', $execution->RD, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $this->view->title                = $title;
        $this->view->position             = $position;
        $this->view->executions           = $executions;
        $this->view->execution            = $execution;
        $this->view->poUsers              = $poUsers;
        $this->view->pmUsers              = $pmUsers;
        $this->view->qdUsers              = $qdUsers;
        $this->view->rdUsers              = $rdUsers;
        $this->view->users                = $this->user->getPairs('nodeleted|noclosed');
        $this->view->allProjects          = $this->project->getPairsByModel();
        $this->view->groups               = $this->loadModel('group')->getPairs();
        $this->view->allProducts          = $allProducts;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->productPlans         = $productPlans;
        $this->view->branchGroups         = $this->loadModel('branch')->getByProducts(array_keys($linkedProducts), '', $linkedBranches);
        $this->display();
    }

    /**
     * Batch edit.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit($executionID = 0)
    {
        $this->app->loadLang('stage');

        if($this->post->names)
        {
            $allChanges = $this->execution->batchUpdate();
            if(!empty($allChanges))
            {
                foreach($allChanges as $executionID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create($this->objectType, $executionID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            die(js::locate($this->session->executionList, 'parent'));
        }

        if($this->app->tab == 'project')
        {
            $this->project->setMenu($this->session->project);
            $this->view->project = $this->project->getById($this->session->project);
        }

        $executionIDList = $this->post->executionIDList ? $this->post->executionIDList : die(js::locate($this->session->executionList, 'parent'));
        $executions      = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIDList)->fetchAll('id');

        $appendPoUsers = $appendPmUsers = $appendQdUsers = $appendRdUsers = array();
        foreach($executions as $execution)
        {
            $appendPoUsers[$execution->PO] = $execution->PO;
            $appendPmUsers[$execution->PM] = $execution->PM;
            $appendQdUsers[$execution->QD] = $execution->QD;
            $appendRdUsers[$execution->RD] = $execution->RD;
        }

        /* Set custom. */
        foreach(explode(',', $this->config->execution->customBatchEditFields) as $field) $customFields[$field] = $this->lang->execution->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->execution->custom->batchEditFields;

        $this->loadModel('user');
        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst', $appendPmUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst',  $appendPoUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst',  $appendQdUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', $appendRdUsers, $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $this->view->title           = $this->lang->execution->batchEdit;
        $this->view->position[]      = $this->lang->execution->batchEdit;
        $this->view->executionIDList = $executionIDList;
        $this->view->executions      = $executions;
        $this->view->allProjects     = $this->project->getPairsByModel();
        $this->view->pmUsers         = $pmUsers;
        $this->view->poUsers         = $poUsers;
        $this->view->qdUsers         = $qdUsers;
        $this->view->rdUsers         = $rdUsers;
        $this->view->from            = $this->app->tab;
        $this->display();
    }

    /**
     * Start execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function start($executionID)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->start($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->start;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $this->view->execution->name);
        $this->view->position[] = $this->lang->execution->start;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * Delay execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function putoff($executionID)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->putoff($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Delayed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->putoff;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $this->view->execution->name);
        $this->view->position[] = $this->lang->execution->putoff;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * Suspend execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function suspend($executionID)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->suspend($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->suspend;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $this->view->execution->name);
        $this->view->position[] = $this->lang->execution->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * Activate execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function activate($executionID)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->activate($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            die(js::reload('parent.parent'));
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $execution->begin);
        $newEnd   = date('Y-m-d', strtotime($execution->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->activate;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $this->view->execution->name);
        $this->view->position[] = $this->lang->execution->activate;
        $this->view->execution    = $execution;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * Close execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function close($executionID)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->close($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->close;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $this->view->execution->name);
        $this->view->position[] = $this->lang->execution->close;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * View a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function view($executionID)
    {
        $executionID = $this->execution->saveState((int)$executionID, $this->executions);
        $execution   = $this->execution->getById($executionID, true);
        if(empty($execution) || strpos('stage,sprint', $execution->type) === false) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->app->loadLang('program');

        /* Execution not found to prevent searching for .*/
        if(!isset($this->executions[$execution->id])) $this->executions = $this->execution->getPairs($execution->project, 'all', 'nocode');

        $products = $this->execution->getProducts($execution->id);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if($product->branch) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        $this->app->loadLang('bug');

        list($dateList, $interval) = $this->execution->getDateList($execution->begin, $execution->end, 'noweekend', 0, 'Y-m-d');
        $chartData = $this->execution->buildBurnData($executionID, $dateList, 'noweekend');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->executeHooks($executionID);

        $this->view->title      = $this->lang->execution->view;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[] = $this->view->title;

        $this->view->execution    = $execution;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->planGroups   = $this->execution->getPlans($products);
        $this->view->actions      = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', 'all', $executionID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->execution->getTeamMembers($executionID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('execution', $executionID);
        $this->view->statData     = $this->execution->statRelatedData($executionID);
        $this->view->chartData    = $chartData;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Kanban.
     *
     * @param  int    $executionID
     * @param  string $browseType story|bug|task|all
     * @param  string $orderBy
     * @param  string $groupBy
     * @access public
     * @return void
     */
    public function kanban($executionID, $browseType = '', $orderBy = 'order_asc', $groupBy = '')
    {
        if(empty($browseType)) $browseType = $this->session->kanbanType ? $this->session->kanbanType : 'all';
        if(empty($groupBy) and $browseType != 'all') $groupBy = $this->session->{'kanbanGroupBy' . $browseType} ? $this->session->{'kanbanGroupBy' . $browseType} : 'default';
        if(empty($groupBy) and $browseType == 'all') $groupBy = 'default';

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList', $uri, 'execution');
        $this->app->session->set('bugList',  $uri, 'qa');
        $this->app->session->set('kanbanType', $browseType, 'execution');
        $this->app->session->set('kanbanGroupBy' . $browseType, $groupBy, 'execution');

        /* Load language. */
        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');

        /* Compatibility IE8. */
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) header("X-UA-Compatible: IE=EmulateIE7");

        $kanbanGroup = $this->loadModel('kanban')->getExecutionKanban($executionID, $browseType, $groupBy);
        if(empty($kanbanGroup))
        {
            $this->kanban->createLanes($executionID, $browseType, $groupBy);
            $kanbanGroup = $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy);
        }

        $this->execution->setMenu($executionID);
        $execution = $this->loadModel('execution')->getById($executionID);

        /* Determines whether an object is editable. */
        $canBeChanged = common::canModify('execution', $execution);

        /* Get execution's product. */
        $productID = 0;
        $products  = $this->execution->getProducts($executionID);
        if($products) $productID = key($products);

        $plans    = $this->execution->getPlans($products);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $userList    = array();
        $avatarPairs = $this->dao->select('account, avatar')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs();
        foreach($avatarPairs as $account => $avatar)
        {
            if(!$avatar) continue;
            $userList[$account]['avatar'] = $avatar;
        }

        $this->view->title         = $this->lang->execution->kanban;
        $this->view->position[]    = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[]    = $this->lang->execution->kanban;
        $this->view->realnames     = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyOrder    = $orderBy;
        $this->view->orderBy       = 'id_asc';
        $this->view->executionID   = $executionID;
        $this->view->productID     = $productID;
        $this->view->allPlans      = $allPlans;
        $this->view->browseType    = $browseType;
        $this->view->kanbanGroup   = $kanbanGroup;
        $this->view->execution     = $execution;
        $this->view->groupBy       = $groupBy;
        $this->view->canBeChanged  = $canBeChanged;
        $this->view->userList      = $userList;

        $this->display();
    }

    /**
     * Execution kanban.
     *
     * @access public
     * @return void
     */
    public function executionKanban()
    {
        $this->loadModel('project');
        $projects   = $this->project->getPairsByProgram(0, 'noclosed');
        $executions = $this->project->getStats(0, 'all', 0, 0, 30, 'id_desc');

        $teams = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('root')->in($this->app->user->view->sprints)
            ->andWhere('type')->eq('execution')
            ->fetchGroup('root', 'account');

        $projectCount = 0;
        $statusCount  = array();
        $myExecutions = array();
        $kanbanGroup  = array();
        foreach(array_keys($projects) as $projectID)
        {
            foreach(array_keys($this->lang->execution->statusList) as $status)
            {
                if(!isset($statusCount[$status])) $statusCount[$status] = 0;

                foreach($executions as $execution)
                {
                    if($execution->status == $status)
                    {
                        if(isset($teams[$execution->id][$this->app->user->account])) $myExecutions[$status][$execution->id] = $execution;
                        if($execution->project == $projectID) $kanbanGroup[$projectID][$status][$execution->id] = $execution;
                    }
                }

                $statusCount[$status] += isset($kanbanGroup[$projectID][$status]) ? count($kanbanGroup[$projectID][$status]) : 0;

                /* Max 2 closed executions. */
                if($status == 'closed')
                {
                    if(isset($myExecutions[$status]) and count($myExecutions[$status]) > 2)
                    {
                        foreach($myExecutions[$status] as $executionID => $execution)
                        {
                            unset($myExecutions[$status][$executionID]);
                            $myExecutions[$status][$execution->closedDate] = $execution;
                        }

                        krsort($myExecutions[$status]);
                        $myExecutions[$status] = array_slice($myExecutions[$status], 0, 2, true);
                    }

                    if(isset($kanbanGroup[$projectID][$status]) and count($kanbanGroup[$projectID][$status]) > 2)
                    {
                        foreach($kanbanGroup[$projectID][$status] as $executionID => $execution)
                        {
                            unset($kanbanGroup[$projectID][$status][$executionID]);
                            $kanbanGroup[$projectID][$status][$execution->closedDate] = $execution;
                        }

                        krsort($kanbanGroup[$projectID][$status]);
                        $kanbanGroup[$projectID][$status] = array_slice($kanbanGroup[$projectID][$status], 0, 2);
                    }
                }
            }

            if(empty($kanbanGroup[$projectID])) continue;
            $projectCount++;
        }

        $this->view->title        = $this->lang->execution->executionKanban;
        $this->view->kanbanGroup  = empty($myExecutions) ? $kanbanGroup : array($myExecutions) + $kanbanGroup;
        $this->view->projects     = $projects;
        $this->view->projectCount = $projectCount;
        $this->view->statusCount  = $statusCount;

        $this->display();
    }

    /**
     * Tree view.
     * Product
     *
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return void
     */
    public function tree($executionID, $type = 'task')
    {
        $this->execution->setMenu($executionID);

        $execution = $this->loadModel('execution')->getById($executionID);
        $tree      = $this->execution->getTree($executionID);

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList', $uri, 'execution');
        $this->app->session->set('storyList', $uri, 'execution');
        $this->app->session->set('executionList', $uri, 'execution');
        $this->app->session->set('caseList', $uri, 'qa');
        $this->app->session->set('bugList', $uri, 'qa');

        if($type === 'json') die(helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS));

        $this->view->title      = $this->lang->execution->tree;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[]  = $this->lang->execution->tree;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->level       = $type;
        $this->view->tree        = $this->execution->printTree($tree);
        $this->display();
    }

    /**
     * Print kanban.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function printKanban($executionID, $orderBy = 'id_asc')
    {
        $this->view->title = $this->lang->execution->printKanban;
        $contents = array('story', 'wait', 'doing', 'done', 'cancel');

        if($_POST)
        {
            $stories    = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, $orderBy);
            $storySpecs = $this->story->getStorySpecs(array_keys($stories));

            $order = 1;
            foreach($stories as $story) $story->order = $order++;

            $kanbanTasks = $this->execution->getKanbanTasks($executionID, "id");
            $kanbanBugs  = $this->loadModel('bug')->getExecutionBugs($executionID);

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
                $prevKanbans = $this->execution->getPrevKanban($executionID);
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

            /* Close the page when there is no data. */
            $hasData = false;
            foreach($datas as $data)
            {
                if(!empty($data)) $hasData = true;
            }
            if(!$hasData) die(js::alert($this->lang->execution->noPrintData) . js::close());

            $this->execution->saveKanbanData($executionID, $originalDatas);

            $hasBurn = $this->post->content == 'all';
            if($hasBurn)
            {
                /* Get date list. */
                $executionInfo    = $this->execution->getByID($executionID);
                list($dateList) = $this->execution->getDateList($executionInfo->begin, $executionInfo->end, 'noweekend');
                $chartData      = $this->execution->buildBurnData($executionID, $dateList, 'noweekend');
            }

            $this->view->hasBurn    = $hasBurn;
            $this->view->datas      = $datas;
            $this->view->chartData  = isset($chartData) ? $chartData : array();
            $this->view->storySpecs = $storySpecs;
            $this->view->realnames  = $this->loadModel('user')->getRealNameAndEmails($users);
            $this->view->executionID  = $executionID;

            die($this->display());

        }

        $this->execution->setMenu($executionID);
        $execution = $this->execution->getById($executionID);

        $this->view->executionID = $executionID;
        $this->display();
    }

    /**
     * Story kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storyKanban($executionID)
    {
        /* Compatibility IE8*/
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) header("X-UA-Compatible: IE=EmulateIE7");

        $this->execution->setMenu($executionID);
        $execution = $this->loadModel('execution')->getById($executionID);
        $stories = $this->loadModel('story')->getExecutionStories($executionID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        /* Get execution's product. */
        $productID = 0;
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);
        if($productPairs) $productID = key($productPairs);

        $this->view->title        = $this->lang->execution->storyKanban;
        $this->view->position[]   = html::a($this->createLink('execution', 'story', "executionID=$executionID"), $execution->name);
        $this->view->position[]   = $this->lang->execution->storyKanban;
        $this->view->stories      = $this->story->getKanbanGroupData($stories);
        $this->view->realnames    = $this->loadModel('user')->getPairs('noletter');
        $this->view->executionID  = $executionID;
        $this->view->execution    = $execution;
        $this->view->productID    = $productID;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Delete a execution.
     *
     * @param  int    $executionID
     * @param  string $confirm   yes|no
     * @access public
     * @return void
     */
    public function delete($executionID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            /* Get the number of unfinished tasks and unresolved bugs. */
            $unfinishedTasks = $this->dao->select('COUNT(id) AS count')->from(TABLE_TASK)
                ->where('execution')->eq($executionID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->in('wait,doing,pause')
                ->fetch();

            $unresolvedBugs = $this->dao->select('COUNT(id) AS count')->from(TABLE_BUG)
                ->where('execution')->eq($executionID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->eq('active')
                ->fetch();

            /* Set prompt information. */
            $tips = '';
            if($unfinishedTasks->count) $tips  = sprintf($this->lang->execution->unfinishedTask, $unfinishedTasks->count);
            if($unresolvedBugs->count)  $tips .= sprintf($this->lang->execution->unresolvedBug,  $unresolvedBugs->count);
            if($tips)                   $tips  = $this->lang->execution->unfinishedExecution . $tips;

            $type = $this->dao->select('type')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('type');
            if($type == 'stage')
            {
                if($tips) $tips = str_replace($this->lang->executionCommon, $this->lang->project->stage, $tips);
                $this->lang->execution->confirmDelete = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->execution->confirmDelete);
            }

            echo js::confirm($tips . sprintf($this->lang->execution->confirmDelete, $this->executions[$executionID]), $this->createLink('execution', 'delete', "executionID=$executionID&confirm=yes"));
            exit;
        }
        else
        {
            /* Delete execution. */
            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->eq($executionID)->exec();
            $this->loadModel('action')->create('execution', $executionID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
            $this->execution->updateUserView($executionID);

            $this->session->set('execution', '');
            $this->executeHooks($executionID);

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            die(js::reload('parent'));
        }
    }

    /**
     * Manage products.
     *
     * @param  int    $executionID
     * @param  from   $from
     * @access public
     * @return void
     */
    public function manageProducts($executionID, $from = '')
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        $browseExecutionLink = $this->createLink('execution', 'browse', "executionID=$executionID");

        $this->loadModel('product');
        $execution  = $this->execution->getById($executionID);

        if(!empty($_POST))
        {
            /* Get executionType and determine whether a product is linked with the stage. */
            $executionType = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('type');
            if($executionType == 'stage')
            {
                if(!isset($_POST['products'])) die(js::alert($this->lang->execution->noLinkProduct) . js::locate($this->createLink('execution', 'manageProducts', "executionID=$executionID&from=$from")));

                if(count($_POST['products']) > 1) die(js::alert($this->lang->execution->oneProduct) . js::locate($this->createLink('execution', 'manageProducts', "executionID=$executionID&from=$from")));
            }

            $oldProducts = $this->execution->getProducts($executionID);

            if($from == 'buildCreate' && $this->session->buildCreate) $browseExecutionLink = $this->session->buildCreate;

            $this->execution->updateProducts($executionID);
            if(dao::isError()) die(js::error(dao::getError()));

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->execution->getProducts($executionID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create($this->objectType, $executionID, 'Managed', '', !empty($_POST['products']) ? join(',', $_POST['products']) : '');

            if(isonlybody())
            {
                unset($_GET['onlybody']);
                die(js::locate($this->createLink('build', 'create', "executionID=$executionID&productID=0&projectID=$execution->project"), 'parent'));
            }
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);

        /* Title and position. */
        $title      = $this->lang->execution->manageProducts . $this->lang->colon . $execution->name;
        $position[] = html::a($browseExecutionLink, $execution->name);
        $position[] = $this->lang->execution->manageProducts;

        $allProducts     = $this->config->systemMode == 'classic' ? $this->product->getPairs('noclosed') : $this->product->getProductPairsByProject($execution->project);
        $linkedProducts  = $this->execution->getProducts($execution->id);
        $linkedBranches  = array();

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $executionStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('product')->eq($productID)->fetchAll('story');
            if(!empty($executionStories)) array_push($unmodifiableProducts, $productID);
        }

        // Merge allProducts and linkedProducts for closed product.
        foreach($linkedProducts as $product)
        {
            if(!isset($allProducts[$product->id])) $allProducts[$product->id] = $product->name;
            if(!empty($product->branch)) $linkedBranches[$product->branch] = $product->branch;
        }

        /* Assign. */
        $this->view->title                = $title;
        $this->view->position             = $position;
        $this->view->allProducts          = $allProducts;
        $this->view->execution              = $execution;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->branchGroups         = $this->loadModel('branch')->getByProducts(array_keys($allProducts), 'ignoreNormal', $linkedBranches);

        $this->display();
    }

    /**
     * Manage members of the execution.
     *
     * @param  int    $executionID
     * @param  int    $team2Import    the team to import.
     * @param  int    $dept
     * @access public
     * @return void
     */
    public function manageMembers($executionID = 0, $team2Import = 0, $dept = 0)
    {
        if(!empty($_POST))
        {
            $this->execution->manageMembers($executionID);
            $this->loadModel('action')->create('team', $executionID, 'managedTeam');
            die(js::locate($this->createLink('execution', 'team', "executionID=$executionID"), 'parent'));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $execution   = $this->execution->getById($executionID);
        $users     = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback', '', $this->config->maxCount);
        $roles     = $this->user->getUserRoles(array_keys($users));
        $deptUsers = empty($dept) ? array() : $this->dept->getDeptUserPairs($dept);

        $currentMembers = $this->execution->getTeamMembers($executionID);
        $members2Import = $this->execution->getMembers2Import($team2Import, array_keys($currentMembers));
        $teams2Import   = $this->loadModel('personnel')->getCopiedObjects($executionID, 'sprint');
        $teams2Import   = array('' => '') + $teams2Import;

        /* Append users for get users. */
        $appendUsers = array();
        foreach($currentMembers as $member) $appendUsers[$member->account] = $member->account;
        foreach($members2Import as $member) $appendUsers[$member->account] = $member->account;
        foreach($deptUsers as $deptAccount => $userName) $appendUsers[$deptAccount] = $deptAccount;

        $users = $this->user->getPairs('noclosed|nodeleted|devfirst|nofeedback', $appendUsers, $this->config->maxCount);
        $roles = $this->user->getUserRoles(array_keys($users));

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["accounts[]"] = $this->config->user->moreLink;

        $title      = $this->lang->execution->manageMembers . $this->lang->colon . $execution->name;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->manageMembers;

        $this->view->title          = $title;
        $this->view->position       = $position;
        $this->view->execution      = $execution;
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
     * @param  int    $executionID
     * @param  int    $userID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkMember($executionID, $userID, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->execution->confirmUnlinkMember, $this->inlink('unlinkMember', "executionID=$executionID&userID=$userID&confirm=yes")));

        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $this->execution->unlinkMember($executionID, $account);
        if(!dao::isError()) $this->loadModel('action')->create('team', $executionID, 'managedTeam');

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
            return $this->send($response);
        }
        die(js::locate($this->inlink('team', "executionID=$executionID"), 'parent'));
    }

    /**
     * Link stories to an execution.
     *
     * @param  int    $objectID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($objectID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->loadModel('story');
        $this->loadModel('product');

        /* Get projects, executions and products. */
        $object     = $this->project->getByID($objectID, $this->app->tab == 'project' ? 'project' : 'sprint,stage');
        $products   = $this->project->getProducts($objectID);
        $browseLink = $this->createLink($this->app->tab == 'project' ? 'projectstory' : 'execution', 'story', "objectID=$objectID");

        $this->session->set('storyList', $this->app->getURI(true), $this->app->tab); // Save session.

        /* Only execution can have no products. */
        if(empty($products))
        {
            echo js::alert($this->lang->execution->errorNoLinkedProducts);
            die(js::locate($this->createLink('execution', 'manageproducts', "executionID=$objectID")));
        }

        if(!empty($_POST))
        {
            $this->execution->linkStory($objectID);
            if($object->type != 'project' and $object->project != 0) $this->execution->linkStory($object->project);

            if(isonlybody()) die(js::reload('parent'));
            die(js::locate($browseLink));
        }

        if($object->type == 'project')
        {
            $this->project->setMenu($object->id);
        }
        else if($object->type == 'sprint' or $object->type == 'stage')
        {
            $this->execution->setMenu($object->id);
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
        $actionURL    = $this->createLink($this->app->rawModule, 'linkStory', "objectID=$objectID&browseType=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'linkStory');

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch('', 0, $queryID, 'id', $objectID);
        }
        else
        {
            $allStories = $this->story->getProductStories(array_keys($products), $branches, $moduleID = '0', $status = 'active', 'story', 'id_desc', $hasParent = false, '', $pager = null);
        }

        $linkedStories = $this->story->getExecutionStoryPairs($objectID);
        foreach($allStories as $id => $story)
        {
            if(isset($linkedStories[$story->id])) unset($allStories[$id]);
            if($story->parent < 0) unset($allStories[$id]);
        }

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($allStories);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $allStories = array_chunk($allStories, $pager->recPerPage);

        /* Assign. */
        $this->view->title      = $object->name . $this->lang->colon . $this->lang->execution->linkStory;
        $this->view->position[] = html::a($browseLink, $object->name);
        $this->view->position[] = $this->lang->execution->linkStory;

        $this->view->object       = $object;
        $this->view->products     = $products;
        $this->view->allStories   = empty($allStories) ? $allStories : $allStories[$pageID - 1];
        $this->view->pager        = $pager;
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
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function unlinkStory($executionID, $storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $tip = $this->app->rawModule == 'projectstory' ? $this->lang->execution->confirmUnlinkExecutionStory : $this->lang->execution->confirmUnlinkStory;
            die(js::confirm($tip, $this->createLink('execution', 'unlinkstory', "executionID=$executionID&storyID=$storyID&confirm=yes")));
        }
        else
        {
            $this->execution->unlinkStory($executionID, $storyID);

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
                return $this->send($response);
            }
            die(js::locate($this->app->session->storyList, 'parent'));
        }
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($executionID)
    {
        if(isset($_POST['storyIdList']))
        {
            $storyIdList = $this->post->storyIdList;
            $_POST       = array();

            $this->loadModel('gitlab');
            foreach($storyIdList as $storyID)
            {
                /* Delete related issue in gitlab. */
                $relation = $this->gitlab->getRelationByObject('story', $storyID);
                if(!empty($relation)) $this->gitlab->deleteIssue('story', $storyID, $relation->issueID);

                $this->execution->unlinkStory($executionID, $storyID);
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        die(js::locate($this->createLink('execution', 'story', "executionID=$executionID")));
    }

    /**
     * Execution dynamic.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction  next|pre
     * @access public
     * @return void
     */
    public function dynamic($executionID = 0, $type = 'today', $param = '', $recTotal = 0, $date = '', $direction = 'next')
    {
        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('reportList',      $uri, 'qa');

        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';
        $sort    = $this->loadModel('common')->appendOrder($orderBy);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id');
            if($user) $account = $user->account;
        }
        $period  = $type == 'account' ? 'all'  : $type;
        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($account, $period, $sort, $pager, 'all', 'all', $executionID, $date, $direction);

        /* The header and position. */
        $execution = $this->execution->getByID($executionID);
        $this->view->title      = $execution->name . $this->lang->colon . $this->lang->execution->dynamic;
        $this->view->position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[] = $this->lang->execution->dynamic;

        $this->view->userIdPairs  = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted|useid');
        $this->view->accountPairs = $this->loadModel('user')->getPairs('noletter|nodeleted');

        /* Assign. */
        $this->view->executionID = $executionID;
        $this->view->type        = $type;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->account     = $account;
        $this->view->param       = $param;
        $this->view->dateGroups  = $this->action->buildDateGroup($actions, $direction, $type);
        $this->view->direction   = $direction;
        $this->display();
    }

    /**
     * AJAX: get products of a execution in html select.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetProducts($executionID)
    {
        $products = $this->execution->getProducts($executionID, false);
        die(html::select('product', $products, '', 'class="form-control"'));
    }

    /**
     * AJAX: get team members of the execution.
     *
     * @param  int    $executionID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function ajaxGetMembers($executionID, $assignedTo = '')
    {
        $users = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
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
     * AJAX: get team members by projectID/executionID.
     *
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function ajaxGetTeamMembers($objectID)
    {
        $type = 'execution';
        if($this->config->systemMode == 'new')
        {
            $type = $this->dao->findById($objectID)->from(TABLE_PROJECT)->fetch('type');
            if($type != 'project') $type = 'execution';
        }

        $users   = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $members = $this->user->getTeamMemberPairs($objectID, $type);

        die(html::select('teamMembers[]', $users, array_keys($members), "class='form-control chosen' multiple"));
    }

    /**
     * When create a execution, help the user.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function tips($executionID)
    {
        $this->view->execution   = $this->execution->getById($executionID);
        $this->view->executionID = $executionID;
        $this->display('execution', 'tips');
    }

    /**
     * Drop menu page.
     *
     * @param  int    $executionID
     * @param  string $module
     * @param  string $method
     * @param  mix    $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($executionID, $module, $method, $extra)
    {
        $orderedExecutions = array();

        $projects = $this->loadModel('program')->getProjectList(0, 'all', 0, 'order_asc', null, 0, 0, true);
        $executionGroups = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('sprint,stage')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->config->systemMode == 'new')->andWhere('project')->in(array_keys($projects))->fi()
            ->orderBy('id_desc')
            ->fetchGroup('project', 'id');

        $teams = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('root')->in($this->app->user->view->sprints)
            ->andWhere('type')->eq('execution')
            ->fetchGroup('root', 'account');

        $projectPairs = array();
        if($this->config->systemMode == 'new')
        {
            foreach($projects as $project)
            {
                $executions = zget($executionGroups, $project->id, array());

                foreach($executions as $execution)
                {
                    if(isset($orderedExecutions[$execution->parent])) unset($orderedExecutions[$execution->parent]);
                    $execution->teams = zget($teams, $execution->id, array());
                    $orderedExecutions[$execution->id] = $execution;
                }

                $projectPairs[$project->id] = $project->name;
            }
        }
        else
        {
            foreach($executionGroups as $projectID => $executions)
            {
                foreach($executions as $execution)
                {
                    $execution->teams = zget($teams, $execution->id, array());
                    $orderedExecutions[$execution->id] = $execution;
                }
            }
        }

        $projectExecutions = array();
        foreach($orderedExecutions as $execution) $projectExecutions[$execution->project][] = $execution;

        $this->view->link        = $this->execution->getLink($module, $method, $extra);
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->view->executionID = $executionID;
        $this->view->extra       = $extra;
        $this->view->projects    = $projectPairs;
        $this->view->executions  = $projectExecutions;
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
        $idList   = explode(',', trim($this->post->executions, ','));
        $orderBy  = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        $executions = $this->dao->select('id,`order`')->from(TABLE_EXECUTION)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($executions as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Story sort.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storySort($executionID)
    {
        $idList   = explode(',', trim($this->post->storys, ','));
        $orderBy  = $this->post->orderBy;

        $order = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('story')->in($idList)->andWhere('project')->eq($executionID)->orderBy('order_asc')->fetch('order');
        foreach($idList as $storyID)
        {
            $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('project')->eq($executionID)->exec();
            $order++;
        }
    }

    /**
     * Story estimate.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $round
     * @access public
     * @return void
     */
    public function storyEstimate($executionID, $storyID, $round = 0)
    {
        $this->loadModel('story');

        if($_POST)
        {
            $this->story->saveEstimateInfo($storyID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('story', $storyID, 'estimated', '', $executionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('execution', 'storyEstimate', "executionID=$executionID&storyID=$storyID")));
        }

        $estimateInfo = $this->story->getEstimateInfo($storyID, $round);
        $team         = $this->execution->getTeamMembers($executionID);
        /* If user has been removed from team and has estimate record, join him into team.*/
        if(!empty($estimateInfo->estimate))
        {
            foreach($estimateInfo->estimate as $account => $estimate)
            {
                if(!in_array($account, array_keys($team)))
                {
                    $team[$account] = new stdclass();
                    $team[$account]->account = $account;
                }
            }
        }

        $this->view->estimateInfo = $estimateInfo;
        $this->view->round        = !empty($estimateInfo->round) ? $estimateInfo->round : 0;
        $this->view->rounds       = $this->story->getEstimateRounds($storyID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->team         = $team;
        $this->view->executionID  = $executionID;
        $this->view->storyID      = $storyID;
        $this->display();
    }

    /**
     * All execution.
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
    public function all($status = 'all', $projectID = 0, $orderBy = 'order_asc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadLang('my');
        $this->app->loadLang('product');
        $this->app->loadLang('programplan');

        $from = $this->app->tab;
        if($from == 'execution') $this->session->set('executionList', $this->app->getURI(true), 'execution');
        if($from == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->saveState($projectID, $projects);
            $this->project->setMenu($projectID);
        }

        if($this->app->viewType == 'mhtml')
        {
            if($this->app->rawModule == 'project' and $this->app->rawMethod == 'execution')
            {
                $projects  = $this->project->getPairsByProgram();
                $projectID = $this->project->saveState($projectID, $projects);
                $this->project->setMenu($projectID);
            }
            else
            {
                $executionID = $this->execution->saveState(0, $this->executions);
                $this->execution->setMenu($executionID);
            }
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->execution->allExecutions;
        $this->view->position[] = $this->lang->execution->allExecutions;

        $this->view->executionStats = $this->project->getStats($projectID, $status, $productID, 0, 30, $orderBy, $pager);
        $this->view->productID      = $productID;
        $this->view->projectID      = $projectID;
        $this->view->projects       = array('') + $this->project->getPairsByProgram();
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->status         = $status;
        $this->view->from           = $from;

        $this->display();
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $executionID
     * @param  string $module
     * @param  string $objectType
     * @param  string $orderby
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist($executionID = 0, $module='execution', $objectType = 'sprint', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        echo $this->fetch('personnel', 'whitelist', "objectID=$executionID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Adding users to the white list.
     *
     * @param  int     $executionID
     * @param  int     $deptID
     * @param  int     $copyID
     * @access public
     * @return void
     */
    public function addWhitelist($executionID = 0, $deptID = 0, $copyID = 0)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$executionID&dept=$deptID&copyID=$copyID&objectType=sprint&module=execution");
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
     * Export execution.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $from
     * @access public
     * @return void
     */
    public function export($status, $productID, $orderBy, $from)
    {
        if($_POST)
        {
            $executionLang   = $this->lang->execution;
            $executionConfig = $this->config->execution;

            $projectID = $from == 'project' ? $this->session->project : 0;
            if($projectID) $this->project->setMenu($projectID);

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $executionConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($executionLang, $fieldName);
                unset($fields[$key]);
            }

            $executionStats = $this->project->getStats($projectID, $status == 'byproduct' ? 'all' : $status, $productID, 0, 30, 'id_asc');
            $users = $this->loadModel('user')->getPairs('noletter');
            foreach($executionStats as $i => $execution)
            {
                $execution->PM            = zget($users, $execution->PM);
                $execution->status        = isset($execution->delay) ? $executionLang->delayed : $this->processStatus('execution', $execution);
                $execution->totalEstimate = $execution->hours->totalEstimate;
                $execution->totalConsumed = $execution->hours->totalConsumed;
                $execution->totalLeft     = $execution->hours->totalLeft;
                $execution->progress      = $execution->hours->progress . '%';

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$execution->id},") === false) unset($executionStats[$i]);
                }
            }
            if(isset($this->config->bizVersion)) list($fields, $executionStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $executionStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $executionStats);
            $this->post->set('kind', 'execution');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->loadModel('project');
        $project = $this->project->getByID($this->session->project);
        if(!empty($project->model) and $project->model == 'waterfall') $this->lang->executionCommon = $this->lang->project->stage;

        $this->view->fileName = (in_array($status, array('all', 'undone')) ? $this->lang->execution->$status : $this->lang->execution->statusList[$status]) . $this->lang->executionCommon;

        $this->display();
    }

    /**
     * Doc for compatible.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function doc($executionID)
    {
        $this->locate($this->createLink('doc', 'objectLibs', "type=execution&objectID=$executionID&from=execution"));
    }

    /**
     * Kanban setting.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxKanbanSetting($executionID)
    {
        if($_POST)
        {
            $this->loadModel('setting');
            $data = fixer::input('post')->get();
            if(common::hasPriv('execution', 'kanbanHideCols'))
            {
                $allCols = $data->allCols;
                $this->setting->setItem("system.execution.kanbanSetting.allCols", $allCols);
            }
            if(common::hasPriv('execution', 'kanbanColsColor')) $this->setting->setItem("system.execution.kanbanSetting.colorList", json_encode($data->colorList));

            die(js::reload('parent.parent'));
        }

        $this->app->loadLang('task');

        $this->view->setting   = $this->execution->getKanbanSetting();
        $this->view->executionID = $executionID;
        $this->display();
    }

    /**
     * Ajax reset kanban setting
     *
     * @param  int    $executionID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxResetKanban($executionID, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->kanbanSetting->noticeReset, inlink('ajaxResetKanban', "executionID=$executionID&confirm=yes")));

        $this->loadModel('setting');

        if(common::hasPriv('execution', 'kanbanHideCols') and isset($this->config->execution->kanbanSetting->allCols))
        {
            $allCols = json_decode($this->config->execution->kanbanSetting->allCols, true);
            unset($allCols[$executionID]);
            $this->setting->setItem("system.execution.kanbanSetting.allCols", json_encode($allCols));
        }

        $account = $this->app->user->account;
        $this->setting->deleteItems("owner={$account}&module=execution&section=kanbanSetting&key=showOption");

        if(common::hasPriv('execution', 'kanbanColsColor')) $this->setting->deleteItems("owner=system&module=execution&section=kanbanSetting&key=colorList");

        die(js::reload('parent.parent'));
    }

    /**
     * Import stories by plan.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @param  string $fromMethod
     * @access public
     * @return void
     */
    public function importPlanStories($executionID, $planID, $productID = 0, $fromMethod = 'story')
    {
        $planStories = $planProducts = array();
        $planStory   = $this->loadModel('story')->getPlanStories($planID);
        $execution   = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

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

            $projectID   = $this->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch('project');
            $planStories = array_keys($planStory);

            $this->execution->linkStory($executionID, $planStories, $planProducts);
            if($this->config->systemMode == 'new' and $executionID != $projectID) $this->execution->linkStory($projectID, $planStories, $planProducts);
        }

        $moduleName = 'execution';
        $param      = "executionID=$executionID";
        if($execution->type == 'project')
        {
            $moduleName = 'projectstory';
            $param      = "projectID=$executionID";
        }
        if($count != 0) echo js::alert(sprintf($this->lang->execution->haveDraft, $count)) . js::locate($this->createLink($moduleName, 'story', $param));
        die(js::locate(helper::createLink($moduleName, $fromMethod, $param)));
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
        $this->view->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
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

        $execution = $this->execution->getById($task->execution);

        $this->view->task    = $task;
        $this->view->execution = $execution;
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     *Ajax get group menu of lanes.
     *
     * @param string $type all|syory|task|bug
     * @param string $group
     * @access public
     * @return void
     */
    public function ajaxGetGroup($type, $group = 'default')
    {
        $this->app->loadLang('kanban');
        $groups = array();
        $groups = $this->lang->kanban->group->$type;
        die(html::select("group", $groups, $group, 'class="form-control chosen" data-max_drop_width="215"'));
    }

    /**
     * Ajax update kanban.
     *
     * @param  int    $executionID
     * @param  string $enterTime
     * @param  string $browseType
     * @param  string $groupBy
     * @access public
     * @return void
     */
    public function ajaxUpdateKanban($executionID = 0, $enterTime = '', $browseType = '', $groupBy = '')
    {
        $enterTime = date('Y-m-d H:i:s', $enterTime);
        $lastEditedTime = $this->dao->select("max(lastEditedTime) as lastEditedTime")->from(TABLE_KANBANLANE)->where('execution')->eq($executionID)->fetch('lastEditedTime');

        if($lastEditedTime > $enterTime)
        {
            $kanbanGroup = $this->loadModel('kanban')->getExecutionKanban($executionID, $browseType, $groupBy);
            die(json_encode($kanbanGroup));
        }
        else
        {
            die('');
        }
    }

    /**
     * AJAX: Update the execution name.
     *
     * @param  int     $executionID
     * @param  string  $newExecutionName
     * @access public
     * @return bool
     */
    public function ajaxUpdateExecutionName($executionID, $newExecutionName)
    {
        $this->dao->update(TABLE_EXECUTION)->set('name')->eq($newExecutionName)->where('id')->eq($executionID)->exec();
        if(dao::isError()) echo false;

        echo true;
    }
}
