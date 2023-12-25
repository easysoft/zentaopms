#!/usr/bin/env php
<?php

/**

title=productModel->getProductsGroupByProgram();
cid=0

- 检查获取项目集条目数。 @10
- 检查项目集 0 的产品名称。 @/正常产品1
- 检查项目集 5 的产品名称。 @项目集5/正常产品6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);
$project = zdTable('project');
$project->id->range('1-10');
$project->type->range('program');
$project->gen(10);

global $tester;
$product = $tester->loadModel('product');

$productGroup = $product->getProductsGroupByProgram();
r(count($productGroup)) && p() && e('10');                 //检查获取项目集条目数。
r($productGroup[0][1])  && p() && e('/正常产品1');         //检查项目集 0 的产品名称。
r($productGroup[5][6])  && p() && e('项目集5/正常产品6');  //检查项目集 5 的产品名称。
