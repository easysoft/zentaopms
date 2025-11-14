#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::checkVersion();
timeout=0
cid=16450

- 测试步骤1：版本字符串为'all'时的检查 @1
- 测试步骤2：版本字符串不包含当前版本时的检查 @0
- 测试步骤3：版本字符串包含当前版本时的检查 @1
- 测试步骤4：空字符串版本检查 @0
- 测试步骤5：多版本列表中包含当前版本的检查 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('extension');

// 测试步骤1：版本字符串为'all'时的检查
$result1 = $tester->extension->checkVersion('all');

// 测试步骤2：版本字符串不包含当前版本时的检查
$result2 = $tester->extension->checkVersion('99.99.99');

// 测试步骤3：版本字符串包含当前版本时的检查
$result3 = $tester->extension->checkVersion($config->version);

// 测试步骤4：空字符串版本检查
$result4 = $tester->extension->checkVersion('');

// 测试步骤5：多版本列表中包含当前版本的检查
$result5 = $tester->extension->checkVersion('1.0,2.0,' . $config->version . ',3.0');

r($result1) && p() && e('1'); // 测试步骤1：版本字符串为'all'时的检查
r($result2) && p() && e('0'); // 测试步骤2：版本字符串不包含当前版本时的检查
r($result3) && p() && e('1'); // 测试步骤3：版本字符串包含当前版本时的检查
r($result4) && p() && e('0'); // 测试步骤4：空字符串版本检查
r($result5) && p() && e('1'); // 测试步骤5：多版本列表中包含当前版本的检查