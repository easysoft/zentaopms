#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getStoriesForCreate();
timeout=0
cid=15459

- 步骤1:测试有executionID时stories是数组 @1
- 步骤2:测试有projectID但无executionID时stories是数组 @1
- 步骤3:测试无executionID和projectID时stories是数组 @1
- 步骤4:测试返回对象包含stories属性 @1
- 步骤5:测试不同产品获取stories是数组 @1
- 步骤6:测试不同分支获取stories是数组 @1
- 步骤7:测试返回对象包含原有属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$story = zenData('story');
$story->id->range('1-30');
$story->product->range('1{10},2{10},3{10}');
$story->branch->range('0{15},1{10},2{5}');
$story->module->range('0{10},1001{10},1002{10}');
$story->status->range('active{20},closed{10}');
$story->stage->range('wait{10},planned{10},developing{10}');
$story->type->range('story');
$story->deleted->range('0');
$story->gen(30);

$projectStory = zenData('projectstory');
$projectStory->project->range('101{10},102{10}');
$projectStory->product->range('1{15},2{5}');
$projectStory->story->range('1-20');
$projectStory->gen(20);

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('user')->gen(5);

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->productID = 1;
$bug1->branch = '0';
$bug1->moduleID = 0;
$bug1->projectID = 0;
$bug1->executionID = 101;

$bug2 = new stdClass();
$bug2->productID = 1;
$bug2->branch = '0';
$bug2->moduleID = 0;
$bug2->projectID = 1;
$bug2->executionID = 0;

$bug3 = new stdClass();
$bug3->productID = 1;
$bug3->branch = '0';
$bug3->moduleID = 0;
$bug3->projectID = 0;
$bug3->executionID = 0;

$bug4 = new stdClass();
$bug4->productID = 2;
$bug4->branch = '0';
$bug4->moduleID = 0;
$bug4->projectID = 0;
$bug4->executionID = 0;

$bug5 = new stdClass();
$bug5->productID = 1;
$bug5->branch = '1';
$bug5->moduleID = 0;
$bug5->projectID = 0;
$bug5->executionID = 0;

r(is_array($bugTest->getStoriesForCreateTest($bug1)->stories)) && p() && e('1'); // 步骤1:测试有executionID时stories是数组
r(is_array($bugTest->getStoriesForCreateTest($bug2)->stories)) && p() && e('1'); // 步骤2:测试有projectID但无executionID时stories是数组
r(is_array($bugTest->getStoriesForCreateTest($bug3)->stories)) && p() && e('1'); // 步骤3:测试无executionID和projectID时stories是数组
r(property_exists($bugTest->getStoriesForCreateTest($bug1), 'stories')) && p() && e('1'); // 步骤4:测试返回对象包含stories属性
r(is_array($bugTest->getStoriesForCreateTest($bug4)->stories)) && p() && e('1'); // 步骤5:测试不同产品获取stories是数组
r(is_array($bugTest->getStoriesForCreateTest($bug5)->stories)) && p() && e('1'); // 步骤6:测试不同分支获取stories是数组
r(property_exists($bugTest->getStoriesForCreateTest($bug1), 'productID')) && p() && e('1'); // 步骤7:测试返回对象包含原有属性