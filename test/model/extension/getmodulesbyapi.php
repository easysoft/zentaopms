#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
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

$api = $tester->loadModel('extension')->getModulesByAPI();

$includeUser    = strpos($api, '个人相关') !== false;
$includeProject = strpos($api, '项目相关') !== false;
$includeMy      = strpos($api, '地盘相关') !== false;

r($includeUser)    && p() && e('1'); // 判断返回的API中是否包含个人相关
r($includeProject) && p() && e('1'); // 判断返回的API中是否包含项目相关
r($includeMy)      && p() && e('0'); // 判断返回的API中是否包含地盘相关