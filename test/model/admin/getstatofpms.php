#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/admin.class.php';
su('admin');

/**

title=测试 adminModel->getStatOfPMS();
cid=1
pid=1

正常测试 >> 0

*/

$admin = new adminTest();

r($admin->getStatOfPMSTest()) && p() && e('0'); //正常测试