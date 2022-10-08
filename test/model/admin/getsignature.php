#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/admin.class.php';
su('admin');

/**

title=测试 adminModel->getSignature();
cid=1
pid=1

正常测试 >> 1

*/
$admin = new adminTest();

r($admin->getSignatureTest()) && p() && e('1'); //正常测试