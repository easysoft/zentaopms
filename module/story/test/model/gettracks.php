#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTracks();
cid=0

- 执行storyModel模块的getTracks方法，参数是0, '', 0, $pager  @0
- 执行$tracks[1]->track[11] @1
- 执行$tracks[1]->track[11] @1
- 执行$tracks[1]->track[11] @1
- 执行storyModel模块的getTracks方法，参数是1, 0, 1, $pager  @0
- 执行$tracks['noRequirement'][20] @1
- 执行$tracks['noRequirement'][11] @1
- 执行$tracks['noRequirement'][11] @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->gen(2);
$story = zdTable('story');
$story->product->range(1);
$story->parent->range('0{18},`-1`,19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

$projectstory = zdTable('projectstory');
$projectstory->project->range(1);
$projectstory->product->range(1);
$projectstory->story->range('1-18');
$projectstory->gen(18);

$case = zdTable('case');
$case->story->range(11);
$case->gen(5);

$bug = zdTable('bug');
$bug->story->range(11);
$bug->gen(5);

$task = zdTable('task');
$task->story->range('11{8},19{2}');
$task->parent->range('0{8},`-1`,9');
$task->gen(10);

$design = zdTable('design');
$design->story->range(11);
$design->gen(10);

$relation = zdTable('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('subdivideinto,subdividedfrom,completedin');
$relation->gen(24);

zdTable('repohistory')->gen(10);

global $tester;
$storyModel = $tester->loadModel('story');

$storyModel->config->URAndSR = 1;
$storyModel->app->loadClass('pager', $static = true);
$storyModel->app->moduleName = 'product';
$storyModel->app->methodName = 'track';
$pager = new pager(0, 20, 1);

r($storyModel->getTracks(0, '', 0, $pager)) && p() && e('0');
$tracks = $storyModel->getTracks(1, '', 0, $pager);
r(isset($tracks[1]->track[11])) && p() && e('1');
$tracks = $storyModel->getTracks(1, '', 1, $pager);
r(isset($tracks[1]->track[11])) && p() && e('1');
$tracks = $storyModel->getTracks(1, 0,  1, $pager);
r(isset($tracks[1]->track[11])) && p() && e('1');

$pager = new pager(0, 20, 2);
r($storyModel->getTracks(1, 0,  1, $pager)) && p() && e('0');
$tracks = $storyModel->getTracks(1, 0,  0, $pager);
r(isset($tracks['noRequirement'][20])) && p() && e('1');

$tester->config->URAndSR = 0;
$tracks = $storyModel->getTracks(1, '', 1, $pager);
r(isset($tracks['noRequirement'][11])) && p() && e('1');
$tracks = $storyModel->getTracks(1, 0,  1, $pager);
r(isset($tracks['noRequirement'][11])) && p() && e('1');
