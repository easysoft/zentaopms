#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkDepends();
timeout=0
cid=16476

- 执行extensionTest模块的checkDependsTest方法，参数是$condition1, $installedExts1  @1
- 执行extensionTest模块的checkDependsTest方法，参数是$condition2, $installedExts2  @1
- 执行extensionTest模块的checkDependsTest方法，参数是$condition3, $installedExts3  @0
- 执行extensionTest模块的checkDependsTest方法，参数是$condition4, $installedExts4  @1
- 执行extensionTest模块的checkDependsTest方法，参数是$condition5, $installedExts5  @0
- 执行extensionTest模块的checkDependsTest方法，参数是$condition6, $installedExts6  @0
- 执行extensionTest模块的checkDependsTest方法，参数是$condition7, $installedExts7  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$extensionTest = new extensionZenTest();

// 准备测试数据:已安装的插件列表
$installedExt1 = new stdclass();
$installedExt1->code    = 'plugin_a';
$installedExt1->name    = 'Plugin A';
$installedExt1->version = '1.5.0';

$installedExt2 = new stdclass();
$installedExt2->code    = 'plugin_b';
$installedExt2->name    = 'Plugin B';
$installedExt2->version = '2.0.0';

$installedExt3 = new stdclass();
$installedExt3->code    = 'plugin_c';
$installedExt3->name    = 'Plugin C';
$installedExt3->version = '1.0.0';

$installedExt4 = new stdclass();
$installedExt4->code    = 'plugin_d';
$installedExt4->name    = 'Plugin D';
$installedExt4->version = '5.0.0';

// 测试步骤1:没有依赖配置(depends为空)
$condition1 = new stdclass();
$condition1->depends = array();
$installedExts1 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkDependsTest($condition1, $installedExts1)) && p() && e('1');

// 测试步骤2:有依赖配置且所有依赖插件都已安装且版本满足要求
$condition2 = new stdclass();
$condition2->depends = array('plugin_a' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts2 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkDependsTest($condition2, $installedExts2)) && p() && e('1');

// 测试步骤3:有依赖配置但依赖的插件未安装
$condition3 = new stdclass();
$condition3->depends = array('plugin_x' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts3 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkDependsTest($condition3, $installedExts3)) && p() && e('0');

// 测试步骤4:有依赖配置且插件已安装但版本低于min范围
$condition4 = new stdclass();
$condition4->depends = array('plugin_c' => array('min' => '2.0.0', 'max' => '3.0.0'));
$installedExts4 = array('plugin_c' => $installedExt3);
r($extensionTest->checkDependsTest($condition4, $installedExts4)) && p() && e('1');

// 测试步骤5:有依赖配置且插件已安装但版本高于max范围
$condition5 = new stdclass();
$condition5->depends = array('plugin_d' => array('min' => '1.0.0', 'max' => '3.0.0'));
$installedExts5 = array('plugin_d' => $installedExt4);
r($extensionTest->checkDependsTest($condition5, $installedExts5)) && p() && e('0');

// 测试步骤6:多个依赖插件其中一个未安装
$condition6 = new stdclass();
$condition6->depends = array('plugin_a' => array('min' => '1.0.0', 'max' => '2.0.0'), 'plugin_x' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts6 = array('plugin_a' => $installedExt1);
r($extensionTest->checkDependsTest($condition6, $installedExts6)) && p() && e('0');

// 测试步骤7:depends配置为空数组
$condition7 = new stdclass();
$condition7->depends = array();
$installedExts7 = array('plugin_a' => $installedExt1);
r($extensionTest->checkDependsTest($condition7, $installedExts7)) && p() && e('1');