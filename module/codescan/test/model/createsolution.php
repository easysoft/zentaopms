#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->createSolution();
timeout=0
cid=0

- 创建启用且不绑定规则集的方案 @0
- 创建启用且绑定一个规则集的方案 @0
- 缺少 rulesets 字段的方案 @0
- 空对象创建方案 @0
- 创建禁用且带描述的方案 @0

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

$data1 = (object)array('name' => 'codescan-unit-solution-a', 'rulesets' => array(), 'status' => 'enabled', 'desc' => '', 'createdBy' => 'admin');
$data2 = (object)array('name' => 'codescan-unit-solution-b', 'rulesets' => array(1), 'status' => 'enabled', 'desc' => 'link one ruleset', 'createdBy' => 'admin');
$data3 = (object)array('name' => 'codescan-unit-solution-c', 'status' => 'enabled', 'desc' => '');
$data4 = new stdclass();
$data5 = (object)array('name' => 'codescan-unit-solution-d', 'rulesets' => array(1, 2), 'status' => 'disabled', 'desc' => 'disabled solution', 'createdBy' => 'admin');

r($test->createSolutionTest($data1)) && p() && e('0');
r($test->createSolutionTest($data2)) && p() && e('0');
r($test->createSolutionTest($data3)) && p() && e('0');
r($test->createSolutionTest($data4)) && p() && e('0');
r($test->createSolutionTest($data5)) && p() && e('0');
