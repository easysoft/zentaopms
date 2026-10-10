#!/usr/bin/env php
<?php

/**

title=测试 productModel::updateSystemsByProduct();
timeout=0
cid=0

- 步骤1：没有关联系统时返回true @1
- 步骤2：有关联系统时返回true @1
- 步骤3：系统名称同步更新 @应用A改名
- 步骤4：系统更新人记录为当前账号 @admin
- 步骤5：已删除的系统不参与同步 @应用A旧系统
- 步骤6：其他应用的系统不参与同步 @应用C系统

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

/* 1号系统关联应用A，2号系统关联应用A但已删除，3号系统关联应用C。*/
zenData('system')->gen(0);
$system = zenData('system');
$system->id->range('1-3');
$system->name->range('应用A旧系统,应用A已删除系统,应用C系统');
$system->product->range('1,1,3');
$system->createdBy->range('admin{3}');
$system->createdDate->range('[2026-01-01 00:00:00]');
$system->deleted->range('0,1,0');
$system->gen(3);

$productTest = new productGitfoxExtModelTest();

r($productTest->updateSystemsByProductTest(2, '应用B改名'))   && p() && e('1');              // 步骤1：没有关联系统时返回true
r($productTest->updateSystemsByProductTest(1, '应用A改名'))   && p() && e('1');              // 步骤2：有关联系统时返回true
r($productTest->systemFieldTest(1, 'name'))                   && p() && e('应用A改名');        // 步骤3：系统名称同步更新
r($productTest->systemFieldTest(1, 'editedBy'))               && p() && e('admin');           // 步骤4：系统更新人记录为当前账号
r($productTest->systemFieldTest(2, 'name'))                   && p() && e('应用A已删除系统');  // 步骤5：已删除的系统不参与同步
r($productTest->systemFieldTest(3, 'name'))                   && p() && e('应用C系统');        // 步骤6：其他应用的系统不参与同步
