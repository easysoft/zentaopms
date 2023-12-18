#!/usr/bin/env php
<?php
/**
title=测试 userModel->isLogon();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(2);

$userTest = new userTest();

$_SESSION['user'] = $userTest->getbyIdTest('admin');
r($userTest->isLogonTest()) && p() && e(1); // session 中用户为 admin 时获取用户的登录状态。

$_SESSION['user'] = $userTest->getbyIdTest('user1');
r($userTest->isLogonTest()) && p() && e(1); // session 中用户为 user1 时获取用户的登录状态。

$_SESSION['user'] = (object)array('account' => 'guest');
r($userTest->isLogonTest()) && p() && e(0); // session 中用户为 guest 时获取用户的登录状态。

$_SESSION['user'] = false;
r($userTest->isLogonTest()) && p() && e(0); // session 中用户为空时获取用户的登录状态。
