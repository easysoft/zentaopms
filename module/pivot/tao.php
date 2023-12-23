<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 获取产品列表。
     * Get product list.
     *
     * @param  string       $conditions
     * @param  array|string $IDList
     * @access public
     * @return array
     */
    protected function getProductList(string $conditions, array|string $IDList = array()): array
    {
        return $this->dao->select('t1.id, t1.code, t1.name, t1.PO')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.shadow')->eq('0')
            ->beginIF(strpos($conditions, 'closedProduct') === false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!empty($IDList))->andWhere('t1.id')->in($IDList)->fi()
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->fetchAll('id');
    }

    /**
     * 补充产品的计划信息。
     * Supplement product plan information.
     *
     * @param  array  $products
     * @access public
     * @return array
     */
    protected function processProductPlan(array &$products, string $conditions): array
    {
        /* 获取产品的计划信息，并且根据产品id进行分组。 */
        /* Get the plan information of the product and group it by product id. */
        $plans = $this->dao->select('id, product, branch, parent, title, begin, end')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in(array_keys($products))
            ->beginIF(strpos($conditions, 'overduePlan') === false)->andWhere('end')->gt(date('Y-m-d'))->fi()
            ->orderBy('product, parent_desc, begin')
            ->fetchAll('id');

        foreach($plans as $plan)
        {
            if($plan->parent > 0)
            {
                $parentPlan = zget($plans, $plan->parent, null);
                if($parentPlan)
                {
                    $products[$plan->product]->plans[$parentPlan->id] = $parentPlan;
                    unset($plans[$parentPlan->id]);
                }
                $plan->title = '>>' . $plan->title;
            }
            $products[$plan->product]->plans[$plan->id] = $plan;
        }

        return $plans;
    }

    /**
     * 获取产品的需求信息。
     * Get product demand information.
     *
     * @param  string $storyType
     * @param  array  $plans
     * @access public
     * @return array
     */
    protected function processPlanStories(array &$products, string $storyType, array $plans): array
    {
        /* 获取所有符合条件的需求。 */
        /* Get all the requirements that meet the conditions. */
        $plannedStories      = array();
        $unplannedStories = array();
        $stmt = $this->dao->select('id, plan, product, status')->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere('parent')->ge(0)
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->query();

        /* 根据需求的计划信息，将需求分组到不同的计划中。 */
        /* According to the plan information of the demand, the demand is grouped into different plans. */
        while($story = $stmt->fetch())
        {
            if(empty($story->plan))
            {
                $unplannedStories[$story->id] = $story;
                continue;
            }

            $storyPlans   = array();
            $storyPlans[] = $story->plan;
            if(strpos($story->plan, ',') !== false) $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID)
            {
                if(isset($plans[$planID]))
                {
                    $plannedStories[$story->id] = $story;
                    break;
                }
            }
        }

        /* 将需求统计信息添加到产品中。 */
        /* Add demand statistics information to the product. */
        $this->getPlanStatusStatistics($products, $plans, $plannedStories, $unplannedStories);

        return $products;
    }

    /**
     * 获取产品计划的需求统计信息。
     * Get product demand statistics information.
     *
     * @param  array     $products
     * @param  array     $plans
     * @param  array     $plannedStories
     * @param  array     $unplannedStories
     * @access protected
     * @return void
     */
    protected function getPlanStatusStatistics(array &$products, array $plans, array $plannedStories, array $unplannedStories): void
    {
        /* 统计已经计划过的产品计划的需求状态信息。 */
        /* Statistics of demand status information for planned product plans. */
        foreach($plannedStories as $story)
        {
            $storyPlans = strpos($story->plan, ',') !== false ? $storyPlans = explode(',', trim($story->plan, ',')) : array($story->plan);
            foreach($storyPlans as $planID)
            {
                if(!isset($plans[$planID])) continue;
                $plan = $plans[$planID];
                $products[$plan->product]->plans[$planID]->status[$story->status] = isset($products[$plan->product]->plans[$planID]->status[$story->status]) ? $products[$plan->product]->plans[$planID]->status[$story->status] + 1 : 1;
            }
        }

        /* 统计还未计划的产品计划的需求状态信息。 */
        /* Statistics of demand status information for unplanned product plans. */
        foreach($unplannedStories as $story)
        {
            $product = $story->product;
            if(isset($products[$product]))
            {
                if(!isset($products[$product]->plans[0]))
                {
                    $products[$product]->plans[0] = new stdClass();
                    $products[$product]->plans[0]->title = $this->lang->pivot->unplanned;
                    $products[$product]->plans[0]->begin = '';
                    $products[$product]->plans[0]->end   = '';
                }
                $products[$product]->plans[0]->status[$story->status] = isset($products[$product]->plans[0]->status[$story->status]) ? $products[$product]->plans[0]->status[$story->status] + 1 : 1;
            }
        }
    }


    /**
     * 获取执行列表。
     * Get execution list.
     *
     * @param  string $begin
     * @param  string $end
     * @param  array  $executionIDList
     * @access public
     * @return array
     */
    protected function getExecutionList(string $begin, string $end, $executionIDList = array()): array
    {
        return $this->dao->select("t1.project AS projectID, t1.execution AS executionID, t2.multiple, IF(t3.multiple = '1', t2.name, '') AS executionName, t3.name AS projectName, ROUND(SUM(t1.estimate), 2) AS estimate, ROUND(SUM(t1.consumed), 2) AS consumed")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->where('t1.status')->ne('cancel')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.status')->eq('closed')
            ->beginIF($begin)->andWhere('t2.begin')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t2.end')->le($end)->fi()
            ->beginIF(!empty($executionIDList))->andWhere('t2.id')->in($executionIDList)->fi()
            ->groupBy('t1.project, t1.execution, t2.multiple')
            ->orderBy('t2.end_desc')
            ->fetchAll();
    }

    /**
     * 获取bug分组信息。
     * Get bug group information.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    protected function getBugGroup(string $begin, string $end, int $product, int $execution): array
    {
        return $this->dao->select("IF(resolution = '', 'unResolved', resolution) AS resolution, openedBy, status")->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->andWhere('openedDate')->ge($begin)
            ->andWhere('openedDate')->le($end)
            ->beginIF($product)->andWhere('product')->eq($product)->fi()
            ->beginIF($execution)->andWhere('execution')->eq($execution)->fi()
            ->fetchGroup('openedBy');
    }

    /**
     * 获取未指派的执行。
     * Get unassigned executions.
     *
     * @param  array  $deptUsers
     * @access public
     * @return array
     */
    protected function getNoAssignExecution(array $deptUsers): array
    {
        return $this->dao->select('t1.account AS user, t2.multiple, t2.id AS executionID, t2.name AS executionName, t3.id AS projectID, t3.name AS projectName')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t2.id = t1.root')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
            ->where('t1.type')->eq('execution')
            ->andWhere('t1.account NOT IN (SELECT DISTINCT `assignedTo` FROM ' . TABLE_TASK . " WHERE `execution` = t1.`root` AND `status` NOT IN ('cancel', 'closed', 'done', 'pause') AND assignedTo != '')")
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.status')->notin('cancel, closed, done, suspended')
            ->beginIF($deptUsers)->andWhere('t1.account')->in($deptUsers)->fi()
            ->fetchAll();
    }

    /**
     * 获取已指派的执行。
     * Get assigned executions.
     *
     * @param  array  $deptUsers
     * @access public
     * @return array
     */
    protected function getAssignTask(array $deptUsers): array
    {
        return $this->dao->select('t1.id, t1.assignedTo AS user, t1.left, t2.multiple, t2.id AS executionID, t2.name AS executionName, t3.id AS projectID, t3.name AS projectName')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ge(0)
            ->andWhere('t1.status')->in('wait,pause,doing')
            ->andWhere('t1.assignedTo')->ne('')
            ->beginIF($deptUsers)->andWhere('t1.assignedTo')->in($deptUsers)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.status')->in('wait,suspended,doing')
            ->fetchAll();
    }

    /**
     * 获取任务相关的团队信息。
     * Get team information related to tasks.
     *
     * @param  array  $taskIDList
     * @access public
     * @return array
     */
    protected function getTeamTasks(array $taskIDList): array
    {
        return $this->dao->select('task, SUM(`left`) AS `left`')->from(TABLE_TASKTEAM)
            ->where('task')->in($taskIDList)
            ->groupBy('task')
            ->fetchPairs('task');
    }

    /**
     * 获取指派的bug。
     * Get assigned bugs.
     *
     * @access public
     * @return array
     */
    protected function getAssignBugGroup(): array
    {
        return $this->dao->select('product, assignedTo, COUNT(*) AS bugCount')->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->andWhere('status')->eq('active')
            ->andWhere('assignedTo')->ne('')
            ->andWhere('assignedTo')->ne('closed')
            ->groupBy('product, assignedTo')
            ->fetchGroup('assignedTo');
    }

    /**
     * 获取产品项目关联关系。
     * Get product project association.
     *
     * @access public
     * @return array
     */
    protected function getProductProjects(): array
    {
        return $this->dao->select('t2.product, t2.project')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.hasProduct')->eq(0)
            ->fetchPairs();
    }

    /**
     * 获取所有产品的id和name。
     * Get the id and name of all products.
     *
     * @access public
     * @return array
     */
    protected function getAllProductsIDAndName(): array
    {
        return $this->dao->select('id, name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq('0')
            ->fetchPairs();
    }

    /**
     * 获取产品和执行名称。
     * Get product and execution name.
     *
     * @access public
     * @return array
     */
    protected function getProjectAndExecutionNameQuery(): array
    {
        return $this->dao->select('t1.id, t1.name, t2.name as projectname, t1.status, t1.multiple')
            ->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in('stage,sprint')
            ->fetchAll();
    }

    /**
     * 获取一个维度的第一个分组。
     * Get the first group of a dimension.
     *
     * @param  int       $dimensionID
     * @access protected
     * @return int
     */
    protected function getFirstGroup(int $dimensionID): int
    {
        return (int)$this->dao->select('id')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('pivot')
            ->andWhere('root')->eq($dimensionID)
            ->andWhere('grade')->eq(1)
            ->orderBy('`order`')
            ->limit(1)
            ->fetch('id');
    }

    /**
     * 通过维度和路径获取分组。
     * Get group by dimension and path.
     *
     * @param  int       $dimensionID
     * @param  string    $path
     * @access protected
     * @return array
     */
    protected function getGroupsByDimensionAndPath(int $dimensionID, string $path): array
    {
        return $this->dao->select('id, grade, name, collector')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('root')->eq($dimensionID)
            ->andWhere('path')->like("{$path}%")
            ->orderBy('`order`')
            ->fetchAll();
    }

    /**
     * 根据分组获取对应的透视表id。
     * Get the corresponding pivot table id according to the group.
     *
     * @param  int       $groupID
     * @access protected
     * @return int
     */
    protected function getPivotID(int $groupID): int
    {
        return (int)$this->dao->select('id')->from(TABLE_PIVOT)
            ->where("FIND_IN_SET({$groupID}, `group`)")
            ->andWhere('stage')->ne('draft')
            ->orderBy('id_desc')
            ->limit(1)
            ->fetch('id');
    }

    /**
     * 根据一个分组下的所有透视表。
     * Get all pivot tables under a group.
     *
     * @param  int       $groupID
     * @access protected
     * @return array
     */
    protected function getAllPivotByGroupID(int $groupID): array
    {
        return $this->dao->select('*')->from(TABLE_PIVOT)
            ->where("FIND_IN_SET({$groupID}, `group`)")
            ->andWhere('stage')->ne('draft')
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
    }
}
