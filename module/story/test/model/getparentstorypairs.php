#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getParentStoryPairs();
timeout=0
cid=18547

- 步骤1：测试story类型父需求 @0
- 步骤2：测试requirement类型父需求 @0
- 步骤3：测试epic类型父需求 @0
- 步骤4：测试附加需求ID @0
- 步骤5：测试排除子需求 @0
- 步骤6：测试不存在的产品ID @0
- 步骤7：测试无效的需求类型 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$story = zenData('story');
$story->product->range('1{15},2{10},3{5}');
$story->type->range('requirement{10},epic{5},story{15}');
$story->status->range('active{20},draft{5},closed{3},reviewing{2}');
$story->deleted->range('0');
$story->stage->range('wait{15},planned{10},closed{5}');
$story->parent->range('0{25},1{3},2{2}');
$story->grade->range('1{20},2{10}');
$story->isParent->range('0{25},1{5}');
$story->twins->range('');
$story->vision->range('rnd');
$story->gen(30);

zenData('storyspec')->gen(30);

$storygrade = zenData('storygrade');
$storygrade->type->range('requirement,epic,story,story');
$storygrade->grade->range('1,1,1,2');
$storygrade->name->range('UR,BR,SR,子');
$storygrade->status->range('enable');
$storygrade->gen(4);

zenData('case')->gen(20);
zenData('task')->gen(20);
zenData('product')->gen(5);

global $config;
$config->vision = 'rnd';
$config->requirement = new stdclass();
$config->requirement->gradeRule = 'stepwise';
$config->epic = new stdclass();
$config->epic->gradeRule = 'stepwise';

su('admin');

$storyTest = new storyTest();

r(count($storyTest->getParentStoryPairsTest(1, '', 'story', 0))) && p() && e('0'); // 步骤1：测试story类型父需求
r(count($storyTest->getParentStoryPairsTest(1, '', 'requirement', 0))) && p() && e('0'); // 步骤2：测试requirement类型父需求
r(count($storyTest->getParentStoryPairsTest(1, '', 'epic', 0))) && p() && e('0'); // 步骤3：测试epic类型父需求
r(count($storyTest->getParentStoryPairsTest(1, '3,5', 'story', 0))) && p() && e('0'); // 步骤4：测试附加需求ID
r(count($storyTest->getParentStoryPairsTest(1, '', 'story', 2))) && p() && e('0'); // 步骤5：测试排除子需求
r(count($storyTest->getParentStoryPairsTest(999, '', 'story', 0))) && p() && e('0'); // 步骤6：测试不存在的产品ID
r($storyTest->getParentStoryPairsTest(1, '', 'invalid', 0)) && p() && e('~~'); // 步骤7：测试无效的需求类型