#!/usr/bin/env php
<?php

/**

title=测试 customModel::hasWaterfallData();
timeout=0
cid=15916

- 步骤1：测试系统中无任何项目数据 @0
- 步骤2：测试系统中有瀑布项目但已删除 @0
- 步骤3：测试系统中有3个未删除的瀑布项目 @3
- 步骤4：测试混合项目类型，只统计瀑布项目 @2
- 步骤5：测试大量瀑布项目数据 @50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('project')->gen(0);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();

r($customTester->hasWaterfallDataTest()) && p() && e('0'); // 步骤1：测试系统中无任何项目数据

$projectTable = zenData('project');
$projectTable->model->range('waterfall');
$projectTable->deleted->range('1');
$projectTable->gen(2);
r($customTester->hasWaterfallDataTest()) && p() && e('0'); // 步骤2：测试系统中有瀑布项目但已删除

zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfall');
$projectTable->deleted->range('0');
$projectTable->gen(3);
r($customTester->hasWaterfallDataTest()) && p() && e('3'); // 步骤3：测试系统中有3个未删除的瀑布项目

zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfall{2},scrum{3},kanban{1}');
$projectTable->deleted->range('0');
$projectTable->gen(6);
r($customTester->hasWaterfallDataTest()) && p() && e('2'); // 步骤4：测试混合项目类型，只统计瀑布项目

zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfall');
$projectTable->deleted->range('0');
$projectTable->gen(50);
r($customTester->hasWaterfallDataTest()) && p() && e('50'); // 步骤5：测试大量瀑布项目数据