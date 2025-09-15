#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchOptions();
timeout=0
cid=0

- 步骤1：空的项目产品列表 @0
- 步骤2：普通类型产品 @0
- 步骤3：分支类型产品，项目ID为0 @1
- 步骤4：分支类型产品，指定项目ID @2
- 步骤5：混合类型产品 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{2},branch{3}');
$product->status->range('normal');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-6');
$branch->product->range('3{2},4{2},5{2}');
$branch->name->range('主分支,开发分支,测试分支,V1.0,V2.0,hotfix');
$branch->status->range('active{5},closed{1}');
$branch->deleted->range('0');
$branch->gen(6);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{2},2{2}');
$projectProduct->product->range('3,4,3,4');
$projectProduct->branch->range('1,3,2,4');
$projectProduct->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getBranchOptionsTest(array(), 0)) && p() && e('0'); // 步骤1：空的项目产品列表
r($productTest->getBranchOptionsTest(array((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 2, 'type' => 'normal')), 0)) && p() && e('0'); // 步骤2：普通类型产品
r($productTest->getBranchOptionsTest(array((object)array('id' => 3, 'type' => 'branch')), 0)) && p() && e('1'); // 步骤3：分支类型产品，项目ID为0
r($productTest->getBranchOptionsTest(array((object)array('id' => 3, 'type' => 'branch'), (object)array('id' => 4, 'type' => 'branch')), 1)) && p() && e('2'); // 步骤4：分支类型产品，指定项目ID
r($productTest->getBranchOptionsTest(array((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 3, 'type' => 'branch'), (object)array('id' => 4, 'type' => 'branch')), 2)) && p() && e('2'); // 步骤5：混合类型产品