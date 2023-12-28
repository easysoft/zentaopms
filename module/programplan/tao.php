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
     * 更新项目阶段。
     * update program plan.
     *
     * @param  object $plan
     * @param  array $conditions
     * @access protected
     * @return bool
     */
    protected function updateRow(object $plan, object $oldPlan, object|bool $parentStage): bool
    {
        $requiredFields = $this->config->programplan->edit->requiredFields ?? '';

        $getname = '';
        if($plan->relatedExecutionsID && $oldPlan->project && $parentStage && $oldPlan->parent) $getname = true;

        $relatedExecutionsID = $plan->relatedExecutionsID;
        $setCode             = $plan->setCode;
        unset($plan->relatedExecutionsID, $plan->setCode);

        $this->dao->update(TABLE_PROJECT)->data($plan)
            ->autoCheck()
            ->batchCheckIF($requiredFields, $requiredFields, 'notempty')
            ->checkIF($plan->end != '0000-00-00', 'end', 'ge', $plan->begin)
            ->checkIF(!empty($plan->percent), 'percent', 'float')
            ->checkIF(!empty($plan->name) && $getname, 'name', 'unique', "id in ({$relatedExecutionsID}) and type in ('sprint','stage') and `project` = {$oldPlan->project} and `deleted` = '0' and `parent` = {$oldPlan->parent}")
            ->checkIF(!empty($plan->code) && $setCode, 'code', 'unique', "id != {$plan->id} and type in ('sprint','stage','kanban') and `deleted` = '0'")
            ->where('id')->eq($plan->id)
            ->exec();

        return !dao::isError();
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
     * @param  array $statusCount
     * @param  object $parent
     * @param  int    $startTasks
     * @param  string $action
     * @param  object $project
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
            if($parent->status != 'wait')
            {
                $newParent    = $this->execution->buildExecutionByStatus('wait');
                $parentAction = 'waitbychild';
            }
        }
        elseif(isset($statusCount['closed']) && $count == 1)
        {
            if($parent->status != 'closed')
            {
                if($project->model == 'ipd' and $parent->parent == $project->id) return array('newParent' => null, 'parentAction' => '');
                $newParent    = $this->execution->buildExecutionByStatus('closed');
                $parentAction = 'closedbychild';
            }
        }
        elseif(isset($statusCount['suspended']) && ($count == 1 || (isset($statusCount['closed']) && $count == 2)))
        {
            if($parent->status != 'suspended')
            {
                $newParent    = $this->execution->buildExecutionByStatus('suspended');
                $parentAction = 'suspendedbychild';
            }
        }
        else
        {
            if($parent->status != 'doing')
            {
                $newParent    = $this->execution->buildExecutionByStatus('doing');
                $parentAction = $parent->status == 'wait' ? 'startbychildstart' : 'startbychild' . $action;
            }
        }
        return array('newParent' => $newParent, 'parentAction' => $parentAction);
    }

    /**
     * 校验提交数据是否必须。
     * Check required items.
     *
     * @param  object $oldPlan
     * @param  object $plan
     * @param  int $projectID
     * @access protected
     * @return bool
     */
    protected function checkRequiredItems(object $oldPlan, object $plan, int $projectID): bool
    {
        /* 校验开始结束时间是否正确。 */
        /* check begin and end date.  */
        if($plan->begin == '0000-00-00') dao::$errors['begin'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->begin);
        if($plan->end   == '0000-00-00') dao::$errors['end'][]   = sprintf($this->lang->error->notempty, $this->lang->programplan->end);
        if(dao::isError()) return false;

        if($plan->parent) $parentStage = $this->getByID($plan->parent);
        if(isset($parentStage) && $plan->begin < $parentStage->begin)
        {
            dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent, $parentStage->begin);
            return false;
        }
        if(isset($parentStage) && $plan->end > $parentStage->end)
        {
            dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parentStage->end);
            return false;
        }

        if($projectID) $this->loadModel('execution')->checkBeginAndEndDate($projectID, $plan->begin, $plan->end, $plan->parent);
        if(dao::isError()) return false;

        $setCode = isset($this->config->setCode) && $this->config->setCode == 1;
        if($setCode && empty($plan->code))
        {
            dao::$errors['code'][] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
            return false;
        }

        /* 对于是否有子阶段进行判断处理。 */
        /* check parent stage. */
        $setPercent = isset($this->config->setPercent) && $this->config->setPercent == 1;
        if($plan->parent > 0)
        {
            $plan->attribute = $parentStage->attribute == 'mix' ? $plan->attribute : $parentStage->attribute;
            $plan->acl       = $parentStage->acl;
            if($setPercent)
            {
                $childrenTotalPercent = $this->getTotalPercent($parentStage, true);
                $childrenTotalPercent = $plan->parent == $oldPlan->parent ? ($childrenTotalPercent - $oldPlan->percent + $plan->percent) : ($childrenTotalPercent + $plan->percent);
                if($childrenTotalPercent > 100) return dao::$errors['percent'][] = $this->lang->programplan->error->percentOver;
            }

            /* 如果子阶段有里程碑，那么父阶段的更新为0。 */
            /* If child plan has milestone, update parent plan set milestone eq 0 . */
            if($plan->milestone && $parentStage->milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($oldPlan->parent)->exec();
        }
        else
        {
            /* Synchronously update sub-phase permissions. */
            $childrenIDList = $this->dao->select('id')->from(TABLE_PROJECT)->where('parent')->eq($oldPlan->id)->fetchAll('id');
            if(!empty($childrenIDList)) $this->dao->update(TABLE_PROJECT)->set('acl')->eq($plan->acl)->where('id')->in(array_keys($childrenIDList))->exec();

            /* 相同父阶段的子阶段工作量占比之和不超过100%。 */
            /* The workload of the parent plan cannot exceed 100%. */
            $oldPlan->parent = $plan->parent;
            if($setPercent)
            {
                $totalPercent    = $this->getTotalPercent($oldPlan);
                $totalPercent    = $totalPercent + $plan->percent;
                if($totalPercent > 100) return dao::$errors['percent'][] = $this->lang->programplan->error->percentOver;
            }
        }

        return true;
    }

    /**
     * 组装里程碑数据。
     * Format milestones use '/'.
     *
     * @param  array  $milestones
     * @param  int    $projectID
     * @access protected
     * @return array
     */
    protected function formatMilestones(array $milestones, int $projectID): array
    {
        $allStages = $this->dao->select('id,name')->from(TABLE_EXECUTION)
            ->where('project')->eq($projectID)
            ->andWhere('type')->notin('program,project')
            ->fetchPairs();

        foreach($milestones as $id => $path)
        {
            $paths     = explode(',', trim($path, ','));
            $stageName = '';
            foreach($paths as $stage)
            {
                if(isset($allStages[$stage])) $stageName .= '/' . $allStages[$stage];
            }
            $milestones[$id] = trim($stageName, '/');
        }

        return $milestones;
    }

    /** 设置阶段基线数据。
     * Set stage baseline data.
     *
     * @param  array   $oldPlans
     * @param  array   $plans
     * @access protected
     * @return array
     */
    protected function setStageBaseline(array $oldPlans, array $plans): array
    {
        foreach($oldPlans as $id => $oldPlan)
        {
            if(!isset($plans[$id])) continue;
            $plans[$id]->version   = $oldPlan->version;
            $plans[$id]->name      = $oldPlan->name;
            $plans[$id]->milestone = $oldPlan->milestone;
            $plans[$id]->begin     = $oldPlan->begin;
            $plans[$id]->end       = $oldPlan->end;
        }

        return $plans;
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
     * @param  array   $datas
     * @param  array   $stageIndex
     * @param  array   $planIdList
     * @access protected
     * @return void
     */
    protected function setPlan(array $plans, array &$datas, array &$stageIndex, array &$planIdList): void
    {
        $this->app->loadLang('stage');

        $today       = helper::today();
        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";
        foreach($plans as $plan)
        {
            $plan->isParent = false;
            if(isset($plans[$plan->parent])) $plans[$plan->parent]->isParent = true;
        }

        $reviewDeadline = array();
        foreach($plans as $plan)
        {
            $planIdList[$plan->id] = $plan->id;
            $reviewDeadline[$plan->id]['stageEnd'] = $plan->end;

            $start     = helper::isZeroDate($plan->begin) ? '' : $plan->begin;
            $end       = helper::isZeroDate($plan->end)   ? '' : $plan->end;
            $realBegan = helper::isZeroDate($plan->realBegan) ? '' : $plan->realBegan;
            $realEnd   = helper::isZeroDate($plan->realEnd)   ? '' : $plan->realEnd;

            $data = new stdclass();
            $data->id            = $plan->id;
            $data->type          = 'plan';
            $data->text          = empty($plan->milestone) ? $plan->name : $plan->name . $isMilestone ;
            $data->name          = $plan->name;
            if(isset($this->config->setPercent) and $this->config->setPercent == 1) $data->percent = $plan->percent;
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

            /* Determines if the object is delay. */
            $data->delay     = $this->lang->programplan->delayList[0];
            $data->delayDays = 0;
            if($today > $end and $plan->status != 'closed')
            {
                $data->delay     = $this->lang->programplan->delayList[1];
                $data->delayDays = helper::diffDate($today, substr($end, 0, 10));
            }

            if($data->endDate > $data->start_date) $data->duration = helper::diffDate(substr($data->endDate, 0, 10), substr($data->start_date, 0, 10)) + 1;
            if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));
            if($data->start_date == '' or $data->endDate == '') $data->duration = 1;

            $datas['data'][$plan->id] = $data;
            $stageIndex[$plan->id]    = array('planID' => $plan->id, 'parent' => $plan->parent, 'progress' => array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalReal' => 0));
        }
    }

    /**
     * 设置甘特图任务数据。
     * Set the Gantt chart task data.
     *
     * @param  array   $tasks
     * @param  array   $plans
     * @param  string  $selectCustom
     * @param  array   $datas
     * @param  array   $stageIndex
     * @access protected
     * @return void
     */
    protected function setTask(array $tasks, array $plans, string $selectCustom, array &$datas, array &$stageIndex): void
    {
        $this->app->loadLang('task');
        $taskPri   = "<span class='label-pri label-pri-%s' title='%s'>%s</span> ";
        $today     = helper::today();
        $taskTeams = $this->dao->select('task,account')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task', 'account');
        $users     = $this->loadModel('user')->getPairs('noletter');

        foreach($tasks as $task)
        {
            $execution = zget($plans, $task->execution, array());
            $pri       = zget($this->lang->task->priList, $task->pri);
            $priIcon   = sprintf($taskPri, $task->pri, $pri, $pri);

            $estStart  = helper::isZeroDate($task->estStarted)  ? '' : $task->estStarted;
            $estEnd    = helper::isZeroDate($task->deadline)    ? '' : $task->deadline;
            $realBegan = helper::isZeroDate($task->realStarted) ? '' : $task->realStarted;
            $realEnd   = (in_array($task->status, array('done', 'closed')) and !helper::isZeroDate($task->finishedDate)) ? $task->finishedDate : '';

            $start = $estStart;
            $end   = $estEnd;
            if(empty($start) and $execution) $start = $execution->begin;
            if(empty($end)   and $execution) $end   = $execution->end;
            if($start > $end) $end = $start;

            $data = new stdclass();
            $data->id            = $task->execution . '-' . $task->id;
            $data->type          = 'task';
            $data->text          = $priIcon . $task->name;
            $data->percent       = '';
            $data->status        = $this->processStatus('task', $task);
            $data->owner_id      = $task->assignedTo;
            $data->attribute     = '';
            $data->milestone     = '';
            $data->begin         = $start;
            $data->deadline      = $end;
            $data->realBegan     = $realBegan ? substr($realBegan, 0, 10) : '';
            $data->realEnd       = $realEnd ? substr($realEnd, 0, 10) : '';
            $data->pri           = $task->pri;
            $data->parent        = $task->parent > 0 ? $task->execution . '-' . $task->parent : $task->execution;
            $data->open          = true;
            $progress            = $task->consumed ? round($task->consumed / ($task->left + $task->consumed), 3) : 0;
            $data->progress      = $progress;
            $data->taskProgress  = ($progress * 100) . '%';
            $data->start_date    = $start;
            $data->endDate       = $end;
            $data->duration      = 1;
            $data->estimate      = $task->estimate;
            $data->consumed      = $task->consumed;
            $data->color         = zget($this->lang->execution->gantt->color, $task->pri, $this->lang->execution->gantt->defaultColor);
            $data->progressColor = zget($this->lang->execution->gantt->progressColor, $task->pri, $this->lang->execution->gantt->defaultProgressColor);
            $data->textColor     = zget($this->lang->execution->gantt->textColor, $task->pri, $this->lang->execution->gantt->defaultTextColor);
            $data->bar_height    = $this->lang->execution->gantt->bar_height;

            /* Determines if the object is delay. */
            $data->delay     = $this->lang->programplan->delayList[0];
            $data->delayDays = 0;
            if($today > $end and $execution->status != 'closed')
            {
                $data->delay     = $this->lang->programplan->delayList[1];
                $data->delayDays = helper::diffDate(($task->status == 'done' || $task->status == 'closed') ? substr($task->finishedDate, 0, 10) : $today, substr($end, 0, 10));
            }

            /* If multi task then show the teams. */
            if($task->mode == 'multi' and !empty($taskTeams[$task->id]))
            {
                $teams     = array_keys($taskTeams[$task->id]);
                $assigneds = array();
                foreach($teams as $assignedTo) $assigneds[] = zget($users, $assignedTo);
                $data->owner_id = implode(',', $assigneds);
            }

            if($data->endDate > $data->start_date) $data->duration = helper::diffDate(substr($data->endDate, 0, 10), substr($data->start_date, 0, 10)) + 1;
            if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));
            if($data->start_date == '' or $data->endDate == '') $data->duration = 1;

            if(strpos($selectCustom, 'task') !== false) $datas['data'][$data->id] = $data;
            foreach($stageIndex as $index => $stage)
            {
                if($stage['planID'] == $task->execution)
                {
                    $stageIndex[$index]['progress']['totalEstimate'] += $task->estimate;
                    $stageIndex[$index]['progress']['totalConsumed'] += $task->parent == '-1' ? 0 : $task->consumed;
                    $stageIndex[$index]['progress']['totalReal']     += ((($task->status == 'closed' || $task->status == 'cancel') ? 0 : $task->left) + $task->consumed);

                    $parent = $stage['parent'];
                    if(isset($stageIndex[$parent]))
                    {
                        $stageIndex[$parent]['progress']['totalEstimate'] += $task->estimate;
                        $stageIndex[$parent]['progress']['totalConsumed'] += $task->parent == '-1' ? 0 : $task->consumed;
                        $stageIndex[$parent]['progress']['totalReal']     += ((($task->status == 'closed' || $task->status == 'cancel') ? 0 : $task->left) + $task->consumed);
                    }
                }
            }
        }
    }

    /**
     * 设置关联任务数据。
     * Set relation task data.
     *
     * @param  array   $planIdList
     * @param  array   $datas
     * @access protected
     * @return array
     */
    protected function setRelationTask(array $planIdList, array $datas): array
    {
        $datas['links'] = array();
        if($this->config->edition == 'open') return $datas;
        $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('execution')->in($planIdList)->orderBy('task,pretask')->fetchAll();
        foreach($relations as $relation)
        {
            $link['source']   = $relation->execution . '-' . $relation->pretask;
            $link['target']   = $relation->execution . '-' . $relation->task;
            $link['type']     = $this->config->execution->gantt->linkType[$relation->condition][$relation->action];
            $datas['links'][] = $link;
        }

        return $datas;
    }

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
    protected function getStageList(int $executionID, int $productID, string $browseType, $orderBy = 'id_asc')
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
    protected function computeOrders(array $orders, array $plans):array
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
     * 构建IPD版本的甘特图数据。
     * Build gantt's data for ipd edition.
     *
     * @param  array     $datas
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $selectCustom
     * @param  array     $reviewDeadline
     * @access protected
     * @return void
     */
    protected function buildGanttData4IPD(array $datas, int $projectID, int $productID, string $selectCustom, array $reviewDeadline)
    {
        $this->loadModel('review');
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
                if(in_array($point->category, $categories))
                {
                    if($point->end and !helper::isZeroDate($point->end))
                    {
                        $end = $point->end;
                    }
                    else
                    {
                        $end = $reviewDeadline[$plan->id]['stageEnd'];
                        if(strpos($point->category, "TR") !== false)
                        {
                            if(isset($reviewDeadline[$plan->id]['taskEnd']) and !helper::isZeroDate($reviewDeadline[$plan->id]['taskEnd']))
                            {
                                $end = $reviewDeadline[$plan->id]['taskEnd'];
                            }
                            else
                            {
                                $end = $this->getReviewDeadline($end);
                            }
                        }
                        elseif(strpos($point->category, "DCP") !== false)
                        {
                            $end = $this->getReviewDeadline($end, 2);
                        }
                    }

                    $data = new stdclass();
                    $data->id            = $plan->id . '-' . $point->category . '-' . $point->id;
                    $data->reviewID      = $point->reviewID;
                    $data->type          = 'point';
                    $data->text          = "<i class='icon-seal'></i> " . $point->title;
                    $data->name          = $point->title;
                    $data->attribute     = '';
                    $data->milestone     = '';
                    $data->owner_id      = '';
                    $data->rawStatus     = $point->status;
                    $data->status        = $point->status ? zget($this->lang->review->statusList, $point->status) : $this->lang->programplan->wait;
                    $data->status        = "<span class='status-{$point->status}'>" . $data->status . '</span>';
                    $data->begin         = $end;
                    $data->deadline      = $end;
                    $data->realBegan     = $point->createdDate;
                    $data->realEnd       = $point->lastReviewedDate;;
                    $data->parent        = $plan->id;
                    $data->open          = true;
                    $data->start_date    = $end;
                    $data->endDate       = $end;
                    $data->duration      = 1;
                    $data->color         = isset($this->lang->programplan->reviewColorList[$point->status]) ? $this->lang->programplan->reviewColorList[$point->status] : '#FC913F';
                    $data->progressColor = $this->lang->execution->gantt->stage->progressColor;
                    $data->textColor     = $this->lang->execution->gantt->stage->textColor;
                    $data->bar_height    = $this->lang->execution->gantt->bar_height;

                    if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));

                    if($selectCustom && strpos($selectCustom, "point") !== false && !$plan->parent) $datas['data'][$data->id] = $data;
                }
            }
        }
    }

    /**
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
        if($project->stageBy)
        {
            $linkProducts = array(0 => $productID);
            if(!empty($productList)) $linkBranches = array(0 => $productList[$productID]->branches);
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
     * @param  object    $project
     * @param  int       $productID
     * @param  int       $parentID
     * @access protected
     * @return int|false
     */
    protected function insertStage(object $plan, object $project, int $productID, int $parentID): int|false
    {
        $account = $this->app->user->account;

        unset($plan->id);
        $plan->status        = 'wait';
        $plan->stageBy       = $project->stageBy;
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
        if($project->model == 'ipd' && $this->config->edition == 'ipd' && !$parentID) $this->loadModel('review')->createDefaultPoint($project->id, $productID, $plan->attribute);

        if($plan->type == 'kanban')
        {
            $execution = $this->execution->getByID($stageID);
            $this->loadModel('kanban')->createRDKanban($execution);
        }

        $this->loadModel('execution')->createMainLib($project->id, $stageID);
        $this->execution->addExecutionMembers($stageID, array($account, $plan->PM));

        $this->setTreePath($stageID);
        $this->computeProgress($stageID, 'create');

        return $stageID;
    }
}
