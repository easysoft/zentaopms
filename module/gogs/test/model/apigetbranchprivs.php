#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetBranchPrivs();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 不过滤分支名
 - 第0条的name属性 @main
 - 第0条的RepoID属性 @1
- 过滤包含master的分支名 @0
- 过滤包含ma的分支名第0条的name属性 @main

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
$project = '';
$branch  = '';
r($gogsModel->apiGetBranchPrivs($gogsID, $project, $branch)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetBranchPrivs($gogsID, $project, $branch)) && p() && e('0'); // 错误的项目ID

$project = 'easycorp/unittest';
r($gogsModel->apiGetBranchPrivs($gogsID, $project, $branch)) && p('0:name,RepoID') && e('main,1'); // 不过滤分支名

$branch = 'master';
r($gogsModel->apiGetBranchPrivs($gogsID, $project, $branch)) && p() && e('0'); // 过滤包含master的分支名

$branch = 'ma';
r($gogsModel->apiGetBranchPrivs($gogsID, $project, $branch)) && p('0:name') && e('main'); // 过滤包含ma的分支名