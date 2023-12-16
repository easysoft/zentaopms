#!/usr/bin/env php
<?php

/**

title=测试 mrModel::afterApiCreate();
timeout=0
cid=0

- 没有更新数据
 - 属性title @test-merge
 - 属性compileStatus @~~
- 流水线不存在
 - 属性title @test-merge
 - 属性compileStatus @~~
- 流水线存在
 - 属性title @test-merge
 - 属性compileStatus @created

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(1);
zdTable('job')->config('job')->gen(3);
zdTable('mr')->config('mr')->gen(1);

$mrModel = new mrTest();

$MR = new stdclass();
$MR->sourceBranch       = 'test1';
$MR->targetBranch       = 'master';
$MR->assignee           = 'admin';
$MR->needCI             = 0;
$MR->jobID              = 0;
$MR->hasNoConflict      = '0';
$MR->mergeStatus        = 'can_be_merged';

r($mrModel->afterApiCreateTester(1, $MR)) && p('title,compileStatus') && e('test-merge,~~'); // 没有更新数据

$MR->jobID = 10;
r($mrModel->afterApiCreateTester(1, $MR)) && p('title,compileStatus') && e('test-merge,~~'); // 流水线不存在

$MR->jobID = 1;
r($mrModel->afterApiCreateTester(1, $MR)) && p('title,compileStatus') && e('test-merge,created'); // 流水线存在