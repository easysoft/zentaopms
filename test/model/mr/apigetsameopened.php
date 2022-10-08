#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::apiGetSameOpened();
cid=0
pid=0

使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch >> success
使用空的gitlabID >> null
使用空的sourceProject >> null
使用空的sourceBranch >> null
使用空的targetProject >> null
使用空的targetBranch >> null
使用错误的gitlabID >> null
使用错误的sourceProject >> null
使用错误的sourceBranch >> null
使用错误的targetProject >> null
使用错误的targetBranch >> null

*/
$mrModel = $tester->loadModel('mr');

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$_POST['gitlabID']           = $gitlabID;
$_POST['title']              = 'test_create';
$_POST['description']        = 'test_create';
$_POST['repoID']             = 1;
$_POST['assignee']           = '';
$_POST['removeSourceBranch'] = '1';
$_POST['sourceProject']      = $sourceProject;
$_POST['sourceBranch']       = $sourceBranch;
$_POST['targetProject']      = $targetProject;
$_POST['targetBranch']       = $targetBranch;
$result = $mrModel->create();
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if(isset($result->iid)) $result = 'success';
r($result) && p() && e('success'); //使用正确的gitlabID, sourceProject,sourceBranch,targetProject,targetBranch

$gitlabID      = '';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用空的gitlabID

$gitlabID      = '1';
$sourceProject = '';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用空的sourceProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = '';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用空的sourceBranch

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用空的targetProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = '';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用空的targetBranch

$gitlabID      = 'test';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用错误的gitlabID

$gitlabID      = '1';
$sourceProject = 'test';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用错误的sourceProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch08';
$targetProject = '42';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用错误的sourceBranch

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = 'test';
$targetBranch  = 'master';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用错误的targetProject

$gitlabID      = '1';
$sourceProject = '42';
$sourceBranch  = 'branch-08';
$targetProject = '42';
$targetBranch  = 'main';
$result = $mrModel->apiGetSameOpened($gitlabID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
if($result === null) $result = 'null';
r($result) && p() && e('null'); //使用错误的targetBranch