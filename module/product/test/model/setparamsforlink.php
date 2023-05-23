#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试productModel->setParamsForLink();
timeout=0
cid=1

*/

global $tester;
$productModel = $tester->loadModel('product');

$productLink = '/product-browse-%s.html';
$planLink    = '/programplan-browse-%s-%s.html';

r($productModel->setParamsForLink('',        '', 0, 0)) && p() && e('0'); //不传入任何数据。
r($productModel->setParamsForLink('product', '', 0, 0)) && p() && e('0'); //只传入module参数。

r($productModel->setParamsForLink('product',     $productLink, 2, 1)) && p() && e('/product-browse-1.html');       //传入module=product，检查链接。
r($productModel->setParamsForLink('programplan', $planLink,    2, 1)) && p() && e('/programplan-browse-2-1.html'); //传入module=programplan，检查链接。
