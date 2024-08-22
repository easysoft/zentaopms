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
