<?php
class pivotZen extends pivot
{
    /**
     * Prepare for preview a pivot.
     *
     * @param  int    $dimension
     * @param  string $group
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function prepare4Preview($dimension, $group, $module, $method, $params)
    {
        $params = helper::safe64Decode($params);

        if(!$dimension) $dimension = $this->getDefaultDimension();
        if(!$group) $group = $this->getDefaultGroup($dimension);
        if(!$module || !$method) list($module, $method, $params) = $this->getDefaultMethod($dimension, $group);

        if(!empty($module) && !empty($method) && $method != 'show' && !common::hasPriv($module, $method)) $this->loadModel('common')->deny('pivot', $method);

        $this->view->sidebar   = $this->getSidebar($dimension, $group, $module, $method, $params);
        $this->view->dimension = $dimension;
        $this->view->group     = $group;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->params    = $params;

        $pivot = new stdclass();
        parse_str($params, $output);
        if(isset($output['pivotID'])) $pivot = $this->pivot->getByID($output['pivotID']);
        $this->view->pivot = $pivot;

        if(empty($this->view->title)) $this->view->title = $this->lang->pivot->list;
    }

    /**
     * Get default dimension of pivot.
     *
     * @access public
     * @return int
     */
    public function getDefaultDimension()
    {
        return 1;
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
        foreach($this->lang->pivotList as $group => $list)
        {
            if(empty($list->lists)) continue;

            foreach($list->lists as $item)
            {
                $items = explode('|', $item);
                if(count($items) < 3) continue;

                list($label, $module, $method) = $items;

                if(common::hasPriv($module, $method)) return $group;
            }
        }

        return '';
    }

    /**
     * Get default module name and method name and params of pivot in a group.
     *
     * @param  int    $dimension
     * @param  string $group
     * @access public
     * @return array
     */
    public function getDefaultMethod($dimension, $group)
    {
        if(empty($this->lang->pivotList->$group->lists)) return array('', '', '');

        foreach($this->lang->pivotList->$group->lists as $item)
        {
            $items = explode('|', $item . '|');
            if(count($items) < 4) continue;

            list($label, $module, $method, $params) = $items;

            if(common::hasPriv($module, $method)) return array($module, $method, $params);
        }

        return array('', '', '');
    }

    /**
     * Get sidebar of pivot.
     *
     * @param  int    $dimension
     * @param  string $group
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return string
     */
    public function getSidebar($dimension, $group, $module, $method, $params)
    {
        if(empty($this->lang->pivotList->$group->lists)) return '';

        $sidebar = '';
        $module  = strtolower($module);
        $method  = strtolower($method);

        ksort($this->lang->pivotList->$group->lists);
        foreach($this->lang->pivotList->$group->lists as $item)
        {
            $items = explode('|', $item . '|');
            if(count($items) < 4) continue;

            list($label, $moduleName, $methodName, $params) = $items;

            $class = '';
            if($module == strtolower($moduleName) && $method == strtolower($methodName))
            {
                $class = 'selected';

                $this->view->title = $label;
            }

            $params = helper::safe64Encode($params);
            if(common::hasPriv($moduleName, $methodName)) $sidebar .= html::a(helper::createLink('pivot', 'preview', "dimension=$dimension&group=$group&module=$moduleName&method=$methodName&params=$params"), '<i class="icon icon-file-text"></i> ' . $label, '', "class='$class' title='$label'");
        }

        return $sidebar;
    }
}
