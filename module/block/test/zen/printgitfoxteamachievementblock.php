#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printGitfoxTeamAchievementBlock();
timeout=0
cid=15307

- 测试步骤1：无任何度量数据
 - 属性commits @0
 - 属性yesterdayCommits @0
 - 属性createdReviews @0
 - 属性mergedReviews @0
 - 属性resolvedIssues @0
- 测试步骤2：有今日和昨日数据
 - 属性commits @8
 - 属性yesterdayCommits @18
 - 属性createdReviews @6
 - 属性yesterdayCreatedReviews @16
 - 属性mergedReviews @7
 - 属性yesterdayMergedReviews @17
 - 属性resolvedIssues @9
 - 属性yesterdayResolvedIssues @19
- 测试步骤3：只有今日数据时返回今日的提交数 @1
- 测试步骤4：只有今日数据时返回昨日的提交数 @0
- 测试步骤5：同一天存在多条提交数据时取最后一条 @9
- 测试步骤6：只有昨日数据时返回今日的提交数 @0
- 测试步骤7：只有昨日数据时返回昨日的提交数 @7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$config->edition = 'gitfox';

$blockTest = new blockZenTest();

/* 准备GitFox度量指标定义数据。*/
zenData('metric')->gen(0);
$metric = zenData('metric');
$metric->id->range('1-4');
$metric->code->range('count_of_daily_gitfox_commits,count_of_daily_gitfox_created_reviews,count_of_daily_gitfox_merged_reviews,count_of_daily_gitfox_resolved_issues');
$metric->scope->range('system{4}');
$metric->dateType->range('day{4}');
$metric->stage->range('released{4}');
$metric->gen(4);

/* 测试步骤1：无任何度量数据的情况。*/
zenData('metriclib')->gen(0);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits,yesterdayCommits,createdReviews,mergedReviews,resolvedIssues') && e('0,0,0,0,0'); // 测试步骤1：无任何度量数据

/* 测试步骤2：有今日和昨日数据的情况。*/
$metriclib = zenData('metriclib');
$metriclib->id->range('1-8');
$metriclib->metricCode->range('count_of_daily_gitfox_commits{2},count_of_daily_gitfox_created_reviews{2},count_of_daily_gitfox_merged_reviews{2},count_of_daily_gitfox_resolved_issues{2}');
$metriclib->system->range('1{8}');
$metriclib->year->range(date('Y') . '{8}');
$metriclib->month->range(date('m') . '{8}');
$metriclib->day->range(date('d') . ',' . date('d', strtotime('-1 day')) . ',' . date('d') . ',' . date('d', strtotime('-1 day')) . ',' . date('d') . ',' . date('d', strtotime('-1 day')) . ',' . date('d') . ',' . date('d', strtotime('-1 day')));
$metriclib->value->range('8,18,6,16,7,17,9,19');
$metriclib->calcType->range('cron{8}');
$metriclib->gen(8);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits,yesterdayCommits,createdReviews,yesterdayCreatedReviews,mergedReviews,yesterdayMergedReviews,resolvedIssues,yesterdayResolvedIssues') && e('8,18,6,16,7,17,9,19'); // 测试步骤2：有今日和昨日数据

/* 测试步骤3、4：只有今日数据时，今日统计值为对应记录值，昨日统计值为0。*/
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1-4');
$metriclib->metricCode->range('count_of_daily_gitfox_commits,count_of_daily_gitfox_created_reviews,count_of_daily_gitfox_merged_reviews,count_of_daily_gitfox_resolved_issues');
$metriclib->system->range('1{4}');
$metriclib->year->range(date('Y') . '{4}');
$metriclib->month->range(date('m') . '{4}');
$metriclib->day->range(date('d') . '{4}');
$metriclib->value->range('1,2,3,4');
$metriclib->calcType->range('cron{4}');
$metriclib->gen(4);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits') && e('1');          // 测试步骤3：只有今日数据时返回今日的提交数
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('yesterdayCommits') && e('0'); // 测试步骤4：只有今日数据时返回昨日的提交数

/* 测试步骤5：同一天存在多条同度量数据时，以最后一条为准。*/
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1-2');
$metriclib->metricCode->range('count_of_daily_gitfox_commits{2}');
$metriclib->system->range('1{2}');
$metriclib->year->range(date('Y') . '{2}');
$metriclib->month->range(date('m') . '{2}');
$metriclib->day->range(date('d') . '{2}');
$metriclib->value->range('5,9');
$metriclib->calcType->range('cron{2}');
$metriclib->gen(2);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits') && e('9'); // 测试步骤5：同一天存在多条提交数据时取最后一条

/* 测试步骤6、7：只有昨日数据时，昨日统计值为对应记录值，今日统计值为0。*/
zenData('metriclib')->gen(0);
$metriclib = zenData('metriclib');
$metriclib->id->range('1');
$metriclib->metricCode->range('count_of_daily_gitfox_commits');
$metriclib->system->range('1');
$metriclib->year->range(date('Y', strtotime('-1 day')) . '');
$metriclib->month->range(date('m', strtotime('-1 day')) . '');
$metriclib->day->range(date('d', strtotime('-1 day')) . '');
$metriclib->value->range('7');
$metriclib->calcType->range('cron');
$metriclib->gen(1);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits') && e('0');          // 测试步骤6：只有昨日数据时返回今日的提交数
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('yesterdayCommits') && e('7'); // 测试步骤7：只有昨日数据时返回昨日的提交数
