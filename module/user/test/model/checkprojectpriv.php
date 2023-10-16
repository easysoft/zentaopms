#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

/**

title=测试 userModel->checkProjectPriv();
cid=1
pid=1

传入admin，判断admin用户是否有权限 >> 1
传入项目、用户名，判断test3用户是否对此项目有权限 >> 0
传入项目、用户名，判断test2用户是否对此项目有权限 >> 1
传入项目、用户名、干系人、白名单，判断user10用户是否对此项目有权限 >> 1
传入项目、用户名、干系人、白名单，判断user60用户是否对此项目有权限 >> 1

*/

$user = new userTest();
$project = new stdclass();
$project->id       = 10;
$project->name     = '测试项目';
$project->type     = 'project';
$project->parent   = 0;
$project->PM       = 'test2';
$project->PO       = '';
$project->QD       = '';
$project->RD       = '';
$project->openedBy = 'pm1';
$project->acl      = 'private';

$stakeholders['user10'] = 'user10';
$whiteList['user60']    = 'user60';
$admins['test6']        = 'test6';

$user->objectModel->app->company->admins = ',admin,';

r($user->checkProjectPrivTest(new stdclass(), 'admin'))                                     && p() && e('1'); //传入admin，判断admin用户是否有权限
r($user->checkProjectPrivTest($project, 'test3'))                                           && p() && e('0'); //传入项目、用户名，判断test3用户是否对此项目有权限
r($user->checkProjectPrivTest($project, 'test2'))                                           && p() && e('1'); //传入项目、用户名，判断test2用户是否对此项目有权限
r($user->checkProjectPrivTest($project, 'user10', $stakeholders, array(), array()))         && p() && e('1'); //传入项目、用户名、干系人、白名单，判断user10用户是否对此项目有权限
r($user->checkProjectPrivTest($project, 'user60', $stakeholders, array(), $whiteList))      && p() && e('1'); //传入项目、用户名、干系人、白名单，判断user60用户是否对此项目有权限
r($user->checkProjectPrivTest($project, 'test6', $stakeholders, array(), array(), $admins)) && p() && e('1'); //传入项目、用户名、admins，判断test6用户是否对此项目有权限

$project->acl = 'open';
r($user->checkProjectPrivTest($project, 'test8')) && p() && e('1'); //传入公开项目，判断test8用户是否对此项目有权限
