#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

error_reporting(E_ERROR);

$zd_task = zenData('task');
$zd_task->id->range('1-5');
$zd_task->parent->range('0');
$zd_task->isParent->range('0');
$zd_task->isTpl->range('0');
$zd_task->design->range('0');
$zd_task->designVersion->range('0');
$zd_task->fromBug->range('0');
$zd_task->feedback->range('0');
$zd_task->fromIssue->range('0');
$zd_task->finishedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->canceledDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->order->range('0');
$zd_task->repo->range('0');
$zd_task->mr->range('0');
$zd_task->deleted->range('0');
$zd_task->gen(5);
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

$tester->loadModel('task');
helper::import($tester->app->getModulePath('', 'task') . 'control.php');
helper::import($tester->app->getModulePath('', 'task') . 'zen.php');


$testObj = new taskZenTest();
/**

title=测试 taskModel::getParentEstStartedAndDeadline()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @2

*/

$result = $testObj->getParentEstStartedAndDeadlineTest(array());
r(count($result)) && p() && e('0'); // 步骤1：正常输入
$result = $testObj->getParentEstStartedAndDeadlineTest(array());
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->getParentEstStartedAndDeadlineTest(array(-1));
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->getParentEstStartedAndDeadlineTest(array(999999));
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->getParentEstStartedAndDeadlineTest(array(1, 2));
r(count($result)) && p() && e('2'); // 步骤5：业务规则验证
