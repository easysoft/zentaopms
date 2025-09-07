#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkIP();
timeout=0
cid=0

- 执行commonTest模块的checkIPTest方法，参数是'*'  @1
- 执行commonTest模块的checkIPTest方法，参数是'127.0.0.1'  @1
- 执行commonTest模块的checkIPTest方法，参数是'192.168.1.1, 127.0.0.1, 10.0.0.1'  @1
- 执行commonTest模块的checkIPTest方法，参数是'127.0.0.1-127.0.0.255'  @1
- 执行commonTest模块的checkIPTest方法，参数是'127.0.0.*'  @1
- 执行commonTest模块的checkIPTest方法，参数是'127.0.0.0/24'  @1
- 执行commonTest模块的checkIPTest方法，参数是'192.168.1.1'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->checkIPTest('*')) && p() && e('1');
r($commonTest->checkIPTest('127.0.0.1')) && p() && e('1');
r($commonTest->checkIPTest('192.168.1.1,127.0.0.1,10.0.0.1')) && p() && e('1');
r($commonTest->checkIPTest('127.0.0.1-127.0.0.255')) && p() && e('1');
r($commonTest->checkIPTest('127.0.0.*')) && p() && e('1');
r($commonTest->checkIPTest('127.0.0.0/24')) && p() && e('1');
r($commonTest->checkIPTest('192.168.1.1')) && p() && e('0');