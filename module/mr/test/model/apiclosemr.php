#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCloseMR();
timeout=0
cid=0

- 重新打开并关闭Gitlab合并请求
 - 属性title @test
 - 属性state @closed
- 重新打开并关闭Gitea合并请求
 - 属性title @test
 - 属性state @closed
- 重新打开并关闭Gogs合并请求
 - 属性title @test
 - 属性state @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);

$mrModel = new mrTest();

$hostID = array
(
    'gitlab' => 1,
    'gitea'  => 4,
    'gogs'   => 5,
);

$projectID = array
(
    'gitlab' => 3,
    'gitea'  => 'gitea/unittest',
    'gogs'   => 'easycorp/unittest',
);

$mrID = array
(
    'gitlab' => 114,
    'gitea'  => 18,
    'gogs'   => 18,
);

r($mrModel->apiCloseMrTester($hostID['gitlab'], $projectID['gitlab'], $mrID['gitlab'])) && p('title,state') && e('test,closed'); // 重新打开并关闭Gitlab合并请求
r($mrModel->apiCloseMrTester($hostID['gitea'],  $projectID['gitea'],  $mrID['gitea']))  && p('title,state') && e('test,closed'); // 重新打开并关闭Gitea合并请求
r($mrModel->apiCloseMrTester($hostID['gogs'],   $projectID['gogs'],   $mrID['gogs']))   && p('title,state') && e('test,closed'); // 重新打开并关闭Gogs合并请求