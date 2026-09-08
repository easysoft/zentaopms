#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

error_reporting(E_ERROR);

zenData('tree')->gen(0);

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

su('admin');

$tester->loadModel('pivot');
helper::import($tester->app->getModulePath('', 'pivot') . 'control.php');
helper::import($tester->app->getModulePath('', 'pivot') . 'zen.php');
$pivotTest = new pivotZenTest();

/**

title=测试 pivotModel::getDefaultMethodAndParams()
timeout=0
cid=0

- 步骤1：正常输入 @,
- 步骤2：边界值输入 @,
- 步骤3：无效输入 @,
- 步骤4：大值输入 @,
- 步骤5：业务规则验证 @,

*/

$result = $pivotTest->getDefaultMethodAndParamsTest(1, 1);
r(implode(',', $result)) && p() && e(','); // 步骤1：正常输入
$result = $pivotTest->getDefaultMethodAndParamsTest(0, 1);
r(implode(',', $result)) && p() && e(','); // 步骤2：边界值输入
$result = $pivotTest->getDefaultMethodAndParamsTest(-1, 1);
r(implode(',', $result)) && p() && e(','); // 步骤3：无效输入
$result = $pivotTest->getDefaultMethodAndParamsTest(999999, 1);
r(implode(',', $result)) && p() && e(','); // 步骤4：大值输入
$result = $pivotTest->getDefaultMethodAndParamsTest(2, 2);
r(implode(',', $result)) && p() && e(','); // 步骤5：业务规则验证
