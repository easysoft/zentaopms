#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDesignsForTrack();
timeout=0
cid=18634

- 传入空参数。 @2
- 执行$designs['design'] @6;7;8;9;10
- 执行$designs['commit'] @6;7;8;9;10
- 执行$designs['design'][6] @1
- 执行$designs['commit'][6] @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$design = zenData('design');
$design->project->range('2{5}');
$design->product->range('1');
$design->story->range('6-10');
$design->commit->range('5-1');
$design->gen(5);

$commit = zenData('repohistory');
$commit->id->range('1-5');
$commit->comment->range('1-5')->prefix('comment');
$commit->gen('5');

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11);

su('admin');

global $tester;
$tester->loadModel('story');

r(count($tester->story->getDesignsForTrack(array()))) && p() && e('2');  //传入空参数。

$designs = $tester->story->getDesignsForTrack(array(5,6,7,8,9,10));
r(implode(';', array_keys($designs['design'])))    && p() && e('6;7;8;9;10');
r(implode(';', array_keys($designs['commit'])))    && p() && e('6;7;8;9;10');
r(implode(';', array_keys($designs['design'][6]))) && p() && e('1');
r(implode(';', array_keys($designs['commit'][6]))) && p() && e('5');