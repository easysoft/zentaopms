<?php
/**
 * The model file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 4726 2013-05-03 05:51:27Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class pivotModel extends model
{
     /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadBIDAO();
    }

    /*
     * 获取透视表。
     * Get pivot.
     *
     * @param  int         $pivotID
     * @access public
     * @return object|bool
     */
    public function getByID(int $pivotID): object|bool
    {
        $pivot = $this->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();
        if(!$pivot) return false;

        $pivot->fieldSettings = array();
        if(!empty($pivot->fields) && $pivot->fields != 'null')
        {
            $pivot->fieldSettings = json_decode($pivot->fields);
            $pivot->fields        = array_keys(get_object_vars($pivot->fieldSettings));
        }

        if(!empty($pivot->filters))
        {
            $filters = json_decode($pivot->filters, true);
            $this->setFilterDefault($filters);
            $pivot->filters = $filters;
        }

        return $this->processPivot($pivot);
    }

    /**
     * 构建sql的时间查询。
     * Build sql date query.
     *
     * @param  string $var
     * @param  string $type
     * @access public
     * @return string
     */
    public function processDateVar(string $var, string $type = 'date'): string
    {
        if(empty($var)) return '';

        $format = $type == 'datetime' ? 'Y-m-d H:i:s' : 'Y-m-d';
        switch($var)
        {
            case '$MONDAY':     $var = date($format, time() - (date('N') - 1) * 24 * 3600); break;
            case '$SUNDAY':     $var = date($format, time() + (7 - date('N')) * 24 * 3600); break;
            case '$MONTHBEGIN': $var = date($format, time() - (date('j') - 1) * 24 * 3600); break;
            case '$MONTHEND':   $var = date($format, time() + (date('t') - date('j')) * 24 * 3600); break;
        }
        return $var;
    }

    /**
     * 构建透视表的信息。
     * Process sql and correct type.
     *
     * @param  object|array $pivots
     * @param  bool         $isObject
     * @access public
     * @return object|array
     */
    public function processPivot(object|array $pivots, bool $isObject = true): object|array
    {
        if($isObject) $pivots = array($pivots);

        $screenList = $this->dao->select('scheme')->from(TABLE_SCREEN)->where('deleted')->eq(0)->andWhere('status')->eq('published')->fetchAll();
        foreach($pivots as $pivot) $this->completePivot($pivot, $screenList);

        if($isObject && isset($pivot->stage) && $pivot->stage == 'published') $this->processFieldSettings($pivot);

        return $isObject ? $pivot : $pivots;
    }

    /**
     * 补充透视表的信息。
     * Complete pivot.
     *
     * @param  object $pivot
     * @param  array  $screenList
     * @access public
     * @return void
     */
    public function completePivot(object $pivot, array $screenList): void
    {
        if(!empty($pivot->sql))      $pivot->sql      = trim(str_replace(';', '', $pivot->sql));
        if(!empty($pivot->settings)) $pivot->settings = json_decode($pivot->settings, true);

        if(empty($pivot->type))
        {
            $pivot->names = array();
            $pivot->descs = array();
            if(!empty($pivot->name))
            {
                $pivotNames   = json_decode($pivot->name, true);
                $pivot->name  = zget($pivotNames, $this->app->getClientLang(), '') ? : reset($pivotNames);
                $pivot->names = $pivotNames;
            }

            if(!empty($pivot->desc))
            {
                $pivotDescs   = json_decode($pivot->desc, true);
                $pivot->desc  = zget($pivotDescs, $this->app->getClientLang(), '');
                $pivot->descs = $pivotDescs;
            }

            $pivot->used = $this->checkIFChartInUse($pivot->id, $screenList, 'pivot');
        }
    }

    /**
     * 检测图表是否在使用。
     * Check if the Chart is in use.
     *
     * @param  int    $chartID
     * @param  string $type
     * @access public
     * @return bool
     */
    public function checkIFChartInUse(int $chartID, array $screenList, string $type = 'chart'): bool
    {
        foreach($screenList as $screen)
        {
            $scheme = json_decode($screen->scheme);
            if(empty($scheme->componentList)) continue;

            foreach($scheme->componentList as $component)
            {
                $list = !empty($component->isGroup) ? $component->groupList : array($component);
                foreach($list as $groupComponent)
                {
                    if(!isset($groupComponent->chartConfig)) continue;

                    $sourceID   = zget($groupComponent->chartConfig, 'sourceID', '');
                    $sourceType = zget($groupComponent->chartConfig, 'package', '') == 'Tables' ? 'pivot' : 'chart';

                    if($chartID == $sourceID && $type == $sourceType) return true;
                }
            }
        }
        return false;
    }

    /**
     * 构建透视表字段的配置信息，类似于dataview/js/basequery.js getFieldSettings()。
     * Process pivot field settings, function like dataview/js/basequery.js getFieldSettings().
     *
     * @param  object $pivot
     * @access public
     * @return void
     */
    public function processFieldSettings(object $pivot)
    {
        $fieldSettings = $pivot->fieldSettings ?? $this->getFieldsFromPivot($pivot, 'fields', array(), true);
        if(empty($fieldSettings)) return;

        /* 获取$filters。 */
        /* Get $filters. */
        $sql     = isset($pivot->sql)     ? $pivot->sql     : '';
        $filters = $this->getFieldsFromPivot($pivot, 'filters', array(), !is_array($pivot->filters), true);
        if(!empty($filters)) $this->setFilterDefault($filters);

        /* 检测sql是否有效。 */
        /* Check if the sql is valid. */
        $querySQL = $this->loadModel('chart')->parseSqlVars($sql, $filters);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $stmt = $this->dbh->query($querySQL);
        if(!$stmt) return;

        $columns      = $this->loadModel('dataview')->getColumns($querySQL);
        $columnFields = array();
        foreach(array_keys(get_object_vars($columns)) as $type) $columnFields[$type] = $type;

        /* 获取$tables和$fields。 */
        /* Get $tables and $fields. */
        extract($this->chart->getTables($querySQL));

        /* 获取field的键值对以及相关联的对象。 */
        /* Get field key value pairs and related objects. */
        $moduleNames = $tables ? $this->dataview->getModuleNames($tables) : array();
        list($fieldPairs, $relatedObject) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames);

        /* 使用fieldPairs, columns, relatedObject, objectFields刷新pivot的fieldSettings。 */
        /* Use fieldPairs, columns, relatedObject, objectFields refresh pivot fieldSettings .*/
        $objectFields = array();
        foreach(array_keys($this->lang->dataview->objects) as $object) $objectFields[$object] = $this->dataview->getTypeOptions($object);

        /* 重建fieldSettings。 */
        /* Rebuild fieldSettings. */
        $this->rebuildFieldSetting($pivot, $fieldPairs, $columns, $relatedObject, $fieldSettings);
    }

    /**
     * 重建透视表filedSettings字段
     * Rebuild fieldSettings field of pivot.
     *
     * @param  object  $pivot
     * @param  array   $fieldPairs
     * @param  object  $columns
     * @param  array   $relatedObject
     * @param  object  $fieldSettings
     * @access private
     * @return void
     */
    private function rebuildFieldSetting(object $pivot, array $fieldPairs, object $columns, array $relatedObject, object $fieldSettings): void
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
     * 获取执行。
     * Get executions.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getExecutions(string $begin = '', string $end = ''): array
    {
        $permission = common::hasPriv('pivot', 'showProject') || $this->app->user->admin;
        $IDList     = !$permission ? $this->app->user->view->sprints : array();
        $executions = $this->pivotTao->getExecutionList($begin, $end, $IDList);

        foreach($executions as $execution)
        {
            $execution->deviation     = round($execution->consumed - $execution->estimate, 2);
            $execution->deviationRate = $execution->estimate ? round($execution->deviation / $execution->estimate * 100, 2) : 'n/a';
        }

        return $executions;
    }

    /**
     * 获取产品透视表。
     * Get products.
     *
     * @param  string $conditions
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getProducts(string $conditions, string $storyType = 'story'): array
    {
        /* 获取符合条件的产品。 */
        /* Get products. */
        $permission = common::hasPriv('pivot', 'showProduct') || $this->app->user->admin;
        $IDList     = !$permission ? $this->app->user->view->products : array();
        $products   = $this->pivotTao->getProductList($conditions, $IDList);

        /* 为产品生成计划数据和相关的需求数据。 */
        /* Generate plan data and related story data for products. */
        $this->pivotTao->processPlanStories($products, $storyType, $this->pivotTao->processProductPlan($products, $conditions));

        unset($products['']);
        return $products;
    }

    /**
     * 获取bug相关透视表信息。
     * Get bug related pivot information.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    public function getBugs(string $begin, string $end, int $product = 0, int $execution = 0): array
    {
        /* 获取符合条件的bug。 */
        /* Get bugs. */
        $end       = date('Y-m-d', strtotime("{$end} +1 day"));
        $bugGroups = $this->pivotTao->getBugGroup($begin, $end, $product, $execution);

        /* 为bug生成统计数据。 */
        /* Generate statistics data for bugs. */
        $bugs = $this->getBugStatistics($bugGroups);

        uasort($bugs, 'sortSummary');

        return $bugs;
    }

    /**
     * 获取bug的统计信息。
     * Get bug statistics information.
     *
     * @param  array  $bugGroups
     * @access public
     * @return array
     */
    private function getBugStatistics(array $bugGroups): array
    {
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

            if(!$bug['total']) continue;

            /* 计算已解决bug的百分比。 */
            /* Calculate the percentage of resolved bugs. */
            $bug['validRate'] = $resolvedCount ? round($validCount / $resolvedCount * 100, 2) . '%' : '0%';

            $bugs[] = $bug;
        }

        return $bugs;
    }

    /**
     * Get workload.
     *
     * @param  int    $dept
     * @param  string $assign   assign|noassign
     * @param  array  $users
     * @param  float  $allHour
     * @access public
     * @return array
     */
    public function getWorkload(int $dept, string $assign, array $users, float $allHour): array
    {
        /* 判断是否需要查询部门用户。 */
        /* Check if need to query department users. */
        $deptUsers = array();
        if($dept)
        {
            $deptUsers = $this->loadModel('dept')->getDeptUserPairs($dept);
            if(!$deptUsers) return array();
        }

        $canViewExecution = common::hasPriv('execution', 'view');

        return $assign == 'noassign' ? $this->getWorkloadNoAssign($deptUsers, $users, $canViewExecution) : $this->getWorkLoadAssign($deptUsers, $users, $canViewExecution, $allHour);

    }

    /**
     * 获取未指派的员工负载表。
     * Get workload no assign.
     *
     * @param  array  $deptUsers
     * @param  array  $users
     * @param  bool   $canViewExecution
     * @access public
     * @return array
     */
    private function getWorkloadNoAssign(array $deptUsers, array $users, bool $canViewExecution): array
    {
        $executions = $this->pivotTao->getNoAssignExecution(array_keys($deptUsers));
        if(empty($executions)) return array();

        /* 构建需要的用户-项目-执行数据结构。 */
        /* Build user-project-execution data structure. */
        $executionGroups = array();
        foreach($executions as $execution)
        {
            if(!isset($users[$execution->user])) continue;
            $executionGroups[$execution->user][$execution->projectID][$execution->executionID] = $execution;
        }

        /* 计算未指派的执行统计数据。 */
        /* Calculate statistics data for no assign execution. */
        $workload = array();
        foreach($executionGroups as $account => $projects)
        {
            if(!isset($users[$account])) continue;

            $totalExecutions = 0;
            foreach($projects as $executions) $totalExecutions += count($executions);

            $userFirstRow = true;
            foreach($projects as $executions)
            {
                $projectFirstRow = true;
                foreach($executions as $execution)
                {
                    $execution->executionTasks = 0;
                    $execution->executionHours = 0;
                    $execution->totalTasks     = 0;
                    $execution->totalHours     = 0;
                    $execution->workload       = '0%';
                    $this->setExecutionName($execution, $canViewExecution);

                    if($userFirstRow)    $execution->userRowspan    = $totalExecutions;
                    if($projectFirstRow) $execution->projectRowspan = count($executions);

                    $workload[] = $execution;

                    $userFirstRow    = false;
                    $projectFirstRow = false;
                }
            }
        }

        return $workload;
    }

    /**
     * 获取指派的员工负载表。
     * Get workload assign.
     *
     * @param  array  $deptUsers
     * @param  array  $users
     * @param  bool   $canViewExecution
     * @param  float  $allHour
     * @access public
     * @return array
     */
    private function getWorkLoadAssign(array $deptUsers, array $users, bool $canViewExecution, float $allHour): array
    {
        $tasks = $this->pivotTao->getAssignTask(array_keys($deptUsers));
        if(empty($tasks)) return array();

        /* 构建需要的用户-项目-执行-任务数据结构。 */
        /* Build user-project-execution-task data structure. */
        $taskGroups = array();
        foreach($tasks as $task)
        {
            if(!isset($users[$task->user])) continue;
            $taskGroups[$task->user][$task->projectID][$task->executionID][$task->id] = $task;
        }

        /* 获取团队任务的剩余工时。 */
        /* Get team task left hours. */
        $teamTasks = $this->pivotTao->getTeamTasks(array_keys($deptUsers));

        $workload = array();
        foreach($taskGroups as $projects)
        {
            list($totalTasks, $totalHours, $totalExecutions, $userWorkload) = $this->getUserWorkLoad($projects, $teamTasks, $allHour);

            /* 计算用户的执行统计数据。 */
            /* Calculate user's execution statistics data. */
            $userFirstRow = true;
            foreach($projects as $executions)
            {
                $projectFirstRow = true;
                foreach($executions as $tasks)
                {
                    $execution = current($tasks);
                    $execution->executionTasks = count($tasks);
                    $execution->executionHours = array_sum(array_map(function($task){return $task->left;}, $tasks));
                    $execution->totalTasks     = $totalTasks;
                    $execution->totalHours     = $totalHours;
                    $execution->workload       = $userWorkload;
                    $this->setExecutionName($execution, $canViewExecution);

                    if($userFirstRow)    $execution->userRowspan    = $totalExecutions;
                    if($projectFirstRow) $execution->projectRowspan = count($executions);

                    $workload[] = $execution;

                    $userFirstRow = $projectFirstRow = false;
                }
            }
        }

        return $workload;
    }

    /**
     * 设置执行名称。
     * Set execution name.
     *
     * @param  object $execution
     * @param  bool   $canViewExecution
     * @access public
     * @return void
     */
    public function setExecutionName(object &$execution, bool $canViewExecution): void
    {
        if($execution->multiple)
        {
            $execution->executionName = $canViewExecution ? html::a(helper::createLink('execution', 'view', "executionID={$execution->executionID}"), $execution->executionName) : $execution->executionName;
        }
        else
        {
            $execution->executionName = $this->lang->null;
        }
    }

    /**
     * 获取用户的工作负载。
     * Get user's workload.
     *
     * @param  array   $projects
     * @param  array   $teamTasks
     * @param  float   $allHour
     * @access private
     * @return array
     */
    private function getUserWorkLoad(array $projects, array $teamTasks, float $allHour): array
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
     * 获取指派过的未解决bug。
     * Get assigned unresolved bugs.
     *
     * @access public
     * @return array
     */
    public function getBugAssign(): array
    {
        $bugGroups       = $this->pivotTao->getAssignBugGroup();
        $products        = $this->pivotTao->getAllProductsIDAndName();
        $productProjects = $this->pivotTao->getProductProjects();

        $canViewProduct = common::hasPriv('product', 'view');
        $canViewProject = common::hasPriv('project', 'view');

        $bugs = array();
        foreach($bugGroups as $userBugs)
        {
            $totalBugs = array_sum(array_map(function($bug){return $bug->bugCount;}, $userBugs));

            $first = true;
            foreach($userBugs as $bug)
            {
                if(!isset($products[$bug->product])) continue;

                $bug->productName = $products[$bug->product];
                if($bug->productName)
                {
                    /* 用户有访问权限的情况下，允许用户点击。 */
                    /* Allow users to click if they have access. */
                    if($canViewProject && !empty($productProjects[$bug->product]))
                    {
                        $bug->productName = html::a(helper::createLink('project', 'view', "projectID={$productProjects[$bug->product]}"), $bug->productName);
                    }
                    elseif($canViewProduct)
                    {
                        $bug->productName = html::a(helper::createLink('product', 'view', "product={$bug->product}"), $bug->productName);
                    }
                }
                $bug->total = $totalBugs;

                if($first) $bug->rowspan = count($userBugs);

                $bugs[] = $bug;

                $first = false;
            }
        }

        return $bugs;
    }

    /**
     * Get System URL.
     *
     * @access public
     * @return void
     */
    public function getSysURL()
    {
        if(isset($this->config->mail->domain)) return $this->config->mail->domain;

        /* Ger URL when run in shell. */
        if(PHP_SAPI == 'cli')
        {
            $url = parse_url(trim($this->server->argv[1]));
            $port = (empty($url['port']) or $url['port'] == 80) ? '' : $url['port'];
            $host = empty($port) ? $url['host'] : $url['host'] . ':' . $port;
            return $url['scheme'] . '://' . $host;
        }
        else
        {
            return common::getSysURL();
        }
    }

    /**
     * Get user bugs.
     *
     * @access public
     * @return void
     */
    public function getUserBugs()
    {
        return $this->dao->select('t1.id, t1.title, t2.account as user, t1.deadline')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.assignedTo = t2.account')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.assignedTo')->ne('closed')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deadline', true)->eq('0000-00-00')
            ->orWhere('t1.deadline')->lt(date(DT_DATE1, strtotime('+4 day')))
            ->markRight(1)
            ->fetchGroup('user');
    }

    /**
     * Get user tasks.
     *
     * @access public
     * @return void
     */
    public function getUserTasks()
    {
        return $this->dao->select('t1.id, t1.name, t2.account as user, t1.deadline')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project = t4.id')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('t3.status')->ne('suspended')
            ->andWhere('t1.deadline', true)->eq('0000-00-00')
            ->orWhere('t1.deadline')->lt(date(DT_DATE1, strtotime('+4 day')))
            ->markRight(1)
            ->fetchGroup('user');
    }

    /**
     * Get user todos.
     *
     * @access public
     * @return array
     */
    public function getUserTodos()
    {
        $stmt = $this->dao->select('t1.*, t2.account as user')
            ->from(TABLE_TODO)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.account = t2.account')
            ->where('t1.cycle')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,doing')
            ->query();

        $todos = array();
        while($todo = $stmt->fetch())
        {
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_BUG)->fetch('title');
            $todos[$todo->user][] = $todo;
        }
        return $todos;
    }

    /**
     * Get user testTasks.
     *
     * @access public
     * @return array
     */
    public function getUserTestTasks()
    {
        return $this->dao->select('t1.*, t2.account as user')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.owner = t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere("(t1.status='wait' OR t1.status='doing')")
            ->fetchGroup('user');
    }

    /**
     * Get user login count in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return int
     */
    public function getUserYearLogins($accounts, $year)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where('actor')->in($accounts)->andWhere('LEFT(date, 4)')->eq($year)->andWhere('action')->eq('login')->fetch('count');
    }

    /**
     * Get user action count in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return int
     */
    public function getUserYearActions($accounts, $year)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->fetch('count');
    }

    /**
     * Get user contributions in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearContributions($accounts, $year)
    {
        $stmt = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('objectType')->in(array_keys($this->config->pivot->annualData['contributions']))
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->orderBy('objectType,objectID,id')
            ->query();

        $filterActions = array();
        $objectIdList  = array();
        while($action = $stmt->fetch())
        {
            $objectType  = $action->objectType;
            $objectID    = $action->objectID;
            $lowerAction = strtolower($action->action);
            if(!isset($this->config->pivot->annualData['contributions'][$objectType][$lowerAction])) continue;

            $objectIdList[$objectType][$objectID] = $objectID;
            $filterActions[$objectType][$objectID][$action->id] = $action;
        }

        foreach($objectIdList as $objectType => $idList)
        {
            $deletedIdList = $this->dao->select('id')->from($this->config->objectTables[$objectType])->where('deleted')->eq(1)->andWhere('id')->in($idList)->fetchPairs('id', 'id');
            foreach($deletedIdList as $id) unset($filterActions[$objectType][$id]);
        }

        $actionGroups = array();
        foreach($filterActions as $objectType => $objectActions)
        {
            foreach($objectActions as $objectID => $actions)
            {
                foreach($actions as $action) $actionGroups[$objectType][$action->id] = $action;
            }
        }

        $contributions = array();
        foreach($actionGroups as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $lowerAction = strtolower($action->action);
                $actionName  = $this->config->pivot->annualData['contributions'][$objectType][$lowerAction];

                $type = ($actionName == 'svnCommit' or $actionName == 'gitCommit') ? 'repo' : $objectType;
                if(!isset($contributions[$type][$actionName])) $contributions[$type][$actionName] = 0;
                $contributions[$type][$actionName] += 1;
            }
        }

        $contributions['case']['run'] = $this->dao->select('count(*) as count')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->fetch('count');

        return $contributions;
    }

    /**
     * Get user todo stat in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return object
     */
    public function getUserYearTodos($accounts, $year)
    {
        return $this->dao->select("count(*) as count, sum(if((`status` != 'done'), 1, 0)) AS `undone`, sum(if((`status` = 'done'), 1, 0)) AS `done`")->from(TABLE_TODO)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();
    }

    /**
     * Get user effort stat in this error.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return object
     */
    public function getUserYearEfforts($accounts, $year)
    {
        $effort = $this->dao->select('count(*) as count, sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();

        $effort->consumed = !empty($effort->consumed) ? round($effort->consumed, 2) : 0;
        return $effort;
    }

    /**
     * Get count of created story,plan and closed story by accounts every product in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearProducts($accounts, $year)
    {
        /* Get changed products in this year. */
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(createdDate, 4)')->eq($year)
            ->beginIF($accounts)
            ->andWhere('createdBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        /* Get created plans in this year. */
        $plans = $this->dao->select('t1.id,t1.product')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id=t2.objectID and t2.objectType='productplan'")
            ->where('LEFT(t2.date, 4)')->eq($year)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.product')->in(array_keys($products))
            ->beginIF($accounts)
            ->andWhere('t2.actor')->in($accounts)
            ->fi()
            ->andWhere('t2.action')->eq('opened')
            ->fetchAll();

        $planProducts = array();
        $planGroups   = array();
        foreach($plans as $plan)
        {
            $planProducts[$plan->product] = $plan->product;
            $planGroups[$plan->product][$plan->id] = $plan->id;
        }

        $createStoryProducts = $this->dao->select('DISTINCT product')->from(TABLE_STORY)
            ->where('LEFT(openedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->fetchPairs('product', 'product');
        $closeStoryProducts  = $this->dao->select('DISTINCT product')->from(TABLE_STORY)
            ->where('LEFT(closedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->fetchPairs('product', 'product');
        if($createStoryProducts or $closeStoryProducts)
        {
            $products += $this->dao->select('id,name')->from(TABLE_PRODUCT)
                ->where('id')->in($createStoryProducts + $closeStoryProducts + $planProducts)
                ->andWhere('deleted')->eq(0)
                ->fetchAll('id');
        }

        $createdStoryStats = $this->dao->select("product,sum(if((type = 'requirement'), 1, 0)) as requirement, sum(if((type = 'story'), 1, 0)) as story")->from(TABLE_STORY)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->andWhere('LEFT(openedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

        $closedStoryStats = $this->dao->select("product,sum(if((status = 'closed'), 1, 0)) as closed")->from(TABLE_STORY)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->andWhere('LEFT(closedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

        /* Merge created plan, created story and closed story in every product. */
        foreach($products as $productID => $product)
        {
            $product->plan        = 0;
            $product->requirement = 0;
            $product->story       = 0;
            $product->closed      = 0;

            $plans = zget($planGroups, $productID, array());
            if($plans) $product->plan = count($plans);

            $createdStoryStat = zget($createdStoryStats, $productID, '');
            if($createdStoryStat)
            {
                $product->requirement = $createdStoryStat->requirement;
                $product->story       = $createdStoryStat->story;
            }

            $closedStoryStat = zget($closedStoryStats, $productID, '');
            if($closedStoryStat) $product->closed = $closedStoryStat->closed;
        }

        return $products;
    }

    /**
     * Get count of finished task, story and resolved bug by accounts every executions in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearExecutions($accounts, $year)
    {
        /* Get changed executions in this year. */
        $executions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('deleted')->eq(0)
            ->andwhere('type')->eq('sprint')
            ->andwhere('multiple')->eq('1')
            ->andWhere('LEFT(begin, 4)', true)->eq($year)
            ->orWhere('LEFT(end, 4)')->eq($year)
            ->markRight(1)
            ->beginIF($accounts)
            ->andWhere('openedBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('PM')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->orderBy('`order` desc')
            ->fetchAll('id');

        $teamExecutions = $this->dao->select('t1.root')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.root=t2.id')
            ->where('t1.type')->eq('execution')
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->andWhere('t2.multiple')->eq('1')
            ->andWhere('LEFT(`join`, 4)')->eq($year)
            ->fetchPairs('root', 'root');

        $taskExecutions = $this->dao->select('t1.execution')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('LEFT(t1.finishedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('finishedBy')->in($accounts)->fi()
            ->andWhere('t2.multiple')->eq('1')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs('execution', 'execution');

        if($teamExecutions or $taskExecutions)
        {
            $executions += $this->dao->select('id,name')->from(TABLE_EXECUTION)
                ->where('id')->in($teamExecutions + $taskExecutions)
                ->andWhere('deleted')->eq(0)
                ->andWhere('type')->eq('sprint')
                ->orderBy('`order` desc')
                ->fetchAll('id');
        }

        /* Get count of finished task, story and resolved bug in this year. */
        $taskStats = $this->dao->select('execution, count(*) as finishedTask, sum(if((story != 0), 1, 0)) as finishedStory')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('finishedBy')->ne('')
            ->andWhere('LEFT(finishedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('finishedBy')->in($accounts)->fi()
            ->groupBy('execution')
            ->fetchAll('execution');
        $resolvedBugs = $this->dao->select('t2.execution, count(*) as count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.resolvedBuild=t2.id')
            ->where('t2.execution')->in(array_keys($executions))
            ->andWhere('t1.resolvedBy')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('LEFT(t1.resolvedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.resolvedBy')->in($accounts)->fi()
            ->groupBy('t2.execution')
            ->fetchAll('execution');

        foreach($executions as $executionID => $execution)
        {
            $execution->task  = 0;
            $execution->story = 0;
            $execution->bug   = 0;

            $taskStat = zget($taskStats, $executionID, '');
            if($taskStat)
            {
                $execution->task  = $taskStat->finishedTask;
                $execution->story = $taskStat->finishedStory;
            }

            $resolvedBug = zget($resolvedBugs, $executionID, '');
            if($resolvedBug) $execution->bug = $resolvedBug->count;
        }

        return $executions;
    }

    /**
     * Get status stat that is all time, include story, task and bug.
     *
     * @access public
     * @return array
     */
    public function getAllTimeStatusStat()
    {
        $statusStat = array();
        $statusStat['story'] = $this->dao->select('status, count(status) as count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('type')->eq('story')->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['task']  = $this->dao->select('status, count(status) as count')->from(TABLE_TASK)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['bug']   = $this->dao->select('status, count(status) as count')->from(TABLE_BUG)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');

        return $statusStat;
    }

    /**
     * Get year object stat, include status and action stat
     *
     * @param  array  $accounts
     * @param  string $year
     * @param  string $objectType   story|task|bug
     * @access public
     * @return array
     */
    public function getYearObjectStat($accounts, $year, $objectType)
    {
        $table = '';
        if($objectType == 'story') $table = TABLE_STORY;
        if($objectType == 'task')  $table = TABLE_TASK;
        if($objectType == 'bug')   $table = TABLE_BUG;
        if(empty($table)) return array();

        $months = $this->getYearMonths($year);
        $stmt   = $this->dao->select('t1.*, t2.status')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin($table)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq($objectType)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->in($this->config->pivot->annualData['monthAction'][$objectType])
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();

        /* Build object action stat and get status group. */
        $statuses   = array();
        $actionStat = array();
        while($action = $stmt->fetch())
        {
            $statuses[$action->objectID] = $action->status;

            $lowerAction = strtolower($action->action);
            /* Story,bug can from feedback and ticket, task can from feedback, boil this action down to opened. */
            if(in_array($lowerAction, array('fromfeedback', 'fromticket'))) $lowerAction = 'opened';
            if(!isset($actionStat[$lowerAction]))
            {
                foreach($months as $month) $actionStat[$lowerAction][$month] = 0;
            }

            $month = substr($action->date, 0, 7);
            $actionStat[$lowerAction][$month] += 1;
        }

        /* Build status stat. */
        $statusStat = array();
        foreach($statuses as $status)
        {
            if(!isset($statusStat[$status])) $statusStat[$status] = 0;
            $statusStat[$status] += 1;
        }

        return array('statusStat' => $statusStat, 'actionStat' => $actionStat);
    }

    /**
     * Get year case stat, include result and action stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearCaseStat($accounts, $year)
    {
        $months = $this->getYearMonths($year);
        $stmt   = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('case')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();

        /* Build create case stat. */
        $resultStat = array();
        $actionStat = array();
        foreach($months as $month)
        {
            $actionStat['opened'][$month]    = 0;
            $actionStat['run'][$month]       = 0;
            $actionStat['createBug'][$month] = 0;
        }

        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['opened'][$month] += 1;
        }

        /* Build testcase result stat and run case stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->query();
        while($testResult = $stmt->fetch())
        {
            if(!isset($resultStat[$testResult->caseResult])) $resultStat[$testResult->caseResult] = 0;
            $resultStat[$testResult->caseResult] += 1;

            $month = substr($testResult->date, 0, 7);
            $actionStat['run'][$month] += 1;
        }

        /* Build testcase create bug stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('t2.case')->ne('0')
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['createBug'][$month] += 1;
        }

        return array('resultStat' => $resultStat, 'actionStat' => $actionStat);
    }

    /**
     * Get year months.
     *
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearMonths($year)
    {
        $months = array();
        for($i = 1; $i <= 12; $i ++) $months[] = $year . '-' . sprintf('%02d', $i);

        return $months;
    }

    /**
     * Get project and execution name.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutions()
    {
        $executions = $this->dao->select('t1.id, t1.name, t2.name as projectname, t1.status, t1.multiple')
            ->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in('stage,sprint')
            ->fetchAll();

        $pairs = array();
        foreach($executions as $execution)
        {
            if($execution->multiple)  $pairs[$execution->id] = $execution->projectname . '/' . $execution->name;
            if(!$execution->multiple) $pairs[$execution->id] = $execution->projectname;
        }

        return $pairs;
    }

    /**
     * Get filter format.
     *
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return string
     */
    public function getFilterFormat($sql, $filters)
    {
        $filterFormat = array();
        if(empty($filters)) return array($sql, $filterFormat);

        foreach($filters as $filter)
        {
            $field = $filter['field'];

            if(isset($filter['from']) and $filter['from'] == 'query')
            {
                $queryDefault = isset($filter['default']) ? $this->processDateVar($filter['default']) : '';
                $sql          = str_replace('$' . $filter['field'], "'{$queryDefault}'", $sql);
            }
            else
            {
                if(!isset($filter['default'])) continue;

                $default = $filter['default'];
                switch($filter['type'])
                {
                    case 'select':
                        if(empty($default)) break;
                        if(is_array($default)) $default = array_filter($default, function($val){return trim($val) != '';});
                        $value = "('" . implode("', '", $default) . "')";
                        $filterFormat[$field] = array('operator' => 'IN', 'value' => $value);
                        break;
                    case 'input':
                        $filterFormat[$field] = array('operator' => 'like', 'value' => "'%$default%'");
                        break;
                    case 'date':
                    case 'datetime':
                        $begin = $default['begin'];
                        $end   = $default['end'];

                        if(!empty($begin) and empty($end))  $filterFormat[$field] = array('operator' => '>', 'value' => "'$begin'");
                        if(empty($begin) and !empty($end))  $filterFormat[$field] = array('operator' => '<', 'value' => "'$end'");
                        if(!empty($begin) and !empty($end)) $filterFormat[$field] = array('operator' => 'BETWEEN', 'value' => "'$begin' and '$end'");
                        break;
                }
            }
        }
        return array($sql, $filterFormat);
    }

    /**
     * Gen sheet.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $sql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genSheet($fields, $settings, $sql, $filters, $langs = array())
    {
        $groups    = array();
        $sqlGroups = array();

        foreach($settings as $key => $value)
        {
            if(strpos($key, 'group') !== false && $value) $groups[] = $value;
        }

        $groups = array_unique($groups);

        /* Add tt for sql. */
        foreach($groups as $key => $group) $sqlGroups[$key] = (!empty($settings['filterType']) and $settings['filterType'] == 'query') ? "`$group`" : "tt.`$group`";

        $groupList = implode(',', $sqlGroups);

        $cols       = array();
        $rows       = array();
        $configs    = array();
        $slices     = array();
        $clientLang = $this->app->getClientLang();
        /* Build cols. */
        foreach($groups as $group)
        {
            $col = new stdclass();
            $col->name    = $group;
            $col->isGroup = true;

            $fieldObject  = $fields[$group]['object'];
            $relatedField = $fields[$group]['field'];

            $colLabel = $group;
            if($fieldObject)
            {
                $this->app->loadLang($fieldObject);
                if(isset($this->lang->$fieldObject->$relatedField)) $colLabel = $this->lang->$fieldObject->$relatedField;
            }

            if(isset($langs[$group]) and !empty($langs[$group][$clientLang])) $colLabel = $langs[$group][$clientLang];
            $col->label = $colLabel;

            $cols[0][] = $col;
        }

        /* Replace the variable with the default value. */
        $sql = $this->initVarFilter($filters, $sql);

        /* Create sql. */
        $sql = str_replace(';', '', $sql);

        if(preg_match_all("/[\$]+[a-zA-Z0-9]+/", $sql, $out))
        {
            foreach($out[0] as $match) $sql = str_replace($match, "''", $sql);
        }

        /* Process rows. */
        $connectSQL = '';
        if(!empty($filters) && !isset($filters[0]['from']))
        {
            $wheres = array();
            foreach($filters as $field => $filter)
            {
                $wheres[] = "tt.`$field` {$filter['operator']} {$filter['value']}";
            }

            $whereStr    = implode(' and ', $wheres);
            $connectSQL .= " where $whereStr";
        }

        $groupSQL = " group by $groupList";
        $orderSQL = " order by $groupList";

        $number       = 0;
        $groupsRow    = array();
        $showColTotal = zget($settings, 'columnTotal', 'noShow');
        $showOrigin   = false;

        if(isset($settings['columns']))
        {
            foreach($settings['columns'] as $column)
            {
                if(isset($column['showOrigin']) and $column['showOrigin']) $showOrigin = true;
            }

            foreach($settings['columns'] as $column)
            {
                $columnShowOrigin = zget($column, 'showOrigin', false);
                if($columnShowOrigin) $column['slice'] = 'noSlice';

                $stat   = $column['stat'];
                $field  = $column['field'];
                $slice  = zget($column, 'slice', 'noSlice');
                $uuName = $field . $number;
                $number ++;

                if($columnShowOrigin)
                {
                    $columnSQL = "select $groupList, tt.`$field` from ($sql) tt" . $connectSQL . $orderSQL;
                }
                else
                {
                    if($stat == 'distinct')
                    {
                        $columnSQL = "count(distinct tt.`$field`) as `$uuName`";
                    }
                    else
                    {
                        if($fields[$field]['type'] != 'number' and in_array($stat, array('avg', 'sum')))
                        {
                            $convertSql = $this->config->db->driver == 'mysql' ? "CAST(tt.`$field` AS DECIMAL(32, 2))" : "TO_DECIMAL(tt.`$field`)";
                            $columnSQL  = "$stat($convertSql) as `$uuName`";
                        }
                        else
                        {
                            $columnSQL = "$stat(tt.`$field`) as `$uuName`";
                        }
                    }

                    if($slice != 'noSlice') $columnSQL = "select $groupList,`$slice`,$columnSQL from ($sql) tt" . $connectSQL . $groupSQL . ",tt.`$slice`" . $orderSQL . ",tt.`$slice`";
                    if($slice == 'noSlice') $columnSQL = "select $groupList,$columnSQL from ($sql) tt" . $connectSQL . $groupSQL . $orderSQL;
                }

                $columnRows = $this->dao->query($columnSQL)->fetchAll();

                $rowcount = array_fill(0, count($columnRows), 1);
                if($showOrigin && !$columnShowOrigin)
                {
                    $countSQL = "select $groupList, count(tt.`$field`) as rowCount from ($sql) tt" . $connectSQL . $groupSQL . $orderSQL;
                    $countRows = $this->dao->query($countSQL)->fetchAll();
                    foreach($countRows as $key => $countRow) $rowcount[$key] = $countRow->rowCount;
                }

                $cols = $this->getTableHeader($columnRows, $column, $fields, $cols, $sql, $langs, $columnShowOrigin);
                if($slice != 'noSlice') $columnRows = $this->processSliceData($columnRows, $groups, $slice, $uuName);
                $columnRows = $this->processShowData($columnRows, $groups, $column, $showColTotal, $uuName);

                $rowIndex = 0;
                foreach($columnRows as $key => $row)
                {
                    $count = isset($rowcount[$key]) ? $rowcount[$key] : 1;
                    for($i = 0; $i < $count; $i++)
                    {
                        if(!isset($groupsRow[$rowIndex])) $groupsRow[$rowIndex] = new stdclass();
                        $groupsRow[$rowIndex] = (object)array_merge((array)$groupsRow[$rowIndex], (array)$row);
                        $rowIndex += 1;
                    }
                }
            }
        }

        /* Get configs. */
        foreach($groups as $index => $group)
        {
            if($index == 0)
            {
                $groupRows = array();
                foreach($groupsRow as $row) $groupRows[$row->$group][] = $row;
            }

            $haveNext = isset($groups[$index + 1]);
            $this->getColumnConfig($groupRows, $configs, $groups, $index, $haveNext);
        }

        /* Get group field lang */
        foreach($groups as $group)
        {
            $options = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field'], $sql);
            foreach($groupsRow as $row)
            {
                if(isset($row->$group)) $row->$group = zget($options, $row->$group);
            }
        }

        $data              = new stdclass();
        $data->groups      = $groups;
        $data->cols        = $cols;
        $data->array       = json_decode(json_encode($groupsRow), true);
        $data->columnTotal = isset($settings['columnTotal']) ? $settings['columnTotal'] : '';

        /* $data->groups  array 代表分组，最多三个
         * $data->cols    array thead数据，其中对象有三个属性：name：分组，label：列的名字，isGroup：标识是不是分组
         * $data->arrays  array tbody数据, 其中每一个数组内是一行td的数据
         *
         * $configs, eg: array(0 => array(0 => 2, 1 => 1), 2 => array(0 => 2))
         * 代表在整个tbody中，位于[0,0]坐标的td rowspan为2，位于[0,1]坐标的td rowspan为1, 位于[2,0]坐标的td rowspan为2
         */
        return array($data, $configs);
    }

    /**
     * InitVarFilter.
     *
     * @param  string $filters
     * @param  string $sql
     * @access public
     * @return void
     */
    public function initVarFilter($filters = '', $sql = '')
    {
        if(empty($filters)) return $sql;
        foreach($filters as $filter)
        {
            if(empty($filter['from'])) continue;
            $default = isset($filter['default']) ? $filter['default'] : '';
            if(is_array($default))
            {
                $default = array_filter($default, function($val){return !empty($val);});
                $default = implode("', '", $default);
            }
            $sql  = str_replace('$' . $filter['field'], "'{$default}'", $sql);
        }

        if(preg_match_all("/[\$]+[a-zA-Z0-9]+/", $sql, $out))
        {
            foreach($out[0] as $match) $sql = str_replace($match, "''", $sql);
        }
        return $sql;
    }

    /**
     * Get column config to merge table cell.
     *
     * @param  array $groupRows
     * @param  array $configs
     * @param  array $groups
     * @param  int   $index
     * @access public
     * @return void
     *
     * Init $groupRows like this: array('people1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2' => ['create' => 'people2', 'product' => 'product2']])
     * The second time the function is executed, groupRows is passed in array('people1_product1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2_product2' => ['create' => 'people2', 'product' => 'product2']])
     *
     * The key value of this array is unique;
     */
    public function getColumnConfig(&$groupRows, &$configs, $groups, $index, $haveNext)
    {
        $newRows = array();
        $start = 1;
        $next  = $index + 1;
        foreach($groupRows as $key => $datas)
        {
            $number = count($datas);

            $configs[$start - 1][$next - 1] = $number;
            $start += $number;

            if($haveNext)
            {
                $nextGroup = $groups[$next];
                foreach($datas as $data)
                {
                    $newKey = $key . '_' . $data->$nextGroup;
                    $newRows[$newKey][] = $data;
                }
            }
        }

        if($haveNext) $groupRows = $newRows;
    }

    /**
     * Get the header of the table.
     *
     * @param  array  $columnRows
     * @param  array  $column
     * @param  array  $fields
     * @param  array  $cols
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function getTableHeader($columnRows, $column, $fields, $cols, $sql, $langs = array(), $showOrigin = false)
    {
        $stat       = zget($column, 'stat', '');
        $showMode   = zget($column, 'showMode', 'default');
        $monopolize = $showMode == 'default' ? '' : zget($column, 'monopolize', '');

        $col = new stdclass();
        $col->name       = $column['field'];
        $col->isGroup    = false;
        $col->showOrigin = $showOrigin;

        $fieldObject  = $fields[$column['field']]['object'];
        $relatedField = $fields[$column['field']]['field'];

        $colLabel = $column['field'];
        if($fieldObject)
        {
            $this->app->loadLang($fieldObject);
            if(isset($this->lang->$fieldObject->$relatedField)) $colLabel = $this->lang->$fieldObject->$relatedField;
        }

        $clientLang = $this->app->getClientLang();
        if(isset($langs[$column['field']]) and !empty($langs[$column['field']][$clientLang])) $colLabel = $langs[$column['field']][$clientLang];

        if(!$showOrigin)
        {
            $colLabel = str_replace('{$field}', $colLabel, $this->lang->pivot->colLabel);
            $colLabel = str_replace('{$stat}', zget($this->lang->pivot->step2->statList, $stat), $colLabel);
            if($showMode != 'default') $colLabel .= sprintf($this->lang->pivot->colShowMode, zget($this->lang->pivot->step2->showModeList, $showMode));
        }
        $col->label = $colLabel;

        $slice = zget($column, 'slice', 'noSlice');
        if($slice != 'noSlice')
        {
            if(!isset($cols[1]))
            {
                foreach($cols[0] as $colData) $colData->rowspan = '2';
                $cols[1] = array();
            }
            $sliceList = array();
            foreach($columnRows as $rows) $sliceList[$rows->{$slice}] = $rows->{$slice};

            $optionList = $this->getSysOptions($fields[$slice]['type'], $fields[$slice]['object'], $fields[$slice]['field'], $sql);
            foreach($sliceList as $field)
            {
                $childCol = new stdclass();
                $childCol->name    = $field;
                $childCol->isGroup = false;
                $childCol->label   = isset($optionList[$field]) ? $optionList[$field] : $field;
                $childCol->colspan = $monopolize ? 2 : 1;
                $cols[1][] = $childCol;
            }
            $col->colspan = count($sliceList);
            if($monopolize) $col->colspan *= 2;

            if(zget($column, 'showTotal', 'noShow') !== 'noShow')
            {
                $childCol = new stdclass();
                $childCol->name    = 'sum';
                $childCol->isGroup = false;
                $childCol->label   = $this->lang->pivot->step2->total;
                $childCol->colspan = $monopolize ? 2 : 1;
                $cols[1][] = $childCol;
                $col->colspan += $childCol->colspan;
            }

            $cols[0][] = $col;
        }
        else
        {
            $col->rowspan = !isset($cols[1]) ? '1' : '2';
            $col->colspan = $monopolize ? 2 : 1;
            $cols[0][] = $col;
        }

        return $cols;
    }

    /**
     * Process column show mode.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  array   $column
     * @param  string  $showColTotal
     * @param  int     $uuName
     * @access public
     * @return array
     */
    public function processShowData($columnRows, $groups, $column, $showColTotal, $uuName)
    {
        $slice      = zget($column, 'slice', 'noSlice');
        $showMode   = zget($column, 'showMode', 'default');
        $showTotal  = $slice == 'noSlice' ? 'noShow' : zget($column, 'showTotal', 'noShow');
        $monopolize = $showMode == 'default' ? '' : zget($column, 'monopolize', '');

        $colTotal = array();
        $rowTotal = array();
        $allTotal = 0;
        foreach($columnRows as $index => $row)
        {
            if(!isset($rowTotal[$index])) $rowTotal[$index] = 0;

            foreach($row as $field => $value)
            {
                if(in_array($field, $groups)) continue;
                if(!isset($colTotal[$field])) $colTotal[$field] = 0;

                if($monopolize)
                {
                    if(!isset($colTotal["self_$field"])) $colTotal["self_$field"] = 0;
                    $colTotal["self_$field"] += (float)$value;
                }

                $colTotal[$field] += (float)$value;
                $rowTotal[$index] += (float)$value;
                $allTotal += (float)$value;
            }
        }

        if($showMode == 'total')
        {
            foreach($columnRows as $index => $row)
            {
                $columnRow = new stdclass();
                foreach($row as $field => $value)
                {
                    if(in_array($field, $groups))
                    {
                        $columnRow->$field = $value;
                        continue;
                    }
                    if($monopolize) $columnRow->{"self_$field"} = $value;
                    $columnRow->{$field} = round((float)$value / $allTotal * 100, 2) . '%';
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{"sum_$uuName"} = round((float)$rowTotal[$index] / $allTotal * 100, 2) . '%';
                }
                $columnRows[$index] = $columnRow;
            }
        }

        if($showMode == 'row')
        {
            foreach($columnRows as $index => $row)
            {
                $columnRow = new stdclass();
                foreach($row as $field => $value)
                {
                    if(in_array($field, $groups))
                    {
                        $columnRow->$field = $value;
                        continue;
                    }
                    if($monopolize) $columnRow->{"self_$field"} = $value;
                    $columnRow->{$field} = round((float)$value / $rowTotal[$index] * 100, 2) . '%';
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{'sum_' . $uuName} = round((float)$rowTotal[$index] / $rowTotal[$index] * 100, 2) . '%';
                }
                $columnRows[$index] = $columnRow;
            }
        }

        if($showMode == 'column')
        {
            foreach($columnRows as $index => $row)
            {
                $columnRow = new stdclass();
                foreach($row as $field => $value)
                {
                    if(in_array($field, $groups))
                    {
                        $columnRow->$field = $value;
                        continue;
                    }
                    if($monopolize) $columnRow->{"self_$field"} = $value;
                    $columnRow->{$field} = round((float)$value / $colTotal[$field] * 100, 2) . '%';
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{'sum_' . $uuName} = round((float)$rowTotal[$index] / $allTotal * 100, 2) . '%';
                }
                $columnRows[$index] = $columnRow;
            }
        }

        if($showMode == 'default' and $showTotal == 'sum')
        {
            foreach($columnRows as $index => $row) $row->{'sum_' . $uuName} = $rowTotal[$index];
        }

        if($showColTotal == 'sum')
        {
            if(empty($columnRows)) return $columnRows;

            $colTotalRow = new stdClass();
            foreach($columnRows[0] as $field => $value)
            {
                if(in_array($field, $groups))
                {
                    $colTotalRow->$field = $this->lang->pivot->step2->total;
                }
                else
                {
                    if($showTotal == 'sum' and $field == "sum_$uuName")
                    {
                        if($showMode == 'default')
                        {
                            $colTotalRow->{$field} = $allTotal;
                        }
                        else
                        {
                            $colTotalRow->{$field} = round((float)$allTotal / $allTotal * 100, 2) . '%';
                        }
                        continue;
                    }

                    if(strpos($field, 'sum_self_') !== false)
                    {
                        $colTotalRow->{$field} = $allTotal;
                        continue;
                    }

                    if(strpos($field, 'self_') !== false)
                    {
                        $colTotalRow->{$field} = $colTotal[$field];
                        continue;
                    }

                    if($showMode == 'default') $colTotalRow->$field = $colTotal[$field];
                    if($showMode == 'column')  $colTotalRow->$field = round((float)$colTotal[$field] / $colTotal[$field] * 100, 2) . '%';
                    if(strpos(',total,row,', ",$showMode,") !== false) $colTotalRow->$field = round((float)$colTotal[$field] / $allTotal * 100, 2) . '%';
                }
            }
            $columnRows[] = $colTotalRow;
        }

        return $columnRows;
    }

    /**
     * Process data as slice table data.
     *
     * @param  array  $columnRows
     * @param  array  $groups
     * @param  string $slice
     * @param  string $uuName
     * @access public
     * @return array
     */
    public function processSliceData($columnRows, $groups, $slice, $uuName)
    {
        $sliceList = array();
        foreach($columnRows as $rows) $sliceList[$rows->{$slice}] = $rows->{$slice};

        $index     = 0;
        $sliceRows = array();
        foreach($columnRows as $key => $columnRow)
        {
            $field = $columnRow->$slice . '_slice_' . $uuName;
            $columnRow->$field = $columnRow->$uuName;
            if(!in_array($slice, $groups)) unset($columnRow->{$slice});
            unset($columnRow->{$uuName});

            if($key == 0)
            {
                $index             = $key;
                $sliceRows[$index] = $columnRow;
                continue;
            }
            $sliceFlag = true;
            foreach($groups as $group)
            {
                if($columnRow->{$group} != $sliceRows[$index]->{$group}) $sliceFlag = false;
            }
            if(!$sliceFlag)
            {
                $index ++;
                $sliceRows[$index] = $columnRow;
            }
            else
            {
                $sliceRows[$index]->{$field} = $columnRow->{$field};
            }
        }

        foreach($sliceRows as $key => $row)
        {
            $sliceRow = array();
            foreach($row as $field => $value)
            {
                if(strpos($field, '_slice_' . $uuName) === false) $sliceRow[$field] = $value;
            }
            foreach($sliceList as $field)
            {
                $field = $field . '_slice_' . $uuName;
                $sliceRow[$field] = !empty($row->{$field}) ? $row->{$field} : '';
            }
            $sliceRows[$key] = (object)$sliceRow;
        }

        return $sliceRows;
    }

    /**
     * Get sys options.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function getSysOptions($type, $object = '', $field = '', $sql = '')
    {
        $options = array();
        switch($type)
        {
            case 'user':
                $options = $this->loadModel('user')->getPairs('noletter');
                break;
            case 'product':
                $options = $this->loadModel('product')->getPairs();
                break;
            case 'project':
                $options = $this->loadModel('project')->getPairsByProgram();
                break;
            case 'execution':
                $options = $this->loadModel('execution')->getPairs();
                break;
            case 'dept':
                $options = $this->loadModel('dept')->getOptionMenu(0);
                break;
            case 'project.status':
                $this->app->loadLang('project');
                $options = $this->lang->project->statusList;
                break;
            case 'option':
                if($field)
                {
                    $path = $this->app->getModuleRoot() . 'dataview' . DS . 'table' . DS . "$object.php";
                    if(is_file($path))
                    {
                        include $path;
                        $options = $schema->fields[$field]['options'];
                    }
                }
                break;
            case 'object':
                if($field)
                {
                    $table = zget($this->config->objectTables, $object, '');
                    if($table) $options = $this->dao->select("id, {$field}")->from($table)->fetchPairs();
                }
                break;
            case 'string':
                if($field)
                {
                    $options = array();
                    if($sql)
                    {
                        $cols = $this->dbh->query($sql)->fetchAll();
                        foreach($cols as $col)
                        {
                            $data = $col->$field;
                            $options[$data] = $data;
                        }
                    }
                }
                break;
        }
        return $options;
    }

    /**
     * Get tree of pivots and groups.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPreviewPivots($dimensionID, $groupID, $pivotID = 0, $orderBy = 'id_desc')
    {
        if(!$groupID) return array(array(), array(), $groupID);

        $group = $this->loadModel('tree')->getByID($groupID);
        if(empty($group)) return array(array(), array(), $groupID);

        $groups = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('pivot')
            ->beginIF($group->grade == '1')->andWhere('path')->like("%,$group->id,%")->fi()
            ->beginIF($group->grade == '2')->andWhere('path')->like("%,$group->parent,%")->fi()
            ->orderBy('`order`')
            ->fetchPairs();

        if(empty($groups)) return array(array(), array(), $groupID);

        $pivotGroups = array();
        foreach($groups as $id => $groupName)
        {
            $pivotGroups[$id] = $this->dao->select('*')->from(TABLE_PIVOT)
                ->where('deleted')->eq(0)
                ->andWhere("FIND_IN_SET($id, `group`)")
                ->andWhere('stage')->ne('draft')
                ->orderBy($orderBy)
                ->fetchAll();
        }

        if(!$pivotGroups) return array(array(), array(), $groupID);

        $pivotTree = '';
        foreach($pivotGroups as $id => $pivots)
        {
            if(!$pivots) continue;
            $pivots     = $this->processPivot($pivots, false);

            $groupName  = zget($groups, $id, $id);
            $title      = "title='{$groupName}'";
            $pivotTree .= "<li class='closed' $title><a>$groupName</a>";
            $pivotTree .= "<ul>";

            foreach($pivots as $pivot)
            {
                $className  = "pivot-{$id}-{$pivot->id}";
                $params     = helper::safe64Encode("pivotID=$pivot->id");
                $linkHtml   = html::a(helper::createLink('pivot', 'preview', "dimensionID=$dimensionID&group=$id&module=pivot&medhot=show&params=$params"), $pivot->name, '_self', "id='module{$pivot->id}' title='{$pivot->name}'");
                $pivotTree .= "<li class='$className'>$linkHtml</li>";
                if(!$pivotID)
                {
                    $pivotID = $pivot->id;
                    $groupID = $id;
                }
            }
            $pivotTree .= "</ul></li>";
        }

        if($pivotTree) $pivotTree = "<ul id='pivotGroups' class='tree' data-ride='tree'>$pivotTree</ul>";

        $pivot = $pivotID ? $this->getByID($pivotID) : array();

        return array($pivotTree, $pivot, $groupID);
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object $pivot
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($pivot, $action)
    {
        if($pivot->builtin) return false;
        return true;
    }

    /**
     * Build table use data and rowspan.
     *
     * @param  object $data
     * @param  array  $configs
     * @param  array  $fields
     * @access public
     * @return void
     *
     */
    public function buildPivotTable($data, $configs, $fields = array(), $sql = '')
    {
        $clientLang  = $this->app->getClientLang();
        $width       = 128;

        /* Init table. */
        $table  = "<table class='reportData table table-condensed table-striped table-bordered table-fixed datatable' style='width: auto; min-width: 100%' data-fixed-left-width='400'>";

        $showOrigins = array();
        $hasShowOrigin = false;

        foreach($data->cols[0] as $col)
        {
            $colspan = zget($col, 'colspan', 1);
            $colShowOrigin = array_fill(0, $colspan, $col->showOrigin);
            $showOrigins = array_merge($showOrigins, $colShowOrigin);
            if($col->showOrigin) $hasShowOrigin = true;
        }


        /* Init table thead. */
        $table .= "<thead>";
        foreach($data->cols as $lineCols)
        {
            $table .= "<tr>";
            foreach($lineCols as $col)
            {
                $isGroup = $col->isGroup;
                $thName  = $col->label;
                $colspan = zget($col, 'colspan', 1);
                $rowspan = zget($col, 'rowspan', 1);

                if($isGroup) $thHtml = "<th data-flex='false' rowspan='$rowspan' colspan='$colspan' data-width='auto' class='text-center'>$thName</th>";
                else         $thHtml = "<th data-flex='true' rowspan='$rowspan' colspan='$colspan' data-type='number' data-width=$width class='text-center'>$thName</th>";

                $table .= $thHtml;
            }
            $table .= "</tr>";
        }
        $table .= "</thead>";

        /* Init table tbody. */
        $table .= "<tbody>";
        $rowCount = 0;
        for($i = 0; $i < count($data->array); $i ++)
        {
            $rowCount ++;
            if(!empty($data->columnTotal) and $data->columnTotal === 'sum' and $rowCount == count($data->array)) continue;

            $line   = array_values($data->array[$i]);
            $table .= "<tr class='text-center'>";
            for($j = 0; $j < count($line); $j ++)
            {
                $isGroup = !empty($data->cols[0][$j]) ? $data->cols[0][$j]->isGroup : false;
                $rowspan = isset($configs[$i][$j]) ? $configs[$i][$j] : 1;
                $hidden  = isset($configs[$i][$j]) ? false : (!$isGroup ? false : true);

                $showOrigin = $showOrigins[$j];
                if($hasShowOrigin && !$isGroup && !$showOrigin)
                {
                    $rowspan = isset($configs[$i]) ? end($configs[$i]) : 1;
                    $hidden  = isset($configs[$i]) ? false : true;
                }

                $lineValue = $line[$j];
                if($isGroup)
                {
                    $groupName = $data->cols[0][$j]->name;
                }
                if(is_numeric($lineValue)) $lineValue = round($lineValue, 2);

                if(!$hidden) $table .= "<td rowspan='$rowspan'>$lineValue</td>";
            }
            $table .= "</tr>";
        }

        /* Add column total. */
        if(!empty($data->columnTotal) and $data->columnTotal === 'sum' and !empty($data->array))
        {
            $table .= "<tr class='text-center'>";
            $table .= "<td colspan='" . count($data->groups) . "'>{$this->lang->pivot->step2->total}</td>";
            foreach(end($data->array) as $field => $total)
            {
                if(in_array($field, $data->groups)) continue;
                if(is_numeric($total)) $total = round($total, 2);
                $table .= "<td>$total</td>";
            }
            $table .= "</tr>";
        }

        $table .= "</tbody>";
        $table .= "</table>";

        echo $table;
    }

    /**
     * replace defined table names.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function replaceTableNames($sql)
    {
        if(preg_match_all("/TABLE_[A-Z]+/", $sql, $out))
        {
            rsort($out[0]);
            foreach($out[0] as $table)
            {
                if(!defined($table)) continue;
                $sql = str_replace($table, trim(constant($table), '`'), $sql);
            }
        }
        $sql = preg_replace("/= *'\!/U", "!='", $sql);
        return $sql;
    }

    /**
     * 设置默认的过滤器。
     * Set default filter.
     *
     * @param  array   $filters
     * @access private
     * @return void
     */
    private function setFilterDefault(array &$filters): void
    {
        foreach($filters as &$filter)
        {
            if(empty($filter['default'])) continue;
            if(is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }
    }

    /**
     * 从透视表对象中获取字段。
     * Get fields from pivot object.
     *
     * @param  object  $pivot
     * @param  string  $key
     * @param  mixed   $default
     * @param  bool    $jsonDecode
     * @param  bool    $needArray
     * @access private
     * @return mixed
     */
    private function getFieldsFromPivot(object $pivot, string $key, mixed $default, bool $jsonDecode = false, bool $needArray = false)
    {
        return isset($pivot->{$key}) && !empty($pivot->{$key}) ? ($jsonDecode ? json_decode($pivot->{$key}, $needArray) : $pivot->{$key}) : $default;
    }
}

/**
 * Sort summary
 *
 * @param  array  $pre
 * @param  array  $next
 * @access public
 * @return mixed
 */
function sortSummary($pre, $next)
{
    if($pre['validRate'] == $next['validRate']) return 0;
    return $pre['validRate'] > $next['validRate'] ? -1 : 1;
}
