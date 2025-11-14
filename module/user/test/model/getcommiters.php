#!/usr/bin/env php
<?php

/**

title=测试 userModel->getCommiters();
timeout=0
cid=19607

- 获取源代码账号为user1的用户真实姓名属性user1 @用户1
- 获取源代码账号为user2的用户真实姓名属性user2 @用户2
- 获取源代码账号为user10的用户真实姓名属性user10 @用户10
- 获取源代码账号为admin的用户真实姓名属性admin @admin
- 获取系统中源代码账号不为空的用户数量 @30

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';
su('admin');

zenData('user')->gen(30);

$user = new userTest();
$commiters = $user->getCommitersTest();

r($commiters)        && p('user1')  && e('用户1'); //获取源代码账号为user1的用户真实姓名
r($commiters)        && p('user2')  && e('用户2'); //获取源代码账号为user2的用户真实姓名
r($commiters)        && p('user10') && e('用户10'); //获取源代码账号为user10的用户真实姓名
r($commiters)        && p('admin')  && e('admin'); //获取源代码账号为admin的用户真实姓名
r(count($commiters)) && p()         && e('30');    //获取系统中源代码账号不为空的用户数量