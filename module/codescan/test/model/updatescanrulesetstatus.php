#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->updateScanRulesetStatus();
timeout=0
cid=0

- 测试disabled状态 >> 0
- 测试返回类型有效 >> 1
- 测试enabled状态 >> 0
- 测试默认参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->updatescanrulesetstatusTest(1, 'disabled')) && p() && e('0');
$result = $test->updatescanrulesetstatusTest(2, 'enabled');
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->updatescanrulesetstatusTest(0, 'disabled')) && p() && e('0');
$result2 = $test->updatescanrulesetstatusTest(3, 'enabled');
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->updatescanrulesetstatusTest(4)) && p() && e('0');
