#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::checkSameOpened();
cid=0
pid=0

使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch >> fail
使用空的gitlabID >> success
使用空的sourceProject >> success
使用空的sourceBranch >> success
使用空的targetProject >> success
使用空的targetBranch >> success
使用错误的gitlabID >> success
使用错误的sourceProject >> success
使用错误的sourceBranch >> success
使用错误的targetProject >> success
使用错误的targetBranch >> success

*/
$mrModel = $tester->loadModel('mr');

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('fail'); //使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch

$gitlabID      = '';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用空的gitlabID

$gitlabID      = '1';
$sourceProject = '';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用空的sourceProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = '';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用空的sourceBranch

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用空的targetProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = '';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用空的targetBranch

$gitlabID      = 'test';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用错误的gitlabID

$gitlabID      = '1';
$sourceProject = 'test';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用错误的sourceProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用错误的sourceBranch

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = 'test';
$targetBranch  = 'master';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用错误的targetProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'main';
$result = $mrModel->checkSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
r($result) && p('result') && e('success'); //使用错误的targetBranch