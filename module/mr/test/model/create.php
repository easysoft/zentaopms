#!/usr/bin/env php
<?php

/**

title=测试 mrModel::create();
timeout=0
cid=0

- hostID为空第hostID条的0属性 @『服务器』不能为空。
- 使用已存在的mr请求数据 @存在重复并且未关闭的合并请求: ID1
- 使用源项目分支与目标项目分支相同的mr请求数据属性result @存在另外一个同样的合并请求在源项目分支中: ID30
- 使用源项目分支与目标项目分支相同的mr请求数据 @源项目分支与目标项目分支不能相同
- 使用正确的mr请求数据属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(1);
zdTable('repo')->config('repo')->gen(1);
zdTable('mr')->config('mr')->gen(1);

$mrModel = new mrTest();

$MR = new stdclass();
$MR->hostID             = 0;
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

r($mrModel->createTester($MR)) && p('hostID:0') && e('『服务器』不能为空。'); // hostID为空

$MR->hostID = 1;
r($mrModel->createTester($MR)) && p() && e('存在重复并且未关闭的合并请求: ID1'); // 使用已存在的mr请求数据

$MR->sourceBranch = 'laudantium-unde-et-iste-et';
r($mrModel->createTester($MR)) && p('result') && e('存在另外一个同样的合并请求在源项目分支中: ID30'); // 使用源项目分支与目标项目分支相同的mr请求数据

$MR->targetBranch = 'laudantium-unde-et-iste-et';
r($mrModel->createTester($MR)) && p() && e('源项目分支与目标项目分支不能相同'); // 使用源项目分支与目标项目分支相同的mr请求数据

$MR->sourceBranch = 'test';
r($mrModel->createTester($MR)) && p('result') && e('success'); // 使用正确的mr请求数据
