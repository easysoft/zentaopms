#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetPipelineLogs();
timeout=0
cid=0

- 步骤 1：流水线名称为空时返回空字符串 @0
- 步骤 2：流水线编号为空时返回空字符串 @0
- 步骤 3：有效参数时返回值类型为 string @string
- 步骤 4：有效参数时不产生 dao 错误 @0
- 步骤 5：有效参数时日志长度为 0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiGetPipelineLogsTest(1, 1, (object)array('name' => '', 'number' => 1))) && p() && e('0');
r($gitfoxTest->apiGetPipelineLogsTest(1, 1, (object)array('name' => 'build', 'number' => ''))) && p() && e('0');
r($gitfoxTest->apiGetPipelineLogsTypeTest(1, 1, (object)array('name' => 'build', 'number' => 1))) && p() && e('string');
r($gitfoxTest->apiGetPipelineLogsErrorTest(1, 1, (object)array('name' => 'build', 'number' => 1))) && p() && e('0');
r($gitfoxTest->apiGetPipelineLogsCountTest(1, 1, (object)array('name' => 'build', 'number' => 1))) && p() && e('0');
