#!/usr/bin/env php
<?php

/**

title=测试 buildZen::getExcludeStoryIdList();
timeout=0
cid=15522

- 测试产品1有父需求和已关联需求的情况,期望返回包含父需求和已关联需求的ID列表 @4
- 测试产品1只有父需求无已关联需求的情况,期望返回只包含父需求的ID列表 @1
- 测试产品2有多个父需求和已关联需求的情况,期望返回包含所有父需求和已关联需求的ID列表 @3
- 测试产品无父需求但有已关联需求的情况,期望返回只包含已关联需求的ID列表 @3
- 测试产品无父需求也无已关联需求的情况,期望返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$story = zenData('story');
$story->id->range('1-20');
$story->vision->range('rnd');
$story->parent->range('0');
$story->isParent->range('0,0,0,1,0,0,0,0,0,0,1,0,1,0,1,0,0,0,0,0');
$story->product->range('1{10},2{7},3{3}');
$story->type->range('story');
$story->status->range('active{15},closed{5}');
$story->stage->range('wait{10},developed{10}');
$story->openedBy->range('admin');
$story->gen(20);

su('admin');

$buildTest = new buildZenTest();

$build1 = new stdclass();
$build1->product    = 1;
$build1->allStories = '3,5,7';

$build2 = new stdclass();
$build2->product    = 1;
$build2->allStories = '';

$build3 = new stdclass();
$build3->product    = 2;
$build3->allStories = '13,15';

$build4 = new stdclass();
$build4->product    = 1;
$build4->allStories = '8,9';

$build5 = new stdclass();
$build5->product    = 3;
$build5->allStories = '';

r($buildTest->getExcludeStoryIdListTest($build1)) && p() && e('4'); // 测试产品1有父需求和已关联需求的情况,期望返回包含父需求和已关联需求的ID列表
r($buildTest->getExcludeStoryIdListTest($build2)) && p() && e('1'); // 测试产品1只有父需求无已关联需求的情况,期望返回只包含父需求的ID列表
r($buildTest->getExcludeStoryIdListTest($build3)) && p() && e('3'); // 测试产品2有多个父需求和已关联需求的情况,期望返回包含所有父需求和已关联需求的ID列表
r($buildTest->getExcludeStoryIdListTest($build4)) && p() && e('3'); // 测试产品无父需求但有已关联需求的情况,期望返回只包含已关联需求的ID列表
r($buildTest->getExcludeStoryIdListTest($build5)) && p() && e('0'); // 测试产品无父需求也无已关联需求的情况,期望返回空数组