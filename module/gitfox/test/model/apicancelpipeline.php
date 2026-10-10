#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCancelPipeline();
timeout=0
cid=0

- 步骤1：取消不存在的流水线执行返回false @0
- 步骤2：取消失败时记录api错误信息 @1
- 步骤3：取消0号流水线执行返回false @0
- 步骤4：取消负数流水线执行返回false @0
- 步骤5：取消超大流水线执行ID返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$gitfoxTest = new gitfoxModelTest();

$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->realname->range('admin');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->deleted->range('0');
$user->gen(1);

zenData('entry')->gen(0);
$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('[2026-01-01 00:00:00]');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('[2026-01-01 00:00:00]');
$entry->deleted->range('0');
$entry->gen(1);

su('admin');

r($gitfoxTest->apiCancelPipelineTest(999999))     && p() && e('0'); // 步骤1：取消不存在的流水线执行返回false
r($gitfoxTest->apiCancelPipelineErrorTest(999999)) && p() && e('1'); // 步骤2：取消失败时记录api错误信息
r($gitfoxTest->apiCancelPipelineTest(0))          && p() && e('0'); // 步骤3：取消0号流水线执行返回false
r($gitfoxTest->apiCancelPipelineTest(-1))         && p() && e('0'); // 步骤4：取消负数流水线执行返回false
r($gitfoxTest->apiCancelPipelineTest(2147483647)) && p() && e('0'); // 步骤5：取消超大流水线执行ID返回false
