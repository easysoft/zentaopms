#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::checkIncompatible();
timeout=0
cid=16449

- 步骤1：正常插件版本输入 @1
- 步骤2：空版本数组输入 @1
- 步骤3：多个插件版本输入 @1
- 步骤4：包含特殊字符的版本输入 @1
- 步骤5：包含数值版本号的输入 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('extension');

// 测试步骤1：正常插件版本输入
$versions = array('zentaopatch' => '1.0');
$apiVersions1 = $tester->extension->checkIncompatible($versions);

// 测试步骤2：空版本数组输入
$emptyVersions = array();
$apiVersions2 = $tester->extension->checkIncompatible($emptyVersions);

// 测试步骤3：多个插件版本输入
$multiVersions = array('plugin1' => '2.1', 'plugin2' => '3.0', 'zentaopatch' => '1.5');
$apiVersions3 = $tester->extension->checkIncompatible($multiVersions);

// 测试步骤4：包含特殊字符的版本输入
$specialVersions = array('test-plugin' => '1.0-beta', 'zentao_ext' => '2.0.1');
$apiVersions4 = $tester->extension->checkIncompatible($specialVersions);

// 测试步骤5：包含数值版本号的输入
$numericVersions = array('numericplugin' => '1.23', 'stringplugin' => '1.0');
$apiVersions5 = $tester->extension->checkIncompatible($numericVersions);

r(is_array($apiVersions1)) && p() && e('1'); // 步骤1：正常插件版本输入
r(is_array($apiVersions2)) && p() && e('1'); // 步骤2：空版本数组输入
r(is_array($apiVersions3)) && p() && e('1'); // 步骤3：多个插件版本输入
r(is_array($apiVersions4)) && p() && e('1'); // 步骤4：包含特殊字符的版本输入
r(is_array($apiVersions5)) && p() && e('1'); // 步骤5：包含数值版本号的输入