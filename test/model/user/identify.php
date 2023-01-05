#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1-100');
$user->account->range('1-100')->prefix('dev');
$user->password->range('f8e41d6c31824c01e5d67c61a8ae49e9,e10adc3949ba59abbe56e057f20f883e');
$user->realname->range('1-100')->prefix("开发");
$user->gen(50);

/**

title=测试 userModel->identify();
cid=1
pid=1

用户名密码皆正确，返回验证登录的用户真实姓名 >> 开发1
验证一个不存在的用户，返回空 >> 0
验证一个不传用户名和密码的用户，返回空 >> 0

*/

$user = new userTest();

r($user->identifyTest('dev1', 'f8e41d6c31824c01e5d67c61a8ae49e9'))     && p('realname') && e('开发1'); //用户名密码皆正确，返回验证登录的用户真实姓名
r($user->identifyTest('asdjaf12', '78302615c8b79cac8df6d2607f8a83ee')) && p()           && e('0');     //验证一个不存在的用户，返回空
r($user->identifyTest('', ''))                                         && p()           && e('0');     //验证一个不传用户名和密码的用户，返回空
