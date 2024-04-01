#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMR();
timeout=0
cid=0

- 正确的数据创建Gitlab合并请求
 - 属性title @test
 - 属性state @opened

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);
$repo = zdTable('repo')->config('repo');
$repo->SCM->range('Gitlab,Gitea,Gogs');
$repo->id->range('1,4,5');
$repo->serviceHost->range('1,4,5');
$repo->serviceProject->range('3,[gitea/unittest],[easycorp/unittest]');
$repo->gen(3);
su('admin');

$mrModel   = new mrTest();
$gitlabID  = 1;
$projectID = 3;
$params = new stdClass();
$params->title              = 'test';
$params->sourceBranch       = 'main';
$params->targetBranch       = 'master';
$params->targetProject      = $projectID;
$params->description        = 'This is a test merge request';
$params->assignee           = '';
$params->removeSourceBranch = 0;
$params->squash             = 0;

r($mrModel->apiCreateMrTester($gitlabID, $projectID, $params)) && p('title,state') && e('test,opened'); // 正确的数据创建Gitlab合并请求