<?php
class pivotZen extends pivot
{
    /**
     * 获取分组里一个透视表的默认方法和参数。
     * Get default method name and parameters of a pivot in a group.
     *
     * @param  int    $dimension
     * @param  int    $group
     * @access protected
     * @return array
     */
    protected function getDefaultMethodAndParams(int $dimensionID, int $groupID): array
    {
        $currentGroup = $this->loadModel('tree')->getByID($groupID);
        if(empty($currentGroup) || $currentGroup->grade != 1) return array('', '');

        $groups = $this->pivot->getGroupsByDimensionAndPath($dimensionID, $currentGroup->path);
        if(!$groups) return array('', '');

        foreach($groups as $group)
        {
            if($this->config->edition == 'open' && $group->grade == 1) continue;

            $pivotID = $this->pivot->getPivotID($group->id);
            if($pivotID) return array('show', "groupID={$group->id}&pivotID={$pivotID}");
        }

        $firstDimension = $this->loadModel('dimension')->getFirst();
        if($dimensionID != $firstDimension->id) return array('', '');

        if(empty($this->lang->pivotList->{$currentGroup->collector}->lists)) return array('', '');

        foreach($this->lang->pivotList->{$currentGroup->collector}->lists as $item)
        {
            $items = explode('|', $item);
            if(count($items) != 3) continue;

            $method = $items[2];

            if(common::hasPriv('pivot', $method)) return array($method, '');
        }

        return array('', '');
    }

    /**
     * 获取侧边栏菜单。
     * Get sidebar menus of pivot.
     *
     * @param  int       $dimensionID
     * @param  object    $groupID
     * @access protected
     * @return array
     */
    protected function getSidebarMenus(int $dimensionID, int $groupID): array
    {
        $currentGroup = $this->loadModel('tree')->getByID($groupID);
        if(empty($currentGroup) || $currentGroup->grade != 1) return array();

        $groups = $this->pivot->getGroupsByDimensionAndPath($dimensionID, $currentGroup->path);
        if(!$groups) return array();

        $menus = array();
        foreach($groups as $group)
        {
            if($this->config->edition == 'open' && $group->grade == 1) continue;

            $pivots = $this->pivot->getAllPivotByGroupID($group->id);

            if(empty($group->collector) && empty($pivots)) continue;

            $menus[] = (object)array('id' => $group->id, 'parent' => 0, 'name' => $group->name);

            if($pivots) $pivots = $this->pivot->processPivot($pivots, false);

            foreach($pivots as $pivot)
            {
                $params  = helper::safe64Encode("groupID={$group->id}&pivotID={$pivot->id}");
                $url     = inlink('preview', "dimension={$dimensionID}&group={$currentGroup->id}&method=show&params={$params}");
                $menus[] = (object)array('id' => $group->id . '_' . $pivot->id, 'parent' => $group->id, 'name' => $pivot->name, 'url' => $url);
            }
        }

        $firstDimension = $this->loadModel('dimension')->getFirst();
        if($dimensionID != $firstDimension->id) return $menus;

        $builtinMenus = $this->getBuiltinMenus($dimensionID, $currentGroup);
        return array_merge($menus, $builtinMenus);
    }

    /**
     * 在第一个维度上显示内置透视表。
     * Display the built-in pivots in the first dimension.
     *
     * @param  int       $dimensionID
     * @param  object    $currengGroup
     * @access protected
     * @return array
     */
    protected function getBuiltinMenus(int $dimensionID, object $currentGroup): array
    {
        $collector = $currentGroup->collector;
        if(empty($this->lang->pivotList->$collector->lists)) return array();

        $menus = array();

        ksort($this->lang->pivotList->$collector->lists);
        foreach($this->lang->pivotList->$collector->lists as $item)
        {
            $items = explode('|', $item);
            if(count($items) != 3) continue;

            $label  = $items[0];
            $method = $items[2];

            if(!common::hasPriv('pivot', $method)) continue;

            $url = inlink('preview', "dimension={$dimensionID}&group={$currentGroup->id}&method={$method}");

            $menus[] = (object)array('id' => $method, 'parent' => 0, 'name' => $label, 'url' => $url);
        }

        return $menus;
    }

