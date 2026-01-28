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
    public function getTables(): array
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $tables = array();
        $datatables = $this->dao->showTables();
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
        if(empty($table)) return array();

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
            $rawFields = $this->dao->descTable($table);
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
            $tableName = zget($this->lang->dev->tableList, $module, $module);
            $field['name'] = (isset($this->lang->$module->{$rawField->field}) and is_string($this->lang->$module->{$rawField->field})) ? str_replace('%s', $tableName, $this->lang->$module->{$rawField->field}) : '';
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
            foreach($methodReflect->getParameters() as $param)
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
    public function getModules(): array
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
                if($module == 'product')
                {
                    $defaultLang->product->menu->system  = $defaultLang->product->system;
                    $defaultLang->product->menuOrder[41] = 'system';
                }

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
           if(!empty($module) and isset($defaultLang->$module->menu->{$method}['menuOrder'])) $menus->menuOrder = $defaultLang->$module->menu->{$method}['menuOrder'];
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
            if($menuKey == 'project')
            {
                $projectTypeList = array('scrum', 'waterfall', 'kanbanProject');
                foreach($projectTypeList as $projectType)
                {
                    if(isset($menus[$projectType])) $sortedMenus[$projectType] = $menus[$projectType];
                }
            }
            if(isset($menus[$menuKey])) $sortedMenus[$menuKey] = $menus[$menuKey];
        }

        $sortedMenus = array_merge($sortedMenus, $menus);
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
            if($this->config->vision == 'rnd')
            {
                $projectKey = (int)$this->loadModel('setting')->getItem('owner=system&key=sprintConcept');
                $originalLangs['productCommon'] = $this->config->productCommonList[$language][PRODUCT_KEY];
                $originalLangs['projectCommon'] = $this->config->projectCommonList[$language][PROJECT_KEY];
                $originalLangs['executionCommon'] = $this->config->executionCommonList[$language][$projectKey];
                $originalLangs['ERCommon']        = $this->lang->dev->ER;
                $originalLangs['URCommon']        = $this->lang->dev->UR;
                $originalLangs['SRCommon']        = $this->lang->dev->SR;

                $URSRList = $this->loadModel('custom')->getItems("lang={$language}&module=custom&section=URSRList&key={$this->config->custom->URSR}&vision={$this->config->vision}");
                if(empty($URSRList)) $URSRList = $this->custom->getItems("lang={$language}&module=custom&section=URSRList&vision={$this->config->vision}");
                $URSRList = array_shift($URSRList);
                if($URSRList)
                {
                    $URSRList = json_decode($URSRList->value);
                    $originalLangs['ERCommon'] = isset($URSRList->defaultERName) ? $URSRList->defaultERName : $URSRList->ERName;
                    $originalLangs['URCommon'] = isset($URSRList->defaultURName) ? $URSRList->defaultURName : $URSRList->URName;
                    $originalLangs['SRCommon'] = isset($URSRList->defaultSRName) ? $URSRList->defaultSRName : $URSRList->SRName;
                }
                if(!$this->config->URAndSR)  unset($originalLangs['URCommon']);
                if(!$this->config->enableER) unset($originalLangs['ERCommon']);
            }
            else
            {
                $originalLangs['projectCommon'] = $this->config->projectCommonList[$language][PROJECT_KEY];
            }
        }
        elseif($type == 'tag')
        {
            if(!isset($defaultLang->$module->featureBar)) return $originalLangs;
            if($this->config->vision == 'lite' and isset($this->config->dev->liteTagMethod["$module-$method"])) $method = $this->config->dev->liteTagMethod["$module-$method"];

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
                if(isset($this->config->dev->skipTags["$module-$method"]) and in_array($feature, $this->config->dev->skipTags["$module-$method"])) continue;
                $moreSelectsTags = isset($defaultLang->{$module}->moreSelects[$method][$feature]) ? $defaultLang->{$module}->moreSelects[$method][$feature] : '';
                if($moreSelectsTags)
                {
                    foreach($moreSelectsTags as $tagKey => $tagName) $originalLangs["moreSelects-{$method}-{$feature}_" . $tagKey] = $tagName;
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
                if(in_array($module, array('scrum', 'waterfall', 'execution')) and in_array($linkKey, $this->config->dev->skipMenus[$module])) continue;

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
                $URSRList = $this->custom->getItems("lang={$language}&module=custom&section=URSRList&key={$this->config->custom->URSR}&vision={$this->config->vision}");
                if(empty($URSRList)) $URSRList = $this->custom->getItems("lang={$language}&module=custom&section=URSRList&vision={$this->config->vision}");
                $URSRList = array_shift($URSRList);
                if($URSRList)
                {
                    $URSRList = json_decode($URSRList->value);
                    $defaultERName = isset($URSRList->defaultERName) ? $URSRList->defaultERName : $URSRList->ERName;
                    $defaultURName = isset($URSRList->defaultURName) ? $URSRList->defaultURName : $URSRList->URName;
                    $defaultSRName = isset($URSRList->defaultSRName) ? $URSRList->defaultSRName : $URSRList->SRName;
                    $customedLangs['ERCommon'] = $defaultERName == $URSRList->ERName ? '' : $URSRList->ERName;
                    $customedLangs['URCommon'] = $defaultURName == $URSRList->URName ? '' : $URSRList->URName;
                    $customedLangs['SRCommon'] = $defaultSRName == $URSRList->SRName ? '' : $URSRList->SRName;
                }
                if(!$this->config->enableER) unset($customedLangs['ERCommon']);
                if(!$this->config->URAndSR)  unset($customedLangs['URCommon']);
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
                if($this->config->vision == 'lite' and isset($this->config->dev->liteTagMethod["$module-$method"])) $method = $this->config->dev->liteTagMethod["$module-$method"];

                $method = str_replace('_', '-', $method);
                $customeds['featureBar']    = $this->loadModel('custom')->getItems("lang={$language}&module={$module}&section=featureBar-$method&vision={$this->config->vision}");
                $customeds['moreSelects']   = $this->dao->select('*')->from(TABLE_LANG)->where('`lang`')->eq($language)->andWhere('module')->eq($module)->andWhere('section')->like("moreSelects-$method%")->andWhere('vision')->eq($this->config->vision)->fetchAll();
                break;
        }

        foreach($customeds as $customType => $customed)
        {
            if(is_array($customed))
            {
                foreach($customed as $row)
                {
                    $langKey = $customType == 'featureBar' ? "featureBar-{$method}_" : $row->section . '_';
                    $rowKey  = $row->key;
                    $customedLangs[$langKey . $rowKey] = $row->value;
                }
            }
            else
            {
                $customedKey = $customed->key;
                if($type == 'second') $customedKey = $customed->section . '_' . $customed->key;
                $customedLangs[$langKey . $customedKey] = $customed->value;
            }
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
        $lang->ERCommon        = '$ERCOMMON';
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
            foreach($this->config->dev->projectMenus as $subMenuKey) $menus[] = $this->getMenuObject($this->lang->dev->projectMenu[$subMenuKey], $subMenuKey, '', ($module == $subMenuKey and $method == ''), $menusPinYin);
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
        if(isset($this->lang->$menu->menuOrder)) $this->lang->$menu->menu->menuOrder = $this->lang->$menu->menuOrder;

        $menuLang    = $this->getLinkTitle($this->lang->$menu->menu);
        $menusPinYin = common::convert2Pinyin($menuLang);
        foreach($menuLang as $menuKey => $menuName)
        {
            if(!isset($this->lang->$menu->menu->{$menuKey}['subMenu']) or (is_object($this->lang->$menu->menu->{$menuKey}['subMenu']) and !get_object_vars($this->lang->$menu->menu->{$menuKey}['subMenu']))) continue;

            $menus[] = $this->getMenuObject($menuName, $menu, $menuKey, ($module == $menu and $method == $menuKey), $menusPinYin);
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
        if(!$module or ($this->config->vision == 'lite' and $module == 'execution')) return $menus;

        $titleList  = array();
        $tagMethods = array();

        /* Convenience secondary menu. */
        foreach(array('homeMenu', 'menu') as $menu)
        {
            if(!isset($this->lang->$module->$menu)) continue;
            /* Sort menu. */
            $menuList = $this->lang->$module->$menu;
            if($menu == 'menu' and isset($this->lang->$module->menuOrder))
            {
                $menuList->menuOrder = $this->lang->$module->menuOrder;
                $menuList = $this->sortMenus($menuList);
            }
            if(!is_array($menuList)) $menuList = (array)$menuList;

            /* Construct menu tree. */
            foreach($menuList as $menuKey => $menuValue)
            {
                $link = $this->getLinkParams($menuValue);
                if(!$link) continue;

                list($label, $thisModule, $thisMethod) = $link;

                /* Replace menu params. */
                if(isset($this->config->dev->linkMethods[$module]["{$thisModule}-{$thisMethod}"]))
                {
                    list($thisModule, $thisMethod) = $this->config->dev->linkMethods[$module]["{$thisModule}-{$thisMethod}"];
                }

                if($this->config->vision == 'lite' and $module == 'kanbanProject' and $thisMethod == 'index') continue;

                $subMenu      = $this->getMenuObject($label, $thisModule, $thisMethod, ($methodName == $thisMethod and $moduleName == $thisModule));
                $titleList[]  = $subMenu->title;
                $tagMethods[] = $thisMethod;

                /* Set three-level menu.  */
                $this->app->loadLang($thisModule);
                $hasFeatureBar = false;
                if(isset($this->lang->$thisModule->featureBar[$thisMethod])) $hasFeatureBar = true;

                if(is_array($menuValue))
                {
                    /* Convenience third menu and secondary drop menu. */
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
                            $link = $this->getLinkParams($subMenuValue);
                            if(!$link) continue;

                            list($label, $thisModule, $thisMethod) = $link;

                            if($label == '@branch@') $label = $this->lang->dev->branch;

                            /* Get the three-level menu under the drop menu. */
                            $moduleList = array($thisModule);
                            if(isset($subMenuValue['subModule'])) $moduleList = array_merge($moduleList, explode(',', $subMenuValue['subModule']));
                            $moduleList = array_unique($moduleList);

                            foreach($moduleList as $moduleKey)
                            {
                                if(empty($moduleKey)) continue;
                                if(isset($menuList[$subMenuKey]) and isset($menuList[$subMenuKey]['subMenu']) and isset($menuList[$subMenuKey]['subMenu']->$moduleKey))
                                {
                                    $labelList = $this->getLinkTitle(array($moduleKey => $menuList[$subMenuKey]['subMenu']->$moduleKey));
                                    $label     = zget($labelList, $moduleKey, $label);
                                }

                                $this->app->loadLang($moduleKey);

                                /* Construct secondary menu subitems. */
                                if(isset($this->lang->$moduleKey->featureBar[$menuKey][$subMenuKey]))
                                {
                                    $titleList[]  = $label;
                                    $tagMethods[] = $thisMethod;

                                    $methodKey = "{$thisMethod}_{$subMenuKey}";
                                    $subMenu->children[] = $this->getMenuObject($label, $moduleKey, $methodKey, ($methodName == $methodKey and $moduleName == $moduleKey));
                                    $hasFeatureBar = true;
                                }

                                /* Replace secondary menu. */
                                if(isset($this->lang->$moduleKey->featureBar[$thisMethod]))
                                {
                                    if(is_array($this->lang->$moduleKey->featureBar[$thisMethod]))
                                    {
                                        $arrayKey = key($this->lang->$moduleKey->featureBar[$thisMethod]);
                                        if(is_array($this->lang->$moduleKey->featureBar[$thisMethod][$arrayKey])) continue;
                                    }

                                    $titleList[]  = $label;
                                    $tagMethods[] = $thisMethod;

                                    $subMenu = $this->getMenuObject($label, $moduleKey, $thisMethod, ($methodName == $thisMethod and $moduleName == $moduleKey));
                                    $menus["$moduleKey-$thisMethod"] = $subMenu;
                                    $hasFeatureBar = false;
                                }
                            }
                        }
                    }
                }

                if($hasFeatureBar) $menus[$menuKey] = $subMenu;
            }
        }

        /* Merge other feature bar menu tree. */
        if($this->config->vision == 'rnd' or in_array($module, $this->config->dev->onlyMainMenu))
        {
            $this->app->loadLang($module);
            if(isset($this->lang->$module->featureBar))
            {
                foreach($this->lang->$module->featureBar as $method => $tags)
                {
                    if(in_array($method, $tagMethods)) continue;

                    $label          = zget($this->lang->$module, $method, $this->lang->$module->common);
                    $titleList[]    = $label;
                    $tagMethods[]   = $method;
                    $menus[$method] = $this->getMenuObject($label, $module, $method, ($methodName == $method and $moduleName == $module));
                }
            }
        }

        $titlePinYin = common::convert2Pinyin($titleList);
        foreach($menus as &$menu) $menu->key = !empty($titlePinYin) ? zget($titlePinYin, $menu->title, '') : '';

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
        if($this->config->vision != 'open' and $type == 'second')
        {
            $flowNav = $this->dao->select('module')->from(TABLE_WORKFLOW)
                ->where('buildin')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('navigator')->in('primary,secondary')
                ->fetchPairs();
            foreach($flowNav as $nav) unset($mainNav->$nav);
        }

        if($type != 'second')
        {
            /* Set main nav list. */
            foreach($this->lang->mainNav as $menuKey => $menu)
            {
                if($menuKey == 'project')
                {
                    foreach($this->config->dev->projectMenus as $subMenuKey) $mainNav[$subMenuKey] = $this->lang->dev->projectMenu[$subMenuKey];
                }

                $mainNav[$menuKey] = $menu;
            }
        }

        /* Get menu tree by menu setting. */
        $mainNav       = $this->getLinkTitle($mainNav);
        $mainNavPinYin = common::convert2Pinyin($mainNav);
        foreach($mainNav as $menuKey => $menu)
        {
            $menuItem = $this->getMenuObject($menu, $menuKey, '', ($module == $menuKey and $method == ''), $mainNavPinYin);

            $childFunc = 'get' . ucfirst($type) . 'Menus';
            if($type == 'tag' and in_array($menuKey, $this->config->dev->projectMenus))
            {
                if($menuKey != 'project') continue;
                foreach($this->config->dev->projectMenus as $projectModule)
                {
                    $children = $this->getTagMenus($projectModule, $module, $method);
                    $menuItem->children = array_merge($menuItem->children, $children);
                }
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
            foreach($menuTree as $menuItem)
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
     * Get link params.
     *
     * @param  string $link
     * @access public
     * @return array|bool
     */
    public function getLinkParams($link)
    {
        if(is_array($link))
        {
            if(!isset($link['link'])) return false;

            $link = $link['link'];
        }

        if(strpos($link, '|') === false) return false;

        return  explode('|', $link);
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
        if($type == 'tag')
        {
            $section = str_replace('_', '-', "&section=featureBar-{$method}");
            $this->dao->delete()->from(TABLE_LANG)->where('lang')->eq($language)->andWhere('module')->eq($moduleName)->andWhere('section')->like("moreSelects-$method%")->andWhere('vision')->eq($this->config->vision)->exec();
        }

        $key = '';
        if($type == 'common') $key = '&key=projectCommon,productCommon,executionCommon';

        $this->loadModel('custom')->deleteItems("lang={$language}&module={$moduleName}&vision={$this->config->vision}{$section}{$key}");

        $data = fixer::input('post')->get();
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
            $oldValue = $this->dao->select('*')->from(TABLE_LANG)->where('`key`')->eq($this->config->custom->URSR)->andWhere('section')->eq('URSRList')->andWhere('lang')->eq($language)->andWhere('module')->eq('custom')->fetch('value');
            $URSRList = $this->loadModel('custom')->getItems("lang={$language}&module=custom&section=URSRList&key={$this->config->custom->URSR}&vision={$this->config->vision}");
            if(empty($URSRList)) $URSRList = $this->custom->getItems("lang={$language}&module=custom&section=URSRList&vision={$this->config->vision}");
            $URSRList = array_shift($URSRList);

            $this->config->custom->URSR = $URSRList->key;
            $oldValue = json_decode($URSRList->value);

            $setting = array(
                'SRName' => $this->post->common_SRCommon !== false ? $this->post->common_SRCommon : zget($oldValue, 'defaultSRName', $oldValue->SRName),
                'URName' => $this->post->common_URCommon !== false ? $this->post->common_URCommon : zget($oldValue, 'defaultURName', $oldValue->URName),
                'ERName' => $this->post->common_ERCommon !== false ? $this->post->common_ERCommon : zget($oldValue, 'defaultERName', $oldValue->ERName)
            );
            $this->custom->updateURAndSR($this->config->custom->URSR, $language, $setting);
        }
    }

    /**
     * Get tree menu object.
     *
     * @param  string $label
     * @param  string $module
     * @param  string $method
     * @param  bool   $active
     * @param  array  $titlePinYin
     * @access public
     * @return object
     */
    public function getMenuObject($label, $module, $method, $active = false, $titlePinYin = array())
    {
        $menu = new stdclass();
        $menu->title    = isset($this->lang->dev->replaceLable["$module-$method"]) ? $this->lang->dev->replaceLable["$module-$method"] : $label;
        $menu->key      = !empty($titlePinYin) ? zget($titlePinYin, $menu->title, '') : '';
        $menu->module   = $module;
        $menu->method   = $method;
        $menu->active   = (int)$active;
        $menu->children = array();
        return $menu;
    }

    /**
     * Get tree by type.
     *
     * @param  string $currentObject
     * @param  string $type          module|table
     * @access public
     * @return array
     */
    public function getTree(string $currentObject, string $type): array
    {
        $tree = array();
        if(!in_array($type, array('module', 'table'))) return $tree;

        $currentModule    = $this->app->getModuleName();
        $currentMethod    = $this->app->getMethodName();
        $currentParamName = $type == 'module' ? 'module' : 'table';

        $objects   = $type == 'module' ? $this->getModules() : $this->getTables();
        $groupList = array_merge($this->lang->dev->groupList, $this->lang->dev->endGroupList);
        foreach($groupList as $moduleKey => $moduleName)
        {
            if(empty($objects[$moduleKey])) continue;

            $module = new stdclass();
            $module->id       = $moduleKey;
            $module->key      = $moduleKey;
            $module->name     = $moduleName;
            $module->url      = '';
            $module->active   = 0;
            $module->children = array();
            foreach($objects[$moduleKey] as $objectKey => $objectName)
            {
                $defaultValue   = $type == 'module' ? $objectName : $this->config->db->prefix . $objectKey;

                $object         = new stdclass();
                $object->id     = $objectName;
                $object->key    = $objectName;
                $object->name   = zget($this->lang->dev->tableList, $objectKey, $defaultValue);
                $object->url    = helper::createLink($currentModule, $currentMethod, "{$currentParamName}={$objectName}");
                $object->active = $objectName == $currentObject ? 1 : 0;
                if($object->active) $module->active = 1;

                $module->children[] = $object;
            }
            $tree[] = $module;
        }
        return $tree;
    }

    /**
     * Create demo data.
     *
     * @param  int     $apiID
     * @param  string  $version
     * @access public
     * @return int
     */
    public function getAPIData($apiID = 0, $version = '16.0')
    {
        $modules = $this->loadModel('api')->getDemoData('module', $version);
        foreach($modules as $index => $module)
        {
            $modules[$module->order] = $module;
            unset($modules[$index]);

            $moduleiNames[$module->id] = $module->name;
        }
        ksort($modules);

        $apis    = $this->api->getDemoData('api', $version);
        $structs = $this->api->getDemoData('apistruct', $version);

        $restApi    = new stdClass();
        $moduleAPIs = array();
        foreach($apis as $api)
        {
            if(!isset($moduleAPIs[$api->module])) $moduleAPIs[$api->module] = array();
            $moduleAPIs[$api->module][] = $api;

            if($api->id == $apiID)
            {
                $api->moduleName = zget($moduleiNames, $api->module, '');
                $api->params   = json_decode($api->params, true);
                $api->response = json_decode($api->response, true);
                $restApi = $api;
            }
        }

        $typeList = array();
        foreach($this->lang->api->paramsTypeOptions as $key => $item) $typeList[$key] = $item;
        foreach($structs as $struct) $typeList[$struct->id] = $struct->name;

        $this->loadModel('doc');
        $treeMenu = array();
        foreach($modules as $module)
        {
            $treeNode = new stdclass();
            $treeNode->id       = $module->id;
            $treeNode->name     = $module->name;
            $treeNode->url      = '';
            $treeNode->active   = 0;
            $treeNode->children = array();
            foreach($moduleAPIs[$module->id] as $moduleAPI)
            {
                $child         = new stdclass();
                $child->id     = $moduleAPI->id;
                $child->icon   = 'icon-file-text';
                $child->name   = $moduleAPI->title;
                $child->url    = helper::createLink('dev', 'api', "module=restapi&apiID={$moduleAPI->id}");
                $child->active = $apiID == $moduleAPI->id ? 1 : 0;
                if($child->active) $treeNode->active = 1;

                $treeNode->children[] = $child;
            }
            $treeMenu[] = $treeNode;
        }

        return array($restApi, $typeList, $treeMenu);
    }
}
