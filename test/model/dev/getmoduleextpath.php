#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 devModel::getModuleExtPath();
cid=1
pid=1

获取扩展的模块路径 >> xuan

*/

global $tester;
$tester->loadModel('dev');
$extPath = $tester->dev->getModuleExtPath();

r(key($extPath)) && p() && e('xuan'); // 获取扩展的模块路径
