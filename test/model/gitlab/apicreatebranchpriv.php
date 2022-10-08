#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiCreateBranchPriv();
cid=1
pid=1

使用空的gitlabID,projectID,保护分支对象创建GitLab保护分支 >> return false
使用空的gitlabID、projectID,正确的保护分支对象创建GitLab保护分支 >> return false
使用正确的gitlabID、保护分支信息，错误的projectID创建保护分支 >> return false
通过gitlabID,projectID,保护分支对象正确创建GitLab保护分支 >> master
使用重复的保护分支信息创建保护分支 >> Protected branch 'master' already exists

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$branch = new stdclass();

$result = $gitlab->apiCreateBranchPriv($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,保护分支对象创建GitLab保护分支

$branch->name               = 'master';
$branch->merge_access_level = '40';
$branch->push_access_level  = '40';
$result = $gitlab->apiCreateBranchPriv($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的保护分支对象创建GitLab保护分支

$gitlabID = 1;
$result = $gitlab->apiCreateBranchPriv($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、保护分支信息，错误的projectID创建保护分支

$projectID = 966;
$gitlab->apiDeleteBranchPriv($gitlabID, $projectID, $branch->name);
r($gitlab->apiCreateBranchPriv($gitlabID, $projectID, $branch)) && p('name')    && e('master'); //通过gitlabID,projectID,保护分支对象正确创建GitLab保护分支
r($gitlab->apiCreateBranchPriv($gitlabID, $projectID, $branch)) && p('message') && e("Protected branch 'master' already exists"); //使用重复的保护分支信息创建保护分支