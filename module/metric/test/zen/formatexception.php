#!/usr/bin/env php
<?php

/**

title=测试 metricZen::formatException();
timeout=0
cid=0

- 步骤1：正常Exception异常 @Error: Database connection failed in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 29
- 步骤2：RuntimeException异常 @Error: Runtime error occurred in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 30
- 步骤3：InvalidArgumentException异常 @Error: Invalid parameter provided in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 31
- 步骤4：自定义异常消息 @Error: Custom error message for testing in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 32
- 步骤5：空消息异常 @Error:  in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 33

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$metricZenTest = new metricZenTest();

// 4. 测试步骤：必须包含至少5个测试步骤
$exception1 = new Exception('Database connection failed');
$exception2 = new RuntimeException('Runtime error occurred');
$exception3 = new InvalidArgumentException('Invalid parameter provided');
$exception4 = new Exception('Custom error message for testing');
$exception5 = new Exception('');

r($metricZenTest->formatExceptionZenTest($exception1)) && p() && e('Error: Database connection failed in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 29'); // 步骤1：正常Exception异常
r($metricZenTest->formatExceptionZenTest($exception2)) && p() && e('Error: Runtime error occurred in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 30'); // 步骤2：RuntimeException异常
r($metricZenTest->formatExceptionZenTest($exception3)) && p() && e('Error: Invalid parameter provided in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 31'); // 步骤3：InvalidArgumentException异常
r($metricZenTest->formatExceptionZenTest($exception4)) && p() && e('Error: Custom error message for testing in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 32'); // 步骤4：自定义异常消息
r($metricZenTest->formatExceptionZenTest($exception5)) && p() && e('Error:  in /repo/zentaopms/module/metric/test/zen/formatexception.php on line 33'); // 步骤5：空消息异常