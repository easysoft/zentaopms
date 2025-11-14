#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkExecutions();
timeout=0
cid=18141

- 测试步骤1:空产品数组 @0
- 测试步骤2:单个产品属性exec_1 @执行_1
- 测试步骤3:多个产品 @3
- 测试步骤4:包含无效产品对象 @1
- 测试步骤5:产品ID正确性验证属性exec_5 @执行_5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('product')->gen(0);
zendata('execution')->gen(0);

su('admin');

$repoTest = new repoZenTest();

$emptyProducts = array();

$singleProduct = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = 'Product 1';
$product1->type = 'normal';
$singleProduct[1] = $product1;

$multipleProducts = array();
$product2 = new stdClass();
$product2->id = 1;
$product2->name = 'Product 1';
$product2->type = 'normal';
$multipleProducts[1] = $product2;

$product3 = new stdClass();
$product3->id = 2;
$product3->name = 'Product 2';
$product3->type = 'branch';
$multipleProducts[2] = $product3;

$product4 = new stdClass();
$product4->id = 3;
$product4->name = 'Product 3';
$product4->type = 'platform';
$multipleProducts[3] = $product4;

$invalidProducts = array();
$product5 = new stdClass();
$product5->id = 1;
$product5->name = 'Valid Product';
$product5->type = 'normal';
$invalidProducts[1] = $product5;
$invalidProducts[2] = null;
$invalidProducts[3] = '';

$verifyProducts = array();
$product6 = new stdClass();
$product6->id = 5;
$product6->name = 'Product 5';
$product6->type = 'normal';
$verifyProducts[5] = $product6;

r($repoTest->getLinkExecutionsTest($emptyProducts)) && p() && e('0'); // 测试步骤1:空产品数组
r($repoTest->getLinkExecutionsTest($singleProduct)) && p('exec_1') && e('执行_1'); // 测试步骤2:单个产品
r(count($repoTest->getLinkExecutionsTest($multipleProducts))) && p() && e('3'); // 测试步骤3:多个产品
r(count($repoTest->getLinkExecutionsTest($invalidProducts))) && p() && e('1'); // 测试步骤4:包含无效产品对象
r($repoTest->getLinkExecutionsTest($verifyProducts)) && p('exec_5') && e('执行_5'); // 测试步骤5:产品ID正确性验证