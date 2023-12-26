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

        if(dao::isError()) return false;

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
        $plans = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in($idList)
            ->andWhere('type')->eq('project')
            ->fetchAll('id');

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
            $plan->grade == 1 ? $parents[$planID] = $plan : $children[$plan->parent][] = $plan;
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
        $plans = $this->getStage($executionID, $productID, $type);

        $pairs = array(0 => '');

        if(strpos($type, 'leaf') !== false)
        {
            $parents = array();
            foreach($plans as $planID => $plan) $parents[$plan->parent] = true;
        }

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
            $oldPlans = $oldData->stage;
            $plans    = $this->programplanTao->setPlanBaseline($oldPlans, $plans);
        }

        $datas = $planIdList = $stageIndex = array();

        /* Set plan for gantt view. */
        $this->programplanTao->setPlan($plans, $datas, $stageIndex, $planIdList);

        /* Judge whether to display tasks under the stage. */
        $owner = $this->app->user->account;
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module=programplan&section=browse&key=stageCustom");

        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in($planIdList)->orderBy('execution_asc, order_asc, id_desc')->fetchAll('id');

        /* Set task baseline data. */
        if($baselineID)
        {
            $oldTasks = isset($oldData->task) ? $oldData->task : array();
            $this->programplanTao->setTaskBaseline($oldTasks, $tasks); // Set task baseline.
        }

        /* Set task for gantt view. */
        $this->programplanTao->setTask($tasks, $plans, $selectCustom, $datas, $stageIndex);

        /* Build data for ipd. */
        if($project->model == 'ipd' and $datas) $datas = $this->programplanTao->buildGanttData4IPD($datas, $projectID, $productID, $selectCustom, $reviewDeadline);

        /* Calculate the progress of the phase. */
        foreach($stageIndex as $index => $stage)
        {
            $progress  = empty($stage['progress']['totalConsumed']) ? 0 : round($stage['progress']['totalConsumed'] / $stage['progress']['totalReal'], 3);
            $datas['data'][$index]->progress = $progress;

            $progress = ($progress * 100) . '%';
            $datas['data'][$index]->taskProgress = $progress;
            $datas['data'][$index]->estimate = $stage['progress']['totalEstimate'];
            $datas['data'][$index]->consumed = $stage['progress']['totalConsumed'];
        }

        /* Set relation task data. */
        $datas = $this->programplanTao->setRelationTask($planIdList, $datas);

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
     * @return string
     */
    public function getDataForGanttGroupByAssignedTo($executionID, $productID, $baselineID = 0, $selectCustom = '', $returnJson = true)
    {
        $plans = $this->getStage($executionID, $productID);

        $datas       = array();
        $planIdList  = array();
        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";
        $stageIndex  = array();

        foreach($plans as $plan) $planIdList[$plan->id] = $plan->id;

        $taskPri  = "<span class='label-pri label-pri-%s' title='%s'>%s</span> ";

        /* Judge whether to display tasks under the stage. */
        $owner   = $this->app->user->account;
        $module  = 'programplan';
        $section = 'browse';
        $object  = 'stageCustom';
        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");

        $tasksGroup = $this->dao->select('*')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in($planIdList)->fetchGroup('assignedTo','id');
        $users      = $this->loadModel('user')->getPairs('noletter');

        $tasksMap   = array();
        $multiTasks = array();
        foreach($tasksGroup as $group => $tasks)
        {
            foreach($tasks as $id => $task)
            {
                if($task->mode == 'multi') $multiTasks[$id] = $group;
                $tasksMap[$task->id] = $task;
            }
        }

        if($multiTasks)
        {
            $taskTeams = $this->dao->select('t1.*,t2.realname')->from(TABLE_TASKTEAM)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
                ->where('t1.task')->in(array_keys($multiTasks))
                ->orderBy('t1.order')
                ->fetchGroup('task', 'id');
            foreach($taskTeams as $taskID => $team)
            {
                $group = $multiTasks[$taskID];
                foreach($team as $member)
                {
                    $account = $member->account;
                    if($account == $group) continue;
                    if(!isset($taskGroups[$account])) $taskGroups[$account] = array();

                    $taskGroups[$account][$taskID] = clone $tasksMap[$taskID];
                    $taskGroups[$account][$taskID]->id         = $taskID . '_' . $account;
                    $taskGroups[$account][$taskID]->realID     = $taskID;
                    $taskGroups[$account][$taskID]->assignedTo = $account;
                    $taskGroups[$account][$taskID]->realname   = $member->realname;
                }
            }
        }

        $groupID = 0;
        foreach($tasksGroup as $group => $tasks)
        {
            $groupID --;
            $groupName = $group;
            $groupName = zget($users, $group);
            $dataGroup             = new stdclass();
            $dataGroup->id         = $groupID;
            $dataGroup->type       = 'group';
            $dataGroup->text       = $groupName;
            $dataGroup->percent    = '';
            $dataGroup->attribute  = '';
            $dataGroup->milestone  = '';
            $dataGroup->owner_id   = $group;
            $dataGroup->status     = '';
            $dataGroup->begin      = '';
            $dataGroup->deadline   = '';
            $dataGroup->realBegan  = '';
            $dataGroup->realEnd    = '';
            $dataGroup->parent     = 0;
            $dataGroup->open       = true;
            $dataGroup->progress   = '';
            $dataGroup->taskProgress  = '';
            $dataGroup->color         = $this->lang->execution->gantt->stage->color;
            $dataGroup->progressColor = $this->lang->execution->gantt->stage->progressColor;
            $dataGroup->textColor     = $this->lang->execution->gantt->stage->textColor;
            $dataGroup->bar_height    = $this->lang->execution->gantt->bar_height;

            $groupKey = $groupID . $group;
            $datas['data'][$groupKey] = $dataGroup;

            $realStartDate = array();
            $realEndDate   = array();
            $totalTask = count($tasks);
            foreach($tasks as $taskID => $task)
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
                $data->id           = $task->id;
                $data->type         = 'task';
                $data->text         = $priIcon . $task->name;
                $data->percent      = '';
                $data->status       = $this->processStatus('task', $task);
                $data->owner_id     = $task->assignedTo;
                $data->attribute    = '';
                $data->milestone    = '';
                $data->begin        = $start;
                $data->deadline     = $end;
                $data->realBegan    = $realBegan ? substr($realBegan, 0, 10) : '';
                $data->realEnd      = $realEnd ? substr($realEnd, 0, 10) : '';
                $data->pri          = $task->pri;
                $data->parent       = ($task->parent > 0 and $task->assignedTo != '' and !empty($tasksMap[$task->parent]->assignedTo)) ? $task->parent : $groupID;
                $data->open         = true;
                $progress           = $task->consumed ? round($task->consumed / ($task->left + $task->consumed), 3) : 0;
                $data->progress     = $progress;
                $data->taskProgress = ($progress * 100) . '%';
                $data->start_date   = $start;
                $data->endDate      = $end;
                $data->duration     = 1;
                $data->estimate     = $task->estimate;
                $data->consumed     = $task->consumed;
                $data->color         = zget($this->lang->execution->gantt->color, $task->pri, $this->lang->execution->gantt->defaultColor);
                $data->progressColor = zget($this->lang->execution->gantt->progressColor, $task->pri, $this->lang->execution->gantt->defaultProgressColor);
                $data->textColor     = zget($this->lang->execution->gantt->textColor, $task->pri, $this->lang->execution->gantt->defaultTextColor);
                $data->bar_height    = $this->lang->execution->gantt->bar_height;

                if($data->endDate > $data->start_date) $data->duration = helper::diffDate($data->endDate, $data->start_date) + 1;

                if($data->start_date) $data->start_date = date('d-m-Y', strtotime($data->start_date));
                if($data->start_date == '' or $data->endDate == '') $data->duration = 1;

                if(strpos($selectCustom, 'task') !== false) $datas['data'][$data->id] = $data;

                if(!empty($start)) $realStartDate[] = strtotime($start);
                if(!empty($end)) $realEndDate[] = strtotime($end);

                if(isset($stageIndex[$groupKey]['totalConsumed']))
                {
                    $stageIndex[$groupKey]['totalConsumed'] += $task->consumed;
                    $stageIndex[$groupKey]['totalReal']     += $task->left + $task->consumed;
                    $stageIndex[$groupKey]['totalEstimate'] += $task->estimate;
                }
                else
                {
                    $stageIndex[$groupKey]['totalConsumed'] = $task->consumed;
                    $stageIndex[$groupKey]['totalReal']     = $task->left + $task->consumed;
                    $stageIndex[$groupKey]['totalEstimate'] = $task->estimate;
                }
            }

            /* Calculate group realBegan and realEnd. */
            if(!empty($realStartDate)) $datas['data'][$groupKey]->realBegan = date('Y-m-d', min($realStartDate));
            if(!empty($realEndDate) and (count($realEndDate) == $totalTask)) $datas['data'][$groupKey]->realEnd = date('Y-m-d', max($realEndDate));
        }

        /* Calculate the progress of the phase. */
        foreach($stageIndex as $index => $stage)
        {
            $progress  = empty($stage['totalReal']) ? 0 : round($stage['totalConsumed'] / $stage['totalReal'], 3);
            $datas['data'][$index]->progress = $progress;

            $progress = ($progress * 100) . '%';
            $datas['data'][$index]->taskProgress = $progress;
            $datas['data'][$index]->estimate     = $stage['totalEstimate'];
            $datas['data'][$index]->consumed     = $stage['totalConsumed'];
        }

        $datas['links'] = array();
        if($this->config->edition != 'open')
        {
            $relations = $this->dao->select('*')->from(TABLE_RELATIONOFTASKS)->where('execution')->in($planIdList)->orderBy('task,pretask')->fetchAll();
            foreach($relations as $relation)
            {
                $link['source']   = $relation->execution . '-' . $relation->pretask;
                $link['target']   = $relation->execution . '-' . $relation->task;
                $link['type']     = $this->config->execution->gantt->linkType[$relation->condition][$relation->action];
                $datas['links'][] = $link;
            }
        }

        $datas['data'] = isset($datas['data']) ? array_values($datas['data']) : array();

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
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return int
     */
    public function getDuration(string $begin, string $end): int
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

        $this->loadModel('action');
        $this->loadModel('execution');
        $project = $this->fetchById($projectID, 'project');

        /* Get linked product by projectID. */
        $linkProducts = $this->programplanTao->getLinkProductsForCreate($projectID, $productID);

        /* Set each plans. */
        $updateUserViewIdList = array();
        $milestone            = 0;
        foreach($plans as $plan)
        {
            if($plan->id)
            {
                $stageID = $plan->id;
                unset($plan->id, $plan->type, $plan->order);

                $oldStage    = $this->getByID($stageID);
                $planChanged = ($oldStage->name != $plan->name || $oldStage->milestone != $plan->milestone || $oldStage->begin != $plan->begin || $oldStage->end != $plan->end);
                if($planChanged) $plan->version = $oldStage->version + 1;

                /* Record version change information. */
                $this->dao->update(TABLE_PROJECT)->data($plan)->where('id')->eq($stageID)->exec();
                if($planChanged) $this->programplanTao->insertProjectSpec($stageID, $plan);

                /* Add PM to stage teams and project teams. */
                if(!empty($plan->PM)) $this->execution->addExecutionMembers($stageID, array($plan->PM));
                if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;

                $changes = common::createChanges($oldStage, $plan);
                if($changes)
                {
                    $actionID = $this->action->create('execution', $stageID, 'edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                $stageID = $this->programplanTao->insertStage($plan, $project, $productID, $parentID);
                if(dao::isError()) return false;

                $extra = '';
                if($project->hasProduct and !empty($linkProducts['products'])) $extra = implode(',', $linkProducts['products']);
                $this->action->create('execution', $stageID, 'opened', '', $extra);

                $this->execution->updateProducts($stageID, $linkProducts);
                if($plan->acl != 'open') $updateUserViewIdList[] = $stageID;
            }
            if($plan->milestone) $milestone = 1;
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
    public function setTreePath($planID): bool
    {
        $stage  = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
        $parent = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($stage->parent)->fetch();

        $this->loadModel('execution');
        if($parent->type == 'project')
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

        if(!empty($children))
        {
            foreach($children as $id => $child) $this->setTreePath($id);
        }

        return (!dao::isError());
    }

    /**
     * 更新阶段。
     * Update a plan.
     *
     * @param  int       $planID
     * @param  int       $projectID
     * @param  object    $plan
     * @access public
     * @return bool|array
     */
    public function update(int $planID = 0, int $projectID = 0, object $plan = null): bool|array
    {
        $oldPlan = $this->getByID($planID);

        /* 判断提交数据是否正确。 */
        /* Judgment of required items. */
        if(!$this->programplanTao->checkRequiredItems($oldPlan, $plan, $projectID)) return false;

        $setCode     = isset($this->config->setCode) && $this->config->setCode == 1;
        $planChanged = ($oldPlan->name != $plan->name || $oldPlan->milestone != $plan->milestone || $oldPlan->begin != $plan->begin || $oldPlan->end != $plan->end);

        /* 设置计划和真实起始日期间隔时间。 */
        /* Set planDuration and realDuration. */
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $plan->planDuration = $this->getDuration($plan->begin, $plan->end);
            if(isset($plan->realBegan) && isset($plan->realEnd)) $plan->realDuration = $this->getDuration($plan->realBegan, $plan->realEnd);
        }

        if($planChanged) $plan->version = $oldPlan->version + 1;
        if(empty($plan->parent)) $plan->parent = $projectID;
        $parentStage = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($plan->parent)->andWhere('type')->eq('stage')->fetch();

        /* Fix bug #22030. Reset field name for show dao error. */
        $this->lang->project->name = $this->lang->programplan->name;
        $this->lang->project->code = $this->lang->execution->code;

        $relatedExecutionsID = $this->loadModel('execution')->getRelatedExecutions($planID);
        $relatedExecutionsID = !empty($relatedExecutionsID) ? implode(',', array_keys($relatedExecutionsID)) : '0';

        /* Pass and unset the arguments after use. */
        $plan->relatedExecutionsID = $relatedExecutionsID;
        $plan->setCode             = $setCode;

        $result = $this->programplanTao->updateRow($plan, $oldPlan, $parentStage);
        if(!$result) return false;

        $this->setTreePath($planID);
        $this->updateSubStageAttr($planID, $plan->attribute);

        if($plan->acl != 'open')
        {
            $planIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('path')->like("%,$planID,%")->andWhere('type')->ne('project')->fetchAll('id');
            $this->loadModel('user')->updateUserView(array_keys($planIdList), 'sprint');
        }

        if($planChanged) $this->programplanTao->insertProjectSpec($planID, $plan);

        return common::createChanges($oldPlan, $plan);
    }

    /**
     * 输出表格单元 <td></td>。
     * Print cell.
     *
     * @param  object $col
     * @param  object $plan
     * @param  array  $users
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function printCell(object $col, object $plan, array $users, int $projectID)
    {
        $labelType = $col->id;
        if($col->show)
        {
            $class  = 'c-' . $labelType;
            $title  = '';
            $idList = array('id','name','output','percent','attribute','version','begin','end','realBegan','realEnd', 'openedBy', 'openedDate');
            if(in_array($labelType, $idList))
            {
                $class .= ' text-left';
                $title  = "title='{$plan->$labelType}'";
                if($labelType == 'output') $class .= ' text-ellipsis';
                if(!empty($plan->children)) $class .= ' has-child';
            }
            else
            {
                $class .= ' text-center';
            }
            if($labelType == 'actions') $class .= ' c-actions';

            echo "<td class='{$class}' {$title}>";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('programplan', $plan, $labelType);
            switch($labelType)
            {
                case 'id':
                    echo sprintf('%03d', $plan->id);
                    break;
                case 'name':
                    $milestoneFlag = $plan->milestone ? " <i class='icon icon-flag red' title={$this->lang->programplan->milestone}'></i>" : '';
                    if($plan->grade > 1) echo '<span class="label label-badge label-light" title="' . $this->lang->programplan->children . '">' . $this->lang->programplan->childrenAB . '</span> ';
                    echo $plan->name . $milestoneFlag;
                    if(!empty($plan->children)) echo '<a class="plan-toggle" data-id="' . $plan->id . '"><i class="icon icon-angle-double-right"></i></a>';
                    break;
                case 'percent':
                    echo $plan->percent . '%';
                    break;
                case 'attribute':
                    echo zget($this->lang->stage->typeList, $plan->attribute, '');
                    break;
                case 'begin':
                case 'end':
                case 'realBegan':
                case 'realEnd':
                case 'output':
                case 'version':
                    echo $plan->{$labelType};
                    break;
                case 'editedBy':
                case 'openedBy':
                    echo zget($users, $plan->{$labelType});
                    break;
                case 'editedDate':
                case 'openedDate':
                    echo substr($plan->{$labelType}, 5, 11);
                    break;
                case 'actions':
                    common::printIcon('execution', 'start', "executionID={$plan->id}", $plan, 'list', '', '', 'iframe', true);
                    $class = !empty($plan->children) ? 'disabled' : '';
                    common::printIcon('task', 'create', "executionID={$plan->id}", $plan, 'list', '', '', $class, false, "data-app='execution'");

                    if($plan->grade == 1 && $this->isCreateTask($plan->id))
                    {
                        common::printIcon('programplan', 'create', "program={$plan->parent}&productID=$plan->product&planID=$plan->id", $plan, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
                    }
                    else
                    {
                        $disabled = ($plan->grade == 2) ? ' disabled' : '';
                        echo html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"');
                    }

                    common::printIcon('programplan', 'edit', "planID=$plan->id&projectID=$projectID", $plan, 'list', '', '', 'iframe', true);

                    $disabled = !empty($plan->children) ? ' disabled' : '';
                    if(common::hasPriv('execution', 'delete', $plan))
                    {
                        common::printIcon('execution', 'delete', "planID=$plan->id&confirm=no", $plan, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
                    }
                    break;
                default:
                    break;
            }
            echo '</td>';
        }
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

        return $this->dao->select('DISTINCT type')->from(TABLE_EXECUTION)->where('parent')->eq($parentID)->andWhere('deleted')->eq('0')->fetchPairs('type');
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
        if(strtolower($action) == 'create')
        {
            global $dao;
            if(empty($plan->id)) return true;

            $task = $dao->select('*')->from(TABLE_TASK)->where('execution')->eq($plan->id)->andWhere('deleted')->eq('0')->limit(1)->fetch();
            return empty($task);
        }

        return true;
    }

    /**
     * 检查名称唯一性.
     * Check name unique.
     *
     * @param  array  $names
     * @access public
     * @return bool
     */
    public function checkNameUnique(array $names): bool
    {
        $names = array_filter($names);
        return count(array_unique($names)) == count($names);
    }

    /**
     * 根据计划ID列表，检查code的唯一性。
     * Check code unique by plan id list.
     *
     * @param  array  $codes
     * @param  array  $planIDList
     * @access public
     * @return array|bool
     */
    public function checkCodeUnique(array $codes, array $planIDList): array|bool
    {
        $codes = array_filter($codes);

        $sameCodes = $this->dao->select('code')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage,kanban')
            ->andWhere('code')->in($codes)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!empty($planIDList))->andWhere('id')->notin($planIDList)->fi()
            ->fetchPairs('code');

        if(count(array_unique($codes)) != count($codes)) $sameCodes += array_diff_assoc($codes, array_unique($codes));

        return $sameCodes ?: true;
    }

    /**
     * 通过项目ID获取里程碑信息。
     * Get milestones by projetc id.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMilestones(int $projectID = 0): array
    {
        $milestones = $this->dao->select('id, path')->from(TABLE_PROJECT)
            ->where('project')->eq($projectID)
            ->andWhere('type')->eq('stage')
            ->andWhere('milestone')->eq(1)
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin_desc,path')
            ->fetchPairs();

        return $this->programplanTao->formatMilestones($milestones, $projectID);
    }

    /**
     * 根据product获取里程碑。
     * Get milestone by product.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMilestoneByProduct(int $productID, int $projectID): array
    {
        $milestones = $this->dao->select('t1.id, t1.path')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.product')->eq($productID)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.milestone')->eq(1)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.begin asc,path')
            ->fetchPairs();

        return $this->programplanTao->formatMilestones($milestones, $projectID);
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

            if($isCreate === false && $key != $plan->parent) unset($parentStage[$key]);
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
        $this->loadModel('execution');

        $stage   = $this->execution->getByID($stageID);
        if(empty($stage) || empty($stage->path)) return false;

        $project = $this->loadModel('project')->getByID($stage->project);
        $model   = zget($project, 'model', '');
        if(empty($stage) or empty($stage->path) or (!in_array($model, array('waterfall','waterfallplus','ipd','research')))) return false;

        $action       = strtolower($action);
        $parentIdList = explode(',', trim($stage->path, ','));
        $parentIdList = array_reverse($parentIdList);
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

            $result       = $this->programplanTao->getNewParentAndAction($statusCount, $parent, (int)$startTasks, $action);
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

        $subStageList = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('parent')->eq($planID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

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
     * @param  int   $stageID
     * @access public
     * @return false|string
     */
    public function getStageAttribute(int $stageID): false|string
    {
        $stageAttribute = $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($stageID)->fetch('attribute');

        if(dao::isError()) return false;

        return $stageAttribute;
    }

    /**
     * Get five days ago.
     *
     * @param  string $date
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getReviewDeadline(string $date, int $counter = 5)
    {
        if(helper::isZeroDate($date)) return '';

        $weekend_days = [6, 7];

        $timestamp = strtotime($date);
        $i         = 0;
        $this->loadModel('holiday');
        while($i < $counter)
        {
            $timestamp   = strtotime('-1 day', $timestamp);
            $weekday     = date('N', $timestamp);
            $currentDate = date('Y-m-d', $timestamp);
            if(!in_array($weekday, $weekend_days) and !$this->holiday->isHoliday($currentDate))
            {
                $i ++;
            }
        }

        return date('Y-m-d', $timestamp);
    }
}
