#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchAndTagOption();
timeout=0
cid=0

- 步骤1：空产品且为项目需求时返回空数组 @0
- 步骤2：普通类型产品返回空数组 @0
- 步骤3：分支类型产品有分支数据时返回分支选项 @4
- 步骤4：分支类型产品有已关闭分支时返回带状态标签的选项 @6
- 步骤5：分支类型产品但无分支数据时返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('禅道项目管理软件,禅道测试管理软件,禅道文档管理,ZenTao移动端,ZenTao API');
$product->type->range('normal{2},branch{2},platform{1}');
$product->status->range('normal{4},closed{1}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-20');
$branch->product->range('1{10},2{5},3{5}');
$branch->name->range('master{3},develop{3},feature/user-auth{2},feature/payment{2},release/v1.0{2},release/v2.0{2},hotfix/bug-123{3},feature/new-ui{3}');
$branch->status->range('active{15},closed{5}');
$branch->deleted->range('0{18},1{2}');
$branch->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getBranchAndTagOptionTest(0, null, true)) && p() && e('0'); // 步骤1：空产品且为项目需求时返回空数组
r($productTest->getBranchAndTagOptionTest(0, (object)array('id' => 1, 'type' => 'normal'), false)) && p() && e('0'); // 步骤2：普通类型产品返回空数组
r($productTest->getBranchAndTagOptionTest(0, (object)array('id' => 3, 'type' => 'branch'), false)) && p() && e('4'); // 步骤3：分支类型产品有分支数据时返回分支选项
r($productTest->getBranchAndTagOptionTest(0, (object)array('id' => 2, 'type' => 'branch'), false)) && p() && e('6'); // 步骤4：分支类型产品有已关闭分支时返回带状态标签的选项
r($productTest->getBranchAndTagOptionTest(0, (object)array('id' => 100, 'type' => 'branch'), false)) && p() && e('0'); // 步骤5：分支类型产品但无分支数据时返回空数组