#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processConditions();
timeout=0
cid=0

- 测试多条件plan返回数组 >> 1
- 测试空plan返回数组 >> 1
- 测试不同条件plan返回数组 >> 1
- 测试空对象返回数组 >> 1
- 测试plan再次调用返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

$plan = new stdclass();
$plan->severity = array('high', 'medium');
$plan->type = array('bug', 'vuln');
$plan->metric = array('count', 'percent');
$plan->threshold = array('10', '80');
$planEmpty = (object)array('severity' => array(), 'type' => array(), 'metric' => array(), 'threshold' => array());
r(is_array($test->processConditionsTest($plan))) && p() && e('1');
r(is_array($test->processConditionsTest($planEmpty))) && p() && e('1');
$plan2 = new stdclass();
$plan2->severity = array('low');
$plan2->type = array('bug');
$plan2->metric = array('count');
$plan2->threshold = array('5');
r(is_array($test->processConditionsTest($plan2))) && p() && e('1');
r(is_array($test->processConditionsTest($planEmpty))) && p() && e('1');
r(is_array($test->processConditionsTest($plan))) && p() && e('1');
