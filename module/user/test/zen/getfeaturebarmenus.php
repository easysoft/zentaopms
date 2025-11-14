#!/usr/bin/env php
<?php
/**

title=测试 userZen::getFeatureBarMenus();
timeout=0
cid=19674

- 检查返回菜单个数。 @12
- 检查日程菜单信息。
 - 属性url @/user-view-1.html
 - 属性active @1
 - 属性text @日程
- vision=lite, 检查需求菜单不存在。 @0
- edition=biz, 检查返回菜单个数。 @9
- systemMode=light, 检查返回菜单个数。 @8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(3);
su('admin');

global $tester, $app, $config;
$userModel = $tester->loadModel('user');

$app->moduleName     = 'user';
$app->methodName     = 'todo';
$config->webRoot     = '/';
$config->requestType = 'PATH_INFO';
$config->edition     = 'ipd';
$config->systemMode  = 'PLM';
$config->vision      = 'rnd';

$zen  = initReference('user');
$func = $zen->getMethod('getFeatureBarMenus');
$user = $userModel->fetchByID(1);

$menus = $func->invokeArgs($zen->newInstance(), array($user));
r(count($menus)) && p() && e('12'); // 检查返回菜单个数。
r($menus['todo']) && p('url,active,text') && e('/user-view-1.html,1,日程'); // 检查日程菜单信息。

$config->vision = 'lite';
$menus = $func->invokeArgs($zen->newInstance(), array($user));
r(isset($menus['requirement'])) && p() && e('0'); // vision=lite, 检查需求菜单不存在。

$config->edition = 'biz';
r(count($func->invokeArgs($zen->newInstance(), array($user)))) && p() && e('9'); // edition=biz, 检查返回菜单个数。

$config->systemMode = 'light';
r(count($func->invokeArgs($zen->newInstance(), array($user)))) && p() && e('8'); // systemMode=light, 检查返回菜单个数。