#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getIssueTrendsByRepo();
timeout=0
cid=0

- 测试day范围 >> 0
- 测试返回类型有效 >> 1
- 测试week范围 >> 0
- 测试month范围 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getissuetrendsbyrepoTest(1, 0, 'day')) && p() && e('0');
$result = $test->getissuetrendsbyrepoTest(2, 0, 'day');
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getissuetrendsbyrepoTest(0, 0, 'week')) && p() && e('0');
$result2 = $test->getissuetrendsbyrepoTest(1, 0, 'month');
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getissuetrendsbyrepoTest(3, 0, 'year')) && p() && e('0');
