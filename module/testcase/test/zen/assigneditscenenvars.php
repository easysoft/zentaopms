#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常场景对象编辑变量设置属性executed @1
- 步骤2：不同产品的场景对象处理属性executed @0
- 步骤3：多分支场景的变量设置属性executed @1
- 步骤4：模块树结构的变量赋值属性executed @0
- 步骤5：场景菜单的构建验证属性executed @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$branch = zenData('branch');
$branch->id->range('1-6');
$branch->product->range('1{2},2{2},3{2}');
$branch->name->range('分支1,分支2,分支3,分支4,分支5,分支6');
$branch->status->range('active{4},closed{2}');
$branch->gen(6);

$module = zenData('module');
$module->id->range('1-12');
$module->root->range('1{4},2{4},3{4}');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10,模块11,模块12');
$module->type->range('case');
$module->parent->range('0{3},1{3},2{3},3{3}');
$module->path->range(',1,,1,2,,1,2,3,,2,,2,3,,2,3,4,,3,,3,4,,3,4,5,');
$module->grade->range('1{3},2{3},3{3},2{3}');
$module->order->range('5,10,15,20,25,30,35,40,45,50,55,60');
$module->deleted->range('0');
$module->gen(12);

$scene = zenData('scene');
$scene->id->range('1-8');
$scene->product->range('1{3},2{3},3{2}');
$scene->branch->range('1{3},3{3},5{2}');
$scene->module->range('1{3},5{3},9{2}');
$scene->title->range('场景1,场景2,场景3,场景4,场景5,场景6,场景7,场景8');
$scene->parent->range('0{4},1{2},2{2}');
$scene->deleted->range('0');
$scene->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '1', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤1：正常场景对象编辑变量设置
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 2, 'product' => 2, 'branch' => '3', 'module' => 5, 'parent' => 0))) && p('executed') && e('0'); // 步骤2：不同产品的场景对象处理
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 3, 'product' => 1, 'branch' => '2', 'module' => 2, 'parent' => 0))) && p('executed') && e('1'); // 步骤3：多分支场景的变量设置
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 4, 'product' => 3, 'branch' => '5', 'module' => 9, 'parent' => 0))) && p('executed') && e('0'); // 步骤4：模块树结构的变量赋值
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 5, 'product' => 2, 'branch' => '4', 'module' => 6, 'parent' => 1))) && p('executed') && e('0'); // 步骤5：场景菜单的构建验证