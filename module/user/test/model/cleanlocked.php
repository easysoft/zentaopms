#!/usr/bin/env php
<?php
/**
title=测试 userModel->cleanLocked();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$user = zdTable('user');
$user->fails->range('5');
$user->locked->range('`2023-01-10 14:34:12`');
$user->gen(2);

$userTest = new userTest();

$_SESSION['loginFails']        = 5;
$_SESSION['admin.loginLocked'] = '2023-01-10 14:34:12';
$_SESSION['user1.loginLocked'] = '2023-01-10 14:34:12';

$users = $userTest->getListTest();
r($users) && p('0:account,fails,locked') && e('admin,5,2023-01-10 14:34:12'); // admin 用户的失败次数为 5，锁定时间为 2023-01-10 14:34:12。
r($users) && p('1:account,fails,locked') && e('user1,5,2023-01-10 14:34:12'); // user1 用户的失败次数为 5，锁定时间为 2023-01-10 14:34:12。

$userTest->cleanLockedTest('admin'); // 清除 admin 用户的失败次数和锁定时间。
$userTest->cleanLockedTest('user1'); // 清除 user1 用户的失败次数和锁定时间。

$users = $userTest->getListTest();
r($users) && p('0:account,fails,locked') && e('admin,0,~~'); // admin 用户的失败次数为 0，锁定时间为空。
r($users) && p('1:account,fails,locked') && e('user1,0,~~'); // user1 用户的失败次数为 0，锁定时间为空。

r(isset($_SESSION['loginFails']))        && p() && e(0); // session 中记录的失败次数为空。
r(isset($_SESSION['admin.loginLocked'])) && p() && e(0); // session 中记录的 admin 用户锁定时间为空。
r(isset($_SESSION['user1.loginLocked'])) && p() && e(0); // session 中记录的 user1 用户锁定时间为空。
