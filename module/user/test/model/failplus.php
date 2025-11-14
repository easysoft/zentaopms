#!/usr/bin/env php
<?php

/**

title=测试 userModel::failPlus();
timeout=0
cid=19601

- 执行userTest模块的failPlusTest方法，参数是'@#$invalid'  @0
- 执行userTest模块的failPlusTest方法，参数是'notexist'  @0
- 执行userTest模块的failPlusTest方法，参数是'testuser'  @1
- 执行userTest模块的failPlusTest方法，参数是'testuser'  @2
- 执行userTest模块的failPlusTest方法，参数是'testuser'  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$user = zenData('user');
$user->account->range('testuser');
$user->password->range('098f6bcd4621d373cade4e832627b4f6');
$user->realname->range('Test User');
$user->fails->range('0');
$user->locked->range('0000-00-00 00:00:00');
$user->dept->range('1');
$user->role->range('dev');
$user->gen(1);

su('admin');

global $config, $tester;
$config->user->failTimes = 3;

$userTest = new userTest();

// 清理session状态
unset($_SESSION['loginFails']);

r($userTest->failPlusTest('@#$invalid')) && p() && e(0);
r($userTest->failPlusTest('notexist')) && p() && e(0);
r($userTest->failPlusTest('testuser')) && p() && e(1);
r($userTest->failPlusTest('testuser')) && p() && e(2);
r($userTest->failPlusTest('testuser')) && p() && e(3);