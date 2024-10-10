#!/usr/bin/env php
<?php

/**

title=批量编辑用户
timeout=0
cid=2

- 正常批量编辑用户
 - 测试结果 @保存成功
- 批量编辑用户，姓名字段置空
 - 测试结果 @姓名不能为空
- 批量编辑用户，验证密码置空
 - 测试结果 @验证失败，请检查您的系统登录密码是否正确

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

r($tester->batchEditUser($user))        && p('message') && e('保存成功');                                 // 正常批量编辑用户
r($tester->emptyRealname($user1))       && p('message') && e('姓名不能为空');                             // 批量编辑用户，姓名字段置空
r($tester->wrongVerifyPassword($user2)) && p('message') && e('验证失败，请检查您的系统登录密码是否正确'); // 批量编辑用户，验证密码置空

$tester->closeBrowser();
