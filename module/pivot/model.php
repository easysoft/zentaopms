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
    }

    /**
     * 判断是否有权限访问。
     * Check pivot access.
     *
     * @param  int    $pivotID
     * @access public
     * @return array
     */
    public function checkAccess($pivotID, $method = 'preview')
    {
        $viewableObjects = $this->bi->getViewableObject('pivot');
        if(!in_array($pivotID, $viewableObjects))
        {
            return $this->app->control->sendError($this->lang->pivot->accessDenied, helper::createLink('pivot', $method));
        }
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
        $viewableObjects = $this->bi->getViewableObject('pivot');
        foreach($pivots as $index => $pivot)
        {
            if(!in_array($pivot->id, $viewableObjects)) unset($pivots[$index]);
        }

        return array_values($pivots);
    }

    /*
     * 获取透视表数据。
     * Get pivot data by id.
     *
     * @param  int    $id
     * @access public
     * @return object|bool
     */
    public function getPivotDataByID($id)
    {
        $pivot = $this->pivotTao->fetchPivot($id);
        if(!$pivot) return false;
        return $pivot;
    }

    /*
     * 获取透视表。
     * Get pivot.
     *
     * @param  int         $pivotID
     * @param  bool        $processDateVar
     * @param  string      $filterStatus
     * @param  bool        $addDrills
     * @access public
     * @return object|bool
     */
    public function getByID(int $pivotID, bool $processDateVar = false, string $filterStatus = 'published', bool $addDrills = true): object|bool
    {
        $pivot = $this->pivotTao->fetchPivot($pivotID);
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
            $pivot->filters = $this->setFilterDefault($filters, $processDateVar);
        }
        else
        {
            $pivot->filters = array();
        }

        $this->completePivot($pivot);
        if($addDrills) $this->addDrills($pivot);

        // if(isset($pivot->stage) && $pivot->stage == 'published' && $this->app->methodName == 'preview') $this->processFieldSettings($pivot);

        return $pivot;
    }

    /*
     * 获取透视表某版本。
     * Get pivot by id and version.
     *
     * @param  int         $pivotID
     * @param  string      $version
     * @param  bool        $processDateVar
     * @param  bool        $addDrills
     * @access public
     * @return object|bool
     */
    public function getPivotSpec(int $pivotID, string $version, bool $processDateVar = false, bool $addDrills = true)
    {
        $pivot = $this->pivotTao->fetchPivot($pivotID, $version);
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
            $pivot->filters = $this->setFilterDefault($filters, $processDateVar);
        }
        else
        {
            $pivot->filters = array();
        }
        $this->completePivot($pivot);
        if($addDrills) $this->addDrills($pivot);

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
        if(!is_string($var) || $var === '') return '';

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

        foreach($pivots as $pivot)
        {
            $this->completePivot($pivot);
            if($isObject) $this->addDrills($pivot);
        }

        return $isObject ? $pivot : $pivots;
    }

    /**
     * Process name and desc of pivot.
     *
     * @param  object $pivot
     * @access private
     * @return void
     */
    public function processNameDesc(object $pivot): void
    {
        if(!empty($pivot->type)) return;

        $pivot->names = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');
        $pivot->descs = array('zh-cn' => '', 'zh-tw' => '', 'en' => '', 'de' => '', 'fr' => '');

        $clientLang = $this->app->getClientLang();

        if(!empty($pivot->name))
        {
            $pivot->names   = json_decode($pivot->name, true);
            $langNames      = empty($pivot->names) ? array() : array_filter($pivot->names);
            $firstName      = empty($langNames)    ? ''      : reset($langNames);
            $clientLangName = zget($pivot->names, $clientLang, '');
            $pivot->name    = empty($clientLangName) ? $firstName : $clientLangName;
        }

        if(!empty($pivot->desc))
        {
            $pivot->descs    = json_decode($pivot->desc, true);
            $langDescs       = empty($pivot->descs) ? array() : array_filter($pivot->descs);
            $firstDesc       = empty($langDescs)    ? ''      : reset($langDescs);
            $clientLangDesc  = zget($pivot->descs, $clientLang, '');
            $pivot->desc     = empty($clientLangDesc) ? $firstDesc : $clientLangDesc;
        }
    }

    /**
     * 完善透视表。
     * Complete pivot.
     *
     * @param  object $pivot
     * @access public
     * @return void
     */
    private function completePivot(object $pivot): void
    {
        if(!empty($pivot->settings)) $pivot->settings = json_decode($pivot->settings, true);

        $this->processNameDesc($pivot);
    }

    /**
     * 添加下钻信息到透视表。
     * Add drills to pivot.
     *
     * @param  object $pivot
     * @access public
     * @return void
     */
    public function addDrills(object $pivot): void
    {
        $settings = $pivot->settings;
        if(!is_array($settings) || !isset($settings['columns'])) return;
        $columns  = $settings['columns'];
        $drillFields = array_column($columns, 'field');
        $drills = $this->pivotTao->fetchPivotDrills($pivot->id, $pivot->version, $drillFields);
        foreach($columns as $index => $column) $pivot->settings['columns'][$index]['drill'] = zget($drills, $column['field']);
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
        $querySQL = $this->loadModel('bi')->parseSqlVars($sql, $filters);
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
                $taskGroups[$user][$project][$execution][$id]->left = round($taskGroups[$user][$project][$execution][$id]->left + $task->left, 2);
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
            $execution->executionName = $canViewExecution ? html::a(helper::createLink('execution', 'view', "executionID={$execution->executionID}"), $execution->executionName, '', "title='{$execution->executionName}'") : "<span title='{$execution->executionName}'>{$execution->executionName}</span>";
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

                    $totalHours = round($totalHours + $task->left, 2);
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

        $filters = $this->processQueryFilterDefaults($filters);
        $currentFilter = current($filters);
        $isQueryFilter = (isset($currentFilter['from']) && $currentFilter['from'] == 'query');

        $filterFormat = $isQueryFilter ? array_values($filters) : array();
        foreach($filters as $filter)
        {
            $field = $filter['field'];

            if($isQueryFilter)
            {
                $queryDefault = '';
                if(isset($filter['default']))
                {
                    $queryDefault = $filter['default'];
                    if($filter['type'] == 'date' || $filter['type'] == 'datetime') $queryDefault = $this->processDateVar($filter['default']);
                    if($filter['type'] == 'datetime') $queryDefault .= ':00.000000000';
                    if($filter['type'] == 'multipleselect' && is_array($filter['default'])) $queryDefault = implode("','", $filter['default']);
                }

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
        return trim($sql, " ;");
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

        if($connectSQL) $sql = "select * from ( $sql ) tt" . $connectSQL;

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
     * Filter fields with settings.
     *
     * @param  array $fields
     * @param  array $groups
     * @param  array $columns
     * @access public
     * @return array
     */
    public function filterFieldsWithSettings(array $fields, array $groups, array $columns): array
    {
        $filteredFields = array();
        $settingFields  = $groups;

        foreach($columns as $column)
        {
            $slice = zget($column, 'slice', 'noSlice');
            $settingFields[] = $column['field'];
            if($slice != 'noSlice') $settingFields[] = $slice;
        }

        $settingFields = array_unique($settingFields);
        foreach($settingFields as $field)
        {
            if(!isset($filteredFields[$field]) && isset($fields[$field])) $filteredFields[$field] = $fields[$field];
        }

        return $filteredFields;
    }

    /**
     * Map record value with field options.
     *
     * @param  array    $records
     * @param  array    $fields
     * @access public
     * @return array
     */
    public function mapRecordValueWithFieldOptions(array $records, array $fields, string $driver): array
    {
        $this->app->loadConfig('dataview');
        $fieldOptions = $this->getFieldsOptions($fields, $records, $driver);
        $records      = json_decode(json_encode($records), true);
        foreach($records as $index => $record)
        {
            foreach($record as $field => $value)
            {
                if(!isset($fields[$field])) continue;

                $value = is_string($value) ? str_replace('"', '', htmlspecialchars_decode($value)) : $value;
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
                $record[$field] = is_string($record[$field]) ? str_replace('"', '', htmlspecialchars_decode($record[$field])) : $record[$field];
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

            if(isset($langs[$group]) && !empty($langs[$group][$clientLang])) $colLabel = $langs[$group][$clientLang];
            $col->label = $colLabel;

            $cols[0][] = $col;
        }

        return $cols;
    }

    /**
     * Get show col position.
     *
     * @param  array       $settings
     * @access public
     * @return string noShow | bottom | row | all
     */
    public function getShowColPosition(array|object $settings): string
    {
        $columnTotal    = zget($settings, 'columnTotal', 'noShow');
        $columnPosition = zget($settings, 'columnPosition', 'bottom');

        if($columnTotal == 'noShow') return 'noShow';
        return $columnPosition;
    }

    /**
     * Check whether showColPosition should show last row.
     *
     * @param  string $showColPosition
     * @access public
     * @return bool
     */
    public function isShowLastRow(string $showColPosition): bool
    {
        return in_array($showColPosition, array('bottom', 'all'));
    }

    /**
     * 计算列的统计值。
     * Calculate column statistics.
     *
     * @param  array $records
     * @param  string $statistic
     * @param  string $field
     * @access public
     * @return mixed
     */
    public function columnStatistics(array $records, string $statistic, string $field): mixed
    {
        $values = array_column($records, $field);
        $numericValues = array_map(function($value)
        {
            return is_numeric($value) ? floatval($value) : 0;
        }, $values);

        if($statistic == 'count')    return count($numericValues);
        if($statistic == 'sum')      return round(array_sum($numericValues), 2);
        if($statistic == 'avg')      return round(array_sum($numericValues) / count($numericValues), 2);
        if($statistic == 'min')      return min($numericValues);
        if($statistic == 'max')      return max($numericValues);
        if($statistic == 'distinct') return count(array_unique($values));
    }

    /**
     * 行数据转树。
     * Convert row data to tree.
     *
     * @param  array $data
     * @access public
     * @return array|string
     */
    public function getGroupTreeWithKey(array $data): array|string
    {
        $first = reset($data);
        if(!isset($first['groups'])) return $first['groupKey'];

        $tree = array();
        foreach($data as $value)
        {
            $groups = $value['groups'];
            $parentKey = array_shift($groups);
            if(!isset($tree[$parentKey])) $tree[$parentKey] = array();
            $value['groups'] = $groups;
            if(count($groups) == 0) unset($value['groups']);
            $tree[$parentKey][] = $value;
        }

        foreach($tree as $key => $value) $tree[$key] = $this->getGroupTreeWithKey($value);

        return $tree;
    }

    /**
     * 获取单元格数据。
     * Get cell data.
     *
     * @param  string $key
     * @param  array $data
     * @access public
     * @return array
     */
    public function formatCellData(string $key, array $data): array
    {
        if(!isset($data[$key])) return array();

        $cellData = $data[$key];
        foreach($cellData as $colKey => $colValue)
        {
            if(is_scalar($colValue))
            {
                $cellData[$colKey] = array('value' => $colValue);
            }
            else
            {
                $value = $colValue['value'];
                $colValue['value'] = is_scalar($value) ? $value : '/';
                $cellData[$colKey] = $colValue;
            }
        }

        return $cellData;
    }

    /**
     * 计算列的总计值。
     * Calculate column total.
     *
     * @param  array $data
     * @access public
     * @return array
     */
    public function getColumnSummary(array $data, string $totalKey): array
    {
        $summary = array();
        foreach($data as $columns)
        {
            foreach($columns as $colKey => $colValue)
            {
                if(!isset($summary[$colKey]))
                {
                    $summary[$colKey] = $colValue;
                }
                else
                {
                    $isGroup   = zget($colValue, 'isGroup', 1);
                    $value     = zget($colValue, 'value', '');
                    $isNumeric = is_numeric($value);

                    $summary[$colKey]['value'] = !$isGroup && $isNumeric ? $summary[$colKey]['value'] + $value : $value;
                }
            }
        }

        $summary[$totalKey] = array('value' => '$total$');
        /* 删除汇总行的下钻配置。*/
        /* Delete drilldown config of summary row. */
        foreach($summary as $key => $value)
        {
            if(isset($value['value']) && is_numeric($value['value'])) $summary[$key]['value'] = round($summary[$key]['value'], 2);
            if(isset($value['drillFields']))
            {
                unset($summary[$key]['drillFields']);
            }
        }

        return $summary;
    }

    /**
     * 添加行总计到树数据中。
     * Add row summary to tree data.
     *
     * @param  array $groupTree
     * @param  array $data
     * @param  array $groups
     * @param  int   $currentGroup
     * @access public
     * @return array
     */
    public function addRowSummary(array $groupTree, array $data, array $groups, int $currentGroup = 0): array
    {
        $first = reset($groupTree);
        if(is_scalar($first))
        {
            $groupData = array();
            $rows      = array();
            foreach($groupTree as $groupKey)
            {
                $groupData[$groupKey] = $this->formatCellData($groupKey, $data);
                $rows[$groupKey]      = $data[$groupKey];
            }
            return array('rows' => $rows, 'summary' => $this->getColumnSummary($groupData, $groups[$currentGroup]));
        }

        $rows = array();
        foreach($groupTree as $key => $children) $rows[$key] = $this->addRowSummary($children, $data, $groups, $currentGroup + 1);
        $groupData = array_column($rows, 'summary');

        return array('rows' => $rows, 'summary' => $this->getColumnSummary($groupData, $groups[$currentGroup]));
    }

    /**
     * 去除数据中的额外信息，只保留单元格数据。
     * Remove extra info from data, only keep cell data.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function pureCrystalData(array $records): array
    {
        $pureData = array();
        foreach($records as $key => $record)
        {
            $columns = $record['columns'];
            $groups  = $record['groups'];
            $pureData[$key] = $groups;
            foreach($columns as $colKey => $colValue)
            {
                $cellData = $colValue['cellData'];
                if(isset($colValue['rowTotal'])) $cellData['total'] = $colValue['rowTotal'];
                if(isset($cellData['value']))
                {
                    $pureData[$key][$colKey] = $cellData;
                }
                else
                {
                    foreach($cellData as $sliceKey => $sliceValue) $pureData[$key][$colKey . '_' . $sliceKey] = $sliceValue;
                }
            }
        }

        return $pureData;
    }

    /**
     * 拍平切片列数据。
     * Flatten slice column data.
     *
     * @param  array  $row
     * @access public
     * @return array
     */
    public function flattenRow(array $row): array
    {
        $record = array();
        foreach($row as $colKey => $cell)
        {
            if(is_scalar($cell))
            {
                $record[$colKey] = array('value' => $cell);
            }
            elseif(isset($cell['value']))
            {
                $record[$colKey] = $cell;
            }
        }

        return $record;
    }

    /**
     * 拍平透视表树结构数据。
     * Flatten pivot table tree structure data.
     *
     * @param  array  $crystalData
     * @param  bool   $withGroupSummary
     * @access public
     * @return array
     */
    public function flattenCrystalData(array $crystalData, bool $withGroupSummary = false): array
    {
        $first = reset($crystalData);
        if(!isset($first['rows']))
        {
            $records = array();
            foreach($crystalData as $row) $records[] = $this->flattenRow($row);
            return $records;
        }

        $records = array();
        foreach($crystalData as $value)
        {
            $groupRecords = $this->flattenCrystalData($value['rows'], $withGroupSummary);
            if($withGroupSummary && isset($value['summary'])) $groupRecords[] = $this->flattenRow($value['summary']);
            $records = array_merge($records, $groupRecords);
        }

        return $records;
    }

    /**
     * 处理行合并单元格。
     * Process row span cell.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function processRowSpan(array $records, array $groups): array
    {
        $lastGroupValue = array();
        foreach($groups as $group) $lastGroupValue[$group] = '';

        /* 定义内部函数：获取当前行数据的分组值。*/
        /* Define internal function: get current row data's group value. */
        $getGroupValue = function($record, $key, $index) use ($groups)
        {
            $value = array($record[$key]['value']);
            $index -= 1;
            while($index >= 0)
            {
                $value[] = $record[$groups[$index]]['value'];
                $index -= 1;
            }

            return $value;
        };

        $groupsRowSpan = array();
        foreach($records as $index => $record)
        {
            $rowSpan = 1;
            foreach($record as $colKey => $cell)
            {
                if(!isset($cell['value']) || !is_array($cell['value'])) continue;
                $rowSpan = max(count($cell['value']), $rowSpan);
            }

            foreach($record as $colKey => $cell)
            {
                $record[$colKey]['rowSpan'] = is_scalar($cell['value']) ? $rowSpan : 1;
            }
            $records[$index] = $record;

            foreach($groups as $groupIndex => $group)
            {
                $groupValue    = $getGroupValue($record, $group, $groupIndex);
                $groupValueStr = implode('_', $groupValue);

                if($groupValue[0] !== '$total$' && $groupValueStr === $lastGroupValue[$group] && isset($groupsRowSpan[$group]))
                {
                    $groupRowSpan = array_pop($groupsRowSpan[$group]);
                    $groupRowSpan['index'][] = $index;
                    $groupRowSpan['rowSpan'] += $rowSpan;
                    $groupsRowSpan[$group][] = $groupRowSpan;
                }
                else
                {
                    $groupsRowSpan[$group][] = array('index' => array($index), 'rowSpan' => $rowSpan);
                }
                $lastGroupValue[$group] = $groupValueStr;
            }
        }

        foreach($groupsRowSpan as $group => $groupRowSpans)
        {
            foreach($groupRowSpans as $groupRowSpan)
            {
                $indexes = $groupRowSpan['index'];
                foreach($indexes as $index)
                {
                    $records[$index][$group]['rowSpan'] = $groupRowSpan['rowSpan'];
                }
            }
        }

        return $records;
    }

    /**
     * 计算行汇总值。
     * Calculate row total.
     *
     * @param  array $row
     * @access public
     * @return array
     */
    public function getRowTotal(array $row): array
    {
        $rowTotal = array();
        foreach($row as $cell)
        {
            if(!isset($cell['percentage'])) continue;
            list(,,,, $columnKey) = $cell['percentage'];
            if(!isset($rowTotal[$columnKey])) $rowTotal[$columnKey] = 0;
            $rowTotal[$columnKey] += $cell['value'];
        }

        return $rowTotal;
    }

    /**
     * 计算百分比值。
     * Calculate percentage.
     *
     * @param  array $row
     * @param  array $rowTotal
     * @param  array $columnTotal
     * @access public
     * @return array
     */
    public function setPercentage(array $row, array $rowTotal, array $columnTotal): array
    {
        foreach($row as $key => $cell)
        {
            if(!isset($cell['percentage'])) continue;
            list(,,$showMode,, $columnKey) = $cell['percentage'];
            if($showMode == 'row')    $cell['percentage'][1] = $rowTotal[$columnKey];
            if($showMode == 'column') $cell['percentage'][1] = $columnTotal[$key]['value'];
            if($showMode == 'total')
            {
                $total = 0;
                foreach($columnTotal as $column)
                {
                    if(!isset($column['percentage'])) continue;
                    $percentage = $column['percentage'];
                    if($percentage[4] === $columnKey) $total += $column['value'];
                }
                $cell['percentage'][1] = $total;
            }

            $cell['percentage'][0] = $cell['value'];
            $row[$key] = $cell;
        }

        return $row;
    }

    /**
     * 处理百分比值。
     * Process percentage.
     *
     * @param  array $crystalData
     * @param  array $allSummary
     * @access public
     * @return array
     */
    public function processPercentage(array $crystalData, array $allSummary): array
    {
        $rows    = $crystalData['rows'];
        $summary = $crystalData['summary'];

        foreach($rows as $key => $row)
        {
            if(isset($row['rows']))
            {
                $rows[$key] = $this->processPercentage($row, $allSummary);
            }
            else
            {
                $rowTotal = $this->getRowTotal($row);
                $rows[$key] = $this->setPercentage($row, $rowTotal, $allSummary);
            }
        }

        $rowTotal = $this->getRowTotal($summary);
        $summary = $this->setPercentage($summary, $rowTotal, $allSummary);

        return array('rows' => $rows, 'summary' => $summary);
    }

    /**
     * 对数据进行分组。
     * Group records.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function groupRecords(array $records, array $groups): array
    {
        $groupsData = array();
        foreach($records as $record)
        {
            $key = $this->getGroupsKey($groups, $record);
            if(!isset($groupsData[$key])) $groupsData[$key] = array();
            $groupsData[$key][] = $record;
        }

        return $groupsData;
    }

    /**
     * 设置切片列去重后的值。
     * Set unique slices.
     *
     * @param  array $records
     * @param  array $setting
     * @access public
     * @return array
     */
    public function setUniqueSlices(array $records, array $setting): array
    {
        static $slices = array();

        $slice = zget($setting, 'slice', 'noSlice');
        if($slice == 'noSlice') return $setting;
        if(isset($slices[$slice]))
        {
            $setting['uniqueSlices'] = $slices[$slice];
            return $setting;
        }

        $uniqueSlices = array();
        foreach($records as $record)
        {
            if(!isset($uniqueSlices[$record->$slice])) $uniqueSlices[$record->$slice] = $record;
        }
        $slices[$slice] = $uniqueSlices;
        $setting['uniqueSlices'] = $uniqueSlices;
        return $setting;
    }

    /**
     * 根据字段的值过滤记录。
     * Filter records by field value.
     *
     * @param  array $records
     * @param  string $field
     * @access public
     * @return array
     */
    public function getSliceRecords(array $records, string $field): array
    {
        $sliceRecords = array();
        foreach($records as $record)
        {
            if(!isset($sliceRecords[$record->$field])) $sliceRecords[$record->$field] = array();
            $sliceRecords[$record->$field][] = $record;
        }

        return $sliceRecords;
    }

    /**
     * 计算单元格数据。
     * Calculate cell data.
     *
     * @param  string $columnKey
     * @param  array $records
     * @param  array $setting
     * @access public
     * @return array
     */
    public function getCellData(string $columnKey, array $records, array $setting): array
    {
        $field      = zget($setting, 'field', '');
        $showOrigin = zget($setting, 'showOrigin', 0);

        if($showOrigin) return array('value' => array_column($records, $field), 'isGroup' => false);

        $stat       = zget($setting, 'stat', 'count');
        $slice      = zget($setting, 'slice', 'noSlice');
        $showMode   = zget($setting, 'showMode', 'default');
        $showTotal  = zget($setting, 'showTotal', 'noShow');
        $monopolize = zget($setting, 'monopolize', 0);
        $isSlice    = $slice != 'noSlice';

        if(!$isSlice)
        {
            $value = $this->columnStatistics($records, $stat, $field);
            $cell  = array('value' => $value, 'isGroup' => false);

            if($showMode == 'default') return $cell;
            $cell['percentage'] = array($value, 1, $showMode, $monopolize, $columnKey);

            return $cell;
        }

        /* 处理切片列的情况。 */
        /* Handle the slice column situation. */
        $uniqueSlices = zget($setting, 'uniqueSlices', array());
        $cell         = array();
        $sliceRecords = $this->getSliceRecords($records, $slice);
        foreach($uniqueSlices as $sliceRecord)
        {
            $sliceValue   = $sliceRecord->$slice;
            $sliceKey     = "{$slice}_{$sliceValue}";

            $value = $this->columnStatistics(zget($sliceRecords, $sliceValue, array()), $stat, $field);

            $sliceCell = array('value' => $value, 'drillFields' => array($slice => $sliceRecord->{$slice . '_origin'}), 'isGroup' => false);
            if($showMode != 'default') $sliceCell['percentage'] = array($value, 1, $showMode, $monopolize, $columnKey);

            $cell[$sliceKey] = $sliceCell;
        }

        if($showTotal != 'noShow')
        {
            $value = array_sum(array_column($cell, 'value'));
            $totalCell = array('value' => $value, 'isGroup' => false);
            if($showMode != 'default') $totalCell['percentage'] = array($value, 1, $showMode, $monopolize, "rowTotal_{$columnKey}");
            $cell['total'] = $totalCell;
        }

        return $cell;
    }

    /**
     * 添加下钻字段信息。
     * Add drill fields information.
     *
     * @param  array $cell
     * @param  array $drillFields
     * @access public
     * @return array
     */
    public function addDrillFields(array $cell, array $drillFields): array
    {
        if(isset($cell['value']))
        {
            if(!isset($cell['drillFields'])) $cell['drillFields'] = array();
            $cell['drillFields'] = array_merge($cell['drillFields'], $drillFields);
            return $cell;
        }

        foreach($cell as $sliceKey => $sliceCell)
        {
            if($sliceKey == 'total') continue;
            $cell[$sliceKey] = $this->addDrillFields($sliceCell, $drillFields);
        }

        return $cell;
    }

    /**
     * 根据列配置，计算透视表数据。
     * Calculate pivot table data.
     *
     * @param  array $groups
     * @param  array $records
     * @param  array $settings
     * @access public
     * @return array
     */
    public function processCrystalData(array $groups,array $records, array $settings): array
    {
        $crystalData    = array();
        $columnSettings = $settings['columns'];
        $groupRecords   = $this->groupRecords($records, $groups);
        foreach($groupRecords as $key => $data)
        {
            $record              = reset($data);
            $groupValues         = array();
            $groupOriginalValues = array();
            foreach($groups as $group)
            {
                $groupValues[$group] = $record->$group;
                $groupOriginalValues[$group] = $record->{$group . '_origin'};
            }

            $columns = array();
            foreach($columnSettings as $colIndex => $setting)
            {
                $setting   = $this->setUniqueSlices($records, $setting);
                $field     = zget($setting, 'field', '');
                $columnKey = "{$field}{$colIndex}";

                $cellData = $this->getCellData($columnKey, $data, $setting);
                $cellData = $this->addDrillFields($cellData, $groupOriginalValues);

                $columns[$columnKey] = array('setting' => $setting, 'cellData' => $cellData);
            }

            $crystalData[$key] = array('groups' => $groupValues, 'groupKey' => $key, 'columns' => $columns);
        }

        return $crystalData;
    }

    /**
     * 处理透视表数据为可以显示的格式。
     * Process pivot table data for display.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function processRecordsForDisplay(array $records): array
    {
        $roundIfMoreThanTwoDecimals = function($number)
        {
            if(!is_numeric($number)) return $number;

            $number = (float)$number;
            if(floor($number) === $number) return $number;

            $decimalPart = explode('.', strval($number));
            if(isset($decimalPart[1]) && strlen($decimalPart[1]) > 2) return $number;
            return $number;
        };

        $values = array();
        foreach($records as $record)
        {
            $row        = array();
            $arrayValue = false;
            foreach($record as $colKey => $cell)
            {
                $cellValue = $cell['value'] === '$total$' ? $this->lang->pivot->total : $cell['value'];
                if(is_array($cellValue)) $arrayValue = $cellValue;

                $cellValue = $roundIfMoreThanTwoDecimals($cellValue);
                $row[$colKey] = $cellValue;
                if(isset($cell['percentage']))
                {
                    list($number, $total,, $monopolize) = $cell['percentage'];
                    if($monopolize) $colKey .= '_percentage';
                    if(!$total) $total = 100;
                    $row[$colKey] = round($number / $total * 100, 2) . '%';
                }
            }

            if(is_array($arrayValue))
            {
                foreach(array_keys($arrayValue) as $index)
                {
                    $flattenValue = array();
                    foreach($row as $key => $value)
                    {
                        $value = is_scalar($value) ? $value : $value[$index];
                        $flattenValue[$key] = $value;
                    }
                    $values[] = $flattenValue;
                }
            }
            else
            {
                $values[] = $row;
            }
        }

        return $values;
    }

    /**
     * 获取合并单元格配置。
     * Get row span config.
     *
     * @param  array $records
     * @access public
     * @return array
     */
    public function getRowSpanConfig(array $records): array
    {
        $configs = array();
        foreach($records as $record)
        {
            $arrayValue = false;
            foreach($record as $cell)
            {
                if(is_array($cell['value'])) $arrayValue = $cell['value'];
            }

            if(!is_array($arrayValue)) $arrayValue = array(1);
            $configs = array_merge($configs, array_fill(0, count($arrayValue), array_column($record, 'rowSpan')));
        }
        return $configs;
    }

    /**
     * 获取下钻字段配置。
     * Get drill fields config.
     *
     * @param  array $records
     * @param  array $groups
     * @access public
     * @return array
     */
    public function getDrillsFromRecords(array $records, array $groups): array
    {
        $drills = array();
        foreach($records as $record)
        {
            $groupKey = $this->getGroupsKey($groups, (object)$record);
            if(!isset($drills[$groupKey])) $drills[$groupKey] = array('drillFields' => array());
            foreach($record as $colKey => $cell)
            {
                if(isset($cell['drillFields'])) $drills[$groupKey]['drillFields'][$colKey] = $cell['drillFields'];
            }
        }

        return $drills;
    }

    /**
     * 处理查询过滤器的默认值。
     * Process query filter defaults.
     *
     * @param  array|false $filters
     * @access public
     * @return array
     */
    public function processQueryFilterDefaults(array|false $filters): array|false
    {
        if(!is_array($filters)) return $filters;
        $options = array();
        foreach($filters as $index => $filter)
        {
            if(empty($filter['default'])) continue;
            if(!isset($filter['from']) || $filter['from'] != 'query') continue;
            if($filter['type'] !== 'multipleselect') continue;

            $type       = $filter['type'];
            $typeOption = $filter['typeOption'];
            if(strpos($type, 'select') !== false && !isset($options[$typeOption])) $options[$typeOption] = $this->getSysOptions($typeOption);
            $filters[$index]['default'] = array_intersect($filter['default'], array_keys($options[$typeOption]));
        }

        return $filters;
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

        $data         = new stdclass();
        $data->groups = $groups;
        $data->cols   = $cols;
        $data->array  = array();
        $data->drills = array();

        if(!isset($settings['columns'])) return array($data, array());

        $filters = $this->processQueryFilterDefaults($filters);
        /* Replace the variable with the default value. */
        $sql = $this->bi->processVars($sql, (array)$filters);
        $sql = $this->trimSemicolon($sql);
        $sql = $this->appendWhereFilterToSql($sql, $filters, $driver);

        $records = $this->bi->queryWithDriver($driver, $sql);
        $settingFields = $this->filterFieldsWithSettings($fields, $groups, $settings['columns']);
        $records = $this->mapRecordValueWithFieldOptions($records, $settingFields, $driver);

        if(empty($records)) return array($data, array());

        foreach($settings['columns'] as $columnSetting)
        {
            $cols = $this->getTableHeader($records, $columnSetting, $fields, $cols, $sql, $langs, $driver);
        }

        /* 根据列配置和分组配置，计算透视表数据。*/
        /* Calculate crystal data based on column settings and group settings. */
        $crystalData = $this->processCrystalData($groups, $records, $settings);

        /* 将扁平的透视表数据转换成树形结构。*/
        /* Convert flattened pivot table data to tree structure. */
        $groupTree = $this->getGroupTreeWithKey($crystalData);

        /* 净化处理透视表数据中的额外信息，只留下与单元格数据相关的信息。*/
        /* Clean up the extra information in pivot table data. */
        $crystalData = $this->pureCrystalData($crystalData);

        /* 基于各级分组，计算每个分组的总计数据行。*/
        /* Calculate total data rows based on each group. */
        $crystalData = $this->addRowSummary($groupTree, $crystalData, $groups);

        /* 计算百分比的值。*/
        /* Calculate percentage values. */
        $crystalData = $this->processPercentage($crystalData, $crystalData['summary']);

        /* 将树形结构转换成扁平的透视表数据。*/
        /* Convert tree structure to flattened pivot table data. */
        $columnPosition = $this->getShowColPosition($settings);
        $showGroupTotal = in_array($columnPosition, array('row', 'all'));
        $showAllTotal   = in_array($columnPosition, array('bottom', 'all'));
        $records = $this->flattenCrystalData($crystalData['rows'], $showGroupTotal);
        if($showAllTotal) $records[] = $this->flattenRow($crystalData['summary']);

        /* 计算行合并单元格的配置。*/
        /* Calculate row span config. */
        $records = $this->processRowSpan($records, $groups);

        $data->cols         = $cols;
        $data->array        = $this->processRecordsForDisplay($records);
        $data->drills       = $this->getDrillsFromRecords($records, $groups);
        $data->showAllTotal = $showAllTotal;

        $configs = $this->getRowSpanConfig($records);

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
     * Check is filters all default empty.
     *
     * @param  array  $data
     * @access public
     * @return void
     */
    public function isFiltersAllEmpty($filters)
    {
        return !empty($filters) && empty(array_filter(array_column($filters, 'default')));
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

        $dbh          = $this->app->loadDriver($driver);
        $rows         = $dbh->query($sql)->fetchAll();
        $rows         = $this->filterSpecialChars($rows);
        $fieldOptions = $this->getFieldsOptions($fields, $rows);

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
    public function getTableHeader($columnRows, $column, $fields, $cols, $sql, $langs = array(), $driver = 'mysql')
    {
        $stat       = zget($column, 'stat', '');
        $showMode   = zget($column, 'showMode', 'default');
        $monopolize = $showMode == 'default' ? '' : zget($column, 'monopolize', '');
        $showOrigin = (bool)zget($column, 'showOrigin', 0);

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
                $childCol->name       = 'sum';
                $childCol->isGroup    = false;
                $childCol->label      = $this->lang->pivot->stepDesign->total;
                $childCol->colspan    = $monopolize ? 2 : 1;
                $childCol->isDrilling = $isDrilling;
                $childCol->drillField = $drillField;
                $childCol->condition  = $condition;
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
        foreach($groups as $group) $groupsKey[] = is_scalar($record->$group) ? $record->$group : $record->$group['value'];

        return implode('_', $groupsKey);
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
                $options = $this->bi->getObjectOptions($object, $field);
                break;
            case 'string':
            case 'number':
                if($source)
                {
                    if($this->config->edition != 'open')
                    {
                        $this->app->loadConfig('dataview');
                        static $workflowFields = array();
                        if(!isset($workflowFields[$object])) $workflowFields[$object] = $this->loadModel('workflowfield')->getList($object);

                        $originalField = zget($_POST, 'originalField', $field);
                        $fieldObject   = zget($workflowFields[$object], $originalField, null);
                        if($fieldObject)
                        {
                            if($fieldObject->control == 'multi-select') $this->config->dataview->multipleMappingFields[] = $object . '-' . $field;

                            $options = $this->workflowfield->getFieldOptions($fieldObject);
                        }
                        if(!empty(array_filter($options))) break;
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

        if(is_string($source) && $source && $saveAs && in_array($type, array('user', 'product', 'project', 'execution', 'dept', 'option', 'object')))
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
    public function getFieldsOptions(array $fieldSettings, array $records, string $driver = 'mysql'): array
    {
        $options = array();

        foreach($fieldSettings as $key => $fieldSetting)
        {
            $type   = $fieldSetting['type'];
            $object = $fieldSetting['object'];
            $field  = $fieldSetting['field'];

            $options[$key] = $this->getSysOptions($type, $object, $field, $records, '', $driver);
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
        $width   = 128;

        $nowSpan = 1;
        $inFlow  = false;

        if(!empty($configs))
        {
            /* 处理不需要展示的单元格，设置为0 */
            $columnCount = count(current($configs));
            $lineCount   = count($configs);
            for($i = 0; $i < $columnCount; $i ++)
            {
                for($j = 0; $j < $lineCount; $j ++)
                {
                    if($configs[$j][$i] > 1 && !$inFlow)
                    {
                        $inFlow  = true;
                        $nowSpan = $configs[$j][$i];
                        continue;
                    }

                    if($configs[$j][$i] > 1 && $inFlow)
                    {
                        $configs[$j][$i] = 0;

                        $nowSpan --;
                        if($nowSpan == 1) $inFlow = false;
                    }
                }
            }
        }

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

        $showAllTotal = isset($data->showAllTotal) && $data->showAllTotal;
        $users        = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');

        for($i = 0; $i < count($data->array); $i ++)
        {
            $rowCount ++;

            if($showAllTotal && $rowCount == count($data->array)) continue;

            $line   = array_values($data->array[$i]);
            $table .= "<tr class='text-center'>";
            for($j = 0; $j < count($line); $j ++)
            {
                $cols    = isset($data->cols[0][$j]) ? $data->cols[0][$j] : array();
                $isGroup = !empty($data->cols[0][$j]->isGroup) ? $data->cols[0][$j]->isGroup : false;
                $rowspan = isset($configs[$i][$j]) ? $configs[$i][$j] : 1;
                $hidden  = (isset($configs[$i][$j]) && $configs[$i][$j]) ? false : (bool)$isGroup;

                $showOrigin = $showOrigins[$j];
                if($hasShowOrigin && !$isGroup && !$showOrigin)
                {
                    $rowspan = isset($configs[$i]) ? end($configs[$i]) : 1;
                    $hidden  = isset($configs[$i]) ? false : true;
                }

                $lineValue = $line[$j];
                if(is_numeric($lineValue)) $lineValue = round($lineValue, 2);

                if(isset($cols->name) && in_array($cols->name, $this->config->pivot->userFields)) $lineValue = isset($users[$lineValue]) ? $users[$lineValue] : $lineValue;

                if(!$hidden) $table .= "<td rowspan='$rowspan'>$lineValue</td>";
            }
            $table .= "</tr>";
        }

        if($showAllTotal && !empty($data->array))
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

            if(!empty($condition['value']))
            {
                if(!empty($condition['htmlspecialed'])) $value .= " OR t1.{$drillField} = {$condition['htmlspecialed']}";
                $conditionSQLs[] = "(t1.{$drillField}{$value})";
            }
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
            if(isset($condition['value']))
            {
                $htmlspecialed = htmlspecialchars($condition['value']);
                $conditions[$index]['value'] = " = " . $this->dbh->quote($condition['value']);
                if($htmlspecialed != $condition['value']) $conditions[$index]['htmlspecialed'] = $this->dbh->quote($htmlspecialed);
            }
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

    /**
     * Get versions of a pivot.
     *
     * @param  int    $pivotID
     * @access public
     * @return array|bool
     */
    public function getPivotVersions(int $pivotID): array|bool
    {
        $pivot = $this->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->andWhere('deleted')->eq('0')->fetch();
        if(!$pivot) return false;

        $pivotSpecList = $this->dao->select('*')->from(TABLE_PIVOTSPEC)->where('pivot')->eq($pivotID)->fetchAll('', false);
        if(!$pivotSpecList) return false;

        $pivotVersionList = array();
        foreach($pivotSpecList as $specData)
        {
            $pivotVersion = clone $pivot;
            foreach($specData as $specKey => $specValue) $pivotVersion->$specKey = $specValue;
            $this->processNameDesc($pivotVersion);

            $pivotVersionList[] = $pivotVersion;
        }

        return $pivotVersionList;
    }

    /**
     * Get max version.
     *
     * @param  int    $pivotID
     * @access public
     * @return string
     */
    public function getMaxVersion(int $pivotID): string
    {
        $versions = $this->dao->select('version')->from(TABLE_PIVOTSPEC)->where('pivot')->eq($pivotID)->fetchPairs();

        $maxVersion = current($versions);
        foreach($versions as $version)
        {
            if(version_compare($version, $maxVersion, '>')) $maxVersion = $version;
        }

        return $maxVersion;
    }

    /**
     * Get max version by idList.
     *
     * @param  string|array $pivotIDList
     * @access public
     * @return string
     */
    public function getMaxVersionByIDList(string|array $pivotIDList)
    {
        $pivotVersions = $this->dao->select('pivot,version')->from(TABLE_PIVOTSPEC)
            ->where('pivot')->in($pivotIDList)
            ->fetchGroup('pivot', 'version');
        if(empty($pivotVersions)) return array();

        $pivotMaxVersion = array();
        foreach($pivotVersions as $pivotID => $versions)
        {
            $versions = array_keys($versions);
            $maxVersion = current($versions);
            foreach($versions as $version)
            {
                if(version_compare($version, $maxVersion, '>')) $maxVersion = $version;
            }

            $pivotMaxVersion[$pivotID] = $maxVersion;
        }

        return $pivotMaxVersion;
    }

    public function isVersionChange(array|object $pivots, bool $isObject = true)
    {
        if($isObject) $pivots = array($pivots);
        $pivotMaxVersion = $this->getMaxVersionByIDList(array_column($pivots, 'id'));

        foreach($pivots as $index => $pivot)
        {
            $maxVersion = zget($pivotMaxVersion, $pivot->id, '');
            $pivots[$index]->versionChange = $maxVersion != $pivot->version && $pivot->builtin == 1;
        }

        return $isObject ? current($pivots) : $pivots;
    }

    /**
     * Switch pivot to a new version.
     *
     * @param  int    $pivotID
     * @param  string $version
     * @access public
     * @return bool
     */
    public function switchNewVersion(int $pivotID, string $version): bool
    {
        $this->dao->update(TABLE_PIVOT)->set('version')->eq($version)->where('id')->eq($pivotID)->exec();
        return !dao::isError();
    }

    /**
     * Filter special chars in query data.
     *
     * @param  array  $records
     * @access public
     * @return array
     */
    public function filterSpecialChars($records)
    {
        if(empty($records)) return $records;

        foreach($records as $index => $record)
        {
            foreach($record as $field => $value)
            {
                $value = is_string($value) ? str_replace('"', '', htmlspecialchars_decode($value)) : $value;
                if(is_object($record)) $record->$field = $value;
                if(is_array($record))  $record[$field] = $value;
            }
            $records[$index] = $record;
        }
        return $records;
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
