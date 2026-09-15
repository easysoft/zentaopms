#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSpace();
timeout=0
cid=0

- 步骤 1：正常输入：查询不存在的空间返回空数组 @array
- 步骤 2：正常输入：查询不存在的空间返回结果为空 @0
- 步骤 3：边界值输入：空间 ID 为 0 时返回空数组 @array
- 步骤 4：边界值输入：空间 ID 为负数时返回空数组 @array
- 步骤 5：无效输入：空间 ID 超出范围时返回结果为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingSpaceID = 999999;

r($gitfoxTest->apiGetSpaceTypeTest($missingSpaceID)) && p() && e('array');  // 步骤 1：正常输入：查询不存在的空间返回空数组
r($gitfoxTest->apiGetSpaceCountTest($missingSpaceID)) && p() && e('0');     // 步骤 2：正常输入：查询不存在的空间返回结果为空
r($gitfoxTest->apiGetSpaceTypeTest(0)) && p() && e('array');                // 步骤 3：边界值输入：空间 ID 为 0 时返回空数组
r($gitfoxTest->apiGetSpaceTypeTest(-1)) && p() && e('array');               // 步骤 4：边界值输入：空间 ID 为负数时返回空数组
r($gitfoxTest->apiGetSpaceCountTest($missingSpaceID + 1)) && p() && e('0'); // 步骤 5：无效输入：空间 ID 超出范围时返回结果为空
