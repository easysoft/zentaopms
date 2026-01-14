#!/usr/bin/env php
<?php

/**

title=测试 svnModel::printLog();
timeout=0
cid=18719

- 步骤1：正常日志输出 @1
- 步骤2：空字符串输入 @1
- 步骤3：包含特殊字符的日志 @1
- 步骤4：长字符串输入 @1
- 步骤5：包含换行符的日志 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$svnTest = new svnModelTest();

r(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} Processing commit 12345/', $svnTest->printLogTest('Processing commit 12345'))) && p() && e(1); // 步骤1：正常日志输出
r(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $svnTest->printLogTest(''))) && p() && e(1); // 步骤2：空字符串输入
r(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} Special chars: <>|&\$#@!/', $svnTest->printLogTest('Special chars: <>|&$#@!'))) && p() && e(1); // 步骤3：包含特殊字符的日志
r(strlen($svnTest->printLogTest(str_repeat('Long content test ', 20))) > 350) && p() && e(1); // 步骤4：长字符串输入
r(strpos($svnTest->printLogTest("Multi\nline\ncontent"), "Multi\nline\ncontent") !== false) && p() && e(1); // 步骤5：包含换行符的日志