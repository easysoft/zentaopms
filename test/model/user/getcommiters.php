#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->getPairs();
cid=1
pid=1

查找带有Closed用户的外部用户键值对 >> Closed
查找带有首字母的外部用户键值对 >> O:用户1
查找不带首字母的外部用户键值对 >> 用户1
按照给定的用户名查找用户键值对，返回值中不带有Closed用户 >> 1
按照给定的用户名查找用户键值对，返回值中带有Closed用户 >> 2
使用limit参数来限制最多获取2个用户键值对 >> 2
使用limit参数来限制最多获取50个用户键值对 >> 50
使用pofirst参数来将系统中的产品经理用户放到返回值前列 >> P:测试主管58
使用pmfirst参数来将系统中的项目经理用户放到返回值前列 >> O:其他100

*/

$user = new userTest();
$commiters = $user->getCommitersTest();

r($commiters)        && p('qd100') && e('高层管理100'); //获取源代码账号为qd100的用户真实姓名
r(count($commiters)) && p()        && e('1000');        //获取系统中源代码账号不为空的用户数量
