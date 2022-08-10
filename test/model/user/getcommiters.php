#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getPairs();
cid=1
pid=1

获取源代码账号为qd100的用户真实姓名 >> 高层管理100
获取系统中源代码账号不为空的用户数量 >> 1000

*/

$user = new userTest();
$commiters = $user->getCommitersTest();

r($commiters)        && p('qd100') && e('高层管理100'); //获取源代码账号为qd100的用户真实姓名
r(count($commiters)) && p()        && e('1000');        //获取系统中源代码账号不为空的用户数量