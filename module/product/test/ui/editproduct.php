#!/usr/bin/env php
<?php

/**

title=编辑产品
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/editproduct.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$tester = new editProductTester();
$tester->login();

$productID['productID'] = 1;
$product = new stdClass();
$product->name = '';
r($tester->editProduct($productID, $product)) && p('message,status') && e('编辑产品表单提示信息正确,SUCCESS');//产品名称必填校验

$product->name = '产品1_编辑';
r($tester->editProduct($productID, $product)) && p('message,status') && e('产品编辑成功,SUCCESS');//编辑产品

$tester->closeBrowser();
