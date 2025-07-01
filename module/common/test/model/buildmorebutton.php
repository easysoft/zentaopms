#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project');
$project->grade->range('1,2');
$project->gen(100);
zenData('story')->gen(100);

/**

title=测试 commonModel::buildMoreButton();
timeout=0
cid=1

- 查询项目ID为11的项目菜单更多里的项目数量 @10
- 查询项目ID为12的项目菜单更多里的项目数量 @0
- 查询项目ID为13的项目菜单更多里的项目数量 @10
- 查询项目ID为99的项目菜单更多里的项目数量 @10
- 查询项目ID为100的项目菜单更多里的项目数量 @0

*/

global $tester, $app;
$app->loadCommon();
$app->appName    = 'pms';
$app->moduleName = 'bug';
$app->methodName = 'browse';
$app->setControlFile();

$moduleName    = $app->moduleName;
$methodName    = $app->methodName;
$file2Included = $app->importControlFile();
$className     = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
$module        = new $className();
$app->control  = $module;

r(mb_substr_count(commonModel::buildMoreButton(11, false), '<li'))  && p() && e('10'); //查询项目ID为11的项目菜单更多里的项目数量
r(mb_substr_count(commonModel::buildMoreButton(12, false), '<li'))  && p() && e('0');  //查询项目ID为12的项目菜单更多里的项目数量
r(mb_substr_count(commonModel::buildMoreButton(13, false), '<li'))  && p() && e('10'); //查询项目ID为13的项目菜单更多里的项目数量
r(mb_substr_count(commonModel::buildMoreButton(99, false), '<li'))  && p() && e('10'); //查询项目ID为99的项目菜单更多里的项目数量
r(mb_substr_count(commonModel::buildMoreButton(100, false), '<li')) && p() && e('0');  //查询项目ID为100的项目菜单更多里的项目数量
