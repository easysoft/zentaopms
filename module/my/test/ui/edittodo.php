#!/usr/bin/env php
<?php

/**

title=编辑待办测试
timeout=0

- 编辑列表第一个待办，编辑成功
 - 测试结果 @编辑待办成功
 - 最终测试状态 @SUCCESS
- 开始列表第一个待办，状态修改成功
 - 测试结果 @开始待办成功
 - 最终测试状态 @SUCCESS
- 完成列表第一个待办，状态修改成功
 - 测试结果 @完成待办成功
 - 最终测试状态 @SUCCESS
- 关闭列表第一个待办，状态修改成功
 - 测试结果 @关闭待办成功
 - 最终测试状态 @SUCCESS
- 激活列表第一个待办，状态修改成功
 - 测试结果 @激活待办成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/edittodo.ui.class.php';

$todo = zenData('todo');
$todo->id->range('1-5');
$todo->account->range('admin');
$todo->type->range('custom');
$todo->name->range('待办A,待办B,待办C,待办D,待办E');
$todo->status->range('wait,doing,done,closed,wait');
$todo->assignedTo->range('admin');
$todo->assignedBy->range('admin');
$todo->gen(5);

$tester = new addTodoTester();
$tester->login();

$todoTitle = new stdClass();
$todoTitle->name = '待办test01';

$todoStatus = new stdClass();
$todoStatus->doing    = '进行中';
$todoStatus->done     = '已完成';
$todoStatus->waiting  = '未开始';
$todoStatus->close    = '已关闭';

r($tester->editTodo($todoTitle, $todoStatus)) && p('message,status') && e('编辑待办成功，SUCCESS'); //编辑待办，添加成功
r($tester->startTodo($todoStatus))            && p('message,status') && e('开始待办成功，SUCCESS'); //开始待办，添加成功
r($tester->finishTodo($todoStatus))           && p('message,status') && e('完成待办成功，SUCCESS'); //完成待办，添加成功
r($tester->closeTodo($todoStatus))            && p('message,status') && e('关闭待办成功，SUCCESS'); //关闭待办，添加成功
r($tester->activateTodo($todoStatus))         && p('message,status') && e('激活待办成功，SUCCESS'); //激活待办，添加成功
