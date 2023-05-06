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
}
