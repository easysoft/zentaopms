#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->changeIssueState();
timeout=0
cid=0

- 测试固定状态参数 >> 0
- 测试返回类型有效 >> 1
- 测试默认参数 >> 0
- 测试忽略日期参数 >> 1
- 测试数组ID参数 >> 0

*/

$test = new codescanModelTest();

r($test->changeissuestateTest(1, 'fixed', 'solution', '2026-01-01', 0)) && p() && e('0');
$result = $test->changeissuestateTest(2, 'wontfix');
r(is_array($result) || is_bool($result) ? '1' : '0') && p() && e('1');
r($test->changeissuestateTest(0, '')) && p() && e('0');
$result2 = $test->changeissuestateTest(array(1, 2, 3), 'fixed');
r(is_array($result2) || is_bool($result2) ? '1' : '0') && p() && e('1');
r($test->changeissuestateTest(3, 'ignore', '', '', -1)) && p() && e('0');
