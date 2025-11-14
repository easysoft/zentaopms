#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildEffortForStart();
timeout=0
cid=18904

- 步骤1：普通任务
 - 属性task @1
 - 属性consumed @5
 - 属性left @25
 - 属性work @开始任务测试
 - 属性account @admin
- 步骤2：团队任务有当前用户
 - 属性task @2
 - 属性consumed @10
 - 属性left @20
 - 属性work @团队任务开始
 - 属性account @admin
- 步骤3：团队任务无当前用户
 - 属性task @3
 - 属性consumed @14
 - 属性left @30
 - 属性work @非团队成员开始
 - 属性account @admin
- 步骤4：边界值零
 - 属性task @4
 - 属性consumed @0
 - 属性left @0
 - 属性account @admin
- 步骤5：缺少字段处理
 - 属性task @5
 - 属性consumed @-8
 - 属性left @15
 - 属性account @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('测试任务{10}');
$task->consumed->range('0-20:2');
$task->left->range('10-50:5');
$task->gen(10);

$team = zenData('taskteam');
$team->id->range('1-10');
$team->task->range('2,3');
$team->account->range('admin,user1,user2');
$team->consumed->range('5-15:2');
$team->left->range('10-30:5');
$team->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：普通任务的effort构建
$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->consumed = 10;
$oldTask1->team = array();
$task1 = new stdclass();
$task1->id = 1;
$task1->consumed = 15;
$task1->left = 25;
$task1->work = '开始任务测试';
r($taskTest->buildEffortForStartTest($oldTask1, $task1)) && p('task,consumed,left,work,account') && e('1,5,25,开始任务测试,admin'); // 步骤1：普通任务

// 步骤2：团队任务且当前用户在团队中的effort构建
$taskModel = $taskTest->tester->loadModel('task');
$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->consumed = 20;
$oldTask2->team = $taskModel->getTeamByTask(2);
$task2 = new stdclass();
$task2->id = 2;
$task2->consumed = 30;
$task2->left = 20;
$task2->work = '团队任务开始';
r($taskTest->buildEffortForStartTest($oldTask2, $task2)) && p('task,consumed,left,work,account') && e('2,10,20,团队任务开始,admin'); // 步骤2：团队任务有当前用户

// 步骤3：团队任务但当前用户不在团队中的effort构建
$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->consumed = 15;
$oldTask3->team = $taskModel->getTeamByTask(3);
$task3 = new stdclass();
$task3->id = 3;
$task3->consumed = 25;
$task3->left = 30;
$task3->work = '非团队成员开始';
r($taskTest->buildEffortForStartTest($oldTask3, $task3)) && p('task,consumed,left,work,account') && e('3,14,30,非团队成员开始,admin'); // 步骤3：团队任务无当前用户

// 步骤4：边界值测试（consumed和left为0）
$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->consumed = 0;
$oldTask4->team = array();
$task4 = new stdclass();
$task4->id = 4;
$task4->consumed = 0;
$task4->left = 0;
$task4->work = '';
r($taskTest->buildEffortForStartTest($oldTask4, $task4)) && p('task,consumed,left,account') && e('4,0,0,admin'); // 步骤4：边界值零

// 步骤5：异常输入测试（缺少部分字段）
$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->consumed = 8;
$oldTask5->team = array();
$task5 = new stdclass();
$task5->id = 5;
// 缺少consumed字段，应使用默认值0
$task5->left = 15;
$task5->work = '';
r($taskTest->buildEffortForStartTest($oldTask5, $task5)) && p('task,consumed,left,account') && e('5,-8,15,admin'); // 步骤5：缺少字段处理