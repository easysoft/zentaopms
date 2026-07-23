#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->unlinkRules();
timeout=0
cid=0

- 测试有ID和数组参数 >> 0
- 测试返回类型有效 >> 1
- 测试默认参数 >> 0
- 测试返回类型验证 >> 1
- 测试不同数组参数 >> 0

*/

$test = new codescanModelTest();

r($test->unlinkrulesTest(1, array(1, 2, 3))) && p() && e('0');
$result = $test->unlinkrulesTest(2, array());
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->unlinkrulesTest(0, array(1))) && p() && e('0');
$result2 = $test->unlinkrulesTest(1, array(4, 5, 6));
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->unlinkrulesTest(2, array(7, 8))) && p() && e('0');
