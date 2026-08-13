#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSpace();
timeout=0
cid=0

- 步骤 1：查询不存在的空间会产生 dao 错误 @1
- 步骤 2：查询不存在的空间返回空结果 @0
- 步骤 3：查询不存在的空间返回值类型为 array @array
- 步骤 4：再次查询另一个不存在的空间仍会产生 dao 错误 @1
- 步骤 5：再次查询另一个不存在的空间仍返回空结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingSpaceID = 999999;

r($gitfoxTest->apiGetSpaceErrorTest($missingSpaceID)) && p() && e('1');
r($gitfoxTest->apiGetSpaceCountTest($missingSpaceID)) && p() && e('0');
r($gitfoxTest->apiGetSpaceTypeTest($missingSpaceID)) && p() && e('array');
r($gitfoxTest->apiGetSpaceErrorTest($missingSpaceID + 1)) && p() && e('1');
r($gitfoxTest->apiGetSpaceCountTest($missingSpaceID + 1)) && p() && e('0');
