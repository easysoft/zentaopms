#!/usr/bin/env php
<?php

/**

title=测试 todoTao::updateRow();
timeout=0
cid=19283

- 步骤1：正常更新custom类型待办 @1
- 步骤2：正常更新task类型待办 @1
- 步骤3：更新没有名称的custom待办，返回必填校验错误 @『待办名称』不能为空。
- 步骤4：更新bug类型待办且objectID为0，返回1 @1
- 步骤5：更新不存在的待办ID，返回1 @1
- 步骤6：重复正常更新测试 @1
- 步骤7：story类型待办更新测试 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
zendata('todo')->loadYaml('todo_updaterow', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTaoTest();

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
r($todoTest->updateRowTest(1, $validCustomTodo))          && p()        && e('1');                    // 步骤1：正常更新custom类型待办
r($todoTest->updateRowTest(2, $validTaskTodo))            && p()        && e('1');                    // 步骤2：正常更新task类型待办
r($todoTest->updateRowTest(3, $invalidCustomTodoNoName))  && p('name:0') && e('『待办名称』不能为空。'); // 步骤3：更新没有名称的custom待办
r($todoTest->updateRowTest(4, $invalidBugTodoNoObjectID)) && p()        && e('1');                    // 步骤4：更新bug类型待办且objectID为0
r($todoTest->updateRowTest(999, $validCustomTodo))        && p()        && e('1');                    // 步骤5：更新不存在的待办ID
r($todoTest->updateRowTest(5, $validCustomTodo))          && p()        && e('1');                    // 步骤6：重复正常更新测试
r($todoTest->updateRowTest(6, $validStoryTodo))           && p()        && e('1');                    // 步骤7：story类型待办更新测试
