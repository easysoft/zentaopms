#!/usr/bin/env php
<?php
/**
title=测试 userModel->failPlus();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$user = zdTable('user');
$user->gen(1);

su('admin');

global $config, $tester;
$userModel = $tester->loadModel('user');

$config->user->failTimes = 3; // 设置最大失败次数为 3 。

$account1 = '$^%%&^%';  // 用户名不合法。
$account2 = 'notexist'; // 用户名合法但不存在。
$account3 = 'admin';    // 用户名合法且存在。

/* 清除 session 中的失败记录。*/
unset($_SESSION['loginFails']);
unset($_SESSION["{$account1}.loginLocked"]);
unset($_SESSION["{$account2}.loginLocked"]);
unset($_SESSION["{$account3}.loginLocked"]);

/* 测试用户名不合法的情况。*/
r($userModel->failPlus($account1))  && p() && e(0); // 用户名不合法时返回 0 。

/* 测试用户名合法但不存在的情况。*/
r($userModel->failPlus($account2))             && p() && e(0); // 用户名合法但不存在时返回 0 。
r($_SESSION['loginFails'])                     && p() && e(1); // 用户名合法但不存在时，session 中失败次数为 1 。
r(isset($_SESSION["{$account2}.loginLocked"])) && p() && e(0); // 用户名合法但不存在时，session 中失败次数未达到最大失败次数限制，session 中该账号锁定时间为空 。

/* 第一次测试用户名合法且存在的情况，未达到最大失败次数限制。*/
r($userModel->failPlus($account3))             && p() && e(1); // 用户名合法且存在时，第一次失败返回 1 。
r($_SESSION['loginFails'])                     && p() && e(2); // 用户名合法且存在时，session 中失败次数为 2 。
r(isset($_SESSION["{$account3}.loginLocked"])) && p() && e(0); // 用户名合法且存在时，session 中失败次数未达到最大失败次数限制，session 中该账号锁定时间为空 。
$user = $userModel->dao->select('fails, locked')->from(TABLE_USER)->where('account')->eq($account3)->fetch();
r($user)                                       && p('fails, locked') && e('1,` `'); // 用户名合法且存在时，$account3 账号未达到最大失败次数限制，数据库中失败次数为 1 ，账号未锁定。

/* 第二次测试用户名合法且存在的情况，未达到最大失败次数限制。*/
r($userModel->failPlus($account3))             && p() && e(2);                 // 用户名合法且存在时，第二次失败返回 2 。
r($_SESSION['loginFails'])                     && p() && e(3);                 // 用户名合法且存在时，session 中失败次数为 3 。
r($_SESSION["{$account3}.loginLocked"] == date('Y-m-d H:i:s')) && p() && e(1); // 用户名合法且存在时，session 中失败次数达到最大失败次数限制，session 中该账号锁定时间为当前时间 。
$user = $userModel->dao->select('fails, locked')->from(TABLE_USER)->where('account')->eq($account3)->fetch();
r($user)                                       && p('fails, locked') && e('2,` `'); // 用户名合法且存在时，$account3 账号未达到最大失败次数限制，数据库中失败次数为 2 ，账号未锁定。

/* 第三次测试用户名合法且存在的情况，达到最大失败次数限制。*/
r($userModel->failPlus($account3))             && p() && e(3);                 // 用户名合法且存在时，第三次失败返回 3 。
r($_SESSION['loginFails'])                     && p() && e(4);                 // 用户名合法且存在时，session 中失败次数为 4 。
r($_SESSION["{$account3}.loginLocked"] == date('Y-m-d H:i:s')) && p() && e(1); // 用户名合法且存在时，session 中失败次数达到最大失败次数限制，session 中该账号锁定时间为当前时间 。
$user = $userModel->dao->select('fails, locked')->from(TABLE_USER)->where('account')->eq($account3)->fetch();
r($user->fails)                                && p() && e(0); // 用户名合法且存在时，$account3 账号达到最大失败次数限制，数据库中失败次数归零。
r($user->locked == date('Y-m-d H:i:s'))        && p() && e(1); // 用户名合法且存在时，$account3 账号达到最大失败次数限制，账号被锁定，数据库中锁定时间为当前时间。
