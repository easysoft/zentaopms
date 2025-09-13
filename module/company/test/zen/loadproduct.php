#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadProduct();
timeout=0
cid=0

- 步骤1：正常情况加载产品数量验证 @10
- 步骤2：检查第一个产品名称属性1 @产品1
- 步骤3：检查默认产品标签 @产品
- 步骤4：检查第八个产品名称属性8 @产品8
- 步骤5：检查第七个产品名称属性7 @产品7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->status->range('normal{8},closed{2}');
$table->deleted->range('0{9},1{1}');
$table->program->range('0');
$table->shadow->range('0');
$table->vision->range('rnd');
$table->gen(10);

su('admin');

$companyTest = new companyTest();

r(count($companyTest->loadProductTest())) && p() && e('10'); // 步骤1：正常情况加载产品数量验证
r($companyTest->loadProductTest()) && p('1') && e('产品1'); // 步骤2：检查第一个产品名称
r($companyTest->loadProductTest()) && p('0') && e('产品'); // 步骤3：检查默认产品标签
r($companyTest->loadProductTest()) && p('8') && e('产品8'); // 步骤4：检查第八个产品名称
r($companyTest->loadProductTest()) && p('7') && e('产品7'); // 步骤5：检查第七个产品名称