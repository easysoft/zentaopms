#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSingleMR();
timeout=0
cid=0

- 查询Gitlab的合并请求
 - 属性title @test-merge（不要关闭或删除）
 - 属性state @opened
- 查询不存在的Gitlab合并请求属性message @404 Not found
- 查询Gitea的合并请求
 - 属性title @更新 LICENSE（不要删除）
 - 属性state @opened
- 查询不存在的Gitea合并请求属性message @找不到目标。
- 查询Gogs的合并请求
 - 属性title @test（不要删除）
 - 属性state @opened
- 查询不存在的Gogs合并请求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);

global $tester;
$mrModel = $tester->loadModel('mr');

$hostID = array(
    'gitlab' => 1,
    'gitea'  => 4,
    'gogs'   => 5
);

$projectID = array(
    'gitlab' => 3,
    'gitea'  => 'gitea/unittest',
    'gogs'   => 'easycorp/unittest'
);

$MRID = array(
    'gitlab' => 36,
    'gitea'  => 11,
    'gogs'   => 7
);

r($mrModel->apiGetSingleMR($hostID['gitlab'], $projectID['gitlab'], $MRID['gitlab'])) && p('title,state') && e('test-merge（不要关闭或删除）,opened'); // 查询Gitlab的合并请求
r($mrModel->apiGetSingleMR($hostID['gitlab'], $projectID['gitlab'], -1))              && p('message')     && e('404 Not found');                       // 查询不存在的Gitlab合并请求

r($mrModel->apiGetSingleMR($hostID['gitea'], $projectID['gitea'], $MRID['gitea']))    && p('title,state') && e('更新 LICENSE（不要删除）,opened'); // 查询Gitea的合并请求
r($mrModel->apiGetSingleMR($hostID['gitea'], $projectID['gitea'], -1))                && p('message')     && e('找不到目标。');                    // 查询不存在的Gitea合并请求

r($mrModel->apiGetSingleMR($hostID['gogs'], $projectID['gogs'], $MRID['gogs']))       && p('title,state') && e('test（不要删除）,opened'); // 查询Gogs的合并请求
r($mrModel->apiGetSingleMR($hostID['gogs'], $projectID['gogs'], -1))                  && p()              && e('0');                       // 查询不存在的Gogs合并请求