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

