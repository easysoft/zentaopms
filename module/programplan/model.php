<?php
declare(strict_types=1);

/**
 * The model file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @link        https://www.zentao.net
 */
class programplanModel extends model
{
    /**
     * 根据id获取阶段。
     * Get plan by id.
     *
     * @param  int    $planID
     * @access public
     * @return object|false
     */
    public function getByID(int $planID): object|false
    {
        $plan = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($planID)->fetch();
        if(empty($plan)) return false;

        return $this->processPlan($plan);
    }

    /**
     * 获取阶段列表。
     * Get stages list.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $browseType all|parent
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStage(int $executionID = 0, int $productID = 0, string $browseType = 'all', string $orderBy = 'id_asc'): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStats();

        $plans = $this->programplanTao->getStageList($executionID, $productID, $browseType, $orderBy);
        return $this->processPlans($plans);
    }

    /**
     * 根据id 查询项目列表。
     * Get project by idList.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByList(array $idList = array()): array
    {
        $plans = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($idList)->andWhere('type')->eq('project')->fetchAll('id');
        return $this->processPlans($plans);
    }

    /**
     * 获取阶段列表。
     * Get plans.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPlans(int $executionID = 0, int $productID = 0, string $orderBy = 'id_asc'): array
    {
        $plans = $this->getStage($executionID, $productID, 'all', $orderBy);
        if(!$plans) return array();

        $parents  = array();
        $children = array();
        foreach($plans as $planID => $plan)
        {
            if($plan->grade == 1) $parents[$planID] = $plan;
            if($plan->grade > 1)  $children[$plan->parent][] = $plan;
        }

        foreach($parents as $planID => $plan) $parents[$planID]->children = isset($children[$planID]) ? $children[$planID] : array();
        return $parents;
    }

    /**
     * 获取项目中的阶段数据键值对。
     * Get stade pairs for project.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  string $type all|leaf
     * @access public
     * @return array
     */
    public function getPairs(int $executionID, int $productID = 0, string $type = 'all'): array
    {
        $plans   = $this->getStage($executionID, $productID, $type);
        $parents = array();
        $pairs   = array();

        if(strpos($type, 'leaf') !== false) array_map(function($plan) use(&$parents){$parents[$plan->parent] = true;}, $plans);
        foreach($plans as $planID => $plan)
        {
            if(strpos($type, 'leaf') !== false and isset($parents[$plan->id])) continue;

            $paths    = array_slice(explode(',', trim($plan->path, ',')), 1);
            $planName = '';
            foreach($paths as $path)
            {
                if(isset($plans[$path])) $planName .= '/' . $plans[$path]->name;
            }

            $pairs[$planID] = $planName;
        }

        return $pairs;
    }

    /**
     * 获取甘特图页面数据。
     * Get gantt data.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  int     $baselineID
     * @param  string  $selectCustom
     * @param  bool    $returnJson
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $orderBy
     * @access public
     * @return string|array
     */
    public function getDataForGantt(int $projectID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true, string $browseType = '', int $queryID = 0, string $orderBy = ''): string|array
    {
        $plans   = $this->getStage($projectID, $productID, 'all', 'order');
        $project = $this->loadModel('project')->getById($projectID);

        /* Set plan baseline data. */
        if($baselineID)
        {
            $baseline = $this->loadModel('cm')->getByID($baselineID);
            $oldData  = json_decode($baseline->data);
            $plans    = $this->programplanTao->setPlanBaseline((array)$oldData->stage, $plans);
        }

        /* Set task baseline data. */
        $tasks = $this->getGanttTasks($projectID, array_keys($plans), $browseType, $queryID);
        if($baselineID) $this->programplanTao->setTaskBaseline(isset($oldData->task) ? $oldData->task : array(), $tasks); // Set task baseline.

        if($browseType == 'bysearch')
        {
            $taskExecutions = array_column($tasks, 'execution');
            $plans = array_filter($plans, function($plan) use($taskExecutions) {return in_array($plan->id, $taskExecutions);});
        }

        /* Set plan for gantt view. */
        $result = $this->programplanTao->initGanttPlans($plans, $browseType);
        $datas          = $result['datas'];
        $stageIndex     = $result['stageIndex'];
        $reviewDeadline = $result['reviewDeadline'];

        /* Judge whether to display tasks under the stage. */
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");

        /* Set task for gantt view. */
        $result     = $this->programplanTao->setTask($tasks, $plans, $selectCustom, $datas, $stageIndex);
        $datas      = $result['datas'];
        $stageIndex = $result['stageIndex'];

        /* 根据排序字段手动排序。 Manually sort by order field. */
        if(!empty($datas['data'])) $datas['data'] = $this->programplanTao->sortForGantt($datas['data'], $orderBy);

        /* Build data for ipd. */
        if($project->model == 'ipd' and $datas) $datas = $this->programplanTao->buildGanttData4IPD($datas, $projectID, $productID, $selectCustom, $reviewDeadline);

        /* Calculate the progress of the phase. */
        $datas = $this->programplanTao->setStageSummary($datas, $stageIndex);

        foreach($tasks as $task) $task->id = $task->execution . '-' . $task->id;

        /* Set relation task data. */
        $datas['links'] = $this->programplanTao->buildGanttLinks($projectID, $tasks);
        $datas['data'] = isset($datas['data']) ? array_values($datas['data']) : array();
        return $returnJson ? json_encode($datas) : $datas;
    }

    /**
     * 获取分组后的甘特图相关数据。
     * Gets Gantt chart related data as assigned to the group.
     *
     * @param  string  $type
     * @param  int     $executionID
     * @param  int     $productID
     * @param  int     $baselineID
     * @param  string  $selectCustom
     * @param  bool    $returnJson
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $orderBy
     * @access public
     * @return string|array
     */
    public function getDataForGanttGroup(string $type, int $executionID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true, string $browseType = '', int $queryID = 0, string $orderBy = ''): string|array
    {
        $datas       = array();
        $stageIndex  = array();

        $plans      = $this->getStage($executionID, $productID);
        $planIdList = array_column($plans, 'id');
        $tasks      = $this->getGanttTasks($executionID, $planIdList, $browseType, $queryID, null, $type);
        $tasksGroup = $this->programplanTao->buildTaskGroup($tasks, $type);

        /* Judge whether to display tasks under the stage. */
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");

        $begin        = $end = helper::today();
        $deadlineList = array();
        foreach($tasksGroup as $group => $tasks)
        {
            foreach($tasks as $taskID => $task)
            {
                $deadline = helper::isZeroDate($task->deadline) && !empty($plans[$task->execution]->end) ? $plans[$task->execution]->end : $task->deadline;
                if(helper::isZeroDate($deadline)) continue;

                $begin = $deadline < $begin ? $deadline : $begin;
                $deadlineList[$taskID] = $deadline;
            }
        }

        $groupID = 0;
        $datas['data'] = array();
        $workingDays   = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);

        $objects = array();
        if(in_array($type, array('assignedTo', 'finishedBy', 'closedBy'))) $objects = $this->loadModel('user')->getPairs('noletter');
        if($type == 'module') $objects = $this->loadModel('tree')->getModulesName(array_keys($tasksGroup));
        if($type == 'story')  $objects = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in(array_keys($tasksGroup))->fetchPairs('id', 'title');

