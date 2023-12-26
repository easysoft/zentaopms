#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

zdTable('product')->gen(10);
$project = zdTable('project');
$project->type->range('sprint');
$project->gen(50);

$projectstory = zdTable('projectstory');
$projectstory->product->range('1-2');
$projectstory->story->range('1-50');
$projectstory->project->range('11');
$projectstory->branch->range('0{30},1{10},2{10}');
$projectstory->gen(50);

$story = zdTable('story');
$story->product->range('1-2');
$story->type->range('story');
$story->branch->range('0{30},1{10},2{10}');
$story->status->range('draft,reviewing,active,closed,changing');
$story->gen(50);

/**

title=测试 storyModel->fetchExecutionStories();
cid=1
pid=1

*/

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
$storyTest->objectModel->app->moduleName = 'product';
$storyTest->objectModel->app->methodName = 'track';
$_SESSION['executionStoryBrowseType'] = '';
$pager = new pager(0, 5, 1);
r(count($storyTest->fetchExecutionStoriesTest($executionID[1], $productID[1], $pager))) && p() && e('5'); //分页获取需求。
