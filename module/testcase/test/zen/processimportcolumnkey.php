#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processImportColumnKey();
timeout=0
cid=19102

- 步骤1：正常情况 @title,module,type,pri,expect

- 步骤2：部分映射 @title,module

- 步骤3：空头部字段 @title
- 步骤4：空映射返回空数组长度 @0
- 步骤5：空文件处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$file = zenData('file');
$file->loadYaml('file_processimportcolumnkey', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试CSV文件
$csvContent1 = "用例标题,所属模块,用例类型,优先级,预期结果\n测试用例1,功能模块,功能测试,高,预期通过\n测试用例2,界面模块,界面测试,中,预期通过";
$csvContent2 = "标题,模块,备注\n测试用例A,模块A,备注A\n测试用例B,模块B,备注B";
$csvContent3 = "用例标题,,用例类型,,预期结果\n测试用例X,,功能测试,,预期通过";
$csvContent4 = "unknown1,unknown2,unknown3\n数据1,数据2,数据3";
$csvContent5 = "\n"; // 空内容只有换行

// 确保创建在zentao路径下
$testDir = dirname(__FILE__, 5) . '/tmp/unittest/';
if(!is_dir($testDir)) mkdir($testDir, 0777, true);

file_put_contents($testDir . 'normal_test.csv', $csvContent1);
file_put_contents($testDir . 'partial_test.csv', $csvContent2);
file_put_contents($testDir . 'empty_header_test.csv', $csvContent3);
file_put_contents($testDir . 'unknown_fields_test.csv', $csvContent4);
file_put_contents($testDir . 'empty_test.csv', $csvContent5);

// 5. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 6. 定义字段映射
$normalFields = array(
    '用例标题' => 'title',
    '所属模块' => 'module',
    '用例类型' => 'type',
    '优先级' => 'pri',
    '预期结果' => 'expect'
);

$partialFields = array(
    '标题' => 'title',
    '模块' => 'module'
);

$emptyFields = array();

// 7. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processImportColumnKeyTest($testDir . 'normal_test.csv', $normalFields)) && p() && e('title,module,type,pri,expect'); // 步骤1：正常情况
r($testcaseTest->processImportColumnKeyTest($testDir . 'partial_test.csv', $partialFields)) && p() && e('title,module'); // 步骤2：部分映射
r($testcaseTest->processImportColumnKeyTest($testDir . 'empty_header_test.csv', $normalFields)) && p() && e('title'); // 步骤3：空头部字段
r($testcaseTest->processImportColumnKeyTest($testDir . 'unknown_fields_test.csv', $emptyFields)) && p() && e('0'); // 步骤4：空映射返回空数组长度
r($testcaseTest->processImportColumnKeyTest($testDir . 'empty_test.csv', $normalFields)) && p() && e('0'); // 步骤5：空文件处理

// 8. 清理测试文件
unlink($testDir . 'normal_test.csv');
unlink($testDir . 'partial_test.csv');
unlink($testDir . 'empty_header_test.csv');
unlink($testDir . 'unknown_fields_test.csv');
unlink($testDir . 'empty_test.csv');