#!/usr/bin/env php
<?php

/**

title=测试 metricZen::completeMissingRecords();
timeout=0
cid=17185

- 执行metricZenTest模块的completeMissingRecordsZenTest方法，参数是$records, $metric 第0条的value属性 @0
- 执行metricZenTest模块的completeMissingRecordsZenTest方法，参数是$records, $metric 第0条的value属性 @0
- 执行metricZenTest模块的completeMissingRecordsZenTest方法，参数是$records, $metric 第0条的value属性 @0
- 执行metricZenTest模块的completeMissingRecordsZenTest方法，参数是$records, $metric 第0条的value属性 @0
- 执行metricZenTest模块的completeMissingRecordsZenTest方法，参数是$records, $metric 第0条的value属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

$table = zenData('metric');
$table->loadYaml('metric_completemissingrecords', false, 2)->gen(5);

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('project')->loadYaml('project', false, 4)->gen(10);

su('admin');

$metricZenTest = new metricZenTest();

// 测试步骤1：正常system范围记录补全 - 测试记录数量
$metric = new stdClass();
$metric->id = 1;
$metric->code = 'test_metric';
$metric->scope = 'system';
$metric->dateType = 'day';

$records = array();
$record = new stdClass();
$record->value = 100;
$record->year = 2025;
$record->month = 9;
$record->day = 15;
$records[] = $record;

r($metricZenTest->completeMissingRecordsZenTest($records, $metric)) && p('0:value') && e('0');

// 测试步骤2：product范围记录补全 - 测试返回数据结构
$metric = new stdClass();
$metric->id = 2;
$metric->code = 'product_metric';
$metric->scope = 'product';
$metric->dateType = 'day';

$records = array();

r($metricZenTest->completeMissingRecordsZenTest($records, $metric)) && p('0:value') && e('0');

// 测试步骤3：project范围记录补全 - 测试返回数据结构
$metric = new stdClass();
$metric->id = 3;
$metric->code = 'project_metric';
$metric->scope = 'project';
$metric->dateType = 'week';

$records = array();

r($metricZenTest->completeMissingRecordsZenTest($records, $metric)) && p('0:value') && e('0');

// 测试步骤4：空记录数组输入 - system范围应返回1条记录
$metric = new stdClass();
$metric->id = 4;
$metric->code = 'empty_metric';
$metric->scope = 'system';
$metric->dateType = 'month';

$records = array();

r($metricZenTest->completeMissingRecordsZenTest($records, $metric)) && p('0:value') && e('0');

// 测试步骤5：execution范围记录补全 - 测试返回数据结构
$metric = new stdClass();
$metric->id = 5;
$metric->code = 'execution_metric';
$metric->scope = 'execution';
$metric->dateType = 'day';

$records = array();

r($metricZenTest->completeMissingRecordsZenTest($records, $metric)) && p('0:value') && e('0');