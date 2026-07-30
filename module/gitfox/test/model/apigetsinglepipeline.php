#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSinglePipeline();
timeout=0
cid=0

- 步骤 1：apiGetSinglePipeline 不产生 dao 错误 @0
- 步骤 2：apiGetSinglePipeline 返回 null @0
- 步骤 3：apiGetSinglePipeline 返回值类型为 null @null
- 步骤 4：重复调用仍返回 null @0
- 步骤 5：重复调用的返回值类型仍为 null @null

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiGetSinglePipelineErrorTest(1, 1, 'ci', 1)) && p() && e('0');
r($gitfoxTest->apiGetSinglePipelineTest(1, 1, 'ci', 1)) && p() && e('0');
r($gitfoxTest->apiGetSinglePipelineTypeTest(1, 1, 'ci', 1)) && p() && e('null');
r($gitfoxTest->apiGetSinglePipelineTest(1, 1, 'ci', 1)) && p() && e('0');
r($gitfoxTest->apiGetSinglePipelineTypeTest(1, 1, 'ci', 1)) && p() && e('null');
