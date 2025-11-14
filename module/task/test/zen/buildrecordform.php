#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildRecordForm();
timeout=0
cid=18905

- 执行taskZenTest模块的buildRecordFormTest方法，参数是1, 'taskList', 'id_desc' 属性taskEffortFold @1
- 执行taskZenTest模块的buildRecordFormTest方法，参数是6, 'taskList', ''
 - 属性taskEffortFold @1
 - 属性orderBy @id_desc
- 执行taskZenTest模块的buildRecordFormTest方法，参数是3, 'taskList', 'order_desc' 属性orderBy @order_desc
- 执行taskZenTest模块的buildRecordFormTest方法，参数是2, 'taskList', 'id_desc'
 - 属性taskID @2
 - 属性from @taskList
 - 属性usersCount @10
- 执行taskZenTest模块的buildRecordFormTest方法，参数是3, 'taskList', 'id_asc'
 - 属性taskID @3
 - 属性orderBy @id_asc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('3');
$task->module->range('0');
$task->story->range('0');
$task->name->range('Task1,Task2,Task3,Task4,Task5,Task6,Task7,Task8,Task9,Task10');
$task->type->range('devel');
$task->mode->range('[]{5},linear{3},multi{2}');
$task->status->range('wait{3},doing{4},done{3}');
$task->assignedTo->range('admin{4},user1{3},user2{3}');
$task->openedBy->range('admin');
$task->openedDate->range('2024-11-01 00:00:00')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$task->deleted->range('0');
$task->gen(10);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-20');
$taskteam->task->range('6{4},7{4},8{4},9{4},10{4}');
$taskteam->account->range('admin,user1,user2,user3');
$taskteam->estimate->range('5-10:1');
$taskteam->consumed->range('0-5:1');
$taskteam->left->range('0-5:1');
$taskteam->status->range('wait{5},doing{10},done{5}');
$taskteam->order->range('1-20');
$taskteam->gen(20);

$effort = zenData('effort');
$effort->id->range('1-50');
$effort->objectType->range('task');
$effort->objectID->range('1{5},2{5},3{5},4{5},5{5},6{5},7{5},8{5},9{5},10{5}');
$effort->project->range('1');
$effort->execution->range('3');
$effort->account->range('admin{20},user1{15},user2{15}');
$effort->work->range('开发任务,测试任务,修复Bug,代码审查,文档编写');
$effort->vision->range('rnd');
$effort->date->range('2024-11-01 00:00:00:1D')->type('timestamp')->format('YYYY-MM-DD');
$effort->left->range('0-10:1');
$effort->consumed->range('1-8:1');
$effort->begin->range('0900,1000,1100,1300,1400,1500,1600');
$effort->end->range('1200,1300,1400,1600,1700,1800,1900');
$effort->order->range('0');
$effort->deleted->range('0');
$effort->gen(50);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('Admin,User1,User2,User3,User4,User5,User6,User7,User8,User9');
$user->role->range('admin,dev{4},qa{5}');
$user->password->range('1234567890');
$user->deleted->range('0');
$user->gen(10);

su('admin');

global $tester;
$tester->loadModel('task');
$taskZenTest = new taskZenTest();

r($taskZenTest->buildRecordFormTest(1, 'taskList', 'id_desc')) && p('taskEffortFold') && e('1');
r($taskZenTest->buildRecordFormTest(6, 'taskList', '')) && p('taskEffortFold;orderBy') && e('1;id_desc');
r($taskZenTest->buildRecordFormTest(3, 'taskList', 'order_desc')) && p('orderBy') && e('order_desc');
r($taskZenTest->buildRecordFormTest(2, 'taskList', 'id_desc')) && p('taskID;from;usersCount') && e('2;taskList;10');
r($taskZenTest->buildRecordFormTest(3, 'taskList', 'id_asc')) && p('taskID;orderBy') && e('3;id_asc');