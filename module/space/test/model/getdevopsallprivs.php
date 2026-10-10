#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getDevOpsAllPrivs();
timeout=0
cid=0

- 获取repo创建权限第repo条的create属性 @1
- 获取repo浏览权限第repo条的browse属性 @1
- 获取pipeline创建权限第pipeline条的create属性 @1
- 获取artifact浏览权限第artifact条的browse属性 @1
- 验证space模块本身不在权限列表中 @0
- 独立版空间权限包含repo编辑权限第repo条的edit属性 @1
- 独立版空间权限包含repo删除权限第repo条的delete属性 @1
- 独立版空间权限包含codescan执行权限第codescan条的exec属性 @1
- 独立版空间权限包含codescan问题权限第codescan条的issue属性 @1
- 独立版空间权限包含ppm浏览权限第ppm条的browse属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getDevOpsAllPrivsTest()) && p('repo:create') && e('1');        // 获取repo创建权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('repo:browse') && e('1');        // 获取repo浏览权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('pipeline:create') && e('1');    // 获取pipeline创建权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('artifact:browse') && e('1');    // 获取artifact浏览权限
r($spaceTester->hasDevOpsModulePrivTest('space')) && p() && e('0');            // 验证space模块本身不在权限列表中

/* GitFox独立版的导航模块各自成组，空间权限清单需要按独立版的一级导航范围收集。 */
$config->vision = 'gitfox';
include dirname(__FILE__, 5) . '/extension/gitfox/common/ext/lang/zh-cn/gitfoxcommon.php';

r($spaceTester->getDevOpsAllPrivsTest()) && p('repo:edit') && e('1');          // 独立版空间权限包含repo编辑权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('repo:delete') && e('1');        // 独立版空间权限包含repo删除权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('codescan:exec') && e('1');      // 独立版空间权限包含codescan执行权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('codescan:issue') && e('1');     // 独立版空间权限包含codescan问题权限
r($spaceTester->getDevOpsAllPrivsTest()) && p('ppm:browse') && e('1');         // 独立版空间权限包含ppm浏览权限
