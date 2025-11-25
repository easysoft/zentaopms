#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkBatchEditTask();
timeout=0
cid=18919

- 步骤1：正常情况 @1
- 步骤2：负数预估工时属性estimate[1] @『预估』不能为负数
- 步骤3：doing状态left为空属性left[2] @剩余工时不能为空，当任务为『进行中』时。
- 步骤4：done状态consumed为0属性consumed[3] @『消耗工时』不能为空
- 步骤5：多种错误组合
 - 属性estimate[4] @『预估』不能为负数
 - 属性consumed[4] @『消耗工时』不能为负数
 - 属性left[4] @剩余工时不能为空，当任务为『进行中』时。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 此测试不需要数据库依赖，因为已在测试类中模拟了核心验证逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 构造测试数据
// 正常的任务数据
$normalTasks = array(
    1 => (object)array(
        'id' => 1,
        'project' => 1,
        'execution' => 101,
        'status' => 'doing',
        'estimate' => 8,
        'consumed' => 4,
        'left' => 4,
        'parent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

$normalOldTasks = array(
    1 => (object)array(
        'id' => 1,
        'project' => 1,
        'execution' => 101,
        'status' => 'wait',
        'estimate' => 8,
        'consumed' => 0,
        'left' => 8,
        'parent' => 0,
        'mode' => '',
        'isParent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

// 工时为负数的任务数据
$negativeEstimateTasks = array(
    1 => (object)array(
        'id' => 1,
        'project' => 1,
        'execution' => 101,
        'status' => 'wait',
        'estimate' => -5,
        'consumed' => 0,
        'left' => 8,
        'parent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

// doing状态但left为空的任务数据
$doingNoLeftTasks = array(
    2 => (object)array(
        'id' => 2,
        'project' => 1,
        'execution' => 101,
        'status' => 'doing',
        'estimate' => 8,
        'consumed' => 8,
        'left' => 0,
        'parent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

$doingOldTasks = array(
    2 => (object)array(
        'id' => 2,
        'project' => 1,
        'execution' => 101,
        'status' => 'wait',
        'estimate' => 8,
        'consumed' => 0,
        'left' => 8,
        'parent' => 0,
        'mode' => '',
        'isParent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

// done状态但consumed为0的任务数据
$doneNoConsumedTasks = array(
    3 => (object)array(
        'id' => 3,
        'project' => 1,
        'execution' => 101,
        'status' => 'done',
        'estimate' => 8,
        'consumed' => 0,
        'left' => 0,
        'parent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

$doneOldTasks = array(
    3 => (object)array(
        'id' => 3,
        'project' => 1,
        'execution' => 101,
        'status' => 'doing',
        'estimate' => 8,
        'consumed' => 8,
        'left' => 0,
        'parent' => 0,
        'mode' => '',
        'isParent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

// 多种错误的任务数据
$multiErrorTasks = array(
    4 => (object)array(
        'id' => 4,
        'project' => 1,
        'execution' => 101,
        'status' => 'doing',
        'estimate' => -10,
        'consumed' => -5,
        'left' => 0,
        'parent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

$multiErrorOldTasks = array(
    4 => (object)array(
        'id' => 4,
        'project' => 1,
        'execution' => 101,
        'status' => 'wait',
        'estimate' => 8,
        'consumed' => 0,
        'left' => 8,
        'parent' => 0,
        'mode' => '',
        'isParent' => 0,
        'estStarted' => '2024-01-01',
        'deadline' => '2024-01-10'
    )
);

// 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->checkBatchEditTaskTest($normalTasks, $normalOldTasks)) && p() && e('1'); // 步骤1：正常情况
r($taskTest->checkBatchEditTaskTest($negativeEstimateTasks, $normalOldTasks)) && p('estimate[1]') && e('『预估』不能为负数'); // 步骤2：负数预估工时
r($taskTest->checkBatchEditTaskTest($doingNoLeftTasks, $doingOldTasks)) && p('left[2]') && e('剩余工时不能为空，当任务为『进行中』时。'); // 步骤3：doing状态left为空
r($taskTest->checkBatchEditTaskTest($doneNoConsumedTasks, $doneOldTasks)) && p('consumed[3]') && e('『消耗工时』不能为空'); // 步骤4：done状态consumed为0
r($taskTest->checkBatchEditTaskTest($multiErrorTasks, $multiErrorOldTasks)) && p('estimate[4],consumed[4],left[4]') && e('『预估』不能为负数,『消耗工时』不能为负数,剩余工时不能为空，当任务为『进行中』时。'); // 步骤5：多种错误组合