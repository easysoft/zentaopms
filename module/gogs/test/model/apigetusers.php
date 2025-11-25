#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetUsers();
timeout=0
cid=16689

- 错误的服务器ID @0
- 正确的服务器ID
 - 第0条的realname属性 @gogs-admin
 - 第1条的realname属性 @unittest
- 正确的服务器ID，查询绑定过的用户
 - 第0条的realname属性 @unittest
 - 第1条的realname属性 @unittest1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);
su('admin');

global $app;
$app->rawModule = 'gogs';
$app->rawMethod = 'browse';

global $tester;
$gogsModel = $tester->loadModel('gogs');

$gogsID = 1;
r($gogsModel->apiGetUsers($gogsID)) && p() && e('0'); // 错误的服务器ID

$gogsID = 5;
r($gogsModel->apiGetUsers($gogsID, false)) && p('0:realname;1:realname') && e('gogs-admin,unittest'); // 正确的服务器ID

r($gogsModel->apiGetUsers($gogsID, true))  && p('0:realname;1:realname') && e('unittest,unittest1'); // 正确的服务器ID，查询绑定过的用户
