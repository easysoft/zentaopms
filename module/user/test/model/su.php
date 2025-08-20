#!/usr/bin/env php
<?php

/**

title=测试 userModel->su();
timeout=0
cid=0

- 当前用户为 user1。属性account @user1
- 当前用户为 admin。属性account @admin
- 当前用户为 user2。属性account @user2
- 当前用户为 user3。属性account @user3
- 没有管理员用户。属性account @No admin users.

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(4);
zenData('company')->gen(1);

su('user1');

global $app, $tester;

r($app->user) && p('account') && e('user1'); // 当前用户为 user1。

$userModel = $tester->loadModel('user');
$userModel->su();

r($app->user) && p('account') && e('admin'); // 当前用户为 admin。

$userModel->dao->update(TABLE_COMPANY)->set('admins')->eq('user2')->exec();
$userModel->su();

r($app->user) && p('account') && e('user2'); // 当前用户为 user2。

$userModel->dao->update(TABLE_COMPANY)->set('admins')->eq('user3')->exec();
$userModel->su();

r($app->user) && p('account') && e('user3'); // 当前用户为 user3。

$user = new stdclass();
$userModel->dao->update(TABLE_COMPANY)->set('admins')->eq('')->exec();
try
{
    $userModel->su();
}
catch(EndResponseException $e)
{
    $user->account = $e->getContent();
}
r($user) && p('account') && e('No admin users.'); // 没有管理员用户。