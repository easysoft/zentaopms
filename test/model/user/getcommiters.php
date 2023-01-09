#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(30);

/**

title=测试 userModel->getCommiters();
cid=1
pid=1

获取源代码账号为user10的用户真实姓名 >> 测试10
获取系统中源代码账号不为空的用户数量 >> 30

*/

$user = new userTest();
$commiters = $user->getCommitersTest();

r($commiters)        && p('user10') && e('测试10'); //获取源代码账号为user10的用户真实姓名
r(count($commiters)) && p()         && e('30');     //获取系统中源代码账号不为空的用户数量
