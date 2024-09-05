#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/batcheditproduct.ui.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(5);

$tester = new batchEditProduct();
$tester->login();

