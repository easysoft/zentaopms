<?php
class devModel extends model
{
    /**
     * Default lang object.
     *
     * @var    object
     * @access public
     */
    public $defaultLang;

    /**
     * Get All tables.
     *
     * @access public
     * @return array
     */
    public function getTables()
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql = "SHOW TABLES";
        $tables = array();
        $datatables = $this->dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach($datatables as $table)
        {
            $table = current($table);
            if(empty($this->config->db->prefix) or strpos($table, $this->config->db->prefix) !== false)
            {
                if(strpos($table, $this->config->db->prefix . 'flow_') === 0) continue;

                $subTable = substr($table, strpos($table, '_') + 1);
                $group    = zget($this->config->dev->group, $subTable, 'other');
                $tables[$group][$subTable] = $table;
            }
        }
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        return $tables;
    }

    /**
     * Get fields of table.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function getFields($table)
    {
        $module      = substr($table, strpos($table, '_') + 1);
        $aliasModule = $subLang = '';
        $this->app->loadLang($module);
        try
        {
            if(isset($this->config->dev->tableMap[$module])) $aliasModule = $this->config->dev->tableMap[$module];
            if(strpos($aliasModule, '-') !== false) list($aliasModule, $subLang) = explode('-', $aliasModule);
            if(!empty($aliasModule) and strpos($module, 'im_') === false) $this->app->loadLang($aliasModule);
        }
        catch(PDOException $e)
        {
            $this->lang->$module = new stdclass();
        }

        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $table";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            $this->sqlError($e);
        }

        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny'), '', $type);
            $field    = array();
            $field['name'] = (isset($this->lang->$module->{$rawField->field}) and is_string($this->lang->$module->{$rawField->field})) ? sprintf($this->lang->$module->{$rawField->field}, $this->lang->dev->tableList[$module]) : '';
            if((empty($field['name']) or !is_string($field['name'])) and $aliasModule) $field['name'] = isset($this->lang->$aliasModule->{$rawField->field}) ? $this->lang->$aliasModule->{$rawField->field} : '';
            if($subLang) $field['name'] = isset($this->lang->$aliasModule->$subLang->{$rawField->field}) ? $this->lang->$aliasModule->$subLang->{$rawField->field} : $field['name'];

            if(!is_string($field['name'])) $field['name'] = '';
            $field['null']            = $rawField->null;
            $fields[$rawField->field] = $this->setField($field, $rawField, $type, $firstPOS);
        }
        return $fields;
    }

    /**
     * Set table fields field.
     *
     * @param  array  $field
     * @param  array  $rawField
     * @param  string $type
     * @param  int    $firstPOS
     * @access public
     * @return array
     */
    public function setField($field, $rawField, $type, $firstPOS)
    {
        if($type == 'enum' or $type == 'set')
        {
            $rangeBegin = $firstPOS + 2;                       // Remove the first quote.
            $rangeEnd   = strrpos($rawField->type, ')') - 1;   // Remove the last quote.
            $range      = substr($rawField->type, $rangeBegin, $rangeEnd - $rangeBegin);
            $field['type']             = $rawField->type;
            $field['options']['enum']  = str_replace("','", ',', $range);
        }
        elseif($type == 'varchar' or $type == 'char' or $type == 'int')
        {
            $begin  = $firstPOS + 1;
            $end    = strpos($rawField->type, ')', $begin);
            $length = substr($rawField->type, $begin, $end - $begin);
            $field['type']           = $type;
            $field['options']['max'] = $length;
            $field['options']['min'] = 0;
        }
        elseif($type == 'float' or $type == 'double')
        {
            $field['type'] = 'float';
        }
        else
        {
            $field['type'] = $type;
        }

        return $field;
    }

    /**
     * Get APIs of a module.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getAPIs($module)
    {
        $fileName = $this->app->getModuleRoot() . $module . DS . 'control.php';
        if(!file_exists($fileName))
        {
            $extPaths = $this->getModuleExtPath();
            foreach($extPaths as $extPath)
            {
                $fileName = $extPath . $module . DS . 'control.php';
                if(file_exists($fileName)) break;
            }
        }
        if($module != 'common' and $module != 'dev') include $fileName;

        $classReflect = new ReflectionClass($module);
        $methods = $classReflect->getMethods();
        $apis = array();
        foreach($methods as $method)
        {
            if($method->class == 'baseControl' or $method->class == 'control' or $method->name == '__construct') continue;
            $api = array('name' => $method->name, 'post' => false, 'param' => array(), 'desc' => '');
            $methodReflect = new ReflectionMethod($module, $method->name);
            foreach($methodReflect->getParameters() as $key => $param)
            {
                $paramName = $param->getName();
                $api['param'][$paramName] = array('var' => $paramName, 'type' => '', 'desc' => '');
            }

            $startLine = $methodReflect->getStartLine();
            $endLine   = $methodReflect->getEndLine();
            $comment   = $methodReflect->getDocComment();

            if($startLine > 0)
            {
                $file = file($fileName);
                for($i = $startLine - 1; $i <= $endLine; $i++)
                {
                    if(strpos($file[$i], '$this->post') or strpos($file[$i], 'fixer::input') or strpos($file[$i], '$_POST')) $api['post'] = true;
                }
            }

            if($comment)
            {
                // Strip the opening and closing tags of the docblock.
                $comment = substr($comment, 3, -2);

                // Split into arrays of lines.
                $comment = preg_split('/\r?\n\r?/', $comment);

                // Trim asterisks and whitespace from the beginning and whitespace from the end of lines.
                $comment = array_map(array('devModel', "trimSpace"), $comment);

                // Group the lines together by @tags
                $blocks = array();
                $b = -1;
                foreach($comment as $line)
                {
                    if(isset($line[1]) && $line[0] == '@' && ctype_alpha($line[1])) $b++;
                    if($b == -1) $b = 0;

                    if(!isset($blocks[$b])) $blocks[$b] = array();
                    $blocks[$b][] = $line;
                }

                // Parse the blocks
                foreach($blocks as $block => $body)
                {
                    $body = trim(implode("\n", $body));
                    if($block == 0 && !(isset($body[1]) && $body[0] == '@' && ctype_alpha($body[1])))
                    {
                        // This is the description block
                        $api['desc'] = $body;
                        continue;
                    }

                    // This block is tagged
                    if(preg_match('/^@[a-z0-9_]+/', $body, $matches))
                    {
                        $tag  = substr($matches[0], 1);
                        $body = substr($body, strlen($tag) + 2);
                        if($tag != 'param')
                        {
                            $api[$tag][] = $body;
                            continue;
                        }

                        $parts        = preg_split('/\s+/', trim($body), 3);
                        $parts        = array_pad($parts, 3, null);
                        $property     = array('type', 'var', 'desc');
                        $param        = array_combine($property, $parts);
                        $paramName    = substr($param['var'], 1);
                        $param['var'] = $paramName;
                        if(isset($api['param'][$paramName])) $api['param'][$paramName] = $param;
                    }
                }
            }
            $apis[] = $api;
        }
        return $apis;
    }

    /**
     * Get all modules.
     *
     * @access public
     * @return array
     */
    public function getModules()
    {
        $moduleList = glob($this->app->getModuleRoot() . '*');
        $modules = array();
        foreach($moduleList as $module)
        {
            if(!file_exists($module . DS . 'control.php')) continue;

            $module = basename($module);
            if($module == 'editor' or $module == 'help' or $module == 'setting' or $module == 'common') continue;
            $group  = zget($this->config->dev->group, $module, 'other');
            $modules[$group][$module] = $module;
        }

        $extPaths = $this->getModuleExtPath();
        foreach($extPaths as $extPath)
        {
            if(empty($extPath)) continue;
            foreach(glob($extPath . '*') as $path)
            {
                if(!file_exists($path . DS . 'control.php')) continue;

                $module = basename($path);
                if($module == 'editor' or $module == 'help' or $module == 'setting' or $module == 'common') continue;
                $group  = zget($this->config->dev->group, $module, 'other');
                $modules[$group][$module] = $module;
            }
        }

        return $modules;
    }

    /**
     * Get module ext path.
     *
     * @access public
     * @return array
     */
    public function getModuleExtPath()
    {
        $extPaths = array();
        if($this->config->edition != 'open') $extPaths['common'] = $this->app->getExtensionRoot() . $this->config->edition . DS;
        $extPaths['xuan'] = $this->app->getExtensionRoot() . 'xuan' . DS;

        return $extPaths;
    }

    /**
     * Get nav lang.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @param  string $language
     * @param  object $defaultLang
     * @access public
     * @return object
     */
    public function getNavLang($type, $module, $method, $language = 'zh-cn', $defaultLang = null)
    {
        if(empty($defaultLang)) $defaultLang = $this->loadDefaultLang($language);

        $menus = new stdclass();
        if($type == 'second')
        {
            if(isset($defaultLang->$module->homeMenu))
            {
                foreach($defaultLang->$module->homeMenu as $menuKey => $menu)
                {
                    $menuKey = 'homeMenu_' . $menuKey;
                    $menus->{$menuKey} = $menu;
                }
            }

            if(isset($defaultLang->$module->menu))
            {
                $menuList = $defaultLang->$module->menu;
                if(isset($defaultLang->$module->menuOrder)) $menuList->menuOrder = $defaultLang->$module->menuOrder;
                $menuList = $this->sortMenus($menuList);
                foreach($menuList as $menuKey => $menu)
                {
                    if(is_array($menu) and !isset($menu['link'])) continue;

                    $newKey = 'menu_' . $menuKey;
                    $menus->{$newKey} = $menu;

                    if(!isset($menu['dropMenu'])) continue;
                    foreach($menu['dropMenu'] as $key => $menu)
                    {
                        $dropMenuKey = $menuKey . 'DropMenu_' . $key;
                        $menus->{$dropMenuKey} = $menu;
                    }
                }
            }
        }
        else
        {
           $menus = ($type == 'third' and isset($defaultLang->$module->menu->{$method}['subMenu'])) ? $defaultLang->$module->menu->{$method}['subMenu'] : $defaultLang->mainNav;
           if(isset($defaultLang->$module->menu->{$method}['menuOrder'])) $menus->menuOrder = $defaultLang->$module->menu->{$method}['menuOrder'];
           $menus = $this->sortMenus($menus);
        }

        return $menus;
    }

    /**
     * Sort menus.
     *
     * @param  array|object $menus
     * @access public
     * @return array
     */
    public function sortMenus($menus)
    {
        if(!is_array($menus)) $menus = (array)$menus;
        if(!isset($menus['menuOrder'])) return $menus;
        $sortedMenus = array();
        $menuOrders  = $menus['menuOrder'];
        ksort($menuOrders);
        foreach($menuOrders as $menuKey)
        {
            if(isset($menus[$menuKey])) $sortedMenus[$menuKey] = $menus[$menuKey];
        }
        return $sortedMenus;
    }

    /**
     * Get original lang.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @param  string $language
     * @access public
     * @return array
     */
    public function getOriginalLang($type, $module = '', $method = '', $language = 'zh-cn')
    {
        if(empty($language)) $language = $this->app->getClientLang();
        $originalLangs = array();
        $defaultLang   = $this->loadDefaultLang($language);
        if($type == 'tag')
        {
            if(in_array($module, $this->config->dev->projectMenus)) $module = 'project';

            $this->defaultLang = $defaultLang;
            $defaultLang       = $this->loadDefaultLang($language, $module);
        }

        $lang    = new stdClass();
        $langKey = '';
        if($type == 'common')
        {
            $projectKey = (int)$this->loadModel('setting')->getItem('owner=system&key=sprintConcept');
            $originalLangs['productCommon']   = $this->config->productCommonList[$language][PRODUCT_KEY];
            $originalLangs['projectCommon']   = $this->config->projectCommonList[$language][PROJECT_KEY];
            $originalLangs['executionCommon'] = $this->config->executionCommonList[$language][$projectKey];
            $originalLangs['URCommon']        = $this->lang->dev->UR;
            $originalLangs['SRCommon']        = $this->lang->dev->SR;
            if(!$this->config->URAndSR) unset($originalLangs['URCommon']);
        }
        elseif($type == 'tag')
        {
            if(!isset($defaultLang->$module->featureBar)) return $originalLangs;

            $langKey     = 'featureBar-' . $method . '_';
            $featureBars = zget($defaultLang->$module->featureBar, $method, array());
            if(strpos($method, '_') !== false)
            {
                $langKey = 'featureBar-' . str_replace('_', '-', $method) . '_';
                list($subMethod, $thirdMethod) = explode('_', $method);
                $featureBars = $defaultLang->$module->featureBar[$subMethod][$thirdMethod];
            }

            foreach($featureBars as $feature => $featureName)
            {
                $selectKey = $feature . 'Selects';
                if(isset($defaultLang->$module->$selectKey))
                {
                    foreach($defaultLang->$module->$selectKey as $feature => $featureName) $originalLangs[$langKey . $feature] = $featureName;
                    continue;
                }
                $originalLangs[$langKey . $feature] = $featureName;
            }
        }
        else
        {
            $lang    = $this->getNavLang($type, $module, $method, $language, $defaultLang);
            $langKey = $type == 'first' ? 'mainNav_' : ($type == 'third' ? "{$method}_" : '');

            $menus = $this->getLinkTitle($lang);
            foreach($menus as $linkKey => $menu)
            {
                foreach($this->config->dev->skipMenus as $menuType => $skipMenus)
                {
                    if($type == $menuType and in_array($linkKey, $skipMenus)) continue 2;
                }

                if($menu == '@branch@') $menu = $this->lang->dev->branch;
                $originalLangs[$langKey . $linkKey] = $menu;
            }
        }

        return $originalLangs;
    }

    /**
     * Get customed lang.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @param  string $language
     * @access public
     * @return array
     */
    public function getCustomedLang($type, $module = '', $method = '', $language = 'zh-cn')
    {
        $customedLangs = array();

        $langKey   = '';
        $customeds = array();
        switch($type)
        {
            case 'common':
                $customeds = $this->loadModel('custom')->getItems("lang={$language}&module=common&section=&vision={$this->config->vision}");
                foreach($customeds as $customed) $customedLangs[$customed->key] = $customed->value;

                $customedLangs['URCommon'] = $this->lang->dev->UR == $this->lang->URCommon ? '' : $this->lang->URCommon;
                $customedLangs['SRCommon'] = $this->lang->dev->SR == $this->lang->SRCommon ? '' : $this->lang->SRCommon;
                if($this->config->custom->URSR)
                {
                    $URSRList = $this->custom->getItems("lang={$language}&module=custom&section=URSRList&key={$this->config->custom->URSR}&vision={$this->config->vision}");
                    $URSRList = array_shift($URSRList);
                    if($URSRList)
                    {
                        $URSRList = json_decode($URSRList->value);
                        $customedLangs['URCommon'] = $this->lang->dev->UR == $URSRList->URName ? '' : $URSRList->URName;
                        $customedLangs['SRCommon'] = $this->lang->dev->SR == $URSRList->SRName ? '' : $URSRList->SRName;
                    }
                }
                if(!$this->config->URAndSR) unset($customedLangs['SRCommon']);
                break;
            case 'first':
                $customeds = $this->loadModel('custom')->getItems("lang={$language}&module=common&section=mainNav&vision={$this->config->vision}");
                $langKey   = 'mainNav_';
                break;
            case 'second':
                $customeds = $this->loadModel('custom')->getItems("lang={$language}&module={$module}Menu&vision={$this->config->vision}");
                break;
            case 'third':
                $customeds = $this->loadModel('custom')->getItems("lang={$language}&module={$module}SubMenu&section=$method&vision={$this->config->vision}");
                $langKey   = "{$method}_";
                break;
            case 'tag':
                $method = str_replace('_', '-', $method);
                $customeds = $this->loadModel('custom')->getItems("lang={$language}&module={$module}&section=featureBar-$method&vision={$this->config->vision}");
                $langKey   = "featureBar-{$method}_";
                break;
        }

        foreach($customeds as $customed)
        {
            $customedKey = $customed->key;
            if($type == 'second') $customedKey = $customed->section . '_' . $customed->key;
            $customedLangs[$langKey . $customedKey] = $customed->value;
        }

        return $customedLangs;
    }

    /**
     * Trim asterisks and whitespace from the beginning and whitespace from the end of lines.
     *
     * @param  string    $line
     * @access public
     * @return string
     */
    public function trimSpace($line)
    {
        return ltrim(rtrim($line), "* \t\n\r\0\x0B");
    }

    /**
     * Load default lang.
     *
     * @param  string $language
     * @param  string $module
     * @access public
     * @return object
     */
    public function loadDefaultLang($language = 'zh-cn', $module = 'common')
    {
        if(empty($language)) $language = 'zh-cn';
        if(empty($module))   $module = 'common';
        if($module != 'common' and !isset($this->defaultLang)) return null;

        $clientLang = $this->app->clientLang;
        if($language and $language != $clientLang) $this->app->clientLang = $language;

        $langFilesToLoad = $this->app->getMainAndExtFiles($module);
        if($language != $clientLang) $this->app->clientLang = $clientLang;

        if(empty($langFilesToLoad)) return false;

        $lang = $module == 'common' ? new language() : $this->defaultLang;
        $lang->URCommon        = '$URCOMMON';
        $lang->SRCommon        = '$SRCOMMON';
        $lang->productCommon   = '$PRODUCTCOMMON';
        $lang->projectCommon   = '$PROJECTCOMMON';
        $lang->executionCommon = '$EXECUTIONCOMMON';
        $lang->hourCommon      = $this->lang->hourCommon;
        if(!isset($lang->common)) $lang->common = new stdclass();

        foreach($langFilesToLoad as $langFile) include $langFile;

        return $lang;
    }

    /**
     * Get second menus.
     *
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getSecondMenus($menu, $module = '', $method = '')
    {
        $menus = array();
        if($menu == 'project')
        {
            $menusPinYin = common::convert2Pinyin($this->lang->dev->projectMenu);
            foreach($this->config->dev->projectMenus as $subMenuKey)
            {
                $subMenu = new stdClass();
                $subMenu->title  = $this->lang->dev->projectMenu[$subMenuKey];
                $subMenu->key    = zget($menusPinYin, $this->lang->dev->projectMenu[$subMenuKey], '');
                $subMenu->module = $subMenuKey;
                $subMenu->method = '';
                $subMenu->active = ($module == $subMenuKey and $method == '') ? 1 : 0;

                $menus[] = $subMenu;
            }
        }

        return $menus;
    }

    /**
     * Get third menus.
     *
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getThirdMenus($menu, $module = '', $method = '')
    {
        $menus = array();
        if(!isset($this->lang->$menu->menu)) return $menus;

        $menuLang    = $this->getLinkTitle($this->lang->$menu->menu);
        $menusPinYin = common::convert2Pinyin($menuLang);
        foreach($menuLang as $menuKey => $menuName)
        {
            if(!isset($this->lang->$menu->menu->{$menuKey}['subMenu']) or !get_object_vars($this->lang->$menu->menu->{$menuKey}['subMenu'])) continue;

            $subMenu = new stdClass();
            $subMenu->title  = $menuName;
            $subMenu->key    = zget($menusPinYin, $menuName, '');
            $subMenu->module = $menu;
            $subMenu->method = $menuKey;
            $subMenu->active = ($method == $menuKey and $module == $menu) ? 1 : 0;

            $menus[] = $subMenu;
        }

        return $menus;
    }

    /**
     * Get tags.
     *
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getTagMenus($module, $moduleName = '', $methodName = '')
    {
        $menus = array();
        foreach(array('homeMenu', 'menu') as $menu)
        {
            if(!isset($this->lang->$module->$menu)) continue;
            $menuList = $this->lang->$module->$menu;
            if($menu == 'menu' and isset($this->lang->$module->menuOrder))
            {
                $menuList->menuOrder = $this->lang->$module->menuOrder;
                $menuList = $this->sortMenus($menuList);
            }

            foreach($menuList as $menuKey => $menuValue)
            {
                if(is_array($menuValue) and !isset($menuValue['link'])) continue;
                $link = is_array($menuValue) ? $menuValue['link'] : $menuValue;
                if(strpos($link, '|') === false) continue;
                list($label, $thisModule, $thisMethod) = explode('|', $link);

                if(isset($this->config->dev->linkMethods[$module]["{$thisModule}-{$thisMethod}"]))
                {
                    list($thisModule, $thisMethod) = $this->config->dev->linkMethods[$module]["{$thisModule}-{$thisMethod}"];
                }

                $subMenu = new stdclass();
                $subMenu->title    = $label;
                $subMenu->key      = '';
                $subMenu->module   = $thisModule;
                $subMenu->method   = $thisMethod;
                $subMenu->active   = ($methodName == $thisMethod and $moduleName == $thisModule) ? 1 : 0;
                $subMenu->children = array();

                $this->app->loadLang($thisModule);
                $hasFeatureBar = false;
                if(isset($this->lang->$thisModule->featureBar[$thisMethod])) $hasFeatureBar = true;

                if(is_array($menuValue))
                {
                    foreach(array('subMenu', 'dropMenu') as $menu)
                    {
                        if(!isset($menuValue[$menu])) continue;
                        if($menu == 'subMenu' and isset($menuValue['menuOrder']))
                        {
                            $menuValue[$menu]->menuOrder = $menuValue['menuOrder'];
                            $menuValue[$menu] = $this->sortMenus($menuValue[$menu]);
                        }
                        foreach($menuValue[$menu] as $subMenuKey => $subMenuValue)
                        {
                            if(is_array($subMenuValue) and !isset($subMenuValue['link'])) continue;
                            $link = is_array($subMenuValue) ? $subMenuValue['link'] : $subMenuValue;
                            if(strpos($link, '|') === false) continue;
                            list($label, $thisModule, $thisMethod) = explode('|', $link);
                            if($label == '@branch@') $label = $this->lang->dev->branch;

                            $this->app->loadLang($thisModule);
                            if(isset($this->lang->$thisModule->featureBar[$menuKey][$subMenuKey]))
                            {
                                $thirdMenu = new stdClass();
                                $thirdMenu->title  = $label;
                                $thirdMenu->key    = '';
                                $thirdMenu->module = $thisModule;
                                $thirdMenu->method = "{$thisMethod}_{$subMenuKey}";
                                $thirdMenu->active = ($methodName == $thirdMenu->method and $moduleName == $thisModule) ? 1 : 0;

                                $subMenu->active     = 0;
                                $subMenu->children[] = $thirdMenu;
                                $hasFeatureBar = true;
                            }
                            elseif(isset($this->lang->$thisModule->featureBar[$thisMethod]))
                            {
                                if(is_array($this->lang->$thisModule->featureBar[$thisMethod]))
                                {
                                    $arrayKey = key($this->lang->$thisModule->featureBar[$thisMethod]);
                                    if(is_array($this->lang->$thisModule->featureBar[$thisMethod][$arrayKey])) continue;
                                }

                                $subMenu = new stdClass();
                                $subMenu->title    = $label;
                                $subMenu->key      = '';
                                $subMenu->module   = $thisModule;
                                $subMenu->method   = $thisMethod;
                                $subMenu->active   = ($methodName == $thisMethod and $moduleName == $thisModule) ? 1 : 0;
                                $subMenu->children = array();

                                $menus[$subMenuKey] = $subMenu;
                                $hasFeatureBar = false;
                            }
                        }
                    }
                }

                if($hasFeatureBar) $menus[$menuKey] = $subMenu;
            }
        }

        return $menus;
    }

    /**
     * Get menu tree.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getMenuTree($type = 'second', $module = '', $method = '')
    {
        $menuTree = array();
        if(!in_array($type, $this->config->dev->navTypes)) return $menuTree;

        $mainNav = $type == 'second' ? $this->lang->mainNav : array();
        if($type != 'second')
        {
            foreach($this->lang->mainNav as $menuKey => $menu)
            {
                if($menuKey == 'project')
                {
                    foreach($this->config->dev->projectMenus as $subMenuKey) $mainNav[$subMenuKey] = $this->lang->dev->projectMenu[$subMenuKey];
                }

                $mainNav[$menuKey] = $menu;
            }
        }

        $mainNav       = $this->getLinkTitle($mainNav);
        $maimNavPinYin = common::convert2Pinyin($mainNav);
        foreach($mainNav as $menuKey => $menu)
        {
            $menuItem = new stdclass();
            $menuItem->title    = $menu;
            $menuItem->module   = $menuKey;
            $menuItem->method   = '';
            $menuItem->active   = ($module == $menuKey and $method == '') ? 1 : 0;
            $menuItem->key      = zget($maimNavPinYin, $menu, '');
            $menuItem->children = array();

            $childFunc = 'get' . ucfirst($type) . 'Menus';
            if($type == 'tag' and in_array($menuKey, $this->config->dev->projectMenus))
            {
                if($menuKey != 'project') continue;
                foreach($this->config->dev->projectMenus as $projectModule) $menuItem->children += $this->getTagMenus($projectModule, $module, $method);
            }
            else
            {
                $menuItem->children = $this->$childFunc($menuKey, $module, $method);
            }
            $menuItem->children = array_values($menuItem->children);

            if($type != 'second' and empty($menuItem->children)) continue;
            if($type == 'second' and in_array($menuKey, $this->config->dev->hideMainMenu)) continue;

            $menuTree[] = $menuItem;
        }

        /* Unique menu tree by module and method. */
        if($type == 'tag')
        {
            $methods = array();
            foreach($menuTree as $index => $menuItem)
            {
                foreach($menuItem->children as $subIndex => $subMenuItem)
                {
                    $key = "{$subMenuItem->module}|{$subMenuItem->method}";
                    if(isset($methods[$key]))
                    {
                        unset($menuItem->children[$subIndex]);
                        continue;
                    }
                    $methods[$key] = true;
                }
                $menuItem->children = array_values($menuItem->children);
            }
        }

        return $menuTree;
    }

    /**
     * Get links title.
     *
     * @param  array  $menus
     * @access public
     * @return void
     */
    public function getLinkTitle($menus)
    {
        $linksTitle = array();
        $menus      = $this->sortMenus($menus);
        foreach($menus as $menuKey => $menu)
        {
            if(is_array($menu) and !isset($menu['link'])) continue;

            $link = is_array($menu) ? strip_tags($menu['link']) : strip_tags($menu);
            $link = explode('|', $link);

            $linksTitle[$menuKey] = trim($link[0]);
        }

        return $linksTitle;
    }

    /**
     * Parse lang that with commonLang.
     *
     * @param  string $lang
     * @access public
     * @return string|array
     */
    public function parseCommonLang($lang)
    {
        if(empty($lang)) return $lang;

        $reg = implode('|', str_replace('$', '\$', array_keys($this->config->custom->commonLang)));
        if(!preg_match("/($reg)/", $lang)) return $lang;

        $lang     = preg_replace("/($reg)/", '$$$1$$', $lang);
        $subLangs = array_filter(explode('$$', $lang));

        return array_values($subLangs);
    }

    /**
     * Check original lang changed.
     *
     * @param  string|array $defaultValue
     * @param  string|array $customedLang
     * @access public
     * @return bool
     */
    public function isOriginalLangChanged($defaultValue, $customedLang)
    {
        if(empty($customedLang)) return false;
        if(!is_array($defaultValue) and !is_array($customedLang)) return false;
        if(!is_array($defaultValue) and is_array($customedLang)) return true;
        if(!is_array($customedLang) or count($defaultValue) != count($customedLang)) return true;

        $commonLang = $this->config->custom->commonLang;
        foreach($defaultValue as $i => $subLang)
        {
            if(!isset($customedLang[$i])) return true;

            $customedSubLang = $customedLang[$i];
            if(!isset($commonLang[$subLang]) and isset($commonLang[$customedSubLang])) return true;
            if(isset($commonLang[$subLang]) and !isset($commonLang[$customedSubLang])) return true;
            if(isset($commonLang[$subLang]) and $subLang != $customedSubLang) return true;
        }
        return false;
    }

    /**
     * Save customed lang.
     *
     * @param  string    $type        common|first|second|third|tag
     * @param  string    $moduleName
     * @param  string    $method
     * @param  string    $language    zh-cn|zh-tw|en|fr|de
     * @access public
     * @return void
     */
    public function saveCustomedLang($type, $moduleName, $method, $language)
    {
        $section = '';
        if($type == 'common') $section = '&section=';
        if($type == 'first')  $section = '&section=mainNav';
        if($type == 'tag')    $section = str_replace('_', '-', "&section=featureBar-{$method}");
        $this->loadModel('custom')->deleteItems("lang={$language}&module={$moduleName}&vision={$this->config->vision}{$section}");

        $data = fixer::input('post')->get();
        if($type == 'common') unset($data->common_SRCommon, $data->common_URCommon);
        foreach($data as $langKey => $customedLang)
        {
            if(strpos($langKey, "{$moduleName}_") !== 0) continue;
            if(is_array($customedLang))
            {
                $isCustomed = false;
                foreach($customedLang as $subLang)
                {
                    if(!isset($this->config->custom->commonLang[$subLang]) and !empty($subLang)) $isCustomed = true;
                }
                $customedLang = $isCustomed ? implode(common::checkNotCN() ? ' ' : '', $customedLang) : '';
            }
            if(empty($customedLang)) continue;

            $this->custom->setItem("{$language}." . str_replace('_', '.', $langKey), $customedLang);
        }

        if($type == 'common' and $this->config->custom->URSR)
        {
            $post  = $_POST;
            $_POST = array();

            $oldValue = $this->dao->select('*')->from(TABLE_LANG)->where('`key`')->eq($this->config->custom->URSR)->andWhere('section')->eq('URSRList')->andWhere('lang')->eq($language)->andWhere('module')->eq('custom')->fetch('value');
            $oldValue = json_decode($oldValue);

            $_POST['SRName'] = !empty($post['common_SRCommon']) ? $post['common_SRCommon'] : zget($oldValue, 'defaultSRName', $oldValue->SRName);
            $_POST['URName'] = !empty($post['common_URCommon']) ? $post['common_URCommon'] : zget($oldValue, 'defaultURName', $oldValue->URName);
            $this->custom->updateURAndSR($this->config->custom->URSR, $language);

            $_POST = $post;
        }
    }
}
