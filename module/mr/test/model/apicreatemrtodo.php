#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreateMRTodo();
timeout=0
cid=0

- 不存在的主机 @0
- 正确的数据 @success

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
$mrID      = rand(30, 38);

r($mrModel->apiCreateMRTodo($hostID['error'], $projectID, $mrID)) && p() && e('0'); // 不存在的主机

$result = $mrModel->apiCreateMRTodo($hostID['gitlab'], $projectID, $mrID);
if(!isset($result->message)) $result = 'success';
r($result) && p() && e('success'); // 正确的数据