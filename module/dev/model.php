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
        $originalLangs = array();
        $clientLang    = $this->app->getClientLang();
        $defaultLang   = $this->loadDefaultLang();
        if($type == 'feature')
        {
            $this->defaultLang = $defaultLang;
            $defaultLang       = $this->loadDefaultLang($clientLang, $module);
        }

        $lang    = new stdClass();
        $langKey = '';
        if($type == 'common')
        {
            $projectKey = (int)$this->loadModel('setting')->getItem('owner=system&key=sprintConcept');
            $originalLangs['productCommon']   = $this->config->productCommonList[$clientLang][PRODUCT_KEY];
            $originalLangs['projectCommon']   = $this->config->projectCommonList[$clientLang][PROJECT_KEY];
            $originalLangs['executionCommon'] = $this->config->executionCommonList[$clientLang][$projectKey];
            $originalLangs['URCommon']        = $this->lang->dev->UR;
            $originalLangs['SRCommon']        = $this->lang->dev->SR;
            if(!$this->config->URAndSR) unset($originalLangs['SRCommon']);
        }
        elseif($type == 'feature')
        {
            $langKey = 'featureBar-' . $method . '_';
            foreach($defaultLang->$module->featureBar[$method] as $feature => $featureName)
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
        elseif($type == 'first')
        {
           $lang    = $defaultLang->mainNav;
           $langKey = 'mainNav_';
        }
        elseif($type == 'second')
        {
            $menus = new stdclass();
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
                foreach($defaultLang->$module->menu as $menuKey => $menu)
                {
                    if(is_array($menu) and !isset($menu['link'])) continue;

                    $newKey = 'menu_' . $menuKey;
                    $menus->{$newKey} = $menu;

                    if(isset($menu['dropMenu']))
                    {
                        foreach($menu['dropMenu'] as $key => $menu)
                        {
                            $dropMenuKey = $menuKey . 'DropMenu_' . $key;
                            $menus->{$dropMenuKey} = $menu;
                        }
                    }
                }
            }
            $lang = $menus;
        }
        elseif($type == 'third')
        {
            $langKey = "{$method}_";
            $lang    = $defaultLang->$module->menu->{$method}['subMenu'];
        }

        $menus = $this->getLinkTitle($lang);
        foreach($menus as $linkKey => $menu)
        {
            if($type == 'first' and in_array($linkKey, $this->config->dev->disableMainMenu)) continue;

            $originalLangs[$langKey . $linkKey] = $menu;
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
        $clientLang    = $this->app->getClientLang();

        $langKey   = '';
        $customeds = array();
        if($type == 'common')
        {
            $customeds = $this->loadModel('custom')->getItems("lang={$clientLang}&module=common&section=&vision={$this->config->vision}");
            foreach($customeds as $customed) $customedLangs[$customed->key] = $customed->value;

            $customedLangs['URCommon'] = $this->lang->dev->UR == $this->lang->URCommon ? '' : $this->lang->URCommon;
            $customedLangs['SRCommon'] = $this->lang->dev->SR == $this->lang->SRCommon ? '' : $this->lang->SRCommon;
            if($this->config->custom->URSR)
            {
                $URSRList = $this->custom->getItems("lang={$clientLang}&module=custom&section=URSRList&key={$this->config->custom->URSR}&vision={$this->config->vision}");
                $URSRList = array_shift($URSRList);
                if($URSRList)
                {
                    $URSRList = json_decode($URSRList->value);
                    $customedLangs['URCommon'] = $this->lang->dev->UR == $URSRList->URName ? '' : $URSRList->URName;
                    $customedLangs['SRCommon'] = $this->lang->dev->SR == $URSRList->SRName ? '' : $URSRList->SRName;
                }
            }
            if(!$this->config->URAndSR) unset($customedLangs['SRCommon']);
        }
        elseif($type == 'first')
        {
            $customeds = $this->loadModel('custom')->getItems("lang={$clientLang}&module=common&section=mainNav&vision={$this->config->vision}");
            $langKey   = 'mainNav_';
        }
        elseif($type == 'second')
        {
            $customeds = $this->loadModel('custom')->getItems("lang={$clientLang}&module={$module}Menu&vision={$this->config->vision}");
        }
        elseif($type == 'third')
        {
            $customeds = $this->loadModel('custom')->getItems("lang={$clientLang}&module={$module}SubMenu&section=$method&vision={$this->config->vision}");
            $langKey   = "{$method}_";
        }
        elseif($type == 'feature')
        {
            $customeds = $this->loadModel('custom')->getItems("lang={$clientLang}&module={$module}&section=featureBar-$method&vision={$this->config->vision}");
            $langKey   = "featureBar-{$method}_";
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
        $clientLang = $this->app->clientLang;
        if($language != $clientLang) $this->app->clientLang = $language;
        $langFilesToLoad = $this->app->getMainAndExtFiles($module);
        if($language != $clientLang) $this->app->clientLang = $clientLang;
        if(empty($langFilesToLoad)) return false;

        $lang = $module == 'common' ? new language() : $this->defaultLang;
        $lang->URCommon        = $this->lang->URCommon;
        $lang->SRCommon        = $this->lang->SRCommon;
        $lang->productCommon   = $this->lang->productCommon;
        $lang->projectCommon   = $this->lang->projectCommon;
        $lang->executionCommon = $this->lang->executionCommon;
        $lang->hourCommon      = $this->lang->hourCommon;
        if(!isset($lang->common)) $lang->common = new stdclass();

        $loadedLangs = array();
        foreach($langFilesToLoad as $langFile)
        {
            if(in_array($langFile, $loadedLangs)) continue;
            include $langFile;
            $loadedLangs[] = $langFile;
        }

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
     * Get feature menus.
     *
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFeatureMenus($menu, $module = '', $method = '')
    {
        $this->app->loadLang($menu);
        $menus = array();
        if(isset($this->lang->$menu->featureBar))
        {
            $featureBar = $this->lang->$menu->featureBar;
            foreach($featureBar as $methodName => $feature)
            {
                if($methodName == 'caselib') $methodName = 'caseLib';

                $subMenu = new stdClass();
                $subMenu->title  = zget($this->lang->$menu, $methodName);
                $subMenu->key    = '';
                $subMenu->module = $menu;
                $subMenu->method = $methodName;
                $subMenu->active = ($method == $methodName and $module == $menu) ? 1 : 0;

                $menus[] = $subMenu;
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

        if(empty($module)) $module = 'my';
        foreach($mainNav as $menuKey => $menu)
        {
            $menuItem = new stdclass();
            $menuItem->title  = $menu;
            $menuItem->module = $menuKey;
            $menuItem->method = '';
            $menuItem->active = ($module == $menuKey and $method == '') ? 1 : 0;
            $menuItem->key    = zget($maimNavPinYin, $menu, '');

            $childFunc = 'get' . ucfirst($type) . 'Menus';
            $menuItem->children = $this->$childFunc($menuKey, $module, $method);
            if($type != 'second' and empty($menuItem->children)) continue;

            $menuTree[] = $menuItem;
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
        foreach($menus as $menuKey => $menu)
        {
            if(is_array($menu) and !isset($menu['link'])) continue;

            $link = is_array($menu) ? strip_tags($menu['link']) : strip_tags($menu);
            $link = explode('|', $link);

            $linksTitle[$menuKey] = trim($link[0]);
        }

        return $linksTitle;
    }
}
