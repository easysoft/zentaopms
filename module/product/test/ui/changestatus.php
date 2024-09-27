#!/usr/bin/env php
<?php

/**

title=关闭/激活/删除产品
timeout=0
cid=0

*/
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
r($tester->closeProduct($productID))    && p('message,status') && e('关闭产品成功,SUCCESS');//关闭产品
r($tester->activateProduct($productID)) && p('message,status') && e('激活产品成功,SUCCESS');//激活产品
r($tester->deleteProduct($productID))   && p('message,status') && e('删除产品成功,SUCCESS');//删除产品

$tester->closeBrowser();
