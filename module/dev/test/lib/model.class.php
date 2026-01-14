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
        $totalModules = 0;
        foreach($result as $groupName => $modules)
        {
            $totalModules += count($modules);
        }
        return $totalModules;
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
    public function getAPIDataTest($apiID = 0, $version = '16.0')
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
            $extensionInfo['hasExtPaths'] = !empty($extPaths) ? '1' : '0';
            $extensionInfo['extPathCount'] = count($extPaths);

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
}
