#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildTaskData();
timeout=0
cid=0

- 步骤1：正常情况测试工时标签第1条的estimateLabel属性 @10工时
- 步骤2：父任务hasChild属性设置第2条的hasChild属性 @1
- 步骤3：空数组处理 @0
- 步骤4：单个任务canBeChanged属性第1条的canBeChanged属性 @1
- 步骤5：需求状态变更导致的changed状态第1条的status属性 @changed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('task')->loadYaml('task_buildtaskdata', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$tasks = array();
$task1 = new stdClass();
$task1->id = 1;
$task1->parent = 0;
$task1->name = '正常任务';
$task1->estimate = 10;
$task1->consumed = 5;
$task1->left = 5;
$task1->status = 'doing';
$task1->storyStatus = 'active';
$task1->latestStoryVersion = 2;
$task1->storyVersion = 1;
$tasks[1] = $task1;

$task2 = new stdClass();
$task2->id = 2;
$task2->parent = 0;
$task2->name = '父任务';
$task2->estimate = 20;
$task2->consumed = 10;
$task2->left = 10;
$task2->status = 'wait';
$task2->storyStatus = 'active';
$task2->latestStoryVersion = 1;
$task2->storyVersion = 1;
$tasks[2] = $task2;

$task3 = new stdClass();
$task3->id = 3;
$task3->parent = 2;
$task3->name = '子任务';
$task3->estimate = 8;
$task3->consumed = 3;
$task3->left = 5;
$task3->status = 'doing';
$task3->storyStatus = 'active';
$task3->latestStoryVersion = 1;
$task3->storyVersion = 1;
$tasks[3] = $task3;

r($myTest->buildTaskDataTest($tasks)) && p('1:estimateLabel') && e('10工时'); // 步骤1：正常情况测试工时标签
r($myTest->buildTaskDataTest($tasks)) && p('2:hasChild') && e('1'); // 步骤2：父任务hasChild属性设置
r($myTest->buildTaskDataTest(array())) && p() && e('0'); // 步骤3：空数组处理
r($myTest->buildTaskDataTest(array(1 => $task1))) && p('1:canBeChanged') && e('1'); // 步骤4：单个任务canBeChanged属性
r($myTest->buildTaskDataTest($tasks)) && p('1:status') && e('changed'); // 步骤5：需求状态变更导致的changed状态