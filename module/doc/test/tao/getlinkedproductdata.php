#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLinkedProductData();
timeout=0
cid=16172

- 测试空数据 @SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'
- 获取productID=1的需求查询SQL @SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'
- 获取productID=1的需求查询SQL属性3 @SELECT id FROM `zt_productplan` WHERE `product`  = '1' AND  `deleted`  = '0'
- 获取productID=2的需求查询SQL @SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'
- 获取productID=2的需求查询SQL属性3 @SELECT id FROM `zt_productplan` WHERE `product`  = '2' AND  `deleted`  = '0'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$storyTable = zenData('story');
$storyTable->product->range('1-5');
$storyTable->gen(20);

$planTable = zenData('productplan');
$planTable->product->range('1-5');
$planTable->gen(20);

$releaseTable = zenData('release');
$releaseTable->product->range('1-5');
$releaseTable->gen(20);

$caseTable = zenData('case');
$caseTable->product->range('1-5');
$caseTable->gen(20);

zenData('product')->loadYaml('product')->gen(5);
zenData('user')->gen(5);
su('admin');

$products = array(0 ,1, 2);

$docTester = new docTest();
r($docTester->getLinkedProductDataTest($products[0])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'");       // 测试空数据
r($docTester->getLinkedProductDataTest($products[1])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'");       // 获取productID=1的需求查询SQL
r($docTester->getLinkedProductDataTest($products[1])) && p('3') && e("SELECT id FROM `zt_productplan` WHERE `product`  = '1' AND  `deleted`  = '0'"); // 获取productID=1的需求查询SQL
r($docTester->getLinkedProductDataTest($products[2])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'");       // 获取productID=2的需求查询SQL
r($docTester->getLinkedProductDataTest($products[2])) && p('3') && e("SELECT id FROM `zt_productplan` WHERE `product`  = '2' AND  `deleted`  = '0'"); // 获取productID=2的需求查询SQL