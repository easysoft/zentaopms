#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常场景编辑变量分配属性executed @1
- 步骤2：分支为1的场景编辑测试属性executed @1
- 步骤3：分支为2的场景编辑测试属性executed @1
- 步骤4：不同模块的场景编辑测试属性executed @1
- 步骤5：多个产品的场景编辑测试属性executed @1

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
$branch->status->range('active');
$branch->gen(5);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1{2},2{2},3{1}');
$module->name->range('模块{1-5}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(5);

$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1{5},2{3},3{2}');
$scene->branch->range('0{5},1{3},2{2}');
$scene->module->range('1{3},2{3},3{4}');
$scene->title->range('场景{1-10}');
$scene->parent->range('0');
$scene->deleted->range('0');
$scene->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 构造测试场景对象
$scene1 = new stdClass();
$scene1->id = 1;
$scene1->product = 1;
$scene1->branch = '0';
$scene1->module = 1;
$scene1->parent = 0;

$scene2 = new stdClass();
$scene2->id = 2;
$scene2->product = 1;
$scene2->branch = '1';
$scene2->module = 2;
$scene2->parent = 0;

$scene3 = new stdClass();
$scene3->id = 3;
$scene3->product = 1;
$scene3->branch = '1';
$scene3->module = 1;
$scene3->parent = 0;

$scene4 = new stdClass();
$scene4->id = 4;
$scene4->product = 1;
$scene4->branch = '0';
$scene4->module = 2;
$scene4->parent = 0;

$scene5 = new stdClass();
$scene5->id = 5;
$scene5->product = 2;
$scene5->branch = '0';
$scene5->module = 3;
$scene5->parent = 0;

// 6. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest($scene1)) && p('executed') && e('1'); // 步骤1：正常场景编辑变量分配
r($testcaseTest->assignEditSceneVarsTest($scene2)) && p('executed') && e('1'); // 步骤2：分支为1的场景编辑测试
r($testcaseTest->assignEditSceneVarsTest($scene3)) && p('executed') && e('1'); // 步骤3：分支为2的场景编辑测试
r($testcaseTest->assignEditSceneVarsTest($scene4)) && p('executed') && e('1'); // 步骤4：不同模块的场景编辑测试
r($testcaseTest->assignEditSceneVarsTest($scene5)) && p('executed') && e('1'); // 步骤5：多个产品的场景编辑测试