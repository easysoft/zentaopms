#!/usr/bin/env php
<?php

/**

title=测试 customModel::setMenuByConfig();
timeout=0
cid=0

- 测试步骤1：设置主菜单带有效数据 @array
- 测试步骤2：设置产品菜单带分支数据 @array
- 测试步骤3：设置地盘菜单排除评分功能 @array
- 测试步骤4：设置无效模块菜单 @array
- 测试步骤5：设置项目菜单 @array
- 测试步骤6：设置执行菜单 @array
- 测试步骤7：设置测试菜单 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(15);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();

r($customTester->setMenuByConfigTest('main'))        && p()      && e('array'); // 测试步骤1：设置主菜单带有效数据
r($customTester->setMenuByConfigTest('product'))     && p()      && e('array'); // 测试步骤2：设置产品菜单带分支数据
r($customTester->setMenuByConfigTest('my'))          && p()      && e('array'); // 测试步骤3：设置地盘菜单排除评分功能
r($customTester->setMenuByConfigTest('invalid'))     && p()      && e('array'); // 测试步骤4：设置无效模块菜单
r($customTester->setMenuByConfigTest('project'))     && p()      && e('array'); // 测试步骤5：设置项目菜单
r($customTester->setMenuByConfigTest('execution'))   && p()      && e('array'); // 测试步骤6：设置执行菜单
r($customTester->setMenuByConfigTest('qa'))          && p()      && e('array'); // 测试步骤7：设置测试菜单

zenData('lang')->gen(0);