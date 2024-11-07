#!/usr/bin/env php
<?php

/**

title=添加待办测试
timeout=0

*/
chdir(__DIR__);
include '../lib/addtodo.ui.class.php';

$todo = zenData('todo');
$todo->id->range('1-5');
$todo->account->range('admin');
$todo->type->range('custom');
$todo->name->range('待办A,待办B,待办C,待办D,待办E');
$todo->status->range('wait,doing,done,closed,wait');
$todo->assignedTo->range('admin');
$todo->assignedBy->range('admin');