    /**
     * 获取透视表过滤器下拉选项。
     * Get filter options of pivot.
     *
     * @param  string $type
     * @param  string $object
     * @param  string $field
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getFilterOptions(string $type, string $object = '', string $field = '', string $sql = ''): array
    {
        $result  = array();
        $options = $this->pivot->getSysOptions($type, $object, $field, $sql);
        foreach($options as $key => $value) $result[] = array('text' => $value, 'value' => $key);
        return $result;
    }

    /**
     * Preview pivots of a group.
     *
     * @param  int    $groupID
     * @param  int    $pivotID
     * @access public
     * @return void
     */
    public function show(int $groupID, int $pivotID): void
    {
        $pivot = $this->pivot->getByID($pivotID);
        if($this->post->filterValues)
        {
            foreach($this->post->filterValues as $key => $value) $pivot->filters[$key]['default'] = $value;
        }

        list($sql, $filterFormat) = $this->pivot->getFilterFormat($pivot->sql, $pivot->filters);

        $tables = $this->loadModel('chart')->getTables($sql);
        $sql    = $tables['sql'];
        $fields = json_decode(json_encode($pivot->fieldSettings), true);
        $langs  = json_decode($pivot->langs, true) ?? array();

        list($data, $configs) = $this->pivot->genSheet($fields, $pivot->settings, $sql, $filterFormat, $langs);

        $this->view->title        = $pivot->name;
        $this->view->currentMenu  = $groupID . '_' . $pivot->id;
        $this->view->currentGroup = $groupID;
        $this->view->pivot        = $pivot;
        $this->view->data         = $data;
        $this->view->configs      = $configs;
    }

