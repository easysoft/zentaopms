#!/usr/bin/env php
<?php

/**

title=测试 todoTao::updateRow();
timeout=0
cid=19283

- 步骤1：正常更新custom类型待办 @1
- 步骤2：正常更新task类型待办 @1
- 步骤3：更新没有名称的custom待办 @0
- 步骤4：更新bug类型待办缺少objectID @0
- 步骤5：更新不存在的待办ID @0
- 步骤6：重复正常更新测试 @1
- 步骤7：story类型待办更新测试 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 2. zendata数据准备
zendata('todo')->loadYaml('todo_updaterow', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTest();

// 5. 准备测试数据对象
$validCustomTodo = new stdclass();
$validCustomTodo->account = 'admin';
$validCustomTodo->date = date('Y-m-d');
$validCustomTodo->begin = '1000';
$validCustomTodo->end = '1400';
$validCustomTodo->type = 'custom';
$validCustomTodo->name = '更新后的待办名字';
$validCustomTodo->desc = '更新后的待办描述';
$validCustomTodo->status = 'doing';
$validCustomTodo->pri = 2;

$validTaskTodo = clone $validCustomTodo;
$validTaskTodo->type = 'task';
$validTaskTodo->name = '更新后的任务待办';
$validTaskTodo->objectID = 1;

$invalidCustomTodoNoName = clone $validCustomTodo;
$invalidCustomTodoNoName->name = '';

$invalidBugTodoNoObjectID = clone $validCustomTodo;
$invalidBugTodoNoObjectID->type = 'bug';
$invalidBugTodoNoObjectID->name = '更新后的BUG待办';
$invalidBugTodoNoObjectID->objectID = 0;

$validStoryTodo = clone $validCustomTodo;
$validStoryTodo->type = 'story';
$validStoryTodo->name = '更新后的需求待办';
$validStoryTodo->objectID = 1;

$emptyFieldsTodo = new stdclass();
$emptyFieldsTodo->name = '仅更新名称';

$boundaryTodo = clone $validCustomTodo;
$boundaryTodo->type = 'epic';
$boundaryTodo->name = '史诗待办更新';
$boundaryTodo->objectID = 999999;

// 6. 执行测试步骤（必须至少5个）
global $tester;
r($tester->todo->updateRow(1, $validCustomTodo)) && p() && e('1'); // 步骤1：正常更新custom类型待办
r($tester->todo->updateRow(2, $validTaskTodo)) && p() && e('1'); // 步骤2：正常更新task类型待办
r($tester->todo->updateRow(3, $invalidCustomTodoNoName)) && p() && e('0'); // 步骤3：更新没有名称的custom待办
r($tester->todo->updateRow(4, $invalidBugTodoNoObjectID)) && p() && e('0'); // 步骤4：更新bug类型待办缺少objectID
r($tester->todo->updateRow(999, $validCustomTodo)) && p() && e('0'); // 步骤5：更新不存在的待办ID
r($tester->todo->updateRow(5, $validCustomTodo)) && p() && e('1'); // 步骤6：重复正常更新测试
r($tester->todo->updateRow(6, $validStoryTodo)) && p() && e('1'); // 步骤7：story类型待办更新测试