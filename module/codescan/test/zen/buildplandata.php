#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 codescanZen->buildPlanData();
timeout=0
cid=0

- step1 >> 1
- step2 >> 1
- step3 >> 1
- step4 >> 1
- step5 >> 1

*/

$test = new codescanZenTest();

$plan = new stdclass();
$plan->solutionIDs = array(5, 10);
$plan->branches = (object)array('include' => array('main'));

r(is_object($test->buildPlanDataTest($plan))) && p() && e('1');
r(is_object($test->buildPlanDataTest(new stdclass()))) && p() && e('1');
r(is_object($test->buildPlanDataTest($plan))) && p() && e('1');
r(is_object($test->buildPlanDataTest($plan))) && p() && e('1');
r(is_object($test->buildPlanDataTest(new stdclass()))) && p() && e('1');
