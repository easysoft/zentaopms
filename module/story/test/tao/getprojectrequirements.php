#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getProjectRequirements();
timeout=0
cid=18642

- 执行storyModel模块的getProjectRequirements方法，参数是0, 0  @0
- 执行storyModel模块的getProjectRequirements方法，参数是0, 1  @0
- 执行storyModel模块的getProjectRequirements方法，参数是1, 0  @0
- 执行storyModel模块的getProjectRequirements方法，参数是1, 1  @9
- 执行storyModel模块的getProjectRequirements方法，参数是1, 1, $pager  @5
- 执行storyModel模块的getProjectRequirements方法，参数是1, 1  @8

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

$projectstory = zenData('projectstory');
$projectstory->project->range(1);
$projectstory->product->range(1);
$projectstory->story->range('1-18');
$projectstory->gen(18);

$story = zenData('story');
$story->product->range(1);
$story->gen(20);

$relation = zenData('relation');
$relation->product->range(1);
$relation->AID->range('1,11,2,12,3,13,4,14,5,15,6,16,7,17,8,18');
$relation->BID->range('11,1,12,2,13,3,14,4,15,5,16,6,17,7,18,8');
$relation->gen(16);

global $tester, $app;
$storyModel = $tester->loadModel('story');
$app->rawModule = 'story';
$app->rawMethod = 'story';

r($storyModel->getProjectRequirements(0, 0)) && p() && e('0');
r($storyModel->getProjectRequirements(0, 1)) && p() && e('0');
r($storyModel->getProjectRequirements(1, 0)) && p() && e('0');
r(count($storyModel->getProjectRequirements(1, 1))) && p() && e('9');

$storyModel->app->moduleName = 'product';
$storyModel->app->methodName = 'track';
$storyModel->app->loadClass('pager', $static = true);
$pager = new pager(0, 5, 1);
r(count($storyModel->getProjectRequirements(1, 1, $pager))) && p() && e('5');

$storyModel->dao->update(TABLE_STORY)->set('deleted')->eq(1)->where('id')->eq(1)->exec();
r(count($storyModel->getProjectRequirements(1, 1))) && p() && e('8');
