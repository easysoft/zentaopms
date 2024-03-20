#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiReopenMR();
timeout=0
cid=0

- 关闭并重新打开Gitlab合并请求
 - 属性title @test
 - 属性state @opened

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
su('admin');

$mrModel = new mrTest();

$hostID = array
(
    'gitlab' => 1,
);

$projectID = array
(
    'gitlab' => 3,
);

$mrID = array
(
    'gitlab' => 138,
);

r($mrModel->apiReopenMrTester($hostID['gitlab'], $projectID['gitlab'], $mrID['gitlab'])) && p('title,state') && e('test,opened'); // 关闭并重新打开Gitlab合并请求