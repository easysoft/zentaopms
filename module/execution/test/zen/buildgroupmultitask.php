#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildGroupMultiTask();
timeout=0
cid=16413

- 步骤1：assignedTo分组，期望2个分组（用户1和用户2） @2
- 步骤2：finishedBy分组，期望1个分组（用户3） @1
- 步骤3：空团队，期望0个分组 @0
- 步骤4：finishedBy分组且有剩余工时，期望2个分组（用户2和用户1） @2
- 步骤5：所有团队成员工时为0，期望2个分组 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

su('admin');

$executionZenTest = new executionZenTest();

// 测试数据
$users = array(
    'user1' => '用户1',
    'user2' => '用户2', 
    'user3' => '用户3'
);

// 测试1：正常assignedTo分组处理
$task1 = new stdClass();
$task1->id = 1;
$task1->name = '多人任务1';
$task1->assignedTo = 'user1';
$task1->finishedBy = 'user1';
$task1->estimate = 8;
$task1->consumed = 4;
$task1->left = 4;
$task1->status = 'doing';
$task1->team = array();

$team1 = new stdClass();
$team1->account = 'user1';
$team1->estimate = 4;
$team1->consumed = 2;
$team1->left = 2;
$task1->team[] = $team1;

$team2 = new stdClass();
$team2->account = 'user2';
$team2->estimate = 4;
$team2->consumed = 2;
$team2->left = 2;
$task1->team[] = $team2;

// 测试2：finishedBy分组，团队成员剩余工时为0
$task2 = new stdClass();
$task2->id = 2;
$task2->name = '多人任务2';
$task2->assignedTo = 'user2';
$task2->finishedBy = 'user2';
$task2->estimate = 8;
$task2->consumed = 8;
$task2->left = 0;
$task2->status = 'done';
$task2->team = array();

$team3 = new stdClass();
$team3->account = 'user3';
$team3->estimate = 8;
$team3->consumed = 8;
$team3->left = 0;
$task2->team[] = $team3;

// 测试3：空团队
$task3 = new stdClass();
$task3->id = 3;
$task3->name = '单人任务';
$task3->assignedTo = 'user3';
$task3->finishedBy = 'user3';
$task3->estimate = 8;
$task3->consumed = 8;
$task3->left = 0;
$task3->status = 'done';
$task3->team = array();

// 测试4：finishedBy分组且原任务有剩余工时
$task4 = new stdClass();
$task4->id = 4;
$task4->name = '多人任务4';
$task4->assignedTo = 'user1';
$task4->finishedBy = 'user1';
$task4->estimate = 10;
$task4->consumed = 8;
$task4->left = 2;
$task4->status = 'doing';
$task4->team = array();

$team4 = new stdClass();
$team4->account = 'user2';
$team4->estimate = 4;
$team4->consumed = 4;
$team4->left = 0;
$task4->team[] = $team4;

// 测试5：所有团队成员剩余工时为0
$task5 = new stdClass();
$task5->id = 5;
$task5->name = '多人任务5';
$task5->assignedTo = 'user1';
$task5->finishedBy = 'user1';
$task5->estimate = 12;
$task5->consumed = 12;
$task5->left = 0;
$task5->status = 'done';
$task5->team = array();

$team5a = new stdClass();
$team5a->account = 'user1';
$team5a->estimate = 6;
$team5a->consumed = 6;
$team5a->left = 0;
$task5->team[] = $team5a;

$team5b = new stdClass();
$team5b->account = 'user2';
$team5b->estimate = 6;
$team5b->consumed = 6;
$team5b->left = 0;
$task5->team[] = $team5b;

// 执行测试步骤
$result1 = $executionZenTest->buildGroupMultiTaskTest('assignedTo', $task1, $users, array());
$result2 = $executionZenTest->buildGroupMultiTaskTest('finishedBy', $task2, $users, array());
$result3 = $executionZenTest->buildGroupMultiTaskTest('assignedTo', $task3, $users, array());
$result4 = $executionZenTest->buildGroupMultiTaskTest('finishedBy', $task4, $users, array());
$result5 = $executionZenTest->buildGroupMultiTaskTest('assignedTo', $task5, $users, array());

r(count($result1)) && p() && e(2); // 步骤1：assignedTo分组，期望2个分组（用户1和用户2）
r(count($result2)) && p() && e(1); // 步骤2：finishedBy分组，期望1个分组（用户3）
r(count($result3)) && p() && e(0); // 步骤3：空团队，期望0个分组
r(count($result4)) && p() && e(2); // 步骤4：finishedBy分组且有剩余工时，期望2个分组（用户2和用户1）
r(count($result5)) && p() && e(2); // 步骤5：所有团队成员工时为0，期望2个分组