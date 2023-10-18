#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(10);
su('admin');

$project = zdTable('project');
$project->type->range('sprint');
$project->PM->range('user1');
$project->gen(10);
zdTable('user')->gen(100);

/**

title=测试 userModel->getSprintAuthedUsers();
cid=1
pid=1

获取对ID为101的执行有权限的用户 >> test9
获取对ID为101的执行有权限的用户 >> admin
获取对ID为201的执行有权限的用户 >> astaw
获取对ID为201的执行有权限的用户 >> test21

*/
$user = new userTest();
$stakeholders['test9']  = 'test9';
$stakeholders['test10'] = 'test10';

$teams['pm7'] = 'pm7';
$teams['pm8'] = 'pm8';

$whiteList['user35'] = 'user35';
$whiteList['user35'] = 'user35';
$whiteList['astaw']  = 'astaw';

$admins['test20'] = 'test20';
$admins['test21'] = 'test21';

$user->objectModel->app->company->admins = ',admin,';

r($user->getSprintAuthedUsersTest(1, $stakeholders, $teams, $whiteList, array())) && p('test9')  && e('test9');  //获取对ID为101的执行有权限的用户
r($user->getSprintAuthedUsersTest(1, $stakeholders, $teams, $whiteList, array())) && p('admin')  && e('admin');  //获取对ID为101的执行有权限的用户
r($user->getSprintAuthedUsersTest(2, $stakeholders, $teams, $whiteList, array())) && p('astaw')  && e('astaw');  //获取对ID为201的执行有权限的用户
r($user->getSprintAuthedUsersTest(2, $stakeholders, $teams, $whiteList, $admins)) && p('test21') && e('test21'); //获取对ID为201的执行有权限的用户
