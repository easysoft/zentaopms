#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkLocked();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

global $config;
if(empty($config->user)) $config->user = new stdclass();
$config->user->lockMinutes = 10;

$now    = date('Y-m-d H:i:s', time());
$locked = date('Y-m-d H:i:s', time() - ($config->user->lockMinutes + 1) * 60); // 锁定时间超过锁定时长限制。

$_SESSION['admin.loginLocked'] = $now; // 把 admin 用户锁定时间存入 session，以供步骤 1 使用。

$table = zdTable('user');
$table->account->range('admin,user1,user2');
$table->locked->range("`{$locked}`,`{$now}`,`{$locked}`");
$table->gen(3);

$userTest = new userTest();

r($userTest->checkLockedTest('admin')) && p() && e(1); // admin 用户被锁定，返回 true。
r($userTest->checkLockedTest('user1')) && p() && e(1); // user1 用户锁定时间未超过锁定时长限制，返回 true。
r($userTest->checkLockedTest('user2')) && p() && e(0); // user3 用户锁定时间超过锁定时长限制，返回 false。
r($userTest->checkLockedTest('user3')) && p() && e(0); // user2 用户不存在，返回 false。

/* 重新生成一条锁定时间为 null 的数据。*/
$table = zdTable('user');
$table->account->range('user4');
$table->locked->setNULL();
$table->gen(1);

r($userTest->checkLockedTest('user4')) && p() && e(0); // user4 用户锁定时间为 null，返回 false。
