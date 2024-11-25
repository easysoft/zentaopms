#!/usr/bin/env php
<?php

/**

title=编辑待办测试
timeout=0

- 编辑列表第一个待办，编辑成功
 - 测试结果 @编辑待办成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/edittodo.ui.class.php';

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
$todoStatus->doing = '进行中';
$todoStatus->done  = '已完成';
