#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMR();
timeout=0
cid=17224

- 正确的数据创建Gitlab合并请求
 - 属性title @test
 - 属性state @opened
 - 属性description @This is a test merge request
 - 属性sourceBranch @main
 - 属性targetBranch @master

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(1);
$repo = zenData('repo')->loadYaml('repo');
$repo->SCM->range('Gitlab,Gitea,Gogs');
$repo->id->range('1,4,5');
$repo->serviceHost->range('1,4,5');
$repo->serviceProject->range('3,[gitea/unittest],[easycorp/unittest]');
$repo->gen(3);
su('admin');

$mrModel   = new mrModelTest();
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

r($mrModel->apiCreateMrTester($gitlabID, $projectID, $params)) && p('title,state,description,source_branch,target_branch') && e('test,opened,This is a test merge request,main,master'); // 正确的数据创建Gitlab合并请求
