#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('release')->gen(20);
zenData('build')->gen(20);
zenData('product')->gen(20);
zenData('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->processRelease();
timeout=0
cid=17974

- 测试计算发布信息 1 @project:131 branch:0 build:1 branchName: buildInfos:项目11版本1
- 测试计算发布信息 3 @project:131 branch:0 build:3 branchName: buildInfos:项目13版本3
- 测试计算发布信息 7 @project:132 branch:2 build:7 branchName: buildInfos:项目17版本7
- 测试计算发布信息 8 @project:0 branch:0 build:8 branchName: buildInfos:项目18版本8
- 测试计算发布信息 10 @project:12 branch:0 build:2 branchName: buildInfos:项目12版本2

*/

$releaseID = array(1, 3, 7, 8, 10);

$projectrelease = new projectreleaseTaoTest();

r($projectrelease->processReleaseTest($releaseID[0])) && p() && e('project:131 branch:0 build:1 branchName: buildInfos:项目11版本1'); // 测试计算发布信息 1
r($projectrelease->processReleaseTest($releaseID[1])) && p() && e('project:131 branch:0 build:3 branchName: buildInfos:项目13版本3'); // 测试计算发布信息 3
r($projectrelease->processReleaseTest($releaseID[2])) && p() && e('project:132 branch:2 build:7 branchName: buildInfos:项目17版本7'); // 测试计算发布信息 7
r($projectrelease->processReleaseTest($releaseID[3])) && p() && e('project:0 branch:0 build:8 branchName: buildInfos:项目18版本8');   // 测试计算发布信息 8
r($projectrelease->processReleaseTest($releaseID[4])) && p() && e('project:12 branch:0 build:2 branchName: buildInfos:项目12版本2');  // 测试计算发布信息 10