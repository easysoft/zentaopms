#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignBranchForEdit();
timeout=0
cid=0

- 步骤1：execution模式正常情况属性1 @分支1
- 步骤2：project模式正常情况属性2 @分支2 (已关闭)
- 步骤3：分支缺失处理属性999 @0
- 步骤4：关闭分支处理属性4 @分支4 (已关闭)
- 步骤5：主分支处理 @主干

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->loadYaml('case_assignbranchforedit', false, 2);
$caseTable->gen(5);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_assignbranchforedit', false, 2);
$branchTable->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：execution模式下分配分支选项
$case1 = new stdclass();
$case1->product = 1;
$case1->project = 1;
$case1->branch = 1;
r($testcaseTest->assignBranchForEditTest($case1, 1, 'execution')) && p('1') && e('分支1'); // 步骤1：execution模式正常情况

// 步骤2：project模式下分配分支选项
$case2 = new stdclass();
$case2->product = 1;
$case2->project = 2;
$case2->branch = 2;
r($testcaseTest->assignBranchForEditTest($case2, 0, 'project')) && p('2') && e('分支2 (已关闭)'); // 步骤2：project模式正常情况

// 步骤3：当前用例分支不在分支列表中时的处理
$case3 = new stdclass();
$case3->product = 2;
$case3->project = 1;
$case3->branch = 999;
r($testcaseTest->assignBranchForEditTest($case3, 1, 'execution')) && p('999') && e('0'); // 步骤3：分支缺失处理

// 步骤4：包含已关闭状态分支的处理
$case4 = new stdclass();
$case4->product = 1;
$case4->project = 1;
$case4->branch = 4;
r($testcaseTest->assignBranchForEditTest($case4, 1, 'execution')) && p('4') && e('分支4 (已关闭)'); // 步骤4：关闭分支处理

// 步骤5：测试主分支(BRANCH_MAIN)的特殊处理
$case5 = new stdclass();
$case5->product = 1;
$case5->project = 1;
$case5->branch = 0;
r($testcaseTest->assignBranchForEditTest($case5, 1, 'execution')) && p('0') && e('主干'); // 步骤5：主分支处理