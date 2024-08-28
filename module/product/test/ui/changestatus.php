#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/changestatus.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$tester = new changeStatus();
$tester->login();

$productID['productID'] = 1;
r($tester->closeProduct($productID))    && p('message,status') && e('关闭产品成功,SUCCESS');
r($tester->activateProduct($productID)) && p('message,status') && e('激活产品成功,SUCCESS');
r($tester->deleteProduct($productID))   && p('message,status') && e('删除产品成功,SUCCESS');

$tester->closeBrowser();
