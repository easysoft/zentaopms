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
    public function getAll()
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
        return $this->createDAO($this->parseItemParam($paramString), 'select')->orderBy('lang,id')->fetchAll('key');
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
        $this->createDAO($this->parseItemParam($paramString), 'delete')->exec();
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
    public function createDAO($params, $method = 'select')
    {
        return $this->dao->$method('*')->from(TABLE_LANG)->where('1 = 1')
            ->beginIF($params['lang'])->andWhere('lang')->in($params['lang'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi();
    }

    public static function getModuleMenu($module = 'main', $rebuild = false)
    {
        if(empty($module)) $module = 'main';

        global $app, $lang, $config;
        // if(empty($app->customMenu)) $app->customMenu = array();
        // if(!$rebuild && !empty($app->customMenu[$module])) return $app->customMenu[$module];

        $menuConfig = $config->menucustom->$module;
        if(!isset($menuConfig) && common::inNoviceMode()) $menuConfig = $config->menu->$module['novice'];
        $isSetMenuConfig = isset($menuConfig);

        if($isSetMenuConfig)
        {
            if(is_string($menuConfig))
            {
                $menuConfigItems = explode(',', $menuConfig);
                $menuConfig = array();
                foreach($menuConfigItems as $menuConfigItem)
                {
                    $menuConfig[$menuConfigItem] = true;
                }
            }
        }

        $menu = array();
        $allMenu = $module == 'main' ? $lang->menu : $lang->$module->menu;

        foreach($allMenu as $name => $item)
        {
            $label  = '';
            $module = '';
            $method = '';
            $link = is_array($item) ? $item['link'] : $item;
            if(strpos($link, '|') !== false)
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
            }
            else
            {
                $label = $link;
            }

            if(commonModel::hasPriv($module, $method))
            {
                $itemLink = '';
                if($module && $method)
                {
                    $itemLink = array('module' => $module, 'method' => $method);
                    if($link[3]) $itemLink['vars'] = $link[3];
                    if(is_array($item))
                    {
                        $itemLink['subModule'] = $item['subModule'];
                        $itemLink['alias']     = $item['alias'];
                        $itemLink['target']    = $item['target'];
                        $itemLink['float']     = $item['float'];
                    }
                }

                $menuItem = new stdclass();
                $menuItem->name   = $name;
                $menuItem->link   = $itemLink;
                $menuItem->text   = $label;
                $menuItem->source = $item;
                $menuItem->hidden = $isSetMenuConfig && (!$menuConfig[$name]);

                $menu[] = $menuItem;
            }
        }
        // $app->customMenu[$module] = $menu;
        return $menu;
    }

    public static function getMainMenu($rebuild = false)
    {
        return self::getModuleMenu('main', $rebuild);
    }

    public static function getFeatureMenu($module, $method)
    {
        global $app, $lang, $config;
        $menucustomKey = 'menucustom' . $module;

        $app->loadLang($module);
        $featurebar = '';
        if(isset($lang->$module->featurebar[$method])) $featurebar = $lang->$module->featurebar[$method];
        $menucustomKey = 'menucustom' . $module;
        return $featurebar;
        if($featurebar)
        {
            $menuOrder  = array();
            $menuStatus = array();
            if(isset($config->$menucustomKey->$method))
            {
                $menuStatus = json_decode($config->$menucustomKey->$method, true);
                foreach($menuStatus as $menuKey => $status) $menuOrder[] = $menuKey;
            }

            /* Merge all menu. */
            $inOrderMenu    = ',' . join(',', $menuOrder) . ',';
            $notInOrderMenu = array();
            foreach($featurebar as $menuKey => $menuName)
            {
                if(strpos($inOrderMenu, ",$menuKey,") === false) $notInOrderMenu[] = $menuKey;
            }
            if($notInOrderMenu)
            {
                $order = count($menuOrder);
                foreach($notInOrderMenu as $menuKey)
                {
                    $menuOrder[$order] = $menuKey;
                    $order++;
                }
            }
            ksort($menuOrder, SORT_ASC);

            $processedMenu = new stdclass();
            foreach($menuOrder as $order => $menuKey)
            {
                $menuContent = $featurebar[$menuKey];
                $menuContent = array('link' => $menuContent);
                $menuContent['status'] = isset($menuStatus[$menuKey]) ? $menuStatus[$menuKey] : 'show';
                $menuContent['order']  = ($order + 1) * 5;

                $processedMenu->$menuKey = $menuContent;
            }
            $processedMenus['featurebar'] = $processedMenu;
        }
    }

    public static function getCustomMenu($module, $method)
    {
        global $app, $lang, $config;
        if(!isset($lang->$module->menu)) return array();
        $allMenu['main']   = $lang->menu;
        $allMenu['module'] = $lang->$module->menu;

        /* Process main and module menu. */
        $processedMenus = array();
        foreach($allMenu as $type => $menu)
        {
            $menucustom = '';
            $menuOrder  = array();
            $menuStatus = array();
            if($type == 'main')  $menucustom = isset($config->menucustom->main) ? $config->menucustom->main : '';
            if($type == 'module')$menucustom = isset($config->menucustomModule->$module) ? $config->menucustomModule->$module : '';

            /* Get order and status from config. */
            if($menucustom)
            {
                $menuStatus = json_decode($menucustom, true);
                $i = 1;
                foreach($menuStatus as $menuKey => $status)
                {
                    $order = $i * 5;
                    $menuOrder[$order] = $menuKey;
                    $i++;
                }
            }
            if(empty($menuOrder)) $menuOrder = $type == 'main' ? $lang->menuOrder : $lang->$module->menuOrder;

            /* Merge all menu. */
            $inOrderMenu    = ',' . join(',', $menuOrder) . ',';
            $notInOrderMenu = array();
            foreach($menu as $menuKey => $menuName)
            {
                if(strpos($inOrderMenu, ",$menuKey,") === false) $notInOrderMenu[] = $menuKey;
            }
            if($notInOrderMenu)
            {
                $order = count($menuOrder) * 5;
                foreach($notInOrderMenu as $menuKey)
                {
                    $order = $order + 5;
                    $menuOrder[$order] = $menuKey;
                }
            }
            ksort($menuOrder, SORT_ASC);

            /* Rebuild menu. */
            $processedMenu = new stdclass();
            foreach($menuOrder as $order => $menuKey)
            {
                if(!isset($menu->$menuKey)) continue;
                $menuContent = $menu->$menuKey;
                if(is_string($menuContent)) $menuContent = array('link' => $menuContent);
                $menuContent['status'] = isset($menuStatus[$menuKey]) ? $menuStatus[$menuKey] : 'show';
                $menuContent['order']  = $order;

                if(strpos($menuContent['link'], '|') !== false)
                {
                    list($menuTitle, $menuModule, $menuMethod) = explode('|', $menuContent['link']);
                    if($menuContent['status'] == 'show' and !common::hasPriv($menuModule, $menuMethod)) $menuContent['status'] = 'hide';
                }

                $processedMenu->$menuKey = $menuContent;
            }
            $processedMenus[$type] = $processedMenu;
        }

        /* Process featurebar. */
        $app->loadLang($module);
        $featurebar = '';
        if(isset($lang->$module->featurebar[$method])) $featurebar = $lang->$module->featurebar[$method];
        $menucustomKey = 'menucustom' . $module;
        if($featurebar)
        {
            $menuOrder  = array();
            $menuStatus = array();
            if(isset($config->$menucustomKey->$method))
            {
                $menuStatus = json_decode($config->$menucustomKey->$method, true);
                foreach($menuStatus as $menuKey => $status) $menuOrder[] = $menuKey;
            }

            /* Merge all menu. */
            $inOrderMenu    = ',' . join(',', $menuOrder) . ',';
            $notInOrderMenu = array();
            foreach($featurebar as $menuKey => $menuName)
            {
                if(strpos($inOrderMenu, ",$menuKey,") === false) $notInOrderMenu[] = $menuKey;
            }
            if($notInOrderMenu)
            {
                $order = count($menuOrder);
                foreach($notInOrderMenu as $menuKey)
                {
                    $menuOrder[$order] = $menuKey;
                    $order++;
                }
            }
            ksort($menuOrder, SORT_ASC);

            $processedMenu = new stdclass();
            foreach($menuOrder as $order => $menuKey)
            {
                $menuContent = $featurebar[$menuKey];
                $menuContent = array('link' => $menuContent);
                $menuContent['status'] = isset($menuStatus[$menuKey]) ? $menuStatus[$menuKey] : 'show';
                $menuContent['order']  = ($order + 1) * 5;

                $processedMenu->$menuKey = $menuContent;
            }
            $processedMenus['featurebar'] = $processedMenu;
        }

        return $processedMenus;
    }
}
