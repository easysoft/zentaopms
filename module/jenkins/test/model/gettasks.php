#!/usr/bin/env php
<?php

/**

title=测试jenkinsModel->gitTasks();
cid=1
pid=1

- 测试获取流水线 1 列表 depth 0 @0
- 测试获取流水线 1 列表 depth 1 @0
- 测试获取流水线 2 列表 depth 0 @0
- 测试获取流水线 2 列表 depth 1 @0
- 测试获取流水线 3 列表 depth 0
 - 属性hello%20world @hello world
 - 属性paramsJob @paramsJob
 - 属性simple-job @simple-job
- 测试获取流水线 3 列表 depth 1
 - 属性/job/hello%20world/ @hello world
 - 属性/job/paramsJob/ @paramsJob
 - 属性/job/simple-job/ @simple-job
- 测试获取流水线 空 depth 0 @0
- 测试获取流水线 空 depth 1 @0
- 测试获取 不存在的 流水线列表 depth 0 @0
- 测试获取 不存在的 流水线列表 depth 1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/jenkins.class.php';

zdTable('pipeline')->gen('3');
zdTable('user')->gen('1');

su('admin');

$jenkins = new jenkinsTest();

$jenkinsID = array(1, 2, 3, 0, 111);
$depth     = array(0, 1);

r($jenkins->getTasks($jenkinsID[0], $depth[0])) && p() && e('0');  // 测试获取流水线 1 列表 depth 0
r($jenkins->getTasks($jenkinsID[0], $depth[1])) && p() && e('0');  // 测试获取流水线 1 列表 depth 1
r($jenkins->getTasks($jenkinsID[1], $depth[0])) && p() && e('0');  // 测试获取流水线 2 列表 depth 0
r($jenkins->getTasks($jenkinsID[1], $depth[1])) && p() && e('0');  // 测试获取流水线 2 列表 depth 1
r($jenkins->getTasks($jenkinsID[2], $depth[0])) && p('hello%20world,paramsJob,simple-job')                   && e('hello world,paramsJob,simple-job');  // 测试获取流水线 3 列表 depth 0
r($jenkins->getTasks($jenkinsID[2], $depth[1])) && p('/job/hello%20world/,/job/paramsJob/,/job/simple-job/') && e('hello world,paramsJob,simple-job');  // 测试获取流水线 3 列表 depth 1
r($jenkins->getTasks($jenkinsID[3], $depth[0])) && p() && e('0');  // 测试获取流水线 空 depth 0
r($jenkins->getTasks($jenkinsID[3], $depth[1])) && p() && e('0');  // 测试获取流水线 空 depth 1
r($jenkins->getTasks($jenkinsID[4], $depth[0])) && p() && e('0');  // 测试获取 不存在的 流水线列表 depth 0
r($jenkins->getTasks($jenkinsID[4], $depth[1])) && p() && e('0');  // 测试获取 不存在的 流水线列表 depth 1
