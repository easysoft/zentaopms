#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForActivate();
timeout=0
cid=18669

- 步骤1：正常需求ID的lastEditedBy应为admin属性lastEditedBy @admin
- 步骤2：已激活需求ID的lastEditedBy应为admin属性lastEditedBy @admin
- 步骤3：草稿状态需求ID的lastEditedBy应为admin属性lastEditedBy @admin
- 步骤4：已关闭需求ID的lastEditedBy应为admin属性lastEditedBy @admin
- 步骤5：不存在需求ID的lastEditedBy应为admin属性lastEditedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('激活测试需求1,激活测试需求2,激活测试需求3,激活测试需求4,激活测试需求5{5}');
$story->type->range('story{8},requirement{2}');
$story->status->range('active{3},draft{3},changing{2},closed{2}');
$story->stage->range('wait{4},planned{3},developed{3}');
$story->openedBy->range('admin,user1,user2{8}');
$story->assignedTo->range('admin,user1,user2,{7}');
$story->closedBy->range('{5},admin,user1,user2{2}');
$story->closedReason->range('{5},done,cancel{2},postponed{2}');
$story->reviewedBy->range('admin,user1,user2{8}');
$story->estimate->range('1-8');
$story->grade->range('1-3');
$story->branch->range('0{5},1{3},2{2}');
$story->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyZenTest = new storyZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyZenTest->buildStoryForActivateTest(1)) && p('lastEditedBy') && e('admin'); // 步骤1：正常需求ID的lastEditedBy应为admin
r($storyZenTest->buildStoryForActivateTest(2)) && p('lastEditedBy') && e('admin'); // 步骤2：已激活需求ID的lastEditedBy应为admin
r($storyZenTest->buildStoryForActivateTest(5)) && p('lastEditedBy') && e('admin'); // 步骤3：草稿状态需求ID的lastEditedBy应为admin
r($storyZenTest->buildStoryForActivateTest(9)) && p('lastEditedBy') && e('admin'); // 步骤4：已关闭需求ID的lastEditedBy应为admin
r($storyZenTest->buildStoryForActivateTest(999)) && p('lastEditedBy') && e('admin'); // 步骤5：不存在需求ID的lastEditedBy应为admin