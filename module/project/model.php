<?php
declare(strict_types=1);
class projectModel extends model
{
    /**
     * 根据对象类型获取访问控制列表。
     * Get access control list by object type.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getAclListByObjectType(string $objectType): array
    {
        return $this->dao->select('id, account, objectType, objectID')->from(TABLE_ACL)->where('objectType')->in($objectType)->fetchAll('id');
    }

    /**
     * 根据权限控制范围获取项目。
     * Get projects by acl.
     *
     * @param  string $acl
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getListByAcl(string $acl, array $idList = []): array
    {
        $projects = $this->getListByAclAndType($acl);
        if(!$projects) return [];
        if(!$idList) return $projects;

        return array_intersect_key($projects, array_flip($idList)) ?: [];
    }

    /**
     * 根据权限控制范围和类型获取项目。
     * Get projects by acl and type.
     *
     * @param  string $acl
     * @param  string $type
     * @access public
     * @return array
     */
    public function getListByAclAndType(string $acl, string $type = ''): array
    {
        return $this->dao->select('id, project, type, parent, path, openedBy, PO, PM, QD, RD, acl')->from(TABLE_PROJECT)
            ->where('acl')->in($acl)
            ->beginIF($type)->andWhere('type')->in($type)->fi()
            ->filterTpl('skip')
            ->fetchAll('id');
    }

    /**
     * 根据类型获取团队。
     * Get teams by type.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTeamListByType(string $type): array
    {
        return $this->dao->select('id, root, type, account')->from(TABLE_TEAM)->where('type')->in($type)->fetchAll('id');
    }

    /**
     * 获取当前登录用户有权限查看的项目列表.
     * Get project list by current user.
     *
     * @param  string $fields
     * @param  string $filterTpl
     * @access public
     * @return array
     */
    public function getListByCurrentUser(string $fields = '*', string $filterTpl = '') :array
    {
        if($filterTpl == 'skip') dao::$filterTpl = 'skip';

        return $this->dao->select($fields)->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($filterTpl == 'onlyTpl')->andWhere('isTpl')->eq('1')->fi()
            ->orderBy('order_asc,id_desc')
            ->fetchAll('id');
    }

    /**
     * 获取当前登录用户参与的项目列表.
     * Get project list by current user.
     *
     * @param  string    $fields
     * @access public
     * @return array
     */
    public function getInvolvedListByCurrentUser(string $fields = 't1.*') :array
    {
        $stmt = $this->dao->select($fields)->from(TABLE_PROJECT)->alias('t1');
        $stmt = $this->leftJoinInvolvedTable($stmt);

        $stmt->where('t1.type')->eq('project')
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi();
        $stmt = $this->appendInvolvedCondition($stmt);

        return $stmt->orderBy('order_asc,id_desc')
            ->fetchAll('id');
    }

    /**
     * 左链接 "我参与的" 条件需要的表，传入的$stmt t1应为 TABLE_PROJECT 表
     * LeftJoin "I participate" condition required table.
     *
     * @param  object $stmt
     * @access public
     * @return object
     */
    public function leftJoinInvolvedTable($stmt)
    {
        return $stmt->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id = t2.root')
            ->leftJoin(TABLE_STAKEHOLDER)->alias('t3')->on('t1.id=t3.objectID');
    }

    /**
     * 添加 "我参与的" 条件所需的查询条件，项目的PM、项目的成员、项目的干系人、项目的白名单
     * Add the query condition for the "I Participate".
     *
     * @param  object $stmt
     * @access public
     * @return object
     */
    public function appendInvolvedCondition($stmt)
    {
        return $stmt->andWhere('t2.type')->eq('project')
            ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
            ->orWhere('t1.PM')->eq($this->app->user->account)
            ->orWhere('t2.account')->eq($this->app->user->account)
            ->orWhere('(t3.user')->eq($this->app->user->account)
            ->andWhere('t3.deleted')->eq(0)
            ->markRight(1)
            ->orWhere("CONCAT(',', t1.whitelist, ',')")->like("%,{$this->app->user->account},%")
            ->markRight(1);
    }

    /**
     * 查找项目执行下关联的产品
     * Get linked products with execution under the project.
     *
     * @param  array $executionIDs
     *
     * @access protected
     * @return array
     */
    public function getExecutionProductGroup(array $executionIDs): array
    {
        return $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in($executionIDs)
            ->fetchGroup('project', 'product');
    }

    /**
     * 检查用户是否有查看项目的权限。
     * Check the privilege.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function checkPriv($projectID): bool
    {
        return !empty($projectID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->projects},", ",{$projectID},") !== false));
    }

    /**
     * 判断项目操作的权限。
     * Judge an action is clickable or not.
     *
     * @param  object $project
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $project, string $action)
    {
        global $config;
        $action = strtolower($action);

        if($action == 'publishtemplate')   return $project->status == 'wait' || $project->status == 'closed';
        if($action == 'disabletemplate')   return $project->status == 'doing';
        if($action == 'deletedeliverable') return !$project->review && empty($project->builtin);

        if($action == 'close')     return $project->status != 'closed';
        if($action == 'group')     return $project->model != 'kanban';
        if($action == 'start')     return $project->status == 'wait' || $project->status == 'suspended';
        if($action == 'finish')    return $project->status == 'wait' || $project->status == 'doing';
        if($action == 'suspend')   return $project->status == 'wait' || $project->status == 'doing';
        if($action == 'activate')  return $project->status == 'done' || $project->status == 'closed';
        if($action == 'whitelist') return $project->acl != 'open';
        if($action == 'link')      return $project->hasProduct;

        return true;
    }

    /**
     * 检查用户是否可以访问当前项目。
     * Check whether access to the current project is allowed or not.
     *
     * @param  int    $projectID
     * @param  array  $projects
     * @access public
     * @return int|false
     */
    public function checkAccess(int $projectID = 0, array $projects = array()): int|false
    {
        if(commonModel::isTutorialMode()) return $projectID;

        if(!$projectID)
        {
            if($this->cookie->lastProject) $projectID = $this->cookie->lastProject;
            if(!$projectID) $projectID = $this->session->project ? $this->session->project : (int)key($projects);
        }

        /* 项目模板不校验访问权限。 */
        $isTpl = $this->dao->select('isTpl')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('isTpl');
        if(empty($isTpl) && !isset($projects[$projectID]))
        {
            if($projectID && strpos(",{$this->app->user->view->projects},", ",{$projectID},") === false && !empty($projects))
            {
                /* Redirect old project to new project. */
                $projectID = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('project');
                if(!$projectID || strpos(",{$this->app->user->view->projects},", ",{$projectID},") === false) return false;
            }
            else
            {
                $projectID = key($projects);
            }
        }

        $this->session->set('project', (int)$projectID, $this->app->tab);
        if($projectID) helper::setcookie('lastProject', (string)$projectID);
        return (int)$projectID;
    }

    /**
     * 获取项目的预算单位列表。
     * Get budget unit list.
     *
     * @access public
     * @return array
     */
    public function getBudgetUnitList(): array
    {
        $budgetUnitList = array();
        if($this->config->vision != 'lite')
        {
            foreach(explode(',', $this->config->project->unitList) as $unit) $budgetUnitList[$unit] = zget($this->lang->project->currencySymbol, $unit, '') . ' ' . zget($this->lang->project->unitList, $unit, '');
        }

        return $budgetUnitList;
    }

