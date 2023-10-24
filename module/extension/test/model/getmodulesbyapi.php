#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getModulesByAPI();
cid=1
pid=1

判断返回的API中是否包含个人相关 >> 1
判断返回的API中是否包含项目相关 >> 1
判断返回的API中是否包含地盘相关 >> 0

*/

global $tester;

$apiModules = $tester->loadModel('extension')->getModulesByAPI();
$modules    = array_column($apiModules, 'name');

$includeUser    = in_array('个人相关', $modules);
$includeProject = in_array('项目相关' ,$modules);
$includeMy      = in_array('地盘相关', $modules);

r($includeUser)    && p() && e('1'); // 判断返回的API中是否包含个人相关
r($includeProject) && p() && e('1'); // 判断返回的API中是否包含项目相关
r($includeMy)      && p() && e('0'); // 判断返回的API中是否包含地盘相关
