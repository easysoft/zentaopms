#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiDeleteMR();
timeout=0
cid=0

- 正确的数据创建并删除Gitlab合并请求属性message @404 Not found
- 正确的数据创建并删除Gitea合并请求
 - 属性title @test
 - 属性state @closed
- 正确的数据创建并删除Gogs合并请求
 - 属性title @test
 - 属性state @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);

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

r($mrModel->apiDeleteMrTester($gitlabID, $gitlabProjectID, $params)) && p('message') && e('404 Not found');   // 正确的数据创建并删除Gitlab合并请求
r($mrModel->apiDeleteMrTester($giteaID, $giteaProjectID, $params))   && p('title,state') && e('test,closed'); // 正确的数据创建并删除Gitea合并请求
r($mrModel->apiDeleteMrTester($gogsID, $gogsProjectID, $params))     && p('title,state') && e('test,closed'); // 正确的数据创建并删除Gogs合并请求