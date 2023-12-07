<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 获取产品列表。
     * Get product list.
     *
     * @param  string $conditions
     * @param  array  $IDList
     * @access public
     * @return array
     */
    protected function getProductList(string $conditions, array $IDList = array()): array
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
     * 获取用户的工作负载。
     * Get user's workload.
     *
     * @param  array  $projects
     * @param  array  $teamTasks
     * @param  float  $allHour
     * @access public
     * @return array
     */
    protected function getUserWorkLoad(array $projects, array $teamTasks, float $allHour): array
    {
        /* 计算用户的任务数，剩余工时和总任务数。 */
        /* Calculate user's task count, left hours and total task count. */
        $totalTasks = $totalHours = $totalExecutions = 0;
        foreach($projects as $executions)
        {
            $totalExecutions += count($executions);
            foreach($executions as $tasks)
            {
                $totalTasks += count($tasks);
                foreach($tasks as $task)
                {
                    if(isset($teamTasks[$task->id])) $task->left = $teamTasks[$task->id]->left;

                    $totalHours += $task->left;
                }
            }
        }

        /* 计算用户的工作负载。 */
        /* Calculate user's workload. */
        $userWorkload = $allHour ? round($totalHours / $allHour * 100, 2) . '%' : '0%';

        return array($totalTasks, $totalHours, $totalExecutions, $userWorkload);
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
}
