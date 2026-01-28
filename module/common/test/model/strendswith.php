#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 commonModel::strEndsWith();
timeout=0
cid=15717

- 步骤1：正常情况-字符串以指定后缀结尾 @1
- 步骤2：不匹配情况-字符串不以指定后缀结尾 @0
- 步骤3：空后缀情况-后缀为空字符串 @1
- 步骤4：相同字符串情况-haystack和needle相同 @1
- 步骤5：大小写敏感-大小写不匹配 @0
- 步骤6：部分匹配-包含但不在末尾 @0
- 步骤7：空haystack情况-被检查字符串为空且needle也为空 @1

*/

$commonTest = new commonModelTest();

r($commonTest->strEndsWithTest('hello world', 'world')) && p() && e('1'); // 步骤1：正常情况-字符串以指定后缀结尾
r($commonTest->strEndsWithTest('hello world', 'hello')) && p() && e('0'); // 步骤2：不匹配情况-字符串不以指定后缀结尾
r($commonTest->strEndsWithTest('hello world', '')) && p() && e('1'); // 步骤3：空后缀情况-后缀为空字符串
r($commonTest->strEndsWithTest('test', 'test')) && p() && e('1'); // 步骤4：相同字符串情况-haystack和needle相同
r($commonTest->strEndsWithTest('Hello World', 'world')) && p() && e('0'); // 步骤5：大小写敏感-大小写不匹配
r($commonTest->strEndsWithTest('hello world test', 'world')) && p() && e('0'); // 步骤6：部分匹配-包含但不在末尾
r($commonTest->strEndsWithTest('', '')) && p() && e('1'); // 步骤7：空haystack情况-被检查字符串为空且needle也为空