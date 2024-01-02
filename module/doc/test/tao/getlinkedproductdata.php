#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLinkedProductData();
cid=1

- 测试空数据 @SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'
- 获取productID=1的需求查询SQL @SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'
- 获取productID=1的需求查询SQL属性1 @SELECT id FROM `zt_productplan` WHERE `product`  = '1' AND  `deleted`  = '0'
- 获取productID=2的需求查询SQL @SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'
- 获取productID=2的需求查询SQL属性1 @SELECT id FROM `zt_productplan` WHERE `product`  = '2' AND  `deleted`  = '0'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$storyTable = zdTable('story');
$storyTable->product->range('1-5');
$storyTable->gen(20);

$planTable = zdTable('productplan');
$planTable->product->range('1-5');
$planTable->gen(20);

$releaseTable = zdTable('release');
$releaseTable->product->range('1-5');
$releaseTable->gen(20);

$caseTable = zdTable('case');
$caseTable->product->range('1-5');
$caseTable->gen(20);

zdTable('product')->config('product')->gen(5);
zdTable('user')->gen(5);
su('admin');

$products = array(0 ,1, 2);

$docTester = new docTest();
r($docTester->getLinkedProductDataTest($products[0])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'");       // 测试空数据
r($docTester->getLinkedProductDataTest($products[1])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'");       // 获取productID=1的需求查询SQL
r($docTester->getLinkedProductDataTest($products[1])) && p('1') && e("SELECT id FROM `zt_productplan` WHERE `product`  = '1' AND  `deleted`  = '0'"); // 获取productID=1的需求查询SQL
r($docTester->getLinkedProductDataTest($products[2])) && p('0') && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'");       // 获取productID=2的需求查询SQL
r($docTester->getLinkedProductDataTest($products[2])) && p('1') && e("SELECT id FROM `zt_productplan` WHERE `product`  = '2' AND  `deleted`  = '0'"); // 获取productID=2的需求查询SQL
