#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetBranchPrivs();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 不过滤分支名第0条的branch_name属性 @main
- 过滤包含master的分支名 @0
- 过滤包含ma的分支名第0条的branch_name属性 @main
- 没有权限的用户 @0
- 绑定的用户，但不是项目管理员 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
$project = '';
$branch  = '';
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p() && e('0'); // 错误的项目ID

$project = 'gitea/unittest';
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p('0:branch_name') && e('main'); // 不过滤分支名

$branch = 'master';
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p() && e('0'); // 过滤包含master的分支名

$branch = 'ma';
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p('0:branch_name') && e('main'); // 过滤包含ma的分支名

su('user1');
$branch = '';
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p() && e('0'); // 没有权限的用户

su('user2');
r($giteaModel->apiGetBranchPrivs($giteaID, $project, $branch)) && p() && e('0'); // 绑定的用户，但不是项目管理员