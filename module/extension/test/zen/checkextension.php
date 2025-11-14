#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtension();
timeout=0
cid=16477

- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent_plugin', 'no', '', 'no', '', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent_plugin', 'yes', 'test_link', 'no', '', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent_plugin', 'no', '', 'yes', 'test_link', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent_plugin', 'yes', 'link1', 'yes', 'link2', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent_plugin', 'no', '', 'no', '', 'upgrade'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化测试实例
$extensionTest = new extensionZenTest();

// 测试步骤1:测试不存在的插件(包文件不存在的情况)
r($extensionTest->checkExtensionTest('nonexistent_plugin', 'no', '', 'no', '', 'install')) && p() && e('0');

// 测试步骤2:测试不存在的插件,但忽略兼容性检查
r($extensionTest->checkExtensionTest('nonexistent_plugin', 'yes', 'test_link', 'no', '', 'install')) && p() && e('0');

// 测试步骤3:测试不存在的插件,允许覆盖文件
r($extensionTest->checkExtensionTest('nonexistent_plugin', 'no', '', 'yes', 'test_link', 'install')) && p() && e('0');

// 测试步骤4:测试不存在的插件,忽略兼容性且允许覆盖文件
r($extensionTest->checkExtensionTest('nonexistent_plugin', 'yes', 'link1', 'yes', 'link2', 'install')) && p() && e('0');

// 测试步骤5:测试不存在的插件,安装类型为upgrade
r($extensionTest->checkExtensionTest('nonexistent_plugin', 'no', '', 'no', '', 'upgrade')) && p() && e('0');