#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/createbranch.ui.class.php';

$product = zenData('product');
$product->id->range('11');
$product->name->range('多分支产品');
$product->status->range('normal');
$product->type->range('branch');
$product->gen(1);

$tester = new createBranchTester();
$tester->login();

$productID['productID'] = 11;
$branch = new stdClass();

$branch->name = '';
r($tester->createBranch($branch, $productID)) && p('message,status') && e('创建分支提示信息正确,SUCCESS');

$branch->name = '分支01';
$branch->desc = '分支描述01';
r($tester->createBranch($branch, $productID)) && p('message,status') && e('创建分支成功,SUCCESS');

$branch->name = '分支01';
r($tester->createBranch($branch, $productID)) && p('message,status') && e('创建分支提示信息正确,SUCCESS');

$tester->closeBrowser();
