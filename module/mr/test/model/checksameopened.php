#!/usr/bin/env php
<?php

/**

title=测试 mrModel::checkSameOpened();
timeout=0
cid=0

- 使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch属性message @存在重复并且未关闭的合并请求: ID1
- 使用空的gitlabID属性result @success
- 源项目为空属性result @success
- 源分支为空属性result @success
- 目标项目为空属性result @success
- 目标分支为空属性result @success
- 使用错误的gitlabID属性result @success
- 使用错误的源项目属性result @success
- 使用错误的目标分支属性result @success
- 使用错误的目标项目属性result @success

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
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('message') && e('存在重复并且未关闭的合并请求: ID1'); // 使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch

$gitlabID = 0;
$result   = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 使用空的gitlabID

$gitlabID      = 1;
$sourceProject = '0';
$result        = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 源项目为空

$sourceProject = '3';
$sourceBranch  = '';
$result        = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 源分支为空

$sourceBranch  = 'test1';
$targetProject = '';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 目标项目为空

$targetProject = '3';
$targetBranch  = '';
$result        = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 目标分支为空

$gitlabID     = 10;
$targetBranch = 'master';
$result       = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 使用错误的gitlabID

$gitlabID      = 1;
$sourceProject = 'test';
$result        = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 使用错误的源项目

$sourceBranch = 'test1';
$targetBranch = 'master1';
$result       = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 使用错误的目标分支

$targetProject = 'test';
$targetBranch  = 'master';
$result        = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); // 使用错误的目标项目