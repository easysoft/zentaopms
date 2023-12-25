#!/usr/bin/env php
<?php

/**

title=测试productModel->convertHtmlSpecialChars();
cid=0

- 不传入任何数据。 @0
- 检查转换后的产品名称。 @a's product
- 检查转换后的项目名称。 @"<a>b</a>" project
- 检查转换后的计划名称。 @a's plan

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$productModel = $tester->loadModel('product');

$productLink = '/product-browse-%s.html';
$planLink    = '/programplan-browse-%s-%s.html';

r($productModel->convertHtmlSpecialChars(array())) && p() && e('0'); //不传入任何数据。

$data['productList']['15'] = new stdclass();
$data['productList']['15']->id = 15;
$data['productList']['15']->name = htmlspecialchars ("a's product");
$data['projectList']['23'] = new stdclass();
$data['projectList']['23']->id = 23;
$data['projectList']['23']->name = htmlspecialchars ('"<a>b</a>" project');
$data['planList'][15][30] = new stdclass();
$data['planList'][15][30]->id = 30;
$data['planList'][15][30]->title = htmlspecialchars ("a's plan");

$convertedData = $productModel->convertHtmlSpecialChars($data);
r($convertedData['productList'][15]->name)   && p() && e("a's product");        //检查转换后的产品名称。
r($convertedData['projectList'][23]->name)   && p() && e('"<a>b</a>" project'); //检查转换后的项目名称。
r($convertedData['planList'][15][30]->title) && p() && e("a's plan");           //检查转换后的计划名称。
