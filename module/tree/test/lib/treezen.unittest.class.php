<?php
declare(strict_types = 1);
class treeTest
{
    public function __construct()
    {
        $this->objectZen = initReference('tree');
    }

    /**
     * Test setRoot method.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $branch
     * @access public
     * @return mixed
     */
    public function setRootTest(int $rootID = 0, string $viewType = '', string $branch = '')
    {
        ob_start();
        $method = $this->objectZen->getMethod('setRoot');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($rootID, $viewType, $branch));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBranches method.
     *
     * @param  object $product
     * @param  string $viewType
     * @param  int    $currentModuleID
     * @access public
     * @return mixed
     */
    public function getBranchesTest(?object $product = null, string $viewType = '', int $currentModuleID = 0)
    {
        if($product === null) {
            $product = (object)array('id' => 1, 'type' => 'normal', 'name' => 'Test Product');
        }

        $method = $this->objectZen->getMethod('getBranches');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($product, $viewType, $currentModuleID));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateBrowseLang method.
     *
     * @param  string $viewType
     * @access public
     * @return mixed
     */
    public function updateBrowseLangTest(string $viewType = '')
    {
        global $app, $lang;

        $app->loadLang('tree');
        $app->loadLang('host');

        $originalManage = $lang->tree->manage;

        $treeZen = $this->objectZen->newInstance();
        $method = $this->objectZen->getMethod('updateBrowseLang');
        $method->setAccessible(true);
        $method->invoke($treeZen, $viewType);
        if(dao::isError()) return dao::getError();

        return $lang->tree;
    }

    /**
     * Test updateWorkflowLang method.
     *
     * @param  string $viewType
     * @access public
     * @return mixed
     */
    public function updateWorkflowLangTest(string $viewType = '')
    {
        global $app, $lang;

        $app->loadLang('tree');

        $treeZen = $this->objectZen->newInstance();
        $method = $this->objectZen->getMethod('updateWorkflowLang');
        $method->setAccessible(true);
        $method->invoke($treeZen, $viewType);
        if(dao::isError()) return dao::getError();

        return $lang->tree;
    }

    /**
     * Test updateRawModule method.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @access public
     * @return mixed
     */
    public function updateRawModuleTest(int $rootID = 0, string $viewType = '')
    {
        global $app;

        // 保存原始的rawModule值
        $originalRawModule = $app->rawModule ?? '';

        $treeZen = $this->objectZen->newInstance();
        $method = $this->objectZen->getMethod('updateRawModule');
        $method->setAccessible(true);
        $method->invoke($treeZen, $rootID, $viewType);
        if(dao::isError()) return dao::getError();

        // 返回设置后的rawModule值
        $result = $app->rawModule ?? '';

        // 恢复原始值
        $app->rawModule = $originalRawModule;

        return $result;
    }

    /**
     * Test printOptionMenuMHtml method.
     *
     * @param  array  $optionMenu
     * @param  string $viewType
     * @param  int    $rootID
     * @access public
     * @return mixed
     */
    public function printOptionMenuMHtmlTest(array $optionMenu = array(), string $viewType = '', int $rootID = 0)
    {
        // 开启输出缓冲
        ob_start();

        $treeZen = $this->objectZen->newInstance();
        $method = $this->objectZen->getMethod('printOptionMenuMHtml');
        $method->setAccessible(true);
        $method->invoke($treeZen, $optionMenu, $viewType, $rootID);
        if(dao::isError()) return dao::getError();

        // 获取输出内容
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Test printOptionMenuArray method.
     *
     * @param  array $optionMenu
     * @access public
     * @return mixed
     */
    public function printOptionMenuArrayTest(array $optionMenu = array())
    {
        // 开启输出缓冲
        ob_start();

        $treeZen = $this->objectZen->newInstance();
        $method = $this->objectZen->getMethod('printOptionMenuArray');
        $method->setAccessible(true);
        $method->invoke($treeZen, $optionMenu);
        if(dao::isError()) return dao::getError();

        // 获取输出内容
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Test printOptionMenuHtml method.
     *
     * @param  array  $optionMenu
     * @param  string $viewType
     * @param  int    $fieldID
     * @param  int    $currentModuleID
     * @access public
     * @return mixed
     */
    public function printOptionMenuHtmlTest(array $optionMenu = array(), string $viewType = '', $fieldID = '', int $currentModuleID = 0)
    {
        global $tester;

        // 开启输出缓冲
        ob_start();

        $treeZen = $this->objectZen->newInstance();
        // 确保tree模块被加载
        $treeZen->tree = $tester->loadModel('tree');

        $method = $this->objectZen->getMethod('printOptionMenuHtml');
        $method->setAccessible(true);
        $method->invoke($treeZen, $optionMenu, $viewType, $fieldID, $currentModuleID);
        if(dao::isError()) return dao::getError();

        // 获取输出内容
        $output = ob_get_clean();

        return $output;
    }
}