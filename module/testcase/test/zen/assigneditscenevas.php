#!/usr/bin/env php
<?php

/**

title=- 属性error @treeModel::getModulesName(): Argument
timeout=0
cid=1

- 步骤1：场景对象1测试 @~~
- 步骤2：场景对象2测试 @~~
- 步骤3：场景对象3测试 @~~
- 步骤4：场景对象4测试 @~~
- 步骤5：场景对象5测试 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('testcase_assigneditscenevas', false, 2)->gen(5);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('1{2},2{2},3{1}');
$branch->name->range('分支{1-5}');
$branch->status->range('active{4},closed{1}');
$branch->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1{4},2{3},3{3}');
$module->name->range('模块{1-10}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(10);

$testcase = zenData('testcase');
$testcase->id->range('1-20');
$testcase->product->range('1{10},2{5},3{5}');
$testcase->branch->range('0{10},1{5},2{5}');
$testcase->module->range('1{5},2{5},3{5},4{5}');
$testcase->title->range('场景{1-20}');
$testcase->type->range('scene');
$testcase->deleted->range('0');
$testcase->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseZenTest = new testcaseZenTest();

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
$oldScene3->product = 3;
$oldScene3->branch = '0';
$oldScene3->module = 3;
$oldScene3->parent = 0;

$oldScene4 = new stdClass();
$oldScene4->id = 4;
$oldScene4->product = 1;
$oldScene4->branch = '1';
$oldScene4->module = 4;
$oldScene4->parent = 1;

$oldScene5 = new stdClass();
$oldScene5->id = 5;
$oldScene5->product = 2;
$oldScene5->branch = '2';
$oldScene5->module = 5;
$oldScene5->parent = 2;

// 6. 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->assignEditSceneVarsTest($oldScene1)) && p() && e('~~'); // 步骤1：场景对象1测试
r($testcaseZenTest->assignEditSceneVarsTest($oldScene2)) && p() && e('~~'); // 步骤2：场景对象2测试
r($testcaseZenTest->assignEditSceneVarsTest($oldScene3)) && p() && e('~~'); // 步骤3：场景对象3测试
r($testcaseZenTest->assignEditSceneVarsTest($oldScene4)) && p() && e('~~'); // 步骤4：场景对象4测试
r($testcaseZenTest->assignEditSceneVarsTest($oldScene5)) && p() && e('~~'); // 步骤5：场景对象5测试