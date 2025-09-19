<?php
class devTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('dev');
    }

    /**
     * Test get All tables.
     *
     * @access public
     * @return void
     */
    public function getTablesTest()
    {
        $result = $this->objectModel->getTables();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTables method with empty prefix.
     *
     * @access public
     * @return mixed
     */
    public function getTablesEmptyPrefixTest()
    {
        $originalPrefix = $this->objectModel->config->db->prefix;
        $this->objectModel->config->db->prefix = '';
        $result = $this->objectModel->getTables();
        $this->objectModel->config->db->prefix = $originalPrefix;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTables method return structure.
     *
     * @access public
     * @return array
     */
    public function getTablesStructureTest()
    {
        $result = $this->objectModel->getTables();
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
        $result = $this->objectModel->getTables();
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
     * @return array
     */
    public function getTablesGroupTest()
    {
        $result = $this->objectModel->getTables();
        if(dao::isError()) return dao::getError();

        $groups = array();
        foreach($result as $groupName => $tables)
        {
            $groups[$groupName] = count($tables);
        }

        return $groups;
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
        $result = $this->objectModel->getFields($table);
        return $result;
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
        $result = $this->objectModel->setField($field, $rawField, $type, $firstPOS);
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
        $result = $this->objectModel->getAPIs($module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test get all modules.
     *
     * @access public
     * @return array
     */
    public function getModulesTest()
    {
        $result = $this->objectModel->getModules();
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
        $result = $this->objectModel->getModules();
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
        $result = $this->objectModel->getModules();
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
        $result = $this->objectModel->getModules();
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
        $result = $this->objectModel->getModules();
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
        $result = $this->objectModel->getOriginalLang($type, $module, $method);
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
        $result = $this->objectModel->getCustomedLang($type, $module, $method);
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
        return $this->objectModel->getNavLang($type, $module, $method, $language, $defaultLang);
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
        if($menus === null) $menus = $this->objectModel->lang->mainNav;
        $result = $this->objectModel->getLinkTitle($menus);
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
        $lang = $this->objectModel->parseCommonLang($lang);
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
        $isOriginalLangChanged = $this->objectModel->isOriginalLangChanged($defaultValue, $customedLang);
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

        $this->objectModel->config->custom->URSR = $this->objectModel->dao->select('*')->from(TABLE_LANG)->where('section')->eq('URSRList')->andWhere('module')->eq('custom')->andWhere('lang')->eq($language)->orderBy('id')->limit(1)->fetch('key');
        $this->objectModel->saveCustomedLang($type, $moduleName, $method, $language);
        $customedLang = $this->objectModel->getCustomedLang($type, $module, $method, $language);
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
        $menus  = $this->objectModel->sortMenus($menus);
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
        $result = $this->objectModel->getAPIData($apiID, $version);
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
        $result = $this->objectModel->getLinkParams($link);
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
        $result = $this->objectModel->getMenuObject($label, $module, $method, $active, $titlePinYin);
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
        $result = $this->objectModel->getSecondMenus($menu, $module, $method);
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
        $result = $this->objectModel->getTagMenus($module, $moduleName, $methodName);
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
        $result = $this->objectModel->loadDefaultLang($language, $module);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
