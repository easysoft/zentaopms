#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getERURCardMenu();
timeout=0
cid=16981

- 步骤1：正常执行ID和空对象数组 @0
- 步骤2：正常执行ID和包含需求对象的数组 @2
- 步骤3：不存在的执行ID和对象数组 @1
- 步骤4：执行ID为0和对象数组 @1
- 步骤5：draft状态的需求 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('用需求1,业需求1,父需求1,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$story->type->range('epic{2},requirement{3},story{5}');
$story->status->range('active{5},draft{2},reviewing{2},closed{1}');
$story->stage->range('wait{3},projected{2},developing{3},testing{2}');
$story->pri->range('1-4');
$story->estimate->range('1-10');
$story->openedBy->range('admin,user1,user2,user3');
$story->assignedTo->range('admin,user1,user2,user3,closed');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(10);

$execution = zenData('project');
$execution->id->range('101-103');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('execution');
$execution->status->range('doing');
$execution->hasProduct->range('1');
$execution->deleted->range('0');
$execution->gen(3);

$projectStory = zenData('projectstory');
$projectStory->project->range('101,102,103');
$projectStory->story->range('1-3,4-6,7-9');
$projectStory->version->range('1');
$projectStory->gen(9);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getERURCardMenuTest(101, array())) && p() && e('0'); // 步骤1：正常执行ID和空对象数组

$story1 = new stdclass();
$story1->id = 1;
$story1->type = 'epic';
$story1->status = 'active';
$story1->title = '用需求1';
$story1->stage = 'wait';
$story1->assignedTo = 'admin';
$story1->version = 1;
$story1->deleted = '0';
$story1->story = 1;

$story2 = new stdclass();
$story2->id = 2;
$story2->type = 'requirement';
$story2->status = 'active';
$story2->title = '业需求1';
$story2->stage = 'projected';
$story2->assignedTo = 'user1';
$story2->version = 1;
$story2->deleted = '0';
$story2->story = 2;

r($kanbanTest->getERURCardMenuTest(101, array($story1, $story2))) && p() && e('2'); // 步骤2：正常执行ID和包含需求对象的数组

r($kanbanTest->getERURCardMenuTest(999, array($story1))) && p() && e('1'); // 步骤3：不存在的执行ID和对象数组

r($kanbanTest->getERURCardMenuTest(0, array($story1))) && p() && e('1'); // 步骤4：执行ID为0和对象数组

$storyDraft = new stdclass();
$storyDraft->id = 3;
$storyDraft->type = 'story';
$storyDraft->status = 'draft';
$storyDraft->title = 'draft状态的需求';
$storyDraft->stage = 'wait';
$storyDraft->assignedTo = 'admin';
$storyDraft->story = 3;

r($kanbanTest->getERURCardMenuTest(101, array($storyDraft))) && p() && e('1'); // 步骤5：draft状态的需求