#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiAcceptMR();
timeout=0
cid=0

- 不存在的主机 @0
- 已关闭的gitlab合并请求属性message @404 Not found
- 已关闭的gitea合并请求 @0
- 已关闭的gogs合并请求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(5);
su('admin');

$mr = zenData('mr')->loadYaml('mr');
$mr->mriid->range(45);
$mr->gen(10);

$mrModel = new mrTest();

$noHost = 6;
r($mrModel->apiAcceptMrTester($noHost)) && p() && e('0'); // 不存在的主机

$gitlab = 1;
r($mrModel->apiAcceptMrTester($gitlab)) && p('message') && e('404 Not found'); // 已关闭的gitlab合并请求

$gitea = 4;
sleep(1);
r($mrModel->apiAcceptMrTester($gitea)) && p() && e('0'); // 已关闭的gitea合并请求

$gogs = 5;
r($mrModel->apiAcceptMrTester($gogs)) && p() && e('0'); // 已关闭的gogs合并请求