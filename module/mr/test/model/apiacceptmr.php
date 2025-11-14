#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiAcceptMR();
timeout=0
cid=17221

- 不存在的主机 @0
- 已关闭的gitlab合并请求属性message @404 Not found
- 已关闭的gitea合并请求 @0
- 已关闭的gogs合并请求 @0
- 无效的MR ID @0
- 测试不同squash和分支删除选项 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(5);
su('admin');

$mr = zenData('mr')->loadYaml('mr');
$mr->mriid->range(45);
$mr->gen(10);

$mrModel = new mrTest();

r($mrModel->apiAcceptMrTester(6)) && p() && e('0'); // 不存在的主机

r($mrModel->apiAcceptMrTester(1)) && p('message') && e('404 Not found'); // 已关闭的gitlab合并请求

sleep(1);
r($mrModel->apiAcceptMrTester(4)) && p() && e('0'); // 已关闭的gitea合并请求

r($mrModel->apiAcceptMrTester(5)) && p() && e('0'); // 已关闭的gogs合并请求

r($mrModel->apiAcceptMrTester(999)) && p() && e('0'); // 无效的MR ID

r($mrModel->apiAcceptMrTester(3)) && p() && e('~~'); // 测试不同squash和分支删除选项