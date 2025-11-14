#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForEdit();
timeout=0
cid=18910

- 步骤1:正常编辑任务
 - 属性id @1
 - 属性name @修改后的任务名称
 - 属性estimate @10
- 步骤2:状态变更为done
 - 属性status @done
 - 属性left @0
 - 属性finishedBy @admin
- 步骤3:状态变更为cancel
 - 属性status @cancel
 - 属性canceledBy @admin
 - 属性assignedTo @user1
- 步骤4:负数验证失败 @%s不能为负数
- 步骤5:并发编辑检查 @该记录可能已经被改动。请刷新页面重新编辑！

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('1');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->type->range('devel');
$task->status->range('wait{3},doing{3},done{2},cancel{2}');
$task->assignedTo->range('user1,user2,admin');
$task->openedBy->range('user1');
$task->left->range('5');
$task->consumed->range('3');
$task->estimate->range('8');
$task->pri->range('1,2,3');
$task->parent->range('0');
$task->mode->range('``');
$task->design->range('0');
$task->story->range('0');
$task->version->range('1');
$task->lastEditedDate->range('`2024-01-01 10:00:00`');
$task->estStarted->range('`2024-01-01`');
$task->deadline->range('`2024-12-31`');
$task->gen(10);

// 准备用户数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,test');
$user->realname->range('管理员,用户1,用户2,用户3,测试用户');
$user->role->range('admin,dev,qa,pm,test');
$user->gen(5);

// 准备project数据
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('project');
$project->model->range('scrum');
$project->taskDateLimit->range('off');
$project->gen(5);

// 准备story数据
$story = zenData('story');
$story->id->range('1-10');
$story->version->range('1-10');
$story->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
// 步骤1:正常编辑任务,修改任务名称和预计工时
$task1 = new stdclass();
$task1->id = 1;
$task1->name = '修改后的任务名称';
$task1->estimate = 10;
$task1->left = 5;
$task1->consumed = 3;
$task1->status = 'wait';
$task1->assignedTo = 'user1';
$task1->estStarted = '2024-01-01';
$task1->deadline = '2024-12-31';
$task1->story = false;
$task1->mode = '';
$task1->lastEditedDate = '2024-01-01 10:00:00';
r($taskZenTest->buildTaskForEditTest($task1)) && p('id,name,estimate') && e('1,修改后的任务名称,10'); // 步骤1:正常编辑任务

// 步骤2:任务状态变更为done,验证完成相关字段自动设置
$task2 = new stdclass();
$task2->id = 2;
$task2->name = '任务2';
$task2->estimate = 8;
$task2->left = 2;
$task2->consumed = 6;
$task2->status = 'done';
$task2->assignedTo = 'user2';
$task2->estStarted = '2024-01-01';
$task2->deadline = '2024-12-31';
$task2->story = false;
$task2->mode = '';
$task2->finishedBy = '';
$task2->finishedDate = '';
$task2->lastEditedDate = '2024-01-01 10:00:00';
r($taskZenTest->buildTaskForEditTest($task2)) && p('status,left,finishedBy') && e('done,0,admin'); // 步骤2:状态变更为done

// 步骤3:任务状态变更为cancel,验证取消相关字段自动设置
$task3 = new stdclass();
$task3->id = 3;
$task3->name = '任务3';
$task3->estimate = 8;
$task3->left = 5;
$task3->consumed = 3;
$task3->status = 'cancel';
$task3->assignedTo = 'user2';
$task3->estStarted = '2024-01-01';
$task3->deadline = '2024-12-31';
$task3->story = false;
$task3->mode = '';
$task3->canceledBy = '';
$task3->canceledDate = '';
$task3->lastEditedDate = '2024-01-01 10:00:00';
r($taskZenTest->buildTaskForEditTest($task3)) && p('status,canceledBy,assignedTo') && e('cancel,admin,user1'); // 步骤3:状态变更为cancel

// 步骤4:编辑时estimate为负数,应返回错误
$task4 = new stdclass();
$task4->id = 4;
$task4->name = '任务4';
$task4->estimate = -5;
$task4->left = 5;
$task4->consumed = 3;
$task4->status = 'wait';
$task4->assignedTo = 'user1';
$task4->estStarted = '2024-01-01';
$task4->deadline = '2024-12-31';
$task4->story = false;
$task4->mode = '';
$task4->lastEditedDate = '2024-01-01 10:00:00';
r($taskZenTest->buildTaskForEditTest($task4)) && p('0') && e('%s不能为负数'); // 步骤4:负数验证失败

// 步骤5:编辑时lastEditedDate不一致,检测并发编辑冲突
$task5 = new stdclass();
$task5->id = 5;
$task5->name = '任务5';
$task5->estimate = 8;
$task5->left = 5;
$task5->consumed = 3;
$task5->status = 'wait';
$task5->assignedTo = 'user1';
$task5->estStarted = '2024-01-01';
$task5->deadline = '2024-12-31';
$task5->story = false;
$task5->mode = '';
$task5->lastEditedDate = '2024-01-01 09:00:00';
r($taskZenTest->buildTaskForEditTest($task5)) && p('0') && e('该记录可能已经被改动。请刷新页面重新编辑！'); // 步骤5:并发编辑检查