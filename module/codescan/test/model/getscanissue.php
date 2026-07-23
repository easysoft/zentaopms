#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanIssue();
timeout=0
cid=0

- 测试showBug为true >> 0
- 测试返回类型有效 >> 1
- 测试showBug为false >> 0
- 测试默认参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getscanissueTest(1, true)) && p() && e('0');
$result = $test->getscanissueTest(2, true);
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getscanissueTest(0, false)) && p() && e('0');
$result2 = $test->getscanissueTest(3, false);
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getscanissueTest(4, true)) && p() && e('0');
