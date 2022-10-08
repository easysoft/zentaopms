#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->checkProgramPriv();
cid=1
pid=1

传入admin，判断admin用户是否有权限 >> 1
传入项目集、用户名，判断test3用户是否对此项目集有权限 >> 0
传入项目集、用户名，判断test2用户是否对此项目集有权限 >> 1
传入项目集、用户名、干系人、白名单，判断user10用户是否对此项目集有权限 >> 1
传入项目集、用户名、干系人、白名单，判断user60用户是否对此项目集有权限 >> 1

*/

$user = new userTest();
$program = new stdclass();
$program->id       = 1;
$program->name     = '测试项目集';
$program->PM       = 'test2';
$program->openedBy = 'pm1';

$stakeholders['user10'] = 'user10';
$whiteList['user60']    = 'user60';

r($user->checkProgramPrivTest('', 'admin'))                                   && p() && e('1'); //传入admin，判断admin用户是否有权限
r($user->checkProgramPrivTest($program, 'test3'))                             && p() && e('0'); //传入项目集、用户名，判断test3用户是否对此项目集有权限
r($user->checkProgramPrivTest($program, 'test2'))                             && p() && e('1'); //传入项目集、用户名，判断test2用户是否对此项目集有权限
r($user->checkProgramPrivTest($program, 'user10', $stakeholders, array()))    && p() && e('1'); //传入项目集、用户名、干系人、白名单，判断user10用户是否对此项目集有权限
r($user->checkProgramPrivTest($program, 'user60', $stakeholders, $whiteList)) && p() && e('1'); //传入项目集、用户名、干系人、白名单，判断user60用户是否对此项目集有权限