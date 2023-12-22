#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetProjects();
timeout=0
cid=0

- 错误的服务器ID @0
- 正确的服务器ID
 - 第0条的id属性 @1
 - 第0条的full_name属性 @easycorp/unittest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
r($gogsModel->apiGetProjects($gogsID)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetProjects($gogsID)) && p('0:id,full_name') && e('1,easycorp/unittest'); // 正确的服务器ID