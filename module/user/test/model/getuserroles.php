#!/usr/bin/env php
<?php
/**
title=测试 userModel->getUserRoles();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$user = zdTable('user');
$user->role->range('dev,qa,pm,po,td,pd,qd,top,others,role');
$user->gen(10);

su('admin');

$userTest = new userTest();

r(count($userTest->getUserRolesTest('')))              && p() && e(0); // 参数为空字符串返回空数组。
r(count($userTest->getUserRolesTest(array())))         && p() && e(0); // 参数为空数组返回空数组。
r(count($userTest->getUserRolesTest('user10')))        && p() && e(0); // 参数为字符串，账号不存在，返回空数组。
r(count($userTest->getUserRolesTest(array('user10')))) && p() && e(0); // 参数为数组，账号不存在，返回空数组。

/* 参数为字符串。*/
$users = $userTest->getUserRolesTest('admin, user1, user2, user3, user4, user5, user6, user7, user8, user9');
r(count($users)) && p()         && e(10);         // 参数包含 10 个账号，返回 10 个键值对。
r($users)        && p('admin')  && e('研发');     // admin 账号的角色是 研发。
r($users)        && p('user1')  && e('测试');     // user1 账号的角色是 测试。
r($users)        && p('user2')  && e('项目经理'); // user2 账号的角色是 项目经理。
r($users)        && p('user3')  && e('产品经理'); // user3 账号的角色是 产品经理。
r($users)        && p('user4')  && e('研发主管'); // user4 账号的角色是 研发主管。
r($users)        && p('user5')  && e('产品主管'); // user5 账号的角色是 产品主管。
r($users)        && p('user6')  && e('测试主管'); // user6 账号的角色是 测试主管。
r($users)        && p('user7')  && e('高层管理'); // user7 账号的角色是 高层管理。
r($users)        && p('user8')  && e('其他');     // user8 账号的角色是 其他。
r($users)        && p('user9')  && e('role');     // user9 账号的角色是 role。
r($users)        && p('user10') && e('``');       // user10 账号不存在。

/* 参数为数组。*/
$users = $userTest->getUserRolesTest(array('admin', 'user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7', 'user8', 'user9'));
r(count($users)) && p()         && e(10);         // 参数包含 10 个账号，返回 10 个键值对。
r($users)        && p('admin')  && e('研发');     // admin 账号的角色是 研发。
r($users)        && p('user1')  && e('测试');     // user1 账号的角色是 测试。
r($users)        && p('user2')  && e('项目经理'); // user2 账号的角色是 项目经理。
r($users)        && p('user3')  && e('产品经理'); // user3 账号的角色是 产品经理。
r($users)        && p('user4')  && e('研发主管'); // user4 账号的角色是 研发主管。
r($users)        && p('user5')  && e('产品主管'); // user5 账号的角色是 产品主管。
r($users)        && p('user6')  && e('测试主管'); // user6 账号的角色是 测试主管。
r($users)        && p('user7')  && e('高层管理'); // user7 账号的角色是 高层管理。
r($users)        && p('user8')  && e('其他');     // user8 账号的角色是 其他。
r($users)        && p('user9')  && e('role');     // user9 账号的角色是 role。
r($users)        && p('user10') && e('``');       // user10 账号不存在。

/* 参数为字符串。*/
$users = $userTest->getUserRolesTest('admin, user1, user10');
r(count($users)) && p() && e(2); // 参数包含 3 个账号，返回 2 个键值对。

/* 参数为数组。*/
$users = $userTest->getUserRolesTest(array('admin', 'user1', 'user10'));
r(count($users)) && p() && e(2); // 参数包含 3 个账号，返回 2 个键值对。
