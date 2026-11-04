#!/usr/bin/env php
<?php

/**

title=测试 productModel::deleteProductWithSystems();
timeout=0
cid=0

- 步骤1：删除应用及其关联系统返回true @1
- 步骤2：应用被标记删除 @1
- 步骤3：关联系统被标记删除 @1
- 步骤4：不存在的应用删除仍返回true @1
- 步骤5：其他应用不受影响 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitfoxext.class.php';

su('admin');

zenData('product')->gen(0);
$product = zenData('product');
$product->id->range('1-2');
$product->name->range('应用A,应用B');
$product->code->range('appa,appb');
$product->status->range('normal{2}');
$product->deleted->range('0{2}');
$product->gen(2);

zenData('system')->gen(0);
$system = zenData('system');
$system->id->range('1-2');
$system->name->range('应用A系统,应用B系统');
$system->product->range('1,2');
$system->createdBy->range('admin{2}');
$system->createdDate->range('[2026-01-01 00:00:00]');
$system->deleted->range('0{2}');
$system->gen(2);

$productTest = new productGitfoxExtModelTest();

r($productTest->deleteProductWithSystemsTest(1))   && p() && e('1');            // 步骤1：删除应用及其关联系统返回true
r($productTest->productFieldTest(1, 'deleted'))    && p() && e('1');            // 步骤2：应用被标记删除
r($productTest->systemFieldTest(1, 'deleted'))     && p() && e('1');            // 步骤3：关联系统被标记删除
r($productTest->deleteProductWithSystemsTest(9999)) && p() && e('1');           // 步骤4：不存在的应用删除仍返回true
r($productTest->productFieldTest(2, 'deleted'))    && p() && e('0');            // 步骤5：其他应用不受影响
