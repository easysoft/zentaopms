#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu();
timeout=0
cid=0

- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'mhtml', 'testcase', 1 属性mhtmlMenuCalled @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'qa', 'testcase', 1 属性qaTestcaseHandled @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'project', 'bug', 1 
 - 属性projectMenuCalled @1
 - 属性projectSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'execution', 'testtask', 1 
 - 属性executionMenuCalled @1
 - 属性executionSubModuleSet @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'invalid', 'test', 0 属性paramsValid @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

su('admin');

$productTest = new productTest();

r($productTest->setShowErrorNoneMenuTest('mhtml', 'testcase', 1)) && p('mhtmlMenuCalled') && e('1');
r($productTest->setShowErrorNoneMenuTest('qa', 'testcase', 1)) && p('qaTestcaseHandled') && e('1');
r($productTest->setShowErrorNoneMenuTest('project', 'bug', 1)) && p('projectMenuCalled,projectSubModuleSet') && e('1,1');
r($productTest->setShowErrorNoneMenuTest('execution', 'testtask', 1)) && p('executionMenuCalled,executionSubModuleSet') && e('1,1');
r($productTest->setShowErrorNoneMenuTest('invalid', 'test', 0)) && p('paramsValid') && e('0');