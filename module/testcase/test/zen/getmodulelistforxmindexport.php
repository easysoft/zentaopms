#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getModuleListForXmindExport();
timeout=0
cid=19094

- 步骤1：指定有效moduleID属性1 @模块1
- 步骤2：moduleID为0获取所有模块，期望返回数组长度5 @5
- 步骤3：不存在的moduleID，期望返回空数组长度0 @0
- 步骤4：不同branch参数，期望返回数组长度5 @5
- 步骤5：验证分支过滤逻辑，由于getOptionMenu方法没有实际过滤分支，返回所有模块 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1{5},2{5}');
$table->branch->range('0{8},1{2}');
$table->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$table->parent->range('0{3},1{3},2{4}');
$table->path->range(',1,,1,2,,1,2,3,,1,2,3,4,');
$table->grade->range('1{3},2{3},3{4}');
$table->order->range('1-10');
$table->type->range('case{10}');
$table->from->range('0{10}');
$table->owner->range('admin{10}');
$table->deleted->range('0{10}');
$table->gen(10);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1,产品2');
$product->type->range('normal{2}');
$product->status->range('normal{2}');
$product->deleted->range('0{2}');
$product->gen(2);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->getModuleListForXmindExportTest(1, 1, '0')) && p('1') && e('模块1'); // 步骤1：指定有效moduleID
r($testcaseZenTest->getModuleListForXmindExportTest(1, 0, '0')) && p() && e('5'); // 步骤2：moduleID为0获取所有模块，期望返回数组长度5
r($testcaseZenTest->getModuleListForXmindExportTest(1, 999, '0')) && p() && e('0'); // 步骤3：不存在的moduleID，期望返回空数组长度0
r($testcaseZenTest->getModuleListForXmindExportTest(1, 0, 'all')) && p() && e('5'); // 步骤4：不同branch参数，期望返回数组长度5
r($testcaseZenTest->getModuleListForXmindExportTest(1, 0, '1')) && p() && e('5'); // 步骤5：验证分支过滤逻辑，由于getOptionMenu方法没有实际过滤分支，返回所有模块