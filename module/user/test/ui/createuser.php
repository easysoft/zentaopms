#!/usr/bin/env php
<?php

/**

title=创建用户
timeout=0
cid=1

- 正常创建用户，创建成功
 - 测试结果 @成功创建用户
- 创建重名用户
 - 测试结果 @创建重名用户提示正确
-创建用户名为空的用户
 - 测试结果 @用户名为空提示正确
- 创建密码为空的用户
 - 测试结果 @密码为空提示正确

 */
chdir(__DIR__);
include '../lib/createuser.ui.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(10);
$tester = new createUserTester();
$tester->login();

$user = new stdclass();
$user->account         = 'lijuanjuan';
$user->password        = '123456';
$user->confirmPassword = '123456';
$user->realname        = '李娟娟';
$user->verifyPassword  = $config->uitest->defaultPassword;

r($tester->createNormalUser($user)) && p('message')  && e('创建用户成功');                   // 创建用户成功
r($tester->createNormalUser($user)) && p('message')  && e('创建重名用户提示正确');           // 创建重名用户提示正确

r($tester->createEmptyAccountUser($user))  && p('message')  && e('用户名为空提示正确');      // 用户名为空的提示正确
r($tester->createEmptyPasswordUser($user)) && p('message')  && e('密码为空提示正确');        // 密码为空的提示正确

$tester->closeBrowser();
