#!/usr/bin/env php
<?php

/**

title=productTao->deleteByID();
timeout=0
cid=17482

- 删除前查询ID为3的产品是否被删除 @0
- 删除前查询产品ID为3的产品库是否被删除 @1
- 删除前查询产品与项目的关联关系 @1
- 删除后查询ID为3的产品是否被删除 @1
- 删除后查询产品ID为3的产品库是否被删除 @1
- 删除后查询产品与项目的关联关系 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(10);
zenData('projectproduct')->gen(10);
zenData('doclib')->gen(10);

$product = new productTest('admin');

$products = $product->objectModel->getByIdList(array(1,2,3,4,5));
r($products[3]->deleted) && p('') && e('0'); // 删除前查询ID为3的产品是否被删除

$doclib = $product->objectModel->dao->select('deleted')->from(TABLE_DOCLIB)->where('product')->eq(3)->andWhere('deleted')->eq(0)->fetchAll();
r(count($doclib)) && p('') && e(1); // 删除前查询产品ID为3的产品库是否被删除

$projectProduct = $product->objectModel->dao->select('1')->from(TABLE_PROJECTPRODUCT)->where('product')->eq(3)->fetchAll();
r(count($projectProduct)) && p('') && e(1); // 删除前查询产品与项目的关联关系

/* Delete product 3. */
$product->objectModel->deleteByID(3);

$products = $product->objectModel->getByIdList(array(1,2,3,4,5));
r($products[3]->deleted) && p('') && e('1'); // 删除后查询ID为3的产品是否被删除

$product->objectModel->dao->reset();
$doclib = $product->objectModel->dao->select('deleted')->from(TABLE_DOCLIB)->where('product')->eq(3)->andWhere('deleted')->eq(1)->fetchAll();
r(count($doclib)) && p('') && e(1); // 删除后查询产品ID为3的产品库是否被删除

$projectProduct = $product->objectModel->dao->select('1')->from(TABLE_PROJECTPRODUCT)->where('product')->eq(3)->fetchAll();
r(count($projectProduct)) && p('') && e(0); // 删除后查询产品与项目的关联关系
