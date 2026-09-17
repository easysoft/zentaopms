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

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$config->edition = 'gitfox';

$blockTest = new blockZenTest();

// 准备GitFox度量指标定义数据
zenData('metric')->gen(0);
$metric = zenData('metric');
$metric->id->range('1-4');
$metric->code->range('count_of_daily_gitfox_commits,count_of_daily_gitfox_created_reviews,count_of_daily_gitfox_merged_reviews,count_of_daily_gitfox_resolved_issues');
$metric->scope->range('system{4}');
$metric->dateType->range('day{4}');
$metric->stage->range('released{4}');
$metric->gen(4);

// 测试步骤1：无任何度量数据的情况
zenData('metriclib')->gen(0);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits,yesterdayCommits,createdReviews,mergedReviews,resolvedIssues') && e('0,0,0,0,0'); // 测试步骤1：无任何度量数据

// 测试步骤2：有今日和昨日数据的情况
$metriclib = zenData('metriclib');
$metriclib->id->range('1-8');
$metriclib->metricCode->range('count_of_daily_gitfox_commits{2},count_of_daily_gitfox_created_reviews{2},count_of_daily_gitfox_merged_reviews{2},count_of_daily_gitfox_resolved_issues{2}');
$metriclib->system->range('1{8}');
$metriclib->year->range(date('Y') . '{8}');
$metriclib->month->range(date('m') . '{8}');
$metriclib->day->range(date('d') . '{4},' . date('d', strtotime('-1 day')) . '{4}');
$metriclib->value->range('8,18,6,16,7,17,9,19');
$metriclib->calcType->range('cron{8}');
$metriclib->gen(8);
r($blockTest->printGitfoxTeamAchievementBlockTest()) && p('commits,yesterdayCommits,createdReviews,yesterdayCreatedReviews,mergedReviews,yesterdayMergedReviews,resolvedIssues,yesterdayResolvedIssues') && e('8,18,6,16,7,17,9,19'); // 测试步骤2：有今日和昨日数据
