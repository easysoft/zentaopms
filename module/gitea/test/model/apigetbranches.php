#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetBranches();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 正确的项目ID
 - 属性name @main
 - 属性user_can_push @~~
- 没有权限的用户 @0
- 有权限的用户
 - 属性name @main
 - 属性user_can_merge @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
$project = '';
r($giteaModel->apiGetBranches($giteaID, $project)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetBranches($giteaID, $project)) && p() && e('0'); // 错误的项目ID

$project = 'gitea/unittest';
$result  = $giteaModel->apiGetBranches($giteaID, $project);
r(end($result)) && p('name,user_can_push') && e('main,~~'); // 正确的项目ID

su('user1');
r($giteaModel->apiGetBranches($giteaID, $project)) && p() && e('0'); // 没有权限的用户

su('user2');
$result = $giteaModel->apiGetBranches($giteaID, $project);
r(end($result)) && p('name,user_can_merge') && e('main,1'); // 有权限的用户
