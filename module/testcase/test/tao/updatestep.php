#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('case')->gen(3);
zenData('casestep')->gen(10);
zenData('story')->gen(10);

/**

title=测试 testcaseTao->updateStep();
timeout=0
cid=19055

- 测试更新步骤的版本号
 - 第0条的version属性 @2
 - 第0条的desc属性 @用例步骤描述1
 - 第0条的type属性 @step
- 测试更新新的步骤
 - 第0条的version属性 @3
 - 第0条的desc属性 @step3Updated
 - 第0条的type属性 @step

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

$case1 = new stdclass();
$case1->version = 2;
$case1->steps   = array();
$case1->id      = 1;

$case2 = new stdclass();
$case2->version  = 3;
$case2->steps    = array('1' => 'step1Updated', '2' => 'step2Updated', '3' => 'step3Updated');
$case2->expects  = array('1' => 'expect1Updated', '2' => 'expect2Updated', '3' => 'expect3Updated');
$case2->stepType = array('1' => 'step', '2' => 'step', '3' => 'step');
$case2->id       = 1;

$testcase = new testcaseTaoTest();

r($testcase->updateStepTest($caseIdList[0], $case1)) && p('1:version,desc,type') && e('2,用例步骤描述1,step'); // 测试更新步骤的版本号
r($testcase->updateStepTest($caseIdList[0], $case2)) && p('1:version,desc,type') && e('3,step3Updated,step');  // 测试更新新的步骤
