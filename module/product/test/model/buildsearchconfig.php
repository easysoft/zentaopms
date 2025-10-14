#!/usr/bin/env php
<?php

/**

title=测试 productModel::buildSearchConfig();
timeout=0
cid=0

- 执行product模块的buildSearchConfigTest方法，参数是1, 'story' 第fields条的branch属性 @~~
- 执行product模块的buildSearchConfigTest方法，参数是7, 'story' 第fields条的branch属性 @所属分支
- 执行product模块的buildSearchConfigTest方法，参数是1, 'requirement' 第fields条的title属性 @名称
- 执行product模块的buildSearchConfigTest方法，参数是7, 'epic' 第params条的module:values属性 @array
- 执行product模块的buildSearchConfigTest方法，参数是0, 'story' 第params条的plan:values属性 @array
- 执行product模块的buildSearchConfigTest方法，参数是1, 'story' 第params条的plan:values属性 @array
- 执行product模块的buildSearchConfigTest方法，参数是8, 'story' 第params条的branch:values属性 @array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

global $lang, $config;
$lang->SRCommon = '软件需求';
$lang->URCommon = '用户需求';
$lang->ERCommon = '业务需求';
$config->productCommon = '产品';

$productTable = zenData('product');
$productTable->id->range('1,7,8');
$productTable->name->range('正常产品1,多分支产品1,多分支产品2');
$productTable->type->range('normal,branch,branch');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->shadow->range('0');
$productTable->gen(3);

$branchTable = zenData('branch');
$branchTable->id->range('1,2');
$branchTable->product->range('7,8');
$branchTable->name->range('v1.0,master');
$branchTable->status->range('active');
$branchTable->deleted->range('0');
$branchTable->gen(2);

$moduleTable = zenData('module');
$moduleTable->id->range('1,2');
$moduleTable->root->range('1,7');
$moduleTable->name->range('用户管理,产品管理');
$moduleTable->type->range('story');
$moduleTable->deleted->range('0');
$moduleTable->gen(2);

$planTable = zenData('productplan');
$planTable->id->range('1,2');
$planTable->product->range('1,7');
$planTable->title->range('V1.0版本计划,迭代计划1');
$planTable->status->range('wait,doing');
$planTable->deleted->range('0');
$planTable->gen(2);

su('admin');
$product = new productTest('admin');

r($product->buildSearchConfigTest(1, 'story')) && p('fields:branch') && e('~~');
r($product->buildSearchConfigTest(7, 'story')) && p('fields:branch') && e('所属分支');
r($product->buildSearchConfigTest(1, 'requirement')) && p('fields:title') && e('名称');
r($product->buildSearchConfigTest(7, 'epic')) && p('params:module:values') && e('array');
r($product->buildSearchConfigTest(0, 'story')) && p('params:plan:values') && e('array');
r($product->buildSearchConfigTest(1, 'story')) && p('params:plan:values') && e('array');
r($product->buildSearchConfigTest(8, 'story')) && p('params:branch:values') && e('array');