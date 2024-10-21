#!/usr/bin/env php
<?php

/**

title=编辑用户
timeout=0
cid=2

- 编辑用户成功
 -属性module@company
 -属性method@browse

*/
chdir(__DIR__);
include '../lib/edituser.ui.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(10);
$tester = new editUserTester();
$tester->login();

$user = new stdclass();
$user->password        = '123456';
$user->confirmPassword = '123456';
$user->realname        = '李娟娟';
$user->verifyPassword  = $config->uitest->defaultPassword;

r($tester->checkLocating($user)) && p('module,method')  && e('company,browse');                     //编辑用户成功

$tester->closeBrowser();
