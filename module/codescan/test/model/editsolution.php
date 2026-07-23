#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->editSolution();
timeout=0
cid=0

- 测试带rulesets的对象 >> 0
- 测试返回类型有效 >> 1
- 测试空对象 >> 0
- 测试返回类型验证 >> 1
- 测试不同参数 >> 0

*/

$test = new codescanModelTest();

$data1 = new stdclass(); $data1->rulesets = '1,2';
r($test->editsolutionTest(1, $data1)) && p() && e('0');
$result = $test->editsolutionTest(2, new stdclass());
r(is_array($result) || is_bool($result) ? '1' : '0') && p() && e('1');
r($test->editsolutionTest(0)) && p() && e('0');
$data2 = new stdclass(); $data2->rulesets = '3,4';
$result2 = $test->editsolutionTest(3, $data2);
r(is_array($result2) || is_bool($result2) ? '1' : '0') && p() && e('1');
r($test->editsolutionTest(4, new stdclass())) && p() && e('0');
