#!/usr/bin/env php
<?php

/**

title=测试 metricZen::formatException();
timeout=0
cid=17187

- 执行metricTest模块的formatExceptionZenTest方法，参数是$exception1), 'Error: Test exception message in') !== false  @1
- 执行metricTest模块的formatExceptionZenTest方法，参数是$exception2), 'Message with special chars: <>&"\'') !== false  @1
- 执行metricTest模块的formatExceptionZenTest方法，参数是$exception3), 'Error:  in') !== false  @1
- 执行metricTest模块的formatExceptionZenTest方法，参数是$exception4), 'Database: connection failed') !== false  @1
- 执行metricTest模块的formatExceptionZenTest方法，参数是$exception5), 'This is a very long exception message') !== false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricTest = new metricZenTest();

$exception1 = new Exception('Test exception message', 0);
$exception2 = new Exception('Message with special chars: <>&"\'', 0);
$exception3 = new Exception('', 0);
$exception4 = new Exception('Database: connection failed', 0);
$exception5 = new Exception('This is a very long exception message that contains multiple words and should be properly formatted by the formatException method without any issues', 0);

r(strpos($metricTest->formatExceptionZenTest($exception1), 'Error: Test exception message in') !== false) && p() && e('1');
r(strpos($metricTest->formatExceptionZenTest($exception2), 'Message with special chars: <>&"\'') !== false) && p() && e('1');
r(strpos($metricTest->formatExceptionZenTest($exception3), 'Error:  in') !== false) && p() && e('1');
r(strpos($metricTest->formatExceptionZenTest($exception4), 'Database: connection failed') !== false) && p() && e('1');
r(strpos($metricTest->formatExceptionZenTest($exception5), 'This is a very long exception message') !== false) && p() && e('1');