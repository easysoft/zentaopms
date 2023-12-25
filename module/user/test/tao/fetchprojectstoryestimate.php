#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchProjectStoryEstimate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$projectTable = zdTable('project');
$projectTable->project->range('1-4{6}');
$projectTable->type->range('sprint,stage,kanban');
$projectTable->multiple->range('1{4},0,1');
$projectTable->vision->range('rnd{12},lite{8}');
$projectTable->deleted->range('0{5},1');
$projectTable->gen(20);

$projectStory = zdTable('projectstory');
$projectStory->project->range('1-20{5}');
$projectStory->story->range('1-100');
$projectStory->gen(100);

$storyTable = zdTable('story');
$storyTable->estimate->range('1-9');
$storyTable->gen(100);

global $config;

$userTest = new userTest();

$projectIdList = array(1, 2, 3, 4);

$stories = $userTest->fetchProjectStoryEstimateTest(array());
r(count($stories)) && p()  && e(0); // 传入空数组，返回空数组。

$config->vision = 'rnd';
$stories = $userTest->fetchProjectStoryEstimateTest($projectIdList);
r(count($stories)) && p()  && e(2);  // 研发综合界面下有 2 个项目。
r($stories)        && p(1) && e(93); // 项目 1 的需求规模总和为 93。
r($stories)        && p(2) && e(99); // 项目 2 的需求规模总和为 99。

$config->vision = 'lite';
$stories = $userTest->fetchProjectStoryEstimateTest($projectIdList);
r(count($stories)) && p()  && e(2);   // 运营管理界面下有 2 个项目。
r($stories)        && p(3) && e(105); // 项目 3 的需求规模总和为 105。
r($stories)        && p(4) && e(46);  // 项目 4 的需求规模总和为 46。
