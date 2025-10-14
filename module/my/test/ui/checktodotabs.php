#!/usr/bin/env php
<?php

/**

title=添加待办测试
timeout=0

- 添加一个待办，添加成功
 - 测试结果 @添加待办成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/checktodotabs.ui.class.php';

$todo = zenData('todo');
$todo->id->range('1-4');
$todo->account->range('admin');
$todo->type->range('custom');
$todo->name->range('待办A,待办B,待办C,待办D');
$todo->status->range('wait,doing,done,closed');
$todo->assignedTo->range('admin');
$todo->assignedBy->range('admin');
$todo->gen(4);

$tester = new addTodoTester();
$tester->login();

$todoTitle = new stdClass();
$todoTitle->wait  = '待办A';
$todoTitle->doing = '待办B';

r($tester->checkTodoTabs($todoTitle)) && p('message,status') && e('检查待办未完成标签成功，SUCCESS'); //检查待办未完成标签，显示正确
