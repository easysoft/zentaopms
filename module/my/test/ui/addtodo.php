#!/usr/bin/env php
<?php

/**

title=添加待办测试
timeout=0

- 添加一个待办，添加成功
 - 测试结果 @添加待办成功
 - 最终测试状态 @SUCCESS
- 批量添加待办，添加成功
 - 测试结果 @todo-1 创建成功 todo-2 创建成功 todo-3 创建成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/addtodo.ui.class.php';

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
$todoTitle->name   = '待办test01';
$todoTitle->first  =  'todo-1';
$todoTitle->second =  'todo-2';
$todoTitle->third  =  'todo-3';

$todoStatus = new stdClass();
$todoStatus->doing = '进行中';
$todoStatus->done  = '已完成';

r($tester->addTodo($todoTitle, $todoStatus))      && p('message,status') && e('添加待办成功，SUCCESS'); //添加待办，添加成功
r($tester->batchAddTodo($todoTitle, $todoStatus)) && p('message,status') && e('批量添加待办成功，SUCCESS'); //批量添加待办，添加成功
