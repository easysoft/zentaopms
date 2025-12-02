#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchOptions();
timeout=0
cid=17575

- 测试空产品列表 @0
- 测试只有normal类型产品 @0
- 测试单个branch类型产品有2个分支第2条的1属性 @分支1
- 测试多个branch类型产品 @2
- 测试mixed类型(normal+branch)产品列表 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal,branch,branch,normal,branch');
$product->status->range('normal{5}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('2,2,3,3,3,5,5,5,5,5');
$branch->name->range('分支1,分支2,V1.0,V2.0,V3.0,主干,开发分支,测试分支,发布分支,维护分支');
$branch->status->range('active{10}');
$branch->deleted->range('0{10}');
$branch->gen(10);

global $tester;
$productTest = new productZenTest();

r(count($productTest->getBranchOptionsTest(array(), 0))) && p() && e('0'); // 测试空产品列表
r(count($productTest->getBranchOptionsTest(array($tester->loadModel('product')->getById(1)), 0))) && p() && e('0'); // 测试只有normal类型产品
r($productTest->getBranchOptionsTest(array($tester->loadModel('product')->getById(2)), 0)) && p('2:1') && e('分支1'); // 测试单个branch类型产品有2个分支
r(count($productTest->getBranchOptionsTest(array($tester->loadModel('product')->getById(2), $tester->loadModel('product')->getById(3)), 0))) && p() && e('2'); // 测试多个branch类型产品
r(count($productTest->getBranchOptionsTest(array($tester->loadModel('product')->getById(1), $tester->loadModel('product')->getById(2), $tester->loadModel('product')->getById(4)), 0))) && p() && e('1'); // 测试mixed类型(normal+branch)产品列表