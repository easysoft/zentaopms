#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/admin.class.php';
su('admin');

/**

title=测试 adminModel->getStatOfPMS();
cid=1
pid=1

正常测试 >> 0

*/

$admin = new adminTest();

r($admin->getStatOfPMSTest()) && p() && e('0'); //正常测试