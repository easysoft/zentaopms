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
        return $result;
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
     * @param  array  $type
     * @access public
     * @return array
     */
    public function setFieldTest($type)
    {
        $this->objectModel->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql = "DESC zt_product";
        $rawFields = $this->objectModel->dbh->query($sql)->fetchAll();
        return $this->objectModel->setField((array)$rawFields[1], (object)$rawFields[1], $type, 1);
    }


    /**
     * Test get APIs of a module.
     *
     * @param string $module
     * @access public
     * @return void
     */
    public function getAPIsTest($module)
    {
        $result = $this->objectModel->getAPIs($module);
        return $result;
    }

    /**
     * Test get all modules.
     *
     * @access public
     * @return void
     */
    public function getModulesTest()
    {
        $result = $this->objectModel->getModules();
        foreach($result as $module => $group) $result[$module] = current($group);
        return $result;
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
     * @access public
     * @return array
     */
    public function getLinkTitleTest()
    {
        $lang = $this->objectModel->lang->mainNav;
        return $this->objectModel->getLinkTitle($lang);
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
}
