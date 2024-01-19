#!/usr/bin/env php
<?php

/**

title=测试 userModel->su();
cid=0

- 当前用户为 user1。属性account @user1
- 当前用户为 admin。属性account @admin
- 没有管理员用户。属性account @No admin users.

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(3);
zdTable('company')->gen(1);

su('user1');

global $app, $tester;

r($app->user) && p('account') && e('user1'); // 当前用户为 user1。

$userModel = $tester->loadModel('user');
$userModel->su();

r($app->user) && p('account') && e('admin'); // 当前用户为 admin。

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
