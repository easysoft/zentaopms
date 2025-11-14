#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=19065

- 步骤1：正常场景对象编辑变量分配属性executed @1
- 步骤2：产品不存在的场景对象变量分配属性executed @0
- 步骤3：分支不存在的场景对象变量分配属性executed @1
- 步骤4：模块不存在的场景对象变量分配属性executed @0
- 步骤5：无效场景对象变量分配属性executed @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品{1-5}');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('1{3},2{3},3{2},4{2}');
$branch->name->range('分支{1-10}');
$branch->status->range('active{8},closed{2}');
$branch->deleted->range('0');
$branch->gen(10);

$module = zenData('module');
$module->id->range('1-15');
$module->root->range('1{5},2{5},3{5}');
$module->name->range('模块{1-15}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(15);

$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1{3},2{3},3{2},4{2}');
$scene->branch->range('1{3},2{3},3{2},4{2}');
$scene->module->range('1{3},2{3},3{2},4{2}');
$scene->title->range('场景{1-10}');
$scene->parent->range('0');
$scene->deleted->range('0');
$scene->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 构造测试场景对象
// 正常场景对象
$normalScene = new stdClass();
$normalScene->id = 1;
$normalScene->product = 1;
$normalScene->branch = '1';
$normalScene->module = 1;
$normalScene->parent = 0;

// 产品不存在的场景对象
$invalidProductScene = new stdClass();
$invalidProductScene->id = 2;
$invalidProductScene->product = 999;
$invalidProductScene->branch = '1';
$invalidProductScene->module = 1;
$invalidProductScene->parent = 0;

// 分支不存在的场景对象
$invalidBranchScene = new stdClass();
$invalidBranchScene->id = 3;
$invalidBranchScene->product = 1;
$invalidBranchScene->branch = '999';
$invalidBranchScene->module = 1;
$invalidBranchScene->parent = 0;

// 模块不存在的场景对象
$invalidModuleScene = new stdClass();
$invalidModuleScene->id = 4;
$invalidModuleScene->product = 1;
$invalidModuleScene->branch = '1';
$invalidModuleScene->module = 999;
$invalidModuleScene->parent = 0;

// 无效的场景对象（缺少必要属性）
$invalidScene = new stdClass();
$invalidScene->id = 5;

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest($normalScene)) && p('executed') && e('1'); // 步骤1：正常场景对象编辑变量分配
r($testcaseTest->assignEditSceneVarsTest($invalidProductScene)) && p('executed') && e('0'); // 步骤2：产品不存在的场景对象变量分配
r($testcaseTest->assignEditSceneVarsTest($invalidBranchScene)) && p('executed') && e('1'); // 步骤3：分支不存在的场景对象变量分配
r($testcaseTest->assignEditSceneVarsTest($invalidModuleScene)) && p('executed') && e('0'); // 步骤4：模块不存在的场景对象变量分配
r($testcaseTest->assignEditSceneVarsTest($invalidScene)) && p('executed') && e('0'); // 步骤5：无效场景对象变量分配