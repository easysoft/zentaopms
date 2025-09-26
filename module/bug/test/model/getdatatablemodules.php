#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDatatableModules();
timeout=0
cid=0

- 测试正常产品获取模块 @array
- 测试分支产品获取模块 @array
- 测试无效产品ID @array
- 测试产品ID为0 @array
- 测试方法返回值类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1,产品2');
$product->type->range('normal,branch');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(2);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('2{2}');
$branch->name->range('主干,分支1');
$branch->status->range('active');
$branch->deleted->range('0');
$branch->gen(2);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1{2},2{3}');
$module->branch->range('0{2},[1-2]{3}');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->type->range('bug');
$module->parent->range('0');
$module->deleted->range('0');
$module->gen(5);

$bugTest = new bugTest();

r($bugTest->getDatatableModulesTest(1)) && p() && e('array'); // 测试正常产品获取模块
r($bugTest->getDatatableModulesTest(2)) && p() && e('array'); // 测试分支产品获取模块
r($bugTest->getDatatableModulesTest(999)) && p() && e('array'); // 测试无效产品ID
r($bugTest->getDatatableModulesTest(0)) && p() && e('array'); // 测试产品ID为0
r(is_array($bugTest->getDatatableModulesTest(1))) && p() && e('1'); // 测试方法返回值类型