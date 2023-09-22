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

    /**
     * 设置计划看板页面数据。
     * Set kanban page data.
     *
     * @param  object    $product
     * @param  string    $branchID
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function assignKanbanData(object $product, string $branchID, string $orderBy)
    {
        $branches    = array();
        $branchPairs = array();
        $planCount   = 0;
        if(!in_array($orderBy, array_keys($this->lang->productplan->orderList))) $orderBy = key($this->lang->productplan->orderList);

        if($product->type == 'normal')
        {
            $planGroup = $this->productplan->getList($product->id, 0, 'all', '', $orderBy, 'skipparent');

            $this->view->planCount = count(array_filter($planGroup));
        }
        else
        {
            $planGroup = $this->productplan->getGroupByProduct(array($product->id), 'skipparent', $orderBy);
            $branches  = $this->branch->getPairs($product->id, 'active');

            foreach($branches as $id => $name)
            {
                $plans            = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
                $branchPairs[$id] = $name . ' ' . count($plans);
                $planCount       += count($plans);
            }

            $this->view->branches = array('all' => $this->lang->productplan->allAB . ' ' . $planCount) + $branchPairs;
        }

        $this->view->kanbanData = $this->loadModel('kanban')->getPlanKanban($product, $branchID, $planGroup);
    }
}