    /**
     * 把自定义透视表的数据转换为数据表格可以使用的格式。
     * Convert the data of custom pivot to the format that can be used by data table.
     *
     * @param  object $data
     * @param  array  $configs
     * @access public
     * @return array
     */
    public function convertDataForDtable(object $data, array $configs): array
    {
        $columns  = array();
        $rows     = array();
        $cellSpan = array();

        $headerRow1 = !empty($data->cols[0]) ? $data->cols[0] : array();
        $headerRow2 = !empty($data->cols[1]) ? $data->cols[1] : array();

        /* 定义数据表格的列配置。*/
        /* Define the column configuration of the data table. */
        $index = 0;
        foreach($headerRow1 as $column)
        {
            /* 如果 colspan 属性不为空则表示该列包含切片字段。*/
            /* If the colspan attribute is not empty, it means that the column contains slice fields. */
            if(!empty($column->colspan) && $column->colspan > 1)
            {
                /* 找到实际切片的字段。*/
                /* Find the actual sliced field. */
                $colspan = 0;
                while($colspan < $column->colspan)
                {
                    $subColumn = array_shift($headerRow2);

                    $field = 'field' . $index;
                    $columns[$field]['name']     = $field;
                    $columns[$field]['title']    = $subColumn->label;
                    $columns[$field]['width']    = 16 * mb_strlen($subColumn->label);
                    $columns[$field]['minWidth'] = 128;
                    $columns[$field]['align']    = 'center';

                    /* 把被切片的字段名设置为数据表格的列配置的 headerGroup 属性。*/
                    /* Set the sliced field name as the headerGroup attribute of the column configuration of the data table. */
                    $columns[$field]['headerGroup'] = $column->label;

                    /* 数据表格不支持表头第二行合并单元格，如果有这种情况把被合并的所有列视为一列，记录 colspan 属性并跳过其它列。*/
                    /* The data table does not support merging cells in the second row of the header. If this is the case, all the merged columns are regarded as one column, the colspan attribute is recorded and other columns are skipped. */
                    if(!empty($subColumn->colspan) && $subColumn->colspan > 1) $columns[$field]['colspan'] = $subColumn->colspan;

                    $colspan += $subColumn->colspan ?: 1;
                    $index++;
                }

                continue;
            }

            $field = 'field' . $index;
            $columns[$field]['name']     = $field;
            $columns[$field]['title']    = $column->label;
            $columns[$field]['width']    = 16 * mb_strlen($column->label);
            $columns[$field]['minWidth'] = 128;
            $columns[$field]['align']    = 'center';

            if(isset($data->groups[$index])) $columns[$field]['fixed'] = 'left';

            $index++;
        }

        $lastRow = count($data->array) - 1;
        foreach($data->array as $rowKey => $rowData)
        {
            $index   = 0;
            $rowData = array_values($rowData);

            for($i = 0; $i < count($rowData); $i++)
            {
                $field = 'field' . $index;
                $value = $rowData[$i];

                if(!empty($columns[$field]['colspan']))
                {
                    $colspan = $columns[$field]['colspan'];
                    $value   = array_slice($rowData, $i, $colspan);

                    $i += $colspan - 1;
                }

                /* 定义数据表格的行数据。*/
                /* Defind row data of the data table. */
                $rows[$rowKey][$field] = $value;

                /* 定义数据表格合并单元格的配置。*/
                /* Define configuration to merge cell of the data table. */
                if(isset($configs[$rowKey][$index]) && $configs[$rowKey][$index] > 1)
                {
                    $rows[$rowKey][$field . '_rowspan'] = $configs[$rowKey][$index];
                    $cellSpan[$field]['rowspan'] = $field . '_rowspan';
                }

                if($i == 0 && !empty($data->groups) && !empty($data->columnTotal) && $rowKey == $lastRow)
                {
                    $rows[$rowKey][$field . '_colspan'] = count($data->groups);
                    $cellSpan[$field]['colspan'] = $field . '_colspan';
                }

                $index++;
            }
        }

        return array($columns, $rows, $cellSpan);
    }

    /**
     * Bug create pivot.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return void
     */
    public function bugCreate(string $begin = '', string $end = '', int $product = 0, int $execution = 0): void
    {
        $this->app->loadLang('bug');
        $begin = date('Y-m-01', strtotime($begin ?:'last month'));
        $end   = date('Y-m-d',  strtotime($end ?: 'now'));

        $this->view->title       = $this->lang->pivot->bugCreate;
        $this->view->bugs        = $this->pivot->getBugs($begin, $end, $product ? $product : 0, $execution ? $execution : 0);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->executions  = $this->pivot->getProjectExecutions();
        $this->view->products    = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->execution   = $execution;
        $this->view->product     = $product;
        $this->view->currentMenu = 'bugcreate';
    }

    /**
     * Bug assign pivot.
     *
     * @access public
     * @return void
     */
    public function bugAssign(): void
    {
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $this->view->title       = $this->lang->pivot->bugAssign;
        $this->view->bugs        = $this->pivot->getBugAssign();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->currentMenu = 'bugassign';
    }

    /**
     * Product information pivot.
     *
     * @params string $conditions
     * @access public
     * @return void
     */
    public function productSummary(string $conditions = ''): void
    {
        $this->app->loadLang('story');
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $products = $this->pivot->getProducts($conditions);

        $this->view->title       = $this->lang->pivot->productSummary;
        $this->view->products    = $this->processProductsForProductSummary($products);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->conditions  = $conditions;
        $this->view->currentMenu = 'productsummary';
    }

