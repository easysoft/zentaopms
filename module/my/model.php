<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    public function getAssignedByMe($account, $limit = 0, $pager = null, $orderBy = "id_desc", $projectID = 0, $objectType = '')
    {
        $module = $objectType == 'requirement' ? 'story' : $objectType;
        $this->loadModel($module);
        $objectList = $this->dao->select('DISTINCT t1.*')
            ->from($this->config->objectTables[$module])->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id = t2.objectID and t2.objectType='{$module}'")
            ->where('t2.actor')->eq($account)
            ->andWhere('t2.action')->eq('assigned')
            ->beginIF($objectType == 'requirement' or $objectType == 'story')->andWhere('t1.type')->eq($objectType)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        if($objectType == 'task')
        {
            $projectList = array();
            foreach($objectList as $task) $projectList[$task->project] = $task->project;
            $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectList)->fetchPairs('id');
            foreach($objectList as $task) $task->projectName = zget($projectPairs, $task->project);

            $executionList = array();
            foreach($objectList as $task) $executionList[$task->execution] = $task->execution;
            $executionPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($executionList)->fetchPairs('id');
            foreach($objectList as $task) $task->executionName = zget($executionPairs, $task->execution);

            if($objectList) return $this->loadModel('task')->processTasks($objectList);
            return array();
        }

        if($objectType == 'bug')
        {
            $productList = array();
            foreach($objectList as $bug) $productList[$bug->product] = $bug->product;
            $productPairs = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productList)->fetchPairs('id');
            foreach($objectList as $bug) $bug->productName = zget($productPairs, $bug->product);
        }
        if($objectType == 'requirement' or $objectType == 'story')
        {
            $productList = array();
            foreach($objectList as $story) $productList[$story->product] = $story->product;
            $productPairs = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productList)->fetchPairs('id');
            foreach($objectList as $story) $story->productTitle = zget($productPairs, $story->product);

            $planList = array();
            foreach($objectList as $story) $planList[$story->plan] = $story->plan;
            $planPairs = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in($planList)->fetchPairs('id');
            foreach($objectList as $story) $story->planTitle = zget($planPairs, $story->plan);
        }
        return $objectList;
    }
}
