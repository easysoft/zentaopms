#!/usr/bin/env php
<?php

/**

title=测试 taskZen::processTaskByStatus();
timeout=0
cid=18940

- 步骤1：正常情况
 - 属性status @done
 - 属性left @0
 - 属性finishedBy @admin
 - 属性canceledBy @~~
- 步骤2：边界值
 - 属性status @cancel
 - 属性canceledBy @admin
 - 属性assignedTo @user1
 - 属性finishedBy @~~
- 步骤3：异常输入
 - 属性status @closed
 - 属性closedBy @admin
- 步骤4：权限验证
 - 属性status @done
 - 属性finishedBy @admin
- 步骤5：业务规则
 - 属性status @wait
 - 属性canceledBy @~~
 - 属性finishedBy @~~
 - 属性closedBy @~~
 - 属性canceledDate @~~
 - 属性finishedDate @~~
 - 属性closedDate @~~
 - 属性closedReason @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->name->range('任务1,任务2,任务3,任务4,任务5{5}');
$table->status->range('wait,doing,done,cancel{2},pause{4}');
$table->openedBy->range('admin,user1,user2{3},tester{5}');
$table->assignedTo->range('admin{3},user1{3},user2{4}');
$table->estimate->range('5,8,10,15,20{5}');
$table->consumed->range('0{3},3{3},8{4}');
$table->left->range('5{2},0{3},2{5}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：任务状态从 wait 改为 done，验证完成相关字段设置
$task = new stdClass();
$task->status = 'done';
$task->left = 5;
$task->consumed = 0;
$task->estimate = 10;
$task->finishedBy = '';
$task->finishedDate = '';
$task->canceledBy = '';
$task->canceledDate = '';
$oldTask = new stdClass();
$oldTask->status = 'wait';
$oldTask->openedBy = 'admin';
$oldTask->finishedBy = '';
$oldTask->finishedDate = '';
r($taskTest->processTaskByStatusTest($task, $oldTask)) && p('status,left,finishedBy,canceledBy') && e('done,0,admin,~~'); // 步骤1：正常情况

// 步骤2：任务状态从 doing 改为 cancel，验证取消相关字段设置
$task = new stdClass();
$task->status = 'cancel';
$task->assignedTo = '';
$task->assignedDate = '';
$task->canceledBy = '';
$task->canceledDate = '';
$task->finishedBy = '';
$task->finishedDate = '';
$oldTask = new stdClass();
$oldTask->status = 'doing';
$oldTask->openedBy = 'user1';
$oldTask->canceledBy = '';
$oldTask->canceledDate = '';
r($taskTest->processTaskByStatusTest($task, $oldTask)) && p('status,canceledBy,assignedTo,finishedBy') && e('cancel,admin,user1,~~'); // 步骤2：边界值

// 步骤3：任务状态从 done 改为 closed，验证关闭相关字段设置
$task = new stdClass();
$task->status = 'closed';
$task->closedBy = '';
$task->closedDate = '';
$oldTask = new stdClass();
$oldTask->status = 'done';
$oldTask->closedBy = '';
$oldTask->closedDate = '';
r($taskTest->processTaskByStatusTest($task, $oldTask)) && p('status,closedBy') && e('closed,admin'); // 步骤3：异常输入

// 步骤4：任务状态为 wait 且有消耗工时但剩余时间为0，验证自动变为 done
$task = new stdClass();
$task->status = 'wait';
$task->consumed = 8;
$task->left = 0;
$task->finishedBy = '';
$task->finishedDate = '';
$oldTask = new stdClass();
$oldTask->status = 'doing';
$oldTask->left = 5;
r($taskTest->processTaskByStatusTest($task, $oldTask)) && p('status,finishedBy') && e('done,admin'); // 步骤4：权限验证

// 步骤5：任务状态从 cancel 改为 wait，验证清空状态字段
$task = new stdClass();
$task->status = 'wait';
$task->consumed = 0;
$task->left = 5;
$task->estimate = 5;
$task->canceledBy = 'admin';
$task->finishedBy = '';
$task->closedBy = '';
$task->canceledDate = '2023-01-01';
$task->finishedDate = '';
$task->closedDate = '';
$task->closedReason = 'test';
$oldTask = new stdClass();
$oldTask->status = 'cancel';
$oldTask->left = 5;
r($taskTest->processTaskByStatusTest($task, $oldTask)) && p('status,canceledBy,finishedBy,closedBy,canceledDate,finishedDate,closedDate,closedReason') && e('wait,~~,~~,~~,~~,~~,~~,~~'); // 步骤5：业务规则