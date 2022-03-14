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

$accounts = array('!asd12d', '中文用户名', 'user10', 'dev33', 'cccfff');
$user  = new userTest();

r($user->getUserRolesTest($accounts, true))         && p('dev33') && e('dev');  //获取用户名和职位Key的键值对
r($user->getUserRolesTest($accounts, false))        && p('dev33') && e('研发'); //获取用户名和职位Value的键值对
r(count($user->getUserRolesTest($accounts, true)))  && p()        && e(2);      //从指定accounts中获取到的键值对数量
r(count($user->getUserRolesTest($accounts, false))) && p()        && e(2);      //从指定accounts中获取到的键值对数量
