#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processDynamicForAPI();
timeout=0
cid=0

- 步骤1：测试处理空动态数组 @0
- 步骤2：测试过滤用户动态 @0
- 步骤3：测试存在用户的动态数据 @1
- 步骤4：测试不存在用户的动态数据 @1
- 步骤5：测试混合动态数据过滤用户类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备用户数据
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,test1,test2,test3,test4,test5,test6');
$userTable->realname->range('管理员,用户1,用户2,用户3,测试1,测试2,测试3,测试4,测试5,测试6');
$userTable->password->range('123456');
$userTable->role->range('admin{1},dev{9}');
$userTable->gen(10);

// 准备动态数据
zenData('action')->gen(10);

su('admin');

$actionTest = new actionTest();

// 获取测试数据
$allDynamics = $tester->dao->select('*')->from(TABLE_ACTION)->fetchAll();

// 创建包含用户动态的测试数据
$userDynamic = new stdClass();
$userDynamic->id = 100;
$userDynamic->objectType = 'user';
$userDynamic->objectID = 1;
$userDynamic->actor = 'admin';
$userDynamic->action = 'login';

// 创建不存在用户的动态数据
$nonExistUserDynamic = new stdClass();
$nonExistUserDynamic->id = 200;
$nonExistUserDynamic->objectType = 'task';
$nonExistUserDynamic->objectID = 1;
$nonExistUserDynamic->actor = 'nonexistentuser';
$nonExistUserDynamic->action = 'created';

// 创建存在用户的动态数据
$existUserDynamic = new stdClass();
$existUserDynamic->id = 300;
$existUserDynamic->objectType = 'task';
$existUserDynamic->objectID = 2;
$existUserDynamic->actor = 'admin';
$existUserDynamic->action = 'created';

r(count($actionTest->processDynamicForAPITest(array()))) && p() && e('0'); // 步骤1：测试处理空动态数组
r(count($actionTest->processDynamicForAPITest(array($userDynamic)))) && p() && e('0'); // 步骤2：测试过滤用户动态
r(count($actionTest->processDynamicForAPITest(array($existUserDynamic)))) && p() && e('1'); // 步骤3：测试存在用户的动态数据
r(count($actionTest->processDynamicForAPITest(array($nonExistUserDynamic)))) && p() && e('1'); // 步骤4：测试不存在用户的动态数据
r(count($actionTest->processDynamicForAPITest(array($userDynamic, $existUserDynamic)))) && p() && e('1'); // 步骤5：测试混合动态数据过滤用户类型