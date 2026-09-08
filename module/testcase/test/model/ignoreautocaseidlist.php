#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_case = zenData('case');
$zd_case->id->range('1-5');
$zd_case->project->range('0');
$zd_case->branch->range('0');
$zd_case->lib->range('0');
$zd_case->module->range('0');
$zd_case->fromBug->range('0');
$zd_case->fromCaseID->range('0');
$zd_case->scene->range('0');
$zd_case->sort->range('0');
$zd_case->gen(5);
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


$testObj = new testcaseModelTest();
/**

title=测试 testcaseModel::ignoreAutoCaseIdList()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @1,2

*/

$result = $testObj->ignoreAutoCaseIdListTest(array());
r(count($result)) && p() && e('0'); // 步骤1：正常输入
$result = $testObj->ignoreAutoCaseIdListTest(array());
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->ignoreAutoCaseIdListTest(array(-1));
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->ignoreAutoCaseIdListTest(array(999999));
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->ignoreAutoCaseIdListTest(array(1, 2));
r(implode(',', $result)) && p() && e('1,2'); // 步骤5：业务规则验证
