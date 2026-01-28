#!/usr/bin/env php
<?php

/**

title=测试 userModel->cleanLocked();
cid=19593

- admin 用户的失败次数为 5，锁定时间为 2023-01-10 14:34:12。
 - 第0条的account属性 @admin
 - 第0条的fails属性 @5
 - 第0条的locked属性 @2023-01-10 14:34:12
- user1 用户的失败次数为 5，锁定时间为 2023-01-10 14:34:12。
 - 第1条的account属性 @user1
 - 第1条的fails属性 @5
 - 第1条的locked属性 @2023-01-10 14:34:12
- admin 用户的失败次数为 0，锁定时间为空。
 - 第0条的account属性 @admin
 - 第0条的fails属性 @0
 - 第0条的locked属性 @~~
- user1 用户的失败次数为 0，锁定时间为空。
 - 第1条的account属性 @user1
 - 第1条的fails属性 @0
 - 第1条的locked属性 @~~
- session 中记录的失败次数为空。 @0
- session 中记录的 admin 用户锁定时间为空。 @0
- session 中记录的 user1 用户锁定时间为空。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->fails->range('5');
$user->locked->range('`2023-01-10 14:34:12`');
$user->gen(2);

$userTest = new userModelTest();

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
