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

    /**
     * Build menu data from config
     * @param  object          $allMenu
     * @param  string | array  $menuConfig
     * @access public
     * @return array
     */
    public static function buildMenuConfig($allMenu, $menuConfig)
    {
        global $app, $lang, $config;
        $isSetMenuConfig = isset($menuConfig);
        $menu = array();
        $order = 1;
        $menuConfigMap = array();

        if($isSetMenuConfig)
        {
            if(is_string($menuConfig))
            {
                $menuConfigItems = explode(',', $menuConfig);
                foreach($menuConfigItems as $menuConfigItem)
                {
                    $item = new stdclass();
                    $item->name   = $menuConfigItem;
                    $item->order  = $order++;
                    $item->hidden = false;
                    $menuConfigMap[$item->name] = $item;
                }
            }
            else if(is_array($menuConfig))
            {
                foreach($menuConfig as $menuConfigItem)
                {
                    if(!isset($menuConfigItem->order)) $menuConfigItem->order = $order++;
                    $menuConfigMap[$menuConfigItem->name] = $menuConfigItem;
                }
            }
            else
            {
                $isSetMenuConfig = false;
            }
        }

        foreach($allMenu as $name => $item)
        {
            $label  = '';
            $module = '';
            $method = '';
            $float  = '';
            $fixed  = '';

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

                $hidden = !$fixed && $isSetMenuConfig && isset($menuConfigMap[$name]) && isset($menuConfigMap[$name]->hidden) && $menuConfigMap[$name]->hidden;

                $menuItem = new stdclass();
                $menuItem->name   = $name;
                $menuItem->link   = $itemLink;
                $menuItem->text   = $label;
                $menuItem->order  = $fixed ? 0 : ($isSetMenuConfig && isset($menuConfigMap[$name]) && isset($menuConfigMap[$name]->order) ? $menuConfigMap[$name]->order : $order++);
                if($float)  $menuItem->float   = $float;
                if($fixed)  $menuItem->fixed   = $fixed;
                if($hidden) $menuItem->hidden  = $hidden;

                while(isset($menu[$menuItem->order])) { $menuItem->order++; }
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
        if(empty($app->customMenu)) $app->customMenu = array();
        if(!$rebuild && !empty($app->customMenu[$module])) return $app->customMenu[$module];

        $menuConfig = $config->menucustom->$module;
        if(!empty($menuConfig)) $menuConfig = json_decode($menuConfig);
        if(!isset($menuConfig) && common::inNoviceMode()) $menuConfig = $config->menu->$module['novice'];

        $allMenu = $module == 'main' ? $lang->menu : $lang->$module->menu;
        $menu    = self::buildMenuConfig($allMenu, $menuConfig);

        $app->customMenu[$module] = $menu;
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

        $configKey  = 'feature_' . $module . '_' . $method;
        $allMenu    = isset($lang->$module->featurebar[$method]) ? $lang->$module->featurebar[$method] : null;
        $menuConfig = '';
        if(isset($config->menucustom->$configKey)) $menuConfig = $config->menucustom->$configKey;
        if(!empty($menuConfig)) $menuConfig = json_decode($menuConfig);
        return $allMenu ? self::buildMenuConfig($allMenu, $menuConfig) : null;
    }

    /**
     * Save custom menu to config
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function saveCustomMenu($menu, $module, $method)
    {
        $account    = $this->app->user->account;
        $settingKey = '';

        if(!is_string($menu)) $menu = json_encode($menu);

        if(empty($method))
        {
            $settingKey = "$account.common.menucustom.$module";
        }
        else
        {
            $settingKey = "$account.common.menucustom.feature_$module_$method";
        }

        $this->loadModel('setting')->setItem($settingKey, $menu);
    }
}
