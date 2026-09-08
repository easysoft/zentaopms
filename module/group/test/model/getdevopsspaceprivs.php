#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_group = zenData('group');
$zd_group->id->range('1-5');
$zd_group->devopsSpace->range('0');
$zd_group->gen(5);
$zd_usergroup = zenData('usergroup');
$zd_usergroup->id->range('1-5');
$zd_usergroup->account->range('1-5');
$zd_usergroup->group->range('1-5');
$zd_usergroup->gen(5);
$zd_grouppriv = zenData('grouppriv');
$zd_grouppriv->id->range('1-5');
$zd_grouppriv->group->range('1-5');
$zd_grouppriv->module->range('1-5');
$zd_grouppriv->method->range('1-5');
$zd_grouppriv->gen(5);
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

$tester->loadModel('group');


$testObj = new groupModelTest();
/**

title=测试 groupModel::getDevOpsSpacePrivs()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

$result = $testObj->getDevOpsSpacePrivsTest(1);
r($result === null) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->getDevOpsSpacePrivsTest(0);
r($result === null) && p() && e('1'); // 步骤2：边界值输入
$result = $testObj->getDevOpsSpacePrivsTest(-1);
r($result === null) && p() && e('1'); // 步骤3：无效输入
$result = $testObj->getDevOpsSpacePrivsTest(999999);
r($result === null) && p() && e('1'); // 步骤4：大值输入
$result = $testObj->getDevOpsSpacePrivsTest(2);
r($result === null) && p() && e('1'); // 步骤5：业务规则验证
