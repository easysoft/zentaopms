#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getExecutionList();
timeout=0
cid=0

- 步骤1：正常时间范围查询，有4个closed状态的执行 @4
- 步骤2：空时间参数查询，有4个closed状态的执行 @4
- 步骤3：指定执行ID列表，返回2条记录 @2
- 步骤4：未来时间范围查询，无记录 @0
- 步骤5：验证返回数据结构中第一条的执行ID第0条的executionID属性 @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
zenData('project')->loadYaml('project_getexecutionlist')->gen(15);
zenData('task')->loadYaml('task_getexecutionlist')->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array()))) && p() && e('4'); // 步骤1：正常时间范围查询，有4个closed状态的执行
r(count($pivotTest->getExecutionListTest('', '', array()))) && p() && e('4'); // 步骤2：空时间参数查询，有4个closed状态的执行
r(count($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array(6, 7)))) && p() && e('2'); // 步骤3：指定执行ID列表，返回2条记录
r(count($pivotTest->getExecutionListTest('2025-01-01', '2025-12-31', array()))) && p() && e('0'); // 步骤4：未来时间范围查询，无记录
r($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array())) && p('0:executionID') && e('6'); // 步骤5：验证返回数据结构中第一条的执行ID