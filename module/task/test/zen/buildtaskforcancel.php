#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForCancel();
timeout=0
cid=18907

- 步骤1：等待中任务取消情况
 - 属性status @cancel
 - 属性canceledBy @admin
 - 属性lastEditedBy @admin
- 步骤2：进行中任务取消情况
 - 属性status @cancel
 - 属性assignedTo @admin
 - 属性finishedBy @~~
- 步骤3：已完成任务取消情况
 - 属性status @cancel
 - 属性canceledBy @admin
 - 属性lastEditedBy @admin
- 步骤4：暂停任务取消情况
 - 属性status @cancel
 - 属性assignedTo @admin
 - 属性finishedBy @~~
- 步骤5：验证取消日期和操作者设置正确
 - 属性status @cancel
 - 属性canceledBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（简化数据结构）
$task = zenData('task');
$task->id->range('1-5');
$task->project->range('1');
$task->execution->range('1');
$task->name->range('任务{1-5}');
$task->type->range('devel');
$task->status->range('wait{2},doing{2},done{1}');
$task->assignedTo->range('user1,user2,admin');
$task->openedBy->range('admin');
$task->left->range('0,1,2,3,5');
$task->consumed->range('0,1,2,3,5');
$task->estimate->range('3,4,5,6,8');
$task->pri->range('1,2,3');
$task->gen(5);

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
$oldTask1->status = 'wait';
$oldTask1->assignedTo = 'user1';
$oldTask1->openedBy = 'admin';
$oldTask1->finishedDate = null;
r($taskZenTest->buildTaskForCancelTest($oldTask1)) && p('status,canceledBy,lastEditedBy') && e('cancel,admin,admin'); // 步骤1：等待中任务取消情况

$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->status = 'doing';
$oldTask2->assignedTo = 'user2';
$oldTask2->openedBy = 'admin';
$oldTask2->finishedDate = null;
r($taskZenTest->buildTaskForCancelTest($oldTask2)) && p('status,assignedTo,finishedBy') && e('cancel,admin,~~'); // 步骤2：进行中任务取消情况

$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->status = 'done';
$oldTask3->assignedTo = 'user1';
$oldTask3->openedBy = 'admin';
$oldTask3->finishedDate = '2024-01-15 18:00:00';
r($taskZenTest->buildTaskForCancelTest($oldTask3)) && p('status,canceledBy,lastEditedBy') && e('cancel,admin,admin'); // 步骤3：已完成任务取消情况

$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->status = 'pause';
$oldTask4->assignedTo = 'admin';
$oldTask4->openedBy = 'admin';
$oldTask4->finishedDate = null;
r($taskZenTest->buildTaskForCancelTest($oldTask4)) && p('status,assignedTo,finishedBy') && e('cancel,admin,~~'); // 步骤4：暂停任务取消情况

$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->status = 'wait';
$oldTask5->assignedTo = 'user3';
$oldTask5->openedBy = 'user1';
$oldTask5->finishedDate = null;
r($taskZenTest->buildTaskForCancelTest($oldTask5)) && p('status,canceledBy') && e('cancel,admin'); // 步骤5：验证取消日期和操作者设置正确