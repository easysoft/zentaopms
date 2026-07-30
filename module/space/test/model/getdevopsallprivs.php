#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getDevOpsAllPrivs();
timeout=0
cid=0

- 获取repo创建权限 @1
- 获取repo浏览权限 @1
- 获取pipeline创建权限 @1
- 获取artifact浏览权限 @1
- 验证space模块本身不在权限列表中 @0

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
