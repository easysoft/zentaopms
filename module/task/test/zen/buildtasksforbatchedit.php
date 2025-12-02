#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTasksForBatchEdit();
timeout=0
cid=18915

- 步骤1：正常批量编辑任务数据，名称变化导致version递增
 - 第1条的name属性 @任务1修改
 - 第1条的version属性 @2
- 步骤2：任务状态变为closed时处理assignedTo第2条的assignedTo属性 @closed
- 步骤3：任务story变化时更新storyVersion第3条的storyVersion属性 @1
- 步骤4：任务指派人为空时的处理第4条的assignedTo属性 @~~
- 步骤5：任务consumed为负数时的处理（保持负数）第5条的consumed属性 @-1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('user')->gen(5);
zenData('story')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 准备测试数据
// 创建旧任务数据
$oldTask1 = new stdClass();
$oldTask1->id = 1;
$oldTask1->project = 1;
$oldTask1->execution = 1;
$oldTask1->parent = 0;
$oldTask1->isParent = false;
$oldTask1->name = '任务1';
$oldTask1->assignedTo = 'admin';
$oldTask1->assignedDate = '2023-01-01 00:00:00';
$oldTask1->version = 1;
$oldTask1->consumed = 0;
$oldTask1->story = 0;
$oldTask1->storyVersion = 1;
$oldTask1->estStarted = '2023-01-01';
$oldTask1->deadline = '2023-01-10';
$oldTask1->status = 'wait';
$oldTask1->mode = '';
$oldTask1->estimate = 5;
$oldTask1->left = 5;

$oldTask2 = new stdClass();
$oldTask2->id = 2;
$oldTask2->project = 1;
$oldTask2->execution = 1;
$oldTask2->parent = 0;
$oldTask2->isParent = false;
$oldTask2->name = '任务2';
$oldTask2->assignedTo = 'user1';
$oldTask2->assignedDate = '2023-01-01 00:00:00';
$oldTask2->version = 1;
$oldTask2->consumed = 2;
$oldTask2->story = 1;
$oldTask2->storyVersion = 1;
$oldTask2->estStarted = '2023-01-01';
$oldTask2->deadline = '2023-01-10';
$oldTask2->status = 'doing';
$oldTask2->mode = '';
$oldTask2->estimate = 5;
$oldTask2->left = 3;

$oldTask3 = new stdClass();
$oldTask3->id = 3;
$oldTask3->project = 1;
$oldTask3->execution = 1;
$oldTask3->parent = 0;
$oldTask3->isParent = false;
$oldTask3->name = '任务3';
$oldTask3->assignedTo = 'user2';
$oldTask3->assignedDate = '2023-01-01 00:00:00';
$oldTask3->version = 1;
$oldTask3->consumed = 4;
$oldTask3->story = 2;
$oldTask3->storyVersion = 1;
$oldTask3->estStarted = '2023-01-01';
$oldTask3->deadline = '2023-01-10';
$oldTask3->status = 'done';
$oldTask3->mode = '';
$oldTask3->estimate = 5;
$oldTask3->left = 1;

$oldTask4 = new stdClass();
$oldTask4->id = 4;
$oldTask4->project = 1;
$oldTask4->execution = 1;
$oldTask4->parent = 0;
$oldTask4->isParent = false;
$oldTask4->name = '任务4';
$oldTask4->assignedTo = 'admin';
$oldTask4->assignedDate = '2023-01-01 00:00:00';
$oldTask4->version = 1;
$oldTask4->consumed = 1;
$oldTask4->story = 0;
$oldTask4->storyVersion = 1;
$oldTask4->estStarted = '2023-01-01';
$oldTask4->deadline = '2023-01-10';
$oldTask4->status = 'doing';
$oldTask4->mode = '';
$oldTask4->estimate = 5;
$oldTask4->left = 4;

$oldTask5 = new stdClass();
$oldTask5->id = 5;
$oldTask5->project = 1;
$oldTask5->execution = 1;
$oldTask5->parent = 0;
$oldTask5->isParent = false;
$oldTask5->name = '任务5';
$oldTask5->assignedTo = 'user1';
$oldTask5->assignedDate = '2023-01-01 00:00:00';
$oldTask5->version = 1;
$oldTask5->consumed = 3;
$oldTask5->story = 3;
$oldTask5->storyVersion = 1;
$oldTask5->estStarted = '2023-01-01';
$oldTask5->deadline = '2023-01-10';
$oldTask5->status = 'wait';
$oldTask5->mode = '';
$oldTask5->estimate = 5;
$oldTask5->left = 2;

