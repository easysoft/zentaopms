#!/usr/bin/env php
<?php

/**

title=测试 metricModel::clearOutDatedRecords();
timeout=0
cid=17071

- 执行metricTest模块的clearOutDatedRecordsTest方法，参数是'test_metric_001', 'year'  @2
- 执行metricTest模块的clearOutDatedRecordsTest方法，参数是'test_metric_001', 'month'  @0
- 执行metricTest模块的clearOutDatedRecordsTest方法，参数是'test_metric_001', 'week'  @0
- 执行metricTest模块的clearOutDatedRecordsTest方法，参数是'test_metric_001', 'day'  @0
- 执行metricTest模块的clearOutDatedRecordsTest方法，参数是'test_metric_001', 'invalid'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 每个测试步骤都重新生成数据，避免互相干扰
$metricTest = new metricTest();

// 生成测试数据
$table = zenData('metriclib');
$table->metricCode->range('test_metric_001{2}');
$table->year->range('2025{2}');
$table->month->range('9{2}');
$table->week->range('37{2}');
$table->day->range('8{2}');
$table->value->range('1-2');
$table->gen(2);

su('admin');

r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'year')) && p() && e('2');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'month')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'week')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'day')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'invalid')) && p() && e('0');