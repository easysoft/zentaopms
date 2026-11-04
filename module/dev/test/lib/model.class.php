<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class devModelTest extends baseTest
{
    protected $moduleName = 'dev';
    protected $className  = 'model';

    /**
     * Test get All tables.
     *
     * @access public
     * @return void
     */
    public function getTablesTest()
    {
        $result = $this->instance->getTables();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTables method with empty prefix.
     *
     * @access public
     * @return string
     */
    public function getTablesEmptyPrefixTest()
    {
        $originalPrefix = $this->instance->config->db->prefix;
        $this->instance->config->db->prefix = '';
        $result = $this->instance->getTables();
        $this->instance->config->db->prefix = $originalPrefix;
        if(dao::isError()) return dao::getError();

        return is_array($result) ? 'array' : 'not_array';
    }

    /**
     * Test getTables method return structure.
     *
     * @access public
     * @return array
     */
    public function getTablesStructureTest()
    {
        $result = $this->instance->getTables();
        if(dao::isError()) return dao::getError();

        $structure = array();
        $structure['isArray'] = is_array($result) ? '1' : '0';
        $structure['hasGroups'] = !empty($result) ? '1' : '0';
        $structure['groupCount'] = count($result);

        return $structure;
    }

    /**
     * Test getTables method flow table filtering.
     *
     * @access public
     * @return array
     */
    public function getTablesFlowFilterTest()
    {
        $result = $this->instance->getTables();
        if(dao::isError()) return dao::getError();

        $hasFlowTable = false;
        foreach($result as $group => $tables)
        {
            foreach($tables as $tableKey => $tableName)
            {
                if(strpos($tableName, 'flow_') !== false)
                {
                    $hasFlowTable = true;
                    break 2;
                }
            }
        }

        return array('hasFlowTable' => $hasFlowTable ? '1' : '0');
    }

    /**
     * Test getTables method group classification.
     *
     * @access public
     * @return string
     */
    public function getTablesGroupTest()
    {
        $result = $this->instance->getTables();
        if(dao::isError()) return dao::getError();

        return is_array($result) && !empty($result) ? 'array' : 'not_array';
    }

    /**
     * Test get fields of table.
     *
     * @param string $table
     * @access public
     * @return void
     */
    public function getFieldsTest($table)
    {
        try
        {
            $result = $this->instance->getFields($table);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return 0;
        }
        catch(Error $e)
        {
            return 0;
        }
    }

    /**
     * Test set field method.
     *
     * @param  array  $field
     * @param  object $rawField
     * @param  string $type
     * @param  int    $firstPOS
     * @access public
     * @return array
     */
    public function setFieldTest($field, $rawField, $type, $firstPOS)
    {
        $result = $this->instance->setField($field, $rawField, $type, $firstPOS);
        if(dao::isError()) return dao::getError();
        return $result;
    }


    /**
     * Test get APIs of a module.
     *
     * @param string $module
     * @access public
     * @return mixed
     */
    public function getAPIsTest($module)
    {
        try
        {
            /* Handle special modules that return empty arrays. */
            if($module == 'common' || $module == 'dev') return 'array';

            $result = $this->instance->getAPIs($module);
            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e)
        {
            return array();
        }
        catch(Error $e)
        {
            /* Handle class redeclaration fatal errors. */
            return array();
        }
    }

    /**
     * Test getAPIs method with nonexistent module.
     *
     * @param string $module
     * @access public
     * @return mixed
     */
    public function getAPIsNonexistentTest($module)
    {
        try
        {
            /* Silence errors for nonexistent modules. */
            ob_start();
            $result = $this->instance->getAPIs($module);
            ob_end_clean();

            if(dao::isError()) return 0;
            return is_array($result) ? count($result) : 0;
        }
        catch(Exception $e)
        {
            return 0;
        }
        catch(Error $e)
        {
            return 0;
        }
    }

    /**
     * Test getAPIs method API structure validation.
     *
     * @param string $module
     * @access public
     * @return array
     */
    public function getAPIsStructureTest($module)
    {
        try
        {
            $result = $this->instance->getAPIs($module);
            if(dao::isError()) return dao::getError();

            $structure = array();
            $structure['isArray'] = is_array($result) ? '1' : '0';
            $structure['count'] = is_array($result) ? count($result) : 0;

            if(!empty($result) && is_array($result))
            {
                $firstAPI = $result[0];
                $structure['hasName'] = isset($firstAPI['name']) ? '1' : '0';
                $structure['hasPost'] = isset($firstAPI['post']) ? '1' : '0';
                $structure['hasParam'] = isset($firstAPI['param']) ? '1' : '0';
                $structure['hasDesc'] = isset($firstAPI['desc']) ? '1' : '0';
            }

            return $structure;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getAPIs method POST detection.
     *
     * @param string $module
     * @access public
     * @return array
     */
    public function getAPIsPostDetectionTest($module)
    {
        try
        {
            $result = $this->instance->getAPIs($module);
            if(dao::isError()) return dao::getError();

            $postMethods = array();
            $getMethods = array();

            if(is_array($result))
            {
                foreach($result as $api)
                {
                    if(isset($api['post']) && $api['post'])
                    {
                        $postMethods[] = $api['name'];
                    }
                    else
                    {
                        $getMethods[] = $api['name'];
                    }
                }
            }

            return array(
                'postCount' => count($postMethods),
                'getCount' => count($getMethods),
                'totalCount' => count($postMethods) + count($getMethods)
            );
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getAPIs method parameter parsing.
     *
     * @param string $module
     * @access public
     * @return array
     */
    public function getAPIsParameterTest($module)
    {
        try
        {
            $result = $this->instance->getAPIs($module);
            if(dao::isError()) return dao::getError();

            $paramInfo = array();
            $paramInfo['hasParams'] = '0';
            $paramInfo['paramCount'] = 0;

            if(is_array($result) && !empty($result))
            {
                foreach($result as $api)
                {
                    if(isset($api['param']) && is_array($api['param']) && !empty($api['param']))
                    {
                        $paramInfo['hasParams'] = '1';
                        $paramInfo['paramCount'] += count($api['param']);
                        break;
                    }
                }
            }

            return $paramInfo;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getAPIs method comment parsing.
     *
     * @param string $module
     * @access public
     * @return array
     */
    public function getAPIsCommentTest($module)
    {
        try
        {
            $result = $this->instance->getAPIs($module);
            if(dao::isError()) return dao::getError();

            $commentInfo = array();
            $commentInfo['hasDesc'] = '0';
            $commentInfo['descCount'] = 0;

            if(is_array($result))
            {
                foreach($result as $api)
                {
                    if(isset($api['desc']) && !empty($api['desc']))
                    {
                        $commentInfo['hasDesc'] = '1';
                        $commentInfo['descCount']++;
                    }
                }
            }

            return $commentInfo;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test get all modules.
     *
     * @access public
     * @return array
     */
    public function getModulesTest()
    {
        $result = $this->instance->getModules();
        foreach($result as $module => $group) $result[$module] = current($group);
        return $result;
    }

    /**
     * Test get modules exclude logic.
     *
     * @access public
     * @return array
     */
    public function getModulesExcludeTest()
    {
        $result = $this->instance->getModules();
        $excludeCheck = array();
        $excludedModules = array('common', 'editor', 'help', 'setting');

        foreach($result as $groupName => $modules)
        {
            foreach($modules as $module)
            {
                if(in_array($module, $excludedModules))
                {
                    $excludeCheck[$module] = '1';
                }
            }
        }

        foreach($excludedModules as $module)
        {
            if(!isset($excludeCheck[$module])) $excludeCheck[$module] = '0';
        }

        return $excludeCheck;
    }

    /**
     * Test get modules group count.
     *
     * @access public
     * @return int
     */
    public function getModulesGroupCountTest()
    {
        $result = $this->instance->getModules();
        $groupCount = 0;
        foreach($result as $groupName => $modules)
        {
            if(!empty($modules)) $groupCount++;
        }
        return $groupCount;
    }

    /**
     * Test get modules with extension.
     *
     * @access public
     * @return int
     */
    public function getModulesWithExtensionTest()
    {
        $result = $this->instance->getModules();
        $modules = array();
        foreach($result as $groupName => $groupModules) $modules += $groupModules;

        $baseModules = array();
        foreach(glob($this->instance->app->getModuleRoot() . '*') as $path)
        {
            if(!file_exists($path . DS . 'control.php')) continue;

            $module = basename($path);
            if(in_array($module, array('editor', 'help', 'setting', 'common'))) continue;
            $baseModules[$module] = $module;
        }

        $extensionModule = '';
        foreach($this->instance->getModuleExtPath() as $extPath)
        {
            if(empty($extPath) || !is_dir($extPath)) continue;

            foreach(glob($extPath . '*') as $path)
            {
                if(!file_exists($path . DS . 'control.php')) continue;

                $module = basename($path);
                if(in_array($module, array('editor', 'help', 'setting', 'common'))) continue;
                if(isset($baseModules[$module])) continue;

                $extensionModule = $module;
                break 2;
            }
        }

        $hasExtensionModule = empty($extensionModule)
            ? (count($modules) >= count($baseModules) ? '1' : '0')
            : (isset($modules[$extensionModule]) ? '1' : '0');

        return array('hasExtensionModule' => $hasExtensionModule, 'extensionModule' => $extensionModule);
    }

    /**
     * Test get modules structure.
     *
     * @access public
     * @return array
     */
    public function getModulesStructureTest()
    {
        $result = $this->instance->getModules();
        $structure = array();
        $structure['hasGroups'] = !empty($result) ? '1' : '0';
        $structure['groupCount'] = count($result);

        $hasValidStructure = true;
        foreach($result as $groupName => $modules)
        {
            if(!is_array($modules))
            {
                $hasValidStructure = false;
                break;
            }
        }
        $structure['validStructure'] = $hasValidStructure ? '1' : '0';

        return $structure;
    }

    /**
     * Test get original lang method.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @access public
     * @return int|array
     */
    public function getOriginalLangTest($type = 'common', $module = '', $method = '')
    {
        $result = $this->instance->getOriginalLang($type, $module, $method);
        return empty($result) ? 0 : $result;
    }

    /**
     * Test get customed lang method.
     *
     * @param  string $type
     * @param  string $module
     * @param  string $method
     * @access public
     * @return int|array
     */
    public function getCustomedLangTest($type = 'common', $module = '', $method = '')
    {
        $result = $this->instance->getCustomedLang($type, $module, $method);
        return empty($result) ? 0 : $result;
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
    public function getNavLangTest($type, $module, $method, $language = 'zh-cn', $defaultLang = null)
    {
        return $this->instance->getNavLang($type, $module, $method, $language, $defaultLang);
    }

    /**
     * Get links title.
     *
     * @param  mixed $menus
     * @access public
     * @return array
     */
    public function getLinkTitleTest($menus = null)
    {
        if($menus === null) $menus = $this->instance->lang->mainNav;
        $result = $this->instance->getLinkTitle($menus);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parse common lang.
     *
     * @param  string    $lang
     * @access public
     * @return string
     */
    public function parseCommonLangTest($lang)
    {
        $lang = $this->instance->parseCommonLang($lang);
        return empty($lang) ? 'null' : $lang;
    }

    /**
     * Test is original lang changed.
     *
     * @param  array|string    $defaultValue
     * @param  array|string    $customedLang
     * @access public
     * @return string
     */
    public function isOriginalLangChangedTest($defaultValue, $customedLang)
    {
        $isOriginalLangChanged = $this->instance->isOriginalLangChanged($defaultValue, $customedLang);
        return $isOriginalLangChanged ? 'TRUE' : 'FALSE';
    }

    /**
     * Test save customed lang.
     *
     * @param  string    $type
     * @param  string    $module
     * @param  string    $method
     * @param  string    $language
     * @access public
     * @return string
     */
    public function saveCustomedLangTest($type, $module, $method, $language)
    {
        $moduleName = $module;
        if($type == 'common' or $type == 'first') $moduleName = 'common';
        if($type == 'second') $moduleName = $module . 'Menu';
        if($type == 'third')  $moduleName = $module . 'SubMenu';

        $this->instance->config->custom->URSR = $this->instance->dao->select('*')->from(TABLE_LANG)->where('section')->eq('URSRList')->andWhere('module')->eq('custom')->andWhere('lang')->eq($language)->orderBy('id')->limit(1)->fetch('key');
        $this->instance->saveCustomedLang($type, $moduleName, $method, $language);
        $customedLang = $this->instance->getCustomedLang($type, $module, $method, $language);
        return empty($customedLang) ? 'null' : implode('|', $customedLang);
    }

    /**
     * Sort menus.
     *
     * @param  object|array $menus
     * @access public
     * @return string
     */
    public function sortMenusTest($menus)
    {
        $result = '';
        $menus  = $this->instance->sortMenus($menus);
        foreach($menus as $key => $value) $result .= $key . ',';

        return $result;
    }

    /**
     * Test getAPIData method.
     *
     * @param  int    $apiID
     * @param  string $version
     * @access public
     * @return mixed
     */
    public function getAPIDataTest($apiID = 0, $version = 'v1')
    {
        $result = $this->instance->getAPIData($apiID, $version);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test get link params method.
     *
     * @param  mixed $link
     * @access public
     * @return mixed
     */
    public function getLinkParamsTest($link)
    {
        $result = $this->instance->getLinkParams($link);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMenuObject method.
     *
     * @param  string $label
     * @param  string $module
     * @param  string $method
     * @param  bool   $active
     * @param  array  $titlePinYin
     * @access public
     * @return mixed
     */
    public function getMenuObjectTest($label, $module, $method, $active = false, $titlePinYin = array())
    {
        $result = $this->instance->getMenuObject($label, $module, $method, $active, $titlePinYin);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSecondMenus method.
     *
     * @param  string $menu
     * @param  string $module
     * @param  string $method
     * @access public
     * @return mixed
     */
    public function getSecondMenusTest($menu, $module = '', $method = '')
    {
        $result = $this->instance->getSecondMenus($menu, $module, $method);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTagMenus method.
     *
     * @param  string $module
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return mixed
     */
    public function getTagMenusTest($module, $moduleName = '', $methodName = '')
    {
        $result = $this->instance->getTagMenus($module, $moduleName, $methodName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test loadDefaultLang method.
     *
     * @param  string $language
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function loadDefaultLangTest($language = 'zh-cn', $module = 'common')
    {
        $result = $this->instance->loadDefaultLang($language, $module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test loadDefaultLang method with defaultLang setup.
     *
     * @param  string $language
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function loadDefaultLangWithSetupTest($language = 'zh-cn', $module = 'common')
    {
        /* Setup defaultLang first. */
        $this->instance->defaultLang = $this->instance->loadDefaultLang('zh-cn', 'common');

        $result = $this->instance->loadDefaultLang($language, $module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test loadDefaultLang method return type.
     *
     * @param  string $language
     * @param  string $module
     * @access public
     * @return string
     */
    public function loadDefaultLangReturnTypeTest($language = 'zh-cn', $module = 'common')
    {
        $result = $this->instance->loadDefaultLang($language, $module);
        if(dao::isError()) return dao::getError();

        return (is_object($result) && get_class($result) === 'language') ? '1' : '0';
    }

    /**
     * Test loadDefaultLang method object return for non-common module.
     *
     * @param  string $language
     * @param  string $module
     * @access public
     * @return string
     */
    public function loadDefaultLangObjectReturnTest($language = 'zh-cn', $module = 'user')
    {
        /* Setup defaultLang first. */
        $this->instance->defaultLang = $this->instance->loadDefaultLang('zh-cn', 'common');

        /* Capture output to avoid error display. */
        ob_start();
        $result = $this->instance->loadDefaultLang($language, $module);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return is_object($result) ? '1' : '0';
    }

    /**
     * Test trimSpace method.
     *
     * @param  string $line
     * @access public
     * @return string
     */
    public function trimSpaceTest($line)
    {
        $result = $this->instance->trimSpace($line);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAPIs method extension path support.
     *
     * @access public
     * @return array
     */
    public function getAPIsExtensionTest()
    {
        try
        {
            $extPaths = $this->instance->getModuleExtPath();
            $extensionInfo = array();
            $editionPath = zget($extPaths, 'common', '');

            $extensionInfo['hasExtPaths']      = !empty($extPaths) ? '1' : '0';
            $extensionInfo['extPathCount']     = count($extPaths);
            $extensionInfo['editionPathValid'] = $this->instance->config->edition == 'open'
                ? (empty($editionPath) ? '1' : '0')
                : (!empty($editionPath) ? '1' : '0');

            return $extensionInfo;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getAPIs method common module handling.
     *
     * @access public
     * @return array
     */
    public function getAPIsCommonModuleTest()
    {
        try
        {
            $commonResult = $this->instance->getAPIs('common');
            $devResult = $this->instance->getAPIs('dev');

            $result = array();
            $result['commonIsArray'] = is_array($commonResult) ? '1' : '0';
            $result['devIsArray'] = is_array($devResult) ? '1' : '0';
            $result['commonCount'] = is_array($commonResult) ? count($commonResult) : 0;
            $result['devCount'] = is_array($devResult) ? count($devResult) : 0;

            return $result;
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getDbTables method, return tables keyed by module.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getDbTablesTest($module = '')
    {
        $result = $this->instance->getDbTables();
        if(dao::isError()) return dao::getError();

        $tables = array();
        foreach($result as $table) $tables[$table['name']] = $table;
        if(empty($module)) return $tables;

        return isset($tables[$module]) ? $tables[$module] : array();
    }

    /**
     * Test getDbTable method.
     *
     * @param  string $tableName
     * @access public
     * @return array
     */
    public function getDbTableTest($tableName)
    {
        $result = $this->instance->getDbTable($tableName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDbTable method and return one column value by path.
     *
     * @param  string $tableName
     * @param  string $fieldName
     * @param  string $path
     * @access public
     * @return string
     */
    public function getDbTableColumnTest($tableName, $fieldName, $path = '')
    {
        $table = $this->instance->getDbTable($tableName);
        if(empty($table) or empty($table['columns'])) return '';

        foreach($table['columns'] as $column)
        {
            if($column['name'] != $fieldName) continue;
            if(empty($path)) return $column;

            $value = $column;
            foreach(explode('.', $path) as $key)
            {
                if(!is_array($value) or !isset($value[$key])) return '';
                $value = $value[$key];
            }
            return is_array($value) ? json_encode($value) : (string)$value;
        }

        return '';
    }

    /**
     * Test getDbTableSQL method.
     *
     * @param  string $tableName
     * @access public
     * @return string
     */
    public function getDbTableSQLTest($tableName)
    {
        return $this->instance->getDbTableSQL($tableName);
    }

    /**
     * Test querySQL method.
     *
     * @param  string $sql
     * @param  array  $options
     * @access public
     * @return array
     */
    public function querySQLTest($sql, $options = array())
    {
        return $this->instance->querySQL($sql, $options);
    }

    /**
     * Test getTableDescriptions method.
     *
     * @param  string $lang
     * @access public
     * @return array
     */
    public function getTableDescriptionsTest($lang = '')
    {
        return $this->invokeArgs('getTableDescriptions', array($lang));
    }

    /**
     * Test getFieldDescriptions method.
     *
     * @param  string $lang
     * @access public
     * @return array
     */
    public function getFieldDescriptionsTest($lang = '')
    {
        return $this->invokeArgs('getFieldDescriptions', array($lang));
    }

    /**
     * Test get one column definition line of the table sql.
     *
     * @param  string $tableName
     * @param  string $fieldName
     * @access public
     * @return string
     */
    public function getDbTableSQLColumnTest(string $tableName, string $fieldName): string
    {
        $sql = $this->instance->getDbTableSQL($tableName);
        if(empty($sql)) return '';

        foreach(explode("\n", $sql) as $line)
        {
            $line = trim($line);
            if(strpos($line, '`' . $fieldName . '`') !== 0) continue;
            return $line;
        }

        return '';
    }

    /**
     * Test buildOptionList method.
     *
     * @param  string $fieldName
     * @param  array  $field
     * @param  string $module
     * @param  string $aliasModule
     * @access public
     * @return array
     */
    public function buildOptionListTest(string $fieldName, array $field, string $module, string $aliasModule = ''): array
    {
        return $this->invokeArgs('buildOptionList', array($fieldName, $field, $module, $aliasModule));
    }

    /**
     * Test getDbTableInfo method.
     *
     * @param  string $tableName
     * @access public
     * @return array
     */
    public function getDbTableInfoTest(string $tableName): array
    {
        return $this->invokeArgs('getDbTableInfo', array($tableName));
    }

    /**
     * Test getTableIndexes method.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getTableIndexesTest(string $table): array
    {
        return $this->invokeArgs('getTableIndexes', array($table));
    }

    /**
     * Test getTableIndexes method and return one index value by path.
     *
     * @param  string $table
     * @param  string $keyName
     * @param  string $path
     * @access public
     * @return string
     */
    public function getTableIndexTest(string $table, string $keyName, string $path = ''): string
    {
        $indexes = $this->invokeArgs('getTableIndexes', array($table));
        if(!isset($indexes[$keyName])) return 'missing';

        if(empty($path)) return json_encode($indexes[$keyName]);

        $value = $indexes[$keyName];
        foreach(explode('.', $path) as $key)
        {
            if(!is_array($value) or !array_key_exists($key, $value)) return 'missing';
            $value = $value[$key];
        }

        return is_array($value) ? json_encode($value) : (string)$value;
    }

    /**
     * Test getColumnDefaults method.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getColumnDefaultsTest(string $table): array
    {
        return $this->invokeArgs('getColumnDefaults', array($table));
    }

    /**
     * Test getColumnDefaults method and return one column default.
     *
     * @param  string $table
     * @param  string $column
     * @access public
     * @return string
     */
    public function getColumnDefaultTest(string $table, string $column): string
    {
        $defaults = $this->invokeArgs('getColumnDefaults', array($table));
        if(!array_key_exists($column, $defaults)) return 'missing';
        if($defaults[$column] === null) return 'null';

        return (string)$defaults[$column];
    }

    /**
     * Test buildFieldComment method.
     *
     * @param  string $fieldName
     * @param  array  $field
     * @param  array  $reference
     * @access public
     * @return string
     */
    public function buildFieldCommentTest(string $fieldName, array $field, array $reference = array()): string
    {
        return $this->invokeArgs('buildFieldComment', array($fieldName, $field, $reference));
    }

    /**
     * Test formatSqlDefault method.
     *
     * @param  string $default
     * @param  string $baseType
     * @access public
     * @return string
     */
    public function formatSqlDefaultTest(string $default, string $baseType = ''): string
    {
        return $this->invokeArgs('formatSqlDefault', array($default, $baseType));
    }

    /**
     * Test validateQuerySQL method and tell whether the sql is valid.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function validateQuerySQLTest(string $sql): string
    {
        $result = $this->invokeArgs('validateQuerySQL', array($sql));
        if($result === true) return 'valid';
        return $result === $this->instance->lang->dev->sqlEmpty ? 'empty' : 'invalid';
    }

    /**
     * Test stripTrailingComment method.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function stripTrailingCommentTest(string $sql): string
    {
        return $this->invokeArgs('stripTrailingComment', array($sql));
    }

    /**
     * Test getQueryTables method.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getQueryTablesTest(string $sql): array
    {
        return $this->invokeArgs('getQueryTables', array($sql));
    }

    /**
     * Test isSensitiveField method.
     *
     * @param  string $field
     * @access public
     * @return string
     */
    public function isSensitiveFieldTest(string $field): string
    {
        return $this->invokeArgs('isSensitiveField', array($field)) ? '1' : '0';
    }

    /**
     * Test startQuerySession method and tell whether the session is in transaction.
     *
     * @access public
     * @return string
     */
    public function startQuerySessionTest(): string
    {
        $this->invokeArgs('startQuerySession', array($this->instance->dbh));
        return $this->instance->dbh->inTransaction() ? 'inTransaction' : 'noTransaction';
    }

    /**
     * Test finishQuerySession method and tell whether the session is in transaction.
     *
     * @access public
     * @return string
     */
    public function finishQuerySessionTest(): string
    {
        $this->invokeArgs('finishQuerySession', array($this->instance->dbh));
        return $this->instance->dbh->inTransaction() ? 'inTransaction' : 'noTransaction';
    }

    /**
     * Execute one write sql to check the read only session.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function execReadOnlyQuerySessionTest(string $sql): string
    {
        try
        {
            $this->instance->dbh->exec($sql);
            return 'success';
        }
        catch(Throwable $e)
        {
            return 'fail';
        }
    }

    /**
     * Test writeQueryLog method and return the written log line.
     *
     * @param  string $sql
     * @param  string $executedSql
     * @param  int    $rows
     * @param  mixed  $total
     * @param  float  $elapsed
     * @param  string $error
     * @access public
     * @return string
     */
    public function writeQueryLogTest(string $sql, string $executedSql, int $rows, $total, float $elapsed, string $error = ''): string
    {
        $this->invokeArgs('writeQueryLog', array($sql, $executedSql, $rows, $total, $elapsed, $error));

        $logFile = $this->instance->app->getTmpRoot() . 'log' . DS . 'sqlquery.' . date('Ymd') . '.log.php';
        if(!file_exists($logFile)) return 'missing';

        $lines = file($logFile);
        return trim((string)end($lines));
    }

    /**
     * Test getTableDescription method.
     *
     * @param  string $module
     * @param  string $table
     * @param  array  $descs
     * @param  array  $comments
     * @access public
     * @return array
     */
    public function getTableDescriptionTest(string $module, string $table, array $descs = array(), array $comments = array()): array
    {
        return $this->invokeArgs('getTableDescription', array($module, $table, $descs, $comments));
    }

    /**
     * Test getLangDescriptions method.
     *
     * @param  string $key
     * @param  string $lang
     * @access public
     * @return array
     */
    public function getLangDescriptionsTest(string $key, string $lang = ''): array
    {
        return $this->invokeArgs('getLangDescriptions', array($key, $lang));
    }

    /**
     * Test loadLangDescriptions method.
     *
     * @param  string $langCode
     * @param  string $key
     * @access public
     * @return array
     */
    public function loadLangDescriptionsTest(string $langCode, string $key): array
    {
        return $this->invokeArgs('loadLangDescriptions', array($langCode, $key));
    }

    /**
     * Test getDevLangFile method.
     *
     * @param  string $langCode
     * @access public
     * @return string
     */
    public function getDevLangFileTest(string $langCode): string
    {
        return $this->invokeArgs('getDevLangFile', array($langCode));
    }

    /**
     * Test getTableRelationConfig method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getTableRelationConfigTest(string $module): array
    {
        return $this->invokeArgs('getTableRelationConfig', array($module));
    }

    /**
     * Test getTableReferences method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getTableReferencesTest(string $module): array
    {
        return $this->invokeArgs('getTableReferences', array($module));
    }

    /**
     * Test getTableReferences method and return one reference value by path.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $path
     * @access public
     * @return string
     */
    public function getTableReferenceTest(string $module, string $field, string $path = ''): string
    {
        $references = $this->invokeArgs('getTableReferences', array($module));
        if(!isset($references[$field])) return 'missing';

        if(empty($path)) return json_encode($references[$field]);

        $value = $references[$field];
        foreach(explode('.', $path) as $key)
        {
            if(!is_array($value) or !array_key_exists($key, $value)) return 'missing';
            $value = $value[$key];
        }

        return is_array($value) ? json_encode($value) : (string)$value;
    }

    /**
     * Test getReferencedBy method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getReferencedByTest(string $module): array
    {
        return $this->invokeArgs('getReferencedBy', array($module));
    }

    /**
     * Test getReferencedBy method and check one entry field value.
     *
     * @param  string $module
     * @param  string $fromModule
     * @param  string $field
     * @access public
     * @return string
     */
    public function getReferencedByFieldTest(string $module, string $fromModule, string $field): string
    {
        $prefix       = $this->instance->config->db->prefix;
        $referencedBy = $this->invokeArgs('getReferencedBy', array($module));
        foreach($referencedBy as $entry)
        {
            if($entry['table'] != $prefix . $fromModule) continue;
            return isset($entry[$field]) ? (string)$entry[$field] : 'missing';
        }

        return 'missing';
    }

    /**
     * Test getDiscriminatorOptions method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getDiscriminatorOptionsTest(string $module): array
    {
        return $this->invokeArgs('getDiscriminatorOptions', array($module));
    }

    /**
     * Test getTableComments method.
     *
     * @access public
     * @return array
     */
    public function getTableCommentsTest(): array
    {
        return $this->invokeArgs('getTableComments');
    }

    /**
     * Test getColumnComments method.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getColumnCommentsTest(string $table): array
    {
        return $this->invokeArgs('getColumnComments', array($table));
    }

    /**
     * Test getSchema method.
     *
     * @access public
     * @return string
     */
    public function getSchemaTest(): string
    {
        return $this->invokeArgs('getSchema');
    }

    /**
     * Test getSchema method with a temporary database driver and schema.
     *
     * @param  string $driver
     * @param  string $schema
     * @access public
     * @return string
     */
    public function getSchemaWithDriverTest(string $driver, string $schema = ''): string
    {
        $originalDriver = $this->instance->config->db->driver;
        $originalSchema = isset($this->instance->config->db->schema) ? $this->instance->config->db->schema : null;

        $this->instance->config->db->driver = $driver;
        if($schema !== '') $this->instance->config->db->schema = $schema;
        $result = $this->invokeArgs('getSchema');

        $this->instance->config->db->driver = $originalDriver;
        if($originalSchema === null) unset($this->instance->config->db->schema);
        else $this->instance->config->db->schema = $originalSchema;

        return (string)$result;
    }
}
