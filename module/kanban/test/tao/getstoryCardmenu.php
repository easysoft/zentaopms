#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1：空对象数组输入 @0
- 步骤2：单个active状态需求 @1
- 步骤3：draft状态需求返回菜单 @1
- 步骤4：closed状态需求返回菜单 @1
- 步骤5：无产品关联执行返回菜单 @1
- 步骤6：reviewing状态需求返回菜单 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$story->type->range('story');
$story->status->range('active{3},draft{2},closed{2},reviewing{2},changing{1}');
$story->stage->range('wait{3},projected{2},developing{3},testing{2}');
$story->pri->range('1-4');
$story->estimate->range('1-10');
$story->openedBy->range('admin,user1,user2,user3');
$story->assignedTo->range('admin,user1,user2,user3,closed');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(10);

$execution = zenData('project');
$execution->id->range('101-105');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->type->range('execution');
$execution->status->range('doing{3},wait,closed');
$execution->hasProduct->range('1{4},0');
$execution->deleted->range('0');
$execution->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('101-104');
$projectProduct->product->range('1,2,3,1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(4);

$projectStory = zenData('projectstory');
$projectStory->project->range('101,102,103,101,102');
$projectStory->story->range('1-10');
$projectStory->version->range('1');
$projectStory->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,closed');
$user->realname->range('管理员,用户1,用户2,用户3,已关闭');
$user->password->range('123456');
$user->role->range('admin,dev,qa,pm,td');
$user->deleted->range('0{4},1{1}');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 101, 'hasProduct' => 1), array())) && p() && e('0'); // 步骤1：空对象数组输入

$story1 = new stdclass();
$story1->id = 1;
$story1->type = 'story';
$story1->status = 'active';
$story1->title = '需求标题1';
$story1->stage = 'wait';
$story1->assignedTo = 'admin';
$story1->version = 1;
$story1->deleted = '0';
$story1->story = 1;

$result = $kanbanTest->getStoryCardMenuTest((object)array('id' => 101, 'hasProduct' => 1), array($story1));
r(count($result)) && p() && e('1'); // 步骤2：单个active状态需求

$storyDraft = new stdclass();
$storyDraft->id = 4;
$storyDraft->type = 'story';
$storyDraft->status = 'draft';
$storyDraft->title = '需求标题4';
$storyDraft->stage = 'wait';
$storyDraft->assignedTo = 'admin';
$storyDraft->story = 4;

$result = $kanbanTest->getStoryCardMenuTest((object)array('id' => 101, 'hasProduct' => 1), array($storyDraft));
r(count($result)) && p() && e('1'); // 步骤3：draft状态需求返回菜单

$storyClosed = new stdclass();
$storyClosed->id = 6;
$storyClosed->type = 'story';
$storyClosed->status = 'closed';
$storyClosed->title = '需求标题6';
$storyClosed->stage = 'closed';
$storyClosed->assignedTo = 'closed';
$storyClosed->story = 6;

$result = $kanbanTest->getStoryCardMenuTest((object)array('id' => 101, 'hasProduct' => 1), array($storyClosed));
r(count($result)) && p() && e('1'); // 步骤4：closed状态需求返回菜单

$result = $kanbanTest->getStoryCardMenuTest((object)array('id' => 105, 'hasProduct' => 0), array($story1));
r(count($result)) && p() && e('1'); // 步骤5：无产品关联执行返回菜单

$storyReviewing = new stdclass();
$storyReviewing->id = 8;
$storyReviewing->type = 'story';
$storyReviewing->status = 'reviewing';
$storyReviewing->title = '需求标题8';
$storyReviewing->stage = 'testing';
$storyReviewing->assignedTo = 'user1';
$storyReviewing->story = 8;

$result = $kanbanTest->getStoryCardMenuTest((object)array('id' => 102, 'hasProduct' => 1), array($storyReviewing));
r(count($result)) && p() && e('1');  // 步骤6：reviewing状态需求返回菜单