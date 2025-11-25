#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchAndTagOption();
timeout=0
cid=17573

- 测试产品为空且isProjectStory为true @0
- 测试产品类型为normal @0
- 测试产品类型为branch有两个active分支
 - 第0条的1属性 @分支1
 - 第0条的2属性 @分支2
- 测试产品类型为branch有closed分支第1条的5属性 @V3.0 (已关闭)
- 测试产品类型为branch返回分支数量 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal,branch,branch,branch,branch');
$product->status->range('normal{5}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('2,2,3,3,3,4,4,5,5,5');
$branch->name->range('分支1,分支2,V1.0,V2.0,V3.0,主干,开发分支,R1,R2,R3');
$branch->status->range('active,active,active,active,closed,active,closed,active,active,active');
$branch->deleted->range('0{10}');
$branch->gen(10);

global $tester;
$productTest = new productZenTest();

r(count($productTest->getBranchAndTagOptionTest(0, null, true)[0])) && p() && e('0'); // 测试产品为空且isProjectStory为true
r(count($productTest->getBranchAndTagOptionTest(0, $tester->loadModel('product')->getById(1), false)[0])) && p() && e('0'); // 测试产品类型为normal
r($productTest->getBranchAndTagOptionTest(0, $tester->loadModel('product')->getById(2), false)) && p('0:1;0:2') && e('分支1,分支2'); // 测试产品类型为branch有两个active分支
r($productTest->getBranchAndTagOptionTest(0, $tester->loadModel('product')->getById(3), false)) && p('1:5') && e('V3.0 (已关闭)'); // 测试产品类型为branch有closed分支
r(count($productTest->getBranchAndTagOptionTest(0, $tester->loadModel('product')->getById(5), false)[0])) && p() && e('4'); // 测试产品类型为branch返回分支数量