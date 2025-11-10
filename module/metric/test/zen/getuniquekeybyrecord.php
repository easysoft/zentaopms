#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getUniqueKeyByRecord();
timeout=0
cid=0

- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record1, ''  @product1_year2025_month1
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record2, ''  @product2_year2025
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record3, 'system'  @year2025_month1
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record4, ''  @execution3_year2025_month1
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record5, ''  @project5_product10_year2025_month1_week1
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record6, ''  @0
- 执行metricTest模块的getUniqueKeyByRecordZenTest方法，参数是$record7, ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricTest = new metricZenTest();

$record1 = new stdclass();
$record1->product = 1;
$record1->year = 2025;
$record1->month = 1;
$record1->value = 100;
$record1->metricID = 1;
$record1->metricCode = 'test_metric';
$record1->calcType = 'cron';
$record1->calculatedBy = 'system';
$record1->date = '2025-01-10';

$record2 = new stdclass();
$record2->product = 2;
$record2->year = 2025;
$record2->month = '';
$record2->value = 200;
$record2->metricID = 2;
$record2->metricCode = 'test_metric2';

$record3 = new stdclass();
$record3->id = 1;
$record3->year = 2025;
$record3->month = 1;
$record3->value = 300;
$record3->metricID = 3;
$record3->metricCode = 'test_metric3';
$record3->calcType = 'cron';

$record4 = new stdclass();
$record4->execution = 3;
$record4->year = 2025;
$record4->month = 1;
$record4->value = 400;

$record5 = new stdclass();
$record5->project = 5;
$record5->product = 10;
$record5->year = 2025;
$record5->month = 1;
$record5->week = 1;
$record5->value = 500;

$record6 = new stdclass();
$record6->value = 600;
$record6->metricID = 6;
$record6->metricCode = 'test_metric6';
$record6->calcType = 'cron';
$record6->calculatedBy = 'system';
$record6->date = '2025-01-10';

$record7 = new stdclass();
$record7->product = '';
$record7->year = '';
$record7->month = '';

r($metricTest->getUniqueKeyByRecordZenTest($record1, '')) && p() && e('product1_year2025_month1');
r($metricTest->getUniqueKeyByRecordZenTest($record2, '')) && p() && e('product2_year2025');
r($metricTest->getUniqueKeyByRecordZenTest($record3, 'system')) && p() && e('year2025_month1');
r($metricTest->getUniqueKeyByRecordZenTest($record4, '')) && p() && e('execution3_year2025_month1');
r($metricTest->getUniqueKeyByRecordZenTest($record5, '')) && p() && e('project5_product10_year2025_month1_week1');
r($metricTest->getUniqueKeyByRecordZenTest($record6, '')) && p() && e('0');
r($metricTest->getUniqueKeyByRecordZenTest($record7, '')) && p() && e('0');