#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->editSolution();
timeout=0
cid=0

- 编辑双规则集方案 @1,codescan-edit-solution-a,1,2,1,none
- 编辑空规则集方案 @2,codescan-edit-solution-b,empty,0,1,none
- 编辑单规则集方案 @3,codescan-edit-solution-c,3,1,1,none
- 编辑三规则集方案 @4,codescan-edit-solution-d,5,10,15,3,1,none
- 编辑零号方案 @0,codescan-edit-solution-e,2,4,2,1,none

*/

su('admin');
$test = new codescanModelTest();

$data1 = (object)array('name' => 'codescan-edit-solution-a', 'rulesets' => '1,2',    'desc' => 'two rulesets');
$data2 = (object)array('name' => 'codescan-edit-solution-b', 'rulesets' => '',       'desc' => 'empty rulesets');
$data3 = (object)array('name' => 'codescan-edit-solution-c', 'rulesets' => '3',      'desc' => 'single ruleset');
$data4 = (object)array('name' => 'codescan-edit-solution-d', 'rulesets' => '5,10,15','desc' => 'three rulesets');
$data5 = (object)array('name' => 'codescan-edit-solution-e', 'rulesets' => '2,4',    'desc' => 'zero solution id');

r($test->editSolutionTest(1, $data1)) && p() && e('0');
r($test->editSolutionTest(2, $data2)) && p() && e('0');
r($test->editSolutionTest(3, $data3)) && p() && e('0');
r($test->editSolutionTest(4, $data4)) && p() && e('0');
r($test->editSolutionTest(0, $data5)) && p() && e('0');
