#!/usr/bin/env php
<?php

/**

title=productModel->getProductsGroupByProgram();
timeout=0
cid=17504

- 检查获取项目集条目数。 @10
- 检查项目集 0 的产品名称。 @/正常产品1
- 检查项目集 1 的产品名称。 @项目集1/正常产品2
- 检查项目集 2 的产品名称。 @项目集2/正常产品3
- 检查项目集 3 的产品名称。 @项目集3/正常产品4
- 检查项目集 4 的产品名称。 @项目集4/正常产品5
- 检查项目集 5 的产品名称。 @项目集5/正常产品6
- 检查项目集 6 的产品名称。 @项目集6/正常产品7
- 检查项目集 7 的产品名称。 @项目集7/正常产品8
- 检查项目集 8 的产品名称。 @项目集8/正常产品9
- 检查项目集 9 的产品名称。 @项目集9/正常产品10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->gen(10);
$project = zenData('project');
$project->id->range('1-10');
$project->type->range('program');
$project->gen(10);

global $tester;
$product = $tester->loadModel('product');

$productGroup = $product->getProductsGroupByProgram();
r(count($productGroup)) && p() && e('10');                 //检查获取项目集条目数。
r($productGroup[0][1])  && p() && e('/正常产品1');         //检查项目集 0 的产品名称。
r($productGroup[1][2])  && p() && e('项目集1/正常产品2');  //检查项目集 1 的产品名称。
r($productGroup[2][3])  && p() && e('项目集2/正常产品3');  //检查项目集 2 的产品名称。
r($productGroup[3][4])  && p() && e('项目集3/正常产品4');  //检查项目集 3 的产品名称。
r($productGroup[4][5])  && p() && e('项目集4/正常产品5');  //检查项目集 4 的产品名称。
r($productGroup[5][6])  && p() && e('项目集5/正常产品6');  //检查项目集 5 的产品名称。
r($productGroup[6][7])  && p() && e('项目集6/正常产品7');  //检查项目集 6 的产品名称。
r($productGroup[7][8])  && p() && e('项目集7/正常产品8');  //检查项目集 7 的产品名称。
r($productGroup[8][9])  && p() && e('项目集8/正常产品9');  //检查项目集 8 的产品名称。
r($productGroup[9][10]) && p() && e('项目集9/正常产品10'); //检查项目集 9 的产品名称。
