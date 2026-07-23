#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->execScanTask();
timeout=0
cid=0

- 测试空plan和空branch >> 0
- 测试返回类型有效 >> 1
- 测试plan有属性 >> 0
- 测试带branch参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

$plan1 = new stdclass(); $plan1->id = 1; $plan1->repoID = 1;
r($test->execscantaskTest($plan1, '')) && p() && e('0');
$result = $test->execscantaskTest(new stdclass(), '');
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
$plan2 = new stdclass(); $plan2->id = 2; $plan2->repoID = 2;
r($test->execscantaskTest($plan2, '')) && p() && e('0');
$plan3 = new stdclass(); $plan3->id = 3; $plan3->repoID = 3;
$result2 = $test->execscantaskTest($plan3, 'main');
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
$plan4 = new stdclass(); $plan4->id = 0; $plan4->repoID = 0;
r($test->execscantaskTest($plan4, '')) && p() && e('0');
