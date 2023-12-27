#!/usr/bin/env php
<?php

/**

title=测试 fileModel->updateTestcaseVersion();
cid=0

- 检查用例ID = 0 用例 fromCaseVersion 字段是否改动 @0
- 检查用例ID = 1 用例 fromCaseVersion 字段是否改动 @1
- 检查用例ID = 2 用例 fromCaseVersion 字段是否改动 @0
- 检查用例ID = 3 用例 fromCaseVersion 字段是否改动 @0
- 检查用例ID = 4 用例 fromCaseVersion 字段是否改动 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$testcase = zdTable('case');
$testcase->lib->range('0,1,2,3');
$testcase->product->range('3,2,1,0');
$testcase->fromCaseID->range('4,3,2,1');
$testcase->fromCaseVersion->range('4,3,2,1');
$testcase->gen(4);

$file = new fileTest();

r($file->updateTestcaseVersionTest(0)) && p() && e('0'); //检查用例ID = 0 用例 fromCaseVersion 字段是否改动
r($file->updateTestcaseVersionTest(1)) && p() && e('1'); //检查用例ID = 1 用例 fromCaseVersion 字段是否改动
r($file->updateTestcaseVersionTest(2)) && p() && e('0'); //检查用例ID = 2 用例 fromCaseVersion 字段是否改动
r($file->updateTestcaseVersionTest(3)) && p() && e('0'); //检查用例ID = 3 用例 fromCaseVersion 字段是否改动
r($file->updateTestcaseVersionTest(4)) && p() && e('0'); //检查用例ID = 4 用例 fromCaseVersion 字段是否改动
