#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

/**

title=测试 userModel->checkSprintPriv();
cid=1
pid=1

传入admin，判断admin用户是否有权限 >> 1
传入执行、用户名，判断test3用户是否对此执行有权限 >> 0
传入执行、用户名，判断test2用户是否对此执行有权限 >> 1
传入执行、用户名、干系人、白名单，判断user10用户是否对此执行有权限 >> 1
传入执行、用户名、干系人、白名单，判断user60用户是否对此执行有权限 >> 1

*/

$user = new userTest();
$sprint = new stdclass();
$sprint->id       = 1;
$sprint->name     = '测试项目集';
$sprint->type     = 'program';
$sprint->parent   = 0;
$sprint->PM       = 'test2';
$sprint->PO       = '';
$sprint->QD       = '';
$sprint->RD       = '';
$sprint->openedBy = 'pm1';
$sprint->acl      = 'private';

$stakeholders['user10'] = 'user10';
$whiteList['user60']    = 'user60';

r($user->checkSprintPrivTest($sprint, 'admin'))                                      && p() && e('1'); //传入admin，判断admin用户是否有权限
r($user->checkSprintPrivTest($sprint, 'test3'))                                      && p() && e('0'); //传入执行、用户名，判断test3用户是否对此执行有权限
r($user->checkSprintPrivTest($sprint, 'test2'))                                      && p() && e('1'); //传入执行、用户名，判断test2用户是否对此执行有权限
r($user->checkSprintPrivTest($sprint, 'user10', $stakeholders, array(), array()))    && p() && e('1'); //传入执行、用户名、干系人、白名单，判断user10用户是否对此执行有权限
r($user->checkSprintPrivTest($sprint, 'user60', $stakeholders, array(), $whiteList)) && p() && e('1'); //传入执行、用户名、干系人、白名单，判断user60用户是否对此执行有权限
