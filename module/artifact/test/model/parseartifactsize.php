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

$tester->loadModel('artifact');


$testObj = new artifactModelTest();
/**

title=测试 artifactModel::parseArtifactSize()
timeout=0
cid=0

- 步骤1：正常输入 @1B
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @error:TypeError
- 步骤4：大值输入 @976.56KB
- 步骤5：业务规则验证 @error:TypeError

*/

r($testObj->parseArtifactSizeTest('1')) && p() && e('1B'); // 步骤1：正常输入
$result = $testObj->parseArtifactSizeTest('');
r($result === '') && p() && e('1'); // 步骤2：边界值输入
r($testObj->parseArtifactSizeTest('abc')) && p() && e('error:TypeError'); // 步骤3：无效输入
r($testObj->parseArtifactSizeTest('999999')) && p() && e('976.56KB'); // 步骤4：大值输入
r($testObj->parseArtifactSizeTest('test')) && p() && e('error:TypeError'); // 步骤5：业务规则验证
