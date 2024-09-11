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
     * 初始化，加载BI相关类。
     * Construct,load BI related classes.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadBIDAO();
        $this->loadModel('bi');
        $this->viewableObjects = $this->bi->getViewableObject('pivot');
    }

    /**
     * 过滤不可见的透视表。
     * Filter invisible pivot.
     *
     * @param  array  $pivots
     * @access public
     * @return array
     */
    public function filterInvisiblePivot($pivots)
    {
        foreach($pivots as $index => $pivot)
        {
            if(!in_array($pivot->id, $this->viewableObjects)) unset($pivots[$index]);
        }

        return array_values($pivots);
    }

    /*
     * 获取透视表。
     * Get pivot.
     *
     * @param  int         $pivotID
     * @param  bool        $processDateVar
     * @access public
     * @return object|bool
     */
    public function getByID(int $pivotID, bool $processDateVar = false, string $filterStatus = 'published'): object|bool
    {
        $pivot = $this->dao->select('*')->from(TABLE_PIVOT)
            ->where('id')->eq($pivotID)
            ->andWhere('deleted')->eq('0')
            ->fetch();
        if(!$pivot) return false;

        $pivot->fieldSettings = array();
        if(!empty($pivot->fields) && $pivot->fields != 'null')
        {
            $pivot->fieldSettings = json_decode($pivot->fields);
            $pivot->fields        = array_keys(get_object_vars($pivot->fieldSettings));
        }

        $pivotFilters = array();
        if(!empty($pivot->filters))
        {
            $filters = json_decode($pivot->filters, true);
            $filters = $this->processFilters($filters, $filterStatus);

            $pivotFilters = $this->setFilterDefault($filters, $processDateVar);
        }

        $pivot->filters = $pivotFilters;

        $pivot = $this->processPivot($pivot);
        if(isset($pivot->stage) && $pivot->stage == 'published' && $this->app->methodName == 'preview') $this->processFieldSettings($pivot);

        return $pivot;
    }

    /**
     * 时间占位符替换为实际的时间。
     * Replace time placeholder with actual time.
     *
     * @param  mixed  $var
     * @param  string $type
     * @access public
     * @return string
     */
    public function processDateVar(mixed $var, string $type = 'date'): string
    {
        if(!is_string($var) || empty($var)) return '';

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
     * Process pivot information.
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
        foreach($pivots as $pivot)
        {
            $this->completePivot($pivot, $screenList);
            if($isObject) $this->addDrills($pivot);
        }

        return $isObject ? $pivot : $pivots;
    }

    /**
     * 完善透视表。
     * Complete pivot.
     *
     * @param  object $pivot
     * @param  array  $screenList
     * @access public
     * @return void
     */
    private function completePivot(object $pivot, array $screenList): void
    {
        if(!empty($pivot->sql))      $pivot->sql      = trim(str_replace(';', '', $pivot->sql));
        if(!empty($pivot->settings)) $pivot->settings = json_decode($pivot->settings, true);

        if(empty($pivot->type))
        {
            $pivot->names = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');
            $pivot->descs = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');
            if(!empty($pivot->name))
            {
                $pivotNames   = json_decode($pivot->name, true);
                $pivot->name  = zget($pivotNames, $this->app->getClientLang(), '') ? : reset(array_filter($pivotNames));
                $pivot->names = $pivotNames;
            }

            if(!empty($pivot->desc))
            {
                $pivotDescs   = json_decode($pivot->desc, true);
                $pivot->desc  = zget($pivotDescs, $this->app->getClientLang(), '');
                $pivot->descs = $pivotDescs;
            }

            $pivot->used = $this->checkIFChartInUse($pivot->id, 'pivot', $screenList);
        }
    }

    /**
     * 添加下钻信息到透视表。
     * Add drills to pivot.
     *
     * @param  object $pivot
     * @access private
     * @return void
     */
    private function addDrills(object $pivot): void
    {
        $settings = $pivot->settings;
        if(!is_array($settings) || !isset($settings['columns'])) return;
        $columns  = $settings['columns'];
        foreach($columns as $index => $column) $pivot->settings['columns'][$index]['drill'] = $this->pivotTao->fetchPivotDrill($pivot->id, $column['field']);
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
    public function checkIFChartInUse(int $chartID, string $type = 'chart', array $screens = array()): bool
    {
        static $screenList = array();
        if($screens) $screenList = $screens;
        if(empty($screenList)) $screenList = $this->dao->select('scheme')->from(TABLE_SCREEN)->where('deleted')->eq(0)->andWhere('status')->eq('published')->fetchAll();

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
    public function processFieldSettings(object $pivot): void
    {
        $this->loadModel('dataview');
        $fieldSettings = $pivot->fieldSettings;
        if(empty($fieldSettings)) return;

        $sql     = isset($pivot->sql) ? $pivot->sql : '';
        $filters = $this->getFieldsFromPivot($pivot, 'filters', array(), !is_array($pivot->filters), true);
        if(!empty($filters)) $filters = $this->setFilterDefault($filters);

        /* 检测sql是否有效。 */
        /* Check if the sql is valid. */
        $querySQL = $this->loadModel('chart')->parseSqlVars($sql, $filters);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $stmt = $this->dbh->query($querySQL);
        if(!$stmt) return;

        $columns      = $this->bi->getColumnsType($querySQL);
        $columnFields = array();
        foreach(array_keys(get_object_vars($columns)) as $type) $columnFields[$type] = $type;

        extract($this->bi->getTableAndFields($querySQL));

        /* 获取field的键值对以及相关联的对象。 */
        /* Get field key value pairs and related objects. */
        $this->loadModel('dataview');
        if($tables)
        {
            $statement = $this->bi->sql2Statement($sql);
            $moduleNames = $this->dataview->getModuleNames($tables);
            $aliasNames  = $this->dataview->getAliasNames($statement, $moduleNames);
        }
        list($fieldPairs, $relatedObjects) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames, $aliasNames);

        $objectFields = $this->loadModel('dataview')->getObjectFields();

        /* 重建fieldSettings字段。 */
        /* Rebuild fieldSettings field. */
        $pivot->fieldSettings = $this->bi->rebuildFieldSettings($fieldPairs, $columns, $relatedObjects, $fieldSettings, $objectFields);
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
     * 获取产品。
     * Get products.
     *
     * @param  string $conditions
     * @param  string $storyType
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function getProducts(string $conditions, string $storyType = 'story', array $filters = array()): array
    {
        $permission = common::hasPriv('pivot', 'showProduct') || $this->app->user->admin;
        $IDList     = !$permission ? $this->app->user->view->products : array();
        $products   = $this->pivotTao->getProductList($conditions, $IDList, $filters);

        /* 为产品生成计划数据和相关的需求数据。 */
        /* Generate plan data and related story data for products. */
        $this->pivotTao->processPlanStories($products, $storyType, $this->pivotTao->processProductPlan($products, $conditions));

        unset($products['']);
        return $products;
    }

    /**
     * 获取Bug创建表的数据。
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
        $end       = date('Y-m-d', strtotime("{$end} +1 day"));
        $bugGroups = $this->pivotTao->getBugGroup($begin, $end, $product, $execution);

        /* 为bug生成统计数据。 */
        /* Generate statistics data for bugs. */
        $bugs = array();
        foreach($bugGroups as $account => $userBugs)
        {
            $bug = array();
            $bug['openedBy']   = $account;
            $bug['unResolved'] = 0;
            $bug['validRate']  = 0;
            $bug['total']      = 0;

            /* Bug已解决状态数据初始化。 */
            /* Bug resolved status data initialization. */
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

            /* 获取有效率。*/
            /* Get valid rate. */
            $bug['validRate'] = $resolvedCount ? round($validCount / $resolvedCount * 100, 2) . '%' : '0%';

            $bugs[] = $bug;
        }

        uasort($bugs, 'sortSummary');
        return $bugs;
    }

    /**
     * 获取员工负载表的数据。
     * Get workload.
     *
     * @param  int    $dept
     * @param  string $assign  assign|noassign
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
    public function getWorkloadNoAssign(array $deptUsers, array $users, bool $canViewExecution): array
    {
        $executions = $this->pivotTao->getNoAssignExecution(array_keys($deptUsers));
        if(empty($executions)) return array();

        /* 构建用户-项目-执行数据结构。 */
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
    public function getWorkLoadAssign(array $deptUsers, array $users, bool $canViewExecution, float $allHour): array
    {
        $tasks = $this->pivotTao->getAssignTask(array_keys($deptUsers));
        if(empty($tasks)) return array();

        /* 构建用户-项目-执行-任务数据结构。 */
        /* Build user-project-execution-task data structure. */
        $taskGroups = array();
        foreach($tasks as $task)
        {
            if(!isset($users[$task->user])) continue;

            $user      = $task->user;
            $project   = $task->projectID;
            $execution = $task->executionID;
            $id        = $task->id;

            if(isset($taskGroups[$user][$project][$execution][$id]))
            {
                $taskGroups[$user][$project][$execution][$id]->left += $task->left;
            }
            else
            {
                $taskGroups[$user][$project][$execution][$id] = $task;
            }
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
    public function setExecutionName(object $execution, bool $canViewExecution): void
    {
        if($execution->multiple)
        {
            $execution->executionName = $canViewExecution ? html::a(helper::createLink('execution', 'view', "executionID={$execution->executionID}"), $execution->executionName, '', "title={$execution->executionName}") : "<span title={$execution->executionName}>{$execution->executionName}</span>";
        }
        else
        {
            $execution->executionName = $this->lang->null;
        }
    }

    /**
     * 获取用户的工作负载相关信息。
     * Get user's workload related information.
     *
     * @param  array   $projects
     * @param  array   $teamTasks
     * @param  float   $allHour
     * @access private
     * @return array
     */
    public function getUserWorkLoad(array $projects, array $teamTasks, float $allHour): array
    {
        /* 计算员工的任务数，剩余工时和总任务数。 */
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

        /* 计算员工的工作负载。 */
        /* Calculate user's workload. */
        $userWorkload = $allHour ? round($totalHours / $allHour * 100, 2) . '%' : '0%';

        return array($totalTasks, $totalHours, $totalExecutions, $userWorkload);
    }

    /**
     * 获取未解决Bug指派表相关数据。
     * Get bug assign.
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
     * 获取执行的下拉菜单相关数据。
     * Get execution dropdown menu related data.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutions(): array
    {
        $executions = $this->pivotTao->getProjectAndExecutionNameQuery();

        $pairs = array();
        foreach($executions as $execution)
        {
            if($execution->multiple)  $pairs[$execution->id] = $execution->projectname . '/' . $execution->name;
            if(!$execution->multiple) $pairs[$execution->id] = $execution->projectname;
        }

        return $pairs;
    }

    /**
     * 格式化sql和过滤条件。
     * Format sql and filter.
     *
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function getFilterFormat(string $sql, array $filters): array
    {
        if(empty($filters)) return array($sql, false);

        $currentFilter = current($filters);
        $isQueryFilter = (isset($currentFilter['from']) && $currentFilter['from'] == 'query');

        $filterFormat = $isQueryFilter ? array_values($filters) : array();
        foreach($filters as $filter)
        {
            $field = $filter['field'];

            if($isQueryFilter)
            {
                $queryDefault = isset($filter['default']) ? $this->processDateVar($filter['default']) : '';

                if(strpos($sql, $filter['field'] . 'Condition') === false)
                {
                    $sql = str_replace('$' . $filter['field'], "'{$queryDefault}'", $sql);
                }
                else
                {
                    $sql = str_replace('$' . $filter['field'] . 'Condition', "{$filter['relatedField']}='{$queryDefault}'", $sql);
                }
            }
            else
            {
                if(!isset($filter['default'])) continue;

                $default = $filter['default'];
                switch($filter['type'])
                {
                    case 'select':
                        if(is_array($default)) $default = implode("', '", array_filter($default, function($val){return trim($val) != '';}));
                        if(empty($default)) break;
                        $value = "('" . $default . "')";
                        $filterFormat[$field] = array('operator' => 'IN', 'value' => $value);
                        break;
                    case 'input':
                        $filterFormat[$field] = array('operator' => 'LIKE', 'value' => "'%$default%'");
                        break;
                    case 'date':
                    case 'datetime':
                        $begin = $default['begin'];
                        $end   = $default['end'];

                        if(!empty($begin)) $begin = date('Y-m-d 00:00:00', strtotime($begin));
                        if(!empty($end))   $end   = date('Y-m-d 23:59:59', strtotime($end));

                        if(!empty($begin) &&  empty($end)) $filterFormat[$field] = array('operator' => '>=',       'value' => "'{$begin}'");
                        if( empty($begin) && !empty($end)) $filterFormat[$field] = array('operator' => '<=',       'value' => "'{$end}'");
                        if(!empty($begin) && !empty($end)) $filterFormat[$field] = array('operator' => 'BETWEEN', 'value' => "'{$begin}' AND '{$end}'");
                        break;
                }
            }
        }

        return array($sql, $filterFormat);
    }

    /**
     * Get groups from settings.
     *
     * @param  array    $settings
     * @access public
     * @return array
     */
    public function getGroupsFromSettings(array $settings): array
    {
        $groups = array();
        foreach($settings as $key => $value)
        {
            if(strpos($key, 'group') !== false && $value) $groups[] = $value;
        }

        return array_unique($groups);
    }

    /**
     * Trim semicolon of sql.
     *
     * @param  string    $sql
     * @access public
     * @return string
     */
    public function trimSemicolon(string $sql): string
    {
        return str_replace(';', '', $sql);
    }

    /**
     * Append where filter to sql from filters.
     *
     * @param  string      $sql
     * @param  array|false $filters
     * @param  array       $driver
     * @access public
     * @return string
     */
    public function appendWhereFilterToSql(string $sql, array|false $filters, string $driver): string
    {
        $connectSQL = '';
        if(!isset($filters[0]['from']) && $filters !== false)
        {
            if(!empty($filters))
            {
                $wheres = array();
                foreach($filters as $field => $filter)
                {
                    $fieldSQL = $this->getFilterFieldSQL($filter, $field, $driver);
                    $wheres[] = "$fieldSQL {$filter['operator']} {$filter['value']}";
                }

                $whereStr    = implode(' and ', $wheres);
                $connectSQL .= " where $whereStr";
            }
            else
            {
                $connectSQL .= " where 1=0";
            }
        }

        $sql = "select * from ( $sql ) tt" . $connectSQL;

        return $sql;
    }

    public function getFilterFieldSQL(array $filter, string $field, string $driver)
    {
        $fieldSql = "tt.`{$field}`";

        if($driver == 'duckdb')
        {
            $type = $filter['type'];
            if($type == 'input')
            {
                $fieldSql = " cast($fieldSql as varchar) ";
            }
        }

        return $fieldSql;
    }

    /**
     * Map record value with field options.
     *
     * @param  array    $records
     * @param  array    $fields
     * @param  string   $sql
     * @access public
     * @return array
     */
    public function mapRecordValueWithFieldOptions(array $records, array $fields, string $sql, string $driver): array
    {
        $this->app->loadConfig('dataview');
        $records      = json_decode(json_encode($records), true);
        $fieldOptions = $this->getFieldsOptions($fields, $sql, $driver);
        foreach($records as $index => $record)
        {
            foreach($record as $field => $value)
            {
                $record["{$field}_origin"] = $value;
                $tableField = !isset($fields[$field]) ? '' : $fields[$field]['object'] . '-' . $fields[$field]['field'];
                $withComma  = in_array($tableField, $this->config->dataview->multipleMappingFields);

                $optionList = isset($fieldOptions[$field]) ? $fieldOptions[$field] : array();

                if($withComma)
                {
                    $valueArr  = array_filter(explode(',', $value));
                    $resultArr = array();
                    foreach($valueArr as $val)
                    {
                        $resultArr[] = isset($optionList[$val]) ? $optionList[$val] : $val;
                    }

                    $record[$field] = implode(',', $resultArr);
                }
                else
                {
                    $valueKey       = "$value";
                    $record[$field] = isset($optionList[$valueKey]) ? $optionList[$valueKey] : $value;
                }
            }

            $records[$index] = (object)$record;
        }

        return $records;
    }

    /**
     * Genereate table cols config.
     *
     * @param  array    $fields
     * @param  array    $groups
     * @param  array    $langs
     * @access public
     * @return array
     */
    public function generateTableCols(array $fields, array $groups, array $langs): array
    {
        $cols       = array();
        $clientLang = $this->app->getClientLang();
        /* Build cols. */
        foreach($groups as $group)
        {
            $fieldObject  = $fields[$group]['object'];
            $relatedField = $fields[$group]['field'];

            $col = new stdclass();
            $col->name    = $group;
            $col->field   = $relatedField;
            $col->isGroup = true;

            $colLabel = $group;
            if($fieldObject)
            {
                $this->app->loadLang($fieldObject);
                if(isset($this->lang->$fieldObject->$relatedField)) $colLabel = $this->lang->$fieldObject->$relatedField;

                if($this->config->edition != 'open')
                {
                    $workflowFields = $this->loadModel('workflowfield')->getFieldPairs($fieldObject);
                    if(isset($workflowFields[$relatedField])) $colLabel = $workflowFields[$relatedField];
                }
            }

            if(isset($langs[$group]) and !empty($langs[$group][$clientLang])) $colLabel = $langs[$group][$clientLang];
            $col->label = $colLabel;

            $cols[0][] = $col;
        }

        return $cols;
    }

    /**
     * Process column original.
     *
     * @param  int      $index
     * @param  string   $field
     * @param  array    $groups
     * @param  array    $records
     * @access public
     * @return array
     */
    public function processColumnOriginal(int $index, string $field, array $groups, array $records): array
    {
        $columnRecords = array();
        foreach($records as $record)
        {
            $columnRecord = new stdclass();
            foreach($groups as $group) $columnRecord->$group = $record->$group;
            $columnRecord->{$field . $index} = $record->$field;

            $columnRecords[] = $columnRecord;
        }
        return $columnRecords;
    }

    /**
     * Get slice field key of record.
     *
     * @param  int     $index
     * @param  string  $slice
     * @param  string  $field
     * @param  object  $record
     * @access public
     * @return string
     */
    public function getSliceFieldKey(int $index, string $slice, string $field, object $record): string
    {
        if($slice == 'noSlice') return $field . $index;
        return $record->$slice . '_slice_' . $field . $index;
    }

    /**
     * Init statistic column with slice.
     *
     * @param  int     $index
     * @param  string  $field
     * @param  string  $slice
     * @param  array   $groups
     * @param  array   $records
     * @access public
     * @return array
     */
    public function initSliceColumnRecords(int $index, string $field, string $slice, array $groups, array $records): array
    {
        $columnRecords = array();
        $groupUnique   = array();
        $sliceUnique   = array();
        foreach($records as $record)
        {
            $groupUnionKey = $this->getGroupsKey($groups, $record);
            $fieldKey  = $this->getSliceFieldKey($index, $slice, $field, $record);

            $sliceUnique[$fieldKey]  = 1;
            $groupUnique[$groupUnionKey] = $record;
        }

        $sliceKeys = array_keys($sliceUnique);
        $groupUnionKeys = array_keys($groupUnique);

        foreach($groupUnionKeys as $groupUnionKey)
        {
            $columnRecord = new stdclass();
            foreach($groups as $group) $columnRecord->$group = $groupUnique[$groupUnionKey]->$group;

            foreach($sliceKeys as $sliceKey) $columnRecord->$sliceKey = array('count' => 0, 'distinct' => array(), 'sum' => 0, 'avg' => array(), 'max' => array(), 'min' => array(), 'rows' => array(), 'drillFields' => array());

            $columnRecords[$groupUnionKey] = $columnRecord;
        }

        return $columnRecords;
    }

    /**
     * 添加下钻所需数据。
     * Add drill data.
     *
     * @param  array  $sliceRecord
     * @param  object $record
     * @param  string $field
     * @param  string $slice
     * @param  array  $groups
     * @access public
     * @return array
     */
    public function addDrillData(array $sliceRecord, object $record, string $slice, array $groups): array
    {
        $drills = $sliceRecord['drillFields'];
        if($slice != 'noSlice') $groups[] = $slice;

        /* 添加下钻所需字段值。*/
        /* add dirll field value. */
        foreach($groups as $drillField) $drills[$drillField] = $record->{$drillField . '_origin'};

        $sliceRecord['drillFields'] = $drills;
        return $sliceRecord;
    }

    /**
     * Process column stat with slice.
     *
     * @param  int       $index
     * @param  string    $field
     * @param  string    $slice
     * @param  string    $stat
     * @param  array     $groups
     * @param  array     $records
     * @access public
     * @return array
     */
    public function processColumnStat(int $index, string $field, string $slice, string $stat, array $groups, array $records, array $drillRecords): array
    {
        $sliceRecords = $this->initSliceColumnRecords($index, $field, $slice, $groups, $records);
        foreach($records as $record)
        {
            $groupUnionKey = $this->getGroupsKey($groups, $record);
            $fieldKey  = $this->getSliceFieldKey($index, $slice, $field, $record);

            $sliceGroupRecord = $sliceRecords[$groupUnionKey];
            $value            = $record->$field;
            $floatValue       = is_numeric($value) ? (float)$value : 0;

            $sliceGroupRecord->{$fieldKey} = $this->addDrillData($sliceGroupRecord->{$fieldKey}, $record, $slice, $groups);

            switch($stat)
            {
                case 'sum':
                    $sliceGroupRecord->{$fieldKey}[$stat] += $floatValue;
                    break;
                case 'avg':
                case 'max':
                case 'min':
                    $sliceGroupRecord->{$fieldKey}[$stat][] = $floatValue;
                    break;
                case 'count':
                    $sliceGroupRecord->{$fieldKey}[$stat] += 1;
                    break;
                case 'distinct':
                    $sliceGroupRecord->{$fieldKey}[$stat][] = $value;
                    break;
            }
        }

        foreach($sliceRecords as $groupUnionKey => $sliceRecord)
        {
            $drillFields = array();

            $sliceFields = array_keys((array)$sliceRecord);
            foreach($sliceFields as $sliceField)
            {
                /* 分组字段直接跳过。*/
                /* Skip the group field directly. */
                if(in_array($sliceField, $groups)) continue;

                $drillFields[$sliceField] = $sliceRecord->{$sliceField}['drillFields'];

                $sliceStat = $sliceRecord->{$sliceField}[$stat];
                switch($stat)
                {
                    case 'sum':
                        $sliceRecord->$sliceField = round($sliceStat, 2);
                        break;
                    case 'avg':
                        $sum   = array_sum($sliceStat);
                        $count = count($sliceStat);
                        $sliceRecord->$sliceField = $count == 0 ? 0 : round($sum / $count, 2);
                        break;
                    case 'max':
                        $sliceRecord->$sliceField = $sliceStat ? round(max($sliceStat), 2) : 0;
                        break;
                    case 'min':
                        $sliceRecord->$sliceField = $sliceStat ? round(min($sliceStat), 2) : 0;
                        break;
                    case 'count':
                        $sliceRecord->$sliceField = $sliceStat;
                        break;
                    case 'distinct':
                        $sliceRecord->$sliceField = count(array_unique($sliceStat));
                        break;
                }
            }

            if(!isset($drillRecords[$groupUnionKey])) $drillRecords[$groupUnionKey] = array('drillFields' => $drillFields);
            if(!empty($drillFields)) $drillRecords[$groupUnionKey]['drillFields'] += $drillFields;
        }

        return array($sliceRecords, $drillRecords);
    }

    /**
     * Merge origin records.
     *
     * @param  array  $originColumns
     * @param  array  $mergeRecords
     * @access public
     * @return array
     */
    public function mergeOriginRecords(array $originColumns, array $mergeRecords): array
    {
        if(empty($originColumns)) return $mergeRecords;

        $originColumns = $this->sortWithItemLength($originColumns);

        foreach($originColumns as $columnIndex => $columnSetting)
        {
            if(empty($mergeRecords))
            {
                $mergeRecords = $columnSetting['records'];
                continue;
            }

            $columnRecords = $columnSetting['records'];
            foreach($mergeRecords as $index => $mergeRecord)
            {
                $mergeRecords[$index] = (object)array_merge((array)$mergeRecord, (array)$columnRecords[$index]);
            }
        }

        return $mergeRecords;
    }

    /**
     * Merge statistic records.
     *
     * @param  array    $statColumns
     * @param  array    $groups
     * @param  array    $mergeRecords
     * @access public
     * @return array
     */
    public function mergeStatRecords(array $statColumns, array $groups, array $mergeRecords): array
    {
        if(empty($statColumns)) return $mergeRecords;

        $statColumns = $this->sortWithItemLength($statColumns);

        foreach($statColumns as $columnIndex => $columnSetting)
        {
            if(empty($mergeRecords))
            {
                $mergeRecords = $columnSetting['records'];
                continue;
            }

            $columnRecords = $columnSetting['records'];
            foreach($mergeRecords as $key => $mergeRecord)
            {
                $groupUnionKey = $this->getGroupsKey($groups, $mergeRecord);
                $mergeRecords[$key] = (object)array_merge((array)$mergeRecord, (array)$columnRecords[$groupUnionKey]);
            }
        }

        return $mergeRecords;
    }

    /**
     * Sort array with item length.
     *
     * @param  array    $array
     * @access public
     * @return array
     */
    public function sortWithItemLength(array $array): array
    {
        usort($array, function($a, $b) {
            $lengthA = count($a);
            $lengthB = count($b);

            if ($lengthA == $lengthB) {
                return 0;
            }

            return ($lengthA > $lengthB) ? -1 : 1;
        });

        return $array;
    }

    /**
     * Sort merge records with group field.
     *
     * @param  array    $records
     * @param  array    $mergeRecords
     * @param  array    $groups
     * @access public
     * @return array
     */
    public function orderByRecordsGroups(array $records, array $mergeRecords, array $groups): array
    {
        $groupTree = $this->generateGroupTree($groups, $records);
        $groupRecords = $this->flattenGroupTree($groups, $groupTree);

        foreach($mergeRecords as $mergeRecord)
        {
            $groupUnionKey = $this->getGroupsKey($groups, $mergeRecord);
            $groupRecords[$groupUnionKey][] = $mergeRecord;
        }

        $orderRecords = array();
        foreach($groupRecords as $groupRecord)
        {
            $orderRecords = array_merge($orderRecords, $groupRecord);
        }

        return $orderRecords;
    }

    /**
     * Generate group tree.
     *
     * @param  array    $groups
     * @param  array    $records
     * @access public
     * @return array
     */
    public function generateGroupTree(array $groups, array $records): array
    {
        $groupTree = array();
        foreach($records as $record)
        {
            $currentGroupTree = &$groupTree;
            foreach($groups as $group)
            {
                $groupValue = $record->$group;
                if(!isset($currentGroupTree[$groupValue])) $currentGroupTree[$groupValue] = array();

                $currentGroupTree = &$currentGroupTree[$groupValue];
            }
        }

        return $groupTree;
    }

    /**
     * Flatten group tree.
     *
     * @param  array  $groups
     * @param  array  $groupTree
     * @access public
     * @return void
     */
    public function flattenGroupTree($groups, $groupTree)
    {
        $flattenGroup = array_map(function($key, $value) {
            return array($key, $value);
        }, array_keys($groupTree), $groupTree);

        $groupLevels           = count($groups);
        $currentLevel          = 1;
        $flattenGroupRecords   = array();
        while($currentLevel < count($groups))
        {
            $nextFlatten = array();
            foreach($flattenGroup as $flatten)
            {
                $lastValue = array_pop($flatten);
                foreach($lastValue as $key => $value)
                {
                    /* 如果value为空，那么说明到了最后一级，直接构建分组键值的数组。*/
                    /* If the value is empty, it means that the last level has been reached, and the array of the group key value is constructed directly. */
                    if(empty($value))
                    {
                        $unionKey = implode('_', $flatten) . '_' . $key;
                        $flattenGroupRecords[$unionKey] = array();
                        continue;
                    }
                    $nextFlatten[] = array_merge($flatten, array($key, $value));
                }
            }
            $flattenGroup = $nextFlatten;
            $currentLevel ++;
        }

        return $flattenGroupRecords;
    }

    /**
     * Get show origin from columns.
     *
     * @param  array $columns
     * @access public
     * @return array
     */
    public function getShowOriginsFromColumns(array $columns): array
    {
        $showOrigins = array();
        foreach($columns as $index => $column)
        {
            $field      = $column['field'];
            $showOrigin = (int)zget($column, 'showOrigin', 0);
            $showOrigins[$field . $index] = $showOrigin;
        }

        return $showOrigins;
    }

    /**
     * Get show origin with record.
     *
     * @param  array|object $record
     * @param  int    $showOrigins
     * @access public
     * @return array
     */
    public function getShowOriginsWithRecord(array|object $record, $showOrigins): array
    {
        $columns = array();
        foreach(array_keys((array)$record) as $index => $field)
        {
            foreach($showOrigins as $shortField => $showOrigin)
            {
                if(strpos(strrev($field), strrev($shortField)) !== 0) continue;
                $columns[$index] = $showOrigin;
            }
        }

        return $columns;
    }

    /**
     * Calculate group merge cell config.
     *
     * @param  array $groups
     * @param  array $records
     * @access public
     * @return array
     */
    public function calculateGroupMergeCellConfig(array $groups, array $records): array
    {
        $getGroupValue = function($groups, $groupIndex, $record)
        {
            $values = array();
            foreach($groups as $index => $group)
            {
                if($index > $groupIndex) break;
                $values[] = $record->$group;
            }

            return implode('-', $values);
        };

        $setConfig = function($configs, $lastConfig, $groupIndex)
        {
            extract($lastConfig);
            if(!isset($configs[$startIndex])) $configs[$startIndex] = array();
            $configs[$startIndex][$groupIndex] = $lineCount;
            return $configs;
        };

        $configs = array();
        foreach($groups as $groupIndex => $group)
        {
            $lastGroup  = null;
            $lastConfig = array();
            foreach($records as $rowIndex => $record)
            {
                $groupValue = $getGroupValue($groups, $groupIndex, $record);
                /* 上一个分组的值与当前分组的值不一致。*/
                if($lastGroup !== $groupValue)
                {
                    /* 如果不是首行，那么需要记录config。*/
                    if($rowIndex != 0) $configs = $setConfig($configs, $lastConfig, $groupIndex);

                    $lastGroup  = $groupValue;
                    $lastConfig = array('startIndex' => $rowIndex, 'lineCount' => 1);
                }
                elseif($rowIndex != 0)
                {
                    $lastConfig['lineCount'] += 1;
                }
            }

            $configs = $setConfig($configs, $lastConfig, $groupIndex);
        }

        return $configs;
    }

    /**
     * Calculate column merge cell config.
     *
     * @param  array $configs
     * @param  array $showOrigins
     * @access public
     * @return array
     */
    public function calculateColumnMergeCellConfig(array $configs, array $showOrigins): array
    {
        foreach($configs as $rowIndex => $config)
        {
            $lineCount = end($config);
            foreach($showOrigins as $colIndex => $showOrigin)
            {
                if($showOrigin === 1) continue;
                $config[$colIndex] = $lineCount;
            }
            $configs[$rowIndex] = $config;
        }

        return $configs;
    }

    /**
     * Calculate merge cell config.
     *
     * @param  array    $groups
     * @param  array    $records
     * @access public
     * @return array
     */
    public function calculateMergeCellConfig(array $groups, array $columns, array $records)
    {
        if(empty($records)) return array();

        $showOrigins = $this->getShowOriginsFromColumns($columns);
        $showOrigins = $this->getShowOriginsWithRecord($records[0], $showOrigins);

        $configs = $this->calculateGroupMergeCellConfig($groups, $records);
        return $this->calculateColumnMergeCellConfig($configs, $showOrigins);
    }

    /**
     * Gen sheet.
     *
     * @param  array       $fields
     * @param  array       $settings
     * @param  string      $sql
     * @param  array|false $filters
     * @param  array       $langs
     * @access public
     * @return array
     */
    public function genSheet(array $fields, array $settings, string $sql, array|false $filters, array $langs = array(), string $driver = 'mysql'): array
    {
        $groups = $this->getGroupsFromSettings($settings);
        $cols   = $this->generateTableCols($fields, $groups, $langs);

        /* Replace the variable with the default value. */
        $sql = $this->bi->processVars($sql, (array)$filters);
        $sql = $this->trimSemicolon($sql);
        $sql = $this->appendWhereFilterToSql($sql, $filters, $driver);

        $dbh     = $this->app->loadDriver($driver);
        $records = $dbh->query($sql)->fetchAll();

        $records = $this->mapRecordValueWithFieldOptions($records, $fields, $sql, $driver);

        $showColTotal = zget($settings, 'columnTotal', 'noShow');

        $mergeRecords = array();
        $drillRecords = array();

        if(isset($settings['columns']))
        {
            $columnSettings = $settings['columns'];
            foreach($columnSettings as $columnIndex => $columnSetting)
            {
                $columnShowOrigin = isset($columnSetting['showOrigin']) ? $columnSetting['showOrigin'] : false;
                $columnStat       = $columnSetting['stat'];
                $columnField      = $columnSetting['field'];
                $columnSlice      = zget($columnSetting, 'slice', 'noSlice');

                $cols = $this->getTableHeader($records, $columnSetting, $fields, $cols, $sql, $langs, $columnShowOrigin, $driver);

                if($columnShowOrigin)
                {
                    $columnRecords = $this->processColumnOriginal($columnIndex, $columnField, $groups, $records);
                    if($columnRecords) $columnRecords = $this->processShowData($columnRecords, $groups, $columnSetting, $showColTotal, $columnField . $columnIndex);

                    $columnSetting['records'] = $columnRecords;
                    $mergeRecords = $this->mergeOriginRecords(array($columnSetting), $mergeRecords);
                }
                elseif(!empty($columnStat))
                {
                    list($columnRecords, $drillRecords) = $this->processColumnStat($columnIndex, $columnField, $columnSlice, $columnStat, $groups, $records, $drillRecords);
                    if($columnRecords) $columnRecords = $this->processShowData($columnRecords, $groups, $columnSetting, $showColTotal, $columnField . $columnIndex);

                    $columnSetting['records'] = $columnRecords;
                    $mergeRecords = $this->mergeStatRecords(array($columnSetting), $groups, $mergeRecords);
                }
            }
        }

        $mergeRecords = $mergeRecords ? array_values($mergeRecords) : array();
        $mergeRecords = $this->orderByRecordsGroups($records, $mergeRecords, $groups);

        $mergeDrillRecords = array();
        foreach($mergeRecords as $lineRecord)
        {
            $lineGroupKey = $this->getGroupsKey($groups, $lineRecord);
            $mergeDrillRecords[$lineGroupKey] = isset($drillRecords[$lineGroupKey]) ? $drillRecords[$lineGroupKey] : array();
        }

        $data              = new stdclass();
        $data->groups      = $groups;
        $data->cols        = $cols;
        $data->array       = json_decode(json_encode($mergeRecords), true);
        if($showColTotal == 'sum' && count($data->array)) $this->processLastRow($data->array[count($data->array) - 1]);
        $data->columnTotal = isset($settings['columnTotal']) ? $settings['columnTotal'] : '';
        $data->drills      = $mergeDrillRecords;

        $configs = $this->calculateMergeCellConfig($groups, $settings['columns'], $mergeRecords);

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
     * Process last column data.
     *
     * @param  array  $data
     * @access public
     * @return void
     */
    public function processLastRow(array &$data)
    {
        foreach($data as $key => $value)
        {
            if($value === '$totalGroup$') $data[$key] = $this->lang->pivot->stepDesign->total;
        }
    }

    /**
     * Gen sheet by origin sql.
     *
     * @param  array       $fields
     * @param  array       $settings
     * @param  string      $sql
     * @param  array|false $filters
     * @param  array       $langs
     * @access public
     * @return string
     */
    public function genOriginSheet($fields, $settings, $sql, $filters, $langs = array(), $driver = 'mysql')
    {
        $sql = $this->bi->processVars($sql, (array)$filters);
        $sql = $this->trimSemicolon($sql);
        $sql = $this->appendWhereFilterToSql($sql, $filters, $driver);

        $dbh  = $this->app->loadDriver($driver);
        $rows = $dbh->query($sql)->fetchAll();
        $rows = json_decode(json_encode($rows), true);

        $cols   = array();
        $drills = zget($settings, 'drills', array());
        /* Build cols. */
        foreach($fields as $key => $field)
        {
            $col = new stdclass();
            $col->name    = $key;
            $col->isGroup = true;
            $col->label   = $this->getColLabel($key, $fields, $langs);

            if(isset($drills[$key]))
            {
                $col->isDrilling = true;
                $col->condition  = $drills[$key];
                $col->drillField = $key;
            }

            $cols[0][] = $col;
        }

        $fieldOptions    = $this->getFieldsOptions($fields, $sql);
        $dataDrills      = array();
        $rowsAfterFields = array();
        foreach($rows as $key => $row)
        {
            $drillFields    = array();
            $rowAfterFields = array();
            foreach($row as $field => $value)
            {
                if(isset($drills[$field]))
                {
                    $drillField = array();
                    foreach($drills[$field] as $condition)
                    {
                        $queryField = $condition['queryField'];
                        $drillField[$queryField] = $row[$queryField];
                    }
                    $drillFields[$field] = $drillField;
                }
                $optionList  = isset($fieldOptions[$field]) ? $fieldOptions[$field] : array();
                $rowAfterFields[$field] = isset($optionList[$value]) ? $optionList[$value] : $value;
            }
            $dataDrills[$key] = array('drillFields' => $drillFields);

            $rowsAfterFields[$key] = $rowAfterFields;
        }

        $data = new stdclass();
        $data->cols   = $cols;
        $data->array  = $rowsAfterFields;
        $data->drills = $dataDrills;

        $configs = array_fill(0, count($rows), array_fill(0, count($fields), 1));

        return array($data, $configs);
    }

    /**
     * 初始化sql。
     * Init sql.
     *
     * @param  string $sql
     * @param  array  $filters
     * @param  string $groupList
     * @access public
     * @return array
     */
    public function initSql(string $sql, array $filters, string $groupList): array
    {
        $sql = str_replace(';', '', $this->initVarFilter($filters, $sql));

        if(preg_match_all("/[\$]+[a-zA-Z0-9]+/", $sql, $out))
        {
            foreach($out[0] as $match) $sql = str_replace($match, "''", $sql);
        }

        $connectSQL = $this->getConnectSQL($filters);
        $groupSQL   = " group by {$groupList}";
        $orderSQL   = " order by {$groupList}";

        return array($sql, $connectSQL, $groupSQL, $orderSQL);
    }

    /**
     * 获取connectSQL。
     * Get connectSQL.
     *
     * @param  array  $filters
     * @access public
     * @return string
     */
    public function getConnectSQL(array $filters): string
    {
        $connectSQL = '';
        if(!empty($filters) && !isset($filters[0]['from']))
        {
            $wheres = array();
            foreach($filters as $field => $filter) $wheres[] = "tt.`{$field}` {$filter['operator']} {$filter['value']}";

            $whereStr    = implode(' and ', $wheres);
            $connectSQL .= " where {$whereStr}";
        }

        return $connectSQL;
    }

    /**
     * 通过过滤配置格式化sql。
     * Init sql by filters.
     *
     * @param  array  $filters
     * @param  string $sql
     * @access public
     * @return string
     */
    private function initVarFilter(array $filters = array(), string $sql = ''): string
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
     * 获取列名称。
     * Get col label.
     *
     * @param  string  $key
     * @param  array   $fields
     * @param  array   $langs
     * @access public
     * @return string
     */
    public function getColLabel(string $key, array $fields, array $langs): string
    {
        $clientLang = $this->app->getClientLang();

        $fieldLang = zget($fields[$key], $clientLang, '');
        if(!empty($fieldLang)) return $fieldLang;

        if(isset($langs[$key]))
        {
            $lang = zget($langs[$key], $clientLang, '');
            if(!empty($lang)) return $lang;
        }

        $object = zget($fields[$key], 'object', '');
        if($object)
        {
            if($this->config->edition != 'open')
            {
                $workflowFields = $this->loadModel('workflowfield')->getFieldPairs($object);
                if(isset($workflowFields[$key])) return $workflowFields[$key];
            }

            $this->app->loadLang($object);
            if(isset($this->lang->{$object}->{$key})) return $this->lang->{$object}->{$key};
        }

        $name = zget($fields[$key], 'name', '');
        if(!empty($name)) return $name;

        return $key;
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
    public function getTableHeader($columnRows, $column, $fields, $cols, $sql, $langs = array(), $showOrigin = false, $driver = 'mysql')
    {
        $stat       = zget($column, 'stat', '');
        $showMode   = zget($column, 'showMode', 'default');
        $monopolize = $showMode == 'default' ? '' : zget($column, 'monopolize', '');

        $isDrilling = isset($column['drill']) && zget($column['drill'], 'condition', '');
        $drillField = $isDrilling ? zget($column['drill'], 'field', '') : '';
        $condition  = $isDrilling ? zget($column['drill'], 'condition', '') : '';

        $col = new stdclass();
        $col->name       = $column['field'];
        $col->isGroup    = false;
        $col->showOrigin = $showOrigin;
        $col->isDrilling = $isDrilling;
        $col->drillField = $drillField;
        $col->condition  = $condition;

        $fieldObject  = $fields[$column['field']]['object'];
        $relatedField = $fields[$column['field']]['field'];
        $colLabel     = $this->getColLabel($column['field'], $fields, $langs);

        if(!$showOrigin)
        {
            $colLabel = str_replace('{$field}', $colLabel, $this->lang->pivot->colLabel);
            $colLabel = str_replace('{$stat}', zget($this->lang->pivot->stepDesign->statList, $stat), $colLabel);
            if($showMode != 'default') $colLabel .= sprintf($this->lang->pivot->colShowMode, zget($this->lang->pivot->stepDesign->showModeList, $showMode));
        }
        $col->label = $colLabel;

        $slice = zget($column, 'slice', 'noSlice');
        $col->isSlice = $slice != 'noSlice';
        if($slice != 'noSlice' && !$showOrigin)
        {
            if(!isset($cols[1]))
            {
                foreach($cols[0] as $colData) $colData->rowspan = '2';
                $cols[1] = array();
            }
            $sliceList = array();
            foreach($columnRows as $rows) $sliceList[$rows->{$slice}] = $rows->{$slice};

            $optionList = $this->getSysOptions($fields[$slice]['type'], $fields[$slice]['object'], $fields[$slice]['field'], $sql, '', $driver);
            foreach($sliceList as $field)
            {
                $childCol = new stdclass();
                $childCol->name       = $field;
                $childCol->isGroup    = false;
                $childCol->label      = isset($optionList[$field]) ? $optionList[$field] : $field;
                $childCol->label      = empty($childCol->label) ? $this->lang->pivot->empty : $childCol->label;
                $childCol->colspan    = $monopolize ? 2 : 1;
                $childCol->isDrilling = $isDrilling;
                $childCol->drillField = $drillField;
                $childCol->condition  = $condition;
                $cols[1][] = $childCol;
            }
            $col->colspan = count($sliceList);
            if($monopolize) $col->colspan *= 2;

            if(zget($column, 'showTotal', 'noShow') !== 'noShow')
            {
                $childCol = new stdclass();
                $childCol->name    = 'sum';
                $childCol->isGroup = false;
                $childCol->label   = $this->lang->pivot->stepDesign->total;
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
     * Get ratio.
     *
     * @param  float    $value
     * @param  float    $total
     * @access public
     * @return float
     */
    public function getRatio($value, $total)
    {
        return $total == 0 ? '0%' : round((float)$value / (float)$total * 100, 2) . '%';
    }

    /**
     * Process column show mode.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  array   $column
     * @param  string  $showColTotal
     * @param  string  $uuName
     * @access public
     * @return array
     */
    public function processShowData(array $columnRows, array $groups, array $column, string $showColTotal, string $uuName): array
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
                    $columnRow->{$field} = $this->getRatio($value, $allTotal);
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{"sum_$uuName"} = $this->getRatio($rowTotal[$index], $allTotal);
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
                    $columnRow->{$field} = $this->getRatio($value, $rowTotal[$index]);
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{'sum_' . $uuName} = $this->getRatio($rowTotal[$index], $rowTotal[$index]);
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
                    $columnRow->{$field} = $this->getRatio($value, $colTotal[$field]);
                }
                if($showTotal == 'sum')
                {
                    if($monopolize) $columnRow->{"sum_self_$uuName"} = (float)$rowTotal[$index];
                    $columnRow->{'sum_' . $uuName} = $this->getRatio($rowTotal[$index], $allTotal);
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
            foreach(reset($columnRows) as $field => $value)
            {
                if(in_array($field, $groups))
                {
                    $colTotalRow->$field = '$totalGroup$';
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
                            $colTotalRow->{$field} = $this->getRatio($allTotal, $allTotal);
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
                    if($showMode == 'column')  $colTotalRow->$field = $this->getRatio($colTotal[$field], $colTotal[$field]);
                    if(strpos(',total,row,', ",$showMode,") !== false) $colTotalRow->$field = $this->getRatio($colTotal[$field], $allTotal);
                }
            }

            $groupKey = $this->getGroupsKey($groups, $colTotalRow);
            $columnRows[$groupKey] = $colTotalRow;
        }

        return $columnRows;
    }

    /**
     * Implode group keys of record.
     *
     * @param  array  $groups
     * @param  object $record
     * @access public
     * @return string
     */
    public function getGroupsKey(array $groups, object $record): string
    {
        $groupsKey = array();
        foreach($groups as $group) $groupsKey[] = $record->$group;

        return implode('_', $groupsKey);
    }

    /**
     * 处理表格的切片数据。
     * Process data as slice table data.
     *
     * @param  array  $columnRows
     * @param  array  $groups
     * @param  string $slice
     * @param  string $uuName
     * @access public
     * @return array
     */
    public function processSliceData(array $columnRows, array $groups, string $slice, string $uuName): array
    {
        $sliceList = array();
        foreach($columnRows as $rows) $sliceList[$rows->{$slice}] = $rows->{$slice};

        $index     = 0;
        $sliceRows = array();
        foreach($columnRows as $key => $columnRow)
        {
            $field = $columnRow->{$slice} . '_slice_' . $uuName;
            $columnRow->{$field} = $columnRow->{$uuName};
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
    public function getSysOptions($type, $object = '', $field = '', $source = '', $saveAs = '', $driver = 'mysql')
    {
        if(in_array($type, $this->config->pivot->scopeOptionList)) return $this->bi->getScopeOptions($type);
        if(!$field) return array();

        $options = array();
        switch($type)
        {
            case 'option':
                $options = $this->bi->getDataviewOptions($object, $field);
                break;
            case 'object':
                if(is_array($source))
                {
                    $options = array();
                    foreach($source as $row) $options[$row->id] = $row->$field;
                }
                else
                {
                    $options = $this->bi->getObjectOptions($object, $field);
                }
                break;
            case 'string':
            case 'number':
                if($source)
                {
                    if($this->config->edition != 'open')
                    {
                        $fieldObject = $this->loadModel('workflowfield')->getByField($object, $field);
                        if($fieldObject) $options = $this->workflowfield->getFieldOptions($fieldObject);
                        if(!empty($options)) break;
                    }

                    $options = array();
                    if(is_array($source))
                    {
                        foreach($source as $row) if(isset($row->$field)) $options["{$row->$field}"] = $row->$field;
                    }
                    else
                    {
                        $keyField   = $field;
                        $valueField = $saveAs ? $saveAs : $field;
                        $options = $this->bi->getOptionsFromSql($source, $driver, $keyField, $valueField);
                    }
                }
                break;
        }

        if(is_string($source) and $source and $saveAs and in_array($type, array('user', 'product', 'project', 'execution', 'dept', 'option', 'object')))
        {
            $options = $this->bi->getOptionsFromSql($source, $driver, $field, $saveAs);
        }

        return array_filter($options);
    }

    /**
     * 替换定义的表名。
     * Replace defined table names.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function replaceTableNames(string $sql): string
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

        return preg_replace("/= *'\!/U", "!='", $sql);
    }

    /**
     * 设置默认的过滤器。
     * Set default filter.
     *
     * @param  array   $filters
     * @access private
     * @return array
     */
    public function setFilterDefault(array $filters, bool $processDateVar = true): array
    {
        foreach($filters as &$filter)
        {
            if(!isset($filter['default']) || empty($filter['default'])) continue;
            if($processDateVar && is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }

        return $filters;
    }

    /**
     * 根据透视表不同阶段获取不同状态的筛选器。
     * Process filters.
     *
     * @param  array  $filters
     * @param  string $filterStatus
     * @access public
     * @return void
     */
    public function processFilters(array $filters, string $filterStatus): array
    {
        foreach($filters as $index => $filter)
        {
            if($filterStatus == 'published' && isset($filter['status']) && $filter['status'] == 'design')
            {
                unset($filters[$index]);
            }

            if($filterStatus == 'design')
            {
                if(isset($filter['status']) && $filter['status'] == 'design' && isset($filter['account']) && $filter['account'] == $this->app->user->account) continue;
                unset($filters[$index]);
            }
        }

        return array_values($filters);
    }

    /**
     * 将筛选器的值填写到查询条件中。
     * Set condition value with filters.
     *
     * @param  array $condition
     * @param  array $filters
     * @access public
     * @return string
     */
    public function setConditionValueWithFilters(array $condition, array $filters): string
    {
        $field = $condition['queryField'];
        if(!isset($filters[$field])) return '';

        $filter = $filters[$field];
        extract($filter);

        return " $operator $value";
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
    private function getFieldsFromPivot(object $pivot, string $key, mixed $default, bool $jsonDecode = false, bool $needArray = false): mixed
    {
        return isset($pivot->{$key}) && !empty($pivot->{$key}) ? ($jsonDecode ? json_decode($pivot->{$key}, $needArray) : $pivot->{$key}) : $default;
    }

    /**
     * Get field options.
     *
     * @param  array  $fieldSettings
     * @param  string $sql
     * @access public
     * @return array
     *
     */
    public function getFieldsOptions(array $fieldSettings, string $sql, string $driver = 'mysql'): array
    {
        $options = array();

        $dbh        = $this->app->loadDriver($driver);
        $sqlRecords = $dbh->query($sql)->fetchAll();

        foreach($fieldSettings as $key => $fieldSetting)
        {
            $type   = $fieldSetting['type'];
            $object = $fieldSetting['object'];
            $field  = $fieldSetting['field'];

            $source = $sql;
            if(in_array($type, array('string', 'number', 'date'))) $source = $sqlRecords;

            $options[$key] = $this->getSysOptions($type, $object, $field, $source, '', $driver);
        }

        return $options;
    }

    /**
     * Process DTable cols config, let buildPivotTable use.
     *
     * @param  array  $cols
     * @access public
     * @return array
     */
    public function processDTableCols($cols)
    {
        $formatCols = array();
        foreach($cols as $colField => $colInfo)
        {
            $formatCols[] = (object)array('name' => $colField,  'label' => $colInfo['title'], 'colspan' => 1);
        }
        return array($formatCols);
    }

    /**
     * Process DTable data, let buildPivotTable use.
     *
     * @param  array  $cols
     * @param  array  $datas
     * @access public
     * @return array
     */
    public function processDTableData($cols, $datas)
    {
        return array_map(function($data) use ($cols)
        {
            $result = [];
            $data   = (array)$data;
            foreach ($cols as $field) $result[] = isset($data[$field]) ? $data[$field] : '';
            return $result;
        }, $datas);
    }

    /**
     * Build table use data and rowspan.
     *
     * @param  object $data
     * @param  array  $configs
     * @access public
     * @return void
     *
     */
    public function buildPivotTable($data, $configs)
    {
        $width = 128;

        /* Init table. */
        $table = "<div class='reportData'><table class='table table-condensed table-striped table-bordered table-fixed datatable' style='width: auto; min-width: 100%' data-fixed-left-width='400'>";

        $showOrigins = array();
        $hasShowOrigin = false;

        foreach($data->cols[0] as $col)
        {
            $colspan       = zget($col, 'colspan', 1);
            $showOrigin    = isset($col->showOrigin) ? $col->showOrigin : false;
            $colShowOrigin = array_fill(0, $colspan, $showOrigin);
            $showOrigins   = array_merge($showOrigins, $colShowOrigin);
            if($showOrigin) $hasShowOrigin = true;
        }

        /* Init table thead. */
        $table .= "<thead>";
        foreach($data->cols as $lineCols)
        {
            $table .= "<tr>";
            foreach($lineCols as $col)
            {
                $thName  = $col->label;
                $colspan = zget($col, 'colspan', 1);
                $rowspan = zget($col, 'rowspan', 1);
                $isGroup = zget($col, 'isGroup', false);

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

        $useColumnTotal = (!empty($data->columnTotal) and $data->columnTotal === 'sum');

        for($i = 0; $i < count($data->array); $i ++)
        {
            $rowCount ++;

            if($useColumnTotal and $rowCount == count($data->array)) continue;

            $line   = array_values($data->array[$i]);
            $table .= "<tr class='text-center'>";
            for($j = 0; $j < count($line); $j ++)
            {
                $isGroup = !empty($data->cols[0][$j]->isGroup) ? $data->cols[0][$j]->isGroup : false;
                $rowspan = isset($configs[$i][$j]) ? $configs[$i][$j] : 1;
                $hidden  = isset($configs[$i][$j]) ? false : (!$isGroup ? false : true);

                $showOrigin = $showOrigins[$j];
                if($hasShowOrigin && !$isGroup && !$showOrigin)
                {
                    $rowspan = isset($configs[$i]) ? end($configs[$i]) : 1;
                    $hidden  = isset($configs[$i]) ? false : true;
                }

                $lineValue = $line[$j];
                if(is_numeric($lineValue)) $lineValue = round($lineValue, 2);

                if(!$hidden) $table .= "<td rowspan='$rowspan'>$lineValue</td>";
            }
            $table .= "</tr>";
        }

        if($useColumnTotal and !empty($data->array))
        {
            $table .= "<tr class='text-center'>";
            $table .= "<td colspan='" . count($data->groups) . "'>{$this->lang->pivot->stepDesign->total}</td>";
            foreach(end($data->array) as $field => $total)
            {
                if(in_array($field, $data->groups)) continue;
                if(is_numeric($total)) $total = round($total, 2);
                $table .= "<td>$total</td>";
            }
            $table .= "</tr>";
        }

        $table .= "</tbody>";
        $table .= "</table></div>";

        echo $table;
    }


    /* Data Drill */

    /**
     * Get cols for preview data table.
     *
     * @param  string $objectTable
     * @access public
     * @return array
     */
    public function getDrillCols($object)
    {
        if($object == 'case') $object = 'testcase';

        $cols = array();
        if(isset($this->config->pivot->drillObjectFields[$object]))
        {
            $this->loadModel($object);
            if(!isset($this->config->$object->dtable->fieldList)) return $this->config->pivot->objectTableFields->$object;

            $fieldList         = $object == 'product' ? $this->config->product->all->dtable->fieldList : $this->config->$object->dtable->fieldList;
            $userTypeCols      = $this->config->pivot->userTypeCols;
            $nameTypeCols      = $this->config->pivot->nameTypeCols;
            $reuseDtableFields = $this->config->pivot->reuseDtableFields;
            foreach($this->config->pivot->drillObjectFields[$object] as $fieldKey)
            {
                $fieldSetting = isset($fieldList[$fieldKey]) ? $fieldList[$fieldKey] : $this->config->pivot->objectTableFields->$object[$fieldKey];
                $fieldSetting['sortType'] = false;
                if(isset($fieldSetting['checkbox']) && $fieldSetting['checkbox']) $fieldSetting['checkbox'] = false;
                if(isset($fieldSetting['link']))
                {
                    if(is_string($fieldSetting['link']))
                    {
                        $fieldSettingLink = $fieldSetting['link'];

                        $fieldSetting['link'] = array();
                        $fieldSetting['link']['url']    = $fieldSettingLink;
                    }
                    $fieldSetting['link']['target'] = '_blank';
                }

                if(isset($fieldSetting['type']) && in_array($fieldSetting['type'], $userTypeCols)) $fieldSetting['type'] = 'user';

                foreach(array_keys($fieldSetting) as $settingKey)
                {
                    if(!in_array($settingKey, $reuseDtableFields)) unset($fieldSetting[$settingKey]);
                    if((!in_array($fieldKey, $nameTypeCols) && $settingKey == 'link') || $object == 'doc') unset($fieldSetting['link']);
                    if(isset($this->config->pivot->objectTableFields->$object[$fieldKey][$settingKey])) $fieldSetting[$settingKey] = $this->config->pivot->objectTableFields->$object[$fieldKey][$settingKey];
                }

                $cols[$fieldKey] = $fieldSetting;
            }
        }
        else
        {
            $this->app->loadLang($object);
            $table     = isset($this->config->objectTables[$object]) ? $this->config->objectTables[$object] : $this->config->db->prefix . $object;
            $table     = str_replace('`', '', $table);
            $fieldList = $this->loadModel('dev')->getFields($table);

            foreach($fieldList as $fieldName => $field)
            {
                if(empty($field['name'])) continue;

                $fieldLabel = $field['name'];
                if(isset($this->lang->$object->$fieldName)) $fieldLabel = $this->lang->$object->$fieldName;

                $cols[$fieldName] = array('name' => $fieldName, 'title' => $fieldLabel);
            }
        }

        return $cols;
    }

    /**
     * getReferSQL
     *
     * @param  string $object
     * @param  string $whereSQL
     * @param  array $fields
     * @access public
     * @return string
     */
    public function getReferSQL(string $object, string $whereSQL = '', array $fields = array()): string
    {
        $fieldStr = empty($fields) ? '' : (',' . implode(',', $fields));
        $table    = $this->config->db->prefix . $object;
        $referSQL = "SELECT t1.* {$fieldStr} FROM $table AS t1";

        return "$referSQL $whereSQL";
    }

    /**
     * Get drill sql.
     *
     * @param  string $objectTable
     * @param  string $whereSQL
     * @param  string $conditionsSQL
     * @access public
     * @return string
     */
    public function getDrillSQL($objectTable, $whereSQL = '', $conditions = array())
    {
        $fieldList     = array();
        $conditionSQLs = array('1=1');
        foreach($conditions as $condition)
        {
            extract($condition);
            if($drillAlias != 't1')
            {
                $fieldList[] = "{$drillAlias}.{$drillField} AS {$drillAlias}{$drillField}";
                $drillField  = $drillAlias . $drillField;
            }

            if(!empty($condition['value'])) $conditionSQLs[] = "t1.{$drillField}{$value}";
        }

        $referSQL     = $this->getReferSQL($objectTable, $whereSQL, $fieldList);
        $conditionSQL = 'WHERE ' . implode(' AND ', $conditionSQLs);

        return "SELECT t1.* FROM ($referSQL) AS t1 {$conditionSQL}";
    }

    /**
     * Execute drill sql.
     *
     * @param  string $object
     * @param  string $drillSQL
     * @access public
     * @return array
     */
    public function execDrillSQL($object, $drillSQL, $limit = 10)
    {
        $limitSQL = "SELECT * FROM ($drillSQL) AS t1 LIMIT $limit";
        $queryResult = $this->loadModel('bi')->querySQL($drillSQL, $limitSQL);

        $result = array();
        if($queryResult['result'] == 'success')
        {
            $result['data'] = $queryResult['rows'];
            $result['cols'] = $this->getDrillCols($object);
        }

        if($queryResult['result'] == 'fail') $result['error'] = $queryResult['message'];
        $result['status'] = $queryResult['result'];
        return $result;
    }

    /**
     * Parse query filter, then get drill result.
     *
     * @param  string $object
     * @param  string $whereSQL
     * @param  array  $filters
     * @param  array  $conditions
     * @param  bool   $emptyFilters
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getDrillResult($object, $whereSQL, $filters = array(), $conditions = array(), $emptyFilters = true, $limit = 10)
    {
        $drillSQL = $this->getDrillSQL($object, $whereSQL, $conditions);
        if(!empty($filters)) $drillSQL = $this->loadModel('bi')->processVars($drillSQL, $filters, $emptyFilters);
        return  $this->execDrillSQL($object, $drillSQL, $limit);
    }

    /**
     * Get drill datas.
     *
     * @param  object $pivotState
     * @param  object $drill
     * @param  array  $conditions
     * @param  array  $filterValues
     * @access public
     * @return array
     */
    public function getDrillDatas(object $pivotState, object $drill, array $conditions, array $filterValues = array()): array
    {
        $filters = $pivotState->setFiltersDefaultValue($filterValues);
        foreach($conditions as $index => $condition)
        {
            if(isset($condition['value'])) $conditions[$index]['value'] = " = '{$condition['value']}'";
        }

        $data   = array();
        $status = null;
        if($pivotState->isQueryFilter())
        {
            $queryResult = $this->getDrillResult($drill->object, $drill->whereSql, $filters, $conditions, false, 999999);

            $data   = $queryResult['data'];
            $status = $queryResult['status'];
        }
        else
        {
            $filters = $pivotState->convertFiltersToWhere($filters);

            foreach($conditions as $index => $condition)
            {
                if(!isset($condition['value'])) $conditions[$index]['value'] = $this->setConditionValueWithFilters($condition, $filters);
            }

            $drillSQL    = $this->getDrillSQL($drill->object, $drill->whereSql, $conditions);
            $queryResult = $this->loadModel('bi')->querySQL($drillSQL, $drillSQL);

            $data   = $queryResult['rows'];
            $status = $queryResult['result'];
        }

        if($status != 'success') return array();

        return $data;
    }

    /**
     * Process task datas in Drill modal.
     *
     * @param  array  $datas
     * @access public
     * @return array
     */
    public function processKanbanDatas(string $object, array $datas): array
    {
        $kanbans = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('kanban')->fetchPairs();

        if($object == 'story') $projectStory = $this->dao->select('story, project')->from(TABLE_PROJECTSTORY)->fetchPairs();

        foreach($datas as $data)
        {
            $projectID = 0;
            if($object == 'story')
            {
                $projectID = isset($projectStory[$data->id]) ? $projectStory[$data->id] : 0;
            }
            else
            {
                $projectID = zget($data, 'execution', 0);
            }

            if($projectID && isset($kanbans[$projectID])) $data->isModal = true;
        }

        return $datas;
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
