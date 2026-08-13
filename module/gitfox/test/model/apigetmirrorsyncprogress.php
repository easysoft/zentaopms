#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetmirrorsyncprogress();
timeout=0
cid=0

- 步骤 1：apiGetMirrorSyncProgress 产生 dao 错误 @1
- 步骤 2：apiGetMirrorSyncProgress 返回 null @0
- 步骤 3：apiGetMirrorSyncProgress 返回值类型为 null @null
- 步骤 4：重复调用仍产生 dao 错误 @1
- 步骤 5：重复调用仍返回 null @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
r($gitfoxTest->apiGetMirrorSyncProgressErrorTest(1)) && p() && e('1');
r($gitfoxTest->apiGetMirrorSyncProgressTest(1)) && p() && e('0');
r($gitfoxTest->apiGetMirrorSyncProgressTypeTest(1)) && p() && e('null');
r($gitfoxTest->apiGetMirrorSyncProgressErrorTest(1)) && p() && e('1');
r($gitfoxTest->apiGetMirrorSyncProgressTest(1)) && p() && e('0');
