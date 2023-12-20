#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetDiffs();
timeout=0
cid=0

- 不存在的主机 @0
- 正确的数据 @200

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

r($mrModel->apiGetDiffs($hostID['error'], $projectID, $mrID)) && p() && e('0'); // 不存在的主机

$result = $mrModel->apiGetDiffs($hostID['gitlab'], $projectID, $mrID);
r(strpos($result, 'bc405f753d3fc57a7c9cf1aafc04c07ef4c758eced90f0d06275492f20f8fcbf')) && p() && e('200'); // 正确的数据