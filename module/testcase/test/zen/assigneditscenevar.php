#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常场景编辑变量分配属性executed @1
- 步骤2：重复场景编辑测试属性executed @1
- 步骤3：同场景不同请求测试属性executed @1
- 步骤4：第四次场景编辑测试属性executed @1
- 步骤5：第五次场景编辑测试属性executed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（简化数据配置）
$product = zenData('product');
$product->id->range('1');
$product->name->range('测试产品');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(1);

$module = zenData('module');
$module->id->range('1');
$module->root->range('1');
$module->name->range('测试模块');
$module->type->range('case');
$module->parent->range('0');
$module->deleted->range('0');
$module->gen(1);

$scene = zenData('scene');
$scene->id->range('1');
$scene->product->range('1');
$scene->branch->range('0');
$scene->module->range('1');
$scene->title->range('测试场景');
$scene->parent->range('0');
$scene->deleted->range('0');
$scene->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤1：正常场景编辑变量分配
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤2：重复场景编辑测试
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤3：同场景不同请求测试
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤4：第四次场景编辑测试
r($testcaseTest->assignEditSceneVarsTest((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'module' => 1, 'parent' => 0))) && p('executed') && e('1'); // 步骤5：第五次场景编辑测试