#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildUpdateCaseForShowImport();
timeout=0
cid=0

- 步骤1：步骤数量相同且内容未变化 @0
- 执行testcaseTest模块的buildUpdateCaseForShowImportTest方法，参数是$caseChanged, $oldCase, $oldStep, false  @1
- 执行testcaseTest模块的buildUpdateCaseForShowImportTest方法，参数是$caseDescChanged, $oldCase, $oldStep, false  @1
- 执行testcaseTest模块的buildUpdateCaseForShowImportTest方法，参数是$caseExpectChanged, $oldCase, $oldStep, false  @1
- 执行$result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->product->range('1-3');
$caseTable->title->range('测试用例{1-10}');
$caseTable->version->range('1-5');
$caseTable->status->range('normal{5},blocked{3},investigate{2}');
$caseTable->gen(10);

$stepTable = zenData('casestep');
$stepTable->id->range('1-20');
$stepTable->case->range('1-10');
$stepTable->version->range('1-5');
$stepTable->type->range('step{15},group{3},item{2}');
$stepTable->desc->range('打开页面,输入用户名,输入密码,点击登录,验证结果');
$stepTable->expect->range('页面正常显示,用户名输入成功,密码输入成功,登录成功,验证通过');
$stepTable->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 构建测试数据
// 构建新的case对象
$case = new stdClass();
$case->desc = array('打开页面', '输入用户名', '输入密码');
$case->expect = array('页面正常显示', '用户名输入成功', '密码输入成功');
$case->stepType = array('step', 'step', 'step');

// 构建旧的case对象
$oldCase = new stdClass();
$oldCase->version = 1;
$oldCase->status = 'normal';

// 构建旧的步骤数组
$oldStep = array();
$step1 = new stdClass();
$step1->desc = '打开页面';
$step1->expect = '页面正常显示';
$step1->type = 'step';
$oldStep[] = $step1;

$step2 = new stdClass();
$step2->desc = '输入用户名';
$step2->expect = '用户名输入成功';
$step2->type = 'step';
$oldStep[] = $step2;

$step3 = new stdClass();
$step3->desc = '输入密码';
$step3->expect = '密码输入成功';
$step3->type = 'step';
$oldStep[] = $step3;

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->buildUpdateCaseForShowImportTest($case, $oldCase, $oldStep, false)) && p() && e('0'); // 步骤1：步骤数量相同且内容未变化

// 步骤2：步骤数量发生变化的情况
$caseChanged = clone $case;
$caseChanged->desc[] = '点击登录';
$caseChanged->expect[] = '登录成功';
$caseChanged->stepType[] = 'step';
r($testcaseTest->buildUpdateCaseForShowImportTest($caseChanged, $oldCase, $oldStep, false)) && p() && e('1');

// 步骤3：步骤数量相同但描述内容发生变化
$caseDescChanged = clone $case;
$caseDescChanged->desc[0] = '打开登录页面';
r($testcaseTest->buildUpdateCaseForShowImportTest($caseDescChanged, $oldCase, $oldStep, false)) && p() && e('1');

// 步骤4：步骤数量相同但期望内容发生变化
$caseExpectChanged = clone $case;
$caseExpectChanged->expect[0] = '登录页面正常显示';
r($testcaseTest->buildUpdateCaseForShowImportTest($caseExpectChanged, $oldCase, $oldStep, false)) && p() && e('1');

// 步骤5：forceNotReview为true时不改变状态
$caseForceNotReview = clone $caseDescChanged;
$oldStatusExists = isset($caseForceNotReview->status);
$result = $testcaseTest->buildUpdateCaseForShowImportTest($caseForceNotReview, $oldCase, $oldStep, true);
r($result) && p() && e('1');