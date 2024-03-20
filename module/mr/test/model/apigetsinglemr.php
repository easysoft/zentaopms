#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSingleMR();
timeout=0
cid=0

- 查询不存在的主机 @0
- 查询Gitlab的合并请求
 - 属性title @test-merge（不要关闭或删除）
 - 属性state @opened
- 查询不存在的Gitlab合并请求属性message @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(1);

global $tester;
$mrModel = $tester->loadModel('mr');

$repoID = array(
    'gitlab' => 1,
    'error'  => 100
);

$MRID = array(
    'gitlab' => 36,
);

r($mrModel->apiGetSingleMR($repoID['error'],  $MRID['gitlab'])) && p() && e('0'); // 查询不存在的主机

r($mrModel->apiGetSingleMR($repoID['gitlab'], $MRID['gitlab'])) && p('title,state') && e('test-merge（不要关闭或删除）,opened'); // 查询Gitlab的合并请求
r($mrModel->apiGetSingleMR($repoID['gitlab'], -1))              && p('message')     && e('0');                       // 查询不存在的Gitlab合并请求