<?php
declare(strict_types = 1);
class testtaskZenTest
{
    public $testtaskZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('testtask');
        $tester->loadModel('testtask');

        $this->testtaskZenTest = initReference('testtask');
    }

    /**
     * Test setMenu method.
     *
     * @param  int $productID
     * @param  mixed $branch
     * @param  int $projectID
     * @param  int $executionID
     * @param  string $tab
     * @access public
     * @return mixed
     */
    public function setMenuTest($productID = 1, $branch = 0, $projectID = 0, $executionID = 0, $tab = 'qa')
    {
        global $app;
        $originalTab = $app->tab;
        $app->tab = $tab;

        try {
            $method = $this->testtaskZenTest->getMethod('setMenu');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$productID, $branch, (int)$projectID, (int)$executionID));
            if(dao::isError()) return dao::getError();

            return $result;
        } finally {
            $app->tab = $originalTab;
        }
    }

    /**
     * Test getBrowseBranch method.
     *
     * @param  string $branch
     * @param  string $productType
     * @access public
     * @return string
     */
    public function getBrowseBranchTest($branch = '', $productType = 'normal')
    {
        $method = $this->testtaskZenTest->getMethod('getBrowseBranch');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array($branch, $productType));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSearchParamsForCases method.
     *
     * @param  object $product
     * @param  int $moduleID
     * @param  object $testtask
     * @param  int $queryID
     * @param  string $tab
     * @access public
     * @return mixed
     */
    public function setSearchParamsForCasesTest($product = null, $moduleID = 0, $testtask = null, $queryID = 0, $tab = 'qa')
    {
        global $app;
        $originalTab = $app->tab;
        $app->tab = $tab;

        // 创建默认产品对象
        if(!$product)
        {
            $product = new stdclass();
            $product->id = 1;
            $product->name = 'Test Product';
            $product->shadow = 0;
            $product->type = 'normal';
        }

        // 创建默认测试单对象
        if(!$testtask)
        {
            $testtask = new stdclass();
            $testtask->id = 1;
            $testtask->project = 1;
            $testtask->execution = 1;
        }

        try {
            $method = $this->testtaskZenTest->getMethod('setSearchParamsForCases');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array($product, (int)$moduleID, $testtask, (int)$queryID));
            if(dao::isError()) return dao::getError();

            return $result;
        } finally {
            $app->tab = $originalTab;
        }
    }

    /**
     * Test setSearchParamsForLinkCase method.
     *
     * @param  object $product
     * @param  object $task
     * @param  string $type
     * @param  int    $param
     * @access public
     * @return mixed
     */
    public function setSearchParamsForLinkCaseTest($product = null, $task = null, $type = '', $param = 0)
    {
        // 创建默认产品对象
        if(!$product)
        {
            $product = new stdclass();
            $product->id = 1;
            $product->name = 'Test Product';
            $product->shadow = 0;
            $product->type = 'normal';
        }

        // 创建默认测试单对象
        if(!$task)
        {
            $task = new stdclass();
            $task->id = 1;
            $task->build = 1;
            $task->branch = '1';
        }

        $method = $this->testtaskZenTest->getMethod('setSearchParamsForLinkCase');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array($product, $task, $type, (int)$param));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildTaskForEdit method.
     *
     * @param  int $taskID
     * @param  int $productID
     * @access public
     * @return object
     */
    public function buildTaskForEditTest($taskID = 1, $productID = 1)
    {
        // 根据不同的taskID使用不同的测试数据
        $membersData = array(
            1 => ',admin,',
            2 => ',user1,',
            3 => ',admin,',
            4 => ',user2,',
            5 => ',admin,user2,',
            6 => ',member1,member2,'
        );

        $members = isset($membersData[$taskID]) ? $membersData[$taskID] : ',admin,user1,';

        // 模拟表单数据
        $_POST = array(
            'product'   => $productID,
            'build'     => '1',
            'name'      => '测试单名称',
            'begin'     => '2024-01-01',
            'end'       => '2024-01-31',
            'desc'      => '测试描述',
            'members'   => $members,
            'uid'       => 'uid123'
        );

        $method = $this->testtaskZenTest->getMethod('buildTaskForEdit');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$taskID, (int)$productID));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}