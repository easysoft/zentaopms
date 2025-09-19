#!/usr/bin/env php
<?php

/**

title=测试 devModel::trimSpace();
timeout=0
cid=0

- 测试步骤1：去除带星号和空格的普通字符串前后空白字符 >> 期望返回清理后的字符串
- 测试步骤2：去除包含制表符和换行符的字符串空白字符 >> 期望正确处理特殊空白字符
- 测试步骤3：测试空字符串输入的处理 >> 期望返回空字符串
- 测试步骤4：测试仅包含空白字符的字符串处理 >> 期望返回空字符串
- 测试步骤5：测试不包含需要清理字符的普通字符串 >> 期望返回原字符串

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$devTest = new devTest();

r($devTest->trimSpaceTest('* test ')) && p() && e('test');
r($devTest->trimSpaceTest(" \t\n\r * hello world \t\n\r ")) && p() && e('hello world');
r($devTest->trimSpaceTest('')) && p() && e('');
r($devTest->trimSpaceTest('* \t\n\r ')) && p() && e('');
r($devTest->trimSpaceTest('normal string')) && p() && e('normal string');