#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_workflowaction = zenData('workflowaction');
$zd_workflowaction->id->range('1-5');
$zd_workflowaction->group->range('1-5');
$zd_workflowaction->module->range('1-5');
$zd_workflowaction->action->range('1-5');
$zd_workflowaction->vision->range('1-5');
$zd_workflowaction->order->range('0');
$zd_workflowaction->buildin->range('0');
$zd_workflowaction->virtual->range('0');
$zd_workflowaction->createdDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workflowaction->editedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workflowaction->gen(5);
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

$tester->loadModel('ai');


/**

title=测试 aiModel::getWorkflowActionForPrompt()
timeout=0
cid=0

- 步骤1：正常输入：存在工作流动作 @1
- 步骤2：边界值输入：空模块无动作 @1
- 步骤3：无效输入：不存在的模块无动作 @1
- 步骤4：大值输入：不存在的模块无动作 @1
- 步骤5：业务规则验证：不存在动作返回 false @1

*/

r(is_object($tester->ai->getWorkflowActionForPrompt('1', '1'))) && p() && e('1'); // 步骤1：正常输入：存在工作流动作
r($tester->ai->getWorkflowActionForPrompt('', '1') === false) && p() && e('1'); // 步骤2：边界值输入：空模块无动作
r($tester->ai->getWorkflowActionForPrompt('abc', '1') === false) && p() && e('1'); // 步骤3：无效输入：不存在的模块无动作
r($tester->ai->getWorkflowActionForPrompt('999999', '1') === false) && p() && e('1'); // 步骤4：大值输入：不存在的模块无动作
r($tester->ai->getWorkflowActionForPrompt('test', 'test') === false) && p() && e('1'); // 步骤5：业务规则验证：不存在动作返回 false
