#!/usr/bin/env php
<?php

/**

title=测试 userModel::isLogon();
timeout=0
cid=19646

- 步骤1：正常登录用户admin的状态检查 @1
- 步骤2：正常登录用户user1的状态检查 @1
- 步骤3：guest用户的登录状态检查 @0
- 步骤4：session中用户为false时的状态检查 @0
- 步骤5：用户对象存在但account为空的状态检查 @0
- 步骤6：用户对象存在但account为null的状态检查 @0
- 步骤7：session中完全没有user时的状态检查 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

zenData('user')->gen(2);

$userTest = new userTest();

$_SESSION['user'] = $userTest->getbyIdTest('admin');
r($userTest->isLogonTest()) && p() && e(1); // 步骤1：正常登录用户admin的状态检查

$_SESSION['user'] = $userTest->getbyIdTest('user1');
r($userTest->isLogonTest()) && p() && e(1); // 步骤2：正常登录用户user1的状态检查

$_SESSION['user'] = (object)array('account' => 'guest');
r($userTest->isLogonTest()) && p() && e(0); // 步骤3：guest用户的登录状态检查

$_SESSION['user'] = false;
r($userTest->isLogonTest()) && p() && e(0); // 步骤4：session中用户为false时的状态检查

$_SESSION['user'] = (object)array('account' => '');
r($userTest->isLogonTest()) && p() && e(0); // 步骤5：用户对象存在但account为空的状态检查

$_SESSION['user'] = (object)array('account' => null);
r($userTest->isLogonTest()) && p() && e(0); // 步骤6：用户对象存在但account为null的状态检查

unset($_SESSION['user']);
r($userTest->isLogonTest()) && p() && e(0); // 步骤7：session中完全没有user时的状态检查