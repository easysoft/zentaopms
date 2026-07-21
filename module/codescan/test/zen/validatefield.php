#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->validateField();
timeout=0
cid=0

- 测试*通配符 >> 1
- 测试有效数字范围 >> 1
- 测试有效步长 >> 1
- 测试简单数字 >> 1
- 测试逗号分隔 >> 1

*/

su('admin');
$test = new codescanZenTest();

r($test->validateFieldTest('*', 'minute') && p() && e('1');
r($test->validateFieldTest('0-59', 'minute') && p() && e('1');
r($test->validateFieldTest('*/5', 'hour') && p() && e('1');
r($test->validateFieldTest('30', 'minute') && p() && e('1');
r($test->validateFieldTest('1,15,30', 'minute') && p() && e('1');
