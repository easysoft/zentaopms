#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStoryIdList();
timeout=0
cid=0

- 步骤1:测试空数组 @0
- 步骤2:测试单个需求无子需求属性1 @1
- 步骤3:测试多个需求无子需求
 - 属性2 @2
 - 属性3 @3
 - 属性4 @4
- 步骤4:测试单个需求有子需求
 - 属性5 @5
 - 属性51 @51
 - 属性52 @52
- 步骤5:测试多个需求有的有子需求有的没有
 - 属性6 @6
 - 属性7 @7
 - 属性71 @71
 - 属性72 @72
- 步骤6:测试需求ID顺序保持
 - 属性100 @100
 - 属性50 @50
 - 属性200 @200
- 步骤7:测试子需求为空数组属性11 @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

/* 准备测试数据 */
/* 步骤1: 测试空数组 */
$emptyStories = array();

/* 步骤2: 测试单个需求无子需求 */
$singleStory = array();
$story1 = new stdclass();
$story1->id = 1;
$singleStory[] = $story1;

/* 步骤3: 测试多个需求无子需求 */
$multipleStories = array();
$story2 = new stdclass();
$story2->id = 2;
$story3 = new stdclass();
$story3->id = 3;
$story4 = new stdclass();
$story4->id = 4;
$multipleStories[] = $story2;
$multipleStories[] = $story3;
$multipleStories[] = $story4;

/* 步骤4: 测试单个需求有子需求 */
$storyWithChildren = array();
$story5 = new stdclass();
$story5->id = 5;
$child1 = new stdclass();
$child1->id = 51;
$child2 = new stdclass();
$child2->id = 52;
$story5->children = array($child1, $child2);
$storyWithChildren[] = $story5;

/* 步骤5: 测试多个需求有的有子需求有的没有 */
$mixedStories = array();
$story6 = new stdclass();
$story6->id = 6;
$story7 = new stdclass();
$story7->id = 7;
$child3 = new stdclass();
$child3->id = 71;
$child4 = new stdclass();
$child4->id = 72;
$story7->children = array($child3, $child4);
$mixedStories[] = $story6;
$mixedStories[] = $story7;

/* 步骤6: 测试需求ID顺序保持 */
$orderedStories = array();
$story8 = new stdclass();
$story8->id = 100;
$story9 = new stdclass();
$story9->id = 50;
$story10 = new stdclass();
$story10->id = 200;
$orderedStories[] = $story8;
$orderedStories[] = $story9;
$orderedStories[] = $story10;

/* 步骤7: 测试子需求为空数组 */
$storyWithEmptyChildren = array();
$story11 = new stdclass();
$story11->id = 11;
$story11->children = array();
$storyWithEmptyChildren[] = $story11;

r($productTest->getStoryIdListTest($emptyStories)) && p() && e('0'); // 步骤1:测试空数组
r($productTest->getStoryIdListTest($singleStory)) && p('1') && e('1'); // 步骤2:测试单个需求无子需求
r($productTest->getStoryIdListTest($multipleStories)) && p('2,3,4') && e('2,3,4'); // 步骤3:测试多个需求无子需求
r($productTest->getStoryIdListTest($storyWithChildren)) && p('5,51,52') && e('5,51,52'); // 步骤4:测试单个需求有子需求
r($productTest->getStoryIdListTest($mixedStories)) && p('6,7,71,72') && e('6,7,71,72'); // 步骤5:测试多个需求有的有子需求有的没有
r($productTest->getStoryIdListTest($orderedStories)) && p('100,50,200') && e('100,50,200'); // 步骤6:测试需求ID顺序保持
r($productTest->getStoryIdListTest($storyWithEmptyChildren)) && p('11') && e('11'); // 步骤7:测试子需求为空数组