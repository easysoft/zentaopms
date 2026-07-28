#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->createSolution();
timeout=0
cid=0

- 创建绑定一个规则集的启用方案 @1
- 创建绑定两个规则集的禁用方案 @2
- 空规则集创建方案失败 @0
- 空对象创建方案失败 @0
- 绑定不存在规则集的方案失败 @0

*/

su('admin');
$test = new codescanModelTest();

$solutionA = (object)array('name' => 'codescan-unit-solution-b', 'rulesets' => array(1),    'status' => 'enabled',  'desc' => 'link one ruleset', 'createdBy' => 'admin');
$solutionB = (object)array('name' => 'codescan-unit-solution-d', 'rulesets' => array(1, 2), 'status' => 'disabled', 'desc' => 'disabled solution', 'createdBy' => 'admin');
$solutionC = (object)array('name' => 'codescan-unit-solution-a', 'rulesets' => array(),     'status' => 'enabled',  'desc' => '', 'createdBy' => 'admin');
$solutionD = new stdclass();
$solutionE = (object)array('name' => 'codescan-unit-solution-e', 'rulesets' => array(99),   'status' => 'enabled',  'desc' => 'missing ruleset', 'createdBy' => 'admin');

r($test->createSolutionTest($solutionA)) && p() && e('1');
r($test->createSolutionTest($solutionB)) && p() && e('2');
r($test->createSolutionTest($solutionC)) && p() && e('0');
r($test->createSolutionTest($solutionD)) && p() && e('0');
r($test->createSolutionTest($solutionE)) && p() && e('0');
