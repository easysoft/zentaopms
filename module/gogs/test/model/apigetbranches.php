#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetBranches();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 正确的项目ID
 - 属性name @main
 - 第commit条的id属性 @be26c25279341d11ae37739a17477100c974fa9f

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
$project = '';
r($gogsModel->apiGetBranches($gogsID, $project)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetBranches($gogsID, $project)) && p() && e('0'); // 错误的项目ID

$project = 'easycorp/unittest';
r(current($gogsModel->apiGetBranches($gogsID, $project))) && p('name;commit:id') && e('main,be26c25279341d11ae37739a17477100c974fa9f'); // 正确的项目ID