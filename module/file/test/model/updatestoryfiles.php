#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_storyspec = zenData('storyspec');
$zd_storyspec->id->range('1-5');
$zd_storyspec->story->range('1-5');
$zd_storyspec->version->range('1-5');
$zd_storyspec->gen(5);
$zd_file = zenData('file');
$zd_file->id->range('1-5');
$zd_file->gen(5);
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

$tester->loadModel('file');


$testObj = new fileModelTest();
/**

title=测试 fileModel::updateStoryFiles()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

$result = $testObj->updateStoryFilesTest(1);
r($result === null) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->updateStoryFilesTest(0);
r($result === null) && p() && e('1'); // 步骤2：边界值输入
$result = $testObj->updateStoryFilesTest(-1);
r($result === null) && p() && e('1'); // 步骤3：无效输入
$result = $testObj->updateStoryFilesTest(999999);
r($result === null) && p() && e('1'); // 步骤4：大值输入
$result = $testObj->updateStoryFilesTest(2);
r($result === null) && p() && e('1'); // 步骤5：业务规则验证
