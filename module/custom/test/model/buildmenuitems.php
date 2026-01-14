#!/usr/bin/env php
<?php

/**

title=测试 customModel::buildMenuItems();
timeout=0
cid=15892

- 测试主菜单构建 @array
- 测试产品模块菜单构建 @array
- 测试地盘模块菜单构建 @array
- 测试项目模块菜单构建 @array
- 测试空菜单输入处理 @array
- 测试权限过滤菜单项 @array
- 测试移动端菜单隐藏配置 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('usergroup')->gen(10);
zenData('group')->gen(5);
zenData('grouppriv')->gen(100);

su('admin');

$customTest = new customModelTest();

r($customTest->buildMenuItemsTest('main'))      && p() && e('array'); // 测试主菜单构建
r($customTest->buildMenuItemsTest('product'))   && p() && e('array'); // 测试产品模块菜单构建
r($customTest->buildMenuItemsTest('my'))        && p() && e('array'); // 测试地盘模块菜单构建
r($customTest->buildMenuItemsTest('project'))   && p() && e('array'); // 测试项目模块菜单构建
r($customTest->buildMenuItemsTest(''))          && p() && e('array'); // 测试空菜单输入处理

su('user');
r($customTest->buildMenuItemsTest('main'))      && p() && e('array'); // 测试权限过滤菜单项

global $config;
$config->viewType = 'mhtml';
r($customTest->buildMenuItemsTest('my'))        && p() && e('array'); // 测试移动端菜单隐藏配置