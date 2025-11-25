#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 devModel::getModuleExtPath();
cid=16006
pid=1

- 获取开源版扩展的模块路径 @xuan
- 检查 xuan 扩展路径 @1
- 获取旗舰版扩展的模块路径 @common
- 检查旗舰版 common 扩展路径 @1
- 检查 IPD 版 common 扩展路径 @1

*/

global $tester;
$tester->loadModel('dev');

$tester->dev->config->edition = 'open';
$extPath = $tester->dev->getModuleExtPath();
r(key($extPath)) && p() && e('xuan'); // 获取开源版扩展的模块路径
r(strpos($extPath['xuan'], DS . 'extension' . DS . 'xuan' . DS) !== false) && p() && e('1'); // 检查 xuan 扩展路径

$tester->dev->config->edition = 'max';
$extPath = $tester->dev->getModuleExtPath();
r(key($extPath)) && p() && e('common'); // 获取旗舰版扩展的模块路径
r(strpos($extPath['common'], DS . 'extension' . DS . 'max' . DS) !== false) && p() && e('1'); // 检查旗舰版 common 扩展路径

$tester->dev->config->edition = 'ipd';
$extPath = $tester->dev->getModuleExtPath();
r(strpos($extPath['common'], DS . 'extension' . DS . 'ipd' . DS) !== false) && p() && e('1'); // 检查 IPD 版 common 扩展路径
