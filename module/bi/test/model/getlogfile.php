#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getLogFile();
timeout=0
cid=15174

- 步骤1：正常调用getLogFile方法返回字符串 @1
- 步骤2：验证返回值包含日志目录 @1
- 步骤3：验证返回值包含syncparquet前缀 @1
- 步骤4：验证返回值包含.log.php扩展名 @1
- 步骤5：验证返回值包含当前日期 @1

*/

$biTest = new biTest();

r(is_string($biTest->getLogFileTest())) && p() && e('1'); // 步骤1：正常调用getLogFile方法返回字符串
r(strpos($biTest->getLogFileTest(), 'log/') !== false) && p() && e('1'); // 步骤2：验证返回值包含日志目录
r(strpos($biTest->getLogFileTest(), 'syncparquet') !== false) && p() && e('1'); // 步骤3：验证返回值包含syncparquet前缀
r(strpos($biTest->getLogFileTest(), '.log.php') !== false) && p() && e('1'); // 步骤4：验证返回值包含.log.php扩展名
r(strpos($biTest->getLogFileTest(), date('Ymd')) !== false) && p() && e('1'); // 步骤5：验证返回值包含当前日期