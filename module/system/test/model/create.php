#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zendata('system')->gen(0);

/**

title=测试 systemModel::create();
timeout=0
cid=1


*/
global $tester;
$system = $tester->loadModel('system');
$default = new stdclass();
$default->name        = '应用10';
$default->product     = 1;
$default->integrated  = 0;
