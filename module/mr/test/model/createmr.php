#!/usr/bin/env php
<?php

/**

title=测试 mrModel->createMR();
timeout=0
cid=1

- 使用正确的mr请求数据
 - 属性id @1
 - 属性title @test-merge
- 使用需要CI的mr请求数据第jobID条的0属性 @『流水线任务』不能为空。
- 使用不存在的流水线任务的mr请求数据
 - 属性id @2
 - 属性title @test-merge
- 使用存在的流水线任务的mr请求数据
 - 属性id @3
 - 属性compileStatus @created
- 使用名称为空的mr请求数据第title条的0属性 @『名称』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('mr')->gen(0);
zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(1);
zdTable('job')->config('job')->gen(1);
su('admin');

$mrModel = new mrTest();

$MR = new stdclass();
$MR->hostID             = 1;
$MR->sourceProject      = '3';
$MR->targetProject      = '3';
$MR->sourceBranch       = 'test1';
$MR->targetBranch       = 'master';
$MR->title              = 'test-merge';
$MR->assignee           = 'admin';
$MR->repoID             = 1;
$MR->executionID        = 0;
$MR->needCI             = 0;
$MR->removeSourceBranch = 0;
$MR->squash             = 0;
$MR->jobID              = 0;
$MR->description        = 'test-merge';
$MR->createdBy          = 'admin';
$MR->createdDate        = '2023-12-01 00:00:00';

r($mrModel->createMrTester($MR)) && p('id,title') && e('1,test-merge'); // 使用正确的mr请求数据

$MR->needCI = 1;
r($mrModel->createMrTester($MR)) && p('jobID:0') && e('『流水线任务』不能为空。'); // 使用需要CI的mr请求数据

$MR->jobID = 100;
r($mrModel->createMrTester($MR)) && p('id,title') && e('2,test-merge'); // 使用不存在的流水线任务的mr请求数据

$MR->jobID = 1;
r($mrModel->createMrTester($MR)) && p('id,compileStatus') && e('3,created'); // 使用存在的流水线任务的mr请求数据

$MR->title = '';
r($mrModel->createMrTester($MR)) && p('title:0') && e('『名称』不能为空。'); // 使用名称为空的mr请求数据