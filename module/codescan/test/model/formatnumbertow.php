#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->formatNumberToW();
timeout=0
cid=0

- 测试小于10000 >> 9999
- 测试正好10000 >> 1W
- 测试15000 >> 1W+
- 测试0 >> 0
- 测试50000 >> 5W

*/

su('admin');
$test = new codescanModelTest();

r($test->formatNumberToWTest(9999)) && p() && e('9999');
r($test->formatNumberToWTest(10000)) && p() && e('1W');
r($test->formatNumberToWTest(15000)) && p() && e('1W+');
r($test->formatNumberToWTest(0)) && p() && e('0');
r($test->formatNumberToWTest(50000)) && p() && e('5W');
