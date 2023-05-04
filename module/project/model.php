<?php
declare(strict_types=1);
class projectModel extends model
{
    /**
     * 获取当前登录用户有权限查看的项目列表.
     * Get project list by current user.
     *
     * @param  string    $fields
     * @access public
     * @return array
     */
    public function getListByCurrentUser(string $fields = '*') :array
    {
        return $this->dao->select($fields)->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc,id_desc')
            ->fetchAll('id');
    }

    /**
     * Check the privilege.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function checkPriv($projectID)
    {
        if(empty($projectID)) return false;

        /* If is admin, return true. */
        if($this->app->user->admin) return true;
        return (strpos(",{$this->app->user->view->projects},", ",{$projectID},") !== false);
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    public function accessDenied()
    {
        if(defined('TUTORIAL')) return true;

        echo(js::alert($this->lang->project->accessDenied));
        $this->session->set('project', '');

        return print(js::locate(helper::createLink('project', 'index')));
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $project
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($project, $action)
    {
        $action = strtolower($action);

        if(empty($project)) return true;
        if(!isset($project->type)) return true;

        if($action == 'start')    return $project->status == 'wait' or $project->status == 'suspended';
        if($action == 'finish')   return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'close')    return $project->status != 'closed';
        if($action == 'suspend')  return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'activate') return $project->status == 'done' or $project->status == 'closed';
        if($action == 'whitelist') return $project->acl != 'open';
        if($action == 'group') return $project->model != 'kanban';

