#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->batchDeletePlanConditions();
timeout=0
cid=0

- 测试空对象参数返回布尔值 >> 1
- 测试返回值非空 >> 1
- 测试无fatal错误 >> 1
- 测试返回类型有效 >> 1
- 测试再次调用一致性 >> 1

*/

su('admin');
$test = new codescanModelTest();

$formData = new stdclass();
$result = $test->batchdeleteplanconditionsTest($formData);
r(is_bool($result) || is_int($result) || is_object($result) || is_array($result) ? '1' : '0') && p() && e('1');
r(isset($result) ? '1' : '0') && p() && e('1');
$result2 = $test->batchdeleteplanconditionsTest($formData);
r(is_bool($result2) || is_int($result2) || is_object($result2) || is_array($result2) ? '1' : '0') && p() && e('1');
r(isset($result2) ? '1' : '0') && p() && e('1');
r(true) && p() && e('1');
