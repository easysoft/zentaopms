<?php
declare(strict_types=1);
/**
 * The tao file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     program
 * @link        https://www.zentao.net
 */

class programTao extends programModel
{
    /**
     *获取所有根项目集基本数据。
     * Get base data of all root programs.
     *
     * @access protected
     * @return array
     */
    protected function getRootProgramList(): array
    {
        return $this->dao->select('id,name,PM,path,parent,type')
            ->from(TABLE_PROGRAM)
            ->where('parent')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->orderBy('order_asc')
            ->fetchAll();
    }

    /**
     * 构建项目视图的项目集的操作数据。
     * Build actions map for program.
     *
     * @param  object    $program
     * @access protected
     * @return array
     */
    protected function buildProgramActionsMap(object $program): array
    {
        if($program->type == 'program' && !str_contains(",{$this->app->user->view->programs},", ",$program->id,")) return array();
        if($program->type == 'project') $this->loadModel('project');

        $actionsMap         = array();
        $canStartProgram    = common::hasPriv($program->type, 'start');
        $canSuspendProgram  = common::hasPriv($program->type, 'suspend');
        $canCloseProgram    = common::hasPriv($program->type, 'close');
        $canActivateProgram = common::hasPriv($program->type, 'activate');
        $normalActions      = array('start', 'close', 'activate');
        foreach($normalActions as $action)
        {
            if($action == 'close' && (!$canCloseProgram || $program->status != 'doing')) continue;
            if($action == 'activate' && (!$canActivateProgram || $program->status != 'closed')) continue;
            if($action == 'start' && (!$canStartProgram || ($program->status != 'wait' && $program->status != 'suspended'))) continue;

            $item = new stdclass();
            $item->name   = $action;
            $item->url    = helper::createLink($program->type, $action, "programID={$program->id}");
            $actionsMap[] = $item;
            break;
        }

        if($canSuspendProgram || ($canCloseProgram && $program->status != 'doing') || ($canActivateProgram && $program->status != 'closed'))
        {
            $other = new stdclass();
            $other->name  = 'other';
            $other->items = array();

            $otherActions = array('suspend', 'close', 'activate');
            foreach($otherActions as $action)
            {
                if(!common::hasPriv($program->type, $action)) continue;
                if($action == 'close' && $program->status == 'doing') continue;

                $item = new stdclass();
                $item->name     = $action;
                $item->url      = helper::createLink($program->type, $action, "programID={$program->id}");
                $item->disabled = !static::isClickable($program, $action);

                if($action == 'close' && $program->status == 'closed')      $item->hint = $this->lang->{$program->type}->tip->closed;
                if($action == 'activate' && $program->status == 'doing')    $item->hint = $this->lang->{$program->type}->tip->actived;
                if($action == 'suspend' && $program->status == 'suspended') $item->hint = $this->lang->{$program->type}->tip->suspended;
                if($action == 'suspend' && $program->status == 'closed')    $item->hint = $this->lang->{$program->type}->tip->notSuspend;
                $other->items[] = $item;
            }

            $actionsMap[] = $other;
        }
        return array_merge($actionsMap, $this->getNormalActions($program));
    }

    /**
     * 获取基础操作的按钮数据。
     * Get normal actions.
     *
     * @param  object $program
     * @access protected
     * @return array
     */
    protected function getNormalActions(object $program): array
    {
        $actionsMap    = array();
        $normalActions = $program->type == 'project' ? array('edit') : array('edit', 'create', 'delete');
        foreach($normalActions as $action)
        {
            if(!common::hasPriv($program->type, $action)) continue;

            $item = new stdclass();
            $item->name = $action;
            if($action != 'delete') $item->url  = helper::createLink($program->type, $action, "programID={$program->id}");
            if($action == 'delete') $item->url = "javascript:confirmDelete({$program->id}, 'program', '{$program->name}')";
            if($action == 'create' && $program->status == 'closed')
            {
                $item->disabled = true;
                $item->hint     = $this->lang->program->tip->notCreate;
            }

            $actionsMap[] = $item;
        }

        return $actionsMap;
    }

