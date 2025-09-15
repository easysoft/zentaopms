#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isCalcByCron();
timeout=0
cid=0

- 步骤1：正常查询年度度量项 @1
- 步骤2：正常查询月度度量项 @1
- 步骤3：查询不存在的日度量项 @0
- 步骤4：查询不存在的周度量项 @0
- 步骤5：查询不存在的度量项代码 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->id->range('1-6');
$table->metricID->range('0{6}');
$table->metricCode->range('test_metric_year{2}, test_metric_month{2}, test_metric_day{1}, test_metric_week{1}');
$table->system->range('0{6}');
$table->program->range('0{6}');
$table->project->range('0{6}');
$table->product->range('0{6}');
$table->execution->range('0{6}');
$table->code->range('[]{6}');
$table->pipeline->range('[]{6}');
$table->user->range('NULL{6}');
$table->dept->range('[]{6}');
$table->calcType->range('cron{6}');
$table->calculatedBy->range('[]{6}');
$table->value->range('10-60:10');
$table->year->range('2024{6}');
$table->month->range('01{2}, 02{2}, 03{2}');
$table->day->range('01{2}, 15{2}, 20{2}');
$table->week->range('1{2}, 6{2}, 12{2}');
$table->date->range('2024-01-01 10:00:00{1}, 2024-01-15 11:00:00{1}, 2024-02-01 10:00:00{1}, 2024-02-15 11:00:00{1}, 2024-03-20 10:00:00{1}, 2024-03-21 11:00:00{1}');
$table->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->isCalcByCronTest('test_metric_year', '2024', 'year')) && p() && e('1'); // 步骤1：正常查询年度度量项
r($metricTest->isCalcByCronTest('test_metric_month', '2024-01', 'month')) && p() && e('1'); // 步骤2：正常查询月度度量项
r($metricTest->isCalcByCronTest('test_metric_day', '2024-01-01', 'day')) && p() && e('0'); // 步骤3：查询不存在的日度量项
r($metricTest->isCalcByCronTest('test_metric_week', '2024-01', 'week')) && p() && e('0'); // 步骤4：查询不存在的周度量项
r($metricTest->isCalcByCronTest('nonexistent_metric', '2024', 'year')) && p() && e('0'); // 步骤5：查询不存在的度量项代码