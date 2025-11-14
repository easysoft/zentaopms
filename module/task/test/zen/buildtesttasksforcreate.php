#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTestTasksForCreate();
timeout=0
cid=18916

- 步骤1：selectTestStory为off时返回空数组 @0
- 步骤2：正常创建单个测试任务 @1
- 步骤3：批量创建多个测试任务 @3
- 步骤4：无效executionID输入 @0
- 步骤5：测试任务数据包含所有必需字段第0条的type属性 @test

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{5}, execution{5}');
$project->status->range('wait{5}, doing{5}');
$project->project->range('0{5}, 1-5');
$project->vision->range('rnd');
$project->deleted->range('0');
$project->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('需求{1-5}');
$story->product->range('1');
$story->status->range('active');
$story->stage->range('wait{2}, planned{3}');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->role->range('admin{1}, dev{4}');
$user->deleted->range('0');
$user->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 测试步骤
r(count($taskZenTest->buildTestTasksForCreateTest(1, array('selectTestStory' => 'off')))) && p() && e('0'); // 步骤1：selectTestStory为off时返回空数组

r(count($taskZenTest->buildTestTasksForCreateTest(6, array('selectTestStory' => 'on', 'testStory' => array(1), 'testPri' => array(3), 'testAssignedTo' => array('user1'), 'testEstimate' => array(8), 'pri' => 2)))) && p() && e('1'); // 步骤2：正常创建单个测试任务

r(count($taskZenTest->buildTestTasksForCreateTest(7, array('selectTestStory' => 'on', 'testStory' => array(1, 2, 3), 'testPri' => array(1, 2, 3), 'testAssignedTo' => array('user1', 'user2', 'user3'), 'testEstimate' => array(4, 6, 8))))) && p() && e('3'); // 步骤3：批量创建多个测试任务

r($taskZenTest->buildTestTasksForCreateTest(999, array('selectTestStory' => 'on', 'testStory' => array(1)))) && p() && e('0'); // 步骤4：无效executionID输入

r($taskZenTest->buildTestTasksForCreateTest(8, array('selectTestStory' => 'on', 'testStory' => array(2), 'testPri' => array(2), 'testAssignedTo' => array('user2'), 'testEstimate' => array(6)))) && p('0:type') && e('test'); // 步骤5：测试任务数据包含所有必需字段