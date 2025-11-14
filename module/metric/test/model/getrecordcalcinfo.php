#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getRecordCalcInfo();
timeout=0
cid=17121

- 执行metricTest模块的getRecordCalcInfoTest方法，参数是1 
 - 属性calcType @cron
 - 属性calculatedBy @admin
- 执行metricTest模块的getRecordCalcInfoTest方法，参数是999  @0
- 执行metricTest模块的getRecordCalcInfoTest方法  @0
- 执行metricTest模块的getRecordCalcInfoTest方法，参数是-1  @0
- 执行metricTest模块的getRecordCalcInfoTest方法，参数是'abc'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

$table = zenData('metriclib');
$table->id->range('1-10');
$table->metricID->range('1,2,3,4,5,1,2,3,4,5');
$table->metricCode->range('test_metric_1,test_metric_2,test_metric_3,test_metric_4,test_metric_5,test_metric_1,test_metric_2,test_metric_3,test_metric_4,test_metric_5');
$table->calcType->range('cron,cron,cron,cron,cron,inference,inference,inference,inference,inference');
$table->calculatedBy->range('admin,admin,admin,user1,user1,user1,user2,user2,user2,user2');
$table->system->range('1,1,1,1,1,0,0,0,0,0');
$table->gen(10);

su('admin');

$metricTest = new metricTest();

r($metricTest->getRecordCalcInfoTest(1)) && p('calcType,calculatedBy') && e('cron,admin');
r($metricTest->getRecordCalcInfoTest(999)) && p() && e('0');
r($metricTest->getRecordCalcInfoTest(0)) && p() && e('0');
r($metricTest->getRecordCalcInfoTest(-1)) && p() && e('0');
r($metricTest->getRecordCalcInfoTest('abc')) && p() && e('0');