#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetBranchLabels();
timeout=0
cid=0

- 步骤 1：HTTP 返回标签数组时按原结构返回数组 @feature
- 步骤 2：HTTP 返回标签数组时第二个标签字段正确 @bugfix
- 步骤 3：HTTP 返回带有 message 字段的错误对象时返回空数组 @0
- 步骤 4：HTTP 返回空响应时返回空数组 @0
- 步骤 5：HTTP 返回非 JSON 字符串时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
zenData('pipeline')->loadYaml('pipeline')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$labelList = json_encode(array(array('id' => 1, 'key' => 'feature'), array('id' => 2, 'key' => 'bugfix')));
$errorMsg  = json_encode(array('message' => 'forbidden'));

r($gitfoxTest->apiGetBranchLabelsTest(1, 1, $labelList)) && p('0:key') && e('feature'); // 步骤 1
r($gitfoxTest->apiGetBranchLabelsTest(1, 1, $labelList)) && p('1:key') && e('bugfix'); // 步骤 2
r($gitfoxTest->apiGetBranchLabelsTest(1, 2, $errorMsg)) && p() && e('0'); // 步骤 3
r($gitfoxTest->apiGetBranchLabelsTest(1, 3, '')) && p() && e('0'); // 步骤 4
r($gitfoxTest->apiGetBranchLabelsTest(1, 4, 'not-json')) && p() && e('0'); // 步骤 5
