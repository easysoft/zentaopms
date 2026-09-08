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

$tester->loadModel('artifact');
helper::import($tester->app->getModulePath('', 'artifact') . 'control.php');
helper::import($tester->app->getModulePath('', 'artifact') . 'zen.php');


$testObj = new artifactZenTest();
/**

title=测试 artifactModel::buildTreeAction()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

$result = $testObj->buildTreeActionTest((object)array(), array());
r(count($result)) && p() && e('0'); // 步骤1：正常输入
$result = $testObj->buildTreeActionTest((object)array(), array());
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->buildTreeActionTest((object)array(), array());
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->buildTreeActionTest((object)array('id' => 999999), array());
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->buildTreeActionTest((object)array('id' => 1, 'name' => 'test'), array(1, 2));
r(count($result)) && p() && e('0'); // 步骤5：业务规则验证
