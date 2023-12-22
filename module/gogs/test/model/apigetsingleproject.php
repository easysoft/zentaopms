#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetSingleProject();
timeout=0
cid=0

- 错误的服务器ID @0
- 错误的项目ID @0
- 正确的项目ID
 - 属性id @1
 - 属性html_url @https://gogsdev.qc.oop.cc/easycorp/unittest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
$project = '';
r($gogsModel->apiGetSingleProject($gogsID, $project)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetSingleProject($gogsID, $project)) && p() && e('0'); // 错误的项目ID

$project = 'easycorp/unittest';
r($gogsModel->apiGetSingleProject($gogsID, $project)) && p('id,html_url') && e('1,https://gogsdev.qc.oop.cc/easycorp/unittest'); // 正确的项目ID