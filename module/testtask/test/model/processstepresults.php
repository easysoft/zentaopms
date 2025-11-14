#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::processStepResults();
timeout=0
cid=19217

- 步骤1：用例有步骤，结果为pass第1条的result属性 @pass
- 步骤2：用例有步骤，结果为fail第1条的result属性 @fail
- 步骤3：用例无步骤，结果为pass第0条的result属性 @pass
- 步骤4：用例无步骤，结果为fail第0条的result属性 @fail
- 步骤5：空步骤数组测试 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备
$casestepTable = zenData('casestep');
$casestepTable->id->range('1-6');
$casestepTable->case->range('1{3},2{2},3{1}');
$casestepTable->version->range('1{6}');
$casestepTable->type->range('step{6}');
$casestepTable->gen(6);

$caseTable = zenData('case');
$caseTable->id->range('1-5');
$caseTable->status->range('normal{5}');
$caseTable->version->range('1{5}');
$caseTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskTest();

// 5. 执行测试步骤
r($testtaskTest->processStepResultsTest([1, 2], 1, 'pass', [], [])) && p('1:result') && e('pass'); // 步骤1：用例有步骤，结果为pass
r($testtaskTest->processStepResultsTest([1, 2], 1, 'fail', [1 => 'fail', 2 => 'pass', 3 => 'fail'], [1 => '实际结果1'])) && p('1:result') && e('fail'); // 步骤2：用例有步骤，结果为fail  
r($testtaskTest->processStepResultsTest([1, 2], 4, 'pass', [], [])) && p('0:result') && e('pass'); // 步骤3：用例无步骤，结果为pass
r($testtaskTest->processStepResultsTest([1, 2], 4, 'fail', [], ['测试失败原因'])) && p('0:result') && e('fail'); // 步骤4：用例无步骤，结果为fail
r($testtaskTest->processStepResultsTest([], 1, 'pass', [], [])) && p() && e('Array'); // 步骤5：空步骤数组测试