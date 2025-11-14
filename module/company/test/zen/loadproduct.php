#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadProduct();
timeout=0
cid=15739

- 步骤1:正常情况下加载产品列表,返回第一个元素为产品标签 @产品
- 步骤2:验证返回类型为数组 @1
- 步骤3:验证返回数组包含索引0 @1
- 步骤4:当存在产品数据时,验证返回数组包含产品ID @1
- 步骤5:验证返回数组第0个元素为产品标签 @产品

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->vision->range('rnd');
$product->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($companyTest->loadProductTest()) && p('0') && e('产品'); // 步骤1:正常情况下加载产品列表,返回第一个元素为产品标签
r(is_array($companyTest->loadProductTest())) && p() && e('1'); // 步骤2:验证返回类型为数组
r(isset($companyTest->loadProductTest()[0])) && p() && e('1'); // 步骤3:验证返回数组包含索引0
r(isset($companyTest->loadProductTest()[1])) && p() && e('1'); // 步骤4:当存在产品数据时,验证返回数组包含产品ID
r($companyTest->loadProductTest()) && p('0') && e('产品'); // 步骤5:验证返回数组第0个元素为产品标签