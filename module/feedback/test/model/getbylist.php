#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_feedback = zenData('feedback');
$zd_feedback->id->range('1-5');
$zd_feedback->pri->range('0');
$zd_feedback->notify->range('0');
$zd_feedback->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_feedback->repeatFeedback->range('0');
$zd_feedback->deleted->range('0');
$zd_feedback->gen(5);
$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('feedback');


$testObj = new feedbackModelTest();
/**

title=测试 feedbackModel::getByList()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

$result = $testObj->getByListTest(1);
r(count($result)) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->getByListTest(0);
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->getByListTest('abc');
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->getByListTest(999999);
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->getByListTest('test');
r(count($result)) && p() && e('0'); // 步骤5：业务规则验证
