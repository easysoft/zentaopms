#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTeamAchievementBlock();
timeout=0
cid=0

- 测试步骤1：无任何度量数据
 - 属性finishedTasks @0
 - 属性yesterdayTasks @0
 - 属性createdStories @0
 - 属性yesterdayStories @0
 - 属性closedBugs @0
- 测试步骤2：仅有今日数据
 - 属性finishedTasks @8
 - 属性createdStories @6
 - 属性closedBugs @7
 - 属性runCases @9
 - 属性consumedHours @10
- 测试步骤3：仅有昨日数据
 - 属性yesterdayTasks @18
 - 属性yesterdayStories @16
 - 属性yesterdayBugs @17
 - 属性yesterdayCases @19
 - 属性yesterdayHours @20
- 测试步骤4：有今日和昨日数据
 - 属性finishedTasks @28
 - 属性yesterdayTasks @26
 - 属性createdStories @27
 - 属性yesterdayStories @29
- 测试步骤5：部分指标有数据
 - 属性finishedTasks @46
 - 属性createdStories @0
 - 属性closedBugs @0
 - 属性runCases @0
 - 属性consumedHours @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$blockTest = new blockZenTest();

// 准备度量指标定义数据
$metric = zenData('metric');
$metric->id->range('1-5');
$metric->code->range('count_of_daily_finished_task,count_of_daily_created_story,count_of_daily_closed_bug,count_of_daily_run_case,hour_of_daily_effort');
$metric->scope->range('system{5}');
$metric->dateType->range('day{5}');
$metric->stage->range('released{5}');
$metric->gen(5);

// 测试步骤1：无任何度量数据的情况
zenData('metriclib')->gen(0);
r($blockTest->printTeamAchievementBlockTest()) && p('finishedTasks,yesterdayTasks,createdStories,yesterdayStories,closedBugs') && e('0,0,0,0,0'); // 测试步骤1：无任何度量数据
// 测试步骤2：仅有今日数据的情况
$metriclib = zenData('metriclib');
$metriclib->id->range('1-10');
$metriclib->metricCode->range('count_of_daily_finished_task{2},count_of_daily_created_story{2},count_of_daily_closed_bug{2},count_of_daily_run_case{2},hour_of_daily_effort{2}');
$metriclib->system->range('1{10}');
$metriclib->year->range(date('Y') . '{10}');
$metriclib->month->range(date('m') . '{10}');
$metriclib->day->range(date('d') . '{10}');
$metriclib->value->range('5,8,3,6,4,7,2,9,1,10');
$metriclib->calcType->range('cron{10}');
$metriclib->gen(10);
r($blockTest->printTeamAchievementBlockTest()) && p('finishedTasks,createdStories,closedBugs,runCases,consumedHours') && e('8,6,7,9,10'); // 测试步骤2：仅有今日数据
// 测试步骤3：仅有昨日数据的情况
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1-10');
$metriclib->metricCode->range('count_of_daily_finished_task{2},count_of_daily_created_story{2},count_of_daily_closed_bug{2},count_of_daily_run_case{2},hour_of_daily_effort{2}');
$metriclib->system->range('1{10}');
$metriclib->year->range(date('Y', strtotime('-1 day')) . '{10}');
$metriclib->month->range(date('m', strtotime('-1 day')) . '{10}');
$metriclib->day->range(date('d', strtotime('-1 day')) . '{10}');
$metriclib->value->range('15,18,13,16,14,17,12,19,11,20');
$metriclib->calcType->range('cron{10}');
$metriclib->gen(10);
r($blockTest->printTeamAchievementBlockTest()) && p('yesterdayTasks,yesterdayStories,yesterdayBugs,yesterdayCases,yesterdayHours') && e('18,16,17,19,20'); // 测试步骤3：仅有昨日数据
// 测试步骤4：有今日和昨日数据的正常情况
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1-20');
$metriclib->metricCode->range('count_of_daily_finished_task{4},count_of_daily_created_story{4},count_of_daily_closed_bug{4},count_of_daily_run_case{4},hour_of_daily_effort{4}');
$metriclib->system->range('1{20}');
$todayYear = date('Y');
$todayMonth = date('m');
$todayDay = date('d');
$yesterdayYear = date('Y', strtotime('-1 day'));
$yesterdayMonth = date('m', strtotime('-1 day'));
$yesterdayDay = date('d', strtotime('-1 day'));
$metriclib->year->range("$todayYear{2},$yesterdayYear{2},$todayYear{2},$yesterdayYear{2},$todayYear{2},$yesterdayYear{2},$todayYear{2},$yesterdayYear{2},$todayYear{2},$yesterdayYear{2}");
$metriclib->month->range("$todayMonth{2},$yesterdayMonth{2},$todayMonth{2},$yesterdayMonth{2},$todayMonth{2},$yesterdayMonth{2},$todayMonth{2},$yesterdayMonth{2},$todayMonth{2},$yesterdayMonth{2}");
$metriclib->day->range("$todayDay{2},$yesterdayDay{2},$todayDay{2},$yesterdayDay{2},$todayDay{2},$yesterdayDay{2},$todayDay{2},$yesterdayDay{2},$todayDay{2},$yesterdayDay{2}");
$metriclib->value->range('25,28,23,26,24,27,22,29,21,30,35,38,33,36,34,37,32,39,31,40');
$metriclib->calcType->range('cron{20}');
$metriclib->gen(20);
r($blockTest->printTeamAchievementBlockTest()) && p('finishedTasks,yesterdayTasks,createdStories,yesterdayStories') && e('28,26,27,29'); // 测试步骤4：有今日和昨日数据
// 测试步骤5：部分指标有数据的情况（仅任务和需求有数据）
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1-8');
$metriclib->metricCode->range('count_of_daily_finished_task{4},count_of_daily_created_story{4}');
$metriclib->system->range('1{8}');
$metriclib->year->range(date('Y') . '{4},' . date('Y', strtotime('-1 day')) . '{4}');
$metriclib->month->range(date('m') . '{4},' . date('m', strtotime('-1 day')) . '{4}');
$metriclib->day->range(date('d') . '{4},' . date('d', strtotime('-1 day')) . '{4}');
$metriclib->value->range('45,48,43,46,55,58,53,56');
$metriclib->calcType->range('cron{8}');
$metriclib->gen(8);
r($blockTest->printTeamAchievementBlockTest()) && p('finishedTasks,createdStories,closedBugs,runCases,consumedHours') && e('46,0,0,0,0'); // 测试步骤5：部分指标有数据