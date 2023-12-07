<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 设置默认的过滤器
     * Set default filter.
     *
     * @param  array  $filters
     * @access public
     * @return void
     */
    protected function setFilterDefault(array &$filters): void
    {
        foreach($filters as &$filter)
        {
            if(empty($filter['default'])) continue;
            if(is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }
    }

    /**
     * 从透视表对象中获取字段
     * Get fields from pivot object.
     *
     * @param  object    $pivot
     * @param  string    $key
     * @param  mixed     $default
     * @param  bool      $jsonDecode
     * @param  bool      $needArray
     * @access protected
     * @return mixed
     */
    protected function getFieldsFromPivot(object $pivot, string $key, mixed $default, bool $jsonDecode = false, bool $needArray = false)
    {
        return isset($pivot->{$key}) && !empty($pivot->{$key}) ? ($jsonDecode ? json_decode($pivot->{$key}, $needArray) : $pivot->{$key}) : $default;
    }

    /**
     * 重建透视表filedSettings字段
     * Rebuild fieldSettings field of pivot.
     *
     * @param  object $pivot
     * @param  array  $fieldPairs
     * @param  object $columns
     * @param  array  $relatedObject
     * @param  object $fieldSettings
     * @access public
     * @return void
     */
    protected function rebuildFieldSetting(object $pivot, array $fieldPairs, object $columns, array $relatedObject, object $fieldSettings): void
    {
        $fieldSettingsNew = new stdclass();

        foreach($fieldPairs as $index => $field)
        {
            $defaultType   = $columns->$index;
            $defaultObject = $relatedObject[$index];

            if(isset($objectFields[$defaultObject][$index])) $defaultType = $objectFields[$defaultObject][$index]['type'] == 'object' ? 'string' : $objectFields[$defaultObject][$index]['type'];

            if(!isset($fieldSettings->$index))
            {
                /* 如果字段设置中没有该字段，则使用默认值 */
                /* If the field is not set in the field settings, use the default value. */
                $fieldItem = new stdclass();
                $fieldItem->name   = $field;
                $fieldItem->object = $defaultObject;
                $fieldItem->field  = $index;
                $fieldItem->type   = $defaultType;

                $fieldSettingsNew->$index = $fieldItem;
            }
            else
            {
                /* 兼容旧版本的字段设置，当为空或者为布尔值时，使用默认值 */
                /* Compatible with old version of field settings, use default value when empty or boolean. */
                if(!isset($fieldSettings->$index->object) || is_bool($fieldSettings->$index->object) || strlen($fieldSettings->$index->object) == 0) $fieldSettings->$index->object = $defaultObject;

                /* 当字段设置中没有字段名时，使用默认的字段名配置。 */
                /* When there is no field name in the field settings, use the default field name configuration. */
                if(!isset($fieldSettings->$index->field) || strlen($fieldSettings->$index->field) == 0)
                {
                    $fieldSettings->$index->field  = $index;
                    $fieldSettings->$index->object = $defaultObject;
                    $fieldSettings->$index->type   = 'string';
                }

                $object = $fieldSettings->$index->object;
                $type   = $fieldSettings->$index->type;
                if($object == $defaultObject && $type != $defaultType) $fieldSettings->$index->type = $defaultType;

                $fieldSettingsNew->$index = $fieldSettings->$index;
            }
        }

        $pivot->fieldSettings = $fieldSettingsNew;
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
     * @param  array  $products
     * @param  array  $plannedStories
     * @param  array  $unplannedStories
     * @access public
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
     * 获取bug的统计信息。
     * Get bug statistics information.
     *
     * @param  array  $bugGroups
     * @access public
     * @return array
     */
    protected function getBugStatistics(array $bugGroups): array
    {
        /* 为bug生成透视表数据。 */
        /* Generate pivot data for bugs. */
        $bugs = array();
        foreach($bugGroups as $account => $userBugs)
        {
            $bug = array();
            $bug['openedBy']   = $account;
            $bug['unResolved'] = 0;
            $bug['validRate']  = 0;
            $bug['total']      = 0;

            /* 初始化bug状态数据。 */
            /* Initialize bug status data. */
            foreach(array_keys($this->lang->bug->resolutionList) as $resolution)
            {
                if($resolution) $bug[$resolution] = 0;
            }

            /* 获取bug各个状态的统计数据。 */
            /* Get statistics data for each status of bugs. */
            $resolvedCount = 0;
            $validCount    = 0;
            foreach($userBugs as $userBug)
            {
                if(!isset($bug[$userBug->resolution])) continue;

                $bug[$userBug->resolution]++;
                $bug['total']++;

                if($userBug->status == 'resolved' || $userBug->status == 'closed') $resolvedCount++;
                if($userBug->resolution == 'fixed' || $userBug->resolution == 'postponed') $validCount++;
            }

            /* 如果bug总数为0，则不显示。 */
            /* If the total number of bugs is 0, it will not be displayed. */
            if(!$bug['total']) continue;

            /* 计算已解决bug的百分比。 */
            /* Calculate the percentage of resolved bugs. */
            $bug['validRate'] = $resolvedCount ? round($validCount / $resolvedCount * 100, 2) . '%' : '0%';

            $bugs[] = $bug;
        }

        return $bugs;
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
