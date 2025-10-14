#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProductsByBrowseType();
timeout=0
cid=0

- 步骤1：all类型返回所有产品 @7
- 步骤2：unclosed类型排除closed状态项目集的产品 @6
- 步骤3：closed类型只返回closed状态项目集的产品 @1
- 步骤4：doing类型返回doing状态项目集的产品 @4
- 步骤5：wait类型返回wait状态项目集的产品 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$programTable = zenData('project');
$programTable->id->range('1-10');
$programTable->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$programTable->type->range('program{5}');
$programTable->status->range('doing{2},wait{1},closed{1},suspended{1}');
$programTable->deleted->range('0{5}');
$programTable->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7');
$productTable->program->range('1{2},2{2},3{1},4{1},0{1}');
$productTable->status->range('normal{7}');
$productTable->deleted->range('0{7}');
$productTable->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programTest = new programTest();

// 构造测试用的产品数据
$products = array();
for($i = 1; $i <= 7; $i++)
{
    $product = new stdclass();
    $product->id = $i;
    $product->name = '产品' . $i;
    if($i <= 2) $product->program = 1; // doing状态的项目集
    elseif($i <= 4) $product->program = 2; // doing状态的项目集
    elseif($i == 5) $product->program = 3; // wait状态的项目集
    elseif($i == 6) $product->program = 4; // closed状态的项目集
    else $product->program = 0; // 无项目集
    $products[] = $product;
}

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($programTest->getProductsByBrowseTypeTest('all', $products))) && p() && e('7'); // 步骤1：all类型返回所有产品
r(count($programTest->getProductsByBrowseTypeTest('unclosed', $products))) && p() && e('6'); // 步骤2：unclosed类型排除closed状态项目集的产品
r(count($programTest->getProductsByBrowseTypeTest('closed', $products))) && p() && e('1'); // 步骤3：closed类型只返回closed状态项目集的产品
r(count($programTest->getProductsByBrowseTypeTest('doing', $products))) && p() && e('4'); // 步骤4：doing类型返回doing状态项目集的产品
r(count($programTest->getProductsByBrowseTypeTest('wait', $products))) && p() && e('1'); // 步骤5：wait类型返回wait状态项目集的产品