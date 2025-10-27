#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForClose();
timeout=0
cid=0

- 执行taskZenTest模块的buildTaskForCloseTest方法，参数是$oldTask1 属性closedReason @done
- 执行taskZenTest模块的buildTaskForCloseTest方法，参数是$oldTask2 属性closedReason @cancel
- 执行taskZenTest模块的buildTaskForCloseTest方法，参数是$oldTask3 属性closedReason @
- 执行taskZenTest模块的buildTaskForCloseTest方法，参数是$oldTask4 
 - 属性status @closed
 - 属性assignedTo @closed
- 执行taskZenTest模块的buildTaskForCloseTest方法，参数是$oldTask5 
 - 属性id @5
 - 属性status @closed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-7');
$task->name->range('测试任务1,测试任务2,测试任务3,关闭任务4,完成任务5,任务6,任务7');
$task->status->range('done{2},cancel{2},doing{1},wait{1},pause{1}');
$task->consumed->range('1{3},2{2},0{2}');
$task->left->range('0{3},1{2},2{2}');
$task->assignedTo->range('admin{2},user1{2},user2{2},closed{1}');
$task->openedBy->range('admin{3},user1{2},user2{2}');
$task->execution->range('1{7}');
$task->project->range('1{7}');
$task->type->range('devel{3},test{2},design{1},affair{1}');
$task->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：关闭已完成任务（状态done）
$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->status = 'done';
$_POST = array('closedReason' => '', 'comment' => '任务关闭', 'uid' => '');
r($taskZenTest->buildTaskForCloseTest($oldTask1)) && p('closedReason') && e('done');

// 步骤2：关闭已取消任务（状态cancel）
$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->status = 'cancel';
$_POST = array('closedReason' => '', 'comment' => '取消关闭', 'uid' => '');
r($taskZenTest->buildTaskForCloseTest($oldTask2)) && p('closedReason') && e('cancel');

// 步骤3：关闭进行中任务（状态doing）
$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->status = 'doing';
$_POST = array('closedReason' => 'bydesign', 'comment' => '按设计关闭', 'uid' => '');
r($taskZenTest->buildTaskForCloseTest($oldTask3)) && p('closedReason') && e('');

// 步骤4：验证关闭后状态设置
$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->status = 'wait';
$_POST = array('closedReason' => '', 'comment' => '验证状态', 'uid' => '');
r($taskZenTest->buildTaskForCloseTest($oldTask4)) && p('status,assignedTo') && e('closed,closed');

// 步骤5：验证任务ID正确设置
$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->status = 'wait';
$_POST = array('closedReason' => '', 'comment' => '验证ID', 'uid' => '');
r($taskZenTest->buildTaskForCloseTest($oldTask5)) && p('id,status') && e('5,closed');