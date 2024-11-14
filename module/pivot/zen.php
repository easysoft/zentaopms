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
     * 获取菜单项。
     * Get menu items.
     *
     * @param  array $menus
     * @access protected
     * @return array
     */
    protected function getMenuItems(array $menus): array
    {
        $items = array();
        foreach($menus as $menu)
        {
            if(isset($menu->url)) $items[] = $menu;
        }

        return $items;
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
            $pivots = $this->pivot->filterInvisiblePivot($pivots);
            if(empty($pivots)) continue;

            if($group->grade > 1) $menus[] = (object)array('id' => $group->id, 'parent' => 0, 'name' => $group->name);

            if($pivots) $pivots = $this->pivot->processPivot($pivots, false);

            foreach($pivots as $pivot)
            {
                $params  = helper::safe64Encode("groupID={$group->id}&pivotID={$pivot->id}");
                $url     = inlink('preview', "dimension={$dimensionID}&group={$currentGroup->id}&method=show&params={$params}");
                $menus[] = (object)array('id' => $group->id . '_' . $pivot->id, 'parent' => $group->grade > 1 ? $group->id : 0, 'name' => $pivot->name, 'url' => $url);
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
     * Preview pivots of a group.
     *
     * @param  int    $groupID
     * @param  int    $pivotID
     * @access public
     * @return void
     */
    public function show(int $groupID, int $pivotID): void
    {
        $this->pivot->checkAccess($pivotID, 'preview');

        $pivot  = $this->pivot->getByID($pivotID, true);
        $driver = $pivot->driver;
        if(isset($_POST['filterValues']) and $_POST['filterValues'])
        {
            foreach($this->post->filterValues as $key => $value) $pivot->filters[$key]['default'] = $value;
        }
        $showOrigin = false;
        if(isset($_POST['summary']) and $_POST['summary']) $showOrigin = $this->post->summary == 'notuse';

        list($sql, $filterFormat) = $this->pivot->getFilterFormat($pivot->sql, $pivot->filters);

        $fields = json_decode(json_encode($pivot->fieldSettings), true);
        $langs  = json_decode($pivot->langs, true) ?? array();

        $settingShowOrigin = isset($pivot->settings['summary']) && $pivot->settings['summary'] == 'notuse';
        if($showOrigin || $settingShowOrigin)
        {
            list($data, $configs) = $this->pivot->genOriginSheet($fields, $pivot->settings, $sql, $filterFormat, $langs, $driver);
        }
        else
        {
            list($data, $configs) = $this->pivot->genSheet($fields, $pivot->settings, $sql, $filterFormat, $langs, $driver);
        }

        $this->view->pivotName    = $pivot->name;
        $this->view->title        = $pivot->name;
        $this->view->currentMenu  = $groupID . '_' . $pivot->id;
        $this->view->currentGroup = $groupID;
        $this->view->pivot        = $pivot;
        $this->view->showOrigin   = $showOrigin;
        $this->view->data         = $data;
        $this->view->configs      = $configs;
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
        $this->view->pivotName   = $this->lang->pivot->bugCreate;
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
        $this->view->pivotName   = $this->lang->pivot->bugAssign;
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
    public function productSummary(string $conditions = '', int|string $productID = 0, string $productStatus = 'normal', string $productType = 'normal'): void
    {
        $this->app->loadLang('story');
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $filters  = array('productID' => $productID, 'productStatus' => $productStatus, 'productType' => $productType);
        $products = $this->pivot->getProducts($conditions, 'story', $filters);

        $this->view->filters     = $filters;
        $this->view->title       = $this->lang->pivot->productSummary;
        $this->view->pivotName   = $this->lang->pivot->productSummary;
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
        $this->view->pivotName   = $this->lang->pivot->projectDeviation;
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
        $this->view->pivotName   = $this->lang->pivot->workload;
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

    /**
     * 获取下钻列配置。
     * Get drill.
     *
     * @param  int    $pivotID
     * @param  string $colName
     * @param  string $status
     * @access public
     * @return object
     */
    public function getDrill(int $pivotID, string $colName, string $status = 'published'): object
    {
        if($status == 'published')
        {
            $drills = $this->pivot->fetchPivotDrills($pivotID, $colName);
            return reset($drills);
        }

        $cache  = $this->getCache($pivotID);
        $drills = json_decode(json_encode($cache->drills), true);
        foreach($drills as $drill)
        {
            $drill = (object)$drill;
            $drill->condition = (array)$drill->condition;
            if($drill->field == $colName) return $drill;
        }
        return new stdclass();
    }
}
