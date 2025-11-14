#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->sendInstallEvent();
timeout=0
cid=17217

- 测试发送安装过程埋点是否成功 @true
- 测试location = '' @false
- 测试fingerprint = '' @false
- 测试fingerprint = '' 并且 location = '' @false
- 测试$data中没有属性 @false

*/

global $tester;
$miscModel = $tester->loadModel('misc');

$fingerprintList = array();
$locationList    = array();

$fingerprintList[0] = 'ba237c362b6aad54d17e7613eac23ec1';
$locationList[0]    = 'start';

$fingerprintList[1] = '572e8c0ccbce6f171980fd98b2b298b1';
$locationList[1]    = '';

$fingerprintList[2] = '';
$locationList[2]    = 'join-community';

$fingerprintList[3] = '';
$locationList[3]    = '';

$fingerprintList[4] = '572e8c0ccbce6f171980fd98b2b298a1';
$locationList[4]    = 'test';


$data = new stdClass();
$data->fingerprint = $fingerprintList[0];
$data->location    = $locationList[0];
r($miscModel->sendInstallEvent($data)) && p() && e(1); // 测试发送安装过程埋点是否成功

$data = new stdClass();
$data->fingerprint = $fingerprintList[1];
$data->location    = $locationList[1];
r($miscModel->sendInstallEvent($data)) && p() && e(0); // 测试location = ''

$data = new stdClass();
$data->fingerprint = $fingerprintList[2];
$data->location    = $locationList[2];
r($miscModel->sendInstallEvent($data)) && p() && e(0); // 测试fingerprint = ''

$data = new stdClass();
$data->fingerprint = $fingerprintList[3];
$data->location    = $locationList[3];
r($miscModel->sendInstallEvent($data)) && p() && e(0); // 测试fingerprint = '' 并且 location = ''

$data = new stdClass();
$data->fingerprint = $fingerprintList[4];
$data->location    = $locationList[4];
r($miscModel->sendInstallEvent($data)) && p() && e(1); // 测试其他location