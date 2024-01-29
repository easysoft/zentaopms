#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/projectrelease.class.php';

zdTable('release')->gen(20);
zdTable('product')->gen(20);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->processRelease();
cid=1
pid=1

*/

$releaseID = array(1, 3, 7, 8);

$projectrelease = new projectreleaseTest();

r($projectrelease->processReleaseTest($releaseID[0])) && p() && e('project:131 branch:0 build:1 branchName: buildInfos:'); // 测试计算发布信息 1
r($projectrelease->processReleaseTest($releaseID[1])) && p() && e('project:131 branch:0 build:3 branchName: buildInfos:'); // 测试计算发布信息 3
r($projectrelease->processReleaseTest($releaseID[2])) && p() && e('project:132 branch:2 build:7 branchName: buildInfos:'); // 测试计算发布信息 7
r($projectrelease->processReleaseTest($releaseID[3])) && p() && e('project:0 branch:0 build:8 branchName: buildInfos:');   // 测试计算发布信息 8
