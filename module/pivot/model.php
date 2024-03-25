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
            $pivot->names = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');
            $pivot->descs = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');
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

            $pivot->used = $this->checkIFChartInUse($pivot->id, 'pivot', $screenList);
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

                        if(!empty($begin)) $begin = date('Y-m-d 00:00:00', strtotime($begin));
                        if(!empty($end))   $end   = date('Y-m-d 23:59:59', strtotime($end));

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
     * @param  string   $sql
     * @param  array    $filters
     * @access public
     * @return string
     */
    public function appendWhereFilterToSql(string $sql, array $filters): string
    {
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

        $sql = "select * from ($sql) tt" . $connectSQL;

        return $sql;
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
    public function mapRecordValueWithFieldOptions(array $records, array $fields, string $sql): array
    {
        $this->app->loadConfig('dataview');
        $records      = json_decode(json_encode($records), true);
        $fieldOptions = $this->getFieldsOptions($fields, $sql);
        foreach($records as $index => $record)
        {
            foreach($record as $field => $value)
            {
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

            foreach($sliceKeys as $sliceKey)
            {
                $columnRecord->$sliceKey = array('count' => 0, 'distinct' => array(), 'sum' => 0, 'avg' => array(), 'max' => array(), 'min' => array());
            }

            $columnRecords[$groupUnionKey] = $columnRecord;
        }

        return $columnRecords;
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
    public function processColumnStat(int $index, string $field, string $slice, string $stat, array $groups, array $records): array
    {
        $sliceRecords = $this->initSliceColumnRecords($index, $field, $slice, $groups, $records);
        foreach($records as $record)
        {
            $groupUnionKey = $this->getGroupsKey($groups, $record);
            $fieldKey  = $this->getSliceFieldKey($index, $slice, $field, $record);

            $sliceGroupRecord = $sliceRecords[$groupUnionKey];
            $value            = $record->$field;
            $floatValue       = is_numeric($value) ? (float)$value : 0;

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
            $sliceFields = array_keys((array)$sliceRecord);
            foreach($sliceFields as $sliceField)
            {
                /* 分组字段直接跳过。*/
                /* Skip the group field directly. */
                if(in_array($sliceField, $groups)) continue;

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
        }

        return $sliceRecords;
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
     * Calculate merge cell config.
     *
     * @param  array    $groups
     * @param  array    $records
     * @access public
     * @return array
     */
    public function calculateMergeCellConfig(array $groups, array $records)
    {
        $configs = array();
        foreach($groups as $index => $group)
        {
            if($index == 0)
            {
                $groupRecords = array();
                foreach($records as $record) $groupRecords[$record->$group][] = $record;
            }

            $haveNext = isset($groups[$index + 1]);
            $this->getColumnConfig($groupRecords, $configs, $groups, $index, $haveNext);
        }
        return $configs;
    }

    /**
     * Get column config to merge table cell.
     *
     * @param  array $groupRows
     * @param  array $configs
     * @param  array $groups
     * @param  int   $index
     * @param  bool  $haveNext
     * @access public
     * @return void
     *
     * Init $groupRows like this: array('people1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2' => ['create' => 'people2', 'product' => 'product2']])
     * The second time the function is executed, groupRows is passed in array('people1_product1' => [0 => ['create' => 'people1', 'product' => 'product1']], 'people2_product2' => ['create' => 'people2', 'product' => 'product2']])
     *
     * The key value of this array is unique;
     */
    public function getColumnConfig(array &$groupRows, array &$configs, array $groups, int $index, bool $haveNext): void
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
    public function genSheet(array $fields, array $settings, string $sql, array $filters, array $langs = array()): array
    {
        $groups = $this->getGroupsFromSettings($settings);
        $cols   = $this->generateTableCols($fields, $groups, $langs);

        /* Replace the variable with the default value. */
        $sql = $this->initVarFilter($filters, $sql);
        $sql = $this->trimSemicolon($sql);
        $sql = $this->appendWhereFilterToSql($sql, $filters);

        $records = $this->dao->query($sql)->fetchAll();
        $records = $this->mapRecordValueWithFieldOptions($records, $fields, $sql);

        $showColTotal = zget($settings, 'columnTotal', 'noShow');

        $mergeRecords  = array();

        if(isset($settings['columns']))
        {
            $columnSettings = $settings['columns'];
            foreach($columnSettings as $columnIndex => $columnSetting)
            {
                $columnShowOrigin = isset($columnSetting['showOrigin']) ? $columnSetting['showOrigin'] : false;
                $columnStat       = $columnSetting['stat'];
                $columnField      = $columnSetting['field'];
                $columnSlice      = zget($columnSetting, 'slice', 'noSlice');

                $cols = $this->getTableHeader($records, $columnSetting, $fields, $cols, $sql, $langs, $columnShowOrigin);

                if($columnShowOrigin)
                {
                    $columnRecords = $this->processColumnOriginal($columnIndex, $columnField, $groups, $records);
                    if($columnRecords) $columnRecords = $this->processShowData($columnRecords, $groups, $columnSetting, $showColTotal, $columnField . $columnIndex);

                    $columnSetting['records']    = $columnRecords;
                    $mergeRecords = $this->mergeOriginRecords(array($columnSetting), $mergeRecords);
                }
                elseif(!empty($columnStat))
                {
                    $columnRecords = $this->processColumnStat($columnIndex, $columnField, $columnSlice, $columnStat, $groups, $records);
                    if($columnRecords) $columnRecords = $this->processShowData($columnRecords, $groups, $columnSetting, $showColTotal, $columnField . $columnIndex);

                    $columnSetting['records']  = $columnRecords;
                    $mergeRecords = $this->mergeStatRecords(array($columnSetting), $groups, $mergeRecords);
                }
            }
        }

        $mergeRecords = $mergeRecords ? array_values($mergeRecords) : array();
        $mergeRecords = $this->orderByRecordsGroups($records, $mergeRecords, $groups);

        $data              = new stdclass();
        $data->groups      = $groups;
        $data->cols        = $cols;
        $data->array       = json_decode(json_encode($mergeRecords), true);
        if($showColTotal == 'sum') $this->processLastRow($data->array[count($data->array) - 1]);
        $data->columnTotal = isset($settings['columnTotal']) ? $settings['columnTotal'] : '';

        $configs = $this->calculateMergeCellConfig($groups, $mergeRecords);

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
            if($value == '$totalGroup$') $data[$key] = $this->lang->pivot->step2->total;
        }
    }

    /**
     * Gen sheet by origin sql.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  string $sql
     * @param  array  $filters
     * @param  array  $langs
     * @access public
     * @return string
     */
    public function genOriginSheet($fields, $settings, $sql, $filters, $langs = array())
    {
        $sql = $this->initVarFilter($filters, $sql);

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

        $this->app->loadClass('sqlparser', true);
        $parser    = new sqlparser($sql);
        $statement = $parser->statements[0];
        if(!$statement->limit)
        {
            $statement->limit = new stdclass();
            $statement->limit->offset   = 0;
            $statement->limit->rowCount = 99999999;
        }
        $sql = $statement->build();

        $columnSQL = "select * from ($sql) tt" . $connectSQL;
        $rows = $this->dao->query($columnSQL)->fetchAll();
        $rows = json_decode(json_encode($rows), true);

        $cols = array();
        $clientLang = $this->app->getClientLang();
        /* Build cols. */
        foreach($fields as $field)
        {
            $key = $field['field'];

            $col = new stdclass();
            $col->name    = $key;
            $col->isGroup = true;

            $fieldObject  = $field['object'];
            $relatedField = $field['field'];

            $colLabel = $key;
            if($fieldObject)
            {
                $this->app->loadLang($fieldObject);
                if(isset($this->lang->$fieldObject->$relatedField)) $colLabel = $this->lang->$fieldObject->$relatedField;
            }

            if(isset($langs[$key]) and !empty($langs[$key][$clientLang])) $colLabel = $langs[$key][$clientLang];
            $col->label = $colLabel;

            $cols[0][] = $col;
        }

        $fieldOptions = $this->getFieldsOptions($fields, $sql);
        foreach($rows as $key => $row)
        {
            foreach($row as $field => $value)
            {
                $optionList  = isset($fieldOptions[$field]) ? $fieldOptions[$field] : array();
                $row[$field] = isset($optionList[$value]) ? $optionList[$value] : $value;
            }

            $rows[$key] = $row;
        }

        $data = new stdclass();
        $data->cols  = $cols;
        $data->array = $rows;

        $configs = array_fill(0, count($rows), array_fill(0, count($fields), 1));

        return array($data, $configs);
    }

    /**
     * 初始化分组信息。
     * Init groups info.
     *
     * @param  array  $fields
     * @param  array  $settings
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function initGroups(array $fields, array $settings, array $langs): array
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
    public function processGroupRows(array $columns, string $sql, array $filters, array $groups, string $groupList, array $fields, string $showColTotal, array &$cols ,array $langs): array
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
    private function getMergeData(array $columnRows, array &$groupsRow)
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
        if($slice != 'noSlice' && !$showOrigin)
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
    public function getSysOptions($type, $object = '', $field = '', $source = '', $saveAs = '')
    {
        $this->loadModel('bi');
        $options = array('' => '');
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
                    $options = $this->bi->getDataviewOptions($object, $field);
                }
                break;
            case 'object':
                if($field)
                {
                    if(is_array($source))
                    {
                        $options = array();
                        foreach($source as $row) $options[$row->id] = $row->$field;
                    }
                    else
                    {
                        $options = $this->bi->getObjectOptions($object, $field);
                    }
                }
                break;
            case 'string':
            case 'number':
                if($field)
                {
                    $options = array();
                    if(is_array($source))
                    {
                        foreach($source as $row) $options["{$row->$field}"] = $row->$field;
                    }
                }
                break;
        }

        if(is_string($source) and $source)
        {
            if(in_array($type, array('string', 'number')))
            {
                $keyField   = $field;
                $valueField = $saveAs ? $saveAs : $field;
                $options = $this->bi->getOptionsFromSql($source, $keyField, $valueField);
            }
            elseif($saveAs)
            {
                $options = $this->bi->getOptionsFromSql($source, $field, $saveAs);
            }
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
     * Get field options.
     *
     * @param  array  $fieldSettings
     * @param  string $sql
     * @access public
     * @return array
     *
     */
    public function getFieldsOptions(array $fieldSettings, string $sql): array
    {
        $options = array();

        $sqlRecords = $this->dbh->query($sql)->fetchAll();

        foreach($fieldSettings as $key => $fieldSetting)
        {
            $type   = $fieldSetting['type'];
            $object = $fieldSetting['object'];
            $field  = $fieldSetting['field'];

            $source = $sql;
            if(in_array($type, array('string', 'number', 'date'))) $source = $sqlRecords;

            $options[$key] = $this->getSysOptions($type, $object, $field, $source);
        }

        return $options;
    }

    /**
     * Build table use data and rowspan.
     *
     * @param  object $data
     * @param  array  $configs
     * @param  int    $page
     * @access public
     * @return void
     *
     */
    public function buildPivotTable($data, $configs, $page = 0)
    {
        $width = 128;

        /* Init table. */
        $table  = "<div class='reportData'><table class='table table-condensed table-striped table-bordered table-fixed datatable' style='width: auto; min-width: 100%' data-fixed-left-width='400'>";

        $showOrigins = array();
        $hasShowOrigin = false;

        foreach($data->cols[0] as $col)
        {
            $colspan = zget($col, 'colspan', 1);
            $showOrigin = isset($col->showOrigin) ? $col->showOrigin : false;
            $colShowOrigin = array_fill(0, $colspan, $showOrigin);
            $showOrigins = array_merge($showOrigins, $colShowOrigin);
            if($showOrigin) $hasShowOrigin = true;
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

        $useColumnTotal = (!empty($data->columnTotal) and $data->columnTotal === 'sum');
        if($page) list($start, $end, $itemCount, $pageTotal) = $this->pagePivot($configs, $page, $useColumnTotal);

        for($i = 0; $i < count($data->array); $i ++)
        {
            $rowCount ++;

            if($page and ($i < $start or $i > $end)) continue;

            if($useColumnTotal and $rowCount == count($data->array)) continue;

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

        /* Add column total. 如果分页了，只在最后一页展示 */
        if($useColumnTotal and !empty($data->array) and (!$page or $page == $pageTotal))
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
        $table .= "</table></div>";

        if($page)
        {
            $recTotal  = $end - $start + 1;
            $leftPage  = $page - 1;
            $rightPage = $page + 1;

            if($recTotal) $table .= $this->getTablePager($itemCount, $leftPage, $rightPage, $page, $pageTotal);
        }

        echo $table;
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
