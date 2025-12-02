#!/usr/bin/env php
<?php

/**

title=- 步骤2：分支产品1行数据，检查分支选项值 @主干(#0) 分支1(#1) 分支3(
timeout=0
cid=19095

- 步骤1：普通产品2行数据，检查第一行的类型选项值 @单元测试 接口测试 功能测试 安装部署 配置相关 性能测试 安全相关 其他
- 步骤2：分支产品1行数据，检查分支选项值 @主干(#0) 分支1(#1) 分支3(#3)
- 步骤3：生成1行数据，检查阶段选项值 @单元测试阶段 功能测试阶段 集成测试阶段 系统测试阶段 冒烟测试阶段 版本验证阶段
- 步骤4：生成0行数据，期望计数为0 @0
- 步骤5：生成5行，每个模块3个，共15行数据 @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3');
$productTable->type->range('normal{1},branch{2}');
$productTable->gen(3);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('2,3');
$branchTable->name->range('分支1,分支2,分支3');
$branchTable->gen(3);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5');
$moduleTable->root->range('1-3');
$moduleTable->type->range('case');
$moduleTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$product1 = new stdClass();
$product1->id = 1;
$product1->type = 'normal';
$rows = $testcaseTest->getRowsForExportTemplateTest($product1, 2);
$typeValue = trim(str_replace("\n", ' ', $rows[0]->typeValue));
r($typeValue) && p() && e('单元测试 接口测试 功能测试 安装部署 配置相关 性能测试 安全相关 其他'); // 步骤1：普通产品2行数据，检查第一行的类型选项值

$product2 = new stdClass();
$product2->id = 2;
$product2->type = 'branch';
$rows = $testcaseTest->getRowsForExportTemplateTest($product2, 1);
$branchValue = trim(str_replace("\n", ' ', $rows[0]->branchValue));
r($branchValue) && p() && e('主干(#0) 分支1(#1) 分支3(#3)'); // 步骤2：分支产品1行数据，检查分支选项值

$rows = $testcaseTest->getRowsForExportTemplateTest($product1, 1);
$stageValue = trim(str_replace("\n", ' ', $rows[0]->stageValue));
r($stageValue) && p() && e('单元测试阶段 功能测试阶段 集成测试阶段 系统测试阶段 冒烟测试阶段 版本验证阶段'); // 步骤3：生成1行数据，检查阶段选项值

r(count($testcaseTest->getRowsForExportTemplateTest($product1, 0))) && p() && e(0); // 步骤4：生成0行数据，期望计数为0

r(count($testcaseTest->getRowsForExportTemplateTest($product1, 5))) && p() && e(15); // 步骤5：生成5行，每个模块3个，共15行数据