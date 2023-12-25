#!/usr/bin/env php
<?php

/**

title=productModel->getOrderedProducts();
cid=0

- 测试获取状态为all的产品数量 @30
- 测试获取状态为normal的产品数量 @20
- 测试获取状态为closed的产品数量 @10
- 测试获取状态为all的5条产品 @5
- 测试获取状态为all的10条产品 @10
- 测试获取状态为normal的10产品 @10
- 测试获取状态为normal的25条产品 @20
- 测试获取状态为closed的5条产品 @5
- 测试获取状态为closed的15条产品 @10
- 测试获取状态为all的关联项目1的产品 @6
- 测试获取状态为normal的关联项目2的产品 @6
- 测试获取状态为closed的关联项目3的产品 @6
- 测试获取状态为closed的管线不存在的项目的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';
su('admin');

zdTable('projectproduct')->config('projectproduct')->gen(30);
zdTable('product')->config('product')->gen(30);

$product = new productTest('admin');

r($product->getOrderedProductsTest('all'))    && p() && e('30'); // 测试获取状态为all的产品数量
r($product->getOrderedProductsTest('normal')) && p() && e('20'); // 测试获取状态为normal的产品数量
r($product->getOrderedProductsTest('closed')) && p() && e('10'); // 测试获取状态为closed的产品数量

r($product->getOrderedProductsTest('all', 5))     && p() && e('5');  // 测试获取状态为all的5条产品
r($product->getOrderedProductsTest('all', 10))    && p() && e('10'); // 测试获取状态为all的10条产品
r($product->getOrderedProductsTest('normal', 10)) && p() && e('10'); // 测试获取状态为normal的10产品
r($product->getOrderedProductsTest('normal', 25)) && p() && e('20'); // 测试获取状态为normal的25条产品
r($product->getOrderedProductsTest('closed', 5))  && p() && e('5');  // 测试获取状态为closed的5条产品
r($product->getOrderedProductsTest('closed', 15)) && p() && e('10'); // 测试获取状态为closed的15条产品

r($product->getOrderedProductsTest('all', 0, 1))    && p() && e('6'); // 测试获取状态为all的关联项目1的产品
r($product->getOrderedProductsTest('normal', 0, 2)) && p() && e('6'); // 测试获取状态为normal的关联项目2的产品
r($product->getOrderedProductsTest('closed', 0, 3)) && p() && e('6'); // 测试获取状态为closed的关联项目3的产品
r($product->getOrderedProductsTest('closed', 0, 6)) && p() && e('0'); // 测试获取状态为closed的管线不存在的项目的产品
