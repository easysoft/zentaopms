#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->isLogon();
cid=1
pid=1

获取admin用户的登录状态 >> 1
获取游客的登录状态 >> 0

*/

$user = new userTest();

$_SESSION['user'] = new stdclass();
$_SESSION['user']->account  = 'admin';
$_SESSION['user']->password = 'a0933c1218a4e745bacdcf572b10eba7';
$admin = $user->isLogonTest();

$_SESSION['user']->account  = 'guest';
$_SESSION['user']->password = '';
$guest = $user->isLogonTest();

r($admin) && p() && e('1'); //获取admin用户的登录状态
r($guest) && p() && e('0'); //获取游客的登录状态
