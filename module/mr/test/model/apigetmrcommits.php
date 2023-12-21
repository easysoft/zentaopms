#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetMRCommits();
timeout=0
cid=0

- 正确的Gitlab合并请求
 - 属性short_id @59b9ec0f
 - 属性author_name @Administrator
- 正确的Gitea合并请求
 - 属性sha @d30919bdb9b4cf8e2698f4a6a30e41910427c01c
 - 第committer条的login属性 @gitea
- gogs没有接口，返回0 @0
- 错误的Gitlab合并请求属性message @404 Project Not Found
- 错误的Gitea合并请求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);

$mrModel = new mrTest();

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

$mrID = array(
    'gitlab' => 36,
    'gitea'  => 11,
    'gogs'   => 7
);

r($mrModel->apiGetMRCommitsTester($hostID['gitlab'], $projectID['gitlab'], $mrID['gitlab'])) && p('short_id,author_name') && e('59b9ec0f,Administrator');        // 正确的Gitlab合并请求
r($mrModel->apiGetMRCommitsTester($hostID['gitea'],  $projectID['gitea'],  $mrID['gitea']))  && p('sha;committer:login')  && e('d30919bdb9b4cf8e2698f4a6a30e41910427c01c,gitea'); // 正确的Gitea合并请求
r($mrModel->apiGetMRCommitsTester($hostID['gogs'],   $projectID['gogs'],   $mrID['gogs']))   && p()                       && e('0');                                              // gogs没有接口，返回0

r($mrModel->apiGetMRCommitsTester($hostID['gitlab'], 9999, $mrID['gitlab'])) && p('message') && e('404 Project Not Found'); // 错误的Gitlab合并请求
r($mrModel->apiGetMRCommitsTester($hostID['gitea'],  9999, $mrID['gitea']))  && p()          && e('0');                     // 错误的Gitea合并请求