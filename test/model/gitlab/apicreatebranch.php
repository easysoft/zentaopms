#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiCreateBranch();
cid=1
pid=1

通过gitlabID,projectID,分支对象创建GitLab分支 >> test_branch5

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 1555;
$branch    = new stdclass();
$branch->branch = 'test_branch5';
$branch->ref    = 'master';
r($gitlab->apiCreateBranch($gitlabID, $projectID, $branch)) && p('name') && e($branch->branch); //通过gitlabID,projectID,分支对象创建GitLab分支
