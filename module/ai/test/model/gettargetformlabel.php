#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

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

title=测试 aiModel::getTargetFormLabel()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @abc
- 步骤4：大值输入 @999999
- 步骤5：业务规则验证 @test
- 步骤6：无需返回禅道表单 @无需返回禅道表单

*/

r($testObj->getTargetFormLabelTest('1', true, '')) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->getTargetFormLabelTest('', true, '');
r($result === '') && p() && e('1'); // 步骤2：边界值输入
r($testObj->getTargetFormLabelTest('abc', true, '')) && p() && e('abc'); // 步骤3：无效输入
r($testObj->getTargetFormLabelTest('999999', true, '')) && p() && e('999999'); // 步骤4：大值输入
r($testObj->getTargetFormLabelTest('test', true, '')) && p() && e('test'); // 步骤5：业务规则验证
r($testObj->getTargetFormLabelTest('empty.empty', true, '')) && p() && e('无需返回禅道表单'); // 步骤6：无需返回禅道表单
