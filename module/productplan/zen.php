<?php
declare(strict_types=1);
/**
 * The zen file of producrplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     producrplan
 * @link        https://www.zentao.net
 */
class productplanZen extends productplan
{
    /**
     * 构建批量编辑计划数据。
     * Build plans for batch edit.
     *
     * @access protected
     * @return array
     */
    protected function buildPlansForBatchEdit(): array
    {
        $fields   = $this->config->productplan->form->batchEdit;
        $plans    = form::batchData($fields)->get();
        $oldPlans = $this->productplan->getByIDList(array_keys($plans));
        foreach($plans as $planID => $plan)
        {
            $oldPlan = $oldPlans[$planID];

            $plan->parent = $oldPlan->parent;
            if(empty($plan->begin))  $plan->begin  = $oldPlan->begin;
            if(empty($plan->end))    $plan->end    = $oldPlan->end;
            if(empty($plan->status)) $plan->status = $oldPlan->status;

            if(empty($plan->title)) dao::$errors[] = sprintf($this->lang->productplan->errorNoTitle, $planID);
            if($plan->begin > $plan->end && !empty($plan->end)) dao::$errors[] = sprintf($this->lang->productplan->beginGeEnd, $planID);;

            if($plan->begin == '') $plan->begin = $this->config->productplan->future;
            if($plan->end   == '') $plan->end   = $this->config->productplan->future;
            if(!empty($_POST['future'][$planID]))
            {
                $plan->begin = $this->config->productplan->future;
                $plan->end   = $this->config->productplan->future;
            }
        }

        return $plans;
    }
}

