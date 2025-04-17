<?php
declare(strict_types=1);
/**
 * The tao file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
class productplanTao extends productplanModel
{
    /**
     * 获取产品计划列表。
     * Get plan list.
     *
     * @param  array     $productIdList
     * @param  string    $branch
     * @param  string    $browseType
     * @param  string    $param
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getPlanList(array $productIdList, string $branch = '', string $browseType = '', string $param = '', string $orderBy = '', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->beginIF(!empty($branch) && $branch != 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'bySearch', 'review')))->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('status')->in('wait,doing')->fi()
            ->beginIF($browseType == 'bySearch')->andWhere($this->session->productplanQuery)->fi()
            ->beginIF(strpos($param, 'skipparent') !== false)->andWhere('parent')->ne(-1)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 根据计划列表获取产品计划信息。
     * Get plan info by plan list.
     *
     * @param  array     $planID
     * @param  int|null  $productID
     * @access protected
     * @return array
     */
    protected function getPlanProjects(array $planIdList, int|null $productID = null): array
    {
        $planProjects = [];
        $projects     = $this->dao->select('t1.name, t2.project, t2.plan')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in('sprint,stage,kanban')
            ->beginIF(!is_null($productID))->andWhere('t2.product')->eq($productID)->fi()
            ->orderBy('project_desc')
            ->fetchAll();

        foreach($projects as $project)
        {
            if(empty($project->plan)) continue;
            $plans = array_filter(explode(',', $project->plan));
            foreach($plans as $planID) $planProjects[$planID][$project->project] = $project;
        }

        foreach($planIdList as $planID)
        {
            if(!isset($planProjects[$planID])) $planProjects[$planID] = [];
        }

        return $planProjects;
    }

    /**
     * 更新产品计划的关联信息。
     * Update plan related info.
     *
     * @param  int       $planID
     * @param  array     $storyIdList
     * @param  bool      $deleteOld
     * @access protected
     * @return bool
     */
    protected function syncLinkedStories(int $planID, array $storyIdList, bool $deleteOld = true): bool
    {
        if($deleteOld) $this->dao->delete()->from(TABLE_PLANSTORY)->where('plan')->eq($planID)->exec();

        $order = 1;
        foreach($storyIdList as $storyID)
        {
            $order ++;
            $planStory = new stdclass();
            $planStory->plan  = $planID;
            $planStory->story = $storyID;
            $planStory->order = $order;
            $this->dao->replace(TABLE_PLANSTORY)->data($planStory)->exec();
        }

        return !dao::isError();
    }
}
