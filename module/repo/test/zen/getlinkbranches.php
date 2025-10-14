#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkBranches();
timeout=0
cid=0

- 步骤1：空数组 @0
- 步骤2：只有normal类型 @0
- 步骤3：有branch类型产品
 - 属性1 @产品B / 分支1
 - 属性2 @产品B / 分支2
- 步骤4：混合类型
 - 属性3 @产品C / 分支1
 - 属性4 @产品C / 分支2
- 步骤5：多个非normal类型
 - 属性1 @产品B / 分支1
 - 属性2 @产品B / 分支2
 - 属性5 @产品E / 分支1
 - 属性6 @产品E / 分支2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->type->range('normal,branch,platform,normal,branch');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-6');
$branch->product->range('2{2},3{2},5{2}');
$branch->name->range('分支1,分支2,分支1,分支2,分支1,分支2');
$branch->status->range('active');
$branch->deleted->range('0');
$branch->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->getLinkBranchesTest(array())) && p() && e('0'); // 步骤1：空数组
r($repoTest->getLinkBranchesTest(array((object)array('id' => 1, 'name' => '产品A', 'type' => 'normal')))) && p() && e('0'); // 步骤2：只有normal类型
r($repoTest->getLinkBranchesTest(array((object)array('id' => 2, 'name' => '产品B', 'type' => 'branch')))) && p('1,2') && e('产品B / 分支1,产品B / 分支2'); // 步骤3：有branch类型产品
r($repoTest->getLinkBranchesTest(array((object)array('id' => 1, 'name' => '产品A', 'type' => 'normal'), (object)array('id' => 3, 'name' => '产品C', 'type' => 'platform')))) && p('3,4') && e('产品C / 分支1,产品C / 分支2'); // 步骤4：混合类型
r($repoTest->getLinkBranchesTest(array((object)array('id' => 2, 'name' => '产品B', 'type' => 'branch'), (object)array('id' => 5, 'name' => '产品E', 'type' => 'branch')))) && p('1,2,5,6') && e('产品B / 分支1,产品B / 分支2,产品E / 分支1,产品E / 分支2'); // 步骤5：多个非normal类型