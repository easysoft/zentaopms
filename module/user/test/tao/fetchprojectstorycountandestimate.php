#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchProjectStoryCountAndEstimate();
cid=19671
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$projectTable = zenData('project');
$projectTable->type->range('project');
$projectTable->multiple->range('1{4}');
$projectTable->vision->range('rnd{2},lite{2}');
$projectTable->deleted->range('0{5},1');
$projectTable->gen(4);

$projectStory = zenData('projectstory');
$projectStory->project->range('1-20{5}');
$projectStory->story->range('1-100');
$projectStory->gen(100);

$storyTable = zenData('story');
$storyTable->estimate->range('1-9');
$storyTable->gen(100);

global $config;

$userTest = new userTaoTest();

$projectIdList = array(1, 2, 3, 4);

$stories = $userTest->fetchProjectStoryCountAndEstimateTest(array());
r(count($stories)) && p()  && e(0); // 传入空数组，返回空数组。

$config->vision = 'rnd';
$stories = $userTest->fetchProjectStoryCountAndEstimateTest($projectIdList);
r(count($stories)) && p()           && e(2);  // 研发综合界面下有 2 个项目。
r($stories[1])     && p('count')    && e(5);  // 项目 1 的需求数为 5。
r($stories[1])     && p('estimate') && e(15); // 项目 2 的需求规模总和为 15。
r($stories[2])     && p('count')    && e(5);  // 项目 1 的需求数为 5。
r($stories[2])     && p('estimate') && e(31); // 项目 2 的需求规模总和为 31。

$config->vision = 'lite';
$stories = $userTest->fetchProjectStoryCountAndEstimateTest($projectIdList);
r(count($stories)) && p()           && e(2);  // 运营管理界面下有 2 个项目。
r($stories[3])     && p('count')    && e(5);  // 项目 3 的需求数为 5。
r($stories[3])     && p('estimate') && e(20); // 项目 4 的需求规模总和为 20。
r($stories[4])     && p('count')    && e(5);  // 项目 3 的需求数为 5。
r($stories[4])     && p('estimate') && e(27); // 项目 4 的需求规模总和为 27。
