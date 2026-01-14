#!/usr/bin/env php
<?php

/**

title=测试 gitModel::printLog();
timeout=0
cid=16550

- 测试步骤1：正常字符串日志输出 @1
- 测试步骤2：空字符串日志输出 @1
- 测试步骤3：包含特殊字符的日志输出 @1
- 测试步骤4：长字符串日志输出 @1
- 测试步骤5：包含换行符的日志输出 @1
- 测试步骤6：数字字符串日志输出 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitTest = new gitModelTest();

$result1 = $gitTest->printLogTest('test log message');
r(strpos($result1, 'test log message') !== false) && p() && e(1); // 测试步骤1：正常字符串日志输出

$result2 = $gitTest->printLogTest('');
r(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result2)) && p() && e(1); // 测试步骤2：空字符串日志输出

$result3 = $gitTest->printLogTest('log with @#$%^&*()_+ special chars');
r(strpos($result3, 'log with @#$%^&*()_+ special chars') !== false) && p() && e(1); // 测试步骤3：包含特殊字符的日志输出

$result4 = $gitTest->printLogTest(str_repeat('A', 100));
r(strpos($result4, str_repeat('A', 100)) !== false) && p() && e(1); // 测试步骤4：长字符串日志输出

$result5 = $gitTest->printLogTest("line1\nline2");
r(strpos($result5, "line1\nline2") !== false) && p() && e(1); // 测试步骤5：包含换行符的日志输出

$result6 = $gitTest->printLogTest('12345');
r(strpos($result6, '12345') !== false) && p() && e(1); // 测试步骤6：数字字符串日志输出