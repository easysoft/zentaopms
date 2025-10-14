#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::idenfyFromSSO();
timeout=0
cid=0

- 步骤1：status失败逻辑 @1
- 步骤2：md5验证失败逻辑 @1
- 步骤3：auth验证失败逻辑 @1
- 步骤4：用户未找到逻辑 @1
- 步骤5：正常用户查找逻辑 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('123456{10}');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->role->range('admin{1},dev{9}');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com,user5@test.com,user6@test.com,user7@test.com,user8@test.com,user9@test.com');
$user->visits->range('0-100');
$user->ip->range('127.0.0.1{10}');
$user->last->range('1672531200-1703980799');
$user->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$ssoTest = new ssoTest();

// 5. 测试步骤：验证idenfyFromSSO方法的各个逻辑组件
// 测试步骤1：status不为success时的逻辑
$_GET['status'] = 'fail';
r($ssoTest->idenfyFromSSOTest('status_fail')) && p() && e('1'); // 步骤1：status失败逻辑

// 测试步骤2：md5验证失败时的逻辑
$_GET['status'] = 'success';
$_GET['data'] = base64_encode('test');
$_GET['md5'] = 'wrong_md5';
r($ssoTest->idenfyFromSSOTest('md5_fail')) && p() && e('1'); // 步骤2：md5验证失败逻辑

// 测试步骤3：auth验证失败时的逻辑
r($ssoTest->idenfyFromSSOTest('auth_fail')) && p() && e('1'); // 步骤3：auth验证失败逻辑

// 测试步骤4：用户未绑定时的逻辑
r($ssoTest->idenfyFromSSOTest('user_not_found')) && p() && e('1'); // 步骤4：用户未找到逻辑

// 测试步骤5：正常用户查找的逻辑
r($ssoTest->idenfyFromSSOTest('valid_flow')) && p() && e('1'); // 步骤5：正常用户查找逻辑