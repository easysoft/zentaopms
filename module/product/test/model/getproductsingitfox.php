#!/usr/bin/env php
<?php

/**

title=测试 productModel::getProductsInGitfox();
timeout=0
cid=0

- 步骤1：status为noclosed时只返回未关闭的应用 @1
- 步骤2：status为closed时只返回已关闭的应用 @1
- 步骤3：status为all时返回所有未删除的应用 @2
- 步骤4：按关键词过滤应用名称 @1
- 步骤5：关键词不存在时返回空数组 @0
- 步骤6：返回的应用名称正确 @应用B
- 步骤7：已删除和影子应用不返回 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitfoxext.class.php';

su('admin');

zenData('product')->gen(0);
$product = zenData('product');
$product->id->range('1-4');
$product->name->range('应用A,应用B,应用C,应用D');
$product->code->range('appa,appb,appc,appd');
$product->shadow->range('0,0,0,1');
$product->status->range('normal,closed,normal,normal');
$product->deleted->range('0,0,1,0');
$product->gen(4);

$productTest = new productGitfoxExtModelTest();

r($productTest->getProductsInGitfoxCountTest('noclosed')) && p() && e('1');      // 步骤1：status为noclosed时只返回未关闭的应用
r($productTest->getProductsInGitfoxCountTest('closed'))   && p() && e('1');      // 步骤2：status为closed时只返回已关闭的应用
r($productTest->getProductsInGitfoxCountTest('all'))      && p() && e('2');      // 步骤3：status为all时返回所有未删除的应用
r($productTest->getProductsInGitfoxCountTest('all', '应用B')) && p() && e('1');  // 步骤4：按关键词过滤应用名称
r($productTest->getProductsInGitfoxCountTest('all', '应用E')) && p() && e('0');  // 步骤5：关键词不存在时返回空数组
r($productTest->getProductsInGitfoxFirstNameTest('closed')) && p() && e('应用B'); // 步骤6：返回的应用名称正确
r($productTest->getProductsInGitfoxCountTest('all', '应用C')) && p() && e('0');  // 步骤7：已删除和影子应用不返回
