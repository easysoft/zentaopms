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

    /**
     * Test buildTaskForStart method.
     *
     * @param  int $taskID
     * @access public
     * @return object
     */
    public function buildTaskForStartTest($taskID = 1)
    {
        // 根据不同的taskID使用不同的测试数据
        $testData = array(
            1 => array('status' => 'doing', 'comment' => '开始测试', 'realBegan' => '2024-01-01', 'uid' => 'uid123'),
            2 => array('status' => 'doing', 'comment' => '', 'realBegan' => '2024-01-15', 'uid' => 'uid456'),
            3 => array('status' => 'doing', 'comment' => '正式开始执行测试任务', 'realBegan' => '2024-02-01', 'uid' => ''),
            4 => array('status' => 'doing', 'comment' => '<p>带HTML标签的注释</p>', 'realBegan' => '2024-02-15', 'uid' => 'uid789'),
            0 => array('status' => 'doing', 'comment' => '无效ID测试', 'realBegan' => '2024-03-01', 'uid' => 'invalid'),
            999 => array('status' => 'doing', 'comment' => '不存在的测试单', 'realBegan' => '2024-12-31', 'uid' => 'test999')
        );

        $data = isset($testData[$taskID]) ? $testData[$taskID] : $testData[1];

        // 模拟表单数据
        $_POST = $data;

        $method = $this->testtaskZenTest->getMethod('buildTaskForStart');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$taskID));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForClose method.
     *
     * @param  int $taskID
     * @access public
     * @return object
     */
    public function buildTaskForCloseTest($taskID = 1)
    {
        // 根据不同的taskID使用不同的测试数据
        $testData = array(
            1 => array('status' => 'done', 'comment' => '测试完成', 'realFinishedDate' => '2024-01-31 18:00:00', 'mailto' => array('admin', 'user1'), 'uid' => 'uid123'),
            2 => array('status' => 'done', 'comment' => '', 'realFinishedDate' => '2024-01-31 15:30:00', 'mailto' => array(), 'uid' => 'uid456'),
            3 => array('status' => 'done', 'comment' => '测试任务已完成，所有用例执行完毕', 'realFinishedDate' => '2024-02-28 17:45:00', 'mailto' => array('qa', 'pm'), 'uid' => ''),
            4 => array('status' => 'done', 'comment' => '<p>带HTML标签的关闭注释</p>', 'realFinishedDate' => '2024-03-15 16:20:00', 'mailto' => array('test'), 'uid' => 'uid789'),
            0 => array('status' => 'done', 'comment' => '无效ID测试', 'realFinishedDate' => '2024-12-31 23:59:59', 'mailto' => array(), 'uid' => 'invalid'),
            999 => array('status' => 'done', 'comment' => '不存在的测试单', 'realFinishedDate' => '2024-12-31 12:00:00', 'mailto' => array('admin'), 'uid' => 'test999')
        );

        $data = isset($testData[$taskID]) ? $testData[$taskID] : $testData[1];

        // 模拟表单数据
        $_POST = $data;

        $method = $this->testtaskZenTest->getMethod('buildTaskForClose');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$taskID));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForActivate method.
     *
     * @param  int $taskID
     * @access public
     * @return object
     */
    public function buildTaskForActivateTest($taskID = 1)
    {
        // 根据不同的taskID使用不同的测试数据
        $testData = array(
            1 => array('status' => 'doing', 'comment' => '重新激活测试单', 'assignedTo' => 'admin', 'uid' => 'uid123'),
            2 => array('status' => 'doing', 'comment' => '', 'assignedTo' => 'user1', 'uid' => 'uid456'),
            3 => array('status' => 'doing', 'comment' => '测试单已激活，可以继续执行测试', 'assignedTo' => 'qa', 'uid' => ''),
            4 => array('status' => 'doing', 'comment' => '<p>带HTML标签的激活注释</p>', 'assignedTo' => 'tester', 'uid' => 'uid789'),
            0 => array('status' => 'doing', 'comment' => '无效ID测试', 'assignedTo' => 'admin', 'uid' => 'invalid'),
            999 => array('status' => 'doing', 'comment' => '不存在的测试单', 'assignedTo' => 'user999', 'uid' => 'test999')
        );

        $data = isset($testData[$taskID]) ? $testData[$taskID] : $testData[1];

        // 模拟表单数据
        $_POST = $data;

        $method = $this->testtaskZenTest->getMethod('buildTaskForActivate');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$taskID));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForBlock method.
     *
     * @param  int $taskID
     * @access public
     * @return object
     */
    public function buildTaskForBlockTest($taskID = 1)
    {
        // 根据不同的taskID使用不同的测试数据
        $testData = array(
            1 => array('status' => 'blocked', 'comment' => '测试被阻塞', 'blockedBy' => '系统故障', 'blockedReason' => 'bug导致无法继续测试', 'uid' => 'uid123'),
            2 => array('status' => 'blocked', 'comment' => '', 'blockedBy' => '', 'blockedReason' => '环境问题', 'uid' => 'uid456'),
            3 => array('status' => 'blocked', 'comment' => '测试单被阻塞，等待问题解决', 'blockedBy' => '第三方服务异常', 'blockedReason' => '依赖服务不可用', 'uid' => ''),
            4 => array('status' => 'blocked', 'comment' => '<p>带HTML标签的阻塞注释</p>', 'blockedBy' => 'bug #123', 'blockedReason' => '<script>alert("xss")</script>数据库连接失败', 'uid' => 'uid789'),
            0 => array('status' => 'blocked', 'comment' => '无效ID测试', 'blockedBy' => 'admin', 'blockedReason' => '无效测试单ID', 'uid' => 'invalid'),
            999 => array('status' => 'blocked', 'comment' => '不存在的测试单', 'blockedBy' => 'system', 'blockedReason' => '测试单不存在', 'uid' => 'test999')
        );

        $data = isset($testData[$taskID]) ? $testData[$taskID] : $testData[1];

        // 模拟表单数据
        $_POST = $data;

        $method = $this->testtaskZenTest->getMethod('buildTaskForBlock');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$taskID));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test buildTaskForImportUnitResult method.
     *
     * @param  int $productID
     * @access public
     * @return object
     */
    public function buildTaskForImportUnitResultTest($productID = 1)
    {
        // 根据不同的productID使用不同的测试数据
        $testData = array(
            1 => array(
                'execution' => 1,
                'build' => 1,
                'frame' => 'phpunit',
                'owner' => 'admin',
                'begin' => '2024-01-01',
                'end' => '2024-01-31',
                'name' => '单元测试导入任务',
                'pri' => 3,
                'desc' => '<p>从单元测试结果导入的测试任务</p>',
                'mailto' => 'admin,qa',
                'uid' => 'uid123'
            ),
            2 => array(
                'execution' => 0,
                'build' => 2,
                'frame' => 'jest',
                'owner' => 'user1',
                'begin' => '2024-02-01',
                'end' => '2024-02-28',
                'name' => 'Jest单元测试',
                'pri' => 2,
                'desc' => '',
                'mailto' => '',
                'uid' => 'uid456'
            ),
            5 => array(
                'execution' => 5,
                'build' => 3,
                'frame' => 'junit',
                'owner' => 'tester',
                'begin' => '2024-03-01',
                'end' => '2024-03-31',
                'name' => 'JUnit测试导入',
                'pri' => 1,
                'desc' => '<script>alert("xss")</script>Java单元测试结果导入',
                'mailto' => 'qa,dev',
                'uid' => ''
            ),
            0 => array(
                'execution' => 0,
                'build' => 1,
                'frame' => '',
                'owner' => '',
                'begin' => '2024-12-01',
                'end' => '2024-12-31',
                'name' => '无效产品ID测试',
                'pri' => 3,
                'desc' => '测试无效产品ID的情况',
                'mailto' => '',
                'uid' => 'invalid'
            ),
            999 => array(
                'execution' => 999,
                'build' => 999,
                'frame' => 'unknown',
                'owner' => 'unknown',
                'begin' => '2025-01-01',
                'end' => '2025-01-31',
                'name' => '不存在的产品测试',
                'pri' => 4,
                'desc' => '测试不存在的产品ID',
                'mailto' => 'admin',
                'uid' => 'test999'
            )
        );

        $data = isset($testData[$productID]) ? $testData[$productID] : $testData[1];

        // 保存原始$_POST和$_GET数据
        $originalPost = $_POST;
        $originalGet = $_GET;

        // 模拟表单数据
        $_POST = $data;
        $_GET = array();

        try {
            $testtaskZen = $this->testtaskZenTest->newInstance();
            $method = $this->testtaskZenTest->getMethod('buildTaskForImportUnitResult');
            $method->setAccessible(true);

            $result = $method->invoke($testtaskZen, (int)$productID);
            if(dao::isError()) return dao::getError();

            // 恢复原始数据
            $_POST = $originalPost;
            $_GET = $originalGet;

            return $result;
        } catch(Exception $e) {
            // 恢复原始数据
            $_POST = $originalPost;
            $_GET = $originalGet;

            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test checkTaskForEdit method.
     *
     * @param  object $task
     * @access public
     * @return bool|array
     */
    public function checkTaskForEditTest($task = null)
    {
        $method = $this->testtaskZenTest->getMethod('checkTaskForEdit');
        $method->setAccessible(true);

        try {
            $testtaskZen = $this->testtaskZenTest->newInstance();
            $result = $method->invoke($testtaskZen, $task);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}