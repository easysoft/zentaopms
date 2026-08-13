#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processPlanData();
timeout=0
cid=0

- step1 >> 1
- step2 >> 1
- step3 >> 1
- step4 >> 1
- step5 >> 1

*/

su('admin');
$test = new codescanZenTest();

$plan = new stdclass();
$plan->name = 'test';
$plan->repo = 1;
$plan->scope = 'full';
$plan->solutions = '1,2';
$plan->severity = array('high');
$plan->type = array('bug');
$plan->metric = array('count');
$plan->threshold = array('10');
$plan->branch = '';
$plan->branchReg = '';
$plan->excludePath = '';
$plan->excludeFile = '';
$emptyPlan = (object)array(
    'solutions' => '',
    'name' => '',
    'repo' => 0,
    'scope' => '',
    'severity' => array(),
    'type' => array(),
    'metric' => array(),
    'threshold' => array(),
    'branch' => '',
    'branchReg' => '',
    'excludePath' => '',
    'excludeFile' => ''
);

r(is_object($test->processPlanDataTest($plan))) && p() && e('1');
r(is_object($test->processPlanDataTest($emptyPlan))) && p() && e('1');
r(is_object($test->processPlanDataTest($plan))) && p() && e('1');
r(is_object($test->processPlanDataTest($emptyPlan))) && p() && e('1');
r(is_object($test->processPlanDataTest($plan))) && p() && e('1');
