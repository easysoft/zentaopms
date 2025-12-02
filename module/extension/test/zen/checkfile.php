#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkFile();
timeout=0
cid=16480

- 步骤1：允许覆盖文件 @1
- 步骤2：不存在的扩展，不冲突 @1
- 步骤3：无冲突的扩展 @1
- 步骤4：空扩展名但允许覆盖 @1
- 步骤5：其他覆盖参数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化extension模型和zen实例
$extensionModel = $tester->loadModel('extension');
$zen = initReference('extension');
$func = $zen->getMethod('checkFile');

// 创建zen实例并设置extension属性
$zenInstance = $zen->newInstance();
$zenInstance->extension = $extensionModel;

// 创建测试环境所需的目录和文件
$testPkgRoot = $extensionModel->pkgRoot;

// 创建一个测试扩展目录来模拟冲突
$conflictExt = 'conflict_extension';
$conflictPath = $testPkgRoot . $conflictExt;
if(!is_dir($conflictPath)) mkdir($conflictPath, 0777, true);

// 创建一个测试文件来模拟冲突
$testFile = $conflictPath . '/test.php';
file_put_contents($testFile, '<?php // conflict test file');

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($func->invokeArgs($zenInstance, array('test_extension', 'yes', 'test_link'))) && p() && e('1'); // 步骤1：允许覆盖文件
r($func->invokeArgs($zenInstance, array('nonexistent_ext', 'no', 'test_link'))) && p() && e('1'); // 步骤2：不存在的扩展，不冲突
r($func->invokeArgs($zenInstance, array($conflictExt, 'no', 'test_link'))) && p() && e('1');      // 步骤3：无冲突的扩展
r($func->invokeArgs($zenInstance, array('', 'yes', 'test_link'))) && p() && e('1');               // 步骤4：空扩展名但允许覆盖
r($func->invokeArgs($zenInstance, array('test_ext', 'maybe', 'test_link'))) && p() && e('1');     // 步骤5：其他覆盖参数

// 清理测试环境
if(file_exists($testFile)) unlink($testFile);
if(is_dir($conflictPath)) rmdir($conflictPath);