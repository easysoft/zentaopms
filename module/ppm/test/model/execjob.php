#!/usr/bin/env php
<?php

/**

title=测试 mrModel::execJob();
timeout=0
cid=17244

- MR不存在 @0
- 没有更新数据
 - 属性title @test-merge
 - 属性compileStatus @~~
- 流水线不存在
 - 属性title @test-merge
 - 属性compileStatus @~~
- 流水线存在
 - 属性title @test-merge
 - 属性compileStatus @created
- MR不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(4);
zenData('repo')->loadYaml('repo')->gen(1);
zenData('job')->loadYaml('job')->gen(3);
zenData('mr')->loadYaml('mr')->gen(1);

$mrModel = new mrModelTest();

$MRID  = 0;
$jobID = 0;
r($mrModel->execJobTester($MRID, $jobID)) && p('') && e('0'); // MR不存在

$MRID = 1;
r($mrModel->execJobTester($MRID, $jobID)) && p('title,compileStatus') && e('test-merge,~~'); // 没有更新数据

$jobID = 10;
r($mrModel->execJobTester($MRID, $jobID)) && p('title,compileStatus') && e('test-merge,~~'); // 流水线不存在

$jobID = 1;
r($mrModel->execJobTester($MRID, $jobID)) && p('title,compileStatus') && e('test-merge,created'); // 流水线存在

$MRID = 10;
r($mrModel->execJobTester($MRID, $jobID)) && p('') && e('0'); // MR不存在