#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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

/**

title=productTao->getPagerProductsWithProgramIn();
cid=1
pid=1

- 步骤1 @1000,0
- 步骤2 @1001,2
- 步骤3 @1002,3

*/

$productIDs = array(1000, 1001, 1002);

$product = new productTest('admin');
$result  = $product->getPagerProductsWithProgramInTest($productIDs, null);

r($result[1000]) && p('id,program') && e('1000,1');
r($result[1001]) && p('id,program') && e('1001,2');
r($result[1002]) && p('id,program') && e('1002,3');
