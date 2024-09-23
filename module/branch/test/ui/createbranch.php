#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/createbranch.ui.class.php';

$product = zenData('product');
$product->id->range('12');
$product->name->range('多分支产品');
$product->status->range('normal');
$product->type->range('branch');
$product->gen(1);

$tester = new createBranchTester();
$tester->login();

$productID['productID'] = 11;
$branch = new stdClass();
$branch->name = '';

$tester->closeBrowser();
