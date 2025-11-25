#!/usr/bin/env php
<?php

/**

title=productTao->getStatCountByID();
cid=17555

- 获取产品1的计划数 @5
- 获取产品1的用例数 @4
- 获取产品1的bug数 @3
- 获取产品1的文档数 @1
- 获取产品1的发布数 @10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('productplan')->loadYaml('productplan')->gen(100);
zenData('case')->loadYaml('case')->gen(100);
zenData('bug')->loadYaml('bug')->gen(100);
zenData('doc')->gen(100);
zenData('build')->gen(100);

$product = new productTest('admin');

r($product->objectModel->getStatCountByID(TABLE_PRODUCTPLAN, 1)) && p('') && e('5');  //获取产品1的计划数
r($product->objectModel->getStatCountByID(TABLE_CASE, 1))        && p('') && e('4');  //获取产品1的用例数
r($product->objectModel->getStatCountByID(TABLE_BUG, 1))         && p('') && e('3');  //获取产品1的bug数
r($product->objectModel->getStatCountByID(TABLE_DOC, 1))         && p('') && e('1');  //获取产品1的文档数
r($product->objectModel->getStatCountByID(TABLE_BUILD, 1))       && p('') && e('10'); //获取产品1的发布数
