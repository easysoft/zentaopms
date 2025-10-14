#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBrowseProduct();
timeout=0
cid=0

- 执行productTest模块的getBrowseProductTest方法，参数是1 
 - 属性id @1
 - 属性name @产品1
 - 属性type @normal
- 执行productTest模块的getBrowseProductTest方法，参数是999  @0
- 执行productTest模块的getBrowseProductTest方法  @0
- 执行productTest模块的getBrowseProductTest方法，参数是-1  @0
- 执行productTest模块的getBrowseProductTest方法，参数是5 
 - 属性id @5
 - 属性name @产品5
 - 属性type @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product');
$product = zenData('product');
$product->id->range('1-10');
$product->program->range('0,1-2');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->type->range('normal{5},branch{3},platform{2}');
$product->status->range('normal{8},closed{2}');
$product->acl->range('open{3},private{4},custom{3}');
$product->gen(10);

su('admin');

$productTest = new productTest();

r($productTest->getBrowseProductTest(1)) && p('id,name,type') && e('1,产品1,normal');
r($productTest->getBrowseProductTest(999)) && p() && e('0');
r($productTest->getBrowseProductTest(0)) && p() && e('0');
r($productTest->getBrowseProductTest(-1)) && p() && e('0');
r($productTest->getBrowseProductTest(5)) && p('id,name,type') && e('5,产品5,normal');