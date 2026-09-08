#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

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

$tester->loadModel('user');
helper::import($tester->app->getModulePath('', 'user') . 'control.php');
helper::import($tester->app->getModulePath('', 'user') . 'zen.php');


$testObj = new userZenTest();
/**

title=测试 userModel::parseLoginModuleAndMethod()
timeout=0
cid=0

- 步骤1：正常输入 @,
- 步骤2：边界值输入 @,
- 步骤3：无效输入 @,
- 步骤4：大值输入 @,
- 步骤5：业务规则验证 @,

*/

$result = $testObj->parseLoginModuleAndMethodTest('1');
r(implode(',', $result)) && p() && e(','); // 步骤1：正常输入
$result = $testObj->parseLoginModuleAndMethodTest('');
r(implode(',', $result)) && p() && e(','); // 步骤2：边界值输入
$result = $testObj->parseLoginModuleAndMethodTest('abc');
r(implode(',', $result)) && p() && e(','); // 步骤3：无效输入
$result = $testObj->parseLoginModuleAndMethodTest('999999');
r(implode(',', $result)) && p() && e(','); // 步骤4：大值输入
$result = $testObj->parseLoginModuleAndMethodTest('test');
r(implode(',', $result)) && p() && e(','); // 步骤5：业务规则验证