    /**
     * 获取有迭代项目关联的产品列表。
     * Get Multiple linked products for project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMultiLinkedProducts(int $projectID): array
    {
        $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
        return $this->dao->select('t3.id,t3.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.product')->in($linkedProducts)
            ->andWhere('t1.project')->ne($projectID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据项目ID获取项目信息。
     * Get a project by id.
     *
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return object|false
     */
    public function getByID(int $projectID, string $type = ''): object|false
    {
        /* Using demo data during tutorials. */
        if(commonModel::isTutorialMode()) return empty($projectID) ? false : $this->loadModel('tutorial')->getProject();

        /* Get project info. */
        $project = $this->projectTao->fetchProjectInfo($projectID, $type);
        if(!$project) return false;

        /* Replace image url. */
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
     * @param  string      $status
     * @param  string      $orderBy
     * @param  bool        $involved
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getList(string $status = 'undone', string $orderBy = 'order_desc', bool $involved = false, ?object $pager = null): array
    {
        /* Get project list by status. */
        $projects = $this->projectTao->fetchProjectList($status, $orderBy, $involved, $pager);
        if(empty($projects)) return array();

        /* Get team members and estimates under the project. */
        $projectIdList = array_keys($projects);
        $teamCount     = $this->projectTao->fetchMemberCountByIdList($projectIdList);
        $estimates     = $this->projectTao->fetchTaskEstimateByIdList($projectIdList, 'estimate');

        /* Set project attribute. */
        $this->app->loadClass('pager', true);
        foreach($projects as $projectID => $project)
        {
            $orderBy = in_array($project->model, array('waterfall', 'ipd')) ? 'id_asc' : 'id_desc';
            $pager   = in_array($project->model, array('waterfall', 'ipd')) ? null : new pager(0, 1, 1);
            $project->executions = $this->loadModel('execution')->getStatData($projectID, 'undone', 0, 0, false, '', $orderBy, $pager);
            $project->teamCount  = isset($teamCount[$projectID]) ? $teamCount[$projectID] : 0;
            $project->estimate   = isset($estimates[$projectID]) ? round((float)$estimates[$projectID]->estimate, 2) : 0;
            $project->parentName = $project->parent ? $this->projectTao->getParentProgram($project->path, $project->grade) : '';
        }
        return $projects;
    }

    /**
     * 获取项目列表区块的数据。
     * Get project list for block.
     *
     * @param  string   $status
     * @param  int      $projectID
     * @param  string   $orderBy
     * @param  int      $limit
     * @param  string   $excludedModel
     * @access public
     * @return object[]
     */
    public function getOverviewList(string $status = '', int $projectID = 0, string $orderBy = 'id_desc', int $limit = 10, string $excludedModel = ''): array
    {
        /* Get project list by query. */
        $projects = $this->projectTao->fetchProjectListByQuery($status, $projectID, $orderBy, $limit, $excludedModel);
        if(empty($projects)) return array();
        if($projectID)
        {
            if(empty($projects[$projectID])) return array();

            $projects = array($projectID => $projects[$projectID]);
        }

        /* Get bug, task and story summary under the project. */
        $projectIdList = array_keys($projects);
        $bugSummary    = $this->projectTao->getTotalBugByProject($projectIdList);
        $taskSummary   = $this->projectTao->getTotalTaskByProject($projectIdList);
        $storySummary  = $this->projectTao->getTotalStoriesByProject($projectIdList);

        /* Set project attribute. */
        $today = helper::today();
        foreach($projects as $projectID => $project)
        {
            $project->leftBugs      = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->leftBugs             : 0;
            $project->allBugs       = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->allBugs              : 0;
            $project->doneBugs      = isset($bugSummary[$projectID])   ? $bugSummary[$projectID]->doneBugs             : 0;
            $project->allStories    = isset($storySummary[$projectID]) ? $storySummary[$projectID]->allStories         : 0;
            $project->doneStories   = isset($storySummary[$projectID]) ? $storySummary[$projectID]->doneStories        : 0;
            $project->leftStories   = isset($storySummary[$projectID]) ? $storySummary[$projectID]->leftStories        : 0;
            $project->leftTasks     = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->leftTasks           : 0;
            $project->allTasks      = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->allTasks            : 0;
            $project->waitTasks     = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->waitTasks           : 0;
            $project->doingTasks    = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->doingTasks          : 0;
            $project->rndDoneTasks  = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->doneTasks           : 0;
            $project->liteDoneTasks = isset($taskSummary[$projectID])  ? $taskSummary[$projectID]->litedoneTasks       : 0;

            /* Judge whether the project is delayed. */
            if($project->status != 'done' && $project->status != 'closed' && $project->status != 'suspended')
            {
                $delay = helper::diffDate($today, $project->end);
                if($delay > 0) $project->delay = $delay;
            }
        }

        return $projects;
    }

    /**
     * 获取瀑布项目的进度。
     * Get waterfall project progress.
     *
     * @param  array  $projectIdList
     * @param  string $mode waterfall|research
     * @access public
     * @return array
     */
    public function getWaterfallProgress(array $projectIdList, string $mode = 'waterfall'): array
    {
        /* Get stage list. */
        $stageGroup = $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.type')->in('stage')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.project')->in($projectIdList)
            ->andWhere('t2.model')->eq($mode)
            ->fetchGroup('project', 'id');

        /* Get hours information. */
        $totalHour = $this->dao->select("t1.project, t1.execution, ROUND(SUM(if(t1.status !='closed' && t1.status !='cancel', t1.`left`, 0)), 2) AS totalLeft, ROUND(SUM(t1.`consumed`), 1) AS totalConsumed")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in(array_keys($stageGroup))
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->groupBy('t1.project,t1.execution')
            ->fetchGroup('project', 'execution');

        /* Compute waterfall project progress. */
        $progressList = array();
        foreach($stageGroup as $projectID => $stageList)
        {
            $projectConsumed = 0;
            $projectLeft     = 0;
            foreach($stageList as $stageID => $stage)
            {
                if($stage->project != $projectID) continue;

                $projectConsumed += isset($totalHour[$projectID][$stageID]) ? (float)$totalHour[$projectID][$stageID]->totalConsumed : 0;
                $projectLeft     += isset($totalHour[$projectID][$stageID]) ? round((float)$totalHour[$projectID][$stageID]->totalLeft, 1) : 0;
            }

            $progressList[$projectID] = ($projectConsumed + $projectLeft) == 0 ? 0 : floor($projectConsumed / ($projectConsumed + $projectLeft) * 1000) / 1000 * 100;
        }

        return $progressList;
    }

    /**
     * 获取瀑布项目的计划值、挣值和实际成本。
     * Get waterfall general PV, EV and AC.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getWaterfallPVEVAC(int $projectID): array
    {
        $executions = $this->dao->select('id,begin,end,realEnd,status')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('vision')->eq($this->config->vision)->andWhere('project')->eq($projectID)->fetchAll('id');
        $stmt       = $this->dao->select('id,status,estimate,consumed,`left`,closedReason')->from(TABLE_TASK)->where('execution')->in(array_keys($executions))->andWhere("isParent")->eq(0)->andWhere("deleted")->eq(0)->andWhere('status')->ne('cancel')->query();

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
     * 获取项目的工时信息。
     * Get project workhour info.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getWorkhour(int $projectID): object
    {
        $totalEstimate = $this->dao->select('ROUND(SUM(t1.estimate), 1) AS totalEstimate')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->fetch('totalEstimate');

        $totalConsumed = $this->dao->select('ROUND(SUM(t1.consumed), 1) AS totalConsumed')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->fetch('totalConsumed');

        $totalLeft = $this->dao->select('ROUND(SUM(t1.`left`), 1) AS totalLeft')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t2.project')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t1.status')->ne('closed,cancel')
            ->fetch('totalLeft');

        $workhour = new stdclass();
        $workhour->totalHours = $this->dao->select('sum(t1.days * t1.hours) AS totalHours')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root=t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.account=t3.account')
            ->where('t2.id')->in($projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t3.deleted')->eq(0)
            ->fetch('totalHours');

        $workhour->totalHours    = empty($workhour->totalHours) ? 0 : $workhour->totalHours;
        $workhour->totalEstimate = empty($totalEstimate) ? 0 : $totalEstimate;
        $workhour->totalConsumed = empty($totalConsumed) ? 0 : $totalConsumed;
        $workhour->totalLeft     = empty($totalLeft) ? 0 : $totalLeft;

        return $workhour;
    }

    /**
     * 获取给定项目的总计消耗工时。
     * Get projects consumed info.
     *
     * @param  array  $projectID
     * @param  string $time
     * @access public
     * @return object[]
     */
    public function getProjectsConsumed(array $projectIdList, string $time = ''): array
    {
        $totalConsumeds = $this->dao->select('t2.project,ROUND(SUM(t1.consumed), 1) AS totalConsumed')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on("t1.objectID=t2.id and t1.objectType = 'task'")
            ->where('t2.project')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.parent')->lt(1)
            ->beginIF($time == 'THIS_YEAR')->andWhere('LEFT(t1.`date`, 4)')->eq(date('Y'))->fi()
            ->groupBy('t2.project')
            ->fetchAll('project');

        $projects = array();
        foreach($projectIdList as $projectID)
        {
            $project = new stdClass();
            $project->totalConsumed = isset($totalConsumeds[$projectID]->totalConsumed) ? $totalConsumeds[$projectID]->totalConsumed : 0;
            $projects[$projectID]   = $project;
        }

        return $projects;
    }


