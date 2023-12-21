#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetSingleProject();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 正确的项目ID
 - 属性id @1
 - 属性html_url @https://giteadev.qc.oop.cc/gitea/unittest
- 没有权限的用户属性message @找不到目标。
- 有权限的用户
 - 属性id @1
 - 属性html_url @https://giteadev.qc.oop.cc/gitea/unittest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

$giteaID = 1;
$project = '';
r($giteaModel->apiGetSingleProject($giteaID, $project)) && p() && e('0'); // 错误的服务器ID

$giteaID = 4;
r($giteaModel->apiGetSingleProject($giteaID, $project)) && p() && e('0'); // 错误的项目ID

$project = 'gitea/unittest';
r($giteaModel->apiGetSingleProject($giteaID, $project)) && p('id,html_url') && e('1,https://giteadev.qc.oop.cc/gitea/unittest'); // 正确的项目ID

su('user1');
r($giteaModel->apiGetSingleProject($giteaID, $project)) && p('message') && e('找不到目标。'); // 没有权限的用户

su('user2');
r($giteaModel->apiGetSingleProject($giteaID, $project)) && p('id,html_url') && e('1,https://giteadev.qc.oop.cc/gitea/unittest'); // 有权限的用户