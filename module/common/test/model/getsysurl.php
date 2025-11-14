#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getSysURL();
timeout=0
cid=15677

- 执行commonTest模块的getSysURLTest方法，参数是1  @0
- 执行commonTest模块的getSysURLTest方法，参数是2  @https://example.com
- 执行commonTest模块的getSysURLTest方法，参数是3  @http://example.com
- 执行commonTest模块的getSysURLTest方法，参数是4  @https://example.com
- 执行commonTest模块的getSysURLTest方法，参数是5  @https://example.com

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

// 测试模式下返回空字符串
r($commonTest->getSysURLTest(1)) && p() && e('0');

// 模拟HTTPS环境测试
r($commonTest->getSysURLTest(2)) && p() && e('https://example.com');

// 模拟HTTP环境测试
r($commonTest->getSysURLTest(3)) && p() && e('http://example.com');

// 模拟X-Forwarded-Proto头部测试
r($commonTest->getSysURLTest(4)) && p() && e('https://example.com');

// 模拟REQUEST_SCHEME头部测试
r($commonTest->getSysURLTest(5)) && p() && e('https://example.com');