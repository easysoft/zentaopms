#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetDiffVersions();
timeout=0
cid=17228

- 测试步骤1：无效的主机ID @0
- 测试步骤2：负数主机ID @0
- 测试步骤3：空字符串项目ID @0
- 测试步骤4：零值合并请求ID @0
- 测试步骤5：有效参数获取差异版本 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(1);

su('admin');

$mrTest = new mrTest();

$validHostID   = 1;
$invalidHostID = 999;
$negativeHostID = -1;
$validProjectID = '3';
$emptyProjectID = '';
$validMRID = 36;
$negativeMRID = -1;
$zeroMRID = 0;

r($mrTest->apiGetDiffVersionsTest($invalidHostID, $validProjectID, $validMRID)) && p() && e('0'); // 测试步骤1：无效的主机ID
r($mrTest->apiGetDiffVersionsTest($negativeHostID, $validProjectID, $validMRID)) && p() && e('0'); // 测试步骤2：负数主机ID
r($mrTest->apiGetDiffVersionsTest($validHostID, $emptyProjectID, $validMRID)) && p() && e('0'); // 测试步骤3：空字符串项目ID
r($mrTest->apiGetDiffVersionsTest($validHostID, $validProjectID, $zeroMRID)) && p() && e('0'); // 测试步骤4：零值合并请求ID
r(count($mrTest->apiGetDiffVersionsTest($validHostID, $validProjectID, $validMRID))) && p() && e('20'); // 测试步骤5：有效参数获取差异版本