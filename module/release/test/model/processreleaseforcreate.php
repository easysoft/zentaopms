#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->processReleaseForCreate();
timeout=0
cid=18009

- 测试同步版本数据并处理空的数据 @0
- 测试同步版本数据并处理发布1的数据属性name @发布1
- 测试同步版本数据并处理不存在发布的数据 @0
- 测试不同步版本数据并处理空的数据 @0
- 测试不同步版本数据并处理发布1的数据属性name @发布1
- 测试不同步版本数据并处理不存在发布的数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$build = zenData('build')->loadYaml('build');
$build->project->range('1{2},0{3}');
$build->execution->range('0,101,0{3}');
$build->gen(5);

zenData('story')->loadYaml('story')->gen(5);
zenData('bug')->gen(0);
zenData('release')->loadYaml('release')->gen(5);
zenData('user')->gen(5);
su('admin');

$syncList = array(true, false);
$releases = array(0, 1, 10);

$releaseTester = new releaseTest();
r($releaseTester->processReleaseForCreateTest($releases[0], $syncList[0])) && p()       && e('0');     // 测试同步版本数据并处理空的数据
r($releaseTester->processReleaseForCreateTest($releases[1], $syncList[0])) && p('name') && e('发布1'); // 测试同步版本数据并处理发布1的数据
r($releaseTester->processReleaseForCreateTest($releases[2], $syncList[0])) && p()       && e('0');     // 测试同步版本数据并处理不存在发布的数据
r($releaseTester->processReleaseForCreateTest($releases[0], $syncList[1])) && p()       && e('0');     // 测试不同步版本数据并处理空的数据
r($releaseTester->processReleaseForCreateTest($releases[1], $syncList[1])) && p('name') && e('发布1'); // 测试不同步版本数据并处理发布1的数据
r($releaseTester->processReleaseForCreateTest($releases[2], $syncList[1])) && p()       && e('0');     // 测试不同步版本数据并处理不存在发布的数据