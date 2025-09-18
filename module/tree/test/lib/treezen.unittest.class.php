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
        $method = $this->objectZen->getMethod('setRoot');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($rootID, $viewType, $branch));
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
    public function getBranchesTest(object $product = null, string $viewType = '', int $currentModuleID = 0)
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
}