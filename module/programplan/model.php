<?php
declare(strict_types=1);

/**
 * The model file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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
        $pairs   = array(0 => '');
        $parents = array();

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
     * @access public
     * @return string|array
     */
    public function getDataForGantt(int $projectID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true): string|array
    {
        $plans   = $this->getStage($projectID, $productID, 'all', 'order');
        $project = $this->loadModel('project')->getById($projectID);

        /* Set plan baseline data. */
        if($baselineID)
        {
            $baseline = $this->loadModel('cm')->getByID($baselineID);
            $oldData  = json_decode($baseline->data);
            $plans    = $this->programplanTao->setPlanBaseline($oldData->stage, $plans);
        }

        /* Set plan for gantt view. */
        $result = $this->programplanTao->initGanttPlans($plans);
        $datas          = $result['datas'];
        $planIdList     = $result['planIdList'];
        $stageIndex     = $result['stageIndex'];
        $reviewDeadline = $result['reviewDeadline'];

        /* Judge whether to display tasks under the stage. */
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");

        /* Set task baseline data. */
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in($planIdList)->orderBy('execution_asc, order_asc, id_desc')->fetchAll('id');
        if($baselineID) $this->programplanTao->setTaskBaseline(isset($oldData->task) ? $oldData->task : array(), $tasks); // Set task baseline.

        /* Set task for gantt view. */
        $result = $this->programplanTao->setTask($tasks, $plans, $selectCustom, $datas, $stageIndex);
        $datas      = $result['datas'];
        $stageIndex = $result['stageIndex'];

        /* Build data for ipd. */
        if($project->model == 'ipd' and $datas) $datas = $this->programplanTao->buildGanttData4IPD($datas, $projectID, $productID, $selectCustom, $reviewDeadline);

        /* Calculate the progress of the phase. */
        $datas = $this->programplanTao->setStageSummary($datas, $stageIndex);

        /* Set relation task data. */
        $datas['links'] = $this->programplanTao->buildGanttLinks($planIdList);
        $datas['data'] = isset($datas['data']) ? array_values($datas['data']) : array();
        return $returnJson ? json_encode($datas) : $datas;
    }

    /**
     * 获取按照指派给分组甘特图相关数据。
     * Gets Gantt chart related data as assigned to the group.
     *
     * @param  int     $executionID
     * @param  int     $productID
     * @param  int     $baselineID
     * @param  string  $selectCustom
     * @param  bool    $returnJson
     * @access public
     * @return string|array
     */
    public function getDataForGanttGroupByAssignedTo(int $executionID, int $productID, int $baselineID = 0, string $selectCustom = '', bool $returnJson = true): string|array
    {
        $datas       = array();
        $stageIndex  = array();

        $plans      = $this->getStage($executionID, $productID);
        $planIdList = array_column($plans, 'id');
        $users      = $this->loadModel('user')->getPairs('noletter');
        $tasks      = $this->dao->select('*')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in($planIdList)->fetchAll('id');
        $tasksGroup = $this->programplanTao->buildTaskGroup($tasks);

        /* Judge whether to display tasks under the stage. */
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");

        $groupID = 0;
        foreach($tasksGroup as $group => $tasks)
        {
            $groupID --;
            $groupKey  = $groupID . $group;
            $datas['data'][$groupKey] = $this->programplanTao->buildGroupDataForGantt($groupID, $group, $users);

            $realStartDate = array();
            $realEndDate   = array();
            $totalTask     = count($tasks);
            foreach($tasks as $taskID => $task)
            {
                $dateLimit = $this->programplanTao->getTaskDateLimit($task, zget($plans, $task->execution, null));
                if(strpos($selectCustom, 'task') !== false) $datas['data'][$task->id] = $this->programplanTao->buildTaskDataForGantt($task, $dateLimit, $groupID, $tasks);

                if(!empty($dateLimit['start'])) $realStartDate[] = strtotime($dateLimit['start']);
                if(!empty($dateLimit['end']))   $realEndDate[]   = strtotime($dateLimit['end']);

                if(!isset($stageIndex[$groupKey]['totalConsumed'])) $stageIndex[$groupKey]['totalConsumed'] = 0;
                if(!isset($stageIndex[$groupKey]['totalReal']))     $stageIndex[$groupKey]['totalReal']     = 0;
                if(!isset($stageIndex[$groupKey]['totalEstimate'])) $stageIndex[$groupKey]['totalEstimate'] = 0;
                $stageIndex[$groupKey]['totalConsumed'] += $task->consumed;
                $stageIndex[$groupKey]['totalReal']     += $task->left + $task->consumed;
                $stageIndex[$groupKey]['totalEstimate'] += $task->estimate;
            }

            /* Calculate group realBegan and realEnd. */
            if(!empty($realStartDate)) $datas['data'][$groupKey]->realBegan = date('Y-m-d', min($realStartDate));
            if(!empty($realEndDate) and (count($realEndDate) == $totalTask)) $datas['data'][$groupKey]->realEnd = date('Y-m-d', max($realEndDate));
        }

        $datas = $this->programplanTao->setStageSummary($datas, $stageIndex);
        $datas['links'] = $this->programplanTao->buildGanttLinks($planIdList);
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
     * @access public
     * @return bool
     */
    public function create(array $plans, int $projectID = 0, int $productID = 0, int $parentID = 0): bool
    {
        if(empty($plans)) dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
        if(!$this->isCreateTask($parentID)) dao::$errors['message'][] = $this->lang->programplan->error->createdTask;
        if(dao::isError()) return false;

        /* Get linked product by projectID. */
        $this->loadModel('action');
        $this->loadModel('execution');
        $linkProducts = $this->programplanTao->getLinkProductsForCreate($projectID, $productID);

        /* Set each plans. */
        $updateUserViewIdList = array();
        $milestone            = 0;
        foreach($plans as $plan)
        {
            if($plan->milestone) $milestone = 1;
            if($plan->id)
            {
                $stageID = $plan->id;
                unset($plan->id, $plan->type, $plan->order);
                $changes = $this->programplanTao->updateRow($stageID, $projectID, $plan);
                if(dao::isError()) return false;
                if(empty($changes)) continue;

                $actionID = $this->action->create('execution', $stageID, 'edited');
                $this->action->logHistory($actionID, $changes);

                /* Add PM to stage teams and project teams. */
                if(!empty($plan->PM)) $this->execution->addExecutionMembers($stageID, array($plan->PM));
                if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;
            }
            else
            {
                $stageID = $this->programplanTao->insertStage($plan, $projectID, $productID, $parentID);
                if(dao::isError()) return false;

                $project = $this->fetchByID($projectID, 'project');
                $extra   = ($project && $project->hasProduct and !empty($linkProducts['products'])) ? implode(',', $linkProducts['products']) : '';
                $this->action->create('execution', $stageID, 'opened', '', $extra);

                $this->execution->updateProducts($stageID, $linkProducts);
                if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;
            }
        }
        /* If child plans has milestone, update parent plan set milestone eq 0 . */
        if($parentID and $milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($parentID)->exec();
        if($updateUserViewIdList) $this->loadModel('user')->updateUserView($updateUserViewIdList, 'sprint');
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
    public function update(int $planID = 0, int $projectID = 0, object|null $plan = null): bool
    {
        if(empty($plan)) return false;
        if($plan->parent > 0)
        {
            /* 如果子阶段有里程碑，那么父阶段的更新为0。 */
            /* If child plan has milestone, update parent plan set milestone eq 0 . */
            $parentStage = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($plan->parent)->andWhere('type')->eq('stage')->fetch();
            if($plan->milestone && $parentStage->milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($plan->parent)->exec();
        }

        $changes = $this->programplanTao->updateRow($planID, $projectID, $plan);
        if(dao::isError()) return false;

        /* Synchronously update sub-phase permissions. */
        $childIdList = $this->dao->select('id')->from(TABLE_PROJECT)->where('parent')->eq($planID)->fetchAll('id');
        if(!empty($childIdList)) $this->dao->update(TABLE_PROJECT)->set('acl')->eq($plan->acl)->where('id')->in(array_keys($childIdList))->exec();

        $this->setTreePath($planID);
        $this->updateSubStageAttr($planID, $plan->attribute);

        if($plan->acl != 'open')
        {
            $planIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('path')->like("%,$planID,%")->andWhere('type')->ne('project')->fetchAll('id');
            $this->loadModel('user')->updateUserView(array_keys($planIdList), 'sprint');
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
     * @access public
     * @return array
     */
    public function getParentStageList(int $executionID, int $planID, int $productID): array
    {
        $parentStage = $this->programplanTao->getParentStages($executionID, $planID, $productID);
        if(!$parentStage) return array();

        $plan = $this->getByID($planID);
        foreach($parentStage as $key => $stage)
        {
            $isCreate    = $this->isCreateTask($key);
            $parentTypes = $this->getParentChildrenTypes($key);

            if(!$isCreate && $key != $plan->parent) unset($parentStage[$key]);
            if($plan->type == 'stage' && (isset($parentTypes['sprint']) || isset($parentTypes['kanban']))) unset($parentStage[$key]);
            if(($plan->type == 'sprint' || $plan->type == 'kanban') && isset($parentTypes['stage'])) unset($parentStage[$key]);
        }
        $parentStage[0] = $this->lang->programplan->emptyParent;
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
     * @return bool
     */
    public function computeProgress(int $stageID, string $action = '', bool $isParent = false): bool
    {
        $stage = $this->loadModel('execution')->getByID($stageID);
        if(empty($stage) || empty($stage->path)) return false;

        $project = $this->loadModel('project')->getByID($stage->project);
        $model   = zget($project, 'model', '');
        if(empty($stage) or empty($stage->path) or (!in_array($model, array('waterfall','waterfallplus','ipd','research')))) return false;

        $action       = strtolower($action);
        $parentIdList = array_reverse(explode(',', trim($stage->path, ',')));
        foreach($parentIdList as $id)
        {
            $parent = $this->execution->getByID((int)$id);
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

            $result       = $this->programplanTao->getNewParentAndAction($statusCount, $parent, (int)$startTasks, $action, $project);
            $newParent    = $result['newParent'] ?? null;
            $parentAction = $result['parentAction'] ?? '';

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
}
