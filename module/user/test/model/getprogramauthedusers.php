#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$project = zdTable('project');
$project->type->range('program');
$project->PM->range('user1');
$project->gen(10);
zdTable('user')->gen(200);
su('admin');

/**

title=测试 userModel->getProgramAuthedUsers();
cid=1
pid=1

获取对ID为1的项目集有权限的用户数量 >> 8
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

$user->objectModel->app->company->admins = ',admin,';

r(count($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))) && p()         && e('6');      //获取对ID为1的项目集有权限的用户数量
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))        && p('test9')  && e('test9');  //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(1, $stakeholders, $whiteList, array()))        && p('admin')  && e('admin');  //获取对ID为1的项目集有权限的用户
r($user->getProgramAuthedUsersTest(2, $stakeholders, $whiteList, array()))        && p('astaw')  && e('astaw');  //获取对ID为2的项目集有权限的用户
r($user->getProgramAuthedUsersTest(2, $stakeholders, $whiteList, $admins))        && p('test21') && e('test21'); //获取对ID为2的项目集有权限的用户
