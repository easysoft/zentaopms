#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->sendInstallEvent();
timeout=0
cid=1

- 发送安装过程埋点 @Success

*/

global $tester;
$miscModel = $tester->loadModel('misc');

$data = new stdClass();
$data->fingerprint = 'ba237c362b6aad54d17e7613eac23ec1';
$data->location    = 'join-community';
r($miscModel->sendInstallEvent($data)) && p() && e('Success'); //测试发送安装过程埋点是否成功
