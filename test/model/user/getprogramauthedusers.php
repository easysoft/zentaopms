#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getProgramAuthedUsers();
cid=1
pid=1

获取对ID为1的项目集有权限的用户数量 >> 6
获取对ID为1的项目集有权限的用户 >> test9
获取对ID为1的项目集有权限的用户 >> admin
获取对ID为2的项目集有权限的用户 >> astaw

*/

$user = new userTest();
$stakeholders['test9']  = 'test9';
$stakeholders['test10'] = 'test10';

$whiteList['user35'] = 'user35';
$whiteList['user35'] = 'user35';
$whiteList['astaw']  = 'astaw';

r(count($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList))) && p()        && e('6');     //获取对ID为1的项目集有权限的用户数量
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList))        && p('test9') && e('test9'); //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList))        && p('admin') && e('admin'); //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(2, $stakeholders, $whiteList))        && p('astaw') && e('astaw'); //获取对ID为2的项目集有权限的用户