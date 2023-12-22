#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetDiffs();
timeout=0
cid=0

- 不存在的主机 @0
- GitLab 服务器 @0
- 正确的数据 @116
- 错误的 MR @{"errors":null,"message":"找不到目标。","url":"https://giteadev.qc.oop.cc/api/swagger"}

- 错误的项目 @{"errors":null,"message":"找不到目标。","url":"https://giteadev.qc.oop.cc/api/swagger"}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

$hostID = array
(
    'gitlab' => 1,
    'gitea'  => 4,
    'error'  => 10
);

$projectID = 'gitea/unittest';
$mrID      = 28;

r($mrModel->apiGetDiffs($hostID['error'], $projectID, $mrID)) && p() && e('0'); // 不存在的主机
r($mrModel->apiGetDiffs($hostID['gitlab'], $projectID, $mrID)) && p() && e('0'); // GitLab 服务器

$result = $mrModel->apiGetDiffs($hostID['gitea'], $projectID, $mrID);
r(strpos($result, 'unittest')) && p() && e('116'); // 正确的数据

$mrID = 10000;
r(trim($mrModel->apiGetDiffs($hostID['gitea'], $projectID, $mrID))) && p() && e('{"errors":null,"message":"找不到目标。","url":"https://giteadev.qc.oop.cc/api/swagger"}'); // 错误的 MR

$projectID = 'gitea/unittest2';
r(trim($mrModel->apiGetDiffs($hostID['gitea'], $projectID, $mrID))) && p() && e('{"errors":null,"message":"找不到目标。","url":"https://giteadev.qc.oop.cc/api/swagger"}'); // 错误的项目