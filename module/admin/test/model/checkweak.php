#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/admin.class.php';
su('admin');

/**

title=测试 adminModel->checkWeak();
cid=1
pid=1

正常测试 >> 0

*/
$account = 'admin';

$admin = new adminTest();

r($admin->checkWeakTest($account)) && p() && e('0'); //正常测试