#!/usr/bin/env php
<?php

/**

title=productTao->getPagerProductsWithProgramIn();
cid=0

- 执行$result[1000]
 - 属性id @1000
 - 属性program @1
- 执行$result[1001]
 - 属性id @1001
 - 属性program @2
- 执行$result[1002]
 - 属性id @1002
 - 属性program @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    /* Generate product data. */
    $product = zdTable('product');
    $product->id->range('1000-1100');
    $product->program->range('1-10');
    $product->name->prefix('product_')->range('1-10');
    $product->code->prefix('product_code_')->range('1-10');
    $product->gen(5);

    /* Generate program data. */
    $program = zdTable('project');
    $program->id->range('1-10');
    $program->name->prefix('program_')->range('1-10');
    $program->gen(5);
}
initData();

$productIDs = array(1000, 1001, 1002);

$product = new productTest('admin');
$result  = $product->getPagerProductsWithProgramInTest($productIDs, null);

r($result[1000]) && p('id,program') && e('1000,1');
r($result[1001]) && p('id,program') && e('1001,2');
r($result[1002]) && p('id,program') && e('1002,3');
