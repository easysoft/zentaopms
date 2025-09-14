#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::getHookFile();
timeout=0
cid=0

- 步骤1：存在的插件和钩子文件 @/repo/zentaopms/extension/pkg/testextension/hook/preinstall.php
- 步骤2：存在的插件但不存在的钩子文件 @0
- 步骤3：不存在的插件 @0
- 步骤4：空字符串参数测试 @0
- 步骤5：特殊字符参数测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化extension模型和zen实例
$extensionModel = $tester->loadModel('extension');
$zen = initReference('extension');
$func = $zen->getMethod('getHookFile');

// 创建zen实例并设置extension属性
$zenInstance = $zen->newInstance();
$zenInstance->extension = $extensionModel;

// 创建测试环境所需的目录和文件
$testPkgRoot = $extensionModel->pkgRoot;

// 创建测试所需的目录结构
$testExtension = 'testextension';
$testHookDir = $testPkgRoot . $testExtension . '/hook/';
if(!is_dir($testHookDir)) mkdir($testHookDir, 0777, true);

// 创建测试钩子文件
$preinstallFile = $testHookDir . 'preinstall.php';
file_put_contents($preinstallFile, '<?php // test preinstall hook');

// 5. 强制要求：必须包含至少5个测试步骤
r($func->invokeArgs($zenInstance, array($testExtension, 'preinstall'))) && p() && e('/repo/zentaopms/extension/pkg/testextension/hook/preinstall.php'); // 步骤1：存在的插件和钩子文件
r($func->invokeArgs($zenInstance, array($testExtension, 'nonexistent'))) && p() && e('0'); // 步骤2：存在的插件但不存在的钩子文件
r($func->invokeArgs($zenInstance, array('nonexistent', 'preinstall'))) && p() && e('0'); // 步骤3：不存在的插件
r($func->invokeArgs($zenInstance, array('', 'preinstall'))) && p() && e('0'); // 步骤4：空字符串参数测试
r($func->invokeArgs($zenInstance, array('test/ext', '../preinstall'))) && p() && e('0'); // 步骤5：特殊字符参数测试

// 6. 清理测试环境
if(file_exists($preinstallFile)) unlink($preinstallFile);
if(is_dir($testHookDir)) rmdir($testHookDir);
if(is_dir(dirname($testHookDir))) rmdir(dirname($testHookDir));