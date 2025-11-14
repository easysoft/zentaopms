#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getProjectsForTrack();
cid=18643

- 传入空参数。 @0
- 执行$projects['project'] @6;7;8;9;10
- 执行$projects['execution'] @6;7;8;9;10
- 执行$projects['project'][6] @1
- 执行$projects['execution'][6] @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$projectStory = zenData('projectstory');
$projectStory->project->range('1-2{5}');
$projectStory->product->range('1');
$projectStory->story->range('6-10');
$projectStory->gen(10);

$project = zenData('project');
$project->id->range('1-2');
$project->type->range('project,scrum');
$project->gen('2');

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

r(count($tester->story->getProjectsForTrack(array()))) && p() && e('0');  //传入空参数。

$projects = $tester->story->getProjectsForTrack(array(5,6,7,8,9,10));
r(implode(';', array_keys($projects['project'])))      && p() && e('6;7;8;9;10');
r(implode(';', array_keys($projects['execution'])))    && p() && e('6;7;8;9;10');
r(implode(';', array_keys($projects['project'][6])))   && p() && e('1');
r(implode(';', array_keys($projects['execution'][6]))) && p() && e('2');
