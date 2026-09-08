#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

zenData('product')->loadYaml('product', false, 2)->gen(3);

su('admin');

$tester->loadModel('testreport');
helper::import($tester->app->getModulePath('', 'testreport') . 'control.php');
helper::import($tester->app->getModulePath('', 'testreport') . 'zen.php');


$testObj = new testreportZenTest();
/**

title=测试 testreportModel::commonAction()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @error:TypeError

*/

r($testObj->commonActionTest(1, 'product')) && p() && e('1'); // 步骤1：正常输入
r($testObj->commonActionTest(0, 'product')) && p() && e('1'); // 步骤2：边界值输入
r($testObj->commonActionTest(-1, 'product')) && p() && e('1'); // 步骤3：无效输入
r($testObj->commonActionTest(999999, 'product')) && p() && e('1'); // 步骤4：大值输入
r($testObj->commonActionTest(2, 'test')) && p() && e('error:TypeError'); // 步骤5：业务规则验证