    /**
     * 构建项目视角中项目的操作数据。
     * Build actions map for project.
     *
     * @param  object    $project
     * @access protected
     * @return array
     */
    protected function buildProjectActionsMap(object $project): array
    {
        $this->loadModel('project');
        $actionsMap = array();
        if(common::hasPriv('project', 'team'))
        {
            $item = new stdclass();
            $item->name   = 'team';
            $item->url    = helper::createLink('project', 'team', "projectID={$project->id}");
            $actionsMap[] = $item;
        }

        if(common::hasPriv('project', 'manageProducts') || common::hasPriv('project', 'whitelist') || common::hasPriv('project', 'delete'))
        {
            $more = new stdclass();
            $more->name  = 'more';
            $more->items = array();
            $moreActions = array('group', 'manageProducts', 'whitelist', 'delete');
            foreach($moreActions as $action)
            {
                if(!common::hasPriv('project', $action)) continue;

                $item = new stdclass();
                $item->name = $action == 'manageProducts' ? 'link' : $action;
                if($action != 'delete') $item->url = helper::createLink('project', $action, "projectID={$project->id}");
                if($action == 'delete') $item->url = "javascript:confirmDelete({$project->id}, 'project', '{$project->name}')";
                if($action == 'whitelist' and $project->acl == 'open')
                {
                    $item->disabled = true;
                    $item->hint     = $this->lang->project->tip->whitelist;
                }
                if($action == 'group' && $project->model == 'kanban')
                {
                    $item->disabled = true;
                    $item->hint     = $this->lang->project->tip->group;
                }

                $more->items[] = $item;
            }

            $actionsMap[] = $more;
        }
        return $actionsMap;
    }

