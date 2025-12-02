#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::togglePackageDisable();
timeout=0
cid=16490

- 步骤1:插件目录不存在时返回true @1
- 步骤2:禁用插件返回true @1
- 步骤2:验证disabled文件已创建 @1
- 步骤3:重复禁用返回true @1
- 步骤3:验证disabled文件仍存在 @1
- 步骤4:激活插件返回true @1
- 步骤4:验证disabled文件已删除 @0
- 步骤5:激活未禁用的插件返回true @1
- 步骤6:插件目录不存在时返回true @1
- 步骤7:空字符串返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$extensionTest = new extensionZenTest();

/* 准备测试环境:创建测试插件目录 */
$testExtension = 'testplugin';
$pkgRoot = dirname(__FILE__, 5) . '/extension/pkg/';
$testExtensionPath = $pkgRoot . $testExtension;
if(!is_dir($pkgRoot)) mkdir($pkgRoot, 0777, true);
if(!is_dir($testExtensionPath)) mkdir($testExtensionPath, 0777, true);

/* 测试步骤1: 插件目录不存在时禁用插件 */
r($extensionTest->togglePackageDisableTest('nonexistent', 'disabled')) && p() && e('1'); // 步骤1:插件目录不存在时返回true

/* 测试步骤2: 插件目录存在时禁用插件 */
$disabledFile = $testExtensionPath . '/disabled';
if(file_exists($disabledFile)) unlink($disabledFile); // 清理环境
r($extensionTest->togglePackageDisableTest($testExtension, 'disabled')) && p() && e('1'); // 步骤2:禁用插件返回true
r(file_exists($disabledFile)) && p() && e('1'); // 步骤2:验证disabled文件已创建

/* 测试步骤3: 禁用已禁用的插件 */
r($extensionTest->togglePackageDisableTest($testExtension, 'disabled')) && p() && e('1'); // 步骤3:重复禁用返回true
r(file_exists($disabledFile)) && p() && e('1'); // 步骤3:验证disabled文件仍存在

/* 测试步骤4: 激活已禁用的插件 */
r($extensionTest->togglePackageDisableTest($testExtension, 'active')) && p() && e('1'); // 步骤4:激活插件返回true
r(file_exists($disabledFile)) && p() && e('0'); // 步骤4:验证disabled文件已删除

/* 测试步骤5: 激活未禁用的插件 */
r($extensionTest->togglePackageDisableTest($testExtension, 'active')) && p() && e('1'); // 步骤5:激活未禁用的插件返回true

/* 测试步骤6: 激活插件目录不存在的插件 */
r($extensionTest->togglePackageDisableTest('nonexistent2', 'active')) && p() && e('1'); // 步骤6:插件目录不存在时返回true

/* 测试步骤7: 使用空字符串作为插件代号 */
r($extensionTest->togglePackageDisableTest('', 'disabled')) && p() && e('1'); // 步骤7:空字符串返回true

/* 清理测试环境 */
if(file_exists($disabledFile)) unlink($disabledFile);
if(is_dir($testExtensionPath)) rmdir($testExtensionPath);