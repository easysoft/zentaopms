#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getProxyType();
timeout=0
cid=15046

- 执行aiTest模块的getProxyTypeTest方法，参数是'http'  @0
- 执行aiTest模块的getProxyTypeTest方法，参数是'socks4'  @4
- 执行aiTest模块的getProxyTypeTest方法，参数是'socks5'  @5
- 执行aiTest模块的getProxyTypeTest方法，参数是'invalid'  @0
- 执行aiTest模块的getProxyTypeTest方法，参数是''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->getProxyTypeTest('http')) && p() && e('0');
r($aiTest->getProxyTypeTest('socks4')) && p() && e('4');
r($aiTest->getProxyTypeTest('socks5')) && p() && e('5');
r($aiTest->getProxyTypeTest('invalid')) && p() && e('0');
r($aiTest->getProxyTypeTest('')) && p() && e('0');