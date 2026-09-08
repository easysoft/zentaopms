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

$tester->loadModel('ai');


$testObj = new aiModelTest();
/**

title=测试 aiModel::getPromptDesignAction()
timeout=0
cid=0

- 步骤1：正常输入 @promptbasicinfo
- 步骤2：边界值输入 @promptbasicinfo
- 步骤3：无效输入 @promptbasicinfo
- 步骤4：大值输入 @promptbasicinfo
- 步骤5：业务规则验证 @promptbasicinfo

*/

r($testObj->getPromptDesignActionTest(1)) && p() && e('promptbasicinfo'); // 步骤1：正常输入
r($testObj->getPromptDesignActionTest(0)) && p() && e('promptbasicinfo'); // 步骤2：边界值输入
r($testObj->getPromptDesignActionTest('abc')) && p() && e('promptbasicinfo'); // 步骤3：无效输入
r($testObj->getPromptDesignActionTest(999999)) && p() && e('promptbasicinfo'); // 步骤4：大值输入
r($testObj->getPromptDesignActionTest('test')) && p() && e('promptbasicinfo'); // 步骤5：业务规则验证
