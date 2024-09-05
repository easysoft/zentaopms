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

$product1 = new stdClass();
$product1->name = '产品名称2024';
r($tester->batchEditProduct($product1)) && p('message,status') && e('产品名称修改成功,SUCCESS');//批量修改产品名称

$product2 = new stdClass();
$product2->type = '多分支';
r($tester->batchEditProduct($product2)) && p('message,status') && e('产品类型修改成功,SUCCESS');//批量修改产品类型

$product3 = new stdClass();
$product3->status = '结束';
r($tester->batchEditProduct($product3)) && p('message,status') && e('产品状态修改成功,SUCCESS');//批量修改产品状态为结束

$product4 = new stdClass();
$product4->acl = 'private';
r($tester->batchEditProduct($product4)) && p('message,status') && e('产品访问控制修改为私有成功,SUCCESS');//批量修改产品访问控制为私有

$tester->closeBrowser();
