#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Edit();
timeout=0
cid=0

- 执行getFormFields4EditTest($productTest模块的getByIdTest方法，参数是1 第name条的required属性 @1
- 执行getFormFields4EditTest($productTest模块的getByIdTest方法，参数是1 第changeProjects条的type属性 @string
- 执行getFormFields4EditTest($productTest模块的getByIdTest方法，参数是1 第name条的default属性 @产品A
- 执行getFormFields4EditTest($productTest模块的getByIdTest方法，参数是1 第program条的control属性 @select
- 执行getFormFields4EditTest($productTest模块的getByIdTest方法，参数是1 第desc条的width属性 @full

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->program->range('0,1,2,0,1');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->code->range('productA,productB,productC,productD,productE');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('admin');
$product->RD->range('admin');
$product->acl->range('open');
$product->line->range('0');
$product->desc->range('这是产品的描述');
$product->gen(5);

su('admin');

$productTest = new productTest();

r($productTest->getFormFields4EditTest($productTest->getByIdTest(1))) && p('name:required') && e('1');
r($productTest->getFormFields4EditTest($productTest->getByIdTest(1))) && p('changeProjects:type') && e('string');
r($productTest->getFormFields4EditTest($productTest->getByIdTest(1))) && p('name:default') && e('产品A');
r($productTest->getFormFields4EditTest($productTest->getByIdTest(1))) && p('program:control') && e('select');
r($productTest->getFormFields4EditTest($productTest->getByIdTest(1))) && p('desc:width') && e('full');