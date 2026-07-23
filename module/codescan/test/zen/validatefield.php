#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->validateField();
timeout=0
cid=0

- step1 >> 1
- step2 >> 1
- step3 >> 1
- step4 >> 1
- step5 >> 1

*/

su('admin');
$test = new codescanZenTest();

r($test->validateFieldTest('*', 'minute')) && p() && e('1');
r($test->validateFieldTest('0-59', 'minute')) && p() && e('1');
r($test->validateFieldTest('0/5', 'hour')) && p() && e('1');
r($test->validateFieldTest('30', 'minute')) && p() && e('1');
r($test->validateFieldTest('1,15,30', 'minute')) && p() && e('1');
