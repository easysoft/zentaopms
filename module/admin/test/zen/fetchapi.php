#!/usr/bin/env php
<?php

/**

title=测试 adminZen::fetchAPI();
timeout=0
cid=0

- 执行adminTest模块的fetchAPITest方法，参数是'http://example.com/api/test'  @0
- 执行adminTest模块的fetchAPITest方法，参数是'http://example.com/api?param=value'  @0
- 执行adminTest模块的fetchAPITest方法，参数是''  @0
- 执行adminTest模块的fetchAPITest方法，参数是'invalid-url'  @0
- 执行adminTest模块的fetchAPITest方法，参数是'http://example.com/api?param=中文'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

r($adminTest->fetchAPITest('http://example.com/api/test')) && p() && e('0');
r($adminTest->fetchAPITest('http://example.com/api?param=value')) && p() && e('0');
r($adminTest->fetchAPITest('')) && p() && e('0');
r($adminTest->fetchAPITest('invalid-url')) && p() && e('0');
r($adminTest->fetchAPITest('http://example.com/api?param=中文')) && p() && e('0');