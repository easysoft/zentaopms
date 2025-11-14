#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getExecutionStories();
cid=18530

- 不传入数据。 @0
- 传入有关联需求的项目数据，不传入产品数据。 @25
- 传入有关联需求的项目数据，传入有需求的产品数据。 @25
- 传入有关联需求的项目数据，传入无需求的产品数据。 @0
- 传入无关联需求的项目数据，不传入产品数据。 @0
- 传入无关联需求的项目数据，传入有需求的产品数据。 @0
- 传入无关联需求的项目数据，传入无需求的产品数据。 @0
- 传入有关联需求的项目数据，不传入产品数据。 @25
- 传入有关联需求的项目数据，传入有需求的产品数据。 @25
- 传入有关联需求的项目数据，传入无需求的产品数据。 @0
- 传入无关联需求的项目数据，不传入产品数据。 @0
- 传入无关联需求的项目数据，传入有需求的产品数据。 @0
- 传入无关联需求的项目数据，传入无需求的产品数据。 @0
- 传入分页。 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('product')->gen(100);
$projectstory = zenData('projectstory');
$projectstory->project->range('11{50},36{50}');
$projectstory->product->range('1');
$projectstory->story->range('1-50');
$projectstory->gen(100);

$story = zenData('story');
$story->product->range('1');
$story->gen(50);

$project = zenData('project');
$project->type->range('project{25},sprint{25}');
$project->gen(50);

global $tester;
$storyModel = $tester->loadModel('story');
r(count($storyModel->getExecutionStories(0,  0))) && p() && e('0');  //不传入数据。
r(count($storyModel->getExecutionStories(11, 0))) && p() && e('25'); //传入有关联需求的项目数据，不传入产品数据。
r(count($storyModel->getExecutionStories(11, 1))) && p() && e('25'); //传入有关联需求的项目数据，传入有需求的产品数据。
r(count($storyModel->getExecutionStories(11, 2))) && p() && e('0');  //传入有关联需求的项目数据，传入无需求的产品数据。
r(count($storyModel->getExecutionStories(12, 0))) && p() && e('0');  //传入无关联需求的项目数据，不传入产品数据。
r(count($storyModel->getExecutionStories(12, 1))) && p() && e('0');  //传入无关联需求的项目数据，传入有需求的产品数据。
r(count($storyModel->getExecutionStories(12, 2))) && p() && e('0');  //传入无关联需求的项目数据，传入无需求的产品数据。
r(count($storyModel->getExecutionStories(36, 0))) && p() && e('25'); //传入有关联需求的项目数据，不传入产品数据。
r(count($storyModel->getExecutionStories(36, 1))) && p() && e('25'); //传入有关联需求的项目数据，传入有需求的产品数据。
r(count($storyModel->getExecutionStories(36, 2))) && p() && e('0'); //传入有关联需求的项目数据，传入无需求的产品数据。
r(count($storyModel->getExecutionStories(37, 0))) && p() && e('0');  //传入无关联需求的项目数据，不传入产品数据。
r(count($storyModel->getExecutionStories(37, 1))) && p() && e('0');  //传入无关联需求的项目数据，传入有需求的产品数据。
r(count($storyModel->getExecutionStories(37, 2))) && p() && e('0');  //传入无关联需求的项目数据，传入无需求的产品数据。

$storyModel->app->loadClass('pager', $static = true);
$storyModel->app->rawModule = 'product';
$storyModel->app->rawMethod = 'track';
$pager = new pager(0, 5, 1);
r(count($storyModel->getExecutionStories(36, 1, 't1.`order`_desc', 'byModule', '0', 'story', '', $pager))) && p() && e('5'); //传入分页。
