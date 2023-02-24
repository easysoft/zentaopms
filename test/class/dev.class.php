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
        return $result;
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
}
