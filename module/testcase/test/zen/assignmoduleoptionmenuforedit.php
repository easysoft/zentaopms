#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModuleOptionMenuForEdit();
timeout=0
cid=0

- 步骤1：普通产品用例(branch=0)属性1 @/模块A
- 步骤2：分支产品用例(branch=1)属性4 @/模块D
- 步骤3：分支产品用例(branch=2)属性7 @/模块G
- 步骤4：产品1的第一个模块属性2 @/模块B
- 步骤5：产品1的第二个模块属性3 @/模块C
- 步骤6：产品2的模块属性6 @/模块F
- 步骤7：产品2分支用例属性8 @/模块H

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 产品数据
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

// 模块数据
$moduleTable = zenData('module');
$moduleTable->id->range('1-20');
$moduleTable->root->range('1{5},2{5},3{5},4{3},5{2}');
$moduleTable->branch->range('0{5},1{5},2{5},0{3},1{2}');
$moduleTable->name->range('模块A,模块B,模块C,模块D,模块E,模块F,模块G,模块H,模块I,模块J,模块K,模块L,模块M,模块N,模块O,模块P,模块Q,模块R,模块S,模块T');
$moduleTable->type->range('case');
$moduleTable->parent->range('0');
$moduleTable->grade->range('1');
$moduleTable->deleted->range('0');
$moduleTable->gen(20);

// 用例数据
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->product->range('1{5},2{3},3{2}');
$caseTable->module->range('1,2,3,4,5,6,7,8,11,12');
$caseTable->branch->range('0{3},1{4},2{3}');
$caseTable->lib->range('0');
$caseTable->fromCaseID->range('0');
$caseTable->title->range('测试用例A,测试用例B,测试用例C,测试用例D,测试用例E,测试用例F,测试用例G,测试用例H,测试用例I,测试用例J');
$caseTable->type->range('feature');
$caseTable->status->range('normal');
$caseTable->deleted->range('0');
$caseTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(1))) && p('1') && e('/模块A'); // 步骤1：普通产品用例(branch=0)
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(4))) && p('4') && e('/模块D'); // 步骤2：分支产品用例(branch=1)
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(7))) && p('7') && e('/模块G'); // 步骤3：分支产品用例(branch=2)
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(2))) && p('2') && e('/模块B'); // 步骤4：产品1的第一个模块
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(3))) && p('3') && e('/模块C'); // 步骤5：产品1的第二个模块
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(6))) && p('6') && e('/模块F'); // 步骤6：产品2的模块
r($testcaseTest->assignModuleOptionMenuForEditTest($testcaseTest->objectModel->loadModel('testcase')->getByID(8))) && p('8') && e('/模块H'); // 步骤7：产品2分支用例