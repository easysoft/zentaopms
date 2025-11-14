#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkBatchCreateTask();
timeout=0
cid=18918

- 步骤1：正常情况 @1
- 步骤2：名称超长属性name[0] @名称长度不能超过255个字符。
- 步骤3：工时格式错误属性estimate[0] @『预计』应当是数字。
- 步骤4：日期错误属性deadline[0] @『截止日期』应当大于『预计开始』。
- 步骤5：负数工时属性estimate[0] @工时不能为负数。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 此测试不需要数据库依赖，因为是纯逻辑验证

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 构造测试数据
// 正常任务数据
$normalTasks = array();
$task1 = new stdClass();
$task1->name = '正常任务1';
$task1->level = 0;
$task1->estimate = 8;
$task1->estStarted = '2024-01-10';
$task1->deadline = '2024-01-20';
$task1->parent = 0;
$task1->execution = 1;
$task1->type = 'devel';
$normalTasks[] = $task1;

$task2 = new stdClass();
$task2->name = '正常任务2';
$task2->level = 0;
$task2->estimate = 4;
$task2->estStarted = '2024-01-12';
$task2->deadline = '2024-01-18';
$task2->parent = 0;
$task2->execution = 1;
$task2->type = 'devel';
$normalTasks[] = $task2;

// 名称超长的任务
$longNameTasks = array();
$longNameTask = new stdClass();
$longNameTask->name = str_repeat('超长任务名称', 50); // 生成超过255字符的名称
$longNameTask->level = 0;
$longNameTask->estimate = 5;
$longNameTask->estStarted = '2024-01-10';
$longNameTask->deadline = '2024-01-15';
$longNameTask->parent = 0;
$longNameTask->execution = 1;
$longNameTask->type = 'devel';
$longNameTasks[] = $longNameTask;

// 预估工时格式错误的任务
$invalidEstimateTasks = array();
$invalidEstimateTask = new stdClass();
$invalidEstimateTask->name = '工时格式错误任务';
$invalidEstimateTask->level = 0;
$invalidEstimateTask->estimate = 'abc'; // 非数字格式
$invalidEstimateTask->estStarted = '2024-01-10';
$invalidEstimateTask->deadline = '2024-01-15';
$invalidEstimateTask->parent = 0;
$invalidEstimateTask->execution = 1;
$invalidEstimateTask->type = 'devel';
$invalidEstimateTasks[] = $invalidEstimateTask;

// 截止日期小于开始日期的任务
$invalidDateTasks = array();
$invalidDateTask = new stdClass();
$invalidDateTask->name = '日期错误任务';
$invalidDateTask->level = 0;
$invalidDateTask->estimate = 6;
$invalidDateTask->estStarted = '2024-01-20';
$invalidDateTask->deadline = '2024-01-10'; // 截止日期小于开始日期
$invalidDateTask->parent = 0;
$invalidDateTask->execution = 1;
$invalidDateTask->type = 'devel';
$invalidDateTasks[] = $invalidDateTask;

// 预估工时为负数的任务
$negativeEstimateTasks = array();
$negativeEstimateTask = new stdClass();
$negativeEstimateTask->name = '负工时任务';
$negativeEstimateTask->level = 0;
$negativeEstimateTask->estimate = -5; // 负数工时
$negativeEstimateTask->estStarted = '2024-01-10';
$negativeEstimateTask->deadline = '2024-01-15';
$negativeEstimateTask->parent = 0;
$negativeEstimateTask->execution = 1;
$negativeEstimateTask->type = 'devel';
$negativeEstimateTasks[] = $negativeEstimateTask;

// 6. 强制要求：必须包含至少5个测试步骤
r($taskTest->checkBatchCreateTaskTest(1, $normalTasks)) && p() && e('1'); // 步骤1：正常情况
r($taskTest->checkBatchCreateTaskTest(1, $longNameTasks)) && p('name[0]') && e('名称长度不能超过255个字符。'); // 步骤2：名称超长
r($taskTest->checkBatchCreateTaskTest(1, $invalidEstimateTasks)) && p('estimate[0]') && e('『预计』应当是数字。'); // 步骤3：工时格式错误
r($taskTest->checkBatchCreateTaskTest(1, $invalidDateTasks)) && p('deadline[0]') && e('『截止日期』应当大于『预计开始』。'); // 步骤4：日期错误
r($taskTest->checkBatchCreateTaskTest(1, $negativeEstimateTasks)) && p('estimate[0]') && e('工时不能为负数。'); // 步骤5：负数工时