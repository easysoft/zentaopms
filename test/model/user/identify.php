#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->identify();
cid=1
pid=1

用户名密码皆正确，返回验证登录的用户 >> admin
验证一个不存在的用户，返回空 >> 0
验证一个不传用户名和密码的用户，返回空 >> 0

*/

$user = new userTest();

r($user->identifyTest('admin', '78302615c8b79cac8df6d2607f8a83ee'))    && p('realname') && e('admin'); //用户名密码皆正确，返回验证登录的用户
r($user->identifyTest('asdjaf12', '78302615c8b79cac8df6d2607f8a83ee')) && p()           && e('0');     //验证一个不存在的用户，返回空
r($user->identifyTest('', ''))                                         && p()           && e('0');     //验证一个不传用户名和密码的用户，返回空