#!/usr/bin/env php
<?php

/**

title=测试 ciZen::parseZtfResult();
timeout=0
cid=15597

- 步骤1：单元测试类型正常情况 @1
- 步骤2：功能测试类型正常情况 @1
- 步骤3：单元测试类型空结果 @1
- 步骤4：功能测试类型空结果 @1
- 步骤5：单元测试类型多个用例 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('testtask')->loadYaml('parseztfresult/zt_testtask', false, 2)->gen(10);
zendata('case')->loadYaml('parseztfresult/zt_case', false, 2)->gen(50);

$testsuite = zenData('testsuite');
$testsuite->id->range('1-20');
$testsuite->product->range('1-5');
$testsuite->name->range('测试套件1,测试套件2,测试套件3,测试套件4,测试套件5');
$testsuite->deleted->range('0');
$testsuite->gen(20);

$testresult = zenData('testresult');
$testresult->id->range('1-100');
$testresult->case->range('1-50');
$testresult->run->range('1-100');
$testresult->caseResult->range('pass{70},fail{20},blocked{10}');
$testresult->gen(100);

su('admin');

$ciTest = new ciZenTest();

r($ciTest->parseZtfResultTest((object)array('testType' => 'unit', 'testFrame' => 'junit', 'unitResult' => array((object)array('title' => 'UnitTest1', 'duration' => 0.5))), 1, 1, 1, 1)) && p() && e('1'); // 步骤1：单元测试类型正常情况
r($ciTest->parseZtfResultTest((object)array('testType' => 'func', 'testFrame' => 'junit', 'funcResult' => array((object)array('title' => 'FuncTest1', 'id' => 1, 'steps' => array((object)array('name' => 'Step1', 'status' => 'pass', 'checkPoints' => array((object)array('expect' => 'result1', 'actual' => 'result1'))))))), 2, 2, 2, 2)) && p() && e('1'); // 步骤2：功能测试类型正常情况
r($ciTest->parseZtfResultTest((object)array('testType' => 'unit', 'testFrame' => 'junit', 'unitResult' => array()), 3, 3, 3, 3)) && p() && e('1'); // 步骤3：单元测试类型空结果
r($ciTest->parseZtfResultTest((object)array('testType' => 'func', 'testFrame' => 'junit', 'funcResult' => array()), 4, 4, 4, 4)) && p() && e('1'); // 步骤4：功能测试类型空结果
r($ciTest->parseZtfResultTest((object)array('testType' => 'unit', 'testFrame' => 'phpunit', 'unitResult' => array((object)array('title' => 'UnitTest1', 'duration' => 0.5), (object)array('title' => 'UnitTest2', 'duration' => 0.8))), 5, 5, 5, 5)) && p() && e('1'); // 步骤5：单元测试类型多个用例