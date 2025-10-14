#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildDataForBatchToTask();
timeout=0
cid=0

- 步骤1：无POST数据时返回空数组 @0
- 步骤2：有POST数据时返回任务数组 @Array
- 步骤3：清空POST后再次测试返回空数组 @0
- 步骤4：单个任务测试 @Array
- 步骤5：测试0参数情况 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$project->type->range('project{5},stage{5}');
$project->status->range('doing{10}');
$project->parent->range('0{5},1,2,3,4,5');
$project->gen(10);

$story = zenData('story');
$story->id->range('1-20');
$story->title->range('故事1,故事2,故事3,故事4,故事5,故事6,故事7,故事8,故事9,故事10,故事11,故事12,故事13,故事14,故事15,故事16,故事17,故事18,故事19,故事20');
$story->version->range('1{20}');
$story->product->range('1-5');
$story->status->range('active{20}');
$story->gen(20);

$task = zenData('task');
$task->id->range('1-5');
$task->name->range('任务1,任务2,任务3,任务4,任务5');
$task->execution->range('6-10');
$task->project->range('1-5');
$task->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 模拟POST数据为空的情况
$_POST = array();

// 5. 测试步骤
r($storyTest->buildDataForBatchToTaskTest(6, 1)) && p() && e(0); // 步骤1：无POST数据时返回空数组

// 模拟POST数据包含任务信息
$_POST = array(
    'name' => array('测试任务1', '测试任务2'),
    'assignedTo' => array('admin', 'user1'),
    'estimate' => array('8', '16'),
    'pri' => array('2', '3'),
    'story' => array('1', '2')
);

r($storyTest->buildDataForBatchToTaskTest(8, 2)) && p() && e('Array'); // 步骤2：有POST数据时返回任务数组

// 清空POST数据，再次测试
$_POST = array();
r($storyTest->buildDataForBatchToTaskTest(7, 1)) && p() && e(0); // 步骤3：清空POST后再次测试返回空数组

// 重新设置POST数据测试方法的一致性
$_POST = array('name' => array('新任务'));
r($storyTest->buildDataForBatchToTaskTest(9, 3)) && p() && e('Array'); // 步骤4：单个任务测试

// 测试空参数情况
r($storyTest->buildDataForBatchToTaskTest(0, 0)) && p() && e('Array'); // 步骤5：测试0参数情况