#!/usr/bin/env php
<?php

/**

title=测试productModel->formatDataForList();
cid=0

- 正常产品
 - 属性name @正常产品1
 - 属性type @product
- 错误的产品 @0
- 不存在的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(3);

$product = new productTest();

$idList = array(1, 0, 5);

r($product->formatDataForListTest($idList[0])) && p('name,type') && e('正常产品1,product'); // 正常产品
r($product->formatDataForListTest($idList[1])) && p() && e('0');                            // 错误的产品
r($product->formatDataForListTest($idList[2])) && p() && e('0');                            // 不存在的产品
