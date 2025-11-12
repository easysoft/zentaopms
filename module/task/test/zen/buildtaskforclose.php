#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForClose();
timeout=0
cid=0

- 步骤1：已完成任务关闭情况
 - 属性status @closed
 - 属性closedReason @done
 - 属性closedBy @admin
- 步骤2：已取消任务关闭情况
 - 属性status @closed
 - 属性closedReason @cancel
 - 属性assignedTo @closed
- 步骤3：进行中任务关闭情况
 - 属性status @closed
 - 属性closedBy @admin
 - 属性assignedTo @closed
- 步骤4：等待中任务关闭情况
 - 属性status @closed
 - 属性closedBy @admin
 - 属性lastEditedBy @admin
- 步骤5：验证任务ID和关闭状态正确设置
 - 属性id @5
 - 属性status @closed
 - 属性closedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('1');
$task->name->range('任务{1-10}');
$task->type->range('devel');
$task->status->range('done{2},cancel{2},doing{3},wait{3}');
$task->assignedTo->range('user1,user2,admin');
$task->openedBy->range('admin');
$task->left->range('0,1,2,3,5');
$task->consumed->range('0,1,2,3,5');
$task->estimate->range('3,4,5,6,8');
$task->pri->range('1,2,3');
$task->gen(10);

// 准备用户数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,test');
$user->realname->range('管理员,用户1,用户2,用户3,测试用户');
$user->role->range('admin,dev,qa,pm,test');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 创建模拟任务对象进行测试
$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->status = 'done';
$oldTask1->assignedTo = 'user1';
$oldTask1->openedBy = 'admin';
r($taskZenTest->buildTaskForCloseTest($oldTask1)) && p('status,closedReason,closedBy') && e('closed,done,admin'); // 步骤1：已完成任务关闭情况

$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->status = 'cancel';
$oldTask2->assignedTo = 'user2';
$oldTask2->openedBy = 'admin';
r($taskZenTest->buildTaskForCloseTest($oldTask2)) && p('status,closedReason,assignedTo') && e('closed,cancel,closed'); // 步骤2：已取消任务关闭情况

$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->status = 'doing';
$oldTask3->assignedTo = 'admin';
$oldTask3->openedBy = 'admin';
r($taskZenTest->buildTaskForCloseTest($oldTask3)) && p('status,closedBy,assignedTo') && e('closed,admin,closed'); // 步骤3：进行中任务关闭情况

$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->status = 'wait';
$oldTask4->assignedTo = 'user1';
$oldTask4->openedBy = 'user2';
r($taskZenTest->buildTaskForCloseTest($oldTask4)) && p('status,closedBy,lastEditedBy') && e('closed,admin,admin'); // 步骤4：等待中任务关闭情况

$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->status = 'doing';
$oldTask5->assignedTo = 'user3';
$oldTask5->openedBy = 'admin';
r($taskZenTest->buildTaskForCloseTest($oldTask5)) && p('id,status,closedBy') && e('5,closed,admin'); // 步骤5：验证任务ID和关闭状态正确设置