#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getStoriesCountByProductID();
timeout=0
cid=18558

- 步骤1：正常产品1需求draft状态统计第draft条的count属性 @3
- 步骤2：正常产品1故事changing状态统计第changing条的count属性 @1
- 步骤3：不存在产品统计 @0
- 步骤4：边界值产品ID为0 @0
- 步骤5：产品2需求active状态统计第active条的count属性 @2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-15');
$story->product->range('1{5},2{3},3{2},999{1}');
$story->type->range('requirement{8},story{7}');
$story->status->range('draft{3},active{4},closed{3},changing{2},reviewing{2},launched{1}');
$story->deleted->range('0{14},1{1}');
$story->title->range('需求标题{15}');
$story->openedBy->range('admin{8},user1{4},user2{3}');
$story->assignedTo->range('admin{5},user1{5},user2{5}');
$story->version->range('1{12},2{2},3{1}');
$story->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyModelTest();

// 5. 执行5个测试步骤
r($storyTest->getStoriesCountByProductIDTest(1, 'requirement')) && p('draft:count') && e('3'); // 步骤1：正常产品1需求draft状态统计
r($storyTest->getStoriesCountByProductIDTest(1, 'story')) && p('changing:count') && e('1'); // 步骤2：正常产品1故事changing状态统计
r($storyTest->getStoriesCountByProductIDTest(999, 'requirement')) && p() && e('0'); // 步骤3：不存在产品统计
r($storyTest->getStoriesCountByProductIDTest(0, 'story')) && p() && e('0'); // 步骤4：边界值产品ID为0
r($storyTest->getStoriesCountByProductIDTest(2, 'requirement')) && p('active:count') && e('2'); // 步骤5：产品2需求active状态统计