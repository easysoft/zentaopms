#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getPrivs();
timeout=0
cid=0

- 获取repo浏览权限标识第repo条的browse属性 @browseAction
- 获取repo创建权限标识第repo条的create属性 @createAction
- 获取pipeline浏览权限标识第pipeline条的browse属性 @browse
- 获取pipeline创建权限标识第pipeline条的create属性 @create
- 获取artifact创建权限标识第artifact条的create属性 @create
- 独立版空间权限列表包含repo浏览权限第repo条的browse属性 @browseAction
- 独立版空间权限列表包含codescan执行权限第codescan条的exec属性 @exec
- 独立版空间权限列表包含ppm浏览权限第ppm条的browse属性 @browseAction
- 独立版空间权限列表包含pipeline执行权限第pipeline条的exec属性 @exec
- 独立版空间权限列表包含artifact浏览权限第artifact条的browse属性 @browse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('group')->gen(5);

/* Generate grouppriv data with devops nav privs */
$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1,2');
$grouppriv->module->range('repo,pipeline,artifact');
$grouppriv->method->range('browse,create,edit,delete,view');
$grouppriv->gen(10);

su('admin');

/* Set app context to avoid pager ucfirst deprecation warning */
global $tester;
$tester->app->rawModule  = 'space';
$tester->app->rawMethod  = 'browse';
$tester->app->moduleName = 'space';
$tester->app->methodName = 'browse';

$spaceTester = new spaceModelTest();

r($spaceTester->getPrivsTest()) && p('repo:browse') && e('browseAction');       // 获取repo浏览权限标识
r($spaceTester->getPrivsTest()) && p('repo:create') && e('createAction');       // 获取repo创建权限标识
r($spaceTester->getPrivsTest()) && p('pipeline:browse') && e('browse');         // 获取pipeline浏览权限标识
r($spaceTester->getPrivsTest()) && p('pipeline:create') && e('create');         // 获取pipeline创建权限标识
r($spaceTester->getPrivsTest()) && p('artifact:create') && e('create');         // 获取artifact创建权限标识

/* GitFox独立版的导航模块各自成组，空间权限配置页需要能列出这些模块。 */
$config->vision = 'gitfox';
include dirname(__FILE__, 5) . '/extension/gitfox/common/ext/lang/zh-cn/gitfoxcommon.php';

r($spaceTester->getPrivsTest()) && p('repo:browse') && e('browseAction');       // 独立版空间权限列表包含repo浏览权限
r($spaceTester->getPrivsTest()) && p('codescan:exec') && e('exec');             // 独立版空间权限列表包含codescan执行权限
r($spaceTester->getPrivsTest()) && p('ppm:browse') && e('browseAction');        // 独立版空间权限列表包含ppm浏览权限
r($spaceTester->getPrivsTest()) && p('pipeline:exec') && e('exec');             // 独立版空间权限列表包含pipeline执行权限
r($spaceTester->getPrivsTest()) && p('artifact:browse') && e('browse');         // 独立版空间权限列表包含artifact浏览权限
