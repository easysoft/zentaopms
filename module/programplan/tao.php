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
     * 获取父阶段列表。
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
        $parentStage = $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)
            ->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.project')->eq($executionID)
            ->andWhere('t2.type')->eq('stage')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.path')->notlike("%,$planID,%")
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->orderBy('t2.id desc')
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
     * @access protected
     * @return array
     */
    protected function getNewParentAndAction(array $statusCount, object $parent, int $startTasks, string $action): array
    {
        $count        = count($statusCount);
        $newParent    = null;
        $parentAction = '';
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
                $newParent    = $this->loadModel('execution')->buildExecutionByStatus('doing');
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

        if($projectID) $this->loadModel('execution')->checkBeginAndEndDate($projectID, $plan->begin, $plan->end);
        if(dao::isError()) return false;

        $setCode = (isset($this->config->setCode) && $this->config->setCode == 1) ? true : false;
        if($setCode && empty($plan->code))
        {
            dao::$errors['code'][] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
            return false;
        }

        /* 对于是否有子阶段进行判断处理。 */
        /* check parent stage. */
        $setPercent = isset($this->config->setPercent) && $this->config->setPercent == 1 ? true : false;
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

            /* 父阶段不能那个超过100。 */
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
     * 格式化里程碑。
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
            $paths = explode(',', trim($path, ','));
            $stageName = '';
            foreach($paths as $stage)
            {
                if(isset($allStages[$stage])) $stageName .= '/' . $allStages[$stage];
            }
            $milestones[$id] = trim($stageName, '/');
        }

        return $milestones;
    }

}
