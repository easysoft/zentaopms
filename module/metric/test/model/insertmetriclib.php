#!/usr/bin/env php
<?php

/**

title=测试 metricModel::insertMetricLib();
timeout=0
cid=17136

- 执行metricTest模块的insertMetricLibTest方法，参数是$recordData1, 'cron'  @0
- 执行metricTest模块的insertMetricLibTest方法，参数是$recordData2, 'cron'  @0
- 执行metricTest模块的insertMetricLibTest方法，参数是$recordData1, 'inference'  @0
- 执行metricTest模块的insertMetricLibTest方法，参数是$recordData3, 'cron'  @0
- 执行metricTest模块的insertMetricLibTest方法，参数是$recordData4, 'cron'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

$metric = zenData('metric');
$metric->id->range('1-5');
$metric->code->range('test_metric1,test_metric2,test_metric3,test_metric4,test_metric5');
$metric->name->range('测试度量1,测试度量2,测试度量3,测试度量4,测试度量5');
$metric->stage->range('released,released,released,released,released');
$metric->scope->range('system,system,system,system,system');
$metric->lastCalcRows->range('0,0,0,0,0');
$metric->deleted->range('0{5}');
$metric->gen(5);

$metriclib = zenData('metriclib');
$metriclib->id->range('1-100');
$metriclib->metricCode->range('test_metric1{50},test_metric2{50}');
$metriclib->value->range('1-100');
$metriclib->calcType->range('cron{100}');
$metriclib->gen(0);

su('admin');

$metricTest = new metricTest();

// 准备测试数据
$singleRecord = new stdClass();
$singleRecord->metricCode = 'test_metric1';
$singleRecord->value = '100';
$singleRecord->date = '2024-01-01 00:00:00';

$recordData1 = array('test_metric1' => array($singleRecord));

// 多个记录数据
$record1 = new stdClass();
$record1->metricCode = 'test_metric2';
$record1->value = '200';
$record1->date = '2024-01-01 00:00:00';

$record2 = new stdClass();
$record2->metricCode = 'test_metric2';
$record2->value = '300';
$record2->date = '2024-01-02 00:00:00';

$recordData2 = array('test_metric2' => array($record1, $record2));

// 包含空记录的数据
$record3 = new stdClass();
$record3->metricCode = 'test_metric3';
$record3->value = '400';
$record3->date = '2024-01-01 00:00:00';

$recordData3 = array('test_metric3' => array($record3, null, ''));

// 空数组数据
$recordData4 = array();

r($metricTest->insertMetricLibTest($recordData1, 'cron')) && p() && e('0');
r($metricTest->insertMetricLibTest($recordData2, 'cron')) && p() && e('0');
r($metricTest->insertMetricLibTest($recordData1, 'inference')) && p() && e('0');
r($metricTest->insertMetricLibTest($recordData3, 'cron')) && p() && e('0');
r($metricTest->insertMetricLibTest($recordData4, 'cron')) && p() && e('0');