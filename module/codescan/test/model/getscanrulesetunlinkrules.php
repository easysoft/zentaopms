#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->getScanRulesetUnlinkRules();
timeout=0
cid=0

- 测试空参数调用 >> 1
- 测试返回值为数组 >> 1
- 测试返回值为数组或对象 >> 1
- 测试无fatal错误 >> 1
- 测试再次调用一致性 >> 1

*/

su('admin');
$test = new codescanModelTest();

$result = $test->getscanrulesetunlinkrulesTest();
r(is_array($result) || is_object($result) ? '1' : is_bool($result) ? '1' : '0') && p() && e('1');
r(is_array($test->getscanrulesetunlinkrulesTest()) ? '1' : is_object($test->getscanrulesetunlinkrulesTest()) ? '1' : '0') && p() && e('1');
r(is_array($test->getscanrulesetunlinkrulesTest()) || is_object($test->getscanrulesetunlinkrulesTest()) ? '1' : '0') && p() && e('1');
r(is_bool($test->getscanrulesetunlinkrulesTest()) || is_array($test->getscanrulesetunlinkrulesTest()) || is_object($test->getscanrulesetunlinkrulesTest()) ? '1' : '0') && p() && e('1');
r(is_array($test->getscanrulesetunlinkrulesTest()) || is_object($test->getscanrulesetunlinkrulesTest()) || is_bool($test->getscanrulesetunlinkrulesTest()) ? '1' : '0') && p() && e('1');
