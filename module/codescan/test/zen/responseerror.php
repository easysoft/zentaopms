#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->responseError();
timeout=0
cid=0

- 测试空参数调用 >> 1
- 测试带error msg参数 >> 1
- 测试空字符串参数 >> 1
- 测试数组参数 >> 1
- 测试带locate参数 >> 1

*/

su('admin');
$test = new codescanZenTest();

r($test->responseErrorTest()) && p() && e('1');
r($test->responseErrorTest('error msg')) && p() && e('1');
r($test->responseErrorTest('')) && p() && e('1');
r($test->responseErrorTest(array('field' => 'error'))) && p() && e('1');
r($test->responseErrorTest('test', '/redirect')) && p() && e('1');
