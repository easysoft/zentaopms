#!/usr/bin/env php
<?php
/**
title=测试 userModel->setCurrentUserFirst();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $app, $tester;
$userModel = $tester->loadModel('user');
$userList  = array
(
    'user1' => (object)array('account' => 'user1'),
    'user2' => (object)array('account' => 'user2'),
    'user3' => (object)array('account' => 'user3'),
);

$users = $userModel->setCurrentUserFirst(array());
r(count($users)) && p() && e(0); // 传入参数为空数组，返回传入参数。

/* 更改当前用户。*/
$app->user = (object)array('account' => '');
$users = $userModel->setCurrentUserFirst($userList);
r(count($users)) && p()          && e(3);       // 传入参数为非空数组，当前用户账号为空，返回传入参数。
r(reset($users)) && p('account') && e('user1'); // 返回数组第 1 个元素为传入参数的第 1 个元素。

/* 更改当前用户。*/
$app->user = (object)array('account' => 'user4');
$users = $userModel->setCurrentUserFirst($userList);
r(count($users)) && p()          && e(3);       // 传入参数为非空数组，当前用户账号非空但在参数中不存在，返回传入参数。
r(reset($users)) && p('account') && e('user1'); // 返回数组第 1 个元素为传入参数的第 1 个元素。

/* 更改当前用户。*/
$app->user = (object)array('account' => 'user3');
$users = $userModel->setCurrentUserFirst($userList);
r(count($users)) && p()          && e(3);       // 传入参数为非空数组，当前用户账号非空且在参数中存在，返回数组包含 3 个用户。
r(reset($users)) && p('account') && e('user3'); // 返回数组第 1 个元素为当前用户。

/* 更改当前用户。*/
$app->user = (object)array('account' => 'user2');
$users = $userModel->setCurrentUserFirst($userList);
r(count($users)) && p()          && e(3);       // 传入参数为非空数组，当前用户账号非空且在参数中存在，返回数组包含 3 个用户。
r(reset($users)) && p('account') && e('user2'); // 返回数组第 1 个元素为当前用户。
