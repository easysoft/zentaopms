<?php
class pivotZen extends pivot
{
    /**
     * Prepare for preview a pivot.
     *
     * @param  int    $dimension
     * @param  string $groupID
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access protected
     * @return void
     */
    protected function prepare4Preview($dimensionID, $groupID, $module, $method, $params)
    {
        $params = helper::safe64Decode($params);

        if(!$groupID) $groupID = $this->getDefaultGroup($dimensionID);
        if(!$module || !$method) list($module, $method, $params) = $this->getDefaultPivotParams($dimensionID, $groupID);

        if(!empty($module) && !empty($method) && $method != 'show' && !common::hasPriv($module, $method)) $this->loadModel('common')->deny('pivot', $method);

        $this->setFeatureBar($dimensionID);

        $group = $this->loadModel('tree')->getByID($groupID);;

        parse_str($params, $result);

        if(method_exists($this, $method)) call_user_func_array(array($this, $method), $result);

        $this->view->currentMenu = '';
        $this->view->menus       = $this->getSidebarMenus($dimensionID, $group, $module, $method, $params);
        $this->view->dimensionID = $dimensionID;
        $this->view->group       = $group;
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->view->params      = $params;

        if(empty($this->view->title)) $this->view->title = $this->lang->pivot->list;
    }

    /**
     * Get default group of a dimension.
     *
     * @param  int    $dimension
     * @access protected
     * @return string
     */
    protected function getDefaultGroup($dimension)
    {
        $group = $this->getFirstGroup($dimension);
        if(!$group) return 0;

        return $group->id;
    }

    /**
     * Get first group of a dimension.
     *
     * @param  int    $dimension
     * @access protected
     * @return int
     */
    protected function getFirstGroup($dimension)
    {
        if(empty($dimension)) return 0;

        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('type')->eq('pivot')
            ->andWhere('root')->eq($dimension)
            ->orderBy('grade, `order`')
            ->limit(1)
            ->fetch();
    }

    /**
     * Get default module name and method name of a pivot in a group.
     *
     * @param  int    $dimension
     * @param  int    $group
     * @access protected
     * @return array
     */
    protected function getDefaultPivotParams($dimension, $group)
    {
        $currentGroup = $this->loadModel('tree')->getByID($group);
        if(empty($currentGroup) || $currentGroup->grade != 1) return array('', '', '');

        $groups = $this->dao->select('id, grade, name, collector')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('root')->eq($dimension)
            ->andWhere('path')->like("$currentGroup->path%")
            ->orderBy('`order`')
            ->fetchAll();
        if(!$groups) return array('', '', '');

        foreach($groups as $group)
        {
            if($this->config->edition == 'open' && $group->grade == 1) continue;

            $pivots = $this->dao->select('*')->from(TABLE_PIVOT)
                ->where("FIND_IN_SET($group->id, `group`)")
                ->andWhere('stage')->ne('draft')
                ->orderBy('id_desc')
                ->fetchAll();
            if($pivots)
            {
                foreach($pivots as $pivot) return array('pivot', 'show', "dimensionID=$dimension&groupID={$group->id}&pivotID={$pivot->id}");
            }
        }

        if(empty($this->lang->pivotList->{$currentGroup->collector}->lists)) return array('', '', '');

        $firstDimension = $this->loadModel('dimension')->getFirst();
        if($dimension != $firstDimension->id) return array('', '', '');

        foreach($this->lang->pivotList->{$currentGroup->collector}->lists as $item)
        {
            $items = explode('|', $item . '|');
            if(count($items) < 4) continue;

            list($label, $module, $method, $params) = $items;

            if(common::hasPriv($module, $method)) return array($module, $method, $params);
        }

        return array('', '', '');
    }

    /**
     * Set pivot Menu of a dimension.
     *
     * @param  int    $dimension
     * @access protected
     * @return void
     */
    protected function setFeatureBar($dimension)
    {
        if(!$dimension) return false;

        $groups = $this->loadModel('tree')->getGroupPairs($dimension, 0, 1, 'pivot');
        if(!$groups) return false;

        $this->lang->pivot->featureBar['preview'] = array();
        foreach($groups as $groupID => $groupName)
        {
            if(empty($groupID) || empty($groupName)) continue;
            $this->lang->pivot->featureBar['preview'][$groupID] = $groupName;
        }
    }

