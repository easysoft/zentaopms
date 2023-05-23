#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(1);
zdTable('module')->gen(1);

/**
title=测试创建产品 productModel->create();
cid=1
pid=1
*/

$product = new productTest('admin');

$create       = array('name' => 'product1', 'code' => 'productcode1');
$repeat       = array('name' => 'product1', 'code' => 'productcode1');
$withCode     = array('name' => 'product3', 'code' => 'productcode3');
$withProgram  = array('program' => '3', 'name' => 'product4', 'code' => 'productcode4');
$withStatus   = array('program' => '4', 'name' => 'product5', 'code' => 'productcode5', 'type' => 'branch', 'status' => 'closed');

r($product->createObject($create))                && p('name')                && e('product1');                // 测试正常的创建
r($product->createObject($withCode, 'test line')) && p('name,code,line')      && e('product3,productcode3,2'); // 测试传入name和code
r($product->createObject($withProgram))           && p('program,name,code')   && e('3,product4,productcode4'); // 测试传入program、name、code
r($product->createObject($withStatus))            && p('program,type,status') && e('4,branch,closed');         // 测试传入program、name、code、type、status

r($product->createObject($repeat))                && p('code:0')              && e('『产品代号』已经有『productcode1』这条记录了。'); // 测试创建重复的产品
