#!/usr/bin/env php
<?php

/**

title=测试 storyModel->fixBranchStoryStage();
cid=0

- 不传入任何项目。 @0
- 传入需求，检查结果。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

$product = zdTable('product');
$product->type->range('normal,branch');
$product->gen(2);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-5');
$projectproduct->product->range('1-2');
$projectproduct->branch->range('0,1,2');
$projectproduct->gen(3);

$projectstory = zdTable('projectstory');
$projectstory->product->range('1-2');
$projectstory->story->range('1-50');
$projectstory->project->range('1-5');
$projectstory->branch->range('0{25},1{25}');
$projectstory->gen(50);

$storystage = zdTable('storystage');
$storystage->story->range('1-25{2}');
$storystage->branch->range('0,1');
$storystage->gen(50);

$story = zdTable('story');
$story->product->range('1');
$story->type->range('story');
$story->stage->range('wait,planned,projected,developing,developed,testing,tested,verified,released,closed');
$story->branch->range('0{25},1{25}');
$story->gen(50);

global $tester;
$storyModel = $tester->loadModel('story');
$stories = $storyModel->dao->select('*')->from(TABLE_STORY)->where('product')->eq(1)->andWhere('type')->eq('story')->fetchAll('id');

r($storyModel->fixBranchStoryStage(array()))  && p() && e('0');  //不传入任何项目。

$oldStage = $stories[6]->stage;
$stories  = $storyModel->fixBranchStoryStage($stories);
$newStage = $stories[6]->stage;
r(($oldStage == 'testing' && $newStage == 'wait')) && p() && e('1');  //传入需求，检查结果。
