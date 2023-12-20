#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetDiffVersions();
timeout=0
cid=0

- 不存在的主机 @0
- 正确的数据 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(1);

global $tester;
$mrModel = $tester->loadModel('mr');

$hostID = array
(
    'gitlab' => 1,
    'error'  => 10
);

$projectID = 3;
$mrID      = 36;

r($mrModel->apiGetDiffVersions($hostID['error'], $projectID, $mrID)) && p() && e('0'); // 不存在的主机

r(count($mrModel->apiGetDiffVersions($hostID['gitlab'], $projectID, $mrID))) && p() && e('20'); // 正确的数据