#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBatchResolveVars();
timeout=0
cid=0

- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @2
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @2
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @0
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @0
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1{5},2{3},3{2}');
$bugTable->module->range('1-5,1-3,1-2');
$bugTable->title->range('Bug Title {1-10}');
$bugTable->status->range('active{5},resolved{3},closed{2}');
$bugTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('Product 1,Product 2,Product 3');
$productTable->QD->range('admin,user1,user2');
$productTable->status->range('normal{3}');
$productTable->gen(3);

$moduleTable = zenData('module');
$moduleTable->id->range('1-5');
$moduleTable->root->range('1{2},2{2},3{1}');
$moduleTable->name->range('Module 1,Module 2,Module 3,Module 4,Module 5');
$moduleTable->type->range('bug{5}');
$moduleTable->gen(5);

su('admin');

$bugTest = new bugTest();

r($bugTest->getBatchResolveVarsTest(array(1, 2, 3))) && p('0') && e('2');
r($bugTest->getBatchResolveVarsTest(array(1))) && p('0') && e('2');
r($bugTest->getBatchResolveVarsTest(array())) && p() && e('0');
r($bugTest->getBatchResolveVarsTest(array(999))) && p() && e('0');
r($bugTest->getBatchResolveVarsTest(array(6, 7))) && p('0') && e('2');