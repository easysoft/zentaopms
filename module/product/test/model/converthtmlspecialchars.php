#!/usr/bin/env php
<?php

/**

title=测试productModel->convertHtmlSpecialChars();
timeout=0
cid=17480

- 不传入任何数据。 @0
- 检查转换后的产品名称。 @a's product
- 检查转换后的项目名称。 @"<a>b</a>" project
- 检查转换后的项目名称。 @"<span class='public'>b</a>" project
- 检查转换后的项目名称。 @"<div class='private'>b</div>" project
- 检查转换后的计划名称。 @a's plan

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$productModel = $tester->loadModel('product');
r($productModel->convertHtmlSpecialChars(array())) && p() && e('0'); //不传入任何数据。

$data['productList']['1'] = new stdclass();
$data['productList']['1']->id = 1;
$data['productList']['1']->name = htmlspecialchars("a's product");
$data['projectList']['2'] = new stdclass();
$data['projectList']['2']->id = 2;
$data['projectList']['2']->name = htmlspecialchars('"<a>b</a>" project');
$data['projectList']['3'] = new stdclass();
$data['projectList']['3']->id = 3;
$data['projectList']['3']->name = htmlspecialchars('"<span class=\'public\'>b</a>" project');
$data['projectList']['4'] = new stdclass();
$data['projectList']['4']->id = 4;
$data['projectList']['4']->name = htmlspecialchars('"<div class=\'private\'>b</div>" project');
$data['planList'][1][1] = new stdclass();
$data['planList'][1][1]->id = 1;
$data['planList'][1][1]->title = htmlspecialchars("a's plan");

$convertedData = $productModel->convertHtmlSpecialChars($data);
r($convertedData['productList'][1]->name)  && p() && e("a's product");        //检查转换后的产品名称。
r($convertedData['projectList'][2]->name)  && p() && e('"<a>b</a>" project'); //检查转换后的项目名称。
r($convertedData['projectList'][3]->name)  && p() && e(`"<span class='public'>b</a>" project`); //检查转换后的项目名称。
r($convertedData['projectList'][4]->name)  && p() && e(`"<div class='private'>b</div>" project`); //检查转换后的项目名称。
r($convertedData['planList'][1][1]->title) && p() && e("a's plan");           //检查转换后的计划名称。
