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

$tester->loadModel('project');
helper::import($tester->app->getModulePath('', 'project') . 'control.php');
helper::import($tester->app->getModulePath('', 'project') . 'zen.php');


$testObj = new projectZenTest();
/**

title=测试 projectModel::buildMembers()
timeout=0
cid=0

- 步骤1：正常输入 @5
- 步骤2：边界值输入 @5
- 步骤3：无效输入 @error:Error
- 步骤4：大值输入 @error:Error
- 步骤5：业务规则验证 @error:Error

*/

$result = $testObj->buildMembersTest(array(), array(), array(), 1);
r(count($result)) && p() && e('5'); // 步骤1：正常输入
$result = $testObj->buildMembersTest(array(), array(), array(), 1);
r(count($result)) && p() && e('5'); // 步骤2：边界值输入
r($testObj->buildMembersTest(array(-1), array(), array(), 1)) && p() && e('error:Error'); // 步骤3：无效输入
r($testObj->buildMembersTest(array(999999), array(), array(), 1)) && p() && e('error:Error'); // 步骤4：大值输入
r($testObj->buildMembersTest(array(1, 2), array(1, 2), array(), 1)) && p() && e('error:Error'); // 步骤5：业务规则验证
