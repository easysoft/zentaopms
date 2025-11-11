#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForCreate();
timeout=0
cid=0

- 步骤1:测试普通产品创建需求的表单字段包含product字段第product条的name属性 @product
- 步骤2:测试分支产品创建需求的表单字段包含branch字段第branch条的name属性 @branch
- 步骤3:测试带执行项目创建需求时产品默认值设置正确第product条的default属性 @1
- 步骤4:测试需求类型为requirement时grade字段存在第grade条的name属性 @grade
- 步骤5:测试需求类型为epic时reviewer字段存在第reviewer条的name属性 @reviewer

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,分支产品1,分支产品2,普通产品1,普通产品2,测试产品1');
$product->type->range('normal{5},branch{2},normal{3}');
$product->status->range('normal{10}');
$product->PO->range('admin{10}');
$product->createdBy->range('admin{10}');
$product->acl->range('open{10}');
$product->gen(10);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

$productplan = zenData('productplan');
$productplan->id->range('1-5');
$productplan->product->range('1-5');
$productplan->branch->range('0{5}');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5');
$productplan->status->range('wait{3},doing{2}');
$productplan->deleted->range('0{5}');
$productplan->gen(5);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1-5');
$module->type->range('story{5}');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->deleted->range('0{5}');
$module->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('需求1,需求2,需求3,需求4,需求5{5}');
$story->type->range('story{5},requirement{3},epic{2}');
$story->status->range('active{10}');
$story->stage->range('wait{10}');
$story->openedBy->range('admin{10}');
$story->assignedTo->range('admin{10}');
$story->reviewedBy->range('admin{10}');
$story->closedBy->range('admin{10}');
$story->estimate->range('1-8');
$story->grade->range('1-3');
$story->branch->range('0{10}');
$story->parent->range('-1{2},0{8}');
$story->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->status->range('doing{5}');
$project->deleted->range('0{5}');
$project->gen(5);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1{3},2{2}');
$projectproduct->product->range('1,2,3,1,4');
$projectproduct->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyZenTest();

// 5. 测试步骤（必须至少5个）
r($storyTest->getFormFieldsForCreateTest(1, '0', 0, 'story')) && p('product:name') && e('product'); // 步骤1:测试普通产品创建需求的表单字段包含product字段
r($storyTest->getFormFieldsForCreateTest(6, '0', 0, 'story')) && p('branch:name') && e('branch'); // 步骤2:测试分支产品创建需求的表单字段包含branch字段
r($storyTest->getFormFieldsForCreateTest(1, '0', 1, 'story')) && p('product:default') && e('1'); // 步骤3:测试带执行项目创建需求时产品默认值设置正确
r($storyTest->getFormFieldsForCreateTest(1, '0', 0, 'requirement')) && p('grade:name') && e('grade'); // 步骤4:测试需求类型为requirement时grade字段存在
r($storyTest->getFormFieldsForCreateTest(1, '0', 0, 'epic')) && p('reviewer:name') && e('reviewer'); // 步骤5:测试需求类型为epic时reviewer字段存在