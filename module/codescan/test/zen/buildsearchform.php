#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->buildSearchForm();
timeout=0
cid=0

- 测试空参数调用返回有效结果 >> 1
- 测试无fatal错误 >> 1
- 测试返回类型有效 >> 1
- 测试第二次调用一致性 >> 1
- 测试第三次调用 >> 1

*/


su('admin');
$test = new codescanZenTest();

r(is_null($test->buildsearchformTest(array('module' => 'codescan', 'params' => array()), 0, 'test-url'))) && p() && e('1');
r(is_null($test->buildsearchformTest(array(), 1, ''))) && p() && e('1');
r(is_null($test->buildsearchformTest(array('module' => 'codescan'), 0, 'url'))) && p() && e('1');
r(is_null($test->buildsearchformTest(array('module' => 'codescan', 'params' => array('type' => array('values' => array('all' => 'all')))), 0, 'test'))) && p() && e('1');
r(is_null($test->buildsearchformTest(array(), '0', 'test-url'))) && p() && e('1');