    /**
     * 把计划数组展开为独立的产品对象并添加跨行合并属性。
     * Expand the plans property to single product objects and add rowspan property.
     *
     * @params array  $products
     * @access public
     * @return array
     */
    public function processProductsForProductSummary(array $products): array
    {
        $productList = array();

        foreach($products as $product)
        {
            if(!isset($product->plans))
            {
                $product->planTitle      = '';
                $product->planBegin      = '';
                $product->planEnd        = '';
                $product->storyDraft     = 0;
                $product->storyReviewing = 0;
                $product->storyActive    = 0;
                $product->storyChanging  = 0;
                $product->storyClosed    = 0;
                $product->storyTotal     = 0;

                $productList[] = $product;

                continue;
            }

            $first = true;
            foreach($product->plans as $plan)
            {
                $newProduct = clone $product;
                $newProduct->planTitle      = $plan->title;
                $newProduct->planBegin      = $plan->begin == '2030-01-01' ? $this->lang->productplan->future : $plan->begin;
                $newProduct->planEnd        = $plan->end   == '2030-01-01' ? $this->lang->productplan->future : $plan->end;
                $newProduct->storyDraft     = isset($plan->status['draft'])     ? $plan->status['draft']     : 0;
                $newProduct->storyReviewing = isset($plan->status['reviewing']) ? $plan->status['reviewing'] : 0;
                $newProduct->storyActive    = isset($plan->status['active'])    ? $plan->status['active']    : 0;
                $newProduct->storyChanging  = isset($plan->status['changing'])  ? $plan->status['changing']  : 0;
                $newProduct->storyClosed    = isset($plan->status['closed'])    ? $plan->status['closed']    : 0;
                $newProduct->storyTotal     = $newProduct->storyDraft + $newProduct->storyReviewing + $newProduct->storyActive + $newProduct->storyChanging + $newProduct->storyClosed;

                if($first) $newProduct->rowspan = count($newProduct->plans);

                $productList[] = $newProduct;

                $first = false;
            }
        }

        return $productList;
    }

    /**
     * Project deviation pivot.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function projectDeviation(string $begin = '', string $end = ''): void
    {
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-01');
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime(date('Y-m-01', strtotime('next month')) . ' -1 day'));

        $this->view->title       = $this->lang->pivot->projectDeviation;
        $this->view->executions  = $this->pivot->getExecutions($begin, $end);
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->currentMenu = 'projectdeviation';
    }

    /**
     * 组织透视表。
     * Workload pivot.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $days
     * @param  float  $workhour
     * @param  int    $dept
     * @param  string $assign
     * @access public
     * @return void
     */
    public function workload(string $begin = '', string $end = '', int $days = 0, float $workhour= 0, int $dept = 0, string $assign = 'assign'): void
    {
        $this->app->loadConfig('execution');
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin  = $begin ? strtotime($begin) : time();
        $end    = $end   ? strtotime($end)   : time() + (7 * 24 * 3600);
        $end   += 24 * 3600;
        $beginWeekDay = date('w',     $begin);
        $begin        = date('Y-m-d', $begin);
        $end          = date('Y-m-d', $end);

        if(empty($workhour)) $workhour = $this->config->execution->defaultWorkhours;
        $diffDays = helper::diffDate($end, $begin);
        if($days > $diffDays) $days = $diffDays;
        if(empty($days))
        {
            $weekDay = $beginWeekDay;
            $days    = $diffDays;
            for($i = 0; $i < $diffDays; $i++, $weekDay++)
            {
                $weekDay = $weekDay % 7;
                if(($this->config->execution->weekend == 2 && $weekDay == 6) || $weekDay == 0) $days--;
            }
        }

        $allHour = $workhour * $days;
        $users   = $this->loadModel('user')->getPairs('noletter|noclosed');

        $this->view->title       = $this->lang->pivot->workload;
        $this->view->workload    = $this->pivot->getWorkload($dept, $assign, $users, $allHour);
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->users       = $users;
        $this->view->dept        = $dept;
        $this->view->begin       = $begin;
        $this->view->end         = date('Y-m-d', strtotime($end) - 24 * 3600);
        $this->view->days        = $days;
        $this->view->workhour    = $workhour;
        $this->view->assign      = $assign;
        $this->view->currentMenu = 'workload';
    }
}
