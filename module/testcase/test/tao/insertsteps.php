#!/usr/bin/env php
<?php

/**

title=测试 testcaseTao::insertSteps();
timeout=0
cid=19049

- 测试插入基础步骤（简单步骤类型） @1,2,3

- 测试插入层级结构步骤（组+项目+步骤） @4,5,6

- 测试插入复杂层级结构（多层组和项目嵌套） @7,8,9,10,11,12

- 测试插入包含空步骤的数据（跳过空步骤，仍然连续ID） @13,14,15

- 测试插入指定版本号的步骤 @16,17

- 测试插入只有组类型的步骤 @18,19

- 测试边界情况（空数组） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('0');
zenData('casestep')->gen('0');

su('admin');

// 准备测试数据
$caseIdList = array(1, 2, 3, 4, 5, 6, 7, 8);

// 测试数据1：基础步骤（简单步骤类型）
$steps1     = array('1' => 'step1', '2' => 'step2', '3' => 'step3');
$expects1   = array('1' => 'expect1', '2' => 'expect2', '3' => 'expect3');
$stepTypes1 = array('1' => 'step', '2' => 'step', '3' => 'step');

// 测试数据2：层级结构步骤（组+项目+步骤）
$steps2     = array('1' => 'group1', '1.1' => 'group1.1', '1.1.1' => 'item1.1.1');
$expects2   = array('1.1.1' => 'expect1.1.1');
$stepTypes2 = array('1' => 'group', '1.1' => 'group', '1.1.1' => 'item');

// 测试数据3：复杂层级结构
$steps3     = array('1' => 'group1', '1.1' => 'group1.1', '1.1.1' => 'item1.1.1', '2' => 'group2', '2.1' => 'item2.1', '3' => 'step3');
$expects3   = array('1.1.1' => 'expect1.1.1', '2.1' => 'expect2.1', '3' => 'expect3');
$stepTypes3 = array('1' => 'group', '1.1' => 'group', '1.1.1' => 'item','2' => 'group', '2.1' => 'item', '3' => 'step');

// 测试数据4：包含空步骤的数据
$steps4     = array('1' => 'step1', '2' => '', '3' => 'step3', '4' => 'step4');
$expects4   = array('1' => 'expect1', '3' => 'expect3', '4' => 'expect4');
$stepTypes4 = array('1' => 'step', '2' => 'step', '3' => 'step', '4' => 'step');

// 测试数据5：指定版本号的步骤
$steps5     = array('1' => 'version2_step1', '2' => 'version2_step2');
$expects5   = array('1' => 'version2_expect1', '2' => 'version2_expect2');
$stepTypes5 = array('1' => 'step', '2' => 'step');

// 测试数据6：只有组类型的步骤
$steps6     = array('1' => 'group1', '2' => 'group2');
$expects6   = array('1' => 'should_be_ignored', '2' => 'should_be_ignored');
$stepTypes6 = array('1' => 'group', '2' => 'group');

// 测试数据7：空数组边界情况
$steps7     = array();
$expects7   = array();
$stepTypes7 = array();

$testcase = new testcaseTest();

r($testcase->insertStepsTest($caseIdList[0], $steps1, $expects1, $stepTypes1)) && p() && e('1,2,3');          // 测试插入基础步骤（简单步骤类型）
r($testcase->insertStepsTest($caseIdList[1], $steps2, $expects2, $stepTypes2)) && p() && e('4,5,6');          // 测试插入层级结构步骤（组+项目+步骤）
r($testcase->insertStepsTest($caseIdList[2], $steps3, $expects3, $stepTypes3)) && p() && e('7,8,9,10,11,12'); // 测试插入复杂层级结构（多层组和项目嵌套）
r($testcase->insertStepsTest($caseIdList[3], $steps4, $expects4, $stepTypes4)) && p() && e('13,14,15');       // 测试插入包含空步骤的数据（跳过空步骤，仍然连续ID）
r($testcase->insertStepsTestWithVersion($caseIdList[4], $steps5, $expects5, $stepTypes5, 2)) && p() && e('16,17'); // 测试插入指定版本号的步骤
r($testcase->insertStepsTest($caseIdList[5], $steps6, $expects6, $stepTypes6)) && p() && e('18,19');          // 测试插入只有组类型的步骤
r($testcase->insertStepsTest($caseIdList[6], $steps7, $expects7, $stepTypes7)) && p() && e('0');               // 测试边界情况（空数组）