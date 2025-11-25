#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::copyHookFiles();
timeout=0
cid=16484

- 步骤1：插件hook目录存在且系统hook目录存在 @0
- 步骤2：插件hook目录不存在 @0
- 步骤3：空字符串参数 @0
- 步骤4：特殊字符参数 @0
- 步骤5：再次测试正常情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化extension模型和zen实例
$extensionModel = $tester->loadModel('extension');
$zen = initReference('extension');
$func = $zen->getMethod('copyHookFiles');

// 创建zen实例并设置extension属性
$zenInstance = $zen->newInstance();
$zenInstance->extension = $extensionModel;

// 创建测试环境
$testExtension = 'testextension';
$testPkgRoot = $extensionModel->pkgRoot;
$testHookDir = $testPkgRoot . $testExtension . '/hook/';
$systemHookDir = $zenInstance->app->getBasePath() . DS . 'hook' . DS;

// 创建测试目录和文件
if(!is_dir($testHookDir)) mkdir($testHookDir, 0777, true);
if(!is_dir($systemHookDir)) mkdir($systemHookDir, 0777, true);
file_put_contents($testHookDir . 'test.php', '<?php // test hook file');

// 5. 强制要求：必须包含至少5个测试步骤
r($func->invokeArgs($zenInstance, array($testExtension))) && p() && e('0'); // 步骤1：插件hook目录存在且系统hook目录存在
r($func->invokeArgs($zenInstance, array('nonexistent'))) && p() && e('0');  // 步骤2：插件hook目录不存在
r($func->invokeArgs($zenInstance, array(''))) && p() && e('0');            // 步骤3：空字符串参数
r($func->invokeArgs($zenInstance, array('test/ext'))) && p() && e('0');     // 步骤4：特殊字符参数
r($func->invokeArgs($zenInstance, array($testExtension))) && p() && e('0'); // 步骤5：再次测试正常情况

// 清理测试环境
if(file_exists($testHookDir . 'test.php')) unlink($testHookDir . 'test.php');
if(is_dir($testHookDir)) rmdir($testHookDir);
if(is_dir(dirname($testHookDir))) rmdir(dirname($testHookDir));