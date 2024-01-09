<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
    public $objectType = 'execution';

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
        if(!isset($this->app->user)) return;

        $mode = $this->app->tab == 'execution' ? 'multiple' : '';
        /* Can not use $this->app->tab in API. */
        if((defined('RUN_MODE') and RUN_MODE == 'api') or $this->viewType == 'json') $mode = '';
        $this->executions = $this->execution->getPairs(0, 'all', "nocode,{$mode}");
        $skipCreateStep   = array('computeburn', 'ajaxgetdropmenu', 'executionkanban', 'ajaxgetteammembers', 'all');
        if(!in_array($this->methodName, $skipCreateStep) and $this->app->tab == 'execution')
        {
            if(!$this->executions and $this->methodName != 'index' and $this->methodName != 'create' and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
        }
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
        $products        = $this->product->getProducts($executionID);
        $childExecutions = $this->execution->getChildExecutions($executionID);
        $teamMembers     = $this->execution->getTeamMembers($executionID);
        $actions         = $this->loadModel('action')->getList($this->objectType, $executionID);
        $project         = $this->loadModel('project')->getByID($execution->project);

        /* Set menu. */
        $this->execution->setMenu($executionID, $buildID = 0, $extra);

        /* Assign. */
        $this->view->hidden          = !empty($project->hasProduct) ? "" : 'hide';
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
        $this->loadModel('product');
        $this->loadModel('user');

        if(common::hasPriv('execution', 'create')) $this->lang->TRActions = html::a($this->createLink('execution', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->execution->create, '', "class='btn btn-primary'");

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        if($executionID) $this->session->set("storyList", $this->createLink("execution", "story", "&executionID=" . $executionID));

        /* Set browse type. */
        $browseType = strtolower($status);

        $execution = $this->commonAction($executionID, $status);
        if($this->config->systemMode == 'PLM') $execution->ipdStage = $this->execution->canStageStart($execution);
        $executionID = $execution->id;

        if($execution->type == 'kanban' and $this->config->vision != 'lite' and $this->app->getViewType() != 'json') $this->locate($this->createLink('execution', 'kanban', "executionID=$executionID"));

        /* Get products by execution. */
        $products = $this->product->getProductPairsByProject($executionID);
        setcookie('preExecutionID', $executionID, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        /* Save the recently five executions visited in the cookie. */
        $recentExecutions = isset($this->config->execution->recentExecutions) ? explode(',', $this->config->execution->recentExecutions) : array();
        array_unshift($recentExecutions, $executionID);
        $recentExecutions = array_unique($recentExecutions);
        $recentExecutions = array_slice($recentExecutions, 0, 5);
        $recentExecutions = join(',', $recentExecutions);
        if($this->session->multiple)
        {
            if(!isset($this->config->execution->recentExecutions) or $this->config->execution->recentExecutions != $recentExecutions) $this->setting->updateItem($this->app->user->account . 'common.execution.recentExecutions', $recentExecutions);
            if(!isset($this->config->execution->lastExecution)    or $this->config->execution->lastExecution != $executionID)         $this->setting->updateItem($this->app->user->account . 'common.execution.lastExecution', $executionID);
        }

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
        $this->app->session->set('taskList', $uri . "#app={$this->app->tab}", 'execution');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionTaskOrder ? $this->cookie->executionTaskOrder : 'status,id_desc';
        setcookie('executionTaskOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);

        /* Header and position. */
        $this->view->title = $execution->name . $this->lang->colon . $this->lang->execution->task;

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get tasks. */
        $tasks    = $this->execution->getTasks($productID, $executionID, $this->executions, $browseType, $queryID, $moduleID, $sort, $pager);
        if(empty($tasks) and $pageID > 1)
        {
            $pager = pager::init(0, $recPerPage, 1);
            $tasks = $this->execution->getTasks($productID, $executionID, $this->executions, $browseType, $queryID, $moduleID, $sort, $pager);
        }

        /* Get product. */
        $product = $this->product->getById($productID);

        /* Display of branch label. */
        $showBranch = $this->loadModel('branch')->showBranch($productID, $moduleID, $executionID);

        /* team member pairs. */
        $memberPairs = array();
        foreach($this->view->teamMembers as $key => $member) $memberPairs[$key] = $member->realname;
        $memberPairs = $this->user->processAccountSort($memberPairs);

        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $extra         = (isset($this->config->execution->task->allModule) && $this->config->execution->task->allModule == 1) ? 'allModule' : '';
        $showModule    = !empty($this->config->datatable->executionTask->showModule) ? $this->config->datatable->executionTask->showModule : '';
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($executionID, 'task', $showModule) : array();

        /* Build the search form. */
        $modules   = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
        $actionURL = $this->createLink('execution', 'task', "executionID=$executionID&status=bySearch&param=myQueryID");
        $this->config->execution->search['onMenuBar'] = 'yes';
        if(!$execution->multiple) unset($this->config->execution->search['fields']['execution']);
        $this->execution->buildTaskSearchForm($executionID, $this->executions, $queryID, $actionURL, $modules);

        /* Assign. */
        $this->view->tasks        = $tasks;
        $this->view->hasTasks     = !empty($tasks) || !empty($this->task->getExecutionTasks($executionID));
        $this->view->summary      = $this->execution->summary($tasks);
        $this->view->tabID        = 'task';
        $this->view->pager        = $pager;
        $this->view->recTotal     = $pager->recTotal;
        $this->view->recPerPage   = $pager->recPerPage;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;
        $this->view->status       = $status;
        $this->view->users        = $this->user->getPairs('noletter|all');
        $this->view->param        = $param;
        $this->view->executionID  = $executionID;
        $this->view->execution    = $execution;
        $this->view->productID    = $productID;
        $this->view->product      = $product;
        $this->view->modules      = $modules;
        $this->view->moduleID     = $moduleID;
        $this->view->moduleTree   = $this->tree->getTaskTreeMenu($executionID, $productID, $startModuleID = 0, array('treeModel', 'createTaskLink'), $extra);
        $this->view->memberPairs  = $memberPairs;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->setModule    = !$execution->multiple ? false : true;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->showBranch   = $showBranch;
        $this->view->projectName  = $this->loadModel('project')->getById($execution->project)->name . ' / ' . $execution->name;

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
        if($groupBy == 'story' and $execution->lifetime == 'ops')
        {
            $groupBy = 'status';
            unset($this->lang->execution->groups['story']);
        }

        $sort        = common::appendOrder($groupBy);
        $tasks       = $this->loadModel('task')->getExecutionTasks($executionID, $productID = 0, $status = 'all', $modules = 0, $sort);
        $groupBy     = str_replace('`', '', $groupBy);
        $taskLang    = $this->lang->task;
        $groupByList = array();
        $groupTasks  = array();

        $groupTasks = array();
        $allCount   = 0;
        foreach($tasks as $task)
        {
            if($task->mode == 'multi') $task->assignedToRealName = $this->lang->task->team;

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
                $multiTaskCount = array();
                foreach($groupTasks as $assignedTo => $tasks)
                {
                    foreach($tasks as $i => $task)
                    {
                        if($task->status != 'wait' and $task->status != 'doing')
                        {
                            if($task->mode == 'multi')
                            {
                                if(!isset($multiTaskCount[$task->id]))
                                {
                                    $multiTaskCount[$task->id] = true;
                                    $allCount -= 1;
                                }
                            }
                            else
                            {
                                $allCount -= 1;
                            }

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
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
        $this->view->filter      = $filter;
        $this->view->allCount    = $allCount;
        $this->display();
    }

    /**
     * Import tasks undoned from other executions.
     *
     * @param  int    $executionID
     * @param  int    $fromExecution
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importTask($toExecution, $fromExecution = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $this->execution->importTask($toExecution);

            /* If link from no head then reload. */
            if(isonlybody())
            {
                $kanbanData = $this->loadModel('kanban')->getRDKanban($toExecution, $this->session->execLaneType ? $this->session->execLaneType : 'all');
                return print(js::reload('parent'));
            }

            return print(js::locate(inlink('importTask', "toExecution=$toExecution&fromExecution=$fromExecution"), 'parent'));
        }

        $execution   = $this->commonAction($toExecution);
        $toExecution = $execution->id;
        $project     = $this->loadModel('project')->getByID($execution->project);
        $branches    = $this->execution->getBranches($toExecution);
        $tasks       = $this->execution->getTasks2Imported($toExecution, $branches);
        $executions  = $this->execution->getToImport(array_keys($tasks), $execution->type, $project->model);
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

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($tasks2Imported);
        $pager      = new pager($recTotal, $recPerPage, $pageID);

        $tasks2ImportedList = array_chunk($tasks2Imported, $pager->recPerPage, true);
        $tasks2ImportedList = empty($tasks2ImportedList) ? $tasks2ImportedList : $tasks2ImportedList[$pageID - 1];
        $tasks2ImportedList = $this->loadModel('task')->processTasks($tasks2ImportedList);

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true), 'execution');

        $this->view->title            = $execution->name . $this->lang->colon . $this->lang->execution->importTask;
        $this->view->pager            = $pager;
        $this->view->tasks2Imported   = $tasks2ImportedList;
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
            if(dao::isError()) return print(js::error(dao::getError()));

            /* If link from no head then reload. */
            if(isonlybody())
            {
                $kanbanData = $this->loadModel('kanban')->getRDKanban($executionID, $this->session->execLaneType ? $this->session->execLaneType : 'all');
                return print(js::reload('parent'));
            }

            return print(js::locate($this->createLink('execution', 'importBug', "executionID=$executionID"), 'parent'));
        }

        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        $this->loadModel('bug');
        $executions = $this->execution->getPairs(0, 'all', 'nocode');
        $this->execution->setMenu($executionID);

        $execution = $this->execution->getByID($executionID);
        $project   = $this->loadModel('project')->getByID($execution->project);

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
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->fetchPairs('project');

            $projects = $this->loadModel('product')->getProjectPairsByProductIDList(array_keys($products));
        }
        else
        {
            $executionName = $executions[$executionID];
            unset($executions);
            $executions[$executionID] = $executionName;

            $projects[$project->id] = $project->name;
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
            $bugs     = $this->execution->getSearchBugs($products, $executionID, $bugQuery, $pager, 'id_desc');
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
        $this->config->bug->search['params']['plan']['values']      = $this->loadModel('productplan')->getPairs(array_keys($products));
        $this->config->bug->search['module'] = 'importBug';
        $this->config->bug->search['params']['confirmed']['values'] = array('' => '') + $this->lang->bug->confirmedList;

        $this->loadModel('tree');
        $bugModules = array();
        foreach($products as $productID => $productName)
        {
            $productModules = $this->tree->getOptionMenu($productID, 'bug', 0, 'all');
            foreach($productModules as $moduleID => $moduleName)
            {
                if(empty($moduleID))
                {
                    $bugModules[$moduleID] = $moduleName;
                    continue;
                }
                $bugModules[$moduleID] = $productName . $moduleName;
            }
        }
        $this->config->bug->search['params']['module']['values'] = $bugModules;

        $this->config->bug->search['params']['project']['values'] = array('' => '') + $projects;

        $this->config->bug->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productID, 'all', 'withbranch|releasetag');

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
        if(empty($execution->multiple) and empty($execution->hasProduct)) unset($this->config->bug->search['fields']['plan']);
        if(empty($project->hasProduct))
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model !== 'scrum') unset($this->config->bug->search['fields']['plan']);
        }
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
        $this->view->execution      = $execution;
        $this->view->executionID    = $executionID;
        $this->view->requiredFields = explode(',', $this->config->task->create->requiredFields);
        $this->display();
    }

    /**
     * Browse stories of a execution.
     *
     * @param  int    $executionID
     * @param  string $storyType story|requirement
     * @param  string $orderBy
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story($executionID = 0, $storyType = 'story', $orderBy = 'order_desc', $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('product');
        $this->loadModel('datatable');
        $this->app->loadLang('datatable');
        $this->app->loadLang('testcase');

        /* Change for requirement story title. */
        if($storyType == 'requirement')
        {
            $this->lang->story->title           = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->story->noStory         = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->noStory);
            $this->lang->execution->createStory = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->execution->createStory);
            $this->config->product->search['fields']['title'] = $this->lang->story->title;
            unset($this->config->product->search['fields']['plan']);
            unset($this->config->product->search['fields']['stage']);
            $this->story->replaceURLang($storyType);
        }

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
            $module    = $this->loadModel('tree')->getByID($param);
            $productID = isset($module->root) ? $module->root : 0;

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
            $this->session->set('storyBrowseType', $type, 'execution');
        }

        /* Save session. */
        $this->session->set('storyList', $this->app->getURI(true), $this->app->tab);
        $this->session->set('executionStoryList', $this->app->getURI(true), 'execution');

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionStoryOrder ? $this->cookie->executionStoryOrder : 'pri';
        setcookie('executionStoryOrder', $orderBy, 0, $this->config->webRoot, '', false, true);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $queryID     = ($type == 'bysearch') ? $param : 0;
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $stories = $this->story->getExecutionStories($executionID, 0, 0, $sort, $type, $param, $storyType, '', '', $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);

        $users = $this->user->getPairs('noletter');

        /* Build the search form. */
        $modules          = array();
        $productModules   = array();
        $executionModules = $this->loadModel('tree')->getTaskTreeModules($executionID, true);
        $products         = $this->product->getProducts($executionID);
        if($productID)
        {
            $product = $products[$productID];
            $productModules = $this->tree->getOptionMenu($productID, 'story', 0, $product->branches);
        }
        else
        {
            foreach($products as $product) $productModules += $this->tree->getOptionMenu($product->id, 'story', 0, $product->branches);
        }

        if(defined('TUTORIAL'))
        {
            $modules = $this->loadModel('tutorial')->getModulePairs();
        }
        else
        {
            foreach($productModules as $branchID => $moduleList)
            {
                foreach($moduleList as $moduleID => $moduleName)
                {
                    if($moduleID and !isset($executionModules[$moduleID])) continue;
                    $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
                }
            }
        }
        $actionURL    = $this->createLink('execution', 'story', "executionID=$executionID&storyType=$storyType&orderBy=$orderBy&type=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $branchOption = array();
        foreach($branchGroups as $branches)
        {
            foreach($branches as $branchID => $name)
            {
                $branchOption[$branchID] = $name;
            }
        }

        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'executionStory', $execution);

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->story;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->story;

        /* Get related tasks, bugs, cases count of each story. */
        $storyIdList = array();
        foreach($stories as $story)
        {
            $storyIdList[$story->id] = $story->id;
            if(!empty($story->children))
            {
                foreach($story->children as $child) $storyIdList[$child->id] = $child->id;
            }
        }

        /* Count T B C */
        $storyTasks  = $this->loadModel('task')->getStoryTaskCounts($storyIdList, $executionID);
        $storyBugs   = $this->loadModel('bug')->getStoryBugCounts($storyIdList, $executionID);
        $storyCases  = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        $plans    = $this->execution->getPlans($products, 'skipParent|withMainPlan|unexpired|noclosed', $executionID);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        if($this->cookie->storyModuleParam) $this->view->module = $this->loadModel('tree')->getById($this->cookie->storyModuleParam);
        if($this->cookie->storyProductParam) $this->view->product = $this->loadModel('product')->getById($this->cookie->storyProductParam);
        if($this->cookie->storyBranchParam)
        {
            $branchID = $this->cookie->storyBranchParam;
            if(strpos($branchID, ',') !== false) list($productID, $branchID) = explode(',', $branchID);
            $this->view->branch  = $this->loadModel('branch')->getById($branchID, $productID);
        }

        /* Display of branch label. */
        $showBranch = $this->loadModel('branch')->showBranch($this->cookie->storyProductParam, $this->cookie->storyModuleParam, $executionID);

        /* Get execution's product. */
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);

        if(empty($productID)) $productID = key($productPairs);
        $showModule  = !empty($this->config->datatable->executionStory->showModule) ? $this->config->datatable->executionStory->showModule : '';
        $modulePairs = $showModule ? $this->tree->getModulePairs($type == 'byproduct' ? $param : 0, 'story', $showModule) : array();

        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        if(!$execution->hasProduct and !$execution->multiple)
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'story', $startModuleID = 0, array('treeModel', $createModuleLink), array('executionID' => $executionID, 'productID' => $productID), '', "&param=$param&storyType=$storyType");
        }
        else
        {
            $moduleTree = $this->tree->getProjectStoryTreeMenu($executionID, 0, array('treeModel', $createModuleLink));
        }

        $executionProductList  = $this->loadModel('product')->getProducts($executionID);
        $multiBranch = false;
        foreach($executionProductList as $executionProduct)
        {
            if($executionProduct->type != 'normal')
            {
                $multiBranch = true;
                break;
            }
        }
        $summary = $this->product->summary($stories, $storyType);
        if($storyType == 'requirement') $summary = str_replace($this->lang->SRCommon, $this->lang->URCommon, $summary);

        /* Assign. */
        $this->view->title             = $title;
        $this->view->position          = $position;
        $this->view->productID         = $productID;
        $this->view->execution         = $execution;
        $this->view->stories           = $stories;
        $this->view->linkedTaskStories = $this->story->getIdListWithTask($executionID);
        $this->view->allPlans          = $allPlans;
        $this->view->summary           = $summary;
        $this->view->orderBy           = $orderBy;
        $this->view->storyType         = $storyType;
        $this->view->type              = $this->session->executionStoryBrowseType;
        $this->view->param             = $param;
        $this->view->isAllProduct      = ($this->cookie->storyProductParam or $this->cookie->storyModuleParam or $this->cookie->storyBranchParam) ? false : true;
        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        $this->view->moduleTree        = $moduleTree;
        $this->view->modulePairs       = $modulePairs;
        $this->view->tabID             = 'story';
        $this->view->storyTasks        = $storyTasks;
        $this->view->storyBugs         = $storyBugs;
        $this->view->storyCases        = $storyCases;
        $this->view->users             = $users;
        $this->view->pager             = $pager;
        $this->view->setModule         = true;
        $this->view->branchGroups      = $branchGroups;
        $this->view->branchOption      = $branchOption;
        $this->view->canBeChanged      = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->showBranch        = $showBranch;
        $this->view->storyStages       = $this->product->batchGetStoryStage($stories);
        $this->view->multiBranch       = $multiBranch;

        $this->display();
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storyView($storyID, $executionID = 0)
    {
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $story = $this->loadModel('story')->getByID($storyID);
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$story->version&param=" . ($executionID ? $executionID : $this->session->execution));
    }

    /**
     * Browse bugs of a execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
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
    public function bug($executionID = 0, $productID = 0, $branch = 'all', $orderBy = 'status,id_desc', $build = 0, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user');
        $this->loadModel('product');
        $this->loadModel('datatable');
        $this->loadModel('tree');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true), 'execution');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $type        = strtolower($type);
        $queryID     = ($type == 'bysearch') ? (int)$param : 0;
        $execution   = $this->commonAction($executionID);
        $project     = $this->loadModel('project')->getByID($execution->project);
        $executionID = $execution->id;
	$products    = $this->product->getProducts($execution->id);
	if(count($products) === 1) $productID = current($products)->id;

        if($execution->hasProduct)
        {
            unset($this->config->bug->search['fields']['product']);
            if($project->model != 'scrum')
            {
                unset($this->config->bug->search['fields']['plan']);
            }
        }

        $productPairs = array('0' => $this->lang->product->all);
        foreach($products as $productData) $productPairs[$productData->id] = $productData->name;
        if($execution->hasProduct) $this->lang->modulePageNav = $this->product->select($productPairs, $productID, 'execution', 'bug', $executionID, $branch);

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->bug;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->bug;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);
        $bugs  = $this->bug->getExecutionBugs($executionID, $productID, $branch, $build, $type, $param, $sort, '', $pager);
        $bugs  = $this->bug->checkDelayedBugs($bugs);
        $users = $this->user->getPairs('noletter');

        /* team member pairs. */
        $memberPairs = array();
        $memberPairs[] = "";
        foreach($this->view->teamMembers as $key => $member)
        {
            $memberPairs[$key] = $member->realname;
        }
        $memberPairs = $this->user->processAccountSort($memberPairs);

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'bug', "executionID=$executionID&productID=$productID&branch=$branch&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->execution->buildBugSearchForm($products, $queryID, $actionURL);

        $product = $this->product->getById($productID);
        $showBranch      = false;
        $branchOption    = array();
        $branchTagOption = array();
        if($product and $product->type != 'normal')
        {
            /* Display of branch label. */
            $showBranch = $this->loadModel('branch')->showBranch($productID);

            /* Display status of branch. */
            $branches = $this->branch->getList($productID, $executionID, 'all');
            foreach($branches as $branchInfo)
            {
                $branchOption[$branchInfo->id]    = $branchInfo->name;
                $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
            }
        }

        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }
        $storyList = $storyIdList ? $this->loadModel('story')->getByList($storyIdList) : array();
        $taskList  = $taskIdList  ? $this->loadModel('task')->getByList($taskIdList)   : array();

        $showModule  = !empty($this->config->datatable->bugBrowse->showModule) ? $this->config->datatable->bugBrowse->showModule : '';

        /* Process the openedBuild and resolvedBuild fields. */
        $bugs = $this->bug->processBuildForBugs($bugs);

        $moduleID = $type != 'bysearch' ? $param : 0;
        $modules  = $this->tree->getAllModulePairs('bug');

        /* Get module tree.*/
        $extra = array('projectID' => $executionID, 'orderBy' => $orderBy, 'type' => $type, 'build' => $build, 'branchID' => $branch);
        if($executionID and empty($productID) and count($products) > 1)
        {
            $moduleTree = $this->tree->getBugTreeMenu($executionID, $productID, 0, array('treeModel', 'createBugLink'), $extra);
        }
        elseif(!empty($products))
        {
            $productID  = empty($productID) ? reset($products)->id : $productID;
            $moduleTree = $this->tree->getTreeMenu($productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('branchID' => $branch, 'productID' => $productID), $branch);
        }
        else
        {
            $moduleTree = '';
        }
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $showModule = !empty($this->config->datatable->executionBug->showModule) ? $this->config->datatable->executionBug->showModule : '';

        /* Assign. */
        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->bugs            = $bugs;
        $this->view->tabID           = 'bug';
        $this->view->build           = $this->loadModel('build')->getById($build);
        $this->view->buildID         = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->users           = $users;
        $this->view->productID       = $productID;
        $this->view->project         = $project;
        $this->view->branchID        = empty($this->view->build->branch) ? $branch : $this->view->build->branch;
        $this->view->memberPairs     = $memberPairs;
        $this->view->type            = $type;
        $this->view->summary         = $this->bug->summary($bugs);
        $this->view->param           = $param;
        $this->view->defaultProduct  = (empty($productID) and !empty($products)) ? current(array_keys($products)) : $productID;
        $this->view->builds          = $this->build->getBuildPairs($productID);
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->plans           = $this->loadModel('productplan')->getPairs($productID ? $productID : array_keys($products));
        $this->view->stories         = $storyList;
        $this->view->tasks           = $taskList;
        $this->view->projectPairs    = $this->loadModel('project')->getPairsByProgram();
        $this->view->moduleTree      = $moduleTree;
        $this->view->modules         = $modules;
        $this->view->moduleID        = $moduleID;
        $this->view->moduleName      = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($productID, 'bug', $showModule) : array();
        $this->view->setModule       = true;
        $this->view->showBranch      = false;

        $this->display();
    }

    /**
     * Execution case list.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $type
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase($executionID = 0, $productID = 0, $branchID = 'all', $type = 'all', $moduleID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testcase');
        $this->loadModel('testtask');
        $this->loadModel('tree');
        $this->commonAction($executionID);
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'execution');
        $this->session->set('bugList',  $uri, 'execution');

        $products = $this->product->getProducts($executionID, 'all', '', false);
        if(count($products) == 1) $productID = key($products);

        $execution = $this->execution->getByID($executionID);

        $hasProduct = $this->dao->findByID($execution->project)->from(TABLE_PROJECT)->fetch('hasProduct');

        $extra = $executionID;
        if($hasProduct) $this->lang->modulePageNav = $this->product->select(array('0' => $this->lang->product->all) + $products, $productID, 'execution', 'testcase', $extra, $branchID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $cases = $this->loadModel('testcase')->getExecutionCases($executionID, $productID, $branchID, $moduleID, $orderBy, $pager, $type);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->testcase->appendData($cases, 'case');
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        $modules = $this->tree->getAllModulePairs('case');

        /* Get module tree.*/
        if($executionID and empty($productID))
        {
            $moduleTree = $this->tree->getCaseTreeMenu($executionID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $executionID, 'productID' => $productID), $branchID);
        }
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $this->view->title       = $this->lang->execution->testcase;
        $this->view->executionID = $executionID;
        $this->view->productID   = $productID;
        $this->view->cases       = $cases;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->type        = $type;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->execution   = $execution;
        $this->view->moduleTree  = $moduleTree;
        $this->view->modules     = $modules;
        $this->view->moduleID    = $moduleID;
        $this->view->moduleName  = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->branchID    = $branchID;

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
        echo $this->fetch('testreport', 'browse', "objectID=$executionID&objectType=$objectType&extra=$extra&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Browse builds of a execution.
     *
     * @param  int    $executionID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function build($executionID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc')
    {
        /* Load module and set session. */
        $this->loadModel('testtask');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Get products' list. */
        $products = $this->product->getProducts($executionID, 'all', '', false);
        $products = array('' => '') + $products;

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('execution', 'build', "executionID=$executionID&type=bysearch&queryID=myQueryID");

        $this->loadModel('build');
        $product = $param ? $this->loadModel('product')->getById($param) : '';
        if($product and $product->type != 'normal')
        {
            $this->loadModel('branch');
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($product->id, '', $executionID);
            $this->config->build->search['fields']['branch'] = sprintf($this->lang->build->branchName, $this->lang->product->branchName[$product->type]);
            $this->config->build->search['params']['branch'] = array('operator' => '=', 'control' => 'select', 'values' => $branches);
        }
        if(!$execution->hasProduct) unset($this->config->build->search['fields']['product']);
        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'execution');

        /* Get builds. */
        if($type == 'bysearch')
        {
            $builds = $this->loadModel('build')->getExecutionBuildsBySearch((int)$executionID, $queryID);
        }
        else
        {
            $builds = $this->loadModel('build')->getExecutionBuilds((int)$executionID, $type, $param, $orderBy);
        }

        /* Set execution builds. */
        $executionBuilds = array();
        $productList     = $this->product->getProducts($executionID);
        $showBranch      = false;
        if(!empty($builds))
        {
            foreach($builds as $build) $executionBuilds[$build->product][] = $build;

            /* Get branch name. */
            $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($executionBuilds));
            foreach($builds as $build)
            {
                $build->branchName = '';
                if(isset($branchGroups[$build->product]))
                {
                    $showBranch  = true;
                    $branchPairs = $branchGroups[$build->product];
                    foreach(explode(',', trim($build->branch, ',')) as $branchID)
                    {
                        if(isset($branchPairs[$branchID])) $build->branchName .= "{$branchPairs[$branchID]},";
                    }
                    $build->branchName = trim($build->branchName, ',');
                }
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
        $this->view->showBranch    = $showBranch;

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

        $tasks = $this->testtask->getExecutionTasks($executionID, 'execution', $orderBy, $pager);
        foreach($tasks as $key => $task) $productTasks[$task->product][] = $task;

        $this->view->title         = $this->executions[$executionID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]    = html::a($this->createLink('execution', 'testtask', "executionID=$executionID"), $this->executions[$executionID]);
        $this->view->position[]    = $this->lang->testtask->common;
        $this->view->execution     = $execution;
        $this->view->project       = $this->loadModel('project')->getByID($execution->project);
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
        $burnBy      = $this->cookie->burnBy ? $this->cookie->burnBy : $burnBy;

        /* Header and position. */
        $title      = $execution->name . $this->lang->colon . $this->lang->execution->burn;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->burn;

        /* Get date list. */
        if(((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
            or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
            or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
            and strpos($type, 'delay') === false)
        $type .= ',withdelay';

        $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
        $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
        $endDate  = strpos($type, 'withdelay') !== false ? $deadline : $execution->end;
        list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, $interval, 'Y-m-d', $execution->end);

        $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
        $chartData    = $this->execution->buildBurnData($executionID, $dateList, $type, $burnBy, $executionEnd);

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
        if($reload == 'yes') return print(js::reload('parent'));
        $this->display();
    }

    /**
     * Kanban CFD.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $withWeekend
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function cfd($executionID = 0, $type = 'story', $withWeekend = 'false', $begin = '', $end = '')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        $this->loadModel('kanban');
        $this->app->loadClass('date');

        $minDate = !helper::isZeroDate($execution->openedDate) ? date('Y-m-d', strtotime($execution->openedDate)) : date('Y-m-d', strtotime($execution->begin));
        $maxDate = !helper::isZeroDate($execution->closedDate) ? date('Y-m-d', strtotime($execution->closedDate)) : helper::today();
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) $type = 'task';

        if(!empty($_POST))
        {
            $begin = htmlspecialchars($this->post->begin, ENT_QUOTES);
            $end   = htmlspecialchars($this->post->end, ENT_QUOTES);

            $dateError = array();

            if(empty($begin)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->begin);
            if(empty($end)) $dateError[] = sprintf($this->lang->error->notempty, $this->lang->execution->charts->cfd->end);
            if(empty($dateError))
            {
                if($begin < $minDate) $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->begin, $minDate);
                if($begin > $maxDate) $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->begin, $maxDate);
                if($end < $minDate)   $dateError[] = sprintf($this->lang->error->gt, $this->lang->execution->charts->cfd->end, $minDate);
                if($end > $maxDate)   $dateError[] = sprintf($this->lang->error->lt, $this->lang->execution->charts->cfd->end, $maxDate);
            }
            if(!empty($dateError))
            {
                foreach($dateError as $index => $error) $dateError[$index] = str_replace(array('。', '.'), array('', ''), $error) . '<br/>';
                return $this->sendError($dateError);
            }

            if($begin >= $end) return $this->sendError($this->lang->execution->charts->cfd->errorBegin);
            if(date("Y-m-d", strtotime("-3 months", strtotime($end))) > $begin) return $this->sendError($this->lang->execution->charts->cfd->errorDateRange);

            $this->execution->computeCFD($executionID);
            $this->execution->checkCFDData($executionID, $begin);
            return $this->send(array('result' => 'success', 'locate' => $this->createLink('execution', 'cfd', "executionID=$executionID&type=$type&withWeekend=$withWeekend&begin=" . helper::safe64Encode(urlencode($begin)) . "&end=" . helper::safe64Encode(urlencode($end)))));
        }

        if($begin and $end)
        {
            $begin = urldecode(helper::safe64Decode($begin));
            $end   = urldecode(helper::safe64Decode($end));
        }
        else
        {
            list($begin, $end) = $this->execution->getBeginEnd4CFD($execution);
        }
        $dateList = date::getDateList($begin, $end, 'Y-m-d', $withWeekend == 'false'? 'noweekend' : '');

        $chartData = $this->execution->buildCFDData($executionID, $dateList, $type);
        if(isset($chartData['line'])) $chartData['line'] = array_reverse($chartData['line']);

        $this->view->title         = $this->lang->execution->CFD;
        $this->view->type          = $type;
        $this->view->execution     = $execution;
        $this->view->withWeekend   = $withWeekend;
        $this->view->executionName = $execution->name;
        $this->view->executionID   = $executionID;
        $this->view->chartData     = $chartData;
        $this->view->features      = $this->execution->getExecutionFeatures($execution);
        $this->view->begin         = $begin;
        $this->view->end           = $end;
        $this->view->minDate       = $minDate;
        $this->view->maxDate       = $maxDate;
        $this->display();
    }

    /**
     * Compute cfd datas.
     *
     * @param  string $reload
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function computeCFD($reload = 'no', $executionID = 0)
    {
        $this->execution->computeCFD($executionID);
        if($reload == 'yes') return print(js::reload('parent'));
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
            return print(js::reload('parent.parent'));
        }

        $execution = $this->execution->getById($executionID);

        if($execution->type == 'stage') $this->lang->execution->placeholder->totalLeft = str_replace($this->lang->executionCommon, $this->lang->execution->stage, $this->lang->execution->placeholder->totalLeft);

        $this->view->firstBurn = $this->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('date')->eq($execution->begin)->fetch();
        $this->view->execution = $execution;
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
        $this->app->session->set('teamList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        $deptID      = $this->app->user->admin ? 0 : $this->app->user->dept;

        $title      = $execution->name . $this->lang->colon . $this->lang->execution->team;
        $position[] = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $position[] = $this->lang->execution->team;

        $this->view->title        = $title;
        $this->view->position     = $position;
        $this->view->deptUsers    = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * Create a execution.
     *
     * @param string $projectID
     * @param int    $executionID
     * @param string $copyExecutionID
     * @param int    $planID
     * @param string $confirm
     * @param string $productID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function create($projectID = '', $executionID = 0, $copyExecutionID = '', $planID = 0, $confirm = 'no', $productID = 0, $extra = '')
    {
        if($this->app->tab == 'doc')     unset($this->lang->doc->menu->execution['subMenu']);
        if($this->app->tab == 'project') $this->project->setMenu($projectID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $type         = !empty($output['type']) ? $output['type'] : 'sprint';
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
            $type          = $copyExecution->type;
            $name          = $copyExecution->name;
            $code          = $copyExecution->code;
            $team          = $copyExecution->team;
            $acl           = $copyExecution->acl;
            $whitelist     = $copyExecution->whitelist;
            $projectID     = $copyExecution->project;
            $project       = $this->project->getByID($projectID);
            $products      = $this->loadModel('product')->getProducts($copyExecutionID);
            $branches      = $this->project->getBranchesByProject($copyExecutionID);
            $plans         = $this->loadModel('productplan')->getGroupByProduct(array_keys($products), 'skipParent|unexpired');
            $branchGroups  = $this->execution->getBranchByProduct(array_keys($products), $projectID);

            $linkedBranches = array();
            foreach($products as $productIndex => $product)
            {
                $productPlans[$productIndex] = array();
                foreach($branches[$productIndex] as $branchID => $branch)
                {
                    $linkedBranches[$productIndex][$branchID] = $branchID;
                    $productPlans[$productIndex] += isset($plans[$productIndex][$branchID]) ? $plans[$productIndex][$branchID] : array();
                }
            }

            $this->view->branches       = $branches;
            $this->view->linkedBranches = $linkedBranches;
        }

        $project = $this->project->getByID($projectID);
        if(!empty($project) and ($project->model == 'kanban' or ($project->model == 'agileplus' and $type == 'kanban')))
        {
            global $lang;
            $executionLang           = $lang->execution->common;
            $executionCommonLang     = $lang->executionCommon;
            $lang->executionCommon   = $lang->execution->kanban;
            $lang->execution->common = $lang->execution->kanban;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
            $lang->execution->common = $executionLang;
            $lang->executionCommon   = $executionCommonLang;

            $lang->execution->typeList['sprint'] = $executionCommonLang;
        }
        elseif(!empty($project) and ($project->model == 'waterfall' or $project->model == 'waterfallplus'))
        {
            global $lang;
            $lang->executionCommon = $lang->execution->stage;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';

            $this->config->execution->create->requiredFields .= ',products0';
        }

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
                    $executionProductList  = $this->loadModel('product')->getProducts($executionID);
                    $multiBranchProduct = false;
                    foreach($executionProductList as $executionProduct)
                    {
                        if($executionProduct->type != 'normal')
                        {
                            $multiBranchProduct = true;
                            break;
                        }
                    }

                    $importPlanStoryTips = $multiBranchProduct ? $this->lang->execution->importBranchPlanStory : $this->lang->execution->importPlanStory;

                    return print(js::confirm($importPlanStoryTips, inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=$planID&confirm=yes"), inlink('create', "projectID=$projectID&executionID=$executionID")));
                }
            }

            if(!empty($projectID) and $execution->type == 'kanban' and $this->app->tab == 'project') return $this->send(array('result' => 'success', 'locate' => $this->createLink('project', 'index', "projectID=$projectID")));
            if(!empty($projectID) and $execution->type == 'kanban') return $this->send(array('result' => 'success', 'locate' => inlink('kanban', "executionID=$executionID")));

            $this->execution->setMenu($executionID); // Fix bug #22897.

            $this->view->title       = $this->lang->execution->tips;
            $this->view->tips        = $this->fetch('execution', 'tips', "executionID=$executionID");
            $this->view->executionID = $executionID;
            $this->view->projectID   = $projectID;
            $this->view->project     = $project;
            return $this->display();
        }

        if(!empty($planID))
        {
            $plan     = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->eq($planID)->fetch();
            $products = $this->dao->select('t1.id, t1.name, t1.type, t2.branch')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
                ->where('t1.id')->eq($plan->product)
                ->fetchAll('id');

            $productPlan    = $this->loadModel('productplan')->getPairsForStory($plan->product, $plan->branch, 'skipParent|unexpired|withMainPlan');
            $linkedBranches = array();
            $linkedBranches[$plan->product][$plan->branch] = $plan->branch;

            $this->view->linkedBranches = $linkedBranches;
        }

        if(!empty($project) and !$project->division)
        {
            $products = $this->loadModel('product')->getProducts($projectID);
            $branches = $this->project->getBranchesByProject($projectID);
            $plans    = $this->loadModel('productplan')->getGroupByProduct(array_keys($products), 'skipParent|unexpired');

            $linkedBranches = array();
            foreach($products as $productIndex => $product)
            {
                $productPlans[$productIndex] = array();
                if(isset($branches[$productIndex]))
                {
                    foreach($branches[$productIndex] as $branchID => $branch)
                    {
                        $linkedBranches[$productIndex][$branchID] = $branchID;
                        $productPlans[$productIndex] += isset($plans[$productIndex][$branchID]) ? $plans[$productIndex][$branchID] : array();
                    }
                }
            }

            $this->view->branches       = $branches;
            $this->view->linkedBranches = $linkedBranches;
        }

        if(isset($project->hasProduct) and empty($project->hasProduct))
        {
            $shadowProduct = $this->loadModel('product')->getShadowProductByProject($project->id);
            $productPlan   = $this->loadModel('productplan')->getPairs($shadowProduct->id, '0,0', 'noclosed,unexpired', true);
        }

        if(!empty($_POST))
        {
            if(isset($_POST['attribute']) and in_array($_POST['attribute'], array('request', 'design', 'review'))) unset($_POST['plans']);

            /* Filter empty plans. */
            if(!empty($_POST['plans']))
            {
                foreach($_POST['plans'] as $key => $planItem) $_POST['plans'][$key] = array_filter($_POST['plans'][$key]);
                $_POST['plans'] = array_filter($_POST['plans']);
            }

            $executionID = $this->execution->create($copyExecutionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $comment = $project->hasProduct ? join(',', $_POST['products']) : '';
            $this->loadModel('action')->create($this->objectType, $executionID, 'opened', '', $comment);

            $this->loadModel('programplan')->computeProgress($executionID, 'create');

            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $executionID));

            if($this->app->tab == 'doc')
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'projectSpace', "objectID=$executionID")));
            }

            if(!empty($projectID) and strpos(',kanban,agileplus,waterfallplus,', ",$project->model,") !== false)
            {
                $execution = $this->execution->getById($executionID);
                if($execution->type == 'kanban') $this->loadModel('kanban')->createRDKanban($execution);
            }

            if(!empty($_POST['plans']))
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=1&confirm=no")));
            }
            else
            {
                if(!empty($projectID) and $project->model == 'kanban')
                {
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                    $link = $this->config->vision != 'lite' ? $this->createLink('project', 'index', "projectID=$projectID") : $this->createLink('project', 'execution', "status=all&projectID=$projectID");
                    if($this->app->tab == 'project') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));

                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('kanban', "executionID=$executionID")));
                }
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "projectID=$projectID&executionID=$executionID")));
            }
        }

        $this->loadModel('user');
        $poUsers = $this->user->getPairs('noclosed|nodeleted|pofirst', '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PM"] = $this->config->user->moreLink;

        $pmUsers = $this->user->getPairs('noclosed|nodeleted|pmfirst', '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["PO"] = $this->config->user->moreLink;

        $qdUsers = $this->user->getPairs('noclosed|nodeleted|qdfirst', '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["QD"] = $this->config->user->moreLink;

        $rdUsers = $this->user->getPairs('noclosed|nodeleted|devfirst', '', $this->config->maxCount);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["RD"] = $this->config->user->moreLink;

        $this->loadModel('product');
        $allProducts   = $this->product->getProductPairsByProject($projectID, 'noclosed');

        $projectModel = isset($project->model) ? $project->model : '';
        if($projectModel == 'agileplus')     $projectModel = array('scrum', 'agileplus');
        if($projectModel == 'waterfallplus') $projectModel = array('waterfall', 'waterfallplus');

        $copyProjects  = $this->loadModel('project')->getPairsByProgram(isset($project->parent) ? $project->parent : '', 'noclosed', '', 'order_asc', '', $projectModel, 'multiple');
        $copyProjectID = ($projectID == 0) ? key($copyProjects) : $projectID;

        if(!empty($project->hasProduct)) $allProducts = array(0 => '') + $allProducts;
        if(isset($project->hasProduct) and $project->hasProduct == 0) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        $this->view->title               = $this->app->tab == 'execution' ? $this->lang->execution->createExec : $this->lang->execution->create;
        $this->view->position[]          = $this->view->title;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'all') : '';
        $this->view->groups              = $this->loadModel('group')->getPairs();
        $this->view->allProducts         = $allProducts;
        $this->view->acl                 = $acl;
        $this->view->plan                = $plan;
        $this->view->name                = $name;
        $this->view->code                = $code;
        $this->view->team                = $team;
        $this->view->teams               = array(0 => '') + $this->execution->getCanCopyObjects((int)$projectID);
        $this->view->allProjects         = array(0 => '') + $this->project->getPairsByModel("scrum,agileplus,waterfall,waterfallplus,kanban", 0, 'noclosed,multiple');
        $this->view->copyProjects        = $copyProjects;
        $this->view->copyExecutions      = array('' => '') + $this->execution->getList($copyProjectID, 'all', 'all', 0, 0, 0, null, false);
        $this->view->executionID         = $executionID;
        $this->view->productID           = $productID;
        $this->view->projectID           = $projectID;
        $this->view->products            = $products;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs();
        $this->view->productPlan         = array(0 => '') + $productPlan;
        $this->view->productPlans        = array(0 => '') + $productPlans;
        $this->view->whitelist           = $whitelist;
        $this->view->copyExecutionID     = $copyExecutionID;
        $this->view->branchGroups        = isset($branchGroups) ? $branchGroups : $this->execution->getBranchByProduct(array_keys($products), $projectID);
        $this->view->poUsers             = $poUsers;
        $this->view->pmUsers             = $pmUsers;
        $this->view->qdUsers             = $qdUsers;
        $this->view->rdUsers             = $rdUsers;
        $this->view->users               = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->copyExecution       = isset($copyExecution) ? $copyExecution : '';
        $this->view->from                = $this->app->tab;
        $this->view->isStage             = (isset($project->model) and (in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))));
        $this->view->project             = $project;
        $this->view->division            = !empty($project) ? $project->division : 1;
        $this->view->type                = $type;
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
    public function edit($executionID, $action = 'edit', $extra = '', $newPlans = '', $confirm = 'no')
    {
        /* Load language files and get browseExecutionLink. */
        $this->loadModel('product');
        $this->app->loadLang('program');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $browseExecutionLink = $this->createLink('execution', 'browse', "executionID=$executionID");
        $execution           = $this->execution->getById($executionID);
        $project             = $this->project->getById($execution->project);
        $branches            = $this->project->getBranchesByProject($executionID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);

        if(!empty($newPlans) and $confirm == 'yes')
        {
            $newPlans = explode(',', $newPlans);
            $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
            $this->loadModel('productplan')->linkProject($executionID, $newPlans);
            $this->productplan->linkProject($projectID, $newPlans);
            return $this->send(array('result' => 'success', 'locate' => inlink('view', "executionID=$executionID")));
        }
        elseif(!empty($newPlans))
        {
            $executionProductList  = $this->loadModel('product')->getProducts($executionID);
            $multiBranchProduct = false;
            foreach($executionProductList as $executionProduct)
            {
                if($executionProduct->type != 'normal')
                {
                    $multiBranchProduct = true;
                    break;
                }
            }

            $importEditPlanStoryTips = $multiBranchProduct ? $this->lang->execution->importBranchEditPlanStory : $this->lang->execution->importEditPlanStory;

            return print(js::confirm($importEditPlanStoryTips, inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=yes"), inlink('view', "executionID=$executionID")));
        }

        /* Set menu. */
        $this->execution->setMenu($executionID);

        if(!empty($_POST))
        {
            $oldPlans    = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->andWhere('plan')->ne(0)->fetchPairs('plan');
            $oldProducts = $this->product->getProducts($executionID, 'all', '', true, $linkedProductIdList);
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
            $newProducts  = $this->product->getProducts($executionID, 'all', '', true, $linkedProductIdList);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            $products     = $diffProducts ? join(',', $newProducts) : '';

            if($changes or $diffProducts)
            {
                $actionID = $this->loadModel('action')->create($this->objectType, $executionID, 'edited', '', $products);
                $this->action->logHistory($actionID, $changes);
            }

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'edit');

            /* Link the plan stories. */
            $oldPlans = explode(',', implode(',' ,$oldPlans));
            $newPlans = array();
            if(isset($_POST['plans']))
            {
                foreach($_POST['plans'] as $plans)
                {
                    foreach($plans as $planID)
                    {
                        if(array_search($planID, $oldPlans) === false) $newPlans[$planID] = $planID;
                    }
                }
            }

            $newPlans = array_filter($newPlans);
            if(!empty($newPlans))
            {
                $newPlans = implode(',', $newPlans);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=no")));
            }

            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($_POST['status'] == 'doing') $this->loadModel('common')->syncPPEStatus($executionID);

            /* If link from no head then reload. */
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "executionID=$executionID")));
        }

        $executions = array('' => '') + $this->executions;
        $managers   = $this->execution->getDefaultManagers($executionID);

        /* Remove current execution from the executions. */
        unset($executions[$executionID]);

        $title      = $this->lang->execution->edit . $this->lang->colon . $execution->name;
        $position[] = html::a($browseExecutionLink, $execution->name);
        $position[] = $this->lang->execution->edit;

        $allProducts = $this->product->getProducts($execution->project, 'noclosed', '', false, $linkedProductIdList);
        $allProducts = array(0 => '') + $allProducts;

        $this->loadModel('productplan');
        $productPlans     = array(0 => '');
        $linkedBranches   = array();
        $linkedBranchList = array();
        $linkedProducts   = $this->product->getProducts($executionID, 'all', '', true, $linkedProductIdList, false);
        $plans            = $this->productplan->getGroupByProduct(array_keys($linkedProducts), 'skipParent|unexpired');
        $executionStories = $this->project->getStoriesByProject($executionID);

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        $unmodifiableBranches = array();
        $linkedStoryIDList    = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->deleted ? $linkedProduct->name . "({$this->lang->product->deleted})" : $linkedProduct->name;
            $productPlans[$productID] = array();

            foreach($branches[$productID] as $branchID => $branch)
            {
                $productPlans[$productID] += isset($plans[$productID][$branchID]) ? $plans[$productID][$branchID] : array();

                $linkedBranchList[$branchID]           = $branchID;
                $linkedBranches[$productID][$branchID] = $branchID;
                if($branchID != BRANCH_MAIN and isset($plans[$productID][BRANCH_MAIN])) $productPlans[$productID] += $plans[$productID][BRANCH_MAIN];
                if(!empty($executionStories[$productID][$branchID]))
                {
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                    $linkedStoryIDList[$productID][$branchID] = $executionStories[$productID][$branchID]->storyIDList;
                }
            }
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

        $project = $this->project->getById($execution->project);
        if(!$project->hasProduct) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            $parentStage = $this->project->getByID($execution->parent, 'stage');

            $this->view->enableOptionalAttr = (empty($parentStage) or (!empty($parentStage) and $parentStage->attribute == 'mix'));
        }
        if($this->app->tab == 'project' and $project->model == 'waterfallplus')
        {
            $productID       = $this->product->getProductIDByProject($executionID);
            $parentStageList = $this->loadModel('programplan')->getParentStageList($execution->project, $executionID, $productID);
            unset($parentStageList[0]);
        }

        $this->view->title                = $title;
        $this->view->position             = $position;
        $this->view->executions           = $executions;
        $this->view->execution            = $execution;
        $this->view->project              = $project;
        $this->view->poUsers              = $poUsers;
        $this->view->pmUsers              = $pmUsers;
        $this->view->qdUsers              = $qdUsers;
        $this->view->rdUsers              = $rdUsers;
        $this->view->users                = $this->user->getPairs('nodeleted|noclosed');
        $this->view->project              = $project;
        $this->view->groups               = $this->loadModel('group')->getPairs();
        $this->view->allProducts          = $allProducts;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->linkedBranches       = $linkedBranches;
        $this->view->linkedStoryIDList    = $linkedStoryIDList;
        $this->view->branches             = $branches;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->unmodifiableBranches = $unmodifiableBranches;
        $this->view->multiBranchProducts  = $this->product->getMultiBranchPairs();
        $this->view->productPlans         = $productPlans;
        $this->view->branchGroups         = $this->execution->getBranchByProduct(array_keys($linkedProducts), $execution->project, 'noclosed', $linkedBranchList);
        $this->view->teamMembers          = $this->execution->getTeamMembers($executionID);
        $this->view->allProjects          = $this->project->getPairsByModel($project->model, 0, 'noclosed', $project->id);
        $this->view->parentStageList      = isset($parentStageList) ? $parentStageList : array();

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
        $this->app->loadLang('programplan');

        if($this->post->names)
        {
            $allChanges = $this->execution->batchUpdate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($allChanges))
            {
                foreach($allChanges as $executionID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create($this->objectType, $executionID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->executionList));
        }

        if($this->app->tab == 'project')
        {
            $projectID   = $this->session->project;
            $project     = $this->project->getById($projectID);
            $allProjects = $this->project->getPairsByModel($project->model, 0, 'noclosed', isset($projectID) ? $projectID : 0);
            $this->project->setMenu($projectID);
            $this->view->project = $project;
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->lang->execution->common = $this->lang->execution->stage;
            if($project->model == 'ipd') $this->config->execution->customBatchEditFields = 'days,teamname,desc,PO,QD,PM,RD';
        }
        else
        {
            $allProjects = $this->project->getPairsByModel('all', 0, 'noclosed', isset($projectID) ? $projectID : 0);
        }

        if(!$this->post->executionIDList) return print(js::locate($this->session->executionList, 'parent'));
        $executionIDList = $this->post->executionIDList;
        $executions      = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIDList)->fetchAll('id');
        $projects        = $this->dao->select('id,project')->from(TABLE_PROJECT)->where('id')->in($executionIDList)->fetchPairs();
        $projects        = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');

        $appendPoUsers = $appendPmUsers = $appendQdUsers = $appendRdUsers = array();
        foreach($executions as $execution)
        {
            $appendPoUsers[$execution->PO] = $execution->PO;
            $appendPmUsers[$execution->PM] = $execution->PM;
            $appendQdUsers[$execution->QD] = $execution->QD;
            $appendRdUsers[$execution->RD] = $execution->RD;
        }

        /* Set custom. */
        foreach(explode(',', $this->config->execution->customBatchEditFields) as $field) $customFields[$field] = str_replace($this->lang->executionCommon, $this->lang->execution->common, $this->lang->execution->$field);
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

        $this->view->title       = $this->lang->execution->batchEdit;
        $this->view->position[]  = $this->lang->execution->batchEdit;
        $this->view->executions  = $executions;
        $this->view->allProjects = $allProjects;
        $this->view->projects    = $projects;
        $this->view->pmUsers     = $pmUsers;
        $this->view->poUsers     = $poUsers;
        $this->view->qdUsers     = $qdUsers;
        $this->view->rdUsers     = $rdUsers;
        $this->view->from        = $this->app->tab;
        $this->display();
    }

    /**
     * Batch change status.
     *
     * @param  string    $status
     * @param  int       $projectID
     * @access public
     * @return void
     */
    public function batchChangeStatus($status, $projectID = 0)
    {
        $executionIdList = $this->post->executionIDList;
        if(is_string($executionIdList)) $executionIdList = explode(',', $executionIdList);

        $pointOutStages = $this->execution->batchChangeStatus($executionIdList, $status);
        $project        = $this->loadModel('project')->getById($projectID);

        if($pointOutStages)
        {
            $alertLang = '';

            if($status == 'wait')
            {
                /* In execution-all list or waterfall, waterfallplus project's execution list. */
                if(empty($project) or (!empty($project) and strpos($project->model, 'waterfall') !== false))
                {
                    $executionLang = (empty($project) or (!empty($project) and $project->model == 'waterfallplus')) ? $this->lang->execution->common : $this->lang->stage->common;
                    $alertLang     = sprintf($this->lang->execution->hasStartedTaskOrSubStage, $executionLang, $pointOutStages);
                }

                if(!empty($project) and strpos('agileplus,scrum', $project->model) !== false)
                {
                    $executionLang = $project->model == 'scrum' ? $this->lang->executionCommon : $this->lang->execution->common;
                    $alertLang     = sprintf($this->lang->execution->hasStartedTask, $executionLang, $pointOutStages);
                }
            }

            if($status == 'suspended') $alertLang = sprintf($this->lang->execution->hasSuspendedOrClosedChildren, $pointOutStages);

            if($status == 'closed') $alertLang = sprintf($this->lang->execution->hasNotClosedChildren, $pointOutStages);

            return print(js::alert($alertLang) . js::locate($this->session->executionList, 'parent'));
        }

        return print(js::locate($this->session->executionList, 'parent'));
    }

    /**
     * Start execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function start($executionID, $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->start($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'research'))) $this->loadModel('programplan')->computeProgress($executionID, 'start');

            $this->loadModel('common')->syncPPEStatus($executionID);
            $this->executeHooks($executionID);
            if(isonlybody() and $from == 'kanban')
            {
                return print(js::closeModal('parent.parent', '', "parent.parent.changeStatus('doing')"));
            }
            else
            {
                return print(js::reload('parent.parent'));
            }
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
     * @param  string $from
     * @access public
     * @return void
     */
    public function putoff($executionID, $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->putoff($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Delayed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($executionID);
            if(isonlybody() and $from == 'kanban')
            {
                return print(js::closeModal('parent.parent', '', "parent.parent.changeStatus('doing')"));
            }
            else
            {
                return print(js::reload('parent.parent'));
            }
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
     * @param  string $from
     * @access public
     * @return void
     */
    public function suspend($executionID, $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $this->execution->computeBurn($executionID);
            $changes = $this->execution->suspend($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'suspend');

            $this->executeHooks($executionID);
            if(isonlybody() and $from == 'kanban')
            {
                return print(js::closeModal('parent.parent', '', "parent.parent.changeStatus('suspended')"));
            }
            else
            {
                return print(js::reload('parent.parent'));
            }
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
     * @param  string $frim
     * @access public
     * @return void
     */
    public function activate($executionID, $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->execution->activate($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'activate');

            $this->executeHooks($executionID);
            if(isonlybody() and $from == 'kanban')
            {
                return print(js::closeModal('parent.parent', '', "parent.parent.changeStatus('doing')"));
            }
            else
            {
                return print(js::reload('parent.parent'));
            }
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
     * @param  string $from
     * @access public
     * @return void
     */
    public function close($executionID, $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->execution->computeBurn($executionID);
            $this->execution->close($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->loadModel('programplan')->computeProgress($executionID, 'close');

            $this->executeHooks($executionID);
            if(isonlybody() and $from == 'kanban')
            {
                return print(js::closeModal('parent.parent', '', "parent.parent.changeStatus('closed')"));
            }
            else
            {
                return print(js::reload('parent.parent'));
            }
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
        $this->app->session->set('teamList', $this->app->getURI(true), 'execution');

        $executionID = $this->execution->saveState((int)$executionID, $this->executions);
        $execution   = $this->execution->getById($executionID, true);

        $type = $this->config->vision == 'lite' ? 'kanban' : 'stage,sprint,kanban';
        if(empty($execution) || strpos($type, $execution->type) === false) return print(js::error($this->lang->notFound) . js::locate('back'));

        if(!$this->loadModel('common')->checkPrivByObject('execution', $executionID)) return print(js::error($this->lang->execution->accessDenied) . js::locate($this->createLink('execution', 'all')));

        $execution->projectInfo = $this->loadModel('project')->getByID($execution->project);

        $programList = array_filter(explode(',', $execution->projectInfo->path));
        array_pop($programList);
        $this->view->programList = $this->loadModel('program')->getPairsByList($programList);

        if($execution->type == 'kanban' and defined('RUN_MODE') and RUN_MODE == 'api') return print($this->fetch('execution', 'kanban', "executionID=$executionID"));

        $this->app->loadLang('program');

        /* Execution not found to prevent searching for .*/
        if(!isset($this->executions[$execution->id])) $this->executions = $this->execution->getPairs($execution->project, 'all', 'nocode');

        $products = $this->loadModel('product')->getProducts($execution->id);
        $linkedBranches = array();
        foreach($products as $product)
        {
            if(isset($product->branches))
            {
                foreach($product->branches as $branchID) $linkedBranches[$branchID] = $branchID;
            }
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        $this->app->loadLang('bug');

        if($execution->type == 'kanban')
        {
            $this->app->loadClass('date');

            list($begin, $end) = $this->execution->getBeginEnd4CFD($execution);
            $dateList  = date::getDateList($begin, $end, 'Y-m-d', 'noweekend');
            $chartData = $this->execution->buildCFDData($executionID, $dateList, 'task');
            if(isset($chartData['line'])) $chartData['line'] = array_reverse($chartData['line']);

            $this->view->begin = helper::safe64Encode(urlencode($begin));
            $this->view->end   = helper::safe64Encode(urlencode($end));
        }
        else
        {
            $type = 'noweekend';
            if(((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
                or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
                or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
                and strpos($type, 'delay') === false)
            {
                $type .= ',withdelay';
            }

            $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
            $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
            $endDate  = strpos($type, 'withdelay') !== false ? $deadline : $execution->end;
            list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $execution->end);

            $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
            $chartData    = $this->execution->buildBurnData($executionID, $dateList, $type, 'left', $executionEnd);
        }

        $this->executeHooks($executionID);
        if(!$execution->projectInfo->hasProduct) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        $this->view->title      = $this->lang->execution->view;

        $this->view->execution    = $execution;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->planGroups   = $this->execution->getPlans($products);
        $this->view->actions      = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', 30, 'all', 'all', $executionID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->teamMembers  = $this->execution->getTeamMembers($executionID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('execution', $executionID);
        $this->view->statData     = $this->execution->statRelatedData($executionID);
        $this->view->chartData    = $chartData;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->type         = $type;
        $this->view->features     = $this->execution->getExecutionFeatures($execution);
        $this->view->project      = $this->loadModel('project')->getByID($execution->project);

        $this->display();
    }

    /**
     * Kanban.
     *
     * @param  int     $executionID
     * @param  string  $browseType
     * @param  string  $orderBy
     * @param  string  $groupBy
     * @access public
     * @return void
     */
    public function kanban($executionID, $browseType = 'all', $orderBy = 'id_asc', $groupBy = 'default')
    {
        $this->app->loadLang('bug');
        $this->app->loadLang('kanban');

        if(empty($groupBy)) $groupBy = 'default';

        $this->lang->execution->menu = new stdclass();
        $execution = $this->commonAction($executionID);
        if($execution->type != 'kanban') return print(js::locate(inlink('view', "executionID=$executionID")));

        /* Set Session. */
        $this->session->set('execGroupBy', $groupBy);
        $this->session->set('storyList', $this->app->getURI(true), 'execution');
        $this->session->set('rdSearchValue', '');

        $features         = $this->execution->getExecutionFeatures($execution);
        $kanbanData       = $this->loadModel('kanban')->getRDKanban($executionID, $browseType, $orderBy, 0, $groupBy);
        $executionActions = array();
        foreach($this->config->execution->statusActions as $action)
        {
            if($this->execution->isClickable($execution, $action)) $executionActions[] = $action;
        }

        /* Set lane type. */
        if(!$features['story'] and !$features['qa']) $browseType = 'task';
        if(!$features['story']) unset($this->lang->kanban->group->task['story']);
        $this->session->set('execLaneType', $browseType);

        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        /* Get execution's product. */
        $productID = 0;
        $branchID  = 0;
        $products  = $this->loadModel('product')->getProducts($executionID);
        $productNames = array();
        if($products)
        {
            $productID = key($products);
            $branches  = $this->loadModel('branch')->getPairs($productID, '', $executionID);
            if($branches) $branchID = key($branches);
        }
        foreach($products as $product) $productNames[$product->id] = $product->name;

        $plans    = $this->execution->getPlans($products, 'skipParent', $executionID);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $projectID = $this->loadModel('task')->getProjectID($execution->id);

        $taskToOpen = !empty($_COOKIE['taskToOpen']) ? $_COOKIE['taskToOpen'] : 0;
        setcookie('taskToOpen', 0, time() - 3600, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $this->view->title            = $this->lang->kanban->view;
        $this->view->users            = $users;
        $this->view->regions          = $kanbanData;
        $this->view->execution        = $execution;
        $this->view->executionID      = $executionID;
        $this->view->userList         = $userList;
        $this->view->browseType       = $browseType;
        $this->view->orderBy          = $orderBy;
        $this->view->groupBy          = $groupBy;
        $this->view->productID        = $productID;
        $this->view->productNames     = $productNames;
        $this->view->productNum       = count($products);
        $this->view->branchID         = $branchID;
        $this->view->projectID        = $projectID;
        $this->view->project          = $this->loadModel('project')->getByID($projectID);
        $this->view->allPlans         = $allPlans;
        $this->view->features         = $features;
        $this->view->kanbanData       = $kanbanData;
        $this->view->executionActions = $executionActions;
        $this->view->kanban           = $this->lang->execution->kanban;
        $this->view->taskToOpen       = $taskToOpen;
        $this->display();
    }

    /**
     * Task kanban.
     *
     * @param  int    $executionID
     * @param  string $browseType story|bug|task|all
     * @param  string $orderBy
     * @param  string $groupBy
     * @access public
     * @return void
     */
    public function taskKanban($executionID, $browseType = 'all', $orderBy = 'order_asc', $groupBy = '')
    {
        if(!$this->loadModel('common')->checkPrivByObject('execution', $executionID)) return print(js::error($this->lang->execution->accessDenied) . js::locate($this->createLink('execution', 'all')));
        if(empty($groupBy)) $groupBy = 'default';

        /* Save to session. */
        $this->session->set('taskSearchValue', '');
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList', $uri, 'execution');
        $this->app->session->set('bugList',  $uri, 'qa');
        $this->app->session->set('execGroupBy', $groupBy);

        /* Load language. */
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->loadModel('kanban');

        /* Compatibility IE8. */
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) header("X-UA-Compatible: IE=EmulateIE7");

        $this->execution->setMenu($executionID);
        $execution = $this->execution->getById($executionID);
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review')))
        {
            $browseType = 'task';
            unset($this->lang->kanban->group->task['story']);
        }

        $this->app->session->set('execLaneType', $browseType);

        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'id_asc';
        $kanbanGroup = $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, '', $orderBy);

        if(empty($kanbanGroup))
        {
            $this->kanban->createExecutionLane($executionID, $browseType);
            $kanbanGroup = $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, '', $orderBy);
        }

        /* Show lanes of the attribute: no story&bug in request, no bug in design. */
        if(!isset($this->lang->execution->menu->story)) unset($kanbanGroup['story']);
        if(!isset($this->lang->execution->menu->qa))    unset($kanbanGroup['bug']);

        /* Determines whether an object is editable. */
        $canBeChanged = common::canModify('execution', $execution);

        /* Get execution's product. */
        $productID    = 0;
        $productNames = array();
        $products     = $this->loadModel('product')->getProducts($executionID);
        if($products) $productID = key($products);
        foreach($products as $product) $productNames[$product->id] = $product->name;

        $plans    = $this->execution->getPlans($products);
        $allPlans = array('' => '');
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $userList = $this->dao->select('account, realname, avatar')->from(TABLE_USER)->where('deleted')->eq(0)->fetchAll('account');
        $userList['closed'] = new stdclass();
        $userList['closed']->account  = 'Closed';
        $userList['closed']->realname = 'Closed';
        $userList['closed']->avatar   = '';

        $projectID  = $execution->project;
        $project    = $this->project->getByID($projectID);
        $hiddenPlan = $project->model !== 'scrum';

        $this->view->title        = $this->lang->execution->kanban;
        $this->view->realnames    = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyOrder   = $orderBy;
        $this->view->orderBy      = 'id_asc';
        $this->view->executionID  = $executionID;
        $this->view->productID    = $productID;
        $this->view->productNames = $productNames;
        $this->view->productNum   = count($products);
        $this->view->allPlans     = $allPlans;
        $this->view->browseType   = $browseType;
        $this->view->features     = $this->execution->getExecutionFeatures($execution);
        $this->view->kanbanGroup  = $kanbanGroup;
        $this->view->execution    = $execution;
        $this->view->groupBy      = $groupBy;
        $this->view->canBeChanged = $canBeChanged;
        $this->view->userList     = $userList;
        $this->view->hiddenPlan   = $hiddenPlan;

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
        $projects   = $this->project->getPairsByProgram('', 'noclosed');
        $executions = $this->execution->getStatData(0, 'all', 0, 0, false, 'withchild', 'id_desc');

        foreach($executions as $execution)
        {
            $execution->name = htmlspecialchars_decode($execution->name);
            $execution->team = htmlspecialchars_decode($execution->team);
        }

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
     * Set Kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setKanban($executionID)
    {
        $execution = $this->execution->getByID($executionID);

        if($_POST)
        {
            $this->execution->setKanban($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title         = $this->lang->execution->setKanban;
        $this->view->execution     = $execution;
        $this->view->laneCount     = $this->loadModel('kanban')->getLaneCount($executionID, $execution->type);
        $this->view->heightType    = $execution->displayCards > 2 ? 'custom' : 'auto';
        $this->view->displayCards  = $execution->displayCards ? $execution->displayCards : '';

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
        $project   = $this->loadModel('project')->getById($execution->project);
        $tree      = $this->execution->getTree($executionID);

        /* Save to session. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList', $uri, 'execution');
        $this->app->session->set('storyList', $uri, 'execution');
        $this->app->session->set('executionList', $uri, 'execution');
        $this->app->session->set('caseList', $uri, 'qa');
        $this->app->session->set('bugList', $uri, 'qa');

        if($type === 'json') return print(helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS));
        if($execution->lifetime == 'ops') unset($this->lang->execution->treeLevel['story']);

        $this->view->title       = $this->lang->execution->tree;
        $this->view->position[]  = html::a($this->createLink('execution', 'browse', "executionID=$executionID"), $execution->name);
        $this->view->position[]  = $this->lang->execution->tree;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->level       = $type;
        $this->view->tree        = $this->execution->printTree($tree, $project->hasProduct);
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
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
            if(!$hasData) return print(js::alert($this->lang->execution->noPrintData) . js::close());

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

            return $this->display();
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
        $stories   = $this->loadModel('story')->getExecutionStories($executionID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        /* Get execution's product. */
        $productID = 0;
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);
        if($productPairs) $productID = key($productPairs);

        $this->app->session->set('executionStoryList', $this->app->getURI(true), 'execution');

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
            elseif($type == 'kanban')
            {
                global $lang;
                $lang->executionCommon   = $lang->execution->kanban;
                include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
            }

            return print(js::confirm($tips . sprintf($this->lang->execution->confirmDelete, $this->executions[$executionID]), $this->createLink('execution', 'delete', "executionID=$executionID&confirm=yes")));
        }
        else
        {
            /* Delete execution. */
            $execution = $this->execution->getByID($executionID);
            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->eq($executionID)->exec();
            $this->loadModel('action')->create('execution', $executionID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
            $this->execution->updateUserView($executionID);
            $this->loadModel('common')->syncPPEStatus($executionID);

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID);

            $this->session->set('execution', '');
            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            return print(js::reload('parent'));
        }
    }

    /**
     * Manage products.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function manageProducts($executionID, $from = '')
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        $browseExecutionLink = $this->createLink('execution', 'browse', "executionID=$executionID");

        $this->loadModel('product');
        $execution = $this->execution->getById($executionID);
        $project   = $this->loadModel('project')->getByID($execution->project);
        if(!$project->hasProduct) return print(js::error($this->lang->project->cannotManageProducts) . js::locate('back'));
        if($project->model == 'waterfall' or $project->model == 'waterfallplus') return print(js::error(sprintf($this->lang->execution->cannotManageProducts, zget($this->lang->project->modelList, $project->model))) . js::locate('back'));

        if(!empty($_POST))
        {
            $oldProducts = $this->product->getProducts($executionID);

            if($from == 'buildCreate' && $this->session->buildCreate) $browseExecutionLink = $this->session->buildCreate;

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->product->getProducts($executionID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create($this->objectType, $executionID, 'Managed', '', !empty($_POST['products']) ? join(',', $_POST['products']) : '');

            if(isonlybody())
            {
                unset($_GET['onlybody']);
                return print(js::locate($this->createLink('build', 'create', "executionID=$executionID&productID=0&projectID=$execution->project"), 'parent'));
            }
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);

        /* Title and position. */
        $title      = $this->lang->execution->manageProducts . $this->lang->colon . $execution->name;
        $position[] = html::a($browseExecutionLink, $execution->name);
        $position[] = $this->lang->execution->manageProducts;

        $branches            = $this->project->getBranchesByProject($executionID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);
        $allProducts         = $this->product->getProductPairsByProject($execution->project, 'all', $linkedProductIdList);
        $linkedProducts      = $this->product->getProducts($execution->id, 'all', '', true, $linkedProductIdList);
        $linkedBranches      = array();
        $executionStories    = $this->project->getStoriesByProject($executionID);

        /* If the story of the product which linked the execution, you don't allow to remove the product. */
        $unmodifiableProducts = array();
        $unmodifiableBranches = array();
        $linkedStoryIDList    = array();
        $linkedBranchIdList   = array();
        foreach($linkedProducts as $productID => $linkedProduct)
        {
            $linkedBranches[$productID] = array();
            if(!isset($allProducts[$productID])) $allProducts[$productID] = $linkedProduct->name;
            foreach($branches[$productID] as $branchID => $branch)
            {
                $linkedBranches[$productID][$branchID] = $branchID;
                $linkedBranchIdList[$branchID] = $branchID;
                if(!empty($executionStories[$productID][$branchID]))
                {
                    array_push($unmodifiableProducts, $productID);
                    array_push($unmodifiableBranches, $branchID);
                    $linkedStoryIDList[$productID][$branchID] = $executionStories[$productID][$branchID]->storyIDList;
                }
            }
        }

        /* Assign. */
        $this->view->title                = $title;
        $this->view->position             = $position;
        $this->view->allProducts          = $allProducts;
        $this->view->execution            = $execution;
        $this->view->linkedProducts       = $linkedProducts;
        $this->view->unmodifiableProducts = $unmodifiableProducts;
        $this->view->unmodifiableBranches = $unmodifiableBranches;
        $this->view->linkedBranches       = $linkedBranches;
        $this->view->linkedStoryIDList    = $linkedStoryIDList;
        $this->view->branchGroups         = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal|noclosed', $linkedBranchIdList);
        $this->view->allBranches          = $this->execution->getBranchByProduct(array_keys($allProducts), $execution->project, 'ignoreNormal');

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
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('team', $executionID, 'managedTeam');
            $link = $this->session->teamList ? $this->session->teamList : $this->createLink('execution', 'team', "executionID=$executionID");
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => $link));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $execution = $this->execution->getById($executionID);
        $users     = $this->user->getPairs('noclosed|nodeleted|devfirst');
        $roles     = $this->user->getUserRoles(array_keys($users));
        $deptUsers = empty($dept) ? array() : $this->dept->getDeptUserPairs($dept);

        $currentMembers = $this->execution->getTeamMembers($executionID);
        $members2Import = $this->execution->getMembers2Import($team2Import, array_keys($currentMembers));
        $teams2Import   = $this->loadModel('personnel')->getCopiedObjects($executionID, 'sprint', true);
        $teams2Import   = array('' => '') + $teams2Import;

        /* Append users for get users. */
        $appendUsers = array();
        foreach($currentMembers as $member) $appendUsers[$member->account] = $member->account;
        foreach($members2Import as $member) $appendUsers[$member->account] = $member->account;
        foreach($deptUsers as $deptAccount => $userName) $appendUsers[$deptAccount] = $deptAccount;

        $users = $this->user->getPairs('noclosed|nodeleted|devfirst', $appendUsers);
        $roles = $this->user->getUserRoles(array_keys($users));

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["accounts[]"] = $this->config->user->moreLink;

        if($execution->type == 'kanban') $this->lang->execution->copyTeamTitle = str_replace($this->lang->execution->common, $this->lang->execution->kanban, $this->lang->execution->copyTeamTitle);

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
        if($confirm == 'no') return print(js::confirm($this->lang->execution->confirmUnlinkMember, $this->inlink('unlinkMember', "executionID=$executionID&userID=$userID&confirm=yes")));

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
        return print(js::locate($this->inlink('team', "executionID=$executionID"), 'parent'));
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
     * @param  string $extra
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function linkStory($objectID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1, $extra = '', $storyType = 'story')
    {
        $this->loadModel('story');
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('branch');

        /* Init objectID */
        $originObjectID = $objectID;

        /* Transfer object id when version lite */
        if($this->config->vision == 'lite')
        {
            $kanban   = $this->project->getByID($objectID, 'kanban');
            $objectID = $kanban->project;
        }

        /* Get projects, executions and products. */
        $object     = $this->project->getByID($objectID, 'project,sprint,stage,kanban');
        $products   = $this->product->getProducts($objectID);
        $queryID    = ($browseType == 'bySearch') ? (int)$param : 0;
        $browseLink = $this->createLink('execution', 'story', "executionID=$objectID&storyType=$storyType");
        if($this->app->tab == 'project' and $object->multiple) $browseLink = $this->createLink('projectstory', 'story', "objectID=$objectID&productID=0&branch=0&browseType=&param=0&storyType=$storyType");
        if($object->type == 'kanban' && !$object->hasProduct) $this->lang->productCommon = $this->lang->project->common;

        $this->session->set('storyList', $this->app->getURI(true), $this->app->tab); // Save session.

        /* Only execution can have no products. */
        if(empty($products))
        {
            echo js::alert($this->lang->execution->errorNoLinkedProducts);
            return print(js::locate($this->createLink('execution', 'manageproducts', "executionID=$objectID")));
        }

        if(!empty($_POST))
        {
            if($object->type != 'project' and $object->project != 0) $this->execution->linkStory($object->project, array(), array(), '', array(), $storyType);
            $this->execution->linkStory($objectID, array(), array(), $extra, array(), $storyType);

            if(isonlybody())
            {
                if($this->app->tab == 'execution')
                {
                    $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
                    $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
                    if($object->type == 'kanban')
                    {
                        $kanbanData = $this->loadModel('kanban')->getRDKanban($objectID, $execLaneType, 'id_desc', 0, $execGroupBy);
                        $kanbanData = json_encode($kanbanData);
                        return print(js::closeModal('parent', '', "parent.updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $kanbanData = $this->loadModel('kanban')->getExecutionKanban($objectID, $execLaneType, $execGroupBy);
                        $kanbanType = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData = $kanbanData[$kanbanType];
                        $kanbanData = json_encode($kanbanData);
                        return print(js::closeModal('parent', '', "parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return print(js::reload('parent'));
                }
            }

            return print(js::locate($browseLink));
        }

        if($object->type == 'project')
        {
            $this->project->setMenu($object->id);
        }
        elseif($object->type == 'sprint' or $object->type == 'stage' or $object->type == 'kanban')
        {
            $this->execution->setMenu($object->id);
        }

        /* Set modules and branches. */
        $modules      = array();
        $branchIDList = array(BRANCH_MAIN);
        $branches     = $this->project->getBranchesByProject($objectID);
        $productType  = 'normal';

        if(defined('TUTORIAL'))
        {
            $modules = $this->loadModel('tutorial')->getModulePairs();
        }
        else
        {
            foreach($products as $product)
            {
                $productModules = $this->tree->getOptionMenu($product->id, 'story', 0, array_keys($branches[$product->id]));
                foreach($productModules as $branch => $branchModules)
                {
                    foreach($branchModules as $moduleID => $moduleName) $modules[$moduleID] = ((count($products) >= 2 and $moduleID != 0) ? $product->name : '') . $moduleName;
                }
                if($product->type != 'normal')
                {
                    $productType = $product->type;
                    if(isset($branches[$product->id]))
                    {
                        foreach($branches[$product->id] as $branchID => $branch) $branchIDList[$branchID] = $branchID;
                    }
                }
            }
        }

        if($storyType == 'requirement')
        {
            $this->app->loadLang('projectstory');
            $this->lang->story->title               = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->projectstory->whyNoStories = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->projectstory->whyNoStories);
            $this->lang->execution->linkStory       = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->linkStory);
            if(isset($this->config->product->search['fields']['stage'])) unset($this->config->product->search['fields']['stage']);
        }

        /* Build the search form. */
        $actionURL    = $this->createLink($this->app->rawModule, 'linkStory', "objectID=$objectID&browseType=bySearch&queryID=myQueryID&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&extra=&storyType=$storyType");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'linkStory', $object, $storyType);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch('', '', $queryID, 'id', $objectID, $storyType);
        }
        else
        {
            $status     = $storyType == 'story' ? 'active' : ($object->model == 'ipd' ? 'launched' : 'active,launched');
            $allStories = $this->story->getProductStories(array_keys($products), $branchIDList, $moduleID = '0', $status, $storyType, 'id_desc', $hasParent = false, '', $pager = null);
        }

        $linkedStories = $this->story->getExecutionStoryPairs($objectID, 0, 'all', 0, 'full', 'all', $storyType);
        foreach($allStories as $id => $story)
        {
            if(isset($linkedStories[$story->id])) unset($allStories[$id]);
            if($story->parent < 0) unset($allStories[$id]);

            if(!isset($modules[$story->module]))
            {
                $storyModule = $this->tree->getModulesName($story->module);
                $productName = count($products) > 1 ? $products[$story->product]->name : '';
                $modules[$story->module] = $productName . zget($storyModule, $story->module, '');
            }
        }

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($allStories);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $allStories = array_chunk($allStories, $pager->recPerPage);

        $project = $object;
        if(strpos('sprint,stage,kanban', $object->type) !== false) $project = $this->loadModel('project')->getByID($object->project);

        /* Assign. */
        $this->view->title        = $object->name . $this->lang->colon . $this->lang->execution->linkStory;
        $this->view->position[]   = html::a($browseLink, $object->name);
        $this->view->position[]   = $this->lang->execution->linkStory;
        $this->view->objectID     = $originObjectID;
        $this->view->object       = $object;
        $this->view->products     = $products;
        $this->view->allStories   = empty($allStories) ? $allStories : $allStories[$pageID - 1];
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->productType  = $productType;
        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->branchGroups = $branchGroups;
        $this->view->browseLink   = $browseLink;
        $this->view->project      = $project;
        $this->view->storyType    = $storyType;
        if($this->config->edition == 'ipd') $this->view->roadmaps = $this->loadModel('roadmap')->getPairs(array_keys($products));

        $this->display();
    }

    /**
     * Unlink a story.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  string $confirm    yes|no
     * @param  string $from taskkanban
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function unlinkStory($executionID, $storyID, $confirm = 'no', $from = '', $laneID = 0, $columnID = 0)
    {
        if($confirm == 'no')
        {
            $tip = $this->app->rawModule == 'projectstory' ? $this->lang->execution->confirmUnlinkExecutionStory : $this->lang->execution->confirmUnlinkStory;

            $story = $this->loadModel('story')->getByID($storyID);
            if($story->type == 'requirement') $tip = str_replace($this->lang->SRCommon, $this->lang->URCommon, $tip);

            return print(js::confirm($tip, $this->createLink('execution', 'unlinkstory', "executionID=$executionID&storyID=$storyID&confirm=yes&from=$from&laneID=$laneID&columnID=$columnID")));
        }
        else
        {
            $execution = $this->execution->getByID($executionID);
            $this->execution->unlinkStory($executionID, $storyID, $laneID, $columnID);
            if($execution->type == 'kanban')
            {
                /* Fix bug #29171. */
                $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($execution->parent)->fetchAll('id');
                $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
                if(empty($executionStories)) $this->execution->unlinkStory($execution->parent, $storyID, $laneID, $columnID);
            }

            /* if kanban then reload and if ajax request then send result. */
            if(helper::isAjaxRequest())
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

            $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
            if($this->app->tab == 'execution' and $execution->type == 'kanban')
            {
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban($kanbanData)"));
            }
            elseif($from == 'taskkanban')
            {
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban(\"story\", $kanbanData)"));
            }

            return print(js::reload('parent'));
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
        return print(js::locate($this->createLink('execution', 'story', "executionID=$executionID")));
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

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id');
            if($user) $account = $user->account;
        }
        $period     = $type == 'account' ? 'all'  : $type;
        $date       = empty($date) ? '' : date('Y-m-d', $date);
        $actions    = $this->loadModel('action')->getDynamic($account, $period, $orderBy, 50, 'all', 'all', $executionID, $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction, $type);

        if(empty($recTotal)) $recTotal = count($dateGroups) < 2 ? count($actions) : $this->action->getDynamicCount();

        $execution = $this->execution->getByID($executionID);

        /* Assign. */
        $this->view->title        = $execution->name . $this->lang->colon . $this->lang->execution->dynamic;
        $this->view->userIdPairs  = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted|useid');
        $this->view->accountPairs = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->executionID  = $executionID;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->account      = $account;
        $this->view->param        = $param;
        $this->view->dateGroups   = $dateGroups;
        $this->view->direction    = $direction;
        $this->view->recTotal     = $recTotal;
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
        $products = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        return print(html::select('product', $products, '', 'class="form-control"'));
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
            return print(json_encode($users));
        }
        else
        {
            $assignedTo = isset($users[$assignedTo]) ? $assignedTo : '';
            return print(html::select('assignedTo', $users, $assignedTo, "class='form-control'"));
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
        $type = $this->dao->findById($objectID)->from(TABLE_PROJECT)->fetch('type');
        if($type != 'project') $type = 'execution';

        $users   = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $members = $this->user->getTeamMemberPairs($objectID, $type);

        return print(html::select('teamMembers[]', $users, array_keys($members), "class='form-control picker-select' multiple"));
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
        $execution = $this->execution->getById($executionID);
        $projectID = $execution->project;

        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->projectID   = $projectID;
        $this->display('execution', 'tips');
    }

    /**
     * Drop menu page.
     *
     * @param  int    $executionID
     * @param  string $module
     * @param  string $method
     * @param  mixed  $extra
     * @param  mixed  $type
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($executionID, $module, $method, $extra, $type = '')
    {
        $onlyClosed = $type == 'closed';

        $this->view->link        = $this->execution->getLink($module, $method, $extra);
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->view->executionID = $executionID;
        $this->view->extra       = $extra;
        $this->view->onlyClosed  = $onlyClosed;

        $cacheProjectsKey   = $this->config->cacheKeys->execution->ajaxGetDropMenuProjects;
        $cacheExecutionsKey = $this->config->cacheKeys->execution->ajaxGetDropMenuExecutions;

        if(helper::isCacheEnabled())
        {
            $projects          = $this->cache->get($cacheProjectsKey);
            $projectExecutions = $this->cache->get($cacheExecutionsKey);

            if(!empty($projects) && !empty($projectExecutions))
            {
                $this->view->projects          = $projects;
                $this->view->projectExecutions = $projectExecutions;
                $this->display();
                return;
            }
        }

        $projects = $this->loadModel('program')->getProjectList(0, 'all', 0, 'order_asc', null, 0, 0, true);

        $executions = $this->dao->select('id,parent,project,grade,status,name,type,PM,path')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('multiple')->eq('1')
            ->andWhere('type')->in('sprint,stage,kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($onlyClosed)
            ->andWhere('status')
            ->in('done,closed')->fi()
            ->beginIF(!$onlyClosed)
            ->andWhere('status')
            ->notin('done,closed')->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->orderBy('order_asc')
            ->fetchAll('id');

        $executionGroups = array();
        $childExecutions = array();
        foreach($executions as $executionID => $execution)
        {
            if(!isset($executionGroups[$execution->project])) $executionGroups[$execution->project] = array();
            $executionGroups[$execution->project][$executionID] = $execution;

            if(!isset($childExecutions[$execution->parent])) $childExecutions[$execution->parent] = array();
            $childExecutions[$execution->parent][$executionID] = $execution;
        }

        $teams = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('root')->in($this->app->user->view->sprints)
            ->andWhere('type')->eq('execution')
            ->fetchGroup('root', 'account');

        $projectPairs      = array();
        $orderedExecutions = array();
        foreach($projects as $project)
        {
            $executions = zget($executionGroups, $project->id, array());
            if(isset($project->model) and $project->model == 'waterfall') ksort($executions);

            $parents         = array();
            $firstGradeExecs = array();
            foreach($executions as $execution)
            {
                $parents[$execution->parent] = $execution->parent;
                if($execution->grade == 1) $firstGradeExecs[$execution->id] = $execution->id;
            }

            $executions = $this->execution->resetExecutionSorts($executions, $firstGradeExecs, $childExecutions);
            foreach($executions as $execution)
            {
                /* Only show leaf executions. */
                if(isset($parents[$execution->id])) continue;

                $execution->teams = zget($teams, $execution->id, array());
                $orderedExecutions[$execution->id] = $execution;
            }

            $projectPairs[$project->id] = $project->name;
        }

        $nameList = $this->execution->getFullNameList($orderedExecutions);

        $projectExecutions = array();
        foreach($orderedExecutions as $execution)
        {
            $execution->name = $nameList[$execution->id];
            $projectExecutions[$execution->project][] = $execution;
        }

        if($this->config->cache->enable)
        {
            $this->cache->set($cacheProjectsKey, $projectPairs);
            $this->cache->set($cacheExecutionsKey, $projectExecutions);
        }

        $this->view->projects          = $projectPairs;
        $this->view->projectExecutions = $projectExecutions;
        $this->view->namePinyinList    = common::convert2Pinyin($nameList);
        $this->display();
    }

    /**
     * 获取执行下拉列表用于切换不同的执行。
     * Drop menu page.
     *
     * @param  int    $executionID 已经打开的页面对应的执行ID
     * @param  string $module 链接里要访问的模块
     * @param  string $method 链接里要访问的方法
     * @access public
     * @return void
     */
    public function ajaxGetDropMenuData(int $executionID, string $module, string $method)
    {
        $this->ajaxGetDropMenu($executionID, $module, $method, '');
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
        if(strpos($orderBy, 'order_desc') !== false) $idList = array_reverse($idList);
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
        $this->view->story        = $this->story->getById($storyID);
        $this->display();
    }

    /**
     * All execution.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $productID
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function all($status = 'undone', $orderBy = 'order_asc', $productID = 0, $param = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('my');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $this->loadModel('datatable');
        $this->loadModel('product');

        $from = $this->app->tab;
        if($from == 'execution') $this->session->set('executionList', $this->app->getURI(true), 'execution');

        if($this->app->viewType == 'mhtml')
        {
            $executionID = $this->execution->saveState(0, $this->executions);
            $this->execution->setMenu($executionID);
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('program')->refreshStats(); // Refresh stats fields of projects.

        $queryID   = ($status == 'bySearch') ? (int)$param : 0;
        $actionURL = $this->createLink('execution', 'all', "status=bySearch&orderBy=$orderBy&productID=$productID&param=myQueryID");
        $this->execution->buildSearchForm($queryID, $actionURL);

        $executionStats = $this->execution->getStatData(0, $status, $productID, 0, false, $queryID, $orderBy, $pager);

        $this->view->title            = $this->lang->execution->allExecutions;
        $this->view->position[]       = $this->lang->execution->allExecutions;
        $this->view->executionStats   = $executionStats;
        $this->view->allExecutionsNum = $this->execution->getExecutionCounts(0, 'all');
        $this->view->productList      = $this->product->getProductPairsByProject(0);
        $this->view->productID        = $productID;
        $this->view->pager            = $pager;
        $this->view->orderBy          = $orderBy;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects         = array('') + $this->project->getPairsByProgram();
        $this->view->status           = $status;
        $this->view->from             = $from;
        $this->view->param            = $param;
        $this->view->showBatchEdit    = $this->cookie->showExecutionBatchEdit;

        $this->display();
    }

    /**
     * Get white list personnel.
     *
     * @param  int    $executionID
     * @param  string $module
     * @param  string $objectType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function whitelist($executionID = 0, $module = 'execution', $objectType = 'sprint', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        $execution = $this->execution->getByID($executionID);
        if(!empty($execution->acl) and $execution->acl != 'private') $this->locate($this->createLink('execution', 'task', "executionID=$executionID"));

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

        $execution = $this->execution->getByID($executionID);
        if(!empty($execution->acl) and $execution->acl != 'private') $this->locate($this->createLink('execution', 'task', "executionID=$executionID"));

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
     * Flatten Object Array.
     *
     * @param array $array
     * @access public
     * @return void
     */
    public function flattenObjectArray($array = array())
    {
        $result = array();

        foreach ($array as $key => $object) {
            $result[$object->id] = $object;

            if (isset($object->children) && is_array($object->children)) {
                $result = array_replace($result, $this->flattenObjectArray($object->children));
            }
        }

        return $result;
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
            $project   = $this->project->getByID($projectID);
            if($projectID) $this->project->setMenu($projectID);

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $executionConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                if($fieldName == 'name' and $this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $fields['method'] = $executionLang->method;

                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($executionLang, $fieldName);
                unset($fields[$key]);
            }

            $executionStats = $this->execution->getStatData($projectID, $status == 'byproduct' ? 'all' : $status, $productID, 0, false, 'withchild', 'order_asc');
            $executionStats = $this->flattenObjectArray($executionStats);

            $users = $this->loadModel('user')->getPairs('noletter');
            foreach($executionStats as $i => $execution)
            {
                $execution->PM            = zget($users, $execution->PM);
                $execution->status        = isset($execution->delay) ? $executionLang->delayed : $this->processStatus('execution', $execution);
                $execution->progress     .= '%';
                $execution->name          = isset($execution->title) ? $execution->title : $execution->name;
                if(isset($executionStats[$execution->parent])) $execution->name = $executionStats[$execution->parent]->name . '/' . $execution->name;
                if($this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $execution->method = zget($executionLang->typeList, $execution->type);

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->post->checkedItem;
                    if(strpos(",$checkedItem,", ",{$execution->id},") === false) unset($executionStats[$i]);
                }
            }

            if($this->config->edition != 'open') list($fields, $executionStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $executionStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $executionStats);
            $this->post->set('kind', $this->lang->execution->common);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->loadModel('project');
        $project = $this->project->getByID($this->session->project);
        if(!empty($project->model) and $project->model == 'waterfall') $this->lang->executionCommon = $this->lang->project->stage;

        $this->view->fileName = (in_array($status, array('all', 'undone')) ? $this->lang->execution->$status : $this->lang->execution->statusList[$status]) . $this->lang->execution->common;

        $this->display();
    }

    /**
     * Doc for compatible.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function doc($executionID, $libID = 0, $moduleID = 0, $browseType = 'all', $orderBy = 'status,id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('doc', 'tableContents', "type=execution&objectID=$executionID&libID=$libID&moduleID=$moduleID&browseType=$browseType&orderBy=$orderBy&param=$param&recTotal=$recTotal&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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

            return print(js::reload('parent.parent'));
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
        if($confirm != 'yes') return print(js::confirm($this->lang->kanbanSetting->noticeReset, inlink('ajaxResetKanban', "executionID=$executionID&confirm=yes")));

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

        return print(js::reload('parent.parent'));
    }

    /**
     * Import stories by plan.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @param  string $fromMethod
     * @param  string $extra
     * @param  string $param
     * @access public
     * @return void
     */
    public function importPlanStories($executionID, $planID, $productID = 0, $fromMethod = 'story', $extra = '', $param = '')
    {
        $planStories = $planProducts = array();
        $planStory   = $this->loadModel('story')->getPlanStories($planID);
        $execution   = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        $count = 0;
        if(!empty($planStory))
        {
            $projectProducts = $this->loadModel('project')->getBranchesByProject($executionID);
            foreach($planStory as $id => $story)
            {
                $projectBranches = zget($projectProducts, $story->product, array());
                if($story->status != 'active' or (!empty($story->branch) and !empty($projectBranches) and !isset($projectBranches[$story->branch])))
                {
                    $count++;
                    unset($planStory[$id]);
                    continue;
                }
                $planProducts[$story->id] = $story->product;
            }

            $projectID   = $this->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch('project');
            $planStories = array_keys($planStory);

            if($executionID != $projectID) $this->execution->linkStory($projectID, $planStories, $planProducts);
            $this->execution->linkStory($executionID, $planStories, $planProducts, $extra);
        }

        $moduleName = 'execution';
        if(empty($param))  $param = "executionID=$executionID";
        if(!empty($param)) $param = str_replace(array(',', ' '), array('&', ''), $param);
        if($execution->type == 'project')
        {
            $moduleName = 'projectstory';
            $param      = "projectID=$executionID&productID=$productID";
        }

        if($execution->type == 'kanban')
        {
            global $lang;
            $lang->executionCommon = $lang->execution->kanban;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        $multiBranchProduct = false;
        if($productID)
        {
            $product = $this->loadModel('product')->getByID($productID);
            if($product->type != 'normal') $multiBranchProduct = true;
        }
        else
        {
            $executionProductList = $this->loadModel('product')->getProducts($executionID);
            foreach($executionProductList as $executionProduct)
            {
                if($executionProduct->type != 'normal')
                {
                    $multiBranchProduct = true;
                    break;
                }
            }
        }
        $importPlanStoryTips = $multiBranchProduct ? $this->lang->execution->haveBranchDraft : $this->lang->execution->haveDraft;

        $haveDraft = sprintf($importPlanStoryTips, $count);
        if(!$execution->multiple or $moduleName == 'projectstory') $haveDraft = str_replace($this->lang->executionCommon, $this->lang->projectCommon, $haveDraft);
        if($count != 0) echo js::alert($haveDraft) . js::locate($this->createLink($moduleName, $fromMethod, $param));
        return print(js::locate(helper::createLink($moduleName, $fromMethod, $param)));
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
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, `type`, shadow')->fetch();
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
        return print(html::select("group", $groups, $group, 'class="form-control chosen" data-max_drop_width="215"'));
    }

    /**
     * Ajax update kanban.
     *
     * @param  int    $executionID
     * @param  string $enterTime
     * @param  string $browseType
     * @param  string $groupBy
     * @param  string $from execution|RD
     * @param  string $searchValue
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function ajaxUpdateKanban($executionID = 0, $enterTime = '', $browseType = '', $groupBy = '', $from = 'execution', $searchValue = '', $orderBy = 'id_asc')
    {
        $this->loadModel('kanban');
        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'pri_asc';

        $enterTime = date('Y-m-d H:i:s', $enterTime);
        $lastEditedTime = $this->dao->select("max(lastEditedTime) as lastEditedTime")->from(TABLE_KANBANLANE)->where('execution')->eq($executionID)->fetch('lastEditedTime');

        if($from == 'execution') $this->session->set('taskSearchValue', $searchValue);
        if($from == 'RD')        $this->session->set('rdSearchValue', $searchValue);
        if(strtotime($lastEditedTime) < 0 or $lastEditedTime > $enterTime or $groupBy != 'default' or !empty($searchValue))
        {
            $kanbanGroup = $from == 'execution' ? $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, $searchValue, $orderBy) : $this->kanban->getRDKanban($executionID, $browseType, $orderBy, 0, $groupBy, $searchValue);
            return print(json_encode($kanbanGroup));
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

    /**
     * Ajax get copy project executions.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetCopyProjectExecutions($projectID = 0)
    {
        $executions = $this->execution->getList($projectID, 'all', 'all', 0, 0, 0, null, false);
        echo json_encode($executions);
    }

    /**
     * AJAX: Get execution kanban data.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetExecutionKanban($executionID)
    {
        $execution = $this->execution->getByID($executionID);
        if($this->app->tab == 'execution' and $execution->type == 'kanban')
        {
            $execLaneType  = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy   = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
            $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
            $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
            echo json_encode($kanbanData);
        }
    }
}
