#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->computeDaysDelta();
cid=1

- 测试计算 2023-12-01 到 2023-12-31 之间的天数 @21
- 测试计算 2023-12-01 到 2024-01-31 之间的天数 @43
- 测试计算 2024-01-01 到 2024-01-31 之间的天数 @22
- 测试计算 2024-01-01 到 2023-12-31 之间的天数 @-1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$begin = array('2023-12-01', '2024-01-01');
$end   = array('2023-12-31', '2024-01-31');

$upgrade = new upgradeTest();
r($upgrade->computeDaysDeltaTest($begin[0], $end[0])) && p() && e('21'); // 测试计算 2023-12-01 到 2023-12-31 之间的天数
r($upgrade->computeDaysDeltaTest($begin[0], $end[1])) && p() && e('43'); // 测试计算 2023-12-01 到 2024-01-31 之间的天数
r($upgrade->computeDaysDeltaTest($begin[1], $end[1])) && p() && e('22'); // 测试计算 2024-01-01 到 2024-01-31 之间的天数
r($upgrade->computeDaysDeltaTest($begin[1], $end[0])) && p() && e('-1'); // 测试计算 2024-01-01 到 2023-12-31 之间的天数
