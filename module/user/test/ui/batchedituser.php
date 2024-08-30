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
