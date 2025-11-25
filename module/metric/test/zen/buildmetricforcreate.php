#!/usr/bin/env php
<?php

/**

title=测试 metricZen::buildMetricForCreate();
timeout=0
cid=17180

- 执行metricZenTest模块的buildMetricForCreateZenTest方法  @1
- 执行metricZenTest模块的buildMetricForCreateZenTest方法 属性createdBy @admin
- 执行buildMetricForCreateZenTest()模块的createdDate) > 15方法  @1
- 执行metricZenTest模块的buildMetricForCreateZenTest方法 属性name @Test Metric
- 执行metricZenTest模块的buildMetricForCreateZenTest方法 属性code @test_metric_001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

zenData('user');

su('admin');

$metricZenTest = new metricZenTest();

$_POST = array(
    'name' => 'Test Metric',
    'code' => 'test_metric_001',
    'purpose' => 'scale',
    'scope' => 'system',
    'object' => 'task',
    'dateType' => 'day',
    'alias' => 'Test Alias',
    'desc' => 'Test Description',
    'definition' => 'Test Definition'
);

r(is_object($metricZenTest->buildMetricForCreateZenTest())) && p() && e('1');
r($metricZenTest->buildMetricForCreateZenTest()) && p('createdBy') && e('admin');
r(strlen($metricZenTest->buildMetricForCreateZenTest()->createdDate) > 15) && p() && e('1');
r($metricZenTest->buildMetricForCreateZenTest()) && p('name') && e('Test Metric');
r($metricZenTest->buildMetricForCreateZenTest()) && p('code') && e('test_metric_001');