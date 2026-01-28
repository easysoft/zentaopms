#!/usr/bin/env php
<?php

/**

title=测试 metricModel::checkHasInferenceOfDate();
timeout=0
cid=17069

- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'year', '2024-01-15'  @1
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'month', '2024-01-15'  @1
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'week', '2024-01-15'  @1
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'not_exist_metric', 'year', '2024-01-15'  @0
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'day', '2024-01-15'  @0
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'nodate', '2024-01-15'  @0
- 执行metricTest模块的checkHasInferenceOfDateTest方法，参数是'test_metric_year', 'year', '2025-01-01'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('metriclib');
$table->id->range('1-10');
$table->metricCode->range('test_metric_year{10}');
$table->calcType->range('inference{10}');
$table->year->range('2024{10}');
$table->month->range('01{10}');
$table->week->range('03{10}');
$table->gen(10);

su('admin');

$metricTest = new metricModelTest();

r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'year', '2024-01-15')) && p() && e('1');
r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'month', '2024-01-15')) && p() && e('1');
r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'week', '2024-01-15')) && p() && e('1');
r($metricTest->checkHasInferenceOfDateTest('not_exist_metric', 'year', '2024-01-15')) && p() && e('0');
r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'day', '2024-01-15')) && p() && e('0');
r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'nodate', '2024-01-15')) && p() && e('0');
r($metricTest->checkHasInferenceOfDateTest('test_metric_year', 'year', '2025-01-01')) && p() && e('0');