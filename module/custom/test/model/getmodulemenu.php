#!/usr/bin/env php
<?php

/**

title=测试 customModel::getModuleMenu();
timeout=0
cid=15900

- 步骤1：正常获取主菜单 @0
- 步骤2：空参数默认处理 @0
- 步骤3：获取产品模块子菜单 @0
- 步骤4：获取my模块菜单 @0
- 步骤5：获取project模块菜单 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester = new customModelTest();

r($customTester->getModuleMenuTest('main'))    && p() && e('0'); // 步骤1：正常获取主菜单
r($customTester->getModuleMenuTest(''))        && p() && e('0'); // 步骤2：空参数默认处理
r($customTester->getModuleMenuTest('product')) && p() && e('0'); // 步骤3：获取产品模块子菜单
r($customTester->getModuleMenuTest('my'))      && p() && e('0'); // 步骤4：获取my模块菜单
r($customTester->getModuleMenuTest('project')) && p() && e('0'); // 步骤5：获取project模块菜单