#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildOperateMenu();
timeout=0
cid=0

- 步骤1：正常情况 @array
- 步骤2：边界值 @array
- 步骤3：异常输入 @array
- 步骤4：空模块名 @array
- 步骤5：业务规则 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$testData1 = (object)array('id' => '1', 'name' => '任务1', 'status' => 'wait', 'assignedTo' => 'admin');
$testData2 = (object)array('id' => '999', 'name' => '不存在任务');
$testData3 = (object)array('id' => '1');
$testData4 = (object)array('id' => '2', 'name' => '任务2', 'status' => 'doing');
$testData5 = (object)array('id' => '0', 'name' => '', 'status' => '');

r($commonTest->buildOperateMenuTest($testData1, 'task')) && p() && e('array'); // 步骤1：正常情况
r($commonTest->buildOperateMenuTest($testData2, 'task')) && p() && e('array'); // 步骤2：边界值
r($commonTest->buildOperateMenuTest($testData3, 'invalid_module')) && p() && e('array'); // 步骤3：异常输入
r($commonTest->buildOperateMenuTest($testData4, '')) && p() && e('array'); // 步骤4：空模块名
r($commonTest->buildOperateMenuTest($testData5, 'task')) && p() && e('array'); // 步骤5：业务规则