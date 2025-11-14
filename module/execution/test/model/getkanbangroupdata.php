#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getKanbanGroupData();
timeout=0
cid=16320

- 执行executionTest模块的getKanbanGroupDataTest方法，参数是array  @2
- 执行executionTest模块的getKanbanGroupDataTest方法，参数是$stories, $tasks, $bugs, 'story'  @12
- 执行executionTest模块的getKanbanGroupDataTest方法，参数是$stories, $tasks, $bugs, 'assignedTo'  @5
- 执行executionTest模块的getKanbanGroupDataTest方法，参数是array  @5
- 执行executionTest模块的getKanbanGroupDataTest方法，参数是$stories, $tasks, $bugs, 'assignedTo'  @5
- 执行executionTest模块的getKanbanGroupDataTest方法，参数是array  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 准备用户数据
zenData('user')->gen(5);
su('admin');

// 准备执行数据
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

// 准备任务数据，包含不同状态和指派人
$task = zenData('task');
$task->id->range('1-15');
$task->execution->range('3');
$task->name->range('1-15')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait{5},doing{5},done{3},closed{2}');
$task->story->range('1{5},2{5},3{3},0{2}');
$task->assignedTo->range('admin{5},user1{5},user2{3},closed{2}');
$task->finishedBy->range('admin{3},user1{3},user2{3},[]{6}');
$task->gen(15);

// 准备产品数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

// 准备需求数据
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('1-10')->prefix('需求');
$story->type->range('story');
$story->product->range('1');
$story->status->range('active');
$story->stage->range('projected');
$story->version->range('1');
$story->gen(10);

// 准备项目需求关联
$projectStory = zenData('projectstory');
$projectStory->project->range('3');
$projectStory->product->range('1');
$projectStory->story->range('1-10');
$projectStory->version->range('1');
$projectStory->gen(10);

// 准备bug数据，包含不同状态和处理人
$bug = zenData('bug');
$bug->id->range('1-12');
$bug->execution->range('3');
$bug->title->range('1-12')->prefix('Bug');
$bug->status->range('active{4},resolved{4},closed{4}');
$bug->resolution->range('[]{4},fixed{2},postponed{2},[]{4}');
$bug->story->range('1{3},2{3},3{3},0{3}');
$bug->assignedTo->range('admin{4},user1{4},closed{2},[]{2}');
$bug->resolvedBy->range('admin{3},user1{3},user2{3},[]{3}');
$bug->gen(12);

$executionTest = new executionTest();

// 构造测试数据
$stories = array();
for($i = 1; $i <= 10; $i++)
{
    $story = new stdclass();
    $story->id = $i;
    $story->title = "需求{$i}";
    $stories[$i] = $story;
}

$tasks = array();
for($i = 1; $i <= 15; $i++)
{
    $task = new stdclass();
    $task->id = $i;
    $task->name = "任务{$i}";
    $task->status = $i <= 5 ? 'wait' : ($i <= 10 ? 'doing' : ($i <= 13 ? 'done' : 'closed'));
    $task->storyID = $i <= 5 ? 1 : ($i <= 10 ? 2 : ($i <= 13 ? 3 : 0));
    $task->assignedTo = $i <= 5 ? 'admin' : ($i <= 10 ? 'user1' : ($i <= 13 ? 'user2' : 'closed'));
    $task->finishedBy = $i <= 8 ? '' : ($i <= 11 ? 'admin' : 'user1');
    $tasks[] = $task;
}

$bugs = array();
for($i = 1; $i <= 12; $i++)
{
    $bug = new stdclass();
    $bug->id = $i;
    $bug->title = "Bug{$i}";
    $bug->status = $i <= 4 ? 'active' : ($i <= 8 ? 'resolved' : 'closed');
    $bug->resolution = $i <= 4 ? '' : ($i <= 6 ? 'fixed' : ($i <= 8 ? 'postponed' : ''));
    $bug->story = $i <= 3 ? 1 : ($i <= 6 ? 2 : ($i <= 9 ? 3 : 0));
    $bug->assignedTo = $i <= 4 ? 'admin' : ($i <= 8 ? 'user1' : ($i <= 10 ? 'closed' : ''));
    $bug->resolvedBy = $i <= 3 ? 'admin' : ($i <= 6 ? 'user1' : ($i <= 9 ? 'user2' : ''));
    $bugs[] = $bug;
}

// 测试步骤1：空数据输入情况
r($executionTest->getKanbanGroupDataTest(array(), array(), array(), 'story')) && p() && e('2');

// 测试步骤2：story类型看板数据分组
r($executionTest->getKanbanGroupDataTest($stories, $tasks, $bugs, 'story')) && p() && e('12');

// 测试步骤3：assignedTo类型看板数据分组
r($executionTest->getKanbanGroupDataTest($stories, $tasks, $bugs, 'assignedTo')) && p() && e('5');

// 测试步骤4：finishedBy类型看板数据分组（主要测试bug数据）
r($executionTest->getKanbanGroupDataTest(array(), array(), $bugs, 'finishedBy')) && p() && e('5');

// 测试步骤5：测试包含closed状态的特殊处理
r($executionTest->getKanbanGroupDataTest($stories, $tasks, $bugs, 'assignedTo')) && p() && e('5');

// 测试步骤6：测试nokey数据的处理（无关联key的任务和bug）
r($executionTest->getKanbanGroupDataTest(array(), $tasks, $bugs, 'story')) && p() && e('2');