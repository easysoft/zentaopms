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
            $pivot->filters = $this->setFilterDefault($filters);
        }
        else
        {
            $pivot->filters = array();
        }

        return $this->processPivot($pivot);
    }

    /**
     * 时间占位符替换为实际的时间。
     * Replace time placeholder with actual time.
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
        foreach($pivots as $pivot) $this->completePivot($pivot, $screenList);

        if($isObject && isset($pivot->stage) && $pivot->stage == 'published') $this->processFieldSettings($pivot);

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
    public function processFieldSettings(object $pivot): void
    {
        $fieldSettings = $pivot->fieldSettings ?? $this->getFieldsFromPivot($pivot, 'fields', array(), true);
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

        $columns      = $this->loadModel('dataview')->getColumns($querySQL);
        $columnFields = array();
        foreach(array_keys(get_object_vars($columns)) as $type) $columnFields[$type] = $type;

        extract($this->chart->getTables($querySQL));

        /* 获取field的键值对以及相关联的对象。 */
        /* Get field key value pairs and related objects. */
        $moduleNames = $tables ? $this->dataview->getModuleNames($tables) : array();
        list($fieldPairs, $relatedObject) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames);

        $objectFields = array();
        foreach(array_keys($this->lang->dataview->objects) as $object) $objectFields[$object] = $this->dataview->getTypeOptions($object);

        /* 重建fieldSettings字段。 */
        /* Rebuild fieldSettings field. */
        $pivot->fieldSettings = $this->rebuildFieldSettings($fieldPairs, $columns, $relatedObject, $fieldSettings, $objectFields);
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
     * @param  array   $objectFields
     * @access private
     * @return object
     */
    public function rebuildFieldSettings(array $fieldPairs, object $columns, array $relatedObject, object $fieldSettings, array $objectFields): object
    {
        $fieldSettingsNew = new stdclass();

        foreach($fieldPairs as $index => $field)
        {
            $defaultType   = $columns->{$index};
            $defaultObject = $relatedObject[$index];

            if(isset($objectFields[$defaultObject][$index])) $defaultType = $objectFields[$defaultObject][$index]['type'] == 'object' ? 'string' : $objectFields[$defaultObject][$index]['type'];

            if(!isset($fieldSettings->{$index}))
            {
                /* 如果字段设置中没有该字段，则使用默认的配置。 */
                /* If the field is not set in the field settings, use the default value. */
                $fieldItem = new stdclass();
                $fieldItem->name   = $field;
                $fieldItem->object = $defaultObject;
                $fieldItem->field  = $index;
                $fieldItem->type   = $defaultType;

                $fieldSettingsNew->{$index} = $fieldItem;
            }
            else
            {
                /* 兼容旧版本的字段设置，当为空或者为布尔值时，使用默认值 */
                /* Compatible with old version of field settings, use default value when empty or boolean. */
                if(!isset($fieldSettings->{$index}->object) || is_bool($fieldSettings->{$index}->object) || strlen($fieldSettings->{$index}->object) == 0) $fieldSettings->{$index}->object = $defaultObject;

                /* 当字段设置中没有字段名时，使用默认的字段名配置。 */
                /* When there is no field name in the field settings, use the default field name configuration. */
                if(!isset($fieldSettings->{$index}->field) || strlen($fieldSettings->{$index}->field) == 0)
                {
                    $fieldSettings->{$index}->field  = $index;
                    $fieldSettings->{$index}->object = $defaultObject;
                    $fieldSettings->{$index}->type   = 'string';
                }

                $object = $fieldSettings->{$index}->object;
                $type   = $fieldSettings->{$index}->type;
                if($object == $defaultObject && $type != $defaultType) $fieldSettings->{$index}->type = $defaultType;

                $fieldSettingsNew->{$index} = $fieldSettings->{$index};
            }
        }

        return $fieldSettingsNew;
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
     * @access public
     * @return array
     */
    public function getProducts(string $conditions, string $storyType = 'story'): array
    {
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
    private function getWorkloadNoAssign(array $deptUsers, array $users, bool $canViewExecution): array
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
    private function getWorkLoadAssign(array $deptUsers, array $users, bool $canViewExecution, float $allHour): array
    {
        $tasks = $this->pivotTao->getAssignTask(array_keys($deptUsers));
        if(empty($tasks)) return array();

        /* 构建用户-项目-执行-任务数据结构。 */
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
    public function setExecutionName(object $execution, bool $canViewExecution): void
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
     * 获取用户的工作负载相关信息。
     * Get user's workload related information.
     *
     * @param  array   $projects
     * @param  array   $teamTasks
     * @param  float   $allHour
     * @access private
     * @return array
     */
    private function getUserWorkLoad(array $projects, array $teamTasks, float $allHour): array
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
        $filterFormat = array();
        if(empty($filters)) return array($sql, $filterFormat);

        foreach($filters as $filter)
        {
            $field = $filter['field'];

            if(isset($filter['from']) && $filter['from'] == 'query')
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
                        if(is_array($default)) $default = implode("', '", array_filter($default, function($val){return trim($val) != '';}));
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

                        if(!empty($begin) &&  empty($end)) $filterFormat[$field] = array('operator' => '>',       'value' => "'{$begin}'");
                        if( empty($begin) && !empty($end)) $filterFormat[$field] = array('operator' => '<',       'value' => "'{$end}'");
                        if(!empty($begin) && !empty($end)) $filterFormat[$field] = array('operator' => 'BETWEEN', 'value' => "'{$begin}' AND '{$end}'");
                        break;
                }
            }
        }

        return array($sql, $filterFormat);
    }

    /**
     * 生成透视表页面表格配置和相关数据。
     * Generate pivot page table configuration and related data.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $sql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function genSheet(array $fields, array $settings, string $sql, array $filters, array $langs = array()): array
    {
        $cols = $configs = array();
        list($groups, $groupList, $groupCol) = $this->initGroups($fields, $settings, $langs);
        array_push($cols, $groupCol);

        $groupsRow    = array();
        $showColTotal = zget($settings, 'columnTotal', 'noShow');
        if(isset($settings['columns'])) $groupsRow = $this->processGroupRows($settings['columns'], $sql, $filters, $groups, $groupList, $fields, $showColTotal, $cols, $langs);

        /* 获取单元格合并配置。 */
        /* Get cell merge configuration. */
        $this->getColumnConfig($groupsRow, $groups, 0, 0, $configs);

        /* 处理分组字段显示。 */
        /* Process group field display. */
        foreach($groups as $group)
        {
            $options = $this->getSysOptions($fields[$group]['type'], $fields[$group]['object'], $fields[$group]['field'], $sql);
            foreach($groupsRow as $row)
            {
                if(isset($row->{$group})) $row->{$group} = zget($options, $row->{$group});
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
     * 初始化分组信息。
     * Init groups info.
     *
     * @param  array   $fields
     * @param  array   $settings
     * @param  array   $langs
     * @access private
     * @return array
     */
    private function initGroups(array $fields, array $settings, array $langs): array
    {
        $groups = $sqlGroups = array();
        $condition  = !empty($settings['filterType']) && $settings['filterType'] == 'query';
        $clientLang = $this->app->getClientLang();

        foreach($settings as $key => $value)
        {
            if(strpos($key, 'group') !== false && $value) $groups[] = $value;
        }
        $groups = array_unique($groups);

        foreach($groups as $group) $sqlGroups[] = $condition ? "`$group`" : "tt.`$group`";
        $groupList = implode(',', $sqlGroups);

        $groupCol = array();
        foreach($groups as $group)
        {
            $col = new stdclass();
            $col->name    = $group;
            $col->isGroup = true;

            $fieldObject  = $fields[$group]['object'];
            $relatedField = $fields[$group]['field'];

            /* 如果有自定义的语言包，则使用自定义的语言包，否则使用系统默认的语言包。 */
            /* If there is a custom language pack, use the custom language pack, otherwise use the system default language pack. */
            $col->label = $this->getColLabelValue($group, $fieldObject, $relatedField, $clientLang, $langs);

            $groupCol[] = $col;
        }

        return array($groups, $groupList, $groupCol);
    }

    /**
     * 根据语言适配对应的列名。
     * Adapt the corresponding column name according to the language.
     *
     * @param  string  $field
     * @param  string  $fieldObject
     * @param  string  $relatedField
     * @param  string  $clientLang
     * @param  array   $langs
     * @access private
     * @return string
     */
    public function getColLabelValue(string $field, string $fieldObject, string $relatedField, string $clientLang, array $langs = array()): string
    {
        $colLabel = $field;
        if(isset($langs[$field][$clientLang]))
        {
            $colLabel = $langs[$field][$clientLang];
        }
        elseif($fieldObject)
        {
            $this->app->loadLang($fieldObject);
            if(isset($this->lang->{$fieldObject}->{$relatedField})) $colLabel = $this->lang->{$fieldObject}->{$relatedField};
        }

        return $colLabel;
    }

    /**
     * 初始化sql。
     * Init sql.
     *
     * @param  string  $sql
     * @param  array   $filters
     * @param  string  $groupList
     * @access private
     * @return array
     */
    private function initSql(string $sql, array $filters, string $groupList): array
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
     * @param  array   $filters
     * @access private
     * @return string
     */
    private function getConnectSQL(array $filters): string
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
     * 构建表格数据。
     * Build table data.
     *
     * @param  array   $columns
     * @param  string  $sql
     * @param  array   $filters
     * @param  array   $groups
     * @param  string  $groupList
     * @param  array   $fields
     * @param  string  $showColTotal
     * @param  array   $cols
     * @param  array   $langs
     * @access private
     * @return array
     */
    private function processGroupRows(array $columns, string $sql, array $filters, array $groups, string $groupList, array $fields, string $showColTotal, array &$cols ,array $langs): array
    {
        list($sql, $connectSQL, $groupSQL, $orderSQL) = $this->initSql($sql, $filters, $groupList);
        $number       = 0;
        $showOrigin   = !empty(array_filter(array_column($settings['columns'] ?? array(), 'showOrigin')));

        $groupsRow = array();
        foreach($columns as $column)
        {
            $columnShowOrigin = zget($column, 'showOrigin', '');
            if($columnShowOrigin) $column['slice'] = 'noSlice';

            $stat   = $column['stat'];
            $field  = $column['field'];
            $slice  = zget($column, 'slice', 'noSlice');
            $uuName = $field . $number;
            $number ++;

            /* 获取列的原始数据，并且根据原始数据生成表头，切片数据以及统计数据。 */
            /* Get the original data of the column, and generate the table header, slice data and statistics data based on the original data. */
            $columnRows = $this->getColumnRows($fields, $field, $sql, $connectSQL, $orderSQL, $uuName, $groupList, $groupSQL, $slice, $stat, $columnShowOrigin);
            $rowcount = array_fill(0, count($columnRows), 1);
            if($showOrigin && !$columnShowOrigin)
            {
                $countSQL = "select $groupList, count(tt.`$field`) as rowCount from ($sql) tt" . $connectSQL . $groupSQL . $orderSQL;
                $countRows = $this->dao->query($countSQL)->fetchAll();
                foreach($countRows as $key => $countRow) $rowcount[$key] = $countRow->rowCount;
            }
            $this->getTableHeader($columnRows, $column, $fields, $cols, $sql, $langs, $columnShowOrigin);
            if($slice != 'noSlice') $columnRows = $this->processSliceData($columnRows, $groups, $slice, $uuName);
            $columnRows = $this->processShowData($columnRows, $groups, $column, $showColTotal, $uuName);

            $this->getMergeData($columnRows, $groupsRow);
        }

        return $groupsRow;
    }

    /**
     * 获取原始指标数据。
     * Get original index data.
     *
     * @param  array   $fields
     * @param  string  $field
     * @param  string  $sql
     * @param  string  $connectSQL
     * @param  string  $orderSQL
     * @param  string  $uuName
     * @param  string  $groupList
     * @param  string  $groupSQL
     * @param  string  $slice
     * @param  string  $stat
     * @param  bool    $columnShowOrigin
     * @access private
     * @return array
     */
    private function getColumnRows(array $fields, string $field, string $sql, string $connectSQL, string $orderSQL, string $uuName, string $groupList, string $groupSQL, string $slice, string $stat, bool $columnShowOrigin): array
    {
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
                if($fields[$field]['type'] != 'number' && in_array($stat, array('avg', 'sum')))
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
        $columnSQL = str_replace('0000-00-00', '1970-01-01', $columnSQL);

        return $this->dao->query($columnSQL)->fetchAll();
    }

    /**
     * 合并数据。
     * Merge data.
     *
     * @param  array  $columnRows
     * @param  array  $groups
     * @param  string $slice
     * @param  string $uuName
     * @access private
     * @return array
     */
    public function getMergeData(array $columnRows, array &$groupsRow)
    {
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
     * 获取合并单元格的配置。
     * Get column config to merge table cell.
     *
     * @param  array  $groupRows
     * @param  array  $configs
     * @param  array  $groups
     * @param  int    $index
     * @access public
     * @return void
     *
     * Init $groupRows like this: array('people1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2' => ['create' => 'people2', 'product' => 'product2']])
     * The second time the function is executed, groupRows is passed in array('people1_product1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2_product2' => ['create' => 'people2', 'product' => 'product2']])
     *
     * The key value of this array is unique;
     */
    private function getColumnConfig($groupsRow, $groups, $index, $key, &$configs): void
    {
        if(!count($groupsRow)) return;
        if($index > count($groups) - 1) return;

        $start = 1;
        $group = $groups[$index];
        $groupRows = array_reduce($groupsRow, function($carry, $item)use($group){
            $carry[$item->{$group}][] = $item;
            return $carry;
        });

        foreach($groupRows as $groupRow)
        {
            $number = count($groupRow);
            $configs[$key][$index] = $number;
            $this->getColumnConfig($groupRow, $groups, $index + 1, $start- 1, $configs);
            $start += $number;
            $key   += $number;
        }
    }

    /**
     * 获取表头。
     * Get the header of the table.
     *
     * @param  array   $columnRows
     * @param  array   $column
     * @param  array   $fields
     * @param  array   $cols
     * @param  string  $sql
     * @param  array   $langs
     * @param  bool    $showOrigin
     * @access private
     * @return void
     */
    private function getTableHeader(array $columnRows, array $column, array $fields, array &$cols, string $sql, array $langs = array(), bool $showOrigin = false): void
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
        $colLabel     = $this->getColLabelValue($column['field'], $fieldObject, $relatedField, $this->app->getClientLang(), $langs);

        if(!$showOrigin)
        {
            $colLabel = str_replace('{$field}', $colLabel,                                        $this->lang->pivot->colLabel);
            $colLabel = str_replace('{$stat}',  zget($this->lang->pivot->step2->statList, $stat), $colLabel);
            if($showMode != 'default') $colLabel .= sprintf($this->lang->pivot->colShowMode, zget($this->lang->pivot->step2->showModeList, $showMode));
        }
        $col->label = $colLabel;

        $slice = zget($column, 'slice', 'noSlice');
        if($slice != 'noSlice')
        {
            $showTotal = zget($column, 'showTotal', 'noShow') === 'noShow';
            $this->processSliceHeader($cols, $col, $columnRows, $fields, $sql, $slice, $monopolize, $showTotal);
        }
        else
        {
            $col->rowspan = !isset($cols[1]) ? '1' : '2';
            $col->colspan = $monopolize ? 2 : 1;
            $cols[0][] = $col;
        }
    }

    /**
     * 处理切片表头。
     * Process slice header.
     *
     * @param  array   $cols
     * @param  object  $col
     * @param  array   $columnRows
     * @param  array   $fields
     * @param  string  $sql
     * @param  string  $slice
     * @param  string  $monopolize
     * @param  bool    $showTotal
     * @access private
     * @return void
     */
    private function processSliceHeader(array &$cols, object $col, array $columnRows, array $fields, string $sql, string $slice, string $monopolize, bool $showTotal): void
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
            $childCol->label   = $optionList[$field] ?? $field;
            $childCol->colspan = $monopolize ? 2 : 1;
            $cols[1][] = $childCol;
        }
        $col->colspan = count($sliceList);
        if($monopolize) $col->colspan *= 2;

        if(!$showTotal)
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

    /**
     * 处理需要展示的数据。
     * Process data to be show.
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
        $slice      = zget($column, 'slice',    'noSlice');
        $showMode   = zget($column, 'showMode', 'default');
        $showTotal  = $slice    == 'noSlice' ? 'noShow' : zget($column, 'showTotal',  'noShow');
        $monopolize = $showMode == 'default' ? ''       : zget($column, 'monopolize', '');

        $method = '';
        if(in_array($showMode, array('column', 'row', 'total')))
        {
            $method = 'process' . ucfirst($showMode) . 'ShowData';
        }
        elseif($showMode == 'default' && $showTotal == 'sum')
        {
            $method = 'processDefaultShowData';
        }

        if($method)
        {
            $params = array();
            $params['columnRows']   = $columnRows;
            $params['groups']       = $groups;
            $params['monopolize']   = $monopolize;
            $params['uuName']       = $uuName;
            $params['showColTotal'] = $showColTotal;
            if($method !== 'processDefaultShowData') $params['showTotal'] = $showTotal;


            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            list($_, $allTotal, $colTotal) = $this->getTotalStatistics($columnRows, $groups, $monopolize);
            return $this->rebuildColumnRows($columnRows, $groups, $showTotal, $uuName, $showMode, $allTotal, $colTotal);
        }
    }

    /**
     * 获取总计数据。
     * Get total statistics.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $monopolize
     * @access private
     * @return array
     */
    private function getTotalStatistics(array $columnRows, array $groups, string $monopolize): array
    {
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
                $allTotal         += (float)$value;
            }
        }

        return array($rowTotal, $allTotal, $colTotal);
    }

    /**
     * 处理模式为total的显示数据。
     * Process show data when the mode is total.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $monopolize
     * @param  string  $uuName
     * @access private
     * @return array
     */
    private function processTotalShowData(array $columnRows, array $groups, string $monopolize, string $uuName, string $showTotal, string $showColTotal): array
    {
        list($rowTotal, $allTotal, $colTotal) = $this->getTotalStatistics($columnRows, $groups, $monopolize);

        foreach($columnRows as $index => $row)
        {
            $columnRow = new stdclass();
            foreach($row as $field => $value)
            {
                if(in_array($field, $groups))
                {
                    $columnRow->{$field} = $value;
                    continue;
                }
                if($monopolize) $columnRow->{"self_$field"} = $value;
                $columnRow->{$field} = round((float)$value / $allTotal * 100, 2) . '%';
            }

            if($showTotal == 'sum')
            {
                if($monopolize) $columnRow->{"sum_self_$uuName"} = $rowTotal[$index];
                $columnRow->{"sum_$uuName"} = round((float)$rowTotal[$index] / $allTotal * 100, 2) . '%';
            }
            $columnRows[$index] = $columnRow;
        }

        if($showColTotal == 'sum') $this->rebuildColumnRows($columnRows, $groups, $showTotal, $uuName, 'total', $allTotal, $colTotal);

        return $columnRows;
    }

    /**
     * 处理模式为row的显示数据。
     * Process show data when the mode is row.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $monopolize
     * @param  string  $uuName
     * @access private
     * @return array
     */
    private function processRowShowData(array $columnRows, array $groups, string $monopolize, string $uuName, string $showTotal, string $showColTotal): array
    {
        list($rowTotal, $_, $colTotal) = $this->getTotalStatistics($columnRows, $groups, $monopolize);
        foreach($columnRows as $index => $row)
        {
            $columnRow = new stdclass();
            foreach($row as $field => $value)
            {
                if(in_array($field, $groups))
                {
                    $columnRow->{$field} = $value;
                    continue;
                }
                if($monopolize) $columnRow->{"self_$field"} = $value;
                $columnRow->{$field} = round((float)$value / $rowTotal[$index] * 100, 2) . '%';
            }
            if($showTotal == 'sum')
            {
                if($monopolize) $columnRow->{"sum_self_$uuName"} = $rowTotal[$index];
                $columnRow->{'sum_' . $uuName} = round((float)$rowTotal[$index] / $rowTotal[$index] * 100, 2) . '%';
            }
            $columnRows[$index] = $columnRow;
        }

        if($showColTotal == 'sum') $this->rebuildColumnRows($columnRows, $groups, $showTotal, $uuName, 'row', $rowTotal[0], $colTotal);

        return $columnRows;
    }

    /**
     * 处理模式为column的显示数据。
     * Process show data when the mode is column.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $monopolize
     * @param  string  $uuName
     * @access private
     * @return array
     */
    private function processColumnShowData(array $columnRows, array $groups, string $monopolize, string $uuName, string $showTotal, string $showColTotal): array
    {
        list($rowTotal, $allTotal, $colTotal) = $this->getTotalStatistics($columnRows, $groups, $monopolize);
        foreach($columnRows as $index => $row)
        {
            $columnRow = new stdclass();
            foreach($row as $field => $value)
            {
                if(in_array($field, $groups))
                {
                    $columnRow->{$field} = $value;
                    continue;
                }
                if($monopolize) $columnRow->{"self_$field"} = $value;
                $columnRow->{$field} = round((float)$value / $colTotal[$field] * 100, 2) . '%';
            }
            if($showTotal == 'sum')
            {
                if($monopolize) $columnRow->{"sum_self_$uuName"} = $rowTotal[$index];
                $columnRow->{'sum_' . $uuName} = round((float)$rowTotal[$index] / $allTotal * 100, 2) . '%';
            }
            $columnRows[$index] = $columnRow;
        }

        if($showColTotal == 'sum') $this->rebuildColumnRows($columnRows, $groups, $showTotal, $uuName, 'column', $allTotal, $colTotal);

        return $columnRows;
    }

    /**
     * 处理模式为default的显示数据。
     * Process show data when the mode is default.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $monopolize
     * @param  string  $uuName
     * @access private
     * @return array
     */
    private function processDefaultShowData(array $columnRows, array $groups, string $monopolize, string $uuName, string $showColTotal): array
    {
        list($rowTotal, $allTotal, $colTotal) = $this->getTotalStatistics($columnRows, $groups, $monopolize);
        foreach($columnRows as $index => $row) $row->{'sum_' . $uuName} = $rowTotal[$index];
        if($showColTotal == 'sum') $this->rebuildColumnRows($columnRows, $groups, 'sum', $uuName, 'default', $allTotal, $colTotal);

        return $columnRows;
    }

    /**
     * 重构列数据。
     * Rebuild column data.
     *
     * @param  array   $columnRows
     * @param  array   $groups
     * @param  string  $showTotal
     * @param  string  $uuName
     * @param  string  $showMode
     * @param  float   $allTotal
     * @param  array   $colTotal
     * @access private
     * @return array
     */
    private function rebuildColumnRows(array $columnRows, array $groups, string $showTotal, string $uuName, string $showMode, float $allTotal, array $colTotal): array
    {
        if(empty($columnRows)) return array();

        $colTotalRow = new stdClass();
        foreach($columnRows[0] as $field => $_)
        {
            if(in_array($field, $groups))
            {
                $colTotalRow->{$field} = $this->lang->pivot->step2->total;
            }
            else
            {
                if($showTotal == 'sum' && $field == "sum_{$uuName}")
                {
                    $colTotalRow->{$field} = $showMode == 'default' ? $allTotal : round((float)$allTotal / $allTotal * 100, 2) . '%';
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

                if($showMode == 'default') $colTotalRow->{$field} = $colTotal[$field];
                if($showMode == 'column')  $colTotalRow->{$field} = round((float)$colTotal[$field] / $colTotal[$field] * 100, 2) . '%';
                if(strpos(',total,row,', ",$showMode,") !== false) $colTotalRow->{$field} = round((float)$colTotal[$field] / $allTotal * 100, 2) . '%';
            }
        }
        $columnRows[] = $colTotalRow;

        return $columnRows;
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
     * 根据类型获取选项。
     * Get options by type.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @access public
     * @return array
     */
    public function getSysOptions(string $type, string $object = '', string $field = '', string $sql = ''): array
    {
        $options = array();
        switch($type)
        {
            case 'user':
                return $this->loadModel('user')->getPairs('noletter');
            case 'project':
                return $this->loadModel('project')->getPairsByProgram();
            case 'product':
            case 'execution':
                return $this->loadModel($type)->getPairs();
            case 'dept':
                return $this->loadModel('dept')->getOptionMenu(0);
            case 'project.status':
                $this->app->loadLang('project');
                return $this->lang->project->statusList;
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
                return $options;
            case 'object':
                if($field)
                {
                    $table = zget($this->config->objectTables, $object, '');
                    if($table) $options = $this->dao->select("id, {$field}")->from($table)->fetchPairs();
                }
                return $options;
            case 'string':
                if($field && $sql)
                {
                    $cols = $this->dbh->query($sql)->fetchAll();
                    foreach($cols as $col)
                    {
                        $data = $col->{$field};
                        $options[$data] = $data;
                    }
                }
                return $options;
        }
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
    public function setFilterDefault(array $filters): array
    {
        foreach($filters as &$filter)
        {
            if(!isset($filter['default']) || empty($filter['default'])) continue;
            if(is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }

        return $filters;
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
