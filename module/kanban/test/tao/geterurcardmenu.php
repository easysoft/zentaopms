#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getERURCardMenu();
timeout=0
cid=16982

- 步骤1：正常执行ID和空对象数组 @0
- 步骤2：正常执行ID和包含单个epic类型需求对象的数组 @1
- 步骤3：正常执行ID和包含多个不同类型需求对象的数组 @3
- 步骤4：不存在的执行ID和对象数组 @1
- 步骤5：包含不同状态和类型需求的对象数组 @2
- 步骤6：包含父需求的对象数组 @2
- 步骤7：执行关联多产品的场景 @2

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
$story->status->range('active{5},draft{2},closed{1},reviewing{1},changing{1}');
$story->stage->range('wait{3},projected{2},developing{3},testing{2}');
$story->pri->range('1-4');
$story->estimate->range('1-10');
$story->openedBy->range('admin,user1,user2,user3');
$story->assignedTo->range('admin,user1,user2,user3,closed');
$story->version->range('1');
$story->deleted->range('0');
$story->isParent->range('0{8},1{2}');
$story->gen(10);

$execution = zenData('project');
$execution->id->range('101-105');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->type->range('execution');
$execution->status->range('doing{3},wait,closed');
$execution->hasProduct->range('1{3},0,1');
$execution->deleted->range('0');
$execution->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('101-105');
$projectProduct->product->range('1,2,3,1,2');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(5);

$projectStory = zenData('projectstory');
$projectStory->project->range('101,102,103');
$projectStory->story->range('1-3,4-6,7-9');
$projectStory->version->range('1');
$projectStory->gen(9);

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

r($kanbanTest->getERURCardMenuTest(101, array($story1))) && p() && e('1'); // 步骤2：正常执行ID和包含单个epic类型需求对象的数组

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

$story3 = new stdclass();
$story3->id = 3;
$story3->type = 'story';
$story3->status = 'active';
$story3->title = '父需求1';
$story3->stage = 'developing';
$story3->assignedTo = 'user2';
$story3->version = 1;
$story3->deleted = '0';
$story3->story = 3;
$story3->isParent = '1';

r($kanbanTest->getERURCardMenuTest(101, array($story1, $story2, $story3))) && p() && e('3'); // 步骤3：正常执行ID和包含多个不同类型需求对象的数组

r($kanbanTest->getERURCardMenuTest(999, array($story1))) && p() && e('1'); // 步骤4：不存在的执行ID和对象数组

$storyDraft = new stdclass();
$storyDraft->id = 4;
$storyDraft->type = 'story';
$storyDraft->status = 'draft';
$storyDraft->title = 'draft状态的需求';
$storyDraft->stage = 'wait';
$storyDraft->assignedTo = 'admin';
$storyDraft->story = 4;

$storyClosed = new stdclass();
$storyClosed->id = 5;
$storyClosed->type = 'requirement';
$storyClosed->status = 'closed';
$storyClosed->title = 'closed状态的需求';
$storyClosed->stage = 'closed';
$storyClosed->assignedTo = 'closed';
$storyClosed->story = 5;

r($kanbanTest->getERURCardMenuTest(101, array($storyDraft, $storyClosed))) && p() && e('2'); // 步骤5：包含不同状态和类型需求的对象数组

$parentStory1 = new stdclass();
$parentStory1->id = 9;
$parentStory1->type = 'story';
$parentStory1->status = 'active';
$parentStory1->title = '父需求9';
$parentStory1->stage = 'testing';
$parentStory1->assignedTo = 'admin';
$parentStory1->story = 9;
$parentStory1->isParent = '1';

$parentStory2 = new stdclass();
$parentStory2->id = 10;
$parentStory2->type = 'story';
$parentStory2->status = 'changing';
$parentStory2->title = '父需求10';
$parentStory2->stage = 'testing';
$parentStory2->assignedTo = 'user1';
$parentStory2->story = 10;
$parentStory2->isParent = '1';

r($kanbanTest->getERURCardMenuTest(103, array($parentStory1, $parentStory2))) && p() && e('2'); // 步骤6：包含父需求的对象数组

// 测试没有产品关联的执行
$noProductExec = new stdclass();
$noProductExec->id = 104;
$noProductExec->hasProduct = 0;

r($kanbanTest->getERURCardMenuTest(104, array($story1, $story2))) && p() && e('2'); // 步骤7：执行关联多产品的场景