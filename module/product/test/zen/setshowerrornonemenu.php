#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu();
timeout=0
cid=0

- 测试步骤1:qa模块testcase菜单 @1
- 测试步骤2:qa模块testsuite菜单 @1
- 测试步骤3:qa模块testtask菜单 @1
- 测试步骤4:qa模块testreport菜单 @1
- 测试步骤5:mhtml视图类型菜单设置 @1
- 测试步骤6:execution模块testtask菜单 @1
- 测试步骤7:execution模块testcase菜单 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setShowErrorNoneMenuTest('qa', 'testcase', 0, '')) && p() && e('1'); // 测试步骤1:qa模块testcase菜单
r($productTest->setShowErrorNoneMenuTest('qa', 'testsuite', 0, '')) && p() && e('1'); // 测试步骤2:qa模块testsuite菜单
r($productTest->setShowErrorNoneMenuTest('qa', 'testtask', 0, '')) && p() && e('1'); // 测试步骤3:qa模块testtask菜单
r($productTest->setShowErrorNoneMenuTest('qa', 'testreport', 0, '')) && p() && e('1'); // 测试步骤4:qa模块testreport菜单
r($productTest->setShowErrorNoneMenuTest('qa', 'testcase', 0, 'mhtml')) && p() && e('1'); // 测试步骤5:mhtml视图类型菜单设置
r($productTest->setShowErrorNoneMenuTest('execution', 'testtask', 101, '')) && p() && e('1'); // 测试步骤6:execution模块testtask菜单
r($productTest->setShowErrorNoneMenuTest('execution', 'testcase', 101, '')) && p() && e('1'); // 测试步骤7:execution模块testcase菜单