#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildUpdateCaseForShowImport();
timeout=0
cid=0

- 步骤1：步骤数量变化，步骤变更，版本号+1
 - 属性result @1
 - 属性version @2
- 步骤2：步骤内容相同，无变更，版本号不变
 - 属性result @0
 - 属性version @1
- 步骤3：步骤描述变化，步骤变更，版本号+1
 - 属性result @1
 - 属性version @2
- 步骤4：步骤期望变化，步骤变更，版本号+1
 - 属性result @1
 - 属性version @2
- 步骤5：步骤类型变化，步骤变更，版本号+1
 - 属性result @1
 - 属性version @2
- 步骤6：步骤变更且forceNotReview=false，状态设为wait
 - 属性result @1
 - 属性status @wait
- 步骤7：步骤变更且forceNotReview=true，状态不变
 - 属性result @1
 - 属性status @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 准备测试数据

// 创建旧步骤数组 - 2个步骤
function createOldStep() {
    $oldStep = array();
    $step1 = new stdClass();
    $step1->desc = '步骤1描述';
    $step1->expect = '步骤1期望';
    $step1->type = 'step';
    $oldStep[] = $step1;

    $step2 = new stdClass();
    $step2->desc = '步骤2描述';
    $step2->expect = '步骤2期望';
    $step2->type = 'step';
    $oldStep[] = $step2;

    return $oldStep;
}

// 创建旧用例对象
function createOldCase() {
    $oldCase = new stdClass();
    $oldCase->version = 1;
    return $oldCase;
}

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：步骤数量变化（3个步骤 vs 2个步骤），步骤变更，版本号+1
$case1 = new stdClass();
$case1->desc = array('步骤1描述', '步骤2描述', '步骤3描述');
$case1->expect = array('步骤1期望', '步骤2期望', '步骤3期望');
$case1->stepType = array('step', 'step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case1, createOldCase(), createOldStep(), false)) && p('result,version') && e('1,2'); // 步骤1：步骤数量变化，步骤变更，版本号+1

// 步骤2：步骤内容相同，无变更，版本号不变
$case2 = new stdClass();
$case2->desc = array('步骤1描述', '步骤2描述');
$case2->expect = array('步骤1期望', '步骤2期望');
$case2->stepType = array('step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case2, createOldCase(), createOldStep(), false)) && p('result,version') && e('0,1'); // 步骤2：步骤内容相同，无变更，版本号不变

// 步骤3：步骤描述变化，步骤变更，版本号+1
$case3 = new stdClass();
$case3->desc = array('修改后步骤1描述', '步骤2描述');
$case3->expect = array('步骤1期望', '步骤2期望');
$case3->stepType = array('step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case3, createOldCase(), createOldStep(), false)) && p('result,version') && e('1,2'); // 步骤3：步骤描述变化，步骤变更，版本号+1

// 步骤4：步骤期望变化，步骤变更，版本号+1
$case4 = new stdClass();
$case4->desc = array('步骤1描述', '步骤2描述');
$case4->expect = array('修改后步骤1期望', '步骤2期望');
$case4->stepType = array('step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case4, createOldCase(), createOldStep(), false)) && p('result,version') && e('1,2'); // 步骤4：步骤期望变化，步骤变更，版本号+1

// 步骤5：步骤类型变化，步骤变更，版本号+1
$case5 = new stdClass();
$case5->desc = array('步骤1描述', '步骤2描述');
$case5->expect = array('步骤1期望', '步骤2期望');
$case5->stepType = array('group', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case5, createOldCase(), createOldStep(), false)) && p('result,version') && e('1,2'); // 步骤5：步骤类型变化，步骤变更，版本号+1

// 步骤6：步骤变更且forceNotReview=false，状态设为wait
$case6 = new stdClass();
$case6->desc = array('修改后步骤1描述', '步骤2描述');
$case6->expect = array('步骤1期望', '步骤2期望');
$case6->stepType = array('step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case6, createOldCase(), createOldStep(), false)) && p('result,status') && e('1,wait'); // 步骤6：步骤变更且forceNotReview=false，状态设为wait

// 步骤7：步骤变更且forceNotReview=true，状态不变
$case7 = new stdClass();
$case7->desc = array('修改后步骤1描述', '步骤2描述');
$case7->expect = array('步骤1期望', '步骤2期望');
$case7->stepType = array('step', 'step');
r($testcaseTest->buildUpdateCaseForShowImportTest($case7, createOldCase(), createOldStep(), true)) && p('result,status') && e('1,~~'); // 步骤7：步骤变更且forceNotReview=true，状态不变