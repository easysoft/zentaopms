#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkModules();
timeout=0
cid=0

- 测试步骤1:空产品数组 @0
- 测试步骤2:单个产品story类型 @2
- 测试步骤3:单个产品task类型 @2
- 测试步骤4:单个产品bug类型 @2
- 测试步骤5:多个产品混合类型 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('product')->gen(0);
zendata('module')->gen(0);

su('admin');

$repoTest = new repoZenTest();

$emptyProducts = array();

$singleProduct = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = 'Product One';
$singleProduct[1] = $product1;

$multiProducts = array();
$product2 = new stdClass();
$product2->id = 1;
$product2->name = 'Product One';
$multiProducts[1] = $product2;

$product3 = new stdClass();
$product3->id = 2;
$product3->name = 'Product Two';
$multiProducts[2] = $product3;

r($repoTest->getLinkModulesTest($emptyProducts, 'story')) && p() && e('0'); // 测试步骤1:空产品数组
r(count($repoTest->getLinkModulesTest($singleProduct, 'story'))) && p() && e('2'); // 测试步骤2:单个产品story类型
r(count($repoTest->getLinkModulesTest($singleProduct, 'task'))) && p() && e('2'); // 测试步骤3:单个产品task类型
r(count($repoTest->getLinkModulesTest($singleProduct, 'bug'))) && p() && e('2'); // 测试步骤4:单个产品bug类型
r(count($repoTest->getLinkModulesTest($multiProducts, 'story'))) && p() && e('4'); // 测试步骤5:多个产品混合类型