#!/usr/bin/env php
<?php

/**

title=测试 storyModel->fetchProjectStories();
cid=18626

- 不传入项目，也不传入产品。 @0
- 传入项目，不传入产品。 @50
- 传入产品，不传入项目。 @0
- 传入产品，传入项目。 @25
- 获取草稿类型的需求。 @13
- 获取非关闭的需求。 @13
- 获取所有分支的需求。 @25
- 获取分支 ID 为 1 的需求。 @20
- 不传入项目，获取关联执行的需求。 @0
- 不传入项目，获取未关联执行的需求。 @0
- 传入项目，获取关联执行的需求。 @1
- 传入项目，获取未关联执行的需求。 @24
- 分页获取需求。 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('product')->gen(10);
zenData('project')->gen(50);

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
$story->gen(50);

$storyTest = new storyTest();

$productID = array(0, 1);
$projectID = array(0, 11);

r(count($storyTest->fetchProjectStoriesTest($productID[0], $projectID[0]))) && p() && e('0');  //不传入项目，也不传入产品。
r(count($storyTest->fetchProjectStoriesTest($productID[0], $projectID[1]))) && p() && e('50'); //传入项目，不传入产品。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[0]))) && p() && e('0');  //传入产品，不传入项目。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1]))) && p() && e('25'); //传入产品，传入项目。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'draft')))             && p() && e('13'); //获取草稿类型的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'unclosed')))          && p() && e('13'); //获取非关闭的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'bybranch')))          && p() && e('25'); //获取所有分支的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'bybranch', '1')))     && p() && e('20'); //获取分支 ID 为 1 的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[0], 'linkedexecution')))   && p() && e('0');  //不传入项目，获取关联执行的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[0], 'unlinkedexecution'))) && p() && e('0');  //不传入项目，获取未关联执行的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'linkedexecution')))   && p() && e('1');  //传入项目，获取关联执行的需求。
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'unlinkedexecution'))) && p() && e('24'); //传入项目，获取未关联执行的需求。

$storyTest->objectModel->app->loadClass('pager', $static = true);
$storyTest->objectModel->app->rawModule = 'product';
$storyTest->objectModel->app->rawMethod = 'track';
$pager = new pager(0, 5, 1);
r(count($storyTest->fetchProjectStoriesTest($productID[1], $projectID[1], 'unclosed', '', $pager))) && p() && e('5'); //分页获取需求。
