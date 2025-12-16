<?php
declare(strict_types=1);
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /**
     * 设置菜单。
     * Set menu.
     *
     * @access public
     * @return void
     */
    public function setMenu()
    {
        /* Adjust the menu order according to the user role. */
        $flowModule = $this->config->global->flow . '_my';
        $customMenu = isset($this->config->customMenu->{$flowModule}) ? $this->config->customMenu->{$flowModule} : array();

        if(empty($customMenu))
        {
            $role = $this->app->user->role;
            if($role == 'qa')
            {
                $taskOrder = '15';
                unset($this->lang->my->menuOrder[$taskOrder]);

                $bugOrder = '20';
                $this->lang->my->menuOrder[32] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',' . $this->lang->my->menuOrder[$bugOrder] . ',', $this->lang->my->dividerMenu);
            }
            elseif($role == 'po')
            {
                $requirementOrder = 29;
                unset($this->lang->my->menuOrder[$requirementOrder]);

                $this->lang->my->menuOrder[15] = 'story';
                $this->lang->my->menuOrder[16] = 'requirement';
                $this->lang->my->menuOrder[30] = 'task';
                $this->lang->my->dividerMenu = str_replace(',task,', ',story,', $this->lang->my->dividerMenu);
            }
            elseif($role == 'pm')
            {
                $projectOrder = 35;
                unset($this->lang->my->menuOrder[$projectOrder]);

                $this->lang->my->menuOrder[17] = 'myProject';
            }
        }
    }

    /**
     * 获取产品相关统计。
     * Get product related data.
     *
     * @param  array   $productKeys
     * @access private
     * @return array
     */
    private function getProductRelatedData(array $productKeys): array
    {
        $storyGroups = $this->dao->select('id,product,status,stage,estimate')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productKeys)
            ->groupBy('product')
            ->fetchGroup('product', 'id');
        $summaryStories = array();
        foreach($storyGroups as $productID => $stories)
        {
            $summaryStory = new stdclass();
            $summaryStory->total = count($stories);

            $finishedTotal = 0;
            $leftTotal     = 0;
            $estimateCount = 0;
            foreach($stories as $story)
            {
                $estimateCount += $story->estimate;
                $story->status == 'closed' || $story->stage == 'released' || $story->stage == 'closed' ? $finishedTotal ++ : $leftTotal ++;
            }

            $summaryStory->finishedTotal = $finishedTotal;
            $summaryStory->leftTotal     = $leftTotal;
            $summaryStory->estimateCount = $estimateCount;
            $summaryStory->finishedRate  = $summaryStory->total == 0 ? 0 : ($finishedTotal / $summaryStory->total) * 100;
            $summaryStories[$productID]  = $summaryStory;
        }

        $plans      = $this->dao->select('product, COUNT(1) AS count')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->in($productKeys)->andWhere('end')->gt(helper::now())->groupBy('product')->fetchPairs();
        $releases   = $this->dao->select('product, COUNT(1) AS count')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('product')->in($productKeys)->groupBy('product')->fetchPairs();
        $executions = $this->dao->select('t1.product,t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productKeys)
            ->andWhere('t2.type')->in('stage,sprint')
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.project')
            ->fetchAll('product');
        $this->loadModel('execution');
        foreach($executions as $productID => $execution)
        {
            $execution = $this->execution->getById($execution->id);
            $executions[$productID]->progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
        }
        return array($summaryStories, $plans, $releases, $executions);
    }

    /**
     * 获取进行中的我的项目。
     * Get my projects.
     *
     * @access public
     * @return object
     */
    public function getDoingProjects(): object
    {
        $doingProjects = $this->loadModel('project')->getOverviewList('doing');
        $maxCount      = 5;
        $myProjects    = array();
        foreach($doingProjects as $key => $project)
        {
            if($project->PM == $this->app->user->account)
            {
                $myProjects[$key] = $project;
                unset($doingProjects[$key]);
            }
            if(count($myProjects) >= $maxCount) break;
        }
        if(count($myProjects) < $maxCount && !empty($doingProjects))
        {
            foreach($doingProjects as $key => $project)
            {
                $myProjects[$key] = $project;
                if(count($myProjects) >= $maxCount) break;
            }
        }

        foreach($myProjects as $key => $project)
        {
            $workhour = $this->project->getWorkhour($project->id);
            $project->progress = ($workhour->totalConsumed + $workhour->totalLeft) ? round($workhour->totalConsumed / ($workhour->totalConsumed + $workhour->totalLeft) * 100, 1) : 0;
            $project->delay    = helper::diffDate(helper::today(), $project->end) > 0;
            $project->link     = common::hasPriv('project', 'view') ? helper::createLink('project', 'view', "projectID={$project->id}") : '';
        }

        $data = new stdClass();
        $data->doingCount = count($myProjects);
        $data->projects   = array_values($myProjects);
        return $data;
    }

    /**
     * 获取我的概述。
     * Get overview.
     *
     * @access public
     * @return object
     */
    public function getOverview(): object
    {
        $inAdminGroup = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group=t2.id')
            ->where('t1.account')->eq($this->app->user->account)
            ->andWhere('t2.role')->eq('admin')
            ->fetch();

        $overview = new stdclass();
        if(!empty($inAdminGroup) || $this->app->user->admin)
        {
            $allConsumed      = 0;
            $thisYearConsumed = 0;
            $projects         = $this->loadModel('project')->getOverviewList('all', 0, 'id_desc', 0);
            $projectsConsumed = $this->project->getProjectsConsumed(array_keys($projects), 'THIS_YEAR');
            foreach($projects as $project)
            {
                $allConsumed      += $project->consumed;
                $thisYearConsumed += $projectsConsumed[$project->id]->totalConsumed;
            }

            $overview->projectTotal     = count($projects);
            $overview->allConsumed      = round($allConsumed, 1);
            $overview->thisYearConsumed = round($thisYearConsumed, 1);
        }
        else
        {
            $overview->myTaskTotal  = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
            $overview->myStoryTotal = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
            $overview->myBugTotal   = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
        }

        return $overview;
    }

    /**
     * 获取我的贡献。
     * Get my contribute.
     *
     * @access public
     * @return object
     */
    public function getContribute()
    {
        $account = $this->app->user->account;
        $inTeam  = $this->dao->select('root')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('account')->eq($account)->fetchPairs('root', 'root');

        $contribute = new stdclass();
        $contribute->myTaskTotal          = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($account)->andWhere('deleted')->eq(0)->fetch('count');
        $contribute->myStoryTotal         = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($account)->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
        $contribute->myBugTotal           = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($account)->andWhere('deleted')->eq(0)->fetch('count');
        $contribute->docCreatedTotal      = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_DOC)->where('addedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        $contribute->ownerProductTotal    = (int)$this->dao->select('COUNT(1) AS count')->from(TABLE_PRODUCT)->where('PO')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        $contribute->involvedProjectTotal = (int)$this->dao->select('COUNT(DISTINCT id) AS count')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('project')
            ->andWhere('id', true)->in($inTeam)
            ->orWhere('openedBy')->eq($account)
            ->orWhere('PO')->eq($account)
            ->orWhere('PM')->eq($account)
            ->orWhere('QD')->eq($account)
            ->orWhere('RD')->eq($account)
            ->markRight(1)
            ->fetch('count');

        return $contribute;
    }

    /**
     * 获取最新动态。
     * Get latest actions.
     *
     * @access public
     * @return array
     */
    public function getActions(): array
    {
        $maxCount = 5;
        $actions  = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc,id_desc', 50);
        $actions  = $this->action->processDynamicForAPI($actions);
        $actions  = array_slice($actions, 0, $maxCount);

        return $actions;
    }

    /**
     * 获取由我指派的对象。
     * Get assigned by me objects.
     *
     * @param  string $account
     * @param  int    $pager
     * @param  string $orderBy
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getAssignedByMe(string $account, ?object $pager = null, string $orderBy = 'id_desc', string $objectType = ''): array
    {
        $module       = $objectType == 'requirement' ? 'story' : $objectType;
        $objectIdList = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('actor')->eq($account)
            ->andWhere('objectType')->eq($module)
            ->andWhere('action')->eq('assigned')
            ->fetchPairs('objectID');
        if(empty($objectIdList)) return array();

        if($objectType == 'task') return $this->getTaskAssignedByMe($pager, $orderBy, $objectIdList);
        if($objectType == 'requirement' || $objectType == 'story' || $objectType == 'bug') return $this->myTao->getProductRelatedAssignedByMe($objectIdList, $objectType, $module, $orderBy, $pager);
        if(in_array($objectType, array('risk', 'issue', 'nc')))
        {
            return $this->dao->select('t1.*')->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.id')->in($objectIdList)
                ->orderBy('t1.' . $orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        if($objectType == 'feedback' || $objectType == 'ticket')
        {
            return $this->dao->select('t1.*,t2.dept')->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t1.id')->in($objectIdList)
                ->orderBy('t1.' . $orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        if($objectType == 'reviewissue')
        {
            return $this->dao->select('t1.*, t2.type')->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.review = t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.id')->in($objectIdList)
                ->orderBy('t1.' . $orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        return $this->dao->select('*')->from($this->config->objectTables[$module])
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($objectIdList)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取由我指派的任务。
     * Get tasks assigned by me.
     *
     * @param  object $pager
     * @param  string $orderBy
     * @param  array  $objectIdList
     * @access private
     * @return array
     */
    private function getTaskAssignedByMe(?object $pager = null, string $orderBy = 'id_desc', array $objectIdList = array()): array
    {
        // 处理优先级排序
        if(strpos($orderBy, 'pri_') !== false) $orderBy = str_replace('pri_', 'priOrder_', $orderBy);

        // 转换字段名称
        $tableFields = array(
            'projectName' => 't3.name',
            'executionName' => 't2.name',
            'executionType' => 't2.type',
            'executionMultiple' => 't2.multiple'
        );

        $orderBy = str_replace(array_keys($tableFields), array_values($tableFields), $orderBy);

        $objectList = $this->dao->select("t1.*, t3.id as project, t2.name as executionName, t2.multiple as executionMultiple, t3.name as projectName, t2.type as executionType, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on("t2.project = t3.id")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.id')->in($objectIdList)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');
        if($objectList) return $this->loadModel('task')->processTasks($objectList);
        return $objectList;
    }

    /**
     * 构建用例搜索表单。
     * Build case search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTestCaseSearchForm(int $queryID, string $actionURL): void
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $scene = $this->loadModel('testcase')->getSceneMenu(0);

        $queryName = $this->app->rawMethod . 'Testcase';
        $this->app->loadModuleConfig('testcase');
        $this->config->testcase->search['module']                      = $queryName;
        $this->config->testcase->search['queryID']                     = $queryID;
        $this->config->testcase->search['actionURL']                   = $actionURL;
        $this->config->testcase->search['params']['product']['values'] = $products;
        $this->config->testcase->search['params']['scene']['values']   = $scene;
        $this->config->testcase->search['params']['lib']['values']     = $this->loadModel('caselib')->getLibraries();

        unset($this->config->testcase->search['fields']['story']);
        unset($this->config->testcase->search['fields']['module']);
        unset($this->config->testcase->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * 通过搜索获取用例。
     * Get testcases by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTestcasesBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeTestcaseQuery' : 'workTestcaseQuery';
        $queryForm = $type == 'openedbyme' ? 'contributeTestcaseForm' : 'workTestcaseForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myTestcaseQuery = $this->session->{$queryName};
        $myTestcaseQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myTestcaseQuery);
        if($type == 'contribute')
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->alias('t1')
                ->where($myTestcaseQuery)
                ->andWhere('t1.openedBy')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $cases = $this->dao->select('t1.*,t3.name AS taskName')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t2.task = t3.id')
                ->where($myTestcaseQuery)
                ->andWhere('t2.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t3.status')->ne('done')
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        return $cases;
    }

    /**
     * 构建任务搜索表单。
     * Build search form for task page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $module
     * @param  bool   $cacheSearchFunc 是否缓存构造搜索参数的方法。默认缓存可以提高性能，构造搜索表单时再加载真实值。
     * @access public
     * @return array
     */
    public function buildTaskSearchForm(int $queryID, string $actionURL, string $module, bool $cacheSearchFunc = true): array
    {
        $this->loadModel('execution');
        $searchConfig = $this->config->execution->search;
        if($cacheSearchFunc)
        {
            $this->cacheSearchFunc($module, __METHOD__, func_get_args());
            return $searchConfig;
        }

        $searchConfig['module']    = $module;
        $searchConfig['actionURL'] = $actionURL;
        $searchConfig['queryID']   = $queryID;

        if($module == 'workTask')
        {
            unset($searchConfig['fields']['closedReason']);
            unset($searchConfig['fields']['closedBy']);
            unset($searchConfig['fields']['canceledBy']);
            unset($searchConfig['fields']['closedDate']);
            unset($searchConfig['fields']['canceledDate']);
            unset($searchConfig['params']['status']['values']['cancel']);
            unset($searchConfig['params']['status']['values']['closed']);
        }

        $projects = $this->loadModel('project')->getPairsByProgram();
        $searchConfig['params']['project']['values'] = $projects + array('all' => $this->lang->project->allProjects);

        $executions = $this->execution->getPairs(0, 'all', 'multiple');
        $searchConfig['params']['execution']['values'] = $executions + array('all' => $this->lang->execution->allExecutions);

        $searchConfig['params']['module']['values'] = $this->loadModel('tree')->getAllModulePairs();

        $this->loadModel('search')->setSearchParams($searchConfig);

        return $searchConfig;
    }

    /**
     * 通过搜索获取任务。
     * Get tasks by search.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getTasksBySearch(string $account, int $limit = 0, ?object $pager = null, string $orderBy = 'id_desc', int $queryID = 0): array
    {
        $moduleName = $this->app->rawMethod == 'work' ? 'workTask' : 'contributeTask';
        $queryName  = $moduleName . 'Query';
        $formName   = $moduleName . 'Form';

        $taskIdList = array();
        if($moduleName == 'contributeTask')
        {
            $tasksAssignedByMe = $this->getAssignedByMe($account, null, 'id_desc', 'task');
            $taskIdList        = array_keys($tasksAssignedByMe);
        }

        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($formName, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $tasks = $this->myTao->fetchTasksBySearch($this->session->{$queryName}, $moduleName, $account, $taskIdList, $orderBy, $limit, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', false);

        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;

        if($tasks) return $this->loadModel('task')->processTasks($tasks);
        return array();
    }

    /**
     * 构建 bug 搜索表单。
     * Build search form for bug page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildBugSearchForm(int $queryID, string $actionURL): void
    {
        $rawMethod = $this->app->rawMethod;
        $this->loadModel('bug');

        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $this->config->bug->search['module']    = $rawMethod . 'Bug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        if($rawMethod == 'work')
        {
            unset($this->config->bug->search['fields']['closedDate']);
            unset($this->config->bug->search['fields']['closedBy']);
        }

        $this->config->bug->search['params']['project']['values']       = $this->loadModel('project')->getPairsByProgram() + array('all' => $this->lang->bug->allProject);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('execution')->getPairs(0, 'all', 'multiple');
        $this->config->bug->search['params']['product']['values']       = $products;
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs();
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getAllModulePairs();
        $this->config->bug->search['params']['severity']['values']      = array(0 => '') + $this->lang->bug->severityList;
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs(array_keys($products), 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        unset($this->config->bug->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /*
     * 构建风险搜索表单。
     * Build risk search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildRiskSearchForm(int $queryID, string $actionURL): void
    {
        $projects  = $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('model')->ne('kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();
        $queryName = $this->app->rawMethod . 'Risk';

        $this->app->loadConfig('risk');
        $this->config->risk->search['module']    = $queryName;
        $this->config->risk->search['actionURL'] = $actionURL;
        $this->config->risk->search['queryID']   = $queryID;

        $this->config->risk->search['params']['project']['values'] = array('') + $projects;

        if(!isset($this->config->risk->search['fields'])) $this->config->risk->search['fields'] = array();
        unset($this->config->risk->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->risk->search);
    }

    /*
     * 构建评审意见搜索表单。
     * Build reviewissue search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildReviewissueSearchForm(int $queryID, string $actionURL): void
    {
        $projects  = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('type')->eq('project')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('model')->ne('kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();
        $queryName = $this->app->rawMethod . 'Reviewissue';

        $this->app->loadConfig('reviewissue');
        $this->config->reviewissue->search['module']    = $queryName;
        $this->config->reviewissue->search['actionURL'] = $actionURL;
        $this->config->reviewissue->search['queryID']   = $queryID;

        $this->config->reviewissue->search['params']['project']['values'] = array('') + $projects;
        $this->config->reviewissue->search['params']['type']['values']    = arrayUnion(array('' => ''), $this->lang->reviewissue->issueType);

        if(!isset($this->config->reviewissue->search['fields'])) $this->config->reviewissue->search['fields'] = array();
        unset($this->config->reviewissue->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->reviewissue->search);
    }

    /**
     * 通过搜索获取风险。
     * Get risks by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRisksBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeRiskQuery' : 'workRiskQuery';
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryName . 'Form', $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $riskQuery = $this->session->{$queryName};

        if($type == 'contribute')
        {
            $assignedByMe = $this->getAssignedByMe($this->app->user->account, null, 'id_desc', 'risk');
            $risks = $this->dao->select('*')->from(TABLE_RISK)
                ->where($riskQuery)
                ->andWhere('deleted')->eq('0')
                ->andWhere('createdBy',1)->eq($this->app->user->account)
                ->orWhere('id')->in(array_keys($assignedByMe))
                ->orWhere('closedBy')->eq($this->app->user->account)
                ->markRight(1)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($type == 'work')
        {
            $risks = $this->dao->select('*')->from(TABLE_RISK)
                ->where($riskQuery)
                ->andWhere('deleted')->eq('0')
                ->andWhere('assignedTo')->eq($this->app->user->account)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        return $risks;
    }

    /**
     * 通过搜索获取评审问题。
     * Get review issues by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReviewissuesBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeReviewissueQuery' : 'workReviewissueQuery';
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryName . 'Form', $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $reviewissueQuery = preg_replace('/`([^`]+)`/', 't1.$0', $this->session->{$queryName});
        if(strpos($reviewissueQuery, 't1.`review`') !== false) $reviewissueQuery = str_replace('t1.`review`', 't2.`object`', $reviewissueQuery);
        if(strpos($reviewissueQuery, 't1.`type`') !== false)   $reviewissueQuery = str_replace('t1.`type`', 't2.`type`', $reviewissueQuery);

        if($type == 'contribute')
        {
            $assignedByMe = $this->getAssignedByMe($this->app->user->account, null, 'id_desc', 'reviewissue');
            $reviewissues = $this->dao->select('t1.*, t2.type')->from(TABLE_REVIEWISSUE)->alias('t1')
                ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.review = t2.id')
                ->where($reviewissueQuery)
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t1.createdBy',1)->eq($this->app->user->account)
                ->orWhere('t1.id')->in(array_keys($assignedByMe))
                ->markRight(1)
                ->orderBy("t1.$orderBy")
                ->page($pager)
                ->fetchAll('id', false);
        }
        elseif($type == 'work')
        {
            $reviewissues = $this->dao->select('t1.*, t2.type')->from(TABLE_REVIEWISSUE)->alias('t1')
                ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.review = t2.id')
                ->where($reviewissueQuery)
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t1.assignedTo')->eq($this->app->user->account)
                ->orderBy("t1.$orderBy")
                ->page($pager)
                ->fetchAll('id', false);
        }
        return $reviewissues;
    }

    /**
     * 构建需求搜索表单。
     * Build Story search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $currentMethod
     * @access public
     * @return void
     */
    public function buildStorySearchForm(int $queryID, string $actionURL, string $currentMethod): void
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $branchParam   = '';
        $queryName     = $currentMethod . 'Story';
        $this->app->loadConfig('product');
        $this->config->product->search['module']                      = $queryName;
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['actionURL']                   = $actionURL;
        $this->config->product->search['params']['product']['values'] = $products;
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getPairs($productIdList, $branchParam);
        $this->config->product->search['fields']['title']             = $this->lang->story->title;
        $this->config->product->search['params']['grade']['values']   = $this->loadModel('story')->getGradePairs('story', 'enable');
        unset($this->config->product->search['fields']['module'], $this->config->product->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 通过搜索获取需求。
     * Get stories by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStoriesBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeStoryQuery' : 'workStoryQuery';
        $queryForm = $type == 'contribute' ? 'contributeStoryForm' : 'workStoryForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName}  == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myStoryQuery = $this->session->{$queryName};
        $myStoryQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myStoryQuery);
        if(strpos($myStoryQuery, 'result') !== false) $myStoryQuery = str_replace('t1.`result`', 't5.`result`', $myStoryQuery);

        return $this->myTao->fetchStoriesBySearch($myStoryQuery, $type, $orderBy, $pager, $type == 'contribute' ? $this->getAssignedByMe($this->app->user->account, null, $orderBy, 'story') : array());
    }

    /**
     * 构建业务需求搜索表单。
     * Build epic search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildEpicSearchForm(int $queryID, string $actionURL): void
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $queryName     = $this->app->rawMethod . 'Epic';
        $this->app->loadConfig('product');
        $this->config->product->search['module']                      = $queryName;
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['actionURL']                   = $actionURL;
        $this->config->product->search['params']['product']['values'] = $products;
        $this->config->product->search['params']['grade']['values']   = $this->loadModel('story')->getGradePairs('epic', 'enable');
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getPairs($productIdList);

        $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->ERCommon, $this->lang->story->title);
        $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->ERCommon, $this->lang->story->create);
        $this->config->product->search['fields']['title'] = $this->lang->story->title;

        unset($this->config->product->search['fields']['plan']);
        unset($this->config->product->search['fields']['stage']);
        unset($this->config->product->search['fields']['module']);
        unset($this->config->product->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构建用户需求搜索表单。
     * Build Requirement search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildRequirementSearchForm(int $queryID, string $actionURL): void
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $queryName     = $this->app->rawMethod . 'Requirement';
        $this->app->loadConfig('product');
        $this->config->product->search['module']                      = $queryName;
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['actionURL']                   = $actionURL;
        $this->config->product->search['params']['product']['values'] = $products;
        $this->config->product->search['params']['grade']['values']   = $this->loadModel('story')->getGradePairs('requirement', 'enable');
        $this->config->product->search['params']['plan']['values']    = $this->loadModel('productplan')->getPairs($productIdList);

        $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
        $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
        $this->config->product->search['fields']['title'] = $this->lang->story->title;

        unset($this->config->product->search['fields']['plan']);
        unset($this->config->product->search['fields']['stage']);
        unset($this->config->product->search['fields']['module']);
        unset($this->config->product->search['fields']['branch']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构建工单搜索表单。
     * Build ticket search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return bool
     */
    public function buildTicketSearchForm(int $queryID, string $actionURL): bool
    {
        if($this->config->edition == 'open')  return false;

        $this->loadModel('ticket');

        $grantProducts = $this->loadModel('feedback')->getGrantProducts();

        $queryName = $this->app->rawMethod . 'Ticket';
        $this->config->ticket->search['module']    = $queryName;
        $this->config->ticket->search['queryID']   = $queryID;
        $this->config->ticket->search['actionURL'] = $actionURL;
        $this->config->ticket->search['params']['product']['values']     = $grantProducts;
        $this->config->ticket->search['params']['module']['values']      = $this->loadModel('feedback')->getModuleList('ticket', true, 'no');
        $this->config->ticket->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs(array_keys($grantProducts), 'all', 'releasetag');
        $this->config->ticket->search['params']['feedback']['values']    = $this->feedback->getPairs();

        $this->loadModel('search')->setSearchParams($this->config->ticket->search);

        return true;
    }

    /**
     * 通过搜索获取业务需求。
     * Get epics by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getEpicsBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeEpicQuery' : 'workEpicQuery';
        $queryForm = $type == 'contribute' ? 'contributeEpicForm' : 'workEpicForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myEpicQuery = $this->session->{$queryName};
        $myEpicQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myEpicQuery);
        if(strpos($myEpicQuery, 'result') !== false) $myEpicQuery = str_replace('t1.`result`', 't3.`result`', $myEpicQuery);

        $epicsAssignedByMe = $type == 'contribute' ? $this->getAssignedByMe($this->app->user->account, null, 'id_desc', 'epic') : array();
        $epicIdList        = array_keys($epicsAssignedByMe);

        return $this->myTao->fetchEpicsBySearch($myEpicQuery, $type, $orderBy, $pager, $epicIdList, 'epic');
    }

    /**
     * 通过搜索获取用户需求。
     * Get requirements by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getRequirementsBySearch(int $queryID, string $type, string $orderBy, ?object $pager = null): array
    {
        $queryName = $type == 'contribute' ? 'contributeRequirementQuery' : 'workRequirementQuery';
        $queryForm = $type == 'contribute' ? 'contributeRequirementForm' : 'workRequirementForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($queryForm, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$queryName} == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myRequirementQuery = $this->session->{$queryName};
        $myRequirementQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myRequirementQuery);
        if(strpos($myRequirementQuery, 'result') !== false) $myRequirementQuery = str_replace('t1.`result`', 't3.`result`', $myRequirementQuery);

        $requirementsAssignedByMe = $type == 'contribute' ? $this->getAssignedByMe($this->app->user->account, null, 'id_desc', 'requirement') : array();
        $requirementIdList        = array_keys($requirementsAssignedByMe);

        return $this->myTao->fetchRequirementsBySearch($myRequirementQuery, $type, $orderBy, $pager, $requirementIdList, 'requirement');
    }

    /**
     * 为菜单获取待评审的类型。
     * Get reviewing type list for menu.
     *
     * @access public
     * @return object
     */
    public function getReviewingTypeList()
    {
        $typeList = array();
        if($this->config->edition == 'ipd' && $this->getReviewingDemands('id_desc', true)) $typeList[] = 'demand';
        if($this->getReviewingStories('id_desc', true, 'story'))       $typeList[] = 'story';
        if($this->getReviewingStories('id_desc', true, 'epic'))        $typeList[] = 'epic';
        if($this->getReviewingStories('id_desc', true, 'requirement')) $typeList[] = 'requirement';
        if($this->config->vision != 'or' && $this->getReviewingCases('id_desc', true))     $typeList[] = 'testcase';
        if($this->config->vision != 'or' && $this->getReviewingApprovals('id_desc', true)) $typeList[] = 'project';
        if($this->getReviewingFeedbacks('id_desc', true)) $typeList[] = 'feedback';
        if($this->config->vision != 'or' && $this->getReviewingOA('status', true))         $typeList[] = 'oa';
        if($this->config->vision != 'or' && $this->getReviewingMRs('id_desc')) $typeList[] = 'mr';
        $typeList = array_merge($typeList, $this->getReviewingFlows('all', 'id_desc', true));

        $flows = $this->config->edition == 'open' ? array() : $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('module')->in($typeList)->andWhere('buildin')->eq(0)->fetchPairs('module', 'name');
        $menu  = new stdclass();
        $menu->all = $this->lang->my->featureBar['audit']['all'];
        foreach($typeList as $type)
        {
            $this->app->loadLang($type);
            $menu->{$type} = isset($this->lang->my->featureBar['audit'][$type]) ? $this->lang->my->featureBar['audit'][$type] : zget($flows, $type, isset($this->lang->{$type}->common) ? $this->lang->{$type}->common : $type);
        }

        return $menu;
    }

    /**
     * 获取我的审批列表。
     * Get reviewing list for me.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReviewingList(string $browseType, string $orderBy = 'time_desc', ?object $pager = null): array
    {
        $vision     = $this->config->vision;
        $reviewList = array();
        if($browseType == 'all' || $browseType == 'demand')                                                                 $reviewList = array_merge($reviewList, $this->getReviewingDemands());
        if($browseType == 'all' || $browseType == 'story')                                                                  $reviewList = array_merge($reviewList, $this->getReviewingStories());
        if($browseType == 'all' || $browseType == 'epic')                                                                   $reviewList = array_merge($reviewList, $this->getReviewingStories('id_desc', false, 'epic'));
        if($browseType == 'all' || $browseType == 'requirement')                                                            $reviewList = array_merge($reviewList, $this->getReviewingStories('id_desc', false, 'requirement'));
        if($vision != 'or' && ($browseType == 'all' || $browseType == 'testcase') && common::hasPriv('testcase', 'review')) $reviewList = array_merge($reviewList, $this->getReviewingCases());
        if($vision != 'or' && ($browseType == 'all' || $browseType == 'project'))                                           $reviewList = array_merge($reviewList, $this->getReviewingApprovals());
        if($vision != 'or' && ($browseType == 'all' || $browseType == 'oa'))                                                $reviewList = array_merge($reviewList, $this->getReviewingOA());
        if($vision != 'or' && ($browseType == 'all' || $browseType == 'mr'))                                                $reviewList = array_merge($reviewList, $this->getReviewingMRs());
        if($browseType == 'all' || !in_array($browseType, $this->config->my->noFlowAuditModules))                           $reviewList = array_merge($reviewList, $this->getReviewingFlows($browseType));
        if(($browseType == 'all' || $browseType == 'feedback') && common::hasPriv('feedback', 'review'))                    $reviewList = array_merge($reviewList, $this->getReviewingFeedbacks());
        if(empty($reviewList)) return array();

        $field     = $orderBy;
        $direction = 'asc';
        if(strpos($orderBy, '_') !== false) list($field, $direction) = explode('_', $orderBy);

        /* Sort review. */
        $reviewGroup = array();
        foreach($reviewList as $review)
        {
            if(!isset($review->{$field})) $field = 'time';
            $reviewGroup[$review->{$field}][] = $review;
        }
        if($direction == 'asc')  ksort($reviewGroup);
        if($direction == 'desc') krsort($reviewGroup);

        $reviewList = array();
        foreach($reviewGroup as $reviews) $reviewList = array_merge($reviewList, $reviews);

        /* Pager. */
        if(!is_null($pager))
        {
            $pager->setRecTotal(count($reviewList));
            $pager->setPageTotal();
            $pager->setPageID($pager->pageID);
            $reviewList = array_chunk($reviewList, (int)$pager->recPerPage);
            $reviewList = $reviewList[$pager->pageID - 1];
        }

        return $reviewList;
    }

    /**
     * 获取待评审的需求池需求。
     * Get reviewing demands.
     *
     * @param  string    $orderBy
     * @param  bool      $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingDemands(string $orderBy = 'id_desc', bool $checkExists = false): array|bool
    {
        if($this->config->edition != 'ipd') return array();

        $this->app->loadLang('demand');
        $demands = $this->dao->select("t1.id, t1.title, 'demand' AS type, t1.createdDate AS time, t1.status, t1.product, 0 AS project")->from(TABLE_DEMAND)->alias('t1')
            ->leftJoin(TABLE_DEMANDREVIEW)->alias('t2')->on('t1.id = t2.demand and t1.version = t2.version')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.reviewer')->eq($this->app->user->account)
            ->andWhere('t2.result')->eq('')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.status')->eq('reviewing')
            ->orderBy($orderBy)
            ->beginIF($checkExists)->limit(1)->fi()
            ->fetchAll('id');

        if($checkExists)
        {
            return !empty($demands);
        }

         $actions = $this->dao->select('objectID, `date`')->from(TABLE_ACTION)->where('objectType')->eq('demand')->andWhere('objectID')->in(array_keys($demands))->andWhere('action')->eq('submitreview')->orderBy('`date`')->fetchPairs('objectID', 'date');
         foreach($actions as $demandID => $date) $demands[$demandID]->time = $date;
         return array_values($demands);
    }

    /**
     * 获取待审批的需求。
     * Get reviewing stories.
     *
     * @param  string     $orderBy
     * @param  bool       $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingStories(string $orderBy = 'id_desc', bool $checkExists = false, $type = 'story'): array|bool
    {
        $this->app->loadLang($type);
        $stories = $this->dao->select("t1.id, t1.title, 'story' AS type, t1.type AS storyType, t1.openedDate AS time, t1.status, t1.product, 0 AS project, t1.parent")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version')
            ->where('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->andWhere('t2.reviewer')->eq($this->app->user->account)
            ->andWhere('t2.result')->eq('')
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.status')->eq('reviewing')
            ->orderBy($orderBy)
            ->beginIF($checkExists)->limit(1)->fi()
            ->fetchAll('id');

        if($checkExists)
        {
            return !empty($stories);
        }

        $actions = $this->dao->select('objectID,`date`')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('objectID')->in(array_keys($stories))->andWhere('action')->eq('submitreview')->orderBy('`date`')->fetchPairs();
        foreach($actions as $storyID => $date) $stories[$storyID]->time = $date;
        return array_values($stories);
    }

    /**
     * 获取待评审的用例。
     * Get reviewing cases.
     *
     * @param  string     $orderBy
     * @param  bool       $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingCases(string $orderBy = 'id_desc', bool $checkExists = false): array|bool
    {
        $cases = $this->dao->select("t1.id, t1.title, 'testcase' AS type, t1.openedDate AS time, t1.status, t1.product, t1.project")->from(TABLE_CASE)->alias('t1')
            ->where('deleted')->eq('0')
            ->andWhere('status')->eq('wait')
            ->beginIF(!$this->app->user->admin)->andWhere('(product')->in($this->app->user->view->products)->orWhere('lib')->gt(0)->markRight(1)->fi()
            ->orderBy($orderBy)
            ->beginIF($checkExists)->limit(1)->fi()
            ->fetchAll('id');

        if($checkExists) return !empty($cases);
        return array_values($cases);
    }

    /**
     * 获取待评审的MR。
     * Get reviewing mrs.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReviewingMRs(string $orderBy = 'id_desc'): array
    {
        return $this->dao->select("`id`, `title`, IF(`isFlow`='1', 'pullreq', 'mr') AS type, `createdDate` AS time, `approvalStatus` AS status, 0 AS product, 0 AS project")->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('approvalStatus')->notIn(array('approved', 'rejected'))
            ->andWhere('status')->ne('closed')
            ->andWhere('assignee')->eq($this->app->user->account)
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 获取待评审的审批。
     * Get reviewing approvals.
     *
     * @param  string     $orderBy
     * @param  bool       $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingApprovals(string $orderBy = 'id_desc', bool $checkExists = false): array|bool
    {
        if($this->config->edition != 'max' and $this->config->edition != 'ipd') return array();

        $pendingList    = $this->loadModel('approval')->getPendingReviews('review');
        $projectReviews = $this->loadModel('review')->getByList(0, $pendingList, $orderBy);

        if($checkExists) return !empty($projectReviews);

        $this->app->loadLang('project');
        $this->session->set('reviewList', $this->app->getURI(true));

        $reviewList = array();
        foreach($projectReviews as $review)
        {
            if(!isset($pendingList[$review->id])) continue;

            $data = new stdclass();
            $data->id      = $review->id;
            $data->title   = $review->title;
            $data->type    = 'projectreview';
            $data->time    = date('Y-m-d H:i:s', strtotime($review->createdDate));
            $data->status  = $review->status;
            $data->product = $review->product;
            $data->project = $review->project;
            $reviewList[] = $data;
        }
        return $reviewList;
    }

    /**
     * 获取审批中的工作流设置。
     * Get reviewing for flows setting.
     *
     * @param  string $objectType
     * @param  string $orderBy
     * @param  bool   $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingFlows($objectType = 'all', $orderBy = 'id_desc', $checkExists = false): array|bool
    {
        $dataList = $this->dao->select('t2.objectType,t2.objectID')->from(TABLE_APPROVALNODE)->alias('t1')
            ->leftJoin(TABLE_APPROVALOBJECT)->alias('t2')->on('t2.approval = t1.approval')
            ->where('t2.objectType')->ne('review')
            ->beginIF($objectType != 'all')->andWhere('t2.objectType')->eq($objectType)->fi()
            ->andWhere('t1.account')->eq($this->app->user->account)
            ->andWhere('t1.status')->eq('doing')
            ->andWhere('t1.type')->eq('review')
            ->beginIF(!helper::hasFeature('project_cm'))->andWhere('objectType')->ne('baseline')->fi()
            ->beginIF(!helper::hasFeature('project_change'))->andWhere('objectType')->ne('projectchange')->fi()
            ->beginIF(!helper::hasFeature('project_risk'))->andWhere('objectType')->ne('risk')->fi()
            ->beginIF(!helper::hasFeature('project_issue'))->andWhere('objectType')->ne('issue')->fi()
            ->beginIF(!helper::hasFeature('project_opportunity'))->andWhere('objectType')->ne('opportunity')->fi()
            ->orderBy("t2.{$orderBy}")
            ->beginIF($checkExists)->limit(1)->fi()
            ->fetchAll();

        $objectIdList = array();
        foreach($dataList as $data) $objectIdList[$data->objectType][$data->objectID] = $data->objectID;

        $this->loadModel('flow');
        $this->loadModel('workflowaction');
        $flows       = $this->dao->select('module,`table`,name,titleField,app')->from(TABLE_WORKFLOW)->where('module')->in(array_keys($objectIdList))->andWhere('vision')->eq($this->config->vision)->fetchAll('module');
        $objectGroup = array();
        foreach($objectIdList as $objectType => $idList)
        {
            $table = zget($this->config->objectTables, $objectType, '');
            if(!in_array($objectType, $this->config->my->notFlowModules))
            {
                if(!isset($flows[$objectType])) continue;
                if(!$table) $table = $flows[$objectType]->table;
            }
            if(empty($table)) continue;

            $objectGroup[$objectType] = $this->dao->select('*')->from($table)->where('id')->in($idList)->andWhere('deleted')->eq('0')->fetchAll('id');

            $action = $this->workflowaction->getByModuleAndAction($objectType, 'approvalreview');
            if($action)
            {
                foreach($objectGroup[$objectType] as $objectID => $object)
                {
                    if(!$this->flow->checkConditions($action->conditions, $object)) unset($objectIdList[$objectType][$objectID], $objectGroup[$objectType][$objectID]);
                }
            }
        }
        if($checkExists) return array_keys(array_filter($objectGroup));

        $this->app->loadConfig('action');
        return $this->myTao->buildReviewingFlows($objectGroup, $flows, $this->config->action->objectNameFields);
    }

    /**
     * 获取评审中的反馈。
     * Get reviewing feedbacks.
     *
     * @param  string     $orderBy
     * @param  bool       $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingFeedbacks(string $orderBy = 'id_desc', bool $checkExists = false): array|bool
    {
        if($this->config->edition == 'open') return array();

        $this->session->set('feedbackProduct', 'all');
        $feedbacks = $this->loadModel('feedback')->getList('review', $orderBy);
        if($checkExists) return !empty($feedbacks);

        $reviewList = array();
        foreach($feedbacks as $feedback)
        {
            $data = new stdclass();
            $data->id      = $feedback->id;
            $data->title   = $feedback->title;
            $data->type    = 'feedback';
            $data->time    = $feedback->openedDate;
            $data->status  = $feedback->status;
            $data->product = isset($feedback->product) ? $feedback->product : 0;
            $data->project = isset($feedback->project) ? $feedback->project : 0;
            $reviewList[] = $data;
        }
        return $reviewList;
    }

    /**
     * 获取评审中的OA。
     * Get reviewing OA.
     *
     * @param  string     $orderBy
     * @param  bool       $checkExists
     * @access public
     * @return array|bool
     */
    public function getReviewingOA(string $orderBy = 'status', bool $checkExists = false): array|bool
    {
        if($this->config->edition == 'open') return array();

        /* Get dept info. */
        $allDeptList = $this->loadModel('dept')->getDeptPairs();
        $allDeptList['0'] = '/';
        $managedDeptList = array();
        $tmpDept = $this->dept->getDeptManagedByMe($this->app->user->account);
        foreach($tmpDept as $d) $managedDeptList[$d->id] = $d->name;

        $oa = array();
        if(common::hasPriv('attend',   'review'))                                        $oa['attend']   = $this->getReviewingAttends($allDeptList, $managedDeptList);
        if(common::hasPriv('leave',    'review') && common::hasPriv('leave',    'view')) $oa['leave']    = $this->getReviewingLeaves($allDeptList, $managedDeptList, $orderBy);
        if(common::hasPriv('overtime', 'review') && common::hasPriv('overtime', 'view')) $oa['overtime'] = $this->getReviewingOvertimes($allDeptList, $managedDeptList, $orderBy);
        if(common::hasPriv('makeup',   'review') && common::hasPriv('makeup',   'view')) $oa['makeup']   = $this->getReviewingMakeups($allDeptList, $managedDeptList, $orderBy);
        if(common::hasPriv('lieu',     'review') && common::hasPriv('lieu',     'view')) $oa['lieu']     = $this->getReviewingLieus($allDeptList, $managedDeptList, $orderBy);
        if($checkExists)
        {
            foreach($oa as $type => $reviewings)
            {
                if(!empty($reviewings)) return true;
            }
        }

        $reviewList = array();
        $users      = $this->loadModel('user')->getPairs('noletter');
        foreach($oa as $type => $reviewings)
        {
            foreach($reviewings as $object)
            {
                $review = new stdclass();
                $review->id     = $object->id;
                $review->type   = $type;
                $review->time   = $type == 'attend' ? $object->date : $object->createdDate;
                $review->status = $type == 'attend' ? $object->reviewStatus : $object->status;
                $review->title  = '';
                $review->product = isset($object->product) ? $object->product : 0;
                $review->project = isset($object->project) ? $object->project : 0;
                if($type == 'attend')
                {
                    $review->title = sprintf($this->lang->my->auditField->oaTitle[$type], zget($users, $object->account), $object->date);
                }
                elseif(isset($this->lang->my->auditField->oaTitle[$type]))
                {
                    $review->title = sprintf($this->lang->my->auditField->oaTitle[$type], zget($users, $object->createdBy), $object->begin . ' ' . substr($object->start, 0, 5) . ' ~ ' . $object->end . ' ' . substr($object->finish, 0, 5));
                }
                $reviewList[] = $review;
            }
        }
        return $reviewList;
    }

    /**
     * 获取评审列表。
     * Get reviewed list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReviewedList(string $browseType, string $orderBy = 'time_desc', ?object $pager = null): array
    {
        $field     = $orderBy;
        $direction = 'asc';
        if(strpos($orderBy, '_') !== false) list($field, $direction) = explode('_', $orderBy);

        $actionField = 'date';
        if($field == 'type') $actionField = 'objectType';
        if($field == 'id')   $actionField = 'objectID';
        $orderBy = $actionField . '_' . $direction;

        $condition = "(`action` = 'reviewed' or `action` = 'approvalreview')";
        if($browseType == 'createdbyme')
        {
            $condition  = "(objectType in('story','case','feedback') and action = 'submitreview') OR ";
            $condition .= "(objectType = 'review' and action = 'opened') OR ";
            $condition .= "(objectType = 'deploy' and action = 'created') OR ";
            $condition .= "(objectType = 'deploy' and action = 'submit') OR ";
            $condition .= "(objectType = 'attend' and action = 'commited') OR ";
            $condition .= "(`action` = 'approvalsubmit') OR ";
            $condition .= "(objectType in('leave','makeup','overtime','lieu') and action = 'created')";
            $condition  = "($condition)";
        }
        $actionIdList = $this->dao->select('MAX(`id`) as `id`')->from(TABLE_ACTION)
            ->where('actor')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere($condition)
            ->groupBy('objectType,objectID')
            ->fetchPairs();

        $objectTypeList = array();
        $actions        = $this->dao->select('objectType,objectID,actor,action,`date`,extra')->from(TABLE_ACTION)->where('id')->in($actionIdList)->orderBy($orderBy)->fetchAll();
        foreach($actions as $action) $objectTypeList[$action->objectType][] = $action->objectID;

        $flows       = $this->config->edition == 'open' ? array() : $this->dao->select('module,`table`,name,titleField')->from(TABLE_WORKFLOW)->where('module')->in(array_keys($objectTypeList))->andWhere('buildin')->eq(0)->fetchAll('module');
        $objectGroup = array();
        foreach($objectTypeList as $objectType => $idList)
        {
            $table = zget($this->config->objectTables, $objectType, '');
            if(empty($table) && isset($flows[$objectType])) $table = $flows[$objectType]->table;
            if(empty($table)) continue;

            if(in_array($objectType, array('story', 'testcase', 'case')))
            {
                $objectGroup[$objectType] = $this->dao->select('t1.*')
                    ->from($table)->alias('t1')
                    ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                    ->where('t1.id')->in($idList)->andWhere('t1.deleted')->eq('0')
                    ->andWhere('t2.deleted')->eq('0')
                    ->fetchAll('id');
            }
            else
            {
                $objectGroup[$objectType] = $this->dao->select('*')->from($table)
                    ->where('id')->in($idList)
                    ->beginIF(strpos(',attend,overtime,makeup,leave,lieu,', ",{$objectType},") === false)->andWhere('deleted')->eq('0')->fi()
                    ->fetchAll('id');
            }
        }

        $reviewedList = $this->buildReviewedList($objectGroup, $actions, $flows);
        if(!is_null($pager))
        {
            $pager->setRecTotal(count($reviewedList));
            $pager->setPageTotal();
            $pager->setPageID($pager->pageID);
            $reviewedList = array_chunk($reviewedList, (int)$pager->recPerPage);
            $reviewedList = !empty($reviewedList[$pager->pageID - 1]) ? $reviewedList[$pager->pageID - 1] : array();
        }

        return !empty($reviewedList) ? $reviewedList : array();
    }

    /**
     * 构建评审列表。
     * Build reviewed list.
     *
     * @param  array   $objectGroup
     * @param  array   $actions
     * @param  array   $flows
     * @access private
     * @return array
     */
    private function buildReviewedList(array $objectGroup, array $actions, array $flows): array
    {
        $this->app->loadConfig('action');
        $reviewList = array();
        $users      = $this->loadModel('user')->getPairs('noletter');
        foreach($actions as $action)
        {
            $objectType = $action->objectType;
            if(!isset($objectGroup[$objectType]) || !$action->objectID) continue;
            if(!isset($objectGroup[$objectType][$action->objectID])) continue;

            $object = $objectGroup[$objectType][$action->objectID];
            $review = new stdclass();
            $review->id     = $object->id;
            $review->type   = $objectType;
            $review->time   = substr($action->date, 0, 19);
            $review->result = strtolower($action->extra);
            $review->status = $objectType == 'attend' ? $object->reviewStatus : (isset($object->status) && !isset($flows[$objectType]) ? $object->status : 'done');
            if(strpos($review->result, ',') !== false) list($review->result) = explode(',', $review->result);

            $review->product = isset($object->product) ? $object->product : 0;
            $review->project = isset($object->project) ? $object->project : 0;

            if($objectType == 'story')    $review->storyType = $object->type;
            if($review->type == 'review') $review->type = 'projectreview';
            if($review->type == 'case')   $review->type = 'testcase';

            if(empty($review->result) && isset($object->reviewResult)) $review->result = zget($this->lang->my->reviewResultList, $object->reviewResult, '');

            $review->title = '';
            if(isset($object->title))
            {
                $review->title = $object->title;
            }
            elseif($objectType == 'attend')
            {
                $review->title = sprintf($this->lang->my->auditField->oaTitle[$objectType], zget($users, $object->account), $object->date);
            }
            elseif(isset($this->lang->my->auditField->oaTitle[$objectType]))
            {
                $review->title = sprintf($this->lang->my->auditField->oaTitle[$objectType], zget($users, $object->createdBy), $object->begin . ' ' . substr($object->start, 0, 5) . ' ~ ' . $object->end . ' ' . substr($object->finish, 0, 5));
            }
            else
            {
                $title          = '';
                $titleFieldName = zget($this->config->action->objectNameFields, $objectType, '');
                if(empty($titleFieldName) && isset($flows[$objectType]))
                {
                    if(!empty($flows[$objectType]->titleField)) $titleFieldName = $flows[$objectType]->titleField;
                    if(empty($flows[$objectType]->titleField)) $title = $flows[$objectType]->name;
                }
                $review->title = empty($titleFieldName) || !isset($object->{$titleFieldName}) ? "{$title} #{$object->id}" : $object->{$titleFieldName};
            }
            $reviewList[] = $review;
        }
        return $reviewList;
    }

    /**
     * 获取工作流键值对。
     * Get flow paris.
     *
     * @access public
     * @return array
     */
    public function getFlowPairs(): array
    {
        return $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->fetchPairs();
    }

    /**
     * 获取我的产品。
     * Get my charged products.
     *
     * @param  string $type undone|ownbyme
     * @access public
     * @return object
     */
    public function getProducts(string $type = 'undone'): object
    {
        $products = $this->dao->select('t1.*, t2.name as programName')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type == 'undone')->andWhere('t1.status')->eq('normal')->fi()
            ->beginIF($type == 'ownbyme')->andWhere('t1.PO')->eq($this->app->user->account)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->filterTpl('skip')
            ->orderBy('t1.order_asc')
            ->fetchAll('id');

        list($summaryStories, $plans, $releases, $executions) = $this->getProductRelatedData(array_keys($products));

        $allCount      = count($products);
        $unclosedCount = 0;
        foreach($products as $key => $product)
        {
            $product->plans      = isset($plans[$product->id]) ? $plans[$product->id] : 0;
            $product->releases   = isset($releases[$product->id]) ? $releases[$product->id] : 0;
            if(isset($executions[$product->id])) $product->executions = $executions[$product->id];
            $product->storyEstimateCount = isset($summaryStories[$product->id]) ? $summaryStories[$product->id]->estimateCount : 0;
            $product->storyTotal         = isset($summaryStories[$product->id]) ? $summaryStories[$product->id]->total : 0;
            $product->storyFinishedTotal = isset($summaryStories[$product->id]) ? $summaryStories[$product->id]->finishedTotal : 0;
            $product->storyLeftTotal     = isset($summaryStories[$product->id]) ? $summaryStories[$product->id]->leftTotal : 0;
            $product->storyFinishedRate  = isset($summaryStories[$product->id]) ? $summaryStories[$product->id]->finishedRate : 0;
            $product->latestExecution    = isset($executions[$product->id])     ? $executions[$product->id] : '';
            if($product->status != 'closed') $unclosedCount ++;
            if($product->status == 'closed') unset($products[$key]);
        }

        /* Sort by storyCount, get 5 records */
        $products = json_decode(json_encode($products), true);
        array_multisort(helper::arrayColumn($products, 'storyEstimateCount'), SORT_DESC, $products);
        $products = array_slice($products, 0, 5);

        $data = new stdClass();
        $data->allCount      = $allCount;
        $data->unclosedCount = $unclosedCount;
        $data->products      = array_values($products);
        return $data;
    }
}
