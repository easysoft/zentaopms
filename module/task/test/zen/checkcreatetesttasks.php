#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkCreateTestTasks();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：空数组 @请至少选择一个软件需求。
- 步骤3：负数estimate属性testEstimate[1] @最初预计不能为负数
- 步骤4：无效日期属性testDeadline[1] @"截止日期"必须大于"预计开始"
- 步骤5：缺少必需字段 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 为了简化测试，这里不使用zendata，直接在测试代码中构造数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：传入正常的测试任务数组
$validTask1 = new stdClass();
$validTask1->execution = 1;
$validTask1->name = '测试任务1';
$validTask1->type = 'test';
$validTask1->estimate = 8.0;
$validTask1->estStarted = '2024-06-01';
$validTask1->deadline = '2024-06-10';

$validTask2 = new stdClass();
$validTask2->execution = 1;
$validTask2->name = '测试任务2';
$validTask2->type = 'test';
$validTask2->estimate = 4.0;
$validTask2->estStarted = '2024-06-02';
$validTask2->deadline = '2024-06-08';

$validTasks = array($validTask1, $validTask2);
r($taskTest->checkCreateTestTasksTest($validTasks)) && p() && e('1'); // 步骤1：正常情况

// 步骤2：传入空任务数组
$emptyTasks = array();
r($taskTest->checkCreateTestTasksTest($emptyTasks)) && p('0') && e('请至少选择一个软件需求。'); // 步骤2：空数组

// 步骤3：传入包含负数estimate的任务
$negativeEstimateTask = new stdClass();
$negativeEstimateTask->execution = 1;
$negativeEstimateTask->name = '测试任务3';
$negativeEstimateTask->type = 'test';
$negativeEstimateTask->estimate = -2.0;
$negativeEstimateTask->estStarted = '2024-06-01';
$negativeEstimateTask->deadline = '2024-06-10';

$negativeEstimateTasks = array($negativeEstimateTask);
r($taskTest->checkCreateTestTasksTest($negativeEstimateTasks)) && p('testEstimate[1]') && e('最初预计不能为负数'); // 步骤3：负数estimate

// 步骤4：传入开始日期晚于结束日期的任务
$invalidDateTask = new stdClass();
$invalidDateTask->execution = 1;
$invalidDateTask->name = '测试任务4';
$invalidDateTask->type = 'test';
$invalidDateTask->estimate = 4.0;
$invalidDateTask->estStarted = '2024-06-15';
$invalidDateTask->deadline = '2024-06-10';

$invalidDateTasks = array($invalidDateTask);
r($taskTest->checkCreateTestTasksTest($invalidDateTasks)) && p('testDeadline[1]') && e('"截止日期"必须大于"预计开始"'); // 步骤4：无效日期

// 步骤5：传入缺少必需字段的任务
$missingFieldTask = new stdClass();
$missingFieldTask->execution = 1;
$missingFieldTask->name = '';
$missingFieldTask->type = 'test';
$missingFieldTask->estimate = 4.0;
$missingFieldTask->estStarted = '2024-06-01';
$missingFieldTask->deadline = '2024-06-10';

$missingFieldTasks = array($missingFieldTask);
r($taskTest->checkCreateTestTasksTest($missingFieldTasks)) && p() && e('Array'); // 步骤5：缺少必需字段