#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processDynamicForAPI();
timeout=0
cid=14922

- 步骤1：测试处理空动态数组 @0
- 步骤2：测试过滤用户动态 @0
- 步骤3：测试存在用户的动态数据 @1
- 步骤4：测试不存在用户的动态数据 @1
- 步骤5：测试混合动态数据过滤用户类型 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$actionTest = new actionTest();

// 创建包含用户动态的测试数据（应该被过滤）
$userDynamic = new stdClass();
$userDynamic->id         = 100;
$userDynamic->objectType = 'user';
$userDynamic->objectID   = 1;
$userDynamic->actor      = 'admin';
$userDynamic->action     = 'login';

// 创建不存在用户的动态数据
$nonExistUserDynamic = new stdClass();
$nonExistUserDynamic->id         = 200;
$nonExistUserDynamic->objectType = 'task';
$nonExistUserDynamic->objectID   = 1;
$nonExistUserDynamic->actor      = 'nonexistentuser';
$nonExistUserDynamic->action     = 'created';

// 创建存在用户的动态数据
$existUserDynamic = new stdClass();
$existUserDynamic->id         = 300;
$existUserDynamic->objectType = 'task';
$existUserDynamic->objectID   = 2;
$existUserDynamic->actor      = 'admin';
$existUserDynamic->action     = 'created';

// 创建另一个存在用户的动态数据
$anotherUserDynamic = new stdClass();
$anotherUserDynamic->id         = 400;
$anotherUserDynamic->objectType = 'story';
$anotherUserDynamic->objectID   = 1;
$anotherUserDynamic->actor      = 'user1';
$anotherUserDynamic->action     = 'opened';

// 创建第5个测试数据：检查不存在用户的actor对象结构
$extraUserDynamic = new stdClass();
$extraUserDynamic->id         = 500;
$extraUserDynamic->objectType = 'bug';
$extraUserDynamic->objectID   = 1;
$extraUserDynamic->actor      = 'user2';
$extraUserDynamic->action     = 'resolved';

r(count($actionTest->processDynamicForAPITest(array())))                                                     && p() && e('0'); // 步骤1：测试处理空动态数组
r(count($actionTest->processDynamicForAPITest(array(clone $userDynamic))))                                         && p() && e('0'); // 步骤2：测试过滤用户动态
r(count($actionTest->processDynamicForAPITest(array(clone $existUserDynamic))))                                    && p() && e('1'); // 步骤3：测试存在用户的动态数据
r(count($actionTest->processDynamicForAPITest(array(clone $nonExistUserDynamic))))                                 && p() && e('1'); // 步骤4：测试不存在用户的动态数据
r(count($actionTest->processDynamicForAPITest(array($userDynamic, $existUserDynamic, $anotherUserDynamic)))) && p() && e('2'); // 步骤5：测试混合动态数据过滤用户类型
