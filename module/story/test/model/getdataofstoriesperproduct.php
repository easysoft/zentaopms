#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerProduct();
timeout=0
cid=18521

- 测试默认storyType参数获取产品需求统计数量 @7
- 测试指定storyType为story的统计结果数量 @7
- 测试指定storyType为requirement的统计结果数量 @8
- 测试产品3的统计数据准确性
 - 第3条的name属性 @正常产品3
 - 第3条的value属性 @5
- 测试产品1的统计数据准确性
 - 第1条的name属性 @正常产品1
 - 第1条的value属性 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('正常产品1,正常产品2,正常产品3,正常产品4,正常产品5,正常产品6,正常产品7,正常产品8,正常产品9,正常产品10');
$product->type->range('normal{5},branch{3},platform{2}');
$product->gen(10);

$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1{4},2{3},3{5},4{2},5{3},6{1},7{1},8{1}');
$story->type->range('story{15},requirement{5}');
$story->status->range('active{12},closed{3},draft{5}');
$story->version->range('1-4');
$story->gen(20);

su('admin');

$storyTest = new storyTest();

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

r(count($storyTest->getDataOfStoriesPerProductTest())) && p() && e('7'); // 测试默认storyType参数获取产品需求统计数量
r(count($storyTest->getDataOfStoriesPerProductTest('story'))) && p() && e('7'); // 测试指定storyType为story的统计结果数量
r(count($storyTest->getDataOfStoriesPerProductTest('requirement'))) && p() && e('8'); // 测试指定storyType为requirement的统计结果数量
r($storyTest->getDataOfStoriesPerProductTest()) && p('3:name,value') && e('正常产品3,5'); // 测试产品3的统计数据准确性
r($storyTest->getDataOfStoriesPerProductTest()) && p('1:name,value') && e('正常产品1,4'); // 测试产品1的统计数据准确性