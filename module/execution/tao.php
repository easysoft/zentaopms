<?php
declare(strict_types=1);
/**
 * The tao file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
class executionTao extends executionModel
{
    /**
     * 根据给定条件构建执行键值对。
     * Build execution id:name pairs through the conditions.
     *
     * @param  string    $mode          all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|noprefix|withobject|hideMultiple
     * @param  array     $allExecutions
     * @param  array     $executions
     * @param  array     $parents
     * @param  array     $projectPairs
     * @param  string    $projectModel
     * @access protected
     * @return array
     */
    protected function buildExecutionPairs(string $mode = '', array $allExecutions = array(), array $executions = array(), array $parents = array(), array $projectPairs = array(), string $projectModel = ''): array
    {
        $executionPairs = array();
        $noMultiples    = array();
        foreach($executions as $execution)
        {
            if(strpos($mode, 'leaf') !== false && isset($parents[$execution->id])) continue; // Only show leaf.
            if(strpos($mode, 'noclosed') !== false && ($execution->status == 'done' or $execution->status == 'closed')) continue;
            if(strpos($mode, 'stagefilter') !== false && isset($projectModel) && in_array($projectModel, array('waterfall', 'waterfallplus')) && in_array($execution->attribute, array('request', 'design', 'review'))) continue; // Some stages of waterfall && waterfallplus not need.

            if(empty($execution->multiple)) $noMultiples[$execution->id] = $execution->project;

            /* Set execution name. */
            $paths         = array_slice(explode(',', trim($execution->path, ',')), 1);
            $executionName = '';
            foreach($paths as $path)
            {
                if(isset($allExecutions[$path])) $executionName .= '/' . $allExecutions[$path]->name;
            }

            if(strpos($mode, 'withobject') !== false) $executionName = zget($projectPairs, $execution->project, '') . $executionName;
            if(strpos($mode, 'noprefix') !== false) $executionName = ltrim($executionName, '/');

            $executionPairs[$execution->id] = $executionName;
        }

        if($noMultiples)
        {
            if(strpos($mode, 'hideMultiple') !== false)
            {
                foreach($noMultiples as $executionID => $projectID) $executionPairs[$executionID] = '';
            }
            else
            {
                $this->app->loadLang('project');
                $noMultipleProjects = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->in($noMultiples)->fetchPairs('id', 'name');
                foreach($noMultiples as $executionID => $projectID)
                {
                    if(isset($noMultipleProjects[$projectID])) $executionPairs[$executionID] = $noMultipleProjects[$projectID] . "({$this->lang->project->disableExecution})";
                }
            }
        }

        /* If the executionPairs is empty, to make sure there's an execution in the executionPairs. */
        if(empty($executionPairs) and isset($executions[0]))
        {
            $firstExecution = $executions[0];
            $executionPairs[$firstExecution->id] = $firstExecution->name;
        }

        return $executionPairs;
    }

    /**
     * 获取燃尽图相关数据。
     * Get burn related data.
     *
     * @param  array     $executionIdList
     * @access protected
     * @return array
     */
    protected function fetchBurnData(array $executionIdList): array
    {
        $today = helper::today();
        $burns = $this->dao->select("execution, '$today' AS date, sum(estimate) AS `estimate`, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status')->ne('cancel')
            ->groupBy('execution')
            ->fetchAll('execution');

        $closedLefts = $this->dao->select('execution, sum(`left`) AS `left`')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status')->eq('closed')
            ->groupBy('execution')
            ->fetchAll('execution');

        $finishedEstimates = $this->dao->select("execution, sum(`estimate`) AS `estimate`")->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status', true)->eq('done')
            ->orWhere('status')->eq('closed')
            ->markRight(1)
            ->groupBy('execution')
            ->fetchAll('execution');

        $storyPoints = $this->dao->select('t1.project, sum(t2.estimate) AS `storyPoint`')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->in($executionIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.stage')->in('wait,planned,projected,developing')
            ->groupBy('project')
            ->fetchAll('project');

        return array($burns, $closedLefts, $finishedEstimates, $storyPoints);
    }

    /**
     * 获取燃尽图数据。
     * Get burn data.
     *
     * @param  int        $executionID
     * @param  string     $date
     * @param  string     $taskCount
     * @access protected
     * @return object|bool
     */
    protected function getBurnByExecution(int $executionID, string $date = '', int $taskCount = 0): object|bool
    {
        return $this->dao->select('*')->from(TABLE_BURN)
            ->where('execution')->eq($executionID)
            ->beginIF($date)->andWhere('date')->eq($date)->fi()
            ->beginIF($taskCount)->andWhere('task')->eq($taskCount)->fi()
            ->fetch();
    }

    /**
     * 获取执行团队成员数量。
     * Get execution team member count.
     *
     * @param  array     $executionIdList
     * @access protected
     * @return void
     */
    protected function getMemberCountGroup(array $executionIdList): array
    {
        return $this->dao->select('t1.root,count(t1.id) as teams')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($executionIdList)
            ->andWhere('t1.type')->ne('project')
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.root')
            ->fetchAll('root');
    }

    /**
     * 通过产品ID列表获取执行id:name的键值对。
     * Get the pair for execution id:name from the product id list.
     *
     * @param  array     $productIdList
     * @access protected
     * @return array
     */
    protected function getPairsByProduct(array $productIdList): array
    {
        return $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.type')->in('sprint,stage,kanban')
            ->fetchPairs('project');
    }

    /**
     * 获取执行关联的产品信息。
     * Get product information of the linked execution.
     *
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function getProductList(int $executionID): array
    {
        $executions = $this->dao->select('t1.id,t2.product,t3.name')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t1.type')->in('kanban,sprint,stage')
            ->fetchAll();

        $productList = array();
        foreach($executions as $execution)
        {
            if(!isset($productList[$execution->id]))
            {
                $productList[$execution->id] = new stdclass();
                $productList[$execution->id]->product     = '';
                $productList[$execution->id]->productName = '';
            }
            $productList[$execution->id]->product     .= $execution->product . ',';
            $productList[$execution->id]->productName .= $execution->name . ',';
        }
        return $productList;
    }

    /**
     * 通过搜索获取Bug列表数据。
     * Get bugs by search in execution.
     *
     * @param  array       $productIdList
     * @param  int         $executionID
     * @param  string      $sql
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access protected
     * @return object[]
     */
    protected function getSearchBugs(array $productIdList, int $executionID, string $sql = '1=1', string $orderBy = 'id_desc', object|null $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where($sql)
            ->andWhere('status')->eq('active')
            ->andWhere('toTask')->eq(0)
            ->andWhere('tostory')->eq(0)
            ->beginIF(!empty($productIdList))->andWhere('product')->in($productIdList)->fi()
            ->beginIF(empty($productIdList))->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 将执行ID保存到session中。
     * Save the execution ID to the session.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function saveSession(int $executionID): void
    {
        $this->session->set('execution', $executionID, $this->app->tab);
        $this->setProjectSession($executionID);
    }

    /**
     * 设置看板执行的菜单。
     * Set kanban menu.
     *
     * @access protected
     * @return void
     */
    protected function setKanbanMenu()
    {
        global $lang;
        $lang->executionCommon = $lang->execution->kanban;
        include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';

        $this->lang->execution->menu           = new stdclass();
        $this->lang->execution->menu->kanban   = array('link' => "{$this->lang->kanban->common}|execution|kanban|executionID=%s", 'subModule' => 'task');
        $this->lang->execution->menu->CFD      = array('link' => "{$this->lang->execution->CFD}|execution|cfd|executionID=%s");
        $this->lang->execution->menu->build    = array('link' => "{$this->lang->build->common}|execution|build|executionID=%s");
        $this->lang->execution->menu->settings = array('link' => "{$this->lang->settings}|execution|view|executionID=%s", 'subModule' => 'personnel', 'alias' => 'edit,manageproducts,team,whitelist,addwhitelist,managemembers', 'class' => 'dropdown dropdown-hover');
        $this->lang->execution->dividerMenu    = '';

        $this->lang->execution->menu->settings['subMenu']            = new stdclass();
        $this->lang->execution->menu->settings['subMenu']->view      = array('link' => "{$this->lang->overview}|execution|view|executionID=%s", 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');
        $this->lang->execution->menu->settings['subMenu']->products  = array('link' => "{$this->lang->productCommon}|execution|manageproducts|executionID=%s");
        $this->lang->execution->menu->settings['subMenu']->team      = array('link' => "{$this->lang->team->common}|execution|team|executionID=%s", 'alias' => 'managemembers');
        $this->lang->execution->menu->settings['subMenu']->whitelist = array('link' => "{$this->lang->whitelist}|execution|whitelist|executionID=%s", 'subModule' => 'personnel', 'alias' => 'addwhitelist');
    }

    /**
     * 更新今日的累计流图数据。
     * Update today's cumulative flow graph data.
     *
     * @param  int       $executionID
     * @param  string    $type
     * @param  string    $colName
     * @param  array     $laneGroup
     * @access protected
     * @return void
     */
    protected function updateTodayCFDData(int $executionID, string $type, string $colName, array $laneGroup)
    {
        $cfd = new stdclass();
        $cfd->count = 0;
        $cfd->date  = helper::today();
        $cfd->type  = $type;
        foreach($laneGroup as $columnGroup)
        {
            foreach($columnGroup as $columnCard)
            {
                $cards = trim($columnCard->cards, ',');
                $cfd->count += $cards ? count(explode(',', $cards)) : 0;
            }
        }

        $cfd->name      = $colName;
        $cfd->execution = $executionID;
        $this->dao->replace(TABLE_CFD)->data($cfd)->exec();
    }

    /**
     * 获取任务分组数据。
     * Get task group data.
     *
     * @param  int       $executionID
     * @access protected
     * @return array
     */
    protected function getTaskGroups(int $executionID): array
    {
        $tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->orderBy('id_desc')
            ->fetchAll();
        $childTasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->ne(0)
            ->orderBy('id_desc')
            ->fetchGroup('parent');

        $taskGroups = array();
        foreach($tasks as $task)
        {
            $taskGroups[$task->module][$task->story][$task->id] = $task;
            if(!empty($childTasks[$task->id])) $taskGroups[$task->module][$task->story][$task->id]->children = $childTasks[$task->id];
        }

        return $taskGroups;
    }

    /**
     * 处理树状图需求类型数据。
     * Process tree chart demand type data.
     *
     * @param  object    $node
     * @param  array     $storyGroups
     * @param  array     $taskGroups
     * @param  array     $users
     * @param  int       $executionID
     * @access protected
     * @return object
     */
    protected function processStoryNode(object $node, array $storyGroups, array $taskGroups, array $users, int $executionID): object
    {
        $node->type = 'module';
        $stories = isset($storyGroups[$node->root][$node->id]) ? $storyGroups[$node->root][$node->id] : array();
        foreach($stories as $story)
        {
            $storyItem = new stdclass();
            $storyItem->type          = 'story';
            $storyItem->id            = 'story' . $story->id;
            $storyItem->title         = $story->title;
            $storyItem->color         = $story->color;
            $storyItem->pri           = $story->pri;
            $storyItem->storyId       = $story->id;
            $storyItem->openedBy      = zget($users, $story->openedBy);
            $storyItem->assignedTo    = zget($users, $story->assignedTo);
            $storyItem->url           = helper::createLink('execution', 'storyView', "storyID=$story->id&execution=$executionID");
            $storyItem->taskCreateUrl = helper::createLink('task', 'batchCreate', "executionID={$executionID}&story={$story->id}");

            $storyTasks = isset($taskGroups[$node->id][$story->id]) ? $taskGroups[$node->id][$story->id] : array();
            if(!empty($storyTasks))
            {
                $taskItems             = $this->formatTasksForTree($storyTasks, $story);
                $storyItem->tasksCount = count($taskItems);
                $storyItem->children   = $taskItems;
            }

            $node->children[] = $storyItem;
        }

        /* Append for task of no story && node is not root. */
        if($node->id && isset($taskGroups[$node->id][0]))
        {
            $taskItems = $this->formatTasksForTree($taskGroups[$node->id][0]);
            $node->tasksCount = count($taskItems);
            foreach($taskItems as $taskItem) $node->children[] = $taskItem;
        }

        return $node;
    }

    /**
     * 处理树状图任务类型数据。
     * Process tree chart task type data.
     *
     * @param  object    $node
     * @param  array     $taskGroups
     * @access protected
     * @return object
     */
    protected function processTaskNode(object $node, array $taskGroups): object
    {
        $node->type       = 'module';
        $node->tasksCount = 0;
        if(!isset($taskGroups[$node->id])) return $node;

        foreach($taskGroups[$node->id] as $tasks)
        {
            $taskItems = $this->formatTasksForTree($tasks);
            $node->tasksCount += count($taskItems);
            foreach($taskItems as $taskItem)
            {
                $node->children[$taskItem->id] = $taskItem;
                if(!empty($tasks[$taskItem->id]->children))
                {
                    $task = $this->formatTasksForTree($tasks[$taskItem->id]->children);
                    $node->children[$taskItem->id]->children=$task;
                    $node->tasksCount += count($task);
                }
            }
        }
        $node->children = array_values($node->children);

        return $node;
    }

    /**
     * Update team of execution when it edited.
     *
     * @param  int    $executionID
     * @param  object $oldExecution
     * @param  object $execution
     * @return void
     */
    protected function updateTeam(int $executionID, object $oldExecution, object $execution)
    {
        /* Get team and language item. */
        $this->loadModel('user');
        $team    = $this->user->getTeamMemberPairs($executionID, 'execution');
        $members = isset($_POST['teamMembers']) ? $_POST['teamMembers'] : array();
        array_push($members, $execution->PO, $execution->QD, $execution->PM, $execution->RD);
        $members = array_unique($members);
        $roles   = $this->user->getUserRoles(array_values($members));

        /* Add the member who joined the team. */
        $changedAccounts = array();
        $teamMembers     = array();
        foreach($members as $account)
        {
            if(empty($account) or isset($team[$account])) continue;

            $member = new stdclass();
            $member->root    = $executionID;
            $member->account = $account;
            $member->join    = helper::today();
            $member->role    = zget($roles, $account, '');
            $member->days    = zget($execution, 'days', 0);
            $member->type    = 'execution';
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();

            $changedAccounts[$account] = $account;
            $teamMembers[$account]     = $member;
        }

        /* Remove the member who left the team. */
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('root')->eq($executionID)
            ->andWhere('type')->eq('execution')
            ->andWhere('account')->in(array_keys($team))
            ->andWhere('account')->notin(array_values($members))
            ->andWhere('account')->ne($oldExecution->openedBy)
            ->exec();
        if(isset($execution->project) and $execution->project) $this->addProjectMembers((int)$execution->project, $teamMembers);

        /* Fix bug#3074, Update views for team members. */
        if($execution->acl != 'open') $this->updateUserView($executionID, 'sprint', $changedAccounts);
    }

    /**
     * 格式化任务树的数据。
     * Format tasks for tree.
     *
     * @param  array     $tasks
     * @param  object    $story
     * @access protected
     * @return array
     */
    protected function formatTasksForTree(array $tasks, object $story = null): array
    {
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

        $taskItems = array();
        foreach($tasks as $task)
        {
            $taskItem = new stdclass();
            $taskItem->type         = 'task';
            $taskItem->id           = $task->id;
            $taskItem->title        = $task->name;
            $taskItem->color        = $task->color;
            $taskItem->pri          = (int)$task->pri;
            $taskItem->status       = $task->status;
            $taskItem->parent       = $task->parent;
            $taskItem->estimate     = $task->estimate;
            $taskItem->consumed     = $task->consumed;
            $taskItem->left         = $task->left;
            $taskItem->openedBy     = zget($users, $task->openedBy);
            $taskItem->assignedTo   = zget($users, $task->assignedTo);
            $taskItem->url          = helper::createLink('task', 'view', "task=$task->id");
            $taskItem->storyChanged = $story && $story->status == 'active' && $story->version > $story->taskVersion;

            $taskItems[] = $taskItem;
        }

        return $taskItems;
    }

    /**
     * 添加执行的团队。
     * Add execution members.
     *
     * @param  int       $executionID
     * @param  array     $postMembers     e.g. array('admin', 'dev1')
     * @access protected
     * @return void
     */
    protected function addExecutionMembers(int $executionID, array $postMembers): void
    {
        $execution = $this->fetchByID($executionID);
        if(empty($execution)) return;

        array_push($postMembers, $execution->PO, $execution->QD, $execution->PM, $execution->RD, $execution->openedBy);
        $members     = array_filter(array_unique($postMembers));
        $roles       = $this->loadModel('user')->getUserRoles(array_values($members));
        $today       = helper::today();
        $teamMembers = array();
        $oldTeams    = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->eq($executionID)->andWhere('type')->eq('execution')->fetchPairs();
        foreach($members as $account)
        {
            if(isset($oldTeams[$account])) continue;

            $member = new stdClass();
            $member->root    = $executionID;
            $member->type    = 'execution';
            $member->account = $account;
            $member->role    = zget($roles, $account, '');
            $member->join    = $today;
            $member->days    = $execution->days;
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            $teamMembers[$account] = $member;
        }
        $this->addProjectMembers($execution->project, $teamMembers);
    }

    /**
     * 关联创建执行主库
     * Create main lib for product
     *
     * @param int productID
     * @access protected
     * @return int|false
     */
    protected function createMainLib(int $projectID, int $executionID, $type = 'sprint'): int|false
    {
        if($projectID < 0 || $executionID <= 0) return false;

        $existedLibID = (int)$this->dao->select('id')->from(TABLE_DOCLIB)->where('execution')->eq($executionID)
            ->andWhere('type')->eq('execution')
            ->andWhere('main')->eq('1')
            ->fetch('id');
        if($existedLibID) return $existedLibID;

        $this->app->loadLang('doc');
        if($type == 'sprint') $this->app->loadLang('project');
        $lib = new stdclass();
        $lib->project   = $projectID;
        $lib->execution = $executionID;
        $lib->name      = $type == 'stage' ? str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doclib->main['execution']) : $this->lang->doclib->main['execution'];
        $lib->type      = 'execution';
        $lib->main      = '1';
        $lib->acl       = 'default';
        $lib->addedBy   = $this->app->user->account;
        $lib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }
}
