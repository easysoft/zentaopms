#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchMetricRecords();
timeout=0
cid=17166

- 执行metricTest模块的fetchMetricRecordsTest方法，参数是'storyScale', array  @0
- 执行metricTest模块的fetchMetricRecordsTest方法，参数是'taskProgress', array  @0
- 执行metricTest模块的fetchMetricRecordsTest方法，参数是'invalidCode', array  @0
- 执行metricTest模块的fetchMetricRecordsTest方法，参数是'bugDensity', array  @0
- 执行metricTest模块的fetchMetricRecordsTest方法，参数是'codeQuality', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

zenData('metric')->loadYaml('metric_fetchmetricrecords', false, 2)->gen(10);
zenData('metriclib')->loadYaml('metriclib_fetchmetricrecords', false, 2)->gen(50);

su('admin');

$metricTest = new metricTest();

r($metricTest->fetchMetricRecordsTest('storyScale', array('project', 'product'), array())) && p() && e('0');
r($metricTest->fetchMetricRecordsTest('taskProgress', array(), array())) && p() && e('0');
r($metricTest->fetchMetricRecordsTest('invalidCode', array('project'), array())) && p() && e('0');
r($metricTest->fetchMetricRecordsTest('bugDensity', array('execution'), array('scope' => array(1, 2)))) && p() && e('0');
r($metricTest->fetchMetricRecordsTest('codeQuality', array('system'), array())) && p() && e('0');