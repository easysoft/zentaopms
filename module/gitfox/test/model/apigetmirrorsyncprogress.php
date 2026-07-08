#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetMirrorSyncProgress();
timeout=0
cid=0

- 步骤 1：HTTP 返回成功对象时返回业务进度对象 @50
- 步骤 2：HTTP 返回成功业务码时进度对象包含 status 字段 @running
- 步骤 3：HTTP 返回失败业务码时返回 null @0
- 步骤 4：HTTP 返回空字符串时返回 null @0
- 步骤 5：HTTP 返回的 data 非对象时返回 null @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
zenData('pipeline')->loadYaml('pipeline')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$success      = json_encode(array('code' => 'success', 'data' => array('progress' => 50, 'status' => 'running')));
$fail         = json_encode(array('code' => 'fail', 'message' => 'mirror sync error'));
$dataNotObject = json_encode(array('code' => 'success', 'data' => array('a', 'b', 'c')));

r($gitfoxTest->apiGetMirrorSyncProgressTest(1, $success)) && p('progress') && e('50'); // 步骤 1
r($gitfoxTest->apiGetMirrorSyncProgressTest(1, $success)) && p('status') && e('running'); // 步骤 2
r($gitfoxTest->apiGetMirrorSyncProgressTest(2, $fail)) && p() && e('0'); // 步骤 3
r($gitfoxTest->apiGetMirrorSyncProgressTest(3, '')) && p() && e('0'); // 步骤 4
r($gitfoxTest->apiGetMirrorSyncProgressTest(4, $dataNotObject)) && p() && e('0'); // 步骤 5
