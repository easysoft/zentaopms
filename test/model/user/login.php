#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->login();
cid=1
pid=1

使用admin登录，返回此用户为超级管理员 >> 1
使用Admin登录，返回权限列表的数量 >> 3
使用空账号登录，返回权限列表数量为0 >> 0

*/

$userClass = new userTest();

$admin = new stdclass();
$admin->id      = 1;
$admin->account = 'admin';
$admin          = $userClass->loginTest($admin);

$emptyAccount = new stdclass();
$emptyAccount->id      = 0;
$emptyAccount->account = '';
$emptyAccount          = $userClass->loginTest($emptyAccount);

r($admin)                       && p('admin') && e('1'); //使用admin登录，返回此用户为超级管理员
r(count($admin->rights))        && p()        && e('3'); //使用Admin登录，返回权限列表的数量
r(count($emptyAccount->group))  && p()        && e('0'); //使用空账号登录，返回权限列表数量为0