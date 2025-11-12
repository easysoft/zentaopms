#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildDataForBatchToTask();
timeout=0
cid=0

- 步骤1:正常的批量转任务数据构建第0条的name属性 @任务1
- 步骤2:包含必填字段验证的数据构建第0条的name属性 @任务2
- 步骤3:同步需求字段到任务的数据构建第0条的desc属性 @需求描述1
- 步骤4:截止日期早于预计开始日期的验证 @0
- 步骤5:重复任务名称的验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求1,需求2,需求3{8}');
$story->type->range('story');
$story->status->range('active');
$story->stage->range('wait,planned,developed{8}');
$story->mailto->range('user1@test.com,user2@test.com{9}');
$story->version->range('1-5');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1');
$storyspec->title->range('需求1,需求2,需求3{8}');
$storyspec->spec->range('需求描述1,需求描述2{9}');
$storyspec->gen(10);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('迭代1,迭代2,迭代3{8}');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->project->range('1-5');
$execution->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyZenTest = new storyZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyZenTest->buildDataForBatchToTaskTest(1, 1, array('name' => array('任务1'), 'type' => array('devel'), 'estimate' => array(5), 'assignedTo' => array('admin'), 'estStarted' => array('2025-01-01'), 'deadline' => array('2025-01-10'), 'story' => array(1)))) && p('0:name') && e('任务1'); // 步骤1:正常的批量转任务数据构建
r($storyZenTest->buildDataForBatchToTaskTest(1, 1, array('name' => array('任务2'), 'type' => array('test'), 'estimate' => array(3), 'assignedTo' => array('user1'), 'estStarted' => array('2025-01-01'), 'deadline' => array('2025-01-10'), 'story' => array(2)))) && p('0:name') && e('任务2'); // 步骤2:包含必填字段验证的数据构建
r($storyZenTest->buildDataForBatchToTaskTest(1, 1, array('name' => array('任务3'), 'type' => array('devel'), 'estimate' => array(8), 'assignedTo' => array('admin'), 'estStarted' => array('2025-01-01'), 'deadline' => array('2025-01-15'), 'story' => array(1), 'syncFields' => 'spec,mailto'))) && p('0:desc') && e('需求描述1'); // 步骤3:同步需求字段到任务的数据构建
r($storyZenTest->buildDataForBatchToTaskTest(1, 1, array('name' => array('任务4'), 'type' => array('devel'), 'estimate' => array(5), 'assignedTo' => array('admin'), 'estStarted' => array('2025-01-10'), 'deadline' => array('2025-01-05'), 'story' => array(3)))) && p() && e('0'); // 步骤4:截止日期早于预计开始日期的验证
r($storyZenTest->buildDataForBatchToTaskTest(1, 1, array('name' => array('任务5', '任务5'), 'type' => array('devel', 'test'), 'estimate' => array(5, 3), 'assignedTo' => array('admin', 'user1'), 'estStarted' => array('2025-01-01', '2025-01-01'), 'deadline' => array('2025-01-10', '2025-01-10'), 'story' => array(4, 5)))) && p() && e('0'); // 步骤5:重复任务名称的验证