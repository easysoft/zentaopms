#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->refreshOverview();
timeout=0
cid=0

- 测试正常调用返回true >> 1
- 测试返回类型有效 >> 1
- 测试再次调用返回true >> 1
- 测试返回类型验证 >> 1
- 测试多次调用一致性 >> 1

*/

$test = new codescanModelTest();

r($test->refreshOverviewTest()) && p() && e('1');
$result = $test->refreshOverviewTest();
r(is_bool($result) ? '1' : '0') && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');
$result2 = $test->refreshOverviewTest();
r(is_bool($result2) ? '1' : '0') && p() && e('1');
r($test->refreshOverviewTest()) && p() && e('1');
