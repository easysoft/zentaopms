#!/usr/bin/env php
<?php

/**

title=productTao->getPagerProductsIn();
cid=0

- 执行$result
 - 属性id @1009
 - 属性order @10
- 执行$result[10000]属性empty @0
- 执行$result
 - 属性id @1007
 - 属性order @8
- 执行$result @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    /* Generate product data. */
    $product = zdTable('product');
    $product->id->range('1000-1010');
    $product->program->range('1-10');
    $product->name->prefix('product_')->range('1-10');
    $product->code->prefix('product_code_')->range('1-10');
    $product->order->range('1-10');
    $product->gen(10);
}
initData();

$productIDs = array(1007, 1008, 1009, 10000);

$product = new productTest('admin');

/* Desc. */
$result  = $product->objectModel->getPagerProductsIn($productIDs, null, 'order_desc');
r(array_shift($result)) && p('id,order') && e('1009,10');

/* Not exist data. */
r(isset($result[10000])) && p('empty') && e('0');

/* Asc. */
$result  = $product->objectModel->getPagerProductsIn($productIDs, null, 'order_asc');
r(array_shift($result)) && p('id,order') && e('1007,8');

/* Pager. */
global $tester;
$tester->app->loadClass('pager', true);
$tester->app->setModuleName('product');
$tester->app->setMethodName('all');
$pager = new pager(0, 2, 1);

$result = $product->objectModel->getPagerProductsIn($productIDs, $pager, 'order_desc');
r(count($result)) && p('') && e('2');
