#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->isbinary();
timeout=0
cid=0

- 正常文本 @0
- PDF后缀 @1
- null字节 @1
- 高频率回车换行 @1
- 空字符串 @0

*/

su('admin');
$test = new repoZenTest();

r($test->isBinaryTest('This is normal text', '')) && p() && e('0');
r($test->isBinaryTest('content', 'pdf')) && p() && e('1');
r($test->isBinaryTest("content\x00null", '')) && p() && e('1');
r($test->isBinaryTest(str_repeat("\r\n", 200), '')) && p() && e('1');
r($test->isBinaryTest('', '')) && p() && e('0');
