#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getUniqueKeyByRecord();
timeout=0
cid=0

- 执行metricTest模块的getUniqueKeyByRecordZenTest方法  @product1_year2024_month01
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法  @project2_year2024
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法  @execution3_year2024
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法  @product1_project2_execution3_year2024_month12_day31
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法  @product5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

$record1 = new stdClass();
$record1->product = 1;
$record1->year = '2024';
$record1->month = '01';
$record1->value = 100;
$record1->metricID = 1;
$record1->metricCode = 'test_code';
$record1->date = '2024-01-01';

r($metricTest->getUniqueKeyByRecordZenTest((array)$record1)) && p() && e('product1_year2024_month01');

$record2 = new stdClass();
$record2->id = 123;
$record2->project = 2;
$record2->year = '2024';
$record2->value = 200;
$record2->metricID = 2;
$record2->metricCode = 'test_code2';
$record2->calcType = 'cron';

r($metricTest->getUniqueKeyByRecordZenTest((array)$record2, 'system')) && p() && e('project2_year2024');

$record3 = new stdClass();
$record3->execution = 3;
$record3->year = '2024';
$record3->month = '';
$record3->day = null;
$record3->value = 300;
$record3->metricID = 3;

r($metricTest->getUniqueKeyByRecordZenTest((array)$record3)) && p() && e('execution3_year2024');

$record4 = new stdClass();
$record4->product = 1;
$record4->project = 2;
$record4->execution = 3;
$record4->year = '2024';
$record4->month = '12';
$record4->day = '31';
$record4->value = 400;

r($metricTest->getUniqueKeyByRecordZenTest((array)$record4)) && p() && e('product1_project2_execution3_year2024_month12_day31');

$record5 = new stdClass();
$record5->product = 5;
$record5->value = 500;
$record5->metricID = 5;
$record5->metricCode = 'ignore_test';
$record5->calcType = 'manual';
$record5->calculatedBy = 'admin';
$record5->date = '2024-01-01';

r($metricTest->getUniqueKeyByRecordZenTest((array)$record5)) && p() && e('product5');