<?php
declare(strict_types=1);
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
}
