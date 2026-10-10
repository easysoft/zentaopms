#!/usr/bin/env php
<?php

/**

title=测试 productModel::deleteSystemsByProduct();
timeout=0
cid=0

- 步骤1：删除关联系统返回true @1
- 步骤2：关联系统被标记删除 @1
- 步骤3：其他应用的系统不受影响 @0
- 步骤4：没有关联系统时仍返回true @1
- 步骤5：重复删除仍返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitfoxext.class.php';

su('admin');

zenData('product')->gen(0);
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('应用A,应用B,应用C');
$product->code->range('appa,appb,appc');
$product->status->range('normal{3}');
$product->deleted->range('0{3}');
$product->gen(3);

zenData('system')->gen(0);
$system = zenData('system');
$system->id->range('1-3');
$system->name->range('应用A系统,应用A系统2,应用B系统');
$system->product->range('1,1,2');
$system->createdBy->range('admin{3}');
$system->createdDate->range('[2026-01-01 00:00:00]');
$system->deleted->range('0{3}');
$system->gen(3);

$productTest = new productGitfoxExtModelTest();

r($productTest->deleteSystemsByProductTest(1))     && p() && e('1');         // 步骤1：删除关联系统返回true
r($productTest->systemCountTest(1, 1))             && p() && e('2');         // 步骤2：关联系统被标记删除
r($productTest->systemCountTest(2, 1))             && p() && e('0');         // 步骤3：其他应用的系统不受影响
r($productTest->deleteSystemsByProductTest(3))     && p() && e('1');         // 步骤4：没有关联系统时仍返回true
r($productTest->deleteSystemsByProductTest(1))     && p() && e('1');         // 步骤5：重复删除仍返回true