        if(in_array($type, array('finishedBy', 'closedBy')))
        {
            $unDoneTasks = !empty($tasksGroup['']) ? $tasksGroup[''] : array();
            if(!empty($unDoneTasks))
            {
                unset($tasksGroup['']);
                $tasksGroup[''] = $unDoneTasks;
            }
        }

        foreach($tasksGroup as $group => $tasks)
        {
            if(!$group) $group = '/'; // 未指派
            $groupID ++;
            $groupKey = $groupID . $group;
            $datas['data'][$groupKey] = $this->programplanTao->buildGroupDataForGantt($groupID, (string)$group, $type, $objects);

            foreach($tasks as $taskID => $task)
            {
                $dateLimit = $this->programplanTao->getTaskDateLimit($task, zget($plans, $task->execution, null));
                if(strpos($selectCustom, 'task') !== false)
                {
                    $data         = $this->programplanTao->buildTaskDataForGantt($task, $dateLimit, $groupID, $tasks);
                    $data->id     = $groupID . '-' . $task->id;
                    $data->parent = $task->parent > 0 && isset($tasks[$task->parent]) ? $groupID . '-' . $task->parent : $groupID;

                    /* Delayed or not?. */
                    $isNotCancel    = !in_array($task->status, array('cancel', 'closed')) || ($task->status == 'closed' && !helper::isZeroDate($task->finishedDate) && $task->closedReason != 'cancel');
                    $isComputeDelay = $isNotCancel && !empty($deadlineList[$taskID]);
                    if($isComputeDelay) $task = $this->task->computeDelay($task, $deadlineList[$taskID], $workingDays);

                    $data->delay     = $this->lang->programplan->delayList[0];
                    $data->delayDays = 0;
                    if(isset($task->delay) && $task->delay > 0)
                    {
                        $data->delay     = $this->lang->programplan->delayList[1];
                        $data->delayDays = $task->delay;
                    }

                    $datas['data'][$task->id] = $data;
                }

                if(!isset($stageIndex[$groupKey]['totalConsumed'])) $stageIndex[$groupKey]['totalConsumed'] = 0;
                if(!isset($stageIndex[$groupKey]['totalLeft']))     $stageIndex[$groupKey]['totalLeft']     = 0;
                if(!isset($stageIndex[$groupKey]['totalReal']))     $stageIndex[$groupKey]['totalReal']     = 0;
                if(!isset($stageIndex[$groupKey]['totalEstimate'])) $stageIndex[$groupKey]['totalEstimate'] = 0;
                $stageIndex[$groupKey]['totalConsumed'] += $task->consumed;
                $stageIndex[$groupKey]['totalLeft']     += $task->left;
                $stageIndex[$groupKey]['totalReal']     += $task->left + $task->consumed;
                $stageIndex[$groupKey]['totalEstimate'] += $task->estimate;
            }
        }

        /* 根据排序字段手动排序。 Manually sort by order field. */
        if(!empty($datas['data']) && $orderBy != 'id_asc') $datas['data'] = $this->programplanTao->sortForGantt($datas['data'], $orderBy);

