<?php
declare(strict_types=1);
/**
 * The tao file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lanzongjun <lanzongjun@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class programplanTao extends programplanModel
{
    /**
     * 获取阶段信息。
     * Get stage list.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @access protected
     * @return array
     */
    protected function getStageList(int $executionID, int $productID, string $browseType, string $orderBy = 'id_asc'): array
    {
        if(empty($executionID)) return array();
        $projectModel = $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('model');

        return $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('1 = 1')
            ->beginIF(!in_array($projectModel, array('waterfallplus', 'ipd')))->andWhere('t1.type')->eq('stage')->fi()
            ->beginIF($productID)->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($browseType == 'all' || $browseType == 'leaf')->andWhere('t1.project')->eq($executionID)->fi()
            ->beginIF($browseType == 'parent')->andWhere('t1.parent')->eq($executionID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 更新项目阶段。
     * update program plan.
     *
     * @param  object    $plan
     * @param  object    $oldPlan
     * @access protected
     * @return array|false
     */
    protected function updateRow(int $planID, int $projectID, object|null $plan): array|false
    {
        if(empty($plan)) return false;

        $oldPlan     = $this->fetchByID($planID);
        $planChanged = ($oldPlan->name != $plan->name || $oldPlan->milestone != $plan->milestone || $oldPlan->begin != $plan->begin || $oldPlan->end != $plan->end);

        if($planChanged) $plan->version = $oldPlan->version + 1;
        if(empty($plan->parent)) $plan->parent = $projectID;
        $parentStage   = empty($projectID) ? null : $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($plan->parent)->andWhere('type')->eq('stage')->fetch();
        $relatedIdList = $this->loadModel('execution')->getRelatedExecutions($planID);
        $relatedIdList = !empty($relatedIdList) ? implode(',', array_keys($relatedIdList)) : '0';
        $setCode       = !empty($this->config->setCode);
        $getName       = ($relatedIdList && $oldPlan->project && $parentStage && $oldPlan->parent) ? true : false;

        /* Fix bug #22030. Reset field name for show dao error. */
        $this->lang->project->name = $this->lang->programplan->name;
        $this->lang->project->code = $this->lang->execution->code;

        $this->dao->update(TABLE_PROJECT)->data($plan)->autoCheck()
            ->checkIF(!empty($plan->name) && $getName, 'name', 'unique', "id in ({$relatedIdList}) and type in ('sprint','stage') and `project` = {$oldPlan->project} and `deleted` = '0' and `parent` = {$oldPlan->parent}")
            ->checkIF(!empty($plan->code) && $setCode, 'code', 'unique', "id != {$planID} and type in ('sprint','stage','kanban') and `deleted` = '0'")
            ->where('id')->eq($planID)
            ->exec();

        if($planChanged) $this->insertProjectSpec($planID, $plan);
        if(dao::isError()) return false;
        return common::createChanges($oldPlan, $plan);
    }

    /**
     * 获取父阶段键值对。
     * Get parent stage list.
     *
     * @param  int    $executionID
     * @param  int    $planID
     * @param  int    $productID
     * @access protected
     * @return array|false
     */
    protected function getParentStages(int $executionID, int $planID, int $productID): array|false
    {
        $parentStage = $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
            ->beginIF($productID)
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->fi()
            ->where('t1.project')->eq($executionID)
            ->beginIF($productID)
            ->andWhere('t2.product')->eq($productID)
            ->fi()
            ->andWhere('t1.type')->eq('stage')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.path')->notlike("%,$planID,%")
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->orderBy('t1.id desc')
            ->fetchPairs();

        if(dao::isError()) return false;
        return $parentStage;
    }

    /**
     * 根据action获取excution。
     * Get new parent and action.
     *
     * @param  array     $statusCount
     * @param  object    $parent
     * @param  int       $startTasks
     * @param  string    $action
     * @param  object    $project
     * @access protected
     * @return array
     */
    protected function getNewParentAndAction(array $statusCount, object $parent, int $startTasks, string $action, object $project): array
    {
        $count        = count($statusCount);
        $newParent    = null;
        $parentAction = '';
        $this->loadModel('execution');
        if(isset($statusCount['wait']) && $count == 1 && helper::isZeroDate($parent->realBegan) && $startTasks == 0)
        {
            if($parent->status == 'wait') return array('newParent' => null, 'parentAction' => '');

            $newParent    = $this->execution->buildExecutionByStatus('wait');
            $parentAction = 'waitbychild';
        }
        elseif(isset($statusCount['closed']) && $count == 1)
        {
            if($parent->status == 'closed') return array('newParent' => null, 'parentAction' => '');
            if($project->model == 'ipd' and $parent->parent == $project->id) return array('newParent' => null, 'parentAction' => '');

            $newParent    = $this->execution->buildExecutionByStatus('closed');
            $parentAction = 'closedbychild';
        }
        elseif(isset($statusCount['suspended']) && ($count == 1 || (isset($statusCount['closed']) && $count == 2)))
        {
            if($parent->status == 'suspended') return array('newParent' => null, 'parentAction' => '');

            $newParent    = $this->execution->buildExecutionByStatus('suspended');
            $parentAction = 'suspendedbychild';
        }
        else
        {
            if($parent->status == 'doing') return array('newParent' => null, 'parentAction' => '');

            $newParent    = $this->execution->buildExecutionByStatus('doing');
            $parentAction = $parent->status == 'wait' ? 'startbychildstart' : 'startbychild' . $action;
        }
        return array('newParent' => $newParent, 'parentAction' => $parentAction);
    }

    /**
     * 设置任务基线数据。
     * Set task baseline data.
     *
     * @param  array   $oldTasks
     * @param  array   $tasks
     * @access protected
     * @return array
     */
    protected function setTaskBaseline(array $oldTasks, array $tasks): array
    {
        foreach($oldTasks as $id => $oldTask)
        {
            if(!isset($tasks[$id])) continue;
            $tasks[$id]->version    = $oldTask->version;
            $tasks[$id]->name       = $oldTask->name;
            $tasks[$id]->estStarted = $oldTask->estStarted;
            $tasks[$id]->deadline   = $oldTask->deadline;
        }

        return $tasks;
    }

    /**
     * 设置甘特图阶段数据。
     * Set the Gantt chart stage data.
     *
     * @param  array   $plans
     * @access protected
     * @return array
     */
    protected function initGanttPlans(array $plans): array
    {
        $this->app->loadLang('stage');

        $datas = $stageIndex = $planIdList = $reviewDeadline = array();
        $today       = helper::today();
        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";
        foreach($plans as $plan)
        {
            $plan->isParent = false;
            if(isset($plans[$plan->parent])) $plans[$plan->parent]->isParent = true;
        }

        foreach($plans as $plan)
        {
            $planIdList[$plan->id] = $plan->id;
            $reviewDeadline[$plan->id]['stageEnd'] = $plan->end;

            $data = $this->buildPlanDataForGantt($plan);

            /* Determines if the object is delay. */
            $data->delay     = $this->lang->programplan->delayList[0];
            $data->delayDays = 0;
            if($today > $data->endDate and $plan->status != 'closed')
            {
                $data->delay     = $this->lang->programplan->delayList[1];
                $data->delayDays = helper::diffDate($today, $data->endDate);
            }

            if($data->endDate > $data->start_date) $data->duration = helper::diffDate($data->endDate, $data->start_date) + 1;
            if(empty($data->start_date) or empty($data->endDate)) $data->duration = 1;

            $datas['data'][$plan->id] = $data;
            $stageIndex[$plan->id]    = array('planID' => $plan->id, 'parent' => $plan->parent, 'totalEstimate' => 0, 'totalConsumed' => 0, 'totalReal' => 0);
        }
        return array('datas' => $datas, 'stageIndex' => $stageIndex, 'planIdList' => $planIdList, 'reviewDeadline' => $reviewDeadline);
    }

    /**
     * 设置甘特图任务数据。
     * Set the Gantt chart task data.
     *
     * @param  array     $tasks
     * @param  array     $plans
     * @param  string    $selectCustom
     * @param  array     $datas
     * @param  array     $stageIndex
     * @access protected
     * @return array
     */
    protected function setTask(array $tasks, array $plans, string $selectCustom, array $datas, array $stageIndex): array
    {
        $this->app->loadLang('task');
        $today     = helper::today();
        $taskTeams = $this->dao->select('task,account')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task', 'account');
        $users     = $this->loadModel('user')->getPairs('noletter');

        foreach($tasks as $task)
        {
            $dateLimit    = $this->getTaskDateLimit($task, zget($plans, $task->execution, null));
            $data         = $this->buildTaskDataForGantt($task, $dateLimit);
            $data->id     = $task->execution . '-' . $task->id;
            $data->parent = $task->parent > 0 ? $task->execution . '-' . $task->parent : $task->execution;

            /* Determines if the object is delay. */
            $data->delay     = $this->lang->programplan->delayList[0];
            $data->delayDays = 0;
            if($today > $dateLimit['end'] and $execution->status != 'closed')
            {
                $data->delay     = $this->lang->programplan->delayList[1];
                $data->delayDays = helper::diffDate(($task->status == 'done' || $task->status == 'closed') ? $task->finishedDate : $today, $dateLimit['end']);
            }

            /* If multi task then show the teams. */
            if($task->mode == 'multi' and !empty($taskTeams[$task->id])) $data->owner_id = implode(',', array_map(function($assignedTo) use($users){return zget($users, $assignedTo);}, array_keys($taskTeams[$task->id])));

            if(strpos($selectCustom, 'task') !== false) $datas['data'][$data->id] = $data;
            foreach($stageIndex as $index => $stage)
            {
                if($stage['planID'] != $task->execution) continue;
                if(!isset($stageIndex[$index])) continue;

                $stageIndex[$index]['totalEstimate'] += $task->estimate;
                $stageIndex[$index]['totalConsumed'] += $task->parent == '-1' ? 0 : $task->consumed;
                $stageIndex[$index]['totalReal']     += ((($task->status == 'closed' || $task->status == 'cancel') ? 0 : $task->left) + $task->consumed);

                $parent = $stage['parent'];
                if(!isset($stageIndex[$parent])) continue;

                $stageIndex[$parent]['totalEstimate'] += $task->estimate;
                $stageIndex[$parent]['totalConsumed'] += $task->parent == '-1' ? 0 : $task->consumed;
                $stageIndex[$parent]['totalReal']     += ((($task->status == 'closed' || $task->status == 'cancel') ? 0 : $task->left) + $task->consumed);
            }
        }
        return array('datas' => $datas, 'stageIndex' => $stageIndex);
    }

    /**
     * 获取子阶段数量。
     * Get stage count.
     *
     * @param  int    $planID
     * @param  string $mode
     * @access protected
     * @return int
     */
    protected function getStageCount(int $planID, string $mode = ''): int
    {
        return $this->dao->select('COUNT(*) AS count')->from(TABLE_PROJECT)
            ->where('parent')->eq($planID)
            ->andWhere('type')->eq('stage')
            ->beginIF($mode == 'milestone')->andWhere('milestone')->eq(1)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch('count');
    }

    /**
     * 计算阶段在数据库中的位置序号。
     * Compute the new order for programplan. if orders is not set then begin with the order in zt_project table.
     *
     * @param  array     $orders
     * @param  array     $plans
     * @access protected
     * @return array
     */
    protected function computeOrders(array $orders, array $plans): array
    {
        if(empty($orders)) $orders = array();
        asort($orders);

        $planCount = count($plans);
        if(count($orders) < $planCount)
        {
            $orderIndex = empty($orders) ? 0 : count($orders);
            $lastID     = $this->dao->select('id')->from(TABLE_EXECUTION)->orderBy('id_desc')->limit(1)->fetch('id');
            for($i = $orderIndex; $i < $planCount; $i ++)
            {
                $lastID ++;
                $orders[$i] = $lastID * 5;
            }
        }

        return $orders;
    }

    /**
     * 插入项目细节。
     * Insert project spec.
     * @param  int       $planID
     * @param  object    $plan
     * @access protected
     * @return bool
     */
    protected function insertProjectSpec(int $planID, object $plan): bool
    {
        if(empty($planID)) return false;

        $spec = new stdclass();
        $spec->project   = $planID;
        $spec->version   = $plan->version;
        $spec->name      = $plan->name;
        $spec->milestone = $plan->milestone;
        $spec->begin     = $plan->begin;
        $spec->end       = $plan->end;

        $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();
        return !dao::isError();
    }

    /**
     * 获取阶段百分比。
     * Get total percent.
     *
     * @param  object    $stage
     * @param  bool      $parent
     * @access public
     * @return int|float
     */
    protected function getTotalPercent(object $stage, bool $parent = false): int|float
    {
        /* When parent is equal to true, query the total workload of the subphase. */
        $executionID = $parent ? $stage->id : $stage->project;
        $plans = $this->getStageList((int)$executionID, (int)$stage->product, 'parent');

        $totalPercent = 0;
        $stageID      = $stage->id;
        foreach($plans as $id => $stage)
        {
            if($id == $stageID) continue;
            $totalPercent += $stage->percent;
        }

        return $totalPercent;
    }

    /**
     * Get point end date.
     *
     * @param  int       $planID
     * @param  object    $point
     * @param  array     $reviewDeadline
     * @access protected
     * @return string
     */
    protected function getPointEndDate(int $planID, object $point, array $reviewDeadline): string
    {
        if($point->end and !helper::isZeroDate($point->end)) return $point->end;

        $end = $reviewDeadline[$planID]['stageEnd'];
        if(strpos($point->category, "DCP") !== false) return $this->getReviewDeadline($end, 2);
        if(strpos($point->category, "TR") !== false)
        {
            if(isset($reviewDeadline[$planID]['taskEnd']) and !helper::isZeroDate($reviewDeadline[$planID]['taskEnd'])) return $reviewDeadline[$planID]['taskEnd'];
            return $this->getReviewDeadline($end);
        }
        return $end;
    }

    /**
     * 构建IPD版本的甘特图数据。
     * Build gantt's data for ipd edition.
     *
     * @param  array     $datas
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $selectCustom
     * @param  array     $reviewDeadline
     * @access protected
     * @return array
     */
    protected function buildGanttData4IPD(array $datas, int $projectID, int $productID, string $selectCustom, array $reviewDeadline): array
    {
        if($this->config->edition != 'ipd') return $datas;

        $this->loadModel('review');
        $this->app->loadConfig('stage');
        $reviewPoints = $this->dao->select('t1.*, t2.status, t2.lastReviewedDate,t2.id as reviewID')->from(TABLE_OBJECT)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.id = t2.object')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.product')->eq($productID)
            ->fetchAll('id');

        foreach($datas['data'] as $plan)
        {
            if($plan->type != 'plan') continue;

            foreach($reviewPoints as $id => $point)
            {
                if(!isset($this->config->stage->ipdReviewPoint->{$plan->attribute})) continue;
                if(!isset($point->status)) $point->status = '';

                $categories = $this->config->stage->ipdReviewPoint->{$plan->attribute};
                if(!in_array($point->category, $categories)) continue;

                $dataID = "{$plan->id}-{$point->category}-{$point->id}";
                if($selectCustom && strpos($selectCustom, "point") !== false && !$plan->parent) $datas['data'][$dataID] = $this->buildPointDataForGantt($plan->id, $point, $reviewDeadline);
            }
        }

        return $datas;
    }

    /**
     * 获取创建时的关联产品。
     * Get linkProducts for create.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function getLinkProductsForCreate(int $projectID, int $productID): array
    {
        $project = $this->fetchByID($projectID, 'project');

        $linkProducts = array();
        $linkBranches = array();
        $productList  = $this->loadModel('product')->getProducts($projectID);
        if($project && $project->stageBy)
        {
            $linkProducts = array(0 => $productID);
            if(!empty($productList[$productID])) $linkBranches = array(0 => zget($productList[$productID], 'branches', array()));
        }
        else
        {
            $linkProducts = array_keys($productList);
            foreach($linkProducts as $index => $productID)
            {
                if(!empty($productList)) $linkBranches[$index] = $productList[$productID]->branches;
            }
        }

        return array('products' => $linkProducts, 'branch' => $linkBranches);
    }

    /**
     * 插入阶段数据。
     * Insert stage.
     * @param  object    $plan
     * @param  int       $projectID
     * @param  int       $productID
     * @param  int       $parentID
     * @access protected
     * @return int|false
     */
    protected function insertStage(object $plan, int $projectID, int $productID, int $parentID): int|false
    {
        $project = $this->fetchById($projectID, 'project');
        $account = $this->app->user->account;

        unset($plan->id);
        $plan->status        = 'wait';
        $plan->stageBy       = empty($project) ? 'product' : $project->stageBy;
        $plan->version       = 1;
        $plan->parentVersion = $plan->parent == 0 ? 0 : $this->dao->findByID($plan->parent)->from(TABLE_PROJECT)->fetch('version');
        $plan->team          = substr($plan->name,0, 30);
        $plan->openedBy      = $account;
        $plan->openedDate    = helper::now();
        $plan->openedVersion = $this->config->version;
        if(!isset($plan->acl)) $plan->acl = $this->dao->findByID($plan->parent)->from(TABLE_PROJECT)->fetch('acl');
        $this->dao->insert(TABLE_PROJECT)->data($plan)->exec();

        if(dao::isError()) return false;

        $stageID = (int)$this->dao->lastInsertID();
        $this->insertProjectSpec($stageID, $plan);

        /* Ipd project create default review points. */
        if($project && $project->model == 'ipd' && $this->config->edition == 'ipd' && !$parentID) $this->loadModel('review')->createDefaultPoint($project->id, $productID, $plan->attribute);

        if($plan->type == 'kanban')
        {
            $execution = $this->execution->getByID($stageID);
            $this->loadModel('kanban')->createRDKanban($execution);
        }

        $this->loadModel('execution');
        if($projectID) $this->execution->createMainLib($projectID, $stageID);
        $this->execution->addExecutionMembers($stageID, array($account, $plan->PM));

        $this->setTreePath($stageID);
        $this->computeProgress($stageID, 'create');

        return $stageID;
    }

    /**
     * 根据任务列表，构建任务分组。
     * Build task group by assignedTo.
     *
     * @param  array     $task
     * @access protected
     * @return array
     */
    protected function buildTaskGroup(array $tasks): array
    {
        $taskGroup  = array();
        $multiTasks = array();
        foreach($tasks as $taskID => $task)
        {
            $taskGroup[$task->assignedTo][$taskID] = $task;
            if($task->mode == 'multi') $multiTasks[$taskID] = $task->assignedTo;
        }
        if(empty($multiTasks)) return $taskGroup;

        $taskTeams = $this->dao->select('t1.*,t2.realname')->from(TABLE_TASKTEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.task')->in(array_keys($multiTasks))
            ->orderBy('t1.order')
            ->fetchGroup('task', 'id');
        foreach($taskTeams as $taskID => $team)
        {
            $assignedTo = $multiTasks[$taskID];
            foreach($team as $member)
            {
                $account = $member->account;
                if($account == $assignedTo) continue;
                if(!isset($taskGroup[$account])) $taskGroup[$account] = array();

                $taskGroup[$account][$taskID] = clone $tasks[$taskID];
                $taskGroup[$account][$taskID]->id         = $taskID . '_' . $account;
                $taskGroup[$account][$taskID]->realID     = $taskID;
                $taskGroup[$account][$taskID]->assignedTo = $account;
                $taskGroup[$account][$taskID]->realname   = $member->realname;
            }
        }

        return $taskGroup;
    }

    /**
     * Build plan data for gantt.
     *
     * @param  object    $plan
     * @access protected
     * @return object
     */
    protected function buildPlanDataForGantt(object $plan): object
    {
        $start     = helper::isZeroDate($plan->begin)     ? '' : $plan->begin;
        $end       = helper::isZeroDate($plan->end)       ? '' : $plan->end;
        $realBegan = helper::isZeroDate($plan->realBegan) ? '' : $plan->realBegan;
        $realEnd   = helper::isZeroDate($plan->realEnd)   ? '' : $plan->realEnd;

        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";
        $data        = new stdclass();
        $data->id            = $plan->id;
        $data->type          = 'plan';
        $data->text          = empty($plan->milestone) ? $plan->name : $plan->name . $isMilestone;
        $data->name          = $plan->name;
        $data->attribute     = zget($this->lang->stage->typeList, $plan->attribute);
        $data->milestone     = zget($this->lang->programplan->milestoneList, $plan->milestone);
        $data->owner_id      = $plan->PM;
        $data->status        = $this->processStatus('execution', $plan);
        $data->begin         = $start;
        $data->deadline      = $end;
        $data->realBegan     = $realBegan ? substr($realBegan, 0, 10) : '';
        $data->realEnd       = $realEnd ? substr($realEnd, 0, 10) : '';
        $data->parent        = $plan->grade == 1 ? 0 :$plan->parent;
        $data->isParent      = $plan->isParent;
        $data->open          = true;
        $data->start_date    = $start;
        $data->endDate       = $end;
        $data->duration      = 1;
        $data->color         = $this->lang->execution->gantt->stage->color;
        $data->progressColor = $this->lang->execution->gantt->stage->progressColor;
        $data->textColor     = $this->lang->execution->gantt->stage->textColor;
        $data->bar_height    = $this->lang->execution->gantt->bar_height;

        if(!empty($this->config->setPercent)) $data->percent = $plan->percent;
        if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));

        return $data;
    }

    /**
     * Guild point data for gantt.
     *
     * @param  int       $planID
     * @param  object    $point
     * @param  array     $reviewDeadline
     * @access protected
     * @return object
     */
    protected function buildPointDataForGantt(int $planID, object $point, array $reviewDeadline): object
    {
        $statusList = array();
        if(isset($this->lang->review->statusList)) $statusList = $this->lang->review->statusList;

        $end  = $this->getPointEndDate($planID, $point, $reviewDeadline);
        $data = new stdclass();
        $data->id            = $planID . '-' . $point->category . '-' . $point->id;
        $data->reviewID      = $point->reviewID;
        $data->type          = 'point';
        $data->text          = "<i class='icon-seal'></i> " . $point->title;
        $data->name          = $point->title;
        $data->attribute     = '';
        $data->milestone     = '';
        $data->owner_id      = '';
        $data->rawStatus     = $point->status;
        $data->status        = $point->status ? zget($statusList, $point->status) : $this->lang->programplan->wait;
        $data->status        = "<span class='status-{$point->status}'>" . $data->status . '</span>';
        $data->begin         = $end;
        $data->deadline      = $end;
        $data->realBegan     = $point->createdDate;
        $data->realEnd       = $point->lastReviewedDate;;
        $data->parent        = $planID;
        $data->open          = true;
        $data->start_date    = $end;
        $data->endDate       = $end;
        $data->duration      = 1;
        $data->color         = isset($this->lang->programplan->reviewColorList[$point->status]) ? $this->lang->programplan->reviewColorList[$point->status] : '#FC913F';
        $data->progressColor = $this->lang->execution->gantt->stage->progressColor;
        $data->textColor     = $this->lang->execution->gantt->stage->textColor;
        $data->bar_height    = $this->lang->execution->gantt->bar_height;

        if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));

        return $data;
    }

    /**
     * 构建甘特图的分组数据。
     * Build group data for gantt.
     *
     * @param  int       $groupID
     * @param  string    $group
     * @param  array     $users
     * @access protected
     * @return object
     */
    protected function buildGroupDataForGantt(int $groupID, string $group, array $users): object
    {
        $groupName = $group;
        $groupName = zget($users, $group);

        $dataGroup                = new stdclass();
        $dataGroup->id            = $groupID;
        $dataGroup->type          = 'group';
        $dataGroup->text          = $groupName;
        $dataGroup->percent       = '';
        $dataGroup->attribute     = '';
        $dataGroup->milestone     = '';
        $dataGroup->owner_id      = $group;
        $dataGroup->status        = '';
        $dataGroup->begin         = '';
        $dataGroup->deadline      = '';
        $dataGroup->realBegan     = '';
        $dataGroup->realEnd       = '';
        $dataGroup->parent        = 0;
        $dataGroup->open          = true;
        $dataGroup->progress      = '';
        $dataGroup->taskProgress  = '';
        $dataGroup->color         = $this->lang->execution->gantt->stage->color;
        $dataGroup->progressColor = $this->lang->execution->gantt->stage->progressColor;
        $dataGroup->textColor     = $this->lang->execution->gantt->stage->textColor;
        $dataGroup->bar_height    = $this->lang->execution->gantt->bar_height;

        return $dataGroup;
    }

    /**
     * 构建甘特图的任务数据。
     * Build task data for gantt.
     *
     * @param  int       $groupID
     * @param  object    $task
     * @param  array     $dateLimit  array('start' => $start, 'end' => $end, 'realBegan' => $realBegan, 'realEnd' => $realEnd);
     * @param  array     $tasksMap
     * @access protected
     * @return object
     */
    protected function buildTaskDataForGantt(object $task, array $dateLimit, int $groupID = 0, array $tasksMap = array()): object
    {
        $taskPri  = "<span class='label-pri label-pri-%s' title='%s'>%s</span> ";
        $pri      = zget($this->lang->task->priList, $task->pri);
        $priIcon  = sprintf($taskPri, $task->pri, $pri, $pri);
        $progress = $task->consumed ? round($task->consumed / ($task->left + $task->consumed), 3) : 0;

        $data = new stdclass();
        $data->id           = $task->id;
        $data->type         = 'task';
        $data->text         = $priIcon . $task->name;
        $data->percent      = '';
        $data->status       = $this->processStatus('task', $task);
        $data->owner_id     = $task->assignedTo;
        $data->attribute    = '';
        $data->milestone    = '';
        $data->begin        = $dateLimit['start'];
        $data->deadline     = $dateLimit['end'];
        $data->realBegan    = $dateLimit['realBegan'] ? substr($dateLimit['realBegan'], 0, 10) : '';
        $data->realEnd      = $dateLimit['realEnd'] ? substr($dateLimit['realEnd'], 0, 10) : '';
        $data->pri          = $task->pri;
        $data->parent       = ($task->parent > 0 and $task->assignedTo != '' and !empty($tasksMap[$task->parent]->assignedTo)) ? $task->parent : $groupID;
        $data->open         = true;
        $data->progress     = $progress;
        $data->taskProgress = ($progress * 100) . '%';
        $data->start_date   = $dateLimit['start'];
        $data->endDate      = $dateLimit['end'];
        $data->duration     = 1;
        $data->estimate     = $task->estimate;
        $data->consumed     = $task->consumed;
        $data->color         = zget($this->lang->execution->gantt->color, $task->pri, $this->lang->execution->gantt->defaultColor);
        $data->progressColor = zget($this->lang->execution->gantt->progressColor, $task->pri, $this->lang->execution->gantt->defaultProgressColor);
        $data->textColor     = zget($this->lang->execution->gantt->textColor, $task->pri, $this->lang->execution->gantt->defaultTextColor);
        $data->bar_height    = $this->lang->execution->gantt->bar_height;

        if($data->endDate > $data->start_date)                $data->duration = helper::diffDate($data->endDate, $data->start_date) + 1;
        if(empty($data->start_date) or empty($data->endDate)) $data->duration = 1;
        if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));

        return $data;
    }

    /**
     * 构建甘特图的关系链接
     * Build gantt links.
     *
     * @param  array     $planIdList
     * @access protected
     * @return array
     */
    protected function buildGanttLinks(array $planIdList): array
    {
        $this->app->loadConfig('execution');
        if($this->config->edition == 'open') return array();

        $links     = array();
        $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('execution')->in($planIdList)->orderBy('task,pretask')->fetchAll();
        foreach($relations as $relation)
        {
            $link           = array();
            $link['source'] = $relation->execution . '-' . $relation->pretask;
            $link['target'] = $relation->execution . '-' . $relation->task;
            $link['type']   = $this->config->execution->gantt->linkType[$relation->condition][$relation->action];
            $links[]        = $link;
        }
        return $links;
    }

    /**
     * 设置阶段统计数据。
     * Set stage summary.
     *
     * @param  array     $ganttData
     * @param  array     $stages
     * @access protected
     * @return array
     */
    protected function setStageSummary(array $ganttData, array $stages): array
    {
        foreach($stages as $index => $stage)
        {
            if(!isset($ganttData['data'][$index])) continue;

            $progress = empty($stage['totalReal']) ? 0 : round($stage['totalConsumed'] / $stage['totalReal'], 3);
            $ganttData['data'][$index]->progress     = $progress;
            $ganttData['data'][$index]->taskProgress = ($progress * 100) . '%';
            $ganttData['data'][$index]->estimate     = $stage['totalEstimate'];
            $ganttData['data'][$index]->consumed     = $stage['totalConsumed'];
        }
        return $ganttData;
    }

    /**
     * 获取任务任务限制。获取该任务在甘特图中的开始，结束时间。
     * Get task date limit.
     *
     * @param  object      $task
     * @param  object|null $execution
     * @access protected
     * @return array
     */
    protected function getTaskDateLimit(object $task, object|null $execution = null): array
    {
        $estStart  = helper::isZeroDate($task->estStarted)  ? '' : $task->estStarted;
        $estEnd    = helper::isZeroDate($task->deadline)    ? '' : $task->deadline;
        $realBegan = helper::isZeroDate($task->realStarted) ? '' : $task->realStarted;
        $realEnd   = (in_array($task->status, array('done', 'closed')) and !helper::isZeroDate($task->finishedDate)) ? $task->finishedDate : '';

        $start = $realBegan ? $realBegan : $estStart;
        $end   = $realEnd   ? $realEnd   : $estEnd;
        if(empty($start) and $execution) $start = $execution->begin;
        if(empty($end)   and $execution) $end   = $execution->end;
        if($start > $end) $end = $start;

        return array('start' => $start, 'end' => $end, 'realBegan' => $realBegan, 'realEnd' => $realEnd);
    }

    /**
     * Get five days ago.
     *
     * @param  string $date
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getReviewDeadline(string $date, int $counter = 5): string
    {
        if(helper::isZeroDate($date)) return '';

        $this->loadModel('holiday');
        $weekendDays = array(6, 7);
        $timestamp   = strtotime($date);
        $index       = 0;
        while($index < $counter)
        {
            $timestamp  -= 24 * 3600;
            $weekday     = date('N', $timestamp);
            $currentDate = date('Y-m-d', $timestamp);
            if(!in_array($weekday, $weekendDays) and !$this->holiday->isHoliday($currentDate)) $index ++;
        }

        return date('Y-m-d', $timestamp);
    }
}
