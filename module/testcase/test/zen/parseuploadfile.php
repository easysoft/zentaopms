#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::parseUploadFile();
timeout=0
cid=0

- 步骤1:空文件数据 @1
- 步骤2:productID为0 @1
- 步骤3:branch为空字符串 @1
- 步骤4:不存在的productID @1
- 步骤5:负数productID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

$result1 = $testcaseTest->parseUploadFileTest(1, 0, array());
$result2 = $testcaseTest->parseUploadFileTest(0, 0, array());
$result3 = $testcaseTest->parseUploadFileTest(1, '', array());
$result4 = $testcaseTest->parseUploadFileTest(999, 0, array());
$result5 = $testcaseTest->parseUploadFileTest(-1, 0, array());

r(is_string($result1) && strpos($result1, 'error') !== false) && p() && e('1'); // 步骤1:空文件数据
r(is_string($result2) && strpos($result2, 'error') !== false) && p() && e('1'); // 步骤2:productID为0
r(is_string($result3) && strpos($result3, 'error') !== false) && p() && e('1'); // 步骤3:branch为空字符串
r(is_string($result4) && strpos($result4, 'error') !== false) && p() && e('1'); // 步骤4:不存在的productID
r(is_string($result5) && strpos($result5, 'error') !== false) && p() && e('1'); // 步骤5:负数productID