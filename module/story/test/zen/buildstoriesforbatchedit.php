#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoriesForBatchEdit();
timeout=0
cid=0

- 步骤1：正常情况
 - 第1条的title属性 @更新需求1
 - 第1条的assignedTo属性 @user1
 - 第1条的stage属性 @planned
- 步骤2：关闭需求
 - 第2条的status属性 @closed
 - 第2条的closedBy属性 @admin
 - 第2条的closedReason属性 @done
- 步骤3：指派人变更
 - 第3条的assignedTo属性 @user2
 - 第3条的assignedDate属性 @2024-01-01 00:00:00
- 步骤4：重复需求验证 @0
- 步骤5：阶段变更
 - 第5条的stage属性 @tested
 - 第5条的stagedBy属性 @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->parent->range('0');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story');
$story->stage->range('wait,planned,projected,developing,testing');
$story->status->range('active');
$story->pri->range('3');
$story->assignedTo->range('admin,user1,user2');
$story->assignedDate->range('`2023-01-01 00:00:00`');
$story->closedBy->range('');
$story->closedDate->range('`0000-00-00 00:00:00`');
$story->closedReason->range('');
$story->lastEditedBy->range('admin');
$story->lastEditedDate->range('`2023-01-01 00:00:00`');
$story->branch->range('0');
$story->roadmap->range('0');
$story->gen(10);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-10');
$storySpec->version->range('1');
$storySpec->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storySpec->spec->range('需求描述1,需求描述2,需求描述3,需求描述4,需求描述5,需求描述6,需求描述7,需求描述8,需求描述9,需求描述10');
$storySpec->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->buildStoriesForBatchEditTest(array(1 => array('title' => '更新需求1', 'assignedTo' => 'user1', 'stage' => 'planned')))) && p('1:title,assignedTo,stage') && e('更新需求1,user1,planned'); // 步骤1：正常情况
r($storyTest->buildStoriesForBatchEditTest(array(2 => array('closedBy' => 'admin', 'closedReason' => 'done', 'stage' => 'closed')))) && p('2:status,closedBy,closedReason') && e('closed,admin,done'); // 步骤2：关闭需求
r($storyTest->buildStoriesForBatchEditTest(array(3 => array('assignedTo' => 'user2')))) && p('3:assignedTo,assignedDate') && e('user2,2024-01-01 00:00:00'); // 步骤3：指派人变更
r($storyTest->buildStoriesForBatchEditTest(array(4 => array('closedReason' => 'duplicate', 'duplicateStory' => '')))) && p() && e('0'); // 步骤4：重复需求验证
r($storyTest->buildStoriesForBatchEditTest(array(5 => array('stage' => 'tested')))) && p('5:stage,stagedBy') && e('tested,admin'); // 步骤5：阶段变更