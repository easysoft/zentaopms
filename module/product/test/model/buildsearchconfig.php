#!/usr/bin/env php
<?php

/**

title=测试 productModel::buildSearchConfig();
timeout=0
cid=0

- 测试普通产品不包含branch字段第fields条的branch属性 @~~
- 测试多分支产品包含branch字段第fields条的branch属性 @*
- 测试requirement类型标题字段第fields条的title属性 @*
- 测试epic类型模块参数第params条的module:values属性 @*
- 测试无效产品ID返回配置第params条的plan:values属性 @array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('正常产品{6},多分支产品{4}');
$productTable->type->range('normal{6},branch{4}');
$productTable->status->range('normal{10}');
$productTable->deleted->range('0{10}');
$productTable->shadow->range('0{10}');
$productTable->gen(10);

$branchTable = zenData('branch');
$branchTable->id->range('1-8');
$branchTable->product->range('7{2},8{2},9{2},10{2}');
$branchTable->name->range('v1.0{2},v2.0{2},master{2},develop{2}');
$branchTable->status->range('active{8}');
$branchTable->deleted->range('0{8}');
$branchTable->gen(8);

$moduleTable = zenData('module');
$moduleTable->id->range('1-15');
$moduleTable->root->range('1{3},2{3},3{3},7{3},8{3}');
$moduleTable->name->range('用户管理{3},系统设置{3},产品管理{3},项目管理{3},测试管理{3}');
$moduleTable->type->range('story{15}');
$moduleTable->deleted->range('0{15}');
$moduleTable->gen(15);

$planTable = zenData('productplan');
$planTable->id->range('1-12');
$planTable->product->range('1{3},2{3},3{3},7{3}');
$planTable->title->range('V1.0版本计划{3},V2.0版本计划{3},迭代计划1{3},迭代计划2{3}');
$planTable->status->range('wait{6},doing{6}');
$planTable->deleted->range('0{12}');
$planTable->gen(12);

$product = new productTest('admin');

r($product->buildSearchConfigTest(1, 'story')) && p('fields:branch') && e('~~'); // 测试普通产品不包含branch字段
r($product->buildSearchConfigTest(7, 'story')) && p('fields:branch') && e('*'); // 测试多分支产品包含branch字段
r($product->buildSearchConfigTest(1, 'requirement')) && p('fields:title') && e('*'); // 测试requirement类型标题字段
r($product->buildSearchConfigTest(8, 'epic')) && p('params:module:values') && e('*'); // 测试epic类型模块参数
r($product->buildSearchConfigTest(0, 'story')) && p('params:plan:values') && e('array'); // 测试无效产品ID返回配置