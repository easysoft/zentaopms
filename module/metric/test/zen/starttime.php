#!/usr/bin/env php
<?php

/**

title=测试 metricZen::startTime();
timeout=0
cid=17208

- 步骤1:验证返回值为浮点数类型 @double
- 步骤2:验证返回值大于零 @1
- 步骤3:验证连续调用返回递增的时间戳 @1
- 步骤4:验证返回值格式为microtime时间戳 @1
- 步骤5:验证返回值精度足够高(包含微秒) @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$metricZenTest = new metricZenTest();

// 4. 强制要求:必须包含至少5个测试步骤
r(gettype($metricZenTest->startTimeZenTest())) && p() && e('double'); // 步骤1:验证返回值为浮点数类型
$time1 = $metricZenTest->startTimeZenTest();
r($time1 > 0 ? '1' : '0') && p() && e('1'); // 步骤2:验证返回值大于零
usleep(100);
$time2 = $metricZenTest->startTimeZenTest();
r($time2 > $time1 ? '1' : '0') && p() && e('1'); // 步骤3:验证连续调用返回递增的时间戳
$currentTime = $metricZenTest->startTimeZenTest();
r(($currentTime > 1000000000 && $currentTime < 2000000000) ? '1' : '0') && p() && e('1'); // 步骤4:验证返回值格式为microtime时间戳
$timeValue = $metricZenTest->startTimeZenTest();
r((strpos(strval($timeValue), '.') !== false) ? '1' : '0') && p() && e('1'); // 步骤5:验证返回值精度足够高(包含微秒)