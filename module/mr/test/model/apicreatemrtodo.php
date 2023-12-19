#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMRTodo();
timeout=0
cid=0

- 不存在的主机 @0
- 正确的数据 @0

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

r($mrModel->apiCreateMRTodo($hostID['error'], $projectID, $mrID)) && p() && e('0'); // 不存在的主机

r($mrModel->apiCreateMRTodo($hostID['gitlab'], $projectID, $mrID)) && p() && e('0'); // 正确的数据