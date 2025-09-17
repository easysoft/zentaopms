#!/usr/bin/env php
<?php

/**

title=待办详情页检查测试
timeout=0
cid=2

- 执行tester模块的viewTodo方法
 - 测试结果 @待办详情页信息正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/viewtodo.ui.class.php';

$todo = zendata('todo');
$todo->id->range('1');
$todo->account->range('admin');
$todo->date->range('(-1M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$todo->type->range('custom');
$todo->name->range('待办1');
$todo->status->range('wait');
$todo->private->range('0');
$todo->assignedTo->range('admin');
$todo->vision->range('rnd');
$todo->gen(1);

$tester = new viewTodoTester();
$tester->login();

r($tester->viewTodo()) && p('message,status') && e('待办详情页信息正确,SUCCESS');

$tester->closeBrowser();
