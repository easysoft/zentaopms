#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiUpdateMR();
timeout=0
cid=0

- 正确的数据更新Gitlab合并请求
 - 属性oldTitle @test-merge（不要关闭或删除）
 - 属性title @test
- 正确的数据更新Gitea合并请求
 - 属性oldTitle @更新 LICENSE（不要删除）
 - 属性title @test
- 正确的数据更新Gogs合并请求
 - 属性oldTitle @test（不要删除）
 - 属性title @test
- 错误的数据更新Gitlab合并请求 @0
- 错误的数据更新Gitea合并请求 @0
- 错误的数据更新Gogs合并请求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);

$mrModel = new mrTest();

$gitlabID = 1;
$giteaID  = 4;
$gogsID   = 5;

$gitlabProjectID = 3;
$giteaProjectID  = 'gitea/unittest';
$gogsProjectID   = 'easycorp/unittest';

$newMR = new stdclass();
$newMR->title              = 'test';
$newMR->assignee           = '';
$newMR->description        = '';
$newMR->removeSourceBranch = '0';
$newMR->squash             = '0';

$oldMR = array();
$oldMR['gitlab'] = new stdclass();
$oldMR['gitlab']->title         = 'test-merge（不要关闭或删除）';
$oldMR['gitlab']->hostID        = 1;
$oldMR['gitlab']->sourceProject = 3;
$oldMR['gitlab']->targetBranch  = 'master';
$oldMR['gitlab']->id            = 36;

$oldMR['gitea'] = new stdclass();
$oldMR['gitea']->title         = '更新 LICENSE（不要删除）';
$oldMR['gitea']->hostID        = 4;
$oldMR['gitea']->sourceProject = 'gitea/unittest';
$oldMR['gitea']->targetBranch  = 'test1';
$oldMR['gitea']->id            = 11;

$oldMR['gogs'] = new stdclass();
$oldMR['gogs']->title         = 'test（不要删除）';
$oldMR['gogs']->hostID        = 5;
$oldMR['gogs']->sourceProject = 'easycorp/unittest';
$oldMR['gogs']->targetBranch  = 'master';
$oldMR['gogs']->id            = 7;

r($mrModel->apiUpdateMrTester($oldMR['gitlab'], $newMR)) && p('oldTitle,title') && e('test-merge（不要关闭或删除）,test'); // 正确的数据更新Gitlab合并请求
r($mrModel->apiUpdateMrTester($oldMR['gitea'],  $newMR)) && p('oldTitle,title') && e('更新 LICENSE（不要删除）,test');     // 正确的数据更新Gitea合并请求
r($mrModel->apiUpdateMrTester($oldMR['gogs'],   $newMR)) && p('oldTitle,title') && e('test（不要删除）,test');             // 正确的数据更新Gogs合并请求

$oldMR['gitlab']->hostID = 10;
r($mrModel->apiUpdateMrTester($oldMR['gitlab'], $newMR)) && p() && e('0'); // 错误的数据更新Gitlab合并请求

$oldMR['gitea']->sourceProject = '1';
r($mrModel->apiUpdateMrTester($oldMR['gitea'], $newMR)) && p() && e('0'); // 错误的数据更新Gitea合并请求

$oldMR['gogs']->sourceProject = '1';
r($mrModel->apiUpdateMrTester($oldMR['gogs'], $newMR)) && p() && e('0'); // 错误的数据更新Gogs合并请求