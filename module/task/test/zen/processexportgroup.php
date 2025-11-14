#!/usr/bin/env php
<?php

/**

title=测试 taskZen::processExportGroup();
timeout=0
cid=18938

- 步骤1：正常情况无团队成员 @5
- 步骤2：有团队成员按assignedTo排序 @6
- 步骤3：按finishedBy排序处理工时 @6
- 步骤4：空任务数组 @0
- 步骤5：正常executionID处理 @5
- 步骤6：检查storyTitle字段是否存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('执行项目{1-5}');
$execution->type->range('sprint');
$execution->gen(5);

$story = zenData('story');
$story->id->range('1-3');
$story->title->range('需求故事{1-3}');
$story->product->range('1');
$story->status->range('active');
$story->gen(3);

$projectstory = zenData('projectstory');
$projectstory->project->range('1');
$projectstory->story->range('1-3');
$projectstory->gen(3);

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('1');
$task->name->range('任务{1-10}');
$task->assignedTo->range('user1,user2,user3');
$task->finishedBy->range('user1,user2,user3');
$task->story->range('1-3,0{5}');
$task->estimate->range('1-8');
$task->consumed->range('0-5');
$task->left->range('0-3');
$task->status->range('wait{3},doing{4},done{3}');
$task->gen(10);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-15');
$taskteam->task->range('1-5{3}');
$taskteam->account->range('user1,user2,user3');
$taskteam->estimate->range('2-8');
$taskteam->consumed->range('1-5');
$taskteam->left->range('0-3');
$taskteam->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskTest();

// 5. 准备测试数据
$mockTasks = array();
for($i = 1; $i <= 5; $i++)
{
    $mockTasks[] = (object)array(
        'id' => $i,
        'name' => "任务{$i}",
        'assignedTo' => 'user' . (($i % 3) + 1),
        'finishedBy' => 'user' . (($i % 3) + 1),
        'story' => $i <= 3 ? $i : 0,
        'estimate' => $i + 1,
        'consumed' => $i,
        'left' => $i % 2,
        'status' => $i <= 2 ? 'wait' : 'done'
    );
}

$mockTasksWithTeam = array();
for($i = 1; $i <= 3; $i++)
{
    $task = (object)array(
        'id' => $i,
        'name' => "团队任务{$i}",
        'assignedTo' => 'user1',
        'finishedBy' => 'user1',
        'story' => $i,
        'estimate' => 5,
        'consumed' => 2,
        'left' => 3,
        'status' => 'doing',
        'team' => array()
    );

    for($j = 1; $j <= 2; $j++)
    {
        $task->team[] = (object)array(
            'account' => 'user' . $j,
            'estimate' => 2 + $j,
            'consumed' => 1 + $j,
            'left' => $j % 2
        );
    }
    $mockTasksWithTeam[] = $task;
}

// 6. 测试步骤（必须包含至少5个测试步骤）
$result1 = $taskTest->processExportGroupTest(1, $mockTasks, 'assignedTo');
r(count($result1)) && p() && e('5'); // 步骤1：正常情况无团队成员

$result2 = $taskTest->processExportGroupTest(1, $mockTasksWithTeam, 'assignedTo');
r(count($result2)) && p() && e('6'); // 步骤2：有团队成员按assignedTo排序

$result3 = $taskTest->processExportGroupTest(1, $mockTasksWithTeam, 'finishedBy');
r(count($result3)) && p() && e('6'); // 步骤3：按finishedBy排序处理工时

$result4 = $taskTest->processExportGroupTest(1, array(), 'assignedTo');
r(count($result4)) && p() && e('0'); // 步骤4：空任务数组

$result5 = $taskTest->processExportGroupTest(1, $mockTasks, 'assignedTo');
r(count($result5)) && p() && e('5'); // 步骤5：正常executionID处理

$result6 = $taskTest->processExportGroupTest(1, $mockTasks, 'assignedTo');
r(isset($result6[0]->storyTitle)) && p() && e('1'); // 步骤6：检查storyTitle字段是否存在