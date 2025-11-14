#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processStepsAndExpectsForBatchEdit();
timeout=0
cid=19106

- 执行testcaseTest模块的processStepsAndExpectsForBatchEditTest方法，参数是$multipleCases  @2
- 执行testcaseTest模块的processStepsAndExpectsForBatchEditTest方法，参数是$emptyCases  @0
- 执行testcaseTest模块的processStepsAndExpectsForBatchEditTest方法，参数是$singleCase  @1
- 执行testcaseTest模块的processStepsAndExpectsForBatchEditTest方法，参数是$invalidCases  @1
- 执行steps) && isset($result[1]模块的expects方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zenData('case')->loadYaml('case', false, 2)->gen(10);
zenData('casestep')->loadYaml('casestep', false, 2)->gen(20);
zenData('module')->loadYaml('module', false, 2)->gen(10);

su('admin');

$testcaseTest = new testcaseZenTest();

// 准备测试数据
$case1 = new stdClass();
$case1->id = 1;
$case1->title = '测试用例1';
$case1->stepDesc = '';
$case1->stepExpect = '';

$case2 = new stdClass();
$case2->id = 2;
$case2->title = '测试用例2';
$case2->stepDesc = '';
$case2->stepExpect = '';

$case3 = new stdClass();
$case3->id = 3;
$case3->title = '测试用例3';
$case3->stepDesc = '';
$case3->stepExpected = '';

$invalidCase = new stdClass();
$invalidCase->id = 999;
$invalidCase->title = '不存在的用例';
$invalidCase->stepDesc = '';
$invalidCase->stepExpect = '';

// 测试步骤1：处理包含多个测试用例的数组
$multipleCases = array(1 => $case1, 2 => $case2);
r(count($testcaseTest->processStepsAndExpectsForBatchEditTest($multipleCases))) && p() && e('2');

// 测试步骤2：处理空数组
$emptyCases = array();
r(count($testcaseTest->processStepsAndExpectsForBatchEditTest($emptyCases))) && p() && e('0');

// 测试步骤3：处理单个测试用例
$singleCase = array(1 => $case1);
r(count($testcaseTest->processStepsAndExpectsForBatchEditTest($singleCase))) && p() && e('1');

// 测试步骤4：处理包含无效ID的测试用例
$invalidCases = array(999 => $invalidCase);
r(is_array($testcaseTest->processStepsAndExpectsForBatchEditTest($invalidCases))) && p() && e('1');

// 测试步骤5：验证步骤和预期结果被正确设置
$testCases = array(1 => $case1);
$result = $testcaseTest->processStepsAndExpectsForBatchEditTest($testCases);
r(isset($result[1]->steps) && isset($result[1]->expects)) && p() && e('1');