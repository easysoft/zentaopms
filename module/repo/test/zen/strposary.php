#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen::strposAry();
timeout=0
cid=0

- 正常匹配fatal @1
- 不匹配 @0
- 空数组 @0
- 中文匹配 @1
- 空字符串匹配 @1

*/

su('admin');
$test = new repoZenTest();

r($test->strposAryTest('fatal error', array('fatal', 'error'))) && p() && e('1');
r($test->strposAryTest('normal log', array('fatal', 'error'))) && p() && e('0');
r($test->strposAryTest('any', array())) && p() && e('0');
r($test->strposAryTest('包含中文', array('中文', 'english'))) && p() && e('1');
r($test->strposAryTest('test', array('', 'none'))) && p() && e('1');
