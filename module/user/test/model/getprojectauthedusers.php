#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$project = zdTable('project');
$project->type->range('project');
$project->PM->range('user1');
$project->gen(10);
zdTable('user')->gen(100);
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

$user->objectModel->app->company->admins = ',admin,';

r($user->getProjectAuthedUsersTest(1, $stakeholders, $teams, $whiteList)) && p('test9') && e('test9');  //获取对ID为1的产品有权限的用户
r($user->getProjectAuthedUsersTest(1, $stakeholders, $teams, $whiteList)) && p('admin') && e('admin');  //获取对ID为1的产品有权限的用户
r($user->getProjectAuthedUsersTest(2, $stakeholders, $teams, $whiteList)) && p('astaw') && e('astaw');  //获取对ID为2的产品有权限的用户
