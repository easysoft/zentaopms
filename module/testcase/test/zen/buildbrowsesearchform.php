#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

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

$tester->loadModel('testcase');
helper::import($tester->app->getModulePath('', 'testcase') . 'control.php');
helper::import($tester->app->getModulePath('', 'testcase') . 'zen.php');


$testObj = new testcaseZenTest();
/**

title=测试 testcaseModel::buildBrowseSearchForm()
timeout=0
cid=0

- 步骤1：正常输入 @error:Error
- 步骤2：边界值输入 @error:Error
- 步骤3：无效输入 @error:Error
- 步骤4：大值输入 @error:Error
- 步骤5：业务规则验证 @error:Error

*/

r($testObj->buildBrowseSearchFormTest(1, 1, 1, '1')) && p() && e('error:Error'); // 步骤1：正常输入
r($testObj->buildBrowseSearchFormTest(0, 1, 1, '1')) && p() && e('error:Error'); // 步骤2：边界值输入
r($testObj->buildBrowseSearchFormTest(-1, 1, 1, '1')) && p() && e('error:Error'); // 步骤3：无效输入
r($testObj->buildBrowseSearchFormTest(999999, 1, 1, '1')) && p() && e('error:Error'); // 步骤4：大值输入
r($testObj->buildBrowseSearchFormTest(2, 2, 1, '1')) && p() && e('error:Error'); // 步骤5：业务规则验证
