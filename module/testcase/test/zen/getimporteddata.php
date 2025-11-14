#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getImportedData();
timeout=0
cid=0

- 执行$result1 @2
- 执行$result2[0]['caseData'] @1
- 执行$result3[1] @1
- 执行$result4[0]['caseData'] @0
- 执行$result5[0]['caseData'] @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 创建测试CSV文件
global $app;
$tmpDir = $app->getBasePath() . 'tmp/test/';
if(!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1:测试正常CSV文件导入,返回数组结构包含两个元素
$csvFile1 = $tmpDir . 'testcase_import_1.csv';
$csvContent1 = "用例名称,优先级\n测试用例1,1\n测试用例2,2";
file_put_contents($csvFile1, $csvContent1);
$result1 = $testcaseTest->getImportedDataTest(1, $csvFile1);
r(count($result1)) && p() && e('2');

// 步骤2:测试CSV文件导入,第一个元素是数组且包含caseData键
$csvFile2 = $tmpDir . 'testcase_import_2.csv';
$csvContent2 = "用例名称,优先级\n测试用例A,1";
file_put_contents($csvFile2, $csvContent2);
$result2 = $testcaseTest->getImportedDataTest(1, $csvFile2);
r(isset($result2[0]['caseData'])) && p() && e('1');

// 步骤3:测试CSV文件导入,第二个元素是数字(stepVars)
$csvFile3 = $tmpDir . 'testcase_import_3.csv';
$csvContent3 = "用例名称,优先级\n测试用例B,2";
file_put_contents($csvFile3, $csvContent3);
$result3 = $testcaseTest->getImportedDataTest(1, $csvFile3);
r(is_numeric($result3[1])) && p() && e('1');

// 步骤4:测试空CSV文件导入,caseData为空数组
$csvFile4 = $tmpDir . 'testcase_import_4.csv';
$csvContent4 = "用例名称,优先级\n";
file_put_contents($csvFile4, $csvContent4);
$result4 = $testcaseTest->getImportedDataTest(1, $csvFile4);
r(count($result4[0]['caseData'])) && p() && e('0');

// 步骤5:测试CSV包含空标题行,空标题被忽略
$csvFile5 = $tmpDir . 'testcase_import_5.csv';
$csvContent5 = "用例名称,优先级\n测试用例C,1\n,2\n测试用例D,3";
file_put_contents($csvFile5, $csvContent5);
$result5 = $testcaseTest->getImportedDataTest(1, $csvFile5);
r(count($result5[0]['caseData'])) && p() && e('2');

// 清理临时文件
unlink($csvFile1);
unlink($csvFile2);
unlink($csvFile3);
unlink($csvFile4);
unlink($csvFile5);