    /**
     * 生成项目下拉框跳转链接
     * Create the link from module,method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getProjectLink(string $module, string $method, int $projectID, string $extra = '') :string
    {
        $link    = helper::createLink('project', 'index', "projectID=%s");

        $project = $this->projectTao->fetchProjectInfo($projectID);

        if(empty($project->multiple)) return $link;

        if($module == 'build' && $method !== 'create')
        {
            $fromModule = $this->app->tab == 'project' ? 'projectbuild' : 'project';
            $fromMethod = $this->app->tab == 'project' ? 'browse' : 'build';

            return helper::createLink($fromModule, $fromMethod, "projectID=%s");
        }

        if(!empty($this->config->project->linkMap->$module[$method]))
        {
            $linkParams = $this->config->project->linkMap->$module[$method];
            if(!$linkParams[0]) $linkParams[0] = $module;
            if(!$linkParams[1]) $linkParams[1] = $method;
            if(strpos($linkParams[2], '{extra}') !== false) $linkParams[2] = str_replace('{extra}', $extra, $linkParams[2]);

            return helper::createLink($linkParams[0], $linkParams[1], $linkParams[2]) . $linkParams[3];
        }

        if(!empty($this->config->project->linkMap->$module['']))
        {
            $linkParams = $this->config->project->linkMap->$module[''];
            if(!$linkParams[0]) $linkParams[0] = $module;

            return helper::createLink($linkParams[0], $linkParams[1], $linkParams[2]) . $linkParams[3];
        }
        if($this->config->edition != 'open')
        {
            $flow = $this->loadModel('workflow')->getByModule($module);
            if(!empty($flow->app) && in_array($flow->app, array('scrum', 'waterfall', 'kanbanProject'))) $flow->app = 'project';
            if(!empty($flow) && $flow->buildin == '0') return helper::createLink('flow', 'ajaxSwitchBelong', "objectID=%s&moduleName=$module") . "#app=$flow->app";
        }

        if(in_array($module, $this->config->waterfallModules)) return helper::createLink($module, 'browse', "projectID=%s");

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
        $storyCount = $this->dao->select('count(t2.story) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t2.project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('storyCount');

        $bugCount = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)
             ->where('project')->in($projectID)
             ->andWhere('deleted')->eq(0)
             ->fetch('bugCount');

        $taskCount = $this->dao->select("COUNT(1) AS `count`,
            SUM(CASE WHEN `status` = 'wait' THEN 1 ELSE 0 END) AS `waitCount`,
            SUM(CASE WHEN `status` = 'doing' THEN 1 ELSE 0 END) AS `doingCount`,
            SUM(CASE WHEN `finishedBy` != '' THEN 1 ELSE 0 END) AS `finishedCount`")
            ->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq('0')
            ->fetch();

        $delayedCount = $this->dao->select('COUNT(id) AS `count`')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deadline')->notZeroDate()
            ->andWhere('deadline')->lt(helper::today())
            ->andWhere('status')->in('wait,doing')
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        $statData = new stdclass();
        $statData->storyCount    = $storyCount;
        $statData->bugCount      = $bugCount;
        $statData->taskCount     = $taskCount->count;
        $statData->waitCount     = $taskCount->waitCount;
        $statData->doingCount    = $taskCount->doingCount;
        $statData->finishedCount = $taskCount->finishedCount;
        $statData->delayedCount  = $delayedCount;

        return $statData;
    }

    /**
     * Get project pairs.
     *
     * @param  bool   $ignoreVision
     * @param  string $params
     * @access public
     * @return object
     */
    public function getPairs(bool $ignoreVision = false, string $params = '')
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$ignoreVision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->beginIF(strpos($params, 'noproduct') !== false)->andWhere('hasProduct')->eq(0)->fi()
            ->beginIF(strpos($params, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(strpos($params, 'nosprint') !== false)->andWhere('multiple')->eq('0')->fi()
            ->beginIF(strpos($params, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(strpos($params, 'nokanban') !== false)->andWhere('model')->ne('kanban')->fi()
            ->beginIF(!$this->app->user->admin && strpos($params, 'haspriv') !== false)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchPairs();
    }

    /**
     * Get project pairs by programID.
     *
     * @param  int          $programID
     * @param  string       $status    all|wait|doing|suspended|closed|noclosed|noprogram
     * @param  bool         $isQueryAll
     * @param  string       $orderBy
     * @param  string       $excludedModel
     * @param  string|array $model
     * @param  string       $param multiple|product
     * @access public
     * @return array
     */
    public function getPairsByProgram(int $programID = 0, string $status = 'all', bool $isQueryAll = false, string $orderBy = 'order_asc', string $excludedModel = '', string|array $model = '', string $param = ''): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProjectPairs();

        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!empty($programID))->andWhere('path')->like("%,$programID,%")->fi()
            ->beginIF($programID === 0 && $status == 'noprogram')->andWhere('parent')->eq(0)->fi()
            ->beginIF($status != 'all' && $status != 'noclosed')->andWhere('status')->eq($status)->fi()
            ->beginIF($excludedModel)->andWhere('model')->ne($excludedModel)->fi()
            ->beginIF($model)->andWhere('model')->in($model)->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('multiple')->eq(1)->fi()
            ->beginIF(strpos($param, 'product') !== false)->andWhere('hasProduct')->eq(1)->fi()
            ->beginIF($status == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin && !$isQueryAll)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 获取关联产品的项目列表信息。
     * Get product list information for linked products.
     *
     * @param  array  $productIdList
     * @param  string $status
     * @access public
     * @return array
     */
    public function getGroupByProduct(array $productIdList = array(), string $status = ''): array
    {
        return $this->dao->select('t1.product,t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!empty($productIdList))->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF(!empty($status))->andWhere('t2.status')->in($status)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchGroup('product');
    }

    /**
     * Get all the projects under the program set to which an project belongs.
     *
     * @param object $project
     * @access public
     * @return void
     */
    public function getBrotherProjects(object $project): array
    {
        if($project->parent == 0) return array($project->id => $project->id);

        $projectIds    = array_filter(explode(',', $project->path));
        $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('id')->in($projectIds)
            ->andWhere('`type`')->eq('program')
            ->orderBy('grade desc')
            ->fetch();

        return $this->dao->select('id')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere("CONCAT(',', path, ',')")->like("%,{$parentProgram->id},%")
            ->fetchPairs('id');
    }

    /**
     * Get project by id list.
     *
     * @param  array  $projectIdList
     * @param  string $mode all
     * @access public
     * @return object
     */
    public function getByIdList(array $projectIdList = array(), string $mode = ''): array
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
    public function getPairsByIdList(array $projectIdList = array(), string $model = '', string $param = ''): array
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
     * 根据项目ID获取关联产品及分支
     * Get branches by project id.
     *
     * @param  int $projectID
     *
     * @access public
     * @return array
     */
    public function getBranchesByProject(int $projectID): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getBranchesByProject();

        return $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->fetchGroup('product', 'branch');
    }

    /**
     * 根据项目ID获取分支分组。
     * Get branch groups.
     *
     * @param  int   $projectID
     * @param  array $productIdList
     * @access public
     * @return array
     */
    public function getBranchGroup(int $projectID, array $productIdList): array
    {
        return $this->dao->select('t1.product as productID, t1.branch as branchID, t2.*')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t2.deleted')->eq(0)
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
     * 获取项目与执行的键值对
     * Get project and execution pairs.
     *
     * @param  string $multiple
     * @param  string $status
     * @access public
     * @return array
     */
    public function getProjectExecutionPairs(string $multiple = '0', string $status = 'all'): array
    {
        return $this->dao->select('project, id')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('multiple')->eq($multiple)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->beginIF(!in_array($status, array('all', 'undone')))->andWhere('status')->eq($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', vision, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchPairs();
    }

    /**
     * 根据状态和项目模型获取项目列表。
     * Get project list by query.
     *
     * @param  string $status
     * @param  string $order
     * @param  int    $limit
     * @param  string $excludedModel
     * @access public
     * @return void
     */
    public function getProjectList(string $status, string $order, int $limit, string $excludedModel): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getProjectStats();
        return $this->projectTao->fetchProjectListByQuery($status, 0, $order, $limit, $excludedModel);
    }

    /**
     * 获取项目下的执行数据。
     * Get execution data under the project.
     *
     * @param  array    $projectIdList
     * @access public
     * @return object[]
     */
    public function getExecutionList(array $projectIdList = array()): array
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('project')->in($projectIdList)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->fetchAll('id');
    }

    /**
     * 根据项目类型生成权限数据。
     * Get project priv data according by the project type.
     *
     * @param  string $model  scrum|waterfall|noSprint|agileplus|waterfallplus
     * @access public
     * @return object|false
     */
    public function getPrivsByModel(string $model = 'waterfall'): object|false
    {
        if(!isset($this->config->programPriv->$model)) return false;

        if($model == 'noSprint') $this->config->project->includedPriv = $this->config->project->noSprintPriv;

        $this->app->loadLang('group');
        $privs = new stdclass();
        foreach($this->lang->resource as $module => $methods)
        {
            if(empty($methods)) continue;

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
     * 构造项目搜索表单配置项。
     * Build search form.
     *
     * @param int     $queryID
     * @param string  $actionURL
     *
     * @return void
     * */
    public function buildSearchForm(int $queryID, string $actionURL)
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
     * 构造项目版本的搜索表单配置。
     * Build project build search form.
     *
     * @param  array  $products
     * @param  int    $queryID
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type project|execution
     * @access public
     * @return bool
     */
    public function buildProjectBuildSearchForm(array $products, int $queryID, int $projectID, int $productID, string $type = 'project'): bool
    {
        /* Set search param. */
        $project = $this->projectTao->fetchProjectInfo($projectID);
        if(!$project) return false;

        $this->loadModel('build');
        if(!$project->hasProduct) unset($this->config->build->search['fields']['product']);
        $product = $productID ? $this->loadModel('product')->getByID($productID) : '';
        if($product && $product->type != 'normal')
        {
            $this->loadModel('branch');
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->branch->getPairs($product->id, '', $projectID);
            $this->config->build->search['fields']['branch'] = sprintf($this->lang->build->branchName, $this->lang->product->branchName[$product->type]);
            $this->config->build->search['params']['branch'] = array('operator' => '=', 'control' => 'select', 'values' => $branches);
        }

        /* If there is an execution, set the execution filter item. */
        if($type == 'project' && $project->multiple)
        {
            $executionPairs = $this->loadModel('execution')->getByProject($project->id, 'all', 0, true, $project->model == 'waterfall');
            $this->config->build->search['fields']['execution'] = zget($this->lang->project->executionList, $project->model);
            $this->config->build->search['params']['execution'] = array('operator' => '=', 'control' => 'select', 'values' => $executionPairs);
        }

        $this->config->build->search['module']    = $type == 'project' ? 'projectBuild' : 'executionBuild';
        $this->config->build->search['actionURL'] = helper::createLink($this->app->rawModule, $this->app->rawMethod, "projectID=$projectID&type=bysearch&queryID=myQueryID");
        $this->config->build->search['queryID']   = (int)$queryID;
        $this->config->build->search['params']['product']['values'] = $products;
        $this->config->build->search['params']['system']['values']  = $this->loadModel('system')->getPairs($queryID ? 0 : (int)$productID, '0');

        $this->loadModel('search')->setSearchParams($this->config->build->search);
        return true;
    }

    /**
     * 根据项目集和模型获取项目列表(列表索引为项目编号)。
     * Get project pairs by model and project.
     *
     * @param  string|array  $model all|scrum|waterfall|kanban
     * @param  string        $param noclosed
     * @param  int           $projectID
     * @param  bool          $pairs
     * @access public
     * @return array         array(projectID => projectName, ...)
     */
    public function getPairsByModel(string|array $model = 'all', string $param = '', int $projectID = 0, bool $pairs = true): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProjectPairs();

        /* Get project list. */
        $projects = $this->projectTao->fetchProjectListByQuery(strpos($param, 'noclosed') !== false ? 'unclosed' : 'all', $projectID);

        if(is_string($model) && $model == 'agileplus')     $model = array('scrum', 'agileplus');
        if(is_string($model) && $model == 'waterfallplus') $model = array('waterfall', 'waterfallplus');

        /* Set first program to the project attribute. */
        $model    = is_string($model) && $model == 'all' ? array() : (array)$model;
        $multiple = strpos($param, 'multiple') !== false;
        foreach($projects as $projectID => $project)
        {
            if(($model && !in_array($project->model, $model)) || ($multiple && !$project->multiple))
            {
                unset($projects[$projectID]);
                continue;
            }

            list($programID) = explode(',', trim($project->path, ','));
            $projects[$projectID]->program = $programID;
        }

        $programs = $this->loadModel('program')->getPairsByList(toIntArray(array_column($projects, 'program')));

        /* Sort by project order in the program list. */
        $allProjects = array();
        foreach($programs as $programID => $program) $allProjects[$programID] = array();
        foreach($projects as $project)
        {
            $programID = zget($project, 'program', '');

            $projectName = $project->name;
            if($this->config->systemMode == 'ALM' && $programID != $project->id) $projectName = zget($programs, $programID, '') . ' / ' . $projectName;
            $project->name = $projectName;

            $allProjects[$programID][] = $project;
        }

        $projectPairs = array();
        foreach($allProjects as $programID => $projects)
        {
            foreach($projects as $project) $projectPairs[$project->id] = $pairs ? $project->name : $project;
        }
        return $projectPairs;
    }

    /**
     * 根据项目ID获取需求信息。
     * Get stories by project id.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getStoriesByProject(int $projectID = 0): array
    {
        return $this->dao->select("t2.product, t2.branch, GROUP_CONCAT(t2.story) as storyIDList")->from(TABLE_STORY)->alias('t1')
           ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
           ->where('t1.deleted')->eq(0)
           ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
           ->groupBy('t2.product, t2.branch')
           ->fetchGroup('product', 'branch');
    }

    /**
     * 获取项目集列表。
     * Get the program tree of project.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProgramTree(string $browseType): array
    {
        /* Get program list. */
        $programsList = $this->dao->select('id,name,parent')->from(TABLE_PROGRAM)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->andWhere('status')->ne('closed')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll();

        /* Init tree data. */
        $programs = array();
        foreach($programsList as $index => $program)
        {
            $programs[$index] = new stdClass();
            $programs[$index]->id     = $program->id;
            $programs[$index]->name   = $program->name;
            $programs[$index]->parent = $program->parent;
            $programs[$index]->url    = helper::createLink('project', 'browse', "programID={$program->id}&browseType={$browseType}");
        }
        return $programs;
    }

    /**
     * 创建项目后，新增团队成员.
     * Add team members after create a project.
     *
     * @param  int       $projectID
     * @param  object    $project
     * @param  array     $members
     * @access protected
     * @return bool
     */
    protected function addTeamMembers(int $projectID, object $project, array $members): bool
    {
        /* Set team of project. */
        array_push($members, $project->PM, $project->openedBy);
        $members     = array_unique($members);
        $roles       = $this->loadModel('user')->getUserRoles(array_values($members));
        $teamMembers = array();

        $this->loadModel('execution');
        foreach($members as $account)
        {
            if(empty($account)) continue;

            $member = new stdClass();
            $member->root    = $projectID;
            $member->type    = 'project';
            $member->join    = helper::now();
            $member->days    = zget($project, 'days', 0);
            $member->hours   = $this->config->execution->defaultWorkhours;
            $member->account = $account;
            $member->role    = zget($roles, $account, '');
            $teamMembers[$account] = $member;
        }
        $this->execution->addProjectMembers($projectID, $teamMembers);

        return !dao::isError();
    }

    /**
     * 创建一个项目。
     * Create a project.
     *
     * @param  object   $project
     * @param  object   $postData
     * @access public
     * @return int|bool
     */
    public function create(object $project, object $postData): int|bool
    {
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->create['id'], $this->post->uid);

        $this->projectTao->doCreate($project);
        if(dao::isError()) return false;

        $projectID = $this->dao->lastInsertId();
        /* Add project whitelist. */
        $whitelist = explode(',', $project->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);

        $program = $project->parent ? $this->getByID((int)$project->parent) : new stdclass();
        $this->projectTao->createDocLib($projectID, $project, $program);
        $this->addTeamMembers($projectID, $project, array($project->openedBy));
        if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) $this->projectTao->createMilestoneReport($projectID);

        if($project->hasProduct && empty($postData->rawdata->newProduct))
        {
            $this->updateProducts($projectID);
            /* If $_POST has product name, create it. */
            $linkedProductsCount = $this->projectTao->getLinkedProductsCount($project, $postData->rawdata);
        }

        $needCreateProduct = (!$project->hasProduct || isset($postData->rawdata->newProduct) || empty($linkedProductsCount)) && empty($project->isTpl);
        if($needCreateProduct && !$this->projectTao->createProduct($projectID, $project, $postData, $program)) return false;

        /* Save order. */
        $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec();
        $this->file->updateObjectID((string)$this->post->uid, $projectID, 'project');
        $this->loadModel('program')->setTreePath($projectID);

        /* Add project admin. */
        if(empty($project->isTpl)) $this->projectTao->addProjectAdmin($projectID);

        if($project->acl != 'open') $this->loadModel('user')->updateUserView(array($projectID), 'project');

        if(empty($project->multiple) and $project->model != 'waterfall' and $project->model != 'waterfallplus') $this->loadModel('execution')->createDefaultSprint($projectID);

        return $projectID;
    }

