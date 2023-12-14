#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateBranch();
timeout=0
cid=1

- 使用空的gitlabID,projectID,分支对象创建GitLab分支 @return false
- 使用空的gitlabID、projectID,正确的分支对象创建GitLab分支 @return null
- 使用正确的gitlabID、分支信息，错误的projectID创建分支属性message @404 Project Not Found
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @1
- 使用重复的分支信息创建分支属性message @Branch already exists

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$branch    = new stdclass();

$result = $gitlab->apiCreateBranch($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支对象创建GitLab分支

$branch->branch = 'test_branch17';
$branch->ref    = 'master';
$result = $gitlab->apiCreateBranch($gitlabID, $projectID, $branch);
if($result === null) $result = 'return null';
r($result) && p() && e('return null'); //使用空的gitlabID、projectID,正确的分支对象创建GitLab分支

$gitlabID = 1;
r($gitlab->apiCreateBranch($gitlabID, $projectID, $branch)) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、分支信息，错误的projectID创建分支

$projectID = 2;
$result = $gitlab->apiCreateBranch($gitlabID, $projectID, $branch);
if(!empty($result->name) and $result->name == $branch->branch) $result = true;
if(!empty($result->message) and $result->message == 'Branch already exists') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支
r($gitlab->apiCreateBranch($gitlabID, $projectID, $branch)) && p('message') && e('Branch already exists'); //使用重复的分支信息创建分支