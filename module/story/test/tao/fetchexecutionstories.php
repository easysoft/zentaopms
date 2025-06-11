#!/usr/bin/env php
<?php

/**

title=测试 storyModel->fetchExecutionStories();
cid=0

- 不传入执行，也不传入产品。 @0
- 传入执行，不传入产品。 @50
- 传入产品，不传入执行。 @0
- 传入产品，传入执行。 @25
- 传入产品，传入执行，设置SESSION。 @5
- 分页获取需求。 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('product')->gen(10);
$project = zenData('project');
$project->type->range('sprint');
$project->gen(50);

$projectstory = zenData('projectstory');
$projectstory->product->range('1-2');
$projectstory->story->range('1-50');
$projectstory->project->range('11');
$projectstory->branch->range('0{30},1{10},2{10}');
$projectstory->gen(50);

$story = zenData('story');
$story->product->range('1-2');
$story->type->range('story');
$story->branch->range('0{30},1{10},2{10}');
$story->status->range('draft,reviewing,active,closed,changing');
$story->gen(50);

$storyTest = new storyTest();

$productID   = array(0, 1);
$executionID = array(0, 11);

r(count($storyTest->fetchExecutionStoriesTest($executionID[0], $productID[0]))) && p() && e('0');  //不传入执行，也不传入产品。
r(count($storyTest->fetchExecutionStoriesTest($executionID[1], $productID[0]))) && p() && e('50'); //传入执行，不传入产品。
r(count($storyTest->fetchExecutionStoriesTest($executionID[0], $productID[1]))) && p() && e('0');  //传入产品，不传入执行。
r(count($storyTest->fetchExecutionStoriesTest($executionID[1], $productID[1]))) && p() && e('25'); //传入产品，传入执行。

$_SESSION['executionStoryBrowseType'] = 'changing';
r(count($storyTest->fetchExecutionStoriesTest($executionID[1], $productID[1]))) && p() && e('5'); //传入产品，传入执行，设置SESSION。

$storyTest->objectModel->app->loadClass('pager', $static = true);
$storyTest->objectModel->app->rawModule = 'product';
$storyTest->objectModel->app->rawMethod = 'track';
$_SESSION['executionStoryBrowseType'] = '';
$pager = new pager(0, 5, 1);
r(count($storyTest->fetchExecutionStoriesTest($executionID[1], $productID[1], $pager))) && p() && e('5'); //分页获取需求。