    /**
     * 检查输入的$product和$branch变量是否合规。
     * Check branch and product valid by project.
     *
     * @param  int    $parent
     * @param  array  $products
     * @param  array  $branch
     * @access public
     * @return bool
     */
    public function checkBranchAndProduct(int $parent, array $products, array $branch): bool
    {
        $topProgramID     = $this->loadModel('program')->getTopByID($parent);
        $multipleProducts = $this->loadModel('product')->getMultiBranchPairs((int)$topProgramID);
        foreach($products as $index => $productID)
        {
            if(isset($multipleProducts[$productID]))
            {
                foreach($branch[$index] as $branchID)
                {
                    if($branchID == '')
                    {
                        dao::$errors["branch[{$index}][]"] = $this->lang->project->error->emptyBranch;
                    }
                }
            }
        }
        return !dao::isError();
    }

    /**
     * 检查执行的起止日期是否小于项目的起止日期。
     * Check if execution's start and end dates in project's start and end dates.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function checkDates($projectID, $project): bool
    {
        $executionsCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_PROJECT)
            ->where('project') ->eq($projectID)
            ->andWhere('deleted') ->eq('0')
            ->fetch('count');
        if(empty($executionsCount))return true;

        $maxExecutionEnd = $this->dao->select('`end` as maxEnd')->from(TABLE_PROJECT)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('end_desc')
            ->fetch();

        $minExecutionBegin = $this->dao->select('`begin` as minBegin')->from(TABLE_PROJECT)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('begin_asc')
            ->fetch();

        if($maxExecutionEnd   && $project->end   < $maxExecutionEnd->maxEnd)     dao::$errors['end']   = sprintf($this->lang->project->endGreatEqualExecution,   $maxExecutionEnd->maxEnd);
        if($minExecutionBegin && $project->begin > $minExecutionBegin->minBegin) dao::$errors['begin'] = sprintf($this->lang->project->beginLessEqualExecution, $minExecutionBegin->minBegin);

        return !dao::isError();
    }

    /**
     * 更新项目关联的团队成员列表。
     * update teammembers while update project.
     *
     * @param  object $project
     * @param  object $oldProject
     * @param  array  $newMembers
     * @access public
     * @return bool
     */
    public function updateTeamMembers(object $project, object $oldProject, array $newMembers = array()): bool
    {
        /* Get old project's team and roles. */
        $projectID  = (int)$oldProject->id;
        $oldMembers = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');

        if(empty($newMembers)) $newMembers = array_keys($oldMembers);
        array_push($newMembers, $project->PM);
        $newMembers = array_unique($newMembers);
        $roles      = $this->loadModel('user')->getUserRoles(array_values($newMembers));

        /* Delete members while old model is kanban. */
        if($oldProject->model == 'kanban')
        {
            $deleteMembers = array_diff(array_keys($oldMembers), array_values($newMembers));
            $this->projectTao->deleteMembers($projectID, $oldProject->openedBy, $deleteMembers);
        }

        /* Init member default for update members. */
        $member = new stdclass();
        $member->type  = 'project';
        $member->root  = $projectID;
        $member->join  = helper::today();
        $member->days  = zget($project, 'days', 0);
        $member->hours = $this->config->execution->defaultWorkhours;

        /* Prepare $addMembers for addProjectMembers(). */
        $addMembers = array();
        foreach($newMembers as $account)
        {
            if(empty($account) or isset($oldMembers[$account])) continue;

            $member->account = $account;
            $member->role    = zget($roles, $account, '');

            $addMembers[$account] = $member;
        }

        /* Add members. */
        if(!empty(count($addMembers))) $this->loadModel('execution')->addProjectMembers($projectID, $addMembers);
        if(!isset($_POST['teamMembers'])) $_POST['teamMembers'] = $newMembers;

        return !dao::isError();
    }

    /**
     * 更新此项目下或影子产品下的白名单列表。
     * Update whitelist by project.
     *
     * @param  object $project
     * @param  object $oldProject
     * @access public
     * @return bool
     */
    public function updateWhitelist(object $project, object $oldProject): bool
    {
        /* 对比新旧白名单检查是否需要更新白名单。*/
        /* Check if whitelist shoud update .*/
        $projectID    = $oldProject->id;
        $whitelist    = array_filter(explode(',', (string)$project->whitelist));
        $oldWhitelist = array_filter(explode(',', (string)$oldProject->whitelist));
        if(count($oldWhitelist) != count($whitelist) || !empty(array_diff($oldWhitelist, $whitelist)))
        {
            if(!$oldProject->hasProduct)
            {
                $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)
                    ->where('project')->eq($projectID)
                    ->fetchPairs();
                $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', current($linkedProducts));
            }
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
        }

