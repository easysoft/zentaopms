#!/usr/bin/env php
<?php
/**
title=测试 userModel->getPairs();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->email->range('account1!qq.com,account2!qq.com,account3!qq.com,account4!qq.com,account5!qq.com');
$user->deleted->range('0{5}');
$user->gen(5);

$userTest = new userTest();
$result   = $userTest->getRealNameAndEmailsTest(array());
r(count($result)) && p() && e(0); // 参数为空数组，返回空数组。

$accounts = array('account1', 'account2', 'account3');
$result   = $userTest->getRealNameAndEmailsTest($accounts);
r(count($result)) && p() && e('3'); // 参数包含 3个账号，返回 3个账号的邮箱和真实姓名。

$accounts = array('account1', 'account2', 'account3', 'account4', 'account5');
$result   = $userTest->getRealNameAndEmailsTest($accounts);
r(count($result)) && p() && e('5'); // 参数包含 5个账号，返回 5个账号的邮箱和真实姓名。

$accounts = array('account1', 'account2', 'account3', 'account4', 'account5', 'account6');
$result   = $userTest->getRealNameAndEmailsTest($accounts);
r(count($result)) && p() && e('5'); // 参数包含 6个账号，返回 5个账号的邮箱和真实姓名。

r($result['account1']) && p('email,realname') && e('account1!qq.com,用户名1'); //获取 account1 的邮箱和真实姓名
r($result['account2']) && p('email,realname') && e('account2!qq.com,用户名2'); //获取 account2 的邮箱和真实姓名