    /**
     * Get sidebar menus of pivot.
     *
     * @param  int    $dimension
     * @param  object $currentGroup
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access protected
     * @return string
     */
    protected function getSidebarMenus(int $dimension, object $currentGroup, string $module, string $method, string $params): array
    {
        if(empty($currentGroup) || $currentGroup->grade != 1) return array();

        $groups = $this->dao->select('id, grade, name, collector')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('root')->eq($dimension)
            ->andWhere('path')->like("{$currentGroup->path}%")
            ->orderBy('`order`')
            ->fetchAll();
        if(!$groups) return array();

        $pivotID = 0;
        if($module == 'pivot' && $method == 'show')
        {
            parse_str($params, $params);
            if(isset($params['pivotID'])) $pivotID = $params['pivotID'];
        }

        $clientLang = $this->app->getClientLang();
        if(!isset($this->config->langs[$clientLang])) $clientLang = 'zh-cn';

        $menus = array();
        foreach($groups as $group)
        {
            if($this->config->edition == 'open' && $group->grade == 1) continue;

            $pivots = $this->dao->select('*')->from(TABLE_PIVOT)
                ->where("FIND_IN_SET($group->id, `group`)")
                ->andWhere('stage')->ne('draft')
                ->andWhere('deleted')->eq(0)
                ->orderBy('id_desc')
                ->fetchAll();

            if(empty($group->collector) && empty($pivots)) continue;

            $groupMenu = new stdclass();
            $groupMenu->id     = 'group_' . $group->id;
            $groupMenu->parent = '0';
            $groupMenu->name   = $group->name;
            $groupMenu->url    = '';

            $menus[] = $groupMenu;

            if($pivots) $pivots = $this->pivot->processPivot($pivots, false);

            foreach($pivots as $pivot)
            {
                $params = helper::safe64Encode("dimensionID={$pivot->dimension}&groupID={$pivot->group}&pivotID={$pivot->id}");

                $pivotMenu = new stdclass();
                $pivotMenu->id     = 'pivot_' . $pivot->id;
                $pivotMenu->parent = $groupMenu->id;
                $pivotMenu->name   = $pivot->name;
                $pivotMenu->url    = inlink('preview', "dimension={$dimension}&group={$currentGroup->id}&module=pivot&method=show&params={$params}");

                $menus[] = $pivotMenu;

                if($module == 'pivot' && $method == 'show' && $pivotID == $pivot->id)
                {
                    $this->view->title       = $pivotMenu->name;
                    $this->view->currentMenu = $pivotMenu->id;
                }
            }
        }

        $menus += $this->getBuiltinMenus($dimension, $currentGroup, $module, $method);

        return $menus;
    }

    /**
     * Display the built-in pivots in the first dimension.
     *
     * @param  int    $dimension
     * @param  object $group
     * @param  string $currentModule
     * @param  string $currentMethod
     * @access protected
     * @return array
     */
    protected function getBuiltinMenus(int $dimension, object $group, string $currentModule, string $currentMethod): array
    {
        $firstDimension = $this->loadModel('dimension')->getFirst();
        if($dimension != $firstDimension->id) return array();

        $collector = $group->collector;
        if(empty($this->lang->pivotList->$collector->lists)) return array();

        $menus         = array();
        $currentModule = strtolower($currentModule);
        $currentMethod = strtolower($currentMethod);

        ksort($this->lang->pivotList->$collector->lists);
        foreach($this->lang->pivotList->$collector->lists as $item)
        {
            $items = explode('|', $item . '|');
            if(count($items) < 4) continue;

            list($label, $module, $method, $params) = $items;

            if(!common::hasPriv($module, $method)) continue;

            $params = helper::safe64Encode($params);

            $pivotMenu = new stdclass();
            $pivotMenu->id     = $module . '_' . $method;
            $pivotMenu->parent = '0';
            $pivotMenu->name   = $label;
            $pivotMenu->url    = inlink('preview', "dimension={$dimension}&group={$group->id}&module={$module}&method={$method}&params={$params}");

            $menus[] = $pivotMenu;

            if($currentModule == strtolower($module) && $currentMethod == strtolower($method))
            {
                $this->view->title       = $pivotMenu->name;
                $this->view->currentMenu = $pivotMenu->id;
            }
        }

        return $menus;
    }

