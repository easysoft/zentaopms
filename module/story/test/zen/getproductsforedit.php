#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getProductsForEdit();
timeout=0
cid=18694

- 执行storyTest模块的getProductsForEditTest方法 属性1 @产品1
- 执行storyTest模块的getProductsForEditTest方法 属性2 @产品2
- 执行storyTest模块的getProductsForEditTest方法  @8
- 执行storyTest模块的getProductsForEditTest方法 属性3 @产品3
- 执行storyTest模块的getProductsForEditTest方法  @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->PO->range('admin,user1,user1,user2,admin,user2,admin,user1,user2,admin');
$product->status->range('normal{5},closed{2},normal{3}');
$product->type->range('normal');
$product->createdBy->range('admin');
$product->acl->range('open');
$product->gen(10);

zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

r($storyTest->getProductsForEditTest()) && p('1') && e('产品1');
r($storyTest->getProductsForEditTest()) && p('2') && e('产品2');
r(count($storyTest->getProductsForEditTest())) && p() && e('8');

su('user1');
r($storyTest->getProductsForEditTest()) && p('3') && e('产品3');
r(count($storyTest->getProductsForEditTest())) && p() && e('8');