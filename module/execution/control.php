<?php
declare(strict_types=1);
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
        if((defined('RUN_MODE') and RUN_MODE == 'api') or $this->viewType == 'json') $mode = '';

        $this->executions = $this->execution->getPairs(0, 'all', "nocode,noprefix,{$mode}");
        $skipCreateStep   = array('computeburn', 'ajaxgetdropmenu', 'executionkanban', 'ajaxgetteammembers', 'all', 'ajaxgetcopyprojectexecutions');
        if(in_array($this->methodName, $skipCreateStep)) return false;
        if($this->executions || $this->methodName == 'index' || $this->methodName == 'create' || $this->app->getViewType() == 'mhtml') return false;
        if($this->app->tab == 'project' && in_array($this->app->rawMethod, array('linkstory', 'importplanstories', 'unlinkstory', 'export'))) return false;
        if(($this->methodName == 'story' || $this->methodName == 'task') && $this->app->tab == 'doc') return false;

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
        $project         = $this->loadModel('project')->fetchByID($execution->project);

        /* Set menu. */
        $this->execution->setMenu($executionID, 0, $extra);

        /* Assign view data. */
        $this->view->projectID       = $execution->project;
        $this->view->hidden          = !empty($project->hasProduct) ? '' : 'hide';
        $this->view->executions      = $this->executions;
        $this->view->execution       = $execution;
        $this->view->executionID     = $executionID;
        $this->view->childExecutions = $childExecutions;
        $this->view->products        = $products;
        $this->view->teamMembers     = $teamMembers;
        $this->view->actions         = $actions;

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
    public function task(int $executionID = 0, string $status = 'unclosed', int $param = 0, string $orderBy = '', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1, string $from = 'execution', int $blockID = 0)
    {
        $this->app->loadLang('doc');
        if(($from == 'doc' || $from == 'ai') && empty($this->executions)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noExecution));

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        /* Save to session. */
        $this->session->set('taskList', $this->app->getURI(true), 'execution');

        /* Set browse type. */
        $browseType  = strtolower($status);
        $execution   = $this->commonAction($executionID, $status);
        $executionID = $execution->id;
        if($execution->type == 'kanban' && $this->config->vision != 'lite' && $this->app->getViewType() != 'json' && $from != 'doc') $this->locate($this->createLink('execution', 'kanban', "executionID=$executionID"));

        /* Save the recently five executions visited in the cookie. */
        $this->executionZen->setRecentExecutions($executionID);

        /* Append id for second sort and process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionTaskOrder ? $this->cookie->executionTaskOrder : 'status,id_desc';
        $sort = common::appendOrder($orderBy);
        $this->executionZen->setTaskPageStorage($executionID, $sort, $browseType, (int)$param);

        /* Set queryID, moduleID and productID. */
        $queryID = $moduleID = $productID = 0;
        if($browseType == 'bysearch')  $queryID   = (int)$param;
        if($browseType == 'bymodule')  $moduleID  = (int)$param;
        if($browseType == 'byproduct') $productID = (int)$param;
        if(!in_array($browseType, array('bysearch', 'bymodule', 'byproduct')))
        {
            $moduleID  = $this->cookie->moduleBrowseParam  ? $this->cookie->moduleBrowseParam  : 0;
            $productID = $this->cookie->productBrowseParam ? $this->cookie->productBrowseParam : 0;
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml' || $this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $tasks = $this->execution->getTasks((int)$productID, $executionID, $this->executions, $browseType, $queryID, (int)$moduleID, $sort, $pager);

        /* team member pairs. */
        $memberPairs = array();
        foreach($this->view->teamMembers as $key => $member) $memberPairs[$key] = $member->realname;
        $memberPairs = $this->loadModel('user')->setCurrentUserFirst($memberPairs);

        if($this->config->edition != 'open') $taskRelatedObject = $this->loadModel('custom')->getRelatedObjectList(array_keys($tasks), 'task', 'byRelation', true);

        /* Append branches to task. */
        $this->loadModel('task');
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($this->view->products));
        foreach($tasks as $task)
        {
            $task->name = htmlspecialchars_decode($task->name);
            if($task->mode == 'multi' && strpos('done,closed', $task->status) === false)
            {
                $task->assignedTo = '';

                $taskTeam = $this->task->getTeamByTask($task->id);
                foreach($taskTeam as $teamMember)
                {
                    if($this->app->user->account == $teamMember->account && $teamMember->status != 'done')
                    {
                        $task->assignedTo = $this->app->user->account;
                        break;
                    }
                }
            }
            if(isset($branchGroups[$task->product][$task->branch])) $task->branch = $branchGroups[$task->product][$task->branch];
            if($task->needConfirm) $task->status = $task->rawStatus = 'changed';
            if($this->config->edition != 'open') $task->relatedObject = zget($taskRelatedObject, $task->id, 0);
        }

        $showAllModule = empty($this->config->execution->task->allModule) ? '' : 'allModule';
        $showModule    = !empty($this->config->execution->task->showModule) ? $this->config->execution->task->showModule : '';

        /* Build the search form. */
        $modules   = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, $showModule);
        $actionURL = $this->createLink('execution', 'task', "executionID=$executionID&status=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=0&recPerPage=100&pageID=1&from=$from&blockID=$blockID");
        $this->execution->buildTaskSearchForm($executionID, $this->executions, $queryID, $actionURL);

        $this->view->title       = $execution->name . $this->lang->hyphen . $this->lang->execution->task;
        $this->view->tasks       = $tasks;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->browseType  = $browseType;
        $this->view->status      = $status;
        $this->view->param       = $param;
        $this->view->moduleID    = $moduleID;
        $this->view->modules     = $modules;
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs($executionID, 'task', $showModule) : array();
        $this->view->moduleTree  = $this->tree->getTaskTreeMenu($executionID, (int)$productID, 0, array('treeModel', 'createTaskLink'), $showAllModule);
        $this->view->memberPairs = $memberPairs;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->productID   = $productID;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
        $this->view->from        = $from;
        $this->view->blockID     = $blockID;
        $this->view->idList      = '';

        if($from === 'doc')
        {
            $docBlock = $this->loadModel('doc')->getDocBlock($blockID);
            $this->view->docBlock = $docBlock;
            if($docBlock)
            {
                $content = json_decode($docBlock->content, true);
                if(isset($content['idList'])) $this->view->idList = $content['idList'];
            }
        }

        $this->display();
    }

    /**
     * 任务分组视图。
     * Browse tasks in group.
     *
     * @param  int    $executionID
     * @param  string $groupBy     story|status|pri|assignedTo|finishedBy|closedBy|type
     * @param  string $filter
     * @access public
     * @return void
     */
    public function grouptask(int $executionID = 0, string $groupBy = 'story', string $filter = '')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Execution of the ops type don't show group by story. */
        if(empty($groupBy))$groupBy = 'story';
        if($groupBy == 'story' && $execution->lifetime == 'ops')
        {
            $groupBy = 'status';
            unset($this->lang->execution->groups['story']);
        }

        $sort        = common::appendOrder($groupBy);
        $tasks       = $this->loadModel('task')->getExecutionTasks($executionID, 0, 'all', array(), $sort);
        $groupBy     = str_replace('`', '', $groupBy);
        $groupTasks  = array();
        $allCount    = 0;
        foreach($tasks as $task)
        {
            if($task->mode == 'multi') $task->assignedToRealName = $this->lang->task->team;

            $groupTasks[] = $task;
            $allCount ++;
            if(isset($task->children))
            {
                foreach($task->children as $child)
                {
                    $groupTasks[] = $child;
                    $allCount ++;
                }
                unset($task->children);
            }
        }

        /* Get users and build task group data. */
        $users = $this->loadModel('user')->getPairs('noletter');
        $tasks = $groupTasks;
        list($groupTasks, $groupByList) = $this->executionZen->buildGroupTasks($groupBy, $groupTasks, array('' => '') + $users);

        /* Remove task by filter and group. */
        $filter = (empty($filter) && isset($this->lang->execution->groupFilter[$groupBy])) ? key($this->lang->execution->groupFilter[$groupBy]) : $filter;
        list($groupTasks, $allCount) = $this->executionZen->filterGroupTasks($groupTasks, $groupBy, $filter, $allCount, $tasks);

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        /* Assign. */
        $this->view->title       = $execution->name . $this->lang->hyphen . $this->lang->execution->task;
        $this->view->members     = $this->execution->getTeamMembers($executionID);
        $this->view->tasks       = $groupTasks;
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = 'group';
        $this->view->groupBy     = $groupBy;
        $this->view->orderBy     = $groupBy;
        $this->view->executionID = $executionID;
        $this->view->users       = $users;
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
        $this->view->filter      = $filter;
        $this->view->allCount    = $allCount;
        $this->view->execution   = $execution;
        $this->view->isLimited   = !$this->app->user->admin && strpos(",{$this->session->limitedExecutions},", ",{$executionID},") !== false;
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
            $taskIdList = form::data($this->config->execution->form->importTask)->get('taskIdList');
            $dateExceed = $this->execution->importTask($toExecution, $taskIdList);

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
        if(!empty($tasks2ImportedList)) $tasks2ImportedList = isset($tasks2ImportedList[$pageID - 1]) ? $tasks2ImportedList[$pageID - 1] : current($tasks2ImportedList);
        $tasks2ImportedList = $this->loadModel('task')->processTasks($tasks2ImportedList);

        $this->view->title          = $execution->name . $this->lang->hyphen . $this->lang->execution->importTask;
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->executions     = $executions;
        $this->view->fromExecution  = $fromExecution;
        $this->view->tasks2Imported = $tasks2ImportedList;
        $this->view->memberPairs    = $this->loadModel('user')->getPairs('noletter|pofirst');
        $this->display();
    }

    /**
     * 导入Bug。
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
        $this->session->set('bugList', $this->app->getURI(true), 'execution');
        $this->execution->setMenu($executionID);
        $execution = $this->execution->getByID($executionID);
        if(!empty($_POST))
        {
            $postData = form::batchData($this->config->execution->form->importBug)->get();
            $tasks    = $this->executionZen->buildTasksForImportBug($execution, $postData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->execution->importBug($tasks);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        /* Get users, products, executions, project and projects.*/
        $users      = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $products   = $this->loadModel('product')->getProductPairsByProject($executionID);
        $executions = !empty($products) ? $this->execution->getPairsByProduct(array_keys($products)) : array($executionID => $execution->name);
        $project    = $this->loadModel('project')->getByID($execution->project);
        !empty($products) ? $projects = $this->product->getProjectPairsByProductIDList(array_keys($products)) : $projects[$project->id] = $project->name;

        /* Set browseType, productID, moduleID and queryID. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get bugs.*/
        $bugs = $this->executionZen->getImportBugs($executionID, array_keys($products), $browseType, $queryID, $pager);

        /* Build the search form. */
        $this->executionZen->buildImportBugSearchForm($execution, $queryID, $products, $executions, $projects);

        if($this->app->tab == 'project') $this->view->projectID = $execution->project;

        /* Assign. */
        $this->view->title       = $executions[$executionID] . $this->lang->hyphen . $this->lang->execution->importBug;
        $this->view->pager       = $pager;
        $this->view->bugs        = $bugs;
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->users       = $users;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->display();
    }

    /**
     * 需求列表。
     * Browse stories of a execution.
     *
     * @param  int    $executionID
     * @param  string $storyType   story|requirement
     * @param  string $orderBy
     * @param  string $type        all|byModule|byProduct|byBranch|bySearch
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story(int $executionID = 0, string $storyType = 'story', string $orderBy = 'order_desc', string $type = 'all', int $param = 0, int $recTotal = 0, int $recPerPage = 50, int $pageID = 1, string $from = 'execution', int $blockID = 0)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('requirement');
        $this->loadModel('epic');
        $this->loadModel('product');
        $this->app->loadLang('doc');

        if($from == 'doc' && empty($this->executions)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noExecution));

        /* Change for requirement story title. */
        $this->lang->story->linkStory = str_replace($this->lang->URCommon, $this->lang->SRCommon, $this->lang->story->linkStory);
        if($storyType == 'requirement')
        {
            $this->story->replaceURLang($storyType);
            $this->config->product->search['fields']['title'] = $this->lang->story->title;
            unset($this->config->product->search['fields']['plan']);
            unset($this->config->product->search['params']['plan']);
            unset($this->config->product->search['fields']['stage']);
            unset($this->config->product->search['params']['stage']);
        }

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->executionStoryOrder ? $this->cookie->executionStoryOrder : 'pri';

        $param     = (int)$param;
        $type      = strtolower($type);
        $productID = $this->executionZen->setStorageForStory((string)$executionID, $type, (string)$param, $orderBy);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        $project     = $this->loadModel('project')->getByID($execution->project);

        /* Build the search form. */
        $products  = $this->product->getProducts($executionID);
        $actionURL = $this->createLink('execution', 'story', "executionID=$executionID&storyType=$storyType&orderBy=$orderBy&type=bySearch&queryID=myQueryID&recTotal=0&recPerPage=&pageID=1&from=$from&blockID=$blockID");
        $this->executionZen->buildStorySearchForm($execution, $productID, $products, $type == 'bysearch' ? $param : 0, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $stories = $this->story->getExecutionStories($executionID, 0, $sort, $type, (string)$param, $project->storyType ?? 'story', '', $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        if(!empty($stories)) $stories = $this->story->mergeReviewer($stories);
        if($this->config->edition == 'ipd') $stories = $this->loadModel('story')->getAffectObject($stories, 'story');

        $this->executionZen->assignCountForStory($executionID, $stories, $storyType);
        $this->executionZen->assignRelationForStory($execution, $products, $productID, $type, $storyType, $param, $orderBy, $pager);

        $this->view->from    = $from;
        $this->view->blockID = $blockID;
        $this->view->idList  = '';
        if($from === 'doc')
        {
            $docBlock = $this->loadModel('doc')->getDocBlock($blockID);
            $this->view->docBlock = $docBlock;
            if($docBlock)
            {
                $content = json_decode($docBlock->content, true);
                if(isset($content['idList'])) $this->view->idList = $content['idList'];
            }
            $this->view->executions = $this->execution->getPairs();
        }

        $this->view->productID          = $productID;
        $this->view->project            = $project;
        $this->view->linkedProductCount = count($products);
        $this->display();
    }

    /**
     * 查看需求详情。
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storyView(int $storyID, int $executionID = 0)
    {
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $story = $this->loadModel('story')->getByID($storyID);
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$story->version&param=" . ($executionID ? $executionID : $this->session->execution) . "&storyType={$story->type}");
    }

    /**
     * Bug列表。
     * Browse bugs of a execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $orderBy
     * @param  string $build
     * @param  string $type
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bug(int $executionID = 0, int $productID = 0, string $branch = 'all', string $orderBy = 'status,id_desc', string $build = '', string $type = 'all', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Save session and load model. */
        $this->loadModel('bug');
        $this->session->set('bugList', $this->app->getURI(true), 'execution');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $type        = strtolower($type);
        $execution   = $this->commonAction($executionID);
        $project     = $this->loadModel('project')->getByID($execution->project);
        $executionID = $execution->id;
        $products    = $this->product->getProducts($execution->id);
        $param       = in_array($type, array('bysearch', 'bymodule')) ? (int)$param : 0;

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
        $bugs  = $this->bug->getExecutionBugs($executionID, $productID, $branch, $build, $type, $param, $sort, '', $pager);
        $bugs  = $this->bug->batchAppendDelayedDays($bugs);

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'bug', "executionID=$executionID&productID=$productID&branch=$branch&orderBy=$orderBy&build=$build&type=bysearch&queryID=myQueryID");
        $this->execution->buildBugSearchForm($products, $param, $actionURL);

        $bugs = $this->bug->processBuildForBugs($bugs);
        $this->executionZen->assignBugVars($execution, $project, $productID, $branch, $products, $orderBy, $type, $param, $build, $bugs, $pager);

        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }

        $this->view->showBranch     = $showBranch;
        $this->view->productOption  = $productOption;
        $this->view->branchOption   = $branchOption;
        $this->view->plans          = $this->loadModel('productplan')->getForProducts(array_keys($products));
        $this->view->tasks          = $this->loadModel('task')->getPairsByIdList($taskIdList);
        $this->view->stories        = $this->loadModel('story')->getPairsByList($storyIdList);
        $this->view->moduleID       = $type == 'bymodule' ? $param : 0;
        $this->view->switcherParams = "executionID={$executionID}&productID={$productID}&currentMethod=bug";
        $this->view->switcherText   = isset($products[$productID]) ? $products[$productID]->name : $this->lang->product->all;
        if(empty($project->hasProduct)) $this->config->excludeSwitcherList[] = 'execution-bug';
        $this->display();
    }

    /**
     * 用例列表。
     * Execution case list.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $branchID
     * @param  string $type
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testcase(int $executionID = 0, int $productID = 0, string $branchID = 'all', string $type = 'all', int $param = 0, int $moduleID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->commonAction($executionID);
        $uri = $this->app->getURI(true);
        $this->session->set('caseList', $uri, 'execution');
        $this->session->set('bugList',  $uri, 'execution');

        $products = $this->product->getProducts($executionID);
        if(count($products) == 1) $productID = key($products);

        $execution     = $this->execution->getByID($executionID);
        $productOption = array();
        $branchOption  = array();
        if($execution->hasProduct)
        {
            list($productOption, $branchOption) = $this->executionZen->buildProductSwitcher($executionID, $productID, $products);
        }

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        unset($this->config->testcase->dtable->fieldList['title']['nestedToggle']);
        if($productID && $products[$productID]->type == 'normal') unset($this->config->testcase->dtable->fieldList['branch']);

        /* Build the search form. */
        $actionURL = $this->createLink('execution', 'testcase', "executionID=$executionID&productID=$productID&branchID=$branchID&type=bysearch&queryID=myQueryID&moduleID=0&orderBy=$orderBy");
        $this->execution->buildCaseSearchForm($products, $param, $actionURL, $executionID);

        $this->executionZen->assignTestcaseVars($executionID, $productID, $branchID, $moduleID, $param, $orderBy, $type, $pager);

        $this->view->execution        = $execution;
        $this->view->productOption    = $productOption;
        $this->view->branchOption     = $branchOption;
        $this->view->switcherParams   = "executionID={$executionID}&productID={$productID}&currentMethod=testcase";
        $this->view->switcherText     = isset($products[$productID]) ? $products[$productID]->name : $this->lang->product->all;
        $this->view->switcherObjectID = $productID;
        if(empty($execution->hasProduct)) $this->config->excludeSwitcherList[] = 'execution-testcase';
        $this->display();
    }

    /**
     * 测试报告列表。
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
    public function testreport(int $executionID = 0, string $objectType = 'execution', string $extra = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        echo $this->fetch('testreport', 'browse', "objectID=$executionID&objectType=$objectType&extra=$extra&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 版本列表。
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
        $type = strtolower($type);
        $this->project->buildProjectBuildSearchForm($products, $type == 'bysearch' ? (int)$param : 0, $executionID, $param, 'execution');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Get builds. */
        $this->loadModel('build');
        if($type == 'bysearch')
        {
            $builds = $this->loadModel('build')->getExecutionBuildsBySearch($executionID, (int)$param, $pager);
        }
        else
        {
            $builds = $this->loadModel('build')->getExecutionBuilds($executionID, $type, (string)$param, $orderBy, $pager);
        }

        /* Set view data. */
        $this->view->title    = $execution->name . $this->lang->hyphen . $this->lang->execution->build;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->builds   = $this->executionZen->processBuildListData($builds, $executionID);
        $this->view->product  = $type == 'product' ? $param : '';
        $this->view->products = $products;
        $this->view->system   = $this->loadModel('system')->getPairs();
        $this->view->type     = $type;
        $this->view->param    = $param;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * 测试单列表页面。
     * Browse test tasks of execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function testtask(int $executionID = 0, int $productID = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('testtask');
        $this->app->loadLang('testreport');

        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true), 'execution');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        $sort = str_replace('taskID', 'id', $orderBy);
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $tasks = $this->testtask->getExecutionTasks($executionID, $productID, 'execution', 'product_asc,' . $sort, $pager);

        $this->executionZen->assignTesttaskVars($tasks);

        $this->view->title         = $this->executions[$executionID] . $this->lang->hyphen . $this->lang->testtask->common;
        $this->view->execution     = $execution;
        $this->view->project       = $this->loadModel('project')->getByID($execution->project);
        $this->view->executionID   = $executionID;
        $this->view->productID     = $productID;
        $this->view->executionName = $this->executions[$executionID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->products      = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        $this->view->canBeChanged  = common::canModify('execution', $execution); // Determines whether an object is editable.

        $this->display();
    }

    /**
     * 燃尽图。
     * Browse burndown chart of a execution.
     *
     * @param  int    $executionID
     * @param  string $type        noweekend|withweekend
     * @param  string $interval
     * @param  string $burnBy      left|estimate|storyPoint
     * @access public
     * @return void
     */
    public function burn(int $executionID = 0, string $type = 'noweekend', string $interval = '', string $burnBy = 'left')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        /* Determine whether to display delay data. */
        if(((strpos('closed,suspended', $execution->status) === false && helper::today() > $execution->end)
            || ($execution->status == 'closed'    && substr($execution->closedDate, 0, 10) > $execution->end)
            || ($execution->status == 'suspended' && $execution->suspendedDate > $execution->end))
            && strpos($type, 'delay') === false)
        $type .= ',withdelay';

        /* Get burn date list and interval. */
        $deadline = $execution->status == 'closed' ? substr($execution->closedDate, 0, 10) : $execution->suspendedDate;
        $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
        $endDate  = strpos($type, 'withdelay') !== false ? $deadline : $execution->end;
        list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, $interval, 'Y-m-d', $execution->end);

        $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
        $burnBy       = $this->cookie->burnBy ? $this->cookie->burnBy : $burnBy;
        $chartData    = $this->execution->buildBurnData($executionID, $dateList, $burnBy, $executionEnd);

        $allDateList = date::getDateList($execution->begin, $endDate, 'Y-m-d', $type, $this->config->execution->weekend);
        $dayList     = array_fill(1, (int)floor(count($allDateList) / $this->config->execution->maxBurnDay) + 5, '');
        foreach($dayList as $key => $val) $dayList[$key] = $this->lang->execution->interval . ($key + 1) . $this->lang->day;

        unset($this->lang->TRActions);

        /* Shows the variables needed to burn page. */
        $this->view->title         = $execution->name . $this->lang->hyphen . $this->lang->execution->burn;
        $this->view->tabID         = 'burn';
        $this->view->burnBy        = $burnBy;
        $this->view->executionID   = $executionID;
        $this->view->executionName = $execution->name;
        $this->view->type          = $type;
        $this->view->interval      = $interval;
        $this->view->chartData     = $chartData;
        $this->view->dayList       = array('full' => $this->lang->execution->interval . '1' . $this->lang->day) + $dayList;

        $this->display();
    }

    /**
     * 更新燃尽图数据。
     * Compute burndown data.
     *
     * @param  string $reload
     * @access public
     * @return void
     */
    public function computeBurn(string $reload = 'no')
    {
        $burns = $this->execution->computeBurn();

        if($reload == 'yes') return $this->send(array('load' => true, 'result' => 'success'));
        return print(json_encode($burns));

    }

    /**
     * 累计流图。
     * Cumulative flow diagram.
     *
     * @param  int    $executionID
     * @param  string $type        story|bug|task
     * @param  string $withWeekend
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function cfd(int $executionID = 0, string $type = 'story', string $withWeekend = 'false', string $begin = '', string $end = '')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        $this->app->loadClass('date');
        $minDate = !helper::isZeroDate($execution->openedDate) ? date('Y-m-d', strtotime($execution->openedDate)) : date('Y-m-d', strtotime($execution->begin));
        $maxDate = !helper::isZeroDate($execution->closedDate) ? date('Y-m-d', strtotime($execution->closedDate)) : helper::today();
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) $type = 'task';

        if(!empty($_POST))
        {
            $begin = htmlspecialchars($this->post->begin, ENT_QUOTES);
            $end   = htmlspecialchars($this->post->end, ENT_QUOTES);

            $this->executionZen->checkCFDDate($begin, $end, $minDate, $maxDate);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->execution->computeCFD($executionID);
            $this->execution->updateCFDData($executionID, $begin);
            return $this->send(array('result' => 'success', 'locate' => $this->createLink('execution', 'cfd', "executionID=$executionID&type=$type&withWeekend=$withWeekend&begin=" . helper::safe64Encode(urlencode($begin)) . "&end=" . helper::safe64Encode(urlencode($end)))));
        }

        if($begin && $end)
        {
            $begin = urldecode(helper::safe64Decode($begin));
            $end   = urldecode(helper::safe64Decode($end));
        }
        else
        {
            list($begin, $end) = $this->execution->getBeginEnd4CFD($execution);
        }
        $dateList  = date::getDateList($begin, $end, 'Y-m-d', $withWeekend == 'false'? 'noweekend' : '');
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
     * 计算累计流图数据。
     * Compute cfd data.
     *
     * @param  string $reload
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function computeCFD(string $reload = 'no', int $executionID = 0)
    {
        $this->execution->computeCFD($executionID);
        if($reload == 'yes') return $this->sendSuccess(array('load' => true));
    }

    /**
     * 修改燃尽图的首天工时
     * Fix burn for first date.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function fixFirst(int $executionID)
    {
        $execution = $this->execution->getByID($executionID);

        if($_POST)
        {
            if(!preg_match("/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|[1-9]\d*$/", (string)$this->post->estimate) || $this->post->estimate <= 0)
            {
                dao::$errors['estimate'] = sprintf($this->lang->execution->errorFloat, $this->lang->execution->estimate);
                return $this->sendError(dao::getError());
            }

            $burn     = $this->execution->getBurnByExecution($executionID, $execution->begin, 0);
            $left     = empty($burn) ? 0 : $burn->left;
            $withLeft = $this->post->withLeft ? $this->post->withLeft : 0;
            if($withLeft) $left = $this->post->estimate;
            $burnData = form::data($this->config->execution->form->fixfirst)
                ->add('task', 0)
                ->add('execution', $executionID)
                ->add('date', $execution->begin)
                ->add('left', $left)
                ->add('consumed', empty($burn) ? 0 : $burn->consumed)
                ->get();

            if(!$burnData->left) $burnData->left = 0;

            if(is_numeric($burnData->estimate)) $this->execution->fixFirst($burnData);
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->firstBurn = $this->execution->getBurnByExecution($executionID, $execution->begin);
        $this->view->execution = $execution;
        $this->display();
    }

    /**
     * 执行团队列表。
     * Browse team of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function team(int $executionID = 0)
    {
        $execution   = $this->commonAction($executionID);
        $deptID      = $this->app->user->admin ? 0 : $this->app->user->dept;
        $teamMembers = array();
        foreach($this->view->teamMembers as $member)
        {
            $member->days    = $member->days . $this->lang->execution->day;
            $member->hours   = helper::formatHours($member->hours) . $this->lang->execution->workHour;
            $member->total   = helper::formatHours($member->totalHours) . $this->lang->execution->workHour;
            $member->actions = array();
            if(common::hasPriv('execution', 'unlinkMember', $member) && common::canModify('execution', $execution)) $member->actions = array('unlink');

            $teamMembers[] = $member;
        }

        $this->view->title        = $execution->name . $this->lang->hyphen . $this->lang->execution->team;
        $this->view->deptUsers    = $this->loadModel('dept')->getDeptUserPairs($deptID, 'id');
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->recTotal     = count($this->view->teamMembers);
        $this->view->teamMembers  = $teamMembers;

        $this->display();
    }

    /**
     * Create a execution.
     *
     * @param int    $projectID
     * @param int    $executionID
     * @param int    $copyExecutionID
     * @param int    $planID
     * @param string $confirm
     * @param int    $productID
     * @param string $extra
     *
     * @access public
     * @return void
     */
    public function create(int $projectID = 0, int $executionID = 0, int $copyExecutionID = 0, int $planID = 0, string $confirm = 'no', int $productID = 0, string $extra = '')
    {
        $project = empty($projectID) ? null : $this->loadModel('project')->fetchByID($projectID);
        if(!empty($project->isTpl)) dao::$filterTpl = 'never';

        if($this->app->tab == 'doc')     unset($this->lang->doc->menu->execution['subMenu']);
        if($this->app->tab == 'project') $this->project->setMenu($projectID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $execution = $this->executionZen->initFieldsForCreate($projectID, $output);
        if($copyExecutionID) $this->executionZen->setFieldsByCopyExecution($execution, $copyExecutionID);
        $projectID = isset($execution->project) ? $execution->project : $projectID;

        if($executionID) return $this->executionZen->displayAfterCreated($projectID, $executionID, $planID, $confirm);

        $allProjects = $this->project->getPairsByModel('all', 'noclosed,multiple');
        $this->loadModel('project')->checkAccess($projectID, $allProjects);
        if(empty($projectID)) $projectID = key($allProjects) ? key($allProjects) : 0;

        $project = empty($projectID) ? null : $this->loadModel('project')->fetchByID($projectID);
        $project ? $this->executionZen->correctExecutionCommonLang($project, $execution->type) : $project = null;
        $products = $this->executionZen->getLinkedProducts($copyExecutionID, $planID, $project);
        $this->executionZen->setLinkedBranches($products, $copyExecutionID, $planID, $project);

        if(!empty($_POST))
        {
            /* Filter empty plans. */
            if(isset($_POST['attribute']) and $_POST['attribute'] == 'review') unset($_POST['plans']);
            if(!empty($_POST['plans']))
            {
                foreach($_POST['plans'] as $key => $planItem) $_POST['plans'][$key] = array_filter($_POST['plans'][$key]);
                $_POST['plans'] = array_filter($_POST['plans']);
            }

            $execution = $this->executionZen->buildExecutionForCreate();
            if(!$execution) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $executionID = $this->execution->create($execution, isset($_POST['teamMembers']) ? $_POST['teamMembers'] : array());
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create($this->objectType, $executionID, 'opened', '', $project->hasProduct ? implode(',', $_POST['products']) : '');
            if(!empty($projectID) and strpos(',kanban,agileplus,waterfallplus,ipd,', ",$project->model,") !== false and $execution->type == 'kanban')
            {
                $execution = $this->execution->fetchByID($executionID);
                $this->loadModel('kanban')->createRDKanban($execution);
            }

            $message = $this->executeHooks($executionID);
            if(empty($message)) $message = $this->lang->saveSuccess;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $executionID));

            $location = $this->executionZen->getAfterCreateLocation($projectID, $executionID, $project->model);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
        }

        list($this->view->pmUsers, $this->view->poUsers, $this->view->qdUsers, $this->view->rdUsers) = $this->executionZen->setUserMoreLink();
        $this->executionZen->setCopyProjects($project);

        $isStage = isset($output['type']) && $output['type'] == 'stage';
        if(!$isStage && !empty($this->view->execution) && $this->view->execution->type == 'stage') $isStage = true;
        if(!empty($project) && in_array($project->model, $this->config->project->waterfallList))
        {
            if(in_array($project->model, $this->config->project->waterfallList) && !isset($output['type'])) $isStage = true;
            $parentStage = isset($output['parentStage']) ? $output['parentStage'] : 0;
            $type        = isset($output['type']) ? $output['type'] : '';
            if($parentStage)
            {
                $parent = $this->execution->fetchById((int)$parentStage);
                if($parent->attribute != 'mix')
                {
                    $this->app->loadLang('stage');
                    foreach($this->lang->stage->typeList as $type => $label)
                    {
                        if($type != $parent->attribute) unset($this->lang->stage->typeList[$type]);
                    }
                }
            }
            else
            {
                unset($this->lang->execution->typeList['kanban']);
                unset($this->lang->execution->typeList['sprint']);
            }

            $this->view->parentStage  = $parentStage;
            $this->view->parentStages = $this->loadModel('programplan')->getParentStageList($projectID, 0, 0, 'withparent|noclosed|' . ($isStage ? 'stage' : 'notstage'));
        }

        $this->view->title               = $this->app->tab == 'execution' ? $this->lang->execution->createExec : $this->lang->execution->create;
        $this->view->gobackLink          = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'all') : '';
        $this->view->allProducts         = array_filter($this->executionZen->getAllProductsForCreate($project));
        $this->view->allProjects         = $allProjects;
        $this->view->multiBranchProducts = $this->loadModel('product')->getMultiBranchPairs();
        $this->view->products            = $products;
        $this->view->teams               = $this->execution->getCanCopyObjects((int)$projectID);
        $this->view->users               = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->copyExecutionID     = $copyExecutionID;
        $this->view->productID           = $productID;
        $this->view->projectID           = $projectID;
        $this->view->from                = $this->app->tab;
        $this->view->isStage             = $isStage;
        $this->view->isKanban            = isset($output['type']) && $output['type'] == 'kanban';
        $this->view->project             = $project;
        $this->view->planID              = $planID;

        $this->display();
    }

    /**
     * 编辑一个执行。
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
        /* Update linked plans if confirmed and new plans added. */
        $this->executionZen->updateLinkedPlans($executionID, $newPlans, $confirm);

        $this->loadModel('product');
        $this->loadModel('programplan');
        $this->loadModel('productplan');
        $this->app->loadLang('program');
        $this->app->loadLang('stage');

        $execution           = $this->execution->getById($executionID);
        $project             = $this->project->getById($execution->project);
        $branches            = $this->project->getBranchesByProject($executionID);
        $linkedProductIdList = empty($branches) ? '' : array_keys($branches);

        /* Set menu. */
        $this->execution->setMenu($executionID);

        if(!empty($_POST))
        {
            $oldProducts  = $this->product->getProducts($executionID, 'all', '', true, $linkedProductIdList);
            $oldPlans     = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->andWhere('plan')->ne(0)->fetchPairs('plan');
            $oldExecution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

            /* Get the data from the post. */
            $formData = form::data(null, $executionID)
                ->add('id', $executionID)
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
                ->setIF(helper::isZeroDate($this->post->begin), 'begin', '')
                ->setIF(helper::isZeroDate($this->post->end), 'end', '')
                ->setIF($this->post->status == 'closed' && $oldExecution->status != 'closed', 'closedDate', helper::now())
                ->setIF($this->post->status == 'closed' && $oldExecution->status != 'closed', 'realEnd',    helper::today())
                ->setIF($this->post->status == 'suspended' && $oldExecution->status != 'suspended', 'suspendedDate', helper::today())
                ->setIF($oldExecution->type == 'stage', 'project', $oldExecution->project)
                ->setDefault('project', $oldExecution->project)
                ->setDefault('team', $this->post->name)
                ->setDefault('branch', $this->post->branch)
                ->setDefault('attribute', $oldExecution->attribute)
                ->get();

            $changes = $this->execution->update($executionID, $formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->updateProducts($executionID, $formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            if($action == 'undelete')
            {
                $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq('0')->where('id')->eq($executionID)->exec();
                $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($extra)->exec();
                $this->action->create($this->objectType, $executionID, 'undeleted');
            }

            $oldProducts  = array_keys($oldProducts);
            $newProducts  = $this->product->getProducts($executionID, 'all', '', true, $linkedProductIdList);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            $products     = $diffProducts ? implode(',', $newProducts) : '';

            if($changes or $diffProducts)
            {
                $actionID = $this->action->create($this->objectType, $executionID, 'edited', '', $products);
                $this->action->logHistory($actionID, $changes);
            }

            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->programplan->computeProgress($executionID, 'edit');

            /* Redirect to confirm page if the execution can link plan stories. */
            $this->executionZen->checkLinkPlan($executionID, $oldPlans);

            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($formData->status == 'doing') $this->loadModel('common')->syncPPEStatus($executionID);

            /* If link from no head then reload. */
            if(isInModal())
            {
                $kanbanLoad = array('selector' => '#main>*');
                return $this->sendSuccess(array('closeModal' => true, 'callback' => $execution->type == 'kanban' ? 'loadCurrentPage(' . json_encode($kanbanLoad) . ');' : 'loadCurrentPage()'));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('view', "executionID=$executionID")));
        }

        $executions = $this->executions;
        unset($executions[$executionID]); /* Remove current execution from the executions. */

        /* Get linked objects of this execution. */
        $linkedObjects = $this->executionZen->getLinkedObjects($execution);

        list($pmUsers, $poUsers, $qdUsers, $rdUsers) = $this->executionZen->setUserMoreLink($execution);

        if(!$project->hasProduct) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            $parentStage = $this->project->getByID($execution->parent, 'stage');
            $this->view->enableOptionalAttr = (empty($parentStage) or (!empty($parentStage) and $parentStage->attribute == 'mix'));
        }
        if($this->app->tab == 'project' and $project->model == 'waterfallplus')
        {
            $productID       = $this->product->getProductIDByProject($executionID);
            $parentStageList = $this->programplan->getParentStageList($execution->project, $executionID, $productID);
            unset($parentStageList[0]);
        }

        if(empty($project->hasProduct))
        {
            $linkedProduct = current($linkedObjects->linkedProducts);
            $linkedPlans   = !empty($linkedProduct->plans) ? array_values($linkedProduct->plans) : array();
        }

        $this->view->title                = $this->lang->execution->edit . $this->lang->hyphen . $execution->name;
        $this->view->executions           = $executions;
        $this->view->execution            = $execution;
        $this->view->project              = $project;
        $this->view->poUsers              = $poUsers;
        $this->view->pmUsers              = $pmUsers;
        $this->view->qdUsers              = $qdUsers;
        $this->view->rdUsers              = $rdUsers;
        $this->view->users                = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->groups               = $this->loadModel('group')->getPairs();
        $this->view->branches             = $branches;
        $this->view->allProducts          = $linkedObjects->allProducts;
        $this->view->multiBranchProducts  = $this->product->getMultiBranchPairs();
        $this->view->linkedProducts       = $linkedObjects->linkedProducts;
        $this->view->linkedBranches       = $linkedObjects->linkedBranches;
        $this->view->linkedStoryIDList    = $linkedObjects->linkedStoryIDList;
        $this->view->unmodifiableProducts = $linkedObjects->unmodifiableProducts;
        $this->view->unmodifiableBranches = $linkedObjects->unmodifiableBranches;
        $this->view->productPlans         = $linkedObjects->productPlans;
        $this->view->productPlan          = $linkedObjects->productPlan;
        $this->view->currentPlan          = empty($project->hasProduct) ? $linkedPlans : $linkedObjects->currentPlan;
        $this->view->branchGroups         = $this->execution->getBranchByProduct(array_keys($linkedObjects->linkedProducts), $execution->project, 'noclosed', $linkedObjects->linkedBranchList);
        $this->view->teamMembers          = $this->execution->getTeamMembers($executionID);
        $this->view->allProjects          = $this->project->getPairsByModel($project->model, 'noclosed|multiple', $project->id);
        $this->view->parentStageList      = isset($parentStageList) ? $parentStageList : array();
        $this->view->isStage              = isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus'));
        $this->view->unclosedTasks        = $this->loadModel('task')->getUnclosedTasksByExecution($executionID);
        $this->display();
    }

    /**
     * 批量编辑多个执行。
     * Batch edit the executions.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit(int $executionID = 0)
    {
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');

        if($this->app->tab == 'project')
        {
            $projectID = $this->session->project;
            $project   = $this->project->getById($projectID);
            $this->project->setMenu($projectID);
            $this->view->project = $project;
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->lang->execution->common = $this->lang->execution->stage;
            if($project->model == 'ipd') $this->config->execution->list->customBatchEditFields = 'days,team,desc,PO,QD,PM,RD';
        }
        else
        {
            $this->config->excludeDropmenuList[] = 'execution-batchedit';
        }

        if($this->post->name)
        {
            $postData   = fixer::input('post')->get();
            $allChanges = $this->execution->batchUpdate($postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks(key($this->post->id));
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

        $projectModel = $this->app->tab == 'project' ? $project->model : 'all';
        $allProjects  = $this->project->getPairsByModel($projectModel, 'noclosed', isset($projectID) ? $projectID : 0);

        if(!$this->post->executionIDList)
        {
            /* Use a fallback link to locate in case session has no related data. */
            $locateLink = !empty($this->session->executionList) ? $this->session->executionList : $this->createLink('execution', 'all');
            return $this->locate($locateLink);
        }

        $executionIDList = $this->post->executionIDList;
        $executions      = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIDList)->fetchAll('id', false);
        $relatedProjects = $this->dao->select('id,project')->from(TABLE_PROJECT)->where('id')->in($executionIDList)->fetchPairs(); /* 获取执行所属的项目列表。*/
        $projects        = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($relatedProjects)->fetchAll('id');        /* 获取执行所属的项目列表中每个项目的项目信息。*/

        list($pmUsers, $poUsers, $qdUsers, $rdUsers) = $this->executionZen->setUserMoreLink($executions);

        /* Set custom fields. */
        foreach(explode(',', $this->config->execution->list->customBatchEditFields) as $field)
        {
            $fieldTitle = $this->app->tab == 'execution' ? str_replace($this->lang->executionCommon, $this->lang->execution->common, $this->lang->execution->$field) : $this->lang->execution->$field;
            if($field == 'lifetime') $fieldTitle = $this->app->tab == 'execution' ? $this->lang->execution->execType : $this->lang->execution->type;
            if($field == 'team') $fieldTitle = $this->lang->execution->teamName;
            $customFields[$field] = $fieldTitle;
        }

        $parentIdList = array();
        foreach($executions as $execution)
        {
            if($execution->parent == 0) continue;
            $parentIdList[] = $execution->parent;
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->execution->custom->batchEditFields;
        $this->view->title        = $this->lang->execution->batchEdit;
        $this->view->executions   = $executions;
        $this->view->allProjects  = $allProjects;
        $this->view->projects     = $projects;
        $this->view->pmUsers      = $pmUsers;
        $this->view->poUsers      = $poUsers;
        $this->view->qdUsers      = $qdUsers;
        $this->view->rdUsers      = $rdUsers;
        $this->view->from         = $this->app->tab;
        $this->view->parents      = $this->execution->getByIdList($parentIdList);
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
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->sendError($this->lang->error->unsupportedReq);

        $executionIdList = $this->post->executionIDList;
        if(is_string($executionIdList)) $executionIdList = explode(',', $executionIdList);

        $message = $this->execution->batchChangeStatus($executionIdList, $status);
        if(empty($message['byChild']) && empty($message['byDeliverable'])) return $this->sendSuccess(array('load' => true));

        $alertMsg = '';
        if($status == 'wait')
        {
            $project = $this->loadModel('project')->getById($projectID);
            /* In execution-all list or waterfall, waterfallplus project's execution list. */
            if(empty($project) or (!empty($project) and strpos($project->model, 'waterfall') !== false))
            {
                $executionLang = (empty($project) or (!empty($project) and $project->model == 'waterfallplus')) ? $this->lang->execution->common : $this->lang->stage->common;
                $alertMsg      = sprintf($this->lang->execution->hasStartedTaskOrSubStage, $executionLang, $message['byChild']);
            }
            if(!empty($project) and strpos('agileplus,scrum', $project->model) !== false)
            {
                $executionLang = $project->model == 'scrum' ? $this->lang->executionCommon : $this->lang->execution->common;
                $alertMsg      = sprintf($this->lang->execution->hasStartedTask, $executionLang, $message['byChild']);
            }
        }
        if($status == 'suspended') $alertMsg = sprintf($this->lang->execution->hasSuspendedOrClosedChildren, $message['byChild']);
        if($status == 'closed')
        {
            if(!empty($message['byChild']))
            {
                $alertMsg .= sprintf($this->lang->execution->hasNotClosedChildren, $message['byChild']);
            }
            elseif(!empty($message['byDeliverable']))
            {
                $alertMsg .= sprintf($this->lang->execution->cannotCloseByDeliverable, $message['byDeliverable']);
            }
        }

        return $this->send(array('load' => array('alert' => $alertMsg), 'result' => 'success'));
    }

    /**
     * 开始一个执行。
     * Start the execution.
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

        $this->extendRequireFields($executionID);
        if(!empty($_POST))
        {
            $postData = fixer::input('post')
                ->add('id', $executionID)
                ->setDefault('status', 'doing')
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->stripTags($this->config->execution->editor->start['id'], $this->config->allowedTags)
                ->get();

            $this->execution->start($executionID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->loadModel('programplan')->computeProgress($executionID, 'start');

            $this->loadModel('common')->syncPPEStatus($executionID);
            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('doing')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->hyphen .$this->lang->execution->start;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * 延期一个迭代。
     * Delay the execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function putoff(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;

        $this->extendRequireFields($executionID);
        if(!empty($_POST))
        {
            $postData = fixer::input('post')
                ->add('id', $executionID)
                ->stripTags($this->config->execution->editor->putoff['id'], $this->config->allowedTags)
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->get();

            $this->execution->putoff($executionID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('doing')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->hyphen .$this->lang->execution->putoff;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * 挂起一个执行。
     * Suspend a execution.
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

        $this->extendRequireFields($executionID);
        if(!empty($_POST))
        {
            $postData = fixer::input('post')
                ->add('id', $executionID)
                ->setDefault('status', 'suspended')
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->setDefault('suspendedDate', helper::today())
                ->stripTags($this->config->execution->editor->suspend['id'], $this->config->allowedTags)
                ->get();

            $this->execution->computeBurn($executionID);
            $this->execution->suspend($executionID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->loadModel('programplan')->computeProgress($executionID, 'suspend');

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('suspended')";
            return $this->sendSuccess($response);
        }

        $this->view->title      = $this->view->execution->name . $this->lang->hyphen .$this->lang->execution->suspend;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->display();
    }

    /**
     * 激活一个执行。
     * Activate a execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function activate(int $executionID, string $from = 'execution')
    {
        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        if($execution->type == 'kanban') $this->lang->executionCommon = $this->lang->execution->kanban;

        $this->extendRequireFields($executionID);
        if(!empty($_POST))
        {
            $postData = fixer::input('post')
                ->add('id', $executionID)
                ->setDefault('realEnd', null)
                ->setDefault('status', 'doing')
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', helper::now())
                ->setDefault('closedBy', '')
                ->setDefault('closedDate', null)
                ->stripTags($this->config->execution->editor->activate['id'], $this->config->allowedTags)
                ->get();

            $this->execution->activate($executionID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->loadModel('programplan')->computeProgress($executionID, 'activate');

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('doing')";
            return $this->sendSuccess($response);
        }

        $newBegin = date('Y-m-d');
        $dateDiff = helper::diffDate($newBegin, $execution->begin);
        $newEnd   = date('Y-m-d', strtotime($execution->end) + $dateDiff * 24 * 3600);

        $this->view->title      = $this->view->execution->name . $this->lang->hyphen .$this->lang->execution->activate;
        $this->view->execution    = $execution;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->newBegin   = $newBegin;
        $this->view->newEnd     = $newEnd;
        $this->display();
    }

    /**
     * 关闭迭代。
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

        $this->extendRequireFields($executionID);
        if(!empty($_POST))
        {
            $now      = helper::now();
            $postData = fixer::input('post')
                ->add('id', $executionID)
                ->setDefault('status', 'closed')
                ->setDefault('closedBy', $this->app->user->account)
                ->setDefault('closedDate', $now)
                ->setDefault('lastEditedBy', $this->app->user->account)
                ->setDefault('lastEditedDate', $now)
                ->stripTags($this->config->execution->editor->close['id'], $this->config->allowedTags)
                ->get();

            $this->execution->computeBurn($executionID);
            $this->execution->close($executionID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->loadModel('project')->getById($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
            {
                $result = $this->loadModel('programplan')->computeProgress($executionID, 'close');
                if(is_array($result)) return $this->send($result);
            }

            $this->executeHooks($executionID);

            $response['closeModal'] = true;
            $response['load']       = true;
            if($from == 'kanban') $response['callback'] = "changeStatus('closed')";
            return $this->sendSuccess($response);
        }

        $this->view->title         = $this->view->execution->name . $this->lang->hyphen .$this->lang->execution->close;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->from          = $from;
        $this->view->actions       = $this->loadModel('action')->getList($this->objectType, $executionID);
        $this->view->unclosedTasks = $this->loadModel('task')->getUnclosedTasksByExecution($executionID);
        $this->display();
    }

    /**
     * 执行详情。
     * View a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function view(int $executionID)
    {
        /* Check execution permission. */
        $executionID = $this->execution->checkAccess((int)$executionID, $this->executions);
        $execution   = $this->execution->getByID($executionID, true);
        $type        = $this->config->vision == 'lite' ? 'kanban' : 'stage,sprint,kanban';
        if($execution->type == 'stage')
        {
            $childExecutions = $this->execution->getChildExecutions($executionID);
            if(!empty($childExecutions)) return $this->app->control->sendError($this->lang->execution->errorParentExecution, helper::createLink('execution', 'all'));
        }

        if(empty($execution) || strpos($type, $execution->type) === false) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->config->vision == 'lite' ? $this->createLink('project', 'index') : $this->createLink('execution', 'all'))));

        if($execution->type == 'kanban' and defined('RUN_MODE') and RUN_MODE == 'api') return print($this->fetch('execution', 'kanban', "executionID=$executionID"));

        /* Load lang and set session. */
        $this->app->loadLang('program');
        $this->app->loadLang('bug');
        $this->app->session->set('teamList', $this->app->getURI(true), 'execution');

        $this->execution->setMenu($execution->id);

        /* Execution not found to prevent searching for. */
        if(!isset($this->executions[$execution->id])) $this->executions = $this->execution->getPairs($execution->project, 'all', 'nocode');
        if(!$this->loadModel('common')->checkPrivByObject('execution', $executionID)) return $this->execution->accessDenied();

        $execution->projectInfo = $this->loadModel('project')->getByID($execution->project);
        $programList = $execution->projectInfo ? array_filter(explode(',', $execution->projectInfo->path)) : array();
        array_pop($programList);

        if(empty($execution->projectInfo->hasProduct)) $this->lang->execution->PO = $this->lang->common->story . $this->lang->execution->owner;

        $this->executionZen->assignViewVars($executionID);

        $this->view->title        = $this->lang->execution->view;
        $this->view->execution    = $execution;
        $this->view->canBeChanged = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->view->type         = $type;
        $this->view->features     = $this->execution->getExecutionFeatures($execution);
        $this->view->project      = $execution->projectInfo;
        $this->view->programList  = $this->loadModel('program')->getPairsByList($programList);

        $this->display();
    }

    /**
     * 研发看板。
     * Research and development Kanban.
     *
     * @param  int     $executionID
     * @param  string  $browseType  all|story|bug|task
     * @param  string  $orderBy
     * @param  string  $groupBy     default|pri|category|module|source|assignedTo|type|story|severity
     * @access public
     * @return void
     */
    public function kanban(int $executionID, string $browseType = 'all', string $orderBy = 'id_asc', string $groupBy = 'default')
    {
        $this->app->loadLang('bug');
        $this->app->loadLang('epic');
        $this->app->loadLang('requirement');
        $this->app->loadLang('kanban');

        if($this->config->vision != 'lite') $this->lang->execution->menu = new stdclass();
        $execution = $this->execution->fetchByID($executionID);
        if(!$execution || $execution->deleted) return $this->sendError($this->lang->notFound, $this->createLink('execution', 'all'));
        $execution = $this->commonAction($executionID);
        if($execution->type != 'kanban') return $this->locate(inlink('view', "executionID={$execution->id}"));

        /* Save the recently five executions visited in the cookie. */
        $this->executionZen->setRecentExecutions($execution->id);

        /* Set Session. */
        if(empty($groupBy)) $groupBy = 'default';
        $this->session->set('execGroupBy', $groupBy);
        $this->session->set('storyList', $this->app->getURI(true), 'execution');
        $this->session->set('rdSearchValue', '');

        /* Get kanban data and set actions. */
        $executionActions = array();
        foreach($this->config->execution->statusActions as $action)
        {
            if($this->execution->isClickable($execution, $action)) $executionActions[] = $action;
        }

        /* Set lane type. */
        $features = $this->execution->getExecutionFeatures($execution);
        if(!$features['story'] and !$features['qa']) $browseType = 'task';
        if(!$features['story']) unset($this->lang->kanban->group->task['story']);
        $this->session->set('execLaneType', $browseType);

        $taskToOpen = $this->cookie->taskToOpen ? $this->cookie->taskToOpen : 0;
        helper::setcookie('taskToOpen', 0, 0);

        $this->executionZen->assignKanbanVars($executionID);

        $this->view->title            = $this->lang->kanban->view;
        $this->view->execution        = $execution;
        $this->view->executionList    = $this->loadModel('project')->getExecutionList(array($execution->project));
        $this->view->executionID      = $executionID;
        $this->view->kanbanList       = $this->loadModel('kanban')->getRDKanban($executionID, $browseType, $orderBy, 0, $groupBy);
        $this->view->browseType       = $browseType;
        $this->view->orderBy          = $orderBy;
        $this->view->groupBy          = $groupBy;
        $this->view->projectID        = $execution->project;
        $this->view->project          = $this->loadModel('project')->getByID($execution->project);
        $this->view->features         = $features;
        $this->view->executionActions = $executionActions;
        $this->view->kanban           = $this->lang->execution->kanban;
        $this->view->taskToOpen       = $taskToOpen;
        $this->display();
    }

    /**
     * 任务看板
     * Task kanban.
     *
     * @param  int    $executionID
     * @param  string $browseType  story|bug|task|all
     * @param  string $orderBy
     * @param  string $groupBy     default|pri|category|module|source|assignedTo|type|story|severity
     * @access public
     * @return void
     */
    public function taskKanban(int $executionID, string $browseType = 'all', string $orderBy = 'order_asc', string $groupBy = '')
    {
        if(!$this->loadModel('common')->checkPrivByObject('execution', $executionID)) return $this->execution->accessDenied();
        /* Load model and language. */
        $this->app->loadLang('epic');
        $this->app->loadLang('requirement');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->loadModel('kanban');

        if(strpos($this->server->http_user_agent, 'MSIE 8.0') !== false) helper::header('X-UA-Compatible', 'IE=EmulateIE7'); // Compatibility IE8.

        $this->execution->setMenu($executionID);
        $execution = $this->execution->getByID($executionID);
        $features  = $this->execution->getExecutionFeatures($execution);

        if($execution->lifetime == 'ops')
        {
            $browseType = $browseType == 'all' ? 'task' : $browseType;
            unset($this->lang->kanban->type['all']);
            unset($this->lang->kanban->type['story']);
            unset($this->lang->kanban->type['bug']);
            unset($this->lang->kanban->type['parentStory']);
        }
        elseif(!$features['story'])
        {
            $browseType = 'task';
            unset($this->lang->kanban->group->task['story'], $this->lang->kanban->type['story'], $this->lang->kanban->type['parentStory']);
        }

        if(!$features['qa']) unset($this->lang->kanban->type['bug']);

        /* Save to session. */
        $uri     = $this->app->getURI(true);
        $groupBy = empty($groupBy) ? 'default' : $groupBy;
        $this->session->set('taskList', $uri, 'execution');
        $this->session->set('storyList', $uri, 'storyList');
        $this->session->set('bugList',  $uri, 'qa');
        $this->session->set('taskSearchValue', '');
        $this->session->set('execGroupBy', $groupBy);
        $this->session->set('execLaneType', $browseType);

        /* Get kanban data. */
        $orderBy = $groupBy == 'story' && $browseType == 'task' && !isset($this->lang->kanban->orderList[$orderBy]) ? 'id_asc' : $orderBy;
        $this->kanban->createLaneIfNotExist($execution);
        list($kanbanGroup, $links) = $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, '', $orderBy);

        /* Show lanes of the attribute: no story and bug in request, no bug in design. */
        if(!isset($this->lang->execution->menu->story)) unset($kanbanGroup['story']);
        if(!isset($this->lang->execution->menu->qa))    unset($kanbanGroup['bug']);

        $this->executionZen->assignTaskKanbanVars($execution);

        if($this->app->tab == 'project') $this->view->projectID = $execution->project;
        $this->view->storyOrder   = $orderBy;
        $this->view->orderBy      = 'id_asc';
        $this->view->executionID  = $executionID;
        $this->view->browseType   = $browseType;
        $this->view->features     = $features;
        $this->view->kanbanGroup  = $kanbanGroup;
        $this->view->links        = $links;
        $this->view->groupBy      = $groupBy;

        $this->display();
    }

    /**
     * 执行看板。
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

        list($projectCount, $statusCount, $myExecutions, $kanbanGroup) = $this->executionZen->buildExecutionKanbanData(array_keys($projects), $executions);
        $kanbanGroup = empty($myExecutions) ? $kanbanGroup : array($myExecutions) + $kanbanGroup;

        $kanbanList  = array();
        $lanes       = array();
        $items       = array();
        $columnCards = array();
        $columns     = array();

        foreach($kanbanGroup as $laneKey => $laneData)
        {
            $laneTitle = zget($projects, $laneKey, $this->lang->execution->myExecutions);

            $laneKey ++;
            $lanes[] = array('name' => $laneKey, 'title' => $laneTitle);
            foreach(array('wait', 'doing', 'suspended', 'closed') as $columnKey)
            {
                $cardList = !empty($laneData[$columnKey]) ? $laneData[$columnKey] : array();
                foreach($cardList as $card)
                {
                    $items[$laneKey][$columnKey][] = array('id' => $card->id, 'name' => $card->id, 'title' => $card->name, 'status' => $card->status, 'delay' => !empty($card->delay) ? $card->delay : 0, 'progress' => $card->progress);

                    if(!isset($columnCards[$columnKey])) $columnCards[$columnKey] = 0;
                    $columnCards[$columnKey] ++;
                }
            }
        }

        foreach(array('wait', 'doing', 'suspended', 'closed') as $columnKey)
        {
            $columns[] = array('name' => $columnKey, 'title' => $this->lang->execution->kanbanColType[$columnKey], 'cards' => !empty($columnCards[$columnKey]) ? $columnCards[$columnKey] : 0);
        }

        if(!empty($kanbanGroup))
        {
            $groupData['key']           = 'executionKanban';
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $columns;
            $groupData['data']['items'] = $items;
            $kanbanList[] = array('items' => array($groupData), 'key' => 'executionKanban', 'heading' => array('title' => $this->lang->execution->executionKanban));
        }

        $this->view->title      = $this->lang->execution->executionKanban;
        $this->view->kanbanList = $kanbanList;
        $this->display();
    }

    /**
     * 设置看板配置。
     * Set Kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setKanban(int $executionID)
    {
        $execution = $this->execution->getByID($executionID);

        if($_POST)
        {
            $executionData = form::data($this->config->execution->form->setkanban)
                ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
                ->setDefault('minColWidth', $execution->minColWidth)
                ->remove('heightType')
                ->get();

            if(!isset($_POST['heightType']) || $this->post->heightType != 'custom' || $this->loadModel('kanban')->checkDisplayCards($executionData->displayCards))
            {
                $this->execution->setKanban($executionID, $executionData);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
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
        if($this->app->tab == 'project') $this->view->projectID = $execution->project;

        $gradeGroup = $this->loadModel('story')->getGradeGroup();

        if(!isset($_SESSION['limitedExecutions'])) $this->execution->getLimitedExecution();

        $this->view->title       = $this->lang->execution->tree;
        $this->view->execution   = $execution;
        $this->view->executionID = $executionID;
        $this->view->level       = $type;
        $this->view->tree        = $this->execution->buildTree($tree, (bool)$project->hasProduct, $gradeGroup);
        $this->view->features    = $this->execution->getExecutionFeatures($execution);
        $this->view->isLimited   = !$this->app->user->admin && strpos(",{$this->session->limitedExecutions},", ",{$executionID},") !== false;
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
        $this->execution->setMenu($executionID);

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
            if($this->post->content == 'increment') $dateList = $this->executionZen->processPrintKanbanData($executionID, $dataList);

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
        $this->app->loadLang('kanban');
        $this->session->set('executionStoryBrowseType', 'unclosed');

        $execution   = $this->commonAction($executionID);
        $executionID = $execution->id;
        $stories     = $this->loadModel('story')->getExecutionStories($executionID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        $stories = array_filter($stories, function($story) {return in_array($story->stage, $this->config->execution->storyKanbanCols);});
        foreach($stories as $story) unset($story->type);

        /* Get execution's product. */
        $productID = 0;
        $productPairs = $this->loadModel('product')->getProductPairsByProject($executionID);
        if($productPairs)
        {
            $productID = key($productPairs);
        }
        else
        {
            return $this->sendError($this->lang->execution->errorNoLinkedProducts, $this->createLink('execution', 'manageproducts', "executionID=$executionID"));
        }

        $this->app->session->set('executionStoryList', $this->app->getURI(true), 'execution');

        $this->view->title        = $this->lang->execution->storyKanban;
        $this->view->kanbanData   = $this->story->getKanbanGroupData($stories);
        $this->view->realnames    = $this->loadModel('user')->getPairs('noletter');
        $this->view->executionID  = $executionID;
        $this->view->execution    = $execution;
        $this->view->productID    = $productID;
        $this->view->total        = count($stories);
        $this->view->product      = $this->product->getByID($productID);
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
            $unresolvedBugs      = $this->loadModel('bug')->getActiveBugs(0, '', (string)$executionID, array());
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

            $tips = $tips . sprintf($this->lang->execution->confirmDelete, $execution->name);
            return $this->send(array('callback' => "confirmDeleteExecution({$executionID}, \"{$tips}\")"));
        }
        else
        {
            /* Delete an execution and update the information. */
            $execution = $this->execution->fetchByID($executionID);
            $this->execution->delete(TABLE_EXECUTION, $executionID);
            $this->execution->updateUserView($executionID);

            $project = $this->loadModel('project')->getByID($execution->project);
            if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->loadModel('programplan')->computeProgress($executionID);

            $this->session->set('execution', '');
            $message = $this->executeHooks($executionID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            $locate = true;
            if($execution->type == 'kanban')
            {
                $locate = $this->config->vision == 'lite' ? $this->createLink('project', 'execution', "status=all&projectID=$execution->project") : $this->createLink('execution', 'all');
            }
            if($this->app->tab == 'project' && $this->session->executionList) $locate = $this->session->executionList;
            return $this->send(array('result' => 'success', 'load' => $locate));
        }
    }

    /**
     * 维护关联产品。
     * Manage products.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function manageProducts(int $executionID)
    {
        /* Use first execution if executionID does not exist. */
        if(!isset($this->executions[$executionID])) $executionID = key($this->executions);

        $execution = $this->execution->getByID($executionID);
        $project   = $this->project->getByID($execution->project);
        if(!$project->hasProduct) return $this->sendError($this->lang->project->cannotManageProducts, true);
        if($project->model == 'waterfall' || $project->model == 'waterfallplus') return $this->sendError(sprintf($this->lang->execution->cannotManageProducts, zget($this->lang->project->modelList, $project->model)), true);

        /* Set menu. */
        $this->execution->setMenu($execution->id);
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $oldProducts = $this->loadModel('product')->getProducts($executionID);
            $oldProducts = array_keys($oldProducts);

            $postData = form::data()->get();
            $this->execution->updateProducts($executionID, $postData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $newProducts  = $this->product->getProducts($executionID);
            $newProducts  = array_keys($newProducts);
            $diffProducts = array_merge(array_diff($oldProducts, $newProducts), array_diff($newProducts, $oldProducts));
            if($diffProducts) $this->loadModel('action')->create($this->objectType, $executionID, 'Managed', '', !empty($_POST['products']) ? implode(',', $_POST['products']) : '');

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->executionZen->assignManageProductsVars($execution);
    }

    /**
     * 维护执行的团队成员。
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
        /* Set menu. */
        $this->execution->setMenu($executionID);
        $execution = $this->execution->getByID($executionID);
        if(!empty($_POST))
        {
            $memberDataList = $this->executionZen->buildMembersForManageMembers($execution);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->manageMembers($execution, $memberDataList);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->execution->getLimitedExecution();

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "renderTaskAssignedTo($executionID);"));
            $this->execution->getLimitedExecution();

            return $this->sendSuccess(array('load' => $this->createLink('execution', 'team', "executionID={$executionID}")));
        }

        $this->loadModel('dept');
        $deptUsers = empty($dept) ? array() : $this->dept->getDeptUserPairs($dept);

        $currentMembers = $this->execution->getTeamMembers($executionID);
        $members2Import = $this->execution->getMembers2Import($team2Import, array_keys($currentMembers));

        /* Append users for get users. */
        $appendUsers = array();
        foreach($currentMembers as $account => $member) $appendUsers[$account] = $account;
        foreach($members2Import as $member) $appendUsers[$member->account] = $member->account;
        foreach($deptUsers as $deptAccount => $userName) $appendUsers[$deptAccount] = $deptAccount;

        if(!empty($this->config->user->moreLink)) $this->config->moreLinks["accounts[]"] = $this->config->user->moreLink;

        if($execution->type == 'kanban') $this->lang->execution->copyTeamTitle = str_replace($this->lang->execution->common, $this->lang->execution->kanban, $this->lang->execution->copyTeamTitle);

        $users     = $this->loadModel('user')->getPairs('noclosed|nodeleted|devfirst', $appendUsers);
        $userItems = array();
        foreach($users as $account => $realName) $userItems[$account] = array('value' => $account, 'text' => $realName, 'keys' => $account, 'disabled' => false);

        $this->view->title          = $this->lang->execution->manageMembers . $this->lang->hyphen . $execution->name;
        $this->view->execution      = $execution;
        $this->view->users          = $users;
        $this->view->userItems      = $userItems;
        $this->view->roles          = $this->user->getUserRoles(array_keys($this->view->users));
        $this->view->dept           = $dept;
        $this->view->depts          = $this->dept->getOptionMenu();
        $this->view->teams2Import   = $this->loadModel('personnel')->getCopiedObjects($executionID, 'sprint', true);
        $this->view->team2Import    = $team2Import;
        $this->view->teamMembers    = $this->executionZen->buildMembers($currentMembers, $members2Import, $deptUsers, $execution->days);
        $this->display();
    }

    /**
     * 移除团队成员。
     * Unlink a memeber.
     *
     * @param  int    $executionID
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unlinkMember(int $executionID, int $userID)
    {
        $user = $this->loadModel('user')->getById($userID, 'id');
        $this->execution->unlinkMember($executionID, $user->account);

        if(dao::isError()) return $this->sendError(dao::getError());

        $this->execution->getLimitedExecution();

        $changes  = array(array('field' => 'removeDiff', 'old' => '', 'new' => '', 'diff' => $user->realname));
        $actionID = $this->loadModel('action')->create('execution', $executionID, 'managedTeam');
        $this->action->logHistory($actionID, $changes);
        return $this->sendSuccess(array('message' => '', 'load' => helper::createLink('execution', 'team', "executionID={$executionID}")));
    }

    /**
     * 在迭代中关联需求。
     * Link stories in the execution.
     *
     * @param  int    $objectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $extra
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function linkStory(int $objectID = 0, string $browseType = '', int $param = 0, string $orderBy = 'id_desc', int $recPerPage = 50, int $pageID = 1, string $extra = '', string $storyType = 'story')
    {
        $this->loadModel('story');
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('branch');

        /* Save old objectID to use later. */
        $originObjectID = $objectID;

        /* Use the projectID of Kanban as objectID if vision is lite. */
        if($this->config->vision == 'lite')
        {
            $kanban = $this->project->getByID($objectID, 'kanban');
            if($kanban) $objectID = $kanban->project;
        }

        $products = $this->product->getProducts($objectID);
        if(empty($products)) return $this->sendError($this->lang->execution->errorNoLinkedProducts, $this->createLink('execution', 'manageproducts', "executionID=$objectID"));

        /* 通过objectID获取符合项目、执行、阶段和看板类型的对象。*/
        $object = $this->project->getByID($objectID, 'project,sprint,stage,kanban');

        $browseLink = $this->createLink('execution', 'story', "executionID=$objectID");
        if($this->app->tab == 'project' and $object->multiple) $browseLink = $this->createLink('projectstory', 'story', "objectID=$objectID");

        if($object->type == 'kanban' && !$object->hasProduct) $this->lang->productCommon = $this->lang->project->common;

        $this->session->set('storyList', $this->app->getURI(true), $this->app->tab);

        if($object->type == 'project') $this->project->setMenu($object->id);
        if(in_array($object->type, array('sprint', 'stage', 'kanban'))) $this->execution->setMenu($object->id);

        if(!empty($_POST))
        {
            if($object->type != 'project' and $object->project != 0) $this->execution->linkStory($object->project, $this->post->stories ? $this->post->stories : array(), '', array(), $storyType);
            $this->execution->linkStory($objectID, $this->post->stories ? $this->post->stories : array(), $extra, array(), $storyType);

            if(isInModal()) return $this->sendSuccess(array('closeModal' => true, 'load' => true, 'callback' => "refreshKanban()"));

            return $this->sendSuccess(array('load' => $browseLink));
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
        $actionURL    = $this->createLink($this->app->rawModule, 'linkStory', "objectID=$objectID&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&recPerPage=$recPerPage&pageID=$pageID&extra=$extra&storyType=$storyType");
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $queryID      = ($browseType == 'bySearch') ? (int)$param : 0;
        $this->execution->buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, 'linkStory', $object);

        $project   = (strpos('sprint,stage,kanban', $object->type) !== false) ? $this->loadModel('project')->getByID($object->project) : $object;
        $storyType = (($object->type == 'stage' && in_array($object->attribute, array('mix', 'request', 'design'))) || $object->type == 'project' || !$object->multiple) ? ($project->storyType ?? 'story') : 'story';

        if($browseType == 'bySearch') $allStories = $this->story->getBySearch('all', '', $queryID, $orderBy, $objectID, $storyType);
        if($browseType != 'bySearch') $allStories = $this->story->getProductStories(implode(',', array_keys($products)), $branchIDList, '0', 'active,launched', $storyType, $orderBy, true, '', null);
        $linkedStories = $this->story->getExecutionStoryPairs($objectID, 0, 'all', 0, 'full', 'all', $storyType);
        foreach($allStories as $id => $story)
        {
            if(isset($linkedStories[$story->id])) unset($allStories[$id]);

            if(!isset($modules[$story->module]))
            {
                $storyModule = $this->tree->getModulesName((array)$story->module);
                $productName = count($products) > 1 ? $products[$story->product]->name : '';
                $modules[$story->module] = $productName . zget($storyModule, $story->module, '');
            }

            $story->estimate = $story->estimate . $this->config->hourUnit;
        }

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        /* Set the pager. */
        $this->app->loadClass('pager', true);
        $pager      = new pager(count($allStories), $recPerPage, $pageID);
        $allStories = array_chunk($allStories, $pager->recPerPage);

        $productPairs = array();
        foreach($products as $id => $product) $productPairs[$id] = $product->name;

        $this->view->title        = $object->name . $this->lang->hyphen . $this->lang->execution->linkStory;
        $this->view->objectID     = $originObjectID;
        $this->view->param        = $param;
        $this->view->extra        = $extra;
        $this->view->object       = $object;
        $this->view->orderBy      = $orderBy;
        $this->view->gradeGroup   = $gradeGroup;
        $this->view->showGrade    = $this->config->edition == 'ipd';
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
        $this->view->executionID  = $object->id;
        $this->view->projectID    = $object->id;
        $this->view->storyType    = $storyType;
        if($this->config->edition == 'ipd') $this->view->roadmaps = $this->loadModel('roadmap')->getPairs(array_keys($productPairs));

        $this->display();
    }

    /**
     * 取消关联需求（移除需求）。
     * Unlink a story.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  string $confirm     yes|no
     * @param  string $from        taskkanban
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function unlinkStory(int $executionID, int $storyID, string $confirm = 'no', string $from = '', int $laneID = 0, int $columnID = 0)
    {
        if($confirm == 'no')
        {
            $confirmURL = $this->createLink('execution', 'unlinkstory', "executionID=$executionID&storyID=$storyID&confirm=yes&from=$from&laneID=$laneID&columnID=$columnID");
            $tip        = $this->app->rawModule == 'projectstory' ? $this->lang->execution->confirmUnlinkExecutionStory : $this->lang->execution->confirmUnlinkStory;
            $story      = $this->loadModel('story')->getByID($storyID);
            if($story->type == 'requirement') $tip = str_replace($this->lang->SRCommon, $this->lang->URCommon, $tip);
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$tip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }

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

        if($this->app->tab == 'execution' and $execution->type == 'kanban' or $from == 'taskkanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 批量取消关联需求（移除需求）。
     * Batch unlink story.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchUnlinkStory(int $executionID)
    {
        if(isset($_POST['storyIdList']))
        {
            $storyIdList = $this->post->storyIdList;
            $_POST       = array();

            $this->loadModel('gitlab');
            foreach($storyIdList as $storyID)
            {
                /* Delete related issue in gitlab. */
                $relation = $this->gitlab->getRelationByObject('story', (int)$storyID);
                if(!empty($relation)) $this->gitlab->deleteIssue('story', (int)$storyID, $relation->issueID);

                $this->execution->unlinkStory($executionID, (int)$storyID);
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        $execution = $this->execution->getByID($executionID);
        return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->execution->confirmBatchUnlinkStory, 'locate' => $this->createLink('execution', 'story', "executionID=$executionID") . ($execution->multiple ? '' : '#app=project'))));
    }

    /**
     * 获取执行动态。
     * Get the dynamic of execution.
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
    public function dynamic(int $executionID = 0, string $type = 'today', string $param = '', int $recTotal = 0, string $date = '', string $direction = 'next')
    {
        if(empty($type)) $type = 'today';

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
        $executionID = $this->execution->checkAccess($executionID, $this->executions);
        $this->execution->setMenu($executionID);
        $execution = $this->execution->getByID($executionID);

        /* Set the user and type. */
        $account = 'all';
        if($type == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id'); /* 通过 id 字段获取用户。另一种是通过 account 获取。*/
            if($user) $account = $user->account;
        }

        /* 获取这个时间段的操作日志列表。*/
        $period     = $type == 'account' ? 'all' : $type;
        $orderBy    = $direction == 'next' ? 'date_desc' : 'date_asc';
        $date       = empty($date) ? '' : date('Y-m-d', (int)$date);
        $actions    = $this->loadModel('action')->getDynamicByExecution($executionID, $account, $period, $orderBy, 50, $date, $direction);
        $dateGroups = $this->action->buildDateGroup($actions, $direction);
        if(empty($recTotal) && $dateGroups) $recTotal = $this->action->getDynamicCount($period);

        $this->view->title        = $execution->name . $this->lang->hyphen . $this->lang->execution->dynamic;
        $this->view->userIdPairs  = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted|useid');
        $this->view->accountPairs = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->executionID  = $executionID;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->account      = $account;
        $this->view->param        = $param;
        $this->view->dateGroups   = $dateGroups; /* 将日志按照日期分组，以日期为索引，将日志列表作为值。*/
        $this->view->direction    = $direction;
        $this->view->recTotal     = $recTotal;

        $this->display();
    }

    /**
     * 通过执行ID获取产品。
     * AJAX: get products of a execution in html select.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetProducts(int $executionID)
    {
        $products = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        return print(html::select('product', $products, '', 'class="form-control"'));
    }

    /**
     * 通过执行ID/项目ID和指派给获取团队成员。
     * AJAX: get team members of the execution.
     *
     * @param  int    $objectID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function ajaxGetMembers(int $objectID, string $assignedTo = '')
    {
        $object = $this->execution->fetchByID($objectID);
        $users  = $this->loadModel('user')->getTeamMemberPairs($objectID, $object->type == 'project' ? 'project' : 'execution');
        if($this->app->getViewType() === 'json') return print(json_encode($users));

        $items = array();
        foreach($users as $account => $realName) $items[] = array('value' => $account, 'text' => $realName, 'keys' => $account);
        return print(json_encode($items));
    }

    /**
     * 通过项目ID或执行ID获取团队成员。
     * AJAX: get team members by projectID/executionID.
     *
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function ajaxGetTeamMembers(int $objectID)
    {
        $objectType = $this->dao->select('`type`')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('type');
        if($objectType != 'project') $objectType = 'execution';

        $members = $this->loadModel('user')->getTeamMemberPairs($objectID, $objectType);

        $selectedMembers = array();
        foreach($members as $account => $realName) $selectedMembers[] = $account;
        return print(json_encode($selectedMembers));
    }

    /**
     * 在用户创建完执行后，给用户发送一个后续操作的提示（比如设置团队、关联软件需求等）方便引导用户使用。
     * Show tips to guide the user to do something after the execution created successfully.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function tips(int $executionID)
    {
        $execution = $this->execution->getById($executionID);

        $this->view->executionID = $executionID;
        $this->view->execution   = $execution;
        $this->view->projectID   = $execution->project;
        $this->display();
    }

    /**
     * 获取执行下拉列表用于切换不同的执行。
     * Drop menu page.
     *
     * @param  int    $executionID 已经打开的页面对应的执行ID
     * @param  string $module      链接里要访问的模块
     * @param  string $method      链接里要访问的方法
     * @param  string $extra       给链接传入的额外的参数
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $executionID, string $module, string $method, string $extra = '')
    {
        $execution = $this->execution->fetchByID($executionID);
        if(!empty($execution->isTpl)) dao::$filterTpl = 'never';

        $this->view->link        = $this->executionZen->getLink($module, $method, $extra);
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->view->executionID = $executionID;
        $this->view->extra       = $extra;

        $projects = $this->loadModel('program')->getProjectList(0, 'all', 0, 'order_asc', '', true); /* 获取所有项目的列表。*/
        $executionGroups = $this->dao->select('*')->from(TABLE_EXECUTION) /* 按照项目分组，获取有权限访问的执行列表。*/
            ->where('deleted')->eq('0')
            ->andWhere('multiple')->eq('1')
            ->andWhere('type')->in(!empty($execution->isTpl) ? 'sprint,stage' : 'sprint,stage,kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(!empty($execution->isTpl))->andWhere('isTpl')->eq('1')->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->orderBy('order_asc')
            ->fetchGroup('project', 'id');

        $teams = $this->dao->select('root,account')->from(TABLE_TEAM) /* 按照执行ID分组，获取有权限访问的执行的团队信息。*/
            ->where('root')->in($this->app->user->view->sprints)
            ->andWhere('type')->eq('execution')
            ->fetchGroup('root', 'account');

        $projectPairs      = array(); /* 项目ID为索引，项目名称为值的数组 [projectID => projectName]。 */
        $orderedExecutions = array();
        $parentExecutions  = array();
        foreach($projects as $project)
        {
            $executions = zget($executionGroups, $project->id, array());
            if(isset($project->model) and $project->model == 'waterfall') ksort($executions);

            $topExecutions = array();
            foreach($executions as $execution)
            {
                if($execution->grade == 1) $topExecutions[$execution->id] = $execution->id;
                if($execution->grade > 1 && $execution->parent) $parentExecutions[$execution->parent][$execution->id] = $execution;
            }

            /* 获取排序后的执行列表，并给每个执行设置团队信息。*/
            $executions = $this->execution->resetExecutionSorts($executions, $topExecutions, $parentExecutions);
            foreach($executions as $execution)
            {
                $execution->teams = zget($teams, $execution->id, array());
                $orderedExecutions[$execution->id] = $execution;
            }

            $projectPairs[$project->id] = $project->name;
        }

        $executionNameList = $this->execution->getFullNameList($orderedExecutions);
        $projectExecutions = array(); /* 项目对应的执行列表 [projectID => execution]。*/
        foreach($orderedExecutions as $execution)
        {
            if(isset($parentExecutions[$execution->id])) continue;

            $execution->name = $executionNameList[$execution->id];
            $projectExecutions[$execution->project][] = $execution;
        }

        $this->view->projects           = $projectPairs;      /* 项目ID为索引，项目名称为值的数组 [projectID => projectName]。 */
        $this->view->projectExecutions  = $projectExecutions; /* 项目对应的执行列表 [projectID => execution]。*/
        $this->view->executionID        = $executionID;
        $this->view->execution          = $execution;
        $this->display();
    }

    /**
     * 将执行列表内的执行进行排序。
     * Update the order of the executions.
     *
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->sendError($this->lang->error->unsupportedReq);

        $idList  = explode(',', trim($this->post->executions, ','));
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'order') === false) return false;

        $executions = $this->dao->select('id,`order`')->from(TABLE_EXECUTION)->where('id')->in($idList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($executions as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
            if(dao::isError()) return $this->sendError(dao::getError());
        }

        return $this->sendSuccess();
    }

    /**
     * 需求排序。
     * Sort stories.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function storySort(int $executionID)
    {
        $storyIdList = json_decode($this->post->storyIdList, true);
        $storyIdList = array_flip($storyIdList);
        $orderBy     = $this->post->orderBy;

        krsort($storyIdList);
        $order = $this->dao->select('`order`')->from(TABLE_PROJECTSTORY)->where('story')->in($storyIdList)->andWhere('project')->eq($executionID)->orderBy('order_asc')->fetch('order');
        foreach($storyIdList as $storyID)
        {
            $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('project')->eq($executionID)->exec();
            $order++;
        }

        return $this->sendSuccess();
    }

    /**
     * 在执行需求列表内进行需求估算。
     * Story estimate.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $round       The round of estimate for stroy 估算的轮次
     * @access public
     * @return void
     */
    public function storyEstimate(int $executionID, int $storyID, int $round = 0)
    {
        $this->loadModel('story');

        if($_POST)
        {
            $this->story->saveEstimateInfo($storyID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('story', $storyID, 'estimated', '', $executionID);
            $link = $this->createLink('execution', 'storyEstimate', "executionID={$executionID}&storyID={$storyID}&round={$round}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => "loadModal(\"$link\", '#storyEstimateModal');"));
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
    public function all(string $status = 'undone', string $orderBy = 'order_asc', int $productID = 0, string $param = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadLang('my');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');
        $this->loadModel('product');
        $this->loadModel('project');
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

        $sort = $orderBy;
        if(strpos($sort, 'rawID_') !== false) $sort = str_replace('rawID_', 'id_', $sort);
        if(strpos($sort, 'nameCol_') !== false) $sort = str_replace('nameCol_', 'name_', $sort);

        $executionStats = $this->execution->getStatData(0, $status, $productID, 0, false, $queryID, $sort, $pager);

        $this->view->title            = $this->lang->execution->allExecutions;
        $this->view->executionStats   = $executionStats;
        $this->view->allExecutionsNum = $this->execution->getExecutionCounts(0, 'all');
        $this->view->productList      = $this->loadModel('product')->getProductPairsByProject(0);
        $this->view->productID        = $productID;
        $this->view->pager            = $pager;
        $this->view->orderBy          = $orderBy;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects         = array('') + $this->project->getPairsByProgram();
        $this->view->status           = $status;
        $this->view->from             = $from;
        $this->view->param            = $param;
        $this->view->showBatchEdit    = $this->cookie->showExecutionBatchEdit;
        $this->view->avatarList       = $this->user->getAvatarPairs('');

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
        /* Set the menu. If the executionID = 0, use the indexMenu instead. */
        $execution = $this->commonAction($executionID);

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
        $lowerStatus = strtolower($status);
        if($_POST)
        {
            $executionLang          = $this->lang->execution;
            $executionConfig        = $this->config->execution;
            $projectExecutionDtable = $this->config->project->execution->dtable->fieldList;

            $projectID = $from == 'project' ? $this->session->project : 0;
            if($projectID) $this->project->setMenu($projectID);
            $project = $this->project->getByID($projectID);

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $executionConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                if($fieldName == 'type' and $this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $fields['method'] = $executionLang->method;

                $fieldName = trim($fieldName);
                $fields[$fieldName] = $from == 'project' && !empty($projectExecutionDtable[$fieldName]) ? $projectExecutionDtable[$fieldName]['title']  : zget($executionLang, $fieldName);
                if($fieldName == 'type') $fields['type'] = $this->lang->typeAB;
                unset($fields[$key]);
            }
            if(isset($fields['id'])) $fields['id'] = $this->lang->idAB;
            unset($fields[$from == 'project' ? 'projectName' : 'productName']);

            $users    = $this->loadModel('user')->getPairs('noletter');
            $showTask = ($this->app->tab == 'project' && (bool)$this->cookie->showTask);
            if($showTask && $lowerStatus == 'bysearch')
            {
                $executionIdList = $this->loadModel('programplan')->getStageList($projectID, $productID, 'all', 'order');
                $tasks = $this->programplan->getGanttTasks($projectID, array_keys($executionIdList), $lowerStatus, 0, null);
                $executionTasks  = array();
                $executionIdList = array();
                foreach($tasks as $task)
                {
                    $executionIdList[$task->execution] = $task->execution;
                    if(!isset($executionTasks[$task->execution])) $executionTasks[$task->execution] = array();
                    $executionTasks[$task->execution][$task->id] = $task;
                }
                $executions = $this->execution->getByIdList($executionIdList);
                $executions = $this->execution->batchProcessExecution($executions, $projectID, $productID, true, '', $executionTasks);
                $executionStats = array_values($executions);
            }
            else
            {
                $executionStats = $this->execution->getStatData($projectID, $lowerStatus == 'byproduct' ? 'all' : $status, $productID, 0, (bool)$showTask, 'withchild', $orderBy);
            }
            $executionStats = $this->flattenObjectArray($executionStats);

            $rows           = array();
            $checkedItem    = $this->cookie->checkedItem;
            foreach($executionStats as $i => $execution)
            {
                if($this->post->exportType == 'selected' && strpos(",$checkedItem,", ",pid{$execution->id},") === false) continue;

                $execution->PM            = zget($users, $execution->PM);
                $execution->status        = !empty($execution->delay) ? $executionLang->delayed : $this->processStatus('execution', $execution);
                $execution->progress     .= '%';
                $execution->name          = isset($execution->title) ? $execution->title : $execution->name;
                $execution->type          = zget($this->lang->execution->typeList, $execution->type, $this->lang->executionCommon);
                if(isset($executionStats[$execution->parent])) $execution->name = $executionStats[$execution->parent]->name . '/' . $execution->name;
                if($this->app->tab == 'project' and ($project->model == 'agileplus' or $project->model == 'waterfallplus')) $execution->method = zget($executionLang->typeList, $execution->type);

                $rows[] = $execution;

                if(empty($execution->tasks)) continue;
                foreach($execution->tasks as $task)
                {
                    if($task->isParent)
                    {
                        $task->name = '[' . $this->lang->task->parentAB . '] ' . $task->name;
                    }
                    elseif($task->parent > 0)
                    {
                        $task->name = '[' . $this->lang->task->childrenAB . '] ' . $task->name;
                    }

                    $task->type          = $this->lang->task->common;
                    $task->status        = zget($this->lang->task->statusList, $task->status);
                    $task->totalEstimate = $task->estimate;
                    $task->totalConsumed = $task->consumed;
                    $task->totalLeft     = $task->left;
                    $task->progress      = (($task->consumed + $task->left) == 0 ? 0 : round($task->consumed / ($task->consumed + $task->left), 4) * 100) . '%';
                    $task->begin         = !helper::isZeroDate($task->estStarted) ? $task->estStarted : '';
                    $task->end           = !helper::isZeroDate($task->deadline) ? $task->deadline : '';
                    $task->realBegan     = !helper::isZeroDate($task->realStarted) ? $task->realStarted : '';
                    $task->realEnd       = !helper::isZeroDate($task->finishedDate) ? $task->finishedDate : '';
                    $task->PM            = zget($users, $task->assignedTo);
                    $rows[] = $task;
                }
            }

            if($this->config->edition != 'open') list($fields, $executionStats) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $executionStats);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $rows);
            $this->post->set('kind', $this->lang->execution->common);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $executionConcept = $this->lang->execution->common;
        if($this->app->tab == 'project')
        {
            $project = $this->project->getByID($this->session->project);
            if(!empty($project->model) && $project->model == 'waterfall') $executionConcept = $this->lang->project->stage;
            if(!empty($project->model) && $project->model == 'scrum')     $executionConcept = $this->lang->executionCommon;
            $fileName = $project->name . ' - ' . $executionConcept;
        }

        if($lowerStatus != 'bysearch') $fileName = (in_array($status, array('all', 'undone', 'delayed')) ? zget($this->lang->execution, $status, '') : zget($this->lang->execution->statusList, $status, '')) . $executionConcept;
        $this->view->fileName = !empty($fileName) ? $fileName : $executionConcept;
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
    public function doc(int $executionID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, string $mode = 'list', int $docID = 0, string $search = '')
    {
        $this->commonAction($executionID);
        echo $this->fetch('doc', 'app', "type=execution&spaceID=$executionID&libID=$libID&moduleID=$moduleID&docID=$docID&mode=$mode&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&filterType=$browseType&search=$search&noSpace=true");
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
            return $this->send(array('closeModal' => true, 'load' => true, 'result' => 'fail', 'message' => $haveDraft));
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
        $this->loadModel('requirement');
        $this->loadModel('epic');

        $story   = $this->loadModel('story')->getById($storyID, $version, true);
        $product = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id, `type`, shadow')->fetch();

        $this->view->story       = $story;
        $this->view->product     = $product;
        $this->view->branches    = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($product->id);
        $this->view->plan        = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fields('title')->fetch('title');
        $this->view->bugs        = $this->dao->select('id,title,status')->from(TABLE_BUG)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->cases       = $this->dao->select('id,title')->from(TABLE_CASE)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll();
        $this->view->modulePath  = $this->loadModel('tree')->getParents($story->module);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->executions  = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        $this->view->actions     = $this->loadModel('action')->getList('story', $storyID);
        $this->view->version     = $version == 0 ? $story->version : $version;
        $this->view->executionID = $this->session->execution;
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
     * @param  string $browseType  all|story|task|bug
     * @param  string $groupBy     default|pri|category|module|source|assignedTo|type|story|severity
     * @param  string $from        taskkanban|execution
     * @param  string $searchValue
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function ajaxUpdateKanban(int $executionID = 0, int $enterTime = 0, string $browseType = '', string $groupBy = '', string $from = 'taskkanban', string $searchValue = '', string $orderBy = 'id_asc')
    {
        $this->loadModel('kanban');
        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'pri_asc';

        if($from == 'execution') $this->session->set('taskSearchValue', $searchValue);
        if($from == 'RD')        $this->session->set('rdSearchValue', $searchValue);

        $lastEditedTime = $this->execution->getLaneMaxEditedTime($executionID);
        $enterTime      = date('Y-m-d H:i:s', $enterTime);
        if(in_array(true, array(empty($lastEditedTime), strtotime($lastEditedTime) < 0, $lastEditedTime > $enterTime, $groupBy != 'default', !empty($searchValue))))
        {
            if($from == 'taskkanban')
            {
                list($kanbanGroup, $links) = $this->kanban->getExecutionKanban($executionID, $browseType, $groupBy, $searchValue, $orderBy);
            }
            else
            {
                $kanbanGroup = $this->kanban->getRDKanban($executionID, $browseType, $orderBy, 0, $groupBy, $searchValue);
            }
            return print(json_encode($kanbanGroup));
        }
    }

    /**
     * 创建执行时，根据项目ID获取可以复制的执行。
     * When the create an execution, get execution list that can be copied by the project ID.
     *
     * @param  int    $projectID
     * @param  int    $copyExecutionID
     * @access public
     * @return void
     */
    public function ajaxGetCopyProjectExecutions(int $projectID = 0, int $copyExecutionID = 0)
    {
        $project = $this->loadModel('project')->fetchByID($projectID);
        if($project) $this->executionZen->correctExecutionCommonLang($project, zget($this->config->execution->modelList, $project->model, 'scrum'));

        $this->view->executions      = $this->execution->getList($projectID, 'all', 'all', 0, 0, 0, null, false);
        $this->view->copyExecutionID = $copyExecutionID;
        $this->display();
    }

    /**
     * 获取专业研发看板的数据。
     * Get execution kanban data by ajax.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetExecutionKanban(int $executionID)
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
     * 展示燃尽图。
     * Display burn chart.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetBurn(int $executionID)
    {
        $execution = $this->execution->getByID($executionID, true);
        $type      = 'noweekend';
        if(((strpos('closed,suspended', $execution->status) === false && helper::today() > $execution->end)
            || ($execution->status == 'closed'    && substr($execution->closedDate ?? '', 0, 10) > $execution->end)
            || ($execution->status == 'suspended' && $execution->suspendedDate > $execution->end))
            && strpos($type, 'delay') === false)
        {
            $type .= ',withdelay';
        }

        $deadline = $execution->status == 'closed' ? substr($execution->closedDate ?? '', 0, 10) : $execution->suspendedDate;
        $deadline = strpos('closed,suspended', $execution->status) === false ? helper::today() : $deadline;
        $endDate  = strpos($type, 'withdelay') !== false ? $deadline : $execution->end;
        list($dateList, $interval) = $this->execution->getDateList($execution->begin, $endDate, $type, 0, 'Y-m-d', $execution->end);

        $executionEnd = strpos($type, 'withdelay') !== false ? $execution->end : '';
        $this->view->chartData = $this->execution->buildBurnData($executionID, $dateList, 'left', $executionEnd);
        $this->view->execution = $execution;

        $this->display();
    }

    /**
     * 展示累计流图。
     * Display cumulative flow diagram.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxGetCFD(int $executionID)
    {
        $execution = $this->execution->getById($executionID, true);

        $this->app->loadClass('date');
        list($begin, $end) = $this->execution->getBeginEnd4CFD($execution);
        $dateList  = date::getDateList($begin, $end, 'Y-m-d', 'noweekend');
        $chartData = $this->execution->buildCFDData($executionID, $dateList, 'task');
        if(isset($chartData['line'])) $chartData['line'] = array_reverse($chartData['line']);

        $this->view->begin     = helper::safe64Encode(urlencode($begin));
        $this->view->end       = helper::safe64Encode(urlencode($end));
        $this->view->execution = $execution;
        $this->view->chartData = $chartData;

        $this->display();
    }

    /**
     * Ajax get product drop menu.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxSwitcherMenu(int $executionID, int $productID, string $currentMethod = '')
    {
        $link = helper::createLink('execution', $currentMethod, "executionID=$executionID&productID={id}");
        if($currentMethod == 'zerocase') $link = helper::createLink('testcase', 'zerocase', "productID={id}&branch=0&orderBy=id_desc&objectID=$executionID");

        $this->view->link          = $link;
        $this->view->productID     = $productID;
        $this->view->products      = $this->loadModel('product')->getProducts($executionID);
        $this->view->executionID   = $executionID;
        $this->view->currentMethod = $currentMethod;
        $this->display();
    }

    /**
     * Ajax get execution drop menu.
     * 通过Ajax获取执行下拉菜单。
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetExecutionSwitcherMenu(int $projectID, int $executionID, string $method = '')
    {
        $this->view->link        = helper::createLink('execution', $method, 'executionID={id}');
        $this->view->executionID = $executionID;
        $this->view->executions  = $this->execution->getList($projectID, 'kanban');

        $this->display();
    }

    /**
     * Flatten Object Array.
     *
     * @param  array  $array
     * @access public
     * @return void
     */
    public function flattenObjectArray(array $array = array())
    {
        $this->loadModel('task');

        $result = array();
        foreach($array as $object)
        {
            $result[$object->id] = $object;
            $tasks = zget($object, 'tasks', array());
            if(empty($tasks)) continue;

            $object->tasks = $this->task->mergeChildIntoParent($tasks);
        }

        return $result;
    }

    /**
     * AJAX: 获取有未关闭任务的执行。
     * AJAX: get executions that have unclosed tasks.
     *
     * @param  string $executionIdList
     * @access public
     * @return string
     */
    public function ajaxGetNonClosableExecutions(string $executionIdList)
    {
        $executionIdList = str_replace('pid', '', $executionIdList);
        $tasks           = $this->loadModel('task')->getUnclosedTasksByExecution(explode(',', $executionIdList));
        $tasks           = array_filter($tasks);
        $executions      = !empty($tasks) ? $this->execution->getPairsByList(array_keys($tasks)) : array();

        return print(json_encode(array_values($executions)));
    }
}
