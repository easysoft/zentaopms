#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->processReleaseForCreate();
timeout=0
cid=18008

- 测试同步版本数据并处理空的数据
 - 第0条的name属性 @发布1
 - 第0条的project属性 @131
- 测试同步版本数据并处理空的数据
 - 第0条的name属性 @发布2
 - 第0条的project属性 @131
- 测试同步版本数据并处理空的数据
 - 第0条的name属性 @发布3
 - 第0条的project属性 @131
- 测试同步版本数据并处理空的数据第0条的build属性 @~~
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$build = zenData('build')->loadYaml('build');
$build->project->range('1{2},0{3}');
$build->execution->range('0,101,0{3}');
$build->gen(5);

zenData('release')->loadYaml('release')->gen(5);
zenData('product')->gen(5);
su('admin');

$releaseTester = new releaseTest();
r($releaseTester->processReleaseBuildsTest(1))        && p('0:name,project') && e('发布1,131');  // 测试同步版本数据并处理空的数据
r($releaseTester->processReleaseBuildsTest(2))        && p('0:name,project') && e('发布2,131');  // 测试同步版本数据并处理空的数据
r($releaseTester->processReleaseBuildsTest(3))        && p('0:name,project') && e('发布3,131');  // 测试同步版本数据并处理空的数据
r($releaseTester->processReleaseBuildsTest(2, false)) && p('0:build')        && e('~~');         // 测试同步版本数据并处理空的数据
