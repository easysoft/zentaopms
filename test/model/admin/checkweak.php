#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/admin.class.php';
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