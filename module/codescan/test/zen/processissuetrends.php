#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processIssueTrends();
timeout=0
cid=0

- 测试空metrics返回数组 >> 1
- 测试month范围返回数组 >> 1
- 测试issue_added指标返回数组 >> 1
- 测试issue_fixed指标返回数组 >> 1
- 测试month范围与指标返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->processIssueTrendsTest(array(), 'day'))) && p() && e('1');
r(is_array($test->processIssueTrendsTest(array(), 'month'))) && p() && e('1');
$metric = array((object)array('metric' => (object)array('name' => 'issue_added'), 'values' => array(array(0, 5))));
r(is_array($test->processIssueTrendsTest($metric, 'day'))) && p() && e('1');
$metric2 = array((object)array('metric' => (object)array('name' => 'issue_fixed'), 'values' => array(array(0, 3))));
r(is_array($test->processIssueTrendsTest($metric2, 'day'))) && p() && e('1');
r(is_array($test->processIssueTrendsTest($metric, 'month'))) && p() && e('1');
