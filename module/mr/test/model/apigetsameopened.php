#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSameOpened();
timeout=0
cid=0

- 使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch
 - 属性id @36
 - 属性state @opened
- 使用空的gitlabID @0
- 源项目为空 @0
- 源分支为空 @0
- 目标项目为空 @0
- 目标分支为空 @0
- 使用错误的gitlabID @0
- 使用错误的源项目 @0
- 使用错误的目标分支 @0
- 使用错误的目标项目 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(1);
zdTable('repo')->config('repo')->gen(1);
zdTable('mr')->config('mr')->gen(1);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

$gitlabID      = 1;
$sourceProject = '3';
$sourceBranch  = 'test1';
$targetProject = '3';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('id,state') && e('36,opened'); // 使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch

$gitlabID = 0;
$result   = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 使用空的gitlabID

$gitlabID      = 1;
$sourceProject = '0';
$result        = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 源项目为空

$sourceProject = '3';
$sourceBranch  = '';
$result        = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 源分支为空

$sourceBranch  = 'test1';
$targetProject = '';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 目标项目为空

$targetProject = '3';
$targetBranch  = '';
$result        = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 目标分支为空

$gitlabID     = 10;
$targetBranch = 'master';
$result       = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 使用错误的gitlabID

$gitlabID      = 1;
$sourceProject = 'test';
$result       = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 使用错误的源项目

$sourceBranch = 'test1';
$targetBranch = 'master1';
$result       = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 使用错误的目标分支

$targetProject = 'test';
$targetBranch  = 'master';
$result        = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p() && e('0'); // 使用错误的目标项目
