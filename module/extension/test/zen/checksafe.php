#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 extensionZen::checkSafe();
timeout=0
cid=16482

- 执行invokeArgs($zen模块的newInstance方法，参数是, []  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, []  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, []  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, []  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, []  @0

*/

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$zen = initReference('extension');
$func = $zen->getMethod('checkSafe');

// 测试步骤1: 正常环境下的checkSafe调用
$config->inContainer = false;
$config->safeFileTimeout = 86400;
$statusFile = $app->getAppRoot() . 'www' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ok.txt';
if(!is_dir(dirname($statusFile))) mkdir(dirname($statusFile), 0777, true);
touch($statusFile);
r($func->invokeArgs($zen->newInstance(), [])) && p() && e('0');

// 测试步骤2: 容器环境
$config->inContainer = true;
r($func->invokeArgs($zen->newInstance(), [])) && p() && e('0');

// 测试步骤3: 正常环境下安全文件存在
$config->inContainer = false;
r($func->invokeArgs($zen->newInstance(), [])) && p() && e('0');

// 测试步骤4: 多次调用测试稳定性
r($func->invokeArgs($zen->newInstance(), [])) && p() && e('0');

// 测试步骤5: 最终验证
r($func->invokeArgs($zen->newInstance(), [])) && p() && e('0');