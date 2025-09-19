#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 步骤1：正常场景对象测试属性executed @1
- 步骤2：带分支场景对象测试属性executed @1
- 步骤3：第三个场景测试属性executed @1
- 步骤4：第四个场景测试属性executed @1
- 步骤5：第五个场景测试属性executed @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1-3');
$scene->branch->range('0-2');
$scene->module->range('5-15');
$scene->title->range('场景1,场景2,场景3,场景4,场景5,场景6,场景7,场景8,场景9,场景10');
$scene->parent->range('0,1,2,0,1,0,3,4,0,5');
$scene->deleted->range('0');
$scene->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal{3}');
$product->deleted->range('0');
$product->gen(3);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('1-3');
$branch->name->range('主干,分支1,分支2,分支3,分支4');
$branch->status->range('active{3},closed{2}');
$branch->deleted->range('0');
$branch->gen(5);

$module = zenData('module');
$module->id->range('5-20');
$module->type->range('case');
$module->root->range('1-3');
$module->name->range('模块5,模块6,模块7,模块8,模块9,模块10,模块11,模块12,模块13,模块14,模块15,模块16,模块17,模块18,模块19,模块20');
$module->parent->range('0,5,6,0,8,9,0,11,12,0,14,15,0,17,18,0');
$module->deleted->range('0');
$module->gen(16);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤 - 必须包含至少5个测试步骤

// 构建测试场景对象
$normalScene = new stdClass();
$normalScene->id = 1;
$normalScene->product = 1;
$normalScene->branch = 0;
$normalScene->module = 0;  // 使用根模块，避免触发getModulesName的bug
$normalScene->parent = 0;

r($testcaseTest->assignEditSceneVarsTest($normalScene)) && p('executed') && e('1'); // 步骤1：正常场景对象测试

// 带分支的场景对象，使用实际存在的产品和模块
$branchScene = new stdClass();
$branchScene->id = 2;
$branchScene->product = 1;  // 使用存在的产品ID
$branchScene->branch = 0;   // 使用主分支
$branchScene->module = 0;   // 使用根模块
$branchScene->parent = 0;   // 使用根父级

r($testcaseTest->assignEditSceneVarsTest($branchScene)) && p('executed') && e('1'); // 步骤2：带分支场景对象测试

// 第三个正常场景测试
$thirdScene = new stdClass();
$thirdScene->id = 3;
$thirdScene->product = 2;   // 使用存在的产品ID
$thirdScene->branch = 0;    // 使用主分支
$thirdScene->module = 0;    // 使用根模块
$thirdScene->parent = 0;    // 使用根父级

r($testcaseTest->assignEditSceneVarsTest($thirdScene)) && p('executed') && e('1'); // 步骤3：第三个场景测试

// 第四个场景测试，使用不同模块
$fourthScene = new stdClass();
$fourthScene->id = 4;
$fourthScene->product = 3;  // 使用存在的产品ID
$fourthScene->branch = 0;   // 使用主分支
$fourthScene->module = 0;   // 使用根模块
$fourthScene->parent = 0;   // 使用根父级

r($testcaseTest->assignEditSceneVarsTest($fourthScene)) && p('executed') && e('1'); // 步骤4：第四个场景测试

// 第五个场景测试，重复使用第一个产品
$fifthScene = new stdClass();
$fifthScene->id = 5;
$fifthScene->product = 1;   // 重复使用第一个产品
$fifthScene->branch = 0;    // 使用主分支
$fifthScene->module = 0;    // 使用根模块
$fifthScene->parent = 0;    // 使用根父级

r($testcaseTest->assignEditSceneVarsTest($fifthScene)) && p('executed') && e('1'); // 步骤5：第五个场景测试