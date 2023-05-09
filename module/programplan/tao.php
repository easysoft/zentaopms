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
    protected function updateRow(object $plan, array $conditions): bool
    {
        $requiredFields = $conditions['requiredFields'] ?? '';
        $ids            = $conditions['ids'] ?? '';
        $project        = $conditions['project'] ?? '';
        $parentStage    = $conditions['parentStage'] ?? '';
        $parent         = $conditions['parent'] ?? '';
        $setCode        = $conditions['setCode'] ?? '';

        $this->dao->update(TABLE_PROJECT)->data($plan)
            ->autoCheck()
            ->batchCheckIF($requiredFields, $requiredFields, 'notempty')
            ->checkIF($plan->end != '0000-00-00', 'end', 'ge', $plan->begin)
            ->checkIF(!empty($plan->percent), 'percent', 'float')
            ->checkIF(!empty($plan->name) && $ids && $project && $parentStage  && $parent, 'name', 'unique', "id in ({$ids}) and type in ('sprint','stage') and `project` = {$project} and `deleted` = '0'" . ($parentStage ? " and `parent` = {$parent}" : ''))
            ->checkIF(!empty($plan->code) and $setCode, 'code', 'unique', "id != {$plan->id} and type in ('sprint','stage','kanban') and `deleted` = '0'")
            ->where('id')->eq($plan->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * 设置阶段基线数据。
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
        $today       = helper::today();
        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";

        foreach($plans as $plan)
        {
            $planIdList[$plan->id] = $plan->id;

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
            $data->open          = true;
            $data->start_date    = $realBegan ? $realBegan : $start;
            $data->endDate       = $realEnd ? $realEnd : $end;
            $data->duration      = 1;
            $data->color         = $this->lang->execution->gantt->stage->color;
            $data->progressColor = $this->lang->execution->gantt->stage->progressColor;
            $data->textColor     = $this->lang->execution->gantt->stage->textColor;
            $data->bar_height    = $this->lang->execution->gantt->bar_height;

            /* Determines if the object is delay. */
            $data->delay     = $this->lang->programplan->delayList[0];
            $data->delayDays = 0;
            if($today > $end)
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
        $taskPri   = "<span class='label-pri label-pri-%s' title='%s'>%s</span> ";
        $today     = helper::today();
        $taskTeams = $this->dao->select('task,account')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task', 'account');
        $users     = $this->loadModel('user')->getPairs('noletter');

        foreach($tasks as $task)
        {
            $execution = zget($plans, $task->execution, array());
            $priIcon   = sprintf($taskPri, $task->pri, $task->pri, $task->pri);

            $estStart  = helper::isZeroDate($task->estStarted)  ? '' : $task->estStarted;
            $estEnd    = helper::isZeroDate($task->deadline)    ? '' : $task->deadline;
            $realBegan = helper::isZeroDate($task->realStarted) ? '' : $task->realStarted;
            $realEnd   = (in_array($task->status, array('done', 'closed')) and !helper::isZeroDate($task->finishedDate)) ? $task->finishedDate : '';

            $start = $realBegan ? $realBegan : $estStart;
            $end   = $realEnd   ? $realEnd   : $estEnd;
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
            if($today > $end)
            {
                $data->delay     = $this->lang->programplan->delayList[1];
                $data->delayDays = helper::diffDate($today, substr($end, 0, 10));
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
                    $stageIndex[$index]['progress']['totalReal']     += ($task->left + $task->consumed);

                    $parent = $stage['parent'];
                    if(isset($stageIndex[$parent]))
                    {
                        $stageIndex[$parent]['progress']['totalEstimate'] += $task->estimate;
                        $stageIndex[$parent]['progress']['totalConsumed'] += $task->parent == '-1' ? 0 : $task->consumed;
                        $stageIndex[$parent]['progress']['totalReal']     += ($task->left + $task->consumed);
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
     * Get stage listt.
     *
     * @param  int       $executionID
     * @param  int       $productID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @access protected
     * @return array
     */
    protected function getStageListBy(int $executionID, int $productID, string $browseType, $orderBy = 'id_asc')
    {
        if(empty($executionID)) return array();
        $projectModel = $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('model');

        return $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('1 = 1')
            ->beginIF($projectModel != 'waterfallplus')->andWhere('t1.type')->eq('stage')->fi()
            ->beginIF($productID)->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($browseType == 'all')->andWhere('t1.project')->eq($executionID)->fi()
            ->beginIF($browseType == 'parent')->andWhere('t1.parent')->eq($executionID)->fi()
            ->beginIF($this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');
    }
}
