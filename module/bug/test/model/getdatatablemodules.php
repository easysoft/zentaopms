#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $product = zenData('product');
    $product->id->range('1-10');
    $product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
    $product->type->range('normal{5},branch{3},platform{2}');
    $product->status->range('normal');
    $product->deleted->range('0');
    $product->gen(10);

    $branch = zenData('branch');
    $branch->id->range('1-15');
    $branch->product->range('6{5},7{5},8{5}');
    $branch->name->range('主干,分支1,分支2,分支3,分支4');
    $branch->status->range('active');
    $branch->deleted->range('0');
    $branch->gen(15);

    $module = zenData('module');
    $module->id->range('1-50');
    $module->product->range('1-10:5');
    $module->branch->range('0{25},[1-5]{5},[6-10]{5},[11-15]{15}');
    $module->name->range('模块1,模块2,模块3,模块4,模块5');
    $module->type->range('bug');
    $module->parent->range('0');
    $module->deleted->range('0');
    $module->gen(50);
}

/**

title=测试 bugModel::getDatatableModules;
timeout=0
cid=0

- 测试无分支产品 @5
- 测试多分支产品 @30
- 测试无效产品ID @1
- 测试产品ID为0 @1
- 测试另一个多分支产品 @30

*/

global $tester;
$tester->loadModel('bug');

initData();

r($tester->bug->getDatatableModules(1)) && p() && e('5'); // 测试无分支产品
r($tester->bug->getDatatableModules(6)) && p() && e('30'); // 测试多分支产品
r($tester->bug->getDatatableModules(999)) && p() && e('1'); // 测试无效产品ID
r($tester->bug->getDatatableModules(0)) && p() && e('1'); // 测试产品ID为0
r($tester->bug->getDatatableModules(7)) && p() && e('30'); // 测试另一个多分支产品