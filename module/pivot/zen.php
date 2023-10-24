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
     * @access public
     * @return void
     */
    public function prepare4Preview($dimension, $groupID, $module, $method, $params)
    {
        $params = helper::safe64Decode($params);

        if(!$groupID) $groupID = $this->getDefaultGroup($dimension);
        if(!$module || !$method) list($module, $method, $params) = $this->getDefaultPivotParams($dimension, $groupID);

        if(!empty($module) && !empty($method) && $method != 'show' && !common::hasPriv($module, $method)) $this->loadModel('common')->deny('pivot', $method);

        $this->setFeatureBar($dimension);

        $this->view->sidebar   = $this->getSidebar($dimension, $groupID, $module, $method, $params);
        $this->view->dimension = $dimension;
        $this->view->groupID   = $groupID;
        $this->view->group     = $this->loadModel('tree')->getByID($groupID);
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->params    = $params;

        /* This session is used to pivot-preview page, when design edit or delete a pivot in pivot-preview, use session to back current dimension and group. */
        $this->session->set('backDimension', $dimension);
        $this->session->set('backGroup',     $groupID);

        if(empty($this->view->title)) $this->view->title = $this->lang->pivot->list;
    }

    /**
     * Get default group of a dimension.
     *
     * @param  int    $dimension
     * @access public
     * @return string
     */
    public function getDefaultGroup($dimension)
    {
        $group = $this->getFirstGroup($dimension);
        if(!$group) return 0;

        return $group->id;
    }

    /**
     * Get first group of a dimension.
     *
     * @param  int    $dimension
     * @access public
     * @return int
     */
    public function getFirstGroup($dimension)
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
     * @access public
     * @return array
     */
    public function getDefaultPivotParams($dimension, $group)
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
     * @access public
     * @return void
     */
    public function setFeatureBar($dimension)
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
     * Get sidebar of pivot.
     *
     * @param  int    $dimension
     * @param  int    $group
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return string
     */
    public function getSidebar($dimension, $group, $module, $method, $params)
    {
        if(!$group) return '';

        $currentGroup = $this->loadModel('tree')->getByID($group);
        if(empty($currentGroup) || $currentGroup->grade != 1) return '';

        $groups = $this->dao->select('id, grade, name, collector')->from(TABLE_MODULE)
            ->where('deleted')->eq('0')
            ->andWhere('root')->eq($dimension)
            ->andWhere('path')->like("$currentGroup->path%")
            ->orderBy('`order`')
            ->fetchAll();
        if(!$groups) return '';

        $pivotID = 0;
        if($module == 'pivot' && $method == 'show')
        {
            parse_str($params, $params);
            if(isset($params['pivotID'])) $pivotID = $params['pivotID'];
        }

        $clientLang = $this->app->getClientLang();
        if(!isset($this->config->langs[$clientLang])) $clientLang = 'zh-cn';

        $index   = 1;
        $sidebar = '';
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

            if($pivots) $pivots = $this->pivot->processPivot($pivots, false);
            if($group->grade == 2)
            {
                $class = $index == 1 ? 'open' : 'closed';
                $sidebar .= "<li class='$class'><a>$group->name</a><ul>";
            }

            foreach($pivots as $pivot)
            {
                $class     = "pivot-{$pivot->id}";
                $pivotName = $pivot->name;
                $params    = helper::safe64Encode("dimensionID=$pivot->dimension&groupID=$pivot->group&pivotID=$pivot->id");

                if($module == 'pivot' && $method == 'show' && $pivotID == $pivot->id)
                {
                    $class .= ' active';

                    $this->view->title = $pivotName;
                }

                $pivotLink = helper::createLink('pivot', 'preview', "dimension=$dimension&group=$currentGroup->id&module=pivot&method=show&params=$params");
                $sidebar  .= "<li class='$class'>" . html::a($pivotLink, $pivotName, '', "title='$pivotName'") . '</li>';
            }

            if($group->grade == 2) $sidebar .= '</ul></li>';

            $index++;
        }

        $sidebar .= $this->getBuiltinSidebar($dimension, $currentGroup, $module, $method);

        if($sidebar) $sidebar = "<ul id='pivotGroups' class='tree' data-ride='tree'>" . $sidebar . '</ul>';

        return $sidebar;
    }

    /**
     * Display the built-in pivots in the first dimension.
     *
     * @param  int    $dimension
     * @param  object $group
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string
     */
    public function getBuiltinSidebar($dimension, $group, $module, $method)
    {
        $firstDimension = $this->loadModel('dimension')->getFirst();
        if($dimension != $firstDimension->id) return '';

        $collector = $group->collector;
        if(empty($this->lang->pivotList->$collector->lists)) return '';

        $sidebar = '';
        $module  = strtolower($module);
        $method  = strtolower($method);

        ksort($this->lang->pivotList->$collector->lists);
        foreach($this->lang->pivotList->$collector->lists as $item)
        {
            $items = explode('|', $item . '|');
            if(count($items) < 4) continue;

            list($label, $moduleName, $methodName, $params) = $items;

            $class = '';
            if($module == strtolower($moduleName) && $method == strtolower($methodName))
            {
                $class = "class='active'";

                $this->view->title = $label;
            }

            $params = helper::safe64Encode($params);
            if(common::hasPriv($moduleName, $methodName)) $sidebar .= "<li $class>" . html::a(helper::createLink('pivot', 'preview', "dimension=$dimension&group=$group->id&module=$moduleName&method=$methodName&params=$params"), $label, '', "title='$label'") . '</li>';
        }

        return $sidebar;
    }
}
