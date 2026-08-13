#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->formatDuration();
timeout=0
cid=0

- 测试超过1小时 >> 1h0m
- 测试不到1小时有分钟有秒 >> 5m30s
- 测试仅有秒 >> 45s
- 测试0秒 >> 0s
- 测试正好1小时 >> 1h0m

*/

su('admin');
$test = new codescanModelTest();

r($test->formatDurationTest(3600)) && p() && e('1h0m');
r($test->formatDurationTest(330)) && p() && e('5m30s');
r($test->formatDurationTest(45)) && p() && e('45s');
r($test->formatDurationTest(0)) && p() && e('0s');
r($test->formatDurationTest(3660)) && p() && e('1h1m');
