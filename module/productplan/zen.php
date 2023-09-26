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
     * @param  int          $productID
     * @access protected
     * @return array|string
     */
    protected function buildPlansForBatchEdit(int $productID): array|string
    {
        $plans        = form::batchData($this->config->productplan->form->batchEdit)->get();
        $oldPlans     = $this->productplan->getByIDList(array_keys($plans));
        $futureConfig = $this->config->productplan->future;
        $product      = $this->loadModel('product')->getByID($productID);
        foreach($plans as $planID => $plan)
        {
            $oldPlan  = $oldPlans[$planID];
            $parentID = $oldPlan->parent;

            if(empty($plan->begin))  $plan->begin  = $oldPlan->begin;
            if(empty($plan->end))    $plan->end    = $oldPlan->end;
            if(empty($plan->status)) $plan->status = $oldPlan->status;

            if($plan->begin > $plan->end && !empty($plan->end)) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginGeEnd, $planID);;
            if($plan->begin == '') $plan->begin = $this->config->productplan->future;
            if($plan->end   == '') $plan->end   = $this->config->productplan->future;
            if(!empty($_POST['future'][$planID]))
            {
                $plan->begin = $this->config->productplan->future;
                $plan->end   = $this->config->productplan->future;
            }

            /* Determine whether the begin and end dates of the parent plan and the child plan are correct. */
            if($parentID > 0)
            {
                $parent = zget($plans, $parentID, $this->productplan->getByID($parentID));
                if($parent->begin != $futureConfig && $plan->begin != $futureConfig && $plan->begin < $parent->begin) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginLessThanParentTip, $planID, $plan->begin, $parent->begin);
                if($parent->end != $futureConfig && $plan->end != $futureConfig && $plan->end > $parent->end)         return dao::$errors["end[{$planID}]"]   = sprintf($this->lang->productplan->endGreatThanParentTip, $planID, $plan->end, $parent->end);
            }
            elseif($parentID == -1 && $plan->begin != $futureConfig)
            {
                $childPlans = $this->productplan->getChildren($parentID);
                $minBegin   = $plan->begin;
                $maxEnd     = $plan->end;
                foreach($childPlans as $childID => $childPlan)
                {
                    $childPlan = isset($plans[$childID]) ? $plans[$childID] : $childPlan;
                    if($childPlan->begin < $minBegin && $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                    if($childPlan->end > $maxEnd && $maxEnd != $this->config->productplan->future)       $maxEnd   = $childPlan->end;
                }
                if($minBegin < $plan->begin && $minBegin != $futureConfig) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginGreaterChildTip, $planID, $plan->begin, $minBegin);
                if($maxEnd > $plan->end     && $maxEnd != $futureConfig)   return dao::$errors["end[{$planID}]"]   = sprintf($this->lang->productplan->endLessThanChildTip, $planID, $plan->end, $maxEnd);
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

    /**
     * 构造计划列表页面数据。
     * Build data for browse page.
     *
     * @param  array     $plans
     * @param  array     $branchOption
     * @access protected
     * @return array
     */
    protected function buildDataForBrowse(array $plans, array $branchOption): array
    {
        if(empty($plans)) return $plans;
        foreach($plans as $plan)
        {
            $plan->branchName = '';
            if(!empty($branchOption))
            {
                foreach(explode(',', $plan->branch) as $branchID) $plan->branchName .= $branchOption[$branchID] . ',';
                $plan->branchName = trim($plan->branchName, ',');
            }
            $plan->begin    = $plan->begin == $this->config->productplan->future ? $this->lang->productplan->future : $plan->begin;
            $plan->end      = $plan->end == $this->config->productplan->future ? $this->lang->productplan->future : $plan->end;
            $plan->actions  = $this->buildActionsList($plan);
            $plan->projects = array_values($plan->projects);
        }

        return array_values($plans);
    }

    /**
     * 构造计划列表页面操作。
     * Build actions for browse page.
     *
     * @param  object    $plan
     * @access protected
     * @return array
     */
    protected function buildActionsList(object $plan): array
    {
        $actions = array();
        if(common::hasPriv('productplan', 'start'))     $actions[] = 'start';
        if(common::hasPriv('productplan', 'finish'))    $actions[] = 'finish';
        if(common::hasPriv('productplan', 'close'))     $actions[] = 'close';
        if(common::hasPriv('productplan', 'activate'))  $actions[] = 'activate';
        if(common::hasPriv('execution', 'create'))      $actions[] = 'createExecution';

        if(count($actions) > 0) $actions[] = 'divider';

        if(common::hasPriv('productplan', 'linkStory')) $actions[] = 'linkStory';
        if(common::hasPriv('productplan', 'linkBug'))   $actions[] = 'linkBug';
        if(common::hasPriv('productplan', 'edit'))      $actions[] = 'edit';
        if(common::hasPriv('productplan', 'create'))    $actions[] = 'create';
        if(common::hasPriv('productplan', 'delete'))    $actions[] = 'delete';

        return $actions;
    }

    /**
     * 统计父计划、子计划和独立计划的总数。
     * Get the total count of parent plan, child plan and indepentdent plan.
     *
     * @param  array     $planList
     * @access protected
     * @return string
     */
    protected function getSummary(array $planList): string
    {
        $totalParent = $totalChild = $totalIndependent = 0;

        foreach($planList as $plan)
        {
            if($plan->parent == -1) $totalParent ++;
            if($plan->parent > 0)   $totalChild ++;
            if($plan->parent == 0)  $totalIndependent ++;
        }

        return sprintf($this->lang->productplan->summary, count($planList), $totalParent, $totalChild, $totalIndependent);
    }

    /**
     * 设置计划详情页面session信息。
     * Set session for view page.
     *
     * @param  int       $planID
     * @param  string    $type
     * @param  string    $orderBy
     * @param  int       $pageID
     * @param  int       $recPerPage
     * @access protected
     * @return void
     */
    protected function setSessionForViewPage(int $planID, string $type, string $orderBy, int $pageID, int $recPerPage)
    {
        if(in_array($type, array('story', 'bug')) && ($orderBy != 'order_desc' || $pageID != 1 || $recPerPage != 100))
        {
            if($type == 'story')
            {
                $this->session->set('storyList', $this->app->getURI(true), 'product');
            }
            elseif($type == 'bug')
            {
                $this->session->set('bugList', $this->app->getURI(true), 'qa');
            }
            else
            {
                $this->session->set('storyList', $this->createLink('productplan', 'view', "planID={$planID}&type={$type}"), $type == 'story' ? 'product' : 'qa');
            }
        }
    }

    /**
     * 设置详情页面的属性。
     * Set attributes for view page.
     *
     * @param  object    $plan
     * @access protected
     * @return void
     */
    protected function assignViewData(object $plan)
    {
        if($plan->parent > 0)     $this->view->parentPlan    = $this->productplan->getById($plan->parent);
        if($plan->parent == '-1') $this->view->childrenPlans = $this->productplan->getChildren($plan->id);

        $this->view->plan         = $plan;
        $this->view->actions      = $this->loadModel('action')->getList('productplan', $plan->id);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->plans        = $this->productplan->getPairs($plan->product, $plan->branch, '', true);
        $this->view->modules      = $this->loadModel('tree')->getOptionMenu($plan->product);

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->bugPager);
        }
    }
}
