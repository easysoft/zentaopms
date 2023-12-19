#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiReopenMR();
timeout=0
cid=0

- 关闭并重新打开Gitlab合并请求
 - 属性title @test
 - 属性state @opened
- 关闭并重新打开Gitea合并请求
 - 属性title @test
 - 属性state @opened
- 关闭并重新打开Gogs合并请求
 - 属性title @test
 - 属性state @opened

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

r($mrModel->apiReopenMrTester($hostID['gitlab'], $projectID['gitlab'], $mrID['gitlab'])) && p('title,state') && e('test,opened'); // 关闭并重新打开Gitlab合并请求
r($mrModel->apiReopenMrTester($hostID['gitea'],  $projectID['gitea'],  $mrID['gitea']))  && p('title,state') && e('test,opened'); // 关闭并重新打开Gitea合并请求
r($mrModel->apiReopenMrTester($hostID['gogs'],   $projectID['gogs'],   $mrID['gogs']))   && p('title,state') && e('test,opened'); // 关闭并重新打开Gogs合并请求