        return true;
    }

    /**
     * Save project state.
     *
     * @param  int    $projectID
     * @param  array  $projects
     * @access public
     * @return int
     */
    public function saveState($projectID = 0, $projects = array())
    {
        if(defined('TUTORIAL')) return $projectID;

        if($projectID == 0 and $this->cookie->lastProject) $projectID = $this->cookie->lastProject;
        if($projectID == 0 and (int)$this->session->project == 0) $projectID = (int)key($projects);
        if($projectID == 0) $projectID = (int)key($projects);

        $this->session->set('project', (int)$projectID, $this->app->tab);

        if(!isset($projects[$this->session->project]))
        {
            if($projectID and strpos(",{$this->app->user->view->projects},", ",{$this->session->project},") === false and !empty($projects))
            {
                /* Redirect old project to new project. */
                $newProjectID = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('project');
                if($newProjectID and strpos(",{$this->app->user->view->projects},", ",{$newProjectID},") !== false)
                {
                    $this->session->set('project', (int)$newProjectID, $this->app->tab);
                    return $this->session->project;
                }

                $this->session->set('project', (int)key($projects), $this->app->tab);
                $this->accessDenied();
            }
            else
            {
                $this->session->set('project', (int)key($projects), $this->app->tab);
            }
        }

        return $this->session->project;
    }

    /**
     * Get budget unit list.
     *
     * @access public
     * @return array
     */

    public function getBudgetUnitList()
    {
        $budgetUnitList = array();
        if($this->config->vision != 'lite')
        {
            foreach(explode(',', $this->config->project->unitList) as $unit) $budgetUnitList[$unit] = zget($this->lang->project->unitList, $unit, '');
        }

        return $budgetUnitList;
    }

    /**
     * Get Multiple linked products for project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMultiLinkedProducts($projectID)
    {
        $linkedProducts      = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
        $multiLinkedProducts = $this->dao->select('t3.id,t3.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.product')->in($linkedProducts)
            ->andWhere('t1.project')->ne($projectID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->fetchPairs('id', 'name');

        return $multiLinkedProducts;
    }

    /*
     * Get project swapper.
     *
     * @param  int     $projectID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher($projectID, $currentModule, $currentMethod)
    {
        if($currentModule == 'project' and $currentMethod == 'browse') return;

        $currentProjectName = $this->lang->project->common;
        if($projectID)
        {
            $currentProject     = $this->getById($projectID);
            $currentProjectName = $currentProject->name;
        }

        if($this->app->viewType == 'mhtml' and $projectID)
        {
            $output  = $this->lang->project->common . $this->lang->colon;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('project', '$projectID', '$currentModule', '$currentMethod', '')\">{$currentProjectName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('project', 'ajaxGetDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProjectName}'><span class='text'>{$currentProjectName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * 根据项目ID获取项目信息。
     * Get a project by id.
     *
     * @param  int    $projectID
     * @access public
     * @return object|false
     */
    public function getByID(int $projectID): object|false
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProject();

        $project = $this->projectTao->fetchProjectInfo($projectID);
        if(!$project) return false;

        $project = $this->loadModel('file')->replaceImgURL($project, 'desc');
        return $project;
    }

    /**
     * 通过影子产品ID获取一条项目记录。
     * Get a project by its shadow product.
     *
     * @param  int    $productID
     * @access public
     * @return object|false
     */
    public function getByShadowProduct(int $productID): object|false
    {
        return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->limit(1)
            ->fetch();
    }

    /**
     * 根据状态和和我参与的查询项目列表。
     * Get project list by status and with my participation.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @param  int       $pager
     * @param  int       $involved
     * @access public
     * @return array
     */
    public function getList($status = 'undone', $orderBy = 'order_desc', $pager = null, $involved = 0)
    {
        /* Init vars. */
        $projects = $this->projectTao->fetchProjectList($status, $orderBy, $involved, $pager);
        if(empty($projects)) return array();

        $projectIdList = array_keys($projects);
        $teams         = $this->projectTao->fetchTeamGroupByIdList($projectIdList);

        $estimates = $this->dao->select("t2.project as project, sum(estimate) as estimate")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t1.parent')->lt(1)
            ->andWhere('t2.project')->in($projectIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t2.project')
            ->fetchAll('project');

        $this->app->loadClass('pager', $static = true);
        foreach($projects as $projectID => $project)
        {
            $orderBy = $project->model == 'waterfall' ? 'id_asc' : 'id_desc';
            $pager   = $project->model == 'waterfall' ? null : new pager(0, 1, 1);
            $project->executions = $this->loadModel('execution')->getStatData($projectID, 'undone', 0, 0, false, '', $orderBy, $pager);
            $project->teamCount  = isset($teams[$projectID]) ? $teams[$projectID]->count : 0;
            $project->estimate   = isset($estimates[$projectID]) ? round($estimates[$projectID]->estimate, 2) : 0;
            $project->parentName = $project->parent ? $this->projectTao->getParentProgram($project->parent, $project->path, $project->grade) : '';
        }
        return $projects;
    }

    /**
     * Get project overview for block.
     *
     * @param  string     $queryType byId|byStatus
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $limit
     * @param  string     $excludedModel
     * @access public
     * @return array
     */
    public function getOverviewList($queryType = 'byStatus', $param = 'all', $orderBy = 'id_desc', $limit = 15, $excludedModel = '')
    {
        $queryType = strtolower($queryType);
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->beginIF($excludedModel)->andWhere('model')->ne($excludedModel)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($queryType == 'bystatus' and $param == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($queryType == 'bystatus' and $param != 'all' and $param != 'undone')->andWhere('status')->eq($param)->fi()
            ->beginIF($queryType == 'byid')->andWhere('id')->eq($param)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');

        if(empty($projects)) return array();
        $projectIdList = array_keys($projects);

        $teams = $this->dao->select('t1.root, count(t1.id) as teams')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectIdList)
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('root')->fetchPairs();

        $hours = $this->dao->select('t2.project, ROUND(SUM(t1.consumed), 1) AS consumed, ROUND(SUM(t1.estimate), 1) AS estimate')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->groupBy('t2.project')
            ->fetchAll('project');

        $storySummary = $this->getTotalStoriesByProject($projectIdList);
        $taskSummary  = $this->getTotalTaskByProject($projectIdList);
        $bugSummary   = $this->getTotalBugByProject($projectIdList);

        foreach($projects as $projectID => $project)
        {
            $project->consumed      = isset($hours[$projectID]) ? (float)$hours[$projectID]->consumed : 0;
            $project->estimate      = isset($hours[$projectID]) ? (float)$hours[$projectID]->estimate : 0;
            $project->teamCount     = isset($teams[$projectID]) ? $teams[$projectID] : 0;
            $project->leftBugs      = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->leftBugs : 0;
            $project->allBugs       = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->allBugs : 0;
            $project->doneBugs      = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->doneBugs : 0;
            $project->allStories    = isset($storySummary[$projectID]) ? $storySummary[$projectID]->allStories: 0;
            $project->doneStories   = isset($storySummary[$projectID]) ? $storySummary[$projectID]->doneStories: 0;
            $project->leftStories   = isset($storySummary[$projectID]) ? $storySummary[$projectID]->leftStories: 0;
            $project->leftTasks     = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->leftTasks : 0;
            $project->allTasks      = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->allTasks : 0;
            $project->waitTasks     = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->waitTasks : 0;
            $project->doingTasks    = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->doingTasks : 0;
            $project->rndDoneTasks  = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->doneTasks : 0;
            $project->liteDoneTasks = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->litedoneTasks : 0;

            if(is_float($project->consumed)) $project->consumed = round($project->consumed, 1);
            if(is_float($project->estimate)) $project->estimate = round($project->estimate, 1);
        }

        return $projects;
    }

    /**
     * Get the number of stories associated with the project.
     *
     * @param  array   $projectIdList
     * @access public
     * @return int
     */
    public function getTotalStoriesByProject($projectIdList = 0)
    {
        return $this->dao->select("t1.project, count(t2.id) as allStories, count(if(t2.status = 'active' or t2.status = 'changing', 1, null)) as leftStories, count(if(t2.status = 'closed' and t2.closedReason = 'done', 1, null)) as doneStories")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.project')->in($projectIdList)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('deleted')->eq('0')
            ->groupBy('project')
            ->fetchAll('project');
    }

    /**
     * Get associated bugs by project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getTotalBugByProject($projectIdList)
    {
        return $this->dao->select("project, count(id) as allBugs, count(if(status = 'active', 1, null)) as leftBugs, count(if(status = 'resolved', 1, null)) as doneBugs")->from(TABLE_BUG)
            ->where('project')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');
    }

    /**
     * Get associated tasks by project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getTotalTaskByProject($projectIdList)
    {
        return $this->dao->select("t1.project, count(t1.id) as allTasks, count(if(t1.status = 'wait', 1, null)) as waitTasks, count(if(t1.status = 'doing', 1, null)) as doingTasks, count(if(t1.status = 'done', 1, null)) as doneTasks, count(if(t1.status = 'wait' or t1.status = 'pause' or t1.status = 'cancel', 1, null)) as leftTasks, count(if(t1.status = 'done' or t1.status = 'closed', 1, null)) as litedoneTasks")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.project')->in($projectIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.project')
            ->fetchAll('project');
    }

    /**
     * Get waterfall project progress.
     *
     * @param  array  $projectIDList
     * @access public
     * @return int
     */
    public function getWaterfallProgress($projectIDList)
    {
        $projectList = $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.type')->in('stage')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.project')->in($projectIDList)
            ->andWhere('t2.model')->eq('waterfall')
            ->fetchGroup('project', 'id');

        $totalHour = $this->dao->select("t1.project, t1.execution, ROUND(SUM(if(t1.status !='closed' && t1.status !='cancel', `left`, 0)), 2) AS totalLeft, ROUND(SUM(consumed), 1) AS totalConsumed")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in(array_keys($projectList))
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->groupBy('t1.project,t1.execution')
            ->fetchGroup('project', 'execution');

        $progressList = array();
        foreach($projectList as $projectID => $stageList)
        {
            $progress = 0;
            $projectConsumed = 0;
            $projectLeft     = 0;
            foreach($stageList as $stageID => $stage)
            {
                if($stage->project != $projectID) continue;

                $stageTotalConsumed = isset($totalHour[$projectID][$stageID]) ? $totalHour[$projectID][$stageID]->totalConsumed : 0;
                $stageTotalLeft     = isset($totalHour[$projectID][$stageID]) ? round($totalHour[$projectID][$stageID]->totalLeft, 1) : 0;

                $projectConsumed += $stageTotalConsumed;
                $projectLeft     += $stageTotalLeft;

            }

            $progress += ($projectConsumed + $projectLeft) == 0 ? 0 : floor($projectConsumed / ($projectConsumed + $projectLeft) * 1000) / 1000 * 100;

            $progressList[$projectID] = $progress;
        }

        return is_numeric($projectIDList) ? (empty($progressList) ? 0 : $progressList[$projectIDList]) : $progressList;
    }

    /**
     * Get waterfall general PV and EV.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getWaterfallPVEVAC($projectID)
    {
        $executions = $this->dao->select('id,begin,end,realEnd,status')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('vision')->eq($this->config->vision)->andWhere('project')->eq($projectID)->fetchAll('id');
        $stmt       = $this->dao->select('id,status,estimate,consumed,`left`,closedReason')->from(TABLE_TASK)->where('execution')->in(array_keys($executions))->andWhere("parent")->ge(0)->andWhere("deleted")->eq(0)->andWhere('status')->ne('cancel')->query();

        $PV   = 0;
        $EV   = 0;
        $left = 0;
        while($task = $stmt->fetch())
        {
            $PV   += $task->estimate;
            $left += $task->left;
            if($task->status == 'done' or $task->closedReason == 'done')
            {
                $EV += $task->estimate;
            }
            else
            {
                $task->progress = 0;
                if(($task->consumed + $task->left) > 0) $task->progress = round($task->consumed / ($task->consumed + $task->left) * 100, 2);
                $EV += round($task->estimate * $task->progress / 100, 2);
            }
        }

        $AC = $this->dao->select('SUM(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->fetch('consumed');
        if(is_null($AC)) $AC = 0;

        return array('PV' => sprintf("%.2f", $PV), 'EV' => sprintf("%.2f", $EV), 'AC' => sprintf("%.2f", $AC), 'left' => sprintf("%.2f", $left));
    }

    /**
     * Get project workhour info.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getWorkhour($projectID)
    {
        $total = $this->dao->select('ROUND(SUM(estimate), 1) AS totalEstimate, ROUND(SUM(`left`), 2) AS totalLeft')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->fetch();

        $totalConsumed = $this->dao->select('ROUND(SUM(consumed), 1) AS totalConsumed')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->fetch('totalConsumed');

        $closedTotalLeft = $this->dao->select('ROUND(SUM(`left`), 2) AS totalLeft')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t1.status')->in('closed,cancel')
            ->fetch('totalLeft');

        $workhour = new stdclass();
        $workhour->totalHours    = $this->dao->select('sum(t1.days * t1.hours) AS totalHours')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root=t2.id')
            ->where('t2.id')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('project')
            ->fetch('totalHours');
        $workhour->totalEstimate = $total->totalEstimate;
        $workhour->totalConsumed = $totalConsumed;
        $workhour->totalLeft     = round($total->totalLeft - $closedTotalLeft, 1);

        return $workhour;
    }

    /**
     * Get projects consumed info.
     *
     * @param  array    $projectID
     * @param  string   $time
     * @access public
     * @return array
     */
    public function getProjectsConsumed($projectIdList, $time = '')
    {
        $projects = array();

        $totalConsumeds = $this->dao->select('t2.project,ROUND(SUM(t1.consumed), 1) AS totalConsumed')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on("t1.objectID=t2.id and t1.objectType = 'task'")
            ->where('t2.project')->in($projectIdList)
            ->beginIF($time == 'THIS_YEAR')->andWhere('LEFT(t1.`date`, 4)')->eq(date('Y'))->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.parent')->lt(1)
            ->groupBy('t2.project')
            ->fetchAll('project');

        foreach($projectIdList as $projectID)
        {
            $project = new stdClass();
            $project->totalConsumed = isset($totalConsumeds[$projectID]->totalConsumed) ? $totalConsumeds[$projectID]->totalConsumed : 0;
            $projects[$projectID]   = $project;
        }

        return $projects;
    }


    /**
     * Create the link from module,method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getProjectLink($module, $method, $projectID)
    {
        $link    = helper::createLink('project', 'index', "projectID=%s");
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        if(empty($project->multiple)) return $link;

        if(in_array($module, array('testreport', 'testcase')))
            return helper::createLink('project', $module, "projectID=%s");

        if($module == 'projectstory' && in_array($method, array('story', 'linkstory', 'track')))
            return helper::createLink($module, $method, "projectID=%s");

        if($module == 'project') $this->buildLinkForProject($module, $method);
        if(strpos(',project,product,projectstory,story,bug,doc,testcase,testtask,testreport,repo,build,projectrelease,stakeholder,issue,risk,meeting,report,measrecord,', ',' . $module . ',') !== false)
        {
            if($module == 'product' and $method == 'showerrornone')
            {
                $link = helper::createLink('projectstory', 'story', "projectID=%s");
            }
            elseif($module == 'projectstory' and $method == 'story')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'projectstory' and $method == 'linkstory')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'projectstory' and $method = 'track')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'bug')
            {
                if($method == 'create')
                {
                    $link = helper::createLink($module, $method, "productID=0&branch=0&extras=projectID=%s");
                }
                elseif($method == 'edit')
                {
                    $link = helper::createLink('project', 'bug', "projectID=%s");
                }
            }
            elseif($module == 'story')
            {
                if($method == 'change' or $method == 'create')
                {
                    $link = helper::createLink('projectstory', 'story', "projectID=%s");
                }
                elseif($method == 'zerocase')
                {
                    $link = helper::createLink('project', 'testcase', "projectID=%s");
                }
            }
            elseif($module == 'testcase')
            {
                $link = helper::createLink('project', 'testcase', "projectID=%s");
            }
            elseif($module == 'testtask')
            {
                if($method == 'browseunits')
                {
                    $link = helper::createLink('project', 'testcase', "projectID=%s");
                }
                else
                {
                    $link = helper::createLink('project', 'testtask', "projectID=%s");
                }
            }
            elseif($module == 'testreport')
            {
                $link = helper::createLink('project', 'testreport', "projectID=%s");
            }
            elseif($module == 'repo')
            {
                $link = helper::createLink($module, 'browse', "repoID=&branchID=&objectID=%s") . '#app=project';
            }
            elseif($module == 'doc' or $module == 'api')
            {
                $link = helper::createLink($module, 'projectSpace', "objectID=%s") . '#app=project';
            }
            elseif($module == 'build')
            {
                if($method == 'create')
                {
                    $link = helper::createLink($module, $method, "executionID=&productID=&projectID=%s") . '#app=project';
                }
                else
                {
                    $fromModule = $this->app->tab == 'project' ? 'projectbuild' : 'project';
                    $fromMethod = $this->app->tab == 'project' ? 'browse' : 'build';
                    $link = helper::createLink($fromModule, $fromMethod, "projectID=%s");
                }
            }
            elseif($module == 'projectrelease')
            {
                if($method == 'create')
                {
                    $link = helper::createLink($module, $method, "projectID=%s");
                }
                else
                {
                    $link = helper::createLink('projectrelease', 'browse', "projectID=%s");
                }
            }
            elseif($module == 'stakeholder')
            {
                if($method == 'create')
                {
                    $link = helper::createLink($module, $method, "projectID=%s");
                }
                else
                {
                    $link = helper::createLink($module, 'browse', "projectID=%s");
                }
            }
            elseif(strpos("issue,risk,meeting,report,measrecord", $module) !== false)
            {
                if($method == 'projectsummary')
                {
                    $link = helper::createLink($module, $method, "projectID=%s") . '#app=project';
                }
                else
                {
                    $link = helper::createLink($module, 'browse', "projectID=%s");
                }
            }
        }

        if(in_array($module, $this->config->waterfallModules))
        {
            $link = helper::createLink($module, 'browse', "projectID=%s");
            if($module == 'reviewissue')
            {
                $link = helper::createLink($module, 'issue', "projectID=%s");
            }
            elseif($module == 'cm' and $method = 'report')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'weekly' and $method = 'index')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'milestone' and $method = 'index')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'workestimation' and $method = 'index')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'durationestimation' and $method = 'index')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'budget' and $method = 'summary')
            {
                $link = helper::createLink($module, $method, "projectID=%s");
            }
            elseif($module == 'programplan')
            {
                $link = helper::createLink('project', 'execution', "type=all&projectID=%s");
            }
        }

        return $link;
    }

    /**
     * Get project stat data .
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getStatData($projectID)
    {
        $executions = $this->loadModel('execution')->getPairs($projectID);
        $storyTypeCount = $this->dao->select('count(t2.story) as storyCount,t1.type')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t2.project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->groupBy('t1.type')
            ->fetchPairs('type', 'storyCount');
        $storyCount       = isset($storyTypeCount['story']) ? $storyTypeCount['story'] : 0;
        $requirementCount = isset($storyTypeCount['requirement']) ? $storyTypeCount['requirement'] : 0;

        $taskCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('execution')->in(array_keys($executions))->andWhere('deleted')->eq(0)->fetch('taskCount');
        $bugCount  = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)->where('project')->in($projectID)->andWhere('deleted')->eq(0)->fetch('bugCount');

        $statusPairs   = $this->dao->select('status,count(id) as count')->from(TABLE_TASK)->where('execution')->in(array_keys($executions))->andWhere('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');
        $finishedCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('execution')->in(array_keys($executions))->andWhere('finishedBy')->ne('')->andWhere('deleted')->eq(0)->fetch('taskCount');
        $delayedCount  = $this->dao->select('count(id) as count')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deadline')->notZeroDate()
            ->andWhere('deadline')->lt(helper::today())
            ->andWhere('status')->in('wait,doing')
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        $statData = new stdclass();
        $statData->storyCount       = $storyCount;
        $statData->requirementCount = $requirementCount;
        $statData->taskCount        = $taskCount;
        $statData->bugCount         = $bugCount;
        $statData->waitCount        = zget($statusPairs, 'wait', 0);
        $statData->doingCount       = zget($statusPairs, 'doing', 0);
        $statData->finishedCount    = $finishedCount;
        $statData->delayedCount     = $delayedCount;

        return $statData;
    }

    /**
     * Get project pairs.
     *
     * @access public
     * @return object
     */
    public function getPairs()
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchPairs();
    }

    /**
     * Get project pairs by programID.
     *
     * @param  int          $programID
     * @param  status       $status    all|wait|doing|suspended|closed|noclosed
     * @param  bool         $isQueryAll
     * @param  string       $orderBy
     * @param  string       $excludedModel
     * @param  string|array $model
     * @param  string       $param multiple|product
     * @access public
     * @return object
     */
    public function getPairsByProgram($programID = '', $status = 'all', $isQueryAll = false, $orderBy = 'order_asc', $excludedModel = '', $model = '', $param = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectPairs();
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!empty($programID))->andWhere('path')->like("%,$programID,%")->fi()
            ->beginIF($programID === 0)->andWhere('parent')->eq(0)->fi()
            ->beginIF($status != 'all' and $status != 'noclosed')->andWhere('status')->eq($status)->fi()
            ->beginIF($excludedModel)->andWhere('model')->ne($excludedModel)->fi()
            ->beginIF($model)->andWhere('model')->in($model)->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('multiple')->eq(1)->fi()
            ->beginIF(strpos($param, 'product') !== false)->andWhere('hasProduct')->eq(1)->fi()
            ->beginIF($status == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin and !$isQueryAll)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * Get all the projects under the program set to which an project belongs.
     *
     * @param object $project
     * @access public
     * @return void
     */
    public function getBrotherProjects($project)
    {
        if($project->parent == 0) return array($project->id => $project->id);

        $projectIds    = array_filter(explode(',', $project->path));
        $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('id')->in($projectIds)
            ->andWhere('`type`')->eq('program')
            ->orderBy('grade desc')
            ->fetch();

        $projects = $this->dao->select('id')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('path')->like("{$parentProgram->path}%")
            ->fetchPairs('id');
        return $projects;
    }

    /**
     * Get project by id list.
     *
     * @param  array  $projectIdList
     * @param  string $mode all
     * @access public
     * @return object
     */
    public function getByIdList($projectIdList = array(), $mode = '')
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('id')->in($projectIdList)
            ->beginIF(!$this->app->user->admin and $mode != 'all')->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchAll('id');
    }

    /**
     * Get project pairs by id list.
     *
     * @param  array  $projectIdList
     * @param  string $model
     * @param  string $param
     * @access public
     * @return array
     */
    public function getPairsByIdList($projectIdList = array(), $model = '', $param = '')
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF($projectIdList)->andWhere('id')->in($projectIdList)->fi()
            ->beginIF(!$this->app->user->admin and $model != 'all')->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($model != 'all' and !empty($model))->andWhere('model')->in($model)->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->fetchPairs('id', 'name');
    }

    /**
     * Get branches by project id.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getBranchesByProject($projectID)
    {
        return $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->fetchGroup('product', 'branch');
    }

    /**
     * Get branch group by project id.
     *
     * @param  int          $projectID
     * @param  array|string $productIdList
     * @access public
     * @return array
     */
    public function getBranchGroupByProject($projectID, $productIdList)
    {
        return $this->dao->select('t1.product as productID, t1.branch as branchID, t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.project')->eq($projectID)
            ->fetchGroup('productID', 'branchID');
    }

    /**
     * Get No product project|execution List.
     *
     * @access public
     * @return array
     */
    public function getNoProductList()
    {
        return $this->dao->select('t1.product, t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t2.hasProduct')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get project and execution pairs.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutionPairs()
    {
        return $this->dao->select('project, id')->from(TABLE_PROJECT)->where('multiple')->eq('0')->andWhere('deleted')->eq('0')->fetchPairs();
    }

    /**
     * Process the project privs according to the project model.
     *
     * @param  string $model    sprint | waterfall | noSprint
     * @access public
     * @return object
     */
    public function processProjectPrivs($model = 'waterfall')
    {
        if($model == 'noSprint') $this->config->project->includedPriv = $this->config->project->noSprintPriv;

        $this->app->loadLang('group');

        $privs    = new stdclass();
        $resource = $this->lang->resource;
        foreach($resource as $module => $methods)
        {
            if(!$methods) continue;
            if(!in_array($module, $this->config->programPriv->$model)) continue;

            foreach($methods as $method => $label)
            {
                if(isset($this->config->project->includedPriv[$module]) and !in_array($method, $this->config->project->includedPriv[$module])) continue;

                if(!isset($privs->$module)) $privs->$module = new stdclass();
                $privs->$module->$method = $label;
            }
        }

        return $privs;
    }

    /*
     * Build search form.
     *
     * @param int     $queryID
     * @param string  $actionURL
     *
     * @return 0
     * */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->project->search['queryID']   = $queryID;
        $this->config->project->search['actionURL'] = $actionURL;

        $statusList = $this->lang->project->statusList;
        unset($statusList['delay']);
        $this->config->project->search['params']['status']['values'] = $statusList;

        $programPairs  = array(0 => '');
        $programPairs += $this->loadModel('program')->getPairs();
        $this->config->project->search['params']['parent']['values'] = $programPairs;

        if(!isset($this->config->setCode) or $this->config->setCode == 0) unset($this->config->project->search['fields']['code'], $this->config->project->search['params']['code']);
        if($this->config->systemMode == 'light') unset($this->config->project->search['fields']['parent'], $this->config->project->search['params']['parent']);

        $this->loadModel('search')->setSearchParams($this->config->project->search);
    }

    /**
     * Build the query.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function buildMenuQuery($projectID = 0)
    {
        $path    = '';
        $project = $this->projectTao->fetchProjectInfo($projectID);
        if($project) $path = $project->path;

        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('program')->fi()
            ->andWhere('status')->ne('closed')
            ->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->beginIF($projectID > 0)->andWhere('path')->like($path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Build project build search form.
     *
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type project|execution
     * @access public
     * @return void
     */
    public function buildProjectBuildSearchForm($products, $queryID, $actionURL, $type = 'project')
    {
        $this->loadModel('build');

        /* Set search param. */
        if($type == 'execution') $this->config->build->search['module'] = 'executionBuild';
        if($type == 'project') $this->config->build->search['module'] = 'projectBuild';
        $this->config->build->search['actionURL'] = $actionURL;
        $this->config->build->search['queryID']   = $queryID;
        $this->config->build->search['params']['product']['values'] = $products;

        $this->loadModel('search')->setSearchParams($this->config->build->search);
    }

    /**
     * Get project pairs by model and project.
     *
     * @param  string           $model all|scrum|waterfall|kanban
     * @param  int              $programID
     * @param  string           $param noclosed
     * @param  string|int|array $append
     * @access public
     * @return array
     */
    public function getPairsByModel($model = 'all', $programID = 0, $param = '', $append = '', $orderBy = 'order_asc')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectPairs();

        if($model == 'agileplus')     $model = array('scrum', 'agileplus');
        if($model == 'waterfallplus') $model = array('waterfall', 'waterfallplus');

        $projects = $this->dao->select('id, name, path')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq('0')
            ->beginIF($programID)->andWhere('parent')->eq($programID)->fi()
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->beginIF($model != 'all')->andWhere('model')->in($model)->fi()
            ->beginIF(strpos($param, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF(!empty($append))->orWhere('id')->in($append)->fi()
            ->orderBy($orderBy)
            ->fetchAll();

        $programIdList  = array();
        $projectProgram = array();
        foreach($projects as $project)
        {
            list($programID) = explode(',', trim($project->path, ','));
            $programIdList[$programID]    = $programID;
            $projectProgram[$project->id] = $programID;
        }

        $programs = $this->dao->select('id, name')->from(TABLE_PROGRAM)->where('id')->in($programIdList)->orderBy($orderBy)->fetchPairs('id', 'name');

        /* Sort by project order in the program list. */
        $allProjects = array();
        foreach($programs as $programID => $program) $allProjects[$programID] = array();
        foreach($projects as $project)
        {
            $programID = zget($projectProgram, $project->id, '');
            $allProjects[$programID][] = $project;
        }

        $pairs = array();
        foreach($allProjects as $programID => $projects)
        {
            foreach($projects as $project)
            {
                $projectName = $project->name;

                if($this->config->systemMode == 'ALM')
                {
                    $programID = zget($projectProgram, $project->id, '');
                    if($programID != $project->id) $projectName = zget($programs, $programID, '') . ' / ' . $projectName;
                }

                $pairs[$project->id] = $projectName;
            }
        }

        return $pairs;
    }

    /**
     * Get stories by project id.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getStoriesByProject($projectID = 0)
    {
        return $this->dao->select("t2.product, t2.branch, GROUP_CONCAT(t2.story) as storyIDList")->from(TABLE_STORY)->alias('t1')
           ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
           ->where('t1.deleted')->eq(0)
           ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
           ->groupBy('t2.product, t2.branch')
           ->fetchGroup('product', 'branch');
    }

    /**
     * Get the tree menu of project.
     *
     * @param  int       $projectID
     * @param  string    $userFunc
     * @param  int       $param
     * @access public
     * @return string
     */
    public function getTreeMenu($projectID = 0, $userFunc = '', $param = 0)
    {
        $projectMenu = array();
        $stmt        = $this->dbh->query($this->buildMenuQuery($projectID));

        while($project = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $project, $param);

            if(isset($projectMenu[$project->id]) and !empty($projectMenu[$project->id]))
            {
                if(!isset($projectMenu[$project->parent])) $projectMenu[$project->parent] = '';
                $projectMenu[$project->parent] .= "<li>$linkHtml";
                $projectMenu[$project->parent] .= "<ul>".$projectMenu[$project->id]."</ul>\n";
            }
            else
            {
                if(isset($projectMenu[$project->parent]) and !empty($projectMenu[$project->parent]))
                {
                    $projectMenu[$project->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $projectMenu[$project->parent] = "<li>$linkHtml\n";
                }
            }
            $projectMenu[$project->parent] .= "</li>\n";
        }

        krsort($projectMenu);
        $projectMenu = array_pop($projectMenu);
        $lastMenu    = "<ul class='tree' data-ride='tree' id='projectTree' data-name='tree-project'>{$projectMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Get the program tree of project.
     * Copied from getTreeMenu().
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getProgramTree($projectID = 0)
    {
        $programs = array();
        $stmt     = $this->dbh->query($this->buildMenuQuery($projectID));

        while($project = $stmt->fetch())
        {
            $prog = new stdClass();
            $prog->id = $project->id;
            $prog->name = $project->name;
            $prog->parent = $project->parent;

            $programs[] = $prog;
        }

        return $programs;
    }

    /**
     * Create the manage link.
     *
     * @param  object    $project
     * @access public
     * @return string
     */
    public function createManageLink($project)
    {
        $link = $project->type == 'program' ? helper::createLink('project', 'browse', "projectID={$project->id}&status=all") : helper::createLink('project', 'index', "projectID={$project->id}", '', '', $project->id);

        if($this->app->rawModule == 'execution') $link = helper::createLink('execution', 'all', "status=all&projectID={$project->id}");

        return html::a($link, $project->name, '_self', "id=program{$project->id} title='{$project->name}' class='text-ellipsis'");
    }

    /**
     * Create a project.
     *
     * @access public
     * @return int|bool
     */
    public function create(object $project, object $postData): int|bool
    {
        $requiredFields = $this->config->project->create->requiredFields;
        if($postData->rawdata->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $this->lang->error->unique = $this->lang->error->repeat;
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->create['id'], $postData->rawdata->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF(!empty($project->name), 'name', 'unique', "`type`='project' and `parent` = $project->parent and `model` = '{$project->model}' and `deleted` = '0'")
            ->checkIF(!empty($project->code), 'code', 'unique', "`type`='project' and `model` = '{$project->model}' and `deleted` = '0'")
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        $projectID = (int)$this->dao->lastInsertId();

        /* Manage team members. */
        $teamMembers = $this->projectTao->setProjectTeam($projectID, $project, $postData);
        $this->loadModel('execution')->addProjectMembers($projectID, $teamMembers);

        /* Add project whitelist. */
        $whitelist = explode(',', $project->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);

        $program = $project->parent ? $this->getByID((int)$project->parent) : new stdclass();
        $this->projectTao->createDocLib($projectID, $project, $postData, $program);

        if($project->hasProduct) $this->updateProducts($projectID);

        /* If $_POST has product name, create it. */
        $linkedProductsCount = $this->projectTao->getLinkedProductsCount($project, $postData->rawdata);
        if(!$project->hasProduct or isset($postData->rawdata->newProduct) or (!$project->parent and empty($linkedProductsCount))) $this->createProduct($projectID, $project, $postData, $program);

        /* Save order. */
        $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec();
        $this->file->updateObjectID($postData->rawdata->uid, $projectID, 'project');
        $this->loadModel('program')->setTreePath($projectID);

        /* Add project admin. */
        $this->projectTao->addProjectAdmin($projectID);

        if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

        if(empty($project->multiple) and $project->model != 'waterfall' and $project->model != 'waterfallplus') $this->loadModel('execution')->createDefaultSprint($projectID);

        return $projectID;
    }

    /**
     * Check branch and product valid by project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function checkBranchAndProductValid(int $projectID, object $project): bool
    {
        $topProgramID     = $this->loadModel('program')->getTopByID($project->parent);
        $multipleProducts = $this->loadModel('product')->getMultiBranchPairs($topProgramID);
        foreach($project->products as $index => $productID)
        {
            if(isset($multipleProducts[$productID]) and empty($project->branch[$index]))
            {
                dao::$errors[] = $this->lang->project->emptyBranch;
                return false;
            }
        }
        return true;
    }

    /**
     * Check dates valid by project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function checkDatesValidByProject($projectID, $project): bool
    {
        $minExecutionBegin = $this->dao->select('`begin` as minBegin')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->orderBy('begin_asc')->fetch();
        $maxExecutionEnd   = $this->dao->select('`end` as maxEnd')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->orderBy('end_desc')->fetch();
        if($minExecutionBegin and $project->begin > $minExecutionBegin->minBegin) dao::$errors['begin'] = sprintf($this->lang->project->begigLetterExecution, $minExecutionBegin->minBegin);
        if($maxExecutionEnd and $project->end < $maxExecutionEnd->maxEnd) dao::$errors['end'] = sprintf($this->lang->project->endGreateExecution, $maxExecutionEnd->maxEnd);
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Update project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function update($projectID, $project)
    {
        $oldProject        = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $linkedProducts    = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();

        if(!isset($project->parent)) $project->parent = $oldProject->parent;

        $executionsCount = $this->dao->select('COUNT(*) as count')->from(TABLE_PROJECT)->where('project')->eq($project->id)->andWhere('deleted')->eq('0')->fetch('count');

        if($project->product && !$this->checkBranchAndProductValid($projectID, $project)) return false;

        /* Judge products not empty. */
        $linkedProductsCount = 0;
        foreach($_POST['products'] as $product)
        {
            if(!empty($product)) $linkedProductsCount++;
        }

        if(empty($linkedProductsCount))
        {
            dao::$errors[] = $this->lang->project->errorNoProducts;
            return false;
        }

        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($project->end, $project->begin) + 1;
        if(isset($project->days) and $project->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->edit['id'], $this->post->uid);

        $requiredFields = $this->config->project->edit->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $this->lang->error->unique = $this->lang->error->repeat;

        if(!empty($executionsCount) and $oldProject->multiple) $this->checkDatesValidByProject($projectID ,$project);
        $this->projectTao->doUpdate($projectID, $project);
        if(dao::isError()) return false;

        if(!$oldProject->hasProduct and ($oldProject->name != $project->name or $oldProject->parent != $project->parent or $oldProject->acl != $project->acl)) $this->updateShadowProduct($project);

        /* Get team and language item. */
        $this->loadModel('user');
        $team    = $this->user->getTeamMemberPairs($projectID, 'project');
        $members = isset($_POST['teamMembers']) ? $_POST['teamMembers'] : array();
        array_push($members, $project->PM);
        $members = array_unique($members);
        $roles   = $this->user->getUserRoles(array_values($members));

        $teamMembers = array();
        foreach($members as $account)
        {
            if(empty($account) or isset($team[$account])) continue;

            $member = new stdclass();
            $member->root    = (int)$projectID;
            $member->account = $account;
            $member->join    = helper::today();
            $member->role    = zget($roles, $account, '');
            $member->days    = zget($project, 'days', 0);
            $member->type    = 'project';
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();

            $teamMembers[$account] = $member;
        }
        if($oldProject->model == 'kanban')
        {
            $this->dao->delete()->from(TABLE_TEAM)
                ->where('root')->eq((int)$projectID)
                ->andWhere('type')->eq('project')
                ->andWhere('account')->in(array_keys($team))
                ->andWhere('account')->notin(array_values($members))
                ->andWhere('account')->ne($oldProject->openedBy)
                ->exec();
        }
        if(!empty($projectID) and !empty($teamMembers)) $this->loadModel('execution')->addProjectMembers($projectID, $teamMembers);

        if(!dao::isError())
        {
            $this->updateProducts($projectID, $_POST['products']);
            if(empty($oldProject->division))
            {
                $executions = $this->loadModel('execution')->getPairs($projectID);
                foreach(array_keys($executions) as $executionID) $this->execution->updateProducts($executionID);
            }

            $this->file->updateObjectID($this->post->uid, $projectID, 'project');

            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if(!$oldProject->hasProduct) $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', current($linkedProducts));
            if($project->acl != 'open')
            {
                $this->loadModel('user')->updateUserView($projectID, 'project');

                $executions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->fetchPairs('id', 'id');
                if($executions) $this->user->updateUserView($executions, 'sprint');
            }

            if($oldProject->parent != $project->parent) $this->loadModel('program')->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);

            /* Fix whitelist changes. */
            $oldWhitelist = explode(',', $oldProject->whitelist) and $oldWhitelist = array_filter($oldWhitelist);
            $newWhitelist = explode(',', $project->whitelist) and $newWhitelist = array_filter($newWhitelist);
            if(count($oldWhitelist) == count($newWhitelist) and count(array_diff($oldWhitelist, $newWhitelist)) == 0) unset($project->whitelist);

            /* Add linkedproducts changes. */
            $oldProject->linkedProducts = implode(',', $linkedProducts);
            $project->linkedProducts    = implode(',', $_POST['products']);

            $unlinkedProducts = array_diff($linkedProducts, $_POST['products']);
            if(!empty($unlinkedProducts))
            {
                $products = $this->dao->select('name')->from(TABLE_PRODUCT)->where('id')->in($unlinkedProducts)->fetchPairs();
                $this->loadModel('action')->create('project', $projectID, 'unlinkproduct', '', implode(',', $products));
            }

            /* Activate or close the shadow product of the project. */
            if(!$oldProject->hasProduct && $oldProject->status != $project->status && strpos('doing,closed', $project->status) !== false)
            {
                $productID = $this->loadModel('product')->getProductIDByProject($projectID);
                if($project->status == 'doing') $this->product->activate($productID);
                if($project->status == 'closed') $this->product->close($productID);
            }

            if(empty($oldProject->multiple) and $oldProject->model != 'waterfall') $this->loadModel('execution')->syncNoMultipleSprint($projectID);

            return common::createChanges($oldProject, $project);
        }
    }

    /**
     * Batch update projects.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $projects    = array();
        $allChanges  = array();
        $data        = fixer::input('post')->get();
        $oldProjects = $this->getByIdList($this->post->projectIdList);
        $nameList    = array();

        $extendFields = $this->getFlowExtendFields();
        foreach($data->projectIdList as $projectID)
        {
            $projectID   = (int)$projectID;
            $projectName = $data->names[$projectID];
            if(isset($data->codes)) $projectCode = $data->codes[$projectID];

            $projects[$projectID] = new stdClass();
            if(isset($data->parents[$projectID])) $projects[$projectID]->parent = $data->parents[$projectID];
            $projects[$projectID]->id             = $projectID;
            $projects[$projectID]->name           = $projectName;
            $projects[$projectID]->model          = $oldProjects[$projectID]->model;
            $projects[$projectID]->PM             = $data->PMs[$projectID];
            $projects[$projectID]->begin          = $data->begins[$projectID];
            $projects[$projectID]->end            = $data->ends[$projectID] == $this->lang->project->longTime ? LONG_TIME : $data->ends[$projectID];
            $projects[$projectID]->days           = $data->ends[$projectID] == $this->lang->project->longTime ? 0 : $data->dayses[$projectID];
            $projects[$projectID]->acl            = $data->acls[$projectID];
            $projects[$projectID]->lastEditedBy   = $this->app->user->account;
            $projects[$projectID]->lastEditedDate = helper::now();

            if(isset($data->codes)) $projects[$projectID]->code = $projectCode;

            foreach($extendFields as $extendField)
            {
                $projects[$projectID]->{$extendField->field} = $this->post->{$extendField->field}[$projectID];
                if(is_array($projects[$projectID]->{$extendField->field})) $projects[$projectID]->{$extendField->field} = join(',', $projects[$projectID]->{$extendField->field});

                $projects[$projectID]->{$extendField->field} = htmlSpecialString($projects[$projectID]->{$extendField->field});
            }
        }
        if(dao::isError()) return false;

        $this->loadModel('execution');
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach($projects as $projectID => $project)
        {
            $oldProject = $oldProjects[$projectID];
            $parentID   = !isset($project->parent) ? $oldProject->parent : $project->parent;

            $this->dao->update(TABLE_PROJECT)->data($project)
                ->autoCheck($skipFields = 'begin,end')
                ->batchCheck($this->config->project->edit->requiredFields, 'notempty')
                ->checkIF($project->begin != '', 'begin', 'date')
                ->checkIF($project->end != '', 'end', 'date')
                ->checkIF($project->end != '', 'end', 'gt', $project->begin)
                ->checkIF(!empty($project->name), 'name', 'unique', "id != $projectID and `type`='project' and `parent` = $parentID and `model` = '{$project->model}' and `deleted` = '0'")
                ->checkIF(!empty($project->code), 'code', 'unique', "id != $projectID and `type`='project' and `model` = '{$project->model}' and `deleted` = '0'")
                ->checkFlow()
                ->where('id')->eq($projectID)
                ->exec();

            if(dao::isError())
            {
                $errors = dao::getError();
                foreach($errors as $key => $error) dao::$errors[$key][0] = 'ID' . $projectID . $error[0];

                return false;
            }

            if(!dao::isError())
            {
                if(!$oldProject->hasProduct and ($oldProject->name != $project->name or $oldProject->parent != $project->parent or $oldProject->acl != $project->acl)) $this->updateShadowProduct($project);

                if(isset($project->parent))
                {
                    $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                    $this->updateProductProgram($oldProject->parent, $project->parent, $linkedProducts);
                    if($oldProject->parent != $project->parent) $this->loadModel('program')->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);
                }

                /* When acl is open, white list set empty. When acl is private,update user view. */
                if($project->acl == 'open') $this->loadModel('personnel')->updateWhitelist(array(), 'project', $projectID);
                if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');
                $this->executeHooks($projectID);

                if(empty($oldProject->multiple) and $oldProject->model != 'waterfall') $this->execution->syncNoMultipleSprint($projectID);
            }
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }
        return $allChanges;
    }

    /**
     * 开始项目并更改其状态.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return array|false
     */
    public function start(int $projectID, object $project):array|false
    {
        $oldProject = $this->getById($projectID);

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->start, $this->post->uid);

        $this->projectTao->doStart($projectID, $project);

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError())
        {
            if(count(dao::$errors['realBegan']) > 1) dao::$errors['realBegan'] = dao::$errors['realBegan'][0];
            return false;
        }

        if(!$oldProject->multiple) $this->changeExecutionStatus($projectID, 'start');
        return common::createChanges($oldProject, $project);
    }

    /**
     * Suspend project and update status.
     * 暂停项目并更改其状态
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  string $type
     *
     * @access public
     * @return array|flase
     */
    public function suspend(int $projectID, object $project, string $type = 'project'): array|false
    {
        $editorIdList = $this->config->project->editor->suspend['id'];

        $oldProject = $this->getById($projectID, $type);

        $project = $this->loadModel('file')->processImgURL($project, $editorIdList, $this->post->uid);

        $this->projectTao->doSuspend($projectID, $project);

        if(!$oldProject->multiple) $this->changeExecutionStatus($projectID, 'suspend');
        return common::createChanges($oldProject, $project);
    }

    /**
     * Activate project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return array  $changes|false
     */
    public function activate(int $projectID, object $project) :array|false
    {
        $oldProject = $this->projectTao->fetchProjectInfo($projectID);

        $daoSuccess = $this->projectTao->doActivate($projectID, $project);
        if(!$daoSuccess) return false;

        if(empty($oldProject->multiple) and $oldProject->model != 'waterfall') $this->loadModel('execution')->syncNoMultipleSprint($projectID);

        /* Update start and end date of tasks in this project. */
        if($project->readjustTime and $project->readjustTask)
        {
            $tasks          = $this->projectTao->fetchUndoneTasks((int)$projectID);
            $this->projectTao->updateTasksStartAndEndDate($tasks, $oldProject, $project);
        }
        /* Activate the shadow product of the project. (only change product status) */
        if(!$oldProject->hasProduct)
        {
            $productID = $this->loadModel('product')->getProductIDByProject($projectID);
            $this->product->activate($productID);
        }

        return common::createChanges($oldProject, $project);
    }

    /**
     * 关闭项目并更改其状态
     * Close project and update status.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access public
     * @return array|false
     */
    public function close(int $projectID, object $project): array|false
    {
        $oldProject = $this->getByID($projectID);

        $editorIdList = $this->config->project->editor->close['id'];

        $project = $this->loadModel('file')->processImgURL($project, $editorIdList, $this->post->uid);

        $this->projectTao->doClosed($projectID, $project, $oldProject);

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError())
        {
           if(count(dao::$errors['realEnd']) > 1) dao::$errors['realEnd'] = dao::$errors['realEnd'][0];
           return false;
        }
        if(!$oldProject->multiple) $this->changeExecutionStatus($projectID, 'close');

        /* Close the shadow product of the project. */
        if(!$oldProject->hasProduct)
        {
            $productID = $this->loadModel('product')->getProductIDByProject($projectID);
            $this->product->close($productID);
        }

        $this->loadModel('score')->create('project', 'close', $oldProject);
        return common::createChanges($oldProject, $project);
    }

    /**
     * Modify the execution status when changing the status of no execution project.
     *
     * @param  int    $projectID
     * @param  string $status
     * @access public
     * @return array
     */
    public function changeExecutionStatus($projectID, $status)
    {
        if(!in_array($status, array('start', 'suspend', 'activate', 'close'))) return false;
        $executionID = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('multiple')->eq('0')->fetch('id');
        if(!$executionID) return false;
        return $this->loadModel('execution')->$status($executionID);
    }

    /**
     * Update shadow product for its project.
     *
     * @param  object $project
     * @access public
     * @return bool
     */
    public function updateShadowProduct($project)
    {
        $product    = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($project->id)->fetch('product');
        $topProgram = !empty($project->parent) ? $this->loadModel('program')->getTopByID($project->parent) : 0;
        $this->dao->update(TABLE_PRODUCT)->set('name')->eq($project->name)->set('program')->eq($topProgram)->set('acl')->eq($project->acl)->where('id')->eq($product)->exec();

        return !dao::isError();
    }

    /**
     * 删除项目下关联执行与产品与文档库
     * Deletes related items under project.
     *
     * @param  string    $table  product|execution|doclib
     * @param  int|array $idList
     *
     * @access public
     * @return bool
     */
    public function deleteByTableName(string $table, int|array $idList): bool
    {
        if(strpos($table, 'doclib') !== false)
        {
            $this->dao->update($table)->set('deleted')->eq(1)->where('execution')->eq($idList)->exec();
        }
        else
        {
            $this->dao->update($table)->set('deleted')->eq(1)->where('id')->in($idList)->exec();
        }

        return !dao::isError();
    }

    /**
     * Update the program of the product.
     *
     * @param  int    $oldProgram
     * @param  int    $newProgram
     * @param  array  $products
     *
     * @access public
     * @return void
     */
    public function updateProductProgram($oldProgram, $newProgram, $products)
    {
        $this->loadModel('action');
        $this->loadModel('program');
        /* Product belonging project set processing. */
        $oldTopProgram = $this->program->getTopByID($oldProgram);
        $newTopProgram = $this->program->getTopByID($newProgram);
        if($oldTopProgram != $newTopProgram)
        {
            foreach($products as $productID)
            {
                $oldProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
                $this->dao->update(TABLE_PRODUCT)->set('program')->eq((int)$newTopProgram)->where('id')->eq((int)$productID)->exec();
                $newProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
                $changes    = common::createChanges($oldProduct, $newProduct);
                $actionID   = $this->action->create('product', $productID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
    }

    /**
     * Unlink a member.
     *
     * @param  int    $projectID
     * @param  string $account
     * @param  string $removeExecution no|yes
     * @access public
     * @return void
     */
    public function unlinkMember(int $projectID, string $account, string $removeExecution = 'no'): void
    {
        $this->projectTao->unlinkTeamMember($projectID, 'project', $account);

        $this->loadModel('user')->updateUserView($projectID, 'project', array($account));

        if($removeExecution == 'yes')
        {
            $executions = $this->loadModel('execution')->getByProject($projectID, 'undone', 0, true);
            $this->projectTao->unlinkTeamMember(array_keys($executions), 'execution', $account);
            $this->user->updateUserView(array_keys($executions), 'sprint', array($account));
        }

        $linkedProducts = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', array($account));
    }

    /**
     * Manage team members.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function manageMembers($projectID)
    {
        $project = $this->projectTao->fetchProjectInfo($projectID);
        $data    = (array)fixer::input('post')->get();

        extract($data);
        $projectID   = (int)$projectID;
        $projectType = 'project';
        $accounts    = array_unique($accounts);
        $oldJoin     = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq($projectType)->fetchPairs();

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            if(!empty($project->days) and (int)$days[$key] > $project->days)
            {
                dao::$errors['message'][]  = sprintf($this->lang->project->daysGreaterProject, $project->days);
                return false;
            }
            if((float)$hours[$key] > 24)
            {
                dao::$errors['message'][]  = $this->lang->project->errorHours;
                return false;
            }
        }

        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq($projectType)->exec();

        $projectMember = array();
        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $member          = new stdclass();
            $member->role    = $roles[$key];
            $member->days    = $days[$key];
            $member->hours   = $hours[$key];
            $member->limited = isset($limited[$key]) ? $limited[$key] : 'no';

            $member->root    = $projectID;
            $member->account = $account;
            $member->join    = isset($oldJoin[$account]) ? $oldJoin[$account] : helper::today();
            $member->type    = $projectType;

            $projectMember[$account] = $member;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccounts     = array_keys($oldJoin);
        $removedAccounts = array_diff($oldAccounts, $accounts);
        $changedAccounts = array_merge($removedAccounts, array_diff($accounts, $oldAccounts));
        $changedAccounts = array_unique($changedAccounts);

        $childSprints   = $this->dao->select('id')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('type')->in('stage,sprint')->andWhere('deleted')->eq('0')->fetchPairs();
        $linkedProducts = $this->dao->select("t2.id")->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->fetchPairs();

        $this->loadModel('user')->updateUserView(array($projectID), 'project', $changedAccounts);
        if(!empty($childSprints))   $this->user->updateUserView($childSprints, 'sprint', $changedAccounts);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccounts);

        /* Remove execution members. */
        if($this->post->removeExecution == 'yes' and !empty($childSprints) and !empty($removedAccounts))
        {
            $this->dao->delete()->from(TABLE_TEAM)
                ->where('root')->in($childSprints)
                ->andWhere('type')->eq('execution')
                ->andWhere('account')->in($removedAccounts)
                ->exec();
        }

        if(empty($project->multiple) and $project->model != 'waterfall') $this->loadModel('execution')->syncNoMultipleSprint($projectID);
    }

    /**
     * Print datatable cell.
     *
     * @param  object $col
     * @param  object $project
     * @param  array  $users
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function printCell($col, $project, $users, $programID = 0)
    {
        $canOrder     = common::hasPriv('project', 'updateOrder');
        $canBatchEdit = common::hasPriv('project', 'batchEdit');
        $account      = $this->app->user->account;
        $id           = $col->id;
        $projectLink  = helper::createLink('project', 'index', "projectID=$project->id", '', false, $project->id);

        if($col->show)
        {
            $title = '';
            $class = "c-$id" . (in_array($id, array('budget', 'teamCount', 'estimate', 'consume')) ? ' c-number' : '');

            if($id == 'id') $class .= ' cell-id';

            if($id == 'code')
            {
                $class .= ' c-name';
                $title  = "title={$project->code}";
            }
            elseif($id == 'name')
            {
                $class .= ' text-left';
                $title  = "title='{$project->name}'";
            }
            elseif($id == 'PM')
            {
                $class .= ' c-manager';
            }

            if($id == 'end')
            {
                $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
                $class .= ' c-name';
                $title  = "title='{$project->end}'";
            }

            if($id == 'budget')
            {
                $projectBudget = $this->getBudgetWithUnit($project->budget);
                $budgetTitle   = $project->budget != 0 ? zget($this->lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $this->lang->project->future;

                $title = "title='$budgetTitle'";
            }

            if($id == 'estimate') $title = "title='{$project->hours->totalEstimate} {$this->lang->execution->workHour}'";
            if($id == 'consume')  $title = "title='{$project->hours->totalConsumed} {$this->lang->execution->workHour}'";
            if($id == 'surplus')  $title = "title='{$project->hours->totalLeft} {$this->lang->execution->workHour}'";

            echo "<td class='$class' $title>";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('project', $project, $id);
            switch($id)
            {
                case 'id':
                    if($canBatchEdit)
                    {
                        echo html::checkbox('projectIdList', array($project->id => '')) . html::a($projectLink, sprintf('%03d', $project->id));
                    }
                    else
                    {
                        printf('%03d', $project->id);
                    }
                    break;
                case 'name':
                    $prefix      = '';
                    $suffix      = '';
                    $projectIcon = '';
                    if(isset($project->delay)) $suffix = "<span class='label label-danger label-badge'>{$this->lang->project->statusList['delay']}</span>";
                    $projectType = $project->model == 'scrum' ? 'sprint' : $project->model;
                    if(!empty($suffix) or !empty($prefix)) echo '<div class="project-name' . (empty($prefix) ? '' : ' has-prefix') . (empty($suffix) ? '' : ' has-suffix') . '">';
                    if(!empty($prefix)) echo $prefix;
                    if($this->config->vision == 'rnd') $projectIcon = "<i class='text-muted icon icon-{$projectType}'></i> ";
                    echo html::a($projectLink, $projectIcon . $project->name, '', "class='text-ellipsis'");
                    if(!empty($suffix)) echo $suffix;
                    if(!empty($suffix) or !empty($prefix)) echo '</div>';
                    break;
                case 'code':
                    echo $project->code;
                    break;
                case 'PM':
                    $user       = $this->loadModel('user')->getByID($project->PM, 'account');
                    $userID     = !empty($user) ? $user->id : '';
                    $userAvatar = !empty($user) ? $user->avatar : '';
                    $PMLink     = helper::createLink('user', 'profile', "userID=$userID", '', true);
                    $userName   = zget($users, $project->PM);
                    if($project->PM) echo html::smallAvatar(array('avatar' => $userAvatar, 'account' => $project->PM, 'name' => $userName), "avatar-circle avatar-{$project->PM}");
                    echo empty($project->PM) ? '' : html::a($PMLink, $userName, '', "title='{$userName}' data-toggle='modal' data-type='iframe' data-width='600'");
                    break;
                case 'begin':
                    echo $project->begin;
                    break;
                case 'end':
                    echo $project->end;
                    break;
                case 'status':
                    echo "<span class='status-task text-center  status-{$project->status}'> " . zget($this->lang->project->statusList, $project->status) . "</span>";
                    break;
                case 'hasProduct':
                    echo zget($this->lang->project->projectTypeList, $project->hasProduct);
                    break;
                case 'budget':
                    echo $budgetTitle;
                    break;
                case 'teamCount':
                    echo $project->teamCount;
                    break;
                case 'estimate':
                    echo $project->hours->totalEstimate . $this->lang->execution->workHourUnit;
                    break;
                case 'consume':
                    echo $project->hours->totalConsumed . $this->lang->execution->workHourUnit;
                    break;
                case 'surplus':
                    echo $project->hours->totalLeft     . $this->lang->execution->workHourUnit;
                    break;
                case 'progress':
                    echo html::ring($project->hours->progress);
                    break;
                case 'actions':
                    $project->programID = $programID;
                    echo $this->buildOperateMenu($project, 'browse');
                    break;
            }
            echo '</td>';
        }
    }

    /**
     * Print datatable cell for ZIN.
     *
     * @param  object $col
     * @param  object $project
     * @param  array  $users
     * @param  object $item
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function printCellZin($col, $project, $users, &$item, $programID = 0)
    {
        if(!$col->show) return;

        $canOrder     = common::hasPriv('project', 'updateOrder');
        $canBatchEdit = common::hasPriv('project', 'batchEdit');
        $account      = $this->app->user->account;
        $id           = $col->id;
        $projectLink  = helper::createLink('project', 'index', "projectID=$project->id", '', '', $project->id);

        $title = '';
        $class = "c-$id" . (in_array($id, array('budget', 'teamCount', 'estimate', 'consume')) ? ' c-number' : '');

        if($id == 'id') $class .= ' cell-id';

        if($id == 'code')
        {
            $class .= ' c-name';
            $title  = "title={$project->code}";
        }
        elseif($id == 'name')
        {
            $class .= ' text-left';
            $title  = "title='{$project->name}'";
        }
        elseif($id == 'PM')
        {
            $class .= ' c-manager';
        }

        if($id == 'end')
        {
            $project->end = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
            $class .= ' c-name';
            $title  = "title='{$project->end}'";
        }

        if($id == 'budget')
        {
            $projectBudget = $this->getBudgetWithUnit($project->budget);
            $budgetTitle   = $project->budget != 0 ? zget($this->lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $this->lang->project->future;

            $title = "title='$budgetTitle'";
        }

        if($id == 'estimate') $title = "title='{$project->hours->totalEstimate} {$this->lang->execution->workHour}'";
        if($id == 'consume')  $title = "title='{$project->hours->totalConsumed} {$this->lang->execution->workHour}'";
        if($id == 'surplus')  $title = "title='{$project->hours->totalLeft} {$this->lang->execution->workHour}'";

        /* TODO attach flow cells. */
        /* if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('project', $project, $id); */
        switch($id)
        {
            case 'id':
                $item->id = sprintf('%03d', $project->id);
                break;
            case 'name':
                $item->name  = $project->name;
                $item->delay = isset($project->delay) ? $project->delay : 0;
                break;
            case 'code':
                $item->code = $project->code;
                break;
            case 'PM':
                $item->PM = $project->PM;
                break;
            case 'begin':
                $item->begin = $project->begin;
                break;
            case 'end':
                $item->end = $project->end;
                break;
            case 'status':
                $item->status = zget($this->lang->project->statusList, $project->status);
                break;
            case 'hasProduct':
                $item->hasProduct = zget($this->lang->project->projectTypeList, $project->hasProduct);
                break;
            case 'budget':
                $item->budget = $budgetTitle;
                break;
            case 'teamCount':
                $item->teamCount = $project->teamCount;
                break;
            case 'estimate':
                $item->estimate = $project->hours->totalEstimate . $this->lang->execution->workHourUnit;
                break;
            case 'consume':
                $item->consume = $project->hours->totalConsumed . $this->lang->execution->workHourUnit;
                break;
            case 'surplus':
                $item->surplus = $project->hours->totalLeft     . $this->lang->execution->workHourUnit;
                break;
            case 'progress':
                $item->progress = $project->hours->progress;
                break;
            case 'actions':
                $project->programID = $programID;
                $this->buildOperateMenuZin($project, $item, 'browse');
                break;
        }

        $item->storyCount     = rand(100, 100000) / 10.0;
        $item->executionCount = rand(10, 200);
        $item->invested       = rand(10, 100);
    }

    /**
     * Convert budget unit.
     *
     * @param  int        $budget
     * @access public
     * @return int|string $projectBudget
     */
    public function getBudgetWithUnit($budget)
    {
        if($budget < 10000)
        {
            $budget = round((float)$budget, 2);
            $unit   = '';
        }
        elseif($budget < 100000000 and $budget >= 10000)
        {
            $budget = round((float)$budget/10000, 2);
            $unit   = $this->lang->project->tenThousand;
        }
        else
        {
            $budget = round((float)$budget/100000000, 2);
            $unit   = $this->lang->project->hundredMillion;
        }

        $projectBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? $budget . $unit : round((float)$budget, 2);
        return $projectBudget;
    }

    /**
     * Update products of a project.
     *
     * @param  int    $projectID
     * @param  array  $products
     * @access public
     * @return void
     */
    public function updateProducts($projectID, $products = '')
    {
        $this->loadModel('user');

        $members = array_keys($this->getTeamMembers($projectID));

        /* Link products of other programs. */
        if(!empty($_POST['otherProducts']))
        {
            $products      = array();
            $otherProducts = $_POST['otherProducts'];
            foreach($otherProducts as $index => $otherProduct)
            {
                if(!$otherProduct) continue;

                $data = new stdclass();
                $data->project = $projectID;
                $data->plan    = 0;

                if(strpos($otherProduct, '_') !== false)
                {
                    $params = explode('_', $otherProduct);
                    $data->product = $params[0];
                    $data->branch  = $params[1];
                }
                else
                {
                    $data->product = $otherProduct;
                    $data->branch  = 0;
                }

                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();

                $products[] = $data->product;
            }

            $this->user->updateUserView($products, 'product', $members);
            if((int)$projectID > 0 and !empty($_POST['division']))
            {
                $this->dao->update(TABLE_PROJECT)->set('division')->eq('1')->where('id')->eq((int)$projectID)->exec();
                $this->dao->update(TABLE_EXECUTION)->set('division')->eq('1')->where('project')->eq((int)$projectID)->exec();
            }

            return !dao::isError();
        }

        /* Link products of current program of the project. */
        $products           = isset($_POST['products']) ? $_POST['products'] : $products;
        $oldProjectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->fetchGroup('product', 'branch');

        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->exec();

        if(empty($products))
        {
            $this->user->updateUserView(array_keys($oldProjectProducts), 'product', $members);
            return true;
        }

        $branches = isset($_POST['branch']) ? $_POST['branch'] : array();
        $plans    = isset($_POST['plans']) ? $_POST['plans'] : array();;

        $existedProducts = array();
        foreach($products as $i => $productID)
        {
            if(empty($productID)) continue;

            if(!isset($existedProducts[$productID])) $existedProducts[$productID] = array();

            $oldPlan = 0;
            $branch  = isset($branches[$i]) ? $branches[$i] : 0;

            if(!is_array($branch)) $branch = array($branch => $branch);

            foreach($branch as $branchID)
            {
                if(isset($existedProducts[$productID][$branchID])) continue;

                if(isset($oldProjectProducts[$productID][$branchID]))
                {
                    $oldProjectProduct = $oldProjectProducts[$productID][$branchID];
                    if($this->app->rawMethod != 'edit') $oldPlan = $oldProjectProduct->plan;
                }

                $data = new stdclass();
                $data->project = $projectID;
                $data->product = $productID;
                $data->branch  = $branchID;
                $data->plan    = isset($plans[$productID]) ? implode(',', $plans[$productID]) : $oldPlan;
                $data->plan    = trim((string)$data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";

                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                $existedProducts[$productID][$branchID] = true;
            }
        }

        /* Delete the execution linked products that is not linked with the execution. */
        $projectID = (int)$projectID;
        if($projectID > 0)
        {
            $executions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->fetchPairs('id');
            $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in($executions)->andWhere('product')->notin($products)->exec();

            if(!empty($_POST['division']))
            {
                $this->dao->update(TABLE_PROJECT)->set('division')->eq('1')->where('id')->eq((int)$projectID)->exec();
                $this->dao->update(TABLE_EXECUTION)->set('division')->eq('1')->where('project')->eq((int)$projectID)->exec();
            }

            $project = $this->projectTao->fetchProjectInfo($projectID);
            if(!empty($project) and ($project->model == 'waterfall' or $project->model == 'waterfallplus') and empty($project->division) and !empty($executions))
            {
                $this->loadModel('execution');
                foreach($executions as $executionID) $this->execution->updateProducts($executionID);
            }
        }

        $oldProductKeys = array_keys($oldProjectProducts);
        $needUpdate = array_merge(array_diff($oldProductKeys, $products), array_diff($products, $oldProductKeys));
        if($needUpdate) $this->user->updateUserView($needUpdate, 'product', $members);
    }

    /**
     * Update userview for involved product and execution.
     *
     * @param  int    $projectID
     * @param  array  $users
     * @access public
     * @return void
     */
    public function updateInvolvedUserView($projectID, $users = array())
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product', 'product');
        $this->loadModel('user')->updateUserView($products, 'product', $users);

        $executions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->fetchPairs('id', 'id');
        if($executions) $this->user->updateUserView($executions, 'sprint', $users);
    }

    /**
     * Get team members.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTeamMembers($projectID)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembers();

        $project = $this->projectTao->fetchProjectInfo($projectID);
        if(empty($project)) return array();

        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, t2.id as userID, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$projectID)
            ->andWhere('t1.type')->eq($project->type)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchAll('account');
    }

    /**
     * Get team member pairs by projectID.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTeamMemberPairs($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        if(empty($project)) return array();

        $type = 'project';
        if($project->type == 'sprint' or $project->type == 'stage' or $project->type == 'kanban') $type = 'execution';

        $members = $this->dao->select("t1.account, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$projectID)
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchPairs('account', 'realname');

        return array('' => '') + $members;
    }

    /**
     * Get team member group.
     *
     * @param  array|string $projectIdList
     * @access public
     * @return array
     */
    public function getTeamMemberGroup($projectIdList)
    {
        if(empty($projectIdList)) return array();

        return $this->dao->select("t1.account, if(t2.deleted='0', t2.realname, t1.account) as realname, t1.root as project")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->in($projectIdList)
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchGroup('project', 'account');
    }

    /**
     * Get members of a project who can be imported.
     *
     * @param  int    $projectID
     * @param  array  $currentMembers
     * @access public
     * @return array
     */
    public function getMembers2Import($projectID, $currentMembers)
    {
        if($projectID == 0) return array();

        return $this->dao->select('account, role, hours')
            ->from(TABLE_TEAM)
            ->where('root')->eq($projectID)
            ->andWhere('type')->eq('project')
            ->andWhere('account')->notIN($currentMembers)
            ->fetchAll('account');
    }

    /**
     * Get stats for project kanban.
     *
     * @access public
     * @return void
     */
    public function getStats4Kanban()
    {
        $this->loadModel('program');

        $projects   = $this->program->getProjectStats(0, 'all', 0, 'order_asc');
        $executions = $this->loadModel('execution')->getStatData(0, 'doing', 0, 0, false, 'hasParentName|skipParent');

        $doingExecutions  = array();
        $latestExecutions = array();
        foreach($executions as $execution) $doingExecutions[$execution->project][$execution->id] = $execution;
        foreach($doingExecutions as $projectID => $executions)
        {
            krsort($doingExecutions[$projectID]);
            $latestExecutions[$projectID] = current($doingExecutions[$projectID]);
        }

        $myProjects    = array();
        $otherProjects = array();
        $closedGroup   = array();
        foreach($projects as $project)
        {
            if(strpos('wait,doing,closed', $project->status) === false) continue;

            $projectPath = explode(',', trim($project->path, ','));
            $topProgram  = !empty($project->parent) ? $projectPath[0] : $project->parent;

            if($project->PM == $this->app->user->account)
            {
                if($project->status != 'closed')
                {
                    $myProjects[$topProgram][$project->status][] = $project;
                }
                else
                {
                    $closedGroup['my'][$topProgram][$project->closedDate] = $project;
                }
            }
            else
            {
                if($project->status != 'closed')
                {
                    $otherProjects[$topProgram][$project->status][] = $project;
                }
                else
                {
                    $closedGroup['other'][$topProgram][$project->closedDate] = $project;
                }
            }
        }

        /* Only display recent two closed projects. */
        foreach($closedGroup as $group => $closedProjects)
        {
            foreach($closedProjects as $topProgram => $projects)
            {
                krsort($projects);
                if($group == 'my')
                {
                    $myProjects[$topProgram]['closed'] = array_slice($projects, 0, 2);
                }
                else
                {
                    $otherProjects[$topProgram]['closed'] = array_slice($projects, 0, 2);
                }
            }
        }

        return array('kanbanGroup' => array('my' => $myProjects, 'other' => $otherProjects), 'latestExecutions' => $latestExecutions);
    }

    /**
     * Computer execution progress.
     *
     * @param  array    $executions
     * @access public
     * @return array
     */
    public function computerProgress($executions)
    {
        $hours     = array();
        $emptyHour = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, execution, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('execution', 'id');

        $projects = $this->dao->select('t1.id,t2.model')
            ->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.id')->in(array_keys($executions))
            ->fetchPairs();

        /* Compute totalEstimate, totalConsumed, totalLeft. */
        foreach($tasks as $executionID => $executionTasks)
        {
            $hour = (object)$emptyHour;
            foreach($executionTasks as $task)
            {
                $hour->totalEstimate += $task->estimate;
                $hour->totalConsumed += $task->consumed;
                if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += $task->left;
            }
            $hours[$executionID] = $hour;

            if(isset($executions[$executionID]) and $executions[$executionID]->grade > 1 and isset($projects[$executionID]) and ($projects[$executionID] == 'waterfall' or $projects[$executionID] == 'waterfallplus'))
            {
                $stageParents = $this->dao->select('id')->from(TABLE_EXECUTION)->where('id')->in(trim($executions[$executionID]->path, ','))->andWhere('type')->eq('stage')->andWhere('id')->ne($executions[$executionID]->id)->orderBy('grade')->fetchPairs();
                foreach($stageParents as $stageParent)
                {
                    if(!isset($hours[$stageParent]))
                    {
                        $hours[$stageParent] = clone $hour;
                        continue;
                    }

                    $hours[$stageParent]->totalEstimate += $hour->totalEstimate;
                    $hours[$stageParent]->totalConsumed += $hour->totalConsumed;
                    $hours[$stageParent]->totalLeft     += $hour->totalLeft;
                }
            }
        }

        /* Compute totalReal and progress. */
        foreach($hours as $hour)
        {
            $hour->totalEstimate = round($hour->totalEstimate, 1) ;
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal * 100, 2) : 0;
        }

        return $hours;
    }

    /**
     * Set menu of project module.
     *
     * @param  int    $objectID  projectID
     * @access public
     * @return int
     */
    public function setMenu($objectID)
    {
        global $lang;

        $model    = 'scrum';
        $objectID = (empty($objectID) and $this->session->project) ? $this->session->project : $objectID;
        $project  = $this->projectTao->fetchProjectInfo($objectID);

        if(!$project)
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            if($execution and $execution->project and !$execution->multiple)
            {
                $project = $this->projectTao->fetchProjectInfo($execution->project);
                $objectID = $execution->project;
            }
        }

        if($project and $project->model == 'waterfall') $model = $project->model;
        if($project and $project->model == 'waterfallplus') $model = 'waterfall';
        if($project and $project->model == 'kanban')
        {
            $model = $project->model . 'Project';

            $lang->executionCommon = $lang->project->kanban;
        }

        if(isset($lang->$model))
        {
            $lang->project->menu        = $lang->{$model}->menu;
            $lang->project->menuOrder   = $lang->{$model}->menuOrder;
            $lang->project->dividerMenu = $lang->{$model}->dividerMenu;
        }

        if(empty($project->hasProduct))
        {
            unset($lang->project->menu->settings['subMenu']->products);
            $projectProduct = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetch('product');
            $lang->project->menu->settings['subMenu']->module['link'] = sprintf($lang->project->menu->settings['subMenu']->module['link'], $projectProduct);

            if(isset($project->model) and ($project->model == 'scrum' or $project->model == 'agileplus'))
            {
                $lang->project->menu->projectplan['link'] = sprintf($lang->project->menu->projectplan['link'], $projectProduct);
            }
            else
            {
                unset($lang->project->menu->projectplan);
            }

            if(!empty($this->config->URAndSR) && $project->model !== 'kanban' && isset($lang->project->menu->storyGroup))
            {
                $lang->project->menu->settings['subMenu']->module['link'] = sprintf($lang->project->menu->settings['subMenu']->module['link'], $projectProduct);

                if($project->model !== 'kanban' && isset($lang->project->menu->storyGroup))
                {
                    $lang->project->menu->story = $lang->project->menu->storyGroup;
                    $lang->project->menu->story['link'] = sprintf($lang->project->menu->storyGroup['link'], '%s', $projectProduct);
                    $lang->project->menu->story['dropMenu']->story['link']       = sprintf($lang->project->menu->storyGroup['dropMenu']->story['link'], '%s', $projectProduct);
                    $lang->project->menu->story['dropMenu']->requirement['link'] = sprintf($lang->project->menu->storyGroup['dropMenu']->requirement['link'], '%s', $projectProduct);
                }

                unset($lang->project->menu->settings['subMenu']->products);
            }
        }
        else
        {
            unset($lang->project->menu->settings['subMenu']->module);
            unset($lang->project->menu->projectplan);
        }

        if(isset($lang->project->menu->storyGroup)) unset($lang->project->menu->storyGroup);

        /* Reset project priv. */
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $this->loadModel('common')->resetProjectPriv($objectID);
        if(!$this->common->isOpenMethod($moduleName, $methodName) and !commonModel::hasPriv($moduleName, $methodName)) $this->common->deny($moduleName, $methodName, false);

        if(isset($project->model) and ($project->model == 'waterfall' or $project->model == 'waterfallplus'))
        {
            $lang->project->createExecution = str_replace($lang->executionCommon, $lang->project->stage, $lang->project->createExecution);
            $lang->project->lastIteration   = str_replace($lang->executionCommon, $lang->project->stage, $lang->project->lastIteration);

            $this->loadModel('execution');
            $executionCommonLang   = $lang->executionCommon;
            $lang->executionCommon = $lang->project->stage;

            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';

            $lang->execution->typeList['sprint'] = $executionCommonLang;
        }

        $lang->switcherMenu = $this->getSwitcher($objectID, $moduleName, $methodName);

        $this->saveState($objectID, $this->getPairsByProgram());

        if(isset($project->acl) and $project->acl == 'open') unset($lang->project->menu->settings['subMenu']->whitelist);

        common::setMenuVars('project', $objectID);

        $this->setNoMultipleMenu($objectID);
        return $objectID;
    }

    /**
     * Set multi-scrum menu.
     *
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function setNoMultipleMenu($objectID)
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        $this->session->set('multiple', true);

        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($objectID)->andWhere('multiple')->eq('0')->fetch();
        if(empty($project)) return;

        if(!in_array($project->type, array('project', 'sprint', 'kanban'))) return;

        if($project->type == 'project')
        {
            $model       = $project->model;
            $projectID   = $project->id;
            $executionID = $this->dao->select('id')->from(TABLE_EXECUTION)
                ->where('project')->eq($projectID)
                ->andWhere('multiple')->eq('0')
                ->andWhere('type')->in('kanban,sprint')
                ->andWhere('deleted')->eq('0')
                ->fetch('id');
        }
        else
        {
            $model       = $project->type == 'kanban' ? 'kanban' : 'scrum';
            $executionID = $project->id;
            $projectID   = $project->project;
        }
        if(empty($projectID) or empty($executionID)) return;

        $this->session->set('project', $projectID, 'project');
        $this->session->set('multiple', false);

        $navGroup = zget($this->lang->navGroup, $moduleName);
        $this->lang->$navGroup->menu        = $this->lang->project->noMultiple->{$model}->menu;
        $this->lang->$navGroup->menuOrder   = $this->lang->project->noMultiple->{$model}->menuOrder;
        $this->lang->$navGroup->dividerMenu = $this->lang->project->noMultiple->{$model}->dividerMenu;

        /* Single execution and has no product project menu. */
        if(!$project->hasProduct and !$project->multiple and !empty($this->config->URAndSR))
        {
            if(isset($this->lang->$navGroup->menu->storyGroup))
            {
                $this->lang->$navGroup->menu->story = $this->lang->$navGroup->menu->storyGroup;
                $this->lang->$navGroup->menu->story['link'] = sprintf($this->lang->$navGroup->menu->storyGroup['link'], '%s', $projectID);

                $this->lang->$navGroup->menu->story['dropMenu']->story['link']       = sprintf($this->lang->$navGroup->menu->storyGroup['dropMenu']->story['link'], '%s', $projectID);
                $this->lang->$navGroup->menu->story['dropMenu']->requirement['link'] = sprintf($this->lang->$navGroup->menu->storyGroup['dropMenu']->requirement['link'], '%s', $projectID);
            }
        }

        if(isset($this->lang->$navGroup->menu->storyGroup)) unset($this->lang->$navGroup->menu->storyGroup);
        foreach($this->lang->$navGroup->menu as $label => $menu)
        {
            $objectID = 0;
            if(strpos($this->config->project->multiple['project'], ",{$label},") !== false) $objectID = $projectID;
            if(strpos($this->config->project->multiple['execution'], ",{$label},") !== false)
            {
                $objectID = $executionID;
                $this->lang->$navGroup->menu->{$label}['subModule'] = 'project';
            }
            $this->lang->$navGroup->menu->$label = commonModel::setMenuVarsEx($menu, $objectID);
            if(isset($menu['subMenu']))
            {
                foreach($menu['subMenu'] as $key1 => $subMenu) $this->lang->$navGroup->menu->{$label}['subMenu']->$key1 = common::setMenuVarsEx($subMenu, $objectID);
            }

            if(!isset($menu['dropMenu'])) continue;
            foreach($menu['dropMenu'] as $key2 => $dropMenu)
            {
                $this->lang->$navGroup->menu->{$label}['dropMenu']->$key2 = common::setMenuVarsEx($dropMenu, $objectID);

                if(!isset($dropMenu['subMenu'])) continue;
                foreach($dropMenu['subMenu'] as $key3 => $subMenu) $this->lang->$navGroup->menu->{$label}['dropMenu']->$key3 = common::setMenuVarsEx($subMenu, $objectID);
            }
        }

        /* If objectID is set, cannot use homeMenu. */
        unset($this->lang->project->homeMenu);
        $this->lang->switcherMenu         = $this->getSwitcher($projectID, $moduleName, $methodName);
        $this->lang->project->menu        = $this->lang->$navGroup->menu;
        $this->lang->project->menuOrder   = $this->lang->$navGroup->menuOrder;
        $this->lang->project->dividerMenu = $this->lang->$navGroup->dividerMenu;

        if(empty($project->hasProduct))
        {
            unset($this->lang->project->menu->settings['subMenu']->products);
        }
        else
        {
            unset($this->lang->project->menu->settings['subMenu']->module);
            unset($this->lang->project->menu->projectplan);
        }

        $this->loadModel('common')->resetProjectPriv($projectID);
    }

    /**
     * Check if the project model can be changed.
     *
     * @param  int    $projectID
     * @param  string $model
     * @access public
     * @return bool
     */
    public function checkCanChangeModel($projectID, $model)
    {
        $checkList = $this->config->project->checkList->$model;
        if($this->config->edition == 'max') $checkList = $this->config->project->maxCheckList->$model;
        foreach($checkList as $module)
        {
            if($module == '') continue;

            $type  = '';
            $table = constant('TABLE_'. strtoupper($module));
            if($module == 'execution')
            {
                switch($model)
                {
                case 'scrum':
                    $type = 'sprint';
                    break;
                case 'waterfall':
                    $type = 'stage';
                    break;
                case 'kanban':
                    $type = 'kanban';
                    break;
                }
            }

            $object = $this->getDataByProject($table, $projectID, $type);
            if(!empty($object)) return false;
        }
        return true;
    }

    /**
     * Get the objects under the project.
     *
     * @param  constant $table
     * @param  int      $projectID
     * @param  string   $type
     * @access public
     * @return object
     */
    public function getDataByProject($table, $projectID, $type = '')
    {
        $result = $this->dao->select('id')->from($table)
            ->where('project')->eq($projectID)
            ->beginIF(!empty($type))->andWhere('type')->eq($type)->fi()
            ->fetch();
        return $result;
    }

    /**
     * Build project action menu.
     *
     * @param  object $project
     * @param  object $item
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenuZin($project, &$item, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'MenuZin';
        return $this->$function($project, $item);
    }

    /**
     * Build project action menu.
     *
     * @param  object $project
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($project, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($project);
    }

    /**
     * Build project view action menu.
     *
     * @param  object $project
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($project)
    {
        if($project->deleted) return '';

        $menu   = '';
        $params = "projectID=$project->id";

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildMenu('project', 'start',    $params, $project, 'view', 'play',  '', 'iframe', true, '', $this->lang->project->start);
        $menu .= $this->buildMenu('project', 'activate', $params, $project, 'view', 'magic', '', 'iframe', true, '', $this->lang->project->activate);
        $menu .= $this->buildMenu('project', 'suspend',  $params, $project, 'view', 'pause', '', 'iframe', true, '', $this->lang->project->suspend);
        $menu .= $this->buildMenu('project', 'close',    $params, $project, 'view', 'off',   '', 'iframe', true, '', $this->lang->close);

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('project', $project, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('project', 'edit',   "project=$project->id&from=view",            $project, 'button', 'edit', '',           '', '', '', $this->lang->edit);
        $menu .= $this->buildMenu('project', 'delete', "project=$project->id&confirm=no&from=view", $project, 'button', 'trash', 'hiddenwin', '', '', '', $this->lang->delete);

        return $menu;
    }

    /**
     * Build project browse action menu.
     *
     * @param  object $project
     * @param  object $item
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenuZin($project, &$item)
    {
        $item->actions = array();
        $moduleName    = 'project';

        if($project->status == 'wait' || $project->status == 'suspended') $item->actions[] = 'start';
        if($project->status == 'doing')  $item->actions[] = 'close';
        if($project->status == 'closed') $item->actions[] = 'active';

        if(common::hasPriv($moduleName, 'suspend') || (common::hasPriv($moduleName, 'close') && $project->status != 'doing') || (common::hasPriv($moduleName, 'activate') && $project->status != 'closed'))
        {
            $menu  = 'pause';
            $comma = ',';
            if($project->status != 'doing')  $menu .= $comma . 'close';
            if($project->status != 'closed') $menu .= $comma . 'active';

            $item->actions[] = 'other:' . $menu;
        }

        $item->actions[] = 'edit';

        if($this->config->vision != 'lite')
        {
            $item->actions[] = 'group';
            $item->actions[] = 'perm';

            if(common::hasPriv($moduleName, 'manageProducts') || common::hasPriv($moduleName, 'whitelist') || common::hasPriv($moduleName, 'delete'))
            {
                $item->actions[] = 'more:link,whitelist,delete';
            }
            return;
        }

        $item->actions[] = 'group';
        $item->actions[] = 'whitelist';
        $item->actions[] = 'delete';
    }

    /**
     * Build project browse action menu.
     *
     * @param  object $project
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($project)
    {
        $menu   = '';
        $params = "projectID=$project->id";

        $moduleName = "project";
        if($project->status == 'wait' || $project->status == 'suspended')
        {
            $menu .= $this->buildMenu($moduleName, 'start', $params, $project, 'browse', 'play', '', 'iframe', true);
        }
        if($project->status == 'doing')  $menu .= $this->buildMenu($moduleName, 'close',    $params, $project, 'browse', 'off',   '', 'iframe', true);
        if($project->status == 'closed') $menu .= $this->buildMenu($moduleName, 'activate', $params, $project, 'browse', 'magic', '', 'iframe', true);

        if(common::hasPriv($moduleName, 'suspend') || (common::hasPriv($moduleName, 'close') && $project->status != 'doing') || (common::hasPriv($moduleName, 'activate') && $project->status != 'closed'))
        {
            $menu .= "<div class='btn-group'>";
            $menu .= "<button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px; border-radius: 4px;'></button>";
            $menu .= "<ul class='dropdown-menu pull-right text-center' role='menu' style='position: unset; min-width: auto; padding: 5px 6px;'>";
            $menu .= $this->buildMenu($moduleName, 'suspend', $params, $project, 'browse', 'pause', '', 'iframe btn-action', true);
            if($project->status != 'doing')  $menu .= $this->buildMenu($moduleName, 'close',    $params, $project, 'browse', 'off',   '', 'iframe btn-action', true);
            if($project->status != 'closed') $menu .= $this->buildMenu($moduleName, 'activate', $params, $project, 'browse', 'magic', '', 'iframe btn-action', true);
            $menu .= "</ul>";
            $menu .= "</div>";
        }

        $from     = $project->from == 'project' ? 'project' : 'pgmproject';
        $iframe   = $this->app->tab == 'program' ? 'iframe' : '';
        $onlyBody = $this->app->tab == 'program' ? true : '';
        $dataApp  = "data-app=project";

        $menu .= $this->buildMenu($moduleName, 'edit', $params, $project, 'browse', 'edit', '', $iframe, $onlyBody, $dataApp);

        if($this->config->vision != 'lite')
        {
            $menu .= $this->buildMenu($moduleName, 'team', $params, $project, 'browse', 'group', '', '', '', $dataApp, $this->lang->execution->team);
            $menu .= $this->buildMenu('project', 'group', "$params&programID={$project->programID}", $project, 'browse', 'lock', '', '', '', $dataApp);

            if(common::hasPriv($moduleName, 'manageProducts') || common::hasPriv($moduleName, 'whitelist') || common::hasPriv($moduleName, 'delete'))
            {
                $menu .= "<div class='btn-group'>";
                $menu .= "<button type='button' class='btn dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}'><i class='icon-ellipsis-v'></i></button>";
                $menu .= "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                $menu .= $this->buildMenu($moduleName, 'manageProducts', $params . "&from={$this->app->tab}", $project, 'browse', 'link', '', 'btn-action', '', $project->hasProduct ? '' : "disabled='disabled'", $this->lang->project->manageProducts);
                $menu .= $this->buildMenu('project', 'whitelist', "$params&module=project&from=$from", $project, 'browse', 'shield-check', '', 'btn-action', '', $dataApp);
                $menu .= $this->buildMenu($moduleName, "delete", $params, $project, 'browse', 'trash', 'hiddenwin', 'btn-action');
                $menu .= "</ul>";
                $menu .= "</div>";
            }
        }
        else
        {
            $menu .= $this->buildMenu($moduleName, 'team', $params, $project, 'browse', 'group', '', '', '', $dataApp, $this->lang->execution->team);
            $menu .= $this->buildMenu('project', 'whitelist', "$params&module=project&from=$from", $project, 'browse', 'shield-check', '', 'btn-action', '', $dataApp);
            $menu .= $this->buildMenu($moduleName, "delete", $params, $project, 'browse', 'trash', 'hiddenwin', 'btn-action');
        }

        return $menu;
    }

    /**
     * Get linked repo pairs by project id.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function linkedRepoPairs($projectID)
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(',', projects, ',')")->like("%,$projectID,%")
            ->fetchAll();

        $repoPairs = array();
        foreach($repos as $repo)
        {
            $scm = $repo->SCM == 'Subversion' ? 'svn' : strtolower($repo->SCM);
            $repoPairs[$repo->id] = "[{$scm}] " . $repo->name;
        }

        return $repoPairs;
    }

    /**
     * Fetch planIdList by project
     *
     * @param  int   $projectID
     * @param  array $plans
     * @access public
     * @return void
     */
    public function updatePlanIdListByProject(int $projectID, array $plans): void
    {
        /* Link the plan stories and transform $plans to $newPlans. */
        $newPlans = array();
        if(isset($plans))
        {
            foreach($plans as $planList)
            {
                foreach($planList as $planIDList)
                {
                    foreach($planIDList as $planID) $newPlans[$planID] = $planID;
                }
            }
        }
        if(empty($newPlans)) return;

        /* Get old PlanIdList by project*/
        $oldPlanList = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->andWhere('plan')->ne(0)->fetchPairs('plan');

        $oldPlans    = array();
        foreach($oldPlanList as $oldPlanIDList)
        {
            if(is_numeric($oldPlanIDList)) $oldPlans[$oldPlanIDList] = $oldPlanIDList;
            if(!is_numeric($oldPlanIDList))
            {
                $oldPlanIDList = explode(',', $oldPlanIDList);
                foreach($oldPlanIDList as $oldPlanID) $oldPlans[$oldPlanID] = $oldPlanID;
            }
        }

        $diffResult = array_diff($oldPlans, $newPlans);
        if(!empty($diffResult)) $this->loadModel('productplan')->linkProject($projectID, $newPlans);
    }
}
