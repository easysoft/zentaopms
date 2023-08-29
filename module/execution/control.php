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
        if($this->app->upgrading || !isset($this->app->user)) return false;

        $mode = $this->app->tab == 'execution' ? 'multiple' : '';
        $this->executions = $this->execution->getPairs(0, 'all', "nocode,{$mode}");
        $skipCreateStep   = array('computeburn', 'ajaxgetdropmenu', 'executionkanban', 'ajaxgetteammembers', 'all');
        if(in_array($this->methodName, $skipCreateStep) && $this->app->tab == 'execution') return false;
        if($this->executions || $this->methodName == 'index' || $this->methodName == 'create' || $this->app->getViewType() == 'mhtml') return false;

        $this->locate($this->createLink('execution', 'create'));
    }

    /**
     * 执行任务列表页。
     * Browse task list for a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function browse(int $executionID = 0)
    {
        $this->locate($this->createLink($this->moduleName, 'task', "executionID=$executionID"));
    }

    /**
     * 设置页面公共数据。
     * Common actions.
     *
     * @param  int          $executionID
     * @param  string       $extra
     * @access public
     * @return object|false
     */
    public function commonAction(int $executionID = 0, string $extra = ''): object|false
    {
        /* Get executions and products info. */
        $executionID     = $this->execution->checkAccess($executionID, $this->executions);
        $execution       = $this->execution->getById($executionID);
        $products        = $this->loadModel('product')->getProducts($executionID);
        $childExecutions = $this->execution->getChildExecutions($executionID);
        $teamMembers     = $this->execution->getTeamMembers($executionID);
        $actions         = $this->loadModel('action')->getList($this->objectType, $executionID);
        $project         = $this->loadModel('project')->getByID($execution->project);

        /* Set menu. */
        $this->execution->setMenu($executionID, $buildID = 0, $extra);

        /* Assign view data. */
        if($this->app->tab == 'project') $this->view->projectID = $executionID;
        $this->view->hidden          = !empty($project->hasProduct) ? '' : 'hide';
        $this->view->executions      = $this->executions;
        $this->view->execution       = $execution;
        $this->view->executionID     = $executionID;
        $this->view->childExecutions = $childExecutions;
        $this->view->products        = $products;
        $this->view->teamMembers     = $teamMembers;

        return $execution;
    }

    /**
     * 执行下任务列表页。
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
    public function task(int $executionID = 0, string $status = 'unclosed', int $param = 0, string $orderBy = '', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        /* Set browse type. */
        $browseType = strtolower($status);
        $execution  = $this->commonAction($executionID, $status);
        if($execution->type == 'kanban' && $this->config->vision != 'lite' && $this->app->getViewType() != 'json') $this->locate($this->createLink('execution', 'kanban', "executionID=$executionID"));

        /* Save the recently five executions visited in the cookie. */
        $executionID = $execution->id;
        $this->executionZen->setRecentExecutions($executionID);

        /* Append id for second sort and process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionTaskOrder ? $this->cookie->executionTaskOrder : 'status,id_desc';
        $orderBy = common::appendOrder($orderBy);
        $this->executionZen->setTaskPageStorage($executionID, $orderBy, $browseType, (int)$param);

        /* Set queryID, moduleID and productID. */
        $queryID = $moduleID = $productID = 0;
        if($browseType == 'bysearch')  $queryID   = (int)$param;
        if($browseType == 'bymodule')  $moduleID  = (int)$param;
        if($browseType == 'byproduct') $productID = (int)$param;
        if(!in_array($browseType, array('bysearch', 'bymodule', 'byproduct')))
        {
            $moduleID  = $this->cookie->moduleBrowseParam;
            $productID = $this->cookie->productBrowseParam;
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $tasks = $this->execution->getTasks($productID, $executionID, $this->executions, $browseType, $queryID, $moduleID, $orderBy, $pager);

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'task', "executionID=$executionID&status=bySearch&param=myQueryID");
        $this->config->execution->search['onMenuBar'] = 'yes';
        if(!$execution->multiple) unset($this->config->execution->search['fields']['execution']);
        $this->execution->buildTaskSearchForm($executionID, $this->executions, $queryID, $actionURL);

        /* team member pairs. */
        $memberPairs = array();
        foreach($this->view->teamMembers as $key => $member) $memberPairs[$key] = $member->realname;
        $memberPairs = $this->loadModel('user')->processAccountSort($memberPairs);

        /* Append branches to task. */
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($this->view->products));
        foreach($tasks as $task)
        {
            if(isset($branchGroups[$task->product][$task->branch])) $task->branch = $branchGroups[$task->product][$task->branch];
        }

        $showModule = empty($this->config->execution->task->allModule) ? '' : 'allModule';
        $this->view->title       = $execution->name . $this->lang->colon . $this->lang->execution->task;
        $this->view->tasks       = $tasks;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->status      = $status;
        $this->view->param       = $param;
        $this->view->moduleID    = $moduleID;
        $this->view->modules     = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, $showModule);
        $this->view->moduleTree  = $this->tree->getTaskTreeMenu($executionID, $productID, 0, array('treeModel', 'createTaskLink'), $showModule);
        $this->view->memberPairs = $memberPairs;
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

        /* Get tasks and group them. */
        if(empty($groupBy))$groupBy = 'story';
        if($groupBy == 'story' and $execution->lifetime == 'ops')
        {
            $groupBy = 'status';
            unset($this->lang->execution->groups['story']);
        }

        $sort        = common::appendOrder($groupBy);
        $tasks       = $this->loadModel('task')->getExecutionTasks($executionID, 0, 'all', array(), $sort);
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

        $this->view->title       = $execution->name . $this->lang->colon . $this->lang->execution->task;
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
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importTask(int $toExecution, int $fromExecution = 0, string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        if(!empty($_POST))
        {
            $taskList   = form::batchData($this->config->execution->form->importTask)->get();
            $dateExceed = $this->execution->importTask($toExecution, array_column($taskList, 'taskIdList'));

            $result = array('load' => true);
            if($dateExceed) $result['message'] = sprintf($this->lang->task->error->dateExceed, implode(',', $dateExceed));
            return $this->sendSuccess($result);
        }

        $execution   = $this->commonAction($toExecution);
        $toExecution = $execution->id;
        $project     = $this->loadModel('project')->getByID($execution->project);
        $branches    = $this->execution->getBranches($toExecution);
        $tasks       = $this->execution->getTasks2Imported($toExecution, $branches, $orderBy);
        $executions  = $this->execution->getToImport(array_keys($tasks), $execution->type, $project->model);
        unset($executions[$toExecution]);

        $tasks2Imported = array();
        if($fromExecution == 0)
        {
            foreach($executions as $executionID => $executionName) $tasks2Imported = array_merge($tasks2Imported, $tasks[$executionID]);
        }
        else
        {
            $tasks2Imported = zget($tasks, $fromExecution, array());
        }

        /* Pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(count($tasks2Imported), $recPerPage, $pageID);

        $tasks2ImportedList = array_chunk($tasks2Imported, $pager->recPerPage, true);
        $tasks2ImportedList = empty($tasks2ImportedList) ? $tasks2ImportedList : $tasks2ImportedList[$pageID - 1];
        $tasks2ImportedList = $this->loadModel('task')->processTasks($tasks2ImportedList);

        $this->view->title          = $execution->name . $this->lang->colon . $this->lang->execution->importTask;
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->executions     = $executions;
        $this->view->fromExecution  = $fromExecution;
        $this->view->tasks2Imported = $tasks2ImportedList;
        $this->view->memberPairs    = $this->loadModel('user')->getPairs('noletter|pofirst');
        $this->display();
    }

    /**
     * Import from Bug.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importBug(int $executionID = 0, string $browseType = 'all', int $param = 0, int $recTotal = 0, int $recPerPage = 30, int $pageID = 1)
    {
        $this->app->loadConfig('task');

        if(!empty($_POST))
        {
            $this->execution->importBug($executionID);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true));
        }

        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        $this->loadModel('bug');
        $executions = $this->execution->getPairs(0, 'all', 'nocode');
        $this->execution->setMenu($executionID);

        /* Load pager. */
        $this->app->loadClass('pager', true);
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
                if($this->session->importBugQuery === false) $this->session->set('importBugQuery', ' 1 = 1');
            }
            $bugQuery = str_replace("`product` = 'all'", "`product`" . helper::dbIN(array_keys($products)), $this->session->importBugQuery); // Search all execution.
            $bugs     = $this->execution->getSearchBugs($products, $executionID, $bugQuery, $pager, 'id_desc');
        }

        $execution = $this->execution->getByID($executionID);
        $project   = $this->loadModel('project')->getByID($execution->project);

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
        $this->config->bug->search['params']['confirmed']['values'] = $this->lang->bug->confirmedList;

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
        $this->lang->story->linkStory = str_replace($this->lang->URCommon, $this->lang->SRCommon, $this->lang->story->linkStory);
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
        $productID = 0;
        helper::setcookie('storyPreExecutionID', $executionID);
        if($this->cookie->storyPreExecutionID != $executionID)
        {
            $_COOKIE['storyModuleParam'] = $_COOKIE['storyProductParam'] = $_COOKIE['storyBranchParam'] = 0;
            helper::setcookie('storyModuleParam',  0);
            helper::setcookie('storyProductParam', 0);
            helper::setcookie('storyBranchParam',  0);
        }
        if($type == 'bymodule')
        {
            $module    = $this->loadModel('tree')->getByID($param);
            $productID = isset($module->root) ? $module->root : 0;

            helper::setcookie('storyModuleParam',  $param);
            helper::setcookie('storyProductParam', 0);
            helper::setcookie('storyBranchParam',  0);
        }
        elseif($type == 'byproduct')
        {
            $productID = $param;
            helper::setcookie('storyModuleParam',  0);
            helper::setcookie('storyProductParam', $param);
            helper::setcookie('storyBranchParam',  0);
        }
        elseif($type == 'bybranch')
        {
            helper::setcookie('storyModuleParam',  0);
            helper::setcookie('storyProductParam', 0);
            helper::setcookie('storyBranchParam',  $param);
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
        helper::setcookie('executionStoryOrder', $orderBy);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $queryID     = ($type == 'bysearch') ? $param : 0;
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $stories = $this->story->getExecutionStories($executionID, 0, $sort, $type, $param, $storyType, '', $pager);

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

        if(commonModel::isTutorialMode())
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

        $plans    = $this->execution->getPlans($products, 'skipParent|withMainPlan|unexpired|noclosed|sortedByDate', $executionID);
        $allPlans = array();
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

        if(empty($productID)) $productID = (int)key($productPairs);
        $showModule  = !empty($this->config->execution->story->showModule) ? $this->config->execution->story->showModule : '';
        $modulePairs = $showModule ? $this->tree->getModulePairs($type == 'byproduct' ? $param : 0, 'story', $showModule) : array();

        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        if(!$execution->hasProduct and !$execution->multiple)
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'story', 0, array('treeModel', $createModuleLink), array('executionID' => $executionID, 'productID' => $productID), '', "&param=$param&storyType=$storyType");
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
        $summary = $this->product->summary($stories);
        if($storyType == 'requirement') $summary = str_replace($this->lang->SRCommon, $this->lang->URCommon, $summary);

        /* Assign. */
        $this->view->title             = $title;
        $this->view->position          = $position;
        $this->view->productID         = $productID;
        $this->view->product           = $this->product->getById($productID);
        $this->view->execution         = $execution;
        $this->view->executionID       = $executionID;
        $this->view->stories           = $stories;
        $this->view->linkedTaskStories = $this->story->getIdListWithTask($executionID);
        $this->view->allPlans          = $allPlans;
        $this->view->summary           = $summary;
        $this->view->orderBy           = $orderBy;
        $this->view->storyType         = $storyType;
        $this->view->type              = $this->session->executionStoryBrowseType;
        $this->view->param             = $param;
        $this->view->isAllProduct      = !$this->cookie->storyProductParam && !$this->cookie->storyModuleParam && !$this->cookie->storyBranchParam;
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
    public function bug(int $executionID = 0, int $productID = 0, string $branch = 'all', string $orderBy = 'status,id_desc', int $build = 0, string $type = 'all', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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

        /* Set the product drop-down and search fields. */
        $productOption = array();
        $branchOption  = array();
        $showBranch    = $this->loadModel('branch')->showBranch($productID);
        if($execution->hasProduct)
        {
            list($productOption, $branchOption) = $this->executionZen->buildProductSwitcher($executionID, $productID, $products);
            unset($this->config->bug->search['fields']['product']);
            unset($this->config->bug->search['params']['product']);
            if($project->model != 'scrum')
            {
                unset($this->config->bug->search['fields']['plan']);
                unset($this->config->bug->search['params']['plan']);
            }

        }

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $sort  = common::appendOrder($orderBy);
        $bugs  = $this->bug->getExecutionBugs($executionID, $productID, $branch, (string)$build, $type, $param, $sort, '', $pager);
        $bugs  = $this->bug->batchAppendDelayedDays($bugs);
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

        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }
        $storyList = $storyIdList ? $this->loadModel('story')->getByList($storyIdList) : array();
        $taskList  = $taskIdList  ? $this->loadModel('task')->getByIdList($taskIdList) : array();

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
            $moduleTree = $this->tree->getTreeMenu((int)$productID, 'bug', 0, array('treeModel', 'createBugLink'), $extra + array('branchID' => $branch, 'productID' => $productID), $branch);
        }
        else
        {
            $moduleTree = array();
        }
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $showModule = !empty($this->config->execution->bug->showModule) ? $this->config->execution->bug->showModule : '';

        /* Assign. */
        $this->view->title           = $execution->name . $this->lang->colon . $this->lang->execution->bug;
        $this->view->bugs            = $bugs;
        $this->view->tabID           = 'bug';
        $this->view->build           = $this->loadModel('build')->getById($build);
        $this->view->buildID         = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->users           = $users;
        $this->view->productID       = $productID;
        $this->view->product         = $this->product->getByID((int) $productID);
        $this->view->project         = $project;
        $this->view->branchID        = empty($this->view->build->branch) ? $branch : $this->view->build->branch;
        $this->view->memberPairs     = $memberPairs;
        $this->view->type            = $type;
        $this->view->summary         = $this->bug->summary($bugs);
        $this->view->param           = $param;
        $this->view->defaultProduct  = (empty($productID) and !empty($products)) ? current(array_keys($products)) : $productID;
        $this->view->builds          = $this->build->getBuildPairs($productID);
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
        $this->view->showBranch      = $showBranch;
        $this->view->recTotal        = $pager->recTotal;
        $this->view->productOption   = $productOption;
        $this->view->branchOption    = $branchOption;

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

        $products = $this->product->getProducts($executionID);
        if(count($products) == 1) $productID = key($products);

        $execution     = $this->execution->getByID($executionID);
        $productOption = array();
        $branchOption  = array();
        $showBranch    = $this->loadModel('branch')->showBranch($productID);
        if($execution->hasProduct)
        {
            list($productOption, $branchOption) = $this->executionZen->buildProductSwitcher($executionID, $productID, $products);
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $cases = $this->loadModel('testcase')->getExecutionCases($executionID, $productID, $branchID, $moduleID, $orderBy, $pager, $type);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        $cases = $this->testcase->appendData($cases, 'case');
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);

        /* Get module tree.*/
        if($executionID and empty($productID))
        {
            $moduleTree = $this->tree->getCaseTreeMenu($executionID, $productID, 0, array('treeModel', 'createCaseLink'));
        }
        else
        {
            $moduleTree = $this->tree->getTreeMenu($productID, 'case', 0, array('treeModel', 'createCaseLink'), array('projectID' => $executionID, 'productID' => $productID, 'branchID' => $branchID), $branchID);
        }
        $tree = $moduleID ? $this->tree->getByID($moduleID) : '';

        $idFieldList['title'] = $this->lang->idAB;
        $idFieldList['name']  = 'id';
        $idFieldList['type']  = 'id';
        $idFieldList['group'] = '1';
        array_unshift($this->config->testcase->dtable->fieldList, $idFieldList);

        unset($this->config->testcase->dtable->fieldList['title']['checkbox']);
        unset($this->config->testcase->dtable->fieldList['title']['nestedToggle']);

        $this->view->title         = $this->lang->execution->testcase;
        $this->view->executionID   = $executionID;
        $this->view->productID     = $productID;
        $this->view->product       = $this->product->getByID((int) $productID);
        $this->view->cases         = $cases;
        $this->view->orderBy       = $orderBy;
        $this->view->pager         = $pager;
        $this->view->type          = $type;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->execution     = $execution;
        $this->view->moduleTree    = $moduleTree;
        $this->view->moduleID      = $moduleID;
        $this->view->moduleName    = $moduleID ? $tree->name : $this->lang->tree->all;
        $this->view->branchID      = $branchID;
        $this->view->recTotal      = $pager->recTotal;
        $this->view->productOption = $productOption;
        $this->view->branchOption  = $branchOption;
        $this->view->showBranch    = $showBranch;

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
     * @param  string $type        all|product|bysearch
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function build(int $executionID = 0, string $type = 'all', int $param = 0, string $orderBy = 't1.date_desc,t1.id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $execution   = $this->commonAction($executionID);
        $executionID = (int)$execution->id;

        /* Get products' list. */
        $products = $this->product->getProducts($executionID, 'all', '', false);

        /* Build the search form. */
        $type    = strtolower($type);
        $this->project->buildProjectBuildSearchForm($products, $type == 'bysearch' ? (int)$param : 0, $executionID, $param, 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get builds. */
        $this->loadModel('build');
        if($type == 'bysearch')
        {
            $builds = $this->loadModel('build')->getExecutionBuildsBySearch($executionID, 0, $pager);
        }
        else
        {
            $builds = $this->loadModel('build')->getExecutionBuilds($executionID, $type, $param, $orderBy, $pager);
        }

        /* Set view data. */
        $this->view->title    = $execution->name . $this->lang->colon . $this->lang->execution->build;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->builds   = $this->executionZen->processBuildListData($builds, $executionID);
        $this->view->product  = $type == 'product' ? $param : 'all';
        $this->view->products = $products;
        $this->view->type     = $type;
        $this->view->param    = $param;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
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
    public function testtask($executionID = 0, $orderBy = 'product_asc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('testtask');
        $this->app->loadLang('testreport');

        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'execution');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $tasks = $this->testtask->getExecutionTasks($executionID, 'execution', $orderBy, $pager);

        /* Compute rowspan. */
        $productGroup = array();
        $waitCount    = 0;
        $testingCount = 0;
        $blockedCount = 0;
        $doneCount    = 0;
        foreach($tasks as $task)
        {
            $productGroup[$task->product][] = $task;
            if($task->status == 'wait')    $waitCount ++;
            if($task->status == 'doing')   $testingCount ++;
            if($task->status == 'blocked') $blockedCount ++;
            if($task->status == 'done')    $doneCount ++;
            if($task->build == 'trunk' || empty($task->buildName)) $task->buildName = $this->lang->trunk;
        }

        $lastProduct = '';
        foreach($tasks as $taskID => $task)
        {
            $task->rowspan = 0;
            if($lastProduct !== $task->product)
            {
                $lastProduct = $task->product;
                if(!empty($productGroup[$task->product])) $task->rowspan = count($productGroup[$task->product]);
            }
        }

        $this->view->title         = $this->executions[$executionID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->execution     = $execution;
        $this->view->project       = $this->loadModel('project')->getByID($execution->project);
        $this->view->executionID   = $executionID;
        $this->view->executionName = $this->executions[$executionID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->tasks         = $tasks;
        $this->view->waitCount     = $waitCount;
        $this->view->testingCount  = $testingCount;
        $this->view->blockedCount  = $blockedCount;
        $this->view->doneCount     = $doneCount;
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
        $chartData    = $this->execution->buildBurnData($executionID, $dateList, $burnBy, $executionEnd);

        $allDateList = date::getDateList($execution->begin, $endDate, 'Y-m-d', $type, $this->config->execution->weekend);
        $dayList     = array_fill(1, floor(count($allDateList) / $this->config->execution->maxBurnDay) + 5, '');
        foreach($dayList as $key => $val) $dayList[$key] = $this->lang->execution->interval . ($key + 1) . $this->lang->day;

        /* Assign. */
        $this->view->title         = $execution->name . $this->lang->colon . $this->lang->execution->burn;
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
        if($reload == 'yes') return $this->send(array('load' => true, 'result' => 'success'));
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
                foreach($dateError as $index => $error) $dateError[$index] = str_replace(array('。', '.'), array('', ''), $error);
                return $this->sendError(implode('; ', $dateError));
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
    public function fixFirst(int $executionID)
    {
        if($_POST)
        {
            $this->execution->fixFirst($executionID);
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $execution = $this->execution->getById($executionID);

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
        $execution = $this->commonAction($executionID);
        $deptID    = $this->app->user->admin ? 0 : $this->app->user->dept;

        $teamMembers = array();
        foreach($this->view->teamMembers as $member)
        {
            $member->days    = $member->days . $this->lang->execution->day;
            $member->hours   = $member->hours . $this->lang->execution->workHour;
            $member->total   = $member->totalHours . $this->lang->execution->workHour;
            $member->actions = array();
            if(common::hasPriv('execution', 'unlinkMember', $member) && common::canModify('execution', $execution)) $member->actions = array('unlink');

            $teamMembers[] = $member;
        }

        $this->view->title        = $execution->name . $this->lang->colon . $this->lang->execution->team;
        $this->view->deptUsers    = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->recTotal     = count($this->view->teamMembers);
        $this->view->teamMembers  = $teamMembers;

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
    public function create(string $projectID = '', int $executionID = 0, string $copyExecutionID = '', int $planID = 0, string $confirm = 'no', int $productID = 0, string $extra = '')
    {
        $projectID = (int)$projectID;

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

                    $confirmURL = inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=$planID&confirm=yes");
                    $cancelURL  = inlink('create', "projectID=$projectID&executionID=$executionID");
                    return $this->send(array('result' => 'success', 'load' => array('confirm' => $importPlanStoryTips, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
                }
            }

            if(!empty($projectID) and $execution->type == 'kanban' and $this->app->tab == 'project') return $this->send(array('result' => 'success', 'load' => $this->createLink('project', 'index', "projectID=$projectID")));
            if(!empty($projectID) and $execution->type == 'kanban') return $this->send(array('result' => 'success', 'load' => inlink('kanban', "executionID=$executionID")));

            $this->view->title       = $this->lang->execution->tips;
            $this->view->executionID = $executionID;
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

        if(!empty($project) and $project->stageBy == 'project')
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
                foreach($_POST['plans'] as $key => $planItem)
                {
                    $_POST['plans'][$key] = array_filter($_POST['plans'][$key]);
                }
                $_POST['plans'] = array_filter($_POST['plans']);
            }

            $executionID = $this->execution->create($copyExecutionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $comment = $project->hasProduct ? implode(',', $_POST['products']) : '';
            $this->loadModel('action')->create($this->objectType, $executionID, 'opened', '', $comment);

            $this->loadModel('programplan')->computeProgress($executionID, 'create');

            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $executionID));

            if($this->app->tab == 'doc')
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('doc', 'projectSpace', "objectID=$executionID")));
            }

            if(!empty($projectID) and strpos(',kanban,agileplus,waterfallplus,', ",$project->model,") !== false)
            {
                $execution = $this->execution->getById($executionID);
                if($execution->type == 'kanban') $this->loadModel('kanban')->createRDKanban($execution);
            }

            if(!empty($_POST['plans']))
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('create', "projectID=$projectID&executionID=$executionID&copyExecutionID=&planID=1&confirm=no")));
            }
            else
            {
                if(!empty($projectID) and $project->model == 'kanban')
                {
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                    $link = $this->config->vision != 'lite' ? $this->createLink('project', 'index', "projectID=$projectID") : $this->createLink('project', 'execution', "status=all&projectID=$projectID");
                    if($this->app->tab == 'project') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));

                    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('kanban', "executionID=$executionID")));
                }
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('create', "projectID=$projectID&executionID=$executionID")));
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

        $copyProjects  = $this->loadModel('project')->getPairsByProgram(isset($project->parent) ? $project->parent : 0, 'noclosed', '', 'order_asc', '', $projectModel, 'multiple');
        $copyProjectID = ($projectID == 0) ? key($copyProjects) : $projectID;

        if(!empty($project->hasProduct)) $allProducts = array(0 => '') + $allProducts;
        if(isset($project->hasProduct) and $project->hasProduct == 0) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        $this->view->title               = $this->app->tab == 'execution' ? $this->lang->execution->createExec : $this->lang->execution->create;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'all') : '';
        $this->view->groups              = $this->loadModel('group')->getPairs();
        $this->view->allProducts         = $allProducts;
        $this->view->acl                 = $acl;
        $this->view->plan                = $plan;
        $this->view->name                = $name;
        $this->view->code                = $code;
        $this->view->team                = $team;
        $this->view->teams               = $this->execution->getCanCopyObjects((int)$projectID);
        $this->view->allProjects         = $this->project->getPairsByModel('all', 'noclosed,multiple');
        $this->view->copyProjects        = $copyProjects;
        $this->view->copyProjectID       = $copyProjectID;
        $this->view->copyExecutions      = $this->execution->getList($copyProjectID, 'all', 'all', 0, 0, 0, null, false);
        $this->view->executionID         = $executionID;
        $this->view->productID           = $productID;
        $this->view->projectID           = $projectID;
        $this->view->products            = $products;
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs();
        $this->view->productPlan         = $productPlan;
        $this->view->productPlans        = $productPlans;
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
        $this->view->isStage             = isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus'));
        $this->view->project             = $project;
        $this->view->stageBy             = empty($project->stageBy) ? '' : $project->stageBy;
        $this->view->type                = $type;
        $this->display();
    }

    /**
     * Edit a execution.
     *
     * @param  int    $executionID
     * @param  string $action
     * @param  string $extra
     * @param  string $newPlans
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function edit(int $executionID, string $action = 'edit', string $extra = '', string $newPlans = '', string $confirm = 'no')
    {
        /* Load language files. */
        $this->loadModel('product');
        $this->app->loadLang('program');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $execution           = $this->execution->getById($executionID);
        $branches            = $this->project->getBranchesByProject($executionID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);

        if(!empty($newPlans) and $confirm == 'yes')
        {
            $newPlans = explode(',', $newPlans);
            $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
            $this->loadModel('productplan')->linkProject($executionID, $newPlans);
            $this->productplan->linkProject($projectID, $newPlans);
            return $this->send(array('result' => 'success', 'load' => inlink('view', "executionID=$executionID")));
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

            $confirmURL = inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=yes");
            $cancelURL  = inlink('view', "executionID=$executionID");
            return $this->send(array('result' => 'success', 'load' => array('confirm' => $importEditPlanStoryTips, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
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
            $products     = $diffProducts ? implode(',', $newProducts) : '';

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
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('edit', "executionID=$executionID&action=edit&extra=&newPlans=$newPlans&confirm=no")));
            }

            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($_POST['status'] == 'doing') $this->loadModel('common')->syncPPEStatus($executionID);

            /* If link from no head then reload. */
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => 'parent'));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "executionID=$executionID")));
        }

        $executions = $this->executions;

        /* Remove current execution from the executions. */
        unset($executions[$executionID]);

        $allProducts = $this->product->getProducts($execution->project, 'noclosed', '', false, $linkedProductIdList);

        $this->loadModel('productplan');
        $productPlans     = array();
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

        if($project->model == 'waterfall' or $project->model == 'waterfallplus')
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

        $this->view->title                = $this->lang->execution->edit . $this->lang->colon . $execution->name;
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
        $this->view->allProjects          = $this->project->getPairsByModel($project->model, 'noclosed', $project->id);
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

        if($this->post->name)
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
            $allProjects = $this->project->getPairsByModel($project->model, 'noclosed', isset($projectID) ? $projectID : 0);
            $this->project->setMenu($projectID);
            $this->view->project = $project;
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->lang->execution->common = $this->lang->execution->stage;
        }
        else
        {
            $allProjects = $this->project->getPairsByModel('all', 'noclosed', isset($projectID) ? $projectID : 0);
        }

        if(!$this->post->executionIDList) return $this->locate($this->session->executionList);

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
     * 批量设置执行状态。
     * Batch change status.
     *
     * @param  string    $status
     * @param  int       $projectID
     * @access public
     * @return void
     */
    public function batchChangeStatus(string $status, int $projectID = 0)
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

            return $this->sendSuccess(array('message' => $alertLang, 'load' => true));
        }

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Start execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function start(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->execution->start($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'start');

            $this->loadModel('common')->syncPPEStatus($executionID);
            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('doing')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->start;
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
    public function suspend(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $this->execution->computeBurn($executionID);
            $changes = $this->execution->suspend($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'Suspended', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'suspend');

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('suspended')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->suspend;
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
    public function activate(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->execution->activate($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'activate');

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('doing')";
            return $this->sendSuccess($response);
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $execution->begin);
        $newEnd   = date('Y-m-d', strtotime($execution->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->activate;
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
    public function close(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        if(!empty($_POST))
        {
            $this->execution->computeBurn($executionID);
            $this->execution->close($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID, 'close');

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('closed')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon .$this->lang->execution->close;
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

        $executionID = $this->execution->checkAccess((int)$executionID, $this->executions);
        $execution   = $this->execution->getById($executionID, true);

        $type = $this->config->vision == 'lite' ? 'kanban' : 'stage,sprint,kanban';
        if(empty($execution) || strpos($type, $execution->type) === false) return print(js::error($this->lang->notFound) . js::locate('back'));

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

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $this->executeHooks($executionID);
        if(!$execution->projectInfo->hasProduct) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        $userPairs = array();
        $userList  = array();
        $users     = $this->loadModel('user')->getList('all');
        foreach($users as $user)
        {
            $userList[$user->account]  = $user;
            $userPairs[$user->account] = $user->realname;
        }

        $this->view->title      = $this->lang->execution->view;

        $this->view->execution    = $execution;
        $this->view->products     = $products;
        $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
        $this->view->planGroups   = $this->execution->getPlans($products);
        $this->view->actions      = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', 'all', $executionID);
        $this->view->users        = $userPairs;
        $this->view->userList     = $userList;
        $this->view->teamMembers  = $this->execution->getTeamMembers($executionID);
        $this->view->docLibs      = $this->loadModel('doc')->getLibsByObject('execution', $executionID);
        $this->view->statData     = $this->execution->statRelatedData($executionID);
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
        $allPlans = array();
        if(!empty($plans))
        {
            foreach($plans as $plan) $allPlans += $plan;
        }

        $taskToOpen = $this->cookie->taskToOpen ? $this->cookie->taskToOpen : 0;
        helper::setcookie('taskToOpen', 0, 0);

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
        $this->view->projectID        = $execution->project;
        $this->view->project          = $this->loadModel('project')->getByID($execution->project);
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
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) helper::header('X-UA-Compatible', 'IE=EmulateIE7');

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
        $allPlans = array();
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
        $projects   = $this->loadModel('project')->getPairsByProgram(0, 'noclosed');
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
     * 用树状图的形式查看迭代/执行的视图。
     * Show the view of the execution in a tree mode.
     *
     * @param  int    $executionID
     * @param  string $type
     * @access public
     * @return void
     */
    public function tree(int $executionID, string $type = 'task')
    {
        $this->execution->setMenu($executionID);

        /* Save this URI to session for locate it back. */
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',      $uri, 'execution');
        $this->app->session->set('storyList',     $uri, 'execution');
        $this->app->session->set('executionList', $uri, 'execution');
        $this->app->session->set('caseList',      $uri, 'qa');
        $this->app->session->set('bugList',       $uri, 'qa');

        $tree = $this->execution->getTree($executionID);
        if($type === 'json') return print(helper::jsonEncode4Parse($tree, JSON_HEX_QUOT | JSON_HEX_APOS));

        $execution = $this->execution->getById($executionID);
        $project   = $this->project->getById($execution->project);
        if($execution->lifetime == 'ops') unset($this->lang->execution->treeLevel['story']);

        $this->view->title       = $this->lang->execution->tree;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->level       = $type;
        $this->view->tree        = $this->execution->printTree($tree, $project->hasProduct);
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
        $this->display();
    }

    /**
     * 看板看板。
     * Print kanban.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function printKanban(int $executionID, string $orderBy = 'id_asc')
    {
        $this->view->title = $this->lang->execution->printKanban;

        if($_POST)
        {
            /* Get stories information. */
            $order      = 1;
            $stories    = $this->loadModel('story')->getExecutionStories($executionID, 0, $orderBy);
            $storySpecs = $this->story->getStorySpecs(array_keys($stories));
            foreach($stories as $story) $story->order = $order ++;

            /* Get printed kanban data. */
            list($dataList, $users) = $this->executionZen->getPrintKanbanData($executionID, $stories);

            $originalDataList = $dataList;
            if($this->post->content == 'increment') $dateList = $this->executionZen->processPrintKanbanData($executionID, $dateList);

            /* Close the page when there is no data. */
            $hasData = false;
            foreach($dataList as $data)
            {
                if(empty($data)) continue;
                $hasData = true;
                break;
            }
            if(!$hasData) return $this->sendSuccess(array('message' => $this->lang->execution->noPrintData, 'load' => true));

            $this->execution->saveKanbanData($executionID, $originalDataList);

            /* Get date list. */
            if($this->post->content == 'all')
            {
                $executionInfo  = $this->execution->getByID($executionID);
                list($dateList) = $this->execution->getDateList($executionInfo->begin, $executionInfo->end, 'noweekend');
            }

            $this->view->hasBurn     = $this->post->content == 'all';
            $this->view->datas       = $dataList;
            $this->view->chartData   = $this->post->content == 'all' ? $this->execution->buildBurnData($executionID, $dateList) : array();
            $this->view->storySpecs  = $storySpecs;
            $this->view->realnames   = $this->loadModel('user')->getRealNameAndEmails($users);
            $this->view->executionID = $executionID;

            return $this->display();
        }

        $this->execution->setMenu($executionID);
        $this->view->executionID = $executionID;

        $this->display();
    }

    /**
     * 需求看板。
     * Story kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storyKanban(int $executionID)
    {
        /* Compatibility IE8*/
        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) helper::header('X-UA-Compatible', 'IE=EmulateIE7');

        $this->execution->setMenu($executionID);
        $execution = $this->loadModel('execution')->getByID($executionID);
        $stories   = $this->loadModel('story')->getExecutionStories($executionID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        /* Get execution's product. */
        $productID = 0;
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);
        if($productPairs) $productID = key($productPairs);

        $this->app->session->set('executionStoryList', $this->app->getURI(true), 'execution');

        $this->view->title        = $this->lang->execution->storyKanban;
        $this->view->stories      = $this->story->getKanbanGroupData($stories);
        $this->view->realnames    = $this->loadModel('user')->getPairs('noletter');
        $this->view->executionID  = $executionID;
        $this->view->execution    = $execution;
        $this->view->productID    = $productID;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * 删除一个执行。
     * Delete a execution.
     *
     * @param  int    $executionID
     * @param  string $confirm      yes|no
     * @access public
     * @return void
     */
    public function delete(int $executionID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            /* Get the number of unfinished tasks and unresolved bugs. */
            $unfinishedTasks     = $this->loadModel('task')->getUnfinishTasks($executionID);
            $unresolvedBugs      = $this->loadModel('bug')->getActiveBugs(0, '', $executionID, array());
            $unfinishedTaskCount = count($unfinishedTasks);
            $unresolvedBugCount  = count($unresolvedBugs);

            /* Set prompt information. */
            $tips = '';
            if($unfinishedTaskCount) $tips  = sprintf($this->lang->execution->unfinishedTask, $unfinishedTaskCount);
            if($unresolvedBugCount)  $tips .= sprintf($this->lang->execution->unresolvedBug,  $unresolvedBugCount);
            if($tips)                $tips  = $this->lang->execution->unfinishedExecution . $tips;

            $execution = $this->execution->fetchByID($executionID);
            if($execution->type == 'stage')
            {
                if($tips) $tips = str_replace($this->lang->executionCommon, $this->lang->project->stage, $tips);
                $this->lang->execution->confirmDelete = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->execution->confirmDelete);
            }
            elseif($execution->type == 'kanban')
            {
                global $lang;
                $lang->executionCommon = $lang->execution->kanban;
                include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
            }

            $tips = $tips . sprintf($this->lang->execution->confirmDelete, $this->executions[$executionID]);
            return $this->send(array('callback' => "confirmDeleteExecution({$executionID}, \"{$tips}\")"));
        }
        else
        {
            /* Delete an execution and update the information. */
            $execution = $this->execution->fetchByID($executionID);
            $this->execution->delete(TABLE_EXECUTION, $executionID);
            $this->execution->updateUserView($executionID);
            $this->loadModel('common')->syncPPEStatus($executionID);

            $project = $this->loadModel('project')->getByID($execution->project);
            if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($executionID);

            $this->session->set('execution', '');
            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            return $this->send(array('result' => 'success', 'load' => true));
        }
    }

    /**
     * Manage products.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function manageProducts($executionID)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        $this->loadModel('product');
        $execution = $this->execution->getById($executionID);
        $project   = $this->loadModel('project')->getByID($execution->project);
        if(!$project->hasProduct) return print(js::error($this->lang->project->cannotManageProducts) . js::locate('back'));
        if($project->model == 'waterfall' or $project->model == 'waterfallplus') return print(js::error(sprintf($this->lang->execution->cannotManageProducts, zget($this->lang->project->modelList, $project->model))) . js::locate('back'));

        if(!empty($_POST))
        {
            $oldProducts = $this->product->getProducts($executionID);

            $this->execution->updateProducts($executionID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->product->getProducts($executionID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create($this->objectType, $executionID, 'Managed', '', !empty($_POST['products']) ? implode(',', $_POST['products']) : '');

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        /* Set menu. */
        $this->execution->setMenu($execution->id);

        /* Title and position. */
        $branches            = $this->project->getBranchesByProject($executionID);
        $linkedProductIdList = empty($branches) ? array() : array_keys($branches);
        $allProducts         = $this->product->getProductPairsByProject($execution->project, 'all', implode(',', $linkedProductIdList));
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
        $this->view->title                = $this->lang->execution->manageProducts . $this->lang->colon . $execution->name;
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
    public function manageMembers(int $executionID = 0, int $team2Import = 0, int $dept = 0)
    {
        if(!empty($_POST))
        {
            $this->execution->manageMembers($executionID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('team', $executionID, 'managedTeam');
            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'load' => array('back' => true)));
        }

        /* Load model. */
        $this->loadModel('user');
        $this->loadModel('dept');

        $execution = $this->execution->getById($executionID);
        $deptUsers = empty($dept) ? array() : $this->dept->getDeptUserPairs($dept);

        $currentMembers = $this->execution->getTeamMembers($executionID);
        $members2Import = $this->execution->getMembers2Import($team2Import, array_keys($currentMembers));
        $teams2Import   = $this->loadModel('personnel')->getCopiedObjects($executionID, 'sprint', true);

        /* Append users for get users. */
        $appendUsers = array();
        foreach($currentMembers as $account => $member) $appendUsers[$account] = $account;
        foreach($members2Import as $member) $appendUsers[$member->account] = $member->account;
        foreach($deptUsers as $deptAccount => $userName) $appendUsers[$deptAccount] = $deptAccount;

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["accounts[]"] = $this->config->user->moreLink;

        if($execution->type == 'kanban') $this->lang->execution->copyTeamTitle = str_replace($this->lang->execution->common, $this->lang->execution->kanban, $this->lang->execution->copyTeamTitle);

        $this->view->title          = $this->lang->execution->manageMembers . $this->lang->colon . $execution->name;
        $this->view->execution      = $execution;
        $this->view->users          = $this->user->getPairs('noclosed|nodeleted|devfirst', $appendUsers);
        $this->view->roles          = $this->user->getUserRoles(array_keys($this->view->users));
        $this->view->dept           = $dept;
        $this->view->depts          = $this->loadModel('dept')->getOptionMenu();
        $this->view->teams2Import   = $teams2Import;
        $this->view->team2Import    = $team2Import;
        $this->view->teamMembers    = $this->executionZen->buildMembers($currentMembers, $members2Import, $deptUsers, $execution->days);
        $this->display();
    }

    /**
     * Unlink a memeber.
     *
     * @param  int    $executionID
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unlinkMember($executionID, $userID)
    {
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $this->execution->unlinkMember($executionID, $account);
        if(!dao::isError()) $this->loadModel('action')->create('team', $executionID, 'managedTeam');

        /* if ajax request, send result. */
        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError();
        }
        else
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $response['load']    = helper::createLink('execution', 'team', "executionID={$executionID}");
        }
        return $this->send($response);
    }

    /**
     * Link stories to an execution.
     *
     * @param  int    $objectID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function linkStory($objectID = 0, $browseType = '', $param = 0, $recPerPage = 50, $pageID = 1, $extra = '')
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
            $kanban  = $this->project->getByID($objectID, 'kanban');
            $objectID = $kanban->project;
        }

        /* Get projects, executions and products. */
        $object     = $this->project->getByID($objectID, 'project,sprint,stage,kanban');
        $products   = $this->product->getProducts($objectID);
        $queryID    = ($browseType == 'bySearch') ? (int)$param : 0;
        $browseLink = $this->session->executionStoryList;
        if($this->app->tab == 'project' and $object->multiple) $browseLink = $this->createLink('projectstory', 'story', "objectID=$objectID");
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
            if($object->type != 'project' and $object->project != 0) $this->execution->linkStory($object->project);
            $this->execution->linkStory($objectID, array(), $extra);

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
                        return $this->sendSuccess(array('closeModal' => true, 'load' => true, 'callback' => "parent.updateKanban($kanbanData)"));
                    }
                    else
                    {
                        $kanbanData = $this->loadModel('kanban')->getExecutionKanban($objectID, $execLaneType, $execGroupBy);
                        $kanbanType = $execLaneType == 'all' ? 'story' : key($kanbanData);
                        $kanbanData = $kanbanData[$kanbanType];
                        $kanbanData = json_encode($kanbanData);
                        return $this->sendSuccess(array('closeModal' => true, 'load' => true, 'callback' => "parent.updateKanban(\"story\", $kanbanData)"));
                    }
                }
                else
                {
                    return $this->sendSuccess(array('closeModal' => true, 'load' => true));
                }
            }

            return $this->sendSuccess(array('load' => $browseLink));
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

        if(commonModel::isTutorialMode())
        {
            $modules = $this->loadModel('tutorial')->getModulePairs();
        }
        else
        {
            foreach($products as $product)
            {
                $productModules = $this->tree->getOptionMenu($product->id, 'story', 0, array_keys($branches[$product->id]));
                foreach($productModules as $branchModules)
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

        /* Build the search form. */
        $actionURL    = $this->createLink($this->app->rawModule, 'linkStory', "objectID=$objectID&browseType=bySearch&queryID=myQueryID");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'linkStory', $object);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch('', '', $queryID, 'id', $objectID);
        }
        else
        {
            $allStories = $this->story->getProductStories(implode(',', array_keys($products)), $branchIDList, '0', 'active', 'story', 'id_desc', false, '', null);
        }

        $linkedStories = $this->story->getExecutionStoryPairs($objectID);
        foreach($allStories as $id => $story)
        {
            if(isset($linkedStories[$story->id])) unset($allStories[$id]);
            if($story->parent < 0) unset($allStories[$id]);

            if(!isset($modules[$story->module]))
            {
                $storyModule = $this->tree->getModulesName((array)$story->module);
                $productName = count($products) > 1 ? $products[$story->product]->name : '';
                $modules[$story->module] = $productName . zget($storyModule, $story->module, '');
            }
        }

        /* Pager. */
        $this->app->loadClass('pager', true);
        $pager      = new pager(count($allStories), $recPerPage, $pageID);
        $allStories = array_chunk($allStories, $pager->recPerPage);

        $project = $object;
        if(strpos('sprint,stage,kanban', $object->type) !== false) $project = $this->loadModel('project')->getByID($object->project);

        $productPairs = array();
        foreach($products as $id => $product) $productPairs[$id] = $product->name;

        /* Assign. */
        $this->view->title        = $object->name . $this->lang->colon . $this->lang->execution->linkStory;
        $this->view->objectID     = $originObjectID;
        $this->view->object       = $object;
        $this->view->executionID  = $object->id;
        $this->view->projectID    = $object->id;
        $this->view->productPairs = $productPairs;
        $this->view->allStories   = empty($allStories) ? $allStories : $allStories[$pageID - 1];
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->productType  = $productType;
        $this->view->modules      = $modules;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->branchGroups = $branchGroups;
        $this->view->browseLink   = $browseLink;
        $this->view->project      = $project;

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
            return print(js::confirm($tip, $this->createLink('execution', 'unlinkstory', "executionID=$executionID&storyID=$storyID&confirm=yes&from=$from&laneID=$laneID&columnID=$columnID")));
        }
        else
        {
            $execution = $this->execution->getByID($executionID);
            $this->execution->unlinkStory($executionID, $storyID, $laneID, $columnID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($execution->type == 'kanban')
            {
                /* Fix bug #29171. */
                $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($execution->parent)->fetchAll('id');
                $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
                if(empty($executionStories)) $this->execution->unlinkStory($execution->parent, $storyID, $laneID, $columnID);
            }

            $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
            if($this->app->tab == 'execution' and $execution->type == 'kanban')
            {
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->loadModel('kanban')->getRDKanban($executionID, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban($kanbanData)"));
            }
            elseif($from == 'taskkanban')
            {
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($executionID, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'story' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "updateKanban(\"story\", $kanbanData)"));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
        return $this->send(array('result' => 'success', 'load' => $this->createLink('execution', 'story', "executionID=$executionID")));
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

        /* Append id for second sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, 50, 1);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id');
            if($user) $account = $user->account;
        }
        $period  = $type == 'account' ? 'all'  : $type;
        $date    = empty($date) ? '' : date('Y-m-d', $date);
        $actions = $this->loadModel('action')->getDynamic($account, $period, $orderBy, $pager, 'all', 'all', $executionID, $date, $direction);
        if(empty($recTotal)) $recTotal = count($actions);

        /* The header and position. */
        $execution = $this->execution->getByID($executionID);
        $this->view->title      = $execution->name . $this->lang->colon . $this->lang->execution->dynamic;

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
        $this->view->recTotal    = $recTotal;
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
        $this->display();
    }

    /**
     * Drop menu page.
     *
     * @param  int    $executionID
     * @param  string $module
     * @param  string $method
     * @param  mixed  $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($executionID, $module, $method, $extra)
    {
        $orderedExecutions = array();

        $projects = $this->loadModel('program')->getProjectList(0, 'all', 0, 'order_asc', null, 0, 0, true);
        $executionGroups = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('multiple')->eq('1')
            ->andWhere('type')->in('sprint,stage,kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->orderBy('order_asc')
            ->fetchGroup('project', 'id');

        $teams = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('root')->in($this->app->user->view->sprints)
            ->andWhere('type')->eq('execution')
            ->fetchGroup('root', 'account');

        $projectPairs = array();
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

            $executions = $this->execution->resetExecutionSorts($executions, $firstGradeExecs);
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

        $this->view->link               = $this->execution->getLink($module, $method, $extra);
        $this->view->module             = $module;
        $this->view->method             = $method;
        $this->view->executionID        = $executionID;
        $this->view->extra              = $extra;
        $this->view->projects           = $projectPairs;
        $this->view->projectExecutions  = $projectExecutions;
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
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('story', $storyID, 'estimated', '', $executionID);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
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
     * 查看执行列表。
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
    public function all(string $status = 'undone', string $orderBy = 'order_asc', int $productID = 0, string $param = '', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $this->app->loadLang('my');
        $this->app->loadLang('product');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $this->loadModel('datatable');

        $from = $this->app->tab;
        if($from == 'execution') $this->session->set('executionList', $this->app->getURI(true), 'execution');

        if($this->app->viewType == 'mhtml')
        {
            $executionID = $this->execution->checkAccess(0, $this->executions);
            $this->execution->setMenu($executionID);
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = ($status == 'bySearch') ? (int)$param : 0;
        $actionURL = $this->createLink('execution', 'all', "status=bySearch&orderBy=$orderBy&productID=$productID&param=myQueryID");
        $this->execution->buildSearchForm($queryID, $actionURL);

        $this->view->title          = $this->lang->execution->allExecutions;
        $this->view->executionStats = $this->execution->getStatData(0, $status, $productID, 0, false, $queryID, $orderBy, $pager);
        $this->view->productList    = $this->loadModel('product')->getProductPairsByProject(0);
        $this->view->productID      = $productID;
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects       = array('') + $this->project->getPairsByProgram();
        $this->view->status         = $status;
        $this->view->from           = $from;
        $this->view->param          = $param;
        $this->view->showBatchEdit  = $this->cookie->showExecutionBatchEdit;
        $this->view->avatarList     = $this->user->getAvatarPairs('');

        $this->display();
    }

    /**
     * 显示白名单信息。
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
    public function whitelist(int $executionID = 0, string $module='execution', string $objectType = 'sprint', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        $execution = $this->execution->getByID($executionID);
        if(!empty($execution->acl) and $execution->acl != 'private') return $this->sendError($this->lang->whitelistNotNeed, $this->createLink('execution', 'task', "executionID=$executionID"));

        echo $this->fetch('personnel', 'whitelist', "objectID=$executionID&module=$module&browseType=$objectType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 添加用户到白名单。
     * Adding users to the white list.
     *
     * @param  int     $executionID
     * @param  int     $deptID
     * @param  int     $copyID
     * @access public
     * @return void
     */
    public function addWhitelist(int $executionID = 0, int $deptID = 0, int $copyID = 0)
    {
        /* use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $this->execution->setMenu($executionID);

        $execution = $this->execution->getByID($executionID);
        if(!empty($execution->acl) and $execution->acl != 'private') return $this->sendError($this->lang->whitelistNotNeed, $this->createLink('execution', 'task', "executionID=$executionID"));

        echo $this->fetch('personnel', 'addWhitelist', "objectID=$executionID&dept=$deptID&copyID=$copyID&objectType=sprint&module=execution");
    }

    /*
     * 从白名单中移除用户。
     * Removing users from the white list.
     *
     * @param  int     $id
     * @param  string  $confirm
     * @access public
     * @return void
     */
    public function unbindWhitelist(int $id = 0, string $confirm = 'no')
    {
        echo $this->fetch('personnel', 'unbindWhitelist', "id=$id&confirm=$confirm");
    }

    /**
     * 执行列表导出数据。
     * Export execution.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $from
     * @access public
     * @return void
     */
    public function export(string $status, int $productID, string $orderBy, string $from)
    {
        if($_POST)
        {
            $executionLang   = $this->lang->execution;
            $executionConfig = $this->config->execution;

            $projectID = $from == 'project' ? $this->session->project : 0;
            if($projectID) $this->project->setMenu($projectID);
            $project = $this->project->getByID($projectID);

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $executionConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                if($fieldName == 'name' and $this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $fields['method'] = $executionLang->method;

                $fieldName = trim($fieldName);
                $fields[$fieldName] = zget($executionLang, $fieldName);
                unset($fields[$key]);
            }

            $users = $this->loadModel('user')->getPairs('noletter');
            $executionStats = $this->execution->getStatData($projectID, $status == 'byproduct' ? 'all' : $status, $productID, 0, false, 'hasParentName', $orderBy);
            foreach($executionStats as $i => $execution)
            {
                $execution->PM            = zget($users, $execution->PM);
                $execution->status        = isset($execution->delay) ? $executionLang->delayed : $this->processStatus('execution', $execution);
                $execution->totalEstimate = $execution->hours->totalEstimate;
                $execution->totalConsumed = $execution->hours->totalConsumed;
                $execution->totalLeft     = $execution->hours->totalLeft;
                $execution->progress      = $execution->hours->progress . '%';
                $execution->name          = isset($execution->title) ? $execution->title : $execution->name;
                if($this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $execution->method = zget($executionLang->typeList, $execution->type);

                if($this->post->exportType == 'selected')
                {
                    $checkedItem = $this->cookie->checkedItem;
                    if(strpos(",$checkedItem,", ",{$execution->id},") === false) unset($executionStats[$i]);
                }
            }
            if($this->config->edition != 'open') list($fields, $executionStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $executionStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $executionStats);
            $this->post->set('kind', $this->lang->execution->common);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $project = $this->project->getByID($this->session->project);
        if(!empty($project->model) and $project->model == 'waterfall') $this->lang->executionCommon = $this->lang->project->stage;

        $this->view->fileName = (in_array($status, array('all', 'undone')) ? $this->lang->execution->$status : $this->lang->execution->statusList[$status]) . $this->lang->execution->common;
        $this->display();
    }

    /**
     * 在迭代中查看迭代的文档。
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
     * 将计划中的需求关联到此项目或迭代/执行或看板中。
     * Import the stories to execution/project/kanban by product plan.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function importPlanStories(int $executionID, int $planID, int $productID = 0, string $extra = '')
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->sendError($this->lang->error->accessDenied);

        $execution   = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        $planStories = array();
        $planStory   = $this->loadModel('story')->getPlanStories($planID);
        $draftCount  = 0;
        if(!empty($planStory))
        {
            $projectProducts = $this->loadModel('project')->getBranchesByProject($executionID);
            foreach($planStory as $id => $story)
            {
                $projectBranches = zget($projectProducts, $story->product, array());
                if($story->status != 'active' or (!empty($story->branch) and !empty($projectBranches) and !isset($projectBranches[$story->branch])))
                {
                    $draftCount++;
                    unset($planStory[$id]);
                    continue;
                }
            }

            $planStories = array_keys($planStory);
            if($execution->project != 0) $this->execution->linkStory($execution->project, $planStories); /* Link story to the project of the execution. */
            $this->execution->linkStory($executionID, $planStories, $extra); /* Link story to the execution. */
        }

        $moduleName = 'execution';
        if($execution->type == 'project') $moduleName = 'projectstory';
        if($execution->type == 'kanban')
        {
            global $lang;
            $lang->executionCommon = $lang->execution->kanban;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        /* Check if the product is multiple branch. */
        $multiBranchProduct = $this->executionZen->hasMultipleBranch($productID, $executionID);

        if($draftCount != 0)
        {
            $importPlanStoryTips = $multiBranchProduct ? $this->lang->execution->haveBranchDraft : $this->lang->execution->haveDraft;
            $haveDraft           = sprintf($importPlanStoryTips, $draftCount);
            if(!$execution->multiple || $moduleName == 'projectstory') $haveDraft = str_replace($this->lang->executionCommon, $this->lang->projectCommon, $haveDraft);
            return $this->sendError($haveDraft);
        }

        return $this->sendSuccess(array('closeModal' => true, 'load' => true));
    }

    /**
     * 在执行/迭代的树状图中查看某个需求的详情。
     * View a story in the tree view of the execution.
     *
     * @param int $storyID
     * @param int $version  The version of story.
     *
     * @access public
     * @return void
     */
    public function treeStory(int $storyID, int $version = 0)
    {
        $story   = $this->loadModel('story')->getById($storyID, $version, true);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name,id,type,shadow')->fetch();

        $this->view->story      = $story;
        $this->view->product    = $product;
        $this->view->branches   = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->plan       = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fields('title')->fetch('title');
        $this->view->bugs       = $this->dao->select('id,title')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->fromBug    = $this->dao->select('id,title')->from(TABLE_BUG)->where('toStory')->eq($storyID)->fetch();
        $this->view->cases      = $this->dao->select('id,title')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->modulePath = $this->loadModel('tree')->getParents($story->module);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        $this->view->actions    = $this->loadModel('action')->getList('story', $storyID);
        $this->view->version    = $version == 0 ? $story->version : $version;
        $this->display();
    }

    /**
     * 在执行/迭代的树状图中查看某个任务的详情。
     * View a task in the tree view of the execution.
     *
     * @param int $taskID
     *
     * @access public
     * @return void
     */
    public function treeTask(int $taskID)
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
            $task->storySpec   = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->spec);
            $task->storyVerify = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->verify);
            $task->storyFiles  = $this->loadModel('file')->getByObject('story', $task->story);
        }

        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        $this->view->task      = $task;
        $this->view->execution = $this->execution->getById($task->execution);
        $this->view->actions   = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 获取泳道分组下拉。
     * Get the lane group drop-down by ajax.
     *
     * @param  string $type all|syory|task|bug
     * @param  string $group
     * @access public
     * @return void
     */
    public function ajaxGetGroup($type, $group = 'default')
    {
        $this->app->loadLang('kanban');

        return print(html::select("group", $this->lang->kanban->group->$type, $group, 'class="form-control chosen" data-max_drop_width="215"'));
    }

    /**
     * 更新看板数据。
     * Update kanban by ajax.
     *
     * @param  int    $executionID
     * @param  int    $enterTime
     * @param  string $browseType  story|task|bug
     * @param  string $groupBy     default|pri|category|module|source|assignedTo|type|story|severity
     * @param  string $from        execution|RD
     * @param  string $searchValue
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function ajaxUpdateKanban(int $executionID = 0, int $enterTime = 0, string $browseType = '', string $groupBy = '', string $from = 'execution', string $searchValue = '', string $orderBy = 'id_asc')
    {
        $this->loadModel('kanban');
        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'pri_asc';

        if($from == 'execution') $this->session->set('taskSearchValue', $searchValue);
        if($from == 'RD')        $this->session->set('rdSearchValue', $searchValue);

        $lastEditedTime = $this->execution->getLaneMaxEditedTime($executionID);
        $enterTime      = date('Y-m-d H:i:s', $enterTime);
        if(in_array(true, array(is_null($lastEditedTime), strtotime($lastEditedTime) < 0, $lastEditedTime > $enterTime, $groupBy != 'default', !empty($searchValue))))
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
     * @param  int    $copyExecutionID
     * @access public
     * @return void
     */
    public function ajaxGetCopyProjectExecutions(int $projectID = 0, int $copyExecutionID = 0)
    {
        $this->view->executions      = $this->execution->getList($projectID, 'all', 'all', 0, 0, 0, null, false);
        $this->view->copyExecutionID = $copyExecutionID;
        $this->display();
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

    /**
     * AJAX: Get brun kanban html.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetBurn(int $executionID)
    {
        $execution = $this->execution->getById($executionID, true);
        $type      = 'noweekend';
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
        $this->view->chartData = $this->execution->buildBurnData($executionID, $dateList, 'left', $executionEnd);
        $this->view->execution = $execution;

        $this->display();
    }

    /**
     * AJAX: Get brun kanban html.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetCFD(int $executionID)
    {
        $this->app->loadClass('date');

        $execution = $this->execution->getById($executionID, true);
        list($begin, $end) = $this->execution->getBeginEnd4CFD($execution);
        $dateList  = date::getDateList($begin, $end, 'Y-m-d', 'noweekend');
        $chartData = $this->execution->buildCFDData($executionID, $dateList, 'task');
        if(isset($chartData['line'])) $chartData['line'] = array_reverse($chartData['line']);

        $this->view->begin     = helper::safe64Encode(urlencode($begin));
        $this->view->end       = helper::safe64Encode(urlencode($end));
        $this->view->chartData = $chartData;

        $this->display();
    }
}
