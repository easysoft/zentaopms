#!/usr/bin/env php
<?php

/**

title=测试 ciZen::getProductIdAndJobID();
timeout=0
cid=0

- 步骤1:从compile获取productID和jobID
 -  @1
 - 属性1 @1
- 步骤2:优先使用post中的productID
 -  @5
 - 属性1 @2
- 步骤3:从funcResult获取productID
 -  @3
 - 属性1 @0
- 步骤4:从case表获取productID
 -  @1
 - 属性1 @0
- 步骤5:无compileID和productID返回0
 -  @0
 - 属性1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$compile = zenData('compile');
$compile->id->range('1-10');
$compile->name->range('compile1,compile2,compile3,compile4,compile5');
$compile->job->range('1-5');
$compile->status->range('success');
$compile->deleted->range('0');
$compile->gen(10);

$job = zenData('job');
$job->id->range('1-10');
$job->name->range('job1,job2,job3,job4,job5');
$job->product->range('1-5');
$job->engine->range('jenkins');
$job->deleted->range('0');
$job->gen(10);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-5');
$case->deleted->range('0');
$case->gen(10);

su('admin');

$ciTest = new ciZenTest();

r($ciTest->getProductIdAndJobIDTest(array('compile' => 1), (object)array('testType' => 'unit', 'productId' => 0))) && p('0,1') && e('1,1'); // 步骤1:从compile获取productID和jobID
r($ciTest->getProductIdAndJobIDTest(array('compile' => 2), (object)array('testType' => 'unit', 'productId' => 5))) && p('0,1') && e('5,2'); // 步骤2:优先使用post中的productID
r($ciTest->getProductIdAndJobIDTest(array(), (object)array('testType' => 'func', 'funcResult' => array((object)array('productId' => 3, 'id' => 0))))) && p('0,1') && e('3,0'); // 步骤3:从funcResult获取productID
r($ciTest->getProductIdAndJobIDTest(array(), (object)array('testType' => 'func', 'funcResult' => array((object)array('productId' => 0, 'id' => 1))))) && p('0,1') && e('1,0'); // 步骤4:从case表获取productID
r($ciTest->getProductIdAndJobIDTest(array(), (object)array('testType' => 'unit', 'productId' => 0))) && p('0,1') && e('0,0'); // 步骤5:无compileID和productID返回0