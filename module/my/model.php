<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /**
     * Set menu.
     *
     * @access public
     * @return void
     */
    public function setMenu()
    {
        /* Adjust the menu order according to the user role. */
        $flowModule = $this->config->global->flow . '_my';
        $customMenu = isset($this->config->customMenu->$flowModule) ? $this->config->customMenu->$flowModule : array();

        if(empty($customMenu))
        {
            $role = $this->app->user->role;
            if($role == 'qa')
            {
                $taskOrder = '15';
                $bugOrder  = '20';

                unset($this->lang->my->menuOrder[$taskOrder]);
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
     * Get my charged products.
     *
     * @param  string $type     undone|ownbyme
     * @access public
     * @return object
     */
    public function getProducts($type = 'undone')
    {
        $products = $this->dao->select('t1.*, t2.name as programName')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type == 'undone')->andWhere('t1.status')->eq('normal')->fi()
            ->beginIF($type == 'ownbyme')->andWhere('t1.PO')->eq($this->app->user->account)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.order_asc')
            ->fetchAll('id');
        $productKeys = array_keys($products);

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
                ($story->status == 'closed' or $story->stage == 'released' or $story->stage == 'closed') ? $finishedTotal ++ : $leftTotal ++;
            }

            $summaryStory->finishedTotal = $finishedTotal;
            $summaryStory->leftTotal     = $leftTotal;
            $summaryStory->estimateCount = $estimateCount;
            $summaryStory->finishedRate  = $summaryStory->total == 0 ? 0 : ($finishedTotal / $summaryStory->total) * 100;
            $summaryStories[$productID]  = $summaryStory;
        }

        $plans = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productKeys)
            ->andWhere('end')->gt(helper::now())
            ->groupBy('product')
            ->fetchPairs();
        $releases = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productKeys)
            ->groupBy('product')
            ->fetchPairs();
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
        array_multisort(array_column($products, 'storyEstimateCount'), SORT_DESC, $products);
        $products = array_slice($products, 0, 5);

        $data = new stdClass();
        $data->allCount      = $allCount;
        $data->unclosedCount = $unclosedCount;
        $data->products      = array_values($products);
        return $data;
    }

    /**
     * Get my projects.
     *
     * @access public
     * @return object
     */
    public function getDoingProjects()
    {
        $data = new stdClass();
        $doingProjects = $this->loadModel('project')->getOverviewList('byStatus', 'doing', 'id_desc');
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
        if(count($myProjects) < $maxCount and !empty($doingProjects))
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
            $project->delay    = (helper::diffDate(helper::today(), $project->end) > 0);
            $project->link     = common::hasPriv('project', 'view') ? helper::createLink('project', 'view', "projectID={$project->id}") : '';
        }

        $data->doingCount = count($myProjects);
        $data->projects   = array_values($myProjects);
        return $data;
    }

    /**
     * Get overview.
     *
     * @access public
     * @return object
     */
    public function getOverview()
    {
        $inAdminGroup = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group=t2.id')
            ->where('t1.account')->eq($this->app->user->account)
            ->andWhere('t2.role')->eq('admin')
            ->fetch();

        $overview = new stdclass();
        if(!empty($inAdminGroup) or $this->app->user->admin)
        {
            $allConsumed      = 0;
            $thisYearConsumed = 0;

            $projects         = $this->loadModel('project')->getOverviewList('byStatus', 'all', 'id_desc', 0);
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
            $overview->myTaskTotal  = (int)$this->dao->select('count(*) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
            $overview->myStoryTotal = (int)$this->dao->select('count(*) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
            $overview->myBugTotal   = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
        }

        return $overview;
    }

    /**
     * Get contribute
     *
     * @access public
     * @return object
     */
    public function getContribute()
    {
        $account    = $this->app->user->account;
        $contribute = new stdclass();

        $contribute->myTaskTotal       = (int)$this->dao->select('count(*) AS count')->from(TABLE_TASK)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
        $contribute->myStoryTotal      = (int)$this->dao->select('count(*) AS count')->from(TABLE_STORY)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->andWhere('type')->eq('story')->fetch('count');
        $contribute->myBugTotal        = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('assignedTo')->eq($this->app->user->account)->andWhere('deleted')->eq(0)->fetch('count');
        $contribute->docCreatedTotal   = (int)$this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('addedBy')->eq($this->app->user->account)->andWhere('deleted')->eq('0')->fetch('count');
        $contribute->ownerProductTotal = (int)$this->dao->select('count(*) AS count')->from(TABLE_PRODUCT)->where('PO')->eq($this->app->user->account)->andWhere('deleted')->eq('0')->fetch('count');

        $inTeam = $this->dao->select('root')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('account')->eq($this->app->user->account)->fetchPairs('root', 'root');
        $contribute->involvedProjectTotal = (int)$this->dao->select('count(*) AS count')->from(TABLE_PROJECT)
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
     * Get latest actions.
     *
     * @access public
     * @return array
     */
    public function getActions()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 50, 1);

        $actions = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager);
        $users   = $this->loadModel('user')->getList();

        $simplifyUsers = array();
        foreach($users as $user)
        {
            $simplifyUser = new stdclass();
            $simplifyUser->id       = $user->id;
            $simplifyUser->account  = $user->account;
            $simplifyUser->realname = $user->realname;
            $simplifyUser->avatar   = $user->avatar;
            $simplifyUsers[$user->account] = $simplifyUser;
        }

        $maxCount = 5;
        $actions  = $this->action->processDynamicForAPI($actions);
        $actions  = array_slice($actions, 0, $maxCount);

        return $actions;
    }

    /**
     * Get assigned by me objects.
     *
     * @param string $account
     * @param int    $limit
     * @param string $orderBy
     * @param int    $pager
     * @param int    $projectID
     * @param string $objectType
     * @access public
     * @return array
     */
    public function getAssignedByMe($account, $limit = 0, $pager = null, $orderBy = "id_desc", $objectType = '')
    {
        $module = $objectType == 'requirement' ? 'story' : $objectType;
        $this->loadModel($module);

        $objectIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('actor')->eq($account)
            ->andWhere('objectType')->eq($module)
            ->andWhere('action')->eq('assigned')
            ->fetchAll('objectID');
        if(empty($objectIDList)) return array();

        if($objectType == 'task')
        {
            $orderBy    = strpos($orderBy, 'pri_') !== false ? str_replace('pri_', 'priOrder_', $orderBy) : 't1.' . $orderBy;
            $objectList = $this->dao->select("t1.*, t2.name as executionName, t2.type as executionType, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.id')->in(array_keys($objectIDList))
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($objectType == 'requirement' or $objectType == 'story' or $objectType == 'bug')
        {
            $orderBy = (strpos($orderBy, 'priOrder') !== false or strpos($orderBy, 'severityOrder') !== false) ? $orderBy : "t1.$orderBy";
            $select  = strpos($orderBy, 'severity') !== false ? "t1.*,IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder" : "t1.*,IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";
            $objectList = $this->dao->select($select)->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on("t1.product = t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.id')->in(array_keys($objectIDList))
                ->beginIF($objectType == 'requirement' or $objectType == 'story')->andWhere('t1.type')->eq($objectType)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($objectType == 'risk' or $objectType == 'issue' or $objectType == 'nc')
        {
            $objectList = $this->dao->select('t1.*')->from($this->config->objectTables[$module])->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.id')->in(array_keys($objectIDList))
                ->orderBy('t1.' . $orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $objectList = $this->dao->select('*')->from($this->config->objectTables[$module])
                ->where('deleted')->eq(0)
                ->andWhere('id')->in(array_keys($objectIDList))
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        if($objectType == 'task')
        {
            if($objectList) return $this->loadModel('task')->processTasks($objectList);
            return $objectList;
        }

        if($objectType == 'bug')
        {
            $productList = array();
            foreach($objectList as $bug) $productList[$bug->product] = $bug->product;
            $productPairs = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productList)->fetchPairs('id');
            foreach($objectList as $bug) $bug->productName = zget($productPairs, $bug->product, '');
        }

        if($objectType == 'requirement' or $objectType == 'story')
        {
            $productList = array();
            foreach($objectList as $story) $productList[$story->product] = $story->product;
            $productPairs = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productList)->fetchPairs('id');
            foreach($objectList as $story) $story->productTitle = zget($productPairs, $story->product, '');

            $planList = array();
            foreach($objectList as $story) $planList[$story->plan] = $story->plan;
            $planPairs = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in($planList)->fetchPairs('id');
            foreach($objectList as $story) $story->planTitle = zget($planPairs, $story->plan, '');
        }
        return $objectList;
    }

    /**
     * Build case search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildTestCaseSearchForm($queryID, $actionURL, $type)
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $queryName = $type == 'contribute' ? 'contributeTestcase' : 'workTestcase';
        $this->app->loadConfig('testcase');
        $this->config->testcase->search['module']                      = $queryName;
        $this->config->testcase->search['queryID']                     = $queryID;
        $this->config->testcase->search['actionURL']                   = $actionURL;
        $this->config->testcase->search['params']['product']['values'] = array('' => '') + $products;
        $this->config->testcase->search['params']['lib']['values']     = array('' => '') + $this->loadModel('caselib')->getLibraries();

        unset($this->config->testcase->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Get testcases by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getTestcasesBySearch($queryID, $type, $orderBy, $pager)
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
            if($this->session->$queryName  == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myTestcaseQuery = $this->session->$queryName;
        $myTestcaseQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myTestcaseQuery);

        if($type == 'contribute')
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->alias('t1')
                ->where($myTestcaseQuery)
                ->andWhere('t1.openedBy')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll('id');
        }
        else
        {
            $cases = $this->dao->select('t1.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                ->where($myTestcaseQuery)
                ->andWhere('t2.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll('id');
        }
        return $cases;
    }

    /**
     * Build search form for task page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTaskSearchForm($queryID, $actionURL)
    {
        $rawMethod = $this->app->rawMethod;
        $this->loadModel('execution');

        $this->config->execution->search['module']    = $rawMethod . 'Task';
        $this->config->execution->search['actionURL'] = $actionURL;
        $this->config->execution->search['queryID']   = $queryID;

        if($rawMethod == 'work')
        {
            unset($this->config->execution->search['fields']['closedReason']);
            unset($this->config->execution->search['fields']['closedBy']);
            unset($this->config->execution->search['fields']['canceledBy']);
            unset($this->config->execution->search['fields']['closedDate']);
            unset($this->config->execution->search['fields']['canceledDate']);
        }

        $projects = $this->loadModel('project')->getPairsByProgram();
        $this->config->execution->search['params']['project']['values'] = $projects + array('all' => $this->lang->project->allProjects);

        $executions = $this->execution->getPairs();
        $this->config->execution->search['params']['execution']['values'] = $executions + array('all' => $this->lang->execution->allExecutions);

        $this->config->execution->search['params']['module']['values'] = $this->loadModel('tree')->getAllModulePairs();

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
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
    public function getTasksBySearch($account, $limit = 0, $pager = null, $orderBy = 'id_desc', $queryID = 0)
    {
        $moduleName = $this->app->rawMethod == 'work' ? 'workTask' : 'contributeTask';
        $queryName  = $moduleName . 'Query';
        $formName   = $moduleName . 'Form';

        $taskIDList = array();
        if($moduleName == 'contributeTask')
        {
            $tasksAssignedByMe = $this->getAssignedByMe($account, 0, '', $orderBy, 'task');
            foreach($tasksAssignedByMe as $taskID => $task)
            {
                $taskIDList[$taskID] = $taskID;
            }
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
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');
        }

        $query = $this->session->$queryName;

        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        $query = str_replace('t1.`project`', 't2.`project`', $query);

        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $tasks   = $this->dao->select("t1.*, t2.id as executionID, t2.name as executionName, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on("t1.project = t4.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t1.id = t5.task")
            ->where($query)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('(t2.status')->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)
            ->beginIF($moduleName == 'workTask')->andWhere("t1.assignedTo")->eq($account)
            ->beginIF($moduleName == 'contributeTask')
            ->andWhere('t1.openedBy', 1)->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.canceledBy')->eq($account)
            ->orWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t5.status')->eq("done")
            ->orWhere('t1.id')->in($taskIDList)
            ->markRight(1)
            ->fi()
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', false);

        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        if($tasks) return $this->loadModel('task')->processTasks($tasks);
        return array();
    }

    /**
     * Build search form for bug page of work.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildBugSearchForm($queryID, $actionURL)
    {
        $rawMethod = $this->app->rawMethod;
        $this->loadModel('bug');
        $this->app->loadConfig('bug');

        $products = $this->loadModel('product')->getPairs();

        $this->config->bug->search['module']    = $rawMethod . 'Bug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        if($rawMethod == 'work')
        {
            unset($this->config->bug->search['fields']['closedDate']);
            unset($this->config->bug->search['fields']['closedBy']);
        }

        if($this->config->systemMode == 'new') $this->config->bug->search['params']['project']['values'] = $this->loadModel('project')->getPairsByProgram() + array('all' => $this->lang->bug->allProject) + array('' => '');
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('execution')->getPairs();
        $this->config->bug->search['params']['product']['values']       = $products + array('' => '');
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs();
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getAllModulePairs();
        $this->config->bug->search['params']['severity']['values']      = array(0 => '') + $this->lang->bug->severityList;
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($products);
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /*
     * Build risk search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type risk|contribute
     * @access public
     * @return void
     */
    public function buildRiskSearchForm($queryID, $actionURL, $type)
    {
        $projects  = $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('model')->ne('kanban')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();
        $queryName = $type == 'contribute' ? 'contributeRisk' : 'workRisk';

        $this->app->loadConfig('risk');
        $this->config->risk->search['module']            = $queryName;
        $this->config->risk->search['actionURL']         = $actionURL;
        $this->config->risk->search['queryID']           = $queryID;

        $this->config->risk->search['params']['project']['values'] = array('') + $projects;

        if($this->config->systemMode == 'classic') unset($this->config->risk->search['fields']['project']);
        unset($this->config->risk->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->risk->search);
    }

    /**
     * Get risks by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getRisksBySearch($queryID, $type, $orderBy, $pager)
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
            if($this->session->$queryName == false) $this->session->set($queryName, ' 1 = 1');
        }

        $riskQuery = $this->session->$queryName;

        if($type == 'contribute')
        {
            $assignedByMe = $this->getAssignedByMe($this->app->user->account, '', '', $orderBy, 'risk');
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
     * Build Story search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildStorySearchForm($queryID, $actionURL, $type)
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $branchParam   = '';
        $queryName = $type == 'contribute' ? 'contributeStory' : 'workStory';
        $this->app->loadConfig('product');
        $this->config->product->search['module']                      = $queryName;
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['actionURL']                   = $actionURL;
        $this->config->product->search['params']['product']['values'] = array('' => '') + $products;
        $this->config->product->search['params']['plan']['values']    = array('' => '') + $this->loadModel('productplan')->getPairs($productIdList, $branchParam);

        unset($this->config->product->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Get stories by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getStoriesBySearch($queryID, $type, $orderBy, $pager)
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
            if($this->session->$queryName  == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myStoryQuery = $this->session->$queryName;
        $myStoryQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myStoryQuery);

        $storyIDList = array();
        if($type == 'contribute')
        {
            $storiesAssignedByMe = $this->getAssignedByMe($this->app->user->account, '', '', $orderBy, 'story');
            foreach($storiesAssignedByMe as $storyID => $story)
            {
                $storyIDList[$storyID] = $storyID;
            }

            $stories = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t4.title as planTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PLANSTORY)->alias('t3')->on('t1.id = t3.plan')
                ->leftJoin(TABLE_PRODUCTPLAN)->alias('t4')->on('t3.plan = t4.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t5')->on('t1.id = t5.story')
                ->where($myStoryQuery)
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t1.openedBy',1)->eq($this->app->user->account)
                ->orWhere('t5.reviewer')->eq($this->app->user->account)
                ->orWhere('t1.closedBy')->eq($this->app->user->account)
                ->orWhere('t1.id')->in($storyIDList)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        else
        {
            $stories = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t4.title as planTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PLANSTORY)->alias('t3')->on('t1.id = t3.plan')
                ->leftJoin(TABLE_PRODUCTPLAN)->alias('t4')->on('t3.plan = t4.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t5')->on('t1.id = t5.story')
                ->where($myStoryQuery)
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t1.assignedTo',1)->eq($this->app->user->account)
                ->orWhere('t5.reviewer')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        return $stories;
    }

    /**
     * Build Requirement search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildRequirementSearchForm($queryID, $actionURL, $type)
    {
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();

        $productIdList = array_keys($products);
        $branchParam   = '';
        $queryName = $type == 'contribute' ? 'contributeRequirement' : 'workRequirement';
        $this->app->loadConfig('product');
        $this->config->product->search['module']                      = $queryName;
        $this->config->product->search['queryID']                     = $queryID;
        $this->config->product->search['actionURL']                   = $actionURL;
        $this->config->product->search['params']['product']['values'] = array('' => '') + $products;
        $this->config->product->search['params']['plan']['values']    = array('' => '') + $this->loadModel('productplan')->getPairs($productIdList, $branchParam);

        $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
        $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
        $this->config->product->search['fields']['title'] = $this->lang->story->title;
        unset($this->config->product->search['fields']['plan']);
        unset($this->config->product->search['fields']['stage']);

        unset($this->config->product->search['fields']['module']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Build ticket search form.
     *
     * @param  string    $queryID
     * @param  string    $actionURL
     * @access public
     * @return mixed
     */
    public function buildTicketSearchForm($queryID, $actionURL)
    {
        $this->loadModel('ticket');
        $this->app->loadConfig('ticket');

        $this->config->ticket->search['module'] = 'workTicket';
        $this->config->ticket->search['queryID']   = $queryID;
        $this->config->ticket->search['actionURL'] = $actionURL;
        $this->config->ticket->search['params']['product']['values'] = array('' => '') + $this->loadModel('feedback')->getGrantProducts();
        $this->config->ticket->search['params']['module']['values']  = array('' => '') + $this->loadModel('tree')->getAllModulePairs();
        $grantProducts = $this->loadModel('feedback')->getGrantProducts();
        $productIDlist = array_keys($grantProducts);
        $this->config->ticket->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productIDlist);

        $this->loadModel('search')->setSearchParams($this->config->ticket->search);
    }

    /**
     * Get requirements by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getRequirementsBySearch($queryID, $type, $orderBy, $pager)
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
            if($this->session->$queryName  == false) $this->session->set($queryName, ' 1 = 1');
        }

        $myRequirementQuery = $this->session->$queryName;
        $myRequirementQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $myRequirementQuery);

        $requirementIDList = array();
        if($type == 'contribute')
        {
            $requirementsAssignedByMe = $this->getAssignedByMe($this->app->user->account, '', '', $orderBy, 'requirement');
            foreach($requirementsAssignedByMe as $requirementID => $requirement)
            {
                $requirementIDList[$requirementID] = $requirementID;
            }

            $requirements = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myRequirementQuery)
                ->andWhere('t1.type')->eq('requirement')
                ->andWhere('t1.openedBy',1)->eq($this->app->user->account)
                ->orWhere('t1.closedBy')->eq($this->app->user->account)
                ->orWhere('t3.reviewer')->eq($this->app->user->account)
                ->orWhere('t1.id')->in($requirementIDList)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        else
        {
            $requirements = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myRequirementQuery)
                ->andWhere('t1.type')->eq('requirement')
                ->andWhere('t1.assignedTo',1)->eq($this->app->user->account)
                ->orWhere('t3.reviewer')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        return $requirements;
    }

}
