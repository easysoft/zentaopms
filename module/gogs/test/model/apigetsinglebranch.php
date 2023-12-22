#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetSingleBranch();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 错误的分支名 @0
- 空的分支名 @0
- 正确的参数
 - 属性name @master
 - 属性web_url @https://gogsdev.qc.oop.cc/easycorp/unittest/src/master

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
$project = '';
$branch  = 'test231222';
r($gogsModel->apiGetSingleBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetSingleBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的项目ID

$project = 'easycorp/unittest';
r($gogsModel->apiGetSingleBranch($gogsID, $project, $branch)) && p() && e('0'); // 错误的分支名

$branch = '';
r($gogsModel->apiGetSingleBranch($gogsID, $project, $branch)) && p() && e('0'); // 空的分支名

$branch = 'master';
r($gogsModel->apiGetSingleBranch($gogsID, $project, $branch)) && p('name,web_url') && e('master,https://gogsdev.qc.oop.cc/easycorp/unittest/src/master'); // 正确的参数