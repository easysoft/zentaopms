#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4Execution();
timeout=0
cid=0

- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'bug', 1 
 - 属性executionMenuLoaded @1
 - 属性rawModuleSet @1
 - 属性bugSubModuleSet @1
 - 属性testcaseSubModuleSet @0
 - 属性testtaskSubModuleSet @0
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testcase', 2 
 - 属性executionMenuLoaded @1
 - 属性rawModuleSet @1
 - 属性bugSubModuleSet @0
 - 属性testcaseSubModuleSet @1
 - 属性testtaskSubModuleSet @0
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testtask', 3 
 - 属性executionMenuLoaded @1
 - 属性rawModuleSet @1
 - 属性testtaskSubModuleSet @1
 - 属性testreportSubModuleSet @0
 - 属性paramsValid @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'testreport', 4 
 - 属性executionMenuLoaded @1
 - 属性rawModuleSet @1
 - 属性testtaskSubModuleSet @0
 - 属性testreportSubModuleSet @1
 - 属性paramsValid @1
- 执行productTest模块的setShowErrorNoneMenu4ExecutionTest方法，参数是'other', 0 
 - 属性executionMenuLoaded @0
 - 属性rawModuleSet @1
 - 属性bugSubModuleSet @0
 - 属性testcaseSubModuleSet @0
 - 属性paramsValid @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$table->type->range('sprint{10}');
$table->status->range('doing{5},wait{3},closed{2}');
$table->grade->range('2');
$table->parent->range('11-20');
$table->path->range(',11,1,,12,2,,13,3,,14,4,,15,5,,16,6,,17,7,,18,8,,19,9,,20,10,');
$table->gen(10);

su('admin');

$productTest = new productTest();

r($productTest->setShowErrorNoneMenu4ExecutionTest('bug', 1)) && p('executionMenuLoaded,rawModuleSet,bugSubModuleSet,testcaseSubModuleSet,testtaskSubModuleSet') && e('1,1,1,0,0');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testcase', 2)) && p('executionMenuLoaded,rawModuleSet,bugSubModuleSet,testcaseSubModuleSet,testtaskSubModuleSet') && e('1,1,0,1,0');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testtask', 3)) && p('executionMenuLoaded,rawModuleSet,testtaskSubModuleSet,testreportSubModuleSet,paramsValid') && e('1,1,1,0,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('testreport', 4)) && p('executionMenuLoaded,rawModuleSet,testtaskSubModuleSet,testreportSubModuleSet,paramsValid') && e('1,1,0,1,1');
r($productTest->setShowErrorNoneMenu4ExecutionTest('other', 0)) && p('executionMenuLoaded,rawModuleSet,bugSubModuleSet,testcaseSubModuleSet,paramsValid') && e('0,1,0,0,0');