#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getLinkedObjects();
timeout=0
cid=0

- 执行executionTest模块的getLinkedObjectsTest方法，参数是$execution1  @Array
- 执行executionTest模块的getLinkedObjectsTest方法，参数是$execution2  @(
- 执行executionTest模块的getLinkedObjectsTest方法，参数是$execution3 属性error @[error] =>
- 执行executionTest模块的getLinkedObjectsTest方法，参数是$execution4  @)
- 执行executionTest模块的getLinkedObjectsTest方法，参数是$execution5  @Array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->loadYaml('project_getlinkedobjects')->gen(10);
zenData('product')->loadYaml('product_getlinkedobjects')->gen(10);
zenData('productplan')->loadYaml('productplan_getlinkedobjects')->gen(15);
zenData('projectproduct')->loadYaml('projectproduct_getlinkedobjects')->gen(13);
zenData('projectstory')->loadYaml('projectstory_getlinkedobjects')->gen(17);
zenData('branch')->loadYaml('branch_getlinkedobjects')->gen(5);

su('admin');

$executionTest = new executionZenTest();

// 构建测试执行对象
$execution1 = new stdClass();
$execution1->id = 6;
$execution1->project = 1;

$execution2 = new stdClass();
$execution2->id = 7;
$execution2->project = 2;

$execution3 = new stdClass();
$execution3->id = 8;
$execution3->project = 2;

$execution4 = new stdClass();
$execution4->id = 9;
$execution4->project = 3;

$execution5 = new stdClass();
$execution5->id = 10;
$execution5->project = 3;

r($executionTest->getLinkedObjectsTest($execution1)) && p() && e('Array');
r($executionTest->getLinkedObjectsTest($execution2)) && p() && e('(');
r($executionTest->getLinkedObjectsTest($execution3)) && p('error') && e('[error] =>');
r($executionTest->getLinkedObjectsTest($execution4)) && p() && e(')');
r($executionTest->getLinkedObjectsTest($execution5)) && p() && e('Array');