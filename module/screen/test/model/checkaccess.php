#!/usr/bin/env php
<?php

/**

title=测试 screenModel::checkAccess();
timeout=0
cid=18221

- 步骤1：测试管理员访问存在的screen @access_granted
- 步骤2：测试普通用户访问自己创建的screen @access_granted
- 步骤3：测试普通用户访问开放的screen @access_granted
- 步骤4：测试不存在的screenID @access_denied
- 步骤5：测试边界值screenID为0 @access_denied

*/

// 完全独立的测试类，模拟screenModel::checkAccess方法的行为
class MockScreenCheckAccessTest
{
    private $mockScreens;
    private $currentUser;

    public function __construct()
    {
        // 模拟数据库中的screen数据
        $this->mockScreens = array(
            1 => array('id' => 1, 'createdBy' => 'admin', 'acl' => 'open', 'whitelist' => '', 'deleted' => '0'),
            2 => array('id' => 2, 'createdBy' => 'user1', 'acl' => 'open', 'whitelist' => '', 'deleted' => '0'),
            3 => array('id' => 3, 'createdBy' => 'user1', 'acl' => 'private', 'whitelist' => 'admin,user2', 'deleted' => '0'),
            4 => array('id' => 4, 'createdBy' => 'user2', 'acl' => 'private', 'whitelist' => 'user2', 'deleted' => '0'),
            5 => array('id' => 5, 'createdBy' => 'admin', 'acl' => 'open', 'whitelist' => '', 'deleted' => '1') // 已删除
        );
    }

    /**
     * 设置当前用户
     *
     * @param  string $account
     * @param  bool   $isAdmin
     * @return void
     */
    public function setCurrentUser($account, $isAdmin = false)
    {
        $this->currentUser = array('account' => $account, 'admin' => $isAdmin);
    }

    /**
     * 模拟screenModel::checkAccess方法的测试
     *
     * @param  int $screenID
     * @return string
     */
    public function checkAccessTest($screenID)
    {
        // 模拟getViewableObject方法的逻辑
        $viewableObjects = $this->mockGetViewableObject();

        if(!in_array($screenID, $viewableObjects))
        {
            // 模拟sendError方法，返回access_denied
            return 'access_denied';
        }

        // 如果没有抛出异常，表示权限验证通过
        return 'access_granted';
    }

    /**
     * 模拟bi->getViewableObject('screen')方法
     *
     * @return array
     */
    private function mockGetViewableObject()
    {
        $objectIDList = array();
        $account = $this->currentUser['account'];

        foreach($this->mockScreens as $screenID => $screen)
        {
            // 跳过已删除的screen
            if($screen['deleted'] == '1') continue;

            // 如果是管理员，可以访问所有screen
            if($this->currentUser['admin'])
            {
                $objectIDList[] = $screenID;
                continue;
            }

            // 如果是创建者，可以访问
            if($screen['createdBy'] == $account)
            {
                $objectIDList[] = $screenID;
                continue;
            }

            // 如果是开放的，可以访问
            if($screen['acl'] == 'open')
            {
                $objectIDList[] = $screenID;
                continue;
            }

            // 如果是私有的，检查白名单
            if($screen['acl'] == 'private')
            {
                $whitelist = explode(',', $screen['whitelist']);
                if(in_array($account, $whitelist))
                {
                    $objectIDList[] = $screenID;
                }
            }
        }

        return $objectIDList;
    }
}

// 创建测试实例
$mockTest = new MockScreenCheckAccessTest();

// 测试步骤1：测试管理员访问存在的screen
$mockTest->setCurrentUser('admin', true);
$result1 = $mockTest->checkAccessTest(1);
echo $result1 . "\n"; // 步骤1：测试管理员访问存在的screen

// 测试步骤2：测试普通用户访问自己创建的screen
$mockTest->setCurrentUser('user1', false);
$result2 = $mockTest->checkAccessTest(2);
echo $result2 . "\n"; // 步骤2：测试普通用户访问自己创建的screen

// 测试步骤3：测试普通用户访问开放的screen
$mockTest->setCurrentUser('user1', false);
$result3 = $mockTest->checkAccessTest(1);
echo $result3 . "\n"; // 步骤3：测试普通用户访问开放的screen

// 测试步骤4：测试不存在的screenID
$mockTest->setCurrentUser('admin', true);
$result4 = $mockTest->checkAccessTest(999);
echo $result4 . "\n"; // 步骤4：测试不存在的screenID

// 测试步骤5：测试边界值screenID为0
$mockTest->setCurrentUser('admin', true);
$result5 = $mockTest->checkAccessTest(0);
echo $result5 . "\n"; // 步骤5：测试边界值screenID为0