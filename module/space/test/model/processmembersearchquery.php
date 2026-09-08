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

$tester->loadModel('space');


$testObj = new spaceModelTest();
/**

title=测试 spaceModel::processMemberSearchQuery()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @abc
- 步骤4：大值输入 @999999
- 步骤5：业务规则验证 @test

*/

r($testObj->processMemberSearchQueryTest('1', array())) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->processMemberSearchQueryTest('', array());
r($result === '') && p() && e('1'); // 步骤2：边界值输入
r($testObj->processMemberSearchQueryTest('abc', array())) && p() && e('abc'); // 步骤3：无效输入
r($testObj->processMemberSearchQueryTest('999999', array())) && p() && e('999999'); // 步骤4：大值输入
r($testObj->processMemberSearchQueryTest('test', array(1, 2))) && p() && e('test'); // 步骤5：业务规则验证
