#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkNotCN();
timeout=0
cid=15659

- 步骤1：中文简体 @0
- 步骤2：中文繁体 @0
- 步骤3：英语 @1
- 步骤4：德语 @1
- 步骤5：法语 @1
- 步骤6：空语言 @0
- 步骤7：未知语言 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->checkNotCNTest('zh-cn')) && p() && e('0'); // 步骤1：中文简体
r($commonTest->checkNotCNTest('zh-tw')) && p() && e('0'); // 步骤2：中文繁体
r($commonTest->checkNotCNTest('en')) && p() && e('1'); // 步骤3：英语
r($commonTest->checkNotCNTest('de')) && p() && e('1'); // 步骤4：德语
r($commonTest->checkNotCNTest('fr')) && p() && e('1'); // 步骤5：法语
r($commonTest->checkNotCNTest('')) && p() && e('0'); // 步骤6：空语言（默认为zh-cn）
r($commonTest->checkNotCNTest('test')) && p() && e('1'); // 步骤7：未知语言