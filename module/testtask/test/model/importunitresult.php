#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importUnitResult();
timeout=0
cid=19202

- 执行testtaskTest模块的parseXMLResultTest方法，参数是$validXML, 1, 'junit' 属性cases @2
- 执行testtaskTest模块的parseXMLResultTest方法，参数是$emptyXML, 1, 'junit' 属性cases @0
- 执行testtaskTest模块的initSuiteTest方法，参数是1, 'TestSuite', $now 属性name @TestSuite
- 执行testtaskTest模块的initCaseTest方法，参数是1, 'TestCase', $now, 'unit', 'junit' 属性title @TestCase
- 执行testtaskTest模块的importDataOfUnitResultTest方法，参数是1, 1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zendata('product')->gen(10);
zendata('project')->gen(10);
zendata('build')->gen(5);

zendata('testtask')->gen(0);
zendata('testcase')->gen(0);
zendata('testrun')->gen(0);
zendata('testsuite')->gen(0);
zendata('suitecase')->gen(0);

su('admin');

$testtaskTest = new testtaskTest();

// 准备测试数据
$validXML = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="ExampleTest" tests="2" failures="0" errors="0" time="0.042">
  <testcase classname="ExampleTest" name="testExample1" time="0.021"/>
  <testcase classname="ExampleTest" name="testExample2" time="0.021"/>
</testsuite>');

$emptyXML = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="EmptyTest" tests="0" failures="0" errors="0" time="0"/>');

$now = date('Y-m-d H:i:s');

r(count($testtaskTest->parseXMLResultTest($validXML, 1, 'junit')['cases'][0])) && p() && e('2');
r(count($testtaskTest->parseXMLResultTest($emptyXML, 1, 'junit')['cases'])) && p() && e('0');
r($testtaskTest->initSuiteTest(1, 'TestSuite', $now)) && p('name') && e('TestSuite');
r($testtaskTest->initCaseTest(1, 'TestCase', $now, 'unit', 'junit')) && p('title') && e('TestCase');
r($testtaskTest->importDataOfUnitResultTest(1, 1, array(), array(), array(), array(), array())) && p() && e('1');
