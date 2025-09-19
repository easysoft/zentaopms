#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常情况属性executed @1
- 步骤2：不同产品属性executed @1
- 步骤3：已关闭分支属性executed @1
- 步骤4：不存在分支属性executed @1
- 步骤5：无效模块属性executed @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品{1-3}');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('1{2},2{2},3{1}');
$branch->name->range('分支{1-5}');
$branch->status->range('active{3},closed{2}');
$branch->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1{4},2{3},3{3}');
$module->name->range('模块{1-10}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(10);

$scene = zenData('scene');
$scene->id->range('1-5');
$scene->product->range('1{2},2{2},3{1}');
$scene->branch->range('1{2},2{2},3{1}');
$scene->module->range('1{2},2{2},3{1}');
$scene->title->range('场景{1-5}');
$scene->parent->range('0');
$scene->deleted->range('0');
$scene->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 构造测试对象
$validScene = new stdClass();
$validScene->id = 1;
$validScene->product = 1;
$validScene->branch = '1';
$validScene->module = 1;
$validScene->parent = 0;

$product2Scene = new stdClass();
$product2Scene->id = 2;
$product2Scene->product = 2;
$product2Scene->branch = '2';
$product2Scene->module = 5;
$product2Scene->parent = 0;

$closedBranchScene = new stdClass();
$closedBranchScene->id = 3;
$closedBranchScene->product = 1;
$closedBranchScene->branch = '4';
$closedBranchScene->module = 1;
$closedBranchScene->parent = 0;

$nonExistentBranchScene = new stdClass();
$nonExistentBranchScene->id = 4;
$nonExistentBranchScene->product = 1;
$nonExistentBranchScene->branch = '999';
$nonExistentBranchScene->module = 1;
$nonExistentBranchScene->parent = 0;

$invalidModuleScene = new stdClass();
$invalidModuleScene->id = 5;
$invalidModuleScene->product = 1;
$invalidModuleScene->branch = '1';
$invalidModuleScene->module = 999;
$invalidModuleScene->parent = 0;

// 6. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest($validScene)) && p('executed') && e('1'); // 步骤1：正常情况
r($testcaseTest->assignEditSceneVarsTest($product2Scene)) && p('executed') && e('1'); // 步骤2：不同产品
r($testcaseTest->assignEditSceneVarsTest($closedBranchScene)) && p('executed') && e('1'); // 步骤3：已关闭分支
r($testcaseTest->assignEditSceneVarsTest($nonExistentBranchScene)) && p('executed') && e('1'); // 步骤4：不存在分支
r($testcaseTest->assignEditSceneVarsTest($invalidModuleScene)) && p('executed') && e('0'); // 步骤5：无效模块