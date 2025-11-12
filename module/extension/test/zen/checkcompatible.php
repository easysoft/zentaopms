#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkCompatible();
timeout=0
cid=0

- 执行extensionTest模块的checkCompatibleTest方法，参数是'test_ext1', $condition1, 'no', 'test_link', 'install'  @1
- 执行extensionTest模块的checkCompatibleTest方法，参数是'test_ext2', $condition2, 'no', 'test_link', 'install'  @0
- 执行extensionTest模块的checkCompatibleTest方法，参数是'test_ext3', $condition3, 'yes', 'test_link', 'install'  @1
- 执行extensionTest模块的checkCompatibleTest方法，参数是'test_ext4', $condition4, 'no', 'test_link', 'install'  @0
- 执行extensionTest模块的checkCompatibleTest方法，参数是'test_ext5', $condition5, 'no', 'test_link', 'install'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化测试实例
$extensionTest = new extensionZenTest();

// 获取当前ZenTao版本用于测试
$currentVersion = $config->version;

// 测试步骤1：正常兼容版本检查,不忽略兼容性
$condition1 = new stdclass();
$condition1->zentao = array('compatible' => $currentVersion, 'incompatible' => '');
r($extensionTest->checkCompatibleTest('test_ext1', $condition1, 'no', 'test_link', 'install')) && p() && e('1');

// 测试步骤2：不兼容版本检查(incompatible匹配)
$condition2 = new stdclass();
$condition2->zentao = array('compatible' => '', 'incompatible' => $currentVersion);
r($extensionTest->checkCompatibleTest('test_ext2', $condition2, 'no', 'test_link', 'install')) && p() && e('0');

// 测试步骤3：兼容版本不匹配但忽略兼容性检查
$condition3 = new stdclass();
$condition3->zentao = array('compatible' => '99.99.99', 'incompatible' => '');
r($extensionTest->checkCompatibleTest('test_ext3', $condition3, 'yes', 'test_link', 'install')) && p() && e('1');

// 测试步骤4：兼容版本不匹配且不忽略兼容性
$condition4 = new stdclass();
$condition4->zentao = array('compatible' => '99.99.99', 'incompatible' => '');
r($extensionTest->checkCompatibleTest('test_ext4', $condition4, 'no', 'test_link', 'install')) && p() && e('0');

// 测试步骤5：所有版本兼容测试(compatible='all')
$condition5 = new stdclass();
$condition5->zentao = array('compatible' => 'all', 'incompatible' => '');
r($extensionTest->checkCompatibleTest('test_ext5', $condition5, 'no', 'test_link', 'install')) && p() && e('1');