#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetSingleBranch();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID第errors条的0属性 @user redirect does not exist [name: branches]
- 错误的分支名第errors条的0属性 @branch does not exist [name: test231222]
- 空的分支名 @0
- 正确的参数
 - 属性name @master
 - 属性web_url @https://giteadev.qc.oop.cc/gitea/unittest/src/branch/master
- 没有权限的用户属性message @找不到目标。
- 有权限的用户
 - 属性name @master
 - 属性web_url @https://giteadev.qc.oop.cc/gitea/unittest/src/branch/master

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
$project = '';
$branch  = 'test231222';
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p('errors:0') && e('user redirect does not exist [name: branches]'); // 错误的项目ID

$project = 'gitea/unittest';
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p('errors:0') && e('branch does not exist [name: test231222]'); // 错误的分支名

$branch = '';
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p() && e('0'); // 空的分支名

$branch = 'master';
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p('name,web_url') && e('master,https://giteadev.qc.oop.cc/gitea/unittest/src/branch/master'); // 正确的参数

su('user1');
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p('message') && e('找不到目标。'); // 没有权限的用户

su('user2');
r($giteaModel->apiGetSingleBranch($giteaID, $project, $branch)) && p('name,web_url') && e('master,https://giteadev.qc.oop.cc/gitea/unittest/src/branch/master'); // 有权限的用户