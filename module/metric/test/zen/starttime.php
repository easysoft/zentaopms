#!/usr/bin/env php
<?php

/**

title=测试 metricZen::startTime();
timeout=0
cid=0

- 步骤1：正常调用，验证返回值类型为浮点数 @double
- 步骤2：多次调用验证递增 @1
- 步骤3：验证微秒级精度 @1
- 步骤4：验证时间范围 @1
- 步骤5：测试时间差 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(gettype($metricTest->startTimeTest())) && p() && e('double'); // 步骤1：正常调用，验证返回值类型为浮点数
$time1 = $metricTest->startTimeTest();
usleep(100);
$time2 = $metricTest->startTimeTest();
r($time2 > $time1 ? '1' : '0') && p() && e('1'); // 步骤2：多次调用验证递增
r(is_float($metricTest->startTimeTest()) && strpos((string)$metricTest->startTimeTest(), '.') !== false) && p() && e('1'); // 步骤3：验证微秒级精度
r(abs($metricTest->startTimeTest() - microtime(true)) < 1) && p() && e('1'); // 步骤4：验证时间范围
$startTime = $metricTest->startTimeTest();
usleep(1000);
$endTime = $metricTest->startTimeTest();
r(($endTime - $startTime > 0) ? '1' : '0') && p() && e('1'); // 步骤5：测试时间差