        return !dao::isError();
    }

    /**
     * 更新用户视图。
     * Update user view.
     *
     * @param  int    $projectID
     * @param  string $acl
     * @access public
     * @return bool
     */
    public function updateUserView(int $projectID, string $acl): bool
    {
        if($acl == 'open') return true;

        $this->loadModel('user')->updateUserView(array($projectID), 'project');
        $executions = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->fetchPairs();

        if($executions) $this->user->updateUserView($executions, 'sprint');

        return !dao::isError();
    }

    /**
     * 更新项目下的所有产品的阶段。
     * Update product stage by project.
     *
     * @param  int    $projectID
     * @param  object $postProductData
     * @access public
     * @return bool
     */
    public function updateProductStage(int $projectID, ?object $postProductData = null): bool
    {
        if(empty($postProductData)) return false;

        $stageParis    = $this->loadModel('execution')->getPairs($projectID);
        $stageProducts = $this->dao->select('project, product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_keys($stageParis))
            ->fetchPairs('project', 'product');

        $products = zget($postProductData, 'products', array());
        $branches = zget($postProductData, 'branch', array(0));
        $plans    = zget($postProductData, 'plans',  array());

        $productBranches = array();
        foreach($products as $i => $productID) $productBranches[$productID] = zget($branches, $i, array(0));

        foreach($stageProducts as $stageID => $productID)
        {
            foreach($productBranches[$productID] as $branchID)
            {
                $data = new stdclass();
                $data->project = (int)$stageID;
                $data->product = (int)$productID;
                $data->branch  = (int)$branchID;
                $data->plan    = isset($plans[$productID]) ? implode(',', $plans[$productID]) : '';
                $data->plan    = trim($data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";

                $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 更新项目。
     * Update project.
     *
     * @param  object      $project
     * @param  object      $oldProject
     * @param  object      $postProductData
     * @access public
     * @return array|false
     */
    public function update(object $project, object $oldProject, ?object $postProductData = null): array|false
    {
        /* 通过主键查老项目信息, 处理父节点和图片字段。*/
        /* Fetch old project's info and dispose parent and file info. */
        $projectID = $oldProject->id;
        if(!isset($project->parent)) $project->parent = $oldProject->parent;
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->edit['id'], $this->post->uid);

        /* 若此项目为多迭代项目， 检查起止日期不得小于迭代的起止日期。*/
        /* If this project has multiple stage, check if execution's start and end dates in project's start and end dates. */
        if($oldProject->multiple && !$this->checkDates($projectID, $project)) return false;

        /* 如果没有传入项目管理方式，则用之前的管理方式。*/
        /* If no project management method is passed, the project management method is used. */
        if(empty($project->model)) $project->model = $oldProject->model;

        /* 更新项目表。*/
        /* Update project table. */
        if(!$this->projectTao->doUpdate($projectID, $project)) return false;

        /* 更新项目的关联信息。*/
        /* Update relation info of this project. */
        $this->updateUserView($projectID, $project->acl);                    // 更新用户视图。
        $this->updateShadowProduct($project, $oldProject);                   // 更新影子产品关联信息。
        $this->updateWhitelist($project, $oldProject);                       // 更新关联的白名单列表。

        $this->updatePlans($projectID, (array)$this->post->plans); // 更新关联的计划列表。
        if($oldProject->hasProduct > 0) $this->updateProducts($projectID, (array)$this->post->products, $postProductData); // 更新关联的产品列表。
        $this->updateTeamMembers($project, $oldProject, zget($_POST, 'teamMembers', array())); // 更新关联的用户信息。
        if($oldProject->stageBy == 'product' && in_array($oldProject->model, array('waterfall', 'waterfallplus'))) $this->updateProductStage($projectID, $postProductData); // 更新多套阶段

        $this->file->updateObjectID((string)$this->post->uid, $projectID, 'project'); // 通过uid更新文件id。

        if($oldProject->parent != $project->parent) $this->loadModel('program')->processNode($projectID, (int)$project->parent, $oldProject->path, $oldProject->grade); // 更新项目从属路径。
        if($oldProject->storyType != $project->storyType)
        {
            /* 编辑项目时如果取消关联需求类型，则把对应类型的需求移除。 */
            $unlinkType = array_diff(explode(',', $oldProject->storyType), explode(',', $project->storyType));
            if($unlinkType) $this->unlinkStoryByType($projectID, $unlinkType);
        }
        if(empty($oldProject->multiple) and !in_array($oldProject->model, array('waterfall', 'waterfallplus'))) $this->loadModel('execution')->syncNoMultipleSprint($projectID); // 无迭代的非瀑布项目需要更新。
        if(in_array($this->config->edition, array('max', 'ipd')) && $oldProject->workflowGroup != $project->workflowGroup) $this->replaceDeliverable($projectID, $project->workflowGroup); // 更新交付物关联信息。

        if($oldProject->model != $project->model && !in_array($oldProject->model, array('waterfall', 'waterfallplus', 'ipd')) && in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            $this->projectTao->createMilestoneReport($projectID);
        }

        if(dao::isError()) return false;
        return common::createChanges($oldProject, $project);
    }

    /**
     * 批量更新项目。
     * Batch update projects.
     *
     * @param  array       $data
     * @access public
     * @return array|false
     */
    public function batchUpdate(array $data): array|false
    {
        $projects     = array();
        $allChanges   = array();
        $oldProjects  = $this->getByIdList(array_keys($data));
        $projects = $this->projectTao->buildBatchUpdateProjects($data, $oldProjects);

        $this->loadModel('execution');
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach($projects as $projectID => $project)
        {
            $oldProject = $oldProjects[$projectID];

            $this->projectTao->doUpdate($projectID, $project);

            if(dao::isError())
            {
                $errors = dao::getError();
                foreach($errors as $key => $error) dao::$errors[$key][0] = 'ID' . $projectID . $error[0];

                return false;
            }

            if(!dao::isError())
            {
                /* 无产品项目信息变更后更新影子产品的相关字段. */
                if(!$oldProject->hasProduct and ($oldProject->name != $project->name or $oldProject->parent != $project->parent or $oldProject->acl != $project->acl)) $this->updateShadowProduct($project, $oldProject);

                if(isset($project->parent))
                {
                    $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                    $this->updateProductProgram($oldProject->parent, $project->parent, $linkedProducts);
                    if($oldProject->parent != $project->parent) $this->loadModel('program')->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);
                }

                /* When acl is open, white list set empty. When acl is private,update user view. */
                if($project->acl == 'open') $this->loadModel('personnel')->updateWhitelist(array(), 'project', $projectID);
                if($project->acl != 'open') $this->loadModel('user')->updateUserView(array($projectID), 'project');
                $this->executeHooks($projectID);

                if(empty($oldProject->multiple) and $oldProject->model != 'waterfall') $this->execution->syncNoMultipleSprint($projectID);
            }
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }

        return $allChanges;
    }

    /**
     * 开始项目并更改其状态.
     * start and update a project.
     *
     * @param  int    $projectID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function start(int $projectID, object $postData): array|false
    {
        $oldProject = $this->getById($projectID);

        $project = $this->loadModel('file')->processImgURL($postData, $this->config->project->editor->start['id'], $this->post->uid);

        $this->projectTao->doStart($projectID, $project);

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError())
        {
            if(count(dao::$errors['realBegan']) > 1) dao::$errors['realBegan'] = dao::$errors['realBegan'][0];
            return false;
        }

        $this->recordFirstEnd($projectID);

        if(!$oldProject->multiple) $this->projectTao->changeExecutionStatus($projectID, 'start');
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

        if(!$oldProject->multiple) $this->projectTao->changeExecutionStatus($projectID, 'suspend');
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
        if($project->begin > $project->end)
        {
            dao::$errors['end'] = $this->lang->project->error->endLessBegin;
            return false;
        }

        $oldProject = $this->projectTao->fetchProjectInfo($projectID);

        $daoSuccess = $this->projectTao->doActivate($projectID, $project);
        if(!$daoSuccess) return false;

        if(empty($oldProject->multiple) and $oldProject->model != 'waterfall') $this->loadModel('execution')->syncNoMultipleSprint($projectID);

        /* Update start and end date of tasks in this project. */
        if($project->readjustTask)
        {
            $tasks = $this->projectTao->fetchUndoneTasks($projectID);
            $this->projectTao->updateTasksStartAndEndDate($tasks, $oldProject, $project);
        }

        /* Activate the shadow product of the project. (only change product status) */
        if(!$oldProject->hasProduct)
        {
            $productID = $this->loadModel('product')->getProductIDByProject($projectID);

            $product = new stdclass();
            $product->status = $this->config->vision == 'or' ? 'wait' : 'normal';
            $this->product->activate($productID, $product);
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
        if(!$oldProject->multiple) $this->projectTao->changeExecutionStatus($projectID, 'close');

        /* Close the shadow product of the project. */
        if(!$oldProject->hasProduct)
        {
            $productID = $this->loadModel('product')->getProductIDByProject($projectID);

            $product = new stdclass();
            $product->status = 'closed';
            $this->product->close($productID, $product);
        }

        $this->loadModel('score')->create('project', 'close', $oldProject);
        return common::createChanges($oldProject, $project);
    }

    /**
     * 如果是无产品项目，更新影子产品信息。
     * Update shadow product.
     *
     * @param  object $project
     * @param  object $oldProject
     * @access public
     * @return bool
     */
    public function updateShadowProduct(object $project, object $oldProject): bool
    {
        /* If this is a project without product, update shadow product's info. */
        if($oldProject->hasProduct) return true;

        /* If oldProject has no product and name or parent or acl has changed, update shadow product. */
        if($oldProject->name != $project->name || $oldProject->parent != $project->parent || $oldProject->acl != $project->acl)
        {
            $product    = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($oldProject->id)->fetch('product');
            $topProgram = !empty($project->parent) ? $this->loadModel('program')->getTopByID((int)$project->parent) : 0;

            /* Convert program acl to custom for product table */
            $productAcl = $project->acl;
            if($productAcl == 'program') $productAcl = 'private';

            $this->dao->update(TABLE_PRODUCT)
                ->set('name')->eq($project->name)
                ->set('program')->eq($topProgram)
                ->set('acl')->eq($productAcl)
                ->where('id')->eq($product)
                ->exec();
        }

        /* Update shadow product's status if need .*/
        if(isset($project->status) && $oldProject->status != $project->status && str_contains('doing,closed', $project->status))
        {
            $productID = $this->loadModel('product')->getProductIDByProject($oldProject->id);
            if($project->status == 'doing')  $this->product->activate($productID);
            if($project->status == 'closed') $this->product->close($productID);
        }

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
     * 更新产品的项目集。
     * Update the program of the product.
     *
     * @param  int    $oldProgram
     * @param  int    $newProgram
     * @param  array  $products
     * @access public
     * @return bool
     */
    public function updateProductProgram(int $oldProgram, int $newProgram, array $products): bool
    {
        /* Product belonging project set processing. */
        $oldTopProgram = $this->loadModel('program')->getTopByID($oldProgram);
        $newTopProgram = $this->program->getTopByID($newProgram);
        if($oldTopProgram != $newTopProgram)
        {
            $productList = $this->loadModel('product')->getByIdList($products);
            foreach($products as $productID)
            {
                $product = zget($productList, $productID, array());
                if(!$product) continue;

                unset($product->id);
                unset($product->code);
                if(empty($product->closedDate)) unset($product->closedDate);
                $product->program = $newTopProgram;
                $this->product->update($productID, $product);
            }
        }

        return !dao::isError();
    }

    /**
     * 删除项目和迭代下对应需求概念的关联关系。
     * Unlink the relationship between the project and the story concept under the iteration and the project.
     *
     * @param  int    $projectID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function unlinkStoryByType($projectID = 0, $storyType = '')
    {
        $idList = $this->dao->select('id')->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->fetchPairs();

        $idList[$projectID] = $projectID;

        $storyIdList = $this->dao->select('t1.story')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t2.type')->in($storyType)
            ->beginIF($projectID > 0)->andWhere('t1.project')->in($idList)->fi()
            ->fetchPairs();

        if($storyIdList) $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('story')->in($storyIdList)->beginIF($projectID > 0)->andWhere('project')->in($idList)->fi()->exec();
    }

    /**
     * 移除项目成员。
     * Unlink a member.
     *
     * @param  int    $projectID
     * @param  string $account
     * @param  bool   $removeExecution
     * @access public
     * @return bool
     */
    public function unlinkMember(int $projectID, string $account, bool $removeExecution = false): bool
    {
        $user    = $this->loadModel('user')->getById($account);
        $changes = array(array('field' => 'removeDiff', 'old' => '', 'new' => '', 'diff' => $user->realname));

        $this->projectTao->unlinkTeamMember($projectID, 'project', $account, $user->realname, $changes);

        $this->user->updateUserView(array($projectID), 'project', array($account));

        if($removeExecution)
        {
            $executions = $this->loadModel('execution')->getByProject($projectID, 'undone', 0, true);
            $this->projectTao->unlinkTeamMember(array_keys($executions), 'execution', $account, $user->realname, $changes);
            $this->user->updateUserView(array_keys($executions), 'sprint', array($account));
        }

        $linkedProducts = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', array($account));

        return !dao::isError();
    }

    /**
     * 维护项目团队成员。
     * Manage team members.
     *
     * @param  int    $projectID
     * @param  array  $members
     * @access public
     * @return bool
     */
    public function manageMembers(int $projectID, array $members): bool
    {
        $project = $this->projectTao->fetchProjectInfo($projectID);
        $oldJoin = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq('project')->fetchPairs();

        /* Check fields. */
        foreach($members as $key => $member)
        {
            if(empty($member->account)) continue;

            if(!empty($project->days) and (int)$member->days > $project->days)
            {
                dao::$errors = sprintf($this->lang->project->daysGreaterProject, $project->days);
                return false;
            }
            if((float)$member->hours > 24)
            {
                dao::$errors = $this->lang->project->errorHours;
                return false;
            }
        }

        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq('project')->exec();

        $accounts = $this->projectTao->insertMember($members, $projectID, $oldJoin);
        $this->projectTao->updateMemberView($projectID, $accounts, $oldJoin);

        if(empty($project->multiple) and $project->model != 'waterfall') $this->loadModel('execution')->syncNoMultipleSprint($projectID);

        return !dao::isError();
    }

    /**
     * 将数字转换成带单位的数字。
     * Convert budget unit.
     *
     * @param  float|string $budget
     * @access public
     * @return float|string $projectBudget
     */
    public function getBudgetWithUnit(float|string $budget): float|string
    {
        $budget    = (float)$budget;
        $rawBudget = $budget;
        if($budget < $this->config->project->budget->tenThousand)
        {
            $budget = round($budget, $this->config->project->budget->precision);
            $unit   = '';
        }
        elseif($budget < $this->config->project->budget->oneHundredMillion && $budget >= $this->config->project->budget->tenThousand)
        {
            $budget = round($budget/$this->config->project->budget->tenThousand, $this->config->project->budget->precision);
            $unit   = $this->lang->project->tenThousand;
        }
        else
        {
            $budget = round($budget/$this->config->project->budget->oneHundredMillion, $this->config->project->budget->precision);
            $unit   = $this->lang->project->hundredMillion;
        }

        return !commonModel::checkNotCN() ? $budget . $unit : round($rawBudget, $this->config->project->budget->precision);
    }

    /**
     * 更新项目关联的产品信息。
     * Update products of a project.
     *
     * @param  int               $projectID
     * @param  array             $products
     * @param  object|array|null $postProductData
     * @access public
     * @return bool
     */
    public function updateProducts(int $projectID, array $products = array(), object|array|null $postProductData = array()): bool
    {
        if(!empty($_POST['newProduct'])) return array();

        $this->loadModel('user');
        $teams        = array_keys($this->getTeamMembers($projectID));
        $stakeholders = array_keys($this->loadModel('stakeholder')->getStakeHolderPairs($projectID));
        $members      = array_merge($teams, $stakeholders);

        /* Link products of other programs. */
        if(!empty($_POST['otherProducts'])) return $this->linkOtherProducts($projectID, $members);

        /* Link products of current program of the project. */
        $products           = isset($_POST['products']) ? (array)$_POST['products'] : $products;
        $oldProjectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchGroup('product', 'branch');
        $this->linkProducts($projectID, $products, $oldProjectProducts, $members);

        /* Delete the execution linked products that is not linked with the execution. */
        if($projectID > 0)
        {
            $executions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->fetchPairs('id');
            $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in($executions)->andWhere('product')->notin(toIntArray($products))->exec();

            if(isset($_POST['stageBy']) and $_POST['stageBy'] == 'product')
            {
                $this->dao->update(TABLE_PROJECT)->set('stageBy')->eq('product')->where('id')->eq($projectID)->orWhere('project')->eq($projectID)->exec();
            }

            $project = $this->projectTao->fetchProjectInfo($projectID);
            if(!empty($project) && !empty($executions) && $project->stageBy == 'project' && in_array($project->model, array('waterfall', 'waterfallplus')))
            {
                $this->loadModel('execution');
                unset($postProductData->plans);
                foreach($executions as $executionID) $this->execution->updateProducts($executionID, $postProductData);
            }
        }

        /* Update the user product view. */
        $oldProductIdList = array_keys($oldProjectProducts);
        $needUpdate       = array_merge(array_diff($oldProductIdList, $products), array_diff($products, $oldProductIdList));
        if($needUpdate) $this->user->updateUserView($needUpdate, 'product', $members);

        /* Create actions. */
        $this->loadModel('action');
        if(!empty($needUpdate)) $this->action->create('project', $projectID, 'managed', '', implode(',', $products));

        /* 如果有取消关联的产品，且项目有迭代且是非瀑布项目，记录关联产品执行到action表。*/
        /* If there are unlinkedProducts and it is multiple project and it isn't waterfall project, record to table action. */
        $unlinkedProducts = array_diff($oldProductIdList, $products);
        if(!empty($unlinkedProducts) && !empty($project) && $project->multiple && $project->model != 'waterfall' && $project->model != 'waterfallplus')
        {
            $products = $this->dao->select('name')->from(TABLE_PRODUCT)
                ->where('id')->in($unlinkedProducts)
                ->fetchPairs();
            $this->action->create('project', $projectID, 'unlinkproduct', '', implode(',', $products));
        }

        return !dao::isError();
    }

    /**
     * 关联项目所属项目集下的产品。
     * Link products of current program of the project.
     *
     * @param  int    $projectID
     * @param  array  $products
     * @param  array  $oldProjectProducts
     * @param  array  $members
     * @access public
     * @return bool
     */
    public function linkProducts(int $projectID, array $products, array $oldProjectProducts, array $members): bool
    {
        $this->loadModel('user');

        /* Delete the linked data. */
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->exec();

        /* Update the user product view. */
        if(empty($products))
        {
            $this->user->updateUserView(array_keys($oldProjectProducts), 'product', $members);
            return true;
        }

        /* Set the product information linked with the project. */
        $branches        = isset($_POST['branch'])   ? $_POST['branch']   : array();
        $plans           = isset($_POST['plans'])    ? $_POST['plans']    : array();
        $charter         = isset($_POST['charter'])  ? $_POST['charter']  : 0;
        $roadmaps        = isset($_POST['roadmaps']) ? $_POST['roadmaps'] : array();
        $existedProducts = array();
        foreach($products as $index => $productID)
        {
            if(empty($productID)) continue;
            if(!isset($existedProducts[$productID])) $existedProducts[$productID] = array();

            $oldPlan    = 0;
            $oldRoadmap = 0;
            $branch     = isset($branches[$index]) ? $branches[$index] : 0;
            $branch     = !is_array($branch) ? array($branch => $branch) : $branch;
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
                $data->branch  = (int)$branchID;
                $data->plan    = (isset($plans[$productID]) && !empty($plans[$productID])) ? implode(',', $plans[$productID]) : $oldPlan;
                $data->plan    = trim((string)$data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";

                if($charter)
                {
                    $data->roadmap = (isset($roadmaps[$productID]) && !empty($roadmaps[$productID])) ? implode(',', $roadmaps[$productID]) : $oldRoadmap;
                    $data->roadmap = trim((string)$data->roadmap, ',');
                    $data->roadmap = empty($data->roadmap) ? 0 : ",$data->roadmap,";
                }

                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                $existedProducts[$productID][$branchID] = true;
            }
        }
        return true;
    }

    /**
     * 关联其他项目集下的产品。
     * Link products of other programs.
     *
     * @access public
     * @return bool
     */
    public function linkOtherProducts(int $projectID, array $members): bool
    {
        $this->loadModel('user');

        $productIdList = array();
        $otherProducts = $_POST['otherProducts'];
        foreach($otherProducts as $otherProduct)
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

            $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();

            $productIdList[] = $data->product;
        }

        $this->user->updateUserView($productIdList, 'product', $members);
        if($projectID > 0 and isset($_POST['stageBy']) and $_POST['stageBy'] == 'product')
        {
            $this->dao->update(TABLE_PROJECT)->set('stageBy')->eq('product')->where('id')->eq($projectID)->orWhere('project')->eq($projectID)->exec();
        }

        return !dao::isError();
    }

    /**
     * 更新关联的产品和执行的用户视图。
     * Update userview for involved product and execution.
     *
     * @param  int    $projectID
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function updateInvolvedUserView(int $projectID, array $users = array()): bool
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product', 'product');
        $this->loadModel('user')->updateUserView($products, 'product', $users);

        $executions = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->fetchPairs('id', 'id');
        if($executions) $this->user->updateUserView($executions, 'sprint', $users);

        return true;
    }

    /**
     * 获取项目团队成员。
     * Get team members.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTeamMembers(int $projectID): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getTeamMembers();

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
    public function getTeamMemberPairs(int $projectID): array
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        if(empty($project)) return array();

        $type = $project->type == 'project' ? 'project' : 'execution';

        $members = $this->dao->select("t1.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$projectID)
            ->andWhere('t1.type')->eq($type)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchPairs('account', 'realname');

        return $members;
    }

    /**
     * Get team member group.
     *
     * @param  array|string $projectIdList
     * @access public
     * @return array
     */
    public function getTeamMemberGroup(mixed $projectIdList): array
    {
        if(empty($projectIdList)) return array();

        return $this->dao->select("t1.account, t2.realname, t1.root as project")->from(TABLE_TEAM)->alias('t1')
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
    public function getMembers2Import(int $projectID, array $currentMembers): array
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
     * @return array
     */
    public function getStats4Kanban(): array
    {
        /* Get execution of the status is doing. */
        $executions        = $this->loadModel('execution')->getStatData(0, 'doing', 0, 0, false, 'hasParentName|skipParent');
        $projectExecutions = array();
        foreach($executions as $execution)
        {
            if(!empty($execution->projectName)) $execution->projectName = htmlspecialchars_decode($execution->projectName);
            $projectExecutions[$execution->project][$execution->id] = $execution;
        }

        /* The execution is sorted in reverse order by execution ID. */
        $ongoingExecutions = array();
        foreach($projectExecutions as $projectID => $executions)
        {
            krsort($projectExecutions[$projectID]);
            $ongoingExecutions[$projectID] = current($projectExecutions[$projectID]);
        }

        $projectsStats = $this->loadModel('program')->getProjectStats(0, 'all', 0, 'order_asc');
        $projectsStats = $this->projectTao->classifyProjects($projectsStats);

        /* Only display recent two closed projects. */
        $projectsStats = $this->projectTao->sortAndReduceClosedProjects($projectsStats, 2);

        return array($projectsStats, $ongoingExecutions);
    }

    /**
     * 设置项目的导航菜单。
     * Set menu of project module.
     *
     * @param  int    $projectID
     * @access public
     * @return int|false
     */
    public function setMenu(int $projectID): int|false
    {
        if(!commonModel::isTutorialMode() and $projectID != 0 and !$this->checkPriv($projectID))
        {
            $this->projectTao->accessDenied();
            return true;
        }

        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        if(!$this->loadModel('common')->isOpenMethod($moduleName, $methodName) and !commonModel::hasPriv($moduleName, $methodName)) $this->common->deny($moduleName, $methodName, false);

        $projectID = (int)$this->checkAccess($projectID, $this->getPairsByProgram());
        $project   = commonModel::isTutorialMode() ? $this->loadModel('tutorial')->getProject() : $this->projectTao->fetchProjectInfo($projectID);
        if(!$project) return false;

        /* Reset project priv. */
        $this->common->resetProjectPriv($projectID);

        /* Set secondary menu. */
        $this->projectTao->setMenuByModel($project->model);
        $this->projectTao->setMenuByProduct($projectID, $project->hasProduct, $project->model);
        $this->lang->switcherMenu = $this->getSwitcher($projectID, $moduleName, $methodName);

        /* Replace url params. */
        common::setMenuVars('project', $projectID);
        $this->setNoMultipleMenu($projectID);

        if($project->acl == 'open') unset($this->lang->project->menu->settings['subMenu']->whitelist);

        if($this->app->getModuleName() == 'repo' || $this->app->getModuleName() == 'mr') $this->loadModel('repo')->setHideMenu($projectID);

        if(!empty($project->isTpl)) dao::$filterTpl = 'never';
        return $projectID;
    }

    /**
     * 设置未启用迭代的菜单。
     * Set multi-scrum menu.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function setNoMultipleMenu(int $projectID): bool
    {
        $this->session->set('multiple', true);

        $project = common::isTutorialMode() ? $this->loadModel('tutorial')->getProject() : $this->projectTao->fetchProjectInfo($projectID);
        if(empty($project) || $project->multiple) return false;
        if(!in_array($project->type, array('project', 'sprint', 'kanban'))) return false;

        if($project->type == 'project')
        {
            $model       = $project->model;
            $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);
        }
        else
        {
            $model       = $project->type == 'kanban' ? 'kanban' : 'scrum';
            $executionID = $project->id;
            $projectID   = $project->project;
            $project     = $this->projectTao->fetchProjectInfo($projectID);
        }
        if(empty($projectID) || empty($executionID)) return false;

        $this->session->set('project', $projectID, 'project');
        $this->session->set('multiple', false);

        global $lang;
        $navGroup = zget($lang->navGroup, $this->app->rawModule);
        $lang->$navGroup->menu        = $lang->project->noMultiple->{$model}->menu;
        $lang->$navGroup->menuOrder   = $lang->project->noMultiple->{$model}->menuOrder;
        $lang->$navGroup->dividerMenu = $lang->project->noMultiple->{$model}->dividerMenu;

        $this->projectTao->setNavGroupMenu($navGroup, $executionID, $project);

        $lang->project->menu        = $lang->$navGroup->menu;
        $lang->project->menuOrder   = $lang->$navGroup->menuOrder;
        $lang->project->dividerMenu = $lang->$navGroup->dividerMenu;
        $this->lang->switcherMenu   = $this->getSwitcher($projectID, $this->app->rawModule, $this->app->rawMethod);

        /* If projectID is set, cannot use homeMenu. */
        unset($lang->project->homeMenu);
        if(empty($project->hasProduct))
        {
            unset($lang->project->menu->settings['subMenu']->products);
        }
        else
        {
            unset($lang->project->menu->settings['subMenu']->module);
            unset($lang->project->menu->projectplan);
        }

        $this->loadModel('common')->resetProjectPriv($projectID);
        return true;
    }

    /**
     * 检查是否可以修改model字段
     * Check if the project model can be changed.
     *
     * @param  int    $projectID
     * @param  string $model
     * @access public
     * @return bool
     */
    public function checkCanChangeModel(int $projectID, string $model): bool
    {
        if(empty($model)) return false;

        $checkList = $this->config->project->checkList->$model;
        if(in_array($this->config->edition, array('max', 'ipd'))) $checkList = $this->config->project->maxCheckList->$model;
        foreach($checkList as $module)
        {
            if($module == '') continue;

            $type  = '';
            $table = constant('TABLE_'. strtoupper($module));
            if($module == 'execution')
            {
                $this->app->loadConfig('execution');
                $type = zget($this->config->execution->modelList, $model, '');
            }

            /* 检查对应类型的数据是否已经存在。 */
            /* Check if the data of the type already exists. */
            $object = $this->getDataByProject($table, $projectID, $type);
            if(!empty($object)) return false;
        }
        return true;
    }

    /**
     * 根据项目ID获取项目关联的对象。
     * Get the objects under the project.
     *
     * @param  string $table
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return object|bool
     */
    public function getDataByProject(string $table, int $projectID, string $type = ''): object|bool
    {
        return $this->dao->select('id')->from($table)
            ->where('project')->eq($projectID)
            ->beginIF(!empty($type))->andWhere('type')->eq($type)->fi()
            ->fetch();
    }

    /**
     * Add plans.
     *
     * @param  int    $projectID
     * @param  array  $plans
     * @access public
     * @return bool
     */
    public function addPlans(int $projectID, array $plans): bool
    {
        $planIdList = array();
        foreach($plans as $planList)
        {
            if(!$planList) continue;
            foreach($planList as $planID)
            {
                $planIdList[$planID] = $planID;
            }
        }
        $planIdList = toIntArray($planIdList);
        if(empty($planIdList)) return true;

        $planStoryGroup = $this->loadModel('story')->getStoriesByPlanIdList($planIdList);
        foreach($planIdList as $planID)
        {
            $planStories = $planProducts = array();
            $planStory   = isset($planStoryGroup[$planID]) ? $planStoryGroup[$planID] : array();
            if(!empty($planStory))
            {
                foreach($planStory as $id => $story)
                {
                    if($story->status != 'active')
                    {
                        unset($planStory[$id]);
                        continue;
                    }
                    $planProducts[$story->id] = $story->product;
                }

                $planStories = array_keys($planStory);
                $this->loadModel('execution')->linkStory($projectID, $planStories);
            }
        }

        return !dao::isError();
    }

    /**
     * 更新项目关联的计划。
     * Update project's plans.
     *
     * @param  int    $projectID
     * @param  array  $plans
     * @access public
     * @return bool
     */
    public function updatePlans(int $projectID, array $plans): bool
    {
        /* Transfer multi dimensional array to one dimensional array. */
        $newPlans = array();
        if(isset($plans))
        {
            foreach($plans as $planList)
            {
                if(is_array($planList))
                foreach($planList as $planID) $newPlans[$planID] = $planID;
            }
        }
        if(empty($newPlans)) return true;

        /* Fetch old plan list. */
        $oldPlanList = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->andWhere('plan')->ne(0)
            ->fetchPairs();

        $oldPlans = array();
        foreach($oldPlanList as $oldPlanIDList)
        {
            if(is_numeric($oldPlanIDList)) $oldPlans[$oldPlanIDList] = $oldPlanIDList;
            if(!is_numeric($oldPlanIDList))
            {
                $oldPlanIDList = explode(',', $oldPlanIDList);
                foreach($oldPlanIDList as $oldPlanID) $oldPlans[$oldPlanID] = $oldPlanID;
            }
        }

        if(count($newPlans) != count($oldPlans) || !empty(array_diff($newPlans, $oldPlans))) $this->loadModel('productplan')->linkProject($projectID, $newPlans);
        return !dao::isError();
    }

    /**
     * 更新项目排序
     * update project order
     *
     * @param  array  $idList
     * @param  string $orderBy
     *
     * @access public
     * @return bool
     */
    public function updateOrder(array $idList, string $orderBy): bool
    {
        $projects = $this->dao->select('id,`order`')->from(TABLE_PROJECT)
            ->where('id')->in($idList)
            ->orderBy($orderBy)
            ->fetchPairs('order', 'id');

        foreach($projects as $order => $id)
        {
            $newID = array_shift($idList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PROJECT)
                ->set('`order`')->eq($order)
                ->set('lastEditedBy')->eq($this->app->user->account)
                ->set('lastEditedDate')->eq(helper::now())
                ->where('id')->eq($newID)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取项目集的最小开始时间
     * Get program min begin
     *
     * @param  int $objectID
     *
     * @access public
     * @return string
     */
    public function getProgramMinBegin(int $objectID): string
    {
        return $this->dao->select('`begin` as minBegin')->from(TABLE_PROGRAM)
            ->where('id')->ne($objectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('path')->like("%,{$objectID},%")
            ->orderBy('begin_asc')
            ->fetch('minBegin');
    }

    /**
     * 获取项目集的最大结束时间
     * get program max end
     *
     * @param  int $objectID
     *
     * @access public
     * @return string
     */
    public function getProgramMaxEnd(int $objectID): string
    {
        return $this->dao->select('`end` as maxEnd')->from(TABLE_PROGRAM)
            ->where('id')->ne($objectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('path')->like("%,{$objectID},%")
            ->andWhere("`end` is true")
            ->orderBy('end_desc')
            ->fetch('maxEnd');
    }

    /**
     * 获取执行的团队成员。
     * get execution members.
     *
     * @param  string $account
     * @param  array $executions
     *
     * @access protected
     * @return array
     */
    protected function getExecutionMembers(string $account, array $executions): array
    {
        return $this->dao->select('t1.root,t2.name')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.root')->in($executions)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t1.account')->eq($account)
            ->fetchPairs();
    }

    /**
     * 根据项目状态和权限生成列表中操作列按钮。
     * Build table action menu for project browse page.
     *
     * @param  object $project
     * @access public
     * @return array
     */
    public function buildActionList(object $project): array
    {
        $actions = array();
        /* Set status button. */
        if($project->status == 'wait' || $project->status == 'suspended') $actions[] = 'start';
        if($project->status == 'doing')  $actions[] = 'close';
        if($project->status == 'closed') $actions[] = 'active';

        /* A drop-down button to set the status. */
        $canClose    = common::hasPriv('project', 'close') && $project->status != 'doing';
        $canActivate = common::hasPriv('project', 'activate') && $project->status != 'closed';
        if(common::hasPriv('project', 'suspend') || $canClose || $canActivate)
        {
            $menu = 'pause';
            if($project->status != 'doing')  $menu .= ',close';
            if($project->status != 'closed') $menu .= ',active';

            $actions[] = 'other:' . $menu;
        }

        $actions[] = 'edit';
        $actions[] = 'group';
        if($this->config->vision != 'lite')
        {
            $actions[] = 'perm';
            if(common::hasPriv('project', 'manageProducts') || common::hasPriv('project', 'whitelist') || common::hasPriv('project', 'delete')) $actions[] = 'more:link,whitelist,delete';
        }
        else
        {
            $actions[] = 'whitelist';
            $actions[] = 'delete';
        }

        /* Set whether the button can be clicked. */
        foreach($actions as &$action)
        {
            if(strpos($action, ':'))
            {
                $actionList = explode(':', $action);
                $action     = $actionList[0] . ':';
                foreach(explode(',', $actionList[1]) as $actionName)
                {
                    if(!$this->isClickable($project, $actionName)) $action .= '-';
                    $action .= $actionName . ',';
                }
                continue;
            }
            if(!$this->isClickable($project, $action)) $action = array('name' => $action, 'disabled' => true);
        }
        return $actions;
    }

    /**
     * 格式化要在数据表格打印的数据。
     * Format data for list.
     *
     * @param  object $project
     * @param  array  $PMList
     * @access public
     * @return object
     */
    public function formatDataForList(object $project, array $PMList): object
    {
        $projectBudget = $this->getBudgetWithUnit($project->budget);

        $project->budget      = $project->budget != 0 ? zget($this->lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $this->lang->project->future;
        $project->statusTitle = $this->processStatus('project', $project);
        $project->estimate    = $project->estimate . $this->lang->project->workHourUnit;
        $project->consume     = $project->consumed . $this->lang->project->workHourUnit;
        $project->surplus     = $project->left     . $this->lang->project->workHourUnit;
        $project->progress    = $project->progress;
        $project->end         = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
        $project->invested    = !empty($this->config->execution->defaultWorkhours) ? round($project->consumed / $this->config->execution->defaultWorkhours, 2) : 0;

        if($project->PM)
        {
            $user              = zget($PMList, $project->PM, '');
            $project->PM       = zget($user, 'realname', $project->PM);
            $project->PMAvatar = zget($user, 'avatar', '');
            $project->PMUserID = zget($user, 'id', 0);
        }

        return $project;
    }

    /**
     * 记录项目启动时的计划完成日期。
     * Record the end date when the project is started.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function recordFirstEnd(int $projectID): bool
    {
        $project = $this->dao->select('end')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        $this->dao->update(TABLE_PROJECT)->set('firstEnd')->eq(helper::isZeroDate($project->end) ? null : $project->end)->where('id')->eq($projectID)->exec();
        return !dao::isError();
    }

    /*
     * 获取旧页面1.5级下拉。
     * Get project swapper.
     *
     * @param  int     $projectID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher(int $projectID, string $currentModule, string $currentMethod): string
    {
        if($currentModule == 'project' && $currentMethod == 'browse') return '';

        $currentProjectName = $this->lang->project->common;
        if($projectID)
        {
            $currentProject     = $this->getById($projectID);
            $currentProjectName = $currentProject->name;
        }

        if($this->app->viewType == 'mhtml' && $projectID)
        {
            $output  = $this->lang->project->common . $this->lang->hyphen;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('project', '$projectID', '$currentModule', '$currentMethod', '')\">{$currentProjectName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $this->app->loadConfig('index');
        $dropMenuMethod = in_array("{$this->app->moduleName}-{$this->app->methodName}", $this->config->index->oldPages) ? 'ajaxGetOldDropMenu' : 'ajaxGetDropMenu';
        $dataRide       = in_array("{$this->app->moduleName}-{$this->app->methodName}", $this->config->index->oldPages) ? 'searchList'         : 'dropmenu' ;
        $dropMenuLink   = helper::createLink('project', $dropMenuMethod, "objectID=$projectID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProjectName}'><span class='text'>{$currentProjectName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='{$dataRide}' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * 检查阶段是否有相关数据。
     * Check whether the stage has related data.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function hasStageData(int $executionID): bool
    {
        if(empty($executionID)) return false;

        $childStage = $this->dao->select('id')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->andWhere('type')->in('sprint,stage,kanban')->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($childStage)) return false;

        $task = $this->dao->select('id')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($task)) return true;

        $effort = $this->dao->select('id')->from(TABLE_EFFORT)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($effort)) return true;

        $bug = $this->dao->select('id')->from(TABLE_BUG)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($bug)) return true;

        $story = $this->dao->select('id')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t2.project')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->limit(1)->fetch();
        if(!empty($story)) return true;

        $case = $this->dao->select('id')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_PROJECTCASE)->alias('t2')->on('t1.id=t2.case')
            ->where('t2.project')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->limit(1)->fetch();
        if(!empty($case)) return true;

        $build = $this->dao->select('id')->from(TABLE_BUILD)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($build)) return true;

        $testtask = $this->dao->select('id')->from(TABLE_TESTTASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($testtask)) return true;

        $testreport = $this->dao->select('id')->from(TABLE_TESTREPORT)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($testreport)) return true;

        $doc = $this->dao->select('id')->from(TABLE_DOC)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($doc)) return true;

        $doclib = $this->dao->select('id')->from(TABLE_DOCLIB)->where('execution')->eq($executionID)->andWhere('type')->eq('execution')->andWhere('main')->ne('1')->andWhere('deleted')->eq(0)->limit(1)->fetch();
        if(!empty($doclib)) return true;

        $libID  = $this->dao->select('id')->from(TABLE_DOCLIB)->where('type')->eq('execution')->andWhere('execution')->eq($executionID)->andWhere('main')->eq('1')->limit(1)->fetch('id');
        $module = $this->dao->select('id')->from(TABLE_MODULE)
            ->where('deleted')->eq(0)
            ->andWhere('((type')->eq('task')
            ->andWhere('root')->eq($executionID)
            ->markRight(1)
            ->orWhere('(type')->eq('doc')
            ->andWhere('root')->eq($libID)
            ->markRight(2)
            ->limit(1)
            ->fetch();
        if(!empty($module)) return true;

        return false;
    }

    /**
     * 获取瀑布/融合瀑布项目不允许解除关联的产品。
     * Get waterfall/waterfallplus unmodifiable products.
     *
     * @param  object  $project
     * @param  array   $linkedProducts
     * @access public
     * @return array
     */
    public function getDisabledProducts(object $project, array $linkedProducts = array()): array
    {
        $disabledProducts = array();
        if(empty($linkedProducts)) return $disabledProducts;
        if(!in_array($project->model, array('waterfall', 'waterfallplus'))) return $disabledProducts;

        $projectStories = $this->dao->select('t2.product')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.project')->eq($project->id)
            ->andWhere('t2.product')->in(array_keys($linkedProducts))
            ->fetchPairs();

        $executionIdSQL    = $this->dao->select('id')->from(TABLE_EXECUTION)->where('deleted')->eq('0')->andWhere('project')->eq($project->id)->get();
        $executionProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->subIn($executionIdSQL)->andWhere('product')->in(array_keys($linkedProducts))->fetchPairs();
        $executionStories  = array();
        if($project->stageBy == 'project')
        {
            $executionStories  = $this->dao->select('t2.product')->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t2.project')->subIn($executionIdSQL)
                ->andWhere('t2.product')->in($executionProducts)
                ->fetchPairs();
        }

        foreach($linkedProducts as $productID => $product)
        {
            if($project->stageBy == 'product')
            {
                if(isset($projectStories[$productID]) && !isset($executionProducts[$productID])) $disabledProducts[$productID] = $this->lang->project->disabledHint->linkedStory;
                if(!isset($projectStories[$productID]) && isset($executionProducts[$productID])) $disabledProducts[$productID] = $this->lang->project->disabledHint->createdStage;
                if(isset($projectStories[$productID]) && isset($executionProducts[$productID])) $disabledProducts[$productID] = $this->lang->project->disabledHint->linkedStoryAndStage;
            }
            else
            {
                if(isset($projectStories[$productID]) && !isset($executionStories[$productID])) $disabledProducts[$productID] = $this->lang->project->disabledHint->linkedStory;
                if(isset($projectStories[$productID]) && isset($executionStories[$productID])) $disabledProducts[$productID] = $this->lang->project->disabledHint->linkedStoryAndExecution;
            }
        }
        return $disabledProducts;
    }
}
