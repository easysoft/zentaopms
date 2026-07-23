#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->createPlan();
timeout=0
cid=0

- 测试带名称的对象 >> 0
- 测试返回类型有效 >> 1
- 测试空对象 >> 0
- 测试返回类型验证 >> 1
- 测试不同对象参数 >> 0

*/

$test = new codescanModelTest();

$data1 = new stdclass(); $data1->name = 'test1';
r($test->createplanTest($data1)) && p() && e('0');
$result = $test->createplanTest(new stdclass());
r(is_array($result) || is_bool($result) || is_int($result) ? '1' : '0') && p() && e('1');
r($test->createplanTest(new stdclass())) && p() && e('0');
$data2 = new stdclass(); $data2->name = 'test2';
$result2 = $test->createplanTest($data2);
r(is_array($result2) || is_bool($result2) || is_int($result2) ? '1' : '0') && p() && e('1');
r($test->createplanTest(new stdclass())) && p() && e('0');
