#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::parseZTFFuncResult();
timeout=0
cid=19212

- 步骤1：正常情况 @1
- 步骤2：多个用例 @2
- 步骤3：空数组 @0
- 步骤4：失败情况 @fail
- 步骤5：缺少步骤数据 @No Steps Case

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->account->range('admin,user1,user2');
$table->realname->range('管理员,用户1,用户2');
$table->password->range('123456{3}');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用例结果数据 - 正常情况
$normalCaseResults = array();
$caseResult1 = new stdclass();
$caseResult1->title = 'Test Case 1';
$caseResult1->id = 1;
$caseResult1->steps = array();

$step1 = new stdclass();
$step1->name = 'Step 1';
$step1->status = 'pass';
$step1->checkPoints = array();
$checkPoint1 = new stdclass();
$checkPoint1->expect = 'Expected result 1';
$checkPoint1->actual = 'Actual result 1';
$step1->checkPoints[] = $checkPoint1;
$caseResult1->steps[] = $step1;

$normalCaseResults[] = $caseResult1;

$result1 = $testtaskTest->parseZTFFuncResultTest($normalCaseResults, 'phpunit', 1, 1, 1);
r(count($result1['cases'])) && p() && e('1'); // 步骤1：正常情况

// 创建多个测试用例数据
$multipleCaseResults = array();
$caseResult2 = new stdclass();
$caseResult2->title = 'Test Case 2';
$caseResult2->id = 2;
$caseResult2->steps = array();

$step2 = new stdclass();
$step2->name = 'Step 2';
$step2->status = 'pass';
$step2->checkPoints = array();
$checkPoint2 = new stdclass();
$checkPoint2->expect = 'Expected result 2';
$checkPoint2->actual = 'Actual result 2';
$step2->checkPoints[] = $checkPoint2;
$caseResult2->steps[] = $step2;

$multipleCaseResults[] = $caseResult1;
$multipleCaseResults[] = $caseResult2;

$result2 = $testtaskTest->parseZTFFuncResultTest($multipleCaseResults, 'phpunit', 1, 1, 1);
r(isset($result2['cases'][0]) ? count($result2['cases'][0]) : 0) && p() && e('2'); // 步骤2：多个用例

// 空测试结果
$emptyCaseResults = array();

$result3 = $testtaskTest->parseZTFFuncResultTest($emptyCaseResults, 'phpunit', 1, 1, 1);
r(count($result3['cases'])) && p() && e('0'); // 步骤3：空数组

// 包含失败步骤的测试结果
$failCaseResults = array();
$failCaseResult = new stdclass();
$failCaseResult->title = 'Fail Test Case';
$failCaseResult->id = 3;
$failCaseResult->steps = array();

$failStep = new stdclass();
$failStep->name = 'Fail Step';
$failStep->status = 'fail';
$failStep->checkPoints = array();
$failCheckPoint = new stdclass();
$failCheckPoint->expect = 'Expected result';
$failCheckPoint->actual = 'Different result';
$failStep->checkPoints[] = $failCheckPoint;
$failCaseResult->steps[] = $failStep;

$failCaseResults[] = $failCaseResult;

$result4 = $testtaskTest->parseZTFFuncResultTest($failCaseResults, 'phpunit', 1, 1, 1);
r(isset($result4['results'][0][0]->caseResult) ? $result4['results'][0][0]->caseResult : 'empty') && p() && e('fail'); // 步骤4：失败情况

// 缺少步骤数据的测试结果
$noStepsCaseResults = array();
$noStepsCase = new stdclass();
$noStepsCase->title = 'No Steps Case';
$noStepsCase->id = 4;
// 不设置steps属性

$noStepsCaseResults[] = $noStepsCase;

$result5 = $testtaskTest->parseZTFFuncResultTest($noStepsCaseResults, 'phpunit', 1, 1, 1);
r(isset($result5['cases'][0][0]->title) ? $result5['cases'][0][0]->title : 'empty') && p() && e('No Steps Case'); // 步骤5：缺少步骤数据