#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常场景对象输入执行属性executed @1
- 步骤2：验证错误处理机制属性executed @0
- 步骤3：验证第二个场景处理属性executed @1
- 步骤4：验证第三个场景处理属性executed @0
- 步骤5：验证第四个场景处理属性executed @0

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

// 5. 构造测试场景对象
$oldScene1 = new stdClass();
$oldScene1->id = 1;
$oldScene1->product = 1;
$oldScene1->branch = '1';
$oldScene1->module = 1;
$oldScene1->parent = 0;

$oldScene2 = new stdClass();
$oldScene2->id = 2;
$oldScene2->product = 2;
$oldScene2->branch = '2';
$oldScene2->module = 2;
$oldScene2->parent = 0;

$oldScene3 = new stdClass();
$oldScene3->id = 3;
$oldScene3->product = 1;
$oldScene3->branch = '1';
$oldScene3->module = 1;
$oldScene3->parent = 0;

$oldScene4 = new stdClass();
$oldScene4->id = 4;
$oldScene4->product = 2;
$oldScene4->branch = '2';
$oldScene4->module = 2;
$oldScene4->parent = 0;

$oldScene5 = new stdClass();
$oldScene5->id = 5;
$oldScene5->product = 3;
$oldScene5->branch = '3';
$oldScene5->module = 3;
$oldScene5->parent = 0;

// 6. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest($oldScene1)) && p('executed') && e('1'); // 步骤1：正常场景对象输入执行
r($testcaseTest->assignEditSceneVarsTest($oldScene2)) && p('executed') && e('0'); // 步骤2：验证错误处理机制
r($testcaseTest->assignEditSceneVarsTest($oldScene3)) && p('executed') && e('1'); // 步骤3：验证第二个场景处理
r($testcaseTest->assignEditSceneVarsTest($oldScene4)) && p('executed') && e('0'); // 步骤4：验证第三个场景处理
r($testcaseTest->assignEditSceneVarsTest($oldScene5)) && p('executed') && e('0'); // 步骤5：验证第四个场景处理