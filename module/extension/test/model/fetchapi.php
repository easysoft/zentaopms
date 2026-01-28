#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::fetchAPI();
timeout=0
cid=16454

- 执行extensionTest模块的fetchAPITest方法，参数是'http://api.example.com/test.json'  @0
- 执行extensionTest模块的fetchAPITest方法，参数是''  @0
- 执行extensionTest模块的fetchAPITest方法，参数是'invalid-url'  @0
- 执行extensionTest模块的fetchAPITest方法，参数是'http://api.test.com/special-chars?param=value&test=123'  @0
- 执行extensionTest模块的fetchAPITest方法，参数是str_repeat  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$extensionTest = new extensionModelTest();

r($extensionTest->fetchAPITest('http://api.example.com/test.json')) && p() && e('0');
r($extensionTest->fetchAPITest('')) && p() && e('0');
r($extensionTest->fetchAPITest('invalid-url')) && p() && e('0');
r($extensionTest->fetchAPITest('http://api.test.com/special-chars?param=value&test=123')) && p() && e('0');
r($extensionTest->fetchAPITest(str_repeat('http://very-long-url.com/', 100))) && p() && e('0');