#!/usr/bin/env php
<?php
/**

title=测试 adminModel->sendInstallEvent();
timeout=0
cid=1

- 发送安装过程埋点 @Success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$admin = new adminTest();
$data = new stdClass();
$data->fingerprint = 'ba237c362b6aad54d17e7613eac23ec1';
$data->location    = 'join-community';
r($admin->sendInstallEvent($data)) && p() && e('Success'); //测试发送安装过程埋点是否成功
