#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRule();
timeout=0
cid=0

- 测试ID为1的调用 >> 0
- 测试返回类型有效 >> 1
- 测试ID为0的调用 >> 0
- 测试返回类型验证 >> 1
- 测试ID为2的调用 >> 0

*/

$test = new codescanModelTest();

r($test->getscanruleTest(1)) && p() && e('0');
$result = $test->getscanruleTest(2);
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getscanruleTest(0)) && p() && e('0');
$result2 = $test->getscanruleTest(3);
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getscanruleTest(4)) && p() && e('0');
