<?php
/**
 * The model file of custom module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class customModel extends model
{
    /**
     * Get all custom lang.
     *
     * @access public
     * @return array
     */
    public function getAllLang()
    {
        $allCustomLang = $this->dao->select('*')->from(TABLE_LANG)->orderBy('lang,id')->fetchAll('id');

        $currentLang   = $this->app->getClientLang();
        $processedLang = array();
        foreach($allCustomLang as $id => $customLang)
        {
            if($customLang->lang != $currentLang and $customLang->lang != 'all') continue;
            $processedLang[$customLang->module][$customLang->section][$customLang->key] = $customLang->value;
        }

        return $processedLang;
    }

    /**
     * Set value of an item. 
     * 
     * @param  string      $path     zh-cn.story.soucreList.customer.1
     * @param  string      $value 
     * @access public
     * @return void
     */
    public function setItem($path, $value = '')
    {
        $level    = substr_count($path, '.');
        $section  = '';
        $system   = 1;

        if($level <= 1) return false;
        if($level == 2) list($lang, $module, $key) = explode('.', $path);
        if($level == 3) list($lang, $module, $section, $key) = explode('.', $path);
        if($level == 4) list($lang, $module, $section, $key, $system) = explode('.', $path);

        $item = new stdclass();
        $item->lang    = $lang;
        $item->module  = $module;
        $item->section = $section;
        $item->key     = $key;
        $item->value   = $value;
        $item->system  = $system;

        $this->dao->replace(TABLE_LANG)->data($item)->exec();
    }

    /**
     * Get some items 
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function getItems($paramString)
    {
        return $this->prepareSQL($this->parseItemParam($paramString), 'select')->orderBy('lang,id')->fetchAll('key');
    }

    /**
     * Delete items.
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function deleteItems($paramString)
    {
        $this->prepareSQL($this->parseItemParam($paramString), 'delete')->exec();
    }

    /**
     * Parse the param string for select or delete items.
     * 
     * @param  string    $paramString     lang=xxx&module=story&section=sourceList&key=customer and so on.
     * @access public
     * @return array
     */
    public function parseItemParam($paramString)
    {
        /* Parse the param string into array. */
        parse_str($paramString, $params); 

        /* Init fields not set in the param string. */
        $fields = 'lang,module,section,key';
        $fields = explode(',', $fields);
        foreach($fields as $field) if(!isset($params[$field])) $params[$field] = '';

        return $params;
    }

    /**
     * Create a DAO object to select or delete one or more records.
     * 
     * @param  array  $params     the params parsed by parseItemParam() method.
     * @param  string $method     select|delete.
     * @access public
     * @return object
     */
    public function prepareSQL($params, $method = 'select')
    {
        return $this->dao->$method('*')->from(TABLE_LANG)->where('1 = 1')
            ->beginIF($params['lang'])->andWhere('lang')->in($params['lang'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi();
    }

    /**
     * Build menu data from config
     * @param  object          $allMenu
     * @param  string | array  $customMenu
     * @access public
     * @return array
     */
    public static function setMenuByConfig($allMenu, $customMenu, $module = '')
    {
        global $app, $lang, $config;
        $menu            = array();
        $menuModuleName  = $module;
        $order           = 1;
        $customMenuMap   = array();
        $isTutorialMode  = commonModel::isTutorialMode();

        if($customMenu)
        {
            if(is_string($customMenu))
            {
                $customMenuItems = explode(',', $customMenu);
                foreach($customMenuItems as $customMenuItem)
                {
                    $item = new stdclass();
                    $item->name   = $customMenuItem;
                    $item->order  = $order++;
                    $item->hidden = false;
                    $customMenuMap[$item->name] = $item;
                }
                foreach($allMenu as $name => $item)
                {
                    if(!isset($customMenuMap[$name]))
                    {
                        $item = new stdclass();
                        $item->name   = $name;
                        $item->hidden = true;
                        $item->order  = $order++;
                        $customMenuMap[$name] = $item;
                    }
                }
            }
            elseif(is_array($customMenu))
            {
                foreach($customMenu as $customMenuItem)
                {
                    if(!isset($customMenuItem->order)) $customMenuItem->order = $order;
                    $customMenuMap[$customMenuItem->name] = $customMenuItem;
                    $order++;
                }
            }
        }
        elseif($module)
        {
            $menuOrder = ($module == 'main' and isset($lang->menuOrder)) ? $lang->menuOrder : (isset($lang->$module->menuOrder) ? $lang->$module->menuOrder : array());
            if($menuOrder)
            {
                ksort($menuOrder);
                foreach($menuOrder as $name)
                {
                    $item = new stdclass();
                    $item->name   = $name;
                    $item->hidden = false;
                    $item->order  = $order++;
                    $customMenuMap[$name] = $item;
                }
            }
        }

        /* Merge fileMenu and customMenu. */
        foreach($customMenuMap as $name => $item)
        {
            if(is_object($allMenu) and !isset($allMenu->$name))$allMenu->$name = $item;
            if(is_array($allMenu)  and !isset($allMenu[$name]))$allMenu[$name] = $item;
        }

        foreach($allMenu as $name => $item)
        {
            if(is_object($item)) $item = (array)$item;

            $label  = '';
            $module = '';
            $method = '';
            $float  = '';
            $fixed  = '';

            $link = (is_array($item) and isset($item['link'])) ? $item['link'] : $item;
            /* The variable of item has not link and is not link then ignore it. */
            if(!is_string($link)) continue;

            $label   = $link;
            $hasPriv = true;
            if(strpos($link, '|') !== false)
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
                $hasPriv = commonModel::hasPriv($module, $method);
            }

            if($isTutorialMode || $hasPriv)
            {
                $itemLink = '';
                if($module && $method)
                {
                    $itemLink = array('module' => $module, 'method' => $method);
                    if(isset($link[3])) $itemLink['vars'] = $link[3];
                    if(is_array($item))
                    {
                        if(isset($item['subModule'])) $itemLink['subModule'] = $item['subModule'];
                        if(isset($item['alias']))     $itemLink['alias']     = $item['alias'];
                        if(isset($item['target']))    $itemLink['target']    = $item['target'];
                    }
                }

                if(is_array($item))
                {
                    if(isset($item['float'])) $float = $item['float'];
                    if(isset($item['fixed'])) $fixed = $item['fixed'];
                }

                $hidden = !$fixed && isset($customMenuMap[$name]) && isset($customMenuMap[$name]->hidden) && $customMenuMap[$name]->hidden;
                if(strpos($name, 'QUERY') === 0 and !isset($customMenuMap[$name])) $hidden = true;

                $menuItem = new stdclass();
                $menuItem->name   = $name;
                $menuItem->link   = $itemLink;
                $menuItem->text   = $label;
                $menuItem->order  = $fixed ? -1 : (isset($customMenuMap[$name]) && isset($customMenuMap[$name]->order) ? $customMenuMap[$name]->order : $order++);
                if($float)  $menuItem->float   = $float;
                if($fixed)  $menuItem->fixed   = $fixed;
                if($hidden) $menuItem->hidden  = $hidden;
                if($isTutorialMode) $menuItem->tutorial = true;

                /* Hidden menu by config in mobile. */
                if($app->viewType == 'mhtml' and isset($config->custom->moblieHidden[$menuModuleName]) and in_array($name, $config->custom->moblieHidden[$menuModuleName])) $menuItem->hidden = 1;

                while(isset($menu[$menuItem->order])) $menuItem->order++;
                $menu[$menuItem->order] = $menuItem;
            }
        }

        ksort($menu, SORT_NUMERIC);
        return array_values($menu);
    }

    /**
     * Get module menu data, if module is 'main' then return main menu
     * @param  string   $module
     * @param  boolean  $rebuild
     * @access public
     * @return array
     */
    public static function getModuleMenu($module = 'main', $rebuild = false)
    {
        if(empty($module)) $module = 'main';

        global $app, $lang, $config;
        $allMenu = $module == 'main' ? $lang->menu : (isset($lang->$module->menu) ? $lang->$module->menu : $lang->my->menu);
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);

        if($module != 'main' and isset($lang->menugroup->$module)) $module = $lang->menugroup->$module;
        $flowModule = $config->global->flow . '_' . $module;
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(commonModel::isTutorialMode() && $module === 'main')$customMenu = 'my,product,project,qa,company';
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);

        $menu = self::setMenuByConfig($allMenu, $customMenu, $module);

        return $menu;
    }

    /**
     * Get main menu data
     * @param  boolean $rebuild
     * @access public
     * @return array
     */
    public static function getMainMenu($rebuild = false)
    {
        return self::getModuleMenu('main', $rebuild);
    }

    /**
     * Get feature menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public static function getFeatureMenu($module, $method)
    {
        global $app, $lang, $config;
        $app->loadLang($module);
        customModel::mergeFeatureBar($module, $method);

        $configKey  = $config->global->flow . '_feature_' . $module . '_' . $method;
        $allMenu    = isset($lang->$module->featureBar[$method]) ? $lang->$module->featureBar[$method] : null;
        $customMenu = '';
        if(!commonModel::isTutorialMode() && isset($config->customMenu->$configKey)) $customMenu = $config->customMenu->$configKey;
        if(!empty($customMenu) && is_string($customMenu)) $customMenu = json_decode($customMenu);
        return $allMenu ? self::setMenuByConfig($allMenu, $customMenu) : null;
    }

    /**
     * Merge shortcut query in featureBar.
     * 
     * @param  string $module 
     * @param  string $method 
     * @access public
     * @return void
     */
    public static function mergeFeatureBar($module, $method)
    {
        global $lang, $app, $dbh;
        if(!isset($lang->$module->featureBar[$method])) return;
        $queryModule = $module == 'project' ? 'task' : ($module == 'product' ? 'story' : $module);
        $shortcuts   = $dbh->query('select id, title from ' . TABLE_USERQUERY . " where `account` = '{$app->user->account}' AND `module` = '{$queryModule}' order by id")->fetchAll();
        foreach($shortcuts as $shortcut)
        {
            $shortcutID = 'QUERY' . $shortcut->id;
            $lang->$module->featureBar[$method][$shortcutID] = $shortcut->title;
        }
    }

    /**
     * Save custom menu to config
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function saveCustomMenu($menu, $module, $method = '')
    {
        $account    = $this->app->user->account;
        $settingKey = '';

        if(!is_string($menu)) $menu = json_encode($menu);

        $flow = $this->config->global->flow;
        if(empty($method))
        {
            $settingKey = "$account.common.customMenu.{$flow}_{$module}";
        }
        else
        {
            $settingKey = "$account.common.customMenu.{$flow}_feature_{$module}_{$method}";
        }

        $this->loadModel('setting')->setItem($settingKey, $menu);
    }
}
