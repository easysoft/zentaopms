#!/usr/bin/env php
<?php

/**

title=测试 customModel::getMainMenu();
timeout=0
cid=15899

- 执行$result1 @array
- 执行$result2 @array
- 执行$result3 @array
- 执行customTester模块的getMainMenuTest方法  @1
- 执行$result4) === gettype($result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$customTester = new customModelTest();

// 步骤1：测试默认参数情况
$result1 = $customTester->getMainMenuTest();
r(gettype($result1)) && p() && e('array');

// 步骤2：测试主页菜单情况
$result2 = $customTester->getMainMenuTest(true);
r(gettype($result2)) && p() && e('array');

// 步骤3：测试普通菜单情况
$result3 = $customTester->getMainMenuTest(false);
r(gettype($result3)) && p() && e('array');

// 步骤4：测试返回数据结构
r(is_array($customTester->getMainMenuTest())) && p() && e('1');

// 步骤5：测试方法调用一致性
$result4 = $customTester->getMainMenuTest(false);
$result5 = $customTester->getMainMenuTest(false);
r(gettype($result4) === gettype($result5)) && p() && e('1');