    /**
     * Preview pivots of a group.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @param  int    $pivotID
     * @access public
     * @return void
     */
    public function show(int $dimensionID = 0, int $groupID = 0, int $pivotID = 0): void
    {
        $dimensionID = $this->loadModel('dimension')->getDimension($dimensionID);

        if(!$groupID)
        {
            $groupID = $this->dao->select('id')->from(TABLE_MODULE)
                ->where('deleted')->eq('0')
                ->andWhere('type')->eq('pivot')
                ->andWhere('root')->eq($dimensionID)
                ->andWhere('grade')->eq(1)
                ->orderBy('`order`')
                ->limit(1)
                ->fetch('id');
        }

        list($pivotTree, $pivot, $groupID) = $this->pivot->getPreviewPivots($dimensionID, $groupID, $pivotID);
        if($pivot)
        {
            list($sql, $filterFormat) = $this->pivot->getFilterFormat($pivot->sql, $pivot->filters);

            $processSqlData = $this->loadModel('chart')->getTables($sql);
            $sql = $processSqlData['sql'];

            list($data, $configs) = $this->pivot->genSheet(json_decode(json_encode($pivot->fieldSettings), true), $pivot->settings, $sql, $filterFormat, json_decode($pivot->langs, true));
            $this->view->data     = $data;
            $this->view->configs  = $configs;
        }

        $group = $this->loadModel('tree')->getByID($groupID);

        $this->view->title       = $this->lang->pivot->preview;
        $this->view->dimensionID = $dimensionID;
        $this->view->pivotTree   = $pivotTree;
        $this->view->pivot       = $pivot;
        $this->view->group       = $group;
        $this->view->parentGroup = $group->grade == 2 ? $this->tree->getByID($group->parent) : $group;
        $this->view->groups      = $this->tree->getGroupPairs($dimensionID, 0, 1, 'pivot');
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

        foreach($data->cols as $lineColumns)
        {
            foreach($lineColumns as $key => $column)
            {
                $field = 'field' . $key;
                $columns[$field]['name']     = $field;
                $columns[$field]['title']    = $column->label;
                $columns[$field]['width']    = 16 * mb_strlen($column->label);
                $columns[$field]['minWidth'] = 128;
                $columns[$field]['align']    = 'center';
            }

            break;
        }

        foreach($data->array as $rowKey => $rowData)
        {
            $rowData = array_values($rowData);
            foreach($rowData as $key => $value)
            {
                $field = 'field' . $key;

                $rows[$rowKey][$field] = $value;
                if(isset($configs[$rowKey][$key]) && $configs[$rowKey][$key] > 1)
                {
                    $rows[$rowKey][$field . 'Rowspan'] = $configs[$rowKey][$key];
                    $cellSpan[] = array('cols' => array($field), 'rowspan' => $field . 'Rowspan');
                }
            }
        }

        return array($columns, $rows, $cellSpan);
    }

    /**
     * Bug create pivot.
     *
     * @param  int    $begin
     * @param  int    $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return void
     */
    public function bugCreate($begin = 0, $end = 0, $product = 0, $execution = 0)
    {
        $this->app->loadLang('bug');
        $begin = $begin == 0 ? date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01'))) : date('Y-m-d', strtotime($begin));
        $end   = $end == 0   ? date('Y-m-d', strtotime('now')) : $end = date('Y-m-d', strtotime($end));

        $this->view->title      = $this->lang->pivot->bugCreate;
        $this->view->bugs       = $this->pivot->getBugs($begin, $end, $product, $execution);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->executions = $this->pivot->getProjectExecutions();
        $this->view->products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->execution  = $execution;
        $this->view->product    = $product;
    }

    /**
     * Bug assign pivot.
     *
     * @access public
     * @return void
     */
    public function bugAssign()
    {
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $this->view->title = $this->lang->pivot->bugAssign;
        $this->view->bugs  = $this->pivot->getBugAssign();
        $this->view->users = $this->loadModel('user')->getPairs('noletter|noclosed');
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

        $this->view->title      = $this->lang->pivot->productSummary;
        $this->view->products   = $this->processProductsForProductSummary($products);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->conditions = $conditions;
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
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return void
     */
    public function projectDeviation(int $begin = 0, int $end = 0): void
    {
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin = date('Y-m-d', ($begin ? strtotime($begin) : time() - (date('j') - 1) * 24 * 3600));
        $end   = date('Y-m-d', ($end   ? strtotime($end)   : time() + (date('t') - date('j')) * 24 * 3600));

        $this->view->title      = $this->lang->pivot->projectDeviation;
        $this->view->executions = $this->pivot->getExecutions($begin, $end);
        $this->view->begin      = $begin;
        $this->view->end        = $end;
    }

    /**
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
        if($_POST)
        {
            $data     = fixer::input('post')->get();
            $begin    = $data->begin;
            $end      = $data->end;
            $dept     = $data->dept;
            $days     = $data->days;
            $assign   = $data->assign;
            $workhour = $data->workhour;
        }

        $this->app->loadConfig('execution');
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin  = $begin ? strtotime($begin) : time();
        $end    = $end   ? strtotime($end)   : time() + (7 * 24 * 3600);
        $end   += 24 * 3600;
        $beginWeekDay = date('w', $begin);
        $begin  = date('Y-m-d', $begin);
        $end    = date('Y-m-d', $end);

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

        $this->view->title    = $this->lang->pivot->workload;
        $this->view->workload = $this->pivot->getWorkload($dept, $assign, $users, $allHour);
        $this->view->depts    = $this->loadModel('dept')->getOptionMenu();
        $this->view->users    = $users;
        $this->view->dept     = $dept;
        $this->view->begin    = $begin;
        $this->view->end      = date('Y-m-d', strtotime($end) - 24 * 3600);
        $this->view->days     = $days;
        $this->view->workhour = $workhour;
        $this->view->assign   = $assign;
    }
}
