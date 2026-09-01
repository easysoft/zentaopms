#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildProductSwitcher();
timeout=0
cid=16417

- 步骤1：多产品情况，应包含全部选项第0条的0属性 @所有产品
- 步骤2：单个normal产品第0条的1属性 @Product A
- 步骤3：branch产品有分支选项数量 @0
- 步骤4：platform产品第0条的4属性 @Product D
- 步骤5：空产品列表长度 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product A,Product B,Product C,Product D,Product E');
$product->type->range('normal,normal,branch,platform,branch');
$product->program->range('1,2,1,3,2');
$product->line->range('1,2,0,3,2');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-6');
$branch->product->range('3,3,5,5,3,5');
$branch->name->range('Branch 1,Branch 2,Branch A,Branch B,Branch 3,Branch C');
$branch->status->range('active,closed,active,active,closed,active');
$branch->deleted->range('0{6}');
$branch->gen(6);

$execution = zenData('project');
$execution->id->range('1');
$execution->name->range('迭代1');
$execution->type->range('sprint');
$execution->project->range('0');
$execution->status->range('doing');
$execution->multiple->range('1');
$execution->deleted->range('0');
$execution->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionZenTest = new executionZenTest();

// 5. 构造测试数据
$products = array();
for($i = 1; $i <= 5; $i++)
{
    $product = new stdClass();
    $product->id = $i;
    $product->name = "Product " . chr(64 + $i);
    $product->type = ($i == 3 || $i == 5) ? 'branch' : ($i == 4 ? 'platform' : 'normal');
    $product->program = ($i <= 2) ? 1 : ($i == 3 || $i == 4 ? $i : 2);
    $product->line = ($i == 3) ? 0 : (($i <= 2) ? $i : ($i == 4 ? 3 : 2));
    $products[] = $product;
}

$singleProduct = array($products[0]);
$branchProduct = array($products[2]);
$platformProduct = array($products[3]);

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($executionZenTest->buildProductSwitcherTest(1, 1, $products)) && p('0:0') && e('所有产品'); // 步骤1：多产品情况，应包含全部选项
r($executionZenTest->buildProductSwitcherTest(1, 1, $singleProduct)) && p('0:1') && e('Product A'); // 步骤2：单个normal产品
r(count($executionZenTest->buildProductSwitcherTest(1, 3, $branchProduct)[1])) && p() && e('0'); // 步骤3：branch产品有分支选项数量
r($executionZenTest->buildProductSwitcherTest(1, 4, $platformProduct)) && p('0:4') && e('Product D'); // 步骤4：platform产品
r(count($executionZenTest->buildProductSwitcherTest(1, 0, array())[0])) && p() && e('0'); // 步骤5：空产品列表长度
