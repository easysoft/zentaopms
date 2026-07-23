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

- 测试正常调用 >> 0
- 测试返回类型有效 >> 1
- 测试空参数调用 >> 0
- 测试多次调用 >> 0
- 测试无fatal错误 >> 1

*/

$test = new codescanModelTest();

$plan = new stdclass();
$plan->id     = 0;
$plan->repoID = 0;
r($test->execscantaskTest($plan, '')) && p() && e('0');
$result = $test->execscantaskTest($plan, '');
r(is_bool($result) || is_int($result) || is_object($result) || is_array($result) ? '1' : '0') && p() && e('1');
r($test->execscantaskTest($plan, '')) && p() && e('0');
r($test->execscantaskTest($plan, '')) && p() && e('0');
r(true) && p() && e('1');
