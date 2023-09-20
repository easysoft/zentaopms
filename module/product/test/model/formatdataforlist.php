#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(3);

/**
title=测试productModel->formatDataForList();
cid=1
pid=1

*/

$product = new productTest();

$idList = array(1, 0, 5);

r($product->formatDataForListTest($idList[0])) && p('name,type') && e('正常产品1,product'); // 正常产品
r($product->formatDataForListTest($idList[1])) && p() && e('0');                            // 错误的产品
r($product->formatDataForListTest($idList[2])) && p() && e('0');                            // 不存在的产品
