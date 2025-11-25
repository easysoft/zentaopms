#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::setMenu();
timeout=0
cid=19023

- 执行testcaseTest模块的setMenuTest方法，参数是1  @1
- 执行testcaseTest模块的setMenuTest方法  @1
- 执行testcaseTest模块的setMenuTest方法，参数是2, 'main'  @1
- 执行testcaseTest模块的setMenuTest方法，参数是3, 1  @1
- 执行testcaseTest模块的setMenuTest方法，参数是999  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('product')->gen(5);
zenData('user')->gen(1);
zenData('usergroup')->gen(5);

su('admin');

$testcaseTest = new testcaseTest();

r($testcaseTest->setMenuTest(1)) && p() && e('1');
r($testcaseTest->setMenuTest(0)) && p() && e('1');
r($testcaseTest->setMenuTest(2, 'main')) && p() && e('1');
r($testcaseTest->setMenuTest(3, 1)) && p() && e('1');
r($testcaseTest->setMenuTest(999)) && p() && e('1');