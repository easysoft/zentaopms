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
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('metriclib')->gen(0);

$metricTest = new metricModelTest();

/* Build records that match the current date so the tested method can remove them. */
$year  = date('Y');
$month = date('n');
$week  = date('W');
$day   = date('j');
$now = date('Y-m-d H:i:s');

for($i = 1; $i <= 2; $i++)
{
    $record = new stdClass();
    $record->id         = $i;
    $record->metricID   = 1;
    $record->metricCode = 'test_metric_001';
    $record->system     = 1;
    $record->year       = (string)$year;
    $record->month      = (string)$month;
    $record->week       = (string)$week;
    $record->day        = (string)$day;
    $record->value      = (string)$i;
    $record->date       = $now;
    $record->deleted    = '0';

    $metricTest->instance->dao->insert(TABLE_METRICLIB)->data($record)->exec();
}

su('admin');

r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'year')) && p() && e('2');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'month')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'week')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'day')) && p() && e('0');
r($metricTest->clearOutDatedRecordsTest('test_metric_001', 'invalid')) && p() && e('0');