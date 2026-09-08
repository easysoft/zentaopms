#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_workflow = zenData('workflow');
$zd_workflow->id->range('1-5');
$zd_workflow->group->range('1-5');
$zd_workflow->app->range('1-5');
$zd_workflow->module->range('1-5');
$zd_workflow->vision->range('1-5');
$zd_workflow->buildin->range('0');
$zd_workflow->gen(5);
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


$testObj = new aiModelTest();
/**

title=测试 aiModel::getTargetFormModuleLabel()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

$result = $testObj->getTargetFormModuleLabelTest('1');
r($result === '') && p() && e('1'); // 步骤1：正常输入
$result = $testObj->getTargetFormModuleLabelTest('');
r($result === '') && p() && e('1'); // 步骤2：边界值输入
$result = $testObj->getTargetFormModuleLabelTest('abc');
r($result === '') && p() && e('1'); // 步骤3：无效输入
$result = $testObj->getTargetFormModuleLabelTest('999999');
r($result === '') && p() && e('1'); // 步骤4：大值输入
$result = $testObj->getTargetFormModuleLabelTest('test');
r($result === '') && p() && e('1'); // 步骤5：业务规则验证
