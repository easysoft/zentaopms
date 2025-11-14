#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForStart();
timeout=0
cid=18912

- 步骤1：正常开始wait状态任务属性status @doing
- 步骤2：剩余工时为0自动完成属性status @done
- 步骤3：分配人变更时返回有效对象 @1
- 步骤4：普通任务开始属性status @doing
- 步骤5：验证失败返回错误消息属性message @"总计消耗"和"预计剩余"不能同时为0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（简化数据结构）
$task = zenData('task');
$task->id->range('1-5');
$task->project->range('1');
$task->execution->range('1');
$task->name->range('任务{1-5}');
$task->type->range('devel');
$task->status->range('wait{3},doing{1},done{1}');
$task->assignedTo->range('user1,user2,admin');
$task->openedBy->range('admin');
$task->left->range('0,1,2,3,5');
$task->consumed->range('0,1,2,3,5');
$task->estimate->range('3,4,5,6,8');
$task->pri->range('1,2,3');
$task->mode->range('linear');
$task->gen(5);

// 准备用户数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,test');
$user->realname->range('管理员,用户1,用户2,用户3,测试用户');
$user->role->range('admin,dev,qa,pm,test');
$user->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 模拟POST数据来测试表单数据处理
$_POST['consumed'] = '2';
$_POST['left'] = '3';
$_POST['assignedTo'] = 'user2';
$_POST['realStarted'] = '2024-01-15 09:00:00';
$_POST['uid'] = '';

// 5. 执行测试步骤
// 正常情况测试
$_POST['consumed'] = '2';
$_POST['left'] = '3';
$_POST['assignedTo'] = 'user2';
$_POST['realStarted'] = '2024-01-15 09:00:00';

$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->status = 'wait';
$oldTask1->assignedTo = 'user1';
$oldTask1->openedBy = 'admin';
$oldTask1->left = 5;
$oldTask1->consumed = 0;
$oldTask1->team = '';
r($taskTest->buildTaskForStartTest($oldTask1)) && p('status') && e('doing'); // 步骤1：正常开始wait状态任务

// 测试剩余工时为0自动完成
$_POST['left'] = '0';
$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->status = 'wait';
$oldTask2->assignedTo = 'user1';
$oldTask2->openedBy = 'admin';
$oldTask2->left = 0;
$oldTask2->consumed = 1;
$oldTask2->team = '';
$result2 = $taskTest->buildTaskForStartTest($oldTask2);
r($result2) && p('status') && e('done'); // 步骤2：剩余工时为0自动完成

// 测试分配人变更
$_POST['assignedTo'] = 'user3';
$_POST['left'] = '3';
$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->status = 'wait';
$oldTask3->assignedTo = 'user1';
$oldTask3->openedBy = 'admin';
$oldTask3->left = 3;
$oldTask3->consumed = 2;
$oldTask3->team = '';
$result3 = $taskTest->buildTaskForStartTest($oldTask3);
r($result3 !== false) && p('') && e('1'); // 步骤3：分配人变更时返回有效对象

// 测试正常任务开始
$_POST['assignedTo'] = 'user1';
$_POST['consumed'] = '4';
$_POST['left'] = '2';
$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->status = 'wait';
$oldTask4->assignedTo = 'user1';
$oldTask4->openedBy = 'admin';
$oldTask4->left = 2;
$oldTask4->consumed = 3;
$oldTask4->team = array();
$result4 = $taskTest->buildTaskForStartTest($oldTask4);
r($result4) && p('status') && e('doing'); // 步骤4：普通任务开始

// 模拟验证失败的情况：消耗和剩余都为0
$_POST['consumed'] = '0';
$_POST['left'] = '0';
$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->status = 'wait';
$oldTask5->assignedTo = 'user1';
$oldTask5->openedBy = 'admin';
$oldTask5->left = 5;
$oldTask5->consumed = 0;
$oldTask5->team = '';
$result5 = $taskTest->buildTaskForStartTest($oldTask5);
r($result5) && p('message') && e('"总计消耗"和"预计剩余"不能同时为0'); // 步骤5：验证失败返回错误消息