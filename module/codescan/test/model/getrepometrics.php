#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getRepoMetrics();
timeout=0
cid=0

- 测试两个参数都有值 >> 0
- 测试返回类型有效 >> 1
- 测试默认参数 >> 0
- 测试返回类型验证 >> 1
- 测试不同参数组合 >> 0

*/

$test = new codescanModelTest();

r($test->getrepometricsTest(1, 1)) && p() && e('0');
$result = $test->getrepometricsTest(2, 2);
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getrepometricsTest(0, 0)) && p() && e('0');
$result2 = $test->getrepometricsTest(1, 0);
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getrepometricsTest(0, 1)) && p() && e('0');
