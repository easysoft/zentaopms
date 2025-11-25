#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::getProducts();
timeout=0
cid=19235

- 步骤1：教程模式下获取产品属性1 @产品A
- 步骤2：项目标签页非弹窗模式属性2 @产品B
- 步骤3：执行标签页非弹窗模式属性3 @产品C
- 步骤4：弹窗模式下获取产品属性4 @产品D
- 步骤5：QA标签页下获取产品属性5 @产品E

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->code->range('productA,productB,productC,productD,productE');
$product->type->range('normal,branch,platform');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskZenTest();

// 5. 执行测试步骤（至少5个）
r($testtaskTest->getProductsTest('qa', true, false, 0, 0)) && p('1') && e('产品A'); // 步骤1：教程模式下获取产品
r($testtaskTest->getProductsTest('project', false, false, 1, 0)) && p('2') && e('产品B'); // 步骤2：项目标签页非弹窗模式
r($testtaskTest->getProductsTest('execution', false, false, 0, 1)) && p('3') && e('产品C'); // 步骤3：执行标签页非弹窗模式
r($testtaskTest->getProductsTest('qa', false, true, 0, 0)) && p('4') && e('产品D'); // 步骤4：弹窗模式下获取产品
r($testtaskTest->getProductsTest('qa', false, false, 0, 0)) && p('5') && e('产品E'); // 步骤5：QA标签页下获取产品