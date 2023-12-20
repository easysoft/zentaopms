#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSingleDiffVersion();
timeout=0
cid=0

- 不存在的主机 @0
- 正确的数据
 - 属性id @43
 - 属性head_commit_sha @cedfe9a54614e71085e93a5a2e819617b48d43c5

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
$versionID = 43;

r($mrModel->apiGetSingleDiffVersion($hostID['error'], $projectID, $mrID, $versionID)) && p() && e('0'); // 不存在的主机

r($mrModel->apiGetSingleDiffVersion($hostID['gitlab'], $projectID, $mrID, $versionID)) && p('id,head_commit_sha') && e('43,cedfe9a54614e71085e93a5a2e819617b48d43c5'); // 正确的数据