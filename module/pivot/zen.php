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
    protected function prepare4Preview($dimension, $groupID, $module, $method, $params)
    {
        $params = helper::safe64Decode($params);

        if(!$groupID) $groupID = $this->getDefaultGroup($dimension);
        if(!$module || !$method) list($module, $method, $params) = $this->getDefaultPivotParams($dimension, $groupID);

        if(!empty($module) && !empty($method) && $method != 'show' && !common::hasPriv($module, $method)) $this->loadModel('common')->deny('pivot', $method);

        $this->setFeatureBar($dimension);

        $group = $this->loadModel('tree')->getByID($groupID);;

        parse_str($params, $result);
        call_user_func_array(array($this, $method), $result);

        $this->view->menus     = $this->getSidebarMenus($dimension, $group, $module, $method, $params);
        $this->view->dimension = $dimension;
        $this->view->group     = $group;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->params    = $params;

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
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->bugs       = $this->pivot->getBugs($begin, $end, $product, $execution);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->executions = $this->pivot->getProjectExecutions();
        $this->view->products   = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->execution  = $execution;
        $this->view->product    = $product;
        $this->view->submenu    = 'test';
        $this->display();
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

        $this->view->title      = $this->lang->pivot->bugAssign;
        $this->view->submenu    = 'test';
        $this->view->assigns    = $this->pivot->getBugAssign();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->display();
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

        $this->view->title      = $this->lang->pivot->productSummary;
        $this->view->products   = $this->pivot->getProducts($conditions);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->submenu    = 'product';
        $this->view->conditions = $conditions;
    }

    /**
     * Project deviation pivot.
     *
     * @access public
     * @return void
     */
    public function projectDeviation($begin = 0, $end = 0)
    {
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin = date('Y-m-d', ($begin ? strtotime($begin) : time() - (date('j') - 1) * 24 * 3600));
        $end   = date('Y-m-d', ($end   ? strtotime($end)   : time() + (date('t') - date('j')) * 24 * 3600));

        $this->view->title      = $this->lang->pivot->projectDeviation;

        $this->view->executions = $this->pivot->getExecutions($begin, $end);
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->submenu    = 'project';
        $this->display();
    }

    /**
     * Workload pivot.
     *
     * @param string $begin
     * @param string $end
     * @param int    $days
     * @param int    $workday
     * @param int    $dept
     * @param int    $assign
     *
     * @access public
     * @return void
     */
    public function workload($begin = '', $end = '', $days = 0, $workday = 0, $dept = 0, $assign = 'assign')
    {
        if($_POST)
        {
            $data    = fixer::input('post')->get();
            $begin   = $data->begin;
            $end     = $data->end;
            $dept    = $data->dept;
            $days    = $data->days;
            $assign  = $data->assign;
            $workday = $data->workday;
        }

        $this->app->loadConfig('execution');
        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $begin  = $begin ? strtotime($begin) : time();
        $end    = $end   ? strtotime($end)   : time() + (7 * 24 * 3600);
        $end   += 24 * 3600;
        $beginWeekDay = date('w', $begin);
        $begin  = date('Y-m-d', $begin);
        $end    = date('Y-m-d', $end);

        if(empty($workday))$workday = $this->config->execution->defaultWorkhours;
        $diffDays = helper::diffDate($end, $begin);
        if($days > $diffDays) $days = $diffDays;
        if(empty($days))
        {
            $weekDay = $beginWeekDay;
            $days    = $diffDays;
            for($i = 0; $i < $diffDays; $i++,$weekDay++)
            {
                $weekDay = $weekDay % 7;
                if(($this->config->execution->weekend == 2 and $weekDay == 6) or $weekDay == 0) $days --;
            }
        }

        $this->view->title      = $this->lang->pivot->workload;

        $this->view->workload = $this->pivot->getWorkload($dept, $assign);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts    = $this->loadModel('dept')->getOptionMenu();
        $this->view->begin    = $begin;
        $this->view->end      = date('Y-m-d', strtotime($end) - 24 * 3600);
        $this->view->days     = $days;
        $this->view->workday  = $workday;
        $this->view->dept     = $dept;
        $this->view->assign   = $assign;
        $this->view->allHour  = $days * $workday;
        $this->view->submenu  = 'staff';
        $this->display();
    }
}
