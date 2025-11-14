#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printDuration();
timeout=0
cid=15693

- 测试标准秒数转换 @1天1小时1分1秒
- 测试0秒 @0
- 测试3661秒 @1小时1分1秒
- 测试一年的秒数 @1年
- 测试一天的秒数 @1天
- 测试小时数 @1小时
- 测试分钟数 @1分

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->printDurationTest(90061, 'y-m-d-h-i-s')) && p() && e('1天1小时1分1秒'); // 测试标准秒数转换
r($commonTest->printDurationTest(0, 'y-m-d-h-i-s')) && p() && e('0'); // 测试0秒
r($commonTest->printDurationTest(3661, 'y-m-d-h-i-s')) && p() && e('1小时1分1秒'); // 测试3661秒
r($commonTest->printDurationTest(31536000, 'y-m-d-h-i-s')) && p() && e('1年'); // 测试一年的秒数
r($commonTest->printDurationTest(86400, 'y-m-d-h-i-s')) && p() && e('1天'); // 测试一天的秒数
r($commonTest->printDurationTest(3600, 'y-m-d-h-i-s')) && p() && e('1小时'); // 测试小时数
r($commonTest->printDurationTest(60, 'y-m-d-h-i-s')) && p() && e('1分'); // 测试分钟数