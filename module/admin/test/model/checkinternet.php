#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkInternet();
timeout=0
cid=0

- 执行adminTest模块的checkInternetTest方法  @1
- 执行adminTest模块的checkInternetTest方法，参数是'https://www.zentao.net'  @1
- 执行adminTest模块的checkInternetTest方法，参数是'http://invalid-domain-test-12345.com'  @0
- 执行adminTest模块的checkInternetTest方法，参数是'', 0  @1
- 执行adminTest模块的checkInternetTest方法，参数是'', 5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$adminTest = new adminTest();

r($adminTest->checkInternetTest()) && p() && e('1');
r($adminTest->checkInternetTest('https://www.zentao.net')) && p() && e('1');
r($adminTest->checkInternetTest('http://invalid-domain-test-12345.com')) && p() && e('0');
r($adminTest->checkInternetTest('', 0)) && p() && e('1');
r($adminTest->checkInternetTest('', 5)) && p() && e('1');