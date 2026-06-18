#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiMirrorSync();
timeout=0
cid=0

- 步骤 1：HTTP 返回成功对象时返回包含 code 字段的对象 @success
- 步骤 2：HTTP 返回成功对象时 data 字段被解析 @1
- 步骤 3：HTTP 返回失败对象时返回包含错误信息的对象 @sync failed
- 步骤 4：HTTP 返回空字符串时返回 null @0
- 步骤 5：HTTP 返回非 JSON 字符串时返回 null @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
zenData('pipeline')->loadYaml('pipeline')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$success = json_encode(array('code' => 'success', 'data' => array('id' => 1)));
$fail    = json_encode(array('code' => 'fail', 'message' => 'sync failed'));

r($gitfoxTest->apiMirrorSyncTest(1, $success)) && p('code') && e('success'); // 步骤 1
r($gitfoxTest->apiMirrorSyncTest(1, $success)) && p('data:id') && e('1'); // 步骤 2
r($gitfoxTest->apiMirrorSyncTest(2, $fail)) && p('message') && e('sync failed'); // 步骤 3
r($gitfoxTest->apiMirrorSyncTest(3, '')) && p() && e('0'); // 步骤 4
r($gitfoxTest->apiMirrorSyncTest(4, 'not-json')) && p() && e('0'); // 步骤 5
