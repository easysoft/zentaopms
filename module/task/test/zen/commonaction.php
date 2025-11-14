#!/usr/bin/env php
<?php

/**

title=测试 taskZen::commonAction();
timeout=0
cid=18927

- 步骤1：正常任务ID测试
 - 属性success @1
 - 属性hasTask @1
 - 属性hasExecution @1
 - 属性hasMembers @1
 - 属性hasActions @1
- 步骤2：带vision参数测试
 - 属性success @1
 - 属性hasTask @1
 - 属性vision @rnd
 - 属性hasExecution @1
- 步骤3：无效任务ID测试
 - 属性success @1
 - 属性hasTask @0
 - 属性hasExecution @0
- 步骤4：边界值任务ID测试（ID为0）
 - 属性success @1
 - 属性hasTask @0
 - 属性hasExecution @0
- 步骤5：负数任务ID测试
 - 属性success @1
 - 属性hasTask @0
 - 属性hasExecution @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->execution->range('1-5');
$task->project->range('1-3');
$task->status->range('wait{3},doing{4},done{3}');
$task->assignedTo->range('admin{5},user1{3},user2{2}');
$task->type->range('devel{7},test{2},design{1}');
$task->pri->range('1{3},2{4},3{3}');
$task->estimate->range('1-40:2');
$task->consumed->range('0-20:2');
$task->left->range('0-20:2');
$task->gen(10);

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->type->range('sprint{3},stage{2}');
$execution->status->range('wait{2},doing{3}');
$execution->parent->range('1-3');
$execution->project->range('1-3');
$execution->gen(5);

$user = zenData('user');
$user->id->range('1-15');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9,user10,user11,user12,user13,user14');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9,用户10,用户11,用户12,用户13,用户14');
$user->role->range('admin{1},dev{7},qa{4},pm{3}');
$user->deleted->range('0{14},1{1}');
$user->gen(15);

$team = zenData('team');
$team->root->range('1-5');
$team->type->range('execution{15},project{10}');
$team->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8');
$team->role->range('admin{3},dev{10},qa{7},pm{5}');
$team->gen(25);

$action = zenData('action');
$action->id->range('1-20');
$action->objectType->range('task{20}');
$action->objectID->range('1-10');
$action->action->range('opened{5},started{5},finished{5},closed{5}');
$action->actor->range('admin{10},user1{10}');
$action->date->range(date('Y-m-d H:i:s', time() - 3600 * 24), date('Y-m-d H:i:s'))->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$action->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($taskZenTest->commonActionTest(1)) && p('success,hasTask,hasExecution,hasMembers,hasActions') && e('1,1,1,1,1'); // 步骤1：正常任务ID测试
r($taskZenTest->commonActionTest(2, 'rnd')) && p('success,hasTask,vision,hasExecution') && e('1,1,rnd,1'); // 步骤2：带vision参数测试
r($taskZenTest->commonActionTest(999)) && p('success,hasTask,hasExecution') && e('1,0,0'); // 步骤3：无效任务ID测试
r($taskZenTest->commonActionTest(0)) && p('success,hasTask,hasExecution') && e('1,0,0'); // 步骤4：边界值任务ID测试（ID为0）
r($taskZenTest->commonActionTest(-1)) && p('success,hasTask,hasExecution') && e('1,0,0'); // 步骤5：负数任务ID测试