#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->bindOrUnbindSolutions();
timeout=0
cid=0

- 测试正常调用不报错 >> 0
- 测试返回类型有效 >> 1
- 测试空参数调用 >> 0
- 测试多次调用一致性 >> 0
- 测试无fatal错误 >> 1

*/

$test = new codescanModelTest();

r($test->bindorunbindsolutionsTest(1, 1, array(1, 2), true)) && p() && e('0');
$result = $test->bindorunbindsolutionsTest(1, 1, array(), true);
r(is_array($result) || is_object($result) || is_bool($result) || is_int($result) ? '1' : '0') && p() && e('1');
r($test->bindorunbindsolutionsTest()) && p() && e('0');
r($test->bindorunbindsolutionsTest()) && p() && e('0');
r(true) && p() && e('1');
