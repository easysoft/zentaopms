#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getProductsForCreate();
timeout=0
cid=0

- 步骤1:测试在默认tab下获取产品列表,传入productID=1 @1
- 步骤2:测试在默认tab下获取产品列表,传入不存在的productID=999 @10
- 步骤3:测试返回对象包含products属性 @1
- 步骤4:测试返回对象包含productID属性 @1
- 步骤5:测试在默认tab下传入空productID @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

zenData('project')->gen(5);
zenData('user')->gen(5);

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->productID = 1;
$bug1->projectID = 0;
$bug1->executionID = 0;

$bug2 = new stdClass();
$bug2->productID = 999;
$bug2->projectID = 0;
$bug2->executionID = 0;

$bug3 = new stdClass();
$bug3->productID = 1;
$bug3->projectID = 0;
$bug3->executionID = 0;

$bug4 = new stdClass();
$bug4->productID = 1;
$bug4->projectID = 0;
$bug4->executionID = 0;

$bug5 = new stdClass();
$bug5->productID = 0;
$bug5->projectID = 0;
$bug5->executionID = 0;

r($bugTest->getProductsForCreateTest($bug1)->productID) && p() && e('1'); // 步骤1:测试在默认tab下获取产品列表,传入productID=1
r($bugTest->getProductsForCreateTest($bug2)->productID) && p() && e('10'); // 步骤2:测试在默认tab下获取产品列表,传入不存在的productID=999
r(property_exists($bugTest->getProductsForCreateTest($bug3), 'products')) && p() && e('1'); // 步骤3:测试返回对象包含products属性
r(property_exists($bugTest->getProductsForCreateTest($bug4), 'productID')) && p() && e('1'); // 步骤4:测试返回对象包含productID属性
r($bugTest->getProductsForCreateTest($bug5)->productID) && p() && e('10'); // 步骤5:测试在默认tab下传入空productID