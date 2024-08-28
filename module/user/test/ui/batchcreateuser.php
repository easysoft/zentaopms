#!/usr/bin/env php
<?php

/**

title=批量创建用户
timeout=0
cid=1

*/
chdir(__DIR__);
include '../lib/batchcreateuser.ui.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(10);
$tester = new batchCreateUserTester();
$tester->login();

$user = new stdclass();
$user->account         = 'ljj';
$user->realname        = '李娟';
$user->password        = '123456';
$user->verifyPassword  = $config->uitest->defaultPassword;

$user1 = new stdclass();
$user1->account         = '';
$user1->realname        = '李娟1';
$user1->password        = '123456';
$user1->verifyPassword  = $config->uitest->defaultPassword;

$user2 = new stdclass();
$user2->account         = 'ljjj';
$user2->realname        = '';
$user2->password        = '123456';
$user2->verifyPassword  = $config->uitest->defaultPassword;

$user3 = new stdclass();
$user3->account         = 'ljuanj';
$user3->realname        = '李娟2';
$user3->password        = '';
$user3->verifyPassword  = $config->uitest->defaultPassword;

r($tester->batchCreateNormalUser($user))         && p('message')  && e('批量建用户成功');          // 创建用户后的跳转链接检查
r($tester->batchCreateEmptyAccountUser($user1))  && p('message')  && e('批量创建用户失败');        // 创建用户后的跳转链接检查
r($tester->batchCreateEmptyRealnameUser($user2)) && p('message')  && e('姓名不能为空');            // 创建用户后的跳转链接检查
r($tester->batchCreateEmptyPasswordUser($user3)) && p('message')  && e('密码不能为空');            // 创建用户后的跳转链接检查

$tester->closeBrowser();
