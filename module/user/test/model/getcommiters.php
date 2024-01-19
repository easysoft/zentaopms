#!/usr/bin/env php
<?php

/**

title=测试 userModel->getCommiters();
cid=0

- 获取源代码账号为user10的用户真实姓名属性user10 @用户10
- 获取系统中源代码账号不为空的用户数量 @30

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

zdTable('user')->gen(30);

$user = new userTest();
$commiters = $user->getCommitersTest();

r($commiters)        && p('user10') && e('用户10'); //获取源代码账号为user10的用户真实姓名
r(count($commiters)) && p()         && e('30');     //获取系统中源代码账号不为空的用户数量