        $datas = $this->programplanTao->setStageSummary($datas, $stageIndex);
        $datas['links'] = $this->programplanTao->buildGanttLinks($executionID, $datas['data']);
        $datas['data']  = isset($datas['data']) ? array_values($datas['data']) : array();
        return $returnJson ? json_encode($datas) : $datas;
    }

    /**
     * 批量查询阶段关联的项目和属性并过滤日期。
     * Get product and attribute for stage correlation.
     *
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function processPlans(array $plans): array
    {
        foreach($plans as $planID => $plan) $plans[$planID] = $this->processPlan($plan);
        return $plans;
    }

    /**
     * 查询阶段关联的项目和属性并过滤日期。
     * Get product and attribute for stage correlation.
     *
     * @param  object $plan
     * @access public
     * @return object
     */
    public function processPlan(object $plan): object
    {
        $plan->setMilestone = true;
        if($plan->parent)
        {
            $attribute = $this->dao->select('attribute')->from(TABLE_PROJECT)->where('id')->eq($plan->parent)->fetch('attribute');
            $plan->attribute = $attribute == 'develop' ? $attribute : $plan->attribute;
        }
        else
        {
            $milestones = $this->programplanTao->getStageCount($plan->id, 'milestone');
            if($milestones > 0)
            {
                $plan->milestone    = 0;
                $plan->setMilestone = false;
            }
        }

        $plan->begin       = helper::isZeroDate($plan->begin)     ? '' : $plan->begin;
        $plan->end         = helper::isZeroDate($plan->end)       ? '' : $plan->end;
        $plan->realBegan   = helper::isZeroDate($plan->realBegan) ? '' : $plan->realBegan;
        $plan->realEnd     = helper::isZeroDate($plan->realEnd)   ? '' : $plan->realEnd;
        $plan->product     = $this->loadModel('product')->getProductIDByProject($plan->id);
        $plan->productName = $this->dao->findByID($plan->product)->from(TABLE_PRODUCT)->fetch('name');

        return $plan;
    }

    /**
     * 获取时间段内工作时间间隔天数。
     * Get duration.
     *
     * @param  string    $begin
     * @param  string    $end
     * @access protected
     * @return int
     */
    protected function getDuration(string $begin, string $end): int
    {
        $duration = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);
        return count($duration);
    }

    /**
     * 创建/设置一个项目阶段。
     * Create/Set a project plan/phase.
     *
     * @param  array  $plans
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $parentID
     * @param  int    $totalSyncData
     * @access public
     * @return bool
     */
    public function create(array $plans, int $projectID = 0, int $productID = 0, int $parentID = 0, int $totalSyncData = 0): bool
    {
        if(empty($plans)) dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
        if(dao::isError()) return false;

        /* Get linked product by projectID. */
        $this->loadModel('action');
        $this->loadModel('execution');
        $linkProducts = $this->programplanTao->getLinkProductsForCreate($projectID, $productID);
        $project      = $this->fetchByID($projectID, 'project');

        /* Set each plans. */
        $updateUserViewIdList = array();
        $enabledPoints        = array();
        $parallel             = 0;
        $parents              = array();
        $prevSyncData         = null;
        $prevLevel            = 0;
        $addNewStage          = false;
        foreach($plans as $plan)
        {
            if(!empty($plan->schedule))
            {
                $schedule = json_decode($plan->schedule, true);
                if(!empty($schedule['calendar'])) $plan->days = count($schedule['calendar']);
            }

            $level    = isset($plan->level) ? $plan->level : 0;
            $syncData = isset($plan->syncData) ? $plan->syncData : null;
            unset($plan->level, $plan->syncData);

            $parallel = isset($plan->parallel) ? $plan->parallel : 0;
            if(!empty($plan->point)) $enabledPoints = array_merge($enabledPoints, $plan->point);
            if($plan->id)
            {
                $stageID = $plan->id;
                $parents[$level] = $stageID;
                unset($plan->id, $plan->type);

                $changes = $this->programplanTao->updateRow($stageID, $projectID, $plan);
                if(dao::isError()) return false;

                if(!empty($changes))
                {
                    $actionID = $this->action->create('execution', $stageID, 'edited');
                    $this->action->logHistory($actionID, $changes);

                    /* Add PM to stage teams and project teams. */
                    if(!empty($plan->PM)) $this->execution->addExecutionMembers($stageID, array($plan->PM));
                    if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;

                    $this->updateSubStageAttr($stageID, $plan->attribute);
                }
            }
            else
            {
                if($level > 0 && isset($parents[$level - 1])) $plan->parent = $parents[$level - 1];
                $stageID = $this->programplanTao->insertStage($plan, $projectID, $productID, $level > 0 ? $plan->parent : $parentID);
                if(dao::isError()) return false;

                $parents[$level] = $stageID;
                $extra = ($project && $project->hasProduct and !empty($linkProducts['products'])) ? implode(',', $linkProducts['products']) : '';
                $this->action->create('execution', $stageID, 'opened', '', $extra);

                $this->execution->updateProducts($stageID, $linkProducts);
                if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;

                $addNewStage = true;
            }

            if(!$totalSyncData && $prevSyncData && $prevLevel == $level - 1)  $this->programplanTao->syncParentData($stageID, $parents[$prevLevel]);
            if($totalSyncData  && $prevSyncData === null && $parentID) $this->programplanTao->syncParentData($stageID, $parentID);

            $prevSyncData = $syncData;
            $prevLevel    = $level;
        }

        if($project && $project->model == 'ipd') $this->dao->update(TABLE_PROJECT)->set('parallel')->eq($parallel)->where('id')->eq($projectID)->exec();
        if($updateUserViewIdList) $this->loadModel('user')->updateUserView($updateUserViewIdList, 'sprint');
        if($enabledPoints) $this->programplanTao->updatePoint($projectID, $enabledPoints);

        if($addNewStage)
        {
            $projectDeliverableID = $this->dao->select('t1.id')->from(TABLE_PROJECTDELIVERABLE)->alias('t1')
                ->leftJoin(TABLE_DELIVERABLE)->alias('t2')->on('t1.deliverable = t2.id')
                ->where('t1.project')->eq($projectID)
                ->andWhere('t2.category')->eq('PP')
                ->fetch('id');

            $this->dao->update(TABLE_PROJECTDELIVERABLE)
                ->set('submittedBy')->eq($this->app->user->account)
                ->set('submittedDate')->eq(helper::now())
                ->where('id')->eq($projectDeliverableID)
                ->exec();
        }

        return true;
    }

    /**
     * 设置阶段在层级中路径。
     * Set stage tree path.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function setTreePath(int $planID): bool
    {
        $stage  = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
        $parent = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($stage->parent)->fetch();

        $this->loadModel('execution');
        if(empty($parent))
        {
            $path['path']  =  ",{$stage->id},";
            $path['grade'] = 1;
        }
        elseif($parent && $parent->type == 'project')
        {
            $path['path']  =  ",{$parent->id},{$stage->id},";
            $path['grade'] = 1;
        }
        elseif(isset($this->lang->execution->typeList[$parent->type]))
        {
            $path['path']  = $parent->path . "{$stage->id},";
            $path['grade'] = $parent->grade + 1;
        }

        $children = $this->execution->getChildExecutions($planID);
        $this->dao->update(TABLE_PROJECT)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($stage->id)->exec();

        if(empty($children)) return !dao::isError();

        foreach($children as $id => $child) $this->setTreePath($id);
        return !dao::isError();
    }

    /**
     * 更新阶段。
     * Update a plan.
     *
     * @param  int       $planID
     * @param  int       $projectID
     * @param  object    $plan
     * @access public
     * @return bool
     */
    public function update(int $planID = 0, int $projectID = 0, ?object $plan = null): bool
    {
        if(empty($plan)) return false;

        $changes = $this->programplanTao->updateRow($planID, $projectID, $plan);
        if(dao::isError()) return false;

        /* Synchronously update sub-phase permissions. */
        $childIdList = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$planID,%")->fetchPairs();
        if(!empty($childIdList)) $this->dao->update(TABLE_PROJECT)->set('acl')->eq($plan->acl)->where('id')->in($childIdList)->exec();

        $this->setTreePath($planID);
        $this->updateSubStageAttr($planID, $plan->attribute);

        if($plan->acl != 'open')
        {
            $this->loadModel('user')->updateUserView($childIdList, 'sprint');
        }

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('execution', $planID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }
        return true;
    }

    /**
     * 根据计划ID判断是否创建了任务。
     * Is create task.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function isCreateTask(int $planID): bool
    {
        if(empty($planID)) return true;

        $task = $this->dao->select('*')->from(TABLE_TASK)->where('execution')->eq($planID)->andWhere('deleted')->eq('0')->limit(1)->fetch();
        return empty($task);
    }

    /**
     * 根据父id获取父阶段的子类型。
     * Get parent stage's children types by parentID.
     *
     * @param  int    $parentID
     * @access public
     * @return array|bool
     */
    public function getParentChildrenTypes(int $parentID): array|bool
    {
        if(empty($parentID)) return true;
        return $this->dao->select('type')->from(TABLE_EXECUTION)->where('parent')->eq($parentID)->andWhere('deleted')->eq('0')->fetchPairs();
    }

    /**
     * 是否可以点击.
     * Is clickable.
     *
     * @param  object  $plan
     * @param  string  $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable(object $plan, string $action): bool
    {
        if(strtolower($action) != 'create') return true;

        global $dao;
        if(empty($plan->id)) return true;

        $task = $dao->select('*')->from(TABLE_TASK)->where('execution')->eq($plan->id)->andWhere('deleted')->eq('0')->limit(1)->fetch();
        return empty($task);
    }

    /**
     * 获取父阶段列表。
     * Get parent stage list.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @param  string $param        withParent|noclosed
     * @access public
     * @return array
     */
    public function getParentStageList(int $executionID, int $planID, int $productID, string $param = ''): array
    {
        $parentStage = $this->programplanTao->getParentStages($executionID, $planID, $productID, $param);
        if(!$parentStage) return array(0 => $this->lang->programplan->emptyParent);

        $plan          = $this->getByID($planID);
        $parents       = array();
        $withParent    = strpos($param, 'withparent') !== false;
        $isStage       = strpos("|$param|", '|stage|') !== false || strpos($param, 'stage') === false;
        $allExecutions = $withParent ? $this->dao->select('id,name,parent,grade,path,type')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->beginIf($executionID)->andWhere('project')->eq($executionID)->fi()
            ->fetchAll('id') : array();
        foreach($allExecutions as $execution) $parents[$execution->parent] = isset($allExecutions[$execution->parent]) ? $allExecutions[$execution->parent] : array();

        foreach($parentStage as $key => $stage)
        {
            $isCreate    = $this->isCreateTask($key);
            $parentTypes = $this->getParentChildrenTypes($key);

            if(!empty($plan))
            {
                if(!$isCreate && $key != $plan->parent) unset($parentStage[$key]);
                if($plan->type == 'stage' && (isset($parentTypes['sprint']) || isset($parentTypes['kanban']))) unset($parentStage[$key]);
                if(($plan->type == 'sprint' || $plan->type == 'kanban') && isset($parentTypes['stage'])) unset($parentStage[$key]);
            }
            else
            {
                if(!$isCreate) unset($parentStage[$key]); // 隐藏有数据的阶段
                if($isStage && (isset($parentTypes['sprint']) || isset($parentTypes['kanban']))) unset($parentStage[$key]); // 如果是阶段，隐藏叶子节点是迭代和看板的数据
                if(!$isStage && (isset($parentTypes['stage']) || isset($parentTypes['stage'])))  unset($parentStage[$key]); // 如果不是阶段，隐藏叶子节点是阶段的数据
            }

            /* Set stage name. */
            if($withParent && isset($parentStage[$key]) && !empty($allExecutions))
            {
                $currentStage  = $allExecutions[$key];
                $paths         = array_slice(explode(',', trim($currentStage->path, ',')), 1);
                $executionName = '';
                foreach($paths as $path)
                {
                    if(isset($allExecutions[$path])) $executionName .= '/' . $allExecutions[$path]->name;
                }
                $parentStage[$key] = $executionName;
            }
        }
        $project = $this->fetchByID($executionID);
        if((!empty($plan) && $plan->type == 'stage') || $project->model == 'waterfall' || $isStage) $parentStage[0] = $this->lang->programplan->emptyParent;
        ksort($parentStage);

        return $parentStage;
    }

    /**
     * 通过计算获取阶段状态。
     * Compute stage status.
     *
     * @param  int    $stage
     * @param  string $action
     * @param  bool   $isParent
     * @access public
     * @return bool|array
     */
    public function computeProgress(int $stageID, string $action = '', bool $isParent = false): bool|array
    {
        $stage = $this->loadModel('execution')->fetchByID($stageID);
        if(empty($stage) || empty($stage->path)) return false;

        $project = $this->loadModel('project')->fetchByID($stage->project);
        $model   = zget($project, 'model', '');
        if(empty($stage) or empty($stage->path) or (!in_array($model, array('waterfall','waterfallplus','ipd','research')))) return false;

        $action       = strtolower($action);
        $parentIdList = array_reverse(explode(',', trim($stage->path, ',')));
        foreach($parentIdList as $id)
        {
            $parent = $this->execution->fetchByID((int)$id);
            if(empty($this->lang->execution->typeList[$parent->type]) || (!$isParent && $id == $stageID)) continue;

            /** 获取子阶段关联开始任务数以及状态下子阶段数量。  */
            /** Get the number of sub-stage associated start tasks and the number of sub-stages under the state. */
            $statusCount = array();
            $children    = $this->execution->getChildExecutions($parent->id);
            $allChildren = $this->dao->select('id')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('path')->like("{$parent->path}%")->andWhere('id')->ne($id)->fetchPairs();
            $startTasks  = $this->dao->select('count(1) as count')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in($allChildren)->andWhere('consumed')->ne(0)->fetch('count');
            foreach($children as $childExecution)
            {
                if(empty($statusCount[$childExecution->status])) $statusCount[$childExecution->status] = 0;
                $statusCount[$childExecution->status] ++;
            }

            if(empty($statusCount)) continue;

            $result       = $this->getNewParentAndAction($statusCount, $parent, (int)$startTasks, $action, $project);
            $newParent    = $result['newParent'] ?? null;
            $parentAction = $result['parentAction'] ?? '';

            /* 如果当前是顶级阶段，并且由于交付物不能关闭，则跳转到顶级阶段的关闭页面。 */
            if(isset($newParent->status) && $newParent->status == 'closed')
            {
                $isTopStage = $parent->grade == 1 && $parent->type != 'project' && $stageID != $id && $parent->status == 'doing';
                if(in_array($this->config->edition, array('max', 'ipd')) && $isTopStage && !$this->execution->canCloseByDeliverable($parent))
                {
                    $url = helper::createLink('execution', 'close', "executionID={$parent->id}");
                    return array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->execution->cannotAutoCloseParent}').then((res) => {if(res) {loadModal('$url', '.modal-dialog');} else {loadPage();}});");
                }
            }

            /** 更新状态以及记录日志。 */
            /** Update status and save log. */
            if(isset($newParent) && $newParent)
            {
                $this->dao->update(TABLE_EXECUTION)->data($newParent)->where('id')->eq($id)->exec();
                $this->loadModel('action')->create('execution', (int)$id, $parentAction, '', $parentAction);
            }
            unset($newParent, $parentAction);
        }
        return true;
    }

    /**
     * 根据阶段ID，检查阶段是否是叶子阶段。
     * Check if the stage is a leaf stage.
     *
     * @param  int    $stageID
     * @access public
     * @return bool
     */
    public function checkLeafStage(int $stageID): bool
    {
        if(empty($stageID)) return false;
        $subStageNumbers = $this->dao->select('COUNT(`id`) AS total')->from(TABLE_EXECUTION)
            ->where('parent')->eq($stageID)
            ->andWhere('deleted')->eq(0)
            ->fetch('total');

        return $subStageNumbers == 0;
    }

    /**
     * 检查是否为顶级。
     * Check whether it is the top stage.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function isTopStage(int $planID): bool
    {
        $parentID   = $this->dao->select('parent')->from(TABLE_EXECUTION)->where('id')->eq($planID)->fetch('parent');
        $parentType = $this->dao->select('type')->from(TABLE_EXECUTION)->where('id')->eq($parentID)->fetch('type');

        return $parentType == 'project';
    }

    /**
     * 更新子阶段的属性值.
     * Update sub-stage attribute.
     *
     * @param  int    $planID
     * @param  string $attribute
     * @access public
     * @return true
     */
    public function updateSubStageAttr(int $planID, string $attribute): bool
    {
        if($attribute == 'mix') return true;

        $subStageList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('parent')->eq($planID)->andWhere('deleted')->eq(0)->fetchAll('id');
        if(empty($subStageList)) return true;

        $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($attribute)->where('id')->in(array_keys($subStageList))->exec();
        foreach($subStageList as $childID => $subStage) $this->updateSubStageAttr($childID, $attribute);
        return true;
    }

    /**
     * 获取阶段当前和子集信息。
     * Get plan and its children.
     *
     * @param  string|int|array $planIdList
     * @access public
     * @return array
     */
    public function getSelfAndChildrenList(string|int|array $planIdList): array
    {
        if(is_numeric($planIdList)) $planIdList = (array)$planIdList;

        $planList = $this->dao->select('t2.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('FIND_IN_SET(t1.id,t2.`path`)')
            ->where('t1.id')->in($planIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('id');

        $selfAndChildrenList = array();
        foreach($planIdList as $planID)
        {
            if(!isset($selfAndChildrenList[$planID])) $selfAndChildrenList[$planID] = array();
            foreach($planList as $plan)
            {
                if(strpos($plan->path, ",$planID,") !== false) $selfAndChildrenList[$planID][$plan->id] = $plan;
            }
        }

        return $selfAndChildrenList;
    }

    /**
     * 获取阶段同一层级信息。
     * Get plan's siblings.
     *
     * @param  string|int|array $planIdList
     * @access public
     * @return array
     */
    public function getSiblings(array|string|int $planIdList): array
    {
        if(is_numeric($planIdList)) $planIdList = (array)$planIdList;

        $siblingsList = $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.parent=t2.parent')
            ->where('t2.id')->in($planIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');

        $siblingStages = array();
        foreach($planIdList as $planID)
        {
            if(!isset($siblingStages[$planID])) $siblingStages[$planID] = array();
            foreach($siblingsList as $sibling)
            {
                if($siblingsList[$planID]->parent == $sibling->parent) $siblingStages[$planID][$sibling->id] = $sibling;
            }
        }

        return $siblingStages;
    }

    /**
     * 获取阶段ID的属性。
     * Get stageID attribute.
     *
     * @param  int    $stageID
     * @access public
     * @return false|string
     */
    public function getStageAttribute(int $stageID): false|string
    {
        return $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($stageID)->fetch('attribute');
    }

    /**
     * 保存自定义配置
     * Save custom setting.
     *
     * @param  object    $settings
     * @param  string    $owner
     * @param  string    $module
     * @access protected
     * @return void
     */
    protected function saveCustomSetting(object $settings, string $owner, string $module): void
    {
        $zooming     = zget($settings, 'zooming', '');
        $stageCustom = zget($settings, 'stageCustom', '');
        $ganttFields = zget($settings, 'ganttFields', '');

        $this->loadModel('setting');
        $this->setting->setItem("$owner.$module.browse.stageCustom", $stageCustom);
        $this->setting->setItem("$owner.$module.ganttCustom.ganttFields", $ganttFields);
        $this->setting->setItem("$owner.$module.ganttCustom.zooming", $zooming);
    }

    /**
     * 获取甘特图的任务.
     * Get tasks in gantt.
     *
     * @param  int    $projectID
     * @param  array  $planIdList
     * @param  string $browseType
     * @param  int    $queryID
     * @param  object $pager
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGanttTasks(int $projectID, array $planIdList, string $browseType, int $queryID, ?object $pager = null, string $type = 'execution')
    {
        $tasks = array();
        if($browseType == 'bysearch')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('projectTaskQuery', $query->sql);
                $this->session->set('projectTaskForm', $query->form);
            }
            elseif(!$this->session->projectTaskQuery)
            {
                $this->session->set('projectTaskQuery', ' 1 = 1');
            }

            if(strpos($this->session->projectTaskQuery, "deleted =") === false) $this->session->set('projectTaskQuery', $this->session->projectTaskQuery . " AND deleted = '0'");

            $projectTaskQuery = $this->session->projectTaskQuery;
            $projectTaskQuery .= " AND `project` = '$projectID'";
            $projectTaskQuery .= " AND `execution` " . helper::dbIN($planIdList);
            $projectTaskQuery .= " AND `status` != 'cancel' AND `closedReason` != 'cancel'";

            $this->session->set('projectTaskQueryCondition', $projectTaskQuery, $this->app->tab);
            $this->session->set('projectTaskOnlyCondition', true, $this->app->tab);

            $tasks = $this->loadModel('execution')->getSearchTasks($projectTaskQuery, "{$type}_asc,beginDate_asc,id_asc", $pager, 'projectTask');
        }
        elseif(!empty($planIdList))
        {
            $tasks = $this->dao->select('t1.*,t2.version AS latestStoryVersion, t2.status AS storyStatus, IF(t1.`estStarted` IS NULL, t3.`begin`, t1.`estStarted`) as beginDate')->from(TABLE_TASK)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.project')->eq($projectID)
                ->andWhere('t1.execution')->in($planIdList)
                ->beginIF($browseType == 'nowait')->andWhere('t1.status')->ne('wait')->fi()
                ->beginIF($browseType != 'nowait')->andWhere('t1.status')->ne('cancel')->fi()
                ->beginIF($browseType != 'nowait')->andWhere('t1.`closedReason`')->ne('cancel')->fi()
                ->filterTpl('skip')
                ->orderBy("{$type}_asc,beginDate_asc,id_asc")
                ->fetchAll('id', false);
        }

        $isGantt = $this->app->rawModule == 'programplan' && $this->app->rawMethod == 'browse';
        if($isGantt) $plans = $this->loadModel('execution')->getByIdList($planIdList);

        $begin         = $end = helper::today();
        $deadlineList  = array();
        $taskDateLimit = $this->dao->select('`taskDateLimit`')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('taskDateLimit');
        foreach($tasks as $taskID => $task)
        {
            if(!$isGantt && helper::isZeroDate($task->deadline)) continue;

            $plan      = isset($plans[$task->execution]) ? $plans[$task->execution] : null;
            $dateLimit = $this->programplanTao->getTaskDateLimit($task, $plan, $taskDateLimit == 'limit' ? zget($tasks, $task->parent, null) : null);
            $deadline  = substr($dateLimit['end'], 0, 10);

            $begin = $deadline < $begin ? $deadline : $begin;
            $deadlineList[$taskID] = $deadline;
        }

        $workingDays       = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);
        $storyVersionPairs = $this->loadModel('task')->getTeamStoryVersion(array_keys($tasks));
        foreach($tasks as $taskID => $task)
        {
            /* Story changed or not. */
            $task->storyVersion = zget($storyVersionPairs, $task->id, $task->storyVersion);
            $task->needConfirm  = false;
            $task->rawStatus    = $task->status;
            if(!empty($task->storyStatus) && $task->storyStatus == 'active' && !in_array($task->status, array('cancel', 'closed')) && $task->latestStoryVersion > $task->storyVersion)
            {
                $task->needConfirm = true;
                $task->status      = 'changed';
            }

            /* Delayed or not?. */
            $isNotCancel    = !in_array($task->status, array('cancel', 'closed')) || ($task->status == 'closed' && !helper::isZeroDate($task->finishedDate) && $task->closedReason != 'cancel');
            $isComputeDelay = $isNotCancel && !empty($deadlineList[$taskID]);
            if($isComputeDelay) $task = $this->task->computeDelay($task, $deadlineList[$taskID], $workingDays);
        }
        return $tasks;
    }

    /**
     * 根据阶段的开始和结束，计算工作日。
     * Calc stage days by stage begin and end.
     *
     * @param  string $start
     * @param  string $end
     * @access public
     * @return int
     */
    public function calcDaysForStage(string $start, string $end): int
    {
        $weekend = $this->config->execution->weekend;
        $days    = range(strtotime($start), strtotime($end), 86400);
        foreach($days as $key => $day)
        {
            $weekDay = date('N', $day);
            if(($weekend == 2 && $weekDay == 6) || $weekDay == 7) unset($days[$key]);
        }
        return count($days);
    }

    /**
     * 获取甘特图的版本。
     * Get all versions for gantt.
     *
     * @param int    $projectID
     * @param int    $productID
     * @param string $category
     * @param string $type       project|execution
     * @access public
     * @return array
     */
    public function getGanttVersions(int $projectID, int $productID = 0, string $category = '', string $type = 'project'): array
    {
        /* 1. 甘特图创建的版本。 Gantt version. */
        $ganttVersions = $this->dao->select("*, 'gantt' AS `reviewType`")->from(TABLE_OBJECT)
            ->where('type')->eq('taged')
            ->andWhere('status')->in('gantt,tmpGantt')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($category))->andWhere('category')->eq($category)->fi()
            ->beginIF($type == 'project')->andWhere('project')->eq($projectID)->fi()
            ->beginIF($type == 'execution')->andWhere('execution')->eq($projectID)->fi()
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->orderBy('id_asc')
            ->fetchAll('id', false);

        /* 执行的甘特图版本只有这个。 Execution's gantt version only has this. */
        if($type == 'execution') return $ganttVersions;
        if($type == 'project' && $category != 'gantt') return $ganttVersions;

        $disabledFeatures = $this->dao->select('t1.`disabledFeatures`')->from(TABLE_WORKFLOWGROUP)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.id = t2.`workflowGroup`')
            ->where('t2.id')->eq($projectID)
            ->fetch('disabledFeatures');

        /* 2. 交付物的项目计划。 Project plan of deliverable. */
        $deliverableVersions = strpos(",{$disabledFeatures},", ',deliverable,') !== false ? array() : $this->dao->select('t1.*, t2.type AS `reviewType`, t2.deliverable, t2.id AS reviewID')->from(TABLE_OBJECT)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.id = t2.object')
            ->leftJoin(TABLE_DELIVERABLE)->alias('t3')->on('t1.category = t3.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t3.category')->eq('PP')
            ->andWhere('t2.status')->eq('pass')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('deliverable')
            ->beginIF($productID)->andWhere('t1.product')->eq($productID)->fi()
            ->fetchAll('id', false);
        $deliverableIdList = array_unique(array_column($deliverableVersions, 'deliverable'));

        /* 3. 基线评审的版本。 Project plan of baseline. */
        $baselineVersions = array();
        if(strpos(",{$disabledFeatures},", ',cm,') === false)
        {
            $baselineVersions = $this->dao->select('t1.id, t1.version, t1.category, t1.`categoryVersion`')->from(TABLE_OBJECT)->alias('t1')
                ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.id = t2.object')
                ->where('t1.project')->eq($projectID)
                ->andWhere('t2.status')->eq('pass')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.type')->eq('baseline')
                ->beginIF($productID)->andWhere('t1.product')->eq($productID)->fi()
                ->fetchAll('id', false);
            foreach($baselineVersions as $baselineVersion)
            {
                foreach(explode(',', $baselineVersion->category) as $category)
                {
                    if(!in_array($category, $deliverableIdList))
                    {
                        unset($baselineVersions[$baselineVersion->id]);
                        continue 2;
                    }

                    $categoryVersion     = json_decode($baselineVersion->categoryVersion, true);
                    $deliverableReviewID = $categoryVersion[$category];
                    foreach($deliverableVersions as $deliverable)
                    {
                        if(!isset($deliverable->baselineList)) $deliverable->baselineList = '';
                        if($deliverable->reviewID == $deliverableReviewID) $deliverable->baselineList .= "$baselineVersion->version ";
                    }
                }
            }
        }

        /* 4. 合并版本。 Merge versions. */
        $versions = arrayUnion($deliverableVersions, $ganttVersions);
        ksort($versions, SORT_NUMERIC);

        return $versions;
    }

    /**
     * 获取甘特图的数据。
     * Get gantt data.
     *
     * @param int    $versionID
     * @access public
     * @return array
     */
    public function getGanttDataByVersion(int $versionID): array
    {
        $object = $this->dao->select('*')->from(TABLE_OBJECT)->where('id')->eq($versionID)->fetch();
        if(empty($object)) return array();

        if($object->status == 'gantt' || $object->status == 'tmpGantt') return (array)json_decode($object->data); // 如果是个甘特图直接创建的版本，直接返回数据。 If it is a gantt version created directly, return the data directly.

        /* 如果是基线关联的甘特图版本，需要找到基线对应的交付物的甘特图版本。 If it is a gantt version related to a baseline, find the gantt version corresponding to the deliverable. */
        if(empty($object->data) && !empty($object->categoryVersion))
        {
            $categoryVersion = json_decode($object->categoryVersion, true);
            $object = $this->dao->select('t2.*')->from(TABLE_REVIEW)->alias('t1')
                ->leftJoin(TABLE_OBJECT)->alias('t2')->on('t1.object=t2.id')
                ->where('t1.id')->in($categoryVersion)
                ->andWhere('t2.data')->ne('')
                ->orderBy('t2.id_desc')
                ->fetch();
        }

        if(!empty($object->data))
        {
            /* 从docblock表中获取甘特图的数据。 From docblock table, get the gantt data. */
            $blockID = zget(zget(json_decode($object->data), 'fetcherParams', array()), 'param2', 0);
            $content = $this->dao->select('*')->from(TABLE_DOCBLOCK)->where('id')->eq($blockID)->fetch('content');

            if(empty($content)) return array();
            return (array)zget(json_decode($content), 'ganttOptions', array());
        }

        return array();
    }

    /**
     * 处理实际进度的甘特图数据。
     * Process nowait gantt data.
     *
     * @param array   $tasks
     * @access public
     * @return array
     */
    public function processNoWaitGanttData(array $tasks): array
    {
        $pausedTasks = array();
        foreach($tasks as $task)
        {
            if(empty($task->type) || $task->type != 'task') continue;
            if(isset($task->rawStatus) && $task->rawStatus == 'pause')
            {
                $taskID = (string)$task->id;
                if(strpos($taskID, '-') !== false) $taskID = explode('-', $taskID)[1];
                $pausedTasks[$taskID] = $taskID;
            }
        }

        $pausedTasksDate = array();
        if($pausedTasks)
        {
            $pausedTasksDate = $this->dao->select('`objectID`,`date`')->from(TABLE_ACTION)->where('objectType')->eq('task')
                ->andWhere('action')->eq('paused')
                ->andWhere('objectID')->in($pausedTasks)
                ->orderBy('id')
                ->fetchPairs('objectID', 'date');
        }

        $today = helper::today();
        return array_values(array_filter($tasks, function($data) use($today, $pausedTasksDate)
        {
            if(empty($data->type) || $data->type != 'task') return true;

            $status = $data->status;
            if(isset($data->rawStatus)) $status = $data->rawStatus;
            if($status == 'wait') return false;
            if(empty($data->realBegan)) return false;

            $taskID = (string)$data->id;
            if(strpos($taskID, '-') !== false) $taskID = explode('-', $taskID)[1];

            $realBegan = $data->realBegan;
            $realEnd   = $data->realEnd;
            if($status == 'doing')  $realEnd = $data->deadline;
            if($status == 'cancel') $realEnd = $data->canceledDate;
            if($status == 'pause')  $realEnd = zget($pausedTasksDate, $taskID, '');

            $useBegan = false;
            if($realBegan > $realEnd)
            {
                $realEnd  = $realBegan;
                $useBegan = true;
            }
            if($useBegan && $realBegan < $today) $realEnd = $today;
            $data->start_date = date('d-m-Y', strtotime($realBegan));
            $data->endDate    = date('d-m-Y', strtotime($realEnd));
            $data->duration   = helper::diffDate($data->endDate, $data->start_date) + 1;
            return true;
        }));
    }

    /**
     * 保存甘特图临时版本。
     * Save tmp gantt version.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $data
     * @access public
     * @return void
     */
    public function saveTmpGanttVersion(int $projectID = 0, string $type = '', string $data = '')
    {
        $status  = 'tmpGantt';
        $project = $this->loadModel('project')->fetchByID($projectID);

        $oldVersions = $this->dao->select('id')->from(TABLE_OBJECT)
            ->where('status')->eq($status)
            ->andWhere('type')->eq('taged')
            ->beginIF($project->type == 'project')->andWhere('project')->eq($projectID)->fi()
            ->beginIF(in_array($project->type, array('stage', 'sprint', 'kanban')))->andWhere('execution')->eq($projectID)->fi()
            ->orderBy('id_asc')
            ->fetchAll();

        if(count($oldVersions) >= 5)
        {
            $oldestVersion = reset($oldVersions);
            if(!empty($oldestVersion->id)) $this->dao->delete()->from(TABLE_OBJECT)->where('id')->eq($oldestVersion->id)->exec();
        }

        $version = new stdClass();
        $version->version  = date(DT_DATE3 . ' H:i:s');
        $version->title    = date(DT_DATE3 . ' H:i:s');
        $version->product  = 0;
        $version->type     = 'taged';
        $version->category = $type;
        $version->status   = $status;
        $version->data     = $data;

        if($project->type == 'project') $version->project = $projectID;
        if(in_array($project->type, array('stage', 'sprint', 'kanban'))) $version->execution = $projectID;
        $this->dao->insert(TABLE_OBJECT)->data($version)->exec();
    }

    /**
     * Rollback stage.
     * 回滚阶段。
     *
     * @param  object $stage
     * @access public
     * @return bool
     */
    public function rollbackStage(object $stage): bool
    {
        $updateStage = new stdClass();
        $updateStage->name           = $stage->name;
        $updateStage->milestone      = $stage->milestonecode;
        $updateStage->status         = $stage->rawStatus;
        $updateStage->begin          = date('Y-m-d', strtotime($stage->begin)) ?: null;
        $updateStage->end            = date('Y-m-d', strtotime($stage->deadline)) ?: null;
        $updateStage->realBegan      = $stage->realBegan ?: null;
        $updateStage->realEnd        = $stage->realEnd ?: null;
        $updateStage->progress       = $stage->progress;
        $updateStage->closedBy       = $stage->closedBy;
        $updateStage->closedDate     = $stage->closedDate ?: null;
        $updateStage->canceledBy     = $stage->canceledBy;
        $updateStage->canceledDate   = $stage->canceledDate ?: null;
        $updateStage->lastEditedBy   = $this->app->user->account;
        $updateStage->lastEditedDate = helper::now();
        $updateStage->estimate       = $stage->estimate;
        $updateStage->consumed       = $stage->consumed;
        $updateStage->left           = $stage->left;
        $updateStage->deleted        = 0;

        $this->app->loadLang('stage');
        $oldStage = $this->fetchByID($stage->id, 'project');
        $project  = $this->fetchByID($oldStage->project, 'project');
        $updateAttribute = array_search($stage->attribute, $project->model == 'ipd' ? $this->lang->stage->ipdTypeList : $this->lang->stage->typeList, true);
        if($updateAttribute) $updateStage->attribute = $updateAttribute;
        $updateStage->parent = $stage->parent ?: $project->id;

        $this->dao->update(TABLE_PROJECT)->data($updateStage)->where('id')->eq($stage->id)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        if($oldStage->deleted == '1')
        {
            $this->loadModel('user');

            /* 恢复用户的执行权限。 */
            $this->user->updateUserView(array($stage->id), 'sprint');

            /* 恢复用户的产品权限。 */
            $products = $this->loadModel('product')->getProducts($stage->id, 'all', '', false);
            if(!empty($products)) $this->user->updateUserView(array_keys($products), 'product');

            /* 恢复文档库。*/
            $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(0)->where('execution')->eq($stage->id)->exec();

            /* 标记为已还原。*/
            $deleteActionID = $this->dao->select('id')->from(TABLE_ACTION)
                ->where('objectType')->eq('execution')
                ->andWhere('objectID')->eq($oldStage->id)
                ->andWhere('project')->eq($oldStage->project)
                ->andWhere('execution')->eq($oldStage->id)
                ->andWhere('action')->eq('deleted')
                ->fetchPairs('id');
            $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->in($deleteActionID)->exec();
            $this->action->create('execution', (int)$oldStage->id, 'undeletedbyrollback');
        }
        else
        {
            $changes = common::createChanges($oldStage, $updateStage);
            if(!empty($changes))
            {
                $actionID = $this->action->create('execution', (int)$oldStage->id, 'editedbyrollback');
                if($actionID) $this->action->logHistory($actionID, $changes);
            }
        }

        return true;
    }

    /**
     * Rollback task.
     * 回滚任务。
     *
     * @param  object $task
     * @access public
     * @return bool
     */
    public function rollbackTask(object $task): bool
    {
        list($executionID, $taskID) = explode('-', $task->id);

        $updateTask = new stdclass();
        $updateTask->execution      = $executionID;
        $updateTask->story          = (int)trim($task->story, '#') ?: 0;
        $updateTask->estStarted     = date('Y-m-d', strtotime($task->begin)) ?: null;
        $updateTask->deadline       = date('Y-m-d', strtotime($task->deadline)) ?: null;
        $updateTask->estimate       = $task->estimate;
        $updateTask->consumed       = $task->consumed;
        $updateTask->left           = $task->left;
        $updateTask->status         = $task->rawStatus;
        $updateTask->pri            = $task->pri ?: 0;
        $updateTask->mailto         = $task->mailto;
        $updateTask->keywords       = $task->keywords;
        $updateTask->finishedBy     = $task->finishedBy;
        $updateTask->closedBy       = $task->closedBy;
        $updateTask->closedDate     = $task->closedDate ?: null;
        $updateTask->closedReason   = $task->closedReason;
        $updateTask->canceledBy     = $task->canceledBy;
        $updateTask->canceledDate   = $task->canceledDate ?: null;
        $updateTask->activatedDate  = $task->activatedDate ?: null;
        $updateTask->lastEditedBy   = $this->app->user->account;
        $updateTask->lastEditedDate = helper::now();
        $updateTask->deleted        = 0;

        $this->app->loadLang('task');
        $updateTask->type = array_search($task->taskType, $this->lang->task->typeList, true);

        /* parent带-的代表任务，不带-的代表阶段，当记录的为阶段时任务的parent为0。*/
        $updateTask->parent = strpos((string)$task->parent, '-') !== false ? explode('-', $task->parent)[1] : 0;

        if(preg_match('/<span[^>]*class=[\'"]gantt_title[\'"]>(.+?)<\/span>/', $task->text, $taskName))
        {
            $updateTask->name = preg_replace('/^#\d+\s+/', '', $taskName[1]);
        }

        /* 当任务名称、计划开始、截止时间被修改时，增加一个版本。*/
        $oldTask = $this->fetchByID((int)$taskID, 'task');
        if($oldTask->name != $updateTask->name || $oldTask->estStarted != $updateTask->estStarted || $oldTask->deadline != $updateTask->deadline)
        {
            $updateTask->version = $oldTask->version + 1;

            $taskSpec = new stdclass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $updateTask->version;
            $taskSpec->name       = $updateTask->name;
            $taskSpec->estStarted = $updateTask->estStarted;
            $taskSpec->deadline   = $updateTask->deadline;
            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->exec();
        }

        $this->dao->update(TABLE_TASK)->data($updateTask)->where('id')->eq($taskID)->exec();
        if(dao::isError()) return false;

        $this->loadModel('action');
        if($oldTask->deleted == '1')
        {
            /* 标记为已还原。*/
            $deleteActionID = $this->dao->select('id')->from(TABLE_ACTION)
                ->where('objectType')->eq('task')
                ->andWhere('objectID')->eq($oldTask->id)
                ->andWhere('project')->eq($oldTask->project)
                ->andWhere('execution')->eq($oldTask->execution)
                ->andWhere('action')->eq('deleted')
                ->fetchPairs('id');
            $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->in($deleteActionID)->exec();
            $this->action->create('task', (int)$taskID, 'undeletedbyrollback');
        }
        else
        {
            $changes = common::createChanges($oldTask, $updateTask);
            if(!empty($changes))
            {
                $actionID = $this->action->create('task', (int)$taskID, 'editedbyrollback');
                if($actionID) $this->action->logHistory($actionID, $changes);
            }
        }
        return true;
    }

    /**
     * Rollback point.
     * 回滚评审点。
     *
     * @param  object $targetPoint
     * @param  object $currentPoint
     * @access public
     * @return bool
     */
    public function rollbackPoint(object $targetPoint, ?object $currentPoint = null): bool
    {
        $pointObjectID = explode('-', $targetPoint->id)[2];
        $this->dao->update(TABLE_OBJECT)->set('enabled')->eq(1)->where('id')->eq($pointObjectID)->exec();
        if(dao::isError()) return false;

        /* 当前版本中没有评审点，目标版本的评审回滚到待评审。*/
        if(empty($currentPoint)) return $this->recallReview((int)$targetPoint->reviewID);

        /* 目标版本与当前版本评审状态一致时，评审信息用最新的评审信息，不用改动直接返回。*/
        if($targetPoint->rawStatus == $currentPoint->rawStatus) return true;

        /* 目标版本为未提交时，将评审点对应的review都删除。*/
        if(empty($targetPoint->reviewID))
        {
            $this->dao->update(TABLE_REVIEW)->set('deleted')->eq(1)->where('object')->eq($pointObjectID)->andWhere('type')->eq('decision')->exec();
            return !dao::isError();
        }

        /* 目标版本与当前版本评审状态不一致的情况，还原目标版本的评审并回滚到待评审，删除当前版本的评审。*/
        if($targetPoint->reviewID != $currentPoint->reviewID) $this->dao->update(TABLE_REVIEW)->set('deleted')->eq(1)->where('id')->eq($currentPoint->reviewID)->exec();
        return $this->recallReview((int)$targetPoint->reviewID);
    }

    /**
     * Recall review.
     * 撤销评审。
     *
     * @param  int    $reviewID
     * @access public
     * @return bool
     */
    public function recallReview(int $reviewID): bool
    {
        if(empty($reviewID)) return true;

        /* 已删除的评审还原。*/
        $this->dao->update(TABLE_REVIEW)->set('deleted')->eq(0)->where('id')->eq($reviewID)->exec();
        if(dao::isError()) return false;

        /* 评审状态回滚为待评审。*/
        $this->dao->update(TABLE_REVIEW)->set('status')->eq('draft')->set('result')->eq('')->where('id')->eq($reviewID)->exec();
        if(dao::isError()) return false;

        /* 评审节点更新为done。*/
        $approvalID = $this->dao->select('approval')->from(TABLE_APPROVALOBJECT)
            ->where('objectType')->eq('review')
            ->andWhere('objectID')->eq($reviewID)
            ->orderBy('id_desc')
            ->limit(1)
            ->fetch('approval');

        $this->dao->update(TABLE_APPROVALNODE)
            ->set('status')->eq('done')
            ->set('result')->eq('ignore')
            ->where('approval')->eq($approvalID)
            ->andWhere('status')->notin('done,forward,reverted')
            ->exec();
        return !dao::isError();
    }

    /**
     * Rollback task relation.
     * 回滚任务依赖关系。
     *
     * @param  int    $projectID
     * @param  array  $relations
     * @access public
     * @return bool
     */
    public function rollbackTaskRelation(int $projectID, array $relations): bool
    {
        if(empty($relations))
        {
            $this->dao->delete()->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->exec();
            return !dao::isError();
        }

        $projectRelationIdList = $this->dao->select('id')->from(TABLE_RELATIONOFTASKS)->where('project')->eq($projectID)->fetchPairs('id');
        foreach($relations as $relation)
        {
            list($sourceExecution, $sourceTaskID) = explode('-', $relation->source);
            list($targetExecution, $targetTaskID) = explode('-', $relation->target);

            /* 0-完成开始; 1-开始开始; 2-完成完成; 3-开始完成 */
            $condition = array(0 => 'end',   1 => 'begin', 2 => 'end', 3 => 'begin');
            $action    = array(0 => 'begin', 1 => 'begin', 2 => 'end', 3 => 'end');

            $updateRelation = new stdclass();
            $updateRelation->execution = "{$targetExecution},{$sourceExecution}";
            $updateRelation->pretask   = $sourceTaskID;
            $updateRelation->condition = $condition[$relation->type];
            $updateRelation->task      = $targetTaskID;
            $updateRelation->action    = $action[$relation->type];

            if(!isset($projectRelationIdList[$relation->id]))
            {
                $updateRelation->project = $projectID;
                $this->dao->insert(TABLE_RELATIONOFTASKS)->data($updateRelation)->exec();
            }
            else
            {
                $this->dao->update(TABLE_RELATIONOFTASKS)->data($updateRelation)->where('id')->eq($relation->id)->exec();
                unset($projectRelationIdList[$relation->id]);
            }

            if(dao::isError()) return false;
        }

        if(!empty($projectRelationIdList))
        {
            $this->dao->delete()->from(TABLE_RELATIONOFTASKS)->where('id')->in($projectRelationIdList)->exec();
            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * Delete extra stage and task.
     * 删除多余的阶段和任务。
     *
     * @param  array  $stages
     * @param  array  $tasks
     * @access public
     * @return bool
     */
    public function deleteExtraStageAndTask(array $stages, array $tasks): bool
    {
        if(empty($stages) && empty($tasks)) return true;

        /* 删除回滚版本中没有的阶段。*/
        if(!empty($stages))
        {
            $this->loadModel('execution');
            $this->loadModel('action');
            foreach($stages as $stageID)
            {
                $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->eq($stageID)->exec();
                if(dao::isError()) return false;

                $this->action->create('execution', (int)$stageID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
                $this->action->create('execution', (int)$stageID, 'deletedbyrollback');
                $this->execution->updateUserView($stageID);
            }
        }

        /* 删除回滚版本中没有的任务。*/
        if(!empty($tasks))
        {
            $this->loadModel('task');
            $this->loadModel('story');
            $this->loadModel('action');
            foreach($tasks as $taskID)
            {
                $this->dao->update(TABLE_TASK)->set('deleted')->eq(1)->where('id')->eq($taskID)->exec();
                if(dao::isError()) return false;

                $this->action->create('task', (int)$taskID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
                $this->action->create('task', (int)$taskID, 'deletedbyrollback');

                $task = $this->task->fetchByID((int)$taskID);
                if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
                if($task->story) $this->story->setStage($task->story);
            }
        }
        return true;
    }

    /**
     * 设置任务的路径。
     * Set task path.
     *
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function setTaskPath(int $taskID): bool
    {
        $task   = $this->dao->select('parent,path')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $parent = $this->dao->select('id,parent,path')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();
        $path   = empty($parent) ? ",{$taskID}," : "{$parent->path}{$taskID},";

        $this->dao->update(TABLE_TASK)->set('path')->eq($path)->where('id')->eq($taskID)->exec();
        if(dao::isError()) return false;

        $children = $this->dao->select('id')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('parent')->eq($taskID)->fetchPairs('id');
        if(empty($children))
        {
            $this->dao->update(TABLE_TASK)->set('isParent')->eq(0)->where('id')->eq($taskID)->exec();
            return true;
        }

        $this->dao->update(TABLE_TASK)->set('isParent')->eq(1)->where('id')->eq($taskID)->exec();

        foreach($children as $id) $this->setTaskPath($id);
        return true;
    }
}
