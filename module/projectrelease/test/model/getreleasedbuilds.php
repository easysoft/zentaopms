#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectrelease.unittest.class.php';

zenData('release')->gen(20);
zenData('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->getReleasedBuilds();
cid=17971

- 测试获取项目 131 的已经发布的版本 @1,0,2,3,4,5,6,7

- 测试获取项目 11 的已经发布的版本 @1,0,3

- 测试获取项目 12 的已经发布的版本 @2,0,4

- 测试获取项目 0 的已经发布的版本 @8,0,2

- 测试获取项目 不存在 的已经发布的版本 @0

*/

$projectID = array(131, 11, 12, 0, 1000);

$projectrelease = new projectreleaseTest();

r($projectrelease->getReleasedBuildsTest($projectID[0])) && p() && e('1,0,2,3,4,5,6,7'); // 测试获取项目 131 的已经发布的版本
r($projectrelease->getReleasedBuildsTest($projectID[1])) && p() && e('1,0,3');           // 测试获取项目 11 的已经发布的版本
r($projectrelease->getReleasedBuildsTest($projectID[2])) && p() && e('2,0,4');           // 测试获取项目 12 的已经发布的版本
r($projectrelease->getReleasedBuildsTest($projectID[3])) && p() && e('8,0,2');           // 测试获取项目 0 的已经发布的版本
r($projectrelease->getReleasedBuildsTest($projectID[4])) && p() && e('0');               // 测试获取项目 不存在 的已经发布的版本
