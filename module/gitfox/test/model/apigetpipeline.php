#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetPipeline();
timeout=0
cid=0

- 步骤 1：查询不存在仓库的流水线不产生 dao 错误 @0
- 步骤 2：查询不存在仓库且不带 branch 时返回 null @0
- 步骤 3：查询不存在仓库且不带 branch 时返回值类型为 null @null
- 步骤 4：查询不存在仓库且带 branch 时返回空数组 @0
- 步骤 5：查询不存在仓库且带 branch 时返回值类型为 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingRepoID = 999999;

r($gitfoxTest->apiGetPipelineErrorTest(1, $missingRepoID)) && p() && e('0');
r($gitfoxTest->apiGetPipelineTest(1, $missingRepoID)) && p() && e('0');
r($gitfoxTest->apiGetPipelineTypeTest(1, $missingRepoID)) && p() && e('null');
r($gitfoxTest->apiGetPipelineTest(1, $missingRepoID, 'main')) && p() && e('0');
r($gitfoxTest->apiGetPipelineTypeTest(1, $missingRepoID, 'main')) && p() && e('array');
