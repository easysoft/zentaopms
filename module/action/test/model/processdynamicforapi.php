#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processDynamicForAPI();
timeout=0
cid=0

- 步骤1：测试处理空动态数组 @0
- 步骤2：测试过滤用户动态 @0
- 步骤3：测试存在用户的动态数据第actor条的account属性 @admin
- 步骤4：测试不存在用户的动态数据
 - 第actor条的id属性 @0
 - 第actor条的account属性 @nonexistentuser
- 步骤5：测试空数组的计数功能 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备用户数据
zenData('user')->gen(10);

// 准备动态数据
zenData('action')->gen(10);

su('admin');

$actionTest = new actionTest();

// 获取测试数据
$allDynamics = $tester->dao->select('*')->from(TABLE_ACTION)->fetchAll();

// 创建包含用户动态的测试数据
$userDynamic = new stdClass();
$userDynamic->id = '100';
$userDynamic->objectType = 'user';
$userDynamic->objectID = '1';
$userDynamic->actor = 'admin';
$userDynamic->action = 'login';

// 创建不存在用户的动态数据
$nonExistUserDynamic = new stdClass();
$nonExistUserDynamic->id = '200';
$nonExistUserDynamic->objectType = 'task';
$nonExistUserDynamic->objectID = '1';
$nonExistUserDynamic->actor = 'nonexistentuser';
$nonExistUserDynamic->action = 'created';

// 创建存在用户的动态数据
$existUserDynamic = new stdClass();
$existUserDynamic->id = '300';
$existUserDynamic->objectType = 'task';
$existUserDynamic->objectID = '2';
$existUserDynamic->actor = 'admin';
$existUserDynamic->action = 'created';

r($actionTest->processDynamicForAPITest(array())) && p() && e('0'); // 步骤1：测试处理空动态数组
r($actionTest->processDynamicForAPITest(array($userDynamic))) && p() && e('0'); // 步骤2：测试过滤用户动态
r($actionTest->processDynamicForAPITest(array($existUserDynamic))) && p('actor:account') && e('admin'); // 步骤3：测试存在用户的动态数据
r($actionTest->processDynamicForAPITest(array($nonExistUserDynamic))) && p('actor:id,account') && e('0,nonexistentuser'); // 步骤4：测试不存在用户的动态数据
r(count(array())) && p() && e('0'); // 步骤5：测试空数组的计数功能