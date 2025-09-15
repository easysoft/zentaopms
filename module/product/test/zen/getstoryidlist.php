#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStoryIdList();
timeout=0
cid=0

- 步骤1：包含子需求的需求列表测试 @4
- 步骤2：无子需求的普通需求列表测试 @2
- 步骤3：空需求列表测试 @0
- 步骤4：单个需求无子需求测试 @1
- 步骤5：多层嵌套子需求测试 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求{1-10}');
$story->type->range('story');
$story->status->range('active');
$story->product->range('1');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：包含子需求的需求列表测试
$storiesWithChildren = array();
$story1 = new stdClass();
$story1->id = 1;
$story1->children = array();
$child1 = new stdClass();
$child1->id = 2;
$child2 = new stdClass();
$child2->id = 3;
$story1->children[] = $child1;
$story1->children[] = $child2;
$storiesWithChildren[] = $story1;

$story2 = new stdClass();
$story2->id = 4;
$storiesWithChildren[] = $story2;

r(count($productTest->getStoryIdListTest($storiesWithChildren))) && p() && e('4'); // 步骤1：包含子需求的需求列表测试

// 步骤2：无子需求的普通需求列表测试
$storiesWithoutChildren = array();
$story3 = new stdClass();
$story3->id = 5;
$storiesWithoutChildren[] = $story3;

$story4 = new stdClass();
$story4->id = 6;
$storiesWithoutChildren[] = $story4;

r(count($productTest->getStoryIdListTest($storiesWithoutChildren))) && p() && e('2'); // 步骤2：无子需求的普通需求列表测试

// 步骤3：空需求列表测试
$emptyStories = array();
r(count($productTest->getStoryIdListTest($emptyStories))) && p() && e('0'); // 步骤3：空需求列表测试

// 步骤4：单个需求无子需求测试
$singleStory = array();
$story5 = new stdClass();
$story5->id = 7;
$singleStory[] = $story5;

r(count($productTest->getStoryIdListTest($singleStory))) && p() && e('1'); // 步骤4：单个需求无子需求测试

// 步骤5：多层嵌套子需求测试
$complexStories = array();
$story6 = new stdClass();
$story6->id = 8;
$story6->children = array();
$child3 = new stdClass();
$child3->id = 9;
$child4 = new stdClass();
$child4->id = 10;
$child5 = new stdClass();
$child5->id = 11;
$story6->children[] = $child3;
$story6->children[] = $child4;
$story6->children[] = $child5;
$complexStories[] = $story6;

r(count($productTest->getStoryIdListTest($complexStories))) && p() && e('4'); // 步骤5：多层嵌套子需求测试