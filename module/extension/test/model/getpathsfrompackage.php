#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::getPathsFromPackage();
timeout=0
cid=16465

- 测试步骤1：正常插件包提取路径列表 @array
- 测试步骤2：不存在的插件包代号 @array
- 测试步骤3：空字符串插件代号 @array
- 测试步骤4：特殊字符插件代号 @array
- 测试步骤5：数字型插件代号 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('extension');

// 测试步骤1：正常插件包提取路径列表
$result1 = $tester->extension->getPathsFromPackage('testextension');

// 测试步骤2：不存在的插件包代号
$result2 = $tester->extension->getPathsFromPackage('nonexistent_ext');

// 测试步骤3：空字符串插件代号
$result3 = $tester->extension->getPathsFromPackage('');

// 测试步骤4：特殊字符插件代号
$result4 = $tester->extension->getPathsFromPackage('special@#$%');

// 测试步骤5：数字型插件代号
$result5 = $tester->extension->getPathsFromPackage('123');

r(gettype($result1)) && p() && e('array'); // 测试步骤1：正常插件包提取路径列表
r(gettype($result2)) && p() && e('array'); // 测试步骤2：不存在的插件包代号
r(gettype($result3)) && p() && e('array'); // 测试步骤3：空字符串插件代号
r(gettype($result4)) && p() && e('array'); // 测试步骤4：特殊字符插件代号
r(gettype($result5)) && p() && e('array'); // 测试步骤5：数字型插件代号