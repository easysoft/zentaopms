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

$tester->loadModel('chart');


$testObj = new chartModelTest();
/**

title=测试 chartModel::getMultiData()
timeout=0
cid=0

- 步骤1：正常输入 @5
- 步骤2：边界值输入 @5
- 步骤3：无效输入 @5
- 步骤4：大值输入 @5
- 步骤5：业务规则验证 @5

*/

$result = $testObj->getMultiDataTest(array(), '1', array(), '1', false);
r(count($result)) && p() && e('5'); // 步骤1：正常输入
$result = $testObj->getMultiDataTest(array(), '1', array(), '1', false);
r(count($result)) && p() && e('5'); // 步骤2：边界值输入
$result = $testObj->getMultiDataTest(array(-1), '1', array(), '1', false);
r(count($result)) && p() && e('5'); // 步骤3：无效输入
$result = $testObj->getMultiDataTest(array(999999), '1', array(), '1', false);
r(count($result)) && p() && e('5'); // 步骤4：大值输入
$result = $testObj->getMultiDataTest(array(1, 2), 'test', array(), '1', false);
r(count($result)) && p() && e('5'); // 步骤5：业务规则验证
