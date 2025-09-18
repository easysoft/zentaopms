#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildUserForCreate();
timeout=0
cid=0

- 执行ssoTest模块的buildUserForCreateTest方法 属性ranzhi @testuser
- 执行ssoTest模块的buildUserForCreateTest方法 属性ranzhi @~~
- 执行ssoTest模块的buildUserForCreateTest方法 属性ranzhi @~~
- 执行ssoTest模块的buildUserForCreateTest方法 属性ranzhi @user_test_email
- 执行$result->account) && isset($result->realname) && isset($result->email) && isset($result->gender) && isset($result->ranzhi @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

su('admin');

$ssoTest = new ssoTest();

$_POST['account'] = 'testuser';
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('testuser');

$_POST = array();
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('~~');

$_POST['account'] = '';
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('~~');

$_POST['account'] = 'user_test_email';
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('user_test_email');

$_POST['account'] = 'normaluser';
$result = $ssoTest->buildUserForCreateTest();
r(isset($result->account) && isset($result->realname) && isset($result->email) && isset($result->gender) && isset($result->ranzhi)) && p() && e(1);