#!/usr/bin/env php
<?php

/**

title=测试 repoModel::startTask();
timeout=0
cid=18106

- 步骤1：正常启动等待任务属性status @1
- 步骤2：启动剩余时间为0的任务
 - 属性status @1
 - 属性finishedBy @admin
- 步骤3：启动另一个等待任务属性status @1
- 步骤4：无效任务ID @0
- 步骤5：验证工作记录创建属性effort_created @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('1');
$task->name->range('任务{1-10}');
$task->status->range('wait{8},doing{2}');
$task->consumed->range('0{5},2{3},4{2}');
$task->left->range('8{3},0{2},4{5}');
$task->openedBy->range('admin');
$task->assignedTo->range('user1{5},user2{5}');
$task->mode->range('linear{5},[]{5}');
$task->gen(10);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->password->range('123456');
$user->realname->range('管理员,用户1,用户2');
$user->gen(3);

$action = zenData('action');
$action->id->range('1-10');
$action->objectType->range('task');
$action->objectID->range('1-10');
$action->action->range('started{5},finished{5}');
$action->actor->range('admin');
$action->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($repoTest->startTaskTest(1, array('left' => 6, 'consumed' => 2))) && p('status') && e('1'); // 步骤1：正常启动等待任务
r($repoTest->startTaskTest(4, array('left' => 0, 'consumed' => 8))) && p('status,finishedBy') && e('1,admin'); // 步骤2：启动剩余时间为0的任务
r($repoTest->startTaskTest(2, array('left' => 4, 'consumed' => 2))) && p('status') && e('1'); // 步骤3：启动另一个等待任务
r($repoTest->startTaskTest(999, array('left' => 4, 'consumed' => 2))) && p() && e('0'); // 步骤4：无效任务ID
r($repoTest->startTaskTest(3, array('left' => 5, 'consumed' => 3))) && p('effort_created') && e('1'); // 步骤5：验证工作记录创建