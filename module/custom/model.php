<?php
declare(strict_types=1);
/**
 * The model file of custom module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class customModel extends model
{
    /**
     * 获取自定义语言项。
     * Get all custom lang.
     *
     * @access public
     * @return array|false
     */
    public function getAllLang(): array|false
    {
        $currentLang   = $this->app->getClientLang();
        $allCustomLang = $this->customTao->getCustomLang();
        if(!$allCustomLang) return false;

        $sectionLang = array();
        foreach($allCustomLang as $customLang)
        {
            $sectionLang[$customLang->module][$customLang->section][$customLang->lang] = $customLang->lang;
        }

        $processedLang = array();
        foreach($allCustomLang as $customLang)
        {
            if(isset($sectionLang[$customLang->module][$customLang->section]['all']) && isset($sectionLang[$customLang->module][$customLang->section][$currentLang]) && $customLang->lang == 'all') continue;

            /* Process list featureBar and more language. */
            if(strpos($customLang->section, 'featureBar-') !== false || strpos($customLang->section, 'moreSelects-') !== false)
            {
                $sections = explode('-', $customLang->section);
                $sections = array_reverse($sections);
                if(!isset($processedLang[$customLang->module])) $processedLang[$customLang->module] = array();
                $sectionArr = array($customLang->key => $customLang->value);
                foreach($sections as $section)
                {
                    $sectionKey = key($sectionArr);
                    $sectionArr[$section] = $sectionArr;
                    if($sectionKey != $section) unset($sectionArr[$sectionKey]);
                }
                if(!empty($sectionArr)) $processedLang[$customLang->module] = array_merge_recursive($processedLang[$customLang->module], $sectionArr);
            }
            else
            {
                $processedLang[$customLang->module][$customLang->section][$customLang->key] = $customLang->value;
            }
        }

        return $processedLang;
    }

    /**
     * 设置自定义语言项。
     * Set value of an item.
     *
     * @param  string $path     zh-cn.story.soucreList.customer.1
     * @param  string $value
     * @access public
     * @return bool
     */
    public function setItem(string $path, string $value = ''): bool
    {
        $level   = substr_count($path, '.');
        $section = '';
        $system  = 1;

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

        if(!$this->app->upgrading) $item->vision = $this->config->vision;
        $this->dao->replace(TABLE_LANG)->data($item)->exec();

        return !dao::isError();
    }

    /**
     * 获取自定义语言项。
     * Get value of custom items.
     *
     * @param  string $paramString see parseItemParam();
     * @access public
     * @return array
     */
    public function getItems(string $paramString): array
    {
        return $this->prepareSQL($this->parseItemParam($paramString), 'select')->orderBy('lang,id')->fetchAll('key');
    }

    /**
     * 删除自定义项。
     * Delete items.
     *
     * @param  string $paramString    see parseItemParam();
     * @access public
     * @return bool
     */
    public function deleteItems($paramString): bool
    {
        $this->prepareSQL($this->parseItemParam($paramString), 'delete')->exec();
        return !dao::isError();
    }

    /**
     * 解析选择或删除项的参数字符串。
     * Parse the param string for select or delete items.
     *
     * @param  string $paramString lang=xxx&module=story&section=sourceList&key=customer and so on.
     * @access public
     * @return array
     */
    public function parseItemParam(string $paramString): array
    {
        /* Parse the param string into array. */
        parse_str($paramString, $params);

        /* Init fields not set in the param string. */
        $fields = 'lang,module,section,key,vision';
        $fields = explode(',', $fields);
        foreach($fields as $field)
        {
            if(isset($params[$field])) continue;
            $params[$field] = '';
        }

        return $params;
    }

    /**
     * 创建一个DAO对象来选择或删除一条或多条记录。
     * Create a DAO object to select or delete one or more records.
     *
     * @param  array  $params the params parsed by parseItemParam() method.
     * @param  string $method select|delete.
     * @access public
     * @return object
     */
    public function prepareSQL(array $params, string $method = 'select'): object
    {
        return $this->dao->$method('*')->from(TABLE_LANG)->where('1 = 1')
            ->beginIF($params['lang'])->andWhere('lang')->in($params['lang'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi()
            ->beginIF($params['vision'])->andWhere('`vision`')->eq($params['vision'])->fi();
    }

    /**
     * 通过配置文件设置菜单。
     * Build menu data from config
     *
     * @param  object|array $allMenu
     * @param  string|array $customMenu
     * @param  string       $module
     * @access public
     * @return array
     */
    public static function setMenuByConfig(object|array $allMenu, string|array $customMenu, string $module = ''): array
    {
        global $app, $lang, $config;

        $tab = $app->tab;
        list($customMenuMap, $order) = static::buildCustomMenuMap($allMenu, $customMenu, $module);

        /* Merge fileMenu && customMenu. */
        foreach($customMenuMap as $name => $item)
        {
            if(is_object($allMenu) && !isset($allMenu->{$name})) $allMenu->{$name} = $item;
            if(is_array($allMenu)  && !isset($allMenu[$name]))   $allMenu[$name]   = $item;
        }

        $menu = static::buildMenuItems($allMenu, $customMenuMap, $module, $order);
        ksort($menu, SORT_NUMERIC);

        /* Set divider in main && module menu. */
        if(!isset($lang->{$tab}->menuOrder)) $lang->{$tab}->menuOrder = array();
        ksort($lang->{$tab}->menuOrder, SORT_NUMERIC);

        $group         = 0;
        $dividerOrders = array();
        foreach($lang->{$tab}->menuOrder as $name)
        {
            if(isset($lang->{$tab}->dividerMenu) && strpos($lang->{$tab}->dividerMenu, ",{$name},") !== false) $group++;
            $dividerOrders[$name] = $group;
        }

        $isFirst = true; // No divider before First item.
        $group   = 0;
        foreach($menu as $item)
        {
            if($module == 'main' && isset($dividerOrders[$item->name]) && $dividerOrders[$item->name] > $group)
            {
                $menu[$item->order]->divider = $isFirst ? false : true;
                $group = $dividerOrders[$item->name];
            }
            else
            {
                $isFirst = false;
                if(!isset($menu[$item->order]->divider))$menu[$item->order]->divider = false;
            }
        }

        return array_values($menu);
    }

    /**
     * 构造自定义导航数据。
     * Build custom menu data.
     *
     * @param  array|string $customMenu
     * @param  string       $module
     * @static
     * @access public
     * @return array
     */
    public static function buildCustomMenuMap(object|array $allMenu, array|string $customMenu = '', string $module = ''): array
    {
        global $lang;
        $customMenuMap = array();
        $order         = 1;
        if($customMenu)
        {
            if(is_string($customMenu))
            {
                $customMenuItems = explode(',', $customMenu);
                foreach($customMenuItems as $customMenuItem)
                {
                    $item = new stdclass();
                    $item->name   = $customMenuItem;
                    $item->order  = $order ++;
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
                        $item->order  = $order ++;
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
                    $order ++;
                }
            }
        }
        elseif($module)
        {
            $menuOrder = ($module == 'main' && isset($lang->menuOrder)) ? $lang->menuOrder : (isset($lang->menu->{$module}['menuOrder']) ? $lang->menu->{$module}['menuOrder'] : array());
            if($menuOrder)
            {
                ksort($menuOrder);
                foreach($menuOrder as $name)
                {
                    /* If menu is removed, delete the menuOrder. */
                    if(!isset($allMenu->$name)) continue;

                    $item = new stdclass();
                    $item->name   = $name;
                    $item->hidden = false;
                    $item->order  = $order ++;
                    $customMenuMap[$name] = $item;
                }
            }
        }

        return array($customMenuMap, $order);
    }

    /**
     * 构造菜单数据。
     * Build menu data.
     *
     * @param  object|array $allMenu
     * @param  array        $customMenuMap
     * @param  string       $menuModuleName
     * @param  int          $order
     * @static
     * @access public
     * @return array
     */
    public static function buildMenuItems(object|array $allMenu, array $customMenuMap, string $menuModuleName, int $order = 1): array
    {
        global $config, $app;

        $menu           = array();
        $isTutorialMode = commonModel::isTutorialMode();
        $module         = $menuModuleName;
        foreach($allMenu as $name => $item)
        {
            if(is_object($item)) $item = (array)$item;

            /* The variable of item has not link && is not link then ignore it. */
            $link = (is_array($item) && isset($item['link'])) ? $item['link'] : $item;
            if(!is_string($link)) continue;

            $subMenu = array();
            $label   = $link;
            $hasPriv = true;
            if(strpos($link, '|') !== false)
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
                $hasPriv = commonModel::hasPriv($module, $method, null, zget($link, 3, ''));

                /* Fix bug #20464 */
                if(isset($vars)) unset($vars);
                if(!$hasPriv && is_array($item) && isset($item['subMenu']))
                {
                    foreach($item['subMenu'] as $subMenu)
                    {
                        if(!isset($subMenu['link']) || strpos($subMenu['link'], '|') === false) continue;
                        if(strpos("|program|product|project|execution|qa|", "|{$app->tab}|") === false && strpos($subMenu['link'], '%s') !== false) continue;
                        list($subLabel, $module, $method) = explode('|', $subMenu['link']);
                        if(count(explode('|', $subMenu['link'])) > 3) list($subLabel, $module, $method, $vars) = explode('|', $subMenu['link']);
                        $hasPriv = commonModel::hasPriv($module, $method);
                        if($hasPriv) break;
                    }
                }
                if($module == 'execution' && $method == 'more') $hasPriv = true;
                if($module == 'project' && $method == 'other')  $hasPriv = true;
                if(!$hasPriv && isset($vars)) unset($vars);
            }

            if($isTutorialMode || $hasPriv)
            {
                $itemLink = '';
                if($module && $method)
                {
                    $itemLink = array('module' => $module, 'method' => $method);
                    if(isset($link[3])) $itemLink['vars'] = $link[3];
                    if(isset($vars))    $itemLink['vars'] = $vars;
                    if(is_array($item) && isset($item['target'])) $itemLink['target'] = $item['target'];
                }

                /* Process menu item's order and hidden attirbute. */
                $menuItem = static::buildMenuItem($item, $customMenuMap, $name, $label, $itemLink, $isTutorialMode, $subMenu);
                $menuItem->order = (isset($customMenuMap[$name]) && isset($customMenuMap[$name]->order) ? $customMenuMap[$name]->order : $order ++);
                if($app->viewType == 'mhtml' && isset($config->custom->moblieHidden[$menuModuleName]) && in_array($name, $config->custom->moblieHidden[$menuModuleName])) $menuItem->hidden = 1; // Hidden menu by config in mobile.
                while(isset($menu[$menuItem->order])) $menuItem->order ++;
                $menu[$menuItem->order] = $menuItem;
            }
        }
        return $menu;
    }

    /**
     * 构造菜单数据项。
     * Build menu item.
     *
     * @param  array|string $item
     * @param  int          $customMenuMap
     * @param  string       $name
     * @param  string       $label
     * @param  string|array $itemLink
     * @param  bool         $isTutorialMode
     * @param  array        $subMenu
     * @static
     * @access public
     * @return object
     */
    public static function buildMenuItem(array|string $item, $customMenuMap, string $name = '', string $label = '', string|array $itemLink = '', bool $isTutorialMode = false, array $subMenu = array()): object
    {
        if(is_array($item) && (isset($item['subMenu']) || isset($item['dropMenu'])))
        {
            foreach(array('subMenu', 'dropMenu') as $key)
            {
                if(!isset($item[$key])) continue;
                foreach($item[$key] as $subItem)
                {
                    if(isset($subItem->link['module']) && isset($subItem->link['method'])) $subItem->hidden = !common::hasPriv($subItem->link['module'], $subItem->link['method']);
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

        $menuItem = new stdclass();
        $menuItem->name  = $name;
        $menuItem->link  = $itemLink;
        $menuItem->text  = $label;
        if($isTutorialMode) $menuItem->tutorial = true;

        $attrList = array('class', 'subModule', 'dropMenu', 'alias', 'exclude', 'divider');
        $hidden   = strpos($name, 'QUERY') === 0 && !isset($customMenuMap[$name]) ? false : isset($customMenuMap[$name]) && isset($customMenuMap[$name]->hidden) && $customMenuMap[$name]->hidden;
        foreach($attrList as $attr)
        {
            if(!empty($item[$attr])) $menuItem->$attr = $item[$attr];
        }
        if($hidden)  $menuItem->hidden  = $hidden;
        if($subMenu) $menuItem->subMenu = $subMenu;

        return $menuItem;
    }

    /**
     * 获取模块菜单数据，如果模块是'main'则返回主菜单。
     * Get module menu data, if module is 'main' then return main menu.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public static function getModuleMenu($module = 'main'): array
    {
        global $app, $lang, $config;

        if(empty($module)) $module = 'main';

        $allMenu = new stdclass();
        if($module == 'main' and !empty($lang->menu)) $allMenu = $lang->menu;
        if($module != 'main' and isset($lang->menu->$module) and isset($lang->menu->{$module}['subMenu'])) $allMenu = $lang->menu->{$module}['subMenu'];
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);
        $flowModule = $config->global->flow . '_' . $module;
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        if($module == 'my' && empty($config->global->scoreStatus)) unset($allMenu->score);

        return static::setMenuByConfig($allMenu, $customMenu, $module);
    }

    /**
     * 获取主菜单数据。
     * Get main menu data.
     *
     * @access public
     * @return array
     */
    public static function getMainMenu(): array
    {
        return static::getModuleMenu('main');
    }

    /**
     * 获取模块的筛选标签。
     * Get feature menu.
     *
     * @param  string     $module
     * @param  string     $method
     * @access public
     * @return array|null
     */
    public static function getFeatureMenu(string $module, string $method): array|null
    {
        global $app, $lang, $config;
        $app->loadLang($module);
        customModel::mergeFeatureBar($module, $method);

        $configKey  = $config->global->flow . '_feature_' . $module . '_' . $method;
        $allMenu    = isset($lang->$module->featureBar[$method]) ? $lang->$module->featureBar[$method] : null;
        $customMenu = '';
        if(!commonModel::isTutorialMode() && isset($config->customMenu->$configKey)) $customMenu = $config->customMenu->$configKey;
        if(!empty($customMenu) && is_string($customMenu)) $customMenu = json_decode($customMenu);
        return $allMenu ? static::setMenuByConfig($allMenu, $customMenu) : null;
    }

    /**
     * 将查询条件合并到筛选标签中。
     * Merge shortcut query in featureBar.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public static function mergeFeatureBar(string $module, string $method): void
    {
        global $lang, $app;
        if(!isset($lang->$module->featureBar[$method])) return;
        $queryModule = $module == 'execution' ? 'task' : ($module == 'product' ? 'story' : $module);
        $shortcuts   = $app->dbQuery('select id, title from ' . TABLE_USERQUERY . " where (`account` = '{$app->user->account}' or `common` = '1') AND `module` = '{$queryModule}' AND `shortcut` = '1' order by id")->fetchAll();

        if($shortcuts)
        {
            $lang->$module->featureBar[$method]['QUERY'] = $lang->custom->common;
            foreach($shortcuts as $shortcut) $lang->custom->queryList[$shortcut->id] = $shortcut->title;
        }
    }

    /**
     * 获取必填字段。
     * Get required fields by config.
     *
     * @param  object $moduleConfig
     * @access public
     * @return array
     */
    public function getRequiredFields(object $moduleConfig): array
    {
        $requiredFields = array();
        foreach($moduleConfig as $method => $subConfig)
        {
            if(is_object($subConfig) && isset($subConfig->requiredFields)) $requiredFields[$method] = trim(str_replace(' ', '', $subConfig->requiredFields));
        }

        return $requiredFields;
    }

    /**
     * 获取表单必填字段。
     * Get form required fields.
     *
     * @param  string $moduleName
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFormFields(string $moduleName, string $method = ''): array
    {
        $fields       = array();
        $moduleLang   = $this->lang->{$moduleName};
        $customFields = $this->config->custom->fieldList;
        if(isset($customFields[$moduleName]))
        {
            $fieldList = isset($customFields[$moduleName][$method]) ? $customFields[$moduleName][$method] : $customFields[$moduleName];
            if(!is_string($fieldList)) return $fields;

            if($moduleName == 'user' && $method == 'edit') $this->app->loadConfig('user');
            foreach(explode(',', $fieldList) as $fieldName)
            {
                if($moduleName == 'user' && $method == 'edit' && strpos($this->config->user->contactField, $fieldName) === false) continue;
                if($fieldName == 'comment') $fields[$fieldName] = $this->lang->comment;
                if(isset($moduleLang->{$fieldName}) && is_string($moduleLang->{$fieldName})) $fields[$fieldName] = $moduleLang->$fieldName;

                if($moduleName == 'program')
                {
                    $fieldKey = substr($method, 0, 3) . ucfirst($fieldName);
                    if(isset($moduleLang->{$fieldKey}) && is_string($moduleLang->{$fieldKey})) $fields[$fieldName] = $moduleLang->$fieldKey;
                }
            }
        }

        return $fields;
    }

    /**
     * 获取需求概念。
     * Get UR and SR concept.
     *
     * @param  int          $key
     * @param  string       $lang
     * @access public
     * @return string|false
     */
    public function getURSRConcept(int $key, string $lang = ''): string|false
    {
        if(empty($lang)) $lang = $this->app->getClientLang();

        return $this->dao->select('`value`')->from(TABLE_LANG)
            ->where('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('URSRList')
            ->andWhere('`key`')->eq($key)
            ->fetch('value');
    }

    /**
     * 获取需求概念集合。
     * Get UR and SR pairs.
     *
     * @access public
     * @return array
     */
    public function getURSRPairs(): array
    {
        $lang     = $this->app->getClientLang();
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
        foreach($langData as $content)
        {
            $value = json_decode($content->value);
            $URSRPairs[$content->key] = $this->config->URAndSR ? $value->URName . '/' . $value->SRName : $value->SRName;
        }

        return $URSRPairs;
    }

    /**
     * 获取用需概念集合。
     * Get UR pairs.
     *
     * @access public
     * @return array
     */
    public function getURPairs(): array
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
     * 获取软需概念集合。
     * Get SR pairs.
     *
     * @access public
     * @return array
     */
    public function getSRPairs(): array
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
     * 获取需求概念列表。
     * Get UR and SR list.
     *
     * @access public
     * @return array
     */
    public function getURSRList(): array
    {
        $this->app->loadLang('custom');
        $lang = $this->app->getClientLang();

        $URSRDataList = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)
            ->where('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('URSRList')
            ->fetchAll();

        if(empty($URSRDataList))
        {
            $URSR         = $this->loadModel('setting')->getURSR();
            $URSRDataList = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)->where('`key`')->eq($URSR)->andWhere('module')->eq('custom')->andWhere('section')->eq('URSRList')->fetchAll();
        }

        $URSRList = array();
        foreach($URSRDataList as $URSRData)
        {
            $value = json_decode($URSRData->value);
            $URSRList[$URSRData->key] = new stdclass();
            $URSRList[$URSRData->key]->key    = $URSRData->key;
            $URSRList[$URSRData->key]->SRName = $value->SRName;
            $URSRList[$URSRData->key]->URName = $value->URName;
            $URSRList[$URSRData->key]->system = $URSRData->system;
        }

        return $URSRList;
    }

    /**
     * 保存表单必填字段设置。
     * Save required fields.
     *
     * @param  string $moduleName product|story|productplan|release|execution|task|bug|testcase|testsuite|testtask|testreport|caselib|doc|user|project|build
     * @param  array  $data
     * @access public
     * @return void
     */
    public function saveRequiredFields(string $moduleName, array $data): void
    {
        if(isset($this->config->system->$moduleName))   unset($this->config->system->$moduleName);
        if(isset($this->config->personal->$moduleName)) unset($this->config->personal->$moduleName);

        $this->loadModel($moduleName);
        $systemFields = $this->getRequiredFields($this->config->$moduleName);

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
                if(!isset($data['requiredFields'][$method]))
                {
                    $requiredFields[$method]['requiredFields'] = $systemField;
                    continue;
                }

                $fields       = implode(',', $data['requiredFields'][$method]);
                $systemFields = array_reverse(explode(',', $systemField));
                foreach($systemFields as $field)
                {
                    $field = trim($field);
                    if(strpos(",$fields,", ",$field,") === false) $fields = "{$field},{$fields}";
                }

                $requiredFields[$method]['requiredFields'] = trim($fields, ',');
            }
        }

        $this->loadModel('setting')->setItems("system.{$moduleName}@{$this->config->vision}", $requiredFields);
    }

    /**
     * 设置产品、项目和迭代概念。
     * Set product and project and sprint concept.
     *
     * @param  string $sprintConcept
     * @access public
     * @return bool
     */
    public function setConcept(string $sprintConcept): bool
    {
        $this->loadModel('setting');
        $this->setting->setItem('system.custom.sprintConcept', $sprintConcept);
        $this->setting->setItem('system.custom.productProject', '0_' . $sprintConcept);

        /* Change block title. */
        $oldConfig = isset($this->config->custom->sprintConcept) ? $this->config->custom->sprintConcept : '0';
        $newConfig = $sprintConcept;

        foreach($this->config->executionCommonList as $executionCommonList)
        {
            $this->dao->update(TABLE_BLOCK)->set("`title` = REPLACE(`title`, '{$executionCommonList[$oldConfig]}', '{$executionCommonList[$newConfig]}')")->where('dashboard')->eq('execution')->exec();
        }
        return !dao::isError();
    }

    /**
     * 设置需求概念。
     * Set UR and SR concept.
     *
     * @param  array  $data
     * @access public
     * @return bool
     */
    public function setURAndSR(array $data): bool
    {
        $lang   = $this->app->getClientLang();
        $maxKey = $this->dao->select('max(cast(`key` as SIGNED)) as maxKey')->from(TABLE_LANG)
            ->where('section')->eq('URSRList')
            ->andWhere('module')->eq('custom')
            ->andWhere('lang')->eq($lang)
            ->fetch('maxKey');

        $maxKey = $maxKey ? $maxKey : 1;

        /* If has custom UR and SR name. */
        foreach($data['SRName'] as $key => $SRName)
        {
            if(isset($data['URName']))  $URName = zget($data['URName'], $key, '');
            if(!isset($data['URName'])) $URName = $this->lang->URCommon;
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
     * 编辑需求概念。
     * Edit UR and SR concept.
     *
     * @param  int    $key
     * @param  string $lang zh-cn|zh-tw|en|fr|de
     * @param  array  $data
     * @access public
     * @return bool
     */
    public function updateURAndSR(int $key = 0, string $lang = '', array $data = array()): bool
    {
        if(empty($lang)) $lang = $this->app->getClientLang();
        if(empty($data['SRName']) || empty($data['URName'])) return false;

        $oldValue = $this->getURSRConcept($key, $lang);
        $oldValue = json_decode($oldValue);

        if(!$oldValue) return false;

        $URSRList = new stdclass();
        $URSRList->defaultSRName = zget($oldValue, 'defaultSRName', $oldValue->SRName);
        $URSRList->defaultURName = zget($oldValue, 'defaultURName', $oldValue->URName);
        $URSRList->SRName        = empty($data['SRName']) ? $URSRList->defaultSRName : $data['SRName'];
        $URSRList->URName        = empty($data['URName']) ? $URSRList->defaultURName : $data['URName'];

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
     * 计算启用和不启用的功能。
     * Compute the enabled and disabled features.
     *
     * @access public
     * @return array
     */
    public function computeFeatures(): array
    {
        /* Check that the project features are enabled. */
        $disabledFeatures = array('program', 'productLine');
        foreach($this->config->custom->dataFeatures as $feature)
        {
            $function = 'has' . ucfirst($feature) . 'Data';
            if(!$this->$function())
            {
                if(strpos($feature, 'scrum') !== false)
                {
                    if(!isset($disabledFeatures['scrum'])) $disabledFeatures['scrum'] = array();
                    $disabledFeatures['scrum'][] = $feature;
                }
                elseif(in_array($feature, array('waterfall', 'waterfallplus')))
                {
                    $disabledFeatures[] = 'project' . ucfirst($feature);
                }
                else
                {
                    $disabledFeatures[] = $feature;
                }
            }
        }
        if(!isset($disabledFeatures['scrum'])) $disabledFeatures['scrum'] = array();
        $disabledFeatures['scrum'][] = 'scrumMeasrecord';

        /* Check that the scrum project features are enabled. */
        $enabledScrumFeatures  = array();
        $disabledScrumFeatures = array();
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            foreach($this->config->custom->scrumFeatures as $scrumFeature)
            {
                if(in_array('scrum' . ucfirst($scrumFeature), $disabledFeatures['scrum']))
                {
                    $disabledScrumFeatures[] = $this->lang->custom->scrum->features[$scrumFeature];
                }
                else
                {
                    $enabledScrumFeatures[] = $this->lang->custom->scrum->features[$scrumFeature];
                }
            }
        }

        return array($disabledFeatures, $enabledScrumFeatures, $disabledScrumFeatures);
    }

    /**
     * 处理项目权限为继承项目集的项目权限。
     * process project priv within a program set.
     *
     * @access public
     * @return bool
     */
    public function processProjectAcl(): bool
    {
        list($projectGroup, $programPM, $stakeholders) = $this->customTao->getDataForUpdateProjectAcl();

        $projectIDList = array();
        foreach($projectGroup as $projects) $projectIDList = array_merge($projectIDList, array_keys($projects));
        $executionGroup = $this->dao->select('project,id')->from(TABLE_EXECUTION)->where('project')->in($projectIDList)->fetchGroup('project', 'id');

        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('personnel');
        foreach($projectGroup as $projects)
        {
            foreach($projects as $project)
            {
                $PM          = zget($programPM, $project->parent, '');
                $stakeholder = zget($stakeholders, $project->parent, '');
                if($stakeholder) $stakeholder = implode(',', array_keys($stakeholder));

                $whitelist = rtrim($project->whitelist . ',' . $PM . ',' . $stakeholder);
                $whitelist = explode(',', $whitelist);
                $whitelist = array_filter(array_unique($whitelist));
                $whitelist = implode(',', $whitelist);

                $data = new stdclass();
                $data->acl       = 'private';
                $data->whitelist = $whitelist;
                $this->dao->update(TABLE_PROJECT)->data($data)->where('id')->eq($project->id)->exec();

                $whitelist = explode(',', $whitelist);
                $this->personnel->updateWhitelist($whitelist, 'project', $project->id);

                $this->user->updateUserView(array($project->id), 'project');
                if(zget($executionGroup, $project->id, ''))
                {
                    $executions = zget($executionGroup, $project->id);
                    $executionPairs = array();
                    foreach($executions as $executionID => $execution) $executionPairs[$executionID] = $executionID;
                    $this->user->updateUserView($executionPairs, 'sprint');
                }
                $changes = common::createChanges($project, $data);
                $actionID = $this->action->create('project', $project->id, 'SwitchToLight');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * 根据管理模式禁用相关功能。
     * Disable related features based on the management mode.
     *
     * @param  string $mode
     * @access public
     * @return void
     */
    public function disableFeaturesByMode(string $mode)
    {
        $disabledFeatures = '';
        if($mode == 'light')
        {
            /* Check whether the product or project data in the system is empty. */
            foreach($this->config->custom->dataFeatures as $feature)
            {
                $function = 'has' . ucfirst($feature) . 'Data';
                if(!$this->$function())
                {
                    /* If the data is empty, this feature is disabled. */
                    $disabledFeatures .= "$feature,";
                    if(strpos($feature, 'scrum') === 0) $disabledFeatures .= 'agileplus' . substr($feature, 5) . ',';
                }
            }
            $disabledFeatures .= 'scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap';
        }

        /* Save the features that are disable to the config. */
        $disabledFeatures = rtrim($disabledFeatures, ',');
        $this->loadModel('setting')->setItem('system.common.disabledFeatures', $disabledFeatures);

        $URAndSR = strpos(",$disabledFeatures,", ',productUR,') === false ? '1' : '0';
        $this->setting->setItem('system.custom.URAndSR', $URAndSR);

        $this->processMeasrecordCron();
    }

    /**
     * 检查系统中是否有用户需求数据。
     * Check whether there is requirement data in the system.
     *
     * @access public
     * @return int
     */
    public function hasProductURData(): int
    {
        return (int)$this->dao->select('*')->from(TABLE_STORY)->where('type')->eq('requirement')->andWhere('deleted')->eq('0')->count();
    }

    /**
     * 检查系统中是否有瀑布项目数据。
     * Check whether there is waterfall project data in the system.
     *
     * @access public
     * @return int
     */
    public function hasWaterfallData(): int
    {
        return (int)$this->dao->select('*')->from(TABLE_PROJECT)->where('model')->eq('waterfall')->andWhere('deleted')->eq('0')->count();
    }

    /**
     * 检查系统中是否有融合项目数据。
     * Check whether there is waterfallplus project data in the system.
     *
     * @access public
     * @return int
     */
    public function hasWaterfallplusData(): int
    {
        return (int)$this->dao->select('*')->from(TABLE_PROJECT)->where('model')->eq('waterfallplus')->andWhere('deleted')->eq('0')->count();
    }

    /**
     * 检查系统中是否有资产库数据。
     * Check whether there is assetlib data in the system.
     *
     * @access public
     * @return int
     */
    public function hasAssetlibData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd'))) return (int)$this->dao->select('*')->from(TABLE_ASSETLIB)->where('deleted')->eq(0)->count();
        return 0;
    }

    /**
     * 检查系统中是否有敏捷项目的问题数据。
     * Check whether there is scrum issue data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumIssueData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('t1.*')->from(TABLE_ISSUE)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.model')->eq('scrum')
                ->count();
        }

        return 0;
    }

    /**
     * 检查系统中是否有敏捷项目的风险数据。
     * Check whether there is scrum risk data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumRiskData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('t1.*')->from(TABLE_RISK)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('t2.model')->eq('scrum')
                ->count();
        }
        return 0;
    }

    /**
     * 检查系统中是否有机会数据。
     * Check whether there is opportunity data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumOpportunityData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('id')->from(TABLE_OPPORTUNITY)->where('execution')->ne('0')->andWhere('deleted')->eq('0')->count();
        }
        return 0;
    }

    /**
     * 检查系统中是否有敏捷项目的会议数据。
     * Check whether there is scrum meeting data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumMeetingData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('id')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_MEETING)->alias('t2')->on('t1.id = t2.project')
                ->where('t1.model')->eq('scrum')
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->count();
        }
        return 0;
    }

    /**
     * 检查系统中是否有敏捷项目的审计数据。
     * Check whether there is scrum auditplan data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumAuditplanData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('id')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_AUDITPLAN)->alias('t2')->on('t1.id = t2.project')
                ->where('t1.model')->eq('scrum')
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->count();
        }
        return 0;
    }

    /**
     * 检查系统中是否有项目活动数据。
     * Check whether there is project activity data in the system.
     *
     * @access public
     * @return int
     */
    public function hasScrumProcessData(): int
    {
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            return (int)$this->dao->select('id')->from(TABLE_PROGRAMACTIVITY)->where('execution')->ne('0')->andWhere('deleted')->eq('0')->count();
        }
        return 0;
    }

    /**
     * 处理定时任务。
     * Process measrecord cron.
     *
     * @access public
     * @return void
     */
    public function processMeasrecordCron()
    {
        $this->loadModel('setting');
        $closedFeatures   = $this->setting->getItem('owner=system&module=common&section=&key=closedFeatures');
        $disabledFeatures = $this->setting->getItem('owner=system&module=common&section=&key=disabledFeatures');
        $disabledFeatures = $disabledFeatures . ',' . $closedFeatures;

        $hasWaterfall               = strpos(",{$disabledFeatures},",  ',waterfall,')           === false;
        $hasWaterfallPlus           = strpos(",{$disabledFeatures},",  ',waterfallplus,')       === false;
        $hasScrumMeasrecord         = strpos(",{$disabledFeatures},",  ',scrumMeasrecord,')     === false;
        $hasAgilePlusMeasrecord     = strpos(",{$disabledFeatures},",  ',agileMeasrecord,')     === false;
        $hasWaterfallMeasrecord     = (strpos(",{$disabledFeatures},", ',waterfallMeasrecord,') === false && $hasWaterfall);
        $hasWaterfallPlusMeasrecord = (strpos(",{$disabledFeatures},", ',waterfallplusMeasrecord,') === false && $hasWaterfallPlus);

        /* Determine whether the cron is enabled based on whether the feature is disabled. */
        $cronStatus = 'normal';
        if(!$hasScrumMeasrecord && !$hasAgilePlusMeasrecord && !$hasWaterfallMeasrecord && !$hasWaterfallPlusMeasrecord) $cronStatus = 'stop';

        /* Update the status of the cron. */
        $this->loadModel('cron');
        $cron = $this->dao->select('id,status')->from(TABLE_CRON)->where('command')->like('%methodName=initCrontabQueue')->fetch();
        if($cron && $cron->status != $cronStatus) $this->cron->changeStatus($cron->id, $cronStatus);
        $cron = $this->dao->select('id,status')->from(TABLE_CRON)->where('command')->like('%methodName=execCrontabQueue')->fetch();
        if($cron && $cron->status != $cronStatus) $this->cron->changeStatus($cron->id, $cronStatus);
    }
}
