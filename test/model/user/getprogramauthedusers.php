#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('project')->gen(10, 'program');
zdTable('user')->gen(200);
su('admin');

/**

title=测试 userModel->getProgramAuthedUsers();
cid=1
pid=1

获取对ID为1的项目集有权限的用户数量 >> 7
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

$admins['test20'] = 'test20';
$admins['test21'] = 'test21';

r(count($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))) && p()         && e('7');     //获取对ID为1的项目集有权限的用户数量
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))        && p('test9')  && e('test9'); //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))        && p('admin')  && e('admin'); //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(2, $stakeholders, $whiteList, array()))        && p('astaw')  && e('astaw'); //获取对ID为2的项目集有权限的用户
r($user->getProgramAuthedUsersTest(2, $stakeholders, $whiteList, $admins))        && p('test21') && e('test21'); //获取对ID为2的项目集有权限的用户
