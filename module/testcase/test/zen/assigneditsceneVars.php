#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常情况第product条的name属性 @产品
- 步骤2：空对象输入属性error @oldScene cannot be empty
- 步骤3：不存在产品属性error @Product not found
- 步骤4：正常标题生成属性title @产品 - 编辑场景
- 步骤5：场景对象设置第scene条的id属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

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

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 构造测试场景对象
$oldScene1 = new stdClass();
$oldScene1->id = 1;
$oldScene1->product = 1;
$oldScene1->branch = '1';
$oldScene1->module = 1;
$oldScene1->parent = 0;

$oldScene2 = new stdClass();
$oldScene2->id = 2;
$oldScene2->product = 999; // 不存在的产品
$oldScene2->branch = '1';
$oldScene2->module = 1;
$oldScene2->parent = 0;

$oldScene3 = new stdClass();
$oldScene3->id = 3;
$oldScene3->product = 1;
$oldScene3->branch = '999'; // 不存在的分支
$oldScene3->module = 1;
$oldScene3->parent = 0;

$oldScene4 = new stdClass();
$oldScene4->id = 4;
$oldScene4->product = 1;
$oldScene4->branch = '1';
$oldScene4->module = 999; // 不存在的模块
$oldScene4->parent = 0;

// 6. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest($oldScene1)) && p('product:name') && e('产品'); // 步骤1：正常情况
r($testcaseTest->assignEditSceneVarsTest(null)) && p('error') && e('oldScene cannot be empty'); // 步骤2：空对象输入
r($testcaseTest->assignEditSceneVarsTest($oldScene2)) && p('error') && e('Product not found'); // 步骤3：不存在产品
r($testcaseTest->assignEditSceneVarsTest($oldScene1)) && p('title') && e('产品 - 编辑场景'); // 步骤4：正常标题生成
r($testcaseTest->assignEditSceneVarsTest($oldScene1)) && p('scene:id') && e('1'); // 步骤5：场景对象设置