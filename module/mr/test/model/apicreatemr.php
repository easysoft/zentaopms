#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMR();
timeout=0
cid=0

- 正确的数据创建Gitlab合并请求
 - 属性title @test
 - 属性state @opened
- 正确的数据创建Gitea合并请求
 - 属性title @test
 - 属性state @opened
- 正确的数据创建Gogs合并请求
 - 属性title @test
 - 属性state @opened

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
su('admin');

$mrModel = new mrTest();

$gitlabID = 1;
$giteaID  = 4;
$gogsID   = 5;

$gitlabProjectID = 3;
$giteaProjectID  = 'gitea/unittest';
$gogsProjectID   = 'easycorp/unittest';

$params = new stdClass();
$params->title              = 'test';
$params->sourceBranch       = 'main';
$params->targetBranch       = 'master';
$params->targetProject      = $gitlabProjectID;
$params->description        = 'This is a test merge request';
$params->assignee           = '';
$params->removeSourceBranch = 0;
$params->squash             = 0;

r($mrModel->apiCreateMrTester($gitlabID, $gitlabProjectID, $params)) && p('title,state') && e('test,opened'); // 正确的数据创建Gitlab合并请求
r($mrModel->apiCreateMrTester($giteaID, $giteaProjectID, $params))   && p('title,state') && e('test,opened'); // 正确的数据创建Gitea合并请求
r($mrModel->apiCreateMrTester($gogsID, $gogsProjectID, $params))     && p('title,state') && e('test,opened'); // 正确的数据创建Gogs合并请求