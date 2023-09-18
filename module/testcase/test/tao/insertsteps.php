#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('user')->gen('1');
zdTable('case')->gen('0');
zdTable('casestep')->gen('0');

su('admin');

/**

title=测试 testcaseModel->insertSteps();
timeout=0
cid=1

- 测试插入用例 1 的用例步骤 @1,2,3

- 测试插入用例 2 的用例步骤 @4,5,6

- 测试插入用例 3 的用例步骤 @7,8,9,10,11,12

*/

$steps1     = array('1' => 'step1', '2' => 'step2', '3' => 'step3');
$expects1   = array('1' => 'expect1', '2' => 'expect2', '3' => 'expect3');
$stepTypes1 = array('1' => 'step', '2' => 'step', '3' => 'step');

$steps2     = array('1' => 'group1', '1.1' => 'group1.1', '1.1.1' => 'item1.1.1');
$expects2   = array('1.1.1' => 'expect1.1.1');
$stepTypes2 = array('1' => 'group', '1.1' => 'group', '1.1.1' => 'item');

$steps3     = array('1' => 'group1', '1.1' => 'group1.1', '1.1.1' => 'item1.1.1', '2' => 'group2', '2.1' => 'item2.1', '3' => 'step3');
$expects3   = array('1.1.1' => 'expect1.1.1', '2.1' => 'expect2.1', '3' => 'expect3');
$stepTypes3 = array('1' => 'group', '1.1' => 'group', '1.1.1' => 'item','2' => 'group', '2.1' => 'item', '3' => 'step');

$caseIdList = array(1, 2, 3);

$testcase = new testcaseTest();

r($testcase->insertStepsTest($caseIdList[0], $steps1, $expects1, $stepTypes1)) && p() && e('1,2,3');          // 测试插入用例 1 的用例步骤
r($testcase->insertStepsTest($caseIdList[1], $steps2, $expects2, $stepTypes2)) && p() && e('4,5,6');          // 测试插入用例 2 的用例步骤
r($testcase->insertStepsTest($caseIdList[2], $steps3, $expects3, $stepTypes3)) && p() && e('7,8,9,10,11,12'); // 测试插入用例 3 的用例步骤