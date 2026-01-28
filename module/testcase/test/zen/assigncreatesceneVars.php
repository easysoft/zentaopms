#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCreateSceneVars();
timeout=0
cid=19062

- 步骤1：正常产品ID，空分支，指定模块第product条的name属性 @产品1
- 步骤2：有分支的产品，指定分支，无模块第product条的name属性 @产品6
- 步骤3：不存在的产品ID属性moduleID @0
- 步骤4：指定模块ID测试属性moduleID @9
- 步骤5：无效分支名称测试属性branch @invalid_branch

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$productTable->type->range('normal{5},branch{5}');
$productTable->status->range('normal{8},closed{2}');
$productTable->deleted->range('0{8},1{2}');
$productTable->gen(10);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('6-10');
$branchTable->name->range('主分支,开发分支,测试分支,发布分支,紧急分支');
$branchTable->status->range('active{4},closed{1}');
$branchTable->deleted->range('0');
$branchTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1-10');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$moduleTable->type->range('case{10}');
$moduleTable->deleted->range('0{8},1{2}');
$moduleTable->gen(10);

$sceneTable = zenData('scene');
$sceneTable->id->range('1-5');
$sceneTable->product->range('1-5');
$sceneTable->module->range('1-5');
$sceneTable->title->range('场景1,场景2,场景3,场景4,场景5');
$sceneTable->deleted->range('0');
$sceneTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤
r($testcaseTest->assignCreateSceneVarsTest(1, '', 1)) && p('product:name') && e('产品1'); // 步骤1：正常产品ID，空分支，指定模块
r($testcaseTest->assignCreateSceneVarsTest(6, '1', 0)) && p('product:name') && e('产品6'); // 步骤2：有分支的产品，指定分支，无模块
r($testcaseTest->assignCreateSceneVarsTest(999, '', 0)) && p('moduleID') && e('0'); // 步骤3：不存在的产品ID
r($testcaseTest->assignCreateSceneVarsTest(1, '', 9)) && p('moduleID') && e('9'); // 步骤4：指定模块ID测试
r($testcaseTest->assignCreateSceneVarsTest(1, 'invalid_branch', 1)) && p('branch') && e('invalid_branch'); // 步骤5：无效分支名称测试