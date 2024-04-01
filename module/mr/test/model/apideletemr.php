#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiDeleteMR();
timeout=0
cid=0

- 正确的数据创建并删除Gitlab合并请求属性message @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);
su('admin');

$mrModel = new mrTest();

$gitlabID = 1;

$gitlabProjectID = 3;

$params = new stdClass();
$params->title              = 'test';
$params->sourceBranch       = 'main';
$params->targetBranch       = 'master';
$params->targetProject      = $gitlabProjectID;
$params->description        = 'This is a test merge request';
$params->assignee           = '';
$params->removeSourceBranch = 0;
$params->squash             = 0;

r($mrModel->apiDeleteMrTester($gitlabID, $gitlabProjectID, $params)) && p('message') && e('0');   // 正确的数据创建并删除Gitlab合并请求