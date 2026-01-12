<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 获取透视表。
     * Fetch pivot by id.
     *
     * @param int         $id
     * @param string|null $version
     * @access public
     * @return object|bool
     */
    protected function fetchPivot(int $id, ?string $version = null): object|bool
    {
        $pivot = $this->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($id)->andWhere('deleted')->eq('0')->fetch();
        if(!$pivot) return false;

        if(is_null($version)) return $this->mergePivotSpecData($pivot);

        $specData = $this->dao->select('*')->from(TABLE_PIVOTSPEC)->where('pivot')->eq($id)->andWhere('version')->eq($version)->fetch();
        if(!$specData) return $pivot;

        foreach($specData as $specKey => $specValue) $pivot->$specKey = $specValue;
        return $pivot;
    }

    /**
     * 合并 pivotSpec 的数据。
     * Merge pivotSpec data to pivot.
     *
     * @param int     $id
     * @param bool    $isObject
     * @access public
     * @return object|bool
     */
    protected function mergePivotSpecData($pivots, $isObject = true)
    {
        if($isObject) $pivots = array($pivots);
        $pivotIDList = array_column($pivots, 'id');

        $pivotSpecs = $this->dao->select('t2.pivot,t2.version,t2.driver,t2.mode,t2.name,t2.desc,t2.sql,t2.fields,t2.langs,t2.vars,t2.objects,t2.settings,t2.filters,t2.createdDate')->from(TABLE_PIVOT)->alias('t1')
            ->leftJoin(TABLE_PIVOTSPEC)->alias('t2')->on('t1.id = t2.pivot and t1.version = t2.version')
            ->where('t1.id')->in($pivotIDList)
            ->fetchAll('pivot', false);

        foreach($pivots as $index => $pivot)
        {
            if(!isset($pivotSpecs[$pivot->id])) continue;

            foreach($pivotSpecs[$pivot->id] as $specKey => $specValue) $pivot->$specKey = $specValue;
            $pivots[$index] = $pivot;
        }

        return $isObject ? current($pivots) : $pivots;
    }

    /**
     * 获取产品列表。
     * Get product list.
     *
     * @param  string       $conditions
     * @param  array|string $IDList
     * @param  array        $filters
     * @access public
     * @return array
     */
    protected function getProductList(string $conditions, array|string $idList = array(), array $filters = array()): array
    {
        $productID     = isset($filters['productID'])     ? $filters['productID']     : 0;
        $productStatus = isset($filters['productStatus']) ? $filters['productStatus'] : '';
        $productType   = isset($filters['productType'])   ? $filters['productType']   : '';

        return $this->dao->select('t1.id, t1.code, t1.name, t1.PO')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.shadow')->eq('0')
            ->beginIF(!empty($idList))->andWhere('t1.id')->in($idList)->fi()
            ->beginIF($productID)->andWhere('t1.id')->eq($productID)->fi()
            ->beginIF($productStatus)->andWhere('t1.status')->eq($productStatus)->fi()
            ->beginIF($productType)->andWhere('t1.type')->eq($productType)->fi()
            ->filterTpl('skip')
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
        return $this->dao->select("t1.project AS projectID, t1.execution AS executionID, t2.multiple, t2.end, IF(t3.multiple = '1', t2.name, '') AS executionName, t3.name AS projectName, ROUND(SUM(t1.estimate), 2) AS estimate, ROUND(SUM(t1.consumed), 2) AS consumed")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->where('t1.status')->ne('cancel')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.status')->eq('closed')
            ->beginIF($begin)->andWhere('t2.realBegan')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t2.realEnd')->le($end)->fi()
            ->beginIF(!empty($executionIDList))->andWhere('t2.id')->in($executionIDList)->fi()
            ->groupBy('t1.project, t1.execution, t2.multiple, t2.end, t2.name, t3.multiple, t3.name')
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
        $assignedToList = $this->dao->select("DISTINCT IF(tt1.mode = '', tt1.assignedTo, tt2.account) AS assignedTo")->from(TABLE_TASK)->alias('tt1')
            ->leftJoin(TABLE_TASKTEAM)->alias('tt2')->on("tt1.id=tt2.task AND tt1.mode IN ('multi', 'linear')")
            ->where('tt1.status')->notIn('cancel,closed,done,pause')
            ->andWhere("IF(tt1.mode = '', tt1.assignedTo, tt2.account)")->ne('')
            ->andWhere('tt1.execution = t1.`root`')
            ->get();

        return $this->dao->select('t1.account AS user, t2.multiple, t2.id AS executionID, t2.name AS executionName, t3.id AS projectID, t3.name AS projectName')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t2.id = t1.root')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
            ->where('t1.type')->eq('execution')
            ->andWhere("t1.account NOT IN ($assignedToList)")
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
        return $this->dao->select(<<<EOT
t1.id,
t1.isParent,
CASE WHEN t1.mode = '' THEN t1.assignedTo ELSE t4.account END AS user,
CASE WHEN t1.mode = '' THEN ROUND(t1.`left`, 2) ELSE ROUND(t4.`left`, 2) END AS `left`,
t2.multiple,
t2.id AS executionID,
t2.name AS executionName,
t3.id AS projectID,
t3.name AS projectName
EOT)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on("t1.id=t4.task and t1.mode IN ('multi', 'linear')")
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ge(0)
            ->andWhere('t1.status')->in('wait,pause,doing')
            ->andWhere("if(t1.mode = '', t1.assignedTo, t4.account)")->ne('')
            ->beginIF($deptUsers)->andWhere("if(t1.mode = '', t1.assignedTo, t4.account)")->in($deptUsers)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.vision')->like('rnd')
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
        return $this->dao->select('product, assignedTo, COUNT(1) AS bugCount')->from(TABLE_BUG)
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
        $viewableObjects = $this->bi->getViewableObject('pivot');
        return (int)$this->dao->select('id')->from(TABLE_PIVOT)
            ->where("FIND_IN_SET({$groupID}, `group`)")
            ->andWhere('stage')->ne('draft')
            ->andWhere('deleted')->eq('0')
            ->andWhere('id')->in($viewableObjects)
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
        $pivots = $this->dao->select('*')->from(TABLE_PIVOT)
            ->where("FIND_IN_SET({$groupID}, `group`)")
            ->andWhere('stage')->ne('draft')
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('', false);

        return $this->mergePivotSpecData($pivots, false);
    }

    /**
     * 获取透视表汇总列的下钻配置。
     * Get drill config of pivot summary column field.
     *
     * @param  int    $pivotID
     * @param  string $field
     * @param  string $status
     * @access public
     * @return object|bool
     */
    public function fetchPivotDrills(int $pivotID, string $version, string|array $fields): array
    {
        if(is_string($fields)) $fields = array($fields);
        $records = $this->dao->select('*')->from(TABLE_PIVOTDRILL)
            ->where('pivot')->eq($pivotID)
            ->andWhere('version')->eq($version)
            ->andWhere('field')->in($fields)
            ->fetchAll('field', false);

        foreach($records as $field => $record)
        {
            $record->condition = json_decode($record->condition, true);
            $records[$field] = $record;
        }

        return $records;
    }
}