$oldTasks = array(
    1 => $oldTask1,
    2 => $oldTask2,
    3 => $oldTask3,
    4 => $oldTask4,
    5 => $oldTask5
);

// 创建新任务数据（批量编辑后的数据）
$newTask1 = new stdClass();
$newTask1->id = 1;
$newTask1->name = '任务1修改';
$newTask1->assignedTo = 'user1';
$newTask1->status = 'doing';
$newTask1->consumed = 2;
$newTask1->story = 0;
$newTask1->estStarted = '2023-01-02';
$newTask1->deadline = '2023-01-12';
$newTask1->estimate = 5;
$newTask1->left = 3;
$newTask1->parent = 0;
$newTask1->closedReason = '';

$newTask2 = new stdClass();
$newTask2->id = 2;
$newTask2->name = '任务2';
$newTask2->assignedTo = 'user1';
$newTask2->status = 'closed';
$newTask2->consumed = 1;
$newTask2->story = 1;
$newTask2->estStarted = '2023-01-01';
$newTask2->deadline = '2023-01-10';
$newTask2->estimate = 5;
$newTask2->left = 0;
$newTask2->parent = 0;
$newTask2->closedReason = '';

$newTask3 = new stdClass();
$newTask3->id = 3;
$newTask3->name = '任务3';
$newTask3->assignedTo = 'user2';
$newTask3->status = 'closed';
$newTask3->consumed = 2;
$newTask3->story = 4;
$newTask3->estStarted = '2023-01-01';
$newTask3->deadline = '2023-01-10';
$newTask3->estimate = 5;
$newTask3->left = 0;
$newTask3->parent = 0;
$newTask3->closedReason = '';

$newTask4 = new stdClass();
$newTask4->id = 4;
$newTask4->name = '任务4';
$newTask4->assignedTo = '';
$newTask4->status = 'wait';
$newTask4->consumed = 0;
$newTask4->story = 0;
$newTask4->estStarted = '2023-01-01';
$newTask4->deadline = '2023-01-10';
$newTask4->estimate = 5;
$newTask4->left = 5;
$newTask4->parent = 0;
$newTask4->closedReason = '';

$newTask5 = new stdClass();
$newTask5->id = 5;
$newTask5->name = '任务5';
$newTask5->assignedTo = 'user1';
$newTask5->status = 'doing';
$newTask5->consumed = -1;
$newTask5->story = 3;
$newTask5->estStarted = '2023-01-01';
$newTask5->deadline = '2023-01-10';
$newTask5->estimate = 5;
$newTask5->left = 3;
$newTask5->parent = 0;
$newTask5->closedReason = '';

$taskData1 = array(1 => $newTask1);
$taskData2 = array(2 => $newTask2);
$taskData3 = array(3 => $newTask3);
$taskData4 = array(4 => $newTask4);
$taskData5 = array(5 => $newTask5);

// 5. 强制要求：必须包含至少5个测试步骤
r($taskTest->buildTasksForBatchEditTest($taskData1, $oldTasks)) && p('1:name,version') && e('任务1修改,2'); // 步骤1：正常批量编辑任务数据，名称变化导致version递增
r($taskTest->buildTasksForBatchEditTest($taskData2, $oldTasks)) && p('2:assignedTo') && e('closed'); // 步骤2：任务状态变为closed时处理assignedTo
r($taskTest->buildTasksForBatchEditTest($taskData3, $oldTasks)) && p('3:storyVersion') && e('1'); // 步骤3：任务story变化时更新storyVersion
r($taskTest->buildTasksForBatchEditTest($taskData4, $oldTasks)) && p('4:assignedTo') && e('~~'); // 步骤4：任务指派人为空时的处理
r($taskTest->buildTasksForBatchEditTest($taskData5, $oldTasks)) && p('5:consumed') && e('-1'); // 步骤5：任务consumed为负数时的处理（保持负数）