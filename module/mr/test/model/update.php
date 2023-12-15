#!/usr/bin/env php
<?php

/**

title=测试 mrModel::update();
timeout=0
cid=0

- 使用正确的mr请求数据属性result @success
- 使用不存在的mr请求数据 @此合并请求不存在。
- 使用需要ci的mr请求数据第jobID条的0属性 @『流水线任务』不能为空。
- 使用标题为空的mr请求数据第title条的0属性 @『名称』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(1);
zdTable('repo')->config('repo')->gen(1);
zdTable('mr')->config('mr')->gen(1);

$mrModel = new mrTest();

$MR = new stdclass();
$MR->title              = 'test-merge';
$MR->assignee           = 'admin';
$MR->repoID             = 1;
$MR->needCI             = 0;
$MR->removeSourceBranch = 0;
$MR->squash             = 0;
$MR->jobID              = 0;
$MR->description        = 'test-merge';
$MR->editedBy           = 'admin';
$MR->editedDate         = '2023-12-01 00:00:00';

$mrModel = new mrTest();

$MRID = 1;
r($mrModel->updateTester($MRID, $MR)) && p('result') && e('success'); // 使用正确的mr请求数据

$MRID = 2;
r($mrModel->updateTester($MRID, $MR)) && p() && e('此合并请求不存在。'); // 使用不存在的mr请求数据

$MRID = 1;
$MR->needCI = 1;
r($mrModel->updateTester($MRID, $MR)) && p('jobID:0') && e('『流水线任务』不能为空。'); // 使用需要ci的mr请求数据

$MR->title = '';
$MR->needCI = 0;
r($mrModel->updateTester($MRID, $MR)) && p('title:0') && e('『名称』不能为空。'); // 使用标题为空的mr请求数据