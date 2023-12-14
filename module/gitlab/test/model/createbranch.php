#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::createBranch();
timeout=0
cid=1

- 使用空的gitlabID,projectID,分支对象创建GitLab分支 @return false
- 使用空的gitlabID、projectID,正确的分支对象创建GitLab分支 @return false
- 使用正确的gitlabID、分支信息，错误的projectID创建分支 @return false
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @return true
- 使用重复的分支信息创建分支 @return false

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$branch    = new stdclass();

$result = $gitlab->createBranch($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支对象创建GitLab分支

dao::$errors = array();
$branch->branch = 'test_branch17';
$branch->ref    = 'master';
$result = $gitlab->createBranch($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的分支对象创建GitLab分支

dao::$errors = array();
$gitlabID    = 1;
$result      = $gitlab->createBranch($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、分支信息，错误的projectID创建分支

dao::$errors = array();
$projectID   = 2;

/* Delete branches with the same name. */
$apiRoot = $gitlab->getApiRoot($gitlabID);
$url     = sprintf($apiRoot, "/projects/{$projectID}/repository/branches/{$branch->branch}");
commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE'));

$result = $gitlab->createBranch($gitlabID, $projectID, $branch);
if($result === true) $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,分支对象正确创建GitLab分支

$result = $gitlab->createBranch($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用重复的分支信息创建分支