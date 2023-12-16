#!/usr/bin/env php
<?php
/**
title=测试 userModel::getById();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

su('admin');

global $config;

$config->user->lockMinutes = 10;

$userTest = new userTest();

$user1 = (object)array('account' => 'user1', 'ranzhi' => '');
$user2 = (object)array('account' => 'user2', 'ranzhi' => 'user2');
$user3 = (object)array('account' => 'user3', 'locked' => date('Y-m-d H:i:s', strtotime("-{$config->user->lockMinutes} minute")));
$user4 = (object)array('account' => 'user4', 'locked' => date('Y-m-d H:i:s'));
$user5 = (object)array('account' => 'admin');
$user6 = (object)array('account' => 'user6');

r($userTest->isClickableTest($user1, 'unbind')) && p() && e(0); // 判断是否可以对用户 user1 执行解除绑定操作，返回 false。
r($userTest->isClickableTest($user2, 'unbind')) && p() && e(1); // 判断是否可以对用户 user2 执行解除绑定操作，返回 true。

/* 测试第二个参数大小写有无影响。*/
r($userTest->isClickableTest($user1, 'UNBIND')) && p() && e(0); // 判断是否可以对用户 user1 执行解除绑定操作，返回 false。
r($userTest->isClickableTest($user2, 'UNBIND')) && p() && e(1); // 判断是否可以对用户 user2 执行解除绑定操作，返回 true。

r($userTest->isClickableTest($user3, 'unlock')) && p() && e(0); // 判断是否可以对用户 user3 执行解锁操作，返回 false。
r($userTest->isClickableTest($user4, 'unlock')) && p() && e(1); // 判断是否可以对用户 user4 执行解锁操作，返回 true。

r($userTest->isClickableTest($user5, 'delete')) && p() && e(0); // 判断是否可以对用户 admin 执行删除操作，返回 false。
r($userTest->isClickableTest($user6, 'delete')) && p() && e(1); // 判断是否可以对用户 user6 执行删除操作，返回 true。
