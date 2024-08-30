#!/usr/bin/env php
<?php

/**

title=批量编辑用户
timeout=0
cid=2

*/
chdir(__DIR__);
include '../lib/batchedituser.ui.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(10);
$tester = new batchEditUserTester();
$tester->login();

$user = new stdclass();
$user->realname        = '李娟娟';
$user->verifyPassword  = $config->uitest->defaultPassword;

$user1 = new stdclass();
$user1->realname        = '';
$user1->verifyPassword  = $config->uitest->defaultPassword;

$user2 = new stdclass();
$user2->realname        = '李娟';
$user2->verifyPassword  = '';

r($tester->batchEditUser($user))        && p('message') && e('保存成功');                         //批量编辑用户后的跳转链接检查
r($tester->emptyRealname($user1))       && p('message') && e('姓名不能为空');                             //批量编辑用户后的跳转链接检查
r($tester->wrongVerifyPassword($user2)) && p('message') && e('验证失败，请检查您的系统登录密码是否正确'); //批量编辑用户后的跳转链接检查

$tester->closeBrowser();
