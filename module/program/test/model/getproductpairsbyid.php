#!/usr/bin/env php
<?php

/**

title=测试 programModel::getProductPairsByID();
timeout=0
cid=0

- 获取项目集ID为1的所有产品键值对
 - 属性1 @产品1
 - 属性2 @产品2
 - 属性3 @产品3
- 获取项目集ID为2的所有产品键值对属性4 @产品4
- 获取不存在的项目集ID的产品键值对 @~~
- 获取项目集ID为0的产品键值对 @~~
- 获取没有产品的项目集ID的产品键值对 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$program->type->range('program');
$program->grade->range('1');
$program->path->range('1,2,3,4,5')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(5);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->program->range('1{3},2{1},999{3},1{3}');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0{7},1{3}');
$product->vision->range('rnd');
$product->gen(10);

$programTest = new programTest();

r($programTest->getProductPairsByIDTest(1)) && p('1,2,3') && e('产品1,产品2,产品3'); // 获取项目集ID为1的所有产品键值对
r($programTest->getProductPairsByIDTest(2)) && p('4') && e('产品4'); // 获取项目集ID为2的所有产品键值对
r($programTest->getProductPairsByIDTest(999)) && p() && e('~~'); // 获取不存在的项目集ID的产品键值对
r($programTest->getProductPairsByIDTest(0)) && p() && e('~~'); // 获取项目集ID为0的产品键值对
r($programTest->getProductPairsByIDTest(3)) && p() && e('~~'); // 获取没有产品的项目集ID的产品键值对