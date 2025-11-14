#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4Execution();
timeout=0
cid=17615

- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'bug', 1
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testcase', 2
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testtask', 3
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testreport', 4
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'other', 5
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'', 0 属性executionSuccess @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'bug', 0
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testcase', 100
 - 属性executionSuccess @1
 - 属性rawModuleMatch @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setShowErrorNoneMenu4ExecutionTest('bug', 1)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testcase', 2)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testtask', 3)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testreport', 4)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('other', 5)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('', 0)) && p('executionSuccess') && e('1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('bug', 0)) && p('executionSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testcase', 100)) && p('executionSuccess,rawModuleMatch') && e('1,1');