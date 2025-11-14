#!/usr/bin/env php
<?php

/**

title=测试 metricZen::buildMetricForEdit();
timeout=0
cid=17181

- 执行metricZenTest模块的buildMetricForEditZenTest方法  @1
- 执行metricZenTest模块的buildMetricForEditZenTest方法 属性editedBy @admin
- 执行buildMetricForEditZenTest()模块的editedDate) > 15方法  @1
- 执行metricZenTest模块的buildMetricForEditZenTest方法 属性name @Updated Metric
- 执行metricZenTest模块的buildMetricForEditZenTest方法 属性code @updated_metric_001
- 执行metricZenTest模块的buildMetricForEditZenTest方法  @1
- 执行metricZenTest模块的buildMetricForEditZenTest方法 属性editedBy @admin
- 执行buildMetricForEditZenTest()模块的editedDate) > 15方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

zenData('user');

su('admin');

$metricZenTest = new metricZenTest();

$_POST = array(
    'name' => 'Updated Metric',
    'code' => 'updated_metric_001',
    'purpose' => 'rate',
    'scope' => 'product',
    'object' => 'story',
    'dateType' => 'week',
    'alias' => 'Updated Alias',
    'desc' => 'Updated Description',
    'definition' => 'Updated Definition'
);

r(is_object($metricZenTest->buildMetricForEditZenTest())) && p() && e('1');
r($metricZenTest->buildMetricForEditZenTest()) && p('editedBy') && e('admin');
r(strlen($metricZenTest->buildMetricForEditZenTest()->editedDate) > 15) && p() && e('1');
r($metricZenTest->buildMetricForEditZenTest()) && p('name') && e('Updated Metric');
r($metricZenTest->buildMetricForEditZenTest()) && p('code') && e('updated_metric_001');

$_POST = array(
    'name' => 'Basic Metric',
    'code' => 'basic_metric_001',
    'purpose' => 'scale',
    'scope' => 'system',
    'object' => 'task',
    'dateType' => 'day',
    'alias' => '',
    'desc' => '',
    'definition' => ''
);

r(is_object($metricZenTest->buildMetricForEditZenTest())) && p() && e('1');
r($metricZenTest->buildMetricForEditZenTest()) && p('editedBy') && e('admin');
r(strlen($metricZenTest->buildMetricForEditZenTest()->editedDate) > 15) && p() && e('1');