#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->editPlan();
timeout=0
cid=0

- 编辑单方案主分支计划 @0
- 编辑双方案双分支计划 @0
- 空 solutionIDs 的计划 @0
- 编辑空分支计划 @0
- 编辑三方案计划 @0

*/

su('admin');
$test = new codescanModelTest();

$planA = (object)array('name' => 'codescan-plan-a', 'solutionIDs' => array(5),      'branches' => (object)array('include' => array('main')));
$planB = (object)array('name' => 'codescan-plan-b', 'solutionIDs' => array(5, 10),  'branches' => (object)array('include' => array('main', 'develop')));
$planC = (object)array('name' => '', 'solutionIDs' => array(), 'branches' => (object)array('include' => array()));
$planD = (object)array('name' => 'codescan-plan-c', 'solutionIDs' => array(),       'branches' => (object)array('include' => array()));
$planE = (object)array('name' => 'codescan-plan-d', 'solutionIDs' => array(5, 10, 15), 'branches' => (object)array('include' => array('release')));

r($test->editPlanTest(1, 1, $planA)) && p() && e('0');
r($test->editPlanTest(1, 2, $planB)) && p() && e('0');
r($test->editPlanTest(2, 3, $planC)) && p() && e('0');
r($test->editPlanTest(2, 4, $planD)) && p() && e('0');
r($test->editPlanTest(3, 5, $planE)) && p() && e('0');
