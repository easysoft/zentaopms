#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getPrivs();
timeout=0
cid=0

- 获取空间权限列表并验证结果类型为对象 @1
- 获取空间权限并验证结果为非空对象 @1
- 获取空间权限并验证返回结果无错误 @1
- 连续两次获取空间权限并验证结果一致 @1
- 获取空间权限并验证方法返回值类型不变 @1

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
