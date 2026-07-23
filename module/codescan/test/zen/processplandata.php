#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processPlanData();
timeout=0
cid=0

- 测试空参数调用返回有效结果 >> 1
- 测试无fatal错误 >> 1
- 测试返回类型有效 >> 1
- 测试第二次调用一致性 >> 1
- 测试第三次调用 >> 1

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

r($test->processplanDataTest($plan)) && p() && e('1');
$plan2 = new stdclass();
r($test->processplanDataTest($plan2)) && p() && e('1');
$plan3 = new stdclass();
$plan3->branchReg = 'main,develop';
$plan3->excludePath = 'vendor,node_modules';
r($test->processplanDataTest($plan3)) && p() && e('1');
r(is_object($test->processplanDataTest($plan))) && p() && e('1');
r($test->processplanDataTest(new stdclass())) && p() && e('1');
