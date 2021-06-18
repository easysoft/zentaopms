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
        $currentLang = $this->app->getClientLang();

        try
        {
            $sql  = $this->dao->select('*')->from(TABLE_LANG)->where('`lang`')->in("$currentLang,all")->orderBy('lang,id')->get();
            $stmt = $this->dbh->query($sql);

            $allCustomLang = array();
            while($row = $stmt->fetch()) $allCustomLang[$row->id] = $row;
        }
        catch(PDOException $e)
        {
            return false;
        }

        $sectionLang = array();
        foreach($allCustomLang as $customLang)
        {
            $sectionLang[$customLang->module][$customLang->section][$customLang->lang] = $customLang->lang;
        }

        $processedLang = array();
        foreach($allCustomLang as $id => $customLang)
        {
            if(isset($sectionLang[$customLang->module][$customLang->section]['all']) && isset($sectionLang[$customLang->module][$customLang->section][$currentLang]) && $customLang->lang == 'all') continue;
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
        $menu           = array();
        $menuModuleName = $module;
        $order          = 1;
        $customMenuMap  = array();
        $openApp        = $app->openApp;
        $isTutorialMode = commonModel::isTutorialMode();

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
            $menuOrder = ($module == 'main' and isset($lang->menuOrder)) ? $lang->menuOrder : (isset($lang->menu->{$module}['menuOrder']) ? $lang->menu->{$module}['menuOrder'] : array());
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
            if(is_object($allMenu) and !isset($allMenu->$name)) $allMenu->$name = $item;
            if(is_array($allMenu)  and !isset($allMenu[$name])) $allMenu[$name] = $item;
        }

        foreach($allMenu as $name => $item)
        {
            if(is_object($item)) $item = (array)$item;

            $label     = '';
            $module    = '';
            $method    = '';
            $class     = '';
            $subModule = '';
            $subMenu   = '';
            $dropMenu  = '';
            $alias     = '';
            $exclude   = '';

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
                    if(is_array($item) and isset($item['target'])) $itemLink['target'] = $item['target'];
                }

                if(is_array($item))
                {
                    if(isset($item['class']))     $class     = $item['class'];
                    if(isset($item['subModule'])) $subModule = $item['subModule'];
                    if(isset($item['subMenu']))   $subMenu   = $item['subMenu'];
                    if(isset($item['dropMenu']))  $dropMenu  = $item['dropMenu'];
                    if(isset($item['alias']))     $alias     = $item['alias'];
                    if(isset($item['exclude']))   $exclude   = $item['exclude'];
                }

                $hidden = isset($customMenuMap[$name]) && isset($customMenuMap[$name]->hidden) && $customMenuMap[$name]->hidden;

                if(is_array($item) and (isset($item['subMenu']) or isset($item['dropMenu'])))
                {
                    foreach(array('subMenu', 'dropMenu') as $key)
                    {
                        if(!isset($item[$key])) continue;
                        foreach($item[$key] as $subItem)
                        {
                            if(isset($subItem->link['module']) && isset($subItem->link['method']))
                            {
                                $subItem->hidden = !common::hasPriv($subItem->link['module'], $subItem->link['method']);
                            }
                        }
                        if(isset($customMenuMap[$name]->$key))
                        {
                            foreach($customMenuMap[$name]->$key as $subItem)
                            {
                                if(isset($subItem->hidden) && isset($item[$key][$subItem->name])) $item[$key][$subItem->name]->hidden = $subItem->hidden;
                            }
                        }
                    }
                }

                if(strpos($name, 'QUERY') === 0 and !isset($customMenuMap[$name])) $hidden = false;

                $menuItem = new stdclass();
                $menuItem->name  = $name;
                $menuItem->link  = $itemLink;
                $menuItem->text  = $label;
                $menuItem->order = (isset($customMenuMap[$name]) && isset($customMenuMap[$name]->order) ? $customMenuMap[$name]->order : $order++);
                if($hidden)   $menuItem->hidden    = $hidden;
                if($class)    $menuItem->class     = $class;
                if($subModule)$menuItem->subModule = $subModule;
                if($subMenu)  $menuItem->subMenu   = $subMenu;
                if($dropMenu) $menuItem->dropMenu  = $dropMenu;
                if($alias)    $menuItem->alias     = $alias;
                if($exclude)  $menuItem->exclude   = $exclude;
                if($isTutorialMode) $menuItem->tutorial = true;

                /* Hidden menu by config in mobile. */
                if($app->viewType == 'mhtml' and isset($config->custom->moblieHidden[$menuModuleName]) and in_array($name, $config->custom->moblieHidden[$menuModuleName])) $menuItem->hidden = 1;

                while(isset($menu[$menuItem->order])) $menuItem->order++;
                $menu[$menuItem->order] = $menuItem;
            }
        }

        ksort($menu, SORT_NUMERIC);

        /* Set divider in main and module menu. */
        if(!isset($lang->$openApp->menuOrder)) $lang->$openApp->menuOrder = array();
        ksort($lang->$openApp->menuOrder, SORT_NUMERIC);

        $group = 0;
        $dividerOrders = array();
        foreach($lang->$openApp->menuOrder as $name)
        {
            if(isset($lang->$openApp->dividerMenu) and strpos($lang->$openApp->dividerMenu, ",{$name},") !== false) $group++;
            $dividerOrders[$name] = $group;
        }

        $isFirst = true; // No divider before First item.
        $group   = 0;
        foreach($menu as $item)
        {
            if(isset($dividerOrders[$item->name]) and $dividerOrders[$item->name] > $group)
            {
                $menu[$item->order]->divider = $isFirst ? false : true;
                $group = $dividerOrders[$item->name];
            }
            else
            {
                $isFirst = false;
                $menu[$item->order]->divider = false;
            }
        }

        return array_values($menu);
    }

    /**
     * Get module menu data, if module is 'main' then return main menu.
     * @param  string   $module
     * @param  boolean  $rebuild
     * @access public
     * @return array
     */
    public static function getModuleMenu($module = 'main', $rebuild = false)
    {
        global $app, $lang, $config;

        if(empty($module)) $module = 'main';

        $allMenu = new stdclass();
        if($module == 'main' and !empty($lang->menu)) $allMenu = $lang->menu;
        if($module != 'main' and isset($lang->menu->$module) and isset($lang->menu->{$module}['subMenu'])) $allMenu = $lang->menu->{$module}['subMenu'];
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);
        $flowModule = $config->global->flow . '_' . $module;
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(commonModel::isTutorialMode() && $module === 'main') $customMenu = 'my,product,project,qa,company';
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        if($module == 'my' && empty($config->global->scoreStatus)) unset($allMenu->score);

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
        $queryModule = $module == 'execution' ? 'task' : ($module == 'product' ? 'story' : $module);
        $shortcuts   = $dbh->query('select id, title from ' . TABLE_USERQUERY . " where `account` = '{$app->user->account}' AND `module` = '{$queryModule}' AND `shortcut` = '1' order by id")->fetchAll();

        if($shortcuts)
        {
            $lang->$module->featureBar[$method]['QUERY'] = $lang->custom->common;
            foreach($shortcuts as $shortcut) $lang->custom->queryList[$shortcut->id] = $shortcut->title;
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

        $setPublic = $this->post->setPublic;
        if(!is_string($menu)) $menu = json_encode($menu);

        $flow = $this->config->global->flow;
        if(empty($method))
        {
            $settingKey = "common.customMenu.{$flow}_{$module}";
        }
        else
        {
            $settingKey = "common.customMenu.{$flow}_feature_{$module}_{$method}";
        }

        $this->loadModel('setting')->setItem($account . '.' . $settingKey, $menu);
        if($setPublic) $this->setting->setItem('system.' . $settingKey, $menu);

        $this->loadModel('score')->create('ajax', 'customMenu');
    }

    /**
     * Get required fields by config.
     *
     * @param  object    $moduleConfig
     * @access public
     * @return array
     */
    public function getRequiredFields($moduleConfig)
    {
        $requiredFields = array();
        foreach($moduleConfig as $method => $subConfig)
        {
            if(is_object($subConfig) and isset($subConfig->requiredFields)) $requiredFields[$method] = trim(str_replace(' ', '', $subConfig->requiredFields));
        }

        return $requiredFields;
    }

    /**
     * Get module fields.
     *
     * @param  string $moduleName
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFormFields($moduleName, $method = '')
    {
        $fields       = array();
        $moduleLang   = $this->lang->$moduleName;
        $customFields = $this->config->custom->fieldList;
        if(isset($customFields[$moduleName]))
        {
            $fieldList = isset($customFields[$moduleName][$method]) ? $customFields[$moduleName][$method] : $customFields[$moduleName];
            if(!is_string($fieldList)) return $fields;

            if($moduleName == 'user' and $method == 'edit') $this->app->loadConfig('user');
            foreach(explode(',', $fieldList) as $fieldName)
            {
                if($moduleName == 'user' and $method == 'edit' and strpos($this->config->user->contactField, $fieldName) === false) continue;
                if($fieldName == 'comment') $fields[$fieldName] = $this->lang->comment;
                if(isset($moduleLang->$fieldName) and is_string($moduleLang->$fieldName)) $fields[$fieldName] = $moduleLang->$fieldName;

                if($moduleName == 'program')
                {
                    $fieldKey = substr($method, 0, 3) . ucfirst($fieldName);
                    if(isset($moduleLang->$fieldKey) and is_string($moduleLang->$fieldKey)) $fields[$fieldName] = $moduleLang->$fieldKey;
                }
            }
        }
        return $fields;
    }

    /**
     * Get UR and SR pairs.
     *
     * @access public
     * @return array
     */
    public function getURSRPairs()
    {
        $lang = $this->app->getClientLang();
        $langData = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)
            ->where('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('URSRList')
            ->fetchAll();
        if(empty($langData))
        {
            $URSR     = $this->loadModel('setting')->getURSR();
            $langData = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)->where('`key`')->eq($URSR)->andWhere('module')->eq('custom')->andWhere('section')->eq('URSRList')->fetchAll();
        }

        $URSRPairs = array();
        foreach($langData as $id => $content)
        {
            $value = json_decode($content->value);
            $URSRPairs[$content->key] = $this->config->URAndSR ? $value->URName . '/' . $value->SRName : $value->SRName;
        }

        return $URSRPairs;
    }

    /**
     * Get UR pairs.
     *
     * @access public
     * @return array
     */
    public function getURPairs()
    {
        $URSRList = $this->dao->select('`key`,`value`')->from(TABLE_LANG)->where('module')->eq('custom')->andWhere('section')->eq('URSRList')->andWhere('lang')->eq($this->app->clientLang)->fetchPairs();
        if(empty($URSRList))
        {
            $URSR     = $this->loadModel('setting')->getURSR();
            $URSRList = $this->dao->select('`key`,`value`')->from(TABLE_LANG)->where('module')->eq('custom')->andWhere('section')->eq('URSRList')->andWhere('`key`')->eq($URSR)->fetchPairs();
        }

        $URPairs = array();
        foreach($URSRList as $key => $value)
        {
            $URSR = json_decode($value);
            $URPairs[$key] = $URSR->URName;
        }

        return $URPairs;
    }

    /**
     * Get SR pairs.
     *
     * @access public
     * @return array
     */
    public function getSRPairs()
    {
        $URSRList = $this->dao->select('`key`,`value`')->from(TABLE_LANG)->where('module')->eq('custom')->andWhere('section')->eq('URSRList')->andWhere('lang')->eq($this->app->clientLang)->fetchPairs();
        if(empty($URSRList))
        {
            $URSR     = $this->loadModel('setting')->getURSR();
            $URSRList = $this->dao->select('`key`,`value`')->from(TABLE_LANG)->where('module')->eq('custom')->andWhere('section')->eq('URSRList')->andWhere('`key`')->eq($URSR)->fetchPairs();
        }

        $SRPairs = array();
        foreach($URSRList as $key => $value)
        {
            $URSR = json_decode($value);
            $SRPairs[$key] = $URSR->SRName;
        }

        return $SRPairs;
    }

    /**
     * Get UR and SR list.
     *
     * @access public
     * @return array
     */
    public function getURSRList()
    {
        $this->app->loadLang('custom');
        $lang = $this->app->getClientLang();

        $langData = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)
            ->where('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('URSRList')
            ->fetchAll();
        if(empty($langData))
        {
            $URSR     = $this->loadModel('setting')->getURSR();
            $langData = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)->where('`key`')->eq($URSR)->andWhere('module')->eq('custom')->andWhere('section')->eq('URSRList')->fetchAll();
        }

        $URSRList = array();
        foreach($langData as $id => $content)
        {
            $value = json_decode($content->value);
            $URSRList[$content->key]['SRName'] = $value->SRName;
            $URSRList[$content->key]['URName'] = $value->URName;
            $URSRList[$content->key]['system'] = $content->system;
        }

        return $URSRList;
    }

    /**
     * Save required fields.
     *
     * @param  int    $moduleName
     * @access public
     * @return void
     */
    public function saveRequiredFields($moduleName)
    {
        if(isset($this->config->system->$moduleName))   unset($this->config->system->$moduleName);
        if(isset($this->config->personal->$moduleName)) unset($this->config->personal->$moduleName);

        $this->loadModel($moduleName);
        $systemFields = $this->getRequiredFields($this->config->$moduleName);

        $data = fixer::input('post')->get();
        $requiredFields = array();
        if(!empty($systemFields))
        {
            foreach($systemFields as $method => $fields)
            {
                $optionFields    = isset($this->config->custom->fieldList[$moduleName][$method]) ? explode(',', $this->config->custom->fieldList[$moduleName][$method]) : array();
                $systemFieldList = explode(',', $this->config->$moduleName->$method->requiredFields);
                foreach($optionFields as $field)
                {
                    if(in_array($field, $systemFieldList))
                    {
                        $key = array_search($field, $systemFieldList);
                        unset($systemFieldList[$key]);
                    }
                }
                $systemField = implode(',', $systemFieldList);

                /* Keep the original required fields when the fields is empty. */
                if(!isset($data->requiredFields[$method]))
                {
                    $requiredFields[$method]['requiredFields'] = $systemField;
                    continue;
                }

                $fields = join(',', $data->requiredFields[$method]);
                foreach(explode(',', $systemField) as $field)
                {
                    $field = trim($field);
                    if(strpos(",$fields,", ",$field,") === false) $fields .= ",$field";
                }

                $requiredFields[$method]['requiredFields'] = $fields;
            }
        }

        $this->loadModel('setting');
        $this->setting->setItems("system.{$moduleName}", $requiredFields);
    }

    /**
     * Set product and project and sprint concept.
     *
     * @access public
     * @return void
     */
    public function setConcept()
    {
        $this->loadModel('setting');
        $this->setting->setItem('system.custom.sprintConcept', $this->post->sprintConcept);

        /* Change block title. */
        $oldConfig = isset($this->config->custom->sprintConcept) ? $this->config->custom->sprintConcept : '0';
        $newConfig = $this->post->sprintConcept;

        foreach($this->config->executionCommonList as $clientLang => $executionCommonList)
        {
            $this->dao->update(TABLE_BLOCK)->set("`title` = REPLACE(`title`, '{$executionCommonList[$oldConfig]}', '{$executionCommonList[$newConfig]}')")->where('source')->eq('execution')->exec();
        }
    }

    /**
     * Set UR and SR concept.
     *
     * @access public
     * @return bool
     */
    public function setURAndSR()
    {
        $data   = fixer::input('post')->get();
        $lang   = $this->app->getClientLang();
        $maxKey = $this->dao->select('max(cast(`key` as SIGNED)) as maxKey')->from(TABLE_LANG)
            ->where('section')->eq('URSRList')
            ->andWhere('module')->eq('custom')
            ->andWhere('lang')->eq($lang)
            ->fetch('maxKey');
        $maxKey = $maxKey ? $maxKey : 1;

        /* If has custom UR and SR name. */
        foreach($data->SRName as $key => $SRName)
        {
            if(isset($data->URName))  $URName = zget($data->URName, $key, '');
            if(!isset($data->URName)) $URName = $this->lang->URCommon;
            if(!$URName || !$SRName) continue;

            $URSRList = new stdclass();
            $URSRList->SRName = $SRName;
            $URSRList->URName = $URName;

            $value   = json_encode($URSRList);
            $maxKey += 1;

            $this->setItem("$lang.custom.URSRList.{$maxKey}.0", $value);
        }

        return true;
    }

    /**
     * Edit UR and SR concept.
     *
     * @param  int $key
     * @access public
     * @return bool
     */
    public function updateURAndSR($key = 0)
    {
        $lang = $this->app->getClientLang();
        $data = fixer::input('post')->get();

        if(!$data->SRName || !$data->URName) return false;

        $URSRList = new stdclass();
        $URSRList->SRName = $data->SRName;
        $URSRList->URName = $data->URName;

        $value = json_encode($URSRList);
        $this->dao->update(TABLE_LANG)->set('value')->eq($value)
            ->where('`key`')->eq($key)
            ->andWhere('section')->eq('URSRList')
            ->andWhere('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->exec();

        return true;
    }

    /**
     * Set story or requirement.
     *
     * @access public
     * @return void
     */
    public function setStoryRequirement()
    {
        if(!isset($_POST['storyRequirement'])) return true;
        $this->loadModel('setting')->setItem('system.custom.storyRequirement', $this->post->storyRequirement);

        $oldIndex = isset($this->config->custom->storyRequirement) ? $this->config->custom->storyRequirement : '0';
        $newIndex = $this->post->storyRequirement;

        foreach($this->config->storyCommonList as $clientLang => $commonList)
        {
            $this->dao->update(TABLE_BLOCK)->set("`title` = REPLACE(`title`, '{$commonList[$oldIndex]}', '{$commonList[$newIndex]}')")->where('source')->eq('product')->exec();
        }
    }
}
