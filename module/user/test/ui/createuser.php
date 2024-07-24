#!/usr/bin/env php
<?php

/**

title=创建用户
timeout=0
cid=1

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

r($tester->createNormalUser($user)) && p('message')  && e('创建用户成功');                   // 创建用户后的跳转链接检查
r($tester->createNormalUser($user)) && p('message')  && e('创建重名用户提示正确');           // 创建用户后的跳转链接检查

r($tester->createEmptyAccountUser($user))  && p('message')  && e('用户名为空提示正确');      // 创建用户后的跳转链接检查
r($tester->createEmptyPasswordUser($user)) && p('message')  && e('密码为空提示正确');        // 创建用户后的跳转链接检查

$tester->closeBrowser();
