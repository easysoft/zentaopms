#!/usr/bin/env php
<?php
/**
title=测试 userModel->getListForGitLabAPI();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$user = zdTable('user');
$user->account->range('1-2')->prefix('user');
$user->avatar->range('`avatar`,``');
$user->gen(2);

$userTest = new userTest();
$accounts = array('user1', 'user2');

$users = $userTest->getListForGitLabAPITest(array());
r(count($users)) && p() && e(0); // 传入参数为空数组，返回空数组。

$users = $userTest->getListForGitLabAPITest(array('admin'));
r(count($users)) && p() && e(0); // 传入参数为非空数组，其中包含的账号在数据库中不存在，返回空数组。

$users = $userTest->getListForGitLabAPITest($accounts);
r(count($users)) && p() && e(2); // 传入参数为非空数组，包含 2 个账号，返回数组包含 2 个用户。

$user1 = $userTest->getByIdTest('user1');
r($users[0]->avatar == $tester->getSysURL() . $user1->avatar)                                   && p() && e(1); // 传入参数为非空数组，包含 2 个账号，返回数组包含 2 个用户，其中第 1 个用户有头像。
r($users[1]->avatar == "https://www.gravatar.com/avatar/" . md5('user2') . "?d=identicon&s=80") && p() && e(1); // 传入参数为非空数组，包含 2 个账号，返回数组包含 2 个用户，其中第 2 个用户无头像。
