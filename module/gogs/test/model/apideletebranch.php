#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiDeleteBranch();
timeout=0
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID  = 0;
$project = '';
$branch  = '';
r($gogsModel->apiDeleteBranch($gogsID, $project, $branch)) && p() && e('0'); // 项目和分支都为空

$gogsID  = 1;
$branch  = 'test-delete';
$project = 'easycorp';
r($gogsModel->apiDeleteBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的项目ID

$gogsID = 5;
r($gogsModel->apiDeleteBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的项目ID

$project = 'easycorp/unittest';
r($gogsModel->apiDeleteBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的分支名

$branch = 'main';
r($gogsModel->apiDeleteBranch($gogsID, $project, $branch)) && p('message') && e('branch protected'); // 受保护的分支
