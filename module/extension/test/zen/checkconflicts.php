#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkConflicts();
timeout=0
cid=0

- 执行extensionTest模块的checkConflictsTest方法，参数是$condition1, $installedExts1  @1
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition2, $installedExts2  @1
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition3, $installedExts3  @0
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition4, $installedExts4  @1
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition5, $installedExts5  @0
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition6, $installedExts6  @1
- 执行extensionTest模块的checkConflictsTest方法，参数是$condition7, $installedExts7  @1

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

// 测试步骤1:没有冲突配置(conflicts为空)
$condition1 = new stdclass();
$condition1->conflicts = array();
$installedExts1 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkConflictsTest($condition1, $installedExts1)) && p() && e('1');

// 测试步骤2:有冲突配置但没有安装冲突的插件
$condition2 = new stdclass();
$condition2->conflicts = array('plugin_x' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts2 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkConflictsTest($condition2, $installedExts2)) && p() && e('1');

// 测试步骤3:有冲突配置且安装了冲突的插件且版本在冲突范围内
$condition3 = new stdclass();
$condition3->conflicts = array('plugin_a' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts3 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkConflictsTest($condition3, $installedExts3)) && p() && e('0');

// 测试步骤4:有冲突配置且安装了冲突的插件但版本高于max范围
$condition4 = new stdclass();
$condition4->conflicts = array('plugin_d' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts4 = array('plugin_a' => $installedExt1, 'plugin_d' => $installedExt4);
r($extensionTest->checkConflictsTest($condition4, $installedExts4)) && p() && e('1');

// 测试步骤5:多个冲突插件的情况(其中一个冲突)
$condition5 = new stdclass();
$condition5->conflicts = array('plugin_a' => array('min' => '2.0.0', 'max' => '3.0.0'), 'plugin_b' => array('min' => '1.5.0', 'max' => '2.5.0'));
$installedExts5 = array('plugin_a' => $installedExt1, 'plugin_b' => $installedExt2);
r($extensionTest->checkConflictsTest($condition5, $installedExts5)) && p() && e('0');

// 测试步骤6:conflicts配置为空数组
$condition6 = new stdclass();
$condition6->conflicts = array();
$installedExts6 = array('plugin_a' => $installedExt1);
r($extensionTest->checkConflictsTest($condition6, $installedExts6)) && p() && e('1');

// 测试步骤7:已安装插件列表为空
$condition7 = new stdclass();
$condition7->conflicts = array('plugin_a' => array('min' => '1.0.0', 'max' => '2.0.0'));
$installedExts7 = array();
r($extensionTest->checkConflictsTest($condition7, $installedExts7)) && p() && e('1');