    /**
     * 如果修改了父项目集，修改关联的产品。
     * If change parent, then fix linked product.
     *
     * @param  int       $programID
     * @param  int       $parent
     * @param  int       $oldParent
     * @param  string    $oldPath
     * @access protected
     * @return void
     */
    protected function fixLinkedProduct(int $programID, int $parent, int $oldParent, string $oldPath): void
    {
        if($parent == $oldParent) return;

        /* Move product to new top program. */
        $oldTopProgram = $this->getTopByPath($oldPath);
        $newTopProgram = $this->getTopByID($programID);
        if($oldTopProgram == $newTopProgram) return;

        if($oldParent == 0)
        {
            $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('program')->eq($oldTopProgram)->exec();
            return;
        }

        /* Get the shadow products that produced by the program's no product projects. */
        $shadowProducts = $this->dao->select('t1.id')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.product')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.path')->like("%,$programID,%")
            ->andWhere('t3.type')->eq('project')
            ->andWhere('t3.hasProduct')->eq('0')
            ->andWhere('t1.shadow')->eq('1')
            ->fetchPairs();
        if($shadowProducts) $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('id')->in($shadowProducts)->exec();
    }

    /**
     * 更新项目集的统计数据。
     * Update stats of program.
     *
     * @param  array     $projectIdList
     * @access protected
     * @return bool
     */
    protected function updateStats(array $projectIdList): bool
    {
        /* Get summary and members of executions to be refreshed. */
        $stats       = $this->getTaskStats($projectIdList);
        $teamMembers = $this->dao->select('t1.root, COUNT(1) AS members')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.type')->eq('project')
            ->beginIF(!empty($projectIdList))->andWhere('t1.root')->in($projectIdList)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.root')
            ->fetchPairs('root');

        foreach($teamMembers as $projectID => $teamCount)
        {
            if(!isset($stats[$projectID])) $stats[$projectID] = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'teamCount' => 0, 'totalConsumedNotDel' => 0, 'totalLeftNotDel' => 0);
            $stats[$projectID]['teamCount'] = $teamCount;
        }

        foreach($stats as $projectID => $project)
        {
            $totalRealNotDel = $project['totalConsumedNotDel'] + $project['totalLeftNotDel'];
            $progress        = $totalRealNotDel ? floor($project['totalConsumedNotDel'] / $totalRealNotDel * 1000) / 1000 * 100 : 0;
            $this->dao->update(TABLE_PROJECT)
                ->set('progress')->eq($progress)
                ->set('teamCount')->eq($project['teamCount'])
                ->set('estimate')->eq($project['totalEstimate'])
                ->set('consumed')->eq($project['totalConsumedNotDel'])
                ->set('left')->eq($project['totalLeftNotDel'])
                ->where('id')->eq($projectID)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取任务的统计数据。
     * Get task stats.
     *
     * @param  array     $projectIdList
     * @access protected
     * @return array
     */
    protected function getTaskStats(array $projectIdList): array
    {
        /* 1. Set execution has no tasks. */
        $summary = $this->setNoTaskExecution($projectIdList);
        /* 2. Get summary of executions to be refreshed. */
        $tasks = $this->dao->select('t1.id, execution, t1.estimate, t1.consumed, t1.`left`, t1.status')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->ge(0) // Ignore parent task.
            ->beginIF(!empty($projectIdList))->andWhere('t1.project')->in($projectIdList)->fi()
            ->fetchAll('id');

        foreach($tasks as $task)
        {
            if(empty($task->execution)) continue;
            if(!isset($summary[$task->execution]))
            {
                $summary[$task->execution] = new stdclass();
                $summary[$task->execution]->totalEstimate = 0;
                $summary[$task->execution]->totalConsumed = 0;
                $summary[$task->execution]->totalLeft     = 0;
            }
            $summary[$task->execution]->execution      = $task->execution;
            $summary[$task->execution]->totalEstimate += $task->estimate;
            $summary[$task->execution]->totalConsumed += $task->consumed;
            $summary[$task->execution]->totalLeft     += ($task->status == 'closed' or $task->status == 'cancel') ? 0 : $task->left;
        }

        /* 3. Get all parents to be refreshed. */
        $executions = array();
        foreach($summary as $execution) $executions[$execution->execution] = $execution->execution;
        $paths = $this->dao->select('id,path')->from(TABLE_PROJECT)->where('id')->in($executions)->fetchAll();
        $executionPaths = array();
        foreach($paths as $path) $executionPaths[$path->id] = explode(',', trim($path->path, ','));

        /* 4. Compute stats of execution and parents. */
        $stats         = array();
        $projectsPairs = $this->dao->select('id,deleted')->from(TABLE_PROJECT)->fetchPairs();
        foreach($summary as $execution)
        {
            $executionID = $execution->execution;
            if(!isset($executionPaths[$executionID])) continue;
            foreach($executionPaths[$executionID] as $nodeID)
            {
                if(!isset($stats[$nodeID])) $stats[$nodeID] = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'teamCount' => 0, 'totalLeftNotDel' => 0, 'totalConsumedNotDel' => 0);
                $stats[$nodeID]['totalEstimate'] += $execution->totalEstimate;
                $stats[$nodeID]['totalConsumed'] += $execution->totalConsumed;
                $stats[$nodeID]['totalLeft']     += $execution->totalLeft;

                /* Check $execution->execution and $nodeID(path) is not deleted. */
                if(empty($projectsPairs[$execution->execution]) && empty($projectsPairs[$nodeID]))
                {
                    $stats[$nodeID]['totalConsumedNotDel'] += $execution->totalConsumed;
                    $stats[$nodeID]['totalLeftNotDel']     += $execution->totalLeft;
                }
            }
        }
        return $stats;
    }

    /**
     * 设置没有任务的执行数据。
     * Set execution stat when has no task.
     *
     * @param  array     $projectIdList
     * @access protected
     * @return array
     */
    protected function setNoTaskExecution(array $projectIdList): array
    {
        $summary = array();
        foreach($projectIdList as $projectID)
        {
            $executions = $this->dao->select('id')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->fetchPairs();
            foreach($executions as $executionID)
            {
                $taskCount = $this->dao->select('id')->from(TABLE_TASK)
                    ->where('deleted')->eq(0)
                    ->andWhere('execution')->eq($executionID)
                    ->count();
                if(empty($taskCount))
                {
                    $summary[$executionID] = new stdclass();
                    $summary[$executionID]->totalEstimate = 0;
                    $summary[$executionID]->totalConsumed = 0;
                    $summary[$executionID]->totalLeft     = 0;
                    $summary[$executionID]->execution     = $executionID;
                }
            }
        }
        return $summary;
    }

    /**
     * 更新项目集的进度。
     * Update program progress.
     *
     * @access protected
     * @return bool
     */
    protected function updateProcess(): bool
    {
        $projectList = $this->dao->select('id,progress,path,consumed,`left`')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('parent')->ne(0)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $programProgress = array();
        foreach($projectList as $projectID => $project)
        {
            $path = explode(',', trim($project->path, ','));
            foreach($path as $programID)
            {
                if($programID == $projectID) continue;
                if(!isset($programProgress[$programID])) $programProgress[$programID] = array('consumed' => 0, 'left' => 0);

                $programProgress[$programID]['consumed'] += $project->consumed;
                $programProgress[$programID]['left']     += $project->left;
            }
        }

        foreach($programProgress as $programID => $hours)
        {
            $progress = ($hours['consumed'] + $hours['left']) ? floor($hours['consumed'] / ($hours['consumed'] + $hours['left']) * 1000) / 1000 * 100 : 0;
            $this->dao->update(TABLE_PROJECT)->set('progress')->eq($progress)->where('id')->eq($programID)->exec();
        }

        return !dao::isError();
    }
}
