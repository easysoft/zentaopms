#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallReportBlock();
timeout=0
cid=15313

- 执行blockTest模块的printWaterfallReportBlockTest方法  @1
- 执行blockTest模块的printWaterfallReportBlockTest方法  @1
- 执行blockTest模块的printWaterfallReportBlockTest方法  @0
- 执行blockTest模块的printWaterfallReportBlockTest方法  @0
- 执行blockTest模块的printWaterfallReportBlockTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('project');
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('瀑布项目1,瀑布项目2,瀑布项目3,瀑布项目4,瀑布项目5');
$project->status->range('wait{2},doing{2},done{1}');
$project->model->range('waterfall{3},waterfallplus{2}');
$project->type->range('project');
$project->deleted->range('0');
$project->begin->range('20240101,20240201,20240301,20240401,20240501');
$project->end->range('20241231,20250131,20250228,20250331,20250430');
$project->gen(5);

zenData('user');
$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4,user5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5');
$user->deleted->range('0');
$user->gen(6);

zenData('metriclib');
$metriclib = zenData('metriclib');
$metriclib->id->range('1-20');
$metriclib->project->range('1-5');
$metriclib->metricCode->range('count_of_opened_risk_in_project,count_of_opened_issue_in_project');
$metriclib->value->range('1-10');
$metriclib->year->range('2024');
$metriclib->month->range('01-12');
$metriclib->gen(20);

su('admin');

$blockTest = new blockTest();

// 设置session项目ID
global $app;
$app->session->set('project', 1);

r($blockTest->printWaterfallReportBlockTest()) && p() && e('1');

// 测试教程模式
$originalTutorialMode = $app->config->vision ?? '';
$app->config->vision = 'tutorial';
r($blockTest->printWaterfallReportBlockTest()) && p() && e('1');
$app->config->vision = $originalTutorialMode;

// 测试无项目数据情况
$app->session->set('project', 999);
r($blockTest->printWaterfallReportBlockTest()) && p() && e('0');

// 测试session无project
$app->session->set('project', 0);
r($blockTest->printWaterfallReportBlockTest()) && p() && e('0');

// 测试正常项目数据
$app->session->set('project', 2);
r($blockTest->printWaterfallReportBlockTest()) && p() && e('1');