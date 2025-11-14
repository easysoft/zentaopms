#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

/**

title=测试 testcaseModel->appendSteps();
cid=18953

- 测试添加原有步骤数量 0 @3

- 测试添加原有步骤数量 1 @3

- 测试添加原有步骤数量 2 @3

- 测试添加原有步骤数量 3 @3

- 测试添加原有步骤数量 0 count为1 @1

- 测试添加原有步骤数量 1 count为1 @1

- 测试添加原有步骤数量 2 count为1 @2

- 测试添加原有步骤数量 3 count为1 @3

*/

$step1 = new stdclass();
$step1->name   = '';
$step1->step   = '';
$step1->desc   = '';
$step1->expect = '';
$step1->type   = 'step';

$step2 = new stdclass();
$step2->name   = '';
$step2->step   = '';
$step2->desc   = '';
$step2->expect = '';
$step2->type   = 'group';

$step3 = new stdclass();
$step3->name   = '';
$step3->step   = '';
$step3->desc   = '';
$step3->expect = '';
$step3->type   = 'item';

$stepsList = array(array(), array($step1), array($step2, $step3), array($step1, $step2, $step3));

$count = 1;

$testcase = new testcaseTest();

r($testcase->appendStepsTest($stepsList[0]))         && p() && e('3'); // 测试添加原有步骤数量 0
r($testcase->appendStepsTest($stepsList[1]))         && p() && e('3'); // 测试添加原有步骤数量 1
r($testcase->appendStepsTest($stepsList[2]))         && p() && e('3'); // 测试添加原有步骤数量 2
r($testcase->appendStepsTest($stepsList[3]))         && p() && e('3'); // 测试添加原有步骤数量 3
r($testcase->appendStepsTest($stepsList[0], $count)) && p() && e('1'); // 测试添加原有步骤数量 0 count为1
r($testcase->appendStepsTest($stepsList[1], $count)) && p() && e('1'); // 测试添加原有步骤数量 1 count为1
r($testcase->appendStepsTest($stepsList[2], $count)) && p() && e('2'); // 测试添加原有步骤数量 2 count为1
r($testcase->appendStepsTest($stepsList[3], $count)) && p() && e('3'); // 测试添加原有步骤数量 3 count为1
