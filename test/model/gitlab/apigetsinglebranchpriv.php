#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetSingleBranchPriv();
cid=1
pid=1

使用空的gitlabID,projectID,分支名称获取保护分支 >> return false
使用正确的gitlabID、保护分支信息，错误的projectID获取保护分支 >> 404 Project Not Found
通过gitlabID,projectID,分支名称正确获取保护分支 >> master
使用错误的保护分支信息获取保护分支 >> 404 Not found

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$branch    = '';

$result = $gitlab->apiGetSingleBranchPriv($gitlabID, $projectID, $branch);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支名称获取保护分支

$gitlabID = 1;
$branch   = 'master';
$result   = $gitlab->apiGetSingleBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、保护分支信息，错误的projectID获取保护分支

$projectID = 1552;
$result    = $gitlab->apiGetSingleBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('name') && e('master');  //通过gitlabID,projectID,分支名称正确获取保护分支

$branch = 'masters';
$result = $gitlab->apiGetSingleBranchPriv($gitlabID, $projectID, $branch);
r($result) && p('message') && e('404 Not found'); //使用错误的保护分支信息获取保护分支