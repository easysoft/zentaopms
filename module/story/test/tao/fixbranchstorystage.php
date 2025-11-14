#!/usr/bin/env php
<?php

/**

title=测试 storyModel->fixBranchStoryStage();
timeout=0
cid=18628

- 不传入任何项目。 @0
- 传入需求，检查结果。 @testing
- 传入需求，检查结果。 @wait
- 传入需求，检查结果。 @tested
- 传入需求，检查结果。 @tested

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$product = zenData('product');
$product->type->range('normal,branch');
$product->gen(2);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-5');
$projectproduct->product->range('1-2');
$projectproduct->branch->range('0,1,2');
$projectproduct->gen(3);

$projectstory = zenData('projectstory');
$projectstory->product->range('1-2');
$projectstory->story->range('1-50');
$projectstory->project->range('1-5');
$projectstory->branch->range('0{25},1{25}');
$projectstory->gen(50);

$storystage = zenData('storystage');
$storystage->story->range('1-25{2}');
$storystage->branch->range('0,1');
$storystage->gen(50);

$story = zenData('story');
$story->product->range('1');
$story->type->range('story');
$story->stage->range('wait,planned,projected,developing,developed,testing,tested,verified,released,closed');
$story->branch->range('0{25},1{25}');
$story->gen(50);

global $tester;
$storyModel = $tester->loadModel('story');
$stories = $storyModel->dao->select('*')->from(TABLE_STORY)->where('product')->eq(1)->andWhere('type')->eq('story')->fetchAll('id');

r($storyModel->fixBranchStoryStage(array()))  && p() && e('0');  //不传入任何项目。

$oldStage6 = $stories[6]->stage;
$oldStage7 = $stories[7]->stage;
$stories   = $storyModel->fixBranchStoryStage($stories);
$newStage6 = $stories[6]->stage;
$newStage7 = $stories[7]->stage;
r($oldStage6) && p() && e('testing'); //传入需求，检查结果。
r($newStage6) && p() && e('wait'); //传入需求，检查结果。

r($oldStage7) && p() && e('tested'); //传入需求，检查结果。
r($newStage7) && p() && e('tested'); //传入需求，检查结果。