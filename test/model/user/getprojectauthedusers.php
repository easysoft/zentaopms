#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('product')->gen(10);
zdTable('user')->gen(200);
su('admin');

/**

title=测试 userModel->getProjectAuthedUsers();
cid=1
pid=1

获取对ID为1的产品有权限的用户 >> test9
获取对ID为1的产品有权限的用户 >> admin
获取对ID为2的产品有权限的用户 >> astaw

*/

$user = new userTest();
$stakeholders['test9']  = 'test9';
$stakeholders['test10'] = 'test10';

$teams['pm7'] = 'pm7';
$teams['pm8'] = 'pm8';

$whiteList['user35'] = 'user35';
$whiteList['user35'] = 'user35';
$whiteList['astaw']  = 'astaw';

r($user->getProjectAuthedUsersTest(1, $stakeholders, $teams, $whiteList)) && p('test9') && e('test9');  //获取对ID为1的产品有权限的用户
r($user->getProjectAuthedUsersTest(1, $stakeholders, $teams, $whiteList)) && p('admin') && e('admin');  //获取对ID为1的产品有权限的用户
r($user->getProjectAuthedUsersTest(2, $stakeholders, $teams, $whiteList)) && p('astaw') && e('astaw');  //获取对ID为2的产品有权限的用户
